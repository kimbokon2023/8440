<?php
session_start();
header("Content-Type: application/json"); // JSON 응답을 위해 헤더 설정

isset($_REQUEST["company"])  ? $company = $_REQUEST["company"] : $company=""; 
isset($_REQUEST["content"])  ? $content = $_REQUEST["content"] : $content=""; 

$company .= '_' . $content;

// 데이터베이스 접속 설정
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 현재 시간 가져오기
    $currentTime = date('Y-m-d H:i:s');
    
    // 사용자 IP 주소 가져오기
    $userIP = $_SERVER['REMOTE_ADDR'];
    
    // 로그 메시지 작성
    $logMessage = $company. " IP: $userIP - Time: $currentTime";
    
    // 데이터베이스에 로그 저장
    $pdo->beginTransaction();
    $sql = "INSERT INTO mirae8440.autopanel (log) VALUES (:log)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':log', $logMessage, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();

    // 성공 메시지와 저장된 num 반환
    $num = $pdo->lastInsertId();
    $data = [
        'status' => 'success',        
        'log' => $logMessage
    ];
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} catch (PDOException $Exception) {
    $pdo->rollBack();
    $data = [
        'status' => 'error',
        'message' => $Exception->getMessage()
    ];
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
?>
