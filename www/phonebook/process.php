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
              
require_once("../lib/mydb.php");
$pdo = db_connect();	

include "_request.php";

// DB이름 설정
$DBtablename = $DB . "." . $tablename ;

if($SelectWork=="update")  {			

// var_dump($order_prod_cd);

	   try{
		 $pdo->beginTransaction();
									// where 구문이 있음 주의 update에 해당함.
		 $sql = "update " . $DBtablename . " set  phone_name = ?,  phonenumber = ?,  belongstr = ?   ";
		 $sql .= " where num=? LIMIT 1 ";                               // 마지막 where num=? LIMIT 1
			 
			$stmh = $pdo->prepare($sql); 

			$stmh->bindValue(1 ,$phone_name         , PDO::PARAM_STR);
			$stmh->bindValue(2 ,$phonenumber         , PDO::PARAM_STR);
			$stmh->bindValue(3 ,$belongstr         , PDO::PARAM_STR);
			$stmh->bindValue(4 ,$num 		     , PDO::PARAM_STR);		 
			 
			$stmh->execute();
			$pdo->commit(); 
			 } catch (PDOException $Exception) {
				  $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
			 }   	 
			 
	  
		
}   // end of $SelectWork if-statement
	

			
if( $SelectWork=="insert")  {	 // 선택에 따라 index로 또는 list로 분기한다. $num이 Null일때	
			
	// 데이터 신규 등록하는 구간		
	
	   try{
		$pdo->beginTransaction();
		 
		$sql = "insert into " . $DBtablename . " ( phone_name, phonenumber, belongstr) "; 		
		$sql .= " values(?, ?, ?) ";		
		 
 		$stmh = $pdo->prepare($sql); 

		$stmh->bindValue(1 ,$phone_name         , PDO::PARAM_STR);
		$stmh->bindValue(2 ,$phonenumber         , PDO::PARAM_STR);
		$stmh->bindValue(3 ,$belongstr         , PDO::PARAM_STR);
		
		$stmh->execute();
		 $pdo->commit(); 
		 } catch (PDOException $Exception) {
			  $pdo->rollBack();
		   print "오류: ".$Exception->getMessage();
		 }   	 
			 
// parentKey 추출

        $sql = "select * from  " . $DBtablename . "  order by num desc ";
		 try{  
			  $stmh = $pdo->query($sql);      // 검색조건에 맞는글 stmh
			  $temp = $stmh->rowCount();
			  $row = $stmh->fetch(PDO::FETCH_ASSOC);
			  $num=$row["num"];
			  
			 } catch (PDOException $Exception) {
					  print "오류: ".$Exception->getMessage();
					  }			  			 					  
  
}   // end of $SelectWork if-statement 


if($SelectWork=="delete")  {   // data 삭제시 
   
  
   try{									// esmaindb의 자료를 삭제한다.
     $pdo->beginTransaction();
     $sql = "delete from  " . $DBtablename . "  where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();  

     $pdo->commit();
	 
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }

}

?>

