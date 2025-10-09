<?php
require_once __DIR__ . '/../bootstrap.php';

  if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          		 
	 sleep(1);
	  header("Location:" . getBaseUrl() . "/login/login_form.php"); 
         exit;
   }  
   
include includePath('load_header.php');
 
 ?> 
 <title>  쟘 일괄처리 </title>  
 </head>
 
 <?php 

 if(isset($_REQUEST["recordDate"])) 
	 $recordDate=$_REQUEST["recordDate"];
   else
     $recordDate=date("Y-m-d");

// print $output_check;

// 변수 초기화
$cursort = $_REQUEST["cursort"] ?? '';    // 현재 정렬모드 지정
$sortof = $_REQUEST["sortof"] ?? '';  // 클릭해서 넘겨준 값
$stable = $_REQUEST["stable"] ?? '';    // 정렬모드 변경할지 안할지 결정  
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
  
$sum=array(); 
	 
if(isset($_REQUEST["mode"]))
   $mode=$_REQUEST["mode"];
else 
   $mode="";        

if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
   $find=$_REQUEST["find"];
else
   $find="";

if(isset($_REQUEST["search"]))
   $search=$_REQUEST["search"];
else
   $search="";
  
// 기간을 정하는 구간
$fromdate = $_REQUEST["fromdate"] ?? '';	 
$todate = $_REQUEST["todate"] ?? '';

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
 
$orderby=" order by workday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 생산예정으로 구분		
  
if($search==""){
	 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
   }
elseif($search!="")
{ 
	  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
	  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$fromdate') and date('$Transtodate'))" . $orderby;				  		  		   
 }    
	  
// bootstrap.php에서 이미 DB 연결됨

   $counter=0;
   $doneday_arr=array();
   $virtualdoneday_arr=array();
   $workday_arr=array();
   $endworkday_arr=array();
   $measureday_arr=array();
   $drawday_arr=array();
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
   $filename1_arr=array(); 
   $filename2_arr=array(); 
   
   
   $num_arr=array();  // 일괄처리를 위한 번호 저장


 try{  
 
   // $sql="select * from mirae8440.work"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
			  $num=$row["num"];
			  include '_row.php';
	
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
	   
		   $workday_arr[$counter]=$workday;
		   $endworkday_arr[$counter]=$endworkday;
		   $measureday_arr[$counter]=$measureday;
		   $drawday_arr[$counter]=$drawday;
		   $doneday_arr[$counter]=$doneday;
		   $filename1_arr[$counter]=$filename1;
		   $filename2_arr[$counter]=$filename2;
		   
		   if($doneday=="")
		        $virtualdoneday_arr[$counter]=  date("Y-m-d",strtotime($workday . " +1 days"));   // 시공일 다음날
		      else
		        $virtualdoneday_arr[$counter]=$doneday;
		   
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
		   
		if( trim($secondord) =='우성' or trim($secondord) == '한산' or $pos>0 )
			$visitfee_arr[$counter]= 0;
		    else
				$visitfee_arr[$counter]= 100000 ;				 

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
											     $wideunit_arr[$counter] = 105000;  
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

						
				
						}		   	   
	 
		        
			   $counter++;	
		   } // end of 판매 / 불량		   		   	   
     // }   	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

?>		 

		 
<body >

<form name="board_form" id="board_form"  method="post" action="batch.php?mode=search">  
 <div class="container-fluid">
    <div class="card">				
			<div class="card">			
				<div class="card-body">			
				<div class="d-flex mb-3 mt-3 justify-content-center align-items-center">  
				
					<h5> 일괄처리(실측일, 시공일자, 사진패스, 청구일 등 강제조정)	 </h5>
				</div>
							
				<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  

			   <span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 

				<div id="showframe" class="card">
					<div class="card-header " style="padding:2px;">
						<div class="d-flex justify-content-center align-items-center">  
							기간 설정
						</div>
					</div>
					<div class="card-body">
						<div class="d-flex justify-content-center align-items-center">  	
							<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
							<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange"   onclick='pre_year()' > 전년도 </button>  
							<button type="button" id="three_month" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='three_month_ago()' > M-3월 </button>
							<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='prepre_month()' > 전전월 </button>	
							<button type="button" id="premonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='pre_month()' > 전월 </button> 						
							<button type="button" class="btn btn-outline-danger btn-sm me-1  change_dateRange"  onclick='this_today()' > 오늘 </button>
							<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_month()' > 당월 </button>
							<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_year()' > 당해년도 </button> 
						</div>
					</div>
				</div>		
						  
						   <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
						   <input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝"> 	&nbsp;
						   <input type="text" id="search" name="search" value="<?=$search?>" onKeyPress="if (event.keyCode==13){ document.getElementById('board_form').submit(); }" >					
							<button type="button" id="searchBtn" class="btn btn-dark  btn-sm mx-1"  ><i class="bi bi-search"></i> 검색  </button>	&nbsp;
				</div> 											
				<div class="d-flex mb-2 mt-2 justify-content-center align-items-center">       				   
						<span style="margin-left:10px;color:grey;"> 작업소장 :  </span>
					   <button type="button" class="btn btn-dark  btn-sm  me-2" onclick="List_name('')"> ALL </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('추영덕')"> 추영덕 </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('이만희')"> 이만희 </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('김운호')"> 김운호 </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('김상훈')"> 김상훈 </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('유영')"> 유영 </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('손상민')"> 손상민 </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('조장우')"> 조장우 </button> 	   	  				
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('박철우')"> 박철우 </button> 	   
					   <button type="button" class="btn btn-outline-secondary me-2 btn-sm" onclick="List_name('이인종')"> 이인종 </button> 	   
							
				 </div>											
				<div class="d-flex mb-2 mt-2 justify-content-center align-items-center">             
					<span class="text-primary me-2" > 처리(실행)일자 </span>
						 <input type="date" id="recordDate" name="recordDate" size="12" value="<?=$recordDate?>" class="me-2" > 	
						 <button type="button" id="measureBtn"  class="btn btn-dark btn-sm me-2"> 실측일 저장 </button>	
						 <button type="button" id="measureclearBtn"  class="btn btn-outline-danger btn-sm me-2"> 선택Clear </button>	
						 <button type="button" id="workdoneBtn"  class="btn btn-dark btn-sm me-2"> 시공완료일,사진패스 저장 </button>	
						 <button type="button" id="workdoneclearBtn"  class="btn btn-outline-danger btn-sm me-2"> 선택Clear </button>	&nbsp;								 
						 <button type="button" id="demandBtn"  class="btn btn-dark btn-sm me-2"> 청구일 저장 </button>							 
						 <button type="button" id="demandclearBtn"  class="btn btn-outline-danger btn-sm me-2"> 선택Clear </button>	&nbsp;								 
						 
				 </div>
				</div>			
		</div>
    <div class="card-body">		

	 <div id="grid" > </div>
     
	 </div> 	   
  </div> 
</div> <!-- end of container -->
</form>

  
  <form id=Form1 name="Form1">
    <input type=hidden id="num_arr" name="num_arr[]" >
    <input type=hidden id="recordDate_arr" name="recordDate_arr[]">
    <input type=hidden id="imagefile1" name="imagefile1[]">
    <input type=hidden id="imagefile2" name="imagefile2[]">
    <input type=hidden id="workchoice" name="workchoice">
  </form>  
  
<script>

$(document).ready(function(){
	
$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
	
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($measureday_arr);?> ;
 var arr3 = <?php echo json_encode($endworkday_arr);?> ;
 var arr4 = <?php echo json_encode($doneday_arr);?> ;
 var arr5 = <?php echo json_encode($workfeedate_arr);?> ;  
 var arr6 = <?php echo json_encode($worker_arr);?> ; 
 var arr7 = <?php echo json_encode($workplacename_arr);?> ;
 var arr8 = <?php echo json_encode($material_arr);?> ;
 var arr9 = <?php echo json_encode($firstord_arr);?> ;  
 var arr10= <?php echo json_encode($secondord_arr);?> ; 
 var arr11= <?php echo json_encode($demand_arr);?> ;
 var arr12= <?php echo json_encode($wide_arr);?> ; 
 var arr13= <?php echo json_encode($normal_arr);?> ;
 var arr14= <?php echo json_encode($narrow_arr);?> ;
 var arr15 = <?php echo json_encode($etc_arr);?> ;
 var arr16 = <?php echo json_encode($num_arr);?> ;
 var arr17 = <?php echo json_encode($drawday_arr);?> ;
 var arr18 = <?php echo json_encode($filename1_arr);?> ;
 var arr19 = <?php echo json_encode($filename2_arr);?> ;
 
 console.log(arr18);
 
 var total_sum=0; 
  
 var rowNum = <?php echo json_encode($counter); ?> ; 
 
 const data = [];
 const columns = [];	
 const COL_COUNT = 17;

 for(i=0;i<rowNum;i++) {			 
		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr3[i] ;						 						
				row[`col4`] = arr4[i] ;						 											 						
				row[`col5`] = arr5[i] ;						 											 						
				row[`col6`] = arr6[i] ;						 											 						
				row[`col7`] = arr7[i] ;						 											 						
				row[`col8`] = arr8[i] ;						 											 						
				row[`col9`] = arr9[i] ;						 											 						
				row[`col10`] = arr10[i] ;						 											 						
				row[`col11`] = arr11[i] ;						 											 						
				row[`col12`] = (arr12[i] == 0) ? "" : comma(arr12[i]);							 						
				row[`col13`] = (arr13[i] == 0) ? "" : comma(arr13[i]);							 						
				row[`col14`] = (arr14[i] == 0) ? "" : comma(arr14[i]);							 						
				row[`col15`] = (arr15[i] == 0) ? "" : comma(arr15[i]);							 						
				row[`col16`] = arr16[i] ;	// num은 콤마 없어야 함 Json 넘겨서 작업할때 문제 생김 주의						 						
				row[`col17`] = arr17[i] ;
				row[`col18`] = arr18[i] ;
				row[`col19`] = arr19[i] ;

						}
				data.push(row); 	 			 
 }
  		

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
	  bodyHeight: 700,					  					
	  columns: [ 				   
		{
		  header: '출고',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:80,
		  editor: 'text',		 		
		  align: 'center'
		},				   
		{
		  header: '실측',
		  name: 'col2',
		  sortingType: 'desc',
		  sortable: true,
		  width:80,
		  editor: 'text',	 		
		  align: 'center'
		},					   
		{
		  header: '생산예정',
		  name: 'col3',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,
		  editor: 'text',		 		
		  align: 'center'
		},				   
		{
		  header: '시공완료',
		  name: 'col4',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,
		  editor: 'text',		 		
		  align: 'center'
		},				   
		{
		  header: '시공비지급',
		  name: 'col5',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,
		  editor: 'text',		 		
		  align: 'center'
		},	

		{
		  header: '청구일',
		  name: 'col11',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:80,
		  editor: 'text',		 		
		  align: 'center'
		},		
		{
		  header: '시공소장',
		  name: 'col6',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:60,
		  editor: 'text',		 		
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col7',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:350,
		  editor: 'text',		 		
		  align: 'left'
		},
		{
		  header: '재질',
		  name: 'col8',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:150,
		  editor: 'text',		 		
		  align: 'left'
		},		
		{
		  header: '원청',
		  name: 'col9',
		  sortingType: 'desc',
		  sortable: true,		  
		  width: 80,
		  editor: 'text',		 		
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col10',
		  sortingType: 'desc',
		  sortable: true,		  
		  width: 80,
		  editor: 'text',		 		
		  align: 'center'
		},

		{
		  header: '막판',
		  name: 'col12',
		  width:30,
		  editor: 'text',	 		
		  align: 'center'
		},		

		{
		  header: '막판무',
		  name: 'col13',
		  width:30,
		  editor: 'text',		 		
		  align: 'center'
		},			
		{
		  header: '쪽쟘',
		  name: 'col14',
		  width:30,
		  editor: 'text',	 		
		  align: 'center'
		},			
		{
		  header: '기타',
		  name: 'col15',
		  width:30,
		  editor: 'text',		 		
		  align: 'center'
		},		
		{
		  header: 'N',
		  name: 'col16',
		  width:5,
		  editor: 'text',		 		
		  align: 'right'
		},		
		{
		  header: '도면작성',
		  name: 'col17',
		  width:100,
		  editor: 'text',		
		  align: 'right'
		},		
		{
		  header: '첨부사진명1',
		  name: 'col18',
		  width:100,	
		  editor: 'text',			  
		  align: 'center'
		},		
		{
		  header: '첨부사진명2',
		  name: 'col19',
		  width:100,	
		  editor: 'text',			  
		  align: 'center'
		}			
	  ],
	columnOptions: {
			resizable: true
		  },
	rowHeaders: ['rowNum','checkbox'],   // checkbox 형성
		  // pageOptions: {
		// useClient: false,
		// perPage: 20
	  // }	  
	});		

// grid 색상등 꾸미기
	var Grid = tui.Grid; // or require('tui-grid')
	Grid.applyTheme('default', {
			selection: {
				background: '#ccc',
				border: '#fdfcfc'
			  },
			  scrollbar: {
				background: '#e6eef5',
				thumb: '#d9d9d9',
				active: '#c1c1c1'
			  },
			  row: {
				hover: {
				  background: '#e6eef5'
				}
			  },
			  cell: {
				normal: {
				  background: '#FFFF',
				  border: '#e6eef5',
				  showVerticalBorder: true
				},
				header: {
				  background: '#e6eef5',
				  border: '#fdfcfc',
				  showVerticalBorder: true
				},
				rowHeader: {
				  border: '#e6eef5',
				  showVerticalBorder: true
				},
				editable: {
				  background: '#fbfbfb'
				},
				selectedHeader: {
				  background: '#e6eef5'
				},
				focused: {
				  border: '#e6eef5'
				},
				disabled: {
				  text: '#e6eef5'
				}
			  }	
	});	


	grid.on('dblclick', (e) => {
				
				
		var link = window.baseUrl + '/work/view.php?menu=no&num=' + grid.getValue(e.rowKey,"col16") ;
	   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
		
	   //  window.location.replace(link);     // 이전 페이지로 못돌아감
	   //  window.open(link);  	
	   if( grid.getValue(e.rowKey,"col16") !=='')
		   window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=100,width=1900,height=900");
		
	   console.log(e.rowKey);
	});		

	$("#measureBtn").click(function(){    	
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		let getval = grid.getValue(e,'col17') ;
		if(grid.getValue(e, 'col2')  == '') 
		{			
		  if(grid.getValue(e, 'col17') == '')
		          grid.setValue(e, 'col2',$("#recordDate").val())  // ; appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
			  else
				  grid.setValue(e, 'col2',getval)  // 도면설계일이 있으면 설계일을 넣는다.
		}
		  //console.log($("#recordDate").val());
		  //console.log(e);
		});			
	   $('#workchoice').val('measureday');
	   savegrid();
	});		
	
	$("#measureclearBtn").click(function(){  		
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col2','')  
		});	
	   $('#workchoice').val('measureday');		
	   savegrid();
	});	
	
	// 청구일 지정하기
	$("#demandBtn").click(function(){    	
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		let getval = $("#recordDate").val() ;
		if(grid.getValue(e, 'col11')  == '') 
		{			
		  if(grid.getValue(e, 'col17') == '')
		          grid.setValue(e, 'col11',$("#recordDate").val())  // ; appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
			  else
				  grid.setValue(e, 'col11',getval)  // 도면설계일이 있으면 설계일을 넣는다.
		}
		  //console.log($("#recordDate").val());
		  //console.log(e);
		});			
	   $('#workchoice').val('demandday');
	   savegrid();
	});		
	
	$("#demandclearBtn").click(function(){  		
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col11','')  
		});	
	   $('#workchoice').val('demandday');		
	   savegrid();
	});	
	
	$("#workdoneBtn").click(function(){    	
	    var arr18 = <?php echo json_encode($virtualdoneday_arr);?> ;
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		let getval = arr18[e] ;  // 시공일 하루 더 한 날짜
		if(grid.getValue(e, 'col4')  == '') 
		{			
		  if(grid.getValue(e, 'col1') == '')
		          grid.setValue(e, 'col4',$("#recordDate").val())  
			  else
				  grid.setValue(e, 'col4',getval)  			 

		}
			  
		  // 사진패스 누르면 처리하는 부분	  
			if(grid.getValue(e, 'col18') == null || grid.getValue(e, 'col18').trim() === '') 
				  grid.setValue(e, 'col18','pass')  

			if(grid.getValue(e, 'col19') == null || grid.getValue(e, 'col19').trim() === '') 
				  grid.setValue(e, 'col19','pass')  

		  //console.log($("#recordDate").val());
		  //console.log(e);
		});			
	   $('#workchoice').val('doneday');
	   savegrid();
	});		
	
	$("#workdoneclearBtn").click(function(){  		
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col4','')  
		});	
	   $('#workchoice').val('doneday');		
	   savegrid();
	});	
	

// grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
function savegrid() {		
		let num_arr               =  new Array();  
		let recordDate_arr        = new Array(); 	
		let image1_arr        = new Array(); 	
		let image2_arr        = new Array(); 	
		
        let workchoice = $('#workchoice').val();			
		
		let pushcount=0;
		
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){		
			num_arr.push(grid.getValue(e, 'col16'));
			  if(workchoice=='measureday')				    
				   recordDate_arr.push(grid.getValue(e, 'col2'));
			   if(workchoice=='demandday')				    
				   recordDate_arr.push(grid.getValue(e, 'col11'));
				 					 
			  if(workchoice=='doneday')	
			    {
					recordDate_arr.push(grid.getValue(e, 'col4'));
					image1_arr.push(grid.getValue(e, 'col18'));
					image2_arr.push(grid.getValue(e, 'col19'));
				}			   
			 
			 });	
			 
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
			$('#image1_arr').val(image1_arr);	
			$('#image2_arr').val(image2_arr);	
	
	    $.ajax({
			url: "batchprocess.php",
    	  	type: "post",		
   			data: $("#Form1").serialize(),
   			dataType:"json",
			success : function( data ){
				console.log( data);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
   }
   

});   

function addDays(date, days) {
  var date = new Date();	
  const clone = new Date(date);
  clone.setDate(date.getDate() + days)
  return clone;
}
 


function dis_text()
{  
		var dis_text = <?php echo json_encode($jamb_total ?? ''); ?>;
		$("#dis_text").val(dis_text);
}	

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

function List_name(worker)
{	
		var worker; 				
		var name = <?php echo json_encode($user_name ?? ''); ?> ;
		 
			$("#search").val(worker);	
			$('#board_form').submit();		// 검색버튼 효과
}

function move_url(href)
{
	 var search = <?php echo json_encode($search ?? ''); ?> ; 
	 if(search!='')
        document.location.href = href;		 
	   else
		  alert('소장을 선택해 주세요');   
	   
}

</script>

  </html>

</body>