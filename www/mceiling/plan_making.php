
 <?php
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
   
 // 기간을 정하는 구간
 
$todate=date("Y-m-d");   // 현재일자 변수지정   

$common=" where  date(deadline)>=date(now()) order by deadline ";  // 출고예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.ceiling " . $common; 							

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$counter=1;

 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}

   ?>

 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/steel.css">
 <link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>

<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 <title> 생산일정(생산완료 제외) 리스트 </title> 
 </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

 <?php 

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
   $workday_arr=array();
   $testday_arr=array();
   $workplacename_arr=array();
   $worker_arr=array();
   $$secondord_arr=array();
   $material_arr=array();
   $sum_arr=array();
   $main_draw_arr=array();
   $lc_draw_arr=array();
   $type_arr=array();
   $car_insize_arr=array();
   $detail_arr=array();
   $sum1=array();
   $sum2=array();
   $sum3=array();
   $sum4=array();
   $sum5=array();
   
   $sum=array();
   
 try{   
   // $sql="select * from mirae8440.ceiling"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


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
			  $widehap=$row["widehap"];
			  $normalhap=$row["normalhap"];
			  $smallhap=$row["smallhap"];
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];  	   
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $hpi=$row["hpi"];	   
			  $delicompany=$row["delicompany"];	   
			  $delipay=$row["delipay"];	   
				  
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
		      $lclbending_date=trans_date($lclbending_date);
		      $lclwelding_date=trans_date($lclwelding_date);
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
	   
		   $workday_arr[$counter] = $deadline;
		   $testday_arr[$counter] = $testday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
		   $worker_arr[$counter]=$worker;
		   $secondord_arr[$counter]=$secondord;
		   $type_arr[$counter]=$type;
		   $car_insize_arr[$counter]=$car_insize;
		   
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
		   
		        $main_draw_arr[$counter]="";			  
			  if(substr($main_draw,0,2)=="20")  $main_draw_arr[$counter]= "OK";		    
			     elseif($bon_su<1) $main_draw_arr[$counter]= "X";		    
   
   		        $lc_draw_arr[$counter]="";			  
			  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr[$counter]= "OK";	   
			     elseif($lc_su<1) $lc_draw_arr[$counter]= "X";		    
   	
			   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {   
    print "오류: ".$Exception->getMessage();    
}  		 
		 
			?>
		 
<body>

 <div id="wrap">
 
 <h1> &nbsp; 본천장/조명천장 납품예정 리스트 </h1>
  <br>
	 <div id="spreadsheet" >
  
  </div>
     <div class="clear"></div> 		 

	 </div>

<script>

$(function () {
          //  $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
          //  $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
			
});
 

 var changed = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
}

var beforeChange = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
}

var insertedRow = function(instance) {
}

var insertedColumn = function(instance) {  
}

var deletedRow = function(instance) {
}

var deletedColumn = function(instance) {
}

var sort = function(instance, cellNum, order) {
    var order = (order) ? 'desc' : 'asc';
}

var resizeColumn = function(instance, cell, width) {
}

var resizeRow = function(instance, cell, height) {
}

var selectionActive = function(instance, x1, y1, x2, y2, origin) {
    var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
    var cellName2 = jexcel.getColumnNameFromId([x2, y2]);

}

var loaded = function(instance) {
}

var moveRow = function(instance, from, to) {
}

var moveColumn = function(instance, from, to) {
}

var blur = function(instance) {
}

var focus = function(instance) {
}

var data = [    [''],
   [''],
 [''],
];

var table1 = jexcel(document.getElementById('spreadsheet'), {
    data:data,
   // csv:'http://8440.co.kr/test.csv',
  //	csvHeaders:false,
    tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
     tableHeight: '3000px' ,	
    tableWidth: '1300px' ,	
    columns: [
        { title: '납품예정일', type: 'text', width:'80', height:'50' },
        { title: '본설계', type: 'text', width:'50' },
        { title: 'L/C설계', type: 'text', width:'50' },
        { title: '현장명', type: 'text', width:'350' },
        { title: '발주처', type: 'text', width:'120' },
        { title: '타입', type: 'text', width:'70' },
        { title: 'Car Insize', type: 'text', width:'100' },
        { title: '납품내역', type: 'text', width:'300' },
    ],
    onchange: changed,
    onbeforechange: beforeChange,
    oninsertrow: insertedRow,
    oninsertcolumn: insertedColumn,
    ondeleterow: deletedRow,
    ondeletecolumn: deletedColumn,
    onselection: selectionActive,
    onsort: sort,
    onresizerow: resizeRow,
    onresizecolumn: resizeColumn,
    onmoverow: moveRow,
    onmovecolumn: moveColumn,
    onload: loaded,
    onblur: blur,
    onfocus: focus,
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
   
  
<script>

function load_data(){   
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($worker_arr);?> ;
 var arr4 = <?php echo json_encode($material_arr);?> ;  
 var arr5 = <?php echo json_encode($sum_arr);?> ;
 
 var arr7 = <?php echo json_encode($main_draw_arr);?> ;
 var arr8 = <?php echo json_encode($lc_draw_arr);?> ;
 var arr9 = <?php echo json_encode($secondord_arr);?> ;
 var arr10 = <?php echo json_encode($type_arr);?> ;
 var arr11 = <?php echo json_encode($car_insize_arr);?> ;
 var arr12 = <?php echo json_encode($detail_arr);?>;
 
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
 
 for(i=0;i<rowNum;i++) {		
 
			 if(arr1[i]!=past)
			   {
				   if(hap1_sum>0) tmp = tmp +  hap1_sum + "(set), " ;
				   if(hap2_sum>0) tmp = tmp +  " 본청장 " + hap2_sum + "," ;
				   if(hap3_sum>0) tmp = tmp +  " L/C  " + hap3_sum + "," ;
				   if(hap4_sum>0) tmp = tmp +  " 기타  " + hap4_sum + "," ;
				   if(hap5_sum>0) tmp = tmp +  " Air " + hap5_sum ;
				   				   
                  			 table1.insertRow();
                             table1.setRowData(j,['','','','','','','',tmp]);	    							 
							 j++;							 
							 table1.insertRow();
							 j++;
							 hap1_sum=0;
							 hap2_sum=0;
							 hap3_sum=0;
							 hap4_sum=0;
							 hap5_sum=0;
							 tmp="";
			   } 
 
             table1.setRowData(j,[arr1[i],arr7[i],arr8[i],arr2[i],arr9[i],arr10[i],arr11[i],arr12[i]]);	    
			 hap1_sum = hap1_sum + hap1[i];
			 hap2_sum = hap2_sum + hap2[i];
			 hap3_sum = hap3_sum + hap3[i];
			 hap4_sum = hap4_sum + hap4[i];
			 hap5_sum = hap5_sum + hap5[i];
			 
			 // total_sum = total_sum + Number(uncomma(arr6[i]));
			 table1.insertRow();				   
				 
			 past=arr1[i];
			 j++;
			 
   }
   
   // 마지막칸에 소계를 찍어주는 부분입니다.
   
			 tmp= hap1_sum + "(set), "  +  " 본청장 " + hap2_sum + ","  +  " L/C  " + hap3_sum  +  " 기타  " + hap4_sum  +  " Air " + hap5_sum ;      
			 table1.insertRow();
		     table1.setRowData(j,['','','','','','','',tmp]);   							   
      
}

  </script>
  
  </body> 

  </html>
  
<script>
setTimeout(function() {
 //  this_month();  // 금월  
  load_data();
}, 800);

</script>  