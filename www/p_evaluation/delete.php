<?php
/**
 * 협력업체 평가표 - 삭제 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

header("Content-Type: application/json");

// 세션 시작
if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화 (?? '' 형태)
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

require_once includePath('lib/mydb.php');
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    $sql = "delete from mirae8440." . $tablename . " where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    print "오류: " . $Exception->getMessage();
}

$data = [
    'num' => $num,
    'tablename' => $tablename
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>