<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 안전보건 위험성평가 카드뉴스 메뉴
 * 
 * 안전보건 관련 카드뉴스 목록을 표시
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';

// 요청 변수 초기화
$mcno = $_REQUEST["mcname"] ?? '';

include includePath('load_header.php');
?>

<meta property="og:type" content="미래기업 통합정보시스템">
<meta property="og:title" content="위험성평가 전산시스템">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="정확한 업무처리를 위한 필수사이트!">
<meta property="og:image" content="https://8440.co.kr/img/miraethumbnail.jpg">

<title>안전보건 위험성평가 카드뉴스</title>

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
        h2.fw-bolder {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
            padding: 0.25rem !important;
        }
        
        h2.fw-bolder img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* 카드 그리드 최적화 */
        .row.gx-4.gx-lg-5 {
            margin: 0 !important;
            padding: 0.5rem !important;
        }
        
        .row.gx-4.gx-lg-5 .col {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            margin: 0.5rem 0 !important;
            flex: 0 0 100% !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0 auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 제목 최적화 */
        .card-body h4 {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            margin: 0.5rem 0 !important;
            padding: 0.25rem !important;
        }
        
        /* 이미지 컨테이너 최적화 */
        .image-container {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            min-height: 200px !important;
            overflow: hidden !important;
        }
        
        .image-container img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: cover !important;
            object-position: center !important;
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
        <!-- 미점검 리스트 출력 -->
        <h2 class="fw-bolder mb-5">
            <img src="<?= getBaseUrl() ?>/img/safe4.jpg" style="width:100%;height:100%;" alt="안전보건">
        </h2>
        <h2 class="fw-bolder mb-4">안전보건 카드뉴스</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

<?php
// 권한 체크
if ($level > 8) {
    $_SESSION["url"] = getBaseUrl() . '/safetycard/laser.php?mcno=' . $mcno;
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 현재일자 변수지정
$todate = date("Y-m-d");
$nowday = date("Y-m-d");

// 카운터 초기화
$counter = 0;

// 카드뉴스 데이터 배열
$mcno_arr = array(
    "[위험성평가] 슈퍼맨이아니라면",
    "용접용단 작업자편",
    "전기기계기구 작업",
    "[위험성평가]혜택-사업주교육인정",
    "[위험성평가] 기업의 이익",
    " [안젤뉴스룸] 재난 수준의 폭염, 노동자 사망",
    "[안젤뉴스룸] 지게차 충돌 사고"
);

$qrcode_arr = array(
    "safeimg1.jpg",
    "safeimg2.jpg",
    "safeimg3.jpg",
    "safeimg4.jpg",
    "safeimg5.jpg",
    "safeimg6.jpg",
    "safeimg7.jpg"
);

for ($i = 0; $i < count($mcno_arr); $i++) {
    $img_filename = $qrcode_arr[$i];  // 파일명만 저장
    $qrcode = getBaseUrl() . '/img/' . $img_filename;  // 표시용 전체 URL
?>
            <div class="col mb-5">
                <div class="card h-100" onclick="choiceMC(<?= $i ?>,'<?= htmlspecialchars($img_filename, ENT_QUOTES, 'UTF-8') ?>','<?= htmlspecialchars($mcno_arr[$i], ENT_QUOTES, 'UTF-8') ?>');">
                    <!-- Product details-->
                    <div class="card-body p-4">
                        <div class="text-center fs-3">
                            <!-- name-->
                            <h4 class="fw-bolder"><?= htmlspecialchars($mcno_arr[$i], ENT_QUOTES, 'UTF-8') ?></h4>
                        </div>
                        <div class="text-center fs-3">
                            <div class="image-container">
                                <img class="image-container" src="<?= htmlspecialchars($qrcode, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:300px;" alt="<?= htmlspecialchars($mcno_arr[$i], ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        </div>
                    </div>
                    <!-- Product actions-->
                </div>
            </div>
<?php
}
?> 					
			


					
        </div>
    </div>
</section>

<!-- ajax 전송으로 DB 수정 -->
<?php include includePath('formload.php'); ?>

<!-- Footer-->
<?php include includePath('shop/footer.php'); ?>
<!-- Core theme JS-->

<script>
function choiceMC(index, imgFilename, name) {
    // 인덱스와 파일명을 전달 (한글 깨짐 방지)
    var link = '<?= getBaseUrl() ?>/safetycard/laser.php?index=' + index + '&img=' + encodeURIComponent(imgFilename) + '&name=' + encodeURIComponent(name);
    window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=1700,height=850");
}

// 서버에 작업 기록
$(document).ready(function() {
    saveLogData('안전보건 카드뉴스');
});
</script>
</body>
</html>