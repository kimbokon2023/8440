<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? ''; // 검색어
$find = $_REQUEST["find"] ?? ''; // 검색항목
$page = $_REQUEST["page"] ?? 1; // 페이지번호
$process = $_REQUEST["process"] ?? ''; // 진행현황
$fromdate = $_REQUEST["fromdate"] ?? ''; // 기간 시작일
$todate = $_REQUEST["todate"] ?? ''; // 기간 종료일
$separate_date = $_REQUEST["separate_date"] ?? '';
$year = $_REQUEST["year"] ?? ''; // 년도 체크박스
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$outputnum = $_REQUEST["outputnum"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $sql = "select * from mirae8440.automan order by num desc limit 1";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $num = $row["num"];
    }
} catch (PDOException $ex) {
    error_log("Automan 마지막 레코드 조회 오류: " . $ex->getMessage());
}

// 로컬/서버 환경에 따른 동적 리다이렉션
$host = $_SERVER['HTTP_HOST'] ?? '';
$protocol = 'http://';

if (strpos($host, 'localhost') === false && strpos($host, '127.0.0.1') === false) {
    $host = '8440.co.kr';
    $protocol = 'http://';
}

$params = http_build_query(array(
    'num' => $num,
    'outputnum' => $outputnum,
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

header("Location: " . $protocol . $host . "/automan/view.php?" . $params);
exit;
?>
