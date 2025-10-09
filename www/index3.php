<?php require_once __DIR__ . '/bootstrap.php';
require_once(includePath('session.php'));
 
// 모바일 사용여부 확인하는 루틴
$mAgent = array("iPhone","iPod","Android","Blackberry", 
    "Opera Mini", "Windows ce", "Nokia", "sony" );
$chkMobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
    if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
        $chkMobile = true;
		// print '권영철';
        break;
    }
}
isset($_REQUEST["home"]) ? $home = $_REQUEST["home"] : $home=""; 
//print $home;

$APIKEY = "2ddb841648d38606331320046099cf67";

isset($_REQUEST["Lat"]) ? $Lat=$_REQUEST["Lat"] : $Lat='';	 
isset($_REQUEST["Lng"]) ? $Lng=$_REQUEST["Lng"] : $Lng='';	 
isset($_REQUEST["HomeAddress"]) ? $HomeAddress=$_REQUEST["HomeAddress"] : $HomeAddress='';	 

// 메인상단 이미지를 AI로 그린 그림으로 10개 랜덤으로 뽑아내서 그려주기
$rnd = rand(1, 10);
$imgsrc = 'img/homepage/' . $rnd . '.png';

$root_dir = getDocumentRoot() ;
$version = '1';
?>

<!DOCTYPE html>

<html lang="ko">

<!-- Favicon-->	
<link rel="icon" type="image/x-icon" href="favicon.ico">   <!-- 33 x 33 -->
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">    <!-- 144 x 144 -->
<link rel="apple-touch-icon" type="image/x-icon" href="favicon.ico">

<meta property="og:type" content="엘리베이터 의장재 조명천장,쟘 미래기업">
<meta property="og:title" content="엘리베이터 의장재 조명천장,쟘 미래기업">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="엘리베이터 의장재 조명천장 쟘 덧씌우기 재료분리대 제작 전문기업">
<meta property="og:image" content="https://8440.co.kr/img/mirae.png"/>

<title> 미래기업 엘리베이터 쟘/조명천장 전문기업</title>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Additional CSS Files -->
<link rel="stylesheet" href="assets/css/fontawesome.css">
<link rel="stylesheet" href="assets/css/templatemo-scholar.css">
<link rel="stylesheet" href="assets/css/owl.css">
<link rel="stylesheet" href="assets/css/animate.css">
<link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>

<script src="vendor/jquery/jquery.min.js"></script>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js" ></script>


<link href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css" rel="stylesheet">
<script src="https://unpkg.com/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.10/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.js"></script>

<title> 미래기업 - 엘리베이터 조명천장/JAMB </title>
  
<style>

.dropdown:hover .dropdown-menu {
    display: block;
    margin-top: 0;
}
/* 마우스 오버하면 드롭다운하기 */

/* 파일선택 CSS */
.box-file-input label{
  display:inline-block;
  background:#23a3a7;
  color:#fff;
  padding:0px 15px;
  line-height:35px;
  cursor:pointer;
}

.box-file-input label:after{
  content:"파일등록";
}

.box-file-input .file-input{
  display:none;
}

.box-file-input .filename{
  display:inline-block;
  padding-left:10px;
}

/* 모달 전체 배경 검정색 */
.modal-content.bg-black {
    background-color: #000;
    color: #fff;
}

/* 닫기 버튼 스타일 */
.btn-close-white {
    filter: invert(1); /* 흰색 닫기 버튼 */
}


</style>

<!-- Additional CSS Files -->
<link rel="stylesheet" href="assets/css/fontawesome.css">
<link rel="stylesheet" href="assets/css/templatemo-scholar.css">
<link rel="stylesheet" href="assets/css/owl.css">
<link rel="stylesheet" href="assets/css/animate.css">
<link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
<!-- 카카오맵에 필요한 3가지 API -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$APIKEY?>&libraries=LIBRARY"></script>
<!-- services 라이브러리 불러오기 -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$APIKEY?>&libraries=services"></script>
<!-- services와 clusterer, drawing 라이브러리 불러오기 -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$APIKEY?>&libraries=services,clusterer,drawing"></script>

<style>
    .screen_out {display:block;overflow:hidden;position:absolute;left:-9999px;width:1px;height:1px;font-size:0;line-height:0;text-indent:-9999px}
    .wrap_content {overflow:hidden;height:330px}
    .wrap_map {width:50%;height:300px;float:left;position:relative}
    .wrap_roadview {width:50%;height:300px;float:left;position:relative}
    .wrap_button {position:absolute;left:15px;top:12px;z-index:2}
    .btn_comm {float:left;display:block;width:70px;height:27px;background:url(https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/sample_button_control.png) no-repeat}
    .btn_linkMap {background-position:0 0;}
    .btn_resetMap {background-position:-69px 0;}
    .btn_linkRoadview {background-position:0 0;}
    .btn_resetRoadview {background-position:-69px 0;}
</style>

</head>
  
<body>

<form id="board_form" name="board_form" method="post" >

<input type="hidden" id="Lat" name="Lat" value="<?=$Lat?>">
<input type="hidden" id="Lng" name="Lng" value="<?=$Lng?>">
<input type="hidden" id="HomeAddress" name="HomeAddress" value="<?=$HomeAddress?>">

</form>
 
  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->
<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.php" class="logo me-3">
                        <h1 class="me-3" style="width:130%;">미래기업</h1>
                    </a>
                    <!-- ***** Logo End ***** -->

                    <!-- ***** Menu Start ***** -->
                    <ul class="nav align-items-center ">
					<li class="scroll-to-section"><a href="#top" class="active">Home</a></li>					
					<li class="scroll-to-section dropdown">
					  <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">회사안내</a>
					  <div class="dropdown-menu shadow-sm m-0">
						<a href="#about-us" class="dropdown-item text-dark fw-bold">인사말</a>
						<a href="#history" class="dropdown-item text-dark fw-bold">연혁</a>
						<a href="#organization" class="dropdown-item text-dark fw-bold">조직도</a>
						<a href="#location" class="dropdown-item text-dark fw-bold">오시는길</a>
						<a href="#notice" class="dropdown-item text-dark fw-bold">공지사항</a>
					  </div>
					</li>

					<li class="scroll-to-section dropdown">
						<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">사업분야</a>
						<div class="dropdown-menu shadow-sm m-0">
							<a href="#ceiling" class="dropdown-item  text-dark fw-bold">EL 조명천장(Ceiling, Light case)</a>
							<a href="#jambcladding" class="dropdown-item text-dark fw-bold">EL 잠 덧씌우기(Jamb cladding)</a>
							<a href="#sillcover" class="dropdown-item text-dark fw-bold">EL 재료분리대(Sill cover)</a>
						</div>
					</li>
					<li class="scroll-to-section dropdown">
						<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">설비/공정</a>
						<div class="dropdown-menu shadow-sm m-0">
							<a href="#device" class="dropdown-item  text-dark fw-bold">설비</a>
							<a href="#processchart" class="dropdown-item text-dark fw-bold">생산공정</a>
						</div>
					</li>
					<li class="scroll-to-section dropdown">
						<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">품질/안전</a>
						<div class="dropdown-menu shadow-sm m-0">
							<a href="#QCplan" class="dropdown-item  text-dark fw-bold">품질목표/품질방침</a>
							<a href="#iso" class="dropdown-item  text-dark fw-bold">ISO인증서</a>
							<a href="#NG" class="dropdown-item text-dark fw-bold">품질불량</a>
							<a href="#RiskAssessement" class="dropdown-item text-dark fw-bold">위험성평가</a>
						</div>
					</li>
					<li class="scroll-to-section"><a href="#gallery">제품/시공 갤러리</a></li>
					<li class="scroll-to-section">
						<a href="#contact" >견적문의</a>
					</li>					

						<li class="scroll-to-section d-flex justify-content-center align-items-center">							
						<?php if (!$chkMobile): ?>
							<?php if (!isset($_SESSION["name"])): ?>
							<form id="login_form" name="login_form" method="post">
								<input type="text" id="uid" name="uid" class="form-control me-1" style="width: 100px; display: inline-block;" placeholder="ID" required autofocus>
								<input type="password" id="upw" name="upw" class="form-control me-1" style="width: 100px; display: inline-block;" placeholder="Password" required>
								<button id="loginBtn" class="btn btn-dark btn-sm" type="button">로그인</button>
							</form>
							<?php else: ?>									
							<form id="login_form" name="login_form" method="post">											
								<span class="text-white" ><?php echo $_SESSION["name"]; ?> 님 </span>
								<button id="logoutBtn" class="btn btn-dark btn-sm" type="button">로그아웃</button>
							</form>									
							<?php endif; ?>							
						<?php else: ?>							
							<?php if (!isset($_SESSION["name"])): ?>
								<form id="login_form" name="login_form" method="post">
									<input type="text"  id="uid" name="uid" class="form-control me-1" style="width: 120px; display: inline-block;" placeholder="Your ID" required autofocus>
									<input type="password" id="upw" name="upw" class="form-control me-1" style="width: 120px; display: inline-block;" placeholder="Password" required>
									<button id="loginBtn"  class="btn btn-dark btn-sm" type="button">로그인</button>
								</form>
							<?php else: ?>
								<form id="login_form" name="login_form" method="post">
									<span class="text-white" ><?php echo $_SESSION["name"]; ?> 님 </span>
									<button id="logoutBtn" class="btn btn-secondary btn-sm" type="button">로그아웃</button>
								</form>
							<?php endif; ?>							
						<?php endif; ?>
					</li>
                    </ul>
                    <a class="menu-trigger">
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->



<!-- 모달 창 -->
<div class="modal fade" id="youtubeModal" tabindex="-1" aria-labelledby="youtubeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-black text-white">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="youtubeVideo" src="" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-banner" id="top">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="owl-carousel owl-banner">
            <div class="item item-1">
              <div class="header-text">
                <span class="category">세련된 디자인 엘리베이터 조명천장</span>
                <h2>엘리베이터 조명천장</h2>
                <p> 국내외 유수의 엘리베이터 기업들과 협력하여 최고의 품질과 혁신을 담은 엘리베이터 조명천장을 제작합니다. <br> 첨단 기술과 세련된 디자인이 어우러진 조명천장은 품격 있는 공간 연출과 함께 안전하고 탁월한 사용 경험을 제공합니다. <br> 국내 최고를 넘어 글로벌 시장을 선도하는 조명천장 제작의 새로운 기준을 제시합니다. </p>
			<div class="buttons">				
				<div class="icon-button">
					<span class="video1 text-white fw-bold" style="cursor:pointer;" ><i class="fa fa-play"></i> 엘리베이터 조명천장이란? </span>
				</div>
			</div>

              </div>
            </div>
            <div class="item item-2">
              <div class="header-text">
                <span class="category">안전을 위한 엘리베이터 잠(JAMB)</span>
                <h2>엘리베이터 잠(JAMB)</h2>
                <p>엘리베이터 JAMB은 도어와 벽을 연결하는 마감재로, 고급스러운 소재와 정교한 마감 처리로 완성된 품격 있는 디자인을 제공합니다. 건축 공간과 완벽히 조화를 이루며, 내구성과 미적 가치를 동시에 만족시키는 엘리베이터의 중요한 요소입니다.</p>                
				<div class="buttons">				
					<div class="icon-button">
						<span class="video2 text-white fw-bold" style="cursor:pointer;" ><i class="fa fa-play"></i>  엘리베이터 쟘(JAMB)이란? </span>
					</div>
				</div>				
				
              </div>
            </div>
			<div class="item item-3">
			  <div class="header-text">
				<span class="category">내구성과 품격의 SILL cover</span>
				<h3 class="text-white mb-2">엘리베이터 재료분리대<br>(SILL cover)</h3>
				<p>엘리베이터 SILL은 도어 하단에 설치되어 안전하고 원활한 승강을 돕는 핵심 요소입니다. SILL과 홀의 간격을 덮어주는 재료분리대(SILL COVER)는 고강도 소재와 정밀한 설계로 내구성을 극대화했으며, 건축 디자인과 조화를 이루는 세련된 마감으로 품격을 더합니다.</p>
				<div class="buttons">				
					<div class="icon-button">
						<span class="video3 text-white fw-bold" style="cursor:pointer;" ><i class="fa fa-play"></i>   엘리베이터 재료분리대(SILL cover)란? </span>
					</div>
				</div>					
				
			  </div>
			</div>

          </div>
        </div>
      </div>
    </div>
  </div>

<div class="services section" id="services">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6" id="ceilinglist">
        <div class="service-item">
          <div class="icon">
            <img src="assets/images/service-01.png" alt="엘리베이터 조명천장">
          </div>
          <div class="main-content">
            <h4>엘리베이터 조명천장</h4>
            <p>국내외 유수의 엘리베이터 기업들과 협력하여 최고의 품질과 혁신을 담은 엘리베이터 조명천장을 제작합니다. 세련된 디자인과 첨단 기술로 품격 있는 공간을 제공합니다.</p>
			<div class="thumb mb-3">
				<img src="assets/images/ceiling1.jpg" alt="엘리베이터 조명천장">
            </div>
            <div class="main-button">
              <a href="#">더 알아보기</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6" id="jambcladding">
        <div class="service-item">
          <div class="icon">
            <img src="assets/images/service-08.png" alt="엘리베이터 JAMB">
          </div>
          <div class="main-content">
            <h4>엘리베이터 잠(JAMB)</h4>
            <p>엘리베이터 JAMB는 도어와 벽을 연결하는 마감재로, 정교한 설계와 고급 소재를 사용하여 내구성과 미적 가치를 동시에 제공합니다. 건축 공간과 조화를 이루는 디자인을 자랑합니다.</p>
			<div class="thumb mb-3">
				<img src="assets/images/jamb1.jpg" alt="엘리베이터 JAMB">
            </div>			
            <div class="main-button">
              <a href="#">더 알아보기</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6" id="sillcover">
        <div class="service-item">
          <div class="icon">
            <img src="assets/images/service-09.png" alt="엘리베이터 SILL COVER">
          </div>
          <div class="main-content">
            <h4>엘리베이터 재료분리대(SILL COVER)</h4>
            <p>SILL은 도어 하단에 설치되어 승강 안전을 돕고, SILL COVER는 고강도 소재와 정밀한 설계로 내구성을 높이며, 세련된 마감으로 건축미를 완성합니다.</p>
			<div class="thumb mb-3">
				<img src="assets/images/sillcover1.jpg" alt="엘리베이터 SILL COVER">
            </div>
            <div class="main-button">
              <a href="#">더 알아보기</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section about-us" id="about-us">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-1">
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                조명천장이란?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                엘리베이터 조명천장은 세련된 디자인과 첨단 기술이 융합된 제품으로, 승객들에게 편안하고 품격 있는 공간을 제공합니다. 다양한 조명 옵션과 맞춤형 설계로 어느 건축 환경에도 완벽히 어울립니다.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                JAMB란 무엇인가요?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                엘리베이터 JAMB는 도어와 벽 사이를 연결하는 마감재로, 고급스러운 소재와 정교한 설계로 내구성과 미적 가치를 동시에 제공합니다. 건축의 완성도를 높이는 중요한 요소입니다.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Sill Cover(재료분리대)의 역할은?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                Sill Cover는 엘리베이터 도어 하단부를 보호하며, 승강장의 안전과 마감 품질을 높이는 핵심 요소입니다. 높은 내구성과 정밀한 설계로 장기간 안정적인 성능을 제공합니다.
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5 align-self-center">
        <div class="section-heading mt-2 mb-5">
          <h6>인사말</h6>
          <h2>2012년 창립, 지속적인 성장의 발자취</h2>
          <p>2012년 11월 27일 창립 이래, 저희 회사는 엘리베이터 조명천장, JAMB, 재료분리대(Sill Cover)와 같은 의장재를 전문적으로 제조 및 생산하며 지속적인 성장을 이루어왔습니다. 고객의 신뢰를 바탕으로 품질과 혁신을 최우선으로 삼아, 업계 선도 기업으로 자리잡았습니다. 앞으로도 한층 더 나은 제품과 서비스를 제공하며, 믿음직한 파트너로서 최선을 다하겠습니다.</p>
		  
		  <br>
		  <br>
          <h6>CEO 인사말</h6>
		  <br>
          <h4>고객을 최우선으로 생각하는 미래기업</h4>
          <p>“엘리베이터 의장재를 제조 판매 그리고 시공하는 업체 미래기업입니다. 경기침체로 많은 어려움 속에서도 제조인으로서의 갖춰야 할 재료에 대한 고민과 건물의 인테리어에 해당되는 중요한 부품들을 최선을 다해 생산하겠습니다.
			많은 응원과 성원 부탁드립니다.”</p>
			<div class="thumb mt-5 ms-3">
              <img src="img/ceo.png" style="width:30%;" alt="CEO">            
				<span class="ms-2 fw-bold">대표이사 소현철</span>		  
            </div>	  
			<br>			
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section testimonials" id="history">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
        <div class="owl-carousel owl-testimonials">
          <div class="item">
            <p><strong>2012</strong><br>미래기업 설립</p>
            <div class="author">
              <img src="assets/images/miraeHistory1.png" style="width:auto;" alt="">
              <span class="category">설립</span>
            <p><strong>2012</strong><br>
              엘리베리이터 조명천장 회사 미래기업 창립<br>              
              TKE 조명천장 제작 납품
            </p>
            </div>
          </div>
          <div class="item">
            <p><strong>2017</strong><br>
              제비코페인트 대리점 창립<br>
              삼화페인트 대리점 창립<br>
              조광페인트 대리점 창립
            </p>
            <div class="author">
              <img src="assets/images/miraeHistory2.png"  style="width:auto;" alt="">
              <span class="category">대리점 창립</span>
            <p><strong>2017</strong><br>
              제비코페인트 대리점 창립<br>
              삼화페인트 대리점 창립<br>
              조광페인트 대리점 창립
            </p>
          </div>
          </div>
          <div class="item">
            <p><strong>2018</strong><br>인천 경제산업 정보 테크노파크 표창장 수여</p>
            <div class="author">
              <img src="assets/images/miraeHistory3.png"  style="width:auto;" alt="">
              <span class="category">수상</span>
              <h4>2018</h4>
            </div>
          </div>
          <div class="item">
            <p><strong>2019</strong><br>
              명성산업 서구공장 확장 및 이전<br>
              도장설비 증설 (분체LINE/엑체LINE)<br>
              URS 인증원 IATF:16949 인증<br>
              유해화학물질 판매업 허가<br>
            </p>
            <div class="author">
              <img src="assets/images/miraeHistory4.png"  style="width:auto;" alt="">
              <span class="category">확장 및 수상</span>
              <h4>2019</h4>
            </div>
          </div>
          <div class="item">
            <p><strong>2020</strong><br>
              레이저 컷팅기 증설<br>
              절곡기 HDS-1303, RG80 증설<br>
              조달청 입찰자격 등록<br>
              용접기, 금형 증설
            </p>
            <div class="author">
              <img src="assets/images/miraeHistory5.png" style="width:auto;" alt="">
              <span class="category">설비 증설</span>
              <h4>2024</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5 align-self-center">
        <div class="section-heading mt-2 mb-3">
          <h6>연혁</h6>
          <h2>2012년 창립</h2>
          <p>2012년 11월 27일 창립 이래, 저희 회사는 엘리베이터 조명천장, JAMB, 재료분리대(Sill Cover)와 같은 의장재를 전문적으로 제조 및 생산하며 지속적인 성장을 이루어왔습니다. 고객의 신뢰를 바탕으로 품질과 혁신을 최우선으로 삼아, 업계 선도 기업으로 자리잡았습니다. 앞으로도 한층 더 나은 제품과 서비스를 제공하며, 믿음직한 파트너로서 최선을 다하겠습니다.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section" id="organization">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 align-self-center">
        <div class="section-heading mt-2 bm-3">
		 <h6>조직도</h6>
		  <h2>회사 조직</h2>
          <p> </p>          
        </div>
      </div>
      <div class="col-lg-8 offset-lg-1">
	   <img src="assets/images/org.jpg" alt="조직도">
      </div>
    </div>
  </div>
</div>

<div class="section" id="location">
  <div class="container">
    <div class="row ">
		<?php if (!$chkMobile): ?>						
        <div class="accordion " >
          <div class="accordion-item justify-content-center align-items-center">                  
	    <div class="d-flex justify-content-center">
			<div class="wrap_map">
			<div id="map" style="width:100%;height:100%"></div> <!-- 지도를 표시할 div 입니다 -->
			<div class="wrap_button">
				<a href="javascript:;" class="btn_comm btn_linkMap" target="_blank" onclick="moveKakaoMap(this)"><span class="screen_out">지도 크게보기</span></a> <!-- 지도 크게보기 버튼입니다 -->
				<a href="javascript:;" class="btn_comm btn_resetMap" onclick="resetKakaoMap()"><span class="screen_out">지도 초기화</span></a> <!-- 지도 크게보기 버튼입니다 -->
			</div>
		</div>    
		<div class="wrap_roadview">
			<div id="roadview" style="width:100%;height:100%"></div> <!-- 로드뷰를 표시할 div 입니다 -->
			<div class="wrap_button">
				<a href="javascript:;" class="btn_comm btn_linkRoadview" target="_blank" onclick="moveKakaoRoadview(this)"><span class="screen_out">로드뷰 크게보기</span></a> <!-- 로드뷰 크게보기 버튼입니다 -->
				<a href="javascript:;" class="btn_comm btn_resetRoadview" onclick="resetRoadview()"><span class="screen_out">로드뷰 크게보기</span></a> <!-- 로드뷰 리셋 버튼입니다 -->
			</div>
		</div>  
         <div class="section-heading mt-1" style="width:60%;">
		    <h4 class="mt-1 ms-2">오시는 길</h4>
			<img src="data:image/jpeg;base64,/9j/4QC8RXhpZgAASUkqAAgAAAAGABIBAwABAAAAAQAAABoBBQABAAAAVgAAABsBBQABAAAAXgAAACgBAwABAAAAAgAAABMCAwABAAAAAQAAAGmHBAABAAAAZgAAAAAAAABgAAAAAQAAAGAAAAABAAAABgAAkAcABAAAADAyMTABkQcABAAAAAECAwAAoAcABAAAADAxMDABoAMAAQAAAP//AAACoAQAAQAAAEAGAAADoAQAAQAAAIQDAAAAAAAA/+EN/Wh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8APD94cGFja2V0IGJlZ2luPSfvu78nIGlkPSdXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQnPz4KPHg6eG1wbWV0YSB4bWxuczp4PSdhZG9iZTpuczptZXRhLyc+CjxyZGY6UkRGIHhtbG5zOnJkZj0naHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyc+CgogPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9JycKICB4bWxuczpBdHRyaWI9J2h0dHA6Ly9ucy5hdHRyaWJ1dGlvbi5jb20vYWRzLzEuMC8nPgogIDxBdHRyaWI6QWRzPgogICA8cmRmOlNlcT4KICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0nUmVzb3VyY2UnPgogICAgIDxBdHRyaWI6Q3JlYXRlZD4yMDIzLTA4LTI4PC9BdHRyaWI6Q3JlYXRlZD4KICAgICA8QXR0cmliOkV4dElkPjA0MWIzMWQyLWMxMTQtNGYzNC04OWQ2LWIwMzg1MWQzZDYyNzwvQXR0cmliOkV4dElkPgogICAgIDxBdHRyaWI6RmJJZD41MjUyNjU5MTQxNzk1ODA8L0F0dHJpYjpGYklkPgogICAgIDxBdHRyaWI6VG91Y2hUeXBlPjI8L0F0dHJpYjpUb3VjaFR5cGU+CiAgICA8L3JkZjpsaT4KICAgPC9yZGY6U2VxPgogIDwvQXR0cmliOkFkcz4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJz4KICA8ZGM6dGl0bGU+CiAgIDxyZGY6QWx0PgogICAgPHJkZjpsaSB4bWw6bGFuZz0neC1kZWZhdWx0Jz7rr7jrnpjquLDsl4Ug7LC+7JWE7Jik64qU6ri4IC0gMTwvcmRmOmxpPgogICA8L3JkZjpBbHQ+CiAgPC9kYzp0aXRsZT4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6cGRmPSdodHRwOi8vbnMuYWRvYmUuY29tL3BkZi8xLjMvJz4KICA8cGRmOkF1dGhvcj5UaW5hIFNhbjwvcGRmOkF1dGhvcj4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6eG1wPSdodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvJz4KICA8eG1wOkNyZWF0b3JUb29sPkNhbnZhPC94bXA6Q3JlYXRvclRvb2w+CiA8L3JkZjpEZXNjcmlwdGlvbj4KPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKPD94cGFja2V0IGVuZD0ndyc/Pv/bAEMABgQFBgUEBgYFBgcHBggKEAoKCQkKFA4PDBAXFBgYFxQWFhodJR8aGyMcFhYgLCAjJicpKikZHy0wLSgwJSgpKP/bAEMBBwcHCggKEwoKEygaFhooKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKP/AABEIA4QGQAMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/APoL4l/8iLrX/Xs/8q0vC3/ItaT/ANesX/oIrO+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIoA1aKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKzvEX/IC1D/rg/8AI1o1neIv+QFqH/XB/wCRoAx/hf8A8iFov/Xun8q6muW+F/8AyIWi/wDXun8q6mgAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooADXmfwc/5Cfjf/sMv/6LSvTDXmfwc/5Cfjf/ALDL/wDotKAPTKKKKAGT/wCqNZOp/wDIOuf+ubfyrWn/ANUaydT/AOQdc/8AXNv5UxFL4l/8iLrX/Xs/8q0fC3/ItaT/ANesX/oIrO+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACs7xF/yAtQ/64P8AyNaNZ3iL/kBah/1wf+RoAx/hf/yIWi/9e6fyrqa5b4X/APIhaL/17p/KupoAKKKQ0ABbAqP7RFu2+Ym703DNeUfEzWNU1nxxpfgnQ7x7ETxG5vblB86xg4Cr7nmrzfB3QPs37q71ZLzHF19sfdn1x0pJ31A9M3VGLqH7R5HnR+fjd5e4bseuOteZfCbW9Ui1nWvCniC5+13mmENFckYMsR6Z965rxT4jPh347+e1jqF+raft8qzTew98ZHFMD3jNGRWR4b1ga5pUV6LO6sw+f3V0mxxj1FZnjLxFqWheR/Zvh+81jzM7vs7ouz67iKGC1OkkuYY2xJNGh9GYCmi9tiQBcQkn/bFeCeLbk61cyanrfw918MifM63yRqFHsHrmPCl3oXiCI3ugeBdfu44ZNpdNRHysPUF6SBn1SCCKXNecaF421y4vrWzm8E6raW7EIZpJYyEGOpw2a9EySKbEmKWA71SudY060YrdahZwH0kmVf5mvL/jFq183inw14dW/k0vS9SkYXF0h2lsdEDds1uW3wk8IKg8+xkvG7vcTs5b9aSdxnXxeINHmbbDq2nyN6Lcof61opIrgFWDA9wc1wcnwk8GMuF0dIz6xyMp/nXGXcL+APiT4f0vw7qFzc2eosVuLCaXzPKH98Z5Ap9RHuOaU0xaeaBhmoLq6gtYWmuZo4Yl+88jBVH1Jp07OsbmNdzgEqpOMn0rx7xb4l8YzWF1aa34D0ltMc7S1xraxq4ByM5XilcLHpw8T6D31vTP/AuP/GpbXXtJu51htNUsJ5W6JFcIzH8Aa+WptZ0aFysngHwuG9vEAP8AJa2/CmtGDU4brw74C8NC9Q/IY/EKbvwBXmmgZ9OZozXJ+CtY8T6o8/8Awk3h2DR1UDyzHeiff+SjFdXmgQpOBnpSK4YZUgj1Fcr8T9WTRvAesXjyeWUgYK2cHJ4GKrfCBZV+HOhm4maaVoAzOzbicknrSWo3odkzYGTwKjt7qG5QvbzRyoDgsjBhn04qDWMjSrwjr5L/AMjXmP7Nju/ge5LszH7dLyTnvTWonoetZpN6jgsM/WkPIxXnup/DPSbu9muJ9a1mJ5WL7VvNoH0GKVxnofmL/eH50GQf3l/OvMv+FV6J/wBB/Wv/AAO/+tWZ4m+HWlaboN9eWviPWEngiZ0JvQQCB9KYHsQOaWvPfgTrN9rnw20q91OYz3LB1MjdWCsQCfwFehUWsJATgU3dQ1eKeOPFXinxH45fwd4Elisvs0fmXt/KM7AegUUrjPa91G6vBtZ8J/EvwjYvrGleMBrbQKZZrO4gEYdRyQpBP9K9Q+HHi2Dxl4UtNXhQxM4xLGf4HHUU+gHU7qM14Lda74x+JnivU9P8IajHoug6dIYJLwrvkkYdcCoPEFv8QvhfGmtN4gHiTRo2H2qGaHy5FXuRgn+dCA+gdxo3Gs3RNXttX0O11S1b/RriFZlJ7AjPNeM3OseLvif4o1Cz8I6ouieHdPk8l70JveaQddo9PxpdbAe8buKXOa8I03XfFfw08XWGleM9SXWNB1JvKgv9mx45OwYehr3WNgyhlOQRkGmIfRmikoGIWoDGqup3kWn2FxdznEUKF2+gFeD6A/xA+KzTatZ62PDfh9pGW1WOLzJHUHGSCR6etK4H0Fupc5714FJqvjX4W69p6eJ9VTXvDd5KIftOzZJCx6ZH/wBevZdf019c0hre3v7ixMmGWe3xuA/Gn0EbGaTdXmh+GeoAf8jtrv8A45XnLWviK6+J9roPhTxVqd5BZMJdSuJ9pRBn7gwOSaF2H0ufSWeaWoogVRVY7iBgn1qWgAooooADXmfwc/5Cfjf/ALDL/wDotK9MNeZ/Bz/kJ+N/+wy//otKAPTKKKKAGT/6o1k6n/yDrn/rm38q1p/9UaydT/5B1z/1zb+VMRS+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIrO+Jf/ACIutf8AXs/8q0fC3/ItaT/16xf+gikM1aKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKzvEX/IC1D/AK4P/I1o1neIv+QFqH/XB/5GgDH+F/8AyIWi/wDXun8q6muW+F//ACIWi/8AXun8q6mgApDS0negDx34nafqPhzx9pfjjTbKa+tI4TaX0EI3OqZyHA745rYk+M/g4WZlS+nkuMZFqttIZSf7u3b1rsfFXiDS/DWky6hrVwkFqnGSMlj2AHc15xF4z1jVZPtHhv4c3U8DcpcXapb7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W+k2yq7cyTNy8jerHvXRin0sHW5yXxT8TL4V8F31+p/0kgQ2692kbgAVlfBbwmfDfhZbi8+fVdRP2i6kPUseQPwrkfiXK3iz4xeGPCyEtaWKtqFyo6EjAXNe3xoI1CqMADAFJdxPsOA5p1IKWmMKKKKAA15n8HP+Qn43/wCwy/8A6LSvTDXmfwc/5Cfjf/sMv/6LSgD0yiiigBk/+qNZOp/8g65/65t/Ktaf/VGsnU/+Qdc/9c2/lTEUviX/AMiLrX/Xs/8AKtHwt/yLWk/9esX/AKCKzviX/wAiLrX/AF7P/KtHwt/yLWk/9esX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACs7xF/yAtQ/wCuD/yNaNZ3iL/kBah/1wf+RoAx/hf/AMiFov8A17p/Kuprlvhf/wAiFov/AF7p/KupoAKQ9aWigDzf4zeFdS8Rabptzo6Rz3Wm3S3ItpD8swHUfWs2P4x6dpSLB4m0XV9JuEGGVrVmX8CB0r1ikIHpSSsB5Ufjl4WmOzTotVvZjwI4rN8k/lVbwppes+JviRD4vv8AS5dGsreBoooZhtlmyMZYV68qgdAAPSlx6U1oJ6ic15J8bbK/h1jwvr1lp9zfW+m3BeeO3Qs4U45xXrp6UmM1PmM8sHxjtMc+GvEP/gG3+FKPjJZj/mW/EP8A4Bt/hXqVLVAec6P8VLbVNTt7OPw/rsLTNtDy2jBV9ycV6KORzQRSigDw39oKK3tvE3hDVtXsnutFtZnFziLzAoI4yKrDx38HeM6Un/grb/CveWAI5AI96aIY/wDnmv5Ul2B6nhB8dfBztpSf+Ctv8KyLC78N+Jviv4Zl8CaY0dvZ7nupFtDCAO2eK+jzDH/cX8qERV+6oH0FPqA4dKdQBRSAjcEqQDgnpXgmm2lx4D8RazeeKvDl1rKXk5ki1KCHzyif3T3Fe/HmkxR5geRJ8aPAcI2SRXcDDqhsG4/Sobn4n+FNaRoNM8OahrEjjAjTTzz+Yr2Iop6gH6ilAFAHmfwU8OapoVpqkuo232C2vLgzW1huz9nQ9vb6V6YelAFKaGI8D02+v/h38RfFV1qWh6neWeqTLNBPaQmRcY5Bx3rqR8Y7P/oW/EP/AIBt/hXqWOaXFMZ5WfjFZ5/5FrxF/wCAbf4V13gvxVF4otZpodOv7IRsFIu4TGWz6Z6102KTGCaBGN4om1qDTC3h22tri93DCXDlVx35Fcd/aXxQ/wCgJof/AIENXpeOKUUhnhfi3QviT4j1XRb6bTNHifTJ/PRVnOHPoa6gal8T/wDoCaH/AN/2r0zFBpgcT4ZvfHE2pBPEOm6Xb2W05eCUs2fpXaDrS4ooEI3Q4rwG10v4keH/AB34i1jS/DljqCag6iOWe5CsqL0A5969/wAUAYpdbjPHf+Ek+L3/AEJulf8AgYP8a7bwpe+J7/Qbl/Eml2+n6lyI4oZd4PpzXWikxR5AfO3gXT/il4Ot72G08LaddvdXDzvcS3YDMSSeea6c+JPi7/0Julf+Bg/xr2ICl7UwsY3hubU7vRIJNetIrS/Zf3sMbblU+gNeWaj4M8XeDfFV9q/w8W1urHUG8y4064faof8AvKe1e10Yo63A8W0XwT4r8WeL7LX/AIi/Zre3sCWtdOt33IH/ALxPevZ0AAAAwBTsUoFAgpKWigZzfxAtdRvfB+rWuipu1CaBkiBbb8xHHNeTeDX+Kvhbw7ZaRZ+DtLaK2QJvN2AWPcnnrXvmKAKSVgPH4/EfxcLqH8G6UEyMkXg6fnXq9k0z2cLXUax3DIDIgOQrY5GatGm4oA8x8IeDtUtPiv4l8SaqiC3uY44bMhgTtHLfTtXpwFLilph5iYpaKKACiiigANeZ/Bz/AJCfjf8A7DL/APotK9MNeZ/Bz/kJ+N/+wy//AKLSgD0yiiigBk/+qNZOp/8AIOuf+ubfyrWn/wBUaydT/wCQdc/9c2/lTEUviX/yIutf9ez/AMq0fC3/ACLWk/8AXrF/6CKzviX/AMiLrX/Xs/8AKtHwt/yLWk/9esX/AKCKQzVooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigArO8Rf8gLUP+uD/wAjWjWb4i/5AV//ANcH/kaAMj4X/wDIhaL/ANe6fyrqa4D4a+ItHh8EaRHLqdojrbqGVpQCOK6f/hJ9D/6C1j/3+WgDYorH/wCEn0P/AKC1l/3+Wj/hJ9D/AOgtZf8Af5aANjFFY/8Awk+h/wDQWsv+/wAtH/CT6H/0FrL/AL/LQBsYorH/AOEn0P8A6C1l/wB/lo/4SfQ/+gtZf9/loA2KKx/+En0P/oLWX/f5aP8AhJ9D/wCgtZf9/loA2MUYrH/4SfQ/+gtZf9/lo/4SfQ/+gtZf9/loA2MUVj/8JPof/QWsv+/y0f8ACT6H/wBBay/7/LQBsYorH/4SfQ/+gtZf9/lo/wCEn0P/AKC1l/3+WgDYoxWP/wAJPof/AEFrL/v8tH/CT6H/ANBay/7/AC0AbFFY/wDwk+h/9Bay/wC/y0f8JPof/QWsv+/y0AbFGKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2KKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2KKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2KKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANjFGKx/wDhJ9D/AOgtZf8Af5aP+En0P/oLWX/f5aANijFY/wDwk+h/9Bay/wC/y0f8JPof/QWsv+/y0AbFGKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2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UAUfgf8AHS98JSx6V4kklvNGYhVcnc8H+Ir7N0TVrLWtMg1DS7mO4tJl3JIjZBrC/wCFdeEf+he03/vwtb+l6XZ6VZpa6dbR21uhysca4UfhQBdooooAKKKKACiiigAooooAK5jx7q91oukW9xZlRI93DCdwz8rOAf0NdPXD/Fr/AJF60/6/7b/0atAHax5KgnuKfTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/AOPvxX/2FX/9AWu+NcD8K/8Aj78V/wDYVf8A9AWgDvqKKKAGT/6o1k6n/wAg65/65t/Ktaf/AFRrJ1P/AJB1z/1zb+VMRS+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIrO+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/8AYTb/ANAWu5rhvhp/yEfFf/YTb/0Ba7mgAooooAKKKKACiiigAooooAKKKKAPmH9tv/kFeHv+uz/yr5Jr62/bb/5BXh//AK7P/KvkmgAr6Z/Yk/5D3iH/AK4R/wAzXzNX0z+xJ/yHvEX/AFwj/maAPruiiigAooooAKKKKACiiigAooooAK4j4tf8i9af9hC2/wDRq129cR8Wv+RetP8AsIW3/o1aAO1i/wBWv0FOpsX+rX6CnUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUU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wq//AKAtAHfUUUUAMn/1RrJ1P/kHXP8A1zb+Va0/+qNZOp/8g65/65t/KmIpfEv/AJEXWv8Ar2f+VaPhb/kWtJ/69Yv/AEEVnfEv/kRda/69n/lWj4W/5FrSf+vWL/0EUhmrRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUHpRQelAHDfDT/kI+K/+wm3/oC13NcN8NP+Qj4r/wCwm3/oC13NABRRRQAUUUUAFFFFABRRRQAUUUUAGKKKjnkWKJ5HOFUEk0ASdaMCqGhapa61pdvqFg5ktp1DoxGMir9ABRRRQAUUUUAFFFFABRRRQAUUUUAFcR8Wv+RetP8AsIW3/o1a7euI+LX/ACL1p/2ELb/0atAHaxf6tfoKdTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigArh/izcY8P21gp/eX95DbqPX5gx/RTXbmvPvFinVPiV4Y08fNFZrLfyr6YGxf1egDvbSIQ20UYHCqF/SpaRaWgAooooAKKKKACg0UhoA4P4rSefbaLpS/ev9QiQgf3Vbef0Wu7jUKir6ACuC1Rf7T+LWkwf8s9NtJbhh23NhR+hNd8KAFooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/AOPvxX/2FX/9AWu+NcD8K/8Aj78V/wDYVf8A9AWgDvqKKKAGT/6o1k6n/wAg65/65t/Ktaf/AFRrJ1P/AJB1z/1zb+VMRS+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIrO+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/8AYTb/ANAWu5rhvhp/yEfFf/YTb/0Ba7mgAooooAKKKKACiiigAooooAKKKKACqmrD/iW3X/XJv5Vbqrqpxpt1/wBc2/lQByvwa/5JtoX/AF7r/Ku0ri/g1/yTbQv+vdf5V2lABRRRQAUUUUAFFFFABRRRQAUUUUAFcR8Wv+RetP8AsIW3/o1a7euI+LX/ACL1p/2ELb/0atAHaxf6tfoKdTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAorL8Qa/p2gWon1O4WINwiDlnPsBz/AErz69+L8CyEWOkySJ2aaYIfyAP86xnXp09JMaTZ6rRXlGlfGS0uLiSO+0qaFI2Cl4pRJ2z0IHr616PousWGt2YudMuUni6HHBU+hHUH604VoT0iwaaNCiiitRBRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFACGuG8Nj7f8R/EN8eVtoY7RG9OSzD9BXbTSCKJ3YgKoLE1xPwmzcaRqGpuPmv7ySXPqBwP5UAd0OlFAooAKKKKACiiigApDS1XvrhbSynuJDhIkLsfQAZoA43wWPtvjHxPqPVVkS1UnsFzmu6FcR8IonPhQXsw/e30z3DH1yeK7egAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/AOPvxX/2FX/9AWu+NcD8K/8Aj78V/wDYVf8A9AWgDvqKKKAGT/6o1k6n/wAg65/65t/Ktaf/AFRrJ1P/AJB1z/1zb+VMRS+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIrO+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/8AYTb/ANAWu5rhvhp/yEfFf/YTb/0Ba7mgAooooAKKKKACiiigAooooAKKKKACmSxrIjI4yrDBHqKfRQBV0ywttMsYrOxiWG2iXaiL0UVaozRQAUUUUAFFFFABRRRQAUUUUAFFFFABXEfFr/kXrT/sIW3/AKNWu3riPi1/yL1p/wBhC2/9GrQB2sX+rX6CnU2L/Vr9BTqACiiigAooooAKKKKACiiigArL8TavFoeh3eoS4PlJ8in+Jzwo/PFaleY/HW6ZNK0y1B+WWZpD/wABXH/s1ZV5+zg5DSuzw3WfEerX2uy3OvyrI0xJVuiovOFUdh7VbRldQykEEZFR3NvHcwmOZdyn9KyLVn0++EE7AI3RgMBhjj8a8WTVXXqa7F6xI+23ygjIdT+YrpvC3iC68OatHeWrHZkCaMnCyJ3B/oexrkra5VNRvnlKomF5PHTNAaXU3+TKWOeSeC+Ow9qLOMubYD7A029t9SsLe9s5Fkt50EiMD1BqzXBfBa78/wAGrb8AWk7xKPRThh/6Ea72vbpT54KRk1YKKKK0EFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRQaAOd+IF6dP8G6tOn+s8hkT/ebgfzqXwRp40zwpploOqQKT9Tyf51ifFN/O0/StNB+a9v40/Bcsf5V2sShI1UdFAFADxRRRQAUUUUAFFFFABXJ/FC6a18EamImxLcILdPq5C/1rrDXB/EtvtV94a0peftV+ruv+yils/mBQB1XhyzXT9DsbRBtWKFVx+FaVNUYUAU6gAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/4+/Ff/AGFX/wDQFrvjXA/Cv/j78V/9hV//AEBaAO+ooooAZP8A6o1k6n/yDrn/AK5t/Ktaf/VGsnU/+Qdc/wDXNv5UxFL4l/8AIi61/wBez/yrR8Lf8i1pP/XrF/6CKzviX/yIutf9ez/yrR8Lf8i1pP8A16xf+gikM1aKKKACiiigAooooAKKKKACiiigAooooAKKKKACg9KKD0oA4b4af8hHxX/2E2/9AWu5rhvhp/yEfFf/AGE2/wDQFrua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kH8qkrQQUUUUAFZ3iC6Flol7ck/6uJm/StE1xfxYujD4QmgT/WXciW6Y9WNAFj4X2P2DwRpikfPMhuHz/edi5/8AQq6yq2nwC2sbeBRhYo1QfgMVZoAKKKKACiiigAoooNAGL4xvPsHhnUbjdgrC2D7niofANiNP8IaVb7drCEOw/wBpvmP6msb4tTk6DaaenMmoXccAHcjOT/Kuzt0EUEca/dRQo/AUATGsHxd4ig8O6YbiQGW4c7ILdfvSueigVc17WLXRNLmv7+QRwxDPuT2A9zXJeE9Iudc1YeKPEKMJSuLC1b7tuh/ix/ePrQBd8E+Hrm3uJtc10rJrd4vzdxAn9xfSuwH60oAFYXi/xFbeHNKe6mBknY7IIF5aVzwFA+tAGZ458QXNoYNI0NVl1q9+SMdREvd29hTU8PweHPAl/aQkyTNC8k0zfelkIyzE/WneBvD1xZNcaxrTedrd988rHpEO0a+gFbXiv/kWtS/64P8AyoAz/hn/AMiFoP8A16R/+giunrmPhn/yIWg/9ekf/oIrp6ACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACg0UGgDh/Fn/I/wDhT6y/+g13FcP4s/5H/wAK/WX/ANBruKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigANcD8K/+PvxX/wBhV/8A0Ba741wPwr/4+/Ff/YVf/wBAWgDvqKKKAGT/AOqNZOp/8g65/wCubfyrWn/1RrJ1P/kHXP8A1zb+VMRS+Jf/ACIutf8AXs/8q0fC3/ItaT/16xf+gis74l/8iLrX/Xs/8q0fC3/ItaT/ANesX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/9hNv/QFrua4b4af8hHxX/wBhNv8A0Ba7mgAooooAKKKKACiiigAooooAKKKKACq2ou0djO6HDKhIPvirNVdV/wCQbdf9c2/lQBgfDDULrVfAukXt/KZbmaBWdz1JxXU1xfwb/wCSa6F/17r/ACrtKACiiigAooooAKKKKACiiigAooooAK4j4tf8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f8nKxf8AYbT/ANGCvvbtXwTN/wAnKxf9htP/AEYKAPvZfuilpF+6KWgAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/wCPvxX/ANhV/wD0Ba741wPwr/4+/Ff/AGFX/wDQFoA76iiigBk/+qNZOp/8g65/65t/Ktaf/VGsnU/+Qdc/9c2/lTEUviX/AMiLrX/Xs/8AKtHwt/yLWk/9esX/AKCKzviX/wAiLrX/AF7P/KtHwt/yLWk/9esX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/9hNv/AEBa7muG+Gn/ACEfFf8A2E2/9AWu5oAKKKKACiiigAooooAKKKKACiig0AGaDzXAfFX4naX8OILKXVrW5nW6Yqvk44wO+a86H7U3hT/oF6p+S/40AfQYpcivns/tTeFD/wAwvVP/AB3/ABruPhV8W9K+I9/e22k2dzb/AGVFdmmxzknpj6UAemUUDpRQAUUUUAFFFFABRRRQAUUUUAFcR8Wv+RetP+whbf8Ao1a7euI+LX/IvWn/AGELb/0atAHaxf6tfoKdTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACmTf6p/oafTJv9U/0NA1ufG97J515PLu3b5GbPrk5r1P9n24MGt39u3S5t/MX/gDAH/0OvKriMRXEsYzhGK8+xr3P4CW1rNoU10YVN3BPJEsncKwQkfTgflXk4T+KfpHEj5cuSXketCigUV6x+bBRRWB4y8UWPhfSnu718ueIogfmkb0H+NJtRV2XTpyqyUIK7YeMvFFj4X0pru9fLniKIH5pG9B/jXzJ4o8QX3iTVZL7UJMseEjH3Y19BR4o8QX3iTVZL7UJMseEjH3Y19BWRXkYjEOo7LY/TcjyOOAj7SprN/gFFFWtL0+61S/hs7CFprmVtqoo/X2HvXMk27I+gqVI04uc3ZINL0+61S/hs7CFpriU7VRf88D3r6U+HPgi18K2G99s2pSr++mx0/2V9B/P+Ufw78DWvhW0EsgWbU5FxLN6d9q+g/n/LthXrYbDKmuaW5+a57n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RRRQAUUVheK/FOm+GbE3GozgMQfLiXl5D6Af5FJtJXZdOnOrJQgrtlvxBrFpoWlz39/KI4Yhk9yT2AHqTXy54x8RXPifW5r+6JVD8sMWeI07D6+vvVnxv4wv/Fd/5tyTFaIf3Nupyqe59T71Q8MaDeeI9WisLBMu3Lufuxr3Y15deu60uSGx+iZNlMMrpPFYp+9b7kdD8KfCjeJPECSXMZOm2hDzEjhz2T+p9vrX0yqhVCgYA4rI8K6BaeHNHgsLFcIgyzn7zserH3rYruoUVSjbqfHZxmUswxDn9lbBRRRW55IUUUUAFFFFABRRRQAUUUUAFFFFABRUNzd21s0S3NxDC0rBIxI4Xex6AZ6n2qagAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigANcD8K/+PvxX/wBhV/8A0Ba741wPwr/4+/Ff/YVf/wBAWgDvqKKKAGT/AOqNZOp/8g65/wCubfyrWn/1RrJ1P/kHXP8A1zb+VMRS+Jf/ACIutf8AXs/8q0fC3/ItaT/16xf+gis74l/8iLrX/Xs/8q0fC3/ItaT/ANesX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACvmTxjGI/2jYFUnB1Sxbn3ER/rX03Xzh8QYfK/aI0p8L+9vbB+P8AeRef++aiY0fR9FFFWIKKKKACiiigAooooAKDyMGiigDndY8EeGdYJbUNEsZHPWRYxG5/4EuD+tcbq3wN8LXe42T31g3YRy71H4OCf1r1SilZAeGf8KCeMn7N4qliU9vsf+EgqB/gx4nscvpHiwCQdMtLD+qlq96opcqHc8ADfGHwx8mJNVt16HCXO78f9Z+dTwfFPx9Cdl74Od3H92znjP65r3iijl8xHia/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">
         </div>
        </div>
      </div>  	        
      </div>
<?php else: // 모바일 일때 ?>	   
        <div class="accordion mt-1" >
          <div class="accordion-item justify-content-center align-items-center mt-1">                  
        <div class="section-heading mt-1 mb-3">
		<br>
		  <h4 class="mt-3 ms-2">오시는 길</h4>
			<img src="data:image/jpeg;base64,/9j/4QC8RXhpZgAASUkqAAgAAAAGABIBAwABAAAAAQAAABoBBQABAAAAVgAAABsBBQABAAAAXgAAACgBAwABAAAAAgAAABMCAwABAAAAAQAAAGmHBAABAAAAZgAAAAAAAABgAAAAAQAAAGAAAAABAAAABgAAkAcABAAAADAyMTABkQcABAAAAAECAwAAoAcABAAAADAxMDABoAMAAQAAAP//AAACoAQAAQAAAEAGAAADoAQAAQAAAIQDAAAAAAAA/+EN/Wh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8APD94cGFja2V0IGJlZ2luPSfvu78nIGlkPSdXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQnPz4KPHg6eG1wbWV0YSB4bWxuczp4PSdhZG9iZTpuczptZXRhLyc+CjxyZGY6UkRGIHhtbG5zOnJkZj0naHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyc+CgogPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9JycKICB4bWxuczpBdHRyaWI9J2h0dHA6Ly9ucy5hdHRyaWJ1dGlvbi5jb20vYWRzLzEuMC8nPgogIDxBdHRyaWI6QWRzPgogICA8cmRmOlNlcT4KICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0nUmVzb3VyY2UnPgogICAgIDxBdHRyaWI6Q3JlYXRlZD4yMDIzLTA4LTI4PC9BdHRyaWI6Q3JlYXRlZD4KICAgICA8QXR0cmliOkV4dElkPjA0MWIzMWQyLWMxMTQtNGYzNC04OWQ2LWIwMzg1MWQzZDYyNzwvQXR0cmliOkV4dElkPgogICAgIDxBdHRyaWI6RmJJZD41MjUyNjU5MTQxNzk1ODA8L0F0dHJpYjpGYklkPgogICAgIDxBdHRyaWI6VG91Y2hUeXBlPjI8L0F0dHJpYjpUb3VjaFR5cGU+CiAgICA8L3JkZjpsaT4KICAgPC9yZGY6U2VxPgogIDwvQXR0cmliOkFkcz4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJz4KICA8ZGM6dGl0bGU+CiAgIDxyZGY6QWx0PgogICAgPHJkZjpsaSB4bWw6bGFuZz0neC1kZWZhdWx0Jz7rr7jrnpjquLDsl4Ug7LC+7JWE7Jik64qU6ri4IC0gMTwvcmRmOmxpPgogICA8L3JkZjpBbHQ+CiAgPC9kYzp0aXRsZT4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6cGRmPSdodHRwOi8vbnMuYWRvYmUuY29tL3BkZi8xLjMvJz4KICA8cGRmOkF1dGhvcj5UaW5hIFNhbjwvcGRmOkF1dGhvcj4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6eG1wPSdodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvJz4KICA8eG1wOkNyZWF0b3JUb29sPkNhbnZhPC94bXA6Q3JlYXRvclRvb2w+CiA8L3JkZjpEZXNjcmlwdGlvbj4KPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKPD94cGFja2V0IGVuZD0ndyc/Pv/bAEMABgQFBgUEBgYFBgcHBggKEAoKCQkKFA4PDBAXFBgYFxQWFhodJR8aGyMcFhYgLCAjJicpKikZHy0wLSgwJSgpKP/bAEMBBwcHCggKEwoKEygaFhooKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKP/AABEIA4QGQAMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/APoL4l/8iLrX/Xs/8q0vC3/ItaT/ANesX/oIrO+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIoA1aKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKzvEX/IC1D/rg/8AI1o1neIv+QFqH/XB/wCRoAx/hf8A8iFov/Xun8q6muW+F/8AyIWi/wDXun8q6mgAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooADXmfwc/5Cfjf/sMv/6LSvTDXmfwc/5Cfjf/ALDL/wDotKAPTKKKKAGT/wCqNZOp/wDIOuf+ubfyrWn/ANUaydT/AOQdc/8AXNv5UxFL4l/8iLrX/Xs/8q0fC3/ItaT/ANesX/oIrO+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACs7xF/yAtQ/64P8AyNaNZ3iL/kBah/1wf+RoAx/hf/yIWi/9e6fyrqa5b4X/APIhaL/17p/KupoAKKKQ0ABbAqP7RFu2+Ym703DNeUfEzWNU1nxxpfgnQ7x7ETxG5vblB86xg4Cr7nmrzfB3QPs37q71ZLzHF19sfdn1x0pJ31A9M3VGLqH7R5HnR+fjd5e4bseuOteZfCbW9Ui1nWvCniC5+13mmENFckYMsR6Z965rxT4jPh347+e1jqF+raft8qzTew98ZHFMD3jNGRWR4b1ga5pUV6LO6sw+f3V0mxxj1FZnjLxFqWheR/Zvh+81jzM7vs7ouz67iKGC1OkkuYY2xJNGh9GYCmi9tiQBcQkn/bFeCeLbk61cyanrfw918MifM63yRqFHsHrmPCl3oXiCI3ugeBdfu44ZNpdNRHysPUF6SBn1SCCKXNecaF421y4vrWzm8E6raW7EIZpJYyEGOpw2a9EySKbEmKWA71SudY060YrdahZwH0kmVf5mvL/jFq183inw14dW/k0vS9SkYXF0h2lsdEDds1uW3wk8IKg8+xkvG7vcTs5b9aSdxnXxeINHmbbDq2nyN6Lcof61opIrgFWDA9wc1wcnwk8GMuF0dIz6xyMp/nXGXcL+APiT4f0vw7qFzc2eosVuLCaXzPKH98Z5Ap9RHuOaU0xaeaBhmoLq6gtYWmuZo4Yl+88jBVH1Jp07OsbmNdzgEqpOMn0rx7xb4l8YzWF1aa34D0ltMc7S1xraxq4ByM5XilcLHpw8T6D31vTP/AuP/GpbXXtJu51htNUsJ5W6JFcIzH8Aa+WptZ0aFysngHwuG9vEAP8AJa2/CmtGDU4brw74C8NC9Q/IY/EKbvwBXmmgZ9OZozXJ+CtY8T6o8/8Awk3h2DR1UDyzHeiff+SjFdXmgQpOBnpSK4YZUgj1Fcr8T9WTRvAesXjyeWUgYK2cHJ4GKrfCBZV+HOhm4maaVoAzOzbicknrSWo3odkzYGTwKjt7qG5QvbzRyoDgsjBhn04qDWMjSrwjr5L/AMjXmP7Nju/ge5LszH7dLyTnvTWonoetZpN6jgsM/WkPIxXnup/DPSbu9muJ9a1mJ5WL7VvNoH0GKVxnofmL/eH50GQf3l/OvMv+FV6J/wBB/Wv/AAO/+tWZ4m+HWlaboN9eWviPWEngiZ0JvQQCB9KYHsQOaWvPfgTrN9rnw20q91OYz3LB1MjdWCsQCfwFehUWsJATgU3dQ1eKeOPFXinxH45fwd4Elisvs0fmXt/KM7AegUUrjPa91G6vBtZ8J/EvwjYvrGleMBrbQKZZrO4gEYdRyQpBP9K9Q+HHi2Dxl4UtNXhQxM4xLGf4HHUU+gHU7qM14Lda74x+JnivU9P8IajHoug6dIYJLwrvkkYdcCoPEFv8QvhfGmtN4gHiTRo2H2qGaHy5FXuRgn+dCA+gdxo3Gs3RNXttX0O11S1b/RriFZlJ7AjPNeM3OseLvif4o1Cz8I6ouieHdPk8l70JveaQddo9PxpdbAe8buKXOa8I03XfFfw08XWGleM9SXWNB1JvKgv9mx45OwYehr3WNgyhlOQRkGmIfRmikoGIWoDGqup3kWn2FxdznEUKF2+gFeD6A/xA+KzTatZ62PDfh9pGW1WOLzJHUHGSCR6etK4H0Fupc5714FJqvjX4W69p6eJ9VTXvDd5KIftOzZJCx6ZH/wBevZdf019c0hre3v7ixMmGWe3xuA/Gn0EbGaTdXmh+GeoAf8jtrv8A45XnLWviK6+J9roPhTxVqd5BZMJdSuJ9pRBn7gwOSaF2H0ufSWeaWoogVRVY7iBgn1qWgAooooADXmfwc/5Cfjf/ALDL/wDotK9MNeZ/Bz/kJ+N/+wy//otKAPTKKKKAGT/6o1k6n/yDrn/rm38q1p/9UaydT/5B1z/1zb+VMRS+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIrO+Jf/ACIutf8AXs/8q0fC3/ItaT/16xf+gikM1aKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKzvEX/IC1D/AK4P/I1o1neIv+QFqH/XB/5GgDH+F/8AyIWi/wDXun8q6muW+F//ACIWi/8AXun8q6mgApDS0negDx34nafqPhzx9pfjjTbKa+tI4TaX0EI3OqZyHA745rYk+M/g4WZlS+nkuMZFqttIZSf7u3b1rsfFXiDS/DWky6hrVwkFqnGSMlj2AHc15xF4z1jVZPtHhv4c3U8DcpcXapb7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W+k2yq7cyTNy8jerHvXRin0sHW5yXxT8TL4V8F31+p/0kgQ2692kbgAVlfBbwmfDfhZbi8+fVdRP2i6kPUseQPwrkfiXK3iz4xeGPCyEtaWKtqFyo6EjAXNe3xoI1CqMADAFJdxPsOA5p1IKWmMKKKKAA15n8HP+Qn43/wCwy/8A6LSvTDXmfwc/5Cfjf/sMv/6LSgD0yiiigBk/+qNZOp/8g65/65t/Ktaf/VGsnU/+Qdc/9c2/lTEUviX/AMiLrX/Xs/8AKtHwt/yLWk/9esX/AKCKzviX/wAiLrX/AF7P/KtHwt/yLWk/9esX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACs7xF/yAtQ/wCuD/yNaNZ3iL/kBah/1wf+RoAx/hf/AMiFov8A17p/Kuprlvhf/wAiFov/AF7p/KupoAKQ9aWigDzf4zeFdS8Rabptzo6Rz3Wm3S3ItpD8swHUfWs2P4x6dpSLB4m0XV9JuEGGVrVmX8CB0r1ikIHpSSsB5Ufjl4WmOzTotVvZjwI4rN8k/lVbwppes+JviRD4vv8AS5dGsreBoooZhtlmyMZYV68qgdAAPSlx6U1oJ6ic15J8bbK/h1jwvr1lp9zfW+m3BeeO3Qs4U45xXrp6UmM1PmM8sHxjtMc+GvEP/gG3+FKPjJZj/mW/EP8A4Bt/hXqVLVAec6P8VLbVNTt7OPw/rsLTNtDy2jBV9ycV6KORzQRSigDw39oKK3tvE3hDVtXsnutFtZnFziLzAoI4yKrDx38HeM6Un/grb/CveWAI5AI96aIY/wDnmv5Ul2B6nhB8dfBztpSf+Ctv8KyLC78N+Jviv4Zl8CaY0dvZ7nupFtDCAO2eK+jzDH/cX8qERV+6oH0FPqA4dKdQBRSAjcEqQDgnpXgmm2lx4D8RazeeKvDl1rKXk5ki1KCHzyif3T3Fe/HmkxR5geRJ8aPAcI2SRXcDDqhsG4/Sobn4n+FNaRoNM8OahrEjjAjTTzz+Yr2Iop6gH6ilAFAHmfwU8OapoVpqkuo232C2vLgzW1huz9nQ9vb6V6YelAFKaGI8D02+v/h38RfFV1qWh6neWeqTLNBPaQmRcY5Bx3rqR8Y7P/oW/EP/AIBt/hXqWOaXFMZ5WfjFZ5/5FrxF/wCAbf4V13gvxVF4otZpodOv7IRsFIu4TGWz6Z6102KTGCaBGN4om1qDTC3h22tri93DCXDlVx35Fcd/aXxQ/wCgJof/AIENXpeOKUUhnhfi3QviT4j1XRb6bTNHifTJ/PRVnOHPoa6gal8T/wDoCaH/AN/2r0zFBpgcT4ZvfHE2pBPEOm6Xb2W05eCUs2fpXaDrS4ooEI3Q4rwG10v4keH/AB34i1jS/DljqCag6iOWe5CsqL0A5969/wAUAYpdbjPHf+Ek+L3/AEJulf8AgYP8a7bwpe+J7/Qbl/Eml2+n6lyI4oZd4PpzXWikxR5AfO3gXT/il4Ot72G08LaddvdXDzvcS3YDMSSeea6c+JPi7/0Julf+Bg/xr2ICl7UwsY3hubU7vRIJNetIrS/Zf3sMbblU+gNeWaj4M8XeDfFV9q/w8W1urHUG8y4064faof8AvKe1e10Yo63A8W0XwT4r8WeL7LX/AIi/Zre3sCWtdOt33IH/ALxPevZ0AAAAwBTsUoFAgpKWigZzfxAtdRvfB+rWuipu1CaBkiBbb8xHHNeTeDX+Kvhbw7ZaRZ+DtLaK2QJvN2AWPcnnrXvmKAKSVgPH4/EfxcLqH8G6UEyMkXg6fnXq9k0z2cLXUax3DIDIgOQrY5GatGm4oA8x8IeDtUtPiv4l8SaqiC3uY44bMhgTtHLfTtXpwFLilph5iYpaKKACiiigANeZ/Bz/AJCfjf8A7DL/APotK9MNeZ/Bz/kJ+N/+wy//AKLSgD0yiiigBk/+qNZOp/8AIOuf+ubfyrWn/wBUaydT/wCQdc/9c2/lTEUviX/yIutf9ez/AMq0fC3/ACLWk/8AXrF/6CKzviX/AMiLrX/Xs/8AKtHwt/yLWk/9esX/AKCKQzVooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigArO8Rf8gLUP+uD/wAjWjWb4i/5AV//ANcH/kaAMj4X/wDIhaL/ANe6fyrqa4D4a+ItHh8EaRHLqdojrbqGVpQCOK6f/hJ9D/6C1j/3+WgDYorH/wCEn0P/AKC1l/3+Wj/hJ9D/AOgtZf8Af5aANjFFY/8Awk+h/wDQWsv+/wAtH/CT6H/0FrL/AL/LQBsYorH/AOEn0P8A6C1l/wB/lo/4SfQ/+gtZf9/loA2KKx/+En0P/oLWX/f5aP8AhJ9D/wCgtZf9/loA2MUYrH/4SfQ/+gtZf9/lo/4SfQ/+gtZf9/loA2MUVj/8JPof/QWsv+/y0f8ACT6H/wBBay/7/LQBsYorH/4SfQ/+gtZf9/lo/wCEn0P/AKC1l/3+WgDYoxWP/wAJPof/AEFrL/v8tH/CT6H/ANBay/7/AC0AbFFY/wDwk+h/9Bay/wC/y0f8JPof/QWsv+/y0AbFGKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2KKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2KKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2KKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANjFGKx/wDhJ9D/AOgtZf8Af5aP+En0P/oLWX/f5aANijFY/wDwk+h/9Bay/wC/y0f8JPof/QWsv+/y0AbFGKx/+En0P/oLWX/f5aP+En0P/oLWX/f5aANiisf/AISfQ/8AoLWX/f5aP+En0P8A6C1l/wB/loA2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UAUfgf8AHS98JSx6V4kklvNGYhVcnc8H+Ir7N0TVrLWtMg1DS7mO4tJl3JIjZBrC/wCFdeEf+he03/vwtb+l6XZ6VZpa6dbR21uhysca4UfhQBdooooAKKKKACiiigAooooAK5jx7q91oukW9xZlRI93DCdwz8rOAf0NdPXD/Fr/AJF60/6/7b/0atAHax5KgnuKfTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/AOPvxX/2FX/9AWu+NcD8K/8Aj78V/wDYVf8A9AWgDvqKKKAGT/6o1k6n/wAg65/65t/Ktaf/AFRrJ1P/AJB1z/1zb+VMRS+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIrO+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/8AYTb/ANAWu5rhvhp/yEfFf/YTb/0Ba7mgAooooAKKKKACiiigAooooAKKKKAPmH9tv/kFeHv+uz/yr5Jr62/bb/5BXh//AK7P/KvkmgAr6Z/Yk/5D3iH/AK4R/wAzXzNX0z+xJ/yHvEX/AFwj/maAPruiiigAooooAKKKKACiiigAooooAK4j4tf8i9af9hC2/wDRq129cR8Wv+RetP8AsIW3/o1aAO1i/wBWv0FOpsX+rX6CnUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUU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wq//AKAtAHfUUUUAMn/1RrJ1P/kHXP8A1zb+Va0/+qNZOp/8g65/65t/KmIpfEv/AJEXWv8Ar2f+VaPhb/kWtJ/69Yv/AEEVnfEv/kRda/69n/lWj4W/5FrSf+vWL/0EUhmrRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUHpRQelAHDfDT/kI+K/+wm3/oC13NcN8NP+Qj4r/wCwm3/oC13NABRRRQAUUUUAFFFFABRRRQAUUUUAGKKKjnkWKJ5HOFUEk0ASdaMCqGhapa61pdvqFg5ktp1DoxGMir9ABRRRQAUUUUAFFFFABRRRQAUUUUAFcR8Wv+RetP8AsIW3/o1a7euI+LX/ACL1p/2ELb/0atAHaxf6tfoKdTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigArh/izcY8P21gp/eX95DbqPX5gx/RTXbmvPvFinVPiV4Y08fNFZrLfyr6YGxf1egDvbSIQ20UYHCqF/SpaRaWgAooooAKKKKACg0UhoA4P4rSefbaLpS/ev9QiQgf3Vbef0Wu7jUKir6ACuC1Rf7T+LWkwf8s9NtJbhh23NhR+hNd8KAFooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/AOPvxX/2FX/9AWu+NcD8K/8Aj78V/wDYVf8A9AWgDvqKKKAGT/6o1k6n/wAg65/65t/Ktaf/AFRrJ1P/AJB1z/1zb+VMRS+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIrO+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/8AYTb/ANAWu5rhvhp/yEfFf/YTb/0Ba7mgAooooAKKKKACiiigAooooAKKKKACqmrD/iW3X/XJv5Vbqrqpxpt1/wBc2/lQByvwa/5JtoX/AF7r/Ku0ri/g1/yTbQv+vdf5V2lABRRRQAUUUUAFFFFABRRRQAUUUUAFcR8Wv+RetP8AsIW3/o1a7euI+LX/ACL1p/2ELb/0atAHaxf6tfoKdTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAorL8Qa/p2gWon1O4WINwiDlnPsBz/AErz69+L8CyEWOkySJ2aaYIfyAP86xnXp09JMaTZ6rRXlGlfGS0uLiSO+0qaFI2Cl4pRJ2z0IHr616PousWGt2YudMuUni6HHBU+hHUH604VoT0iwaaNCiiitRBRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFACGuG8Nj7f8R/EN8eVtoY7RG9OSzD9BXbTSCKJ3YgKoLE1xPwmzcaRqGpuPmv7ySXPqBwP5UAd0OlFAooAKKKKACiiigApDS1XvrhbSynuJDhIkLsfQAZoA43wWPtvjHxPqPVVkS1UnsFzmu6FcR8IonPhQXsw/e30z3DH1yeK7egAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/AOPvxX/2FX/9AWu+NcD8K/8Aj78V/wDYVf8A9AWgDvqKKKAGT/6o1k6n/wAg65/65t/Ktaf/AFRrJ1P/AJB1z/1zb+VMRS+Jf/Ii61/17P8AyrR8Lf8AItaT/wBesX/oIrO+Jf8AyIutf9ez/wAq0fC3/ItaT/16xf8AoIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/8AYTb/ANAWu5rhvhp/yEfFf/YTb/0Ba7mgAooooAKKKKACiiigAooooAKKKKACmSxrIjI4yrDBHqKfRQBV0ywttMsYrOxiWG2iXaiL0UVaozRQAUUUUAFFFFABRRRQAUUUUAFFFFABXEfFr/kXrT/sIW3/AKNWu3riPi1/yL1p/wBhC2/9GrQB2sX+rX6CnU2L/Vr9BTqACiiigAooooAKKKKACiiigArL8TavFoeh3eoS4PlJ8in+Jzwo/PFaleY/HW6ZNK0y1B+WWZpD/wABXH/s1ZV5+zg5DSuzw3WfEerX2uy3OvyrI0xJVuiovOFUdh7VbRldQykEEZFR3NvHcwmOZdyn9KyLVn0++EE7AI3RgMBhjj8a8WTVXXqa7F6xI+23ygjIdT+YrpvC3iC68OatHeWrHZkCaMnCyJ3B/oexrkra5VNRvnlKomF5PHTNAaXU3+TKWOeSeC+Ow9qLOMubYD7A029t9SsLe9s5Fkt50EiMD1BqzXBfBa78/wAGrb8AWk7xKPRThh/6Ea72vbpT54KRk1YKKKK0EFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRQaAOd+IF6dP8G6tOn+s8hkT/ebgfzqXwRp40zwpploOqQKT9Tyf51ifFN/O0/StNB+a9v40/Bcsf5V2sShI1UdFAFADxRRRQAUUUUAFFFFABXJ/FC6a18EamImxLcILdPq5C/1rrDXB/EtvtV94a0peftV+ruv+yils/mBQB1XhyzXT9DsbRBtWKFVx+FaVNUYUAU6gAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/4+/Ff/AGFX/wDQFrvjXA/Cv/j78V/9hV//AEBaAO+ooooAZP8A6o1k6n/yDrn/AK5t/Ktaf/VGsnU/+Qdc/wDXNv5UxFL4l/8AIi61/wBez/yrR8Lf8i1pP/XrF/6CKzviX/yIutf9ez/yrR8Lf8i1pP8A16xf+gikM1aKKKACiiigAooooAKKKKACiiigAooooAKKKKACg9KKD0oA4b4af8hHxX/2E2/9AWu5rhvhp/yEfFf/AGE2/wDQFrua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kH8qkrQQUUUUAFZ3iC6Flol7ck/6uJm/StE1xfxYujD4QmgT/WXciW6Y9WNAFj4X2P2DwRpikfPMhuHz/edi5/8AQq6yq2nwC2sbeBRhYo1QfgMVZoAKKKKACiiigAoooNAGL4xvPsHhnUbjdgrC2D7niofANiNP8IaVb7drCEOw/wBpvmP6msb4tTk6DaaenMmoXccAHcjOT/Kuzt0EUEca/dRQo/AUATGsHxd4ig8O6YbiQGW4c7ILdfvSueigVc17WLXRNLmv7+QRwxDPuT2A9zXJeE9Iudc1YeKPEKMJSuLC1b7tuh/ix/ePrQBd8E+Hrm3uJtc10rJrd4vzdxAn9xfSuwH60oAFYXi/xFbeHNKe6mBknY7IIF5aVzwFA+tAGZ458QXNoYNI0NVl1q9+SMdREvd29hTU8PweHPAl/aQkyTNC8k0zfelkIyzE/WneBvD1xZNcaxrTedrd988rHpEO0a+gFbXiv/kWtS/64P8AyoAz/hn/AMiFoP8A16R/+giunrmPhn/yIWg/9ekf/oIrp6ACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACg0UGgDh/Fn/I/wDhT6y/+g13FcP4s/5H/wAK/WX/ANBruKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigANcD8K/+PvxX/wBhV/8A0Ba741wPwr/4+/Ff/YVf/wBAWgDvqKKKAGT/AOqNZOp/8g65/wCubfyrWn/1RrJ1P/kHXP8A1zb+VMRS+Jf/ACIutf8AXs/8q0fC3/ItaT/16xf+gis74l/8iLrX/Xs/8q0fC3/ItaT/ANesX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/9hNv/QFrua4b4af8hHxX/wBhNv8A0Ba7mgAooooAKKKKACiiigAooooAKKKKACq2ou0djO6HDKhIPvirNVdV/wCQbdf9c2/lQBgfDDULrVfAukXt/KZbmaBWdz1JxXU1xfwb/wCSa6F/17r/ACrtKACiiigAooooAKKKKACiiigAooooAK4j4tf8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f8nKxf8AYbT/ANGCvvbtXwTN/wAnKxf9htP/AEYKAPvZfuilpF+6KWgAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAA1wPwr/wCPvxX/ANhV/wD0Ba741wPwr/4+/Ff/AGFX/wDQFoA76iiigBk/+qNZOp/8g65/65t/Ktaf/VGsnU/+Qdc/9c2/lTEUviX/AMiLrX/Xs/8AKtHwt/yLWk/9esX/AKCKzviX/wAiLrX/AF7P/KtHwt/yLWk/9esX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPSig9KAOG+Gn/IR8V/9hNv/AEBa7muG+Gn/ACEfFf8A2E2/9AWu5oAKKKKACiiigAooooAKKKKACiig0AGaDzXAfFX4naX8OILKXVrW5nW6Yqvk44wO+a86H7U3hT/oF6p+S/40AfQYpcivns/tTeFD/wAwvVP/AB3/ABruPhV8W9K+I9/e22k2dzb/AGVFdmmxzknpj6UAemUUDpRQAUUUUAFFFFABRRRQAUUUUAFcR8Wv+RetP+whbf8Ao1a7euI+LX/IvWn/AGELb/0atAHaxf6tfoKdTYv9Wv0FOoAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACmTf6p/oafTJv9U/0NA1ufG97J515PLu3b5GbPrk5r1P9n24MGt39u3S5t/MX/gDAH/0OvKriMRXEsYzhGK8+xr3P4CW1rNoU10YVN3BPJEsncKwQkfTgflXk4T+KfpHEj5cuSXketCigUV6x+bBRRWB4y8UWPhfSnu718ueIogfmkb0H+NJtRV2XTpyqyUIK7YeMvFFj4X0pru9fLniKIH5pG9B/jXzJ4o8QX3iTVZL7UJMseEjH3Y19BR4o8QX3iTVZL7UJMseEjH3Y19BWRXkYjEOo7LY/TcjyOOAj7SprN/gFFFWtL0+61S/hs7CFprmVtqoo/X2HvXMk27I+gqVI04uc3ZINL0+61S/hs7CFpriU7VRf88D3r6U+HPgi18K2G99s2pSr++mx0/2V9B/P+Ufw78DWvhW0EsgWbU5FxLN6d9q+g/n/LthXrYbDKmuaW5+a57n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RRRQAUUVheK/FOm+GbE3GozgMQfLiXl5D6Af5FJtJXZdOnOrJQgrtlvxBrFpoWlz39/KI4Yhk9yT2AHqTXy54x8RXPifW5r+6JVD8sMWeI07D6+vvVnxv4wv/Fd/5tyTFaIf3Nupyqe59T71Q8MaDeeI9WisLBMu3Lufuxr3Y15deu60uSGx+iZNlMMrpPFYp+9b7kdD8KfCjeJPECSXMZOm2hDzEjhz2T+p9vrX0yqhVCgYA4rI8K6BaeHNHgsLFcIgyzn7zserH3rYruoUVSjbqfHZxmUswxDn9lbBRRRW55IUUUUAFFFFABRRRQAUUUUAFFFFABRUNzd21s0S3NxDC0rBIxI4Xex6AZ6n2qagAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigANcD8K/+PvxX/wBhV/8A0Ba741wPwr/4+/Ff/YVf/wBAWgDvqKKKAGT/AOqNZOp/8g65/wCubfyrWn/1RrJ1P/kHXP8A1zb+VMRS+Jf/ACIutf8AXs/8q0fC3/ItaT/16xf+gis74l/8iLrX/Xs/8q0fC3/ItaT/ANesX/oIpDNWiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACvmTxjGI/2jYFUnB1Sxbn3ER/rX03Xzh8QYfK/aI0p8L+9vbB+P8AeRef++aiY0fR9FFFWIKKKKACiiigAooooAKDyMGiigDndY8EeGdYJbUNEsZHPWRYxG5/4EuD+tcbq3wN8LXe42T31g3YRy71H4OCf1r1SilZAeGf8KCeMn7N4qliU9vsf+EgqB/gx4nscvpHiwCQdMtLD+qlq96opcqHc8ADfGHwx8mJNVt16HCXO78f9Z+dTwfFPx9Cdl74Od3H92znjP65r3iijl8xHia/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">
        </div>
	    <div class="d-flex justify-content-center mt-2 mb-2">
			<div class="wrap_map">
			<div id="map" style="width:100%;height:100%"></div> <!-- 지도를 표시할 div 입니다 -->
			<div class="wrap_button">
				<a href="javascript:;" class="btn_comm btn_linkMap" target="_blank" onclick="moveKakaoMap(this)"><span class="screen_out">지도 크게보기</span></a> <!-- 지도 크게보기 버튼입니다 -->
				<a href="javascript:;" class="btn_comm btn_resetMap" onclick="resetKakaoMap()"><span class="screen_out">지도 초기화</span></a> <!-- 지도 크게보기 버튼입니다 -->
			</div>
		</div>    
		<div class="wrap_roadview">
			<div id="roadview" style="width:100%;height:100%"></div> <!-- 로드뷰를 표시할 div 입니다 -->
			<div class="wrap_button">
				<a href="javascript:;" class="btn_comm btn_linkRoadview" target="_blank" onclick="moveKakaoRoadview(this)"><span class="screen_out">로드뷰 크게보기</span></a> <!-- 로드뷰 크게보기 버튼입니다 -->
				<a href="javascript:;" class="btn_comm btn_resetRoadview" onclick="resetRoadview()"><span class="screen_out">로드뷰 크게보기</span></a> <!-- 로드뷰 리셋 버튼입니다 -->
			</div>
		</div>  
    </div> 
      </div>
    </div>
<?php endif; ?>	  

</div>
</div>
</div>

<div class="section" id="notice">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-1">
        <div class="accordion" id="accordiongNotice">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOneOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                엘리베이터 조명천장 디자인 선택시 유의사항
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOneOne" data-bs-parent="#accordiongNotice">
              <div class="accordion-body">
                엘리베이터 조명천장을 선택할 때는 공간의 분위기와 디자인에 어울리는 조명 스타일을 고려해야 합니다. 세련된 디자인은 물론, 에너지 효율성과 내구성을 겸비한 제품을 선택하여 장기적인 효용을 극대화하는 것이 중요합니다.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwoTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                엘리베이터 쟘(JAMB) 의장재로 적당한 소재는?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwoTwo" data-bs-parent="#accordiongNotice">
              <div class="accordion-body">
                엘리베이터 JAMB의 소재를 선택할 때는 내구성과 미적 요소를 동시에 고려해야 합니다. 스테인리스 스틸이나 알루미늄과 같은 고급 소재는 내구성이 뛰어나며, 다양한 마감 처리가 가능해 디자인적으로도 우수합니다.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThreeThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                재료분리대(SILL COVER) 시공시 주의해야할 점은?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThreeThree" data-bs-parent="#accordiongNotice">
              <div class="accordion-body">
                재료분리대(SILL COVER) 시공 시, 정확한 치수와 적절한 설치 각도를 유지하는 것이 중요합니다. 잘못된 설치는 엘리베이터의 안전성과 외관에 영향을 줄 수 있으므로, 숙련된 전문가의 시공을 권장합니다.
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5 align-self-center">
        <div class="section-heading">
          <h6>공지사항</h6>
          <h2>함께 알아야할 내용</h2>
          <p>엘리베이터 의장재를 제조 생산하는 저희 업체에서는 고객님의 안전과 품질을 최우선으로 생각합니다. 조명천장, JAMB, SILL COVER와 같은 주요 제품 선택 및 설치 시 반드시 숙지해야 할 사항들을 안내드리오니, 참고하시어 최상의 결과를 얻으시기 바랍니다.</p>
          <div class="main-button">
            <a href="#">더 알아보기</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <section class="section courses" id="ceiling" >
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-heading">
            <h6> 엘리베이터 조명천장</h6>
            <h2> 조명천장 </h2>
          </div>
        </div>
      </div>
      <ul class="event_filter">
        <li>
          <a class="is_active" href="#!" data-filter="*">전체</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-011">ELC-011</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-012">ELC-012</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-013">ELC-013</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-017">ELC-017</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-026">ELC-026</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-N20">ELC-N20</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-031">ELC-031</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-032">ELC-032</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-034">ELC-034</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-035">ELC-035</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-036">ELC-036</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-037">ELC-037</a>
        </li>
        <li>
          <a href="#!" data-filter=".ELC-038">ELC-038</a>
        </li>
      </ul>
      <div class="row event_box">	  
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-011">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling1.jpg" alt="">
              <!-- <span class="category">ELC-011</span>              -->
            </div>
            <div class="down-content">
              <span class="author">소방구조형에 적용되는 LH시방적용 모델 ELC-011</span>
              <h6>알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 곡선과 직선의 교차하며 센터부분의 하우징을 이용하여 포인트요소를 적용되는 천정 중판 분할은 인승에 따라 다르게 적용가능</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-012">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling2.jpg" alt="">
              <!-- <span class="category">ELC-012</span>              -->
            </div>
            <div class="down-content">
              <span class="author">모델 ELC-013</span>
              <h6>알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 곡선과 직선의 교차하며 센터부분의 하우징을 이용하여 포인트요소를 적용되는 천정 중판 분할은 인승에 따라 다르게 적용가능</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-013">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling3.jpg" alt="">
              <!-- <span class="category">ELC-013</span>              -->
            </div>
            <div class="down-content">
              <span class="author">모델 ELC-013</span>
              <h6>알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 곡선과 직선의 교차하며 센터부분의 하우징을 이용하여 포인트요소를 적용되는 천정 중판 분할은 인승에 따라 다르게 적용가능</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-017">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling4.jpg" alt="">
              <!-- <span class="category">ELC-017</span>              -->
            </div>
            <div class="down-content">
              <span class="author">모델 ELC-017</span>
              <h6>알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 곡선과 직선의 교차하며 센터부분의 하우징을 이용하여 포인트요소를 적용되는 천정 중판 분할은 인승에 따라 다르게 적용가능</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-026">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling5.jpg" alt="">
              <!-- <span class="category">ELC-026</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-026</span>
              <h6>압출물과 판금의 조화로 다른 재질감이 특징 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 천장 중판 분할은 인승에 따라 다르게 적용 가능, 인기있는 026</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-N20">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling6.jpg" alt="">
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-N20</span>
              <h6>압출물과 판금의 조화로 다른 재질감이 특징 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 천장 중판 분할은 인승에 따라 다르게 적용 가능 기존의 천장 디자인과 차별화로 깔끔한 이미지 연출</h6>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-031">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling7.jpg" alt="">
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-031</span>
              <h6> 넓은 광원으로 인해 밝고 깔끔한 이미지 연출 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 천장 중판 분할은 인승에 따라 다르게 적용 가능 측면의 곡선을 이용해 일체감을 높여주는 디자인</h6>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-032">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling8.jpg" alt="">
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-032</span>
              <h6> 압출물과 판금의 조화로 다른 재질감이 특징 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 천장 중판 분할은 인승에 따라 다르게 적용 가능 여러가지 재질(미러,도장,아크릴)을 이용해 고급스러움을 강조</h6>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-034">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling9.jpg" alt="">
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능, 11인승 이하 추천, 다운라이트는 인승에 따라 변경 모델 ELC-034</span>
              <h6> 넓은 미러 부분이 시원함을 주고 심플하지만 다양한 디자인과 호환 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 천장 중판 분할은 인승에 따라 다르게 적용 가능</h6>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-035">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceiling10.jpg" alt="">
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-035</span>
              <h6> 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 천장 중판 분할은 인승에 따라 다르게 적용 가능 넓은 미러 부분이 시원함을 주고 심플하지만 다양한 디자인과 호환 </h6>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-036">
          <div class="events_item">
            <div class="thumb">
              <a href="#"><img src="img/ceiling11.jpg" alt=""></a>
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-036</span>
              <h6> 팬톤 디자인으로 기존 트랜드를 깬 이중천장 곡선을 강조해 부드러운 선을 강조 천장 중간 조명부분은 인승에 따라 다르게 적용 </h6>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-037">
          <div class="events_item">
            <div class="thumb">
              <a href="#"><img src="img/ceiling12.jpg" alt=""></a>
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-037</span>
              <h6> 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 계단식을 강조해 디자인에 다채로움을 강조 천장 중간 조명부분은 인승에 따라 다르게 적용 </h6>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-70 event_outer col-md-6 ELC-038">
          <div class="events_item">
            <div class="thumb">
              <a href="#"><img src="img/ceiling13.jpg" alt=""></a>
              <!-- <span class="category">ELC-N20</span>              -->
            </div>
            <div class="down-content">
              <span class="author">LH 시방적용가능 모델 ELC-038</span>
              <h6> 알루미늄 압출 성형물을 이용하여 가볍고 환한 이미지 천장 중판 분할은 인승에 따라 다르게 적용 가능 넓은 미러 부분이 시원함을 주고 심플하지만 다양한 디자인과 호환 </h6>
            </div>
          </div>
        </div>		
        		
      </div>
    </div>
  </section>

  <div class="section fun-facts">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="wrapper">
            <div class="row">
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="13" data-speed="1000"></h2>
                   <p class="count-text ">천장모델 타입</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="80" data-speed="1000"></h2>
                  <p class="count-text ">쟘(Jamb) 고객사</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="50" data-speed="1000"></h2>
                  <p class="count-text ">재료분리대 고객사</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="counter end">
                  <h2 class="timer count-title count-number" data-to="12" data-speed="1000"></h2>
                  <p class="count-text ">설립년차</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- 설비/공정 -->
  <section class="section" id="device" >
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-heading">
            <h6> 설비/공정 </h6>
            <h2> 주요 설비 및 장비 </h2>
          </div>
        </div>
      </div>
	  
      <div class="row event_box">	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc1.jpg" alt="">              
            </div>
            <div class="down-content">
              <span class="author">레이져 Fiber Laser Cutting M/C</span>
              <h6>Fiber Laser Cutting M/C</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc2.jpg" alt="">              
            </div>
            <div class="down-content">
              <span class="author">브이컷장비 CNC V-cutting M/C</span>
              <h6>JWVZ-1000 5Ton</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc3.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author">절곡기 Hydraulic Press Brake</span>
                <h6>NCB 0640 T6/ 4000MM </h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc4.jpg" alt="">              
            </div>
            <div class="down-content">
              <span class="author">유압식 절단기</span>
              <h6>3000MM</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc5.jpg" alt="">              
            </div>
            <div class="down-content">			
              <span class="author">Air Spot Welder</span>
              <h6>Output Current 22000A</h6>
            </div>
          </div>
        </div>	   
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc6.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">CO2 용접기</span>
              <h6>CO2-350A</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc7.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">알곤용접기1</span>
              <h6> PTN-350LP</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc8.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">알곤용접기2</span>
              <h6>TIG350P</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc9.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">전동지게차1</span>
              <h6>7FBH25 / 2500KG</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc10.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">전동지게차2</span>
              <h6>41-FB15DE / 1500KG</h6>
            </div>
          </div>
        </div>	  
       
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc11.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">인버터탭드릴머신</span>
              <h6>1HP / KM-020</h6>
            </div>
          </div>
        </div>	  
       
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc12.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">콤푸레셔1</span>
              <h6>한신 TYPE NH-10 0.97Mpa</h6>
            </div>
          </div>
        </div>	  
       
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/mc13.jpg" alt="">              
            </div>
            <div class="down-content">		
			   <span class="author">콤푸레셔2</span>
              <h6>한신 TYPE NH-5 1Mpa</h6>
            </div>
          </div>
        </div>	  
       
		
		
      </div>
    </div>
  </section>

<!-- 생산공정 -->
<section class="section" id="processchart" >
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-heading">
            <h6> 제조 Process </h6>
            <h2> 공정 프로세스 </h2>
          </div>
        </div>
      </div>	  
      <div class="row event_box">	  
        <div class="col-lg-12 col-md-12 align-self-center mb-30  col-md-12 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/processchart.jpg" alt="">              
            </div>
            <div class="down-content justify-content-center text-center">
              <span class="author">제조절차 및 과정</span>              
            </div>
          </div>
        </div>	  
      </div>
    </div>
</section>

<!--품질목표/품질방침  -->
<?php if (!$chkMobile): ?>	
<section class="section" id="QCplan" >
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-heading">
            <h6> 품질목표&품질방침 </h6>
            <h2> 품질목표, 품질방침 </h2>
          </div>
        </div>
      </div>	  
      <div class="row event_box">	  
        <div class="col-lg-12 col-md-12 align-self-center mb-30  ">
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
				
        </div>	  
      </div>
    </div>
</section>
<?php else: ?>
<!-- 모바일용 코드 -->
<section class="section" id="QCplan">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h6> 품질관리 </h6>
                    <h2> 품질방침 & 목표 </h2>
                </div>
            </div>
        </div>
        <div class="row event_box">
            <div class="col-lg-12 col-md-12 align-self-center mb-30 ">
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">
                    <div class="mb-4 text-center">
                        <img src="../img/quality/quality01.jpg" style="width:100%; height:auto;" alt="품질 방침 및 목표" class="img-fluid">
                        <h5 class="fs-4 fw-bold mt-3 mt-2 mb-3"> 품질방침</h5>
                        <ul class="fs-5 text-start">
                            <li>고객중심 품질관리 경영</li>
                            <li>고객만족을 위한 내부 인프라 확충</li>
                            <li>고객 클레임 능동적 대응</li>
                            <li>부적합 관리 (원인규명 및 재발방지)</li>
                            <li>품질관리 유지 협의체 순환</li>
                        </ul>
                    </div>
                    <div class="text-center">
                        <img src="../img/quality/quality02.jpg" style="width:100%; height:auto;" alt="품질 목표" class="img-fluid">
                        <h5 class="fs-4 fw-bold mt-3 mt-2 mb-3"> 품질목표</h5>
                        <ul class="fs-5 text-start">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ISO 인증취득  -->
<?php if (!$chkMobile): ?>
<!-- PC용 코드 그대로 유지 -->
<section class="section" id="iso">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h6> 품질관리 </h6>
                    <h2> ISO 인증취득 </h2>
                </div>
            </div>
        </div>
        <div class="row event_box">
            <div class="col-lg-12 col-md-12 align-self-center mb-30  col-md-12">                
                <div class="d-flex mt-3 mb-1 justify-content-center align-items-center">
                    <table class="table table-bordered rounded">
                        <tr>
                            <td style="width:50%;">
                                <img src="../img/quality/iso9001.jpg" style="width:120%; height:800px;" alt="ISO 9001" class="img-fluid">
                            </td>
                            <td>
                                <img src="../img/quality/iso14001.jpg" style="width:120%; height:800px;" alt="ISO 14001" class="img-fluid">
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
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<!-- 모바일용 코드 -->
<section class="section" id="iso">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h6> 품질관리 </h6>
                    <h2> ISO 인증취득 </h2>
                </div>
            </div>
        </div>
        <div class="row event_box">
            <div class="col-lg-12 col-md-12 align-self-center mb-30  col-md-12">            
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">
                    <!-- ISO 9001 -->
                    <div class="mb-4 text-center">
                        <img src="../img/quality/iso9001.jpg" style="width:100%; height:auto;" alt="ISO 9001" class="img-fluid">
                        <h5 class="fs-4 fw-bold mt-3"> ISO 9001 인증서</h5>
                    </div>
                    <!-- ISO 14001 -->
                    <div class="text-center">
                        <img src="../img/quality/iso14001.jpg" style="width:100%; height:auto;" alt="ISO 14001" class="img-fluid">
                        <h5 class="fs-4 fw-bold mt-3"> ISO 14001 인증서</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 품질불량  -->
<section class="section " id="NG">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h6> 품질관리 </h6>
                    <h2> 품질불량 </h2>
                </div>
            </div>
        </div>	
        <div class="row event_box">		
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">            
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                    
                    <div class="mb-4 text-center">                        
                        <h5 class="fs-4 fw-bold mt-3">  <?=date("Y")?>년도 제조통계 </h5>
                    </div>                    
					<?php  include $root_dir . '/QC/prod_jamb_sub.php';  ?>
                </div>
            </div>	
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">            
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                    
                    <div class="mb-4 text-center">                        
                        <h5 class="fs-4 fw-bold mt-3">  <?=date("Y")?>년도 제조통계 </h5>
                    </div>                    
					<?php  include $root_dir . '/QC/prod_ceiling.php';  ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-top mb-30 ">            
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-top">                                        
					<?php include getDocumentRoot() . '/QC/rate_badAllexcept.php' ?>  					                
					<?php include getDocumentRoot() . '/QC/rate_badDetailexcept.php' ?>   
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">    
					<h5 class="fs-4 fw-bold text-center"> (모델구분 : 쟘) 불량율 </h5>  			
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                                        
					<?php include getDocumentRoot() . '/QC/rate_badAllJamb.php' ?>   
					 <?php include getDocumentRoot() . '/QC/rate_badDetailJamb.php' ?>   
					</div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">    
					<h5 class="fs-4 fw-bold text-center"> (모델구분 : 천장) 불량율  </h5>  			
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                                        
					<?php include getDocumentRoot() . '/QC/rate_badAllJamb.php' ?>  
					<?php include getDocumentRoot() . '/QC/rate_badDetailCeiling.php' ?>   					
				</div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">    
					<h5 class="fs-4 fw-bold text-center"> 불량 점유율 월별 비교  </h5>  			
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                                        
					<?php include getDocumentRoot() . '/load_errorstatistics.php' ?>  
				</div>
            </div>
            
        </div>			
	</div>  
</section >

<!-- 위험성평가  -->
<section class="section " id="RiskAssessement">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h6> 품질/안전 </h6>
                    <h2> 위험성평가 </h2>
                </div>
            </div>
        </div>	
        <div class="row event_box">		
            <div class="col-lg-12 col-md-12 align-self-center mb-30 ">     
			<div class="card">
			  <div class="card-body">
				<div class="d-flex mt-2 justify-content-center align-items-center">  					 
				<H3> 위험성평가 개요 </h3>
				</div>
				<div class="d-flex mt-5 align-items-center">  			 
					<H4> 위험성평가란? </h4>			
				</div>
				<div class="d-flex p-5 mb-1 align-items-center">  			 
					사업주가 스스로 유해ㆍ위험요인을 파악하고 해당 유해ㆍ위험요인의 위험성 수준을 결정하여, 위험성을 낮추기 위한 적절한 조치를 마련하고 실행하는 과정을 말합니다.
				</div>
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
					 <img src="../img/content_new.gif">		
				</div>		
				
				<div class="d-flex mb-1 mt-5 align-items-center">  			 
					<H4> 관련법령 </h4>			
				</div>		
				
				<div class="d-flex mb-1 mt-2 align-items-center">  	
						<H5> 산업안전보건법 제36조(위험성평가)  </h5> 
				</div>		
				<div class="row mb-1 mt-2  align-items-center" >				  
					① 사업주는 건설물, 기계ㆍ기구ㆍ설비, 원재료, 가스, 증기, 분진, 근로자의 작업행동 또는 그 밖의 업무로 인한 <br>
					유해ㆍ위험 요인을 찾아내어 부상 및 질병으로 이어질 수 있는 위험성의 크기가 허용 가능한 범위인지를 평가하여야 하고, <br>
					그 결과에 따라 이 법과 이 법에 따른 명령에 따른 조치를 하여야 하며, <br>
					근로자에 대한 위험 또는 건강장해를 방지하기 위하여 필요한 경우에는 추가적인 조치를 하여야 한다. <br>
					② 사업주는 제1항에 따른 평가 시 고용노동부장관이 정하여 고시하는 바에 따라 해당 작업장의 근로자를 참여시켜야 한다. <br>
					③ 사업주는 제1항에 따른 평가의 결과와 조치사항을 고용노동부령으로 정하는 바에 따라 기록하여 보존하여야 한다. <br>
					④ 제1항에 따른 평가의 방법, 절차 및 시기, 그 밖에 필요한 사항은 고용노동부장관이 정하여 고시한다.				  
				</div>
			
				<div class="d-flex mb-1 mt-5 align-items-center">  			 
					<H5> &nbsp; &nbsp; 고용노동부 고시 제2023-19호 「사업장 위험성평가에 관한 지침」 </h5>			
				</div>		
				
				<div class="row mb-1 mt-2 align-items-center" >											
							제1장 : 총칙(제1조~제4조) <br>
							제2장 : 사업장 위험성평가(제5조~제15조) <br>
							제3장 : 위험성평가 인정(제16조~제25조) <br>
							제4장 : 지원사업의 추진 등(제26조~제28조) <br>
							부칙							
				</div>		
				
				<div class="d-flex mt-5 justify-content-center align-items-center">  					 
				<H3> 위험성평가 인정 </h3>
				</div>
				<div class="d-flex mt-5 align-items-center">  			 
					<H4> "위험성평가" 사업주의 의무 입니다. </h4>			
				</div>
				<div class="d-flex p-5 mb-1 align-items-center">  			 
					위험성평가 인정, 신청 대상 사엄장, 우수사업장 인정절차 및 혜택에 대하여 알아보세요!
				</div>
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
					 <img src="../img/safe1.jpg">		
				</div>					
				<div class="d-flex mt-5 justify-content-center align-items-center">  	
				 
				<H3> 위험성평가 교육 </h3>
				</div>
				<div class="d-flex mt-5 align-items-center">  			 
					<H4> "위험성평가" 교육을 통해 안전한 사업장이 될 수 있도록 노력합시다! </h4>			
				</div>
				
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  	
					 <img src="../img/safe2.jpg" >		
				</div>						
			  </div>   
			  </div>       
            </div>	
        </div>			
	</div>  
</section >

<!-- 제품/시공 갤러리-->
  <section class="section" id="gallery" >
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-heading">
            <h6> 갤러리 </h6>
            <h2> 제품/시공 갤러리</h2>
          </div>
        </div>
      </div>
	  
      <div class="row event_box">	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/jambdone1.jpg" alt="">              
            </div>
            <div class="down-content">
              <span class="author"> 충남 천안 공주대 천안캠 챌린지하우스 식당 </span>
              <h6>와이드쟘 시공현장(2024년12월)</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/jambdone2.jpg" alt="">              
            </div>
            <div class="down-content">
              <span class="author">경기도 고양 수빌딩(K20240785-A)</span>
              <h6>멍텅구리쟘 시공현장(2024년12월)</h6>
            </div>
          </div>
        </div>	  
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/jambdone3.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 경기도 양주스파월드</span>
              <h6>와이드쟘 시공현장(2024년12월)</h6>
            </div>
          </div>
        </div>	  
         
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/jambdone4.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 경기도 포천 엠모텔 </span>
              <h6>와이드쟘 시공현장(2024년12월)</h6>
            </div>
          </div>
        </div>	  
         
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/jambdone5.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 서울 관악구 정림빌딩</span>
              <h6> 시공현장(2024년12월)</h6>
            </div>
          </div>
        </div>	           
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/jambdone6.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 전북 군산교도소 재활관 </span>
              <h6>멍텅구리쟘 시공현장(2024년12월)</h6>
            </div>
          </div>
        </div>	  
		
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone1.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 본천장 </span>
              <h6>제작완료(2024년12월)</h6>
            </div>
          </div>
        </div>	 
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone2.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 본천장 </span>
              <h6>제작완료(2024년12월)</h6>
            </div>
          </div>
        </div>	 
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone3.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 본천장 </span>
              <h6>제작완료(2024년12월)</h6>
            </div>
          </div>
        </div>	          
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone4.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 본천장 </span>
              <h6>제작완료(2024년12월)</h6>
            </div>
          </div>
        </div>	 
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone5.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 본천장 </span>
              <h6>제작완료(2024년12월)</h6>
            </div>
          </div>
        </div>	         
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone6.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author"> 라이트 케이스 </span>
              <h6>제작완료(2024년12월)</h6>
            </div>
          </div>
        </div>	  
       
       		
		
      </div>
    </div>
  </section>











<!-- 견적서  -->
  <div class="contact-us section" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-6  align-self-center">
          <div class="section-heading">
            <h6>견적</h6>
            <h2>견적 문의</h2>
            <p> 안녕하세요! 엘리베이터 조명천장, JAMB(잠), 재료분리대(SILL COVER) 등 엘리베이터 의장재 전문 제조업체인 저희 회사에서는 고객님의 문의를 환영합니다. 제품 견적이나 맞춤 제작 상담이 필요하시다면 언제든지 편하게 문의해 주세요. 고객님의 요청에 신속하고 정확하게 답변드리며, 최적의 솔루션을 제공해 드리겠습니다. 믿음직한 품질과 서비스를 약속드립니다!
			</p>
            <div class="special-offer">
              <span class="offer">편리한<br><em>견적</em></span>
              <h6>Email: <em> mirae@8440.co.kr </em></h6>
              <h4>연락처 <em> 031 </em> 983.8440</h4>
              <a href="#"><i class="fa fa-angle-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="contact-us-content">
            <form id="contact-form" name="contact-form" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12">
                  <fieldset>
                    <input type="name" name="name" id="name" placeholder="성함" autocomplete="off" required>
                  </fieldset>
                </div>
                <div class="col-lg-12">
                  <fieldset>
                    <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="받으실 Email 주소, ex) yes@gmail.com " autocomplete="off" required >
                  </fieldset>
                </div>
				<div class="col-lg-12">
					<fieldset>
						<input type="text" name="phone" id="phone" pattern="010-[0-9]{4}-[0-9]{4}" placeholder="연락처 HP, ex) 010-0000-0000" autocomplete="off" required>
					</fieldset>									
				</div>
				<div class="col-lg-12">
					<div class="text-start d-flex align-items-center">
						<input type="checkbox" id="privacyCheck" name="privacyCheck" 
							   style="transform: scale(0.5); margin: 0 8px 0 0; position: relative;" required>
						<label for="privacyCheck" class="mb-0">
							<a href="javascript:void(0);" id="privacyPolicyLink" 
							   class="badge bg-primary text-decoration-underline fs-6"
							   style="margin: 0 8px 0 0; position: relative;" 
							   >
								개인정보 수집 및 이용에 동의합니다
							</a>
						</label>
					</div>
				</div>


                <div class="col-lg-12">
                  <fieldset>
                    <textarea name="message" id="message" placeholder="남기고 싶은 말씀"></textarea>
                  </fieldset>
                </div>
				<div class="col-lg-12">
					<fieldset>
						<label class="text-white" for="file">파일첨부 (10M 이하, PDF, 이미지):</label>
						<input type="file" id="file" name="file" class="form-control" accept=".pdf,image/*">
					</fieldset>
				</div>				
                <div class="col-lg-12">
                  <fieldset>
                    <button type="button" id="form-submit" class="orange-button">견적 의뢰 하기</button>
                  </fieldset>
                </div>
              </div>
            </form>
          </div>
        </div>
		
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="col-lg-12">
        <p>Copyright © 2020 (주)미래기업 All rights reserved. &nbsp;&nbsp;&nbsp; Design: <a href="https://templatemo.com" rel="nofollow" target="_blank">TemplateMo</a> Distribution: <a href="https://themewagon.com" rel="nofollow" target="_blank">ThemeWagon</a></p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->    
  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/counter.js"></script>
  <script src="assets/js/custom.js"></script>
  
  
<script>

$(document).ready(function(){
	
$("#form-submit").click(function () {
    // 필수 필드 확인
    if (
        $('#name').val() === '' ||
        $('#phone').val() === '' ||
        $('#email').val() === '' ||
        $('#message').val() === ''
    ) {
        alert("모든 필드를 입력해주세요.");
        return;
    }

    // 체크박스 확인
    if (!$('#privacyCheck').is(':checked')) {
        alert("개인정보 수집 및 이용에 동의해야 합니다.");
        return;
    }

    // FormData 생성
    var form = $('#contact-form')[0];
    var data = new FormData(form);

    // AJAX를 사용해 데이터 전송
    $.ajax({
        enctype: 'multipart/form-data',
        url: "./PHPMailer/sendmail.php",
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        data: data,
        success: function (response) {
			console.log(response);
						
            if (response === "1") {
                Swal.fire({
                    title: "성공",
                    text: "견적요청 메일이 성공적으로 전송되었습니다.",
                    icon: "success",
                    confirmButtonText: "확인"
                });
                $('#contact-form')[0].reset(); // 폼 초기화
            } else {
                Swal.fire({
                    title: "오류",
                    text: "메일 전송 중 오류가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인"
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire({
                title: "오류",
                text: "서버와의 통신 중 문제가 발생했습니다. 다시 시도해주세요.",
                icon: "error",
                confirmButtonText: "확인"
            });
            console.error("에러 발생:", textStatus, errorThrown);
        }
    });
});
	
});	
   
ajaxRequest = null;	 
  
$(document).ready(function(){  
	$("#loginBtn").click(function(){ 
	
     const home = '<?php echo $home; ?>';			
	  
	  if (ajaxRequest !== null) {
		ajaxRequest.abort();
	  }

	 // data 전송해서 php 값을 넣기 위해 필요한 구문
		ajaxRequest = $.ajax({
			url: '/login/login_confirm.php',
			type: "post",		
			data: $("#login_form").serialize(),
			dataType:"json",
			success : function( data ){			
			
				console.log(data);
				
				if( data["error"] ==='' || data["error"] === null)
					location.href = './index2.php';		  // 통합사이트로 이동
				   else
				   {
						Swal.fire(
						  '오류알림',
						  data["error"] ,
						  'fail'
						)  
				   }

				},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });				  
	});		
		
	$("#logoutBtn").click(function(){ 	
			location.href = './login/logout.php';		  // log out
	});		
	
	$("#loginIconBtn").click(function(){		
		const home = '<?php echo $home; ?>';	
		
		console.log(name);
		
		if( home==='1')
			location.href = 'index2.php';		  
			else		
			   $('#loginModal').modal('show');
    
	});
	

	
});
	
  
// 지도보기

$(document).ready(function(){
	
var Lat = "<?php echo isset($Lat) ? $Lat : ''; ?>";
var Lng = "<?php echo isset($Lng) ? $Lng : ''; ?>";
var HomeAddress = "<?php echo $HomeAddress; ?>";

console.log('Lat : ');
console.log(Lat);

if(Lat=='' || Lat==null)
  {
	console.log('null 실행');  
    Lat = '37.676328924477936';
    Lng = '126.61606606909503';
    HomeAddress = '(주) 미래기업';	
  }		 
 
var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
    mapCenter = new kakao.maps.LatLng(parseFloat(Lat), parseFloat(Lng) ), // 지도의 중심 좌표 (위도, 경도) 웹툴에서 https://xn--yq5bk9r.com/blog/map-coordinates 검색가능
    mapOption = {
        center: mapCenter, // 지도의 중심 좌표
        level: 4 // 지도의 확대 레벨
    };

// 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
var map = new kakao.maps.Map(mapContainer, mapOption);

// 지도에 올릴 마커를 생성합니다.
var mMarker = new kakao.maps.Marker({
    position: mapCenter, // 지도의 중심좌표에 올립니다.
    map: map // 생성하면서 지도에 올립니다.
});

// 지도에 올릴 장소명 인포윈도우 입니다.
var mLabel = new kakao.maps.InfoWindow({
    position: mapCenter, // 지도의 중심좌표에 올립니다.
    content: HomeAddress // 인포윈도우 내부에 들어갈 컨텐츠 입니다.
});
mLabel.open(map, mMarker); // 지도에 올리면서, 두번째 인자로 들어간 마커 위에 올라가도록 설정합니다.


var rvContainer = document.getElementById('roadview'); // 로드뷰를 표시할 div
var rv = new kakao.maps.Roadview(rvContainer); // 로드뷰 객체 생성
var rc = new kakao.maps.RoadviewClient(); // 좌표를 통한 로드뷰의 panoid를 추출하기 위한 로드뷰 help객체 생성
var rvResetValue = {} //로드뷰의 초기화 값을 저장할 변수
rc.getNearestPanoId(mapCenter, 50, function(panoId) {
    rv.setPanoId(panoId, mapCenter);//좌표에 근접한 panoId를 통해 로드뷰를 실행합니다.
    rvResetValue.panoId = panoId;
});

// 로드뷰 초기화 이벤트
kakao.maps.event.addListener(rv, 'init', function() {

    // 로드뷰에 올릴 마커를 생성합니다.
    var rMarker = new kakao.maps.Marker({
        position: mapCenter,
        map: rv //map 대신 rv(로드뷰 객체)로 설정하면 로드뷰에 올라갑니다.
    });

    // 로드뷰에 올릴 장소명 인포윈도우를 생성합니다.
    var rLabel = new kakao.maps.InfoWindow({
        position: mapCenter,
        content: HomeAddress
    });
    rLabel.open(rv, rMarker);

    // 로드뷰 마커가 중앙에 오도록 로드뷰의 viewpoint 조정 합니다.
    var projection = rv.getProjection(); // viewpoint(화면좌표)값을 추출할 수 있는 projection 객체를 가져옵니다.
    
    // 마커의 position과 altitude값을 통해 viewpoint값(화면좌표)를 추출합니다.
    var viewpoint = projection.viewpointFromCoords(rMarker.getPosition(), rMarker.getAltitude());
    rv.setViewpoint(viewpoint); //로드뷰에 뷰포인트를 설정합니다.

    //각 뷰포인트 값을 초기화를 위해 저장해 놓습니다.
    rvResetValue.pan = viewpoint.pan;
    rvResetValue.tilt = viewpoint.tilt;
    rvResetValue.zoom = viewpoint.zoom;
});

//지도 이동 이벤트 핸들러
function moveKakaoMap(self){
    
    var center = map.getCenter(), 
        lat = center.getLat(),
        lng = center.getLng();

    self.href = 'https://map.kakao.com/link/map/' + encodeURIComponent(HomeAddress) + ',' + lat + ',' + lng; //Kakao 지도로 보내는 링크
}

//지도 초기화 이벤트 핸들러
function resetKakaoMap(){
    map.setCenter(mapCenter); //지도를 초기화 했던 값으로 다시 셋팅합니다.
    map.setLevel(mapOption.level);
}

//로드뷰 이동 이벤트 핸들러
function moveKakaoRoadview(self){
    var panoId = rv.getPanoId(); //현 로드뷰의 panoId값을 가져옵니다.
    var viewpoint = rv.getViewpoint(); //현 로드뷰의 viewpoint(pan,tilt,zoom)값을 가져옵니다.
    self.href = 'https://map.kakao.com/?panoid='+panoId+'&pan='+viewpoint.pan+'&tilt='+viewpoint.tilt+'&zoom='+viewpoint.zoom; //Kakao 지도 로드뷰로 보내는 링크
}

//로드뷰 초기화 이벤트 핸들러
function resetRoadview(){
    //초기화를 위해 저장해둔 변수를 통해 로드뷰를 초기상태로 돌립니다.
    rv.setViewpoint({
        pan: rvResetValue.pan, tilt: rvResetValue.tilt, zoom: rvResetValue.zoom
    });
    rv.setPanoId(rvResetValue.panoId);
}
 
 
});	 //end of ready
		
	  
// SweetAlert2로 개인정보 보호정책 팝업 표시
document.getElementById('privacyPolicyLink').addEventListener('click', function () {
    Swal.fire({
        title: '개인정보 보호정책 안내',
        html: `
            <p><strong>수집항목:</strong> 휴대폰 번호</p>
            <p><strong>수집이용목적:</strong> 고객문의사항 접수 및 회신</p>
            <p><strong>보유기간:</strong> 3년</p>
        `,
        icon: 'info',
        confirmButtonText: '확인'
    });
});
  
$(document).ready(function () {    
	// 천장
    $('.icon-button .video1').on('click', function (event) {
        event.preventDefault(); // 기본 동작(페이지 이동) 방지
        console.log('click youtube');		
        const videoUrl = "https://www.youtube.com/embed/Tv7p06VOvq8?autoplay=1";
		$("#youtubeModal").modal('show');
        $('#youtubeVideo').attr('src', videoUrl);
    });

    // jamb 유튜브 URL 설정
    $('.icon-button .video2').on('click', function (event) {
        event.preventDefault(); // 기본 동작(페이지 이동) 방지
        console.log('click youtube');				
        const videoUrl = "https://www.youtube.com/embed/WAuC0ELSfgs?autoplay=1";
		$("#youtubeModal").modal('show');
        $('#youtubeVideo').attr('src', videoUrl);
    });

    // 재료분리대 유튜브 URL 설정
    $('.icon-button .video3').on('click', function (event) {
        event.preventDefault(); // 기본 동작(페이지 이동) 방지
        console.log('click youtube');				
        const videoUrl = "https://www.youtube.com/embed/B2Aufa2409c?autoplay=1";
		$("#youtubeModal").modal('show');
        $('#youtubeVideo').attr('src', videoUrl);
    });

    // 모달 닫을 때 유튜브 영상 정지
    $('#youtubeModal').on('hidden.bs.modal', function () {
        $('#youtubeVideo').attr('src', ''); // src를 초기화하여 재생 중지
       	$("#youtubeModal").modal('hide');
    });
});



  
</script>
  
  </body>
</html>