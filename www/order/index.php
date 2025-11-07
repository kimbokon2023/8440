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
    $_SESSION["url"] = "{$base_url}/order/index.php";
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
    $where_conditions[] = "issue_date >= :search_date_from";
    $params[':search_date_from'] = $search_date_from;
}

if ($search_date_to) {
    $where_conditions[] = "issue_date <= :search_date_to";
    $params[':search_date_to'] = $search_date_to;
}

$where_clause = implode(' AND ', $where_conditions);

// 전체 레코드 수 조회
$count_sql = "SELECT COUNT(*) FROM `order` WHERE $where_clause";
$count_stmt = $pdo->prepare($count_sql);

foreach ($params as $key => $value) {
    $count_stmt->bindValue($key, $value);
}

$count_stmt->execute();
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// 데이터 조회
$sql = "SELECT * FROM `order` WHERE $where_clause ORDER BY issue_date DESC, created_at DESC LIMIT :offset, :per_page";
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
FROM `order` WHERE is_deleted = 0";
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
        padding: 10px;
    }

    .top-navbar {
        padding: 10px 15px;
    }

    .top-navbar .logo {
        font-size: 16px;
    }

    .page-header {
        padding: 20px;
    }

    .page-header h1 {
        font-size: 20px;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-group {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
    }

    .table-container {
        overflow-x: auto;
    }

    .order-table {
        min-width: 800px;
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
            <a href="write_form.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> 신규 발주서 작성
            </a>
        </div>
    </div>

    <!-- 필터 섹션 -->
    <div class="filter-section">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>검색어</label>
                <input type="text" name="search_keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="공급업체, 품목, 규격 검색" style="width: 280px;">
            </div>
            <div class="filter-group">
                <label>발행일 (시작)</label>
                <input type="date" name="search_date_from" value="<?php echo htmlspecialchars($search_date_from); ?>">
            </div>
            <div class="filter-group">
                <label>발행일 (종료)</label>
                <input type="date" name="search_date_to" value="<?php echo htmlspecialchars($search_date_to); ?>">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> 검색
            </button>
            <a href="index.php" class="btn" style="background: #999; color: white;">
                <i class="fas fa-redo"></i> 초기화
            </a>
        </form>
    </div>

    <!-- 발주서 목록 테이블 -->
    <div class="table-container">
        <table class="order-table">
            <thead>
                <tr>
                    <th width="3%" class="text-center">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                    </th>
                    <th width="8%">발행일</th>
                    <th width="15%">공급업체명</th>
                    <th width="30%">품목/규격</th>
                    <th width="8%">공급가액</th>
                    <th width="6%">세액</th>
                    <th width="8%">합계금액</th>
                    <th width="8%" class="text-center">상태</th>
                    <th width="8%">납기일자</th>
                    <th width="6%">비고</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="10">
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
                        <strong><?php echo htmlspecialchars($order['supplier_name']); ?></strong>
                    </td>
                    <td>
                        <?php
                        // JSON 데이터에서 품목 정보 추출
                        $items_display = '';
                        if (!empty($order['order_items'])) {
                            $items = json_decode($order['order_items'], true);
                            if (is_array($items)) {
                                $item_names = array_filter(array_column($items, '품목'));
                                $spec_names = array_filter(array_column($items, '규격'));
                                $combined = array_merge($item_names, $spec_names);
                                $items_display = implode(', ', array_slice($combined, 0, 3));
                                if (count($combined) > 3) $items_display .= '...';
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
                        <?php echo $order['delivery_date'] ? date('Y-m-d', strtotime($order['delivery_date'])) : '-'; ?>
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
                    <button type="button" class="btn btn-sm btn-light" onclick="iframeAddRow()">
                        <i class="fas fa-plus"></i> 행 추가
                    </button>
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
     * 현재 선택된 발주서 ID (전역 변수)
     */
    var currentOrderId = null;
    var orderModal = null;

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
    
    /**
     * 발주서 수정 모달 열기 (iframe 방식)
     */
    var orderEditModal = null;

    window.editOrder = function() {
        console.log('수정하기 클릭, currentOrderId:', currentOrderId);

        if (!currentOrderId) {
            alert('발주서 ID를 찾을 수 없습니다.');
            return;
        }

        // 상세 모달 닫기
        if (orderModal) {
            orderModal.hide();
        }

        // 수정 모달 인스턴스 생성 또는 가져오기
        var editModalElement = document.getElementById('orderEditModal');
        if (!orderEditModal) {
            orderEditModal = new bootstrap.Modal(editModalElement, {
                backdrop: 'static',
                keyboard: true
            });
        }

        // iframe에 write_form.php 로드 (iframe 모드 파라미터 추가)
        var iframe = document.getElementById('editOrderIframe');
        iframe.src = 'write_form.php?id=' + currentOrderId + '&iframe=1';

        // 수정 모달 열기
        orderEditModal.show();

        // iframe 로드 완료 이벤트 (저장 후 목록 새로고침)
        iframe.onload = function() {
            console.log('write_form.php 로드 완료');

            // iframe 내부에서 저장 완료 메시지 수신 대기
            window.addEventListener('message', function(event) {
                if (event.data === 'orderSaved') {
                    console.log('발주서 저장 완료, 목록 새로고침');
                    orderEditModal.hide();
                    location.reload();
                }
            });
        };
    };

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
        html += '<div class="detail-item"><div class="detail-label">공급업체</div><div class="detail-value">' + (order.supplier_name || '-') + '</div></div>';
        html += '<div class="detail-item"><div class="detail-label">상태</div><div class="detail-value">' + (statusLabels[order.status] || '알 수 없음') + '</div></div>';
        html += '<div class="detail-item"><div class="detail-label">납기일자</div><div class="detail-value">' + (order.delivery_date || '-') + '</div></div>';
        html += '</div>';
        html += '</div>';
        
        // 금액 정보
        var subtotal = parseInt(order.subtotal) || 0;
        var tax = Math.round(subtotal * 0.1);
        var total = subtotal + tax;
        
        html += '<div class="detail-section">';
        html += '<div class="detail-section-title">💰 금액 정보</div>';
        html += '<div class="detail-grid">';
        html += '<div class="detail-item"><div class="detail-label">공급가액</div><div class="detail-value">' + subtotal.toLocaleString('ko-KR') + '원</div></div>';
        html += '<div class="detail-item"><div class="detail-label">세액 (10%)</div><div class="detail-value">' + tax.toLocaleString('ko-KR') + '원</div></div>';
        html += '<div class="detail-item"><div class="detail-label">합계금액</div><div class="detail-value" style="color: #2196f3; font-size: 18px;">' + total.toLocaleString('ko-KR') + '원</div></div>';
        html += '</div>';
        html += '</div>';
        
        // 품목 정보
        if (order.order_items) {
            var items;
            try {
                items = typeof order.order_items === 'string' ? JSON.parse(order.order_items) : order.order_items;
            } catch (e) {
                items = [];
            }
            
            if (Array.isArray(items) && items.length > 0) {
                html += '<div class="detail-section">';
                html += '<div class="detail-section-title">📦 품목 내역</div>';
                html += '<table class="detail-table">';
                html += '<thead><tr><th width="5%">번호</th><th width="25%">품목</th><th width="20%">규격</th><th width="10%">수량</th><th width="10%">단위</th><th width="15%">단가</th><th width="15%">금액</th></tr></thead>';
                html += '<tbody>';
                
                items.forEach(function(item, index) {
                    var itemName = item['품목'] || item.item_name || '-';
                    var spec = item['규격'] || item.spec || '-';
                    var quantity = item['수량'] || item.quantity || 0;
                    var unit = item['단위'] || item.unit || 'EA';
                    var unitPrice = parseInt(item['단가'] || item.unit_price || 0);
                    var amount = parseInt(item['금액'] || item.amount || (quantity * unitPrice));
                    
                    html += '<tr>';
                    html += '<td class="text-center">' + (index + 1) + '</td>';
                    html += '<td>' + itemName + '</td>';
                    html += '<td>' + spec + '</td>';
                    html += '<td class="text-center">' + quantity + '</td>';
                    html += '<td class="text-center">' + unit + '</td>';
                    html += '<td style="text-align: right;">' + unitPrice.toLocaleString('ko-KR') + '</td>';
                    html += '<td style="text-align: right;">' + amount.toLocaleString('ko-KR') + '</td>';
                    html += '</tr>';
                });
                
                html += '</tbody>';
                html += '</table>';
                html += '</div>';
            }
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
