<?php

if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   } 

include $_SERVER['DOCUMENT_ROOT'] . '/common.php';


  // 임시저장된 첨부파일을 확정하기 위해 검사하기  
isset($_REQUEST["timekey"])  ? $timekey=$_REQUEST["timekey"] :  $timekey='';   // 신규데이터에 생성할때 임시저장키  
  
  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"];
  else 
    $page=1;   // 1로 설정해야 함
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["tablename"]))
    $tablename=$_REQUEST["tablename"];
 else 
    $tablename="";

// 기본 항목 불러옴
include '_request.php';
        
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();
    
 if ($mode=="modify"){              
     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440." . $tablename . " set parentID=? ,  subject=? ,  regist_day=?  ,  check0=?  ,  check1=? ,  check2=? ,  check3=? ,  check4=? ,  check5=? ,  check6=? ,  check7=? ,  check8=? ,  check9=? ,  writer=?  where num=?";
        $stmh = $pdo->prepare($sql); 
		$stmh->bindValue(1  , $parentID, PDO::PARAM_STR);  
		$stmh->bindValue(2  , $subject, PDO::PARAM_STR);  
		$stmh->bindValue(3  , $regist_day, PDO::PARAM_STR);  
		$stmh->bindValue(4  , $check0, PDO::PARAM_STR);  
		$stmh->bindValue(5  , $check1, PDO::PARAM_STR);  
		$stmh->bindValue(6  , $check2, PDO::PARAM_STR);  
		$stmh->bindValue(7  , $check3, PDO::PARAM_STR);  
		$stmh->bindValue(8  , $check4, PDO::PARAM_STR);  
		$stmh->bindValue(9  , $check5, PDO::PARAM_STR);  
		$stmh->bindValue(10 , $check6, PDO::PARAM_STR);  
		$stmh->bindValue(11 , $check7, PDO::PARAM_STR);  
		$stmh->bindValue(12 , $check8, PDO::PARAM_STR);  
		$stmh->bindValue(13 , $check9, PDO::PARAM_STR);  
		$stmh->bindValue(14 , $writer, PDO::PARAM_STR);    
		$stmh->bindValue(15 , $num, PDO::PARAM_STR);   
        $stmh->execute();
        $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                                
	   
 } else	{
 // insert인 경우
   try{
     $pdo->beginTransaction();
     $sql = "insert into mirae8440." . $tablename . " (parentID,subject,regist_day,check0, check1,check2,check3,check4,check5,check6,check7,check8,check9,writer) ";     
     $sql .= "values( ?, ?, ? , ?, ?, ?, ? , ? , ?, ?, ?, ? , ? , ? ) ";
     $stmh = $pdo->prepare($sql); 
		$stmh->bindValue(1  , $parentID, PDO::PARAM_STR);  
		$stmh->bindValue(2  , $subject, PDO::PARAM_STR);  
		$stmh->bindValue(3  , $regist_day, PDO::PARAM_STR);  
		$stmh->bindValue(4  , $check0, PDO::PARAM_STR);  
		$stmh->bindValue(5  , $check1, PDO::PARAM_STR);  
		$stmh->bindValue(6  , $check2, PDO::PARAM_STR);  
		$stmh->bindValue(7  , $check3, PDO::PARAM_STR);  
		$stmh->bindValue(8  , $check4, PDO::PARAM_STR);  
		$stmh->bindValue(9  , $check5, PDO::PARAM_STR);  
		$stmh->bindValue(10 , $check6, PDO::PARAM_STR);  
		$stmh->bindValue(11 , $check7, PDO::PARAM_STR);  
		$stmh->bindValue(12 , $check8, PDO::PARAM_STR);  
		$stmh->bindValue(13 , $check9, PDO::PARAM_STR);  
		$stmh->bindValue(14 , $writer, PDO::PARAM_STR);   
     
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }  

	// 신규데이터인경우 num을 추출한 후 view로 보여주기
	 $sql="select * from mirae8440." . $tablename . " order by num desc"; 					

	  try{  
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	   $rowNum = $stmh->rowCount();  
	   $row = $stmh->fetch(PDO::FETCH_ASSOC) ;
		$num=$row["num"];			   			 	
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}    


	 
}


 $data = [   'num' => $num ,
 'row' => $row
 ];
 
 echo json_encode($data, JSON_UNESCAPED_UNICODE);


 ?>

