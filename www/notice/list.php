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
            font-size: 1rem !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
            width: 100% !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center span,
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .inputWrap,
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .inputWrap {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .inputWrap input#search {
            width: 100% !important;
            max-width: 100% !important;
            height: 40px !important;
            padding-right: 40px !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .btnClear {
            position: absolute !important;
            right: 10px !important;
            width: 24px !important;
            height: 24px !important;
            background: transparent !important;
            border: none !important;
            cursor: pointer !important;
            z-index: 10 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #6c757d !important;
            font-size: 18px !important;
            line-height: 1 !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .btnClear::before {
            content: '×' !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .btnClear:hover {
            color: #495057 !important;
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
            left: -9999px !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-cards-container {
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
            margin-bottom: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            cursor: pointer;
        }
        
        .mobile-card-item {
            margin-bottom: 0.5rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-label {
            font-weight: bold;
            color: #495057;
            margin-right: 0.5rem;
            display: inline-block;
            min-width: 80px;
        }
        
        .mobile-card-value {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
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
                            
                            <tr onclick="redirectToView('<?= htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>')"
                                data-num="<?= htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8') ?>"
                                data-start_num="<?= htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8') ?>"
                                data-subject="<?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?>"
                                data-nick="<?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?>"
                                data-date="<?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?>"
                                data-hit="<?= htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8') ?>"
                                data-ripple="<?= htmlspecialchars($num_ripple, ENT_QUOTES, 'UTF-8') ?>"
                                data-tablename="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>">
                                <td class="text-center" data-label="번호"><?= htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8') ?></td>
                                <td data-label="글제목">
                                    <?= htmlspecialchars($item_subject, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
                                    <?php if ($num_ripple > 0) { ?>
                                        <span class="badge bg-primary"><?= htmlspecialchars($num_ripple, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php } ?>
                                </td>
                                <td class="text-center" data-label="작성자"><?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="등록일자"><?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="조회수"><?= htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                            
                            <?php
                                $start_num--;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- 모바일 카드 컨테이너 -->
                <div id="mobile-cards-container"></div>
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
// 모바일 카드 렌더링 함수
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-cards-container').html('');
        return; // PC 환경에서는 실행하지 않음
    }
    
    var $container = $('#mobile-cards-container');
    $container.html(''); // 기존 내용 초기화
    
    try {
        // 원본 테이블에서 직접 행 가져오기
        var $targetTable = $('#myTable');
        if ($targetTable.length === 0) {
            console.log('Table not found');
            $container.append('<div class="text-center text-muted p-3">표시할 데이터가 없습니다.</div>');
            return;
        }
        
        var rows = $targetTable.find('tbody tr');
        
        console.log('Target table found:', $targetTable.length);
        console.log('Total rows found:', rows.length);
        
        if (!rows || rows.length === 0) {
            console.log('No rows found in table');
            $container.append('<div class="text-center text-muted p-3">표시할 데이터가 없습니다.</div>');
            return;
        }
        
        var validCardCount = 0;
        var skippedCount = 0;
        
        rows.each(function(index) {
            var $row = $(this);
            
            // td 요소가 없는 경우 제외 (빈 행)
            var $tds = $row.find('td');
            var tdCount = $tds.length;
            if (tdCount === 0) {
                skippedCount++;
                return;
            }
            
            // data 속성에서 직접 데이터 읽기
            var num = $row.attr('data-start_num') || $row.data('start_num') || $tds.eq(0).text().trim();
            var subject = $row.attr('data-subject') || $row.data('subject') || $tds.eq(1).text().trim();
            var nick = $row.attr('data-nick') || $row.data('nick') || $tds.eq(2).text().trim();
            var date = $row.attr('data-date') || $row.data('date') || $tds.eq(3).text().trim();
            var hit = $row.attr('data-hit') || $row.data('hit') || $tds.eq(4).text().trim();
            var ripple = $row.attr('data-ripple') || $row.data('ripple') || '0';
            var itemNum = $row.attr('data-num') || $row.data('num');
            var tablename = $row.attr('data-tablename') || $row.data('tablename');
            
            // 유효성 검사: 잘못된 텍스트 값 제외
            var invalidValues = ['no data available in table', '구분', '번호', '글제목', '작성자', '등록일자', '조회수'];
            var numLower = (num || '').toString().toLowerCase().trim();
            
            if (invalidValues.indexOf(numLower) !== -1) {
                skippedCount++;
                return;
            }
            
            // num이 완전히 비어있는 경우만 제외
            if (!num || num === '' || num === 'undefined' || num === 'null') {
                skippedCount++;
                return;
            }
            
            // itemNum이나 tablename이 없으면 기본값 사용
            if (!itemNum) {
                itemNum = '';
            }
            if (!tablename) {
                tablename = '<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>';
            }
            
            // HTML 이스케이프 처리
            var escapeHtml = function(text) {
                if (!text) return '';
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
            };
            
            // 댓글 배지 HTML 생성
            var rippleBadge = '';
            if (ripple && parseInt(ripple) > 0) {
                rippleBadge = ' <span class="badge bg-primary">' + escapeHtml(ripple) + '</span>';
            }
            
            // 클릭 이벤트를 위한 onclick 속성
            var onclickAttr = $row.attr('onclick') || '';
            var cardHtml = '<div class="mobile-card" onclick="' + escapeHtml(onclickAttr) + '">' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(num) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">글제목:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(subject) + rippleBadge + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작성자:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(nick) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">등록일자:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(date) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">조회수:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(hit) + '</span>' +
                '</div>' +
                '</div>';
            
            $container.append(cardHtml);
            validCardCount++;
        });
        
        console.log('Mobile cards rendered: ' + validCardCount + ' valid cards');
        console.log('Skipped rows: ' + skippedCount);
        
        if (validCardCount === 0) {
            $container.append('<div class="text-center text-muted p-3">표시할 데이터가 없습니다.</div>');
        }
    } catch (error) {
        console.error('Error rendering mobile cards:', error);
    }
}

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
            "order": [[0, 'desc']],
            "drawCallback": function(settings) {
                // 모바일 환경에서 카드 렌더링 (약간의 지연을 두어 완전히 렌더링된 후 실행)
                if (window.innerWidth <= 768) {
                    setTimeout(function() {
                        renderMobileCards();
                    }, 300);
                } else {
                    $('#mobile-cards-container').html('');
                }
            },
            "initComplete": function(settings, json) {
                // 초기화 완료 후 모바일 카드 렌더링
                if (window.innerWidth <= 768) {
                    setTimeout(function() {
                        renderMobileCards();
                    }, 500);
                }
            }
        });
        
        // 화면 크기 변경 시 카드 재렌더링
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth <= 768) {
                    renderMobileCards();
                } else {
                    $('#mobile-cards-container').html('');
                }
            }, 250);
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
        
        // 검색 버튼 클릭
        $("#searchBtn").click(function() {
            var search = $('#search').val();
            location.href = 'list.php?mode=search&search=' + encodeURIComponent(search);
        });
        
        // 검색 입력 필드 clear 버튼
        $('#search').on('input', function() {
            var clearBtn = $('.btnClear');
            if ($(this).val().length > 0) {
                clearBtn.show();
            } else {
                clearBtn.hide();
            }
        });
        
        $('.btnClear').on('click', function() {
            $('#search').val('').focus();
            $(this).hide();
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
    
    // 검색 엔터키 처리
    window.SearchEnter = function() {
        if (event.keyCode == 13) {
            var search = $('#search').val();
            location.href = 'list.php?mode=search&search=' + encodeURIComponent(search);
        }
    };
    
})();
</script>

</body>
</html>
