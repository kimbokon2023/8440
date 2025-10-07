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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>	
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

 <title> 서한컴퍼니 외주 청구일 일괄처리 </title> 
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
	 
 if(isset($_REQUEST["team_check"])) 
	 $team_check=$_REQUEST["team_check"]; // 시공팀미지정
   else
	if(isset($_POST["team_check"]))   
         $team_check=$_POST["team_check"]; // 시공팀미지정
	 else
		 $team_check='0';	 
	 
 if(isset($_REQUEST["measure_check"])) 
	 $measure_check=$_REQUEST["measure_check"]; // 미실측리스트
   else
	if(isset($_POST["measure_check"]))   
         $measure_check=$_POST["measure_check"]; // 미실측리스트
	 else
		 $measure_check='0';		 
  
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
  
 
 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}  
  
// print $output_check;
  
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
					 $sql="select * from mirae8440.oem where deadline between date('$fromdate') and date('$Transtodate')" . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql ="select * from mirae8440.oem where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delivery like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (memo like '%$search%' )) and ( deadline between date('$fromdate') and date('$Transtodate'))" . $orderby ;				  		  		   
			     }    
}
  else
  {
    $sql="select * from mirae8440.oem where deadline between date('$fromdate') and date('$Transtodate')" . $orderby;  			
  }
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

   $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $address_arr=array();
   $secondord_arr=array();
   $sum_arr=array();
   $delivery_arr=array();
   $content_arr=array();
   $num_arr=array();
   $demand_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
 
   // $sql="select * from mirae8440.oem"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	 

			  $num=$row["num"];

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
			  
			  $type1=$row["type1"];			  
			  $inseung1=$row["inseung1"];			  
			  $car_insize1=$row["car_insize1"];		  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];					  			

			$type2=$row["type2"];
			$type3=$row["type3"];
			$type4=$row["type4"];
			$type5=$row["type5"];
			$type6=$row["type6"];
			$type7=$row["type7"];
			$type8=$row["type8"];
			$type9=$row["type9"];
			$type10=$row["type10"];			  
			$inseung2=$row["inseung2"];
			$inseung3=$row["inseung3"];
			$inseung4=$row["inseung4"];
			$inseung5=$row["inseung5"];
			$inseung6=$row["inseung6"];
			$inseung7=$row["inseung7"];
			$inseung8=$row["inseung8"];
			$inseung9=$row["inseung9"];
			$inseung10=$row["inseung10"];
			$car_insize2=$row["car_insize2"];
			$car_insize3=$row["car_insize3"];
			$car_insize4=$row["car_insize4"];
			$car_insize5=$row["car_insize5"];
			$car_insize6=$row["car_insize6"];
			$car_insize7=$row["car_insize7"];
			$car_insize8=$row["car_insize8"];
			$car_insize9=$row["car_insize9"];
			$car_insize10=$row["car_insize10"];  
			
			$comment1=$row["comment1"];
			$comment2=$row["comment2"];
			$comment3=$row["comment3"];
			$comment4=$row["comment4"];
			$comment5=$row["comment5"];
			$comment6=$row["comment6"];
			$comment7=$row["comment7"];
			$comment8=$row["comment8"];
			$comment9=$row["comment9"];
			$comment10=$row["comment10"]; 								  			
            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];		

              $demand=trans_date($demand);			  
			  
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
	   
		   $workday_arr[$counter]=$workday;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;    
		   $delivery_arr[$counter]=$delivery;    
		   $secondord_arr[$counter]=$secondord;   
		   $num_arr[$counter]=$num;    
		   $demand_arr[$counter]=$demand;    

		   $content_arr[$counter]=$type1 . " " . $inseung1 ." " . $car_insize1 ." " .   $first_item1 . " " .  $first_item2 . " " . $first_item3 ." " .  $first_item4 ." " .  $comment1 ;    
		   $content_arr[$counter] .=$type2 . " " . $inseung2 ." " . $car_insize2 ." " .   $second_item1 . " " .  $second_item2 . " " . $second_item3 ." " .  $second_item4 ." " .  $comment2 ;    
		   $content_arr[$counter] .=$type3 . " " . $inseung3 ." " . $car_insize3 ." " .   $third_item1 . " " .  $third_item2 . " " . $third_item3 ." " .  $third_item4 ." " .  $comment3 ;    
		   
   
    $sum_arr[$counter]=$workitem;
		
	   $counter++;
	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
$all_sum=$sum1 + $sum2 + $sum3;		 		 
		 
			?>
		 
<body >

 <div id="wrap">
  
   <div id="content" style="width:1450px;">			 
   <form name="board_form" id="board_form"  method="post" action="batchDB.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
   <div id="list_search" style="width:1200px;">
		
        <div id="list_search111"> 
			 <?php
	    if($separate_date=="1") {
			 ?>
			&nbsp; 입출고일 <input type="radio" checked name="separate_date" value="1">
			&nbsp; 접수일 <input type="radio" name="separate_date" value="2">
			<?php
             		}    ?>	 
			 <?php
	    if($separate_date=="2") {
			 ?>
			&nbsp; 입출고일 <input type="radio"  name="separate_date" value="1">
			&nbsp; 접수일 <input type="radio" checked name="separate_date" value="2">
			<?php
             		}    ?>	 		

	   <input id ="prepremonth" type='button' onclick='prepre_month()' value='전전월'>	 
	   <input id ="premonth" type='button' onclick='pre_month()' value='전월'>	 
       <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">부터	   
       <input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">까지 	   
	   <input id ="thismonth" type='button' onclick='this_month()' value='당월'>
       <input id ="thisyear" type='button' onclick='this_year()' value='당해년도'>		 
       </div>		
        <div id="list_search2"> <img src="../img/select_search.gif"></div>

        <div id="list_search4"><input type="text" name="search" id="search" value="<?=$search?>"> </div>

        <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<div id="list_search6"> 합계 :  <input type="text" id="dis_text" size="100" style="font-size:12px;">  </div> <br>

      </div> <!-- end of list_search -->  
     <div class="clear"></div> 		 
	 <h3 class="input-text" > <h3> 출고일 기준 자료 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="date" id="recordDate" name="recordDate" size="12" value="<?=$recordDate?>" placeholder=""> 선택체크	
	 <button type="button" id="saveBtn"  class="btn btn-secondary"> 적용&저장 </button>	&nbsp;		
	 <button type="button" id="clearBtn"  class="btn btn-outline-danger"> 선택 Clear </button>	&nbsp;		
	 </h3>
     <div class="clear"></div> 		 

	 <div id="grid" style="width:1620px;">
  
  </div>
     <div class="clear"></div> 		 
	 </form>
	 </div>

   <div class="clear"></div>	
   
   </div> 	   
  </div> <!-- end of wrap -->
  
  <form id=Form1 name="Form1">
    <input type=hidden id="num_arr" name="num_arr[]" >
    <input type=hidden id="recordDate_arr" name="recordDate_arr[]">
  </form>
  
  </body>

</html>  

  
<script>	
	
$(document).ready(function(){
	
 var arr1 = <?php echo json_encode($workday_arr);?> ;
 var arr2 = <?php echo json_encode($workplacename_arr);?> ;
 var arr3 = <?php echo json_encode($address_arr);?> ;
 var arr4 = <?php echo json_encode($sum_arr);?> ;  
 var arr5 = <?php echo json_encode($delivery_arr);?> ;
 var arr6 = <?php echo json_encode($content_arr);?> ;
 var arr7 = <?php echo json_encode($secondord_arr);?> ;
 var arr8 = <?php echo json_encode($num_arr);?> ;
 var arr9 = <?php echo json_encode($demand_arr);?> ;
 var total_sum=0; 
  
 var rowNum = "<? echo $counter; ?>" ; 
 var jamb_total = "<? echo $jamb_total; ?>"; 
 
 const data = [];
 const columns = [];	 
 
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
		  header: '출고일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
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
		  header: '청구일',
		  name: 'col9',
		  color : 'red',
		  sortingType: 'desc',
		  sortable: true,
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
		  header: '현장명',
		  name: 'col2',
		  width:280,
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
		  header: '현장주소',
		  name: 'col4',
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
		  name: 'col5',
		  width:150,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '운송내역',
		  name: 'col6',
		  width:120,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
		  },	 		
		  align: 'center'
		},
		{
		  header: '상세내역',
		  name: 'col7',
		  width:250,
		  editor: {
			type: CustomTextEditor,
			options: {
			  maxLength: 50
			}			
		  },	 		
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
		var dis_text = '<?php echo $dis_text; ?>';
		$("#dis_text").val(dis_text);
}	

</script>