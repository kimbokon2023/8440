<?php
require_once __DIR__ . '/../bootstrap.php';

// Return JSON for AJAX requests
header('Content-Type: application/json');

function getPosition($userId, $pdo) {
    $query = "SELECT position FROM mirae8440.member WHERE id = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $userId, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['position'] : '';
    } catch (PDOException $e) {
        // error_log("getPosition Error: " . $e->getMessage());
        return '';
    }
}
 
 function getRippleData($rippleId, $pdo) {
    $query = "SELECT * FROM mirae8440.eworks_ripple WHERE num = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $rippleId, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        return $row ?: [];
    } catch (PDOException $e) {
        // error_log("getRippleData Error: " . $e->getMessage());
        return [];
    }
}


$e_num = $_REQUEST["e_num"] ?? $_REQUEST["num"] ?? null;

isset($_REQUEST["ripple_num"])  ? $ripple_num=$_REQUEST["ripple_num"] :   $ripple_num=''; 
isset($_REQUEST["SelectWork"])  ? $SelectWork = $_REQUEST["SelectWork"] :   $SelectWork="insert"; 
isset($_REQUEST["e_line"])  ? $e_line = $_REQUEST["e_line"] :   $e_line=""; 
isset($_REQUEST["e_line_id"])  ? $e_line_id = $_REQUEST["e_line_id"] :   $e_line_id=""; 
isset($_REQUEST["e_confirm"])  ? $e_confirm = $_REQUEST["e_confirm"] :   $e_confirm=""; 
isset($_REQUEST["eworks_item"])  ? $eworks_item = $_REQUEST["eworks_item"] :   $eworks_item=""; 
isset($_REQUEST["author"])  ? $author = $_REQUEST["author"] :   $author=""; 		
isset($_REQUEST["author_id"])  ? $author_id = $_REQUEST["author_id"] :   $author_id=""; 	
$recent_num = $e_num;  // 마지막 번호 임시 저장

$arr = explode("!",$e_line_id);		
$e_line_count = count($arr);
// 결재시간 추출해서 조합하기
$approval_time = explode("!",$e_confirm);	
$e_confirm_count = count($approval_time);

include "_request.php";

if($status==null) $status = 'draft' ;   // 최초 작성으로 설정함

$date = date('Y-m-d H:i:s'); // 현재 시간

try {
    if($SelectWork=="update")  {	
        $sql = "UPDATE mirae8440.eworks SET eworks_item=?, e_title=?, contents=?, registdate=?, status=?, e_line=?, e_line_id=?, e_confirm=?,  e_confirm_id=?, r_line=?, r_line_id = ?, recordtime=?, author=?, author_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$eworks_item, $e_title, $contents, $date, $status, $e_line, $e_line_id, $e_confirm,  $e_confirm_id, $r_line, $r_line_id, $recordtime, $author, $author_id, $e_num]);
    }   // end of $SelectWork if-statement
                
    if( $SelectWork=="insert")  {	 // 선택에 따라 index로 또는 list로 분기한다. $num이 Null일때	
      // 데이터베이스에 새로운 문서 추가
      $sql = "INSERT INTO mirae8440.eworks (eworks_item, e_title,contents,registdate,status,e_line,e_line_id,e_confirm,e_confirm_id,r_line,r_line_id,recordtime, author, author_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmh = $pdo->prepare($sql);
      $stmh->execute([$eworks_item, $e_title, $contents, $registdate, $status, $e_line, $e_line_id, $e_confirm, $e_confirm_id, $r_line, $r_line_id, $recordtime, $author, $author_id]);
      $recent_num = $pdo->lastInsertId();
    }   // end of $SelectWork if-statement 


    // 결재상신 (작성에서 상신으로 수정)
    if($SelectWork=="send")  {   // data 삭제시     
        $status = 'send';
        $sql = "UPDATE mirae8440.eworks SET status=? WHERE num=? ";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$status, $e_num]);
    }

    // 결재 승인
    if ($SelectWork == "approval") {
        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo) . " ". $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

        $sql = "UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$e_confirm_value, $e_confirm_id_value, $e_num]);
        
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
            $sql_done = "UPDATE mirae8440.eworks SET done=? WHERE num=?";
            $stmh_done = $pdo->prepare($sql_done);
            $stmh_done->execute([$done, $e_num]);
        }

        // status 업데이트
        $sql_status = "UPDATE mirae8440.eworks SET status=? WHERE num=?";
        $stmh_status = $pdo->prepare($sql_status);
        $stmh_status->execute([$status, $e_num]);
    }
    // 복구
    if ($SelectWork == "restore") {
        $idArray = explode('!', $e_viewexcept_id);
        if (($key = array_search($user_id, $idArray)) !== false) {
            unset($idArray[$key]);
        }
        $e_viewexcept_id_new = implode('!', $idArray);

        $sql = "UPDATE mirae8440.eworks SET e_viewexcept_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$e_viewexcept_id_new, $e_num]);
    }

    // viewexcept 처리 본인에게 보이지 않게 하는 메뉴
    if ($SelectWork == "except") {    
        $e_viewexcept_id_new = ($e_viewexcept_id === '' || $e_viewexcept_id === null) ? $user_id : $e_viewexcept_id . '!' . $user_id;
        
        $sql = "UPDATE mirae8440.eworks SET e_viewexcept_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$e_viewexcept_id_new, $e_num]);
    }

    // 결재 회수
    if ($SelectWork == "recall") {
        $status = 'draft';
        $sql = "UPDATE mirae8440.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$status, $e_num]);
    }

    // 결재 거절
    if ($SelectWork == "reject") {
        $status = 'reject';
        $sql = "UPDATE mirae8440.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$status, $e_num]);

        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo) . " ". $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

        $sql_confirm = "UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh_confirm = $pdo->prepare($sql_confirm);
        $stmh_confirm->execute([$e_confirm_value, $e_confirm_id_value, $e_num]);
    }

    // 결재 보류
    if ($SelectWork == "wait") {
        $status = 'wait';
        $sql = "UPDATE mirae8440.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$status, $e_num]);

        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo) . " ". $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;
        
        $sql_confirm = "UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh_confirm = $pdo->prepare($sql_confirm);
        $stmh_confirm->execute([$e_confirm_value, $e_confirm_id_value, $e_num]);
    }

    if($SelectWork=="delete_ripple")  {
        $sql = "UPDATE mirae8440.eworks_ripple SET is_deleted=1 WHERE num=? ";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$ripple_num]);
    }

    if ($SelectWork == "insert_ripple") {
        $ripple_content = $_REQUEST['ripple_content'];
        $regist_day = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO mirae8440.eworks_ripple (content, author, author_id, parent, regist_day) VALUES (?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$ripple_content, $user_name, $user_id, $e_num, $regist_day]);
        
        $last_id = $pdo->lastInsertId();
        $ripple_data = getRippleData($last_id, $pdo);

        echo json_encode($ripple_data, JSON_UNESCAPED_UNICODE);
    }

    if($SelectWork=="deldata")  {   // data 삭제시 변경 영구삭제가 아닌 소프트삭제 DB남기고 check로 구분    
        $sql = "UPDATE mirae8440.eworks SET is_deleted=1 WHERE num=? ";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$e_num]);
    }

    // 전자결재 댓글저장이 아니면 실행
    if($SelectWork!=="insert_ripple")  {
        $data = [ 'e_num' => $recent_num ]; 	
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database processing error', 'message' => $e->getMessage()]);
}

?>

