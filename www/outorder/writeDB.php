<?php
session_start(); 
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  
 
$num=$_REQUEST["num"];
if(isset($_REQUEST["strtmp"]))
  {
  // confirm에 내용을 넣고 저장할시 사용함
  $confirm=$_REQUEST["strtmp"];
  }
  else
  {	  
	// 확인완료 'y' 저장
    $confirm = date("Y-m-d");
  }
  
 
 require_once("../lib/mydb.php");
 $pdo = db_connect(); 
 
 try{
     $sql = "select * from mirae8440.outorder where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
     $update_log=$row["update_log"];
	
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
 
    $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea   	
 
 	 try{		 
    $pdo->beginTransaction();   
    $sql = "update mirae8440.outorder set ";
    $sql .="update_log=?, confirm=? "; 	
	$sql .= " where num=? LIMIT 1" ;         
	   
	$stmh = $pdo->prepare($sql); 

	$stmh->bindValue(1, $update_log, PDO::PARAM_STR);	 
	$stmh->bindValue(2, $confirm, PDO::PARAM_STR);	 
	$stmh->bindValue(3, $num, PDO::PARAM_STR);	 
    $stmh->execute();
    $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }      
	   

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
   	   
	   
?>