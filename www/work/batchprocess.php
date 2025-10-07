<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

function checkInput($input) {
    if(is_null($input) || trim($input) === '') {
        return false;
    } else {
        return true;
    }
}

isset($_REQUEST["num_arr"])  ? $num_arr=$_REQUEST["num_arr"] :   $num_arr=''; 
isset($_REQUEST["recordDate_arr"])  ? $recordDate_arr=$_REQUEST["recordDate_arr"] :   $recordDate_arr=''; 
isset($_REQUEST["workchoice"])  ? $workchoice=$_REQUEST["workchoice"] :   $workchoice=''; 
isset($_REQUEST["image1_arr"])  ? $image1_arr=$_REQUEST["image1_arr"] :   $image1_arr=''; 
isset($_REQUEST["image2_arr"])  ? $image2_arr=$_REQUEST["image2_arr"] :   $image2_arr=''; 

$num_tmp = explode(",",$num_arr[0]);
$date_tmp = explode(",",$recordDate_arr[0]);
$image1 = explode(",",$image1_arr[0]);
$image2 = explode(",",$image2_arr[0]);

require_once("../lib/mydb.php");	
$pdo = db_connect();

if($workchoice == 'measureday')
{
	for($i=0;$i<count($num_tmp);$i++) {	
		 try{		 
		$pdo->beginTransaction();   
		$sql = "update mirae8440.work set ";
		$sql .="measureday=? where num=? LIMIT 1" ;       
		   
		 $stmh = $pdo->prepare($sql); 

   	     $stmh->bindValue(1, $date_tmp[$i], PDO::PARAM_STR);    
	   
		 $stmh->bindValue(2, $num_tmp[$i], PDO::PARAM_STR);	 
		 $stmh->execute();
		 $pdo->commit(); 
			} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
		   } 
	}
}
if($workchoice == 'demandday')
{
	for($i=0;$i<count($num_tmp);$i++) {	
		 try{		 
		$pdo->beginTransaction();   
		$sql = "update mirae8440.work set ";
		$sql .="demand=? where num=? LIMIT 1" ;       
		   
		 $stmh = $pdo->prepare($sql); 

   	     $stmh->bindValue(1, $date_tmp[$i], PDO::PARAM_STR);    
	   
		 $stmh->bindValue(2, $num_tmp[$i], PDO::PARAM_STR);	 
		 $stmh->execute();
		 $pdo->commit(); 
			} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
		   } 
	}
}

if($workchoice == 'doneday')
{
	for($i=0;$i<count($num_tmp);$i++) {	
		 try{		 
		$pdo->beginTransaction();   
		$sql = "update mirae8440.work set ";
		$sql .="filename1=?, filename2=?, doneday=?  where num=? LIMIT 1" ;       
		   
		 $stmh = $pdo->prepare($sql); 
		 
		 if(!checkInput($image1[$i]))
			 $image1_result = 'pass';
		   else
			$image1_result = $image1[$i];
		
		 if(!checkInput($image2[$i]))
			 $image2_result = 'pass';
		   else
			$image2_result = $image2[$i];

   	     $stmh->bindValue(1, $image1_result, PDO::PARAM_STR);    
   	     $stmh->bindValue(2, $image2_result, PDO::PARAM_STR);      
   	     $stmh->bindValue(3, $date_tmp[$i], PDO::PARAM_STR);    
	   
		 $stmh->bindValue(4, $num_tmp[$i], PDO::PARAM_STR);	 
		 $stmh->execute();
		 $pdo->commit(); 
			} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
		   } 
	}
}
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num_arr" =>         $num_tmp,
		"recordDate_arr" =>   $date_tmp,
		"image1 " =>         $image1,
		"image2 " =>         $image2,
		"workchoice" =>         $workchoice,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>