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

$mode = "insert";
$id = $_REQUEST["id"] ?? '';

// 초기값
$registdate = date("Y-m-d");
$item_name = "";
$spec = "";
$price = "";
$unit = "";
$remarks = "";

if ($id) {
    $mode = "update";
    $sql = "SELECT * FROM material_master WHERE id = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $id);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $registdate = $row['registdate'];
        $item_name = $row['item_name'];
        $spec = $row['spec'];
        $price = $row['price'];
        $unit = $row['unit'];
        $remarks = $row['remarks'];
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자재 등록/수정</title>
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
    <form id="materialForm">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="save_master">

        <div class="mb-3">
            <label class="form-label">등록일자</label>
            <input type="date" class="form-control" name="registdate" value="<?= $registdate ?>" required>
        </div>

                <div class="mb-3">
                    <label class="form-label">품명</label>
                    <input type="text" class="form-control" name="item_name" value="<?= htmlspecialchars($item_name) ?>" required placeholder="예: 시멘트, 철근">
                </div>

                <div class="mb-3">
                    <label class="form-label">규격</label>
                    <input type="text" class="form-control" name="spec" value="<?= htmlspecialchars($spec) ?>" placeholder="예: 40kg, 10mm">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">단가</label>
                        <input type="number" class="form-control" name="price" value="<?= $price ?>" placeholder="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">단위</label>
                        <input type="text" class="form-control" name="unit" value="<?= htmlspecialchars($unit) ?>" placeholder="예: EA, BOX, kg">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">비고</label>
                    <textarea class="form-control" name="remarks" rows="3"><?= htmlspecialchars($remarks) ?></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-secondary me-md-2" onclick="closeForm()">취소</button>
                    <button type="button" class="btn btn-primary" onclick="saveData()">저장</button>
                </div>
            </form>
</div>

<script>
    function closeForm() {
        if (window.parent && window.parent !== window) {
            window.parent.postMessage('close_modal', '*');
        } else {
            window.close();
        }
    }

    function saveData() {
        var form = document.getElementById('materialForm');
        var formData = new FormData(form);

        if (!formData.get('item_name')) {
            Swal.fire('경고', '품명을 입력해주세요.', 'warning');
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
                                window.parent.postMessage('reload_parent', '*');
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
