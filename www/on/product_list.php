<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

// 제품 목록 조회
try {
    $where = ["1=1"];
    $params = [];

    if (!empty($_GET['product_type'])) {
        $where[] = "product_type = :product_type";
        $params[':product_type'] = $_GET['product_type'];
    }

    if (!empty($_GET['status'])) {
        $where[] = "status = :status";
        $params[':status'] = $_GET['status'];
    }

    if (!empty($_GET['search'])) {
        $where[] = "(product_name LIKE :search OR product_code LIKE :search OR spec LIKE :search)";
        $params[':search'] = '%' . $_GET['search'] . '%';
    }

    $sql = "SELECT p.id, p.product_code, p.product_name, p.product_type, p.spec, 
                   p.unit, p.standard_price, p.status, p.created_by, p.created_at, p.updated_at,
                   (SELECT name FROM member WHERE id = p.created_by LIMIT 1) as creator_name
            FROM daon_products p
            WHERE " . implode(" AND ", $where) . "
            GROUP BY p.id
            ORDER BY p.product_type, p.product_name
            LIMIT 200";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // 제품 구분 목록 가져오기
    $typeSql = "SELECT DISTINCT product_type FROM daon_products WHERE product_type IS NOT NULL ORDER BY product_type";
    $productTypes = $pdo->query($typeSql)->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    $products = [];
    $productTypes = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>제품 관리 - 다온텍</title>

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
    background: linear-gradient(135deg, var(--product-gradient-start) 0%, var(--product-gradient-end) 100%);
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

.top-navbar .nav-buttons {
    display: flex;
    gap: 10px;
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
    background: linear-gradient(135deg, var(--product-gradient-start) 0%, var(--product-gradient-end) 100%);
    color: var(--text-white);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(240, 147, 251, 0.4);
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
    background: var(--bg-primary);
    color: var(--text-primary);
}

.product-table {
    width: 100%;
    background: var(--bg-secondary);
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    overflow: hidden;
}

.product-table thead {
    background: linear-gradient(135deg, var(--product-gradient-start) 0%, var(--product-gradient-end) 100%);
    color: var(--text-white);
}

.product-table th {
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
}

.product-table td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border-light);
    font-size: 13px;
}

.product-table tbody tr:hover {
    background: var(--product-hover-bg);
    transition: background 0.2s ease;
}

.product-type-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    background: #e3f2fd;
    color: #1976d2;
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
    padding: 5px 9px;
    font-size: 13px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    margin-right: 4px;
    transition: all 0.3s ease;
}

.btn-view { background: #2196f3; color: var(--text-white); }
.btn-edit { background: #ff9800; color: var(--text-white); }
.btn-delete { background: #f44336; color: var(--text-white); }

.btn-sm:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
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

    .product-table {
        display: block;
        overflow-x: auto;
    }

    .product-table th,
    .product-table td {
        font-size: 12px;
        padding: 8px 6px;
    }

    .product-table th:nth-child(2),
    .product-table td:nth-child(2),
    .product-table th:nth-child(4),
    .product-table td:nth-child(4),
    .product-table th:nth-child(7),
    .product-table td:nth-child(7) {
        display: none;
    }
}

@media (max-width: 576px) {
    .product-table th:nth-child(5),
    .product-table td:nth-child(5),
    .product-table th:nth-child(6),
    .product-table td:nth-child(6) {
        display: none;
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

<div class="top-navbar">
    <div class="logo">📦 제품 관리</div>
    <div class="nav-buttons">
        <button id="darkModeToggle" class="btn-back" style="cursor: pointer;">
            <i class="fas fa-moon"></i>
        </button>
        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> 메인</a>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1>📦 제품 관리</h1>
        <p>발주 가능한 제품 정보를 관리합니다</p>
    </div>

    <div class="action-bar">
        <a href="index.php" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: var(--text-white);">
            <i class="fas fa-home"></i> 발주 시스템
        </a>
        <a href="product_form.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> 신규 제품 등록
        </a>
        <button class="btn" onclick="openCalendar()" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: var(--text-white);">
            <i class="fas fa-calendar-alt"></i> 일정 관리
        </button>
    </div>

    <div class="filter-section">
        <form method="GET">
            <select name="product_type" onchange="this.form.submit()">
                <option value="">전체 구분</option>
                <?php foreach ($productTypes as $type): ?>
                <option value="<?php echo htmlspecialchars($type); ?>"
                        <?php echo (isset($_GET['product_type']) && $_GET['product_type'] == $type) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($type); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select name="status" onchange="this.form.submit()">
                <option value="">전체 상태</option>
                <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>활성</option>
                <option value="inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>비활성</option>
            </select>
            <input type="text" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="제품명, 제품코드, 규격 검색">
            <button type="submit" class="btn btn-primary">검색</button>
            <a href="product_list.php" class="btn" style="background:#999;color:var(--text-white);">초기화</a>
        </form>
    </div>

    <?php if (isset($error)): ?>
    <div style="background:#ffebee;color:#d32f2f;padding:15px;border-radius:8px;margin-bottom:20px;">
        오류: <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <?php if (count($products) > 0): ?>
    <table class="product-table">
        <thead>
            <tr>
                <th width="12%">제품코드</th>
                <th width="18%">제품명</th>
                <th width="10%" style="text-align:center;">제품구분</th>
                <th width="18%">규격</th>
                <th width="8%" style="text-align:center;">단위</th>
                <th width="10%" style="text-align:right;">표준단가</th>
                <th width="8%" style="text-align:center;">상태</th>
                <th width="16%" style="text-align:center;">작업</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($product['product_code'] ?? '-'); ?></strong></td>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td style="text-align:center;">
                    <span class="product-type-badge">
                        <?php echo htmlspecialchars($product['product_type']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($product['spec'] ?? '-'); ?></td>
                <td style="text-align:center;"><?php echo htmlspecialchars($product['unit']); ?></td>
                <td style="text-align:right;">
                    <?php echo $product['standard_price'] ? number_format($product['standard_price']) . '원' : '-'; ?>
                </td>
                <td style="text-align:center;">
                    <span class="status-badge status-<?php echo $product['status']; ?>">
                        <?php echo $product['status'] == 'active' ? '활성' : '비활성'; ?>
                    </span>
                </td>
                <td style="white-space: nowrap; text-align: center;">
                    <button class="btn-sm btn-edit" onclick="location.href='product_form.php?id=<?php echo $product['id']; ?>'" title="수정"><i class="fas fa-edit"></i></button>
                    <button class="btn-sm btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)" title="삭제" style="margin-right: 0;"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-box-open" style="font-size:48px;display:block;margin-bottom:20px;"></i>
        <h3>등록된 제품이 없습니다</h3>
        <p>신규 제품 등록 버튼을 눌러 제품을 등록해보세요</p>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteProduct(id) {
    if (confirm('정말로 이 제품을 삭제하시겠습니까?')) {
        fetch('product_delete.php', {
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
        $page = 'product_list';
        ?>
        helpContent.innerHTML = `<?php echo getHelpContent($page); ?>`;
    }
});

// 다크모드 토글
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle) return;

    // 초기 아이콘 설정
    function updateIcon() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        darkModeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    }

    updateIcon();

    darkModeToggle.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcon();
    });
});
</script>

</body>
</html>
