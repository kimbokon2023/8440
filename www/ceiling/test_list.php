<?php
session_start();

$level = $_SESSION["level"];
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
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>검사일정별 시공소장님 DB</title>
</head> 

<?php

// 변수 초기화
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : 0;

require_once("../lib/mydb.php");
$pdo = db_connect();

$attached = " ";
$orderby = "order by testday desc, measureday desc ";
$a = " " . $orderby;

$sql = "select * from mirae8440.work where workday='' " . $attached . $a;

// 배열 변수 초기화
$counter = 0;
$secondord_arr = array();
$workplacename_arr = array();
$address_arr = array();
$sum1_arr = array();
$sum2_arr = array();
$sum3_arr = array();
$sum_arr = array();
$sum_arr1 = array();
$sum_arr2 = array();
$material_arr = array();
$hpi_arr = array();
$firstordman_arr = array();
$firstordmantel_arr = array();
$chargedman_arr = array();
$chargedmantel_arr = array();
$measureday_arr = array();
$worker_arr = array();
$endworkday_arr = array();
$draw_arr = array();
$startday_arr = array();
$testday_arr = array();   

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $checkstep = isset($row["checkstep"]) ? $row["checkstep"] : "";
        $workplacename = isset($row["workplacename"]) ? $row["workplacename"] : "";
        $address = isset($row["address"]) ? $row["address"] : "";
        $firstord = isset($row["firstord"]) ? $row["firstord"] : "";
        $firstordman = isset($row["firstordman"]) ? $row["firstordman"] : "";
        $firstordmantel = isset($row["firstordmantel"]) ? $row["firstordmantel"] : "";
        $secondord = isset($row["secondord"]) ? $row["secondord"] : "";
        $secondordman = isset($row["secondordman"]) ? $row["secondordman"] : "";
        $secondordmantel = isset($row["secondordmantel"]) ? $row["secondordmantel"] : "";
        $chargedman = isset($row["chargedman"]) ? $row["chargedman"] : "";
        $chargedmantel = isset($row["chargedmantel"]) ? $row["chargedmantel"] : "";
        $orderday = isset($row["orderday"]) ? $row["orderday"] : "";
        $measureday = isset($row["measureday"]) ? $row["measureday"] : "";
        $drawday = isset($row["drawday"]) ? $row["drawday"] : "";
        $deadline = isset($row["deadline"]) ? $row["deadline"] : "";
        $workday = isset($row["workday"]) ? $row["workday"] : "";
        $testday = isset($row["testday"]) ? $row["testday"] : "";
        $worker = isset($row["worker"]) ? $row["worker"] : "";
        $endworkday = isset($row["endworkday"]) ? $row["endworkday"] : "";
        $material1 = isset($row["material1"]) ? $row["material1"] : "";
        $material2 = isset($row["material2"]) ? $row["material2"] : "";
        $material3 = isset($row["material3"]) ? $row["material3"] : "";
        $material4 = isset($row["material4"]) ? $row["material4"] : "";
        $material5 = isset($row["material5"]) ? $row["material5"] : "";
        $material6 = isset($row["material6"]) ? $row["material6"] : "";
        $widejamb = isset($row["widejamb"]) ? $row["widejamb"] : 0;
        $normaljamb = isset($row["normaljamb"]) ? $row["normaljamb"] : 0;
        $smalljamb = isset($row["smalljamb"]) ? $row["smalljamb"] : 0;
        $memo = isset($row["memo"]) ? $row["memo"] : "";
        $regist_day = isset($row["regist_day"]) ? $row["regist_day"] : "";
        $update_day = isset($row["update_day"]) ? $row["update_day"] : "";
        $demand = isset($row["demand"]) ? $row["demand"] : "";
        $startday = isset($row["startday"]) ? $row["startday"] : "";
        $hpi = isset($row["hpi"]) ? $row["hpi"] : "";	   
        
        // 날짜 형식 변환
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
        $secondord_arr[$counter] = $secondord;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $material_arr[$counter] = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;
        $chargedman_arr[$counter] = $chargedman;
        $chargedmantel_arr[$counter] = $chargedmantel;
        $firstordman_arr[$counter] = $firstordman;
        $firstordmantel_arr[$counter] = $firstordmantel;
        $hpi_arr[$counter] = $hpi;
        $startday_arr[$counter] = $startday;
        $testday_arr[$counter] = $testday;
        $worker_arr[$counter] = $worker;
        $measureday_arr[$counter] = $measureday;
        $endworkday_arr[$counter] = $endworkday;
        
        // 설계 여부 확인
        $draw_arr[$counter] = "";
        if (substr($drawday, 0, 2) == "20") {
            $draw_arr[$counter] = "OK";
        }
        
        // 수량 계산
        if (!isset($sum1_arr[$counter])) {
            $sum1_arr[$counter] = 0;
        }
        if (!isset($sum2_arr[$counter])) {
            $sum2_arr[$counter] = 0;
        }
        if (!isset($sum3_arr[$counter])) {
            $sum3_arr[$counter] = 0;
        }
        
        if ($widejamb != "") {
            $sum1_arr[$counter] += $widejamb;
        }
        if ($normaljamb != "") {
            $sum2_arr[$counter] += $normaljamb;
        }
        if ($smalljamb != "") {
            $sum3_arr[$counter] += $smalljamb;
        }
        
        if (!isset($sum_arr1[$counter])) {
            $sum_arr1[$counter] = 0;
        }
        if (!isset($sum_arr2[$counter])) {
            $sum_arr2[$counter] = 0;
        }
        
        $sum_arr[$counter] = $sum_arr1[$counter] + $sum_arr2[$counter] + $sum3_arr[$counter];
        
        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  
		 
?>

<body>
    <div id="wrap">
        <div id="content">
            <div id="spreadsheet"></div>
            <div class="clear"></div>

<script>
var changed = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x, y]);
}

var beforeChange = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x, y]);
}

var insertedRow = function(instance) {
}

var insertedColumn = function(instance) {
}

var deletedRow = function(instance) {
}

var deletedColumn = function(instance) {
}

var sort = function(instance, cellNum, order) {
    var order = (order) ? 'desc' : 'asc';
}

var resizeColumn = function(instance, cell, width) {
}

var resizeRow = function(instance, cell, height) {
}

var selectionActive = function(instance, x1, y1, x2, y2, origin) {
    var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
    var cellName2 = jexcel.getColumnNameFromId([x2, y2]);
}

var loaded = function(instance) {
}

var moveRow = function(instance, from, to) {
}

var moveColumn = function(instance, from, to) {
}

var blur = function(instance) {
}

var focus = function(instance) {
}

var data = [
    [''],
    [''],
    [''],
    ['']
];

var table1 = jexcel(document.getElementById('spreadsheet'), {
    data: data,
    tableOverflow: true,
    rowResize: true,
    columnDrag: true,
    tableHeight: '700px',
    tableWidth: '1650px',
    columns: [
        { title: '', type: 'text', width: '90' },   // 검사일
        { title: '', type: 'text', width: '90' },   // 실측일
        { title: '', type: 'text', width: '40' },   // 설계여부
        { title: '', type: 'text', width: '90' },   // 출고예정일
        { title: '', type: 'text', width: '60' },   // 소장
        { title: '', type: 'text', width: '300' },  // 현장명
        { title: '', type: 'text', width: '300' },  // 현장주소
        { title: '', type: 'text', width: '40' },   // 막판
        { title: '', type: 'text', width: '40' },   // 멍텅구리
        { title: '', type: 'text', width: '40' },   // 쪽쟘
        { title: '', type: 'text', width: '200' },  // 재질
        { title: '', type: 'text', width: '100' },  // 담당PM
        { title: '', type: 'text', width: '100' },  // PM전번
        { title: '', type: 'text', width: '100' },  // 현장소장
        { title: '', type: 'text', width: '100' },  // 소장전번
        { title: '', type: 'text', width: '90' }    // 착공일
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
            
            <div id="order2"></div>
            <div class="clear"></div>
        </div>
    </div> <!-- end of wrap -->

<script>
function load_data() {
    var arr1 = <?php echo json_encode($secondord_arr); ?>;
    var arr2 = <?php echo json_encode($workplacename_arr); ?>;
    var arr3 = <?php echo json_encode($address_arr); ?>;
    var arr4 = <?php echo json_encode($sum_arr); ?>;
    var arr5 = <?php echo json_encode($material_arr); ?>;
    var arr6 = <?php echo json_encode($hpi_arr); ?>;
    var arr7 = <?php echo json_encode($firstordman_arr); ?>;
    var arr8 = <?php echo json_encode($firstordmantel_arr); ?>;
    var arr9 = <?php echo json_encode($chargedman_arr); ?>;
    var arr10 = <?php echo json_encode($chargedmantel_arr); ?>;
    var arr11 = <?php echo json_encode($startday_arr); ?>;
    var arr12 = <?php echo json_encode($testday_arr); ?>;
    var arr13 = <?php echo json_encode($measureday_arr); ?>;
    var arr14 = <?php echo json_encode($worker_arr); ?>;
    var arr15 = <?php echo json_encode($sum1_arr); ?>;
    var arr16 = <?php echo json_encode($sum2_arr); ?>;
    var arr17 = <?php echo json_encode($sum3_arr); ?>;
    var arr18 = <?php echo json_encode($endworkday_arr); ?>;
    var arr19 = <?php echo json_encode($draw_arr); ?>;
    
    var rowNum = <?php echo $counter; ?>;
    table1.setRowData(0, ["검사일", "실측일", "설계", "출고예정일", "시공소장", "현장명", "현장주소", "막", "멍", "쪽", "재질", "담당자PM", "담당전번", "현장소장", "소장전번", "착공일"]);
    
    for (i = 0; i < rowNum; i++) {
        table1.setRowData(i + 1, [arr12[i], arr13[i], arr19[i], arr18[i], arr14[i], arr2[i], arr3[i], arr15[i], arr16[i], arr17[i], arr5[i], arr7[i], arr8[i], arr9[i], arr10[i], arr11[i]]);
        table1.insertRow();
    }
}
</script>

</body>

<script>
setTimeout(function() {
    load_data();
}, 500);
</script>

</html>