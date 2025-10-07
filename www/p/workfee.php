<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   } 
   
$workername = $_REQUEST["workername"];

 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/steel.css">
 <link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>	

<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 

 <title> 시공완료일 기준 시공비 산출자료 </title> 
 </head>

 <?php 
 
  function conv_num($num) {
$number = (int)str_replace(',', '', $num);
return $number;
}
 
  if(isset($_REQUEST["worker"])) 
	 $worker=$_REQUEST["worker"]; 
   else
     $worker=$_POST["worker"]; 
 
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
  
// print $output_check;
  
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 $sortof=$_REQUEST["sortof"];  // 클릭해서 넘겨준 값
 $stable=$_REQUEST["stable"];    // 정렬모드 변경할지 안할지 결정  
  
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
/*
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
 */
 // 당월을 날짜 기간으로 설정
 
 if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,7) ;
	$fromdate=$fromdate . "-01";
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
 
  if(isset($_REQUEST["search"]))   //
 $search=$_REQUEST["search"];

 $orderby=" order by doneday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 생산예정으로 구분		
  
if($mode=="search"){
					 $sql="select * from mirae8440.work where (doneday between date('$fromdate') and date('$Transtodate')) and (worker like '%$worker%' ) " . $orderby;  								 
}

	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

  $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $firstord_arr=array();
   $secondord_arr=array();
   $worker_arr=array();
   $workfeedate_arr=array();
   $material_arr=array();
   $demand_arr=array();
   $visitfee_arr=array();
   $totalfee_arr=array();
   
   $wide_arr=array();
   $normal_arr=array();
   $narrow_arr=array();
   $widefee_arr=array();
   $normalfee_arr=array();
   $narrowfee_arr=array();
   $etc_arr=array();
   $etcfee_arr=array();  
   
   $wideunit_arr=array();
   $normalunit_arr=array();
   $narrowunit_arr=array();   
   $etcunit_arr=array();   
   
   $num_arr=array();  // 일괄처리를 위한 번호 저장
   
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
 
   // $sql="select * from mirae8440.work"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
			  $num=$row["num"];
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
			  $doneday=$row["doneday"];  // 시공완료일
			  $workfeedate=$row["workfeedate"];  // 시공비지급일
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
			  
			  $widejambworkfee=$row["widejambworkfee"];
			  $normaljambworkfee=$row["normaljambworkfee"];
			  $smalljambworkfee=$row["smalljambworkfee"];
			  $workfeedate=$row["workfeedate"];      // 시공비 처리일			  
			  
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  
			  $demand=$row["demand"];  	   
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $hpi=$row["hpi"];	   
			  $delicompany=$row["delicompany"];	   
			  $delipay=$row["delipay"];	   
	
		      if($orderday!="0000-00-00" and $orderday!="1970-01-01"  and $orderday!="") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01" and $drawday!="")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01" and $deadline!="")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01" and $endworkday!="")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";		      
		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";						
		      if($startday!="0000-00-00" and $startday!="1970-01-01" and $startday!="")  $startday = date("Y-m-d", strtotime( $startday) );
					else $startday="";	
		      if($testday!="0000-00-00" and $testday!="1970-01-01" and $testday!="")  $testday = date("Y-m-d", strtotime( $testday) );
					else $testday="";	
		      if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
					else $doneday="";	
		      if($workfeedate!="0000-00-00" and $workfeedate!="1970-01-01" and $workfeedate!="")  $workfeedate = date("Y-m-d", strtotime( $workfeedate) );
					else $workfeedate="";	
		      if($recordDate!="0000-00-00" and $recordDate!="1970-01-01" and $recordDate!="")  $recordDate = date("Y-m-d", strtotime( $recordDate) );
					else $recordDate="";
	   
		   $doneday_arr[$counter]=$doneday;
		   $workfeedate_arr[$counter]=$workfeedate;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;
		   $secondord_arr[$counter]=$secondord;   
		   $firstord_arr[$counter]=$firstord;   
		   $worker_arr[$counter]=$worker;   
		   $demand_arr[$counter]=$demand;   
		   $num_arr[$counter]=$num;    
		   
		   // 판매'란 단어 있으면 실측비 제외		   
		   $findstr = '판매';
		   $pos = stripos($workplacename, $findstr);	

			$visitfee_arr[$counter]= 0 ;  // 소장들은 실측비 부분 표시되지 않도록 설계
		   
		// if( trim($secondord) =='우성' or trim($secondord) == '한산' or $pos>0 )
			// $visitfee_arr[$counter]= 0;
		    // else
				// $visitfee_arr[$counter]= 100000 ;
				 

			  $materials="";
			  $materials=$material2 . " " . $material1 . " " . $material3 . $material4 . $material5 . $material6;		
		   
		   $material_arr[$counter]=$materials;   		   

						   
		   
		   $wide_arr[$counter] = 0;
		   $widefee_arr[$counter] = 0;
		   $normal_arr[$counter] = 0;
		   $normalfee_arr[$counter] = 0;
		   $narrow_arr[$counter] = 0;
		   $narrowfee_arr[$counter] = 0;
		   $etc_arr[$counter] = 0;
		   $etcfee_arr[$counter] = 0;
		   
		   $wideunit_arr[$counter] = 0;
		   $normalunit_arr[$counter] = 0;
		   $narrowunit_arr[$counter] = 0;
		   $etcunit_arr[$counter] = 0;	

 
   				 $workitem="";
				 if($widejamb!="")   {
						$wide_arr[$counter] = (int)$widejamb;
								  
							   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr = '불량';
							   $pos = stripos($workplacename, $findstr);							   
							   //판매란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr2 = '판매';
							   $pos2 = stripos($workplacename, $findstr2);
							   if($pos==0 and $pos2==0)
									 if((int)$widejambworkfee > 0)
								        $wideunit_arr[$counter] = conv_num($widejambworkfee) ;  
									    else if( trim($secondord) =='우성' and (trim(strtoupper($firstord)) =='OTIS' or trim($firstord)=='오티스') )
											     $wideunit_arr[$counter] = 100000;  
												   else
													  $wideunit_arr[$counter] = 80000;  								   								 
							 
									$widefee_arr[$counter]= (int)$widejamb * $wideunit_arr[$counter];  	  							   
								   
									}
				 if($normaljamb!="")   {
						$normal_arr[$counter] = (int)$normaljamb;				
							 
							   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr = '불량';
							   $pos = stripos($workplacename, $findstr);							   
							   //판매란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr2 = '판매';
							   $pos2 = stripos($workplacename, $findstr2);
							   if($pos==0 and $pos2==0)
										if((int)$normaljambworkfee > 0)
											 $normalunit_arr[$counter] = conv_num($normaljambworkfee) ;  								   
											else
												$normalunit_arr[$counter] = 70000 ;								   
							   

								     
						
						$normalfee_arr[$counter]=  (int)$normaljamb * $normalunit_arr[$counter];  						
						}
				 if($smalljamb!="") {
						$narrow_arr[$counter] = (int)$smalljamb;	
						

								 
							   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr = '불량';
							   $pos = stripos($workplacename, $findstr);							   
							   //판매란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr2 = '판매';
							   $pos2 = stripos($workplacename, $findstr2);
							   if($pos==0 and $pos2==0)
								   	if((int)$smalljambworkfee > 0)
								         $narrowunit_arr[$counter] = conv_num($smalljambworkfee) ; 	
									 else
										$narrowunit_arr[$counter] = 20000 ;	

						
						$narrowfee_arr[$counter]= (int)$smalljamb * $narrowunit_arr[$counter];  												
						}		   	   
	 
		        $totalfee_arr[$counter] = $widefee_arr[$counter] + $normalfee_arr[$counter]+ $narrowfee_arr[$counter] + $etcfee_arr[$counter] ;  
			   $counter++;	
		   } // end of 판매 / 불량		   		   	   
     // }   	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

?>		 
<body >

<div id="wrap">
			<div class="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<h1 class="display-0  text-left">
			  <input type="button" class="btn btn-primary btn-lg " value="목록으로 이동" onclick="javascript:move_url('index.php?check=<?=$check?>&workername=<?=$workername ?>');"> </h1>
			  </div>
<div class="card-header">    
 <h1> &nbsp; 시공완료일 기준 시공비 산출자료 &nbsp; 		<button  type="button" class="btn btn-secondary" id="downloadcsvBtn"> CSV 엑셀 </button>	 
 <button  type="button" class="btn btn-secondary" id="downloadlistBtn" onclick="javascript:move_url('../work/excelform.php?fromdate=<?=$fromdate?>&todate=<?=$todate?>&search=<?=$worker?>&workername=<?=$workername ?>')"> 소장별 거래명세표(엑셀)</button>
 	</h1>
</div> 
  
   <div id="content" style="width:1000px;">			 
   <form name="board_form" id="board_form"  method="post" action="workfee.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>&workername=<?=$workername ?>">  
   <div id="list_search" style="width:1000px;">
		
    <div class="input-group p-2 mb-2">
		<button type="button" id="preyear" class="btn btn-secondary"   onclick='pre_year()' > 전년도 </button>  &nbsp;  	
		<button type="button" id="three_month" class="btn btn-secondary"  onclick='three_month_ago()' > M-3월 </button> &nbsp;  	
		<button type="button" id="prepremonth" class="btn btn-secondary"  onclick='prepre_month()' > 전전월 </button>	&nbsp;  
		<button type="button" id="premonth" class="btn btn-secondary"  onclick='pre_month()' > 전월 </button>  &nbsp; 	
		<button type="button" id="thismonth" class="btn btn-dark"  onclick='this_month()' > 당월 </button>	&nbsp;  	   
		<button type="button" id="thisyear" class="btn btn-dark"  onclick='this_year()' > 당해년도 </button> &nbsp;  				   
       </div>
    <div class="input-group p-2 mb-2">	   
       <span class='input-group-text align-items-center' style='width:400 px;'>  
       <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp; 부터 &nbsp;  
       <input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">  &nbsp;  까지    </span>  &nbsp;
	   <input type="hidden" name="search" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();"> 
		<button type="button" id="searchBtn" class="btn btn-dark"  > 검색 </button>		
	   
       </div>
	   
<input type="hidden" id="worker" name="worker" size="12"  value="<?=$worker?>" > 

	 <div id="grid" style="width:1200px;">
  
  </div>
     <div class="clear"></div> 		 
	 </form>
	 </div>

   <div class="clear"></div>	
   
   </div> 	   
  </div> <!-- end of wrap -->
  
<script>

$(document).ready(function(){
	
$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
	
 var arr1 = <?php echo json_encode($doneday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($secondord_arr);?> ;  
 var arr4 = <?php echo json_encode($wide_arr);?> ;
 var arr5 = <?php echo json_encode($wideunit_arr);?> ;
 var arr6 = <?php echo json_encode($widefee_arr);?> ;
 var arr7 = <?php echo json_encode($normal_arr);?> ;
 var arr8 = <?php echo json_encode($normalunit_arr);?> ;
 var arr9 = <?php echo json_encode($normalfee_arr);?> ;
 var arr10= <?php echo json_encode($narrow_arr);?> ;
 var arr11= <?php echo json_encode($narrowunit_arr);?> ;
 var arr12= <?php echo json_encode($narrowfee_arr);?> ;
 var arr13 = <?php echo json_encode($etc_arr);?> ;
 var arr14 = <?php echo json_encode($etcfee_arr);?> ;
 var arr15 = <?php echo json_encode($totalfee_arr);?> ;
 
 var total_sum=0; 
  
 var rowNum = "<? echo $counter; ?>" ; 
 
 const data = [];
 const columns = [];	
 const COL_COUNT = 15;

 for(i=0;i<rowNum;i++) {
			 total_sum = total_sum + Number(uncomma(arr15[i]));
		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr3[i] ;						 						
				row[`col4`] =  (arr4[i] == 0) ? "" : comma(arr4[i]);						 						
				row[`col5`] = (arr5[i] == 0) ? "" : comma(arr5[i]);					 						
				row[`col6`] =  (arr6[i] == 0) ? "" : comma(arr6[i]);						 						
				row[`col7`] =  (arr7[i] == 0) ? "" : comma(arr7[i]);					 						
				row[`col8`] = (arr8[i] == 0) ? "" : comma(arr8[i]);					 						
				row[`col9`] =(arr9[i] == 0) ? "" : comma(arr9[i]);						 						
				row[`col10`] = (arr10[i] == 0) ? "" : comma(arr10[i]);							 						
				row[`col11`] = (arr11[i] == 0) ? "" : comma(arr11[i]);							 						
				row[`col12`] = (arr12[i] == 0) ? "" : comma(arr12[i]);							 						
				row[`col13`] = (arr13[i] == 0) ? "" : comma(arr13[i]);							 						
				row[`col14`] = (arr14[i] == 0) ? "" : comma(arr14[i]);							 						
				row[`col15`] = (arr15[i] == 0) ? "" : comma(arr15[i]);							 						
						}
				data.push(row); 	 			 
 }
   // 마지막에 한줄 추가해서 합계내역 넣음
	i++;		
	row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = '' ;						 						
				row[`col2`] = '' ;						 						
				row[`col3`] = '' ;						 						
				row[`col4`] = '' ;						 						
				row[`col5`] = '' ;
				row[`col6`] = '' ;						 						
				row[`col7`] = '' ;						 						
				row[`col8`] = '' ;
				row[`col9`] = '' ;					 						
				row[`col10`] ='' ;
				row[`col11`] ='' ;
				row[`col12`] ='' ;
				row[`col13`] ='' ;
				row[`col14`] = '합계';
				row[`col15`] = comma(total_sum)  ;						 						
			}
	data.push(row); 	 			 
			
			

 class CustomTextEditor {
	  constructor(props) {
		const el = document.createElement('input');
		const { maxLength } = props.columnInfo.editor.options;

		el.type = 'text';
		el.maxLength = maxLength;
		el.value = String(props.value);

		this.el = el;
	  }

	  getElement() {
		return this.el;
	  }

	  getValue() {
		return this.el.value;
	  }

	  mounted() {
		this.el.select();
	  }
	}	

const grid = new tui.Grid({
	  el: document.getElementById('grid'),
	  data: data,
	  bodyHeight: 1200,					  					
	  columns: [ 				   
		{
		  header: '시공완료일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col2',
		  width:200,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
			
		  },	 		
		  align: 'left'
		},
		{
		  header: '발주처',
		  name: 'col3',
		  width: 80,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},		
		{
		  header: '막판',
		  name: 'col4',
		  width:40,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},		
		{
		  header: '단가',
		  name: 'col5',
		  width:60,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		},		
		{
		  header: '금액',
		  name: 'col6',
		  width:70,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		},			
		{
		  header: '막판무',
		  name: 'col7',
		  width:50,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},			
		{
		  header: '단가',
		  name: 'col8',
		  width:60,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		},				
		{
		  header: '금액',
		  name: 'col9',
		  width:70,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		},			
		{
		  header: '쪽쟘',
		  name: 'col10',
		  width:40,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},			
		{
		  header: '단가',
		  name: 'col11',
		  width:50,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		},				
		{
		  header: '금액',
		  name: 'col12',
		  width:70,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		},			
		{
		  header: '기타',
		  name: 'col13',
		  width:40,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},		
		{
		  header: '금액',
		  name: 'col14',
		  width:70,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		},					
		{
		  header: '청구비합',
		  name: 'col15',
		  width:90,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'right'
		}			
	  ],
	columnOptions: {
			resizable: true
		  },
	  rowHeaders: ['rowNum'],
	  pageOptions: {
		useClient: false,
		perPage: 20
	  }	  
	});		
var Grid = tui.Grid; // or require('tui-grid')
Grid.applyTheme('default', {
			  cell: {
				normal: {
				  background: '#fbfbfb',
				  border: '#e0e0e0',
				  showVerticalBorder: true
				},
				header: {
				  background: '#eee',
				  border: '#ccc',
				  showVerticalBorder: true
				},
				rowHeader: {
				  border: '#ccc',
				  showVerticalBorder: true
				},
				editable: {
				  background: '#fbfbfb'
				},
				selectedHeader: {
				  background: '#d8d8d8'
				},
				focused: {
				  border: '#418ed4'
				},
				disabled: {
				  text: '#b0b0b0'
				}
			  }	
	});		

$("#downloadcsvBtn").click(function(){  Do_gridexport();   });	          // CSV파일 클릭	
//////////////////// saveCSV	
Do_gridexport = function () { 
	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.		
            
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '시공완료일 ,' ;
			   row += '현장명 ,' ;			   
			   row += '발주처 ,' ;
			   row += '막판 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '막판무 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '쪽쟘 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '기타 ,' ;
			   row += '금액 ,' ;			   
			   row += '청구합 ' ;
			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			const COLNUM = 15;   
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
			var tmpName = "<? echo $worker; ?>" ; 
			link.setAttribute("download", tmpName + "소장 시공내역서.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
			
	dis_text();
	
});

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

function this_year()  {   // 당해년도
//		document.getElementById('search').value=null; 
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
			dd = '0' + dd;
		} 

		if(mm<10) {
			mm = '0' + mm;
		} 

		frompreyear = yyyy + '-01' + '-01';

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

			 topreyear = yyyy + '-' + mm + '-' + dd ;

			document.getElementById("fromdate").value = frompreyear;
			document.getElementById("todate").value = topreyear;
		    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function dis_text()
{  
		var dis_text = '<?php echo $jamb_total; ?>';
		$("#dis_text").val(dis_text);
}	

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

function adjustGrid(){
	
// 모바일 화면과 PC화면 표시 다르게 하기 
// myWidth = document.write(window.innerWidth);

// $("#grid").css("width","1000px");
  
}

setTimeout(function() {
 // console.log('Works!');
 adjustGrid();
}, 500);

function move_url(href)
{	 
        document.location.href = href;		 
}

</script>

  </html>

</body>