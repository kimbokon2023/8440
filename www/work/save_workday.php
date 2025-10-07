<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["num_arr"])  ? $num_arr=$_REQUEST["num_arr"] :   $num_arr=''; 
isset($_REQUEST["choice"])  ? $choice=$_REQUEST["choice"] :   $choice=''; 
isset($_REQUEST["recordDate_arr"])  ? $recordDate_arr=$_REQUEST["recordDate_arr"] :   $recordDate_arr=''; 
// $data = file_get_contents($url); // 파일의 내용을 변수에 넣는다

//print_r($num_arr[0]);
$num_tmp = explode(",",$num_arr[0]);
$date_tmp = explode(",",$recordDate_arr[0]);

require_once("../lib/mydb.php");	
$pdo = db_connect();

if($choice == 'endworkday')  // 예정일 변경
{		
	for($i=0;$i<count($num_tmp);$i++) {	
		 try{		 
			$pdo->beginTransaction();   
			$sql = "update mirae8440.work set ";
			$sql .="endworkday =? where num=? LIMIT 1" ;       
			   
			 $stmh = $pdo->prepare($sql); 

			 $stmh->bindValue(1, $date_tmp[$i], PDO::PARAM_STR);      // 청구일 기록        
			 $stmh->bindValue(2, $num_tmp[$i], PDO::PARAM_STR);	 
			 $stmh->execute();
			 $pdo->commit(); 
			} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
		   } 
	}	
}
else
{	
	for($i=0;$i<count($num_tmp);$i++) {	
		 try{		 
			$pdo->beginTransaction();   
			$sql = "update mirae8440.work set ";
			$sql .="workday=?, deadline=?  where num=? LIMIT 1" ;       
			   
			 $stmh = $pdo->prepare($sql); 

			 $stmh->bindValue(1, $date_tmp[$i], PDO::PARAM_STR);      // 출고일 기록        
			 $stmh->bindValue(2, $date_tmp[$i], PDO::PARAM_STR);	  // 생산완료일
			 $stmh->bindValue(3, $num_tmp[$i], PDO::PARAM_STR);	 
			 $stmh->execute();
			 $pdo->commit(); 
		} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
	   } 
	}
}

$data = array(
		"num_arr" =>         $num_tmp,
		"recordDate_arr" =>         $date_tmp,
);

echo(json_encode($data, JSON_UNESCAPED_UNICODE));
?>