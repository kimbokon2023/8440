<?php
require_once __DIR__ . '/../bootstrap.php';

// 다온텍 로그인 체크
require_once __DIR__ . '/login/check_login.php';

// 선택적: load_header 사용 안함 (독립적인 시스템으로 운영)
// require_once(includePath('load_header.php'));
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>다온텍 발주 시스템</title>

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="../favicon.ico">
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
<link rel="apple-touch-icon" type="image/x-icon" href="../favicon.ico">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
:root {
    /* 라이트 모드 색상 */
    --bg-primary: #f5f7fa;
    --bg-secondary: #ffffff;
    --bg-gradient-start: #667eea;
    --bg-gradient-end: #764ba2;
    --text-primary: #333333;
    --text-secondary: #666666;
    --text-white: #ffffff;
    --border-color: #ddd;
    --border-light: #f0f0f0;
    --shadow: rgba(0,0,0,0.1);
    --shadow-hover: rgba(102, 126, 234, 0.3);
    --hover-bg: #f8f9ff;
}

[data-theme="dark"] {
    /* 다크 모드 색상 */
    --bg-primary: #1a1a2e;
    --bg-secondary: #16213e;
    --bg-gradient-start: #4a5568;
    --bg-gradient-end: #2d3748;
    --text-primary: #e2e8f0;
    --text-secondary: #cbd5e0;
    --text-white: #ffffff;
    --border-color: #4a5568;
    --border-light: #2d3748;
    --shadow: rgba(0,0,0,0.3);
    --shadow-hover: rgba(74, 85, 104, 0.5);
    --hover-bg: #2d3748;
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

.btn-logout {
    background: rgba(255,255,255,0.2);
    color: var(--text-white);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background: rgba(255,255,255,0.3);
}

/* 다크모드 토글 버튼 */
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


.order-container {
    max-width: 1600px;
    margin: 20px auto;
    padding: 20px;
}

.page-header {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px var(--shadow-hover);
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
}

.page-header p {
    margin: 10px 0 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card h3 {
    margin: 0 0 10px 0;
    font-size: 14px;
    color: var(--text-secondary);
}

.stat-card .number {
    font-size: 32px;
    font-weight: bold;
    color: #667eea;
}

.stat-card.pending .number { color: #ff9800; }
.stat-card.processing .number { color: #2196f3; }
.stat-card.completed .number { color: #4caf50; }

.action-buttons {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-primary-custom {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow-hover);
}

.filter-section {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px var(--shadow);
}

.filter-section select,
.filter-section input {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    margin-right: 10px;
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.order-table {
    width: 100%;
    background: var(--bg-secondary);
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    overflow: hidden;
}

.order-table thead {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
}

.order-table th {
    padding: 15px 10px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
}

.order-table td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border-light);
    font-size: 13px;
}

.order-table tbody tr:hover {
    background: var(--hover-bg);
    transition: background 0.2s ease;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    white-space: nowrap;
}

.status-pending {
    background: #fff3e0;
    color: #f57c00;
}

.status-processing {
    background: #e3f2fd;
    color: #1976d2;
}

.status-completed {
    background: #e8f5e9;
    color: #388e3c;
}

.status-cancelled {
    background: #ffebee;
    color: #d32f2f;
}

.priority-high {
    color: #f44336;
    font-weight: bold;
}

.priority-normal {
    color: #666;
}

.btn-sm {
    padding: 5px 9px;
    font-size: 13px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    margin-right: 4px;
    transition: all 0.3s ease;
}

.btn-view {
    background: #2196f3;
    color: white;
}

.btn-edit {
    background: #ff9800;
    color: white;
}

.btn-delete {
    background: #f44336;
    color: white;
}

.btn-sm:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 20px;
    display: block;
}

/* 모바일 반응형 */
@media (max-width: 1024px) {
    .order-table {
        display: block;
        overflow-x: auto;
    }

    .order-table th,
    .order-table td {
        font-size: 12px;
        padding: 8px 6px;
    }
}

@media (max-width: 768px) {
    .top-navbar {
        padding: 10px 15px;
    }

    .top-navbar .logo {
        font-size: 16px;
    }

    .top-navbar .user-name span {
        display: none;
    }

    .btn-logout {
        padding: 6px 12px;
        font-size: 13px;
    }

    .order-container {
        padding: 10px;
        margin: 10px auto;
    }

    .page-header {
        padding: 20px 15px;
    }

    .page-header h1 {
        font-size: 20px;
    }

    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .stat-card {
        padding: 15px;
    }

    .stat-card h3 {
        font-size: 12px;
    }

    .stat-card .number {
        font-size: 24px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-primary-custom {
        width: 100%;
        padding: 12px;
    }

    .filter-section {
        padding: 15px;
    }

    .filter-section form {
        flex-direction: column !important;
    }

    .filter-section select,
    .filter-section input {
        width: 100%;
        margin-bottom: 10px;
    }

    /* 태블릿: 납품일, 청구일, 입금일, 항목수 숨김 */
    .order-table th:nth-child(3),
    .order-table td:nth-child(3),
    .order-table th:nth-child(4),
    .order-table td:nth-child(4),
    .order-table th:nth-child(5),
    .order-table td:nth-child(5),
    .order-table th:nth-child(8),
    .order-table td:nth-child(8) {
        display: none;
    }
}

@media (max-width: 576px) {
    .stats-container {
        grid-template-columns: 1fr;
    }

    .order-table {
        font-size: 11px;
    }

    /* 모바일: 제품명만 표시 (발주번호, 발주일, 거래처, 제품명, 상태, 작업만 표시) */
    .order-table th:nth-child(7),
    .order-table td:nth-child(7) {
        width: 30% !important;
    }

    .btn-sm {
        padding: 5px 8px;
        font-size: 12px;
        margin-right: 3px;
    }
    
    .btn-sm i {
        font-size: 12px;
    }
}
</style>
</head>
<body>

<!-- 상단 네비게이션 -->
<div class="top-navbar">
    <div class="logo">
        🏭 다온텍 발주 시스템
    </div>
    <div class="user-info">
        <div class="user-name">
            <i class="fas fa-user-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['daon_name']); ?> (<?php echo htmlspecialchars($_SESSION['daon_position'] ?? ''); ?>)</span>
        </div>
        <button class="theme-toggle" onclick="toggleTheme()" title="다크모드 전환">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
        <button class="btn-logout" onclick="location.href='login/logout.php'">
            <i class="fas fa-sign-out-alt"></i> 로그아웃
        </button>
    </div>
</div>

<div class="order-container">
    <div class="page-header">
        <h1>🏭 다온텍 발주 관리 시스템</h1>
        <p>아크릴, LED바 등의 제품 발주를 관리합니다</p>
    </div>

    <?php
    try {
        // 통계 데이터 조회
        $stats_sql = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
                        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                      FROM daon_orders
                      WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $stats_stmt = $pdo->prepare($stats_sql);
        $stats_stmt->execute();
        $stats = $stats_stmt->fetch();
    ?>

    <div class="stats-container">
        <div class="stat-card pending">
            <h3>대기중 발주</h3>
            <div class="number"><?php echo $stats['pending'] ?? 0; ?></div>
        </div>
        <div class="stat-card processing">
            <h3>진행중 발주</h3>
            <div class="number"><?php echo $stats['processing'] ?? 0; ?></div>
        </div>
        <div class="stat-card completed">
            <h3>완료된 발주</h3>
            <div class="number"><?php echo $stats['completed'] ?? 0; ?></div>
        </div>
        <div class="stat-card">
            <h3>전체 발주 (30일)</h3>
            <div class="number"><?php echo $stats['total'] ?? 0; ?></div>
        </div>
    </div>

    <?php } catch (PDOException $e) { } ?>

    <div class="action-buttons">
        <button class="btn-primary-custom" onclick="location.href='order_form.php'">
            ➕ 신규 발주 등록
        </button>
        <button class="btn-primary-custom" onclick="location.href='customer_list.php'" style="background: linear-gradient(135deg, #20b2aa 0%, #17a2b8 100%);">
            👥 거래처 관리
        </button>
        <button class="btn-primary-custom" onclick="location.href='product_list.php'" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            📦 제품 관리
        </button>
        <button class="btn-primary-custom" onclick="openCalendar()" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);">
            📅 일정 관리
        </button>
    </div>

    <div class="filter-section">
        <form method="GET" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
            <select name="status" onchange="this.form.submit()">
                <option value="">전체 상태</option>
                <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>대기중</option>
                <option value="processing" <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'selected' : ''; ?>>진행중</option>
                <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : ''; ?>>완료</option>
                <option value="cancelled" <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : ''; ?>>취소</option>
            </select>
            <input type="date" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>" placeholder="시작일">
            <input type="date" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>" placeholder="종료일">
            <input type="text" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="거래처명, 제품명 검색">
            <button type="submit" class="btn-primary-custom">검색</button>
            <button type="button" class="btn-primary-custom" onclick="location.href='index.php'" style="background: #999;">초기화</button>
        </form>
    </div>

    <?php
    try {
        // 발주 목록 조회
        $where = ["1=1"];
        $params = [];

        if (!empty($_GET['status'])) {
            $where[] = "o.status = :status";
            $params[':status'] = $_GET['status'];
        }

        if (!empty($_GET['date_from'])) {
            $where[] = "o.order_date >= :date_from";
            $params[':date_from'] = $_GET['date_from'];
        }

        if (!empty($_GET['date_to'])) {
            $where[] = "o.order_date <= :date_to";
            $params[':date_to'] = $_GET['date_to'];
        }

        if (!empty($_GET['search'])) {
            $where[] = "(c.company_name LIKE :search OR o.order_items LIKE :search)";
            $params[':search'] = '%' . $_GET['search'] . '%';
        }

        $sql = "SELECT DISTINCT
                    o.id,
                    o.order_number,
                    o.order_date,
                    o.delivery_date,
                    o.billing_date,
                    o.payment_date,
                    o.customer_id,
                    c.company_name as customer_name,
                    o.order_items,
                    o.status,
                    o.priority,
                    o.created_by,
                    o.created_at,
                    (SELECT name FROM member WHERE id = o.created_by LIMIT 1) as creator_name
                FROM daon_orders o
                LEFT JOIN daon_customers c ON o.customer_id = c.id
                WHERE " . implode(" AND ", $where) . "
                ORDER BY o.order_date DESC, o.created_at DESC
                LIMIT 100";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        if (count($orders) > 0) {
            echo '<table class="order-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th width="9%">발주번호</th>';
            echo '<th width="8%">발주일</th>';
            echo '<th width="7%">납품일</th>';
            echo '<th width="7%">청구일</th>';
            echo '<th width="7%">입금일</th>';
            echo '<th width="11%">거래처</th>';
            echo '<th width="17%">제품명 (첫항목)</th>';
            echo '<th width="6%" style="text-align:center;">항목수</th>';
            echo '<th width="10%" style="text-align:right;">총액</th>';
            echo '<th width="7%" style="text-align:center;">상태</th>';
            echo '<th width="11%" style="text-align:center;">작업</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // echo '<pre>';
            // print_r($orders);
            // echo '</pre>';

            foreach ($orders as $order) {
                $status_class = '';
                $status_text = '';

                switch($order['status']) {
                    case 'pending':
                        $status_class = 'status-pending';
                        $status_text = '대기중';
                        break;
                    case 'processing':
                        $status_class = 'status-processing';
                        $status_text = '진행중';
                        break;
                    case 'completed':
                        $status_class = 'status-completed';
                        $status_text = '완료';
                        break;
                    case 'cancelled':
                        $status_class = 'status-cancelled';
                        $status_text = '취소';
                        break;
                    default:
                        $status_class = 'status-pending';
                        $status_text = '대기중';
                }

                $priority_class = $order['priority'] == 'high' ? 'priority-high' : 'priority-normal';

                // 발주사항 파싱
                $first_product_name = '-';
                $item_count = 0;
                $total_amount = 0;
                
                if (!empty($order['order_items'])) {
                    try {
                        $items = json_decode($order['order_items'], true);
                        if (is_array($items) && count($items) > 0) {
                            $first_product_name = $items[0]['product_name'] ?? '-';
                            $item_count = count($items);
                            
                            foreach ($items as $item) {
                                $total_amount += intval($item['amount'] ?? 0);
                            }
                        }
                    } catch (Exception $e) {
                        $first_product_name = '(오류)';
                    }
                }

                echo '<tr class="' . $priority_class . '">';
                echo '<td><strong>' . htmlspecialchars($order['order_number']) . '</strong></td>';
                echo '<td>' . date('Y-m-d', strtotime($order['order_date'])) . '</td>';
                echo '<td>' . ($order['delivery_date'] ? date('Y-m-d', strtotime($order['delivery_date'])) : '-') . '</td>';
                echo '<td>' . ($order['billing_date'] ? date('Y-m-d', strtotime($order['billing_date'])) : '-') . '</td>';
                echo '<td>' . ($order['payment_date'] ? date('Y-m-d', strtotime($order['payment_date'])) : '-') . '</td>';
                echo '<td>' . htmlspecialchars($order['customer_name']) . '</td>';
                echo '<td>' . htmlspecialchars($first_product_name) . ($item_count > 1 ? ' 외 ' . ($item_count - 1) . '건' : '') . '</td>';
                echo '<td style="text-align:center;">' . $item_count . '개</td>';
                echo '<td style="text-align:right;"><strong>' . number_format($total_amount) . '원</strong></td>';
                echo '<td style="text-align:center;"><span class="status-badge ' . $status_class . '">' . $status_text . '</span></td>';
                echo '<td style="white-space: nowrap; text-align: center;">';
                echo '<button class="btn-sm btn-view" onclick="location.href=\'order_view.php?id=' . $order['id'] . '\'" title="보기"><i class="fas fa-eye"></i></button>';
                echo '<button class="btn-sm btn-edit" onclick="location.href=\'order_form.php?id=' . $order['id'] . '\'" title="수정"><i class="fas fa-edit"></i></button>';
                echo '<button class="btn-sm btn-delete" onclick="deleteOrder(' . $order['id'] . ')" title="삭제" style="margin-right: 0;"><i class="fas fa-trash"></i></button>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<div class="empty-state">';
            echo '<i class="fas fa-inbox"></i>';
            echo '<h3>등록된 발주가 없습니다</h3>';
            echo '<p>신규 발주 등록 버튼을 눌러 발주를 등록해보세요</p>';
            echo '</div>';
        }

    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">데이터베이스 오류: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    ?>
</div>

<script>
function deleteOrder(id) {
    if (confirm('정말로 이 발주를 삭제하시겠습니까?')) {
        fetch('order_delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('삭제되었습니다');
                location.reload();
            } else {
                alert('삭제 실패: ' + data.message);
            }
        })
        .catch(error => {
            alert('오류가 발생했습니다');
            console.error('Error:', error);
        });
    }
}
</script>

<!-- 도움말 버튼 -->
<button class="help-btn" onclick="openHelp()" title="도움말">
    <i class="fas fa-question-circle"></i>
</button>

<?php include 'calendar_modal.php'; ?>
<?php include 'help_modal.php'; ?>

<script>
// 다크모드 토글 함수
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    html.setAttribute('data-theme', newTheme);

    // 쿠키에 테마 저장 (30일 유효)
    const expires = new Date();
    expires.setDate(expires.getDate() + 30);
    document.cookie = `daon_theme=${newTheme}; expires=${expires.toUTCString()}; path=/`;

    // 아이콘 변경
    updateThemeIcon(newTheme);
}

// 테마 아이콘 업데이트
function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    if (theme === 'dark') {
        icon.className = 'fas fa-sun';
    } else {
        icon.className = 'fas fa-moon';
    }
}

// 쿠키에서 테마 로드
function loadThemeFromCookie() {
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        const [name, value] = cookie.trim().split('=');
        if (name === 'daon_theme') {
            return value;
        }
    }
    return 'light'; // 기본값
}

// 페이지 로드시 저장된 테마 적용
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = loadThemeFromCookie();
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
});

// 도움말 내용 설정
document.addEventListener('DOMContentLoaded', function() {
    const helpContent = document.getElementById('helpContent');
    if (helpContent) {
        helpContent.innerHTML = `
            <div class="help-section">
                <h3><i class="fas fa-home"></i> 메인 화면 소개</h3>
                <p>다온텍 발주 관리 시스템의 메인 화면입니다. 전체 발주 현황을 한눈에 확인하고 관리할 수 있습니다.</p>
            </div>

            <div class="help-section">
                <h3><i class="fas fa-chart-bar"></i> 통계 카드</h3>
                <p>화면 상단의 통계 카드에서 최근 30일간의 발주 현황을 확인할 수 있습니다:</p>
                <ul>
                    <li><strong>대기중 발주</strong>: 아직 처리되지 않은 발주 건수</li>
                    <li><strong>진행중 발주</strong>: 현재 제작/처리 중인 발주 건수</li>
                    <li><strong>완료된 발주</strong>: 완료된 발주 건수</li>
                    <li><strong>전체 발주</strong>: 최근 30일간 총 발주 건수</li>
                </ul>
            </div>

            <div class="help-section">
                <h3><i class="fas fa-th-large"></i> 주요 기능 버튼</h3>
                <div class="help-step">
                    <strong>➕ 신규 발주 등록</strong>
                    <p>새로운 발주를 등록합니다. 거래처, 제품, 수량, 납품일 등을 입력할 수 있습니다.</p>
                </div>
                <div class="help-step">
                    <strong>👥 거래처 관리</strong>
                    <p>발주처 거래처 정보를 등록하고 관리합니다.</p>
                </div>
                <div class="help-step">
                    <strong>📦 제품 관리</strong>
                    <p>아크릴, LED바 등 제품 정보를 등록하고 관리합니다.</p>
                </div>
                <div class="help-step">
                    <strong>📅 일정 관리</strong>
                    <p>달력 형식으로 납품 일정을 확인하고 관리합니다.</p>
                </div>
            </div>

            <div class="help-section">
                <h3><i class="fas fa-filter"></i> 검색 및 필터</h3>
                <p>발주 목록을 다양한 조건으로 검색하고 필터링할 수 있습니다:</p>
                <ul>
                    <li><strong>상태별 필터</strong>: 대기중, 진행중, 완료, 취소 중에서 선택</li>
                    <li><strong>날짜 범위</strong>: 시작일과 종료일로 기간 검색</li>
                    <li><strong>키워드 검색</strong>: 거래처명이나 제품명으로 검색</li>
                    <li><strong>초기화 버튼</strong>: 모든 필터를 제거하고 전체 목록 보기</li>
                </ul>
            </div>

            <div class="help-section">
                <h3><i class="fas fa-list"></i> 발주 목록 테이블</h3>
                <p>등록된 발주 목록을 표 형식으로 확인할 수 있습니다. 각 행에서 다음 작업을 수행할 수 있습니다:</p>
                <ul>
                    <li><strong>보기</strong>: 발주 상세 정보 확인</li>
                    <li><strong>수정</strong>: 발주 정보 수정</li>
                    <li><strong>삭제</strong>: 발주 삭제 (확인 메시지 표시)</li>
                </ul>
            </div>

            <div class="help-tip">
                <strong><i class="fas fa-lightbulb"></i> 팁:</strong>
                <p>• 발주번호는 자동으로 생성됩니다 (형식: ON-YYYYMMDD-순번)</p>
                <p>• 우선순위가 높은 발주는 빨간색 글씨로 표시됩니다</p>
                <p>• 모바일에서는 일부 컬럼이 자동으로 숨겨져 화면에 최적화됩니다</p>
            </div>

            <div class="help-warning">
                <strong><i class="fas fa-exclamation-triangle"></i> 주의사항:</strong>
                <p>• 삭제된 발주는 복구할 수 없으니 신중하게 삭제하세요</p>
                <p>• 완료된 발주도 수정할 수 있지만, 통계에 영향을 줄 수 있습니다</p>
            </div>
        `;
    }
});
</script>

</body>
</html>
