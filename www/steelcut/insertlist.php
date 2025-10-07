 <?php   
  session_start();   
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=8) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
   }
 $modify=$_REQUEST["modify"];
 $outputnum=$_REQUEST["outputnum"];
  $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호
 
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

  if(isset($_REQUEST["sort"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $sort=$_REQUEST["sort"];
  else
   $sort="1";

 if(isset($_REQUEST["upnum"]))
    $upnum=$_REQUEST["upnum"];
 else 
    $upnum=0;

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
$outputnumint=(int)$outputnum;
  if($outputnumint>0) 	{    // 출고번호의 레코드가 존재할 경우 수정모드로 전환
	  $outputnum=$_REQUEST["outputnum"];
 require_once("../lib/mydb.php");
 $pdo = db_connect();	  
	       try{
        $sql = "select * from chandj.egiorderlist where outputnum=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$outputnum,PDO::PARAM_STR); 
        $stmh->execute(); 
        $rowcount=$stmh->rowCount();	
	    $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
      if($rowcount==1) 
	  {
		  	  $mode="modify";		  
			  $num= $row["num"];
			  
	  }		  
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 	  

	  
  }
  
 			  $item_outdate=$_REQUEST["outdate"];
			  $item_indate=$_REQUEST["indate"];
			  $item_orderman=$_REQUEST["orderman"];
			  $item_outworkplace=$_REQUEST["outworkplace"];
			  $item_outputplace=$_REQUEST["outputplace"];
			  $item_phone=$_REQUEST["phone"];
			  $item_receiver=$_REQUEST["receiver"];
			  $item_comment=$_REQUEST["comment"];
    
 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify" || ($mode=="modify" && $modify=="1")){ // 정렬버튼 선택시도 함께
 
   
     try{
        $sql = "select * from chandj.egiorderlist where num=?";  // get target record
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
        $sql = "update chandj.egiorderlist set outdate=?, indate=?, orderman=?, outworkplace=?, outputplace=?, receiver=?, phone=?, comment=?, outputnum=? ";
        $sql .= " where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $item_outdate, PDO::PARAM_STR);  
     $stmh->bindValue(2, $item_indate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $item_orderman, PDO::PARAM_STR);       
		
     $stmh->bindValue(4, $item_outworkplace, PDO::PARAM_STR);        
     $stmh->bindValue(5, $item_outputplace, PDO::PARAM_STR);        
     $stmh->bindValue(6, $item_receiver, PDO::PARAM_STR);        
     $stmh->bindValue(7, $item_phone, PDO::PARAM_STR);        
     $stmh->bindValue(8, $item_comment, PDO::PARAM_STR);        
     $stmh->bindValue(9, $outputnum, PDO::PARAM_STR);        
      
     $stmh->bindValue(10, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 }  
 else
 {
	 
	 // 데이터 신규 등록하는 구간
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into chandj.egiorderlist(outdate, indate, orderman, outworkplace, outputplace, receiver, phone, comment, outputnum)";

     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?)";// 총 9개 레코드 추가
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $item_outdate, PDO::PARAM_STR);  
     $stmh->bindValue(2, $item_indate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $item_orderman, PDO::PARAM_STR);       
		
     $stmh->bindValue(4, $item_outworkplace, PDO::PARAM_STR);        
     $stmh->bindValue(5, $item_outputplace, PDO::PARAM_STR);        
     $stmh->bindValue(6, $item_receiver, PDO::PARAM_STR);        
     $stmh->bindValue(7, $item_phone, PDO::PARAM_STR);        
     $stmh->bindValue(8, $item_comment, PDO::PARAM_STR);  	 
     $stmh->bindValue(9, $outputnum, PDO::PARAM_STR);  	 
   	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
    ?>
 <?php
  if($mode=="not"&&$modify!='1')
  {
	  $sql = "select * from chandj.egiorderlist order by num desc";
      $stmh = $pdo->prepare($sql); 
      $stmh->execute();
      $row = $stmh->fetch(PDO::FETCH_ASSOC); 
      $num = $row["num"];   
      $upnum = $num;   	  
	  header("Location:http://5130.co.kr/egimake/write.php?num=$num&upnum=$upnum&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date&outworkplace=$item_outworkplace&sort=$sort&parentnum=$parentnum");    // 신규가입일때는 리스트로 이동
  }
	 elseif($modify=='1') 	  // 정렬만 보여줌
      header("Location:http://5130.co.kr/egimake/write_form.php?num=$num&upnum=$upnum&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date&outworkplace=$item_outworkplace&sort=$sort&indate=$item_indate&outdate=$item_outdate&orderman=$item_orderman&mode=modify&parentnum=$parentnum");  
 else
	  header("Location:http://5130.co.kr/egimake/write.php?num=$num&upnum=$upnum&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date&outworkplace=$item_outworkplace&sort=$sort&parentnum=$parentnum");  
 
 ?>