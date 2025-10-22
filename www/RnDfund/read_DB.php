<?php
/**
 * RnDfund 마지막 레코드 조회 및 리다이렉트
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$page = $_REQUEST["page"] ?? '';
$process = $_REQUEST["process"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$year = $_REQUEST["year"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';

try {
    $sql = "select * from " . $DB . ".RnDfund order by num desc limit 1";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $num = $row["num"] ?? '';

    if ($num) {
        echo "마지막 레코드 번호: " . $num;
    }
} catch (PDOException $Exception) {
    error_log("마지막 레코드 조회 오류: " . $Exception->getMessage());
    echo "오류: " . $Exception->getMessage();
}

// 환경에 따른 리다이렉트 URL 설정
$redirectUrl = getBaseUrl() . "/RnDfund/view.php?num=" . $num . "&page=" . $page . "&search=" . $search . "&find=" . $find . "&process=" . $process . "&yearcheckbox=" . $yearcheckbox . "&year=" . $year . "&fromdate=" . $fromdate . "&todate=" . $todate . "&separate_date=" . $separate_date;

header("Location: " . $redirectUrl);
exit;
?>  
	
