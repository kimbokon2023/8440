<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

$isEdit = false;
$order = null;
$pageTitle = "신규 발주 등록";

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
    grid-template-columns: 1fr 1fr;
    gap: 15px;
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

.auto-calc {
    background: var(--hover-bg);
    border: 1px dashed #667eea;
}

.help-text {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 5px;
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

            <div class="form-row">
                <div class="form-group">
                    <label>발주번호 <span class="required">*</span></label>
                    <input type="text" name="order_number" class="form-control"
                           value="<?php echo $isEdit ? htmlspecialchars($order['order_number']) : 'ON-' . date('Ymd') . '-'; ?>"
                           readonly>
                    <div class="help-text">자동 생성됩니다 (예: ON-20251022-001)</div>
                </div>

                <div class="form-group">
                    <label>발주일자 <span class="required">*</span></label>
                    <input type="date" name="order_date" class="form-control"
                           value="<?php echo $isEdit ? $order['order_date'] : date('Y-m-d'); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>거래처 <span class="required">*</span></label>
                    <select name="customer_id" id="customer_id" class="form-control" required>
                        <option value="">거래처 선택</option>
                        <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>"
                                <?php echo ($isEdit && $order['customer_id'] == $customer['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($customer['company_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>납품요청일</label>
                    <input type="date" name="delivery_date" class="form-control"
                           value="<?php echo $isEdit ? $order['delivery_date'] : ''; ?>">
                </div>
            </div>

            <div class="form-row">
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

        <!-- 제품 정보 -->
        <div class="form-card">
            <div class="form-section-title">📦 제품 정보</div>

            <div class="form-group">
                <label>제품 선택</label>
                <select id="product_select" class="form-control">
                    <option value="">제품을 선택하세요</option>
                    <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>"
                            data-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                            data-type="<?php echo htmlspecialchars($product['product_type']); ?>"
                            data-spec="<?php echo htmlspecialchars($product['spec']); ?>"
                            data-unit="<?php echo htmlspecialchars($product['unit']); ?>"
                            data-price="<?php echo $product['standard_price']; ?>">
                        [<?php echo htmlspecialchars($product['product_type']); ?>]
                        <?php echo htmlspecialchars($product['product_name']); ?>
                        (<?php echo htmlspecialchars($product['spec']); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="help-text">제품을 선택하면 아래 정보가 자동으로 입력됩니다</div>
            </div>

            <div class="form-group">
                <label>제품명 <span class="required">*</span></label>
                <input type="text" name="product_name" id="product_name" class="form-control"
                       value="<?php echo $isEdit ? htmlspecialchars($order['product_name']) : ''; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>제품구분</label>
                    <input type="text" name="product_type" id="product_type" class="form-control"
                           value="<?php echo $isEdit ? htmlspecialchars($order['product_type']) : ''; ?>"
                           placeholder="예: 아크릴, LED바">
                </div>

                <div class="form-group">
                    <label>규격</label>
                    <input type="text" name="spec" id="spec" class="form-control"
                           value="<?php echo $isEdit ? htmlspecialchars($order['spec']) : ''; ?>"
                           placeholder="예: 1200x600x3T">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>수량 <span class="required">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-control"
                           value="<?php echo $isEdit ? intval($order['quantity']) : ''; ?>"
                           step="1" min="1" required>
                </div>

                <div class="form-group">
                    <label>단위</label>
                    <input type="text" name="unit" id="unit" class="form-control"
                           value="<?php echo $isEdit ? htmlspecialchars($order['unit']) : 'EA'; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>단가 <span class="required">*</span></label>
                    <input type="number" name="unit_price" id="unit_price" class="form-control"
                           value="<?php echo $isEdit ? intval($order['unit_price']) : ''; ?>"
                           step="1" min="0" required>
                </div>

                <div class="form-group">
                    <label>총액</label>
                    <input type="number" name="total_price" id="total_price" class="form-control auto-calc"
                           value="<?php echo $isEdit ? intval($order['total_price']) : ''; ?>" readonly>
                    <div class="help-text">수량 x 단가로 자동 계산됩니다</div>
                </div>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="vat_included" value="1"
                           <?php echo (!$isEdit || $order['vat_included']) ? 'checked' : ''; ?>>
                    부가세 포함
                </label>
            </div>
        </div>

        <!-- 배송 정보 -->
        <div class="form-card">
            <div class="form-section-title">🚚 배송 정보</div>

            <div class="form-group">
                <label>납품주소</label>
                <textarea name="delivery_address" class="form-control" rows="3"
                          placeholder="납품받을 주소를 입력하세요"><?php echo $isEdit ? htmlspecialchars($order['delivery_address']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>비고</label>
                <textarea name="note" class="form-control" rows="4"
                          placeholder="특이사항이나 요청사항을 입력하세요"><?php echo $isEdit ? htmlspecialchars($order['note']) : ''; ?></textarea>
            </div>
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

<script>
// 제품 선택 시 자동 입력
document.getElementById('product_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (this.value) {
        document.getElementById('product_name').value = selectedOption.dataset.name || '';
        document.getElementById('product_type').value = selectedOption.dataset.type || '';
        document.getElementById('spec').value = selectedOption.dataset.spec || '';
        document.getElementById('unit').value = selectedOption.dataset.unit || 'EA';
        document.getElementById('unit_price').value = selectedOption.dataset.price || '';
        calculateTotal();
    }
});

// 총액 자동 계산
function calculateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value) || 0;
    const unitPrice = parseInt(document.getElementById('unit_price').value) || 0;
    const total = quantity * unitPrice;
    document.getElementById('total_price').value = total;
}

document.getElementById('quantity').addEventListener('input', calculateTotal);
document.getElementById('unit_price').addEventListener('input', calculateTotal);

// 폼 제출 시 확인
document.getElementById('orderForm').addEventListener('submit', function(e) {
    const total = parseFloat(document.getElementById('total_price').value) || 0;
    if (total <= 0) {
        alert('총액이 0보다 커야 합니다.');
        e.preventDefault();
        return false;
    }
});
</script>

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
                </div>
                <div class="help-step">
                    <strong>4. 납품요청일</strong>
                    <p>제품을 납품받기 원하는 날짜를 입력합니다 (선택사항).</p>
                </div>
                <div class="help-step">
                    <strong>5. 우선순위</strong>
                    <p>발주의 긴급도를 선택합니다: 일반 / 높음 / 긴급</p>
                </div>
                <div class="help-step">
                    <strong>6. 상태</strong>
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
                    <li><strong>수량</strong>: 발주할 수량 (필수, 정수만 입력)</li>
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
                <p>• 수량과 단가는 정수만 입력 가능합니다 (소수점 입력 불가)</p>
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
