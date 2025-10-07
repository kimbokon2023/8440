<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
 
 if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:https://8440.co.kr/login/login_form.php"); 
         exit;
   }
   
ini_set('display_errors','1');  // 화면에 warning 없애기	

$today = date("Y-m-d");


?>

<meta property="og:type" content="미래기업 통합정보시스템">
<meta property="og:title" content="위험성평가 전산시스템">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="정확한 업무처리를 위한 필수사이트!">
<meta property="og:image" content="https://8440.co.kr/img/miraethumbnail.jpg"> 

 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<title> 미래기업 안전보건 </title>  

</head>

<body>

  
<? include $_SERVER['DOCUMENT_ROOT'] . '/myheader.php'; ?>   
    
<div class="container mb-3"  >
	<div class="card">
	  <div class="card-body">
		<div class="d-flex mt-2 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 위험성평가 개요 </h3>
		</div>
		<div class="d-flex mt-5 align-items-center">  			 
			<H4> &nbsp; &nbsp; 위험성평가란? </h4>			
		</div>
		<div class="d-flex p-5 mb-1 align-items-center">  			 
			사업주가 스스로 유해ㆍ위험요인을 파악하고 해당 유해ㆍ위험요인의 위험성 수준을 결정하여, 위험성을 낮추기 위한 적절한 조치를 마련하고 실행하는 과정을 말합니다.
		</div>
		<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
			 <img src="../img/content_new.gif">		
		</div>		
		
		<div class="d-flex mb-1 mt-5 align-items-center">  			 
			<H4> &nbsp; &nbsp; 관련법령 </h4>			
		</div>		
		
		<div class="d-flex mb-1 mt-2 align-items-center">  	
				<H5>  &nbsp; &nbsp; 산업안전보건법 제36조(위험성평가)  </h5> 
		</div>		
		<div class="row mb-1 mt-2  align-items-center" style="margin-left: 40px;">
		  <code>
			① 사업주는 건설물, 기계ㆍ기구ㆍ설비, 원재료, 가스, 증기, 분진, 근로자의 작업행동 또는 그 밖의 업무로 인한 <br>
			유해ㆍ위험 요인을 찾아내어 부상 및 질병으로 이어질 수 있는 위험성의 크기가 허용 가능한 범위인지를 평가하여야 하고, <br>
			그 결과에 따라 이 법과 이 법에 따른 명령에 따른 조치를 하여야 하며, <br>
			근로자에 대한 위험 또는 건강장해를 방지하기 위하여 필요한 경우에는 추가적인 조치를 하여야 한다. <br>
			② 사업주는 제1항에 따른 평가 시 고용노동부장관이 정하여 고시하는 바에 따라 해당 작업장의 근로자를 참여시켜야 한다. <br>
			③ 사업주는 제1항에 따른 평가의 결과와 조치사항을 고용노동부령으로 정하는 바에 따라 기록하여 보존하여야 한다. <br>
			④ 제1항에 따른 평가의 방법, 절차 및 시기, 그 밖에 필요한 사항은 고용노동부장관이 정하여 고시한다.
		  </code>
		</div>


		
		<div class="d-flex mb-1 mt-5 align-items-center">  			 
			<H5> &nbsp; &nbsp; 고용노동부 고시 제2023-19호 「사업장 위험성평가에 관한 지침」 </h5>			
		</div>		
		
		<div class="row mb-1 mt-2 align-items-center" style="margin-left: 40px;">					
			    <code>
					제1장 : 총칙(제1조~제4조) <br>
					제2장 : 사업장 위험성평가(제5조~제15조) <br>
					제3장 : 위험성평가 인정(제16조~제25조) <br>
					제4장 : 지원사업의 추진 등(제26조~제28조) <br>
					부칙
				</code>	
		</div>		
		
		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 위험성평가 인정 </h3>
		</div>
		<div class="d-flex mt-5 align-items-center">  			 
			<H4> &nbsp; &nbsp; "위험성평가" 사업주의 의무 입니다. </h4>			
		</div>
		<div class="d-flex p-5 mb-1 align-items-center">  			 
			위험성평가 인정, 신청 대상 사엄장, 우수사업장 인정절차 및 혜택에 대하여 알아보세요!
		</div>
		<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
			 <img src="../img/safe1.jpg">		
		</div>	
		
		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 위험성평가 교육 </h3>
		</div>
		<div class="d-flex mt-5 align-items-center">  			 
			<H4> &nbsp; &nbsp; "위험성평가" 교육을 통해 안전한 사업장이 될 수 있도록 노력합시다! </h4>			
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
		<H5> 	경기중부지사	경기도 부천시 원미구 송내대로265번길 19 대신프라자 (3층)	032-680-6556	경기도 부천시 및 김포시 </h5>
		</div>	

		<div class="d-flex mt-5 justify-content-center align-items-center">  	
		 <!-- <img src="../img/workingon.jpg">	 -->		 
		<H3> 제조업 자율점검표(고위험 기인물 12종) </h3>
		</div>			
		<div class="d-flex mt-5 mb-5 justify-content-center align-items-center">  	
		<embed src="../img/safe3.pdf" type="application/pdf" width="100%" height="850px" />
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