<?php
$fromdate=substr(date("Y-m-d",time()),0,7) ;
$fromdate=$fromdate . "-01";
$todate=substr(date("Y-m-d",time()),0,4) . "-12-31";
$Transtodate=strtotime($todate.'+1 days');
$Transtodate=date("Y-m-d",$Transtodate);
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

   $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $sum_arr=array();
   $delicompany_arr=array();
   $delipay_arr=array();
   $secondord_arr=array();
   $worker_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;

 try{   
   $sql="select * from mirae8440.work"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   
   $total_row = 0;
   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	   
			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $address=$row["address"];
			  $firstord=$row["firstord"];
			  $firstordman=$row["firstordman"];
			  $firstordmantel=$row["firstordmantel"];
			  $secondord=$row["secondord"];
			  $secondordman=$row["secondordman"];
			  $secondordmantel=$row["secondordmantel"];
			  $chargedman=$row["chargedman"];
			  $chargedmantel=$row["chargedmantel"];
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $workday=$row["workday"];
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];  	   
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $hpi=$row["hpi"];	   
			  $delicompany=$row["delicompany"];	   
			  $delipay=$row["delipay"];	   	
	   
		   $workday_arr[]=$workday;
		   $workplacename_arr[]=$workplacename;
		   $address_arr[]=$address;
		   $delicompany_arr[]=$delicompany;   
		   $delipay_arr[]=$delipay;   
		   $secondord_arr[]=$secondord;   
		   $worker_arr[]=$worker;   
		   
   
		   // 불량이란 단어가 들어가 있는 수량은 제외한다.		   
		   $findstr = '불량';

		   $pos = stripos($workplacename, $findstr);							   

		   if($pos==0)  {
   				 $workitem="";
				 if($widejamb!="")   {
					    $workitem="막판" . $widejamb . " "; 
						$sum1 += (int)$widejamb;
									}
				 if($normaljamb!="")   {
					    $workitem .="막(無)" . $normaljamb . " "; 					
						$sum2 += (int)$normaljamb;						
						}
				 if($smalljamb!="") {
					    $workitem .="쪽쟘" . $smalljamb . " "; 												   
						$sum3 += (int)$smalljamb;												
						}		   
				$sum_arr[$counter]=$workitem;
			}
		
	   $counter++;
	   $total_row++ ;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$all_sum = $sum1 + $sum2 + $sum3;		 
$jamb_total = "막판:" . $sum1 . ", " . "막판(無):" . $sum2 . ", " . "쪽쟘:" . $sum3  . "  합계:" . $all_sum;		 

$item_arr = array();	
$work_sum = array();	
$month_sum = array();	

$item_arr[0]='막판';
$item_arr[1]='막(無)';
$item_arr[2]='쪽쟘';

$work_sum[0]=$sum1;
$work_sum[1]=$sum2;
$work_sum[2]=$sum3;

$year=substr($fromdate,0,4) ;

		$year_count=0;
		$date_count=0;    // 24개월을 기준으로 데이터를 작성하는 합계변수 2020년1월 2021년1월 이런식으로 계산할 것임.
		$year_sum = array();	
		$year=substr($fromdate,0,4) -1;				
		
	while($year_count<2)	
	  {
		$month_count=0;      // 년도비교 차트 통계 내는 부분
		while($month_count<12)		 
		{		 

					$month=$month_count + 1;
						switch ($month_count) {
							case   0   :   $day=31; break;
							case   1   :   $day=28; break;
							case   2   :   $day=31; break;
							case   3   :   $day=30; break;
							case   4   :   $day=31; break;
							case   5   :   $day=30; break;
							case   6   :   $day=31; break;
							case   7   :   $day=31; break;
							case   8   :   $day=30; break;
							case   9   :   $day=31; break;
							case   10  :   $day=30; break;
							case   11  :   $day=31; break;

						}
					  
					$month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);  // 날짜형식으로 바꾸기
					$month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);  // 날짜형식으로 바꾸기
					
					$sql="select * from mirae8440.work where workday between date('$month_fromdate') and date('$month_todate')" ;
					require_once("../lib/mydb.php");
					$counter=0;
					$sum1=0;
					$sum2=0;
					$sum3=0;

					 try{  
					   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
					   $rowNum = $stmh->rowCount();  
					   $total_row = 0;
					   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	

								  $widejamb=$row["widejamb"];
								  $normaljamb=$row["normaljamb"];
								  $smalljamb=$row["smalljamb"];										  
								  $workplacename=$row["workplacename"];										  
				   
					   // 불량이란 단어가 들어가 있는 수량은 제외한다.		   
					   $findstr = '불량';

					   $pos = stripos($workplacename, $findstr);							   

					   if($pos==0)  {							   
									 $workitem="";
									 if($widejamb!="")   {
											$sum1 += (int)$widejamb;
														}
									 if($normaljamb!="")   {
											$sum2 += (int)$normaljamb;						
											}
									 if($smalljamb!="") {
											$sum3 += (int)$smalljamb;												
											}							   
						   $counter++;	   
						   $total_row++;
							}
						 } 	 
					   } catch (PDOException $Exception) {
						print "오류: ".$Exception->getMessage();
					}  

					$year_sum[$date_count]= $sum1 + $sum2 + $sum3/4;
					$month_count++;
					$date_count++;
		   }
	  $year_count++;
	  $year++;			   
		   
	}



?>
         
<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 	

	 <div id="spreadsheet" style="display:none;">   
     </div>
     </div>

	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 	
	<div class="col-lg-7"> 	
        <canvas id="myChart"  width="700" height="500"></canvas> 		   
	 </div>
	 <div class="col-lg-4"> 	
		   <?php		  
			 
		      print "<h5> 제작수량, 쪽쟘 4 SET -> 와이드 1 SET로 산출함 (단위:SET) </h5>";			
			  print " <h6><span style='color:grey' > 전년 1월  </span> " . "   " . number_format($year_sum[0])      . "(SET), &nbsp; ";
			  print " <span style='color:red' > &nbsp;&nbsp; 1월  </span> " . "   " . number_format($year_sum[12])  . "(SET) <br/><br/>";
			  print " <span style='color:grey' >  전년 2월 </span> " . "   " . number_format($year_sum[1])          . "(SET), &nbsp; ";
			  print " <span style='color:blue' >   &nbsp;&nbsp; 2월 </span> " . number_format($year_sum[13])        . "(SET) <br/><br/>";
			  print " <span style='color:grey' > 전년 3월 </span> " .  number_format($year_sum[2])                  . "(SET), &nbsp; ";
			  print " <span style='color:orange' >  &nbsp;&nbsp; 3월 </span> " . number_format($year_sum[14])       . "(SET) <br/><br/>";
				 print " <span style='color:grey' > 전년 4월 </span> " . number_format($year_sum[3])                . "(SET), &nbsp; ";
				 print " <span style='color:green' >  &nbsp;&nbsp; 4월 </span> " . number_format($year_sum[15])     . "(SET) <br/><br/>";
				  print " <span style='color:grey' > 전년 5월 </span> " .  number_format($year_sum[4])             . "(SET), &nbsp; ";
				  print " <span style='color:purple' >  &nbsp;&nbsp; 5월 </span> " . number_format($year_sum[16])  . "(SET) <br/><br/>";
				  print " <span style='color:grey' > 전년 6월 </span> " . "   " . number_format($year_sum[5])       . "(SET), &nbsp; ";
				  print " <span style='color:blue' >  &nbsp;&nbsp;  6월 </span> " . number_format($year_sum[17])    . "(SET) <br/><br/>";
				  print " <span style='color:grey' > 전년 7월 </span> " . "   " . number_format($year_sum[6])       . "(SET), &nbsp; ";
				  print " <span style='color:orange' >  &nbsp;&nbsp; 7월 </span> " .number_format($year_sum[18])    . "(SET) <br/><br/>";
				  print " <span style='color:grey' > 전년 8월 </span> " . "   " . number_format($year_sum[7])    . "(SET), &nbsp; ";
				  print " <span style='color:green' >  &nbsp;&nbsp; 8월 </span> " . number_format($year_sum[19]) . "(SET) <br/><br/>";
				  print " <span style='color:grey' > 전년 9월 </span> " . "   " .  number_format($year_sum[8])     . "(SET), &nbsp; ";
				  print " <span style='color:purple' >  &nbsp;&nbsp; 9월 </span> " . number_format($year_sum[20])  . "(SET) <br/><br/>";
				  print " <span style='color:grey' > 전년 10월 </span> " . number_format($year_sum[9])              . "(SET), &nbsp; ";
				  print " <span style='color:red' >  &nbsp;&nbsp; 10월 </span> " . number_format($year_sum[21])     . "(SET) <br/><br/>";
				  print " <span style='color:grey' > 전년 11월 </span> " . number_format($year_sum[10])           . "(SET), &nbsp; ";
				  print " <span style='color:blue' >  &nbsp;&nbsp; 11월 </span> " . number_format($year_sum[22])  . "(SET) <br/><br/>";
				  print " <span style='color:grey'> 전년 12월 </span> " . "   " . number_format($year_sum[11])      . "(SET), &nbsp; ";  			  
				  print " <span style='color:brown'>  &nbsp;&nbsp; 12월 </span> " . number_format($year_sum[23])    . "(SET) </h6> " ;	  			  
			  
           ?>  
     </div>
	 </div>
	
  
<script> 
  					
    var item_arr = [];
    var work_sum = <?php echo json_encode($work_sum);?> ;
    var month_sum = <?php echo json_encode($month_sum);?> ;
    var year_sum = <?php echo json_encode($year_sum);?> ;
	var ctx = document.getElementById('myChart');
    var chart_type = 'bar';
    var item_type = '년도비교';	

		item_arr[0] = '전년1월';
		item_arr[1] = '1월';
		item_arr[2] = '전년2월';
		item_arr[3] = '2월';
		item_arr[4] = '전년3월';
		item_arr[5] = '3월';
		item_arr[6] = '전년4월';
		item_arr[7] = '4월';
		item_arr[8] = '전년5월';
		item_arr[9] = '5월';
		item_arr[10] = '전년6월';
		item_arr[11] = '6월';
		item_arr[12] = '전년7월';
		item_arr[13] = '7월';
		item_arr[14] = '전년8월';
		item_arr[15] = '8월';
		item_arr[16] = '전년9월';
		item_arr[17] = '9월';
		item_arr[18] = '전년10월';
		item_arr[19] = '10월';
		item_arr[20] = '전년11월';
		item_arr[21] = '11월';
		item_arr[22] = '전년12월';
		item_arr[23] = '12월';
		
	var myChart = new Chart(ctx, {
		type: chart_type,
		data: {
			labels: [item_arr[0], item_arr[1], item_arr[2],item_arr[3], item_arr[4], item_arr[5],item_arr[6], item_arr[7], item_arr[8],item_arr[9], item_arr[10],item_arr[11], item_arr[12], item_arr[13], item_arr[14],item_arr[15], item_arr[16], item_arr[17],item_arr[18], item_arr[19], item_arr[20],item_arr[21], item_arr[22],item_arr[23]  ],
			datasets: [{
				label: '# 쟘 전년도 제작수량 합계 , #금년도 제작수량',
				data: [ year_sum[0], year_sum[12], year_sum[1],year_sum[13], year_sum[2], year_sum[14],year_sum[3], year_sum[15], year_sum[4],year_sum[16], year_sum[5],year_sum[17], year_sum[6], year_sum[18], year_sum[7],year_sum[19], year_sum[8], year_sum[20],year_sum[9], year_sum[21], year_sum[10],year_sum[22], year_sum[11],year_sum[23]  ], 		
				backgroundColor: [
					'rgba(128, 128, 128, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(130, 130, 130, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(132, 132, 132, 0.2)',
					'rgba(205, 100, 25, 0.2)',
					'rgba(134, 134, 134, 0.2)',
					'rgba(95, 452, 60, 0.2)',
					'rgba(136, 136, 136, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(138, 138, 138, 0.2)',				
					'rgba(255, 159, 64, 0.2)' ,					
					'rgba(126, 126, 126, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(128, 128, 128, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(130, 130, 130, 0.2)',
					'rgba(205, 100, 25, 0.2)',
					'rgba(132, 132, 132, 0.2)',
					'rgba(95, 452, 60, 0.2)',
					'rgba(134, 134, 134, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(136, 136, 136, 0.2)',				
					'rgba(255, 159, 64, 0.2)'														
				],
				borderColor: [
					'rgba(128, 128, 128, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(205, 100, 25, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(95, 452, 60, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(255, 99, 132, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(255, 159, 64, 1)'	,
					'rgba(128, 128, 128, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(205, 100, 25, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(95, 452, 60, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(255, 99, 132, 1)',
					'rgba(128, 128, 128, 1)',
					'rgba(255, 159, 64, 1)'										
				],
				borderWidth: 1
			}]
		},
		options: {
			responsive: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			},
		}
	});	

		
	
</script>  