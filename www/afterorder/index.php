<?php
/**
 * 식사 주문 관리 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$chkMobile = $chkMobile ?? false;

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}
?>

<?php
include getDocumentRoot() . '/load_header.php';

// 요청 파라미터 수신
$num = $_REQUEST["num"] ?? '';
$year = $_REQUEST["year"] ?? date("Y");
$month = $_REQUEST["month"] ?? date("m");

// 검색 및 모드 관련 변수 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;

// 관리자 권한 체크
$admin = 0;
if ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지'  || $user_name == '조경임') {
    $admin = 1;
}

// $pdo는 bootstrap.php에서 이미 초기화됨

// 배열로 기본정보 불러옴
include "load_DB.php";

// load_DB.php에서 정의되지 않은 경우 대비
$basic_name_arr = $basic_name_arr ?? array();
$name_arr = array_unique($basic_name_arr);

// 현재 날짜를 가져옵니다
$today = date("Y-m-d");

// 유튜브 변수 초기화
$youtube1 = '';

// 제조파트에 해당되는 직원들의 근무를 파악하는 루틴
// 배열에 이름, 일자, 내용을 기록한다.
$view_name = array();
$view_date = array();
$view_item = array();
$view_memo = array();
$view_contents = array();
$view_sum_after = 0; // 점심식사
$view_sum_evening = 0; // 석식 합계

// rowDBask.php에서 사용될 변수 초기화
$name = '';
$askdatefrom = '';
$content = '';
$item = '';
$memo = '';
$state = '';

try {
    $sql = "select * from mirae8440.afterorder";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $count = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include 'rowDBask.php';

        array_push($view_name, $name);
        array_push($view_date, $askdatefrom);
        array_push($view_contents, $content);
        array_push($view_item, $item);
        array_push($view_memo, $memo);
    }
} catch (PDOException $ex) {
    error_log("afterorder 조회 오류: " . $ex->getMessage());
}
?>
 
<title>식사 주문</title>

<style>
/* 모바일 환경 최적화 */
@media (max-width: 768px) {
    /* 컨테이너 최적화 */
    .container,
    .container-fluid {
        padding: 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 최적화 */
    .card {
        margin: 0.5rem auto !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 검색 UI 최적화 */
    .d-flex.justify-content-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
        flex-wrap: wrap !important;
    }
    
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-center .form-control,
    .d-flex.justify-content-center .form-select {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
        box-sizing: border-box !important;
    }
    
    /* 년월 선택 UI 최적화 */
    .d-flex.align-items-center {
        flex-direction: column !important;
        gap: 0.5rem !important;
        align-items: stretch !important;
    }
    
    .d-flex.align-items-center select,
    .d-flex.align-items-center h6 {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
    }
    
    /* 테이블을 카드 형식으로 변환 */
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    
    table.table thead {
        display: none !important;
    }
    
    table.table tbody {
        display: block !important;
        width: 100% !important;
    }
    
    table.table tbody tr {
        display: block !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        margin: 0.5rem auto 0.75rem auto !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        padding: 0.75rem !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    table.table tbody tr td {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    table.table tbody tr td::before {
        content: attr(data-label) !important;
        font-weight: bold !important;
        font-size: 0.75rem !important;
        color: #666 !important;
        margin-right: 0.5rem !important;
        min-width: 80px !important;
        flex-shrink: 0 !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모든 텍스트 요소 강제 줄바꿈 */
    p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        display: inline !important;
        overflow: visible !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모달 최적화 */
    .modal {
        padding: 0 !important;
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
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        overflow-y: auto !important;
        flex: 1 1 auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    /* 모달 내부 요소 최적화 */
    .modal-body .row {
        flex-direction: column !important;
    }
    
    .modal-body .col-md-5,
    .modal-body .col-md-7 {
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 1rem !important;
    }
    
    /* 페이지네이션 최적화 */
    .d-flex.justify-content-center a,
    .d-flex.justify-content-center font {
        font-size: 0.875rem !important;
        padding: 0.25rem 0.5rem !important;
        word-wrap: break-word !important;
    }
    
    /* '기간' 버튼 숨기기 */
    #showdate {
        display: none !important;
    }
}

/* PC 환경 버튼 간격 최적화 */
@media (min-width: 769px) {
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-start .btn {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }
}
</style>

<body>

<?php include '../myheader.php'; ?>

<?php if ($chkMobile == false) { ?>
    <div class="container">
<?php } else { ?>
    <div class="container-fluid">
<?php } ?>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg modal-center">
            <!-- Modal content-->
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h4 class="modal-title">알림제목 넣는 칸</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="saleprice_val" id="saleprice_val">
                    <input type="hidden" name="mcmain" id="mcmain">
                    <input type="hidden" name="mcsub" id="mcsub">
                    <input type="hidden" name="cartsave" id="cartsave">

                    (부제목) : <input type="text" name="test" id="test" size="2" value=""/> <br><br>

                    <div class="row gx-4 gx-lg-4 align-items-center">
                        <div class="col-md-5">
                            <img id="imgID" class="card-img-top mb-5 mb-md-0" src="" alt="...">
                            <br>
                            <br>
                            <br>
                            <p class="lasercut"></p>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe id="youtubeID" class="embed-responsive-item" src="<?=$youtube1?>" frameborder="0" allowfullscreen></iframe>
                            </div>

                            <br>
                            <p class="workdone"></p>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe id="youtubeIDsecond" class="embed-responsive-item" src="<?=$youtube1?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="small mb-5">
                                <h1 id="catagory_sub" class="display-5 fw-bolder"></h1>
                                <div id="item_sub" class="fs-1 mb-5"></div>

                                <div id="itemdes_sub" class="fs-3 mb-5"></div>

                                <div class="d-flex justify-content-center large text-warning mb-2">
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                </div>

                                <div class="d-flex fs-2 mb-2">
                                    <span id="price_sub" class="text-decoration-line-through">11</span> &nbsp;
                                    <span id="salepricerate" style="color:red;font-weight:bold;"></span> &nbsp;
                                </div>
                                <div class="d-flex fs-2 mb-5">
                                    <span style="color:blue;">판매가</span> &nbsp;
                                    <span id="saleprice_sub"></span>
                                </div>

                                <br>
                                <div class="d-flex">
                                    <button type="button" id="addcart" class="btn btn-outline-dark mt-auto fs-1">
                                        <i class="bi-cart-fill me-1"></i>
                                        장바구니 넣기
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form name="board_form" id="board_form" method="post" action="index.php">
    <div class="container">
        <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
            <h5 class="text-dark">(중식) 1차 주문 소민지, 부재시 조경임,</h5> &nbsp;&nbsp;&nbsp;
            <h5 class="text-primary">(석식) 주문 이경묵 입력</h5>
            <button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'><i class="bi bi-arrow-clockwise"></i></button>
        </div>

        <div class="row">
            <?php
            if ($chkMobile === true) {
                echo '<div class="col-sm-12">';
            } else {
                echo '<div class="col-sm-6">';
            }
            ?>

            <div class="card card-body">
                <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
                    <h6>(식사 주문) 년월 설정</h6>
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
                <div class="d-flex mt-3 mb-3 justify-content-center">
                    <h5>식사 제외 인원(외식등) : <span id="exceptNum"></span></h5>
                </div>
                <div class="d-flex mt-3 mb-3 justify-content-center">
                    <h5>지정식당 지급인원수 (식수 - 제외수) : <span id="totalNum"></span></h5>
                </div>
                <div class="d-flex mt-3 mb-3 justify-content-center">
                    <table id="table_sum" class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <?php
                                // 직원 이름을 출력하는 열 출력
                                echo '<th scope="col">구분</th>';
                                echo '<th>(중식) 인원수</th>';
                                echo '<th>(석식) 인원수</th>';
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

                            $exceptNum = 0;

                            for ($i = 0; $i < $num_days + 2; $i++) {
                                echo '<tr class="text-center">';
                                if ($i === 0) {
                                    echo '<td class="text-primary" data-label="구분">인원 합계</td>';
                                    echo '<td class="text-primary fw-bold" data-label="(중식) 인원수">' . $view_sum_after . '</td>';
                                    echo '<td class="text-primary fw-bold" data-label="(석식) 인원수">' . $view_sum_evening . '</td>';
                                } else if ($i === 1) {
                                    echo '<td data-label="구분"></td>';
                                    echo '<td data-label="(중식) 인원수"></td>';
                                    echo '<td data-label="(석식) 인원수"></td>';
                                } else {
                                    $thisday = $days[date('w', strtotime($year . "-" . $month . "-" . ($i - 1)))];
                                    $pointday = date("Y-m-d", strtotime($year . "-" . $month . "-" . ($i - 1)));

                                    if ($thisday === '토' || $thisday === '일') {
                                        echo '<td class="text-danger" data-label="구분">' . ($i - 1) . '(' . $thisday . ')</td>';
                                    } else {
                                        echo '<td data-label="구분">' . ($i - 1) . '(' . $thisday . ')</td>';
                                    }

                                    // 중식 있으면 찾는 구문
                                    $printstr = '<td data-label="(중식) 인원수"></td>';

                                    for ($kk = 0; $kk < count($view_date); $kk++) {
                                        if ($view_date[$kk] === $pointday && $view_contents[$kk] == '중식') {
                                            $view_sum_after += (float)$view_item[$kk];
                                            if ($view_memo[$kk] !== '지정식당' && $view_memo[$kk] !== null) {
                                                $exceptNum += (float)$view_item[$kk];
                                            }

                                            $printstr = '<td data-label="(중식) 인원수">' . $view_item[$kk] . '</td>';
                                        }
                                    }
                                    echo $printstr;

                                    // 석식 있으면 찾는 구문
                                    $printstr = '<td data-label="(석식) 인원수"></td>';

                                    for ($kk = 0; $kk < count($view_date); $kk++) {
                                        if ($view_date[$kk] === $pointday && $view_contents[$kk] == '석식') {
                                            $view_sum_evening += (float)$view_item[$kk];

                                            if ($view_memo[$kk] !== '지정식당' && $view_memo[$kk] !== null) {
                                                $exceptNum += (float)$view_item[$kk];
                                            }

                                            $printstr = '<td data-label="(석식) 인원수">' . $view_item[$kk] . '</td>';
                                        }
                                    }
                                    echo $printstr;
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
            echo '<div class="col-sm-6">';
        }
        ?>

        <div class="card">
            <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
                <?php
                $scale = 35;       // 한 페이지에 보여질 게시글 수
                $page_scale = 15;   // 한 페이지당 표시될 페이지 수
                $first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

                // SQL 쿼리 생성
                if ($mode == "search" || $mode == "") {
                    if ($search == "") {
                        $sql = "select * from mirae8440.afterorder order by askdatefrom desc, name asc limit $first_num, $scale";
                        $sqlcon = "select * from mirae8440.afterorder order by askdatefrom desc";
                    } elseif ($search != "") {
                        $sql = "select * from mirae8440.afterorder where (name like '%$search%') or (content like '%$search%') or (memo like '%$search%')";
                        $sql .= " order by askdatefrom desc limit $first_num, $scale";
                        $sqlcon = "select * from mirae8440.afterorder where (name like '%$search%') or (content like '%$search%') or (memo like '%$search%')";
                        $sqlcon .= " order by askdatefrom desc";
                    }
                }

                // 데이터 조회
                $dataList = [];
                $total_row = 0;
                
                try {
                    // 전체 행 수 조회
                    $allstmh = $pdo->query($sqlcon);
                    $total_row = $allstmh->rowCount();
                    
                    // 페이지별 데이터 조회
                    $stmh = $pdo->query($sql);
                    
                    // 데이터를 배열로 미리 가져오기
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $dataList[] = $row;
                    }
                    
                    $total_page = ceil($total_row / $scale);
                    $current_page = ceil($page / $page_scale);
                    
                } catch (PDOException $ex) {
                    error_log("afterorder 조회 오류: " . $ex->getMessage());
                    $dataList = [];
                    $total_row = 0;
                    $total_page = 1;
                    $current_page = 1;
                }
                    ?>

                    ▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;

                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('write_form_ask.php', '식사 관리', 415, 520);return false;"><i class="bi bi-pencil"></i> 신규</button>
                    <input type="text" class="form-control mx-1" name="search" id="search" value="<?=$search?>" style="width:150px; height:32px;" onkeydown="JavaScript:SearchEnter();" placeholder="검색어 입력">
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm"><i class="bi bi-search"></i> 검색</button>
            </div>

            <div class="table table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-primary">
                        <tr class="text-center">
                            <th class="text-center">번호</th>
                            <th class="text-center">주문일</th>
                            <th class="text-center">유형</th>
                            <th class="text-center">주문수량</th>
                            <th class="text-center">종류</th>
                            <th class="text-center">확인</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($dataList)) {
                            if ($page <= 1) {
                                $start_num = $total_row;
                            } else {
                                $start_num = $total_row - ($page - 1) * $scale;
                            }

                            foreach ($dataList as $row) {
                                // 변수 추출
                                $num = $row['num'] ?? '';
                                $name = $row['name'] ?? '';
                                $askdatefrom = $row['askdatefrom'] ?? '';
                                $content = $row['content'] ?? '';
                                $item = $row['item'] ?? '';
                                $memo = $row['memo'] ?? '';
                                $state = $row['state'] ?? '';
                                ?>
                                <tr onclick="popupCenter('write_form_ask.php?num=<?=$num?>', '식사주문', 420, 550);return false;" style="cursor: pointer;">
                                    <td class="text-center" data-label="번호"><?=$start_num?></td>
                                    <td class="text-center" data-label="주문일"><?=iconv_substr($askdatefrom, 5, 5, "utf-8")?></td>
                                    <td class="text-center" data-label="유형"><?=$content?></td>
                                    <td class="text-center" data-label="주문수량"><?=$item?></td>
                                    <td class="text-center" data-label="종류"><?=$memo?></td>
                                    <td class="text-center <?= $state == '요청' ? 'text-primary' : '' ?>" data-label="확인"><?= $state ?></td>
                                </tr>
                                <?php
                                $start_num--;
                            }
                        } else {
                            // 데이터가 없는 경우
                            echo '<tr><td colspan="6" class="text-center">조회된 데이터가 없습니다.</td></tr>';
                        }

                        // 페이지 구분 블럭의 첫 페이지 수 계산
                        $start_page = ($current_page - 1) * $page_scale + 1;
                        // 페이지 구분 블럭의 마지막 페이지 수 계산
                        $end_page = $start_page + $page_scale - 1;
                        ?>
                    </tbody>
                </table>
            </div>

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
$(document).ready(function() {
    // 합계치가 나오면 첫번째줄의 요소를 바꿔준다
    var view_sum_after = <?php echo json_encode($view_sum_after, JSON_UNESCAPED_UNICODE); ?>;
    var view_sum_evening = <?php echo json_encode($view_sum_evening, JSON_UNESCAPED_UNICODE); ?>;
    var exceptNum = <?php echo json_encode($exceptNum ?? 0, JSON_UNESCAPED_UNICODE); ?>;

    var totalNum;

    totalNum = Number(view_sum_after) + Number(view_sum_evening) - Number(exceptNum);

    var exceptNumStr = '(' + (Number(view_sum_after) + Number(view_sum_evening)) + ' - ' + exceptNum + ') = ' + totalNum;
    console.log(view_sum_after);
    console.log(view_sum_evening);

    // 연장근로 상단에 표시해주기
    $("#table_sum tr:eq(1) td:eq(1)").text(view_sum_after);
    $("#table_sum tr:eq(1) td:eq(2)").text(view_sum_evening);

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

    $("#exceptNum").text(exceptNum);
    $("#totalNum").text(exceptNumStr);

    // 서버에 작업 기록
    saveLogData('(중식) 1차 주문 소민지');
    
    // 모바일 환경에서 '기간' 버튼 숨기기
    if (window.innerWidth <= 768) {
        $('#showdate').hide();
    }
    
    // 창 크기 변경 시 '기간' 버튼 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('#showdate').hide();
        } else {
            $('#showdate').show();
        }
    });
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