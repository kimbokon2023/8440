<?php

// CSV 다운로드를 위한 헤더 설정
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/vnd.ms-excel; charset=utf-8");

// 변수 초기화
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : "";
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : "";

// SQL 쿼리 설정
$orderby = " order by orderday desc ";
$sql = "select * from mirae8440.ceiling where orderday between date('$fromdate') and date('$todate')" . $orderby;

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// CSV 데이터 초기화
$csv_dump = "";
$csv_dump .= "날짜,원청,발주처,현장명,납기일,타입,인승,수량,본천장,L/C,기타,공기청정기,Car Insize,";
$csv_dump .= "\r\n";

// 합계 배열 초기화
$sum = array(0, 0, 0, 0, 0, 0);

// 날짜 변환 함수
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}
 
// 데이터 조회 및 CSV 생성
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
        $air_su = $row["air_su"];
        $car_insize = $row["car_insize"];
        
        // 합계 계산
        $sum[0] = $sum[0] + (int)$su;
        $sum[1] += (int)$bon_su;
        $sum[2] += (int)$lc_su;
        $sum[3] += (int)$etc_su;
        $sum[4] += (int)$air_su;
        $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
        
        // 날짜 변환
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        
        // CSV 데이터 추가
        $csv_dump .= $orderday . ",";
        $csv_dump .= $firstord . ",";
        $csv_dump .= str_replace(",", "; ", $secondord) . ",";
        $csv_dump .= str_replace(",", "; ", $workplacename) . ",";
        $csv_dump .= $deadline . ",";
        $csv_dump .= str_replace(",", "; ", $type) . ",";
        $csv_dump .= $inseung . ",";
        $csv_dump .= $su . ",";
        $csv_dump .= $bon_su . ",";
        $csv_dump .= $lc_su . ",";
        $csv_dump .= $etc_su . ",";
        $csv_dump .= $air_su . ",";
        $csv_dump .= $car_insize . ",";
        $csv_dump .= "\r\n";
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 파일명 생성
$date = date("YmdHi");
$filename = "ceiling_CSV_" . $date . ".csv";

// BOM 추가로 한글 깨짐 방지
echo "\xEF\xBB\xBF";
echo $csv_dump;

// 파일 다운로드 헤더
header("Content-Disposition: attachment; filename=$filename");
header("Content-Description: Generated Report");

?>

<script>
/*
// 사용되지 않는 클라이언트 사이드 CSV 생성 코드 (참고용)
// const data = grid.getData();
let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF 추가해서 해결함.

// header 넣기
let row = "";
row += '번호' + ',';
row += '출고일 ,';
row += '현장명 ,';
row += '원청 ,';
row += '발주처 ,';
row += '현장주소 ,';
row += '시공소장 ,';
row += '수량 ,';
row += '운송자 ,';
row += '비용 ';

csvContent += row + "\r\n";
console.log(rowNum);

const COLNUM = 9;
for (let i = 0; i < grid.getRowCount(); i++) {
    let row = "";
    row += (i + 1) + ',';
    for (let j = 1; j <= COLNUM; j++) {
        let tmp = String(grid.getValue(i, 'col' + j));
        tmp = tmp.replace(/undefined/gi, "");
        tmp = tmp.replace(/#/gi, " ");
        row += tmp.replace(/,/gi, "") + ',';
    }
    
    csvContent += row + "\r\n";
}

var encodedUri = encodeURI(csvContent);
var link = document.createElement("a");
link.setAttribute("href", encodedUri);
link.setAttribute("download", "miraeCSV_CeilingData.csv");
document.body.appendChild(link);
link.click();
*/
</script>			
