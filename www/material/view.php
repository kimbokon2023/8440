<?php
/**
 * 현장 상세 보기 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    } elseif (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return $default;
}

// 날짜 포맷팅 함수
function formatDate($date) {
    if (empty($date) || $date == "0000-00-00" || $date == "1970-01-01") {
        return "";
    }
    return date("Y-m-d", strtotime($date));
}

// 파일 디렉토리
$file_dir = '../uploads/';

// 기본 변수 초기화
$num = getRequestValue("num", '');
$search = getRequestValue("search", '');
$find = getRequestValue("find", '');
$page = getRequestValue("page", 1);
$process = getRequestValue("process", '');
$year = getRequestValue("year", '');
$yearcheckbox = getRequestValue("yearcheckbox", '');

// 체크박스 변수들
$check = getRequestValue("check", '0');
$output_check = getRequestValue("output_check", '0');
$team_check = getRequestValue("team_check", '0');
$measure_check = getRequestValue("measure_check", '0');
$plan_output_check = getRequestValue("plan_output_check", '0');

// 정렬 관련 변수
$cursort = getRequestValue("cursort", '0');
$sortof = getRequestValue("sortof", '0');
$stable = getRequestValue("stable", '0');

// 작업 관련 변수 초기화
$checkstep = $workplacename = $address = '';
$firstord = $firstordman = $firstordmantel = '';
$secondord = $secondordman = $secondordmantel = '';
$chargedman = $chargedmantel = '';
$orderday = $measureday = $drawday = $deadline = '';
$workday = $worker = $endworkday = '';
$material1 = $material2 = $material3 = $material4 = $material5 = $material6 = '';
$widejamb = $normaljamb = $smalljamb = $memo = '';
$regist_day = $update_day = '';
$delivery = $delicar = $delicompany = $delipay = $delimethod = '';
$demand = $startday = $testday = $hpi = '';
$content = '';

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 작업 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.work WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // 기본 정보
        $checkstep = $row["checkstep"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $address = $row["address"] ?? '';
        
        // 발주 정보
        $firstord = $row["firstord"] ?? '';
        $firstordman = $row["firstordman"] ?? '';
        $firstordmantel = $row["firstordmantel"] ?? '';
        $secondord = $row["secondord"] ?? '';
        $secondordman = $row["secondordman"] ?? '';
        $secondordmantel = $row["secondordmantel"] ?? '';
        $chargedman = $row["chargedman"] ?? '';
        $chargedmantel = $row["chargedmantel"] ?? '';
        
        // 작업자
        $worker = $row["worker"] ?? '';
        
        // 날짜 정보 (포맷팅 적용)
        $orderday = formatDate($row["orderday"] ?? '');
        $measureday = formatDate($row["measureday"] ?? '');
        $drawday = formatDate($row["drawday"] ?? '');
        $deadline = formatDate($row["deadline"] ?? '');
        $workday = formatDate($row["workday"] ?? '');
        $endworkday = formatDate($row["endworkday"] ?? '');
        $demand = formatDate($row["demand"] ?? '');
        $startday = formatDate($row["startday"] ?? '');
        $testday = formatDate($row["testday"] ?? '');
        
        // 자재 정보
        $material1 = $row["material1"] ?? '';
        $material2 = $row["material2"] ?? '';
        $material3 = $row["material3"] ?? '';
        $material4 = $row["material4"] ?? '';
        $material5 = $row["material5"] ?? '';
        $material6 = $row["material6"] ?? '';
        
        // 수량 정보
        $widejamb = $row["widejamb"] ?? '';
        $normaljamb = $row["normaljamb"] ?? '';
        $smalljamb = $row["smalljamb"] ?? '';
        
        // 기타
        $memo = $row["memo"] ?? '';
        $regist_day = $row["regist_day"] ?? '';
        $update_day = $row["update_day"] ?? '';
        $delivery = $row["delivery"] ?? '';
        $delicar = $row["delicar"] ?? '';
        $delicompany = $row["delicompany"] ?? '';
        $delipay = $row["delipay"] ?? '';
        $delimethod = $row["delimethod"] ?? '';
        $hpi = $row["hpi"] ?? '';
    }
    
} catch (PDOException $ex) {
    error_log("작업 정보 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "오류: 작업 정보를 불러오는 중 문제가 발생했습니다.";
}

// VOC(협의사항) 조회
try {
    $sql = "SELECT * FROM mirae8440.voc WHERE parent = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $content = $row["content"] ?? '';
    }
    
} catch (PDOException $ex) {
    error_log("VOC 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "오류: 협의사항을 불러오는 중 문제가 발생했습니다.";
}

// 안전한 URL 파라미터 생성
$safe_params = http_build_query([
    'mode' => 'search',
    'search' => $search,
    'find' => $find,
    'process' => $process,
    'yearcheckbox' => $yearcheckbox,
    'year' => $year,
    'check' => $check,
    'output_check' => $output_check,
    'plan_output_check' => $plan_output_check,
    'team_check' => $team_check,
    'measure_check' => $measure_check,
    'page' => $page,
    'cursort' => $cursort,
    'sortof' => $sortof,
    'stable' => $stable,
    'scale' => 10000
], '', '&', PHP_QUERY_RFC3986);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 쟘공사 관리시스템</title>
    
    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" 
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="../css/partner.css" type="text/css" />
</head>
<body>
    <div id="top-menu">
        <?php if (!isset($_SESSION["userid"])): ?>
            <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
        <?php else: ?>
            <div class="row">
                <div class="col">
                    <h1 class="display-5 font-center text-left"><br>
                        <?= htmlspecialchars($_SESSION["nick"] ?? '', ENT_QUOTES, 'UTF-8') ?> | 
                        <a href="../login/logout.php">로그아웃</a> | 
                        <a href="../member/updateForm.php?id=<?= htmlspecialchars($_SESSION["userid"] ?? '', ENT_QUOTES, 'UTF-8') ?>">정보수정</a>
                    </h1>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <br>
    <div class="row">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <h1 class="display-2 text-left">
            <input type="button" class="btn btn-secondary btn-lg" value="목록으로 돌아가기" onclick="move_url('index.php')">
        </h1>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <h1 class="display-2 text-left">
            <input type="button" class="btn btn-primary btn-lg" value="실측완료 전송버튼" 
                   onclick="input_measureday('process_DB.php?num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>')">
        </h1>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <h1 class="display-2 text-left">
            <input type="button" class="btn btn-danger btn-lg" value="협의사항 기록&전달" 
                   onclick="input_message('voc.php?num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>')">
        </h1>
    </div>
    
    <br><br>
    
    <form id="board_form" name="board_form" method="post" action="view.php?<?= $safe_params ?>">
        <div class="container">
            <div class="row">
                <h1 class="display-4 font-center text-center">미래기업 쟘(Jamb) 현장</h1>
            </div>
            
            <br>
            
            <div class="row">
                <h1 class="display-5 font-center text-left">
                    현장명: <?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>
                    <br><br>
                    
                    <span style="color:blue;font-weight:bold;">출고예정일(김진억이사 협의):</span>
                    <span style="color:red;font-weight:bold;"><?= htmlspecialchars($endworkday, ENT_QUOTES, 'UTF-8') ?></span>
                    <br><br>
                    
                    막판 HPI 형태: 
                    <span style="color:brown;font-weight:bold;"><?= htmlspecialchars($hpi, ENT_QUOTES, 'UTF-8') ?></span>
                    <br><br>
                    
                    재질 1: 
                    <input disabled type="text" value="<?= htmlspecialchars($material1 . ' ' . $material2, ENT_QUOTES, 'UTF-8') ?>" size="25">
                    <br>
                    
                    <?php
                    $sum_mat1 = trim($material3 . ' ' . $material4);
                    if (!empty($sum_mat1)) {
                        echo '재질 2: <input disabled type="text" value="' . htmlspecialchars($sum_mat1, ENT_QUOTES, 'UTF-8') . '" size="25"><br>';
                    }
                    
                    $sum_mat2 = trim($material5 . ' ' . $material6);
                    if (!empty($sum_mat2)) {
                        echo '재질 3: <input disabled type="text" value="' . htmlspecialchars($sum_mat2, ENT_QUOTES, 'UTF-8') . '" size="25"><br>';
                    }
                    ?>
                    <br>
                    
                    <div style="color:red; font-weight:bold;">< 총 설치 수량></div>
                    <br>
                    
                    <?php
                    if (!empty($widejamb) && $widejamb > 0) {
                        echo '<div class="alert alert-info" role="alert">막판: ' . htmlspecialchars($widejamb, ENT_QUOTES, 'UTF-8') . '</div>';
                    }
                    if (!empty($normaljamb) && $normaljamb > 0) {
                        echo '<div class="alert alert-warning" role="alert">막판(無): ' . htmlspecialchars($normaljamb, ENT_QUOTES, 'UTF-8') . '</div>';
                    }
                    if (!empty($smalljamb) && $smalljamb > 0) {
                        echo '<div class="alert alert-danger" role="alert">쪽쟘: ' . htmlspecialchars($smalljamb, ENT_QUOTES, 'UTF-8') . '</div>';
                    }
                    ?>
                    <br>
                    
                    현장주소: <?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>
                    <br><br>
                    
                    원청: <?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?>
                    <br>
                    원청담당(PM): <?= htmlspecialchars($firstordman, ENT_QUOTES, 'UTF-8') ?>
                    <br>
                    연락처: <a href="tel:<?= htmlspecialchars($firstordmantel, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($firstordmantel, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <br><br>
                    
                    발주처: <?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?>
                    <br>
                    발주처담당: <?= htmlspecialchars($secondordman, ENT_QUOTES, 'UTF-8') ?>
                    <br>
                    연락처: <a href="tel:<?= htmlspecialchars($secondordmantel, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($secondordmantel, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <br><br>
                    
                    현장소장: <?= htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') ?>
                    <br>
                    현장소장연락처: <a href="tel:<?= htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <br><br>
                    
                    발주접수일: 
                    <input disabled type="text" name="orderday" id="orderday" 
                           value="<?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?>" 
                           size="10" placeholder="발주접수일">
                    <br>
                    
                    실측일: 
                    <input disabled value="<?= htmlspecialchars($measureday, ENT_QUOTES, 'UTF-8') ?>" 
                           size="10" placeholder="실측일">
                    <br>
                    
                    도면설계완료일: 
                    <input disabled value="<?= htmlspecialchars($drawday, ENT_QUOTES, 'UTF-8') ?>" 
                           size="10" placeholder="도면설계일">
                    <br><br>
                    
                    제품출고일: <?= htmlspecialchars($workday, ENT_QUOTES, 'UTF-8') ?>
                    <br><br>
                    
                    착공일: <?= htmlspecialchars($startday, ENT_QUOTES, 'UTF-8') ?>
                    <br>
                    
                    검사일: 
                    <input disabled value="<?= htmlspecialchars($testday, ENT_QUOTES, 'UTF-8') ?>" 
                           size="10" style="color:red;">
                    <br><br>
                    
                    추가 메모(기타 사항):
                    <br>
                    <textarea disabled rows="3" cols="30" name="memo" 
                              placeholder="추가적으로 기록할 내역" 
                              style="color:blue;"><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></textarea>
                    <br><br>
                    
                    협의사항 기록:
                    <br>
                    <textarea disabled rows="10" cols="30" name="content" 
                              placeholder="협의사항 기록 내역" 
                              style="color:brown;"><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
                    <br>
                </h1>
            </div>
        </div>
    </form>
</body>
</html>

<script type="text/javascript">
(function() {
    'use strict';
    
    var imgObj = new Image();
    
    /**
     * 이미지 윈도우 표시
     */
    window.showImgWin = function(imgName) {
        imgObj.src = imgName;
        setTimeout(function() {
            createImgWin(imgObj);
        }, 100);
    };
    
    function createImgWin(imgObj) {
        if (!imgObj.complete) {
            setTimeout(function() {
                createImgWin(imgObj);
            }, 100);
            return;
        }
        window.open("", "imageWin", "width=" + imgObj.width + ",height=" + imgObj.height);
    }
    
    /**
     * 숫자 포맷팅
     */
    window.inputNumberFormat = function(obj) {
        if (obj) {
            obj.value = comma(uncomma(obj.value));
        }
    };
    
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
    
    /**
     * 날짜 입력 마스크
     */
    window.date_mask = function(formd, textid) {
        var form = document[formd];
        if (!form) return;
        
        var text = form[textid];
        if (!text) return;
        
        var textlength = text.value.length;
        
        if (textlength == 4) {
            text.value = text.value + "-";
        } else if (textlength == 7) {
            text.value = text.value + "-";
        } else if (textlength > 9) {
            checkdate(text);
        }
    };
    
    /**
     * 날짜 유효성 검사
     */
    function checkdate(input) {
        var validformat = /^\d{4}-\d{2}-\d{2}$/;
        var returnval = false;
        
        if (!validformat.test(input.value)) {
            alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            input.select();
            return false;
        }
        
        var parts = input.value.split("-");
        var yearfield = parts[0];
        var monthfield = parts[1];
        var dayfield = parts[2];
        var dayobj = new Date(yearfield, monthfield - 1, dayfield);
        
        if ((dayobj.getMonth() + 1 != monthfield) || 
            (dayobj.getDate() != dayfield) || 
            (dayobj.getFullYear() != yearfield)) {
            alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            input.select();
            return false;
        }
        
        return true;
    }
    
    /**
     * 텍스트 입력 (10% 증가)
     */
    window.input_Text = function() {
        var testElem = document.getElementById("test");
        if (testElem) {
            testElem.value = comma(Math.floor(uncomma(testElem.value) * 1.1));
        }
    };
    
    /**
     * AS 이력 복사
     */
    window.copy_below = function() {
        var elements = {
            ashistory: document.getElementById("ashistory"),
            asday: document.getElementById("asday"),
            aswriter: document.getElementById("aswriter"),
            asorderman: document.getElementById("asorderman"),
            asordermantel: document.getElementById("asordermantel"),
            asfee: document.getElementById("asfee"),
            asfee_estimate: document.getElementById("asfee_estimate"),
            aslist: document.getElementById("aslist"),
            as_refer: document.getElementById("as_refer"),
            asproday: document.getElementById("asproday"),
            setdate: document.getElementById("setdate"),
            asman: document.getElementById("asman"),
            asendday: document.getElementById("asendday"),
            asresult: document.getElementById("asresult")
        };
        
        if (!elements.ashistory) return;
        
        var park = document.getElementsByName("asfee");
        var feeType = (park[1] && park[1].checked) ? " 유상 " : " 무상 ";
        
        var history = elements.ashistory.value;
        history += (elements.asday ? elements.asday.value + " " : "");
        history += (elements.aswriter ? elements.aswriter.value + " " : "");
        history += (elements.asorderman ? elements.asorderman.value + " " : "");
        history += (elements.asordermantel ? elements.asordermantel.value + " " : "");
        history += feeType + (elements.asfee ? elements.asfee.value + " " : "");
        history += (elements.asfee_estimate ? elements.asfee_estimate.value + " " : "");
        history += (elements.aslist ? elements.aslist.value + " " : "");
        history += (elements.as_refer ? elements.as_refer.value + " " : "");
        history += (elements.asproday ? elements.asproday.value + " " : "");
        history += (elements.setdate ? elements.setdate.value + " " : "");
        history += (elements.asman ? elements.asman.value + " " : "");
        history += (elements.asendday ? elements.asendday.value + " " : "");
        history += (elements.asresult ? elements.asresult.value + "        " : "");
        
        elements.ashistory.value = history;
    };
    
    /**
     * 실측일 전송
     */
    window.input_measureday = function(href) {
        if (confirm("실측일을 전송합니다.\n\n정말 본사 전산에 입력 하시겠습니까?")) {
            document.location.href = href;
        }
    };
    
    /**
     * 삭제 처리
     */
    window.del = function(href) {
        var levelElem = document.getElementById('session_level');
        var level = levelElem ? Number(levelElem.value) : 999;
        
        if (level > 2) {
            alert("삭제하려면 관리자에게 문의해 주세요");
        } else {
            if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                document.location.href = href;
            }
        }
    };
    
    /**
     * 메시지 입력 페이지 이동
     */
    window.input_message = function(href) {
        document.location.href = href;
    };
    
    /**
     * URL 이동
     */
    window.move_url = function(href) {
        document.location.href = href;
    };
    
    /**
     * 출고 목록 표시
     */
    window.displayoutputlist = function() {
        if (typeof $ !== 'undefined') {
            $("#displayoutput").show();
            $("#displayoutput").load("./outputlist.php");
        } else {
            alert("jQuery가 로드되지 않았습니다.");
        }
    };
    
})();
</script>
