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
<style>
    /* integratedordering 스타일 차용 */
    :root {
        --bg-gradient-start: #6c757d;
        --bg-gradient-end: #495057;
    }
    .page-header {
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        color: white;
        padding: 20px 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(108, 117, 125, 0.15);
    }
    .table-container {
        background: white;
        border-radius: 9px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        overflow-x: auto;
    }
    .custom-table thead {
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        color: white;
    }
    .custom-table th, .custom-table td {
        padding: 12px 10px;
        vertical-align: middle;
        white-space: nowrap;
    }
    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
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

<div class="container-fluid mt-3" style="max-width: 1600px;">
    
    <div class="page-header">
        <h1 class="fs-4 mb-1"><i class="fas fa-boxes"></i> 관리대상 자재 입출고 관리</h1>
        <p class="mb-0 opacity-75">자재 등록 및 입출고 내역을 관리합니다.</p>
    </div>

    <!-- 검색 및 버튼 -->
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="get" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" class="form-control" name="search" placeholder="품명, 규격 검색" value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> 조회</button>
                </div>
                <div class="col-auto ms-auto">
                    <button type="button" class="btn btn-dark" onclick="openWriteForm()"><i class="fas fa-plus"></i> 자재 등록</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 목록 테이블 -->
    <div class="table-container">
        <table class="table custom-table mb-0">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">등록일자</th>
                    <th>품명</th>
                    <th>규격</th>
                    <th class="text-end">가격</th>
                    <th class="text-center">단위</th>
                    <th class="text-end">현재고</th>
                    <th>비고</th>
                    <th class="text-center">관리</th>
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

</div>

<script>
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
