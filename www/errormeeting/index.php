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
                                        <td class="text-center"><?= $start_num ?></td>
                                        <td class="text-center"><?= htmlspecialchars($occur) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($approve) ?></td>
                                        <td class="text-start"><?= htmlspecialchars($place) ?></td>
                                        <td class="text-start"><?= htmlspecialchars($content) ?></td>
                                        <td class="text-start"><?= htmlspecialchars($method) ?></td>
                                        <td class="text-start"><?= htmlspecialchars($emember) ?></td>
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