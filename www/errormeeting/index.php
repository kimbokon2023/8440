<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 파라미터 초기화
$menu = $_REQUEST["menu"] ?? '';
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';
$page = $_REQUEST["page"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"])) {
    $_SESSION["url"] = $WebSite . 'errormeeting/index.php?user_name=' . $user_name;
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 관리자 권한 설정
$admin = 0;
$admin_names = array('소현철', '김보곤', '최장중', '이경묵');
if (in_array($user_name, $admin_names)) {
    $admin = 1;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 접속 IP 기록
$ip_address = $_SERVER["REMOTE_ADDR"] ?? '';
$ip_address = 'ip_(품질분임조) : ' . $ip_address;

$data = date("Y-m-d H:i:s") . " - " . $user_id . " - " . $user_name . '  ' . $ip_address;

try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO {$DB}.logdata (data) VALUES (?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $data, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("접속 로그 기록 오류: " . $ex->getMessage());
}

$tablename = 'emeeting';

// rowDB.php에서 사용되는 변수 초기화
$num = '';
$occur = '';
$approve = '';
$place = '';
$content = '';
$method = '';
$emember = '';

// 결재권자 결재정보 보기
$approvalwait = 0;

if ($admin == 1) {
    $sql = "SELECT * FROM {$DB}.emeeting WHERE approve <> '처리완료'";
    
    try {
        $stmh = $pdo->query($sql);
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $approvalwait += 1;
        }
    } catch (PDOException $ex) {
        error_log("결재 대기 건 조회 오류: " . $ex->getMessage());
    }
}

// 서버의 정보를 읽어와 메인화면 꾸미기 (초기 변수 설정용)
// (실제 데이터는 아래 검색 로직에서 처리됨)

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title>품질분임조</title>

<style>
    .modal .modal-full {
        max-width: 94%;
    }
    
    .modal .white {
        color: #fff;
    }
    
    .modal .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal .modal-header .modal-title {
        font-size: 1.1rem;
    }
    
    .modal .modal-header .close {
        padding: 7px 10px;
        border-radius: 50%;
        background: none;
        border: none;
    }
    
    .modal .modal-header .close:hover {
        background: #dee2e6;
    }
    
    .modal .modal-header i,
    .modal .modal-header svg {
        font-size: 12px;
        height: 12px;
        width: 12px;
    }
    
    .modal .modal-footer {
        padding: 1rem;
    }
    
    .modal.modal-borderless .modal-header {
        border-bottom: 0;
    }
    
    .modal.modal-borderless .modal-footer {
        border-top: 0;
    }
    
    th {
        white-space: nowrap;
    }
    
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
        
        /* 제목 이미지 최적화 */
        .card-body img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            flex-wrap: wrap !important;
        }
        
        .d-flex.justify-content-center .form-control,
        .d-flex.justify-content-center .btn {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            box-sizing: border-box !important;
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
            cursor: pointer !important;
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
            min-width: 100px !important;
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
        
        /* 버튼 최적화 */
        .btn {
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            box-sizing: border-box !important;
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
        
        .modal-body img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
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

<?php require_once(includePath('common/modal.php')); ?>
<?php if ($menu !== 'no') require_once(includePath('myheader.php')); ?>

<form name="board_form" id="board_form" method="post" action="./index.php">
    <input id="view_table" name="view_table" type="hidden" value="<?= htmlspecialchars($view_table) ?>">
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert) ?>">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert) ?>">
    <input type="hidden" id="order_alert" name="order_alert" value="<?= htmlspecialchars($order_alert) ?>">
    <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page) ?>">
    
    <div class="container mb-5">
        <!-- 알림 모달 -->
        <div class="modal fade" id="notice_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-header text-center">
                            <h2>알림</h2>
                        </div>
                    </div>
                    <div class="modal-body">
                        <img src="../img/norice_errorreport1.png" alt="알림 이미지">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">&times; 닫기</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-2 mb-2">
            <div class="card-body">
                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <img class="form-control" src="./title.jpg" alt="품질분임조">
                </div>
                
                <?php
                // 기간 설정
                if ($fromdate == "") {
                    $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
                }
                
                if ($todate == "") {
                    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
                    $Transtodate = strtotime($todate . '+1 days');
                    $Transtodate = date("Y-m-d", $Transtodate);
                } else {
                    $Transtodate = strtotime($todate);
                    $Transtodate = date("Y-m-d", $Transtodate);
                }
                
                // SQL 쿼리 생성 (기본 쿼리 초기화)
                $sql = "SELECT * FROM {$DB}.emeeting ORDER BY num DESC";
                
                if ($mode == "search" && $search != "") {
                    if ($search == "결재상신 1차결재") {
                        $sql = "SELECT * FROM {$DB}.emeeting WHERE approve = '결재상신' OR approve = '1차결재' ORDER BY num DESC";
                        $search = null;
                    } else {
                        // 기본 SQL Injection 방어
                        $search_safe = str_replace("'", "''", $search);
                        $sql = "SELECT * FROM {$DB}.emeeting WHERE " .
                               "(emember LIKE '%{$search_safe}%') OR " .
                               "(place LIKE '%{$search_safe}%') OR " .
                               "(content LIKE '%{$search_safe}%') OR " .
                               "(method LIKE '%{$search_safe}%') OR " .
                               "(approve LIKE '%{$search_safe}%') " .
                               "ORDER BY occur DESC";
                    }
                }
                
                // 레코드 조회 및 배열에 저장
                $rows = array();
                $total_row = 0;
                $error_message = '';
                
                try {
                    $stmh = $pdo->query($sql);
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $rows[] = $row;
                    }
                    
                    $total_row = count($rows);
                } catch (PDOException $ex) {
                    $error_message = "품질분임조 검색 오류: " . $ex->getMessage();
                    error_log($error_message);
                }
                
                // 디버깅: 데이터 확인
                if ($total_row == 0 && $error_message == '') {
                    error_log("품질분임조: 조회된 데이터가 없습니다. SQL: " . $sql);
                }
                ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-danger mx-3" role="alert">
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                <?php endif; ?>
                
                <!-- 임시 디버깅 정보 (문제 해결 후 삭제) -->
                <?php if (isset($_GET['debug'])): ?>
                    <div class="alert alert-info mx-3" role="alert">
                        <strong>디버깅 정보:</strong><br>
                        - DB: <?= htmlspecialchars($DB) ?><br>
                        - SQL: <?= htmlspecialchars($sql) ?><br>
                        - 조회 건수: <?= $total_row ?><br>
                        - Mode: <?= htmlspecialchars($mode) ?><br>
                        - Search: <?= htmlspecialchars($search) ?><br>
                        - Rows count: <?= count($rows) ?>
                    </div>
                <?php endif; ?>
                
                <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                    ▷ <?= $total_row ?> 건 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" class="form-control me-2" style="width:150px;height:32px;" 
                           name="search" id="search" value="<?= htmlspecialchars($search) ?>" 
                           onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2">
                        <i class="bi bi-search"></i> 검색
                    </button>
                    <button type="button" class="btn btn-dark btn-sm" id="writeBtn">
                        <i class="bi bi-pencil"></i> 신규
                    </button>
                    &nbsp;&nbsp;&nbsp;
                </div>
                
                <div class="row d-flex">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">번호</th>
                                <th class="text-center">회의일시</th>
                                <th class="text-center">승인상태</th>
                                <th class="text-center">현장명(품명)</th>
                                <th class="text-center">부적합 현상 및 불량내용</th>
                                <th class="text-center">개선대책</th>
                                <th class="text-center">참석자</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($rows) > 0) {
                                $start_num = $total_row;
                                
                                foreach ($rows as $row) {
                                    // rowDB.php 내용을 직접 여기에 포함
                                    $num = $row["num"] ?? '';
                                    $place = $row["place"] ?? '';
                                    $occur = $row["occur"] ?? '';
                                    $errortype = $row["errortype"] ?? '';
                                    $emember = $row["emember"] ?? '';
                                    $content = $row["content"] ?? '';
                                    $method = $row["method"] ?? '';
                                    $filename = $row["filename"] ?? '';
                                    $serverfilename = $row["serverfilename"] ?? '';
                                    $approve = $row["approve"] ?? '';
                            ?>
                                    <tr onclick="redirectToView('<?= $num ?>', '<?= $tablename ?>')">
                                        <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                        <td class="text-center" data-label="회의일시"><?= htmlspecialchars($occur) ?></td>
                                        <td class="text-center" data-label="승인상태"><?= htmlspecialchars($approve) ?></td>
                                        <td class="text-start" data-label="현장명(품명)"><?= htmlspecialchars($place) ?></td>
                                        <td class="text-start" data-label="부적합 현상 및 불량내용"><?= htmlspecialchars($content) ?></td>
                                        <td class="text-start" data-label="개선대책"><?= htmlspecialchars($method) ?></td>
                                        <td class="text-start" data-label="참석자"><?= htmlspecialchars($emember) ?></td>
                                    </tr>
                            <?php
                                    $start_num--;
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-3 text-muted">조회된 데이터가 없습니다.</p>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include "footer.php"; ?>
</form>

<script src="js/scripts.js"></script>

<script>
// ES5 호환 JavaScript

var dataTable;
var emeetingpageNumber;

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
    
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
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
    
    // 페이지 번호 복원
    var savedPageNumber = getCookie('emeetingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }
    
    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        emeetingpageNumber = dataTable.page.info().page + 1;
        setCookie('emeetingpageNumber', emeetingpageNumber, 10);
    });
    
    // 페이지 길이 변경 이벤트
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw();
        
        savedPageNumber = getCookie('emeetingpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('emeetingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = emeetingpageNumber;
    var url = "write_form.php?num=" + num + "&tablename=" + tablename;
    customPopup(url, '품질분임조', 1100, 900);
}

// 엔터 키로 검색
function SearchEnter() {
    if (event.keyCode == 13) {
        event.preventDefault();
        document.getElementById('board_form').action = './index.php?mode=search';
        document.getElementById('board_form').submit();
    }
}

$(document).ready(function() {
    // 검색 버튼 클릭
    $("#searchBtn").click(function() {
        document.getElementById('board_form').action = './index.php?mode=search';
        document.getElementById('board_form').submit();
    });
    
    $("#writeBtn").click(function() {
        var page = emeetingpageNumber;
        var tablename = '<?php echo htmlspecialchars($tablename); ?>';
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '품질분임조', 1100, 850);
    });
    
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
    
    $("#adminprocess").click(function() {
        $('#search').val('결재상신 1차결재');
        document.getElementById('board_form').action = './index.php?mode=search';
        document.getElementById('board_form').submit();
    });
    
    $("#searchNoinputBtn").click(function() {
        $('#search').val('');
        document.getElementById('board_form').action = './index.php';
        document.getElementById('board_form').submit();
    });
    
    // 서버에 작업 기록
    saveLogData('부적합개선 품질분임조');
});
</script>

</body>
</html>