<?php
    $num=$_REQUEST["num"];
    $page=$_REQUEST["page"];
    $ripple_num=$_REQUEST["ripple_num"];    
    require_once("../lib/mydb.php");
    $pdo = db_connect();
        
     try{
       $pdo->beginTransaction();
       $sql = "delete from mirae8440.voc_ripple where num = ?";  //db만 수정 
       $stmh = $pdo->prepare($sql);
       $stmh->bindValue(1,$ripple_num,PDO::PARAM_STR);
       $stmh->execute();   
       $pdo->commit();
                
        header("Location:http://8440.co.kr/work_voc/view.php?num=$num&page=$page");
       } catch (Exception $ex) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
       }
  ?>
