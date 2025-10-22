<?php
/**
 * work_voc 모듈 - 댓글 삭제 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';
$ripple_num = $_REQUEST["ripple_num"] ?? '';

// 데이터베이스 연결
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    $sql = "DELETE FROM mirae8440.voc_ripple WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $ripple_num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();

    header("Location:" . getBaseUrl() . "/work_voc/view.php?num=$num&page=$page");

} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("delete ripple error: " . $ex->getMessage());
    echo "댓글 삭제 중 오류: " . $ex->getMessage();
    exit;
}
?>