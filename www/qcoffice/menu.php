<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;

// 요청 변수 초기화
$mcno = $_REQUEST["mcname"] ?? '';

// 첫 화면 표시 문구
$title_message = '사무실 정리정돈 캠페인';
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?= $title_message ?></title>

</head>

<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php require_once(includePath('myheader.php')); ?>

<?php
// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    $_SESSION["url"] = 'https://8440.co.kr/qcoffice/laser.php?mcno=' . $mcno;
    sleep(1);
    header("Location:https://8440.co.kr/login/login_form.php");
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 장비점검리스트 불러옴
include "load_DB.php";

// 배열로 미점검 장비점검리스트 불러옴
// include "load_nocheck.php";
?>

<div class="container">
    <div class="card mt-2 mb-2">
        <div class="card-body">
            <div class="d-flex mt-3 mb-1 justify-content-center">
                <img src="../img/qc-bg.jpg" style="width:100%;">
            </div>
            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-outline-info btn-sm me-2" onclick="openHelpModal()">
                    <i class="bi bi-question-circle"></i> 도움말
                </button>
                <a href="mc_list.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-gear-fill"></i> 정비 항목 관리
                </a>
            </div>
            <h5 class="fw-bolder mb-4">사무실 정비</h5>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                $todate = date("Y-m-d");   // 현재일자 변수지정
                $sql = "select * from mirae8440.myarea order by num";
                $nowday = date("Y-m-d");   // 현재일자 변수지정

                // 배열 초기화
                $counter = 0;
                $num_arr = array();
                $mcno_arr = array();
                $mcname_arr = array();
                $mcspec_arr = array();
                $mcmaker_arr = array();
                $mcmain_arr = array();
                $mcsub_arr = array();
                $qrcode_arr = array();

                try {
                    $stmh = $pdo->query($sql);  // 검색조건에 맞는글 stmh
                    $rowNum = $stmh->rowCount();

                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $num = $row["num"] ?? '';
                        $mcno = $row["mcno"] ?? '';
                        $mcname = $row["mcname"] ?? '';
                        $mcspec = $row["mcspec"] ?? '';
                        $mcmaker = $row["mcmaker"] ?? '';
                        $mcmain = $row["mcmain"] ?? '';
                        $mcsub = $row["mcsub"] ?? '';
                        $qrcode = $row["qrcode"] ?? '';

                        $num_arr[$counter] = $row["num"] ?? '';
                        $mcno_arr[$counter] = $row["mcno"] ?? '';
                        $mcname_arr[$counter] = $row["mcname"] ?? '';
                        $mcspec_arr[$counter] = $row["mcspec"] ?? '';
                        $mcmaker_arr[$counter] = $row["mcmaker"] ?? '';
                        $mcmain_arr[$counter] = $row["mcmain"] ?? '';
                        $mcsub_arr[$counter] = $row["mcsub"] ?? '';
                        $qrcode_tmp = 'https://8440.co.kr/img/' . $qrcode . '.png';
                        $qrcode_arr[$counter] = 'https://8440.co.kr/img/' . $qrcode . '.png';

                        $counter++;
                ?>
                        <div class="col mb-2">
                            <div class="card h-100" onclick="choiceMC(<?= $num ?>,'<?= $mcmain ?>','<?= $mcsub ?>','<?= $mcno ?>');">
                                <!-- Product details-->
                                <div class="card-body p-2">
                                    <div class="text-center">
                                        <h5 class="fw-bolder"><?= $row["mcname"] ?? '' ?></h5>
                                    </div>
                                    <div class="text-center">
                                        <span class="fw-bolder"><?= $row["mcspec"] ?? '' ?></span>
                                    </div>
                                    <div class="text-center">
                                        <span class="fw-bolder">점검(정) <?= $row["mcmain"] ?? '' ?></span>
                                    </div>
                                    <div class="text-center">
                                        <span class="fw-bolder">점검(부) <?= $row["mcsub"] ?? '' ?></span>
                                    </div>
                                    <div class="text-center">
                                        <span class="fw-bolder">
                                            <img src="<?= $qrcode_tmp ?>" style="width:100%;height:100%;">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } catch (PDOException $Exception) {
                    print "오류: " . $Exception->getMessage();
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- ajax 전송으로 DB 수정 -->
<?php include "../formload.php"; ?>

<!-- Footer-->
<?php include "../shop/footer.php" ?>

<!-- 도움말 모달 -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white py-3">
                <h5 class="modal-title fs-5" id="helpModalLabel">
                    <i class="bi bi-info-circle"></i> 사무실 정비 메뉴 사용법
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto; font-size: 1.15rem;">
                <div class="p-2">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-check2-square"></i> 정비 구역 선택 및 점검</h6>
                    <p class="text-muted mb-4">
                        화면에 표시된 <strong>정비 구역 카드</strong>를 클릭하면 해당 구역의 <strong>점검표(체크리스트)</strong> 화면으로 이동합니다.<br>
                        주간, 월간 등 정해진 주기에 맞춰 점검을 수행하세요.
                    </p>

                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-gear"></i> 정비 항목 관리</h6>
                    <p class="text-muted mb-4">
                        우측 상단의 <strong>'정비 항목 관리'</strong> 버튼을 클릭하여 새로운 정비 구역을 등록하거나<br>
                        기존 구역의 정보를 수정/삭제할 수 있습니다. (관리자 권한 필요)
                    </p>

                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-qr-code"></i> QR 코드 활용</h6>
                    <p class="text-muted mb-4">
                        구역에 부착된 <strong>QR 코드</strong>를 스마트폰으로 스캔하면<br>
                        해당 구역의 점검 화면으로 즉시 연결됩니다.
                    </p>
                    
                    <h6 class="fw-bold text-info mb-2"><i class="bi bi-person-badge"></i> 담당자 확인</h6>
                    <p class="text-muted mb-0">
                        각 구역 카드에는 <strong>(정), (부) 담당자</strong>가 표시되어 있습니다.<br>
                        해당 구역의 관리 책임을 맡은 담당자를 확인할 수 있습니다.
                    </p>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<script>
function openHelpModal() {
    var myModal = new bootstrap.Modal(document.getElementById('helpModal'), {
        keyboard: true
    });
    myModal.show();
}
</script>

<script>
    function choiceMC(num, mcmain, mcsub, mcno) {
        var link;
        link = 'https://8440.co.kr/qcoffice/laser.php?mcno=' + mcno;
        popupCenter(link, '사무실 정비', 1200, 900);
    }

    // 서버에 작업 기록
    $(document).ready(function() {
        saveLogData('사무실 정비'); // 다른 페이지에 맞는 menuName을 전달
    });
</script>
</body>
</html>