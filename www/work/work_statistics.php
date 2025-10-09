<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

$title_message = "JAMB 제조 통계";

include includePath('load_header.php');
?>

<!-- Link to consolidated dashboard style -->
<link rel="stylesheet" href="../css/dashboard-style.css">

<title><?= $title_message ?></title>

<style>
/* Light & Subtle theme customizations for work statistics page */

body {
    background: white;
    overflow-x: hidden;
}

/* Updated glass container using modern design system */
.glass-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.glass-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

/* Modern card header using dashboard style */
.card-header {
    background: var(--dashboard-secondary) !important;
    color: #000 !important;
    border-radius: 12px 12px 0 0 !important;
    padding: 0.25rem !important;
    text-align: center;
    font-size: 0.9rem;
    font-weight: 500;
    margin: -1.5rem -1.5rem 1.5rem -1.5rem;
}

.card-header .text-center {
    color: #000 !important;
    font-weight: 500;
    margin: 0;
}

/* Chart title styling */
.compact-chart-title {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--dashboard-text);
    margin-bottom: 0.5rem;
}

/* Updated table styling */
.table th {
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.5rem;
    background: #f0fbff;
    color: var(--dashboard-text);
    border: none;
    text-align: center;
}

.table-primary {
    background: #f0fbff;
}

.table-primary th {
    background: #f0fbff;
    color: var(--dashboard-text);
}

.table td {
    font-size: 0.85rem;
    padding: 0.5rem;
    border-bottom: 1px solid var(--dashboard-border);
    background: rgba(255, 255, 255, 0.9);
    transition: background-color 0.2s ease;
}

.table-hover tbody tr:hover td {
    background-color: var(--dashboard-hover);
}

/* Updated radio button styling */
.form-check-label {
    font-size: 0.85rem !important;
    font-weight: 500;
    color: var(--dashboard-text);
}

.form-check-input {
    width: 1.1rem;
    height: 1.1rem;
    border-color: var(--dashboard-accent);
}

.form-check-input:checked {
    background-color: var(--dashboard-accent);
    border-color: var(--dashboard-accent);
}

/* Updated badge styling */
.compact-badge {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    color: var(--dashboard-text);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    box-shadow: var(--dashboard-shadow);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Updated chart container */
.chart-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    padding: 0.9rem;
    box-shadow: var(--dashboard-shadow);
    height: 280px;
}

/* Updated table container */
.table-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    padding: 0.75rem;
    margin-left: 1rem;
    box-shadow: var(--dashboard-shadow);
}

/* Text styling improvements */
.text-dark {
    color: var(--dashboard-text) !important;
}

.text-danger {
    color: var(--status-danger) !important;
    font-weight: 600;
}

/* Enhanced table styling */
.table-bordered {
    border: 1px solid var(--dashboard-border);
}

.table-bordered th,
.table-bordered td {
    border: 1px solid var(--dashboard-border);
}

/* Responsive design */
@media (max-width: 768px) {
    .col-md-8, .col-md-4, .col-sm-8, .col-sm-4 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table-container {
        margin-left: 0;
        margin-top: 1rem;
    }

    .compact-badge {
        flex-direction: column;
        gap: 0.5rem;
    }

    .chart-container {
        height: 200px;
    }
}
</style>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

</head>

<body>

<?php require_once(includePath('myheader.php')); ?>

<?php
// 요청 변수 안전하게 초기화
$load_confirm = $_REQUEST["load_confirm"] ?? '';
$display_sel = $_REQUEST["display_sel"] ?? 'bar';
$item_sel = $_REQUEST["item_sel"] ?? '년도비교';
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$search = $_REQUEST["search"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

// 모바일 체크
$chkMobile = function_exists('isMobile') ? isMobile() : false;

$sum = array();

// 기간 초기화 (올해를 날짜 기간으로 설정)
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

$orderby = " ORDER BY workday DESC";
$now = date("Y-m-d");   // 현재 날짜와 크거나 같으면 출고예정으로 구분

// SQL 쿼리 생성
if ($search == "") {
    $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;
} else {
    $sql = "SELECT * FROM mirae8440.work WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
    $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN date('$fromdate') AND date('$Transtodate'))" . $orderby;
}

// 배열 초기화
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
$start_num = 0;

// 차트 선택 변수 초기화
$chartchoice = array('', '', '', '', '');
$item_sel_choice = array('', '', '');

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    $total_row = 0;

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '../work/_row.php';

        // 날짜 포맷 처리 함수
        $formatDate = function($date) {
            return ($date != "0000-00-00" && $date != "1970-01-01" && $date != "") ? date("Y-m-d", strtotime($date)) : "";
        };

        $orderday = $formatDate($orderday);
        $measureday = $formatDate($measureday);
        $drawday = $formatDate($drawday);
        $deadline = $formatDate($deadline);
        $workday = $formatDate($workday);
        $endworkday = $formatDate($endworkday);
        $demand = $formatDate($demand);
        $startday = $formatDate($startday);
        $testday = $formatDate($testday);

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

$unit_sum = $sum1 + $sum2 + $sum3;
$all_sum = $sum1 + $sum2 + $sum3 / 4;

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
$year_sum = array();

// 월별 비교
if ($item_sel === '월별비교') {
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

        $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);  // 날짜형식으로 바꾸기
        $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);  // 날짜형식으로 바꾸기

        if ($search === "") {
            $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$month_fromdate') AND date('$month_todate')";
        }

        if ($search !== "") {
            $sql = "SELECT * FROM mirae8440.work WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
            $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN date('$month_fromdate') AND date('$month_todate'))";
        }
        
        $counter = 0;
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        try {
            $stmh = $pdo->query($sql);
            $rowNum = $stmh->rowCount();
            $total_row = 0;
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $widejamb = $row["widejamb"];
                $normaljamb = $row["normaljamb"];
                $smalljamb = $row["smalljamb"];
                $workplacename = $row["workplacename"];

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

// 년도 비교
if ($item_sel === '년도비교') {
    $year_count = 0;
    $date_count = 0;    // 24개월을 기준으로 데이터를 작성하는 합계변수 2020년1월 2021년1월 이런식으로 계산할 것임.
    $year = substr($fromdate, 0, 4) - 1;

    while ($year_count < 2) {
        $month_count = 0;      // 년도비교 차트 통계 내는 부분
        
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

            $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);  // 날짜형식으로 바꾸기
            $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);  // 날짜형식으로 바꾸기

            if ($search === "") {
                $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$month_fromdate') AND date('$month_todate')";
            }

            if ($search !== "") {
                $sql = "SELECT * FROM mirae8440.work WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
                $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN date('$month_fromdate') AND date('$month_todate'))";
            }

            $counter = 0;
            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;

            try {
                $stmh = $pdo->query($sql);
                $rowNum = $stmh->rowCount();
                $total_row = 0;
                
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    $widejamb = $row["widejamb"];
                    $normaljamb = $row["normaljamb"];
                    $smalljamb = $row["smalljamb"];
                    $workplacename = $row["workplacename"];

                    // 불량이란 단어가 들어가 있는 수량은 제외한다.
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

<form name="board_form" id="board_form" method="post" action="work_statistics.php?mode=search&year=<?= $year ?>&search=<?= $search ?>&process=<?= $process ?>&asprocess=<?= $asprocess ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&up_fromdate=<?= $up_fromdate ?>&up_todate=<?= $up_todate ?>&separate_date=<?= $separate_date ?>&view_table=<?= $view_table ?>">

<?php if ($chkMobile): ?>
	<div class="container">
<?php else: ?>
	<div class="container">
<?php endif; ?>

<div class="glass-container modern-management-card mt-2 mb-2">
    <div class="card-body">
        <div class="card-header modern-dashboard-header mt-1 mb-3 justify-content-center align-items-center text-center">
            <span class="text-center fs-5"><?= $title_message ?></span>
        </div>
        
        <div class="row">
            <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                <!-- 기간설정 칸 -->
                <?php include getDocumentRoot() . '/setdate.php' ?>
            </div>
        </div>
        
        <div class="d-flex justify-content-center align-items-center">
            <div id="spreadsheet" style="display:none;"></div>

            <?php
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

            <input id="item_sel" name="item_sel" type="hidden" value="<?= $item_sel ?>">
            <div class="compact-badge mb-3">
                <label class="form-check-label">년도비교 <input type="radio" class="form-check-input" <?= $item_sel_choice[0] ?> name="item_sel" value="년도비교"></label>
                <label class="form-check-label">월별비교 <input type="radio" class="form-check-input" <?= $item_sel_choice[1] ?> name="item_sel" value="월별비교"></label>
                <label class="form-check-label">종류별비교 <input type="radio" class="form-check-input" <?= $item_sel_choice[2] ?> name="item_sel" value="종류별비교"></label>
            </div>
            
            <div class="compact-badge mb-3">
                <input id="view_table" name="view_table" type="hidden" value="<?= $view_table ?>">
                <input id="display_sel" name="display_sel" type="hidden" value="<?= $display_sel ?>">
                <label class="form-check-label">도넛 <input type="radio" class="form-check-input" <?= $chartchoice[0] ?> name="chart_sel" value="doughnut"></label>
                <label class="form-check-label">바 <input type="radio" class="form-check-input" <?= $chartchoice[1] ?> name="chart_sel" value="bar"></label>
                <label class="form-check-label">라인 <input type="radio" class="form-check-input" <?= $chartchoice[2] ?> name="chart_sel" value="line"></label>
                <label class="form-check-label">레이더 <input type="radio" class="form-check-input" <?= $chartchoice[3] ?> name="chart_sel" value="radar"></label>
                <label class="form-check-label">Polar Area <input type="radio" class="form-check-input" <?= $chartchoice[4] ?> name="chart_sel" value="polarArea"></label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div id="chartMain" class="chart-container compact-chart-container"></div>
            </div>
            <div class="col-md-4">
                <div class="table-container">

                    <?php
                    if ($item_sel == "종류별비교") {
                        echo "<div class='d-flex justify-content-end mb-1'>";
                        echo "<span class='text-dark text-end fs-6'>제작수량 : 쪽쟘 4 → 와이드 1, 단위(SET)</span></div>";
                        echo "<table class='table table-bordered modern-dashboard-table'>";
                        echo '<thead class="table-primary">';
                        echo "<tr>";
                        echo "<th class='text-center'>해당월</th>";
                        echo "<th class='text-center'>제작수량</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        $total_production = 0;

                        for ($i = 0; $i < 3; $i++) {
                            $total_production += $work_sum[$i];
                            echo "<tr>";
                            echo "<td class='text-center'>" . $item_arr[$i] . "</td>";
                            echo "<td class='text-end'>" . number_format($work_sum[$i]) . " (SET)</td>";
                            echo "</tr>";
                        }

                        // 단순합계 행 추가
                        echo "<tr>";
                        echo "<td class='text-center'><strong>단순 합계</strong></td>";
                        echo "<td class='text-end'><strong>" . number_format($unit_sum) . " (SET)</strong></td>";
                        echo "</tr>";
                        
                        // 합계 행 추가
                        echo "<tr>";
                        echo "<td class='text-center text-danger'><strong>변환 합계</strong></td>";
                        echo "<td class='text-end text-danger'><strong>" . number_format($all_sum) . " (SET)</strong></td>";
                        echo "</tr>";

                        echo "</tbody>";
                        echo "</table>";
                    }

                    if ($item_sel == "월별비교") {
                        echo "<span class='fs-6'>제작수량 : 쪽쟘 4 → 와이드 1 SET</span><br><br>";

                        echo "<table class='table table-bordered modern-dashboard-table'>";
                        echo '<thead class="table-primary">';
                        echo "<tr>";
                        echo "<th class='text-center'>해당월</th>";
                        echo "<th class='text-center'>제작수량</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        $months = ['red', 'blue', 'orange', 'green', 'purple', 'blue', 'orange', 'green', 'purple', 'red', 'blue', 'brown'];
                        $total_monthly_production = 0;

                        foreach ($months as $key => $color) {
                            $total_monthly_production += $month_sum[$key] ?? 0;

                            echo "<tr>";
                            echo "<td class='text-center'>" . ($key + 1) . "월</td>";
                            echo "<td class='text-end'>" . number_format($month_sum[$key] ?? 0) . " (SET)</td>";
                            echo "</tr>";
                        }

                        // 합계 행 추가
                        echo "<tr>";
                        echo "<td class='text-center'><strong>합계</strong></td>";
                        echo "<td class='text-end'><strong>" . number_format($total_monthly_production) . " (SET)</strong></td>";
                        echo "</tr>";

                        echo "</tbody>";
                        echo "</table>";
                    }

                    if ($item_sel == "년도비교") {
                        $year_from = substr($fromdate, 0, 4);
                        $year_to = substr($todate, 0, 4);
                        $month_fromdate_year = intval($year_from) - 1;
                        $month_todate_year = intval($year_to);

                        echo "<span class='fs-6'>제작수량 : 쪽쟘 4 → 와이드 1 SET</span><br>";

                        echo "<table class='table table-bordered modern-dashboard-table'>";
                        echo '<thead class="table-primary">';
                        echo "<tr>";
                        echo "<th class='text-center'>해당월</th>";
                        echo "<th class='text-center'>" . $month_fromdate_year . "년 제작</th>";
                        echo "<th class='text-center'>" . $month_todate_year . "년 제작</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        $months = ['grey', 'red', 'blue', 'orange', 'green', 'purple', 'cyan', 'magenta', 'yellow', 'olive', 'maroon', 'navy'];
                        $total_last_year = 0;
                        $total_this_year = 0;

                        for ($i = 0; $i < 12; $i++) {
                            $total_last_year += $year_sum[$i] ?? 0;
                            $total_this_year += $year_sum[($i + 12)] ?? 0;

                            echo "<tr>";
                            echo "<td class='text-center'>" . ($i + 1) . "월</td>";
                            echo "<td class='text-end'>" . number_format($year_sum[$i] ?? 0) . " (SET)</td>";
                            echo "<td class='text-end'>" . number_format($year_sum[($i + 12)] ?? 0) . " (SET)</td>";
                            echo "</tr>";
                        }

                        // 합계 행 추가
                        echo "<tr>";
                        echo "<td class='text-center'><strong>합계</strong></td>";
                        echo "<td class='text-end'><strong>" . number_format($total_last_year) . " (SET)</strong></td>";
                        echo "<td class='text-end'><strong>" . number_format($total_this_year) . " (SET)</strong></td>";
                        echo "</tr>";

                        echo "</tbody>";
                        echo "</table>";
                    }
                    ?>
                </div>
            </div>
        </div>

</div> <!--card-body-->
</div> <!--glass-container -->
</div> <!--container-->

</form>

</body>
</html>

<script>
/* Checkbox change event */
$('input[name="chart_sel"]').change(function() {
    $('input[name="chart_sel"]').each(function() {
        var value = $(this).val();
        var checked = $(this).prop('checked');
        if (checked) {
            $("#display_sel").val(value);
            document.getElementById('board_form').submit();
        }
    });
});

$('input[name="item_sel"]').change(function() {
    $('input[name="item_sel"]').each(function() {
        var value = $(this).val();
        var checked = $(this).prop('checked');
        if (checked) {
            $("#item_sel").val(value);
            document.getElementById('board_form').submit();
        }
    });
});

$(document).ready(function() {
    createChart();
    saveLogData('Jamb 제조통계');
});

function createChart() {
    var item_arr = <?php echo json_encode($item_arr); ?>;
    var work_sum = <?php echo json_encode($work_sum); ?>;
    var month_sum = <?php echo json_encode($month_sum); ?>;
    var year_sum = <?php echo json_encode($year_sum); ?>;
    var chart_type = document.getElementById('display_sel').value;
    var item_type = document.getElementById('item_sel').value;

    // 차트 타입 매핑
    function getHighchartsType(chartType) {
        switch (chartType) {
            case 'bar': return 'column';
            case 'line': return 'line';
            case 'doughnut': return 'pie';
            case 'radar': return 'line';
            case 'polarArea': return 'pie';
            default: return 'column';
        }
    }

    if (item_type == '종류별비교') {
        Highcharts.chart('chartMain', {
            chart: {
                type: getHighchartsType(chart_type),
                backgroundColor: 'rgba(255, 255, 255, 0.9)'
            },
            title: {
                text: 'JAMB 종류별 제작수량',
                style: { fontSize: '14px', fontWeight: '500', color: '#334155' }
            },
            xAxis: {
                categories: [item_arr[0], item_arr[1], item_arr[2]],
                labels: { style: { fontSize: '10px', color: '#334155' } }
            },
            yAxis: {
                title: { text: 'SET 수량', style: { fontSize: '10px', color: '#334155' } },
                labels: { style: { fontSize: '10px', color: '#334155' } }
            },
            series: [{
                name: '제작수량',
                data: [work_sum[0], work_sum[1], work_sum[2]],
                color: '#64748b'
            }],
            tooltip: {
                formatter: function() {
                    return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' SET</b>';
                }
            },
            legend: { enabled: false },
            credits: { enabled: false },
            plotOptions: {
                pie: {
                    innerSize: chart_type === 'doughnut' ? '50%' : 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y} SET'
                    }
                }
            }
        });
    }

    if (item_type == '월별비교') {
        var monthLabels = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];

        Highcharts.chart('chartMain', {
            chart: {
                type: getHighchartsType(chart_type),
                backgroundColor: 'rgba(255, 255, 255, 0.9)'
            },
            title: {
                text: 'JAMB 월별 제작수량',
                style: { fontSize: '14px', fontWeight: '500', color: '#334155' }
            },
            xAxis: {
                categories: monthLabels,
                labels: { style: { fontSize: '10px', color: '#334155' } }
            },
            yAxis: {
                title: { text: 'SET 수량', style: { fontSize: '10px', color: '#334155' } },
                labels: { style: { fontSize: '10px', color: '#334155' } }
            },
            series: [{
                name: '제작수량',
                data: month_sum,
                color: '#64748b'
            }],
            tooltip: {
                formatter: function() {
                    return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' SET</b>';
                }
            },
            legend: { enabled: false },
            credits: { enabled: false },
            plotOptions: {
                pie: {
                    innerSize: chart_type === 'doughnut' ? '50%' : 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y} SET'
                    }
                }
            }
        });
    }

    if (item_type == '년도비교') {
        var yearLabels = [];
        var yearData = [];

        // 전년도와 올해 데이터를 번갈아가며 설정
        for (var i = 0; i < 12; i++) {
            yearLabels.push('전년' + (i + 1) + '월');
            yearLabels.push((i + 1) + '월');
            yearData.push(year_sum[i] || 0);
            yearData.push(year_sum[i + 12] || 0);
        }

        Highcharts.chart('chartMain', {
            chart: {
                type: getHighchartsType(chart_type),
                backgroundColor: 'rgba(255, 255, 255, 0.9)'
            },
            title: {
                text: 'JAMB 년도별 제작수량',
                style: { fontSize: '14px', fontWeight: '500', color: '#334155' }
            },
            xAxis: {
                categories: yearLabels,
                labels: {
                    style: { fontSize: '10px', color: '#334155' },
                    rotation: -45
                }
            },
            yAxis: {
                title: { text: 'SET 수량', style: { fontSize: '10px', color: '#334155' } },
                labels: { style: { fontSize: '10px', color: '#334155' } }
            },
            series: [{
                name: '제작수량',
                data: yearData,
                color: '#64748b'
            }],
            tooltip: {
                formatter: function() {
                    return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' SET</b>';
                }
            },
            legend: { enabled: false },
            credits: { enabled: false },
            plotOptions: {
                pie: {
                    innerSize: chart_type === 'doughnut' ? '50%' : 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y} SET'
                    }
                }
            }
        });
    }
}
</script>
