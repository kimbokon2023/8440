<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }   

   // 첫 화면 표시 문구
 $title_message = 'jamb 시공비 통계';    
   
?>
   
   
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<title> <?=$title_message?> </title>

<!-- Light & Subtle Theme CSS -->
<link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />

<style>
:root {
    --primary-blue: #0288d1;
    --secondary-blue: #0277bd;
    --light-blue: #b3e5fc;
    --glass-bg: rgba(224, 242, 254, 0.3);
    --glass-border: rgba(176, 230, 247, 0.5);
    --shadow: 0 2px 12px rgba(2, 136, 209, 0.08);
    --text-primary: #01579b;
    --text-secondary: #0277bd;
}
 
body {
    background: white;
    overflow-x: hidden;
}

.container-fluid {
    max-width: 100%;
    overflow-x: hidden;
    padding: 0.9rem;
}

.glass-container {
    background: linear-gradient(135deg, #e0f2fe 0%, #f1f8fe 100%);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    box-shadow: var(--shadow);
}

.compact-chart-title {
    font-size: 0.81rem;
    font-weight: 600;
    color: var(--text-primary);
}

.table th {
    font-size: 0.81rem;
    font-weight: 600;
    padding: 0.54rem;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    border: none;
}

.table td {
    font-size: 1.0125rem;
    padding: 0.675rem;
    border-color: var(--glass-border);
    background: rgba(255, 255, 255, 0.5);
}

.table-secondary {
    background: linear-gradient(135deg, var(--light-blue), var(--secondary-blue)) !important;
    color: white;
}

.form-check-label {
    font-size: 0.9rem !important;
    font-weight: 500;
}

.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
}

.compact-badge {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.chart-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.9rem;
    box-shadow: var(--shadow);
    height: 280px;
}

.table-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem;
    margin-left: 1rem;
}

@media (max-width: 768px) {
    .col-md-8, .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table-container {
        margin-left: 0;
    }
}

/* Statistics Specific Styles - Light & Subtle Theme */
.stats-chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dashboard-text);
    margin-bottom: 1rem;
}

.stats-table th {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.6rem;
    background: var(--dashboard-secondary);
    color: var(--dashboard-text);
    border: none;
    text-align: center;
}

.stats-table td {
    font-size: 0.9rem;
    padding: 0.7rem;
    border-color: var(--dashboard-border);
    background: white;
    text-align: center;
}

.stats-table-secondary {
    background: var(--gradient-accent) !important;
    color: white !important;
}

.stats-form-check-label {
    font-size: 0.9rem !important;
    font-weight: 500;
    color: var(--dashboard-text);
    margin-right: 0.5rem;
}

.stats-form-check-input {
    width: 1.1rem;
    height: 1.1rem;
    margin-right: 0.3rem;
}

.stats-radio-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 20px;
    padding: 0.8rem 1.5rem;
    display: inline-block;
    box-shadow: var(--dashboard-shadow);
}

.stats-chart-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    padding: 1rem;
    box-shadow: var(--dashboard-shadow);
    height: 280px;
}

.stats-table-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    padding: 1rem;
    margin-left: 1rem;
    box-shadow: var(--dashboard-shadow);
}

.stats-info-text {
    color: var(--dashboard-text-secondary);
    font-size: 0.85rem;
    font-style: italic;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .col-md-8, .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .stats-table-container {
        margin-left: 0;
        margin-top: 1rem;
    }

    .stats-radio-container {
        padding: 0.6rem 1rem;
    }
}
</style>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

</head>

<body>

  <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>

 <?php
 
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : null;
$load_confirm = isset($_REQUEST["load_confirm"]) ? $_REQUEST["load_confirm"] : null;
$display_sel = isset($_REQUEST["display_sel"]) ? $_REQUEST["display_sel"] : 'bar';
$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : 0;
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : null;
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';

require_once("../lib/mydb.php");
$pdo = db_connect();
  
$fromdate = $_REQUEST["fromdate"];	 
$todate = $_REQUEST["todate"];	 

// 올해를 날짜 기간으로 설정
if (empty($fromdate)) {
    $fromdate = date("Y-m-01"); // 현재 달의 첫째 날
}

if (empty($todate)) {
    $year = date("Y"); // 현재 연도
    $month = date("m"); // 현재 달
    $firstDayOfNextMonth = mktime(0, 0, 0, $month + 1, 1, $year); // 다음 달의 첫째 날
    $lastDayOfMonth = date("Y-m-d", strtotime("-1 day", $firstDayOfNextMonth)); // 현재 달의 마지막 날
    $todate = $lastDayOfMonth;
}

$Transtodate = strtotime($todate . '+1 days');
$Transtodate = date("Y-m-d", $Transtodate);



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

		include '_row.php'; 

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
   
		 include '_row.php';
		 	 	
	
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
							 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')   " ; 					
	                       			
			     }
	   
				   
            else { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.work where ((workday like '%$search%')  or (workplacename like '%$search%') ";
							  $sql .="or (item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%')) and (workday between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  ";

						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')   "; 				
					
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   
	
$worker_arr = array();	
$work_done = array();	
$temp_arr = array();	
$count=0;

$worker_arr[0]='추영덕';
$worker_arr[1]='이만희';
$worker_arr[2]='김상훈';
$worker_arr[3]='박철우';
$worker_arr[4]='유영';
$worker_arr[5]='김운호';
$worker_arr[6]='손상민';
$worker_arr[7]='조장우';
$worker_arr[8]='이인종';
$worker_arr[9]='이춘일';

$workerNum = count($worker_arr); // 소장8명 명단


// print $fromdate;
// print $todate;
?>
<form name="board_form" id="board_form"  method="post" action="statistics.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&display_sel=<?=$display_sel?>">

<div class="container">
<div class="modern-management-card mt-2 mb-4">

<div class="modern-dashboard-header">
		<h3 class="stats-chart-title text-center mb-0"> 시공소장 실적 비교 (대외비 - 유출금지) </h3>
</div>

<div style="padding: 1.5rem;">
<div class="row">
	<div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
	<!-- 기간설정 칸 -->
	 <?php include $_SERVER['DOCUMENT_ROOT'] . '/setdate.php' ?>
	</div>
</div>

<div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center">

	 <?php
	 
	//  print 'sql :' . $sql;
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();
		
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
              
			 //  var_dump($row);
			  
              $num=$row["num"];		  
			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $workday=$row["workday"];
			  $worker=$row["worker"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
 			  
   
			   // 불량이란 단어가 들어가 있는 수량은 제외한다.		   
			   $findstr = '불량';

			   $pos = stripos($workplacename, $findstr);							   

			   if($pos==0)  {			
			  
			  $work_done[$count]=   (int) $widejamb +   (int) $normaljamb +  (int) $smalljamb/4 ;
 			  
							switch ($worker) {
								case   $worker_arr[0]     :   $work_sum[0] += $work_done[$count]; break;
								case   $worker_arr[1]     :   $work_sum[1] += $work_done[$count]; break;	
								case   $worker_arr[2]     :   $work_sum[2] += $work_done[$count]; break;	
								case   $worker_arr[3]     :   $work_sum[3] += $work_done[$count]; break;
								case   $worker_arr[4]     :   $work_sum[4] += $work_done[$count]; break;									
								case   $worker_arr[5]     :   $work_sum[5] += $work_done[$count]; break;									
								case   $worker_arr[6]     :   $work_sum[6] += $work_done[$count]; break;									
								case   $worker_arr[7]     :   $work_sum[7] += $work_done[$count]; break;									
								case   $worker_arr[8]     :   $work_sum[8] += $work_done[$count]; break;									
								case   $worker_arr[9]     :   $work_sum[9] += $work_done[$count]; break;									
								

								default:  break;	
							}					  		  			  

					$count++;
			   }
			$start_num--;  
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }    
	switch ($display_sel) {
		case   "doughnut"     :   $chartchoice[0]='checked'; break;
		case   "bar"     :   $chartchoice[1]='checked'; break;
		case   "line"     :   $chartchoice[2]='checked'; break;
		case   "radar"     :   $chartchoice[3]='checked'; break;
		case   "polarArea"     :   $chartchoice[4]='checked'; break;
	}
 ?>
   <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
   <input id="display_sel" name="display_sel" type='hidden' value='<?=$display_sel?>' >
     <div class="stats-radio-container mb-3">
   			<span class="stats-form-check-label">도넛</span> <input type="radio" class="stats-form-check-input" <?=$chartchoice[0]?> name="chart_sel" value="doughnut">
			<span class="stats-form-check-label">바</span> <input type="radio" class="stats-form-check-input" <?=$chartchoice[1]?> name="chart_sel" value="bar">
			<span class="stats-form-check-label">라인</span> <input type="radio" class="stats-form-check-input" <?=$chartchoice[2]?> name="chart_sel" value="line">
			<span class="stats-form-check-label">레이더</span> <input type="radio" class="stats-form-check-input" <?=$chartchoice[3]?> name="chart_sel" value="radar">
			<span class="stats-form-check-label">Polar Area</span> <input type="radio" class="stats-form-check-input" <?=$chartchoice[4]?> name="chart_sel" value="polarArea">
	 </div>
	</div>
     <div class="row">
	     <div class="col-md-8">
           <div id="chartMain" class="stats-chart-container"></div>
		   </div>
           <div class="col-md-4">
           <div class="stats-table-container">
		    <h5 class="mb-2 mt-3 stats-chart-title"> 시공소장별 시공비 추정금액 </h5>
			<span class='stats-info-text mt-2 mb-4 d-block'>
					최종SET는 쪽쟘 4Set → 와이드 1Set로 계산함
		    </span>
									   
			<table class="stats-table table-bordered">
				<thead>
					<tr>
						<th class="text-center"> 시공소장</th>
						<th class="text-center"> 설치 SET</th>
						<th class="text-center"> 시공비(만원)</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$totalAmount = 0;
					for ($i = 0; $i < $workerNum; $i++) {
						$amount = $work_sum[$i] * 8;
						$totalAmount += $amount;

						echo "<tr>";
						echo "<td class='text-center'> " . $worker_arr[$i] . "</td>";
						echo "<td class='text-center'> " . number_format($work_sum[$i]) . " (SET)</td>";
						echo "<td class='text-center'> " . number_format($amount) . " </td>";
						echo "</tr>";
					}
					?>
					<tr class="stats-table-secondary">
						<td class="text-center" colspan="2"><strong>합계</strong></td>
						<td class="text-center"><strong><?= number_format($totalAmount) ?></strong></td>
					</tr>
				</tbody>
			</table>

		   </div>
		   </div>

   </div>

   </div> <!--card-body-->
   </div> <!--glass-container -->
   </div> <!--container-fluid-->

</form>



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
					
	// 차트 타입 변경 시 페이지 새로고침을 위한 함수
	function refreshChart() {
		var chart_type = document.getElementById('display_sel').value;
		createChart();
	}
</script>

<script>
	$(document).ready(function(){
		// 차트 생성을 DOM 로드 후 실행
		createChart();
		saveLogData('Jamb 시공소장 시공비');
	});

	function createChart() {
		var worker_arr = <?php echo json_encode($worker_arr);?> ;
		var work_sum = <?php echo json_encode($work_sum);?> ;
		var chart_type = document.getElementById('display_sel').value;

		console.log('Worker Array:', worker_arr);
		console.log('Work Sum:', work_sum);
		console.log('Chart Type:', chart_type);

		// 차트 타입 매핑
		function getHighchartsType(chartType) {
			switch(chartType) {
				case 'bar': return 'column';
				case 'line': return 'line';
				case 'doughnut': return 'pie';
				case 'radar': return 'line';
				case 'polarArea': return 'pie';
				default: return 'column';
			}
		}

		// 차트 데이터 준비
		var chartData = [];
		var chartCategories = [];

		for(var i = 0; i < worker_arr.length; i++) {
			if(worker_arr[i] && work_sum[i] !== undefined) {
				chartCategories.push(worker_arr[i]);
				chartData.push(work_sum[i] || 0);
			}
		}

		console.log('Chart Data:', chartData);
		console.log('Chart Categories:', chartCategories);

		// Highcharts 차트 생성
		Highcharts.chart('chartMain', {
			chart: {
				type: getHighchartsType(chart_type),
				backgroundColor: 'rgba(255, 255, 255, 0.9)'
			},
			title: {
				text: '시공소장별 실적 통계',
				style: { fontSize: '14px', fontWeight: '600', color: '#334155' }
			},
			xAxis: {
				categories: chartCategories,
				labels: { style: { fontSize: '10px', color: '#64748b' } }
			},
			yAxis: {
				title: { text: 'SET 수량', style: { fontSize: '10px', color: '#64748b' } },
				labels: { style: { fontSize: '10px', color: '#64748b' } }
			},
			series: [{
				name: '시공수량',
				data: chartData,
				color: '#64748b'
			}],
			tooltip: {
				formatter: function() {
					return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' SET</b>';
				}
			},
			legend: { enabled: false },
			credits: { enabled: false },
			plotOptions: {
				pie: {
					innerSize: chart_type === 'doughnut' ? '50%' : 0,
					dataLabels: {
						enabled: true,
						format: '{point.name}: {point.y} SET'
					}
				}
			}
		});
	}
</script> 

</body>
</html>