<?php

require_once getDocumentRoot() . '/vendor/autoload.php';

session_start();

// Google OAuth2 클라이언트 설정
$client = new Google_Client();
$client->setClientId('201614692575-nbik0u921jg5826f4e9j22pd6mfbmuhr.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://8440.co.kr/drive/auth.php'); // 고정된 리디렉션 URI

// 인증 후 필요한 위치로 리다이렉트
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: https://8440.co.kr/drive/upload.php'); // 인증 후 이동할 페이지
    exit;
}

$client->addScope(Google_Service_Drive::DRIVE);

// 토큰 파일 경로 설정
$tokenFile = getDocumentRoot() . '/tokens/token.json';

if (file_exists($tokenFile)) {
    $accessToken = json_decode(file_get_contents($tokenFile), true);
    $client->setAccessToken($accessToken);
    
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($tokenFile, json_encode($client->getAccessToken()));
    }
} else {
    if (!isset($_GET['code'])) {
        $authUrl = $client->createAuthUrl();
        echo "<a href='$authUrl'>구글 드라이브 인증</a>";
        exit;
    } else {
        $client->authenticate($_GET['code']);
        $accessToken = $client->getAccessToken();

        if (!file_exists(dirname($tokenFile))) {
            mkdir(dirname($tokenFile), 0700, true);
        }
        file_put_contents($tokenFile, json_encode($accessToken));

        header('Location: ' . filter_var($client->getRedirectUri(), FILTER_SANITIZE_URL));
        exit;
    }
}

// Google Drive 서비스 초기화
$service = new Google_Service_Drive($client);

try {
    // '미래기업' 폴더 ID 가져오기 또는 생성
    $folderName = '미래기업';
    $folderId = null;

    // '미래기업' 폴더 검색
    $response = $service->files->listFiles([
        'q' => "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)',
    ]);

    if (count($response->files) > 0) {
        $folderId = $response->files[0]->id;
    } else {
        // 폴더가 없으면 생성
        $folderMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder'
        ]);
        $folder = $service->files->create($folderMetadata, ['fields' => 'id']);
        $folderId = $folder->id;
        echo "폴더 '미래기업'이 생성되었습니다. 폴더 ID: " . $folderId . "<br>";
    }

    // '미래기업' 폴더 내 'imgwork' 하위 폴더 생성
    $imgworkFolderName = 'imgwork';
    $imgworkFolderId = null;

    // 'imgwork' 폴더가 이미 있는지 확인
    $response = $service->files->listFiles([
        'q' => "name='$imgworkFolderName' and mimeType='application/vnd.google-apps.folder' and '$folderId' in parents and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)',
    ]);

    if (count($response->files) > 0) {
        $imgworkFolderId = $response->files[0]->id;
    } else {
        // 'imgwork' 하위 폴더가 없으면 생성
        $imgworkFolderMetadata = new Google_Service_Drive_DriveFile([
            'name' => $imgworkFolderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$folderId]
        ]);
        $imgworkFolder = $service->files->create($imgworkFolderMetadata, ['fields' => 'id']);
        $imgworkFolderId = $imgworkFolder->id;
        echo "하위 폴더 'imgwork'이 생성되었습니다. 폴더 ID: " . $imgworkFolderId . "<br>";
    }

    // /imgwork 폴더에서 모든 파일을 Google Drive의 '미래기업/imgwork'로 업로드
    $imgworkPath = getDocumentRoot() . '/imgwork';
    $files = scandir($imgworkPath);

    echo "<h2>Google Drive에 파일 업로드:</h2>";
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;  // 현재 및 상위 디렉토리 무시

        $filePath = "$imgworkPath/$file";
        $mimeType = mime_content_type($filePath);

        // Google Drive 파일 메타데이터 설정
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $file,
            'parents' => [$imgworkFolderId]
        ]);
        $content = file_get_contents($filePath);

        // Google Drive에 파일 업로드
        try {
            $uploadedFile = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink'
            ]);

            // 모든 사용자가 볼 수 있도록 파일 권한 설정
            $permission = new Google_Service_Drive_Permission([
                'type' => 'anyone',
                'role' => 'reader'
            ]);
            $service->permissions->create($uploadedFile->id, $permission);

            echo "파일 '{$file}'이 업로드되었습니다. 링크: <a href='" . $uploadedFile->webViewLink . "' target='_blank'>보기</a><br>";
        } catch (Exception $e) {
            echo "파일 '{$file}' 업로드 중 오류 발생: " . $e->getMessage() . "<br>";
        }
    }

    // '미래기업/imgwork' 폴더 내의 파일 목록 가져오기
    $files = $service->files->listFiles([
        'q' => "'$imgworkFolderId' in parents and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name, mimeType, createdTime, webViewLink)',
    ]);

    echo "<h2>'미래기업/imgwork' 폴더 내의 파일 목록:</h2>";
    echo "<ul>";

    if (count($files->files) > 0) {
        foreach ($files->files as $file) {
            $fileType = $file->mimeType === 'application/vnd.google-apps.folder' ? '폴더' : '파일';
            echo "<li>";
            echo "$fileType: <a href='" . $file->webViewLink . "' target='_blank'>" . $file->name . "</a><br>";
            echo "파일 ID: " . $file->id . "<br>";
            echo "생성 시간: " . $file->createdTime . "<br><br>";
            echo "</li>";
        }
    } else {
        echo "<li>폴더에 파일이 없습니다.</li>";
    }

    echo "</ul>";

} catch (Exception $e) {
    echo '오류 발생: ' . $e->getMessage();
}

?>
