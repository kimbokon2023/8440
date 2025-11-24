<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 쿠키 및 설정
$Allmonth = isset($_COOKIE['Allmonth']) ? $_COOKIE['Allmonth'] : 'false';
$title_message = '원자재 부적합 통계';

// 요청 파라미터 초기화
$tabName = $_REQUEST["tabName"] ?? '';
$load_confirm = $_REQUEST["load_confirm"] ?? '';
$display_sel = $_REQUEST["display_sel"] ?? 'bar';
$item_sel = $_REQUEST["item_sel"] ?? '년도비교';
$check = $_REQUEST["check"] ?? ($_POST["check"] ?? '');
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 기간 설정
if ($fromdate == "") {
    $fromdate = date("Y", time()) . "-01-01";
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
$sql = '';

// 현재 날짜
$now = date("Y-m-d");

// 검색 모드 SQL 생성
if ($mode == "search") {
    if ($search == "") {
        $sql = "SELECT * FROM {$DB}.work WHERE workday BETWEEN date('{$fromdate}') AND date('{$Transtodate}')" . $orderby;
    } else {
        // 기본 SQL Injection 방어
        $search_safe = str_replace("'", "''", $search);
        $sql = "SELECT * FROM {$DB}.work WHERE (bad_choice LIKE '%{$search_safe}%') " .
               "AND (workday BETWEEN date('{$fromdate}') AND date('{$Transtodate}'))" . $orderby;
    }
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
   
// SQL 실행
$total_row = 0;

if (!empty(trim($sql))) {
    try {
        $stmh = $pdo->prepare($sql);
        $stmh->execute();
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
            
            $workday_arr[$counter] = $workday;
            $workplacename_arr[$counter] = $workplacename;
            $address_arr[$counter] = $address;
            $delicompany_arr[$counter] = $delicompany;
            $delipay_arr[$counter] = $delipay;
            $secondord_arr[$counter] = $secondord;
            $worker_arr[$counter] = $worker;
            
            // 불량이란 단어가 들어가 있는 수량은 제외
            $findstr = '불량';
            $pos = stripos($workplacename, $findstr);
            
            if ($pos === false || $pos > 0) {
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
    } catch (PDOException $ex) {
        error_log("작업 데이터 조회 오류: " . $ex->getMessage());
    }
}

$all_sum = $sum1 + $sum2 + $sum3;
$jamb_total = "막판:" . $sum1 . ", 막판(無):" . $sum2 . ", 쪽쟘:" . $sum3 . " 합계:" . $all_sum;

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

// 월별 비교 데이터 처리
if ($item_sel == '월별비교') {
    $month_count = 0;
    
    while ($month_count < 12) {
        $year = substr($fromdate, 0, 4);
        $month = $month_count + 1;
        
        // 월별 일수 계산
        switch ($month_count) {
            case 0:  $day = 31; break;
            case 1:  $day = 28; break;
            case 2:  $day = 31; break;
            case 3:  $day = 30; break;
            case 4:  $day = 31; break;
            case 5:  $day = 30; break;
            case 6:  $day = 31; break;
            case 7:  $day = 31; break;
            case 8:  $day = 30; break;
            case 9:  $day = 31; break;
            case 10: $day = 30; break;
            case 11: $day = 31; break;
        }
        
        $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);
        $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);
        
        $sql = "SELECT * FROM {$DB}.work WHERE workday BETWEEN date('{$month_fromdate}') AND date('{$month_todate}') ORDER BY workday DESC";
        
        $counter = 0;
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        
        try {
            $stmh = $pdo->prepare($sql);
            $stmh->execute();
            $rowNum = $stmh->rowCount();
            $total_row = 0;
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $widejamb = $row["widejamb"] ?? '';
                $normaljamb = $row["normaljamb"] ?? '';
                $smalljamb = $row["smalljamb"] ?? '';
                $workplacename = $row["workplacename"] ?? '';
                
                // 불량이란 단어가 들어가 있는 수량은 제외
                $findstr = '불량';
                $pos = stripos($workplacename, $findstr);
                
                if ($pos === false || $pos > 0) {
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
        } catch (PDOException $ex) {
            error_log("월별 데이터 조회 오류: " . $ex->getMessage());
        }
        
        $month_sum[$month_count] = $sum1 + $sum2 + $sum3 / 4;
        $month_count++;
    }
}

// 년도 비교 데이터 처리
if ($item_sel == '년도비교') {
    $year_count = 0;
    $date_count = 0;
    $year_sum = array();
    $year = substr($fromdate, 0, 4) - 1;
				
    while ($year_count < 2) {
        $month_count = 0;
        
        while ($month_count < 12) {
            $month = $month_count + 1;
            
            // 월별 일수 계산
            switch ($month_count) {
                case 0:  $day = 31; break;
                case 1:  $day = 28; break;
                case 2:  $day = 31; break;
                case 3:  $day = 30; break;
                case 4:  $day = 31; break;
                case 5:  $day = 30; break;
                case 6:  $day = 31; break;
                case 7:  $day = 31; break;
                case 8:  $day = 30; break;
                case 9:  $day = 31; break;
                case 10: $day = 30; break;
                case 11: $day = 31; break;
            }
            
            $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);
            $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);
            
            $sql = "SELECT * FROM {$DB}.work WHERE workday BETWEEN date('{$month_fromdate}') AND date('{$month_todate}')";
            
            $counter = 0;
            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;
            
            try {
                $stmh = $pdo->prepare($sql);
                $stmh->execute();
                $rowNum = $stmh->rowCount();
                $total_row = 0;
                
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    $widejamb = $row["widejamb"] ?? '';
                    $normaljamb = $row["normaljamb"] ?? '';
                    $smalljamb = $row["smalljamb"] ?? '';
                    $workplacename = $row["workplacename"] ?? '';
                    
                    // 불량이란 단어가 들어가 있는 수량은 제외
                    $findstr = '불량';
                    $pos = stripos($workplacename, $findstr);
                    
                    if ($pos === false || $pos > 0) {
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
            } catch (PDOException $ex) {
                error_log("년도 비교 데이터 조회 오류: " . $ex->getMessage());
            }
            
            $year_sum[$date_count] = $sum1 + $sum2 + $sum3 / 4;
            $month_count++;
            $date_count++;
        }
        
        $year_count++;
        $year++;
    }
}

// 추가 파라미터 초기화
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

// URL 파라미터 생성
$form_params = http_build_query(array(
    'mode' => 'search',
    'year' => $year,
    'search' => $search,
    'process' => $process,
    'asprocess' => $asprocess,
    'fromdate' => $fromdate,
    'todate' => $todate,
    'up_fromdate' => $up_fromdate,
    'up_todate' => $up_todate,
    'separate_date' => $separate_date,
    'view_table' => $view_table
));
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?= htmlspecialchars($title_message) ?></title>

<style>
/* 모바일 환경 최적화 */
@media (max-width: 768px) {
    /* 컨테이너 최적화 */
    .container,
    .container-fluid {
        padding: 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 최적화 */
    .card {
        margin: 0.5rem auto !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 제목 최적화 */
    h4, h5 {
        font-size: 1.125rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        text-align: center !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
    }
    
    .d-flex.justify-content-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
    
    /* jQuery DataTable 숨기기 */
    .dataTables_length,
    .dataTables_filter {
        display: none !important;
    }
    
    /* 테이블을 카드 형식으로 변환 */
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    
    table.table thead {
        display: none !important;
    }
    
    table.table tbody {
        display: block !important;
        width: 100% !important;
    }
    
    table.table tbody tr {
        display: block !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        margin: 0.5rem auto 0.75rem auto !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        padding: 0.75rem !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    table.table tbody tr td {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    table.table tbody tr td::before {
        content: attr(data-label) !important;
        font-weight: bold !important;
        font-size: 0.75rem !important;
        color: #666 !important;
        margin-right: 0.5rem !important;
        min-width: 80px !important;
        flex-shrink: 0 !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모든 텍스트 요소 강제 줄바꿈 */
    p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        display: inline !important;
        overflow: visible !important;
    }
    
    /* 체크박스 최적화 */
    input[type="checkbox"] {
        margin-right: 0.5rem !important;
    }
    
    label {
        display: flex !important;
        align-items: center !important;
        word-wrap: break-word !important;
    }
    
    /* 네비게이션 탭 최적화 */
    .nav-pills {
        flex-direction: column !important;
        width: 100% !important;
    }
    
    .nav-pills .nav-link {
        width: 100% !important;
        margin: 0.25rem 0 !important;
        text-align: center !important;
    }
    
    /* 차트 최적화 */
    #mychart-all,
    #mychart-filtered {
        width: 100% !important;
        height: 300px !important;
        max-width: 100% !important;
    }
    
    /* 모달 최적화 */
    .modal {
        padding: 0 !important;
    }
    
    .modal-dialog {
        margin: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        height: 100vh !important;
        max-height: 100vh !important;
    }
    
    .modal-content {
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        height: 100vh !important;
        max-height: 100vh !important;
        border-radius: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        overflow-y: auto !important;
        flex: 1 1 auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    /* SweetAlert2 모달 최적화 */
    .swal2-popup {
        width: 90% !important;
        max-width: 90% !important;
        padding: 1rem !important;
        font-size: 0.875rem !important;
    }
    
    .swal2-title {
        font-size: 1.125rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-content {
        font-size: 0.875rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-actions {
        flex-direction: column !important;
        gap: 0.5rem !important;
    }
    
    .swal2-confirm,
    .swal2-cancel {
        width: 100% !important;
        margin: 0 !important;
    }
    
    /* '기간' 버튼 숨기기 */
    #showdate {
        display: none !important;
    }
    
    /* row 최적화 */
    .row {
        margin: 0 !important;
    }
    
    .col-5 {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
}

/* PC 환경 버튼 간격 최적화 */
@media (min-width: 769px) {
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-start .btn {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }
}
</style>

</head>

<body>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<form name="board_form" id="board_form" method="post" action="statistics.php?<?= $form_params ?>">
    <input type="hidden" id="tabName" name="tabName" value="<?= htmlspecialchars($tabName) ?>">
    
    <div class="container mt-5 mb-5">
        <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
            <h4><?= htmlspecialchars($title_message) ?></h4>
            <button type="button" class="btn btn-dark btn-sm mx-3" onclick="location.reload();" title="새로고침">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        
        <div class="row">
            <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                <!-- 기간설정 칸 -->
                <?php include getDocumentRoot() . '/setdate.php' ?>
            </div>
        </div>
        
        <div id="qualityIssues" class="tabcontent" style="display: none;">
            <!-- Improved UX: Display both data sets separately -->
            <div class="d-flex mb-3 mt-2 justify-content-center">
                <h5>
                    <label>
                        <input type="checkbox" id="Allmonth" name="Allmonth" 
                               value="<?= htmlspecialchars($Allmonth) ?>" 
                               <?= $Allmonth === 'true' ? 'checked' : '' ?> 
                               onchange="updateAllmonth()">
                        <span class="checkmark"></span>
                        (기간무시)월별그래프 표시
                    </label>
                </h5>
            </div>
            
            <!-- Navigation tabs for two data sections -->
            <div class="d-flex justify-content-center mb-4">
                <ul class="nav nav-pills" id="dataTypeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-data-tab" data-bs-toggle="pill" 
                                data-bs-target="#all-data" type="button" role="tab">전체 부적합 데이터</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="filtered-data-tab" data-bs-toggle="pill" 
                                data-bs-target="#filtered-data" type="button" role="tab">소장/업체/기타 제외 데이터</button>
                    </li>
                </ul>
            </div>

            
            <?php
            // 환경파일 불러오기
            $readIni = array();
            $ini_file = "../steel/settings.ini";
            
            if (file_exists($ini_file)) {
                $readIni = parse_ini_file($ini_file, false);
            }
            
            $PO = isset($readIni['PO']) ? (int)str_replace(',', '', $readIni['PO']) : 0;
            $CR = isset($readIni['CR']) ? (int)str_replace(',', '', $readIni['CR']) : 0;
            $EGI = isset($readIni['EGI']) ? (int)str_replace(',', '', $readIni['EGI']) : 0;
            $HL304 = isset($readIni['HL304']) ? (int)str_replace(',', '', $readIni['HL304']) : 0;
            $MR304 = isset($readIni['MR304']) ? (int)str_replace(',', '', $readIni['MR304']) : 0;
            $HL201 = isset($readIni['HL201']) ? (int)str_replace(',', '', $readIni['HL201']) : 0;
            $MR201 = isset($readIni['MR201']) ? (int)str_replace(',', '', $readIni['MR201']) : 0;
            $etcsteel = isset($readIni['etcsteel']) ? (int)str_replace(',', '', $readIni['etcsteel']) : 0;
            $I3HL304 = isset($readIni['I3HL304']) ? (int)str_replace(',', '', $readIni['I3HL304']) : 0;
            $I3MR304 = isset($readIni['I3MR304']) ? (int)str_replace(',', '', $readIni['I3MR304']) : 0;

// // 값 화면에 출력 (한글 설명 포함)
// echo "<div class='container mb-3'>";
// echo "<h5>원자재 단가 (1kg 기준):</h5>";
// echo "<ul>";
// echo "<li>PO: $PO 원</li>";
// echo "<li>CR: $CR 원</li>";
// echo "<li>EGI: $EGI 원</li>";
// echo "<li>304 HL: $HL304 원</li>";
// echo "<li>304 MR: $MR304 원</li>";
// echo "<li>201 HL: $HL201 원</li>";
// echo "<li>201 MR: $MR201 원</li>";
// echo "<li>기타강종(etcsteel): $etcsteel 원</li>";
// echo "<li>I3HL304: $I3HL304 원</li>";
// echo "<li>I3MR304: $I3MR304 원</li>";
// echo "</ul>";
// echo "</div>";


            // 가격 배열 (PHP 7.3 호환)
            $price_per_kg = array(
                'CR' => $CR,
                'PO' => $PO,
                'EGI' => $EGI,
                '304 HL' => $HL304,
                '201 2B MR' => $MR201,
                '201 MR' => $MR201,
                '201 HL' => $HL201,
                '304 MR' => $MR304,
                'etcsteel' => $etcsteel,
                'I3HL304' => $I3HL304,
                'I3MR304' => $I3MR304
            );

// Create tab content containers
echo '<div class="tab-content" id="dataTypeTabContent">';
echo '<div class="tab-pane fade show active" id="all-data" role="tabpanel">';
echo '<h4 class="text-center mb-4">전체 부적합 데이터 (소장/업체/기타 포함)</h4>';

            // 전체 데이터 쿼리 (모든 부적합 포함)
            $sql_all = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, " .
                       "outdate, outworkplace, item, spec, steelnum, bad_choice " .
                       "FROM {$DB}.steel " .
                       "WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재') " .
                       "AND outdate BETWEEN date('{$fromdate}') AND date('{$Transtodate}') " .
                       "ORDER BY year, month";
            
            $total_sum_all = 0;
            
            try {
                // 전체 데이터 처리
                $stmh = $pdo->query($sql_all);

		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			$item = trim($row["item"]);
			$spec = trim($row["spec"]);
			$steelnum = $row["steelnum"];
			$bad_choice = $row["bad_choice"];

			$spec_parts = explode('*', $spec);
			$thickness = $spec_parts[0];
			$width = $spec_parts[1];
			$length = $spec_parts[2];

			$weight = $thickness * $width * $length/1000;
          if((int)$steelnum !== 0)  // 수량이 1이상일때 잔재는 제외함
		  {
			if (array_key_exists($item, $price_per_kg)) {
				$price = $price_per_kg[$item];
			} else {
				$price = $price_per_kg['etcsteel'];
			}

			$total_price = ($weight * $price * $steelnum * 7.93)/1000;
			$total_sum_all += $total_price;
		  }
		  else
		  {
			$total_price = 0 ;
			$total_sum_all += $total_price;
		  }
		}

            } catch (PDOException $ex) {
                error_log("전체 데이터 조회 오류: " . $ex->getMessage());
            }

// 전체 데이터 비용 표시
$formatted_total_sum_all = number_format($total_sum_all, 0, '.', ',');

	echo '
	<div class="container">
	  <div class="row justify-content-center">
		<div class="col-5">
		  <div class="card shadow-sm bg-danger text-white m-1 p-1">
			<div class="card-body text-center">
			  <h4 class="card-title">전체 부적합 비용</h4>
			  <h4 class="card-text mb-1">' . $formatted_total_sum_all . '원</h4>
			</div>
		  </div>
		</div>
	  </div>
	</div>';


// 전체 데이터 차트 구현
if ($Allmonth === 'true') {
    $sql_chart_all = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, outdate, outworkplace, item, spec, steelnum, bad_choice
            FROM mirae8440.steel
            WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재')
            ORDER BY year, month";
} else {
    $sql_chart_all = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, outdate, outworkplace, item, spec, steelnum, bad_choice
            FROM mirae8440.steel
            WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재')
            AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
            ORDER BY year, month";
}




//print 'allmonth ' . $Allmonth ; 
// var_dump($sql) ; 


// 전체 데이터 월별 합계 계산
$monthly_totals_all = [];

try{
    $stmh = $pdo->query($sql_chart_all);

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $bad_choice = $row["bad_choice"];

        $spec_parts = explode('*', $spec);
        $thickness = $spec_parts[0];
        $width = $spec_parts[1];
        $length = $spec_parts[2];

        $weight = $thickness * $width * $length/1000;
        if((int)$steelnum !== 0) {
            if (array_key_exists($item, $price_per_kg)) {
                $price = $price_per_kg[$item];
            } else {
                $price = $price_per_kg['etcsteel'];
            }

            $total_price = ($weight * $price * $steelnum * 7.93)/1000;
        } else {
            $total_price = 0;
        }

        $year = $row["year"];
        $month = $row["month"];

        if (!isset($monthly_totals_all["$year-$month"])) {
            $monthly_totals_all["$year-$month"] = 0;
        }

        $monthly_totals_all["$year-$month"] += $total_price;
    }

            } catch (PDOException $ex) {
                error_log("전체 데이터 조회 오류: " . $ex->getMessage());
            }

// 필터링된 데이터 월별 합계 계산 (소장/업체/기타 제외)
$monthly_totals_filtered = [];

// 필터링된 데이터 SQL 쿼리
if ($Allmonth == 1) {
    $sql_chart_filtered = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, outdate, outworkplace, item, spec, steelnum, bad_choice
            FROM mirae8440.steel
            WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재'
            AND bad_choice != '소장' AND bad_choice != '소장불량' AND bad_choice != '업체' AND bad_choice != '업체불량' AND bad_choice != '기타')
            ORDER BY year, month";
} else {
    $sql_chart_filtered = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, outdate, outworkplace, item, spec, steelnum, bad_choice
            FROM mirae8440.steel
            WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재'
            AND bad_choice != '소장' AND bad_choice != '소장불량' AND bad_choice != '업체' AND bad_choice != '업체불량' AND bad_choice != '기타')
            AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
            ORDER BY year, month";
}

try{
    $stmh_filtered = $pdo->query($sql_chart_filtered);

    while ($row = $stmh_filtered->fetch(PDO::FETCH_ASSOC)) {
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $bad_choice = $row["bad_choice"];

        $spec_parts = explode('*', $spec);
        $thickness = $spec_parts[0];
        $width = $spec_parts[1];
        $length = $spec_parts[2];

        $weight = $thickness * $width * $length/1000;
        if((int)$steelnum !== 0) {
            if (array_key_exists($item, $price_per_kg)) {
                $price = $price_per_kg[$item];
            } else {
                $price = $price_per_kg['etcsteel'];
            }

            $total_price = ($weight * $price * $steelnum * 7.93)/1000;
        } else {
            $total_price = 0;
        }

        $year = $row["year"];
        $month = $row["month"];

        if (!isset($monthly_totals_filtered["$year-$month"])) {
            $monthly_totals_filtered["$year-$month"] = 0;
        }

        $monthly_totals_filtered["$year-$month"] += $total_price;
    }

            } catch (PDOException $ex) {
                error_log("전체 데이터 조회 오류: " . $ex->getMessage());
            }

// 필터링된 데이터 비용 표시
$formatted_total_sum_filtered = number_format(array_sum($monthly_totals_filtered), 0, '.', ',');

// 전체 데이터 탭 내용 완성
echo '<div class="mt-4">';
echo '<div id="mychart-all" style="width: 100%; height: 400px;"></div>';
echo '</div>';

// 전체 데이터 테이블 다시 쿼리해서 표시
echo '<div class="mt-4">';
echo '<table id="myTable-all" class="table table-striped table-hover">';
echo '<thead class="table-dark">';
echo '<tr>';
echo '<th class="text-center">출고일</th>';
echo '<th class="text-center">불량유형</th>';
echo '<th class="text-center">현장명</th>';
echo '<th class="text-center">종류</th>';
echo '<th class="text-center">규격</th>';
echo '<th class="text-center">수량</th>';
echo '<th class="text-center">발생비용</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// 전체 데이터 다시 조회해서 테이블 생성
try {
    $stmh_table_all = $pdo->query($sql_all);
    while($row = $stmh_table_all->fetch(PDO::FETCH_ASSOC)) {
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $bad_choice = $row["bad_choice"];

        $spec_parts = explode('*', $spec);
        $thickness = $spec_parts[0];
        $width = $spec_parts[1];
        $length = $spec_parts[2];

        $weight = $thickness * $width * $length/1000;
        if((int)$steelnum !== 0) {
            if (array_key_exists($item, $price_per_kg)) {
                $price = $price_per_kg[$item];
            } else {
                $price = $price_per_kg['etcsteel'];
            }
            $total_price = ($weight * $price * $steelnum * 7.93)/1000;
        } else {
            $total_price = 0;
        }

        $formatted_price = number_format($total_price, 0, '.', ',');

        echo '<tr>';
        echo '<td class="text-center" data-label="출고일">' . htmlspecialchars($row['outdate']) . '</td>';
        echo '<td class="text-center" data-label="불량유형">' . htmlspecialchars($bad_choice) . '</td>';
        echo '<td class="text-start" data-label="현장명">' . htmlspecialchars($row['outworkplace']) . '</td>';
        echo '<td class="text-center" data-label="종류">' . htmlspecialchars($item) . '</td>';
        echo '<td class="text-center" data-label="규격">' . htmlspecialchars($spec) . '</td>';
        echo '<td class="text-center" data-label="수량">' . htmlspecialchars($steelnum) . '</td>';
        echo '<td class="text-end" data-label="발생비용">' . $formatted_price . '원</td>';
        echo '</tr>';
    }
            } catch (PDOException $ex) {
                error_log("전체 데이터 조회 오류: " . $ex->getMessage());
            }

echo '</tbody>';
echo '</table>';
echo '</div>';
echo '</div>'; // 전체 데이터 탭 종료

// 필터링된 데이터 탭 시작
echo '<div class="tab-pane fade" id="filtered-data" role="tabpanel">';
echo '<h4 class="text-center mb-4">소장/업체/기타 제외 데이터</h4>';

echo '
<div class="container">
  <div class="row justify-content-center">
    <div class="col-5">
      <div class="card shadow-sm bg-warning text-dark m-1 p-1">
        <div class="card-body text-center">
          <h4 class="card-title">필터링된 부적합 비용</h4>
          <h4 class="card-text mb-1">' . $formatted_total_sum_filtered . '원</h4>
        </div>
      </div>
    </div>
  </div>
</div>';

echo '<div class="mt-4">';
echo '<div id="mychart-filtered" style="width: 100%; height: 400px;"></div>';
echo '</div>';

// 필터링된 데이터 테이블
echo '<div class="mt-4">';
echo '<table id="myTable-filtered" class="table table-striped table-hover">';
echo '<thead class="table-dark">';
echo '<tr>';
echo '<th class="text-center">출고일</th>';
echo '<th class="text-center">불량유형</th>';
echo '<th class="text-center">현장명</th>';
echo '<th class="text-center">종류</th>';
echo '<th class="text-center">규격</th>';
echo '<th class="text-center">수량</th>';
echo '<th class="text-center">발생비용</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// 필터링된 데이터 조회해서 테이블 생성
try {
    $stmh_table_filtered = $pdo->query($sql_chart_filtered);
    while($row = $stmh_table_filtered->fetch(PDO::FETCH_ASSOC)) {
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $bad_choice = $row["bad_choice"];

        $spec_parts = explode('*', $spec);
        $thickness = $spec_parts[0];
        $width = $spec_parts[1];
        $length = $spec_parts[2];

        $weight = $thickness * $width * $length/1000;
        if((int)$steelnum !== 0) {
            if (array_key_exists($item, $price_per_kg)) {
                $price = $price_per_kg[$item];
            } else {
                $price = $price_per_kg['etcsteel'];
            }
            $total_price = ($weight * $price * $steelnum * 7.93)/1000;
        } else {
            $total_price = 0;
        }

        $formatted_price = number_format($total_price, 0, '.', ',');

        echo '<tr>';
        echo '<td class="text-center" data-label="출고일">' . htmlspecialchars($row['outdate']) . '</td>';
        echo '<td class="text-center" data-label="불량유형">' . htmlspecialchars($bad_choice) . '</td>';
        echo '<td class="text-start" data-label="현장명">' . htmlspecialchars($row['outworkplace']) . '</td>';
        echo '<td class="text-center" data-label="종류">' . htmlspecialchars($item) . '</td>';
        echo '<td class="text-center" data-label="규격">' . htmlspecialchars($spec) . '</td>';
        echo '<td class="text-center" data-label="수량">' . htmlspecialchars($steelnum) . '</td>';
        echo '<td class="text-end" data-label="발생비용">' . $formatted_price . '원</td>';
        echo '</tr>';
    }
            } catch (PDOException $ex) {
                error_log("전체 데이터 조회 오류: " . $ex->getMessage());
            }

echo '</tbody>';
echo '</table>';
echo '</div>';
echo '</div>'; // 필터링된 데이터 탭 종료
echo '</div>'; // tab-content 종료
echo '</div>'; // qualityIssues 종료

?>
<script>
// ES5 호환 JavaScript

var dataTable;
var errorstatpageNumber;

$(document).ready(function() {
    // 모바일 환경에서 '기간' 버튼 숨기기
    if (window.innerWidth <= 768) {
        $('#showdate').hide();
    }
    
    // 창 크기 변경 시 '기간' 버튼 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('#showdate').hide();
        } else {
            $('#showdate').show();
        }
    });
    
    // 모바일 환경에서 jQuery DataTable 컨트롤 숨기기
    if (window.innerWidth <= 768) {
        $('.dataTables_length, .dataTables_filter').hide();
    }
    
    // 창 크기 변경 시 DataTable 컨트롤 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    });
    
    // 전체 데이터 테이블 DataTables 초기 설정
    var dataTableAll = $('#myTable-all').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });
    
    // 필터링된 데이터 테이블 DataTables 초기 설정
    var dataTableFiltered = $('#myTable-filtered').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });
    
    // 기본적으로 전체 데이터 테이블을 dataTable 변수로 설정
    dataTable = dataTableAll;
    
    // 페이지 번호 복원
    var savedPageNumber = getCookie('errorstatpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }
    
    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        errorstatpageNumber = dataTable.page.info().page + 1;
        setCookie('errorstatpageNumber', errorstatpageNumber, 10);
    });
    
    // 페이지 길이 변경 이벤트
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw();
        
        savedPageNumber = getCookie('errorstatpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('errorstatpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

$(document).ready(function() {
    // PHP에서 계산된 월별 합계를 JavaScript로 전달
    var monthly_totals_all = <?php echo json_encode($monthly_totals_all ?? array(), JSON_UNESCAPED_UNICODE); ?>;
    
    // x축 라벨 및 데이터 시리즈 준비
    var categories_all = Object.keys(monthly_totals_all);
    var data_all = Object.values(monthly_totals_all).map(function(total) {
        return parseFloat(total.toFixed(2));
    });

        // 그래프 생성
        var chartHeight = window.innerWidth <= 768 ? 300 : 400;
        Highcharts.chart('mychart-all', {
            chart: {
                type: 'column',
                height: chartHeight,
                responsive: true
            },
            title: {
                text: '전체 원자재 월별 부적합 비용 (소장/업체/기타 포함)'
            },
            xAxis: {
                categories: categories_all,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: '비용 (원)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:15px">{point.key}</span><table>',
					pointFormatter: function() {
						return '<tr><td style="color:' + this.series.color + ';padding:0">' + this.series.name + ': </td>' +
							'<td style="padding:0"><b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + ' 원</b></td></tr>';
					},
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: '전체 부적합 비용',
                data: data_all,
                color: '#dc3545'
            }]
        });

    // 필터링된 데이터 차트
    var monthly_totals_filtered = <?php echo json_encode($monthly_totals_filtered ?? array(), JSON_UNESCAPED_UNICODE); ?>;
    var categories_filtered = Object.keys(monthly_totals_filtered);
    var data_filtered = Object.values(monthly_totals_filtered).map(function(total) {
        return parseFloat(total.toFixed(2));
    });

        // 필터링된 데이터 그래프 생성
        var chartHeightFiltered = window.innerWidth <= 768 ? 300 : 400;
        Highcharts.chart('mychart-filtered', {
            chart: {
                type: 'column',
                height: chartHeightFiltered,
                responsive: true
            },
            title: {
                text: '필터링된 원자재 월별 부적합 비용 (소장/업체/기타 제외)'
            },
            xAxis: {
                categories: categories_filtered,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: '비용 (원)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:15px">{point.key}</span><table>',
                pointFormatter: function() {
                    return '<tr><td style="color:' + this.series.color + ';padding:0">' + this.series.name + ': </td>' +
                        '<td style="padding:0"><b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + ' 원</b></td></tr>';
                },
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: '필터링된 부적합 비용',
                data: data_filtered,
                color: '#ffc107'
            }]
        });

    var tabName = '<?php echo htmlspecialchars($tabName); ?>';
    console.log(tabName);
    
    openTab('qualityIssues');
});

function updateAllmonth() {
    var isChecked = document.getElementById('Allmonth').checked;
    document.cookie = "Allmonth=" + isChecked + ";path=/";
    document.getElementById('board_form').submit();
}

function openTab(tabName) {
    var i, tabcontent, tablinks;
    
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    document.getElementById(tabName).style.display = "block";
    $('#tabName').val(tabName);
}
</script>

<script>
// 유틸리티 함수
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

function List_name(worker) {
    var name = '<?php echo htmlspecialchars($user_name); ?>';
    $("#search").val(worker);
    $('#board_form').submit();
}

// 서버에 작업 기록
$(document).ready(function() {
    saveLogData('원자재 부적합 통계');
});
</script>

</body>
</html>    