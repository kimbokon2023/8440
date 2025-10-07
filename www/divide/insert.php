 <?php   
  session_start();   
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=8) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }
   
  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"];
  else 
    $page=1;   // 1로 설정해야 함
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $find=$_REQUEST["find"];
  else
   $find="";
  if(isset($_REQUEST["process"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $process=$_REQUEST["process"];
  else
   $process="전체";
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];

              $num=$_REQUEST["num"];
 			  $outdate=$_REQUEST["outdate"];			  
			  
			  $indate=$_REQUEST["indate"];
			  $outworkplace=$_REQUEST["outworkplace"];
			  
			  $item=$_REQUEST["item"];			  
			  $spec=$_REQUEST["spec"];
			  $steelnum=$_REQUEST["steelnum"];			  
			  $company=$_REQUEST["company"];
			  $comment=$_REQUEST["comment"];
			  $which=$_REQUEST["which"];	 	
			  $model=$_REQUEST["model"];	 				  
			  $holes=$_REQUEST["holes"];	 				  

 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify"){
      
     try{
        $sql = "select * from mirae8440.divide where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
       
	    
			  
/* 	print "접속완료"	  ; */

     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.divide set which=?, outdate=?, indate=?, outworkplace=?, item=?, spec=?, steelnum=?, company=?, comment=?, model=?, holes=?";
        $sql .= " where num=?  LIMIT 1";		
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);  
     $stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $indate, PDO::PARAM_STR);  
     $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);  
     $stmh->bindValue(5, $item, PDO::PARAM_STR);  
     $stmh->bindValue(6, $spec, PDO::PARAM_STR);  
     $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);  
     $stmh->bindValue(8, $company, PDO::PARAM_STR);  
     $stmh->bindValue(9, $comment, PDO::PARAM_STR);  
     $stmh->bindValue(10, $model, PDO::PARAM_STR);  
     $stmh->bindValue(11, $holes, PDO::PARAM_STR);  

     $stmh->bindValue(12, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	 
	 // 데이터 신규 등록하는 구간
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.divide(which, outdate, indate, outworkplace, item, spec, steelnum, company, comment, model,holes) ";
     
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);  
     $stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $indate, PDO::PARAM_STR);  
     $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);  
     $stmh->bindValue(5, $item, PDO::PARAM_STR);  
     $stmh->bindValue(6, $spec, PDO::PARAM_STR);  
     $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);  
     $stmh->bindValue(8, $company, PDO::PARAM_STR);  
     $stmh->bindValue(9, $comment, PDO::PARAM_STR);  
     $stmh->bindValue(10, $model, PDO::PARAM_STR);  
     $stmh->bindValue(11, $holes, PDO::PARAM_STR);  
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
    ?>
  <script>
        alert('자료등록/수정 완료');      
  </script>
  
  
 <?php
  if($mode=="not")
   header("Location:http://8440.co.kr/divide/read_DB.php?num=$num&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");    // 신규가입일때는 리스트로 이동
	 else		 
     header("Location:http://8440.co.kr/divide/view.php?num=$num&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>