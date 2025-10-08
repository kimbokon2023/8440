<?php require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 첫 화면 표시 문구
$title_message = '품질방침/품질목표';
?>

<?php include getDocumentRoot() . '/load_header.php' ?> 

<title> <?=$title_message?> </title> 

</head> 

<body>

<?php 
$header = isset($_REQUEST["header"]) ? $_REQUEST["header"] : '';
if($header !=='header')
	require_once(includePath('myheader.php')); 

?>   

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();    

$tablename = "iso";  

$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : '';

if ($mode == "search") {
    if (!$search) {
        $sql = "select * from mirae8440." . $tablename . " order by num desc";                 
    } else {
        $sql = "select * from mirae8440." . $tablename . " where name like '%$search%' or subject like '%$search%' or nick like '%$search%' or regist_day like '%$search%' order by num desc";              
    }
} else {
    $sql = "select * from mirae8440." . $tablename . " order by num desc";              
}
?>

<form name="board_form" id="board_form" method="post" action="goal.php?mode=search&search=<?=$search?>">


<?php if ($chkMobile): ?>
	<div class="container-fluid">  
<?php else: ?>     
	<div class="container">  
<?php endif; ?>	

    <div class="card mt-1 mb-1">  
        <div class="card-body">  
            <div class="d-flex mt-3 mb-2 justify-content-center">  
                <h5 class="fs-4 fw-bold"> <?=$title_message?> </h5>  
            </div>     
            <div class="d-flex mt-3 mb-1 justify-content-center align-items-center">  
                <!-- 부트스트랩 테이블로 이미지와 텍스트를 구성 -->
                <table class="table table-bordered rounded">
                    <tr>
                        <td style="width:50%;">
                            <img src="../img/quality/quality01.jpg" style="width:120%; height:300px;" alt="품질 방침 및 목표" class="img-fluid">
                        </td>
                        <td>
                            <img src="../img/quality/quality02.jpg" style="width:120%; height:300px;"  alt="품질 목표" class="img-fluid">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5 class="fs-4 fw-bold"> 품질방침</h5>
                            <ul class="fs-5" >
                                <li>고객중심 품질관리 경영</li>
                                <li>고객만족을 위한 내부 인프라 확충</li>
                                <li>고객 클레임 능동적 대응</li>
                                <li>부적합 관리 (원인규명 및 재발방지)</li>
                                <li>품질관리 유지 협의체 순환</li>
                            </ul>
                        </td>
                        <td>
                            <h5 class="fs-4 fw-bold"> 품질목표</h5>
							<ul class="fs-5">
								<li>고객만족 최우선 및 고객 불만 발생 최소화</li>
								<li>원재료, 공정, 제품, 통계적 관리를 통한 품질 경쟁력 확보</li>
								<li>품질결함 무결점 완전 적용</li>
								<li>제품개발의 효과적 활성화</li>
								<li>품질기술 향상 및 지속적 개선</li>
								<li>고객불만 Zero</li>
								<li>납기준수율 "100%"</li>
								<li>공정불량율 "1% 미만"</li>
								<li>원자재불량률 "1,000ppm"</li>
								<li>공정품질이슈 "5건 이하"</li>
							</ul>

                        </td>
                    </tr>
                </table>
            </div>  
            <div class="d-flex mt-3 mb-2 justify-content-center">  
                <h5 class="fs-4 fw-bold"> 인증서 취득 </h5>  
            </div>     
            <div class="d-flex mt-3 mb-1 justify-content-center align-items-center">                  
                <table class="table table-bordered rounded">
                    <tr>
                        <td style="width:50%;">
                            <img src="../img/quality/iso9001.jpg" style="width:120%; height:800px;" alt="ISO 9001" class="img-fluid">
                        </td>
                        <td>
                            <img src="../img/quality/iso14001.jpg" style="width:120%; height:800px;"  alt="ISO 14001" class="img-fluid">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <h5 class="fs-4 fw-bold"> ISO 9001 인증서</h5>                            
                        </td>
                        <td class="text-center">
                            <h5 class="fs-4 fw-bold"> ISO 14001 인증서</h5>                            
                        </td>
                    </tr>
                </table>
            </div>
        
<?php
$currentYear = date("Y");
?>

<div class="d-flex mt-5 mb-5 justify-content-center align-items-center">
    <select id="yearSelect" class="form-select fs-5 me-2 w150px">
        <?php for ($i = $currentYear; $i >= $currentYear - 0; $i--):  // -2 적용하면 3년치 나옴 ?>
            <option value="<?= $i ?>"><?= $i ?>년도</option>
        <?php endfor; ?>
    </select>
    <span class="fs-5">품질목표</span> 
</div>
<div class="d-flex mt-3 mb-2 justify-content-center">  
	<h5 class="fs-4 fw-bold"> 주요 제품 제조통계 </h5>  
</div>  
<div class="row mt-3 mb-1 justify-content-center align-items-top">
		<div class="col-sm-6">
	        <?php include includePath('QC/prod_jamb.php') ?>   
		</div>
		<div class="col-sm-6">
	        <?php include includePath('QC/prod_ceiling.php') ?>   
		</div>
</div>

<div class="d-flex mt-3 mb-2 justify-content-center">  
	<h5 class="fs-4 fw-bold"> 전체 불량율 </h5>  
</div>  
<div class="row mt-3 mb-1 justify-content-center align-items-top">
		<div class="col-sm-6">
			<?php include includePath('QC/rate_badAll.php') ?>   
		</div>
		<div class="col-sm-6">		
	        <?php include includePath('QC/rate_badDetail.php') ?>   
		</div>
</div>

<div class="d-flex mt-5 mb-5 justify-content-center">  
	<h5 class="fs-4 fw-bold"> 소장불량(협력사)/업체불량(소재)/기타 불량 제외 불량율 재산출 </h5>  
</div>  
<div class="row mt-3 mb-1 justify-content-center align-items-top">
		<div class="col-sm-6">
			<?php include includePath('QC/rate_badAllexcept.php') ?>   
		</div>
		<div class="col-sm-6">		
	        <?php include includePath('QC/rate_badDetailexcept.php') ?>   
		</div>
</div>

<div class="d-flex mt-5 mb-5 justify-content-center">  
	<h5 class="fs-4 fw-bold"> (모델구분 : 쟘) 불량율 </h5>  
</div>  
<div class="row mt-3 mb-1 justify-content-center align-items-top">
		<div class="col-sm-6">
			<?php include includePath('QC/rate_badAllJamb.php') ?>   
		</div>
		<div class="col-sm-6">		
	        <?php include includePath('QC/rate_badDetailJamb.php') ?>   
		</div>
</div>

<div class="d-flex mt-5 mb-5 justify-content-center">  
	<h5 class="fs-4 fw-bold"> (모델구분 : 천장) 불량율 </h5>  
</div>  
<div class="row mt-3 mb-1 justify-content-center align-items-top">
		<div class="col-sm-6">
            <?php include includePath('QC/rate_badAllCeiling.php') ?>   
		</div>
		<div class="col-sm-6">		
	        <?php include includePath('QC/rate_badDetailCeiling.php') ?>   
		</div>
</div>


<!-- 점유율 그래프 -->
<div class="row mt-5 mb-5 justify-content-center align-items-top">
		<div class="col-sm-12"> 
			 <?php include includePath('load_errorstatistics.php');   ?>		  
		</div>

</div>


<div class="row mt-5 mb-5 justify-content-center align-items-top">
		  <div class="d-flex justify-content-start align-items-center ">
            <button  type="button" id="NGlistView" class="btn btn-info btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>            
			<h5> <유형별 세부내역> </h5>
		  </div>
	<div id="NGlist">	  
		<div class="col-sm-12"> 
			<?php include includePath('QC/rate_badList.php') ?>    
		</div>
	</div>

</div>

<div class="row mt-5 mb-5 justify-content-center align-items-top">
		  <div class="d-flex justify-content-start align-items-center ">
            <button  type="button" id="NGreportView" class="btn btn-primary btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>            
			<h5> <불량 보고서내역> </h5>
		  </div>
	<div id="NGreportlist">	  
		<div class="col-sm-12"> 
			<?php include includePath('QC/NGreport.php') ?>    
		</div>
	</div>

</div>

<div class="row mt-5 mb-5 justify-content-center align-items-top">
		  <div class="d-flex justify-content-start align-items-center ">
            <button  type="button" id="FixNGView" class="btn btn-warning btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>            
			<h5> <품질개선활동 - 품질분임조> </h5>
		  </div>
	<div id="FixNGList">	  
		<div class="col-sm-12"> 
			<?php include includePath('QC/FixNGactivity.php') ?>    
		</div>
	</div>

</div>

<div class="row mt-5 mb-5 justify-content-center align-items-top">
		  <div class="d-flex justify-content-start align-items-center ">
            <button  type="button" id="MCactivity_view" class="btn btn-secondary btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>            
			<h5> <주기별 장비점검> </h5>
		  </div>
	<div id="MCactivity">	  
		<div class="col-sm-12"> 
			<?php include includePath('QC/MCactivity.php') ?>    
		</div>
	</div>

</div>
<div class="row mt-5 mb-5 justify-content-center align-items-top">
		  <div class="d-flex justify-content-start align-items-center ">
            <button  type="button" id="MCquestion_view" class="btn btn-dark btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>            
			<h5> <장비점검 항목> </h5>
		  </div>
	<div id="MCquestion">	  
		<div class="col-sm-12"> 
			<?php include includePath('QC/MCquestion.php') ?>    
		</div>
	</div>

</div>













		</div>
    </div>
</div>

</form>   

<script>

$('#yearSelect').change(function() {
	var selectedYear = $(this).val();
	// 여기서 선택한 연도를 기반으로 품질목표를 계산하는 로직을 추가하면 됩니다.
	console.log(selectedYear + '년도의 품질목표를 계산합니다.');
});	

$(document).ready(function() {
    // NGlistView
    $("#NGlistView").on("click", function() { 
		var showNGlist = getCookie("showNGlist");
		var todoCalendarContainer = $("#NGlist");
		if (showNGlist === "show") {
			todoCalendarContainer.css("display", "none");
			setCookie("showNGlist",  "hide"  , 10);
		} else {
			todoCalendarContainer.css("display", "block");
			setCookie("showNGlist",  "show"  , 10);
		}
    });	
    // NGreportView
    $("#NGreportView").on("click", function() { 
		var showReport = getCookie("showReport");
		var todoCalendarContainer = $("#NGreportlist");
		if (showReport === "show") {
			todoCalendarContainer.css("display", "none");
			setCookie("showReport",  "hide"  , 10);
		} else {
			todoCalendarContainer.css("display", "block");
			setCookie("showReport",  "show"  , 10);
		}
    });	
    // FixNGView 품질개선활동 - 품질분임조 
    $("#FixNGView").on("click", function() { 
		var showFixNG = getCookie("showFixNG");
		var todoCalendarContainer = $("#FixNGList");
		if (showFixNG === "show") {
			todoCalendarContainer.css("display", "none");
			setCookie("showFixNG",  "hide"  , 10);
		} else {
			todoCalendarContainer.css("display", "block");
			setCookie("showFixNG",  "show"  , 10);
		}
    });	
    // 장비점검
    $("#MCactivity_view").on("click", function() { 
		var showMCactivity = getCookie("showMCactivity");
		var todoCalendarContainer = $("#MCactivity");
		if (showMCactivity === "show") {
			todoCalendarContainer.css("display", "none");
			setCookie("showMCactivity",  "hide"  , 10);
		} else {
			todoCalendarContainer.css("display", "block");
			setCookie("showMCactivity",  "show"  , 10);
		}
    });
    // 장비점검 문항
    $("#MCquestion_view").on("click", function() { 
		var showMCquestion = getCookie("showMCquestion");
		var todoCalendarContainer = $("#MCquestion");
		if (showMCquestion === "show") {
			todoCalendarContainer.css("display", "none");
			setCookie("showMCquestion",  "hide"  , 10);
		} else {
			todoCalendarContainer.css("display", "block");
			setCookie("showMCquestion",  "show"  , 10);
		}
    });	
});	
	


function choiceMC(num, mcmain, mcsub, mcno) {
    var link;
    switch (num) {
        case 1:
            link = 'https://8440.co.kr/qc/laser.php?mcno=laser01&mcname=laser01';
            break;
        case 2:
            link = 'https://8440.co.kr/qc/laser.php?mcno=vcut01&mcname=vcut01';
            break;
        case 3:
            link = 'https://8440.co.kr/qc/laser.php?mcno=bending01&mcname=bending01';
            break;
        case 4:
            link = 'https://8440.co.kr/qc/laser.php?mcno=shearing01&mcname=shearing01';
            break;
        case 5:
            link = 'https://8440.co.kr/qc/laser.php?mcno=welder01&mcname=welder01';
            break;
        case 6:
            link = 'https://8440.co.kr/qc/laser.php?mcno=welder02&mcname=welder02';
            break;
        case 7:
            link = 'https://8440.co.kr/qc/laser.php?mcno=welder03&mcname=welder03';
            break;
        case 8:
            link = 'https://8440.co.kr/qc/laser.php?mcno=welder04&mcname=welder04';
            break;
        case 9:
            link = 'https://8440.co.kr/qc/laser.php?mcno=motor01&mcname=motor01';
            break;
        case 10:
            link = 'https://8440.co.kr/qc/laser.php?mcno=motor02&mcname=motor02';
            break;
        case 11:
            link = 'https://8440.co.kr/qc/laser.php?mcno=tapdrill01&mcname=tapdrill01';
            break;
        case 12:
            link = 'https://8440.co.kr/qc/laser.php?mcno=comp01&mcname=comp01';
            break;
        case 13:
            link = 'https://8440.co.kr/qc/laser.php?mcno=comp02&mcname=comp02';
            break;
    }

    if (num > 0) {
        popupCenter(link, '장비 점검', 1200, 900);
    }
}
	

$(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';
   title = '품질방침 품질목표';
   // title = '절곡 ' + title ;
   saveMenuLog(title);
});	
	
</script>


</body>
</html>
