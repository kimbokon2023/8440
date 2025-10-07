<?php include $_SERVER['DOCUMENT_ROOT'] . '/session.php';   
   
if(!isset($_SESSION["level"]) || $level>=8) {
	 echo "<script> alert('관리자 승인이 필요합니다.') </script>";
	 sleep(2);
	 header ("Location:http://8440.co.kr/login/logout.php");
	 exit;
}

$num=$_REQUEST["num"] ?? '';

print $num;
	 
require_once("../lib/mydb.php");
$pdo = db_connect();

   try{
     $pdo->beginTransaction();
     $sql = "delete from mirae8440.automan where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
 
     header("Location:https://8440.co.kr/automan/list.php");
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
?>