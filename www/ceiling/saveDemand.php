<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["num_arr"])  ? $num_arr=$_REQUEST["num_arr"] :   $num_arr=''; 
isset($_REQUEST["recordDate_arr"])  ? $recordDate_arr=$_REQUEST["recordDate_arr"] :   $recordDate_arr=''; 
// $data = file_get_contents($url); // 파일의 내용을 변수에 넣는다

//print_r($num_arr[0]);
$num_tmp = explode(",",$num_arr[0]);
$date_tmp = explode(",",$recordDate_arr[0]);
//print_r(count($tmp));
//print_r($num_tmp);

require_once("../lib/mydb.php");	
$pdo = db_connect();

for($i=0;$i<count($num_tmp);$i++) {	
	 try{		 
    $pdo->beginTransaction();   
    $sql = "update mirae8440.ceiling set ";
    $sql .="demand=? where num=? LIMIT 1" ;       
	   
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
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num_arr" =>         $num_tmp,
		"recordDate_arr" =>         $date_tmp,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>