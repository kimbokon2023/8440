 <?php   
  
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

$measureday=date("Y-m-d");
 		
 require_once("../lib/mydb.php");
 $pdo = db_connect();     
      
	try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.work set measureday=? where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $measureday, PDO::PARAM_STR);         
     $stmh->bindValue(2, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }     
	   
	   
     // echo '<script>alert("실측일이 입력되었습니다."");</script>';	    
	
    header("Location:https://8440.co.kr/p/view.php?num=$num");  
 ?>