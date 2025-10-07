<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함

 $mcno=$_REQUEST["mcname"];

// // 배열로 미점검 장비점검리스트 불러옴
// include "load_nocheck.php";
 ?>
 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?> 
 
<meta property="og:type" content="미래기업 통합정보시스템">
<meta property="og:title" content="위험성평가 전산시스템">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="정확한 업무처리를 위한 필수사이트!">
<meta property="og:image" content="https://8440.co.kr/img/miraethumbnail.jpg"> 
 
<title> 안전보건 위험성평가 카드뉴스 </title> 
</head>		 

  <style>
    .image-container {
      width: 100%;
      height: 300px;
      overflow: hidden;
    }

    .image-container img {
      object-fit: 20%;
      object-position: 0px 0px;
    }
  </style>

<body id="page-top">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/myheader.php'; ?>   
	
	<!-- Related items section-->
	<section class="py-5 bg-light">
	<div class="container px-1 px-lg-1 mt-1">
		<!-- 미점검 리스트 출력 -->			
		<h2 class="fw-bolder mb-5">
		<img src="../img/safe4.jpg" style="width:100%;height:100%;" >
		 </h2>
		<h2 class="fw-bolder mb-4"> 안전보건 카드뉴스 </h2>
		<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
				
<?
   
 if(!isset($_SESSION["level"]) || $level>8) {          
		 $_SESSION["url"]='https://8440.co.kr/safetycard/laser.php?mcno=' + $mcno ; 		  		 
		 sleep(1);
		 header("Location:https://8440.co.kr/login/login_form.php"); 
         exit;
}   
   
$todate=date("Y-m-d");   // 현재일자 변수지정   

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$counter=0;
$mcno_arr=array("[위험성평가] 슈퍼맨이아니라면", "용접용단 작업자편", "전기기계기구 작업", "[위험성평가]혜택-사업주교육인정", "[위험성평가] 기업의 이익"," [안젤뉴스룸] 재난 수준의 폭염, 노동자 사망","[안젤뉴스룸] 지게차 충돌 사고" );
$qrcode_arr=array("safeimg1.jpg","safeimg2.jpg","safeimg3.jpg","safeimg4.jpg","safeimg5.jpg","safeimg6.jpg","safeimg7.jpg");

for($i=0;$i<count($mcno_arr);$i++)
 {	
  $qrcode = 'https://8440.co.kr/img/' . $qrcode_arr[$i] ;   
  
  // print $qrcode ;
?>			
                  <div class="col mb-5">			     
                        <div class="card h-100" onclick="choiceMC('<?=$qrcode?>','<?=$mcno_arr[$i]?>');" >
                            
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center fs-3">
                                    <!-- name-->
                                    <h4 class="fw-bolder"> <?=$mcno_arr[$i]?> </h4>
                                </div>
                                <div class="text-center fs-3">                                                                       
								    <div class="image-container">
								   <img class="image-container" src=<?=$qrcode?> style="width:100%;height:300px;" >
								    </div>								   
                                </div>
                            </div>
                            <!-- Product actions-->
                            
                        </div>
					
                    </div> 
<?
      }	
?> 					
			


					
                </div>
            </div>
</section>
		
<!-- ajax 전송으로 DB 수정 -->
<? include "../formload.php"; ?>	
		
<!-- Footer-->
<? include "../shop/footer.php" ?>  		
<!-- Core theme JS-->
  
<script>
function choiceMC(qrcode, name){

var link ;
link = 'https://8440.co.kr/safetycard/laser.php?qrcode=' + qrcode + '&name=' + name;       

window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=1700,height=850");
	
}	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('안전보건 카드뉴스'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>