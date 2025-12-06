<?php
/**
 * 구매발주서 작성/수정 폼 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";
$WebSite = $base_url . '/';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    $_SESSION["url"] = "{$base_url}/orders/write_form.php";
    sleep(1);
    header("Location: {$WebSite}login/logout.php");
    exit;
}

include getDocumentRoot() . '/load_header.php';

// 모드 확인 (새로 작성 또는 수정) - ?? '' 형태
$mode = $_REQUEST["mode"] ?? '';
$id = $_REQUEST["id"] ?? 0;
$id = (int)$id;

// iframe 모드 확인 (모달에서 열렸는지)
$iframe_mode = isset($_GET['iframe']) && $_GET['iframe'] === '1';

// 구매카트 항목 파라미터 확인
$cart_items_param = $_GET['cart_items'] ?? '';
$cart_items_data = [];

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>데이터베이스 연결 실패: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}

// 구매카트 항목이 있으면 데이터 조회
if (!empty($cart_items_param)) {
    $item_nums = explode(',', $cart_items_param);
    $item_nums = array_filter(array_map('intval', $item_nums));
    
    if (!empty($item_nums)) {
        $DB = $_SESSION["DB"] ?? 'mirae8440';
        $placeholders = implode(',', array_fill(0, count($item_nums), '?'));
        $sql = "SELECT * FROM {$DB}.eworks 
                WHERE num IN ({$placeholders}) 
                AND is_deleted IS NULL 
                AND eworks_item IN ('원자재구매', '부자재구매')
                ORDER BY num ASC";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($item_nums);
            $cart_items_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("구매카트 항목 조회 오류: " . $e->getMessage());
        }
    }
}

// 수정 모드일 경우 기존 데이터 조회
$order_data = null;
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `orders` WHERE id = :id AND is_deleted = 0");
    $stmt->execute([':id' => $id]);
    $order_data = $stmt->fetch();

    if (!$order_data) {
        echo "<script>alert('존재하지 않는 발주서입니다.'); location.href='index.php';</script>";
        exit;
    }
}

// 상태 옵션
$status_options = [
    'draft' => '임시저장',
    'sent' => '발송완료',
    'completed' => '완료'
];
$current_status = $order_data['status'] ?? 'draft';

// 제목 설정
if ($mode === 'copy') {
    $title_message = "(데이터복사) 발주서 작성";
} else if ($id > 0) {
    $title_message = "발주서 수정";
} else {
    $title_message = "발주서 작성";
}

// 자동 발주서 번호 생성
$order_no = date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
if ($order_data && isset($order_data['order_no']) && $order_data['order_no']) {
    $order_no = $order_data['order_no'];
}
?>

<title><?=$title_message?></title>
<!-- Tabulator CSS -->
<link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<?php
$body_class = $iframe_mode ? 'iframe-mode' : 'default-mode';
?>

<style>
/* stylelint-disable */
/* 기존 시스템 톤앤매너에 맞춘 발주서 스타일 */
:root {
    --dashboard-primary: #f8fafc;
    --dashboard-secondary: #f1f5f9;
    --dashboard-accent: #64748b;
    --dashboard-text: #334155;
    --dashboard-border: #e2e8f0;
    --dashboard-shadow: 0 1px 3px rgba(51, 65, 85, 0.04);
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #ffffff;
    color: var(--dashboard-text);
}

body.iframe-mode {
    --dashboard-primary: #f8f9fa;
    --dashboard-secondary: #e9ecef;
    --dashboard-accent: #6c757d;
    --dashboard-text: #333333;
    --dashboard-border: #dee2e6;
    --dashboard-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin: 0;
    padding: 0;
    overflow: visible;
}

.order-container {
    max-width: 100%;
    margin: 10px auto;
    border: 1px solid var(--dashboard-border);
    border-radius: 8px;
    box-shadow: var(--dashboard-shadow);
    background: white;
    font-size: 16px;
}

body.iframe-mode .order-container {
    margin: 0;
    padding: 10px;
    border: none;
    border-radius: 0;
    box-shadow: none;
}

.order-header {
    background: var(--dashboard-secondary);
    color: #000;
    padding: 12px 20px;
    font-weight: 500;
    font-size: 18px;
    border-bottom: 1px solid var(--dashboard-border);
}

body.iframe-mode .order-header {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: #ffffff;
}

.order-title {
    text-align: center;
    font-size: 28px;
    font-weight: 600;
    padding: 20px 0;
    border-bottom: 1px solid var(--dashboard-border);
    margin: 20px 0;
    color: var(--dashboard-text);
}

.order-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    padding: 0 20px;
}

.info-section {
    border: 1px solid var(--dashboard-border);
    border-radius: 6px;
    overflow: hidden;
}

.info-section.orderer-section .info-label,
.info-section.orderer-section .info-value {
    font-size: 14px;
}

.info-section.orderer-section input,
.info-section.orderer-section select {
    font-size: 14px;
}

.section-header {
    background: var(--dashboard-secondary);
    color: var(--dashboard-text);
    padding: 8px 12px;
    text-align: center;
    font-weight: 500;
    border-bottom: 1px solid var(--dashboard-border);
}

body.iframe-mode .section-header {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    color: #495057;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: 100px 1fr;
}

.info-grid.grid-4 {
    grid-template-columns: 120px 1fr 120px 1fr;
}

.info-grid .info-label.colspan-1,
.info-grid .info-value.colspan-1 {
    grid-column: span 1;
}

.info-grid .info-label.colspan-2,
.info-grid .info-value.colspan-2 {
    grid-column: span 2;
}

.info-grid .info-label.colspan-3,
.info-grid .info-value.colspan-3 {
    grid-column: span 3;
}

.info-label {
    background: var(--dashboard-primary);
    padding: 8px 12px;
    border-right: 1px solid var(--dashboard-border);
    border-bottom: 1px solid var(--dashboard-border);
    font-weight: 500;
    color: var(--dashboard-text);
    font-size: 14px;
}

.info-value {
    padding: 8px 12px;
    border-bottom: 1px solid var(--dashboard-border);
    background: white;
}

.info-value input, .info-value select {
    width: 100%;
    border: none;
    outline: none;
    font-size: 16px;
    color: var(--dashboard-text);
    background: transparent;
}

.info-value input:focus {
    background: var(--dashboard-primary);
    border-radius: 3px;
    padding: 2px 4px;
}

.info-value select:focus {
    background: var(--dashboard-primary);
    border-radius: 3px;
}

.items-section {
    margin: 20px;
}

.items-header {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 10px;
}

.items-header .btn-add-row {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border: none;
    border-radius: 6px;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: #fff;
    font-size: 13px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(108, 117, 125, 0.3);
}

.items-header .btn-add-row:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(108, 117, 125, 0.4);
}

.row-actions {
    display: flex;
    align-items: center;
    gap: 4px;
}

.row-actions .row-index {
    font-weight: 600;
    width: 18px;
    text-align: center;
}

.row-action-btn {
    border: none;
    background: transparent;
    color: #6c757d;
    cursor: pointer;
    font-size: 12px;
    padding: 2px;
    line-height: 1;
}

.row-action-btn:hover {
    color: #495057;
}

.tabulator {
    border: 1px solid var(--dashboard-border);
    border-radius: 6px;
    overflow: hidden;
    box-shadow: var(--dashboard-shadow);
    background: #ffffff;
}

.tabulator .tabulator-tableHolder {
    background: #ffffff;
}

.tabulator .tabulator-table {
    background: #ffffff;
}

.tabulator .tabulator-header {
    background: var(--dashboard-secondary) !important;
    color: var(--dashboard-text) !important;
    border-bottom: 1px solid var(--dashboard-border) !important;
    display: table-header-group !important;
    visibility: visible !important;
}

.tabulator .tabulator-headers {
    display: table-header-group !important;
    visibility: visible !important;
}

.tabulator .tabulator-col {
    display: table-cell !important;
    visibility: visible !important;
}

.tabulator .tabulator-col-title {
    color: var(--dashboard-text) !important;
    font-weight: 500;
    font-size: 14px !important;
    text-align: center;
    padding: 8px 4px;
}

.summary-section {
    margin: 20px;
}

.delivery-info {
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.delivery-grid {
    display: grid;
    grid-template-columns: 120px 1fr 120px 1fr;
}

.delivery-grid .info-label,
.delivery-grid .info-value {
    border-bottom: 1px solid var(--dashboard-border);
}

.delivery-grid .info-label:nth-last-child(-n+4),
.delivery-grid .info-value:nth-last-child(-n+4) {
    border-bottom: none;
}

.note-section {
    margin: 20px;
    border: 1px solid #ddd;
}

.note-header {
    background: #f5f5f5;
    padding: 5px 10px;
    border-bottom: 1px solid #ddd;
}

.note-content {
    padding: 10px;
}

.note-content textarea {
    width: 100%;
    height: 80px;
    border: none;
    outline: none;
    resize: vertical;
}

.action-buttons {
    text-align: center;
    padding: 20px;
    border-top: 1px solid #ddd;
}

.btn {
    padding: 8px 20px;
    margin: 0 4px;
    border: 1px solid var(--dashboard-border);
    background: white;
    cursor: pointer;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.2s ease;
    color: var(--dashboard-text);
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: var(--dashboard-shadow);
}

.btn-primary {
    background: var(--dashboard-accent);
    color: white;
    border-color: var(--dashboard-accent);
}

.btn-primary:hover {
    background: #475569;
    border-color: #475569;
}

body.iframe-mode .btn-primary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border-color: #6c757d;
}

body.iframe-mode .btn-primary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    border-color: #5a6268;
}

.btn-success {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.btn-success:hover {
    background: #059669;
    border-color: #059669;
}

.btn-secondary {
    background: var(--dashboard-primary);
    color: var(--dashboard-text);
    border-color: var(--dashboard-border);
}

.btn-secondary:hover {
    background: var(--dashboard-secondary);
}

.btn-danger {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

.btn-danger:hover {
    background: #dc2626;
    border-color: #dc2626;
}

.btn-sm {
    padding: 6px 16px;
    font-size: 14px;
    border-radius: 4px;
}

.header-buttons .btn {
    margin: 0;
}

/* Tabulator 자동 계산 필드 스타일 */
.tabulator-cell[tabulator-field="공급가액"],
.tabulator-cell[tabulator-field="세액"] {
    background-color: var(--dashboard-primary) !important;
    color: var(--dashboard-text);
    font-weight: 500;
}

.tabulator-cell[tabulator-field="공급가액"]:hover,
.tabulator-cell[tabulator-field="세액"]:hover {
    background-color: var(--dashboard-secondary) !important;
    cursor: not-allowed;
}

.tabulator .tabulator-row {
    border-bottom: 1px solid var(--dashboard-border);
    background: #ffffff;
}

.tabulator .tabulator-row:nth-child(even) {
    background: #f9fbff;
}

.tabulator .tabulator-row:hover {
    background: var(--dashboard-primary);
}

/* 인쇄 스타일 */
@media print {
    .action-buttons,
    .header-buttons {
        display: none;
    }
    .order-container {
        border: none;
        box-shadow: none;
    }
}
/* stylelint-enable */
</style>

</head>
<body class="<?php echo $body_class; ?>">
<?php if (!$iframe_mode): ?>
    <?php include getDocumentRoot() . '/myheader.php'; ?>
<?php endif; ?>

<div class="order-container">
    <?php if (!$iframe_mode): ?>
    <div class="order-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <span><?php echo $title_message; ?></span>
                <button type="button" class="btn btn-sm" onclick="location.href='write_form.php'">새 발주서</button>
                <button type="button" class="btn btn-sm" onclick="location.href='index.php'">목록</button>
            </div>
            <div class="header-buttons" style="display: flex; gap: 5px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="saveOrder()">저장</button>
                <?php if ($id > 0 && $mode !== 'copy'): ?>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteOrder()">삭제</button>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelOrder()">취소</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <form id="orderForm" method="POST" action="insert.php">
        <?php if ($id > 0 && $mode !== 'copy'): ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="action" value="update">
        <?php else: ?>
            <input type="hidden" name="action" value="insert">
        <?php endif; ?>
        <?php if (!empty($cart_items_param)): ?>
            <input type="hidden" name="cart_items" value="<?php echo htmlspecialchars($cart_items_param); ?>">
        <?php endif; ?>
        
        <!-- 거래처 ID (이메일 조회용) -->
        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $order_data ? ($order_data['customer_id'] ?? '') : ''; ?>">

        <?php if (!$iframe_mode): ?>
        <div class="order-title">발 주 서</div>
        <?php endif; ?>

        <div class="order-info">
            <!-- 왼쪽: 공급자 정보 (거래처) -->
            <div class="info-section">
                <div class="info-grid grid-4">
                    <div class="info-label colspan-1">프로젝트/현장</div>
                    <div class="info-value colspan-3">
                        <input type="text" name="project_site" id="project_site" value="<?php 
                            if (!empty($cart_items_data)) {
                                // 구매카트 항목의 첫 번째 항목의 현장명(outworkplace)을 프로젝트/현장에 입력
                                echo htmlspecialchars($cart_items_data[0]['outworkplace'] ?? '');
                            } else {
                                echo $order_data ? htmlspecialchars($order_data['project_site'] ?? '') : '';
                            }
                        ?>" placeholder="프로젝트/현장명을 입력하세요" autocomplete="off">
                    </div>
                    <div class="info-label colspan-1">거래처명</div>
                    <div class="info-value colspan-3" style="display: flex; gap: 0.5rem; align-items: center;">
                        <input type="text" name="contact_name" id="contact_name" value="<?php 
                            if (!empty($cart_items_data)) {
                                // 구매카트 항목의 첫 번째 항목의 supplier(공급/제조사) 값을 거래처명에 입력
                                echo htmlspecialchars($cart_items_data[0]['supplier'] ?? '');
                            } else {
                                echo $order_data ? htmlspecialchars($order_data['contact_name'] ?? '') : '';
                            }
                        ?>" placeholder="거래처명을 입력하세요" style="flex: 1;" autocomplete="off">
                        <button type="button" class="btn btn-sm btn-primary" id="searchCustomerBtn" style="white-space: nowrap;">
                            <i class="bi bi-search"></i> 검색
                        </button>
                    </div>
                    <div class="info-label">발주일자</div>
                    <div class="info-value">
                        <input type="date" name="issue_date" value="<?php echo $order_data ? ($order_data['issue_date'] ?? date('Y-m-d')) : date('Y-m-d'); ?>" required autocomplete="off">
                    </div>
                    <div class="info-label">진행상태</div>
                    <div class="info-value">
                        <select name="status" id="status" required>
                            <?php foreach ($status_options as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo $current_status === $value ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="info-label">전화번호</div>
                    <div class="info-value">
                        <input type="text" name="phone" value="<?php echo $order_data ? htmlspecialchars($order_data['phone'] ?? '') : ''; ?>" placeholder="전화번호" autocomplete="off">
                    </div>
                    <div class="info-label">팩스번호</div>
                    <div class="info-value">
                        <input type="text" name="fax" value="<?php echo $order_data ? htmlspecialchars($order_data['fax'] ?? '') : ''; ?>" placeholder="팩스번호" autocomplete="off">
                    </div>
                    <div class="info-label">사업자번호</div>
                    <div class="info-value">
                        <input type="text" name="business_registration_number" id="business_registration_number" value="<?php echo $order_data ? htmlspecialchars($order_data['business_registration_number'] ?? '') : ''; ?>" placeholder="사업자등록번호" autocomplete="off">
                    </div>
                    <div class="info-label" style="border-bottom: none;">합계금액</div>
                    <div class="info-value" style="border-bottom: none;">
                        <input type="text" id="totalAmount" name="total_amount" value="₩0" readonly style="background: #f8f9fa; font-weight: bold;">
                    </div>
                </div>
            </div>

            <!-- 오른쪽: 발주자 정보 (미래기업) -->
            <div class="info-section orderer-section">
                <div class="section-header">발주자</div>
                <div class="info-grid grid-4">
                    <div class="info-label">등록번호</div>
                    <div class="info-value">
                        <input type="text" name="supplier_code" value="722-88-00035" readonly style="background: #f8f9fa;">
                    </div>
                    <div class="info-label">상호</div>
                    <div class="info-value">
                        <input type="text" name="supplier_name" value="주식회사미래기업" readonly style="background: #f8f9fa;" required>
                    </div>
                    <div class="info-label colspan-1">주소</div>
                    <div class="info-value colspan-3">
                        <input type="text" name="supplier_address" value="경기도 김포시 양촌읍 흥신리 220-27 (흥신리)" readonly style="background: #f8f9fa;">
                    </div>
                    <div class="info-label">업태</div>
                    <div class="info-value">
                        <input type="text" name="business_type" value="제조업" readonly style="background: #f8f9fa;">
                    </div>
                    <div class="info-label">종목</div>
                    <div class="info-value">
                        <input type="text" name="business_item" value="엘리베이터인테리어제품" readonly style="background: #f8f9fa;">
                    </div>
                    <div class="info-label">전화번호</div>
                    <div class="info-value">
                        <input type="text" name="supplier_phone" value="031-983-8440" readonly style="background: #f8f9fa;">
                    </div>
                    <div class="info-label">팩스번호</div>
                    <div class="info-value">
                        <input type="text" name="supplier_fax" value="031-982-8443" readonly style="background: #f8f9fa;">
                    </div>
                </div>
            </div>
        </div>

        <!-- 품목 그리드 -->
        <div class="items-section" style="overflow-x: auto; margin: 20px 0;">
            <div class="items-header">
                <button type="button" class="btn-add-row" onclick="addRow()">+ 행 추가</button>
            </div>
            <div id="orderItemsTable" style="min-width: 1000px;"></div>
        </div>

        <!-- 하단 정보 -->
        <div class="summary-section">
            <div class="delivery-info">
                <div class="delivery-grid">
                    <div class="info-label">납기일자</div>
                    <div class="info-value">
                        <input type="date" name="delivery_date" id="delivery_date" value="<?php 
                            if (!empty($cart_items_data)) {
                                $first_requestdate = $cart_items_data[0]['requestdate'] ?? '';
                                if (!empty($first_requestdate) && $first_requestdate !== '0000-00-00') {
                                    echo htmlspecialchars(substr($first_requestdate, 0, 10));
                                }
                            } else {
                                echo $order_data ? ($order_data['delivery_date'] ?? '') : '';
                            }
                        ?>">
                    </div>
                    <div class="info-label">유효일자</div>
                    <div class="info-value">
                        <input type="date" name="valid_date" value="<?php echo $order_data ? ($order_data['valid_date'] ?? '') : ''; ?>">
                    </div>
                    <div class="info-label">납품장소</div>
                    <div class="info-value">
                        <input type="text" name="delivery_location" value="<?php echo $order_data ? htmlspecialchars($order_data['delivery_location'] ?? '') : ''; ?>">
                    </div>
                    <div class="info-label">결제조건</div>
                    <div class="info-value">
                        <input type="text" name="payment_terms" value="<?php echo $order_data ? htmlspecialchars($order_data['payment_terms'] ?? '') : ''; ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 비고 -->
        <div class="note-section">
            <div class="note-header">비고</div>
            <div class="note-content">
                <textarea name="note" placeholder="정보를 입력합니다."><?php echo $order_data ? htmlspecialchars($order_data['note'] ?? '') : ''; ?></textarea>
            </div>
        </div>

    </form>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var isIframeMode = <?php echo $iframe_mode ? 'true' : 'false'; ?>;
    
    function notifyParent(type, payload) {
        if (!isIframeMode || !window.parent) {
            return;
        }
        
        try {
            window.parent.postMessage({
                scope: 'orderModule',
                type: type,
                payload: payload || {}
            }, '*');
        } catch (error) {
            console.warn('부모 창 메시지 전송 실패:', error);
        }
    }
    
// 전역 변수
var orderTable;
var orderItems = [];

function createEmptyRowData(index) {
    return {
        순번: index,
        품목: '',
        규격: '',
        수량: '',
        단가: '',
        공급가액: '',
        세액: '',
        비고: ''
    };
}

function renumberRows() {
    if (!orderTable) return;
    var rows = orderTable.getRows();
    rows.forEach(function(row, idx) {
        row.update({ "순번": idx + 1 });
    });
}

function handleRowAction(action, row) {
    if (!orderTable) return;

    var referenceRow = row;
    var insertAfter = function(data) {
        orderTable.addRow(data, false, referenceRow);
        renumberRows();
    };

    if (action === 'add') {
        var newData = createEmptyRowData(orderTable.getData().length + 1);
        insertAfter(newData);
    } else if (action === 'copy') {
        var copied = Object.assign({}, row.getData());
        copied.순번 = orderTable.getData().length + 1;
        insertAfter(copied);
        setTimeout(updateTotalAmount, 50);
    } else if (action === 'delete') {
        if (orderTable.getData().length <= 1) {
            alert('최소 한 개의 행은 필요합니다.');
            return;
        }
        row.delete();
        renumberRows();
        setTimeout(updateTotalAmount, 50);
    }
}

// 초기 데이터
<?php if (!empty($cart_items_data)): ?>
// 구매카트에서 가져온 데이터로 초기화
<?php
$cart_order_items = [];
$index = 1;
foreach ($cart_items_data as $item): 
    $quantity = floatval($item['steelnum'] ?? 0);
    $unit_price = floatval(str_replace(',', '', $item['suppliercost'] ?? 0));
    $supply_amount = $quantity * $unit_price;
    $tax = round($supply_amount * 0.1);
    
    // 품목명 생성: 현장명/품목명
    $item_name = '';
    $outworkplace = $item['outworkplace'] ?? '';
    $steel_item = $item['steel_item'] ?? '';
    
    if (!empty($outworkplace) && !empty($steel_item)) {
        $item_name = $outworkplace . '/' . $steel_item;
    } elseif (!empty($outworkplace)) {
        $item_name = $outworkplace;
    } else {
        $item_name = $steel_item;
    }

    $cart_order_items[] = [
        '순번' => $index,
        '품목' => $item_name,
        '규격' => $item['spec'] ?? '',
        '수량' => $quantity,
        '단가' => $unit_price,
        '공급가액' => $supply_amount,
        '세액' => $tax,
        '비고' => $item['request_comment'] ?? ''
    ];
    $index++;
endforeach;
?>
orderItems = <?php echo json_encode($cart_order_items, JSON_UNESCAPED_UNICODE); ?>;
<?php elseif ($order_data && isset($order_data['order_items']) && $order_data['order_items']): ?>
orderItems = <?php 
    $items = json_decode($order_data['order_items'] ?? '[]', true);
    if (!is_array($items)) $items = [];
    echo json_encode($items, JSON_UNESCAPED_UNICODE); 
?>;
<?php else: ?>
orderItems = [
    {순번: 1, 품목: '', 규격: '', 수량: '', 단가: '', 공급가액: '', 세액: '', 비고: ''},
    {순번: 2, 품목: '', 규격: '', 수량: '', 단가: '', 공급가액: '', 세액: '', 비고: ''},
    {순번: 3, 품목: '', 규격: '', 수량: '', 단가: '', 공급가액: '', 세액: '', 비고: ''},
    {순번: 4, 품목: '', 규격: '', 수량: '', 단가: '', 공급가액: '', 세액: '', 비고: ''}
];
<?php endif; ?>

// Tabulator 초기화
document.addEventListener('DOMContentLoaded', function() {
    orderTable = new Tabulator("#orderItemsTable", {
        data: orderItems,
        layout: "fitColumns", // 컬럼을 컨테이너에 맞춤
        height: "300px",
        headerVisible: true, // 헤더 표시
        autoResize: true, // 자동 리사이즈
        resizableColumns: true, // 컬럼 리사이즈 허용
        columns: [
            {title: "순번", field: "순번", width: 90, hozAlign: "center", headerHozAlign: "center", resizable: false,
             formatter: function(cell) {
                 var row = cell.getRow();
                 var position = cell.getValue();
                 return '<div class="row-actions">' +
                        '<span class="row-index">' + position + '</span>' +
                        '<button type="button" class="row-action-btn" data-action="add">＋</button>' +
                        '<button type="button" class="row-action-btn" data-action="copy">⧉</button>' +
                        '<button type="button" class="row-action-btn" data-action="delete">×</button>' +
                        '</div>';
             },
             cellClick: function(e, cell) {
                 var action = e.target.getAttribute('data-action');
                 if (!action) return;
                 e.stopPropagation();
                 handleRowAction(action, cell.getRow());
             }},
            {title: "품목", field: "품목", width: 300, hozAlign: "left", headerHozAlign: "center", editor: "input", resizable: true,
             cellClick: function() { return true; }},
            {title: "규격", field: "규격", width: 200, hozAlign: "left", headerHozAlign: "center", editor: "input", resizable: true,
             cellClick: function() { return true; }},
            {title: "수량", field: "수량", width: 80, hozAlign: "right", headerHozAlign: "center", editor: "input", validator: "numeric", resizable: false,
             cellEdited: function(cell) {
                 var row = cell.getRow();
                 var data = row.getData();
                 var 수량 = parseFloat(String(data.수량).replace(/,/g, '')) || 0;
                 var 단가 = parseFloat(String(data.단가).replace(/,/g, '')) || 0;
                 if (수량 && 단가) {
                     var 공급가액 = Math.round(수량 * 단가);
                     var 세액 = Math.round(공급가액 * 0.1);
                     row.update({공급가액: 공급가액, 세액: 세액});
                     setTimeout(updateTotalAmount, 50);
                 }
             },
             cellClick: function() { return true; }},
            {title: "단가", field: "단가", width: 100, hozAlign: "right", headerHozAlign: "center", 
             editor: function(cell, onRendered, success, cancel) {
                 var input = document.createElement("input");
                 input.type = "text";
                 input.style.width = "100%";
                 input.style.padding = "4px";
                 // 초기값 포맷팅
                 var initialValue = cell.getValue();
                 input.value = initialValue ? Number(initialValue).toLocaleString() : '';
                 
                 // 입력 시 동적 포맷팅
                 input.addEventListener("input", function() {
                     var value = input.value.replace(/[^\d]/g, ''); // 숫자만 남김
                     if (value) {
                         input.value = Number(value).toLocaleString();
                     } else {
                         input.value = '';
                     }
                 });

                 input.addEventListener("blur", function() {
                     var value = input.value.replace(/,/g, '');
                     var numValue = parseFloat(value) || 0;
                     success(numValue);
                 });
                 input.addEventListener("keydown", function(e) {
                     if (e.key === "Enter") {
                         var value = input.value.replace(/,/g, '');
                         var numValue = parseFloat(value) || 0;
                         success(numValue);
                     } else if (e.key === "Escape") {
                         cancel();
                     }
                 });
                 onRendered(function() {
                     input.focus();
                     // input.select(); // 포맷팅된 상태에서는 select가 불편할 수 있음 (선택사항)
                 });
                 return input;
             },
             formatter: function(cell) {
                 var value = cell.getValue();
                 return value ? Number(value).toLocaleString() : '';
             },
             cellEdited: function(cell) {
                 var row = cell.getRow();
                 var data = row.getData();
                 var 수량 = parseFloat(String(data.수량).replace(/,/g, '')) || 0;
                 var 단가 = parseFloat(String(data.단가).replace(/,/g, '')) || 0;
                 if (수량 && 단가) {
                     var 공급가액 = Math.round(수량 * 단가);
                     var 세액 = Math.round(공급가액 * 0.1);
                     row.update({공급가액: 공급가액, 세액: 세액});
                     setTimeout(updateTotalAmount, 50);
                 }
             },
             cellClick: function() { return true; }},
            {title: "공급가액", field: "공급가액", width: 120, hozAlign: "right", headerHozAlign: "center", editor: false, validator: "numeric", resizable: true,
             formatter: function(cell) {
                 var value = cell.getValue();
                 return value ? Number(value).toLocaleString() : '';
             },
             cellClick: function(e, cell) {
                 console.log('공급가액은 자동 계산됩니다 (수량 × 단가)');
                 return false;
             }},
            {title: "세액", field: "세액", width: 100, hozAlign: "right", headerHozAlign: "center", editor: false, validator: "numeric", resizable: true,
             formatter: function(cell) {
                 var value = cell.getValue();
                 return value ? Number(value).toLocaleString() : '';
             },
             cellClick: function(e, cell) {
                 console.log('세액은 자동 계산됩니다 (공급가액의 10%)');
                 return false;
             }},
            {title: "비고", field: "비고", widthGrow: 1, hozAlign: "left", headerHozAlign: "center", editor: "input", resizable: true}
        ],
        cellEdited: function(cell) {
            console.log(`📝 [DEBUG] 셀 편집: ${cell.getField()} = ${cell.getValue()}`);
            // 컬럼별 cellEdited에서 계산하므로 여기서는 전체 합계만 업데이트
            if (cell.getField() !== '공급가액' && cell.getField() !== '세액') {
                setTimeout(updateTotalAmount, 100);
            }
        },
        rowAdded: function(row) {
            console.log('📋 [DEBUG] 새 행 추가됨');
        }
    });

    // 초기 로드 시 합계 계산
    setTimeout(function() {
        updateTotalAmount();
    }, 100);
});

// 계산 업데이트
function updateCalculations(cell) {
    var row = cell.getRow();
    var data = row.getData();
    
    // 수량 또는 단가가 변경된 경우 자동 계산
    if (cell.getField() === '수량' || cell.getField() === '단가') {
        // 입력값 정리 (쉼표 제거 후 숫자 변환)
        var 수량 = data.수량;
        var 단가 = data.단가;
        
        if (typeof 수량 === 'string') {
            수량 = parseFloat(수량.replace(/,/g, '')) || 0;
        } else {
            수량 = parseFloat(수량) || 0;
        }
        
        if (typeof 단가 === 'string') {
            단가 = parseFloat(단가.replace(/,/g, '')) || 0;
        } else {
            단가 = parseFloat(단가) || 0;
        }
        
        // 공급가액 = 수량 × 단가 (정확한 계산)
        var 공급가액 = Math.round(수량 * 단가);
        
        // 세액 = 공급가액 × 10% (부가세, 소수점 반올림)
        var 세액 = Math.round(공급가액 * 0.1);
        
        console.log('📊 [DEBUG] 자동 계산 상세:');
        console.log('   - 수량: ' + 수량 + ' (원본: ' + data.수량 + ')');
        console.log('   - 단가: ' + 단가.toLocaleString() + ' (원본: ' + data.단가 + ')');
        console.log('   - 공급가액: ' + 공급가액.toLocaleString() + ' (' + 수량 + ' × ' + 단가 + ')');
        console.log('   - 세액: ' + 세액.toLocaleString() + ' (공급가액의 10%)');
        
        // 한 번에 업데이트 (이벤트 루프 방지)
        row.update({
            공급가액: 공급가액,
            세액: 세액
        });
        
        // 행 별 계산 완료 후 전체 합계 업데이트
        setTimeout(function() {
            updateTotalAmount();
        }, 10);
    } else {
        // 다른 필드 변경 시에도 합계 업데이트
        setTimeout(function() {
            updateTotalAmount();
        }, 10);
    }
}

// 전체 합계 업데이트
function updateTotalAmount() {
    var data = orderTable.getData();
    var totalSupply = 0; // 공급가액 합계
    var totalTax = 0;    // 세액 합계
    var grandTotal = 0;  // 총 합계
    
    data.forEach(function(row) {
        var 공급가액 = parseFloat(row.공급가액) || 0;
        var 세액 = parseFloat(row.세액) || 0;
        
        totalSupply += 공급가액;
        totalTax += 세액;
    });
    
    grandTotal = totalSupply + totalTax;
    
    // 합계금액 필드 업데이트
    document.getElementById('totalAmount').value = '₩' + grandTotal.toLocaleString();
    
    console.log('💰 [DEBUG] 합계 계산: 공급가액(' + totalSupply.toLocaleString() + ') + 세액(' + totalTax.toLocaleString() + ') = 총액(' + grandTotal.toLocaleString() + ')');
}

// 행 추가
window.addRow = function() {
    var data = orderTable.getData();
    var newRowNum = data.length + 1;
    orderTable.addRow(createEmptyRowData(newRowNum), false);
    renumberRows();
};

// AJAX 저장 함수 - 디버그 코드 포함
window.saveOrder = function() {
    console.log('🚀 [DEBUG] 저장 프로세스 시작');
    
    if (!validateForm()) {
        console.error('❌ [DEBUG] 폼 검증 실패');
        return;
    }
    
    console.log('✅ [DEBUG] 폼 검증 통과');
    
    // 로딩 표시
    showLoadingSpinner();
    
    try {
        // 폼 데이터 수집
        var formData = collectFormData();
        console.log('📋 [DEBUG] 수집된 폼 데이터:', formData);
        
        // AJAX 요청
        fetch('insert.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(function(response) {
            console.log('📡 [DEBUG] 서버 응답 상태:', response.status, response.statusText);
            
            if (!response.ok) {
                throw new Error('HTTP Error: ' + response.status + ' ' + response.statusText);
            }
            
            return response.text();
        })
        .then(function(data) {
            console.log('📥 [DEBUG] 서버 응답 데이터:', data);
            
            hideLoadingSpinner();
            
            // 응답 데이터 파싱
            try {
                // JSON 응답인지 확인
                var jsonData = JSON.parse(data);
                console.log('📊 [DEBUG] JSON 파싱 성공:', jsonData);
                
                handleJsonResponse(jsonData);
            } catch (e) {
                // HTML 응답 처리
                console.log('📄 [DEBUG] HTML 응답 처리');
                handleHtmlResponse(data);
            }
        })
        .catch(function(error) {
            console.error('❌ [DEBUG] AJAX 오류:', error);
            hideLoadingSpinner();
            showErrorMessage('저장 중 오류가 발생했습니다: ' + error.message);
        });
        
    } catch (error) {
        console.error('❌ [DEBUG] 폼 데이터 수집 오류:', error);
        hideLoadingSpinner();
        showErrorMessage('폼 데이터 처리 중 오류가 발생했습니다.');
    }
};

// 폼 데이터 수집
function collectFormData() {
    console.log('📋 [DEBUG] 폼 데이터 수집 시작');
    
    var formData = new URLSearchParams();
    
    // 기본 필드들
    var basicFields = [
        'action', 'id', 'order_no', 'issue_date', 'supplier_code',
        'supplier_name', 'supplier_address', 'business_type', 'business_item',
        'supplier_phone', 'supplier_fax', 'contact_name', 'business_registration_number',
        'phone', 'fax', 'project_site', 'delivery_date', 'delivery_location',
        'payment_terms', 'note', 'status'
    ];
    
    basicFields.forEach(function(fieldName) {
        var element = document.getElementById(fieldName) || document.querySelector('[name="' + fieldName + '"]');
        if (element) {
            var value = element.value || '';
            formData.append(fieldName, value);
            console.log('📌 [DEBUG] ' + fieldName + ':', value);
        } else {
            console.warn('⚠️ [DEBUG] 필드를 찾을 수 없음: ' + fieldName);
        }
    });
    
    // Hidden input 필드들 수집 (cart_items 등)
    var hiddenInputs = document.querySelectorAll('input[type="hidden"]');
    hiddenInputs.forEach(function(input) {
        var name = input.name;
        var value = input.value;
        if (name && !basicFields.includes(name)) {
            formData.append(name, value);
            console.log('📌 [DEBUG] Hidden field ' + name + ':', value);
        }
    });
    
    // Tabulator 데이터 수집
    try {
        var orderItems = orderTable.getData();
        var orderItemsJson = JSON.stringify(orderItems);
        formData.append('order_items', orderItemsJson);
        
        console.log('📦 [DEBUG] Tabulator 데이터:', orderItems);
        console.log('📦 [DEBUG] JSON 문자열:', orderItemsJson);
        console.log('📦 [DEBUG] JSON 문자열 길이:', orderItemsJson.length);
        
    } catch (error) {
        console.error('❌ [DEBUG] Tabulator 데이터 수집 오류:', error);
        throw new Error('품목 데이터 수집 실패');
    }
    
    return formData;
}

// JSON 응답 처리
function handleJsonResponse(data) {
    console.log('📊 [DEBUG] JSON 응답 처리:', data);
    
    if (data.success) {
        console.log('✅ [DEBUG] 저장 성공');
        
        if (isIframeMode) {
            notifyParent('orderSaved', {
                id: data.id || null,
                message: data.message || ''
            });
            return;
        }
        
        showSuccessMessage(data.message || '발주서가 성공적으로 저장되었습니다.');
        
        if (data.redirect_url) {
            console.log('🔄 [DEBUG] 리다이렉트:', data.redirect_url);
            setTimeout(function() {
                window.location.href = data.redirect_url;
            }, 1500);
        } else if (data.id) {
            console.log('🔄 [DEBUG] view.php로 이동, ID:', data.id);
            setTimeout(function() {
                window.location.href = 'view.php?id=' + data.id;
            }, 1500);
        }
    } else {
        console.error('❌ [DEBUG] 저장 실패:', data.message);
        showErrorMessage(data.message || '저장 중 오류가 발생했습니다.');
    }
}

// HTML 응답 처리 (기존 스크립트 태그가 포함된 응답)
function handleHtmlResponse(data) {
    console.log('📄 [DEBUG] HTML 응답 처리');
    
    // alert 및 location.href 추출
    var alertMatch = data.match(/alert\(['"]([^'"]+)['"]\)/);
    var locationMatch = data.match(/location\.href\s*=\s*['"]([^'"]+)['"]/);
    
    if (alertMatch) {
        var message = alertMatch[1];
        console.log('📢 [DEBUG] 추출된 알림 메시지:', message);
        
        if (message.includes('성공')) {
                if (isIframeMode) {
                    var redirectedId = null;
                    if (locationMatch) {
                        var urlForId = locationMatch[1] || '';
                        var idMatch = urlForId.match(/id=(\d+)/);
                        if (idMatch) {
                            redirectedId = parseInt(idMatch[1], 10);
                        }
                    }
                    
                    notifyParent('orderSaved', {
                        id: redirectedId,
                        message: message
                    });
                    return;
                }
                
            showSuccessMessage(message);
            
            if (locationMatch) {
                var url = locationMatch[1];
                console.log('🔄 [DEBUG] 추출된 리다이렉트 URL:', url);
                setTimeout(function() {
                    window.location.href = url;
                }, 1500);
            }
        } else {
            showErrorMessage(message);
        }
    } else {
        console.warn('⚠️ [DEBUG] HTML 응답에서 메시지를 추출할 수 없음');
        showErrorMessage('서버 응답을 처리할 수 없습니다.');
    }
}

// UI 헬퍼 함수들
function showLoadingSpinner() {
    // 로딩 스피너 표시
    var loadingHtml = '<div id="loadingOverlay" style="' +
        'position: fixed; top: 0; left: 0; width: 100%; height: 100%;' +
        'background: rgba(0,0,0,0.5); z-index: 9999;' +
        'display: flex; justify-content: center; align-items: center;">' +
        '<div style="background: white; padding: 20px; border-radius: 8px; text-align: center;">' +
        '<div style="border: 4px solid #f3f3f3; border-radius: 50%; border-top: 4px solid #3498db; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px;"></div>' +
        '<div>저장 중...</div>' +
        '</div>' +
        '</div>' +
        '<style>' +
        '@keyframes spin {' +
        '0% { transform: rotate(0deg); }' +
        '100% { transform: rotate(360deg); }' +
        '}' +
        '</style>';
    
    document.body.insertAdjacentHTML('beforeend', loadingHtml);
    console.log('🔄 [DEBUG] 로딩 스피너 표시');
}

function hideLoadingSpinner() {
    var overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
        console.log('✅ [DEBUG] 로딩 스피너 숨김');
    }
}

function showSuccessMessage(message) {
    console.log('✅ [DEBUG] 성공 메시지 표시:', message);
    
    // Toast 스타일 성공 메시지
    var toastHtml = '<div id="successToast" style="' +
        'position: fixed; top: 20px; right: 20px; z-index: 10000;' +
        'background: #28a745; color: white; padding: 15px 20px;' +
        'border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);' +
        'animation: slideIn 0.3s ease-out;">' +
        '<div style="display: flex; align-items: center; gap: 10px;">' +
        '<span style="font-size: 18px;">✅</span>' +
        '<span>' + message + '</span>' +
        '</div>' +
        '</div>' +
        '<style>' +
        '@keyframes slideIn {' +
        'from { transform: translateX(100%); opacity: 0; }' +
        'to { transform: translateX(0); opacity: 1; }' +
        '}' +
        '</style>';
    
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    
    // 3초 후 자동 제거
    setTimeout(function() {
        var toast = document.getElementById('successToast');
        if (toast) toast.remove();
    }, 3000);
}

function showErrorMessage(message) {
    console.error('❌ [DEBUG] 오류 메시지 표시:', message);
    
    // Toast 스타일 오류 메시지
    var toastHtml = '<div id="errorToast" style="' +
        'position: fixed; top: 20px; right: 20px; z-index: 10000;' +
        'background: #dc3545; color: white; padding: 15px 20px;' +
        'border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);' +
        'animation: slideIn 0.3s ease-out; max-width: 400px;">' +
        '<div style="display: flex; align-items: flex-start; gap: 10px;">' +
        '<span style="font-size: 18px;">❌</span>' +
        '<div>' +
        '<div style="font-weight: bold; margin-bottom: 5px;">오류가 발생했습니다</div>' +
        '<div style="font-size: 14px; line-height: 1.4;">' + message + '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    
    // 5초 후 자동 제거
    setTimeout(function() {
        var toast = document.getElementById('errorToast');
        if (toast) toast.remove();
    }, 5000);
}

window.submitOrder = function() {
    if (validateForm()) {
        document.getElementById('orderForm').submit();
    }
};

window.cancelOrder = function() {
    if (!confirm('작성 중인 내용이 사라집니다. 계속하시겠습니까?')) {
        return;
    }
    
    if (isIframeMode) {
        notifyParent('orderCanceled');
        return;
    }
    
    location.href = 'index.php';
};

window.deleteOrder = function() {
    if (!confirm('정말로 이 발주서를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) {
        return;
    }
    
    var idInput = document.querySelector('input[name="id"]');
    var orderId = idInput ? idInput.value : null;
    if (!orderId) {
        alert('삭제할 발주서 ID를 찾을 수 없습니다.');
        return;
    }
    
    console.log('🗑️ [DEBUG] 삭제 요청 시작 - ID:', orderId);
    
    showLoadingSpinner();
    
    fetch('delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: parseInt(orderId) })
    })
    .then(function(response) {
        console.log('📡 [DEBUG] 삭제 응답 상태:', response.status, response.statusText);
        return response.json();
    })
    .then(function(data) {
        console.log('📥 [DEBUG] 삭제 응답 데이터:', data);
        hideLoadingSpinner();
        
        if (data.success) {
            if (isIframeMode) {
                notifyParent('orderDeleted', {
                    id: parseInt(orderId, 10) || null
                });
                return;
            }
            
            alert('발주서가 성공적으로 삭제되었습니다.');
            location.href = 'index.php';
        } else {
            alert('삭제 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(function(error) {
        console.error('❌ [DEBUG] 삭제 오류:', error);
        hideLoadingSpinner();
        alert('삭제 중 오류가 발생했습니다: ' + error.message);
    });
};

// 폼 유효성 검사
function validateForm() {
    var supplierName = document.querySelector('input[name="supplier_name"]').value.trim();
    var issueDate = document.querySelector('input[name="issue_date"]').value;
    
    if (!supplierName) {
        alert('공급업체 상호를 입력해주세요.');
        return false;
    }
    
    if (!issueDate) {
        alert('발주일자를 선택해주세요.');
        return false;
    }
    
    // Tabulator 데이터를 hidden input에 저장
    var tableData = orderTable.getData();
    var orderItemsInput = document.createElement('input');
    orderItemsInput.type = 'hidden';
    orderItemsInput.name = 'order_items';
    orderItemsInput.value = JSON.stringify(tableData);
    document.getElementById('orderForm').appendChild(orderItemsInput);
    
    return true;
}

// 페이지 로드 시 합계 계산
window.onload = function() {
    setTimeout(updateTotalAmount, 500);
};

})();
</script>

    <!-- 거래처 검색 모달 -->
    <div class="modal fade" id="customerSearchModal" tabindex="-1" aria-labelledby="customerSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerSearchModalLabel">거래처 검색</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="customerSearchInput" placeholder="거래처명, 사업자번호로 검색...">
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-sm">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>거래처명</th>
                                    <th>사업자번호</th>
                                    <th>대표자명</th>
                                    <th>전화번호</th>
                                    <th>선택</th>
                                </tr>
                            </thead>
                            <tbody id="customerSearchResults">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">검색어를 입력하세요</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 거래처 검색 기능
        var customerSearchModal = new bootstrap.Modal(document.getElementById('customerSearchModal'));
        var searchTimeout = null;

        // 검색 버튼 클릭
        document.getElementById('searchCustomerBtn').addEventListener('click', function() {
            openCustomerSearchModal();
        });

        // 거래처명 입력 필드에서 엔터 키 입력 시 검색 모달 열기
        document.getElementById('contact_name').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // 폼 제출 방지
                openCustomerSearchModal();
            }
        });

        function openCustomerSearchModal() {
            // 현재 거래처명 필드의 값을 검색창에 기본값으로 설정
            var currentContactName = document.getElementById('contact_name').value.trim();
            var searchInput = document.getElementById('customerSearchInput');
            var modalElement = document.getElementById('customerSearchModal');
            
            if (currentContactName) {
                searchInput.value = currentContactName;
            } else {
                searchInput.value = '';
            }
            
            // 모달이 완전히 표시된 후 포커스 및 자동 검색
            modalElement.addEventListener('shown.bs.modal', function() {
                searchInput.focus();
                
                // 거래처명이 있으면 자동으로 검색 실행
                if (currentContactName) {
                    searchCustomers(currentContactName);
                }
            }, { once: true });
            
            customerSearchModal.show();
        }

        // 검색 입력 이벤트
        document.getElementById('customerSearchInput').addEventListener('input', function() {
            var searchTerm = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (searchTerm.length < 1) {
                document.getElementById('customerSearchResults').innerHTML = 
                    '<tr><td colspan="5" class="text-center text-muted">검색어를 입력하세요</td></tr>';
                return;
            }

            searchTimeout = setTimeout(function() {
                searchCustomers(searchTerm);
            }, 300);
        });

        // 선택 버튼 클릭 이벤트 (이벤트 위임)
        document.getElementById('customerSearchResults').addEventListener('click', function(e) {
            if (e.target.classList.contains('select-customer-btn')) {
                var customerData = e.target.getAttribute('data-customer');
                if (customerData) {
                    try {
                        var customer = JSON.parse(decodeURIComponent(customerData));
                        selectCustomer(customer);
                    } catch (err) {
                        console.error('거래처 데이터 파싱 오류:', err);
                        alert('거래처 선택 중 오류가 발생했습니다.');
                    }
                }
            }
        });

        // 거래처 검색 함수
        function searchCustomers(searchTerm) {
            var tbody = document.getElementById('customerSearchResults');
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">검색 중...</td></tr>';

            fetch('/corp/search_customers.php?q=' + encodeURIComponent(searchTerm))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.customers && data.customers.length > 0) {
                        var html = '';
                        data.customers.forEach(function(customer, index) {
                            var customerData = encodeURIComponent(JSON.stringify(customer));
                            html += '<tr>';
                            html += '<td>' + (customer.company_name || '') + '</td>';
                            html += '<td>' + (customer.business_registration_number || '') + '</td>';
                            html += '<td>' + (customer.representative_name || '') + '</td>';
                            html += '<td>' + (customer.phone_number || '') + '</td>';
                            html += '<td><button type="button" class="btn btn-sm btn-primary select-customer-btn" data-customer="' + customerData + '">선택</button></td>';
                            html += '</tr>';
                        });
                        tbody.innerHTML = html;
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">검색 결과가 없습니다</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('검색 오류:', error);
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">검색 중 오류가 발생했습니다</td></tr>';
                });
        }

        // 거래처 선택 함수
        // 거래처 선택 함수
        function selectCustomer(customer) {
            document.getElementById('contact_name').value = customer.company_name || '';
            document.getElementById('business_registration_number').value = formatBizNumber(customer.business_registration_number || '');
            
            // 거래처 ID 설정 (hidden input)
            var customerIdInput = document.getElementById('customer_id');
            if (customerIdInput) {
                customerIdInput.value = customer.num || ''; 
            }

            var phoneInput = document.querySelector('input[name="phone"]');
            if (phoneInput) phoneInput.value = formatPhoneNumber(customer.phone_number || '');
            
            var faxInput = document.querySelector('input[name="fax"]');
            if (faxInput) faxInput.value = formatPhoneNumber(customer.fax_number || '');
            
            customerSearchModal.hide();
        }
    </script>
    <script>
        // 전화번호 포맷팅 함수
        function formatPhoneNumber(value) {
            if (!value) return '';
            value = value.replace(/[^0-9]/g, '');
            
            if (value.length < 4) {
                return value;
            } else if (value.length < 7) {
                return value.substr(0, 3) + '-' + value.substr(3);
            } else if (value.length < 11) {
                if (value.substr(0, 2) === '02') {
                    if (value.length < 10) {
                        return value.substr(0, 2) + '-' + value.substr(2, 3) + '-' + value.substr(5);
                    } else {
                        return value.substr(0, 2) + '-' + value.substr(2, 4) + '-' + value.substr(6);
                    }
                } else {
                    return value.substr(0, 3) + '-' + value.substr(3, 3) + '-' + value.substr(6);
                }
            } else {
                if (value.substr(0, 2) === '02') {
                    return value.substr(0, 2) + '-' + value.substr(2, 4) + '-' + value.substr(6);
                } else {
                    return value.substr(0, 3) + '-' + value.substr(3, 4) + '-' + value.substr(7);
                }
            }
        }

        // 사업자번호 포맷팅 함수
        function formatBizNumber(value) {
            if (!value) return '';
            value = value.replace(/[^0-9]/g, '');
            
            if (value.length < 4) {
                return value;
            } else if (value.length < 6) {
                return value.substr(0, 3) + '-' + value.substr(3);
            } else if (value.length < 11) {
                return value.substr(0, 3) + '-' + value.substr(3, 2) + '-' + value.substr(5);
            } else {
                return value.substr(0, 3) + '-' + value.substr(3, 2) + '-' + value.substr(5);
            }
        }

        // 입력 필드에 포맷팅 적용
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector('input[name="phone"]');
            const faxInput = document.querySelector('input[name="fax"]');
            const bizNumInput = document.querySelector('input[name="business_registration_number"]');

            function applyPhoneFormat(e) {
                e.target.value = formatPhoneNumber(e.target.value);
            }

            function applyBizFormat(e) {
                e.target.value = formatBizNumber(e.target.value);
            }

            if (phoneInput) {
                phoneInput.addEventListener('input', applyPhoneFormat);
                phoneInput.value = formatPhoneNumber(phoneInput.value); // 초기값 포맷팅
            }
            if (faxInput) {
                faxInput.addEventListener('input', applyPhoneFormat); // 팩스도 전화번호 형식 사용
                faxInput.value = formatPhoneNumber(faxInput.value); // 초기값 포맷팅
            }
            if (bizNumInput) {
                bizNumInput.addEventListener('input', applyBizFormat);
                bizNumInput.value = formatBizNumber(bizNumInput.value); // 초기값 포맷팅
            }

                // 거래처명 입력 후 포커스 아웃 시 자동 검색
            const contactNameInput = document.getElementById('contact_name');
            if (contactNameInput) {
                contactNameInput.addEventListener('blur', function() {
                    const searchTerm = this.value.trim();
                    // 이미 사업자번호나 전화번호가 입력되어 있다면 자동 검색 하지 않음 (기존 정보 보존)
                    const bizNum = document.getElementById('business_registration_number').value.trim();
                    const phone = document.querySelector('input[name="phone"]').value.trim();
                    
                    if (searchTerm && !bizNum && !phone) {
                        fetchCustomerInfo(searchTerm);
                    }
                });

                // 페이지 로드 1초 후 거래처명이 있고 기타 정보가 없을 때만 자동 검색 (구매카트 연동 시 유용)
                setTimeout(function() {
                    const initialSearchTerm = contactNameInput.value.trim();
                    // 이미 사업자번호나 전화번호가 입력되어 있다면 자동 검색 하지 않음
                    const bizNum = document.getElementById('business_registration_number').value.trim();
                    const phone = document.querySelector('input[name="phone"]').value.trim();

                    if (initialSearchTerm && !bizNum && !phone) {
                        fetchCustomerInfo(initialSearchTerm);
                    }
                }, 1000);
            }
        });

        // 거래처 정보 자동 가져오기
        function fetchCustomerInfo(searchTerm) {
            fetch('/corp/search_customers.php?q=' + encodeURIComponent(searchTerm))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.customers && data.customers.length > 0) {
                        // 정확히 일치하는 거래처 찾기
                        const exactMatch = data.customers.find(customer => customer.company_name === searchTerm);
                        
                        if (exactMatch) {
                            // 정확히 일치하는 거래처가 있으면 자동 선택
                            selectCustomer(exactMatch);
                            
                            // 알림 표시 (선택사항)
                            Toastify({
                                text: "거래처 정보를 불러왔습니다.",
                                duration: 2000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                                }
                            }).showToast();
                        }
                    }
                })
                .catch(error => {
                    console.error('거래처 정보 가져오기 오류:', error);
                });
        }
    </script>
</body>
</html>