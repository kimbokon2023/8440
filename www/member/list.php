<?php
/**
 * 회원 정보 관리 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$WebSite = $protocol . "://" . $host;

// 세션 변수 안전하게 가져오기
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 확인
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location: {$WebSite}/login/logout.php");
    exit;
}

// 요청 변수 초기화
$page = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 1;
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : '';
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : '';
$sort_field = isset($_REQUEST["sort_field"]) ? $_REQUEST["sort_field"] : 'id';
$sort_order = isset($_REQUEST["sort_order"]) ? $_REQUEST["sort_order"] : 'desc';
$exclude_resigned = isset($_REQUEST["exclude_resigned"]) ? $_REQUEST["exclude_resigned"] : '1'; // 기본값: 체크됨 (퇴사자 제외)

// 허용된 정렬 필드 (SQL Injection 방지)
$allowed_sort_fields = ['id', 'name', 'division', 'part', 'position', 'hp', 'level', 'numorder', 'eworks_level'];
if (!in_array($sort_field, $allowed_sort_fields)) {
    $sort_field = 'id';
}

// 허용된 정렬 순서
$allowed_sort_orders = ['asc', 'desc'];
if (!in_array(strtolower($sort_order), $allowed_sort_orders)) {
    $sort_order = 'desc';
}

// 페이지 설정
$scale = 50;
$page_scale = 10;
$first_num = ($page - 1) * $scale;

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 테이블명
$tablename = "member";

// SQL 쿼리 생성
$order_clause = " ORDER BY {$sort_field} {$sort_order}";

// 퇴사자 제외 조건
$exclude_resigned_condition = "";
if ($exclude_resigned == '1') {
    $exclude_resigned_condition = " AND position != '퇴사'";
}

$sql = "";
$sqlcon = "";
$params = [];
$params_con = [];

if ($mode == "search" && !empty($search)) {
    $sql = "SELECT * FROM {$DB}.{$tablename} 
            WHERE (id LIKE ? OR name LIKE ? OR nick LIKE ?) {$exclude_resigned_condition}
            {$order_clause} 
            LIMIT ?, ?";
    $sqlcon = "SELECT * FROM {$DB}.{$tablename} 
               WHERE (id LIKE ? OR name LIKE ? OR nick LIKE ?) {$exclude_resigned_condition}
               {$order_clause}";
    $searchParam = "%{$search}%";
    $params = [$searchParam, $searchParam, $searchParam, $first_num, $scale];
    $params_con = [$searchParam, $searchParam, $searchParam];
} else {
    $where_clause = $exclude_resigned_condition ? "WHERE 1=1 {$exclude_resigned_condition}" : "";
    $sql = "SELECT * FROM {$DB}.{$tablename} {$where_clause} {$order_clause} LIMIT ?, ?";
    $sqlcon = "SELECT * FROM {$DB}.{$tablename} {$where_clause} {$order_clause}";
    $params = [$first_num, $scale];
    $params_con = [];
}

// 변수 초기화
$member_list = [];
$total_row = 0;
$result_count = 0;

// 쿼리 실행
try {
    // 디버깅: SQL 쿼리 확인
    error_log("DEBUG - SQL: " . $sql);
    error_log("DEBUG - Params: " . print_r($params, true));
    error_log("DEBUG - DB: " . $DB);
    error_log("DEBUG - Table: " . $tablename);
    
    // === 임시 디버깅: 화면에 출력 ===
    echo "<!-- DEBUG INFO -->";
    echo "<!-- DB: {$DB} -->";
    echo "<!-- Table: {$tablename} -->";
    echo "<!-- Mode: {$mode} -->";
    echo "<!-- Search: {$search} -->";
    
    // 전체 레코드 수 (COUNT 사용)
    try {
        if ($mode == "search" && !empty($search)) {
            $count_sql = "SELECT COUNT(*) as total FROM {$DB}.{$tablename} 
                          WHERE (id LIKE ? OR name LIKE ? OR nick LIKE ?) {$exclude_resigned_condition}";
            echo "<!-- COUNT SQL (SEARCH): {$count_sql} -->";
            $count_stmh = $pdo->prepare($count_sql);
            $searchParam = "%{$search}%";
            $count_stmh->execute([$searchParam, $searchParam, $searchParam]);
        } else {
            $where_clause = $exclude_resigned_condition ? "WHERE 1=1 {$exclude_resigned_condition}" : "";
            $count_sql = "SELECT COUNT(*) as total FROM {$DB}.{$tablename} {$where_clause}";
            echo "<!-- COUNT SQL: {$count_sql} -->";
            $count_stmh = $pdo->query($count_sql);
        }
        $count_result = $count_stmh->fetch(PDO::FETCH_ASSOC);
        echo "<!-- COUNT RESULT: " . print_r($count_result, true) . " -->";
        
        // COUNT 결과 확인 및 처리
        if ($count_result && isset($count_result['total'])) {
            $total_row = (int)$count_result['total'];
        } else {
            // COUNT 쿼리 실패 시 대체 방법: 실제 조회된 데이터 수 사용
            $total_row = 0;
            error_log("WARNING - COUNT query returned no result, using fallback");
        }
        
        echo "<!-- TOTAL ROW: {$total_row} -->";
        error_log("DEBUG - Total rows (COUNT): " . $total_row);
    } catch (PDOException $e) {
        error_log("COUNT query error: " . $e->getMessage());
        $total_row = 0;
    }
    
    // 페이지별 레코드 조회 (배열로 받기)
    if ($mode == "search" && !empty($search)) {
        $searchParam = "%{$search}%";
        $sql_final = "SELECT * FROM {$DB}.{$tablename} 
                      WHERE id LIKE ? OR name LIKE ? OR nick LIKE ? 
                      {$order_clause} 
                      LIMIT ?, ?";
        $stmh = $pdo->prepare($sql_final);
        $stmh->bindValue(1, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(2, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(3, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(4, $first_num, PDO::PARAM_INT);
        $stmh->bindValue(5, $scale, PDO::PARAM_INT);
        $stmh->execute();
        echo "<!-- SEARCH SQL: {$sql_final} -->";
    } else {
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $first_num, PDO::PARAM_INT);
        $stmh->bindValue(2, $scale, PDO::PARAM_INT);
        $stmh->execute();
        echo "<!-- NORMAL SQL: {$sql} -->";
    }
    
    // 배열로 전부 받기 - 이것이 핵심!
    $member_list = $stmh->fetchAll(PDO::FETCH_ASSOC);
    $result_count = count($member_list);
    
    echo "<!-- FETCHED ROWS: {$result_count} -->";
    echo "<!-- FIRST MEMBER: " . print_r($member_list[0] ?? 'EMPTY', true) . " -->";
    error_log("DEBUG - Result count: " . $result_count);
    
    // total_row가 0이고 실제 데이터가 있으면 total_row 재계산
    if ($total_row == 0 && $result_count > 0) {
        // 전체 카운트를 다시 시도
        try {
            $where_clause = $exclude_resigned_condition ? "WHERE 1=1 {$exclude_resigned_condition}" : "";
            $count_sql = "SELECT COUNT(*) as total FROM {$DB}.{$tablename} {$where_clause}";
            $count_stmh = $pdo->query($count_sql);
            $count_result = $count_stmh->fetch(PDO::FETCH_ASSOC);
            if ($count_result && isset($count_result['total'])) {
                $total_row = (int)$count_result['total'];
                error_log("DEBUG - Total row recalculated: " . $total_row);
            }
        } catch (PDOException $e) {
            error_log("Recalculation COUNT query error: " . $e->getMessage());
        }
    }
    
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
    
} catch (PDOException $ex) {
    error_log("회원 목록 조회 오류: " . $ex->getMessage());
    error_log("DEBUG - SQL that failed: " . $sql);
    echo "<div class='container mt-5'>";
    echo "<div class='alert alert-danger'>";
    echo "<h4>오류: 데이터를 불러오는 중 문제가 발생했습니다.</h4>";
    echo "<p>상세: " . htmlspecialchars($ex->getMessage()) . "</p>";
    echo "<hr>";
    echo "<p class='small'>SQL: " . htmlspecialchars($sql) . "</p>";
    echo "<p><a href='test_query.php' class='btn btn-primary'>테스트 페이지에서 확인하기</a></p>";
    echo "</div></div>";
    exit;
}

include getDocumentRoot() . '/load_header.php';

?>

<title>미래기업 회원관리</title>

<style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #64748b;
        --success-color: #22c55e;
        --bg-color: #f8fafc;
        --card-bg: #ffffff;
        --table-header-bg: #f1f5f9;
        --border-color: #e2e8f0;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-main);
        font-family: 'Noto Sans KR', 'Roboto', sans-serif;
    }

    .main-card {
        background: var(--card-bg);
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .page-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(to right, #ffffff, #f8fafc);
    }

    .page-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        color: var(--primary-color);
    }

    /* Action Bar Styles */
    .action-bar {
        padding: 1.25rem 2rem;
        background: #ffffff;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .stats-badge {
        background: #eff6ff;
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid #dbeafe;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-left: auto;
    }

    /* Modern Search Input */
    .search-container {
        position: relative;
        min-width: 300px;
    }

    .search-input {
        width: 100%;
        padding: 0.625rem 2.5rem 0.625rem 1rem;
        font-size: 0.875rem;
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        transition: all 0.2s;
        background-color: #f1f5f9;
        border: 1px solid transparent;
    }

    .search-input:focus {
        background-color: #ffffff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .search-clear {
        position: absolute;
        right: 2.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        cursor: pointer;
        padding: 0.25rem;
        display: none;
    }

    .search-clear:hover {
        color: var(--text-main);
    }

    /* Premium Button Styles */
    .btn-premium {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .btn-premium-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-premium-primary:hover {
        background-color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-premium-secondary {
        background-color: #ffffff;
        color: var(--text-main);
        border-color: var(--border-color);
    }

    .btn-premium-secondary:hover {
        background-color: #f8fafc;
        border-color: var(--secondary-color);
    }

    /* Table Styles */
    .table-container {
        padding: 0;
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table th {
        background-color: var(--table-header-bg);
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
    }

    .custom-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-main);
        font-size: 0.875rem;
    }

    .custom-table tbody tr {
        transition: all 0.2s;
    }

    .custom-table tbody tr:hover {
        background-color: #f8fafc;
        cursor: pointer;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Sort Icons */
    .sort-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: inherit;
        text-decoration: none;
    }

    .sort-icon {
        font-size: 0.7rem;
        opacity: 0.3;
        transition: opacity 0.2s;
    }

    .sort-icon.active {
        opacity: 1;
        color: var(--primary-color);
    }

    /* Level & Status Badges */
    .badge-level {
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .level-admin { background: #fee2e2; color: #ef4444; }
    .level-manager { background: #fef3c7; color: #d97706; }
    .level-user { background: #f1f5f9; color: #475569; }

    /* Pagination Styles */
    .pagination-container {
        padding: 2rem;
        display: flex;
        justify-content: center;
        background: #ffffff;
    }

    .pagination-list {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .page-btn {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        border: 1px solid var(--border-color);
        background: white;
        color: var(--text-main);
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .page-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: #eff6ff;
    }

    .page-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .page-btn-nav {
        width: auto;
        padding: 0 1rem;
    }

    /* Password Input in Table */
    .table-input-pw {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-family: monospace;
        letter-spacing: 0.1em;
        width: 80px;
        text-align: center;
    }

    /* Mobile Responsive Optimizations */
    @media (max-width: 1024px) {
        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group {
            margin-left: 0;
            flex-wrap: wrap;
        }
        .search-container {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .container { padding: 0.5rem !important; }
        .main-card { border-radius: 0; border-left: none; border-right: none; }
        .page-header { padding: 1rem; }
        
        .custom-table thead { display: none; }
        .custom-table tbody tr {
            display: block;
            padding: 1rem;
            border-bottom: 8px solid var(--bg-color);
            position: relative;
        }
        
        .custom-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
            text-align: right;
        }

        .custom-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            text-align: left;
        }

        .custom-table td:last-child { border-bottom: none; }
    }
</style>
</head>
<body>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<main class="container-fluid py-4">
    <div class="main-card">
        <form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="sort_field" id="sort_field" value="<?= htmlspecialchars($sort_field, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="sort_order" id="sort_order" value="<?= htmlspecialchars($sort_order, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" id="exclude_resigned" name="exclude_resigned" value="<?= htmlspecialchars($exclude_resigned, ENT_QUOTES, 'UTF-8') ?>">

            <!-- 페이지 헤더 -->
            <header class="page-header">
                <h1 class="page-title">
                    <i class="bi bi-people-fill"></i>
                    회원 정보관리
                </h1>
                <button type="button" class="btn-premium btn-premium-secondary" onclick='location.reload();' title="새로고침">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span class="d-none d-sm-inline">새로고침</span>
                </button>
            </header>

            <!-- 액션 및 검색 바 -->
            <section class="action-bar">
                <div class="stats-badge">
                    <i class="bi bi-person-check me-2"></i>
                    총 <?= $total_row ?>명
                </div>

                <div class="form-check form-switch ms-2">
                    <input class="form-check-input" type="checkbox" id="excludeResignedCheckbox" <?= $exclude_resigned == '1' ? 'checked' : '' ?> onchange="toggleExcludeResigned()">
                    <label class="form-check-label text-muted small" for="excludeResignedCheckbox">퇴사자 제외</label>
                </div>

                <div class="filter-group">
                    <div class="search-container">
                        <input type="text" name="search" id="search" class="search-input" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" onkeydown="SearchEnter();" placeholder="ID, 이름, 닉네임 검색...">
                        <i class="bi bi-x-circle-fill search-clear" id="searchClear"></i>
                        <i class="bi bi-search search-icon"></i>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn-premium btn-premium-primary" onclick="popupCenter('write_form.php?id=null', '회원 등록', 800, 500);return false;">
                            <i class="bi bi-plus-lg"></i> 등록
                        </button>
                        <button type="button" class="btn-premium btn-premium-secondary" onclick="popupCenter('setline.php?id=null', '결재라인 등록', 600, 800);return false;">
                            <i class="bi bi-diagram-3"></i> 결재라인
                        </button>
                    </div>
                </div>
            </section>
        
            <!-- 데이터 테이블 -->
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">번호</th>
                            <th>
                                <a href="javascript:void(0)" class="sort-link" onclick="sortTable('division')">
                                    구분
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'division' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'division' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort-link" onclick="sortTable('name')">
                                    이름
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'name' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'name' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort-link" onclick="sortTable('part')">
                                    파트
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'part' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'part' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort-link" onclick="sortTable('position')">
                                    직위
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'position' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'position' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort-link" onclick="sortTable('id')">
                                    ID
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'id' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'id' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">P/W</th>
                            <th>
                                <a href="javascript:void(0)" class="sort-link" onclick="sortTable('hp')">
                                    연락처
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'hp' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'hp' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="javascript:void(0)" class="sort-link justify-content-center" onclick="sortTable('level')">
                                    레벨
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'level' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'level' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="javascript:void(0)" class="sort-link justify-content-center" onclick="sortTable('numorder')">
                                    순서
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'numorder' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'numorder' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="javascript:void(0)" class="sort-link justify-content-center" onclick="sortTable('eworks_level')">
                                    결재레벨
                                    <i class="bi bi-caret-up-fill sort-icon <?= ($sort_field == 'eworks_level' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                                    <i class="bi bi-caret-down-fill sort-icon <?= ($sort_field == 'eworks_level' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $start_num = ($page - 1) * $scale + 1;
                        $row_count = 0;
                        
                        foreach ($member_list as $row) {
                            $row_count++;
                            $id = $row["id"] ?? '';
                            $pass = $row["pass"] ?? '';
                            $name = $row["name"] ?? '';
                            $level_val = $row["level"] ?? '';
                            $part = $row["part"] ?? '';
                            $hp = $row["hp"] ?? '';
                            $position = $row["position"] ?? '';
                            $division = $row["division"] ?? '';
                            $numorder = $row["numorder"] ?? '';
                            $eworks_level = $row["eworks_level"] ?? '';

                            // 레벨별 뱃지 클래스
                            $level_class = 'level-user';
                            if ($level_val <= 1) $level_class = 'level-admin';
                            else if ($level_val <= 5) $level_class = 'level-manager';
                            ?>
                            <tr onclick="redirectToView('<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>')">
                                <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                <td data-label="구분">
                                    <span class="text-secondary small"><?= htmlspecialchars($division, ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td data-label="이름">
                                    <div class="fw-bold"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td data-label="파트"><?= htmlspecialchars($part, ENT_QUOTES, 'UTF-8') ?></td>
                                <td data-label="직위"><?= htmlspecialchars($position, ENT_QUOTES, 'UTF-8') ?></td>
                                <td data-label="ID"><?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="P/W">
                                    <input type="password" value="<?= htmlspecialchars($pass, ENT_QUOTES, 'UTF-8') ?>" class="table-input-pw" readonly>
                                </td>
                                <td data-label="연락처"><?= htmlspecialchars($hp, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="레벨">
                                    <span class="badge-level <?= $level_class ?>"><?= htmlspecialchars($level_val, ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="text-center" data-label="순서">
                                    <small class="text-muted"><?= htmlspecialchars($numorder, ENT_QUOTES, 'UTF-8') ?></small>
                                </td>
                                <td class="text-center" data-label="결재레벨">
                                    <span class="badge rounded-pill bg-light text-dark border"><?= htmlspecialchars($eworks_level, ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                            </tr>
                            <?php
                            $start_num++;
                        }
                        
                        if ($row_count == 0) {
                            ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox-fill display-1 opacity-25"></i>
                                        <p class="mt-4 fs-5">표시할 데이터가 없습니다.</p>
                                        <?php if ($mode == "search" && !empty($search)) { ?>
                                            <p class="small">검색어: <strong><?= htmlspecialchars($search) ?></strong></p>
                                            <button type="button" class="btn-premium btn-premium-secondary btn-sm" onclick="location.href='list.php'">전체 목록 보기</button>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- 페이지네이션 -->
            <div class="pagination-container">
                <div class="pagination-list">
                    <?php
                    $start_page = ($current_page - 1) * $page_scale + 1;
                    $end_page = $start_page + $page_scale - 1;
                    
                    if ($page != 1 && $page > $page_scale) {
                        $prev_page = $page - $page_scale;
                        if ($prev_page <= 0) $prev_page = 1;
                        echo '<button class="page-btn page-btn-nav" type="button" onclick="movetoPage(' . $prev_page . ')"><i class="bi bi-chevron-double-left"></i></button>';
                    }
                    
                    for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                        $active_class = ($page == $i) ? 'active' : '';
                        if ($page == $i) {
                            echo '<span class="page-btn ' . $active_class . '">' . $i . '</span>';
                        } else {
                            echo '<button class="page-btn" type="button" onclick="movetoPage(' . $i . ')">' . $i . '</button>';
                        }
                    }
                    
                    if ($page < $total_page) {
                        $next_page = $page + $page_scale;
                        if ($next_page > $total_page) $next_page = $total_page;
                        echo '<button class="page-btn page-btn-nav" type="button" onclick="movetoPage(' . $next_page . ')"><i class="bi bi-chevron-double-right"></i></button>';
                    }
                    ?>
                </div>
            </div>
        </form>
    </div>
</main>

<script type="text/javascript">
(function() {
    'use strict';
    
    // 회원 상세보기 (팝업 또는 이동)
    window.redirectToView = function(id) {
        if (typeof popupCenter !== 'undefined') {
            popupCenter('write_form.php?id=' + encodeURIComponent(id), '회원정보 수정', 800, 550);
        } else {
            window.location.href = 'write_form.php?id=' + encodeURIComponent(id);
        }
    };
    
    // 테이블 정렬
    window.sortTable = function(field) {
        var currentSortField = $("#sort_field").val();
        var currentSortOrder = $("#sort_order").val();
        
        var newOrder = (currentSortField === field && currentSortOrder === 'asc') ? 'desc' : 'asc';
        
        $("#sort_field").val(field);
        $("#sort_order").val(newOrder);
        $("#page").val('1');
        $("#board_form").submit();
    };
    
    // 페이지 이동
    window.movetoPage = function(page) {
        $("#page").val(page);
        $("#board_form").submit();
    };
    
    // 검색 엔터키 처리
    window.SearchEnter = function() {
        if (event.keyCode == 13) {
            $("#page").val('1');
            document.getElementById('board_form').submit();
        }
    };
    
    // 퇴사자 제외 토글
    window.toggleExcludeResigned = function() {
        var isChecked = document.getElementById('excludeResignedCheckbox').checked;
        document.getElementById('exclude_resigned').value = isChecked ? '1' : '0';
        $("#page").val('1');
        document.getElementById('board_form').submit();
    };
    
    $(document).ready(function() {
        const $searchInput = $('#search');
        const $searchClear = $('#searchClear');

        // 검색어 입력 시 지우기 버튼 노출 제어
        $searchInput.on('input', function() {
            if ($(this).val().length > 0) {
                $searchClear.fadeIn(200);
            } else {
                $searchClear.fadeOut(200);
            }
        });

        // 초기 로드 시 검색어가 있으면 버튼 표시
        if ($searchInput.val().length > 0) {
            $searchClear.show();
        }

        // 검색어 지우기 클릭
        $searchClear.on('click', function() {
            $searchInput.val('').focus();
            $(this).fadeOut(200);
        });

        // 행 마우스 오버 시 미세한 애니메이션 (옵션)
        $('.custom-table tbody tr').on('mouseenter', function() {
            $(this).find('.badge-level').css('transform', 'scale(1.05)');
        }).on('mouseleave', function() {
            $(this).find('.badge-level').css('transform', 'scale(1)');
        });
    });
    
})();
</script>
</body>
</html>
