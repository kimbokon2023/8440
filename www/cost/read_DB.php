<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? "";
$find = $_REQUEST["find"] ?? "";
$process = $_REQUEST["process"] ?? "전체";
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? "";
$year = $_REQUEST["year"] ?? "";
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";
$separate_date = $_REQUEST["separate_date"] ?? "";
$outputnum = $_REQUEST["outputnum"] ?? "";

// 변수 초기화
$num = "";

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $sql = "SELECT * FROM {$DB}.cost ORDER BY num DESC LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $num = $row["num"];
    } else {
        error_log("cost 테이블에 데이터가 없습니다.");
    }
} catch (PDOException $ex) {
    error_log("최근 발주 조회 오류: " . $ex->getMessage());
}

// 로컬/서버 환경에 따른 동적 리다이렉션
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$redirect_params = http_build_query(array(
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

if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    header("Location: http://{$host}/cost/view.php?{$redirect_params}");
} else {
    header("Location: http://8440.co.kr/cost/view.php?{$redirect_params}");
}
exit;

?>
