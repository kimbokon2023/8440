<?php   
session_start();
 
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num='';
isset($_REQUEST["update_log"])  ? $update_log=$_REQUEST["update_log"] : $update_log='';
 
require_once("../lib/mydb.php");
$pdo = db_connect();
 
// 당일 입고완료처리 
$which='3';

$indate= date("Y-m-d");   // 현재일자 변수지정   	

$today=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
$update_log = $today . $update_log . "&#10";  // 개행문자 Textarea      	
       
	try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.eworks set which=? , indate=?, update_log=?  where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);         
     $stmh->bindValue(2, $indate, PDO::PARAM_STR);         
     $stmh->bindValue(3, $update_log, PDO::PARAM_STR);         
     $stmh->bindValue(4, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }      


//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num ,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));	 
   
    ?>
  