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

 <title> 출고일 기준 미청구리스트 DB </title> 
 </head>


 <?php
  
 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	   $search=$_REQUEST["search"];
	 else 
		 $search="";
	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
  
  
$attached=" ";
	
$orderby="order by workday desc";	
	 
$a= " " . $orderby ;    
		  
$sql ="select * from mirae8440.ceiling where ((workday!='') OR (workday!='0000-00-00')) and ((demand IS NULL) or (demand='0000-00-00')) "  . $attached . $a;	   

   $counter=0;
   $secondord_arr=array();
   $workplacename_arr=array();
   
   $sum1_arr=array();
   $sum2_arr=array();
   $sum3_arr=array();
   $sum_arr=array();
   $bon_su_arr=array();
   $lc_su_arr=array();
   $etc_su_arr=array();
   $air_su_arr=array();      
   $workday_arr=array();   
   $type_arr=array();   
   $car_insize_arr=array();   
   $delivery_arr=array();   
   $memo_arr=array();   
   
   $sum=array();
  
	 try{  
	 
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
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $delicompany=$row["delicompany"];
			  $delivery=$row["delivery"];
			  $delipay=$row["delipay"];
			  
			  $workday=$row["workday"];
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];

			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];			  
			  $car_insize=$row["car_insize"];			  
			  $order_com1=$row["order_com1"];			  
			  $order_text1=$row["order_text1"];			  
			  $order_com2=$row["order_com2"];			  
			  $order_text2=$row["order_text2"];			  
			  $order_com3=$row["order_com3"];			  
			  $order_text3=$row["order_text3"];			  
			  $order_com4=$row["order_com4"];			  
			  $order_text4=$row["order_text4"];			  
			  $lc_draw=$row["lc_draw"];			  
			  $lclaser_com=$row["lclaser_com"];			  
			  $lclaser_date=$row["lclaser_date"];			  
			  $lcbending_date=$row["lcbending_date"];			  
			  $lcwelding_date=$row["lcwelding_date"];			  
			  $lcpainting_date=$row["lcpainting_date"];			  
			  $lcassembly_date=$row["lcassembly_date"];			  
			  $main_draw=$row["main_draw"];			  
			  $eunsung_make_date=$row["eunsung_make_date"];			  
			  $eunsung_laser_date=$row["eunsung_laser_date"];			  
			  $mainbending_date=$row["mainbending_date"];			  
			  $mainwelding_date=$row["mainwelding_date"];			  
			  $mainpainting_date=$row["mainpainting_date"];			  
			  $mainassembly_date=$row["mainassembly_date"];			  
			  $memo2=$row["memo2"];			  	
            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];				  
			  
			  $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
			  
			  $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),  본천장 : " . $sum[1] . " (EA),  L/C : "  . $sum[2] . "  (EA), 기타 : "  . $sum[3] . "  (EA), 공기청정기 : "  . $sum[4] . " (EA) "; 			   			  

		      $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lc_draw=trans_date($lc_draw);
		      $lclaser_date=trans_date($lclaser_date);
		      $lclbending_date=trans_date($lclbending_date);
		      $lclwelding_date=trans_date($lclwelding_date);
		      $lcpainting_date=trans_date($lcpainting_date);
		      $lcassembly_date=trans_date($lcassembly_date);
		      $main_draw=trans_date($main_draw);			
		      $eunsung_make_date=trans_date($eunsung_make_date);			
		      $eunsung_laser_date=trans_date($eunsung_laser_date);			
		      $mainbending_date=trans_date($mainbending_date);			
		      $mainwelding_date=trans_date($mainwelding_date);			
		      $mainpainting_date=trans_date($mainpainting_date);			
		      $mainassembly_date=trans_date($mainassembly_date);										

		      $order_date1=trans_date($order_date1);					   
		      $order_date2=trans_date($order_date2);					   
		      $order_date3=trans_date($order_date3);					   
		      $order_date4=trans_date($order_date4);					   
		      $order_input_date1=trans_date($order_input_date1);					   
		      $order_input_date2=trans_date($order_input_date2);					   
		      $order_input_date3=trans_date($order_input_date3);					   
		      $order_input_date4=trans_date($order_input_date4);				
	   
			   $secondord_arr[$counter]=$secondord;
			   $workplacename_arr[$counter]=$workplacename;   
			   $bon_su_arr[$counter]=(int)$bon_su;   
			   $lc_su_arr[$counter]=(int)$lc_su;   
			   $etc_su_arr[$counter]=(int)$etc_su;   
			   $air_su_arr[$counter]=(int)$air_su;   
			   $workday_arr[$counter]=$workday;   
			   $type_arr[$counter]=$type;   
			   $car_insize_arr[$counter]=$car_insize;   
			   
			   $deli_text="";
				 if($delivery!="" || $delipay!=0)
				 		  $deli_text = $delivery . " " . $delipay ;  			   
			   $delivery_arr[$counter]=$deli_text; 
			   $memo_arr[$counter]=trim($memo) . ' ' . trim($memo2);   
			   
		   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
		 
			?>
		 
<body >

 <div id="wrap">
   <div id="content">			 
   <h1> &nbsp; 본천장/조명천장 출고완료 미청구 List </h1>
  <br>

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

 var arr1 = <?php echo json_encode($workday_arr);?> ; 
 var arr2 = <?php echo json_encode($secondord_arr);?> ;
 var arr3 = <?php echo json_encode($workplacename_arr);?> ;
 var arr4 = <?php echo json_encode($bon_su_arr);?> ;
 var arr5 = <?php echo json_encode($lc_su_arr);?> ;   
 var arr6 = <?php echo json_encode($etc_su_arr);?> ; 
 var arr7 = <?php echo json_encode($air_su_arr);?> ;  
 var arr8 = <?php echo json_encode($type_arr);?> ;  
 var arr9 = <?php echo json_encode($car_insize_arr);?> ;  
 var arr10 = <?php echo json_encode($delivery_arr);?> ;  
 var arr11 = <?php echo json_encode($memo_arr);?> ;  
  
 var rowNum = <?php echo $counter; ?>; 
 
const data = [];
 const columns = [];	
 const COL_COUNT = 13; 
 
 for(i=0;i<rowNum;i++) { 	
 
              if(arr4[i]=='0') arr4[i]="";
              if(arr5[i]=='0') arr5[i]="";
              if(arr6[i]=='0') arr6[i]="";
              if(arr7[i]=='0') arr7[i]="";

		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] =   arr1[i];	 						
				row[`col2`] =   arr2[i]; 						
				row[`col3`] =   arr3[i];	 						
				row[`col4`] =   arr4[i]; 						
				row[`col5`] =   arr5[i];	 						
				row[`col6`] =   arr6[i];	 						
				row[`col7`] =   arr7[i];	 						
				row[`col8`] =   arr8[i]; 						
				row[`col9`] =   arr9[i]; 						
				row[`col10`] =  arr10[i];	 						
				row[`col11`] =  arr11[i];	 						
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
	  bodyHeight: 1500,					  					
	  columns: [ 				   
		{
		  header: '출고일',
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
		  header: '발주처',
		  name: 'col2',
		  width:150,
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
		  name: 'col3',
		  width:350,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '본천장',
		  name: 'col4',
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
		  header: 'L/C',
		  name: 'col5',
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
		  header: '기타',
		  name: 'col6',
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
		  header: '공기청정기',
		  name: 'col7',
		  width: 60,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 40
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '타입',
		  name: 'col8',
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
		  header: 'Car Insize',
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
		  header: '운반비내역',
		  name: 'col10',
		  width:150,
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
		  name: 'col11',
		  width: 350,
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
   
	
});


</script>
</body>
</html>