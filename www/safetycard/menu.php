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