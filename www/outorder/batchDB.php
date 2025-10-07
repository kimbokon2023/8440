<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 include getDocumentRoot() . '/load_header.php';

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
 ?>
 
 
  <title> 외주 청구일/출고일 일괄처리 </title> 
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

$orderby=" order by deadline desc ";
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		
  
if($mode=="search"){
		  if($search==""){
					 $sql="select * from mirae8440.outorder where deadline between date('$fromdate') and date('$Transtodate')" . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql = "SELECT * FROM mirae8440.outorder WHERE (";
						$fields = [
							'num', 'workday', 'checkstep', 'regist_day', 'workplacename', 'chargedman', 'address',
							'firstord', 'firstordman', 'firstordmantel', 'secondord', 'secondordman', 'secondordmantel',
							'worker', 'endworkday', 'memo', 'chargedmantel', 'orderday', 'measureday', 'drawday', 'deadline',
							'startday', 'testday', 'material1', 'material2', 'material3', 'material4', 'material5', 'material6',
							'widejamb', 'normaljamb', 'smalljamb', 'update_day', 'delivery', 'delicar', 'delicompany', 'delipay',
							'delimethod', 'demand', 'hpi', 'first_writer', 'update_log', 'filename1', 'filename2', 'su',
							'bon_su', 'lc_su', 'etc_su', 'air_su', 'order_com1', 'order_text1', 'order_com2', 'order_text2',
							'order_com3', 'order_text3', 'order_com4', 'order_text4', 'lc_draw', 'lclaser_com', 'lclaser_date',
							'lcbending_date', 'lcwelding_date', 'lcpainting_date', 'lcassembly_date', 'main_draw', 'eunsung_make_date',
							'eunsung_laser_date', 'mainbending_date', 'mainwelding_date', 'mainpainting_date', 'mainassembly_date',
							'memo2', 'order_date1', 'order_date2', 'order_date3', 'order_date4', 'order_input_date1', 'order_input_date2',
							'order_input_date3', 'order_input_date4', 'first_item1', 'first_item2', 'first_item3', 'first_item4',
							'second_item1', 'second_item2', 'second_item3', 'second_item4', 'third_item1', 'third_item2', 'third_item3',
							'third_item4', 'fourth_item1', 'fourth_item2', 'fourth_item3', 'fourth_item4', 'fifth_item1', 'fifth_item2',
							'fifth_item3', 'fifth_item4', 'sixth_item1', 'sixth_item2', 'sixth_item3', 'sixth_item4', 'seventh_item1',
							'seventh_item2', 'seventh_item3', 'seventh_item4', 'eighth_item1', 'eighth_item2', 'eighth_item3', 'eighth_item4',
							'ninth_item1', 'ninth_item2', 'ninth_item3', 'ninth_item4', 'tenth_item1', 'tenth_item2', 'tenth_item3',
							'tenth_item4', 'type1', 'type2', 'type3', 'type4', 'type5', 'type6', 'type7', 'type8', 'type9', 'type10',
							'inseung1', 'inseung2', 'inseung3', 'inseung4', 'inseung5', 'inseung6', 'inseung7', 'inseung8', 'inseung9', 'inseung10',
							'car_inside1', 'car_inside2', 'car_inside3', 'car_inside4', 'car_inside5', 'car_inside6', 'car_inside7', 'car_inside8', 'car_inside9', 'car_inside10',
							'comment1', 'comment2', 'comment3', 'comment4', 'comment5', 'comment6', 'comment7', 'comment8', 'comment9', 'comment10',
							'pdffile_name', 'copied_file', 'confirm', 'deliverynum', 'submemo'
						];

						foreach ($fields as $index => $field) {
							$sql .= ($index > 0 ? " OR " : "") . "$field LIKE '%$search%'";
						}

						$sql .= ") AND (deadline BETWEEN date('$fromdate') AND date('$Transtodate'))" . $orderby;

			     }    
}
  else
  {
    $sql="select * from mirae8440.outorder where deadline between date('$fromdate') and date('$Transtodate')" . $orderby;  			
  }
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

   $counter=0;
   $num_arr=array();
   $deadline_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $secondord_arr=array();
   $sum_arr=array();
   $delivery_arr=array();
   $content_arr=array();
   $num_arr=array();
   $demand_arr=array();
   $workday_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	 
               
              include '../outorder/_row.php';

              $demand=trans_date($demand);			  
              $workday=trans_date($workday);			  
			  
			  $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

		  $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),   L/C : "  . $sum[2] . "  (EA), 기타 : "  . $sum[3] . "  (EA)"; 			   			  
			
			
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
	   
		   $num_arr[$counter]=$num;
		   $deadline_arr[$counter]=$deadline;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;    
		   $delivery_arr[$counter]=$deli_text;    
		   $secondord_arr[$counter]=$secondord;   
		   $num_arr[$counter]=$num;    
		   $demand_arr[$counter]=$demand;    
		   $workday_arr[$counter]=$workday;    

		   $content_arr[$counter]=$type1 . " " . $inseung1 ." " . $car_inside1 ." " .   $first_item1 . " " .  $first_item2 . " " . $first_item3 ." " .  $first_item4 ." " .  $comment1 ;    
		   $content_arr[$counter] .=$type2 . " " . $inseung2 ." " . $car_inside2 ." " .   $second_item1 . " " .  $second_item2 . " " . $second_item3 ." " .  $second_item4 ." " .  $comment2 ;    
		   $content_arr[$counter] .=$type3 . " " . $inseung3 ." " . $car_inside3 ." " .   $third_item1 . " " .  $third_item2 . " " . $third_item3 ." " .  $third_item4 ." " .  $comment3 ;    
		   
   
    $sum_arr[$counter]=$workitem;
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
$all_sum=$sum1 + $sum2 + $sum3;		 		 
		 
			?>
		 
		 
<body >
<form name="board_form" id="board_form"  method="post" action="batchDB.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
 <div class="container-fluid">
    <div class="card">		
		<div class="card-header">    
						
				<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
				
					<span class="badge bg-success fs-6 ">	 외주 청구일/출고일 일괄처리   </span> &nbsp;&nbsp; 
				</div>
							
<div class="row"> 		  
	<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
	<!-- 기간설정 칸 -->
	 <?php include getDocumentRoot() . '/setdate.php' ?>
	</div>
</div>
			   
    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">    

	  납기일 기준 &nbsp;&nbsp;
	 <input type="date" id="recordDate" name="recordDate" class="form-control me-2" style="width:100px;" value="<?=$recordDate?>" placeholder=""> 선택체크	
	&nbsp;&nbsp;
	 <button type="button" id="saveBtn"  class="btn btn-secondary btn-sm me-2 "> 청구일 적용&저장 </button>	&nbsp;		
	 <button type="button" id="clearBtn"  class="btn btn-outline-danger btn-sm  me-2 "> 청구일 선택 Clear </button>	&nbsp;&nbsp;&nbsp;&nbsp;
	 <button type="button" id="OutputsaveBtn"  class="btn btn-secondary btn-sm  me-2 "> 출고일 적용&저장 </button>	&nbsp;		
	 <button type="button" id="OutputclearBtn"  class="btn btn-outline-danger btn-sm  me-2 "> 출고일 선택 Clear </button>	&nbsp;		
	</div> 

	 <div id="grid" style="width:1680px;">
  
	  </div>
	  </div>
	  </div>
  </div>
     
	 </form>

  <form id=Form1 name="Form1">
    <input type=hidden id="num_arr" name="num_arr[]" >
    <input type=hidden id="recordDate_arr" name="recordDate_arr[]">
  </form>
  
  </body>

</html>  

  
<script>	
	
$(document).ready(function(){
	
var num = <?php echo json_encode($num_arr);?> ;
var numcopy = new Array(); ;	
	
 var arr1 = <?php echo json_encode($deadline_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($address_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($delivery_arr);?> ;
 var arr6 = <?php echo json_encode($content_arr);?> ;
 var arr7 = <?php echo json_encode($secondord_arr);?> ;
 var arr8 = <?php echo json_encode($num_arr);?> ;
 var arr9 = <?php echo json_encode($demand_arr);?> ;
 var arr10 = <?php echo json_encode($workday_arr);?> ;
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
	  bodyHeight: 700,					  					
	  columns: [ 				   
		{
		  header: '납기일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:80,	
		  align: 'center'
		},			
		{
		  header: '청구일',
		  name: 'col9',
		  color : 'red',
		  sortingType: 'desc',
		  sortable: true,
		  width:80,	
		  align: 'center'
		},		
		{
		  header: '출고일',
		  name: 'col10',
		  color : 'red',
		  sortingType: 'desc',
		  sortable: true,
		  width:80,	
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col2',
		  width:280,	
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col3',
		  width: 150, 		
		  align: 'center'
		},
		{
		  header: '현장주소',
		  name: 'col4',
		  width:300,	
		  align: 'center'
		},		
		{
		  header: '수량',
		  name: 'col5',
		  width:150,
		  align: 'center'
		},
		{
		  header: '운송비',
		  name: 'col6',
		  width:120,
		  align: 'center'
		},
		{
		  header: '상세내역',
		  name: 'col7',
		  width:250,
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
 		
		
	
	$("#saveBtn").click(function(){    	
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col9',$("#recordDate").val())  // ; appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
		  //console.log($("#recordDate").val());
		  //console.log(e);
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
	
	$("#OutputsaveBtn").click(function(){    	
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col10',$("#recordDate").val())  // ; appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
		  //console.log($("#recordDate").val());
		  //console.log(e);
		});			
	   Outputsavegrid();
	});		
	
	$("#OutputclearBtn").click(function(){  		
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col10','')  
		});	
	   Outputsavegrid();
	});		

// grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
function savegrid() {		
		let num_arr               =  new Array();  
		let recordDate_arr        = new Array(); 		

		const MAXcount=grid.getRowCount();  				     
		let pushcount=0;
		for(i=0;i<MAXcount;i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.    
				num_arr.push(grid.getValue(i, 'col8'));
				recordDate_arr.push(grid.getValue(i, 'col9'));
			 }	
			 console.log(num_arr);
			 console.log(recordDate_arr);
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
        // console.log(num_arr);	
        // console.log(recordDate_arr);	
        //console.log($("#Form1").serialize());	
	    $.ajax({
			url: "saveDemand.php",
    	  	type: "post",		
 			dataType: "json",			
			// contentType : 'application/json;charset=utf-8',
   			data: $("#Form1").serialize(),   		
		// data: $("#Form1").serialize(),
  
			success : function( data ){
				console.log( data);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
   }
   
   
function Outputsavegrid() {		 // 출고일 저장
		let num_arr               =  new Array();  
		let recordDate_arr        = new Array(); 		

		const MAXcount=grid.getRowCount();  				     
		let pushcount=0;
		for(i=0;i<MAXcount;i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.    
				num_arr.push(grid.getValue(i, 'col8'));
				recordDate_arr.push(grid.getValue(i, 'col10'));  // 출고일 관련 컬럼번호 10번
			 }	
			 console.log(num_arr);
			 console.log(recordDate_arr);
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
        // console.log(num_arr);	
        // console.log(recordDate_arr);	
        //console.log($("#Form1").serialize());	
	    $.ajax({
			url: "SaveOutput.php",
    	  	type: "post",		
 			dataType: "json",			
			// contentType : 'application/json;charset=utf-8',
   			data: $("#Form1").serialize(),   		
		// data: $("#Form1").serialize(),
  
			success : function( data ){
				console.log( data);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
   }
	dis_text();
	

	
});  // end toast gui

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
		var dis_text = '<?php echo $dis_text; ?>';
		$("#dis_text").val(dis_text);
}	

</script>