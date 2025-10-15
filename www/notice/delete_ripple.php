<?php
/**
 * 댓글 삭제 처리
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 요청 변수 초기화 및 검증
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '1';
$ripple_num = isset($_REQUEST["ripple_num"]) ? $_REQUEST["ripple_num"] : '';
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : '';

// 필수 파라미터 검증
if (empty($ripple_num)) {
    error_log("댓글 삭제 실패: ripple_num이 비어있음");
    die("오류: 삭제할 댓글 번호가 지정되지 않았습니다.");
}

if (empty($num) || empty($tablename)) {
    error_log("댓글 삭제 실패: num 또는 tablename이 비어있음");
    die("오류: 필수 파라미터가 누락되었습니다.");
}

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_path = '/notice/view.php';

require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    // 댓글 삭제
    $sql = "DELETE FROM mirae8440.notice_ripple WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $ripple_num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
    // 안전한 URL 파라미터 생성
    $params = http_build_query([
        'tablename' => $tablename,
        'num' => $num,
        'page' => $page
    ], '', '&', PHP_QUERY_RFC3986);
    
    // 리다이렉트 URL 구성
    $redirect_url = "{$protocol}://{$host}{$base_path}?{$params}";
    
    header("Location: {$redirect_url}");
    exit;
    
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("댓글 삭제 오류 (ripple_num: {$ripple_num}): " . $ex->getMessage());
    die("오류: 댓글 삭제 중 문제가 발생했습니다. 관리자에게 문의하세요.");
}
?>
