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

// Google Client 초기화
try {
    $client = new Google_Client();
} catch (Exception $e) {
    error_log("Google_Client 초기화 오류: " . $e->getMessage());
    http_response_code(500);
    echo "Google API 클라이언트 초기화에 실패했습니다.";
    exit;
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
        $redirectUri = 'http://127.0.0.1:8000/drive/index.php';
    } else {
        $redirectUri = 'https://8440.co.kr/drive/index.php';
    }
}

// 자격 증명 검증
if (!$clientId || !$clientSecret) {
    http_response_code(500);
    echo "Google Drive API 자격 증명이 설정되지 않았습니다. config/google_drive_credentials.php 파일을 작성하거나 환경변수를 설정하세요.";
    exit;
}

// Google Client 설정
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

/** @var string */
$driveScope = Google_Service_Drive::DRIVE;
$client->addScope($driveScope);

// 토큰 파일 경로
$tokenFile = getDocumentRoot() . '/tokens/token.json';

// 토큰 파일이 존재하는 경우
if (file_exists($tokenFile)) {
    $accessToken = json_decode(file_get_contents($tokenFile), true);
    $client->setAccessToken($accessToken);
    
    // 토큰이 만료된 경우
    if ($client->isAccessTokenExpired()) {
        if (isset($accessToken['refresh_token'])) {
            try {
                $newAccessToken = $client->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
                $client->setAccessToken($newAccessToken);
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
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($accessToken['error'])) {
            throw new Exception('Google API 인증 오류: ' . $accessToken['error']);
        }
        
        $client->setAccessToken($accessToken);
        
        // 토큰 디렉토리 생성
        if (!file_exists(dirname($tokenFile))) {
            mkdir(dirname($tokenFile), 0700, true);
        }
        
        file_put_contents($tokenFile, json_encode($accessToken));
        
        header('Location: ' . $client->getRedirectUri());
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

// Google Drive 파일 업로드 처리
try {
    $folderName = '미래기업';
    $folderId = null;
    
    // 폴더 검색
    $response = $service->files->listFiles(array(
        'q' => "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ));
    
    // 폴더가 존재하는지 확인
    if (count($response->files) > 0) {
        $folderId = $response->files[0]->id;
        echo "폴더 '미래기업'을 찾았습니다. 폴더 ID: " . htmlspecialchars($folderId) . "<br>";
    } else {
        // 폴더 생성
        /** @var Google_Service_Drive_DriveFile $folderMetadata */
        $folderMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder'
        ));
        $folder = $service->files->create($folderMetadata, array('fields' => 'id'));
        $folderId = $folder->id;
        echo "폴더 '미래기업'이 생성되었습니다. 폴더 ID: " . htmlspecialchars($folderId) . "<br>";
    }
    
    // 업로드할 파일 설정
    $filePath = getDocumentRoot() . '/index2.php';
    $fileName = 'index2.php';
    
    // 파일 존재 여부 확인
    if (!file_exists($filePath)) {
        throw new Exception("업로드할 파일을 찾을 수 없습니다: " . $filePath);
    }
    
    // 기존 파일 검색
    $existingFiles = $service->files->listFiles(array(
        'q' => "name='$fileName' and '$folderId' in parents and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ));
    
    // 기존 파일이 있으면 삭제
    if (count($existingFiles->files) > 0) {
        foreach ($existingFiles->files as $existingFile) {
            $service->files->delete($existingFile->id);
            echo "기존 파일 '" . htmlspecialchars($existingFile->name) . "'이 삭제되었습니다.<br>";
        }
    }
    
    // 파일 메타데이터 설정
    /** @var Google_Service_Drive_DriveFile $fileMetadata */
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $fileName,
        'parents' => array($folderId)
    ));
    
    $content = file_get_contents($filePath);
    $mimeType = mime_content_type($filePath);
    
    // 파일 업로드
    $uploadedFile = $service->files->create($fileMetadata, array(
        'data' => $content,
        'mimeType' => $mimeType,
        'uploadType' => 'multipart',
        'fields' => 'id'
    ));
    
    // 공개 권한 설정
    /** @var Google_Service_Drive_Permission $permission */
    $permission = new Google_Service_Drive_Permission(array(
        'type' => 'anyone',
        'role' => 'reader'
    ));
    $service->permissions->create($uploadedFile->id, $permission);
    
    echo "파일이 구글 드라이브의 '미래기업' 폴더에 업로드되었고, 모든 사용자가 접근할 수 있도록 설정되었습니다.<br>";
    echo "파일 ID: " . htmlspecialchars($uploadedFile->id) . "<br>";
    echo "파일명: " . htmlspecialchars($fileName) . "<br>";
} catch (Exception $e) {
    error_log("Google Drive 파일 업로드 오류: " . $e->getMessage());
    echo '<p style="color: red;">오류 발생: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
