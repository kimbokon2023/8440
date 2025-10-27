<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

// 거래처 목록 조회
try {
    $where = ["1=1"];
    $params = [];

    if (!empty($_GET['status'])) {
        $where[] = "status = :status";
        $params[':status'] = $_GET['status'];
    }

    if (!empty($_GET['search'])) {
        $where[] = "(company_name LIKE :search OR manager_name LIKE :search OR tel LIKE :search)";
        $params[':search'] = '%' . $_GET['search'] . '%';
    }

    $sql = "SELECT c.*, m.name as creator_name
            FROM daon_customers c
            LEFT JOIN daon_member m ON c.created_by = m.id
            WHERE " . implode(" AND ", $where) . "
            ORDER BY c.created_at DESC
            LIMIT 100";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $customers = $stmt->fetchAll();

} catch (PDOException $e) {
    $customers = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>거래처 관리 - 다온텍</title>

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="../favicon.ico">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- 다크모드 CSS -->
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
    box-shadow: 0 2px 10px var(--shadow);
    position: sticky;
    top: 0;
    z-index: 100;
}

.top-navbar .logo {
    font-size: 18px;
    font-weight: bold;
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

.top-navbar .user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 15px;
}

.page-header {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px var(--shadow);
}

.page-header h1 {
    font-size: 22px;
    color: var(--text-primary);
    margin-bottom: 5px;
}

.page-header p {
    color: var(--text-secondary);
}

.action-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--customer-gradient-start) 0%, var(--customer-gradient-end) 100%);
    color: var(--text-white);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(32, 178, 170, 0.4);
}

.filter-section {
    background: var(--bg-secondary);
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px var(--shadow);
}

.filter-section form {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-section select,
.filter-section input {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 14px;
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.customer-table {
    width: 100%;
    background: var(--bg-secondary);
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    overflow: hidden;
}

.customer-table thead {
    background: linear-gradient(135deg, var(--customer-gradient-start) 0%, var(--customer-gradient-end) 100%);
    color: var(--text-white);
}

.customer-table th {
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
}

.customer-table td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border-light);
    font-size: 13px;
}

.customer-table tbody tr:hover {
    background: var(--customer-hover-bg);
    transition: background 0.2s ease;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
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

.btn-sm {
    padding: 5px 12px;
    font-size: 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    margin-right: 5px;
}

.btn-view { background: #2196f3; color: var(--text-white); }
.btn-edit { background: #ff9800; color: var(--text-white); }
.btn-delete { background: #f44336; color: var(--text-white); }

.btn-sm:hover { opacity: 0.8; }

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    .page-header {
        padding: 15px;
    }

    .page-header h1 {
        font-size: 18px;
    }

    .action-bar {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .filter-section form {
        flex-direction: column;
    }

    .filter-section select,
    .filter-section input {
        width: 100%;
    }

    .customer-table {
        display: block;
        overflow-x: auto;
    }

    .customer-table th,
    .customer-table td {
        font-size: 12px;
        padding: 8px 6px;
    }

    .customer-table th:nth-child(5),
    .customer-table td:nth-child(5),
    .customer-table th:nth-child(6),
    .customer-table td:nth-child(6),
    .customer-table th:nth-child(7),
    .customer-table td:nth-child(7) {
        display: none;
    }
}

@media (max-width: 576px) {
    .customer-table th:nth-child(3),
    .customer-table td:nth-child(3),
    .customer-table th:nth-child(4),
    .customer-table td:nth-child(4) {
        display: none;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 10px;
    }
}
</style>
</head>
<body>

<div class="top-navbar">
    <div class="logo">👥 거래처 관리</div>
    <div class="user-info">
        <button class="theme-toggle" onclick="toggleTheme()" title="다크모드 전환">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> 메인</a>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1>👥 거래처 관리</h1>
        <p>발주처 거래처 정보를 관리합니다</p>
    </div>

    <div class="action-bar">
        <a href="index.php" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <i class="fas fa-home"></i> 발주 시스템
        </a>
        <a href="customer_form.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> 신규 거래처 등록
        </a>
        <button class="btn" onclick="openCalendar()" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white;">
            <i class="fas fa-calendar-alt"></i> 일정 관리
        </button>
    </div>

    <div class="filter-section">
        <form method="GET">
            <select name="status" onchange="this.form.submit()">
                <option value="">전체 상태</option>
                <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>활성</option>
                <option value="inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>비활성</option>
            </select>
            <input type="text" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="회사명, 담당자, 전화번호 검색">
            <button type="submit" class="btn btn-primary">검색</button>
            <a href="customer_list.php" class="btn" style="background:#999;color:white;">초기화</a>
        </form>
    </div>

    <?php if (isset($error)): ?>
    <div style="background:#ffebee;color:#d32f2f;padding:15px;border-radius:8px;margin-bottom:20px;">
        오류: <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <?php if (count($customers) > 0): ?>
    <table class="customer-table">
        <thead>
            <tr>
                <th width="20%">회사명</th>
                <th width="12%">대표자</th>
                <th width="12%">전화번호</th>
                <th width="10%">담당자</th>
                <th width="12%">담당자연락처</th>
                <th width="12%">이메일</th>
                <th width="8%">상태</th>
                <th width="14%">작업</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($customer['company_name']); ?></strong></td>
                <td><?php echo htmlspecialchars($customer['ceo_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($customer['tel'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($customer['manager_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($customer['manager_tel'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($customer['email'] ?? '-'); ?></td>
                <td>
                    <span class="status-badge status-<?php echo $customer['status']; ?>">
                        <?php echo $customer['status'] == 'active' ? '활성' : '비활성'; ?>
                    </span>
                </td>
                <td>
                    <button class="btn-sm btn-view" onclick="location.href='customer_view.php?id=<?php echo $customer['id']; ?>'">보기</button>
                    <button class="btn-sm btn-edit" onclick="location.href='customer_form.php?id=<?php echo $customer['id']; ?>'">수정</button>
                    <button class="btn-sm btn-delete" onclick="deleteCustomer(<?php echo $customer['id']; ?>)">삭제</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-building" style="font-size:48px;display:block;margin-bottom:20px;"></i>
        <h3>등록된 거래처가 없습니다</h3>
        <p>신규 거래처 등록 버튼을 눌러 거래처를 등록해보세요</p>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteCustomer(id) {
    if (confirm('정말로 이 거래처를 삭제하시겠습니까?\n관련된 발주 정보가 있으면 삭제할 수 없습니다.')) {
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
// 도움말 내용 설정
document.addEventListener('DOMContentLoaded', function() {
    const helpContent = document.getElementById('helpContent');
    if (helpContent) {
        <?php
        require_once 'help_contents.php';
        $page = 'customer_list';
        ?>
        helpContent.innerHTML = `<?php echo getHelpContent($page); ?>`;
    }
});
</script>

</body>
</html>
