  <?php   
   session_start();
   $num=$_REQUEST["num"];
   $upnum=$num;   // 상위번호 호출
   $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호         
    require_once("../lib/mydb.php");
    $pdo = db_connect();
   
   if($num=="all")
   {
   try{	      
     $pdo->beginTransaction();	   
	 $sql = "truncate chandj.egiorderlist";
     $stmh = $pdo->prepare($sql); 
     $stmh->execute();
     $pdo->commit();	 
   	 header("Location:http://5130.co.kr/egimake/list.php");    // 리스트로 이동	 
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     }	   

   }
 else 
 {	 
	 
   try{
     $sql = "select * from chandj.egiorderlist where num = ? ";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1,$num,PDO::PARAM_STR); 
     $stmh->execute();
     $count = $stmh->rowCount();      

   }catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
   }
 
   try{
     $pdo->beginTransaction();
     $sql = "delete from chandj.egiorderlist where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
   
  // 리스트를 삭제하고 밑에 딸려있는 세부 항목도 지워줘야 한다. 
 
   try{
     $pdo->beginTransaction();
     $sql = "delete from chandj.egimake where upnum = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$upnum,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
   	 header("Location:http://5130.co.kr/egimake/list.php");    // 신규가입일때는 리스트로 이동
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
   
 }
 
?>