<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 원자재 구매 요청 입고 완료 처리
 * 
 * which 값을 '3' (입고완료)로 업데이트하고 입고일자를 기록합니다.
 */

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;

// 권한 체크
if (!isset($_SESSION["level"]) || $level >= 8) {
    echo "<script>alert('관리자 승인이 필요합니다.');</script>";
    sleep(2);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

// 세션 변수
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 변수 초기화
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$code = $_REQUEST["code"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';

// 필수 데이터 체크
if (empty($num)) {
    echo "<script>alert('처리할 데이터 번호가 없습니다.');</script>";
    header("Location:" . getBaseUrl() . "/request/");
    exit;
}

// 입고 완료 상태 설정
$which = '3';
$indate = date("Y-m-d");  // 현재일자

// 데이터베이스 업데이트
try {
    $pdo->beginTransaction();

    $sql = "update " . $DB . ".request set which = ?, indate = ? where num = ? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $which, PDO::PARAM_STR);
    $stmh->bindValue(2, $indate, PDO::PARAM_STR);
    $stmh->bindValue(3, $num, PDO::PARAM_INT);

    $stmh->execute();
    $pdo->commit();

    // 성공 - 상세 페이지로 리다이렉트
    $redirectUrl = getBaseUrl() . "/request/view.php?" . http_build_query(array(
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
    
    header("Location:" . $redirectUrl);
    exit;
} catch (PDOException $Exception) {
    $pdo->rollBack();
    
    // 에러 로그 기록
    error_log("입고 완료 처리 오류: " . $Exception->getMessage());
    
    // 에러 메시지 출력 후 리다이렉트
    echo "<script>alert('입고 완료 처리 중 오류가 발생했습니다.');</script>";
    header("Location:" . getBaseUrl() . "/request/view.php?num=" . $num);
    exit;
}
?>
