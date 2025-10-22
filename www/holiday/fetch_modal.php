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

<style>
    .modal-form-container {
        padding: 10px;
    }
    
    .form-group-modern {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        border: 2px solid rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
    }
    
    .form-group-modern:hover {
        border-color: rgba(102, 126, 234, 0.3);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
    }
    
    .form-label-modern {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    
    .form-label-modern i {
        margin-right: 8px;
        font-size: 1.1rem;
    }
    
    .date-input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .custom-checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
        user-select: none;
        margin-bottom: 10px;
    }
    
    .custom-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #667eea;
        margin-right: 8px;
    }
    
    .custom-checkbox label {
        cursor: pointer;
        margin: 0;
        font-weight: 500;
        color: #555;
    }
    
    .date-separator {
        font-size: 1.2rem;
        color: #667eea;
        font-weight: bold;
        margin: 0 5px;
    }
    
    input[type="date"],
    input[type="text"] {
        border: 2px solid #e0e7ff;
        border-radius: 10px;
        padding: 10px 15px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    input[type="date"]:focus,
    input[type="text"]:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .btn-group-modern {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
    }
</style>

<input type="hidden" id="update_log" name="update_log" value="<?php echo htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="registedate" name="registedate" value="<?php echo htmlspecialchars($registedate, ENT_QUOTES, 'UTF-8'); ?>">

<div class="modal-form-container">
    <!-- 기간 설정 -->
    <div class="form-group-modern">
        <div class="form-label-modern">
            <i class="bi bi-calendar-range"></i>
            기간 설정
        </div>
        <div class="custom-checkbox">
            <input type="checkbox" id="periodcheck" name="periodcheck" value="1" <?php echo $periodcheck ? 'checked' : ''; ?>>
            <label for="periodcheck">기간 범위 지정</label>
        </div>
        <div class="date-input-wrapper">
            <input type="date" class="form-control" id="startdate" name="startdate" style="width:160px;" value="<?php echo htmlspecialchars($startdate, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span id="enddateWrapper" style="<?php echo $periodcheck ? 'display: flex; align-items: center; gap: 10px;' : 'display:none;'; ?>">
                <span class="date-separator">→</span>
                <input type="date" class="form-control" id="enddate" name="enddate" style="width:160px;" value="<?php echo htmlspecialchars($enddate, ENT_QUOTES, 'UTF-8'); ?>">
            </span>
        </div>
    </div>
    
    <!-- 비고 -->
    <div class="form-group-modern">
        <div class="form-label-modern">
            <i class="bi bi-chat-left-text"></i>
            비고
        </div>
        <input type="text" class="form-control" id="comment" name="comment" placeholder="휴무 내용을 입력하세요..." value="<?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
    </div>
    
    <!-- 버튼 그룹 -->
    <div class="btn-group-modern">
        <button type="button" id="saveBtn" class="btn btn-dark btn-sm">
            <i class="bi bi-check-circle"></i> 저장
        </button>
        <?php if ($mode === 'update'): ?>
        <button type="button" id="deleteBtn" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> 삭제
        </button>
        <?php endif; ?>
        <button type="button" class="btn btn-outline-dark btn-sm closeBtn">
            <i class="bi bi-x-circle"></i> 닫기
        </button>
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
                enddateWrapper.style.display = 'flex';
                enddateWrapper.style.alignItems = 'center';
                enddateWrapper.style.gap = '10px';
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
            enddateWrapper.style.display = 'flex';
            enddateWrapper.style.alignItems = 'center';
            enddateWrapper.style.gap = '10px';
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
