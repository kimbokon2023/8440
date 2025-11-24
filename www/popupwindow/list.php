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
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .d-flex.mt-3.mb-3.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-3.justify-content-center span {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.mb-2.px-5.px-lg-2.mt-2.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-2.px-5.px-lg-2.mt-2.justify-content-center.align-items-center > * {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        .d-flex.mb-2.px-5.px-lg-2.mt-2.justify-content-center.align-items-center input#search {
            width: 100% !important;
            max-width: 100% !important;
            height: 40px !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .d-flex.mb-2.px-5.px-lg-2.mt-2.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .d-flex.mb-2.px-5.px-lg-2.mt-2.justify-content-center.align-items-center .total-count {
            width: 100% !important;
            text-align: center !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            font-weight: bold !important;
        }
        
        /* DataTables 컨트롤 숨기기 */
        #myTable_wrapper .dataTables_length,
        #myTable_wrapper .dataTables_filter {
            display: none !important;
        }
        
        /* 테이블 숨기기 (모바일에서는 카드로 표시) */
        #myTable_wrapper {
            visibility: hidden !important;
            position: absolute !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
        }
        
        #myTable {
            visibility: hidden !important;
            position: absolute !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-cards-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: calc(100vw - 1rem);
            max-width: calc(100vw - 1rem);
            box-sizing: border-box;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .mobile-card-item {
            margin-bottom: 0.5rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .mobile-card-label {
            font-weight: bold;
            color: #495057;
            margin-right: 0.5rem;
            display: inline-block;
            min-width: 80px;
        }
        
        .mobile-card-value {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
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
    
    /* PC 환경에서는 모바일 카드 숨기기 */
    @media (min-width: 769px) {
        #mobile-cards-container {
            display: none !important;
        }
        
        #myTable_wrapper {
            display: block !important;
            visibility: visible !important;
            position: relative !important;
            width: auto !important;
            height: auto !important;
            overflow: visible !important;
        }
        
        #myTable {
            visibility: visible !important;
            position: relative !important;
            width: auto !important;
            height: auto !important;
            overflow: visible !important;
        }
    }
</style>

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
                        <span class="total-count">▷ 전체: <?= $total_row ?>건</span>
                        <input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?= $search ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                        <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"><i class="bi bi-search"></i> 검색</button>
                        <button type="button" class="btn btn-dark btn-sm" id="writeBtn"><i class="bi bi-pencil"></i> 신규</button> &nbsp;&nbsp;&nbsp;
                    </div>

                    <div class="table-responsive">
                    <div class="d-flex justify-content-center">
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
                                    <tr data-num="<?= $item_num ?>" data-tablename="<?= $tablename ?>" onclick="redirectToView('<?= $item_num ?>', '<?= $tablename ?>')">
                                        <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                        <td class="text-center" data-label="화면표시/숨김"><?= $division ?></td>
                                        <td data-label="제목"><?= $item_subject ?></td>
                                        <td class="text-center" data-label="작성"><?= $item_nick ?></td>
                                        <td class="text-center" data-label="등록일"><?= $item_date ?></td>
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
                    
                    <!-- 모바일 카드 컨테이너 -->
                    <div id="mobile-cards-container"></div>
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
            "searching": false,
            "pageLength": 50,
            "lengthMenu": [25, 50, 100, 200, 500, 1000],
            "order": [[0, 'desc']],
            "initComplete": function() {
                setTimeout(function() {
                    if (window.innerWidth <= 768) {
                        renderMobileCards();
                    }
                }, 500);
            },
            "drawCallback": function() {
                setTimeout(function() {
                    if (window.innerWidth <= 768) {
                        renderMobileCards();
                    }
                }, 300);
            }
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
        
        // 창 크기 변경 시 카드 재렌더링
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth <= 768) {
                    renderMobileCards();
                }
            }, 250);
        });
        
        // 초기 로드 시 모바일 카드 렌더링
        if (window.innerWidth <= 768) {
            setTimeout(function() {
                renderMobileCards();
            }, 500);
        }
    });

    /**
     * 모바일 카드 렌더링 함수
     */
    function renderMobileCards() {
        if (window.innerWidth > 768) {
            return;
        }
        
        var container = $('#mobile-cards-container');
        if (container.length === 0) {
            return;
        }
        
        container.empty();
        
        // 원본 테이블에서 데이터 읽기
        var rows = $('#myTable tbody tr').filter(function() {
            var $row = $(this);
            var firstTd = $row.find('td').first();
            var firstText = firstTd.text().trim();
            
            // 유효하지 않은 행 필터링
            if (firstText === '' || 
                firstText === 'No data available in table' ||
                firstText === '번호' ||
                firstText === '화면표시/숨김' ||
                firstText === '제목' ||
                firstText === '작성' ||
                firstText === '등록일') {
                return false;
            }
            
            // data-num이 있거나 첫 번째 td가 숫자인 경우만 포함
            var hasDataNum = $row.attr('data-num');
            var isNumeric = /^\d+$/.test(firstText);
            
            return hasDataNum || isNumeric;
        });
        
        if (rows.length === 0) {
            container.html('<div class="mobile-card"><div class="text-center text-muted">데이터가 없습니다.</div></div>');
            return;
        }
        
        rows.each(function() {
            var $row = $(this);
            var num = $row.attr('data-num') || $row.find('td').eq(0).text().trim();
            var tablename = $row.attr('data-tablename') || '<?php echo $tablename; ?>';
            var tds = $row.find('td');
            
            if (tds.length < 5) {
                return;
            }
            
            var cardHtml = '<div class="mobile-card" onclick="redirectToView(\'' + num + '\', \'' + tablename + '\')">';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">번호:</span><span class="mobile-card-value">' + tds.eq(0).text().trim() + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">화면표시/숨김:</span><span class="mobile-card-value">' + tds.eq(1).text().trim() + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">제목:</span><span class="mobile-card-value">' + tds.eq(2).html().trim() + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">작성:</span><span class="mobile-card-value">' + tds.eq(3).text().trim() + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">등록일:</span><span class="mobile-card-value">' + tds.eq(4).text().trim() + '</span></div>';
            cardHtml += '</div>';
            
            container.append(cardHtml);
        });
    }

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

    /**
     * 검색 엔터키 처리
     */
    function SearchEnter() {
        if (event.keyCode === 13) {
            event.preventDefault();
            $('#searchBtn').click();
        }
    }

    // 검색 버튼 클릭 이벤트
    $(document).on('click', '#searchBtn', function() {
        var search = $('#search').val();
        if (search.trim() === '') {
            window.location.href = 'list.php';
        } else {
            window.location.href = 'list.php?mode=search&search=' + encodeURIComponent(search);
        }
    });
</script>