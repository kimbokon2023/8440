<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

include includePath('load_header.php');
?>

<title>시공소장 시공비</title>
</head>

<body>

<?php require_once(includePath('myheader.php')); ?>   
<?php 
// 요청 변수 안전하게 초기화
$recordDate = $_REQUEST["recordDate"] ?? date("Y-m-d");
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$mode = $_REQUEST["mode"] ?? $_POST["mode"] ?? '';
$find = $_REQUEST["find"] ?? $_POST["find"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? $_POST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? $_POST["todate"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$cursort = $_REQUEST["cursort"] ?? '';
$sortof = $_REQUEST["sortof"] ?? '';
$stable = $_REQUEST["stable"] ?? '';
$search = $_REQUEST["search"] ?? '';

$sum = array();

// 기간 초기화
if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 7);
    $fromdate = $fromdate . "-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$orderby = " ORDER BY workday DESC";

$now = date("Y-m-d");   // 출고일기준으로 변경 24/06/05

// SQL 쿼리 생성
$sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;

if ($mode == "search") {
    if ($search == "") {
        $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;
    } elseif ($search != "") {
        $sql = "SELECT * FROM mirae8440.work WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
        $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN date('$fromdate') AND date('$Transtodate'))" . $orderby;
    }
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열 초기화
$counter = 0;
$workday_arr = array();
$workplacename_arr = array();
$firstord_arr = array();
$secondord_arr = array();
$worker_arr = array();
$workfeedate_arr = array();
$material_arr = array();
$demand_arr = array();
$visitfee_arr = array();
$totalfee_arr = array();

$wide_arr = array();
$normal_arr = array();
$narrow_arr = array();
$widefee_arr = array();
$normalfee_arr = array();
$narrowfee_arr = array();
$etc_arr = array();
$etcfee_arr = array();

$wideunit_arr = array();
$normalunit_arr = array();
$narrowunit_arr = array();
$etcunit_arr = array();

$add_arr = array();   // 추가로 재료분리대/ 쫄대등 들어가는 현장
$num_arr = array();   // 일괄처리를 위한 번호 저장

// 유효한 날짜 포맷 함수
function formatValidDate($date) {
    return ($date != "0000-00-00" && $date != "1970-01-01" && $date != "") ? date("Y-m-d", strtotime($date)) : "";
}	   

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';

        $searchitem = $workplacename . $memo . $attachment;

        // 날짜 포맷 처리
        $orderday = formatValidDate($orderday);
        $measureday = formatValidDate($measureday);
        $drawday = formatValidDate($drawday);
        $deadline = formatValidDate($deadline);
        $workday = formatValidDate($workday);
        $endworkday = formatValidDate($endworkday);
        $demand = formatValidDate($demand);
        $startday = formatValidDate($startday);
        $testday = formatValidDate($testday);
        $doneday = formatValidDate($doneday);
        $workfeedate = formatValidDate($workfeedate);
        $recordDate = formatValidDate($recordDate);

        // 배열에 데이터 저장
        $doneday_arr[$counter] = $doneday;
        $workfeedate_arr[$counter] = $workfeedate;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $secondord_arr[$counter] = $secondord;
        $firstord_arr[$counter] = $firstord;
        $worker_arr[$counter] = $worker;
        $demand_arr[$counter] = $demand;
        $num_arr[$counter] = $num;   
		   
		   // 판매'란 단어 있으면 실측비 제외		   
		   // $findstr = '판매';
		   $findstr = '떨미';
		   $pos = stripos($workplacename, $findstr);			   
		   
		if( trim($secondord) =='우성스틸' or trim($secondord) == '한산' or trim($secondord) == '대오정공' or $pos>0 )
			$visitfee_arr[$counter]= 0;
		    else
				$visitfee_arr[$counter]= 100000 ;				 

			  $materials="";
			  $materials=$material2 . " " . $material1 . " " . $material3 . $material4 . $material5 . $material6;		
		   
		   $material_arr[$counter]=$materials;   		   
							   	   
		   $wide_arr[$counter] = 0;
		   $widefee_arr[$counter] = 0;
		   $normal_arr[$counter] = 0;
		   $normalfee_arr[$counter] = 0;
		   $narrow_arr[$counter] = 0;
		   $narrowfee_arr[$counter] = 0;
		   $etc_arr[$counter] = 0;
		   $etcfee_arr[$counter] = 0;
		   
		   $wideunit_arr[$counter] = 0;
		   $normalunit_arr[$counter] = 0;
		   $narrowunit_arr[$counter] = 0;
		   $etcunit_arr[$counter] = 0;		

			// 재료분리대
			$add_arr[$counter]=	$gapcover;
   
   				 $workitem="";
				 if($widejamb!="")   {
					   $wide_arr[$counter] = (int)$widejamb;
								  
					   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
					   // $findstr = '불량';
					   $findstr = '떨뿔';
					   $findstr2 = '떨뿔';
					   $pos = stripos($workplacename, $findstr);							   
					   //판매란 단어가 들어가 있는 수량은 제외한다.		   
					   // $findstr2 = '판매';
					   $pos2 = stripos($workplacename, $findstr2);
					   if($pos==0 and $pos2==0)
							 if((int)$widejambworkfee > 0)
								$wideunit_arr[$counter] = conv_num($widejambworkfee) ;  
								else if( trim($secondord) =='우성스틸' and (trim(strtoupper($firstord)) =='OTIS' or trim($firstord)=='오티스') )
										 $wideunit_arr[$counter] = 100000;  
										   else
											  $wideunit_arr[$counter] = 80000;  													  

								if (strpos($workplacename, '판매') !== false || strpos($workplacename, '불량') !== false) {
									// 판매 불량 단어가 있으면
									$widefee_arr[$counter]= 0; 
								} else {										
									$widefee_arr[$counter]= (int)$widejamb * $wideunit_arr[$counter];  
								}												  
																   
								}
				 if($normaljamb!="")   {
						$normal_arr[$counter] = (int)$normaljamb;				
							 
					   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
						// $findstr = '불량';
					   $findstr = '떨뿔';
					   $findstr2 = '떨뿔';
					   $pos = stripos($workplacename, $findstr);							   
					   //판매란 단어가 들어가 있는 수량은 제외한다.		   							   
					   $pos2 = stripos($workplacename, $findstr2);
					   if($pos==0 and $pos2==0)
								if((int)$normaljambworkfee > 0)
									 $normalunit_arr[$counter] = conv_num($normaljambworkfee) ;  								   
									else
										$normalunit_arr[$counter] = 70000 ;								   
					   
								if (strpos($workplacename, '판매') !== false || strpos($workplacename, '불량') !== false) {
									// 판매 불량 단어가 있으면
									$normalfee_arr[$counter]=  0;  
								} else {										
									$normalfee_arr[$counter]=  (int)$normaljamb * $normalunit_arr[$counter];  
								}											
			}
				
				 if($smalljamb!="") {
						$narrow_arr[$counter] = (int)$smalljamb;	
														 
					   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
					   // $findstr = '불량';
					   $findstr = '떨뿔';
					   $findstr2 = '떨뿔';
					   $pos = stripos($workplacename, $findstr);							   
					   //판매란 단어가 들어가 있는 수량은 제외한다.		   							   
					   $pos2 = stripos($workplacename, $findstr2);
					   if($pos==0 and $pos2==0)
							if((int)$smalljambworkfee > 0)
								 $narrowunit_arr[$counter] = conv_num($smalljambworkfee) ; 	
							 else
								$narrowunit_arr[$counter] = 20000 ;	

					   
								if (strpos($workplacename, '판매') !== false || strpos($workplacename, '불량') !== false) {
									// 판매 불량 단어가 있으면
									$narrowfee_arr[$counter] =  0;  
								} else {												
									$narrowfee_arr[$counter] = (int)$smalljamb * $narrowunit_arr[$counter];  	
								}	
																	
						}		   	   

        $totalfee_arr[$counter] = $widefee_arr[$counter] + $normalfee_arr[$counter] + $narrowfee_arr[$counter] + $etcfee_arr[$counter];
        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>

<form name="board_form" id="board_form" method="post" action="workfee.php?mode=search&year=<?= $year ?>&search=<?= $search ?>&process=<?= $process ?>&asprocess=<?= $asprocess ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&up_fromdate=<?= $up_fromdate ?>&up_todate=<?= $up_todate ?>&separate_date=<?= $separate_date ?>&view_table=<?= $view_table ?>">

<div class="container-fluid">
    <div class="card mt-3 mb-5">    
	
	<div class="card-header">    
	<span class="fs-6 badge bg-danger" > 출고일 기준자료</span> &nbsp; 	   
	 판매,불량자료도 나옴 &nbsp; 	
	 <button  type="button" class="btn btn-secondary  btn-sm" id="refresh"> <i class="bi bi-arrow-clockwise"></i>  </button>	 &nbsp;			 
	 <button  type="button" class="btn btn-secondary  btn-sm" id="downloadcsvBtn"> CSV 엑셀 다운로드 </button>	 &nbsp;
	 <button  type="button" class="btn btn-secondary  btn-sm" id="downloadlistBtn" onclick="javascript:move_url('../work/excelform.php?fromdate=<?=$fromdate?>&todate=<?=$todate?>&search=<?=$search?>')"> 소장별 거래명세표(엑셀)</button>&nbsp;
	  
	 <span style="margin-left:70px;color:grey;"> 작업소장 :  </span>
	    <button type="button" class="btn btn-dark  btn-sm" onclick="List_name('')"> ALL </button> 				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('추영덕')"> 추영덕 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('이만희')"> 이만희 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('김운호')"> 김운호 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('김상훈')"> 김상훈 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('유영')"> 유영 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('손상민')"> 손상민 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('조장우')"> 조장우 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('박철우')"> 박철우 </button> 	   
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('이인종')"> 이인종 </button> 	   	
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('이춘일')"> 이춘일 </button> 	   	
	</div> 
   <div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 		 
  
   <span style="color:blue;"> 시공비 청구일자 일괄처리 </span>	&nbsp;	
	 <input type="date" id="recordDate" name="recordDate"  class="form-control fs-6" style="width:150px;"  value="<?=$recordDate?>" placeholder=""> 	 
	 &nbsp;	&nbsp;	선택체크		&nbsp;	
	 <button type="button" id="saveBtn"  class="btn btn-secondary btn-sm"> 일괄적용&저장 </button>	&nbsp;		
	 <button type="button" id="clearBtn"  class="btn btn-outline-danger btn-sm"> 선택 Clear </button>	&nbsp;		

		<span class='input-group-text align-items-center' >
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
		<div class="card-footer">					
		</div>
		</div>

		<input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
		<input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">  &nbsp;     </span> 
		&nbsp;&nbsp;		   		

		&nbsp;
		<input type="text" name="search" id="search" value="<?=$search?>" class="form-control" style="width:150px;" onkeydown="JavaScript:SearchEnter();" placeholder="검색어"> 
		<button type="button" id="searchBtn" class="btn btn-dark  btn-sm mx-1"> <i class="bi bi-search"></i> 검색  </button>	&nbsp;&nbsp;											

		<span style="margin-left:20px;font-size:15px;color:blue;"> ※실측비는 1,2,3차 등 차수 및 여건에 따라 달라질 수 있음 </span>       

	 </div>        
	 
	 <div id="grid" style="width:1880px;">  
	 </div>     
	 
	 </div>   
   </div> 	   
   
 </form>
  
  <form id="Form1" name="Form1">
    <input type="hidden" id="num_arr" name="num_arr[]" >
    <input type="hidden" id="recordDate_arr" name="recordDate_arr[]">
  </form>  
  
<script>

document.addEventListener('DOMContentLoaded', function() {
    var showdate = document.getElementById('showdate');
    var showframe = document.getElementById('showframe');
    var hideTimeout; // 프레임을 숨기기 위한 타이머 변수

    showdate.addEventListener('mouseenter', function(event) {
        clearTimeout(hideTimeout);  // 이미 설정된 타이머가 있다면 취소
        showframe.style.top = (showdate.offsetTop + showdate.offsetHeight) + 'px';
        showframe.style.left = showdate.offsetLeft + 'px';
        showframe.style.display = 'block';
    });

    showdate.addEventListener('mouseleave', startHideTimer);

    showframe.addEventListener('mouseenter', function() {
        clearTimeout(hideTimeout);  // 이미 설정된 타이머가 있다면 취소
    });

    showframe.addEventListener('mouseleave', startHideTimer);

    function startHideTimer() {
        hideTimeout = setTimeout(function() {
            showframe.style.display = 'none';
        }, 300);  // 300ms 후에 프레임을 숨깁니다.
    }
});



$(document).ready(function(){
	
$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });
	
$("#popupwindow").click(function(){  

	var link = 'popupworkfee.php';
	window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1920,height=1000");

});		
	
 var arr1 = <?php echo json_encode($doneday_arr);?> ;
 var arr2 = <?php echo json_encode($workfeedate_arr);?> ;  
 var arr3 = <?php echo json_encode($worker_arr);?> ; 
 var arr4 = <?php echo json_encode($workplacename_arr);?> ;
 var arr5 = <?php echo json_encode($material_arr);?> ;
 var arr6 = <?php echo json_encode($firstord_arr);?> ;  
 var arr7 = <?php echo json_encode($secondord_arr);?> ; 
 var arr8 = <?php echo json_encode($demand_arr);?> ;
 var arr9 = <?php echo json_encode($visitfee_arr);?> ;
 var arr10= <?php echo json_encode($wide_arr);?> ;
 var arr11= <?php echo json_encode($wideunit_arr);?> ;
 var arr12= <?php echo json_encode($widefee_arr);?> ;
 var arr13= <?php echo json_encode($normal_arr);?> ;
 var arr14= <?php echo json_encode($normalunit_arr);?> ;
 var arr15= <?php echo json_encode($normalfee_arr);?> ;
 var arr16= <?php echo json_encode($narrow_arr);?> ;
 var arr17= <?php echo json_encode($narrowunit_arr);?> ;
 var arr18= <?php echo json_encode($narrowfee_arr);?> ;
 var arr19 = <?php echo json_encode($etc_arr);?> ;
 var arr20 = <?php echo json_encode($etcfee_arr);?> ;
 var arr21 = <?php echo json_encode($totalfee_arr);?> ;
 var arr22 = <?php echo json_encode($num_arr);?> ;
 var arr23 = <?php echo json_encode($add_arr);?> ;  // 재료분리대 여부 검색해서 알려줌 추가부분
 
var num = <?php echo json_encode($num_arr);?> ;
var numcopy = new Array(); ;
 
 var total_sum=0; 
 var count=0;  // 전체줄수 카운트 
  
 var rowNum = "<? echo $counter; ?>" ; 
 
 const data = [];
 const columns = [];	
 const COL_COUNT = 22;

 for(i=0;i<rowNum;i++) {
			 total_sum = total_sum + Number(uncomma(arr21[i]));
		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr3[i] ;						 						
				row[`col4`] = arr4[i] ;						 											 						
				row[`col5`] = arr5[i] ;						 											 						
				row[`col6`] = arr6[i] ;						 											 						
				row[`col7`] = arr7[i] ;						 											 						
				row[`col8`] = arr8[i] ;						 											 						
				row[`col9`] =  (arr9[i] == 0) ? "" : comma(arr9[i]);						 						
				row[`col10`] = (arr10[i] == 0) ? "" : comma(arr10[i]);					 						
				row[`col11`] = (arr11[i] == 0) ? "" : comma(arr11[i]);							 						
				row[`col12`] = (arr12[i] == 0) ? "" : comma(arr12[i]);							 						
				row[`col13`] = (arr13[i] == 0) ? "" : comma(arr13[i]);							 						
				row[`col14`] = (arr14[i] == 0) ? "" : comma(arr14[i]);							 						
				row[`col15`] = (arr15[i] == 0) ? "" : comma(arr15[i]);							 						
				row[`col16`] = (arr16[i] == 0) ? "" : comma(arr16[i]);							 						
				row[`col17`] = (arr17[i] == 0) ? "" : comma(arr17[i]);							 						
				row[`col18`] = (arr18[i] == 0) ? "" : comma(arr18[i]);							 						
				row[`col19`] = (arr19[i] == 0) ? "" : comma(arr19[i]);							 						
				row[`col20`] = (arr20[i] == 0) ? "" : comma(arr20[i]);							 						
				row[`col21`] = (arr21[i] == 0) ? "" : comma(arr21[i]);							 						
				row[`col22`] = arr22[i] ;						 											 						
				row[`col23`] = arr23[i] ;						 											 						
						}
				data.push(row); 	 
                numcopy[count] = num[i] ; 			 
			    count++;					
 }
 
// 마지막에 한줄 추가해서 합계내역 넣음
i++;		
row = { name: i };		 
	 for (let k = 0; k < COL_COUNT; k++ ) {				
			row[`col1`] = '' ;						 						
			row[`col2`] = '' ;						 						
			row[`col3`] = '' ;						 						
			row[`col4`] = '' ;						 						
			row[`col5`] = '' ;
			row[`col6`] = '' ;						 						
			row[`col7`] = '' ;						 						
			row[`col8`] = '' ;
			row[`col9`] = '' ;					 						
			row[`col10`] ='' ;
			row[`col11`] ='' ;
			row[`col12`] ='' ;
			row[`col13`] ='' ;
			row[`col14`] ='' ;
			row[`col15`] ='' ;
			row[`col16`] ='' ;
			row[`col17`] ='' ;
			row[`col18`] ='' ;
			row[`col19`] ='' ;
			row[`col20`] = '합계';
			row[`col21`] = comma(total_sum)  ;	
			row[`col22`] ='' ;				
		}
		 data.push(row); 	
		 numcopy[count] = 0 ;
		 count++;	

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
	  bodyHeight: 1000,					  					
	  columns: [ 				   
		{
		  header: '시공완료',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,	
		  align: 'center'
		},				   
		{
		  header: '시공청구',
		  name: 'col2',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,		
		  align: 'center'
		},			
		{
		  header: '시공소장',
		  name: 'col3',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:60, 		
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col4',
		  width:250,	
		  align: 'left'
		},
		{
		  header: '재질',
		  name: 'col5',
		  width:150,	
		  align: 'left'
		},
		{
		  header: '원청',
		  name: 'col6',
		  width: 80,		
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col7',
		  width: 80,	
		  align: 'center'
		},

		{
		  header: '발주처 청구',
		  name: 'col8',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:80, 		
		  align: 'center'
		},
		{
		  header: '실측비',
		  name: 'col9',
		  width:55, 		
		  align: 'center'
		},

		{
		  header: '쫄대/재분',
		  name: 'col23',
		  width:80,	
		  align: 'center'
		},				
			
		{
		  header: '막판',
		  name: 'col10',
		  width:30, 		
		  align: 'center'
		},		
		{
		  header: '단가',
		  name: 'col11',
		  width:60, 		
		  align: 'right'
		},		
		{
		  header: '금액',
		  name: 'col12',
		  width:70,	
		  align: 'right'
		},			
		{
		  header: '멍텅',
		  name: 'col13',
		  width:25,	 		
		  align: 'center'
		},			
		{
		  header: '단가',
		  name: 'col14',
		  width:60,	 		
		  align: 'right'
		},				
		{
		  header: '금액',
		  name: 'col15',
		  width:70,	 		
		  align: 'right'
		},			
		{
		  header: '쪽쟘',
		  name: 'col16',
		  width:30,	 		
		  align: 'center'
		},			
		{
		  header: '단가',
		  name: 'col17',
		  width:50,	 		
		  align: 'right'
		},				
		{
		  header: '금액',
		  name: 'col18',
		  width:70,	 		
		  align: 'right'
		},			
		{
		  header: '기타',
		  name: 'col19',
		  width:30,	 		
		  align: 'center'
		},		
		{
		  header: '금액',
		  name: 'col20',
		  width:70,	 		
		  align: 'right'
		},					
		{
		  header: '청구비합',
		  name: 'col21',
		  width:80,	 		
		  align: 'right'
		},					
		{
		  header: 'N',
		  name: 'col22',
		  width:5,	 		
		  align: 'right'
		}			
	  ],
	columnOptions: {
			resizable: true
		  },
	rowHeaders: ['rowNum','checkbox'],   // checkbox 형성
	  
	});
	
grid.hideColumn('col22');  // 숨기기
	
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
		  grid.setValue(e, 'col2',$("#recordDate").val())  // ; appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
		  //console.log($("#recordDate").val());
		  //console.log(e);
		});			
	   savegrid();
	});		
	
	$("#clearBtn").click(function(){  		
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col2','')  
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
				num_arr.push(grid.getValue(i, 'col22'));
				recordDate_arr.push(grid.getValue(i, 'col2'));
			 }	
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
        // console.log(num_arr);	
        // console.log(recordDate_arr);	
        // console.log($("#Form1").serialize());	
	    $.ajax({
			url: "SaveDemand.php",
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
   

grid.on('dblclick', (e) => {
    var base_url = '<?php echo $base_url; ?>';
    var link = base_url + '/work/view.php?menu=no&num=' + numcopy[e.rowKey];
    
    if (numcopy[e.rowKey] > 0) {
        popupCenter(link, "jamb 수주내역", 1800, 920);
    }
    
    console.log(e.rowKey);
});	   
   
$("#refresh").click(function(){  location.reload();   });	          // refresh     

$("#downloadcsvBtn").click(function(){  Do_gridexport();   });	          // CSV파일 클릭	
  
//////////////////// saveCSV	
Do_gridexport = function () { 	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '시공완료일 ,' ;
			   row += '시공비지급 ,' ;
			   row += '시공소장 ,' ;			   
			   row += '현장명 ,' ;
			   row += '원청 ,' ;
			   row += '발주처 ,' ;
			   row += '재질 ,' ;
			   row += '청구여부 ,' ;
			   row += '실측비 ,' ;
			   row += '막판 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '막판무 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '쪽쟘 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '기타 ,' ;
			   row += '금액 ,' ;			   
			   row += '청구합 ' ;
			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			const COLNUM = 21;   
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
			link.setAttribute("download", "시공소장 시공비내역.csv");
			document.body.appendChild(link); 
			link.click();
			
			}    //csv 파일 export				
	
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
		var dis_text = '<?php echo $jamb_total; ?>';
		$("#dis_text").val(dis_text);
}	

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

function List_name(worker)
{	
		var worker; 				
		var name='<?php echo $user_name; ?>' ;
		 
			$("#search").val(worker);	
			$('#board_form').submit();		// 검색버튼 효과
}

function move_url(href)
{
	 var  search = "<? echo $search;  ?>" ; 
	 if(search!='')
        document.location.href = href;		 
	   else
		  alert('소장을 선택해 주세요');   
	   
}

</script>

<script>
	$(document).ready(function(){
		saveLogData('Jamb 시공소장 시공비 결산'); // 다른 페이지에 맞는 menuName을 전달
	});
</script>

</html>
</body>