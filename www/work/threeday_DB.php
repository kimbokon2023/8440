 <?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
   
 // 기간을 정하는 구간
$todate = date("Y-m-d");   // 현재일자 변수지정
$nowday = date("Y-m-d");   // 현재일자 변수지정
$counter = 1;

// 출고예정일이 현재일보다 클때 조건
$common = " WHERE endworkday BETWEEN DATE(NOW())+1 AND DATE(NOW())+3 ORDER BY endworkday";
$sql = "SELECT * FROM mirae8440.work " . $common;
?>

<!DOCTYPE html>
<html lang="ko">
 <head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>출고예정리스트</title>
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/steel.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/jexcel.css">
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
 </head>
<body> 

 <?php 
    // 요청 변수 안전하게 초기화
    $check = $_REQUEST["check"] ?? ($_POST["check"] ?? '0');
    $plan_output_check = $_REQUEST["plan_output_check"] ?? ($_POST["plan_output_check"] ?? '0');
    $output_check = $_REQUEST["output_check"] ?? ($_POST["output_check"] ?? '0');
    $team_check = $_REQUEST["team_check"] ?? ($_POST["team_check"] ?? '0');
    $measure_check = $_REQUEST["measure_check"] ?? ($_POST["measure_check"] ?? '0');		 

    // 페이지 관련 변수
    $page = $_REQUEST["page"] ?? 1;
    $cursort = $_REQUEST["cursort"] ?? 0;
    $sortof = $_REQUEST["sortof"] ?? 0;
    $stable = $_REQUEST["stable"] ?? 0;

    // 정렬 모드 처리
    if (isset($_REQUEST["sortof"])) {
        if ($sortof == 1 && $stable == 0) {  // 접수일 클릭되었을때
            $cursort = ($cursort != 1) ? 1 : 2;
        }
        if ($sortof == 2 && $stable == 0) {  // 납기일 클릭되었을때
            $cursort = ($cursort != 3) ? 3 : 4;
        }
        if ($sortof == 3 && $stable == 0) {  // 실측일 클릭되었을때
            $cursort = ($cursort != 5) ? 5 : 6;
        }
        if ($sortof == 4 && $stable == 0) {  // 도면작성일 클릭되었을때
            $cursort = ($cursort != 7) ? 7 : 8;
        }
        if ($sortof == 5 && $stable == 0) {  // 출고일 클릭되었을때
            $cursort = ($cursort != 9) ? 9 : 10;
        }
        if ($sortof == 6 && $stable == 0) {  // 청구 클릭되었을때
            $cursort = ($cursort != 11) ? 11 : 12;
        }
    } else {
        $sortof = 0;
        $cursort = 0;
    }

    // 기타 요청 변수
    $mode = $_REQUEST["mode"] ?? '';
    $find = $_REQUEST["find"] ?? '';
    $fromdate = $_REQUEST["fromdate"] ?? '';
    $todate = $_REQUEST["todate"] ?? '';
    
    // 배열 초기화
    $sum = array();
    
    // 기간을 정하는 구간
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
    
    // 카운터 및 배열 변수 초기화
    $counter = 0;
    $workday_arr = array();
    $testday_arr = array();
    $workplacename_arr = array();
    $worker_arr = array();
    $material_arr = array();
    $sum_arr = array();
    $draw_arr = array();
    
    $sum1 = 0;
    $sum2 = 0;
    $sum3 = 0;
    $jamb_total = '';

    try {
        // $sql="SELECT * FROM mirae8440.work";
        $stmh = $pdo->query($sql);  // 검색조건에 맞는글 stmh
        $rowNum = $stmh->rowCount();

        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            // 데이터베이스 변수 안전하게 초기화
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
            $widejamb = $row["widejamb"] ?? '';
            $normaljamb = $row["normaljamb"] ?? '';
            $smalljamb = $row["smalljamb"] ?? '';
            $memo = $row["memo"] ?? '';
            $regist_day = $row["regist_day"] ?? '';
            $update_day = $row["update_day"] ?? '';
            $demand = $row["demand"] ?? '';
            $startday = $row["startday"] ?? '';
            $testday = $row["testday"] ?? '';
            $hpi = $row["hpi"] ?? '';
            $delicompany = $row["delicompany"] ?? '';
            $delipay = $row["delipay"] ?? '';	   

            // 날짜 포맷팅
            if ($orderday !== null && $orderday != "0000-00-00" && $orderday != "1970-01-01" && $orderday != "") {
                $orderday = date("Y-m-d", strtotime($orderday));
            } else {
                $orderday = "";
            }
            
            if ($measureday !== null && $measureday != "0000-00-00" && $measureday != "1970-01-01" && $measureday != "") {
                $measureday = date("Y-m-d", strtotime($measureday));
            } else {
                $measureday = "";
            }
            
            if ($drawday !== null && $drawday != "0000-00-00" && $drawday != "1970-01-01" && $drawday != "") {
                $drawday = date("Y-m-d", strtotime($drawday));
            } else {
                $drawday = "";
            }
            
            if ($deadline !== null && $deadline != "0000-00-00" && $deadline != "1970-01-01" && $deadline != "") {
                $deadline = date("Y-m-d", strtotime($deadline));
            } else {
                $deadline = "";
            }
            
            if ($workday !== null && $workday != "0000-00-00" && $workday != "1970-01-01" && $workday != "") {
                $workday = date("Y-m-d", strtotime($workday));
            } else {
                $workday = "";
            }
            
            if ($endworkday !== null && $endworkday != "0000-00-00" && $endworkday != "1970-01-01" && $endworkday != "") {
                $endworkday = date("Y-m-d", strtotime($endworkday));
            } else {
                $endworkday = "";
            }
            
            if ($demand !== null && $demand != "0000-00-00" && $demand != "1970-01-01" && $demand != "") {
                $demand = date("Y-m-d", strtotime($demand));
            } else {
                $demand = "";
            }
            
            if ($startday !== null && $startday != "0000-00-00" && $startday != "1970-01-01" && $startday != "") {
                $startday = date("Y-m-d", strtotime($startday));
            } else {
                $startday = "";
            }
            
            if ($testday !== null && $testday != "0000-00-00" && $testday != "1970-01-01" && $testday != "") {
                $testday = date("Y-m-d", strtotime($testday));
            } else {
                $testday = "";
            }		

            $sum_material = $material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6;
	   
		   $workday_arr[$counter] = $endworkday;
		   $testday_arr[$counter] = $testday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
            $worker_arr[$counter] = $worker;
            
            $draw_arr[$counter] = "";
            if (substr($row["drawday"] ?? '', 0, 2) == "20") {
                $draw_arr[$counter] = "OK";
            }
            
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
		 
    $jamb_total = "막판:" . $sum1 . ", " . "막판(無):" . $sum2 . ", " . "쪽쟘:" . $sum3;
			?>

 <div id="wrap">
        <h2>잠 출고예정 리스트 (익일 + 3일)<br></h2>
        <div id="spreadsheet"></div>
     <div class="clear"></div> 		 
	 </div>

<script>

$(function() {
    // $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
    // $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
});

// jExcel 이벤트 핸들러
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

// 초기 데이터
var data = [
    [''],
   [''],
 [''],
];

// jExcel 테이블 초기화
var table1 = jexcel(document.getElementById('spreadsheet'), {
    data: data,
    tableOverflow: true,   // 스크롤바 형성 여부
    rowResize: true,
    columnDrag: true,
    tableHeight: '700px',
    tableWidth: '1250px',
    columns: [
        { title: '검사일', type: 'text', width: '100' },
        { title: '출고예정일', type: 'text', width: '100' },
        { title: '설계', type: 'text', width: '40' },
        { title: '현장명', type: 'text', width: '350' },
        { title: '시공팀', type: 'text', width: '60' },
        { title: '재질', type: 'text', width: '300' },
        { title: '출고내역', type: 'text', width: '200' },
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
    onfocus: focus,
});
   </script> 
 


   <div class="clear"></div>	
  
<script>
function load_data() {
    var arr1 = <?php echo json_encode($workday_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var arr2 = <?php echo json_encode($workplacename_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var arr3 = <?php echo json_encode($worker_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var arr4 = <?php echo json_encode($material_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var arr5 = <?php echo json_encode($sum_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var arr6 = <?php echo json_encode($testday_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var arr7 = <?php echo json_encode($draw_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var total_sum = 0;
    
    var rowNum = <?php echo $counter; ?>;
    var jamb_total = "<?php echo htmlspecialchars($jamb_total, ENT_QUOTES, 'UTF-8'); ?>";
 
  // table1.setRowData(0,["번호","검사일","출고예정일","설계","현장명","시공팀","재질","쟘 내역","비 고"]);	    
    for (var i = 0; i < rowNum; i++) {
        table1.setRowData(i, [arr6[i], arr1[i], arr7[i], arr2[i], arr3[i], arr4[i], arr5[i]]);
			 // total_sum = total_sum + Number(uncomma(arr6[i]));
			 table1.insertRow();
   }
  // table1.setRowData(rowNum,['','','',jamb_total,'배송비 합계',comma(total_sum)]);	   
  // alert(jamb_total);	
}
  </script>
  


<script>
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

function pre_month() {  // 전월
    // document.getElementById('search').value=null;
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
    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
} 


function this_month() {  // 당해월
    // document.getElementById('search').value=null;
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
    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
} 


function this_year() {  // 당해년도
    // document.getElementById('search').value=null;
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
    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
}

setTimeout(function() {
    // this_month();  // 금월
    load_data();
}, 500);
</script>

</body>
</html>  