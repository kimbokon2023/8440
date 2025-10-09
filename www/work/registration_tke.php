<?php
require_once __DIR__ . '/../bootstrap.php';

$level = $_SESSION["level"] ?? null;

include includePath('load_header.php');
?>

<title> TKE 엑셀 양식 일괄등록 </title> 
</head>

<?php 

$recordDate = $_REQUEST["recordDate"] ?? date("Y-m-d");

// 변수 초기화
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '1';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;
$cursort = $_REQUEST["cursort"] ?? '';
$sortof = $_REQUEST["sortof"] ?? '';
$stable = $_REQUEST["stable"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
  
$sum = array();
 
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
 
$orderby=" order by workday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 생산예정으로 구분		
  
  
		  if($search==""){
					 $sql="select * from mirae8440.work where (workday between date('$fromdate') and date('$Transtodate') ) and (filename1 is null  or filename2 is null or doneday is null ) and (workplacename Not like '%판매%' ) and (workplacename Not like '%불량%' ) and (workplacename Not like '%분실%' ) and (workplacename Not like '%누락%' )  and (workplacename Not like '%추가%' ) " . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( (workday between date('$fromdate') and date('$Transtodate') ) and (filename1 is null  or filename2 is null or doneday is null )  and (workplacename Not like '%판매%' ) and (workplacename Not like '%불량%' ) and (workplacename Not like '%분실%' ) and (workplacename Not like '%누락%' )  and (workplacename Not like '%추가%' ) )" . $orderby;				  		  		   
			     }    
	  
// bootstrap.php에서 이미 DB 연결됨	  		  
 
   $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $firstord_arr=array();
   $secondord_arr=array();
   $worker_arr=array();

   
   $wide_arr=array();
   $normal_arr=array();
   $narrow_arr=array();
   
   $beforepic_arr=array();
   $afterpic_arr=array();
   $etc_arr=array();

     
   $num_arr=array();  // 일괄처리를 위한 번호 저장


 try{  
 
   // $sql="select * from mirae8440.work"; 		 
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
			  $chargedmantel=$row["chargedmantel"];
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $workday=$row["workday"];
			  $doneday=$row["doneday"];  // 시공완료일
			  $workfeedate=$row["workfeedate"];  // 시공비지급일
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  
			  $filename1=$row["filename1"];
			  $filename2=$row["filename2"];
			  
			  if($filename1!=Null)
			       $filename1='등록';
					  
			  if($filename2!=Null)
			       $filename2='등록';
			  
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
		      if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
					else $doneday="";	
		      if($workfeedate!="0000-00-00" and $workfeedate!="1970-01-01" and $workfeedate!="")  $workfeedate = date("Y-m-d", strtotime( $workfeedate) );
					else $workfeedate="";	
		      if($recordDate!="0000-00-00" and $recordDate!="1970-01-01" and $recordDate!="")  $recordDate = date("Y-m-d", strtotime( $recordDate) );
					else $recordDate="";						
	   
		   $workday_arr[$counter]=$workday;
		   $doneday_arr[$counter]=$doneday;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;
		   $secondord_arr[$counter]=$secondord;   
		   $firstord_arr[$counter]=$firstord;   
		   $worker_arr[$counter]=$worker;   
		   $beforepic_arr[$counter]=$filename1;   
		   $afterpic_arr[$counter]=$filename2;   
		   $num_arr[$counter]=$num;   
		   
		   // 판매'란 단어 있으면 실측비 제외		   
		   $findstr = '판매';
		   $pos = stripos($workplacename, $findstr);			   
						   	   
		   $wide_arr[$counter] = 0;

		   $normal_arr[$counter] = 0;

		   $narrow_arr[$counter] = 0;

   				 $workitem="";
				 if($widejamb!="")   {
						$wide_arr[$counter] = (int)$widejamb;
													   
								   
									}
				 if($normaljamb!="")   {
						$normal_arr[$counter] = (int)$normaljamb;				
							 
							   					
						}
				 if($smalljamb!="") {
						$narrow_arr[$counter] = (int)$smalljamb;	
						}		   	    
		        
			   $counter++;	
         }
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

?>		 
<body >

<div class="container-fluid">

<div class="d-flex mt-2 mb-2">    
 <span class="badge bg-primary fs-5"> &nbsp; TKE 엑셀 양식 일괄등록 &nbsp; 		</span>&nbsp;&nbsp; 	&nbsp;&nbsp; 	
 <button  type="button" class="btn btn-secondary" id="savegridBtn"> 일괄등록 </button>	 &nbsp; &nbsp; &nbsp; 
 <button  type="button" class="btn btn-outline-secondary" onclick="self.close();"  > 창닫기 </button>	 &nbsp;
 
</div> 
  
   <div id="content" style="width:1850px;">			 
   <form name="regform" id="regform"  method="post" >  
   <div id="list_search" style="width:1800px;">   
		
    <div class="input-group p-2 mb-2">
		<span style="margin-left:20px;font-size:20px;color:brown;"> ※ TKE 해당셀을 긁어서 복사한 후 발주서 생성하는 방식입니다. </span>
       </div>
	 <div id="grid" style="width:1870px;">  
  </div>     
  
  <?php
  for($i=1;$i<=47;$i++)
     echo '<input id="col' . $i . '" name="col' . $i . '[]" type="hidden" >';
   ?>   
	 </form>
	 </div>   
   </div> 	   
  </div> <!-- end of container -->
  
   
<script>

$(document).ready(function(){

 var total_sum=0; 
 var count=0;  // 전체줄수 카운트 
  
 var rowNum = <?php echo json_encode($counter); ?> ; 
 
const COL_COUNT = 47;
const COL_NAMES = 47;
const column = Array.from({ length: COL_NAMES }, (_, i) => `col${i + 1}`);
const data = Array.from({length: COL_COUNT}, (_, i) => {
    const row = {name: i};
    column.forEach(col => row[col] = '');
    return row;
});

 const commonSettings = {
    sortingType: 'desc',
    sortable: true,
    editor: 'text',
    align: 'center',
};

const columnsConfig = [
    { header: 'guubn', name: 'col1', width: 60 },
    { header: 'pono', name: 'col2', width: 100 },
    { header: 'pono_org', name: 'col3', width: 150 },
    { header: 'po_seq', name: 'col4', width: 80 },
    { header: 'part_code', name: 'col5', width: 90 },
    { header: 'draw_no', name: 'col6', width: 90 },
    { header: 'part_name', name: 'col7', width: 300 },
    { header: 'unit', name: 'col8', width: 80 },
    { header: 'po_qty', name: 'col9', width: 60 },
    { header: 'in_qty', name: 'col10', width: 60 },
    { header: 'notin_qty', name: 'col11', width: 60 },
    { header: 'supp_up', name: 'col12', width: 60 },
    { header: 'supp_amt', name: 'col13', width: 100 },
    { header: 'jobno', name: 'col14', width: 100 },
    { header: 'job1_code', name: 'col15', width: 100 },
    { header: 'job2_code', name: 'col16', width: 100 },
    { header: 'proj_name', name: 'col17', width: 400 },
    { header: 'spec', name: 'col18', width: 200 },
    { header: 'vend_code', name: 'col19', width: 100 },
    { header: 'vend_name', name: 'col20', width: 150 },
    { header: 'emp_name', name: 'col21', width: 80 },    
    { header: 'col22', name: 'col22', width: 100 },
    { header: 'col23', name: 'col23', width: 100 },
    { header: 'col24', name: 'col24', width: 100 },
    { header: 'col25', name: 'col25', width: 100 },
    { header: 'col26', name: 'col26', width: 100 },
    { header: 'col27', name: 'col27', width: 100 },
    { header: 'col28', name: 'col28', width: 100 },
    { header: 'col29', name: 'col29', width: 100 },
    { header: 'col30', name: 'col30', width: 100 },
    { header: 'col31', name: 'col31', width: 100 },
    { header: 'col32', name: 'col32', width: 100 },
    { header: 'col33', name: 'col33', width: 100 },
    { header: 'col34', name: 'col34', width: 100 },
    { header: 'col35', name: 'col35', width: 100 },
    { header: 'col36', name: 'col36', width: 100 },
    { header: 'col37', name: 'col37', width: 100 },
    { header: 'col38', name: 'col38', width: 100 },
    { header: 'col39', name: 'col39', width: 100 },
    { header: 'col40', name: 'col40', width: 100 },
    { header: 'col41', name: 'col41', width: 100 },
    { header: 'col42', name: 'col42', width: 100 },
    { header: 'col43', name: 'col43', width: 100 },
    { header: 'col44', name: 'col44', width: 100 },
    { header: 'col45', name: 'col45', width: 100 },
    { header: 'col46', name: 'col46', width: 100 },
    { header: 'col47', name: 'col47', width: 100 }	
];


const columns = columnsConfig.map(config => ({ ...commonSettings, ...config }));

const grid = new tui.Grid({
    el: document.getElementById('grid'),
    data: data,
    bodyHeight: 700,
    columns,
    columnOptions: {
        resizable: true
    },
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

	
function savegrid() {
    let columns = {};
    for(let i = 1; i <= 47; i++) {
        columns[`col${i}`] = [];
    }
    
    const MAXcount = grid.getRowCount();
    for(let i = 0; i < MAXcount; i++) {
        for(let j = 1; j <= 47; j++) {
            let colName = `col${j}`;
            let value = grid.getValue(i, colName);
            if(value != null) {
                columns[colName].push(swapcommatopipe(value));
            }
        }
    }
    
    for(let i = 1; i <= 47; i++) {
        let colName = `col${i}`;
        $(`#${colName}`).val(columns[colName]);
    }

	 $.ajax({
			url: "uploadorder_tke.php",
			type: "post",		
			data: $("#regform").serialize(),
			dataType:"json",
			success : function( data ){
				console.log( data);						
				Swal.fire(
				  '처리되었습니다.',
				  '데이터가 성공적으로 등록되었습니다.',
				  'success'
				);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
				Swal.fire(
				  '오류 발생',
				  '데이터 등록 중 오류가 발생했습니다. 다시 시도해주세요.',
				  'error'
						)
				}
		   });
			
  setTimeout(function() { 
		   self.close();
			window.opener.location.reload();  // 부모창 새로고침
		   }, 2000);		
	
		
}	


$("#savegridBtn").click(function(){  savegrid();   });	  


});


function dis_text()
{  
		var dis_text = <?php echo json_encode($jamb_total ?? ''); ?>;
		$("#dis_text").val(dis_text);
}	


function swapcommatopipe(strtmp)
{
	let replaced_str = strtmp.replace(/,/g, '|');
	return replaced_str;	   
}



</script>

  </html>

</body>