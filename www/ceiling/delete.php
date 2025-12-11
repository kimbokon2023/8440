<?php
require_once __DIR__ . '/../bootstrap.php';

if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화
$DB = isset($_SESSION["DB"]) ? $_SESSION["DB"] : "";
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";
$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

// JSON 출력 헤더 설정
header("Content-Type: application/json");

// 요청 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : "";

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$upload_dir = '../uploads/';  // 물리적 저장위치

// 데이터 삭제 처리
try {
    $pdo->beginTransaction();
    
    $sql = "delete from mirae8440.ceiling where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
} catch (Exception $ex) {
    $pdo->rollBack();
    print "오류: " . $ex->getMessage();
}

// 응답 데이터 구성
$data = array(
    "num" => $num
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>