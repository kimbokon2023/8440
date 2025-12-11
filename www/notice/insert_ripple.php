<?php
/**
 * 댓글 등록 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$userid = $_SESSION["userid"] ?? '';
$username = $_SESSION["name"] ?? '';
$usernick = $_SESSION["nick"] ?? '';

// 요청 변수 초기화 및 검증
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '1';
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : '';
$ripple_content = isset($_REQUEST["ripple_content"]) ? $_REQUEST["ripple_content"] : '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_path = '/notice/view.php';

// 필수 파라미터 검증
if (empty($num)) {
    error_log("댓글 등록 실패: num이 비어있음");
    die("오류: 게시글 번호가 지정되지 않았습니다.");
}

if (empty($tablename)) {
    error_log("댓글 등록 실패: tablename이 비어있음");
    die("오류: 테이블명이 지정되지 않았습니다.");
}

if (empty($ripple_content)) {
    error_log("댓글 등록 실패: 댓글 내용이 비어있음");
    die("오류: 댓글 내용을 입력해주세요.");
}

if (empty($userid)) {
    error_log("댓글 등록 실패: 로그인되지 않음");
    die("오류: 로그인이 필요합니다.");
}

require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO mirae8440.notice_ripple (parent, id, name, nick, content, regist_day) ";
    $sql .= "VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->bindValue(2, $userid, PDO::PARAM_STR);
    $stmh->bindValue(3, $username, PDO::PARAM_STR);
    $stmh->bindValue(4, $usernick, PDO::PARAM_STR);
    $stmh->bindValue(5, $ripple_content, PDO::PARAM_STR);
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
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("댓글 등록 오류 (num: {$num}): " . $ex->getMessage());
    die("오류: 댓글 등록 중 문제가 발생했습니다. 관리자에게 문의하세요.");
}
?>
