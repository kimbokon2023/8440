<?php
/**
 * Google Drive OAuth2 인증 및 토큰 관리
 * Google Drive API를 사용하기 위한 OAuth2 인증을 처리합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// Composer 자동 로드 확인
$vendorAutoload = getDocumentRoot() . '/vendor/autoload.php';
if (!file_exists($vendorAutoload)) {
    die('Composer autoload not found. Please run: composer install');
}
require_once $vendorAutoload;

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 변수 초기화
$client = null;
$service = null;
$accessToken = null;
$authUrl = '';
$tokenFile = getDocumentRoot() . '/tokens/token.json';

try {
    // Google OAuth2 클라이언트 설정
    $client = new Google_Client();
    
    // 클라이언트 설정 (환경별 분기)
    $serverName = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    $client->setClientId('590016082672-nbj1qblqunvl2fcplt6eupcu7db4jqtm.apps.googleusercontent.com');
    $client->setClientSecret('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');
    
    // 동적 리디렉션 URI 설정
    if (stripos($serverName, 'localhost') !== false || stripos($serverName, '127.0.0.1') !== false) {
        $redirectUri = 'http://' . $serverName . '/googledrive/index.php';
    } else {
        $redirectUri = 'https://8440.co.kr/googledrive/index.php';
    }
    $client->setRedirectUri($redirectUri);
    $client->addScope(Google_Service_Drive::DRIVE);
    
    // 토큰 디렉토리 확인 및 생성
    $tokenDir = dirname($tokenFile);
    if (!is_dir($tokenDir)) {
        if (!mkdir($tokenDir, 0755, true)) {
            throw new Exception('토큰 디렉토리를 생성할 수 없습니다: ' . htmlspecialchars($tokenDir, ENT_QUOTES, 'UTF-8'));
        }
    }
    
    // 토큰 파일이 존재하는지 확인하고 유효한지 검사
    if (file_exists($tokenFile)) {
        $tokenContent = file_get_contents($tokenFile);
        if ($tokenContent === false) {
            throw new Exception('토큰 파일을 읽을 수 없습니다.');
        }
        
        $accessToken = json_decode($tokenContent, true);
        
        // JSON 디코딩 오류 확인
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Invalid token JSON in googledrive/index.php: ' . json_last_error_msg());
            // 잘못된 토큰 파일 삭제
            if (file_exists($tokenFile)) {
                unlink($tokenFile);
            }
            $accessToken = null;
        }
    }
    
    // 유효한 토큰이 있는 경우
    if ($accessToken !== null && is_array($accessToken)) {
        $client->setAccessToken($accessToken);
        
        // 토큰이 만료되었으면 리프레시 토큰을 사용해 갱신
        if ($client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken();
            
            if ($refreshToken) {
                try {
                    $newAccessToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
                    
                    // 새 토큰 저장
                    if (!isset($newAccessToken['error'])) {
                        if (file_put_contents($tokenFile, json_encode($newAccessToken)) === false) {
                            error_log('Failed to save refreshed token in googledrive/index.php');
                        }
                    } else {
                        error_log('Token refresh error in googledrive/index.php: ' . print_r($newAccessToken, true));
                        // 리프레시 실패 시 재인증 필요
                        $accessToken = null;
                    }
                } catch (Exception $ex) {
                    error_log('Token refresh exception in googledrive/index.php: ' . $ex->getMessage());
                    $accessToken = null;
                }
            } else {
                // 리프레시 토큰이 없으면 재인증 필요
                $accessToken = null;
            }
        }
        
        // 인증 완료 - Google Drive 서비스 초기화
        if ($accessToken !== null) {
            /** @var Google_Service_Drive $service */
            $service = new Google_Service_Drive($client);
            
            echo '<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive 인증 완료</title>
    <style>
        body {
            font-family: "Malgun Gothic", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
        }
        .success-icon {
            font-size: 60px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: left;
        }
        .info strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1>Google Drive 인증 완료</h1>
        <p>Google Drive API 사용이 가능합니다.</p>
        <div class="info">
            <p><strong>토큰 파일:</strong> ' . htmlspecialchars($tokenFile, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>리디렉션 URI:</strong> ' . htmlspecialchars($redirectUri, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>상태:</strong> 정상</p>
        </div>
    </div>
</body>
</html>';
            exit;
        }
    }
    
    // 토큰이 없거나 만료된 경우 - OAuth 인증 절차 수행
    if (!isset($_GET['code'])) {
        // 인증 URL 생성 및 표시
        $authUrl = $client->createAuthUrl();
        
        echo '<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive 인증</title>
    <style>
        body {
            font-family: "Malgun Gothic", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
        }
        .drive-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .auth-button {
            display: inline-block;
            padding: 15px 40px;
            background: #4285F4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .auth-button:hover {
            background: #357ae8;
        }
        .info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: left;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="drive-icon">📁</div>
        <h1>Google Drive 인증</h1>
        <p>Google Drive API를 사용하려면 먼저 인증이 필요합니다.</p>
        <a href="' . htmlspecialchars($authUrl, ENT_QUOTES, 'UTF-8') . '" class="auth-button">구글 드라이브 인증</a>
        <div class="info">
            <p><strong>참고:</strong> 인증 후 이 페이지로 자동 리디렉션됩니다.</p>
            <p><strong>리디렉션 URI:</strong> ' . htmlspecialchars($redirectUri, ENT_QUOTES, 'UTF-8') . '</p>
        </div>
    </div>
</body>
</html>';
        exit;
        
    } else {
        // 사용자가 인증을 완료하고 돌아온 경우
        $authCode = $_GET['code'];
        
        try {
            $client->authenticate($authCode);
            $accessToken = $client->getAccessToken();
            
            // 새로 발급받은 토큰을 파일에 저장
            if (file_put_contents($tokenFile, json_encode($accessToken)) === false) {
                throw new Exception('토큰 파일을 저장할 수 없습니다.');
            }
            
            // 권한 설정
            chmod($tokenFile, 0600);
            
            // 리디렉션
            $redirectUrl = filter_var($client->getRedirectUri(), FILTER_SANITIZE_URL);
            header('Location: ' . $redirectUrl);
            exit;
            
        } catch (Exception $ex) {
            error_log('OAuth authentication error in googledrive/index.php: ' . $ex->getMessage());
            die('<html>
<head>
    <meta charset="UTF-8">
    <title>인증 오류</title>
    <style>
        body { font-family: "Malgun Gothic", sans-serif; padding: 40px; text-align: center; }
        .error { color: #d32f2f; font-size: 18px; }
    </style>
</head>
<body>
    <h1>인증 오류</h1>
    <p class="error">Google 인증 중 오류가 발생했습니다.</p>
    <p>잠시 후 다시 시도해주세요.</p>
    <p><a href="index.php">다시 시도</a></p>
</body>
</html>');
        }
    }
    
} catch (Exception $ex) {
    error_log('Critical error in googledrive/index.php: ' . $ex->getMessage());
    die('<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>시스템 오류</title>
    <style>
        body {
            font-family: "Malgun Gothic", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        .error-icon {
            font-size: 60px;
            color: #d32f2f;
            margin-bottom: 20px;
        }
        h1 {
            color: #d32f2f;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠</div>
        <h1>시스템 오류</h1>
        <p>Google Drive 연동 중 오류가 발생했습니다.</p>
        <p>관리자에게 문의하세요.</p>
        <a href="index.php" class="back-link">다시 시도</a>
    </div>
</body>
</html>');
}
?>
