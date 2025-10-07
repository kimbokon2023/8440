<?php   
session_start();   

$level= $_SESSION["level"];
$user_name= $_SESSION["name"];
$id= $_SESSION["userid"];

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode=""; 
isset($_REQUEST["num"])  ? $num = $_REQUEST["num"] : $num=""; 
			  
require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열로 기본정보 불러옴
 include "load_DB.php";
 
// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기 
// 2022년  2023년 등 자료의 유일한 값을 위주로 대량생산함 array_unique
for($i=0;$i<count(array_unique($basic_name_arr));$i++)  
{	
$id=$id_arr[$i];
$name=$basic_name_arr[$i];
$part=$part_arr[$i];
$registdate=$_REQUEST["registdate"];
$item=$_REQUEST["item"];
$askdatefrom=$_REQUEST["askdatefrom"];
$askdateto=$_REQUEST["askdateto"];
$usedday=$_REQUEST["usedday"];
$content=$_REQUEST["content"];
$state='처리완료';  // 강제처리완료 처리함			  
   
 if ($mode=="insert"){	 	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.al(id , name , registdate , item , askdatefrom ,  askdateto ,  usedday ,  content ,  state, part ) ";     
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
	   
    
     $stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $id, PDO::PARAM_STR);  
	$stmh->bindValue(2, $name, PDO::PARAM_STR);  
	$stmh->bindValue(3, $registdate, PDO::PARAM_STR);  
	$stmh->bindValue(4, $item, PDO::PARAM_STR);  
	$stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);  
    $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);      
    $stmh->bindValue(7, $usedday, PDO::PARAM_STR);        
    $stmh->bindValue(8, $content, PDO::PARAM_STR);        
    $stmh->bindValue(9, $state, PDO::PARAM_STR);     
    $stmh->bindValue(10, $part, PDO::PARAM_STR);     
	 
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
		"registdate" =>  $registdate,
		"state" =>  $state,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
   
 ?>