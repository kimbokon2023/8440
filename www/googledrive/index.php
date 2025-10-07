<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

session_start();

// Google OAuth2 클라이언트 설정
$client = new Google_Client();
$client->setClientId('590016082672-nbj1qblqunvl2fcplt6eupcu7db4jqtm.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://8440.co.kr/googledrive/index.php'); // 리디렉션 URI
$client->addScope(Google_Service_Drive::DRIVE);

// 토큰 파일 경로 설정
$tokenFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/token.json';

// 토큰 파일이 존재하는지 확인하고 유효한지 검사
if (file_exists($tokenFile) && $accessToken = json_decode(file_get_contents($tokenFile), true)) {
    // 유효한 JSON 형식의 토큰이 있는 경우 설정
    $client->setAccessToken($accessToken);

    // 만약 토큰이 만료되었으면 리프레시 토큰을 사용해 갱신하고 파일에 다시 저장
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($tokenFile, json_encode($client->getAccessToken()));
    }
} else {
    // 토큰 파일이 없거나 JSON 형식이 잘못된 경우, OAuth 인증 절차 수행
    if (!isset($_GET['code'])) {
        $authUrl = $client->createAuthUrl();
        echo "<a href='$authUrl'>구글 드라이브 인증</a>";
        exit;
    } else {
        // 사용자가 인증을 완료하고 돌아오면 액세스 토큰을 받아서 파일에 저장
        $client->authenticate($_GET['code']);
        $accessToken = $client->getAccessToken();

        // 새로 발급받은 토큰을 파일에 저장
        file_put_contents($tokenFile, json_encode($accessToken));
        
        header('Location: ' . filter_var($client->getRedirectUri(), FILTER_SANITIZE_URL));
        exit;
    }
}

// Google Drive 서비스 초기화 및 파일 업로드
$service = new Google_Service_Drive($client);

// 여기에서 파일 업로드 등 Google Drive API 호출 수행

?>
