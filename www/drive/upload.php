<?php
require_once __DIR__ . '/../common/functions.php';

// Composer Autoload
$autoloadPath = getDocumentRoot() . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    http_response_code(500);
    echo "Composer 패키지가 설치되지 않았습니다. 'composer install'을 실행하세요.";
    exit;
}

require_once $autoloadPath;

// 세션 시작
if (!isset($_SESSION)) {
    session_start();
}

// 자격 증명 로드
$credentialsPath = getDocumentRoot() . '/config/google_drive_credentials.php';
$clientId = getenv('GOOGLE_DRIVE_CLIENT_ID') ?: null;
$clientSecret = getenv('GOOGLE_DRIVE_CLIENT_SECRET') ?: null;
$redirectUri = getenv('GOOGLE_DRIVE_REDIRECT_URI') ?: null;

if (file_exists($credentialsPath)) {
    $credentials = require $credentialsPath;
    $clientId = $credentials['client_id'] ?? $clientId;
    $clientSecret = $credentials['client_secret'] ?? $clientSecret;
    $redirectUri = $credentials['redirect_uri'] ?? $redirectUri;
}

// 동적 리다이렉트 URI 설정 (로컬/서버 환경)
if (!$redirectUri) {
    $isLocal = (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || 
                strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
    
    if ($isLocal) {
        $redirectUri = 'http://127.0.0.1:8000/drive/upload.php';
    } else {
        $redirectUri = 'https://8440.co.kr/drive/upload.php';
    }
}

// 자격 증명 검증
if (!$clientId || !$clientSecret) {
    http_response_code(500);
    echo "Google Drive API 자격 증명이 설정되지 않았습니다. config/google_drive_credentials.php 파일을 작성하거나 환경변수를 설정하세요.";
    exit;
}

// Google Client 초기화
try {
    $client = new Google_Client();
    $client->setClientId($clientId);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    
    /** @var string */
    $driveScope = Google_Service_Drive::DRIVE;
    $client->addScope($driveScope);
} catch (Exception $e) {
    error_log("Google_Client 초기화 오류: " . $e->getMessage());
    http_response_code(500);
    echo "Google API 클라이언트 초기화에 실패했습니다.";
    exit;
}

// 토큰 파일 경로 설정
$tokenFile = getDocumentRoot() . '/tokens/token.json';

// 토큰 파일이 존재하는 경우
if (file_exists($tokenFile)) {
    $accessToken = json_decode(file_get_contents($tokenFile), true);
    $client->setAccessToken($accessToken);
    
    // 토큰이 만료된 경우
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            try {
                $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                file_put_contents($tokenFile, json_encode($newAccessToken));
            } catch (Exception $e) {
                error_log("토큰 갱신 오류: " . $e->getMessage());
                unlink($tokenFile);
                header('Location: ' . $client->createAuthUrl());
                exit;
            }
        } else {
            unlink($tokenFile);
            header('Location: ' . $client->createAuthUrl());
            exit;
        }
    }
}
// 인증 코드가 있는 경우
elseif (isset($_GET['code'])) {
    try {
        $client->authenticate($_GET['code']);
        $accessToken = $client->getAccessToken();
        
        // 토큰 디렉토리 생성
        if (!file_exists(dirname($tokenFile))) {
            mkdir(dirname($tokenFile), 0700, true);
        }
        
        file_put_contents($tokenFile, json_encode($accessToken));
        $_SESSION['access_token'] = $accessToken;
        
        header('Location: ' . filter_var($redirectUri, FILTER_SANITIZE_URL));
        exit;
    } catch (Exception $e) {
        error_log("인증 코드 처리 오류: " . $e->getMessage());
        die('Google API 인증 오류: ' . $e->getMessage());
    }
}
// 인증이 필요한 경우
else {
    echo "<h2>구글 드라이브 연동</h2>";
    echo "<p>구글 드라이브와 연동하려면 아래 버튼을 클릭하세요.</p>";
    echo "<a href='" . htmlspecialchars($client->createAuthUrl()) . "' class='btn btn-primary'>구글 드라이브 인증</a>";
    exit;
}

// Google Drive Service 초기화
try {
    /** @var Google_Service_Drive $service */
    $service = new Google_Service_Drive($client);
} catch (Exception $e) {
    error_log("Google_Service_Drive 초기화 오류: " . $e->getMessage());
    http_response_code(500);
    echo "Google Drive 서비스 초기화에 실패했습니다.";
    exit;
}

try {
    // '미래기업' 폴더 ID 가져오기 또는 생성
    $folderName = '미래기업';
    $folderId = null;
    
    // '미래기업' 폴더 검색
    $response = $service->files->listFiles(array(
        'q' => "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ));
    
    if (count($response->files) > 0) {
        $folderId = $response->files[0]->id;
        echo "폴더 '미래기업'을 찾았습니다. 폴더 ID: " . htmlspecialchars($folderId) . "<br>";
    } else {
        // 폴더가 없으면 생성
        /** @var Google_Service_Drive_DriveFile $folderMetadata */
        $folderMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder'
        ));
        $folder = $service->files->create($folderMetadata, array('fields' => 'id'));
        $folderId = $folder->id;
        echo "폴더 '미래기업'이 생성되었습니다. 폴더 ID: " . htmlspecialchars($folderId) . "<br>";
    }
    
    // '미래기업' 폴더 내 'imgwork' 하위 폴더 생성
    $imgworkFolderName = 'imgwork';
    $imgworkFolderId = null;
    
    // 'imgwork' 폴더가 이미 있는지 확인
    $response = $service->files->listFiles(array(
        'q' => "name='$imgworkFolderName' and mimeType='application/vnd.google-apps.folder' and '$folderId' in parents and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ));
    
    if (count($response->files) > 0) {
        $imgworkFolderId = $response->files[0]->id;
        echo "하위 폴더 'imgwork'을 찾았습니다. 폴더 ID: " . htmlspecialchars($imgworkFolderId) . "<br>";
    } else {
        // 'imgwork' 하위 폴더가 없으면 생성
        /** @var Google_Service_Drive_DriveFile $imgworkFolderMetadata */
        $imgworkFolderMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $imgworkFolderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array($folderId)
        ));
        $imgworkFolder = $service->files->create($imgworkFolderMetadata, array('fields' => 'id'));
        $imgworkFolderId = $imgworkFolder->id;
        echo "하위 폴더 'imgwork'이 생성되었습니다. 폴더 ID: " . htmlspecialchars($imgworkFolderId) . "<br>";
    }

    // /imgwork 폴더에서 모든 파일을 Google Drive의 '미래기업/imgwork'로 업로드
    $imgworkPath = getDocumentRoot() . '/imgwork';
    
    // 디렉토리 존재 여부 확인
    if (!is_dir($imgworkPath)) {
        throw new Exception("imgwork 폴더를 찾을 수 없습니다: " . $imgworkPath);
    }
    
    $files = scandir($imgworkPath);
    
    echo "<h2>Google Drive에 파일 업로드:</h2>";
    
    $uploadCount = 0;
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;  // 현재 및 상위 디렉토리 무시
        }
        
        $filePath = $imgworkPath . '/' . $file;
        
        // 파일인지 확인 (디렉토리 제외)
        if (!is_file($filePath)) {
            continue;
        }
        
        $mimeType = mime_content_type($filePath);
        
        // Google Drive 파일 메타데이터 설정
        /** @var Google_Service_Drive_DriveFile $fileMetadata */
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $file,
            'parents' => array($imgworkFolderId)
        ));
        
        $content = file_get_contents($filePath);
        
        // Google Drive에 파일 업로드
        try {
            $uploadedFile = $service->files->create($fileMetadata, array(
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink'
            ));
            
            // 모든 사용자가 볼 수 있도록 파일 권한 설정
            /** @var Google_Service_Drive_Permission $permission */
            $permission = new Google_Service_Drive_Permission(array(
                'type' => 'anyone',
                'role' => 'reader'
            ));
            $service->permissions->create($uploadedFile->id, $permission);
            
            echo "파일 '" . htmlspecialchars($file) . "'이 업로드되었습니다. ";
            echo "링크: <a href='" . htmlspecialchars($uploadedFile->webViewLink) . "' target='_blank'>보기</a><br>";
            
            $uploadCount++;
        } catch (Exception $e) {
            error_log("파일 업로드 오류 (" . $file . "): " . $e->getMessage());
            echo "파일 '" . htmlspecialchars($file) . "' 업로드 중 오류 발생: " . htmlspecialchars($e->getMessage()) . "<br>";
        }
    }
    
    echo "<p>총 " . $uploadCount . "개의 파일이 업로드되었습니다.</p>";
    
    // '미래기업/imgwork' 폴더 내의 파일 목록 가져오기
    $fileList = $service->files->listFiles(array(
        'q' => "'$imgworkFolderId' in parents and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name, mimeType, createdTime, webViewLink)'
    ));
    
    echo "<h2>'미래기업/imgwork' 폴더 내의 파일 목록:</h2>";
    echo "<ul>";
    
    if (count($fileList->files) > 0) {
        foreach ($fileList->files as $file) {
            $fileType = ($file->mimeType === 'application/vnd.google-apps.folder') ? '폴더' : '파일';
            echo "<li>";
            echo htmlspecialchars($fileType) . ": <a href='" . htmlspecialchars($file->webViewLink) . "' target='_blank'>" . htmlspecialchars($file->name) . "</a><br>";
            echo "파일 ID: " . htmlspecialchars($file->id) . "<br>";
            echo "생성 시간: " . htmlspecialchars($file->createdTime) . "<br><br>";
            echo "</li>";
        }
    } else {
        echo "<li>폴더에 파일이 없습니다.</li>";
    }
    
    echo "</ul>";
    
} catch (Exception $e) {
    error_log("Google Drive 파일 업로드 오류: " . $e->getMessage());
    echo '<p style="color: red;">오류 발생: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

?>
