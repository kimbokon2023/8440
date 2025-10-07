<?php\nrequire_once __DIR__ . '/../common/functions.php';
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

require_once(includePath('lib/mydb.php'));
$pdo = db_connect(); 

// 자료읽기
$sql="select * from mirae8440.myarealist " ;
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


    
$todate=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.myarea order by num";

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$counter=0;
$mcnum_arr=array();
$mcno_arr=array();
$mcname_arr=array();
$mcspec_arr=array();
$mcmaker_arr=array();
$mcmain_arr=array();
$mcsub_arr=array();
$qrcode_arr=array();
$questionstep_arr=array();

 try{  
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
		
	  $mcnum_arr[$counter] = $row["num"];
	  $mcno_arr[$counter] = $row["mcno"];
	  $mcname_arr[$counter] = $row["mcname"];
	  $mcspec_arr[$counter] = $row["mcspec"];
	  $mcmaker_arr[$counter] = $row["mcmaker"];
	  $mcmain_arr[$counter] = $row["mcmain"];
	  $mcsub_arr[$counter] = $row["mcsub"];
	  $qrcode_tmp = 'http://8440.co.kr/img/' . $qrcode . '.png' ;
	  $qrcode_arr[$counter] = 'http://8440.co.kr/img/' . $qrcode . '.png' ;
      $questionstep_arr[$counter]=$row["questionstep"];	  	  
	  
      $counter++;		 		
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
}	


// mcmain mcsub 찾아 정하기
for($i=0;$i<count($mcmain_arr);$i++)
{
	if($mcno_arr[$i] == $mcno)
	{
		$mcmain= $mcmain_arr[$i];
		$mcsub= $mcsub_arr[$i];
		// print '찾았다.';
	}
	// print $mcno_arr[$i];
}


 ?>