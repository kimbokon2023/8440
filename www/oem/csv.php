<?php
/**
 * 외주 데이터 CSV 다운로드
 * 로컬 및 서버 환경 모두 지원
 */

// 날짜 변환 함수
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}

require_once("../lib/mydb.php");
$pdo = db_connect();

$common = " ORDER BY orderday DESC";
$sql = "SELECT * FROM mirae8440.oem" . $common;

// CSV 변수 초기화
$csv_dump = "";
$csv_dump .= "날짜,매입처,발주처,현장명,업체납기,타입,인승,수량,L/C,기타,Car Insize,기타(메모)";
$csv_dump .= "\r\n";

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $workplacename = $row["workplacename"];
        $firstord = $row["firstord"];
        $secondord = $row["secondord"];
        $orderday = $row["orderday"];
        $deadline = $row["deadline"];
        $demand = $row["demand"];
        $type = $row["type"];
        $inseung = $row["inseung"];
        $su = $row["su"];
        $bon_su = $row["bon_su"];
        $lc_su = $row["lc_su"];
        $etc_su = $row["etc_su"];
        $car_insize = $row["car_insize"];
        $memo = $row["memo"];
        
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        
        $csv_dump .= $orderday . ",";
        $csv_dump .= $firstord . ",";
        $csv_dump .= str_replace(",", "; ", $secondord) . ",";
        $csv_dump .= str_replace(",", "; ", $workplacename) . ",";
        $csv_dump .= $deadline . ",";
        $csv_dump .= str_replace(",", "; ", $type) . ",";
        $csv_dump .= $inseung . ",";
        $csv_dump .= $su . ",";
        $csv_dump .= $lc_su . ",";
        $csv_dump .= $etc_su . ",";
        $csv_dump .= $car_insize . ",";
        $csv_dump .= str_replace(",", "; ", $memo) . ",";
        $csv_dump .= "\r\n";
    }
} catch (PDOException $ex) {
    error_log("CSV 데이터 조회 오류: " . $ex->getMessage());
    die("오류: 데이터를 불러오는 중 문제가 발생했습니다.");
}

// 파일명 생성
$date = date("YmdHi");
$filename = "Outsorcing_CSV_" . $date . ".csv";

// CSV 다운로드 헤더 설정
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=" . $filename);
header("Content-Description: Generated Report");

// BOM 추가 (엑셀에서 한글 깨짐 방지)
echo "\xEF\xBB\xBF";
echo $csv_dump;
?>
