<?php
/**
 * 서한컴퍼니 외주 데이터 복사 페이지
 * 로컬 및 서버 환경 모두 지원
 */

if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location: {$base_url}/login/logout.php");
    exit;
}

// 날짜 변환 함수
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}

// 요청 변수 초기화
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : '';
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : '';
$process = isset($_REQUEST["process"]) ? $_REQUEST["process"] : '전체';
$yearcheckbox = isset($_REQUEST["yearcheckbox"]) ? $_REQUEST["yearcheckbox"] : '';
$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : '';
$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : '0';
$output_check = isset($_REQUEST["output_check"]) ? $_REQUEST["output_check"] : '0';
$team_check = isset($_REQUEST["team_check"]) ? $_REQUEST["team_check"] : '0';
$plan_output_check = isset($_REQUEST["plan_output_check"]) ? $_REQUEST["plan_output_check"] : '0';
$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : '0';
$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : '0';
$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : '0';
$check_draw = isset($_REQUEST["check_draw"]) ? $_REQUEST["check_draw"] : '0';

require_once("../lib/mydb.php");
$pdo = db_connect();

// 데이터 변수 초기화
$item_file_0 = '';
$item_file_1 = '';
$copied_file_0 = '';
$copied_file_1 = '';
$checkstep = '';
$workplacename = '';
$address = '';
$firstord = '';
$firstordman = '';
$firstordmantel = '';
$secondord = '';
$secondordman = '';
$secondordmantel = '';
$chargedman = '';
$chargedmantel = '';
$orderday = '';
$measureday = null;
$drawday = null;
$deadline = null;
$workday = null;
$worker = '';
$endworkday = null;
$widejamb = '';
$normaljamb = '';
$smalljamb = '';
$memo = '';
$regist_day = '';
$update_day = '';
$delicar = '없음';
$delivery = '';
$delipay = '';
$delimethod = '없음';
$demand = null;
$startday = '';
$testday = '';
$hpi = '';
$first_writer = '';
$update_log = '';

// Type 관련 변수
$type1 = '';
$type2 = '';
$type3 = '';
$type4 = '';
$type5 = '';
$type6 = '';
$type7 = '';
$type8 = '';
$type9 = '';
$type10 = '';

// 인승 관련 변수
$inseung1 = '';
$inseung2 = '';
$inseung3 = '';
$inseung4 = '';
$inseung5 = '';
$inseung6 = '';
$inseung7 = '';
$inseung8 = '';
$inseung9 = '';
$inseung10 = '';

// Car insize 관련 변수
$car_insize1 = '';
$car_insize2 = '';
$car_insize3 = '';
$car_insize4 = '';
$car_insize5 = '';
$car_insize6 = '';
$car_insize7 = '';
$car_insize8 = '';
$car_insize9 = '';
$car_insize10 = '';

// 수량 관련 변수
$su = '';
$bon_su = '';
$lc_su = '';
$etc_su = '';
$air_su = '';

// Comment 관련 변수
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

// Order 관련 변수
$order_com1 = '';
$order_com2 = '';
$order_com3 = '';
$order_com4 = '';
$order_text1 = '';
$order_text2 = '';
$order_text3 = '';
$order_text4 = '';
$order_date1 = '';
$order_date2 = '';
$order_date3 = '';
$order_date4 = '';
$order_input_date1 = '';
$order_input_date2 = '';
$order_input_date3 = '';
$order_input_date4 = '';

// Draw/Process 관련 변수
$lc_draw = '';
$lclaser_com = '';
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
$memo2 = '';

// Item 관련 변수 (1-10개 항목, 각 4개씩)
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

// 복사 모드 - 기존 데이터 조회
if ($mode == "copy") {
    try {
        $sql = "SELECT * FROM mirae8440.oem WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        
        if ($count < 1) {
            error_log("복사할 데이터 없음 (num: {$num})");
            echo "<script>alert('검색결과가 없습니다.'); window.close();</script>";
            exit;
        } else {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            
            $item_file_0 = $row["file_name_0"];
            $item_file_1 = $row["file_name_1"];
            $copied_file_0 = "../uploads/" . $row["file_copied_0"];
            $copied_file_1 = "../uploads/" . $row["file_copied_1"];
            
            $checkstep = $row["checkstep"];
            $workplacename = $row["workplacename"];
            $address = $row["address"];
            $firstord = $row["firstord"];
            $firstordman = $row["firstordman"];
            $firstordmantel = $row["firstordmantel"];
            $secondord = $row["secondord"];
            $secondordman = $row["secondordman"];
            $secondordmantel = $row["secondordmantel"];
            $chargedman = $row["chargedman"];
            $chargedmantel = $row["chargedmantel"];
            $orderday = $row["orderday"];
            $worker = $row["worker"];
            $widejamb = $row["widejamb"];
            $normaljamb = $row["normaljamb"];
            $smalljamb = $row["smalljamb"];
            $memo = $row["memo"];
            $regist_day = $row["regist_day"];
            $update_day = $row["update_day"];
            $measureday = $row["measureday"];
            $drawday = $row["drawday"];
            $workday = $row["workday"];
            $deadline = $row["deadline"];
            $endworkday = $row["endworkday"];
            $startday = $row["startday"];
            $testday = $row["testday"];
            $hpi = $row["hpi"];
            $first_writer = $row["first_writer"];
            $update_log = $row["update_log"];
            
            // Type/인승/Car insize 데이터
            $type1 = $row["type1"];
            $type2 = $row["type2"];
            $type3 = $row["type3"];
            $type4 = $row["type4"];
            $type5 = $row["type5"];
            $type6 = $row["type6"];
            $type7 = $row["type7"];
            $type8 = $row["type8"];
            $type9 = $row["type9"];
            $type10 = $row["type10"];
            
            $inseung1 = $row["inseung1"];
            $inseung2 = $row["inseung2"];
            $inseung3 = $row["inseung3"];
            $inseung4 = $row["inseung4"];
            $inseung5 = $row["inseung5"];
            $inseung6 = $row["inseung6"];
            $inseung7 = $row["inseung7"];
            $inseung8 = $row["inseung8"];
            $inseung9 = $row["inseung9"];
            $inseung10 = $row["inseung10"];
            
            $car_insize1 = $row["car_insize1"];
            $car_insize2 = $row["car_insize2"];
            $car_insize3 = $row["car_insize3"];
            $car_insize4 = $row["car_insize4"];
            $car_insize5 = $row["car_insize5"];
            $car_insize6 = $row["car_insize6"];
            $car_insize7 = $row["car_insize7"];
            $car_insize8 = $row["car_insize8"];
            $car_insize9 = $row["car_insize9"];
            $car_insize10 = $row["car_insize10"];
            
            // 수량 데이터
            $su = $row["su"];
            $bon_su = $row["bon_su"];
            $lc_su = $row["lc_su"];
            $etc_su = $row["etc_su"];
            $air_su = $row["air_su"];
            
            // Comment 데이터
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
            
            // Order 관련 데이터
            $order_com1 = $row["order_com1"];
            $order_text1 = $row["order_text1"];
            $order_com2 = $row["order_com2"];
            $order_text2 = $row["order_text2"];
            $order_com3 = $row["order_com3"];
            $order_text3 = $row["order_text3"];
            $order_com4 = $row["order_com4"];
            $order_text4 = $row["order_text4"];
            $order_date1 = $row["order_date1"];
            $order_date2 = $row["order_date2"];
            $order_date3 = $row["order_date3"];
            $order_date4 = $row["order_date4"];
            $order_input_date1 = $row["order_input_date1"];
            $order_input_date2 = $row["order_input_date2"];
            $order_input_date3 = $row["order_input_date3"];
            $order_input_date4 = $row["order_input_date4"];
            
            // Draw/Process 데이터
            $lc_draw = $row["lc_draw"];
            $lclaser_com = $row["lclaser_com"];
            $lclaser_date = $row["lclaser_date"];
            $lcbending_date = $row["lcbending_date"];
            $lcwelding_date = $row["lcwelding_date"];
            $lcpainting_date = $row["lcpainting_date"];
            $lcassembly_date = $row["lcassembly_date"];
            $main_draw = $row["main_draw"];
            $eunsung_make_date = $row["eunsung_make_date"];
            $eunsung_laser_date = $row["eunsung_laser_date"];
            $mainbending_date = $row["mainbending_date"];
            $mainwelding_date = $row["mainwelding_date"];
            $mainpainting_date = $row["mainpainting_date"];
            $mainassembly_date = $row["mainassembly_date"];
            $memo2 = $row["memo2"];
            
            // Item 데이터
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
            
            // 날짜 변환 적용
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
            $eunsung_laser_date = trans_date($eunsung_laser_date);
            $mainbending_date = trans_date($mainbending_date);
            $mainwelding_date = trans_date($mainwelding_date);
            $mainpainting_date = trans_date($mainpainting_date);
            $mainassembly_date = trans_date($mainassembly_date);
            $order_date1 = trans_date($order_date1);
            $order_date2 = trans_date($order_date2);
            $order_date3 = trans_date($order_date3);
            $order_date4 = trans_date($order_date4);
            $order_input_date1 = trans_date($order_input_date1);
            $order_input_date2 = trans_date($order_input_date2);
            $order_input_date3 = trans_date($order_input_date3);
            $order_input_date4 = trans_date($order_input_date4);
        }
    } catch (PDOException $ex) {
        error_log("복사 데이터 조회 오류 (num: {$num}): " . $ex->getMessage());
        echo "<script>alert('오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "'); window.close();</script>";
        exit;
    }
}

$mode = "";

// 자재 배열
$material_arr = ['', '304 Hair Line 1.2T', '304 HL 1.2T', '304 Mirror 1.2T', '304 MR 1.2T', 'VB 1.2T', '2B VB 1.2T', '304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)', '기타'];
?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 서한컴퍼니 발주</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/outorder.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php include '../myheader.php'; ?>

<div id="wrap">
    <?php if ($mode == "modify") { ?>
        <form id="board_form" name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?mode=modify&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&yearcheckbox=<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&sortof=<?= htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8') ?>&stable=1&check_draw=<?= htmlspecialchars($check_draw, ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
    <?php } else { ?>
        <form id="board_form" name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?mode=not" enctype="multipart/form-data">
    <?php } ?>
    
    <div id="container">
        <div class="d-flex mb-1 justify-content-center">
            <div id="estimate_text2" style="width:840px;height:650px;font-size:14px;">
                <div class="sero1" style="width:500px;">
                    <h3>서한 컴퍼니 발주</h3>
                </div>
                
                <input type="hidden" id="first_writer" name="first_writer" value="<?= htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" id="update_log" name="update_log" value="<?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?>">
                
                <button type="button" id="saveBtn" class="btn btn-secondary">DATA 저장</button>&nbsp;
                <button type="button" id="gotoList" class="btn btn-secondary" onclick="location.href='list.php?&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&yearcheckbox=<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>'">목록(List)</button>&nbsp;
                
                <div class="clear"></div>
                <br>
                
                <div class="sero1">현장명 :</div>
                <div class="sero2"><input type="text" id="workplacename" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장명" required></div>
                <div class="clear"></div>
                
                <div class="sero1">현장주소 :</div>
                <div class="sero2"><input type="text" id="address" name="address" value="<?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장주소"></div>
                <div class="clear"></div>
                
                <div class="sero1">매입처 :</div>
                <div class="sero2"><input type="text" id="firstord" name="firstord" value="<?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="매입처"></div>
                <div class="sero1" style="text-align:right;">담당 :</div>
                <div class="sero2"><input type="text" id="firstordman" name="firstordman" value="<?= htmlspecialchars($firstordman, ENT_QUOTES, 'UTF-8') ?>" size="10" onkeydown="JavaScript:Enter_firstCheck();" placeholder="원청담당"></div>
                <div class="sero1">연락처 :</div>
                <div class="sero2"><input type="text" id="firstordmantel" name="firstordmantel" value="<?= htmlspecialchars($firstordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호"></div>
                <div class="clear"></div>
                
                <div class="sero1">발주처 :</div>
                <div class="sero2"><input type="text" id="secondord" name="secondord" value="<?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="발주처"></div>
                <div class="sero1" style="text-align:right;">담당 :</div>
                <div class="sero2"><input type="text" id="secondordman" name="secondordman" value="<?= htmlspecialchars($secondordman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="발주처 담당자" onkeydown="JavaScript:Enter_Check();"></div>
                <div class="sero1">연락처 :</div>
                <div class="sero2"><input type="text" id="secondordmantel" name="secondordmantel" value="<?= htmlspecialchars($secondordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호"></div>
                <div class="clear"></div>
                
                <div class="sero1">담당자 :</div>
                <div class="sero2"><input type="text" name="chargedman" id="chargedman" value="<?= htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="현장담당자" onkeydown="JavaScript:Enter_chargedman_Check();"></div>
                <div class="sero1" style="text-align:right;">연락처 :</div>
                <div class="sero2"><input type="text" name="chargedmantel" id="chargedmantel" value="<?= htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="현장담당전화"></div>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <span>접수일 : <input type="date" name="orderday" id="orderday" value="<?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?>">
                발주일 : <input type="date" name="startday" id="startday" value="<?= htmlspecialchars($startday, ENT_QUOTES, 'UTF-8') ?>"></span>
                <span style="color:red;">납기일 : <input type="date" name="deadline" id="deadline" value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>"></span>
                <span style="color:blue;">출고일 : <input type="date" name="workday" id="workday" value="<?= htmlspecialchars($workday, ENT_QUOTES, 'UTF-8') ?>"></span>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div class="sero1">타입(Type)</div>
                <div class="sero4">
                    <select name="type1">
                        <?php
                        $arrSel = ["NP50", "NP60", "NP70", "NP80", "기타"];
                        foreach ($arrSel as $option) {
                            $selected = ($type1 == $option) ? 'selected' : '';
                            echo "<option {$selected} value='" . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="sero1">인승</div>
                <div class="sero4"><input type="text" name="inseung1" value="<?= htmlspecialchars($inseung1, ENT_QUOTES, 'UTF-8') ?>" size="5" placeholder="인승"></div>
                <div class="sero1">car insize</div>
                <div class="sero4"><input type="text" name="car_insize1" value="<?= htmlspecialchars($car_insize1, ENT_QUOTES, 'UTF-8') ?>" size="8" placeholder="Car insize"></div>
                <div class="clear"></div>
                
                <div class="sero1">L/C수량:</div>
                <div class="sero4"><input type="text" name="lc_su" value="<?= htmlspecialchars($lc_su, ENT_QUOTES, 'UTF-8') ?>" size="2" placeholder="수량"></div>
                <div class="sero1">기타 수량:</div>
                <div class="sero4"><input type="text" name="etc_su" value="<?= htmlspecialchars($etc_su, ENT_QUOTES, 'UTF-8') ?>" size="2" placeholder="수량"></div>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div id="delivery_col">
                    운송비 : <input type="text" name="delivery" value="<?= htmlspecialchars($delivery, ENT_QUOTES, 'UTF-8') ?>" placeholder="운반비내역">
                    <span style="color:red;">운임(있을시 기록) :</span> <input type="text" name="delipay" value="<?= htmlspecialchars($delipay, ENT_QUOTES, 'UTF-8') ?>" placeholder="운임금액" onkeyup="inputNumberFormat(this)">
                </div>
                
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div class="box1">
                    <div class="box2 box_col1" style="width:500px;">
                        <div class="sero6" style="width:200px;">비고(마구리/도장)</div>
                        <div class="clear"></div>
                        <div class="sero5"><textarea rows="3" cols="65" name="memo" placeholder="비고1"><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></textarea></div>
                        <br><br><br><br><br>
                        <div class="clear"></div>
                        <div class="sero1" style="font-size:18px;color:red;width:400;">
                            청구일자 : <input type="date" name="demand" id="demand" value="<?= htmlspecialchars($demand, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="청구일, 계산서발행">
                        </div>
                    </div>
                    
                    <div class="box1 box_col2">
                        <?php echo "최초등록자 : " . htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8'); ?><br><br>
                        <?php echo "수정기록 : "; ?><br>
                        <textarea rows="3" cols="32" name="dispaly_log"><?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    </form>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var workplacename = <?php echo json_encode($workplacename, JSON_UNESCAPED_UNICODE); ?>;
    var address = <?php echo json_encode($address, JSON_UNESCAPED_UNICODE); ?>;
    var firstord = <?php echo json_encode($firstord, JSON_UNESCAPED_UNICODE); ?>;
    var firstordman = <?php echo json_encode($firstordman, JSON_UNESCAPED_UNICODE); ?>;
    var firstordmantel = <?php echo json_encode($firstordmantel, JSON_UNESCAPED_UNICODE); ?>;
    var secondord = <?php echo json_encode($secondord, JSON_UNESCAPED_UNICODE); ?>;
    var secondordman = <?php echo json_encode($secondordman, JSON_UNESCAPED_UNICODE); ?>;
    var secondordmantel = <?php echo json_encode($secondordmantel, JSON_UNESCAPED_UNICODE); ?>;
    var chargedman = <?php echo json_encode($chargedman, JSON_UNESCAPED_UNICODE); ?>;
    var chargedmantel = <?php echo json_encode($chargedmantel, JSON_UNESCAPED_UNICODE); ?>;
    
    $(document).ready(function() {
        $("#saveBtn").click(function() {
            $("#board_form").submit();
        });
        
        // 데이터 초기화 확인
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '데이터 복사',
                text: "기본사항을 제외하고 초기화 하실래요?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '네 그렇게 합시다!'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $('#board_form').find('input').each(function() { $(this).val(''); });
                    $('#board_form').find('select').each(function() { $(this).val(''); });
                    $('#board_form').find('textarea').each(function() { $(this).val(''); });
                    
                    $('#workplacename').val(workplacename);
                    $('#address').val(address);
                    $('#firstord').val(firstord);
                    $('#firstordman').val(firstordman);
                    $('#firstordmantel').val(firstordmantel);
                    $('#secondord').val(secondord);
                    $('#secondordman').val(secondordman);
                    $('#secondordmantel').val(secondordmantel);
                    $('#chargedman').val(chargedman);
                    $('#chargedmantel').val(chargedmantel);
                    $('#orderday').val(getToday());
                    $('#startday').val(getToday());
                    
                    Swal.fire('처리되었습니다.', '데이터가 성공적으로 복사되었습니다.', 'success');
                } else {
                    $('#main_draw').val('');
                    $('#lc_draw').val('');
                    $('#deadline').val('');
                    $('#workday').val('');
                    $('#startday').val(getToday());
                    $('#demand').val('');
                    $('#orderday').val(getToday());
                }
            });
        }
        
        // Type 복사 버튼들
        $("#types2").click(function() {
            $("input[name=type2]").val($("input[name=type1]").val());
            $("input[name=inseung2]").val($("input[name=inseung1]").val());
            $("input[name=car_insize2]").val($("input[name=car_insize1]").val());
        });
        
        $("#types3").click(function() {
            $("input[name=type3]").val($("input[name=type2]").val());
            $("input[name=inseung3]").val($("input[name=inseung2]").val());
            $("input[name=car_insize3]").val($("input[name=car_insize2]").val());
        });
        
        $("#types4").click(function() {
            $("input[name=type4]").val($("input[name=type3]").val());
            $("input[name=inseung4]").val($("input[name=inseung3]").val());
            $("input[name=car_insize4]").val($("input[name=car_insize3]").val());
        });
        
        $("#types5").click(function() {
            $("input[name=type5]").val($("input[name=type4]").val());
            $("input[name=inseung5]").val($("input[name=inseung4]").val());
            $("input[name=car_insize5]").val($("input[name=car_insize4]").val());
        });
        
        $("#types6").click(function() {
            $("input[name=type6]").val($("input[name=type5]").val());
            $("input[name=inseung6]").val($("input[name=inseung5]").val());
            $("input[name=car_insize6]").val($("input[name=car_insize5]").val());
        });
        
        $("#types7").click(function() {
            $("input[name=type7]").val($("input[name=type6]").val());
            $("input[name=inseung7]").val($("input[name=inseung6]").val());
            $("input[name=car_insize7]").val($("input[name=car_insize6]").val());
        });
        
        $("#types8").click(function() {
            $("input[name=type8]").val($("input[name=type7]").val());
            $("input[name=inseung8]").val($("input[name=inseung7]").val());
            $("input[name=car_insize8]").val($("input[name=car_insize7]").val());
        });
        
        $("#types9").click(function() {
            $("input[name=type9]").val($("input[name=type8]").val());
            $("input[name=inseung9]").val($("input[name=inseung8]").val());
            $("input[name=car_insize9]").val($("input[name=car_insize8]").val());
        });
        
        $("#types10").click(function() {
            $("input[name=type10]").val($("input[name=type9]").val());
            $("input[name=inseung10]").val($("input[name=inseung9]").val());
            $("input[name=car_insize10]").val($("input[name=car_insize9]").val());
        });
        
        // calculateBoth 버튼들
        $("#cal_both1").click(function() { calculateBoth(1, 'first_item', 'second_item'); });
        $("#cal_both2").click(function() { calculateBoth(2, 'second_item', 'third_item'); });
        $("#cal_both3").click(function() { calculateBoth(3, 'third_item', 'forth_item'); });
        $("#cal_both4").click(function() { calculateBoth(4, 'forth_item', 'fifth_item'); });
        $("#cal_both5").click(function() { calculateBoth(5, 'fifth_item', 'sixth_item'); });
        $("#cal_both6").click(function() { calculateBoth(6, 'sixth_item', 'seventh_item'); });
        $("#cal_both7").click(function() { calculateBoth(7, 'seventh_item', 'eighth_item'); });
        $("#cal_both8").click(function() { calculateBoth(8, 'eighth_item', 'ninth_item'); });
        $("#cal_both9").click(function() { calculateBoth(9, 'ninth_item', 'tenth_item'); });
        $("#cal_both10").click(function() { calculateBoth(10, 'tenth_item', 'tenth_item'); });
        
        // calculateFrame 버튼들
        $("#cal_frame1").click(function() { calculateFrame(1, 'first_item'); });
        $("#cal_frame2").click(function() { calculateFrame(2, 'second_item'); });
        $("#cal_frame3").click(function() { calculateFrame(3, 'third_item'); });
        $("#cal_frame4").click(function() { calculateFrame(4, 'forth_item'); });
        $("#cal_frame5").click(function() { calculateFrame(5, 'fifth_item'); });
        $("#cal_frame6").click(function() { calculateFrame(6, 'sixth_item'); });
        $("#cal_frame7").click(function() { calculateFrame(7, 'seventh_item'); });
        $("#cal_frame8").click(function() { calculateFrame(8, 'eighth_item'); });
        $("#cal_frame9").click(function() { calculateFrame(9, 'ninth_item'); });
        $("#cal_frame10").click(function() { calculateFrame(10, 'tenth_item'); });
    });
    
    $(function() {
        $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
    });
    
    /**
     * calculateBoth 함수
     */
    window.calculateBoth = function(NUM, name1, name2) {
        var type = $("input[name=type" + NUM + "]").val();
        var insize = $("input[name=car_insize" + NUM + "]").val();
        var lc_su_val = $("input[name=lc_su]").val();
        var firstName = name1;
        var secondName = name2;
        var nextNUM = NUM + 1;
        var result;
        var jungSu;
        var divider;
        
        var wide_insize = insize.split('*');
        var wide = Number(wide_insize[0]);
        var depth = Number(wide_insize[1]);
        
        if (type == '011' || type == '012' || type == '025' || type == '017' || type == '014') {
            result = depth - 50;
        } else if (type == '013') {
            result = depth - 20;
        }
        
        $("input[name=" + firstName + "1]").val('프레임');
        $("input[name=" + firstName + "2]").val(result);
        $("input[name=" + firstName + "3]").val(1);
        $("input[name=" + firstName + "4]").val('SET');
        
        var result_wide = 0;
        
        switch (type) {
            case '011':
                result_wide = wide - 730;
                break;
            case '012':
                result_wide = wide - 750;
                break;
            case '013':
                result_wide = wide - 705;
                break;
            case '014':
                result_wide = wide / 2 - 143;
                break;
            case '017':
                result_wide = wide - 810;
                break;
            case '017S':
            case '017s':
                result_wide = wide - 410;
                break;
            case '017m':
            case '017M':
                result_wide = wide - 610;
                break;
            case 'N20':
                result_wide = wide - 705;
                break;
            case '026':
                result_wide = wide - 670;
                break;
            default:
                break;
        }
        
        if (depth < 1000) {
            jungSu = 1;
            divider = 1;
        } else if (depth >= 1800) {
            jungSu = 3;
            divider = 3;
        } else {
            jungSu = 2;
            divider = 2;
        }
        
        var result_depth = 0;
        
        switch (type) {
            case '011':
                result_depth = (depth - 54) / divider;
                break;
            case '012':
                result_depth = (depth - 54) / divider;
                break;
            case '013':
                result_depth = (depth - 20) / divider;
                break;
            case '014':
                result_depth = (depth - 54);
                break;
            case '017':
                if (depth >= 1800) {
                    result_depth = (depth - 60) / 3;
                } else {
                    result_depth = (depth - 60) / 2;
                }
                break;
            case '017S':
            case '017s':
                result_depth = (depth - 60) / divider;
                break;
            case '017m':
            case '017M':
                result_depth = (depth - 60) / divider;
                break;
            case 'N20':
                result_depth = (depth - 56) / divider;
                break;
            case '026':
                result_depth = (depth - 58) / divider;
                break;
            default:
                break;
        }
        
        $("input[name=" + secondName + "1]").val('중판');
        $("input[name=" + secondName + "2]").val(result_wide + "*" + Math.floor(result_depth));
        $("input[name=" + secondName + "3]").val(jungSu);
        $("input[name=" + secondName + "4]").val('EA');
    };
    
    /**
     * calculateFrame 함수
     */
    window.calculateFrame = function(NUM, name1) {
        var type = $("input[name=type" + NUM + "]").val();
        var insize = $("input[name=car_insize" + NUM + "]").val();
        var firstName = name1;
        var result;
        var jungSu;
        
        var wide_insize = insize.split('*');
        var wide = Number(wide_insize[0]);
        var depth = Number(wide_insize[1]);
        
        if (type == '011' || type == '012' || type == '025' || type == '017' || type == '014') {
            result = depth - 50;
        } else if (type == '013') {
            result = depth - 20;
        }
        
        $("input[name=" + firstName + "1]").val('프레임/중판X');
        $("input[name=" + firstName + "2]").val(result);
        $("input[name=" + firstName + "3]").val(1);
        $("input[name=" + firstName + "4]").val('SET');
    };
    
    /**
     * 숫자 포맷팅 함수
     */
    window.inputNumberFormat = function(obj) {
        obj.value = comma(uncomma(obj.value));
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
            var chk_date = checkdate(text);
            if (chk_date == false) {
                return;
            }
        }
    };
    
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
            
            if ((dayobj.getMonth() + 1 != monthfield) || (dayobj.getDate() != dayfield) || (dayobj.getFullYear() != yearfield)) {
                alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            } else {
                returnval = true;
            }
            
            if (returnval == false) {
                input.select();
            }
        }
        
        return returnval;
    }
    
    /**
     * 텍스트 입력 처리 (10% 증가)
     */
    window.input_Text = function() {
        var testElem = document.getElementById("test");
        if (testElem) {
            testElem.value = comma(Math.floor(uncomma(testElem.value) * 1.1));
            testElem.select();
            document.execCommand("Copy");
        }
    };
    
    /**
     * AS 이력 복사
     */
    window.copy_below = function() {
        var park = document.getElementsByName("asfee");
        var ashistory = document.getElementById("ashistory");
        var asday = document.getElementById("asday");
        var aswriter = document.getElementById("aswriter");
        var asorderman = document.getElementById("asorderman");
        var asordermantel = document.getElementById("asordermantel");
        var asfee = document.getElementById("asfee");
        var asfee_estimate = document.getElementById("asfee_estimate");
        var aslist = document.getElementById("aslist");
        var as_refer = document.getElementById("as_refer");
        var asproday = document.getElementById("asproday");
        var setdate = document.getElementById("setdate");
        var asman = document.getElementById("asman");
        var asendday = document.getElementById("asendday");
        var asresult = document.getElementById("asresult");
        
        if (!ashistory) return;
        
        ashistory.value = ashistory.value + (asday ? asday.value + " " : "") + (aswriter ? aswriter.value + " " : "") + (asorderman ? asorderman.value + " " : "");
        ashistory.value = ashistory.value + (asordermantel ? asordermantel.value + " " : "");
        
        if (park[1] && park[1].checked) {
            ashistory.value = ashistory.value + " 유상 " + (asfee ? asfee.value + " " : "");
        } else {
            ashistory.value = ashistory.value + " 무상 " + (asfee ? asfee.value + " " : "");
        }
        
        ashistory.value += (asfee_estimate ? asfee_estimate.value + " " : "") + (aslist ? aslist.value + " " : "") + (as_refer ? as_refer.value + " " : "");
        ashistory.value += (asproday ? asproday.value + " " : "") + (setdate ? setdate.value + " " : "") + (asman ? asman.value + " " : "");
        ashistory.value += (asendday ? asendday.value + " " : "") + (asresult ? asresult.value + "        " : "");
    };
    
    /**
     * 날짜 초기화
     */
    window.deldate = function() {
        var elements = ['measureday', 'drawday', 'workday', 'deadline', 'endworkday', 'startday', 'testday'];
        elements.forEach(function(id) {
            var elem = document.getElementById(id);
            if (elem) elem.value = "";
        });
        
        var today = new Date();
        var printday = today.format('yyyy-MM-dd');
        var orderdayElem = document.getElementById("orderday");
        if (orderdayElem) orderdayElem.value = printday;
    };
    
    /**
     * Date format prototype
     */
    Date.prototype.format = function(f) {
        if (!this.valueOf()) return " ";
        
        var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
        var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];
        var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        var d = this;
        
        return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function($1) {
            switch ($1) {
                case "yyyy": return d.getFullYear();
                case "yy": return (d.getFullYear() % 1000).zf(2);
                case "MM": return (d.getMonth() + 1).zf(2);
                case "dd": return d.getDate().zf(2);
                case "KS": return weekKorShortName[d.getDay()];
                case "KL": return weekKorName[d.getDay()];
                case "ES": return weekEngShortName[d.getDay()];
                case "EL": return weekEngName[d.getDay()];
                case "HH": return d.getHours().zf(2);
                case "hh": 
                    var h = d.getHours() % 12;
                    return (h ? h : 12).zf(2);
                case "mm": return d.getMinutes().zf(2);
                case "ss": return d.getSeconds().zf(2);
                case "a/p": return d.getHours() < 12 ? "오전" : "오후";
                default: return $1;
            }
        });
    };
    
    String.prototype.string = function(len) {
        var s = '', i = 0;
        while (i++ < len) { s += this; }
        return s;
    };
    
    String.prototype.zf = function(len) {
        return "0".string(len - this.length) + this;
    };
    
    Number.prototype.zf = function(len) {
        return this.toString().zf(len);
    };
    
    /**
     * AS 이력 초기화
     */
    window.del_below = function() {
        if (confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
            var asday = document.getElementById("asday");
            var aswriter = document.getElementById("aswriter");
            if (asday) asday.value = "";
            if (aswriter) aswriter.value = "";
        }
    };
    
    /**
     * Enter 키 이벤트
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
            var data1 = "oem";
            var data2 = "chargedman";
            var data3 = "chargedmantel";
            var search = $("#" + data2).val();
            window.open('../ceiling/load_tel.php?search=' + encodeURIComponent(search) + '&data1=' + data1 + '&data2=' + data2 + '&data3=' + data3, '전번 조회', 'top=0, left=0, width=1500px, height=600px, scrollbars=yes');
        }
    };
    
    window.exe_search = function() {
        // 검색 실행
    };
    
    window.exe_firstordman = function() {
        // 원청 담당자 검색
    };
    
    window.exe_chargedman = function() {
        // 현장 담당자 검색
    };
    
    window.captureReturnKey = function(e) {
        if (e.keyCode == 13 && e.srcElement.type != 'textarea') {
            return false;
        }
    };
    
    window.recaptureReturnKey = function(e) {
        if (e.keyCode == 13) {
            exe_search();
        }
    };
    
    window.getToday = function() {
        var now = new Date();
        var year = now.getFullYear();
        var month = now.getMonth() + 1;
        var date = now.getDate();
        
        month = month >= 10 ? month : "0" + month;
        date = date >= 10 ? date : "0" + date;
        
        return "" + year + "-" + month + "-" + date;
    };
    
})();
</script>

</body>
</html>
