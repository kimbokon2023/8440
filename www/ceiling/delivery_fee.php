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
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   } 

 ?>
 
<title> 배송비 정산 </title>  

<?php 

$check = $_REQUEST["check"] ?? $_POST["check"];
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;
$cursort = $_REQUEST["cursort"] ?? 0;
$sortof = $_REQUEST["sortof"] ?? 0;
$stable = $_REQUEST["stable"] ?? 0;
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';

// 정렬 로직
if ($sortof != 0 && $stable == 0) {
    $increment = ($cursort % 2 == 0) ? 1 : 2;
    $cursort = (($sortof - 1) * 2) + $increment;
}

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 6개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
	
 
$orderby=" order by workday desc ";
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		
  
if($search==""){
		 $sql="select * from mirae8440.ceiling where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
	   }
 elseif($search!="")
	{ 
		  $sql ="select * from mirae8440.ceiling where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
		  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$fromdate') and date('$Transtodate'))" . $orderby;				  		  		   
	 }    

  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  
// print $search;
// print $sql;

   $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $secondord_arr=array();
   $sum_arr=array();
   $delicompany_arr=array();
   $delipay_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
 
   // $sql="select * from mirae8440.ceiling"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
           include '_rowDB.php';
 		  
			  
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
	   
		   $workday_arr[$counter]=$workday;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;
		   $delicompany_arr[$counter]=$delicompany;   
		   $delipay_arr[$counter]=$delipay;    
		   $delivery_arr[$counter]=$delivery;    
		   $secondord_arr[$counter]=$secondord;    
   
    $sum_arr[$counter]=$workitem;
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
$all_sum=$sum1 + $sum2 + $sum3;		 		 
		 
			?>
		 
<body >

<form name="board_form" id="board_form"  method="post" action="delivery_fee.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
 <div class="container-fluid">
    <div class="card">		
		<div class="card-header">    
					
			<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
			
				<span class="badge bg-success fs-6 "> 배송비  </span> &nbsp;&nbsp; 
			</div>
										
			<div class="row"> 		  
				<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
				<!-- 기간설정 칸 -->
				 <?php include getDocumentRoot() . '/setdate.php' ?>
				</div>
			</div>			
			
	 </div>
    <div class="card-body">		
		 <div id="grid" > </div>		 
	</div> 	   
  </div> 
</div> <!-- end of container -->

</form>

  
<script>
$(document).ready(function(){
	
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($address_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($delivery_arr);?> ;
 var arr6 = <?php echo json_encode($delipay_arr);?> ;
 var arr7 = <?php echo json_encode($secondord_arr);?> ;
 var total_sum=0;
 
  
 var rowNum = "<? echo $counter; ?>" ; 
 var jamb_total = "<? echo $jamb_total; ?>"; 
 
 const data = [];
 const columns = [];	
 const COL_COUNT = 8;
 
 for(i=0;i<rowNum;i++) {
			 total_sum = total_sum + Number(uncomma(arr6[i]));
		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr7[i] ;						 						
				row[`col4`] = arr3[i] ;						 						
				row[`col5`] = arr4[i] ;						 						
				row[`col6`] = arr5[i] ;						 						
				row[`col7`] = arr6[i] ;						 						
						}
				data.push(row); 	 			 
 }
	i++;		
	row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = '' ;						 						
				row[`col2`] = '' ;						 						
				row[`col3`] = '' ;						 						
				row[`col4`] = jamb_total ;						 						
				row[`col5`] = '' ;						 						
				row[`col6`] = '배송비 합계';					 						
				row[`col7`] = comma(total_sum)  ;						 						
			}
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
		  width:100,
		  editor: {
			type: 'text',
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col2',
		  width:380,
		  editor: {
			type: 'text',
			options: {
			  maxLength: 50
			}			
			
		  },	 		
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col3',
		  width: 150,
		  editor: {
			type: 'text',
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '현장주소',
		  name: 'col4',
		  width:300,
		  editor: {
			type: 'text',
		  },	 		
		  align: 'center'
		},		
		{
		  header: '수량',
		  name: 'col5',
		  width:150,
		  editor: {
			type: 'text',
		  },	 		
		  align: 'center'
		},
		{
		  header: '운송내역',
		  name: 'col6',
		  width:120,
		  editor: {
			type: 'text',
		  },	 		
		  align: 'center'
		},
		{
		  header: '비용',
		  name: 'col7',
		  width:100,
		  editor: {
			type: 'text',
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
	
		
});

</script>

 </body>
</html>  
