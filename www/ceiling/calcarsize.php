<?php
session_start();
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : "";
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
    <script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
    <script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>	
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <!-- 화면에 UI창 알람창 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <title> Car 인승 산출 계산기 </title> 
</head>
<?php 

// 변수 초기화
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : ""; 
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : ""; 
$recordDate = isset($_REQUEST["recordDate"]) ? $_REQUEST["recordDate"] : date("Y-m-d");

// Check 변수 초기화
if (isset($_REQUEST["check"])) {
    $check = $_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시
} else {
    $check = isset($_POST["check"]) ? $_POST["check"] : '0'; // 미출고 리스트 POST사용
}

if (isset($_REQUEST["plan_output_check"])) {
    $plan_output_check = $_REQUEST["plan_output_check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시
} else if (isset($_POST["plan_output_check"])) {
    $plan_output_check = $_POST["plan_output_check"]; // 미출고 리스트 POST사용
} else {
    $plan_output_check = '0';
}

if (isset($_REQUEST["output_check"])) {
    $output_check = $_REQUEST["output_check"]; // 출고완료
} else if (isset($_POST["output_check"])) {
    $output_check = $_POST["output_check"]; // 출고완료
} else {
    $output_check = '0';
}

if (isset($_REQUEST["team_check"])) {
    $team_check = $_REQUEST["team_check"]; // 시공팀미지정
} else if (isset($_POST["team_check"])) {
    $team_check = $_POST["team_check"]; // 시공팀미지정
} else {
    $team_check = '0';
}

if (isset($_REQUEST["measure_check"])) {
    $measure_check = $_REQUEST["measure_check"]; // 미실측리스트
} else if (isset($_POST["measure_check"])) {
    $measure_check = $_POST["measure_check"]; // 미실측리스트
} else {
    $measure_check = '0';
}

// 페이지 번호 설정
if (isset($_REQUEST["page"])) {
    $page = $_REQUEST["page"];  // 페이지 번호
} else {
    $page = 1;
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

// 정렬 변수 초기화
$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : 0;    // 현재 정렬모드 지정
$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : 0;      // 클릭해서 넘겨준 값
$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : 0;      // 정렬모드 변경할지 안할지 결정

if (isset($_REQUEST["sortof"])) {
    if ($sortof == 1 && $stable == 0) {      // 접수일 클릭되었을때
        if ($cursort != 1) {
            $cursort = 1;
        } else {
            $cursort = 2;
        }
    }
    
    if ($sortof == 2 && $stable == 0) {      // 납기일 클릭되었을때
        if ($cursort != 3) {
            $cursort = 3;
        } else {
            $cursort = 4;
        }
    }
    
    if ($sortof == 3 && $stable == 0) {      // 실측일 클릭되었을때
        if ($cursort != 5) {
            $cursort = 5;
        } else {
            $cursort = 6;
        }
    }
    
    if ($sortof == 4 && $stable == 0) {      // 도면작성일 클릭되었을때
        if ($cursort != 7) {
            $cursort = 7;
        } else {
            $cursort = 8;
        }
    }
    
    if ($sortof == 5 && $stable == 0) {      // 출고일 클릭되었을때
        if ($cursort != 9) {
            $cursort = 9;
        } else {
            $cursort = 10;
        }
    }
    
    if ($sortof == 6 && $stable == 0) {      // 청구 클릭되었을때
        if ($cursort != 11) {
            $cursort = 11;
        } else {
            $cursort = 12;
        }
    }
} else {
    $sortof = 0;
    $cursort = 0;
}

// 배열 및 기타 변수 초기화
$sum = array(0, 0, 0, 0, 0, 0);
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : "";  // 목록표에 제목,이름 등 나오는 부분
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

// Form action에서 사용되는 변수들 초기화
$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : "";
$process = isset($_REQUEST["process"]) ? $_REQUEST["process"] : "";
$asprocess = isset($_REQUEST["asprocess"]) ? $_REQUEST["asprocess"] : "";
$up_fromdate = isset($_REQUEST["up_fromdate"]) ? $_REQUEST["up_fromdate"] : "";
$up_todate = isset($_REQUEST["up_todate"]) ? $_REQUEST["up_todate"] : "";
$separate_date = isset($_REQUEST["separate_date"]) ? $_REQUEST["separate_date"] : "";
$view_table = isset($_REQUEST["view_table"]) ? $_REQUEST["view_table"] : "";
 
// 날짜 범위 설정
if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 7);
    $fromdate = $fromdate . "-01";
}

if ($todate == "") {
    $todate = date("Y-m-d");
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$orderby = " order by workday desc ";
$now = date("Y-m-d");  // 현재 날짜와 크거나 같으면 출고예정으로 구분

// SQL 쿼리 생성
if ($mode == "search") {
    if ($search == "") {
        $sql = "select * from mirae8440.ceiling where workday between date('$fromdate') and date('$Transtodate')" . $orderby;
    } else if ($search != "") {
        $sql = "select * from mirae8440.ceiling where ((workplacename like '%$search%') or (firstordman like '%$search%') or (secondordman like '%$search%') or (chargedman like '%$search%') ";
        $sql .= "or (delivery like '%$search%') or (firstord like '%$search%') or (secondord like '%$search%') or (memo like '%$search%')) and (workday between date('$fromdate') and date('$Transtodate'))" . $orderby;
    }
} else {
    $sql = "select * from mirae8440.ceiling where workday between date('$fromdate') and date('$Transtodate')" . $orderby;
}
	  
// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열 및 카운터 초기화
$counter = 0;
$workday_arr = array();
$workplacename_arr = array();
$address_arr = array();
$secondord_arr = array();
$sum_arr = array();
$delivery_arr = array();
$content_arr = array();
$num_arr = array();
$demand_arr = array();
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$jamb_total = 0;  // JavaScript에서 사용되는 변수

try {
    // 검색조건에 맞는글 조회
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();  


    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
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
        $orderday = $row["orderday"];
        $measureday = $row["measureday"];
        $drawday = $row["drawday"];
        $deadline = $row["deadline"];
        $delicompany = $row["delicompany"];
        $delivery = $row["delivery"];
        $delipay = $row["delipay"];
        
        $workday = $row["workday"];
        $startday = $row["startday"];
        $testday = $row["testday"];
        $worker = $row["worker"];
        $endworkday = $row["endworkday"];
        $material1 = $row["material1"];
        $material2 = $row["material2"];
        $material3 = $row["material3"];
        $material4 = $row["material4"];
        $material5 = $row["material5"];
        $material6 = $row["material6"];

        $memo = $row["memo"];
        $regist_day = $row["regist_day"];
        $update_day = $row["update_day"];
        $demand = $row["demand"];
        
        $type = $row["type"];
        $inseung = $row["inseung"];
        $su = $row["su"];
        $bon_su = $row["bon_su"];
        $lc_su = $row["lc_su"];
        $etc_su = $row["etc_su"];
        $air_su = $row["air_su"];
        $car_insize = $row["car_insize"];
        $order_com1 = $row["order_com1"];
        $order_text1 = $row["order_text1"];
        $order_com2 = $row["order_com2"];
        $order_text2 = $row["order_text2"];
        $order_com3 = $row["order_com3"];
        $order_text3 = $row["order_text3"];
        $order_com4 = $row["order_com4"];
        $order_text4 = $row["order_text4"];
        $lc_draw = $row["lc_draw"];
        $lclaser_com = $row["lclaser_com"];
        $lclaser_date = $row["lclaser_date"];
        $lcbending_date = $row["lcbending_date"];
        $lcwelding_date = $row["lcwelding_date"];
        $lcpainting_date = $row["lcpainting_date"];
        $lcassembly_date = $row["lcassembly_date"];
        $main_draw = $row["main_draw"];
        $eunsung_make_date = $row["eunsung_make_date"];
        $eunsung_laser_date = $row["eunsung_laser_date"];
        $mainbending_date = $row["mainbending_date"];
        $mainwelding_date = $row["mainwelding_date"];
        $mainpainting_date = $row["mainpainting_date"];
        $mainassembly_date = $row["mainassembly_date"];
        $memo2 = $row["memo2"];

        $order_date1 = $row["order_date1"];
        $order_date2 = $row["order_date2"];
        $order_date3 = $row["order_date3"];
        $order_date4 = $row["order_date4"];
        $order_input_date1 = $row["order_input_date1"];
        $order_input_date2 = $row["order_input_date2"];
        $order_input_date3 = $row["order_input_date3"];
        $order_input_date4 = $row["order_input_date4"];

        $demand = trans_date($demand);
        
        // 합계 계산
        $sum[0] = $sum[0] + (int)$su;
        $sum[1] += (int)$bon_su;
        $sum[2] += (int)$lc_su;
        $sum[3] += (int)$etc_su;
        $sum[4] += (int)$air_su;
        $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

        $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),  본천장 : " . $sum[1] . " (EA),  L/C : "  . $sum[2] . "  (EA), 기타 : "  . $sum[3] . "  (EA), 공기청정기 : "  . $sum[4] . " (EA) ";
        
        // 작업 항목 문자열 생성
        $workitem = "";
        if ($su != "") {
            $workitem = $su . " , ";
        }
        if ($bon_su != "") {
            $workitem .= "본 " . $bon_su . ", ";
        }
        if ($lc_su != "") {
            $workitem .= "L/C " . $lc_su . ", ";
        }
        if ($etc_su != "") {
            $workitem .= "기타 " . $etc_su . ", ";
        }
        if ($air_su != "") {
            $workitem .= "공기청정기 " . $air_su . " ";
        }
        
        // 부품 문자열 생성
        $part = "";
        if ($order_com1 != "") {
            $part = $order_com1 . ",";
        }
        if ($order_com2 != "") {
            $part .= $order_com2 . ", ";
        }
        if ($order_com3 != "") {
            $part .= $order_com3 . ", ";
        }
        if ($order_com4 != "") {
            $part .= $order_com4 . ", ";
        }
        
        // 배송 텍스트 생성
        $deli_text = "";
        if ($delivery != "" || $delipay != 0) {
            $deli_text = $delivery . " " . $delipay;
        }
        
        // 배열에 데이터 저장
        $workday_arr[$counter] = $workday;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $delivery_arr[$counter] = $delivery;
        $secondord_arr[$counter] = $secondord;
        $num_arr[$counter] = $num;
        $demand_arr[$counter] = $demand;
        $content_arr[$counter] = $type . " " . $inseung . " " . $car_insize . " " . $memo . " " . $memo2 . " ";
        $sum_arr[$counter] = $workitem;
        
        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$all_sum = $sum1 + $sum2 + $sum3;

?>

<body>

<div id="wrap">
    <div id="content" style="width:1850px;">
        <form name="board_form" id="board_form" method="post" action="batchDB.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">
            
            <div class="clear"></div>
            
            <fieldset class="groupbox-border">
                <legend class="groupbox-border" style="color:blue;"> (카 인승 산출 계산기 - 선형 보간법) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </legend>
                
                <div class="input-group p-1 mb-1">
                    <span class="input-group-text">  카 사이즈(Car inside) &nbsp; </span>
                    <span class="input-group-text">  너비(Width) :&nbsp;  <input type="number" id="carwidth" name="carwidth" value="1600" size="5"> </span>
                    <span class="input-group-text">  폭(Depth):&nbsp; <input type="number" id="cardepth" name="cardepth" value="1500" size="5"> </span>
                </div>
                
                <div class="input-group p-1 mb-1">
                    <span class="input-group-text">  출입구 사이즈 &nbsp; </span>
                    <span class="input-group-text">  폭 :&nbsp;  <input type="number" id="gatewidth" name="gatewidth" value="900" size="5"> </span>
                    <span class="input-group-text">  높이:&nbsp; <input type="number" id="gateheight" name="gateheight" value="2100" size="5"> </span>
                    <span class="input-group-text">  카주 깊이 :&nbsp; <input type="number" id="columndepth" name="columndepth" value="80" size="5">  </span>
                    <span class="input-group-text">  유효바닥 면적(㎡) &nbsp;  <input type="number" id="Area" name="Area" value="" size="5">  </span>
                    <span class="input-group-text">  정격하중(kg) &nbsp;  <input type="number" id="basicweight" name="basicweight" value="" size="5">  </span>
                </div>

                <div class="input-group p-1 mb-1">
                    <span class="input-group-text text-center">  적재하중 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; </span>
                    <span class="input-group-text text-center">  최대 유효면적  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp; </span>
                </div>
                
                <div class="input-group p-1 mb-1">
                    <span class="input-group-text">  <input type="number" id="weightup" name="weightup" value="" size="1">  &nbsp; </span>
                    <span class="input-group-text">  <input type="number" id="weightupArea" name="weightupArea" value="" size="1">  &nbsp; </span>
                </div>
                
                <div class="input-group p-1 mb-1">
                    <span class="input-group-text">  <input type="text" id="weightmid" name="weightmid" value="X" size="20">  &nbsp; </span>
                    <span class="input-group-text">  <input type="number" id="weightmidArea" name="weightmidArea" value="" size="1">  &nbsp; </span>
                </div>
                
                <div class="input-group p-1 mb-1">
                    <span class="input-group-text">  <input type="number" id="weightbottom" name="weightbottom" value="" size="1">  &nbsp; </span>
                    <span class="input-group-text">  <input type="number" id="weightbottomArea" name="weightbottomArea" value="" size="1">  &nbsp; </span>
                </div>
                
                <div class="input-group p-1 mb-1">
                    <span class="input-group-text">  정격하중 X = &nbsp; <input type="text" id="formula" name="formula" value="" size="100">  &nbsp; </span>
                </div>
                
                <div class="input-group p-1 mb-1">
                    <span class="input-group-text">  최소 카 면적(㎡)&nbsp; </span>
                    <span class="input-group-text">  <input type="number" id="resultcarArea" name="resultcarArea" value="" size="1">  &nbsp; </span>
                    <span class="input-group-text">  인승 계산결과 &nbsp; </span>
                    <span class="input-group-text text-danger">  <input type="text" id="resultcarinside" name="resultcarinside" value="" size="5">  &nbsp; 인승 &nbsp; &nbsp; &nbsp; &nbsp; </span>
                </div>
            
            </fieldset>
            
            &nbsp; <button type="button" id="calBtn" class="btn btn-secondary"> 계산하기 </button> &nbsp;
            
            <div class="clear"></div>
            <br><br>
            <span style="font-size:20px;color:grey;"> ※ 카사이즈 일방형 15인승 산출 예시 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
            <div class="clear"></div>
            <div style="width:1800px;">
                <img src="../img/carsize1.jpg">
                <img src="../img/carsize2.jpg">
                <img src="../img/carsize3.jpg">
            </div>
        
        </form>
        
        <div class="clear"></div>
    </div>
    
    <div class="clear"></div>
</div> <!-- end of wrap -->

<form id="Form1" name="Form1">
    <input type="hidden" id="num_arr" name="num_arr[]">
    <input type="hidden" id="recordDate_arr" name="recordDate_arr[]">
</form>

</body>

</html>  


<script>

$(document).ready(function(){
    
    var arr1 = <?php echo json_encode($workday_arr);?>;
    var arr2 = <?php echo json_encode($workplacename_arr);?>;
    var arr3 = <?php echo json_encode($address_arr);?>;
    var arr4 = <?php echo json_encode($sum_arr);?>;
    var arr5 = <?php echo json_encode($delivery_arr);?>;
    var arr6 = <?php echo json_encode($content_arr);?>;
    var arr7 = <?php echo json_encode($secondord_arr);?>;
    var arr8 = <?php echo json_encode($num_arr);?>;
    var arr9 = <?php echo json_encode($demand_arr);?>;
    var total_sum = 0;
    
    var rowNum = "<?php echo $counter; ?>";
    var jamb_total = "<?php echo $jamb_total; ?>";
    
    const data = [];
    const columns = [];
    
    
    $("#calBtn").click(function(){
        let Area = 0;
        const carwidth = $("#carwidth").val();
        const cardepth = $("#cardepth").val();
        
        let gatewidth = 0;
        
        // 적재하중 배열
        arrweight = [
            100, 180, 225, 300, 375, 400, 450, 525, 600, 630,
            675, 750, 800, 825, 900, 975, 1000, 1050, 1125, 1200,
            1250, 1275, 1350, 1425, 1500, 1600, 2000, 2500, 2600, 2700,
            2800, 2900, 3000, 3100, 3200, 3300, 3400, 3500, 3600, 3700,
            3800, 3900, 4000, 4100, 4200, 4300, 4400, 4500, 4600, 4700,
            4800, 4900, 5000, 5100, 5200, 5300, 5400, 5500, 5600, 5700,
            5800, 5900, 6000, 6100, 6200, 6300, 6400, 6500, 6600, 6700,
            6800, 6900, 7000, 7100, 7200, 7300, 7400, 7500, 7600, 7700,
            7800, 7900, 8000, 8100, 8200, 8300, 8400, 8500, 8600, 8700,
            8800, 8900, 9000, 9100, 9200, 9300
        ];

        // 유효면적 배열
        arrArea = [
            0.37, 0.58, 0.70, 0.90, 1.10, 1.17, 1.30, 1.45, 1.60, 1.66,
            1.75, 1.90, 2.00, 2.05, 2.20, 2.36, 2.40, 2.50, 2.65, 2.80,
            2.90, 2.95, 3.10, 3.25, 3.40, 3.56, 4.20, 5.00, 5.16, 5.32,
            5.48, 5.64, 5.8, 5.96, 6.12, 6.28, 6.44, 6.6, 6.76, 6.92,
            7.08, 7.24, 7.4, 7.56, 7.72, 7.88, 8.04, 8.2, 8.36, 8.52,
            8.68, 8.84, 9, 9.16, 9.32, 9.48, 9.64, 9.8, 9.96, 10.12,
            10.28, 10.44, 10.6, 10.76, 10.92, 11.08, 11.24, 11.4, 11.56, 11.72,
            11.88, 12.04, 12.2, 12.36, 12.52, 12.68, 12.84, 13, 13.16, 13.32,
            13.48, 13.64, 13.8, 13.96, 14.12, 14.28, 14.44, 14.6, 14.76, 14.92,
            15.08, 15.24, 15.4, 15.56, 15.72, 15.88
        ];
        
        // 카 너비에 따른 출입구 폭 결정
        if (carwidth < 1400) {
            gatewidth = 800;
        }
        if (carwidth >= 1400 && carwidth < 1800) {
            gatewidth = 900;
        }
        if (carwidth >= 1800 && carwidth < 2000) {
            gatewidth = 1000;
        }
        if (carwidth >= 2000) {
            gatewidth = 1100;
        }
        
        let weightup = 0;
        let weightbottom = 0;
        
        // 유효바닥 면적 계산
        Area = carwidth / 1000 * cardepth / 1000 + (gatewidth / 1000 * 0.08);
        let BasicPerson;
        
        $("#Area").val(Area);
        $("#weightmidArea").val(Area);

        // 면적 범위에 따른 상하 중량 찾기
        for (i = 0; i < arrArea.length - 1; i++) {
            if (Area > arrArea[i] && Area < arrArea[i + 1]) {
                weightup = arrweight[i];
                weightbottom = arrweight[i + 1];
                console.log(weightup);
            }
        }
        
        // 유효면적 validareaup, validareabottom 추출
        let validareaup = 0;
        let validareabottom = 0;

        for (i = 0; i < arrweight.length; i++) {
            if (weightup == arrweight[i]) {
                validareaup = arrArea[i];
            }
            if (weightbottom == arrweight[i]) {
                validareabottom = arrArea[i];
            }
        }
        
        $("#weightupArea").val(validareaup);
        $("#weightbottomArea").val(validareabottom);
        
        $("#weightup").val(weightup);
        $("#weightbottom").val(weightbottom);
        $("#gatewidth").val(gatewidth);  // gate width 설정한다.

        // 중량 X 구함 (선형 보간법)
        let x;
        
        x = (Area - validareaup) / (validareabottom - validareaup) * (weightbottom - weightup) + weightup;
        
        let str = " ( " + Area + " - " + validareaup + " ) / ( " + validareabottom + " - " + validareaup + ") * ( " + weightbottom + " - " + weightup + " ) + " + weightup;
        
        $("#formula").val(str);
        $("#basicweight").val(x);
        
        // 인승 계산 (정격하중 / 75kg)
        let num1 = x / 75;
        
        console.log(num1);
        $("#resultcarinside").val(Math.floor(num1));
        
        /*
        // 이전 방식 (면적 기준 고정 인승)
        if (Area < 1.73) {
            BasicPerson = 9;
        } else if (Area < 1.87) {
            BasicPerson = 10;
        } else if (Area < 2.15) {
            BasicPerson = 12;
        } else if (Area < 2.43) {
            BasicPerson = 13;
        } else if (Area < 2.85) {
            BasicPerson = 15;
        } else if (Area < 2.99) {
            BasicPerson = 18;
        } else if (Area < 3.41) {
            BasicPerson = 21;
        } else if (Area < 3.79) {
            BasicPerson = 24;
        } else if (Area > 3.79) {
            BasicPerson = '견적요함';
        }
        
        $("#resultcarinside").val(BasicPerson);
        */
    });

});

</script>