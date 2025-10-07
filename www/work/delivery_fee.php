<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	


 ?>
 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  


 ?>
 
 <title> 배송비 </title> 
 
 <?php 

  
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 $sortof=$_REQUEST["sortof"];  // 클릭해서 넘겨준 값
 $stable=$_REQUEST["stable"];    // 정렬모드 변경할지 안할지 결정  
  
  $sum=array(); 
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";        
 
  
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 
 
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
 
  if(isset($_REQUEST["search"]))   //
 $search=$_REQUEST["search"];

 $orderby=" order by workday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 생산예정으로 구분		
  
  if($search==""){
			 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
		   }
	 elseif($search!="")
		{ 
			  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
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
   $sum_arr=array();
   $delicompany_arr=array();
   $delipay_arr=array();
   $firstord_arr=array();
   $secondord_arr=array();
   $worker_arr=array();
   $material_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
 
   // $sql="select * from mirae8440.work"; 		 
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
		   $secondord_arr[$counter]=$secondord;   
		   $firstord_arr[$counter]=$firstord;   
		   $worker_arr[$counter]=$worker;   
		
                  $materials="";
				  $materials=$material2 . " " . $material1 . " " . $material3 . $material4 . $material5 . $material6;		
		   
		   $material_arr[$counter]=$materials;   

   
   				 $workitem="";
				 if($widejamb!="")   {
					    $workitem="막판" . $widejamb . " "; 
						$sum1 += (int)$widejamb;
									}
				 if($normaljamb!="")   {
					    $workitem .="막(無)" . $normaljamb . " "; 					
						$sum2 += (int)$normaljamb;						
						}
				 if($smalljamb!="") {
					    $workitem .="쪽쟘" . $smalljamb . " "; 												   
						$sum3 += (int)$smalljamb;												
						}
   
    $sum_arr[$counter]=$workitem;
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
$all_sum=$sum1 + $sum2 + $sum3;		 
$jamb_total = "막판:" . $sum1 . ", " . "막판(無):" . $sum2 . ", " . "쪽쟘:" . $sum3  . "  합계:" . $all_sum;
			?>		 
		 
<body >

<form name="board_form" id="board_form"  method="post" action="delivery_fee.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
 <div class="container-fluid">
    <div class="card">		
		<div class="card-header">    
						
				<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
				
					<span class="badge bg-success fs-6 "> 배송비  </span> &nbsp;&nbsp; 
				</div>
							
				<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  

					<div class="input-group p-1 mb-1  justify-content-center align-items-center">	  
					 
					   <span class='input-group-text align-items-center' style='width:400px;'>
					   <span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 
						<div id="showframe" class="card">
							<div class="card-header " style="padding:2px;">
								기간 검색
							</div>
							<div class="card-body">
								<button type="button" id="preyear" class="btn btn-primary btn-sm "   onclick='pre_year()' > 전년도 </button>  
								<button type="button" id="three_month" class="btn btn-secondary btn-sm "  onclick='three_month_ago()' > M-3월 </button>
								<button type="button" id="prepremonth" class="btn btn-secondary btn-sm "  onclick='prepre_month()' > 전전월 </button>	
								<button type="button" id="premonth" class="btn btn-secondary btn-sm "  onclick='pre_month()' > 전월 </button> 						
								<button type="button" class="btn btn-danger btn-sm "  onclick='this_today()' > 오늘 </button>
								<button type="button" id="thismonth" class="btn btn-dark btn-sm "  onclick='this_month()' > 당월 </button>
								<button type="button" id="thisyear" class="btn btn-dark btn-sm "  onclick='this_year()' > 당해년도 </button> 
							</div>							
						</div>

						   <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
						   <input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝"> 	&nbsp;
						   
							<button type="button" id="searchBtn" class="btn btn-dark  btn-sm "  > <i class="bi bi-search"></i> 검색  </button>	&nbsp;
							
					  </span> 										
						
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
 var arr8 = <?php echo json_encode($worker_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($delicompany_arr);?> ;
 var arr6 = <?php echo json_encode($delipay_arr);?> ;
 var arr7 = <?php echo json_encode($secondord_arr);?> ;
 var arr9 = <?php echo json_encode($firstord_arr);?> ;
 var arr10 = <?php echo json_encode($material_arr);?> ;
 var total_sum=0;
 
  
 var rowNum = "<? echo $counter; ?>" ; 
 var jamb_total = "<? echo $jamb_total; ?>"; 
 
 const data = [];
 const columns = [];	
 const COL_COUNT = 10;
 
 // table1.setRowData(0,["발주처","현장명","현장주소","설치수량","재질","HPI형태","담당자PM","담당전번","현장소장","소장전번","착공일","검사일"]);	    
 for(i=0;i<rowNum;i++) {
			 total_sum = total_sum + Number(uncomma(arr6[i]));
		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr9[i] ;						 						
				row[`col4`] = arr7[i] ;						 						
				row[`col5`] = arr3[i] ;						 						
				row[`col6`] = arr8[i] ;						 						
				row[`col7`] = arr10[i] ;						 						
				row[`col8`] = arr4[i] ;						 						
				row[`col9`] = arr5[i] ;						 						
				row[`col10`] = arr6[i] ;						 						
						}
				data.push(row); 	 			 
 }
	i++;		
	row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = '' ;						 						
				row[`col2`] = '' ;						 						
				row[`col3`] = '' ;						 						
				row[`col4`] = '' ;						 						
				row[`col5`] = jamb_total ;						 						
				row[`col6`] = '' ;						 						
				row[`col7`] = '' ;						 						
				row[`col8`] = '배송비 합계';					 						
				row[`col9`] = '';						 						
				row[`col10`] = comma(total_sum)  ;						 						
			}
	data.push(row); 	 			 
			
			

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
		  header: '출고일',
		  name: 'col1',
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
		  header: '현장명',
		  name: 'col2',
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
		  header: '원청',
		  name: 'col3',
		  width: 150,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col4',
		  width: 150,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '현장주소',
		  name: 'col5',
		  width:260,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '시공소장',
		  name: 'col6',
		  width:80,
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
		  name: 'col7',
		  width:300,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}
		  },	 		
		  align: 'center'
		},
		{
		  header: '수량',
		  name: 'col8',
		  width:150,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '운송자',
		  name: 'col9',
		  width:120,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '비용',
		  name: 'col10',
		  width:100,
		  editor: {
			type: CustomTextEditor,
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
	
	

$("#downloadcsvBtn").click(function(){  Do_gridexport();   });	          // CSV파일 클릭	
//////////////////// saveCSV	
Do_gridexport = function () { 
	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '출고일 ,' ;
			   row += '현장명 ,' ;
			   row += '원청 ,' ;
			   row += '발주처 ,' ;
			   row += '현장주소 ,' ;
			   row += '시공소장 ,' ;
			   row += '재질 ,' ;
			   row += '수량 ,' ;
			   row += '운송자 ,' ;
			   row += '비용 ' ;

			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			const COLNUM = 10;   
			for (let i = 0; i <grid.getRowCount(); i++) {
			   let row = "";			  
			   row += (i+1) + ',' ;
			   for(let j=1; j<=COLNUM ; j++) {
				  let tmp = String(grid.getValue(i, 'col'+j));
				  tmp = tmp.replace(/undefined/gi, "") ;
				  tmp = tmp.replace(/#/gi, " ") ;
				  row +=  tmp.replace(/,/gi, "") + ',' ;
			   }

			   csvContent += row + "\r\n";
			}		 		  
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "miraeCSV_deliveryFee.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
	
		
	dis_text();
	
	
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
		var dis_text = '<?php echo $jamb_total; ?>';
		$("#dis_text").val(dis_text);
}	
</script>

  </html>

</body>