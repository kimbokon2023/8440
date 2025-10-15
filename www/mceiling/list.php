<?php
/**
 * 천장/L/C 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 날짜 처리 함수
function processDate($date, $condition, $types, $type) {
    if (substr($date, 0, 2) == "20") {
        return mb_substr($date, 5, 5, "utf-8");
    } elseif ($condition < 1 || in_array($type, $types)) {
        return "X";
    }
    return '';
}

// 값이 'X'인지 아닌지 판단하는 함수
function is_valid_etc_value($val) {
    return isset($val) && $val !== '' && $val !== '0000-00-00' && strtolower($val) !== 'null' && $val !== 'X';
}

// 권한 확인
$level = $_SESSION["level"] ?? 10;
if (!isset($_SESSION["level"]) || $level > 6) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정
$base_url = getBaseUrl();

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["user_name"] ?? '';

// 요청 변수 안전하게 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$cursort = $_REQUEST["cursort"] ?? ($_POST["cursort"] ?? '8');
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$find = $_REQUEST["find"] ?? '';
$alerts = $_REQUEST["alerts"] ?? '';

// 현재 날짜
$now = date("Y-m-d", time());
$nowday = date("Y-m-d");

// 기간 설정
if (empty($fromdate)) {
    $fromdate = date("Y-m-d", time());
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = date("Y-m-d", strtotime($todate . '+1 days'));
} else {
    $Transtodate = date("Y-m-d", strtotime($todate));
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 구성
$process = "전체";
$SettingDate = "deadline";

$sql = '';
$params = [];

// 검색 모드
if ($mode == "search" && !empty($search)) {
    $sql = "SELECT * FROM {$DB}.ceiling 
            WHERE (orderday LIKE ? OR type LIKE ? OR inseung LIKE ? 
                   OR deadline LIKE ? OR workplacename LIKE ?) 
                AND ({$SettingDate} BETWEEN DATE(?) AND DATE(?)) 
            ORDER BY {$SettingDate} ASC, num DESC";
    $searchParam = "%{$search}%";
    $params = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $fromdate, $Transtodate];
}

// cursort 조건 처리
if (empty($search)) {
    switch ($cursort) {
        case 1:
        case 0:
        case '':
            // 납기일 기준
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE (DATE(deadline) >= DATE(NOW())) 
                        AND ((bon_su > 0 OR etc_su > 0 OR lc_su > 0) 
                        AND (type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))) 
                    ORDER BY deadline ASC, num DESC";
            break;
            
        case 2:
            // 전체
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE {$SettingDate} BETWEEN DATE(?) AND DATE(?) 
                    ORDER BY {$SettingDate} ASC, num DESC";
            $params = [$fromdate, $Transtodate];
            break;
            
        case 3:
            // 레이져
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND (etc_su > 0 
                             OR ((eunsung_laser_date IS NULL OR eunsung_laser_date = '0000-00-00') AND bon_su > 0) 
                             OR ((lclaser_date IS NULL OR lclaser_date = '0000-00-00') AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))) 
                    ORDER BY deadline ASC, num DESC";
            break;
            
        case 4:
            // 절곡
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND (etc_su > 0 
                             OR ((mainbending_date IS NULL OR mainbending_date = '0000-00-00') AND bon_su > 0) 
                             OR ((lcbending_date IS NULL OR lcbending_date = '0000-00-00') AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))) 
                    ORDER BY deadline ASC, num DESC";
            break;
            
        case 5:
            // 제관
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND (etc_su > 0 
                             OR ((mainwelding_date IS NULL OR mainwelding_date = '0000-00-00') AND bon_su > 0) 
                             OR ((lcwelding_date IS NULL OR lcwelding_date = '0000-00-00') AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))) 
                    ORDER BY deadline ASC, num DESC";
            break;
            
        case 6:
            // 도장
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND (etc_su > 0 
                             OR ((mainpainting_date IS NULL OR mainpainting_date = '0000-00-00') AND bon_su > 0) 
                             OR ((lcpainting_date IS NULL OR lcpainting_date = '0000-00-00') AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))) 
                    ORDER BY deadline ASC, num DESC";
            break;
            
        case 7:
            // 조립
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND (etc_su > 0 
                             OR ((mainassembly_date IS NULL OR mainassembly_date = '0000-00-00') AND bon_su > 0) 
                             OR ((lcassembly_date IS NULL OR lcassembly_date = '0000-00-00') AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))) 
                    ORDER BY deadline ASC, num DESC";
            break;
            
        case 8:
            // 미제작
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE DATE(deadline) >= DATE(NOW()) 
                        AND (((mainassembly_date IS NULL OR mainassembly_date = '0000-00-00') AND bon_su > 0) 
                             OR ((lcassembly_date IS NULL OR lcassembly_date = '0000-00-00') AND lc_su > 0 
                                 AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038')) 
                             OR ((etcassembly_date IS NULL OR etcassembly_date = '0000-00-00') AND etc_su > 0)) 
                    ORDER BY deadline ASC, num DESC";
            break;
            
        case 9:
            // 7일 전부터 2달 후까지
            $sql = "SELECT * FROM {$DB}.ceiling 
                    WHERE DATE(deadline) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() + INTERVAL 2 MONTH 
                    ORDER BY deadline ASC, num DESC";
            break;
    }
}

$sqlMain = $sql;

include includePath('load_header.php');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/dashboard-style.css">

<title>미래기업 공정 관리</title>
</head>
<style>
    .table td, .table th {
        vertical-align: middle;
        font-size: 14px;
    }
    
    .outsourcing-tooltip {
        position: relative;
        display: inline-block;
        cursor: help;
    }
    
    .outsourcing-tooltip .tooltip-content {
        visibility: hidden;
        width: 320px;
        max-width: 90vw;
        background: linear-gradient(135deg, rgb(86, 193, 219) 0%, rgb(35, 173, 197) 100%);
        color: white;
        text-align: left;
        border-radius: 12px;
        padding: 15px;
        position: absolute;
        z-index: 1000;
        bottom: 125%;
        left: 50%;
        margin-left: -160px;
        opacity: 0;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        font-size: 13px;
        line-height: 1.5;
        transform: translateY(10px);
    }
    
    .outsourcing-tooltip .tooltip-content::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -8px;
        border-width: 8px;
        border-style: solid;
        border-color: rgb(35, 173, 197) transparent transparent transparent;
    }
    
    .outsourcing-tooltip:hover .tooltip-content {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }
    
    .tooltip-title {
        font-weight: 700;
        margin-bottom: 10px;
        color: white;
        border-bottom: 2px solid rgba(255, 255, 255, 0.4);
        padding-bottom: 8px;
        font-size: 14px;
    }
    
    .tooltip-memo {
        color: rgba(255, 255, 255, 0.95);
        white-space: pre-wrap;
        word-wrap: break-word;
        max-height: 200px;
        overflow-y: auto;
    }
    
    @media (max-width: 768px) {
        .outsourcing-tooltip .tooltip-content {
            width: 280px;
            margin-left: -140px;
            font-size: 12px;
        }
    }
</style>

<body>
<form id="board_form" name="board_form" method="post" action="list.php?mode=search">
    <div class="container-fluid">
        <div class="row d-flex">
            <?php include includePath('ceiling/chart_page.php'); ?>
            
            <div class="col-sm-8 mt-1 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mt-5 mb-5 fs-5 justify-content-center gap-2">
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-light px-4 py-2" 
                                    onclick="location.href='./etclist.php';">(판넬,발보호판)</button>
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-info px-4 py-2" 
                                    onclick="location.href='../paint/index.php';">도장발주</button>
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-success px-4 py-2" 
                                    onclick="location.href='./packinglist.php';">포장상자</button>
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-outline-dark px-4 py-2 me-4" 
                                    onclick="popupCenter('../ceiling/list_part_table.php?menu=no','주요부품표',1000,950);">주요부품</button>
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-dark px-4 py-2" 
                                    onclick="self.close();">&times; 닫기</button>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3 align-items-center">
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-light px-4 py-2" 
                                    onclick="show_list(2);">전체</button>
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-info px-4 py-2" 
                                    onclick="show_list(9);">7일전</button>
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-danger px-4 py-2" 
                                    onclick="show_list(8);">미제작</button>
                            <div class="inputWrap me-1">
                                <input type="text" name="search" id="search" 
                                       value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" 
                                       class="form-control px-3 py-1" 
                                       onkeydown="SearchEnter(event);" 
                                       style="width:200px;" 
                                       autocomplete="off">
                                <button class="btnClear"></button>
                            </div>
                            <input type="hidden" id="alerts" name="alerts" value="<?php echo htmlspecialchars($alerts, ENT_QUOTES, 'UTF-8'); ?>" size="3">
                            <input type="hidden" id="cursort" name="cursort" value="<?php echo htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8'); ?>" size="3">
                            <button type="button" class="modern-management-card modern-dashboard-header btn btn-dark btn-sm px-3 py-2" 
                                    onclick="process_list();">
                                <i class="bi bi-search"></i> 검색
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row d-flex mt-3 mb-2 justify-content-center">
            <table class="table table-hover" id="myTable">
                <thead class="table-primary">
                    <tr>
                        <th class="col text-center" style="width:100px;">납기일</th>
                        <th class="col text-center" style="width:100px;">inside</th>
                        <th class="col text-center text-success" style="width:60px;">박스<br>포장</th>
                        <th class="col text-center" style="width:350px;">[발주처]현장명</th>
                        <th class="col text-center" style="width:120px;">Type</th>
                        <th class="col text-center" style="width:80px;">결합수</th>
                        <th class="col text-center" style="width:150px;">비고</th>
                        <th class="col text-center" style="width:200px;">설계(본/LC/기타)</th>
                        <th class="col text-center text-success" style="width:80px;">외주</th>
                        <th class="text-center text-white bg-primary" style="width:80px;">본LB</th>
                        <th class="text-center text-white bg-primary" style="width:80px;">제관</th>
                        <th class="text-center text-white bg-primary" style="width:80px;">도장</th>
                        <th class="text-center text-white bg-primary" style="width:80px;">조립</th>
                        <th class="text-center text-white bg-success" style="width:100px;">LC_LB<br>기타_LB</th>
                        <th class="text-center text-white bg-success" style="width:80px;">제관<br>절곡</th>
                        <th class="text-center text-white bg-success" style="width:80px;">도장<br>제관</th>
                        <th class="text-center text-white bg-success" style="width:80px;">결선<br>도장</th>
                        <th class="text-center text-white bg-success" style="width:80px;">포장<br>조립</th>
                        <th class="col text-center text-white bg-warning" style="width:50px;"><i class="bi bi-images"></i></th>
                    </tr>
                </thead>
                <tbody>
<?php
try {
    // Prepared Statement 실행
    if (!empty($params)) {
        $stmh = $pdo->prepare($sqlMain);
        foreach ($params as $key => $value) {
            $stmh->bindValue($key + 1, $value, PDO::PARAM_STR);
        }
        $stmh->execute();
    } else {
        $stmh = $pdo->query($sqlMain);
    }
    
    $total_row = $stmh->rowCount();
    $start_num = $total_row;
    
    $titlemsg = '';
    $sortLabels = [
        1 => '납품예정 List',
        2 => '발주된 전체 List',
        3 => '레이져 미가공 List',
        4 => '절곡 미가공 List',
        5 => '제관 미가공 List',
        6 => '미도장 List',
        7 => '미조립 List',
        8 => '미제작 List',
        9 => '7일전 List'
    ];
    $titlemsg = $sortLabels[$cursort] ?? '';
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // _row.php에서 변수를 가져옴
        include includePath('ceiling/_row.php');
        
        // 날짜 색상
        $date_font = ($nowday == $orderday) ? "red" : "black";
        $date_font1 = ($nowday == $workday) ? "blue" : "black";
        
        // 날짜 변환
        $workday = trans_date($workday ?? '');
        $startday = trans_date($startday ?? '');
        $demand = trans_date($demand ?? '');
        $orderday = trans_date($orderday ?? '');
        $deadline = trans_date($deadline ?? '');
        $testday = trans_date($testday ?? '');
        $lc_draw = trans_date($lc_draw ?? '');
        $lclaser_date = trans_date($lclaser_date ?? '');
        $lcbending_date = trans_date($lcbending_date ?? '');
        $lcwelding_date = trans_date($lcwelding_date ?? '');
        $lcpainting_date = trans_date($lcpainting_date ?? '');
        $lcassembly_date = trans_date($lcassembly_date ?? '');
        $main_draw = trans_date($main_draw ?? '');
        $eunsung_make_date = trans_date($eunsung_make_date ?? '');
        $eunsung_laser_date = trans_date($eunsung_laser_date ?? '');
        $mainbending_date = trans_date($mainbending_date ?? '');
        $mainwelding_date = trans_date($mainwelding_date ?? '');
        $mainpainting_date = trans_date($mainpainting_date ?? '');
        $mainassembly_date = trans_date($mainassembly_date ?? '');
        $etclaser_date = trans_date($etclaser_date ?? '');
        $etcbending_date = trans_date($etcbending_date ?? '');
        $etcwelding_date = trans_date($etcwelding_date ?? '');
        $etcpainting_date = trans_date($etcpainting_date ?? '');
        $etcassembly_date = trans_date($etcassembly_date ?? '');
        
        // 본천장 데이터 처리
        if ((int)$bon_su > 0) {
            $eunsung_laser_date = mb_substr($eunsung_laser_date, 5, 5, "utf-8");
            $mainbending_date = mb_substr($mainbending_date, 5, 5, "utf-8");
            $mainwelding_date = mb_substr($mainwelding_date, 5, 5, "utf-8");
            $mainpainting_date = mb_substr($mainpainting_date, 5, 5, "utf-8");
            $mainassembly_date = mb_substr($mainassembly_date, 5, 5, "utf-8");
        } else {
            $eunsung_laser_date = "X";
            $mainbending_date = "X";
            $mainwelding_date = "X";
            $mainpainting_date = "X";
            $mainassembly_date = "X";
        }
        
        // L/C 데이터 처리
        if ((int)$lc_su > 0 && !in_array($type, ['011', '012', '013D', '025', '017', '014', '037', '038'])) {
            $lclaser_date = mb_substr($lclaser_date, 5, 5, "utf-8");
            $lcbending_date = mb_substr($lcbending_date, 5, 5, "utf-8");
            $lcwelding_date = mb_substr($lcwelding_date, 5, 5, "utf-8");
            $lcpainting_date = mb_substr($lcpainting_date, 5, 5, "utf-8");
            $lcassembly_date = mb_substr($lcassembly_date, 5, 5, "utf-8");
        } else {
            $lclaser_date = "X";
            $lcbending_date = "X";
            $lcwelding_date = "X";
            $lcpainting_date = "X";
            $lcassembly_date = "X";
        }
        
        // 기타 데이터 처리
        if ((int)$etc_su > 0) {
            $etclaser_date = mb_substr($etclaser_date, 5, 5, "utf-8");
            $etcwelding_date = mb_substr($etcwelding_date, 5, 5, "utf-8");
            $etcpainting_date = mb_substr($etcpainting_date, 5, 5, "utf-8");
            $etcassembly_date = mb_substr($etcassembly_date, 5, 5, "utf-8");
            $etcbending_date = mb_substr($etcbending_date, 5, 5, "utf-8");
        } else {
            $etclaser_date = "X";
            $etcwelding_date = "X";
            $etcpainting_date = "X";
            $etcassembly_date = "X";
            $etcbending_date = "X";
        }
        
        // 요일 추가
        $orderday_display = '';
        if (!empty($orderday)) {
            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
            $orderday_display = mb_substr($orderday, 5, 5, "utf-8") . $week[date('w', strtotime($orderday))];
        }
        
        $deadlineStr = '';
        if (!empty($deadline)) {
            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
            $deadlineStr = mb_substr($deadline, 5, 5, "utf-8") . $week[date('w', strtotime($deadline))];
        }
        
        $workplacename = "[" . $secondord . "]" . $workplacename;
        
        // 사진 등록 여부 확인
        $sqltmp = "SELECT * FROM {$DB}.picuploads WHERE item = 'ceilingwrap' AND parentnum = ?";
        $tmpmsg = "";
        try {
            $stmhtmp = $pdo->prepare($sqltmp);
            $stmhtmp->execute([$num]);
            
            if ($stmhtmp->rowCount() > 0) {
                $tmpmsg = '<i class="bi bi-images"></i>';
            }
        } catch (PDOException $ex) {
            error_log("사진 조회 오류 (num: {$num}): " . $ex->getMessage());
        }
        
        // 포장 문자 제거
        $boxwrap = str_replace('포장', '', $boxwrap ?? '');
        
        // 설계처리여부 판단
        $typesForX = ['011', '012', '013D', '025', '017', '014', '037', '038'];
        
        $main_draw_arr = processDate($main_draw, $bon_su ?? 0, [], '');
        $lc_draw_arr = processDate($lc_draw, $lc_su ?? 0, $typesForX, $type ?? '');
        $etc_draw_arr = processDate($etc_draw ?? '', $etc_su ?? 0, [], '');
        
        // 디스플레이 값
        $main_draw_display = empty($main_draw_arr) ? '<span class="badge bg-warning">설NO</span>' : htmlspecialchars($main_draw_arr, ENT_QUOTES, 'UTF-8');
        $lc_draw_display = empty($lc_draw_arr) ? '<span class="badge bg-warning">설NO</span>' : htmlspecialchars($lc_draw_arr, ENT_QUOTES, 'UTF-8');
        $etc_draw_display = empty($etc_draw_arr) ? '<span class="badge bg-warning">설NO</span>' : htmlspecialchars($etc_draw_arr, ENT_QUOTES, 'UTF-8');
        
        // 본천장 디스플레이
        $mainlaser_display = empty($eunsung_laser_date) || $eunsung_laser_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($eunsung_laser_date, ENT_QUOTES, 'UTF-8');
        $mainwelding_display = empty($mainwelding_date) || $mainwelding_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($mainwelding_date, ENT_QUOTES, 'UTF-8');
        $mainpainting_display = empty($mainpainting_date) || $mainpainting_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($mainpainting_date, ENT_QUOTES, 'UTF-8');
        $mainassembly_display = empty($mainassembly_date) || $mainassembly_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($mainassembly_date, ENT_QUOTES, 'UTF-8');
        
        // LC 디스플레이
        $lclaser_display = empty($lclaser_date) || $lclaser_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($lclaser_date, ENT_QUOTES, 'UTF-8');
        $lcwelding_display = empty($lcwelding_date) || $lcwelding_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($lcwelding_date, ENT_QUOTES, 'UTF-8');
        $lcpainting_display = empty($lcpainting_date) || $lcpainting_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($lcpainting_date, ENT_QUOTES, 'UTF-8');
        $lccabledone_display = empty($cabledone) || $cabledone == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($cabledone, ENT_QUOTES, 'UTF-8');
        $lcassembly_display = empty($lcassembly_date) || $lcassembly_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($lcassembly_date, ENT_QUOTES, 'UTF-8');
        
        // 기타 디스플레이
        $etclaser_display = empty($etclaser_date) || $etclaser_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($etclaser_date, ENT_QUOTES, 'UTF-8');
        $etcbending_display = empty($etcbending_date) || $etcbending_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($etcbending_date, ENT_QUOTES, 'UTF-8');
        $etcwelding_display = empty($etcwelding_date) || $etcwelding_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($etcwelding_date, ENT_QUOTES, 'UTF-8');
        $etcpainting_display = empty($etcpainting_date) || $etcpainting_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($etcpainting_date, ENT_QUOTES, 'UTF-8');
        $etcassembly_display = empty($etcassembly_date) || $etcassembly_date == 'X' ? '<span class="badge bg-danger">NO</span>' : htmlspecialchars($etcassembly_date, ENT_QUOTES, 'UTF-8');
        
        // LC와 ETC 값 결합
        $has_lc_values = (
            is_valid_etc_value($lclaser_date) ||
            is_valid_etc_value($lcbending_date) ||
            is_valid_etc_value($lcwelding_date) ||
            is_valid_etc_value($lcpainting_date) ||
            is_valid_etc_value($lcassembly_date)
        );
        
        $has_etc_values = (
            is_valid_etc_value($etclaser_date) ||
            is_valid_etc_value($etcbending_date) ||
            is_valid_etc_value($etcwelding_date) ||
            is_valid_etc_value($etcpainting_date) ||
            is_valid_etc_value($etcassembly_date)
        );
        
        if ($has_lc_values && $has_etc_values) {
            $lclaser_combined = $lclaser_display . '<br>' . $etclaser_display;
            $lcwelding_combined = $lcwelding_display . '<br>' . $etcbending_display;
            $lcpainting_combined = $lcpainting_display . '<br>' . $etcwelding_display;
            $lccabledone_combined = $lccabledone_display . '<br>' . $etcpainting_display;
            $lcassembly_combined = $lcassembly_display . '<br>' . $etcassembly_display;
        } elseif ($has_lc_values) {
            $lclaser_combined = $lclaser_display;
            $lcwelding_combined = $lcwelding_display;
            $lcpainting_combined = $lcpainting_display;
            $lccabledone_combined = $lccabledone_display;
            $lcassembly_combined = $lcassembly_display;
        } elseif ($has_etc_values) {
            $lclaser_combined = $etclaser_display;
            $lcwelding_combined = $etcwelding_display;
            $lcpainting_combined = $etcpainting_display;
            $lccabledone_combined = '';
            $lcassembly_combined = $etcassembly_display;
        } else {
            $lclaser_combined = $lclaser_display;
            $lcwelding_combined = $lcwelding_display;
            $lcpainting_combined = $lcpainting_display;
            $lccabledone_combined = $lccabledone_display;
            $lcassembly_combined = $lcassembly_display;
        }
        
        // 현장명 표시 (길이 제한)
        $display_workplacename = (mb_strlen($workplacename, 'UTF-8') > 15) 
            ? mb_substr($workplacename, 0, 15, 'UTF-8') . '..' 
            : $workplacename;
        
        // 메모 표시 (길이 제한)
        $display_memo = (mb_strlen($memo ?? '', 'UTF-8') > 10) 
            ? mb_substr($memo ?? '', 0, 10, 'UTF-8') . '..' 
            : ($memo ?? '');
        ?>
        
        <tr onclick="navigateToLink(event, 'view.php?num=<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>')">
            <td class="text-center" data-order="<?php echo htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($deadlineStr, ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td class="text-center"><?php echo htmlspecialchars($car_insize ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center text-danger"><?php echo htmlspecialchars($boxwrap, ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <span title="<?php echo htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($display_workplacename, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </td>
            <td class="text-center"><?php echo htmlspecialchars($type ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($su ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-start">
                <span title="<?php echo htmlspecialchars($memo ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($display_memo, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </td>
            <td class="text-center" data-order="<?php echo htmlspecialchars($main_draw_arr, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo $main_draw_display; ?>/<?php echo $lc_draw_display; ?>/<?php echo $etc_draw_display; ?>
            </td>
            <td class="text-center text-success">
                <?php if (!empty($outsourcing ?? '')): ?>
                    <div class="outsourcing-tooltip">
                        <span class="badge bg-success"><?php echo htmlspecialchars($outsourcing, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php if (!empty($outsourcing_memo ?? '')): ?>
                            <div class="tooltip-content">
                                <div class="tooltip-title">외주가공 메모</div>
                                <div class="tooltip-memo"><?php echo htmlspecialchars($outsourcing_memo, ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php echo htmlspecialchars($outsourcing ?? '', ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </td>
            <td class="text-center text-primary"><?php echo $mainlaser_display; ?></td>
            <td class="text-center text-primary"><?php echo $mainwelding_display; ?></td>
            <td class="text-center text-primary"><?php echo $mainpainting_display; ?></td>
            <td class="text-center text-primary"><?php echo $mainassembly_display; ?></td>
            <td class="text-center text-success"><?php echo $lclaser_combined; ?></td>
            <td class="text-center text-success"><?php echo $lcwelding_combined; ?></td>
            <td class="text-center text-success"><?php echo $lcpainting_combined; ?></td>
            <td class="text-center text-success"><?php echo $lccabledone_combined; ?></td>
            <td class="text-center text-success"><?php echo $lcassembly_combined; ?></td>
            <td class="text-center"><?php echo $tmpmsg; ?></td>
        </tr>
        
        <?php
        $start_num--;
    }
    
} catch (PDOException $ex) {
    error_log("데이터 조회 오류: " . $ex->getMessage());
    echo "오류: 데이터를 불러오는 중 문제가 발생했습니다.";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</form>
</body>
</html>

<script type="text/javascript">
(function() {
    'use strict';
    
    var dataTable;
    var mceilingpageNumber;
    
    $(document).ready(function() {
        // DataTables 초기 설정
        dataTable = $('#myTable').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 200,
            "lengthMenu": [200, 500, 1000],
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "search": "Live Search:"
            },
            "order": [[0, 'asc']]
        });
        
        // 페이지 번호 복원
        var savedPageNumber = getCookie('mceilingpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
        
        // 페이지 변경 이벤트
        dataTable.on('page.dt', function() {
            mceilingpageNumber = dataTable.page.info().page + 1;
            setCookie('mceilingpageNumber', mceilingpageNumber, 10);
        });
        
        // 페이지 길이 변경 이벤트
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw();
            
            savedPageNumber = getCookie('mceilingpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }
        });
        
        // 추가 정보 패널 토글
        $("#addflip").click(function() {
            $("#addpanel").slideToggle();
        });
        
        $("#addpanel").click(function() {
            $("#addpanel").slideUp("slow");
        });
        
        // 외주 툴팁 위치 조정
        $('.outsourcing-tooltip').each(function() {
            $(this).on('mouseenter', function() {
                var tooltip = $(this).find('.tooltip-content');
                var tooltipRect = tooltip[0].getBoundingClientRect();
                var viewportWidth = window.innerWidth;
                
                if (tooltipRect.right > viewportWidth) {
                    tooltip.css('left', 'auto').css('right', '0').css('margin-left', '0');
                } else {
                    tooltip.css('left', '50%').css('right', 'auto').css('margin-left', '-160px');
                }
            });
        });
    });
    
    function restorePageNumber() {
        var savedPageNumber = getCookie('mceilingpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
        }
    }
    
    window.navigateToLink = function(event, url) {
        if (event.target.tagName !== 'A') {
            if (typeof customPopup !== 'undefined') {
                customPopup(url, '세부 내역', 1400, 900);
            } else {
                window.location.href = url;
            }
        }
    };
    
    window.process_list = function() {
        document.getElementById('board_form').submit();
    };
    
    window.show_list = function(insu) {
        document.getElementById('search').value = null;
        document.getElementById('cursort').value = insu;
        document.getElementById('board_form').submit();
    };
    
    window.SearchEnter = function(event) {
        if (event.keyCode == 13) {
            document.getElementById('board_form').submit();
        }
    };
    
})();
</script>
