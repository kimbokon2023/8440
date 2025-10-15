<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    $_SESSION["url"] = $WebSite . 'error/index.php?user_name=' . $user_name;
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 관리자 권한 설정
$admin = 0;
$admin_names = array('소현철', '김보곤', '최장중', '이경묵');
if (in_array($user_name, $admin_names)) {
    $admin = 1;
}

$tablename = 'error';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// _row.php에서 사용되는 변수 초기화
$num = '';
$occur = '';
$occurconfirm = '';
$approve = '';
$errortype = '';
$place = '';
$reporter = '';
$content = '';
$method = '';
$involved = '';

// 서버의 정보를 읽어와 메인화면 꾸미기
$sql = "SELECT * FROM {$DB}.error ORDER BY num DESC";
$numarr = array();

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include "_row.php";
    }
} catch (PDOException $ex) {
    error_log("부적합 리스트 조회 오류: " . $ex->getMessage());
}

// 랜덤하게 유튜브 주소 추출
$youtube_arr = array();
array_push($youtube_arr, "https://www.youtube.com/embed/VPwhUEc84pg");
array_push($youtube_arr, "https://www.youtube.com/embed/NcFf9JhcHDQ");
array_push($youtube_arr, "https://www.youtube.com/embed/aXB5XNmG-TE");
array_push($youtube_arr, "https://www.youtube.com/embed/5ulG8-brBng");

$youtubeURL = $youtube_arr[rand(0, count($youtube_arr) - 1)];

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

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
                                                    <img src="../img/qm1.jpg" alt="품질경영 1">
                                                    <img src="../img/qm2.jpg" alt="품질경영 2">
                                                    <img src="../img/qm3.jpg" alt="품질경영 3">
                                                    <img src="../img/qm4.jpg" alt="품질경영 4">
                                                    <img src="../img/qm5.jpg" alt="품질경영 5">
                                                    <img src="../img/qm6.jpg" alt="품질경영 6">
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
                                        <iframe class="embed-responsive-item" src="<?= htmlspecialchars($youtubeURL) ?>" frameborder="0" allowfullscreen></iframe>
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
        <?php
        // 기간 설정
        if ($fromdate == "") {
            $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
        }
        
        if ($todate == "") {
            $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
            $Transtodate = strtotime($todate . '+1 days');
            $Transtodate = date("Y-m-d", $Transtodate);
        } else {
            $Transtodate = strtotime($todate);
            $Transtodate = date("Y-m-d", $Transtodate);
        }
        
        // SQL 쿼리 생성
        if ($mode == "search" || $mode == "") {
            if ($search == "") {
                $sql = "SELECT * FROM {$DB}.error ORDER BY num DESC";
            } elseif ($search == "결재상신 1차결재") {
                $sql = "SELECT * FROM {$DB}.error WHERE approve = '결재상신' OR approve = '1차결재' ORDER BY num DESC";
                $search = null;
            } else {
                // 기본 SQL Injection 방어
                $search_safe = str_replace("'", "''", $search);
                $sql = "SELECT * FROM {$DB}.error WHERE " .
                       "(reporter LIKE '%{$search_safe}%') OR " .
                       "(place LIKE '%{$search_safe}%') OR " .
                       "(content LIKE '%{$search_safe}%') OR " .
                       "(method LIKE '%{$search_safe}%') OR " .
                       "(involved LIKE '%{$search_safe}%') OR " .
                       "(approve LIKE '%{$search_safe}%') " .
                       "ORDER BY occur DESC";
            }
        }
        
        // 레코드 조회
        try {
            $stmh = $pdo->query($sql);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                include "_row.php";
            }
        } catch (PDOException $ex) {
            error_log("부적합 검색 오류: " . $ex->getMessage());
        }
        ?>
        
        <input id="view_table" name="view_table" type="hidden" value="<?= htmlspecialchars($view_table) ?>">
        <input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert) ?>">
        <input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert) ?>">
        <input type="hidden" id="order_alert" name="order_alert" value="<?= htmlspecialchars($order_alert) ?>">
    </div>
    
    <!-- Footer -->
    <?php include "footer.php"; ?>
</form>

<script>
// ES5 호환 JavaScript
var dataTable;
var errorpageNumber;

$(document).ready(function() {
    // 서버에 작업 기록
    saveLogData('품질불량 관리기법');
});
</script>

</body>
</html>


