<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 통합 발주 목록 (원자재 + 부자재)
 * 탭 없이 리스트로 통합, 구분 컬럼 추가
 */

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    $_SESSION["url"] = getBaseUrl() . '/integratedordering/list.php';
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

include getDocumentRoot() . '/load_header.php';

// 페이지네이션 및 검색 변수
$page = $_REQUEST["page"] ?? '';
$scale = $_REQUEST["scale"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 날짜 설정
$currentDate = date("Y-m-d");
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

if ($fromdate === "" || $todate === "") {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate)));
    $todate = $currentDate;
}
$Transtodate = $todate;

// SQL 기본 조건
// 원자재, 부자재 모두 outdate(접수일) 기준
$Andis_deleted = " AND (is_deleted IS NULL OR is_deleted='0')";

// 검색 쿼리 구성
$order_sql_common = " FROM " . $DB . ".eworks WHERE (
    outdate BETWEEN '$fromdate' AND '$Transtodate'
) " . $Andis_deleted;

if ($mode === "search" && $search !== "") {
    $search = str_replace(' ', '', $search);
    $order_sql_search = " AND (";
    $order_sql_search .= "replace(outworkplace,' ','') like '%$search%' ";
    $order_sql_search .= "OR steel_item like '%$search%' ";
    $order_sql_search .= "OR spec like '%$search%' ";
    $order_sql_search .= "OR company like '%$search%' ";
    $order_sql_search .= "OR supplier like '%$search%' ";
    $order_sql_search .= "OR request_comment like '%$search%' ";
    $order_sql_search .= "OR model like '%$search%' ";
    $order_sql_search .= ")";
    $order_sql_common .= $order_sql_search;
}

// 정렬 설정
$sort_column = $_REQUEST["sort_column"] ?? 'num';
$sort_order = $_REQUEST["sort_order"] ?? 'desc';

// 정렬 컬럼 화이트리스트
$allowed_columns = [
    'num', 'eworks_item', 'which', 'outdate', 'outworkplace', 
    'model', 'steel_item', 'spec', 'steelnum', 'supplier', 
    'requestdate', 'indate'
];

if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'num';
}

$sort_order = strtolower($sort_order) === 'asc' ? 'asc' : 'desc';
$next_sort_order = $sort_order === 'asc' ? 'desc' : 'asc';

// 정렬 쿼리 구성
if ($sort_column === 'outdate') {
    // 접수일: 모두 outdate 기준
    $order_sql_order = " ORDER BY outdate $sort_order, num DESC";
} else {
    $order_sql_order = " ORDER BY $sort_column $sort_order, num DESC";
}

// 데이터 조회
$order_sql = "SELECT * " . $order_sql_common . $order_sql_order;

try {
    $stmh = $pdo->query($order_sql);
    $order_total_row = $stmh->rowCount();
} catch (PDOException $e) {
    $order_total_row = 0;
    error_log($e->getMessage());
}

// 정렬 링크 생성 함수
function getSortLink($column, $label, $current_column, $current_order, $next_order) {
    global $fromdate, $todate, $search, $mode;
    
    $active = $column === $current_column;
    $icon = '';
    if ($active) {
        $icon = $current_order === 'asc' ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>';
    }
    
    $url = "?mode=$mode&fromdate=$fromdate&todate=$todate&search=$search&sort_column=$column&sort_order=" . ($active ? $next_order : 'desc');
    
    return "<a href='$url' class='text-white text-decoration-none'>$label$icon</a>";
}

?>
<title>통합 발주 현황 </title>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    :root {
        /* 그레이 톤 색상 변경 */
        --bg-primary: #ffffff;
        --bg-secondary: #ffffff;
        --bg-card: #f8f9fa;
        --bg-gradient-start: #6c757d; /* Gray */
        --bg-gradient-end: #495057;   /* Darker Gray */
        --text-primary: #333333;
        --text-secondary: #666666;
        --text-white: #ffffff;
        --border-color: #e0e0e0;
        --border-light: #f0f0f0;
        --shadow: rgba(0,0,0,0.08);
        --shadow-hover: rgba(108, 117, 125, 0.2); /* Gray shadow */
        --hover-bg: #f8f9fa; /* Light gray hover */
    }

    [data-theme="dark"] {
        /* 다크 모드 색상 */
        --bg-primary: #1a1a2e;
        --bg-secondary: #16213e;
        --bg-card: #1e2a3a;
        --bg-gradient-start: #495057;
        --bg-gradient-end: #343a40;
        --text-primary: #e2e8f0;
        --text-secondary: #cbd5e0;
        --text-white: #ffffff;
        --border-color: #4a5568;
        --border-light: #2d3748;
        --shadow: rgba(0,0,0,0.3);
        --shadow-hover: rgba(108, 117, 125, 0.5);
        --hover-bg: #2d3748;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .order-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 15px 20px 25px 20px;
    }

    .page-header {
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        color: white;
        padding: 20px 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(108, 117, 125, 0.15);
    }

    .page-header h1 {
        margin: 0 0 8px 0;
        font-size: 22px;
        font-weight: 600;
        color: white;
    }

    .page-header p {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 13px;
    }

    .filter-section {
        background: var(--bg-card);
        padding: 15px 18px;
        border-radius: 9px;
        margin-bottom: 15px;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px var(--shadow);
    }

    .filter-form {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-group label {
        font-size: 13px;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .filter-group input,
    .filter-group select {
        padding: 6px 10px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        font-size: 13px;
        background: white;
        color: var(--text-primary);
        transition: border-color 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #6c757d;
        box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.1);
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        color: var(--text-white);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px var(--shadow-hover);
        color: var(--text-white);
    }

    .btn-secondary {
        background: #6c757d;
        color: var(--text-white);
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        color: var(--text-white);
    }
    
    .btn-dark {
        background: #343a40;
        color: #fff;
    }
    
    .btn-dark:hover {
        background: #23272b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 58, 64, 0.3);
    }

    .table-container {
        background: white;
        border-radius: 9px;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px var(--shadow);
        overflow-x: auto;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .order-table thead {
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        color: var(--text-white);
    }

    .order-table th {
        padding: 12px 10px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
    }

    .order-table th.sortable {
        cursor: pointer;
        user-select: none;
        position: relative;
        padding-right: 25px;
    }

    .order-table th.sortable:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .order-table td {
        padding: 12px 10px;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
        white-space: nowrap;
    }

    .order-table tbody tr:hover {
        background: var(--hover-bg);
        transition: background 0.2s ease;
    }
    
    .text-center { text-align: center; }
    .text-danger { color: #dc3545 !important; }
    .fw-bold { font-weight: 700 !important; }

    /* 구분 배지 스타일 */
    .badge-main { background-color: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; } /* 원자재 */
    .badge-aux { background-color: #495057; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; } /* 부자재 */
    
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        color: white;
    }
    .bg-primary { background-color: #6c757d !important; }
    .bg-danger { background-color: #dc3545 !important; } /* Keep danger red for importance */
    .bg-secondary { background-color: #adb5bd !important; }

    /* 모바일 반응형 */
    @media (max-width: 768px) {
        .order-container { padding: 5px; }
        .table-container { overflow-x: auto; }
        
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-group { flex-direction: column; align-items: stretch; }
        
        /* 카드형 리스트 스타일 (모바일) */
        .mobile-card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 10px;
            margin-bottom: 10px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .mobile-card-body div {
            margin-bottom: 3px;
        }
        .mobile-card-label {
            font-weight: 600;
            color: #6c757d;
            margin-right: 5px;
        }
        
        /* PC 테이블 숨기기 */
        .pc-table { display: none; }
    }
    
    @media (min-width: 769px) {
        .mobile-list { display: none; }
    }

    @keyframes blink {
        0%   { opacity: 1; }
        50%  { opacity: 0.35; }
        100% { opacity: 1; }
    }
    .blink {
        animation: blink 1s linear infinite;
    }

    #floatingCartBtn {
        position: absolute;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
</style>

<!-- 발주서 작성 모달 -->
<div class="modal fade" id="orderWriteModal" tabindex="-1" aria-labelledby="orderWriteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 95%; height: 95%;">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="orderWriteModalLabel">
                    <i class="bi bi-cart-check"></i> 구매카트에서 발주서 작성
                </h5>
                <div>
                    <button type="button" class="btn btn-success btn-sm me-1" onclick="iframeSaveOrderFromCart()">저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="iframeCancelOrderFromCart()">취소</button>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="height: calc(100% - 50px); overflow: hidden;">
                <iframe id="orderWriteIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    // 전체 선택/해제
    function toggleAll(source) {
        var checkboxes = document.querySelectorAll('.row-checkbox');
        for(var i=0; i<checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    // 구매카트 담기 (모달 열기)
    function addToCart() {
        var checkboxes = document.querySelectorAll('.row-checkbox:checked');
        var nums = [];
        
        if(checkboxes.length === 0) {
            Swal.fire('알림', '선택된 항목이 없습니다.', 'warning');
            return;
        }

        checkboxes.forEach(function(checkbox) {
            nums.push(checkbox.value);
        });

        // 모달 열기 (발주서 작성 화면)
        openOrderWriteModal(nums);
    }

    // 발주서 작성 모달 열기
    function openOrderWriteModal(itemNums) {
        var modalElement = document.getElementById('orderWriteModal');
        if (!modalElement) {
            console.error('orderWriteModal 요소를 찾을 수 없습니다.');
            return;
        }

        var orderWriteModal = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: true
        });

        var iframe = document.getElementById('orderWriteIframe');
        if (!iframe) {
            console.error('orderWriteIframe 요소를 찾을 수 없습니다.');
            return;
        }

        // 체크된 항목의 num을 콤마로 구분하여 전달
        var cartItems = itemNums.join(',');
        var iframeUrl = '../orders/write_form.php?iframe=1&cart_items=' + encodeURIComponent(cartItems);

        iframe.src = iframeUrl;
        
        // 모달 메시지 리스너 설정
        ensureIframeMessageListenerForCart();
        
        orderWriteModal.show();
    }

    // iframe에서 메시지를 받는 리스너 (구매카트 모달용)
    function ensureIframeMessageListenerForCart() {
        if (window.iframeMessageListenerForCartRegistered) {
            return;
        }
        
        window.addEventListener('message', function(event) {
            // 보안: 같은 출처에서 온 메시지만 처리
            if (event.origin !== window.location.origin) {
                return;
            }
            
            var message = event.data;
            if (message && message.scope === 'orderModule') {
                if (message.type === 'orderSaved') {
                    // 발주서 저장 완료
                    Swal.fire('성공', message.payload.message || '발주서가 저장되었습니다.', 'success').then(() => {
                        var modalElement = document.getElementById('orderWriteModal');
                        if (modalElement) {
                            var modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }
                        location.reload();
                    });
                } else if (message.type === 'orderCanceled') {
                    // 발주서 작성 취소
                    var modalElement = document.getElementById('orderWriteModal');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                }
            }
        });
        
        window.iframeMessageListenerForCartRegistered = true;
    }

    // iframe 내부의 저장 함수 호출
    window.iframeSaveOrderFromCart = function() {
        var iframe = document.getElementById('orderWriteIframe');
        if (iframe && iframe.contentWindow) {
            try {
                // iframe 내부의 saveOrder 함수 호출
                if (typeof iframe.contentWindow.saveOrder === 'function') {
                    iframe.contentWindow.saveOrder();
                } else {
                    alert('저장 기능을 사용할 수 없습니다.');
                }
            } catch (e) {
                console.error('저장 함수 호출 오류:', e);
                alert('저장 중 오류가 발생했습니다.');
            }
        }
    };

    // iframe 내부의 취소 함수 호출
    window.iframeCancelOrderFromCart = function() {
        var iframe = document.getElementById('orderWriteIframe');
        if (iframe && iframe.contentWindow) {
            try {
                // iframe 내부의 cancelOrder 함수 호출
                if (typeof iframe.contentWindow.cancelOrder === 'function') {
                    iframe.contentWindow.cancelOrder();
                } else {
                    // 함수가 없으면 모달만 닫기
                    var modalElement = document.getElementById('orderWriteModal');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                }
            } catch (e) {
                console.error('취소 함수 호출 오류:', e);
                // 오류 발생 시에도 모달 닫기
                var modalElement = document.getElementById('orderWriteModal');
                if (modalElement) {
                    var modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            }
        }
    };
</script>

<body>
<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="order-container">
    
    <div class="page-header">
        <h1><i class="fas fa-clipboard-list"></i> 통합 발주 관리</h1>
        <p>원자재 및 부자재 발주 요청 내역을 통합 관리합니다.</p>
    </div>

    <!-- 검색 필터 -->
    <div class="filter-section">
        <form method="get" action="list.php" class="filter-form">
            <input type="hidden" name="mode" value="search">
            <input type="hidden" name="sort_column" value="<?= $sort_column ?>">
            <input type="hidden" name="sort_order" value="<?= $sort_order ?>">
            
            <div class="filter-group">
                <label><i class="far fa-calendar-alt"></i> 기간</label>
                <div style="display: flex; gap: 5px; align-items: center;">
                    <input type="date" name="fromdate" value="<?= $fromdate ?>">
                    <span>~</span>
                    <input type="date" name="todate" value="<?= $todate ?>">
                </div>
            </div>
            
            <div class="filter-group">
                <label><i class="fas fa-search"></i> 검색어</label>
                <input type="text" name="search" placeholder="현장명, 모델, 자재 등" value="<?= $search ?>">
            </div>
            
            <div class="filter-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> 조회
                </button>
                <button type="button" class="btn btn-primary" onclick="addToCart()">
                    <i class="fas fa-cart-plus"></i> 구매카트
                </button>
                <button type="button" class="btn btn-dark" onclick="openWriteForm()">
                    <i class="fas fa-pencil-alt"></i> 신규등록
                </button>
            </div>
        </form>
    </div>

    <!-- 목록 (PC 테이블) -->
    <div class="table-container pc-table">
        <table class="order-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="checkAll" onclick="toggleAll(this)">
                    </th>
                    <th><?= getSortLink('num', 'No', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('eworks_item', '구분', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('which', '진행상태', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('outdate', '접수일', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('requestdate', '납기일', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th>결재</th>
                    <th><?= getSortLink('indate', '완료일', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th>구매카트</th>
                    <th>요청자</th>
                    <th><?= getSortLink('supplier', '공급처', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('outworkplace', '현장명/물품명', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('model', '모델', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('steel_item', '종류', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('spec', '규격', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th><?= getSortLink('steelnum', '수량', $sort_column, $sort_order, $next_sort_order) ?></th>
                    <th>비고</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($order_total_row > 0) {
                    $stmh = $pdo->query($order_sql);
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        include '_row.php';
                        
                        // 상태 배지
                        $status_badge = '';
                        if ($which == '1') $status_badge = '<span class="badge bg-primary">요청</span>';
                        elseif ($which == '2') $status_badge = '<span class="badge bg-danger">발주</span>';
                        elseif ($which == '3') $status_badge = '<span class="badge bg-secondary">완료</span>';
                        
                        if ($eworks_item === '원자재구매') $type_badge = '<span class="badge badge-main">원자재</span>';
                        elseif ($eworks_item === '부자재구매') $type_badge = '<span class="badge badge-aux">부자재</span>';
                        
                        // 결재 상태 설정
                        $statusstr = '';
                        switch ($status) {
                            case 'send':
                                $statusstr = '상신';
                                break;
                            case 'ing':
                                $statusstr = '진행';
                                break;
                            case 'end':
                                $statusstr = '완료';
                                break;
                            default:
                                $statusstr = '';
                                break;
                        }

                        echo "<tr onclick=\"viewDetail('$num')\" style='cursor:pointer;'>";
                        echo "<td class='text-center' onclick='event.stopPropagation()'><input type='checkbox' class='row-checkbox' value='$num'></td>";
                        echo "<td class='text-center'>$num</td>";
                        echo "<td class='text-center'>$type_badge</td>";
                        echo "<td class='text-center'>$status_badge</td>";
                        echo "<td class='text-center'>$outdate</td>";
                        
                        echo "<td class='text-center'>$requestdate</td>";
                        
                        $blink_class = ($status === 'ing') ? ' text-primary blink' : '';
                        echo "<td class='text-center$blink_class'>$statusstr</td>"; // 결재
                        
                        if ($indate == '0000-00-00') $indate = '';
                        echo "<td class='text-center'>$indate</td>";

                        // 구매카트
                        $cart_badge = '';
                        if (isset($cart) && $cart == 1) {
                            $cart_badge = '<span class="badge bg-primary"><i class="fas fa-check"></i> 담김</span>';
                        }
                        echo "<td class='text-center'>$cart_badge</td>";
                        echo "<td class='text-center'>$author</td>"; // 요청자 추가
                        echo "<td class='text-center'>$supplier</td>"; // 공급처 이동
                        
                        echo "<td>$outworkplace</td>"; // 현장명 또는 물품명
                        echo "<td>$model</td>";
                        echo "<td>$steel_item</td>";
                        echo "<td>$spec</td>"; // 규격 추가
                        echo "<td class='text-center text-danger fw-bold'>$steelnum</td>";
                        echo "<td>$request_comment</td>"; // 비고 추가
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14' class='text-center py-4'>데이터가 없습니다.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- 목록 (모바일 카드) -->
    <div class="mobile-list">
        <?php
        if ($order_total_row > 0) {
            $stmh = $pdo->query($order_sql);
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                include '_row.php';
                
                $status_text = '';
                $status_class = '';
                if ($which == '1') { $status_text = '요청'; $status_class = 'text-primary'; }
                elseif ($which == '2') { $status_text = '발주'; $status_class = 'text-danger'; }
                elseif ($which == '3') { $status_text = '완료'; $status_class = 'text-secondary'; }
                
                $type_text = ($eworks_item === '원자재구매') ? '원자재' : '부자재';
                $type_class = ($eworks_item === '원자재구매') ? 'text-primary' : 'text-success';
                
                echo "<div class='mobile-card' onclick=\"viewDetail('$num')\">";
                echo "<div class='mobile-card-header'>";
                echo "<span>No. $num <span class='$type_class'>[$type_text]</span></span>";
                echo "<span class='$status_class'>$status_text</span>";
                echo "</div>";
                echo "<div class='mobile-card-body'>";
                
                echo "<div><span class='mobile-card-label'>명칭:</span><span class='fw-bold text-primary'>$outworkplace</span></div>";
                if ($model) echo "<div><span class='mobile-card-label'>모델:</span>$model</div>";
                if ($steel_item) echo "<div><span class='mobile-card-label'>자재:</span>$steel_item</div>";
                echo "<div><span class='mobile-card-label'>규격:</span>$spec</div>";
                
                echo "<div><span class='mobile-card-label'>수량:</span><span class='text-danger fw-bold'>$steelnum</span></div>";
                echo "<div><span class='mobile-card-label'>공급처:</span>$supplier</div>";
                echo "<div><span class='mobile-card-label'>납기일:</span>$requestdate</div>";
                echo "</div>"; // body
                echo "</div>"; // card
            }
        } else {
            echo "<div class='text-center py-4'>데이터가 없습니다.</div>";
        }
        ?>
    </div>

</div>

<!-- Floating Cart Button -->
<button id="floatingCartBtn" class="btn btn-primary btn-sm" onclick="addToCart()">
    <i class="fas fa-cart-plus"></i> 담기
</button>

<script>
      function viewDetail(num) {
        // 팝업 창 열기
        var width = 1000;
        var height = 800;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;
        
        window.open('write_form.php?mode=modify&num=' + num, 'OrderDetail', 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',resizable=yes,scrollbars=yes');
    }
    
    function openWriteForm() {
        var width = 1000;
        var height = 800;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;
        
        window.open('write_form.php?mode=insert', 'OrderWrite', 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',resizable=yes,scrollbars=yes');
    }

    function restorePageNumber() {
        location.reload();
    }
    function restorePageNumber() {
        location.reload();
    }

    // Floating Cart Button Logic
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const floatingBtn = document.getElementById('floatingCartBtn');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('click', function(e) {
                // e.stopPropagation(); // Already handled in inline onclick, but good to be safe if needed
                
                if (this.checked) {
                    // Show button near the checkbox
                    const rect = this.getBoundingClientRect();
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

                    floatingBtn.style.top = (rect.top + scrollTop - 10) + 'px'; // Slightly above
                    floatingBtn.style.left = (rect.left + scrollLeft + 30) + 'px'; // To the right
                    floatingBtn.style.display = 'block';
                } else {
                    // Check if any other checkboxes are checked
                    const anyChecked = document.querySelector('.row-checkbox:checked');
                    if (!anyChecked) {
                        floatingBtn.style.display = 'none';
                    }
                }
            });
        });

        // Hide button when clicking outside (optional, but good for UX)
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.row-checkbox') && !e.target.closest('#floatingCartBtn')) {
                // floatingBtn.style.display = 'none'; // Uncomment if you want it to hide on outside click
            }
        });
    });
</script>
</body>
</html>
