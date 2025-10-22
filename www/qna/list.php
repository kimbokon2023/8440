<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * QNA 자료실 목록
 * 
 * 자료실 게시글 목록을 표시하고 검색 기능 제공
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';

// 권한 체크
if ($level > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 요청 변수 초기화
$navibar = $_REQUEST['navibar'] ?? '';
$menu = $_REQUEST['menu'] ?? '';
$page = isset($_REQUEST["page"]) ? intval($_REQUEST["page"]) : 1;
$scale = $_REQUEST["scale"] ?? 50;
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';

// 테이블명
$tablename = "qna";

// 첫 화면 표시 문구
$title_message = '자료실';

include includePath('load_header.php');
?>
 
<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
<?php require_once(includePath('myheader.php')); ?>


<?php
// SQL 쿼리 준비
if ($mode == "search" && !empty($search)) {
    // 🔒 SQL 인젝션 방지: Prepared Statement 사용
    $searchParam = '%' . $search . '%';
    $sql = "select * from " . $DB . "." . $tablename . " 
            where name like ? 
               or subject like ? 
               or nick like ? 
               or regist_day like ? 
               or searchtext like ? 
            order by num desc";
    $params = array($searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
} else {
    $sql = "select * from " . $DB . "." . $tablename . " order by num desc";
    $params = array();
}

// 전체 레코드수 파악
$total_row = 0;
try {
    if (!empty($params)) {
        $stmh = $pdo->prepare($sql);
        $stmh->execute($params);
    } else {
        $stmh = $pdo->query($sql);
    }
    $total_row = $stmh->rowCount();
} catch (PDOException $Exception) {
    error_log("데이터 조회 오류: " . $Exception->getMessage());
}

// 데이터 조회
try {
    if (!empty($params)) {
        $stmh = $pdo->prepare($sql);
        $stmh->execute($params);
    } else {
        $stmh = $pdo->query($sql);
    } 
?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">

<div class="container justify-content-center">

    <input type="hidden" id="page" name="page" value="<?= $page ?>">
    <input type="hidden" id="scale" name="scale" value="<?= $scale ?>">
    
    <div class="card mt-2 mb-4">
        <div class="card-body">
            <div class="d-flex mt-3 mb-2 justify-content-center">
                <h5><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></h5>
                <button type="button" class="btn btn-dark btn-sm mx-3" onclick="location.reload();" title="새로고침">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="d-flex mt-3 mb-1 justify-content-center align-items-center">
                ▷ <?= $total_row ?> &nbsp;
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
                    $start_num = $total_row;    // 페이지당 표시되는 첫번째 글순번
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $item_num = $row["num"] ?? '';
                        $item_id = $row["id"] ?? '';
                        $item_name = $row["name"] ?? '';
                        $item_nick = $row["nick"] ?? '';
                        $item_hit = $row["hit"] ?? 0;
                        $item_date = $row["regist_day"] ?? '';
                        $item_date = substr($item_date, 0, 10);
                        $item_subject = $row["subject"] ?? '';
                        
                        // 댓글 수 조회
                        $sql_ripple = "select * from " . $DB . ".notice_ripple where parent = ?";
                        $stmh1 = $pdo->prepare($sql_ripple);
                        $stmh1->bindValue(1, $item_num, PDO::PARAM_INT);
                        $stmh1->execute();
                        $num_ripple = $stmh1->rowCount();
                    ?>
                    
                    <tr onclick="redirectToView('<?= $item_num ?>', '<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>')">
                        <td class="text-center"><?= $start_num ?></td>
                        <td><?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center"><?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center"><?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center"><?= $item_hit ?></td>
                    </tr>
                    
                    <?php
                        $start_num--;
                    }
                    } catch (PDOException $Exception) {
                        error_log("데이터 출력 오류: " . $Exception->getMessage());
                    }
                    ?>
                    
                    </tbody>
                </table>
            </div>
        </div> <!--card-body-->
    </div> <!--card-->
</div> <!--container-->
</form>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var qnapageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('qnapageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var qnapageNumber = dataTable.page.info().page + 1;
        setCookie('qnapageNumber', qnapageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('qnapageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });

    // 신규 버튼 클릭
    $("#writeBtn").click(function() {
        var page = qnapageNumber;
        var tablename = '<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>';
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '자료실', 1300, 850);
    });

    // 서버에 작업 기록
    saveLogData('자료실');
});

function restorePageNumber() {
    var savedPageNumber = getCookie('qnapageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = qnapageNumber;
    var url = "view.php?num=" + num + "&tablename=" + tablename;
    customPopup(url, '자료실', 1200, 900);
}
</script>
</body>
</html>
