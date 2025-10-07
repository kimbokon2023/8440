<?php
    $num=$_REQUEST["num"];
    $ripple_num=$_REQUEST["ripple_num"];    
    require_once("../lib/mydb.php");
    $pdo = db_connect();
        
     try{
       $pdo->beginTransaction();
       $sql = "delete from chandj.free_ripple where num = ?";  //db만 수정 
       $stmh = $pdo->prepare($sql);
       $stmh->bindValue(1,$ripple_num,PDO::PARAM_STR);
       $stmh->execute();   
       $pdo->commit();
                
        header("Location:http://5130.co.kr/free/view.php?num=$num");
       } catch (Exception $ex) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
       }
  ?>
