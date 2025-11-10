<?php
// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}
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
$workLevel = 0; // 1차 결재권자 여부
$firstStep = array();
$firstStepID = array();

// 배열 길이 확인 후 처리
$eworks_count = count($eworks_level_arr);
for ($i = 0; $i < $eworks_count; $i++) {
    if ((int)$eworks_level_arr[$i] == 2 || (int)$eworks_level_arr[$i] == 1) {
        array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i]);    
        array_push($firstStepID, $id_arr[$i]);
        if ($user_id === $id_arr[$i]) {
            $workLevel = 1;
        }
    }
}

/**
 * 각 상태별 문서 개수를 카운트하는 함수
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param string $user_id 사용자 ID
 * @param string $status 문서 상태
 * @param int $workLevel 결재권자 여부
 * @param string $DB 데이터베이스명
 * @return int|string 문서 개수 또는 SQL 쿼리
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

// 각 상태별 문서 개수 카운트
$statuses = array('draft', 'send', 'noend', 'ing', 'end', 'reject', 'wait', 'refer');
$data = array();

foreach ($statuses as $index => $status) {
    $data['val' . $index] = countEworksStatus($pdo, $user_id, $status, $workLevel, $DB);
}

// 디버깅 정보 추가
$data['debug'] = array(
    'user_id' => $user_id,
    'workLevel' => $workLevel,
    'timestamp' => date('Y-m-d H:i:s')
);

// 결과를 JSON으로 출력 (AJAX 요청인 경우)
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
?>
