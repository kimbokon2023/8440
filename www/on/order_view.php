<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';
require_once __DIR__ . '/../api/file_api.php';

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

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    border: 1px solid var(--border-color);
    background: var(--hover-bg);
}

.table td {
    border: 1px solid var(--border-color);
}

.table-bordered {
    border: 1px solid var(--border-color);
}

.text-center {
    text-align: center;
}

.text-end {
    text-align: right;
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
                <div class="info-label">청구일</div>
                <div class="info-value">
                    <?php echo $order['billing_date'] ? date('Y-m-d', strtotime($order['billing_date'])) : '-'; ?>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">입금일</div>
                <div class="info-value">
                    <?php echo $order['payment_date'] ? date('Y-m-d', strtotime($order['payment_date'])) : '-'; ?>
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

    <!-- 발주사항 -->
    <?php if (!empty($order['order_items'])): ?>
    <div class="info-card">
        <div class="info-section-title">📝 발주사항</div>
        <table class="table table-bordered" style="background: var(--bg-secondary); margin: 0;">
            <thead style="background: #f0f0f0;">
                <tr>
                    <th width="6%" class="text-center" style="padding: 10px; font-size: 13px;">번호</th>
                    <th width="22%" style="padding: 10px; font-size: 13px;">제품명</th>
                    <th width="15%" style="padding: 10px; font-size: 13px;">규격</th>
                    <th width="10%" class="text-center" style="padding: 10px; font-size: 13px;">수량</th>
                    <th width="8%" class="text-center" style="padding: 10px; font-size: 13px;">단위</th>
                    <th width="13%" class="text-end" style="padding: 10px; font-size: 13px;">단가</th>
                    <th width="13%" class="text-end" style="padding: 10px; font-size: 13px;">금액</th>
                    <th width="13%" style="padding: 10px; font-size: 13px;">비고</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $order_items_data = json_decode($order['order_items'], true);
                    if (is_array($order_items_data) && count($order_items_data) > 0) {
                        $rowNum = 1;
                        $totalAmount = 0;
                        foreach ($order_items_data as $item) {
                            $amount = intval($item['amount'] ?? 0);
                            $totalAmount += $amount;
                            
                            echo '<tr>';
                            echo '<td class="text-center" style="padding: 8px; font-size: 13px;">' . $rowNum . '</td>';
                            echo '<td style="padding: 8px; font-size: 13px;">' . htmlspecialchars($item['product_name'] ?? '') . '</td>';
                            echo '<td style="padding: 8px; font-size: 13px;">' . htmlspecialchars($item['spec'] ?? '') . '</td>';
                            echo '<td class="text-center" style="padding: 8px; font-size: 13px;">' . htmlspecialchars($item['quantity'] ?? '') . '</td>';
                            echo '<td class="text-center" style="padding: 8px; font-size: 13px;">' . htmlspecialchars($item['unit'] ?? 'EA') . '</td>';
                            echo '<td class="text-end" style="padding: 8px; font-size: 13px;">' . number_format(intval($item['unit_price'] ?? 0)) . '원</td>';
                            echo '<td class="text-end" style="padding: 8px; font-size: 13px;"><strong>' . number_format($amount) . '원</strong></td>';
                            echo '<td style="padding: 8px; font-size: 13px;">' . htmlspecialchars($item['note'] ?? '') . '</td>';
                            echo '</tr>';
                            $rowNum++;
                        }
                        
                        // 합계 행 추가
                        echo '<tr style="background: #f8f9fa; font-weight: bold;">';
                        echo '<td colspan="6" class="text-end" style="padding: 10px; font-size: 14px;">합계</td>';
                        echo '<td class="text-end" style="padding: 10px; font-size: 14px; color: #667eea;">' . number_format($totalAmount) . '원</td>';
                        echo '<td></td>';
                        echo '</tr>';
                    }
                } catch (Exception $e) {
                    echo '<tr><td colspan="8" class="text-center" style="padding: 20px;">발주사항 데이터를 불러올 수 없습니다.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

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

    <!-- 첨부파일 -->
    <?php
    try {
        $fileOptions = [
            'tablename' => 'daon_orders',
            'item' => 'attached',
            'parentnum' => $order['id'],
            'DBtable' => 'picuploads'
        ];
        $files = getFilesFromGoogleDrive($fileOptions);
        
        // 배열이 아니거나 null인 경우 빈 배열로 설정
        if (!is_array($files)) {
            $files = [];
        }
        
        // 빈 요소 필터링
        $files = array_filter($files, function($file) {
            return !empty($file) && is_array($file) && !empty($file['realname']);
        });
        
    } catch (Exception $e) {
        $files = [];
        error_log("파일 목록 조회 오류: " . $e->getMessage());
    }
    
    if (is_array($files) && count($files) > 0): ?>
    <div class="info-card">
        <div class="info-section-title">📎 첨부파일 (<?php echo count($files); ?>개)</div>
        <div class="file-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
            <?php foreach ($files as $file): ?>
            <div class="file-item" style="background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                <?php if (in_array(strtolower(pathinfo($file['realname'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                <img src="<?php echo $file['thumbnail']; ?>" 
                     alt="<?php echo htmlspecialchars($file['realname']); ?>"
                     style="width: 100%; height: 150px; object-fit: cover; border-radius: 6px; margin-bottom: 10px;">
                <?php else: ?>
                <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; background: #f0f0f0; border-radius: 6px; margin-bottom: 10px;">
                    <i class="fas fa-file" style="font-size: 48px; color: #999;"></i>
                </div>
                <?php endif; ?>
                
                <div style="font-size: 13px; margin-bottom: 8px; word-break: break-all;">
                    <?php echo htmlspecialchars($file['realname']); ?>
                </div>
                
                <div style="display: flex; gap: 5px;">
                    <a href="<?php echo $file['link']; ?>" target="_blank" 
                       style="flex: 1; padding: 6px 10px; background: #2196f3; color: white; border-radius: 4px; text-decoration: none; text-align: center; font-size: 12px;">
                        <i class="fas fa-download"></i> 다운로드
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
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
