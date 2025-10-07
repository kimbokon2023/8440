  <?php   
   session_start();
   $mode=$_REQUEST["mode"];
   $num=$_REQUEST["num"];
   $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호   
   $sort=$_REQUEST["sort"];
   $upnum=$_REQUEST["upnum"];
  $tempnum=$_REQUEST["upnum"]; 	     // 발주서 번호 비교를 위해 임시변수 만듦
  $callname=$_REQUEST["callname"]; 
  $cutwidth=$_REQUEST["cutwidth"]; 
  $cutheight=$_REQUEST["cutheight"]; 
  $number=$_REQUEST["number"]; 	
  $exititem=$_REQUEST["exititem"];    
  $memo=$_REQUEST["memo"];    
  $intervalnum=$_REQUEST["intervalnum"];    
  $intervalnumsecond=$_REQUEST["intervalnumsecond"];    
  $printside=$_REQUEST["printside"];    
  $direction=$_REQUEST["direction"];    
  $ordercompany=$_REQUEST["ordercompany"];    
  
    require_once("../lib/mydb.php");
    $pdo = db_connect();
   
   if($mode=="all")
   {
  try{
     $pdo->beginTransaction();
     $sql = "delete from chandj.egimake where upnum = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$upnum,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();   
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }	   

   }
 else 
 {	 
	  
   try{
     $pdo->beginTransaction();
     $sql = "delete from chandj.egimake where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();   
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
 }
 	 header("Location:http://5130.co.kr/egimake/write.php?upnum=$upnum&num=$num&ordercompany=$ordercompany&callname=$callname&cutheight=$cutheight&cutwidth=$cutwidth&number=$number&comment=$comment&printside=$printside&direction=$direction&exititem=$exititem&intervalnum=$intervalnum&intervalnumsecond=$intervalnumsecond&memo=$memo&parentnum=$parentnum&hinge=$hinge");    // 신규가입일때는 리스트로 이동
?>