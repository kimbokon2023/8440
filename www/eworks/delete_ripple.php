<?php    
    $page=$_REQUEST["page"];
    $ripple_num=$_REQUEST["ripple_num"];    
	$tablename=$_REQUEST["tablename"];   //tablename 이름
	
    require_once("../lib/mydb.php");
    $pdo = db_connect();
        
     try{
       $pdo->beginTransaction();
       $sql = "delete from mirae8440.eworks_ripple where num = ?";  //db만 수정 
       $stmh = $pdo->prepare($sql);
       $stmh->bindValue(1,$ripple_num,PDO::PARAM_STR);
       $stmh->execute();   
       $pdo->commit();       
         
       } catch (Exception $ex) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
       }
  ?>
