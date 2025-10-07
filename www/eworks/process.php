<?php

include getDocumentRoot() . '/common.php';

if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
function getPosition($userId, $conn) {
    $query = "SELECT position FROM mirae8440.member WHERE id = ?"; // Assuming 'id' is the field for user ID and 'position' for the job title
    $position = '';

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $userId); // 's' is used for string type
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $position = $row['position']; // Assuming 'position' is the field that contains the job title
        }
        mysqli_stmt_close($stmt);
    }
    return $position; // Returns the position as a string
}

 
 function getRippleData($rippleId, $conn) {
    $query = "SELECT * FROM mirae8440.eworks_ripple WHERE num = ?";
    $rippleData = array();

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $rippleId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Assuming these are the fields in your e_works_ripple table
            $rippleData = [
                'num' => $row['num'],
                'content' => $row['content'],
                'author_id' => $row['author_id'],
                'author' => $row['author'], 
                'regist_day' => $row['regist_day'] ,               
                'parent' => $row['parent']                
            ];
        }
        mysqli_stmt_close($stmt);
    }
    return $rippleData; // Returns an associative array with ripple data
}


$e_num=$_REQUEST["e_num"] ?? $_REQUEST["num"] ;

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

require_once("eworksmydb.php");

// MySQL 연결 오류 발생 시 스크립트 종료
if (mysqli_connect_errno()) {
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}

include "_request.php";

if($status==null) $status = 'draft' ;   // 최초 작성으로 설정함

$date = date('Y-m-d H:i:s'); // 현재 시간

if($SelectWork=="update")  {	
	
$query = "UPDATE mirae8440.eworks SET eworks_item='$eworks_item', e_title='$e_title', contents='$contents', registdate='$date', status='$status', e_line='$e_line', e_line_id='$e_line_id', e_confirm='$e_confirm',  e_confirm_id='$e_confirm_id', r_line='$r_line', r_line_id = '$r_line_id', recordtime='$recordtime', author='$author', author_id='$author_id' WHERE num=$e_num";
$result = mysqli_query($conn, $query);
		
}   // end of $SelectWork if-statement
			
if( $SelectWork=="insert")  {	 // 선택에 따라 index로 또는 list로 분기한다. $num이 Null일때	

  // 데이터베이스에 새로운 문서 추가
  $query = "INSERT INTO mirae8440.eworks (eworks_item, e_title,contents,registdate,status,e_line,e_line_id,e_confirm,e_confirm_id,r_line,r_line_id,recordtime, author, author_id)
            VALUES ('$eworks_item', '$e_title', '$contents', '$registdate', '$status', '$e_line','$e_line_id', '$e_confirm', '$e_confirm_id', '$r_line','$r_line_id', '$recordtime', '$author' , '$author_id' )";
  $result = mysqli_query($conn, $query);
  
// 마지막 num 추출하기
    $query = "SELECT num FROM mirae8440.eworks ORDER BY num DESC LIMIT 1";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_array($result);
	$recent_num = $row['num'];
  
}   // end of $SelectWork if-statement 


// 결재상신 (작성에서 상신으로 수정)
if($SelectWork=="send")  {   // data 삭제시     
    $status = 'send';
    $query = "UPDATE mirae8440.eworks SET status='$status' WHERE num=$e_num ";
	$result = mysqli_query($conn, $query);
	
}

// 결재 승인
if ($SelectWork == "approval") {
	
    $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $conn) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $conn) . " ". $date;
    $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

    // 데이터 이스케이핑 및 쿼리 준비
    $e_num = mysqli_real_escape_string($conn, $e_num);
    $query = $conn->prepare("UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?");
    $query->bind_param("ssi", $e_confirm_value, $e_confirm_id_value, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // 결재상태 확인 및 업데이트
    $arr = explode("!", $e_line_id);
    $approval_time = explode("!", $e_confirm_id_value); // 최신 e_confirm_id 사용
    $e_line_count = count($arr);
    $e_confirm_count = count($approval_time);

    if ($e_line_count > $e_confirm_count) {
        // 아직 모든 결재자가 결재하지 않았으므로 '진행중'
        $status = 'ing';
    } else if ($e_line_count == $e_confirm_count) {
        // 모든 결재자가 결재를 완료했으므로 '결재완료'
        $status = 'end';
        $done = 'done';
        $query = $conn->prepare("UPDATE mirae8440.eworks SET done=? WHERE num=?");
        $query->bind_param("si", $done, $e_num);
        $query->execute();
    }

    // status 업데이트
    $query = $conn->prepare("UPDATE mirae8440.eworks SET status=? WHERE num=?");
    $query->bind_param("si", $status, $e_num);
    $query->execute();
}
// 복구
if ($SelectWork == "restore") {
    // e_viewexcept_id에서 user_id를 제거
    $idArray = explode('!', $e_viewexcept_id);
    if (($key = array_search($user_id, $idArray)) !== false) {
        unset($idArray[$key]);
    }
    $e_viewexcept_id = implode('!', $idArray);

    // 데이터 이스케이핑 및 쿼리 준비
    $e_num = mysqli_real_escape_string($conn, $e_num);
    $query = $conn->prepare("UPDATE mirae8440.eworks SET e_viewexcept_id=? WHERE num=?");
    $query->bind_param("si", $e_viewexcept_id, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}

// viewexcept 처리 본인에게 보이지 않게 하는 메뉴
if ($SelectWork == "except") {    
    $e_viewexcept_id = ($e_viewexcept_id === '' || $e_viewexcept_id === null) ? $user_id : $e_viewexcept_id . '!' . $user_id;

    // 데이터 이스케이핑 및 쿼리 준비
    $e_num = mysqli_real_escape_string($conn, $e_num);
    $query = $conn->prepare("UPDATE mirae8440.eworks SET e_viewexcept_id=? WHERE num=?");
    $query->bind_param("si", $e_viewexcept_id, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}

// 결재 회수
if ($SelectWork == "recall") {
    $status = 'draft';
    $e_num = mysqli_real_escape_string($conn, $e_num); // 데이터 이스케이핑
    $query = $conn->prepare("UPDATE mirae8440.eworks SET status=? WHERE num=?");
    $query->bind_param("si", $status, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }	

}

// 결재 거절
if ($SelectWork == "reject") {
    $status = 'reject';
    $e_num = mysqli_real_escape_string($conn, $e_num); // 데이터 이스케이핑
    $query = $conn->prepare("UPDATE mirae8440.eworks SET status=? WHERE num=?");
    $query->bind_param("si", $status, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // e_confirm과 e_confirm_id 업데이트
        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $conn) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $conn) . " ". $date;
    $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

    $query = $conn->prepare("UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?");
    $query->bind_param("ssi", $e_confirm_value, $e_confirm_id_value, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}



// 결재 보류
if ($SelectWork == "wait") {
    $status = 'wait';
    $e_num = mysqli_real_escape_string($conn, $e_num); // 데이터 이스케이핑
    $query = $conn->prepare("UPDATE mirae8440.eworks SET status=? WHERE num=?");
    $query->bind_param("si", $status, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // e_confirm과 e_confirm_id 업데이트
	$e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $conn) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $conn) . " ". $date;
    $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

    $query = $conn->prepare("UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?");
    $query->bind_param("ssi", $e_confirm_value, $e_confirm_id_value, $e_num);
    $result = $query->execute();

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}


if($SelectWork=="delete_ripple")  {   // data 삭제시 변경 영구삭제가 아닌 소프트삭제 DB남기고 check로 구분    
	
    $query = "UPDATE mirae8440.eworks_ripple SET is_deleted=1 WHERE num=$ripple_num ";
	$result = mysqli_query($conn, $query);		
	
}

if ($SelectWork == "insert_ripple") {
    
    // Escape input data to prevent SQL injection
    $ripple_content = mysqli_real_escape_string($conn, $_REQUEST['ripple_content']);
    $ripple_author = mysqli_real_escape_string($conn, $user_name); 
    $ripple_author_id = mysqli_real_escape_string($conn, $user_id); 
    $parent_id = mysqli_real_escape_string($conn, $e_num);

    // Get the current date and time
    $regist_day = date('Y-m-d H:i:s'); // Current date and time in MySQL datetime format

    // Construct and execute the SQL query
    $query = "INSERT INTO mirae8440.eworks_ripple (content, author, author_id, parent, regist_day) VALUES ('$ripple_content', '$ripple_author', '$ripple_author_id', '$parent_id', '$regist_day')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Fetch the last inserted ripple id
	$last_id = mysqli_insert_id($conn);
	$ripple_data = getRippleData($last_id, $conn); // Passing the ripple ID and the database connection

    // Return the new ripple data as JSON
    echo json_encode($ripple_data, JSON_UNESCAPED_UNICODE);
}



if($SelectWork=="deldata")  {   // data 삭제시 변경 영구삭제가 아닌 소프트삭제 DB남기고 check로 구분    
    
    $query = "UPDATE mirae8440.eworks SET is_deleted=1 WHERE num=$e_num ";
	$result = mysqli_query($conn, $query);		
	
}


// 전자결재 댓글저장이 아니면 실행
if($SelectWork!=="insert_ripple")  {
	$data = [ 'e_num' => $recent_num 			 ]; 	
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
}


?>

