<?php
session_start();

// 세션 변수 초기화
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location:http://8440.co.kr/login/login_form.php");
    exit;
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" type="text/css" href="../css/jexcel.css">
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <title> 미출고 데이터 추출 </title>
</head>

<?php

// REQUEST/POST 변수 초기화
$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : (isset($_POST["check"]) ? $_POST["check"] : '0'); 

$plan_output_check = isset($_REQUEST["plan_output_check"]) ? $_REQUEST["plan_output_check"] : (isset($_POST["plan_output_check"]) ? $_POST["plan_output_check"] : '0');
$output_check = isset($_REQUEST["output_check"]) ? $_REQUEST["output_check"] : (isset($_POST["output_check"]) ? $_POST["output_check"] : '0');
$team_check = isset($_REQUEST["team_check"]) ? $_REQUEST["team_check"] : (isset($_POST["team_check"]) ? $_POST["team_check"] : '0');
$measure_check = isset($_REQUEST["measure_check"]) ? $_REQUEST["measure_check"] : (isset($_POST["measure_check"]) ? $_POST["measure_check"] : '0');
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

// 디버그 출력 (필요시 주석 해제)
// print $plan_output_check;

// 정렬 관련 변수 초기화
$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : 0;
$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : 0;
$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : 0;

// 정렬 모드 결정
if (isset($_REQUEST["sortof"])) {
    if ($sortof == 1 && $stable == 0) { // 접수일 클릭
        $cursort = ($cursort != 1) ? 1 : 2;
    }
    if ($sortof == 2 && $stable == 0) { // 납기일 클릭
        $cursort = ($cursort != 3) ? 3 : 4;
    }
    if ($sortof == 3 && $stable == 0) { // 실측일 클릭
        $cursort = ($cursort != 5) ? 5 : 6;
    }
    if ($sortof == 4 && $stable == 0) { // 도면작성일 클릭
        $cursort = ($cursort != 7) ? 7 : 8;
    }
    if ($sortof == 5 && $stable == 0) { // 출고일 클릭
        $cursort = ($cursort != 9) ? 9 : 10;
    }
    if ($sortof == 6 && $stable == 0) { // 청구 클릭
        $cursort = ($cursort != 11) ? 11 : 12;
    }
} else {
    $sortof = 0;
    $cursort = 0;
}


// 기타 변수 초기화
$sum = array();
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : "";
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

// 기간 설정
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : "";
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : "";

if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 4);
    $fromdate = $fromdate . "-01-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

// 폼에서 사용되는 변수 초기화
$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : "";
$process = isset($_REQUEST["process"]) ? $_REQUEST["process"] : "";
$asprocess = isset($_REQUEST["asprocess"]) ? $_REQUEST["asprocess"] : "";
$up_fromdate = isset($_REQUEST["up_fromdate"]) ? $_REQUEST["up_fromdate"] : "";
$up_todate = isset($_REQUEST["up_todate"]) ? $_REQUEST["up_todate"] : "";
$separate_date = isset($_REQUEST["separate_date"]) ? $_REQUEST["separate_date"] : "";
$view_table = isset($_REQUEST["view_table"]) ? $_REQUEST["view_table"] : "";

// SQL 설정
$orderby = " order by orderday desc ";
$now = date("Y-m-d"); // 현재 날짜

// SQL 쿼리: 미출고 데이터
$sql = "select * from mirae8440.work where (workday='') or (workday='0000-00-00') " . $orderby;

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열 초기화
$counter = 0;
$workplacename_arr = array();
$address_arr = array();
$firstord_arr = array();
$firstordman_arr = array();
$firstordmantel_arr = array();
$secondord_arr = array();
$secondordman_arr = array();
$secondordmantel_arr = array();
$chargedman_arr = array();
$chargedmantel_arr = array();
$orderday_arr = array();
$measureday_arr = array();
$drawday_arr = array();
$deadline_arr = array();
$workday_arr = array();
$worker_arr = array();
$endworkday_arr = array();
$material1_arr = array();
$material2_arr = array();
$material3_arr = array();
$material4_arr = array();
$material5_arr = array();
$material6_arr = array();
$widejamb_arr = array();
$normaljamb_arr = array();
$smalljamb_arr = array();
$memo_arr = array();
$regist_day_arr = array();
$update_day_arr = array();
$demand_arr = array();
$startday_arr = array();
$testday_arr = array();
$hpi_arr = array();
$delicompany_arr = array();
$delipay_arr = array();
$sum_arr = array();

// 합계 변수 초기화
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$jamb_total = "";


// 데이터 조회
try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // 기본 정보
        $checkstep = $row["checkstep"];
        $workplacename = $row["workplacename"];
        $address = $row["address"];
        $firstord = $row["firstord"];
        $firstordman = $row["firstordman"];
        $firstordmantel = $row["firstordmantel"];
        $secondord = $row["secondord"];
        $secondordman = $row["secondordman"];
        $secondordmantel = $row["secondordmantel"];
        $chargedman = $row["chargedman"];
        $chargedmantel = $row["chargedmantel"];
        
        // 날짜 정보
        $orderday = $row["orderday"];
        $measureday = $row["measureday"];
        $drawday = $row["drawday"];
        $deadline = $row["deadline"];
        $workday = $row["workday"];
        $worker = $row["worker"];
        $endworkday = $row["endworkday"];
        $startday = $row["startday"];
        $testday = $row["testday"];
        $demand = $row["demand"];
        $regist_day = $row["regist_day"];
        $update_day = $row["update_day"];
        
        // 재질 정보
        $material1 = $row["material1"];
        $material2 = $row["material2"];
        $material3 = $row["material3"];
        $material4 = $row["material4"];
        $material5 = $row["material5"];
        $material6 = $row["material6"];
        
        // 제품 정보
        $widejamb = $row["widejamb"];
        $normaljamb = $row["normaljamb"];
        $smalljamb = $row["smalljamb"];
        $hpi = $row["hpi"];
        
        // 배송 정보
        $delicompany = $row["delicompany"];
        $delipay = $row["delipay"];
        
        // 메모
        $memo = $row["memo"];
        
        // 날짜 변환
        if ($orderday != "0000-00-00" && $orderday != "1970-01-01" && $orderday != "") {
            $orderday = date("Y-m-d", strtotime($orderday));
        } else {
            $orderday = "";
        }
        
        if ($measureday != "0000-00-00" && $measureday != "1970-01-01" && $measureday != "") {
            $measureday = date("Y-m-d", strtotime($measureday));
        } else {
            $measureday = "";
        }
        
        if ($drawday != "0000-00-00" && $drawday != "1970-01-01" && $drawday != "") {
            $drawday = date("Y-m-d", strtotime($drawday));
        } else {
            $drawday = "";
        }
        
        if ($deadline != "0000-00-00" && $deadline != "1970-01-01" && $deadline != "") {
            $deadline = date("Y-m-d", strtotime($deadline));
        } else {
            $deadline = "";
        }
        
        if ($workday != "0000-00-00" && $workday != "1970-01-01" && $workday != "") {
            $workday = date("Y-m-d", strtotime($workday));
        } else {
            $workday = "";
        }
        
        if ($endworkday != "0000-00-00" && $endworkday != "1970-01-01" && $endworkday != "") {
            $endworkday = date("Y-m-d", strtotime($endworkday));
        } else {
            $endworkday = "";
        }
        
        if ($demand != "0000-00-00" && $demand != "1970-01-01" && $demand != "") {
            $demand = date("Y-m-d", strtotime($demand));
        } else {
            $demand = "";
        }
        
        if ($startday != "0000-00-00" && $startday != "1970-01-01" && $startday != "") {
            $startday = date("Y-m-d", strtotime($startday));
        } else {
            $startday = "";
        }
        
        if ($testday != "0000-00-00" && $testday != "1970-01-01" && $testday != "") {
            $testday = date("Y-m-d", strtotime($testday));
        } else {
            $testday = "";
        }
        
        // 배열에 데이터 저장
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $firstord_arr[$counter] = $firstord;
        $firstordman_arr[$counter] = $firstordman;
        $firstordmantel_arr[$counter] = $firstordmantel;
        $secondord_arr[$counter] = $secondord;
        $secondordman_arr[$counter] = $secondordman;
        $secondordmantel_arr[$counter] = $secondordmantel;
        $chargedman_arr[$counter] = $chargedman;
        $chargedmantel_arr[$counter] = $chargedmantel;
        $orderday_arr[$counter] = $orderday;
        $measureday_arr[$counter] = $measureday;
        $drawday_arr[$counter] = $drawday;
        $deadline_arr[$counter] = $deadline;
        $workday_arr[$counter] = $workday;
        $worker_arr[$counter] = $worker;
        $endworkday_arr[$counter] = $endworkday;
        $material1_arr[$counter] = $material1;
        $material2_arr[$counter] = $material2;
        $material3_arr[$counter] = $material3;
        $material4_arr[$counter] = $material4;
        $material5_arr[$counter] = $material5;
        $material6_arr[$counter] = $material6;
        $widejamb_arr[$counter] = $widejamb;
        $normaljamb_arr[$counter] = $normaljamb;
        $smalljamb_arr[$counter] = $smalljamb;
        $memo_arr[$counter] = $memo;
        $regist_day_arr[$counter] = $regist_day;
        $update_day_arr[$counter] = $update_day;
        $demand_arr[$counter] = $demand;
        $startday_arr[$counter] = $startday;
        $testday_arr[$counter] = $testday;
        $hpi_arr[$counter] = $hpi;
        $delicompany_arr[$counter] = $delicompany;
        $delipay_arr[$counter] = $delipay;
        
        // 수량 문자열 생성
        $workitem = "";
        if ($widejamb != "") {
            $workitem = "막판" . $widejamb . " ";
            $sum1 += (int)$widejamb;
        }
        if ($normaljamb != "") {
            $workitem .= "막(無)" . $normaljamb . " ";
            $sum2 += (int)$normaljamb;
        }
        if ($smalljamb != "") {
            $workitem .= "쪽쟘" . $smalljamb . " ";
            $sum3 += (int)$smalljamb;
        }
        
        $sum_arr[$counter] = $workitem;
        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 합계 문자열 생성
$jamb_total = "막판:" . $sum1 . ", " . "막판(無):" . $sum2 . ", " . "쪽쟘:" . $sum3;


?>

<body>

<div id="wrap">
    <div id="content" style="width:1450px;">
        <form name="board_form" id="board_form" method="post" action="extract.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">
            <div id="list_search" style="width:1200px;">
                <div id="list_search111">
                    <?php if ($separate_date == "1") { ?>
                        &nbsp; 입출고일 <input type="radio" checked name="separate_date" value="1">
                        &nbsp; 접수일 <input type="radio" name="separate_date" value="2">
                    <?php } ?>
                    
                    <?php if ($separate_date == "2") { ?>
                        &nbsp; 입출고일 <input type="radio" name="separate_date" value="1">
                        &nbsp; 접수일 <input type="radio" checked name="separate_date" value="2">
                    <?php } ?>
                    
                    <input id="premonth" type="button" onclick="pre_month()" value="전월">
                    <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">부터
                    <input type="date" id="todate" name="todate" size="12" value="<?=$todate?>" placeholder="기간 끝">까지
                    <input id="thismonth" type="button" onclick="this_month()" value="당월">
                    <input id="thisyear" type="button" onclick="this_year()" value="당해년도">
                </div>
                
                <div id="list_search2">
                    <img src="../img/select_search.gif">
                </div>
                
                <div id="list_search4">
                    <input type="text" name="search" id="search" value="<?=$search?>">
                </div>
                
                <div id="list_search5">
                    <input type="image" src="../img/list_search_button.gif">
                </div>
            </div> <!-- end of list_search -->
            
            <div id="spreadsheet"></div>
            <div class="clear"></div>
        </form>
    </div>


<script>
// jQuery 초기화
$(function() {
    // 날짜 선택기 초기화 (필요시 주석 해제)
    // $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
    // $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
});

// jexcel 콜백 함수들
var changed = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x, y]);
}

var beforeChange = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x, y]);
}

var insertedRow = function(instance) {}

var insertedColumn = function(instance) {}

var deletedRow = function(instance) {}

var deletedColumn = function(instance) {}

var sort = function(instance, cellNum, order) {
    var order = (order) ? 'desc' : 'asc';
}

var resizeColumn = function(instance, cell, width) {}

var resizeRow = function(instance, cell, height) {}

var selectionActive = function(instance, x1, y1, x2, y2, origin) {
    var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
    var cellName2 = jexcel.getColumnNameFromId([x2, y2]);
}

var loaded = function(instance) {}

var moveRow = function(instance, from, to) {}

var moveColumn = function(instance, from, to) {}

var blur = function(instance) {}

var focus = function(instance) {}

// jexcel 초기 데이터
var data = [
    [''],
    [''],
    ['']
];

// jexcel 테이블 생성
var table1 = jexcel(document.getElementById('spreadsheet'), {
    data: data,
    tableOverflow: true,
    rowResize: true,
    columnDrag: true,
    tableHeight: '700px',
    tableWidth: '1250px',
    columns: [
        { title: '출고일', type: 'text', width: '100' },
        { title: '현장명', type: 'text', width: '300' },
        { title: '현장주소', type: 'text', width: '350' },
        { title: '수량', type: 'text', width: '200' },
        { title: '운송자', type: 'text', width: '150' },
        { title: '비용', type: 'text', width: '120' },
        { title: '원청', type: 'text', width: '120' },
        { title: '원청담당', type: 'text', width: '120' },
        { title: '원청tel', type: 'text', width: '120' },
        { title: '발주처', type: 'text', width: '120' },
        { title: '발주처담당', type: 'text', width: '120' },
        { title: '발주처tel', type: 'text', width: '120' },
        { title: '현장소장', type: 'text', width: '120' },
        { title: '연락처', type: 'text', width: '120' },
        { title: '접수일', type: 'text', width: '120' },
        { title: '실측일', type: 'text', width: '120' },
        { title: '도면설계일', type: 'text', width: '120' },
        { title: '납기일', type: 'text', width: '120' },
        { title: '출고일', type: 'text', width: '120' },
        { title: '시공팀', type: 'text', width: '120' },
        { title: '출고예정일', type: 'text', width: '120' },
        { title: '착공일', type: 'text', width: '120' },
        { title: '검사일', type: 'text', width: '120' },
        { title: '운송업체', type: 'text', width: '120' },
        { title: '막판', type: 'text', width: '120' },
        { title: '막판(無)', type: 'text', width: '120' },
        { title: '쪽쟘', type: 'text', width: '120' },
        { title: 'hpi형태', type: 'text', width: '120' },
        { title: '청구일자', type: 'text', width: '120' },
        { title: '메모내역', type: 'text', width: '600' }
    ],
    onchange: changed,
    onbeforechange: beforeChange,
    oninsertrow: insertedRow,
    oninsertcolumn: insertedColumn,
    ondeleterow: deletedRow,
    ondeletecolumn: deletedColumn,
    onselection: selectionActive,
    onsort: sort,
    onresizerow: resizeRow,
    onresizecolumn: resizeColumn,
    onmoverow: moveRow,
    onmovecolumn: moveColumn,
    onload: loaded,
    onblur: blur,
    onfocus: focus
});
</script> 
 


    <div class="clear"></div>
</div>
</div> <!-- end of wrap -->

<script>
// 데이터 로드 함수
function load_data() {
    // PHP 배열 데이터를 JavaScript로 가져오기
    var arr1 = <?php echo json_encode($workday_arr);?>;
    var arr2 = <?php echo json_encode($workplacename_arr);?>;
    var arr3 = <?php echo json_encode($address_arr);?>;
    var arr4 = <?php echo json_encode($sum_arr);?>;
    var arr5 = <?php echo json_encode($delicompany_arr);?>;
    var arr6 = <?php echo json_encode($delipay_arr);?>;
    var arr7 = <?php echo json_encode($firstord_arr);?>;
    var arr8 = <?php echo json_encode($firstordman_arr);?>;
    var arr9 = <?php echo json_encode($firstordmantel_arr);?>;
    var arr10 = <?php echo json_encode($secondord_arr);?>;
    var arr11 = <?php echo json_encode($secondordman_arr);?>;
    var arr12 = <?php echo json_encode($secondordmantel_arr);?>;
    var arr13 = <?php echo json_encode($chargedman_arr);?>;
    var arr14 = <?php echo json_encode($chargedmantel_arr);?>;
    var arr15 = <?php echo json_encode($orderday_arr);?>;
    var arr16 = <?php echo json_encode($measureday_arr);?>;
    var arr17 = <?php echo json_encode($drawday_arr);?>;
    var arr18 = <?php echo json_encode($deadline_arr);?>;
    var arr19 = <?php echo json_encode($workday_arr);?>;
    var arr20 = <?php echo json_encode($worker_arr);?>;
    var arr21 = <?php echo json_encode($endworkday_arr);?>;
    var arr22 = <?php echo json_encode($startday_arr);?>;
    var arr23 = <?php echo json_encode($testday_arr);?>;
    var arr24 = <?php echo json_encode($delicompany_arr);?>;
    var arr25 = <?php echo json_encode($widejamb_arr);?>;
    var arr26 = <?php echo json_encode($normaljamb_arr);?>;
    var arr27 = <?php echo json_encode($smalljamb_arr);?>;
    var arr28 = <?php echo json_encode($hpi_arr);?>;
    var arr29 = <?php echo json_encode($demand_arr);?>;
    var arr30 = <?php echo json_encode($memo_arr);?>;
    
    var total_sum = 0;
    var rowNum = "<?php echo $counter; ?>";
    var jamb_total = "<?php echo $jamb_total; ?>";
    
    // 테이블에 데이터 채우기
    for (var i = 0; i < rowNum; i++) {
        table1.setRowData(i, [
            arr1[i], arr2[i], arr3[i], arr4[i], arr5[i], arr6[i], arr7[i], arr8[i], arr9[i], arr10[i],
            arr11[i], arr12[i], arr13[i], arr14[i], arr15[i], arr16[i], arr17[i], arr18[i], arr19[i], arr20[i],
            arr21[i], arr22[i], arr23[i], arr24[i], arr25[i], arr26[i], arr27[i], arr28[i], arr29[i], arr30[i]
        ]);
        total_sum = total_sum + Number(uncomma(arr6[i]));
        table1.insertRow();
    }
    
    // 합계 행 추가
    table1.setRowData(rowNum, ['', '', '', jamb_total, '배송비 합계', comma(total_sum)]);
}
</script>


</body>

<script>
// 유틸리티 함수들
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

// 전월 선택
function pre_month() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; // January is 0!
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

// 당월 선택
function this_month() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; // January is 0!
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

// 당해년도 선택
function this_year() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; // January is 0!
    var yyyy = today.getFullYear();
    
    if (dd < 10) {
        dd = '0' + dd;
    }
    
    if (mm < 10) {
        mm = '0' + mm;
    }
    
    var frompreyear = yyyy + '-01-01';
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
</script>

</html>

<script>
// 페이지 로드 후 데이터 로드
setTimeout(function() {
    // this_month();  // 금월 (필요시 주석 해제)
    load_data();
}, 500);
</script>  