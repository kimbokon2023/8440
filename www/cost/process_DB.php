<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level >= 8) {
    echo "<script> alert('관리자 승인이 필요합니다.') </script>";
    sleep(2);
    
    // 로컬/서버 환경에 따른 동적 리다이렉션
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://" . $host . "/login/logout.php");
    } else {
        header("Location: http://8440.co.kr/login/logout.php");
    }
    exit;
}

// 요청 파라미터 초기화
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? "";
$num = $_REQUEST["num"] ?? "";
$search = $_REQUEST["search"] ?? "";
$find = $_REQUEST["find"] ?? "";
$process = $_REQUEST["process"] ?? "전체";
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";
$separate_date = $_REQUEST["separate_date"] ?? "";
$code = $_REQUEST["code"] ?? "";
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? "";
$year = $_REQUEST["year"] ?? "";

// 처리 변수 초기화
$which = '3';
$indate = date("Y-m-d");   // 현재일자 변수지정

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 입고완료 처리
try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE {$DB}.request SET which = ?, indate = ? WHERE num = ? LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $which, PDO::PARAM_STR);
    $stmh->bindValue(2, $indate, PDO::PARAM_STR);
    $stmh->bindValue(3, $num, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("입고완료 처리 오류: " . $ex->getMessage());
}

// 로컬/서버 환경에 따른 동적 리다이렉션
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$redirect_params = http_build_query(array(
    'num' => $num,
    'page' => $page,
    'search' => $search,
    'find' => $find,
    'process' => $process,
    'yearcheckbox' => $yearcheckbox,
    'year' => $year,
    'fromdate' => $fromdate,
    'todate' => $todate,
    'separate_date' => $separate_date
));

if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    header("Location: http://{$host}/request/view.php?{$redirect_params}");
} else {
    header("Location: http://8440.co.kr/request/view.php?{$redirect_params}");
}

?>