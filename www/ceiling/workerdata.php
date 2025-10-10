<?php
header("Content-Type: application/json"); // JSON을 사용하기 위해 필요한 구문

// Initialize request variables
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$weekend = $_REQUEST["weekend"] ?? '';

// 콤마를 없애주는 함수
function conv_num($num)
{
    $number = (int)str_replace(',', '', $num);
    return $number;
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 현재 날짜와 시간
$now = date("Y-m-d");
$nowtime = date("H:i:s");

// 조건절 조합
$where = " ";

// 기간을 정하는 구간
$fromdatetmp = date('Y-m-d', strtotime($fromdate . "0 day")); // 납기일 기준

$common = " WHERE deadline >= date('$fromdatetmp') ORDER BY deadline "; // 출고예정일이 현재일보다 클때 조건

$sql = "SELECT * FROM mirae8440.ceiling " . $common;

// Initialize arrays
$date_arr = array();
$tmp = array();
array_push($tmp, $sql);

try {
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql); // 검색조건에 맞는글 stmh

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $secondord = $row["secondord"] ?? '';
        $deadline = $row["deadline"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $bon_su = $row["bon_su"] ?? '';
        $lc_su = $row["lc_su"] ?? '';
        $etc_su = $row["etc_su"] ?? '';

        if ($bon_su != "") {
            $secondord .= ", 본 " . $bon_su;
        }
        if ($lc_su != "") {
            $secondord .= ", LC " . $lc_su;
        }
        if ($etc_su != "") {
            $secondord .= ", 기타 " . $etc_su;
        }

        if ($fromdatetmp == $deadline) {
            array_push($date_arr, $secondord, $workplacename);
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
    "weekend" => $weekend,
    "date_arr" => $date_arr,
);

// JSON 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));
?>