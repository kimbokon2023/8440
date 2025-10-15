<?php
/**
 * 미래기업 원자재 관리시스템 - 미입고 자재 목록
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

// 권한 체크
$level = $_SESSION["level"] ?? 999;
$id_name = $_SESSION["name"] ?? '';

if (!isset($_SESSION["level"]) || $level > 7) {
    sleep(2);
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    header("Location: {$protocol}://{$host}/login/logout.php");
    exit;
}

// 기본 변수 초기화
$check = getRequestValue("check", '1');
$search = getRequestValue("search", '');
$separate_date = getRequestValue("separate_date", '');
$list = getRequestValue("list", 0);
$page = getRequestValue("page", 1);
$mode = getRequestValue("mode", '');
$cursort = getRequestValue("cursort", '');
$find = getRequestValue("find", '');

// hidden input 변수들 초기화
$yearcheckbox = getRequestValue("yearcheckbox", '');
$year = getRequestValue("year", '');
$output_check = getRequestValue("output_check", '');
$plan_output_check = getRequestValue("plan_output_check", '');
$team_check = getRequestValue("team_check", '');
$measure_check = getRequestValue("measure_check", '');
$process = getRequestValue("process", '');

// 페이징 설정
$scale = 50;
$page_scale = 15;
$first_num = ($page - 1) * $scale;

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 기타 변수 초기화
$real_count = 0;
$date_font = 'black';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 원자재 관리시스템</title>
    
    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/jexcel.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Alertify -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>
    
    <!-- jExcel -->
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/partner.css" type="text/css" />
    
    <style>
        #panel, #flip {
            padding: 5px;
            text-align: center;
            background-color: #e5eecc;
            border: solid 1px #c3c3c3;
        }
        
        #panel {
            padding: 50px;
            display: none;
        }
    </style>
</head>

<?php
// 미입고 자재 데이터 조회
$sql = "SELECT * FROM mirae8440.request ORDER BY num DESC";

try {
    $stmh = $pdo->query($sql);
    $temp1 = $stmh->rowCount();
} catch (PDOException $ex) {
    error_log("미입고 자재 조회 오류: " . $ex->getMessage());
    $temp1 = 0;
}
?>

<body>
    <div class="container-fluid">
        <br><br>
        
        <div id="top-menu">
            <?php if (!isset($_SESSION["userid"])): ?>
                <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
            <?php else: ?>
                <div class="row">
                    <div class="col-6">
                        <h3 class="display-5 font-center text-left">
                            <?=htmlspecialchars($_SESSION["name"], ENT_QUOTES, 'UTF-8')?> |
                            <a href="../login/logout.php">로그아웃</a> |
                            <a href="../member/updateForm.php?id=<?=htmlspecialchars($_SESSION["userid"], ENT_QUOTES, 'UTF-8')?>">정보수정</a>
                        </h3>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <br>
        
        <button type="button" class="btn btn-success btn-lg" onclick="location.href='../steel/list.php';">PC화면으로 이동</button>&nbsp;
        <button type="button" class="btn btn-secondary btn-lg" onclick="location.href='./index.php?check=1';">전체 원자재 List</button>&nbsp;
        
        <?php
        // 안전한 form action URL 생성
        $form_params = http_build_query([
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
            'measure_check' => $measure_check
        ], '', '&', PHP_QUERY_RFC3986);
        ?>
        
        <form id="board_form" name="board_form" method="get" action="index.php?<?=$form_params?>">
            <br>
            <h1 class="display-3 font-center text-center" style="color:red;">미입고 자재 List</h1>
            <br>
            
            <div class="row">
                <div class="col-2">
                    <h5 class="display-5 font-center text-center">요청일</h5>
                </div>
                <div class="col-3">
                    <h5 class="display-5 font-center text-center">현장명</h5>
                </div>
                <div class="col-2">
                    <h5 class="display-5 font-center text-center">철판종류</h5>
                </div>
                <div class="col-2">
                    <h5 class="display-5 font-center text-center">규격</h5>
                </div>
                <div class="col-1">
                    <h5 class="display-5 font-center text-center">수량</h5>
                </div>
                <div class="col-2">
                    <h5 class="display-5 font-center text-center">납품업체</h5>
                </div>
            </div>
            <br>
            
            <?php
            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
            
            try {
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    $num = $row["num"] ?? '';
                    $outdate = $row["outdate"] ?? '';
                    $indate = $row["indate"] ?? '';
                    $outworkplace = $row["outworkplace"] ?? '';
                    $item = $row["item"] ?? '';
                    $spec = $row["spec"] ?? '';
                    $steelnum = $row["steelnum"] ?? '';
                    $company = $row["company"] ?? '';
                    $comment = $row["comment"] ?? '';
                    $which = $row["which"] ?? '';
                    $model = $row["model"] ?? '';
                    
                    // 날짜 포맷팅
                    if ($indate != "0000-00-00" && !empty($indate)) {
                        $indate = date("Y-m-d", strtotime($indate));
                    } else {
                        $indate = "";
                    }
                    
                    if ($outdate != "0000-00-00" && !empty($outdate)) {
                        $outdate = date("Y-m-d", strtotime($outdate));
                    } else {
                        $outdate = "";
                    }
                    
                    // 요일 추가
                    if (!empty($outdate)) {
                        $outdate = $outdate . $week[date('w', strtotime($outdate))];
                        
                        if ($which == '2' || $which == '1') {
                            $real_count++;
                            ?>
                            <div class="row">
                                <div class="col-2">
                                    <h5 class="display-5 font-center text-center" style="color:<?=$date_font?>;">
                                        <?=htmlspecialchars(mb_substr($outdate, 0, 10), ENT_QUOTES, 'UTF-8')?>
                                    </h5>
                                </div>
                                <div class="col-3">
                                    <h5 class="display-5 font-center text-center">
                                        <?=htmlspecialchars(mb_substr($outworkplace, 0, 20), ENT_QUOTES, 'UTF-8')?>
                                    </h5>
                                </div>
                                <div class="col-2">
                                    <h5 class="display-5 font-center text-center">
                                        <?=htmlspecialchars(mb_substr($item, 0, 20), ENT_QUOTES, 'UTF-8')?>
                                    </h5>
                                </div>
                                <div class="col-2">
                                    <h5 class="display-5 font-center text-center">
                                        <?=htmlspecialchars(mb_substr($spec, 0, 15), ENT_QUOTES, 'UTF-8')?>
                                    </h5>
                                </div>
                                <div class="col-1">
                                    <h5 class="display-5 font-center text-center">
                                        <?=htmlspecialchars(mb_substr($steelnum, 0, 3), ENT_QUOTES, 'UTF-8')?>
                                    </h5>
                                </div>
                                <div class="col-2">
                                    <h5 class="display-5 font-center text-center">
                                        <?=htmlspecialchars(mb_substr($company, 0, 5), ENT_QUOTES, 'UTF-8')?>
                                    </h5>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                
            } catch (PDOException $ex) {
                error_log("데이터 렌더링 오류: " . $ex->getMessage());
                echo "<p>데이터 조회 중 오류가 발생했습니다.</p>";
            }
            ?>
            
            <br><br>
        </form>
    </div> <!-- end of container -->

</body>
</html>