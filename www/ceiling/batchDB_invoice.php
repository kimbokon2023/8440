<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  
$title_message = '출고증 일괄';
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
 ?>

   <title>  <?=$title_message?> </title>
   
<style>
#showframe {
	width:200px !important;
}
</style>   

</head>

<body>

<?php
 
 
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : ''; 
 
if($fromdate=="")
{
	$fromdate=date("Y-m-d");
}
if($todate=="")
{
	$todate=date("Y-m-d");
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
	$num=$_REQUEST["num"];
  else
   $num="";

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

$orderby=" order by deadline desc ";
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		


// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);  

if($search==""){
      $sql="select * from mirae8440.ceiling where deadline between date('$fromdate') and date('$Transtodate')" . $orderby;  	
}
else
{
  $sql="select * from mirae8440.ceiling where deadline between date('$fromdate') and date('$Transtodate') " ;
  $sql .=" and ((replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )   or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
  $sql .=" or (delicompany like '%$search%' ) or (type like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize like '%$search%' ) or (memo like '%$search%' ) or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  or (searchtag like '%$search%' )   or (boxwrap like '%$search%' )  ) ".  $orderby; 				  
}				  

	  
   $counter=0;
   $num_arr=array();   
   $deadline_arr=array();
   $workday_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $secondord_arr=array();
   $sum_arr=array();
   $delivery_arr=array();
   $content_arr=array();
   $num_arr=array();
   $demand_arr=array();
   $memo_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;

 try{  
   
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	 

			 include '_rowDB.php';
            
			
 $workitem="";
 
 if($workday=='0000-00-00')
	   $workday = '';
				 
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
	   
		   $num_arr[$counter]=$num; 	   
		   array_push($deadline_arr,$deadline);
		   $workday_arr[$counter]=$workday;
		   
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;    
		   $delivery_arr[$counter]=$delivery;    
		   $secondord_arr[$counter]=$secondord;   
		   $num_arr[$counter]=$num;    
		   $demand_arr[$counter]=$demand;    

		   $content_arr[$counter]=$type . " " . $inseung ." 인승 " . $car_insize ;
		   $memo_arr[$counter]=$memo ;
   
		$sum_arr[$counter]=$workitem;
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
$all_sum=$sum1 + $sum2 + $sum3;		 


// 오늘을 담는 변수
$recordDate	=Date("Y-m-d",time());
		 
?>

<form name="board_form" id="board_form"  method="post" action="batchDB_invoice.php?mode=search">  

<input type=hidden id="num_arr" name="num_arr[]" >
<input type=hidden id="recordDate_arr" name="recordDate_arr[]">

<div class="container-fluid">
	<div class="card mt-2 mb-4">  
	<div class="card-body">  	
	<div class="card mt-2 mb-4">  
	<div class="card-body"> 
	<div class="d-flex justify-content-center align-items-center"> 
        <span class="fs-5 text-primary me-3">(천정/LC)</span>
       <h5>  <?=$title_message?>  </h5> &nbsp;&nbsp;&nbsp;&nbsp; 
	    <button  type="button" class="btn btn-outline-secondary btn-sm mx-3" id="refresh"> <i class="bi bi-arrow-clockwise"></i> 새로고침 </button>	 
		<button  type="button" class="btn btn-dark btn-sm mx-3" onclick="window.close();"> <i class="bi bi-x-lg"></i> 닫기 </button>
	   </div>
	   
<div class="row"> 		  
	<div class="d-flex mt-1 justify-content-center align-items-center "> 			
		<!-- 기간부터 검색까지 연결 묶음 start -->
		<div class="card">
		<div class="card-body">
		<div class="d-flex justify-content-center align-items-center mb-2">
		<span id="showdate" class="btn btn-dark btn-sm " > 납기일 기간 </span>	&nbsp; 
		<div id="showframe" class="card">
			<div class="card-header ">
				<div class="d-flex justify-content-center align-items-center">  
					기간 설정
				</div>
			</div> 
			<div class="card-body">
				<div class="d-flex justify-content-center align-items-center">  	
								
				<button type="button" id="premonth" class="btn btn-dark btn-sm me-1   "  onclick='yesterday()' > 전일 </button> 						
				<button type="button" class="btn btn-outline-dark btn-sm me-1   "  onclick='this_today()' > 금일 </button>
				<button type="button" class="btn btn-dark btn-sm me-1   "  onclick='this_tomorrow()' > 익일  </button>				
					
				</div>
			</div>
		</div>			

			   <input type="date" id="fromdate" name="fromdate"  class="form-control " style="width:100px;"  value="<?=$fromdate?>">  &nbsp; ~ &nbsp;  
			   <input type="date" id="todate" name="todate"  class="form-control me-1"  style="width:100px;"    value="<?=$todate?>" >    </span>  
			<div class="inputWrap">
			<input type="text" id="search" name="search" style="width:150px;"   value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control me-1" style="width:200px;" >
				<button class="btnClear"></button>	
			</div>	
				<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"  > <i class="bi bi-search"></i> 검색 </button>										
		</div>	
		<div class="d-flex justify-content-center align-items-center mb-2">		
 		  		<input type="date" id="recordDate" name="recordDate" class="form-control me-1" style="width:120px;"  value="<?=$recordDate?>"  > 	        					
				<button type="button" id="saveDeadlineBtn" class="btn btn-outline-danger btn-sm me-5">선택항목 납기일 변경</button>&nbsp;&nbsp;
				<button type="button" id="saveBtn" class="btn btn-outline-success  btn-sm"> 선택 출고일 입력 </button>  &nbsp;&nbsp;
				<button type="button" id="cancelBtn" class="btn btn-danger btn-sm "> 선택 출고일 취소 </button>  &nbsp;&nbsp;
				<button type="button" id="invoiceBtn" class="btn btn-primary  btn-sm"> <i class="bi bi-printer"></i> 선택 출고증</button>	
				&nbsp;	
		</div>	  
		</div>	  
		</div>		
		</div>		 
	   
      
    
    </div>
    </div>
    </div>
		<div class="d-flex justify-content-center"> 
			<div id="grid" ></div>

     </div> 
   </div> <!--card-body-->
   </div> <!--card -->
   </div> <!--container-->

</form>
  
    
  </body>

</html>  

  
<script>	
	
$(document).ready(function(){
	
 $("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
  
 var num = <?php echo json_encode($num_arr);?> ;
 var numcopy = new Array(); 	
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($address_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($delivery_arr);?> ;
 var arr6 = <?php echo json_encode($content_arr);?> ;
 var arr7 = <?php echo json_encode($secondord_arr);?> ;
 var arr8 = <?php echo json_encode($num_arr);?> ;
 var arr9 = <?php echo json_encode($deadline_arr);?> ;
 var arr10 = <?php echo json_encode($memo_arr);?> ;
 var total_sum=0; 
  
 var rowNum = "<? echo $counter; ?>" ; 
 var jamb_total = "<? echo $jamb_total; ?>"; 
 
 const data = [];
 const columns = [];	
 var count=0;  // 전체줄수 카운트  
 
 for(i=0;i<rowNum;i++) {
			 total_sum = total_sum + Number(uncomma(arr6[i]));
		 row = { name: i };		 
				row[`col1`] = arr1[i] ;	
				row[`col2`] = arr2[i] ;	
				row[`col3`] = arr7[i] ;	
				row[`col4`] = arr3[i] ;	
				row[`col5`] = arr4[i] ;	
				row[`col6`] = arr5[i] ;	
				row[`col7`] = arr6[i] ;	
				row[`col8`] = arr8[i] ;	
				row[`col9`] = arr9[i] ;	
				row[`col10`] = arr10[i] ; 
				data.push(row); 	
				 numcopy[count] = num[i] ; 	
				 count++;					
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
	  bodyHeight: 500,					  					
	  columns: [ 	
		{
		  header: '납기일',
		  name: 'col9',
		  color : 'red',
		  sortingType: 'desc',
		  sortable: true,
		  width:80,	
		  align: 'center'
		},	
		{
		  header: '출고일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:80,	
		  align: 'center'
		},			
		
		{
		  header: '현장명',
		  name: 'col2',
		  width:250,	
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col3',
		  sortingType: 'desc',
		  sortable: true,		  
		  width: 150,	
		  align: 'center'
		},	
		{
		  header: '상세내역(모델, 인승, 카사이즈)',
		  name: 'col7',
		  width:280,	
		  align: 'center'
		},	
		{
		  header: '비 고',
		  name: 'col10',
		  width:250,	
		  align: 'center'
		},		
		{
		  header: '수량',
		  name: 'col5',
		  width:150,	
		  align: 'center'
		},
		{
		  header: '운송내역',
		  name: 'col6',
		  width:120,	
		  align: 'center'
		},
		{
		  header: '현장주소',
		  name: 'col4',
		  width:200,	
		  align: 'center'
		},			
		{
		  header: 'rec No.',
		  name: 'col8',
		  width:50, 		
		  align: 'center'
		}			
	  ],
	columnOptions: {
			resizable: true
		  },
	  rowHeaders: ['rowNum','checkbox'],
	  // pageOptions: {
		// useClient: false,
		// perPage: 20
	  // }	  
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
    var link = 'http://8440.co.kr/ceiling/view.php?menu=no&num=' + numcopy[e.rowKey] ;
   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드	
   //  window.location.replace(link);     // 이전 페이지로 못돌아감
   //  window.open(link);  	
   if(numcopy[e.rowKey]>0)
       popupCenter(link, "천정/LC 수주내역", 1850, 920);
	
   console.log(e.rowKey);
});		
 			
// 출고증 출력하기 묶음출력	
$("#invoiceBtn").click(function() {
    var tmp = grid.getCheckedRowKeys();
    var col8Array = tmp.map(function(e) {
        return grid.getValue(e, 'col8'); // 각 행의 col8 값을 가져와 배열에 저장합니다.
    });
    var col8String = col8Array.join(','); // 배열을 콤마로 구분된 문자열로 변환합니다.
    console.log(col8String); // 콤마로 구분된 문자열을 출력합니다.
	
	// 출고일 입력하기
	$("#saveBtn").click(); 
	
	popupCenter('transform_group.php?array=' + encodeURIComponent(col8String), '출고증 인쇄', 1500,900);

});

$("#refresh").click(function(){  location.reload();   });	          // refresh
	
	// 납기일 일괄적용
	$("#saveDeadlineBtn").click(function(){ 
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
			grid.setValue(e, 'col9',$("#recordDate").val())  // ; appendRow(e+1);     납기일 넣어야 함		  
		});			
	   savegrid(choice="deadline");
	});	
	
	$("#saveBtn").click(function(){  
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
			grid.setValue(e, 'col1',$("#recordDate").val())  // ; appendRow(e+1);     출고일에 넣어야 함		  
		});			
	   savegrid();
	});	
	
	$("#cancelBtn").click(function(){    	
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
			grid.setValue(e, 'col1','')  // ; appendRow(e+1);     출고일에 넣어야 함		  
		});			
	   savegrid();
	});		
	
	$("#clearBtn").click(function(){  		
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col9','')  
		});	
	   savegrid();
	});		

// grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
function savegrid(choice=null) {		
		let num_arr               =  new Array();  
		let recordDate_arr        = new Array(); 		

		const MAXcount=grid.getRowCount();  				     
		let pushcount=0;
		
		for(i=0;i<MAXcount;i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.  
				num_arr.push(grid.getValue(i, 'col8'));
				if(choice == 'deadline')
					recordDate_arr.push(grid.getValue(i, 'col9'));  // 납기일 col9
				else
					recordDate_arr.push(grid.getValue(i, 'col1'));  // 출고일은 col1
			 }	
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
			
        console.log(choice);	
        console.log(recordDate_arr);	
        // console.log($("#board_form").serialize());	
		
		// 출고일 저장하기
	    $.ajax({
			url: "save_workday.php?choice=" + choice ,
    	  	type: "post",		
   			data: $("#board_form").serialize(),
   			dataType:"json",
			success : function(data){
				console.log( data);				
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
   }	
});


function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

</script>