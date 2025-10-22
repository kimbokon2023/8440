<?php
session_start();

// Environment detection for URL
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$base_url = $is_local ? 'http://localhost' : 'http://8440.co.kr';

// Initialize variables with null safety
$level = $_SESSION["level"] ?? null;

if (!isset($_SESSION["level"]) || $level >= 8) {
    echo "<script> alert('관리자 승인이 필요합니다.') </script>";
    sleep(2);
    header("Location: {$base_url}/login/logout.php");
    exit;
}
   
// Request variables with null safety
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
         
    require_once("../lib/mydb.php");
   $pdo = db_connect();

   try{
     $pdo->beginTransaction();
     $sql = "delete from mirae8440.shopitem where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
 
     header("Location: {$base_url}/shop/list.php");
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
?>