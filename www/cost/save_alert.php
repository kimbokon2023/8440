 <?php

$choice=$_REQUEST["choice"];
$num=$_REQUEST["num"];
 require_once("../lib/mydb.php");
 $pdo = db_connect(); 
 
if($choice=='2') $alerts=0;  // 알람해제
    else
		$alerts=1;  // 알람설정
	
try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.alert set alert=?  ";
        $sql .= " where num=?  LIMIT 1";  
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $alerts, PDO::PARAM_STR);    	 
     $stmh->bindValue(2, $num, PDO::PARAM_STR);      	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   	
 
	 ?>