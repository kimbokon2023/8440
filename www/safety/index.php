<?php
/**
 * 위험성평가 메인 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    /*   alert("관리자 승인이 필요합니다."); */
    sleep(2);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

ini_set('display_errors', '1');  // 에러 표시 활성화 (개발용)

$today = date("Y-m-d");


?>

<meta property="og:type" content="미래기업 통합정보시스템">
<meta property="og:title" content="위험성평가 전산시스템">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="정확한 업무처리를 위한 필수사이트!">
<meta property="og:image" content="https://8440.co.kr/img/miraethumbnail.jpg"> 

<?php include includePath('load_header.php') ?>

<title> 미래기업 안전보건 </title>  

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 최적화 */
        h3, h4, h5 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
            padding: 0.25rem !important;
        }
        
        h4 {
            font-size: 1.125rem !important;
        }
        
        h5 {
            font-size: 1rem !important;
        }
        
        /* 텍스트 영역 최적화 */
        .d-flex {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 0.5rem !important;
            margin: 0.5rem 0 !important;
        }
        
        .d-flex.p-5 {
            padding: 0.75rem !important;
        }
        
        .d-flex.mt-5 {
            margin-top: 1rem !important;
        }
        
        .d-flex.mb-3 {
            margin-bottom: 1rem !important;
        }
        
        /* 이미지 최적화 */
        img {
            max-width: 100% !important;
            height: auto !important;
            width: auto !important;
        }
        
        img[src*="content_new.gif"],
        img[src*="safe1.jpg"],
        img[src*="safe2.jpg"] {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* PDF 임베드 최적화 */
        embed[type="application/pdf"] {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            min-height: 400px !important;
        }
        
        /* 코드 블록 최적화 */
        code {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            margin: 0.5rem 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
        }
        
        .row {
            margin: 0.5rem 0 !important;
            padding: 0.5rem !important;
        }
        
        .row[style*="margin-left"] {
            margin-left: 0 !important;
            padding-left: 0.5rem !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* SweetAlert2 모달 최적화 */
        .swal2-popup {
            width: 90% !important;
            max-width: 90% !important;
            padding: 1rem !important;
            font-size: 0.875rem !important;
        }
        
        .swal2-title {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-content {
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-actions {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .swal2-confirm,
        .swal2-cancel {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
    }
</style>

</head>

<body>

  
<?php include includePath('myheader.php'); ?>   
    
<div class="container mb-3"  >
	<div class="card">
	  <div class="card-body">
		<div class="d-flex mt-2 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 위험성평가 개요 </H3>
		</div>
		<div class="d-flex mt-5 align-items-center">  			 
			<H4> 위험성평가란? </H4>			
		</div>
		<div class="d-flex p-5 mb-1 align-items-center">  			 
			사업주가 스스로 유해ㆍ위험요인을 파악하고 해당 유해ㆍ위험요인의 위험성 수준을 결정하여, 위험성을 낮추기 위한 적절한 조치를 마련하고 실행하는 과정을 말합니다.
		</div>
		<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
			 <img src="../img/content_new.gif">		
		</div>		
		
		<div class="d-flex mb-1 mt-5 align-items-center">  			 
			<H4> 관련법령 </H4>			
		</div>		
		
		<div class="d-flex mb-1 mt-2 align-items-center">  	
				<H5> 산업안전보건법 제36조(위험성평가) </H5> 
		</div>		
		<div class="row mb-1 mt-2 align-items-center" style="margin-left: 40px;">
		  <code>
			① 사업주는 건설물, 기계ㆍ기구ㆍ설비, 원재료, 가스, 증기, 분진, 근로자의 작업행동 또는 그 밖의 업무로 인한 유해ㆍ위험 요인을 찾아내어 부상 및 질병으로 이어질 수 있는 위험성의 크기가 허용 가능한 범위인지를 평가하여야 하고, 그 결과에 따라 이 법과 이 법에 따른 명령에 따른 조치를 하여야 하며, 근로자에 대한 위험 또는 건강장해를 방지하기 위하여 필요한 경우에는 추가적인 조치를 하여야 한다.<br>
			② 사업주는 제1항에 따른 평가 시 고용노동부장관이 정하여 고시하는 바에 따라 해당 작업장의 근로자를 참여시켜야 한다.<br>
			③ 사업주는 제1항에 따른 평가의 결과와 조치사항을 고용노동부령으로 정하는 바에 따라 기록하여 보존하여야 한다.<br>
			④ 제1항에 따른 평가의 방법, 절차 및 시기, 그 밖에 필요한 사항은 고용노동부장관이 정하여 고시한다.
		  </code>
		</div>


		
		<div class="d-flex mb-1 mt-5 align-items-center">  			 
			<H5> 고용노동부 고시 제2023-19호 「사업장 위험성평가에 관한 지침」 </H5>			
		</div>		
		
		<div class="row mb-1 mt-2 align-items-center" style="margin-left: 40px;">					
			    <code>
					제1장 : 총칙(제1조~제4조)<br>
					제2장 : 사업장 위험성평가(제5조~제15조)<br>
					제3장 : 위험성평가 인정(제16조~제25조)<br>
					제4장 : 지원사업의 추진 등(제26조~제28조)<br>
					부칙
				</code>	
		</div>		
		
		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 위험성평가 인정 </h3>
		</div>
		<div class="d-flex mt-5 align-items-center">  			 
			<H4> "위험성평가" 사업주의 의무 입니다. </H4>			
		</div>
		<div class="d-flex p-5 mb-1 align-items-center">  			 
			위험성평가 인정, 신청 대상 사업장, 우수사업장 인정절차 및 혜택에 대하여 알아보세요!
		</div>
		<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
			 <img src="../img/safe1.jpg">		
		</div>	
		
		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 위험성평가 교육 </h3>
		</div>
		<div class="d-flex mt-5 align-items-center">  			 
			<H4> "위험성평가" 교육을 통해 안전한 사업장이 될 수 있도록 노력합시다! </H4>			
		</div>
		
		<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
			 <img src="../img/safe2.jpg" style="width:100%;">		
		</div>		
		
		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 공단 지역본부 및 지사 연락처 </h3>
		</div>			
		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H5> 경기중부지사 | 경기도 부천시 원미구 송내대로265번길 19 대신프라자 (3층) | 032-680-6556 | 경기도 부천시 및 김포시 </H5>
		</div>	

		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 제조업 자율점검표(고위험 기인물 12종) </h3>
		</div>			
		<div class="d-flex mt-5 mb-5 justify-content-center align-items-center">  	
		<embed src="../img/safe3.pdf" type="application/pdf" width="100%" height="850px" style="max-width: 100%; height: auto; min-height: 400px;" />
		</div>			
		<div class="d-flex mt-5 mb-5 justify-content-center align-items-center">  			
		</div>	
		
	  </div>     	
	</div>     	
</div>     	

<script> 
// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('위험성 평가'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>