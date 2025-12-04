<?php
require_once __DIR__ . '/bootstrap.php';

// 회사명 상수 정의
if (!defined('COMPANY_NAME')) {
    define('COMPANY_NAME', '미래기업');
}

// 시공사례 데이터 가져오기
require_once __DIR__ . '/includes/get_portfolios.php';
 
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

isset($_REQUEST["home"]) ? $home = $_REQUEST["home"] : $home=""; 

//print $home;

// $home에 1이 들어오면 홈페이지 보도록 변경
if(isset($_SESSION["name"]) && $home!='1') 
	{ 
		  header ("Location:index2.php");
		  exit; 
	}
	else if(isset($_SESSION["name"]) && $home=='2') 
		{ 
			  header ("Location:index3.php?home=2");
			  exit; 
		}

$APIKEY = "2ddb841648d38606331320046099cf67";

isset($_REQUEST["Lat"]) ? $Lat=$_REQUEST["Lat"] : $Lat='';	 
isset($_REQUEST["Lng"]) ? $Lng=$_REQUEST["Lng"] : $Lng='';	 
isset($_REQUEST["HomeAddress"]) ? $HomeAddress=$_REQUEST["HomeAddress"] : $HomeAddress='';	 

// 메인상단 이미지를 AI로 그린 그림으로 10개 랜덤으로 뽑아내서 그려주기
$rnd = rand(1, 10);
$imgsrc = 'img/homepage/' . $rnd . '.png';

$root_dir = getDocumentRoot() ;
$version = time(); // 매번 다른 값으로 캐시 방지
$time = time(); // 스크립트 캐시 방지용
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<!-- Favicon-->	
<link rel="icon" type="image/x-icon" href="favicon.ico">   <!-- 33 x 33 -->
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">    <!-- 144 x 144 -->
<link rel="apple-touch-icon" type="image/x-icon" href="favicon.ico">

<meta property="og:type" content="엘리베이터 의장재 조명천장,쟘 미래기업">
<meta property="og:title" content="엘리베이터 의장재 조명천장,쟘 미래기업">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="엘리베이터 의장재 조명천장 쟘 덧씌우기 재료분리대 제작 전문기업">
<meta property="og:image" content="https://8440.co.kr/img/mirae.png"/>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Bootstrap core CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Additional CSS Files -->
<link rel="stylesheet" href="assets/css/fontawesome.css?v=<?php echo $version; ?>">
<link rel="stylesheet" href="assets/css/templatemo-scholar.css?v=<?php echo $version; ?>">
<link rel="stylesheet" href="assets/css/owl.css?v=<?php echo $version; ?>">
<link rel="stylesheet" href="assets/css/animate.css?v=<?php echo $version; ?>">
<link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
<link rel="stylesheet" href="css/portfolio.css?v=<?php echo $version; ?>">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
/* 드롭다운 메뉴 위치 설정 */
.header-area .main-nav .nav .dropdown {
    position: relative;
}

.header-area .main-nav .nav .dropdown .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 5px;
    min-width: 200px;
    z-index: 1000;
}

.dropdown:hover .dropdown-menu {
    display: block;
}
/* 마우스 오버하면 드롭다운하기 */

/* PC 화면에서 메뉴 한 줄 표시를 위한 스타일 */
@media (min-width: 768px) {
    .header-area {
        position: relative !important;
        top: 0 !important;
        background-color: #7a6ad8 !important;
        border-radius: 0px 0px 25px 25px !important;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.15) !important;
    }
    
    .header-area .main-nav {
        background: transparent !important;
    }
    
    .header-area .main-nav {
        display: flex !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
        width: 100%;
    }
    
    .header-area .main-nav .nav {
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        gap: 0 !important;
        white-space: nowrap !important;
        flex-shrink: 0;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .header-area .main-nav .nav li {
        padding-left: 0 !important;
        padding-right: 0 !important;
        flex-shrink: 0 !important;
        white-space: nowrap !important;
        margin: 0 !important;
    }
    
    .header-area .main-nav .nav li a {
        padding-left: 12px !important;
        padding-right: 12px !important;
        font-size: 21px !important;
        white-space: nowrap !important;
        height: 50px !important;
        line-height: 50px !important;
    }
    
    .header-area .main-nav .nav li.dropdown > a::after {
        margin-left: 5px;
        font-size: 18px;
    }
    
    /* SNS 아이콘 버튼 스타일 */
    .desktop-sns-icons {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        height: auto !important;
    }
    
    .desktop-sns-btn {
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        border: 2px solid transparent !important;
        transition: all 0.3s ease !important;
        flex-shrink: 0;
    }
    
    .desktop-sns-btn:hover {
        transform: translateY(-2px);
    }
    
    .desktop-sns-btn svg,
    .desktop-sns-btn img {
        width: 20px !important;
        height: 20px !important;
        display: block;
    }
    
    /* 로그인 폼 크기 최적화 */
    .desktop-login-form {
        display: flex !important;
        align-items: center !important;
        gap: 4px !important;
        margin: 0 !important;
        white-space: nowrap;
    }
    
    .login-input {
        width: 80px !important;
        height: 30px !important;
        font-size: 11px !important;
        padding: 3px 6px !important;
        display: inline-block !important;
    }
    
    .login-btn {
        font-size: 11px !important;
        padding: 3px 10px !important;
        height: 30px !important;
        white-space: nowrap !important;
    }
    
    .user-name {
        font-size: 12px !important;
        white-space: nowrap !important;
    }
    
    /* 관리자 버튼 크기 조정 */
    .admin-btn {
        font-size: 11px !important;
        padding: 3px 8px !important;
        height: 30px !important;
        display: flex !important;
        align-items: center !important;
        white-space: nowrap !important;
    }
    
    .admin-btn i {
        margin-right: 3px !important;
    }
    
    /* 로고와 메뉴 사이 간격 조정 */
    .logo-sns-container {
        flex-shrink: 0;
        max-width: 300px;
    }
    
    .logo h1 {
        font-size: 26px !important;
        margin-right: 8px !important;
        padding-right: 8px !important;
        border-right: 1px solid rgba(250, 250, 250, 0.3) !important;
    }
    
    /* 컨테이너 최적화 */
    .header-area .container {
        max-width: 100% !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    
    /* 메뉴 중앙 정렬 */
    .header-area .main-nav {
        justify-content: center !important;
    }
    
    .header-area .main-nav .nav {
        flex: 0 0 auto;
        justify-content: center !important;
        margin: 0 auto !important;
    }
    
    /* 로고를 왼쪽에 배치 */
    .logo-sns-container {
        position: absolute !important;
        left: 15px !important;
        z-index: 10;
    }
    
    /* PC용 SNS 아이콘 컨테이너 숨기기 (nav 안으로 이동) */
    .desktop-sns-icons-wrapper {
        display: none !important;
    }
    
    /* SNS 아이콘을 nav에서 표시하고 "회사안내" 왼쪽에 배치 */
    .header-area .main-nav .nav li.desktop-sns-icons {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-right: 10px !important;
    }
    
    /* SNS 아이콘 크기 조정 */
    .desktop-sns-btn {
        width: 45px !important;
        height: 45px !important;
    }
    
    .desktop-sns-btn svg,
    .desktop-sns-btn img {
        width: 26px !important;
        height: 26px !important;
    }
    
    /* 로그인 폼을 오른쪽에 배치 */
    .header-area .main-nav .nav li:last-child,
    .header-area .main-nav .nav li:nth-last-child(2) {
        position: absolute !important;
        right: 15px !important;
        z-index: 10;
    }
    
    /* 메뉴 항목들 간격 조정 */
    .header-area .main-nav .nav li {
        padding-left: 3px !important;
        padding-right: 3px !important;
    }
    
    /* 메뉴만 중앙에 정렬되도록 */
    .header-area .main-nav .nav {
        width: auto;
        margin: 0 auto;
    }
}

/* 모바일 메뉴 초기 상태: 닫힘 상태로 강제 설정 */
@media (max-width: 767px) {
    .header-area .main-nav .nav:not(.nav-open) {
        display: none !important;
    }
    .header-area .main-nav .nav.nav-open {
        display: block !important;
    }
    
    /* 모바일 헤더 컨테이너 패딩 조정 */
    .header-area .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    /* 모바일 헤더 높이 설정 - 상부 여백 제거 */
    .header-area {
        top: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        height: 70px !important;
        min-height: 70px !important;
        max-height: 70px !important;
    }
    
    .header-area .main-nav {
        min-height: 70px !important;
        height: 70px !important;
        max-height: 70px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .header-area .container {
        padding: 0 !important;
        margin: 0 !important;
        height: 70px !important;
        min-height: 70px !important;
        max-height: 70px !important;
        display: flex !important;
        align-items: center !important;
    }
    
    /* 로고의 상부 여백 제거 */
    .header-area .main-nav .logo {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    
    /* 로고 컨테이너 오버플로우 방지 */
    .logo-sns-container {
        overflow: visible;
        max-width: 100%;
        width: 100%;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        height: 70px !important;
        min-height: 70px !important;
        vertical-align: middle !important;
        position: relative;
        z-index: 1000;
        padding: 0 10px !important;
        margin: 0 !important;
        gap: 12px;
    }
    
    /* 헤더 내 모든 요소 세로 중앙 정렬 */
    .logo-sns-container > *,
    .logo-sns-container > div {
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        vertical-align: middle !important;
    }
    
    /* 로고와 SNS 아이콘을 감싸는 내부 컨테이너 */
    .logo-sns-inner {
        display: flex !important;
        align-items: center !important;
        gap: 12px;
        flex-wrap: nowrap;
        flex: 1;
        min-width: 0;
        overflow: hidden;
        height: 100% !important;
    }
    
    /* 로고 텍스트 중앙 정렬 */
    .logo {
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        vertical-align: middle !important;
        flex-shrink: 0;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .logo h1 {
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        vertical-align: middle !important;
        width: 110%;
        white-space: nowrap;
        font-size: 36px;
        color: #fff;
    }
    
    .mobile-menu-toggle i {
        font-size: 1.5rem;
        color: white;
    }
    
    /* 햄버거 버튼이 잘리지 않도록 */
    .mobile-menu-toggle {
        flex-shrink: 0 !important;
        min-width: 44px;
        width: 44px;
        height: 44px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        backdrop-filter: blur(5px);
        margin: 0 !important;
        vertical-align: middle !important;
        align-self: center !important;
        border-radius: 8px;
        position: relative;
        top: 0;
    }
    
    .mobile-menu-toggle i {
        line-height: 1 !important;
        vertical-align: middle !important;
        margin: 0 !important;
        padding: 0 !important;
        display: inline-block;
        font-size: 1.5rem;
        color: white;
    }
    
    /* SNS 아이콘 컨테이너 */
    .mobile-sns-icons {
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        gap: 6px !important;
        vertical-align: middle !important;
        align-self: center !important;
    }
    
    /* SNS 아이콘 버튼 */
    .mobile-sns-icons button {
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        vertical-align: middle !important;
        align-self: center !important;
        border-radius: 50%;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        top: 0;
    }
    
    .mobile-sns-icons button svg,
    .mobile-sns-icons button img {        
        margin: 0 !important;
        padding: 0 !important;
        display: block;
        object-fit: contain;
    }
    
    .mobile-sns-icons button svg {
        width: 18px;
        height: 18px;
    }
    
    .mobile-sns-icons button img {
        width: 18px;
        height: 18px;
    }
    
    .mobile-menu-toggle:hover,
    .mobile-menu-toggle:focus {
        background-color: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2) !important;
        opacity: 1;
    }
    
    .mobile-menu-toggle:active {
        background-color: rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2) !important;
    }
    
    .mobile-menu-toggle i {
        color: white !important;
    }
    
    /* 모바일에서 드롭다운 메뉴 스타일 */
    .header-area .main-nav .nav .dropdown {
        position: relative;
    }
    
    .header-area .main-nav .nav .dropdown .dropdown-toggle {
        position: relative;
        cursor: pointer;
        user-select: none;
    }
    
    .header-area .main-nav .nav .dropdown .dropdown-toggle::after {
        content: " ▼";
        font-size: 0.8em;
        transition: transform 0.3s ease;
    }
    
    .header-area .main-nav .nav .dropdown.dropdown-open .dropdown-toggle::after {
        transform: rotate(180deg);
    }
    
    .header-area .main-nav .nav .dropdown .dropdown-menu {
        position: static !important;
        display: none;
        width: 100%;
        margin-top: 0;
        margin-left: 0;
        border: none;
        border-radius: 0;
        box-shadow: none;
        background-color: rgba(255, 255, 255, 0.95);
        padding: 0;
    }
    
    .header-area .main-nav .nav .dropdown.dropdown-open .dropdown-menu {
        display: block !important;
    }
    
    .header-area .main-nav .nav .dropdown .dropdown-item {
        padding: 12px 30px;
        border-top: 1px solid #eee;
    }
}

/* 모바일 오프캔버스 메뉴 스타일 */
.offcanvas {
    box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
}

.accordion-button {
    padding: 15px 20px;
    font-weight: 600;
    color: #2d3748;
    background-color: #ffffff;
    border: none;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 12px;
}

.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: #ffffff !important;
    box-shadow: none;
}

.accordion-button:not(.collapsed) i {
    color: #ffffff !important;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(102, 126, 234, 0.5);
}

.accordion-button::after {
    margin-left: auto;
    font-size: 0.9rem;
    opacity: 0.6;
}

.accordion-button:not(.collapsed)::after {
    filter: invert(1);
    opacity: 1 !important;
}

.accordion-button i {
    font-size: 1.1rem;
    width: 24px;
    text-align: center;
    color: #667eea;
    transition: color 0.2s ease;
}

.mobile-sub-link {
    display: flex;
    align-items: center;
    padding: 12px 20px 12px 50px;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    gap: 12px;
}

.mobile-sub-link:hover {
    background-color: #f0f4f8;
    color: #667eea;
    border-left-color: #667eea;
    padding-left: 55px;
}

.mobile-sub-link i {
    color: #667eea;
    width: 20px;
    text-align: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.mobile-sub-link span {
    flex: 1;
}

.mobile-menu-item {
    border-bottom: 1px solid #e9ecef;
}

.mobile-menu-link {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    color: #2d3748;
    text-decoration: none;
    transition: all 0.2s ease;
    font-weight: 500;
    gap: 12px;
    width: 100%;
}

.mobile-menu-link:hover {
    background-color: #f0f4f8;
    color: #667eea;
}

.mobile-menu-link i:first-child {
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
    color: #667eea;
    flex-shrink: 0;
}

.mobile-menu-link span {
    flex: 1;
}

.mobile-menu-link i:last-child {
    margin-left: auto;
    font-size: 0.9rem;
    opacity: 0.6;
    color: #4a5568;
}

.accordion-body {
    background-color: #ffffff;
    padding: 0 !important;
}

.accordion-item {
    border: none;
    border-bottom: 1px solid #e9ecef;
}

.accordion-item:last-child {
    border-bottom: none;
}

/* 스크롤바 스타일링 */
.offcanvas-body::-webkit-scrollbar {
    width: 6px;
}

.offcanvas-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.offcanvas-body::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 3px;
}

.offcanvas-body::-webkit-scrollbar-thumb:hover {
    background: #764ba2;
}

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
<link rel="stylesheet" href="assets/css/fontawesome.css?v=<?php echo $version; ?>">
<link rel="stylesheet" href="assets/css/templatemo-scholar.css?v=<?php echo $version; ?>">
<link rel="stylesheet" href="assets/css/owl.css?v=<?php echo $version; ?>">
<link rel="stylesheet" href="assets/css/animate.css?v=<?php echo $version; ?>">
<link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
<style>
    .screen_out {display:block;overflow:hidden;position:absolute;left:-9999px;width:1px;height:1px;font-size:0;line-height:0;text-indent:-9999px}
    .wrap_content {overflow:hidden;height:330px}
    .wrap_map {width:100%;height:100%;position:relative}
    .wrap_roadview {width:50%;height:300px;float:left;position:relative}
    .wrap_button {position:absolute;left:15px;top:12px;z-index:2}
    .btn_comm {float:left;display:block;width:70px;height:27px;background:url(https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/sample_button_control.png) no-repeat}
    .btn_linkMap {background-position:0 0;}
    .btn_resetMap {background-position:-69px 0;}
    .btn_linkRoadview {background-position:0 0;}
    .btn_resetRoadview {background-position:-69px 0;}
    
    /* 오시는 길 섹션 스타일 */
    .about-location {
        margin-top: 60px;
    }
    
    .location-card {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        background-color: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
    }
    
    .location-map {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    .location-map iframe {
        width: 100%;
        height: 100%;
        min-height: 400px;
        border: none;
    }
    
    .location-map .wrap_map {
        width: 100%;
        height: 100%;
        min-height: 400px;
    }
    
    .location-content {
        padding: 48px 50px;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    
    .location-label {
        font-size: 14px;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
        color: #7a6ad8;
    }
    
    .location-title {
        font-size: 26px;
        font-weight: 700;
        color: #1e1e1e;
        line-height: 1.4;
        margin: 0;
    }
    
    .location-info {
        display: flex;
        flex-direction: column;
        gap: 14px;
        font-size: 15px;
        color: #4a4a4a;
    }
    
    .info-row {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }
    
    .info-row strong {
        min-width: 68px;
        font-weight: 600;
        color: #1e1e1e;
    }
    
    .location-highlights {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    
    .highlight {
        display: flex;
        gap: 12px;
        align-items: center;
        background-color: #f1f0fe;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 14px;
        color: #4a4a4a;
    }
    
    .highlight-icon {
        font-size: 18px;
    }
    
    .location-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .location-actions .btn {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .location-actions .btn-outline {
        border: 1px solid #7a6ad8;
        color: #7a6ad8;
        background-color: transparent;
    }
    
    .location-actions .btn-outline:hover {
        background-color: #7a6ad8;
        color: #fff;
    }
    
    @media (max-width: 992px) {
        .location-card {
            grid-template-columns: 1fr;
        }
        
        .location-content {
            padding: 30px 25px;
        }
        
        .location-map iframe {
            min-height: 300px;
        }
        
        .location-map .wrap_map {
            min-height: 300px;
        }
    }
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
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <div class="logo-sns-container">
                        <!-- 모바일 햄버거 메뉴 버튼 (맨 왼쪽) -->
                        <button class="btn d-md-none mobile-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavMenu" aria-controls="mobileNavMenu">
                            <i class="fa fa-bars"></i>
                        </button>
                        
                        <div class="logo-sns-inner">
                            <a href="index.php" class="logo">
                                <h1>미래기업</h1>
                            </a>
                            <!-- ***** Logo End ***** -->
                            
                            <!-- PC용 SNS 아이콘 (로고 옆에 표시) -->
                            <div class="desktop-sns-icons-wrapper d-none d-md-flex">
                                <button type="button" class="btn btn-outline-danger btn-sm desktop-sns-btn" onclick="popupCenter('https://youtube.com/@miraecorp', 'YouTube', 1920, 1080); return false;" title="미래기업 유튜브">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 26" width="22" height="22">
                                    <rect width="34" height="26" rx="5" ry="5" fill="#FF0000"/>					
                                    <polygon points="10,5 10,20 25,15" fill="#FFFFFF"/>
                                    </svg>
                                </button>	
                                <button type="button" class="btn btn-outline-secondary btn-sm desktop-sns-btn"
                                    onclick="popupCenter('https://www.instagram.com/miraecompany2025/', 'Instagram', 1280, 900); return false;"
                                    title="미래기업 인스타그램">
                                    <img src="https://ko.savefrom.net/img/articles/instagram/new/instagram.webp" 
                                        width="22" 
                                        height="22" 
                                        alt="Instagram">
                                </button>
                            </div>
                            
                            <!-- 모바일 SNS 아이콘 (로고 옆에 표시) -->
                            <div class="mobile-sns-icons d-md-none">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="popupCenter('https://youtube.com/@miraecorp', 'YouTube', 1920, 1080); return false;" title="미래기업 유튜브">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 26" width="18" height="18">
                                    <rect width="34" height="26" rx="5" ry="5" fill="#FF0000"/>					
                                    <polygon points="10,5 10,20 25,15" fill="#FFFFFF"/>
                                    </svg>
                                </button>	
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="popupCenter('https://www.instagram.com/miraecompany2025/', 'Instagram', 1280, 900); return false;"
                                    title="미래기업 인스타그램">
                                    <img src="https://ko.savefrom.net/img/articles/instagram/new/instagram.webp" 
                                      width="18" 
                                      height="18" 
                                      alt="Instagram">
                                </button>
                            </div>
                        </div>
                    </div>
					
            <!-- ***** Menu Start ***** -->
            <ul class="nav align-items-center d-none d-md-flex">
					<li class="scroll-to-section desktop-sns-icons">						
						<button type="button" class="btn btn-outline-danger btn-sm desktop-sns-btn" onclick="popupCenter('https://youtube.com/@miraecorp', 'YouTube', 1920, 1080); return false;" title="미래기업 유튜브">
						  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 26" width="22" height="22">
							<!-- 빨간 배경 (라운드 사각형) -->
							<rect width="34" height="26" rx="5" ry="5" fill="#FF0000"/>					
							<!-- 흰색 재생 버튼 (삼각형) -->
							<polygon points="10,5 10,20 25,15" fill="#FFFFFF"/>
						  </svg>
						</button>	
						<button type="button" class="btn btn-outline-secondary btn-sm desktop-sns-btn"
							onclick="popupCenter('https://www.instagram.com/miraecompany2025/', 'Instagram', 1280, 900); return false;"
							title="미래기업 인스타그램">
							<img src="https://ko.savefrom.net/img/articles/instagram/new/instagram.webp" 
								width="22" 
								height="22" 
								alt="Instagram">
						</button>
					</li>					
					<!-- <li class="scroll-to-section"><a href="#top" class="active">H</a></li>					  -->
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
							<a href="#sillcoverdetail" class="dropdown-item text-dark fw-bold">EL 재료분리대(Sill cover)</a>
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
					<li class="scroll-to-section">
						<a href="login_manager.php" class="btn btn-sm btn-outline-warning admin-btn">
							<i class="fa fa-lock" aria-hidden="true"></i>관리자
						</a>
					</li>

            <li class="scroll-to-section">							
              <?php if (!$chkMobile): ?>
                <?php if (!isset($_SESSION["name"])): ?>
                <form id="login_form" name="login_form" method="post" class="desktop-login-form">
                  <input type="text" id="uid" name="uid" class="form-control login-input" placeholder="ID" required autofocus>
                  <input type="password" id="upw" name="upw" class="form-control login-input" placeholder="Password" required>
                  <button id="loginBtn" class="btn btn-dark btn-sm login-btn" type="button">로그인</button>
                </form>
                <?php else: ?>									
                <form id="login_form" name="login_form" method="post" class="desktop-login-form">											
                  <span class="text-white user-name"><?php echo $_SESSION["name"]; ?> 님</span>
                  <button id="logoutBtn" class="btn btn-dark btn-sm login-btn" type="button">로그아웃</button>
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
                    <!-- ***** Menu End ***** -->
                </nav>
          </div>
</header>
<!-- ***** Header Area End ***** -->

<!-- 모바일 오프캔버스 메뉴 -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileNavMenu" aria-labelledby="mobileNavMenuLabel" style="width: 85%; max-width: 360px;">
	<div class="offcanvas-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px;">
		<h5 class="offcanvas-title" id="mobileNavMenuLabel" style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
			<i class="fa fa-bars"></i> 메뉴
		</h5>
		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(1);"></button>
	</div>
	<div class="offcanvas-body p-0">
		<div class="accordion accordion-flush" id="mobileMenuAccordion">
			<!-- 홈 -->
			<div class="mobile-menu-item">
				<a href="#top" class="mobile-menu-link">
					<i class="fa fa-home"></i>
					<span>홈</span>
					<i class="fa fa-chevron-right" style="margin-left: auto; font-size: 0.9rem; opacity: 0.6;"></i>
				</a>
			</div>

			<!-- 회사안내 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompany">
						<i class="fa fa-building"></i> 회사안내
					</button>
				</h2>
				<div id="collapseCompany" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="#about-us" class="mobile-sub-link"><i class="fa fa-handshake-o"></i> <span>인사말</span></a>
						<a href="#history" class="mobile-sub-link"><i class="fa fa-history"></i> <span>연혁</span></a>
						<a href="#organization" class="mobile-sub-link"><i class="fa fa-sitemap"></i> <span>조직도</span></a>
						<a href="#location" class="mobile-sub-link"><i class="fa fa-map-marker"></i> <span>오시는길</span></a>
						<a href="#notice" class="mobile-sub-link"><i class="fa fa-bullhorn"></i> <span>공지사항</span></a>
					</div>
				</div>
			</div>

			<!-- 사업분야 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBusiness">
						<i class="fa fa-briefcase"></i> 사업분야
					</button>
				</h2>
				<div id="collapseBusiness" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="#ceiling" class="mobile-sub-link"><i class="fa fa-lightbulb-o"></i> <span>EL 조명천장</span></a>
						<a href="#jambcladding" class="mobile-sub-link"><i class="fa fa-cube"></i> <span>EL 잠 덧씌우기</span></a>
						<a href="#sillcoverdetail" class="mobile-sub-link"><i class="fa fa-th"></i> <span>EL 재료분리대</span></a>
					</div>
				</div>
			</div>

			<!-- 설비/공정 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFacility">
						<i class="fa fa-cogs"></i> 설비/공정
					</button>
				</h2>
				<div id="collapseFacility" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="#device" class="mobile-sub-link"><i class="fa fa-wrench"></i> <span>설비</span></a>
						<a href="#processchart" class="mobile-sub-link"><i class="fa fa-industry"></i> <span>생산공정</span></a>
					</div>
				</div>
			</div>

			<!-- 품질/안전 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQuality">
						<i class="fa fa-shield"></i> 품질/안전
					</button>
				</h2>
				<div id="collapseQuality" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="#QCplan" class="mobile-sub-link"><i class="fa fa-check-circle"></i> <span>품질목표/품질방침</span></a>
						<a href="#iso" class="mobile-sub-link"><i class="fa fa-certificate"></i> <span>ISO인증서</span></a>
						<a href="#NG" class="mobile-sub-link"><i class="fa fa-exclamation-triangle"></i> <span>품질불량</span></a>
						<a href="#RiskAssessement" class="mobile-sub-link"><i class="fa fa-shield"></i> <span>위험성평가</span></a>
					</div>
				</div>
			</div>

			<!-- 제품/시공 갤러리 -->
			<div class="mobile-menu-item">
				<a href="#gallery" class="mobile-menu-link">
					<i class="fa fa-image"></i>
					<span>제품/시공 갤러리</span>
					<i class="fa fa-chevron-right" style="margin-left: auto; font-size: 0.9rem; opacity: 0.6;"></i>
				</a>
			</div>

			<!-- 견적문의 -->
			<div class="mobile-menu-item">
				<a href="#contact" class="mobile-menu-link">
					<i class="fa fa-envelope"></i>
					<span>견적문의</span>
					<i class="fa fa-chevron-right" style="margin-left: auto; font-size: 0.9rem; opacity: 0.6;"></i>
				</a>
			</div>

			<!-- 관리자 -->
			<div class="mobile-menu-item">
				<a href="login_manager.php" class="mobile-menu-link">
					<i class="fa fa-lock"></i>
					<span>관리자</span>
					<i class="fa fa-chevron-right" style="margin-left: auto; font-size: 0.9rem; opacity: 0.6;"></i>
				</a>
			</div>

			<?php if (isset($_SESSION["name"])): ?>
			<!-- 사용자 프로필 섹션 -->
			<div class="mobile-menu-item" style="margin-top: 10px; border-top: 2px solid #e9ecef; padding-top: 0;">
				<div style="padding: 15px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
					<i class="fa fa-user-circle" style="font-size: 1.3rem;"></i>
					<span><?php echo $_SESSION["name"]; ?>님</span>
				</div>
			</div>

			<!-- 로그아웃 -->
			<div class="mobile-menu-item">
				<a href="javascript:void(0);" id="mobileLogoutBtn" class="mobile-menu-link">
					<i class="fa fa-sign-out"></i>
					<span>로그아웃</span>
					<i class="fa fa-chevron-right" style="margin-left: auto; font-size: 0.9rem; opacity: 0.6;"></i>
				</a>
			</div>
			<?php else: ?>
			<!-- 로그인 폼 -->
			<div class="mobile-menu-item" style="margin-top: 10px; border-top: 2px solid #e9ecef; padding: 15px 20px;">
				<form id="mobile_login_form" name="mobile_login_form" method="post" style="display: flex; flex-direction: column; gap: 10px;">
					<div style="display: flex; flex-direction: column; gap: 8px;">
						<input type="text" id="mobile_uid" name="uid" class="form-control" placeholder="ID" required autofocus style="height: 40px; font-size: 14px;">
						<input type="password" id="mobile_upw" name="upw" class="form-control" placeholder="Password" required style="height: 40px; font-size: 14px;">
					</div>
					<button id="mobileLoginBtn" class="btn btn-dark" type="button" style="width: 100%; height: 40px; font-size: 14px; font-weight: 600;">로그인</button>
				</form>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

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
              <a href="#sillcoverdetail">더 알아보기</a>
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
            <p><strong>2012년 11월 </strong>			
			</p>
            <div class="author">
              <img src="assets/images/miraeHistory1.png" style="width:auto;" alt="">
              <span class="category"> 
			    미래기업 설립<br>
				미래기업 엘리베이터 표준천정개발 (4종)
				</span>
            </div>
          </div>
          <div class="item">
            <p><strong>2013년 </strong>			
			</p>
            <div class="author">
              <img src="assets/images/miraeHistory2.png" style="width:auto;" alt="">
              <span class="category"> 
				오티스엘리베이터 협력업체 등록 <br>
				표천천정 개발 및 납품 (2종)
				</span>
            </div>
          </div>
          <div class="item">
            <p><strong>2014년 </strong>			
			</p>
            <div class="author">
              <img src="assets/images/miraeHistory3.png" style="width:auto;" alt="">
              <span class="category"> 
					㈜미래기업으로 법인전환
				</span>
            </div>
          </div>
          <div class="item">
            <p><strong>2016년 </strong>			
			</p>
            <div class="author">
              <img src="assets/images/miraeHistory4.png" style="width:auto;" alt="">
              <span class="category"> 
					오티스엘리베이터 쇼룸공사 
				</span>
            </div>
          </div>
          <div class="item">
            <p><strong>2017년 </strong>			
			</p>
            <div class="author">
              <img src="assets/images/miraeHistory5.png" style="width:auto;" alt="">
              <span class="category"> 
			    공장이전 및  레이저컷팅기, 절곡기외 다수 가공기계설치 <br>
				미쓰비시엘리베이터  표준천정 개발 및 납품 <br>
				엘리베이터 조명장치 특허 출원 <br>
				</span>
            </div>
          </div>
          <div class="item">
            <p><strong>2020년 </strong>			
			</p>
            <div class="author">
              <img src="assets/images/miraeHistory4.png" style="width:auto;" alt="">
              <span class="category"> 
					LH공사 엘리베이터 표준인테리어 기법 채택(당사제출)
				</span>
            </div>
          </div>
          <div class="item">
            <p><strong>2021년 </strong>			
			</p>
            <div class="author">
              <img src="assets/images/miraeHistory5.png" style="width:auto;" alt="">
              <span class="category"> 
					현위치  공장이전  
				</span>
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

<?php
// 본사 주소 정보
$company_address = "경기도 김포시 양촌읍 흥신로 220-27";
$company_address_encoded = urlencode($company_address);
$naver_map_url = "https://map.naver.com/v5/search/" . $company_address_encoded;
$kakao_map_url = "https://map.kakao.com/link/search/" . $company_address_encoded;
?>
<div class="section" id="location">
  <div class="container">
    <div class="about-location">						
      <div class="location-card">
        <div class="location-map" aria-label="<?php echo COMPANY_NAME; ?> 본사 위치">
          <iframe
            title="<?php echo COMPANY_NAME; ?> 본사 지도"
            src="https://www.google.com/maps?q=<?php echo urlencode($company_address); ?>&hl=ko&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="location-content">
          <span class="location-label">오시는 길</span>
          <h3 class="location-title"><?php echo COMPANY_NAME; ?> 본사</h3>
          <div class="location-info">            
            <div class="info-row">
              <strong>본사</strong>
              <span><?php echo htmlspecialchars($company_address); ?></span>
            </div>
            <div class="info-row">
              <strong>대표번호</strong>
              <span>031-983-8440</span>
            </div>
            <div class="info-row">
              <strong>운영시간</strong>
              <span>평일 09:00 - 18:00 (주말·공휴일 예약 상담)</span>
            </div>
          </div>
          <div class="location-actions">
            <a href="<?php echo $naver_map_url; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm">네이버 지도 열기</a>
            <a href="<?php echo $kakao_map_url; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm">카카오 내비로 길찾기</a>
          </div>
        </div>
      </div>			
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
          <h2>FAQ 공지 </h2>
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
            </div>
          </div>
        </div>		
        		
      </div>
    </div>
  </section>

	<!-- 재료분리대 Start -->
  <section class="section sillcoverdetail" id="sillcoverdetail" >

  <div class="container">  
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-heading">
            <h6>  도어 하단에 설치되어 승강기 안전에 도움주는 부품 </h6>
            <h2> 재료분리대 </h2>
          </div>
        </div>
      </div>
      <div class="row event_box d-flex justify-content-center">	  
        <div class="col-lg-6 col-md-6 align-self-center mb-70 event_outer col-md-6">
          <div class="events_item">
            <div class="thumb">
              <img src="img/sillcover1.jpg" alt="">
              <!-- <span class="category">ELC-011</span>              -->
            </div>			
            <div class="down-content">
              <span class="author"> 재료분리대는 공간을 구분하기 위한 용도로 바닥재와 바닥재의 경계에 사용되는 재료   </span>              
            </div>
          </div>
        </div>	        
        <div class="col-lg-6 col-md-6 align-self-center mb-70 event_outer col-md-6">
          <div class="events_item">
            <div class="thumb">
              <img src="img/sillcover2.jpg" alt="">
              <!-- <span class="category">ELC-011</span>              -->
            </div>			
            <div class="down-content">			
				  <h3 class="py-5 border-top border-dark" data-aos="fade-right"> 교체용 재료분리대 </h3>
				  <p data-aos="fade-left" data-aos-delay="200"> SILL은 도어 하단에 설치되어 승강 안전을 돕고, SILL COVER는 고강도 소재와 정밀한 설계로 내구성을 높이며, 세련된 마감으로 건축미를 완성합니다.   </p>
				  <p data-aos="fade-left" data-aos-delay="200"> 각도 조절기능	  </p>
				  <p data-aos="fade-right" data-aos-delay="200"> 렌더링 참조  </p>    
            </div>
          </div>
      </div>
      </div>
    </div>
	<!-- 재료분리대 End -->    	
  </section>

  <div class="section fun-facts">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="wrapper">
            <div class="row">
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="12" data-speed="1000"></h2>
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
					<?php  include $root_dir . '/qc/prod_jamb_sub.php';  ?>
                </div>
            </div>	
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">            
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                    
                    <div class="mb-4 text-center">                        
                        <h5 class="fs-4 fw-bold mt-3">  <?=date("Y")?>년도 제조통계 </h5>
                    </div>                    
					<?php  include $root_dir . '/qc/prod_ceiling.php';  ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-top mb-30 ">            
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-top">                                        
					<?php include getDocumentRoot() . '/qc/rate_badAllexcept.php' ?>  					                
					<?php include getDocumentRoot() . '/qc/rate_badDetailexcept.php' ?>   
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">    
					<h5 class="fs-4 fw-bold text-center"> (모델구분 : 쟘) 불량율 </h5>  			
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                                        
					<?php include getDocumentRoot() . '/qc/rate_badAllJamb.php' ?>   
					 <?php include getDocumentRoot() . '/qc/rate_badDetailJamb.php' ?>   
					</div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center mb-30 ">    
					<h5 class="fs-4 fw-bold text-center"> (모델구분 : 천장) 불량율  </h5>  			
                <div class="d-flex flex-column mt-3 mb-1 justify-content-center align-items-center">                                        
					<?php include getDocumentRoot() . '/qc/rate_badAllJamb.php' ?>  
					<?php include getDocumentRoot() . '/qc/rate_badDetailCeiling.php' ?>   					
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
              
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone7.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author">엘리베이터 의장품 시공</span>
              <h6> 한남도 리버힐 </h6>
            </div>
          </div>
        </div>	
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone8.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author">엘리베이터 의장품 시공</span>
              <h6> 마리오 아울렛 </h6>
            </div>
          </div>
        </div>	
        <div class="col-lg-4 col-md-6 align-self-center mb-30  col-md-6 ">
          <div class="events_item">
            <div class="thumb">
              <img src="img/ceilingdone9.jpg" alt="">              
            </div>
            <div class="down-content">
				<span class="author">엘리베이터 의장품 시공</span>
              <h6>옵티멈  스포츠 센터 </h6>
            </div>
          </div>
        </div>	  
       
       		
		
      </div>
    </div>
  </section>

<?php include __DIR__ . '/includes/portfolio_section.php'; ?>

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
  <script src="assets/js/isotope.min.js?v=<?php echo $time; ?>"></script>
  <script src="assets/js/owl-carousel.js?v=<?php echo $time; ?>"></script>
  <script src="assets/js/counter.js?v=<?php echo $time; ?>"></script>
  <script src="assets/js/custom.js?v=<?php echo $time; ?>"></script>
  
  
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
	// 로그인 처리 함수
	function performLogin() {
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
				{					
					if(Number(data["level"]) ===9)
						location.href = '/partner/index.php';	  // 우성스틸 파트너
					else if(Number(data["level"]) ===8)
						location.href = '/p/index.php';	  // 소장
					else if (Number(data["level"]) ===7)  
						location.href = '/outorder/list.php';		  // 협력사(덴크리, 다온텍)
					else if (Number(data["level"]) ===20)
						location.href = '/phomi/list.php';		  // 포미스톤
					else 
						location.href = '/index2.php';		  // 통합사이트로 이동										
				}
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
	}
	
	// 로그인 버튼 클릭 이벤트
	$("#loginBtn").click(function(){ 
		performLogin();
	});
	
	// 모바일 로그인 버튼 클릭 이벤트
	$("#mobileLoginBtn").click(function(){ 
		// 모바일 폼의 값을 메인 폼에 복사
		$("#uid").val($("#mobile_uid").val());
		$("#upw").val($("#mobile_upw").val());
		performLogin();
	});
	
	// 폼 제출 이벤트 (엔터키 처리)
	$("#login_form").on('submit', function(e){
		e.preventDefault(); // 기본 폼 제출 방지
		performLogin();
		return false;
	});
	
	// 모바일 폼 제출 이벤트 (엔터키 처리)
	$("#mobile_login_form").on('submit', function(e){
		e.preventDefault(); // 기본 폼 제출 방지
		// 모바일 폼의 값을 메인 폼에 복사
		$("#uid").val($("#mobile_uid").val());
		$("#upw").val($("#mobile_upw").val());
		performLogin();
		return false;
	});
	
	// ID, Password 입력창에서 엔터키 처리
	$("#uid, #upw").on('keypress', function(e){
		if(e.which === 13) { // 엔터키 코드
			e.preventDefault();
			performLogin();
			return false;
		}
	});
	
	// 모바일 ID, Password 입력창에서 엔터키 처리
	$("#mobile_uid, #mobile_upw").on('keypress', function(e){
		if(e.which === 13) { // 엔터키 코드
			e.preventDefault();
			// 모바일 폼의 값을 메인 폼에 복사
			$("#uid").val($("#mobile_uid").val());
			$("#upw").val($("#mobile_upw").val());
			performLogin();
			return false;
		}
	});		
		
	$("#logoutBtn").click(function(){ 	
			location.href = './login/logout.php';		  // log out
	});		
	
	// 모바일 로그아웃 버튼
	$("#mobileLogoutBtn").click(function(){ 	
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

function popupCenter(href, pop_name, w, h) {
    // 고유한 팝업 창 이름을 생성하기 위해 현재 시간을 이용
    var uniqueName = pop_name + '_' + new Date().getTime();

    // 화면 가로 위치
    var xPos = (window.innerWidth / 2) - (w / 2) + window.screenX;
    // 화면 세로 위치
    var yPos = (window.innerHeight / 2) - (h / 2) + window.screenY;

    window.open(href, uniqueName, "width=" + w + ", height=" + h + ", left=" + xPos + ", top=" + yPos + ", target=_blank, menubar=yes, status=yes, titlebar=yes, resizable=yes");
}
  
</script>

<!-- Portfolio JavaScript -->
<script src="js/portfolio.js?v=<?php echo $version; ?>"></script>

<script>
// 모바일 메뉴 링크 클릭 시 오프캔버스 닫기
$(document).ready(function() {
    function isMobile() {
        return $(window).width() < 768;
    }
    
    // 모바일 메뉴 링크 클릭 시 오프캔버스 닫기
    $(document).on('click', '.mobile-menu-link, .mobile-sub-link', function(e) {
        // 로그아웃 버튼은 제외 (이미 처리됨)
        if (!$(this).attr('id') || $(this).attr('id') !== 'mobileLogoutBtn') {
            var href = $(this).attr('href');
            // 모든 링크 클릭 시 메뉴 닫기 (앵커 링크 포함)
            if (href && href !== 'javascript:void(0);') {
                // 약간의 지연을 두어 스크롤이 먼저 실행되도록
                setTimeout(function() {
                    var offcanvasElement = document.getElementById('mobileNavMenu');
                    if (offcanvasElement) {
                        var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                        if (bsOffcanvas) {
                            bsOffcanvas.hide();
                        }
                    }
                }, 150);
            }
        }
    });
    
    // PC 화면에서는 Bootstrap 드롭다운 활성화
    if (!isMobile()) {
        $('.dropdown-toggle').attr('data-bs-toggle', 'dropdown');
    }
    
    // 화면 크기 변경 시 처리
    $(window).on('resize', function() {
        if (!isMobile()) {
            $('.dropdown-toggle').attr('data-bs-toggle', 'dropdown');
        } else {
            $('.dropdown-toggle').removeAttr('data-bs-toggle');
        }
    });
});
</script>

  
  </body>
</html>