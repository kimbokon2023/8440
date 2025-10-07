<?php
session_start();

$root_dir = $_SERVER['DOCUMENT_ROOT'] ;

ini_set('display_errors','1');  // 화면에 warning 없애기	0

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
	$mode="modify";
}

?>  

 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
<title> 천장 제품단가 </title>
</head>
 
<body>

    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
		     결재가 진행중입니다. <br> 
		   <br> 
		  수정사항이 있으면 결재권자에게 말씀해 주세요.
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>

<style>
	.fixed-table {
		position: sticky;
		top: 0;
		background-color: #fff;
		z-index: 1;
		margin-bottom: 10px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}
	

/* 우측배너 제작 */

.sideBanner {
  position: absolute;
  width: calc(100vw - 90vw);
  height:calc(100vh - 70vh);
  top: calc(100vh - 70vh);
  left: calc(100vw - 20vw);  
}

</style>	
	
<form id="board_form" name="board_form" class="form-signin" method="post">

<input type="hidden" id="mode" name="mode" value="<?=$mode?>">
<input type="hidden" id="num" name="num" value="<?=$num?>" >                        
<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" >	

<div class="container-fluid">
    <div class="row justify-content-center align-items-center  vh-100">
        <div class="col-sm-7 text-center">
            <div class="card align-middle justify-content-center " style="border-radius: 20px;">
                <div class="card-body mt-5 mb-5">
				<div class="d-flex justify-content-center">	
                    <span class="card-title fs-5 mb-1 " style="color: #113366; ">조명천장/본천장 제품 단가</span>
				</div>	
<div class="table-responsive justify-content-center">			
	<table class="table table-bordered ">
    <thead>
        <tr>
            <th style="width:50%;" >인승</th>            
            <th >조명천장</th>            
            <th >본천장</th>            
        </tr>        
    </thead>
    <tbody>
        <tr>
            <td>12인승 이하</td>
            <td>
				<div class="d-flex justify-content-center">	
					<input type="text" class="form-control w-75 text-end" name="lc_unit_12" value="<?=$readIni['lc_unit_12']?>"  data-separator="," />
				</div>	
            </td>
            <td>
				<div class="d-flex justify-content-center">	
					<input type="text" class="form-control w-75 text-end" name="bon_unit_12" value="<?=$readIni['bon_unit_12']?>" data-separator="," />
				</div>
            </td>
        </tr>
        <tr>
            <td>13인승 이상 17인승 이하</td>
            <td>
				<div class="d-flex justify-content-center">	
					<input type="text" class="form-control w-75 text-end" name="bon_unit_13to17"  value="<?=$readIni['bon_unit_13to17']?>" data-separator="," />
				</div>
            </td>
            <td>
				<div class="d-flex justify-content-center">	
					<input type="text" class="form-control w-75 text-end" name="lc_unit_13to17" value="<?=$readIni['lc_unit_13to17']?>"  data-separator="," />
				</div>
            </td>
        </tr>
        <tr>
            <td>18인승 이상</td>
            <td>
				<div class="d-flex justify-content-center">	
					<input type="text" class="form-control w-75 text-end" name="lc_unit_18"  value="<?=$readIni['lc_unit_18']?>" data-separator="," />
				</div>
            </td>
            <td>
				<div class="d-flex justify-content-center">	
					<input type="text" class="form-control w-75 text-end" name="bon_unit_18"  value="<?=$readIni['bon_unit_18']?>" data-separator="," />
				</div>
            </td>
        </tr>
    </tbody>
</table>
</div>

				
		</div>
	</div>
</div>
</div>
</div>


<div class="sideBanner">
 
<div class="mb-1 mt-1">
  <button type="button" class="btn btn-dark rounded-pill fs-6" id="saveButton">저장</button>
</div>

  <div class="mb-1 mt-1">
    <button type="button" class="btn btn-secondary rounded-pill closeBtn fs-6">닫기</button>
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
    }, 500);
  
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
