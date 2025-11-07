<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$mode = $_POST['mode'] ?? '';
$num = $_POST['num'] ?? '';

$tablename = 'delivery';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// _row.php와 _request.php에서 사용되는 변수 초기화
$registedate = '';
$receiver = '';
$receiver_tel = '';
$address = '';
$sender = '';
$item_name = '';
$unit = '';
$surang = '';
$fee = '';
$fee_type = '';
$goods_price = '';
$update_log = '';

// 제목 메시지 설정
if ($mode === 'update') {
    $title_message = '경동화물/택배 정보 수정';
} elseif ($mode === 'copy') {
    $title_message = '경동화물/택배 정보 복사';
} else {
    $title_message = '경동화물/택배 정보 신규 등록';
}

// 수정 모드
if ($mode === 'update' && $num) {
    try {
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include '_row.php';
        } else {
            error_log("배송 정보를 찾을 수 없습니다. num: " . $num);
        }
    } catch (PDOException $ex) {
        error_log("배송 정보 조회 오류 (수정): " . $ex->getMessage());
        echo "오류: " . $ex->getMessage();
        exit;
    }
}
// 복사 모드
elseif ($mode === 'copy' && $num) {
    try {
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include '_row.php';
            $mode = 'copy';
            $num = null;
        } else {
            error_log("복사할 배송 정보를 찾을 수 없습니다. num: " . $num);
        }
    } catch (PDOException $ex) {
        error_log("배송 정보 조회 오류 (복사): " . $ex->getMessage());
        echo "오류: " . $ex->getMessage();
        exit;
    }
}
// 신규 등록 모드
else {
    include '_request.php';
    $mode = 'insert';
    $registedate = date('Y-m-d');
}
?>

<input type="hidden" id="update_log" name="update_log" value="<?= htmlspecialchars($update_log) ?>">
<input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num) ?>">
<input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode) ?>">

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-center">
        <div class="card w-100">
            <div class="card-header text-center align-items-center">
                <div class="d-flex align-items-center justify-content-center m-2">
                    <span class="text-center fs-5 mx-3"><?= htmlspecialchars($title_message) ?></span>
                    <button type="button" data-num="<?= htmlspecialchars($num) ?>" class="btn btn-outline-dark btn-sm me-5" id="showlogBtn">
                        Log 기록
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center text-center">
                    <div class="d-flex align-items-center justify-content-center m-2">
                        <table class="table table-bordered" id="listTable">
                            <tbody>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">등록일자</td>
                                    <td class="text-center" colspan="3">
                                        <input type="date" class="form-control fs-6 noborder-input w120px" 
                                               id="registedate" autocomplete="off" name="registedate" 
                                               style="width:130px;" value="<?= htmlspecialchars($registedate) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">받을 분</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="receiver" autocomplete="off" name="receiver" 
                                               value="<?= htmlspecialchars($receiver) ?>">
                                    </td>
                                    <td class="text-center fs-6 fw-bold">연락처</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="receiver_tel" autocomplete="off" name="receiver_tel" 
                                               value="<?= htmlspecialchars($receiver_tel) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">도착지 영업소 또는 받을 분 주소</td>
                                    <td class="text-center" colspan="3">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="address" autocomplete="off" name="address" 
                                               value="<?= htmlspecialchars($address) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">보내는 사람</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="sender" autocomplete="off" name="sender" 
                                               value="<?= htmlspecialchars($sender) ?>">
                                    </td>
                                    <td class="text-center fs-6 fw-bold">품명/현장명</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="item_name" autocomplete="off" name="item_name" 
                                               value="<?= htmlspecialchars($item_name) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">포장</td>
                                    <td class="text-center">
                                        <select id="unit" name="unit" class="form-select fs-6 noborder-input w120px" 
                                                style="font-size: 0.8rem; height: 32px;">
                                            <?php
                                            $unit_options = array('(선택)', '박스', '파렛트');
                                            
                                            foreach ($unit_options as $option) {
                                            ?>
                                                <option value="<?= htmlspecialchars($option) ?>" 
                                                        <?= ($unit === $option) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($option) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="text-center fs-6 fw-bold">수량</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input w50px text-end" 
                                               id="surang" name="surang" value="<?= htmlspecialchars($surang) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">운임</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input w120px text-end" 
                                               autocomplete="off" id="fee" name="fee" 
                                               value="<?= number_format((int)str_replace(',', '', $fee)) ?>" 
                                               oninput="inputNumberFormat(this)">
                                    </td>
                                    <td class="text-center fs-6 fw-bold">운임구분</td>
                                    <td class="text-center">
                                        <select id="fee_type" name="fee_type" class="form-select fs-6 noborder-input w120px" 
                                                style="font-size: 0.8rem; height: 32px;">
                                            <?php
                                            $fee_type_options = array('(선택)', '현택', '착택', '현화', '착화');
                                            
                                            foreach ($fee_type_options as $option) {
                                            ?>
                                                <option value="<?= htmlspecialchars($option) ?>" 
                                                        <?= ($fee_type === $option) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($option) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">물품가액</td>
                                    <td class="text-center" colspan="3">
                                        <input type="text" class="form-control fs-6 noborder-input w120px text-start" 
                                               autocomplete="off" id="goods_price" name="goods_price" 
                                               value="<?= htmlspecialchars($goods_price) ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" id="saveBtn" class="btn btn-dark btn-sm me-3">
                        <i class="bi bi-floppy-fill"></i> 저장
                    </button>
                    <?php if ($mode != 'insert' && $mode != 'copy') { ?>
                        <button type="button" id="copyBtn" class="btn btn-primary btn-sm me-3">
                            <i class="bi bi-copy"></i> 복사
                        </button>
                        <button type="button" id="deleteBtn" class="btn btn-danger btn-sm me-3">
                            <i class="bi bi-trash"></i> 삭제
                        </button>
                    <?php } ?>
                    <button type="button" id="closeBtn" class="btn btn-outline-dark btn-sm me-2">
                        &times; 닫기
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
