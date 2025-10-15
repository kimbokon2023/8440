<?php
/**
 * Holiday 모달 폼 로딩 페이지
 * 휴무일 등록/수정을 위한 모달 폼을 AJAX로 로드합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
require_once(includePath('session.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// POST 파라미터 초기화
$mode = $_POST['mode'] ?? '';
$num = $_POST['num'] ?? '';

// 변수 초기화
$tablename = 'holiday';
$title_message = '';

// _row.php와 _request.php에서 사용될 변수들 사전 초기화
$registedate = '';
$comment = '';
$is_deleted = '';
$searchtag = '';
$update_log = '';
$startdate = '';
$enddate = '';
$periodcheck = '0';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode === 'update' && !empty($num)) {
    try {
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // _row.php에서 변수를 설정
            include '_row.php';
        } else {
            error_log("Record not found in holiday/fetch_modal.php: num={$num}");
            // 레코드가 없으면 신규 등록 모드로 전환
            $mode = 'insert';
            include '_request.php';
            $registedate = date('Y-m-d');
            $startdate = date('Y-m-d');
        }
        
    } catch (PDOException $ex) {
        error_log("DB query error in holiday/fetch_modal.php: " . $ex->getMessage());
        echo '<div class="alert alert-danger" role="alert">';
        echo '데이터 조회 중 오류가 발생했습니다.';
        echo '</div>';
        exit;
    }
} else {
    // 신규 등록 모드
    include '_request.php';
    $mode = 'insert';
    $registedate = date('Y-m-d');
    $startdate = date('Y-m-d');
}

// 모드에 따른 제목 메시지
$title_message = ($mode === 'update') ? '휴무 수정' : '휴무 신규 등록';
?>

<input type="hidden" id="update_log" name="update_log" value="<?php echo htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="registedate" name="registedate" value="<?php echo htmlspecialchars($registedate, ENT_QUOTES, 'UTF-8'); ?>">

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-center">
        <div class="card justify-content-center">
            <div class="card-header text-center">
                <span class="text-center fs-5"><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            
            <div class="card-body">
                <div class="row justify-content-center text-center">
                    <div class="d-flex align-items-center justify-content-center m-2">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="text-center fs-6 fw-bold" style="width:150px;">기간 설정</td>
                                    <td class="text-start" style="width:450px;">
                                        <input type="checkbox" id="periodcheck" name="periodcheck" value="1" <?php echo $periodcheck ? 'checked' : ''; ?>>
                                        <span>
                                            <input type="date" class="form-control d-inline fs-6" id="startdate" name="startdate" style="width:130px;" value="<?php echo htmlspecialchars($startdate, ENT_QUOTES, 'UTF-8'); ?>">
                                            <span id="enddateWrapper" style="<?php echo $periodcheck ? '' : 'display:none;'; ?>">
                                                ~
                                                <input type="date" class="form-control d-inline fs-6" id="enddate" name="enddate" style="width:130px;" value="<?php echo htmlspecialchars($enddate, ENT_QUOTES, 'UTF-8'); ?>">
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-center fs-6 fw-bold" style="width:150px;">비고</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6" id="comment" name="comment" value="<?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
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
                    <?php if ($mode === 'update'): ?>
                    <button type="button" id="deleteBtn" class="btn btn-danger btn-sm me-3">
                        <i class="bi bi-trash"></i> 삭제
                    </button>
                    <?php endif; ?>
                    <button type="button" id="closeBtn" class="btn btn-outline-dark btn-sm me-2">
                        &times; 닫기
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    /**
     * 기간 체크박스 이벤트 핸들러
     * 체크박스 상태에 따라 종료일 필드를 표시/숨김
     */
    var periodCheckbox = document.getElementById('periodcheck');
    var enddateWrapper = document.getElementById('enddateWrapper');
    
    if (periodCheckbox && enddateWrapper) {
        periodCheckbox.addEventListener('change', function() {
            if (this.checked) {
                enddateWrapper.style.display = 'inline';
            } else {
                enddateWrapper.style.display = 'none';
                // 체크 해제 시 종료일 초기화
                var enddateInput = document.getElementById('enddate');
                if (enddateInput) {
                    enddateInput.value = '';
                }
            }
        });
        
        // 초기 로드 시 체크박스 상태에 따라 종료일 표시
        if (periodCheckbox.checked) {
            enddateWrapper.style.display = 'inline';
        } else {
            enddateWrapper.style.display = 'none';
        }
    }
    
    /**
     * 시작일 변경 시 종료일 최소값 설정
     */
    var startdateInput = document.getElementById('startdate');
    var enddateInput = document.getElementById('enddate');
    
    if (startdateInput && enddateInput) {
        startdateInput.addEventListener('change', function() {
            // 종료일의 최소값을 시작일로 설정
            enddateInput.min = this.value;
            
            // 종료일이 시작일보다 이전이면 자동 조정
            if (enddateInput.value && enddateInput.value < this.value) {
                enddateInput.value = this.value;
            }
        });
        
        // 초기 로드 시 설정
        if (startdateInput.value) {
            enddateInput.min = startdateInput.value;
        }
    }
})();
</script>
