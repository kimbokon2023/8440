<?php
/**
 * IMAP 연결 및 폴더 목록 조회 테스트 스크립트
 */
require_once __DIR__ . '/../bootstrap.php';

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if ($level > 5) {
    die("권한이 없습니다.");
}

// 계정 정보 로드
$secretPath = __DIR__ . '/../secret/webmail.txt';
if (!file_exists($secretPath)) {
    die("메일 설정 파일을 찾을 수 없습니다.");
}

$lines = file($secretPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$username = '';
$password = '';

foreach ($lines as $line) {
    $parts = explode(':', $line, 2);
    if (count($parts) === 2) {
        $key = trim($parts[0]);
        $value = trim($parts[1]);
        
        if ($key === 'id') {
            $username = $value;
        } elseif ($key === 'password') {
            $password = $value;
        }
    }
}

// 도메인 추가
$fullEmail = $username;
if (!filter_var($fullEmail, FILTER_VALIDATE_EMAIL)) {
    $fullEmail .= '@8440.co.kr';
}

echo "<h3>IMAP 연결 테스트</h3>";
echo "User: " . $fullEmail . "<br>";

// 테스트할 호스트 목록
$hosts = [
    '{webmail.8440.co.kr:993/imap/ssl}',
    '{webmail.8440.co.kr:143/imap/notls}',
    '{mail.8440.co.kr:993/imap/ssl}',
    '{ssl.cafe24.com:993/imap/ssl}' // Cafe24 공용
];

foreach ($hosts as $host) {
    echo "<hr>Testing Host: <strong>$host</strong><br>";
    
    $mbox = @imap_open($host, $fullEmail, $password);
    
    if ($mbox) {
        echo "<span style='color:green'>연결 성공!</span><br>";
        
        echo "<strong>폴더 목록:</strong><br>";
        $folders = imap_list($mbox, $host, "*");
        
        if ($folders) {
            foreach ($folders as $folder) {
                echo $folder . "<br>";
            }
        } else {
            echo "폴더를 가져올 수 없습니다.<br>";
        }
        
        imap_close($mbox);
        break; // 성공하면 중단
    } else {
        echo "<span style='color:red'>연결 실패: " . imap_last_error() . "</span><br>";
    }
}
?>
