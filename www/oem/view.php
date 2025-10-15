<?php
/**
 * 서한컴퍼니 상세보기
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';

if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화 (?? '' 형태)
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
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

<link rel="stylesheet" type="text/css" href="../css/common.css">
<link rel="stylesheet" type="text/css" href="../css/outorder.css">

<title>미래기업 서한컴퍼니</title>
</head>

<body>
<div class="container-fluid">
    <div class="d-flex mb-1 justify-content-center">
        <a href="../index.php"><img src="../img/toplogo.jpg" style="width:100%;" alt="Logo"></a>
    </div>
    
    <?php require_once(includePath('myheader.php')); ?>
</div>

<?php
// 파일 디렉토리
$file_dir = '../uploads/';

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$process = $_REQUEST["process"] ?? '';
$year = $_REQUEST["year"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$check_draw = $_REQUEST["check_draw"] ?? $_POST["check_draw"] ?? '0';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$scale = $_REQUEST["scale"] ?? 30;
$cursort = $_REQUEST["cursort"] ?? $_POST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? $_POST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? $_POST["stable"] ?? '0';
$upnum = $_REQUEST["upnum"] ?? '';
$sort = $_REQUEST["sort"] ?? '';
$m2 = $_REQUEST["m2"] ?? '';

require_once("../lib/mydb.php");
$pdo = db_connect();

// 변수 초기화
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
$filename1 = '';
$filename2 = '';
$imgurl1 = '';
$imgurl2 = '';

// 추가 변수들
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

// 항목 데이터 초기화
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

// 타입/인승/카사이즈 초기화
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

try {
    $sql = "SELECT * FROM mirae8440.oem WHERE num=?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
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
        $measureday = $row["measureday"];
        $drawday = $row["drawday"];
        $deadline = $row["deadline"];
        $workday = $row["workday"];
        $worker = $row["worker"];
        $endworkday = $row["endworkday"];
        $material1 = $row["material1"];
        $material2 = $row["material2"];
        $material3 = $row["material3"];
        $material4 = $row["material4"];
        $material5 = $row["material5"];
        $material6 = $row["material6"];
        $widejamb = $row["widejamb"];
        $normaljamb = $row["normaljamb"];
        $smalljamb = $row["smalljamb"];
        $memo = $row["memo"];
        $regist_day = $row["regist_day"];
        $update_day = $row["update_day"];
        $delivery = $row["delivery"];
        $delicar = $row["delicar"];
        $delicompany = $row["delicompany"];
        $delipay = $row["delipay"];
        $delimethod = $row["delimethod"];
        $demand = $row["demand"];
        $startday = $row["startday"];
        $testday = $row["testday"];
        $hpi = $row["hpi"];
        $first_writer = $row["first_writer"];
        $update_log = $row["update_log"];
        $filename1 = $row["filename1"];
        $filename2 = $row["filename2"];
        $imgurl1 = "../imgwork/" . $filename1;
        $imgurl2 = "../imgwork/" . $filename2;
        
        $type1 = $row["type1"];
        $inseung1 = $row["inseung1"];
        $car_insize1 = $row["car_insize1"];
        $su = $row["su"];
        $bon_su = $row["bon_su"];
        $lc_su = $row["lc_su"];
        $etc_su = $row["etc_su"];
        $air_su = $row["air_su"];
        $order_com1 = $row["order_com1"];
        $order_text1 = $row["order_text1"];
        $order_com2 = $row["order_com2"];
        $order_text2 = $row["order_text2"];
        $order_com3 = $row["order_com3"];
        $order_text3 = $row["order_text3"];
        $order_com4 = $row["order_com4"];
        $order_text4 = $row["order_text4"];
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
        
        $order_date1 = $row["order_date1"];
        $order_date2 = $row["order_date2"];
        $order_date3 = $row["order_date3"];
        $order_date4 = $row["order_date4"];
        $order_input_date1 = $row["order_input_date1"];
        $order_input_date2 = $row["order_input_date2"];
        $order_input_date3 = $row["order_input_date3"];
        $order_input_date4 = $row["order_input_date4"];
        
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
    error_log("OEM 상세 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}
?>

<div id="container">
    <div class="d-flex mb-1 justify-content-center">
        <div id="estimate_text2" style="width:840px;height:650px;font-size:14px;">
            
            <div class="sero1" style="width:400px;">
                <h3>서한 컴퍼니 발주</h3>
            </div>
            
            <input type="hidden" id="first_writer" name="first_writer" value="<?= htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" id="update_log" name="update_log" value="<?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?>">
            
            <?php
            $list_params = http_build_query([
                'page' => $page,
                'search' => $search,
                'find' => $find,
                'list' => 1,
                'process' => $process,
                'yearcheckbox' => $yearcheckbox,
                'year' => $check,
                'check' => $check,
                'check_draw' => $check_draw,
                'output_check' => $output_check,
                'team_check' => $team_check,
                'measure_check' => $measure_check,
                'plan_output_check' => $plan_output_check,
                'scale' => $scale,
                'cursort' => $cursort,
                'sortof' => $sortof,
                'stable' => 1
            ], '', '&', PHP_QUERY_RFC3986);
            
            $edit_params = http_build_query([
                'mode' => 'modify',
                'num' => $num,
                'page' => $page,
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
            
            $copy_params = http_build_query([
                'mode' => 'copy',
                'num' => $num,
                'page' => $page,
                'scale' => $scale,
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
                'stable' => 1
            ], '', '&', PHP_QUERY_RFC3986);
            ?>
            
            <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='list.php?<?= htmlspecialchars($list_params, ENT_QUOTES, 'UTF-8') ?>'">목록</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='write_form.php?<?= htmlspecialchars($edit_params, ENT_QUOTES, 'UTF-8') ?>'">수정</button>
            
            <?php if ($level < 5) { ?>
                <button type="button" class="btn btn-secondary btn-sm" onclick="del('delete.php?num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>')">삭제</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='write_form.php'">글쓰기</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='copy_data.php?<?= htmlspecialchars($copy_params, ENT_QUOTES, 'UTF-8') ?>'">데이터복사</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openTransform()">발주서인쇄</button>
            <?php } ?>
            
            <div class="clear"></div>
            <br>
            
            <div class="sero1">현장명 :</div>
            <div class="sero2">
                <input type="text" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장명" required disabled>
            </div>
            <div class="clear"></div>
            
            <div class="sero1">현장주소 :</div>
            <div class="sero2">
                <input type="text" name="address" value="<?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장주소" disabled>
            </div>
            <div class="clear"></div>
            
            <div class="sero1">매입처 :</div>
            <div class="sero2">
                <input type="text" id="firstord" name="firstord" value="<?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="매입처" disabled>
            </div>
            <div class="sero1" style="text-align:right;">담당 :</div>
            <div class="sero2">
                <input type="text" id="firstordman" name="firstordman" value="<?= htmlspecialchars($firstordman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="원청담당" disabled>
            </div>
            <div class="sero1">연락처 :</div>
            <div class="sero2">
                <input type="text" id="firstordmantel" name="firstordmantel" value="<?= htmlspecialchars($firstordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호" disabled>
            </div>
            <div class="clear"></div>
            
            <div class="sero1">발주처 :</div>
            <div class="sero2">
                <input type="text" id="secondord" name="secondord" value="<?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="발주처" disabled>
            </div>
            <div class="sero1" style="text-align:right;">담당 :</div>
            <div class="sero2">
                <input type="text" id="secondordman" name="secondordman" value="<?= htmlspecialchars($secondordman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="발주처 담당자" disabled>
            </div>
            <div class="sero1">연락처 :</div>
            <div class="sero2">
                <input type="text" id="secondordmantel" name="secondordmantel" value="<?= htmlspecialchars($secondordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호" disabled>
            </div>
            <div class="clear"></div>
            
            <div class="sero1">담당자 :</div>
            <div class="sero2">
                <input type="text" name="chargedman" id="chargedman" value="<?= htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="현장담당자" disabled>
            </div>
            <div class="sero1" style="text-align:right;">연락처 :</div>
            <div class="sero2">
                <input type="text" name="chargedmantel" id="chargedmantel" value="<?= htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="현장담당전화" disabled>
            </div>
            <div class="clear"></div>
            <div class="space"></div>
            <div class="clear"></div>
            
            <span>접수일 :
                <input type="date" name="orderday" id="orderday" value="<?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?>" disabled>
                발주일 :
                <input type="date" name="startday" id="startday" value="<?= htmlspecialchars($startday, ENT_QUOTES, 'UTF-8') ?>" disabled>
            </span>
            <span style="color:red;">
                납기일 :
                <input type="date" name="deadline" id="deadline" value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>" disabled>
            </span>
            <span style="color:blue;">
                출고일 :
                <input type="date" name="workday" id="workday" value="<?= htmlspecialchars($workday, ENT_QUOTES, 'UTF-8') ?>" disabled>
            </span>
            <div class="clear"></div>
            <div class="space"></div>
            <div class="clear"></div>
            
            <div class="sero1">타입(Type)</div>
            <div class="sero4">
                <input type="text" name="type1" value="<?= htmlspecialchars($type1, ENT_QUOTES, 'UTF-8') ?>" size="5" placeholder="타입" disabled>
            </div>
            <div class="sero1">인승</div>
            <div class="sero4">
                <input type="text" name="inseung1" value="<?= htmlspecialchars($inseung1, ENT_QUOTES, 'UTF-8') ?>" size="5" placeholder="인승" disabled>
            </div>
            <div class="sero1">car insize</div>
            <div class="sero4">
                <input type="text" name="car_insize1" value="<?= htmlspecialchars($car_insize1, ENT_QUOTES, 'UTF-8') ?>" size="8" placeholder="Car insize" disabled>
            </div>
            <div class="clear"></div>
            
            <div class="sero1">L/C수량:</div>
            <div class="sero4">
                <input type="text" name="lc_su" value="<?= htmlspecialchars($lc_su, ENT_QUOTES, 'UTF-8') ?>" size="2" placeholder="수량" disabled>
            </div>
            <div class="sero1">기타 수량:</div>
            <div class="sero4">
                <input type="text" name="etc_su" value="<?= htmlspecialchars($etc_su, ENT_QUOTES, 'UTF-8') ?>" size="2" placeholder="수량" disabled>
            </div>
            <div class="clear"></div>
            <div class="space"></div>
            <div class="clear"></div>
            
            <div id="delivery_col">운송비 :
                <input type="text" name="delivery" value="<?= htmlspecialchars($delivery, ENT_QUOTES, 'UTF-8') ?>" placeholder="운반비내역" disabled>
                <span style="color:red;">운임(있을시 기록) :</span>
                <input type="text" name="delipay" value="<?= htmlspecialchars($delipay, ENT_QUOTES, 'UTF-8') ?>" placeholder="운임금액" disabled>
            </div>
            
            <div class="clear"></div>
            <div class="space"></div>
            <div class="clear"></div>
            
            <div class="box1">
                <div class="box2 box_col1" style="width:500px;">
                    <div class="sero6" style="width:200px;">비고(마구리/도장)</div>
                    <div class="clear"></div>
                    <div class="sero5">
                        <textarea rows="3" cols="65" name="memo" placeholder="비고1" disabled><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <br><br><br><br><br>
                    <div class="clear"></div>
                    
                    <div class="sero1" style="font-size:18px;color:red;width:400;">청구일자 :
                        <input type="date" name="demand" id="demand" value="<?= htmlspecialchars($demand, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="청구일, 계산서발행" disabled>
                    </div>
                </div>
                
                <div class="box1 box_col2">
                    <?php echo "최초등록자 : " . htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8'); ?>
                    <br><br>
                    <?php echo "수정기록 : "; ?>
                    <br>
                    <textarea rows="3" cols="32" name="display_log" disabled><?= htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    $(document).ready(function() {
        // 조회화면 구현시 적용 - input을 모두 disabled 하기
        $("div *").find("input,textarea").prop("disabled", true);
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
        }
    };
    
    /**
     * 아래로 복사
     */
    window.copy_below = function() {
        // 필요시 구현
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
     * 삭제 확인
     */
    window.del = function(href) {
        var sessionLevel = document.getElementById('session_level');
        var level = sessionLevel ? Number(sessionLevel.value) : 999;
        
        if (level > 2) {
            alert("삭제하려면 관리자에게 문의해 주세요");
        } else {
            if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                document.location.href = href;
            }
        }
    };
    
    /**
     * 출력 리스트 표시
     */
    window.displayoutputlist = function() {
        // 필요시 구현
    };
    
    /**
     * 발주서 인쇄 창 열기
     */
    window.openTransform = function() {
        var num = <?php echo json_encode($num, JSON_UNESCAPED_UNICODE); ?>;
        var upnum = <?php echo json_encode($upnum, JSON_UNESCAPED_UNICODE); ?>;
        var page = <?php echo json_encode($page, JSON_UNESCAPED_UNICODE); ?>;
        var search = <?php echo json_encode($search, JSON_UNESCAPED_UNICODE); ?>;
        var find = <?php echo json_encode($find, JSON_UNESCAPED_UNICODE); ?>;
        var process = <?php echo json_encode($process, JSON_UNESCAPED_UNICODE); ?>;
        var sort = <?php echo json_encode($sort, JSON_UNESCAPED_UNICODE); ?>;
        var m2 = <?php echo json_encode($m2, JSON_UNESCAPED_UNICODE); ?>;
        
        var url = 'transform.php?num=' + encodeURIComponent(num) + '&upnum=' + encodeURIComponent(upnum) + '&page=' + encodeURIComponent(page) + '&search=' + encodeURIComponent(search) + '&find=' + encodeURIComponent(find) + '&list=1&process=' + encodeURIComponent(process) + '&sort=' + encodeURIComponent(sort) + '&m2=' + encodeURIComponent(m2);
        window.open(url, '출고증 인쇄', 'left=50,top=50,scrollbars=yes,toolbars=no,width=1200,height=800');
    };
    
})();
</script>

</body>
</html>
