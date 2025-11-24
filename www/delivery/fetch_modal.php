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

<style>
/* 모달 내부 모바일 최적화 */
@media (max-width: 768px) {
    /* 컨테이너 최적화 */
    .container-fluid {
        padding: 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 최적화 */
    .card {
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-header {
        padding: 0.5rem 0.4rem !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 모달 헤더 내용 최적화 */
    .modal-header-content {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
    
    .modal-header-content .fs-5 {
        margin: 0 !important;
        width: 100% !important;
    }
    
    .modal-header-content #showlogBtn {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    .card-body {
        padding: 0.5rem 0.4rem !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        max-height: calc(100vh - 200px) !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        -webkit-overflow-scrolling: touch !important; /* iOS 부드러운 스크롤 */
    }
    
    /* 제목 최적화 */
    .fs-5 {
        font-size: 0.9rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 테이블을 카드 형식으로 변환 */
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    
    table.table tbody {
        display: block !important;
        width: 100% !important;
    }
    
    table.table tbody tr {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 0.75rem !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        padding: 0.75rem !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    table.table tbody tr td {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    table.table tbody tr td::before {
        content: attr(data-label) !important;
        font-weight: bold !important;
        font-size: 0.75rem !important;
        color: #666 !important;
        margin-right: 0.5rem !important;
        min-width: 100px !important;
        flex-shrink: 0 !important;
    }
    
    /* 입력 필드 최적화 */
    input[type="text"],
    input[type="date"],
    input[type="number"],
    select,
    .form-control,
    .form-select {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        box-sizing: border-box !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: nowrap !important;
        min-height: 40px !important;
        box-sizing: border-box !important;
        margin-bottom: 0.5rem !important;
    }
    
    .btn-sm {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.6rem !important;
        min-height: 36px !important;
    }
    
    /* 버튼 그룹 최적화 */
    .d-flex.justify-content-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
    
    .d-flex.justify-content-center .btn {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모든 텍스트 요소 강제 줄바꿈 */
    p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        display: inline !important;
        overflow: visible !important;
    }
}
</style>

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-center">
        <div class="card w-100">
            <div class="card-header text-center align-items-center">
                <div class="d-flex align-items-center justify-content-center m-2 modal-header-content">
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
                                    <td class="text-center fs-6 fw-bold" data-label="등록일자">등록일자</td>
                                    <td class="text-center" colspan="3" data-label="">
                                        <input type="date" class="form-control fs-6 noborder-input w120px" 
                                               id="registedate" autocomplete="off" name="registedate" 
                                               style="width:130px;" value="<?= htmlspecialchars($registedate) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold" data-label="받을 분">받을 분</td>
                                    <td class="text-center" data-label="">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="receiver" autocomplete="off" name="receiver" 
                                               value="<?= htmlspecialchars($receiver) ?>">
                                    </td>
                                    <td class="text-center fs-6 fw-bold" data-label="연락처">연락처</td>
                                    <td class="text-center" data-label="">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="receiver_tel" autocomplete="off" name="receiver_tel" 
                                               value="<?= htmlspecialchars($receiver_tel) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold" data-label="도착지 영업소 또는 받을 분 주소">도착지 영업소 또는 받을 분 주소</td>
                                    <td class="text-center" colspan="3" data-label="">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="address" autocomplete="off" name="address" 
                                               value="<?= htmlspecialchars($address) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold" data-label="보내는 사람">보내는 사람</td>
                                    <td class="text-center" data-label="">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="sender" autocomplete="off" name="sender" 
                                               value="<?= htmlspecialchars($sender) ?>">
                                    </td>
                                    <td class="text-center fs-6 fw-bold" data-label="품명/현장명">품명/현장명</td>
                                    <td class="text-center" data-label="">
                                        <input type="text" class="form-control fs-6 noborder-input" 
                                               id="item_name" autocomplete="off" name="item_name" 
                                               value="<?= htmlspecialchars($item_name) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold" data-label="포장">포장</td>
                                    <td class="text-center" data-label="">
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
                                    <td class="text-center fs-6 fw-bold" data-label="수량">수량</td>
                                    <td class="text-center" data-label="">
                                        <input type="text" class="form-control fs-6 noborder-input w50px text-end" 
                                               id="surang" name="surang" value="<?= htmlspecialchars($surang) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold" data-label="운임">운임</td>
                                    <td class="text-center" data-label="">
                                        <input type="text" class="form-control fs-6 noborder-input w120px text-end" 
                                               autocomplete="off" id="fee" name="fee" 
                                               value="<?= number_format((int)str_replace(',', '', $fee)) ?>" 
                                               oninput="inputNumberFormat(this)">
                                    </td>
                                    <td class="text-center fs-6 fw-bold" data-label="운임구분">운임구분</td>
                                    <td class="text-center" data-label="">
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
                                    <td class="text-center fs-6 fw-bold" data-label="물품가액">물품가액</td>
                                    <td class="text-center" colspan="3" data-label="">
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
