<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

if(!isset($_SESSION["level"]) || $level>5) {
    $_SESSION["url"]='https://8440.co.kr/order/write_form.php';
    sleep(1);
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}

include getDocumentRoot() . "/common.php";
require_once(includePath('lib/mydb.php'));

include getDocumentRoot() . '/load_header.php';

// 모드 확인 (새로 작성 또는 수정)
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$id = isset($_REQUEST["id"]) ? (int)$_REQUEST["id"] : 0;

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>데이터베이스 연결 실패: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}

// 수정 모드일 경우 기존 데이터 조회
$order_data = null;
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `order` WHERE id = :id AND is_deleted = 0");
    $stmt->execute([':id' => $id]);
    $order_data = $stmt->fetch();

    if (!$order_data) {
        echo "<script>alert('존재하지 않는 발주서입니다.'); location.href='index.php';</script>";
        exit;
    }
}

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
if ($order_data && $order_data['order_no']) {
    $order_no = $order_data['order_no'];
}
?>

<title><?=$title_message?></title>
<!-- Tabulator CSS -->
<link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<style>
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

.order-container {
    max-width: 100%;
    margin: 10px auto;
    background: white;
    border: 1px solid var(--dashboard-border);
    border-radius: 8px;
    box-shadow: var(--dashboard-shadow);
    font-size: 16px;
    overflow: hidden;
}

.order-header {
    background: var(--dashboard-secondary);
    color: #000;
    padding: 12px 20px;
    font-weight: 500;
    font-size: 18px;
    border-bottom: 1px solid var(--dashboard-border);
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

.section-header {
    background: var(--dashboard-secondary);
    padding: 8px 12px;
    text-align: center;
    font-weight: 500;
    border-bottom: 1px solid var(--dashboard-border);
    color: var(--dashboard-text);
}

.info-grid {
    display: grid;
    grid-template-columns: 100px 1fr;
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

.tabulator {
    border: 1px solid var(--dashboard-border);
    border-radius: 6px;
    overflow: hidden;
    box-shadow: var(--dashboard-shadow);
}

.tabulator .tabulator-header {
    background: var(--dashboard-secondary);
    color: var(--dashboard-text);
    border-bottom: 1px solid var(--dashboard-border);
}

.tabulator .tabulator-col-title {
    color: var(--dashboard-text);
    font-weight: 500;
    font-size: 14px;
}

.summary-section {
    margin: 20px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 20px;
}

.delivery-info {
    border: 1px solid #ddd;
}

.delivery-grid {
    display: grid;
    grid-template-columns: 80px 1fr;
}

.summary-table {
    border: 1px solid #ddd;
    width: 200px;
}

.summary-table th, .summary-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.summary-table th {
    background: #f5f5f5;
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
</style>

</head>
<body>
<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="order-container">
    <div class="order-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <span><?php echo $title_message; ?></span>
                <button type="button" class="btn btn-sm" onclick="location.href='write_form.php'">새 발주서</button>
                <button type="button" class="btn btn-sm" onclick="location.href='index.php'">목록</button>
            </div>
            <div class="header-buttons" style="display: flex; gap: 5px;">
                <button type="button" class="btn btn-sm" onclick="addRow()">행 추가</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveOrder()">저장</button>
                <?php if ($id > 0 && $mode !== 'copy'): ?>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteOrder()">삭제</button>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelOrder()">취소</button>
            </div>
        </div>
    </div>

    <form id="orderForm" method="POST" action="insert.php">
        <?php if ($id > 0 && $mode !== 'copy'): ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="action" value="update">
        <?php else: ?>
            <input type="hidden" name="action" value="insert">
        <?php endif; ?>

        <div class="order-title">발 주 서</div>

        <div class="order-info">
            <!-- 왼쪽: 발주자 정보 -->
            <div class="info-section">
                <div class="section-header">발주자</div>
                <div class="info-grid">
                    <div class="info-label">거래처명</div>
                    <div class="info-value">
                        <input type="text" name="contact_name" value="<?php echo $order_data ? htmlspecialchars($order_data['contact_name']) : ''; ?>" placeholder="거래처명을 입력하세요">
                    </div>
                    <div class="info-label">발주일자</div>
                    <div class="info-value">
                        <input type="date" name="issue_date" value="<?php echo $order_data ? $order_data['issue_date'] : date('Y-m-d'); ?>" required>
                    </div>
                    <div class="info-label">전화번호</div>
                    <div class="info-value">
                        <input type="text" name="phone" value="<?php echo $order_data ? htmlspecialchars($order_data['phone']) : ''; ?>" placeholder="전화번호를 입력하세요">
                    </div>
                    <div class="info-label">팩스번호</div>
                    <div class="info-value">
                        <input type="text" name="fax" value="<?php echo $order_data ? htmlspecialchars($order_data['fax']) : ''; ?>" placeholder="팩스번호를 입력하세요">
                    </div>
                    <div class="info-label">합계금액</div>
                    <div class="info-value">
                        <input type="text" id="totalAmount" name="total_amount" value="₩0" readonly style="background: #f8f9fa; font-weight: bold;">
                    </div>
                </div>
            </div>

            <!-- 오른쪽: 공급업체 정보 -->
            <div class="info-section">
                <div class="section-header">공급업체</div>
                <div style="text-align: right; padding: 10px;">
                    <span style="font-size: 11px;">PAGE: 1 / 1</span>
                </div>
                <div class="info-grid">
                    <div class="info-label">등록번호</div>
                    <div class="info-value">
                        <input type="text" name="supplier_code" value="722-88-00035" readonly style="background: #f8f9fa;">
                    </div>
                    <div class="info-label">상호</div>
                    <div class="info-value">
                        <input type="text" name="supplier_name" value="주식회사미래기업" readonly style="background: #f8f9fa;" required>
                    </div>
                    <div class="info-label">주소</div>
                    <div class="info-value">
                        <input type="text" name="supplier_address" value="경기도 김포시 양촌읍 옹심리 220-27 (옹심리)" readonly style="background: #f8f9fa;">
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
            <div id="orderItemsTable" style="min-width: 1000px;"></div>
        </div>

        <!-- 하단 정보 -->
        <div class="summary-section">
            <div class="delivery-info">
                <div class="delivery-grid">
                    <div class="info-label">납기일자</div>
                    <div class="info-value">
                        <input type="date" name="delivery_date" value="<?php echo $order_data ? $order_data['delivery_date'] : ''; ?>">
                    </div>
                    <div class="info-label">유효일자</div>
                    <div class="info-value">
                        <input type="date" name="valid_date" value="<?php echo $order_data ? $order_data['valid_date'] : ''; ?>">
                    </div>
                    <div class="info-label">납품장소</div>
                    <div class="info-value">
                        <input type="text" name="delivery_location" value="<?php echo $order_data ? htmlspecialchars($order_data['delivery_location']) : ''; ?>">
                    </div>
                    <div class="info-label">결제조건</div>
                    <div class="info-value">
                        <input type="text" name="payment_terms" value="<?php echo $order_data ? htmlspecialchars($order_data['payment_terms']) : ''; ?>">
                    </div>
                </div>
            </div>

            <table class="summary-table">
                <tr>
                    <th>합계</th>
                    <td id="summaryTotal">0</td>
                </tr>
            </table>
        </div>

        <!-- 비고 -->
        <div class="note-section">
            <div class="note-header">비고</div>
            <div class="note-content">
                <textarea name="note" placeholder="정보를 입력합니다."><?php echo $order_data ? htmlspecialchars($order_data['note']) : ''; ?></textarea>
            </div>
        </div>

    </form>
</div>

<script>
// 전역 변수
let orderTable;
let orderItems = [];

// 초기 데이터
<?php if ($order_data && $order_data['order_items']): ?>
orderItems = <?php echo $order_data['order_items']; ?>;
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
        layout: "fitDataStretch", // 행 합치기 방지 및 전체 너비 사용
        height: "300px",
        autoResize: false, // 자동 리사이즈 방지
        resizableColumns: false, // 컬럼 리사이즈 방지
        columns: [
            {title: "순번", field: "순번", width: 50, editor: "input", resizable: false},
            {title: "품목", field: "품목", width: 350, editor: "input", resizable: false,
             cellClick: function() { return true; }}, // 품목 폭 대폭 증가
            {title: "규격", field: "규격", width: 250, editor: "input", resizable: false,
             cellClick: function() { return true; }}, // 규격 폭 대폭 증가
            {title: "수량", field: "수량", width: 70, editor: "input", validator: "numeric", resizable: false,
             cellClick: function() { return true; }},
            {title: "단가", field: "단가", width: 80, editor: "input", validator: "numeric", resizable: false, // 최소 크기
             formatter: function(cell) {
                 const value = cell.getValue();
                 return value ? Number(value).toLocaleString() : '';
             },
             cellClick: function() { return true; }},
            {title: "공급가액", field: "공급가액", width: 90, editor: false, validator: "numeric", resizable: false, // 최소 크기
             formatter: function(cell) {
                 const value = cell.getValue();
                 return value ? Number(value).toLocaleString() : '';
             },
             cellClick: function(e, cell) {
                 console.log('공급가액은 자동 계산됩니다 (수량 × 단가)');
                 return false;
             }},
            {title: "세액", field: "세액", width: 70, editor: false, validator: "numeric", resizable: false, // 최소 크기
             formatter: function(cell) {
                 const value = cell.getValue();
                 return value ? Number(value).toLocaleString() : '';
             },
             cellClick: function(e, cell) {
                 console.log('세액은 자동 계산됩니다 (공급가액의 10%)');
                 return false;
             }},
            {title: "비고", field: "비고", width: 120, editor: "input", resizable: false}
        ],
        cellEdited: function(cell) {
            console.log(`📝 [DEBUG] 셀 편집: ${cell.getField()} = ${cell.getValue()}`);
            updateCalculations(cell);
        },
        rowAdded: function(row) {
            console.log('📋 [DEBUG] 새 행 추가됨');
        }
    });

    // 초기 로드 시 합계 계산
    setTimeout(() => {
        updateTotalAmount();
    }, 100);
});

// 계산 업데이트
function updateCalculations(cell) {
    const row = cell.getRow();
    const data = row.getData();

    // 수량 또는 단가가 변경된 경우 자동 계산
    if (cell.getField() === '수량' || cell.getField() === '단가') {
        // 입력값 정리 (쉼표 제거 후 숫자 변환)
        let 수량 = data.수량;
        let 단가 = data.단가;

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
        const 공급가액 = Math.round(수량 * 단가);

        // 세액 = 공급가액 × 10% (부가세, 소수점 반올림)
        const 세액 = Math.round(공급가액 * 0.1);

        console.log(`📊 [DEBUG] 자동 계산 상세:`);
        console.log(`   - 수량: ${수량} (원본: ${data.수량})`);
        console.log(`   - 단가: ${단가.toLocaleString()} (원본: ${data.단가})`);
        console.log(`   - 공급가액: ${공급가액.toLocaleString()} (${수량} × ${단가})`);
        console.log(`   - 세액: ${세액.toLocaleString()} (공급가액의 10%)`);

        // 한 번에 업데이트 (이벤트 루프 방지)
        row.update({
            공급가액: 공급가액,
            세액: 세액
        });

        // 행 별 계산 완료 후 전체 합계 업데이트
        setTimeout(() => {
            updateTotalAmount();
        }, 10);
    } else {
        // 다른 필드 변경 시에도 합계 업데이트
        setTimeout(() => {
            updateTotalAmount();
        }, 10);
    }
}

// 전체 합계 업데이트
function updateTotalAmount() {
    const data = orderTable.getData();
    let totalSupply = 0; // 공급가액 합계
    let totalTax = 0;    // 세액 합계
    let grandTotal = 0;  // 총 합계

    data.forEach(row => {
        const 공급가액 = parseFloat(row.공급가액) || 0;
        const 세액 = parseFloat(row.세액) || 0;

        totalSupply += 공급가액;
        totalTax += 세액;
    });

    grandTotal = totalSupply + totalTax;

    // 합계금액 필드 업데이트
    document.getElementById('totalAmount').value = '₩' + grandTotal.toLocaleString();

    // 하단 요약 정보가 있다면 업데이트
    const summaryElement = document.getElementById('summaryTotal');
    if (summaryElement) {
        summaryElement.textContent = grandTotal.toLocaleString();
    }

    console.log(`💰 [DEBUG] 합계 계산: 공급가액(${totalSupply.toLocaleString()}) + 세액(${totalTax.toLocaleString()}) = 총액(${grandTotal.toLocaleString()})`);
}

// 행 추가
function addRow() {
    const data = orderTable.getData();
    const newRowNum = data.length + 1;

    orderTable.addRow({
        순번: newRowNum,
        품목: '',
        규격: '',
        수량: '',
        단가: '',
        공급가액: '',
        세액: '',
        비고: ''
    });
}

// AJAX 저장 함수 - 디버그 코드 포함
function saveOrder() {
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
        const formData = collectFormData();
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
        .then(response => {
            console.log('📡 [DEBUG] 서버 응답 상태:', response.status, response.statusText);

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
            }

            return response.text();
        })
        .then(data => {
            console.log('📥 [DEBUG] 서버 응답 데이터:', data);

            hideLoadingSpinner();

            // 응답 데이터 파싱
            try {
                // JSON 응답인지 확인
                const jsonData = JSON.parse(data);
                console.log('📊 [DEBUG] JSON 파싱 성공:', jsonData);

                handleJsonResponse(jsonData);
            } catch (e) {
                // HTML 응답 처리
                console.log('📄 [DEBUG] HTML 응답 처리');
                handleHtmlResponse(data);
            }
        })
        .catch(error => {
            console.error('❌ [DEBUG] AJAX 오류:', error);
            hideLoadingSpinner();
            showErrorMessage('저장 중 오류가 발생했습니다: ' + error.message);
        });

    } catch (error) {
        console.error('❌ [DEBUG] 폼 데이터 수집 오류:', error);
        hideLoadingSpinner();
        showErrorMessage('폼 데이터 처리 중 오류가 발생했습니다.');
    }
}

// 폼 데이터 수집
function collectFormData() {
    console.log('📋 [DEBUG] 폼 데이터 수집 시작');

    const formData = new URLSearchParams();

    // 기본 필드들
    const basicFields = [
        'action', 'id', 'order_no', 'issue_date', 'supplier_code',
        'supplier_name', 'supplier_address', 'business_type', 'business_item',
        'supplier_phone', 'supplier_fax', 'contact_name', 'phone', 'fax',
        'delivery_date', 'delivery_location', 'payment_terms', 'note', 'status'
    ];

    basicFields.forEach(fieldName => {
        const element = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
        if (element) {
            const value = element.value || '';
            formData.append(fieldName, value);
            console.log(`📌 [DEBUG] ${fieldName}:`, value);
        } else {
            console.warn(`⚠️ [DEBUG] 필드를 찾을 수 없음: ${fieldName}`);
        }
    });

    // Tabulator 데이터 수집
    try {
        const orderItems = orderTable.getData();
        const orderItemsJson = JSON.stringify(orderItems);
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
        showSuccessMessage(data.message || '발주서가 성공적으로 저장되었습니다.');

        if (data.redirect_url) {
            console.log('🔄 [DEBUG] 리다이렉트:', data.redirect_url);
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 1500);
        } else if (data.id) {
            console.log('🔄 [DEBUG] view.php로 이동, ID:', data.id);
            setTimeout(() => {
                window.location.href = `view.php?id=${data.id}`;
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
    const alertMatch = data.match(/alert\(['"]([^'"]+)['"]\)/);
    const locationMatch = data.match(/location\.href\s*=\s*['"]([^'"]+)['"]/);

    if (alertMatch) {
        const message = alertMatch[1];
        console.log('📢 [DEBUG] 추출된 알림 메시지:', message);

        if (message.includes('성공')) {
            showSuccessMessage(message);

            if (locationMatch) {
                const url = locationMatch[1];
                console.log('🔄 [DEBUG] 추출된 리다이렉트 URL:', url);
                setTimeout(() => {
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
    const loadingHtml = `
        <div id="loadingOverlay" style="
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 9999;
            display: flex; justify-content: center; align-items: center;
        ">
            <div style="background: white; padding: 20px; border-radius: 8px; text-align: center;">
                <div style="border: 4px solid #f3f3f3; border-radius: 50%; border-top: 4px solid #3498db; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px;"></div>
                <div>저장 중...</div>
            </div>
        </div>
        <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        </style>
    `;

    document.body.insertAdjacentHTML('beforeend', loadingHtml);
    console.log('🔄 [DEBUG] 로딩 스피너 표시');
}

function hideLoadingSpinner() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
        console.log('✅ [DEBUG] 로딩 스피너 숨김');
    }
}

function showSuccessMessage(message) {
    console.log('✅ [DEBUG] 성공 메시지 표시:', message);

    // Toast 스타일 성공 메시지
    const toastHtml = `
        <div id="successToast" style="
            position: fixed; top: 20px; right: 20px; z-index: 10000;
            background: #28a745; color: white; padding: 15px 20px;
            border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out;
        ">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">✅</span>
                <span>${message}</span>
            </div>
        </div>
        <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        </style>
    `;

    document.body.insertAdjacentHTML('beforeend', toastHtml);

    // 3초 후 자동 제거
    setTimeout(() => {
        const toast = document.getElementById('successToast');
        if (toast) toast.remove();
    }, 3000);
}

function showErrorMessage(message) {
    console.error('❌ [DEBUG] 오류 메시지 표시:', message);

    // Toast 스타일 오류 메시지
    const toastHtml = `
        <div id="errorToast" style="
            position: fixed; top: 20px; right: 20px; z-index: 10000;
            background: #dc3545; color: white; padding: 15px 20px;
            border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out; max-width: 400px;
        ">
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <span style="font-size: 18px;">❌</span>
                <div>
                    <div style="font-weight: bold; margin-bottom: 5px;">오류가 발생했습니다</div>
                    <div style="font-size: 14px; line-height: 1.4;">${message}</div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', toastHtml);

    // 5초 후 자동 제거
    setTimeout(() => {
        const toast = document.getElementById('errorToast');
        if (toast) toast.remove();
    }, 5000);
}

function submitOrder() {
    if (validateForm()) {
        document.getElementById('orderForm').submit();
    }
}

function cancelOrder() {
    if (confirm('작성 중인 내용이 사라집니다. 계속하시겠습니까?')) {
        location.href = 'index.php';
    }
}

function deleteOrder() {
    if (!confirm('정말로 이 발주서를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) {
        return;
    }

    const orderId = document.querySelector('input[name="id"]')?.value;
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
    .then(response => {
        console.log('📡 [DEBUG] 삭제 응답 상태:', response.status, response.statusText);
        return response.json();
    })
    .then(data => {
        console.log('📥 [DEBUG] 삭제 응답 데이터:', data);
        hideLoadingSpinner();

        if (data.success) {
            alert('발주서가 성공적으로 삭제되었습니다.');
            location.href = 'index.php';
        } else {
            alert('삭제 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('❌ [DEBUG] 삭제 오류:', error);
        hideLoadingSpinner();
        alert('삭제 중 오류가 발생했습니다: ' + error.message);
    });
}

// 폼 유효성 검사
function validateForm() {
    const supplierName = document.querySelector('input[name="supplier_name"]').value.trim();
    const issueDate = document.querySelector('input[name="issue_date"]').value;

    if (!supplierName) {
        alert('공급업체 상호를 입력해주세요.');
        return false;
    }

    if (!issueDate) {
        alert('발주일자를 선택해주세요.');
        return false;
    }

    // Tabulator 데이터를 hidden input에 저장
    const tableData = orderTable.getData();
    const orderItemsInput = document.createElement('input');
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
</script>

</body>
</html>