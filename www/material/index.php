<?php
/**
 * 미래기업 원자재 관리시스템
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Alertify -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>
    
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
// 기본 변수 초기화
$search = getRequestValue("search", '');
$separate_date = getRequestValue("separate_date", '1');
$list = getRequestValue("list", 0);
$page = getRequestValue("page", 1);
$mode = getRequestValue("mode", '');
$keyword = getRequestValue("keyword", '');
$cursort = getRequestValue("cursort", '');
$find = getRequestValue("find", '');

// 페이징 설정
$scale = 100;       // 한 페이지에 보여질 게시글 수
$page_scale = 15;   // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;

// hidden input 변수들 초기화
$yearcheckbox = getRequestValue("yearcheckbox", '');
$year = getRequestValue("year", '');
$output_check = getRequestValue("output_check", '');
$plan_output_check = getRequestValue("plan_output_check", '');
$team_check = getRequestValue("team_check", '');
$measure_check = getRequestValue("measure_check", '');
$sqltext = getRequestValue("sqltext", '');

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 날짜 범위 설정
$fromdate = "2019-01-01";  // 고정 시작일
$todate = getRequestValue("todate", '');

if (empty($todate)) {
    $todate = date("Y") . "-12-31";
    $Transtodate = date("Y-m-d", strtotime($todate . ' +1 days'));
} else {
    $Transtodate = date("Y-m-d", strtotime($todate));
}

// 기본 설정
$process = "전체";
$comment_arr = array();
$indate_arr = array();
$num_arr = array(); 

// 원자재 기본 데이터 조회
$sql = "SELECT * FROM mirae8440.steelsource ORDER BY sortorder ASC, item ASC, spec ASC";

$rowNum = 0;
$counter = 0;
$steelsource_num = array();
$steelsource_item = array();
$steelsource_spec = array();
$steelsource_take = array();

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    $counter = 0;
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $counter++;
        
        $steelsource_num[$counter] = $row["num"] ?? '';
        $steelsource_item[$counter] = $row["item"] ?? '';
        $steelsource_spec[$counter] = $row["spec"] ?? '';
        
        $company = $row["take"] ?? '';
        
        // 일반매입처리
        if ($row["take"] == '미래기업' || $row["take"] == '윤스틸') {
            $company = '';
        }
        
        $steelsource_take[$counter] = $company;
        $comment_arr[$counter] = '';
    }
    
} catch (PDOException $ex) {
    error_log("원자재 기본 데이터 조회 오류: " . $ex->getMessage());
    echo "오류: 원자재 데이터를 불러오는 중 문제가 발생했습니다.";
}

// 날짜 기준 설정
$SettingDate = ($separate_date == "1") ? "outdate" : "indate";

// SQL 쿼리 조건 생성 (Prepared Statement용)
$common_where = " WHERE {$SettingDate} BETWEEN ? AND ? ";
$common_order = " ORDER BY {$SettingDate} DESC, num DESC";
$limit_clause = " LIMIT ?, ?";

$a = $common_where . $common_order . $limit_clause;  // 페이징용
$b = $common_where . $common_order;  // 전체
  
// 전체합계 계산을 위한 배열 초기화
$sum_title = array();
$sum = array();

// sum 배열 초기화
for ($i = 1; $i <= $rowNum; $i++) {
    $sum[$i] = 0;
}

// 입고 데이터 집계
$sql = "SELECT * FROM mirae8440.steel" . $b;

try {
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
    $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
    $stmh->execute();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $outworkplace = $row["outworkplace"] ?? '';
        $indate = $row["indate"] ?? '';
        $item = $row["item"] ?? '';
        $spec = $row["spec"] ?? '';
        $steelnum = $row["steelnum"] ?? 0;
        $company = $row["company"] ?? '';
        $comment = $row["comment"] ?? '';
        $which = $row["which"] ?? '';
        
        // 일반매입처리
        if ($company == '미래기업' || $company == '윤스틸') {
            $company = '';
        }
        
        $tmp = $item . $spec . $company;
        
        // 입고 데이터 집계
        for ($i = 1; $i <= $rowNum; $i++) {
            $sum_title[$i] = $steelsource_item[$i] . $steelsource_spec[$i] . $steelsource_take[$i];
            
            if ($which == '1' && $tmp == $sum_title[$i]) {
                $sum[$i] = ($sum[$i] ?? 0) + (int)$steelnum;
                
                if (!empty($comment_arr[$i])) {
                    $comment_arr[$i] .= "/" . $indate . "  " . $outworkplace . "    " . $steelnum . "매";
                } else {
                    $comment_arr[$i] = $indate . "  " . $outworkplace . "    " . $steelnum . "매";
                }
            }
        }
    }
    
} catch (PDOException $ex) {
    error_log("입고 데이터 집계 오류: " . $ex->getMessage());
}

// 출고 데이터 집계
try {
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
    $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
    $stmh->execute();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $item = $row["item"] ?? '';
        $spec = $row["spec"] ?? '';
        $steelnum = $row["steelnum"] ?? 0;
        $company = $row["company"] ?? '';
        $which = $row["which"] ?? '';
        
        // 일반매입처리
        if ($company == '미래기업' || $company == '윤스틸') {
            $company = '';
        }
        
        $tmp = $item . $spec . $company;
        
        // 출고 데이터 집계
        for ($i = 1; $i <= $rowNum; $i++) {
            $sum_title[$i] = $steelsource_item[$i] . $steelsource_spec[$i] . $steelsource_take[$i];
            
            if ($which == '2' && $tmp == $sum_title[$i]) {
                $sum[$i] = ($sum[$i] ?? 0) - (int)$steelnum;
            }
        }
    }
    
} catch (PDOException $ex) {
    error_log("출고 데이터 집계 오류: " . $ex->getMessage());
}

// 목록 조회용 SQL
$sql = "SELECT * FROM mirae8440.steel" . $a;
$sqlcon = "SELECT * FROM mirae8440.steel" . $b;

// 현재일자
$nowday = date("Y-m-d");

// 페이징 처리
$total_row = 0;
$total_page = 0;
$current_page = 0;
$regist_state = "1";
$date_font = "black";
$font = "black";
$outdate = '';

try {
    // 전체 레코드 수 파악
    $allstmh = $pdo->prepare($sqlcon);
    $allstmh->bindValue(1, $fromdate, PDO::PARAM_STR);
    $allstmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
    $allstmh->execute();
    $total_row = $allstmh->rowCount();
    
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
    
} catch (PDOException $ex) {
    error_log("페이징 처리 오류: " . $ex->getMessage());
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
        <button type="button" class="btn btn-primary btn-lg" onclick="location.href='./notinputlist.php?check=2';">미입고 자재 List</button>&nbsp;
        
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
            <h1 class="display-3 font-center text-center">미래기업 원자재 List</h1>
            <br>
            <h3 class="display-4 font-center text-center text-danger">(특수소재는 현장에 있는 실제 철판 수량 확인 후 작업하세요.)</h3>
            <br>
            
            <div class="row">
                <button type="button" class="btn btn-dark btn-lg" onclick="location.href='index.php?keyword='">전체</button>&nbsp;
                <button type="button" class="btn btn-danger btn-lg" onclick="location.href='index.php?keyword=CR'">CR</button>&nbsp;
                <button type="button" class="btn btn-warning btn-lg" onclick="location.href='index.php?keyword=PO'">PO</button>&nbsp;
                <button type="button" class="btn btn-info btn-lg" onclick="location.href='index.php?keyword=EGI'">EGI</button>&nbsp;
                <button type="button" class="btn btn-success btn-lg" onclick="location.href='index.php?keyword=304 HL'">304 HL</button>&nbsp;
                <button type="button" class="btn btn-secondary btn-lg" onclick="location.href='index.php?keyword=304 MR'">304 MR</button>&nbsp;
                <button type="button" class="btn btn-primary btn-lg" onclick="location.href='index.php?keyword=ETC'">특수소재</button>&nbsp;
            </div>	
	
<br>			  

            <br>
            
            <div class="row">
                <div class="col-1">
                    <h2 class="display-5 font-center text-center">번호</h2>
                </div>
                <div class="col-3">
                    <h2 class="display-5 font-center text-center">종류</h2>
                </div>
                <div class="col-3">
                    <h2 class="display-5 font-center text-center">SPEC(TxWxH)</h2>
                </div>
                <div class="col-1">
                    <h2 class="display-5 font-center text-center">수량</h2>
                </div>
                <div class="col-2">
                    <h2 class="display-5 font-center text-center">업체</h2>
                </div>
                <div class="col-2">
                    <h2 class="display-5 font-center text-center">비고</h2>
                </div>
            </div>
            <br>
            
            <?php
            $real_count = 0;
            
            for ($i = 1; $i <= $rowNum; $i++) {
                $item_sum = 0;
                $number = $i;
                $item = $steelsource_item[$i] ?? '';
                $spec = $steelsource_spec[$i] ?? '';
                $take = $steelsource_take[$i] ?? '';
                $item_sum = $sum[$i] ?? 0;
                
                if ($item_sum > 0) {
                    $real_count++;
                    
                    $item_prefix = substr(trim($item), 0, 6);
                    
                    // 키워드 필터링
                    $should_display = false;
                    if (empty($keyword)) {
                        $should_display = true;
                    } elseif ($keyword == 'ETC') {
                        $excluded = array('EGI', 'CR', 'PO', '304 HL', '304 MR');
                        $should_display = !in_array($item_prefix, $excluded);
                    } else {
                        $should_display = ($item_prefix == $keyword);
                    }
                    
                    if ($should_display) {
                        $etc = ($keyword == 'ETC') ? ($comment_arr[$i] ?? '') : '';
                        $etc_safe = htmlspecialchars($etc, ENT_QUOTES, 'UTF-8');
                        $etc_param = urlencode($etc);
                        ?>
                        <div class="row">
                            <div class="col-1">
                                <h2 class="display-5 font-center text-center"><?=$real_count?></h2>
                            </div>
                            <div class="col-3">
                                <h2 class="display-5 font-center text-center"><?=htmlspecialchars($item, ENT_QUOTES, 'UTF-8')?></h2>
                            </div>
                            <div class="col-3">
                                <h2 class="display-5 font-center text-center"><?=htmlspecialchars($spec, ENT_QUOTES, 'UTF-8')?></h2>
                            </div>
                            <div class="col-1">
                                <h2 class="display-5 font-center text-center"><?=htmlspecialchars($item_sum, ENT_QUOTES, 'UTF-8')?></h2>
                            </div>
                            <div class="col-2">
                                <h2 class="display-5 font-center text-center"><?=htmlspecialchars($take, ENT_QUOTES, 'UTF-8')?></h2>
                            </div>
                            <div class="col-2">
                                <h2 class="display-5 font-center text-center">
                                    <a href="#" onclick="window.open('viewinput.php?etc=<?=$etc_param?>', '철판 입고현장 보기', 'left=10,top=10,scrollbars=yes,toolbars=no,width=1400,height=700'); return false;">
                                        <?=mb_substr($etc_safe, 0, 8)?>&nbsp;
                                    </a>
                                </h2>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>		
  
			
			
            <br><br>
            
            <div class="row">
                <h4 class="display-4 text-left">
                    <div id="flip">&nbsp;&nbsp; 오늘도 수고 많았습니다.</div>
                    <div id="panel">고생한 당신이 오늘의 주인공입니다.</div>
                </h4>
            </div>
            
            <br><br>
            
            <input type="hidden" id="check" name="check" value="<?=htmlspecialchars($check, ENT_QUOTES, 'UTF-8')?>">
            <input type="hidden" id="output_check" name="output_check" value="<?=htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8')?>">
            <input type="hidden" id="measure_check" name="measure_check" value="<?=htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8')?>">
            <input type="hidden" id="sqltext" name="sqltext" value="<?=htmlspecialchars($sqltext, ENT_QUOTES, 'UTF-8')?>">
            
            <div id="list_search4"></div>
            <div id="list_search5"></div>
            <div id="list_search11"></div>
            <div id="list_search12"></div>
            
            <div class="row">
                <div class="col-2">
                    <h5 class="display-5 font-center text-center">입출고일</h5>
                </div>
                <div class="col-1">
                    <h5 class="display-5 font-center text-center">구분</h5>
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
            </div>
            <br><br>
            
                    <?php
            $start_num = ($page <= 1) ? $total_row : $total_row - ($page - 1) * $scale;
            
            try {
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
                $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
                $stmh->bindValue(3, $first_num, PDO::PARAM_INT);
                $stmh->bindValue(4, $scale, PDO::PARAM_INT);
                $stmh->execute();
                
                $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                
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
                    
                    // 날짜에 요일 추가
                    if (!empty($outdate)) {
                        $outdate = $outdate . $week[date('w', strtotime($outdate))];
                    }
                    
                    // 현재 날짜 강조
                    $date_font = ($nowday == substr($outdate, 0, 10)) ? "red" : "black";
                    
                    // 입고/출고 구분
                    if ($which == '1') {
                        $tmp_word = "입고";
                        $font_state = "blue";
                    } else {
                        $tmp_word = "출고";
                        $font_state = "red";
                    }
                    ?>
                    <div class="row">
                        <div class="col-2">
                            <h5 class="display-5 font-center text-center" style="color:<?=$date_font?>;">
                                <?=htmlspecialchars(mb_substr($outdate, 0, 10), ENT_QUOTES, 'UTF-8')?>
                            </h5>
                        </div>
                        <div class="col-1">
                            <h5 class="display-5 font-center text-center" style="color:<?=$font_state?>;">
                                <?=htmlspecialchars($tmp_word, ENT_QUOTES, 'UTF-8')?>
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
                    </div>
                    <div class="clear"></div>
                    <?php
                    $start_num--;
                }
                
            } catch (PDOException $ex) {
                error_log("목록 조회 오류: " . $ex->getMessage());
                echo "<p>데이터 조회 중 오류가 발생했습니다.</p>";
            }
            ?>
            
            <br><br>
        </form>
    </div> <!-- end of container -->     
  
<script>
'use strict';

// 레벨 체크
function check_level() {
    var memberForm = document.member_form;
    if (memberForm && memberForm.nick) {
        window.open(
            "check_level.php?nick=" + encodeURIComponent(memberForm.nick.value),
            "NICKcheck",
            "left=200,top=200,width=300,height=100,scrollbars=no,resizable=yes"
        );
    }
}

$(document).ready(function() {
    // 체크박스 변경 이벤트들
    $("#without").change(function() {
        if ($("#without").is(":checked")) {
            $('#check').val('1');
            $('#search').val('');
        } else {
            $('#check').val('');
            $('#search').val('');
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
            $('#search').val('');
        } else {
            $('#plan_output_check').val('');
            $('#search').val('');
        }
        $('#board_form').submit();
    });
    
    $("#team").change(function() {
        if ($("#team").is(":checked")) {
            $('#team_check').val('1');
            $('#search').val('');
        } else {
            $('#team_check').val('');
            $('#search').val('');
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

</script>

</body>

<script>
// 깜빡임 효과
function blinker() {
    $('.blinking').fadeOut(700);
    $('.blinking').fadeIn(700);
}
setInterval(blinker, 1500);

// Datepicker 초기화
$(function() {
    $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#todate").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#up_fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#up_todate").datepicker({ dateFormat: 'yy-mm-dd' });
});
 
// 날짜 포맷 헬퍼 함수
function formatDate(year, month, day) {
    return year + '-' + (month < 10 ? '0' + month : month) + '-' + (day < 10 ? '0' + day : day);
}

// 전년도 추출 (윗쪽)
function up_pre_year() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var yyyy = today.getFullYear() - 1;
    var frompreyear = yyyy + '-01-01';
    var topreyear = yyyy + '-12-31';
    
    var upFromdateElem = document.getElementById("up_fromdate");
    var upTodateElem = document.getElementById("up_todate");
    var viewTableElem = document.getElementById('view_table');
    
    if (upFromdateElem) upFromdateElem.value = frompreyear;
    if (upTodateElem) upTodateElem.value = topreyear;
    if (viewTableElem) viewTableElem.value = "search";
    
    document.getElementById('board_form').submit();
}  
 
// 전년도 추출 (아래쪽)
function pre_year() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var yyyy = today.getFullYear() - 1;
    
    document.getElementById("fromdate").value = yyyy + '-01-01';
    document.getElementById("todate").value = yyyy + '-12-31';
    document.getElementById('board_form').submit();
}

// 전월 추출 (윗쪽)
function up_pre_month() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var mm = today.getMonth();  // 0-11
    var yyyy = today.getFullYear();
    
    if (mm === 0) {
        mm = 12;
        yyyy--;
    }
    
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    
    document.getElementById("up_fromdate").value = yyyy + '-' + mmStr + '-01';
    document.getElementById("up_todate").value = yyyy + '-' + mmStr + '-31';
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

// 전월 추출 (아래쪽)
function pre_month() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var mm = today.getMonth();
    var yyyy = today.getFullYear();
    
    if (mm === 0) {
        mm = 12;
        yyyy--;
    }
    
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    
    document.getElementById("fromdate").value = yyyy + '-' + mmStr + '-01';
    document.getElementById("todate").value = yyyy + '-' + mmStr + '-31';
    document.getElementById('board_form').submit();
}

// 당해년도 (윗쪽)
function up_this_year() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var yyyy = new Date().getFullYear();
    
    document.getElementById("up_fromdate").value = yyyy + '-01-01';
    document.getElementById("up_todate").value = yyyy + '-12-31';
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

// 당해년도 (아래쪽)
function this_year() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var yyyy = new Date().getFullYear();
    
    document.getElementById("fromdate").value = yyyy + '-01-01';
    document.getElementById("todate").value = yyyy + '-12-31';
    document.getElementById('board_form').submit();
}

// 당해월 (윗쪽)
function up_this_month() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    
    document.getElementById("up_fromdate").value = yyyy + '-' + mmStr + '-01';
    document.getElementById("up_todate").value = yyyy + '-' + mmStr + '-31';
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

// 당해월 (아래쪽)
function this_month() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    
    document.getElementById("fromdate").value = yyyy + '-' + mmStr + '-01';
    document.getElementById("todate").value = yyyy + '-' + mmStr + '-31';
    document.getElementById('board_form').submit();
}

// 익일 이후
function From_tomorrow() {
    var today = new Date();
    var dd = today.getDate() + 1;
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var ddStr = dd < 10 ? '0' + dd : '' + dd;
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    
    document.getElementById("fromdate").value = yyyy + '-' + mmStr + '-' + ddStr;
    document.getElementById("todate").value = yyyy + '-12-31';
    document.getElementById('board_form').submit();
}

// 금일 이후
function Fromthis_today() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var ddStr = dd < 10 ? '0' + dd : '' + dd;
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    
    document.getElementById("fromdate").value = yyyy + '-' + mmStr + '-' + ddStr;
    document.getElementById("todate").value = yyyy + '-12-31';
    document.getElementById('board_form').submit();
}

// 금일 (윗쪽)
function up_this_today() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var ddStr = dd < 10 ? '0' + dd : '' + dd;
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    var dateStr = yyyy + '-' + mmStr + '-' + ddStr;
    
    document.getElementById("up_fromdate").value = dateStr;
    document.getElementById("up_todate").value = dateStr;
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

// 금일 (아래쪽)
function this_today() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var ddStr = dd < 10 ? '0' + dd : '' + dd;
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    var dateStr = yyyy + '-' + mmStr + '-' + ddStr;
    
    document.getElementById("fromdate").value = dateStr;
    document.getElementById("todate").value = dateStr;
    document.getElementById('board_form').submit();
}

// 익일
function this_tomorrow() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    
    var today = new Date();
    var dd = today.getDate() + 1;
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var ddStr = dd < 10 ? '0' + dd : '' + dd;
    var mmStr = mm < 10 ? '0' + mm : '' + mm;
    var dateStr = yyyy + '-' + mmStr + '-' + ddStr;
    
    document.getElementById("fromdate").value = dateStr;
    document.getElementById("todate").value = dateStr;
    document.getElementById('board_form').submit();
}

// 접수일/출고일 라디오버튼 클릭
function process_list() {
    var searchElem = document.getElementById('search');
    if (searchElem) searchElem.value = '';
    document.getElementById('board_form').submit();
}

// 출고현황 검색 클릭
function exe_view_table() {
    document.getElementById('view_table').value = "search";
    document.getElementById('board_form').submit();
}

// 숫자 포맷팅
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

// 패널 토글
$(document).ready(function() {
    $("#flip").click(function() {
        $("#panel").slideToggle();
    });
    
    $("#panel").click(function() {
        $("#panel").slideUp("slow");
    });
});

</script>

</body>
</html>