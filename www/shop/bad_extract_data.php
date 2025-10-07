<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   } 


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
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 
 <title> DB전체추출 </title> 
 </head>


 <?php
 

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	   $search=$_REQUEST["search"];
	 else 
		 $search="";
	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
 if(isset($_REQUEST["bad_choice"])) // $_REQUEST["bad_choice"]값이 없을 때에는 1로 지정 
 {
    $bad_choice=$_REQUEST["bad_choice"];  // 페이지 번호
 }
  else
  {
    $bad_choice='없음';	 
  }	 	  
$read_bad_choice=$bad_choice;	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
  
$orderby="order by num desc ";	
	 
$a= " " . $orderby ;    

$sql ="select * from mirae8440.steel where (bad_choice = '$bad_choice')  order by outdate desc , num desc ";						  

  if($bad_choice=="불량전체") 
	  $sql ="select * from mirae8440.steel where (bad_choice like '%불량%')  order by outdate desc, num desc ";

   $counter=0;
   $num_arr=array();
   $outdate_arr=array();
   $indate_arr=array();
   $outworkplace_arr=array();
   $item_arr=array();
   $spec_arr=array();
   $steelnum_arr=array();
   $company_arr=array();
   $comment_arr=array();
   $which_arr=array();
   $model_arr=array();
   $bad_arr=array();

	 try{  
	 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	

              $num=$row["num"];
 			  $outdate=$row["outdate"];			  
			  
			  $indate=$row["indate"];
			  $outworkplace=$row["outworkplace"];
			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  $model=$row["model"];	 	
			  $search_opt=$row["search_opt"];
			  $bad_choice=$row["bad_choice"];				  
			  
  
   $num_arr[$counter]=$num;
   $outdate_arr[$counter]=$outdate;
   $indate_arr[$counter]=$indate;
   $outworkplace_arr[$counter]=$outworkplace;
   $item_arr[$counter]=$item;
   $spec_arr[$counter]=$spec;
   $steelnum_arr[$counter]=$steelnum;
   $company_arr[$counter]=$company;
   $comment_arr[$counter]=$comment;   
   $which_arr[$counter]=$which;
   $model_arr[$counter]=$model;
   $bad_arr[$counter]=$bad_choice;
	
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
		 
			?>
		 
<body >

 <div id="wrap">
   <div id="content">			 
  <button type="button" class="button button4"  onclick="location.href='./list.php';">   이전화면 돌아가기 </button>
 <div id="title" style="width:1600px;height:50px;" ><h1> 원자재 유형별 불량현황 List  &nbsp;&nbsp;&nbsp; <span style="font-size:18px;color:red;"> ( <?=$read_bad_choice?> )&nbsp;&nbsp; </span> </div>

	 <div id="grid" >
  
  </div>
     <div class="clear"></div> 		 

   <div class="clear"></div>	

  <div id="order2">
	   
	 </div> 
     <div class="clear"></div>
   
   </div> 	   
  </div> <!-- end of wrap -->
  
<script>

$(document).ready(function(){

 var arr1 = <?php echo json_encode($num_arr);?> ;
 var arr2 = <?php echo json_encode($outdate_arr);?> ;
 var arr3 = <?php echo json_encode($indate_arr);?> ;
 var arr4 = <?php echo json_encode($which_arr);?>; 
 var arr5 = <?php echo json_encode($outworkplace_arr);?> ;  
 var arr6 = <?php echo json_encode($model_arr);?> ;
 var arr7 = <?php echo json_encode($item_arr);?> ;
 var arr8 = <?php echo json_encode($spec_arr);?> ;
 var arr9 = <?php echo json_encode($steelnum_arr);?> ;
 var arr10 = <?php echo json_encode($company_arr);?> ;
 var arr11 = <?php echo json_encode($comment_arr);?> ;
 var arr12 = <?php echo json_encode($bad_arr);?> ;

  
 var rowNum = <?php echo $counter; ?>; 
   
  const data = [];
 const columns = [];	
 const COL_COUNT = 12; 
 
 for(i=0;i<rowNum;i++) { 	
             var tmp=arr4[i];
			 if(arr4[i]=='1') tmp="입고";
			    else
					tmp="출고";
		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] =     arr3[i] ;						 						
				row[`col4`] =    tmp ;					 						
				row[`col5`] =     arr5[i] ;						 						
				row[`col6`] =     arr6[i] ;						 						
				row[`col7`] =     arr7[i] ;						 						
				row[`col8`] =     arr8[i] ;					 						
				row[`col9`] =     arr9[i] ;					 						
				row[`col10`] =    arr10[i] ;						 						
				row[`col11`] =    arr12[i] ;						 						
				row[`col12`] =    arr11[i] ;						 															 						
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
		  header: '번호',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:60,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},			
		{
		  header: '입출고일',
		  name: 'col2',
		  width:100,
		  editor: {
			type: CustomTextEditor,			
		  },	 		
		  align: 'center'
		},
		{
		  header: '접수일',
		  name: 'col3',
		  width: 100,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '입출고',
		  name: 'col4',
		  width: 60,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '현장명',
		  name: 'col5',
		  width: 300,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '모델명',
		  name: 'col6',
		  width: 100,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '철판종류',
		  name: 'col7',
		  width: 100,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '규격',
		  name: 'col8',
		  width: 100,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '수량',
		  name: 'col9',
		  width: 50,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '해당업체',
		  name: 'col10',
		  width: 100,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '불량유형',
		  name: 'col11',
		  width: 200,
		  editor: {
			type: CustomTextEditor,
		  },	 		
		  align: 'center'
		},
		{
		  header: '비고',
		  name: 'col12',
		  width: 400,
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
   
	
});

  </script>
  


  </body>
  
  </html>