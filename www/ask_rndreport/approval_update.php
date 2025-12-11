<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';
header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$store = $_REQUEST["store"] ?? '';
$firstTime = $_REQUEST["firstTime"] ?? '';
$secondTime = $_REQUEST["secondTime"] ?? '';
$mode = $_REQUEST["mode"] ?? 'update';

// 결재 확인 정보 생성
$e_confirm = "최장중 이사 " . $firstTime . "!소현철 대표 " . $secondTime;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE {$DB}.eworks SET 
                e_confirm = ?,
                store = ?
            WHERE num = ? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->execute([
        $e_confirm, 
        $store, 
        $num
    ]);
    
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("연구개발 결재시간 업데이트 오류: " . $ex->getMessage());
    echo json_encode(["error" => $ex->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}

// 결과 반환
echo json_encode(["num" => $num, "mode" => $mode], JSON_UNESCAPED_UNICODE);
