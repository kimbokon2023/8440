<?php
require_once __DIR__ . '/../bootstrap.php';
   
 // 기간을 정하는 구간

 
include getDocumentRoot() . '/load_header.php';
 
$todate=date("Y-m-d");   // 현재일자 변수지정   

$common=" where  date(deadline)>=date(now()) order by deadline ";  // 출고예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.outorder " . $common; 							

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$counter=1;


?>

 <title> 덴크리 외주발주 출고리스트 (더블클릭하면 세부내역조회) </title>   
 
 <style>
/* 모바일 최적화 스타일 */
@media (max-width: 768px) {
    /* 컨테이너 및 카드 최적화 */
    #wrap {
        padding: 0.75rem 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 제목 최적화 */
    h1 {
        font-size: 1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        text-align: center !important;
        margin-bottom: 0.75rem !important;
        padding: 0 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* TUI Grid 숨기기 */
    #grid {
        display: none !important;
    }
    
    /* 모바일 카드 컨테이너 */
    #mobile-grid-cards {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 0.25rem !important;
    }
    
    .mobile-grid-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin: 0.5rem auto 0.75rem auto !important;
        padding: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        overflow-x: hidden;
        overflow-y: visible !important;
        box-sizing: border-box;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
        width: 100%;
        max-width: 100% !important;
        box-sizing: border-box;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .mobile-grid-card-label {
        font-weight: bold;
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 0.25rem;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .mobile-grid-card-value {
        font-size: 0.9rem;
        color: #333;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 0 !important;
        overflow: visible !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모든 텍스트 요소 강제 줄바꿈 */
    p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        display: inline !important;
        overflow: visible !important;
    }
}

/* PC 환경에서 모바일 카드 숨기기 */
@media (min-width: 769px) {
    #mobile-grid-cards {
        display: none !important;
    }
    #grid {
        display: block !important;
    }
}

/* 모달 최적화 */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem !important;
        max-width: calc(100% - 1rem) !important;
    }
    
    .modal-dialog.modal-lg {
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    .modal-content {
        border-radius: 0.5rem !important;
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        min-height: 50px !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-title {
        font-size: 1rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
    }
    
    .modal-header .btn-close {
        margin: 0 !important;
        padding: 0.5rem !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.9rem !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-footer .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.25rem !important;
    }
    
    /* SweetAlert2 모달 최적화 */
    .swal2-popup {
        width: 90% !important;
        max-width: 90% !important;
        padding: 1rem !important;
        font-size: 0.9rem !important;
    }
    
    .swal2-title {
        font-size: 1.1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-content {
        font-size: 0.875rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-actions {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    .swal2-confirm,
    .swal2-cancel {
        font-size: 0.875rem !important;
        padding: 0.5rem 1rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
    }
}
</style>
</head>


 <?php 

 if(isset($_REQUEST["check"]))
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   elseif(isset($_POST["check"]))
     $check=$_POST["check"]; // 미출고 리스트 POST사용
   else
     $check=''; 
 
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
  
 $cursort=$_REQUEST["cursort"] ?? '';    // 현재 정렬모드 지정
 $sortof=$_REQUEST["sortof"] ?? '';  // 클릭해서 넘겨준 값
 $stable=$_REQUEST["stable"] ?? '';    // 정렬모드 변경할지 안할지 결정
   
  $sum=array(); 
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";        
 
 if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
 $find=$_REQUEST["find"];
 
  
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"] ?? '';
$todate=$_REQUEST["todate"] ?? '';	 

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
 
   $counter=0;
   $num_arr=array();
   $workday_arr=array();
   $testday_arr=array();
   $workplacename_arr=array();
   $worker_arr=array();
   $secondord_arr=array();
   $material_arr=array();
   $sum_arr=array();
   $main_draw_arr=array();
   $lc_draw_arr=array();
   $type_arr=array();
   $car_inside_arr=array();
   $detail_arr=array();
   $sum1=array_fill(0, 1000, 0);
   $sum2=array_fill(0, 1000, 0);
   $sum3=array_fill(0, 1000, 0);
   $sum4=array_fill(0, 1000, 0);
   $sum5=array_fill(0, 1000, 0);

   $sum=array();
   
 try{   
   // $sql="select * from mirae8440.outorder"; 		 
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
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widehap=$row["widehap"] ?? '';
			  $normalhap=$row["normalhap"] ?? '';
			  $smallhap=$row["smallhap"] ?? '';
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];  	   
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $hpi=$row["hpi"];	   
			  $delicompany=$row["delicompany"];	   
			  $delipay=$row["delipay"];	   
				  
			  $type1=$row["type1"];			  
			  $inseung=$row["inseung"] ?? '';			  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];			  
			  $car_inside1=$row["car_inside1"];			  
			  $order_com1=$row["order_com1"];			  
			  $order_text1=$row["order_text1"];			  
			  $order_com2=$row["order_com2"];			  
			  $order_text2=$row["order_text2"];			  
			  $order_com3=$row["order_com3"];			  
			  $order_text3=$row["order_text3"];			  
			  $order_com4=$row["order_com4"];			  
			  $order_text4=$row["order_text4"];			  
			  $lc_draw=$row["lc_draw"];			  
			  $lclaser_com=$row["lclaser_com"];			  
			  $lclaser_date=$row["lclaser_date"];			  
			  $lcbending_date=$row["lcbending_date"];			  
			  $lcwelding_date=$row["lcwelding_date"];			  
			  $lcpainting_date=$row["lcpainting_date"];			  
			  $lcassembly_date=$row["lcassembly_date"];			  
			  $main_draw=$row["main_draw"];			  
			  $eunsung_make_date=$row["eunsung_make_date"];			  
			  $eunsung_laser_date=$row["eunsung_laser_date"];			  
			  $mainbending_date=$row["mainbending_date"];			  
			  $mainwelding_date=$row["mainwelding_date"];			  
			  $mainpainting_date=$row["mainpainting_date"];			  
			  $mainassembly_date=$row["mainassembly_date"];			  
			  $memo2=$row["memo2"];		

		$type2=$row["type2"];
		$type3=$row["type3"];
		$type4=$row["type4"];
		$type5=$row["type5"];
		$type6=$row["type6"];
		$type7=$row["type7"];
		$type8=$row["type8"];
		$type9=$row["type9"];
		$type10=$row["type10"];			  
		$inseung2=$row["inseung2"];
		$inseung3=$row["inseung3"];
		$inseung4=$row["inseung4"];
		$inseung5=$row["inseung5"];
		$inseung6=$row["inseung6"];
		$inseung7=$row["inseung7"];
		$inseung8=$row["inseung8"];
		$inseung9=$row["inseung9"];
		$inseung10=$row["inseung10"];
		$car_inside2=$row["car_inside2"];
		$car_inside3=$row["car_inside3"];
		$car_inside4=$row["car_inside4"];
		$car_inside5=$row["car_inside5"];
		$car_inside6=$row["car_inside6"];
		$car_inside7=$row["car_inside7"];
		$car_inside8=$row["car_inside8"];
		$car_inside9=$row["car_inside9"];
		$car_inside10=$row["car_inside10"];  
			  
			  
			  $sum1[$counter] += (int)$su;
			  $sum2[$counter] += (int)$bon_su;
			  $sum3[$counter] += (int)$lc_su;
			  $sum4[$counter] += (int)$etc_su;
			  $sum5[$counter] += (int)$air_su;			  

		      $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lc_draw=trans_date($lc_draw);
		      $lclaser_date=trans_date($lclaser_date);
		      $lcbending_date=trans_date($lcbending_date);
		      $lcwelding_date=trans_date($lcwelding_date);
		      $lcpainting_date=trans_date($lcpainting_date);
		      $lcassembly_date=trans_date($lcassembly_date);
		      $main_draw=trans_date($main_draw);			
		      $eunsung_make_date=trans_date($eunsung_make_date);			
		      $eunsung_laser_date=trans_date($eunsung_laser_date);			
		      $mainbending_date=trans_date($mainbending_date);			
		      $mainwelding_date=trans_date($mainwelding_date);			
		      $mainpainting_date=trans_date($mainpainting_date);			
		      $mainassembly_date=trans_date($mainassembly_date);													  	  				  	
					
	       $sum_material=$material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6; 
	   
	   
       $typeAll="";
	   $tmp="";
       for($i=1;$i<=10;$i++) {
		   $tmp='type' . $i;
		 if($i>1 && $$tmp!='' )
      			 $typeAll .= '/' . $$tmp;   
		     else
				  $typeAll .= $$tmp;   
	   }      
	   $car_insideAll="";
	   $tmp="";
       for($i=1;$i<=10;$i++) {
		   $tmp='car_inside' . $i;
		 if($i>1 && $$tmp!='' )
      			 $car_insideAll .= '/' . $$tmp;   
		     else
				  $car_insideAll .= $$tmp;   
	   }
	   
	   
		   $num_arr[$counter] = $num;
		   $workday_arr[$counter] = $deadline;
		   $testday_arr[$counter] = $testday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
		   $worker_arr[$counter]=$worker;
		   $secondord_arr[$counter]=$secondord;
		   $type_arr[$counter]=$typeAll;
		   $car_inside_arr[$counter]=$car_insideAll;
		   
		   		 $workitem="";
				 
				 if($su!="")
					    $workitem= $su . " , "; 
				 if($bon_su!="")
					    $workitem .="본 " . $bon_su . ", "; 					
				 if($lc_su!="")
					    $workitem .="L/C " . $lc_su . ", "; 											
				 if($etc_su!="")
					    $workitem .="기타 "  . $etc_su . ", "; 																	
				 if($air_su!="")
					    $workitem .="공기청정기 "  . $air_su . " "; 				   
		   
		   $detail_arr[$counter]=$workitem;
		   
		   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {   
    print "오류: ".$Exception->getMessage();    
}  		 
		 
			?>
		 
<body>

 <div id="wrap">
 
 <h3> &nbsp; 덴크리 외주발주 납품예정 리스트 </h3>
  <br>
	 <div id="grid" >
  
  </div>
  <!-- 모바일 카드 컨테이너 -->
  <div id="mobile-grid-cards" style="display: none;"></div>
     <div class="clear"></div> 		 

	 </div>

<script>
    
$(document).ready(function(){
	
var num = <?php echo json_encode($num_arr);?> ;
var numcopy = new Array(); 

 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;  
 var arr3 = <?php echo json_encode($secondord_arr);?> ;
 var arr4 = <?php echo json_encode($type_arr);?> ;
 var arr5 = <?php echo json_encode($car_inside_arr);?> ;
 var arr6 = <?php echo json_encode($detail_arr);?>;
 var arr7 = <?php echo json_encode($sum_arr);?> ; 
 
 var hap1 = <?php echo json_encode($sum1);?>;
 var hap2 = <?php echo json_encode($sum2);?>;
 var hap3 = <?php echo json_encode($sum3);?>;
 var hap4 = <?php echo json_encode($sum4);?>;
 var hap5 = <?php echo json_encode($sum5);?>;

 var total_sum=0;
 var hap1_sum=0;
 var hap2_sum=0;
 var hap3_sum=0;
 var hap4_sum=0;
 var hap5_sum=0;
 var sum_tmp=0;
 var tmp="";
   
 var rowNum = "<? echo $counter; ?>" ; 
 
 var j=0;
 var past;
 past=arr1[0];
  var count=0;  // 전체줄수 카운트

 const COL_COUNT = 6;

 const data = [];
 const columns = [];	
 
 for(i=0;i<rowNum;i++) {		
 row = { name: j };		
 
			 if(arr1[i]!=past)
			   {
				   if(hap1_sum>0) tmp = tmp +  hap1_sum + "(set), " ;
				   if(hap2_sum>0) tmp = tmp +  " 본청장 " + hap2_sum + "," ;
				   if(hap3_sum>0) tmp = tmp +  " L/C  " + hap3_sum + "," ;
				   if(hap4_sum>0) tmp = tmp +  " 기타  " + hap4_sum + "," ;
				   if(hap5_sum>0) tmp = tmp +  " Air " + hap5_sum ;
				   				   
							 for (let k = 0; k < COL_COUNT; k++ ) {				
								row[`col1`] = '' ;						 						
								row[`col2`] = '' ;					 						
								row[`col3`] = '' ;						 						
								row[`col4`] = '' ;						 						
								row[`col5`] = '' ;					 						
								row[`col6`] = tmp ;				 						
							   }
							 data.push(row); 
								 numcopy[count] = 0 ;
								 count++;							 
							 j++;							 
							 row = { name: j };
							 for (let k = 0; k < COL_COUNT; k++ ) {				
								row[`col1`] = '' ;						 						
								row[`col2`] = '' ;					 						
								row[`col3`] = '' ;						 						
								row[`col4`] = '' ;						 						
								row[`col5`] = '' ;						 						
								row[`col6`] = '' ;						 						
							   }
							 data.push(row); 
								 numcopy[count] = 0 ;
								 count++;							 
							 j++;							 
							 hap1_sum=0;
							 hap2_sum=0;
							 hap3_sum=0;
							 hap4_sum=0;
							 hap5_sum=0;
							 tmp="";
			   } 
 
  	         row = { name: j };						 
			 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i];					 						
				row[`col2`] = arr2[i];					 						
				row[`col3`] = arr3[i];					 						
				row[`col4`] = arr4[i];					 						
				row[`col5`] = arr5[i];					 						
				row[`col6`] = arr6[i];					 						
			   }
		  	 data.push(row); 
			 
             numcopy[count] = num[i] ; 			 
			 count++;				 
			 
			 hap1_sum = hap1_sum + hap1[i];
			 hap2_sum = hap2_sum + hap2[i];
			 hap3_sum = hap3_sum + hap3[i];
			 hap4_sum = hap4_sum + hap4[i];
			 hap5_sum = hap5_sum + hap5[i];  
				 
			 past=arr1[i];
			 j++;
			 
   }
   
   // 마지막칸에 소계를 찍어주는 부분입니다.
   
			 tmp= hap1_sum + "(set), "  +  " 본청장 " + hap2_sum + ","  +  " L/C  " + hap3_sum  +  " 기타  " + hap4_sum  +  " Air " + hap5_sum ;      
			 row = { name: j };
			 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = '' ;						 						
				row[`col2`] = '' ;					 						
				row[`col3`] = '' ;						 						
				row[`col4`] = '' ;						 						
				row[`col5`] = '' ;						 						
				row[`col6`] = tmp ;						 						
			   }
			 data.push(row);  	
		 numcopy[count] = 0 ;
		 count++;				 

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
	  bodyHeight: 800,					  					
	  columns: [ 				   
		{
		  header: '납품예정일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:120,	
		  align: 'center'
		},	
		{
		  header: '현장명',
		  name: 'col2',
		  width:450,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col3',
		  width:120, 		
		  align: 'center'
		},
		{
		  header: '타입',
		  name: 'col4',
		  width:300,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		  align: 'center'
		},	
		{
		  header: 'Car Inside',
		  name: 'col5',
		  width:300,		
		  align: 'center'
		},
		{
		  header: '납품내역',
		  name: 'col6',
		  width:300, 		
		  align: 'center'
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
	
// 더블클릭 이벤트	
grid.on('dblclick', (e) => {
	
    var link = 'http://8440.co.kr/outorder/view.php?num=' + numcopy[e.rowKey] ;
   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
	
   //  window.location.replace(link);     // 이전 페이지로 못돌아감
   //  window.open(link);  	
   if(numcopy[e.rowKey]>0)
       window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1800,height=900");
	
   console.log(e.rowKey);
});		
 	
	// 모바일 카드 렌더링 함수
	function renderMobileCards() {
		var isMobile = window.innerWidth <= 768;
		var gridEl = document.getElementById('grid');
		var cardsContainer = document.getElementById('mobile-grid-cards');
		
		if (!cardsContainer) return;
		
		if (isMobile) {
			// 모바일: 그리드 숨기고 카드 표시
			if (gridEl) gridEl.style.display = 'none';
			cardsContainer.style.display = 'block';
			
			// 기존 카드 제거
			cardsContainer.innerHTML = '';
			
			// 컬럼 정보
			var columnConfig = {
				'col1': { label: '납품예정일', width: 120 },
				'col2': { label: '현장명', width: 450 },
				'col3': { label: '발주처', width: 120 },
				'col4': { label: '타입', width: 300 },
				'col5': { label: 'Car Inside', width: 300 },
				'col6': { label: '납품내역', width: 300 }
			};
			
			// 각 행을 카드로 변환
			data.forEach(function(row, index) {
				// 빈 행이나 소계 행은 건너뛰기
				if (numcopy[index] === 0 && row.col1 === '' && row.col2 === '' && row.col3 === '' && row.col4 === '' && row.col5 === '') {
					return;
				}
				
				var card = document.createElement('div');
				card.className = 'mobile-grid-card';
				card.setAttribute('data-row-index', index);
				
				var cardContent = '';
				
				// 각 컬럼을 카드 아이템으로 추가
				Object.keys(columnConfig).forEach(function(colName) {
					var config = columnConfig[colName];
					var value = row[colName] || '';
					
					if (value !== '' && value !== null && value !== undefined) {
						cardContent += '<div class="mobile-grid-card-item">';
						cardContent += '<span class="mobile-grid-card-label">' + config.label + '</span>';
						cardContent += '<span class="mobile-grid-card-value">' + (value || '') + '</span>';
						cardContent += '</div>';
					}
				});
				
				if (cardContent) {
					card.innerHTML = cardContent;
					cardsContainer.appendChild(card);
				}
			});
			
			// 카드 클릭 이벤트 (더블클릭 대신)
			cardsContainer.addEventListener('click', function(e) {
				var targetCard = e.target.closest('.mobile-grid-card');
				if (targetCard) {
					var rowIndex = parseInt(targetCard.getAttribute('data-row-index'));
					if (numcopy[rowIndex] > 0) {
						var link = 'http://8440.co.kr/outorder/view.php?num=' + numcopy[rowIndex];
						window.open(link, "_blank");
					}
				}
			});
		} else {
			// PC: 카드 숨기고 그리드 표시
			if (gridEl) gridEl.style.display = 'block';
			cardsContainer.style.display = 'none';
		}
	}
	
	// 초기 렌더링 및 리사이즈 이벤트
	renderMobileCards();
	$(window).on('resize', function() {
		setTimeout(renderMobileCards, 100);
	});
	
	// 그리드 데이터 변경 시 카드 업데이트
	grid.on('afterChange', function() {
		if (window.innerWidth <= 768) {
			renderMobileCards();
		}
	});
	
});	
	
	
 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
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

</script>
 


   <div class="clear"></div>	
   
  

  </body> 

  </html>
