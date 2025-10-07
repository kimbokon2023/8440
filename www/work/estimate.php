<?php
session_start();

$root_dir = $_SERVER['DOCUMENT_ROOT'] ;

ini_set('display_errors','0');  // 화면에 warning 없애기	

// 모바일 사용여부 확인하는 루틴
$mAgent = array("iPhone","iPod","Android","Blackberry", 
    "Opera Mini", "Windows ce", "Nokia", "sony" );
$chkMobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
    if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
        $chkMobile = true;
        break;
    }
}   

$level= $_SESSION["level"];
$user_name= $_SESSION["name"];

$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./estimate.ini",false);	
// 초기 서버를 이동중에 저정해야할 변수들을 저장하면서 작업한다. 자료를 추가 불러올때 카운터 숫자등..
$init_read = array();   // 환경파일 불러오기
$init_read = parse_ini_file("./estimate.ini",false);	


										  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

	
if($num=='')
{
	$registedate=date("Y-m-d");	

	$mcno = '';
	$inputsum = '';
	$outputsum = '';
}
else // 값이 존재하면 수정모드
{
	$isEditMode = true; // 수정 모드 여부
	$inputvalues = explode(',', $input_arr);
	$outputvalues1 = explode(',', $output_arr1);
	$outputvalues2 = explode(',', $output_arr2);
	$outputvalues3 = explode(',', $output_arr3);
	$outputvalues4 = explode(',', $output_arr4);

	
	$input_arr = $inputvalues;
	$output_arr1 = $outputvalues1;
	$output_arr2 = $outputvalues2;
	$output_arr3 = $outputvalues3;
	$output_arr4 = $outputvalues4;	
	
// 지출부분 읽기
		
	$text_arr1 = explode(',', $text1);
	$text_arr2 = explode(',', $text2);
	$text_arr3 = explode(',', $text3);
	$text_arr4 = explode(',', $text4);
	$text_arr5 = explode(',', $text5);

	// 배열의 각 요소가 0인 경우 공백으로 변경
	$text_arr1 = array_map(function($value) {
		return ($value == 0) ? '' : $value;
	}, $text_arr1);

	$text_arr2 = array_map(function($value) {
		return ($value == 0) ? '' : $value;
	}, $text_arr2);

	$text_arr3 = array_map(function($value) {
		return ($value == 0) ? '' : $value;
	}, $text_arr3);

	$text_arr4 = array_map(function($value) {
		return ($value == 0) ? '' : $value;
	}, $text_arr4);

	$text_arr5 = array_map(function($value) {
		return ($value == 0) ? '' : $value;
	}, $text_arr5);

	
	// var_dump($text1);

	$mode="modify";
}


	$total0 = 0;		  
	$total1 = 0;		  
	$total2 = 0;		  
	$total3 = 0;		  
	$total4 = 0;		  
	$total5 = 0;

    $total1 += array_sum($text1);
    $total2 += array_sum($text2);
    $total3 += array_sum($text3);
    $total4 += array_sum($text4);
    $total5 += array_sum($text5);
	
	$total0 += $total1 + $total2 + $total3 + $total4 + $total5 ;	


?>  

<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
<title>JAMB 단가</title>
<!-- Dashboard CSS -->
<link rel="stylesheet" href="/css/dashboard-style.css" type="text/css" />

<style>
/* Light & Subtle Theme - Estimate Specific */
/* ========================================= */

body {
    background: var(--gradient-primary);
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}


/* Side Banner */
.sideBanner {
    position: fixed;
    top: 20vh;
    left:75vw;
    width: 30vw;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap:1rem;
}

/* Tables - Light & Subtle Theme */
.table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--dashboard-shadow);
    margin-bottom: 0;
} 

.table th {
    background: #f0fbff !important;
    color: var(--dashboard-text) !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    border: 1px solid var(--dashboard-border) !important;
    padding: 0.75rem !important;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
}

.table td {
    font-size: 0.9rem !important;
    padding: 0.75rem !important;
    border: 1px solid var(--dashboard-border) !important;
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--dashboard-text) !important;
    vertical-align: middle;
    text-align: center;
}

.table tbody tr:hover td {
    background-color: var(--dashboard-hover) !important;
    transition: background-color 0.2s ease;
}

/* Form Controls */
.form-control {
    border: 1px solid var(--dashboard-border);
    background: white;
    color: var(--dashboard-text);
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
    outline: none;
}

.form-control.text-end {
    text-align: right;
    padding-right: 0.75rem;
}

/* Buttons - Light & Subtle Theme */
.btn-dark {
    background: var(--dashboard-accent) !important;
    color: white !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2) !important;
    padding: 0.5rem 1.25rem !important;
}

.btn-dark:hover {
    background: var(--dashboard-accent-light) !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3) !important;
}

.btn-secondary {
    background: var(--dashboard-secondary) !important;
    color: var(--dashboard-text) !important;
    border: 1px solid var(--dashboard-border) !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    padding: 0.5rem 1.25rem !important;
}

.btn-secondary:hover {
    background: #c7f0ff !important;
    color: var(--dashboard-text) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.15) !important;
}

.btn.rounded-pill {
    border-radius: 25px !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sideBanner {
        position: relative;
        bottom: 20px;
        right: 20px;
        left: 20px;
        width: auto;
        flex-direction: row;
        justify-content: center;
        gap: 1rem;
    }

    .container-fluid {
        padding: 1rem 0.5rem;
    }

    .modern-management-card {
        margin: 0.5rem 0;
    }

    .table-responsive {
        font-size: 0.8rem;
    }
}
</style>
</head>

<body>	
	
	
<form id="board_form" name="board_form" class="form-signin" method="post">                      
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>">
	<input type="hidden" id="num" name="num" value="<?=$num?>">
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>">	

<div class="container-fluid">
	<div class="row justify-content-center align-items-center vh-60">
		<div class="col-sm-6">
			<div class="modern-management-card">
				<div class="modern-dashboard-header">
					<h3 style="color: #000; margin: 0; font-size: 1.1rem; font-weight: 600;">쟘 제품 단가</h3>
				</div>
				<div class="card-body" style="padding: 1.0rem;">
					<div class="table-responsive">
						<table id="table2" class="table table-bordered tabls-sm">
    <thead>
        <tr>
            <th rowspan="2">구분</th>
            <th colspan="2">재질</th>
        </tr>
        <tr>
            <th style="width:30%;">H/L</th>
            <th style="width:30%;">기타</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>막판유</td>
            <td>
                <input type="text" class="form-control text-end" name="WJ_HL" value="<?=$readIni['WJ_HL']?>"  data-separator=","  />
            </td>
            <td>
                <input type="text" class="form-control text-end" name="WJ" value="<?=$readIni['WJ']?>" data-separator="," />
            </td>
        </tr>
        <tr>
            <td>막판무</td>
            <td>
                <input type="text" class="form-control text-end" name="NJ_HL"  value="<?=$readIni['NJ_HL']?>" data-separator="," />
            </td>
            <td>
                <input type="text" class="form-control text-end" name="NJ" value="<?=$readIni['NJ']?>"  data-separator="," />
            </td>
        </tr>
        <tr>
            <td>쪽쟘</td>
            <td>
                <input type="text" class="form-control text-end" name="SJ_HL"  value="<?=$readIni['SJ_HL']?>" data-separator="," />
            </td>
            <td>
                <input type="text" class="form-control text-end" name="SJ"  value="<?=$readIni['SJ']?>" data-separator="," />
            </td>
        </tr>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
<div class="sideBanner">

 <!-- <div class="mb-1 mt-1 fs-3">
    <button type="button" class="btn btn-dark rounded-pill saveBtn fs-1">저장</button>
  </div> -->
  
<div class="mb-1 mt-1">
  <button type="button" class="btn btn-dark rounded-pill fs-6" id="saveButton">저장</button>
</div>

  <div class="mb-1 mt-1">
    <button type="button" class="btn btn-secondary rounded-pill closeBtn fs-6">닫기</button>
   </div>
</div>
</div>
</div>
</form>
<script> 

// 전자결재를 위해 띄우는 창
// 기본 위치(top)값
var floatPosition = parseInt($(".sideBanner").css('top'))

// scroll 인식
$(window).scroll(function() {
  // 모바일에선 나타나지 않게 하기  
    // 현재 스크롤 위치
    var currentTop = $(window).scrollTop();
    var bannerTop = currentTop + floatPosition + "px";

    //이동 애니메이션
    $(".sideBanner").stop().animate({
      "top" : bannerTop
    }, 400);
  
}).scroll();


$(document).ready(function(){	

	$('.form-control').on('input', function() {
		var separator = $(this).data('separator');
		var value = $(this).val().replace(/\,/g, '');
		var parsedValue = parseInt(value);
		var formattedValue = isNaN(parsedValue) ? '' : parsedValue.toLocaleString();
		$(this).val(formattedValue);
	});	

	var state =  $('#state').val();  	
	// 처리완료인 경우는 수정하기 못하게 한다.

	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});
		
	$(".closeBtn").click(function(){    // 저장하고 창닫기	

			myalert("창 닫기!");		
			opener.location.reload();		
			 window.close();	
	 });	
	 
	$('#saveButton').on('click', function() {
		$.ajax({
			url: "save_estimate.php",
			type: "post",		
			data: $("#board_form").serialize(),								
			success : function( data ){		

			   console.log(data);			
														
					 Toastify({
							text: '저장되었습니다.' ,
							duration: 3000,
							close:true,
							gravity:"top",
							position: "center",
							backgroundColor: "#4fbe87",
						}).showToast();							
			 												
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
					   } 			      		
		   });		
	   });		
			 
}); // end of ready document


function myalert(str) {

 Toastify({
		text: str,
		duration: 3000,
		close:true,
		gravity:"top",
		position: "center",
		backgroundColor: "#4fbe87",
		className: "toastify-content",
	}).showToast();	
	
	setTimeout(function() {
		// 시간지연
		}, 1000);
	
}	 

</script>

</body>
</html>
