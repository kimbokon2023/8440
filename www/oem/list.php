<?php
/**
 * 서한컴퍼니 외주발주 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
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

// 요청 변수 초기화 (?? '' 형태)
$search = $_REQUEST["search"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$scale = $_REQUEST["scale"] ?? 30;

// 체크박스 변수 초기화 (?? '' 형태)
$check_draw = $_REQUEST["check_draw"] ?? $_POST["check_draw"] ?? '0';
$notorder = $_REQUEST["notorder"] ?? $_POST["notorder"] ?? '0';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';

// 페이지 변수 (?? '' 형태)
$page = $_REQUEST["page"] ?? 1;

// 정렬 관련 변수 (?? '' 형태)
$cursort = $_REQUEST["cursort"] ?? $_POST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? $_POST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? $_POST["stable"] ?? '0';

// 정렬 로직
if ($sortof != '0') {
    if ($sortof == 1 && $stable == 0) {
        $cursort = ($cursort != 1) ? 1 : 2;
    }
    if ($sortof == 2 && $stable == 0) {
        $cursort = ($cursort != 3) ? 3 : 4;
    }
    if ($sortof == 3 && $stable == 0) {
        $cursort = ($cursort != 5) ? 5 : 6;
    }
    if ($sortof == 4 && $stable == 0) {
        $cursort = ($cursort != 7) ? 7 : 8;
    }
    if ($sortof == 5 && $stable == 0) {
        $cursort = ($cursort != 9) ? 9 : 10;
    }
    if ($sortof == 6 && $stable == 0) {
        $cursort = ($cursort != 11) ? 11 : 12;
    }
    if ($sortof == 7 && $stable == 0) {
        $cursort = ($cursort != 13) ? 13 : 14;
    }
} else {
    $sortof = 0;
    $cursort = 0;
}

// 기타 변수 초기화 (?? '' 형태)
$sum = [];
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$year = $_REQUEST["year"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '0';
$ma_alert = $_REQUEST["ma_alert"] ?? '0';
$order_alert = $_REQUEST["order_alert"] ?? '0';
$sqltext = '';
$dis_text = '';

$page_scale = 10;
$first_num = ($page - 1) * $scale;

// 정렬 기준
switch ($cursort) {
    case 1:
        $orderby = "ORDER BY orderday DESC, num DESC";
        break;
    case 2:
        $orderby = "ORDER BY orderday ASC, num DESC";
        break;
    case 3:
        $orderby = "ORDER BY startday DESC, num DESC";
        break;
    case 4:
        $orderby = "ORDER BY startday ASC, num DESC";
        break;
    case 5:
        $orderby = "ORDER BY startday DESC, num DESC";
        break;
    case 6:
        $orderby = "ORDER BY startday ASC, num DESC";
        break;
    case 7:
        $orderby = "ORDER BY deadline DESC, num DESC";
        break;
    case 8:
        $orderby = "ORDER BY deadline ASC, num DESC";
        break;
    case 9:
        $orderby = "ORDER BY workday DESC, num DESC";
        break;
    case 10:
        $orderby = "ORDER BY workday ASC, num DESC";
        break;
    case 11:
        $orderby = "ORDER BY demand ASC, orderday DESC, num DESC";
        break;
    case 12:
        $orderby = "ORDER BY demand DESC, orderday DESC, num DESC";
        break;
    case 13:
        $orderby = "ORDER BY testday ASC, num DESC";
        break;
    case 14:
        $orderby = "ORDER BY testday DESC, num DESC";
        break;
    default:
        $orderby = "ORDER BY orderday DESC, num DESC";
        break;
}

$now = date("Y-m-d");

// WHERE 절 조건 설정
$attached = '';
$whereattached = '';

if ($check == '1') {
    $attached = " AND ((workday='') OR (workday='0000-00-00'))";
    $whereattached = " WHERE workday=''";
}

if ($check_draw == '1') {
    $attached = " AND ((main_draw='') OR (main_draw='0000-00-00') OR (lc_draw='') OR (lc_draw='0000-00-00'))";
    $whereattached = " WHERE ((main_draw='') AND (bon_su>'0')) OR ((lc_draw='') AND (lc_su>'0'))";
}

if ($notorder == '1') {
    $attached = " AND (((order_com1<>'') AND (order_date1='')) OR ((order_com2<>'') AND (order_date2='')) OR ((order_com3<>'') AND (order_date3='')))";
    $whereattached = " WHERE ((order_com1<>'') AND (order_date1='')) OR ((order_com2<>'') AND (order_date2='')) OR ((order_com3<>'') AND (order_date3=''))";
}

if ($plan_output_check == '1') {
    $attached = " AND (DATE(deadline)>=DATE(NOW()))";
    $whereattached = " WHERE DATE(deadline)>=DATE(NOW())";
    $orderby = "ORDER BY deadline ASC";
}

if ($notorder == '1' && $plan_output_check == '1') {
    $attached = " AND (((order_com1<>'') AND (order_date1='')) OR ((order_com2<>'') AND (order_date2='')) OR ((order_com3<>'') AND (order_date3='')) AND (DATE(deadline)>=DATE(NOW())))";
    $whereattached = " WHERE (((order_com1<>'') AND (order_date1='')) OR ((order_com2<>'') AND (order_date2='')) OR ((order_com3<>'') AND (order_date3=''))) AND DATE(deadline)>=DATE(NOW())";
}

if ($notorder == '1' && $check == '1') {
    $attached = " AND (((order_com1<>'') AND (order_date1='')) OR ((order_com2<>'') AND (order_date2='')) OR ((order_com3<>'') AND (order_date3='')) AND (workday=''))";
    $whereattached = " WHERE (((order_com1<>'') AND (order_date1='')) OR ((order_com2<>'') AND (order_date2='')) OR ((order_com3<>'') AND (order_date3=''))) AND (workday='')";
}

if ($output_check == '1') {
    $attached = " AND ((workday!='') AND (workday!='0000-00-00'))";
    $whereattached = " WHERE workday!=''";
}

$a = " " . $orderby . " LIMIT ?, ?";
$b = " " . $orderby;

// SQL 쿼리 생성 (Prepared Statement 사용)
$sql = '';
$sqlcon = '';
$params = [];
$params_count = [];

if (empty($search)) {
    $sql = "SELECT * FROM mirae8440.oem" . $whereattached . " " . $orderby . " LIMIT ?, ?";
    $sqlcon = "SELECT * FROM mirae8440.oem" . $whereattached . " " . $orderby;
    $params = [$first_num, $scale];
    $params_count = [];
} elseif (!empty($search) && $find != "all" && !empty($find)) {
    $sql = "SELECT * FROM mirae8440.oem WHERE ({$find} LIKE ?)" . $attached . " " . $orderby . " LIMIT ?, ?";
    $sqlcon = "SELECT * FROM mirae8440.oem WHERE ({$find} LIKE ?)" . $attached . " " . $orderby;
    $searchTerm = "%{$search}%";
    $params = [$searchTerm, $first_num, $scale];
    $params_count = [$searchTerm];
} elseif (!empty($search) && $find == "all") {
    $searchTerm = "%{$search}%";
    
    if ($check != '1') {
        $sql = "SELECT * FROM mirae8440.oem WHERE (workplacename LIKE ? ) OR (firstordman LIKE ? ) OR (secondordman LIKE ? ) OR (chargedman LIKE ? ) ";
        $sql .= "OR (delicompany LIKE ? ) OR (type1 LIKE ? ) OR (firstord LIKE ? ) OR (secondord LIKE ? ) OR (car_insize1 LIKE ? ) OR (memo LIKE ? ) OR (memo2 LIKE ? ) OR (material1 LIKE ? ) OR (material2 LIKE ? ) OR (material3 LIKE ? ) OR (material4 LIKE ? ) OR (material5 LIKE ? ) OR (air_su LIKE ? ) " . $a;
        
        $sqlcon = "SELECT * FROM mirae8440.oem WHERE (workplacename LIKE ? ) OR (firstordman LIKE ? ) OR (secondordman LIKE ? ) OR (chargedman LIKE ? ) ";
        $sqlcon .= "OR (delicompany LIKE ? ) OR (type1 LIKE ? ) OR (firstord LIKE ? ) OR (secondord LIKE ? ) OR (car_insize1 LIKE ? ) OR (memo LIKE ? ) OR (memo2 LIKE ? ) OR (material1 LIKE ? ) OR (material2 LIKE ? ) OR (material3 LIKE ? ) OR (material4 LIKE ? ) OR (material5 LIKE ? ) OR (air_su LIKE ? ) " . $b;
        
        $params = array_fill(0, 17, $searchTerm);
        $params[] = $first_num;
        $params[] = $scale;
        $params_count = array_fill(0, 17, $searchTerm);
    }
    
    if ($check == '1' || $output_check == '1' || $measure_check == '1' || $plan_output_check == '1' || $team_check == '1') {
        $sql = "SELECT * FROM mirae8440.oem WHERE ((workplacename LIKE ? ) OR (firstordman LIKE ? ) OR (secondordman LIKE ? ) OR (chargedman LIKE ? ) ";
        $sql .= "OR (delicompany LIKE ? ) OR (type1 LIKE ? ) OR (firstord LIKE ? ) OR (secondord LIKE ? ) OR (car_insize1 LIKE ? ) OR (memo LIKE ? ) OR (memo2 LIKE ? ) OR (material1 LIKE ? ) OR (material2 LIKE ? ) OR (material3 LIKE ? ) OR (material4 LIKE ? ) OR (material5 LIKE ? ) OR (air_su LIKE ? )) " . $attached . " " . $orderby . " LIMIT ?, ?";
        
        $sqlcon = "SELECT * FROM mirae8440.oem WHERE ((workplacename LIKE ? ) OR (firstordman LIKE ? ) OR (secondordman LIKE ? ) OR (chargedman LIKE ? ) ";
        $sqlcon .= "OR (delicompany LIKE ? ) OR (type1 LIKE ? ) OR (firstord LIKE ? ) OR (secondord LIKE ? ) OR (car_insize1 LIKE ? ) OR (memo LIKE ? ) OR (memo2 LIKE ? ) OR (material1 LIKE ? ) OR (material2 LIKE ? ) OR (material3 LIKE ? ) OR (material4 LIKE ? ) OR (material5 LIKE ? ) OR (air_su LIKE ? )) " . $attached . " " . $orderby;
        
        $params = array_fill(0, 17, $searchTerm);
        $params[] = $first_num;
        $params[] = $scale;
        $params_count = array_fill(0, 17, $searchTerm);
    }
}

// 기본 쿼리 (조건이 없을 때)
if (empty($sql)) {
    $sql = "SELECT * FROM mirae8440.oem " . $orderby . " LIMIT ?, ?";
    $sqlcon = "SELECT * FROM mirae8440.oem " . $orderby;
    $params = [$first_num, $scale];
    $params_count = [];
}

$sum = [0, 0, 0, 0, 0, 0];
$dataList = [];
$total_row = 0;
$total_page = 1;
$current_page = 1;

try {
    // 전체 레코드 수 조회
    $allstmh = $pdo->prepare($sqlcon);
    foreach ($params_count as $index => $param) {
        $allstmh->bindValue($index + 1, $param, PDO::PARAM_STR);
    }
    $allstmh->execute();
    $total_row = $allstmh->rowCount();
    
    // 페이지별 데이터 조회
    $stmh = $pdo->prepare($sql);
    foreach ($params as $index => $param) {
        if ($index >= count($params) - 2) {
            $stmh->bindValue($index + 1, $param, PDO::PARAM_INT);
        } else {
            $stmh->bindValue($index + 1, $param, PDO::PARAM_STR);
        }
    }
    $stmh->execute();
    
    // 데이터를 배열에 저장
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $dataList[] = $row;
    }
    
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
    
} catch (PDOException $ex) {
    error_log("외주 목록 조회 오류: " . $ex->getMessage());
    $dataList = [];
    $total_row = 0;
    $total_page = 1;
    $current_page = 1;
}

include getDocumentRoot() . '/load_header.php';
?>

<link rel="stylesheet" type="text/css" href="../css/oem.css">
<title>서한 컴퍼니</title>
</head>
<body>

<div class="container-fluid">
    <div class="d-flex mb-1 justify-content-center">
        <a href="../index.php"><img src="../img/toplogo.jpg" style="width:100%;" alt="Logo"></a>
    </div>
    
    <?php require_once(includePath('myheader.php')); ?>
</div>

<div class="container-fluid">
    <div id="content">
        <form id="board_form" name="board_form" method="post" action="list.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&yearcheckbox=<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&sortof=<?= htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8') ?>&stable=<?= htmlspecialchars($stable, ENT_QUOTES, 'UTF-8') ?>">
            
            <input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" id="order_alert" name="order_alert" value="<?= htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8') ?>">
            
            <div id="vacancy" style="display:none"></div>
            
            <div id="col2">
                <div id="title_top" style="width:350px;margin-top:5px;color:grey;">
                    <h3>서한컴퍼니 외주발주</h3>
                </div>
                <div id="dis_board">
                    <input type="text" id="dis_text" size="100" style="font-size:17px;">
                </div>
                
                <div class="clear"></div>
                
                <div id="dis_board2" style="width:1500px;height:60px;margin-left:-5px;">
                    <span>25일 마감 파주시 파주읍 백석리 1-2 / 성광 조대리 010-7225-9608</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 설계 강형구부장 010-6244-4561
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:grey;">정스틸/010-2214-8030/이천용이사</span>
                </div>
                <br>
                
                <div id="list_search">
                    <div id="list_search1" style="font-size:12px;">총 <?= htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8') ?> 개의 자료</div>
                    
                    <div id="list_search2" style="width:350px;">
                        <br>
                        <?php
                        if ($check == '1') {
                            echo "<input type='checkbox' checked id='without' value='1'> 미출고 리스트 &nbsp;&nbsp;&nbsp;";
                        } else {
                            echo "<input type='checkbox' id='without' value='1'> 미출고 리스트 &nbsp;&nbsp;&nbsp;";
                        }
                        
                        if ($plan_output_check == '1') {
                            echo "<input type='checkbox' checked id='plan_outputlist' value='1'> 납품예정 &nbsp;&nbsp;&nbsp;";
                        } else {
                            echo "<input type='checkbox' id='plan_outputlist' value='1'> 납품예정&nbsp;&nbsp;&nbsp;";
                        }
                        
                        if ($output_check == '1') {
                            echo "<input type='checkbox' checked id='outputlist' value='1'> 출고완료&nbsp;&nbsp;&nbsp;";
                        } else {
                            echo "<input type='checkbox' id='outputlist' value='1'> 출고완료&nbsp;&nbsp;&nbsp;";
                        }
                        ?>
                    </div>
                    
                    <input type="hidden" id="check" name="check" value="<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="plan_output_check" name="plan_output_check" value="<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="output_check" name="output_check" value="<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="team_check" name="team_check" value="<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="measure_check" name="measure_check" value="<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="sqltext" name="sqltext" value="<?= htmlspecialchars($sqltext, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="check_draw" name="check_draw" value="<?= htmlspecialchars($check_draw, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="notorder" name="notorder" value="<?= htmlspecialchars($notorder, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" id="scale" name="scale" value="<?= htmlspecialchars($scale, ENT_QUOTES, 'UTF-8') ?>">
                    
                    <div id="list_search3"><img src="../img/select_search.gif" alt="Search"></div>
                    
                    <div id="list_search4">
                        <select name="find">
                            <option value='all' <?= ($find == "all" || $find == "") ? "selected" : "" ?>>전체</option>
                            <option value='workplacename' <?= ($find == "workplacename") ? "selected" : "" ?>>현장명</option>
                            <option value='firstord' <?= ($find == "firstord") ? "selected" : "" ?>>원청</option>
                            <option value='secondord' <?= ($find == "secondord") ? "selected" : "" ?>>발주처</option>
                            <option value='type' <?= ($find == "type") ? "selected" : "" ?>>타입</option>
                        </select>
                    </div>
                    
                    <div id="list_search5"><input type="text" id="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div id="list_search6"><input type="image" src="../img/list_search_button.gif" alt="Search Button"></div> &nbsp;
                    
                    <?php if ($level < 5) { ?>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="window.open('batchDB.php','청구 일괄처리','left=10,top=50, scrollbars=yes, toolbars=no,width=1700,height=850');">청구 일괄처리</button>
                    <?php } ?>
                    
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.open('plan_making.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&asprocess=<?= htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8') ?>&fromdate=<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>&todate=<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>','납품일정 List DB','left=50,top=50, scrollbars=yes, toolbars=no,width=1600,height=800');" border="0">납품예정</button>
                    
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.open('No_demandlist.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&asprocess=<?= htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8') ?>&fromdate=<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>&todate=<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>','출고완료 미청구 List DB','left=50,top=50, scrollbars=yes, toolbars=no,width=1600,height=800');" border="0">출고완료 미청구</button>
                    
                    <?php if ($level < 5) { ?>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="window.open('call_csv.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&asprocess=<?= htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8') ?>&fromdate=<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>&todate=<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>&list=1&sortof=6&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&stable=0&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>','CSV 파일추출','left=100,top=100, scrollbars=yes, toolbars=no,width=1600,height=500');">엑셀CSV저장</button>
                    <?php } ?>
                    
                    <div id="list_search7">
                        <?php if ($level < 5) { ?>
                            <a href="write_form.php"><img src="../img/write.png" alt="Write"></a>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="clear"></div>
                
                <div id="list_top_title">
                    <ul>
                        <li id="list_title1" style="margin-left:-35px;">번호</li>
                        <li id="list_title6" style="margin-left:15px;"><a href="list.php?&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&list=1&sortof=1&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&stable=0&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>">접수일</a></li>
                        <li id="list_title6" style="margin-left:75px;"><a style="color:green" href="list.php?&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&list=1&sortof=3&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&stable=0&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>">&nbsp;발주일&nbsp;</a></li>
                        <li id="list_title6"><a style="color:red" href="list.php?&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&list=1&sortof=4&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&stable=0&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>">&nbsp;납기일&nbsp;</a></li>
                        <li id="list_title13" style="margin-left:15px;"><a href="list.php?&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&list=1&sortof=5&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&stable=0&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>">출고일</a></li>
                        <li id="list_title14"><a href="list.php?&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&list=1&sortof=6&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&stable=0&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>">청구</a></li>
                        <li id="list_title4">현장명</li>
                        <li id="list_title9" style="margin-left:180px;">발주처</li>
                        <li id="list_title10" style="margin-left:50px;">타입</li>
                        <li id="list_title10">인승</li>
                        <li id="list_title10" style="margin-left:50px;">Car insize</li>
                        <li id="list_title11">L/C</li>
                        <li id="list_title11">기타</li>
                        <li id="list_title11">운반비 내역</li>
                        <li id="list_title12">비고</li>
                    </ul>
                </div>
                
                <div id="list_content">
                    <?php
                    if ($page <= 1) {
                        $start_num = $total_row;
                    } else {
                        $start_num = $total_row - ($page - 1) * $scale;
                    }
                    
                    foreach ($dataList as $row) {
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
                        $order_date1 = $row["order_date1"] ?? '';
                        $order_date2 = $row["order_date2"] ?? '';
                        $order_date3 = $row["order_date3"] ?? '';
                        $order_date4 = $row["order_date4"] ?? '';
                        $order_input_date1 = $row["order_input_date1"] ?? '';
                        $order_input_date2 = $row["order_input_date2"] ?? '';
                        $order_input_date3 = $row["order_input_date3"] ?? '';
                        $order_input_date4 = $row["order_input_date4"] ?? '';
                        
                        $sum[0] = $sum[0] + (int)$su;
                        $sum[1] += (int)$bon_su;
                        $sum[2] += (int)$lc_su;
                        $sum[3] += (int)$etc_su;
                        $sum[4] += (int)$air_su;
                        $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
                        
                        $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),  L/C : " . $sum[2] . "  (EA), 기타 : " . $sum[3] . "  (EA)";
                        
                        $startday = trans_date($startday);
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
                        
                        $checkbox = $row["checkbox"] ?? 0;
                        
                        $state_work = 0;
                        if ($checkbox == 0) $state_work = 1;
                        if (substr($workday, 0, 2) == "20") $state_work = 2;
                        if (substr($endworkday, 0, 2) == "20") $state_work = 3;
                        
                        $typeAll = "";
                        for ($i = 1; $i <= 10; $i++) {
                            $tmp = 'type' . $i;
                            if ($i > 1 && $$tmp != '') {
                                $typeAll .= '/' . $$tmp;
                            } else {
                                $typeAll .= $$tmp;
                            }
                        }
                        
                        $car_insizeAll = "";
                        for ($i = 1; $i <= 10; $i++) {
                            $tmp = 'car_insize' . $i;
                            if ($i > 1 && $$tmp != '') {
                                $car_insizeAll .= '/' . $$tmp;
                            } else {
                                $car_insizeAll .= $$tmp;
                            }
                        }
                        
                        $workitem = "";
                        if ($su != "") $workitem = $su . " , ";
                        if ($bon_su != "") $workitem .= "본 " . $bon_su . ", ";
                        if ($lc_su != "") $workitem .= "L/C " . $lc_su . ", ";
                        if ($etc_su != "") $workitem .= "기타 " . $etc_su . ", ";
                        if ($air_su != "") $workitem .= "공기청정기 " . $air_su . " ";
                        
                        $part = "";
                        if ($order_com1 != "") $part = $order_com1 . ",";
                        if ($order_com2 != "") $part .= $order_com2 . ", ";
                        if ($order_com3 != "") $part .= $order_com3 . ", ";
                        if ($order_com4 != "") $part .= $order_com4 . ", ";
                        
                        $deli_text = "";
                        if ($delivery != "" || $delipay != 0) {
                            $deli_text = $delivery . " " . $delipay;
                        }
                    ?>
                    
                    <div id="subject_item">
                        <a href="view.php?num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&scale=<?= htmlspecialchars($scale, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&yearcheckbox=<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&output_check=<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>&team_check=<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>&measure_check=<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>&plan_output_check=<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>&cursort=<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>&sortof=<?= htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8') ?>&stable=<?= htmlspecialchars($stable, ENT_QUOTES, 'UTF-8') ?>&check=<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>&notorder=<?= htmlspecialchars($notorder, ENT_QUOTES, 'UTF-8') ?>" style="font-size:13px">
                            <div id="subject_item1" style="margin-left:-5px;"><?= htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8') ?> &nbsp;</div>
                            <div id="subject_item2"><?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?> &nbsp;</div>
                            <div id="subject_item3"><?= htmlspecialchars(mb_substr($startday, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            
                            <?php if ($deadline == date("Y-m-d", time())) { ?>
                                <div id="subject_item6" class="blink" style="color:red;">
                            <?php } else { ?>
                                <div id="subject_item6">
                            <?php } ?>
                                &nbsp;<?= htmlspecialchars(mb_substr($deadline, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') ?>&nbsp;
                            </div>
                            
                            <div id="subject_item6">&nbsp;<?= htmlspecialchars(mb_substr($workday, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item14" style="color:purple;">&nbsp;<?= htmlspecialchars(mb_substr($demand, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item4"><?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            
                            <?php if ($secondord == '성광') { ?>
                                <div id="subject_item9" style="color:grey;">
                            <?php } else { ?>
                                <div id="subject_item9" style="color:brown;">
                            <?php } ?>
                                <?= htmlspecialchars(mb_substr($secondord, 0, 15, "utf-8"), ENT_QUOTES, 'UTF-8') ?>&nbsp;
                            </div>
                            
                            <div id="subject_item14" style="overflow: auto;color:blue;width:80px;">&nbsp;<?= htmlspecialchars($typeAll, ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item22" style="overflow: auto;width:80px;">&nbsp;<?= htmlspecialchars($inseung1, ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item14" style="overflow: auto;color:red;width:100px;">&nbsp;<?= htmlspecialchars($car_insizeAll, ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item21" style="color:black;"><?= htmlspecialchars($lc_su, ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item22" style="color:grey;"><?= htmlspecialchars($etc_su, ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item17" style="color:purple"><?= htmlspecialchars($deli_text, ENT_QUOTES, 'UTF-8') ?>&nbsp;</div>
                            <div id="subject_item12"><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></div>
                        </a>
                    </div>
                    <div class="clear"></div>
                    
                    <?php
                        $start_num--;
                    }
                    
                    if (empty($dataList)) {
                        echo '<div class="alert alert-info text-center">조회된 데이터가 없습니다.</div>';
                    }
                    ?>
                </div>
                
                <?php
                // 페이지네이션 계산
                $start_page = ($current_page - 1) * $page_scale + 1;
                $end_page = $start_page + $page_scale - 1;
                ?>
                
                <div id="page_button">
                    <div id="page_num">
                        <?php
                        if ($page != 1 && $page > $page_scale) {
                            $prev_page = $page - $page_scale;
                            if ($prev_page <= 0) $prev_page = 1;
                            echo "<a href='list.php?page=" . htmlspecialchars($prev_page, ENT_QUOTES, 'UTF-8') . "&search=" . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . "&find=" . htmlspecialchars($find, ENT_QUOTES, 'UTF-8') . "&list=1&process=" . htmlspecialchars($process, ENT_QUOTES, 'UTF-8') . "&yearcheckbox=" . htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') . "&year=" . htmlspecialchars($year, ENT_QUOTES, 'UTF-8') . "&sortof=" . htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8') . "&cursort=" . htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') . "&stable=1&check=" . htmlspecialchars($check, ENT_QUOTES, 'UTF-8') . "&output_check=" . htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') . "&team_check=" . htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') . "&measure_check=" . htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') . "'>◀ </a>";
                        }
                        
                        for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                            if ($page == $i) {
                                echo "<font color='red'><b>[{$i}]</b></font>";
                            } else {
                                echo "<a href='list.php?page={$i}&search=" . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . "&find=" . htmlspecialchars($find, ENT_QUOTES, 'UTF-8') . "&list=1&process=" . htmlspecialchars($process, ENT_QUOTES, 'UTF-8') . "&yearcheckbox=" . htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') . "&year=" . htmlspecialchars($year, ENT_QUOTES, 'UTF-8') . "&sortof=" . htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8') . "&cursort=" . htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') . "&stable=1&check=" . htmlspecialchars($check, ENT_QUOTES, 'UTF-8') . "&output_check=" . htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') . "&team_check=" . htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') . "&measure_check=" . htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') . "'>[{$i}]</a>";
                            }
                        }
                        
                        if ($page < $total_page) {
                            $next_page = $page + $page_scale;
                            if ($next_page > $total_page) $next_page = $total_page;
                            echo "<a href='list.php?page={$next_page}&search=" . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . "&find=" . htmlspecialchars($find, ENT_QUOTES, 'UTF-8') . "&list=1&process=" . htmlspecialchars($process, ENT_QUOTES, 'UTF-8') . "&yearcheckbox=" . htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') . "&year=" . htmlspecialchars($year, ENT_QUOTES, 'UTF-8') . "&sortof=" . htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8') . "&cursort=" . htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') . "&stable=1&check=" . htmlspecialchars($check, ENT_QUOTES, 'UTF-8') . "&output_check=" . htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') . "&team_check=" . htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') . "&measure_check=" . htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') . "'> ▶</a><p>";
                        }
                        ?>
                    </div>
                    <div class="clear"></div>
                    <br><br><br><br><br><br>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var userName = <?php echo json_encode($user_name, JSON_UNESCAPED_UNICODE); ?>;
    var disText = <?php echo json_encode($dis_text, JSON_UNESCAPED_UNICODE); ?>;
    
    $(document).ready(function() {
        $("#without").change(function() {
            if ($("#without").is(":checked")) {
                $('#check').val('1');
            } else {
                $('#check').val('');
            }
            $('#board_form').submit();
        });
        
        $("#outputlist").change(function() {
            if ($("#outputlist").is(":checked")) {
                $('#output_check').val('1');
            } else {
                $('#output_check').val('');
            }
            $('#board_form').submit();
        });
        
        $("#plan_outputlist").change(function() {
            if ($("#plan_outputlist").is(":checked")) {
                $('#plan_output_check').val('1');
            } else {
                $('#plan_output_check').val('');
            }
            $('#board_form').submit();
        });
        
        $("#team").change(function() {
            if ($("#team").is(":checked")) {
                $('#team_check').val('1');
            } else {
                $('#team_check').val('');
            }
            $('#board_form').submit();
        });
        
        $("#notmeasure").change(function() {
            if ($("#notmeasure").is(":checked")) {
                $('#measure_check').val('1');
            } else {
                $('#measure_check').val('');
            }
            $('#board_form').submit();
        });
    });
    
    window.check_level = function() {
        window.open("check_level.php?nick=" + encodeURIComponent(document.member_form.nick.value), "NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
    };
    
    window.search_condition = function(con) {
        var notorder = <?php echo json_encode($notorder, JSON_UNESCAPED_UNICODE); ?>;
        var checkDraw = <?php echo json_encode($check_draw, JSON_UNESCAPED_UNICODE); ?>;
        
        if (con == 'draw') {
            if (checkDraw == '0' || checkDraw == '') {
                checkDraw = '1';
                $("#check_draw").val('1');
            } else {
                checkDraw = '0';
                $("#check_draw").val('0');
            }
            $("#check_draw").val(checkDraw);
            $("#scale").val('200');
            $('#board_form').submit();
        }
        
        if (con == 'notorder') {
            if (notorder == '0' || notorder == '') {
                notorder = '1';
                $("#notorder").val('1');
            } else {
                notorder = '0';
                $("#notorder").val('0');
            }
            $("#notorder").val(notorder);
            $("#scale").val('200');
            $('#board_form').submit();
        }
    };
    
    window.check_alert = function() {
        var tmp;
        
        tmp = "../load_alert.php";
        $("#vacancy").load(tmp);
        
        var vocAlert = $("#voc_alert").val();
        var maAlert = $("#ma_alert").val();
        var orderAlert = $("#order_alert").val();
        
        if (userName == '김진억' && vocAlert == '1') {
            if (typeof alertify !== 'undefined') {
                alertify.alert('<H1> 현장VOC 도착 알림</H1>', '<h1> 김진억 이사님 <br> <br> 현장VOC가 접수되었습니다. 확인 후 조치바랍니다. </h1>');
            }
            tmp = "../save_alert.php?voc_alert=0&ma_alert=" + maAlert + "&order_alert=" + orderAlert;
            $("#voc_alert").val('0');
            $("#vacancy").load(tmp);
        }
        
        if (userName == '김진억' && orderAlert == '1') {
            if (typeof alertify !== 'undefined') {
                alertify.alert('<H1> 쟘 발주서 도착 알림</H1>', '<h1> 김진억 이사님 <br> <br> 이메일을 확인해 주세요. 발주서가 접수되었습니다. </h1>');
            }
            tmp = "../save_alert.php?order_alert=0&ma_alert=" + maAlert + "&voc_alert=" + vocAlert;
            $("#order_alert").val('0');
            $("#vacancy").load(tmp);
        }
        
        if (userName == '조경임' && maAlert == '1') {
            if (typeof alertify !== 'undefined') {
                alertify.alert('<h1> 발주서 접수 알림 </h1>', '<h1> 조과장님 <br> <br> 발주서가 접수되었습니다. 내역 확인 후 발주해 주세요. </h1>');
            }
            tmp = "../save_alert.php?ma_alert=0&voc_alert=" + vocAlert + "&order_alert=" + orderAlert;
            $("#ma_alert").val('0');
            $("#vacancy").load(tmp);
        }
    };
    
    // 5초마다 알람상황을 체크
    var timer = setInterval(function() {
        check_alert();
    }, 3000);
    
    window.send_alert = function() {
        var vocAlert = $("#voc_alert").val();
        var maAlert = $("#ma_alert").val();
        var orderAlert = $("#order_alert").val();
        var tmp = "../save_alert.php?order_alert=1&ma_alert=" + maAlert + "&voc_alert=" + vocAlert;
        $("#vacancy").load(tmp);
        
        if (typeof alertify !== 'undefined') {
            alertify.alert('발주서 등록 알림', '<h1> 발주서가 접수되었습니다. 이메일을 확인해 주세요. </h1>');
        }
    };
    
    function dis_text() {
        $("#dis_text").val(disText);
    }
    
    // 합계 화면에 출력
    setTimeout(function() {
        dis_text();
    }, 2000);
    
})();
</script>

</body>
</html>
