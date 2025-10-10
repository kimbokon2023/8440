<?php
session_start();

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 현재 날짜
$today = date("Y-m-d");
$nowday = date("Y-m-d");
$todate = date("Y-m-d");

// SQL 설정
$common = " where (date(endworkday)>=date(now())) order by endworkday "; // 출고예정일이 현재일보다 클때 조건
$sql = "select * from mirae8440.work " . $common;
$counter = 1;

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
    
    <title> 출고예정리스트 </title>
</head>

<body>

<?php

// REQUEST/POST 변수 초기화
$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : (isset($_POST["check"]) ? $_POST["check"] : '0'); 

$plan_output_check = isset($_REQUEST["plan_output_check"]) ? $_REQUEST["plan_output_check"] : (isset($_POST["plan_output_check"]) ? $_POST["plan_output_check"] : '0');
$output_check = isset($_REQUEST["output_check"]) ? $_REQUEST["output_check"] : (isset($_POST["output_check"]) ? $_POST["output_check"] : '0');
$team_check = isset($_REQUEST["team_check"]) ? $_REQUEST["team_check"] : (isset($_POST["team_check"]) ? $_POST["team_check"] : '0');
$measure_check = isset($_REQUEST["measure_check"]) ? $_REQUEST["measure_check"] : (isset($_POST["measure_check"]) ? $_POST["measure_check"] : '0');
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

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

// 배열 초기화
$counter = 0;
$workday_arr = array();
$testday_arr = array();
$workplacename_arr = array();
$worker_arr = array();
$material_arr = array();
$sum_arr = array();
$draw_arr = array();

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
        
        // 재질 문자열 생성
        $sum_material = $material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6;
        
        // 배열에 데이터 저장
        $workday_arr[$counter] = $endworkday;
        $testday_arr[$counter] = $testday;
        $workplacename_arr[$counter] = $workplacename;
        $material_arr[$counter] = $sum_material;
        $worker_arr[$counter] = $worker;
        
        // 도면 상태
        $draw_arr[$counter] = "";
        if (substr($row["drawday"], 0, 2) == "20") {
            $draw_arr[$counter] = "OK";
        }
        
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

<div id="wrap">
    <h1> &nbsp; 제품 출고일정리스트 </h1>
    <br>
    
    <div id="spreadsheet"></div>
    <div class="clear"></div>
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
    tableOverflow: true, // 스크롤바 형성 여부
    rowResize: true,
    columnDrag: true,
    tableHeight: '700px',
    tableWidth: '1250px',
    columns: [
        { title: '번호', type: 'text', width: '60' },
        { title: '검사일', type: 'text', width: '150' },
        { title: '출고예정일', type: 'text', width: '150' },
        { title: '설계', type: 'text', width: '60' },
        { title: '현장명', type: 'text', width: '200' },
        { title: '시공팀', type: 'text', width: '100' },
        { title: '재질', type: 'text', width: '300' },
        { title: '출고내역', type: 'text', width: '200' },
        { title: '비고', type: 'text', width: '100' }
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

// 데이터 로드 함수
function load_data() {
    // PHP 배열 데이터를 JavaScript로 가져오기
    var arr1 = <?php echo json_encode($workday_arr);?>;
    var arr2 = <?php echo json_encode($workplacename_arr);?>;
    var arr3 = <?php echo json_encode($worker_arr);?>;
    var arr4 = <?php echo json_encode($material_arr);?>;
    var arr5 = <?php echo json_encode($sum_arr);?>;
    var arr6 = <?php echo json_encode($testday_arr);?>;
    var arr7 = <?php echo json_encode($draw_arr);?>;
    var total_sum = 0;
    
    var rowNum = "<?php echo $counter; ?>";
    var jamb_total = "<?php echo $jamb_total; ?>";
    
    // 헤더 행 설정
    table1.setRowData(0, ["번호", "검사일", "출고예정일", "설계", "현장명", "시공팀", "재질", "쟘 내역", "비 고"]);
    
    // 데이터 행 삽입
    for (var i = 0; i < rowNum; i++) {
        table1.setRowData(i + 1, [i + 1, arr6[i], arr1[i], arr7[i], arr2[i], arr3[i], arr4[i], arr5[i]]);
        table1.insertRow();
    }
}

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

// 자동 데이터 로드
setTimeout(function() {
    // this_month(); // 금월 데이터 로드 (필요시 주석 해제)
    load_data();
}, 500);
</script>

</body>
</html>  