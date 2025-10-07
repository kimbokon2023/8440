 <?php
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.cost order by num desc limit 1";
     $stmh = $pdo->prepare($sql);  
     $stmh->execute();                  
     $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
     $num=$row["num"];
		
	}
   catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
  header("Location:http://8440.co.kr/cost/view.php?num=$num&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>  
	
