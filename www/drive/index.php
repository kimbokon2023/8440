<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

session_start();

$client = new Google_Client();

$credentialsPath = $_SERVER['DOCUMENT_ROOT'] . '/config/google_drive_credentials.php';
$clientId = getenv('GOOGLE_DRIVE_CLIENT_ID') ?: null;
$clientSecret = getenv('GOOGLE_DRIVE_CLIENT_SECRET') ?: null;
$redirectUri = getenv('GOOGLE_DRIVE_REDIRECT_URI') ?: null;

if (file_exists($credentialsPath)) {
    $credentials = require $credentialsPath;
    $clientId = $credentials['client_id'] ?? $clientId;
    $clientSecret = $credentials['client_secret'] ?? $clientSecret;
    $redirectUri = $credentials['redirect_uri'] ?? $redirectUri;
}

$redirectUri = $redirectUri ?: 'https://8440.co.kr/drive/index.php';

if (!$clientId || !$clientSecret) {
    http_response_code(500);
    echo "Google Drive API 자격 증명이 설정되지 않았습니다. config/google_drive_credentials.php 파일을 작성하거나 환경변수를 설정하세요.";
    exit;
}

$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope(Google_Service_Drive::DRIVE);

$tokenFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/token.json';

if (file_exists($tokenFile)) {
    $accessToken = json_decode(file_get_contents($tokenFile), true);
    $client->setAccessToken($accessToken);

    if ($client->isAccessTokenExpired()) {
        if (isset($accessToken['refresh_token'])) {
            try {
                $newAccessToken = $client->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
                $client->setAccessToken($newAccessToken);
                file_put_contents($tokenFile, json_encode($newAccessToken));
            } catch (Exception $e) {
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
} elseif (isset($_GET['code'])) {
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($accessToken['error'])) {
        die('Google API 인증 오류: ' . $accessToken['error']);
    }
    $client->setAccessToken($accessToken);

    if (!file_exists(dirname($tokenFile))) {
        mkdir(dirname($tokenFile), 0700, true);
    }
    file_put_contents($tokenFile, json_encode($accessToken));

    header('Location: ' . $client->getRedirectUri());
    exit;
} else {
    echo "<a href='" . $client->createAuthUrl() . "'>구글 드라이브 인증</a>";
    exit;
}

$service = new Google_Service_Drive($client);

try {
    $folderName = '미래기업';
    $folderId = null;

    $response = $service->files->listFiles([
        'q' => "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)',
    ]);

    if (count($response->files) > 0) {
        $folderId = $response->files[0]->id;
    } else {
        $folderMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);
        $folder = $service->files->create($folderMetadata, ['fields' => 'id']);
        $folderId = $folder->id;
        echo "폴더 '미래기업'이 생성되었습니다. 폴더 ID: " . $folderId . "<br>";
    }

    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/index2.php';
    $fileName = 'index2.php';
    $existingFiles = $service->files->listFiles([
        'q' => "name='$fileName' and '$folderId' in parents and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name)',
    ]);

    if (count($existingFiles->files) > 0) {
        foreach ($existingFiles->files as $existingFile) {
            $service->files->delete($existingFile->id);
            echo "기존 파일 '" . $existingFile->name . "'이 삭제되었습니다.<br>";
        }
    }

    $fileMetadata = new Google_Service_Drive_DriveFile([
        'name' => $fileName,
        'parents' => [$folderId],
    ]);
    $content = file_get_contents($filePath);

    $uploadedFile = $service->files->create($fileMetadata, [
        'data' => $content,
        'mimeType' => mime_content_type($filePath),
        'uploadType' => 'multipart',
        'fields' => 'id',
    ]);

    $permission = new Google_Service_Drive_Permission([
        'type' => 'anyone',
        'role' => 'reader',
    ]);
    $service->permissions->create($uploadedFile->id, $permission);

    echo "파일이 구글 드라이브의 '미래기업' 폴더에 업로드되었고, 모든 사용자가 접근할 수 있도록 설정되었습니다. 파일 ID: " . $uploadedFile->id;
} catch (Exception $e) {
    echo '오류 발생: ' . $e->getMessage();
}
