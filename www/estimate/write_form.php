<?php
/**
 * 견적서 작성/수정 폼 페이지
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
    $_SESSION["url"] = "{$base_url}/estimate/write_form.php";
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
    $stmt = $pdo->prepare("SELECT * FROM `estimates` WHERE id = :id AND is_deleted = 0");
    $stmt->execute([':id' => $id]);
    $order_data = $stmt->fetch();

    if (!$order_data) {
        echo "<script>alert('존재하지 않는 견적서입니다.'); location.href='index.php';</script>";
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
    $title_message = "(데이터복사) 견적서 작성";
} else if ($id > 0) {
    $title_message = "견적서 수정";
} else {
    $title_message = "견적서 작성";
}

// 자동 견적서 번호 생성
$order_no = date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
if ($order_data && isset($order_data['estimate_no']) && $order_data['estimate_no']) {
    $order_no = $order_data['estimate_no'];
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

/* 합계 행 스타일 */
.tabulator .tabulator-calcs {
    background-color: #f8f9fa !important;
    border-top: 2px solid #6c757d !important;
    font-weight: bold !important;
}

.tabulator .tabulator-calcs .tabulator-cell {
    background-color: #f8f9fa !important;
    font-weight: bold !important;
    color: #333 !important;
}

.tabulator .tabulator-calcs .tabulator-cell[tabulator-field="공급가액"],
.tabulator .tabulator-calcs .tabulator-cell[tabulator-field="세액"] {
    background-color: #e9ecef !important;
    color: #333 !important;
    font-weight: bold !important;
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

        <!-- Phomi Style Header -->
        <div class="row mb-4 p-3">
            <!-- 수신자 정보 (Customer) - Left Side -->
            <div class="col-md-6">
                <div class="card h-100 bg-light border" style="border: 1px solid #dee2e6; border-radius: 6px;">
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold border-bottom pb-2 mb-3" style="font-weight: bold; border-bottom: 1px solid #dee2e6; padding-bottom: 8px; margin-bottom: 16px;">수신 (Customer)</h6>
                        
                        <div class="mb-2 row align-items-center" style="margin-bottom: 8px; display: flex; align-items: center;">
                            <label class="col-sm-3 col-form-label fw-bold" style="font-weight: bold; width: 25%;">상호(성명)</label>
                            <div class="col-sm-9" style="width: 75%; display: flex; gap: 5px;">
                                <input type="text" class="form-control form-control-sm" name="contact_name" id="contact_name" value="<?php 
                                    if (!empty($cart_items_data)) {
                                        echo htmlspecialchars($cart_items_data[0]['supplier'] ?? '');
                                    } else {
                                        echo $order_data ? htmlspecialchars($order_data['contact_name'] ?? '') : '';
                                    }
                                ?>" placeholder="거래처명 입력" style="width: 100%; padding: 4px 8px; border: 1px solid #ced4da; border-radius: 4px;">
                                <button type="button" class="btn btn-sm btn-primary" id="searchCustomerBtn" style="white-space: nowrap; padding: 4px 8px; font-size: 12px;">
                                    <i class="bi bi-search"></i> 검색
                                </button>
                            </div>
                            <!-- 이메일 정보 표시 및 저장 -->
                            <div class="col-sm-9 offset-sm-3 mt-1" style="margin-left: 25%; width: 75%;">
                                <input type="hidden" name="email" id="email" value="<?php echo $order_data ? ($order_data['email'] ?? '') : ''; ?>">
                                <span id="email_display" style="font-size: 11px; color: #adb5bd;">
                                    <?php 
                                    $email = $order_data['email'] ?? '';
                                    echo $email ? '<i class="bi bi-envelope-check"></i> ' . htmlspecialchars($email) : ''; 
                                    ?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-2 row align-items-center" style="margin-bottom: 8px; display: flex; align-items: center;">
                            <label class="col-sm-3 col-form-label fw-bold" style="font-weight: bold; width: 25%;">참조</label>
                            <div class="col-sm-9" style="width: 75%;">
                                <input type="text" class="form-control form-control-sm" name="reference" value="<?php echo $order_data ? htmlspecialchars($order_data['reference'] ?? '') : ''; ?>" placeholder="참조 입력" style="width: 100%; padding: 4px 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            </div>
                        </div>

                        <div class="mb-2 row align-items-center" style="margin-bottom: 8px; display: flex; align-items: center;">
                            <label class="col-sm-3 col-form-label fw-bold" style="font-weight: bold; width: 25%;">현장명</label>
                            <div class="col-sm-9" style="width: 75%;">
                                <input type="text" class="form-control form-control-sm" name="project_site" id="project_site" value="<?php 
                                    if (!empty($cart_items_data)) {
                                        echo htmlspecialchars($cart_items_data[0]['outworkplace'] ?? '');
                                    } else {
                                        echo $order_data ? htmlspecialchars($order_data['project_site'] ?? '') : '';
                                    }
                                ?>" placeholder="현장명 입력" style="width: 100%; padding: 4px 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            </div>
                        </div>

                        <div class="mb-2 row align-items-center" style="margin-bottom: 8px; display: flex; align-items: center;">
                            <label class="col-sm-3 col-form-label fw-bold" style="font-weight: bold; width: 25%;">견적일자</label>
                            <div class="col-sm-9" style="width: 75%;">
                                <input type="date" class="form-control form-control-sm" name="issue_date" value="<?php echo $order_data ? ($order_data['issue_date'] ?? date('Y-m-d')) : date('Y-m-d'); ?>" style="width: 100%; padding: 4px 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            </div>
                        </div>

                        <!-- Hidden fields for compatibility -->
                        <input type="hidden" name="fax" value="<?php echo $order_data ? htmlspecialchars($order_data['fax'] ?? '') : ''; ?>">
                        <input type="hidden" name="business_registration_number" value="<?php echo $order_data ? htmlspecialchars($order_data['business_registration_number'] ?? '') : ''; ?>">
                        <input type="hidden" name="status" value="<?php echo $current_status; ?>">

                        <div class="text-center mt-3 fw-bold text-primary" style="margin-top: 16px; text-align: center; color: #0d6efd; font-weight: bold;">
                            아래와 같이 견적합니다.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 공급자 정보 (Supplier/Mirae) - Right Side -->
            <div class="col-md-6">
                <div class="card h-100 border" style="border: 1px solid #dee2e6; border-radius: 6px; height: 100%;">
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0 h-100 supplier-table" style="width: 100%; height: 100%; border-collapse: collapse; font-size: 14px;">
                            <tr>
                                <td rowspan="5" class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; width: 40px; vertical-align: middle; border: 1px solid #dee2e6;">공<br>급<br>자</td>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">등록번호</td>
                                <td colspan="3" class="fw-bold text-center" style="text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">
                                    722-88-00035
                                    <input type="hidden" name="supplier_code" value="722-88-00035">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">상 호</td>
                                <td style="padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">
                                    주식회사 미래기업
                                    <input type="hidden" name="supplier_name" value="주식회사미래기업">
                                </td>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">대 표</td>
                                <td style="padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">소현철</td>
                            </tr>
                            <tr>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">주 소</td>
                                <td colspan="3" style="padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">
                                    경기도 김포시 양촌읍 흥신로 220-27
                                    <input type="hidden" name="supplier_address" value="경기도 김포시 양촌읍 흥신리 220-27 (흥신리)">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">업 태</td>
                                <td style="padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">
                                    제조업
                                    <input type="hidden" name="business_type" value="제조업">
                                </td>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">종 목</td>
                                <td style="padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">
                                    엘리베이터의장품
                                    <input type="hidden" name="business_item" value="엘리베이터인테리어제품">
                                </td>
                            </tr>
                            <tr>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">연락처</td>
                                <td style="padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">
                                    031-985-8440
                                    <input type="hidden" name="supplier_phone" value="031-983-8440">
                                </td>
                                <td class="label-cell" style="background-color: #f8f9fa; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">팩스</td>
                                <td style="padding: 5px; border: 1px solid #dee2e6; vertical-align: middle;">
                                    031-982-8443
                                    <input type="hidden" name="supplier_fax" value="031-982-8443">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount Bar -->
        <div class="row mb-3 p-3 pt-0">
            <div class="col-12">
                <div class="alert alert-secondary d-flex justify-content-between align-items-center py-2" style="background-color: #e2e3e5; border-color: #d3d6d8; padding: 10px 20px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                    <div class="fs-5 fw-bold" style="font-weight: bold; font-size: 1.25rem;">합계금액 (VAT 포함)</div>
                    <div class="fs-4 fw-bold text-danger" style="font-weight: bold; font-size: 1.5rem; color: #dc3545;">
                        <input type="text" id="totalAmount" name="total_amount" value="₩0" readonly style="background: transparent; border: none; text-align: right; color: #dc3545; font-weight: bold; width: 200px;">
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



        <!-- 비고 -->
        <div class="note-section">
            <div class="note-header">비고</div>
            <div class="note-content">
                <textarea name="note" placeholder="정보를 입력합니다."><?php echo $order_data ? htmlspecialchars($order_data['note'] ?? '') : ''; ?></textarea>
            </div>
        </div>

        <!-- PDF 안내 문구 -->
        <div class="note-section">
            <div class="note-header">PDF 안내 문구</div>
            <div class="note-content">
                <?php
                // 기본 안내 문구
                $default_disclaimer = "1. 상기 견적의 금액은 이후 확정 시 금액이 변동될 수 있습니다.\n2. 제품 현장 도착 후 즉시 현장 검수를 원칙으로 하며, 반품·교환 시 추가 운송비가 발생할 수 있습니다.\n3. 견적서 내역 검토는 구매자의 의무이며, 미검토로 인한 배송 오류에 대한 책임은 구매자에게 있습니다.\n4. 본 견적서로 계약서를 갈음하며, 납기 확정 시 견적 내용에 동의하는 것으로 간주합니다.";
                
                // 기존 데이터가 있으면 사용, 없으면 기본값 사용
                $disclaimer_text = '';
                if ($order_data && !empty($order_data['disclaimer_text'])) {
                    $disclaimer_text = $order_data['disclaimer_text'];
                } else {
                    $disclaimer_text = $default_disclaimer;
                }
                ?>
                <textarea name="disclaimer_text" id="disclaimer_text" placeholder="PDF에 표시될 안내 문구를 입력합니다. (각 항목은 줄바꿈으로 구분됩니다)" style="min-height: 150px;"><?php echo htmlspecialchars($disclaimer_text); ?></textarea>
                <div style="font-size: 12px; color: #6c757d; margin-top: 8px;">
                    ※ PDF 하단에 표시되는 안내 문구입니다. 각 항목은 줄바꿈으로 구분됩니다.
                </div>
            </div>
        </div>

        <!-- 내부 메모 및 파일 업로드 섹션 -->
        <div class="row mb-3 p-3" style="margin: 20px 0;">
            <div class="col-12">
                <!-- 내부 메모 카드 -->
                <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; padding: 12px 16px; border-bottom: none; border-radius: 8px 8px 0 0;">
                        <h6 class="mb-0 fw-bold" style="margin: 0; font-weight: bold;">
                            <i class="bi bi-sticky"></i> 회사 내부 메모
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 16px;">
                        <textarea name="internalmemo" id="internalmemo" class="form-control auto-resize-textarea" rows="3" placeholder="회사 내부에서만 볼 수 있는 메모를 입력하세요." style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 8px; resize: none; overflow-y: hidden; min-height: 80px;"><?php echo $order_data ? htmlspecialchars($order_data['internalmemo'] ?? '') : ''; ?></textarea>
                    </div>
                </div>

                <!-- 파일 업로드 섹션 -->
                <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; padding: 12px 16px; border-bottom: none; border-radius: 8px 8px 0 0;">
                        <h6 class="mb-0 fw-bold" style="margin: 0; font-weight: bold;">
                            <i class="bi bi-paperclip"></i> 내부 참고 문서 첨부
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 16px;">
                        <div class="file-upload-area" id="fileUploadArea" style="border: 2px dashed #ced4da; border-radius: 8px; padding: 40px; text-align: center; background: #f8f9fa; cursor: pointer; transition: all 0.3s ease; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="font-size: 48px; margin-bottom: 16px; color: #6c757d;">📎</div>
                            <div style="color: #495057; font-size: 14px; line-height: 1.6;">
                                <strong>클릭</strong>하거나 <strong>드래그앤드롭</strong>으로<br>
                                파일을 업로드하세요 (여러 개 선택 가능)
                            </div>
                            <input type="file" id="fileInput" name="attached_files[]" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt" multiple style="display: none;">
                        </div>
                        <div class="file-preview mt-3" id="filePreview" style="display: none; min-height: 0;"></div>
                        <div class="form-note mt-2" style="font-size: 12px; color: #6c757d;">
                            ※ 견적서 관련 문서를 첨부합니다 (파일당 최대 10MB)
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<!-- 업로드 진행 중 모달 -->
<div class="modal fade" id="uploadProgressModal" tabindex="-1" aria-labelledby="uploadProgressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-secondary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mb-2">업로드 중입니다.</h5>
                <p class="text-muted mb-0">잠시 기다려주세요...</p>
            </div>
        </div>
    </div>
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
        단위: '',
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
    
    $item_name = $item['steel_item'];
    if ($item['eworks_item'] == '부자재구매' && empty($item_name)) {
        $item_name = $item['outworkplace'];
    }

    $cart_order_items[] = [
        '순번' => $index,
        '품목' => $item_name ?? '',
        '규격' => $item['spec'] ?? '',
        '수량' => $quantity,
        '단위' => $item['단위'] ?? $item['unit'] ?? '',
        '단가' => $unit_price,
        '공급가액' => $supply_amount,
        '세액' => $tax,
        '비고' => $item['request_comment'] ?? ''
    ];
    $index++;
endforeach;
?>
orderItems = <?php echo json_encode($cart_order_items, JSON_UNESCAPED_UNICODE); ?>;
<?php elseif ($order_data && isset($order_data['estimate_items']) && $order_data['estimate_items']): ?>
orderItems = <?php 
    $items = json_decode($order_data['estimate_items'] ?? '[]', true);
    if (!is_array($items)) $items = [];
    // 기존 데이터의 "견적가액" 필드를 "공급가액"으로 변환 및 단위 필드 추가
    foreach ($items as &$item) {
        if (isset($item['견적가액']) && !isset($item['공급가액'])) {
            $item['공급가액'] = $item['견적가액'];
            unset($item['견적가액']);
        }
        // 단위 필드가 없으면 기본값 '' 설정
        if (!isset($item['단위']) && !isset($item['unit'])) {
            $item['단위'] = '';
        } elseif (isset($item['unit']) && !isset($item['단위'])) {
            $item['단위'] = $item['unit'];
        }
    }
    unset($item);
    echo json_encode($items, JSON_UNESCAPED_UNICODE); 
?>;
<?php else: ?>
orderItems = [
    {순번: 1, 품목: '', 규격: '', 수량: '', 단위: '', 단가: '', 공급가액: '', 세액: '', 비고: ''},
    {순번: 2, 품목: '', 규격: '', 수량: '', 단위: '', 단가: '', 공급가액: '', 세액: '', 비고: ''},
    {순번: 3, 품목: '', 규격: '', 수량: '', 단위: '', 단가: '', 공급가액: '', 세액: '', 비고: ''},
    {순번: 4, 품목: '', 규격: '', 수량: '', 단위: '', 단가: '', 공급가액: '', 세액: '', 비고: ''}
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
                 var 수량 = parseFloat(String(data.수량 || '').replace(/,/g, '')) || 0;
                 var 단가 = parseFloat(String(data.단가 || '').replace(/,/g, '')) || 0;
                 
                 // 수량과 단가가 모두 있으면 공급가액과 세액 자동 계산
                 if (수량 > 0 && 단가 > 0) {
                     var 공급가액 = Math.round(수량 * 단가);
                     var 세액 = Math.round(공급가액 * 0.1);
                     row.update({공급가액: 공급가액, 세액: 세액});
                 }
                 setTimeout(updateTotalAmount, 50);
             },
             cellClick: function() { return true; }},
            {title: "단위", field: "단위", width: 80, hozAlign: "center", headerHozAlign: "center", 
             editor: function(cell, onRendered, success, cancel) {
                 var select = document.createElement("select");
                 select.style.width = "100%";
                 select.style.height = "100%";
                 select.style.padding = "4px";
                 select.style.boxSizing = "border-box";
                 
                 // 옵션 목록
                 var options = [
                     {value: "", label: ""},
                     {value: "EA", label: "EA"},
                     {value: "SET", label: "SET"},
                     {value: "대", label: "대"},
                     {value: "식", label: "식"}
                 ];
                 
                 // 현재 값
                 var currentValue = cell.getValue() || '';
                 
                 // 옵션 추가
                 options.forEach(function(opt) {
                     var option = document.createElement("option");
                     option.value = opt.value;
                     option.textContent = opt.label;
                     if (opt.value === currentValue) {
                         option.selected = true;
                     }
                     select.appendChild(option);
                 });
                 
                 // 이벤트 핸들러
                 select.addEventListener("change", function() {
                     success(select.value);
                 });
                 
                 select.addEventListener("blur", function() {
                     success(select.value);
                 });
                 
                 select.addEventListener("keydown", function(e) {
                     if (e.key === "Enter") {
                         success(select.value);
                     } else if (e.key === "Escape") {
                         cancel();
                     }
                 });
                 
                 onRendered(function() {
                     select.focus();
                 });
                 
                 return select;
             },
             formatter: function(cell) {
                 var value = cell.getValue();
                 return value || '';
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
                 var 수량 = parseFloat(String(data.수량 || '').replace(/,/g, '')) || 0;
                 var 단가 = parseFloat(String(data.단가 || '').replace(/,/g, '')) || 0;
                 
                 // 수량과 단가가 모두 있으면 공급가액과 세액 자동 계산
                 if (수량 > 0 && 단가 > 0) {
                     var 공급가액 = Math.round(수량 * 단가);
                     var 세액 = Math.round(공급가액 * 0.1);
                     row.update({공급가액: 공급가액, 세액: 세액});
                 }
                 setTimeout(updateTotalAmount, 50);
             },
             cellClick: function() { return true; }},
            {title: "공급가액", field: "공급가액", width: 120, hozAlign: "right", headerHozAlign: "center", 
             editor: function(cell, onRendered, success, cancel) {
                 var input = document.createElement("input");
                 input.type = "text";
                 input.style.width = "100%";
                 input.style.padding = "4px";
                 input.style.boxSizing = "border-box";
                 
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
                 });
                 
                 return input;
             },
             formatter: function(cell) {
                 var value = cell.getValue();
                 if (value === null || value === undefined || value === '') {
                     return '';
                 }
                 var numValue = typeof value === 'string' ? parseFloat(value.replace(/,/g, '')) : Number(value);
                 return !isNaN(numValue) && numValue !== 0 ? numValue.toLocaleString() : '';
             },
             cellEdited: function(cell) {
                 var row = cell.getRow();
                 var data = row.getData();
                 var 공급가액 = parseFloat(String(data.공급가액 || '').replace(/,/g, '')) || 0;
                 
                 // 공급가액이 입력되면 세액 자동 계산 (공급가액의 10%)
                 if (공급가액 > 0) {
                     var 세액 = Math.round(공급가액 * 0.1);
                     row.update({세액: 세액});
                 }
                 setTimeout(updateTotalAmount, 50);
             },
             bottomCalc: "sum",
             bottomCalcFormatter: function(cell) {
                 var value = cell.getValue();
                 if (value === null || value === undefined || value === '') {
                     return '';
                 }
                 var numValue = typeof value === 'string' ? parseFloat(value.replace(/,/g, '')) : Number(value);
                 return !isNaN(numValue) && numValue !== 0 ? numValue.toLocaleString() : '';
             },
             cellClick: function() { return true; }},
            {title: "세액", field: "세액", width: 100, hozAlign: "right", headerHozAlign: "center", 
             editor: function(cell, onRendered, success, cancel) {
                 var input = document.createElement("input");
                 input.type = "text";
                 input.style.width = "100%";
                 input.style.padding = "4px";
                 input.style.boxSizing = "border-box";
                 
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
                 });
                 
                 return input;
             },
             formatter: function(cell) {
                 var value = cell.getValue();
                 return value ? Number(value).toLocaleString() : '';
             },
             cellEdited: function(cell) {
                 // 세액은 직접 입력 가능, 합계만 업데이트
                 setTimeout(updateTotalAmount, 50);
             },
             bottomCalc: "sum",
             bottomCalcFormatter: function(cell) {
                 var value = cell.getValue();
                 if (value === null || value === undefined || value === '') {
                     return '';
                 }
                 var numValue = typeof value === 'string' ? parseFloat(value.replace(/,/g, '')) : Number(value);
                 return !isNaN(numValue) && numValue !== 0 ? numValue.toLocaleString() : '';
             },
             cellClick: function() { return true; }},
            {title: "비고", field: "비고", widthGrow: 1, hozAlign: "left", headerHozAlign: "center", editor: "input", resizable: true}
        ],
        cellEdited: function(cell) {
            console.log(`📝 [DEBUG] 셀 편집: ${cell.getField()} = ${cell.getValue()}`);
            // 각 컬럼의 cellEdited에서 계산하므로 여기서는 전체 합계만 업데이트
            // (수량, 단가, 공급가액, 세액은 각 컬럼의 cellEdited에서 처리)
            setTimeout(updateTotalAmount, 100);
        },
        rowAdded: function(row) {
            console.log('📋 [DEBUG] 새 행 추가됨');
        }
    });

    // 초기 로드 시 공급가액 재계산 및 합계 계산
    setTimeout(function() {
        // 기존 데이터의 공급가액이 없거나 잘못된 경우 재계산
        var rows = orderTable.getRows();
        rows.forEach(function(row) {
            var data = row.getData();
            var 수량 = parseFloat(String(data.수량 || '').replace(/,/g, '')) || 0;
            var 단가 = parseFloat(String(data.단가 || '').replace(/,/g, '')) || 0;
            var 공급가액 = parseFloat(String(data.공급가액 || '').replace(/,/g, '')) || 0;
            var 세액 = parseFloat(String(data.세액 || '').replace(/,/g, '')) || 0;
            
            // 수량과 단가가 모두 있는 경우: 공급가액과 세액 계산
            if (수량 > 0 && 단가 > 0) {
                var 예상공급가액 = Math.round(수량 * 단가);
                var 예상세액 = Math.round(예상공급가액 * 0.1);
                
                // 공급가액이 없거나 잘못된 경우 재계산
                if (!공급가액 || 공급가액 !== 예상공급가액) {
                    row.update({
                        공급가액: 예상공급가액,
                        세액: 예상세액
                    });
                } else if (공급가액 && (!세액 || 세액 !== 예상세액)) {
                    // 공급가액은 맞지만 세액이 잘못된 경우 세액만 업데이트
                    row.update({
                        세액: 예상세액
                    });
                }
            }
            // 수량과 단가가 없지만 공급가액이 있는 경우: 세액만 계산
            else if (공급가액 > 0 && (!세액 || 세액 === 0)) {
                var 예상세액 = Math.round(공급가액 * 0.1);
                row.update({
                    세액: 예상세액
                });
            }
        });
        
        updateTotalAmount();
    }, 200);
});

// 계산 업데이트 (레거시 함수, cellEdited에서 처리하므로 사용 안 함)
function updateCalculations(cell) {
    // cellEdited에서 직접 처리하므로 이 함수는 사용하지 않음
    setTimeout(function() {
        updateTotalAmount();
    }, 10);
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
        'reference', 'fax', 'project_site', 'note', 'status', 'internalmemo', 'email', 'customer_id', 'disclaimer_text'
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

// textarea 자동 크기 조절 함수
function autoResizeTextarea(textarea) {
    if (!textarea) return;
    
    // 높이를 초기화하여 정확한 scrollHeight 계산
    textarea.style.height = 'auto';
    
    // scrollHeight 계산 (padding 포함)
    var scrollHeight = textarea.scrollHeight;
    
    // 최소 높이 80px, 최대 높이는 제한 없음 (또는 필요시 설정)
    var newHeight = Math.max(80, scrollHeight);
    
    // 높이 설정
    textarea.style.height = newHeight + 'px';
    
    // overflow-y를 hidden으로 유지하여 스크롤바 숨김
    if (textarea.scrollHeight > newHeight) {
        textarea.style.overflowY = 'hidden';
    }
}

// textarea 자동 크기 조절 초기화
function initializeAutoResizeTextarea() {
    var textarea = document.getElementById('internalmemo');
    if (!textarea) {
        // textarea가 아직 로드되지 않았으면 재시도
        setTimeout(initializeAutoResizeTextarea, 100);
        return;
    }
    
    // 초기 로드 시 크기 조절 (약간의 지연을 두어 CSS 적용 대기)
    setTimeout(function() {
        autoResizeTextarea(textarea);
    }, 50);
    
    // 입력 시마다 크기 조절
    textarea.addEventListener('input', function() {
        autoResizeTextarea(this);
    });
    
    // 포커스 시에도 크기 조절 (복사/붙여넣기 대응)
    textarea.addEventListener('paste', function() {
        var self = this;
        setTimeout(function() {
            autoResizeTextarea(self);
        }, 10);
    });
    
    // 키보드 이벤트도 처리 (Enter 키 등)
    textarea.addEventListener('keydown', function(e) {
        var self = this;
        setTimeout(function() {
            autoResizeTextarea(self);
        }, 10);
    });
    
    // 내용이 변경될 때마다 크기 조절 (프로그래밍 방식으로 값이 변경되는 경우 대응)
    var observer = new MutationObserver(function() {
        autoResizeTextarea(textarea);
    });
    
    // textarea의 속성 변경 감지
    observer.observe(textarea, {
        attributes: true,
        attributeFilter: ['value']
    });
}

// DOMContentLoaded 이벤트에서도 textarea 초기화 (빠른 초기화)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // textarea 자동 크기 조절 초기화
        initializeAutoResizeTextarea();
    });
} else {
    // DOM이 이미 로드된 경우 즉시 실행
    initializeAutoResizeTextarea();
}

// 페이지 로드 시 합계 계산
window.onload = function() {
    setTimeout(updateTotalAmount, 500);
    // 파일 업로드 초기화
    initializeFileUpload();
    // textarea 자동 크기 조절 재초기화 (CSS 적용 후)
    setTimeout(function() {
        var textarea = document.getElementById('internalmemo');
        if (textarea) {
            autoResizeTextarea(textarea);
        }
    }, 200);
    // 기존 파일 로드 (수정 모드일 경우)
    <?php if ($id > 0): ?>
    // 파일 목록 로드 (약간의 지연을 두어 DOM이 완전히 준비된 후 실행)
    setTimeout(function() {
        loadExistingFiles();
    }, 500);
    <?php endif; ?>
};

// 파일 업로드 관련 변수
var uploadedFiles = [];
var currentEstimateId = <?php echo $id > 0 ? $id : 'null'; ?>;

// 파일 업로드 초기화
function initializeFileUpload() {
    var fileUploadArea = document.getElementById('fileUploadArea');
    var fileInput = document.getElementById('fileInput');
    
    if (!fileUploadArea || !fileInput) return;
    
    // 클릭 이벤트
    fileUploadArea.addEventListener('click', function(e) {
        if (e.target === fileInput) return;
        fileInput.click();
    });
    
    // 드래그 앤 드롭 이벤트
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileUploadArea.style.borderColor = '#6c757d';
        fileUploadArea.style.background = '#e9ecef';
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileUploadArea.style.borderColor = '#ced4da';
        fileUploadArea.style.background = '#f8f9fa';
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileUploadArea.style.borderColor = '#ced4da';
        fileUploadArea.style.background = '#f8f9fa';
        
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileUpload(files);
        }
    });
    
    // 파일 선택 이벤트
    fileInput.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            handleFileUpload(this.files);
        }
    });
}

// 파일 업로드 처리
function handleFileUpload(files) {
    // 견적서 ID가 없으면 먼저 저장해야 함
    if (!currentEstimateId) {
        alert('먼저 견적서를 저장해주세요.');
        return;
    }
    
    // 파일 검증
    var validFiles = [];
    var maxSize = 10 * 1024 * 1024; // 10MB
    
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        
        // 파일 크기 검증
        if (file.size > maxSize) {
            alert(file.name + ' 파일 크기는 10MB를 초과할 수 없습니다.');
            continue;
        }
        
        validFiles.push(file);
    }
    
    if (validFiles.length === 0) {
        return;
    }
    
    // 업로드 진행 모달 표시
    showUploadModal();
    
    // FormData 생성
    var formData = new FormData();
    
    for (var i = 0; i < validFiles.length; i++) {
        formData.append('attached_files[]', validFiles[i]);
    }
    
    // 추가 데이터 설정
    formData.append('tablename', 'estimate');
    formData.append('item', 'attached');
    formData.append('upfilename', 'attached_files');
    formData.append('folderPath', '미래기업/uploads/estimate');
    formData.append('DBtable', 'picuploads');
    formData.append('num', currentEstimateId);
    
    // AJAX 요청
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/filedrive/fileprocess.php', true);
    
    xhr.onload = function() {
        hideUploadModal();
        
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                console.log('업로드 응답:', response);
                
                var successCount = 0;
                var errorCount = 0;
                
                if (Array.isArray(response)) {
                    response.forEach(function(item) {
                        if (item.status === 'success') {
                            successCount++;
                        } else if (item.status === 'error') {
                            errorCount++;
                        }
                    });
                }
                
                if (successCount > 0) {
                    // 업로드 완료 후 파일 목록 다시 로드
                    setTimeout(function() {
                        loadExistingFiles();
                    }, 800);
                    alert(successCount + '개의 파일이 성공적으로 업로드되었습니다.');
                }
                
                if (errorCount > 0) {
                    alert('오류 발생: ' + errorCount + '개의 파일 업로드 실패');
                }
                
                // 파일 입력 초기화
                document.getElementById('fileInput').value = '';
            } catch (e) {
                console.error('응답 파싱 오류:', e);
                alert('파일 업로드 응답 처리 중 오류가 발생했습니다.');
            }
        } else {
            alert('파일 업로드 중 오류가 발생했습니다.');
        }
    };
    
    xhr.onerror = function() {
        hideUploadModal();
        alert('파일 업로드 중 네트워크 오류가 발생했습니다.');
    };
    
    xhr.send(formData);
}

// 업로드 모달 표시/숨김
function showUploadModal() {
    var modal = document.getElementById('uploadProgressModal');
    if (modal) {
        var bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

function hideUploadModal() {
    var modal = document.getElementById('uploadProgressModal');
    if (modal) {
        var bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
            bsModal.hide();
        }
    }
}

// 기존 파일 로드
function loadExistingFiles() {
    if (!currentEstimateId) {
        console.log('견적서 ID가 없어 파일을 불러올 수 없습니다.');
        return;
    }
    
    console.log('파일 목록 조회 시작, estimate ID:', currentEstimateId);
    
    // GET 요청으로 파일 목록 조회 (corp와 동일한 방식)
    var url = '/filedrive/fileprocess.php?num=' + encodeURIComponent(currentEstimateId) + 
              '&tablename=estimate&item=attached&folderPath=' + encodeURIComponent('미래기업/uploads/estimate');
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('HTTP Error: ' + response.status);
        }
        return response.json();
    })
    .then(function(data) {
        console.log('파일 목록 조회 결과:', data);
        
        if (Array.isArray(data) && data.length > 0) {
            // 응답 데이터를 uploadedFiles 형식으로 변환
            uploadedFiles = data.map(function(file) {
                var fileInfo = {
                    fileId: file.fileId || file.picname || '',
                    realname: file.realname || 'Unknown',
                    link: file.link || file.webViewLink || '',
                    thumbnail: file.thumbnail || file.thumbnailLink || file.link || ''
                };
                
                // link가 없으면 fileId로 다운로드 링크 생성
                if (!fileInfo.link && fileInfo.fileId) {
                    fileInfo.link = '/filedrive/fileprocess.php?action=download&fileId=' + encodeURIComponent(fileInfo.fileId);
                }
                
                // thumbnail이 없으면 link 사용 (이미지인 경우)
                if (!fileInfo.thumbnail && fileInfo.link) {
                    // 이미지 파일인 경우 Google Drive 썸네일 링크 사용
                    if (fileInfo.realname && /\.(jpg|jpeg|png|gif|webp)$/i.test(fileInfo.realname)) {
                        if (fileInfo.fileId) {
                            fileInfo.thumbnail = 'https://drive.google.com/uc?id=' + fileInfo.fileId;
                        } else {
                            fileInfo.thumbnail = fileInfo.link;
                        }
                    } else {
                        fileInfo.thumbnail = fileInfo.link;
                    }
                }
                
                console.log('파일 정보 처리:', fileInfo);
                return fileInfo;
            });
            
            console.log('최종 uploadedFiles:', uploadedFiles);
            displayFiles();
        } else {
            console.log('파일 목록이 비어있습니다.');
            uploadedFiles = [];
            displayFiles();
        }
    })
    .catch(function(error) {
        console.error('파일 목록 로드 오류:', error);
        uploadedFiles = [];
        displayFiles();
    });
}

// 파일 목록 표시
function displayFiles() {
    var preview = document.getElementById('filePreview');
    if (!preview) {
        console.warn('filePreview 요소를 찾을 수 없습니다.');
        return;
    }
    
    preview.innerHTML = '';
    
    if (uploadedFiles.length === 0) {
        preview.style.display = 'none';
        return;
    }
    
    // 그리드 레이아웃을 위한 컨테이너 스타일
    preview.style.display = 'grid';
    preview.style.gridTemplateColumns = 'repeat(auto-fill, minmax(150px, 1fr))';
    preview.style.gap = '12px';
    preview.style.marginTop = '16px';
    preview.style.padding = '0';
    
    uploadedFiles.forEach(function(file, index) {
        var fileId = file.fileId || '';
        var realname = file.realname || 'Unknown';
        var link = file.link || '/filedrive/fileprocess.php?action=download&fileId=' + encodeURIComponent(fileId);
        var thumbnail = file.thumbnail || link;
        
        // 이미지 여부 확인
        var isImage = false;
        if (realname) {
            isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(realname);
        }
        if (!isImage && thumbnail) {
            isImage = thumbnail.match(/\.(jpg|jpeg|png|gif|webp)$/i) || thumbnail.startsWith('http');
        }
        
        var itemHtml = '<div class="file-preview-item" data-index="' + index + '" data-file-id="' + fileId + '" style="position: relative; border: 1px solid #dee2e6; border-radius: 8px; background: white; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.1)\';" onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'\';">';
        
        // 파일 클릭 가능하게 만들기
        var fileClickHandler = 'viewFile(' + index + ')';
        var cursorStyle = 'cursor: pointer;';
        
        if (isImage) {
            itemHtml += '<div class="file-image-wrapper" style="position:relative; ' + cursorStyle + '; width: 100%; height: 150px; overflow: hidden;" onclick="' + fileClickHandler + '">';
            itemHtml += '<img src="' + thumbnail + '" alt="' + realname + '" onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3Crect width=\'100\' height=\'100\' fill=\'%23f8f9fa\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3E이미지%3C/text%3E%3C/svg%3E\';" style="width:100%; height:100%; object-fit:cover;">';
            itemHtml += '<div class="file-overlay" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.3); display:none; align-items:center; justify-content:center; color:white; font-size:1.2rem;">👁️</div>';
            itemHtml += '</div>';
        } else {
            itemHtml += '<div class="file-icon-wrapper" style="' + cursorStyle + '; width: 100%; height: 150px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; position: relative;" onclick="' + fileClickHandler + '">';
            itemHtml += '<div style="font-size: 48px;">📄</div>';
            itemHtml += '<div class="file-overlay" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.3); display:none; align-items:center; justify-content:center; color:white; font-size:1.2rem;">⬇️</div>';
            itemHtml += '</div>';
        }
        
        itemHtml += '<div class="file-info" style="padding: 8px; font-size: 12px; word-break: break-all; cursor: pointer; border-top: 1px solid #f0f0f0;" onclick="' + fileClickHandler + '">' + realname + '</div>';
        itemHtml += '<button type="button" onclick="event.stopPropagation(); removeFile(' + index + ', \'' + fileId + '\')" style="position: absolute; top: 4px; right: 4px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 16px; line-height: 1; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" title="삭제">×</button>';
        itemHtml += '</div>';
        
        var itemElement = document.createElement('div');
        itemElement.innerHTML = itemHtml;
        var fileItem = itemElement.firstElementChild;
        
        // 호버 효과 추가
        var imageWrapper = fileItem.querySelector('.file-image-wrapper');
        var iconWrapper = fileItem.querySelector('.file-icon-wrapper');
        var fileInfo = fileItem.querySelector('.file-info');
        
        function showOverlay() {
            var overlay = fileItem.querySelector('.file-overlay');
            if (overlay) overlay.style.display = 'flex';
        }
        
        function hideOverlay() {
            var overlay = fileItem.querySelector('.file-overlay');
            if (overlay) overlay.style.display = 'none';
        }
        
        if (imageWrapper) {
            imageWrapper.addEventListener('mouseenter', showOverlay);
            imageWrapper.addEventListener('mouseleave', hideOverlay);
        }
        if (iconWrapper) {
            iconWrapper.addEventListener('mouseenter', showOverlay);
            iconWrapper.addEventListener('mouseleave', hideOverlay);
        }
        if (fileInfo) {
            fileInfo.addEventListener('mouseenter', showOverlay);
            fileInfo.addEventListener('mouseleave', hideOverlay);
        }
        
        preview.appendChild(fileItem);
    });
}

// 파일 보기 (전역 함수로 등록)
window.viewFile = function(index) {
    if (!uploadedFiles[index]) {
        console.error('파일 정보를 찾을 수 없습니다. index:', index);
        return;
    }
    
    var file = uploadedFiles[index];
    var link = file.link || '/filedrive/fileprocess.php?action=download&fileId=' + encodeURIComponent(file.fileId || '');
    
    if (!link) {
        alert('파일 링크를 가져올 수 없습니다.');
        return;
    }
    
    // 이미지 여부 확인
    var isImage = false;
    if (file.realname) {
        isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(file.realname);
    }
    
    if (isImage) {
        // 이미지인 경우: 새 창에서 열기
        var width = 1000;
        var height = 700;
        var left = (window.innerWidth / 2) - (width / 2) + window.screenX;
        var top = (window.innerHeight / 2) - (height / 2) + window.screenY;
        window.open(link, 'imageViewer_' + Date.now(), 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top + ', scrollbars=yes, resizable=yes');
    } else {
        // PDF나 기타 파일은 새 창에서 열기 또는 다운로드
        var isPdf = file.realname && file.realname.toLowerCase().match(/\.pdf$/i);
        if (isPdf) {
            window.open(link, '_blank');
        } else {
            // 다운로드 링크 생성
            var a = document.createElement('a');
            a.href = link;
            a.download = file.realname || 'download';
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }
};

// 파일 삭제 (전역 함수로 등록)
window.removeFile = function(index, fileId) {
    if (!confirm('이 파일을 삭제하시겠습니까?')) {
        return;
    }
    
    fetch('/filedrive/fileprocess.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            fileId: fileId,
            tablename: 'estimate',
            item: 'attached',
            folderPath: '미래기업/uploads/estimate',
            DBtable: 'picuploads'
        })
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.status === 'success' || data.success) {
            uploadedFiles.splice(index, 1);
            displayFiles();
            alert('파일이 삭제되었습니다.');
        } else {
            alert('파일 삭제 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(function(error) {
        console.error('파일 삭제 오류:', error);
        alert('파일 삭제 중 오류가 발생했습니다.');
    });
};

// 저장 성공 시 파일 업로드 영역 활성화 및 파일 목록 로드
var originalHandleJsonResponse = handleJsonResponse;
handleJsonResponse = function(data) {
    originalHandleJsonResponse(data);
    
    // 저장 성공 시 견적서 ID 업데이트 및 파일 목록 로드
    if (data.success && data.id) {
        currentEstimateId = data.id;
        // 파일 목록 다시 로드
        setTimeout(function() {
            loadExistingFiles();
        }, 300);
    }
};

})();
</script>

    <!-- 거래처 검색 모달 -->
    <div class="modal fade" id="customerSearchModal" tabindex="-1" aria-labelledby="customerSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerSearchModalLabel">주소록 검색</h5>
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
                                    <th>회사명</th>
                                    <th>표시이름</th>
                                    <th>이메일</th>
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
        });

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

            fetch('search_address_book.php?q=' + encodeURIComponent(searchTerm))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.customers && data.customers.length > 0) {
                        var html = '';
                        data.customers.forEach(function(customer, index) {
                            var customerData = encodeURIComponent(JSON.stringify(customer));
                            html += '<tr>';
                            html += '<td>' + (customer.company_name || '-') + '</td>';
                            html += '<td>' + (customer.display_name || '-') + '</td>';
                            html += '<td>' + (customer.email || '-') + '</td>';
                            html += '<td>' + (customer.mobile_phone || customer.work_phone || '-') + '</td>';
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
        function selectCustomer(customer) {
            // 상호(성명)에는 회사명이 있으면 회사명, 없으면 표시이름 사용
            var nameToUse = customer.company_name || customer.display_name || '';
            document.getElementById('contact_name').value = nameToUse;
            
            // 사업자번호는 주소록에 없으므로 비움 (필요시 추가)
            // document.getElementById('business_registration_number').value = '';
            
            // 거래처 ID 설정 (hidden input)
            var customerIdInput = document.getElementById('customer_id');
            if (customerIdInput) {
                customerIdInput.value = customer.num || ''; 
            }

            // 참조 필드는 거래처 검색 시 자동 입력하지 않음 (사용자 직접 입력)
            // var referenceInput = document.querySelector('input[name="reference"]');
            
            // 이메일 설정 및 표시
            var emailInput = document.getElementById('email');
            var emailDisplay = document.getElementById('email_display');
            if (emailInput) {
                emailInput.value = customer.email || '';
                if (emailDisplay) {
                    if (customer.email) {
                        emailDisplay.innerHTML = '<i class="bi bi-envelope-check"></i> ' + customer.email;
                        emailDisplay.style.color = '#adb5bd'; // 흐린 색상
                    } else {
                        emailDisplay.innerHTML = '';
                    }
                }
            }
            
            // 팩스는 주소록에 없으므로 유지하거나 비움
            // var faxInput = document.querySelector('input[name="fax"]');
            // if (faxInput) faxInput.value = '';
            
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
            // 참조 필드는 포맷팅 불필요 (자유 텍스트 입력)
            const faxInput = document.querySelector('input[name="fax"]');
            const bizNumInput = document.querySelector('input[name="business_registration_number"]');

            function applyPhoneFormat(e) {
                e.target.value = formatPhoneNumber(e.target.value);
            }

            function applyBizFormat(e) {
                e.target.value = formatBizNumber(e.target.value);
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
                // 엔터키 입력 시 검색 버튼 클릭 트리거
                contactNameInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // 폼 제출 방지
                        document.getElementById('searchCustomerBtn').click();
                    }
                });

                contactNameInput.addEventListener('blur', function() {
                    const searchTerm = this.value.trim();
                    // if (searchTerm) {
                    //     fetchCustomerInfo(searchTerm);
                    // }
                });

                // 페이지 로드 1초 후 거래처명이 있으면 자동 검색 (구매카트 연동 시 유용)
                setTimeout(function() {
                    const initialSearchTerm = contactNameInput.value.trim();
                    if (initialSearchTerm) {
                        // fetchCustomerInfo(initialSearchTerm);
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