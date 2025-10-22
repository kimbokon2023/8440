<?php
/**
 * 공지사항 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $website = $_SESSION["WebSite"] ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    header("Location: {$website}login/login_form.php");
    exit;
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 기본 변수 초기화
$title_message = '공지사항';
$tablename = "notice";

// 요청 변수 초기화
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

include includePath('load_header.php');
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>
<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
    }
</style>
</head>
<body>

<?php require_once(includePath('myheader.php')); ?>

<?php
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 생성 (Prepared Statement 사용)
if ($mode == "search" && !empty($search)) {
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE name LIKE ? OR subject LIKE ? OR nick LIKE ? OR regist_day LIKE ? OR searchtext LIKE ? ORDER BY num DESC";
    $searchTerm = "%{$search}%";
} else {
    $sql = "SELECT * FROM {$DB}.{$tablename} ORDER BY num DESC";
}

// 전체 레코드 수 조회
$total_row = 0;
try {
    $stmh = $pdo->prepare($sql);
    
    if ($mode == "search" && !empty($search)) {
        $stmh->bindValue(1, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(2, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(3, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(4, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchTerm, PDO::PARAM_STR);
    }
    
    $stmh->execute();
    $total_row = $stmh->rowCount();
    
} catch (PDOException $ex) {
    error_log("공지사항 목록 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}

// 데이터 조회
try {
    $stmh = $pdo->prepare($sql);
    
    if ($mode == "search" && !empty($search)) {
        $stmh->bindValue(1, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(2, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(3, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(4, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchTerm, PDO::PARAM_STR);
    }
    
    $stmh->execute();
?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
    <div class="container justify-content-center">
        <div class="card mt-2 mb-4">
            <div class="card-body">
                <div class="d-flex mt-3 mb-2 justify-content-center">
                    <h5><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></h5>
                    <button type="button" class="btn btn-dark btn-sm mx-3" onclick="location.reload();" title="새로고침">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                
                <div class="d-flex mt-3 mb-1 justify-content-center align-items-center">
                    ▷ <?= htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8') ?> &nbsp;
                    <div class="inputWrap">
                        <input type="text" id="search" class="form-control mx-1" style="width:150px;" name="search" autocomplete="off" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="검색어" onkeydown="JavaScript:SearchEnter();">
                        <button class="btnClear"></button>
                    </div>
                    <button id="searchBtn" type="button" class="btn btn-dark btn-sm mx-1">
                        <i class="bi bi-search"></i> 검색
                    </button>
                    <button type="button" class="btn btn-dark btn-sm mx-1" id="writeBtn">
                        <i class="bi bi-pencil"></i> 신규
                    </button>
                </div>
                
                <div class="row d-flex">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">번호</th>
                                <th class="text-center">글제목</th>
                                <th class="text-center">작성자</th>
                                <th class="text-center">등록일자</th>
                                <th class="text-center">조회수</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $start_num = $total_row;
                            
                            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                $item_num = $row["num"];
                                $item_id = $row["id"];
                                $item_name = $row["name"];
                                $item_nick = $row["nick"];
                                $item_hit = $row["hit"];
                                $item_date = $row["regist_day"];
                                $item_date = substr($item_date, 0, 10);
                                $item_subject = $row["subject"];
                                
                                // 댓글 수 조회 (Prepared Statement 사용)
                                $sql_ripple = "SELECT COUNT(*) as count FROM {$DB}.notice_ripple WHERE parent = ?";
                                $stmh1 = $pdo->prepare($sql_ripple);
                                $stmh1->bindValue(1, $item_num, PDO::PARAM_INT);
                                $stmh1->execute();
                                $ripple_row = $stmh1->fetch(PDO::FETCH_ASSOC);
                                $num_ripple = $ripple_row['count'];
                            ?>
                            
                            <tr onclick="redirectToView('<?= htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>')">
                                <td class="text-center"><?= htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?= htmlspecialchars($item_subject, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
                                    <?php if ($num_ripple > 0) { ?>
                                        <span class="badge bg-primary"><?= htmlspecialchars($num_ripple, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php } ?>
                                </td>
                                <td class="text-center"><?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center"><?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center"><?= htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                            
                            <?php
                                $start_num--;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
} catch (PDOException $ex) {
    error_log("공지사항 데이터 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}
?>

<script type="text/javascript">
(function() {
    'use strict';
    
    var dataTable;
    var noticepageNumber;
    var baseUrl = <?php echo json_encode($base_url, JSON_UNESCAPED_UNICODE); ?>;
    var tablename = <?php echo json_encode($tablename, JSON_UNESCAPED_UNICODE); ?>;
    
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
        
        // 페이지 번호 복원 (초기 로드 시)
        var savedPageNumber = getCookie('noticepageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
        
        // 페이지 변경 이벤트 리스너
        dataTable.on('page.dt', function() {
            var noticepageNumber = dataTable.page.info().page + 1;
            setCookie('noticepageNumber', noticepageNumber, 10);
        });
        
        // 페이지 길이 셀렉트 박스 변경 이벤트 처리
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw();
            
            savedPageNumber = getCookie('noticepageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }
        });
        
        // 신규 작성 버튼
        $("#writeBtn").click(function() {
            var page = noticepageNumber;
            var url = "write_form.php?tablename=" + encodeURIComponent(tablename);
            if (typeof customPopup === 'function') {
                customPopup(url, '공지사항', 1300, 850);
            } else {
                window.open(url, '공지사항', 'width=1300,height=850');
            }
        });
        
        // 서버에 작업 기록
        if (typeof saveLogData === 'function') {
            saveLogData('공지사항');
        }
    });
    
    window.restorePageNumber = function() {
        var savedPageNumber = getCookie('noticepageNumber');
        if (savedPageNumber && dataTable) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
        }
    };
    
    window.redirectToView = function(num, tablename) {
        var page = noticepageNumber;
        var url = "view.php?num=" + encodeURIComponent(num) + "&tablename=" + encodeURIComponent(tablename);
        
        if (typeof customPopup === 'function') {
            customPopup(url, '공지사항', 1200, 900);
        } else {
            window.open(url, '공지사항', 'width=1200,height=900');
        }
    };
    
})();
</script>

</body>
</html>
