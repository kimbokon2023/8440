<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * RnDnote 연구 노트 목록
 * 
 * 연구 노트 게시글 목록을 표시하고 검색 기능 제공
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
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
$page = $_REQUEST["page"] ?? 1;

// 테이블명 및 타이틀
$tablename = "RnDnote";
$title_message = '연구 노트';

include getDocumentRoot() . '/load_header.php';
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
               or searchtext like ? 
               or regist_day like ? 
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
?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">

<div class="container justify-content-center">
    <div class="card mt-2">
        <div class="card-body">
            
            <div class="d-flex mt-3 mb-3 justify-content-center">
                <span class="fs-5"><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            
            <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                ▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2">
                    <i class="bi bi-search"></i> 검색
                </button>
                <button type="button" class="btn btn-dark btn-sm" id="writeBtn">
                    <i class="bi bi-pencil"></i> 신규
                </button> &nbsp;&nbsp;&nbsp;
            </div>	

            
            <div class="row d-flex">
                <table class="table table-hover" id="myTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">번호</th>
                            <th class="text-center">구분</th>
                            <th class="text-center">글제목</th>
                            <th class="text-center">작성</th>
                            <th class="text-center">등록일</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                    $start_num = $total_row;
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $item_num = $row["num"] ?? '';
                        $item_id = $row["id"] ?? '';
                        $item_name = $row["name"] ?? '';
                        $item_nick = $row["nick"] ?? '';
                        $item_hit = $row["hit"] ?? 0;
                        $item_date = $row["regist_day"] ?? '';
                        $item_date = substr($item_date, 0, 10);
                        $item_subject = $row["subject"] ?? '';
                        $division = $row["division"] ?? '';
                    ?>
                    <tr onclick="redirectToView('<?= $item_num ?>', '<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>')">
                        <td class="text-center"><?= $start_num ?></td>
                        <td class="text-center"><?= htmlspecialchars($division, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center"><?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center"><?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></td>
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
        </div>
    </div>
</div>

</form>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var RnDnoticepageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('RnDnoticepageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var RnDnoticepageNumber = dataTable.page.info().page + 1;
        setCookie('RnDnoticepageNumber', RnDnoticepageNumber, 10);
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw();

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('RnDnoticepageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });

    // 신규 버튼 클릭
    $("#writeBtn").click(function() {
        var page = RnDnoticepageNumber;
        var tablename = '<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>';
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '<?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?>', 1300, 850);
    });

    // 서버에 작업 기록
    saveLogData('연구 노트');
});

function restorePageNumber() {
    var savedPageNumber = getCookie('RnDnoticepageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = RnDnoticepageNumber;
    var url = "view.php?num=" + num + "&tablename=" + tablename;
    customPopup(url, '<?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?>', 1200, 900);
}
</script>
</body>
</html>
