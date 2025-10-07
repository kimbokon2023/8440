<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
isset($_REQUEST["filename1"])  ? $filename1=$_REQUEST["filename1"] :   $filename1=''; 
isset($_REQUEST["filename2"])  ? $filename2=$_REQUEST["filename2"] :   $filename2=''; 
isset($_REQUEST["copyfilename"])  ? $copyfilename=$_REQUEST["copyfilename"] :   $copyfilename=''; 

require_once("../lib/mydb.php");	
$pdo = db_connect();
if($copyfilename=='copy')
{
		if($filename1=='')
			$filename1 = $filename2;
		if($filename2=='')
			$filename2 = $filename1;
}
	else
	{
		if($filename1=='')
			$filename1 = 'pass';
		if($filename2=='')
			$filename2 = 'pass';
	}
	
 try{		 
		$pdo->beginTransaction();   
		$sql = "update mirae8440.work set ";
		$sql .="filename1=?, filename2=?  where num=? LIMIT 1" ;       
		   
		 $stmh = $pdo->prepare($sql); 

   	     $stmh->bindValue(1, $filename1, PDO::PARAM_STR);    
	   
		 $stmh->bindValue(2, $filename2, PDO::PARAM_STR);	 
		 $stmh->bindValue(3, $num, PDO::PARAM_STR);	 
		 $stmh->execute();
		 $pdo->commit(); 
			} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
		   } 	

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num_arr" =>         $num_tmp,
		"recordDate_arr" =>         $date_tmp,
		"workchoice" =>         $workchoice,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>