 <?php
/**
 * 본천장/조명천장 납품예정 리스트 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    } elseif (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return $default;
}

// 날짜 변환 함수
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        return date("Y-m-d", strtotime($tdate));
    }
    return "";
}

// 요청 변수 초기화
$num = getRequestValue("num", '');
$check = getRequestValue("check", '0');
$plan_output_check = getRequestValue("plan_output_check", '0');
$output_check = getRequestValue("output_check", '0');
$team_check = getRequestValue("team_check", '0');
$measure_check = getRequestValue("measure_check", '0');
$page = getRequestValue("page", 1);
$cursort = getRequestValue("cursort", '');
$sortof = getRequestValue("sortof", '');
$stable = getRequestValue("stable", '');
$mode = getRequestValue("mode", '');
$find = getRequestValue("find", '');
$year = getRequestValue("year", '');
$process = getRequestValue("process", '');
$asprocess = getRequestValue("asprocess", '');
$yearcheckbox = getRequestValue("yearcheckbox", '');
$separate_date = getRequestValue("separate_date", '');

// 기간 설정
$fromdate = getRequestValue("fromdate", '');
$todate = getRequestValue("todate", '');

if (empty($fromdate)) {
    $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = date("Y-m-d", strtotime($todate . '+1 days'));
} else {
    $Transtodate = date("Y-m-d", strtotime($todate));
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 현재 날짜
$nowday = date("Y-m-d");
$counter = 1;

// 배열 초기화
$sum = array();
$workday_arr = array();
$testday_arr = array();
$workplacename_arr = array();
$worker_arr = array();
$secondord_arr = array();  // 136줄 오타 수정 ($$secondord_arr → $secondord_arr)
$material_arr = array();
$sum_arr = array();
$main_draw_arr = array();
$lc_draw_arr = array();
$type_arr = array();
$car_insize_arr = array();
$detail_arr = array();
$sum1 = array();
$sum2 = array();
$sum3 = array();
$sum4 = array();
$sum5 = array();

// SQL 쿼리 (Prepared Statement 사용)
$sql = "SELECT * FROM mirae8440.ceiling WHERE DATE(deadline) >= DATE(NOW()) ORDER BY deadline";

// 데이터 조회
try {
    $stmh = $pdo->query($sql);
   $rowNum = $stmh->rowCount();  

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $checkstep = $row["checkstep"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $address = $row["address"] ?? '';
        $firstord = $row["firstord"] ?? '';
        $firstordman = $row["firstordman"] ?? '';
        $firstordmantel = $row["firstordmantel"] ?? '';
        $secondord = $row["secondord"] ?? '';
        $secondordman = $row["secondordman"] ?? '';
        $secondordmantel = $row["secondordmantel"] ?? '';
        $chargedman = $row["chargedman"] ?? '';
        $chargedmantel = $row["chargedmantel"] ?? '';
        $orderday = $row["orderday"] ?? '';
        $measureday = $row["measureday"] ?? '';
        $drawday = $row["drawday"] ?? '';
        $deadline = $row["deadline"] ?? '';
        $workday = $row["workday"] ?? '';
        $worker = $row["worker"] ?? '';
        $endworkday = $row["endworkday"] ?? '';
        $material1 = $row["material1"] ?? '';
        $material2 = $row["material2"] ?? '';
        $material3 = $row["material3"] ?? '';
        $material4 = $row["material4"] ?? '';
        $material5 = $row["material5"] ?? '';
        $material6 = $row["material6"] ?? '';
        $widehap = $row["widehap"] ?? '';
        $normalhap = $row["normalhap"] ?? '';
        $smallhap = $row["smallhap"] ?? '';
        $memo = $row["memo"] ?? '';
        $regist_day = $row["regist_day"] ?? '';
        $update_day = $row["update_day"] ?? '';
        $demand = $row["demand"] ?? '';
        $startday = $row["startday"] ?? '';
        $testday = $row["testday"] ?? '';
        $hpi = $row["hpi"] ?? '';
        $delicompany = $row["delicompany"] ?? '';
        $delipay = $row["delipay"] ?? '';
        $type = $row["type"] ?? '';
        $inseung = $row["inseung"] ?? '';
        $su = $row["su"] ?? '';
        $bon_su = $row["bon_su"] ?? '';
        $lc_su = $row["lc_su"] ?? '';
        $etc_su = $row["etc_su"] ?? '';
        $air_su = $row["air_su"] ?? '';
        $car_insize = $row["car_insize"] ?? '';
        $order_com1 = $row["order_com1"] ?? '';
        $order_text1 = $row["order_text1"] ?? '';
        $order_com2 = $row["order_com2"] ?? '';
        $order_text2 = $row["order_text2"] ?? '';
        $order_com3 = $row["order_com3"] ?? '';
        $order_text3 = $row["order_text3"] ?? '';
        $order_com4 = $row["order_com4"] ?? '';
        $order_text4 = $row["order_text4"] ?? '';
        $lc_draw = $row["lc_draw"] ?? '';
        $lclaser_com = $row["lclaser_com"] ?? '';
        $lclaser_date = $row["lclaser_date"] ?? '';
        $lcbending_date = $row["lcbending_date"] ?? '';
        $lcwelding_date = $row["lcwelding_date"] ?? '';
        $lcpainting_date = $row["lcpainting_date"] ?? '';
        $lcassembly_date = $row["lcassembly_date"] ?? '';
        $main_draw = $row["main_draw"] ?? '';
        $eunsung_make_date = $row["eunsung_make_date"] ?? '';
        $eunsung_laser_date = $row["eunsung_laser_date"] ?? '';
        $mainbending_date = $row["mainbending_date"] ?? '';
        $mainwelding_date = $row["mainwelding_date"] ?? '';
        $mainpainting_date = $row["mainpainting_date"] ?? '';
        $mainassembly_date = $row["mainassembly_date"] ?? '';
        $memo2 = $row["memo2"] ?? '';
        
        // 합계 계산
        $sum1[$counter] = ($sum1[$counter] ?? 0) + (int)$su;
        $sum2[$counter] = ($sum2[$counter] ?? 0) + (int)$bon_su;
        $sum3[$counter] = ($sum3[$counter] ?? 0) + (int)$lc_su;
        $sum4[$counter] = ($sum4[$counter] ?? 0) + (int)$etc_su;
        $sum5[$counter] = ($sum5[$counter] ?? 0) + (int)$air_su;
        
        // 날짜 변환
        $workday = trans_date($workday);
        $demand = trans_date($demand);
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        $testday = trans_date($testday);
        $lc_draw = trans_date($lc_draw);
        $lclaser_date = trans_date($lclaser_date);
        $lcbending_date = trans_date($lcbending_date);
        $lcwelding_date = trans_date($lcwelding_date);
        $lcpainting_date = trans_date($lcpainting_date);
        $lcassembly_date = trans_date($lcassembly_date);
        $main_draw = trans_date($main_draw);
        $eunsung_make_date = trans_date($eunsung_make_date);
        $eunsung_laser_date = trans_date($eunsung_laser_date);
        $mainbending_date = trans_date($mainbending_date);
        $mainwelding_date = trans_date($mainwelding_date);
        $mainpainting_date = trans_date($mainpainting_date);
        $mainassembly_date = trans_date($mainassembly_date);
        
        // 재질 조합
        $sum_material = $material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6;
        
        // 배열에 데이터 저장
		   $workday_arr[$counter] = $deadline;
		   $testday_arr[$counter] = $testday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
        $worker_arr[$counter] = $worker;
        $secondord_arr[$counter] = $secondord;
        $type_arr[$counter] = $type;
        $car_insize_arr[$counter] = $car_insize;
        
        // 작업 내역 조합
        $workitem = "";
        if (!empty($su)) $workitem = $su . " , ";
        if (!empty($bon_su)) $workitem .= "본 " . $bon_su . ", ";
        if (!empty($lc_su)) $workitem .= "L/C " . $lc_su . ", ";
        if (!empty($etc_su)) $workitem .= "기타 " . $etc_su . ", ";
        if (!empty($air_su)) $workitem .= "공기청정기 " . $air_su . " ";
        
        $detail_arr[$counter] = $workitem;
        
        // 설계 완료 여부
        $main_draw_arr[$counter] = "";
        if (substr($main_draw, 0, 2) == "20") {
            $main_draw_arr[$counter] = "OK";
        } elseif ($bon_su < 1) {
            $main_draw_arr[$counter] = "X";
        }
        
        $lc_draw_arr[$counter] = "";
        if (substr($lc_draw, 0, 2) == "20") {
            $lc_draw_arr[$counter] = "OK";
        } elseif ($lc_su < 1) {
            $lc_draw_arr[$counter] = "X";
        }
   	
			   $counter++;
    }
    
} catch (PDOException $ex) {
    error_log("데이터 조회 오류: " . $ex->getMessage());
    echo "오류: 데이터를 불러오는 중 문제가 발생했습니다.";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>생산일정(생산완료 제외) 리스트</title>
    
    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" type="text/css" href="../css/jexcel.css">
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
 <div id="wrap">
        <h1>&nbsp; 본천장/조명천장 납품예정 리스트</h1>
  <br>
        <div id="spreadsheet"></div>
     <div class="clear"></div> 		 
	 </div>

<script type="text/javascript">
(function() {
    'use strict';
    
    $(function() {
        if (typeof $.fn.datepicker !== 'undefined') {
            $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd' });
        }
    });
    
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
    
    window.pre_month = function() {
        var today = new Date();
        var mm = today.getMonth();
        var yyyy = today.getFullYear();
        
        if (mm < 1) {
            mm = 12;
            yyyy--;
        }
        
        mm = (mm < 10) ? '0' + mm : mm;
        
        var tmp = 0;
        switch (Number(mm)) {
            case 1: case 3: case 5: case 7: case 8: case 10: case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4: case 6: case 9: case 11:
                tmp = 30;
                break;
        }
        
        var frompreyear = yyyy + '-' + mm + '-01';
        var topreyear = yyyy + '-' + mm + '-' + tmp;
        
        var fromdateElem = document.getElementById("fromdate");
        var todateElem = document.getElementById("todate");
        var boardForm = document.getElementById('board_form');
        
        if (fromdateElem) fromdateElem.value = frompreyear;
        if (todateElem) todateElem.value = topreyear;
        if (boardForm) boardForm.submit();
    };
    
    window.this_month = function() {
        var today = new Date();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        mm = (mm < 10) ? '0' + mm : mm;
        
        var tmp = 0;
        switch (Number(mm)) {
            case 1: case 3: case 5: case 7: case 8: case 10: case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4: case 6: case 9: case 11:
                tmp = 30;
                break;
        }
        
        var frompreyear = yyyy + '-' + mm + '-01';
        var topreyear = yyyy + '-' + mm + '-' + tmp;
        
        var fromdateElem = document.getElementById("fromdate");
        var todateElem = document.getElementById("todate");
        var boardForm = document.getElementById('board_form');
        
        if (fromdateElem) fromdateElem.value = frompreyear;
        if (todateElem) todateElem.value = topreyear;
        if (boardForm) boardForm.submit();
    };
    
    window.this_year = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        var frompreyear = yyyy + '-01-01';
        var topreyear = yyyy + '-' + mm + '-' + dd;
        
        var fromdateElem = document.getElementById("fromdate");
        var todateElem = document.getElementById("todate");
        var boardForm = document.getElementById('board_form');
        
        if (fromdateElem) fromdateElem.value = frompreyear;
        if (todateElem) todateElem.value = topreyear;
        if (boardForm) boardForm.submit();
    };
    
    // jExcel 이벤트 핸들러
    var changed = function(instance, cell, x, y, value) {
        var cellName = jexcel.getColumnNameFromId([x, y]);
    };
    
    var beforeChange = function(instance, cell, x, y, value) {
        var cellName = jexcel.getColumnNameFromId([x, y]);
    };
    
    var insertedRow = function(instance) {};
    var insertedColumn = function(instance) {};
    var deletedRow = function(instance) {};
    var deletedColumn = function(instance) {};

var sort = function(instance, cellNum, order) {
    var order = (order) ? 'desc' : 'asc';
    };
    
    var resizeColumn = function(instance, cell, width) {};
    var resizeRow = function(instance, cell, height) {};

var selectionActive = function(instance, x1, y1, x2, y2, origin) {
    var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
    var cellName2 = jexcel.getColumnNameFromId([x2, y2]);
    };
    
    var loaded = function(instance) {};
    var moveRow = function(instance, from, to) {};
    var moveColumn = function(instance, from, to) {};
    var blur = function(instance) {};
    var focus = function(instance) {};
    
    // jExcel 초기 데이터
    var data = [
   [''],
 [''],
        ['']
];

    // jExcel 테이블 생성
var table1 = jexcel(document.getElementById('spreadsheet'), {
        data: data,
        tableOverflow: true,
        rowResize: true,
        columnDrag: true,
        tableHeight: '3000px',
        tableWidth: '1300px',
    columns: [
            { title: '납품예정일', type: 'text', width: '80', height: '50' },
            { title: '본설계', type: 'text', width: '50' },
            { title: 'L/C설계', type: 'text', width: '50' },
            { title: '현장명', type: 'text', width: '350' },
            { title: '발주처', type: 'text', width: '120' },
            { title: '타입', type: 'text', width: '70' },
            { title: 'Car Insize', type: 'text', width: '100' },
            { title: '납품내역', type: 'text', width: '300' }
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
    window.load_data = function() {
        var arr1 = <?php echo json_encode($workday_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr2 = <?php echo json_encode($workplacename_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr3 = <?php echo json_encode($worker_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr4 = <?php echo json_encode($material_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr5 = <?php echo json_encode($sum_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr7 = <?php echo json_encode($main_draw_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr8 = <?php echo json_encode($lc_draw_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr9 = <?php echo json_encode($secondord_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr10 = <?php echo json_encode($type_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr11 = <?php echo json_encode($car_insize_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr12 = <?php echo json_encode($detail_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        var hap1 = <?php echo json_encode($sum1, JSON_UNESCAPED_UNICODE); ?>;
        var hap2 = <?php echo json_encode($sum2, JSON_UNESCAPED_UNICODE); ?>;
        var hap3 = <?php echo json_encode($sum3, JSON_UNESCAPED_UNICODE); ?>;
        var hap4 = <?php echo json_encode($sum4, JSON_UNESCAPED_UNICODE); ?>;
        var hap5 = <?php echo json_encode($sum5, JSON_UNESCAPED_UNICODE); ?>;
        
        var total_sum = 0;
        var hap1_sum = 0;
        var hap2_sum = 0;
        var hap3_sum = 0;
        var hap4_sum = 0;
        var hap5_sum = 0;
        var tmp = "";
        
        var rowNum = <?php echo $counter; ?>;
        
        var j = 0;
        var past = arr1[0];
        
        for (var i = 0; i < rowNum; i++) {
            if (arr1[i] != past) {
                if (hap1_sum > 0) tmp = tmp + hap1_sum + "(set), ";
                if (hap2_sum > 0) tmp = tmp + " 본청장 " + hap2_sum + ",";
                if (hap3_sum > 0) tmp = tmp + " L/C " + hap3_sum + ",";
                if (hap4_sum > 0) tmp = tmp + " 기타 " + hap4_sum + ",";
                if (hap5_sum > 0) tmp = tmp + " Air " + hap5_sum;
				   				   
                  			 table1.insertRow();
                table1.setRowData(j, ['', '', '', '', '', '', '', tmp]);
							 j++;							 
                
							 table1.insertRow();
							 j++;
                
                hap1_sum = 0;
                hap2_sum = 0;
                hap3_sum = 0;
                hap4_sum = 0;
                hap5_sum = 0;
                tmp = "";
            }
            
            table1.setRowData(j, [arr1[i], arr7[i], arr8[i], arr2[i], arr9[i], arr10[i], arr11[i], arr12[i]]);
            hap1_sum = hap1_sum + (hap1[i] || 0);
            hap2_sum = hap2_sum + (hap2[i] || 0);
            hap3_sum = hap3_sum + (hap3[i] || 0);
            hap4_sum = hap4_sum + (hap4[i] || 0);
            hap5_sum = hap5_sum + (hap5[i] || 0);
            
			 table1.insertRow();				   
				 
            past = arr1[i];
			 j++;
   }
   
        // 마지막 소계
        tmp = hap1_sum + "(set), " + " 본청장 " + hap2_sum + "," + " L/C " + hap3_sum + " 기타 " + hap4_sum + " Air " + hap5_sum;
			 table1.insertRow();
        table1.setRowData(j, ['', '', '', '', '', '', '', tmp]);
    };
    
    // 800ms 후 데이터 로드
setTimeout(function() {
  load_data();
}, 800);

})();
</script>  
</body>
</html>
