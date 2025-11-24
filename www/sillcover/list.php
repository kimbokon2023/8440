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

/* 모바일 반응형 스타일 */
@media (max-width: 768px) {
	/* body와 html의 width 제한 */
	html, body {
		max-width: 100vw !important;
		overflow-x: hidden !important;
		font-size: 16px !important;
	}

	/* 컨테이너 패딩 조정 및 width 제한 */
	.container-fluid {
		max-width: 100vw !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		overflow-x: hidden !important;
	}

	/* 모든 row의 width 제한 */
	.row {
		max-width: 100vw !important;
		margin-left: 0 !important;
		margin-right: 0 !important;
		overflow-x: hidden !important;
	}

	/* card의 width 제한 */
	.card {
		max-width: 100% !important;
		overflow-x: hidden !important;
	}

	/* 제목 영역 모바일 최적화 */
	.d-flex h4 {
		font-size: 1.1rem !important;
		white-space: nowrap !important;
	}

	/* 버튼 그룹 모바일 최적화 */
	.btn-sm {
		font-size: 0.85rem !important;
		padding: 0.4rem 0.6rem !important;
		white-space: nowrap !important;
	}

	/* 날짜 행: 총 개수와 날짜 입력 필드를 한 행에 표시 */
	.date-row {
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		gap: 2px !important;
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
		justify-content: center !important;
		align-items: center !important;
		padding: 0.25rem 0.15rem !important;
		margin: 0.2rem 0 !important;
		width: 100% !important;
		box-sizing: border-box !important;
		min-height: 34px !important;
		max-height: 40px !important;
	}
	
	/* 날짜 행 내부 모든 요소 공백 제거 */
	.date-row > * {
		margin: 0 !important;
		flex-shrink: 0 !important;
		white-space: nowrap !important;
	}
	
	/* 기간 설정 카드 모바일에서 숨기기 */
	#showframe {
		display: none !important;
	}
	
	/* 총 개수 텍스트 크기 조정 */
	.date-row > span:first-child {
		font-size: 0.8rem !important;
		font-weight: 600 !important;
		color: #495057 !important;
		margin-right: 2px !important;
		padding: 0 !important;
	}
	
	/* 날짜 입력 필드 크기 조정 */
	.date-row #fromdate, 
	.date-row #todate {
		width: auto !important;
		min-width: 90px !important;
		max-width: 110px !important;
		font-size: 0.7rem !important;
		padding: 0.2rem 0.3rem !important;
		height: 30px !important;
		flex: 0 0 auto !important;
		box-sizing: border-box !important;
	}
	
	/* 날짜 입력 필드 내부 텍스트 크기 조정 */
	.date-row #fromdate::-webkit-datetime-edit,
	.date-row #todate::-webkit-datetime-edit {
		font-size: 0.7rem !important;
		padding: 0 !important;
	}
	
	/* 날짜 입력 필드 달력 아이콘 크기 조정 */
	.date-row #fromdate::-webkit-calendar-picker-indicator,
	.date-row #todate::-webkit-calendar-picker-indicator {
		width: 16px !important;
		height: 16px !important;
		padding: 0 !important;
	}
	
	/* 날짜 사이 ~ 기호 */
	.date-row .date-separator {
		font-size: 0.7rem !important;
		white-space: nowrap !important;
		margin: 0 1px !important;
		color: #6c757d !important;
	}

	/* 검색 행: search, searchBtn을 한 행에 표시 */
	.search-row {
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		gap: 2px !important;
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
		justify-content: flex-start !important;
		align-items: center !important;
		padding: 0.25rem 0.15rem !important;
		margin: 0.2rem 0 !important;
		width: 100% !important;
		box-sizing: border-box !important;
		min-height: 34px !important;
		max-height: 40px !important;
	}
	
	/* 검색 행 내부 모든 요소 공백 제거 */
	.search-row > * {
		margin: 0 !important;
		flex-shrink: 0 !important;
		white-space: nowrap !important;
	}
	
	/* inputWrap 크기 조정 */
	.search-row .inputWrap {
		flex: 1 1 auto !important;
		min-width: 100px !important;
		max-width: none !important;
		margin: 0 2px !important;
		position: relative !important;
		display: flex !important;
		align-items: center !important;
	}
	
	/* 검색어 입력 필드 */
	.search-row #search {
		width: 100% !important;
		font-size: 0.75rem !important;
		padding: 0.2rem 0.4rem !important;
		height: 30px !important;
		box-sizing: border-box !important;
	}
	
	/* 검색 버튼 */
	.search-row #searchBtn {
		display: inline-block !important;
		visibility: visible !important;
		opacity: 1 !important;
		flex-shrink: 0 !important;
		white-space: nowrap !important;
		font-size: 0.75rem !important;
		padding: 0.3rem 0.6rem !important;
		height: 30px !important;
		margin-left: auto !important;
		order: 999 !important;
		min-width: 50px !important;
	}
	
	/* autocomplete-list 숨기기 (필요시) */
	.search-row #autocomplete-list {
		position: absolute;
		z-index: 1000;
	}

	/* 팝업 프레임 모바일 최적화 */
	#showextractframe,
	#showframe {
		position: fixed !important;
		left: 50% !important;
		top: 50% !important;
		transform: translate(-50%, -50%) !important;
		width: 95% !important;
		max-width: 400px !important;
		z-index: 9999 !important;
		max-height: 80vh !important;
		overflow-y: auto !important;
	}

	/* 테이블 헤더 숨기기 */
	#myTable thead {
		display: none;
	}

	/* 테이블을 카드 레이아웃으로 변경 */
	#myTable,
	#myTable tbody,
	#myTable tr,
	#myTable td {
		display: block;
		width: 100%;
	}

	#myTable tr {
		margin-bottom: 10px;
		border: 1px solid #dee2e6;
		border-radius: 8px;
		background: white;
		box-shadow: 0 2px 8px rgba(0,0,0,0.08);
		padding: 5px;
		overflow: hidden;
	}

	#myTable td {
		text-align: left !important;
		padding: 4px !important;
		border: none !important;
		position: relative;
		padding-left: 35% !important;
		white-space: normal !important;
		word-wrap: break-word;
		min-height: 30px;
		font-size: 0.95rem !important;
		line-height: 1.5 !important;
	}

	/* 모바일에서 라벨 표시 */
	#myTable td:before {
		content: attr(data-label);
		position: absolute;
		left: 4px;
		width: 30%;
		padding-right: 3px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		font-weight: 600;
		color: #6b7280;
		font-size: 0.8rem;
	}

	/* 콜론 제거 - 모든 셀에서 콜론 숨김 */
	#myTable td:after {
		display: none !important;
	}

	/* 첫 번째 셀 숨김 처리 */
	#myTable td:first-child {
		display: none !important;
	}

	#myTable td:first-child:before {
		display: none;
	}

	/* 출고일 (2번째) */
	#myTable td:nth-child(2) {
		font-weight: 600;
		color: #495057;
		border-bottom: 1px solid #e9ecef;
		padding-bottom: 4px !important;
		margin-bottom: 3px;
	}

	/* 현장명 (3번째) - 가장 중요, 강조 표시 */
	#myTable td:nth-child(3),
	#myTable td[data-label="현장명"] {
		display: block !important;
		visibility: visible !important;
		opacity: 1 !important;
		background: #e7f3ff !important;
		font-weight: 700 !important;
		font-size: 1rem !important;
		color: #0056b3 !important;
		padding: 5px 4px !important;
		padding-left: 4px !important;
		margin: 3px 0 !important;
		border-radius: 4px !important;
		border-left: 4px solid #0056b3 !important;
		width: 100% !important;
		position: relative !important;
		text-align: left !important;
		white-space: normal !important;
		word-wrap: break-word !important;
		min-height: auto !important;
		line-height: 1.5 !important;
	}

	/* 현장명에 일반 td 스타일이 적용되지 않도록 */
	#myTable td:nth-child(3),
	#myTable td[data-label="현장명"] {
		padding-left: 4px !important;
	}

	/* 현장명 라벨 스타일 */
	#myTable td:nth-child(3):before,
	#myTable td[data-label="현장명"]:before {
		position: static !important;
		display: block !important;
		width: 100% !important;
		margin-bottom: 2px !important;
		font-size: 0.8rem !important;
		color: #6b7280 !important;
		font-weight: 600 !important;
		left: auto !important;
		width: 100% !important;
		padding-right: 0 !important;
		overflow: visible !important;
		text-overflow: clip !important;
	}

	/* DataTables 컨트롤 모바일에서 숨기기 */
	.dataTables_wrapper .dataTables_length,
	.dataTables_wrapper .dataTables_filter {
		display: none !important;
	}

	/* DataTables 페이지네이션 최적화 */
	.dataTables_wrapper .dataTables_paginate {
		font-size: 0.9rem !important;
		margin-top: 15px !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button {
		padding: 0.5rem 0.7rem !important;
		margin: 0 2px !important;
	}

	/* DataTables 정보 표시 최적화 */
	.dataTables_wrapper .dataTables_info {
		font-size: 0.9rem !important;
		text-align: center !important;
		margin-top: 10px !important;
		margin-bottom: 10px !important;
	}

	/* 버튼 영역 줄바꿈 허용 */
	.d-flex.justify-content-center {
		flex-wrap: wrap !important;
		overflow-x: visible !important;
		gap: 0.4rem !important;
		justify-content: flex-start !important;
	}

	/* 버튼 영역 가운데 정렬 유지 */
	.d-flex.justify-content-center.align-items-center {
		justify-content: center !important;
	}

	/* 배지 크기 조정 */
	.badge {
		font-size: 0.85rem !important;
		padding: 0.3rem 0.6rem !important;
	}

	/* 카드 패딩 조정 */
	.card {
		margin-bottom: 10px !important;
	}

	.card-body {
		padding: 4px !important;
	}

	/* 상단 검색 영역 정리 */
	.card-body > .d-flex.mb-1.mt-1 {
		flex-direction: column !important;
		align-items: stretch !important;
		gap: 10px !important;
	}

	/* 제목과 총 개수 영역 */
	.card-body > .d-flex.mb-3.mt-2 {
		flex-direction: row !important;
		justify-content: space-between !important;
		align-items: center !important;
		margin-bottom: 5px !important;
		padding-bottom: 4px !important;
		border-bottom: 2px solid #e9ecef !important;
	}

	/* 모바일에서 신규 버튼은 표시 (직접 등록 가능) */
	#writeBtn {
		display: inline-block !important;
		visibility: visible !important;
		opacity: 1 !important;
	}
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
                
                <!-- 날짜 행 -->
                <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center date-row">
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
                    <input type="date" id="fromdate" name="fromdate" size="12" class="form-control" style="width:100px;" value="<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 시작일">
                    <span class="date-separator">~</span>
                    <input type="date" id="todate" name="todate" size="12" class="form-control" style="width:100px;" value="<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 끝">
                </div>
                
                <!-- 검색 행 -->
                <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center search-row">
                    <div class="inputWrap">
                        <input type="text" id="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" class="form-control" style="width:200px;">
                        <button class="btnClear"></button>
                    </div>
                    <div id="autocomplete-list">
                    </div>
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm mx-1"><i class="bi bi-search"></i></button>
                    <button type="button" class="btn btn-dark btn-sm mx-1" id="writeBtn"><i class="bi bi-pencil-fill"></i> 신규</button>
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
                            <td class="text-center" data-label="번호"><?= $start_num ?></td>
                            <td class="text-center" data-label="출고일"><?= htmlspecialchars($outdate, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center text-primary fw-bold" data-label="현장명"><?= htmlspecialchars($workplace, ENT_QUOTES, 'UTF-8') ?></td>
                            <td data-label="비고"><?= htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') ?></td>
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