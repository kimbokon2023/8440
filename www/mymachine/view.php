<?php
/**
 * 장비 점검 상세 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

session_start();

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';

// 기타 변수 초기화
$title_message = '장비 점검';
$chkMobile = false; // load_header.php에서 재정의될 수 있음

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

include getDocumentRoot() . '/load_header.php';
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>

<?php
// 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        body, table th, table td, .form-control, span {
            font-size: 25px;
        }
        h4 {
            font-size: 40px;
        }
        .btn-sm {
            font-size: 26px;
        }
        .spantitle {
            font-size: 40px;
        }
    </style>';
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열로 장비점검리스트 불러옴
include "load_DB.php";

// 변수 초기화
$nowday = date("Y-m-d");
$checkdate = '';
$item = '';
$term = '';
$check1 = null;
$check2 = null;
$check3 = null;
$check4 = null;
$check5 = null;
$check6 = null;
$check7 = null;
$check8 = null;
$check9 = null;
$check10 = null;
$trouble = '';
$fixdata = '';
$writer = '';
$writer2 = '';
$itemstr = '';

// 데이터베이스에서 점검 데이터 조회
try {
    $sql = "SELECT * FROM mirae8440.mymclist WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $checkdate = $row["checkdate"];
        $item = $row["item"];
        $term = $row["term"];
        $check1 = $row["check1"];
        $check2 = $row["check2"];
        $check3 = $row["check3"];
        $check4 = $row["check4"];
        $check5 = $row["check5"];
        $check6 = $row["check6"];
        $check7 = $row["check7"];
        $check8 = $row["check8"];
        $check9 = $row["check9"];
        $check10 = $row["check10"];
        $trouble = $row["trouble"];
        $fixdata = $row["fixdata"];
        $writer = $row["writer"];
        $writer2 = $row["writer2"];
    }
} catch (PDOException $ex) {
    error_log("장비 점검 데이터 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}

// 작성자가 없을때는 작성자 생성
if ($writer == null) {
    $writer = $user_name;
}

// 질문 배열 초기화
$question = [];

// laser01 - 주간
if ($item == 'laser01' && $term == '주간') {
    $question = [
        "(칠러) ☞ 물통내부 냉각수 양은 적정선까지 채워져 있는가?",
        "(칠러) ☞ 냉각수는 오염되어 있는지 확인했는가?",
        "(칠러) ☞ 에어필터를 분리해 먼지를 청소했는가?",
        "(XY축 자바라) ☞ XY축 자바라부를 청소했는가?",
        "(재받이) ☞ 재받이부는 청소되어 있는가?",
        "(헤드부) ☞ 헤드부와 노출부 분진은 닦았고, 노즐팁은 깨끗한가?",
        "(집진기) ☞ 집진부 재받이는 청소되어 있는가?"
    ];
}

// laser01 - 1개월
if ($item == 'laser01' && $term == '1개월') {
    $question = [
        "(집진기) ☞ 집진부 필터의 오염상태는 확인했는가?",
        "(칠러) ☞ 내부 외부 누수는 없는지 확인 했는가?",
        "(칠러) ☞ 배출 라디에이터는 먼지청소를 했는가?",
        "(테이블체인저) ☞ 체인부에 구리스를 발랐는가?"
    ];
}

// laser01 - 2개월
if ($item == 'laser01' && $term == '2개월') {
    $question = [
        "(XYZ) ☞ (8시간/일 가동시 2개월에 1회) XYZ축에 구리스 주입했는가?",
        "(XYZ) ☞ XY축 자바라내 랙기어에 구리스를 발랐는가?",
        "(칠러) ☞ 물필터 오염상태를 확인하고 청소했는가?",
        "(컴퓨터) ☞ 가공조건, 중요자료를 안전한 컴퓨터에 복사했는가?"
    ];
}

// laser01 - 6개월
if ($item == 'laser01' && $term == '6개월') {
    $question = [
        "(작업테이블) ☞ 그리드(살대) 청소를 했는가?",
        "(칠러) ☞ 증류수 교체는 했는가?"
    ];
}

// vcut01 - 주간
if ($item == 'vcut01' && $term == '주간') {
    $question = [
        "(바이트) ☞ 바이트날 마모상태는 양호한가?",
        "(테이블) ☞ 작업테이블의 오염 및 파손은 없는가?",
        "(청결) ☞ 장비주변의 청결상태는 양호한가?",
        "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가?",
        "(조작판) ☞ 조작등은 정상작동하는가?",
        "(에어공급) ☞ 콤푸레샤로부터 에어공급은 잘 되는가?"
    ];
}

// vcut01 - 1개월
if ($item == 'vcut01' && $term == '1개월') {
    $question = [
        "(바이트) ☞ 바이트날 재고는 적정량을 보유하고 있는가?",
        "(에어공급) ☞ 콤푸레샤로부터 에어공급장치의 결함은 없는가?",
        "(작업페달) ☞ 장비 하단의 작업 패달작동은 양호한가?"
    ];
}

// vcut01 - 2개월
if ($item == 'vcut01' && $term == '2개월') {
    $question = [
        "(에어공급) ☞ 에어압력장치는 양호한가?",
        "(조작램프) ☞ 조작램프는 정상작동하는가?",
        "(프로그램) ☞ 자료 저장 읽기 등 프로그램의 작동은 양호한가?"
    ];
}

// vcut01 - 6개월
if ($item == 'vcut01' && $term == '6개월') {
    $question = [
        "(구리스주입구) ☞ 장치의 마찰부위의 구리스 주입구 상태는 양호한가?",
        "(전기장치) ☞ 전원공급장치의 전선상태는 양호한가?"
    ];
}

// bending01 - 주간
if ($item == 'bending01' && $term == '주간') {
    $question = [
        "(절곡날) ☞ 절곡날 마모상태는 양호한가?",
        "(청결) ☞ 장비주변의 청결상태는 양호한가?",
        "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가?",
        "(조작판) ☞ 조작등은 정상작동하는가?",
        "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가?",
        "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가?"
    ];
}

// bending01 - 1개월
if ($item == 'bending01' && $term == '1개월') {
    $question = [
        "(프로그램) ☞ 데이터 저장/읽기 등 프로그램은 정상작동하는가?",
        "(아답터) ☞ 아답터 조립상태는 양호한가?",
        "(작업페달) ☞ 장비 하단의 작업 패달작동은 양호한가?",
        "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가?",
        "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가?"
    ];
}

// bending01 - 2개월
if ($item == 'bending01' && $term == '2개월') {
    $question = [
        "(에어공급) ☞ 에어압력장치는 양호한가?",
        "(조작램프) ☞ 조작램프는 정상작동하는가?",
        "(프로그램) ☞ 자료 저장 읽기 등 프로그램의 작동은 양호한가?",
        "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가?",
        "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가?"
    ];
}

// bending01 - 6개월
if ($item == 'bending01' && $term == '6개월') {
    $question = [
        "(절곡날밸런스) ☞ 절곡날의 좌우밸런스는 잘 나오는가?",
        "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가?",
        "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가?"
    ];
}

// shearing01 - 주간
if ($item == 'shearing01' && $term == '주간') {
    $question = [
        "(절단날) ☞ 절단날 마모상태는 양호한가?",
        "(청결) ☞ 장비주변의 청결상태는 양호한가?",
        "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가?",
        "(조작판) ☞ 조작등은 정상작동하는가?"
    ];
}

// shearing01 - 1개월
if ($item == 'shearing01' && $term == '1개월') {
    $question = [
        "(수동프로그램) ☞ 위치조절 프로그램은 정상 작동하는가?",
        "(작업페달) ☞ 장비 하단의 작업 패달작동은 양호한가?"
    ];
}

// shearing01 - 2개월
if ($item == 'shearing01' && $term == '2개월') {
    $question = [
        "(조작램프) ☞ 조작램프는 정상 작동하는가?",
        "(백게이지) ☞ 백게이지 이동은 정상 작동하는가?"
    ];
}

// shearing01 - 6개월
if ($item == 'shearing01' && $term == '6개월') {
    $question = [
        "(절단밸런스) ☞ 절단날의 좌우밸런스는 잘 나오는가?"
    ];
}

// welder01~04 - 주간
if (($item == 'welder01' || $item == 'welder02' || $item == 'welder03' || $item == 'welder04') && $term == '주간') {
    $question = [
        "(전원) ☞ 전원은 정격전압에 연결되어 있는가?",
        "(전선) ☞ 케이블(전선)의 피복의 벗겨진 부분은 없는가?",
        "(전선) ☞ 케이블(전선)의 용접기와 접속부의 부착, 절연상태는 양호한가?",
        "(청결) ☞ 작업장 부근에 기름, 도료, 헝겊 등의 타기 쉬운 물건을 두지 않았는가?",
        "(청결) ☞ 통풍이나 환기는 충분히 이뤄지고 있는가?"
    ];
}

// welder01~04 - 1개월
if (($item == 'welder01' || $item == 'welder02' || $item == 'welder03' || $item == 'welder04') && $term == '1개월') {
    $question = [
        "(조작판) ☞ 조작등(램프류)은 정상 작동하는가?",
        "(조작스위치) ☞ 조작 스위치(버튼)류는 정상 작동하는가?"
    ];
}

// welder01~04 - 2개월
if (($item == 'welder01' || $item == 'welder02' || $item == 'welder03' || $item == 'welder04') && $term == '2개월') {
    $question = [
        "(장비안전) ☞ 용접기 본체는 접치가 되어있는가?",
        "(용품비치) ☞ 용접장소에 소화 준비물(소화기,물통 등) 비치되어 있는가?"
    ];
}

// welder01~04 - 6개월
if (($item == 'welder01' || $item == 'welder02' || $item == 'welder03' || $item == 'welder04') && $term == '6개월') {
    $question = [
        "(성능) ☞ 용접기 성능(용접상태, 소음 등)은 이상이 없는가?",
        "(관련부품훼손) ☞ 용접기 주요부품 및 부속품에 이상은 없는가?"
    ];
}

// motor01~02 - 주간
if (($item == 'motor01' || $item == 'motor02') && $term == '주간') {
    $question = [
        "(오일수준) ☞ 유압오일 레벨은 양호한가?",
        "(오일수준) ☞ 브레이크오일 레벨은 양호한가?",
        "(전기전장) ☞ 각종 경고장치는 작동은 양호한가?",
        "(전기전장) ☞ 배선 및 휴즈상태는 양호한가?",
        "(동작) ☞ 리프트 작동상태는 양호한가?",
        "(동작) ☞ 틸트 작동상태는 양호한가?",
        "(제어) ☞ 핸들 작동상태는 양호한가?",
        "(제동) ☞ 주차브레이크 작동상태는 양호한가?"
    ];
}

// motor01~02 - 1개월
if (($item == 'motor01' || $item == 'motor02') && $term == '1개월') {
    $question = [
        "(구리스주입) ☞ 마스트 및 베어링 구리스 주입은 양호한가?",
        "(구리스주입) ☞ 틸트핀 작동부 구리스 주입은 양호한가?",
        "(구리스주입) ☞ 각종 조인트 구리스 주입은 양호한가?",
        "(유압계통) ☞ 각종 실린더 누유는 없는가?",
        "(유압계통) ☞ 각종 펌프 누유는 없는가?",
        "(유압계통) ☞ 각종 파이프 및 호스 누유는 없는가?"
    ];
}

// motor01~02 - 2개월
if (($item == 'motor01' || $item == 'motor02') && $term == '2개월') {
    $question = [
        "(타이어) ☞ 타이어 마모량 상태는 양호한가?",
        "(타이어) ☞ 타이어 휠볼트 체결 상태는 양호한가?",
        "(타이어) ☞ 타이어 외관 상태는 양호한가?"
    ];
}

// motor01~02 - 6개월
if (($item == 'motor01' || $item == 'motor02') && $term == '6개월') {
    $question = [
        "(베터리) ☞ 증류수량은 적당한가?",
        "(베터리) ☞ 베터리 접지에는 이상 없는가?"
    ];
}

// tapdrill01 - 주간
if ($item == 'tapdrill01' && $term == '주간') {
    $question = [
        "(드릴날) ☞ 드릴날 마모상태는 양호한가?",
        "(청결) ☞ 장비주변의 청결상태는 양호한가?",
        "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가?",
        "(조작판) ☞ 조작등은 정상작동하는가?"
    ];
}

// tapdrill01 - 1개월
if ($item == 'tapdrill01' && $term == '1개월') {
    $question = [
        "(수동/자동레버) ☞ 레바 작동시 드릴회전은 정상 작동하는가?",
        "(높이조절작업대) ☞ 높이 조절작업대는 작동은 양호한가?"
    ];
}

// tapdrill01 - 2개월
if ($item == 'tapdrill01' && $term == '2개월') {
    $question = [
        "(조작램프) ☞ 조작램프는 정상 작동하는가?",
        "(진동/소음) ☞ 작동시 모터의 이상소음 및 진동은 정상인가?"
    ];
}

// tapdrill01 - 6개월
if ($item == 'tapdrill01' && $term == '6개월') {
    $question = [
        "(전선) ☞ 케이블(전선)의 피복의 벗겨진 부분은 없는가?",
        "(모터) ☞ 회전모터의 회전량 및 출력은 정상인가?"
    ];
}

// comp01,02 - 주간
if (($item == 'comp01' || $item == 'comp02') && $term == '주간') {
    $question = [
        "(오일수준) ☞ 피스톤 유압오일 양호한가(폭발위험)?",
        "(수분) ☞ 탱크 하부 수분은 양호한가?",
        "(밸트장력) ☞ 느슨함이 없이 작동은 양호한가?"
    ];
}

// comp01,02 - 1개월
if (($item == 'comp01' || $item == 'comp02') && $term == '1개월') {
    $question = [
        "(위험요소) ☞ 폭발이 가능한 물질이나 환경으로 안전한가??",
        "(정리정돈) ☞ 장비주변에 정리정돈은 양호한가?"
    ];
}

// comp01,02 - 2개월
if (($item == 'comp01' || $item == 'comp02') && $term == '2개월') {
    $question = [
        "(위험요소) ☞ 폭발이 가능한 물질이나 환경으로 안전한가??",
        "(정리정돈) ☞ 장비주변에 정리정돈은 양호한가?"
    ];
}

// comp01,02 - 6개월
if (($item == 'comp01' || $item == 'comp02') && $term == '6개월') {
    $question = [
        "(위험요소) ☞ 폭발이 가능한 물질이나 환경으로 안전한가??",
        "(정리정돈) ☞ 장비주변에 정리정돈은 양호한가?"
    ];
}

$questionNum = count($question);

// $mcno_arr에서 $item과 일치하는 항목 찾기
$index = array_search($item, $mcno_arr);
if ($index !== false) {
    $itemstr = $mcname_arr[$index];
}
?>

<form id="board_form" name="board_form" method="post">
    <input type="hidden" name="check1" id="check1" value="<?= htmlspecialchars($check1, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check2" id="check2" value="<?= htmlspecialchars($check2, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check3" id="check3" value="<?= htmlspecialchars($check3, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check4" id="check4" value="<?= htmlspecialchars($check4, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check5" id="check5" value="<?= htmlspecialchars($check5, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check6" id="check6" value="<?= htmlspecialchars($check6, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check7" id="check7" value="<?= htmlspecialchars($check7, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check8" id="check8" value="<?= htmlspecialchars($check8, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check9" id="check9" value="<?= htmlspecialchars($check9, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="check10" id="check10" value="<?= htmlspecialchars($check10, ENT_QUOTES, 'UTF-8') ?>">
    
    <?php if ($chkMobile) { ?>
        <div class="container-fluid mt-2 mb-2">
    <?php } else { ?>
        <div class="container mt-2 mb-2">
    <?php } ?>
    
        <div class="card mt-3">
            <div class="card-body">
                <div class="row gx-1 gx-lg-1 align-items-center">
                    <div class="fs-4 mb-1" id="leftchar">
                        <label class="form-check-label text-primary" for="leftchar">
                            &nbsp;&nbsp; '<?= htmlspecialchars($itemstr, ENT_QUOTES, 'UTF-8') ?>' &nbsp;
                        </label>
                        담당 (정) <?= htmlspecialchars($writer, ENT_QUOTES, 'UTF-8') ?>, (부) <?= htmlspecialchars($writer2, ENT_QUOTES, 'UTF-8') ?> &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" id="closeBtn" class="btn btn-dark btn-sm">
                            <ion-icon name="close-outline"></ion-icon> 창닫기
                        </button>
                        <?php
                        if ($user_name == '김보곤' || $user_name == '이경묵') {
                            echo '<button type="button" id="passBtn" class="btn btn-primary btn-sm"><ion-icon name="color-wand-outline"></ion-icon> pass </button>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <section class="h-100 gradient-custom">
                    <div class="container-fluid py-5 h-100">
                        <div class="row d-flex justify-content-center align-items-center h-100">
                            <div class="col-xl-12">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-header px-0 py-0 text-center">
                                        <h3 class="text-muted mb-3">
                                            <?= htmlspecialchars($term, ENT_QUOTES, 'UTF-8') ?>
                                            <span style="color: #a8729a;">점검</span>!
                                        </h3>
                                    </div>
                                    
                                    <!-- 체크리스트 -->
                                    <?php
                                    for ($i = 0; $i < count($question); $i++) {
                                        $checktmp = 'check' . ($i + 1);
                                    ?>
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <p class="lead fw-normal mb-0" style="color: #a8729a;">
                                                <?= htmlspecialchars($question[$i], ENT_QUOTES, 'UTF-8') ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <span id="ckname<?= $i + 1 ?>" style="color:gray;">
                                                    <?php if ($$checktmp != null) {
                                                        echo htmlspecialchars($$checktmp, ENT_QUOTES, 'UTF-8') . ", (점검자) " . htmlspecialchars($writer, ENT_QUOTES, 'UTF-8');
                                                    } else { ?>
                                                </span>
                                                <button type="button" id="ckbtn<?= $i + 1 ?>" class="btn btn-secondary btn-sm check-btn" onclick="checklist('<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>','<?= $i + 1 ?>');">점검완료</button>
                                                <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    
                                    <?php
                                    // 절곡기인 경우는 이미지를 출력
                                    if ($item == 'bending01') {
                                        echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/a105.jpg' alt='Bending'>";
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/a101_84.jpg' alt='Bending'>";
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/a101_78.jpg' alt='Bending'>";
                                        echo '</div>';
                                        echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/a103.jpg' alt='Bending'>";
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/a115.jpg' alt='Bending'>";
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/d605_80.jpg' alt='Bending'>";
                                        echo '</div>';
                                        echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/d605_86.jpg' alt='Bending'>";
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/d612.jpg' alt='Bending'>";
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/d602.jpg' alt='Bending'>";
                                        echo '</div>';
                                        echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                        echo "<img style='width:25%;height:auto' src='{$base_url}/img/bending/d603.jpg' alt='Bending'>";
                                        echo '</div>';
                                    }
                                    ?>
                                    
                                    <!-- 특이사항 기록 -->
                                    <div class="card-header px-0 py-0 text-center">
                                        <h3 class="text-muted mb-3">'점검 후 특이사항' 기록</h3>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-center align-items-center mb-4">
                                            <textarea class="form-control" style="width:500px;" rows="3" id="trouble" name="trouble" placeholder="특이사항 있을시 기록"><?= htmlspecialchars($trouble, ENT_QUOTES, 'UTF-8') ?></textarea>
                                            &nbsp;
                                            <p class="fw-normal mb-0" style="color: #a8729a;">
                                                <button type="button" class="btn btn-dark btn-sm" onclick="write_memo('<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>');">기록 저장</button>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- footer -->
                                    <div class="card-footer border-0 px-5 py-4" style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                                        <h2 class="d-flex align-items-center justify-content-center text-white mb-0">
                                            안전을 최우선으로 생각하는 미래기업
                                        </h2>
                                        <h3 class="d-flex align-items-center justify-content-center text-center text-white mb-0">
                                            고객만족 품질경영
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</form>

<!-- ajax 전송으로 DB 수정 -->
<?php include "../formload.php"; ?>

<div id="dummy"></div>

</body>
</html>

<script type="text/javascript">
(function() {
    'use strict';
    
    var baseUrl = <?php echo json_encode($base_url, JSON_UNESCAPED_UNICODE); ?>;
    var writer = <?php echo json_encode($writer, JSON_UNESCAPED_UNICODE); ?>;
    var writer2 = <?php echo json_encode($writer2, JSON_UNESCAPED_UNICODE); ?>;
    var userName = <?php echo json_encode($user_name, JSON_UNESCAPED_UNICODE); ?>;
    var questionNum = <?php echo json_encode($questionNum, JSON_UNESCAPED_UNICODE); ?>;
    
    $(document).ready(function() {
        $("#closeModalBtn").click(function() {
            $('#myModal').modal('hide');
        });
        
        $("#closeBtn").click(function() {
            if (window.opener && !window.opener.closed) {
                window.opener.location.reload();
            }
            window.close();
        });
        
        $("#passBtn").click(function() {
            document.querySelectorAll('.check-btn').forEach(function(button) {
                button.click();
            });
        });
        
        $("#doBtn").click(function() {
            // 일시로 만든 함수 각 장비의 체크리스트 자료 db 생성을 위해서
        });
        
        // 브라우저 강제로 닫을때 이벤트
        $(window).bind("beforeunload", function(e) {
            if (window.opener && !window.opener.closed) {
                window.opener.location.reload();
            }
        });
    });
    
    /**
     * 점검후 특이사항 기록하기
     */
    window.write_memo = function(num) {
        $("#table").val('mymclist');
        $("#command").val('update');
        $("#field").val('trouble');
        $("#strtmp").val($("#trouble").val());
        $("#recnum").val(num);
        
        $.ajax({
            url: baseUrl + "/proDB.php",
            type: "post",
            data: $("#Form1").serialize(),
            dataType: "json",
            success: function(data) {
                console.log(data);
            },
            error: function(jqxhr, status, error) {
                console.error(status, error);
            }
        });
        
        $('#myModal').modal('show');
    };
    
    /**
     * 체크리스트 점검 완료 처리
     */
    window.checklist = function(num, whichone) {
        console.log('writer:', writer);
        console.log('userName:', userName);
        console.log('questionNum:', questionNum);
        
        if (writer == userName || writer2 == userName || userName === '김보곤' || userName === '이경묵') {
            // DB 수정
            $("#table").val('mymclist');
            $("#command").val('update');
            $("#field").val('check' + whichone);
            $("#strtmp").val(getToday());
            $("#recnum").val(num);
            $("#arr").val('free');
            
            // check값 form의 변수에 넣어주기
            $('#check' + whichone).val(getToday());
            
            $.ajax({
                url: baseUrl + "/proDB.php",
                type: "post",
                data: $("#Form1").serialize(),
                dataType: "json",
                success: function(data) {
                    console.log(data);
                },
                error: function(jqxhr, status, error) {
                    console.error(status, error);
                }
            });
            
            // 각 주간점검/1개월 점검등 문항을 전부 check했을 경우 완료 done 처리하기
            var sum = 0;
            for (var i = 1; i <= 10; i++) {
                if ($('#check' + i).val() != '') {
                    sum += 1;
                }
            }
            
            console.log('질문수: ' + questionNum);
            console.log('답변수: ' + sum);
            
            if (questionNum == sum) {
                // 체크문항과 같으면 DB 완료로 수정하기
                $("#table").val('mymclist');
                $("#command").val('update');
                $("#field").val('done');
                $("#strtmp").val('1');
                $("#recnum").val(num);
                $("#arr").val('free');
                
                $.ajax({
                    url: baseUrl + "/proDB.php",
                    type: "post",
                    data: $("#Form1").serialize(),
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                    },
                    error: function(jqxhr, status, error) {
                        console.error(status, error);
                    }
                });
            }
            
            // 화면 변경하기
            $("#ckname" + whichone).html(getToday() + ' ' + '(작성자) ' + userName);
            // 버튼삭제
            $("#ckbtn" + whichone).remove();
        } else {
            var tmp = '점검자와 이름이 다릅니다. 확인바랍니다.';
            $('#alertmsg').html(tmp);
            $('#myModal').modal('show');
        }
    };
    
})();
</script>
