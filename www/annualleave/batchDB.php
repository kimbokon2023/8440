<?php

	if(!isset($_SESSION))      
			session_start(); 
	if(isset($_SESSION["DB"]))
			$DB = $_SESSION["DB"] ;	
	 $level= $_SESSION["level"];
	 $user_name= $_SESSION["name"];
	 $user_id= $_SESSION["userid"];	

	 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {			  
		sleep(1);
		header("Location:http://8440.co.kr/login/login_form.php"); 
		exit;
	   }  

$tablename = 'eworks';

 ?>
 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>  
 
 <title> 연차 사용 리스트 </title> 
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

$orderby=" order by al_askdateto desc ";
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분		
  
if($mode=="search"){
		  if($search==""){
					 $sql="select * from " . $DB . "." . $tablename . "  where al_askdateto between date('$fromdate') and date('$Transtodate') AND is_deleted IS NULL " . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql ="select * from " . $DB . "." . $tablename . "  where ((author like '%$search%' )  or (al_askdateto like '%$search%' )  or (al_askdatefrom like '%$search%' ))  ";
					  $sql .=" and ( al_askdateto between date('$fromdate') and date('$Transtodate'))   AND is_deleted IS NULL " . $orderby ;				  		  		   
			     }    
}
  else
  {
    $sql="select * from " . $DB . "." . $tablename . "  where al_askdateto between date('$fromdate') and date('$Transtodate')  AND is_deleted IS NULL " . $orderby;  			
  }
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

   $counter=0;
   $num_arr=array();
   $id_arr=array();
   $name_arr=array();
   $part_arr=array();
   $registdate_arr=array();
   $item_arr=array();
   $al_askdatefrom_arr=array();
   $al_askdateto_arr=array();
   $usedday_arr=array();
   $content_arr=array();
   $state_arr=array();
   $sum1=0;
   $sum2=0;
   $sum3=0;


 try{  
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  


   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	 

		   include 'rowDBask.php';              		  
   
           array_push($num_arr,$num);
           array_push($id_arr,$author_id);
           array_push($name_arr,$author);
           array_push($part_arr,$al_part);
           array_push($registdate_arr,$registdate);
           array_push($item_arr,$al_item);
           array_push($al_askdatefrom_arr,$al_askdatefrom);
           array_push($al_askdateto_arr,$al_askdateto);
           array_push($usedday_arr,$al_usedday);
           array_push($content_arr,$al_content);
		   
			   switch($status) {
				   
				   case 'send':
				      $statusstr = '결재상신';
					  break;
				   case 'ing':
				      $statusstr = '결재중';
					  break;
				   case 'end':
				      $statusstr = '결재완료';
					  break;
				   default:
					  $statusstr = '';
					  break;
			   }		   
		   
           array_push($state_arr,$statusstr);
           
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
$all_sum=$sum1 + $sum2 + $sum3;		 		 
		 
			?>

<form name="board_form" id="board_form"  method="post" action="batchDB.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
 <div class="container-fluid">
    <div class="card">		
		<div class="card-header">    
						
				<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
				
					<span class="badge bg-primary fs-6 ">	직원 연차 list  </span> &nbsp;&nbsp; 
				</div>
									
		<div class="row"> 		  
			<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
			<!-- 기간설정 칸 -->
			 <?php include $_SERVER['DOCUMENT_ROOT'] . '/setdate.php' ?>
			</div>
		</div>		
	
			
     </div> <!-- end of card-header -->
	 
    <div class="card-body">		

	    <div id="grid" style="width:1250px;"></div>
     
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
	
	
$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
		
var arr1 = <?php echo json_encode($num_arr);?> ;

var numcopy = new Array(); ;	

 var arr2 = <?php echo json_encode($registdate_arr);?> ;	
 var arr3 = <?php echo json_encode($al_askdatefrom_arr);?> ;
 var arr4 = <?php echo json_encode($al_askdateto_arr);?> ;
 var arr5 = <?php echo json_encode($usedday_arr);?> ;
 var arr6 = <?php echo json_encode($name_arr);?> ; 
 var arr7 = <?php echo json_encode($state_arr);?> ;
 
 
 
 var total_sum=0; 
  
 var rowNum = arr1.length;
 
 const data = [];
 const columns = [];	
  var count=0;  // 전체줄수 카운트 
 
 for(i=0;i<rowNum;i++) {
			 total_sum = total_sum + Number(uncomma(arr6[i]));
		 row = { name: i };		 
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr3[i] ;						 						
				row[`col4`] = arr4[i] ;						 						
				row[`col5`] = arr5[i] ;						 						
				row[`col6`] = arr6[i] ;						 						
				row[`col7`] = arr7[i] ;						 													
				data.push(row); 				 
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
	  bodyHeight: 650,					  					
	  columns: [ 				   
		{
		  header: '번호',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:100,	
		  align: 'center'
		},			
		{
		  header: '접수일',
		  name: 'col2',
		  color : 'red',
		  sortingType: 'desc',
		  sortable: true,
		  width:150,	
		  align: 'center'
		},		
		{
		  header: '시작일',
		  name: 'col3',
		  color : 'red',
		  sortingType: 'desc',
		  sortable: true,
		  width:150,	
		  align: 'center'
		},			
		{
		  header: '종료일',
		  name: 'col4',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:150,	
		  align: 'center'
		},
		{
		  header: '사용일수',
		  name: 'col5',
		  width: 150, 		
		  align: 'center'
		},
		{
		  header: '성명',
		  name: 'col6',
		  width:150,	
		  align: 'center'
		},		
		{
		  header: '결재상태',
		  name: 'col7',
		  width:150,
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
	
		

	
});  // end toast gui


</script>