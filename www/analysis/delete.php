  <?php   
   session_start();

 $ordercompany=$_REQUEST["ordercompany"]; 	     // 발주처  
 $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호  
 $upnum=$_REQUEST["upnum"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $num=$_REQUEST["num"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $outputnum=$_REQUEST["outputnum"]; 
 $length1=$_REQUEST["length1"]; 
 $length2=$_REQUEST["length2"]; 
 $length3=$_REQUEST["length3"]; 
 $length4=$_REQUEST["length4"]; 
 $length5=$_REQUEST["length5"]; 
 $amount1=$_REQUEST["amount1"]; 
 $amount2=$_REQUEST["amount2"]; 
 $amount3=$_REQUEST["amount3"]; 
 $amount4=$_REQUEST["amount4"]; 
 $amount5=$_REQUEST["amount5"]; 
 $material1=$_REQUEST["material1"]; 
 $material2=$_REQUEST["material2"]; 
 $material3=$_REQUEST["material3"]; 
 $material4=$_REQUEST["material4"]; 
 $material5=$_REQUEST["material5"]; 
 $material6=$_REQUEST["material6"]; 
 $material7=$_REQUEST["material7"]; 
 $material8=$_REQUEST["material8"]; 
 $sum1=$_REQUEST["sum1"]; 
 $sum2=$_REQUEST["sum2"]; 
 $sum3=$_REQUEST["sum3"]; 
 $sum4=$_REQUEST["sum4"]; 
 $sum5=$_REQUEST["sum5"]; 
 $sum6=$_REQUEST["sum6"]; 
 $sum7=$_REQUEST["sum7"]; 
 $sum8=$_REQUEST["sum8"]; 
 
 $total_text=$_REQUEST["total_text"]; 
 $datanum=$_REQUEST["datanum"];  //절곡데이터 번호 기억
  
    require_once("../lib/mydb.php");
    $pdo = db_connect();
   
   if($mode=="all")
   {
  try{
     $pdo->beginTransaction();
     $sql = "delete from chandj.bending_write where upnum = ?";  
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
     $sql = "delete from chandj.bending_write where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();   
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
 }
 	 header("Location:http://5130.co.kr/analysis/write.php?upnum=$upnum&outputnum=$outputnum&datanum=$datanum&ordercompany=$ordercompany&callname=$callname&cutheight=$cutheight&cutwidth=$cutwidth&number=$number&comment=$comment&printside=$printside&direction=$direction&exititem=$exititem&intervalnum=$intervalnum&intervalnumsecond=$intervalnumsecond&memo=$memo&parentnum=$parentnum");    // 신규가입일때는 리스트로 이동
?>