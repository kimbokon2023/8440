<?php   
 
session_start(); 
  
 $level= $_SESSION["level"];
   
   $num=$_REQUEST["num"];
   $page=$_REQUEST["page"];
         
    require_once("../lib/mydb.php");
   $pdo = db_connect();

   try{
     $pdo->beginTransaction();
     $sql = "delete from mirae8440.cost where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
 
     header("Location:http://8440.co.kr/cost/list.php");
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
?>