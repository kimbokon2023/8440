<?php 

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["e_num"]) ? $e_num = $_REQUEST["e_num"] :   $e_num=""; 

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

 try{
      $sql = "select * from mirae8440.eworks where num='$e_num' and is_deleted IS NULL ";
      $stmh = $pdo->prepare($sql);       
      $stmh->execute();
      $count = $stmh->rowCount();         	  
    if($count<1){  
     // print "검색결과가 없습니다.<br>";
	  $eworks_item = '일반';
     }   else    {      
		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			include getDocumentRoot() . "/eworks/_row.php";		
            if($eworks_item==='연차')
                $contents = urldecode($contents);			
		}
	 }     											  		      										

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
 }
 
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
	"e_num" => $e_num, 
	"eworks_item" => $eworks_item, 
	"e_title" => $e_title, 
	"contents" => $contents, 
	"registdate" => $registdate, 
	"status" => $status, 
	"e_line" => $e_line, 
	"e_line_id" => $e_line_id, 
	"e_confirm" => $e_confirm, 
	"e_confirm_id" => $e_confirm_id, 
	"r_line" => $r_line, 
	"r_line_id" => $r_line_id, 
	"recordtime" => $recordtime, 
	"author" => $author, 	
	"author_id" => $author_id, 	
	"done" => $done 	
);
//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>
