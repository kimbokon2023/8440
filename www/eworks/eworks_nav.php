<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));
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
$foundUser1 = 0;

// 결재권자 배열 생성
$firstStep = array();
$firstStepID = array();

for ($i = 0; $i < count($eworks_level_arr); $i++) {
    $eworks_level_value = (int)$eworks_level_arr[$i];
    
    if ($eworks_level_value == 2 || $eworks_level_value == 1) {
        array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i]);
        array_push($firstStepID, $id_arr[$i]);
        
        // 현재 사용자가 결재권자 목록에 있으면 플래그 설정
        if ($user_id === $id_arr[$i]) {
            $foundUser1 = 1;
        }
    }
}

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
 * @return int 문서 개수
 */
function countEworksStatus($pdo, $user_id, $status, $workLevel, $DB = 'mirae8440') {
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

    if (!$workLevel) { // 일반 사용자의 경우 자신이 작성한 문서만 카운트
        // 상신인 경우는 send 상신인 경우도 미결도 함께 숫자표시
        if ($status == 'noend') {
            $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'send' AND is_deleted IS NULL " . $viewcon;
        } else {
            $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = '{$status}' AND is_deleted IS NULL " . $viewcon;
        }
    } else { // 결재권자의 경우 다양한 상태의 문서를 카운트
        switch ($status) {
            case 'draft':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'draft' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'send':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'send' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'noend':
                $pendingIds = fetchPendingApprovalIds($pdo, $dbName, $rawUserId);
                $count = count($pendingIds);
                $customCount = true;
                break;
            case 'ing':
                // '진행중' 상태: 사용자가 결재 중인 문서 카운트
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' AND status IN ('send', 'ing') AND is_deleted IS NULL" . $viewcon;
                break;
            case 'end':
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'end' AND is_deleted IS NULL" . $viewcon;
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
    $data['val' . ($index + 1)] = countEworksStatus($pdo, $user_id, $status, $foundUser1, $DB);
}

// 삭제된 문서 카운트 (viewexcept)
$viewconDeleted = " AND CONCAT('!', e_viewexcept_id, '!') LIKE '%!{$user_id}!%' ";
try {
    $sql_deleted = "SELECT COUNT(*) FROM {$DB}.eworks WHERE (author_id = '{$user_id}' OR CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%') " . $viewconDeleted;
    $stmh_deleted = $pdo->query($sql_deleted);
    $data['val9'] = (int)$stmh_deleted->fetchColumn();
} catch (PDOException $ex) {
    error_log("삭제 문서 카운트 오류: " . $ex->getMessage());
    $data['val9'] = 0;
}

// 탭 데이터 설정
$tabs = array(
    array("작성", 1, "bi-pencil-square", $data["val1"] ?? 0),
    array("상신", 2, "bi-cloud-arrow-up", $data["val2"] ?? 0),
    array("미결", 3, "bi-patch-minus", $data["val3"] ?? 0),
    array("진행", 4, "bi-arrow-right-circle", $data["val4"] ?? 0),
    array("결재", 5, "bi-journal-check", $data["val5"] ?? 0),
    array("반려", 6, "bi-slash-circle", $data["val6"] ?? 0),
    array("보류", 7, "bi-hourglass", $data["val7"] ?? 0),
    array("참조", 8, "bi-info-circle", $data["val8"] ?? 0),
    array("삭제", 9, "bi-trash", $data["val9"] ?? 0)
);

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
        
        // 탭 표시 조건: 일반 사용자는 모든 탭, 결재권자는 3번 이상만
        if ((!$eworks_level && ($tabId > 0)) || ($eworks_level && ($tabId >= 3))) {
    ?>
        <li class="nav-item">
            <div class="nav-link text-dark <?php echo $active; ?>" 
                 id="navtab<?php echo $tabId; ?>" 
                 onclick="seltab(<?php echo $tabId; ?>);">
                <i class="bi <?php echo htmlspecialchars($iconClass); ?>"></i> 
                <?php echo htmlspecialchars($label); ?>&nbsp;
                <?php if ($count > 0) { ?>
                    <span class="badge bg-primary"><?php echo $count; ?></span>
                <?php } ?>
            </div>
        </li>
    <?php
        }
    }
    ?>
</ul>

