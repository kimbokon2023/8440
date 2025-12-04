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

$material_id = $_REQUEST["material_id"] ?? '';

if (!$material_id) {
    echo "잘못된 접근입니다.";
    exit;
}

// 자재 정보
$sql_m = "SELECT * FROM material_master WHERE id = ?";
$stmt_m = $pdo->prepare($sql_m);
$stmt_m->execute([$material_id]);
$material = $stmt_m->fetch(PDO::FETCH_ASSOC);

// 내역 조회
$sql = "SELECT * FROM material_transactions WHERE material_id = ? ORDER BY transaction_date DESC, id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$material_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 총 재고 계산
$total_stock = 0;
foreach ($history as $h) {
    if ($h['transaction_type'] == 'in') $total_stock += $h['quantity'];
    else $total_stock -= $h['quantity'];
}
// Note: This total stock calculation is just for verification, the running balance might be better displayed in reverse or just list transactions.
// Let's just list transactions.
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>입출고 내역 - <?= htmlspecialchars($material['item_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fff; padding: 0; }
    </style>
</head>
<body>

<div class="container-fluid p-3">
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">일자</th>
                            <th class="text-center">구분</th>
                            <th class="text-end">수량</th>
                            <th>비고</th>
                            <th class="text-center">등록일시</th>
                            <th class="text-center">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($history) > 0): ?>
                            <?php foreach ($history as $row): ?>
                            <tr>
                                <td class="text-center"><?= $row['transaction_date'] ?></td>
                                <td class="text-center">
                                    <?php if ($row['transaction_type'] == 'in'): ?>
                                        <span class="badge bg-primary">입고</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">출고</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end fw-bold"><?= number_format($row['quantity']) ?></td>
                                <td><?= htmlspecialchars($row['remarks']) ?></td>
                                <td class="text-center text-muted small"><?= $row['created_at'] ?></td>
                                <td class="text-center">
                                    <a href="javascript:void(0);" onclick="editTransaction(<?= $row['id'] ?>)" class="text-primary me-3 fs-5" title="수정"><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0);" onclick="deleteTransaction(<?= $row['id'] ?>)" class="text-danger fs-5" title="삭제"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4">내역이 없습니다.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-center mt-3">
                <button type="button" class="btn btn-secondary" onclick="closeForm()">닫기</button>
            </div>
</div>

<script>
    // 페이지 로드 시 부모 모달 업데이트
    window.addEventListener('DOMContentLoaded', function() {
        if (window.parent && window.parent !== window) {
            window.parent.postMessage({
                type: 'update_modal',
                title: '<i class="fas fa-history"></i> 입출고 내역',
                headerClass: 'modal-header bg-info text-white py-2',
                maxWidth: '1200px'
            }, '*');
        }
    });

    function closeForm() {
        if (window.parent && window.parent !== window) {
            window.parent.postMessage('close_modal', '*');
        } else {
            window.close();
        }
    }

    function editTransaction(id) {
        // 부모 창에 중첩 모달 열기 요청
        if (window.parent && window.parent !== window) {
            window.parent.postMessage({
                type: 'open_nested_modal',
                url: 'transaction_form.php?id=' + id + '&nested=1'
            }, '*');
        } else {
            // iframe이 아닌 경우 직접 이동
            location.href = 'transaction_form.php?id=' + id;
        }
    }

    function deleteTransaction(id) {
        Swal.fire({
            title: '삭제 확인',
            text: "이 내역을 삭제하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'process.php',
                    type: 'POST',
                    data: { action: 'delete_transaction', id: id },
                    success: function(response) {
                        try {
                            var result = typeof response === 'string' ? JSON.parse(response) : response;
                            if (result.success) {
                                Swal.fire('삭제됨', '내역이 삭제되었습니다.', 'success').then(() => {
                                    location.reload();
                                    if (window.opener) window.opener.location.reload();
                                });
                            } else {
                                Swal.fire('오류', result.message || '삭제 실패', 'error');
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
        });
    }
</script>

</body>
</html>
