<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$selectedIds = $_REQUEST["selectedIds"] ?? array();

// 데이터베이스 연결
$pdo = db_connect();

/**
 * 사용자 직책 조회
 * 
 * @param string $userId 사용자 ID
 * @param PDO $pdo 데이터베이스 연결
 * @return string 사용자 직책
 */
function getPosition($userId, $pdo) {
    global $DB;
    
    $query = "SELECT position FROM {$DB}.member WHERE id = ?";
    
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $userId, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $row['position'] : '';
    } catch (PDOException $ex) {
        error_log("직책 조회 오류: " . $ex->getMessage());
        return '';
    }
}

/**
 * 전자결재 정보 조회
 * 
 * @param int $e_num 전자결재 번호
 * @param PDO $pdo 데이터베이스 연결
 * @return array 전자결재 정보
 */
function getEConfirmValues($e_num, $pdo) {
    global $DB;
    
    $query = "SELECT e_confirm, e_confirm_id, e_line_id FROM {$DB}.eworks WHERE num = ?";
    
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
        $stmh->execute();
        
        return $stmh->fetch(PDO::FETCH_ASSOC) ?: array();
    } catch (PDOException $ex) {
        error_log("전자결재 정보 조회 오류: " . $ex->getMessage());
        return array();
    }
}

// 현재 시간
$date = date('Y-m-d H:i:s');
$last_e_num = null;

try {
    // 선택된 항목들에 대해 결재 처리
    foreach ($selectedIds as $e_num) {
        $last_e_num = $e_num;
        
        // 기존 결재 정보 조회
        $confirmValues = getEConfirmValues($e_num, $pdo);
        $e_confirm = $confirmValues['e_confirm'] ?? '';
        $e_confirm_id = $confirmValues['e_confirm_id'] ?? '';
        $e_line_id = $confirmValues['e_line_id'] ?? '';
        
        // 결재자 정보 생성
        $position = getPosition($user_id, $pdo);
        $approver_info = $user_name . " " . $position . " " . $date;
        
        // 결재자 정보 누적
        if ($e_confirm === '' || $e_confirm === null) {
            $e_confirm_value = $approver_info;
        } else {
            $e_confirm_value = $e_confirm . '!' . $approver_info;
        }
        
        // 결재자 ID 누적
        if ($e_confirm_id === '' || $e_confirm_id === null) {
            $e_confirm_id_value = $user_id;
        } else {
            $e_confirm_id_value = $e_confirm_id . '!' . $user_id;
        }
        
        // 결재 완료 여부 확인
        $e_line_id_count = count(explode("!", $e_line_id));
        $e_confirm_count = count(explode("!", $e_confirm_id_value));
        
        $status = 'ing';
        $done = null;
        
        if ($e_line_id_count == $e_confirm_count) {
            $status = 'end';
            $done = 'done';
        }
        
        // 데이터베이스 업데이트
        $sql = "UPDATE {$DB}.eworks SET e_confirm = ?, e_confirm_id = ?, done = ?, status = ? WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_confirm_value, $e_confirm_id_value, $done, $status, $e_num));
    }
    
    // 성공 응답
    $data = array(
        "success" => true,
        "num" => $last_e_num,
        "message" => "결재 처리 완료"
    );
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $ex) {
    http_response_code(500);
    
    error_log("결재 처리 오류: " . $ex->getMessage());
    
    echo json_encode(array(
        'success' => false,
        'error' => 'Database processing error',
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
