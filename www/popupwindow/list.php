<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$level = $_SESSION["level"] ?? 999;

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . ($_SESSION["WebSite"] ?? '') . "login/login_form.php");
    exit;
}

$title_message = '팝업창 관리';
?>
<?php include getDocumentRoot() . '/load_header.php' ?>
<title><?= $title_message ?></title>
</head>
<body>

<?php
require_once(includePath('myheader.php'));
require_once(includePath('common/modal.php'));

// 요청 변수 초기화
$tablename = "popupwindow";
$page = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 1;  // 페이지 번호
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 생성
if ($mode == "search") {
    if (!$search) {
        $sql = "select * from {$DB}.{$tablename} order by num desc";
    } else {
        $sql = "select * from {$DB}.{$tablename} where name like '%$search%' or subject like '%$search%' or nick like '%$search%' or searchtext like '%$search%' or regist_day like '%$search%' order by num desc";
    }
} else {
    $sql = "select * from {$DB}.{$tablename} order by num desc";
}

// 전체 레코드수를 파악한다.
$total_row = 0;
try {
    $stmh = $pdo->query($sql);  // 검색조건에 맞는글 stmh
    $total_row = $stmh->rowCount();
?>

    <form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= $search ?>">
        <div class="container justify-content-center">
            <div class="card justify-content-center">
                <div class="card-body">
                    <input type="hidden" id="page" name="page" value="<?= $page ?>">

                    <div class="d-flex mt-3 mb-3 justify-content-center">
                        <span class="fs-5">&nbsp;&nbsp;<?= $title_message ?>&nbsp;&nbsp;</span>
                    </div>

                    <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                        ▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?= $search ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                        <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"><i class="bi bi-search"></i> 검색</button>
                        <button type="button" class="btn btn-dark btn-sm" id="writeBtn"><i class="bi bi-pencil"></i> 신규</button> &nbsp;&nbsp;&nbsp;
                    </div>

                    <div class="row d-flex">
                        <table class="table table-hover" id="myTable">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">번호</th>
                                    <th class="text-center">화면표시/숨김</th>
                                    <th class="text-center">제목</th>
                                    <th class="text-center">작성</th>
                                    <th class="text-center">등록일</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $start_num = $total_row;  // 페이지당 표시되는 첫번째 글순번

                                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                    $item_num = $row["num"] ?? '';
                                    $item_id = $row["id"] ?? '';
                                    $item_name = $row["name"] ?? '';
                                    $item_nick = $row["nick"] ?? '';
                                    $item_hit = $row["hit"] ?? 0;
                                    $item_date = $row["regist_day"] ?? '';
                                    $item_date = substr($item_date, 0, 10);
                                    $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');
                                    $division = $row["division"] ?? '';
                                ?>
                                    <tr onclick="redirectToView('<?= $item_num ?>', '<?= $tablename ?>')">
                                        <td class="text-center"><?= $start_num ?></td>
                                        <td class="text-center"><?= $division ?></td>
                                        <td><?= $item_subject ?></td>
                                        <td class="text-center"><?= $item_nick ?></td>
                                        <td class="text-center"><?= $item_date ?></td>
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
</body>
</html>

<script>
    var dataTable; // DataTables 인스턴스 전역 변수
    var HRpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
        var savedPageNumber = getCookie('HRpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }

        // 페이지 변경 이벤트 리스너
        dataTable.on('page.dt', function() {
            var HRpageNumber = dataTable.page.info().page + 1;
            setCookie('HRpageNumber', HRpageNumber, 10); // 쿠키에 페이지 번호 저장
        });

        // 페이지 길이 셀렉트 박스 변경 이벤트 처리
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

            // 변경 후 현재 페이지 번호 복원
            savedPageNumber = getCookie('HRpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }
        });

        // 신규 버튼 클릭 이벤트
        $("#writeBtn").click(function() {
            var page = HRpageNumber; // 현재 페이지 번호
            var tablename = '<?php echo $tablename; ?>';
            var url = "write_form.php?tablename=" + tablename;
            customPopup(url, '', 1300, 850);
        });
    });

    function restorePageNumber() {
        var savedPageNumber = getCookie('HRpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
        }
    }

    function redirectToView(num, tablename) {
        var page = HRpageNumber; // 현재 페이지 번호
        var url = "view.php?num=" + num + "&tablename=" + tablename;
        customPopup(url, '', 1200, 900);
    }
</script>