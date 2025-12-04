<?php
/**
 * 주소록 등록/수정 폼
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    echo "<script>alert('권한이 없습니다.'); window.close();</script>";
    exit;
}

include getDocumentRoot() . '/load_header.php';

// 모드 확인
$id = $_REQUEST["id"] ?? 0;
$id = (int)$id;
$is_edit = $id > 0;

// iframe 모드
$iframe_mode = isset($_GET['iframe']) && $_GET['iframe'] === '1';

// 데이터 조회
$data = [];
if ($is_edit) {
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("SELECT * FROM estimate_customer WHERE num = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            echo "<script>alert('존재하지 않는 데이터입니다.'); window.close();</script>";
            exit;
        }
    } catch (Exception $e) {
        echo "<script>alert('DB 오류: " . addslashes($e->getMessage()) . "'); window.close();</script>";
        exit;
    }
}
?>

<style>
    body { background-color: #f8f9fa; overflow-x: hidden; }
    .container-fluid { padding: 15px; max-width: 100%; }
    .card { border: none; box-shadow: none; border-radius: 0; background: transparent; }
    .card-header { background-color: transparent; border-bottom: 1px solid #eee; padding: 10px 0; font-weight: 600; color: #333; }
    .card-body { padding: 15px 0; }
    .form-label { font-weight: 500; font-size: 0.9rem; color: #555; }
    .form-control { border-radius: 4px; border: 1px solid #ddd; font-size: 0.95rem; }
    .form-control:focus { border-color: #4a90e2; box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25); }
    .btn-primary { background-color: #4a90e2; border-color: #4a90e2; }
    .btn-primary:hover { background-color: #357abd; border-color: #357abd; }
    /* Hide scrollbar for Chrome, Safari and Opera */
    body::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    body {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $is_edit ? '주소록 수정' : '주소록 등록' ?>
        </div>
        <div class="card-body">
            <form id="addressBookForm" method="POST" action="save.php">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="mode" value="<?= $is_edit ? 'update' : 'insert' ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="display_name" class="form-label">표시 이름</label>
                        <input type="text" class="form-control" id="display_name" name="display_name" value="<?= htmlspecialchars($data['display_name'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="company_name" class="form-label">회사</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" value="<?= htmlspecialchars($data['company_name'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="department" class="form-label">부서</label>
                        <input type="text" class="form-control" id="department" name="department" value="<?= htmlspecialchars($data['department'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">이메일</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="mobile_phone" class="form-label">휴대폰</label>
                        <input type="text" class="form-control" id="mobile_phone" name="mobile_phone" value="<?= htmlspecialchars($data['mobile_phone'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="work_phone" class="form-label">근무처 전화</label>
                        <input type="text" class="form-control" id="work_phone" name="work_phone" value="<?= htmlspecialchars($data['work_phone'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="home_phone" class="form-label">집 전화</label>
                        <input type="text" class="form-control" id="home_phone" name="home_phone" value="<?= htmlspecialchars($data['home_phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="memo" class="form-label">메모</label>
                    <textarea class="form-control" id="memo" name="memo" rows="4"><?= htmlspecialchars($data['memo'] ?? '') ?></textarea>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="parent.closeAddressModal();">닫기</button>
                    <button type="submit" class="btn btn-primary">저장</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#addressBookForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'save.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('저장되었습니다.');
                    if (window.opener && window.opener.reloadTable) {
                        window.opener.reloadTable();
                    } else if (parent && parent.reloadTable) {
                        parent.reloadTable();
                    }
                    if (parent && parent.closeAddressModal) {
                        parent.closeAddressModal();
                    } else {
                        window.close();
                    }
                } else {
                    alert(response.message || '저장 중 오류가 발생했습니다.');
                }
            },
            error: function(xhr, status, error) {
                alert('서버 통신 오류: ' + error);
            }
        });
    });
});
</script>

<?php include getDocumentRoot() . '/load_footer.php'; ?>
