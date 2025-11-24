
<?php
/**
 * 도장발주 목록 페이지
 * 도장 발주서의 목록을 조회하고 검색하는 페이지
 */

session_start();

// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

// 세션 변수 안전하게 초기화
$level = $_SESSION["level"] ?? '';
$id_name = $_SESSION["name"] ?? '';

if (!isset($_SESSION["level"]) || $level > 7) {
    /*   alert("관리자 승인이 필요합니다."); */
    sleep(2);
    header("Location: {$base_url}/login/logout.php");
    exit;
}

// 요청 변수 안전하게 초기화
$search = $_REQUEST["search"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;
$scale = $_REQUEST["scale"] ?? 20;
$mode = $_REQUEST["mode"] ?? '';
$cursort = $_REQUEST["cursort"] ?? 0;
$sortof = $_REQUEST["sortof"] ?? 0;
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$find = $_REQUEST["find"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';

require_once("../lib/mydb.php");
$pdo = db_connect();

$page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.

// 정렬 모드 처리
if ($sortof == 1) {
    if ($cursort != 1) {
        $cursort = 1;
    } else {
        $cursort = 2;
    }
}
if ($sortof == 2) {  // 접수일 클릭되었을때
    if ($cursort != 3) {
        $cursort = 3;
    } else {
        $cursort = 4;
    }
}
if ($sortof == 3) {  // 구분 클릭되었을때
    if ($cursort != 5) {
        $cursort = 5;
    } else {
        $cursort = 6;
    }
}
if ($sortof == 4) {  // 절곡 클릭되었을때
    if ($cursort != 7) {
        $cursort = 7;
    } else {
        $cursort = 8;
    }
}
if ($sortof == 5) {  // 모터 클릭되었을때
    if ($cursort != 9) {
        $cursort = 9;
    } else {
        $cursort = 10;
    }
}

if ($separate_date == "") {
    $separate_date = "1";
}

// 기간을 정하는 구간
if ($fromdate == "") {
    $fromdate = "2020-01-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$process = "전체";  // 기본 전체로 정한다.

if ($separate_date == "1") {
    $SettingDate = "orderdate ";
} else {
    $SettingDate = "indate ";
}

$common = " where {$SettingDate} between date('{$fromdate}') and date('{$Transtodate}') order by {$SettingDate}";
$a = "{$common} desc, num desc limit {$first_num}, {$scale}";  // 내림차순
$b = "{$common} desc, num desc";  // 내림차순 전체
$c = "{$common} asc, num desc limit {$first_num}, {$scale}";  // 오름차순
$d = "{$common} asc, num desc";  // 오름차순 전체

$where = " where {$SettingDate} between date('{$fromdate}') and date('{$Transtodate}')";
$all = " limit {$first_num}, {$scale}";

// 초기화
$sql = '';
$sqlcon = '';

if ($mode == "search") {
    if ($search == "") {
        $sql = "select * from mirae8440.make {$a}";
        $sqlcon = "select * from mirae8440.make {$b}";  // 전체 레코드수를 파악하기 위함.
    } elseif ($search != "") {  // 각 필드별로 검색어가 있는지 쿼리주는 부분
        $sql = "select * from mirae8440.make where (orderdate like '%{$search}%') or (text like '%{$search}%')";
        $sql .= " or (indate like '%{$search}%') or (company like '%{$search}%')";
        $sql .= " order by {$SettingDate} desc, num desc limit {$first_num}, {$scale}";
        $sqlcon = "select * from mirae8440.make where (orderdate like '%{$search}%') or (text like '%{$search}%')";
        $sqlcon .= " or (indate like '%{$search}%') or (company like '%{$search}%')";
        $sqlcon .= " order by {$SettingDate} desc, num desc";
    }
}

if ($mode == "") {
    $sql = "select * from mirae8440.make {$a}";
    $sqlcon = "select * from mirae8440.make {$b}";  // 전체 레코드수를 파악하기 위함.
}

if ($cursort == 1) {
    $sql = "select * from mirae8440.make {$c}";
    $sqlcon = "select * from mirae8440.make {$d}";
}

if ($cursort == 2) {
    $sql = "select * from mirae8440.make {$a}";
    $sqlcon = "select * from mirae8440.make {$b}";
}

if ($cursort == 3) {  // 접수일 클릭시 정렬
    $sql = "select * from mirae8440.make {$where} order by indate desc {$all}";
    $sqlcon = "select * from mirae8440.make {$where} order by indate desc, num desc";
}

if ($cursort == 4) {  // 접수일 클릭시 정렬
    $sql = "select * from mirae8440.make {$where} order by indate asc {$all}";
    $sqlcon = "select * from mirae8440.make {$where} order by indate asc, num desc";
}

if ($cursort == 5) {  // 발주처 클릭시
    $sql = "select * from mirae8440.make {$where} order by company desc, {$SettingDate} desc, num desc {$all}";
    $sqlcon = "select * from mirae8440.make {$where} order by company desc, {$SettingDate} desc, num desc";
}

if ($cursort == 6) {  // 발주처 클릭시
    $sql = "select * from mirae8440.make {$where} order by company asc, {$SettingDate} desc, num desc {$all}";
    $sqlcon = "select * from mirae8440.make {$where} order by company asc, {$SettingDate} desc, num desc";
}

$nowday = date("Y-m-d");  // 현재일자 변수지정

// 초기화
$total_row = 0;
$total_page = 0;
$current_page = 1;
$temp1 = 0;
$temp2 = 0;

try {
    $allstmh = $pdo->query($sqlcon);  // 검색 조건에 맞는 쿼리 전체 개수
    $temp2 = $allstmh->rowCount();
    $stmh = $pdo->query($sql);  // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();

    $total_row = $temp2;  // 전체 글수

    $total_page = ceil($total_row / $scale);  // 검색 전체 페이지 블록 수
    $current_page = ceil($page / $page_scale);  // 현재 페이지 블록 위치계산
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/jexcel.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    <link rel="stylesheet" href="../css/partner.css" type="text/css"/>
    <title>미래기업 도장발주</title>
</head>
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

    /* 모바일 최적화 */
    @media (max-width: 768px) {
        /* body와 html의 width 제한 */
        html, body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            font-size: 16px !important;
        }

        /* 컨테이너 모바일 최적화 */
        .container,
        .container-fluid {
            max-width: 100vw !important;
            padding: 10px !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        /* 카드 모바일 최적화 */
        .card {
            margin: 0.5rem 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        .card-body {
            padding: 0.75rem 0.5rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        /* 헤더 모바일 최적화 */
        .display-5, .display-4, .display-3, h2, h3, h4, h5 {
            font-size: 0.9rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            max-width: 100% !important;
        }

        /* 검색 영역 모바일 최적화 */
        .row {
            margin-left: -5px !important;
            margin-right: -5px !important;
            flex-wrap: wrap !important;
        }

        .row > [class*="col-"] {
            padding-left: 5px !important;
            padding-right: 5px !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            flex: 0 0 100% !important;
            margin-bottom: 0.5rem !important;
        }

        /* 검색 영역 특정 컬럼 조정 */
        .row > .col-4:first-child {
            flex: 0 0 100% !important;
            margin-bottom: 0.5rem !important;
        }

        .row > .col-4:nth-child(2) {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            max-width: calc(100% - 80px) !important;
            margin-bottom: 0 !important;
        }

        .row > .col-2:last-child {
            flex: 0 0 auto !important;
            max-width: 80px !important;
            margin-bottom: 0 !important;
        }

        /* 검색 입력 필드 모바일 최적화 */
        .inputcontainer {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .inputcontainer .input,
        #search {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            font-size: 0.9rem !important;
            padding: 0.5rem !important;
        }

        .input {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            font-size: 0.9rem !important;
            padding: 0.5rem !important;
        }

        /* 버튼 모바일 최적화 */
        .btn {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.6rem !important;
            white-space: nowrap !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            width: 100% !important;
        }

        .btn-lg {
            font-size: 0.85rem !important;
            padding: 0.45rem 0.65rem !important;
        }

        /* 천장발주 버튼 모바일 최적화 */
        .btn-success.btn-lg {
            width: 100% !important;
            max-width: 100% !important;
            margin-bottom: 0.5rem !important;
            box-sizing: border-box !important;
        }

        /* 리스트 항목 모바일 최적화 - 카드 형식 */
        .list-item-card {
            background: #fff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            padding: 0.75rem !important;
            margin-bottom: 0.75rem !important;
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .list-item-card .col-2,
        .list-item-card .col-9 {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            box-sizing: border-box !important;
        }

        .list-item-card .col-2 {
            border-bottom: 1px solid #f0f0f0 !important;
            margin-bottom: 0.5rem !important;
            padding-bottom: 0.75rem !important;
        }

        .list-item-card .col-2 h3 {
            font-size: 0.85rem !important;
            margin: 0 !important;
            text-align: left !important;
        }

        .list-item-card .col-9 h2 {
            font-size: 0.9rem !important;
            margin: 0 !important;
            line-height: 1.5 !important;
        }

        .list-item-card .col-9 a {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            display: block !important;
            color: #007bff !important;
            text-decoration: none !important;
        }

        /* 페이지네이션 모바일 최적화 */
        #page_button {
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        #page_button a {
            font-size: 0.8rem !important;
            padding: 0.3rem 0.5rem !important;
            margin: 0.1rem !important;
            display: inline-block !important;
            box-sizing: border-box !important;
            word-wrap: break-word !important;
        }

        /* 모든 요소가 컨테이너 내부에 머물도록 */
        * {
            box-sizing: border-box !important;
        }

        .container-fluid,
        .container-fluid *,
        .row,
        .row * {
            max-width: 100% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 폼 요소 모바일 최적화 */
        form {
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        form * {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 상단 메뉴 모바일 최적화 */
        #top-menu {
            padding: 0.5rem !important;
            font-size: 0.85rem !important;
        }

        #top-menu a {
            font-size: 0.85rem !important;
            padding: 0.25rem 0.5rem !important;
            display: inline-block !important;
            word-wrap: break-word !important;
        }

        /* 날짜 입력 필드 모바일 최적화 */
        input[type="date"],
        input[type="text"] {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            font-size: 0.9rem !important;
            padding: 0.5rem !important;
        }
    }
</style>

<script>
$(document).ready(function() {
    $("input:radio[name=separate_date]").click(function() {
        process_list();
    })
});
</script>

<body>
<div class="container-fluid">
    <div id="top-menu">
        <?php
        if (!isset($_SESSION["userid"])) {
        ?>
            <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
        <?php
        } else {
        ?>
            <div class="row">
                <div class="col-6">
                    <h3 class="display-5 font-center text-left">
                        <?= $_SESSION["name"] ?? '' ?> |
                        <a href="../login/logout.php">로그아웃</a> |
                        <a href="../member/updateForm.php?id=<?= $_SESSION["userid"] ?? '' ?>">정보수정</a>
                    </h3>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <br>
    <form name="board_form" id="board_form" method="post" action="index.php?mode=search&search=<?= $search ?>&find=<?= $find ?>&year=<?= $year ?>&search=<?= $search ?>&process=<?= $process ?>&asprocess=<?= $asprocess ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&separate_date=<?= $separate_date ?>&scale=10000">

        <br>
        <button type="button" class="btn btn-success btn-lg" onclick="location.href='../mceiling/list.php';">천장발주화면으로 이동</button>&nbsp;&nbsp;&nbsp;
        <div class="clear"></div>
        <br>
        <br>

        <div class="row">
            <h4 class="display-4 text-left">
                <div id="flip">&nbsp;&nbsp; 도장발주 리스트</div>
                <div id="panel">오늘도 고생 많으셨어요~~</div>
            </h4>
        </div>
        <br><br>
        <div class="clear"></div>

        <div class="row">
            <div class="col-4">
                <h3 class="display-5 text-left">
                    총 : <span style="color:brown;"><?= $total_row ?></span> 개 자료 검색됨.
                </h3>
            </div>
            <div class="col-4">
                <h4 class="display-4 font-center text-center">
                    <div class="inputcontainer">
                        <input type="text" id="search" name="search" value="<?= $search ?>" size="8" class="input" placeholder="검색어">
                    </div>
                </h4>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-dark btn-lg" onclick="document.getElementById('board_form').submit();">검색</button>
            </div>
        </div>
        <div class="row"></div>
        <div class="clear"></div>
        <div class="row">
            <div class="col-2">
                <h4 class="display-5 font-center text-center">&nbsp; &nbsp;&nbsp;발주일자</h4>
            </div>
            <div class="col">
                <h4 class="display-5 font-center text-center">(현장명,색상 등) 발주내용</h4>
            </div>
        </div>

<?php
    // 초기화
    $start_num = 0;

    if ($page <= 1) {
        $start_num = $total_row;  // 페이지당 표시되는 첫번째 글순번
    } else {
        $start_num = $total_row - ($page - 1) * $scale;
    }

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $orderdate = $row["orderdate"] ?? '';
        $indate = $row["indate"] ?? '';
        $company = $row["company"] ?? '';
        $text = $row["text"] ?? '';

        $text = str_replace(",", " ", $text);
        $text = str_replace("|", " ", $text);
        $sumStr = $text;

        $date_font = "black";  // 현재일자 Red 색상으로 표기
        if ($nowday == $orderdate) {
            $date_font = "red";
        }

        if ($orderdate != "") {
            $week = ["(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)"];
            $orderdate = $orderdate . $week[date('w', strtotime($orderdate))];
        }

        $sqlTemp = "select * from mirae8440.make where num='{$num}' order by num desc";  // 처음 내림차순
        ?>
        <div class="row list-item-card">
            <div class="col-2">
                <h3 class="display-5 font-center text-center" style="color:<?= $date_font ?>;">
                    <b><?= substr($orderdate, 0, 15) ?></b>
                </h3>
            </div>

            <div class="col-9">
                <h2 class="display-5">
                    <a href="view.php?num=<?= $num ?>&page=<?= $page ?>&find=<?= $find ?>&search=<?= $search ?>&process=<?= $process ?>&asprocess=<?= $asprocess ?>&yearcheckbox=<?= $yearcheckbox ?>&year=<?= $year ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&separate_date=<?= $separate_date ?>">
                        <?= iconv_substr($sumStr, 0, 130, "utf-8") ?>
                    </a>
                </h2>
            </div>
        </div>
        <div class="clear"></div>

        <?php
        $start_num--;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
$start_page = ($current_page - 1) * $page_scale + 1;
// 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
$end_page = $start_page + $page_scale - 1;
?>

<div id="page_button">
    <div id="vacancy" style="display:none"></div>
    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <div class="row">
        <div class="col">
            <h5 class="display-3 font-center text-center">
                <?php
                if ($page != 1 && $page > $page_scale) {
                    $prev_page = $page - $page_scale;
                    // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
                    if ($prev_page <= 0) {
                        $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
                    }
                    print "<a href=index.php?page={$prev_page}&mode=search&search={$search}&find={$find}&list=1&process={$process}&asprocess={$asprocess}&yearcheckbox={$yearcheckbox}&year={$year}>◀ </a>";
                }

                for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                    // [1][2][3] 페이지 번호 목록 출력
                    if ($page == $i) {  // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                        print "<font color=red><b>[{$i}]</b></font>";
                    } else {
                        print "<a href=index.php?page={$i}&mode=search&search={$search}&find={$find}&list=1&process={$process}&asprocess={$asprocess}&yearcheckbox={$yearcheckbox}&year={$year}>[{$i}]</a>";
                    }
                }

                if ($page < $total_page) {
                    $next_page = $page + $page_scale;
                    if ($next_page > $total_page) {
                        $next_page = $total_page;
                    }
                    // next_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
                    print "<a href=index.php?page={$next_page}&mode=search&search={$search}&find={$find}&list=1&process={$process}&asprocess={$asprocess}&yearcheckbox={$yearcheckbox}&year={$year}> ▶</a><p>";
                }
                ?>
            </h5>
        </div>
    </div>

</div>
</form>
</div> <!-- end of col2 -->
</div> <!-- end of content -->
</div> <!-- end of wrap -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    $(function() {
        $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#todate").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    function pre_year() {  // 전년도 추출
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;  // January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }

        today = mm + '/' + dd + '/' + yyyy;
        yyyy = yyyy - 1;
        var frompreyear = yyyy + '-01-01';
        var topreyear = yyyy + '-12-31';

        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function pre_month() {
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;  // January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        mm = mm - 1;
        if (mm < 1) {
            mm = '12';
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        if (mm >= 12) {
            yyyy = yyyy - 1;
        }

        var frompreyear = yyyy + '-' + mm + '-01';
        var topreyear = yyyy + '-' + mm + '-31';

        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function this_year() {  // 당해년도
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;  // January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        today = mm + '/' + dd + '/' + yyyy;
        var frompreyear = yyyy + '-01-01';
        var topreyear = yyyy + '-12-31';

        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        var fromdate1 = frompreyear;
        var todate1 = topreyear;
        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function this_month() {  // 당해월
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;  // January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        var frompreyear = yyyy + '-' + mm + '-01';
        var topreyear = yyyy + '-' + mm + '-31';

        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function From_tomorrow() {  // 익일 이후
        var today = new Date();
        var dd = today.getDate() + 1;  // 하루를 더해준다. 익일
        var mm = today.getMonth() + 1;  // January is 0! 항상 1을 더해야 해당월을 구한다
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-12-31';
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function Fromthis_today() {  // 금일이후
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;  // January is 0! 항상 1을 더해야 해당월을 구한다
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-12-31';

        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;

        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function this_today() {  // 금일
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;  // January is 0! 항상 1을 더해야 해당월을 구한다
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-' + mm + '-' + dd;

        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;

        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function this_tomorrow() {  // 익일
        document.getElementById('search').value = null;
        var today = new Date();
        var dd = today.getDate() + 1;
        var mm = today.getMonth() + 1;  // January is 0! 항상 1을 더해야 해당월을 구한다
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        var frompreyear = yyyy + '-' + mm + '-' + dd;
        var topreyear = yyyy + '-' + mm + '-' + dd;

        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

    function process_list() {  // 접수일 발주일 라디오버튼 클릭시
        document.getElementById('search').value = null;
        document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
    }

</script>
<?php
if ($mode == "" && $fromdate == null) {
    echo ("<script language=javascript> this_year();</script>");  // 당해년도 화면에 초기세팅하기
}
?>
</body>
<script>
    $(document).ready(function() {
        $("#flip").click(function() {
            $("#panel").slideToggle();
        });
    });

    $(document).ready(function() {
        $("#panel").click(function() {
            $("#panel").slideUp("slow");
        });
    });
</script>
</html>
