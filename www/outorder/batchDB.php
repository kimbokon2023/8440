<?php
/**
 * 외주 청구일/출고일 일괄처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화 (?? '' 형태)
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

include getDocumentRoot() . '/load_header.php';

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $redirectUrl = $_SESSION["WebSite"] ?? '';
    header("Location:" . $redirectUrl . "login/login_form.php");
    exit;
}
?>

<title>외주 청구일/출고일 일괄처리</title>

<style>
/* 모바일 최적화 스타일 */
@media (max-width: 768px) {
    /* 컨테이너 및 카드 최적화 */
    .container-fluid,
    .container {
        padding: 0.75rem 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .card {
        margin: 0.5rem auto !important;
        border-radius: 0.5rem !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-header {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 창닫기 버튼 모바일 최적화 */
    .card-header .d-flex.justify-content-between {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    .card-header #closeBtn {
        width: auto !important;
        max-width: none !important;
        flex-shrink: 0 !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 제목 최적화 */
    h4, h5, h6 {
        font-size: 1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        text-align: center !important;
        margin-bottom: 0.75rem !important;
        padding: 0 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: nowrap !important;
        min-height: 40px !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    /* d-flex 컨테이너 안의 버튼은 자동 크기 */
    .d-flex .btn,
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-start .btn {
        width: auto !important;
        max-width: none !important;
        margin: 0.25rem !important;
        flex-shrink: 0 !important;
    }
    
    .btn-sm {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.6rem !important;
        min-height: 36px !important;
    }
    
    /* 입력 필드 최적화 */
    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="file"],
    textarea,
    select,
    .form-control {
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* 기간 설정 UI 최적화 */
    .d-flex.mt-1.mb-2.justify-content-center.align-items-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
    
    .d-flex.mt-1.mb-2.justify-content-center.align-items-center > * {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* 버튼 그룹 최적화 */
    .d-flex.mb-1.mt-1.justify-content-center.align-items-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
    
    .d-flex.mb-1.mt-1.justify-content-center.align-items-center > * {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* TUI Grid 숨기기 */
    #grid {
        display: none !important;
    }
    
    /* 모바일 카드 컨테이너 */
    #mobile-grid-cards {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 0.25rem !important;
    }
    
    .mobile-grid-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin: 0.5rem auto 0.75rem auto !important;
        padding: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        overflow-x: hidden;
        overflow-y: visible !important;
        box-sizing: border-box;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
        width: 100%;
        max-width: 100% !important;
        box-sizing: border-box;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .mobile-grid-card-label {
        font-weight: bold;
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 0.25rem;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .mobile-grid-card-value {
        font-size: 0.9rem;
        color: #333;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 0 !important;
        overflow: visible !important;
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
    
    /* badge 최적화 */
    .badge {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
}

/* PC 환경에서 모바일 카드 숨기기 */
@media (min-width: 769px) {
    #mobile-grid-cards {
        display: none !important;
    }
    #grid {
        display: block !important;
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

/* 모달 최적화 */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem !important;
        max-width: calc(100% - 1rem) !important;
    }
    
    .modal-dialog.modal-lg {
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    .modal-content {
        border-radius: 0.5rem !important;
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        min-height: 50px !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-title {
        font-size: 1rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
    }
    
    .modal-header .btn-close {
        margin: 0 !important;
        padding: 0.5rem !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.9rem !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-footer .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.25rem !important;
    }
    
    /* SweetAlert2 모달 최적화 */
    .swal2-popup {
        width: 90% !important;
        max-width: 90% !important;
        padding: 1rem !important;
        font-size: 0.9rem !important;
    }
    
    .swal2-title {
        font-size: 1.1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-content {
        font-size: 0.875rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-actions {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    .swal2-confirm,
    .swal2-cancel {
        font-size: 0.875rem !important;
        padding: 0.5rem 1rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
    }
}
</style>
</head>

<?php
// 요청 변수 초기화 (?? '' 형태)
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$recordDate = $_REQUEST["recordDate"] ?? date("Y-m-d");

// 체크 필터 변수 초기화
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';                              // 미출고 리스트
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';  // 출고예정
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';        // 출고완료

// 정렬 관련 변수 초기화
$cursort = $_REQUEST["cursort"] ?? '0';    // 현재 정렬모드
$sortof = $_REQUEST["sortof"] ?? '0';      // 클릭해서 넘겨준 값
$stable = $_REQUEST["stable"] ?? '0';      // 정렬모드 변경 여부
 
// 정렬 로직 처리
if ($sortof != '0') {
    
    if ($sortof == 1 && $stable == 0) {
        // 접수일 클릭되었을 때
        if ($cursort != 1) {
            $cursort = 1;
        } else {
            $cursort = 2;
        }
    }
    
    if ($sortof == 2 && $stable == 0) {
        // 납기일 클릭되었을 때
        if ($cursort != 3) {
            $cursort = 3;
        } else {
            $cursort = 4;
        }
    }
    
    if ($sortof == 3 && $stable == 0) {
        // 실측일 클릭되었을 때
        if ($cursort != 5) {
            $cursort = 5;
        } else {
            $cursort = 6;
        }
    }
    
    if ($sortof == 4 && $stable == 0) {
        // 도면작성일 클릭되었을 때
        if ($cursort != 7) {
            $cursort = 7;
        } else {
            $cursort = 8;
        }
    }
    
    if ($sortof == 5 && $stable == 0) {
        // 출고일 클릭되었을 때
        if ($cursort != 9) {
            $cursort = 9;
        } else {
            $cursort = 10;
        }
    }
    
    if ($sortof == 6 && $stable == 0) {
        // 청구 클릭되었을 때
        if ($cursort != 11) {
            $cursort = 11;
        } else {
            $cursort = 12;
        }
    }
} else {
    $sortof = '0';
    $cursort = '0';
}
  
  
// 배열 및 기타 변수 초기화
$sum = array();
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

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

// SQL 정렬 및 현재 날짜
$orderby = " ORDER BY deadline DESC ";
$now = date("Y-m-d");  // 현재 날짜와 크거나 같으면 출고예정으로 구분

// SQL 쿼리 생성
if ($mode == "search") {
    if ($search == "") {
        $sql = "SELECT * FROM {$DB}.outorder WHERE deadline BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;
    } elseif ($search != "") { 
        $sql = "SELECT * FROM {$DB}.outorder WHERE (";
        $fields = [
            'num', 'workday', 'checkstep', 'regist_day', 'workplacename', 'chargedman', 'address',
            'firstord', 'firstordman', 'firstordmantel', 'secondord', 'secondordman', 'secondordmantel',
            'worker', 'endworkday', 'memo', 'chargedmantel', 'orderday', 'measureday', 'drawday', 'deadline',
            'startday', 'testday', 'material1', 'material2', 'material3', 'material4', 'material5', 'material6',
            'widejamb', 'normaljamb', 'smalljamb', 'update_day', 'delivery', 'delicar', 'delicompany', 'delipay',
            'delimethod', 'demand', 'hpi', 'first_writer', 'update_log', 'filename1', 'filename2', 'su',
            'bon_su', 'lc_su', 'etc_su', 'air_su', 'order_com1', 'order_text1', 'order_com2', 'order_text2',
            'order_com3', 'order_text3', 'order_com4', 'order_text4', 'lc_draw', 'lclaser_com', 'lclaser_date',
            'lcbending_date', 'lcwelding_date', 'lcpainting_date', 'lcassembly_date', 'main_draw', 'eunsung_make_date',
            'eunsung_laser_date', 'mainbending_date', 'mainwelding_date', 'mainpainting_date', 'mainassembly_date',
            'memo2', 'order_date1', 'order_date2', 'order_date3', 'order_date4', 'order_input_date1', 'order_input_date2',
            'order_input_date3', 'order_input_date4', 'first_item1', 'first_item2', 'first_item3', 'first_item4',
            'second_item1', 'second_item2', 'second_item3', 'second_item4', 'third_item1', 'third_item2', 'third_item3',
            'third_item4', 'fourth_item1', 'fourth_item2', 'fourth_item3', 'fourth_item4', 'fifth_item1', 'fifth_item2',
            'fifth_item3', 'fifth_item4', 'sixth_item1', 'sixth_item2', 'sixth_item3', 'sixth_item4', 'seventh_item1',
            'seventh_item2', 'seventh_item3', 'seventh_item4', 'eighth_item1', 'eighth_item2', 'eighth_item3', 'eighth_item4',
            'ninth_item1', 'ninth_item2', 'ninth_item3', 'ninth_item4', 'tenth_item1', 'tenth_item2', 'tenth_item3',
            'tenth_item4', 'type1', 'type2', 'type3', 'type4', 'type5', 'type6', 'type7', 'type8', 'type9', 'type10',
            'inseung1', 'inseung2', 'inseung3', 'inseung4', 'inseung5', 'inseung6', 'inseung7', 'inseung8', 'inseung9', 'inseung10',
            'car_inside1', 'car_inside2', 'car_inside3', 'car_inside4', 'car_inside5', 'car_inside6', 'car_inside7', 'car_inside8', 'car_inside9', 'car_inside10',
            'comment1', 'comment2', 'comment3', 'comment4', 'comment5', 'comment6', 'comment7', 'comment8', 'comment9', 'comment10',
            'pdffile_name', 'copied_file', 'confirm', 'deliverynum', 'submemo'
        ];
        
        foreach ($fields as $index => $field) {
            $sql .= ($index > 0 ? " OR " : "") . "$field LIKE '%$search%'";
        }
        
        $sql .= ") AND (deadline BETWEEN date('$fromdate') AND date('$Transtodate'))" . $orderby;
    }
} else {
    $sql = "SELECT * FROM {$DB}.outorder WHERE deadline BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");

try {
    $pdo = db_connect();
} catch (Exception $ex) {
    error_log("DB 연결 실패: " . $ex->getMessage());
    die("데이터베이스 연결에 실패했습니다.");
}

// 배열 및 카운터 초기화
$counter = 0;
$num_arr = array();
$deadline_arr = array();
$workplacename_arr = array();
$address_arr = array();
$secondord_arr = array();
$sum_arr = array();
$delivery_arr = array();
$content_arr = array();
$demand_arr = array();
$workday_arr = array();
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$jamb_total = 0;
$dis_text = '';


// 데이터 조회 및 처리
try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // _row.php에서 변수 할당
        include '../outorder/_row.php';
        
        // 날짜 변환
        $demand = trans_date($demand);
        $workday = trans_date($workday);
        
        // 수량 합계 계산
        $sum[0] = ($sum[0] ?? 0) + (int)$su;
        $sum[1] = ($sum[1] ?? 0) + (int)$bon_su;
        $sum[2] = ($sum[2] ?? 0) + (int)$lc_su;
        $sum[3] = ($sum[3] ?? 0) + (int)$etc_su;
        $sum[4] = ($sum[4] ?? 0) + (int)$air_su;
        $sum[5] = ($sum[5] ?? 0) + (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
        
        $dis_text = " (종류별 합계)    결합단위 : " . ($sum[0] ?? 0) . " (SET),   L/C : " . ($sum[2] ?? 0) . "  (EA), 기타 : " . ($sum[3] ?? 0) . "  (EA)";
        
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
        
        // 배송 정보 문자열 생성
        $deli_text = "";
        if ($delivery != "" || $delipay != 0) {
            $deli_text = $delivery . " " . $delipay;
        }
        
        // 배열에 데이터 저장
        $num_arr[$counter] = $num;
        $deadline_arr[$counter] = $deadline;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $delivery_arr[$counter] = $deli_text;
        $secondord_arr[$counter] = $secondord;
        $demand_arr[$counter] = $demand;
        $workday_arr[$counter] = $workday;
        
        // 상세 내역 문자열 생성
        $content_arr[$counter] = $type1 . " " . $inseung1 . " " . $car_inside1 . " " . $first_item1 . " " . $first_item2 . " " . $first_item3 . " " . $first_item4 . " " . $comment1;
        $content_arr[$counter] .= $type2 . " " . $inseung2 . " " . $car_inside2 . " " . $second_item1 . " " . $second_item2 . " " . $second_item3 . " " . $second_item4 . " " . $comment2;
        $content_arr[$counter] .= $type3 . " " . $inseung3 . " " . $car_inside3 . " " . $third_item1 . " " . $third_item2 . " " . $third_item3 . " " . $third_item4 . " " . $comment3;
        
        $sum_arr[$counter] = $workitem;
        
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("데이터 조회 오류: " . $ex->getMessage());
    print "오류: " . htmlspecialchars($ex->getMessage());
}

$all_sum = $sum1 + $sum2 + $sum3;
?>

<body>
    <?php
    // 동적 URL 생성
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $formAction = htmlspecialchars("batchDB.php?mode=search&year={$year}&search={$search}&process={$process}&asprocess={$asprocess}&fromdate={$fromdate}&todate={$todate}&up_fromdate={$up_fromdate}&up_todate={$up_todate}&separate_date={$separate_date}&view_table={$view_table}", ENT_QUOTES, 'UTF-8');
    ?>
    
    <form name="board_form" id="board_form" method="post" action="<?= $formAction ?>">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex mb-1 mt-2 justify-content-between align-items-center">
                        <span class="badge bg-success fs-6">외주 청구일/출고일 일괄처리</span>
                        <button type="button" id="closeBtn" class="btn btn-secondary btn-sm">
                            <i class="bi bi-x-lg"></i> 닫기
                        </button>
                    </div>
                    
                    
                    <!-- 기간설정 칸 -->
                    <?php include getDocumentRoot() . '/setdate.php'; ?>
            
                    
                    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
                        납기일 기준 &nbsp;&nbsp;
                        <input type="date" id="recordDate" name="recordDate" class="form-control me-2" 
                               style="width:100px;" value="<?= htmlspecialchars($recordDate, ENT_QUOTES, 'UTF-8') ?>" placeholder=""> 선택체크
                        &nbsp;&nbsp;
                        <button type="button" id="saveBtn" class="btn btn-secondary btn-sm me-2">청구일 적용&저장</button>&nbsp;
                        <button type="button" id="clearBtn" class="btn btn-outline-danger btn-sm me-2">청구일 선택 Clear</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" id="OutputsaveBtn" class="btn btn-secondary btn-sm me-2">출고일 적용&저장</button>&nbsp;
                        <button type="button" id="OutputclearBtn" class="btn btn-outline-danger btn-sm me-2">출고일 선택 Clear</button>&nbsp;
                    </div>
                    
                    <div id="grid" style="width:1680px;"></div>
                    <!-- 모바일 카드 컨테이너 -->
                    <div id="mobile-grid-cards" style="display: none;"></div>
                </div>
            </div>
        </div>
    </form>
    
    <form id="Form1" name="Form1">
        <input type="hidden" id="num_arr" name="num_arr[]">
        <input type="hidden" id="recordDate_arr" name="recordDate_arr[]">
    </form>
</body>
</html>  

  
<script>
(function() {
    'use strict';
    
    $(document).ready(function() {
        
        // PHP 데이터를 JavaScript 변수로 전달
        var num = <?php echo json_encode($num_arr, JSON_UNESCAPED_UNICODE); ?>;
        var numcopy = [];
        
        var arr1 = <?php echo json_encode($deadline_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr2 = <?php echo json_encode($workplacename_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr3 = <?php echo json_encode($address_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr4 = <?php echo json_encode($sum_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr5 = <?php echo json_encode($delivery_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr6 = <?php echo json_encode($content_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr7 = <?php echo json_encode($secondord_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr8 = <?php echo json_encode($num_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr9 = <?php echo json_encode($demand_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr10 = <?php echo json_encode($workday_arr, JSON_UNESCAPED_UNICODE); ?>;
        var total_sum = 0;
        
        var rowNum = <?php echo json_encode($counter, JSON_UNESCAPED_UNICODE); ?>;
        var jamb_total = <?php echo json_encode($jamb_total, JSON_UNESCAPED_UNICODE); ?>;
        
        var data = [];
        var columns = [];
        var count = 0;  // 전체줄수 카운트 
 
        // 데이터 배열 생성
        for (var i = 0; i < rowNum; i++) {
            total_sum = total_sum + Number(uncomma(arr6[i]));
            var row = { name: i };
            row['col1'] = arr1[i];
            row['col2'] = arr2[i];
            row['col3'] = arr7[i];
            row['col4'] = arr3[i];
            row['col5'] = arr4[i];
            row['col6'] = arr5[i];
            row['col7'] = arr6[i];
            row['col8'] = arr8[i];
            row['col9'] = arr9[i];
            row['col10'] = arr10[i];
            data.push(row);
            numcopy[count] = num[i];
            count++;
        }
			

        // CustomTextEditor 클래스 (IE11 호환)
        function CustomTextEditor(props) {
            var el = document.createElement('input');
            var maxLength = props.columnInfo.editor.options.maxLength;
            
            el.type = 'text';
            el.maxLength = maxLength;
            el.value = String(props.value);
            
            this.el = el;
        }
        
        CustomTextEditor.prototype.getElement = function() {
            return this.el;
        };
        
        CustomTextEditor.prototype.getValue = function() {
            return this.el.value;
        };
        
        CustomTextEditor.prototype.mounted = function() {
            this.el.select();
        };
        
        // TUI Grid 초기화
        var grid = new tui.Grid({
            el: document.getElementById('grid'),
            data: data,
            bodyHeight: 700,
            columns: [
                {
                    header: '납기일',
                    name: 'col1',
                    sortingType: 'desc',
                    sortable: true,
                    width: 80,
                    align: 'center'
                },
                {
                    header: '청구일',
                    name: 'col9',
                    color: 'red',
                    sortingType: 'desc',
                    sortable: true,
                    width: 80,
                    align: 'center'
                },
                {
                    header: '출고일',
                    name: 'col10',
                    color: 'red',
                    sortingType: 'desc',
                    sortable: true,
                    width: 80,
                    align: 'center'
                },
                {
                    header: '현장명',
                    name: 'col2',
                    width: 280,
                    align: 'center'
                },
                {
                    header: '발주처',
                    name: 'col3',
                    width: 150,
                    align: 'center'
                },
                {
                    header: '현장주소',
                    name: 'col4',
                    width: 300,
                    align: 'center'
                },
                {
                    header: '수량',
                    name: 'col5',
                    width: 150,
                    align: 'center'
                },
                {
                    header: '운송비',
                    name: 'col6',
                    width: 120,
                    align: 'center'
                },
                {
                    header: '상세내역',
                    name: 'col7',
                    width: 250,
                    align: 'center'
                },
                {
                    header: 'rec No.',
                    name: 'col8',
                    width: 50,
                    align: 'center'
                }
            ],
            columnOptions: {
                resizable: true
            },
            rowHeaders: ['rowNum', 'checkbox'],
            pageOptions: {
                useClient: false,
                perPage: 20
            }
        });
        
        // TUI Grid 테마 적용
        var Grid = tui.Grid;
        Grid.applyTheme('default', {
            cell: {
                normal: {
                    background: '#fbfbfb',
                    border: '#e0e0e0',
                    showVerticalBorder: true
                },
                header: {
                    background: '#eee',
                    border: '#ccc',
                    showVerticalBorder: true
                },
                rowHeader: {
                    border: '#ccc',
                    showVerticalBorder: true
                },
                editable: {
                    background: '#fbfbfb'
                },
                selectedHeader: {
                    background: '#d8d8d8'
                },
                focused: {
                    border: '#418ed4'
                },
                disabled: {
                    text: '#b0b0b0'
                }
            }
        });
        
        // 모바일 카드 렌더링 함수
        function renderMobileCards() {
            var isMobile = window.innerWidth <= 768;
            var gridEl = document.getElementById('grid');
            var cardsContainer = document.getElementById('mobile-grid-cards');
            
            if (!cardsContainer) return;
            
            if (isMobile) {
                // 모바일: 그리드 숨기고 카드 표시
                if (gridEl) gridEl.style.display = 'none';
                cardsContainer.style.display = 'block';
                
                // 기존 카드 제거
                cardsContainer.innerHTML = '';
                
                // 컬럼 정보
                var columnConfig = {
                    'col1': { label: '납기일', width: 80 },
                    'col9': { label: '청구일', width: 80 },
                    'col10': { label: '출고일', width: 80 },
                    'col2': { label: '현장명', width: 280 },
                    'col3': { label: '발주처', width: 150 },
                    'col4': { label: '현장주소', width: 300 },
                    'col5': { label: '수량', width: 150 },
                    'col6': { label: '운송비', width: 120 },
                    'col7': { label: '상세내역', width: 250 },
                    'col8': { label: 'rec No.', width: 50 }
                };
                
                // 각 행을 카드로 변환
                data.forEach(function(row, index) {
                    var card = document.createElement('div');
                    card.className = 'mobile-grid-card';
                    card.setAttribute('data-row-index', index);
                    
                    var cardContent = '';
                    
                    // 체크박스 추가
                    cardContent += '<div class="mobile-grid-card-item">';
                    cardContent += '<input type="checkbox" class="mobile-checkbox" data-row-index="' + index + '" style="margin-right: 0.5rem;">';
                    cardContent += '<span class="mobile-grid-card-label">선택</span>';
                    cardContent += '</div>';
                    
                    // 각 컬럼을 카드 아이템으로 추가
                    Object.keys(columnConfig).forEach(function(colName) {
                        var config = columnConfig[colName];
                        var value = row[colName] || '';
                        
                        if (value !== '' && value !== null && value !== undefined) {
                            cardContent += '<div class="mobile-grid-card-item">';
                            cardContent += '<span class="mobile-grid-card-label">' + config.label + '</span>';
                            cardContent += '<span class="mobile-grid-card-value">' + (value || '') + '</span>';
                            cardContent += '</div>';
                        }
                    });
                    
                    card.innerHTML = cardContent;
                    cardsContainer.appendChild(card);
                });
                
                // 카드 클릭 이벤트 (더블클릭 대신)
                cardsContainer.addEventListener('click', function(e) {
                    var targetCard = e.target.closest('.mobile-grid-card');
                    if (targetCard && !e.target.closest('.mobile-checkbox')) {
                        var rowIndex = parseInt(targetCard.getAttribute('data-row-index'));
                        if (numcopy[rowIndex] > 0) {
                            <?php
                            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                            $host = $_SERVER['HTTP_HOST'];
                            ?>
                            var link = '<?= $protocol ?>://<?= $host ?>/outorder/view.php?num=' + numcopy[rowIndex];
                            window.open(link, "_blank");
                        }
                    }
                });
                
                // 체크박스 이벤트 처리
                cardsContainer.addEventListener('change', function(e) {
                    if (e.target.classList.contains('mobile-checkbox')) {
                        var rowIndex = parseInt(e.target.getAttribute('data-row-index'));
                        // 그리드의 체크박스와 동기화
                        if (grid) {
                            if (e.target.checked) {
                                grid.check(rowIndex);
                            } else {
                                grid.uncheck(rowIndex);
                            }
                        }
                    }
                });
            } else {
                // PC: 카드 숨기고 그리드 표시
                if (gridEl) gridEl.style.display = 'block';
                cardsContainer.style.display = 'none';
            }
        }
        
        // 초기 렌더링 및 리사이즈 이벤트
        renderMobileCards();
        $(window).on('resize', function() {
            setTimeout(renderMobileCards, 100);
        });
        
        // 그리드 데이터 변경 시 카드 업데이트
        grid.on('afterChange', function() {
            if (window.innerWidth <= 768) {
                renderMobileCards();
            }
        });	
	
	
        // 더블클릭 이벤트 (IE11 호환)
        grid.on('dblclick', function(e) {
            <?php
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            ?>
            var link = '<?= $protocol ?>://<?= $host ?>/outorder/view.php?num=' + numcopy[e.rowKey];
            
            if (numcopy[e.rowKey] > 0) {
                window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1800,height=900");
            }
            
            console.log(e.rowKey);
        });		
 		
		
	
        // 창닫기 버튼
        $("#closeBtn").click(function() {
            window.close(); // 현재 창 닫기
        });
        
        // 청구일 적용&저장 버튼
        $("#saveBtn").click(function() {
            var tmp = grid.getCheckedRowKeys();
            tmp.forEach(function(e) {
                grid.setValue(e, 'col9', $("#recordDate").val());
            });
            savegrid();
        });
        
        // 청구일 선택 Clear 버튼
        $("#clearBtn").click(function() {
            var tmp = grid.getCheckedRowKeys();
            tmp.forEach(function(e) {
                grid.setValue(e, 'col9', '');
            });
            savegrid();
        });
        
        // 출고일 적용&저장 버튼
        $("#OutputsaveBtn").click(function() {
            var tmp = grid.getCheckedRowKeys();
            tmp.forEach(function(e) {
                grid.setValue(e, 'col10', $("#recordDate").val());
            });
            Outputsavegrid();
        });
        
        // 출고일 선택 Clear 버튼
        $("#OutputclearBtn").click(function() {
            var tmp = grid.getCheckedRowKeys();
            tmp.forEach(function(e) {
                grid.setValue(e, 'col10', '');
            });
            Outputsavegrid();
        });		

        // grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
        function savegrid() {
            var num_arr = [];
            var recordDate_arr = [];
            
            var MAXcount = grid.getRowCount();
            var pushcount = 0;
            
            for (var i = 0; i < MAXcount; i++) {
                // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/추가된 것을 반영못함.
                num_arr.push(grid.getValue(i, 'col8'));
                recordDate_arr.push(grid.getValue(i, 'col9'));
            }
            
            console.log(num_arr);
            console.log(recordDate_arr);
            
            $('#num_arr').val(num_arr);
            $('#recordDate_arr').val(recordDate_arr);
            
            $.ajax({
                url: "saveDemand.php",
                type: "post",
                dataType: "json",
                data: $("#Form1").serialize(),
                success: function(data) {
                    console.log(data);
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        }
        
        // 출고일 저장
        function Outputsavegrid() {
            var num_arr = [];
            var recordDate_arr = [];
            
            var MAXcount = grid.getRowCount();
            var pushcount = 0;
            
            for (var i = 0; i < MAXcount; i++) {
                // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/추가된 것을 반영못함.
                num_arr.push(grid.getValue(i, 'col8'));
                recordDate_arr.push(grid.getValue(i, 'col10'));  // 출고일 관련 컬럼번호 10번
            }
            
            console.log(num_arr);
            console.log(recordDate_arr);
            
            $('#num_arr').val(num_arr);
            $('#recordDate_arr').val(recordDate_arr);
            
            $.ajax({
                url: "SaveOutput.php",
                type: "post",
                dataType: "json",
                data: $("#Form1").serialize(),
                success: function(data) {
                    console.log(data);
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        }
        
        dis_text();
    });  // end document.ready

    // 유틸리티 함수들
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }


    // 전전월 조회 (전역 함수로 노출)
    window.prepre_month = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; // January is 0!
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        mm = mm - 2;  // 전전월
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
    };
    
    // 전월 조회 (전역 함수로 노출)
    window.pre_month = function() {
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
    };
    
    // 당해월 조회 (전역 함수로 노출)
    window.this_month = function() {
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
    };
    
    // 당해년도 조회 (전역 함수로 노출)
    window.this_year = function() {
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
        document.getElementById('board_form').submit();
    };
    
    // 합계 텍스트 표시
    function dis_text() {
        var dis_text = <?php echo json_encode($dis_text, JSON_UNESCAPED_UNICODE); ?>;
        if ($("#dis_text").length > 0) {
            $("#dis_text").val(dis_text);
        }
    }
    
})();  // end IIFE
</script>