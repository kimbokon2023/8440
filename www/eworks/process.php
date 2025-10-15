<?php
// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Return JSON for AJAX requests
header('Content-Type: application/json; charset=utf-8');

// 변수 초기화
$e_num = $_REQUEST["e_num"] ?? $_REQUEST["num"] ?? null;
$ripple_num = $_REQUEST["ripple_num"] ?? '';
$SelectWork = $_REQUEST["SelectWork"] ?? 'insert';
$e_line = $_REQUEST["e_line"] ?? '';
$e_line_id = $_REQUEST["e_line_id"] ?? '';
$e_confirm = $_REQUEST["e_confirm"] ?? '';
$eworks_item = $_REQUEST["eworks_item"] ?? '';
$author = $_REQUEST["author"] ?? '';
$author_id = $_REQUEST["author_id"] ?? '';

// 세션 변수들 초기화
$user_name = $_SESSION['name'] ?? '';
$user_id = $_SESSION['user_id'] ?? '';
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 기타 변수들 초기화
$recent_num = $e_num;
$status = '';
$e_title = '';
$contents = '';
$registdate = '';
$e_confirm_id = '';
$r_line = '';
$r_line_id = '';
$recordtime = '';
$e_viewexcept_id = '';
$done = '';
$date = date('Y-m-d H:i:s');

/**
 * 사용자의 직책을 가져오는 함수
 * @param string $userId 사용자 ID
 * @param PDO $pdo 데이터베이스 연결 객체
 * @return string 직책명
 */
function getPosition($userId, $pdo, $DB = 'mirae8440') {
    $query = "SELECT position FROM {$DB}.member WHERE id = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $userId, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['position'] : '';
    } catch (PDOException $ex) {
        error_log("getPosition Error: " . $ex->getMessage());
        return '';
    }
}

/**
 * 댓글 데이터를 가져오는 함수
 * @param int $rippleId 댓글 ID
 * @param PDO $pdo 데이터베이스 연결 객체
 * @return array 댓글 데이터
 */
function getRippleData($rippleId, $pdo, $DB = 'mirae8440') {
    $query = "SELECT * FROM {$DB}.eworks_ripple WHERE num = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $rippleId, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        return $row ?: array();
    } catch (PDOException $ex) {
        error_log("getRippleData Error: " . $ex->getMessage());
        return array();
    }
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열 분리 및 카운트
$arr = explode("!", $e_line_id);		
$e_line_count = count($arr);
// 결재시간 추출해서 조합하기
$approval_time = explode("!", $e_confirm);	
$e_confirm_count = count($approval_time);

// _request.php 포함
include "_request.php";

// 상태 초기화
if ($status == null) {
    $status = 'draft';   // 최초 작성으로 설정함
}

try {
    if ($SelectWork == "update") {	
        $sql = "UPDATE {$DB}.eworks SET eworks_item=?, e_title=?, contents=?, registdate=?, status=?, e_line=?, e_line_id=?, e_confirm=?, e_confirm_id=?, r_line=?, r_line_id=?, recordtime=?, author=?, author_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($eworks_item, $e_title, $contents, $date, $status, $e_line, $e_line_id, $e_confirm, $e_confirm_id, $r_line, $r_line_id, $recordtime, $author, $author_id, $e_num));
    }
                
    if ($SelectWork == "insert") {	 
        // 데이터베이스에 새로운 문서 추가
        $sql = "INSERT INTO {$DB}.eworks (eworks_item, e_title, contents, registdate, status, e_line, e_line_id, e_confirm, e_confirm_id, r_line, r_line_id, recordtime, author, author_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($eworks_item, $e_title, $contents, $registdate, $status, $e_line, $e_line_id, $e_confirm, $e_confirm_id, $r_line, $r_line_id, $recordtime, $author, $author_id));
        $recent_num = $pdo->lastInsertId();
    } 


    // 결재상신 (작성에서 상신으로 수정)
    if ($SelectWork == "send") {     
        $status = 'send';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));
    }

    // 결재 승인
    if ($SelectWork == "approval") {
        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

        $sql = "UPDATE {$DB}.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_confirm_value, $e_confirm_id_value, $e_num));
        
        // 결재상태 확인 및 업데이트
        $arr = explode("!", $e_line_id);
        $approval_time = explode("!", $e_confirm_id_value); // 최신 e_confirm_id 사용
        $e_line_count = count($arr);
        $e_confirm_count = count($approval_time);

        if ($e_line_count > $e_confirm_count) {
            $status = 'ing';
        } else if ($e_line_count == $e_confirm_count) {
            $status = 'end';
            $done = 'done';
            $sql_done = "UPDATE {$DB}.eworks SET done=? WHERE num=?";
            $stmh_done = $pdo->prepare($sql_done);
            $stmh_done->execute(array($done, $e_num));
        }

        // status 업데이트
        $sql_status = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh_status = $pdo->prepare($sql_status);
        $stmh_status->execute(array($status, $e_num));
    }
    // 복구
    if ($SelectWork == "restore") {
        $idArray = explode('!', $e_viewexcept_id);
        if (($key = array_search($user_id, $idArray)) !== false) {
            unset($idArray[$key]);
        }
        $e_viewexcept_id_new = implode('!', $idArray);

        $sql = "UPDATE {$DB}.eworks SET e_viewexcept_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_viewexcept_id_new, $e_num));
    }

    // viewexcept 처리 본인에게 보이지 않게 하는 메뉴
    if ($SelectWork == "except") {    
        $e_viewexcept_id_new = ($e_viewexcept_id === '' || $e_viewexcept_id === null) ? $user_id : $e_viewexcept_id . '!' . $user_id;
        
        $sql = "UPDATE {$DB}.eworks SET e_viewexcept_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_viewexcept_id_new, $e_num));
    }

    // 결재 회수
    if ($SelectWork == "recall") {
        $status = 'draft';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));
    }

    // 결재 거절
    if ($SelectWork == "reject") {
        $status = 'reject';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));

        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

        $sql_confirm = "UPDATE {$DB}.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh_confirm = $pdo->prepare($sql_confirm);
        $stmh_confirm->execute(array($e_confirm_value, $e_confirm_id_value, $e_num));
    }

    // 결재 보류
    if ($SelectWork == "wait") {
        $status = 'wait';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));

        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;
        
        $sql_confirm = "UPDATE {$DB}.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh_confirm = $pdo->prepare($sql_confirm);
        $stmh_confirm->execute(array($e_confirm_value, $e_confirm_id_value, $e_num));
    }

    if ($SelectWork == "delete_ripple") {
        $sql = "UPDATE {$DB}.eworks_ripple SET is_deleted=1 WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($ripple_num));
    }

    if ($SelectWork == "insert_ripple") {
        $ripple_content = $_REQUEST['ripple_content'] ?? '';
        $regist_day = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO {$DB}.eworks_ripple (content, author, author_id, parent, regist_day) VALUES (?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($ripple_content, $user_name, $user_id, $e_num, $regist_day));
        
        $last_id = $pdo->lastInsertId();
        $ripple_data = getRippleData($last_id, $pdo, $DB);

        echo json_encode($ripple_data, JSON_UNESCAPED_UNICODE);
    }

    if ($SelectWork == "deldata") {   
        // data 삭제시 변경 영구삭제가 아닌 소프트삭제 DB남기고 check로 구분    
        $sql = "UPDATE {$DB}.eworks SET is_deleted=1 WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_num));
    }

    // 전자결재 댓글저장이 아니면 실행
    if ($SelectWork !== "insert_ripple") {
        $data = array('e_num' => $recent_num); 	
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $ex) {
    http_response_code(500);
    error_log("Database error in process.php: " . $ex->getMessage());
    echo json_encode(array('error' => 'Database processing error', 'message' => $ex->getMessage()), JSON_UNESCAPED_UNICODE);
}

?>

