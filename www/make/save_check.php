 <?php

 $num=$_REQUEST["num"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $left_check=$_REQUEST["left_check"];  
 $right_check=$_REQUEST["right_check"];  
 $mid_check=$_REQUEST["mid_check"];  
 $done_check=$_REQUEST["done_check"];   
 $remain_check=$_REQUEST["remain_check"];   


// print "<script> alert($modify); </script>";

 require_once("../lib/mydb.php");
  $pdo = db_connect();
     try{
        $sql = "select * from mirae8440.make where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
	  
try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.make set left_check=?, mid_check=?, right_check=?, done_check=?, remain_check=?   ";
        $sql .= " where num=?  LIMIT 1"; 
 
	   
     $stmh = $pdo->prepare($sql); 

     $stmh->bindValue(1, $left_check, PDO::PARAM_STR);           	 
     $stmh->bindValue(2, $mid_check, PDO::PARAM_STR);           	 
     $stmh->bindValue(3, $right_check, PDO::PARAM_STR);           	 
     $stmh->bindValue(4, $done_check, PDO::PARAM_STR);           	 
     $stmh->bindValue(5, $remain_check, PDO::PARAM_STR);           	 
     $stmh->bindValue(6, $num, PDO::PARAM_STR);           	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
	 
?>