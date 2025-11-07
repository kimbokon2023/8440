<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';
require_once __DIR__ . '/../api/file_api.php';

$isEdit = false;
$order = null;
$pageTitle = "신규 발주 등록";
$fileParentNum = 'temp_' . time(); // 임시 파일번호 (신규일 때)

// 수정 모드인 경우
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $isEdit = true;
    $pageTitle = "발주 수정";

    try {
        $sql = "SELECT * FROM daon_orders WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['id']]);
        $order = $stmt->fetch();

        if (!$order) {
            header('Location: index.php');
            exit;
        }
        
        // 수정 모드일 때는 실제 order_id 사용
        $fileParentNum = $order['id'];
    } catch (PDOException $e) {
        die("오류: " . $e->getMessage());
    }
}

// 거래처 목록 가져오기
try {
    $customerSql = "SELECT id, company_name FROM daon_customers WHERE status = 'active' ORDER BY company_name";
    $customers = $pdo->query($customerSql)->fetchAll();
} catch (PDOException $e) {
    $customers = [];
}

// 제품 목록 가져오기
try {
    $productSql = "SELECT id, product_code, product_name, product_type, spec, unit, standard_price
                   FROM daon_products WHERE status = 'active' ORDER BY product_type, product_name";
    $products = $pdo->query($productSql)->fetchAll();
} catch (PDOException $e) {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $pageTitle; ?> - 다온텍</title>

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="../favicon.ico">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- File Manager -->
<script src="../js/file_manager.js"></script>

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

.top-navbar .user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.btn-back {
    background: rgba(255,255,255,0.2);
    color: var(--text-white);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: rgba(255,255,255,0.3);
}

.container {
    max-width: 1200px;
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
    font-size: 14px;
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
    color: #667eea;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
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
    background: var(--bg-secondary);
    color: var(--text-primary);
    transition: border 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 15px;
}

@media (max-width: 992px) {
    .form-row {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
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
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow-hover);
}

.btn-secondary {
    background: #6c757d;
    color: var(--text-white);
}

.btn-secondary:hover {
    background: #5a6268;
}

.input-with-button {
    display: flex;
    gap: 10px;
}

.input-with-button .form-control {
    flex: 1;
}

.input-with-button .btn-small {
    padding: 10px 15px;
    background: #667eea;
    color: var(--text-white);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    white-space: nowrap;
    font-size: 13px;
}

.input-with-button .btn-icon {
    padding: 10px 12px;
    background: #4caf50;
    color: var(--text-white);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.input-with-button .btn-icon:hover {
    background: #45a049;
    transform: translateY(-1px);
}

.auto-calc {
    background: var(--hover-bg);
    border: 1px dashed #667eea;
}

.help-text {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 5px;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    border: 1px solid var(--border-color);
    padding: 10px;
}

.table td {
    border: 1px solid var(--border-color);
    padding: 8px;
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

.btn-sm {
    padding: 6px 10px;
    font-size: 13px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    margin-right: 5px;
    transition: all 0.2s ease;
}

.btn-view { background: #2196f3; color: white; }
.btn-edit { background: #ff9800; color: white; }
.btn-delete { background: #f44336; color: white; }

.btn-sm:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* 파일 드롭존 스타일 */
.drop-zone {
    border: 2px dashed var(--border-color);
    border-radius: 10px;
    padding: 20px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: var(--bg-primary);
    margin-bottom: 20px;
}

.drop-zone:hover {
    border-color: #667eea;
    background: var(--hover-bg);
}

.drop-zone.drag-over {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    transform: scale(1.02);
}

.file-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

/* file_manager.js가 생성하는 요소 스타일링 */
#displayFile {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

#displayFile .row {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 15px;
    margin: 0 !important;
    transition: all 0.2s ease;
}

#displayFile .row:hover {
    box-shadow: 0 4px 12px var(--shadow);
    transform: translateY(-2px);
}

#displayFile .d-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

#displayFile span[id^="file"] {
    flex: 1;
    min-width: 0;
}

#displayFile span[id^="file"] a {
    color: var(--text-primary);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    word-break: break-all;
    padding: 10px 12px;
    background: var(--bg-secondary);
    border-radius: 6px;
    transition: all 0.2s ease;
}

#displayFile span[id^="file"] a:hover {
    background: var(--hover-bg);
    color: #667eea;
    transform: translateX(5px);
}

#displayFile span[id^="file"] a i {
    font-size: 24px;
    flex-shrink: 0;
}

#displayFile .btn {
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

#displayFile .btn-danger {
    background: #f44336;
    color: white;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

#displayFile .btn-danger:hover {
    background: #d32f2f;
    transform: scale(1.1);
}

#displayFile .btn-sm {
    padding: 0;
}

#displayFile .text-center {
    display: none;
}

/* 파일이 없을 때 메시지 스타일 */
#displayFile > div.text-center {
    display: block;
    text-align: center;
    padding: 20px;
    color: var(--text-secondary);
    font-size: 13px;
}

.file-item {
    background: var(--bg-secondary);
    transition: all 0.2s ease;
}

.file-item:hover {
    background: var(--hover-bg);
}

.file-item .file-name {
    font-size: 14px;
    color: var(--text-primary);
    font-weight: 500;
    word-break: break-word;
}

.file-item .file-actions {
    display: flex;
    gap: 5px;
}

.file-item .file-actions button {
    flex: 1;
    padding: 6px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s ease;
}

.file-btn-download {
    background: #2196f3;
    color: white;
}

.file-btn-delete {
    background: #f44336;
    color: white;
}

.file-item .file-actions button:hover {
    opacity: 0.9;
    transform: scale(1.05);
}

.upload-progress {
    margin-top: 10px;
    padding: 10px;
    background: var(--hover-bg);
    border-radius: 6px;
    display: none;
}

.upload-progress.show {
    display: block;
}

.progress-bar {
    width: 100%;
    height: 20px;
    background: var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    margin-top: 5px;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
}

/* 업로드 중 모달 스타일 */
.file-upload-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    backdrop-filter: blur(4px);
}

.file-upload-loading-content {
    background: white;
    padding: 40px 60px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    min-width: 300px;
}

[data-theme="dark"] .file-upload-loading-content {
    background: #2d3748;
    color: #e2e8f0;
}

.file-upload-loading-content p {
    font-size: 18px;
    font-weight: 600;
    margin-top: 20px;
    color: var(--text-primary);
}

.file-upload-loading-content small {
    font-size: 14px;
    color: var(--text-secondary);
}

/* 스피너 애니메이션 */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner-border {
    display: inline-block;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

[data-theme="dark"] .spinner-border {
    border-left-color: #667eea;
}

/* Toast 메시지 스타일 */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast {
    background: white;
    border-left: 4px solid #4caf50;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 300px;
    max-width: 400px;
    animation: slideInRight 0.3s ease;
}

[data-theme="dark"] .toast {
    background: #2d3748;
    color: #e2e8f0;
}

.toast.success {
    border-left-color: #4caf50;
}

.toast.error {
    border-left-color: #f44336;
}

.toast.info {
    border-left-color: #2196f3;
}

.toast-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.toast.success .toast-icon {
    color: #4caf50;
}

.toast.error .toast-icon {
    color: #f44336;
}

.toast.info .toast-icon {
    color: #2196f3;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 14px;
}

.toast-message {
    font-size: 13px;
    color: var(--text-secondary);
}

.toast-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: var(--text-secondary);
    padding: 0;
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}

.toast-close:hover {
    color: var(--text-primary);
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

/* 모달 스타일 */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: var(--bg-secondary);
    margin: 5% auto;
    padding: 0;
    border-radius: 10px;
    width: 90%;
    max-width: 800px;
    box-shadow: 0 4px 20px var(--shadow);
    animation: slideDown 0.3s ease;
    max-height: 90vh;
    overflow-y: auto;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 18px;
}

.modal-close {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
    width: 30px;
    height: 30px;
    line-height: 28px;
    text-align: center;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: rgba(255,255,255,0.2);
}

.modal-body {
    padding: 20px;
}

.modal-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 0;
}

.modal-row .form-group {
    margin-bottom: 15px;
}

.modal-row-full {
    grid-column: 1 / -1;
}

@media (max-width: 768px) {
    .modal-row {
        grid-template-columns: 1fr;
    }
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 10px;
    justify-content: flex-end;
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

    .btn {
        width: 100%;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>
</head>
<body>

<!-- 상단 네비게이션 -->
<div class="top-navbar">
    <div class="logo">
        🏭 다온텍
    </div>
    <div class="user-info">
        <button class="theme-toggle" onclick="toggleTheme()" title="다크모드 전환">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
        <a href="index.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> 목록
        </a>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1><?php echo $pageTitle; ?></h1>
        <p><?php echo $isEdit ? '발주 정보를 수정합니다' : '새로운 발주를 등록합니다'; ?></p>
    </div>

    <form method="POST" action="order_save.php" id="orderForm">
        <?php if ($isEdit): ?>
        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
        <?php endif; ?>

        <!-- 기본 정보 -->
        <div class="form-card">
            <div class="form-section-title">📋 기본 정보</div>

            <!-- Row 1: 발주번호, 발주일자, 거래처, 납품요청일 -->
            <div class="form-row">
                <div class="form-group">
                    <label>발주번호 <span class="required">*</span></label>
                    <input type="text" name="order_number" class="form-control"
                           value="<?php echo $isEdit ? htmlspecialchars($order['order_number']) : 'ON-' . date('Ymd') . '-'; ?>"
                           readonly>
                    <div class="help-text">자동 생성됩니다</div>
                </div>

                <div class="form-group">
                    <label>발주일자 <span class="required">*</span></label>
                    <input type="date" name="order_date" class="form-control"
                           value="<?php echo $isEdit ? $order['order_date'] : date('Y-m-d'); ?>" required>
            </div>

                <div class="form-group">
                    <label>거래처 <span class="required">*</span></label>
                    <div class="input-with-button">
                    <select name="customer_id" id="customer_id" class="form-control" required>
                        <option value="">거래처 선택</option>
                        <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>"
                                <?php echo ($isEdit && $order['customer_id'] == $customer['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($customer['company_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                        <button type="button" class="btn-icon" onclick="openCustomerModal()" title="거래처 등록">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>납품요청일</label>
                    <input type="date" name="delivery_date" class="form-control"
                           value="<?php echo $isEdit ? $order['delivery_date'] : ''; ?>">
                </div>
            </div>

            <!-- Row 2: 청구일, 입금일, 우선순위, 상태 -->
            <div class="form-row">
                <div class="form-group">
                    <label>청구일</label>
                    <input type="date" name="billing_date" class="form-control"
                           value="<?php echo $isEdit ? $order['billing_date'] : ''; ?>">
                    <div class="help-text">청구서 발행 날짜</div>
                </div>

                <div class="form-group">
                    <label>입금일</label>
                    <input type="date" name="payment_date" class="form-control"
                           value="<?php echo $isEdit ? $order['payment_date'] : ''; ?>">
                    <div class="help-text">대금 입금 날짜</div>
                </div>

                <div class="form-group">
                    <label>우선순위</label>
                    <select name="priority" class="form-control">
                        <option value="normal" <?php echo ($isEdit && $order['priority'] == 'normal') ? 'selected' : ''; ?>>일반</option>
                        <option value="high" <?php echo ($isEdit && $order['priority'] == 'high') ? 'selected' : ''; ?>>높음</option>
                        <option value="urgent" <?php echo ($isEdit && $order['priority'] == 'urgent') ? 'selected' : ''; ?>>긴급</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>상태</label>
                    <select name="status" class="form-control">
                        <option value="pending" <?php echo ($isEdit && $order['status'] == 'pending') ? 'selected' : ''; ?>>대기중</option>
                        <option value="processing" <?php echo ($isEdit && $order['status'] == 'processing') ? 'selected' : ''; ?>>진행중</option>
                        <option value="completed" <?php echo ($isEdit && $order['status'] == 'completed') ? 'selected' : ''; ?>>완료</option>
                        <option value="cancelled" <?php echo ($isEdit && $order['status'] == 'cancelled') ? 'selected' : ''; ?>>취소</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 발주사항 (멀티행) -->
        <div class="form-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div class="form-section-title" style="margin-bottom: 0;">📝 발주사항</div>
                <button type="button" class="btn-icon" onclick="openProductModal()" 
                        style="padding: 8px 12px; background: #4caf50; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#45a049'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='#4caf50'; this.style.transform='translateY(0)'">
                    <i class="fas fa-plus"></i> 제품관리
                </button>
            </div>

            <table class="table table-bordered" style="background: var(--bg-secondary);">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th width="8%" class="text-center">번호</th>
                        <th width="22%">제품명</th>
                        <th width="15%">규격</th>
                        <th width="10%">수량</th>
                        <th width="8%">단위</th>
                        <th width="12%">단가</th>
                        <th width="12%">금액</th>
                        <th width="13%">비고</th>
                    </tr>
                </thead>
                <tbody id="orderItemsTableBody">
                    <!-- 동적 행이 여기에 추가됨 -->
                </tbody>
            </table>

            <div style="margin-top: 10px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="addOrderItemRow()">
                    <i class="fas fa-plus"></i> 행 추가
                </button>
                <div class="help-text" style="display: inline-block; margin-left: 10px;">
                    제품 선택 시 규격, 단위, 단가가 자동으로 입력됩니다
                </div>
            </div>
        </div>

        <!-- 배송 정보 -->
        <div class="form-card">
            <div class="form-section-title">🚚 배송 정보</div>

            <div class="form-group">
                <label>납품주소</label>
                <textarea name="delivery_address" class="form-control" rows="1"
                          placeholder="납품받을 주소를 입력하세요"><?php echo $isEdit ? htmlspecialchars($order['delivery_address']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>비고</label>
                <textarea name="note" class="form-control" rows="2"
                          placeholder="특이사항이나 요청사항을 입력하세요"><?php echo $isEdit ? htmlspecialchars($order['note']) : ''; ?></textarea>
            </div>
        </div>

        <!-- 첨부파일 -->
        <div class="form-card">
            <div class="form-section-title">📎 첨부파일</div>
            
            <!-- 드롭 영역 -->
            <div id="dropZone" class="drop-zone">
                <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #667eea; margin-bottom: 15px;"></i>
                <p style="margin: 0 0 10px 0; font-size: 16px; font-weight: 500;">파일을 여기에 드래그하거나 클릭하여 선택하세요</p>
                <p style="margin: 0; font-size: 13px; color: var(--text-secondary);">최대 10MB, 이미지/문서 파일 지원</p>
                <input type="file" id="upfile" multiple style="display: none;">
            </div>
            
            <!-- 파일 목록 표시 영역 -->
            <div id="displayFile" class="file-list"></div>
            
            <!-- 임시 파일번호 (신규 등록 시 사용) -->
            <input type="hidden" id="temp_parentnum" name="temp_parentnum" value="<?php echo $fileParentNum; ?>">
        </div>

        <!-- 버튼 -->
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

<!-- Toast 컨테이너 -->
<div id="toastContainer" class="toast-container"></div>

<script>
// ===== 발주사항 멀티행 관련 함수 =====
let orderItemRowCount = 0;

/**
 * 제품관리 모달 열기
 */
function openProductModal() {
    document.getElementById('productModal').style.display = 'block';
    document.getElementById('productForm').reset();
}

/**
 * 제품관리 모달 닫기
 */
function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

/**
 * 제품 단가 포맷팅
 */
function formatProductPrice(input) {
    let value = input.value.replace(/,/g, '');
    if (value && !isNaN(value)) {
        input.value = parseInt(value).toLocaleString('ko-KR');
    }
}

/**
 * 제품 저장
 */
async function saveProduct() {
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    
    // 유효성 검사
    const productName = formData.get('product_name');
    if (!productName || productName.trim() === '') {
        showToast('제품명을 입력해주세요.', 'error');
        return;
    }
    
    try {
        console.log('제품 등록 시작...');
        
        const response = await fetch('product_save.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        console.log('응답 상태:', response.status, response.statusText);
        
        // 응답 텍스트 먼저 확인
        const responseText = await response.text();
        console.log('응답 내용:', responseText);
        
        // JSON 파싱 시도
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON 파싱 오류:', jsonError);
            showToast('서버 응답 오류가 발생했습니다.', 'error');
            return;
        }
        
        if (result.success) {
            showToast('제품이 등록되었습니다.', 'success');
            
            // 페이지 새로고침하여 제품 목록 갱신
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('오류: ' + (result.message || '제품 등록에 실패했습니다.'), 'error');
        }
    } catch (error) {
        console.error('제품 등록 오류:', error);
        showToast('제품 등록 중 오류가 발생했습니다.', 'error');
    }
}

// 모달 외부 클릭 시 닫기
window.addEventListener('click', function(event) {
    const productModal = document.getElementById('productModal');
    if (event.target === productModal) {
        closeProductModal();
    }
});

/**
 * 발주사항 행 추가
 * @param {Object} data - 행에 채울 데이터 (null이면 빈 행)
 * @param {HTMLElement} afterRow - 이 행 다음에 추가 (null이면 마지막에)
 */
function addOrderItemRow(data = null, afterRow = null) {
    const tbody = document.getElementById('orderItemsTableBody');
    const newRow = document.createElement('tr');
    orderItemRowCount++;

    // 제품 옵션 생성
    const productOptions = generateProductOptions(data ? data.product_name : '');

    newRow.innerHTML = `
        <td class="text-center">
            <div style="display: flex; align-items: center; justify-content: center; gap: 3px;">
                <span style="margin-right: 3px;">${orderItemRowCount}</span>
                <button type="button" class="btn btn-sm" 
                        style="padding: 2px 6px; font-size: 11px; background: #667eea; color: white;"
                        onclick="addOrderItemRow(null, this.closest('tr'))" title="이 행 아래에 추가">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-sm"
                        style="padding: 2px 6px; font-size: 11px; background: #28a745; color: white;"
                        onclick="copyOrderItemRow(this)" title="이 행 복사">
                    <i class="fas fa-copy"></i>
                </button>
                <button type="button" class="btn btn-sm"
                        style="padding: 2px 6px; font-size: 11px; background: #dc3545; color: white;"
                        onclick="deleteOrderItemRow(this)" title="이 행 삭제">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
        <td>
            <select class="form-control order-item-product" 
                    onchange="onProductSelect(this)"
                    style="font-size: 13px; padding: 6px;">
                <option value="">제품 선택 또는 직접입력</option>
                ${productOptions}
                <option value="__custom__">직접입력</option>
            </select>
            <input type="text" class="form-control order-item-name" 
                   value="${data ? data.product_name : ''}"
                   placeholder="제품명 직접입력"
                   style="font-size: 13px; padding: 6px; display: none; margin-top: 5px;">
        </td>
        <td>
            <input type="text" class="form-control order-item-spec"
                   value="${data ? data.spec : ''}" 
                   placeholder="규격"
                   style="font-size: 13px; padding: 6px;">
        </td>
        <td>
            <input type="number" class="form-control order-item-quantity"
                   value="${data ? data.quantity : ''}" 
                   placeholder="수량"
                   min="0" step="0.1"
                   oninput="calculateRowTotal(this)"
                   style="font-size: 13px; padding: 6px; text-align: right;">
        </td>
        <td>
            <input type="text" class="form-control order-item-unit"
                   value="${data ? data.unit : 'EA'}" 
                   placeholder="단위"
                   style="font-size: 13px; padding: 6px;">
        </td>
        <td>
            <input type="text" class="form-control order-item-price"
                   value="${data ? data.unit_price : ''}" 
                   placeholder="단가"
                   oninput="formatNumberInput(this); calculateRowTotal(this)"
                   style="font-size: 13px; padding: 6px; text-align: right;">
        </td>
        <td>
            <input type="text" class="form-control order-item-amount"
                   value="${data ? data.amount : ''}" 
                   readonly
                   style="font-size: 13px; padding: 6px; text-align: right; background: #f0f0f0;">
        </td>
        <td>
            <input type="text" class="form-control order-item-note"
                   value="${data ? data.note : ''}" 
                   placeholder="비고"
                   style="font-size: 13px; padding: 6px;">
        </td>
    `;

    // 행을 DOM에 추가
    if (afterRow) {
        afterRow.parentNode.insertBefore(newRow, afterRow.nextSibling);
    } else {
        tbody.appendChild(newRow);
    }

    updateOrderItemRowNumbers();
    
    // 데이터가 있으면 제품명 필드 표시 설정 (DOM 업데이트 후)
    if (data && data.product_name) {
        // setTimeout을 사용하여 DOM이 완전히 업데이트된 후 실행
        setTimeout(function() {
            const select = newRow.querySelector('.order-item-product');
            const input = newRow.querySelector('.order-item-name');
            
            console.log('');
            console.log('=== 제품명 로드 시작 ===');
            console.log('로드할 제품명:', data.product_name);
            console.log('select 옵션 개수:', select.options.length);
            
            // 제품 목록에서 dataset.name으로 매칭
            let found = false;
            for (let i = 0; i < select.options.length; i++) {
                const option = select.options[i];
                const optionName = option.getAttribute('data-name');
                
                console.log('옵션 ' + i + ':', {
                    value: option.value,
                    dataName: optionName,
                    text: option.text
                });
                
                // data-name 속성과 비교 (순수 제품명)
                if (optionName === data.product_name) {
                    select.value = option.value;
                    found = true;
                    console.log('✅ 제품 목록에서 찾음:', data.product_name, '-> option value:', option.value);
                    break;
                }
            }
            
            if (!found) {
                // 제품 목록에 없으면 직접입력 모드
                select.value = '__custom__';
                input.style.display = 'block';
                input.value = data.product_name;
                console.log('❌ 제품 목록에 없음, 직접입력 모드 활성화');
            }
            
            console.log('최종 select 값:', select.value);
            console.log('=== 제품명 로드 완료 ===');
            console.log('');
        }, 0);
    }
}

/**
 * 기존 데이터 로드 (수정 모드)
 */
function loadOrderItems() {
    <?php if ($isEdit && !empty($order['order_items'])): ?>
    try {
        const orderItemsData = <?php echo $order['order_items'] ?? '[]'; ?>;
        
        let parsedData;
        if (typeof orderItemsData === 'string') {
            parsedData = JSON.parse(orderItemsData);
        } else {
            parsedData = orderItemsData;
        }
        
        console.log('발주사항 로드:', parsedData);
        
        if (Array.isArray(parsedData) && parsedData.length > 0) {
            parsedData.forEach(item => {
                const rowData = {
                    product_name: item.product_name || '',
                    spec: item.spec || '',
                    quantity: item.quantity || '',
                    unit: item.unit || 'EA',
                    unit_price: item.unit_price ? parseInt(item.unit_price).toLocaleString('ko-KR') : '',
                    amount: item.amount ? parseInt(item.amount).toLocaleString('ko-KR') : '',
                    note: item.note || ''
                };
                addOrderItemRow(rowData);
            });
        } else {
            // 데이터가 없으면 빈 행 추가
            addOrderItemRow();
        }
    } catch (e) {
        console.error('발주사항 로드 오류:', e);
        addOrderItemRow(); // 오류 시 빈 행 추가
    }
    <?php else: ?>
    // 신규 등록: 빈 행 1개 추가
    addOrderItemRow();
    <?php endif; ?>
}

/**
 * 발주사항 데이터 수집 (JSON 변환용)
 */
function collectOrderItems() {
    let orderItems = [];
    
    document.querySelectorAll('#orderItemsTableBody tr').forEach(function(row) {
        const select = row.querySelector('.order-item-product');
        const customInput = row.querySelector('.order-item-name');
        
        // select에서 선택된 제품명 또는 직접입력한 제품명
        let productName = '';
        if (select.value === '__custom__') {
            productName = customInput.value;
        } else if (select.value) {
            productName = select.options[select.selectedIndex].dataset.name || '';
        }
        
        const data = {
            product_name: productName,
            spec: row.querySelector('.order-item-spec').value,
            quantity: row.querySelector('.order-item-quantity').value,
            unit: row.querySelector('.order-item-unit').value,
            unit_price: row.querySelector('.order-item-price').value.replace(/,/g, ''),
            amount: row.querySelector('.order-item-amount').value.replace(/,/g, ''),
            note: row.querySelector('.order-item-note').value
        };
        
        // 제품명이 있는 행만 추가
        if (data.product_name && data.product_name.trim()) {
            orderItems.push(data);
        }
    });
    
    return orderItems;
}

// 폼 제출 시 발주사항 데이터를 hidden input에 추가
document.getElementById('orderForm').addEventListener('submit', function(e) {
    // 발주사항 데이터 수집 및 JSON 변환
    const orderItemsData = collectOrderItems();
    
    // 최소 1개 이상의 발주사항 필요
    if (orderItemsData.length === 0) {
        showToast('최소 1개 이상의 발주사항을 입력해주세요.', 'error');
        e.preventDefault();
        return false;
    }
    
    const orderItemsJson = JSON.stringify(orderItemsData);
    
    // hidden input 생성하여 추가
    let hiddenInput = document.getElementById('order_items_data');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.id = 'order_items_data';
        hiddenInput.name = 'order_items';
        this.appendChild(hiddenInput);
    }
    hiddenInput.value = orderItemsJson;
    
    console.log('발주사항 JSON:', orderItemsJson);
});

// 페이지 로드 시 발주사항 데이터 로드 
document.addEventListener('DOMContentLoaded', function() {
    loadOrderItems();
    initFileUpload();
});

/**
 * ===== Toast 메시지 =====
 */
function showToast(message, type = 'success', fileName = '') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    
    const iconMap = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'info': 'fa-info-circle'
    };
    
    const titleMap = {
        'success': '성공',
        'error': '오류',
        'info': '알림'
    };
    
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas ${iconMap[type]}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${titleMap[type]}</div>
            <div class="toast-message">${message}${fileName ? '<br><strong>' + fileName + '</strong>' : ''}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // 3초 후 자동 제거
    setTimeout(function() {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, 3000);
}

/**
 * ===== 첨부파일 관리 =====
 */
var orderFileManager;

function initFileUpload() {
    const parentNum = document.getElementById('temp_parentnum').value;
    
    console.log('');
    console.log('=== 파일 매니저 초기화 ===');
    console.log('parentnum:', parentNum);
    console.log('========================');
    console.log('');
    
    // 파일 매니저 초기화
    orderFileManager = new GoogleDriveFileManager({
        containerId: 'dropZone',
        displayContainerId: 'displayFile',
        uploadInputId: 'upfile',
        tablename: 'daon_orders',
        item: 'attached',
        parentnum: parentNum,
        folderPath: '다온/발주',  // Google Drive 저장 경로: 다온 폴더 > 발주 폴더
        DBtable: 'picuploads',
        showDeleteButton: true,
        showDownloadButton: true,
        allowMultiple: true,
        maxFileSize: 10 * 1024 * 1024, // 10MB
        allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
        uploadUrl: '../filedrive/fileprocess.php',
        deleteUrl: '../filedrive/fileprocess.php',
        onUploadSuccess: function(response) {
            console.log('파일 업로드 성공:', response);
            
            // Toast 메시지 표시
            if (response && response.length > 0) {
                var fileNames = response.map(function(file) {
                    return file.realname || file.file;
                }).join(', ');
                
                showToast(
                    response.length + '개의 파일이 업로드되었습니다.', 
                    'success', 
                    fileNames
                );
            }
            
            // 파일 목록 다시 로드
            if (orderFileManager) {
                setTimeout(function() {
                    orderFileManager.loadFiles();
                }, 500);
            }
        },
        onUploadError: function(xhr, status, error) {
            console.error('파일 업로드 실패:', error);
            showToast('파일 업로드에 실패했습니다.', 'error');
        },
        onDeleteSuccess: function(response) {
            showToast('파일이 삭제되었습니다.', 'success');
        },
        onLoadSuccess: function(files) {
            console.log('파일 로드 완료:', files);
        },
        autoLoad: true
    });
    
    orderFileManager.init();
    
    // 드롭존 클릭 시 파일 선택 창 열기
    document.getElementById('dropZone').addEventListener('click', function() {
        document.getElementById('upfile').click();
    });
    
    // 드래그 앤 드롭 이벤트
    const dropZone = document.getElementById('dropZone');
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('drag-over');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && orderFileManager) {
            orderFileManager.uploadFiles(files);
        }
    });
}

/**
 * 제품 옵션 생성
 */
function generateProductOptions(selectedName = '') {
    const products = <?php echo json_encode($products); ?>;
    let options = '';
    
    console.log('제품 옵션 생성, selectedName:', selectedName);
    console.log('전체 제품 목록:', products);
    
    products.forEach(product => {
        const productName = product.product_name || '';
        const selected = productName === selectedName ? 'selected' : '';
        const spec = product.spec || '';
        const unit = product.unit || 'EA';
        const price = product.standard_price || '';
        const displayText = productName + (spec ? ' (' + spec + ')' : '');
        
        // HTML 이스케이프 처리
        const escapedName = productName.replace(/"/g, '&quot;');
        const escapedSpec = spec.replace(/"/g, '&quot;');
        const escapedUnit = unit.replace(/"/g, '&quot;');
        
        options += '<option value="' + product.id + '" ' +
                   'data-name="' + escapedName + '" ' +
                   'data-spec="' + escapedSpec + '" ' +
                   'data-unit="' + escapedUnit + '" ' +
                   'data-price="' + price + '" ' +
                   selected + '>' + displayText + '</option>';
        
        if (selected) {
            console.log('✅ 선택된 제품:', productName, '(ID:', product.id, ')');
        }
    });
    
    return options;
}

/**
 * 제품 선택 시 자동 입력
 */
function onProductSelect(select) {
    const row = select.closest('tr');
    const selectedOption = select.options[select.selectedIndex];
    const customInput = row.querySelector('.order-item-name');
    
    if (select.value === '__custom__') {
        // 직접입력 모드
        customInput.style.display = 'block';
        customInput.focus();
    } else if (select.value) {
        // 제품 선택 모드
        customInput.style.display = 'none';
        
        // 제품 정보 자동 입력
        row.querySelector('.order-item-spec').value = selectedOption.dataset.spec || '';
        row.querySelector('.order-item-unit').value = selectedOption.dataset.unit || 'EA';
        row.querySelector('.order-item-price').value = selectedOption.dataset.price ? 
            parseInt(selectedOption.dataset.price).toLocaleString('ko-KR') : '';
        
        // 금액 계산
        calculateRowTotal(row.querySelector('.order-item-quantity'));
    } else {
        // 선택 해제
        customInput.style.display = 'none';
    }
}

/**
 * 행 복사
 */
function copyOrderItemRow(button) {
    const row = button.closest('tr');
    const select = row.querySelector('.order-item-product');
    const customInput = row.querySelector('.order-item-name');
    
    // select에서 선택된 제품명 또는 직접입력한 제품명
    let productName = '';
    if (select.value === '__custom__') {
        productName = customInput.value;
    } else if (select.value) {
        productName = select.options[select.selectedIndex].dataset.name || '';
    }
    
    const data = {
        product_name: productName,
        spec: row.querySelector('.order-item-spec').value,
        quantity: row.querySelector('.order-item-quantity').value,
        unit: row.querySelector('.order-item-unit').value,
        unit_price: row.querySelector('.order-item-price').value,
        amount: row.querySelector('.order-item-amount').value,
        note: row.querySelector('.order-item-note').value
    };
    addOrderItemRow(data, row);
}

/**
 * 행 삭제
 */
function deleteOrderItemRow(button) {
    const tbody = document.getElementById('orderItemsTableBody');
    if (tbody.querySelectorAll('tr').length <= 1) {
        showToast('최소 1개 이상의 행이 필요합니다.', 'error');
        return;
    }
    
    if (confirm('이 행을 삭제하시겠습니까?')) {
        button.closest('tr').remove();
        updateOrderItemRowNumbers();
    }
}

/**
 * 행 번호 업데이트
 */
function updateOrderItemRowNumbers() {
    const rows = document.querySelectorAll('#orderItemsTableBody tr');
    rows.forEach((row, index) => {
        const numberSpan = row.querySelector('td:first-child span');
        if (numberSpan) {
            numberSpan.textContent = index + 1;
        }
    });
    orderItemRowCount = rows.length;
}

/**
 * 숫자 포맷팅 (콤마 추가)
 */
function formatNumberInput(input) {
    let value = input.value.replace(/,/g, '');
    if (value && !isNaN(value)) {
        input.value = parseInt(value).toLocaleString('ko-KR');
    }
}

/**
 * 행별 금액 자동 계산
 */
function calculateRowTotal(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(row.querySelector('.order-item-quantity').value) || 0;
    const unitPrice = parseInt(row.querySelector('.order-item-price').value.replace(/,/g, '')) || 0;
    const total = Math.round(quantity * unitPrice); // 소수점 계산 후 반올림
    
    row.querySelector('.order-item-amount').value = total.toLocaleString('ko-KR');
}

/**
 * ===== 거래처 등록 관련 =====
 */

/**
 * 거래처 등록 모달 열기
 */
function openCustomerModal() {
    document.getElementById('customerModal').style.display = 'block';
    // 폼 초기화
    document.getElementById('customerForm').reset();
}

/**
 * 거래처 등록 모달 닫기
 */
function closeCustomerModal() {
    document.getElementById('customerModal').style.display = 'none';
}

// 모달 외부 클릭 시 닫기
window.addEventListener('click', function(event) {
    const customerModal = document.getElementById('customerModal');
    const productModal = document.getElementById('productModal');
    
    if (event.target === customerModal) {
        closeCustomerModal();
    }
    if (event.target === productModal) {
        closeProductModal();
    }
});

/**
 * 거래처 등록 처리
 */
async function saveCustomer() {
    const form = document.getElementById('customerForm');
    const formData = new FormData(form);
    
    // 유효성 검사
    const companyName = formData.get('company_name');
    if (!companyName || companyName.trim() === '') {
        showToast('회사명을 입력해주세요.', 'error');
        return;
    }
    
    try {
        console.log('거래처 등록 시작...');
        
        const response = await fetch('customer_save.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        console.log('응답 상태:', response.status, response.statusText);
        console.log('응답 헤더:', response.headers.get('Content-Type'));
        
        // 응답 텍스트 먼저 확인
        const responseText = await response.text();
        console.log('응답 내용:', responseText);
        
        // JSON 파싱 시도
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON 파싱 오류:', jsonError);
            console.error('응답 텍스트:', responseText);
            showToast('서버 응답 오류가 발생했습니다.', 'error');
            return;
        }
        
        if (result.success) {
            showToast('거래처가 등록되었습니다.', 'success');
            
            // 거래처 목록 갱신
            await refreshCustomerList();
            
            // 방금 등록한 거래처 선택
            if (result.customer_id) {
                document.getElementById('customer_id').value = result.customer_id;
            }
            
            closeCustomerModal();
        } else {
            showToast('오류: ' + (result.message || '거래처 등록에 실패했습니다.'), 'error');
        }
    } catch (error) {
        console.error('거래처 등록 오류:', error);
        showToast('거래처 등록 중 오류가 발생했습니다.', 'error');
    }
}

/**
 * 거래처 목록 갱신
 */
async function refreshCustomerList() {
    try {
        const response = await fetch('get_customers.php');
        const customers = await response.json();
        
        const select = document.getElementById('customer_id');
        const currentValue = select.value;
        
        // 기존 옵션 제거 (첫 번째 빈 옵션 제외)
        while (select.options.length > 1) {
            select.remove(1);
        }
        
        // 새 옵션 추가
        customers.forEach(customer => {
            const option = document.createElement('option');
            option.value = customer.id;
            option.textContent = customer.company_name;
            select.appendChild(option);
        });
        
        // 이전 선택값 복원 시도
        if (currentValue) {
            select.value = currentValue;
        }
    } catch (error) {
        console.error('거래처 목록 갱신 오류:', error);
    }
}
</script>

<!-- 거래처 등록 모달 -->
<div id="customerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h2><i class="fas fa-building"></i> 거래처 등록</h2>
                <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">회사명만 입력하면 빠르게 등록할 수 있습니다</p>
            </div>
            <button class="modal-close" onclick="closeCustomerModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="customerForm">
                <!-- Row 1: 회사명, 사업자번호 -->
                <div class="modal-row">
                    <div class="form-group">
                        <label>회사명 <span class="required">*</span></label>
                        <input type="text" name="company_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>사업자번호</label>
                        <input type="text" name="business_number" class="form-control" placeholder="000-00-00000">
                    </div>
                </div>
                
                <!-- Row 2: 대표자명, 대표 연락처 -->
                <div class="modal-row">
                    <div class="form-group">
                        <label>대표자명</label>
                        <input type="text" name="ceo_name" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>대표 연락처</label>
                        <input type="text" name="tel" class="form-control" placeholder="02-1234-5678">
                    </div>
                </div>
                
                <!-- Row 3: 담당자명, 담당자 연락처 -->
                <div class="modal-row">
                    <div class="form-group">
                        <label>담당자명</label>
                        <input type="text" name="manager_name" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>담당자 연락처</label>
                        <input type="text" name="manager_tel" class="form-control" placeholder="010-1234-5678">
                    </div>
                </div>
                
                <!-- Row 4: 이메일, 팩스 -->
                <div class="modal-row">
                    <div class="form-group">
                        <label>이메일</label>
                        <input type="email" name="email" class="form-control" placeholder="example@company.com">
                    </div>
                    
                    <div class="form-group">
                        <label>팩스</label>
                        <input type="text" name="fax" class="form-control" placeholder="02-1234-5679">
                    </div>
                </div>
                
                <!-- Row 5: 주소 (Full Width) -->
                <div class="modal-row">
                    <div class="form-group modal-row-full">
                        <label>주소</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>
                
                <!-- Row 6: 비고 (Full Width) -->
                <div class="modal-row">
                    <div class="form-group modal-row-full">
                        <label>비고</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeCustomerModal()">
                <i class="fas fa-times"></i> 취소
            </button>
            <button type="button" class="btn btn-primary" onclick="saveCustomer()">
                <i class="fas fa-save"></i> 저장
            </button>
        </div>
    </div>
</div>

<!-- 제품관리 모달 -->
<div id="productModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);">
            <div>
                <h2><i class="fas fa-box-open"></i> 제품 등록</h2>
                <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">새로운 제품을 등록하세요</p>
            </div>
            <button class="modal-close" onclick="closeProductModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="productForm">
                <!-- Row 1: 제품코드, 제품명 -->
                <div class="modal-row">
                    <div class="form-group">
                        <label>제품코드</label>
                        <input type="text" name="product_code" class="form-control" placeholder="자동생성 또는 직접입력">
                        <div class="help-text">비워두면 자동 생성됩니다</div>
                    </div>
                    
                    <div class="form-group">
                        <label>제품명 <span class="required">*</span></label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                </div>
                
                <!-- Row 2: 제품구분, 규격 -->
                <div class="modal-row">
                    <div class="form-group">
                        <label>제품구분</label>
                        <select name="product_type" class="form-control">
                            <option value="">선택</option>
                            <option value="아크릴">아크릴</option>
                            <option value="LED바">LED바</option>
                            <option value="전원">전원</option>
                            <option value="부자재">부자재</option>
                            <option value="기타">기타</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>규격</label>
                        <input type="text" name="spec" class="form-control" placeholder="예: 1200x600x3T">
                    </div>
                </div>
                
                <!-- Row 3: 단위, 기준단가 -->
                <div class="modal-row">
                    <div class="form-group">
                        <label>단위</label>
                        <input type="text" name="unit" class="form-control" value="EA">
                    </div>
                    
                    <div class="form-group">
                        <label>기준단가</label>
                        <input type="text" name="standard_price" class="form-control" 
                               oninput="formatProductPrice(this)" placeholder="0">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeProductModal()">
                <i class="fas fa-times"></i> 취소
            </button>
            <button type="button" class="btn btn-primary" onclick="saveProduct()">
                <i class="fas fa-save"></i> 저장
            </button>
        </div>
    </div>
</div>

<!-- 도움말 버튼 -->
<button class="help-btn" onclick="openHelp()" title="도움말">
    <i class="fas fa-question-circle"></i>
</button>

<?php include 'help_modal.php'; ?>

<script>
// 도움말 내용 설정
document.addEventListener('DOMContentLoaded', function() {
    const helpContent = document.getElementById('helpContent');
    if (helpContent) {
        helpContent.innerHTML = `
            <div class="help-section">
                <h3><i class="fas fa-file-alt"></i> 발주 등록/수정 화면</h3>
                <p>새로운 발주를 등록하거나 기존 발주 정보를 수정하는 화면입니다.</p>
            </div>

            <div class="help-section">
                <h3><i class="fas fa-clipboard-list"></i> 기본 정보 입력</h3>
                <div class="help-step">
                    <strong>1. 발주번호</strong>
                    <p>자동으로 생성되므로 수정할 수 없습니다. 형식: ON-YYYYMMDD-순번</p>
                </div>
                <div class="help-step">
                    <strong>2. 발주일자</strong>
                    <p>발주를 등록하는 날짜입니다. 기본값은 오늘 날짜입니다.</p>
                </div>
                <div class="help-step">
                    <strong>3. 거래처 선택</strong>
                    <p>발주를 의뢰하는 거래처를 선택합니다. 활성화된 거래처만 표시됩니다.</p>
                    <p><i class="fas fa-plus" style="color: #4caf50;"></i> <strong>거래처 등록 버튼:</strong> 목록에 없는 거래처를 즉시 등록할 수 있습니다.</p>
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <li><strong>필수 정보:</strong> 회사명만 입력하면 등록 가능</li>
                        <li><strong>선택 정보:</strong> 사업자번호, 대표자명, 연락처, 담당자 정보, 이메일, 팩스, 주소, 비고</li>
                        <li>등록 후 자동으로 거래처가 선택되어 바로 사용 가능</li>
                    </ul>
                </div>
                <div class="help-step">
                    <strong>4. 납품요청일</strong>
                    <p>제품을 납품받기 원하는 날짜를 입력합니다 (선택사항).</p>
                </div>
                <div class="help-step">
                    <strong>5. 청구일</strong>
                    <p>거래처에 청구서를 발행한 날짜를 입력합니다 (선택사항).</p>
                </div>
                <div class="help-step">
                    <strong>6. 입금일</strong>
                    <p>대금이 입금된 날짜를 입력합니다 (선택사항).</p>
                </div>
                <div class="help-step">
                    <strong>7. 우선순위</strong>
                    <p>발주의 긴급도를 선택합니다: 일반 / 높음 / 긴급</p>
                </div>
                <div class="help-step">
                    <strong>8. 상태</strong>
                    <p>발주의 처리 상태를 선택합니다: 대기중 / 진행중 / 완료 / 취소</p>
                </div>
            </div>

            <div class="help-section">
                <h3><i class="fas fa-box"></i> 제품 정보 입력</h3>
                <div class="help-tip">
                    <strong><i class="fas fa-magic"></i> 빠른 입력 방법:</strong>
                    <p>제품 선택 드롭다운에서 제품을 선택하면 제품명, 제품구분, 규격, 단위, 단가가 자동으로 입력됩니다.</p>
                </div>
                <ul>
                    <li><strong>제품명</strong>: 발주할 제품의 이름 (필수)</li>
                    <li><strong>제품구분</strong>: 아크릴, LED바 등의 분류</li>
                    <li><strong>규격</strong>: 제품의 크기나 사양 (예: 1200x600x3T)</li>
                    <li><strong>수량</strong>: 발주할 수량 (필수, 소수점 입력 가능 예: LED바 1.2개)</li>
                    <li><strong>단위</strong>: 수량의 단위 (EA, 개, m 등)</li>
                    <li><strong>단가</strong>: 개당 가격 (필수, 원 단위)</li>
                    <li><strong>총액</strong>: 수량 × 단가로 자동 계산됩니다</li>
                    <li><strong>부가세 포함</strong>: 총액에 부가세 포함 여부</li>
                </ul>
            </div>

            <div class="help-section">
                <h3><i class="fas fa-truck"></i> 배송 정보</h3>
                <ul>
                    <li><strong>납품주소</strong>: 제품을 납품받을 주소 (선택사항)</li>
                    <li><strong>비고</strong>: 특이사항이나 요청사항 입력 (선택사항)</li>
                </ul>
            </div>

            <div class="help-info">
                <strong><i class="fas fa-calculator"></i> 자동 계산 기능:</strong>
                <p>수량이나 단가를 입력하면 총액이 자동으로 계산됩니다. 총액 필드는 수정할 수 없습니다.</p>
            </div>

            <div class="help-warning">
                <strong><i class="fas fa-exclamation-triangle"></i> 주의사항:</strong>
                <p>• 빨간색 별표(*)가 있는 항목은 필수 입력 항목입니다</p>
                <p>• 제품을 직접 입력할 수도 있고, 드롭다운에서 선택할 수도 있습니다</p>
                <p>• 수량은 소수점 입력 가능 (예: LED바 1.2개), 단가는 정수만 입력 가능합니다</p>
                <p>• 총액이 0원인 발주는 등록할 수 없습니다</p>
            </div>

            <div class="help-tip">
                <strong><i class="fas fa-keyboard"></i> 빠른 작업:</strong>
                <p>• <strong>등록하기/수정하기</strong>: 입력한 정보를 저장합니다</p>
                <p>• <strong>발주 시스템</strong>: 메인 화면으로 돌아갑니다</p>
                <p>• <strong>취소</strong>: 입력을 취소하고 이전 페이지로 돌아갑니다</p>
            </div>
        `;
    }
});
</script>

</body>
</html>
