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
 <title> 검사일정별 시공소장님 DB </title> 
 </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

 <?php
 

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	   $search=$_REQUEST["search"];
	 else 
		 $search="";
	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
  
  
$attached=" ";
	
$orderby="order by testday desc, measureday desc ";	
	 
$a= " " . $orderby ;    
		  
$sql ="select * from mirae8440.work where workday='' "  . $attached . $a;	   

   $counter=0;
   $secondord_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $sum1_arr=array();
   $sum2_arr=array();
   $sum3_arr=array();
   $sum_arr=array();
   $material_arr=array();
   $hpi_arr=array();
   $fistordman_arr=array();
   $fistordmantel_arr=array();   
   $chargedman_arr=array();
   $chargedmantel_arr=array();   
   $measureday_arr=array();   
   $worker_arr=array();   
   $endworkday_arr=array();   
   $draw_arr=array();   

	 try{  
	 
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
			  $testday=$row["testday"];
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

	   
   $secondord_arr[$counter]=$secondord;
   $workplacename_arr[$counter]=$workplacename;
   $address_arr[$counter]=$address;
   $material_arr[$counter]=$material1 . $material2 . $material3 . $material4 . $material5 . $material6 ;
   $chargedman_arr[$counter]=$chargedman;
   $chargedmantel_arr[$counter]=$chargedmantel;
   $firstordman_arr[$counter]=$firstordman;
   $firstordmantel_arr[$counter]=$firstordmantel;   
   $hpi_arr[$counter]=$hpi;   
   $startday_arr[$counter]=$startday;   
   $testday_arr[$counter]=$testday;   
   $worker_arr[$counter]=$worker;   
   $measureday_arr[$counter]=$measureday;   
   $endworkday_arr[$counter]=$endworkday;   
   
   		        $draw_arr[$counter]="";			  
			  if(substr($row["drawday"],0,2)=="20")  $draw_arr[$counter]= "OK";		  
   
				 if($widejamb!="")
					    $sum1_arr[$counter] += $widejamb;
				 if($normaljamb!="")
					    $sum2_arr[$counter] += $normaljamb;
				 if($smalljamb!="")
					    $sum3_arr[$counter] += $smalljamb;
   
    $sum_arr[$counter] = $sum_arr1[$counter] + $sum_arr2[$counter] + $sum3_arr[$counter];
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
		 
			?>
		 
<body >

 <div id="wrap">
   <div id="content">			 
  


	 <div id="spreadsheet" >
  
  </div>
     <div class="clear"></div> 		 
	 

<script>

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
    tableWidth: '1650px' ,	
    columns: [
        { title: '', type: 'text', width:'90' },   // 검사일
        { title: '', type: 'text', width:'90' },   // 실측일
        { title: '', type: 'text', width:'40' },   // 설계여부
        { title: '', type: 'text', width:'90' },   // 출고예정일
        { title: '', type: 'text', width:'60' },   // 소장
        { title: '', type: 'text', width:'300' },  // 현장명
        { title: '', type: 'text', width:'300' },  // 현장명
        { title: '', type: 'text', width:'40' },  // 막판
        { title: '', type: 'text', width:'40' },  // 멍텅구리	 	
        { title: '', type: 'text', width:'40' },   // 쪽쟘
        { title: '', type: 'text', width:'200' },  // 재질		
        { title: '', type: 'text', width:'100' },   // 담당PM
        { title: '', type: 'text', width:'100' },  // PM전번
        { title: '', type: 'text', width:'100' },   // 현장소장		 
        { title: '', type: 'text', width:'100' },   // 소장전번		 
        { title: '', type: 'text', width:'90' },   // 착공일		

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

  <div id="order2">
	   
	 </div> 
     <div class="clear"></div>
   
   </div> 	   
  </div> <!-- end of wrap -->
  
<script>

function load_data(){   

 var arr1 = <?php echo json_encode($secondord_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($address_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($material_arr);?> ;
 var arr6 = <?php echo json_encode($hpi_arr);?> ;
 var arr7 = <?php echo json_encode($firstordman_arr);?> ;
 var arr8 = <?php echo json_encode($firstordmantel_arr);?> ;
 var arr9 = <?php echo json_encode($chargedman_arr);?> ;
 var arr10 = <?php echo json_encode($chargedmantel_arr);?>;
 var arr11 = <?php echo json_encode($startday_arr);?> ;
 var arr12 = <?php echo json_encode($testday_arr);?> ; 
 var arr13 = <?php echo json_encode($measureday_arr);?> ; 
 var arr14 = <?php echo json_encode($worker_arr);?> ; 
 var arr15 = <?php echo json_encode($sum1_arr);?> ; 
 var arr16 = <?php echo json_encode($sum2_arr);?> ; 
 var arr17 = <?php echo json_encode($sum3_arr);?> ; 
 var arr18 = <?php echo json_encode($endworkday_arr);?> ; 
 var arr19 = <?php echo json_encode($draw_arr);?> ; 
  
 var rowNum = <?php echo $counter; ?>; 
 table1.setRowData(0,["검사일","실측일","설계","출고예정일","시공소장","현장명","현장주소","막","멍","쪽", "재질","담당자PM","담당전번","현장소장","소장전번","착공일"]);	    
 for(i=0;i<rowNum;i++) {		
             table1.setRowData(i+1,[arr12[i],arr13[i],arr19[i],arr18[i],arr14[i],arr2[i],arr3[i],arr15[i],arr16[i],arr17[i],arr5[i],arr7[i],arr8[i],arr9[i],arr10[i],arr11[i]]);	    
			 table1.insertRow();		
   }
}

  </script>
  


  </body>
  
  <script> 

setTimeout(function() {

 load_data();
}, 500);

</script>

  </html>