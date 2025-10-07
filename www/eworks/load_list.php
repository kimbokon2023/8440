<?php 

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["e_num"])  ? $e_num = $_REQUEST["e_num"] :   $e_num=""; 

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

$num_arr= array();
$e_title_arr= array();
$contents_arr= array();
$registdate_arr= array();
$status_arr= array();
$e_line_arr= array();
$e_line_id_arr= array();
$e_confirm_arr= array();
$r_line_arr= array();
$r_line_id_arr= array();
$recordtime_arr= array();
$author_arr= array();
$author_id_arr= array();
$done_arr= array();
	
 try{
      $sql = "select * from mirae8440.eworks where is_deleted IS NULL ";
      $stmh = $pdo->prepare($sql);       
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
     // print "검색결과가 없습니다.<br>";
     }   else    {      
		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			include "_row.php";
			array_push($num_arr, $e_num);
			array_push($e_title_arr, $e_title);
			array_push($contents_arr, $contents);
			array_push($registdate_arr, $registdate);
			array_push($status_arr, $status);
			array_push($e_line_arr, $e_line);
			array_push($e_line_id_arr, $e_line_id);
			array_push($e_confirm_arr, $e_confirm);
			array_push($r_line_arr, $r_line);
			array_push($r_line_id_arr, $r_line_id);
			array_push($recordtime_arr, $recordtime);
			array_push($author_arr, $author);
			array_push($author_id_arr, $author_id);
			array_push($done_arr, $done);
		}
	 }    											  		      										

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
 }
 
 
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
	"num_arr" => $num_arr, 
	"e_title_arr" => $e_title_arr, 
	"contents_arr" => $contents_arr, 
	"registdate_arr" => $registdate_arr, 
	"status_arr" => $status_arr, 
	"e_line_arr" => $e_line_arr, 
	"e_line_id_arr" => $e_line_id_arr, 
	"e_confirm_arr" => $e_confirm_arr, 
	"r_line_arr" => $r_line_arr, 
	"r_line_id_arr" => $r_line_id_arr, 
	"recordtime_arr" => $recordtime_arr, 
	"author_arr" => $author_arr, 	
	"author_id_arr" => $author_id_arr, 	
	"done_arr" => $done_arr 	
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>
