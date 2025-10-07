<?php

// php warning 안나오게 하는 방법
ini_set('display_errors', 'Off');

// DB이름 설정
$DB = "mirae8440.steelitem";

isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
isset($_REQUEST["SelectWork"])  ? $SelectWork = $_REQUEST["SelectWork"] :   $SelectWork=""; 
isset($_REQUEST["item"])  ? $item = $_REQUEST["item"] :   $item=""; 

if((int)$num == 0)
	$SelectWork="insert"; 

// var_dump($order_prod_des);
              
require_once("../lib/mydb.php");
$pdo = db_connect();	

include "_request.php";

if($SelectWork=="update")  {			

// var_dump($order_prod_cd);

	   try{
		 $pdo->beginTransaction();
									// where 구문이 있음 주의 update에 해당함.
		 $sql = "update " . $DB . " set  item = ?  ";
		 $sql .= " where num=? LIMIT 1 ";                               // 마지막 where num=? LIMIT 1
			 
			$stmh = $pdo->prepare($sql); 

			$stmh->bindValue(1 ,$item         , PDO::PARAM_STR);
			$stmh->bindValue(2,$num 		     , PDO::PARAM_STR);		 
			 
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
		 
		$sql = "insert into " . $DB . " ( item) "; 		
		$sql .= " values(? ) ";		
		 
 		$stmh = $pdo->prepare($sql); 

		$stmh->bindValue(1 ,$item           , PDO::PARAM_STR);	
		
			
		    $stmh->execute();
			 $pdo->commit(); 
			 } catch (PDOException $Exception) {
				  $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
			 }   	 
			 
// parentKey 추출

        $sql = "select * from  " . $DB . "  order by num desc";
		 try{  
			  $stmh = $pdo->query($sql);      // 검색조건에 맞는글 stmh
			  $temp = $stmh->rowCount();
			  $row = $stmh->fetch(PDO::FETCH_ASSOC);
			  $num=$row["num"];
			  
			 } catch (PDOException $Exception) {
					  print "오류: ".$Exception->getMessage();
					  }			  			 					  
	  // print "마지막 parentKey = " . $num;

// echo $num;		  

  
}   // end of $SelectWork if-statement 


if($SelectWork=="delete")  {   // data 삭제시 
   
  
   try{									// esmaindb의 자료를 삭제한다.
     $pdo->beginTransaction();
     $sql = "delete from  " . $DB . "  where num = ?";  
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

