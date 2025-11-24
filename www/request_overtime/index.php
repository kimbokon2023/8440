<?php
require_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? '';
$level = $_SESSION["level"] ?? 0;
$chkMobile = $_SESSION["chkMobile"] ?? false;

if (!isset($_SESSION["name"])) {
    $_SESSION["url"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://")
        . $_SERVER['HTTP_HOST']
        . '/request_overtime/index.php';
    sleep(1);
    header("Location:" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://")
        . $_SERVER['HTTP_HOST']
        . "/login/logout.php");
    exit;
}
$title_message = '연장근무(잔업/특근) 사전승인 신청';
?>
<?php include getDocumentRoot() . '/load_header.php' ?>
<title> <?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?> </title>

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
        .d-flex.justify-content-center.align-items-center.mt-3.mb-2 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.justify-content-center.align-items-center.mt-3.mb-2 span {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        .d-flex.justify-content-center.align-items-center.mt-3.mb-2 button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 안내 문구 최적화 */
        .d-flex.justify-content-center.mt-1.mb-3 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.justify-content-center.mt-1.mb-3 span {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            font-size: 0.9rem !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.justify-content-between.align-items-center.mt-2.mb-2 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.75rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.justify-content-between.align-items-center.mt-2.mb-2 > div {
            width: 100% !important;
            max-width: 100% !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        
        .d-flex.justify-content-between.align-items-center.mt-2.mb-2 .d-flex.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        
        .d-flex.justify-content-between.align-items-center.mt-2.mb-2 select,
        .d-flex.justify-content-between.align-items-center.mt-2.mb-2 input {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .d-flex.justify-content-between.align-items-center.mt-2.mb-2 button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 테이블 숨기기 (모바일에서는 카드로 표시) - visibility로 변경하여 데이터 읽기 가능 */
        .table {
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
        .d-flex.justify-content-center.mt-5.mb-5 {
            padding: 0.5rem !important;
            flex-wrap: wrap !important;
        }
        
        .input-group.p-1.mb-2.mt-2 {
            width: 100% !important;
            max-width: 100% !important;
            justify-content: center !important;
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
        
        .modal-body .row {
            margin: 0.5rem 0 !important;
        }
        
        .modal-body .col-md-3,
        .modal-body .col-md-6 {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.5rem 0 !important;
        }
        
        .modal-body label {
            width: 100% !important;
            max-width: 100% !important;
            margin-bottom: 0.25rem !important;
        }
        
        .modal-body select,
        .modal-body input,
        .modal-body textarea {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 모달창 select 요소 height 조정 (글자 잘림 방지) */
        .modal-body select.form-select {
            min-height: 40px !important;
            height: auto !important;
            line-height: 1.5 !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        /* PC 환경에서도 모달창 select height 조정 */
        .modal-body select[style*="height: 32px"],
        .modal-body select[style*="height:32px"],
        .modal-body select#author,
        .modal-body select#ot_type {
            min-height: 40px !important;
            height: auto !important;
            line-height: 1.5 !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        /* 모달창 input 요소 height 조정 */
        .modal-body input[style*="height: 32px"],
        .modal-body input[style*="height:32px"],
        .modal-body input#al_part,
        .modal-body input#al_askdatefrom {
            min-height: 40px !important;
            height: auto !important;
            line-height: 1.5 !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
    }
    
    /* PC 환경에서도 모달창 select height 조정 */
    @media (min-width: 769px) {
        .modal-body select.form-select,
        .modal-body select#author,
        .modal-body select#ot_type {
            min-height: 38px !important;
            height: auto !important;
            line-height: 1.4 !important;
            padding-top: 0.375rem !important;
            padding-bottom: 0.375rem !important;
        }
        
        .modal-body input#al_part,
        .modal-body input#al_askdatefrom {
            min-height: 38px !important;
            height: auto !important;
            line-height: 1.4 !important;
            padding-top: 0.375rem !important;
            padding-bottom: 0.375rem !important;
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
        
        /* 빠른 시간 선택 버튼 최적화 */
        #time_button_container {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        #time_button_container button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
    }
    
    /* PC 환경에서는 모바일 카드 숨기기 */
    @media (min-width: 769px) {
        #mobile-cards-container {
            display: none !important;
        }
    }
</style>

</head>

<body>
    <?php require_once(includePath('myheader.php')); ?>

    <?php
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 관리자 권한 확인
    $admin = 0;
    if ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '조경임'  ) {
        $admin = 1;
    }

    // 요청 파라미터 초기화
    $search = $_REQUEST["search"] ?? '';
    $mode = $_REQUEST["mode"] ?? '';
    $page = $_REQUEST["page"] ?? 1;
    $year = $_REQUEST["year"] ?? date("Y");
    $month = $_REQUEST["month"] ?? date("m");

    $tablename = "eworks";
    $scale = 50;
    $page_scale = 15;
    $first_num = ($page - 1) * $scale;

    $AndisDeleted = " AND is_deleted IS NULL ";
    $WhereisDeleted = " where is_deleted IS NULL ";

    // ot_type 컬럼을 이용해 연장근무만 필터링
    $overtimeFilter = " AND ot_type IS NOT NULL ";
    
    // 년월 필터링 (al_askdatefrom 기준)
    $yearMonthFilter = " AND DATE_FORMAT(al_askdatefrom, '%Y-%m') = '" . $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "' ";

    // 재직 직원 정보 로드 (연장근무는 제조파트, 지원파트만 대상)
    $employee_name_arr = array();
    $employee_id_arr = array();
    $employee_part_arr = array();

    try {
        // 연장근무는 제조파트와 지원파트 직원만 대상
        $sql_employee = "SELECT * FROM {$DB}.member 
                         WHERE position != '퇴사' 
                         AND (part = '제조파트' OR part = '지원파트') 
                         ORDER BY part ASC, name ASC";
        $stmh_employee = $pdo->query($sql_employee);
        while ($row_employee = $stmh_employee->fetch(PDO::FETCH_ASSOC)) {
            $name = $row_employee["name"] ?? '';
            $id = $row_employee["id"] ?? '';
            $part = $row_employee["part"] ?? '';

            // 제조파트, 지원파트만 추가
            if ($name && $id && ($part == '제조파트' || $part == '지원파트')) {
                array_push($employee_name_arr, $name);
                array_push($employee_id_arr, $id);
                array_push($employee_part_arr, $part);
                
                error_log("연장근무 대상 직원 로드: name='{$name}', id='{$id}', part='{$part}'");
            }
        }
        
        error_log("총 로드된 연장근무 대상 직원 수: " . count($employee_name_arr));
        error_log("직원 이름 목록: " . implode(', ', $employee_name_arr));
    } catch (PDOException $ex) {
        error_log("재직 직원 조회 오류: " . $ex->getMessage());
    }
    ?>

    <form name="board_form" id="board_form" method="post" action="index.php?mode=search&search=<?= $search ?>&year=<?= $year ?>&month=<?= $month ?>">
        <?php if ($chkMobile == false) { ?>
            <div class="container">
            <?php } else { ?>
                <div class="container-fluid">
                <?php } ?>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-2">
                            <span class="fs-5"> <?= $title_message ?> </span>
                            <button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'><i class="bi bi-arrow-clockwise"></i></button>
                        </div>

                        <div class="d-flex justify-content-center mt-1 mb-3">
                            <span class="badge bg-secondary fs-6 me-4"> 결재 진행중에는 수정, 삭제가 불가합니다. </span>
                            <span class="text-secondary fs-6"> 진행순서 : 결재상신 → 1차결재 → 처리완료 </span>
                        </div>

                        <?php
                        if ($mode == "search" || $mode == "") {
                            if ($search == "") {
                                if ($admin == 1) {
                                    $sql = "SELECT * FROM " . $DB . "." . $tablename . $WhereisDeleted . $overtimeFilter . $yearMonthFilter . " ORDER BY al_askdatefrom DESC, registdate DESC LIMIT $first_num, $scale";
                                    $sqlcon = "SELECT * FROM " . $DB . "." . $tablename . $WhereisDeleted . $overtimeFilter . $yearMonthFilter . " ORDER BY al_askdatefrom DESC, registdate DESC";
                                } else {
                                    $sql = "SELECT * FROM " . $DB . "." . $tablename . " WHERE author LIKE '%$user_name%' " . $AndisDeleted . $overtimeFilter . $yearMonthFilter . " ORDER BY al_askdatefrom DESC, registdate DESC LIMIT $first_num, $scale";
                                    $sqlcon = "SELECT * FROM " . $DB . "." . $tablename . " WHERE author LIKE '%$user_name%' " . $AndisDeleted . $overtimeFilter . $yearMonthFilter . " ORDER BY al_askdatefrom DESC, registdate DESC";
                                }
                            } elseif ($search != "") {
                                if ($admin == 1) {
                                    $sql = "SELECT * FROM " . $DB . "." . $tablename . " WHERE (author LIKE '%$search%') " . $AndisDeleted . $overtimeFilter . $yearMonthFilter;
                                    $sql .= " ORDER BY al_askdatefrom DESC, registdate DESC LIMIT $first_num, $scale";
                                    $sqlcon = "SELECT * FROM " . $DB . "." . $tablename . " WHERE (author LIKE '%$search%') " . $AndisDeleted . $overtimeFilter . $yearMonthFilter;
                                    $sqlcon .= " ORDER BY al_askdatefrom DESC, registdate DESC";
                                } else {
                                    $sql = "SELECT * FROM " . $DB . "." . $tablename . " WHERE (author = '$user_name') AND (author LIKE '%$search%') " . $AndisDeleted . $overtimeFilter . $yearMonthFilter;
                                    $sql .= " ORDER BY al_askdatefrom DESC, registdate DESC LIMIT $first_num, $scale";
                                    $sqlcon = "SELECT * FROM " . $DB . "." . $tablename . " WHERE (author = '$user_name') AND (author LIKE '%$search%') " . $AndisDeleted . $overtimeFilter . $yearMonthFilter;
                                    $sqlcon .= " ORDER BY al_askdatefrom DESC, registdate DESC";
                                }
                            }
                        }

                        // 변수 초기화
                        $num = '';
                        $author_id = '';
                        $author = '';
                        $al_part = '';
                        $registdate = '';
                        $ot_type = '';  // 연장근무 유형
                        $al_askdatefrom = '';  // 작업일자
                        $ot_start_time = '';  // 시작시간
                        $ot_end_time = '';  // 종료시간
                        $al_usedday = 0;  // 연장근무 시간
                        $al_content = '';  // 사유
                        $status = '';

                        try {
                            $stmh = $pdo->query($sql);
                            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                $num = $row["num"] ?? '';
                                $author_id = $row["author_id"] ?? '';
                                $author = $row["author"] ?? '';
                                $al_part = $row["al_part"] ?? '';
                                $registdate = $row["registdate"] ?? '';
                                $ot_type = $row["ot_type"] ?? '';
                                $al_askdatefrom = $row["al_askdatefrom"] ?? '';
                                $ot_start_time = $row["ot_start_time"] ?? '';
                                $ot_end_time = $row["ot_end_time"] ?? '';
                                $al_usedday = $row["al_usedday"] ?? 0;
                                $al_content = $row["al_content"] ?? '';
                                $status = $row["status"] ?? '';
                            }
                        } catch (PDOException $ex) {
                            error_log("연장근무 조회 오류: " . $ex->getMessage());
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

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2" style="font-weight: 500;">년월 설정</span>
                                            <select name="year" id="year" class="form-select form-select-sm w-auto text-center me-2">
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
                                        <div class="d-flex align-items-center">
                                            &nbsp;&nbsp;&nbsp; ▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;
                                            <input type="text" name="search" id="search" class="form-control me-1" style="width:180px;" value="<?= $search ?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" placeholder="검색어">
                                            <button type="button" id="searchBtn" class="btn btn-dark btn-sm ms-1 me-1"><i class="bi bi-search"></i> 검색</button>
                                            <button type="button" id="writeBtn" class="btn btn-primary btn-sm ms-1 me-1"><i class="bi bi-pencil-square"></i> 신청</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">번호</th>
                                            <th class="text-center">신청일</th>
                                            <th class="text-center">작업일자</th>
                                            <th class="text-center">근무유형</th>
                                            <th class="text-center">시작시간</th>
                                            <th class="text-center">종료시간</th>
                                            <th class="text-center">연장시간</th>
                                            <th class="text-center">성명</th>
                                            <th class="text-center">사유</th>
                                            <th class="text-center">결재상태</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($page <= 1)
                                            $start_num = $total_row;
                                        else
                                            $start_num = $total_row - ($page - 1) * $scale;

                                        $stmh = $pdo->query($sql);
                                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                            $num = $row["num"] ?? '';
                                            $author = $row["author"] ?? '';
                                            $registdate = $row["registdate"] ?? '';
                                            $ot_type = $row["ot_type"] ?? '';
                                            $al_askdatefrom = $row["al_askdatefrom"] ?? '';
                                            $ot_start_time = $row["ot_start_time"] ?? '';
                                            $ot_end_time = $row["ot_end_time"] ?? '';
                                            $al_usedday = $row["al_usedday"] ?? 0;
                                            $al_content = $row["al_content"] ?? '';
                                            $status = $row["status"] ?? '';

                                            switch ($status) {
                                                case 'send':
                                                    $statusstr = '결재상신';
                                                    break;
                                                case 'ing':
                                                    $statusstr = '결재중';
                                                    break;
                                                case 'end':
                                                    $statusstr = '결재완료';
                                                    break;
                                                default:
                                                    $statusstr = '';
                                                    break;
                                            }
                                        ?>
                                            <tr style="cursor:pointer;" 
                                                data-num="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>"
                                                data-registdate="<?= htmlspecialchars(substr($registdate, 0, 10), ENT_QUOTES, 'UTF-8') ?>"
                                                data-askdatefrom="<?= htmlspecialchars($al_askdatefrom, ENT_QUOTES, 'UTF-8') ?>"
                                                data-ot-type="<?= htmlspecialchars($ot_type, ENT_QUOTES, 'UTF-8') ?>"
                                                data-start-time="<?= htmlspecialchars($ot_start_time, ENT_QUOTES, 'UTF-8') ?>"
                                                data-end-time="<?= htmlspecialchars($ot_end_time, ENT_QUOTES, 'UTF-8') ?>"
                                                data-usedday="<?= htmlspecialchars($al_usedday, ENT_QUOTES, 'UTF-8') ?>"
                                                data-author="<?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?>"
                                                data-content="<?= htmlspecialchars($al_content, ENT_QUOTES, 'UTF-8') ?>"
                                                data-status="<?= htmlspecialchars($statusstr, ENT_QUOTES, 'UTF-8') ?>">
                                                <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                                <td class="text-center" data-label="신청일"><?= substr($registdate, 0, 10) ?></td>
                                                <td class="text-center" data-label="작업일자"><?= htmlspecialchars($al_askdatefrom, ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-center" data-label="근무유형"><?= htmlspecialchars($ot_type, ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-center" data-label="시작시간"><?= htmlspecialchars($ot_start_time, ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-center" data-label="종료시간"><?= htmlspecialchars($ot_end_time, ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-center" data-label="연장시간"><?= htmlspecialchars($al_usedday, ENT_QUOTES, 'UTF-8') ?>시간</td>
                                                <td class="text-center" data-label="성명"><?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-center" data-label="사유"><?= htmlspecialchars($al_content, ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-center" data-label="결재상태"><?= htmlspecialchars($statusstr, ENT_QUOTES, 'UTF-8') ?></td>
                                            </tr>
                                        <?php
                                            $start_num--;
                                        }
                                    } catch (PDOException $ex) {
                                        error_log("연장근무 페이징 조회 오류: " . $ex->getMessage());
                                    }

                                    $start_page = ($current_page - 1) * $page_scale + 1;
                                    $end_page = $start_page + $page_scale - 1;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- 모바일 카드 컨테이너 -->
                            <div id="mobile-cards-container"></div>

                            <div class="d-flex justify-content-center mt-5 mb-5">
                                <div class="input-group p-1 mb-2 mt-2 justify-content-center">
                                    <?php
                                    if ($page != 1 && $page > $page_scale) {
                                        $prev_page = $page - $page_scale;
                                        if ($prev_page <= 0)
                                            $prev_page = 1;
                                        print "<a href='index.php?page=$prev_page&mode=search&search=$search&year=$year&month=$month'>◀ </a>";
                                    }
                                    for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                                        if ($page == $i)
                                            print "<font color=red><b>[$i]</b></font>";
                                        else
                                            print "<a href='index.php?page=$i&mode=search&search=$search&year=$year&month=$month'>[$i]</a>";
                                    }
                                    if ($page < $total_page) {
                                        $next_page = $page + $page_scale;
                                        if ($next_page > $total_page)
                                            $next_page = $total_page;
                                        print "<a href='index.php?page=$next_page&mode=search&search=$search&year=$year&month=$month'> ▶</a><p>";
                                    }
                                    ?>
                                </div>
                            </div>
                    </div>
                </div>
                </div>
    </form>

    <!-- 모달 창 -->
    <div class="modal fade" id="overtimeModal" tabindex="-1" aria-labelledby="overtimeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="overtimeModalLabel">연장근무 신청</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="overtimeForm">
                        <input type="hidden" id="mode" name="mode" value="insert">
                        <input type="hidden" id="num" name="num" value="">
                        <input type="hidden" id="author_id" name="author_id" value="">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="author" class="form-label">성명</label>
                                <select class="form-select w-auto" id="author" name="author" style="font-size: 0.7rem;height: 32px;" required>
                                    <option value="">선택하세요</option>
                                    <?php
                                    for ($i = 0; $i < count($employee_name_arr); $i++) {
                                        if ($user_name == $employee_name_arr[$i]) {
                                            echo "<option selected value='" . htmlspecialchars($employee_name_arr[$i]) . "'>" . htmlspecialchars($employee_name_arr[$i]) . "</option>";
                                        } else {
                                            echo "<option value='" . htmlspecialchars($employee_name_arr[$i]) . "'>" . htmlspecialchars($employee_name_arr[$i]) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="al_part" class="form-label">부서</label>
                                <input type="text" class="form-control w-auto" id="al_part" name="al_part" readonly style="font-size: 0.7rem;height: 32px;">
                            </div>
                        </div>

                        <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ot_type" class="form-label">근무유형</label>
                            <select class="form-select w-auto" id="ot_type" name="ot_type" style="font-size: 0.7rem;height: 32px;" required>
                                <option value="">선택하세요</option>
                                <option value="잔업">잔업 (평일 연장근무)</option>
                                <option value="특근">특근 (주말/휴일 근무)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="al_askdatefrom" class="form-label">작업일자</label>
                            <input type="date" class="form-control w-auto" id="al_askdatefrom" name="al_askdatefrom" style="font-size: 0.7rem;height: 32px;" required>
                        </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="ot_start_time" class="form-label">시작시간</label>
                                <input type="time" class="form-control" id="ot_start_time" name="ot_start_time" required>
                            </div>
                            <div class="col-md-3">
                                <label for="ot_end_time" class="form-label">종료시간</label>
                                <input type="time" class="form-control" id="ot_end_time" name="ot_end_time" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="meal_deduction" name="meal_deduction" value="1" checked>
                                    <label class="form-check-label" for="meal_deduction">
                                        식사시간 공제 (30분)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_time" name="remember_time" value="1" checked>
                                    <label class="form-check-label" for="remember_time">
                                        기억하기
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 빠른 시간 선택 버튼 영역 -->
                        <div class="mb-3" id="quick_time_buttons" style="display:none;">
                            <label class="form-label text-muted">
                                <i class="bi bi-clock"></i> 빠른 시간 선택 (30분 단위)
                            </label>
                            <div id="time_button_container" class="d-flex flex-wrap gap-2">
                                <!-- 버튼들이 동적으로 생성됩니다 -->
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="al_usedday" class="form-label">연장시간 (시간)</label>
                            <input type="number" step="0.5" class="form-control" id="al_usedday" name="al_usedday" placeholder="예: 2.5 (2시간 30분)" readonly>
                            <small class="text-muted">시작/종료 시간으로 자동 계산됩니다. 식사시간 공제 체크 시 30분 차감됩니다.</small>
                        </div>

                        <div class="mb-3">
                            <label for="al_content" class="form-label">
                                신청사유
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="voiceBtn" title="음성으로 입력하기">
                                    <i class="bi bi-mic-fill"></i>
                                </button>
                            </label>
                            <textarea class="form-control" id="al_content" name="al_content" rows="3" placeholder="연장근무 사유를 입력하세요" required></textarea>
                        </div>

                        <div id="statusDisplay" class="mb-3" style="display:none;">
                            <label class="form-label">결재상태</label>
                            <input type="text" class="form-control" id="statusstr" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="button" class="btn btn-danger" id="deleteBtn" style="display:none;">삭제</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">저장</button>
                </div>
            </div>
        </div>
    </div>

<script>
// 전역 변수로 선언 (모든 함수에서 접근 가능)
var employeeNameArray = <?= json_encode($employee_name_arr); ?>;
var employeePartArray = <?= json_encode($employee_part_arr); ?>;
var employeeIdArray = <?= json_encode($employee_id_arr); ?>;
var isAdmin = <?= $admin ?>;
var currentUserId = '<?= $user_id ?>';
var currentUserName = '<?= $user_name ?>';

// 🔥 선택된 직원 정보를 저장하는 전역 변수 (중요!)
var selectedEmployeeInfo = {
    name: '',
    id: '',
    part: ''
};

// 전역 함수: 성명 선택 시 부서와 ID 자동 매칭
function updatePartAndId(selectedName) {
    console.log('--- updatePartAndId() 함수 실행 시작 ---');

    // 파라미터가 없으면 드롭다운에서 직접 읽기 (모달 표시 시)
    if (!selectedName) {
        selectedName = $("#author").val();
    }

    var index = employeeNameArray.indexOf(selectedName);

    console.log('1. 선택된 이름 (파라미터):', selectedName);
    console.log('2. 배열에서 찾은 인덱스:', index);

    if (index !== -1) {
        var partValue = employeePartArray[index];
        var idValue = employeeIdArray[index];

        console.log('3. 찾은 부서:', partValue);
        console.log('4. 찾은 ID:', idValue);

        // 부서 필드 설정 전 값
        console.log('5-1. 부서 필드 설정 전:', $('#al_part').val());

        $("#al_part").val(partValue).prop('value', partValue);
        $("#author_id").val(idValue).prop('value', idValue);
        document.getElementById('al_part').value = partValue;
        document.getElementById('author_id').value = idValue;

        // 🔥 전역 변수에 저장 (중요!)
        selectedEmployeeInfo.name = selectedName;
        selectedEmployeeInfo.id = idValue;
        selectedEmployeeInfo.part = partValue;

        // 부서 필드 설정 후 값
        console.log('5-2. 부서 필드 설정 후 (jQuery):', $('#al_part').val());
        console.log('5-3. 부서 필드 설정 후 (DOM):', document.getElementById('al_part').value);
        console.log('5-4. author_id 필드 설정 후:', $('#author_id').val());
        console.log('5-5. 🔥 전역 변수에 저장됨:', selectedEmployeeInfo);
        console.log('✅ 부서와 ID 설정 완료:', partValue, '/', idValue);

        // 🔥 신규 작성 모드일 때만 근무유형 자동 선택
        var currentMode = $('#mode').val();
        console.log('6. 현재 모드:', currentMode);
        
        if (currentMode === 'insert') {
            if (partValue === '지원파트') {
                $('#ot_type').val('특근');
                console.log('✅ 지원파트 감지 → 근무유형 "특근" 자동 선택');
                
                // 특근: 시작시간 08:00, 기본 8시간 (종료시간 16:00)
                $('#ot_start_time').val('08:00');
                console.log('✅ 특근 선택 → 시작시간 08:00 자동 설정');
                
                // 기본 8시간 후로 설정 (08:00 + 8시간 = 16:00)
                // 쿠키가 있으면 나중에 loadFormFromCookie()에서 덮어씀
                setEndTimeByHours(8);
                console.log('✅ 특근 기본: 종료시간 16:00 (8시간) 자동 설정');
                
                // 빠른 시간 선택 버튼 생성
                generateTimeButtons();
                console.log('✅ 빠른 시간 선택 버튼 생성');
                
            } else if (partValue === '제조파트') {
                $('#ot_type').val('잔업');
                console.log('✅ 제조파트 감지 → 근무유형 "잔업" 자동 선택');
                
                // 잔업: 시작시간 17:00, 기본 3시간 (종료시간 20:00)
                $('#ot_start_time').val('17:00');
                console.log('✅ 잔업 선택 → 시작시간 17:00 자동 설정');
                
                // 기본 3시간 후로 설정 (17:00 + 3시간 = 20:00)
                // 쿠키가 있으면 나중에 loadFormFromCookie()에서 덮어씀
                setEndTimeByHours(3);
                console.log('✅ 잔업 기본: 종료시간 20:00 (3시간) 자동 설정');
                
                // 빠른 시간 선택 버튼 생성
                generateTimeButtons();
                console.log('✅ 빠른 시간 선택 버튼 생성');
            }
        } else {
            console.log('ℹ️ 수정 모드이므로 근무유형 자동 선택하지 않음 (기존 값 유지)');
        }
    } else {
        console.log('❌ 선택된 이름이 배열에 없음');
        console.log('   배열 내용:', employeeNameArray);
        $("#al_part").val('').prop('value', '');
        $("#author_id").val('').prop('value', '');
        document.getElementById('al_part').value = '';
        document.getElementById('author_id').value = '';
        
        // 전역 변수 초기화
        selectedEmployeeInfo.name = '';
        selectedEmployeeInfo.id = '';
        selectedEmployeeInfo.part = '';
    }

    console.log('--- updatePartAndId() 함수 실행 종료 ---');
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
        // 실제 연장근무 리스트 테이블만 선택
        // 여러 선택자 시도
        var $targetTable = $('.d-flex.justify-content-center table.table').first();
        
        if ($targetTable.length === 0) {
            $targetTable = $('.card-body table.table').first();
        }
        
        if ($targetTable.length === 0) {
            $targetTable = $('table.table-bordered.table-hover').first();
        }
        
        console.log('Target table found:', $targetTable.length);
        console.log('Target table selector:', $targetTable.length > 0 ? 'Found' : 'Not found');
        
        var rows;
        if ($targetTable.length === 0) {
            // 대체: 모든 테이블에서 data-num 속성이 있는 행 찾기
            rows = $('tbody tr[data-num]');
            console.log('Using fallback selector: rows with data-num attribute, count:', rows.length);
            
            // data-num이 없으면 모든 tbody tr에서 첫 번째 td가 숫자인 행 찾기
            if (rows.length === 0) {
                rows = $('tbody tr').filter(function() {
                    var $row = $(this);
                    var firstTd = $row.find('td:first').text().trim();
                    return /^\d+/.test(firstTd);
                });
                console.log('Using fallback selector 2: rows with numeric first td, count:', rows.length);
            }
        } else {
            rows = $targetTable.find('tbody tr');
            console.log('Using specific table selector, initial rows:', rows.length);
            
            // 각 행의 td 개수 확인 (디버깅)
            if (rows.length > 0) {
                console.log('First row td count:', rows.first().find('td').length);
                console.log('First row data-num:', rows.first().attr('data-num'));
                console.log('First row first td text:', rows.first().find('td:first').text().trim());
            }
        }
        
        // 초기 필터링: td가 5개 이상인 행만 포함 (너무 엄격하지 않게)
        // td 개수 필터 제거 - 모든 행 포함하고 나중에 유효성 검사로 제외
        var allRows = rows;
        var rowsBeforeFilter = rows.length;
        
        console.log('Rows before filter:', rowsBeforeFilter);
        console.log('Using all rows without td count filter');
        
        if (rows.length > 0) {
            console.log('Sample row td count:', rows.first().find('td').length);
            console.log('Sample row first td:', rows.first().find('td:first').text().trim());
            console.log('Sample row data-num:', rows.first().attr('data-num'));
            console.log('Sample row html (first 200 chars):', rows.first().html().substring(0, 200));
        }
        
        console.log('Total rows found:', rows.length);
        console.log('Table element:', $('table.table').length);
        
        if (!rows || rows.length === 0) {
            console.log('No valid rows found in table');
            console.log('All tables:', $('table').length);
            console.log('All tbody:', $('tbody').length);
            console.log('All tbody tr:', $('tbody tr').length);
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
                if (index < 3) {
                    console.log('Row ' + index + ': Skipping - no td elements');
                }
                return;
            }
            
            // td에서 직접 데이터 읽기 (텍스트 노드 직접 접근)
            var num = '';
            var registdate = '';
            var askdatefrom = '';
            var otType = '';
            var startTime = '';
            var endTime = '';
            var usedday = '';
            var author = '';
            var content = '';
            var status = '';
            
            // 각 td에서 텍스트 추출 (더 안전한 방법)
            if ($tds.length > 0) num = $tds.eq(0).text().trim() || $tds.eq(0).html().trim();
            if ($tds.length > 1) registdate = $tds.eq(1).text().trim() || $tds.eq(1).html().trim();
            if ($tds.length > 2) askdatefrom = $tds.eq(2).text().trim() || $tds.eq(2).html().trim();
            if ($tds.length > 3) otType = $tds.eq(3).text().trim() || $tds.eq(3).html().trim();
            if ($tds.length > 4) startTime = $tds.eq(4).text().trim() || $tds.eq(4).html().trim();
            if ($tds.length > 5) endTime = $tds.eq(5).text().trim() || $tds.eq(5).html().trim();
            if ($tds.length > 6) usedday = $tds.eq(6).text().trim() || $tds.eq(6).html().trim();
            if ($tds.length > 7) author = $tds.eq(7).text().trim() || $tds.eq(7).html().trim();
            if ($tds.length > 8) content = $tds.eq(8).text().trim() || $tds.eq(8).html().trim();
            if ($tds.length > 9) status = $tds.eq(9).text().trim() || $tds.eq(9).html().trim();
            
            // data 속성도 확인 (우선순위 높음)
            var dataNum = $row.attr('data-num') || $row.data('num');
            if (dataNum) {
                num = dataNum;
            }
            
            // 디버깅: 첫 3개 행만 상세 로그
            if (index < 3) {
                console.log('Processing row ' + index + ':', {
                    num: num,
                    registdate: registdate,
                    askdatefrom: askdatefrom,
                    author: author,
                    tdCount: tdCount,
                    dataNum: dataNum,
                    firstTdText: $tds.eq(0).text().trim()
                });
            }
            
            // 유효성 검사: 잘못된 텍스트 값만 제외
            var invalidValues = ['no data available in table', '구분', '결재진행', '작성일시', '작성자', '결재라인', '참조', '번호'];
            var numLower = (num || '').toString().toLowerCase().trim();
            
            // 잘못된 텍스트 값인 경우 제외
            if (invalidValues.indexOf(numLower) !== -1) {
                skippedCount++;
                if (index < 3) {
                    console.log('Row ' + index + ': Skipping - num is invalid text', num);
                }
                return;
            }
            
            // num이 완전히 비어있는 경우만 제외 (숫자가 아니어도 허용)
            if (!num || num === '' || num === 'undefined' || num === 'null') {
                skippedCount++;
                if (index < 3) {
                    console.log('Row ' + index + ': Skipping - num is empty', num);
                }
                return;
            }
            
            // 추가 검증: author나 askdatefrom이 있는 행만 포함 (실제 데이터 행)
            if (!author && !askdatefrom) {
                skippedCount++;
                if (index < 3) {
                    console.log('Row ' + index + ': Skipping - no author or askdatefrom', {author: author, askdatefrom: askdatefrom});
                }
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
            
            var cardHtml = '<div class="mobile-card" data-num="' + escapeHtml(num) + '" style="cursor:pointer;">' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(num) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">신청일:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(registdate) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작업일자:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(askdatefrom) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">근무유형:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(otType) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">시작시간:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(startTime) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">종료시간:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(endTime) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">연장시간:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(usedday) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">성명:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(author) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">사유:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(content) + '</span>' +
                '</div>' +
                '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">결재상태:</span>' +
                '<span class="mobile-card-value">' + escapeHtml(status) + '</span>' +
                '</div>' +
                '</div>';
            
            $container.append(cardHtml);
            validCardCount++;
        });
        
        console.log('Mobile cards rendered: ' + validCardCount + ' valid cards');
        console.log('Skipped rows: ' + skippedCount);
        console.log('Total rows processed: ' + rows.length);
        
        // 유효한 카드가 없으면 메시지 표시
        if (validCardCount === 0) {
            console.warn('No valid cards rendered. Check console for details.');
            $container.append('<div class="text-center text-muted p-3">표시할 데이터가 없습니다.</div>');
        }
    } catch (error) {
        console.error('Error rendering mobile cards:', error);
    }
}

$(document).ready(function() {
    console.log('=== 직원 정보 로드 완료 ===');
    console.log('이름 배열:', employeeNameArray);
    console.log('부서 배열:', employeePartArray);
    console.log('ID 배열:', employeeIdArray);
    console.log('관리자 여부:', isAdmin);
    console.log('현재 사용자:', currentUserId, currentUserName);

    // 모바일 카드 렌더링 (테이블이 완전히 로드된 후)
    if (window.innerWidth <= 768) {
        // 여러 번 시도하여 테이블이 완전히 로드될 때까지 대기
        var renderAttempts = 0;
        var maxAttempts = 10;
        
        var tryRender = function() {
            renderAttempts++;
            var rows = $('tbody tr');
            console.log('Render attempt ' + renderAttempts + ': Found ' + rows.length + ' rows');
            
            if (rows.length > 0) {
                renderMobileCards();
            } else if (renderAttempts < maxAttempts) {
                setTimeout(tryRender, 300);
            } else {
                console.warn('Table not loaded after ' + maxAttempts + ' attempts');
                $('#mobile-cards-container').append('<div class="text-center text-muted p-3">데이터를 불러오는 중...</div>');
            }
        };
        
        setTimeout(tryRender, 200);
    }
    
    // 화면 크기 변경 시 카드 재렌더링
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth <= 768) {
                renderMobileCards();
            } else {
                $('#mobile-cards-container').html('');
            }
        }, 250);
    });

    // 폼 submit 완전 차단 (AJAX로만 전송)
    $('#overtimeForm').on('submit', function(e) {
        e.preventDefault();
        console.warn('폼 submit이 차단되었습니다. AJAX로만 전송합니다.');
        return false;
    });

    // 성명 선택 시 부서와 ID 자동 매칭 (이벤트 위임 사용)
    $(document).on('change', '#author', function(e) {
        console.log('');
        console.log('===== 성명 드롭다운 CHANGE 이벤트 발생 =====');
        console.log('이벤트 타입:', e.type);
        console.log('이벤트 시간:', new Date().toLocaleTimeString());
        console.log('이벤트 대상:', e.target.id);
        console.log('선택된 값 (this.value):', this.value);
        console.log('선택된 값 ($(this).val()):', $(this).val());
        console.log('선택된 옵션 텍스트:', $(this).find('option:selected').text());

        // 선택된 값을 파라미터로 전달
        updatePartAndId(this.value);

        console.log('===== 성명 드롭다운 CHANGE 이벤트 처리 완료 =====');
        console.log('');
    });

    // 검색
    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });

    // 신청 버튼
    $("#writeBtn").click(function() {
        resetModal();
        $('#overtimeModal').modal('show');
    });
    
    // 년월 선택 시 자동 검색
    $("#year, #month").change(function() {
        console.log('년월 변경:', $('#year').val() + '-' + $('#month').val());
        document.getElementById('board_form').submit();
    });

    // 모달이 완전히 표시된 후 처리
    $('#overtimeModal').on('shown.bs.modal', function () {
        var currentMode = $('#mode').val();
        console.log('모달이 표시되었습니다. 현재 모드:', currentMode);
        
        // 신규 작성 모드일 때만 부서 정보 자동 매칭
        // 수정 모드에서는 loadOvertimeData()에서 이미 전역 변수를 설정했으므로 불필요
        if (currentMode === 'insert') {
            console.log('신규 작성 모드: 부서 정보 자동 매칭 실행');
            updatePartAndId();
        } else if (currentMode === 'update') {
            console.log('수정 모드: 전역 변수 확인 -', selectedEmployeeInfo);
            console.log('성명 필드 값:', $('#author').val());
        }
        
        // 🔥 빠른 시간 선택 버튼이 항상 나오도록 시작시간 확인
        setTimeout(function() {
            var startTime = $('#ot_start_time').val();
            if (startTime) {
                // 버튼이 이미 생성되어 있는지 확인
                var buttonCount = $('#time_button_container button').length;
                if (buttonCount === 0) {
                    generateTimeButtons();
                    console.log('✅ 모달 표시 후: 빠른 시간 선택 버튼 생성 (시작시간:', startTime, ')');
                } else {
                    console.log('ℹ️ 빠른 시간 선택 버튼이 이미 생성되어 있음 (', buttonCount, '개)');
                }
            } else {
                console.log('ℹ️ 시작시간이 없어 빠른 시간 선택 버튼을 생성하지 않음');
            }
        }, 200); // 모든 값이 설정된 후 실행
    });

    // 테이블 행 클릭 (수정)
    $("tbody tr").click(function() {
        const num = $(this).data('num');
        if (num) {
            loadOvertimeData(num);
        }
    });
    
    // 모바일 카드 클릭 (수정)
    $(document).on('click', '.mobile-card', function() {
        const num = $(this).attr('data-num') || $(this).data('num');
        if (num) {
            loadOvertimeData(num);
        }
    });

    // 시작시간 변경 시 처리
    $("#ot_start_time").change(function() {
        const overtimeType = $('#ot_type').val();
        const startTime = $(this).val();
        
        console.log('시작시간 변경:', startTime, ', 근무유형:', overtimeType);
        
        // 특근이고 종료시간이 비어있으면 자동으로 8시간 후 설정
        if (overtimeType === '특근' && startTime && !$('#ot_end_time').val()) {
            setEndTimeByHours(8);
            console.log('✅ 특근: 시작시간 입력 → 8시간 후 자동 설정');
        } else {
            // 그 외의 경우는 연장시간 재계산
            calculateHours();
        }
        
        // 빠른 시간 선택 버튼 생성
        generateTimeButtons();
        
        // 쿠키 저장
        saveTimeToCookie();
    });

    // 종료시간 변경 시 연장시간 자동 계산 및 쿠키 저장
    $("#ot_end_time").change(function() {
        calculateHours();
        saveTimeToCookie();
    });

    // 식사시간 공제 체크박스 변경 시
    $("#meal_deduction").change(function() {
        calculateHours();
    });

    // 기억하기 체크박스 변경 시
    $("#remember_time").change(function() {
        if ($(this).is(':checked')) {
            console.log('✅ 기억하기 활성화: 폼 데이터가 저장 시 쿠키에 저장됩니다.');
            // 현재 설정된 값이 있으면 즉시 저장
            const currentStartTime = $('#ot_start_time').val();
            const currentEndTime = $('#ot_end_time').val();
            if (currentStartTime || currentEndTime) {
                saveTimeToCookie();
                console.log('현재 시간을 쿠키에 저장했습니다.');
            }
        } else {
            console.log('❌ 기억하기 비활성화: 모든 쿠키가 삭제됩니다.');
            // 모든 쿠키 삭제 (만료일을 과거로 설정)
            setCookie('overtime_ot_type', '', -1);
            setCookie('overtime_al_askdatefrom', '', -1);
            setCookie('overtime_ot_start_time', '', -1);
            setCookie('overtime_ot_end_time', '', -1);
            setCookie('overtime_meal_deduction', '', -1);
            setCookie('overtime_al_content', '', -1);
            console.log('✅ 모든 폼 데이터 쿠키 삭제 완료');
        }
    });

    // 근무유형 선택 변경 시
    $("#ot_type").change(function() {
        handleOvertimeTypeChange();
    });

    // 신청사유 선택 변경 시
    $("#al_content_select").change(function() {
        handleReasonChange();
    });

    // 직접입력 텍스트 변경 시
    $("#al_content_custom").on('input', function() {
        if ($("#al_content_select").val() === '직접입력') {
            $('#al_content').val($(this).val());
        }
    });

    // 저장 버튼
    $("#saveBtn").click(function(e) {
        e.preventDefault(); // 폼 submit 방지
        if (validateForm()) {
            saveOvertimeData();
        }
        return false; // 폼 submit 완전 차단
    });

    // 삭제 버튼
    $("#deleteBtn").click(function() {
        if (confirm('정말 삭제하시겠습니까?')) {
            deleteOvertimeData();
        }
    });

    // 오늘 날짜를 기본값으로 설정
    const today = new Date().toISOString().split('T')[0];
    $("#al_askdatefrom").val(today);
});

function SearchEnter() {
    if (event.keyCode == 13) {
        document.getElementById('board_form').submit();
    }
}

// 쿠키 저장 함수
function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
}

// 쿠키 읽기 함수
function getCookie(name) {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// 폼 데이터를 쿠키에 저장 (저장 버튼 클릭 시)
function saveFormToCookie() {
    if ($('#remember_time').is(':checked')) {
        const formData = {
            ot_type: $('#ot_type').val(),
            al_askdatefrom: $('#al_askdatefrom').val(),
            ot_start_time: $('#ot_start_time').val(),
            ot_end_time: $('#ot_end_time').val(),
            meal_deduction: $('#meal_deduction').is(':checked') ? '1' : '0',
            al_content: $('#al_content').val()
        };

        // 각 필드를 쿠키에 저장 (30일 유효)
        Object.keys(formData).forEach(key => {
            if (formData[key]) {
                setCookie('overtime_' + key, formData[key], 30);
            }
        });

        console.log('✅ 모든 폼 데이터 쿠키에 저장:', formData);
    } else {
        console.log('ℹ️ 기억하기가 체크되지 않아 쿠키에 저장하지 않음');
    }
}

// 시간 변경 시 즉시 쿠키 저장 (기존 기능 유지)
function saveTimeToCookie() {
    if ($('#remember_time').is(':checked')) {
        const startTime = $('#ot_start_time').val();
        const endTime = $('#ot_end_time').val();

        if (startTime) setCookie('overtime_ot_start_time', startTime, 30);
        if (endTime) setCookie('overtime_ot_end_time', endTime, 30);

        console.log('시간 쿠키 저장:', { startTime, endTime });
    }
}

// 쿠키에서 폼 데이터 불러오기 (신규 작성 모드에서만)
function loadFormFromCookie() {
    // 신규 작성 모드가 아니면 쿠키 불러오기 안 함
    const currentMode = $('#mode').val();
    if (currentMode !== 'insert') {
        console.log('수정 모드이므로 쿠키에서 데이터를 불러오지 않습니다.');
        return;
    }

    if ($('#remember_time').is(':checked')) {
        console.log('');
        console.log('=== 쿠키에서 폼 데이터 불러오기 (기억하기 기능) ===');
        
        const savedData = {
            ot_type: getCookie('overtime_ot_type'),
            al_askdatefrom: getCookie('overtime_al_askdatefrom'),
            ot_start_time: getCookie('overtime_ot_start_time'),
            ot_end_time: getCookie('overtime_ot_end_time'),
            meal_deduction: getCookie('overtime_meal_deduction'),
            al_content: getCookie('overtime_al_content')
        };

        console.log('저장된 데이터:', savedData);

        // 근무유형 복원 (부서별 자동 설정보다 우선)
        if (savedData.ot_type) {
            $('#ot_type').val(savedData.ot_type);
            console.log('✅ 근무유형 복원:', savedData.ot_type);
        }

        // 작업일자 복원
        if (savedData.al_askdatefrom) {
            $('#al_askdatefrom').val(savedData.al_askdatefrom);
            console.log('✅ 작업일자 복원:', savedData.al_askdatefrom);
        }

        // 시작시간 복원
        if (savedData.ot_start_time) {
            $('#ot_start_time').val(savedData.ot_start_time);
            console.log('✅ 시작시간 복원:', savedData.ot_start_time);
        }

        // 종료시간 복원
        if (savedData.ot_end_time) {
            $('#ot_end_time').val(savedData.ot_end_time);
            console.log('✅ 종료시간 복원:', savedData.ot_end_time);
        }

        // 식사시간 공제 복원
        if (savedData.meal_deduction) {
            $('#meal_deduction').prop('checked', savedData.meal_deduction === '1');
            console.log('✅ 식사시간 공제 복원:', savedData.meal_deduction === '1');
        }

        // 신청사유 복원
        if (savedData.al_content) {
            $('#al_content').val(savedData.al_content);
            console.log('✅ 신청사유 복원:', savedData.al_content);
        }

        // 시간이 모두 있으면 자동 계산
        if (savedData.ot_start_time && savedData.ot_end_time) {
            calculateHours();
            console.log('✅ 연장시간 자동 계산 완료');
        }
        
        // 시작시간이 있으면 빠른 시간 선택 버튼 생성
        if (savedData.ot_start_time) {
            generateTimeButtons();
            console.log('✅ 빠른 시간 선택 버튼 생성 완료');
        }
        
        console.log('===========================================');
        console.log('');
    } else {
        console.log('기억하기 체크박스가 해제되어 있어 쿠키를 불러오지 않습니다.');
    }
}

function resetModal() {
    console.log('===== resetModal() 함수 실행 =====');

    $('#overtimeForm')[0].reset();
    $('#mode').val('insert');
    $('#num').val('');
    $('#al_content').val('');
    $('#statusDisplay').hide();
    $('#deleteBtn').hide();
    $('#saveBtn').text('저장');
    $('#saveBtn').show(); // 저장 버튼 표시
    $('#overtimeModalLabel').text('연장근무 신청');

    // *** 중요: 모든 필드를 입력 가능 상태로 활성화 ***
    console.log('모든 필드 활성화 (disabled 속성 제거)');
    $('#overtimeForm input, #overtimeForm select, #overtimeForm textarea').prop('disabled', false);

    // readonly 속성은 유지 (al_part는 항상 readonly)
    $('#al_part').prop('readonly', true);
    $('#statusstr').prop('readonly', true);

    const today = new Date().toISOString().split('T')[0];
    $("#al_askdatefrom").val(today);

    // 폼 리셋 후 현재 로그인한 사용자를 자동으로 선택
    var currentUser = '<?= $user_name ?>';
    console.log('현재 로그인 사용자:', currentUser);

    // 성명 드롭다운에 현재 사용자 설정
    $('#author').val(currentUser);
    console.log('성명 드롭다운에 설정 후 값:', $('#author').val());

    // 성명이 설정되었으므로 부서와 ID도 자동으로 설정
    // (change 이벤트가 발생하지 않으므로 명시적으로 호출)
    // ✅ updatePartAndId() 안에서 부서에 따른 근무유형 자동 선택 및 시간 설정이 이루어짐
    console.log('resetModal에서 updatePartAndId() 호출');
    
    // 기억하기 체크박스 기본값으로 체크
    $('#remember_time').prop('checked', true);
    
    // updatePartAndId() 호출 (부서별 근무유형 자동 선택 포함)
    updatePartAndId(currentUser);
    
    // 🔥 쿠키에서 모든 폼 데이터 불러오기 (쿠키가 있으면 부서별 기본값을 덮어씀)
    loadFormFromCookie();
    
    // 🔥 빠른 시간 선택 버튼이 항상 나오도록 시작시간 확인
    setTimeout(function() {
        var startTime = $('#ot_start_time').val();
        if (startTime) {
            generateTimeButtons();
            console.log('✅ resetModal: 빠른 시간 선택 버튼 생성 (시작시간:', startTime, ')');
        }
    }, 100); // 약간의 지연을 두어 모든 값이 설정된 후 실행

    console.log('===== resetModal() 함수 종료 =====');
}

function handleOvertimeTypeChange() {
    const overtimeType = $('#ot_type').val();

    console.log('근무유형 변경:', overtimeType);

    if (overtimeType === '잔업') {
        // 잔업 선택 시 시작시간을 오후 5:00 (17:00)로 자동 설정
        $('#ot_start_time').val('17:00');
        console.log('✅ 잔업 선택 → 시작시간 17:00 자동 설정');
        
        // 종료시간이 없으면 기본 3시간 후 (20:00) 자동 설정
        if (!$('#ot_end_time').val()) {
            setEndTimeByHours(3);
            console.log('✅ 잔업 선택 → 종료시간 20:00 (3시간) 자동 설정');
        } else {
            // 종료시간이 있으면 연장시간 재계산
            calculateHours();
        }
        
        // 빠른 시간 선택 버튼 생성
        generateTimeButtons();
        
    } else if (overtimeType === '특근') {
        console.log('✅ 특근 선택');
        
        // 특근: 시작시간 08:00, 기본 8시간 (종료시간 16:00)
        $('#ot_start_time').val('08:00');
        console.log('✅ 특근 선택 → 시작시간 08:00 자동 설정');
        
        // 종료시간이 없으면 기본 8시간 후 (16:00) 자동 설정
        if (!$('#ot_end_time').val()) {
            setEndTimeByHours(8);
            console.log('✅ 특근 선택 → 종료시간 16:00 (8시간) 자동 설정');
        } else {
            // 종료시간이 있으면 연장시간 재계산
            calculateHours();
        }
        
        // 빠른 시간 선택 버튼 생성
        generateTimeButtons();
        console.log('✅ 빠른 시간 선택 버튼 생성');
    }
}

function handleReasonChange() {
    const selectedValue = $('#al_content_select').val();

    if (selectedValue === '직접입력') {
        $('#customReasonDiv').show();
        $('#al_content').val($('#al_content_custom').val());
        $('#al_content_custom').focus();
    } else {
        $('#customReasonDiv').hide();
        $('#al_content').val(selectedValue);
        $('#al_content_custom').val('');
    }
}

function loadOvertimeData(num) {
    $.ajax({
        url: 'process.php',
        type: 'POST',
        data: { mode: 'load', num: num },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                console.log('');
                console.log('=== 연장근무 데이터 로드 (수정 모드) ===');
                console.log('불러온 데이터:', data.data);
                
                $('#mode').val('update');
                $('#num').val(data.data.num);
                
                // 🔥 성명 필드 설정 (jQuery prop 사용으로 안전하게)
                $('#author option').prop('selected', false);  // 모든 옵션 선택 해제
                $('#author option[value="' + data.data.author + '"]').prop('selected', true);  // 해당 옵션 선택
                $('#author').val(data.data.author);  // 값 설정
                
                console.log('성명 필드 설정:', data.data.author, '→ 실제 값:', $('#author').val());
                console.log('선택된 옵션:', $('#author option:selected').text());
                
                $('#author_id').val(data.data.author_id);
                $('#al_part').val(data.data.al_part);
                $('#ot_type').val(data.data.ot_type);
                $('#al_askdatefrom').val(data.data.al_askdatefrom);
                $('#ot_start_time').val(data.data.ot_start_time);
                $('#ot_end_time').val(data.data.ot_end_time);
                $('#al_usedday').val(data.data.al_usedday);

                // 🔥 중요: 전역 변수도 업데이트 (수정 모드에서 기존 직원 정보 유지)
                selectedEmployeeInfo.name = data.data.author;
                selectedEmployeeInfo.id = data.data.author_id;
                selectedEmployeeInfo.part = data.data.al_part;
                
                console.log('🔥 전역 변수 업데이트됨 (수정 모드):', selectedEmployeeInfo);
                console.log('');

                // 식사시간 공제 체크박스 상태 복원 (which 컬럼에서 로드)
                if (data.data.meal_deduction === '1') {
                    $('#meal_deduction').prop('checked', true);
                } else {
                    $('#meal_deduction').prop('checked', false);
                }

                // 신청사유 처리
                const reasonValue = data.data.al_content;
                const presetReasons = ['업무과다', '긴급업무처리', '납기준수', '설비점검', '기타'];

                if (presetReasons.includes(reasonValue)) {
                    // 미리 정의된 사유인 경우
                    $('#al_content_select').val(reasonValue);
                    $('#al_content').val(reasonValue);
                    $('#customReasonDiv').hide();
                } else {
                    // 직접입력한 사유인 경우
                    $('#al_content_select').val('직접입력');
                    $('#al_content_custom').val(reasonValue);
                    $('#al_content').val(reasonValue);
                    $('#customReasonDiv').show();
                }

                if (data.data.status) {
                    let statusstr = '';
                    switch(data.data.status) {
                        case 'send': statusstr = '결재상신'; break;
                        case 'ing': statusstr = '결재중'; break;
                        case 'end': statusstr = '결재완료'; break;
                    }
                    $('#statusstr').val(statusstr);
                    $('#statusDisplay').show();
                }

                // 수정 권한 확인: 관리자와 본인의 권한 차별화
                var hasEditPermission = false;
                var isOwner = data.data.author_id === currentUserId;
                var currentStatus = data.data.status || ''; // 결재 상태 (send, ing, end)
                var hasApprovalStarted = false; // 결재가 진행되었는지 여부

                console.log('=== 수정 권한 확인 시작 ===');
                console.log('1. 원본 데이터:', {
                    num: data.data.num,
                    author: data.data.author,
                    author_id: data.data.author_id,
                    status: currentStatus
                });
                console.log('2. 현재 사용자 정보:', {
                    currentUserId: currentUserId,
                    currentUserName: currentUserName,
                    isAdmin: isAdmin
                });
                console.log('3. 본인 여부 체크:', {
                    'data.author_id': data.data.author_id,
                    'currentUserId': currentUserId,
                    '===비교 결과': data.data.author_id === currentUserId,
                    'isOwner': isOwner
                });

                // status로 결재 진행 여부 확인
                // status가 'send'가 아니면 (즉, 'ing'이나 'end'이면) 결재가 진행된 것
                if (currentStatus && currentStatus !== 'send' && currentStatus !== '') {
                    hasApprovalStarted = true;
                    console.log('4. 결재 진행 상태:', {
                        'status': currentStatus,
                        'hasApprovalStarted': true,
                        '설명': 'status가 send가 아니므로 결재 진행됨'
                    });
                } else {
                    console.log('4. 결재 진행 상태:', {
                        'status': currentStatus,
                        'hasApprovalStarted': false,
                        '설명': 'status가 send이거나 비어있으므로 결재 미진행'
                    });
                }

                // 수정 권한 판단:
                // 1. 관리자는 무조건 수정 가능
                // 2. 본인은 결재가 진행되면 수정 불가
                if (isAdmin == 1) {
                    hasEditPermission = true;
                    console.log('✅ 최종 결정: 관리자이므로 무조건 수정 가능');
                } else if (isOwner) {
                    if (hasApprovalStarted) {
                        hasEditPermission = false;
                        console.log('❌ 최종 결정: 본인 작성글이지만 결재가 진행되어 수정 불가');
                    } else {
                        hasEditPermission = true;
                        console.log('✅ 최종 결정: 본인 작성글이고 결재 진행 전이므로 수정 가능');
                    }
                } else {
                    hasEditPermission = false;
                    console.log('❌ 최종 결정: 본인도 아니고 관리자도 아니므로 수정 불가');
                }

                console.log('=== 최종 권한 결정:', hasEditPermission, '===');

                if (hasEditPermission) {
                    // 수정 권한 있음: 모든 필드 활성화
                    console.log('✅ 수정 권한 있음 - 모든 필드 활성화');
                    $('#overtimeForm input, #overtimeForm select').prop('disabled', false);

                    // 관리자가 아니고 본인이 아닌 경우: 성명 변경 불가
                    if (isAdmin != 1 && !isOwner) {
                        console.log('본인이 아니므로 성명 변경 불가');
                        $('#author').prop('disabled', true);
                    } else {
                        console.log('관리자이거나 본인이므로 성명 변경 가능');
                        $('#author').prop('disabled', false);
                    }

                    $('#saveBtn').show();
                    $('#saveBtn').text('수정');
                    $('#deleteBtn').show();
                } else {
                    // 수정 권한 없음: 모든 필드 비활성화 (읽기 전용)
                    console.log('❌ 수정 권한 없음 - 읽기 전용 모드');
                    $('#overtimeForm input, #overtimeForm select').not('#statusstr').prop('disabled', true);
                    $('#saveBtn').hide();
                    $('#deleteBtn').hide();
                }

                $('#overtimeModalLabel').text('연장근무 수정');
                
                // 🔥 수정 모드에서도 빠른 시간 선택 버튼 생성
                if (data.data.ot_start_time) {
                    generateTimeButtons();
                    console.log('✅ 수정 모드: 빠른 시간 선택 버튼 생성 (시작시간:', data.data.ot_start_time, ')');
                }
                
                $('#overtimeModal').modal('show');
            } else {
                alert(data.message || '데이터 로드 실패');
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
}

function calculateHours() {
    const startTime = $('#ot_start_time').val();
    const endTime = $('#ot_end_time').val();

    if (startTime && endTime) {
        const start = new Date('2000-01-01 ' + startTime);
        const end = new Date('2000-01-01 ' + endTime);

        let diff = (end - start) / (1000 * 60 * 60); // 시간 단위

        // 종료시간이 시작시간보다 이르면 다음날로 간주
        if (diff < 0) {
            diff += 24;
        }

        // 식사시간 공제 체크박스 확인
        const isMealDeduction = $('#meal_deduction').is(':checked');
        if (isMealDeduction) {
            // 30분 (0.5시간) 차감
            diff -= 0.5;
            console.log('식사시간 30분 공제 적용');
        }

        // 음수 방지
        if (diff < 0) {
            diff = 0;
        }

        $('#al_usedday').val(diff.toFixed(1));
        console.log('계산된 연장시간:', diff.toFixed(1), '시간 (식사시간 공제:', isMealDeduction, ')');
    }
}

/**
 * 시작시간으로부터 지정된 시간만큼 후의 종료시간을 자동으로 설정하는 함수
 * @param {number} hours - 연장 근무 시간 (3 또는 8)
 */
function setEndTimeByHours(hours) {
    const startTime = $('#ot_start_time').val();
    
    if (!startTime) {
        console.warn('시작시간이 설정되지 않아 종료시간을 계산할 수 없습니다.');
        return;
    }
    
    // 시작시간을 Date 객체로 변환
    const start = new Date('2000-01-01 ' + startTime);
    
    // 지정된 시간(hours)을 밀리초로 변환하여 더하기
    const endTimeMs = start.getTime() + (hours * 60 * 60 * 1000);
    const end = new Date(endTimeMs);
    
    // 시간과 분을 추출
    let endHours = end.getHours();
    let endMinutes = end.getMinutes();
    
    // HH:MM 형식으로 변환
    const endTimeStr = String(endHours).padStart(2, '0') + ':' + String(endMinutes).padStart(2, '0');
    
    // 종료시간 설정
    $('#ot_end_time').val(endTimeStr);
    
    // 연장시간 자동 계산
    calculateHours();
    
    console.log('✅ setEndTimeByHours: 시작시간', startTime, '+ ', hours, '시간 =', endTimeStr);
}

function calculateEndTime() {
    const startTime = $('#ot_start_time').val();
    const hours = parseFloat($('#al_usedday').val());

    if (startTime && hours && hours > 0) {
        // 시작시간을 Date 객체로 변환
        const start = new Date('2000-01-01 ' + startTime);

        // 입력된 시간(소수점 포함)을 밀리초로 변환하여 더하기
        // 예: 2.5시간 = 2시간 30분
        const endTimeMs = start.getTime() + (hours * 60 * 60 * 1000);
        const end = new Date(endTimeMs);

        // 시간과 분을 추출
        let endHours = end.getHours();
        let endMinutes = end.getMinutes();

        // 24시간을 넘어가면 다음날로 처리 (00:00 이후)
        if (endHours < start.getHours() && hours < 24) {
            // 정상적으로 다음날로 넘어간 경우는 그대로 유지
        }

        // HH:MM 형식으로 변환
        const endTimeStr = String(endHours).padStart(2, '0') + ':' + String(endMinutes).padStart(2, '0');

        $('#ot_end_time').val(endTimeStr);
    }
}

function validateForm() {
    if (!$('#ot_type').val()) {
        alert('근무유형을 선택하세요.');
        return false;
    }
    if (!$('#al_askdatefrom').val()) {
        alert('작업일자를 입력하세요.');
        return false;
    }
    if (!$('#ot_start_time').val() || !$('#ot_end_time').val()) {
        alert('시작시간과 종료시간을 입력하세요.');
        return false;
    }
    if (!$('#al_usedday').val() || parseFloat($('#al_usedday').val()) <= 0) {
        alert('연장시간이 올바르지 않습니다.');
        return false;
    }
    if (!$('#al_content').val()) {
        alert('신청사유를 선택하세요.');
        return false;
    }
    return true;
}

function saveOvertimeData() {
    // 저장 전에 author_id와 al_part 값 확인 및 설정
    console.log('=== saveOvertimeData 실행 ===');
    console.log('🔥 전역 변수에서 읽은 선택된 직원 정보:', selectedEmployeeInfo);

    // employeeNameArray 등 배열이 정의되어 있는지 확인
    if (typeof employeeNameArray === 'undefined') {
        console.error('❌ employeeNameArray가 정의되지 않음');
        alert('시스템 오류: 직원 정보를 불러올 수 없습니다.');
        return;
    }

    // 🔥 전역 변수에 저장된 값 사용 (폼 필드 무시!)
    if (!selectedEmployeeInfo.name || !selectedEmployeeInfo.id || !selectedEmployeeInfo.part) {
        console.error('❌ 선택된 직원 정보가 없습니다.');
        console.error('selectedEmployeeInfo:', selectedEmployeeInfo);
        alert('직원 정보를 찾을 수 없습니다. 성명을 다시 선택하세요.');
        return;
    }

    var selectedName = selectedEmployeeInfo.name;
    var authorIdValue = selectedEmployeeInfo.id;
    var alPartValue = selectedEmployeeInfo.part;

    console.log('');
    console.log('🔥🔥🔥 전역 변수에서 가져온 값 🔥🔥🔥');
    console.log('선택된 이름:', selectedName);
    console.log('author_id:', authorIdValue);
    console.log('al_part:', alPartValue);
    console.log('');

    // hidden input에 값 강제 설정 (폼 검증용)
    $('#author_id').val(authorIdValue);
    $('#al_part').val(alPartValue);
    $('#author').val(selectedName); // 폼 필드도 동기화

    // 최종 전송 데이터 확인
    console.log('');
    console.log('========================================');
    console.log('=== 연장근무 저장 - 최종 전송 데이터 ===');
    console.log('========================================');
    console.log('📝 폼 필드 값:');
    console.log('  - mode:', $('#mode').val());
    console.log('  - num:', $('#num').val());
    console.log('  - author (폼):', $('#author').val());
    console.log('  - author_id (폼):', $('#author_id').val());
    console.log('  - al_part (폼):', $('#al_part').val());
    console.log('');
    console.log('🔥 전역 변수에서 가져온 값 (실제 전송될 값):');
    console.log('  - selectedName:', selectedName);
    console.log('  - authorIdValue:', authorIdValue);
    console.log('  - alPartValue:', alPartValue);
    console.log('');

    // 🔥 serialize 대신 명시적으로 데이터 구성 (전역 변수 사용!)
    var formData = {
        mode: $('#mode').val(),
        num: $('#num').val(),
        author_id: authorIdValue,  // 🔥 전역 변수에서!
        author: selectedName,       // 🔥 전역 변수에서!
        al_part: alPartValue,       // 🔥 전역 변수에서!
        ot_type: $('#ot_type').val(),
        al_askdatefrom: $('#al_askdatefrom').val(),
        ot_start_time: $('#ot_start_time').val(),
        ot_end_time: $('#ot_end_time').val(),
        al_usedday: $('#al_usedday').val(),
        al_content: $('#al_content').val(),
        meal_deduction: $('#meal_deduction').is(':checked') ? '1' : '0'  // 식사시간 공제 체크박스 상태
    };

    console.log('🚀 서버로 전송할 formData 객체:');
    console.log(JSON.stringify(formData, null, 2));
    console.log('');
    console.log('⚠️ 확인 사항:');
    console.log('  - author는 선택된 직원 이름이어야 함:', formData.author);
    console.log('  - author_id는 선택된 직원의 ID이어야 함:', formData.author_id);
    console.log('  - al_part는 선택된 직원의 부서여야 함:', formData.al_part);
    console.log('  - 현재 로그인 사용자:', currentUserName, '(', currentUserId, ')');
    console.log('  - 대리 신청 여부:', formData.author !== currentUserName ? '🔥 대리 신청' : '본인 신청');
    console.log('========================================');
    console.log('');

    // 최종 검증: author_id와 al_part가 비어있으면 저장하지 않음
    if (!formData.author_id || !formData.al_part || !formData.author) {
        console.error('❌ 저장 중단: 필수 정보가 비어있음');
        console.error('formData.author:', formData.author);
        console.error('formData.author_id:', formData.author_id);
        console.error('formData.al_part:', formData.al_part);
        alert('직원 정보를 불러올 수 없습니다.\n성명을 다시 선택해주세요.');
        return;
    }

    // AJAX 전송 직전 최종 확인
    console.log('');
    console.log('🔥🔥🔥 AJAX 전송 직전 최종 확인 🔥🔥🔥');
    console.log('전송할 author:', formData.author);
    console.log('전송할 author_id:', formData.author_id);
    console.log('전송할 al_part:', formData.al_part);
    console.log('전송할 mode:', formData.mode);
    console.log('');

    $.ajax({
        url: 'process.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function(xhr) {
            console.log('AJAX 전송 시작...');
            console.log('전송 데이터 (직렬화 전):', formData);
        },
        success: function(data) {
            console.log('');
            console.log('✅ 서버 응답 받음:');
            console.log(JSON.stringify(data, null, 2));
            
            if (data.success) {
                // 🔥 저장 성공 시 쿠키에 폼 데이터 저장
                saveFormToCookie();
                console.log('✅ 저장 성공 후 쿠키에 폼 데이터 저장 완료');
                
                alert(data.message || '저장되었습니다.');
                $('#overtimeModal').modal('hide');
                location.reload();
            } else {
                console.error('저장 실패:', data.message);
                alert(data.message || '저장 실패');
            }
        },
        error: function(xhr, status, error) {
            console.error('');
            console.error('❌ AJAX 오류 발생:');
            console.error('- Status:', status);
            console.error('- Error:', error);
            console.error('- Response Text:', xhr.responseText);
            alert('서버 오류가 발생했습니다.');
        }
    });
}

function deleteOvertimeData() {
    const num = $('#num').val();
    $.ajax({
        url: 'process.php',
        type: 'POST',
        data: { mode: 'delete', num: num },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                alert(data.message || '삭제되었습니다.');
                $('#overtimeModal').modal('hide');
                location.reload();
            } else {
                alert(data.message || '삭제 실패');
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
}

// 빠른 시간 선택 버튼 생성 함수
function generateTimeButtons() {
    const startTime = $('#ot_start_time').val();

    if (!startTime) {
        // 시작시간이 없으면 버튼 영역 숨기기
        $('#quick_time_buttons').hide();
        return;
    }

    // 시작시간을 Date 객체로 변환
    const start = new Date('2000-01-01 ' + startTime);
    const container = $('#time_button_container');
    container.empty(); // 기존 버튼 제거

    // 시작시간부터 최대 8시간 후까지 30분 단위로 버튼 생성 (최대 16개 버튼)
    const maxButtons = 16;

    for (let i = 1; i <= maxButtons; i++) {
        // 30분 단위로 시간 증가
        const buttonTime = new Date(start.getTime() + (i * 30 * 60 * 1000));
        const hours = buttonTime.getHours();
        const minutes = buttonTime.getMinutes();

        // HH:MM 형식으로 변환
        const timeStr = String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');

        // 근무 시간 계산 (시작시간부터의 차이)
        const workMinutes = i * 30;
        const workHoursPart = Math.floor(workMinutes / 60);
        const workMinutesPart = workMinutes % 60;

        // 근무시간 텍스트 생성
        let workTimeLabel = '';
        if (workHoursPart > 0 && workMinutesPart > 0) {
            workTimeLabel = workHoursPart + '시간 ' + workMinutesPart + '분';
        } else if (workHoursPart > 0) {
            workTimeLabel = workHoursPart + '시간';
        } else {
            workTimeLabel = workMinutesPart + '분';
        }

        // 3시간(180분) 또는 8시간(480분)일 때, "btn-outline-danger" 추가
        let btnClass = 'btn btn-outline-primary btn-sm';
        if (workMinutes === 180 || workMinutes === 480) {
            btnClass = 'btn btn-outline-danger btn-sm';
        }

        // 버튼 생성: "30분" 또는 "1시간" 형식 (종료시간 표시 제거)
        const button = $('<button>')
            .addClass(btnClass)
            .attr('type', 'button')
            .attr('data-time', timeStr)
            .attr('title', '종료시간: ' + timeStr)  // 마우스 오버 시 종료시간 표시
            .text(workTimeLabel)
            .click(function() {
                selectQuickTime(timeStr);
            });

        container.append(button);
    }

    // 버튼 영역 표시
    $('#quick_time_buttons').show();
}

// 빠른 시간 선택 버튼 클릭 핸들러
function selectQuickTime(timeStr) {
    // 종료시간 설정
    $('#ot_end_time').val(timeStr);

    // 연장시간 자동 계산
    calculateHours();

    console.log('빠른 시간 선택:', timeStr);
}

$(document).ready(function() {
    var title = '<?php echo $title_message; ?>';
    saveMenuLog(title);
});

// ===== 음성인식 기능 =====
let recognition = null;
let isRecording = false;
let finalTranscript = '';
let interimTranscript = '';

// Web Speech API 초기화
function initSpeechRecognition() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert('이 브라우저는 음성 인식을 지원하지 않습니다.\nChrome 브라우저를 사용하세요.');
        return false;
    }

    recognition = new SpeechRecognition();
    recognition.lang = 'ko-KR';           // 한국어 설정
    recognition.continuous = true;        // 연속 인식
    recognition.interimResults = true;    // 중간 결과 표시
    recognition.maxAlternatives = 1;

    // 음성 인식 결과 처리
    recognition.onresult = function(event) {
        interimTranscript = '';

        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;

            if (event.results[i].isFinal) {
                finalTranscript += transcript + ' ';
            } else {
                interimTranscript += transcript;
            }
        }

        // textarea에 실시간 업데이트
        const displayText = finalTranscript + interimTranscript;
        $('#al_content').val(displayText);

        console.log('음성 인식 중:', displayText);
    };

    // 음성 인식 시작
    recognition.onstart = function() {
        console.log('음성 인식 시작');
        isRecording = true;
        $('#voiceBtn').removeClass('btn-outline-secondary').addClass('btn-danger');
        $('#voiceBtn').html('<i class="bi bi-stop-fill"></i>');
        $('#voiceBtn').attr('title', '녹음 중지');
    };

    // 음성 인식 종료
    recognition.onend = function() {
        console.log('음성 인식 종료');
        isRecording = false;
        $('#voiceBtn').removeClass('btn-danger').addClass('btn-outline-secondary');
        $('#voiceBtn').html('<i class="bi bi-mic-fill"></i>');
        $('#voiceBtn').attr('title', '음성으로 입력하기');
    };

    // 에러 처리
    recognition.onerror = function(event) {
        console.error('음성 인식 오류:', event.error);

        switch(event.error) {
            case 'no-speech':
                console.log('음성이 감지되지 않았습니다.');
                break;
            case 'aborted':
                console.log('음성 인식이 중단되었습니다.');
                break;
            case 'not-allowed':
                alert('마이크 권한을 허용해주세요.');
                break;
            default:
                console.error('음성 인식 오류가 발생했습니다:', event.error);
        }

        // 오류 발생 시 버튼 상태 초기화
        isRecording = false;
        $('#voiceBtn').removeClass('btn-danger').addClass('btn-outline-secondary');
        $('#voiceBtn').html('<i class="bi bi-mic-fill"></i>');
        $('#voiceBtn').attr('title', '음성으로 입력하기');
    };

    return true;
}

// 음성 인식 시작/중지 토글
function toggleVoiceRecognition() {
    if (!recognition) {
        if (!initSpeechRecognition()) {
            return;
        }
    }

    if (isRecording) {
        // 녹음 중지
        recognition.stop();
        console.log('음성 인식 중지 요청');
    } else {
        // 녹음 시작
        finalTranscript = $('#al_content').val() || '';  // 기존 텍스트 유지
        interimTranscript = '';

        try {
            recognition.start();
            console.log('음성 인식 시작 요청');
        } catch (error) {
            console.error('음성 인식 시작 오류:', error);
            alert('음성 인식을 시작할 수 없습니다.');
        }
    }
}

// 음성 버튼 클릭 이벤트
$(document).on('click', '#voiceBtn', function(e) {
    e.preventDefault();
    console.log('음성 버튼 클릭, 현재 상태:', isRecording ? '녹음 중' : '대기 중');
    toggleVoiceRecognition();
});

// 모달이 닫힐 때 음성 인식 중지
$('#overtimeModal').on('hidden.bs.modal', function () {
    if (recognition && isRecording) {
        recognition.stop();
        console.log('모달 닫힘: 음성 인식 중지');
    }
});
</script>

</body>
</html>
