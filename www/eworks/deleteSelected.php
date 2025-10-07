<?php

if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  
 
isset($_REQUEST["e_num"])  ? $e_num=$_REQUEST["e_num"] :   $e_num=''; 
isset($_REQUEST["ripple_num"])  ? $ripple_num=$_REQUEST["ripple_num"] :   $ripple_num=''; 
isset($_REQUEST["SelectWork"])  ? $SelectWork = $_REQUEST["SelectWork"] :   $SelectWork=""; 
isset($_REQUEST["e_line"])  ? $e_line = $_REQUEST["e_line"] :   $e_line=""; 
isset($_REQUEST["e_line_id"])  ? $e_line_id = $_REQUEST["e_line_id"] :   $e_line_id=""; 
isset($_REQUEST["e_confirm"])  ? $e_confirm = $_REQUEST["e_confirm"] :   $e_confirm=""; 
isset($_REQUEST["eworks_item"])  ? $eworks_item = $_REQUEST["eworks_item"] :   $eworks_item=""; 
isset($_REQUEST["author"])  ? $author = $_REQUEST["author"] :   $author=""; 		
isset($_REQUEST["author_id"])  ? $author_id = $_REQUEST["author_id"] :   $author_id=""; 	

// 여러 e_num 값을 배열로 받아옵니다.
$e_nums = isset($_REQUEST["selectedIds"]) ? $_REQUEST["selectedIds"] : [];

require_once("eworksmydb.php");

// MySQL 연결 오류 발생 시 스크립트 종료
if (mysqli_connect_errno()) {
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}

include "_request.php";

// viewexcept 처리 본인에게 보이지 않게 하는 메뉴
// '제외' 작업을 처리합니다.
foreach ($e_nums as $e_num) {
        // 데이터 이스케이핑 및 쿼리 준비
			$e_viewexcept_id = ($e_viewexcept_id === '' || $e_viewexcept_id === null) ? $user_id : $e_viewexcept_id . '!' . $user_id;

			// 데이터 이스케이핑 및 쿼리 준비
			$e_num = mysqli_real_escape_string($conn, $e_num);
			$query = $conn->prepare("UPDATE mirae8440.eworks SET e_viewexcept_id=? WHERE num=?");
			$query->bind_param("si", $e_viewexcept_id, $e_num);
			$result = $query->execute();
			$result = $query->execute();

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
}


//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
    "num" =>  $e_num, // 이 부분은 마지막 처리된 e_num만 반영될 것입니다.
    "SelectWork" =>  $SelectWork,
    "selectedIds" => $e_nums, // 여기서 $selectedIds 대신 $e_nums를 사용합니다.
);

//json 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>

