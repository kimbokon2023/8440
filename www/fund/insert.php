<?php 
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문 받는측에서 필요한 정보임 ajax로 보내는 쪽에서 type : json

isset($_REQUEST["which"])  ? $which = $_REQUEST["which"] : $which=""; 
isset($_REQUEST["search_opt"])  ? $search_opt = $_REQUEST["search_opt"] : $search_opt=""; 
isset($_REQUEST["displaySelect"])  ? $displaySelect = $_REQUEST["displaySelect"] : $displaySelect=""; 
   
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
 			  $proDate=$_REQUEST["proDate"];			  			  
 			  $writer=$_REQUEST["writer"];			  			  
 			  $amount=$_REQUEST["amount"];			  			  
 			  $memo=$_REQUEST["memo"];
			  $which=$_REQUEST["which"];					  
			  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

     
 if ($mode=="modify"){
      
     try{
        $sql = "select * from mirae8440.fund where num=?";  // get target record
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
        $sql = "update mirae8440.fund set proDate=?, writer=?, amount=?, memo=?, which=? ";        
        $sql .= " where num=?  LIMIT 1";		

	$stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $proDate, PDO::PARAM_STR);  
	$stmh->bindValue(2, $writer, PDO::PARAM_STR);  
	$stmh->bindValue(3, $amount, PDO::PARAM_STR);  
	$stmh->bindValue(4, $memo, PDO::PARAM_STR);  
	$stmh->bindValue(5, $which, PDO::PARAM_STR);  
    $stmh->bindValue(6, $num, PDO::PARAM_STR);   
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	 		 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.fund(proDate, writer, amount, memo, which) ";          
     $sql .= " values(?, ?, ?, ?, ?)";
	   
     $stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $proDate, PDO::PARAM_STR);  
	$stmh->bindValue(2, $writer, PDO::PARAM_STR);  
	$stmh->bindValue(3, $amount, PDO::PARAM_STR);  
	$stmh->bindValue(4, $memo, PDO::PARAM_STR);  
	$stmh->bindValue(5, $which, PDO::PARAM_STR);      	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
	 
	// 신규데이터인경우 num을 추출한 후 view로 보여주기
	 $sql="select * from mirae8440.fund order by num desc limit 1"; 					

	  try{  
		   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh	   
		   $row = $stmh->fetch(PDO::FETCH_ASSOC);
			$num=$row["num"];			   			 			 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}    
                     

   }
  

 $data = [   
	'num' => $num
 ]; 
 
 echo json_encode($data, JSON_UNESCAPED_UNICODE);

 ?>