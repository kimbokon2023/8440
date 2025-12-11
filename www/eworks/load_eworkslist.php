<?php require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/helpers.php';

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 파일 포함
require_once(includePath('session.php'));

// 변수 초기화
$pdo = null;
$user_id = $_SESSION['userid'] ?? $_SESSION['user_id'] ?? '';
$user_name = $_SESSION['name'] ?? $_SESSION['user_name'] ?? '';
$level = $_SESSION['level'] ?? 0;
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 메인화면 우측에 플로팅되어 있는 숫자를 추출하는 부분

// 데이터베이스 연결
if (!isset($pdo) || !$pdo) {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();
}

$pdo = db_connect();  

// 결재라인을 설정하기 위해 사용자 정보를 배열에 저장
$eworks_level_arr = array(); 
$part_arr = array(); 
$position_arr = array();
$name_arr = array();
$id_arr = array();

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
    error_log("Database error in load_eworkslist.php: " . $ex->getMessage());
    // 빈 배열로 초기화하여 오류 방지
    $name_arr = array();
    $id_arr = array();
    $eworks_level_arr = array();
    $part_arr = array();
    $position_arr = array();
}

// 결재권자 여부를 확인
$workLevel = 0; // 0 = 일반 사용자, 1 = 1차 결재권자, 2 = 2차 결재권자
$firstStep = array();
$firstStepID = array();

// 세션에서 user_eworks_level을 먼저 확인 (load_header.php에서 Company_approvalLine_.json 기반으로 설정됨)
$session_eworks_level = $_SESSION["user_eworks_level"] ?? 0;
if ($session_eworks_level > 0) {
    // 세션에 값이 있으면 우선 사용 (Company_approvalLine_.json 기반)
    $workLevel = (int)$session_eworks_level;
    error_log("load_eworkslist.php: 세션에서 workLevel 설정 - user_id: {$user_id}, workLevel: {$workLevel}");
} else {
    // 세션에 값이 없으면 기존 로직대로 데이터베이스에서 조회
    $eworks_count = count($eworks_level_arr);
    for ($i = 0; $i < $eworks_count; $i++) {
        $level_value = (int)$eworks_level_arr[$i];
        if ($level_value == 2 || $level_value == 1) {
            array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i]);    
            array_push($firstStepID, $id_arr[$i]);
            if ($user_id === $id_arr[$i]) {
                // 실제 eworks_level 값을 workLevel에 저장 (1차=1, 2차=2)
                $workLevel = $level_value;
            }
        }
    }
}

// mirae 사용자(사장님)는 2차 결재권자로 명시적 처리
// 세션에서 user_eworks_level을 확인하거나, 직접 조회
if ($user_id === 'mirae') {
    // 세션에서 가져오기 시도
    $session_eworks_level = $_SESSION["user_eworks_level"] ?? 0;
    if ($session_eworks_level == 2) {
        $workLevel = 2;
    } else {
        // 세션에 없으면 데이터베이스에서 직접 조회
        try {
            $sql_mirae = "SELECT eworks_level FROM {$DB}.member WHERE id = 'mirae'";
            $stmh_mirae = $pdo->prepare($sql_mirae);
            $stmh_mirae->execute();
            $row_mirae = $stmh_mirae->fetch(PDO::FETCH_ASSOC);
            if ($row_mirae) {
                $workLevel = (int)$row_mirae["eworks_level"];
                if ($workLevel == 0) {
                    // 데이터베이스에도 없으면 2차 결재권자로 설정
                    $workLevel = 2;
                }
            } else {
                // 데이터베이스에도 없으면 최종결재권자로 설정
                $workLevel = 2;
            }
        } catch (PDOException $ex) {
            error_log("Error getting mirae eworks_level: " . $ex->getMessage());
            $workLevel = 2; // 예외 발생 시에도 2차 결재권자로 설정
        }
    }
    // firstStepID 배열에도 추가
    if (!in_array($user_id, $firstStepID)) {
        $firstStepID[] = $user_id;
    }
}

// 2차 결재권자 여부 확인: eworks_level == 2인 사람이 2차 결재권자
$is_final_approver = ($workLevel == 2);

/**
 * 각 상태별 문서 개수를 카운트하는 함수
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param string $user_id 사용자 ID
 * @param string $status 문서 상태
 * @param int $workLevel 결재권자 여부
 * @param string $DB 데이터베이스명
 * @return int|string 문서 개수 또는 SQL 쿼리
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
                // 결재 대기 문서: 
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
                
                // 디버깅: 2차 결재권자의 noend 케이스 - 실제 데이터 확인
                error_log("load_eworkslist.php noend (2차 결재권자) - user_id: {$rawUserId}, SQL: {$sql}");
                
                // 실제 데이터 확인을 위한 디버깅 쿼리
                $debugSql = "SELECT num, status, done, e_line_id, e_confirm_id, author_id FROM {$dbName}.eworks WHERE " .
                           "status = 'ing' " .
                           "AND CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
                           "AND COALESCE(e_confirm_id, '') != '' " .
                           "AND is_deleted IS NULL " .
                           "LIMIT 5";
                try {
                    $debugStmh = $pdo->query($debugSql);
                    $debugRows = $debugStmh->fetchAll(PDO::FETCH_ASSOC);
                    error_log("load_eworkslist.php noend (2차 결재권자) 디버깅 - user_id: {$rawUserId}, 찾은 문서 수: " . count($debugRows));
                    foreach ($debugRows as $debugRow) {
                        $confirmId = $debugRow['e_confirm_id'] ?? '';
                        $hasUserInConfirm = (strpos($confirmId, $rawUserId) !== false);
                        error_log("  - num: {$debugRow['num']}, status: {$debugRow['status']}, done: {$debugRow['done']}, e_line_id: {$debugRow['e_line_id']}, e_confirm_id: {$confirmId}, hasUserInConfirm: " . ($hasUserInConfirm ? 'true' : 'false'));
                    }
                } catch (PDOException $ex) {
                    error_log("디버깅 SQL 오류: " . $ex->getMessage());
                }
                break;
            case 'ing':
                // 진행중: 2차 결재권자가 결재 중인 문서
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' AND status IN ('send', 'ing') AND is_deleted IS NULL" . $viewcon;
                break;
            case 'end':
                // 결재 탭: 결재 대기 문서 + 2차 결재권자가 결재한 완료 문서
                // 1. 결재 대기: status='ing'이고 done='done'이고, 결재라인에 포함되어 있고, 아직 결재하지 않음
                // 2. 결재 완료: 결재라인에 포함되어 있고, 이미 결재했고, status='end'
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE " .
                       "((status = 'ing' AND done = 'done' AND CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', COALESCE(e_confirm_id, ''), '!') NOT LIKE '%!{$user_id}!%') " .
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
    } else if (!$workLevel) { // 일반 사용자의 경우 자신이 작성한 문서만 카운트
        // 상신인 경우는 send 상신인 경우도 미결도 함께 숫자표시
        if ($status == 'noend') {
            $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE author_id = '{$user_id}' AND status = 'send' AND is_deleted IS NULL " . $viewcon;
        } else {
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
                // fetchPendingApprovalIds 함수를 사용하여 정확한 미결 문서 ID 목록을 가져온 후 카운트
                $pendingIds = fetchPendingApprovalIds($pdo, $dbName, $rawUserId);
                $count = count($pendingIds);
                $customCount = true;
                
                // 디버깅: 미결 문서 ID 목록 출력
                if ($count > 0) {
                    error_log("load_eworkslist.php noend - user_id: {$rawUserId}, pendingIds: " . implode(',', $pendingIds));
                } else {
                    // SQL 쿼리를 직접 실행하여 디버깅
                    $debugSql = "SELECT num, e_line_id, e_confirm_id, status, author_id FROM {$dbName}.eworks WHERE " .
                               "CONCAT('!', COALESCE(e_line_id, ''), '!') LIKE '%!{$rawUserId}!%' " .
                               "AND (status = 'send' OR status = '상신') " .
                               "AND is_deleted IS NULL " .
                               "LIMIT 5";
                    try {
                        $debugStmh = $pdo->query($debugSql);
                        $debugRows = $debugStmh->fetchAll(PDO::FETCH_ASSOC);
                        error_log("load_eworkslist.php noend 디버깅 - user_id: {$rawUserId}, 찾은 문서 수: " . count($debugRows));
                        foreach ($debugRows as $debugRow) {
                            error_log("  - num: {$debugRow['num']}, author_id: {$debugRow['author_id']}, e_line_id: {$debugRow['e_line_id']}, e_confirm_id: {$debugRow['e_confirm_id']}, status: {$debugRow['status']}");
                        }
                    } catch (PDOException $ex) {
                        error_log("디버깅 SQL 오류: " . $ex->getMessage());
                    }
                }
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
                // 2차 결재 대기 문서: 
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
                // 1. 2차 결재 대기: status='ing'이고 done='done'이고, 결재라인에 포함되어 있고, 아직 결재하지 않음
                // 2. 2차 결재 완료: 결재라인에 포함되어 있고, 이미 결재했고, status='end'
                $sql = "SELECT COUNT(*) FROM {$dbName}.eworks WHERE " .
                       "((status = 'ing' AND done = 'done' AND CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', COALESCE(e_confirm_id, ''), '!') NOT LIKE '%!{$user_id}!%') " .
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
            if (empty($sql)) {
                error_log("countEworksStatus: SQL이 비어있음 - status: {$status}, workLevel: {$workLevel}, user_id: {$rawUserId}");
                $count = 0;
            } else {
                $stmh = $pdo->query($sql);
                $count = $stmh->fetchColumn();
                // 디버깅: 1차 결재권자의 send와 noend 케이스 로그 출력
                if ($workLevel == 1 && ($status == 'send' || $status == 'noend')) {
                    error_log("countEworksStatus - status: {$status}, user_id: {$rawUserId}, workLevel: {$workLevel}, count: {$count}");
                    error_log("countEworksStatus SQL: {$sql}");
                }
                // 디버깅: 2차 결재권자의 noend 케이스 로그 출력
                if ($is_final_approver && $status == 'noend') {
                    error_log("countEworksStatus (2차 결재권자) - status: {$status}, user_id: {$rawUserId}, workLevel: {$workLevel}, count: {$count}");
                    error_log("countEworksStatus SQL: {$sql}");
                }
            }
        } catch (PDOException $ex) {
            error_log("Database error in countEworksStatus: " . $ex->getMessage() . " SQL: " . $sql);
            $count = 0;
        }
    } else {
        // 디버깅: customCount인 경우
        if ($workLevel == 1 && $status == 'send') {
            error_log("countEworksStatus send (customCount) - user_id: {$rawUserId}, workLevel: {$workLevel}, count: {$count}");
        }
    }

    return (int)$count;
}

// 각 상태별 문서 개수 카운트
$statuses = array('draft', 'send', 'noend', 'ing', 'end', 'reject', 'wait', 'refer');
$data = array();

foreach ($statuses as $index => $status) {
    // index.js는 val0부터 사용하므로 val0, val1, val2, val3, val4... 형태로 반환
    $count = countEworksStatus($pdo, $user_id, $status, $workLevel, $DB, $is_final_approver, $user_name);
    $data['val' . $index] = $count;
    
    // 디버깅: 1차 결재권자의 경우 상신과 미결 상태 로그 출력
    if ($workLevel == 1 && ($status == 'send' || $status == 'noend')) {
        error_log("load_eworkslist.php - status: {$status}, count: {$count}, user_id: {$user_id}, workLevel: {$workLevel}");
    }
    // 디버깅: 2차 결재권자의 경우 결재대기(noend) 상태 로그 출력
    if ($is_final_approver && $status == 'noend') {
        error_log("load_eworkslist.php (2차 결재권자) - status: {$status}, count: {$count}, user_id: {$user_id}, workLevel: {$workLevel}");
    }
}

// 디버깅 정보 추가
$data['debug'] = array(
    'user_id' => $user_id,
    'user_name' => $user_name,
    'workLevel' => $workLevel, // 0=일반, 1=1차결재권자, 2=2차결재권자
    'is_final_approver' => $is_final_approver,
    'session_eworks_level' => $_SESSION["user_eworks_level"] ?? 'not set',
    'timestamp' => date('Y-m-d H:i:s'),
    'status_counts' => array(
        'draft' => $data['val0'],
        'send' => $data['val1'],
        'noend' => $data['val2'],
        'ing' => $data['val3'],
        'end' => $data['val4']
    )
);

// 결과를 JSON으로 출력 (AJAX 요청인 경우)
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
?>
