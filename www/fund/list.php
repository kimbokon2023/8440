<?php
/**
 * Fund 공동자금 목록 페이지
 * 수입/지출 내역을 표시하고 검색 기능을 제공합니다.
 */

// 로컬과 서버 호환성을 위한 설정
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '1';
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$cursort = $_REQUEST["cursort"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$year = $_REQUEST["year"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

// 변수 초기화
$scale = 50; // 한 페이지에 보여질 게시글 수
$page_scale = 15; // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;
$SettingDate = "proDate";
$input_sum = 0;
$output_sum = 0;
$remain_sum = 0;
$resultText = '';
$total_row = 0;
$total_page = 0;
$current_page = 0;
$start_num = 0;
$nowday = date("Y-m-d");
$regist_state = null;

// 날짜 설정
if (empty($fromdate)) {
    $fromdate = "2021-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
}

// Transtodate는 항상 todate + 1일로 설정
$Transtodate = date("Y-m-d", strtotime($todate . '+1 days'));

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수입/지출 합계 계산
try {
    $sql_sum = "SELECT * FROM {$DB}.fund WHERE proDate BETWEEN DATE('2010-01-01') AND DATE(?) ORDER BY proDate desc, num desc";
    $stmh_sum = $pdo->prepare($sql_sum);
    $stmh_sum->bindValue(1, $todate, PDO::PARAM_STR);
    $stmh_sum->execute();
    $sum_data = $stmh_sum->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($sum_data as $row) {
        $amount = $row["amount"];
        $which = $row["which"];
        
        if ($which == '1') {
            $input_sum += (int)conv_num($amount);
        } else {
            $output_sum += (int)conv_num($amount);
        }
    }
} catch (PDOException $ex) {
    error_log("Sum calculation error: " . $ex->getMessage());
}

$remain_sum = $input_sum - $output_sum;
$resultText = "총수입(" . number_format($input_sum) . "원) - 총지출(" . number_format($output_sum) . "원) = 보유 잔액(" . number_format($remain_sum) . "원)";

// SQL 쿼리 생성
if ($mode == "search" && !empty($search)) {
    $sql = "SELECT * FROM {$DB}.fund WHERE ({$SettingDate} BETWEEN ? AND ?) AND (memo LIKE ? OR writer LIKE ?) ORDER BY {$SettingDate} desc, num desc LIMIT ?, ?";
} else {
    $sql = "SELECT * FROM {$DB}.fund WHERE {$SettingDate} BETWEEN '{$fromdate}' AND '{$Transtodate}' ORDER BY {$SettingDate} desc, num desc LIMIT {$first_num}, {$scale}";
}

// 전체 레코드 수 조회
try {
    $count_sql = "SELECT COUNT(*) as cnt FROM {$DB}.fund WHERE proDate BETWEEN '{$fromdate}' AND '{$Transtodate}' ORDER BY proDate desc, num desc";
    $count_result = $pdo->query($count_sql);
    if ($count_result) {
        $count_row = $count_result->fetch(PDO::FETCH_ASSOC);
        $total_row = (int)($count_row['cnt'] ?? 0);
    }
} catch (Exception $e) {
    error_log("Count error: " . $e->getMessage());
}

$total_page = ceil($total_row / $scale);
$current_page = ceil($page / $page_scale);

// 페이지별 데이터 조회
try {
    if ($mode == "search" && !empty($search)) {
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
        $stmh->bindValue(3, '%' . $search . '%', PDO::PARAM_STR);
        $stmh->bindValue(4, '%' . $search . '%', PDO::PARAM_STR);
        $stmh->bindValue(5, $first_num, PDO::PARAM_INT);
        $stmh->bindValue(6, $scale, PDO::PARAM_INT);
        $stmh->execute();
    } else {
        $stmh = $pdo->query($sql);
    }
} catch (PDOException $ex) {
    error_log("List query error: " . $ex->getMessage());
}

include getDocumentRoot() . '/load_header.php';
?>

<title>공동자금</title>

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body,
        .card-header {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .d-flex.mb-4.mt-4.fs-6.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-4.mt-4.fs-6.justify-content-center.align-items-center {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        .d-flex.mb-4.mt-4.fs-6.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 검색/버튼 영역 최적화 */
        .d-flex.mb-1.mt-1.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-1.mt-1.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* DataTables 컨트롤 숨기기 */
        #myTable_wrapper .dataTables_length {
            display: none !important;
        }
        
        #myTable_wrapper .dataTables_filter {
            display: none !important;
        }
        
        /* 테이블 숨기기 (데이터는 읽을 수 있도록) */
        #myTable {
            visibility: hidden !important;
            position: absolute !important;
            left: -9999px !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-card-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            cursor: pointer;
            transition: box-shadow 0.15s ease-in-out;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .mobile-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .mobile-card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.75rem;
            color: #212529;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-item:last-child {
            border-bottom: none;
        }
        
        .mobile-card-label {
            font-weight: 600;
            color: #6c757d;
            display: inline-block;
            min-width: 80px;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-value {
            color: #212529;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 페이징 최적화 */
        .row.row-cols-auto.mt-3.mb-5.justify-content-center.align-items-center {
            flex-wrap: wrap !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .row.row-cols-auto.mt-3.mb-5.justify-content-center.align-items-center button,
        .row.row-cols-auto.mt-3.mb-5.justify-content-center.align-items-center span {
            margin: 0.25rem !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
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
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
    
    /* PC 환경에서 모바일 카드 컨테이너 숨기기 */
    @media (min-width: 769px) {
        #mobile-card-container {
            display: none !important;
        }
    }
</style>

</head>
<body>

<?php require_once(includePath('myheader.php')); ?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search">
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="find" value="<?php echo htmlspecialchars($find, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="year" value="<?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="process" value="<?php echo htmlspecialchars($process, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="asprocess" value="<?php echo htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="fromdate" value="<?php echo htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="todate" value="<?php echo htmlspecialchars($todate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="up_fromdate" value="<?php echo htmlspecialchars($up_fromdate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="up_todate" value="<?php echo htmlspecialchars($up_todate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="separate_date" value="<?php echo htmlspecialchars($separate_date, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="view_table" value="<?php echo htmlspecialchars($view_table, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="page" name="page" value="<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>">

    <div class="container">
        <div class="card mt-2 mb-1">
            <div class="card-header">
                <div class="d-flex mb-4 mt-4 fs-6 justify-content-center align-items-center">
                    <?php echo htmlspecialchars($resultText, ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn btn-dark btn-sm mx-3" onclick='location.reload();' title="새로고침">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                
                <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
                    ▷ 총 <?php echo htmlspecialchars(count($sum_data), ENT_QUOTES, 'UTF-8'); ?>건 &nbsp;&nbsp;
                    
                    <!-- 기간설정 -->
                    <?php include getDocumentRoot() . '/setdate.php'; ?>
                    
                    <?php if (isset($_SESSION["userid"]) && in_array($user_name, array('조경임', '김보곤', '소민지'))) { ?>
                    &nbsp;&nbsp;
                    <button type="button" id="writeBtn" class="btn btn-dark btn-sm">
                        <i class="bi bi-pencil"></i> 신규
                    </button>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body justify-content-center align-items-center">
                <div class="row">
                    <div class="col-sm-1 mb-1 mt-1"></div>
                    <div class="col-sm-10 mb-1 mt-1">
                        <!-- 모바일 카드 컨테이너 -->
                        <div id="mobile-card-container" style="display: none;"></div>
                        
                        <table class="table table-hover" id="myTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">번호</th>
                                    <th class="text-center">작성일</th>
                                    <th class="text-center">수입</th>
                                    <th class="text-center">지출</th>
                                    <th class="text-center">금액</th>
                                    <th class="text-center">내역</th>
                                    <th class="text-center">작성자</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                                
                                $start_num = 1;
                                
                                try {
                                    if (!isset($sum_data)) {
                                        echo '<tr><td colspan="7" class="text-center text-danger">데이터베이스 쿼리 오류가 발생했습니다.</td></tr>';
                                    } else {
                                        $row_count = 0;
                                        foreach ($sum_data as $row) {
                                            $row_count++;
                                            $num = $row["num"] ?? '';
                                            $proDate = $row["proDate"] ?? '';
                                            $writer = $row["writer"] ?? '';
                                            $amount = $row["amount"] ?? '';
                                            $memo = $row["memo"] ?? '';
                                            $which = $row["which"] ?? '';
                                        
                                        // 요일 추가
                                        if (!empty($proDate)) {
                                            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                                            $proDate = $proDate . $week[date('w', strtotime($proDate))];
                                        }
                                        
                                        // 수입/지출 구분
                                        if ($which == '1') {
                                            $tmp_word = "수입";
                                        } else {
                                            $tmp_word = "지출";
                                        }
                                ?>
                                <tr onclick="redirectToView('<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>')" style="cursor: pointer;">
                                    <td class="text-center"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($proDate, ENT_QUOTES, 'UTF-8'); ?></td>
                                    
                                    <?php if ($tmp_word == '수입') { ?>
                                        <td class="text-primary text-center"><?php echo htmlspecialchars($tmp_word, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <?php } else { ?>
                                        <td class="text-danger text-center"></td>
                                    <?php } ?>
                                    
                                    <?php if ($tmp_word !== '수입') { ?>
                                        <td class="text-danger text-center"><?php echo htmlspecialchars($tmp_word, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <?php } else { ?>
                                        <td class="text-primary text-center"></td>
                                    <?php } ?>
                                    
                                    <td class="text-end"><?php echo htmlspecialchars($amount, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-start"><?php echo htmlspecialchars($memo, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($writer, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <?php
                                        $start_num++;
                                    }

                                    if ($row_count == 0) {
                                        echo '<tr><td colspan="7" class="text-center">조회된 데이터가 없습니다.</td></tr>';
                                    }
                                }
                                } catch (PDOException $ex) {
                                    error_log("List fetch error: " . $ex->getMessage());
                                    echo '<tr><td colspan="7" class="text-center text-danger">데이터 조회 중 오류가 발생했습니다.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-1 mb-1 mt-1"></div>
                </div>
                
                <!-- 페이징 -->
                <div class="row row-cols-auto mt-3 mb-5 justify-content-center align-items-center">
                    <?php
                    $start_page = ($current_page - 1) * $page_scale + 1;
                    $end_page = $start_page + $page_scale - 1;
                    
                    if ($page != 1 && $page > $page_scale) {
                        $prev_page = $page - $page_scale;
                        if ($prev_page <= 0) {
                            $prev_page = 1;
                        }
                        echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;';
                    }
                    
                    for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                        if ($page == $i) {
                            echo '<span class="text-secondary"> ' . $i . ' </span>';
                        } else {
                            echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="movetoPage(' . $i . ')"> ' . $i . '</button> &nbsp;';
                        }
                    }
                    
                    if ($page < $total_page) {
                        $next_page = $page + $page_scale;
                        if ($next_page > $total_page) {
                            $next_page = $total_page;
                        }
                        echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="container-fluid">
    <?php include '../footer_sub.php'; ?>
</div>

<script>
var dataTable;
var fundpageNumber;

$(document).ready(function() {
    'use strict';
    
    var hasData = $('#myTable tbody tr').length > 0 && !$('#myTable tbody tr td[colspan]').length;
    
    if (hasData) {
        dataTable = $('#myTable').DataTable({
            "paging": true,
            "ordering": true,
            "searching": false, // 모바일에서 수동 검색 사용
            "pageLength": 50,
            "lengthMenu": [25, 50, 100, 200, 500, 1000],
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "search": "Live Search:"
            },
            "initComplete": function() {
                // 모바일에서 카드 렌더링
                if (window.innerWidth <= 768) {
                    setTimeout(function() {
                        renderMobileCards();
                    }, 500);
                }
            },
            "drawCallback": function() {
                // 모바일에서 카드 재렌더링
                if (window.innerWidth <= 768) {
                    setTimeout(function() {
                        renderMobileCards();
                    }, 300);
                }
            }
        });
        
        var savedPageNumber = getCookie('fundpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
        
        dataTable.on('page.dt', function() {
            fundpageNumber = dataTable.page.info().page + 1;
            setCookie('fundpageNumber', fundpageNumber, 10);
        });
        
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw();
            
            savedPageNumber = getCookie('fundpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }
        });
    }
    
    // 모바일 카드 클릭 이벤트
    $(document).on('click', '.mobile-card', function() {
        var num = $(this).data('num');
        redirectToView(num);
    });
    
    // 창 크기 변경 시 카드/테이블 전환
    $(window).on('resize', function() {
        if (window.innerWidth <= 768) {
            renderMobileCards();
        }
    });
    
    $("#writeBtn").click(function() {
        var url = "write_form.php";
        customPopup(url, '공동자금', 800, 500);
    });
    
    saveLogData('공동자금 조회');
});

function restorePageNumber() {
    if (dataTable) {
        var savedPageNumber = getCookie('fundpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
        }
    }
}

function redirectToView(num) {
    var url = "view.php?num=" + num;
    customPopup(url, '공동자금', 800, 500);
}

function movetoPage(page) {
    $("#page").val(page);
    $("#board_form").submit();
}

/**
 * 모바일에서 테이블을 카드 형식으로 렌더링
 */
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-card-container').hide();
        return;
    }
    
    $('#mobile-card-container').show();
    $('#mobile-card-container').empty();
    
    // 원본 테이블에서 데이터 읽기
    var rows = $('#myTable tbody tr');
    
    if (rows.length === 0) {
        $('#mobile-card-container').html('<div class="text-center text-muted p-3">데이터가 없습니다.</div>');
        return;
    }
    
    rows.each(function() {
        var $row = $(this);
        var num = $row.attr('onclick');
        if (!num) return;
        
        // onclick에서 num 추출
        var numMatch = num.match(/redirectToView\('(\d+)'\)/);
        if (!numMatch) return;
        var numValue = numMatch[1];
        
        var tds = $row.find('td');
        if (tds.length < 7) return;
        
        var 번호 = escapeHtml($(tds[0]).text().trim());
        var 작성일 = escapeHtml($(tds[1]).text().trim());
        var 수입 = escapeHtml($(tds[2]).text().trim());
        var 지출 = escapeHtml($(tds[3]).text().trim());
        var 금액 = escapeHtml($(tds[4]).text().trim());
        var 내역 = escapeHtml($(tds[5]).text().trim());
        var 작성자 = escapeHtml($(tds[6]).text().trim());
        
        // 유효성 검사
        if (!번호 || 번호 === '' || 번호 === 'No data available in table') {
            return;
        }
        
        // 수입/지출 색상 구분
        var 수입Class = 수입 ? 'text-primary' : '';
        var 지출Class = 지출 ? 'text-danger' : '';
        
        var cardHtml = '<div class="mobile-card" data-num="' + escapeHtml(numValue) + '">' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + 번호 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작성일:</span>' +
                '<span class="mobile-card-value">' + 작성일 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">수입:</span>' +
                '<span class="mobile-card-value ' + 수입Class + '">' + (수입 || '-') + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">지출:</span>' +
                '<span class="mobile-card-value ' + 지출Class + '">' + (지출 || '-') + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">금액:</span>' +
                '<span class="mobile-card-value">' + 금액 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">내역:</span>' +
                '<span class="mobile-card-value">' + 내역 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작성자:</span>' +
                '<span class="mobile-card-value">' + 작성자 + '</span>' +
            '</div>' +
        '</div>';
        
        $('#mobile-card-container').append(cardHtml);
    });
}

/**
 * HTML 이스케이프 함수
 */
function escapeHtml(text) {
    if (!text) return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>
</body>
</html>
