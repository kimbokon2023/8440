<?php
/**
 * Holiday 일정표 휴일 목록 페이지
 * 휴무일 목록을 표시하고 등록/수정/삭제 기능을 제공합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
require_once(includePath('session.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$header = $_REQUEST['header'] ?? '';
$search = $_REQUEST['search'] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 변수 초기화
$tablename = 'holiday';
$title_message = '일정표 휴일';
$total_row = 0;

// 헤더 포함
include getDocumentRoot() . '/load_header.php';
?>

<link href="css/style.css" rel="stylesheet">
<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>

<style>
    /* 전체 레이아웃 */
    body {
        background: #ffffff;
        min-height: 100vh;
        font-family: 'Noto Sans KR', sans-serif;
    }
    
    /* 카드 스타일 */
    .main-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 20px;
        color: white;
    }
    
    .card-header .fs-5 {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    /* 검색 영역 */
    .search-area {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
    }
    
    .inputWrap30 {
        position: relative;
    }
    
    .form-control {
        border: 2px solid #e0e7ff;
        border-radius: 10px;
        padding: 8px 35px 8px 12px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .btnClear {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        cursor: pointer;
        color: #999;
        font-size: 16px;
    }
    
    .btnClear:hover {
        color: #667eea;
    }
    
    /* 버튼 스타일 */
    .btn {
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .btn-dark {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-outline-dark {
        border: 2px solid #667eea;
        color: #667eea;
        background: white;
    }
    
    .btn-outline-dark:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    /* 테이블 스타일 */
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
    }
    
    #myTable {
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        margin-bottom: 0;
    }
    
    #myTable thead {
        background: #f5f7fa;
    }
    
    #myTable thead th {
        color: #000000;
        font-weight: 600;
        padding: 15px;
        border: none;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    #myTable tbody tr {
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    #myTable tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    #myTable tbody td {
        padding: 15px;
        vertical-align: middle;
        border: none;
    }
    
    /* 데이터 카운트 */
    .data-count {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 10px;
    }
    
    /* 모달 스타일 */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 3% auto;
        padding: 0;
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.4s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-header {
        padding: 20px 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-bottom: none;
        border-radius: 20px 20px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: white;
        letter-spacing: 0.5px;
    }
    
    .close {
        color: white;
        font-size: 32px;
        font-weight: 300;
        cursor: pointer;
        opacity: 0.8;
        transition: all 0.3s ease;
        line-height: 1;
    }
    
    .close:hover,
    .close:focus {
        opacity: 1;
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 25px;
    }
    
    /* 아이콘 애니메이션 */
    .btn i {
        transition: transform 0.3s ease;
    }
    
    .btn:hover i {
        transform: scale(1.2);
    }
    
    /* 반응형 디자인 */
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
        .container {
            width: 100% !important;
            max-width: 100vw !important;
            padding: 0.5rem !important;
            margin: 0.5rem auto !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .main-card {
            border-radius: 10px;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            margin: 0 auto !important;
        }
        
        .card-body {
            padding: 1rem !important;
            overflow-x: hidden !important;
        }
        
        .search-area {
            padding: 15px;
        }
        
        /* 검색 UI 최적화 */
        .search-area .d-flex {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.75rem !important;
        }
        
        .search-area .d-flex > div {
            width: 100% !important;
        }
        
        .inputWrap30 {
            width: 100% !important;
        }
        
        .inputWrap30 input#search {
            width: 100% !important;
            max-width: 100% !important;
            height: 40px !important;
        }
        
        .search-area .btn {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 테이블 숨기기 (모바일에서는 카드로 표시) */
        #myTable {
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
            min-width: 100px;
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
        
        .data-count {
            width: 100% !important;
            justify-content: center !important;
            margin-bottom: 0.5rem !important;
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

<?php
// 헤더 포함 여부 확인
if ($header == 'header') {
    require_once(includePath('myheader.php'));
}

/**
 * null 체크 함수
 * @param string|null $strtmp 체크할 문자열
 * @return bool null이 아니고 빈 문자열이 아니면 true
 */
function checkNull($strtmp) {
    return ($strtmp !== null && trim($strtmp) !== '');
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 생성
$order = " ORDER BY registedate DESC, num DESC ";

if (checkNull($search)) {
    // SQL Injection 방지를 위한 Prepared Statement 사용
    $sql = "SELECT * FROM {$DB}.{$tablename} 
            WHERE searchtag LIKE ? AND is_deleted IS NULL " . $order;
    $searchParam = '%' . $search . '%';
} else {
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE is_deleted IS NULL " . $order;
}

try {
    if (checkNull($search)) {
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $searchParam, PDO::PARAM_STR);
        $stmh->execute();
    } else {
        $stmh = $pdo->query($sql);
    }
    
    $total_row = $stmh->rowCount();
?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="mode" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="num" name="num">
    <input type="hidden" id="tablename" name="tablename" value="<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="header" name="header" value="<?php echo htmlspecialchars($header, ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content" style="width:600px;">
            <div class="modal-header">
                <span class="modal-title">일정표 휴일</span>
                <span class="close closeBtn">&times;</span>
            </div>
            <div class="modal-body">
                <div class="custom-card"></div>
            </div>
        </div>
    </div>
    
    <div class="container" style="width:50%; margin-top: 30px;">
        <div class="card main-card">
            <div class="card-header">
                <div class="d-flex justify-content-center align-items-center">
                    <i class="bi bi-calendar-event me-3" style="font-size: 1.5rem;"></i>
                    <span class="text-center fs-5 me-auto"><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></span>
                    <button type="button" class="btn btn-light btn-sm" onclick='location.href="list.php?header=header"' style="border-radius: 50%; width: 38px; height: 38px; padding: 0;">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body" style="padding: 25px;">
                <div class="search-area">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <span class="data-count">
                                <i class="bi bi-folder2-open me-2"></i>
                                <?php echo htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8'); ?>건
                            </span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <div class="inputWrap30">
                                <input type="text" id="search" class="form-control" style="width:180px;" name="search" placeholder="검색어 입력..." value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" onKeyPress="if (event.keyCode == 13) { this.form.submit(); }">
                                <button class="btnClear" style="display: <?php echo !empty($search) ? 'block' : 'none'; ?>;"><i class="bi bi-x-circle-fill"></i></button>
                            </div>
                            <button class="btn btn-outline-dark btn-sm" type="button" id="searchBtn">
                                <i class="bi bi-search"></i>
                            </button>
                            <button id="newBtn" type="button" class="btn btn-dark btn-sm">
                                <i class="bi bi-plus-circle"></i> 신규
                            </button>
                            <?php if ($header !== 'header'): ?>
                            <button id="closeBtn" type="button" class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="row d-flex">
                    <div class="table-responsive">
                        <table class="table table-hover" id="myTable">
                            <thead class="table-info">
                                <tr>
                                    <th class="text-center">번호</th>
                                    <th class="text-center">휴일시작</th>
                                    <th class="text-center">휴일종료</th>
                                    <th class="text-center">기간체크</th>
                                    <th class="text-center">내용</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start_num = $total_row;
                                
                                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                    $num = $row['num'];
                                    $registedate = $row['registedate'] ?? '';
                                    $startdate = $row['startdate'] ?? '';
                                    $enddate = $row['enddate'] ?? '';
                                    $periodcheck = ($row['periodcheck'] ?? '0') ? '예' : '아니오';
                                    $comment = $row['comment'] ?? '';
                                    
                                    // 종료일 표시 처리
                                    $enddateDisplay = ($enddate === '0000-00-00' || empty($enddate)) ? '' : $enddate;
                                ?>
                                <tr onclick="loadForm('update', '<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>');"
                                    data-num="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-start_num="<?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-startdate="<?php echo htmlspecialchars($startdate, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-enddate="<?php echo htmlspecialchars($enddateDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-periodcheck="<?php echo htmlspecialchars($periodcheck, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-comment="<?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?>">
                                    <td class="text-center" data-label="번호"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center" data-label="휴일시작"><?php echo htmlspecialchars($startdate, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center" data-label="휴일종료"><?php echo htmlspecialchars($enddateDisplay, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center" data-label="기간체크"><?php echo htmlspecialchars($periodcheck, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-start" data-label="내용"><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></td>
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
    </div>
</form>

<?php
} catch (PDOException $ex) {
    error_log("DB query error in holiday/list.php: " . $ex->getMessage());
    ?>
    <div class="container" style="width:40%;">
        <div class="alert alert-danger" role="alert">
            데이터 조회 중 오류가 발생했습니다.
        </div>
    </div>
    <?php
}
?>

<script>
// 모바일 카드 렌더링 함수
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-cards-container').html('');
        return; // PC 환경에서는 실행하지 않음
    }
    
    var $container = $('#mobile-cards-container');
    $container.html(''); // 기존 내용 초기화
    
    try {
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
            
            // td에서 직접 데이터 읽기
            var num = $row.attr('data-num') || $row.data('num') || $tds.eq(0).text().trim();
            var start_num = $row.attr('data-start_num') || $row.data('start_num') || $tds.eq(0).text().trim();
            var startdate = $row.attr('data-startdate') || $row.data('startdate') || $tds.eq(1).text().trim();
            var enddate = $row.attr('data-enddate') || $row.data('enddate') || $tds.eq(2).text().trim();
            var periodcheck = $row.attr('data-periodcheck') || $row.data('periodcheck') || $tds.eq(3).text().trim();
            var comment = $row.attr('data-comment') || $row.data('comment') || $tds.eq(4).text().trim();
            
            // 유효성 검사: 잘못된 텍스트 값 제외
            var invalidValues = ['no data available in table', '구분', '번호', '휴일시작', '휴일종료', '기간체크', '내용'];
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
            
            // 클릭 이벤트를 위한 onclick 속성
            var onclickAttr = $row.attr('onclick') || '';
            var cardHtml = '<div class="mobile-card" onclick="' + escapeHtml(onclickAttr) + '">' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(start_num) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">휴일시작:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(startdate) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">휴일종료:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(enddate) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">기간체크:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(periodcheck) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">내용:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(comment) + '</span>' +
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
    
    $(document).ready(function() {
        // 모바일 카드 렌더링
        if (window.innerWidth <= 768) {
            setTimeout(function() {
                renderMobileCards();
            }, 200);
        }
        
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
        // Loader 숨기기
        var loader = document.getElementById('loadingOverlay');
        if (loader) {
            loader.style.display = 'none';
        }
        
        // Modal 닫기 기능
        var modal = document.getElementById('myModal');
        var closeButtons = document.getElementsByClassName('close');
        
        if (closeButtons.length > 0) {
            closeButtons[0].onclick = function() {
                if (modal) {
                    modal.style.display = 'none';
                }
            };
        }
        
        // 모달 외부 클릭 시 닫기
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
        
        // 신규 버튼 클릭 시
        $('#newBtn').on('click', function() {
            loadForm('insert');
        });
        
        // 검색 버튼 클릭 시
        $('#searchBtn').on('click', function() {
            $('#board_form').submit();
        });
        
        // 창닫기 버튼 (있는 경우)
        $('#closeBtn').on('click', function() {
            window.close();
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
    });
    
    /**
     * 폼 로드 함수
     * @param {string} mode - 모드 (insert/update)
     * @param {string|null} num - 레코드 번호
     */
    window.loadForm = function(mode, num) {
        if (num == null) {
            $('#mode').val('insert');
        } else {
            $('#mode').val('update');
            $('#num').val(num);
        }
        
        // 모달 타이틀 업데이트
        var modalTitle = document.querySelector('.modal-title');
        if (modalTitle) {
            modalTitle.textContent = mode === 'update' ? '휴무 수정' : '휴무 신규 등록';
        }
        
        $.ajax({
            type: 'POST',
            url: 'fetch_modal.php',
            data: { 
                mode: mode, 
                num: num 
            },
            dataType: 'html',
            success: function(response) {
                var modalBody = document.querySelector('.modal-body .custom-card');
                if (modalBody) {
                    modalBody.innerHTML = response;
                }
                
                var myModal = document.getElementById('myModal');
                if (myModal) {
                    myModal.style.display = 'block';
                }
                
                // 동적 기간체크 기능 추가
                $('#periodcheck').on('change', function() {
                    var enddateWrapper = $('#enddateWrapper');
                    var enddateInput = $('#enddate');
                    
                    if ($(this).is(':checked')) {
                        enddateWrapper.css({
                            'display': 'flex',
                            'align-items': 'center',
                            'gap': '10px'
                        });
                    } else {
                        enddateWrapper.hide();
                        enddateInput.val('');
                    }
                });
                
                // 초기 로드 시 체크박스 상태에 따른 필드 표시/숨김
                if ($('#periodcheck').is(':checked')) {
                    $('#enddateWrapper').css({
                        'display': 'flex',
                        'align-items': 'center',
                        'gap': '10px'
                    });
                } else {
                    $('#enddateWrapper').hide();
                }
                
                // 모달 닫기 버튼 (동적으로 로드된 버튼에 이벤트 바인딩)
                $('.closeBtn').off('click').on('click', function() {
                    $('#myModal').hide();
                });
                
                // 저장 버튼
                $('#saveBtn').off('click').on('click', function() {
                    var formData = $('#board_form').serialize();
                    
                    // 시작일 필수 확인
                    var startdate = $('#startdate').val();
                    if (!startdate) {
                        Toastify({
                            text: '시작일을 입력해주세요.',
                            duration: 2000,
                            close: true,
                            gravity: 'top',
                            position: 'center',
                            backgroundColor: '#ff5f5f'
                        }).showToast();
                        return;
                    }
                    
                    $.ajax({
                        url: 'process.php',
                        type: 'post',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Toastify({
                                    text: '저장완료',
                                    duration: 2000,
                                    close: true,
                                    gravity: 'top',
                                    position: 'center',
                                    backgroundColor: '#4fbe87'
                                }).showToast();
                                
                                setTimeout(function() {
                                    $('#myModal').hide();
                                    location.reload();
                                }, 1000);
                            } else {
                                Toastify({
                                    text: response.message || '저장 중 오류가 발생했습니다.',
                                    duration: 3000,
                                    close: true,
                                    gravity: 'top',
                                    position: 'center',
                                    backgroundColor: '#ff5f5f'
                                }).showToast();
                            }
                        },
                        error: function(jqxhr, status, error) {
                            console.error('Save error:', jqxhr, status, error);
                            Toastify({
                                text: '저장 중 오류가 발생했습니다.',
                                duration: 3000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#ff5f5f'
                            }).showToast();
                        }
                    });
                });
                
                // 삭제 버튼
                $('#deleteBtn').off('click').on('click', function() {
                    deleteHoliday(num);
                });
            },
            error: function(jqxhr, status, error) {
                console.error('AJAX Error:', status, error);
                Toastify({
                    text: '폼 로드 중 오류가 발생했습니다.',
                    duration: 3000,
                    close: true,
                    gravity: 'top',
                    position: 'center',
                    backgroundColor: '#ff5f5f'
                }).showToast();
            }
        });
    };
    
    /**
     * 휴일 삭제 함수
     * @param {string} num - 삭제할 레코드 번호
     */
    window.deleteHoliday = function(num) {
        Swal.fire({
            title: '자료 삭제',
            text: '정말 삭제하시겠습니까?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then(function(result) {
            if (result.isConfirmed) {
                $('#mode').val('delete');
                $('#num').val(num);
                var formData = $('#board_form').serialize();
                
                $.ajax({
                    url: 'process.php',
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Toastify({
                                text: '삭제 완료',
                                duration: 2000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#ff5f5f'
                            }).showToast();
                            
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            Toastify({
                                text: response.message || '삭제 중 오류가 발생했습니다.',
                                duration: 3000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#ff5f5f'
                            }).showToast();
                        }
                    },
                    error: function(jqxhr, status, error) {
                        console.error('Delete error:', jqxhr, status, error);
                        Toastify({
                            text: '삭제 중 오류가 발생했습니다.',
                            duration: 3000,
                            close: true,
                            gravity: 'top',
                            position: 'center',
                            backgroundColor: '#ff5f5f'
                        }).showToast();
                    }
                });
            }
        });
    };
})();
</script>

</body>
</html>
