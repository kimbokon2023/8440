<?php
/**
 * 장비 점검 메뉴 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 변수 초기화
$mcno = $_REQUEST["mcname"] ?? '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$WebSite = "{$protocol}://{$host}/";

// 기타 변수 초기화
$title_message = '미래기업 고객만족 품질경영';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    $_SESSION["url"] = $WebSite . "mymachine/laser.php?mcno=" . urlencode($mcno);
    sleep(1);
    header("Location: " . $WebSite . "login/login_form.php");
    exit;
}

include getDocumentRoot() . '/load_header.php';
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>
<?php require_once(includePath('myheader.php')); ?>

<?php
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 장비점검리스트 불러옴
include "load_DB.php";
?>

<div class="container">
    <div class="card mt-2 mb-2">
        <div class="card-body">
            <div class="d-flex mt-3 mb-1 justify-content-center">
                <img src="../img/qc-bg.jpg" style="width:100%;" alt="QC Background">
            </div>
            
            <h5 class="fw-bolder mb-4">점검 장비</h5>
            
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                $todate = date("Y-m-d");
                $nowday = date("Y-m-d");
                
                $sql = "SELECT * FROM mirae8440.mymc ORDER BY num";
                
                $counter = 0;
                $num_arr = [];
                $mcno_arr = [];
                $mcname_arr = [];
                $mcspec_arr = [];
                $mcmaker_arr = [];
                $mcmain_arr = [];
                $mcsub_arr = [];
                $qrcode_arr = [];
                
                try {
                    $stmh = $pdo->query($sql);
                    $rowNum = $stmh->rowCount();
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $num = $row["num"];
                        $mcno = $row["mcno"];
                        $mcname = $row["mcname"];
                        $mcspec = $row["mcspec"];
                        $mcmaker = $row["mcmaker"];
                        $mcmain = $row["mcmain"];
                        $mcsub = $row["mcsub"];
                        $qrcode = $row["qrcode"];
                        
                        $num_arr[$counter] = $row["num"];
                        $mcno_arr[$counter] = $row["mcno"];
                        $mcname_arr[$counter] = $row["mcname"];
                        $mcspec_arr[$counter] = $row["mcspec"];
                        $mcmaker_arr[$counter] = $row["mcmaker"];
                        $mcmain_arr[$counter] = $row["mcmain"];
                        $mcsub_arr[$counter] = $row["mcsub"];
                        
                        // 동적 QR 코드 URL 생성
                        $qrcode_tmp = "{$protocol}://{$host}/img/" . $qrcode . ".png";
                        $qrcode_arr[$counter] = $qrcode_tmp;
                ?>
                
                <div class="col mb-2">
                    <div class="card h-100" onclick="choiceMC(<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>,'<?= htmlspecialchars($mcmain, ENT_QUOTES, 'UTF-8') ?>','<?= htmlspecialchars($mcsub, ENT_QUOTES, 'UTF-8') ?>','<?= htmlspecialchars($mcno, ENT_QUOTES, 'UTF-8') ?>');" style="cursor: pointer;">
                        <div class="card-body p-2">
                            <div class="text-center">
                                <h5 class="fw-bolder"><?= htmlspecialchars($row["mcname"], ENT_QUOTES, 'UTF-8') ?></h5>
                            </div>
                            <div class="text-center">
                                <span class="fw-bolder"><?= htmlspecialchars($row["mcspec"], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <div class="text-center">
                                <span class="fw-bolder">점검(정) <?= htmlspecialchars($row["mcmain"], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <div class="text-center">
                                <span class="fw-bolder">점검(부) <?= htmlspecialchars($row["mcsub"], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <div class="text-center">
                                <span class="fw-bolder">
                                    <img src="<?= htmlspecialchars($qrcode_tmp, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;" alt="QR Code">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
                        $counter++;
                    }
                } catch (PDOException $ex) {
                    error_log("장비 목록 조회 오류: " . $ex->getMessage());
                    echo "<div class='alert alert-danger'>오류: 장비 목록을 불러오는 중 문제가 발생했습니다.</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- ajax 전송으로 DB 수정 -->
<?php include "../formload.php"; ?>

<!-- Footer-->
<?php include "../shop/footer.php"; ?>

<script type="text/javascript">
(function() {
    'use strict';
    
    // 현재 프로토콜과 호스트 가져오기
    var protocol = window.location.protocol;
    var host = window.location.host;
    var baseUrl = protocol + '//' + host;
    
    /**
     * 장비 선택 함수
     */
    window.choiceMC = function(num, mcmain, mcsub, mcno) {
        var link = '';
        
        switch (num) {
            case 1:
                link = baseUrl + '/mymachine/laser.php?mcno=laser01&mcname=laser01';
                break;
            case 2:
                link = baseUrl + '/mymachine/laser.php?mcno=vcut01&mcname=vcut01';
                break;
            case 3:
                link = baseUrl + '/mymachine/laser.php?mcno=bending01&mcname=bending01';
                break;
            case 4:
                link = baseUrl + '/mymachine/laser.php?mcno=shearing01&mcname=shearing01';
                break;
            case 5:
                link = baseUrl + '/mymachine/laser.php?mcno=welder01&mcname=welder01';
                break;
            case 6:
                link = baseUrl + '/mymachine/laser.php?mcno=welder02&mcname=welder02';
                break;
            case 7:
                link = baseUrl + '/mymachine/laser.php?mcno=welder03&mcname=welder03';
                break;
            case 8:
                link = baseUrl + '/mymachine/laser.php?mcno=welder04&mcname=welder04';
                break;
            case 9:
                link = baseUrl + '/mymachine/laser.php?mcno=motor01&mcname=motor01';
                break;
            case 10:
                link = baseUrl + '/mymachine/laser.php?mcno=motor02&mcname=motor02';
                break;
            case 11:
                link = baseUrl + '/mymachine/laser.php?mcno=tapdrill01&mcname=tapdrill01';
                break;
            case 12:
                link = baseUrl + '/mymachine/laser.php?mcno=comp01&mcname=comp01';
                break;
            case 13:
                link = baseUrl + '/mymachine/laser.php?mcno=comp02&mcname=comp02';
                break;
            default:
                console.warn('알 수 없는 장비 번호:', num);
                return;
        }
        
        if (num > 0 && link) {
            if (typeof popupCenter === 'function') {
                popupCenter(link, '장비 점검', 1200, 900);
            } else {
                window.open(link, '장비 점검', 'width=1200,height=900,scrollbars=yes,resizable=yes');
            }
        }
    };
    
    // 서버에 작업 기록
    $(document).ready(function() {
        if (typeof saveLogData === 'function') {
            saveLogData('장비점검 화면');
        }
    });
    
})();
</script>

</body>
</html>
