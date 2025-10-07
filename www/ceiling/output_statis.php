<?php\nrequire_once __DIR__ . '/../common/functions.php';
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

  if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          		 
		 sleep(1);
		  header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }  
   
$title_message = "본천장/LC 매출";

$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./estimate.ini",false);	
?>

<?php include getDocumentRoot() . '/load_header.php' ?>   

<title> <?=$title_message?> </title> 

<style>

.custom-radio .radio-input {
    opacity: 0;
    position: fixed;
    width: 0;
}

.custom-radio .radio-label {
    display: inline-block;
    background-color: #ddd;
    padding: 6px 12px;
    font-family: Arial, sans-serif;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.custom-radio .radio-input:checked + .radio-label {
    background-color: #007bff;
    color: white;
}

.custom-radio .radio-label:hover {
    background-color: #0056b3;
    cursor: pointer;
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

  if(isset($_REQUEST["item_sel"]))   //목록표에 제목,이름 등 나오는 부분
		$item_sel=$_REQUEST["item_sel"];	 
	 else
		$item_sel='년도비교';	
  
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
if(isset($_REQUEST["search"]))   
	$search=$_REQUEST["search"];

$orderby=" order by workday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분	

$sql="select * from mirae8440.ceiling ";	

if($search=="")
{
	$sql="select * from mirae8440.ceiling where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
}
else
{ 
	$sql ="select * from mirae8440.ceiling where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
	$sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$fromdate') and date('$Transtodate'))" . $orderby;				  		  		   
 }    

$item_arr = array();	
$work_sum = array();	
$month_sum = array();	

$item_arr[0]='결합단위';
$item_arr[1]='본천장';
$item_arr[2]='L/C';
$item_arr[3]='기타';
$item_arr[4]='공기청정기';
 
$lc_total_12 = 0;
$bon_total_12 = 0;
$lc_total_13to17 = 0;
$bon_total_13to17 = 0;
$lc_total_18 = 0;
$bon_total_18 = 0;

try {	
	$allstmh = $pdo->query($sql);
  while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {		
    $inseung = $row["inseung"];			  		
    $bon_su = $row["bon_su"];			  
    $lc_su = $row["lc_su"];
    
    $inseung_num = intval($inseung);
    $lc_su_num = intval($lc_su);
    $bon_su_num = intval($bon_su);
    
    if ($inseung_num <= 12 && $lc_su_num >= 1) {
      $lc_total_12 += $lc_su_num ;
    }
    if ($inseung_num >= 13 && $inseung_num <= 17 && $lc_su_num >= 1) {
      $lc_total_13to17 += $lc_su_num;
    }
    if ($inseung_num >= 18 && $lc_su_num >= 1) {
      $lc_total_18 += $lc_su_num;
    }
    
    if ($inseung_num <= 12 && $bon_su_num >= 1) {
      $bon_total_12 += $bon_su_num;
    }
    if ($inseung_num >= 13 && $inseung_num <= 17 && $bon_su_num >= 1) {
      $bon_total_13to17 +=  $bon_su_num;
    }
    if ($inseung_num >= 18 && $bon_su_num >= 1) {
      $bon_total_18 +=  $bon_su_num;
    }
  }
} catch (PDOException $Exception) {
  print "오류: " . $Exception->getMessage();
}

$sum_12 = $lc_total_12 + $bon_total_12; 
$sum_13to17 = $lc_total_13to17 + $bon_total_13to17; 
$sum_18 = $lc_total_18 + $bon_total_18; 

// 합계표 만들기
// bon_total_12 12인승 이하
$bon_total_12_amount = str_replace(',', '', $bon_total_12) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
$lc_total_12_amount = str_replace(',', '', $lc_total_12) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
$bon_total_12_total = $bon_total_12_amount + $lc_total_12_amount ;
$bon_total_12_num = $bon_total_12 + $lc_total_12;

$bon_total_13to17_amount = str_replace(',', '', $bon_total_13to17) * intval(str_replace(',', '', $readIni["bon_unit_13to17"]));
$lc_total_13to17_amount = str_replace(',', '', $lc_total_13to17) * intval(str_replace(',', '', $readIni["lc_unit_13to17"]));
$bon_total_13to17_total = $bon_total_13to17_amount + $lc_total_13to17_amount ;
$bon_total_13to17_num = $bon_total_13to17 + $lc_total_13to17;

$bon_total_18_amount = str_replace(',', '', $bon_total_18) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
$lc_total_18_amount = str_replace(',', '', $lc_total_18) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
$bon_total_18_total = $bon_total_18_amount + $lc_total_18_amount ;
$bon_total_18_num = $bon_total_18 + $lc_total_18;

$total = $bon_total_12_total + $bon_total_13to17_total + $bon_total_18_total ;  

$work_sum[1] =  $lc_total_12_amount + $lc_total_13to17_amount + $lc_total_18_amount  ;
$work_sum[2] =  $bon_total_12_amount + $bon_total_13to17_amount + $bon_total_18_amount  ;

$year=substr($fromdate,0,4) ;
// print $year;

if($item_sel==='년도비교') 
 {
    $year_count=0;
    $date_count=0;
    $year_sum = array();	
    $year=substr($fromdate,0,4) -1;				

    while($year_count<2) {
		
        $month_count=0;		
		
        while($month_count<12) {	
		
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
			
			$month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);
			$month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);

			$sql="select * from mirae8440.ceiling where workday between date('$month_fromdate') and date('$month_todate')" ; 			
						
			$lc_total_12_sub = 0;
			$bon_total_12_sub = 0;
			$lc_total_13to17_sub = 0;
			$bon_total_13to17_sub = 0;
			$lc_total_18_sub = 0;
			$bon_total_18_sub = 0;
			
			$sub_total = 0;

			try {
			  $allstmh = $pdo->query($sql);

			  while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {		
				$inseung = $row["inseung"];			  		
				$bon_su = $row["bon_su"];			  
				$lc_su = $row["lc_su"];
				
				$inseung_num = intval($inseung);
				$lc_su_num = intval($lc_su);
				$bon_su_num = intval($bon_su);
				
				if ($inseung_num <= 12 && $lc_su_num >= 1) {
				  $lc_total_12_sub += $lc_su_num ;
				}
				if ($inseung_num >= 13 && $inseung_num <= 17 && $lc_su_num >= 1) {
				  $lc_total_13to17_sub += $lc_su_num;
				}
				if ($inseung_num >= 18 && $lc_su_num >= 1) {
				  $lc_total_18_sub += $lc_su_num;
				}
				
				if ($inseung_num <= 12 && $bon_su_num >= 1) {
				  $bon_total_12_sub += $bon_su_num;
				}
				if ($inseung_num >= 13 && $inseung_num <= 17 && $bon_su_num >= 1) {
				  $bon_total_13to17_sub +=  $bon_su_num;
				}
				if ($inseung_num >= 18 && $bon_su_num >= 1) {
				  $bon_total_18_sub +=  $bon_su_num;
				}

				$sum_12_sub = $lc_total_12_sub + $bon_total_12_sub; 
				$sum_13to17_sub = $lc_total_13to17_sub + $bon_total_13to17_sub; 
				$sum_18_sub = $lc_total_18_sub + $bon_total_18_sub; 

				// 합계표 만들기				
				// bon_total_12_sub 12인승 이하
				$bon_total_12_amount = str_replace(',', '', $bon_total_12_sub) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
				$lc_total_12_amount = str_replace(',', '', $lc_total_12_sub) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
				$bon_total_12_total_sub = $bon_total_12_amount + $lc_total_12_amount ;
				$bon_total_12_num = $bon_total_12_sub + $lc_total_12_sub;

				$bon_total_13to17_amount = str_replace(',', '', $bon_total_13to17_sub) * intval(str_replace(',', '', $readIni["bon_unit_13to17"]));
				$lc_total_13to17_amount = str_replace(',', '', $lc_total_13to17_sub) * intval(str_replace(',', '', $readIni["lc_unit_13to17"]));
				$bon_total_13to17_total_sub = $bon_total_13to17_amount + $lc_total_13to17_amount ;
				$bon_total_13to17_num = $bon_total_13to17_sub + $lc_total_13to17_sub;
				
				$bon_total_18_amount = str_replace(',', '', $bon_total_18_sub) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
				$lc_total_18_amount = str_replace(',', '', $lc_total_18_sub) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
				$bon_total_18_total_sub = $bon_total_18_amount + $lc_total_18_amount ;
				$bon_total_18_num = $bon_total_18_sub + $lc_total_18_sub;

				$sub_total = $bon_total_12_total_sub + $bon_total_13to17_total_sub + $bon_total_18_total_sub ;   

				} 	 
		   } catch (PDOException $Exception) {
			print "오류: ".$Exception->getMessage();
		}  

		$year_sum[$date_count]= $sub_total ;
		$month_count++;
		$date_count++;
	   }
	  $year_count++;
	  $year++;	 
	}
}

if($item_sel==='월별비교') 
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
			
	if($search=== "" ){
	    $sql="select * from mirae8440.ceiling where workday between date('$month_fromdate') and date('$month_todate')" ; 			
	}
										
	if( $search!=="" ){
	  $sql ="select * from mirae8440.ceiling where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
	  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$month_fromdate') and date('$month_todate'))" ;				  		  		   
	}					
		$counter=0;
		$sum1=0;
		$sum2=0;
		$sum3=0;

		$sum1 = 0; $sum2 = 0; $sum3 = 0;
		
	
			$sql="select * from mirae8440.ceiling where workday between date('$month_fromdate') and date('$month_todate')" ; 			
						
			$lc_total_12_sub = 0;
			$bon_total_12_sub = 0;
			$lc_total_13to17_sub = 0;
			$bon_total_13to17_sub = 0;
			$lc_total_18_sub = 0;
			$bon_total_18_sub = 0;
			
			$sub_total = 0;

			try {
			  $allstmh = $pdo->query($sql);

			  while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {		
				$inseung = $row["inseung"];			  		
				$bon_su = $row["bon_su"];			  
				$lc_su = $row["lc_su"];
				
				$inseung_num = intval($inseung);
				$lc_su_num = intval($lc_su);
				$bon_su_num = intval($bon_su);
				
				if ($inseung_num <= 12 && $lc_su_num >= 1) {
				  $lc_total_12_sub += $lc_su_num ;
				}
				if ($inseung_num >= 13 && $inseung_num <= 17 && $lc_su_num >= 1) {
				  $lc_total_13to17_sub += $lc_su_num;
				}
				if ($inseung_num >= 18 && $lc_su_num >= 1) {
				  $lc_total_18_sub += $lc_su_num;
				}
				
				if ($inseung_num <= 12 && $bon_su_num >= 1) {
				  $bon_total_12_sub += $bon_su_num;
				}
				if ($inseung_num >= 13 && $inseung_num <= 17 && $bon_su_num >= 1) {
				  $bon_total_13to17_sub +=  $bon_su_num;
				}
				if ($inseung_num >= 18 && $bon_su_num >= 1) {
				  $bon_total_18_sub +=  $bon_su_num;
				}

				$sum_12_sub = $lc_total_12_sub + $bon_total_12_sub; 
				$sum_13to17_sub = $lc_total_13to17_sub + $bon_total_13to17_sub; 
				$sum_18_sub = $lc_total_18_sub + $bon_total_18_sub; 

				// 합계표 만들기				
				// bon_total_12_sub 12인승 이하
				$bon_total_12_amount = str_replace(',', '', $bon_total_12_sub) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
				$lc_total_12_amount = str_replace(',', '', $lc_total_12_sub) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
				$bon_total_12_total_sub = $bon_total_12_amount + $lc_total_12_amount ;
				$bon_total_12_num = $bon_total_12_sub + $lc_total_12_sub;

				$bon_total_13to17_amount = str_replace(',', '', $bon_total_13to17_sub) * intval(str_replace(',', '', $readIni["bon_unit_13to17"]));
				$lc_total_13to17_amount = str_replace(',', '', $lc_total_13to17_sub) * intval(str_replace(',', '', $readIni["lc_unit_13to17"]));
				$bon_total_13to17_total_sub = $bon_total_13to17_amount + $lc_total_13to17_amount ;
				$bon_total_13to17_num = $bon_total_13to17_sub + $lc_total_13to17_sub;
				
				$bon_total_18_amount = str_replace(',', '', $bon_total_18_sub) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
				$lc_total_18_amount = str_replace(',', '', $lc_total_18_sub) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
				$bon_total_18_total_sub = $bon_total_18_amount + $lc_total_18_amount ;
				$bon_total_18_num = $bon_total_18_sub + $lc_total_18_sub;

				$sub_total = $bon_total_12_total_sub + $bon_total_13to17_total_sub + $bon_total_18_total_sub ;   
				
				$counter++;	   
				$total_row++;

				 } 	 
			   } catch (PDOException $Exception) {
				print "오류: ".$Exception->getMessage();
			} 				
				
            $month_sum[$month_count] = $sub_total ;
            $month_count++;       
        }
       
}



// 차트는 바차트로 고정한다.
$chartchoice[1] = 'checked';  // 바차트 선택
$chart_sel = 'bar';  // 바차트 선택

?>
		 

<form id="board_form" name="board_form" method="post" action="output_statis.php?mode=search">  
	<input type="hidden" id="chart_sel" name="chart_sel" value="bar">
	<input type="hidden" id="display_sel" name="display_sel" value="bar">
	<input type="hidden" id="item_sel" name="item_sel" value='<?=$item_sel?>'>

<div class="container">  	
<div class="card mt-1 mb-2">  
<div class="card-body">  	
<div class="card-header mt-1 mb-3  justify-content-center align-items-center text-center" style="background-color:#cfe2ff;"> 		
		<span class="text-center fs-5"> <?=$title_message?> </span>
</div>					

<div class="row"> 		  
	<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
	<!-- 기간설정 칸 -->
	 <?php include getDocumentRoot() . '/setdate.php' ?>
	</div>
</div>

<div class="row "> 		 
<div class="d-flex justify-content-center align-items-center mt-2 mb-2 "> 		 
	  
	<?php			
		switch ($item_sel) {
			case   "년도비교"     :     $item_sel_choice[0]='checked'; break;			
			case   "월별비교"     :     $item_sel_choice[1]='checked'; break;
			case   "종류별비교"     :   $item_sel_choice[2]='checked'; break;
		}		
	 ?>    
			
	<label class='custom-radio me-1'>				
		<input type="radio" class="radio-input" <?=$item_sel_choice[0]?> name="item_sel" value="년도비교">
	<span class="radio-label">년도비교</span>
	</label>
	<label class='custom-radio me-1'>				
		<input type="radio" class="radio-input" <?=$item_sel_choice[1]?> name="item_sel" value="월별비교">
	<span class="radio-label">월별비교</span>
	</label>
	<label class='custom-radio'>				
		<input type="radio" class="radio-input" <?=$item_sel_choice[2]?> name="item_sel" value="종류별비교">
	<span class="radio-label">종류별비교</span>
	</label>		
		
	</div>
	<div class="row"> 	
	<div class="col-sm-8"> 	
		<div class="card"> 	
			<div class="card-body"> 		
				<canvas id="myChart"  width="800" height="500"></canvas> 		   				
			</div>	 
		</div>	 
	 </div>	 
<div class="col-sm-4">
		<div class="card"> 	
			<div class="card-body"> 
    <?php

			
	if ($item_sel == "년도비교") {
		
		$month_fromdate = sprintf("%04d", $month_todate);
		$month_fromdate = intval($month_fromdate) -1 ;
		$month_todate = sprintf("%04d", $month_todate);	

		echo "<div class='d-flex justify-content-end mb-1'> ";
		echo "<span class='text-dark text-end'> (단위:천원) </span> </div>";
		echo "<table class='table table-bordered'>";
		echo '<thead class="table-primary">';
		echo "<tr>";
		echo "<th class='text-center'>해당월</th>";
		echo "<th class='text-center'>" . $month_fromdate . "년 매출</th>";
		echo "<th class='text-center'>" . $month_todate . "년 매출</th>";

		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";	

		$total_last_year = 0;
		$total_this_year = 0;

		for ($i = 0; $i < 12; $i++) {
			$total_last_year += $year_sum[$i];
			$total_this_year += $year_sum[($i + 12)];

			echo "<tr>";
			echo "<td  class='text-center'>" . ($i + 1) . "월 </td>";
			echo "<td  class='text-end'>" . number_format($year_sum[$i]/1000) . " (천원)</td>";
			echo "<td  class='text-end'>" . number_format($year_sum[($i + 12)]/1000) . " (천원)</td>";
			echo "</tr>";
		}

		// 합계 행 추가
		echo "<tr>";
		echo "<td class='text-center'><strong>합계</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_last_year/1000) . " (천원)</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_this_year/1000) . " (천원)</strong></td>";
		echo "</tr>";

		echo "</tbody>";
		echo "</table>";
	}
	

	if ($item_sel == "월별비교") {		

		echo "<div class='d-flex justify-content-end mb-1'> ";
		echo "<span class='text-dark text-end'> (단위:천원) </span> </div>";		
		echo "<table class='table table-bordered'>";
		echo '<thead class="table-primary">';
		echo "<tr>";
		echo "<th class='text-center'>해당월</th>";
		echo "<th class='text-center'>매출합</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$months = ['red', 'blue', 'orange', 'green', 'purple', 'blue', 'orange', 'green', 'purple', 'red', 'blue', 'brown'];

		$total_monthly_production = 0;

		foreach ($months as $key => $color) {
			$total_monthly_production += $month_sum[$key];

			echo "<tr>";
			echo "<td class='text-center'>" . ($key + 1) . "월</td>";
			echo "<td class='text-end'>" . number_format($month_sum[$key]/1000) . " (천원)</td>";
			echo "</tr>";
		}

		// 합계 행 추가
		echo "<tr>";
		echo "<td class='text-center'><strong>합계</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_monthly_production/1000) . " (천원)</strong></td>";
		echo "</tr>";

		echo "</tbody>";
		echo "</table>";
	}
	
	
    if ($item_sel == "종류별비교") {		

		echo "<div class='d-flex justify-content-end mb-1'> ";
		echo "<span class='text-dark text-end'> (단위:천원) </span> </div>";		
		echo "<table class='table table-bordered'>";
		echo '<thead class="table-primary">';
		echo "<tr>";
		echo "<th class='text-center'>해당월</th>";
		echo "<th class='text-center'>매출</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$total_production = 0;

		for ($i = 1; $i < 3; $i++) {
				$total_production += $work_sum[$i];
			echo "<tr>";
			echo "<td class='text-center'>" . $item_arr[$i] . "</td>";
			echo "<td class='text-end'>" .number_format($work_sum[$i]/1000) . " (천원)</td>";
			echo "</tr>";
		}

		// 합계 행 추가
		echo "<tr>";
		echo "<td class='text-center'><strong>합계</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_production/1000) . " (천원)</strong></td>";
		echo "</tr>";

		echo "</tbody>";
		echo "</table>";
		
    }

    ?>
		</div> <!--card-body-->
   </div> <!--card -->
</div>


	 </div>
	 
   </div> <!-- card -->
   </div> <!-- row -->
</div> <!-- card -->

<div class="card">
		<div class="card-body">
		<div class="row justify-content-center align-items-center mt-1">
			<div class="col-sm-5">			
			<div class="d-flex  p-1 mt-1 mb-1 justify-content-center align-items-center "> 	
				<span class="mb-2 mt-3 me-2 "> 단가</span> 
				 <button id="writeBtn" type="button" class="btn btn-primary btn-sm"   > <ion-icon name="pencil-outline"></ion-icon> 수정  </button>
			</div>
					
<div class="d-flex justify-content-center align-items-center mt-2">	   									
<table class="table table-bordered justify-content-center" style="width:60%;">
    <thead class="table-primary">
		<tr>
			<th class="align-middle text-center">인승</th>
			<th class="align-middle text-center">조명천장</th>
			<th class="align-middle text-center">본천장</th>
		</tr>
      
    </thead>
    <tbody>
        <tr>
            <td>12인승 이하</td>
            <td>
			   <div class="d-flex justify-content-center">
					<input readonly  type="text" class="form-control   text-end" name="lc_total_12" value="<?=$readIni['lc_unit_12']?>"  data-separator="," />
				</div>
            </td>
            <td>
			   <div class="d-flex justify-content-center">			
					<input readonly  type="text" class="form-control   text-end" name="bon_total_12" value="<?=$readIni['bon_unit_12']?>" data-separator="," />
				</div>            
			</td>
        </tr>
        <tr>
            <td>13인승 이상 17인승 이하</td>
            <td>
			   <div class="d-flex justify-content-center">			
					<input readonly  type="text" class="form-control   text-end" name="lc_unit_13to17"  value="<?=$readIni['lc_unit_13to17']?>" data-separator="," />
				</div>				
            </td>
            <td>
			   <div class="d-flex justify-content-center">			
					<input readonly  type="text" class="form-control   text-end" name="bon_unit_13to17" value="<?=$readIni['bon_unit_13to17']?>"  data-separator="," />
				</div>				
            </td>
        </tr>
        <tr>
            <td>18인승 이상</td>
            <td>
			   <div class="d-flex justify-content-center">			
					<input readonly  type="text" class="form-control   text-end" name="lc_unit_18"  value="<?=$readIni['lc_unit_18']?>" data-separator="," />
				</div>				
            </td>
            <td>
			   <div class="d-flex justify-content-center">			
					<input readonly  type="text" class="form-control   text-end" name="bon_unit_18"  value="<?=$readIni['bon_unit_18']?>" data-separator="," />
				</div>				
            </td>
				</tr>
			</tbody>
		</table>	
		</div>		
	</div>		
<div class="col-sm-7 text-center">	
	
<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 	
  <span class="fs-6 mb-2 mt-3">
     세부 내역
	</span>
</div>


<div class="d-flex justify-content-center align-items-center mt-2">
<table id="table2" class="table table-bordered justify-content-center" style="width:100%;">
    <thead class="table-primary">
		<tr>
			<th rowspan="2" style="width:25%;" class="align-middle text-center">인승</th>
			<th rowspan="2" class="align-middle text-center">수량합</th>
			<th colspan="2" class="align-middle text-center">조명천장</th>
			<th colspan="2" class="align-middle text-center">본천장</th>
			<th rowspan="2" class="align-middle text-center" style="width:16%;">합계</th>
		</tr>
		<tr>
			<th class="align-middle text-center">수량</th>
			<th class="align-middle text-center" style="width:15%;">금액</th>
			<th class="align-middle text-center">수량</th>
			<th class="align-middle text-center" style="width:15%;">금액</th>
		</tr>
      
    </thead>	
 <tbody>
    <tr>
		<td >  12인승 이하</td>
        <td>
			<div class="d-flex justify-content-center">			
				<input readonly  type="text"  class="form-control    text-end"  value="<?= number_format($sum_12) ?>"  />
			</div>	
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input readonly  type="text" class="form-control    text-end"   value="<?= number_format($lc_total_12) ?>"   />
			</div>
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input readonly  type="text"  class="form-control text-end"  value="<?= number_format($lc_total_12_amount) ?>"   />
			</div>
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input readonly  type="text"  class="form-control    text-end"   value="<?= number_format($bon_total_12) ?>" />
			</div>
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input readonly  type="text"  class="form-control text-end"  value="<?= number_format($bon_total_12_amount) ?>" />
			</div>
        </td>
        <td>
		    <div class="d-flex justify-content-center">			
				<input readonly  type="text"  class="form-control text-end"   style=" font-weight: bold;" value="<?= number_format($bon_total_12_total) ?>"  />
			</div>
        </td>			
    </tr>
    <tr>
            <td>13인승 이상 17인승 이하</td>
        <td>
		    <div class="d-flex justify-content-center">			
				<input readonly  type="text"   class="form-control    text-end"   value="<?= number_format($sum_13to17) ?>"  />
			</div>
        </td>	
        <td>
		    <div class="d-flex justify-content-center">			
				<input readonly  type="text"  class="form-control    text-end"    value="<?= number_format($lc_total_13to17) ?>"   />
			</div>
        </td>		
        <td>
			<div class="d-flex justify-content-center">	
				<input readonly  type="text"   class="form-control text-end"   value="<?= number_format($lc_total_13to17_amount) ?>"   />
			</div>
        </td>
        <td>
		    <div class="d-flex justify-content-center">			
				<input readonly  type="text"  class="form-control    text-end"    value="<?= number_format($bon_total_13to17) ?>"   />
			</div>
        </td>		
        <td>
			<div class="d-flex justify-content-center">	
				<input readonly  type="text"   class="form-control text-end"  value="<?= number_format($bon_total_13to17_amount) ?>" />
			</div>
        </td>
        <td>
		    <div class="d-flex justify-content-center">			
				<input readonly  type="text"   class="form-control text-end"   style=" font-weight: bold;" value="<?= number_format($bon_total_13to17_total) ?>"  />
			</div>
        </td>			
    </tr>
	
    <tr>	
	
	  <td>18인승 이상</td>
	  
        <td>
		    <div class="d-flex justify-content-center">					
				<input readonly  type="text"   class="form-control    text-end"  value="<?= number_format($sum_18) ?>"  />
			</div>
        </td>			
        <td>
		    <div class="d-flex justify-content-center">					
				<input readonly  type="text"  class="form-control    text-end"  value="<?= number_format($lc_total_18) ?>"   />
			</div>
        </td>		
        <td>
		    <div class="d-flex justify-content-center">					
				<input readonly  type="text"   class="form-control text-end"  value="<?= number_format($lc_total_18_amount) ?>"   />
			</div>
        </td>
        <td>
		    <div class="d-flex justify-content-center">					
				<input readonly  type="text"  class="form-control    text-end"  value="<?= number_format($bon_total_18) ?>"   />
			</div>
        </td>		
        <td>
		    <div class="d-flex justify-content-center">					
				<input readonly  type="text"    class="form-control text-end"  value="<?= number_format($bon_total_18_amount) ?>" />
			</div>
        </td>		
        <td>
		    <div class="d-flex justify-content-center">					
				<input readonly  type="text"   class="form-control  text-end"   name="bon_total_18_total" style=" font-weight: bold;" value="<?= number_format($bon_total_18_total) ?>"  />
			</div>
        </td>			
    </tr>
    <tr>
        <td colspan="6" >합계</td>            
        <td>
		    <div class="d-flex justify-content-center">					
				<input readonly  type="text" class="form-control text-end text-primary" style=" font-weight: bold;"  name="total"  value="<?= number_format($total) ?>"  />
			</div>
        </td>
		</tr>		
	</tbody>

	</table>		  
	</div>
	</div>
	</div>
	</div>
</div>
	  
</div> <!--card-body-->
</div> <!--card -->           
</div> <!--container-->     
</form>
   
<script> 

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

$(document).ready(function() { 

	$("#writeBtn").click(function(){ 	
		popupCenter('./estimate.php', '쟘 제품 단가입력', 1050, 600);	   	 
	});		  

	var item_arr = <?php echo json_encode($item_arr);?> ;
	var work_sum = <?php echo json_encode($work_sum);?> ;
	var month_sum = <?php echo json_encode($month_sum);?> ;
	var year_sum = <?php echo json_encode($year_sum);?> ;
		
	var ctx = document.getElementById('myChart');
	var chart_type = document.getElementById('display_sel').value;
	var item_type = document.getElementsByName('item_sel')[0].value;

// console.log(year_sum);
// console.log(chart_type);
// console.log(item_type);

if(item_type=='종류별비교') 
{
	var myChart = new Chart(ctx, {
	type: chart_type,
	data: {
		labels: [ item_arr[1], item_arr[2]],
		datasets: [{
			label: '#본천장/LC 매출 ',
			data: [ work_sum[1], work_sum[2]],
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
				label: '#본천장/LC 매출 ',
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
				label: '#전년도 본천장/LC 매출 , #금년도 본천장/LC 매출',
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

});   // end of ready
</script>  

<script>
	$(document).ready(function(){
		saveLogData('Ceiling 매출통계'); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 

</body>
</html>