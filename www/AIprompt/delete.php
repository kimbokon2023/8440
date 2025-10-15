<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

header("Content-Type: application/json");

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 첨부파일 삭제
try {
    $pdo->beginTransaction();
    $sql1 = "delete from mirae8440.picuploads where parentnum = ? and tablename = ?";
    $stmh1 = $pdo->prepare($sql1);
    $stmh1->bindValue(1, $num, PDO::PARAM_STR);
    $stmh1->bindValue(2, $tablename, PDO::PARAM_STR);
    $stmh1->execute();

    $pdo->commit();
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("첨부파일 삭제 오류 (" . $tablename . "): " . $ex->getMessage());
}

// 메인 레코드 삭제
try {
    $pdo->beginTransaction();
    $sql = "delete from mirae8440." . $tablename . " where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("레코드 삭제 오류 (" . $tablename . "): " . $ex->getMessage());
}

// JSON 응답 데이터 생성
$data = array(
    "num" => $num
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>