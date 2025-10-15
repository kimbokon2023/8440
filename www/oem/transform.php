<?php
/**
 * 출고증 자료 입력화면
 * 로컬 및 서버 환경 모두 지원
 */

if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location: {$base_url}/login/logout.php");
    exit;
}

/**
 * 날짜 변환 함수
 */
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}

// 요청 변수 초기화 (?? '' 형태)
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';
$cursort = $_REQUEST["cursort"] ?? $_POST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? $_POST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? $_POST["stable"] ?? '0';

// 출고일 생성
$outputdate = date("Y-m-d", time());
if ($outputdate != "") {
    $week = ["(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)"];
    $outputdate = $outputdate . $week[date('w', strtotime($outputdate))];
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 데이터베이스에서 데이터 조회
$workplacename = '';
$address = '';
$worker = '';
$secondord = '';
$firstord = '';
$startday = '';
$chargedman = '';
$chargedmantel = '';
$memo = '';
$deadline = '';
$delivery = '';
$delipay = '';
$delitext = '';
$type1 = '';
$inseung1 = '';
$car_insize1 = '';
$su = 0;
$lc_su = 0;
$etc_su = 0;
$air_su = 0;
$item_file_0 = '';
$item_file_1 = '';
$copied_file_0 = '';
$copied_file_1 = '';

// 항목 데이터 초기화 (first ~ tenth)
$first_item1 = '';
$first_item2 = '';
$first_item3 = '';
$first_item4 = '';
$second_item1 = '';
$second_item2 = '';
$second_item3 = '';
$second_item4 = '';
$third_item1 = '';
$third_item2 = '';
$third_item3 = '';
$third_item4 = '';
$forth_item1 = '';
$forth_item2 = '';
$forth_item3 = '';
$forth_item4 = '';
$fifth_item1 = '';
$fifth_item2 = '';
$fifth_item3 = '';
$fifth_item4 = '';
$sixth_item1 = '';
$sixth_item2 = '';
$sixth_item3 = '';
$sixth_item4 = '';
$seventh_item1 = '';
$seventh_item2 = '';
$seventh_item3 = '';
$seventh_item4 = '';
$eighth_item1 = '';
$eighth_item2 = '';
$eighth_item3 = '';
$eighth_item4 = '';
$ninth_item1 = '';
$ninth_item2 = '';
$ninth_item3 = '';
$ninth_item4 = '';
$tenth_item1 = '';
$tenth_item2 = '';
$tenth_item3 = '';
$tenth_item4 = '';

// 타입/인승/카사이즈 2~10 초기화
$type2 = '';
$type3 = '';
$type4 = '';
$type5 = '';
$type6 = '';
$type7 = '';
$type8 = '';
$type9 = '';
$type10 = '';
$inseung2 = '';
$inseung3 = '';
$inseung4 = '';
$inseung5 = '';
$inseung6 = '';
$inseung7 = '';
$inseung8 = '';
$inseung9 = '';
$inseung10 = '';
$car_insize2 = '';
$car_insize3 = '';
$car_insize4 = '';
$car_insize5 = '';
$car_insize6 = '';
$car_insize7 = '';
$car_insize8 = '';
$car_insize9 = '';
$car_insize10 = '';
$comment1 = '';
$comment2 = '';
$comment3 = '';
$comment4 = '';
$comment5 = '';
$comment6 = '';
$comment7 = '';
$comment8 = '';
$comment9 = '';
$comment10 = '';

// 기타 날짜 관련 변수
$workday = '';
$demand = '';
$orderday = '';
$testday = '';
$lc_draw = '';
$lclaser_date = '';
$lcbending_date = '';
$lcwelding_date = '';
$lcpainting_date = '';
$lcassembly_date = '';
$main_draw = '';
$eunsung_make_date = '';
$eunsung_laser_date = '';
$mainbending_date = '';
$mainwelding_date = '';
$mainpainting_date = '';
$mainassembly_date = '';
$order_date1 = '';
$order_date2 = '';
$order_date3 = '';
$order_date4 = '';
$order_input_date1 = '';
$order_input_date2 = '';
$order_input_date3 = '';
$order_input_date4 = '';

try {
    $sql = "SELECT * FROM mirae8440.oem WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        echo "<div class='alert alert-warning'>검색결과가 없습니다.</div>";
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        $item_file_0 = $row["file_name_0"];
        $item_file_1 = $row["file_name_1"];
        $copied_file_0 = "../uploads/" . $row["file_copied_0"];
        $copied_file_1 = "../uploads/" . $row["file_copied_1"];
        
        $num = $row["num"];
        $checkstep = $row["checkstep"];
        $workplacename = $row["workplacename"];
        $address = $row["address"];
        $worker = $row["worker"];
        $secondord = $row["secondord"];
        $firstord = $row["firstord"];
        $startday = $row["startday"];
        $chargedman = $row["chargedman"];
        $chargedmantel = $row["chargedmantel"];
        $memo = $row["memo"];
        $deadline = $row["deadline"];
        $delivery = $row["delivery"];
        $delipay = $row["delipay"];
        
        $delitext = $delivery . ' ' . $delipay;
        
        $type1 = $row["type1"];
        $inseung1 = $row["inseung1"];
        $car_insize1 = $row["car_insize1"];
        $su = (int)$row["su"];
        $lc_su = (int)$row["lc_su"];
        $etc_su = (int)$row["etc_su"];
        $air_su = (int)$row["air_su"];
        
        // 항목 데이터
        $first_item1 = $row["first_item1"];
        $first_item2 = $row["first_item2"];
        $first_item3 = $row["first_item3"];
        $first_item4 = $row["first_item4"];
        $second_item1 = $row["second_item1"];
        $second_item2 = $row["second_item2"];
        $second_item3 = $row["second_item3"];
        $second_item4 = $row["second_item4"];
        $third_item1 = $row["third_item1"];
        $third_item2 = $row["third_item2"];
        $third_item3 = $row["third_item3"];
        $third_item4 = $row["third_item4"];
        $forth_item1 = $row["forth_item1"];
        $forth_item2 = $row["forth_item2"];
        $forth_item3 = $row["forth_item3"];
        $forth_item4 = $row["forth_item4"];
        $fifth_item1 = $row["fifth_item1"];
        $fifth_item2 = $row["fifth_item2"];
        $fifth_item3 = $row["fifth_item3"];
        $fifth_item4 = $row["fifth_item4"];
        $sixth_item1 = $row["sixth_item1"];
        $sixth_item2 = $row["sixth_item2"];
        $sixth_item3 = $row["sixth_item3"];
        $sixth_item4 = $row["sixth_item4"];
        $seventh_item1 = $row["seventh_item1"];
        $seventh_item2 = $row["seventh_item2"];
        $seventh_item3 = $row["seventh_item3"];
        $seventh_item4 = $row["seventh_item4"];
        $eighth_item1 = $row["eighth_item1"];
        $eighth_item2 = $row["eighth_item2"];
        $eighth_item3 = $row["eighth_item3"];
        $eighth_item4 = $row["eighth_item4"];
        $ninth_item1 = $row["ninth_item1"];
        $ninth_item2 = $row["ninth_item2"];
        $ninth_item3 = $row["ninth_item3"];
        $ninth_item4 = $row["ninth_item4"];
        $tenth_item1 = $row["tenth_item1"];
        $tenth_item2 = $row["tenth_item2"];
        $tenth_item3 = $row["tenth_item3"];
        $tenth_item4 = $row["tenth_item4"];
        
        $type2 = $row["type2"];
        $type3 = $row["type3"];
        $type4 = $row["type4"];
        $type5 = $row["type5"];
        $type6 = $row["type6"];
        $type7 = $row["type7"];
        $type8 = $row["type8"];
        $type9 = $row["type9"];
        $type10 = $row["type10"];
        $inseung2 = $row["inseung2"];
        $inseung3 = $row["inseung3"];
        $inseung4 = $row["inseung4"];
        $inseung5 = $row["inseung5"];
        $inseung6 = $row["inseung6"];
        $inseung7 = $row["inseung7"];
        $inseung8 = $row["inseung8"];
        $inseung9 = $row["inseung9"];
        $inseung10 = $row["inseung10"];
        $car_insize2 = $row["car_insize2"];
        $car_insize3 = $row["car_insize3"];
        $car_insize4 = $row["car_insize4"];
        $car_insize5 = $row["car_insize5"];
        $car_insize6 = $row["car_insize6"];
        $car_insize7 = $row["car_insize7"];
        $car_insize8 = $row["car_insize8"];
        $car_insize9 = $row["car_insize9"];
        $car_insize10 = $row["car_insize10"];
        $comment1 = $row["comment1"];
        $comment2 = $row["comment2"];
        $comment3 = $row["comment3"];
        $comment4 = $row["comment4"];
        $comment5 = $row["comment5"];
        $comment6 = $row["comment6"];
        $comment7 = $row["comment7"];
        $comment8 = $row["comment8"];
        $comment9 = $row["comment9"];
        $comment10 = $row["comment10"];
        
        // 날짜 변환
        $workday = trans_date($row["workday"] ?? '');
        $startday = trans_date($row["startday"] ?? '');
        $demand = trans_date($row["demand"] ?? '');
        $orderday = trans_date($row["orderday"] ?? '');
        $deadline = trans_date($row["deadline"] ?? '');
        $testday = trans_date($row["testday"] ?? '');
        $lc_draw = trans_date($row["lc_draw"] ?? '');
        $lclaser_date = trans_date($row["lclaser_date"] ?? '');
        $lcbending_date = trans_date($row["lcbending_date"] ?? '');
        $lcwelding_date = trans_date($row["lcwelding_date"] ?? '');
        $lcpainting_date = trans_date($row["lcpainting_date"] ?? '');
        $lcassembly_date = trans_date($row["lcassembly_date"] ?? '');
        $main_draw = trans_date($row["main_draw"] ?? '');
        $eunsung_make_date = trans_date($row["eunsung_make_date"] ?? '');
        $eunsung_laser_date = trans_date($row["eunsung_laser_date"] ?? '');
        $mainbending_date = trans_date($row["mainbending_date"] ?? '');
        $mainwelding_date = trans_date($row["mainwelding_date"] ?? '');
        $mainpainting_date = trans_date($row["mainpainting_date"] ?? '');
        $mainassembly_date = trans_date($row["mainassembly_date"] ?? '');
        $order_date1 = trans_date($row["order_date1"] ?? '');
        $order_date2 = trans_date($row["order_date2"] ?? '');
        $order_date3 = trans_date($row["order_date3"] ?? '');
        $order_date4 = trans_date($row["order_date4"] ?? '');
        $order_input_date1 = trans_date($row["order_input_date1"] ?? '');
        $order_input_date2 = trans_date($row["order_input_date2"] ?? '');
        $order_input_date3 = trans_date($row["order_input_date3"] ?? '');
        $order_input_date4 = trans_date($row["order_input_date4"] ?? '');
    }
} catch (PDOException $ex) {
    error_log("출고증 데이터 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}

// 배열 변수 초기화
$text = [];
$item = [];
$spec = [];
$carsize = [];
$item_memo = [];
$textnum = [];
$textset = [];

$text[0] = $type1;
$carsize[0] = $car_insize1;
$textnum[0] = $lc_su;
$item_memo[0] = $memo;
$textset[0] = 'SET';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>출고증 자료 입력화면</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/work.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<div id="wrap">
    <form name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
        
        <div id="content">
            <div id="work_col2">
                <div id="estimate_text2" style="width:980px;">
                    
                    <input type="hidden" id="delitext" name="delitext" value="<?= htmlspecialchars($delitext, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="deadline" name="deadline" value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="startday" name="startday" value="<?= htmlspecialchars($startday, ENT_QUOTES, 'UTF-8') ?>">
                    
                    <div class="sero1" style="width:500px;">
                        <h1>외주발주(서한 컴퍼니) 출고증</h1>
                    </div>
                    
                    <div id="write_button_renew">
                        <input type="image" src="../img/print.png" alt="Print">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" onclick="window.close();" value="창 닫기">창 닫기</button>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    
                    <div class="sero1" style="color:blue;">(귀중) :</div>
                    <div class="sero2">
                        <input type="text" name="firstord" value="<?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?>" size="30" placeholder="(귀중)">
                    </div>
                    <div class="clear"></div>
                    
                    <div class="sero1" style="color:red;">하차일시 :</div>
                    <div class="sero2">
                        <input type="text" name="outputdate" value="<?= htmlspecialchars($outputdate, ENT_QUOTES, 'UTF-8') ?>" size="30" placeholder="하차일시" required>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="sero1">발주(업체) :</div>
                    <div class="sero2">
                        <input type="text" name="secondord" value="<?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="발주처(업체명)" required>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="sero1">현장명 :</div>
                    <div class="sero2">
                        <input type="text" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장명" required>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="sero1">현장주소 :</div>
                    <div class="sero2">
                        <input type="text" name="address" value="<?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장주소">
                    </div>
                    <div class="clear"></div>
                    
                    <div class="sero1">받으실분 :</div>
                    <div class="sero2">
                        <input type="text" name="chargedman" value="<?= htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="담당자">
                    </div>
                    <div class="sero1" style="width:130px;">받으실분 연락처 :</div>
                    <div class="sero2">
                        <input type="text" name="chargedmantel" id="chargedmantel" value="<?= htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="담당자 연락처">
                    </div>
                    <div class="clear"></div>
                    <div class="space"></div>
                    <div class="clear"></div>
                    
                    <?php
                    echo '<div class="sero2" style="width:370px;color:red;"></div>';
                    echo '<div class="clear"></div>';
                    
                    for ($i = 0; $i <= 9; $i++) {
                        $safe_text = htmlspecialchars($text[$i] ?? '', ENT_QUOTES, 'UTF-8');
                        $safe_item = htmlspecialchars($item[$i] ?? '', ENT_QUOTES, 'UTF-8');
                        $safe_spec = htmlspecialchars($spec[$i] ?? '', ENT_QUOTES, 'UTF-8');
                        $safe_textnum = htmlspecialchars($textnum[$i] ?? '', ENT_QUOTES, 'UTF-8');
                        $safe_textset = htmlspecialchars($textset[$i] ?? '', ENT_QUOTES, 'UTF-8');
                        $safe_carsize = htmlspecialchars($carsize[$i] ?? '', ENT_QUOTES, 'UTF-8');
                        $safe_item_memo = htmlspecialchars($item_memo[$i] ?? '', ENT_QUOTES, 'UTF-8');
                        
                        echo '<div class="sero1">' . ($i + 1) . '번째줄 :</div>';
                        echo '<div class="sero2" style="width:100px;"><input type="text" name="text[]" value="' . $safe_text . '" size="10" placeholder="타입(Type)"></div>';
                        echo '<div class="sero2" style="width:100px;margin-left:10px"><input type="text" name="item[]" value="' . $safe_item . '" size="10" placeholder="품목"></div>';
                        echo '<div class="sero2" style="width:100px;margin-left:10px"><input type="text" name="spec[]" value="' . $safe_spec . '" size="10" placeholder="규격"></div>';
                        echo '<div class="sero2" style="margin-left:30px;width:20px;"><input type="text" name="textnum[]" value="' . $safe_textnum . '" size="1" placeholder="수량"></div>';
                        echo '<div class="sero2" style="margin-left:30px;width:20px;"><input type="text" name="textset[]" value="' . $safe_textset . '" size="1" placeholder="단위"></div>';
                        echo '<div class="sero2" style="margin-left:40px;width:100px;"><input type="text" name="carsize[]" value="' . $safe_carsize . '" size="10" placeholder="Car insize"></div>';
                        echo '<div class="sero2" style="margin-left:10px;width:150px;"><input type="text" name="item_memo[]" value="' . $safe_item_memo . '" size="30" placeholder="비고"></div>';
                        echo '<div class="clear"></div>';
                    }
                    ?>
                    
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    $(function() {
        $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
    });
    
    /**
     * 숫자 포맷팅
     */
    window.inputNumberFormat = function(obj) {
        if (obj && obj.value) {
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
     * 날짜 입력 마스크 (eval() 제거하여 보안 개선)
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
            var chk_date = checkdate(text);
            if (chk_date == false) {
                return;
            }
        }
    };
    
    /**
     * 날짜 유효성 검사
     */
    function checkdate(input) {
        var validformat = /^\d{4}\-\d{2}\-\d{2}$/;
        var returnval = false;
        
        if (!validformat.test(input.value)) {
            alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
        } else {
            var yearfield = input.value.split("-")[0];
            var monthfield = input.value.split("-")[1];
            var dayfield = input.value.split("-")[2];
            var dayobj = new Date(yearfield, monthfield - 1, dayfield);
            
            if ((dayobj.getMonth() + 1 != monthfield) ||
                (dayobj.getDate() != dayfield) ||
                (dayobj.getFullYear() != yearfield)) {
                alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            } else {
                returnval = true;
            }
        }
        
        if (returnval == false) {
            input.select();
        }
        return returnval;
    }
    
    /**
     * 텍스트 입력 및 클립보드 복사
     */
    window.input_Text = function() {
        var testElement = document.getElementById("test");
        if (testElement) {
            testElement.value = comma(Math.floor(uncomma(testElement.value) * 1.1));
            testElement.select();
            document.execCommand("Copy");
        }
    };
    
    /**
     * 아래로 복사
     */
    window.copy_below = function() {
        var park = document.getElementsByName("asfee");
        var ashistoryElement = document.getElementById("ashistory");
        var asdayElement = document.getElementById("asday");
        var aswriterElement = document.getElementById("aswriter");
        var asordermanElement = document.getElementById("asorderman");
        var asordermantelElement = document.getElementById("asordermantel");
        var asfeeElement = document.getElementById("asfee");
        var asfeeEstimateElement = document.getElementById("asfee_estimate");
        var aslistElement = document.getElementById("aslist");
        var asReferElement = document.getElementById("as_refer");
        var asprodayElement = document.getElementById("asproday");
        var setdateElement = document.getElementById("setdate");
        var asmanElement = document.getElementById("asman");
        var asenddayElement = document.getElementById("asendday");
        var asresultElement = document.getElementById("asresult");
        
        if (!ashistoryElement) return;
        
        ashistoryElement.value = ashistoryElement.value + (asdayElement ? asdayElement.value : '') + " " + (aswriterElement ? aswriterElement.value : '') + " " + (asordermanElement ? asordermanElement.value : '') + " ";
        ashistoryElement.value = ashistoryElement.value + (asordermantelElement ? asordermantelElement.value : '') + " ";
        
        if (park && park[1] && park[1].checked) {
            ashistoryElement.value = ashistoryElement.value + " 유상 " + (asfeeElement ? asfeeElement.value : '') + " ";
        } else {
            ashistoryElement.value = ashistoryElement.value + " 무상 " + (asfeeElement ? asfeeElement.value : '') + " ";
        }
        
        ashistoryElement.value += (asfeeEstimateElement ? asfeeEstimateElement.value : '') + " " + (aslistElement ? aslistElement.value : '') + " " + (asReferElement ? asReferElement.value : '') + " ";
        ashistoryElement.value += (asprodayElement ? asprodayElement.value : '') + " " + (setdateElement ? setdateElement.value : '') + " " + (asmanElement ? asmanElement.value : '') + " ";
        ashistoryElement.value += (asenddayElement ? asenddayElement.value : '') + " " + (asresultElement ? asresultElement.value : '') + "        ";
    };
    
    /**
     * 초기화
     */
    window.del_below = function() {
        if (confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
            var asdayElement = document.getElementById("asday");
            var aswriterElement = document.getElementById("aswriter");
            
            if (asdayElement) asdayElement.value = "";
            if (aswriterElement) aswriterElement.value = "";
        }
    };
    
    /**
     * Enter 키 체크 함수들
     */
    window.Enter_Check = function() {
        if (event.keyCode == 13) {
            exe_search();
        }
    };
    
    window.Enter_firstCheck = function() {
        if (event.keyCode == 13) {
            exe_firstordman();
        }
    };
    
    window.Enter_chargedman_Check = function() {
        if (event.keyCode == 13) {
            exe_chargedman();
        }
    };
    
    /**
     * 발주처 담당자 자동 입력
     */
    window.exe_search = function() {
        var tmp = $('#secondordman').val();
        switch (tmp) {
            case '김관':
                $("#secondordmantel").val("010-2648-0225");
                $("#secondordman").val("김관부장");
                $("#secondord").val("한산");
                break;
        }
    };
    
    /**
     * 원청 담당자 자동 입력
     */
    window.exe_firstordman = function() {
        var tmp = $('#firstordman').val();
        switch (tmp) {
            case '고범섭':
                $("#firstordman").val("고범섭소장");
                $("#firstordmantel").val("010-6774-6211");
                $("#firstord").val("오티스");
                $("#secondord").val("우성");
                break;
        }
    };
    
    /**
     * 현장소장 자동 입력
     */
    window.exe_chargedman = function() {
        // 필요시 구현
    };
    
    /**
     * Enter 키 캡처 (textarea 제외)
     */
    window.captureReturnKey = function(e) {
        if (e.keyCode == 13 && e.srcElement.type != 'textarea') {
            return false;
        }
    };
    
    /**
     * Enter 키 재캡처
     */
    window.recaptureReturnKey = function(e) {
        if (e.keyCode == 13) {
            exe_search();
        }
    };
    
})();
</script>

</body>
</html>
