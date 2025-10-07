
 <?php
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
   
 // 기간을 정하는 구간
 
$todate=date("Y-m-d");   // 현재일자 변수지정   

$common=" where  (date(endworkday)>=date(now()))  order by endworkday ";  // 생산예정일이 현재일보다 클때 조건

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
 
 <title> 쟘(jamb) 생산 전체 리스트 </title> 
 </head>

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
   $num_arr=array();   
   $workday_arr=array();
   $testday_arr=array();
   $workplacename_arr=array();
   $worker_arr=array();
   $material_arr=array();
   $sum_arr=array();
   $draw_arr=array();
   $jamb1=array();
   $jamb2=array();
   $jamb3=array();

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
			  $designer=$row["designer"];	   
			  $attachment=$row["attachment"];	   
	
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
	   
		   $num_arr[$counter] = $num;	   
		   $endworkday_arr[$counter] = $endworkday;
		   $workday_arr[$counter] = $workday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
		   $worker_arr[$counter]=$worker;
		   $attachment_arr[$counter]=$attachment;
		   $hpi_arr[$counter]=$hpi;
		   
		        $draw_arr[$counter]="";			  
			  if(substr($row["drawday"],0,2)=="20") 
			  {
			      $draw_arr[$counter]= "OK";		
					if($designer!='')
					   $draw_arr[$counter] = $designer ;
			  }    
			$jamb1[$counter]=0;
			$jamb2[$counter]=0;
			$jamb3[$counter]=0;   
   				 $workitem="";
				 if($widejamb!="")   {
					    $workitem="막판" . $widejamb . " "; 
						$sum1 += (int)$widejamb;
						$jamb1[$counter]= (int)$widejamb;						
									}
				 if($normaljamb!="")   {
					    $workitem .="막(無)" . $normaljamb . " "; 					
						$sum2 += (int)$normaljamb;						
						$jamb2[$counter]= (int)$normaljamb;						
						}
				 if($smalljamb!="") {
					    $workitem .="쪽쟘" . $smalljamb . " "; 												   
						$sum3 += (int)$smalljamb;								
						$jamb3[$counter]= (int)$smalljamb;
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
 
 <h3> &nbsp; 쟘(jamb) 생산 전체 리스트 </h3>  
	<div class="card-header">    
				<button  type="button" class="btn btn-secondary" id="downloadcsvBtn"> CSV 엑셀 다운로드 </button>	 &nbsp;			
			    <button  type="button" class="btn btn-secondary" id="refresh"> 새로고침 </button>	 &nbsp;									 				
	</div>  
	 <div id="grid" >
  
  </div>
     <div class="clear"></div> 		 

	 </div>

<script>

$(document).ready(function(){
	
var num = <?php echo json_encode($num_arr);?> ;
var numcopy = new Array(); ;
var arr1 = <?php echo json_encode($endworkday_arr);?> ;
var arr2 = <?php echo json_encode($workday_arr);?> ;
var arr3 = <?php echo json_encode($draw_arr);?> ;
var arr4 = <?php echo json_encode($workplacename_arr);?> ;
var arr5 = <?php echo json_encode($worker_arr);?> ;
var arr6 = <?php echo json_encode($material_arr);?> ;  
var arr7 = <?php echo json_encode($sum_arr);?> ; 
var arr8 = <?php echo json_encode($attachment_arr);?> ;
 var jamb1 = <?php echo json_encode($jamb1);?> ;
 var jamb2 = <?php echo json_encode($jamb2);?> ;
 var jamb3 = <?php echo json_encode($jamb3);?> ;
 var total_sum=0;
 var jamb1_sum=0;
 var jamb2_sum=0;
 var jamb3_sum=0;
 var sum_tmp=0;
 var count=0;  // 전체줄수 카운트 
  
 var rowNum = "<? echo $counter; ?>" ; 

 var j=0;
 var past;
 past=arr1[0];

 const COL_COUNT = 8;

 const data = [];
 const columns = [];	
 
 for(i=0;i<rowNum;i++) {		
 row = { name: j };
 
			 if(arr1[i]!=past)
			   {
				   sum_tmp = jamb1_sum + jamb2_sum + jamb3_sum ;
				   tmp="소계 : " + sum_tmp +"(set),      ";
				   if(jamb1_sum>0) tmp = tmp +  "막판 : " + jamb1_sum + "," ;
				   if(jamb2_sum>0) tmp = tmp +  " 막판(무) : " + jamb2_sum + "," ;
				   if(jamb3_sum>0) tmp = tmp +  " 쪽쟘 : " + jamb3_sum ;			   
							  							 
							 for (let k = 0; k < COL_COUNT; k++ ) {				
								row[`col1`] = '' ;						 						
								row[`col2`] = '' ;					 						
								row[`col3`] = '' ;						 						
								row[`col4`] = '' ;						 						
								row[`col5`] = '' ;						 						
								row[`col6`] = tmp ;						 						
								row[`col7`] = '' ;						 						
								row[`col8`] = '' ;						 						
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
								row[`col7`] = '' ;						 						
								row[`col8`] = '' ;						 						
								    	}
								data.push(row); 
								 numcopy[count] = 0 ;
								 count++;								
							 j++;
							 jamb1_sum=0;
							 jamb2_sum=0;
							 jamb3_sum=0;
							 sum_tmp=0;
			   } 
			   
 	         row = { name: j };						 
			 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr3[i] ;						 						
				row[`col4`] = arr4[i] ;						 						
				row[`col5`] = arr5[i] ;						 						
				row[`col6`] = arr6[i] ;						 						
				row[`col7`] = arr7[i];	
				row[`col8`] = arr8[i];	
						}
				data.push(row);
				 numcopy[count] = num[i] ; 			 
				 count++;				
             
			 jamb1_sum += jamb1[i];
			 jamb2_sum += jamb2[i];
			 jamb3_sum += jamb3[i];
			 
			 past=arr1[i];
			 j++;
			 
   }
   
   // 마지막칸에 소계를 찍어주는 부분입니다.
   sum_tmp = jamb1_sum + jamb2_sum + jamb3_sum ;
   tmp="소계 : " + sum_tmp +"(set),      막판 : " + jamb1_sum + ", 막판(무) : " + jamb2_sum + ", 쪽쟘 : " + jamb3_sum ;      
   row = { name: j };
	 for (let k = 0; k < COL_COUNT; k++ ) {				
		row[`col1`] = '' ;						 						
		row[`col2`] = '' ;					 						
		row[`col3`] = '' ;						 						
		row[`col4`] = '' ;						 						
		row[`col5`] = '' ;						 						
		row[`col6`] = tmp ;						 						
		row[`col7`] = '' ;		
		row[`col8`] = '' ;			
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
		  header: '생산예정일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:120,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		  align: 'center'
		},					   
		{
		  header: '출고일',
		  name: 'col2',
		  sortingType: 'desc',
		  sortable: true,
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		  align: 'center'
		},			
		{
		  header: '설계',
		  name: 'col3',
		  width:60,
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
		  name: 'col4',
		  width:400,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 100
			}
		  },	 		
		  align: 'center'
		},
		{
		  header: '시공팀',
		  name: 'col5',
		  width:70,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		  align: 'center'
		},
		{
		  header: '재질',
		  name: 'col6',
		  width:350,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		  align: 'center'
		},
		{
		  header: '출고내역',
		  name: 'col7',
		  width:200,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		},
		{
		  header: '부속자재(브라켓,마구리 등)',
		  name: 'col8',
		  width:250,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
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
	
		
grid.on('dblclick', (e) => {
	
    var link = 'http://8440.co.kr/work/view.php?num=' + numcopy[e.rowKey] ;
   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
	
   //  window.location.replace(link);     // 이전 페이지로 못돌아감
   //  window.open(link);  	
   if(numcopy[e.rowKey]>0)
       window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=100,width=1600,height=900");
	
   console.log(e.rowKey);
});			
  
$("#refresh").click(function(){  location.reload();   });	          // refresh  
  
$("#downloadcsvBtn").click(function(){  Do_gridexport();   });	          // CSV파일 클릭	
//////////////////// saveCSV	
Do_gridexport = function () { 
	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '출고일, ' ;
			   row += '생산예정일 ,' ;
			   row += '설계 ,' ;
			   row += '현장명 ,' ;
			   row += '시공팀 ,' ;
			   row += '재질 ,' ;
			   row += '출고내역 ,' ;
			   row += '부속자재 ' ;

			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			const COLNUM = 8;   
			for (let i = 0; i <grid.getRowCount(); i++) {
			   let row = "";			  
			   row += (i+1) + ',' ;
			   for(let j=1; j<=COLNUM ; j++) {
				  let tmp = String(grid.getValue(i, 'col'+j));
				  tmp = tmp.replace(/undefined/gi, "") ;
				  tmp = tmp.replace(/#/gi, " ") ;
				  row +=  tmp.replace(/,/gi, "`") + ',' ;
			   }

			   csvContent += row + "\r\n";
			}		 		  
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "쟘 생산예정일.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
	
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
   
   </div> 	   
  </div> <!-- end of wrap -->

  </script>
  
  </body> 

  </html>
  
