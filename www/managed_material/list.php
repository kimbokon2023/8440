<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 및 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 8) {
    echo "<script>alert('권한이 없습니다.'); history.back();</script>";
    exit;
}

include getDocumentRoot() . '/load_header.php';

// 검색 및 페이지네이션
$search = $_REQUEST["search"] ?? '';

// SQL 구성
$sql_common = " FROM material_master WHERE (is_deleted IS NULL OR is_deleted = 0) ";
if ($search) {
    $sql_common .= " AND (item_name LIKE '%$search%' OR spec LIKE '%$search%' OR remarks LIKE '%$search%') ";
}

$sql_order = " ORDER BY item_name ASC ";

// 데이터 조회
$sql = "SELECT * " . $sql_common . $sql_order;
$stmh = $pdo->query($sql);
$materials = $stmh->fetchAll(PDO::FETCH_ASSOC);

// 재고 계산 로직 (각 자재별 입고 - 출고)
// 성능 최적화를 위해 한 번의 쿼리로 가져오는 것이 좋음
$stock_sql = "SELECT material_id, 
              SUM(CASE WHEN transaction_type = 'in' THEN quantity ELSE 0 END) as total_in,
              SUM(CASE WHEN transaction_type = 'out' THEN quantity ELSE 0 END) as total_out
              FROM material_transactions 
              GROUP BY material_id";
$stock_stmh = $pdo->query($stock_sql);
$stocks = [];
while ($row = $stock_stmh->fetch(PDO::FETCH_ASSOC)) {
    $stocks[$row['material_id']] = $row['total_in'] - $row['total_out'];
}

?>
<title>관리대상 자재 입출고 관리</title>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
:root {
    /* 라이트 모드 색상 - 그레이 계열 */
    --bg-primary: #ffffff;
    --bg-secondary: #ffffff;
    --bg-card: #f8f9fa;
    --bg-gradient-start: #6c757d;
    --bg-gradient-end: #495057;
    --text-primary: #333333;
    --text-secondary: #666666;
    --text-white: #ffffff;
    --border-color: #e0e0e0;
    --border-light: #f0f0f0;
    --shadow: rgba(0,0,0,0.08);
    --shadow-hover: rgba(108, 117, 125, 0.2);
    --hover-bg: #f8f9fa;
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
    max-width: 1600px;
    margin: 0 auto;
    padding: 15px 12px 25px 12px;
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
    color: var(--text-white);
}

.btn-dark:hover {
    background: #23272b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 58, 64, 0.3);
    color: var(--text-white);
}

.btn-outline-secondary {
    border: 1px solid #6c757d;
    color: #6c757d;
    background: transparent;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.table-container {
    background: white;
    border-radius: 9px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px var(--shadow);
    overflow: hidden;
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
}

.order-table th.text-center {
    text-align: center;
}

.order-table th.text-end {
    text-align: right;
}

.order-table td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
}

.order-table tbody tr:hover {
    background: var(--hover-bg);
    transition: background 0.2s ease;
}

/* 중첩 모달을 위한 z-index 조정 */
#nestedModal {
    z-index: 1060;
}
#nestedModal ~ .modal-backdrop {
    z-index: 1059;
}
</style>

<body>
<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="order-container">
    
    <div class="page-header">
        <h1><i class="fas fa-boxes"></i> 관리대상 자재 입출고 관리</h1>
        <p>자재 등록 및 입출고 내역을 관리합니다.</p>
    </div>

    <!-- 검색 및 버튼 -->
    <div class="filter-section">
        <form method="get" class="filter-form">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> 검색어</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="품명, 규격 검색" style="width: 300px;">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> 조회
            </button>
            
            <button type="button" class="btn btn-dark" onclick="openWriteForm()">
                <i class="fas fa-plus"></i> 자재 등록
            </button>

            <button type="button" class="btn btn-outline-secondary" onclick="openHelpModal()">
                <i class="fas fa-question-circle"></i> 도움말
            </button>
        </form>
    </div>

    <!-- 목록 테이블 -->
    <div class="table-container">
        <table class="order-table">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="10%">등록일자</th>
                    <th>품명</th>
                    <th>규격</th>
                    <th class="text-end">가격</th>
                    <th class="text-center">단위</th>
                    <th class="text-end">현재고</th>
                    <th>비고</th>
                    <th class="text-center" width="20%">관리</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($materials) > 0): ?>
                    <?php foreach ($materials as $item): 
                        $current_stock = $stocks[$item['id']] ?? 0;
                        $stock_class = $current_stock <= 0 ? 'text-danger fw-bold' : 'text-primary fw-bold';
                    ?>
                    <tr>
                        <td class="text-center"><?= $item['id'] ?></td>
                        <td class="text-center"><?= $item['registdate'] ?></td>
                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['spec']) ?></td>
                        <td class="text-end"><?= number_format($item['price']) ?></td>
                        <td class="text-center"><?= $item['unit'] ?></td>
                        <td class="text-end <?= $stock_class ?>"><?= number_format($current_stock) ?></td>
                        <td><?= htmlspecialchars($item['remarks']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="openWriteForm(<?= $item['id'] ?>)">수정</button>
                            <button class="btn btn-sm btn-success" onclick="openTransactionForm(<?= $item['id'] ?>)">입출고</button>
                            <button class="btn btn-sm btn-info" onclick="viewHistory(<?= $item['id'] ?>)">내역</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteMaterial(<?= $item['id'] ?>)">삭제</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center py-4">등록된 자재가 없습니다.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 자재 등록/수정 모달 -->
    <div class="modal fade" id="materialModal" tabindex="-1" aria-labelledby="materialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content" style="height: 800px; max-height: 90vh;">
                <div class="modal-header bg-primary text-white py-2">
                    <h5 class="modal-title fs-6" id="materialModalLabel">
                        <i class="fas fa-edit"></i> 자재 관리
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="height: calc(100% - 50px); overflow: hidden;">
                    <iframe id="materialIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- 중첩 모달 (입출고 내역에서 수정할 때 사용) -->
    <div class="modal fade" id="nestedModal" tabindex="-1" aria-labelledby="nestedModalLabel" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content" style="height: 800px; max-height: 90vh;">
                <div class="modal-header bg-success text-white py-2">
                    <h5 class="modal-title fs-6" id="nestedModalLabel">
                        <i class="fas fa-exchange-alt"></i> 입출고 기록 수정
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="height: calc(100% - 50px); overflow: hidden;">
                    <iframe id="nestedIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- 도움말 모달 -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white py-3">
                    <h5 class="modal-title fs-5" id="helpModalLabel">
                        <i class="fas fa-info-circle"></i> 자재 관리 메뉴 사용법
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto; font-size: 1.15rem;">
                    <div class="p-2">
                        <h6 class="fw-bold text-primary mb-2"><i class="fas fa-plus-circle"></i> 자재 등록</h6>
                        <p class="text-muted mb-4">
                            우측 상단의 <strong>'자재 등록'</strong> 버튼을 클릭하여 신규 자재를 등록합니다.<br>
                            품명, 규격, 단위, 단가 등의 필수 정보를 입력하여 저장하세요.
                        </p>

                        <h6 class="fw-bold text-secondary mb-2"><i class="fas fa-search"></i> 조회 및 검색</h6>
                        <p class="text-muted mb-4">
                            상단의 검색창에 <strong>품명</strong>이나 <strong>규격</strong>을 입력하고 '조회' 버튼을 누르면 원하는 자재를 빠르게 찾을 수 있습니다.
                        </p>

                        <h6 class="fw-bold text-success mb-2"><i class="fas fa-cubes"></i> 재고 현황 확인</h6>
                        <p class="text-muted mb-4">
                            목록에서 각 자재의 <strong>현재고</strong>를 실시간으로 확인할 수 있습니다.<br>
                            <span class="text-primary fw-bold">파란색 숫자</span>는 재고가 있음을, <span class="text-danger fw-bold">빨간색 숫자</span>는 재고가 없거나 부족함(0 이하)을 나타냅니다.<br>
                            <small>* 현재고 = 총 입고 수량 - 총 출고 수량</small>
                        </p>

                        <h6 class="fw-bold text-warning mb-2"><i class="fas fa-exchange-alt"></i> 입출고 등록</h6>
                        <p class="text-muted mb-4">
                            자재의 수량이 변동될 때마다 <strong>'입출고'</strong> 버튼을 눌러 기록하세요.<br>
                            <strong>'입고'</strong>는 자재가 들어올 때, <strong>'출고'</strong>는 자재를 사용할 때 선택합니다.
                        </p>

                        <h6 class="fw-bold text-info mb-2"><i class="fas fa-history"></i> 내역 관리</h6>
                        <p class="text-muted mb-4">
                            <strong>'내역'</strong> 버튼을 클릭하면 해당 자재의 모든 입출고 기록을 날짜별로 상세히 볼 수 있습니다.<br>
                            잘못 입력된 기록은 내역 창에서 수정하거나 삭제할 수 있습니다.
                        </p>

                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-cog"></i> 정보 수정 및 삭제</h6>
                        <p class="text-muted mb-0">
                            <strong>'수정'</strong> 버튼으로 자재의 기본 정보(품명, 단가 등)를 변경할 수 있으며,<br>
                            더 이상 사용하지 않는 자재는 <strong>'삭제'</strong> 버튼으로 목록에서 제거할 수 있습니다.
                        </p>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function openHelpModal() {
        var modal = new bootstrap.Modal(document.getElementById('helpModal'));
        modal.show();
    }

    function openWriteForm(id = '') {
        var url = 'write_form.php';
        if (id) url += '?id=' + id;
        
        var iframe = document.getElementById('materialIframe');
        iframe.src = url;
        
        document.getElementById('materialModalLabel').innerHTML = '<i class="fas fa-edit"></i> 자재 등록/수정';
        document.querySelector('#materialModal .modal-header').className = 'modal-header bg-primary text-white py-2';
        document.querySelector('#materialModal .modal-dialog').style.maxWidth = '600px';
        
        var modal = new bootstrap.Modal(document.getElementById('materialModal'));
        modal.show();
    }

    // 모달 닫기 및 리로드를 위한 메시지 리스너
    window.addEventListener('message', function(event) {
        if (event.data === 'reload_parent') {
            location.reload();
        } else if (event.data === 'reload_history') {
            // 중첩 모달이 닫히고 history를 새로고침
            var iframe = document.getElementById('materialIframe');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.location.reload();
            }
        } else if (event.data === 'close_modal') {
            var modalEl = document.getElementById('materialModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        } else if (event.data === 'close_nested_modal') {
            var nestedModalEl = document.getElementById('nestedModal');
            var nestedModal = bootstrap.Modal.getInstance(nestedModalEl);
            if (nestedModal) nestedModal.hide();
        } else if (event.data && event.data.type === 'update_modal') {
            if (event.data.title) document.getElementById('materialModalLabel').innerHTML = event.data.title;
            if (event.data.headerClass) document.querySelector('#materialModal .modal-header').className = event.data.headerClass;
            if (event.data.maxWidth) document.querySelector('#materialModal .modal-dialog').style.maxWidth = event.data.maxWidth;
        } else if (event.data && event.data.type === 'open_nested_modal') {
            // 중첩 모달 열기
            var iframe = document.getElementById('nestedIframe');
            iframe.src = event.data.url;
            
            var nestedModalEl = document.getElementById('nestedModal');
            var nestedModal = new bootstrap.Modal(nestedModalEl, {
                backdrop: true // backdrop 활성화
            });
            nestedModal.show();
            
            // z-index 동적 조정
            nestedModalEl.addEventListener('shown.bs.modal', function() {
                var backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 1) {
                    backdrops[backdrops.length - 1].style.zIndex = '1059';
                }
                nestedModalEl.style.zIndex = '1060';
            });
        }
    });

    function openTransactionForm(id) {
        var url = 'transaction_form.php?material_id=' + id;
        
        var iframe = document.getElementById('materialIframe');
        iframe.src = url;

        document.getElementById('materialModalLabel').innerHTML = '<i class="fas fa-exchange-alt"></i> 입출고 기록';
        document.querySelector('#materialModal .modal-header').className = 'modal-header bg-success text-white py-2';
        document.querySelector('#materialModal .modal-dialog').style.maxWidth = '600px';

        var modal = new bootstrap.Modal(document.getElementById('materialModal'));
        modal.show();
    }

    function viewHistory(id) {
        var url = 'history.php?material_id=' + id;
        
        var iframe = document.getElementById('materialIframe');
        iframe.src = url;

        document.getElementById('materialModalLabel').innerHTML = '<i class="fas fa-history"></i> 입출고 내역';
        document.querySelector('#materialModal .modal-header').className = 'modal-header bg-info text-white py-2';
        document.querySelector('#materialModal .modal-dialog').style.maxWidth = '1200px';

        var modal = new bootstrap.Modal(document.getElementById('materialModal'));
        modal.show();
    }

    function deleteMaterial(id) {
        if (!confirm('정말 삭제하시겠습니까? 삭제 시 복구할 수 없습니다.')) return;
        
        // AJAX 요청
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'process.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        alert('삭제되었습니다.');
                        location.reload();
                    } else {
                        alert('오류: ' + (res.message || '삭제 실패'));
                    }
                } catch (e) {
                    alert('응답 처리 오류');
                }
            } else {
                alert('서버 통신 오류');
            }
        };
        xhr.send('action=delete_master&id=' + id);
    }

    // 팝업 센터 함수 (기존 시스템에 없다면 사용, 있다면 중복 정의 주의)
    if (typeof popupCenter !== 'function') {
        function popupCenter(url, title, w, h) {
            const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
            const dualScreenTop = window.screenTop !==  undefined ? window.screenTop : window.screenY;
            const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
            const systemZoom = width / window.screen.availWidth;
            const left = (width - w) / 2 / systemZoom + dualScreenLeft;
            const top = (height - h) / 2 / systemZoom + dualScreenTop;
            const newWindow = window.open(url, title, 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);
            if (window.focus) newWindow.focus();
        }
    }
</script>

</body>
</html>
