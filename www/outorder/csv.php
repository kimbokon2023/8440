
 <?php
  session_start();
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"])) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
//header("Refresh:0");  // reload refresh  
 
 
 
 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}
 
require_once("../lib/mydb.php");
$pdo = db_connect();	
$common=" order by orderday desc ";  // 출고예정일이 현재일보다 클때 조건
$sql = "select * from mirae8440.outorder " . $common; 	

$csv_dump = array();				 
$counter = 0;
 
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
			  
			$csv_dump[$counter] .= $orderday .","; 
			$csv_dump[$counter] .= $firstord .","; 
			$csv_dump[$counter] .= str_replace(",", "; ", $secondord) .","; 
			$csv_dump[$counter] .= str_replace(",", "; ", $workplacename) .","; 
			$csv_dump[$counter] .= $deadline .","; 
			$csv_dump[$counter] .= str_replace(",", "; ", $type) .","; 
			$csv_dump[$counter] .= $inseung .","; 
			$csv_dump[$counter] .= $su .","; 
			$csv_dump[$counter] .= $lc_su .","; 
			$csv_dump[$counter] .= $etc_su .","; 
			$csv_dump[$counter] .= $car_insize .",";    
			$csv_dump[$counter] .=  str_replace(",", "; ", $memo) .",";  

			$csv_dump[$counter] .= "\r\n"; 
			
			$counter++;
	 } 	 
   } catch (PDOException $Exception) {   
    print "오류: ".$Exception->getMessage();    
}   

?>


<script>
$(document).ready(function(){
 var arr = <?php echo json_encode($csv_dump);?> ;
 var counter = <?php echo $counter ;?> ;
 var total_sum=0;
	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";				   
			   row += "번호,날짜,매입처,발주처,현장명,업체납기,타입,인승,수량,L/C,기타,Car Insize,기타(메모)"; 
			  				
			   csvContent += row + "\r\n";

			const COLNUM = 13;   
			for (let i = 0; i <counter; i++) {
			   let row = "";			  
			   row += (i+1) + ',' ;
				  let tmp = String(arr[i]);
				  tmp = tmp.replace(/undefined/gi, "") ;
				   row += tmp.replace(/#/gi, " ") ;
				 //  row +=  tmp.replace(/,/gi, "") ;
                   csvContent += row + "\r\n";				  
			   }
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "miraeCSV_OutOEMDen.csv");
			document.body.appendChild(link); 
			link.click();
});

</script>