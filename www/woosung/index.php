<?php 
// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'ini.php';    
?>

<!doctype html>

<html lang="ko">
  <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="우성스틸앤엘리베이터">
    
  <!-- theme meta -->
  <meta name="theme-name" content="우성스틸앤엘리베이터" />
  
  <meta name="author" content="Themefisher.com">

  <title>우성스틸앤엘리베이터</title>

  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Icofont Css -->
  <link rel="stylesheet" href="plugins/icofont/icofont.min.css">
  <!-- Themify Css -->
  <link rel="stylesheet" href="plugins/themify/css/themify-icons.css">
  <!-- animate.css -->
  <link rel="stylesheet" href="plugins/animate-css/animate.css">
  <!-- Magnify Popup -->
  <link rel="stylesheet" href="plugins/magnific-popup/dist/magnific-popup.css">
  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick-theme.css">
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="css/style1.css">
  
<script src="http://8440.co.kr/common.js"></script>  

</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Header Close -->

<div class="main-wrapper ">
<!-- Section Menu End -->

<!-- Section Slider Start -->
<!-- Slider Start -->
<section class="slider">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<span class="h6 d-inline-block mb-4 subhead text-uppercase">Elevator 의장재 및 원자재</span>
				<h2 class="text-uppercase text-white mb-5">Step up your <span class="text-color">Steel Business</span><br>with us</h2>
			
			<!--	<a href="pricing.html" target="_blank" class="btn btn-main " >Join Us <i class="ti-angle-right ml-3"></i></a>  -->
			</div>
		</div>
	</div>
</section>
<!-- Section Slider End -->

<!-- Section Intro Start -->
<section class="mt-80px">
	<div class="container">
		<div class="row ">
			<div class="col-lg-4 col-md-6">
				<div class="card p-5 border-0 rounded-top border-bottom position-relative hover-style-1">
					<span class="number">01</span>
					<h3 class="mt-3">공사관리</h3>
					<p class="mt-3 mb-4">현장 등록/조회/수정/삭제</p>
					<a href="order.php" class="text-color text-uppercase font-size-13 letter-spacing font-weight-bold"><i class="ti-minus mr-2 "></i>more Details</a>
				</div>
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="card p-5 border-0 rounded-top hover-style-1">
					<span class="number">02</span>
					<h3 class="mt-3">일정관리</h3>
					<p class="mt-3 mb-4">납기일자 등 일정조회 </p>
					<a href="#" onclick="popupCenter('schedule.php' , '일정표', 900, 900);" class="text-color text-uppercase font-size-13 letter-spacing font-weight-bold"><i class="ti-minus mr-2 "></i>more Details</a>
				</div>
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="card p-5 border-0 rounded-top hover-style-1">
					<span class="number">03</span>
					<h3 class="mt-3">원자재관리</h3>
					<p class="mt-3 mb-4"> 자재 등록/조회/수정/삭제 거래명세표 출력</p>
					<a href="steel.php" class="text-color text-uppercase font-size-13 letter-spacing font-weight-bold"><i class="ti-minus mr-2 "></i>more Details</a>
				</div>
			</div>           
		</div> 
	</div>     
</section>
<!-- Section Intro End -->

<!-- Section Footer Start -->
<!-- footer Start -->




<footer class="footer bg-black-50">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-md-6">
				<div class="footer-widget">
					<h4 class="mb-4 text-white letter-spacing text-uppercase">본사 주소</h4>
					<p>서울사무소 : 서울시 강서구 마곡중앙로 59-11 엠비즈타워 509,510호 <br>
						공장 : 경기도 광주시 곤지암읍 내선길 112-9  <br>						
						Fax : 02-6404-5787			
					
					</p>
					<span class="text-white">+82 (02) 6339-6325</span>
					<span class="text-white">woosung6339@gmail.com</span>
				</div>
			</div>
		</div>

		<div class="row align-items-center mt-5 px-3 bg-black mx-1">
			<div class="col-lg-4">
				<p class="text-white mt-3">우성스틸앤엘리베이터 © 2022 , copyrights </p>
			</div>
			<div class="col-lg-6 ml-auto text-right">
				<ul class="list-inline mb-0 footer-socials">
					<li class="list-inline-item"><a href="https://www.facebook.com/themefisher"><i class="ti-facebook"></i></a></li>
					<li class="list-inline-item"><a href="https://twitter.com/themefisher"><i class="ti-twitter"></i></a></li>					
				</ul>
			</div>
		</div>
	</div>
</footer>
<!-- Section Footer End -->

<!-- Section Footer Scripts -->
   </div>

   <!-- 
    Essential Scripts
    =====================================-->


   <!-- Main jQuery -->
   <script src="plugins/jquery/jquery.js"></script>
   <!-- Bootstrap 4.3.1 -->
   <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
   <!-- Slick Slider -->
   <script src="plugins/slick-carousel/slick/slick.min.js"></script>
   <!--  Magnific Popup-->
   <script src="plugins/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
   <!-- Form Validator -->
   <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.32/jquery.form.js"></script>
   <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
   <!-- Google Map -->
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBu5nZKbeK-WHQ70oqOWo-_4VmwOwKP9YQ"></script>
   <script src="plugins/google-map/gmap.js"></script>

   <script src="js/script.js"></script>

   </body>

   </html>
   
