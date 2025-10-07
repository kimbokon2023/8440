<?php

session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }
   
   
 ?>
  <!DOCTYPE HTML>
 <html>
 <head> 
 <meta charset="utf-8">

 <title> 미래기업 부적합(품질) 통계 </title>
  
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>	
	<script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="http://8440.co.kr/order/order.js"></script>
    <script src="http://8440.co.kr/common.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>	
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 	
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>	   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.js" integrity="sha512-79j1YQOJuI8mLseq9icSQKT6bLlLtWknKwj1OpJZMdPt2pFBry3vQTt+NZuJw7NSd1pHhZlu0s12Ngqfa371EA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- jqeury Zoom -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.6.1/jquery.zoom.min.js" integrity="sha512-xhvWWTTHpLC+d+TEOSX2N0V4Se1989D03qp9ByRsiQsYcdKmQhQ8fsSTX3KLlzs0jF4dPmq0nIzvEc3jdYqKkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/work.css">   
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="../css/common.css">
<link rel="stylesheet" type="text/css" href="../css/steel.css">
<link rel="stylesheet" type="text/css" href="../css/statistics.css">   
 
 <!-- 최초화면에서 보여주는 상단메뉴 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.css" integrity="sha512-VSD3lcSci0foeRFRHWdYX4FaLvec89irh5+QAGc00j5AOdow2r5MFPhoPEYBUQdyarXwbzyJEO7Iko7+PnPuBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
  
  <style>

.modal .modal-full {
  max-width: 94%;
}  
  
    .rotated {
  transform: rotate(90deg);
  -ms-transform: rotate(90deg); /* IE 9 */
  -moz-transform: rotate(90deg); /* Firefox */
  -webkit-transform: rotate(90deg); /* Safari and Chrome */
  -o-transform: rotate(90deg); /* Opera */
}


.bigPictureWrapper {
	position: absolute;
	display: none;
	justify-content: center;
	align-items: center;
	top:0%;
	width:100%;
	height:100%;
	background-color: gray; 
	z-index: 100;
	background:rgba(255,255,255,0.5);
}
.bigPicture {
	position: absolute;
	display:flex;
	justify-content: center;
	align-items: center;
}

.bigPicture img {
	height:100%; /*새로기준으로 꽉차게 보이기 */
}

@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");

fieldset.groupbox-border {
border: 1px groove #ddd !important; 
padding: 3 3 3 3 !important; 
margin: 3 3 3 3 !important; 
box-shadow: 0px 0px 0px 0px #000; 
} 

legend.groupbox-border { 
    background-color: #F0F0F0;
    color: #000;
    padding: 3px 6px;
font-size: 1.0em !important; 
font-weight: bold !important; 
text-align: left !important; 
border-bottom:none; 
}  

fieldset.groupbox1-border {
border: 1px groove #ddd !important; 
padding: 3 3 3 3 !important; 
margin: 3 3 3 3 !important; 
} 

legend.groupbox1-border { 
    background-color: #F0F0F0;
    color: #000;
    padding: 9px 9px;
font-size: 1.0em !important; 
font-weight: bold !important; 
text-align: left !important; 
border-bottom:none; 
}   


.rotated {
  transform: rotate(90deg);
  -ms-transform: rotate(90deg); /* IE 9 */
  -moz-transform: rotate(90deg); /* Firefox */
  -webkit-transform: rotate(90deg); /* Safari and Chrome */
  -o-transform: rotate(90deg); /* Opera */
}
  
a {
text-decoration:none;
  }	  

</style>

<body>

<div class="container-fluid">  
<div class="d-flex mb-1 justify-content-center">    
   <a href="../index.php"><img src="../img/toplogo.jpg" style="width:100%;" ></a>	
</div>

<? include '../myheader.php'; ?>   

</div>   
   

		
<div class="container-fluid">  
	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title"> 알림 </h4>
        </div>
        <div class="modal-body fs-5">
		
		<input type="hidden" name="saleprice_val" id="saleprice_val" > 
		<input type="hidden" name="cartsave" id="cartsave" > 
		
		 <input type="text" id="insertword" name="insertword" size="70" class="text-primary" value=""> 		 
		 
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
			<br>
			도면저장 폴더위치가 복사되었습니다. <br>
		    탐색기에 'Ctrl+v'로 붙여넣기 하세요!
		  <br>
			</div>		 
		 
		 
		  			
                    </div>			  
        <div class="modal-footer">
          <button id=closeModalBtn type="button" class="btn btn-default" >닫기</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
 


 <?php 
 
 
isset($_REQUEST["tabName"])  ? $tabName=$_REQUEST["tabName"] :  $tabName='';   // 신규데이터에 생성할때 임시저장키  
 
  if(isset($_REQUEST["load_confirm"]))   // 초기 당월 차트보이도록 변수를 저장하고 다시 부르면 실행되지 않도록 하기 위한 루틴
	 $load_confirm=$_REQUEST["load_confirm"];	 
  
  if(isset($_REQUEST["display_sel"]))   //목록표에 제목,이름 등 나오는 부분
	 $display_sel=$_REQUEST["display_sel"];	 
	 else
		 	 $display_sel='bar';	

  if(isset($_REQUEST["item_sel"]))   //목록표에 제목,이름 등 나오는 부분
	 $item_sel=$_REQUEST["item_sel"];	 
	 else
		 	 $item_sel='년도비교';	  
 

 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용 
 
  if(isset($_REQUEST["plan_output_check"])) 
	 $plan_output_check=$_REQUEST["plan_output_check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
	if(isset($_POST["plan_output_check"]))   
         $plan_output_check=$_POST["plan_output_check"]; // 미출고 리스트 POST사용  
	 else
		 $plan_output_check='0';
 
 if(isset($_REQUEST["output_check"])) 
	 $output_check=$_REQUEST["output_check"]; // 출고완료
   else
	if(isset($_POST["output_check"]))   
         $output_check=$_POST["output_check"]; // 출고완료
	 else
		 $output_check='0';
	 
 if(isset($_REQUEST["team_check"])) 
	 $team_check=$_REQUEST["team_check"]; // 시공팀미지정
   else
	if(isset($_POST["team_check"]))   
         $team_check=$_POST["team_check"]; // 시공팀미지정
	 else
		 $team_check='0';	 
	 
 if(isset($_REQUEST["measure_check"])) 
	 $measure_check=$_REQUEST["measure_check"]; // 미실측리스트
   else
	if(isset($_POST["measure_check"]))   
         $measure_check=$_POST["measure_check"]; // 미실측리스트
	 else
		 $measure_check='0';		 
  
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;
  }
  
  
  $sum=array(); 
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";        
 
 if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
 $find=$_REQUEST["find"]; 
  
// 기간을 정하는 구간
 
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

 // 당월을 날짜 기간으로 설정
 
	 if($fromdate=="")
	{
		$fromdate=substr(date("Y-m-d",time()),0,7) ;
		$fromdate=$fromdate . "-01";
	}
	if($todate=="")
	{
		$todate=substr(date("Y-m-d",time()),0,4) . "-12-31";
		$Transtodate=strtotime($todate.'+1 days');
		$Transtodate=date("Y-m-d",$Transtodate);
	}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}
 
  if(isset($_REQUEST["search"]))   
 $search=$_REQUEST["search"];

 $orderby=" order by workday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		

if($mode=="search"){
		  if($search==""){
					 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$fromdate') and date('$Transtodate'))" . $orderby;				  		  		   
			     }    
}
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

// print $search;
// print $sql;

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
   
if (!empty(trim($sql))) {   

 try{   
   // $sql="select * from mirae8440.work"; 		 
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
	
		   
		   $workday_arr[$counter]=$workday;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;
		   $delicompany_arr[$counter]=$delicompany;   
		   $delipay_arr[$counter]=$delipay;   
		   $secondord_arr[$counter]=$secondord;   
		   $worker_arr[$counter]=$worker;   
		   
   
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

 // print $fromdate . '   ' .  $Transtodate ;

if($item_sel=='월별비교') 
		{
		$month_count=0;      // 월별 차트 통계 내는 부분
		while($month_count<12)		 
		{	
	
					$year=substr($fromdate,0,4) ;
					
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
								   $counter++;	   
									$total_row++;
							   }
						 } 	 
					   } catch (PDOException $Exception) {
						print "오류: ".$Exception->getMessage();
					}  

					$month_sum[$month_count]= $sum1 + $sum2 + $sum3/4;

					$month_count++;

		}
}

if($item_sel=='년도비교') 
		{
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

}

			?>
		 
<div class="container-fluid">
		 
<div class="d-flex mb-1 justify-content-center">   		 
   <form name="board_form" id="board_form"  method="post" action="statistics.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
    <input type="hidden" id="tabName" name="tabName"  value="<?=$tabName?>" >
</div>
</div>

<div class="container">
    <h2> 원자재 생산/부적합(품질) 통계 DATA</h2>
	        <div class="card-header">
            <!-- 여기에 쟘 제조통계 관련 내용을 넣으세요. -->
				<div class="input-group p-2 mb-2">
					<button type="button" id="preyear" class="btn btn-secondary"   onclick='pre_year()' > 전년도 </button>  &nbsp;  	
					<button type="button" id="three_month" class="btn btn-secondary"  onclick='three_month_ago()' > M-3월 </button> &nbsp;  	
					<button type="button" id="prepremonth" class="btn btn-secondary"  onclick='prepre_month()' > 전전월 </button>	&nbsp;  
					<button type="button" id="premonth" class="btn btn-secondary"  onclick='pre_month()' > 전월 </button>  &nbsp; 	
					<button type="button" id="thismonth" class="btn btn-dark"  onclick='this_month()' > 당월 </button>	&nbsp;  	   
					<button type="button" id="thisyear" class="btn btn-dark"  onclick='this_year()' > 당해년도 </button> &nbsp;  			
				   <span class='input-group-text align-items-center' style='width:400 px;'>  
				   
				   <input type="text" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp; 부터 &nbsp;  
				   <input type="text" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">  &nbsp;  까지    </span>  &nbsp;
					<button type="button" id="searchBtn" class="btn btn-dark"  > 검색 </button>		   
				   </div>	
					
        </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active tablinks" onclick="openTab('manufacturingStatistics')">쟘 제조통계</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tablinks" onclick="openTab('qualityIssues')">원자재 품질불량</a>
        </li>
    </ul>

    <div id="manufacturingStatistics" class="tabcontent" style="display: block;">			
  
		  <?php
		

		switch ($display_sel) {
			case   "doughnut"     :   $chartchoice[0]='checked'; break;
			case   "bar"     :   $chartchoice[1]='checked'; break;
			case   "line"     :   $chartchoice[2]='checked'; break;
			case   "radar"     :   $chartchoice[3]='checked'; break;
			case   "polarArea"     :   $chartchoice[4]='checked'; break;
		}
		switch ($item_sel) {
			case   "년도비교"     :     $item_sel_choice[0]='checked'; break;			
			case   "월별비교"     :     $item_sel_choice[1]='checked'; break;
			case   "종류별비교"     :   $item_sel_choice[2]='checked'; break;
		}		
 ?>
   
   <input id="item_sel" name="item_sel" type='hidden' value='<?=$item_sel?>' > 
    <div class="input-group p-2 mb-2">   
	 <span class='input-group-text align-items-center' style='width:400 px;'>  
			&nbsp; 년도비교 <input type="radio" <?=$item_sel_choice[0]?> name="item_sel" value="년도비교">   
   			&nbsp; 월별비교 <input type="radio" <?=$item_sel_choice[1]?> name="item_sel" value="월별비교">
   			&nbsp; 종류별비교 <input type="radio" <?=$item_sel_choice[2]?> name="item_sel" value="종류별비교">		
	</span>
			</div>
   <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
   <input id="display_sel" name="display_sel" type='hidden' value='<?=$display_sel?>' >
   			&nbsp; 도넛 <input type="radio" <?=$chartchoice[0]?> name="chart_sel" value="doughnut">
			&nbsp; 바 <input type="radio" <?=$chartchoice[1]?> name="chart_sel" value="bar">
			&nbsp; 라인 <input type="radio" <?=$chartchoice[2]?> name="chart_sel" value="line">
			&nbsp; 레이더 <input type="radio" <?=$chartchoice[3]?> name="chart_sel" value="radar">
			&nbsp; Polar Area <input type="radio" <?=$chartchoice[4]?> name="chart_sel" value="polarArea"> 
			<br><br>
   <div id=firstgroup>
           <div id=second1> <canvas id="myChart" width="800" height="500"></canvas> 		   
		   </div> 
           <div id=second2>
		   <?php
		   if($item_sel=="종류별비교") 
		     {
		      print "<h5> 제작수량 (단위:SET) </h5>";		
			  print " <h6><span style='color:black'> 총 수량합계 : </span> " . number_format($all_sum) . " (SET), <br/><br/><br/>";
			  print " <span style='color:red' > " . $item_arr[0] . " </span> " . "   " . number_format($work_sum[0]) . "(SET), <br/><br/><br/>";
			  print " <span style='color:blue' > " . $item_arr[1] . " </span> " . "   " . number_format($work_sum[1]) . "(SET), <br/><br/><br/>";
			  print " <span style='color:orange' > " . $item_arr[2] . " </span> " . "   " . number_format($work_sum[2]) . "(SET) <br/><br/><br/>";
			 }
			 
		   if($item_sel=="월별비교") 
		     {
		      print "<h5> 제작수량, 쪽쟘 4 SET -> 와이드 1 SET로 산출함 (단위:SET) </h5>";			
			  print " <h6><span style='color:red' > 1월  </span> " . "   " . number_format($month_sum[0]) . "(SET) <br/><br/>";
			  print " <span style='color:blue' >  2월 </span> " . "   " . number_format($month_sum[1]) . "(SET) <br/><br/>";
			  print " <span style='color:orange' > 3월 </span> " . "   " . number_format($month_sum[2]) . "(SET) <br/><br/>";
				 print " <span style='color:green' > 4월 </span> " . "   " . number_format($month_sum[3]) . "(SET)<br/><br/>";
				  print " <span style='color:purple' > 5월 </span> " . "   " . number_format($month_sum[4]) . "(SET)<br/><br/>";
				  print " <span style='color:blue' > 6월 </span> " . "   " . number_format($month_sum[5]) . "(SET)<br/><br/>";
				  print " <span style='color:orange' > 7월 </span> " . "   " . number_format($month_sum[6]) . "(SET)<br/><br/>";
				  print " <span style='color:green' > 8월 </span> " . "   " . number_format($month_sum[7]) . "(SET)<br/><br/>";
				  print " <span style='color:purple' > 9월 </span> " . "   " .  number_format($month_sum[8]) . "(SET)<br/><br/>";
				  print " <span style='color:red' > 10월 </span> " . "   " . number_format($month_sum[9]) . "(SET)<br/><br/>";
				  print " <span style='color:blue' > 11월 </span> " . "   " .  number_format($month_sum[10]) . "(SET)<br/><br/>";
				  print " <span style='color:brown'> 12월 </span> " . "   " . number_format($month_sum[11]) . "(SET) </h6> " ;	  			  
			 }			 
			 
		   if($item_sel=="년도비교") 
		     {
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
			 }			 			 
			  
           ?>  
  
			</div>
		</div>
    </div>
     
    <div id="qualityIssues" class="tabcontent" style="display: none;">
   	
<?

$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("../steel/settings.ini",false);	
			  					   
$PO=$readIni['PO'];
$CR=$readIni['CR'];
$EGI=$readIni['EGI'];
$HL304=$readIni['HL304'];
$MR304=$readIni['MR304'];
$etcsteel=$readIni['etcsteel'];

$price_per_kg = [
    'CR' => $CR,
    'PO' => $PO,
    'EGI' => $EGI,
    '304 HL' => $HL304,
    '201 MR' => '3000',
    '304 MR' => $MR304,
    'etcsteel' => $etcsteel
];


require_once("../lib/mydb.php");
$pdo = db_connect();	


$sql = "SELECT item,spec,steelnum,bad_choice FROM mirae8440.steel WHERE outdate BETWEEN date('$fromdate') AND date('$Transtodate') AND (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음') ";		
 
$total_sum = 0;

try{  
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh

		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			
			
			$item = trim($row["item"]);
			$spec = trim($row["spec"]);
			$steelnum = $row["steelnum"];
			$bad_choice = $row["bad_choice"];

			$spec_parts = explode('*', $spec);
			$thickness = $spec_parts[0];
			$width = $spec_parts[1];
			$length = $spec_parts[2];

			$weight = $thickness * $width * $length/1000;
          if((int)$steelnum !== 0)  // 수량이 1이상일때 잔재는 제외함
		  {
			if (array_key_exists($item, $price_per_kg)) {
				$price = $price_per_kg[$item];
			} else {
				$price = $price_per_kg['etcsteel'];
			}

			$total_price = ($weight * $price * $steelnum * 7.93);
			$total_sum += $total_price;
		  }
		  else
		  {
			$total_price = 0 ;
			$total_sum += $total_price;			  
		  }
		}

} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}	
// 최종 결과를 포맷팅하여 출력
$formatted_total_sum = number_format($total_sum, 0, '.', ',');
echo " <br> <h2 class='text-danger' > 부적합 비용 : " . $formatted_total_sum . "원 </h2>";


// 차트를 구현하는 부분

$sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice FROM mirae8440.steel WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음') ORDER BY year, month";

//  다음 월별/년도별 합계를 계산하기 위해 다음과 같이 코드를 수정합니다.
$monthly_totals = [];

 
$total_sum = 0;

try{  
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh

while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
    // ... (기존 코드)
	
	        $item = trim($row["item"]);
			$spec = trim($row["spec"]);
			$steelnum = $row["steelnum"];
			$bad_choice = $row["bad_choice"];

			$spec_parts = explode('*', $spec);
			$thickness = $spec_parts[0];
			$width = $spec_parts[1];
			$length = $spec_parts[2];

			$weight = $thickness * $width * $length/1000;
          if((int)$steelnum !== 0)  // 수량이 1이상일때 잔재는 제외함
		  {
			if (array_key_exists($item, $price_per_kg)) {
				$price = $price_per_kg[$item];
			} else {
				$price = $price_per_kg['etcsteel'];
			}

			$total_price = ($weight * $price * $steelnum * 7.93);
			$total_sum += $total_price;
		  }
		  else
		  {
			$total_price = 0 ;
			$total_sum += $total_price;			  
		  }	

    $year = $row["year"];
    $month = $row["month"];

    if (!isset($monthly_totals["$year-$month"])) {
        $monthly_totals["$year-$month"] = 0;
    }

    $monthly_totals["$year-$month"] += $total_price;
}

} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}	




require_once("../lib/mydb.php");
$pdo = db_connect();	


$sql = "SELECT * FROM mirae8440.steel WHERE outdate BETWEEN date('$fromdate') AND date('$Transtodate') AND (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음') order by num desc ";		
 
$total_sum = 0;

try{  
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);    
	
	
		

?>
    <div id="mychart" style="width: 100%; height: 400px;"></div>

    <div class="container">
        <h2 class="my-4">부적합(품질불량) 세부내역 </h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">출고일</th>
                    <th scope="col">현장명</th>
                    <th scope="col">종류</th>
                    <th scope="col">규격</th>
                    <th scope="col">수량</th>
                    <th scope="col">발생비용</th>
                    <th scope="col">불량내용</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
					$total_price = 0;
					$item = trim($row["item"]);
					$spec = trim($row["spec"]);
					$steelnum = $row["steelnum"];
					$bad_choice = $row["bad_choice"];

					$spec_parts = explode('*', $spec);
					$thickness = $spec_parts[0];
					$width = $spec_parts[1];
					$length = $spec_parts[2];

					$spec_parts = explode('*', $spec);
					$thickness = $spec_parts[0];
					$width = $spec_parts[1];
					$length = $spec_parts[2];

					$weight = $thickness * $width * $length/1000;
				  if((int)$steelnum !== 0)  // 수량이 1이상일때 잔재는 제외함
				  {
					if (array_key_exists($item, $price_per_kg)) {
						$price = $price_per_kg[$item];
					} else {
						$price = $price_per_kg['etcsteel'];
					}

					$total_price = ($weight * $price * (int)$steelnum * 7.93);
					$formatted_total_sum = number_format($total_price, 0, '.', ',');
				  }
				   else
				   {
					   $total_price = 0 ;
					   $formatted_total_sum = number_format($total_price, 0, '.', ',');
				   }
				  

				?>
                    <tr>
                        <td><?= htmlspecialchars($row['outdate']) ?></td>
                        <td><?= htmlspecialchars($row['outworkplace']) ?></td>
                        <td><?= htmlspecialchars($row['item']) ?></td>
                        <td><?= htmlspecialchars($row['spec']) ?></td>
                        <td><?= htmlspecialchars($steelnum) ?></td>
                        <td class="text-right" ><?= $formatted_total_sum . '원' ?></td>
                        <td><?= htmlspecialchars($row['bad_choice']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
		
<?php
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}			


?>		
		
		
		
    </div>
        </div>			
		

	    <div class="container">
		   <br>
		   <br>
		</div>		
			
			
	 </form>
	 </div>

<script>

$(document).ready(function(){	

      // PHP에서 계산된 월별/년도별 합계를 JavaScript로 전달
        const monthly_totals = <?php echo json_encode($monthly_totals); ?>;

        // x축 라벨 및 데이터 시리즈 준비
        const categories = Object.keys(monthly_totals);
        const data = Object.values(monthly_totals).map(total => parseFloat(total.toFixed(2)));

        // 그래프 생성
        Highcharts.chart('mychart', {
            chart: {
                type: 'column'
            },
            title: {
                text: '원자재 월별 부적합 비용'
            },
            xAxis: {
                categories: categories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: '비용 (원)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:15px">{point.key}</span><table>',
					pointFormatter: function() {
						return '<tr><td style="color:' + this.series.color + ';padding:0">' + this.series.name + ': </td>' +
							'<td style="padding:0"><b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + ' 원</b></td></tr>';
					},
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: '부적합 비용',
                data: data
            }]
        });







      const tabName =  '<?php echo $tabName; ?>';  
	  
	  console.log(tabName);
	  
	  if(tabName == 'manufacturingStatistics')
		  openTab( 'manufacturingStatistics');
	       else
			    openTab('qualityIssues');

});

function openTab(tabName) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(tabName).style.display = "block";
	// currentTarget.className += " active";
	
	$('#tabName').val(tabName);	
	
}

$(function() {
 $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
	 
$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		 
	 
});  
$(function () {
            $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#up_fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#up_todate").datepicker({ dateFormat: 'yy-mm-dd'});			
			
});

      
   </script> 
  
   </div> 	   
  </div> <!-- end of wrap -->


  </body>

  
<script> 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
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
			  if(mm<0)					  
				mm='11';
			   else
				mm='12';
			} 
			if(mm<10) {
				mm='0'+mm;
			} 
			if(mm>=11) {       // 전전월은 11월
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

function pre_month(){    // 전월
			// document.getElementById('search').value=null; 
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

function this_month() { // 당해월
    // document.getElementById('search').value=null; 
    var today = new Date();
    var mm = today.getMonth() + 1; // January is 0!
    var yyyy = today.getFullYear();

    if (mm < 10) {
        mm = '0' + mm;
    }

    var fromThisMonth = yyyy + '-' + mm + '-01';

    var daysInMonth;
    switch (Number(mm)) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            daysInMonth = 31;
            break;
        case 2:
            daysInMonth = (yyyy % 4 === 0 && (yyyy % 100 !== 0 || yyyy % 400 === 0)) ? 29 : 28;
            break;
        case 4:
        case 6:
        case 9:
        case 11:
            daysInMonth = 30;
            break;
    }

    var toThisMonth = yyyy + '-' + mm + '-' + daysInMonth;

    document.getElementById("fromdate").value = fromThisMonth;
    document.getElementById("todate").value = toThisMonth;
    document.getElementById('board_form').submit(); // form의 검색버튼 누른 효과 
}


function this_year() { // 당해년도
    // document.getElementById('search').value=null; 
    var today = new Date();
    var yyyy = today.getFullYear();

    var fromThisYear = yyyy + '-01' + '-01';
    var toThisYear = yyyy + '-12' + '-31';

    document.getElementById("fromdate").value = fromThisYear;
    document.getElementById("todate").value = toThisYear;
    document.getElementById('board_form').submit(); // form의 검색버튼 누른 효과 
}

function dis_text()
{  
		var dis_text = '<?php echo $jamb_total; ?>';
		$("#dis_text").val(dis_text);
}	

function List_name(worker)
{	
		var worker; 				
		var name='<?php echo $user_name; ?>' ;
		 
			$("#search").val(worker);	
			$('#board_form').submit();		// 검색버튼 효과
}
</script>
  </html>
  
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

$('input[name="item_sel"]').change(function() {
    // 모든 radio를 순회한다.
    $('input[name="item_sel"]').each(function() {
        var value = $(this).val();              // value
        var checked = $(this).prop('checked');  // jQuery 1.6 이상 (jQuery 1.6 미만에는 prop()가 없음, checked, selected, disabled는 꼭 prop()를 써야함)
        // var checked = $(this).attr('checked');   // jQuery 1.6 미만 (jQuery 1.6 이상에서는 checked, undefined로 return됨)
        // var checked = $(this).is('checked');
        var $label = $(this).next(); 
        if(checked)  {
           $("#item_sel").val(value);
	       document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
		}

    });
});
					
    var item_arr = <?php echo json_encode($item_arr);?> ;
    var work_sum = <?php echo json_encode($work_sum);?> ;
    var month_sum = <?php echo json_encode($month_sum);?> ;
    var year_sum = <?php echo json_encode($year_sum);?> ;
	var ctx = document.getElementById('myChart');
    var chart_type = document.getElementById('display_sel').value;
    var item_type = document.getElementById('item_sel').value;

    if(item_type=='종류별비교') 
					var myChart = new Chart(ctx, {
						type: chart_type,
						data: {
							labels: [item_arr[0], item_arr[1], item_arr[2]],
							datasets: [{
								label: '#쟘 제작수량 합계 ',
								data: [work_sum[0], work_sum[1], work_sum[2]],
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
					

    if(item_type=='월별비교') 
	{
		item_arr[0] = '1월';
		item_arr[1] = '2월';
		item_arr[2] = '3월';
		item_arr[3] = '4월';
		item_arr[4] = '5월';
		item_arr[5] = '6월';
		item_arr[6] = '7월';
		item_arr[7] = '8월';
		item_arr[8] = '9월';
		item_arr[9] = '10월';
		item_arr[10] = '11월';
item_arr[11] = '12월';


		var myChart = new Chart(ctx, {
			type: chart_type,
			data: {
				labels: [item_arr[0], item_arr[1], item_arr[2],item_arr[3], item_arr[4], item_arr[5],item_arr[6], item_arr[7], item_arr[8],item_arr[9], item_arr[10],item_arr[11] ],
				datasets: [{
					label: '#쟘 제작수량 합계 ',
					data: [ month_sum[0], month_sum[1], month_sum[2],month_sum[3], month_sum[4], month_sum[5],month_sum[6], month_sum[7], month_sum[8],month_sum[9], month_sum[10],month_sum[11] ],
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
	}
	
if(item_type=='년도비교') 
	{
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
	}	
		
	
setTimeout(function() {
	
  dis_text();
}, 500);

</script>  