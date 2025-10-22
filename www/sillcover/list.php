<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 문지방덮개 (재료분리대) 출고 목록
 * 
 * 출고/입고 데이터 목록 표시 및 검색 기능
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';
$chkMobile = $_SESSION["chkMobile"] ?? false;

// 권한 체크
if ($level > 8) {
    $_SESSION["url"] = getBaseUrl() . '/request_etc/list.php';
    sleep(1);
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}

// 첫 화면 표시 문구
$title_message = '재료분리대 출고사진';

include includePath('load_header.php');
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>  
 
<style>
#showextract {
	display: inline-block;
	position: relative;
}
		
#showextractframe {
    display: none;
    position: absolute;
    width: 800px;
    z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */
    top: 110px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}
#autocomplete-list {
	border: 1px solid #d4d4d4;
	border-bottom: none;
	border-top: none;
	position: absolute;
	top: 87%;
	left: 65%;
	right: 30%;
	width: 10%;
	z-index: 99;
}
.autocomplete-item {
	padding: 10px;
	cursor: pointer;
	background-color: #fff;
	border-bottom: 1px solid #d4d4d4;
}
.autocomplete-item:hover {
	background-color: #e9e9e9;
}

</style>  
 
</head>

<body>

<?php
if (!$chkMobile) {
    require_once(includePath('myheader.php'));
}

if ($chkMobile) {
    echo '<style>
        table th, table td, h4, .form-control, span {
            font-size: 22px;
        }
        h4 {
            font-size: 40px;
        }
        .btn-sm {
            font-size: 30px;
        }
    </style>';
}

include "_request.php";

// 첨부파일 배열 초기화
$savefilename_arr = array();
$realname_arr = array();
$attach_arr = array();
$tablename = 'sillcover';
$item = 'image';

// 날짜 관련 변수 초기화
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if (empty($fromdate) || empty($todate)) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 3개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
    $Transtodate = $todate;
} else {
    $Transtodate = $todate;
}

// 요청 변수 초기화
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
$page = isset($_REQUEST["page"]) ? intval($_REQUEST["page"]) : 1;
$scale = $_REQUEST["scale"] ?? 50;

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);

// SQL 쿼리 준비
if (!empty($search)) {
    // 🔒 SQL 인젝션 방지: Prepared Statement 사용
    $searchParam = '%' . $search . '%';
    $sql = "select * from " . $DB . "." . $tablename . " 
            where outdate between ? and ? 
              and searchtag like ? 
              and is_deleted IS NULL 
            order by num desc";
    $params = array($fromdate, $Transtodate, $searchParam);
} else {
    $sql = "select * from " . $DB . "." . $tablename . " 
            where outdate between ? and ? 
              and is_deleted IS NULL 
            order by num desc";
    $params = array($fromdate, $Transtodate);
}

// 합계 계산을 위한 변수 초기화
$recount = 0;
$rowNum = 0;
$sum = array();
$sum_title = array();

try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute($params);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';
        $recount++;
        
        // 합계 계산 로직 (필요시)
        for ($i = 1; $i <= $rowNum; $i++) {
            $steelsource_item_val = $steelsource_item[$i] ?? '';
            $steelsource_spec_val = $steelsource_spec[$i] ?? '';
            $sum_title[$i] = $steelsource_item_val . $steelsource_spec_val;
            
            $which = $which ?? '';
            $tmp = $tmp ?? '';
            $steelnum = $steelnum ?? 0;
            
            if ($which == '1' && $tmp == $sum_title[$i]) {
                $sum[$i] = ($sum[$i] ?? 0) + (int)$steelnum; // 입고숫자 더해주기 합계표
            }
        }
    }
} catch (PDOException $Exception) {
    error_log("데이터 조회 오류: " . $Exception->getMessage());
}

// 전체 레코드수 파악
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute($params);
    $total_row = $stmh->rowCount();						

    
    // 출고일에 요일 추가
    if (!empty($outdate)) {
        $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
        $outdate = $outdate . $week[date('w', strtotime($outdate))];
    }
} catch (PDOException $Exception) {
    error_log("전체 레코드수 조회 오류: " . $Exception->getMessage());
}
?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search">
    <input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
    <div class="container-fluid">
        <div class="card mt-2">
            <div class="card-body">
                <div class="d-flex mb-3 mt-2 justify-content-center align-items-center">
                    <h4><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></h4>
                </div>
                
                <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
                    <!-- Replace the checkbox code with the Bootstrap-styled button -->
                    <span> ▷ <?= $total_row ?>&nbsp;&nbsp; </span>
                    <div id="showframe" class="card">
                        <div class="card-header" style="padding:2px;">
                            <div class="d-flex justify-content-center align-items-center">
                                기간 설정
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange" onclick='alldatesearch()'>전체</button>
                                <button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange" onclick='pre_year()'>전년도</button>
                                <button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='three_month_ago()'>M-3월</button>
                                <button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='prepre_month()'>전전월</button>
                                <button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='pre_month()'>전월</button>
                                <button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange" onclick='this_today()'>오늘</button>
                                <button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='this_month()'>당월</button>
                                <button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='this_year()'>당해년도</button>
                            </div>
                        </div>
                    </div>
                    <input type="date" id="fromdate" name="fromdate" size="12" class="form-control" style="width:100px;" value="<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 시작일"> &nbsp; ~ &nbsp;
                    <input type="date" id="todate" name="todate" size="12" class="form-control" style="width:100px;" value="<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 끝"> &nbsp;
                    &nbsp;&nbsp;
                    <?php if ($chkMobile) { ?>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                    <?php } ?>
                    &nbsp;
                    &nbsp;
                    <div class="inputWrap">
                        <input type="text" id="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" class="form-control" style="width:200px;"> &nbsp;
                        <button class="btnClear"></button>
                    </div>
                    &nbsp;
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm"><i class="bi bi-search"></i></button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-dark btn-sm me-1" id="writeBtn"><i class="bi bi-pencil-fill"></i> 신규</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover fs-5" id="myTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">번호</th>
                            <th class="text-center">출고일</th>
                            <th class="text-center">현장명</th>
                            <th class="text-center">비고</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    try {
                        if ($page <= 1) {
                            $start_num = $total_row; // 페이지당 표시되는 첫번째 글순번
                        } else {
                            $start_num = $total_row - ($page - 1) * $scale;
                        }
                        
                        $stmh = $pdo->prepare($sql);
                        $stmh->execute($params);
                        
                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            $num = $row["num"] ?? '';
                            include '_row.php';
                    ?>
                        <tr style="cursor:pointer;" onclick="redirectToView(<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>)">
                            <td class="text-center"><?= $start_num ?></td>
                            <td class="text-center"><?= htmlspecialchars($outdate, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center text-primary fw-bold"><?= htmlspecialchars($workplace, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php
                            $start_num--;
                        }
                    } catch (PDOException $Exception) {
                        error_log("데이터 출력 오류: " . $Exception->getMessage());
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</form>

<!-- ajax 전송으로 DB 수정 -->
<?php include "../formload.php"; ?>

<div class="container-fluid">
<?php include '../footer_sub.php'; ?>
</div>

<script>

var dataTable; // DataTables 인스턴스 전역 변수
var sillcoverpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });
    
    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('sillcoverpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }
    
    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var sillcoverpageNumber = dataTable.page.info().page + 1;
        setCookie('sillcoverpageNumber', sillcoverpageNumber, 10); // 쿠키에 페이지 번호 저장
    });
    
    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경
        
        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('sillcoverpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    // 신규 버튼 클릭
    $("#writeBtn").click(function() {
        var tablename = $("#tablename").val();
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '부자재 구매 등록', 800, 800);
    });
    
    // 검색 버튼 클릭
    $("#searchBtn").click(function() {
        // 페이지 번호를 1로 설정
        currentpageNumber = 1;
        setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장
        document.getElementById('board_form').submit();
    });
    
    // Enter 키로 검색
    $("#search").keydown(function(event) {
        if (event.key === "Enter" || event.keyCode === 13) {
            event.preventDefault();
            $("#searchBtn").click();
        }
    });
    
    // 서버에 작업 기록
    saveLogData('재료분리대 출고사진');
});

function restorePageNumber() {
    var savedPageNumber = getCookie('sillcoverpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function blinker() {
    $('.blinking').fadeOut(500);
    $('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

function redirectToView(num) {
    var tablename = $("#tablename").val();
    var url = "write_form.php?mode=view&num=" + num + "&tablename=" + tablename;
    customPopup(url, '재료분리대', 800, 800);
}
</script> 

</body>
</html>