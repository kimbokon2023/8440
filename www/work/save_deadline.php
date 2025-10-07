<?php

session_start();

	header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

	isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
	
	// $log=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	// $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea
	$deadline= date("Y-m-d"); 

	require_once("../lib/mydb.php");	
	$pdo = db_connect();

		try{		 
			$pdo->beginTransaction();   
			$sql = "update mirae8440.work set ";
			$sql .="deadline=? where num=? LIMIT 1" ;       
			   
			 $stmh = $pdo->prepare($sql); 

			 $stmh->bindValue(1, $deadline, PDO::PARAM_STR);    
		   
			 $stmh->bindValue(2, $num , PDO::PARAM_STR);	 
			 $stmh->execute();
			 $pdo->commit(); 
				} catch (PDOException $Exception) {
				   $pdo->rollBack();
				   print "오류: ".$Exception->getMessage();
			   } 
	

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>         $num,
		"deadline" =>         $deadline
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>