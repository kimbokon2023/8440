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
 <title> 검사일정 </title>  
 </head>
 
 <?php

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	   $search=$_REQUEST["search"];
	 else 
		 $search="";
	  
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
 	  
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
  
  
$attached=" ";
	
$orderby="order by testday desc, measureday desc ";	
	 
 $orderby=" order by workday desc ";
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		
  
if($search==""){
	 $sql="select * from mirae8440.work where workday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
   }
elseif($search!="")
{ 
	  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
	  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( workday between date('$fromdate') and date('$Transtodate'))" . $orderby;				  		  		   
 }    


   $counter=0;
   $secondord_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $sum1_arr=array();
   $sum2_arr=array();
   $sum3_arr=array();
   $sum_arr=array();
   $material_arr=array();
   $hpi_arr=array();
   $fistordman_arr=array();
   $fistordmantel_arr=array();   
   $chargedman_arr=array();
   $chargedmantel_arr=array();   
   $measureday_arr=array();   
   $worker_arr=array();   
   $endworkday_arr=array();   
   $draw_arr=array();   

	 try{  
	 
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

	   
   $secondord_arr[$counter]=$secondord;
   $workplacename_arr[$counter]=$workplacename;
   $address_arr[$counter]=$address;
   $material_arr[$counter]=$material1 . $material2 . $material3 . $material4 . $material5 . $material6 ;
   $chargedman_arr[$counter]=$chargedman;
   $chargedmantel_arr[$counter]=$chargedmantel;
   $firstordman_arr[$counter]=$firstordman;
   $firstordmantel_arr[$counter]=$firstordmantel;   
   $hpi_arr[$counter]=$hpi;   
   $startday_arr[$counter]=$startday;   
   $testday_arr[$counter]=$testday;   
   $worker_arr[$counter]=$worker;   
   $measureday_arr[$counter]=$measureday;   
   $endworkday_arr[$counter]=$endworkday;   
   
   		        $draw_arr[$counter]="";			  
			  if(substr($row["drawday"],0,2)=="20")  $draw_arr[$counter]= "OK";		  
   
				 if($widejamb!="")
					    $sum1_arr[$counter] += $widejamb;
				 if($normaljamb!="")
					    $sum2_arr[$counter] += $normaljamb;
				 if($smalljamb!="")
					    $sum3_arr[$counter] += $smalljamb;
   
    $sum_arr[$counter] = $sum_arr1[$counter] + $sum_arr2[$counter] + $sum3_arr[$counter];
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
		 
			?>
		 
<body >

<form name="board_form" id="board_form"  method="post" action="test_list.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
 <div class="container-fluid">
    <div class="card">		
		<div class="card-header">    
						
				<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
				
					<span class="badge bg-success fs-6 "> 검사일정  </span> &nbsp;&nbsp; 
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
						   
							<button type="button" id="searchBtn" class="btn btn-dark btn-sm"  ><i class="bi bi-search"></i> 검색 </button>	&nbsp;
							
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
	


 var arr1 = <?php echo json_encode($secondord_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($address_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($material_arr);?> ;
 var arr6 = <?php echo json_encode($hpi_arr);?> ;
 var arr7 = <?php echo json_encode($firstordman_arr);?> ;
 var arr8 = <?php echo json_encode($firstordmantel_arr);?> ;
 var arr9 = <?php echo json_encode($chargedman_arr);?> ;
 var arr10 = <?php echo json_encode($chargedmantel_arr);?>;
 var arr11 = <?php echo json_encode($startday_arr);?> ;
 var arr12 = <?php echo json_encode($testday_arr);?> ; 
 var arr13 = <?php echo json_encode($measureday_arr);?> ; 
 var arr14 = <?php echo json_encode($worker_arr);?> ; 
 var arr15 = <?php echo json_encode($sum1_arr);?> ; 
 var arr16 = <?php echo json_encode($sum2_arr);?> ; 
 var arr17 = <?php echo json_encode($sum3_arr);?> ; 
 var arr18 = <?php echo json_encode($endworkday_arr);?> ; 
 var arr19 = <?php echo json_encode($draw_arr);?> ; 
  
 var rowNum = <?php echo $counter; ?>; 

 const data = [];
 const columns = [];	
 const COL_COUNT = 13; 
 
 for(i=0;i<rowNum;i++) { 	

		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] =            arr12[i];						 						
				row[`col2`] =            arr13[i];					 						
				row[`col3`] =            arr19[i];						 						
				row[`col4`] =            arr18[i];					 						
				row[`col5`] =            arr14[i];						 						
				row[`col6`] =            arr2[i];						 						
				row[`col7`] =            arr3[i];						 						
				row[`col8`] =            arr15[i];					 						
				row[`col9`] =            arr16[i];					 						
				row[`col10`] =           arr17[i];						 						
				row[`col11`] =           arr5[i];						 						
				row[`col12`] =           arr7[i];						 						
				row[`col13`] =           arr8[i];						 						
				row[`col14`] =           arr9[i];						 						
				row[`col15`] =           arr10[i];						 						
				row[`col16`] =           arr11[i];						 						
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
		  header: '검사일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},			
		{
		  header: '실측일',
		  name: 'col2',
		  width:90,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '설계',
		  name: 'col3',
		  width: 40,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '생산예정일',
		  name: 'col4',
		  width:90,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '시공소장',
		  name: 'col5',
		  width:60,
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
		  name: 'col6',
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
		  header: '현장주소',
		  name: 'col7',
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
		  header: '막',
		  name: 'col8',
		  width:40,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '멍',
		  name: 'col9',
		  width:40,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '쪽',
		  name: 'col10',
		  width:40,
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
		  name: 'col11',
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
		  header: '담당자PM',
		  name: 'col12',
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
		  header: '담당전번',
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
		  header: '현장소장',
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
		  header: '소장전번',
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
		  header: '착공일',
		  name: 'col16',
		  width:90,
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
            var rowNum = <?php echo $counter; ?>; 		
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '검사일 ,' ;
			   row += '실측일 ,' ;
			   row += '설계 ,' ;
			   row += '생산예정일 ,' ;
			   row += '시공소장 ,' ;
			   row += '현장명 ,' ;
			   row += '현장주소 ,' ;
			   row += '막 ,' ;
			   row += '멍 ,' ;
			   row += '쪽 ,' ;
			   row += '재질 ,' ;
			   row += '담당자PM ,' ;
			   row += '담당전번 ,' ;
			   row += '현장소장 ,' ;
			   row += '소장전번 ,' ;
			   row += '착공일 ' ;
			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			for (let i = 0; i <rowNum; i++) {
			   let row = "";			  
			   row += (i+1) + ',' ;
			   for(let j=1; j<=16 ; j++) {
				  let tmp = String(grid.getValue(i, 'col'+j));
				  tmp = tmp.replace(/undefined/gi, "") ;
				  tmp = tmp.replace(/#/gi, " ") ;
				  row +=  tmp.replace(/,/gi, "-") + ',' ;
			   }

			   csvContent += row + "\r\n";
			}		 		  
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "miraeCSV_testday.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
	
});

function replaceAllaa(str, searchStr, replaceStr) {

  return  str.replace(/,/gi, "-");
}


  </script>
  


  </body>


  </html>