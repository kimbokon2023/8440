<?php 
require_once(includePath('session.php'));  

$title_message = 'jamb 출고증 일괄처리';
?>

<?php include getDocumentRoot() . '/load_header.php';
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

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
  
$outputdate = date("Y-m-d", time());

if($outputdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outputdate = $outputdate . $week[ date('w',  strtotime($outputdate)  ) ] ;
} 

// $fromdate = date("Y-m-d",time());	
// $Transtodate = date("Y-m-d",time());	
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		

$sql="select * from mirae8440.work where endworkday between date('$fromdate') and date('$Transtodate') ";  	

   $counter=0;
   $num_arr=array();   
   $endworkday_arr=array();   
   $workday_arr=array();
   $worker_arr=array();
   $workplacename_arr=array();
   $address_arr=array();      
   $item_arr=array(); 
   $material_arr=array();
   $work_order_arr=array();
   
   $sum1=0;
   $sum2=0;
   $sum3=0; 
      
  require_once("../lib/mydb.php");
  $pdo = db_connect();

 try{
	$stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1,$num,PDO::PARAM_STR); 
	$stmh->execute();
	$count = $stmh->rowCount();  
	  
while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	

     include getDocumentRoot() . '/work/_row.php';

	     switch ($worker) {
			case   "김운호"      : $workertel =  "010-9322-7626" ; break;
			case   "김상훈"      : $workertel =  "010-6622-2200" ; break;
			case   "이만희"      : $workertel =  "010-6866-5030" ; break; 
			case   "유영"        : $workertel =  "010-5838-5948" ; break;
			case   "추영덕"      : $workertel =  "010-6325-4280" ; break;
			case   "김지암"      : $workertel =  "010-3235-5850" ; break;
			case   "손상민"      : $workertel =  "010-4052-8930" ; break;
			case   "지영복"      : $workertel =  "010-6338-9718" ; break;
			case   "김한준"      : $workertel =  "010-4445-7515" ; break;
			case   "민경채"      : $workertel =  "010-2078-7238" ; break;
			case   "이용휘"      : $workertel =  "010-9453-8612" ; break;
			case   "박경호"      : $workertel =  "010-3405-6669" ; break;
			case   "조형영"      : $workertel =  "010-2419-2574" ; break;
			case   "김진섭"     : $workertel =  "010-6524-3325" ; break;
			case   "최양원"     : $workertel =  "010-5426-3475" ; break;
			case   "임형주"     : $workertel =  "010-8976-9777" ; break;
			case   "박철우"     : $workertel =  "010-4857-7022" ; break;
			case   "조장우"     : $workertel =  "010-5355-9709" ; break;
			case   "백석묵"     : $workertel =  "010-5635-0821" ; break;
			
			default: break;
		}	
	
	$text=array();			
	$textnum=array();	
	$textset=array();	
	
	$text[3]="";
	$text[4]="";
	
    $j=0;
	if($widejamb>=1) 	{
	     $text[$j]="막판(유) ";		
		 $textnum[$j]=$widejamb;
		 $textset[$j]="SET,";
		 $j++;
	                  }
	if($normaljamb>=1) 	{
	     $text[$j]="막판(무) ";		
		 $textnum[$j]=$normaljamb;
		 $textset[$j]="SET,";		 
		 $j++;
	                  }					  
	if($smalljamb>=1) 	{
	     $text[$j]="쪽쟘 ";		
		 $textnum[$j]=$smalljamb;
		 $textset[$j]="SET";	
		 $j++;
	                  }		
$textgroup = '';
for($i=0;$i<count($text);$i++)
       $textgroup .= $text[$i] . " " . $textnum[$i]  . " " . $textset[$i] ;				  
					  
    if($attachment != null and ($attachment != "x" or $attachment != "X") ) 	{
	     $text[$j]="부속자재 : " . $attachment ;		
		 $textnum[$j]= '';
		 $textset[$j]='';		 
		 $j++;
	                  }		
 
    $materials = "";

    if (!empty(trim($material1))) {
      $materials .= " " . $material1;
    }
    if (!empty(trim($material2))) {
      $materials .= " " . $material2;
    }
    if (!empty(trim($material3))) {
      $materials .= " " . $material3;
    }

    if (!empty(trim($material4))) {
      $materials .=  " " . $material4;
    }

    if (!empty(trim($material5))) {
      $materials .= $material5;
    }

    if (!empty(trim($material6))) {
      $materials .= " " . $material6;
    } else {
      $materials = rtrim($materials);
    }
		
 if($workday=='0000-00-00')
	   $workday = '';		
		
	   array_push($num_arr,$num);
	   array_push($address_arr, $address); 
	   array_push($workplacename_arr, $workplacename); 
	   array_push($workday_arr, $workday);	   
	   array_push($endworkday_arr, $endworkday);	   
	   array_push($worker_arr, $worker);								  
	   array_push($item_arr, $textgroup);								  
	   array_push($material_arr, $materials);								  
	   array_push($work_order_arr, $work_order);	// 작업순서
	
		}
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  
// 오늘을 담는 변수

$recordDate	=Date("Y-m-d",time());
		 
?>

<form name="board_form" id="board_form"  method="post" >  	
	<input type="hidden" id="num_arr" name="num_arr[]" >
	<input type="hidden" id="recordDate_arr" name="recordDate_arr[]">

<div class="container-fluid">
	<div class="card mt-2 mb-4">  
	<div class="card-body">  	
	<div class="card mt-2 mb-4">  
	<div class="card-body"> 
	<div class="d-flex justify-content-center align-items-center"> 
        <span class="fs-5 text-primary me-3"> 
        <?=$title_message?>  
	   </span>
		<button  type="button" class="btn btn-outline-secondary btn-sm mx-3" id="refresh"> <i class="bi bi-arrow-clockwise"></i> 새로고침 </button>	 
		<button  type="button" class="btn btn-dark btn-sm mx-3" onclick="window.close();"> <i class="bi bi-x-lg"></i> 창닫기 </button>
    </div>
	   
<div class="row"> 		  
	<div class="d-flex mt-1 justify-content-center align-items-center "> 			
		<!-- 기간부터 검색까지 연결 묶음 start -->
		<div class="card">
		<div class="card-body">
		<div class="d-flex justify-content-center align-items-center mb-2">
		<span id="showdate" class="btn btn-dark btn-sm " > 출고예정일 기간 </span>	&nbsp; 
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
				<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"  >  <i class="bi bi-search"></i> 검색 </button>										
		</div>	
		<div class="d-flex justify-content-center align-items-center mb-2">		
 		  		<input type="date" id="recordDate" name="recordDate" class="form-control me-1" style="width:120px;"  value="<?=$recordDate?>"  > 	        					
				<button type="button" id="saveDeadlineBtn" class="btn btn-outline-danger btn-sm mx-2">선택 출고예정일 변경</button> 
				<button type="button" id="saveBtn" class="btn btn-outline-success  btn-sm mx-2"> 선택 출고일 입력 </button>  
				<button type="button" id="cancelBtn" class="btn btn-danger btn-sm  mx-2"> 선택 출고일 취소 </button> 
				<button type="button" id="invoiceBtn" class="btn btn-primary btn-sm mx-2"><i class="bi bi-printer"></i>  선택 출고증</button>	
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

var ajaxRequest = null	;
$(document).ready(function(){
	
	$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
	
	var num = <?php echo json_encode($num_arr);?> ;
	var numcopy = new Array(); 	
	var arr1 = <?php echo json_encode($endworkday_arr);?> ;
	var arr2 = <?php echo json_encode($workday_arr);?> ;
	var arr3 = <?php echo json_encode($work_order_arr);?> ;
	var arr4 = <?php echo json_encode($workplacename_arr);?> ;
	var arr5 = <?php echo json_encode($address_arr);?> ;	
	var arr6 = <?php echo json_encode($material_arr);?> ;	
	var arr7 = <?php echo json_encode($worker_arr);?> ;	
	var arr8 = <?php echo json_encode($item_arr);?> ;	
	var total_sum = 0; 
  
 var rowNum =  num.length; 
 
 const data = [];
 const columns = [];	
 var count=0;  // 전체줄수 카운트  
 
 for(i=0;i<rowNum;i++) {			 
		 row = { name: i };		 
				row[`col1`] = arr1[i] ;	
				row[`col2`] = arr2[i] ;	
				row[`col3`] = arr3[i] ;	
				row[`col4`] = arr4[i] ;	
				row[`col5`] = arr5[i] ;	
				row[`col6`] = arr6[i] ;	
				row[`col7`] = arr7[i] ;	
				row[`col8`] = arr8[i] ;	
				row[`col9`] = num[i] ;	
				
				data.push(row); 	
				 numcopy[count] = num[i] ; 	
				 count++;					
 }

	const grid = new tui.Grid({
	  el: document.getElementById('grid'),
	  data: data,
	  bodyHeight: 500,
	  columns: [
		{ header: '출고예정', name: 'col1', sortable: true, width: 90, align: 'center' },
		{ header: '출고일', name: 'col2', sortable: true, width: 90, align: 'center' },
		{ header: '순서', name: 'col3', sortable: true, width: 60, align: 'center' },
		{ header: '현장명', name: 'col4', width: 400, align: 'center' },
		{ header: '주소', name: 'col5', sortable: true, width: 400, align: 'center' },
		{ header: '재질', name: 'col6', sortable: true, width: 150, align: 'center' },
		{ header: '소장', name: 'col7', sortable: true, width: 70, align: 'center' },
		{ header: '내역', name: 'col8', sortable: true, width: 300, align: 'center' },
		{ header: 'rec No.', name: 'col9', width: 50, align: 'center' }
	  ],
	  columnOptions: { resizable: true },
	  rowHeaders: ['rowNum', 'checkbox']
	});

	tui.Grid.applyTheme('default', {
	  cell: {
		normal: { background: '#fbfbfb', border: '#e0e0e0', showVerticalBorder: true },
		header: { background: '#eee', border: '#ccc', showVerticalBorder: true },
		focused: { border: '#418ed4' },
		disabled: { text: '#b0b0b0' }
	  }
	});	
		
	// 더블클릭 이벤트	
	grid.on('dblclick', (e) => {	
		var link = 'http://8440.co.kr/work/view.php?menu=no&num=' + numcopy[e.rowKey] ;   
		if(numcopy[e.rowKey]>0)
		   window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1900,height=900");	
	   console.log(e.rowKey);
	});		
 			
	// 출고증 출력하기 묶음출력	
	$("#invoiceBtn").click(function() {
		var tmp = grid.getCheckedRowKeys();
		var col8Array = tmp.map(function(e) {
			return grid.getValue(e, 'col9'); // 각 행의 col8 값을 가져와 배열에 저장합니다.
		});
		var col8String = col8Array.join(','); // 배열을 콤마로 구분된 문자열로 변환합니다.
		console.log(col8String); // 콤마로 구분된 문자열을 출력합니다.
		
		popupCenter('transform_group.php?array=' + encodeURIComponent(col8String), '출고증 인쇄', 1600,900);

	});

	$("#refresh").click(function(){  location.reload();   });	          // refresh

	$("#cancelBtn").click(function(){   
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
			grid.setValue(e, 'col2','')  // ; appendRow(e+1);     출고일에 넣어야 함		  
		});			
		savegrid();
	});		

	// 출고예정일 일괄적용
	$("#saveDeadlineBtn").click(function(){ 	   
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
			grid.setValue(e, 'col1', $("#recordDate").val())  // ; appendRow(e+1);     납기일 넣어야 함		  
		});			
	    savegrid(choice="endworkday");
	});	
	
	$("#saveBtn").click(function(){    	
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
			grid.setValue(e, 'col2',$("#recordDate").val())  // ; appendRow(e+1);     출고일에 넣어야 함		  
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
		let num_arr =  new Array();  
		let recordDate_arr = new Array(); 	 	

		const MAXcount=grid.getRowCount();  				     
		let pushcount=0;
		for(i=0;i<MAXcount;i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.    
				num_arr.push(grid.getValue(i, 'col9'));
				if(choice == 'endworkday')
					recordDate_arr.push(grid.getValue(i, 'col1'));  // 출고예정일 col1
				else
					recordDate_arr.push(grid.getValue(i, 'col2'));  // 출고일은 col2							
			 }	
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
        
			showSavingModal();
			// ajax 요청 중복호출 금지	
			if (ajaxRequest !== null) { ajaxRequest.abort(); }

			// data 전송해서 php 값을 넣기 위해 필요한 구문
			ajaxRequest = $.ajax({
					url: "save_workday.php?choice=" + choice ,
					type: "post",		
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){
						// location.reload();
						console.log( data);
							
						setTimeout(function() {			 
							hideSavingModal();
						}, 1000);				
							
						ajaxRequest = null	;
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
						ajaxRequest = null	;
					} 			      		
				   });
   }	
});

function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


function dis_text()
{  
		var dis_text = '<?php echo $dis_text; ?>';
		$("#dis_text").val(dis_text);
}	

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

</script>