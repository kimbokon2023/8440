
 <?php
 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}
 
require_once("../lib/mydb.php");
$pdo = db_connect();	
$common=" order by orderday desc ";  // 출고예정일이 현재일보다 클때 조건
$sql = "select * from mirae8440.oem " . $common; 	

$csv_dump = "";				 
$csv_dump .= "날짜,매입처,발주처,현장명,업체납기,타입,인승,수량,L/C,기타,Car Insize,기타(메모)"; 
$csv_dump .= "\r\n"; 
 
 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   		 while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			 
			  $num=$row["num"];
			  $workplacename=$row["workplacename"];
			  $firstord=$row["firstord"];
			  $secondord=$row["secondord"];
			  $orderday=$row["orderday"];
			  $deadline=$row["deadline"];		  
			  
			  $demand=$row["demand"];
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $car_insize=$row["car_insize"];		
			  $memo=$row["memo"];			  
			  
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);			  
			  
			$csv_dump .= $orderday .","; 
			$csv_dump .= $firstord .","; 
			$csv_dump .= str_replace(",", "; ", $secondord) .","; 
			$csv_dump .= str_replace(",", "; ", $workplacename) .","; 
			$csv_dump .= $deadline .","; 
			$csv_dump .= str_replace(",", "; ", $type) .","; 
			$csv_dump .= $inseung .","; 
			$csv_dump .= $su .","; 
			$csv_dump .= $lc_su .","; 
			$csv_dump .= $etc_su .","; 
			$csv_dump .= $car_insize .",";    
			$csv_dump .=  str_replace(",", "; ", $memo) .",";  

			$csv_dump .= "\r\n"; 
	 } 	 
   } catch (PDOException $Exception) {   
    print "오류: ".$Exception->getMessage();    
}   
		 
$date = date("YmdHi"); 
$filename = "Outsorcing_CSV_".$date.".csv"; 

/*
header('Content-Type: '.$mime.';charset=UTF-8;');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Expires: 0');
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.$filesize);
header('Cache-Control: private, no-transform, no-store, must-revalidate');
  
echo "\xEF\xBB\xBF"; // 얠 넣어줘야 안깨짐...
출처: https://wonis-lifestory.tistory.com/entry/php-csv-다운로드-시-한글-깨짐 [Woni's Life Story]
*/

header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");  
header ("Content-Description: Generated Report" );
echo "\xEF\xBB\xBF"; // 2020.08.12 추가, 엑셀에서 파일열었을때 한글 깨짐 수정
echo $csv_dump; 		 
echo "\xEF\xBB\xBF"; // 2020.08.12 추가, 엑셀에서 파일열었을때 한글 깨짐 수정
			?>
