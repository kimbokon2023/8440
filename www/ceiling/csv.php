 <?php
 
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel");
header( "Content-type: application/vnd.ms-excel; charset=utf-8");

  // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

$orderby=" order by orderday desc ";
  
$sql="select * from mirae8440.ceiling where orderday between date('$fromdate') and date('$todate')" . $orderby;  	

require_once("../lib/mydb.php");
$pdo = db_connect();	

$csv_dump = "";				 
$csv_dump .= "날짜,원청,발주처,현장명,납기일,타입,인승,수량,본천장,L/C,기타,공기청정기,Car Insize,"; 
$csv_dump .= "\r\n"; 

 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}
 
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
			  $air_su=$row["air_su"];			  
			  $car_insize=$row["car_insize"];			  
			              		  
			  $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

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
			$csv_dump .= $bon_su .","; 
			$csv_dump .= $lc_su .","; 
			$csv_dump .= $etc_su .","; 
			$csv_dump .= $air_su .","; 
			$csv_dump .= $car_insize .",";    

			$csv_dump .= "\r\n"; 
	 } 	 
   } catch (PDOException $Exception) {   
    print "오류: ".$Exception->getMessage();    
}   
		 
$date = date("YmdHi"); 
$filename = "ceiling_CSV_".$date.".csv"; 

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

echo "\xEF\xBB\xBF"; // 2020.08.12 추가, 엑셀에서 파일열었을때 한글 깨짐 수정
echo $csv_dump; 		 
echo "\xEF\xBB\xBF"; // 2020.08.12 추가, 엑셀에서 파일열었을때 한글 깨짐 수정
header("Content-Disposition: attachment; filename=$filename");  
header ("Content-Description: Generated Report" );


			?>
			
<script>

	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '출고일 ,' ;
			   row += '현장명 ,' ;
			   row += '원청 ,' ;
			   row += '발주처 ,' ;
			   row += '현장주소 ,' ;
			   row += '시공소장 ,' ;
			   row += '수량 ,' ;
			   row += '운송자 ,' ;
			   row += '비용 ' ;

			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			const COLNUM = 9;   
			for (let i = 0; i <grid.getRowCount(); i++) {
			   let row = "";			  
			   row += (i+1) + ',' ;
			   for(let j=1; j<=COLNUM ; j++) {
				  let tmp = String(grid.getValue(i, 'col'+j));
				  tmp = tmp.replace(/undefined/gi, "") ;
				  tmp = tmp.replace(/#/gi, " ") ;
				  row +=  tmp.replace(/,/gi, "") + ',' ;
			   }

			   csvContent += row + "\r\n";
			}		 		  
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "miraeCSV_CeilingData.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
		
	


</script>			
