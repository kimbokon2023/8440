<?php
 session_start();
 $level= $_SESSION["level"];
 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/steel.css"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>	
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

 <title> Car 인승 산출 계산기 </title> 
 </head>
 <?php 

isset($_REQUEST["fromdate"])  ? $fromdate = $_REQUEST["fromdate"] :   $fromdate=""; 
isset($_REQUEST["todate"])  ? $todate = $_REQUEST["todate"] :   $todate=""; 
isset($_REQUEST["recordDate"])  ? $recordDate = $_REQUEST["recordDate"] :   $recordDate=date("Y-m-d");

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
  
 
 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}  
  
// print $output_check;
  
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 $sortof=$_REQUEST["sortof"];  // 클릭해서 넘겨준 값
 $stable=$_REQUEST["stable"];    // 정렬모드 변경할지 안할지 결정
 
 if(isset($_REQUEST["sortof"]))
    {

	if($sortof==1 and $stable==0) {      //접수일 클릭되었을때
		
	 if($cursort!=1)
	    $cursort=1;
      else
	     $cursort=2;
	    } 
	if($sortof==2 and $stable==0) {     //납기일 클릭되었을때
		
	 if($cursort!=3)
	    $cursort=3;
      else
		 $cursort=4;			
	   }	   
	if($sortof==3 and $stable==0) {     //실측일 클릭되었을때
		
	 if($cursort!=5)
	    $cursort=5;
      else
		 $cursort=6;			
	   }	   	   
	if($sortof==4 and $stable==0) {     //도면작성일 클릭되었을때
		
	 if($cursort!=7)
	    $cursort=7;
      else
		 $cursort=8;			
	   }	   
	if($sortof==5 and $stable==0) {     //출고일 클릭되었을때
		
	 if($cursort!=9)
	    $cursort=9;
      else
		 $cursort=10;			
	   }		   
	if($sortof==6 and $stable==0) {     //청구 클릭되었을때
		
	 if($cursort!=11)
	    $cursort=11;
      else
		 $cursort=12;			
	   }		   
	}	   
  else 
  {
     $sortof=0;     
	 $cursort=0;
  }
  
  
  $sum=array(); 
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";        
 
 if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
 $find=$_REQUEST["find"];
 
if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,7) ;
	$fromdate=$fromdate . "-01";
}
if($todate=="")
{
	$todate=date("Y-m-d");
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

$orderby=" order by workday desc ";
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		
  
if($mode=="search"){
		  if($search==""){
					 $sql="select * from mirae8440.ceiling where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql ="select * from mirae8440.ceiling where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delivery like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$fromdate') and date('$Transtodate'))" . $orderby ;				  		  		   
			     }    
}
  else
  {
    $sql="select * from mirae8440.ceiling where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
  }
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

   $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $secondord_arr=array();
   $sum_arr=array();
   $delivery_arr=array();
   $content_arr=array();
   $num_arr=array();
   $demand_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
 
   // $sql="select * from mirae8440.ceiling"; 		 
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
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $delicompany=$row["delicompany"];
			  $delivery=$row["delivery"];
			  $delipay=$row["delipay"];
			  
			  $workday=$row["workday"];
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];

			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];			  
			  $car_insize=$row["car_insize"];			  
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

            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];				 

              $demand=trans_date($demand);			  
			  
			  $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

			  $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),  본천장 : " . $sum[1] . " (EA),  L/C : "  . $sum[2] . "  (EA), 기타 : "  . $sum[3] . "  (EA), 공기청정기 : "  . $sum[4] . " (EA) "; 			   			  
			
			
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
						
				 $part="";
				 if($order_com1!="")
					    $part= $order_com1 . "," ; 
				 if($order_com2!="")
					    $part .= $order_com2 . ", " ; 						
				 if($order_com3!="")
					    $part .= $order_com3 . ", " ; 												
				 if($order_com4!="")
					    $part .= $order_com4 . ", " ; 
						
                 $deli_text="";
				 if($delivery!="" || $delipay!=0)
				 		  $deli_text = $delivery . " " . $delipay ;  		     	
	   
		   $workday_arr[$counter]=$workday;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;    
		   $delivery_arr[$counter]=$delivery;    
		   $secondord_arr[$counter]=$secondord;   
		   $num_arr[$counter]=$num;    
		   $demand_arr[$counter]=$demand;    

		   $content_arr[$counter]=$type . " " . $inseung ." " . $car_insize ." " .  $memo ." " .  $memo2 ." " .  
   
    $sum_arr[$counter]=$workitem;
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
$all_sum=$sum1 + $sum2 + $sum3;		 		 
		 
			?>
		 
<body >

 <div id="wrap">
  
   <div id="content" style="width:1850px;">			 
   <form name="board_form" id="board_form"  method="post" action="batchDB.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">     
      
     <div class="clear"></div> 		 
	 
<fieldset class="groupbox-border"> 
<legend class="groupbox-border" style="color:blue;"> (카 인승 산출 계산기 - 선형 보간법) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </legend> 	
<div class="input-group p-1 mb-1">

		 <span class="input-group-text ">  카 사이즈(Car inside) &nbsp; </span>	  
		 <span class="input-group-text ">  너비(Width) :&nbsp;  <input type="number" id="carwidth" name="carwidth" value="1600" size=5> </span>	  
		 <span class="input-group-text ">  폭(Depth):&nbsp; <input type="number" id="cardepth" name="cardepth" value="1500" size=5> </span> 

</div>
<div class="input-group p-1 mb-1">
		 <span class="input-group-text ">  출입구 사이즈 &nbsp; </span>	  
		 <span class="input-group-text ">  폭 :&nbsp;  <input type="number" id="gatewidth" name="gatewidth" value="900" size=5> </span>	  
		 <span class="input-group-text ">  높이:&nbsp; <input type="number" id="gateheight" name="gateheight" value="2100" size=5> </span> 
		 <span class="input-group-text ">  카주 깊이 :&nbsp; <input type="number" id="columndepth" name="columndepth" value="80" size=5>  </span> 
		 <span class="input-group-text ">  유효바닥 면적(㎡) &nbsp;  <input type="number" id="Area" name="Area" value="" size=5>  </span> 
		 <span class="input-group-text ">  정격하중(kg) &nbsp;  <input type="number" id="basicweight" name="basicweight" value="" size=5>  </span> 
		</div> 

	<div class="input-group p-1 mb-1">
		 <span class="input-group-text text-center">  적재하중 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; </span>	  
		 <span class="input-group-text text-center">  최대 유효면적  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp; </span>	  
	</div> 
	<div class="input-group p-1 mb-1">
		 <span class="input-group-text">  <input type="number" id="weightup" name="weightup" value="" size=1>  &nbsp; </span>	  
		 <span class="input-group-text">  <input type="number" id="weightupArea" name="weightupArea" value="" size=1>  &nbsp; </span>	  
	</div> 
	<div class="input-group p-1 mb-1">
		 <span class="input-group-text">  <input type="text" id="weightmid" name="weightmid" value="X" size=20>  &nbsp; </span>	  
		 <span class="input-group-text">  <input type="number" id="weightmidArea" name="weightmidArea" value="" size=1>  &nbsp; </span>	  
	</div> 
	<div class="input-group p-1 mb-1">
		 <span class="input-group-text">  <input type="number" id="weightbottom" name="weightbottom" value="" size=1>  &nbsp; </span>	  
		 <span class="input-group-text">  <input type="number" id="weightbottomArea" name="weightbottomArea" value="" size=1>  &nbsp; </span>	  
	</div> 
	<div class="input-group p-1 mb-1">
		 <span class="input-group-text">  정격하중 X = &nbsp; <input type="text" id="formula" name="formula" value="" size=100>  &nbsp; </span>	  	  
	</div> 
	<div class="input-group p-1 mb-1">
		 <span class="input-group-text ">  최소 카 면적(㎡)&nbsp; </span>	  
		 <span class="input-group-text">  <input type="number" id="resultcarArea" name="resultcarArea" value="" size=1>  &nbsp; </span>	  
		 <span class="input-group-text ">  인승 계산결과 &nbsp; </span>	  
		 <span class="input-group-text text-danger">  <input type="text" id="resultcarinside" name="resultcarinside" value="" size=5>  &nbsp; 인승 &nbsp; &nbsp; &nbsp; &nbsp; </span>	  

		</div> 	
	
</fieldset>	
	 
	 &nbsp; <button type="button" id="calBtn"  class="btn btn-secondary"> 계산하기 </button>	&nbsp;		

	 </h3>
    <div class="clear"></div> 		 
	<BR> <br> 	
    <span style="font-size:20px;color:grey;"> ※ 카사이즈 일방형 15인승 산출 예시 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
    <div class="clear"></div> 		
    <div style="width:1800px;">		
	 <img src="../img/carsize1.jpg"> 
	 <img src="../img/carsize2.jpg"> 
	 <img src="../img/carsize3.jpg"> 
	 </div> 
  </div>
     <div class="clear"></div> 		 
	 </form>
	 </div>

   <div class="clear"></div>	
   
   </div> 	   
  </div> <!-- end of wrap -->
  
  <form id=Form1 name="Form1">
    <input type=hidden id="num_arr" name="num_arr[]" >
    <input type=hidden id="recordDate_arr" name="recordDate_arr[]">
  </form>
  
  </body>

</html>  

  
<script>	
	
$(document).ready(function(){
	
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($address_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($delivery_arr);?> ;
 var arr6 = <?php echo json_encode($content_arr);?> ;
 var arr7 = <?php echo json_encode($secondord_arr);?> ;
 var arr8 = <?php echo json_encode($num_arr);?> ;
 var arr9 = <?php echo json_encode($demand_arr);?> ;
 var total_sum=0; 
  
 var rowNum = "<? echo $counter; ?>" ; 
 var jamb_total = "<? echo $jamb_total; ?>";  
 
 const data = [];
 const columns = [];	 
 
	
$("#calBtn").click(function(){    	
  let Area = 0 ;
  const carwidth = $("#carwidth").val() ;
  const cardepth = $("#cardepth").val() ;
  
  let gatewidth=0;
  
  arrweight = [];
arrweight.push(100 );
arrweight.push(180 );
arrweight.push(225 );
arrweight.push(300 );
arrweight.push(375 );
arrweight.push(400 );
arrweight.push(450 );
arrweight.push(525 );
arrweight.push(600 );
arrweight.push(630 );
arrweight.push(675 );
arrweight.push(750 );
arrweight.push(800 );
arrweight.push(825 );
arrweight.push(900 );
arrweight.push(975 );
arrweight.push(1000);
arrweight.push(1050);
arrweight.push(1125);
arrweight.push(1200);
arrweight.push(1250);
arrweight.push(1275);
arrweight.push(1350);
arrweight.push(1425);
arrweight.push(1500);
arrweight.push(1600);
arrweight.push(2000);
arrweight.push(2500);
arrweight.push(2600);
arrweight.push(2700);
arrweight.push(2800);
arrweight.push(2900);
arrweight.push(3000);
arrweight.push(3100);
arrweight.push(3200);
arrweight.push(3300);
arrweight.push(3400);
arrweight.push(3500);
arrweight.push(3600);
arrweight.push(3700);
arrweight.push(3800);
arrweight.push(3900);
arrweight.push(4000);
arrweight.push(4100);
arrweight.push(4200);
arrweight.push(4300);
arrweight.push(4400);
arrweight.push(4500);
arrweight.push(4600);
arrweight.push(4700);
arrweight.push(4800);
arrweight.push(4900);
arrweight.push(5000);
arrweight.push(5100);
arrweight.push(5200);
arrweight.push(5300);
arrweight.push(5400);
arrweight.push(5500);
arrweight.push(5600);
arrweight.push(5700);
arrweight.push(5800);
arrweight.push(5900);
arrweight.push(6000);
arrweight.push(6100);
arrweight.push(6200);
arrweight.push(6300);
arrweight.push(6400);
arrweight.push(6500);
arrweight.push(6600);
arrweight.push(6700);
arrweight.push(6800);
arrweight.push(6900);
arrweight.push(7000);
arrweight.push(7100);
arrweight.push(7200);
arrweight.push(7300);
arrweight.push(7400);
arrweight.push(7500);
arrweight.push(7600);
arrweight.push(7700);
arrweight.push(7800);
arrweight.push(7900);
arrweight.push(8000);
arrweight.push(8100);
arrweight.push(8200);
arrweight.push(8300);
arrweight.push(8400);
arrweight.push(8500);
arrweight.push(8600);
arrweight.push(8700);
arrweight.push(8800);
arrweight.push(8900);
arrweight.push(9000);
arrweight.push(9100);
arrweight.push(9200);
arrweight.push(9300);

//유효면적 배열
arrArea = [];
arrArea.push(0.37 );
arrArea.push(0.58 );
arrArea.push(0.70 );
arrArea.push(0.90 );
arrArea.push(1.10 );
arrArea.push(1.17 );
arrArea.push(1.30 );
arrArea.push(1.45 );
arrArea.push(1.60 );
arrArea.push(1.66 );
arrArea.push(1.75 );
arrArea.push(1.90 );
arrArea.push(2.00 );
arrArea.push(2.05 );
arrArea.push(2.20 );
arrArea.push(2.36 );
arrArea.push(2.40 );
arrArea.push(2.50 );
arrArea.push(2.65 );
arrArea.push(2.80 );
arrArea.push(2.90 );
arrArea.push(2.95 );
arrArea.push(3.10 );
arrArea.push(3.25 );
arrArea.push(3.40 );
arrArea.push(3.56 );
arrArea.push(4.20 );
arrArea.push(5.00 );
arrArea.push(5.16 );
arrArea.push(5.32 );
arrArea.push(5.48 );
arrArea.push(5.64 );
arrArea.push(5.8  );
arrArea.push(5.96 );
arrArea.push(6.12 );
arrArea.push(6.28 );
arrArea.push(6.44 );
arrArea.push(6.6  );
arrArea.push(6.76 );
arrArea.push(6.92 );
arrArea.push(7.08 );
arrArea.push(7.24 );
arrArea.push(7.4  );
arrArea.push(7.56 );
arrArea.push(7.72 );
arrArea.push(7.88 );
arrArea.push(8.04 );
arrArea.push(8.2  );
arrArea.push(8.36 );
arrArea.push(8.52 );
arrArea.push(8.68 );
arrArea.push(8.84 );
arrArea.push(9    );
arrArea.push(9.16 );
arrArea.push(9.32 );
arrArea.push(9.48 );
arrArea.push(9.64 );
arrArea.push(9.8  );
arrArea.push(9.96 );
arrArea.push(10.12);
arrArea.push(10.28);
arrArea.push(10.44);
arrArea.push(10.6 );
arrArea.push(10.76);
arrArea.push(10.92);
arrArea.push(11.08);
arrArea.push(11.24);
arrArea.push(11.4 );
arrArea.push(11.56);
arrArea.push(11.72);
arrArea.push(11.88);
arrArea.push(12.04);
arrArea.push(12.2 );
arrArea.push(12.36);
arrArea.push(12.52);
arrArea.push(12.68);
arrArea.push(12.84);
arrArea.push(13   );
arrArea.push(13.16);
arrArea.push(13.32);
arrArea.push(13.48);
arrArea.push(13.64);
arrArea.push(13.8 );
arrArea.push(13.96);
arrArea.push(14.12);
arrArea.push(14.28);
arrArea.push(14.44);
arrArea.push(14.6 );
arrArea.push(14.76);
arrArea.push(14.92);
arrArea.push(15.08);
arrArea.push(15.24);
arrArea.push(15.4 );
arrArea.push(15.56);
arrArea.push(15.72);
arrArea.push(15.88);
  
  if(carwidth<1400)
	  gatewidth = 800;
  if(carwidth>=1400 && carwidth<1800) 
	  gatewidth = 900;
  if(carwidth>=1800 && carwidth<2000) 
	  gatewidth = 1000;
  if(carwidth>=2000 )
	  gatewidth = 1100;  
  
let weightup = 0;
let weightbottom = 0;
    
  Area = carwidth/1000 * cardepth/1000 +  (gatewidth/1000 * 0.08) ;
  let BasicPerson ; 
  
  $("#Area").val(Area) ;
  $("#weightmidArea").val(Area) ;

for(i=0;i<arrArea.length-1;i++)
	if(Area > arrArea[i] &&  Area < arrArea[i+1])
	{
		weightup = arrweight[i];
		weightbottom = arrweight[i+1];
		console.log(weightup);
	}
  
// 유효면적 validareaup, validareabottom 추출
let validareaup=0;
let validareabottom=0;

for(i=0;i<arrweight.length;i++)
{
	if(weightup == arrweight[i])	
		validareaup = arrArea[i];		
	if(weightbottom == arrweight[i])	
		validareabottom = arrArea[i];		
}
  
  
  $("#weightupArea").val(validareaup) ;   
  $("#weightbottomArea").val(validareabottom) ; 
  
  $("#weightup").val(weightup) ; 
  $("#weightbottom").val(weightbottom) ; 
  $("#gatewidth").val(gatewidth) ;  // gate width 설정한다.

// 중량 X 구함
 let x;
 
 x = (Area - validareaup) / ( validareabottom - validareaup) * ( weightbottom - weightup ) + weightup ;
 
 let str = " ( " + Area +" - " + validareaup + " ) / ( " + validareabottom + " - " + validareaup + ") * ( " + weightbottom + " - " + weightup + " ) + " +  weightup ;
 
 $("#formula").val(str);
 $("#basicweight").val(x);
 
 let num1 = x/75 ;
 
 console.log(num1);
 $("#resultcarinside").val(Math.floor(num1));

  
  // if(Area < 1.73)
	  // BasicPerson = 9 ; 
  // else if(Area < 1.87)
	  // BasicPerson = 10 ; 
  // else if(Area < 2.15)
	  // BasicPerson = 12 ; 
  // else if(Area < 2.43)
	  // BasicPerson = 13 ; 
  // else if(Area < 2.85)
	  // BasicPerson = 15 ; 
  // else if(Area < 2.99)
	  // BasicPerson = 18 ; 
  // else if(Area < 3.41)
	  // BasicPerson = 21 ; 
  // else if(Area < 3.79)
	  // BasicPerson = 24 ;   
  // else if(Area > 3.79)
	  // BasicPerson = '견적요함' ; 
  
  // $("#resultcarinside").val(BasicPerson) ;

   
});		
	

});		


</script>