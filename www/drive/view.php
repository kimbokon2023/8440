<?php

require_once getDocumentRoot() . '/vendor/autoload.php';

session_start();

// Google OAuth2 클라이언트 설정
$client = new Google_Client();
$client->setClientId('201614692575-nbik0u921jg5826f4e9j22pd6mfbmuhr.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://8440.co.kr/drive/view.php'); // 리디렉션 URI
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
    // '미래기업' 폴더 ID 가져오기
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
        echo "폴더 '미래기업'이 존재하지 않습니다.";
        exit;
    }

    // '미래기업' 폴더 내의 파일 목록 가져오기
    $files = $service->files->listFiles([
        'q' => "'$folderId' in parents and trashed=false",
        'spaces' => 'drive',
        'fields' => 'files(id, name, mimeType, createdTime, webViewLink)',
    ]);

    echo "<h2>'미래기업' 폴더 내의 파일 목록:</h2>";
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
