<?php
require_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? '';
$admin = 0;

// 관리자 권한 확인
if ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵') {
    $admin = 1;
}
?>
<?php include getDocumentRoot() . '/load_header.php'; ?>

<body>
    <?php
    $tablename = "eworks";

    // 요청 파라미터 초기화
    $num = $_REQUEST["num"] ?? '';

    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 변수 초기화
    $author_id = '';
    $author = '';
    $al_part = '';
    $registdate = '';
    $ot_type = '';
    $al_askdatefrom = '';
    $ot_start_time = '';
    $ot_end_time = '';
    $al_usedday = 0;
    $al_content = '';
    $status = '';
    $e_confirm = '';
    $e_confirm_id = '';

    // 기존 데이터 조회 (수정 모드)
    if (!empty($num)) {
        try {
            $sql = "SELECT * FROM " . $DB . "." . $tablename . " WHERE num = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $num, PDO::PARAM_STR);
            $stmh->execute();
            $row = $stmh->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $author_id = $row['author_id'] ?? '';
                $author = $row['author'] ?? '';
                $al_part = $row['al_part'] ?? '';
                $registdate = $row['registdate'] ?? '';
                $ot_type = $row['ot_type'] ?? '';
                $al_askdatefrom = $row['al_askdatefrom'] ?? '';
                $ot_start_time = $row['ot_start_time'] ?? '';
                $ot_end_time = $row['ot_end_time'] ?? '';
                $al_usedday = $row['al_usedday'] ?? 0;
                $al_content = $row['al_content'] ?? '';
                $status = $row['status'] ?? '';
                $e_confirm = $row['e_confirm'] ?? '';
                $e_confirm_id = $row['e_confirm_id'] ?? '';
            }
        } catch (PDOException $ex) {
            error_log("eworks 연장근무 조회 오류: " . $ex->getMessage());
        }
    } else {
        // 신규데이터인 경우
        $registdate = date("Y-m-d H:i:s");
        $al_askdatefrom = date("Y-m-d");
        $ot_start_time = '18:00';
        $ot_end_time = '20:30';
        $al_usedday = 2.5;
        $ot_type = '잔업';
        $status = '';
        $statusstr = '';
        $author = $user_name;
        $author_id = $user_id;
    }

    // 상태 문자열 변환
    $statusstr = '';
    switch ($status) {
        case 'send':
            $statusstr = '결재상신';
            break;
        case 'ing':
            $statusstr = '결재중';
            break;
        case 'end':
            $statusstr = '결재완료';
            break;
        case 'reject':
            $statusstr = '반려';
            break;
        case 'wait':
            $statusstr = '보류';
            break;
        case 'draft':
            $statusstr = '임시저장';
            break;
        default:
            $statusstr = '';
    }

    // 재직 직원 정보 로드
    $employee_name_arr = array();
    $employee_id_arr = array();
    $employee_part_arr = array();

    try {
        $sql_employee = "SELECT * FROM {$DB}.member WHERE position != '퇴사' ORDER BY name ASC";
        $stmh_employee = $pdo->query($sql_employee);
        while ($row_employee = $stmh_employee->fetch(PDO::FETCH_ASSOC)) {
            $eworks_level = (int)($row_employee["eworks_level"] ?? 0);
            $name = $row_employee["name"] ?? '';

            if ($eworks_level > 0 && $name != '소현철') {
                array_push($employee_name_arr, $name);
                array_push($employee_id_arr, $row_employee["id"] ?? '');
                array_push($employee_part_arr, $row_employee["part"] ?? '');
            }
        }
    } catch (PDOException $ex) {
        error_log("재직 직원 조회 오류: " . $ex->getMessage());
    }
    ?>

    <div class="container-fluid" style="max-width: 550px;">
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">연장근무 신청</h5>
            </div>
            <div class="card-body">
                <form id="overtimeForm">
                    <input type="hidden" id="mode" name="mode" value="<?= empty($num) ? 'insert' : 'update' ?>">
                    <input type="hidden" id="num" name="num" value="<?= $num ?>">
                    <input type="hidden" id="author_id" name="author_id" value="<?= $author_id ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="author" class="form-label">성명 <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="author" name="author" required>
                                <option value="">선택하세요</option>
                                <?php
                                for ($i = 0; $i < count($employee_name_arr); $i++) {
                                    $selected = ($author == $employee_name_arr[$i]) ? 'selected' : '';
                                    echo "<option $selected value='" . htmlspecialchars($employee_name_arr[$i]) . "'>" . htmlspecialchars($employee_name_arr[$i]) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="al_part" class="form-label">부서</label>
                            <input type="text" class="form-control form-control-sm" id="al_part" name="al_part" value="<?= htmlspecialchars($al_part) ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ot_type" class="form-label">근무유형 <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="ot_type" name="ot_type" required>
                                <option value="">선택하세요</option>
                                <option value="잔업" <?= $ot_type == '잔업' ? 'selected' : '' ?>>잔업 (평일 연장근무)</option>
                                <option value="특근" <?= $ot_type == '특근' ? 'selected' : '' ?>>특근 (주말/휴일 근무)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="al_askdatefrom" class="form-label">작업일자 <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="al_askdatefrom" name="al_askdatefrom" value="<?= $al_askdatefrom ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="ot_start_time" class="form-label">시작시간 <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-sm" id="ot_start_time" name="ot_start_time" value="<?= $ot_start_time ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ot_end_time" class="form-label">종료시간 <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-sm" id="ot_end_time" name="ot_end_time" value="<?= $ot_end_time ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="meal_deduction" name="meal_deduction" value="1" checked>
                                <label class="form-check-label small" for="meal_deduction">
                                    식사시간 공제 (30분)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="al_usedday" class="form-label">연장시간 (시간)</label>
                        <input type="number" step="0.5" class="form-control form-control-sm" id="al_usedday" name="al_usedday" value="<?= $al_usedday ?>" readonly>
                        <small class="text-muted">시작/종료 시간으로 자동 계산됩니다.</small>
                    </div>

                    <div class="mb-3">
                        <label for="al_content" class="form-label">신청사유 <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm" id="al_content" name="al_content" rows="3" placeholder="연장근무 사유를 입력하세요" required><?= htmlspecialchars($al_content) ?></textarea>
                    </div>

                    <?php if (!empty($statusstr)) { ?>
                    <div class="mb-3">
                        <label class="form-label">결재상태</label>
                        <input type="text" class="form-control form-control-sm" value="<?= $statusstr ?>" readonly>
                    </div>
                    <?php } ?>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close();">취소</button>
                    <div>
                        <?php if (!empty($num) && $admin == 1 && empty($status)) { ?>
                        <button type="button" class="btn btn-danger btn-sm me-2" id="deleteBtn">삭제</button>
                        <?php } ?>
                        <?php if (empty($status) || $status == 'draft') { ?>
                        <button type="button" class="btn btn-primary btn-sm" id="saveBtn">저장 후 전자결재 작성</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
// 전역 변수
var employeeNameArray = <?= json_encode($employee_name_arr); ?>;
var employeePartArray = <?= json_encode($employee_part_arr); ?>;
var employeeIdArray = <?= json_encode($employee_id_arr); ?>;
var currentUserId = '<?= $user_id ?>';
var currentUserName = '<?= $user_name ?>';

$(document).ready(function() {
    // 성명 선택 시 부서와 ID 자동 매칭
    $('#author').on('change', function() {
        var selectedName = $(this).val();
        var index = employeeNameArray.indexOf(selectedName);
        
        if (index !== -1) {
            $('#al_part').val(employeePartArray[index]);
            $('#author_id').val(employeeIdArray[index]);
        } else {
            $('#al_part').val('');
            $('#author_id').val('');
        }
    });

    // 초기 로드 시 부서 설정
    if ($('#author').val()) {
        $('#author').trigger('change');
    }

    // 시간 변경 시 연장시간 자동 계산
    $('#ot_start_time, #ot_end_time, #meal_deduction').on('change', calculateOvertimeHours);

    function calculateOvertimeHours() {
        var startTime = $('#ot_start_time').val();
        var endTime = $('#ot_end_time').val();
        var mealDeduction = $('#meal_deduction').is(':checked');

        if (startTime && endTime) {
            var start = new Date('2000-01-01 ' + startTime);
            var end = new Date('2000-01-01 ' + endTime);
            
            // 종료시간이 시작시간보다 이른 경우 (익일로 처리)
            if (end < start) {
                end.setDate(end.getDate() + 1);
            }

            var diffMs = end - start;
            var diffHours = diffMs / (1000 * 60 * 60);

            // 식사시간 공제 (30분 = 0.5시간)
            if (mealDeduction) {
                diffHours -= 0.5;
            }

            // 음수 방지
            if (diffHours < 0) {
                diffHours = 0;
            }

            // 0.5 단위로 반올림
            diffHours = Math.round(diffHours * 2) / 2;

            $('#al_usedday').val(diffHours);
        }
    }

    // 초기 계산
    calculateOvertimeHours();

    // 저장 버튼
    $('#saveBtn').on('click', function() {
        if (!$('#overtimeForm')[0].checkValidity()) {
            $('#overtimeForm')[0].reportValidity();
            return;
        }

        var formData = $('#overtimeForm').serialize();

        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('저장 성공:', response);
                
                if (response.success) {
                    alert('연장근무가 저장되었습니다.\n전자결재가 상신되었습니다.');
                    
                    // 부모 창 리로드
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    
                    window.close();
                } else {
                    alert('오류: ' + (response.message || '저장에 실패했습니다.'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX 오류:', xhr, status, error);
                console.error('응답 텍스트:', xhr.responseText);
                alert('저장 중 오류가 발생했습니다.\n' + (xhr.responseText || error));
            }
        });
    });

    // 삭제 버튼
    $('#deleteBtn').on('click', function() {
        if (!confirm('정말 삭제하시겠습니까?')) {
            return;
        }

        var num = $('#num').val();

        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: {
                mode: 'delete',
                num: num
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('삭제되었습니다.');
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    window.close();
                } else {
                    alert('오류: ' + (response.message || '삭제에 실패했습니다.'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX 오류:', xhr, status, error);
                alert('삭제 중 오류가 발생했습니다.');
            }
        });
    });
});
</script>
</body>
</html>

