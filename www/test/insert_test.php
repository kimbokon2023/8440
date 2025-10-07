<!DOCTYPE html>
<meta charset="UTF-8">

<?php
		// 기존데이터 삭제하고, 새로운 데이터로 만듬

		function make_array($str) {          // 콤마로 이뤄진 배열을 풀어준다. decode
			$tmp_num= implode (",", $str);
			$tmp = explode( ',', $tmp_num );
			return $tmp;	
		}

		//$num = make_array($_REQUEST["source_num"]);		

		$test = $_REQUEST["test"];
		$test1 = $_REQUEST["test1"];
		
		// $test = 'dfksafkasdf';
		
		// 데이터 신규 등록하는 구간
		
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
		   try{
			   
			 $pdo->beginTransaction();
			 
			 $sql = "insert into mirae8440.test(test,test1) ";
			 $sql .= " values(?,?) ";
			 
			 $stmh = $pdo->prepare($sql); 

             $stmh->bindValue(1, $test, PDO::PARAM_STR);
             $stmh->bindValue(2, $test1, PDO::PARAM_STR);
				
			 $stmh->execute();
			 $pdo->commit(); 
			 } catch (PDOException $Exception) {
				  $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
			 }   	 


print '완료';
// if($testmode=='' && ($SelectWork!='uploadfile' || $SelectWork!='uploadfile_second')  )
         // unlink('uploadfilearr.txt');
// header("Location:http://8440.co.kr/es/index.php?myfiles=$myfiles&file_name=$file_name&SelectWork=$SelectWork"); 
?>

