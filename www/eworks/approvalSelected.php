<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  
 
include getDocumentRoot() . '/common.php';

$date = date('Y-m-d H:i:s'); // 현재 시간

$e_nums = isset($_REQUEST["selectedIds"]) ? $_REQUEST["selectedIds"] : [];

require_once("eworksmydb.php");

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

function getEConfirmValues($e_num, $conn) {
    $query = "SELECT e_confirm, e_confirm_id, e_line_id FROM mirae8440.eworks WHERE num = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $e_num);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $values = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $values;
}

foreach ($e_nums as $e_num) {
    $confirmValues = getEConfirmValues($e_num, $conn);
    $e_confirm = $confirmValues['e_confirm'];
    $e_confirm_id = $confirmValues['e_confirm_id'];
    $e_line_id = $confirmValues['e_line_id'];    

    $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $conn) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $conn) . " ". $date;
    $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;
	

	// 결재상태 확인 및 업데이트
	$e_line_id_count = count(explode("!", $e_line_id));
	$e_confirm_count = count(explode("!", $e_confirm_id_value));	
	$status = 'ing';	
	// $done = "(" . $e_confirm_count . ") e_confirm_count " . $e_line_id .  " + e_line_count(" . $e_line_count .")" . $e_confirm_id_value ;	
	$done = null;
	
	if ($e_line_id_count == $e_confirm_count) {
		// 모든 결재자가 결재를 완료했으므로 '결재완료'
		$status = 'end';
		$done = 'done';
	}

    // 데이터 이스케이핑 및 쿼리 준비
    $e_num = mysqli_real_escape_string($conn, $e_num);
    $query = $conn->prepare("UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=?, done=? , status=?  WHERE num=?");
    $query->bind_param("ssssi", $e_confirm_value, $e_confirm_id_value, $done, $status,  $e_num);
    $result = $query->execute();
	if (!$result) {
		die("Query failed: " . mysqli_error($conn));
	}	
}


//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
    "num" =>  $e_num, // 이 부분은 마지막 처리된 e_num만 반영될 것입니다.    
);

//json 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>

