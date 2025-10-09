<?php
require_once __DIR__ . '/bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// mysql 테이블 생성하기
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$sql = "CREATE TABLE IF NOT EXISTS mirae8440.steelcompany (
    num INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(20) NULL
)";

try {
    $result = $pdo->query($sql);
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Table steelcompany created successfully'
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $Exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error creating table: ' . $Exception->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

// PDO는 자동으로 연결이 종료되므로 close() 불필요
?>
