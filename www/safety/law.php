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
        
        section {
            padding: 0.5rem 0 !important;
        }
        
        /* 제목 영역 최적화 */
        h2 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
            padding: 0.25rem !important;
        }
        
        /* 텍스트 영역 최적화 */
        .row.justify-content-center {
            padding: 0.5rem !important;
            margin: 0.5rem 0 !important;
        }
        
        .row.gx-4.gx-lg-5 {
            padding: 0.5rem !important;
            margin: 0.5rem 0 !important;
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* iframe 최적화 */
        iframe {
            width: 100% !important;
            max-width: 100% !important;
            height: 70vh !important;
            min-height: 400px !important;
            border: none !important;
            box-sizing: border-box !important;
        }
        
        .container-fluid.w-90 {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            margin: 0 auto !important;
        }
        
        /* 이미지 최적화 */
        img {
            max-width: 100% !important;
            height: auto !important;
            width: auto !important;
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
	 
    <div class="container-fluid w-90">		
        <iframe src="https://www.law.go.kr/%ED%96%89%EC%A0%95%EA%B7%9C%EC%B9%99/%EC%82%AC%EC%97%85%EC%9E%A5%EC%9C%84%ED%97%98%EC%84%B1%ED%8F%89%EA%B0%80%EC%97%90%EA%B4%80%ED%95%9C%EC%A7%80%EC%B9%A8" width="100%" height="600px" style="max-width: 100%;"></iframe>		
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
