 <?php

// print "<script> alert($modify); </script>";
$num=1;
 require_once("../lib/mydb.php");
 $pdo = db_connect();   
	  
try{

     $sql = "select * from mirae8440.alert where num=?";  // get target record
	   
     $stmh = $pdo->prepare($sql); 
 
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      	 
	 
     $stmh->execute();
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
	 $alerts=$row["alert"];	 
 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   	 
	 ?>
<script>
 var alerts=<?php echo json_encode($alerts ?? ''); ?> ;
$("#alerts").val(alerts);	
//console.log(alerts);
</script>