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
        .d-flex.mt-3.mb-2.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-2.justify-content-center h5 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        .d-flex.mt-3.mb-2.justify-content-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center > * {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .total-count {
            width: 100% !important;
            text-align: center !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            font-weight: bold !important;
        }
        
        .inputWrap {
            width: 100% !important;
            max-width: 100% !important;
            position: relative !important;
            display: block !important;
        }
        
        .inputWrap input#search {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            height: 40px !important;
            padding: 0.5rem 35px 0.5rem 0.5rem !important;
            font-size: 1rem !important;
            box-sizing: border-box !important;
            margin: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        .inputWrap .btnClear {
            position: absolute !important;
            right: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 24px !important;
            height: 24px !important;
            min-width: 24px !important;
            min-height: 24px !important;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>') no-repeat center !important;
            background-size: 18px 18px !important;
            background-color: transparent !important;
            border: none !important;
            cursor: pointer !important;
            z-index: 10 !important;
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
        }

        @media (max-width: 768px) {
            .inputWrap .btnClear {
                right: 0 !important; /* 모바일에서는 완전히 input의 우측끝으로 */
            }
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
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
            min-width: 60px;
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
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
        }
    }
</style>

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
                <span class="total-count">▷ 전체: <?= $total_row ?>건</span>
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

            <div class="table-responsive">
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
                    
                    <tr data-num="<?= $item_num ?>" data-tablename="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>" onclick="redirectToView('<?= $item_num ?>', '<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>')">
                        <td class="text-center" data-label="번호"><?= $start_num ?></td>
                        <td data-label="글제목"><?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center" data-label="작성자"><?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center" data-label="등록일자"><?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center" data-label="조회수"><?= $item_hit ?></td>
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
            
            <!-- 모바일 카드 컨테이너 -->
            <div id="mobile-cards-container"></div>
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
            firstText === '글제목' ||
            firstText === '작성자' ||
            firstText === '등록일자' ||
            firstText === '조회수') {
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
        var tablename = $row.attr('data-tablename') || '<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>';
        var tds = $row.find('td');
        
        if (tds.length < 5) {
            return;
        }
        
        var cardHtml = '<div class="mobile-card" onclick="redirectToView(\'' + num + '\', \'' + tablename + '\')">';
        cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">번호:</span><span class="mobile-card-value">' + tds.eq(0).text().trim() + '</span></div>';
        cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">글제목:</span><span class="mobile-card-value">' + tds.eq(1).text().trim() + '</span></div>';
        cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">작성자:</span><span class="mobile-card-value">' + tds.eq(2).text().trim() + '</span></div>';
        cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">등록일자:</span><span class="mobile-card-value">' + tds.eq(3).text().trim() + '</span></div>';
        cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">조회수:</span><span class="mobile-card-value">' + tds.eq(4).text().trim() + '</span></div>';
        cardHtml += '</div>';
        
        container.append(cardHtml);
    });
}

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

// 검색어 지우기 버튼
$(document).on('click', '.btnClear', function() {
    $('#search').val('');
    $('#search').focus();
});
</script>
</body>
</html>
