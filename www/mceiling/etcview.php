<?php
/**
 * 기타품목 조회/수정 페이지
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

// 날짜 변환 함수
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        return date("Y-m-d", strtotime($tdate));
    }
    return "";
}

// 세션 변수
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : '';
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 999;

// 파일 디렉토리
$file_dir = '../uploads/';

// 요청 변수 초기화
$num = getRequestValue("num", '');
$search = getRequestValue("search", '');
$find = getRequestValue("find", '');
$page = getRequestValue("page", 1);
$check = getRequestValue("check", '0');
$scale = getRequestValue("scale", 30);
$cursort = getRequestValue("cursort", '0');
$sortof = getRequestValue("sortof", '0');
$stable = getRequestValue("stable", '0');

// 현재 날짜
$nowday = date("Y-m-d");

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 변수 초기화 (DB에서 가져올 값들)
$checkstep = $workplacename = $address = '';
$firstord = $firstordman = $firstordmantel = '';
$secondord = $secondordman = $secondordmantel = '';
$chargedman = $chargedmantel = '';
$orderday = $measureday = $drawday = $deadline = '';
$delicompany = $delivery = $delipay = '';
$workday = $startday = $testday = $worker = $endworkday = '';
$material1 = $material2 = $material3 = $material4 = $material5 = $material6 = '';
$memo = $regist_day = $update_day = $demand = '';
$type = $inseung = $su = $bon_su = $lc_su = $etc_su = $air_su = '';
$car_insize = '';
$order_com1 = $order_text1 = $order_com2 = $order_text2 = '';
$order_com3 = $order_text3 = $order_com4 = $order_text4 = '';
$lc_draw = $lclaser_com = $lclaser_date = '';
$lcbending_date = $lcwelding_date = $lcpainting_date = $lcassembly_date = '';
$main_draw = $eunsung_make_date = $eunsung_laser_date = '';
$mainbending_date = $mainwelding_date = $mainpainting_date = $mainassembly_date = '';
$etclaser_date = $etcbending_date = $etcwelding_date = $etcpainting_date = $etcassembly_date = '';
$memo2 = '';
$order_date1 = $order_date2 = $order_date3 = $order_date4 = '';
$order_input_date1 = $order_input_date2 = $order_input_date3 = $order_input_date4 = '';
$first_writer = $update_log = $check_draw = '';
$date_font = "black";
$date_font1 = "black";

// 데이터 조회
try {
    $sql = "SELECT * FROM mirae8440.ceiling WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $num = $row["num"] ?? '';
        $checkstep = $row["checkstep"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $address = $row["address"] ?? '';
        $firstord = $row["firstord"] ?? '';
        $firstordman = $row["firstordman"] ?? '';
        $firstordmantel = $row["firstordmantel"] ?? '';
        $secondord = $row["secondord"] ?? '';
        $secondordman = $row["secondordman"] ?? '';
        $secondordmantel = $row["secondordmantel"] ?? '';
        $chargedman = $row["chargedman"] ?? '';
        $chargedmantel = $row["chargedmantel"] ?? '';
        $orderday = $row["orderday"] ?? '';
        $measureday = $row["measureday"] ?? '';
        $drawday = $row["drawday"] ?? '';
        $deadline = $row["deadline"] ?? '';
        $delicompany = $row["delicompany"] ?? '';
        $delivery = $row["delivery"] ?? '';
        $delipay = $row["delipay"] ?? '';
        $workday = $row["workday"] ?? '';
        $startday = $row["startday"] ?? '';
        $testday = $row["testday"] ?? '';
        $worker = $row["worker"] ?? '';
        $endworkday = $row["endworkday"] ?? '';
        $material1 = $row["material1"] ?? '';
        $material2 = $row["material2"] ?? '';
        $material3 = $row["material3"] ?? '';
        $material4 = $row["material4"] ?? '';
        $material5 = $row["material5"] ?? '';
        $material6 = $row["material6"] ?? '';
        $memo = $row["memo"] ?? '';
        $regist_day = $row["regist_day"] ?? '';
        $update_day = $row["update_day"] ?? '';
        $demand = $row["demand"] ?? '';
        $type = $row["type"] ?? '';
        $inseung = $row["inseung"] ?? '';
        $su = $row["su"] ?? '';
        $bon_su = $row["bon_su"] ?? '';
        $lc_su = $row["lc_su"] ?? '';
        $etc_su = $row["etc_su"] ?? '';
        $air_su = $row["air_su"] ?? '';
        $car_insize = $row["car_insize"] ?? '';
        $order_com1 = $row["order_com1"] ?? '';
        $order_text1 = $row["order_text1"] ?? '';
        $order_com2 = $row["order_com2"] ?? '';
        $order_text2 = $row["order_text2"] ?? '';
        $order_com3 = $row["order_com3"] ?? '';
        $order_text3 = $row["order_text3"] ?? '';
        $order_com4 = $row["order_com4"] ?? '';
        $order_text4 = $row["order_text4"] ?? '';
        $lc_draw = $row["lc_draw"] ?? '';
        $lclaser_com = $row["lclaser_com"] ?? '';
        $lclaser_date = $row["lclaser_date"] ?? '';
        $lcbending_date = $row["lcbending_date"] ?? '';
        $lcwelding_date = $row["lcwelding_date"] ?? '';
        $lcpainting_date = $row["lcpainting_date"] ?? '';
        $lcassembly_date = $row["lcassembly_date"] ?? '';
        $main_draw = $row["main_draw"] ?? '';
        $eunsung_make_date = $row["eunsung_make_date"] ?? '';
        $mainbending_date = $row["mainbending_date"] ?? '';
        $mainwelding_date = $row["mainwelding_date"] ?? '';
        $mainpainting_date = $row["mainpainting_date"] ?? '';
        $mainassembly_date = $row["mainassembly_date"] ?? '';
        $etclaser_date = $row["etclaser_date"] ?? '';
        $etcbending_date = $row["etcbending_date"] ?? '';
        $etcwelding_date = $row["etcwelding_date"] ?? '';
        $etcpainting_date = $row["etcpainting_date"] ?? '';
        $etcassembly_date = $row["etcassembly_date"] ?? '';
        $memo2 = $row["memo2"] ?? '';
        
        // 날짜 색상
        $date_font = ($nowday == $orderday) ? "red" : "black";
        $date_font1 = ($nowday == $workday) ? "blue" : "black";
        
        // 날짜 변환
        $workday = trans_date($workday);
        $demand = trans_date($demand);
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        $testday = trans_date($testday);
        $lc_draw = trans_date($lc_draw);
        $lclaser_date = trans_date($lclaser_date);
        $lcbending_date = trans_date($lcbending_date);
        $lcwelding_date = trans_date($lcwelding_date);
        $lcpainting_date = trans_date($lcpainting_date);
        $lcassembly_date = trans_date($lcassembly_date);
        $main_draw = trans_date($main_draw);
        $eunsung_make_date = trans_date($eunsung_make_date);
        $mainbending_date = trans_date($mainbending_date);
        $mainwelding_date = trans_date($mainwelding_date);
        $mainpainting_date = trans_date($mainpainting_date);
        $mainassembly_date = trans_date($mainassembly_date);
        $etclaser_date = trans_date($etclaser_date);
        $etcbending_date = trans_date($etcbending_date);
        $etcwelding_date = trans_date($etcwelding_date);
        $etcpainting_date = trans_date($etcpainting_date);
        $etcassembly_date = trans_date($etcassembly_date);
    }
    
} catch (PDOException $ex) {
    error_log("데이터 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "오류: 데이터를 불러오는 중 문제가 발생했습니다.";
}

// 도면 표시 처리
$main_draw_arr = "";
if (substr($main_draw, 0, 2) == "20") {
    $main_draw_arr = mb_substr($main_draw, 0, 10, "utf-8");
} elseif ($bon_su < 1) {
    $main_draw_arr = "X";
}

$lc_draw_arr = "";
if (substr($lc_draw, 0, 2) == "20") {
    $lc_draw_arr = mb_substr($lc_draw, 0, 10, "utf-8");
} elseif ($lc_su < 1) {
    $lc_draw_arr = "X";
}

if (in_array($type, ['011', '012', '013D', '025', '017', '014'])) {
    $lc_draw_arr = "X";
}

// 기타품 날짜 표시
if ((int)$etc_su > 0) {
    $etclaser_date = mb_substr($etclaser_date, 5, 5, "utf-8");
    $etcbending_date = mb_substr($etcbending_date, 5, 5, "utf-8");
    $etcwelding_date = mb_substr($etcwelding_date, 5, 5, "utf-8");
    $etcpainting_date = mb_substr($etcpainting_date, 5, 5, "utf-8");
    $etcassembly_date = mb_substr($etcassembly_date, 5, 5, "utf-8");
} else {
    $etclaser_date = "X";
    $etcbending_date = "X";
    $etcwelding_date = "X";
    $etcpainting_date = "X";
    $etcassembly_date = "X";
}

$workplacename = "(" . $secondord . ")" . $workplacename;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 통합정보시스템 기타(판넬 등) 조회/수정</title>
    
    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/jexcel.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/partner.css" type="text/css" />
    
    <style>
        #panel, #flip {
            padding: 3px;
            text-align: left;
            color: brown;
            border: solid 1px #c3c3c3;
        }
        
        #panel {
            padding: 40px;
            display: none;
        }
        
        #addpanel, #addflip {
            padding: 3px;
            text-align: center;
            color: white;
            background-color: grey;
            border: solid 1px #c3c3c3;
        }
        
        #addpanel {
            padding: 30px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div id="top-menu">
            <?php if (!isset($_SESSION["userid"])): ?>
                <a href="../login/login_form.php">로그인</a> | 
                <a href="../member/insertForm.php">회원가입</a>
            <?php else: ?>
                <div id="vacancy" style="display:none"></div>
                <div class="row">
                    <div class="col-6">
                        <h3 class="display-5 font-center text-left">
                            <?= htmlspecialchars($_SESSION["name"] ?? '', ENT_QUOTES, 'UTF-8') ?> | 
                            <a href="../login/logout.php">로그아웃</a> | 
                            <a href="../member/updateForm.php?id=<?= htmlspecialchars($_SESSION["userid"] ?? '', ENT_QUOTES, 'UTF-8') ?>">정보수정</a>
                        </h3>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <br>
        <button type="button" class="btn btn-primary btn-lg" onclick="location.href='../mceiling/etclist.php?cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>';">이전화면 이동</button>
        <div class="clear"></div>
        <br>
        
        <div class="row">
            <h2 class="display-3 text-left">
                <div id="flip">&nbsp;&nbsp;&nbsp; 기타(판넬 등) 조회/수정</div>
            </h2>
        </div>
        
        <input type="hidden" id="first_writer" name="first_writer" value="<?= htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="update_log" name="update_log" value="<?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="check_draw" name="check_draw" value="<?= htmlspecialchars($check_draw, ENT_QUOTES, 'UTF-8') ?>" size="1">
        <input type="hidden" id="scale" name="scale" value="<?= htmlspecialchars($scale, ENT_QUOTES, 'UTF-8') ?>" size="1">
        
        <div class="row">
            <h1 class="display-5 font-center text-center">
                <span style="color:grey;">현장명:</span>
                <?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>
            </h1>
        </div>
        
        <div class="row">
            <h1 class="display-5 font-center text-center">
                <span style="color:grey;">타입:</span>
                <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
            </h1>
        </div>
        
        <div class="row">
            <h1 class="display-5 font-center text-center">
                <span style="color:grey;">메모1:</span>
                <?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?>
            </h1>
        </div>
        
        <div class="row">
            <h1 class="display-5 font-center text-center">
                <span style="color:grey;">발주접수일:</span>
                <?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </h1>
            <h1 class="display-5 font-center text-center">
                <span style="color:red;">납기일:</span>
                <span style="color:grey;"><?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?></span>
            </h1>
        </div>
        
        <div class="row">
            <h1 class="display-5 font-center text-center">
                <span style="color:grey;">본천장설계:</span>
                <?= htmlspecialchars($main_draw_arr, ENT_QUOTES, 'UTF-8') ?>
                &nbsp;&nbsp;&nbsp;
            </h1>
            <h1 class="display-5 font-center text-center">
                <span style="color:grey;">LC설계:</span>
                <?= htmlspecialchars($lc_draw_arr, ENT_QUOTES, 'UTF-8') ?>
            </h1>
        </div>
        
        <div class="row">
            <h1 class="display-5 font-center text-center">
                <span style="color:grey;">결합단위(SET)</span>
                <?= htmlspecialchars($su, ENT_QUOTES, 'UTF-8') ?>
                &nbsp;&nbsp;&nbsp;
            </h1>
            <?php if ((int)$bon_su > 0): ?>
                <h1 class="display-5 font-center text-center">
                    <span style="color:grey;">본천장 수량:</span>
                    <?= htmlspecialchars($bon_su, ENT_QUOTES, 'UTF-8') ?>
                    &nbsp;&nbsp;&nbsp;
                </h1>
            <?php endif; ?>
            <?php if ((int)$lc_su > 0): ?>
                <h1 class="display-5 font-center text-center">
                    <span style="color:grey;">L/C수량:</span>
                    <?= htmlspecialchars($lc_su, ENT_QUOTES, 'UTF-8') ?>
                    &nbsp;&nbsp;&nbsp;
                </h1>
            <?php endif; ?>
            <?php if ((int)$etc_su > 0): ?>
                <h1 class="display-5 font-center text-center">
                    <span style="color:grey;">기타 수량:</span>
                    <?= htmlspecialchars($etc_su, ENT_QUOTES, 'UTF-8') ?>
                    &nbsp;&nbsp;&nbsp;
                </h1>
            <?php endif; ?>
            <?php if ((int)$air_su > 0): ?>
                <h1 class="display-5 font-center text-center">
                    <span style="color:grey;">공기청정기:</span>
                    <?= htmlspecialchars($air_su, ENT_QUOTES, 'UTF-8') ?>
                    &nbsp;&nbsp;&nbsp;
                </h1>
            <?php endif; ?>
        </div>
        
        <div class="row">&nbsp;&nbsp;&nbsp;</div>
        <div class="row">&nbsp;&nbsp;&nbsp;</div>
        
        <div class="row">
            <h1 class="display-3 font-center text-center">
                <span style="color:green;">기타(판넬 등) 제조현황</span>
            </h1>
        </div>
        
        <div class="row">
            <h2 class="display-4 font-center text-center">
                기타 laser완료:
                <input type="text" name="etclaser_date" id="etclaser_date" 
                       value="<?= htmlspecialchars($etclaser_date, ENT_QUOTES, 'UTF-8') ?>" size="7">
            </h2>
            &nbsp;&nbsp;&nbsp;
            <?php if ($etclaser_date != 'X'): ?>
                <button type="button" class="btn btn-primary btn-lg" onclick="dodata('etclaser_date');">완료</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-lg" onclick="dodatadel('etclaser_date');">삭제</button>
            <?php endif; ?>
        </div>
        
        <div class="row">
            <h1 class="display-4 font-center text-center">
                기타 절곡완료:
                <input type="text" name="etcbending_date" id="etcbending_date" 
                       value="<?= htmlspecialchars($etcbending_date, ENT_QUOTES, 'UTF-8') ?>" size="7">
            </h1>
            &nbsp;&nbsp;&nbsp;
            <?php if ($etcbending_date != 'X'): ?>
                <button type="button" class="btn btn-primary btn-lg" onclick="dodata('etcbending_date');">완료</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-lg" onclick="dodatadel('etcbending_date');">삭제</button>
            <?php endif; ?>
        </div>
        
        <div class="row">
            <h1 class="display-4 font-center text-center">
                기타 제관완료:
                <input type="text" name="etcwelding_date" id="etcwelding_date" 
                       value="<?= htmlspecialchars($etcwelding_date, ENT_QUOTES, 'UTF-8') ?>" size="7">
            </h1>
            &nbsp;&nbsp;&nbsp;
            <?php if ($etcwelding_date != 'X'): ?>
                <button type="button" class="btn btn-primary btn-lg" onclick="dodata('etcwelding_date');">완료</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-lg" onclick="dodatadel('etcwelding_date');">삭제</button>
            <?php endif; ?>
        </div>
        
        <div class="row">
            <h1 class="display-4 font-center text-center">
                기타 도장완료:
                <input type="text" name="etcpainting_date" id="etcpainting_date" 
                       value="<?= htmlspecialchars($etcpainting_date, ENT_QUOTES, 'UTF-8') ?>" size="7">
            </h1>
            &nbsp;&nbsp;&nbsp;
            <?php if ($etcpainting_date != 'X'): ?>
                <button type="button" class="btn btn-primary btn-lg" onclick="dodata('etcpainting_date');">완료</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-lg" onclick="dodatadel('etcpainting_date');">삭제</button>
            <?php endif; ?>
        </div>
        
        <div class="row">
            <h1 class="display-4 font-center text-center">
                기타 조립완료:
                <input type="text" name="etcassembly_date" id="etcassembly_date" 
                       value="<?= htmlspecialchars($etcassembly_date, ENT_QUOTES, 'UTF-8') ?>" size="7">
            </h1>
            &nbsp;&nbsp;&nbsp;
            <?php if ($etcassembly_date != 'X'): ?>
                <button type="button" class="btn btn-primary btn-lg" onclick="dodata('etcassembly_date');">완료</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-lg" onclick="dodatadel('etcassembly_date');">삭제</button>
            <?php endif; ?>
        </div>
        
        <div class="row">&nbsp;&nbsp;&nbsp;</div>
        <div class="row">&nbsp;&nbsp;&nbsp;</div>
        
        <div class="row">
            <h2 class="display-3 font-center text-center">
                <div id="addflip">추가정보 보기</div>
            </h2>
        </div>
        
        <div id="addpanel">
            <div class="row"><h2 class="display-5 font-center text-center">현장주소: <?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">제품출고일: <?= htmlspecialchars($workday, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">원청: <?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">담당: <?= htmlspecialchars($firstordman, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">연락처: <?= htmlspecialchars($firstordmantel, ENT_QUOTES, 'UTF-8') ?></h2></div><br>
            <div class="row"><h2 class="display-5 font-center text-center">발주처: <?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">담당: <?= htmlspecialchars($secondordman, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">연락처: <?= htmlspecialchars($secondordmantel, ENT_QUOTES, 'UTF-8') ?></h2></div><br>
            <div class="row"><h2 class="display-5 font-center text-center">운반비내역: <?= htmlspecialchars($delivery, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($delipay, ENT_QUOTES, 'UTF-8') ?></h2></div><br>
            <div class="row"><h2 class="display-5 font-center text-center">담당: <?= htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">타입: <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?> &nbsp;&nbsp;&nbsp; 인승: <?= htmlspecialchars($inseung, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">car insize: <?= htmlspecialchars($car_insize, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">재질1: <?= htmlspecialchars($material2, ENT_QUOTES, 'UTF-8') ?> &nbsp;&nbsp;&nbsp; <?= htmlspecialchars($material1, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">재질2: <?= htmlspecialchars($material4, ENT_QUOTES, 'UTF-8') ?> &nbsp;&nbsp;&nbsp; <?= htmlspecialchars($material3, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">재질3: <?= htmlspecialchars($material6, ENT_QUOTES, 'UTF-8') ?> &nbsp;&nbsp;&nbsp; <?= htmlspecialchars($material5, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">비고1: <?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">비고2: <?= htmlspecialchars($memo2, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">청구일자: <?= htmlspecialchars($demand, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row">&nbsp;&nbsp;&nbsp;</div>
            <div class="row"><h2 class="display-5 font-center text-center">자료 최초등록자: <?= htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8') ?></h2></div>
            <div class="row"><h2 class="display-5 font-center text-center">자료 수정기록: <?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?></h2></div>
        </div>
    </div>
</body>
</html>

<script type="text/javascript">
(function() {
    'use strict';
    
    var imgObj = new Image();
    var user_name = <?= json_encode($user_name, JSON_UNESCAPED_UNICODE) ?>;
    var num = <?= json_encode($num, JSON_UNESCAPED_UNICODE) ?>;
    
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
    
    window.input_Text = function() {
        var testElem = document.getElementById("test");
        if (testElem) {
            testElem.value = comma(Math.floor(uncomma(testElem.value) * 1.1));
        }
    };
    
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
    
    window.del_below = function() {
        if (confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
            var asdayElem = document.getElementById("asday");
            var aswriterElem = document.getElementById("aswriter");
            if (asdayElem) asdayElem.value = "";
            if (aswriterElem) aswriterElem.value = "";
        }
    };
    
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
    
    window.displayoutputlist = function() {
        if (typeof $ !== 'undefined') {
            $("#displayoutput").show();
            $("#displayoutput").load("./outputetclist.php");
        }
    };
    
    function check_alert() {
        if (typeof $ === 'undefined') return;
        
        var tmp = "../load_alert.php";
        $("#vacancy").load(tmp);
        
        setTimeout(function() {
            var voc_alert = $("#voc_alert").val();
            var ma_alert = $("#ma_alert").val();
            
            if (user_name == '김진억' && voc_alert == '1') {
                if (typeof alertify !== 'undefined') {
                    alertify.alert('<H1> 현장VOC 도착 알림</H1>', '<h1> 김진억 이사님 <br><br> 현장VOC가 접수되었습니다. 확인 후 조치바랍니다. </h1>');
                }
                tmp = "../save_alert.php?voc_alert=0&ma_alert=" + ma_alert;
                $("#voc_alert").val('0');
                $("#vacancy").load(tmp);
            }
            
            if (user_name == '조경임' && ma_alert == '1') {
                if (typeof alertify !== 'undefined') {
                    alertify.alert('<h1> 발주서 접수 알림 </h1>', '<h1> 조과장님 <br><br> 발주서가 접수되었습니다. 내역 확인 후 발주해 주세요. </h1>');
                }
                tmp = "../save_alert.php?ma_alert=0&voc_alert=" + voc_alert;
                $("#ma_alert").val('0');
                $("#vacancy").load(tmp);
            }
        }, 100);
    }
    
    var timer = setInterval(function() {
        check_alert();
    }, 5000);
    
    $(document).ready(function() {
        $("#addflip").click(function() {
            $("#addpanel").slideToggle();
        });
        
        $("#addpanel").click(function() {
            $("#addpanel").slideUp("slow");
        });
    });
    
    window.dodata = function(anyone) {
        var id = "#" + anyone;
        var tmp = "./insert.php?num=" + num + "&data=" + anyone;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        today = yyyy + '-' + mm + '-' + dd;
        
        if (typeof $ !== 'undefined') {
            $("#vacancy").load(tmp);
            $(id).val(today);
        }
    };
    
    window.dodatadel = function(anyone) {
        var id = "#" + anyone;
        var tmp = "./insert.php?num=" + num + "&deldata=" + anyone;
        
        if (typeof $ !== 'undefined') {
            $("#vacancy").load(tmp);
            $(id).val('');
        }
    };
    
    window.dodata_all = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        var arr = [];
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        today = yyyy + '-' + mm + '-' + dd;
        
        for (var i = 0; i < 5; i++) {
            var tmp = "./insert.php?num=" + num + "&data=" + arr[i];
            if (typeof $ !== 'undefined') {
                $("#vacancy").load(tmp);
                var id = "#" + arr[i];
                $(id).val(today);
            }
        }
    };
    
    window.dodatadel_all = function() {
        // 전체 삭제 로직
    };
    
})();
</script>
