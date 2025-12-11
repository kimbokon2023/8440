<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["user_name"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';
$menu = $_SESSION["menu"] ?? '';
$chkMobile = $_SESSION["chkMobile"] ?? false;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$year = $_REQUEST["year"] ?? date("Y");
$month = $_REQUEST["month"] ?? date("m");
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;

// 관리자 권한 설정
$admin = 0;
$admin_names = array('소현철', '김보곤', '최장중', '이경묵', '소민지');
if (in_array($user_name, $admin_names)) {
    $admin = 1;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 기본정보 불러옴
include "load_DB.php";

// 배열 초기화 (load_DB.php에서 설정되지 않은 경우 대비)
$basic_name_arr = $basic_name_arr ?? array();
$name_arr = array_unique($basic_name_arr);

// 현재 날짜
$today = date("Y-m-d");

// 제조파트에 해당되는 직원들의 근무를 파악하는 루틴
// 배열에 이름, 일자, 내용을 기록한다.
// 해당요일과 맞으면 출력해 준다.

$view_name = array();
$view_date = array();
$view_item = array();
$view_labor_name = array();
$view_contents = array();
$view_sum_after = 0;  // 점심식사
$view_sum_evening = 0;  // 석식 합계

// rowDBask.php에서 사용되는 변수 초기화
$name = '';
$askdatefrom = '';
$content = '';
$item = '';
$labor_name = '';
$part = '';
$state = '';

try {
    $sql = "SELECT * FROM {$DB}.daylaborer";
    $stmh = $pdo->query($sql);
    $count = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include 'rowDBask.php';
        
        array_push($view_name, $name);
        array_push($view_date, $askdatefrom);  // 작업일 기준
        array_push($view_contents, $content);  // 형태 (중식/석식 구분)
        array_push($view_item, $item);  // 인원수
        array_push($view_labor_name, $labor_name);  // 비고기록
    }
} catch (PDOException $ex) {
    error_log("일용직 데이터 조회 오류: " . $ex->getMessage());
}


include getDocumentRoot() . '/load_header.php';
?>

<title>일용직 근태관리</title>

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
        .card-body .d-flex.mt-3.mb-3.justify-content-center table {
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
        
        .table-sum-card-value {
            text-align: right;
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 두 번째 테이블 (개별 입력 리스트)은 카드로 표시 */
        .card table.table-hover,
        .card .table.table-striped.table-bordered.table-hover {
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
        
        /* PC에서는 테이블 표시 */
        .card-body .d-flex.mt-3.mb-3.justify-content-center table {
            visibility: visible !important;
            position: relative !important;
            left: auto !important;
            width: auto !important;
            height: auto !important;
            overflow: visible !important;
        }
    }
</style>

</head>

<body>
<?php if ($menu !== 'no') require_once(includePath('myheader.php')); ?>

<form name="board_form" id="board_form" method="post" action="index.php">
    <div class="container">
        <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
            <h5 class="text-dark">(신청) -> 공장장</h5>&nbsp;&nbsp;&nbsp;
            <h5 class="text-primary">(결재관련) -> 경리부</h5>
            <button type="button" class="btn btn-dark btn-sm mx-2" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        
        <div class="row">
            <?php
            if ($chkMobile == true) {
                echo '<div class="col-sm-12">';
            } else {
                echo '<div class="col-sm-5">';
            }
            ?>
            
            <div class="card card-body">
                <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
                    <h5>(일용직 근태) 년월 설정</h5>
                    <select name="year" id="year" class="form-select form-select-sm w-auto text-center mx-2">
                        <?php
                        $current_year = date("Y");
                        $year_arr = array();
                        
                        for ($i = 0; $i < 3; $i++) {
                            $year_arr[] = $current_year - $i;
                        }
                        
                        for ($i = 0; $i < count($year_arr); $i++) {
                            if ($year == $year_arr[$i]) {
                                print "<option selected value='" . $year_arr[$i] . "'>" . $year_arr[$i] . "</option>";
                            } else {
                                print "<option value='" . $year_arr[$i] . "'>" . $year_arr[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <select name="month" id="month" class="form-select form-select-sm w-auto text-center">
                        <?php
                        $month_arr = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
                        
                        for ($i = 0; $i < count($month_arr); $i++) {
                            if ($month == $month_arr[$i]) {
                                print "<option selected value='" . $month_arr[$i] . "'>" . $month_arr[$i] . "</option>";
                            } else {
                                print "<option value='" . $month_arr[$i] . "'>" . $month_arr[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="d-flex mt-3 mb-3 justify-content-center">
                    <!-- table_sum 모바일 카드 컨테이너 -->
                    <div id="table-sum-mobile-cards"></div>
                    <table class="table table-striped table-bordered" id="table_sum">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <th scope="col">구분</th>
                                <th>근무인원</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 요일 배열
                            $days = array("일", "월", "화", "수", "목", "금", "토");
                            
                            // 해당 월의 마지막 날짜를 구합니다.
                            $num_days = date("t", mktime(0, 0, 0, $month, 1, $year));
                            
                            $exceptNum = 0;
                            
                            for ($i = 0; $i < $num_days + 2; $i++) {
                                print '<tr class="text-center" data-row-index="' . $i . '">';
                                
                                if ($i === 0) {
                                    print '<td class="text-primary" data-label="구분">인원 합계</td>';
                                    print '<td data-label="근무인원"></td>';
                                } else if ($i === 1) {
                                    print '<td data-label="구분"></td>';
                                    print '<td data-label="근무인원"></td>';
                                } else {
                                    // 화면에 보여주기 위한 날짜
                                    $thisday = $days[date('w', strtotime($year . "-" . $month . "-" . ($i - 1)))];
                                    // 해당일을 뽑아내서 비교하기 위한 변수
                                    $pointday = date("Y-m-d", strtotime($year . "-" . $month . "-" . ($i - 1)));
                                    
                                    if ($thisday === '토' || $thisday === '일') {
                                        print '<td class="text-danger" data-label="구분">' . htmlspecialchars(($i - 1) . '(' . $thisday . ')', ENT_QUOTES, 'UTF-8') . '</td>';
                                    } else {
                                        print '<td data-label="구분">' . htmlspecialchars(($i - 1) . '(' . $thisday . ')', ENT_QUOTES, 'UTF-8') . '</td>';
                                    }
                                    
                                    $printstr = '<td data-label="근무인원"></td>';
                                    $inner = 0;
                                    
                                    for ($kk = 0; $kk < count($view_date); $kk++) {
                                        if ($view_date[$kk] === $pointday && $view_contents[$kk] == '일당') {
                                            $inner += (float)$view_item[$kk];
                                            $view_sum_after += (float)$view_item[$kk];
                                            $printstr = '<td data-label="근무인원">' . htmlspecialchars($inner, ENT_QUOTES, 'UTF-8') . '</td>';
                                        }
                                    }
                                    
                                    print $printstr;
                                }
                                
                                print '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <?php
        if ($chkMobile == true) {
            echo '<div class="col-sm-12">';
        } else {
            echo '<div class="col-sm-7">';
        }
        
        // 페이징 설정
        $scale = 35;        // 한 페이지에 보여질 게시글 수
        $page_scale = 15;   // 한 페이지당 표시될 페이지 수
        $first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번
        
        // SQL 쿼리 생성
        if ($mode == "search" || $mode == "") {
            if ($search == "") {
                $sql = "SELECT * FROM {$DB}.daylaborer 
                        ORDER BY askdatefrom DESC, name ASC 
                        LIMIT $first_num, $scale";
                $sqlcon = "SELECT * FROM {$DB}.daylaborer 
                           ORDER BY askdatefrom DESC";
            } elseif ($search != "") {
                // SQL Injection 기본 방어
                $search_safe = str_replace("'", "''", $search);
		              
                $sql = "SELECT * FROM {$DB}.daylaborer 
                        WHERE (name LIKE '%$search_safe%') 
                           OR (content LIKE '%$search_safe%') 
                           OR (labor_name LIKE '%$search_safe%') 
                        ORDER BY askdatefrom DESC 
                        LIMIT $first_num, $scale";
                $sqlcon = "SELECT * FROM {$DB}.daylaborer 
                           WHERE (name LIKE '%$search_safe%') 
                              OR (content LIKE '%$search_safe%') 
                              OR (labor_name LIKE '%$search_safe%') 
                           ORDER BY askdatefrom DESC";
            }
        }
        
        try {
            $stmh = $pdo->query($sql);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                include "rowDBask.php";
            }
        } catch (PDOException $ex) {
            error_log("일용직 리스트 조회 오류: " . $ex->getMessage());
        }
        
        try {
            $allstmh = $pdo->query($sqlcon);  // 검색 조건에 맞는 쿼리 전체 개수
            $temp2 = $allstmh->rowCount();
            $stmh = $pdo->query($sql);  // 검색조건에 맞는글 stmh
            $temp1 = $stmh->rowCount();
            
            $total_row = $temp2;  // 전체 글수
            
            $total_page = ceil($total_row / $scale);  // 검색 전체 페이지 블록 수
            $current_page = ceil($page / $page_scale);  // 현재 페이지 블록 위치계산
        ?>
        
        <div class="card">
            <div class="d-flex flex-column flex-md-row align-items-center gap-2 mt-5 mb-3">
                <span class="me-2">▷ <?= htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8') ?></span>
                <button type="button" class="btn btn-dark btn-sm" 
                        onclick="popupCenter('write_form_ask.php', '등록/수정/삭제', 450, 550); return false;">
                    <i class="bi bi-pencil"></i> 신규
                </button>
                <input type="text" name="search" id="search" class="form-control" 
                       style="width:150px;" value="<?= htmlspecialchars($search) ?>" 
                       onkeydown="JavaScript:SearchEnter();" placeholder="검색어">
                <button type="button" id="searchBtn" class="btn btn-dark btn-sm">
                    <i class="bi bi-search"></i> 검색
                </button>
            </div>
            
            <div class="table table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-primary">
                        <tr class="text-center">
                            <th>번호</th>
                            <th>근무일</th>
                            <th>종류</th>
                            <th>성함</th>
                            <th>요청/요청확인/지급완료</th>
                            <th>비고(추가근무 등)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($page <= 1) {
                            $start_num = $total_row;  // 페이지당 표시되는 첫번째 글순번
                        } else {
                            $start_num = $total_row - ($page - 1) * $scale;
                        }
                        
                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            include "rowDBask.php";
                        ?>
                            <tr onclick="popupCenter('write_form_ask.php?num=<?= $num ?>', '일용직 신청', 450, 550); return false;" style="cursor: pointer;"
                                data-num="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>"
                                data-askdatefrom="<?= htmlspecialchars($askdatefrom, ENT_QUOTES, 'UTF-8') ?>"
                                data-content="<?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?>"
                                data-labor_name="<?= htmlspecialchars($labor_name, ENT_QUOTES, 'UTF-8') ?>"
                                data-state="<?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8') ?>"
                                data-part="<?= htmlspecialchars($part, ENT_QUOTES, 'UTF-8') ?>">
                                <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                <td class="text-center" data-label="근무일"><?= htmlspecialchars(iconv_substr($askdatefrom, 5, 5, "utf-8"), ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center" data-label="종류"><?= htmlspecialchars($content) ?></td>
                                <td class="text-center" data-label="성함"><?= htmlspecialchars($labor_name) ?></td>
                                <td class="text-center <?= $state == '요청' ? 'text-primary' : ($state == '지급완료' ? 'text-danger' : '') ?>" data-label="요청/요청확인/지급완료">
                                    <?= htmlspecialchars($state) ?>
                                </td>
                                <td class="text-center" data-label="비고(추가근무 등)"><?= htmlspecialchars($part) ?></td>
                            </tr>
                        <?php
                            $start_num--;
                        }
                        } catch (PDOException $ex) {
                            error_log("일용직 페이징 처리 오류: " . $ex->getMessage());
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
                $search_encoded = urlencode($search);
                
                if ($page != 1 && $page > $page_scale) {
                    $prev_page = $page - $page_scale;
                    
                    // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
                    if ($prev_page <= 0) {
                        $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
                    }
                    
                    print "<a href='index.php?page=$prev_page&mode=search&search=$search_encoded'>◀</a>";
                }
                
                for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                    // [1][2][3] 페이지 번호 목록 출력
                    if ($page == $i) {
                        // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                        print "<font color='red'><b>[$i]</b></font>";
                    } else {
                        print "<a href='index.php?page=$i&mode=search&search=$search_encoded'>[$i]</a>";
                    }
                }
                
                if ($page < $total_page) {
                    $next_page = $page + $page_scale;
                    
                    if ($next_page > $total_page) {
                        $next_page = $total_page;
                    }
                    
                    // next_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
                    print "<a href='index.php?page=$next_page&mode=search&search=$search_encoded'>▶</a>";
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
            
            // 두 번째 셀(근무인원) 표시
            if ($cells.length > 1) {
                var $secondCell = $cells.eq(1);
                var cellText = $secondCell.html().trim();
                
                if (cellText && cellText !== '') {
                    cardHtml += '<div class="table-sum-card-value">' + cellText + '</div>';
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
            var askdatefrom = $row.attr('data-askdatefrom') || $row.data('askdatefrom') || $tds.eq(1).text().trim();
            var content = $row.attr('data-content') || $row.data('content') || $tds.eq(2).text().trim();
            var labor_name = $row.attr('data-labor_name') || $row.data('labor_name') || $tds.eq(3).text().trim();
            var state = $row.attr('data-state') || $row.data('state') || $tds.eq(4).text().trim();
            var part = $row.attr('data-part') || $row.data('part') || $tds.eq(5).text().trim();
            
            // 유효성 검사: 잘못된 텍스트 값 제외
            var invalidValues = ['no data available in table', '구분', '번호', '근무일', '종류', '성함', '요청/요청확인/지급완료', '비고(추가근무 등)'];
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
            var stateClass = '';
            if (state === '요청') {
                stateClass = 'text-primary';
            } else if (state === '지급완료') {
                stateClass = 'text-danger';
            }
            
            var cardHtml = '<div class="mobile-card" onclick="' + escapeHtml(onclickAttr) + '" style="cursor:pointer;">' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(num) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">근무일:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(askdatefrom) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">종류:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(content) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">성함:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(labor_name) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">요청/요청확인/지급완료:</span>' +
                '<span class="mobile-card-value ' + stateClass + '">' + escapeHtml(state) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">비고(추가근무 등):</span>' +
                '<span class="mobile-card-value">' + escapeHtml(part) + '</span>' +
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
    // 합계치가 나오면 첫번째줄의 요소를 바꿔준다.
    var view_sum_after = '<?php echo $view_sum_after; ?>';
    var view_sum_evening = '<?php echo $view_sum_evening; ?>';
    
    console.log(view_sum_after);
    console.log(view_sum_evening);
    
    // 연장근로 상단에 표시해주기
    $("td:eq(1)").text(view_sum_after);
    $("td:eq(2)").text(view_sum_evening);
    
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
    
    // 년도 변경 시 폼 제출
    $('select[name="year"]').change(function() {
        document.getElementById('board_form').submit();
    });
    
    // 월 변경 시 폼 제출
    $('select[name="month"]').change(function() {
        document.getElementById('board_form').submit();
    });
    
    // 모달 닫기
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
    
    // 검색 버튼 클릭
    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });
    
    // a 태그 밑줄 제거
    $('a').children().css('textDecoration', 'none');
    $('a').parent().css('textDecoration', 'none');
    
    // exceptNum 표시 (지연)
    setTimeout(function() {
        $("#exceptNum").text('<?php echo $exceptNum; ?>');
    }, 1500);
});

// 엔터키 검색
function SearchEnter() {
    if (event.keyCode == 13) {
        document.getElementById('board_form').submit();
    }
}
</script>
</body>
</html>