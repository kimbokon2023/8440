<?php
/**
 * 천장/LC 포장 제작 List 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
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

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$WebSite = $protocol . "://" . $host;

// 세션 변수 안전하게 가져오기
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 999;
$id_name = isset($_SESSION["name"]) ? $_SESSION["name"] : '';

// 권한 확인
if (!isset($_SESSION["level"]) || $level > 7) {
    sleep(2);
    header("Location: {$WebSite}/login/logout.php");
    exit;
}

// 요청 변수 초기화
$search = getRequestValue("search", '');
$list = getRequestValue("list", 0);
$page = getRequestValue("page", 1);
$scale = getRequestValue("scale", 50);
$mode = getRequestValue("mode", '');
$cursort = getRequestValue("cursort", '8');
$find = getRequestValue("find", '');
$year = getRequestValue("year", '');
$process = getRequestValue("process", '');
$asprocess = getRequestValue("asprocess", '');
$yearcheckbox = getRequestValue("yearcheckbox", '');
$separate_date = getRequestValue("separate_date", '');
$alerts = getRequestValue("alerts", '');

// 기간 설정
$fromdate = getRequestValue("fromdate", "2010-01-01");
$todate = getRequestValue("todate", '');

if (empty($fromdate)) {
    $fromdate = "2010-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = date("Y-m-d", strtotime($todate . '+1 days'));
} else {
    $Transtodate = date("Y-m-d", strtotime($todate));
}

// 페이지 설정
$page_scale = 10;
$first_num = ($page - 1) * $scale;

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 현재 날짜
$nowday = date("Y-m-d");

// SQL 쿼리 생성
$process = "전체";
$SettingDate = "orderday";

$sql = "";
$sqlcon = "";
$params = [];
$params_con = [];

// 검색 모드
if ($mode == "search") {
    if (empty($search)) {
        $sql = "SELECT * FROM mirae8440.ceiling 
                WHERE {$SettingDate} BETWEEN ? AND ? 
                ORDER BY {$SettingDate} DESC, num DESC 
                LIMIT ?, ?";
        $sqlcon = "SELECT * FROM mirae8440.ceiling 
                   WHERE {$SettingDate} BETWEEN ? AND ? 
                   ORDER BY {$SettingDate} DESC, num DESC";
        $params = [$fromdate, $Transtodate, $first_num, $scale];
        $params_con = [$fromdate, $Transtodate];
    } else {
        $sql = "SELECT * FROM mirae8440.ceiling 
                WHERE (orderday LIKE ? OR type LIKE ? OR inseung LIKE ? 
                       OR deadline LIKE ? OR workplacename LIKE ?) 
                ORDER BY {$SettingDate} DESC, num DESC 
                LIMIT ?, ?";
        $sqlcon = "SELECT * FROM mirae8440.ceiling 
                   WHERE (orderday LIKE ? OR type LIKE ? OR inseung LIKE ? 
                          OR deadline LIKE ? OR workplacename LIKE ?) 
                   ORDER BY {$SettingDate} DESC, num DESC";
        $searchParam = "%{$search}%";
        $params = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $first_num, $scale];
        $params_con = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
    }
} else {
    $sql = "SELECT * FROM mirae8440.ceiling 
            WHERE {$SettingDate} BETWEEN ? AND ? 
            ORDER BY {$SettingDate} DESC, num DESC 
            LIMIT ?, ?";
    $sqlcon = "SELECT * FROM mirae8440.ceiling 
               WHERE {$SettingDate} BETWEEN ? AND ? 
               ORDER BY {$SettingDate} DESC, num DESC";
    $params = [$fromdate, $Transtodate, $first_num, $scale];
    $params_con = [$fromdate, $Transtodate];
}

// cursort 조건 처리
if (empty($search)) {
    switch ($cursort) {
        case 1:
        case 0:
        case '':
            // 납기일 기준
            $sql = "SELECT * FROM mirae8440.ceiling 
                    WHERE (deadline = '0000-00-00' OR DATE(deadline) >= DATE(NOW())) 
                        AND ((bon_su > 0) OR (lc_su > 0 AND type NOT IN ('011', '012', '013D', '025', '017', '014'))) 
                    ORDER BY deadline ASC, num DESC";
            $sqlcon = $sql;
            $params = [];
            $params_con = [];
            break;
            
        case 2:
            // 전체
            $sql = "SELECT * FROM mirae8440.ceiling ORDER BY orderday DESC LIMIT ?, ?";
            $sqlcon = "SELECT * FROM mirae8440.ceiling ORDER BY orderday DESC";
            $params = [$first_num, $scale];
            $params_con = [];
            break;
            
        case 3:
            // 레이져
            $sql = "SELECT * FROM mirae8440.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND ((eunsung_laser_date = '0000-00-00' AND bon_su > 0) 
                             OR (lclaser_date = '0000-00-00' AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014'))) 
                    ORDER BY deadline ASC, num DESC";
            $sqlcon = $sql;
            $params = [];
            $params_con = [];
            break;
            
        case 4:
            // 절곡
            $sql = "SELECT * FROM mirae8440.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND ((mainbending_date = '0000-00-00' AND bon_su > 0) 
                             OR (lcbending_date = '0000-00-00' AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014'))) 
                    ORDER BY deadline ASC, num DESC";
            $sqlcon = $sql;
            $params = [];
            $params_con = [];
            break;
            
        case 5:
            // 제관
            $sql = "SELECT * FROM mirae8440.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND ((mainwelding_date = '0000-00-00' AND bon_su > 0) 
                             OR (lcwelding_date = '0000-00-00' AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014'))) 
                    ORDER BY deadline ASC, num DESC";
            $sqlcon = $sql;
            $params = [];
            $params_con = [];
            break;
            
        case 6:
            // 도장
            $sql = "SELECT * FROM mirae8440.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND ((mainpainting_date = '0000-00-00' AND bon_su > 0) 
                             OR (lcpainting_date = '0000-00-00' AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014'))) 
                    ORDER BY deadline ASC, num DESC";
            $sqlcon = $sql;
            $params = [];
            $params_con = [];
            break;
            
        case 7:
            // 조립
            $sql = "SELECT * FROM mirae8440.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND ((mainassembly_date = '0000-00-00' AND bon_su > 0) 
                             OR (lcassembly_date = '0000-00-00' AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014'))) 
                    ORDER BY deadline ASC, num DESC";
            $sqlcon = $sql;
            $params = [];
            $params_con = [];
            break;
            
        case 8:
            // 미제작
            $sql = "SELECT * FROM mirae8440.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND ((mainassembly_date = '0000-00-00' AND bon_su > 0) 
                             OR (lcassembly_date = '0000-00-00' AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014'))) 
                    ORDER BY deadline ASC, num DESC";
            $sqlcon = $sql;
            $params = [];
            $params_con = [];
            break;
    }
}

// 쿼리 실행
try {
    // 전체 레코드 수
    if (!empty($params_con)) {
        $allstmh = $pdo->prepare($sqlcon);
        foreach ($params_con as $key => $value) {
            $allstmh->bindValue($key + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $allstmh->execute();
    } else {
        $allstmh = $pdo->query($sqlcon);
    }
    $total_row = $allstmh->rowCount();
    
    // 페이지별 레코드
    if (!empty($params)) {
        $stmh = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmh->bindValue($key + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmh->execute();
    } else {
        $stmh = $pdo->query($sql);
    }
    
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
    
} catch (PDOException $ex) {
    error_log("DB 조회 오류: " . $ex->getMessage());
    echo "오류: 데이터를 불러오는 중 문제가 발생했습니다.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 천장/LC 포장 제작 List</title>
    
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
        .c-table { 
            border-collapse: collapse; 
            text-align: center;
        }
        .c-table th { 
            background: gray; 
            height: 40px; 
            color: white; 
            border: 1px solid black;
        }
        .c-table td { 
            border: 1px solid grey; 
        }
        #panel, #flip {
            padding: 5px;
            text-align: center;
            color: white;
            background-color: blue;
            border: solid 1px #c3c3c3;
        }
        #panel {
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
        
        <?php
        $formParams = http_build_query([
            'mode' => 'search',
            'find' => $find,
            'year' => $year,
            'search' => $search,
            'process' => $process,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'separate_date' => $separate_date,
            'scale' => 50
        ]);
        ?>
        
        <form name="board_form" id="board_form" method="post" action="packinglist.php?<?= $formParams ?>">
            <br>
            <button type="button" class="btn btn-danger btn-lg" onclick="location.href='./list.php';">이전화면</button>
            <div class="clear"></div>
            <br><br>
            
            <div class="row">
                <h4 class="display-4 text-left">
                    <span style="color:green;">&nbsp;&nbsp; 천장&L/C 포장 제작용 자료 List &nbsp;&nbsp;</span>
                    <button type="button" class="btn btn-Dark btn-lg" onclick="show_list(2);">전체</button>
                    <button type="button" class="btn btn-success btn-lg" onclick="show_list(1);">납품예정</button>
                    <button type="button" class="btn btn-danger btn-lg" onclick="show_list(8);">미제작</button>
                </h4>
            </div>
            <br>
            
            <div class="row">
                <h2 class="display-5 font-center text-center">
                    ▷ 총 <?= $total_row ?> 개의 자료 파일 &nbsp;&nbsp; <선택:
                    <?php
                    $sortLabels = [
                        1 => '납품예정 List',
                        2 => '발주된 전체 List',
                        3 => '레이져 미가공 List',
                        4 => '절곡 미가공 List',
                        5 => '제관 미가공 List',
                        6 => '미도장 List',
                        7 => '미조립 List',
                        8 => '미제작 List'
                    ];
                    echo htmlspecialchars($sortLabels[$cursort] ?? '', ENT_QUOTES, 'UTF-8');
                    ?>>
                </h2>
            </div>
            
            <div class="row">
                <div class="col">
                    <h2 class="display-5 font-center text-center">
                        <input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" size="12">
                        <input type="hidden" id="alerts" name="alerts" value="<?= htmlspecialchars($alerts, ENT_QUOTES, 'UTF-8') ?>" size="3">
                        <input type="hidden" id="cursort" name="cursort" value="<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>" size="3">
                        <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>" size="3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="process_list();">검색</button>
                    </h2>
                </div>
            </div>
            
            <section>
                <table class="c-table">
                    <div class="row">
                        <tr>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">접수일</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">납기일</h3></th></div>
                            <div class="col-3"><th><h3 class="display-5 font-center text-center">현장명</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">타입</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">인승</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">수량</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">본천장</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">L/C</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">기타</h3></th></div>
                            <div class="col-1"><th><h3 class="display-5 font-center text-center">공기청정기</h3></th></div>
                            <div class="col-2"><th><h3 class="display-5 font-center text-center">Car Insize</h3></th></div>
                            <div class="col-3"><th><h3 class="display-5 font-center text-center">비고</h3></th></div>
                        </tr>
                    </div>
                    
                    <div class="row"></div>
                    
                    <?php
                    $start_num = ($page <= 1) ? $total_row : $total_row - ($page - 1) * $scale;
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
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
                        $orderday = trans_date($row["orderday"] ?? '');
                        $measureday = trans_date($row["measureday"] ?? '');
                        $drawday = trans_date($row["drawday"] ?? '');
                        $deadline = trans_date($row["deadline"] ?? '');
                        $delicompany = $row["delicompany"] ?? '';
                        $delivery = $row["delivery"] ?? '';
                        $delipay = $row["delipay"] ?? '';
                        $workday = trans_date($row["workday"] ?? '');
                        $startday = trans_date($row["startday"] ?? '');
                        $testday = trans_date($row["testday"] ?? '');
                        $worker = $row["worker"] ?? '';
                        $endworkday = trans_date($row["endworkday"] ?? '');
                        $material1 = $row["material1"] ?? '';
                        $material2 = $row["material2"] ?? '';
                        $material3 = $row["material3"] ?? '';
                        $material4 = $row["material4"] ?? '';
                        $material5 = $row["material5"] ?? '';
                        $material6 = $row["material6"] ?? '';
                        $memo = $row["memo"] ?? '';
                        $regist_day = $row["regist_day"] ?? '';
                        $update_day = $row["update_day"] ?? '';
                        $demand = trans_date($row["demand"] ?? '');
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
                        $lc_draw = trans_date($row["lc_draw"] ?? '');
                        $lclaser_com = $row["lclaser_com"] ?? '';
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
                        $memo2 = $row["memo2"] ?? '';
                        
                        // 미선언 변수 초기화
                        $order_date1 = trans_date($row["order_date1"] ?? '');
                        $order_date2 = trans_date($row["order_date2"] ?? '');
                        $order_date3 = trans_date($row["order_date3"] ?? '');
                        $order_date4 = trans_date($row["order_date4"] ?? '');
                        $order_input_date1 = trans_date($row["order_input_date1"] ?? '');
                        $order_input_date2 = trans_date($row["order_input_date2"] ?? '');
                        $order_input_date3 = trans_date($row["order_input_date3"] ?? '');
                        $order_input_date4 = trans_date($row["order_input_date4"] ?? '');
                        
                        // 날짜 색상
                        $date_font = ($nowday == $orderday) ? "red" : "black";
                        $date1_font = ($nowday == $deadline) ? "blue" : "black";
                        
                        // 요일 추가
                        if (!empty($orderday)) {
                            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                            $orderday = mb_substr($orderday, 5, 5, "utf-8") . $week[date('w', strtotime($orderday))];
                        }
                        
                        if (!empty($deadline)) {
                            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                            $deadline = mb_substr($deadline, 5, 5, "utf-8") . $week[date('w', strtotime($deadline))];
                        }
                        
                        $workplacename = "(" . $secondord . ")" . $workplacename;
                        $memo = $memo . $memo2;
                        ?>
                        
                        <div class="row">
                            <tr>
                                <div class="col-1">
                                    <td>
                                        <h3 class="display-5 font-center text-center" style="color:<?= $date_font ?>;">
                                            <?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?>
                                        </h3>
                                    </td>
                                </div>
                                
                                <div class="col-1">
                                    <td>
                                        <h3 class="display-5 font-center text-center" style="color:<?= $date1_font ?>;">
                                            <?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>
                                        </h3>
                                    </td>
                                </div>
                                
                                <div class="col-3">
                                    <td>
                                        <h2 class="display-5">
                                            <?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>
                                        </h2>
                                    </td>
                                </div>
                                
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($inseung, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($su, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($bon_su, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($lc_su, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($etc_su, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($air_su, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($car_insize, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                                <div class="col-1"><td><h2 class="display-5"><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></h2></td></div>
                            </tr>
                        </div>
                        
                        <?php
                        $start_num--;
                    }
                    ?>
                </table>
            </section>
            
            <div id="vacancy" style="display:none"></div>
            <div class="row">&nbsp;</div>
            <div class="row">&nbsp;</div>
            
            <div id="page_button">
                <div class="row">
                    <div class="col">
                        <h5 class="display-3 font-center text-center">
                            <?php
                            $start_page = ($current_page - 1) * $page_scale + 1;
                            $end_page = $start_page + $page_scale - 1;
                            
                            if ($page != 1 && $page > $page_scale) {
                                $prev_page = $page - $page_scale;
                                if ($prev_page <= 0) $prev_page = 1;
                                
                                $prevParams = http_build_query([
                                    'page' => $prev_page,
                                    'mode' => 'search',
                                    'search' => $search,
                                    'find' => $find,
                                    'list' => 1,
                                    'process' => $process,
                                    'asprocess' => $asprocess,
                                    'yearcheckbox' => $yearcheckbox,
                                    'year' => $year,
                                    'cursort' => $cursort
                                ]);
                                
                                echo '<a href="list.php?' . $prevParams . '">◀</a> ';
                            }
                            
                            for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                                if ($page == $i) {
                                    echo '<font color=red><b>[' . $i . ']</b></font> ';
                                } else {
                                    $pageParams = http_build_query([
                                        'page' => $i,
                                        'mode' => 'search',
                                        'search' => $search,
                                        'find' => $find,
                                        'list' => 1,
                                        'process' => $process,
                                        'asprocess' => $asprocess,
                                        'yearcheckbox' => $yearcheckbox,
                                        'year' => $year,
                                        'cursort' => $cursort
                                    ]);
                                    
                                    echo '<a href="list.php?' . $pageParams . '">[' . $i . ']</a> ';
                                }
                            }
                            
                            if ($page < $total_page) {
                                $next_page = $page + $page_scale;
                                if ($next_page > $total_page) $next_page = $total_page;
                                
                                $nextParams = http_build_query([
                                    'page' => $next_page,
                                    'mode' => 'search',
                                    'search' => $search,
                                    'find' => $find,
                                    'list' => 1,
                                    'process' => $process,
                                    'asprocess' => $asprocess,
                                    'yearcheckbox' => $yearcheckbox,
                                    'year' => $year,
                                    'cursort' => $cursort
                                ]);
                                
                                echo '<a href="list.php?' . $nextParams . '">▶</a>';
                            }
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

<script type="text/javascript">
(function() {
    'use strict';
    
    $(function() {
        if (typeof $.fn.datepicker !== 'undefined') {
            $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd' });
        }
    });
    
    window.pre_year = function() {
        document.getElementById('search').value = null;
        var today = new Date();
        var yyyy = today.getFullYear() - 1;
        var frompreyear = yyyy + '-01-01';
        var topreyear = yyyy + '-12-31';
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.pre_month = function() {
        document.getElementById('search').value = null;
        var today = new Date();
        var mm = today.getMonth();
        var yyyy = today.getFullYear();
        
        if (mm < 1) {
            mm = 12;
            yyyy--;
        }
        
        mm = (mm < 10) ? '0' + mm : mm;
        var frompreyear = yyyy + '-' + mm + '-01';
        var topreyear = yyyy + '-' + mm + '-31';
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.this_year = function() {
        document.getElementById('search').value = null;
        var today = new Date();
        var yyyy = today.getFullYear();
        var frompreyear = yyyy + '-01-01';
        var topreyear = yyyy + '-12-31';
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.this_month = function() {
        document.getElementById('search').value = null;
        var today = new Date();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        mm = (mm < 10) ? '0' + mm : mm;
        var frompreyear = yyyy + '-' + mm + '-01';
        var topreyear = yyyy + '-' + mm + '-31';
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.From_tomorrow = function() {
        var today = new Date();
        var dd = today.getDate() + 1;
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-12-31';
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.Fromthis_today = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-12-31';
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.this_today = function() {
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-' + mm + '-' + dd;
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.this_tomorrow = function() {
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate() + 1;
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-' + mm + '-' + dd;
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    };
    
    window.process_list = function() {
        document.getElementById('board_form').submit();
    };
    
    window.show_list = function(insu) {
        document.getElementById('search').value = null;
        document.getElementById('cursort').value = insu;
        document.getElementById('board_form').submit();
    };
    
})();

<?php if ($mode == "" && $fromdate == null): ?>
    this_year();
<?php endif; ?>
</script>
