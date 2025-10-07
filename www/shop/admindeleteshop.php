<?php   
session_start();
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["ordernum"])  ? $ordernum=$_REQUEST["ordernum"] :  $ordernum=''; 
isset($_REQUEST["option"])  ? $option=$_REQUEST["option"] :  $option=''; 

// 복구일때는 0 아닐때는 1 적용함
if($option=='recovery')
	$optionvalue = 0;
   else
		$optionvalue = 1;
	 
    require_once("../lib/mydb.php");
   $pdo = db_connect(); 
 
   try{
     $pdo->beginTransaction();
     $sql = "update mirae8440.shop set delvalue=? where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$optionvalue,PDO::PARAM_STR);      
     $stmh->bindValue(2,$ordernum,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
	 
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
   
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>        	 $ordernum,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));
   
?>