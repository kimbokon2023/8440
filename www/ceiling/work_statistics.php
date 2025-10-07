<?php include getDocumentRoot() . '/session.php';   

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }  
   
$title_message = "본천장/LC 제조통계";
   
 ?>
 
<?php include getDocumentRoot() . '/load_header.php' ?>

<title>  <?=$title_message?></title>

<!-- Dashboard CSS -->
<link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />

<style>
/* Light & Subtle Theme - Ceiling Work Statistics */
/* ============================================= */

body {
    background: var(--gradient-primary);
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

/* Modern Management Card */
.modern-management-card,
.card {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.modern-management-card:hover,
.card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

.card-header {
    background: var(--dashboard-secondary) !important;
    color: var(--dashboard-text) !important;
    border-bottom: 1px solid var(--dashboard-border) !important;
    border-radius: 12px 12px 0 0 !important;
    padding: 1rem !important;
    text-align: center;
    font-weight: 600;
}

/* Tables - Light & Subtle Theme */
.table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--dashboard-shadow);
    margin-bottom: 1rem;
}

.table th {
    background: #f0fbff !important;
    color: var(--dashboard-text) !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    border: 1px solid var(--dashboard-border) !important;
    padding: 0.75rem !important;
    text-align: center;
    vertical-align: middle;
}

.table td {
    font-size: 0.9rem !important;
    padding: 0.75rem !important;
    border: 1px solid var(--dashboard-border) !important;
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--dashboard-text) !important;
    vertical-align: middle;
}

.table tbody tr:hover td {
    background-color: var(--dashboard-hover) !important;
    transition: background-color 0.2s ease;
}

.table-primary > th {
    background: #f0fbff !important;
    color: var(--dashboard-text) !important;
    border: 1px solid var(--dashboard-border) !important;
}

/* Form Controls */
.form-control {
    border: 1px solid var(--dashboard-border);
    background: white;
    color: var(--dashboard-text);
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
    outline: none;
}

/* Input Group */
.input-group-text {
    background: var(--dashboard-secondary) !important;
    color: var(--dashboard-text) !important;
    border: 1px solid var(--dashboard-border) !important;
    border-radius: 6px !important;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Radio Buttons */
input[type="radio"] {
    margin-left: 0.5rem;
    margin-right: 0.75rem;
    accent-color: var(--dashboard-accent);
}

/* Text Colors */
.text-primary {
    color: var(--dashboard-text) !important;
}

/* Canvas Container */
canvas {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Responsive Design */
@media (max-width: 768px) {
    .card {
        margin: 0.5rem 0;
    }

    .input-group {
        flex-direction: column;
        gap: 0.5rem;
    }

    .input-group-text {
        width: 100%;
    }
}
</style>

</head>

		 
<body>

  <?php require_once(includePath('myheader.php')); ?>   

 <?php 
 
  if(isset($_REQUEST["load_confirm"]))   // 초기 당월 차트보이도록 변수를 저장하고 다시 부르면 실행되지 않도록 하기 위한 루틴
	 $load_confirm=$_REQUEST["load_confirm"];	 
  
  if(isset($_REQUEST["display_sel"]))   //목록표에 제목,이름 등 나오는 부분
		$display_sel=$_REQUEST["display_sel"];	 
	 else
		 $display_sel='bar';	

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

// 올해를 날자기간으로 설정

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}

  if(isset($_REQUEST["load_confirm"]))   // 초기 당월 차트보이도록 변수를 저장하고 다시 부르면 실행되지 않도록 하기 위한 루틴
	 $load_confirm=$_REQUEST["load_confirm"];	 
  
  if(isset($_REQUEST["display_sel"]))   //목록표에 제목,이름 등 나오는 부분
	 $display_sel=$_REQUEST["display_sel"];	 
	 else
		 	 $display_sel='bar';	

  if(isset($_REQUEST["item_sel"]))   //목록표에 제목,이름 등 나오는 부분
	 $item_sel=$_REQUEST["item_sel"];	 
	 else
		 	 $item_sel="작년대비 월비교";	

	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";        
 
 if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
 $find=$_REQUEST["find"]; 
  

if(isset($_REQUEST["search"]))   
    $search=$_REQUEST["search"];

$orderby=" order by workday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분


$sql="select * from mirae8440.ceiling where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
$sqltag = '';

if($search!="")
	{ 
		  $sqltag = "  and ( (workplacename like '%$search%' )  or (firstordman like '%$search%' )   or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
		  $sqltag .= " or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ))  " ;
	 }    
 
 
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

$counter=0;
$sum1=0;
$sum2=0;
$sum3=0;
$sum4=0;
$sum5=0;

 try{   
   // $sql="select * from mirae8440.ceiling"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
   
			  $workday=$row["workday"];
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];
				  
			  $sum2 += (int)$bon_su;
			  $sum3 += (int)$lc_su;			  
			  $sum6 += (int)$bon_su + (int)$lc_su ;		
	      $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$item_arr = array();	
$work_sum = array();	
$month_sum = array();	

$item_arr[0]='결합단위';
$item_arr[1]='본천장';
$item_arr[2]='L/C';
$item_arr[3]='기타';
$item_arr[4]='공기청정기';

$work_sum[0]=$sum1;
$work_sum[1]=$sum2;
$work_sum[2]=$sum3;
$work_sum[3]=$sum4;
$work_sum[4]=$sum5;
$work_sum[5]=$sum6;

if($item_sel=='월별비교') 
		{
		$month_count=0;      // 월별 차트 통계 내는 부분
		while($month_count<12)		 
		{	
	
					$year=substr(date("Y-m-d",time()),0,4) ;
					
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
					
					$sql="select * from mirae8440.ceiling where ( workday between date('$month_fromdate') and date('$month_todate') )" . $sqltag ;
					require_once("../lib/mydb.php");
					$counter=0;
					$sum1=0;
					$sum2=0;
					$sum3=0;

					try{  
					   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
					   $rowNum = $stmh->rowCount();  
					   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	

						  $workday=$row["workday"];						  
						  $bon_su=$row["bon_su"];			  
						  $lc_su=$row["lc_su"];			  						  
						  $type = $row["type"];
		   
						 $workitem="";
						 if($bon_su!="")   {
								$sum2 += (int)$bon_su;						
								}
						 if($lc_su!="") {												   
								$sum3 += (int)$lc_su;												
								}							
					   
							$sum_arr[$counter]=$workitem;		
						   $counter++;	   
						 } 	 
					   } catch (PDOException $Exception) {
						print "오류: ".$Exception->getMessage();
					}  

					$month_sum[$month_count]= $sum2 + $sum3 ;
					$month_count++;					
		}
}

if($item_sel=='작년대비 월비교') 
		{
			$year_count=0;
			$date_count=0;    // 24개월을 기준으로 데이터를 작성하는 합계변수 2020년1월 2021년1월 이런식으로 계산할 것임.
			$year_sum = array();	
			$year=substr(date("Y-m-d",time()),0,4) -1;				
				
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
							
							$sql="select * from mirae8440.ceiling where (workday between date('$month_fromdate') and date('$month_todate'))" . $sqltag ;
							
							$counter=0;
							$sum1=0;
							$sum2=0;
							$sum3=0;
							$sum4=0;
							$sum5=0;

							 try{  
								$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
							   $rowNum = $stmh->rowCount();  
							   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	

								  $workday=$row["workday"];								  
								  $bon_su=$row["bon_su"];			  
								  $lc_su=$row["lc_su"];			  								  
							   
									 $workitem="";
									 if($bon_su!="")   {
											$sum2 += (int)$bon_su;						
											}
									 if($lc_su!="") {												   
											$sum3 += (int)$lc_su;												
											}									 
							   
									$sum_arr[$counter]=$workitem;		
								   $counter++;	   
								 } 	 
							   } catch (PDOException $Exception) {
								print "오류: ".$Exception->getMessage();
							}  

							$year_sum[$date_count]=  $sum2 + $sum3 ;
							$month_count++;
							$date_count++;
				   }
			  $year_count++;
			  $year++;	 
			}
}
if($item_sel=='1년~2년전 년도비교') 
		{
			$year_count=0;
			$date_count=0;    // 24개월을 기준으로 데이터를 작성하는 합계변수 2020년1월 2021년1월 이런식으로 계산할 것임.
			$year_sum = array();	
			$year=substr(date("Y-m-d",time()),0,4) -2;	// 2년도 뺌			
				
			while($year_count<2)	
			  {
				$month_count=0;      // 2년 년도비교 차트 통계 내는 부분
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
							
							
							$sql="select * from mirae8440.ceiling where (workday between date('$month_fromdate') and date('$month_todate') )" . $sqltag ;
							
							require_once("../lib/mydb.php");
							$counter=0;
							$sum1=0;
							$sum2=0;
							$sum3=0;
							$sum4=0;
							$sum5=0;

							 try{  
								$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
							   $rowNum = $stmh->rowCount();  
							   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	

								  $workday=$row["workday"];
								  $num=$row["num"];
								  $su=$row["su"];			  
								  $bon_su=$row["bon_su"];			  
								  $lc_su=$row["lc_su"];			  
								  $etc_su=$row["etc_su"];			  
								  $air_su=$row["air_su"];
							   
											 $workitem="";
											 if($su!="")   {
													$sum1 += (int)$su;
																}
											 if($bon_su!="")   {
													$sum2 += (int)$bon_su;						
													}
											 if($lc_su!="") {												   
													$sum3 += (int)$lc_su;												
													}									 
											if($etc_su!="") {												   
													$sum4 += (int)$etc_su;												
													}									 
											if($air_su!="") {												   
													$sum5 += (int)$air_su;												
													}
								   $counter++;	   
								 } 	 
							   } catch (PDOException $Exception) {
								print "오류: ".$Exception->getMessage();
							}  

							$year_sum[$date_count]= $sum1 + $sum2 + $sum3 ;
							
							$month_count++;
							$date_count++;
				   }
			  $year_count++;
			  $year++;	 
			}
}
?>


<form name="board_form" id="board_form"  method="post" action="work_statistics.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">
<div class="container">
	<div class="modern-management-card mt-2 mb-2">
		<div class="card-header">
			<h3 style="margin: 0; font-size: 1.2rem;"><?=$title_message?></h3>
		</div>
		<div class="card-body">					

<div class="row"> 		  
	<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
	<!-- 기간설정 칸 -->
	 <?php include getDocumentRoot() . '/setdate.php' ?>
	</div>
</div>
    
<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 	

		  <?php
		

		switch ($display_sel) {
			case   "doughnut"     :   $chartchoice[0]='checked'; break;
			case   "bar"     :   $chartchoice[1]='checked'; break;
			case   "line"     :   $chartchoice[2]='checked'; break;
			case   "radar"     :   $chartchoice[3]='checked'; break;
			case   "polarArea"     :   $chartchoice[4]='checked'; break;
		}
		
        $itemArr = ["작년대비 월비교","1년~2년전 년도비교","월별비교","종류별비교" ]	;

		switch ($item_sel) {
			case  $itemArr[0]     :     $item_sel_choice[0]='checked'; break;			
			case  $itemArr[1]     :     $item_sel_choice[1]='checked'; break;			
			case  $itemArr[2]    :     $item_sel_choice[2]='checked'; break;			
			case  $itemArr[3]    :     $item_sel_choice[3]='checked'; break;			
		}
		
 ?>
   
   <input id="item_sel" name="item_sel" type='hidden' value='<?=$item_sel?>' > 
    <div class="input-group p-2 mb-2 justify-content-center align-items-center ">   
	 <span class='input-group-text justify-content-center align-items-center ' >  
	 &nbsp;  <?=$itemArr[0]?> <input type="radio" <?=$item_sel_choice[0]?> name="item_sel" value="<?=$itemArr[0]?>">
	 &nbsp;  <?=$itemArr[1]?> <input type="radio" <?=$item_sel_choice[1]?> name="item_sel" value="<?=$itemArr[1]?>">  
   	 &nbsp;  <?=$itemArr[2]?> <input type="radio" <?=$item_sel_choice[2]?> name="item_sel" value="<?=$itemArr[2]?>">   			
   	 &nbsp;  <?=$itemArr[3]?> <input type="radio" <?=$item_sel_choice[3]?> name="item_sel" value="<?=$itemArr[3]?>">  
	</span>	
		&nbsp; &nbsp; 	
		<span class='input-group-text justify-content-center align-items-center ' >  		
		   <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
		   <input id="display_sel" name="display_sel" type='hidden' value='<?=$display_sel?>' >
   			&nbsp; 도넛 <input type="radio" <?=$chartchoice[0]?> name="chart_sel" value="doughnut">
			&nbsp; 바 <input type="radio" <?=$chartchoice[1]?> name="chart_sel" value="bar">
			&nbsp; 라인 <input type="radio" <?=$chartchoice[2]?> name="chart_sel" value="line">
			&nbsp; 레이더 <input type="radio" <?=$chartchoice[3]?> name="chart_sel" value="radar">
			&nbsp; Polar Area <input type="radio" <?=$chartchoice[4]?> name="chart_sel" value="polarArea"> 
				</span>
			</div>
		</div>
	
	
	
	<div class="row "> 	
	<div class="col-sm-7"> 	  
		<div class="card"> 	
			<div class="card-body"> 	
        <canvas id="myChart" width="700" height="500"></canvas> 		   
			</div>
		</div>
	 </div>
	 <div class="col-sm-5"> 
	 	<div class="card"> 	
			<div class="card-body"> 
	   <?php
		if ($item_sel == "종류별비교") {
			echo "<span class='text-primary'> 수량환산 : 기타 및 공기청정기 제외 </span>";
			
			echo "<table class='table table-bordered' style='width:80%;'>";
			echo ' <thead class="table-primary">';
			echo "<tr>";
			echo "<th class='text-center'>항목</th>";
			echo "<th class='text-center'>제작수량</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

			$colors = ['black', 'red', 'blue', 'orange', 'orange', 'orange'];
			$total_quantity = $work_sum[5];

			echo "<tr>";
			echo "<td class='text-center' style='color:{$colors[0]}'><strong>총 수량합계</strong></td>";
			echo "<td class='text-end'><strong>" . number_format($total_quantity) . " (SET)</strong></td>";
			echo "</tr>";

			for ($i = 0; $i < 5; $i++) {
				echo "<tr>";
				echo "<td class='text-center' style='color:{$colors[$i + 1]}'>" . $item_arr[$i] . "</td>";
				echo "<td class='text-end'>" . number_format($work_sum[$i]) . " (SET)</td>";
				echo "</tr>";
			}

			echo "</tbody>";
			echo "</table>";
		}

	if ($item_sel == "월별비교") {
		
		echo "<span class='text-primary'> 수량환산 : 기타 및 공기청정기 제외  </span>";
		echo "<table class='table table-bordered ' style='width:70%;'>";
		echo ' <thead class="table-primary">';
		echo "<tr>";
		echo "<th class='text-center' style='width:30%;'>월</th>";
		echo "<th class='text-center' style='width:20%;'>제작수량</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$months = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];

		for ($i = 0; $i < count($months); $i++) {
			echo "<tr>";
			echo "<td class='text-center'>" . $months[$i] . "</td>";
			echo "<td class='text-end'>" . number_format($month_sum[$i]) . " (SET)</td>";
			echo "</tr>";
		}

		$total_sum = array_sum($month_sum);

		echo "<tr>";
		echo "<td class='text-center' style='color:black'><strong>전체 합계</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_sum) . " (SET)</strong></td>";
		echo "</tr>";

		echo "</tbody>";
		echo "</table>";
	}

if ($item_sel == "작년대비 월비교") 
{
    echo "<span class='text-primary'> 수량환산 : 기타 및 공기청정기 제외  </span>";
    echo "<table class='table table-bordered' style='width:80%;'>";
    echo ' <thead class="table-primary">';
    echo "<tr>";

	$month_fromdate = sprintf("%04d", $month_todate);
	$month_fromdate = intval($month_fromdate) -1 ;
	$month_todate = sprintf("%04d", $month_todate);	
	
		echo "<th class='text-center'>해당월</th>";
		echo "<th class='text-center'>".$month_fromdate."년 제작</th>";
		echo "<th class='text-center'>".$month_todate."년 제작</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $months = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];

    $lastYearTotal = 0; // 전년의 총합
    $thisYearTotal = 0; // 올해의 총합

    for ($i = 0; $i < count($months); $i++) {
        $lastYearTotal += $year_sum[$i];
        $thisYearTotal += $year_sum[$i + 12];
        
        echo "<tr>";
        echo "<td class='text-center'>" . $months[$i] . "</td>";
        echo "<td class='text-end'>" . number_format($year_sum[$i]) . " (SET)</td>";
        echo "<td class='text-end'>" . number_format($year_sum[$i + 12]) . " (SET)</td>";
        echo "</tr>";
    }

    echo "<tr>";
    echo "<td class='text-center' style='color:black'><strong> 총합</strong></td>";
    echo "<td class='text-end' ><strong>" . number_format($lastYearTotal) . " (SET)</strong></td>";    
    echo "<td class='text-end' ><strong>" . number_format($thisYearTotal) . " (SET)</strong></td>";
    echo "</tr>";

    echo "</tbody>";
    echo "</table>";
}

			  
if ($item_sel == "1년~2년전 년도비교") 
{
    echo "<span class='text-primary'> 수량환산 : 기타 및 공기청정기 제외  </span>";
    echo "<table class='table table-bordered' style='width:80%;'>";
    echo ' <thead class="table-primary">';
    echo "<tr>";

	$month_fromdate = sprintf("%04d", $month_todate);
	$month_fromdate = intval($month_fromdate) -1 ;
	$month_todate = sprintf("%04d", intval($month_todate)  );	
    
    echo "<th class='text-center'>해당월</th>";
	echo "<th class='text-center'>".$month_fromdate."년 제작</th>";
	echo "<th class='text-center'>".$month_todate."년 제작</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $months = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];

    $yearBeforeLastTotal = 0; // 전전년의 총합
    $lastYearTotal = 0; // 전년의 총합

    for ($i = 0; $i < count($months); $i++) {
        $yearBeforeLastTotal += $year_sum[$i];
        $lastYearTotal += $year_sum[$i + 12];
        
        echo "<tr>";
        echo "<td class='text-center'>" . $months[$i] . "</td>";
        echo "<td class='text-end'>" . number_format($year_sum[$i]) . " (SET)</td>";
        echo "<td class='text-end'>" . number_format($year_sum[$i + 12]) . " (SET)</td>";
        echo "</tr>";
    }
	
    echo "<tr>";
    echo "<td class='text-center' style='color:black'><strong> 총합</strong></td>";
    echo "<td class='text-end' ><strong>" . number_format($yearBeforeLastTotal) . " (SET)</strong></td>";    
    echo "<td class='text-end' ><strong>" . number_format($lastYearTotal) . " (SET)</strong></td>";
    echo "</tr>";	

    echo "</tbody>";
    echo "</table>";
}
?>    
	 </div> <!--card-body-->
	</div> <!--card -->
	</div>	
	</div>
</div> <!--card-body-->
</div> <!--card -->

<!--모델별 통계 -->
<div class="modern-management-card mt-2 mb-5">
	<div class="card-header">
		<h3 style="margin: 0; font-size: 1.2rem;">조명천장 모델별 제조통계</h3>
	</div>
	<div class="card-body">		
<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 	
				 
	
</div>
	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 		
  <?php
// Define your SQL query based on search and date conditions

$now = date("Y-m-d");

$sql = "SELECT * FROM mirae8440.ceiling WHERE workday BETWEEN DATE(:fromdate) AND DATE(:Transtodate) " . $sqltag;

require_once("../lib/mydb.php");
$pdo = db_connect();

// Prepare the SQL query
$stmt = $pdo->prepare($sql);

// Bind parameters
$stmt->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
$stmt->bindParam(':Transtodate', $Transtodate, PDO::PARAM_STR);

// if ($search != "") {
    // $searchParam = '%' . $search . '%';
    // $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
// }

// Execute the query
$stmt->execute();

// Fetch data into an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the statement
$stmt->closeCursor();

// Process data to calculate statistics  
$statisticsData = array(); 

foreach ($data as $row) {
    $type = $row["type"];
    $lc_su = intval($row["lc_su"]);

    if (!isset($statisticsData[$type])) {
        $statisticsData[$type] = 0;
    }

    $statisticsData[$type] += $lc_su;
}

// Sort the statistics data in descending order of values
arsort($statisticsData);
$topStatistics = array_slice($statisticsData, 0, 30); // Select top 30

// Corrected JSON encoding
$jsStatisticsData = json_encode($topStatistics);
?>


<!-- Your HTML and Chart.js code -->

<div class="col-lg-6">
	<div class="card"> 	
		<div class="card-body"> 
			<canvas id="myChart_model" width="1400" height="2150"></canvas>
		</div> <!--card-body-->
   </div> <!--card -->
</div>

<script>
 

var statisticsData = <?php echo $jsStatisticsData; ?>;
var ctx = document.getElementById("myChart_model").getContext("2d");
var colors = [
    "rgba(75, 192, 192, 0.2)",
    "rgba(255, 99, 132, 0.2)",
    "rgba(54, 162, 235, 0.2)",
	'rgba(153, 102, 255, 0.2)',
	'rgba(205, 100, 25, 0.2)',
	'rgba(25, 66, 200, 0.2)',
	'rgba(95, 452, 60, 0.2)',
	'rgba(113, 62, 55, 0.2)',
	'rgba(255, 99, 132, 0.2)',
	'rgba(54, 162, 235, 0.2)'	
];

var labels = Object.keys(statisticsData);

var myChart_model = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [{			
            data: Object.values(statisticsData),
            backgroundColor: colors.slice(0, labels.length),
            borderColor: colors.slice(0, labels.length),
            borderWidth: 1
        }]
    },
   options: {
    responsive: true,
    scales: {
        y: {
            beginAtZero: true
        }
    },
    plugins: {
        legend: {
            display: false // 범례를 숨김
        }
    }  
}


});


</script>

<div class="col-lg-5 p-2 m-2">
<div class="card"> 	
	<div class="card-body"> 
    <table class="table table-bordered table-sm">
        <thead class="table-primary">
            <tr>
                <th class="text-center">Type</th>
                <th class="text-center">Total </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalSum = 0; // 변수 초기화
            
            foreach ($topStatistics as $type => $totalLC_SU) {
                $totalSum += $totalLC_SU; // 각 행의 값을 총합에 추가
            ?>
                <tr>
                    <td class="text-center"><?php echo $type; ?></td>
                    <td class="text-center"><?php echo $totalLC_SU; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td class="text-center"><strong>총합계</strong></td>
                <td class="text-center"><strong><?php echo $totalSum; ?></strong></td>
            </tr>
        </tbody>
    </table>
   </div> <!--card-body-->
   </div> <!--card -->
   
	</div>
     
   </div> <!--card-body-->
   </div> <!--card -->
   
   </div> <!--container-->	 
	 
	 </form>
  </body>
  
<script> 

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
							labels: [item_arr[0], item_arr[1], item_arr[2], item_arr[3], item_arr[4]],
							datasets: [{
								label: '#천장/LC 제작수량 합계 ',
								data: [work_sum[0], work_sum[1], work_sum[2], work_sum[3], work_sum[4]],
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
								label: '#천장/LC 제작수량 합계 ',
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
	
if(item_type=='작년대비 월비교') 
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
								label: '# 천장/LC 작년 제작수량 합계 , # 올해 제작수량',
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
		
if(item_type=='1년~2년전 년도비교') 
	{
		item_arr[0] = '전전년1월';
		item_arr[1] = '전년1월';
		item_arr[2] = '전전년2월';
		item_arr[3] = '전년2월';
		item_arr[4] = '전전년3월';
		item_arr[5] = '전년3월';
		item_arr[6] = '전전년4월';
		item_arr[7] = '전년4월';
		item_arr[8] = '전전년5월';
		item_arr[9] = '전년5월';
		item_arr[10] = '전전년6월';
		item_arr[11] = '전년6월';
		item_arr[12] = '전전년7월';
		item_arr[13] = '전년7월';
		item_arr[14] = '전전년8월';
		item_arr[15] = '전년8월';
		item_arr[16] = '전전년9월';
		item_arr[17] = '전년9월';
		item_arr[18] = '전전년10월';
		item_arr[19] = '전년10월';
		item_arr[20] = '전전년11월';
		item_arr[21] = '전년11월';
		item_arr[22] = '전전년12월';
		item_arr[23] = '전년12월';

		
					var myChart = new Chart(ctx, {
						type: chart_type,
						data: {
							labels: [item_arr[0], item_arr[1], item_arr[2],item_arr[3], item_arr[4], item_arr[5],item_arr[6], item_arr[7], item_arr[8],item_arr[9], item_arr[10],item_arr[11], item_arr[12], item_arr[13], item_arr[14],item_arr[15], item_arr[16], item_arr[17],item_arr[18], item_arr[19], item_arr[20],item_arr[21], item_arr[22],item_arr[23]  ],
							datasets: [{
								label: '# 천장/LC 전전년 제작수량 합계 , # 전년 제작수량',
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
	
	
	
</script>  


<script>
	$(document).ready(function(){
		saveLogData('Ceiling 제조통계'); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 


  </body>
 </html> 
 