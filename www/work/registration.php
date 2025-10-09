<?php
require_once __DIR__ . '/../bootstrap.php';

$level = $_SESSION["level"] ?? null;
 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="<?= asset('css/common.css') ?>">
 <link rel="stylesheet" type="text/css" href="<?= asset('css/steel.css') ?>"> 
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

 <title> 우성스틸 발주서(OTIS) 엑셀 양식 일괄등록 </title> 
 </head>

 <?php 
 
 // conv_num() 함수는 common/functions.php에 정의되어 있음

 if(isset($_REQUEST["recordDate"])) 
	 $recordDate=$_REQUEST["recordDate"];
   else
     $recordDate=date("Y-m-d");
 
// 변수 초기화
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '1';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;
$cursort = $_REQUEST["cursort"] ?? '';
$sortof = $_REQUEST["sortof"] ?? '';
$stable = $_REQUEST["stable"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
  
$sum = array();
 
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
					 $sql="select * from mirae8440.work where (workday between date('$fromdate') and date('$Transtodate') ) and (filename1 is null  or filename2 is null or doneday is null ) and (workplacename Not like '%판매%' ) and (workplacename Not like '%불량%' ) and (workplacename Not like '%분실%' ) and (workplacename Not like '%누락%' )  and (workplacename Not like '%추가%' ) " . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( (workday between date('$fromdate') and date('$Transtodate') ) and (filename1 is null  or filename2 is null or doneday is null )  and (workplacename Not like '%판매%' ) and (workplacename Not like '%불량%' ) and (workplacename Not like '%분실%' ) and (workplacename Not like '%누락%' )  and (workplacename Not like '%추가%' ) )" . $orderby;				  		  		   
			     }    
	  
// bootstrap.php에서 이미 DB 연결됨	  		  
 
   $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $firstord_arr=array();
   $secondord_arr=array();
   $worker_arr=array();

   
   $wide_arr=array();
   $normal_arr=array();
   $narrow_arr=array();
   
   $beforepic_arr=array();
   $afterpic_arr=array();
   $etc_arr=array();

     
   $num_arr=array();  // 일괄처리를 위한 번호 저장


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
			  
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  
			  $filename1=$row["filename1"];
			  $filename2=$row["filename2"];
			  
			  if($filename1!=Null)
			       $filename1='등록';
					  
			  if($filename2!=Null)
			       $filename2='등록';
			  
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
	   
		   $workday_arr[$counter]=$workday;
		   $doneday_arr[$counter]=$doneday;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;
		   $secondord_arr[$counter]=$secondord;   
		   $firstord_arr[$counter]=$firstord;   
		   $worker_arr[$counter]=$worker;   
		   $beforepic_arr[$counter]=$filename1;   
		   $afterpic_arr[$counter]=$filename2;   
		   $num_arr[$counter]=$num;   
		   
		   // 판매'란 단어 있으면 실측비 제외		   
		   $findstr = '판매';
		   $pos = stripos($workplacename, $findstr);			   
						   	   
		   $wide_arr[$counter] = 0;

		   $normal_arr[$counter] = 0;

		   $narrow_arr[$counter] = 0;

   				 $workitem="";
				 if($widejamb!="")   {
						$wide_arr[$counter] = (int)$widejamb;
													   
								   
									}
				 if($normaljamb!="")   {
						$normal_arr[$counter] = (int)$normaljamb;				
							 
							   					
						}
				 if($smalljamb!="") {
						$narrow_arr[$counter] = (int)$smalljamb;	
						}		   	    
		        
			   $counter++;	
         }
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

?>		 
<body >

<div id="wrap">

<div class="card-header">    
 <h5> &nbsp; 우성스틸 발주서 엑셀 양식 일괄등록	 &nbsp; &nbsp; 		
 <button  type="button" class="btn btn-secondary" id="savegridBtn"> 글쓰기 일괄등록 실행 </button>	 &nbsp; &nbsp; &nbsp; 
 <button  type="button" class="btn btn-outline-secondary" onclick="self.close();"  > 창닫기 </button>	 &nbsp;
 
 </h5>
</div> 
  
   <div id="content" style="width:1850px;">			 
   <form name="regform" id="regform"  method="post" >  
   <div id="list_search" style="width:1800px;">   
		
    <div class="input-group p-2 mb-2">
		<span style="margin-left:20px;font-size:20px;color:blue;"> ※ 해당셀을 긁어서 복사한 후 발주서 생성하는 방식입니다. </span>
       </div>
	 <div id="grid" style="width:1870px;">  
  </div>     
  
  
  
   <input id="col1" name="col1[]" type=hidden >
   <input id="col2" name="col2[]" type=hidden >
   <input id="col3" name="col3[]" type=hidden >
   <input id="col4" name="col4[]" type=hidden >
   <input id="col5" name="col5[]" type=hidden >
   <input id="col6" name="col6[]" type=hidden >
   <input id="col7" name="col7[]" type=hidden >
   <input id="col8" name="col8[]" type=hidden >
   <input id="col9" name="col9[]" type=hidden >
   <input id="col10" name="col10[]" type=hidden >
   <input id="col11" name="col11[]" type=hidden >
   <input id="col12" name="col12[]" type=hidden >
   <input id="col13" name="col13[]" type=hidden >
   <input id="col14" name="col14[]" type=hidden >
   <input id="col15" name="col15[]" type=hidden >
   <input id="col16" name="col16[]" type=hidden >
   <input id="col17" name="col17[]" type=hidden >
   <input id="col18" name="col18[]" type=hidden >
   <input id="col19" name="col19[]" type=hidden >

   
	 </form>
	 </div>   
   </div> 	   
  </div> <!-- end of wrap -->
  
   
<script>

$(document).ready(function(){
	
$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
 
 var total_sum=0; 
 var count=0;  // 전체줄수 카운트 
  
 var rowNum = <?php echo json_encode($counter); ?> ; 
 
 const data = [];
 const columns = [];	
 const COL_COUNT = 19;

 for(i=0;i<20;i++) {			 
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
                row[`col10`] = '' ;								 											 						
				row[`col11`] = '' ;						 						
				row[`col12`] = '' ;						 											 						
				row[`col13`] = '' ;						 											 						
				row[`col14`] = '' ;						 											 											 						
				row[`col15`] = '' ;						 											 											 						
				row[`col16`] = '' ;						 											 											 						
				row[`col17`] = '' ;						 						
                row[`col18`] = '' ;						 										
                row[`col19`] = '' ;						 										
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
		  header: '발주처',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:70,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 80
			}			
		  },	 		
		  align: 'center'
		},			
		{
		  header: '제번',
		  name: 'col2',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 80
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '영원사원',
		  name: 'col3',
		  width: 150,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: 'SV',
		  name: 'col4',
		  width:170,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '현장소장',
		  name: 'col5',
		  width:210,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: 'IND타입',
		  name: 'col6',
		  width:110,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '현장명',
		  name: 'col7',
		  width:200,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '현장주소',
		  name: 'col8',
		  width:300,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '고무쫄대',
		  name: 'col9',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: 'Jamb타입(3xx)',
		  name: 'col10',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: 'Jamb타입(101)',
		  name: 'col11',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '재질',
		  name: 'col12',
		  width:250,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '총수량(3xx)',
		  name: 'col13',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '총수량(101)',
		  name: 'col14',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '검사수량(3xx)',
		  name: 'col15',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '검사수량(101)',
		  name: 'col16',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '검사호기',
		  name: 'col17',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '검사일',
		  name: 'col18',
		  width:100,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '비고',
		  name: 'col19',
		  width:150,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		}
	  ],
	columnOptions: {
			resizable: true
		  },
// rowHeaders: ['rowNum','checkbox'],   // checkbox 형성
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


	function savegrid() {		
								let col1    =  new Array();  
								let col2    =  new Array();  
								let col3    =  new Array();  
								let col4    =  new Array();  
								let col5    =  new Array();  
								let col6    =  new Array();  
								let col7    =  new Array();  
								let col8    =  new Array();  
								let col9    =  new Array();  
								let col10   =  new Array();  
								let col11   =  new Array();  
								let col12   =  new Array();  
								let col13   =  new Array();  
								let col14   =  new Array();  
								let col15   =  new Array();  
								let col16   =  new Array();  
								let col17   =  new Array();  
								let col18   =  new Array();  
								let col19   =  new Array();  
								
					        // console.log(grid.getRowCount());	//삭제시 숫자가 정상적으로 줄어든다.
						     const MAXcount=grid.getRowCount() ; 
							 let pushcount=0;
							 for(i=0;i<MAXcount;i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.    
							    if( grid.getValue(i, 'col1')!= null ) {				 								   
								    col1.push(swapcommatopipe(grid.getValue(i, 'col1')));																 
								    col2.push(swapcommatopipe(grid.getValue(i, 'col2')));																 
								    col3.push(swapcommatopipe(grid.getValue(i, 'col3')));																 
								    col4.push(swapcommatopipe(grid.getValue(i, 'col4')));																 
								    col5.push(swapcommatopipe(grid.getValue(i, 'col5')));																 
								    col6.push(swapcommatopipe(grid.getValue(i, 'col6')));																 
								    col7.push(swapcommatopipe(grid.getValue(i, 'col7')));																 
								    col8.push(swapcommatopipe(grid.getValue(i, 'col8')));																 
								    col9.push(swapcommatopipe(grid.getValue(i, 'col9')));																 
								    col10.push(swapcommatopipe(grid.getValue(i, 'col10')));																 
								    col11.push(swapcommatopipe(grid.getValue(i, 'col11')));																 
								    col12.push(swapcommatopipe(grid.getValue(i, 'col12')));																 
								    col13.push(swapcommatopipe(grid.getValue(i, 'col13')));																 
								    col14.push(swapcommatopipe(grid.getValue(i, 'col14')));																 
								    col15.push(swapcommatopipe(grid.getValue(i, 'col15')));																	 
								    col16.push(swapcommatopipe(grid.getValue(i, 'col16')));																	 
								    col17.push(swapcommatopipe(grid.getValue(i, 'col17')));																	 
								    col18.push(swapcommatopipe(grid.getValue(i, 'col18')));																	 
								    col19.push(swapcommatopipe(grid.getValue(i, 'col19')));																	 
								   										
									}								   									
								 }	
								$('#col1').val(col1);					 
								$('#col2').val(col2);					 
								$('#col3').val(col3);					 
								$('#col4').val(col4);					 
								$('#col5').val(col5);					 
								$('#col6').val(col6);					 
								$('#col7').val(col7);					 
								$('#col8').val(col8);					 
								$('#col9').val(col9);					 
								$('#col10').val(col10);					 
								$('#col11').val(col11);					 
								$('#col12').val(col12);					 
								$('#col13').val(col13);					 
								$('#col14').val(col14);					 
								$('#col15').val(col15);				
								$('#col16').val(col16);				
								$('#col17').val(col17);				
								$('#col18').val(col18);				
								$('#col19').val(col19);			
	

		 $.ajax({
					url: "uploadorder.php",
					type: "post",		
					data: $("#regform").serialize(),
					dataType:"json",
					success : function( data ){
						console.log( data);
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
					} 			      		
				   });

		Swal.fire(
		  '처리되었습니다.',
		  '데이터가 성공적으로 등록되었습니다.',
		  'success'
		)			
	  setTimeout(function() { 
               self.close();
			 window.opener.location.reload();  // 부모창 새로고침
               }, 2000);		
		
			
		   }	


$("#savegridBtn").click(function(){  savegrid();   });	  


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

function swapcommatopipe(strtmp)
{
	let replaced_str = strtmp.replace(/,/g, '|');
	return replaced_str;	   
}



</script>

  </html>

</body>