<?php
/**
 * 품질불량 관리기법/교육 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    $_SESSION["url"] = getBaseUrl() . '/error/index.php?user_name=' . urlencode($user_name);
    header("Location: " . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 관리자 권한 설정
$admin = 0;
$admin_names = array('소현철', '김보곤', '최장중', '이경묵');
if (in_array($user_name, $admin_names)) {
    $admin = 1;
}

$tablename = 'error';

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';

// 기간 설정
if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = date("Y-m-d", strtotime($todate . ' +1 days'));
} else {
    $Transtodate = date("Y-m-d", strtotime($todate));
}

// DB 연결
require_once includePath('lib/mydb.php');
$pdo = db_connect();

// SQL 쿼리 생성
$sql = '';

if ($mode == "search" || $mode == "") {
    if ($search == "") {
        $sql = "SELECT * FROM {$DB}.error ORDER BY num DESC";
    } elseif ($search == "결재상신 1차결재") {
        $sql = "SELECT * FROM {$DB}.error WHERE approve = '결재상신' OR approve = '1차결재' ORDER BY num DESC";
    } elseif ($search != "") {
        $sql = "SELECT * FROM {$DB}.error WHERE " .
               "(reporter LIKE '%{$search}%') OR " .
               "(place LIKE '%{$search}%') OR " .
               "(content LIKE '%{$search}%') OR " .
               "(method LIKE '%{$search}%') OR " .
               "(involved LIKE '%{$search}%') OR " .
               "(approve LIKE '%{$search}%') " .
               "ORDER BY occur DESC";
    }
}

// 랜덤하게 유튜브 주소 추출
$youtube_arr = array(
    "https://www.youtube.com/embed/VPwhUEc84pg",
    "https://www.youtube.com/embed/NcFf9JhcHDQ",
    "https://www.youtube.com/embed/aXB5XNmG-TE",
    "https://www.youtube.com/embed/5ulG8-brBng"
);

$youtubeURL = $youtube_arr[rand(0, count($youtube_arr) - 1)];

include getDocumentRoot() . '/load_header.php';
?>

<title>품질불량 관리기법/교육</title>
<div id="cookiedisplay"></div>

</head>

<body>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<form name="board_form" id="board_form" method="post">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center mt-2 mb-2">
                                    <!-- 품질불량 관리기법 -->
                                    <div id="Materialshow" class="mb-5">
                                        <h3 class="text-center text-primary">품질불량 관리기법</h3>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mt-2 mb-2">
                                    <div id="Material">
                                        <section class="page-section">
                                            <div class="container">
                                                <div class="row text-center">
                                                    <?php include '8d.php'; ?>
                                                </div>
                                                <div class="row text-left">
                                                    <?php include 'fmea.php'; ?>
                                                    <img src="<?= getBaseUrl() ?>/img/qm1.jpg" alt="품질경영 1" class="img-fluid">
                                                    <img src="<?= getBaseUrl() ?>/img/qm2.jpg" alt="품질경영 2" class="img-fluid">
                                                    <img src="<?= getBaseUrl() ?>/img/qm3.jpg" alt="품질경영 3" class="img-fluid">
                                                    <img src="<?= getBaseUrl() ?>/img/qm4.jpg" alt="품질경영 4" class="img-fluid">
                                                    <img src="<?= getBaseUrl() ?>/img/qm5.jpg" alt="품질경영 5" class="img-fluid">
                                                    <img src="<?= getBaseUrl() ?>/img/qm6.jpg" alt="품질경영 6" class="img-fluid">
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                <div class="d-flex px-1 px-lg-1 mt-1 justify-content-center">
                                    <h5 class="mb-1 text-secondary">미래기업 직원여러분의 지속적 관심/분석/개선이 불량감소에 큰 도움이 됩니다.</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <!-- YouTube 교육영상 -->
                                <div class="d-flex mt-1 mb-1 justify-content-center">
                                    <h5>품질관련 교육영상</h5>
                                </div>
                                <div class="d-flex mt-1 mb-2 justify-content-center">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="<?= htmlspecialchars($youtubeURL, ENT_QUOTES, 'UTF-8') ?>" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <input id="view_table" name="view_table" type='hidden' value='<?= htmlspecialchars($view_table, ENT_QUOTES, 'UTF-8') ?>'>
        <input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="order_alert" name="order_alert" value="<?= htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <!-- Footer -->
    <?php include "footer.php"; ?>
</form>

<script>
// ES5 호환 JavaScript
var dataTable; // DataTables 인스턴스 전역 변수
var errorpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {
    // 서버에 작업 기록
    saveLogData('품질불량 관리기법');
});
</script>

</body>
</html>
