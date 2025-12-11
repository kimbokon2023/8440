<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 요청 파라미터 초기화
$which = $_REQUEST["which"] ?? '';
$search_opt = $_REQUEST["search_opt"] ?? '';
$displaySelect = $_REQUEST["displaySelect"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$proDate = $_REQUEST["proDate"] ?? '';
$writer = $_REQUEST["writer"] ?? '';
$amount = $_REQUEST["amount"] ?? '';
$memo = $_REQUEST["memo"] ?? '';

// 추가 파라미터 초기화 (리다이렉션에서 사용)
$outputnum = $_REQUEST["outputnum"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

if ($mode == "modify") {
    try {
        $pdo->beginTransaction();
        $sql = "update mirae8440.automan set proDate=?, writer=?, amount=?, memo=?, which=? ";
        $sql .= " where num=? LIMIT 1";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $proDate, PDO::PARAM_STR);
        $stmh->bindValue(2, $writer, PDO::PARAM_STR);
        $stmh->bindValue(3, $amount, PDO::PARAM_STR);
        $stmh->bindValue(4, $memo, PDO::PARAM_STR);
        $stmh->bindValue(5, $which, PDO::PARAM_STR);
        $stmh->bindValue(6, $num, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("Automan 수정 오류: " . $ex->getMessage());
    }
} else {
    try {
        $pdo->beginTransaction();

        $sql = "insert into mirae8440.automan(proDate, writer, amount, memo, which) ";
        $sql .= " values(?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $proDate, PDO::PARAM_STR);
        $stmh->bindValue(2, $writer, PDO::PARAM_STR);
        $stmh->bindValue(3, $amount, PDO::PARAM_STR);
        $stmh->bindValue(4, $memo, PDO::PARAM_STR);
        $stmh->bindValue(5, $which, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("Automan 등록 오류: " . $ex->getMessage());
    }
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

if ($mode == "not") {
    header("Location: " . $protocol . $host . "/automan/read_DB.php?" . $params);
} else {
    header("Location: " . $protocol . $host . "/automan/view.php?" . $params);
}
?>