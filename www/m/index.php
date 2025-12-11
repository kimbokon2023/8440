<?php
/**
 * 미래기업 쟘(Jamb) 공사관리 시스템 메인 페이지
 * 공사 현황을 조회하고 관리하는 메인 페이지입니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$id_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$user_nick = $_SESSION["nick"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $level > 8) {
    sleep(2);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/logout.php");
    exit;
}

// 특정 사용자 리다이렉션
if ($id_name === '소현철' || $id_name === '김보곤') {
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/index.php");
    exit;
}

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$scale = $_REQUEST["scale"] ?? 1000;
$check = $_REQUEST["check"] ?? $_POST["check"] ?? 0;
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';
$output_check = $_REQUEST["output_check"] ?? '';
$plan_output_check = $_REQUEST["plan_output_check"] ?? '';
$team_check = $_REQUEST["team_check"] ?? '';
$measure_check = $_REQUEST["measure_check"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$cursort = $_REQUEST["cursort"] ?? '';
$sortof = $_REQUEST["sortof"] ?? '';
$stable = $_REQUEST["stable"] ?? '';

// 기타 변수 초기화
if ($check == null) {
    $check = 0;
}

$attached = '';
$orderby = '';
$sqltext = '';

// 검색 조건 설정
switch ($check) {
    case '1': // 출고예정
        $attached = " (date(endworkday) >= date(now())) ";
        $orderby = " ORDER BY endworkday ASC ";
        break;
    case '2': // 시공완료 (사진여부)
        $attached = " (filename2 <> '') ";
        $orderby = " ORDER BY workday DESC ";
        break;
    case '3': // 미시공
        $attached = " ((workday = '') OR (workday = '0000-00-00')) ";
        $orderby = " ORDER BY orderday DESC ";
        break;
    case '4': // 미실측
        $attached = " ((measureday = '') OR (measureday = '0000-00-00')) ";
        $orderby = " ORDER BY num ASC ";
        break;
    default:
        $orderby = " ORDER BY num DESC ";
}

$a = " " . $orderby . " ";
$b = " " . $orderby;

// 합계 배열 초기화
$sum = array(0, 0, 0, 0);

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// SQL 쿼리 생성
$sql = '';
$sqlcon = '';

if ($search == "") {
    if ($check == '1' || $check == '2' || $check == '3' || $check == '4') {
        $sql = "SELECT * FROM {$DB}.work WHERE " . $attached . $orderby;
    } else {
        $sql = "SELECT * FROM {$DB}.work " . $a;
        $sqlcon = "SELECT * FROM {$DB}.work " . $b;
    }
} else {
    // SQL Injection 방지를 위한 이스케이프 처리
    $search_escaped = str_replace("'", "''", $search);
    
    $sql = "SELECT * FROM {$DB}.work WHERE (";
    $sql .= "(workplacename LIKE '%{$search_escaped}%') OR ";
    $sql .= "(firstordman LIKE '%{$search_escaped}%') OR ";
    $sql .= "(secondordman LIKE '%{$search_escaped}%') OR ";
    $sql .= "(chargedman LIKE '%{$search_escaped}%') OR ";
    $sql .= "(delicompany LIKE '%{$search_escaped}%') OR ";
    $sql .= "(hpi LIKE '%{$search_escaped}%') OR ";
    $sql .= "(firstord LIKE '%{$search_escaped}%') OR ";
    $sql .= "(secondord LIKE '%{$search_escaped}%') OR ";
    $sql .= "(worker LIKE '%{$search_escaped}%') OR ";
    $sql .= "(memo LIKE '%{$search_escaped}%')) " . $a;
    
    $sqlcon = "SELECT * FROM {$DB}.work WHERE (";
    $sqlcon .= "(workplacename LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(firstordman LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(secondordman LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(chargedman LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(delicompany LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(hpi LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(firstord LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(secondord LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(worker LIKE '%{$search_escaped}%') OR ";
    $sqlcon .= "(memo LIKE '%{$search_escaped}%')) " . $b;
}

try {
    $stmh = $pdo->query($sql);
    $temp1 = $stmh->rowCount();
    $total_row = $temp1;
} catch (PDOException $ex) {
    error_log("DB query error in m/index.php: " . $ex->getMessage());
    $total_row = 0;
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo getBaseUrl(); ?>/css/partner.css" type="text/css" />
    
    <title>미래기업 쟘공사 관리시스템</title>
    
    <style>
    .container {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    
    @media (min-width: 768px) {
        .container { width: 750px; }
    }
    @media (min-width: 992px) {
        .container { width: 970px; }
    }
    @media (min-width: 1200px) {
        .container { width: 1170px; }
    }
    
    .container-fluid {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    
    .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1,
    .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2,
    .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3,
    .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4,
    .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5,
    .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6,
    .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7,
    .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8,
    .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9,
    .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10,
    .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11,
    .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
        position: relative;
        min-height: 1px;
        padding-right: 6px;
        padding-left: 6px;
    }
    
    .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4,
    .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10,
    .col-xs-11, .col-xs-12 {
        position: relative;
        min-height: 1px;
        padding-right: 3px;
        padding-left: 3px;
        float: left;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <br><br>
        
        <div id="top-menu">
            <?php if (!isset($_SESSION["userid"])) { ?>
                <a href="<?php echo getBaseUrl(); ?>/login/login_form.php">로그인</a> | 
                <a href="<?php echo getBaseUrl(); ?>/member/insertForm.php">회원가입</a>
            <?php } else { ?>
                <div class="row">
                    <div class="col-6">
                        <h3 class="display-5 font-center text-left">
                            <?php echo htmlspecialchars($user_nick, ENT_QUOTES, 'UTF-8'); ?> | 
                            <a href="<?php echo getBaseUrl(); ?>/login/logout.php">로그아웃</a> | 
                            <a href="<?php echo getBaseUrl(); ?>/member/updateForm.php?id=<?php echo urlencode($user_id); ?>">정보수정</a>
                        </h3>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <br>
        
        <form id="board_form" name="board_form" method="get" action="<?php echo htmlspecialchars('index.php?' . http_build_query(array(
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
        )), ENT_QUOTES, 'UTF-8'); ?>">
            
            <h1>미래기업 쟘(Jamb) 공사관리System</h1>
            
            <br>
            
            <div class="row">
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">미래기업 공지사항</h4>
                            </div>
                            <div class="modal-body">
                                <p>요즘 쟘 출고량이 많습니다. 출고일이 지연될 수 있음을 양해 부탁드립니다.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-5">
                    <h3 class="display-5 text-left">
                        총 <?php echo htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8'); ?> 개의 자료 파일이 있습니다.
                    </h3>
                </div>
                <div class="col">
                    <h4 class="display-4 font-center text-center">
                        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" size="20">
                    </h4>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-dark btn-lg" onclick="document.getElementById('board_form').submit();">검색</button>
                </div>
            </div>
            
            <br>
            
            <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary btn-lg" onclick="location.href='index.php?mode=search&search=추영덕'">추영덕</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-secondary btn-lg" onclick="location.href='index.php?mode=search&search=이만희'">이만희</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-success btn-lg" onclick="location.href='index.php?mode=search&search=김상훈'">김상훈</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-lg" onclick="location.href='index.php?mode=search&search=김운호'">김운호</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-warning btn-lg" onclick="location.href='index.php?mode=search&search=이용휘'">이용휘</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-info btn-lg" onclick="location.href='index.php?mode=search&search=손상민'">손상민</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            
            <br>
            
            <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-light btn-lg" onclick="location.href='index.php?mode=search&search=김진섭'">김진섭</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-dark btn-lg" onclick="location.href='index.php?mode=search&search=유영'">유영</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary btn-lg" onclick="location.href='index.php?mode=search&search=지영복'">지영복</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-secondary btn-lg" onclick="location.href='index.php?mode=search&search=김한준'">김한준</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-success btn-lg" onclick="location.href='index.php?mode=search&search=민경채'">민경채</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            
            <br><br>
            
            <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" id="showall" class="btn btn-dark btn-lg" onclick="location.href='index.php?mode=search&search=<?php echo urlencode($search); ?>&check=0'">전체</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" id="outputplan" class="btn btn-danger btn-lg" onclick="location.href='index.php?mode=search&search=<?php echo urlencode($search); ?>&check=1'">출고예정</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" id="outputplan" class="btn btn-primary btn-lg" onclick="location.href='index.php?mode=search&search=<?php echo urlencode($search); ?>&check=2'">시공후 사진등록완료</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button id="showNowork" type="button" class="btn btn-info btn-lg" onclick="location.href='index.php?mode=search&search=<?php echo urlencode($search); ?>&check=3'">미시공</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button id="showNomeasure" type="button" class="btn btn-success btn-lg btn-lg" onclick="location.href='index.php?mode=search&search=<?php echo urlencode($search); ?>&check=4'">미실측</button>
            </div>
            
            <br>
            
            <input type="hidden" id="check" name="check" value="<?php echo htmlspecialchars($check, ENT_QUOTES, 'UTF-8'); ?>" size="5">
            <input type="hidden" id="plan_output_check" name="plan_output_check" value="<?php echo htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8'); ?>" size="5">
            <input type="hidden" id="output_check" name="output_check" value="<?php echo htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8'); ?>" size="5">
            <input type="hidden" id="team_check" name="team_check" value="<?php echo htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8'); ?>" size="5">
            <input type="hidden" id="measure_check" name="measure_check" value="<?php echo htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8'); ?>" size="5">
            <input type="hidden" id="sqltext" name="sqltext" value="<?php echo htmlspecialchars($sqltext, ENT_QUOTES, 'UTF-8'); ?>">
            
            <div id="list_search4"></div>
            <div id="list_search5"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div id="list_search11"></div>
            <div id="list_search12"></div>
            
            <div class="row">
                <div class="col-1">
                    <h4 class="display-5 font-center text-center">No</h4>
                </div>
                <?php
                switch ($check) {
                    case '1':
                        echo '<div class="col-sm-1"><h4 class="display-5 font-center text-center text-danger">출고예정일</h4></div>';
                        break;
                    case '2':
                        echo '<div class="col-sm-1"><h4 class="display-5 font-center text-center">출고일</h4></div>';
                        break;
                    default:
                        echo '<div class="col-sm-1"><h4 class="display-5 font-center text-center">접수일</h4></div>';
                        break;
                }
                ?>
            </div>
            
            <?php
            switch ($check) {
                case '0':
                    echo '<div class="col-sm-1"><h4 class="display-5 font-center text-center text-danger">출고일</h4></div>';
                    break;
                default:
                    echo '<div class="col-sm-1"><h4 class="display-5 font-center text-center">검사일</h4></div>';
                    break;
            }
            ?>
            
            <div class="col-sm-1">
                <h4 class="display-5 font-center text-center">실측</h4>
            </div>
            <div class="col-sm-1">
                <h4 class="display-5 font-center text-center">도면</h4>
            </div>
            <div class="col-sm-1">
                <h4 class="display-5 font-center text-center text-primary">후사진</h4>
            </div>
            <div class="col-sm">
                <h4 class="display-5 font-center text-center">현장명</h4>
            </div>
            
            <?php
            $start_num = $total_row;
            
            if (isset($stmh)) {
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    // 변수 초기화
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
                    $widejamb = $row["widejamb"] ?? 0;
                    $normaljamb = $row["normaljamb"] ?? 0;
                    $smalljamb = $row["smalljamb"] ?? 0;
                    $memo = $row["memo"] ?? '';
                    $regist_day = $row["regist_day"] ?? '';
                    $update_day = $row["update_day"] ?? '';
                    $demand = $row["demand"] ?? '';
                    $filename1 = $row["filename1"] ?? '';
                    $filename2 = $row["filename2"] ?? '';
                    
                    $imgurl1 = "../imgwork/" . $filename1;
                    $imgurl2 = "../imgwork/" . $filename2;
                    
                    // 합계 계산
                    $sum[0] = $sum[0] + (int)$widejamb;
                    $sum[1] += (int)$normaljamb;
                    $sum[2] += (int)$smalljamb;
                    $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
                    
                    $dis_text = "막판 : " . $sum[0] . " 세트, 막판(無) : " . $sum[1] . " 세트, 쪽쟘 : " . $sum[2] . " 세트, 합계 : " . $sum[3] . " 세트";
                    
                    // 날짜 포맷 처리
                    if ($orderday != "0000-00-00" && $orderday != "1970-01-01" && $orderday != "") {
                        $orderday = date("Y-m-d", strtotime($orderday));
                    } else {
                        $orderday = "";
                    }
                    
                    if ($measureday != "0000-00-00" && $measureday != "1970-01-01" && $measureday != "") {
                        $measureday = date("Y-m-d", strtotime($measureday));
                    } else {
                        $measureday = "";
                    }
                    
                    if ($drawday != "0000-00-00" && $drawday != "1970-01-01" && $drawday != "") {
                        $drawday = date("Y-m-d", strtotime($drawday));
                    } else {
                        $drawday = "";
                    }
                    
                    if ($deadline != "0000-00-00" && $deadline != "1970-01-01" && $deadline != "") {
                        $deadline = date("Y-m-d", strtotime($deadline));
                    } else {
                        $deadline = "";
                    }
                    
                    if ($workday != "0000-00-00" && $workday != "1970-01-01" && $workday != "") {
                        $workday = date("Y-m-d", strtotime($workday));
                    } else {
                        $workday = "";
                    }
                    
                    if ($endworkday != "0000-00-00" && $endworkday != "1970-01-01" && $endworkday != "") {
                        $endworkday = date("Y-m-d", strtotime($endworkday));
                    } else {
                        $endworkday = "";
                    }
                    
                    if ($demand != "0000-00-00" && $demand != "1970-01-01" && $demand != "") {
                        $demand = date("Y-m-d", strtotime($demand));
                    } else {
                        $demand = "";
                    }
                    
                    if ($startday != "0000-00-00" && $startday != "1970-01-01" && $startday != "") {
                        $startday = date("Y-m-d", strtotime($startday));
                    } else {
                        $startday = "";
                    }
                    
                    if ($testday != "0000-00-00" && $testday != "1970-01-01" && $testday != "") {
                        $testday = date("Y-m-d", strtotime($testday));
                    } else {
                        $testday = "";
                    }
                    
                    // 상태 표시
                    $state_work = 0;
                    if (isset($row["checkbox"]) && $row["checkbox"] == 0) {
                        $state_work = 1;
                    }
                    if (substr($row["workday"] ?? '', 0, 2) == "20") {
                        $state_work = 2;
                    }
                    if (substr($row["endworkday"] ?? '', 0, 2) == "20") {
                        $state_work = 3;
                    }
                    
                    $draw_done = "     ";
                    if (substr($row["drawday"] ?? '', 0, 2) == "20") {
                        $draw_done = "OK";
                    }
                    
                    $measure_done = "     ";
                    if (substr($row["measureday"] ?? '', 0, 2) == "20") {
                        $measure_done = "OK";
                    }
                    
                    if (substr($row["testday"] ?? '', 0, 2) == "20") {
                        $testday = mb_substr($testday, 5, 5, "utf-8");
                    } else {
                        $testday = "    ";
                    }
                    
                    $pic_done = ($filename2 != "") ? "등록" : '';
            ?>
            
            <div class="row">
                <div class="col-1">
                    <h4 class="display-5 font-center text-center"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></h4>
                </div>
                <div class="col-sm-1">
                    <?php
                    switch ($check) {
                        case '1':
                            echo '<h4 class="display-5 font-center text-center text-danger">' . htmlspecialchars(mb_substr($endworkday, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') . '&nbsp;</h4>';
                            break;
                        case '2':
                            echo '<h4 class="display-5 font-center text-center">' . htmlspecialchars(mb_substr($workday, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') . '&nbsp;</h4>';
                            break;
                        default:
                            echo '<h4 class="display-5 font-center text-center">' . htmlspecialchars(mb_substr($orderday, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') . '&nbsp;</h4>';
                            break;
                    }
                    ?>
                </div>
                <div class="col-sm-1">
                    <?php
                    switch ($check) {
                        case '0':
                            echo '<h4 class="display-5 font-center text-center text-danger">' . htmlspecialchars(mb_substr($workday, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') . '&nbsp;</h4>';
                            break;
                        default:
                            echo '<h4 class="display-5 font-center text-center">' . htmlspecialchars(mb_substr($testday, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') . '&nbsp;</h4>';
                            break;
                    }
                    ?>
                </div>
                <div class="col-sm-1">
                    <h4 class="display-5 font-center text-center text-success"><?php echo htmlspecialchars($measure_done, ENT_QUOTES, 'UTF-8'); ?>&nbsp;</h4>
                </div>
                <div class="col-sm-1">
                    <h4 class="display-5 font-center text-center text-danger"><?php echo htmlspecialchars($draw_done, ENT_QUOTES, 'UTF-8'); ?>&nbsp;</h4>
                </div>
                <div class="col-sm-1">
                    <h4 class="display-5 font-center text-center text-secondary"><?php echo htmlspecialchars($pic_done, ENT_QUOTES, 'UTF-8'); ?>&nbsp;</h4>
                </div>
                <div class="col-6">
                    <h3 class="display-5 font-center text-left">
                        <a href="view.php?num=<?php echo urlencode($num); ?>&check=<?php echo urlencode($check); ?>">
                            <?php echo htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8'); ?>
                        </a>&nbsp;
                    </h3>
                </div>
            </div>
            
            <div class="clear"></div>
            
            <?php
                    $start_num--;
                }
            }
            ?>
            
            <br><br>
            
            <div class="row">
                <div class="col-11">
                    <h3 class="display-6 font-center text-center"></h3>
                </div>
            </div>
        </form>
    </div>
    
    <script>
    (function() {
        'use strict';
        
        /**
         * 레벨 체크 팝업
         */
        function check_level() {
            window.open(
                'check_level.php?nick=' + document.member_form.nick.value,
                'NICKcheck',
                'left=200,top=200,width=300,height=100,scrollbars=no,resizable=yes'
            );
        }
        
        $(document).ready(function() {
            // 체크박스 이벤트 핸들러
            $('#without').on('change', function() {
                if ($('#without').is(':checked')) {
                    $('#check').val('1');
                    $('#search').val('');
                    $('#board_form').submit();
                } else {
                    $('#check').val('');
                    $('#search').val('');
                    $('#board_form').submit();
                }
            });
            
            $('#outputlist').on('change', function() {
                if ($('#outputlist').is(':checked')) {
                    $('#output_check').val('1');
                    $('#board_form').submit();
                } else {
                    $('#output_check').val('');
                    $('#board_form').submit();
                }
            });
            
            $('#plan_outputlist').on('change', function() {
                if ($('#plan_outputlist').is(':checked')) {
                    $('#plan_output_check').val('1');
                    $('#search').val('');
                    $('#board_form').submit();
                } else {
                    $('#plan_output_check').val('');
                    $('#search').val('');
                    $('#board_form').submit();
                }
            });
            
            $('#team').on('change', function() {
                if ($('#team').is(':checked')) {
                    $('#team_check').val('1');
                    $('#search').val('');
                    $('#board_form').submit();
                } else {
                    $('#team_check').val('');
                    $('#search').val('');
                    $('#board_form').submit();
                }
            });
            
            $('#notmeasure').on('change', function() {
                if ($('#notmeasure').is(':checked')) {
                    $('#measure_check').val('1');
                    $('#board_form').submit();
                } else {
                    $('#measure_check').val('');
                    $('#board_form').submit();
                }
            });
        });
        
        $('#btn-1').on('click', function() {
            $('#myModal').modal();
        });
    })();
    </script>
</body>
</html>
