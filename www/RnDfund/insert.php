<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session_header.php'); 

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문 받는측에서 필요한 정보임 ajax로 보내는 쪽에서 type : json

isset($_REQUEST["which"])  ? $which = $_REQUEST["which"] : $which=""; 
isset($_REQUEST["search_opt"])  ? $search_opt = $_REQUEST["search_opt"] : $search_opt=""; 
isset($_REQUEST["displaySelect"])  ? $displaySelect = $_REQUEST["displaySelect"] : $displaySelect=""; 
   
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

if(isset($_REQUEST["tablename"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $tablename=$_REQUEST["tablename"];
   
 if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];

include '_request.php';
			  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

     
 if ($mode=="modify"){
      
     try{
        $sql = "select * from ".$DB."." . $tablename . " where num=?";  // get target record
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
        $sql = "update  ".$DB."." . $tablename . "  set proDate=?, writer=?, amount=?, memo=?, which=?, item=?, comment=? ";        
        $sql .= " where num=?  LIMIT 1";		

	$stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $proDate, PDO::PARAM_STR);  
	$stmh->bindValue(2, $writer, PDO::PARAM_STR);  
	$stmh->bindValue(3, $amount, PDO::PARAM_STR);  
	$stmh->bindValue(4, $memo, PDO::PARAM_STR);  
	$stmh->bindValue(5, $which, PDO::PARAM_STR);  
    $stmh->bindValue(6, $item, PDO::PARAM_STR);   
    $stmh->bindValue(7, $comment, PDO::PARAM_STR);   
    $stmh->bindValue(8, $num, PDO::PARAM_STR);   
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	 		 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into  ".$DB."." . $tablename . "  (proDate, writer, amount, memo, which, item, comment) ";          
     $sql .= " values(?, ?, ?, ?, ?, ?, ?)";
	   
     $stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $proDate, PDO::PARAM_STR);  
	$stmh->bindValue(2, $writer, PDO::PARAM_STR);  
	$stmh->bindValue(3, $amount, PDO::PARAM_STR);  
	$stmh->bindValue(4, $memo, PDO::PARAM_STR);  
	$stmh->bindValue(5, $which, PDO::PARAM_STR);      	 
	$stmh->bindValue(6, $item, PDO::PARAM_STR);      	 
	$stmh->bindValue(7, $comment, PDO::PARAM_STR);      	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
	 
	// 신규데이터인경우 num을 추출한 후 view로 보여주기
	 $sql="select * from  ".$DB."." . $tablename . " order by num desc limit 1"; 					

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