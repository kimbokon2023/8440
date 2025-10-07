 <?php 
 session_start();  

 if(isset($_REQUEST["id"]))
    $id=$_REQUEST["id"];
		 else 
			$id="";			
 
  if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 	


 if(isset($_REQUEST["tablename"]))
    $tablename=$_REQUEST["tablename"];
		 else 
			$tablename="";	
		
 if(isset($_REQUEST["imagecolumn"]))
    $imagecolumn=$_REQUEST["imagecolumn"];
		 else 
			$imagecolumn="";	 	
		
 if(isset($_REQUEST["filename"]))
    $filename=$_REQUEST["filename"];
		 else 
			$filename="";	 	

require_once("../lib/mydb.php");
	 $pdo = db_connect();
     // insert
try{		 
	$pdo->beginTransaction();   
	$sql = "insert into mirae8440.woosungpic ";
	$sql .=" (tablename, item, parentid, picname) " ;        
	$sql .=" values(?, ?, ?, ?) " ;        
	   
	 $stmh = $pdo->prepare($sql); 
	 
	 $stmh->bindValue(1, $tablename, PDO::PARAM_STR);   
	 $stmh->bindValue(2, $imagecolumn, PDO::PARAM_STR);   
	 $stmh->bindValue(3, $id, PDO::PARAM_STR);             
	 $stmh->bindValue(4, $filename, PDO::PARAM_STR);   
	 
	 
	 $stmh->execute();
	 $pdo->commit(); 
		} catch (PDOException $Exception) {
		   $pdo->rollBack();
		   print "오류: ".$Exception->getMessage();
 }  

echo $filename;

?>  
  
  
 

