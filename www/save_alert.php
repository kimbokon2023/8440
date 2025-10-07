 <?php

  if(isset($_REQUEST["voc_alert"]))  
   $voc_alert=$_REQUEST["voc_alert"];
  else
   $voc_alert=0;
   
  if(isset($_REQUEST["ma_alert"])) 
   $ma_alert=$_REQUEST["ma_alert"];
  else
   $ma_alert=0;   
   
  if(isset($_REQUEST["order_alert"]))  
   $order_alert=$_REQUEST["order_alert"];
  else
   $order_alert=0;      


$num=1;
 require_once("./lib/mydb.php");
 $pdo = db_connect();  
	
try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.alert set voc_alert=?, ma_alert=?, order_alert=?  ";
        $sql .= " where num=?  LIMIT 1";  
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $voc_alert, PDO::PARAM_STR);    	 
     $stmh->bindValue(2, $ma_alert, PDO::PARAM_STR);    	 
     $stmh->bindValue(3, $order_alert, PDO::PARAM_STR);    	 
     $stmh->bindValue(4, $num, PDO::PARAM_STR);      	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   	
 
	 ?>