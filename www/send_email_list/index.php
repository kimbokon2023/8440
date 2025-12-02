<?php
/**
 * 이메일 발송 내역 리스트 페이지
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
    $_SESSION["url"] = "{$base_url}/send_email_list/index.php";
    sleep(1);
    header("Location: {$base_url}/login/logout.php");
    exit;
}

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

// 정렬 조건
$sort_column = $_GET['sort'] ?? 'sent_at';
$sort_direction = $_GET['dir'] ?? 'desc';

// 허용된 정렬 컬럼
$allowed_sort_columns = [
    'sent_at' => '발송일시',
    'recipient_email' => '수신자',
    'subject' => '제목',
    'status' => '상태'
];

if (!array_key_exists($sort_column, $allowed_sort_columns)) {
    $sort_column = 'sent_at';
}

if (!in_array(strtolower($sort_direction), ['asc', 'desc'])) {
    $sort_direction = 'desc';
}
$sort_direction = strtoupper($sort_direction);

// WHERE 조건 구성
$where_conditions = ["1=1"];
$params = [];

if ($search_keyword) {
    $where_conditions[] = "(recipient_email LIKE :search1 OR subject LIKE :search2 OR sender_email LIKE :search3)";
    $searchTerm = '%' . $search_keyword . '%';
    $params[':search1'] = $searchTerm;
    $params[':search2'] = $searchTerm;
    $params[':search3'] = $searchTerm;
}

if ($search_date_from) {
    $where_conditions[] = "DATE(sent_at) >= :search_date_from";
    $params[':search_date_from'] = $search_date_from;
}

if ($search_date_to) {
    $where_conditions[] = "DATE(sent_at) <= :search_date_to";
    $params[':search_date_to'] = $search_date_to;
}

if ($search_status !== 'all') {
    $where_conditions[] = "status = :search_status";
    $params[':search_status'] = $search_status;
}

$where_clause = implode(' AND ', $where_conditions);

// 전체 레코드 수 조회
// sent_email_logs 테이블이 존재하는지 먼저 확인 (없으면 0)
try {
    $checkTable = $pdo->query("SHOW TABLES LIKE 'sent_email_logs'");
    if ($checkTable->rowCount() == 0) {
        $total_records = 0;
        $logs = [];
    } else {
        $count_sql = "SELECT COUNT(*) FROM `sent_email_logs` WHERE $where_clause";
        $count_stmt = $pdo->prepare($count_sql);
        foreach ($params as $key => $value) {
            $count_stmt->bindValue($key, $value);
        }
        $count_stmt->execute();
        $total_records = $count_stmt->fetchColumn();

        // 데이터 조회
        $sql = "SELECT * FROM `sent_email_logs` WHERE $where_clause ORDER BY $sort_column $sort_direction LIMIT :offset, :per_page";
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll();
    }
} catch (Exception $e) {
    $total_records = 0;
    $logs = [];
    error_log("Email Log Error: " . $e->getMessage());
}

$total_pages = ceil($total_records / $per_page);
?>

<title>이메일 발송 내역 - 미래정공</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
:root {
    /* 라이트 모드 색상 - 파란색 계열 (orders/index.php와 동일) */
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

.order-table th.sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 25px;
}

.order-table th.sortable:hover {
    background-color: rgba(255, 255, 255, 0.1);
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

.status-badge {
    padding: 4px 10px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.status-success {
    background: #e8f5e9;
    color: #388e3c;
}

.status-fail {
    background: #ffebee;
    color: #c62828;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 4px;
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

@media (max-width: 768px) {
    .order-container {
        padding: 5px;
    }
    
    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .table-container {
        overflow-x: auto;
    }
}
</style>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="order-container">
    <div class="page-header">
        <h1><i class="fas fa-envelope-open-text"></i> 이메일 발송 내역</h1>
        <p>시스템에서 발송된 이메일 로그를 확인합니다.</p>
    </div>

    <!-- 검색 필터 -->
    <div class="filter-section">
        <form method="GET" action="index.php" class="filter-form">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> 검색어</label>
                <input type="text" name="search_keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="수신자, 제목 검색">
            </div>
            
            <div class="filter-group">
                <label><i class="far fa-calendar-alt"></i> 기간</label>
                <div style="display: flex; gap: 5px; align-items: center;">
                    <input type="date" name="search_date_from" value="<?php echo htmlspecialchars($search_date_from); ?>">
                    <span>~</span>
                    <input type="date" name="search_date_to" value="<?php echo htmlspecialchars($search_date_to); ?>">
                </div>
            </div>
            
            <div class="filter-group">
                <label><i class="fas fa-filter"></i> 상태</label>
                <select name="search_status">
                    <option value="all" <?php echo $search_status === 'all' ? 'selected' : ''; ?>>전체</option>
                    <option value="success" <?php echo $search_status === 'success' ? 'selected' : ''; ?>>성공</option>
                    <option value="fail" <?php echo $search_status === 'fail' ? 'selected' : ''; ?>>실패</option>
                </select>
            </div>
            
            <div class="filter-group" style="margin-left: auto;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> 조회
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> 초기화
                </a>
            </div>
        </form>
    </div>

    <!-- 목록 테이블 -->
    <div class="table-container">
        <table class="order-table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%" class="sortable" onclick="location.href='?sort=sent_at&dir=<?php echo ($sort_column == 'sent_at' && $sort_direction == 'DESC') ? 'asc' : 'desc'; ?>&<?php echo http_build_query(array_merge($_GET, ['sort' => null, 'dir' => null])); ?>'">
                        발송일시
                        <?php if ($sort_column == 'sent_at'): ?>
                            <i class="fas fa-sort-<?php echo $sort_direction == 'ASC' ? 'up' : 'down'; ?>"></i>
                        <?php endif; ?>
                    </th>
                    <th width="10%" class="text-center">발주서ID</th>
                    <th width="20%">수신자</th>
                    <th width="35%">제목</th>
                    <th width="10%" class="text-center">상태</th>
                    <th width="5%" class="text-center">상세</th>
                    <th width="5%" class="text-center">삭제</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_records > 0): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-center"><?php echo $log['id']; ?></td>
                            <td><?php echo $log['sent_at']; ?></td>
                            <td class="text-center">
                                <?php if ($log['order_id']): ?>
                                    <a href="../orders/index.php?search_keyword=<?php echo $log['order_id']; ?>" target="_blank">
                                        #<?php echo $log['order_id']; ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($log['recipient_email']); ?></td>
                            <td><?php echo htmlspecialchars($log['subject']); ?></td>
                            <td class="text-center">
                                <?php if ($log['status'] === 'success'): ?>
                                    <span class="status-badge status-success">성공</span>
                                <?php else: ?>
                                    <span class="status-badge status-fail" title="<?php echo htmlspecialchars($log['error_message']); ?>">실패</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($log['error_message']): ?>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="alert('<?php echo htmlspecialchars(addslashes($log['error_message'])); ?>')">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteLog(<?php echo $log['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="far fa-folder-open"></i>
                                <h3>발송된 이메일 내역이 없습니다.</h3>
                                <p>발주서 관리에서 이메일을 발송하면 이곳에 기록됩니다.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 페이지네이션 -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php
        // 페이지네이션 범위 설정
        $page_range = 5;
        $start_page = max(1, $page - floor($page_range / 2));
        $end_page = min($total_pages, $start_page + $page_range - 1);
        
        // 시작 페이지 조정
        if ($end_page - $start_page + 1 < $page_range) {
            $start_page = max(1, $end_page - $page_range + 1);
        }
        
        // 쿼리 스트링 유지
        $query_params = $_GET;
        ?>
        
        <?php if ($page > 1): ?>
            <div class="page-item">
                <?php $query_params['page'] = 1; ?>
                <a class="page-link" href="?<?php echo http_build_query($query_params); ?>">&laquo;</a>
            </div>
            <div class="page-item">
                <?php $query_params['page'] = $page - 1; ?>
                <a class="page-link" href="?<?php echo http_build_query($query_params); ?>">&lt;</a>
            </div>
        <?php endif; ?>
        
        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
            <div class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <?php $query_params['page'] = $i; ?>
                <a class="page-link" href="?<?php echo http_build_query($query_params); ?>"><?php echo $i; ?></a>
            </div>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <div class="page-item">
                <?php $query_params['page'] = $page + 1; ?>
                <a class="page-link" href="?<?php echo http_build_query($query_params); ?>">&gt;</a>
            </div>
            <div class="page-item">
                <?php $query_params['page'] = $total_pages; ?>
                <a class="page-link" href="?<?php echo http_build_query($query_params); ?>">&raquo;</a>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<script>
function deleteLog(id) {
    if (!confirm('정말로 이 로그를 삭제하시겠습니까?')) {
        return;
    }

    fetch('delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('삭제 실패: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('오류가 발생했습니다.');
    });
}
</script>

</body>
</html>
