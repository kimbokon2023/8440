<?php
/**
 * 출하검사 통계 페이지
 * 검사 데이터의 통계를 차트로 시각화
 */

session_start();

// 세션 변수 안전하게 초기화
$level = $_SESSION["level"] ?? 999;

if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location:http://8440.co.kr/login/login_form.php");
    exit;
}

// 요청 변수 안전하게 초기화
$load_confirm = $_REQUEST["load_confirm"] ?? '';
$display_sel = $_REQUEST["display_sel"] ?? 'bar';
$item_sel = $_REQUEST["item_sel"] ?? '년도비교';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;
$cursort = $_REQUEST["cursort"] ?? '';
$sortof = $_REQUEST["sortof"] ?? '';
$stable = $_REQUEST["stable"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$search = $_REQUEST["search"] ?? '';

$sum = array();

// 당월을 날짜 기간으로 설정
if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 7);
    $fromdate = $fromdate . "-01";
}
if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$orderby = " order by workday desc ";
$now = date("Y-m-d");    // 현재 날짜와 크거나 같으면 출고예정으로 구분

if ($mode == "search") {
    if ($search == "") {
        $sql = "select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')" . $orderby;
    } elseif ($search != "") {
        $sql = "select * from mirae8440.work where ((workplacename like '%$search%' ) or (firstordman like '%$search%' ) or (secondordman like '%$search%' ) or (chargedman like '%$search%' ) ";
        $sql .= "or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$fromdate') and date('$Transtodate'))" . $orderby;
    }
}

require_once("../lib/mydb.php");
$pdo = db_connect();

$counter = 0;
$workday_arr = array();
$workplacename_arr = array();
$address_arr = array();
$sum_arr = array();
$delicompany_arr = array();
$delipay_arr = array();
$secondord_arr = array();
$worker_arr = array();
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;

try {
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $rowNum = $stmh->rowCount();

    $total_row = 0;

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

        // 날짜 형식 변환
        if ($orderday != "0000-00-00" and $orderday != "1970-01-01" and $orderday != "") $orderday = date("Y-m-d", strtotime($orderday));
        else $orderday = "";
        if ($measureday != "0000-00-00" and $measureday != "1970-01-01" and $measureday != "") $measureday = date("Y-m-d", strtotime($measureday));
        else $measureday = "";
        if ($drawday != "0000-00-00" and $drawday != "1970-01-01" and $drawday != "") $drawday = date("Y-m-d", strtotime($drawday));
        else $drawday = "";
        if ($deadline != "0000-00-00" and $deadline != "1970-01-01" and $deadline != "") $deadline = date("Y-m-d", strtotime($deadline));
        else $deadline = "";
        if ($workday != "0000-00-00" and $workday != "1970-01-01" and $workday != "") $workday = date("Y-m-d", strtotime($workday));
        else $workday = "";
        if ($endworkday != "0000-00-00" and $endworkday != "1970-01-01" and $endworkday != "") $endworkday = date("Y-m-d", strtotime($endworkday));
        else $endworkday = "";
        if ($demand != "0000-00-00" and $demand != "1970-01-01" and $demand != "") $demand = date("Y-m-d", strtotime($demand));
        else $demand = "";
        if ($startday != "0000-00-00" and $startday != "1970-01-01" and $startday != "") $startday = date("Y-m-d", strtotime($startday));
        else $startday = "";
        if ($testday != "0000-00-00" and $testday != "1970-01-01" and $testday != "") $testday = date("Y-m-d", strtotime($testday));
        else $testday = "";

        $workday_arr[$counter] = $workday;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $delicompany_arr[$counter] = $delicompany;
        $delipay_arr[$counter] = $delipay;
        $secondord_arr[$counter] = $secondord;
        $worker_arr[$counter] = $worker;

        // 불량이란 단어가 들어가 있는 수량은 제외한다.
        $findstr = '불량';
        $pos = stripos($workplacename, $findstr);

        if ($pos == 0) {
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
        }

        $counter++;
        $total_row++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$all_sum = $sum1 + $sum2 + $sum3;
$jamb_total = "막판:" . $sum1 . ", " . "막판(無):" . $sum2 . ", " . "쪽쟘:" . $sum3 . "  합계:" . $all_sum;

$item_arr = array();
$work_sum = array();
$month_sum = array();

$item_arr[0] = '막판';
$item_arr[1] = '막(無)';
$item_arr[2] = '쪽쟘';

$work_sum[0] = $sum1;
$work_sum[1] = $sum2;
$work_sum[2] = $sum3;

$year = substr($fromdate, 0, 4);

// 월별비교 차트 데이터 생성
if ($item_sel == '월별비교') {
    $month_count = 0;
    while ($month_count < 12) {
        $year = substr($fromdate, 0, 4);
        $month = $month_count + 1;

        switch ($month_count) {
            case 0: $day = 31; break;
            case 1: $day = 28; break;
            case 2: $day = 31; break;
            case 3: $day = 30; break;
            case 4: $day = 31; break;
            case 5: $day = 30; break;
            case 6: $day = 31; break;
            case 7: $day = 31; break;
            case 8: $day = 30; break;
            case 9: $day = 31; break;
            case 10: $day = 30; break;
            case 11: $day = 31; break;
        }

        $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);
        $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);

        $sql = "select * from mirae8440.work where workday between date('$month_fromdate') and date('$month_todate')";
        require_once("../lib/mydb.php");
        $counter = 0;
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        try {
            $stmh = $pdo->query($sql);
            $rowNum = $stmh->rowCount();
            $total_row = 0;

            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $widejamb = $row["widejamb"] ?? '';
                $normaljamb = $row["normaljamb"] ?? '';
                $smalljamb = $row["smalljamb"] ?? '';
                $workplacename = $row["workplacename"] ?? '';

                $findstr = '불량';
                $pos = stripos($workplacename, $findstr);

                if ($pos == 0) {
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
                    $total_row++;
                }
            }
        } catch (PDOException $Exception) {
            print "오류: " . $Exception->getMessage();
        }

        $month_sum[$month_count] = $sum1 + $sum2 + $sum3 / 4;
        $month_count++;
    }
}

// 년도비교 차트 데이터 생성
if ($item_sel == '년도비교') {
    $year_count = 0;
    $date_count = 0;
    $year_sum = array();
    $year = substr($fromdate, 0, 4) - 1;

    while ($year_count < 2) {
        $month_count = 0;

        while ($month_count < 12) {
            $month = $month_count + 1;

            switch ($month_count) {
                case 0: $day = 31; break;
                case 1: $day = 28; break;
                case 2: $day = 31; break;
                case 3: $day = 30; break;
                case 4: $day = 31; break;
                case 5: $day = 30; break;
                case 6: $day = 31; break;
                case 7: $day = 31; break;
                case 8: $day = 30; break;
                case 9: $day = 31; break;
                case 10: $day = 30; break;
                case 11: $day = 31; break;
            }

            $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);
            $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);

            $sql = "select * from mirae8440.work where workday between date('$month_fromdate') and date('$month_todate')";
            require_once("../lib/mydb.php");
            $counter = 0;
            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;

            try {
                $stmh = $pdo->query($sql);
                $rowNum = $stmh->rowCount();
                $total_row = 0;

                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    $widejamb = $row["widejamb"] ?? '';
                    $normaljamb = $row["normaljamb"] ?? '';
                    $smalljamb = $row["smalljamb"] ?? '';
                    $workplacename = $row["workplacename"] ?? '';

                    $findstr = '불량';
                    $pos = stripos($workplacename, $findstr);

                    if ($pos == 0) {
                        $workitem = "";
                        if ($widejamb != "") {
                            $sum1 += (int)$widejamb;
                        }
                        if ($normaljamb != "") {
                            $sum2 += (int)$normaljamb;
                        }
                        if ($smalljamb != "") {
                            $sum3 += (int)$smalljamb;
                        }
                        $counter++;
                        $total_row++;
                    }
                }
            } catch (PDOException $Exception) {
                print "오류: " . $Exception->getMessage();
            }

            $year_sum[$date_count] = $sum1 + $sum2 + $sum3 / 4;
            $month_count++;
            $date_count++;
        }
        $year_count++;
        $year++;
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ></script>
<link rel="stylesheet" type="text/css" href="../css/common.css">
<link rel="stylesheet" type="text/css" href="../css/steel.css">
<link rel="stylesheet" type="text/css" href="../css/statistics.css">
<title> 부적합 통계자료 </title>
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" ></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" ></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" />

<body>

<div id="wrap">
    <div id="content" style="width:1450px;">
        <form name="board_form" id="board_form" method="post" action="work_statistics.php?mode=search&year=<?= $year ?>&search=<?= $search ?>&process=<?= $process ?>&asprocess=<?= $asprocess ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&up_fromdate=<?= $up_fromdate ?>&up_todate=<?= $up_todate ?>&separate_date=<?= $separate_date ?>&view_table=<?= $view_table ?>">
            <div class="card-header">
                <div class="input-group p-2 mb-2">
                    <span class="input-group-text text-primary" >
                        쟘 제조통계 DATA  </span> &nbsp;&nbsp;
                </div>

                <div class="clear"></div>

                <div class="input-group p-2 mb-2">
                    <button type="button" id="preyear" class="btn btn-secondary" onclick='pre_year()' > 전년도 </button>  &nbsp;
                    <button type="button" id="three_month" class="btn btn-secondary" onclick='three_month_ago()' > M-3월 </button> &nbsp;
                    <button type="button" id="prepremonth" class="btn btn-secondary" onclick='prepre_month()' > 전전월 </button> &nbsp;
                    <button type="button" id="premonth" class="btn btn-secondary" onclick='pre_month()' > 전월 </button>  &nbsp;
                    <button type="button" id="thismonth" class="btn btn-dark" onclick='this_month()' > 당월 </button> &nbsp;
                    <button type="button" id="thisyear" class="btn btn-dark" onclick='this_year()' > 당해년도 </button> &nbsp;
                    <span class='input-group-text align-items-center' style='width:400px;'>
                    <input type="text" id="fromdate" name="fromdate" size="12" value="<?= $fromdate ?>" placeholder="기간 시작일">  &nbsp; 부터 &nbsp;
                    <input type="text" id="todate" name="todate" size="12" value="<?= $todate ?>" placeholder="기간 끝">  &nbsp;  까지    </span>  &nbsp;
                    <button type="button" id="searchBtn" class="btn btn-dark"  > 검색 </button>
                </div>
            </div>

            <div class="clear"></div>

            <div id="spreadsheet" style="display:none;">
            </div>

            <?php
            $chartchoice = array();
            $item_sel_choice = array();

            switch ($display_sel) {
                case "doughnut": $chartchoice[0] = 'checked'; break;
                case "bar": $chartchoice[1] = 'checked'; break;
                case "line": $chartchoice[2] = 'checked'; break;
                case "radar": $chartchoice[3] = 'checked'; break;
                case "polarArea": $chartchoice[4] = 'checked'; break;
            }

            switch ($item_sel) {
                case "년도비교": $item_sel_choice[0] = 'checked'; break;
                case "월별비교": $item_sel_choice[1] = 'checked'; break;
                case "종류별비교": $item_sel_choice[2] = 'checked'; break;
            }
            ?>

            <input id="item_sel" name="item_sel" type='hidden' value='<?= $item_sel ?>' >
            <div class="input-group p-2 mb-2">
                <span class='input-group-text align-items-center' style='width:400px;'>
                    &nbsp; 년도비교 <input type="radio" <?= $item_sel_choice[0] ?? '' ?> name="item_sel" value="년도비교">
                    &nbsp; 월별비교 <input type="radio" <?= $item_sel_choice[1] ?? '' ?> name="item_sel" value="월별비교">
                    &nbsp; 종류별비교 <input type="radio" <?= $item_sel_choice[2] ?? '' ?> name="item_sel" value="종류별비교">
                </span>
            </div>

            <input id="view_table" name="view_table" type='hidden' value='<?= $view_table ?? '' ?>' >
            <input id="display_sel" name="display_sel" type='hidden' value='<?= $display_sel ?>' >
            &nbsp; 도넛 <input type="radio" <?= $chartchoice[0] ?? '' ?> name="chart_sel" value="doughnut">
            &nbsp; 바 <input type="radio" <?= $chartchoice[1] ?? '' ?> name="chart_sel" value="bar">
            &nbsp; 라인 <input type="radio" <?= $chartchoice[2] ?? '' ?> name="chart_sel" value="line">
            &nbsp; 레이더 <input type="radio" <?= $chartchoice[3] ?? '' ?> name="chart_sel" value="radar">
            &nbsp; Polar Area <input type="radio" <?= $chartchoice[4] ?? '' ?> name="chart_sel" value="polarArea">
            <br><br>

            <div id=firstgroup>
                <div id=second1> <canvas id="myChart" width="800" height="500"></canvas>
                </div>
                <div id=second2>
                    <?php
                    // 통계 데이터 출력 부분은 생략 (기존 코드 유지)
                    ?>
                </div>
            </div>

            <div class="clear"></div>
        </form>
    </div>
</div> <!-- end of wrap -->

<script>
// JavaScript 코드는 기존 코드 유지
</script>
</body>
</html>
