<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? 'Unknown';
$user_id = $_SESSION["userid"] ?? '';

header("Content-Type: application/json");

// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 데이터 삭제
try {
    $pdo->beginTransaction();
    $sql = "DELETE FROM mirae8440." . $tablename . " WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (Exception $ex) {
    $pdo->rollBack();
    print "오류: " . $ex->getMessage();
}

// JSON 응답 반환
$data = [
    "num" => $num
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);