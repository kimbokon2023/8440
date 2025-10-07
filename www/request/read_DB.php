 <?php
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.eworks order by num desc limit 1";
     $stmh = $pdo->prepare($sql);  
     $stmh->execute();                  
     $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
     $num=$row["num"];
	 	
	}
   catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
  header("Location:http://8440.co.kr/request/view.php?num=$num");  
 ?>  
	
