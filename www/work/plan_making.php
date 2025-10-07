 <?php

session_start();  
	
ini_set('display_errors','1');  // 화면에 warning 없애기	0, 1은 나오기

$today = date("Y-m-d");

$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"]; 

$WebSite = "https://8440.co.kr/";	


 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
		  header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    
include getDocumentRoot() . '/load_header.php';
   
 ?>
 
 
 <title> Jamb 생산일정(생산완료 제외) </title> 
 </head>
 

 <?php 
 
  // 기간을 정하는 구간
 
$todate=date("Y-m-d");   // 현재일자 변수지정   

$common=" where  (date(endworkday)>=date(now())) and (deadline='') order by endworkday asc, work_order asc ";  // 생산예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.work " . $common; 							

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$counter=1;
 
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
   $designer_arr=array();
   $work_order_arr=array();
   $sum_arr=array();
   $draw_arr=array();
   $jamb1=array();
   $jamb2=array();
   $jamb3=array();
   $firstord_arr=array();
   $secondord_arr=array();
   $outsourcing_arr=array();

 try{  
 
   // $sql="select * from mirae8440.work"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
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
									
	  
	  if($checkmat1 == 'checked')
		   $material2 = '(사급)' . $material2;
	  if($checkmat2 == 'checked')
		   $material4 = '(사급)' . $material4;
	  if($checkmat3 == 'checked')
		   $material6 = '(사급)' . $material6;		
		
					
	       $sum_material=$material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6; 
	   
	       // 신규 공사 추가로 이름 변경 [신규] .+ 현장명 형태로 수정
	   
	   
	    if($checkstep==='신규') 
			  $workplacename = "[신규] " . $workplacename;
		   $num_arr[$counter] = $num;
		   $workday_arr[$counter] = $endworkday;
		   $testday_arr[$counter] = $testday;
		   $workplacename_arr[$counter] = $workplacename;
		   $material_arr[$counter] = $sum_material;		   
		   $worker_arr[$counter]=$worker;
		   $hpi_arr[$counter]=$hpi;
		   $attachment_arr[$counter]=$attachment;
		   $work_order_arr[$counter]=$work_order;
		   $firstord_arr[$counter]=$firstord;
		   $secondord_arr[$counter]=$secondord;
		   $outsourcing_arr[$counter]=$outsourcing;
		   
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

 <div class="container-fluid">
 <div class="d-flex mt-2 mb-2">
 
<h4> &nbsp; &nbsp; (주)미래기업 Jamb 생산일정(생산완료 제외) 		
		 <button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  
		 <button  type="button" class="btn btn-dark btn-sm" onclick="window.close();"> <i class="bi bi-x-lg"></i> 창닫기 </button>

  </h4>
  </div>
	 <div id="grid" >
  
  </div>
     <div class="clear"></div> 		 

	 </div>

<script>

$(document).ready(function(){
	
var num = <?php echo json_encode($num_arr);?>;
var arr1 = <?php echo json_encode($workday_arr);?>;
var arr2 = <?php echo json_encode($workplacename_arr);?>;
var arr3 = <?php echo json_encode($worker_arr);?>;
var arr4 = <?php echo json_encode($material_arr);?>;
var arr5 = <?php echo json_encode($sum_arr);?>;
var arr7 = <?php echo json_encode($draw_arr);?>;
var arr8 = <?php echo json_encode($hpi_arr);?>;
var arr9 = <?php echo json_encode($attachment_arr);?>;
var work_order = <?php echo json_encode($work_order_arr);?>;
var jamb1 = <?php echo json_encode($jamb1);?>;
var jamb2 = <?php echo json_encode($jamb2);?>;
var jamb3 = <?php echo json_encode($jamb3);?>;
var firstord = <?php echo json_encode($firstord_arr);?>;
var secondord = <?php echo json_encode($secondord_arr);?>;
var outsourcing = <?php echo json_encode($outsourcing_arr);?>;
var total_sum = 0;
var jamb1_sum = 0;
var jamb2_sum = 0;
var jamb3_sum = 0;
var sum_tmp = 0;
var out_total_sum = 0;
var out_jamb1_sum = 0;
var out_jamb2_sum = 0;
var out_jamb3_sum = 0;
var out_sum_tmp = 0;
var count = 0; // 전체줄수 카운트

var rowNum = "<? echo $counter; ?>";

console.log(num);

var j = 0;
var past;
past = arr1[0];

const COL_COUNT = 9;

const data = [];
const columns = [];

let sectionData = []; // 각 구간별 데이터를 저장할 배열

for (i = 0; i < rowNum; i++) {
  var row = { name: j };

  if (arr1[i] != past) {
    // 정렬 함수 정의 (work_order 기준 오름차순)
    function compareFunction(a, b) {
      return a["col2"] - b["col2"];
    }

    // 구간별 데이터 정렬
    sectionData.sort(compareFunction);

    // 정렬된 구간 데이터를 전체 데이터 배열에 추가
    data.push(...sectionData);

    // 구간별 데이터 배열 초기화
    sectionData = [];
	
    sum_tmp = jamb1_sum + jamb2_sum + jamb3_sum;
    out_sum_tmp = out_jamb1_sum + out_jamb2_sum + out_jamb3_sum;
	
    var out_tmp =
      "외주 소계 : " +
      out_sum_tmp +
      "(set), " +
      (out_jamb1_sum > 0 ? "막판: "    + out_jamb1_sum + ", " : "") +
      (out_jamb2_sum > 0 ? "막판(무): " + out_jamb2_sum + ", " : "") +
      (out_jamb3_sum > 0 ? "쪽쟘: "    + out_jamb3_sum : "");
	
    var tmp =
      "소계 : " +
      sum_tmp +
      "(set), " +
      (jamb1_sum > 0 ? "막판: " + jamb1_sum + ", " : "") +
      (jamb2_sum > 0 ? "막판(무): " + jamb2_sum + ", " : "") +
      (jamb3_sum > 0 ? "쪽쟘: " + jamb3_sum : "");

  if(out_sum_tmp < 1)
	    out_tmp = '';
    
    for (let k = 0; k < COL_COUNT; k++) {
      row[`col1`] = "";
      row[`col2`] = "";
      row[`col3`] = "";
	  row[`col4`] = out_tmp;	  
      row[`col5`] = "";
      row[`col6`] = tmp;
      row[`col8`] = "";
      row[`col9`] = "";
      row[`col10`] = "";
      row[`col11`] = "";
      row[`col21`] = "";
    }
    data.push(row);
    count++;
    j++;
    row = { name: j };
    for (let k = 0; k < COL_COUNT; k++) {
      row[`col1`] = "";
      row[`col2`] = "";
      row[`col3`] = "";
      row[`col4`] = "";
      row[`col5`] = "";
      row[`col6`] = "";
      row[`col8`] = "";
      row[`col9`] = "";
      row[`col10`] = "";
      row[`col11`] = "";
      row[`col21`] = "";
    }
    data.push(row);
    count++;
    j++;
    jamb1_sum = 0;
    jamb2_sum = 0;
    jamb3_sum = 0;
    sum_tmp = 0;
	var out_total_sum = 0;
	var out_jamb1_sum = 0;
	var out_jamb2_sum = 0;
	var out_jamb3_sum = 0;
	var out_sum_tmp = 0;	
	
  }

  row = { name: j };
  for (let k = 0; k < COL_COUNT; k++) {
    row[`col1`] = arr1[i];
    row[`col2`] = work_order[i];
    row[`col3`] = arr7[i];
    row[`col4`] = arr2[i];
    row[`col5`] = arr3[i];
    row[`col6`] = arr4[i];
    row[`col7`] = arr5[i];
    row[`col8`] = arr9[i];
    row[`col9`] = num[i];
    row[`col10`] = firstord[i];
    row[`col11`] = secondord[i];
    row[`col21`] = outsourcing[i];
  }
  // 생성된 row 데이터를 sectionData에 추가
  sectionData.push(row);

  count++;
if( outsourcing[i]!=='외주')  // 외주가 아닐때의 수량
{
  jamb1_sum += jamb1[i];
  jamb2_sum += jamb2[i];
  jamb3_sum += jamb3[i];
}
else
{
  out_jamb1_sum += jamb1[i];
  out_jamb2_sum += jamb2[i];
  out_jamb3_sum += jamb3[i];
}

  past = arr1[i];
  j++;
}

// 마지막 구간의 데이터 정렬 및 추가
function compareFunction(a, b) {
  return a["col2"] - b["col2"];
}
sectionData.sort(compareFunction);
data.push(...sectionData);

    sum_tmp = jamb1_sum + jamb2_sum + jamb3_sum;
    out_sum_tmp = out_jamb1_sum + out_jamb2_sum + out_jamb3_sum;
	
    var out_tmp =
      "외주가공 : " +
      out_sum_tmp +
      "(set), " +
      (out_jamb1_sum > 0 ? "막판: "    + out_jamb1_sum + ", " : "") +
      (out_jamb2_sum > 0 ? "막판(무): " + out_jamb2_sum + ", " : "") +
      (out_jamb3_sum > 0 ? "쪽쟘: "    + out_jamb3_sum : "");

var tmp =
	  "소계: " +
	  sum_tmp +
	  "(set), 막판: " +
	  jamb1_sum +
	  ", 막판(무): " +
	  jamb2_sum +
	  ", 쪽쟘: " +
	  jamb3_sum;
row = { name: j };

  if(out_sum_tmp < 1)
	    out_tmp = '';

for (let k = 0; k < COL_COUNT; k++) {
  row[`col1`] = "";
  row[`col2`] = "";
  row[`col3`] = "";
  row[`col4`] = out_tmp;
  row[`col5`] = "";
  row[`col6`] = tmp;
  row[`col7`] = "";
  row[`col8`] = "";
  row[`col9`] = "";
  row[`col10`] = "";
  row[`col11`] = "";
  row[`col21`] = "";
}
data.push(row);
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
	  bodyHeight: 750,					  					
	  columns: [ 				   
		{
		  header: '생산예정일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:100,	 		
		  align: 'center'
		},					
		{
		  header: '외주',
		  name: 'col21',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:50,	 		
		  align: 'center'
		},					
		{
		  header: '작업순서',
		  name: 'col2',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:90,	 		
		  align: 'center'
		},					
		{
		  header: '설계',
		  name: 'col3',
		  width:60,	 		
		  align: 'center'
		},
		{
		  header: '원청',
		  name: 'col10',
		  width:60,	
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col11',
		  width:60,	
		  align: 'center'
		},
		{
		  header: '현장명',
		  name: 'col4',
		  width:400,	
		  align: 'center'
		},
		{
		  header: '시공팀',
		  name: 'col5',
		  width:60,	
		  align: 'center'
		},
		{
		  header: '재질',
		  name: 'col6',
		  width:350,	
		  align: 'center'
		},
		{
		  header: '출고내역',
		  name: 'col7',
		  width:200,	
		  align: 'center'
		},
		{
		  header: '부속자재(브라켓,마구리 등)',
		  name: 'col8',
		  width:200,		  
		  align: 'center'
		},
		{
		  header: 'numarr',
		  name: 'col9',
		  width:100,		  
		  align: 'center'
		},
		{
		  header: '생산완료',
		  name: 'col20',
		  width:100,		  
		  align: 'center'
		}			
	  ],
	columnOptions: {
			resizable: true
		  },
	  rowHeaders: ['rowNum'],
	  
	});		
	
	grid.hideColumn('col9');
	

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

	
grid.on('click', (e) => {
	  if (e.columnName === 'col1' && typeof e.rowKey !== 'undefined' ) { // 생산예정일 셀 클릭 확인
		console.log(e.rowKey);
		var link = 'http://8440.co.kr/work/updateday.php?num=' + grid.getValue(e.rowKey, 'col9');
		// 원하는 동작을 수행하는 코드 작성
		var leftPosition = window.screen.width * -1 + 250;  // 첫 번째 모니터의 너비
		window.open(link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=' + leftPosition + ',width=300,height=250');
	  }
	if (e.columnName === 'col2'  && typeof e.rowKey !== 'undefined' ) {  
		console.log(e.rowKey);
		if(grid.getValue(e.rowKey, 'col1') !=='' ) {
		var link = 'http://8440.co.kr/work/updateorder.php?num=' + grid.getValue(e.rowKey, 'col9');
		
		var leftPosition = window.screen.width * -1 + 300;  
		var popupWindow = window.open(link, '_blank', 'toolbar=yes,scrollbars=no,resizable=no,top=150,left=' + leftPosition + ',width=300,height=250');
		
		var focusInterval = setInterval(function() {
			// 팝업 창이 아직 열려 있으면 포커스를 줌
			if (!popupWindow.closed) {
				popupWindow.focus();
			} else {
				clearInterval(focusInterval); // 팝업 창이 닫혔으면 setInterval을 해제
			}
		}, 50);
	 }
	}

  if (e.columnName === 'col20') { // 생산예정일 셀 클릭 확인
    var user_name ='<?php echo $user_name; ?>';
    if(user_name=='이미래') {
    // 원하는 동작을 수행하는 코드 작성
	ajaxRequest = null;
		// DATA 삭제버튼 클릭시
			Swal.fire({ 
				   title: '생산완료일자 입력', 
				   text: " 현재일로 생산일자를 지정하시겠습니까?", 
				   icon: 'info', 
				   showCancelButton: true, 
				   confirmButtonColor: '#3085d6', 
				   cancelButtonColor: '#d33', 
				   confirmButtonText: '저장', 
				   cancelButtonText: '취소' })
				   .then((result) => { if (result.isConfirmed) { 						
					
					  if (ajaxRequest !== null) {
							ajaxRequest.abort();
						}

					 // ajax 요청 생성
					 ajaxRequest = $.ajax({
								url: "../work/save_deadline.php?num=" + grid.getValue(e.rowKey, 'col9') ,
								type: "post",		
								data: '',								
								success : function( data ){			
												console.log( data);
												location.reload();													
									},
									error : function( jqxhr , status , error ){
										console.log( jqxhr , status , error );
								} 			      		
							   });												
				   } });		
	
	
    // var leftPosition = window.screen.width * -1 + 250;  // 첫 번째 모니터의 너비
    // window.open(link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=' + leftPosition + ',width=300,height=400');
     }
  }
  
  
});

grid.on('dblclick', (e) => {
			
			
    var link = 'http://8440.co.kr/work/view.php?menu=no&num=' + grid.getValue(e.rowKey,"col9") ;
   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
	
   //  window.location.replace(link);     // 이전 페이지로 못돌아감
   //  window.open(link);  	
   if( grid.getValue(e.rowKey,"col9") !=='')
       window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=100,width=1900,height=900");
	
   console.log(e.rowKey);
});		
 
$("#downloadcsvBtn").click(function(){  Do_gridexport();   });	          // CSV파일 클릭	

$("#refresh").click(function(){  location.reload();   });	          // refresh

//////////////////// saveCSV	
Do_gridexport = function () { 
	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '생산예정일 ,' ;
			   row += '설계 ,' ;
			   row += '현장명 ,' ;
			   row += '시공팀 ,' ;
			   row += '재질 ,' ;
			   row += '출고내역 ,' ;
			   row += '부속자재(브라켓 마구리 등) ' ;

			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			const COLNUM = 7;   
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
			link.setAttribute("download", "miraeCSV_exceptDone.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
		
	
	
	
});

  </script>
  
  </body> 

  </html>
  
