<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

header("Content-Type: application/json");

// 요청 파라미터 초기화
$company = $_REQUEST["company"] ?? '';
$content = $_REQUEST["content"] ?? '';

$company .= '_' . $content;

// 데이터베이스 접속 설정
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 현재 시간 가져오기
    $currentTime = date('Y-m-d H:i:s');

    // 사용자 IP 주소 가져오기
    $userIP = $_SERVER['REMOTE_ADDR'] ?? '';

    // 로그 메시지 작성
    $logMessage = $company . " IP: $userIP - Time: $currentTime";

    // 데이터베이스에 로그 저장
    $pdo->beginTransaction();
    $sql = "INSERT INTO mirae8440.autopanel (log) VALUES (:log)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':log', $logMessage, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();

    // 성공 메시지와 저장된 num 반환
    $num = $pdo->lastInsertId();
    $data = array(
        'status' => 'success',
        'log' => $logMessage
    );
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("Autopanel 로그 저장 오류: " . $ex->getMessage());
    $data = array(
        'status' => 'error',
        'message' => $ex->getMessage()
    );
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
?>
