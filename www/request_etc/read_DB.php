<?php
require_once __DIR__ . '/../common/functions.php';
?>
 <?php
 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.eworks order by num desc limit 1";
     $stmh = $pdo->prepare($sql);  
     $stmh->execute();                  
     $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
     $num=$row["num"];
	 
	print "마지막 레코드 번호 : " . $num;		 
	
	}
   catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
  header("Location:http://8440.co.kr/request_etc/view.php?num=$num");  
 ?>  
	
