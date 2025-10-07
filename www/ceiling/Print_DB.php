
 <?php
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
   
 // 기간을 정하는 구간
 
$todate=date("Y-m-d");   // 현재일자 변수지정   

$common=" where  (date(endworkday)>=date(now()))  order by endworkday ";  // 출고예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.work " . $common; 							

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$counter=1;
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
 <title> 출고예정리스트 </title> 
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
   $material_arr=array();
   $sum_arr=array();
   $draw_arr=array();
   
   $sum1=0;
   $sum2=0;
   $sum3=0;

 try{  
 
   // $sql="select * from mirae8440.work"; 		 
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
					
	       $sum_material=$material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6; 
	   
		   $workday_arr[$counter] = $endworkday;
		   $testday_arr[$counter] = $testday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
		   $worker_arr[$counter]=$worker;
		   
		        $draw_arr[$counter]="";			  
			  if(substr($row["drawday"],0,2)=="20")  $draw_arr[$counter]= "OK";		    
   
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
	   
	 } 	 
   } catch (PDOException $Exception) {   
    print "오류: ".$Exception->getMessage();    
}  
		 
$jamb_total = "막판:" . $sum1 . ", " . "막판(無):" . $sum2 . ", " . "쪽쟘:" . $sum3 ;
		 
		 
			?>
		 
<body >

 <div id="wrap">
   
 <h1> &nbsp; 제품 출고일정리스트 </h1>
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
     tableHeight: '700px' ,	
    tableWidth: '1250px' ,	
    columns: [
        { title: '번호', type: 'text', width:'60' },
        { title: '검사일', type: 'text', width:'150' },
        { title: '출고예정일', type: 'text', width:'150' },
        { title: '설계', type: 'text', width:'60' },
        { title: '현장명', type: 'text', width:'200' },
        { title: '시공팀', type: 'text', width:'100' },
        { title: '재질', type: 'text', width:'300' },
        { title: '출고내역', type: 'text', width:'200' },		
        { title: '비고', type: 'text', width:'100' },		
       // { type: 'calendar', width:'50' },
	    // tableWidth: '1000px',		
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
      
   </script> 
 


   <div class="clear"></div>	
   
   </div> 	   
  </div> <!-- end of wrap -->
  
<script>

function load_data(){   

 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($worker_arr);?> ;
 var arr4 = <?php echo json_encode($material_arr);?> ;  
 var arr5 = <?php echo json_encode($sum_arr);?> ;
 var arr6 = <?php echo json_encode($testday_arr);?> ;
 var arr7 = <?php echo json_encode($draw_arr);?> ;
 var total_sum=0;
 
  
 var rowNum = "<? echo $counter; ?>" ; 
 var jamb_total = "<? echo $jamb_total; ?>"; 
 
  table1.setRowData(0,["번호","검사일","출고예정일","설계","현장명","시공팀","재질","쟘 내역","비 고"]);	    
 for(i=0;i<rowNum;i++) {		
             table1.setRowData(i+1,[i+1,arr6[i],arr1[i],arr7[i],arr2[i],arr3[i],arr4[i],arr5[i]]);	    
			 // total_sum = total_sum + Number(uncomma(arr6[i]));
			 table1.insertRow();
   }
  // table1.setRowData(rowNum,['','','',jamb_total,'배송비 합계',comma(total_sum)]);	   
  // alert(jamb_total);	
}

  </script>
  


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

  </html>
  
<script>
setTimeout(function() {
 //  this_month();  // 금월  
  load_data();
}, 500);

</script>  