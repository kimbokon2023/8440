
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8"> 
 <link rel="stylesheet" type="text/css" href="../css/statistics.css">
 <link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>

<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />

 
 <?php
 

 if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	 
 if(isset($_REQUEST["load_confirm"]))   // 초기 당월 차트보이도록 변수를 저장하고 다시 부르면 실행되지 않도록 하기 위한 루틴
	 $load_confirm=$_REQUEST["load_confirm"];	 
  
  if(isset($_REQUEST["display_sel"]))   //목록표에 제목,이름 등 나오는 부분
	 $display_sel=$_REQUEST["display_sel"];	 
	 else
		 	 $display_sel='bar';	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	  

  if(isset($_REQUEST["mode"]))
         $mode=$_REQUEST["mode"];
	   else 
		    $mode="";     
   
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,7) ;
	$fromdate=$fromdate . "-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,7) . "-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}

$SettingDate="workday ";

$common="   where workday between date('$fromdate') and date('$Transtodate')  " ;
 
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum=array();

$sql="select * from mirae8440.work " .$common; 	
 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];			  
		      $row = $stmh->fetch(PDO::FETCH_ASSOC); 				  
			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $address=$row["address"];
			  $deadline=$row["deadline"];
			  $workday=$row["workday"];
			  $worker=$row["worker"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
	
        for($i=1;$i<=$rowNum;$i++) {			 			  

	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='1' and $tmp==$sum_title[$i])
				    $sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
     // $sum[$i]=(float)-1;				
		           }
	
			  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

$sql="select * from mirae8440.work " . $common; 	 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

       $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
 
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
  
  $delivery=$row["delivery"];
  $delicar=$row["delicar"];
  $delicompany=$row["delicompany"];
  $delipay=$row["delipay"];
  $delimethod=$row["delimethod"];
  $demand=$row["demand"];
  $startday=$row["startday"];
  $testday=$row["testday"];
  $hpi=$row["hpi"];  
  $first_writer=$row["first_writer"];
  $update_log=$row["update_log"];
	
        for($i=1;$i<=$rowNum;$i++) {
			 			  
 			  
	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='2' and $tmp==$sum_title[$i])
				    $sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

  
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')   " . $a; 					
	                       			
			     }
	   
				   
            else { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.work where ((workday like '%$search%')  or (workplacename like '%$search%') ";
							  $sql .="or (item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%')) and (workday between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  ";

						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')   " . $a; 				
					
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   
	
$worker_arr = array();	
$work_done = array();	
$temp_arr = array();	
$count=0;

$worker_arr[0]='추영덕';
$worker_arr[1]='이만희';
$worker_arr[2]='김상훈';
$worker_arr[3]='유영';
$worker_arr[4]='김운호';
$worker_arr[5]='손상민';
$worker_arr[6]='김한준';

   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();



			 
			?>
		 
<div id="tmp" style="float:left;width:880px;">

  
       <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
       <input id="load_confirm" name="load_confirm" type='hidden' value='<?=$load_confirm?>' >	
   
		<div id="tmp left" style="float:left;width:510px;">   
	<h2> 당월 시공소장별 시공실적 	<br>  <br>     
	 <?php
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];		  
  $checkstep=$row["checkstep"];
  $workplacename=$row["workplacename"];
  $workday=$row["workday"];
  $worker=$row["worker"];
  $widejamb=$row["widejamb"];
  $normaljamb=$row["normaljamb"];
  $smalljamb=$row["smalljamb"];
 
 
 
			  
			//  print $temp_arr[0];
			  
			  $work_done[$count]=   (int) $widejamb +   (int) $normaljamb +  (int) $smalljamb/4 ;
 			  
							switch ($worker) {
								case   $worker_arr[0]     :   $work_sum[0] += $work_done[$count]; break;
								case   $worker_arr[1]     :   $work_sum[1] += $work_done[$count]; break;	
								case   $worker_arr[2]     :   $work_sum[2] += $work_done[$count]; break;	
								case   $worker_arr[3]     :   $work_sum[3] += $work_done[$count]; break;
								case   $worker_arr[4]     :   $work_sum[4] += $work_done[$count]; break;									
								case   $worker_arr[5]     :   $work_sum[5] += $work_done[$count]; break;									
								case   $worker_arr[6]     :   $work_sum[6] += $work_done[$count]; break;									
									

								default:  break;	
							}					  		  			  

              $count++;
			$start_num--;  
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
  print " <span style='color:blue' >  -> 쪽쟘 4(SET)는 와이드 1(SET)로 산출함  </span> </h2>";
  print " <h4><span style='color:red' > " . $worker_arr[0] . " </span> " . number_format($work_sum[0]) . "(SET),";
  print " <span style='color:blue' > " . $worker_arr[1] . " </span> " . number_format($work_sum[1]) . "(SET),";
  print " <span style='color:orange' > " . $worker_arr[2] . " </span> " . number_format($work_sum[2]) . "(SET),";
  print " <span style='color:green' > " . $worker_arr[3] . " </span> " . number_format($work_sum[3]) . "(SET),";
    print " <span style='color:blue' > " . $worker_arr[4] . " </span> " . number_format($work_sum[4]) . "(SET),";
  print " <span style='color:orange' > " . $worker_arr[5] . " </span> " . number_format($work_sum[5]) . "(SET),";
  print " <span style='color:green' > " . $worker_arr[6] . " </span> " . number_format($work_sum[6]) . "(SET),";
  
  	
 ?>
   <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
   <input id="display_sel" name="display_sel" type='hidden' value='<?=$display_sel?>' >
			<br><br>  
           <canvas id="myChart" width="500" height="300"></canvas> 		  
		    </div>
			<div id="tmp right" style="float:left;width:350px;height:300px;margin-top:70px;margin-left:20px;"> 	       
		    <h2> 시공비 추정금액 (단위:만원) </h2> <br>
			<?php
			  print " <h2><span style='color:red' > " . $worker_arr[0] . " &nbsp;&nbsp; </span> " . "   " . number_format($work_sum[0]*8) . "&nbsp;(만원) <br/><br/>";
			  print " <span style='color:blue' > " . $worker_arr[1] . "  &nbsp;&nbsp; </span> " . "   " . number_format($work_sum[1]*8) . "&nbsp;(만원) <br/><br/>";
			  print " <span style='color:orange' > " . $worker_arr[2] . "  &nbsp;&nbsp; </span> " . "   " . number_format($work_sum[2]*8) . "&nbsp;(만원) <br/><br/>";
			  print " <span style='color:green' > " . $worker_arr[3] . "  &nbsp;&nbsp;&nbsp;&nbsp; </span> " . "   " . number_format($work_sum[3]*8) . "&nbsp;(만원) <br/><br/>";
			  print " <span style='color:purple' > " . $worker_arr[4] . " &nbsp;&nbsp;  </span> " . "   " . number_format($work_sum[4]*8) . "&nbsp;(만원) <br/><br/>";
			  print " <span style='color:blue' > " . $worker_arr[5] . " &nbsp;&nbsp;  </span> " . "   " . number_format($work_sum[5]*8) . "&nbsp;(만원) <br/><br/>";
			  print " <span style='color:orange' > " . $worker_arr[6] . "  &nbsp;&nbsp; </span> " . "   " . number_format($work_sum[6]*8) . "&nbsp;(만원) <br/></h2>";
           ?>		   
   		    </div>
     </div>   

	 </div>   
    </div> <!-- end of tmp div -->


  


<script>

/* Checkbox change event */
$('input[name="chart_sel"]').change(function() {
    // 모든 radio를 순회한다.
    $('input[name="chart_sel"]').each(function() {
        var value = $(this).val();              // value
        var checked = $(this).prop('checked');  // jQuery 1.6 이상 (jQuery 1.6 미만에는 prop()가 없음, checked, selected, disabled는 꼭 prop()를 써야함)
        // var checked = $(this).attr('checked');   // jQuery 1.6 미만 (jQuery 1.6 이상에서는 checked, undefined로 return됨)
        // var checked = $(this).is('checked');
        var $label = $(this).next(); 
        if(checked)  {
           $("#display_sel").val(value);
	       document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
		}

    });
});
					
    var worker_arr = <?php echo json_encode($worker_arr);?> ;
    var work_done = <?php echo json_encode($work_done);?> ;
    var work_sum = <?php echo json_encode($work_sum);?> ;
	var ctx = document.getElementById('myChart');
    var chart_type = document.getElementById('display_sel').value;

	var myChart = new Chart(ctx, {
		type: chart_type,
		data: {
			labels: [worker_arr[0], worker_arr[1], worker_arr[2],worker_arr[3], worker_arr[4], worker_arr[5],worker_arr[6] ],
			datasets: [{
				label: '',
				data: [work_sum[0], work_sum[1], work_sum[2],work_sum[3], work_sum[4], work_sum[5],work_sum[6] ],
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(205, 100, 25, 0.2)',
					'rgba(25, 66, 200, 0.2)',
					'rgba(95, 452, 60, 0.2)',
					'rgba(113, 62, 55, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',					
					'rgba(255, 159, 64, 0.2)'					
				],
				borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(205, 100, 25, 1)',
					'rgba(25, 66, 200, 1)',
					'rgba(95, 452, 60, 1)',
					'rgba(113, 62, 55, 1)',
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
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

<script>
/* $(document).ready(function() { 
	$("input:radio[name=separate_date]").click(function() { 
	process_list(); 
	}) 
); */

  $(function() {
     $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
});  
$(function () {
            $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#up_fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#up_todate").datepicker({ dateFormat: 'yy-mm-dd'});			
			
});
 
 function up_pre_year(){   // 윗쪽 전년도 추출
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
yyyy=yyyy-1;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

document.getElementById("up_fromdate").value = frompreyear;
document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
}  
 
function pre_year(){   // 전년도 추출
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
yyyy=yyyy-1;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
}  

function up_pre_month(){    // 윗쪽 전월
	document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10) {
    dd='0'+dd;
} 

mm=mm-1;
if(mm<1) {
    mm='12';
} 
if(mm<10) {
    mm='0'+mm;
} 
if(mm>=12) {
    yyyy=yyyy-1;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function three_month_ago(){    // 석달전
			// document.getElementById('search').value=null; 
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			if(dd<10) {
				dd='0'+dd;
			} 

			mm=mm-3;  // 전전전월
			if(mm<-1) {
				mm='11';
			} 			
			if(mm<1) {
				mm='12';
			} 
			if(mm<10) {
				mm='0'+mm;
			} 
			if(mm>=12) {
				yyyy=yyyy-1;
			} 


			frompreyear = yyyy+'-' + mm+'-01';

			var tmp=0;
				  
			switch (Number(mm)) {
				
				case 1 :
				case 3 :
				case 5 :
				case 7 :
				case 8 :
				case 10 :
				case 12 :
				  tmp=31 ;
				  break;
				case 2 :   
				   tmp=28;
				   break;
				case 4 :
				case 6 :
				case 9 :
				case 11:
				   tmp=30;
				   break;
			}  	  

			topreyear = yyyy + '-' + mm + '-' + tmp ;

				document.getElementById("fromdate").value = frompreyear;
				document.getElementById("todate").value = topreyear;
				document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function  prepre_month(){    // 전전월
			// document.getElementById('search').value=null; 
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			if(dd<10) {
				dd='0'+dd;
			} 

			mm=mm-2;  // 전전월
			if(mm<1) {
				mm='12';
			} 
			if(mm<10) {
				mm='0'+mm;
			} 
			if(mm>=12) {
				yyyy=yyyy-1;
			} 


			frompreyear = yyyy+'-' + mm+'-01';

			var tmp=0;
				  
			switch (Number(mm)) {
				
				case 1 :
				case 3 :
				case 5 :
				case 7 :
				case 8 :
				case 10 :
				case 12 :
				  tmp=31 ;
				  break;
				case 2 :   
				   tmp=28;
				   break;
				case 4 :
				case 6 :
				case 9 :
				case 11:
				   tmp=30;
				   break;
			}  	  

			topreyear = yyyy + '-' + mm + '-' + tmp ;

				document.getElementById("fromdate").value = frompreyear;
				document.getElementById("todate").value = topreyear;
				document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

 
function pre_month(){    // 전월
//	document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10) {
    dd='0'+dd;
} 

mm=mm-1;
if(mm<1) {
    mm='12';
} 
if(mm<10) {
    mm='0'+mm;
} 
if(mm>=12) {
    yyyy=yyyy-1;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function up_this_year(){   // 윗쪽 당해년도
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
fromdate1=frompreyear;
todate1=topreyear;
document.getElementById('view_table').value="search"; 
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function this_year(){   // 아래쪽 당해년도
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
fromdate1=frompreyear;
todate1=topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function up_this_month(){   // 윗쪽 당해월
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function this_month(){   // 당해월
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function From_tomorrow(){   // 익일 이후
var today = new Date();
var dd = today.getDate()+1;  // 하루를 더해준다. 익일
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 
frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-12-31';	
    document.getElementById("fromdate").value = frompreyear;   
    document.getElementById("todate").value = topreyear;       
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 



function Fromthis_today(){   // 금일이후
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function up_this_today(){   // 윗쪽 날짜 입력란 금일
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function this_today(){   // 금일
document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function this_tomorrow(){   // 익일

// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate()+1;
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function process_list(){   // 접수일 출고일 라디오버튼 클릭시

// document.getElementById('search').value=null; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function exe_view_table(){   // 출고현황 검색을 클릭시 실행

document.getElementById('view_table').value="search"; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}

  </script> 


  </body>

  </html>