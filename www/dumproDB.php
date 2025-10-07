<?php

require_once("./lib/mydb.php");
$pdo = db_connect();     

$num_arr = array();
$checkdate_arr = array();
$item_arr = array();
$term_arr = array();
$check1_arr = array();
$check2_arr = array();
$check3_arr = array();
$check4_arr = array();
$check5_arr = array();
$check6_arr = array();
$check7_arr = array();
$check8_arr = array();
$check9_arr = array();
$check10_arr = array();
$writer_arr = array();

// 자료읽기
$sql="select * from mirae8440.mymclist " ;
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

	          array_push($num_arr,$row["num"]);	
	          array_push($checkdate_arr, $row["checkdate"]);	
	          array_push($item_arr, $row["item"]);
	          array_push($term_arr, $row["term"]);
	          array_push($check1_arr, $row["check1"]);	
	          array_push($check2_arr, $row["check2"]);		
	          array_push($check3_arr, $row["check3"]);		
	          array_push($check4_arr, $row["check4"]);		
	          array_push($check5_arr, $row["check5"]);		
	          array_push($check6_arr, $row["check6"]);		
	          array_push($check7_arr, $row["check7"]);		
	          array_push($check8_arr, $row["check8"]);		
	          array_push($check9_arr, $row["check9"]);		
	          array_push($check10_arr, $row["check10"]);		
	          array_push($writer_arr, $row["writer"]);
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 
$strtmp = '주간';	 
$term = Null ;	 

$item_tmp='welder04';
$writer_tmp='이경묵';

// insert인 경우 	
// for($i=0;$i<count($num_arr);$i++) {
for($i=0;$i<47;$i++) {  // 특정데이터 숫자만큼만 제작
   // if($term_arr[$i]=='')
    // {	   
	 try{
		$pdo->beginTransaction();   
		$sql = "insert into mirae8440.mymclist (checkdate, item, term, check1,  check2 ,  check3 ,  check4 ,  check5 ,  check6 ,  check7 ,  check8 ,  check9 ,  check10, trouble, fixdata, writer )  " ;
		$sql .= " values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";		
		   
		 $stmh = $pdo->prepare($sql); 
		 $stmh->bindValue(1, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(2, $item_tmp, PDO::PARAM_STR);  
		 $stmh->bindValue(3, $term_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(4, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(5, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(6, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(7, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(8, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(9, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(10, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(11, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(12, $checkdate_arr[$i], PDO::PARAM_STR);  
		 $stmh->bindValue(13, $checkdate_arr[$i], PDO::PARAM_STR);  		 
		 $stmh->bindValue(14, '', PDO::PARAM_STR);  		 
		 $stmh->bindValue(15, '', PDO::PARAM_STR);  		 
		 $stmh->bindValue(16, $writer_tmp, PDO::PARAM_STR);  		 
		 
	 	 
		 $stmh->execute();
		 $pdo->commit();          
			} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
		   }    
	// }  // end of if
}  // end of for

// // update인 경우 	
// for($i=0;$i<count($num_arr);$i++) {
   // // if($term_arr[$i]=='')
    // // {	   
	 // try{
		// $pdo->beginTransaction();   
		// $sql = "update mirae8440.mymclist set check1=?,  check2=?,  check3=?,  check4=?,  check5=?,  check6=?,  check7=?,  check8=?,  check9=?,  check10=? " ;
		// $sql .= " where num=? ";		
		   
		 // $stmh = $pdo->prepare($sql); 
		 // $stmh->bindValue(1, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(2, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(3, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(4, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(5, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(6, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(7, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(8, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(9, $checkdate_arr[$i], PDO::PARAM_STR);  
		 // $stmh->bindValue(10, $checkdate_arr[$i], PDO::PARAM_STR);  		 
		 // $stmh->bindValue(11, $num_arr[$i], PDO::PARAM_STR);  		 
	 
		 // $stmh->execute();
		 // $pdo->commit();          
			// } catch (PDOException $Exception) {
			   // $pdo->rollBack();
			   // print "오류: ".$Exception->getMessage();
		   // }    
	// // }  // end of if
// }  // end of for

?>

