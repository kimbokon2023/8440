<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 및 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 8) {
    echo "<script>alert('권한이 없습니다.'); window.close();</script>";
    exit;
}

// DB 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$id = $_REQUEST["id"] ?? '';
$material_id = $_REQUEST["material_id"] ?? '';
$nested = $_REQUEST["nested"] ?? ''; // 중첩 모달 여부

$mode = "insert";
$transaction = [];

if ($id) {
    $mode = "update";
    $sql = "SELECT * FROM material_transactions WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($transaction) {
        $material_id = $transaction['material_id'];
    }
}

// 자재 정보 로드
$materials = [];
$sql = "SELECT * FROM material_master WHERE (is_deleted IS NULL OR is_deleted = 0) ORDER BY item_name ASC";
$stmh = $pdo->query($sql);
$materials = $stmh->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>입출고 기록</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fff; padding: 0; }
        .form-label { font-weight: 600; color: #495057; }
    </style>
</head>
<body>
<div class="container-fluid p-3">
    <form id="transactionForm">
        <input type="hidden" name="action" value="<?= $mode == 'update' ? 'update_transaction' : 'save_transaction' ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="mb-3">
            <label class="form-label">자재 선택</label>
            <select class="form-select" name="material_id" required>
                <option value="">선택하세요</option>
                <?php foreach ($materials as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $material_id == $m['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['item_name']) ?> (<?= htmlspecialchars($m['spec']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">구분</label>
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="transaction_type" id="type_in" value="in" <?= ($transaction['transaction_type'] ?? 'in') == 'in' ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary" for="type_in">입고 (In)</label>

                <input type="radio" class="btn-check" name="transaction_type" id="type_out" value="out" <?= ($transaction['transaction_type'] ?? '') == 'out' ? 'checked' : '' ?>>
                <label class="btn btn-outline-danger" for="type_out">출고 (Out)</label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">기준일</label>
            <input type="date" class="form-control" name="transaction_date" value="<?= $transaction['transaction_date'] ?? date('Y-m-d') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">수량</label>
            <input type="number" class="form-control" name="quantity" value="<?= $transaction['quantity'] ?? '' ?>" required placeholder="0">
        </div>

        <div class="mb-3">
            <label class="form-label">비고</label>
            <textarea class="form-control" name="remarks" rows="3"><?= htmlspecialchars($transaction['remarks'] ?? '') ?></textarea>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="button" class="btn btn-secondary me-md-2" onclick="closeForm()">취소</button>
            <button type="button" class="btn btn-success" onclick="saveData()">저장</button>
        </div>
    </form>
</div>

<script>
    var isNested = <?= $nested ? 'true' : 'false' ?>;

    // 페이지 로드 시 부모 모달 업데이트 (중첩 모달이 아닐 때만)
    window.addEventListener('DOMContentLoaded', function() {
        if (window.parent && window.parent !== window && !isNested) {
            window.parent.postMessage({
                type: 'update_modal',
                title: '<i class="fas fa-exchange-alt"></i> 입출고 기록',
                headerClass: 'modal-header bg-success text-white py-2',
                maxWidth: '600px'
            }, '*');
        }
    });

    function closeForm() {
        if (window.parent && window.parent !== window) {
            if (isNested) {
                window.parent.postMessage('close_nested_modal', '*');
            } else {
                window.parent.postMessage('close_modal', '*');
            }
        } else {
            window.close();
        }
    }

    function saveData() {
        var form = document.getElementById('transactionForm');
        var formData = new FormData(form);

        if (!formData.get('material_id')) {
            Swal.fire('경고', '자재를 선택해주세요.', 'warning');
            return;
        }
        if (!formData.get('quantity')) {
            Swal.fire('경고', '수량을 입력해주세요.', 'warning');
            return;
        }

        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {

                    var result = (typeof response === 'string') ? JSON.parse(response) : response;
                    if (result.success) {
                        Swal.fire('성공', '저장되었습니다.', 'success').then(() => {
                            // 모달인 경우
                            if (window.parent && window.parent !== window) {
                                if (isNested) {
                                    // 중첩 모달인 경우: 중첩 모달 닫고 history 새로고침
                                    window.parent.postMessage('reload_history', '*');
                                    window.parent.postMessage('close_nested_modal', '*');
                                } else {
                                    // 일반 모달인 경우: 전체 페이지 새로고침
                                    window.parent.postMessage('reload_parent', '*');
                                }
                            } 
                            // 팝업인 경우
                            else if (window.opener) {
                                window.opener.location.reload();
                                window.close();
                            }
                        });
                    } else {
                        Swal.fire('오류', result.message || '저장 실패', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('오류', '응답 처리 중 오류가 발생했습니다.', 'error');
                }
            },
            error: function() {
                Swal.fire('오류', '서버 통신 중 오류가 발생했습니다.', 'error');
            }
        });
    }
</script>

</body>
</html>
