<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: index.php');
    exit;
}

try {
    $sql = "SELECT o.*, c.company_name, c.tel as customer_tel, c.manager_name, c.manager_tel,
            m.name as creator_name
            FROM daon_orders o
            LEFT JOIN daon_customers c ON o.customer_id = c.id
            LEFT JOIN daon_member m ON o.created_by = m.id
            WHERE o.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $order = $stmt->fetch();

    if (!$order) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("오류: " . $e->getMessage());
}

// 상태 한글 변환
$statusText = [
    'pending' => '대기중',
    'processing' => '진행중',
    'completed' => '완료',
    'cancelled' => '취소'
];

$priorityText = [
    'normal' => '일반',
    'high' => '높음',
    'urgent' => '긴급'
];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>발주 상세보기 - 다온텍</title>

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="../favicon.ico">

<!-- Font Awesome -->
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
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px var(--shadow);
    position: sticky;
    top: 0;
    z-index: 100;
}

.top-navbar .logo {
    font-size: 16px;
    font-weight: bold;
}

.nav-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
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

.status-pending { background: #fff3e0; color: #f57c00; }
.status-processing { background: #e3f2fd; color: #1976d2; }
.status-completed { background: #e8f5e9; color: #388e3c; }
.status-cancelled { background: #ffebee; color: #d32f2f; }

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
    color: #667eea;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #667eea;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    padding: 12px;
    background: var(--hover-bg);
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

.info-value.large {
    font-size: 20px;
    color: #667eea;
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

.btn-primary {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
}
</style>
</head>
<body>

<!-- 상단 네비게이션 -->
<div class="top-navbar">
    <div class="logo">🏭 다온텍</div>
    <div class="nav-buttons">
        <button id="theme-toggle" class="btn-back" style="cursor: pointer;">
            <i class="fas fa-moon"></i>
        </button>
        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> 목록</a>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <div>
            <h1>📋 발주 상세 정보</h1>
            <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">
                발주번호: <?php echo htmlspecialchars($order['order_number']); ?>
            </div>
        </div>
        <span class="status-badge status-<?php echo $order['status']; ?>">
            <?php echo $statusText[$order['status']] ?? $order['status']; ?>
        </span>
    </div>

    <!-- 기본 정보 -->
    <div class="info-card">
        <div class="info-section-title">📋 기본 정보</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">발주일자</div>
                <div class="info-value"><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">납품요청일</div>
                <div class="info-value">
                    <?php echo $order['delivery_date'] ? date('Y-m-d', strtotime($order['delivery_date'])) : '-'; ?>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">우선순위</div>
                <div class="info-value">
                    <?php
                    $priority = $priorityText[$order['priority']] ?? $order['priority'];
                    $priorityColor = $order['priority'] == 'urgent' ? 'color:#f44336' : ($order['priority'] == 'high' ? 'color:#ff9800' : '');
                    echo "<span style='{$priorityColor}'>{$priority}</span>";
                    ?>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">등록자</div>
                <div class="info-value"><?php echo htmlspecialchars($order['creator_name']); ?></div>
            </div>
        </div>
    </div>

    <!-- 거래처 정보 -->
    <div class="info-card">
        <div class="info-section-title">🏢 거래처 정보</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">회사명</div>
                <div class="info-value"><?php echo htmlspecialchars($order['company_name']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">대표 전화</div>
                <div class="info-value"><?php echo htmlspecialchars($order['customer_tel'] ?? '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">담당자</div>
                <div class="info-value"><?php echo htmlspecialchars($order['manager_name'] ?? '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">담당자 연락처</div>
                <div class="info-value"><?php echo htmlspecialchars($order['manager_tel'] ?? '-'); ?></div>
            </div>
        </div>
    </div>

    <!-- 제품 정보 -->
    <div class="info-card">
        <div class="info-section-title">📦 제품 정보</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">제품명</div>
                <div class="info-value"><?php echo htmlspecialchars($order['product_name']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">제품구분</div>
                <div class="info-value"><?php echo htmlspecialchars($order['product_type'] ?? '-'); ?></div>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <div class="info-label">규격</div>
                <div class="info-value"><?php echo htmlspecialchars($order['spec'] ?? '-'); ?></div>
            </div>
        </div>
    </div>

    <!-- 금액 정보 -->
    <div class="info-card">
        <div class="info-section-title">💰 금액 정보</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">수량</div>
                <div class="info-value">
                    <?php echo number_format(intval($order['quantity'])); ?> <?php echo htmlspecialchars($order['unit']); ?>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">단가</div>
                <div class="info-value"><?php echo number_format($order['unit_price']); ?>원</div>
            </div>
            <div class="info-item">
                <div class="info-label">총액</div>
                <div class="info-value large"><?php echo number_format($order['total_price']); ?>원</div>
            </div>
            <div class="info-item">
                <div class="info-label">부가세</div>
                <div class="info-value"><?php echo $order['vat_included'] ? '포함' : '별도'; ?></div>
            </div>
        </div>
    </div>

    <!-- 배송 정보 -->
    <?php if ($order['delivery_address'] || $order['note']): ?>
    <div class="info-card">
        <div class="info-section-title">🚚 배송 및 비고</div>
        <?php if ($order['delivery_address']): ?>
        <div class="info-item" style="margin-bottom: 10px;">
            <div class="info-label">납품주소</div>
            <div class="info-value"><?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?></div>
        </div>
        <?php endif; ?>
        <?php if ($order['note']): ?>
        <div class="info-item">
            <div class="info-label">비고</div>
            <div class="info-value"><?php echo nl2br(htmlspecialchars($order['note'])); ?></div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- 버튼 -->
    <div class="info-card">
        <div class="btn-group">
            <a href="order_form.php?id=<?php echo $order['id']; ?>" class="btn btn-edit">
                <i class="fas fa-edit"></i> 수정
            </a>
            <button onclick="deleteOrder(<?php echo $order['id']; ?>)" class="btn btn-delete">
                <i class="fas fa-trash"></i> 삭제
            </button>
            <button onclick="openCalendar()" class="btn" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: var(--text-white);">
                <i class="fas fa-calendar-alt"></i> 일정 관리
            </button>
            <a href="index.php" class="btn btn-back-list">
                <i class="fas fa-list"></i> 목록으로
            </a>
        </div>
    </div>
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
                location.href = 'index.php';
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

// Dark mode toggle
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = themeToggle.querySelector('i');

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateIcon(savedTheme);

    // Toggle theme
    themeToggle.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcon(newTheme);
    });

    function updateIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
});
</script>

<?php include 'calendar_modal.php'; ?>

</body>
</html>
