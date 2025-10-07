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

  if(isset($_REQUEST["separate_date"]))   //출고일 완료일
	 $separate_date=$_REQUEST["separate_date"];	 

// code는 접수완료, 처리완료를 표시해 준다.
$code=$_REQUEST["code"];

$which='3';

$indate= date("Y-m-d");   // 현재일자 변수지정   
 		
 require_once("../lib/mydb.php");
 $pdo = db_connect();
       
	try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.request set which=? , indate=? where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);         
     $stmh->bindValue(2, $indate, PDO::PARAM_STR);         
     $stmh->bindValue(3, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
    header("Location:http://8440.co.kr/request/view.php?num=$num&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>