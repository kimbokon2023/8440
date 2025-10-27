<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

$isEdit = false;
$product = null;
$pageTitle = "신규 제품 등록";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $isEdit = true;
    $pageTitle = "제품 수정";

    try {
        $sql = "SELECT * FROM daon_products WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['id']]);
        $product = $stmt->fetch();

        if (!$product) {
            header('Location: product_list.php');
            exit;
        }
    } catch (PDOException $e) {
        die("오류: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $pageTitle; ?> - 다온텍</title>

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
    font-size: 16px;
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
    max-width: 900px;
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
    font-size: 20px;
    color: var(--text-primary);
}

.page-header p {
    color: var(--text-secondary);
}

.form-card {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow);
    margin-bottom: 20px;
}

.form-section-title {
    font-size: 16px;
    font-weight: 600;
    color: #f5576c;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f5576c;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: var(--text-primary);
    font-size: 14px;
}

.form-group label .required {
    color: #f44336;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s ease;
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: #f5576c;
    box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.btn-group {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, var(--product-gradient-start) 0%, var(--product-gradient-end) 100%);
    color: var(--text-white);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
}

.btn-secondary {
    background: #6c757d;
    color: var(--text-white);
}

.btn-secondary:hover {
    background: #5a6268;
}

.help-text {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 5px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 10px;
    }

    .page-header, .form-card {
        padding: 15px;
    }

    .page-header h1 {
        font-size: 18px;
    }

    .btn-group {
        flex-direction: column;
    }
}
</style>
</head>
<body>

<div class="top-navbar">
    <div class="logo">📦 제품 관리</div>
    <div class="nav-buttons">
        <button id="themeToggle" class="btn-back" style="cursor: pointer; border: none;">
            <i class="fas fa-moon"></i>
        </button>
        <a href="product_list.php" class="btn-back"><i class="fas fa-arrow-left"></i> 목록</a>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1><?php echo $pageTitle; ?></h1>
        <p><?php echo $isEdit ? '제품 정보를 수정합니다' : '새로운 제품을 등록합니다'; ?></p>
    </div>

    <form method="POST" action="product_save.php">
        <?php if ($isEdit): ?>
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <?php endif; ?>

        <div class="form-card">
            <div class="form-section-title">📦 기본 정보</div>

            <div class="form-row">
                <div class="form-group">
                    <label>제품코드</label>
                    <input type="text" name="product_code" class="form-control"
                           value="<?php echo $isEdit ? htmlspecialchars($product['product_code']) : ''; ?>"
                           placeholder="ACR-001">
                    <div class="help-text">고유한 제품 코드를 입력하세요 (선택사항)</div>
                </div>

                <div class="form-group">
                    <label>제품구분 <span class="required">*</span></label>
                    <input type="text" name="product_type" class="form-control" list="productTypes"
                           value="<?php echo $isEdit ? htmlspecialchars($product['product_type']) : ''; ?>"
                           placeholder="아크릴, LED바, 기타" required>
                    <datalist id="productTypes">
                        <option value="아크릴">
                        <option value="LED바">
                        <option value="간판">
                        <option value="조명">
                        <option value="기타">
                    </datalist>
                </div>
            </div>

            <div class="form-group">
                <label>제품명 <span class="required">*</span></label>
                <input type="text" name="product_name" class="form-control"
                       value="<?php echo $isEdit ? htmlspecialchars($product['product_name']) : ''; ?>"
                       placeholder="투명 아크릴판" required>
            </div>

            <div class="form-group">
                <label>규격</label>
                <input type="text" name="spec" class="form-control"
                       value="<?php echo $isEdit ? htmlspecialchars($product['spec']) : ''; ?>"
                       placeholder="1200x600x3T">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>단위 <span class="required">*</span></label>
                    <input type="text" name="unit" class="form-control" list="units"
                           value="<?php echo $isEdit ? htmlspecialchars($product['unit']) : 'EA'; ?>" required>
                    <datalist id="units">
                        <option value="EA">
                        <option value="M">
                        <option value="M2">
                        <option value="BOX">
                        <option value="SET">
                    </datalist>
                </div>

                <div class="form-group">
                    <label>표준단가</label>
                    <input type="number" name="standard_price" class="form-control"
                           value="<?php echo $isEdit ? $product['standard_price'] : ''; ?>"
                           step="0.01" min="0" placeholder="25000">
                    <div class="help-text">발주 등록 시 기본값으로 사용됩니다</div>
                </div>
            </div>

            <div class="form-group">
                <label>제품설명</label>
                <textarea name="description" class="form-control" rows="4"
                          placeholder="제품에 대한 상세 설명을 입력하세요"><?php echo $isEdit ? htmlspecialchars($product['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>상태</label>
                <select name="status" class="form-control">
                    <option value="active" <?php echo (!$isEdit || $product['status'] == 'active') ? 'selected' : ''; ?>>활성</option>
                    <option value="inactive" <?php echo ($isEdit && $product['status'] == 'inactive') ? 'selected' : ''; ?>>비활성</option>
                </select>
                <div class="help-text">비활성으로 설정하면 발주 등록 시 선택할 수 없습니다</div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $isEdit ? '수정하기' : '등록하기'; ?>
            </button>
            <a href="index.php" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-home"></i> 발주 시스템
            </a>
            <button type="button" class="btn btn-secondary" onclick="history.back()">
                <i class="fas fa-times"></i> 취소
            </button>
        </div>
    </form>
</div>

<script src="common/dark-mode.js"></script>
</body>
</html>
