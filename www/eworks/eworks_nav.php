<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

require_once __DIR__ . '/helpers.php';

// 세션 변수 초기화
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$eworks_level = $_SESSION["eworks_level"] ?? 0;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$selnum = $_REQUEST["selnum"] ?? '';

// 데이터베이스 연결
$pdo = db_connect();

// 멤버 정보 배열 초기화
$eworks_level_arr = array();
$part_arr = array();
$position_arr = array();
$name_arr = array();
$id_arr = array();

// 멤버 정보 조회 (제조파트, 지원파트)
try {
    $sql = "SELECT * FROM {$DB}.member WHERE part IN ('제조파트', '지원파트')";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($name_arr, $row["name"]);
        array_push($id_arr, $row["id"]);
        array_push($eworks_level_arr, $row["eworks_level"]);
        array_push($part_arr, $row["part"]);
        array_push($position_arr, $row["position"]);
    }
} catch (PDOException $ex) {
    error_log("멤버 정보 조회 오류: " . $ex->getMessage());
}

// 결재권자 여부 확인
// 세션에서 user_eworks_level을 먼저 확인 (load_header.php에서 Company_approvalLine_.json 기반으로 설정됨)
$session_eworks_level = $_SESSION["user_eworks_level"] ?? 0;
$foundUser1 = 0; // 0 = 일반 사용자, 1 = 1차 결재권자, 2 = 2차 결재권자

if ($session_eworks_level > 0) {
    // 세션에 값이 있으면 우선 사용 (Company_approvalLine_.json 기반)
    $foundUser1 = (int)$session_eworks_level;
} else {
    // 세션에 값이 없으면 기존 로직대로 데이터베이스에서 조회
    // 결재권자 배열 생성
    $firstStep = array();
    $firstStepID = array();

    for ($i = 0; $i < count($eworks_level_arr); $i++) {
        $eworks_level_value = (int)$eworks_level_arr[$i];
        
        if ($eworks_level_value == 2 || $eworks_level_value == 1) {
            array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i]);
            array_push($firstStepID, $id_arr[$i]);
            
            // 현재 사용자가 결재권자 목록에 있으면 실제 eworks_level 값으로 설정
            if ($user_id === $id_arr[$i]) {
                $foundUser1 = $eworks_level_value; // 1차=1, 2차=2
            }
        }
    }
}

// 2차 결재권자 여부 확인: eworks_level == 2인 사람이 2차 결재권자
$is_final_approver = ($foundUser1 == 2);
// 1차 결재권자 여부 확인: eworks_level == 1인 사람이 1차 결재권자
$is_first_approver = ($foundUser1 == 1);

$status_arr = array();

/**
 * 각 상태별 문서 개수를 카운트하는 함수
 * load_eworkslist.php와 동일한 로직 사용
 * 
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param string $user_id 사용자 ID
 * @param string $status 문서 상태
 * @param int $workLevel 결재권자 여부
 * @param string $DB 데이터베이스명
 * @param bool $is_final_approver 2차 결재권자 여부
 * @param string $user_name 사용자 이름
 * @return int 문서 개수
 */
function countEworksStatus($pdo, $user_id, $status, $workLevel, $DB = 'mirae8440', $is_final_approver = false, $user_name = '') {
    $rawUserId = $user_id;

    // SQL Injection 방지
    $user_id = str_replace("'", "''", $user_id);
    
    // 데이터베이스명 설정
    $dbName = $DB;
    
    // view 설정
    $viewcon = " AND CONCAT('!', COALESCE(e_viewexcept_id, ''), '!') NOT LIKE '%!{$user_id}!%' ";
    $viewconNone = " AND CONCAT('!', COALESCE(e_viewexcept_id, ''), '!') LIKE '%!{$user_id}!%' ";

    $count = 0;
    $sql = "";
    $customCount = false;

    // 2차 결재권자 전용 로직
    if ($is_final_approver) {
        switch ($status) {
            case 'draft':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'draft' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'send':
                // 2차 결재권자는 상신 문서도 볼 수 있음
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'send' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'noend':
                // 결재 대기 문서: status='ing'이고, done='done'이고, 2차 결재권자는 아직 결재하지 않음
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE status = 'ing' AND done = 'done' AND CONCAT('!', COALESCE(e_confirm_id, ''), '!') NOT LIKE '%!{$user_id}!%' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'ing':
                // 진행중: 2차 결재권자가 결재 중인 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' AND status IN ('send', 'ing') AND is_deleted IS NULL" . $viewcon;
                break;
            case 'end':
                // 결재 탭: 결재 대기 문서 + 2차 결재권자가 결재한 완료 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE " .
                       "(status = 'ing' AND done = 'done' AND CONCAT('!', COALESCE(e_confirm_id, ''), '!') NOT LIKE '%!{$user_id}!%' " .
                       "OR (CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' AND status = 'end')) " .
                       "AND is_deleted IS NULL" . $viewcon;
                break;
            case 'reject':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'reject' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'wait':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'wait' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'refer':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'refer' AND is_deleted IS NULL" . $viewcon;
                break;
        }
    } else if ($workLevel != 1 && $workLevel != 2) { // 일반 사용자의 경우 (workLevel이 0, 3, 또는 다른 값)
        // 상신인 경우는 send 상신인 경우도 미결도 함께 숫자표시
        if ($status == 'noend') {
            $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'send' AND is_deleted IS NULL " . $viewcon;
        } else if ($status == 'end') {
            // 결재 탭: 자신이 작성하고 상신한 문서 중 결재 완료된 문서 (결재를 올린 숫자)
            $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'end' AND is_deleted IS NULL " . $viewcon;
        } else {
            // draft, send, ing, reject, wait, refer 등 모든 상태 처리
            $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = '{$status}' AND is_deleted IS NULL " . $viewcon;
        }
    } else if ($workLevel == 1) { // 1차 결재권자의 경우
        switch ($status) {
            case 'draft':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'draft' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'send':
                // 1차 결재권자는 상신 탭에 자신이 작성한 문서만 카운트
                // 다른 사람이 작성한 send 상태의 문서는 미결(noend) 탭에 표시됨
                // status는 'send' 또는 '상신' 둘 다 확인 (데이터베이스에 둘 다 있을 수 있음)
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND (status = 'send' OR status = '상신') AND is_deleted IS NULL" . $viewcon;
                break;
            case 'noend':
                // 1차 결재권자의 미결 문서: status='send'이고, 자신이 첫 번째 결재권자이고, 아직 결재하지 않은 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE " .
                       "CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND CONCAT('!', COALESCE(e_confirm_id, ''), '!') = '!' " .
                       "AND LOCATE('{$user_id}', e_line_id) = 1 " .
                       "AND status = 'send' " .
                       "AND is_deleted IS NULL" . $viewcon;
                break;
            case 'ing':
                // 1차 결재권자가 결재 중인 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND status IN ('send', 'ing') AND is_deleted IS NULL" . $viewcon;
                break;
            case 'end':
                // 1차 결재권자가 결재한 완료 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND status = 'end' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'reject':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'reject' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'wait':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'wait' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'refer':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'refer' AND is_deleted IS NULL" . $viewcon;
                break;
        }
    } else if ($workLevel == 2) { // 2차 결재권자의 경우
        switch ($status) {
            case 'draft':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'draft' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'send':
                // 2차 결재권자는 상신 문서도 볼 수 있음
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'send' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'noend':
                // 2차 결재 대기 문서: 1차 결재가 완료되고 2차 결재 대기 중인 문서
                // 1. status='ing' (진행 중)
                // 2. 결재라인에 2차 결재권자가 포함되어 있음
                // 3. 2차 결재권자는 아직 결재하지 않음 (e_confirm_id에 포함되지 않음)
                // 4. 1차 결재권자가 이미 결재했음 (e_confirm_id가 비어있지 않음)
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE " .
                       "status = 'ing' " .
                       "AND CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND CONCAT('!', COALESCE(e_confirm_id, ''), '!') NOT LIKE '%!{$user_id}!%' " .
                       "AND COALESCE(e_confirm_id, '') != '' " . // 1차 결재권자가 이미 결재했음
                       "AND is_deleted IS NULL" . $viewcon;
                break;
            case 'ing':
                // 2차 결재권자가 결재 중인 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' " .
                       "AND status IN ('send', 'ing') AND is_deleted IS NULL" . $viewcon;
                break;
            case 'end':
                // 2차 결재 탭: 2차 결재 대기 문서 + 2차 결재권자가 결재한 완료 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE " .
                       "(status = 'ing' AND done = 'done' AND CONCAT('!', COALESCE(e_confirm_id, ''), '!') NOT LIKE '%!{$user_id}!%' " .
                       "OR (CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' AND status = 'end')) " .
                       "AND is_deleted IS NULL" . $viewcon;
                break;
            case 'reject':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'reject' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'wait':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'wait' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'refer':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'refer' AND is_deleted IS NULL" . $viewcon;
                break;
        }
    }

    if (!$customCount) {
        try {
            $stmh = $pdo->query($sql);
            $count = $stmh->fetchColumn();
        } catch (PDOException $ex) {
            error_log("Database error in countEworksStatus: " . $ex->getMessage());
            $count = 0;
        }
    }

    return (int)$count;
}

// 각 상태별 문서 개수 카운트 (load_eworkslist.php와 동일)
$statuses = array('draft', 'send', 'noend', 'ing', 'end', 'reject', 'wait', 'refer');
$data = array();

foreach ($statuses as $index => $status) {
    // load_eworkslist.php와 동일하게 val0부터 시작
    $data['val' . $index] = countEworksStatus($pdo, $user_id, $status, $foundUser1, $DB, $is_final_approver, $user_name);
}

// 삭제된 문서 카운트 (viewexcept) - val8에 저장
$viewconDeleted = " AND CONCAT('!', e_viewexcept_id, '!') LIKE '%!{$user_id}!%' ";
try {
    $sql_deleted = "SELECT COUNT(*) FROM {$DB}.eworks WHERE (author_id = '{$user_id}' OR CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%') " . $viewconDeleted;
    $stmh_deleted = $pdo->query($sql_deleted);
    $data['val8'] = (int)$stmh_deleted->fetchColumn();
} catch (PDOException $ex) {
    error_log("삭제 문서 카운트 오류: " . $ex->getMessage());
    $data['val8'] = 0;
}

// 탭 데이터 설정 - 2차 결재권자는 다른 레이블 사용
    if ($is_final_approver) {
        // 2차 결재권자는 5개 탭 표시: 결재대기, 결재, 반려, 보류, 삭제 (진행 탭 제거)
        $tabs = array(
        array("결재대기", 3, "bi-patch-minus", $data["val2"] ?? 0),  // noend (미결 대신 결재대기)
        array("결재", 5, "bi-journal-check", $data["val4"] ?? 0),     // end (결재)
        array("반려", 6, "bi-slash-circle", $data["val5"] ?? 0),     // reject (반려)
        array("보류", 7, "bi-hourglass", $data["val6"] ?? 0),         // wait (보류)
        array("삭제", 9, "bi-trash", $data["val8"] ?? 0)             // trash (삭제)
        );
} else {
    // val0=draft(작성), val1=send(상신), val2=noend(미결), val3=ing(진행), val4=end(결재)
    // val5=reject(반려), val6=wait(보류), val7=refer(참조), val8=trash(삭제)
    $tabs = array(
        array("작성", 1, "bi-pencil-square", $data["val0"] ?? 0),  // draft
        array("상신", 2, "bi-cloud-arrow-up", $data["val1"] ?? 0),  // send
        array("미결", 3, "bi-patch-minus", $data["val2"] ?? 0),     // noend
        array("진행", 4, "bi-arrow-right-circle", $data["val3"] ?? 0), // ing
        array("결재", 5, "bi-journal-check", $data["val4"] ?? 0),    // end
        array("반려", 6, "bi-slash-circle", $data["val5"] ?? 0),    // reject
        array("보류", 7, "bi-hourglass", $data["val6"] ?? 0),        // wait
        array("참조", 8, "bi-info-circle", $data["val7"] ?? 0),      // refer
        array("삭제", 9, "bi-trash", $data["val8"] ?? 0)             // trash (val9가 아니라 val8)
    );
}

?>

<ul class="nav nav-tabs justify-content-center">
    <?php
    foreach ($tabs as $tab) {
        $label = $tab[0];
        $tabId = $tab[1];
        $iconClass = $tab[2];
        $count = $tab[3];
        $active = '';
        
        if ($selnum == $tabId) {
            $active = 'active';
        }
        
        // 탭 표시 조건
        // 2차 결재권자는 tabId가 3, 5, 6, 7, 9인 탭만 표시 (결재대기, 결재, 반려, 보류, 삭제)
        // 1차 결재권자는 모든 탭 표시 (작성, 상신, 미결, 진행, 결재, 반려, 보류, 참조, 삭제)
        // 일반 사용자도 모든 탭 표시 (작성, 상신, 미결, 진행, 결재, 반려, 보류, 참조, 삭제)
        $showTab = false;
        if ($is_final_approver) {
            // 2차 결재권자는 tabId가 3, 5, 6, 7, 9인 탭만 표시 (결재대기, 결재, 반려, 보류, 삭제)
            $showTab = ($tabId == 3 || $tabId == 5 || $tabId == 6 || $tabId == 7 || $tabId == 9);
        } else if ($is_first_approver) {
            // 1차 결재권자는 모든 탭 표시
            $showTab = true;
        } else if ($eworks_level && ($tabId >= 3)) {
            // 기타 결재권자는 미결(3) 이상만 표시 (호환성을 위해 유지)
            $showTab = true;
        } else if (!$eworks_level) {
            // 일반 사용자도 모든 탭 표시
            $showTab = true;
        }
        
        if ($showTab) {
    ?>
        <li class="nav-item">
            <div class="nav-link text-dark <?php echo $active; ?>" 
                 id="navtab<?php echo $tabId; ?>" 
                 onclick="seltab(<?php echo $tabId; ?>);">
                <i class="bi <?php echo htmlspecialchars($iconClass); ?>"></i> 
                <?php echo htmlspecialchars($label); ?>&nbsp;
                <?php if ($tabId == 5) { ?>
                    <!-- 결재 탭은 항상 숫자 표시 -->
                    <span class="badge bg-primary"><?php echo $count; ?></span>
                <?php } else if ($count > 0) { ?>
                    <span class="badge bg-primary"><?php echo $count; ?></span>
                <?php } ?>
            </div>
        </li>
    <?php
        }
    }
    ?>
</ul>

