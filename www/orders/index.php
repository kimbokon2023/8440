<?php
/**
 * 구매발주서 관리 메인 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base_url = "{$protocol}://{$host}";
    $_SESSION["url"] = "{$base_url}/orders/index.php";
    sleep(1);
    header("Location: {$base_url}/login/logout.php");
    exit;
}

// load_header 포함
include getDocumentRoot() . '/load_header.php';

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>데이터베이스 연결 실패: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}

// 페이지네이션 설정
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// 검색 조건
$search_keyword = isset($_GET['search_keyword']) ? trim($_GET['search_keyword']) : '';
$search_date_from = $_GET['search_date_from'] ?? '';
$search_date_to = $_GET['search_date_to'] ?? '';
$search_status = $_GET['search_status'] ?? 'all';
$date_field = $_GET['date_field'] ?? 'issue';

// 정렬 조건
$sort_column = $_GET['sort'] ?? 'issue_date';
$sort_direction = $_GET['dir'] ?? 'desc';

// 허용된 정렬 컬럼
$allowed_sort_columns = [
    'issue_date' => '발행일',
    'contact_name' => '거래처명',
    'subtotal' => '공급가액',
    'business_registration_number' => '사업자번호',
    'status' => '상태',
    'delivery_date' => '납기일자',
    'created_at' => '등록일'
];

// 정렬 컬럼 검증
if (!array_key_exists($sort_column, $allowed_sort_columns)) {
    $sort_column = 'issue_date';
}

// 정렬 방향 검증
if (!in_array(strtolower($sort_direction), ['asc', 'desc'])) {
    $sort_direction = 'desc';
}

$sort_direction = strtoupper($sort_direction);

$allowed_status = ['all', 'draft', 'sent', 'completed'];
if (!in_array($search_status, $allowed_status, true)) {
    $search_status = 'all';
}

$allowed_date_fields = ['issue', 'delivery'];
if (!in_array($date_field, $allowed_date_fields, true)) {
    $date_field = 'issue';
}

// WHERE 조건 구성
$where_conditions = ["is_deleted = 0"];
$params = [];

if ($search_keyword) {
    // 공급업체명, 품목/규격 검색 (PHP 7.3 호환)
    $where_conditions[] = "(supplier_name LIKE :search1 OR order_items LIKE :search2 OR note LIKE :search3)";
    $searchTerm = '%' . $search_keyword . '%';
    $params[':search1'] = $searchTerm;
    $params[':search2'] = $searchTerm;
    $params[':search3'] = $searchTerm;
}

if ($search_date_from) {
    if ($date_field === 'delivery') {
        $where_conditions[] = "delivery_date >= :search_date_from";
    } else {
        $where_conditions[] = "issue_date >= :search_date_from";
    }
    $params[':search_date_from'] = $search_date_from;
}

if ($search_date_to) {
    if ($date_field === 'delivery') {
        $where_conditions[] = "delivery_date <= :search_date_to";
    } else {
        $where_conditions[] = "issue_date <= :search_date_to";
    }
    $params[':search_date_to'] = $search_date_to;
}

if ($search_status !== 'all') {
    $where_conditions[] = "status = :search_status";
    $params[':search_status'] = $search_status;
}

$where_clause = implode(' AND ', $where_conditions);

// 전체 레코드 수 조회
$count_sql = "SELECT COUNT(*) FROM `orders` WHERE $where_clause";
$count_stmt = $pdo->prepare($count_sql);

foreach ($params as $key => $value) {
    $count_stmt->bindValue($key, $value);
}

$count_stmt->execute();
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// 데이터 조회
// 정렬 컬럼이 contact_name인 경우 COALESCE로 처리
$order_by_clause = '';
if ($sort_column === 'contact_name') {
    $order_by_clause = "ORDER BY COALESCE(contact_name, supplier_name) $sort_direction, created_at DESC";
} else {
    $order_by_clause = "ORDER BY $sort_column $sort_direction, created_at DESC";
}

$sql = "SELECT * FROM `orders` WHERE $where_clause $order_by_clause LIMIT :offset, :per_page";
$stmt = $pdo->prepare($sql);

// 파라미터 바인딩
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);

$stmt->execute();
$orders = $stmt->fetchAll();

// 통계 데이터 조회
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_count,
    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent_count,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count
FROM `orders` WHERE is_deleted = 0";
$stats = $pdo->query($stats_sql)->fetch();
?>

<title>구매발주서 관리 - 미래정공</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
:root {
    /* 라이트 모드 색상 - 파란색 계열 */
    --bg-primary: #ffffff;
    --bg-secondary: #ffffff;
    --bg-card: #f8f9fa;
    --bg-gradient-start: #2196f3;
    --bg-gradient-end: #1976d2;
    --text-primary: #333333;
    --text-secondary: #666666;
    --text-white: #ffffff;
    --border-color: #e0e0e0;
    --border-light: #f0f0f0;
    --shadow: rgba(0,0,0,0.08);
    --shadow-hover: rgba(33, 150, 243, 0.2);
    --hover-bg: #f5f9ff;
}

[data-theme="dark"] {
    /* 다크 모드 색상 */
    --bg-primary: #1a1a2e;
    --bg-secondary: #16213e;
    --bg-card: #1e2a3a;
    --bg-gradient-start: #1976d2;
    --bg-gradient-end: #0d47a1;
    --text-primary: #e2e8f0;
    --text-secondary: #cbd5e0;
    --text-white: #ffffff;
    --border-color: #4a5568;
    --border-light: #2d3748;
    --shadow: rgba(0,0,0,0.3);
    --shadow-hover: rgba(25, 118, 210, 0.5);
    --hover-bg: #1e3a5f;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: background-color 0.3s ease, color 0.3s ease;
}

.top-navbar {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px var(--shadow);
    position: sticky;
    top: 0;
    z-index: 100;
}

.top-navbar .logo {
    font-size: 20px;
    font-weight: bold;
}

.top-navbar .user-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.top-navbar .user-name {
    display: flex;
    align-items: center;
    gap: 8px;
}

.theme-toggle {
    background: rgba(255,255,255,0.2);
    color: var(--text-white);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
}

.theme-toggle:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.05);
}

.btn-logout {
    background: rgba(255,255,255,0.2);
    color: var(--text-white);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-logout:hover {
    background: rgba(255,255,255,0.3);
    color: var(--text-white);
}

.order-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 15px 12px 25px 12px;
}

.page-header {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: white;
    padding: 20px 25px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.15);
}

.page-header h1 {
    margin: 0 0 8px 0;
    font-size: 22px;
    font-weight: 600;
    color: white;
}

.page-header p {
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
    font-size: 13px;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.stat-card {
    background: var(--bg-card);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px var(--shadow);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 6px var(--shadow-hover);
    border-color: #2196f3;
}

.stat-card h3 {
    margin: 0 0 5px 0;
    font-size: 11px;
    color: var(--text-secondary);
    font-weight: 500;
}

.stat-card .number {
    font-size: 20px;
    font-weight: bold;
    color: #2196f3;
}

.stat-card.draft .number { color: #ff9800; }
.stat-card.sent .number { color: #2196f3; }
.stat-card.completed .number { color: #4caf50; }

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-group {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow-hover);
    color: var(--text-white);
}

.btn-secondary {
    background: #6c757d;
    color: var(--text-white);
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    color: var(--text-white);
}

.btn-danger {
    background: #f44336;
    color: var(--text-white);
}

.btn-danger:hover {
    background: #d32f2f;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
}

.filter-section {
    background: var(--bg-card);
    padding: 15px 18px;
    border-radius: 9px;
    margin-bottom: 15px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px var(--shadow);
}

.filter-form {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 6px;
}

.status-filter {
    display: flex;
    gap: 8px;
}

.status-btn {
    padding: 6px 14px;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    background: #f0f4ff;
    color: #3b5bdb;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s;
}

.status-btn.active {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
}

.filter-group label {
    font-size: 13px;
    color: var(--text-secondary);
    white-space: nowrap;
}

.filter-group input,
.filter-group select {
    padding: 6px 10px;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    font-size: 13px;
    background: white;
    color: var(--text-primary);
    transition: border-color 0.3s ease;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: #2196f3;
    box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.1);
}

.table-container {
    background: white;
    border-radius: 9px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px var(--shadow);
    overflow: hidden;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.order-table thead {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
}

.order-table th.sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 25px;
    transition: background-color 0.2s;
}

.order-table th.sortable:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.order-table th.sortable i {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
}

.order-table th.sortable i.fa-sort-up,
.order-table th.sortable i.fa-sort-down {
    opacity: 1 !important;
    color: var(--text-white);
}

.order-table th {
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
}

.order-table th.text-center {
    text-align: center;
}

.order-table td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
}

.order-table tbody tr:hover {
    background: var(--hover-bg);
    transition: background 0.2s ease;
}

.order-table tbody tr:last-child td {
    border-bottom: none;
}

.text-center {
    text-align: center;
}

.amount-cell {
    text-align: right;
    font-family: 'Consolas', 'Monaco', monospace;
    font-weight: 500;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.status-draft {
    background: #fff3e0;
    color: #f57c00;
}

.status-sent {
    background: #e3f2fd;
    color: #1976d2;
}

.status-completed {
    background: #e8f5e9;
    color: #388e3c;
}

[data-theme="dark"] .status-draft {
    background: #5d4037;
    color: #ffb74d;
}

[data-theme="dark"] .status-sent {
    background: #1e3a5f;
    color: #64b5f6;
}

[data-theme="dark"] .status-completed {
    background: #1b5e20;
    color: #81c784;
}

.form-check-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 4px;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    padding: 6px 10px;
    border: 1px solid var(--border-color);
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 13px;
}

.pagination .page-link:hover {
    background: var(--hover-bg);
    border-color: #2196f3;
    color: #2196f3;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    border-color: transparent;
}

.empty-state {
    text-align: center;
    padding: 50px 15px;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.3;
    color: #2196f3;
}

.empty-state h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.empty-state p {
    font-size: 13px;
    color: var(--text-secondary);
}

a {
    color: #2196f3;
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: #1976d2;
}

@media (max-width: 768px) {
    .order-container {
        padding: 5px !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }

    .top-navbar {
        padding: 0.5rem 0.4rem !important;
    }

    .top-navbar .logo {
        font-size: 0.9rem !important;
    }

    .page-header {
        padding: 0.75rem 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .page-header h1 {
        font-size: 1.2rem !important;
        margin: 0 0 0.25rem 0 !important;
    }

    .page-header p {
        font-size: 0.8rem !important;
        margin: 0 !important;
    }

    .stats-container {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .stat-card {
        padding: 0.5rem 0.4rem !important;
    }

    .stat-card h3 {
        font-size: 0.7rem !important;
        margin-bottom: 0.25rem !important;
    }

    .stat-card .number {
        font-size: 1.2rem !important;
    }

    .action-bar {
        flex-direction: column !important;
        align-items: stretch !important;
        margin-bottom: 0.5rem !important;
        gap: 0.25rem !important;
    }

    .btn-group {
        flex-direction: column !important;
        width: 100% !important;
        gap: 0.25rem !important;
    }

    .btn {
        width: 100% !important;
        padding: 0.4rem 0.6rem !important;
        font-size: 0.8rem !important;
        min-height: 36px !important;
        justify-content: center !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .filter-section {
        padding: 0.5rem 0.4rem !important;
        margin-bottom: 0.5rem !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }

    .filter-form {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }

    .filter-group {
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100% !important;
        gap: 0.25rem !important;
    }

    .filter-group label {
        font-size: 0.75rem !important;
        margin-bottom: 0.25rem !important;
    }

    .filter-group input,
    .filter-group select {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.4rem 0.5rem !important;
        font-size: 0.85rem !important;
        min-height: 40px !important;
        box-sizing: border-box !important;
    }

    .status-filter {
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
        width: 100% !important;
    }

    .status-btn {
        padding: 0.3rem 0.6rem !important;
        font-size: 0.75rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    /* 테이블 모바일 카드 형식 */
    .table-container {
        overflow-x: hidden !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .order-table {
        display: none !important;
    }

    #mobile-order-cards {
        display: block !important;
        padding: 0.5rem 0.4rem !important;
    }

    .mobile-order-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.4rem !important;
        margin-bottom: 0.5rem !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        cursor: pointer;
    }

    .mobile-order-card-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 0.4rem !important;
        padding-bottom: 0.4rem !important;
        border-bottom: 1px solid #f1f5f9;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }

    .mobile-order-card-item:last-child {
        border-bottom: none;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .mobile-order-card-label {
        font-size: 0.7rem !important;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.25rem !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .mobile-order-card-value {
        font-size: 0.85rem !important;
        color: #1f2937;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100% !important;
    }

    .mobile-order-card-value .status-badge {
        font-size: 0.7rem !important;
        padding: 0.2rem 0.4rem !important;
    }

    /* 텍스트/버튼이 카드 영역을 벗어나지 않도록 */
    * {
        max-width: 100% !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }

    /* 페이지네이션 최적화 */
    .pagination {
        margin-top: 0.5rem !important;
        gap: 0.25rem !important;
        flex-wrap: wrap !important;
    }

    .pagination .page-link {
        padding: 0.3rem 0.5rem !important;
        font-size: 0.75rem !important;
        min-height: 36px !important;
    }

    /* 모달 모바일 최적화 */
    .modal-dialog {
        margin: 0.25rem !important;
        max-width: calc(100% - 0.5rem) !important;
    }

    .modal-dialog.modal-xl {
        max-width: calc(100% - 0.5rem) !important;
        margin: 0.25rem !important;
    }

    .modal-dialog.modal-fullscreen {
        margin: 0 !important;
        max-width: 100% !important;
        height: 100vh !important;
    }

    .modal-content {
        border-radius: 8px !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }

    .modal-header {
        padding: 0.5rem 0.4rem !important;
        border-bottom: 1px solid rgba(255,255,255,0.2) !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
    }

    .modal-header .modal-title {
        font-size: 1rem !important;
        margin: 0 !important;
        padding: 0 !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }

    .modal-header .btn-close {
        padding: 0.25rem !important;
        margin: 0 !important;
        font-size: 0.8rem !important;
        flex-shrink: 0 !important;
    }

    .modal-header .btn {
        padding: 0.3rem 0.5rem !important;
        font-size: 0.75rem !important;
        min-height: 36px !important;
        margin: 0.125rem !important;
        flex-shrink: 0 !important;
    }

    .modal-header > div {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
        align-items: center !important;
    }

    .modal-body {
        padding: 0.5rem 0.4rem !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        font-size: 0.9rem !important;
    }

    .modal-footer {
        padding: 0.5rem 0.4rem !important;
        border-top: 1px solid #dee2e6 !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }

    .modal-footer .btn {
        padding: 0.4rem 0.6rem !important;
        font-size: 0.8rem !important;
        min-height: 36px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    /* 거래처 수정 모달 iframe 최적화 */
    #orderEditModal .modal-body {
        height: calc(100vh - 80px) !important;
        padding: 0 !important;
    }

    #orderEditModal .modal-header {
        min-height: 50px !important;
    }

    #orderEditModal iframe {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
    }
}

/* PC에서는 모바일 카드 숨김 */
@media (min-width: 769px) {
    #mobile-order-cards {
        display: none !important;
    }
}
</style>

<body>
<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="order-container">
    <!-- 페이지 헤더 -->
    <div class="page-header">
        <h1>📋 구매발주서 관리</h1>
        <p>발주서 작성, 조회 및 관리를 수행합니다</p>
    </div>

    <!-- 통계 카드 -->
    <div class="stats-container">
        <div class="stat-card">
            <h3>전체 발주서</h3>
            <div class="number"><?php echo number_format($stats['total']); ?></div>
        </div>
        <div class="stat-card draft">
            <h3>임시저장</h3>
            <div class="number"><?php echo number_format($stats['draft_count']); ?></div>
        </div>
        <div class="stat-card sent">
            <h3>발송완료</h3>
            <div class="number"><?php echo number_format($stats['sent_count']); ?></div>
        </div>
        <div class="stat-card completed">
            <h3>완료</h3>
            <div class="number"><?php echo number_format($stats['completed_count']); ?></div>
        </div>
    </div>

    <!-- 액션 버튼 -->
    <div class="action-bar">
        <div class="btn-group">
            <button type="button" class="btn btn-danger" onclick="deleteSelected()">
                <i class="fas fa-trash"></i> 선택 삭제
            </button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" id="createOrderBtn">
                <i class="fas fa-plus"></i> 신규 발주서 작성
            </button>
        </div>
    </div>

    <!-- 필터 섹션 -->
    <div class="filter-section">
        <form method="GET" class="filter-form" id="orderFilterForm">
            <input type="hidden" name="search_status" id="searchStatusInput" value="<?php echo htmlspecialchars($search_status); ?>">
            <div class="filter-group">
                <label>발행일 (시작)</label>
                <input type="date" name="search_date_from" value="<?php echo htmlspecialchars($search_date_from); ?>">
            </div>
            <div class="filter-group">
                <label>발행일 (종료)</label>
                <input type="date" name="search_date_to" value="<?php echo htmlspecialchars($search_date_to); ?>">
            </div>
            <div class="filter-group">
                <label>검색어</label>
                <input type="text" name="search_keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="공급업체, 품목, 규격 검색" style="width: 280px;">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> 검색
            </button>
            <a href="index.php" class="btn" style="background: #999; color: white;">
                <i class="fas fa-redo"></i> 초기화
            </a>
            <div class="status-filter">
                <?php
                $status_buttons = [
                    'all' => '전체',
                    'draft' => '임시저장',
                    'sent' => '발송완료',
                    'completed' => '완료'
                ];
                foreach ($status_buttons as $value => $label):
                    $active_class = $search_status === $value ? 'active' : '';
                ?>
                <button type="button" class="status-btn <?php echo $active_class; ?>" data-status="<?php echo $value; ?>">
                    <?php echo $label; ?>
                </button>
                <?php endforeach; ?>
            </div>
        </form>
    </div>

    <!-- 발주서 목록 테이블 -->
    <div class="table-container">
        <!-- 모바일 카드 형식 -->
        <div id="mobile-order-cards" style="display: none;"></div>
        <table class="order-table">
            <thead>
                <tr>
                    <th width="3%" class="text-center">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                    </th>
                    <th width="8%" class="sortable" data-sort="issue_date">
                        발행일
                        <?php if ($sort_column === 'issue_date'): ?>
                            <i class="fas fa-sort-<?php echo strtolower($sort_direction) === 'asc' ? 'up' : 'down'; ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-sort text-muted" style="opacity: 0.3;"></i>
                        <?php endif; ?>
                    </th>
                    <th width="15%" class="sortable" data-sort="contact_name">
                        거래처명
                        <?php if ($sort_column === 'contact_name'): ?>
                            <i class="fas fa-sort-<?php echo strtolower($sort_direction) === 'asc' ? 'up' : 'down'; ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-sort text-muted" style="opacity: 0.3;"></i>
                        <?php endif; ?>
                    </th>
                    <th width="30%">품목/규격</th>
                    <th width="8%" class="sortable" data-sort="subtotal">
                        공급가액
                        <?php if ($sort_column === 'subtotal'): ?>
                            <i class="fas fa-sort-<?php echo strtolower($sort_direction) === 'asc' ? 'up' : 'down'; ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-sort text-muted" style="opacity: 0.3;"></i>
                        <?php endif; ?>
                    </th>
                    <th width="6%">세액</th>
                    <th width="10%" class="sortable" data-sort="business_registration_number">
                        사업자번호
                        <?php if ($sort_column === 'business_registration_number'): ?>
                            <i class="fas fa-sort-<?php echo strtolower($sort_direction) === 'asc' ? 'up' : 'down'; ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-sort text-muted" style="opacity: 0.3;"></i>
                        <?php endif; ?>
                    </th>
                    <th width="8%">합계금액</th>
                    <th width="8%" class="text-center sortable" data-sort="status">
                        상태
                        <?php if ($sort_column === 'status'): ?>
                            <i class="fas fa-sort-<?php echo strtolower($sort_direction) === 'asc' ? 'up' : 'down'; ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-sort text-muted" style="opacity: 0.3;"></i>
                        <?php endif; ?>
                    </th>
                    <th width="8%" class="sortable" data-sort="delivery_date">
                        납기일자
                        <?php if ($sort_column === 'delivery_date'): ?>
                            <i class="fas fa-sort-<?php echo strtolower($sort_direction) === 'asc' ? 'up' : 'down'; ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-sort text-muted" style="opacity: 0.3;"></i>
                        <?php endif; ?>
                    </th>
                    <th width="6%">비고</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="11">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>등록된 발주서가 없습니다</h3>
                            <p>신규 발주서 작성 버튼을 눌러 발주서를 등록해보세요</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr style="cursor: pointer;" onclick="openOrderModal(<?php echo $order['id']; ?>, event)" class="order-row">
                    <td class="text-center" onclick="event.stopPropagation();">
                        <input type="checkbox" class="form-check-input order-checkbox"
                               value="<?php echo $order['id']; ?>">
                    </td>
                    <td class="text-center">
                        <?php echo date('Y-m-d', strtotime($order['issue_date'])); ?>
                    </td>
                    <td>
                        <?php
                        $partner_name = $order['contact_name'] ?? $order['supplier_name'] ?? '';
                        ?>
                        <strong><?php echo htmlspecialchars($partner_name); ?></strong>
                    </td>
                    <td style="max-width: 300px; word-wrap: break-word; white-space: normal;">
                        <?php
                        // JSON 데이터에서 품목+규격 정보 추출
                        $items_display = '';
                        if (!empty($order['order_items'])) {
                            $items = json_decode($order['order_items'], true);
                            if (is_array($items)) {
                                $item_spec_pairs = [];
                                foreach ($items as $item) {
                                    $item_name = trim($item['품목'] ?? $item['item_name'] ?? '');
                                    $spec_name = trim($item['규격'] ?? $item['spec'] ?? '');
                                    
                                    // 품목과 규격이 모두 있는 경우만 조합
                                    if (!empty($item_name) || !empty($spec_name)) {
                                        if (!empty($item_name) && !empty($spec_name)) {
                                            $item_spec_pairs[] = $item_name . ' ' . $spec_name;
                                        } elseif (!empty($item_name)) {
                                            $item_spec_pairs[] = $item_name;
                                        } elseif (!empty($spec_name)) {
                                            $item_spec_pairs[] = $spec_name;
                                        }
                                    }
                                }
                                
                                if (!empty($item_spec_pairs)) {
                                    // 열의 폭을 고려하여 최대한 많은 항목 표시
                                    $max_length = 100; // 최대 표시 길이
                                    $display_items = [];
                                    $current_length = 0;
                                    
                                    foreach ($item_spec_pairs as $pair) {
                                        $pair_length = mb_strlen($pair, 'UTF-8');
                                        $separator_length = count($display_items) > 0 ? 2 : 0; // ', ' 길이
                                        
                                        if ($current_length + $separator_length + $pair_length <= $max_length) {
                                            $display_items[] = $pair;
                                            $current_length += $separator_length + $pair_length;
                                        } else {
                                            // 더 이상 추가할 수 없으면 중단
                                            break;
                                        }
                                    }
                                    
                                    $items_display = implode(', ', $display_items);
                                    
                                    // 표시하지 못한 항목이 있으면 말줄임표 추가
                                    if (count($item_spec_pairs) > count($display_items)) {
                                        $items_display .= '...';
                                    }
                                }
                            }
                        }
                        echo htmlspecialchars($items_display ?: '품목 없음');
                        ?>
                    </td>
                    <td class="amount-cell">
                        <?php echo $order['subtotal'] ? number_format($order['subtotal']) : '0'; ?>
                    </td>
                    <td class="amount-cell">
                        <?php echo $order['subtotal'] ? number_format($order['subtotal'] * 0.1) : '0'; ?>
                    </td>
                    <td class="text-center">
                        <?php echo htmlspecialchars($order['business_registration_number'] ?? '-'); ?>
                    </td>
                    <td class="amount-cell">
                        <?php echo $order['subtotal'] ? number_format($order['subtotal'] * 1.1) : '0'; ?>
                    </td>
                    <td class="text-center">
                        <?php
                        $status_labels = [
                            'draft' => '임시저장',
                            'sent' => '발송완료',
                            'completed' => '완료'
                        ];
                        $status = $order['status'] ?? 'draft';
                        $status_class = 'status-' . $status;
                        echo '<span class="status-badge ' . $status_class . '">' . ($status_labels[$status] ?? '알 수 없음') . '</span>';
                        ?>
                    </td>
                    <td class="text-center">
                        <?php
                        $delivery_date = $order['delivery_date'] ?? '';
                        // 빈 값이거나 '0000-00-00' 형식의 날짜는 공백으로 표시
                        if (empty($delivery_date) || 
                            $delivery_date === '0000-00-00' || 
                            strpos($delivery_date, '0000-00-00') !== false ||
                            strpos($delivery_date, '-0001-') !== false) {
                            echo '';
                        } else {
                            // 날짜 유효성 검사
                            $timestamp = strtotime($delivery_date);
                            if ($timestamp !== false) {
                                $year = date('Y', $timestamp);
                                // 연도가 1900년 이후인 경우만 표시
                                if ($year > 1900 && $year < 2100) {
                                    echo date('Y-m-d', $timestamp);
                                } else {
                                    echo '';
                                }
                            } else {
                                echo '';
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars(mb_substr($order['note'] ?? '', 0, 20)); ?>
                        <?php if (mb_strlen($order['note'] ?? '') > 20) echo '...'; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 페이지네이션 -->
    <?php if ($total_pages > 1): ?>
    <nav>
        <ul class="pagination">
            <?php
            $start_page = max(1, $page - 5);
            $end_page = min($total_pages, $page + 5);

            // URL 파라미터 생성
            $url_params = '';
            if ($search_keyword) $url_params .= '&search_keyword=' . urlencode($search_keyword);
            if ($search_date_from) $url_params .= '&search_date_from=' . urlencode($search_date_from);
            if ($search_date_to) $url_params .= '&search_date_to=' . urlencode($search_date_to);
            if ($search_status) $url_params .= '&search_status=' . urlencode($search_status);
            if ($date_field) $url_params .= '&date_field=' . urlencode($date_field);
            // 정렬 파라미터 추가
            if ($sort_column) $url_params .= '&sort=' . urlencode($sort_column);
            if ($sort_direction) $url_params .= '&dir=' . urlencode(strtolower($sort_direction));

            // 이전 페이지
            if ($page > 1):
            ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $url_params; ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $url_params; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $url_params; ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<!-- 발주서 상세 모달 (부트스트랩) -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white;">
                <h5 class="modal-title" id="orderDetailModalLabel">📋 발주서 상세 정보</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">불러오는 중...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> 닫기
                </button>
                <button type="button" class="btn btn-info text-white" id="modalEmailBtn">
                    <i class="fas fa-envelope"></i> Email 전송
                </button>
                <button type="button" class="btn btn-success" id="modalPdfBtn">
                    <i class="fas fa-file-pdf"></i> PDF 저장
                </button>
                <button type="button" class="btn btn-danger" id="modalDeleteBtn">
                    <i class="fas fa-trash"></i> 삭제
                </button>
                <button type="button" class="btn btn-primary" id="modalEditBtn">
                    <i class="fas fa-edit"></i> 수정하기
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 발주서 수정 모달 (부트스트랩 - iframe) -->
<div class="modal fade" id="orderEditModal" tabindex="-1" aria-labelledby="orderEditModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="modal-title" id="orderEditModalLabel">✏️ 발주서 수정</h5>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button type="button" class="btn btn-sm btn-success" onclick="iframeSaveOrder()">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" id="iframeDeleteBtn" onclick="iframeDeleteOrder()" style="display: none;">
                        <i class="fas fa-trash"></i> 삭제
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> 취소
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="height: calc(100vh - 60px); overflow: auto;">
                <iframe id="editOrderIframe" src="" style="width: 100%; min-height: 100%; border: none; display: block;"></iframe>
            </div>
        </div>
    </div>
</div>

    <!-- 이메일 전송 모달 -->
    <div class="modal fade" id="emailSendModal" tabindex="-1" aria-labelledby="emailSendModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailSendModalLabel">이메일 전송</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="emailRecipientName" class="form-label">받는 사람 (거래처)</label>
                        <input type="text" class="form-control" id="emailRecipientName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="emailRecipientAddress" class="form-label">이메일 주소</label>
                        <input type="email" class="form-control" id="emailRecipientAddress" placeholder="example@domain.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="button" class="btn btn-primary" id="btnSendEmailAction">
                        <i class="fas fa-paper-plane"></i> 전송
                    </button>
                </div>
            </div>
        </div>
    </div>

<style>
/* 부트스트랩 모달 커스터마이징 */
.modal-xl {
    max-width: 1400px;
}

/* 상세 정보 스타일 */
.detail-section {
    margin-bottom: 25px;
}

.detail-section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2196f3;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #2196f3;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 18px;
}

.detail-item {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.detail-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 5px;
    font-weight: 500;
}

.detail-value {
    font-size: 16px;
    color: #333;
    font-weight: 600;
}

.detail-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.detail-table th {
    background: #f8f9fa;
    padding: 10px 12px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    border-bottom: 2px solid #2196f3;
}

.detail-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #e0e0e0;
    font-size: 14px;
}

.detail-table tr:last-child td {
    border-bottom: none;
}

.order-row:hover {
    background: var(--hover-bg) !important;
}
</style>

<script>
(function() {
    'use strict';
    
    // 전체 선택/해제
    var selectAllElement = document.getElementById('selectAll');
    if (selectAllElement) {
        selectAllElement.addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.order-checkbox');
            var self = this;
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = self.checked;
            });
        });
    }
    
    // 개별 체크박스 변경 시 전체 선택 체크박스 상태 업데이트
    var orderCheckboxes = document.querySelectorAll('.order-checkbox');
    orderCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var allCheckboxes = document.querySelectorAll('.order-checkbox');
            var checkedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
            var selectAll = document.getElementById('selectAll');
            
            if (!selectAll) return;
            
            if (checkedCheckboxes.length === 0) {
                selectAll.indeterminate = false;
                selectAll.checked = false;
            } else if (checkedCheckboxes.length === allCheckboxes.length) {
                selectAll.indeterminate = false;
                selectAll.checked = true;
            } else {
                selectAll.indeterminate = true;
                selectAll.checked = false;
            }
        });
    });
    
    /**
     * 선택된 발주서들 일괄 삭제 함수
     */
    window.deleteSelected = function() {
        var selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
        
        if (selectedCheckboxes.length === 0) {
            alert('삭제할 발주서를 선택해주세요.');
            return;
        }
        
        var selectedIds = [];
        for (var i = 0; i < selectedCheckboxes.length; i++) {
            selectedIds.push(parseInt(selectedCheckboxes[i].value));
        }
        var count = selectedIds.length;
        
        if (!confirm('선택된 ' + count + '개의 발주서를 정말로 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) {
            return;
        }
        
        fetch('delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                ids: selectedIds,
                bulk: true
            })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                alert((data.deleted_count || count) + '개의 발주서가 성공적으로 삭제되었습니다.');
                location.reload();
            } else {
                alert('삭제 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
            }
        })
        .catch(function(error) {
            console.error('일괄 삭제 오류:', error);
            alert('삭제 중 오류가 발생했습니다: ' + error.message);
        });
    };
    
    /**
     * 모바일 카드 렌더링 함수
     */
    function renderMobileOrderCards() {
        var isMobile = window.innerWidth <= 768;
        var cardsContainer = document.getElementById('mobile-order-cards');
        var tableContainer = document.querySelector('.order-table');
        
        if (!isMobile) {
            // PC 화면: 테이블 표시, 카드 숨김
            if (tableContainer) {
                tableContainer.style.display = '';
            }
            if (cardsContainer) {
                cardsContainer.style.display = 'none';
            }
            return;
        }
        
        // 모바일 화면: 테이블 숨김, 카드 표시
        if (tableContainer) {
            tableContainer.style.display = 'none';
        }
        if (!cardsContainer) return;
        
        cardsContainer.style.display = 'block';
        cardsContainer.innerHTML = '';
        
        try {
            var rows = document.querySelectorAll('.order-table tbody tr.order-row');
            
            if (!rows || rows.length === 0) {
                var emptyRow = document.querySelector('.order-table tbody tr:not(.order-row)');
                if (emptyRow) {
                    cardsContainer.innerHTML = '<div class="text-center py-4 text-muted">등록된 발주서가 없습니다</div>';
                }
                return;
            }
            
            // 컬럼 매핑 (thead에서 가져오기)
            var headers = document.querySelectorAll('.order-table thead th');
            var columnMap = [];
            headers.forEach(function(header, index) {
                var text = header.textContent.trim();
                // 아이콘 제거
                text = text.replace(/[\s\S]*?(발행일|거래처명|품목\/규격|공급가액|세액|사업자번호|합계금액|상태|납기일자|비고)/, '$1');
                if (text && !text.match(/^[\s\S]*?$/)) {
                    columnMap.push({
                        index: index,
                        label: text || '컬럼' + (index + 1)
                    });
                }
            });
            
            rows.forEach(function(row) {
                var cells = row.querySelectorAll('td');
                if (cells.length === 0) return;
                
                var card = document.createElement('div');
                card.className = 'mobile-order-card';
                
                // 체크박스는 첫 번째 셀에 있음
                var checkbox = row.querySelector('.order-checkbox');
                var orderId = checkbox ? checkbox.value : null;
                
                // 클릭 이벤트를 위한 데이터 속성
                if (orderId) {
                    card.setAttribute('data-order-id', orderId);
                    card.addEventListener('click', function(e) {
                        if (e.target.type === 'checkbox') {
                            e.stopPropagation();
                            return;
                        }
                        if (typeof window.openOrderModal === 'function') {
                            window.openOrderModal(parseInt(orderId), e);
                        }
                    });
                }
                
                var cardHtml = '';
                
                // 각 셀을 카드 아이템으로 변환
                cells.forEach(function(cell, cellIndex) {
                    // 첫 번째 셀(체크박스)은 제외하거나 특별 처리
                    if (cellIndex === 0) {
                        // 체크박스는 카드 상단에 별도로 표시
                        var checkboxHtml = cell.innerHTML.trim();
                        if (checkboxHtml) {
                            cardHtml += '<div class="mobile-order-card-item" style="flex-direction: row; align-items: center; gap: 0.5rem;">';
                            cardHtml += checkboxHtml;
                            cardHtml += '<span class="mobile-order-card-label" style="margin: 0;">선택</span>';
                            cardHtml += '</div>';
                        }
                        return;
                    }
                    
                    var cellText = cell.textContent.trim();
                    if (!cellText || cellText === '') return;
                    
                    // 헤더 라벨 찾기 (인덱스 기반)
                    var label = '';
                    if (cellIndex < headers.length) {
                        var headerText = headers[cellIndex].textContent.trim();
                        // 정렬 아이콘 제거
                        headerText = headerText.replace(/[\s\S]*?(발행일|거래처명|품목\/규격|공급가액|세액|사업자번호|합계금액|상태|납기일자|비고)/, '$1');
                        label = headerText || '항목' + cellIndex;
                    }
                    
                    // HTML 내용이 있으면 사용 (배지 등)
                    var cellHtml = cell.innerHTML.trim();
                    var displayValue = cellHtml || cellText;
                    
                    cardHtml += '<div class="mobile-order-card-item">';
                    cardHtml += '<div class="mobile-order-card-label">' + label + '</div>';
                    cardHtml += '<div class="mobile-order-card-value">' + displayValue + '</div>';
                    cardHtml += '</div>';
                });
                
                if (cardHtml === '') {
                    cardHtml = '<div class="text-muted">데이터 없음</div>';
                }
                
                card.innerHTML = cardHtml;
                cardsContainer.appendChild(card);
            });
        } catch (error) {
            console.error('모바일 카드 렌더링 오류:', error);
            cardsContainer.innerHTML = '<div class="text-center py-4 text-danger">데이터를 불러오는 중 오류가 발생했습니다.</div>';
        }
    }
    
    // 화면 크기 변경 시 카드/테이블 전환
    function updateOrderDisplay() {
        renderMobileOrderCards();
    }
    
    // 초기 로드 및 리사이즈 이벤트
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            updateOrderDisplay();
            window.addEventListener('resize', function() {
                updateOrderDisplay();
            });
        });
    } else {
        updateOrderDisplay();
        window.addEventListener('resize', function() {
            updateOrderDisplay();
        });
    }

    /**
     * 현재 선택된 발주서 ID (전역 변수)
     */
    var currentOrderId = null;
    var currentOrderEmail = ''; // 거래처 이메일 저장 변수
    var currentOrderContactName = ''; // 거래처명 저장 변수
    var orderModal = null;
    var orderEditModal = null;
    var iframeMessageListenerRegistered = false;

    /**
     * 발주서 상세 모달 열기 (부트스트랩 방식)
     */
    window.openOrderModal = function(orderId, event) {
        if (event) {
            event.stopPropagation();
        }

        currentOrderId = orderId;

        // 부트스트랩 모달 인스턴스 생성 또는 가져오기
        var modalElement = document.getElementById('orderDetailModal');
        if (!orderModal) {
            orderModal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: true
            });
        }

        // 모달 열기
        orderModal.show();

        // 발주서 데이터 로드
        loadOrderDetail(orderId);
    };

    /**
     * 발주서 상세 모달 닫기 (부트스트랩 방식)
     */
    window.closeOrderModal = function() {
        console.log('closeOrderModal 호출됨');

        if (orderModal) {
            orderModal.hide();
            currentOrderId = null;
            console.log('모달 닫힘');
        }
    };
    
    function ensureIframeMessageListener() {
        if (iframeMessageListenerRegistered) {
            return;
        }

        window.addEventListener('message', function(event) {
            var data = event.data;

            if (!data || typeof data !== 'object' || data.scope !== 'orderModule') {
                return;
            }

            if (data.type === 'orderSaved' || data.type === 'orderDeleted') {
                if (orderEditModal) {
                    orderEditModal.hide();
                }

                var iframe = document.getElementById('editOrderIframe');
                if (iframe) {
                    iframe.src = '';
                }

                location.reload();
            } else if (data.type === 'orderEditCanceled') {
                if (orderEditModal) {
                    orderEditModal.hide();
                }

                var cancelIframe = document.getElementById('editOrderIframe');
                if (cancelIframe) {
                    cancelIframe.src = '';
                }
            }
        });

        iframeMessageListenerRegistered = true;
    }

    function toggleIframeDeleteButton(shouldShow) {
        var deleteBtn = document.getElementById('iframeDeleteBtn');
        if (!deleteBtn) return;
        deleteBtn.style.display = shouldShow ? 'inline-flex' : 'none';
    }

    function showOrderEditModal(orderId) {
        var editModalElement = document.getElementById('orderEditModal');
        if (!editModalElement) {
            console.error('orderEditModal 요소를 찾을 수 없습니다.');
            return;
        }

        if (!orderEditModal) {
            orderEditModal = new bootstrap.Modal(editModalElement, {
                backdrop: 'static',
                keyboard: true
            });
        }

        var iframe = document.getElementById('editOrderIframe');
        if (!iframe) {
            console.error('editOrderIframe 요소를 찾을 수 없습니다.');
            return;
        }

        var iframeUrl = 'write_form.php?iframe=1';
        if (orderId) {
            iframeUrl += '&id=' + encodeURIComponent(orderId);
        }

        iframe.src = iframeUrl;
        toggleIframeDeleteButton(Boolean(orderId));
        ensureIframeMessageListener();
        orderEditModal.show();
    }

    window.openCreateOrderModal = function() {
        currentOrderId = null;
        showOrderEditModal(null);
    };

    window.editOrder = function() {
        if (!currentOrderId) {
            alert('발주서 ID를 찾을 수 없습니다.');
            return;
        }

        if (orderModal) {
            orderModal.hide();
        }

        showOrderEditModal(currentOrderId);
    };

    var createOrderBtn = document.getElementById('createOrderBtn');
    if (createOrderBtn) {
        createOrderBtn.addEventListener('click', function() {
            openCreateOrderModal();
        });
    }

    // 테이블 헤더 정렬 기능
    var sortableHeaders = document.querySelectorAll('.order-table th.sortable');
    sortableHeaders.forEach(function(header) {
        header.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var sortColumn = this.getAttribute('data-sort');
            if (!sortColumn) return;
            
            // 현재 정렬 상태 확인
            var currentSort = '<?php echo $sort_column; ?>';
            var currentDir = '<?php echo strtolower($sort_direction); ?>';
            
            // 같은 컬럼이면 방향 토글, 다른 컬럼이면 오름차순으로 시작
            var newDir = 'asc';
            if (currentSort === sortColumn && currentDir === 'asc') {
                newDir = 'desc';
            }
            
            // URL 파라미터 구성
            var url = new URL(window.location.href);
            url.searchParams.set('sort', sortColumn);
            url.searchParams.set('dir', newDir);
            
            // 페이지는 1로 리셋
            url.searchParams.set('page', '1');
            
            // 페이지 이동
            window.location.href = url.toString();
        });
    });

    // 상태 필터 버튼
    var statusButtons = document.querySelectorAll('.status-btn');
    var statusInput = document.getElementById('searchStatusInput');
    var filterForm = document.getElementById('orderFilterForm');
    if (statusButtons.length && statusInput && filterForm) {
        statusButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var statusValue = button.getAttribute('data-status') || 'all';
                statusInput.value = statusValue;
                filterForm.submit();
            });
        });
    }

    // 삭제하기 버튼 이벤트 리스너
    var modalDeleteBtn = document.getElementById('modalDeleteBtn');
    if (modalDeleteBtn) {
        modalDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('삭제 버튼 클릭');
            deleteOrderFromModal();
        });
    }

    /**
     * 발주서 상세 모달에서 삭제 처리
     */
    function deleteOrderFromModal() {
        if (!currentOrderId) {
            alert('삭제할 발주서가 선택되지 않았습니다.');
            return;
        }

        // 삭제 확인
        if (!confirm('정말로 이 발주서를 삭제하시겠습니까?\n삭제된 발주서는 복구할 수 없습니다.')) {
            return;
        }

        // 삭제 요청
        fetch('delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: currentOrderId,
                bulk: false
            })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                alert(data.message || '발주서가 삭제되었습니다.');
                
                // 모달 닫기
                if (orderModal) {
                    orderModal.hide();
                }
                
                // 목록 새로고침
                location.reload();
            } else {
                alert('삭제 실패: ' + (data.message || '알 수 없는 오류가 발생했습니다.'));
            }
        })
        .catch(function(error) {
            console.error('삭제 오류:', error);
            alert('삭제 중 오류가 발생했습니다.');
        });
    }

    // 수정하기 버튼 이벤트 리스너
    var modalEditBtn = document.getElementById('modalEditBtn');
    if (modalEditBtn) {
        modalEditBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('수정하기 버튼 클릭');
            editOrder();
        });
    }

    // PDF 저장 버튼 이벤트 리스너
    var modalPdfBtn = document.getElementById('modalPdfBtn');
    if (modalPdfBtn) {
        modalPdfBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('PDF 저장 버튼 클릭');
            
            if (!currentOrderId) {
                alert('발주서 ID를 찾을 수 없습니다.');
                return;
            }
            
            // PDF 다운로드
            location.href = '../pdf/order_send_pdf.php?id=' + encodeURIComponent(currentOrderId) + '&download=1';
        });
    }

    // 이메일 전송 버튼 이벤트 리스너 (모달 열기)
    var modalEmailBtn = document.getElementById('modalEmailBtn');
    if (modalEmailBtn) {
        modalEmailBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!currentOrderId) {
                alert('발주서 ID를 찾을 수 없습니다.');
                return;
            }

            // 모달 필드 채우기
            document.getElementById('emailRecipientName').value = currentOrderContactName;
            document.getElementById('emailRecipientAddress').value = currentOrderEmail;
            
            // 모달 열기
            var emailModal = new bootstrap.Modal(document.getElementById('emailSendModal'));
            emailModal.show();
        });
    }

    // 모달 내 전송 버튼 이벤트 리스너
    var btnSendEmailAction = document.getElementById('btnSendEmailAction');
    if (btnSendEmailAction) {
        btnSendEmailAction.addEventListener('click', function() {
            var emailInput = document.getElementById('emailRecipientAddress');
            var email = emailInput.value.trim();
            
            if (!email) {
                alert('이메일 주소를 입력해주세요.');
                emailInput.focus();
                return;
            }
            
            // 이메일 형식 검사
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('유효하지 않은 이메일 주소입니다.');
                emailInput.focus();
                return;
            }
            
            // 전송 중 표시
            var originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 전송 중...';
            this.disabled = true;
            var btn = this;
            
            // AJAX 요청
            var formData = new FormData();
            formData.append('order_id', currentOrderId);
            formData.append('email', email);
            
            fetch('send_email.php', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    alert(data.message || '이메일이 성공적으로 전송되었습니다.');
                    // 모달 닫기
                    var modalEl = document.getElementById('emailSendModal');
                    var modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                } else {
                    alert('전송 실패: ' + (data.message || '알 수 없는 오류'));
                }
            })
            .catch(function(error) {
                console.error('이메일 전송 오류:', error);
                alert('전송 중 오류가 발생했습니다.');
            })
            .finally(function() {
                // 버튼 상태 복구
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }

    // 수정 모달 닫힐 때 iframe 초기화
    var editModalElement = document.getElementById('orderEditModal');
    if (editModalElement) {
        editModalElement.addEventListener('hidden.bs.modal', function() {
            document.getElementById('editOrderIframe').src = '';
        });

        // iframe 로드 완료 시 높이 자동 조정
        editModalElement.addEventListener('shown.bs.modal', function() {
            var iframe = document.getElementById('editOrderIframe');
            iframe.onload = function() {
                try {
                    var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    var height = iframeDoc.body.scrollHeight;
                    iframe.style.height = height + 'px';
                } catch(e) {
                    console.log('iframe 높이 조정 실패:', e);
                }
            };
        });
    }

    /**
     * iframe 내부 함수 호출 (모달 헤더 버튼용)
     */
    window.iframeAddRow = function() {
        var iframe = document.getElementById('editOrderIframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.addRow();
        }
    };

    window.iframeSaveOrder = function() {
        var iframe = document.getElementById('editOrderIframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.saveOrder();
        }
    };

    window.iframeDeleteOrder = function() {
        var iframe = document.getElementById('editOrderIframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.deleteOrder();
        }
    };
    
    function toNumber(value) {
        if (value === null || value === undefined) {
            return 0;
        }

        if (typeof value === 'number') {
            return isNaN(value) ? 0 : value;
        }

        if (typeof value === 'string') {
            var cleaned = value.replace(/[^\d.-]/g, '');
            if (cleaned === '' || cleaned === '-' || cleaned === '.') {
                return 0;
            }

            var parsed = parseFloat(cleaned);
            return isNaN(parsed) ? 0 : parsed;
        }

        var numberValue = Number(value);
        return isNaN(numberValue) ? 0 : numberValue;
    }

    function formatNumber(value) {
        return toNumber(value).toLocaleString('ko-KR');
    }

    function normalizeOrderItems(rawItems) {
        var result = {
            rows: [],
            totals: {
                supply: 0,
                tax: 0,
                total: 0
            }
        };

        var items = [];
        if (Array.isArray(rawItems)) {
            items = rawItems;
        } else if (typeof rawItems === 'string' && rawItems.trim() !== '') {
            try {
                items = JSON.parse(rawItems);
            } catch (error) {
                console.warn('품목 JSON 파싱 실패:', error);
            }
        }

        if (!Array.isArray(items)) {
            return result;
        }

        items.forEach(function(item, index) {
            if (!item) return;

            var quantity = toNumber(item['수량'] ?? item.quantity);
            var unit = (item['단위'] ?? item.unit ?? 'EA') || 'EA';
            var unitPrice = toNumber(item['단가'] ?? item.unit_price);
            var supply = toNumber(item['공급가액'] ?? item.amount ?? 0);

            if (!supply && quantity && unitPrice) {
                supply = Math.round(quantity * unitPrice);
            }

            var tax = toNumber(item['세액'] ?? item.tax_amount ?? 0);
            if (!tax && supply) {
                tax = Math.round(supply * 0.1);
            }

            var total = toNumber(item['금액'] ?? item.total_amount ?? 0);
            if (!total) {
                total = supply + tax;
            }

            var normalized = {
                index: index + 1,
                itemName: item['품목'] || item.item_name || '-',
                spec: item['규격'] || item.spec || '-',
                quantity: quantity,
                unit: unit || 'EA',
                unitPrice: unitPrice,
                taxAmount: tax,
                totalAmount: total
            };

            result.rows.push({
                index: normalized.index,
                itemName: normalized.itemName,
                spec: normalized.spec,
                quantity: normalized.quantity,
                unit: normalized.unit,
                unitPrice: normalized.unitPrice,
                taxAmount: normalized.taxAmount,
                totalAmount: normalized.totalAmount,
                supplyAmount: supply
            });

            result.totals.supply += supply;
            result.totals.tax += tax;
            result.totals.total += total;
        });

        return result;
    }

    /**
     * AJAX로 발주서 상세 정보 로드
     */
    function loadOrderDetail(orderId) {
        var contentDiv = document.getElementById('orderDetailContent');
        contentDiv.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin" style="font-size: 38px; color: #2196f3;"></i><p>불러오는 중...</p></div>';
        
        fetch('get_order_detail.php?id=' + orderId)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    displayOrderDetail(data.order);
                } else {
                    contentDiv.innerHTML = '<div class="loading-spinner"><i class="fas fa-exclamation-circle" style="font-size: 38px; color: #f44336;"></i><p>데이터를 불러올 수 없습니다.</p></div>';
                }
            })
            .catch(function(error) {
                console.error('발주서 로드 오류:', error);
                contentDiv.innerHTML = '<div class="loading-spinner"><i class="fas fa-exclamation-circle" style="font-size: 38px; color: #f44336;"></i><p>오류가 발생했습니다.</p></div>';
            });
    }
    
    /**
     * 발주서 상세 정보 표시
     */
    function displayOrderDetail(order) {
        var contentDiv = document.getElementById('orderDetailContent');
        var normalizedItems = normalizeOrderItems(order.order_items);
        var hasItems = normalizedItems.rows.length > 0;
        var subtotal = hasItems ? normalizedItems.totals.supply : toNumber(order.subtotal);
        var tax = hasItems ? normalizedItems.totals.tax : Math.round(subtotal * 0.1);
        var total = hasItems ? normalizedItems.totals.total : subtotal + tax;
        
        // 이메일 저장
        currentOrderEmail = order.email || '';
        currentOrderContactName = order.contact_name || order.supplier_name || '';

        var statusLabels = {
            'draft': '임시저장',
            'sent': '발송완료',
            'completed': '완료'
        };
        
        var html = '';
        
        // 기본 정보
        html += '<div class="detail-section">';
        html += '<div class="detail-section-title">📋 기본 정보</div>';
        html += '<div class="detail-grid">';
        html += '<div class="detail-item"><div class="detail-label">발행일</div><div class="detail-value">' + (order.issue_date || '-') + '</div></div>';
        var partnerName = order.contact_name || order.supplier_name || '-';
        html += '<div class="detail-item"><div class="detail-label">거래처</div><div class="detail-value">' + partnerName + '</div></div>';
        html += '<div class="detail-item"><div class="detail-label">상태</div><div class="detail-value">' + (statusLabels[order.status] || '알 수 없음') + '</div></div>';
        // 납기일자 처리 (빈 값이나 유효하지 않은 날짜는 공백)
        var deliveryDate = '';
        if (order.delivery_date && 
            order.delivery_date !== '0000-00-00' && 
            order.delivery_date.indexOf('0000-00-00') === -1 &&
            order.delivery_date.indexOf('-0001-') === -1) {
            var dateObj = new Date(order.delivery_date);
            if (dateObj && !isNaN(dateObj.getTime()) && dateObj.getFullYear() > 1900) {
                deliveryDate = order.delivery_date.substring(0, 10); // YYYY-MM-DD 형식
            }
        }
        html += '<div class="detail-item"><div class="detail-label">납기일자</div><div class="detail-value">' + (deliveryDate || '') + '</div></div>';
        html += '</div>';
        html += '</div>';
        
        // 금액 정보
        html += '<div class="detail-section">';
        html += '<div class="detail-section-title">💰 금액 정보</div>';
        html += '<div class="detail-grid">';
        html += '<div class="detail-item"><div class="detail-label">공급가액</div><div class="detail-value">' + formatNumber(subtotal) + '원</div></div>';
        html += '<div class="detail-item"><div class="detail-label">세액 (10%)</div><div class="detail-value">' + formatNumber(tax) + '원</div></div>';
        html += '<div class="detail-item"><div class="detail-label">합계금액</div><div class="detail-value" style="color: #2196f3; font-size: 18px;">' + formatNumber(total) + '원</div></div>';
        html += '</div>';
        html += '</div>';
        
        // 품목 정보
        if (hasItems) {
                html += '<div class="detail-section">';
                html += '<div class="detail-section-title">📦 품목 내역</div>';
                html += '<table class="detail-table">';
                html += '<thead><tr><th width="5%">번호</th><th width="23%">품목</th><th width="18%">규격</th><th width="10%">수량</th><th width="10%">단위</th><th width="12%">단가</th><th width="12%">세액</th><th width="15%">금액</th></tr></thead>';
                html += '<tbody>';
                
                normalizedItems.rows.forEach(function(row) {
                    html += '<tr>';
                    html += '<td class="text-center">' + row.index + '</td>';
                    html += '<td>' + row.itemName + '</td>';
                    html += '<td>' + row.spec + '</td>';
                    html += '<td class="text-center">' + formatNumber(row.quantity) + '</td>';
                    html += '<td class="text-center">' + (row.unit || 'EA') + '</td>';
                    html += '<td style="text-align: right;">' + formatNumber(row.unitPrice) + '</td>';
                    html += '<td style="text-align: right;">' + formatNumber(row.taxAmount) + '</td>';
                    html += '<td style="text-align: right;">' + formatNumber(row.totalAmount) + '</td>';
                    html += '</tr>';
                });
                
                html += '</tbody>';
                html += '</table>';
                html += '</div>';
        }
        
        // 비고
        if (order.note) {
            html += '<div class="detail-section">';
            html += '<div class="detail-section-title">📝 비고</div>';
            html += '<div class="detail-item"><div class="detail-value">' + (order.note || '-') + '</div></div>';
            html += '</div>';
        }
        
        contentDiv.innerHTML = html;
    }
    
})();
</script>

</body>
</html>
