<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

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
 * 전자결재 상태별 카운트 조회
 * 
 * @param PDO $pdo 데이터베이스 연결
 * @param string $user_id 사용자 ID
 * @param string $viewCondition 조회 조건
 * @param int $isApprover 결재권자 여부 (0 또는 1)
 * @return array 상태별 카운트 배열
 */
function countEworksStatus($pdo, $user_id, $viewCondition, $isApprover) {
    global $DB;
    
    // 상태별 카운트 초기화
    $counts = array(
        "draft" => 0,
        "send" => 0,
        "noend" => 0,
        "ing" => 0,
        "end" => 0,
        "reject" => 0,
        "wait" => 0,
        "refer" => 0,
        "deleted" => 0
    );
    
    // SQL 쿼리 생성
    if ($isApprover) {
        $sqlBase = "SELECT * FROM {$DB}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND is_deleted IS NULL";
    } else {
        $sqlBase = "SELECT * FROM {$DB}.eworks WHERE author_id = '{$user_id}' AND is_deleted IS NULL";
    }
    
    $sql = $sqlBase . $viewCondition;
    
    try {
        $stmh = $pdo->prepare($sql);
        $stmh->execute();
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include includePath('eworks/_row.php');
            
            // 결재권자인 경우 상태 재계산
            if ($isApprover) {
                $arr = explode("!", $e_line_id);
                $approval_time = explode("!", $e_confirm_id);
                $last_user_id = end($arr);
                $last_approved_id = end($approval_time);
                
                foreach ($arr as $id) {
                    if ($id == $user_id) {
                        // 특정 상태가 아닌 경우 재계산
                        if ($status !== 'reject' && $status !== 'wait' && 
                            $status !== 'refer' && $status !== 'end') {
                            
                            if ($id == $last_user_id) {
                                // 마지막 사용자인 경우
                                if ($last_approved_id == $id) {
                                    $status = 'end';
                                } else {
                                    if ($status !== 'send') {
                                        $status = 'noend';
                                    } else {
                                        $status = '';
                                    }
                                }
                            } else {
                                // 마지막 사용자가 아닌 경우
                                if (in_array($id, $approval_time)) {
                                    $status = 'ing';
                                } else {
                                    $status = 'noend';
                                }
                            }
                        }
                    }
                }
            }
            
            if (isset($counts[$status])) {
                $counts[$status]++;
            }
        }
    } catch (PDOException $ex) {
        error_log("전자결재 상태 조회 오류: " . $ex->getMessage());
    }
    
    return $counts;
}

// 함수 호출
$viewconVisible = " AND CONCAT('!', e_viewexcept_id, '!') NOT LIKE '%!{$user_id}!%' ";
$visibleCounts = countEworksStatus($pdo, $user_id, $viewconVisible, $foundUser1);

$viewconDeleted = " AND CONCAT('!', e_viewexcept_id, '!') LIKE '%!{$user_id}!%' ";
$deletedCounts = countEworksStatus($pdo, $user_id, $viewconDeleted, $foundUser1);

// 각 상태별 카운트 할당
$data = array(
    "val1" => $visibleCounts["draft"],
    "val2" => $visibleCounts["send"],
    "val3" => $visibleCounts["noend"],
    "val4" => $visibleCounts["ing"],
    "val5" => $visibleCounts["end"],
    "val6" => $visibleCounts["reject"],
    "val7" => $visibleCounts["wait"],
    "val8" => $visibleCounts["refer"],
    "val9" => $deletedCounts["deleted"]
);

// 탭 데이터 설정
$tabs = array(
    array("작성", 1, "bi-pencil-square", $data["val1"]),
    array("상신", 2, "bi-cloud-arrow-up", $data["val2"]),
    array("미결", 3, "bi-patch-minus", $data["val3"]),
    array("진행", 4, "bi-arrow-right-circle", $data["val4"]),
    array("결재", 5, "bi-journal-check", $data["val5"]),
    array("반려", 6, "bi-slash-circle", $data["val6"]),
    array("보류", 7, "bi-hourglass", $data["val7"]),
    array("참조", 8, "bi-info-circle", $data["val8"]),
    array("삭제", 9, "bi-trash", $data["val9"])
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

