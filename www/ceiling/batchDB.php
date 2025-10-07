<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 ?>
 
 <?php include getDocumentRoot() . '/load_header.php';

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
		 header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }     
 ?>  
 <title> 조명천장&본천장 청구일 일괄처리 </title> 
 
 </head>
 
 <?php 
 
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";
$recordDate = $_REQUEST["recordDate"] ?? date("Y-m-d");

$check = $_REQUEST["check"] ?? $_POST["check"];
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';

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
   $num_arr=array();   
   $workday_arr=array();
   $workplacename_arr=array();
   $price_arr=array();
   $secondord_arr=array();
   $sum_arr=array();
   $delivery_arr=array();
   $content_arr=array();
   $num_arr=array();
   $demand_arr=array();
   $memo_arr=array();
   
	$su_sum_arr = 0;
	$bon_su_sum_arr = 0;
	$lc_su_sum_arr = 0;
	$etc_su_sum_arr = 0;
	
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
   
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	 

			 include '_rowDB.php';

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
	   
		   $num_arr[$counter]=$num; 	   
		   $workday_arr[$counter]=$workday;
		   $workplacename_arr[$counter]=$workplacename;
		   $price_arr[$counter]=$price;    
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

$su_sum_arr = $sum[0] ;
$bon_su_sum_arr = $sum[1] ;
$lc_su_sum_arr = $sum[2] ;		 
$etc_su_sum_arr = $sum[3] ;		 
			?>
		 
<body >
<form name="board_form" id="board_form"  method="post" action="batchDB.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
 <div class="container-fluid">
    <div class="card">		
		<div class="card-header">    
						
				<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
				
					<span class="badge bg-success fs-6 ">	일괄처리(청구일)  </span> &nbsp;&nbsp; 
				</div>
							
<div class="row"> 		  
	<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
	<!-- 기간설정 칸 -->
	 <?php include getDocumentRoot() . '/setdate.php' ?>
	</div>
</div>
			
    
    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">    
		<input type="date" id="recordDate" name="recordDate" class="form-control me-1" style="width:100px;"  value="<?=$recordDate?>"  > 	        
		&nbsp;&nbsp; 선택  &nbsp;&nbsp;
        <button type="button" id="saveBtn" class="btn btn-dark btn-sm">적용&저장</button>&nbsp;&nbsp;        
        <button type="button" id="clearBtn" class="btn btn-outline-danger btn-sm"> Clear</button>
    </div>
	
			
     </div> <!-- end of card-header -->
	 
    <div class="card-body">		

	    <div id="grid" style="width:1720px;"></div>
     
	 </div> 	   
  </div> 
</div> <!-- end of container -->
</form> <!-- end of board_form -->

  
<form id=Form1 name="Form1">
    <input type=hidden id="num_arr" name="num_arr[]" >
    <input type=hidden id="recordDate_arr" name="recordDate_arr[]">
</form>
  
  </body>

</html>  


  
<script>	
	
$(document).ready(function(){
		
	
var num = <?php echo json_encode($num_arr);?> ;
var numcopy = new Array(); 	
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($price_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($delivery_arr);?> ;
 var arr6 = <?php echo json_encode($content_arr);?> ;
 var arr7 = <?php echo json_encode($secondord_arr);?> ;
 var arr8 = <?php echo json_encode($num_arr);?> ;
 var arr9 = <?php echo json_encode($demand_arr);?> ;
 var arr10 = <?php echo json_encode($memo_arr);?> ;
 var su = '<?php echo $su_sum_arr;?>' ;
 var bon_su = '<?php echo $bon_su_sum_arr;?>' ; 
 var lc_su = '<?php echo $lc_su_sum_arr;?>' ; 
 var etc_su = '<?php echo $etc_su_sum_arr;?>' ; 
  
 var rowNum = "<? echo $counter; ?>" ; 
 var jamb_total = "<? echo $jamb_total; ?>"; 
 
 const data = [];
 const columns = [];	
 var count=0;  // 전체줄수 카운트  
 
 for(i=0;i<rowNum;i++) {			 
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

// 합계 넣어주기 
 row = { name: count};		 
		
		row[`col5`] = "합: " + su + ", 본:" + bon_su + ", LC: "+ lc_su  + ", 기타: " + etc_su  ;	
		data.push(row);  
			

const grid = new tui.Grid({
	  el: document.getElementById('grid'),
	  data: data,
	  bodyHeight: 700,					  					
	  columns: [ 				   
		{
		  header: '출고일',
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
		  header: '현장명',
		  name: 'col2',
		  width:250,	
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col3',
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
		  header: '제품가격',
		  name: 'col4',
		  width:150,	
		  align: 'center'
		},			
		{
		  header: '수량',
		  name: 'col5',
		  width:200,	
		  align: 'center'
		},
		{
		  header: '운송내역',
		  name: 'col6',
		  width:120,	
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
	
    var link = 'http://8440.co.kr/ceiling/view.php?num=' + numcopy[e.rowKey] ;
   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
	
   //  window.location.replace(link);     // 이전 페이지로 못돌아감
   //  window.open(link);  	
   if(numcopy[e.rowKey]>0)
       window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1800,height=900");
	
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
	
	window.open('transform_group.php?array=' + encodeURIComponent(col8String), '출고증 인쇄', 'left=100,top=100,scrollbars=yes,toolbars=no,width=1500,height=900');

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
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
        // console.log(num_arr);	
        // console.log(recordDate_arr);	
        // console.log($("#Form1").serialize());	
	    $.ajax({
			url: "saveDemand.php",
    	  	type: "post",		
   			data: $("#Form1").serialize(),
   			dataType:"json",
			success : function( data ){
				console.log( data);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
   }
	
});



</script>