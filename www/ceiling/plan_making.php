<?php

session_start();  
	
ini_set('display_errors','1');  // 화면에 warning 없애기	0, 1은 나오기

$today = date("Y-m-d");

$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"]; 

?>
   
<?php include getDocumentRoot() . '/load_header.php' ?>
<title> 조명천장 납품일정 </title> 
 
</head>
 

 <?php 
 // 기간을 정하는 구간
 
$todate=date("Y-m-d");   // 현재일자 변수지정   

$common=" where  date(deadline)>=date(now()) order by deadline ";  // 출고예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.ceiling " . $common; 							

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$counter=1;

$sum=array(); 
   
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
   $car_insize_arr=array();
   $detail_arr=array();
   $sum1=array();
   $sum2=array();
   $sum3=array();
   $sum4=array();
   $sum5=array();
   
   $sum=array();
   $memo_arr=array();
   
// todolist 배열 (도장/제작 완료)
$paintlist = array();
$donelist = array();   
$bon_laser = array();   
$bon_bending = array();   
$lc_laser = array();   
$lc_bending = array();   
   
 try{   
   // $sql="select * from mirae8440.ceiling"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
		  include '_rowDB.php';	  			  
			  
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
		      $etcpainting_date=trans_date($etcpainting_date);			
		      $etcassembly_date=trans_date($etcassembly_date);													  	  				  	
					
	       $sum_material=$material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6; 
	   
		   $num_arr[$counter] = $num;	   
		   $workday_arr[$counter] = $deadline;
		   $testday_arr[$counter] = $testday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
		   $worker_arr[$counter]=$worker;
		   $secondord_arr[$counter]=$secondord;
		   $type_arr[$counter]=$type;
		   $car_insize_arr[$counter]=$car_insize;
		   $memo_arr[$counter]=trim($memo . $memo2);
		   
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
		   
		    $lc_type = "";
		  if($type=='011'||$type=='012'|| $type=='013D'||$type=='025'||$type=='017'||$type=='014'||$type=='037')
				 $lc_type = "외주";	
		   
		        $main_draw_arr[$counter]="";			  
			  if(substr($main_draw,0,2)=="20")  
			  {
				  $main_draw_arr[$counter]= "OK";	
                  
				  if( $eunsung_laser_date != '' ) // 본천장 레이져완료
					  $bon_laser[$counter] = 'OK';
				   if( $mainbending_date != '' ) // 본천장 절곡
					   $bon_bending[$counter] = 'OK';
					  
			  }
			     elseif($bon_su<1) {
					 $main_draw_arr[$counter]= "X";		    
					  $bon_laser[$counter] = 'X';	
					  $bon_bending[$counter] = 'X';	
				 }
   
   		        $lc_draw_arr[$counter]="";			  
			  if(substr($lc_draw,0,2)=="20") {
				  $lc_draw_arr[$counter]= "OK";	   				  
				  if( $lclaser_date != '' ) // lc 레이져완료
					  $lc_laser[$counter] = 'OK';
				   if( $lcbending_date != '' ) // lc 절곡
					   $lc_bending[$counter] = 'OK';
				  
			  }
			     elseif($lc_su<1  ) {
					 $lc_draw_arr[$counter]= "X";		
					  $lc_laser[$counter] = 'X';	
					  $lc_bending[$counter] = 'X';	
				 }

			     elseif($lc_type=="외주" ) {
					 $lc_draw_arr[$counter]= "외주";		
					  $lc_laser[$counter] = '외주';	
					  $lc_bending[$counter] = '외주';	
				 }

			$paintcondition = 1; 
			$donecondition = 1; 
			    // 본천창 도장
			  if ( ( ($main_draw!='' and $main_draw!='0000-00-00') and ($mainpainting_date!='' and $mainpainting_date!='0000-00-00') and (int)$bon_su>0 )  or  ($main_draw_arr== "X") )
				    $paintcondition = 0; 
		    // LC 도장
			  if ( ( ($lc_draw_arr!='' and $lc_draw_arr!='0000-00-00') and ($lcpainting_date!='' and $lcpainting_date!='0000-00-00')  and (int)$lc_su>0 )  or  ($lc_draw_arr== "X") )
				    $paintcondition = 0; 
			// 기타 도장
			  if ( ($etcpainting_date!='' and $etcpainting_date!='0000-00-00')    and ((int)$etc_su > 0) )
				    $paintcondition = 0; 
								
				
		    // 본천장 완료
			  if ( ( ($main_draw!='' and $main_draw!='0000-00-00') and ($mainassembly_date!='' and $mainassembly_date!='0000-00-00')  and (int)$bon_su>0 )  or  ($main_draw_arr== "X") )
				    $donecondition = 0; 
		    // LC 완료
			  if ( ( ($lc_draw_arr!='' and $lc_draw_arr!='0000-00-00') and ($lcassembly_date!='' and $lcassembly_date!='0000-00-00')   and (int)$lc_su>0 )  or  ($lc_draw_arr== "X") )
				    $donecondition = 0; 
			// 기타 조립
			  if ( ($etcassembly_date!='' and $etcassembly_date!='0000-00-00') and ((int)$etc_su > 0) )
				    $donecondition = 0; 				
								
				
				// 도장완료
			  if(!$paintcondition)
			    {
					   $paintlist[$counter] = 'OK';					   
				}
				// 제작완료				
			  if(!$donecondition)
			    {					   
					   $donelist[$counter] = 'OK';
				}
   	
			   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {   
    print "오류: ".$Exception->getMessage();    
}  		 
		 
			?>
		 
<body>

 <div id="wrap">
 
 <h2> &nbsp; 본천장/조명천장 납품예정 List </h2>
	 <div id="grid" >
  
  </div>
     <div class="clear"></div> 		 

	 </div>

<script>

$(document).ready(function(){
  
var num = <?php echo json_encode($num_arr);?> ;
var numcopy = new Array(); 
  
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($main_draw_arr);?> ;
 var arr3 = <?php echo json_encode($lc_draw_arr);?> ;
 var arr4 = <?php echo json_encode($bon_laser);?> ;
 var arr5 = <?php echo json_encode($bon_bending);?> ;
 var arr6 = <?php echo json_encode($lc_laser);?> ;
 var arr7 = <?php echo json_encode($lc_bending);?> ;
 var arr8 = <?php echo json_encode($paintlist);?> ;
 var arr9 = <?php echo json_encode($donelist);?> ;
 
 var arr10= <?php echo json_encode($workplacename_arr);?> ;
 
 var arr11= <?php echo json_encode($secondord_arr);?> ;
 var arr12 = <?php echo json_encode($type_arr);?> ;
 var arr13 = <?php echo json_encode($car_insize_arr);?> ;
 var arr14 = <?php echo json_encode($detail_arr);?>;
 var arr15 = <?php echo json_encode($memo_arr);?>;
 
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
 
 
 const COL_COUNT = 16;
 
 const data = [];
 const columns = [];
 
 for(i=-1;i<rowNum;i++) {		
  row = { name: j };

if(i==-1) { 
	// 타이틀 만들기  
	 for (let k = 0; k < COL_COUNT; k++ ) {				
		row[`col1`] = '납품예정일' ;						 						
		row[`col2`] = '(본_설)' ;					 						
		row[`col3`] = '(LC_설)' ;					 						
		row[`col4`] = '(본_레)' ;					 						
		row[`col5`] = '(본_절)' ;							 						
		row[`col6`] = '(LC_레)' ;					 						
		row[`col7`] = '(LC_절)' ;							 						
		row[`col8`] = '(도장)' ;					 						
		row[`col9`] = '(조립)' ;						 						
		row[`col10`] = '현장명' ;						 						
		row[`col11`] = '발주처' ;						 						
		row[`col12`] = '타입' ;						 						
		row[`col13`] = 'Car inside' ;						 						
		row[`col14`] = '납품내역' ;						 						
		row[`col15`] = '비고' ;						 						
				}  
		 data.push(row); 
			 numcopy[count] = 0 ;
			 count++;				
				
      }
   else			
	{
				 
				 row = { name: j };						 
				 for (let k = 0; k < COL_COUNT; k++ ) {				
					row[`col1`] = arr1[i];		 						
					row[`col2`] = arr2[i];		 						
					row[`col3`] = arr3[i];		 						
					row[`col4`] = arr4[i];		 						
					row[`col5`] = arr5[i];		 						
					row[`col6`] = arr6[i];		 						
					row[`col7`] = arr7[i];		 						
					row[`col8`] = arr8[i];		 						
					row[`col9`] = arr9[i];		 						
					row[`col10`] = arr10[i];		 						
					row[`col11`] = arr11[i];		 						
					row[`col12`] = arr12[i];		 						
					row[`col13`] = arr13[i];		 						
					row[`col14`] = arr14[i];		 						
					row[`col15`] = arr15[i];		 						
							}
					data.push(row);	 
				 numcopy[count] = num[i] ; 			 
				 count++;							
				 hap1_sum = hap1_sum + hap1[i];
				 hap2_sum = hap2_sum + hap2[i];
				 hap3_sum = hap3_sum + hap3[i];
				 hap4_sum = hap4_sum + hap4[i];
				 hap5_sum = hap5_sum + hap5[i];
				 
				 // total_sum = total_sum + Number(uncomma(arr6[i]));

					 
				 past=arr1[i];
		}
				 j++;
			 
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
	  bodyHeight: 800,					  					
	  columns: [ 				   
		{
		  header: ' ',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width: 100,	
		  align: 'center'
		},			
		{
		  header: ' ',
		  name: 'col2',
		  width:50,	
		  align: 'center'
		},		
		{
		  header: ' ',
		  name: 'col3',
		  width:50,	
		  align: 'center'
		},			
		{
		  header: ' ',
		  name: 'col4',
		  width:50,	
		  align: 'center'
		},		
		{
		  header: ' ',
		  name: 'col5',
		  width:50,	
		  align: 'center'
		},		
		{
		  header: ' ',
		  name: 'col6',
		  width:50,	
		  align: 'center'
		},		
		{
		  header: ' ',
		  name: 'col7',
		  width:50,	
		  align: 'center'
		},		
		{
		  header: ' ',
		  name: 'col8',
		  width:50,	
		  align: 'center'
		},
		{
		  header: ' ',
		  name: 'col9',
		  width: 50,	
		  align: 'center'
		},
		{
		  header: ' ',
		  name: 'col10',
		  width:300,	
		  align: 'left'
		},
		{
		  header: ' ',
		  name: 'col11',
		  width:150,	
		  align: 'center'
		},
		{
		  header: ' ',
		  name: 'col12',
		  width: 70,	
		  align: 'center'
		},		{
		  header: ' ',
		  name: 'col13',
		  width:120,		
		  align: 'center'
		},
		{
		  header: ' ',
		  name: 'col14',
		  width:120,		
		  align: 'center'
		}	,
		{
		  header: ' ',
		  name: 'col15',
		  width:300,		
		  align: 'left'
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
				  background: '#ffffff',
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
				  background: '#ffffff'
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
	
    var link = 'http://8440.co.kr/ceiling/view.php?num=' + numcopy[e.rowKey] ;
   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
	
   //  window.location.replace(link);     // 이전 페이지로 못돌아감
   //  window.open(link);  	
   if(numcopy[e.rowKey]>0)
       window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1800,height=900");
	
   console.log(e.rowKey);
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