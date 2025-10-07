<?php
session_start();

$root_dir = $_SERVER['DOCUMENT_ROOT'] ;

$level= $_SESSION["level"];
$user_name= $_SESSION["name"];

  if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          		 
		 sleep(1);
		  header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }  
  
// ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//header("Refresh:0");  // reload refresh   
							                	
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	

 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>

<link rel="stylesheet" type="text/css" href="../css/steel.css?v=1"> 

<script src="http://8440.co.kr/js/date.js"></script>  <!-- 기간을 설정하는 관련 js 포함 -->
<script src="http://8440.co.kr/common.js"></script>  <!-- 기간을 설정하는 관련 js 포함 -->

<!-- 최초화면에서 보여주는 상단메뉴 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<link  rel="stylesheet" type="text/css" href="../css/common.css?v=1">

<title> 미래기업 원자재(철판) 관리 </title> 
</head>
 
<body >


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">원자재 현황 부족(마이너스) 상태 알림</h4>
        </div>
        <div class="modal-body">		
           <div class="row gx-4 gx-lg-4 align-items-center">		  
				   <br>
				   <div id="alertmsg" class="fs-3" > </div> <br>
				  <br>		  									
				</div>
			</div>		  
        <div class="modal-footer">
          <button id="closeModalBtn" type="button" class="btn btn-default btn-sm " data-dismiss="modal">닫기</button>
        </div>
		</div>
		</div>
      </div>      
   
<? include '../myheader.php'; ?>   

<style>
  .text-center {
    display: flex;
    justify-content: center;
  }
</style>

 <?php
 
include "_request.php";
     
 // 철판종류에 대한 추출부분
 $sql = "select * from mirae8440.steelsource order by sortorder asc, item desc, spec asc ";

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    $steelsource = array();
    $pass = '0';

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $company = trim($row["take"]);

        // 일반매입처리
        if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
            $company = '';
        }

        array_push($steelsource, trim($row["item"]) . '|' . trim($row["spec"]) . '|' . trim($company));
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$steelsource_item = $steelsource;
array_push($steelsource, "VB Ecolor Black|1.2*1000*4000|");
array_push($steelsource, "VB Ecolor Black|1.2*1000*4000|");
array_push($steelsource, "VB Ecolor Black|1.2*1000*4000|");
array_push($steelsource, "VB Ecolor Black|1.2*1000*4000|");
array_push($steelsource, "VB Ecolor Black|1.2*1000*4000|");
array_push($steelsource, "VB Ecolor Black|1.2*1000*4000|");
array_push($steelsource, "VB Ecolor Black|1.2*1000*4000|");
$test = array_unique($steelsource);

// print_r ($test);

$sum_title = array();
$sum = array();
$yday_sum = array();
$ydaysaved_sum = array();

$title_arr = array();
$titleurl_arr = array();
$num_arr = array();
$full_arr = array();

$yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 86400);
$week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");

if ($week[date('w', strtotime($yesterday))] == '(일)') {
    $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 172800);
}

$sql = "select * from mirae8440.steel where outdate between date('$yesterday') and date('$yesterday') order by outdate";
$tmpsum = 0;

try {
    $stmh = $pdo->query($sql);

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $outdate = $row["outdate"];
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $comment = $row["comment"];
        $which = $row["which"];

        $company = trim($row["company"]);

        // 일반매입처리
        if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
            $company = '';
        }

        $tmp = $item . $spec . $company;
        $tmpsum += (int)$steelnum;
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

if ($tmpsum < 1) {
    $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 259200);
}

$sql = "SELECT * FROM mirae8440.steel";

for ($i = 0; $i < count($steelsource_item); $i++) {
    $sum[$i] = 0;
    $num_arr[$i] = 0;
    $full_arr[$i] = "";
}

try {
    $stmh = $pdo->query($sql);

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $outdate = $row["outdate"];
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $comment = $row["comment"];
        $which = $row["which"];

        $temp_arr = explode("*", $spec);
        $saved_weight = calculate_saved_weight($row, $temp_arr[0]);
        $saved_fee = calculate_saved_fee($item, $saved_weight, $readIni);

        $company = trim($row["company"]);

        // 일반매입처리
        if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
            $company = '';
        }

        $tmp = trim($item) . '|' . trim($spec) . '|' . trim($company);

        for ($i = 0; $i < count($steelsource_item); $i++) {
            if ($which == '1' && $tmp == $steelsource_item[$i]) {
                $sum_title[$i] = $steelsource_item[$i];
                $titleurl_arr[$i] = rawurlencode($steelsource_item[$i]);
                $sum[$i] = $sum[$i] + intval($steelnum);
                $num_arr[$i]++;
                $full_arr[$i] = $full_arr[$i] . ',' . $outdate . ' ' . $num . ' ' . $which . ' ' . $item . ' ' . $spec . ' ' . $steelnum;
            }
            if ($which == '2' && $tmp == $steelsource_item[$i]) {
                $sum_title[$i] = $steelsource_item[$i];
                $titleurl_arr[$i] = rawurlencode($steelsource_item[$i]);
                $sum[$i] = $sum[$i] - intval($steelnum);
                $num_arr[$i]++;
                $full_arr[$i] = $full_arr[$i] . ',' . $outdate . ' ' . $num . ' ' . $which . ' ' . $item . ' ' . $spec . ' ' . $steelnum;
            }
            if ($which == '2' && $tmp == $steelsource_item[$i] && $outdate == $yesterday) {
                $yday_sum[$i] += (int)$steelnum;
                $ydaysaved_sum[$i] += $saved_fee;
            }
        }
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

function calculate_saved_weight($row, $thickness)
{
    $saved_weight = 0.0;

    for ($i = 1; $i <= 5; $i++) {
        $saved_weight += ($thickness * $row["used_width_$i"] * $row["used_length_$i"] * 7.93 * (int)$row["used_num_$i"]) / 1000000;
    }

    return sprintf('%0.1f', $saved_weight);
}

function calculate_saved_fee($item, $saved_weight, $readIni)
{
    switch ($item) {
        case 'CR':
            $saved_fee = $saved_weight * conv_num($readIni['CR']);
            break;
        case 'PO':
            $saved_fee = $saved_weight * conv_num($readIni['PO']);
            break;
        case 'EGI':
            $saved_fee = $saved_weight * conv_num($readIni['EGI']);
            break;
        case '304 HL':
            $saved_fee = $saved_weight * conv_num($readIni['HL304']);
            break;
        case '201 HL':
            $saved_fee = $saved_weight * conv_num($readIni['HL201']);
            break;
        case '201 2B MR':
        case '201 MR':
            $saved_fee = $saved_weight * conv_num($readIni['MR201']);
            break;
        case '304 MR':
        case 'V/B':
            $saved_fee = $saved_weight * conv_num($readIni['MR304']);
            break;
        default:
            $saved_fee = $saved_weight * conv_num($readIni['etcsteel']);
            break;
    }

    return $saved_fee;
}



?>   
   
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">원자재 현황 부족(마이너스) 상태 알림</h4>
        </div>
        <div class="modal-body">		
           <div class="row gx-4 gx-lg-4 align-items-center">		  
				   <br>
				   <div id="alertmsg" class="fs-3" > </div> <br>
				  <br>		  									
				</div>
			</div>		  
        <div class="modal-footer">
          <button id="closeModalBtn" type="button" class="btn btn-default btn-sm " data-dismiss="modal">닫기</button>
        </div>
		</div>
		</div>
      </div>      
   
   
<!-- menu -->

 <div class="container-fluid" >
  <form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">  
   

		<input type="hidden" id="username" name="username" value="<?=$user_name?>" size="5" > 					
		<input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?=$BigsearchTag?>" size="5" > 					
		
		<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
		<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
		<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
		<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
		<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
		<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
		<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
		<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
		<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
		<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
		<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
		<input type="hidden" id="list" name="list" value="<?=$list?>" > 				
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" > 	
		
	                <div id="vacancy" style="display:none">  </div>
</div>
	 <div class="container-fluid" >					
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-4 text-center" style="display:none; white-space: normal;  height: 100px; "> 
		</div>     
	</div>	
	</div>	
 <div class="container-fluid" >						
	<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 
		 <span class="fs-5">  원자재(철판) 자산 &nbsp; <div id=up_display2>   </div>  </span> &nbsp; &nbsp; &nbsp;		 
		 <button  type="button" id="showmaterialfeeBtn" class="btn btn-secondary btn-sm" > 가격/종류/사이즈 설정 </button> &nbsp;	 
		 <button  type="button" id="displayinput" class="btn btn-secondary btn-sm"  > 기간별 수불현황 </button> &nbsp;	 
		 <button  type="button" id="displaygraph" class="btn btn-secondary btn-sm"  > 출고그래프 </button> &nbsp;	 
	</div>	

	<div class="d-flex mb-1 justify-content-center align-items-center"> 
	
		<div class="input-group p-1 mb-1  justify-content-center">											
		<span class="input-group-text bg-secondary "> <i class="bi bi-layout-text-window"></i> </span>
		<span class="input-group-text text-white  bg-secondary ">PO </span>						  					   
		<input  type="text" id="PO" style="color:grey;text-align:right;" name="PO" size="6" value="<?=$readIni['PO']?>"  onkeyup="inputNumberFormat(this)"> 
		<span class="input-group-text text-white  bg-secondary ">CR </span>						  					   
		<input  type="text" id="CR" style="color:black;text-align:right;" name="CR" size="6" value="<?=$readIni['CR']?>" onkeyup="inputNumberFormat(this)"> 
		<span class="input-group-text text-white  bg-secondary ">EGI </span>											
		<input  type="text" id="EGI" style="color:brown;text-align:right;" name="EGI" size="6" value="<?=$readIni['EGI']?>" onkeyup="inputNumberFormat(this)"> 
		</div>		
		</div>		
	<div class="d-flex mb-1 justify-content-center align-items-center"> 
		<div class="input-group p-1 mb-1  justify-content-center">																					
		 <span class="input-group-text  text-white bg-secondary "> 201 HL </span>						  					   
		<input  type="text" id="HL201" style="color:purple;text-align:right;" name="HL201" size="6" value="<?=$readIni['HL201']?>" onkeyup="inputNumberFormat(this)"> 
		<span class="input-group-text  text-white bg-secondary "> 201 MR </span>						  					   
		<input  type="text" id="MR201" style="color:blue;text-align:right;" name="MR201" size="6" value="<?=$readIni['MR201']?>" onkeyup="inputNumberFormat(this)"> 						
		
		 <span class="input-group-text  text-white bg-secondary "> 304 HL </span>						  					   
		<input  type="text" id="HL304" style="color:purple;text-align:right;" name="HL304" size="6" value="<?=$readIni['HL304']?>" onkeyup="inputNumberFormat(this)"> 
		<span class="input-group-text  text-white bg-secondary "> 304 MR </span>						  					   
		<input  type="text" id="MR304" style="color:blue;text-align:right;" name="MR304" size="6" value="<?=$readIni['MR304']?>" onkeyup="inputNumberFormat(this)"> 						
			
		
		<span class="input-group-text  text-white bg-secondary "> 특수소재평균값 </span>						  					   
		<input  type="text" id="etcsteel" style="color:red;text-align:right;" name="etcsteel" size="6" value="<?=$readIni['etcsteel']?>" onkeyup="inputNumberFormat(this)"> 																					 													
		</div> 
	</div>
  
	    
	<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 
  
		 <div id="grid" class="board"  >		  
		  
		  </div>

		</div>	


	
	</form>
 </div>
 
  
 <br>
 
 
</div>		

</body>
  </html>


<script>
$(document).ready(function(){	

// 원자재 가격테이블 클릭시
$("#showmaterialfeeBtn").click(function(){ 
        
	 popupCenter('./settings.php'  , '원자재현황보기', 800, 500);	
});

// 기간변수불현황
$("#displayinput").click(function(){         
	 popupCenter('../steel/list_materialinout.php'  , '기간별수불현황 보기', 1800, 900);	
});

// 기간변수불현황
$("#displaygraph").click(function(){         
	 popupCenter('../steel/list_statistics.php'  , '출고통계 보기', 1800, 900);	
});

$("#closeModalBtn").click(function(){ 
	$('#myModal').modal('hide');
});

				
			  $('a').children().css('textDecoration','none');  // a tag 전체 밑줄없앰.	
			  $('a').parent().css('textDecoration','none');


			$("input:radio[name=separate_date]").click(function() { 
					process_list(); 
			});
				

				 var numcopy = new Array(); 
				 var full_arr =  <?php echo json_encode($full_arr); ?> ;	 
				 var titleurl_arr =  <?php echo json_encode($titleurl_arr); ?> ;	 
				 var jsonData  = <?php echo json_encode($steelsource_item);?> ;
				 
				 var arr4 = <?php echo json_encode($sum_title);?> ;
				 var arr5 = <?php echo json_encode($sum);?> ;
				 var arr7 = <?php echo json_encode($num_arr);?> ;
				 var yday_sum = <?php echo json_encode($yday_sum);?> ;   // 전날 사용자재
				 var ydaysaved_sum = <?php echo json_encode($ydaysaved_sum);?> ;   // 전날 절약자재
				 var tmp;
				 var weight;
				 var price;
				 var total_sum = 0;
				 var yesterday = 0;
				 var yesterdaysaved = 0;
				 var check_minus= 0;
				 var check_str=""; 
				 
				 var splitData = [];

					// 각 문자열을 '|'로 분할하여 새로운 배열을 만듭니다.
					let str ;					
							
					// 2차원 배열을 1차원 배열로 변환합니다.
					let arr1 = [];
					let arr2 = [];
					let arr3 = [];
											
						var keys = Object.keys(jsonData);
						for (var i = 0; i < keys.length; i++) {
						  var key = keys[i];
						  var value = jsonData[key];
						  var arrvalue = value.split('|');		

                         // console.log(keys);
                         // console.log(keys.length);
                         // console.log(arrvalue[0]);
                         // console.log(arrvalue[1]);
                         // console.log(arrvalue[2]);
						  
						  arr1.push(arrvalue[0]);
						  arr2.push(arrvalue[1]);
						  arr3.push(arrvalue[2]);						  
						}					
										
				  
				 var rowNum = arr1.length ;   // sum_title의 길이
				 var count=0;
				 
				 let counter = 0;	 // numcopy 변수적용

				const COL_COUNT = 10;

				const data = [];
				const columns = [];
				
				let lastsavedData = '';
				 
				 for(i=0;i<rowNum;i++) {
					 var strArr=arr2[i].split('*');   // 규격 3가지로 구분
					 if(arr1[i]=='CR'|| arr1[i]=='PO' || arr1[i]=='EGI' || arr1[i]=='HTM')
							weight=Math.floor(7.85 * Number(strArr[0]) * Number(strArr[1]) * Number(strArr[2]) / 1000000);
					 if(arr1[i]=='304 HL'|| arr1[i]=='304 MR' || arr1[i]=='VB'  || arr1[i]=='MR VB TI-BRONZE' || arr1[i]=='BEAD BRONZE'  || arr1[i]=='3S BLACK V/B'  || arr1[i]=='BEAD GOLD'  || arr1[i]=='BEAD BLACK'  || arr1[i]=='V/B'  || arr1[i]=='201 2B MR'  || arr1[i]=='2B VB'  || arr1[i]=='MR BRONZE'  || arr1[i]=='MR VB')     
							weight=Math.floor(7.93 * Number(strArr[0]) * Number(strArr[1]) * Number(strArr[2])  / 1000000) ;		
					 if(arr1[i]=='AL')
							weight=Math.floor(2.56 * Number(strArr[0]) * Number(strArr[1]) * Number(strArr[2]) / 1000000) ;		
				  price=0;
				  
				 if(arr3[i]==='' || arr3[i]===null) { 
					  switch(arr1[i]) {
						 case 'CR' :
							price = uncomma($('#CR').val());		
							break;
						 case 'PO' :	 
							price = uncomma($('#PO').val());		
						   if(Number(strArr[0])==2.3)
									price = uncomma($('#PO').val());	;		  
							break;		
						 case 'EGI' :
							price = uncomma($('#EGI').val());	
						   if(Number(strArr[0])==1.0)
									price = uncomma($('#EGI').val());	
							break;		
						 case '304 HL' :
						   if(strArr[0]=='0.8')
								 price = Number(uncomma($('#HL304').val())) + 500;  // 300원 평균 더 비쌈 
						   if(strArr[0]=='1.2')
								  price = uncomma($('#HL304').val()) ;  
						   if(strArr[0]=='1.5')
								 price = uncomma($('#HL304').val()) ;  
						   if(strArr[0]=='3.0')
								 price = uncomma($('#HL304').val()) ;  
							break;		
						 case '201 HL' :						   
								 price = uncomma($('#HL201').val()) ;  // 300원 평균 더 비쌈 						   
							break;				
						 case '201 2B MR' :
						 case '201 MR' :						   
								 price = uncomma($('#MR201').val()) ;  
							break;	
						case '304 MR' :
						   if(strArr[0]=='0.8')
								 price = Number(uncomma($('#MR304').val())) + 600;  // 700원 평균 더 비쌈 
						   if(strArr[0]=='1.2')
								 price = uncomma($('#MR304').val()) ;  
						   if(strArr[0]=='1.5')
								 price = uncomma($('#MR304').val()) ;  
							break;		
						 case 'AL' :
							 price = uncomma($('#MR304').val()) ;   // 알루미늄은 MR과 비슷함.
							break;					
						 case 'VB' :
							 price = uncomma($('#MR304').val()) ;   // MR과 비슷함.
							break;				
						 case '2B VB' :
						 case '201 2B MR' :
							price = uncomma($('#HL304').val()) ;    // HL과 비슷한 가격
							break;				
						  case '304 2B BA' :
						  case '304 BA BEAD' :	  
								 price = uncomma($('#MR304').val()) ;  // MR가격과 비슷함.
							break;			
						 default:
								 price = uncomma($('#etcsteel').val()) ;  // 나머지는 특수소재로 계산함. 
							break;
						 }	  
				 }


					 
					 if(Number(arr5[i])!=0 && !isNaN(arr5[i]) && lastsavedData !== arr1[i] +  arr2[i] +  arr3[i] ) 		   
					 {
						 const row = { name: count }; 
						 tmp=Number(arr5[i]);
						 			
							row['col1'] = arr1[i] ;						 						
							row['col2'] = arr2[i] ;			
							row['col3'] = arr3[i] ;			
							row['col4'] = tmp ;			
							row['col5'] = comma(tmp*weight) ;			
							row['col6'] = comma(price) ;			
							row['col7'] = comma(tmp*weight*price);							
							row['col8'] = arr7[i];
							
							data.push(row); 	
							
							// 마지막 '|' 제거
							
							numcopy[counter] = titleurl_arr[i]   ; 	 // 더블클릭 후 이벤트 전달			
							counter++;				
							 total_sum += (tmp*weight*price);
							 count++;
							 if(tmp<0)  {			 
								 check_minus++;    // 합계가 음수일때는 화면창에 알림
								 check_str += arr1[i] + " " + arr2[i] + " 업체명: " + arr3[i] + " " + tmp + " " ;
								 }
						 lastsavedData =  arr1[i] +  arr2[i] +  arr3[i] ;
					 }
						else 
						{
							// table1.setRowData(i-1,[arr1[i],arr2[i],arr3[i]]);	 
						}		
						
						if(Number(yday_sum[i])!=0 && !isNaN(yday_sum[i])) 	 // 어제 출고된 철판 금액 정리	   
							 {
									 yesterday += (Number(yday_sum[i])*weight*price);
									 // console.log(yday_sum[i]);
							 }			
						if(Number(ydaysaved_sum[i])!=0 && !isNaN(ydaysaved_sum[i])) 	 // 어제 잔재로 사용한 철판 금액
							 {
									 yesterdaysaved += Number(ydaysaved_sum[i]);
									// console.log(ydaysaved_sum[i]);
							 }		
						
						
					}  // end of for
				  // alert(total_sum);
				 var strdata ="";
				 strdata = total_sum + '\n' + yesterday;
				 $("#up_display2").text(comma(total_sum) + '원');
				 
				 // console.log(total_sum);
				 // console.log(yesterday);
				 // console.log(yesterdaysaved);
				 
				 tmp="./saveinfo.php?yesterdaytotal=" + total_sum + "&yesterdayused=" + yesterday + "&yesterdaysaved=" + yesterdaysaved;			
				 $("#vacancy").load(tmp);     
				 
				 if(check_minus>0)  {
								$("#display_board").show();
								$("#display_board").text("재고 마이너스 상태 확인 : " + check_str);
								if($("#username").val()=='김영무') {
									$("#alertmsg").text("재고 마이너스 상태 확인 : " + check_str);
									$('#myModal').modal('show');
									}
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
				  bodyHeight: 600,					  					
				  columns: [ 				   
					{
					  header: '철판종류 구분',
					  name: 'col1',
					  sortingType: 'desc',
					  sortable: true,
					  width:150,		
					  align: 'center'
					},			
					{
					  header: '규격 (두께 x W x H)',
					  name: 'col2',
					  width:120,	
					  align: 'center'
					},
					{
					  header: '사급자재 여부',
					  name: 'col3',
					  sortingType: 'desc',
					  sortable: true,					  
					  width:150,	
					  align: 'center'
					},
					{
					  header: '수량',
					  name: 'col4',
					  width:70,
					  align: 'center'
					},
					{
					  header: '중량(kg)',
					  name: 'col5',
					  width:70,	
					  align: 'center'
					},
					{
					  header: 'kg당 단가',
					  name: 'col6',
					  width:100,		
					  align: 'center'
					},
					{
					  header: '금액',
					  name: 'col7',
					  width:150,
					  align: 'center'
					},
					{
					  header: '기록건수',
					  name: 'col8',
					  width:60,
					  align: 'center'
					}				
				  ],
				columnOptions: {
						resizable: true
					  },
				  rowHeaders: ['rowNum'],
				});	

					   // grid 색상등 꾸미기
							var Grid = tui.Grid; // or require('tui-grid')
							Grid.applyTheme('default', {
									selection: {
										background: '#ccc',
										border: '#fdfcfc'
									  },
									  scrollbar: {
										background: '#e6eef5',
										thumb: '#d9d9d9',
										active: '#c1c1c1'
									  },
									  row: {
										hover: {
										  background: '#e6eef5'
										}
									  },
									  cell: {
										normal: {
										  background: '#FFFF',
										  border: '#e6eef5',
										  showVerticalBorder: true
										},
										header: {
										  background: '#e6eef5',
										  border: '#fdfcfc',
										  showVerticalBorder: true
										},
										rowHeader: {
										  border: '#e6eef5',
										  showVerticalBorder: true
										},
										editable: {
										  background: '#fbfbfb'
										},
										selectedHeader: {
										  background: '#e6eef5'
										},
										focused: {
										  border: '#e6eef5'
										},
										disabled: {
										  text: '#e6eef5'
										}
									  }	
							});	
				

			// 더블클릭 이벤트	
			grid.on('dblclick', (e) => {	
			    const sendvar = grid.getValue(e.rowKey,'col1') + "|" + grid.getValue(e.rowKey,'col2') + "|" + grid.getValue(e.rowKey,'col3')  ;				
				// const sendvar = inputString.replace("&", "@");
				
				var link = 'http://8440.co.kr/steel/part_view.php?arr=' + encodeURIComponent(sendvar) ; 	   
			   popupCenter(link, '원자재 입출고 상세내역' ,1000,900);	
			   
			});		
					
				

			});



			  </script>
			  

