<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: customer_list.php');
    exit;
}

try {
    // 거래처 정보 조회
    $sql = "SELECT c.*, m.name as creator_name
            FROM daon_customers c
            LEFT JOIN daon_member m ON c.created_by = m.id
            WHERE c.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $customer = $stmt->fetch();

    if (!$customer) {
        header('Location: customer_list.php');
        exit;
    }

    // 이 거래처의 발주 내역 조회
    $orderSql = "SELECT id, order_number, order_date, product_name, total_price, status
                 FROM daon_orders
                 WHERE customer_id = ?
                 ORDER BY order_date DESC
                 LIMIT 10";
    $orderStmt = $pdo->prepare($orderSql);
    $orderStmt->execute([$id]);
    $orders = $orderStmt->fetchAll();

    // 발주 통계
    $statsSql = "SELECT
                    COUNT(*) as total_orders,
                    SUM(total_price) as total_amount,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count
                 FROM daon_orders
                 WHERE customer_id = ?";
    $statsStmt = $pdo->prepare($statsSql);
    $statsStmt->execute([$id]);
    $stats = $statsStmt->fetch();

} catch (PDOException $e) {
    die("오류: " . $e->getMessage());
}

$statusText = [
    'pending' => '대기중',
    'processing' => '진행중',
    'completed' => '완료',
    'cancelled' => '취소'
];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>거래처 상세보기 - 다온텍</title>

<link rel="icon" type="image/x-icon" href="../favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?php include 'common/dark-mode-header.php'; ?>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
}

.top-navbar {
    background: linear-gradient(135deg, var(--customer-gradient-start) 0%, var(--customer-gradient-end) 100%);
    color: var(--text-white);
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    position: sticky;
    top: 0;
    z-index: 100;
}

.top-navbar .logo {
    font-size: 16px;
    font-weight: bold;
}

.theme-toggle {
    background: rgba(255,255,255,0.2);
    color: var(--text-white);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.theme-toggle:hover {
    background: rgba(255,255,255,0.3);
}

.top-navbar .btn-back {
    background: rgba(255,255,255,0.2);
    color: var(--text-white);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 15px;
}

.page-header {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.page-header h1 {
    font-size: 20px;
    color: var(--text-primary);
}

.status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-active {
    background: #e8f5e9;
    color: #388e3c;
}

.status-inactive {
    background: #ffebee;
    color: #d32f2f;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    text-align: center;
}

.stat-card h3 {
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 10px;
}

.stat-card .number {
    font-size: 28px;
    font-weight: bold;
    color: #20b2aa;
}

.info-card {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    margin-bottom: 15px;
}

.info-section-title {
    font-size: 16px;
    font-weight: 600;
    color: #20b2aa;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #20b2aa;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    padding: 12px;
    background: var(--customer-hover-bg);
    border-radius: 8px;
}

.info-label {
    font-size: 12px;
    color: var(--text-secondary);
    margin-bottom: 5px;
}

.info-value {
    font-size: 15px;
    color: var(--text-primary);
    font-weight: 500;
}

.order-history-table {
    width: 100%;
    border-collapse: collapse;
}

.order-history-table th {
    background: var(--customer-hover-bg);
    padding: 12px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #20b2aa;
}

.order-history-table td {
    padding: 12px;
    border-bottom: 1px solid var(--border-light);
    font-size: 13px;
}

.order-history-table tbody tr:hover {
    background: var(--customer-hover-bg);
}

.btn-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-edit {
    background: #ff9800;
    color: var(--text-white);
}

.btn-delete {
    background: #f44336;
    color: var(--text-white);
}

.btn-back-list {
    background: #6c757d;
    color: var(--text-white);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow);
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    .info-card {
        padding: 15px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .stat-card {
        padding: 15px;
    }

    .stat-card .number {
        font-size: 22px;
    }

    .btn-group {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-history-table {
        font-size: 12px;
    }

    .order-history-table th,
    .order-history-table td {
        padding: 8px 6px;
    }

    .order-history-table th:nth-child(4),
    .order-history-table td:nth-child(4) {
        display: none;
    }
}

@media (max-width: 576px) {
    .stats-container {
        grid-template-columns: 1fr;
    }

    .order-history-table th:nth-child(3),
    .order-history-table td:nth-child(3) {
        display: none;
    }
}
</style>
</head>
<body>

<div class="top-navbar">
    <div class="logo">👥 거래처 관리</div>
    <div style="display: flex; gap: 10px;">
        <button class="theme-toggle" onclick="toggleTheme()" title="다크모드 전환">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
        <a href="customer_list.php" class="btn-back"><i class="fas fa-arrow-left"></i> 목록</a>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <div>
            <h1>🏢 거래처 상세 정보</h1>
            <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">
                <?php echo htmlspecialchars($customer['company_name']); ?>
            </div>
        </div>
        <span class="status-badge status-<?php echo $customer['status']; ?>">
            <?php echo $customer['status'] == 'active' ? '활성' : '비활성'; ?>
        </span>
    </div>

    <!-- 발주 통계 -->
    <div class="stats-container">
        <div class="stat-card">
            <h3>총 발주 건수</h3>
            <div class="number"><?php echo number_format($stats['total_orders'] ?? 0); ?></div>
        </div>
        <div class="stat-card">
            <h3>총 발주 금액</h3>
            <div class="number" style="font-size:20px;"><?php echo number_format($stats['total_amount'] ?? 0); ?>원</div>
        </div>
        <div class="stat-card">
            <h3>진행중 발주</h3>
            <div class="number" style="color:#ff9800;"><?php echo number_format($stats['pending_count'] ?? 0); ?></div>
        </div>
        <div class="stat-card">
            <h3>완료된 발주</h3>
            <div class="number" style="color:#4caf50;"><?php echo number_format($stats['completed_count'] ?? 0); ?></div>
        </div>
    </div>

    <!-- 기본 정보 -->
    <div class="info-card">
        <div class="info-section-title">🏢 기본 정보</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">회사명</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['company_name']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">사업자번호</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['business_number'] ?? '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">대표자명</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['ceo_name'] ?? '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">등록자</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['creator_name'] ?? '-'); ?></div>
            </div>
        </div>
        <?php if ($customer['address']): ?>
        <div class="info-item" style="margin-top: 15px;">
            <div class="info-label">주소</div>
            <div class="info-value"><?php echo nl2br(htmlspecialchars($customer['address'])); ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- 연락처 정보 -->
    <div class="info-card">
        <div class="info-section-title">📞 연락처 정보</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">전화번호</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['tel'] ?? '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">팩스번호</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['fax'] ?? '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">이메일</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['email'] ?? '-'); ?></div>
            </div>
        </div>
    </div>

    <!-- 담당자 정보 -->
    <?php if ($customer['manager_name'] || $customer['manager_tel']): ?>
    <div class="info-card">
        <div class="info-section-title">👤 담당자 정보</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">담당자명</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['manager_name'] ?? '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">담당자 연락처</div>
                <div class="info-value"><?php echo htmlspecialchars($customer['manager_tel'] ?? '-'); ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- 비고 -->
    <?php if ($customer['note']): ?>
    <div class="info-card">
        <div class="info-section-title">📝 비고</div>
        <div class="info-item">
            <div class="info-value"><?php echo nl2br(htmlspecialchars($customer['note'])); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- 최근 발주 내역 -->
    <div class="info-card">
        <div class="info-section-title">📋 최근 발주 내역 (최대 10건)</div>
        <?php if (count($orders) > 0): ?>
        <div style="overflow-x: auto;">
            <table class="order-history-table">
                <thead>
                    <tr>
                        <th>발주번호</th>
                        <th>발주일</th>
                        <th>제품명</th>
                        <th>금액</th>
                        <th>상태</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                        <td><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></td>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td style="text-align:right;"><?php echo number_format($order['total_price']); ?>원</td>
                        <td>
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo $statusText[$order['status']] ?? $order['status']; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn" style="background:#2196f3;color:var(--text-white);padding:5px 12px;font-size:12px;"
                                    onclick="location.href='order_view.php?id=<?php echo $order['id']; ?>'">
                                보기
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>발주 내역이 없습니다</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- 버튼 -->
    <div class="info-card">
        <div class="btn-group">
            <a href="index.php" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: var(--text-white);">
                <i class="fas fa-home"></i> 발주 시스템
            </a>
            <a href="customer_form.php?id=<?php echo $customer['id']; ?>" class="btn btn-edit">
                <i class="fas fa-edit"></i> 수정
            </a>
            <button onclick="deleteCustomer(<?php echo $customer['id']; ?>)" class="btn btn-delete">
                <i class="fas fa-trash"></i> 삭제
            </button>
            <button onclick="openCalendar()" class="btn" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: var(--text-white);">
                <i class="fas fa-calendar-alt"></i> 일정 관리
            </button>
            <a href="customer_list.php" class="btn btn-back-list">
                <i class="fas fa-list"></i> 목록으로
            </a>
        </div>
    </div>
</div>

<script>
function deleteCustomer(id) {
    if (confirm('정말로 이 거래처를 삭제하시겠습니까?\n관련된 발주가 있으면 삭제할 수 없습니다.')) {
        fetch('customer_delete.php', {
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
                location.href = 'customer_list.php';
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

<?php include 'calendar_modal.php'; ?>

</body>
</html>
