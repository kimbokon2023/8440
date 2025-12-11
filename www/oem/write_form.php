<?php
/**
 * 서한컴퍼니 발주 작성/수정 폼
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 권한 체크
if (!isset($_SESSION["level"])) {
    sleep(1);
    header("Location: {$base_url}/login/logout.php");
    exit;
}
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 서한컴퍼니</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/outorder.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
<div class="container-fluid">
    <div class="d-flex mb-1 justify-content-center">
        <a href="../index.php"><img src="../img/toplogo.jpg" style="width:100%;" alt="Logo"></a>
    </div>
    
    <?php require_once(includePath('myheader.php')); ?>
</div>

<?php
// 요청 변수 초기화 (?? '' 형태)
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$Eworks_page = $_REQUEST["Eworks_page"] ?? 1;
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$check_draw = $_REQUEST["check_draw"] ?? $_POST["check_draw"] ?? '0';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';
$cursort = $_REQUEST["cursort"] ?? $_POST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? $_POST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? $_POST["stable"] ?? '0';

require_once("../lib/mydb.php");
$pdo = db_connect();

// 모든 변수 초기화
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
$measureday = '';
$drawday = '';
$deadline = '';
$workday = '';
$worker = '';
$endworkday = '';
$material1 = '';
$material2 = '';
$material3 = '';
$material4 = '';
$material5 = '';
$material6 = '';
$widejamb = '';
$normaljamb = '';
$smalljamb = '';
$memo = '';
$regist_day = '';
$update_day = '';
$delivery = '';
$delicar = '';
$delicompany = '';
$delipay = '';
$delimethod = '';
$demand = '';
$startday = '';
$testday = '';
$hpi = '';
$first_writer = '';
$update_log = '';
$item_file_0 = '';
$item_file_1 = '';
$copied_file_0 = '';
$copied_file_1 = '';
$type1 = '';
$inseung1 = '';
$car_insize1 = '';
$su = '';
$bon_su = '';
$lc_su = '';
$etc_su = '';
$air_su = '';
$order_com1 = '';
$order_text1 = '';
$order_com2 = '';
$order_text2 = '';
$order_com3 = '';
$order_text3 = '';
$order_com4 = '';
$order_text4 = '';
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
$order_date1 = '';
$order_date2 = '';
$order_date3 = '';
$order_date4 = '';
$order_input_date1 = '';
$order_input_date2 = '';
$order_input_date3 = '';
$order_input_date4 = '';
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

if ($mode == "modify") {
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
            $item_file_0 = $row["file_name_0"] ?? '';
            $item_file_1 = $row["file_name_1"] ?? '';
            $copied_file_0 = !empty($row["file_copied_0"]) ? "../uploads/" . $row["file_copied_0"] : '';
            $copied_file_1 = !empty($row["file_copied_1"]) ? "../uploads/" . $row["file_copied_1"] : '';
            
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
            $workday = $row["workday"] ?? '';
            $worker = $row["worker"] ?? '';
            $endworkday = $row["endworkday"] ?? '';
            $material1 = $row["material1"] ?? '';
            $material2 = $row["material2"] ?? '';
            $material3 = $row["material3"] ?? '';
            $material4 = $row["material4"] ?? '';
            $material5 = $row["material5"] ?? '';
            $material6 = $row["material6"] ?? '';
            $widejamb = $row["widejamb"] ?? '';
            $normaljamb = $row["normaljamb"] ?? '';
            $smalljamb = $row["smalljamb"] ?? '';
            $memo = $row["memo"] ?? '';
            $regist_day = $row["regist_day"] ?? '';
            $update_day = $row["update_day"] ?? '';
            $delivery = $row["delivery"] ?? '';
            $delicar = $row["delicar"] ?? '';
            $delicompany = $row["delicompany"] ?? '';
            $delipay = $row["delipay"] ?? '';
            $delimethod = $row["delimethod"] ?? '';
            $demand = $row["demand"] ?? '';
            $startday = $row["startday"] ?? '';
            $testday = $row["testday"] ?? '';
            $hpi = $row["hpi"] ?? '';
            $first_writer = $row["first_writer"] ?? '';
            $update_log = $row["update_log"] ?? '';
            
            $type1 = $row["type1"] ?? '';
            $inseung1 = $row["inseung1"] ?? '';
            $car_insize1 = $row["car_insize1"] ?? '';
            $su = $row["su"] ?? '';
            $bon_su = $row["bon_su"] ?? '';
            $lc_su = $row["lc_su"] ?? '';
            $etc_su = $row["etc_su"] ?? '';
            $air_su = $row["air_su"] ?? '';
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
            $eunsung_laser_date = $row["eunsung_laser_date"] ?? '';
            $mainbending_date = $row["mainbending_date"] ?? '';
            $mainwelding_date = $row["mainwelding_date"] ?? '';
            $mainpainting_date = $row["mainpainting_date"] ?? '';
            $mainassembly_date = $row["mainassembly_date"] ?? '';
            $memo2 = $row["memo2"] ?? '';
            $order_date1 = $row["order_date1"] ?? '';
            $order_date2 = $row["order_date2"] ?? '';
            $order_date3 = $row["order_date3"] ?? '';
            $order_date4 = $row["order_date4"] ?? '';
            $order_input_date1 = $row["order_input_date1"] ?? '';
            $order_input_date2 = $row["order_input_date2"] ?? '';
            $order_input_date3 = $row["order_input_date3"] ?? '';
            $order_input_date4 = $row["order_input_date4"] ?? '';
            
            $first_item1 = $row["first_item1"] ?? '';
            $first_item2 = $row["first_item2"] ?? '';
            $first_item3 = $row["first_item3"] ?? '';
            $first_item4 = $row["first_item4"] ?? '';
            $second_item1 = $row["second_item1"] ?? '';
            $second_item2 = $row["second_item2"] ?? '';
            $second_item3 = $row["second_item3"] ?? '';
            $second_item4 = $row["second_item4"] ?? '';
            $third_item1 = $row["third_item1"] ?? '';
            $third_item2 = $row["third_item2"] ?? '';
            $third_item3 = $row["third_item3"] ?? '';
            $third_item4 = $row["third_item4"] ?? '';
            $forth_item1 = $row["forth_item1"] ?? '';
            $forth_item2 = $row["forth_item2"] ?? '';
            $forth_item3 = $row["forth_item3"] ?? '';
            $forth_item4 = $row["forth_item4"] ?? '';
            $fifth_item1 = $row["fifth_item1"] ?? '';
            $fifth_item2 = $row["fifth_item2"] ?? '';
            $fifth_item3 = $row["fifth_item3"] ?? '';
            $fifth_item4 = $row["fifth_item4"] ?? '';
            $sixth_item1 = $row["sixth_item1"] ?? '';
            $sixth_item2 = $row["sixth_item2"] ?? '';
            $sixth_item3 = $row["sixth_item3"] ?? '';
            $sixth_item4 = $row["sixth_item4"] ?? '';
            $seventh_item1 = $row["seventh_item1"] ?? '';
            $seventh_item2 = $row["seventh_item2"] ?? '';
            $seventh_item3 = $row["seventh_item3"] ?? '';
            $seventh_item4 = $row["seventh_item4"] ?? '';
            $eighth_item1 = $row["eighth_item1"] ?? '';
            $eighth_item2 = $row["eighth_item2"] ?? '';
            $eighth_item3 = $row["eighth_item3"] ?? '';
            $eighth_item4 = $row["eighth_item4"] ?? '';
            $ninth_item1 = $row["ninth_item1"] ?? '';
            $ninth_item2 = $row["ninth_item2"] ?? '';
            $ninth_item3 = $row["ninth_item3"] ?? '';
            $ninth_item4 = $row["ninth_item4"] ?? '';
            $tenth_item1 = $row["tenth_item1"] ?? '';
            $tenth_item2 = $row["tenth_item2"] ?? '';
            $tenth_item3 = $row["tenth_item3"] ?? '';
            $tenth_item4 = $row["tenth_item4"] ?? '';
            
            $type2 = $row["type2"] ?? '';
            $type3 = $row["type3"] ?? '';
            $type4 = $row["type4"] ?? '';
            $type5 = $row["type5"] ?? '';
            $type6 = $row["type6"] ?? '';
            $type7 = $row["type7"] ?? '';
            $type8 = $row["type8"] ?? '';
            $type9 = $row["type9"] ?? '';
            $type10 = $row["type10"] ?? '';
            $inseung2 = $row["inseung2"] ?? '';
            $inseung3 = $row["inseung3"] ?? '';
            $inseung4 = $row["inseung4"] ?? '';
            $inseung5 = $row["inseung5"] ?? '';
            $inseung6 = $row["inseung6"] ?? '';
            $inseung7 = $row["inseung7"] ?? '';
            $inseung8 = $row["inseung8"] ?? '';
            $inseung9 = $row["inseung9"] ?? '';
            $inseung10 = $row["inseung10"] ?? '';
            $car_insize2 = $row["car_insize2"] ?? '';
            $car_insize3 = $row["car_insize3"] ?? '';
            $car_insize4 = $row["car_insize4"] ?? '';
            $car_insize5 = $row["car_insize5"] ?? '';
            $car_insize6 = $row["car_insize6"] ?? '';
            $car_insize7 = $row["car_insize7"] ?? '';
            $car_insize8 = $row["car_insize8"] ?? '';
            $car_insize9 = $row["car_insize9"] ?? '';
            $car_insize10 = $row["car_insize10"] ?? '';
            $comment1 = $row["comment1"] ?? '';
            $comment2 = $row["comment2"] ?? '';
            $comment3 = $row["comment3"] ?? '';
            $comment4 = $row["comment4"] ?? '';
            $comment5 = $row["comment5"] ?? '';
            $comment6 = $row["comment6"] ?? '';
            $comment7 = $row["comment7"] ?? '';
            $comment8 = $row["comment8"] ?? '';
            $comment9 = $row["comment9"] ?? '';
            $comment10 = $row["comment10"] ?? '';
            
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
        error_log("OEM 수정 데이터 조회 오류 (num: {$num}): " . $ex->getMessage());
        echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
    }
} else {
    // 신규 등록 모드 - 기본값 설정
    $orderday = date("Y-m-d");
    $startday = date("Y-m-d");
    $firstord = "서한컴퍼니";
    $secondord = "성광";
    $first_item1 = "프레임";
    $first_item4 = "SET";
    $second_item1 = "중판";
    $second_item4 = "EA";
}

$material_arr = ['', '304 Hair Line 1.2T', '304 HL 1.2T', '304 Mirror 1.2T', '304 MR 1.2T', 'VB 1.2T', '2B VB 1.2T', '304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)', '기타'];
?>

</div>
<div id="wrap">
    <?php
    $form_params = http_build_query([
        'mode' => 'modify',
        'num' => $num,
        'Eworks_page' => $Eworks_page,
        'search' => $search,
        'find' => $find,
        'process' => $process,
        'yearcheckbox' => $yearcheckbox,
        'year' => $year,
        'check' => $check,
        'output_check' => $output_check,
        'team_check' => $team_check,
        'plan_output_check' => $plan_output_check,
        'cursort' => $cursort,
        'sortof' => $sortof,
        'stable' => 1,
        'check_draw' => $check_draw
    ], '', '&', PHP_QUERY_RFC3986);
    
    if ($mode == "modify") {
    ?>
        <form name="board_form" id="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?<?= htmlspecialchars($form_params, ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
    <?php } else { ?>
        <form name="board_form" id="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?mode=not" enctype="multipart/form-data">
    <?php } ?>
    
    <div id="container">
        <div class="d-flex mb-1 justify-content-center">
            <div id="estimate_text2" style="width:840px;height:650px;font-size:14px;">
                
                <div class="sero1" style="width:500px;">
                    <h3>서한 컴퍼니 발주</h3>
                </div>
                
                <input type="hidden" id="first_writer" name="first_writer" value="<?= htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" id="update_log" name="update_log" value="<?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?>">
                
                <?php
                $list_url_params = http_build_query([
                    'Eworks_page' => $Eworks_page,
                    'search' => $search,
                    'find' => $find,
                    'process' => $process,
                    'yearcheckbox' => $yearcheckbox,
                    'year' => $year,
                    'check' => $check,
                    'output_check' => $output_check,
                    'team_check' => $team_check,
                    'plan_output_check' => $plan_output_check
                ], '', '&', PHP_QUERY_RFC3986);
                ?>
                
                <button type="button" id="saveBtn" class="btn btn-secondary">DATA 저장</button>&nbsp;
                <button type="button" id="gotoList" class="btn btn-secondary" onclick="location.href='list.php?<?= htmlspecialchars($list_url_params, ENT_QUOTES, 'UTF-8') ?>'">목록(List)</button>&nbsp;
                
                <div class="clear"></div>
                <br>
                
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
                
                <div class="sero1">매입처 :</div>
                <div class="sero2">
                    <input type="text" id="firstord" name="firstord" value="<?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="매입처">
                </div>
                <div class="sero1" style="text-align:right;">담당 :</div>
                <div class="sero2">
                    <input type="text" id="firstordman" name="firstordman" value="<?= htmlspecialchars($firstordman, ENT_QUOTES, 'UTF-8') ?>" size="10" onkeydown="Enter_firstCheck();" placeholder="원청담당">
                </div>
                <div class="sero1">연락처 :</div>
                <div class="sero2">
                    <input type="text" id="firstordmantel" name="firstordmantel" value="<?= htmlspecialchars($firstordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호">
                </div>
                <div class="clear"></div>
                
                <div class="sero1">발주처 :</div>
                <div class="sero2">
                    <input type="text" id="secondord" name="secondord" value="<?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="발주처">
                </div>
                <div class="sero1" style="text-align:right;">담당 :</div>
                <div class="sero2">
                    <input type="text" id="secondordman" name="secondordman" value="<?= htmlspecialchars($secondordman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="발주처 담당자" onkeydown="Enter_Check();">
                </div>
                <div class="sero1">연락처 :</div>
                <div class="sero2">
                    <input type="text" id="secondordmantel" name="secondordmantel" value="<?= htmlspecialchars($secondordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호">
                </div>
                <div class="clear"></div>
                
                <div class="sero1">담당자 :</div>
                <div class="sero2">
                    <input type="text" name="chargedman" id="chargedman" value="<?= htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="현장담당자" onkeydown="Enter_chargedman_Check();">
                </div>
                <div class="sero1" style="text-align:right;">연락처 :</div>
                <div class="sero2">
                    <input type="text" name="chargedmantel" id="chargedmantel" value="<?= htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="현장담당전화">
                </div>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <span>접수일 :
                    <input type="date" name="orderday" id="orderday" value="<?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?>">
                    발주일 :
                    <input type="date" name="startday" id="startday" value="<?= htmlspecialchars($startday, ENT_QUOTES, 'UTF-8') ?>">
                </span>
                <span style="color:red;">
                    납기일 :
                    <input type="date" name="deadline" id="deadline" value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>">
                </span>
                <span style="color:blue;">
                    출고일 :
                    <input type="date" name="workday" id="workday" value="<?= htmlspecialchars($workday, ENT_QUOTES, 'UTF-8') ?>">
                </span>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div class="sero1">타입(Type)</div>
                <div class="sero4">
                    <select name="type1">
                        <?php
                        $arrSel = ["NP50", "NP60", "NP70", "NP80", "기타"];
                        foreach ($arrSel as $option) {
                            if ($type1 == $option) {
                                echo "<option selected value='" . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . "</option>";
                            } else {
                                echo "<option value='" . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="sero1">인승</div>
                <div class="sero4">
                    <input type="text" name="inseung1" value="<?= htmlspecialchars($inseung1, ENT_QUOTES, 'UTF-8') ?>" size="5" placeholder="인승">
                </div>
                <div class="sero1">car insize</div>
                <div class="sero4">
                    <input type="text" name="car_insize1" value="<?= htmlspecialchars($car_insize1, ENT_QUOTES, 'UTF-8') ?>" size="8" placeholder="Car insize">
                </div>
                <div class="clear"></div>
                
                <div class="sero1">L/C수량:</div>
                <div class="sero4">
                    <input type="text" name="lc_su" value="<?= htmlspecialchars($lc_su, ENT_QUOTES, 'UTF-8') ?>" size="2" placeholder="수량">
                </div>
                <div class="sero1">기타 수량:</div>
                <div class="sero4">
                    <input type="text" name="etc_su" value="<?= htmlspecialchars($etc_su, ENT_QUOTES, 'UTF-8') ?>" size="2" placeholder="수량">
                </div>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div id="delivery_col">운송비 :
                    <input type="text" name="delivery" id="delivery" value="<?= htmlspecialchars($delivery, ENT_QUOTES, 'UTF-8') ?>" placeholder="운반비내역">
                    <span style="color:red;">운임(있을시 기록) :</span>
                    <input type="text" name="delipay" id="delipay" value="<?= htmlspecialchars($delipay, ENT_QUOTES, 'UTF-8') ?>" placeholder="운임금액" onkeyup="inputNumberFormat(this)">
                </div>
                
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div class="box1">
                    <div class="box2 box_col1" style="width:500px;">
                        <div class="sero6" style="width:200px;">비고(마구리/도장)</div>
                        <div class="clear"></div>
                        <div class="sero5">
                            <textarea rows="3" cols="65" name="memo" placeholder="비고1"><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <br><br><br><br><br>
                        <div class="clear"></div>
                        
                        <div class="sero1" style="font-size:18px;color:red;width:400;">청구일자 :
                            <input type="date" name="demand" id="demand" value="<?= htmlspecialchars($demand, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="청구일, 계산서발행">
                        </div>
                    </div>
                    
                    <div class="box1 box_col2">
                        <?php echo "최초등록자 : " . htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8'); ?>
                        <br><br>
                        <?php echo "수정기록 : "; ?>
                        <br>
                        <textarea rows="3" cols="32" name="display_log"><?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?></textarea>
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
    
    var level = <?php echo json_encode($level, JSON_UNESCAPED_UNICODE); ?>;
    
    $(document).ready(function() {
        console.log('Level:', level);
        
        if (level == '7') {
            $("div *").find("input,textarea").prop("disabled", true);
            $("#workday").prop("disabled", false);
            $("#update_log").prop("disabled", false);
            $("#delivery").prop("disabled", false);
            $("#delipay").prop("disabled", false);
        }
        
        $("#saveBtn").click(function() {
            $("#board_form").submit();
        });
    });
    
    var imgObj = new Image();
    
    /**
     * 이미지 창 표시
     */
    window.showImgWin = function(imgName) {
        imgObj.src = imgName;
        setTimeout(function() {
            createImgWin(imgObj);
        }, 100);
    };
    
    /**
     * 이미지 창 생성
     */
    function createImgWin(imgObj) {
        if (!imgObj.complete) {
            setTimeout(function() {
                createImgWin(imgObj);
            }, 100);
            return;
        }
        var imageWin = window.open("", "imageWin", "width=" + imgObj.width + ",height=" + imgObj.height);
    }
    
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
     * 텍스트 입력
     */
    window.input_Text = function() {
        var testElement = document.getElementById("test");
        if (testElement) {
            testElement.value = comma(Math.floor(uncomma(testElement.value) * 1.1));
            var copyText = testElement;
            copyText.select();
            document.execCommand("Copy");
        }
    };
    
    /**
     * 아래로 복사
     */
    window.copy_below = function() {
        var park = document.getElementsByName("asfee");
        var ashistoryElement = document.getElementById("ashistory");
        
        if (!ashistoryElement) return;
        
        var asdayVal = document.getElementById("asday") ? document.getElementById("asday").value : '';
        var aswriterVal = document.getElementById("aswriter") ? document.getElementById("aswriter").value : '';
        var asordermanVal = document.getElementById("asorderman") ? document.getElementById("asorderman").value : '';
        var asordermantelVal = document.getElementById("asordermantel") ? document.getElementById("asordermantel").value : '';
        var asfeeVal = document.getElementById("asfee") ? document.getElementById("asfee").value : '';
        var asfeeEstimateVal = document.getElementById("asfee_estimate") ? document.getElementById("asfee_estimate").value : '';
        var aslistVal = document.getElementById("aslist") ? document.getElementById("aslist").value : '';
        var asReferVal = document.getElementById("as_refer") ? document.getElementById("as_refer").value : '';
        var asprodayVal = document.getElementById("asproday") ? document.getElementById("asproday").value : '';
        var setdateVal = document.getElementById("setdate") ? document.getElementById("setdate").value : '';
        var asmanVal = document.getElementById("asman") ? document.getElementById("asman").value : '';
        var asenddayVal = document.getElementById("asendday") ? document.getElementById("asendday").value : '';
        var asresultVal = document.getElementById("asresult") ? document.getElementById("asresult").value : '';
        
        ashistoryElement.value = ashistoryElement.value + asdayVal + " " + aswriterVal + " " + asordermanVal + " ";
        ashistoryElement.value = ashistoryElement.value + asordermantelVal + " ";
        
        if (park && park[1] && park[1].checked) {
            ashistoryElement.value = ashistoryElement.value + " 유상 " + asfeeVal + " ";
        } else {
            ashistoryElement.value = ashistoryElement.value + " 무상 " + asfeeVal + " ";
        }
        
        ashistoryElement.value += asfeeEstimateVal + " " + aslistVal + " " + asReferVal + " ";
        ashistoryElement.value += asprodayVal + " " + setdateVal + " " + asmanVal + " ";
        ashistoryElement.value += asenddayVal + " " + asresultVal + "        ";
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
            var data1 = "oem";
            var data2 = "chargedman";
            var data3 = "chargedmantel";
            var search = $("#" + data2).val();
            
            window.open('../ceiling/load_tel.php?search=' + encodeURIComponent(search) + '&data1=' + encodeURIComponent(data1) + '&data2=' + encodeURIComponent(data2) + '&data3=' + encodeURIComponent(data3), '전번 조회', 'top=0,left=0,width=1500px,height=600px,scrollbars=yes');
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
        // 필요시 구현
    };
    
    /**
     * 현장담당자 자동 입력
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
