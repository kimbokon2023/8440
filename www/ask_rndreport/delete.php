<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = "eworks";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 첨부파일 삭제
try {
    $pdo->beginTransaction();
    
    $sql1 = "delete from {$DB}.picuploads where parentnum = ? and tablename = ? ";
    $stmh1 = $pdo->prepare($sql1);
    $stmh1->bindValue(1, $num, PDO::PARAM_STR);
    $stmh1->bindValue(2, 'request_etc', PDO::PARAM_STR);
    $stmh1->execute();

    $pdo->commit();
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("연구개발보고서 첨부파일 삭제 오류: " . $ex->getMessage());
}

// 데이터 soft delete
try {
    $pdo->beginTransaction();
    
    $sql = "update " . $DB . "." . $tablename . " set is_deleted = ? ";
    $sql .= " where num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, true, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);

    $stmh->execute();
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("연구개발보고서 삭제 오류: " . $ex->getMessage());
}

// 각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
    "num" => $num
);

// json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));
?>