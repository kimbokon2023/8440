 <?php   
 
 session_start();   
 $level= $_SESSION["level"];
   
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

 include 'request.php';
 
$data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea   

 require_once("../lib/mydb.php");
 $pdo = db_connect();
	 
 if ($mode==="modify"){

     try{
        $sql = "select * from mirae8440.work_outcost where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
	 
 try{
	$pdo->beginTransaction();   
	$sql = "update mirae8440.work_outcost set registerdate=?, widejamb_unitprice=?, normaljamb_unitprice=?, narrowjamb_unitprice=? , update_log=?  ";
	$sql .= " where num=?  LIMIT 1";		
	   
     $stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $registerdate, PDO::PARAM_STR);  
	$stmh->bindValue(2, $widejamb_unitprice, PDO::PARAM_STR);  
	$stmh->bindValue(3, $normaljamb_unitprice, PDO::PARAM_STR);  
	$stmh->bindValue(4, $narrowjamb_unitprice, PDO::PARAM_STR);  
	$stmh->bindValue(5, $update_log, PDO::PARAM_STR);  
	$stmh->bindValue(6, $num, PDO::PARAM_STR);        
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } 
 
 if ($mode==="insert"){	 
		 
	try{	
		$pdo->beginTransaction();

		$sql = "insert into mirae8440.work_outcost(registerdate, widejamb_unitprice, normaljamb_unitprice, narrowjamb_unitprice, update_log ) ";

		$sql .= " values(?, ?, ?, ?, update_log )";

		$stmh = $pdo->prepare($sql); 
		$stmh->bindValue(1, $registerdate, PDO::PARAM_STR);  
		$stmh->bindValue(2, $widejamb_unitprice, PDO::PARAM_STR);  
		$stmh->bindValue(3, $normaljamb_unitprice, PDO::PARAM_STR);  
		$stmh->bindValue(4, $narrowjamb_unitprice, PDO::PARAM_STR);  
		$stmh->bindValue(5, $update_log, PDO::PARAM_STR);  

		$stmh->execute();
		$pdo->commit(); 
		} catch (PDOException $Exception) {
		  $pdo->rollBack();
		print "오류: ".$Exception->getMessage();
		}   
	}
	
 if ($mode==="delete"){	 	  	 
	   
	try{	 
     $pdo->beginTransaction();
  	 
     $sql = "delete from mirae8440.work_outcost where num = ? ";
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}
	
$response = array(
        "num" => $num,
        "registerdate" => $registerdate
		
    );
    echo json_encode($response);


 ?>