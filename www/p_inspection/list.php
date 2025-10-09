<?php
require_once __DIR__ . '/../bootstrap.php';

// 첫 화면 표시 문구
$title_message = '출하검사서';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

$tablename = "p_inspection";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 요청 변수 안전하게 초기화
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';

// SQL 쿼리 생성
if ($mode == "search") {
    if (!$search) {
        $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";
    } else {
        $sql = "SELECT * FROM mirae8440." . $tablename . " WHERE writer LIKE '%$search%' OR subject LIKE '%$search%' ORDER BY num DESC";
    }
} else {
    $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";
}

// 전체 레코드수를 파악한다.
try {
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();

    include includePath('load_header.php');
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES) ?></title>

<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
    }
</style>
</head>

<body>

<?php include includePath("myheader.php"); ?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= $search ?>">
    <input type="hidden" id="page" name="page" value="<?= $page ?>">

    <div class="container mb-5">
        <div class="card mt-2 mb-2">
            <div class="card-body">

                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <img src="<?= $base_url ?>/img/p_inspection.jpg" class="form-control">
                </div>

                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <h4><?= $title_message ?></h4>
                </div>

                <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                    ▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?= $search ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"><i class="bi bi-search"></i> 검색</button>
                    <button type="button" class="btn btn-dark btn-sm" id="writeBtn"><i class="bi bi-pencil"></i> 신규</button> &nbsp;&nbsp;&nbsp;
                </div>

                <div class="row d-flex">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">번호</th>
                                <th class="text-center">현장명</th>
                                <th class="text-center">검사자</th>
                                <th class="text-center">검사일자</th>
                                <th class="text-center">검사결과</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
    $start_num = $total_row;

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';

        $result = 0;
        for ($i = 0; $i <= 9; $i++) {
            if (${'check'.$i} != '' && ${'check'.$i} != '0000-00-00') {
                $result++;
            }
        }

        if ($result == 10) {
            $result = '검사완료';
        } else {
            $result = '미검사';
        }
?>
                            <tr onclick="redirectToView('<?= $num ?>','<?= $parentID ?>', '<?= $tablename ?>')">
                                <td class="text-center"><?= $start_num ?></td>
                                <td class="text-start"><?= $subject ?></td>
                                <td class="text-center"><?= $writer ?></td>
                                <td class="text-center"><?= $regist_day ?></td>
                                <td class="text-center"><?= $result ?></td>
                            </tr>
<?php
        $start_num--;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
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
var outinspectionpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('outinspectionpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var outinspectionpageNumber = dataTable.page.info().page + 1;
        setCookie('outinspectionpageNumber', outinspectionpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('outinspectionpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });

    // 신규 버튼 클릭 이벤트
    $("#writeBtn").click(function() {
        var page = outinspectionpageNumber; // 현재 페이지 번호
        var tablename = '<?php echo $tablename; ?>';
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '출하검사서', 1400, 900);
    });

    // 서버에 작업 기록
    saveLogData('출하 검사서');
});

function restorePageNumber() {
    var savedPageNumber = getCookie('outinspectionpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, parentID, tablename) {
    var page = outinspectionpageNumber; // 현재 페이지 번호
    var url = "view.php?num=" + num + "&parentID=" + parentID + "&tablename=" + tablename;
    customPopup(url, '출하검사서', 1400, 900);
}
</script>
</body>
</html>