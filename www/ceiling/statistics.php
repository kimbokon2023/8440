<?php
session_start();

$level = $_SESSION["level"];
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location:http://8440.co.kr/login/login_form.php");
    exit;
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

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
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 <title> 미래기업 통합정보시스템 </title> 
 </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />
 
<?php
// 변수 초기화
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
$display_sel = isset($_REQUEST["display_sel"]) ? $_REQUEST["display_sel"] : "doughnut";
$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : 0;
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : "";
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : "";
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : "";
$view_table = isset($_REQUEST["view_table"]) ? $_REQUEST["view_table"] : "";

require_once("../lib/mydb.php");
$pdo = db_connect();

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

$SettingDate = "workday";
$common = " where workday between date('$fromdate') and date('$Transtodate') ";

// 전체합계(입고부분)를 산출하는 부분
$sum_title = array();
$sum = array();
$a = "";

// 변수 초기화
$rowNum = 0;
$steelsource_item = array();
$steelsource_spec = array();
$which = "";
$tmp = "";
$steelnum = 0;

$sql = "select * from mirae8440.work " . $common;

try {
    // 레코드 전체 sql 설정 (입고부분) - 현재는 사용하지 않음
    $stmh = $pdo->query($sql);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $checkstep = isset($row["checkstep"]) ? $row["checkstep"] : "";
        $workplacename = isset($row["workplacename"]) ? $row["workplacename"] : "";
        $address = isset($row["address"]) ? $row["address"] : "";
        $deadline = isset($row["deadline"]) ? $row["deadline"] : "";
        $workday = isset($row["workday"]) ? $row["workday"] : "";
        $worker = isset($row["worker"]) ? $row["worker"] : "";
        $material1 = isset($row["material1"]) ? $row["material1"] : "";
        $material2 = isset($row["material2"]) ? $row["material2"] : "";
        $material3 = isset($row["material3"]) ? $row["material3"] : "";
        $material4 = isset($row["material4"]) ? $row["material4"] : "";
        $material5 = isset($row["material5"]) ? $row["material5"] : "";
        $material6 = isset($row["material6"]) ? $row["material6"] : "";
        $widejamb = isset($row["widejamb"]) ? $row["widejamb"] : 0;
        $normaljamb = isset($row["normaljamb"]) ? $row["normaljamb"] : 0;
        $smalljamb = isset($row["smalljamb"]) ? $row["smalljamb"] : 0;
        
        for ($i = 1; $i <= $rowNum; $i++) {
            $sum_title[$i] = $steelsource_item[$i] . $steelsource_spec[$i];
            if ($which == '1' && $tmp == $sum_title[$i]) {
                if (!isset($sum[$i])) {
                    $sum[$i] = 0;
                }
                $sum[$i] = $sum[$i] + (int)$steelnum;
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  


// 전체합계(출고부분)를 처리하는 부분

$sql = "select * from mirae8440.work " . $common;
try {
    // 레코드 전체 sql 설정 (출고부분) - 현재는 사용하지 않음
    $stmh = $pdo->query($sql);
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
        $delivery = isset($row["delivery"]) ? $row["delivery"] : "";
        $delicar = isset($row["delicar"]) ? $row["delicar"] : "";
        $delicompany = isset($row["delicompany"]) ? $row["delicompany"] : "";
        $delipay = isset($row["delipay"]) ? $row["delipay"] : "";
        $delimethod = isset($row["delimethod"]) ? $row["delimethod"] : "";
        $demand = isset($row["demand"]) ? $row["demand"] : "";
        $startday = isset($row["startday"]) ? $row["startday"] : "";
        $testday = isset($row["testday"]) ? $row["testday"] : "";
        $hpi = isset($row["hpi"]) ? $row["hpi"] : "";
        $first_writer = isset($row["first_writer"]) ? $row["first_writer"] : "";
        $update_log = isset($row["update_log"]) ? $row["update_log"] : "";
        
        for ($i = 1; $i <= $rowNum; $i++) {
            $sum_title[$i] = $steelsource_item[$i] . $steelsource_spec[$i];
            if ($which == '2' && $tmp == $sum_title[$i]) {
                if (!isset($sum[$i])) {
                    $sum[$i] = 0;
                }
                $sum[$i] = $sum[$i] - (int)$steelnum;
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  

// 검색 모드에 따른 SQL 쿼리 설정
$separate_date = isset($_REQUEST["separate_date"]) ? $_REQUEST["separate_date"] : "";

if ($mode == "search") {
    if ($search == "") {
        $sql = "select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate') " . $a;
    } else {
        // 각 필드별로 검색어가 있는지 쿼리주는 부분
        $sql = "select * from mirae8440.work where ((workday like '%$search%') or (workplacename like '%$search%') ";
        $sql .= "or (item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%') or (comment like '%$search%')) ";
        $sql .= "and (workday between date('$fromdate') and date('$Transtodate')) ";
        if ($separate_date != "") {
            $sql .= "and (which='$separate_date') ";
        }
    }
} else {
    $sql = "select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate') " . $a;
}		
				         
   
$nowday = date("Y-m-d");   // 현재일자 변수지정

$worker_arr = array();
$work_done = array();
$temp_arr = array();
$work_sum = array();
$count = 0;
$start_num = 0;

$worker_arr[0] = '추영덕';
$worker_arr[1] = '이만희';
$worker_arr[2] = '김상훈';
$worker_arr[3] = '민경채';
$worker_arr[4] = '유영';
$worker_arr[5] = '김운호';
$worker_arr[6] = '손상민';
$worker_arr[7] = '김한준';
$worker_arr[8] = '김진섭';
$worker_arr[9] = '강병규';

// work_sum 배열 초기화
for ($i = 0; $i < 10; $i++) {
    $work_sum[$i] = 0;
}			
   
try {
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();
    $chartchoice = array("", "", "", "", "");
?>


<body>

<div id="wrap">
    <div id="content">
        <form name="board_form" id="board_form" method="post" action="statistics.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&display_sel=<?=$display_sel?>">
            <div id="col2">
                <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>'>
                
                <div id="display_board" class="background" name="display_board">
                    <div class="clear"></div>
                    
                    <div id="list_board">
                        <div id="title" style="width:300px">
                            <h2>시공소장별 시공점유율 차트</h2>
                        </div>
                        <div class="clear"></div>	 

                        <div id="list_search">
                            <div id="list_search1">
                                <br> ▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다.
                            </div>
                            <div id="list_search111">
                                <input id="preyear" type='button' onclick='pre_year()' value='전년도'>
                                <input id="three_month" type='button' onclick='three_month_ago()' value='M-3월'>
                                <input id="prepremonth" type='button' onclick='prepre_month()' value='전전월'>
                                <input id="premonth" type='button' onclick='pre_month()' value='전월'>
                                <input type="text" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">부터
                                <input type="text" id="todate" name="todate" size="12" value="<?=$todate?>" placeholder="기간 끝">까지
                                <input id="thismonth" type='button' onclick='this_month()' value='당월'>
                                <input id="thisyear" type='button' onclick='this_year()' value='당해년도'>
                            </div>
                            
                            <div id="list_search4">
                                <input type="text" name="search" id="search" value="<?=$search?>">
                            </div>
                            <div id="list_search5">
                                <input type="image" src="../img/list_search_button.gif">
                            </div>
                        </div> <!-- end of list_search -->
                        <div class="clear"></div>
                        <?php
                        
                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            $num = $row["num"];
                            $checkstep = isset($row["checkstep"]) ? $row["checkstep"] : "";
                            $workplacename = isset($row["workplacename"]) ? $row["workplacename"] : "";
                            $workday = isset($row["workday"]) ? $row["workday"] : "";
                            $worker = isset($row["worker"]) ? $row["worker"] : "";
                            $widejamb = isset($row["widejamb"]) ? $row["widejamb"] : 0;
                            $normaljamb = isset($row["normaljamb"]) ? $row["normaljamb"] : 0;
                            $smalljamb = isset($row["smalljamb"]) ? $row["smalljamb"] : 0;
                            
                            $work_done[$count] = (int)$widejamb + (int)$normaljamb + (int)$smalljamb / 4;
                            
                            switch ($worker) {
                                case $worker_arr[0]:
                                    $work_sum[0] += $work_done[$count];
                                    break;
                                case $worker_arr[1]:
                                    $work_sum[1] += $work_done[$count];
                                    break;
                                case $worker_arr[2]:
                                    $work_sum[2] += $work_done[$count];
                                    break;
                                case $worker_arr[3]:
                                    $work_sum[3] += $work_done[$count];
                                    break;
                                case $worker_arr[4]:
                                    $work_sum[4] += $work_done[$count];
                                    break;
                                case $worker_arr[5]:
                                    $work_sum[5] += $work_done[$count];
                                    break;
                                case $worker_arr[6]:
                                    $work_sum[6] += $work_done[$count];
                                    break;
                                case $worker_arr[7]:
                                    $work_sum[7] += $work_done[$count];
                                    break;
                                case $worker_arr[8]:
                                    $work_sum[8] += $work_done[$count];
                                    break;
                                case $worker_arr[9]:
                                    $work_sum[9] += $work_done[$count];
                                    break;
                                default:
                                    break;
                            }
                            
                            $count++;
                            $start_num--;
                        }
                    } catch (PDOException $Exception) {
                        print "오류: " . $Exception->getMessage();
                    }  
  
                    print "<br><h2><span style='color:blue'>쪽쟘 4(SET)는 와이드 1(SET)로 산출함</span></h2><br>";
                    print "<h3>";
                    print "<span style='color:red'>" . $worker_arr[0] . "</span> " . number_format($work_sum[0]) . "(SET), &nbsp; ";
                    print "<span style='color:blue'>" . $worker_arr[1] . "</span> " . number_format($work_sum[1]) . "(SET), &nbsp; ";
                    print "<span style='color:orange'>" . $worker_arr[2] . "</span> " . number_format($work_sum[2]) . "(SET), &nbsp; ";
                    print "<span style='color:green'>" . $worker_arr[3] . "</span> " . number_format($work_sum[3]) . "(SET), &nbsp; ";
                    print "<span style='color:purple'>" . $worker_arr[4] . "</span> " . number_format($work_sum[4]) . "(SET), &nbsp; ";
                    print "<span style='color:blue'>" . $worker_arr[5] . "</span> " . number_format($work_sum[5]) . "(SET), &nbsp; ";
                    print "<span style='color:orange'>" . $worker_arr[6] . "</span> " . number_format($work_sum[6]) . "(SET), &nbsp; ";
                    print "<span style='color:green'>" . $worker_arr[7] . "</span> " . number_format($work_sum[7]) . "(SET), &nbsp; ";
                    print "<span style='color:purple'>" . $worker_arr[8] . "</span> " . number_format($work_sum[8]) . "(SET), &nbsp; ";
                    print "<span style='color:brown'>" . $worker_arr[9] . "</span> " . number_format($work_sum[9]) . "(SET)";
                    print "</h3><br><br>";
                    
                    switch ($display_sel) {
                        case "doughnut":
                            $chartchoice[0] = 'checked';
                            break;
                        case "bar":
                            $chartchoice[1] = 'checked';
                            break;
                        case "line":
                            $chartchoice[2] = 'checked';
                            break;
                        case "radar":
                            $chartchoice[3] = 'checked';
                            break;
                        case "polarArea":
                            $chartchoice[4] = 'checked';
                            break;
                    }
                    ?>
                    
                    <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>'>
                    <input id="display_sel" name="display_sel" type='hidden' value='<?=$display_sel?>'>
                    
                    &nbsp; 도넛 <input type="radio" <?=$chartchoice[0]?> name="chart_sel" value="doughnut">
                    &nbsp; 바 <input type="radio" <?=$chartchoice[1]?> name="chart_sel" value="bar">
                    &nbsp; 라인 <input type="radio" <?=$chartchoice[2]?> name="chart_sel" value="line">
                    &nbsp; 레이더 <input type="radio" <?=$chartchoice[3]?> name="chart_sel" value="radar">
                    &nbsp; Polar Area <input type="radio" <?=$chartchoice[4]?> name="chart_sel" value="polarArea">
                    <br><br>
                    
                    <canvas id="myChart" width="1300" height="500"></canvas>
                    
                    </div> <!-- end of list_board -->
                </div> <!-- end of display_board -->
            </div> <!-- end of col2 -->
        </form>
    </div> <!-- end of content -->
</div> <!-- end of wrap -->
  


<script>
/* Checkbox change event */
$('input[name="chart_sel"]').change(function() {
    // 모든 radio를 순회한다.
    $('input[name="chart_sel"]').each(function() {
        var value = $(this).val();
        var checked = $(this).prop('checked');
        var $label = $(this).next();
        
        if (checked) {
            $("#display_sel").val(value);
            document.getElementById('board_form').submit();
        }
    });
});

var worker_arr = <?php echo json_encode($worker_arr); ?>;
var work_done = <?php echo json_encode($work_done); ?>;
var work_sum = <?php echo json_encode($work_sum); ?>;
var ctx = document.getElementById('myChart');
var chart_type = document.getElementById('display_sel').value;

var myChart = new Chart(ctx, {
    type: chart_type,
    data: {
        labels: [
            worker_arr[0], worker_arr[1], worker_arr[2], worker_arr[3], worker_arr[4],
            worker_arr[5], worker_arr[6], worker_arr[7], worker_arr[8], worker_arr[9]
        ],
        datasets: [{
            label: '#소장별 시공수량 합계',
            data: [
                work_sum[0], work_sum[1], work_sum[2], work_sum[3], work_sum[4],
                work_sum[5], work_sum[6], work_sum[7], work_sum[8], work_sum[9]
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(205, 100, 25, 0.2)',
                'rgba(25, 66, 200, 0.2)',
                'rgba(95, 452, 60, 0.2)',
                'rgba(113, 62, 55, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(205, 100, 25, 1)',
                'rgba(25, 66, 200, 1)',
                'rgba(95, 452, 60, 1)',
                'rgba(113, 62, 55, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<script>
function blinker() {
    $('.blinking').fadeOut(500);
    $('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

$(function() {
    $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
});

$(function() {
    $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#todate").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#up_fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#up_todate").datepicker({ dateFormat: 'yy-mm-dd' });
});
 
function up_pre_year() {
    // 윗쪽 전년도 추출
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
    
    today = mm + '/' + dd + '/' + yyyy;
    yyyy = yyyy - 1;
    frompreyear = yyyy + '-01-01';
    topreyear = yyyy + '-12-31';
    
    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

function pre_year() {
    // 전년도 추출
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
    
    today = mm + '/' + dd + '/' + yyyy;
    yyyy = yyyy - 1;
    frompreyear = yyyy + '-01-01';
    topreyear = yyyy + '-12-31';
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}  

function up_pre_month() {
    // 윗쪽 전월
    document.getElementById('search').value = null;
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
    
    frompreyear = yyyy + '-' + mm + '-01';
    topreyear = yyyy + '-' + mm + '-31';
    
    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
} 


function three_month_ago() {
    // 석달전
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    
    if (dd < 10) {
        dd = '0' + dd;
    }
    
    mm = mm - 3;
    if (mm < -1) {
        mm = '11';
    }
    if (mm < 1) {
        mm = '12';
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    if (mm >= 12) {
        yyyy = yyyy - 1;
    }
    
    frompreyear = yyyy + '-' + mm + '-01';
    
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
    
    topreyear = yyyy + '-' + mm + '-' + tmp;
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}

function prepre_month() {
    // 전전월
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
    
    frompreyear = yyyy + '-' + mm + '-01';
    
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
    
    topreyear = yyyy + '-' + mm + '-' + tmp;
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}

function pre_month() {
    // 전월
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
    
    frompreyear = yyyy + '-' + mm + '-01';
    topreyear = yyyy + '-' + mm + '-31';
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
} 

function up_this_year() {
    // 윗쪽 당해년도
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
    
    today = mm + '/' + dd + '/' + yyyy;
    frompreyear = yyyy + '-01-01';
    topreyear = yyyy + '-12-31';
    
    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
    fromdate1 = frompreyear;
    todate1 = topreyear;
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

function this_year() {
    // 아래쪽 당해년도
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
    
    today = mm + '/' + dd + '/' + yyyy;
    frompreyear = yyyy + '-01-01';
    topreyear = yyyy + '-12-31';
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    fromdate1 = frompreyear;
    todate1 = topreyear;
    document.getElementById('board_form').submit();
}

function up_this_month() {
    // 윗쪽 당해월
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
    
    frompreyear = yyyy + '-' + mm + '-01';
    topreyear = yyyy + '-' + mm + '-31';
    
    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

function this_month() {
    // 당해월
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
    
    frompreyear = yyyy + '-' + mm + '-01';
    topreyear = yyyy + '-' + mm + '-31';
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}

function From_tomorrow() {
    // 익일 이후
    var today = new Date();
    var dd = today.getDate() + 1;
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    
    if (dd < 10) {
        dd = '0' + dd;
    }
    
    if (mm < 10) {
        mm = '0' + mm;
    }
    
    frompreyear = yyyy + '-' + mm + '-' + dd;
    topreyear = yyyy + '-12-31';
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}

function Fromthis_today() {
    // 금일이후
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
    
    frompreyear = yyyy + '-' + mm + '-' + dd;
    topreyear = yyyy + '-12-31';
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}

function up_this_today() {
    // 윗쪽 날짜 입력란 금일
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
    
    frompreyear = yyyy + '-' + mm + '-' + dd;
    topreyear = yyyy + '-' + mm + '-' + dd;
    
    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

function this_today() {
    // 금일
    document.getElementById('search').value = null;
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
    
    frompreyear = yyyy + '-' + mm + '-' + dd;
    topreyear = yyyy + '-' + mm + '-' + dd;
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}

function this_tomorrow() {
    // 익일
    var today = new Date();
    var dd = today.getDate() + 1;
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    
    if (dd < 10) {
        dd = '0' + dd;
    }
    
    if (mm < 10) {
        mm = '0' + mm;
    }
    
    frompreyear = yyyy + '-' + mm + '-' + dd;
    topreyear = yyyy + '-' + mm + '-' + dd;
    
    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();
}

function process_list() {
    // 접수일 출고일 라디오버튼 클릭시
    document.getElementById('board_form').submit();
}

function exe_view_table() {
    // 출고현황 검색을 클릭시 실행
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
} 

function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}
</script>

<?php
if ($mode == "" && $fromdate == null) {
    echo("<script>this_year();</script>");  // 당해년도 화면에 초기세팅하기
}
?>

</body>
</html>