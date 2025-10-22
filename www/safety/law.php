<?php
/**
 * 위험성평가 법령 안내 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 변수 초기화
$mcno = $_REQUEST["mcname"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    /*   alert("관리자 승인이 필요합니다."); */
    $_SESSION["url"] = getBaseUrl() . '/safetycard/laser.php?mcno=' . $mcno;
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}


 ?>
 
 <?php include includePath('load_header.php') ?> 

<meta property="og:type" content="미래기업 통합정보시스템">
<meta property="og:title" content="위험성평가 전산시스템">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="정확한 업무처리를 위한 필수사이트!">
<meta property="og:image" content="https://8440.co.kr/img/miraethumbnail.jpg"> 

<title> 사업장 위험성평가에 관한 지침 </title> 
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

<?php include includePath('myheader.php'); ?>   
	
	<!-- Related items section-->
	<section class="py-5 bg-light">
	<div class="container px-1 px-lg-1 mt-1">
		<div class="row justify-content-center">
		<h2 > 사업장 위험성평가에 관한 지침 </h2>
		</div>
		<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
          [시행 2023. 5. 22.] [고용노동부고시 제2023-19호, 2023. 5. 22., 일부개정]
		</div>
     </div>
	</section>
	 
    <div class="container-fluid w-90 " >		
             <iframe src="https://www.law.go.kr/%ED%96%89%EC%A0%95%EA%B7%9C%EC%B9%99/%EC%82%AC%EC%97%85%EC%9E%A5%EC%9C%84%ED%97%98%EC%84%B1%ED%8F%89%EA%B0%80%EC%97%90%EA%B4%80%ED%95%9C%EC%A7%80%EC%B9%A8" width="100%" height="600px;"></iframe>		
	</div>
		
<?php
    // 데이터베이스 연결
    $pdo = db_connect();
?>

    <!-- ajax 전송으로 DB 수정 -->
    <?php include includePath('formload.php'); ?>

    <!-- Footer-->
    <?php include includePath('shop/footer.php'); ?>
        <!-- Core theme JS-->

<script>

function choiceMC(qrcode, name) {
    var link;
    link = '<?php echo getBaseUrl(); ?>/safetycard/laser.php?qrcode=' + qrcode + '&name=' + name;

    window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=1700,height=850");
}	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('사업장 위험성평가에 관한 지침');
});
</script> 
</body>
</html>
