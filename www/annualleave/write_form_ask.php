<?php
require_once __DIR__ . '/../bootstrap.php';
include getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? '';
$admin = 0;
if ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지') {
    $admin = 1;
}
?>
<?php include getDocumentRoot() . '/load_header.php'; ?>
<link rel="stylesheet" href="<?= asset('css/dashboard-style.css') ?>">
<style>
    /* Annual Leave Form Styles */
    .annual-leave-form-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .annual-leave-card {
        background: var(--gradient-primary);
        border: 1px solid var(--dashboard-border);
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(51, 65, 85, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .annual-leave-card:hover {
        box-shadow: 0 6px 20px rgba(51, 65, 85, 0.12);
    }

    .annual-leave-header {
        background: var(--dashboard-secondary);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--dashboard-border);
    }

    .annual-leave-header h3 {
        color: var(--dashboard-text);
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        text-align: center;
        letter-spacing: 0.3px;
    }

    .annual-leave-body {
        padding: 1.5rem;
    }

    .form-section {
        margin-bottom: 1.5rem;
    }

    .form-section-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dashboard-text);
        margin-bottom: 0.75rem;
        text-align: center;
        letter-spacing: 0.2px;
    }

    /* 2 Grid Layout */
    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    /* 4 Grid Layout */
    .form-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-grid-item {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--dashboard-text);
        text-align: left;
        white-space: nowrap;
        min-width: fit-content;
    }

    .form-field-wrapper {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 0.25rem;
        flex: 1;
    }

    .form-input-modern {
        border: 1px solid var(--dashboard-border);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: var(--dashboard-text);
        background: white;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-input-modern:focus {
        outline: none;
        border-color: var(--dashboard-accent);
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
    }

    .form-input-modern[readonly] {
        background: var(--dashboard-primary);
        color: var(--dashboard-text-secondary);
    }

    .form-select-modern {
        border: 1px solid var(--dashboard-border);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        height: 38px;
        background: white;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-select-modern:focus {
        outline: none;
        border-color: var(--dashboard-accent);
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
    }

    .radio-group {
        display: flex;
        flex-wrap: nowrap;
        justify-content: flex-start;
        gap: 1.5rem;
        margin-top: 0.5rem;
        width: 100%;
    }

    .radio-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .radio-item input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--dashboard-accent);
    }

    .radio-item label {
        font-size: 0.875rem;
        color: var(--dashboard-text);
        cursor: pointer;
        margin: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        text-align: center;
    }

    .status-badge-send {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    .status-badge-ing {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-badge-end {
        background: linear-gradient(135deg, #84cc16, #65a30d);
        color: white;
    }

    .approval-table {
        width: 100%;
        margin: 1rem auto;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(51, 65, 85, 0.06);
    }

    .approval-table thead {
        background: #f0fbff;
    }

    .approval-table th {
        padding: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dashboard-text);
        border: none;
    }

    .approval-table td {
        padding: 0.75rem;
        font-size: 0.875rem;
        color: var(--dashboard-text);
        background: white;
        border-bottom: 1px solid var(--dashboard-border);
    }

    .approval-table tbody tr:last-child td {
        border-bottom: none;
    }

    .button-group {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--dashboard-border);
        flex-wrap: wrap;
    }

    .btn-modern-primary {
        background: var(--gradient-accent);
        color: var(--btn-text-on-accent);
        border: none;
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(51, 65, 85, 0.1);
    }

    .btn-modern-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(51, 65, 85, 0.15);
        opacity: 0.9;
    }

    .btn-modern-danger {
        background: var(--gradient-info);
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2);
    }

    .btn-modern-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        opacity: 0.9;
    }

    .btn-modern-secondary {
        background: var(--dashboard-secondary);
        color: var(--btn-text-on-light);
        border: 1px solid var(--dashboard-border);
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modern-secondary:hover {
        background: #c7f0ff;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
    }

    .info-highlight {
        color: var(--status-info);
        font-weight: 600;
    }

    .danger-highlight {
        color: var(--status-danger);
        font-weight: 600;
    }

    /* Modal Modern Styling */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 8px 32px rgba(51, 65, 85, 0.12);
    }

    .modal-header {
        background: var(--dashboard-secondary);
        border-bottom: 1px solid var(--dashboard-border);
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
    }

    .modal-header .modal-title {
        color: var(--dashboard-text);
        font-weight: 600;
        font-size: 1.125rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--dashboard-border);
        padding: 1rem 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .annual-leave-form-container {
            margin: 1rem auto;
            padding: 0 0.5rem;
        }

        .annual-leave-body {
            padding: 1rem;
        }

        .form-grid-2,
        .form-grid-4 {
            grid-template-columns: 1fr;
        }

        .form-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .radio-group {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .radio-item {
            flex: 0 0 auto;
        }

        .button-group {
            flex-direction: column;
        }

        .button-group button {
            width: 100%;
        }
    }
</style>

<body>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg modal-center">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h4 class="modal-title">알림</h4>
                </div>
                <div class="modal-body">
                    <div id="alertmsg" class="text-center" style="font-size: 1rem; line-height: 1.6; color: var(--dashboard-text);">
                        결재가 진행중입니다.<br><br>
                        수정사항이 있으면 결재권자에게 말씀해 주세요.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalBtn" class="btn-modern-secondary" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $tablename = "eworks";

    // 요청 파라미터 초기화
    $num = $_REQUEST["num"] ?? '';

    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // rowDBask.php에서 사용될 변수 초기화
    $author_id = '';
    $author = '';
    $al_part = '';
    $registdate = '';
    $al_item = '';
    $al_askdatefrom = '';
    $al_askdateto = '';
    $al_usedday = 0;
    $al_content = '';
    $status = '';
    $e_confirm = '';
    $e_confirm_id = '';

    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        include 'rowDBask.php';
    } catch (PDOException $ex) {
        error_log("eworks 연차 조회 오류: " . $ex->getMessage());
    }

    // 배열로 기본정보 불러옴
    include "load_DB.php";

    // load_DB.php에서 정의될 변수들 초기화 (정의되지 않은 경우 대비)
    $basic_name_arr = $basic_name_arr ?? array();
    $basic_part_arr = $basic_part_arr ?? array();
    $referencedate_arr = $referencedate_arr ?? array(); // 년도 배열 추가
    $totalname_arr = $totalname_arr ?? array();
    $totalused_arr = $totalused_arr ?? array();
    $totalusedYear_arr = $totalusedYear_arr ?? array(); // 년도 배열 추가
    $availableday_arr = $availableday_arr ?? array();
    $employee_name_arr = $employee_name_arr ?? array();
    $employee_part_arr = $employee_part_arr ?? array();
    $employee_id_arr = $employee_id_arr ?? array(); 

    if (empty($num)) {
        // 신규데이터인 경우
        $registdate = date("Y-m-d H:i:s");
        $al_askdatefrom = date("Y-m-d");
        $al_askdateto = date("Y-m-d");
        $al_usedday = abs(strtotime($al_askdateto) - strtotime($al_askdatefrom)) + 1;
        $al_item = '연차';
        $status = 'send';
        $statusstr = '결재상신';
        $author = $_SESSION["name"];
        $author_id = $user_id;
        $al_part = '';

        // DB에서 al_part 찾아서 넣어주기
        for ($i = 0; $i < count($basic_name_arr); $i++) {
            if (trim($basic_name_arr[$i]) == trim($author)) {
                $al_part = $basic_part_arr[$i];
                break;
            }
        }
    }

    // 잔여일수 개인별 산출 루틴
    try {
        // 연차 잔여일수 산출
        $totalusedday = 0;
        $totalremainday = 0;
        $availableday = 0;

        for ($i = 0; $i < count($totalname_arr); $i++) {
            if ($author == $totalname_arr[$i]) {
                $availableday = $availableday_arr[$i];
            }
        }

        // 연차 사용일수 계산
        for ($i = 0; $i < count($totalname_arr); $i++) {
            if ($author == $totalname_arr[$i]) {
                $totalusedday = $totalused_arr[$i];
                $totalremainday = $availableday - $totalusedday;
            }
        }
    } catch (PDOException $ex) {
        error_log("잔여일수 산출 오류: " . $ex->getMessage());
    }

    // 현재 년도 가져오기 (admin.php와 동일한 방식)
    $current_year = date("Y");
    
    // JavaScript로 전달할 직원별 잔여일수 배열 생성
    // admin.php 방식과 동일하게 almember 테이블에서 현재 년도의 availableday를 가져와서 계산
    $employee_remainday_arr = array();
    $employee_availableday_arr = array();
    $employee_usedday_arr = array();
    
    // load_DB.php의 구조:
    // - totalname_arr는 basic_name_arr를 그대로 복사 (인덱스 동일)
    // - totalused_arr[j]는 basic_name_arr[j]의 사용일수
    // - totalusedYear_arr[j]는 referencedate_arr[j]와 동일
    // 따라서 같은 인덱스 j를 사용하면 됨
    
    for ($i = 0; $i < count($employee_name_arr); $i++) {
        $emp_name = $employee_name_arr[$i];
        $remainday = 0;
        $avail = 0;
        $used = 0;
        
        // almember 테이블에서 현재 년도의 availableday 찾기 (admin.php와 동일한 방식)
        // basic_name_arr에서 이름과 년도가 모두 일치하는 경우 찾기
        for ($j = 0; $j < count($basic_name_arr); $j++) {
            // 이름과 년도가 모두 일치하는 경우 (admin.php와 동일한 로직)
            if (trim($emp_name) == trim($basic_name_arr[$j]) && 
                isset($referencedate_arr[$j]) && 
                trim($referencedate_arr[$j]) == $current_year) {
                $avail = isset($availableday_arr[$j]) ? floatval($availableday_arr[$j]) : 0;
                
                // totalname_arr는 basic_name_arr를 그대로 복사했으므로 같은 인덱스 j 사용
                // admin.php 방식: 이름과 년도가 모두 일치하는 경우에만 사용일수 사용
                if (isset($totalname_arr[$j]) && isset($totalusedYear_arr[$j]) &&
                    trim($emp_name) == trim($totalname_arr[$j]) && 
                    trim($referencedate_arr[$j]) == trim($totalusedYear_arr[$j])) {
                    $used = isset($totalused_arr[$j]) ? floatval($totalused_arr[$j]) : 0;
                }
                
                $remainday = $avail - $used;
                break;
            }
        }
                
        $employee_remainday_arr[] = $remainday;
        $employee_availableday_arr[] = $avail;
        $employee_usedday_arr[] = $used;
    }
    ?>  
 
<form id="board_form" name="board_form" method="post">
<div class="annual-leave-form-container">
    <div class="annual-leave-card">
        <div class="annual-leave-header">
            <h3>연차 신청</h3>
        </div>
        <div class="annual-leave-body">
			  
				<input type="hidden" id="mode" name="mode">
				<input type="hidden" id="num" name="num" value="<?=$num?>" >			  				
				<input type="hidden" id="registdate" name="registdate" value="<?=$registdate?>"  >			  						  				
				<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" >
				<input type="hidden" id="author_id" name="author_id" value="<?=$author_id?>" > 					
				<input type="hidden" id="htmltext" name="htmltext" > 					
				
				<?php
				//var_dump($al_part);			

               if($e_confirm ==='') 
			   {
					$formattedDate = date("m/d", strtotime($registdate)); // 월/일 형식으로 변환
					// echo $formattedDate; // 출력
					
					if($al_part=='제조파트')
					{
						$approvals = array(
							array("name" => "공장장 이경묵", "date" =>  $formattedDate),
							array("name" => "대표 소현철", "date" =>  $formattedDate),
							// 더 많은 결재권자가 있을 수 있음...
						);	
					}
					if($al_part=='지원파트')
					{
						$approvals = array(
							array("name" => "이사 최장중", "date" =>  $formattedDate),
							array("name" => "대표 소현철", "date" =>  $formattedDate),
							// 더 많은 결재권자가 있을 수 있음...
						);	
					}
			   }
			   else
			   {			
					$approver_ids = explode('!', $e_confirm_id);
					$approver_details = explode('!', $e_confirm);

					$approvals = array();

					foreach($approver_ids as $index => $id) {
						if (isset($approver_details[$index])) {
							// Use regex to match the pattern (name title date time)
							// The pattern looks for any character until it hits a series of digits that resemble a date followed by a time
							preg_match("/^(.+ \d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})$/", $approver_details[$index], $matches);

							// Ensure that the full pattern and the two capturing groups are present
							if (count($matches) === 3) {
								$nameWithTitle = $matches[1]; // This is the name and title
								$time = $matches[2]; // This is the time
								$date = substr($nameWithTitle, -10); // Extract date from the end of the 'nameWithTitle' string
								$nameWithTitle = trim(str_replace($date, '', $nameWithTitle)); // Remove the date from the 'nameWithTitle' to get just the name and title
								$formattedDate = date("m/d H:i:s", strtotime("$date $time")); // Combining date and time

								$approvals[] = array("name" => $nameWithTitle, "date" => $formattedDate);
							}
						}
					}
			   }
			   ?>
				
            <?php if($status === 'end') { ?>
                <div class="form-section">
                    <table class="approval-table">
                        <thead>
                            <tr>
                                <th colspan="<?php echo count($approvals); ?>" class="text-center">결재</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($approvals as $approval) { ?>
                                    <td class="text-center" style="height: 50px;"><?php echo htmlspecialchars($approval["name"]); ?></td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($approvals as $approval) { ?>
                                    <td class="text-center"><?php echo htmlspecialchars($approval["date"]); ?></td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <div id="savetext">
                <!-- 첫 번째 2 Grid: 성명 | 부서 -->
                <div class="form-section">
                    <div class="form-grid-2">
                        <div class="form-grid-item">
                            <label class="form-label">성명</label>
                            <select name="author" id="author" class="form-select-modern" style="flex: 1;">
                                <?php
                                for($i=0; $i<count($employee_name_arr); $i++) {
                                    $selected = ($author == $employee_name_arr[$i]) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($employee_name_arr[$i]) . "' {$selected}>" . htmlspecialchars($employee_name_arr[$i]) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-grid-item">
                            <label class="form-label">부서</label>
                            <input type="text" id="al_part" name="al_part" value="<?=$al_part?>" class="form-input-modern" style="flex: 1;" readonly>
                        </div>
                    </div>
                </div>

                <!-- 두 번째 2 Grid: 신청시작일 | 신청종료일 -->
                <div class="form-section">
                    <div class="form-grid-2">
                        <div class="form-grid-item">
                            <label class="form-label">신청시작일</label>
                            <input type="date" id="al_askdatefrom" name="al_askdatefrom" class="form-input-modern" style="flex: 1;" required autofocus value="<?=$al_askdatefrom?>">
                        </div>
                        <div class="form-grid-item">
                            <label class="form-label">신청종료일</label>
                            <input type="date" id="al_askdateto" name="al_askdateto" class="form-input-modern" style="flex: 1;" required value="<?=$al_askdateto?>">
                        </div>
                    </div>
                </div>

                <!-- 연차 종류 섹션 -->
                <div class="form-section">
                    <div class="form-section-title">연차 종류</div>
                    <div class="radio-group">
                        <?php
                        $item_arr = array('연차','오전반차','오전반반차','오후반차','오후반반차','경조사');
                        for($i=0; $i<count($item_arr); $i++) {
                            $checked = ($al_item == $item_arr[$i]) ? 'checked="checked"' : '';
                            echo '<div class="radio-item">';
                            echo "<input type='radio' name='al_item' id='al_item_{$i}' value='" . htmlspecialchars($item_arr[$i]) . "' {$checked}>";
                            echo "<label for='al_item_{$i}'>" . htmlspecialchars($item_arr[$i]) . "</label>";
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- 두 번째 4 Grid: 신청 기간 산출 | 연차 잔여일수 | 신청 사유 | 결재 상태 -->
                <div class="form-section">
                    <div class="form-grid-4">
                        <div class="form-grid-item">
                            <label class="form-label danger-highlight">신청 기간 산출</label>
                            <div class="form-field-wrapper">
                                <input type="text" id="al_usedday" name="al_usedday" class="form-input-modern" style="text-align: center; font-weight: 600;" readonly value="<?=$al_usedday?>">
                                <span style="color: var(--dashboard-text-muted); font-size: 0.75rem; white-space: nowrap;">일</span>
                            </div>
                        </div>
                        <div class="form-grid-item">
                            <label class="form-label info-highlight">연차 잔여일수</label>
                            <div class="form-field-wrapper">
                                <input type="text" id="totalremainday" name="totalremainday" class="form-input-modern" style="text-align: center; font-weight: 600;" readonly value="<?=$totalremainday?>">
                                <span style="color: var(--dashboard-text-muted); font-size: 0.75rem; white-space: nowrap;">일</span>
                            </div>
                        </div>
                        <div class="form-grid-item">
                            <label class="form-label">신청 사유</label>
                            <select name="al_content" id="al_content" class="form-select-modern" style="flex: 1;">
                                <?php
                                $al_content_arr = array("개인사정","휴가","여행", "병원진료등", "전직원연차", "경조사", "기타");
                                for($i=0; $i<count($al_content_arr); $i++) {
                                    $selected = ($al_content == $al_content_arr[$i]) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($al_content_arr[$i]) . "' {$selected}>" . htmlspecialchars($al_content_arr[$i]) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-grid-item">
                            <?php
                            switch($status) {
                                case 'send':
                                    $statusstr = '결재상신';
                                    $statusClass = 'status-badge-send';
                                    break;
                                case 'ing':
                                    $statusstr = '결재중';
                                    $statusClass = 'status-badge-ing';
                                    break;
                                case 'end':
                                    $statusstr = '결재완료';
                                    $statusClass = 'status-badge-end';
                                    break;
                                default:
                                    $statusstr = '';
                                    $statusClass = '';
                                    break;
                            }
                            ?>
                            <label class="form-label">결재 상태</label>
                            <?php if($statusstr) { ?>
                                <input type="hidden" id="status" name="status" value="<?=$status?>">
                                <span class="status-badge <?=$statusClass?>"><?=$statusstr?></span>
                            <?php } else { ?>
                                <span style="color: var(--dashboard-text-muted); font-size: 0.875rem;">-</span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>


            <!-- 버튼 그룹 -->
            <div class="button-group">
                <button id="saveBtn" class="btn-modern-primary" type="button">
                    <?php echo ((int)$num > 0) ? '결재상신(수정)' : '결재상신(등록)'; ?>
                </button>
                <?php if((int)$num > 0) { ?>
                    <button id="delBtn" class="btn-modern-danger" type="button">삭제</button>
                <?php } ?>
                <button id="closeBtn" class="btn-modern-secondary" type="button">× 닫기</button>
            </div>
        </div>
    </div>
</div>
</form>
		
<script> 
ajaxRequest = null;

$(document).ready(function(){
				
	// Array of employee names, parts, IDs, and remaining days
	var employeeNameArray = <?= json_encode($employee_name_arr); ?>;
	var employeePartArray = <?= json_encode($employee_part_arr); ?>; // Array of corresponding employee parts
	var employeeIdArray = <?= json_encode($employee_id_arr); ?>; // Array of corresponding employee IDs
	var employeeRemaindayArray = <?= json_encode($employee_remainday_arr); ?>; // Array of corresponding remaining days
	var employeeAvailabledayArray = <?= json_encode($employee_availableday_arr); ?>; // Array of corresponding available days
	var employeeUseddayArray = <?= json_encode($employee_usedday_arr); ?>; // Array of corresponding used days
	
	// 디버그: totalname_arr도 전달 (원본 데이터 확인용)
	var totalNameArray = <?= json_encode($totalname_arr ?? array()); ?>;
	var totalAvailabledayArray = <?= json_encode($availableday_arr ?? array()); ?>;
	var totalUseddayArray = <?= json_encode($totalused_arr ?? array()); ?>;
	var totalUsedYearArray = <?= json_encode($totalusedYear_arr ?? array()); ?>;
	
	// 현재 년도 가져오기
	var currentYear = new Date().getFullYear().toString();

	// 디버그: 배열 내용 출력
	console.log("=== 직원 정보 배열 디버그 ===");
	console.log("직원 이름 배열:", employeeNameArray);
	console.log("직원 부서 배열:", employeePartArray);
	console.log("직원 ID 배열:", employeeIdArray);
	console.log("직원 잔여일수 배열:", employeeRemaindayArray);
	console.log("직원 가용일수 배열:", employeeAvailabledayArray);
	console.log("직원 사용일수 배열:", employeeUseddayArray);
	
	// 디버그: 원본 totalname_arr 데이터 출력
	console.log("\n=== 원본 totalname_arr 데이터 (PHP에서 계산된 원본) ===");
	console.log("totalname_arr:", totalNameArray);
	console.log("availableday_arr:", totalAvailabledayArray);
	console.log("totalused_arr:", totalUseddayArray);
	console.log("totalusedYear_arr:", totalUsedYearArray);
	
	// 현재 년도 가져오기
	var currentYear = new Date().getFullYear().toString();
	console.log("현재 년도:", currentYear);
	
	// totalname_arr에서 각 직원의 정보 확인 (현재 년도만)
	console.log("\n=== totalname_arr 인덱스별 정보 (현재 년도: " + currentYear + ") ===");
	for (var i = 0; i < totalNameArray.length; i++) {
		var name = totalNameArray[i];
		var year = totalUsedYearArray[i] || '';
		var avail = totalAvailabledayArray[i] || 0;
		var used = totalUseddayArray[i] || 0;
		var remain = avail - used;		
	}
	
	// 각 인덱스별로 매칭 정보 출력
	console.log("\n=== 인덱스별 매칭 정보 ===");
	for (var i = 0; i < employeeNameArray.length; i++) {
		console.log("인덱스 " + i + ":", {
			이름: employeeNameArray[i],
			부서: employeePartArray[i],
			ID: employeeIdArray[i],
			가용일수: employeeAvailabledayArray[i],
			사용일수: employeeUseddayArray[i],
			잔여일수: employeeRemaindayArray[i]
		});
	}

	// Elements from the DOM
	var nameSelect = document.getElementById("author");
	var partInput = document.getElementById("al_part");
	var authorIdInput = document.getElementById("author_id");
	var remaindayInput = document.getElementById("totalremainday");

	// Function to update part, author ID, and remaining days based on the selected name
	function updatePartAndId() {
		// selectedIndex 대신 실제 선택된 value로 인덱스 찾기
		var selectedValue = nameSelect.value;
		var selectedIndex = -1;
		
		// 선택된 값으로 배열에서 인덱스 찾기
		for (var i = 0; i < employeeNameArray.length; i++) {
			if (employeeNameArray[i] === selectedValue) {
				selectedIndex = i;
				break;
			}
		}
		
		// 디버그: 선택 정보 출력
		console.log("\n=== 성명 선택 이벤트 ===");
		console.log("선택된 값 (value):", selectedValue);
		console.log("선택된 인덱스 (배열에서 찾은 인덱스):", selectedIndex);
		console.log("selectedIndex (DOM):", nameSelect.selectedIndex);
		
		var selectedPart = selectedIndex >= 0 ? employeePartArray[selectedIndex] : "";
		var selectedAuthorId = selectedIndex >= 0 ? employeeIdArray[selectedIndex] : "";
		var selectedRemainday = selectedIndex >= 0 ? (employeeRemaindayArray[selectedIndex] || 0) : 0;
		var selectedAvailable = selectedIndex >= 0 ? (employeeAvailabledayArray[selectedIndex] || 0) : 0;
		var selectedUsed = selectedIndex >= 0 ? (employeeUseddayArray[selectedIndex] || 0) : 0;

		// totalname_arr에서 직접 찾아서 확인 (현재 년도 조건 추가)
		var totalIndex = -1;
		var totalAvailable = 0;
		var totalUsed = 0;
		var totalRemain = 0;
		for (var k = 0; k < totalNameArray.length; k++) {
			// 이름과 년도가 모두 일치하는 경우만 찾기 (admin.php와 동일한 방식)
			if (totalNameArray[k] === selectedValue && 
				totalUsedYearArray[k] === currentYear) {
				totalIndex = k;
				totalAvailable = totalAvailabledayArray[k] || 0;
				totalUsed = totalUseddayArray[k] || 0;
				totalRemain = totalAvailable - totalUsed;
				break;
			}
		}

		console.log("매칭된 정보 (employee 배열에서):", {
			이름: selectedValue,
			부서: selectedPart,
			ID: selectedAuthorId,
			가용일수: selectedAvailable,
			사용일수: selectedUsed,
			잔여일수: selectedRemainday
		});
		
		console.log("원본 정보 (totalname_arr에서 직접 찾음):", {
			이름: selectedValue,
			totalname_arr_인덱스: totalIndex,
			가용일수: totalAvailable,
			사용일수: totalUsed,
			잔여일수: totalRemain
		});
		
		if (totalIndex >= 0 && (selectedAvailable !== totalAvailable || selectedRemainday !== totalRemain)) {
			console.warn("⚠️ 불일치 감지! employee 배열과 totalname_arr의 값이 다릅니다.");
			console.warn("employee 배열 값:", selectedAvailable, "/", selectedRemainday);
			console.warn("totalname_arr 값:", totalAvailable, "/", totalRemain);
			
			// totalname_arr의 값을 사용하도록 수정
			selectedAvailable = totalAvailable;
			selectedUsed = totalUsed;
			selectedRemainday = totalRemain;
			console.log("✅ totalname_arr 값을 사용하도록 수정됨");
		}

		partInput.value = selectedPart;
		authorIdInput.value = selectedAuthorId;
		
		// 연차 잔여일수 업데이트
		if (remaindayInput) {
			remaindayInput.value = selectedRemainday;
			console.log("잔여일수 입력 필드 업데이트:", selectedRemainday);
		}
		console.log("========================\n");
	}

	// Event listener for when the name selection changes
	nameSelect.addEventListener("change", updatePartAndId);

	// Initialize part, author ID, and remaining days on page load
	updatePartAndId();

	
var status =  $('#status').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});

// 버튼 호버 효과 개선
$('.btn-modern-primary, .btn-modern-danger, .btn-modern-secondary').on('mousedown', function() {
    $(this).css('transform', 'translateY(0)');
}).on('mouseup', function() {
    $(this).css('transform', 'translateY(-2px)');
});
	
	
    // 신청일 변경시 종료일도 변경함
    $("#al_askdatefrom").change(function() {
        var radioVal = $('input[name="al_item"]:checked').val();
        console.log(radioVal);
        $('#al_askdateto').val($("#al_askdatefrom").val());

        var result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
   
   switch(radioVal)
   {
      case '연차' :
	     $('#al_usedday').val(result);
		 break;
	  case '오전반차' :	 
	  case '오후반차' :	 	   
		 $('#al_usedday').val(result/2);
		 break;
	  case '오전반반차' :	 
	  case '오후반반차' :	 	   
		 $('#al_usedday').val(result/4);
		 break;
	  case '경조사' :	 	   
		 $('#al_usedday').val(0);
		 break;		 
   }
		 
});	
	
    $('input[name="al_item"]').change(function() {
        var radioVal = $('input[name="al_item"]:checked').val();
        console.log(radioVal);

        var result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
   
   switch(radioVal)
   {
      case '연차' :
	     $('#al_usedday').val(result);
		 break;
	  case '오전반차' :	 
	  case '오후반차' :	 	   
		 $('#al_usedday').val(result/2);
		 break;
	  case '오전반반차' :	 
	  case '오후반반차' :	 	   
		 $('#al_usedday').val(result/4);
		 break;
	  case '경조사' :	 	   
		 $('#al_usedday').val(0);
		 break;
   }
});	

    // 종료일을 변경해도 자동계산해 주기
    $("#al_askdateto").change(function() {
        var radioVal = $('input[name="al_item"]:checked').val();
        console.log(radioVal);

        var result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
   
   switch(radioVal)
   {
      case '연차' :
	     $('#al_usedday').val(result);
		 break;
	  case '오전반차' :	 
	  case '오후반차' :	 	   
		 $('#al_usedday').val(result/2);
		 break;
	  case '오전반반차' :	 
	  case '오후반반차' :	 	   
		 $('#al_usedday').val(result/4);
		 break;
	  case '경조사' :	 	   
		 $('#al_usedday').val(0);
		 break;		 
   }
});	

$("#closeBtn").off('click').on('click', function(){    // 저장하고 창닫기	
	window.close(); // 현재 창 닫기
	setTimeout(function(){									
				if (window.opener && !window.opener.closed) {					
					window.opener.location.reload(); // 부모 창 새로고침
				}					

	return false;
	}, 1000);
 });	 

// 휴가 등 대량으로 데이터를 생성할때 활용하는 루틴
$("#massBtn").off('click').on('click', function(){   

  $("#mode").val('insert');     
	  
	$.ajax({
		url: "mass_insert.php",
		type: "post",		
		data: $("#board_form").serialize(),
		dataType:"json",
		success : function( data ){
			console.log( data);
		    opener.location.reload();
		    window.close();			
		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
		} 			      		
	   });				
 }); 				
					
$("#saveBtn").off('click').on('click', function(){      // DATA 저장버튼 누름
	var num = $("#num").val();  
	var part = $("#part").val();  
    var status = $("#status").val();  
    var user_name = $("#user_name").val(); 	
    var admin = '<?php echo $admin ; ?>';

if(status=='send' || admin === '1') {  
   if(Number(num)>0) 
       $("#mode").val('modify');     
      else
          $("#mode").val('insert');   

    // savetext div의 HTML 내용을 가져옴
    var htmlContent = document.getElementById('savetext').innerHTML;

   $("#htmltext").val(encodeURIComponent(htmlContent));	  
	  
	$.ajax({
		url: "insert_ask.php",
		type: "post",		
		data: $("#board_form").serialize(),
		dataType:"json",
		success : function( data ){
			console.log( data);
		    opener.location.reload();
		    window.close();			
		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
		} 			      		
	   });		
	} // end of if
		else
		{
			
		Toastify({
			text: "본인과 관리자만 수정 가능",
			duration: 2000,
			close:true,
			gravity:"top",
			position: "center",
			style: {
				background: "linear-gradient(to right, #00b09b, #96c93d)"
			},
		}).showToast();	
		}
		
 }); 
		 
$("#delBtn").off('click').on('click', function(){      // del
var num = $("#num").val();    
var status = $("#status").val();  
var user_name = $("#user_name").val();  
var admin = '<?php echo $admin ; ?>';
   
// 결재상신이 아닌경우 수정안됨
if(status=='send' || admin === '1') {   
   
	// DATA 삭제버튼 클릭시
	Swal.fire({ 
	   title: '삭제', 
	   text: " 삭제! '\n 정말 삭제 하시겠습니까?", 
	   icon: 'warning', 
	   showCancelButton: true, 
	   confirmButtonColor: '#3085d6', 
	   cancelButtonColor: '#d33', 
	   confirmButtonText: '삭제', 
	   cancelButtonText: '취소' })
	   .then((result) => { if (result.isConfirmed) {
 
	   
		$("#mode").val('delete');  
			
		if (ajaxRequest !== null) {
				ajaxRequest.abort();
			}

			 // ajax 요청 생성
		 ajaxRequest = $.ajax({	
					url: "insert_ask.php",
					type: "post",		
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){														
								console.log('저장된 Num ' + $("#num").val()) ;													
								 Toastify({
										text: "삭제 완료!",
										duration: 3000,
										close:true,
										gravity:"top",
										position: "center",
										style: {
											background: "linear-gradient(to right, #00b09b, #96c93d)"
										},
									}).showToast();									
																			
							console.log( data);
							opener.location.reload();
							window.close();	
											
							},
							error : function( jqxhr , status , error ){
								console.log( jqxhr , status , error );

								$('#myModal').modal('show');  								
						} 			      		
					   });												
			} }) // end of then

		} // end of ajax
   }); // end of ajax

	return false;
}); // end of delBtn

// 두날짜 사이 일자 구하기
function getDateDiff(d1, d2) {
    var date1 = new Date(d1);
    var date2 = new Date(d2);
    var count = 0;
    var oneDay = 24 * 60 * 60 * 1000;

    while (date1 < date2) {
        var dayOfWeek = date1.getDay();

        // 토요일(6)이나 일요일(0)이 아닌 경우에만 count 증가
        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
            count++;
        }

        date1.setTime(date1.getTime() + oneDay);
    }

    return count;
}
</script>
</body>

</html>


