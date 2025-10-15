<?php
// 이 파일을 google_drive_credentials.php로 복사한 후 실제 Google OAuth 클라이언트 정보를 입력하세요.
// google_drive_credentials.php는 .gitignore에 의해 추적되지 않으며, 민감 정보를 절대 커밋하지 마세요.

// 로컬/서버 환경에 따른 동적 redirect_uri 설정
$host = $_SERVER['HTTP_HOST'] ?? '';
$protocol = 'http://';

if (strpos($host, 'localhost') === false && strpos($host, '127.0.0.1') === false) {
    $host = '8440.co.kr';
    $protocol = 'https://';
}

return array(
    'client_id' => 'your-client-id.apps.googleusercontent.com',
    'client_secret' => 'your-client-secret',
    'redirect_uri' => $protocol . $host . '/drive/index.php',
);
