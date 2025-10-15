<?php
/**
 * 외주 자료 CSV 다운로드
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location: {$base_url}/login/login_form.php");
    exit;
}

// 날짜 변환 함수
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}

// 체크박스 변수 초기화
$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : (isset($_POST["check"]) ? $_POST["check"] : '0');
$plan_output_check = isset($_REQUEST["plan_output_check"]) ? $_REQUEST["plan_output_check"] : (isset($_POST["plan_output_check"]) ? $_POST["plan_output_check"] : '0');
$output_check = isset($_REQUEST["output_check"]) ? $_REQUEST["output_check"] : (isset($_POST["output_check"]) ? $_POST["output_check"] : '0');
$team_check = isset($_REQUEST["team_check"]) ? $_REQUEST["team_check"] : (isset($_POST["team_check"]) ? $_POST["team_check"] : '0');
$measure_check = isset($_REQUEST["measure_check"]) ? $_REQUEST["measure_check"] : (isset($_POST["measure_check"]) ? $_POST["measure_check"] : '0');

// 페이지 변수
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

// 정렬 관련 변수
$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : 0;
$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : 0;
$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : 0;

// 정렬 로직
if (isset($_REQUEST["sortof"])) {
    if ($sortof == 1 && $stable == 0) {
        $cursort = ($cursort != 1) ? 1 : 2;
    }
    if ($sortof == 2 && $stable == 0) {
        $cursort = ($cursort != 3) ? 3 : 4;
    }
    if ($sortof == 3 && $stable == 0) {
        $cursort = ($cursort != 5) ? 5 : 6;
    }
    if ($sortof == 4 && $stable == 0) {
        $cursort = ($cursort != 7) ? 7 : 8;
    }
    if ($sortof == 5 && $stable == 0) {
        $cursort = ($cursort != 9) ? 9 : 10;
    }
    if ($sortof == 6 && $stable == 0) {
        $cursort = ($cursort != 11) ? 11 : 12;
    }
} else {
    $sortof = 0;
    $cursort = 0;
}

// 기타 변수 초기화
$sum = [];
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : '';
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : '';
$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : '';
$process = isset($_REQUEST["process"]) ? $_REQUEST["process"] : '';
$asprocess = isset($_REQUEST["asprocess"]) ? $_REQUEST["asprocess"] : '';
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : '';
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : '';
$up_fromdate = isset($_REQUEST["up_fromdate"]) ? $_REQUEST["up_fromdate"] : '';
$up_todate = isset($_REQUEST["up_todate"]) ? $_REQUEST["up_todate"] : '';
$separate_date = isset($_REQUEST["separate_date"]) ? $_REQUEST["separate_date"] : '';
$view_table = isset($_REQUEST["view_table"]) ? $_REQUEST["view_table"] : '';

// 날짜 설정
if (empty($fromdate)) {
    $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$orderby = " ORDER BY orderday DESC";
$now = date("Y-m-d");

// SQL 쿼리 생성 (Prepared Statement 사용)
$sql = '';
$params = [];

if ($mode == "search") {
    if (empty($search)) {
        $sql = "SELECT * FROM mirae8440.oem WHERE orderday BETWEEN DATE(?) AND DATE(?)" . $orderby;
        $params = [$fromdate, $Transtodate];
    } else {
        $sql = "SELECT * FROM mirae8440.oem WHERE ((workplacename LIKE ?) OR (firstordman LIKE ?) OR (secondordman LIKE ?) OR (chargedman LIKE ?) ";
        $sql .= "OR (delicompany LIKE ?) OR (hpi LIKE ?) OR (firstord LIKE ?) OR (secondord LIKE ?) OR (worker LIKE ?) OR (memo LIKE ?)) AND (workday BETWEEN DATE(?) AND DATE(?))" . $orderby;
        $searchTerm = "%{$search}%";
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $fromdate, $Transtodate];
    }
} else {
    // 기본값: mode가 없을 때도 쿼리 제공
    $sql = "SELECT * FROM mirae8440.oem WHERE orderday BETWEEN DATE(?) AND DATE(?)" . $orderby;
    $params = [$fromdate, $Transtodate];
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열 변수 초기화
$counter = 0;
$csv_dump = [];
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;

try {
    $stmh = $pdo->prepare($sql);
    
    // 파라미터 바인딩
    foreach ($params as $index => $param) {
        $stmh->bindValue($index + 1, $param, PDO::PARAM_STR);
    }
    
    $stmh->execute();
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $workplacename = $row["workplacename"];
        $firstord = $row["firstord"];
        $secondord = $row["secondord"];
        $orderday = $row["orderday"];
        $deadline = $row["deadline"];
        $demand = $row["demand"];
        $type1 = $row["type1"];
        $inseung1 = $row["inseung1"];
        $su = $row["su"];
        $bon_su = $row["bon_su"];
        $lc_su = $row["lc_su"];
        $etc_su = $row["etc_su"];
        $car_insize1 = $row["car_insize1"];
        $memo = $row["memo"];
        
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        
        $csv_dump[$counter] = '';
        $csv_dump[$counter] .= $orderday . ",";
        $csv_dump[$counter] .= $firstord . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $secondord) . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $workplacename) . ",";
        $csv_dump[$counter] .= $deadline . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $type1) . ",";
        $csv_dump[$counter] .= $inseung1 . ",";
        $csv_dump[$counter] .= $su . ",";
        $csv_dump[$counter] .= $lc_su . ",";
        $csv_dump[$counter] .= $etc_su . ",";
        $csv_dump[$counter] .= $car_insize1 . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $memo) . ",";
        
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("외주 CSV 데이터 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}

$all_sum = $sum1 + $sum2 + $sum3;
?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자료 CSV 저장</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<form name="board_form" id="board_form" method="post" action="call_csv.php?mode=search&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&asprocess=<?= htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8') ?>&fromdate=<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>&todate=<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>&up_fromdate=<?= htmlspecialchars($up_fromdate, ENT_QUOTES, 'UTF-8') ?>&up_todate=<?= htmlspecialchars($up_todate, ENT_QUOTES, 'UTF-8') ?>&separate_date=<?= htmlspecialchars($separate_date, ENT_QUOTES, 'UTF-8') ?>&view_table=<?= htmlspecialchars($view_table, ENT_QUOTES, 'UTF-8') ?>">
    
    <h2>&nbsp;&nbsp;&nbsp;&nbsp; CSV파일로 저장하기</h2>
    
    <div id="list_search">
        <div id="list_search111">
            <?php if ($separate_date == "1") { ?>
                &nbsp; 입출고일 <input type="radio" checked name="separate_date" value="1">
                &nbsp; 접수일 <input type="radio" name="separate_date" value="2">
            <?php } ?>
            
            <?php if ($separate_date == "2") { ?>
                &nbsp; 입출고일 <input type="radio" name="separate_date" value="1">
                &nbsp; 접수일 <input type="radio" checked name="separate_date" value="2">
            <?php } ?>
            
            <input id="prepremonth" type="button" onclick="prepre_month()" value="전전월">
            <input id="premonth" type="button" onclick="pre_month()" value="전월">
            <input type="date" id="fromdate" name="fromdate" size="12" value="<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 시작일">부터
            <input type="date" id="todate" name="todate" size="12" value="<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 끝">까지
            <input id="thismonth" type="button" onclick="this_month()" value="당월">
            <input id="thisyear" type="button" onclick="this_year()" value="당해년도">
        </div>
        
        <div id="list_search2"><img src="../img/select_search.gif" alt="Search"></div>
        <div id="list_search4"><input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"></div>
        <div id="list_search5"><input type="image" src="../img/list_search_button.gif" alt="Search Button"></div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <br>
        
        <div class="clear"></div>
        <br>
        
        <h3>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 검색버튼을 클릭 후 '엑셀CSV저장'을 클릭해 주세요.
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
            <button type="button" class="btn btn-secondary" id="downloadcsvBtn">CSV 엑셀 다운로드</button>&nbsp;&nbsp;&nbsp;
        </h3>
        <br>
    </div>
</form>

<div class="clear"></div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var arr = <?php echo json_encode($csv_dump, JSON_UNESCAPED_UNICODE); ?>;
    var counter = <?php echo json_encode($counter, JSON_UNESCAPED_UNICODE); ?>;
    var totalSum = 0;
    
    $(document).ready(function() {
        $("#downloadcsvBtn").click(function() {
            Do_gridexport();
        });
    });
    
    /**
     * CSV 파일 다운로드
     */
    function Do_gridexport() {
        var csvContent = "data:text/csv;charset=utf-8,\uFEFF";
        
        // 헤더 넣기
        var row = "";
        row += "번호,날짜,매입처,발주처,현장명,업체납기,타입,인승,수량,L/C,기타,Car Insize,기타(메모)";
        csvContent += row + "\r\n";
        
        var COLNUM = 14;
        for (var i = 0; i < counter; i++) {
            var row = "";
            row += (i + 1) + ',';
            var tmp = String(arr[i]);
            tmp = tmp.replace(/undefined/gi, "");
            row += tmp.replace(/#/gi, " ");
            csvContent += row + "\r\n";
        }
        
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "miraeCSV_OutOEMData.csv");
        document.body.appendChild(link);
        link.click();
    }
    
    /**
     * 숫자 포맷팅 함수
     */
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
    
    /**
     * 전전월
     */
    function prepre_month() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        mm = mm - 2;
        if (mm < 1) {
            mm = '12';
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        if (mm >= 12) {
            yyyy = yyyy - 1;
        }
        
        var frompreyear = yyyy + '-' + mm + '-01';
        var tmp = 0;
        
        switch (Number(mm)) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                tmp = 30;
                break;
        }
        
        var topreyear = yyyy + '-' + mm + '-' + tmp;
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    }
    
    /**
     * 전월
     */
    function pre_month() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        mm = mm - 1;
        if (mm < 1) {
            mm = '12';
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        if (mm >= 12) {
            yyyy = yyyy - 1;
        }
        
        var frompreyear = yyyy + '-' + mm + '-01';
        var tmp = 0;
        
        switch (Number(mm)) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                tmp = 30;
                break;
        }
        
        var topreyear = yyyy + '-' + mm + '-' + tmp;
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    }
    
    /**
     * 당해월
     */
    function this_month() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        if (mm < 10) {
            mm = '0' + mm;
        }
        
        var frompreyear = yyyy + '-' + mm + '-01';
        var tmp = 0;
        
        switch (Number(mm)) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                tmp = 30;
                break;
        }
        
        var topreyear = yyyy + '-' + mm + '-' + tmp;
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    }
    
    /**
     * 당해년도
     */
    function this_year() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        if (mm < 10) {
            mm = '0' + mm;
        }
        
        var frompreyear = yyyy + '-01' + '-01';
        var tmp = 0;
        
        switch (Number(mm)) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                tmp = 30;
                break;
        }
        
        var topreyear = yyyy + '-' + mm + '-' + dd;
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    }
    
})();
</script>

</body>
</html>
