 <?php   
  session_start();   
 $level= $_SESSION["level"];
   
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
		    include 'request.php';

 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify"){
    $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea      
     try{
        $sql = "select * from mirae8440.cost where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
	 
// data 수정 update       

     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.cost set which=?, outdate=?, indate=?, outworkplace=?, item=?, spec=?, steelnum=?, company=?, comment=?, model=? ,first_writer=?, update_log=? , supplier=? , requestdate = ?  , suppliercost = ? ";
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
     $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);  
     $stmh->bindValue(12, $update_log, PDO::PARAM_STR);  
     $stmh->bindValue(13, $supplier, PDO::PARAM_STR);  
     $stmh->bindValue(14, $requestdate, PDO::PARAM_STR);  
     $stmh->bindValue(15, $suppliercost, PDO::PARAM_STR);  

     $stmh->bindValue(16, $num, PDO::PARAM_STR);        
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	 // 데이터 신규 등록하는 구간
	 $first_writer=$_SESSION["name"] . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록 
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.cost(which, outdate, indate, outworkplace, item, spec, steelnum, company, comment, model, first_writer, update_log, supplier, requestdate, suppliercost ) ";
     
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	   
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
     $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);  
     $stmh->bindValue(12, $update_log, PDO::PARAM_STR);  
     $stmh->bindValue(13, $supplier, PDO::PARAM_STR);  
     $stmh->bindValue(14, $requestdate, PDO::PARAM_STR);  
     $stmh->bindValue(15, $suppliercost, PDO::PARAM_STR);  
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }

  if($mode=="not")
   header("Location:http://8440.co.kr/cost/read_DB.php?num=$num&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");    // 신규가입일때는 리스트로 이동
	 else		 
     header("Location:http://8440.co.kr/cost/view.php?num=$num&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>