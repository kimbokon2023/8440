<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

$tablename = "eworks";
?>

<?php
include getDocumentRoot() . '/load_header.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$menu = $_REQUEST["menu"] ?? '';

// 권한 체크 (로컬과 서버 환경에 따라 다르게 처리)
if (!isset($_SESSION["level"])) {
    $isLocal = (
        isset($_SERVER['HTTP_HOST']) && 
        (
            $_SERVER['HTTP_HOST'] === 'localhost' ||
            $_SERVER['HTTP_HOST'] === '127.0.0.1'
        )
    );

    if ($isLocal) {
        // 로컬 개발환경: 로컬 로그인 페이지로 리디렉션
        $_SESSION["url"] = 'http://8440.local/absent/index.php?user_name=' . $user_name;
        sleep(1);
        header("Location:http://8440.local/login/login_form.php");
        exit;
    } else {
        // 서버 환경: 서버 사이트로 리디렉션
        $_SESSION["url"] = 'https://8440.co.kr/absent/index.php?user_name=' . $user_name;
        sleep(1);
        header("Location:https://8440.co.kr/login/logout.php");
        exit;
    }
}
?>

<title>공장 근태</title>

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .d-flex.mt-3.mb-3.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-3.justify-content-center.align-items-center h5 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        .d-flex.mt-3.mb-3.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 년월 설정 영역 최적화 */
        .d-flex.mt-3.mb-3.justify-content-center.align-items-center:has(select) {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-3.justify-content-center.align-items-center select {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.flex-column.flex-md-row.align-items-center.gap-2 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
            width: 100% !important;
        }
        
        .d-flex.flex-column.flex-md-row.align-items-center.gap-2 span,
        .d-flex.flex-column.flex-md-row.align-items-center.gap-2 input#search,
        .d-flex.flex-column.flex-md-row.align-items-center.gap-2 button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .d-flex.flex-column.flex-md-row.align-items-center.gap-2 input#search {
            height: 40px !important;
        }
        
        /* 첫 번째 테이블 (근태 현황)은 카드로 변환 */
        .card-body .table-responsive {
            overflow-x: hidden !important;
        }
        
        .card-body .table-responsive #table_sum {
            visibility: hidden !important;
            position: absolute !important;
            left: -9999px !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
        }
        
        /* table_sum 모바일 카드 컨테이너 */
        #table-sum-mobile-cards {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }
        
        .table-sum-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .table-sum-card-title {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #dee2e6;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .table-sum-card-title.text-primary {
            color: #0d6efd !important;
        }
        
        .table-sum-card-title.text-danger {
            color: #dc3545 !important;
        }
        
        .table-sum-card-title.text-dark {
            color: #212529 !important;
        }
        
        .table-sum-card-title.text-success {
            color: #198754 !important;
        }
        
        .table-sum-card-employee {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .table-sum-card-employee:last-child {
            border-bottom: none;
        }
        
        .table-sum-card-employee-name {
            font-weight: 600;
            color: #495057;
            flex: 0 0 40%;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .table-sum-card-employee-value {
            flex: 1;
            text-align: right;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
        }
        
        /* 두 번째 테이블 (개별 입력 리스트)은 카드로 표시 */
        .card:last-child .table,
        .card table.table-hover {
            visibility: hidden !important;
            position: absolute !important;
            left: -9999px !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-cards-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .mobile-card-item {
            margin-bottom: 0.5rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-label {
            font-weight: bold;
            color: #495057;
            margin-right: 0.5rem;
            display: inline-block;
            min-width: 80px;
        }
        
        .mobile-card-value {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
        }
        
        /* 페이지네이션 최적화 */
        .d-flex.mt-2.mb-2.justify-content-center {
            padding: 0.5rem !important;
            flex-wrap: wrap !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
    
    /* PC 환경에서는 모바일 카드 숨기기 */
    @media (min-width: 769px) {
        #mobile-cards-container,
        #table-sum-mobile-cards {
            display: none !important;
        }
        
        /* PC에서는 table_sum 테이블 표시 */
        .card-body .table-responsive #table_sum {
            visibility: visible !important;
            position: relative !important;
            left: auto !important;
            width: auto !important;
            height: auto !important;
            overflow: visible !important;
        }
    }
</style>

<body>

<?php if ($menu !== 'no') require_once(includePath('myheader.php')); ?>

<?php
// 요청 파라미터 수신
$num = $_REQUEST["num"] ?? '';
$year = $_REQUEST["year"] ?? date("Y");
$month = $_REQUEST["month"] ?? date("m");

// 검색 및 모드 관련 변수 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;

// 모바일 체크 변수 초기화
$chkMobile = $_SESSION["chkMobile"] ?? false;

// 관리자 권한 체크
$admin = 0;
if ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지') {
    $admin = 1;
}

// DB 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 기본정보 불러옴
include "load_DB.php";

// 변수 초기화 (load_DB.php에서 정의되지 않은 경우 대비)
$basic_name_arr = $basic_name_arr ?? array();
$remainedAL = $remainedAL ?? array();

// json 연관배열을 만든 후 처리하기 위함
$basic_name_json = json_encode($basic_name_arr);
$remainedAL_json = json_encode($remainedAL);

$name_arr = array_unique($basic_name_arr);

// 현재 날짜를 가져옵니다
$today = date("Y-m-d");

// 제조파트에 해당되는 직원들의 근무를 파악하는 루틴
// 배열에 이름, 일자, 내용을 기록한다.
$view_name = array();
$view_date = array();
$view_item = array();
$view_contents = array();
$sum_overtime = array(); // 연장근로
$sum_holidaywork = array(); // 특근 합계
$sum_allovertime = array(); // 잔업 + 특근 합계

// rowDBask.php에서 사용될 변수 초기화
$name = '';
$askdatefrom = '';
$content = '';
$item = '';

try {
    $sql = "select * from mirae8440.absent";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $count = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include 'rowDBask.php';
        array_push($view_name, $name);
        array_push($view_date, $askdatefrom);
        array_push($view_contents, $content);
        array_push($view_item, $item);
    }
} catch (PDOException $ex) {
    error_log("absent 조회 오류: " . $ex->getMessage());
}
?>

<?php if ($chkMobile == false) { ?>
    <div class="container">
<?php } else { ?>
    <div class="container-fluid">
<?php } ?>
</div>

<form name="board_form" id="board_form" method="post" action="index.php">
    <div class="container-fluid">
        <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
            <h5>(현장 근태관리) -> 공장장</h5>
            <button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'><i class="bi bi-arrow-clockwise"></i></button>
        </div>

        <div class="row">
            <?php
            if ($chkMobile === true) {
                echo '<div class="col-sm-12">';
            } else {
                echo '<div class="col-sm-7">';
            }
            ?>

            <div class="card card-body">
                <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
                    <h5>년월 설정</h5>
                    <select name="year" id="year" class="form-select form-select-sm w-auto text-center mx-2">
                        <?php
                        $current_year = date("Y");
                        $year_arr = array();

                        for ($i = 0; $i < 3; $i++) {
                            $year_arr[] = $current_year - $i;
                        }

                        for ($i = 0; $i < count($year_arr); $i++) {
                            if ($year == $year_arr[$i]) {
                                echo "<option selected value='" . $year_arr[$i] . "'>" . $year_arr[$i] . "</option>";
                            } else {
                                echo "<option value='" . $year_arr[$i] . "'>" . $year_arr[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <select name="month" id="month" class="form-select form-select-sm w-auto text-center">
                        <?php
                        $month_arr = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
                        for ($i = 0; $i < count($month_arr); $i++) {
                            if ($month == $month_arr[$i]) {
                                echo "<option selected value='" . $month_arr[$i] . "'>" . $month_arr[$i] . "</option>";
                            } else {
                                echo "<option value='" . $month_arr[$i] . "'>" . $month_arr[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="table-responsive mt-3 mb-3 justify-content-center">
                    <!-- table_sum 모바일 카드 컨테이너 -->
                    <div id="table-sum-mobile-cards"></div>
                    <table id="table_sum" class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <?php
                                // 직원 이름을 출력하는 열 출력
                                for ($i = 0; $i < count($name_arr) + 1; $i++) {
                                    if ($i === 0) {
                                        echo '<th scope="col" style="font-size:14px;">구분</th>';
                                    } else {
                                        echo '<th scope="col" style="font-size:14px;">' . $name_arr[$i - 1] . '</th>';
                                    }

                                    $sum_overtime[$i] = 0;
                                    $sum_holidaywork[$i] = 0;
                                    $sum_allovertime[$i] = 0;
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 현재 날짜를 가져옵니다
                            $today = date("Y-m-d");
                            // 요일을 출력합니다
                            $days = array("일", "월", "화", "수", "목", "금", "토");

                            // 해당 월의 마지막 날짜를 구합니다
                            $num_days = date("t", mktime(0, 0, 0, $month, 1, $year));

                            for ($i = 0; $i < $num_days + 5; $i++) {
                                echo '<tr class="text-center" data-row-index="' . $i . '">';
                                if ($i === 0) {
                                    echo '<td class="text-primary" data-label="구분">연장근로(시간) 합계</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td data-label="' . htmlspecialchars($name_arr[$j], ENT_QUOTES, 'UTF-8') . '"></td>';
                                    }
                                } else if ($i === 1) {
                                    echo '<td class="text-danger" data-label="구분">특근(시간) 합계</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td data-label="' . htmlspecialchars($name_arr[$j], ENT_QUOTES, 'UTF-8') . '"></td>';
                                    }
                                } else if ($i === 2) {
                                    echo '<td class="text-dark" data-label="구분">(잔업+특근) 합계</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td data-label="' . htmlspecialchars($name_arr[$j], ENT_QUOTES, 'UTF-8') . '"></td>';
                                    }
                                } else if ($i === 3) {
                                    echo '<td class="text-success" data-label="구분">연차 잔여일수</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td data-label="' . htmlspecialchars($name_arr[$j], ENT_QUOTES, 'UTF-8') . '"></td>';
                                    }
                                } else if ($i === 4) {
                                    echo '<td data-label="구분"></td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td data-label="' . htmlspecialchars($name_arr[$j], ENT_QUOTES, 'UTF-8') . '"></td>';
                                    }
                                } else {
                                    $thisday = $days[date('w', strtotime($year . "-" . $month . "-" . ($i - 4)))];
                                    $pointday = date("Y-m-d", strtotime($year . "-" . $month . "-" . ($i - 4)));

                                    if ($thisday === '토' || $thisday === '일') {
                                        echo '<td class="text-danger" data-label="구분">' . ($i - 4) . '(' . $thisday . ')</td>';
                                    } else {
                                        echo '<td data-label="구분">' . ($i - 4) . '(' . $thisday . ')</td>';
                                    }

                                    // 자료있는 숫자만큼 찾는다
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        // 연차 사용이력 추적
                                        $annualleave = '';

                                        // 연차등 사용한 것 있으면 화면에 보여준다
                                        try {
                                            $sql1 = "select * from mirae8440.eworks where is_deleted IS NULL and eworks_item='연차'";
                                            $stmh1 = $pdo->prepare($sql1);
                                            $stmh1->execute();

                                            while ($row = $stmh1->fetch(PDO::FETCH_ASSOC)) {
                                                $al_name = $row["author"];
                                                $al_item = $row["al_item"];
                                                $al_askdatefrom = $row["al_askdatefrom"];
                                                $al_askdateto = $row["al_askdateto"];

                                                if ($pointday >= $al_askdatefrom && $pointday <= $al_askdateto && $al_name === $name_arr[$j]) {
                                                    if ($thisday !== '토' && $thisday !== '일') {
                                                        $annualleave = $al_item;
                                                    }
                                                }
                                            }
                                        } catch (PDOException $ex) {
                                            error_log("연차 조회 오류: " . $ex->getMessage());
                                        }

                                        // 기본적으로 연차표시
                                        $printstr = '<td data-label="' . htmlspecialchars($name_arr[$j], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($annualleave, ENT_QUOTES, 'UTF-8') . '</td>';

                                        $addstr = '';
                                        for ($kk = 0; $kk < count($view_date); $kk++) {
                                            if ($view_name[$kk] === $name_arr[$j] && $view_date[$kk] === $pointday) {
                                                if ($view_contents[$kk] == '연장근로') {
                                                    $sum_overtime[$j] += (float)$view_item[$kk];
                                                    $sum_allovertime[$j] += (float)$view_item[$kk];
                                                    $addstr = '(야)';
                                                }
                                                if ($view_contents[$kk] == '특근') {
                                                    $sum_holidaywork[$j] += (float)$view_item[$kk];
                                                    $sum_allovertime[$j] += (float)$view_item[$kk];
                                                    $addstr = '(특)';
                                                }
                                                if ($view_contents[$kk] == '휴가') {
                                                    $addstr = '<span class="badge bg-danger">휴가</span>';
                                                }

                                                $printstr = '<td data-label="' . htmlspecialchars($name_arr[$j], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($annualleave, ENT_QUOTES, 'UTF-8') . $addstr . htmlspecialchars($view_item[$kk], ENT_QUOTES, 'UTF-8') . '</td>';
                                            }
                                        }

                                        echo $printstr;
                                    }
                                }

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <?php
        if ($chkMobile === true) {
            echo '<div class="col-sm-12">';
        } else {
            echo '<div class="col-sm-5">';
        }
        ?>

        <div class="card">
            <div class="d-flex mt-5 mb-3 justify-content-center align-items-center">
                <?php
                $scale = 35;       // 한 페이지에 보여질 게시글 수
                $page_scale = 15;   // 한 페이지당 표시될 페이지 수
                $first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

                if ($mode == "search" || $mode == "") {
                    if ($search == "") {
                        $sql = "select * from mirae8440.absent order by askdatefrom desc, name asc limit $first_num, $scale";
                        $sqlcon = "select * from mirae8440.absent order by askdatefrom desc";
                    } elseif ($search != "") {
                        $sql = "select * from mirae8440.absent where (name like '%$search%')";
                        $sql .= " order by askdatefrom desc limit $first_num, $scale";
                        $sqlcon = "select * from mirae8440.absent where (name like '%$search%')";
                        $sqlcon .= " order by askdatefrom desc";
                    }
                }

                try {
                    $stmh = $pdo->query($sql);
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        include "rowDBask.php";
                    }
                } catch (PDOException $ex) {
                    error_log("absent 조회 오류: " . $ex->getMessage());
                }

                try {
                    $allstmh = $pdo->query($sqlcon);
                    $temp2 = $allstmh->rowCount();
                    $stmh = $pdo->query($sql);
                    $temp1 = $stmh->rowCount();

                    $total_row = $temp2;
                    $total_page = ceil($total_row / $scale);
                    $current_page = ceil($page / $page_scale);
                    ?>

                    <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                        <span class="me-2">▷ <?= htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8') ?></span>
                        <button type="button" class="btn btn-dark btn-sm" onclick="popupCenter('write_form_ask.php', '등록/수정/삭제', 450, 520);return false;"><i class="bi bi-pencil"></i> 신규</button>
                        <input type="text" name="search" id="search" class="form-control" style="width:150px;height:32px;" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                        <button type="button" id="searchBtn" class="btn btn-dark btn-sm"><i class="bi bi-search"></i> 검색</button>
                        <button type="button" class="btn btn-dark btn-sm" onclick="popupCenter('../annualleave/batchDB.php','직원 연차 list',1400,950);">직원 연차 list</button>
                    </div>
            </div>

            <div class="table-responsive mt-3 mb-3 justify-content-center">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">번호</th>
                            <th class="text-center">성명</th>
                            <th class="text-center">작업일</th>
                            <th class="text-center">근로형태</th>
                            <th class="text-center">작업시간</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($page <= 1) {
                            $start_num = $total_row;
                        } else {
                            $start_num = $total_row - ($page - 1) * $scale;
                        }

                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            include "rowDBask.php";
                            ?>
                            <tr onclick="popupCenter('write_form_ask.php?num=<?=$num?>', '연장근로 등록/수정/삭제', 450, 550);return false;"
                                data-num="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>"
                                data-name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                                data-askdatefrom="<?= htmlspecialchars($askdatefrom, ENT_QUOTES, 'UTF-8') ?>"
                                data-content="<?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?>"
                                data-item="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
                                <td class="text-center" data-label="번호"><?=$start_num?></td>
                                <td class="text-center" data-label="성명"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="작업일"><?= htmlspecialchars(iconv_substr($askdatefrom, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="근로형태"><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="작업시간"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                            <?php
                            $start_num--;
                        }
                    } catch (PDOException $ex) {
                        error_log("absent 조회 오류: " . $ex->getMessage());
                    }

                    // 페이지 구분 블럭의 첫 페이지 수 계산
                    $start_page = ($current_page - 1) * $page_scale + 1;
                    // 페이지 구분 블럭의 마지막 페이지 수 계산
                    $end_page = $start_page + $page_scale - 1;
                    ?>
                    </tbody>
                </table>
            </div>
            
            <!-- 모바일 카드 컨테이너 -->
            <div id="mobile-cards-container"></div>

            <div class="d-flex mt-2 mb-2 justify-content-center">
                <?php
                if ($page != 1 && $page > $page_scale) {
                    $prev_page = $page - $page_scale;
                    if ($prev_page <= 0) {
                        $prev_page = 1;
                    }
                    echo "<a href='index.php?page=$prev_page&mode=search&search=$search'>◀ </a>";
                }

                for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                    if ($page == $i) {
                        echo "<font color=red><b>[$i]</b></font>";
                    } else {
                        echo "<a href='index.php?page=$i&mode=search&search=$search'>[$i]</a>";
                    }
                }

                if ($page < $total_page) {
                    $next_page = $page + $page_scale;
                    if ($next_page > $total_page) {
                        $next_page = $total_page;
                    }
                    echo "<a href='index.php?page=$next_page&mode=search&search=$search'> ▶</a><p>";
                }
                ?>
            </div>
        </div>
    </div>

</div>
</div>
			

</form>
</div>

<script>
// table_sum 테이블을 카드로 렌더링하는 함수
function renderTableSumCards() {
    if (window.innerWidth > 768) {
        $('#table-sum-mobile-cards').html('');
        return; // PC 환경에서는 실행하지 않음
    }
    
    var $container = $('#table-sum-mobile-cards');
    $container.html(''); // 기존 내용 초기화
    
    try {
        var $table = $('#table_sum');
        if ($table.length === 0) {
            console.log('table_sum not found');
            return;
        }
        
        // 헤더 정보 가져오기
        var $headerRow = $table.find('thead tr');
        var headers = [];
        $headerRow.find('th').each(function() {
            headers.push($(this).text().trim());
        });
        
        // tbody의 모든 행 가져오기
        var $rows = $table.find('tbody tr');
        
        console.log('table_sum rows found:', $rows.length);
        console.log('Headers:', headers);
        
        if ($rows.length === 0) {
            $container.append('<div class="text-center text-muted p-3">표시할 데이터가 없습니다.</div>');
            return;
        }
        
        $rows.each(function(index) {
            var $row = $(this);
            var $cells = $row.find('td');
            
            if ($cells.length === 0) {
                return; // 빈 행 건너뛰기
            }
            
            // 첫 번째 셀(구분/날짜)을 카드 제목으로 사용
            var $firstCell = $cells.eq(0);
            var titleText = $firstCell.text().trim();
            var titleClass = '';
            
            // 첫 번째 셀의 클래스 확인 (text-primary, text-danger 등)
            if ($firstCell.hasClass('text-primary')) {
                titleClass = 'text-primary';
            } else if ($firstCell.hasClass('text-danger')) {
                titleClass = 'text-danger';
            } else if ($firstCell.hasClass('text-dark')) {
                titleClass = 'text-dark';
            } else if ($firstCell.hasClass('text-success')) {
                titleClass = 'text-success';
            }
            
            // 빈 제목인 경우 건너뛰기
            if (!titleText || titleText === '') {
                return;
            }
            
            // HTML 이스케이프 처리
            var escapeHtml = function(text) {
                if (!text) return '';
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
            };
            
            // 카드 HTML 생성
            var cardHtml = '<div class="table-sum-card">';
            cardHtml += '<div class="table-sum-card-title ' + titleClass + '">' + escapeHtml(titleText) + '</div>';
            
            // 나머지 셀들을 직원별로 표시
            for (var i = 1; i < $cells.length && i < headers.length; i++) {
                var $cell = $cells.eq(i);
                var cellText = $cell.html().trim(); // HTML 포함 (badge 등)
                var employeeName = headers[i] || '직원' + i;
                
                // 셀이 비어있지 않은 경우만 표시
                if (cellText && cellText !== '') {
                    cardHtml += '<div class="table-sum-card-employee">';
                    cardHtml += '<div class="table-sum-card-employee-name">' + escapeHtml(employeeName) + '</div>';
                    cardHtml += '<div class="table-sum-card-employee-value">' + cellText + '</div>';
                    cardHtml += '</div>';
                }
            }
            
            cardHtml += '</div>';
            $container.append(cardHtml);
        });
        
        console.log('table_sum cards rendered');
    } catch (error) {
        console.error('Error rendering table_sum cards:', error);
    }
}

// 모바일 카드 렌더링 함수
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-cards-container').html('');
        return; // PC 환경에서는 실행하지 않음
    }
    
    var $container = $('#mobile-cards-container');
    $container.html(''); // 기존 내용 초기화
    
    try {
        // 두 번째 테이블 (개별 입력 리스트)만 선택
        var $targetTable = $('.card table.table-hover').last();
        
        if ($targetTable.length === 0) {
            $targetTable = $('table.table-hover').last();
        }
        
        var rows = $targetTable.find('tbody tr');
        
        console.log('Target table found:', $targetTable.length);
        console.log('Total rows found:', rows.length);
        
        if (!rows || rows.length === 0) {
            console.log('No rows found in table');
            $container.append('<div class="text-center text-muted p-3">표시할 데이터가 없습니다.</div>');
            return;
        }
        
        var validCardCount = 0;
        var skippedCount = 0;
        
        rows.each(function(index) {
            var $row = $(this);
            
            // td 요소가 없는 경우 제외 (빈 행)
            var $tds = $row.find('td');
            var tdCount = $tds.length;
            if (tdCount === 0) {
                skippedCount++;
                return;
            }
            
            // td에서 직접 데이터 읽기
            var num = $row.attr('data-num') || $row.data('num') || $tds.eq(0).text().trim();
            var name = $row.attr('data-name') || $row.data('name') || $tds.eq(1).text().trim();
            var askdatefrom = $row.attr('data-askdatefrom') || $row.data('askdatefrom') || $tds.eq(2).text().trim();
            var content = $row.attr('data-content') || $row.data('content') || $tds.eq(3).text().trim();
            var item = $row.attr('data-item') || $row.data('item') || $tds.eq(4).text().trim();
            
            // 유효성 검사: 잘못된 텍스트 값 제외
            var invalidValues = ['no data available in table', '구분', '번호', '성명', '작업일', '근로형태', '작업시간'];
            var numLower = (num || '').toString().toLowerCase().trim();
            
            if (invalidValues.indexOf(numLower) !== -1) {
                skippedCount++;
                return;
            }
            
            // num이 완전히 비어있는 경우만 제외
            if (!num || num === '' || num === 'undefined' || num === 'null') {
                skippedCount++;
                return;
            }
            
            // HTML 이스케이프 처리
            var escapeHtml = function(text) {
                if (!text) return '';
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
            };
            
            // 클릭 이벤트를 위한 onclick 속성
            var onclickAttr = $row.attr('onclick') || '';
            var cardHtml = '<div class="mobile-card" onclick="' + escapeHtml(onclickAttr) + '" style="cursor:pointer;">' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(num) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">성명:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(name) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작업일:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(askdatefrom) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">근로형태:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(content) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작업시간:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(item) + '</span>' +
                '</div>' +
                '</div>';
            
            $container.append(cardHtml);
            validCardCount++;
        });
        
        console.log('Mobile cards rendered: ' + validCardCount + ' valid cards');
        console.log('Skipped rows: ' + skippedCount);
        
        if (validCardCount === 0) {
            $container.append('<div class="text-center text-muted p-3">표시할 데이터가 없습니다.</div>');
        }
    } catch (error) {
        console.error('Error rendering mobile cards:', error);
    }
}

$(document).ready(function() {
    // PHP에서 JSON으로 변환된 배열
    var sum_overtime = <?php echo json_encode($sum_overtime); ?>;
    var sum_holidaywork = <?php echo json_encode($sum_holidaywork); ?>;
    var sum_allovertime = <?php echo json_encode($sum_allovertime); ?>;

    var basicNames = <?php echo $basic_name_json; ?>;
    var remainedAL_json = <?php echo $remainedAL_json; ?>;

    // 특정 테이블의 연장근로 상단에 표시하기
    sum_overtime.forEach(function(value, index) {
        if (value !== 0) {
            $("#table_sum tr:eq(1) td:eq(" + (index + 1) + ")").text(value);
        }
    });

    // 특정 테이블의 특근 상단에 표시하기
    sum_holidaywork.forEach(function(value, index) {
        if (value !== 0) {
            $("#table_sum tr:eq(2) td:eq(" + (index + 1) + ")").text(value);
        }
    });

    // 특정 테이블의 잔업 + 특근 상단에 표시하기
    sum_allovertime.forEach(function(value, index) {
        if (value !== 0) {
            $("#table_sum tr:eq(3) td:eq(" + (index + 1) + ")").text("(" + value + ")");
        }
    });

    // 특정 테이블의 잔여 연차일 상단에 표시하기
    basicNames.forEach(function(name, index) {
        var totalUsedDays = findTotalUsedDaysForName(name);
        if (totalUsedDays !== null) {
            $("#table_sum tr:eq(4) td:eq(" + (index + 1) + ")").text(totalUsedDays);
        }
    });

    // 이름에 해당하는 총 사용일 찾기
    function findTotalUsedDaysForName(name) {
        var index = basicNames.indexOf(name);
        if (index !== -1) {
            return remainedAL_json[index];
        }
        return null;
    }
    
    // 합계 값 업데이트 후 table_sum 카드 재렌더링
    if (window.innerWidth <= 768) {
        setTimeout(function() {
            renderTableSumCards();
        }, 300);
    }

    // 모바일 카드 렌더링
    if (window.innerWidth <= 768) {
        setTimeout(function() {
            renderTableSumCards();
            renderMobileCards();
        }, 200);
    }
    
    // 화면 크기 변경 시 카드 재렌더링
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth <= 768) {
                renderTableSumCards();
                renderMobileCards();
            } else {
                $('#mobile-cards-container').html('');
                $('#table-sum-mobile-cards').html('');
            }
        }, 250);
    });
    
    // table_sum의 합계 값이 업데이트된 후 카드 재렌더링
    setTimeout(function() {
        if (window.innerWidth <= 768) {
            renderTableSumCards();
        }
    }, 500);

    // 년도 선택 변경
    $('select[name="year"]').change(function() {
        document.getElementById('board_form').submit();
    });

    // 월 선택 변경
    $('select[name="month"]').change(function() {
        document.getElementById('board_form').submit();
    });

    // 모달 닫기
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });

    // 검색 버튼
    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });

    // a 태그 전체 밑줄없앰
    $('a').children().css('textDecoration', 'none');
    $('a').parent().css('textDecoration', 'none');

    // 서버에 작업 기록
    saveLogData('공장 근태관리');
});

// 검색 엔터키 처리
function SearchEnter() {
    if (event.keyCode == 13) {
        document.getElementById('board_form').submit();
    }
}
</script>
</body>
</html>