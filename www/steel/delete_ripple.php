<?php
// Environment detection
$isLocal = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
           strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
           strpos($_SERVER['HTTP_HOST'], '::1') !== false;
$baseUrl = $isLocal ? 'http://localhost:5130' : 'http://5130.co.kr';

// Initialize request variables with null safety
$num = $_REQUEST["num"] ?? null;
$page = $_REQUEST["page"] ?? null;
$ripple_num = $_REQUEST["ripple_num"] ?? null;    
    require_once("../lib/mydb.php");
    $pdo = db_connect();
        
     try{
       $pdo->beginTransaction();
       $sql = "delete from chandj.work_ripple where num = ?";  //db만 수정 
       $stmh = $pdo->prepare($sql);
       $stmh->bindValue(1,$ripple_num,PDO::PARAM_STR);
       $stmh->execute();   
       $pdo->commit();
                
        header("Location:" . $baseUrl . "/as/view.php?num=$num&page=$page");
       } catch (Exception $ex) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
       }
  ?>
