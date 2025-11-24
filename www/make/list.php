<?php
/**
 * 도장 발주 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

// 공통 함수 및 세션 로드
require_once __DIR__ . '/../bootstrap.php';

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level >= 5) {
    sleep(1);
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $WebSite = "{$protocol}://{$host}/";
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}

// 페이지 설정
$title_message = '도장 발주';

// _request.php에서 사용될 변수들 미리 선언
require_once "_request.php";

// 추가 변수 초기화 (hidden input용)
$voc_alert = getRequestValue("voc_alert", '');
$ma_alert = getRequestValue("ma_alert", '');
$order_alert = getRequestValue("order_alert", '');
$yearcheckbox = getRequestValue("yearcheckbox", '');
$year = getRequestValue("year", '');
$check = getRequestValue("check", '');
$output_check = getRequestValue("output_check", '');
$plan_output_check = getRequestValue("plan_output_check", '');
$team_check = getRequestValue("team_check", '');
$measure_check = getRequestValue("measure_check", '');
$cursort = getRequestValue("cursort", '');
$sortof = getRequestValue("sortof", '');
$stable = getRequestValue("stable", '');
$sqltext = getRequestValue("sqltext", '');
$asprocess = getRequestValue("asprocess", '');
$separate_date = getRequestValue("separate_date", '');
$first_num = getRequestValue("first_num", 0);
$find = getRequestValue("find", '');

// 날짜 범위 설정
if (empty($fromdate)) {
    $fromdate = "2020-01-01";
}

if (empty($todate)) {
    $todate = date("Y") . "-12-31";
    $Transtodate = date("Y-m-d", strtotime($todate . ' +1 days'));
} else {
    $Transtodate = date("Y-m-d", strtotime($todate));
}

// 기본 설정
$process = "전체";

// 날짜 기준 설정
$SettingDate = ($separate_date == "1") ? "orderdate" : "indate";

// SQL 쿼리 생성 (Prepared Statement 사용)
$nowday = date("Y-m-d");

try {
    if ($mode == "search" && !empty($search)) {
        // 검색 모드 (Prepared Statement로 SQL 인젝션 방지)
        $sql = "SELECT * FROM mirae8440.make 
                WHERE (orderdate LIKE ? OR text LIKE ? OR indate LIKE ? OR company LIKE ?) 
                ORDER BY {$SettingDate} DESC, num DESC 
                LIMIT ?, ?";
        
        $stmh = $pdo->prepare($sql);
        $searchTerm = "%{$search}%";
        $stmh->bindValue(1, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(2, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(3, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(4, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(5, (int)$first_num, PDO::PARAM_INT);
        $stmh->bindValue(6, (int)$scale, PDO::PARAM_INT);
        $stmh->execute();
    } else {
        // 일반 목록
        $sql = "SELECT * FROM mirae8440.make 
                WHERE {$SettingDate} BETWEEN ? AND ? 
                ORDER BY num DESC";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
        $stmh->execute();
    }
    
    $total_row = $stmh->rowCount();
    
    // 데이터를 배열로 미리 가져오기 (메모리 효율적이고 재사용 가능)
    $dataList = [];
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $dataList[] = $row;
    }

} catch (PDOException $ex) {
    error_log("목록 조회 오류: " . $ex->getMessage());
    $dataList = [];
    $total_row = 0;
}

// 디버그용 (필요시 주석 해제)
// print $sql;
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?></title>
<style>
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

	.card-header {
		padding: 0.75rem 0.5rem !important;
		font-size: 0.9rem !important;
	}

	.card-header h4 {
		font-size: 1rem !important;
		margin: 0 !important;
		margin-right: 0.5rem !important;
	}

	.card-header .btn {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.5rem !important;
		white-space: nowrap !important;
	}

	/* 기간 설정 영역 모바일 최적화 */
	.row .d-flex.mt-1.mb-2 {
		display: flex !important;
		flex-wrap: wrap !important;
		gap: 0.3rem !important;
		justify-content: flex-start !important;
		align-items: center !important;
		padding: 0.5rem !important;
		margin: 0.5rem 0 !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
		width: 100% !important;
	}

	/* setdate.php 내부 요소 모바일 최적화 */
	.row .d-flex.mt-1.mb-2 > .card {
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
		width: 100% !important;
		flex: 1 1 100% !important;
		margin-bottom: 0.5rem !important;
	}

	.row .d-flex.mt-1.mb-2 > .card .card-body {
		padding: 0.5rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.row .d-flex.mt-1.mb-2 > .card .card-body > .d-flex {
		flex-wrap: wrap !important;
		gap: 0.25rem !important;
		justify-content: center !important;
	}

	.row .d-flex.mt-1.mb-2 > .card .card-body .btn {
		font-size: 0.65rem !important;
		padding: 0.25rem 0.4rem !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
		box-sizing: border-box !important;
		max-width: 100% !important;
	}

	.row .d-flex.mt-1.mb-2 #showdate {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.5rem !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
		margin-right: 0.3rem !important;
		box-sizing: border-box !important;
		min-width: 50px !important;
		max-width: 100% !important;
	}

	.row .d-flex.mt-1.mb-2 #showframe {
		position: absolute !important;
		z-index: 1000 !important;
		display: none !important;
	}

	.row .d-flex.mt-1.mb-2 #fromdate,
	.row .d-flex.mt-1.mb-2 #todate {
		width: auto !important;
		min-width: 90px !important;
		max-width: calc(50% - 0.5rem) !important;
		font-size: 0.7rem !important;
		padding: 0.3rem 0.35rem !important;
		flex: 0 0 auto !important;
		margin: 0 0.15rem !important;
		box-sizing: border-box !important;
	}

	.row .d-flex.mt-1.mb-2 span {
		font-size: 0.7rem !important;
		margin: 0 0.1rem !important;
		flex-shrink: 0 !important;
		white-space: nowrap !important;
		max-width: 100% !important;
	}

	.row .d-flex.mt-1.mb-2 .inputWrap {
		flex: 1 1 auto !important;
		min-width: 120px !important;
		max-width: calc(100% - 100px) !important;
		margin-right: 0.3rem !important;
		box-sizing: border-box !important;
	}

	.row .d-flex.mt-1.mb-2 #search {
		width: 100% !important;
		min-width: 0 !important;
		max-width: 100% !important;
		font-size: 0.7rem !important;
		padding: 0.3rem 0.35rem !important;
		box-sizing: border-box !important;
	}

	.row .d-flex.mt-1.mb-2 #searchBtn {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.5rem !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
		box-sizing: border-box !important;
		min-width: 50px !important;
		max-width: 100% !important;
	}

	.row .d-flex.mt-1.mb-2 #writeBtn {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.5rem !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
		box-sizing: border-box !important;
		min-width: 50px !important;
		max-width: 100% !important;
		margin-left: 0.3rem !important;
	}

	/* 테이블 모바일 최적화 - 카드 형식으로 변환 */
	.table-responsive {
		overflow-x: hidden !important;
		width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	/* DataTables 모바일 최적화 - 카드 형식 */
	.dataTables_wrapper {
		font-size: 0.75rem !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
	}

	/* 테이블 헤더 숨기기 (모바일) */
	.dataTables_wrapper thead {
		display: none !important;
	}

	/* 모바일에서 DataTables 행을 카드로 표시 */
	.dataTables_wrapper tbody tr {
		display: block !important;
		width: 100% !important;
		margin-bottom: 0.75rem !important;
		background: #fff !important;
		border: 1px solid #dee2e6 !important;
		border-radius: 0.375rem !important;
		box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
		padding: 0.75rem !important;
		box-sizing: border-box !important;
		cursor: pointer !important;
	}

	.dataTables_wrapper tbody tr:hover {
		background: #f8f9fa !important;
		box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
	}

	.dataTables_wrapper tbody tr td {
		display: flex !important;
		width: 100% !important;
		padding: 0.5rem 0.75rem !important;
		text-align: left !important;
		border: none !important;
		border-bottom: 1px solid #f0f0f0 !important;
		box-sizing: border-box !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		flex-wrap: wrap !important;
	}

	.dataTables_wrapper tbody tr td:last-child {
		border-bottom: none !important;
	}

	.dataTables_wrapper tbody tr td .mobile-label {
		font-weight: bold !important;
		display: inline-block !important;
		min-width: 30% !important;
		margin-right: 1rem !important;
		color: #495057 !important;
		font-size: 0.75rem !important;
		flex-shrink: 0 !important;
	}

	.dataTables_wrapper tbody tr td .mobile-value {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		font-size: 0.75rem !important;
		color: #212529 !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	/* 모바일에서 테이블 자체는 숨기되 DataTables는 작동하도록 */
	.dataTables_wrapper table {
		width: 100% !important;
		border-collapse: separate !important;
		border-spacing: 0 !important;
	}

	.dataTables_wrapper table thead {
		display: none !important;
	}

	.dataTables_wrapper table tbody {
		display: block !important;
		width: 100% !important;
	}

	.dataTables_wrapper table tbody tr {
		display: block !important;
		width: 100% !important;
	}

	.dataTables_wrapper .dataTables_length,
	.dataTables_wrapper .dataTables_filter,
	.dataTables_wrapper .dataTables_info,
	.dataTables_wrapper .dataTables_paginate {
		font-size: 0.7rem !important;
		margin-bottom: 0.5rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.dataTables_wrapper .dataTables_length select,
	.dataTables_wrapper .dataTables_filter input {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.4rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.dataTables_wrapper .dataTables_paginate {
		display: flex !important;
		flex-wrap: wrap !important;
		justify-content: center !important;
		gap: 0.25rem !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.5rem !important;
		margin: 0 0.1rem !important;
		box-sizing: border-box !important;
		max-width: 100% !important;
	}

	/* 버튼 모바일 최적화 */
	.btn {
		font-size: 0.75rem !important;
		padding: 0.35rem 0.5rem !important;
		white-space: nowrap !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.btn-sm {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.45rem !important;
	}

	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin-left: -5px !important;
		margin-right: -5px !important;
	}

	.row > [class*="col-"] {
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* 모든 버튼과 텍스트가 카드 내부에 머물도록 */
	.card *,
	.container *,
	.container-fluid * {
		box-sizing: border-box !important;
		max-width: 100% !important;
	}

	.card button,
	.card .btn,
	.card span,
	.card input,
	.card table,
	.container button,
	.container .btn,
	.container span,
	.container input,
	.card-body *,
	.card-header * {
		max-width: 100% !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		box-sizing: border-box !important;
	}

	/* 카드 내부 모든 요소가 넘치지 않도록 */
	.card {
		overflow-x: hidden !important;
		overflow-y: visible !important;
	}

	.card-body {
		overflow-x: hidden !important;
		overflow-y: visible !important;
	}
}
</style>

</head>
<body>

<?php require_once(includePath('myheader.php')); ?>

<?php
// 안전한 form action URL 생성
$form_params = http_build_query([
    'mode' => 'search',
    'search' => $search,
    'find' => $find,
    'year' => $year,
    'process' => $process,
    'asprocess' => $asprocess,
    'fromdate' => $fromdate,
    'todate' => $todate,
    'separate_date' => $separate_date,
    'scale' => 10000
], '', '&', PHP_QUERY_RFC3986);
?>

<form name="board_form" id="board_form" method="post" action="list.php?<?=$form_params?>">
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?=htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?=htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="order_alert" name="order_alert" value="<?=htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="page" name="page" value="<?=htmlspecialchars($page, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="scale" name="scale" value="<?=htmlspecialchars($scale, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="year" name="year" value="<?=htmlspecialchars($year, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="check" name="check" value="<?=htmlspecialchars($check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="output_check" name="output_check" value="<?=htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="team_check" name="team_check" value="<?=htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="measure_check" name="measure_check" value="<?=htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="cursort" name="cursort" value="<?=htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="sortof" name="sortof" value="<?=htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="stable" name="stable" value="<?=htmlspecialchars($stable, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="sqltext" name="sqltext" value="<?=htmlspecialchars($sqltext, ENT_QUOTES, 'UTF-8')?>"> 

<div class="container">
    <div class="card mt-2 mb-4">
        <div class="card-header mb-1 justify-content-center align-items-center">
            <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                <h4 class="me-4"><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?></h4>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="location.href='../paint/index.php';">
                    도장발주 모바일버전으로 화면보기
                </button>
            </div>
        </div>
        
        <div class="card-body justify-content-center">
            <div class="row">
                <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                    <!-- 기간설정 칸 -->
                    <?php include getDocumentRoot() . '/setdate.php' ?>
                    &nbsp;
                    <button type="button" class="btn btn-dark btn-sm me-2" id="writeBtn">
                        <i class="bi bi-pencil"></i> 신규
                    </button>
                </div>
            </div>
            
            <div class="d-flex justify-content-center align-items-center">
                <table class="table table-hover" id="myTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center" style="width:50px;">번호</th>
                            <th class="text-center" style="width:120px;">접수</th>
                            <th class="text-center" style="width:100px;">발주</th>
                            <th class="text-center" style="width:120px;">발주처</th>
                            <th class="text-center">(현장명 등) 발주내용</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
/**
 * 테이블 행 렌더링 함수
 * @param array $rowData 행 데이터
 * @param int $rowNumber 행 번호
 * @param string $currentDate 현재 날짜
 * @param array $params 추가 파라미터
 * @return void
 */
function renderTableRow($rowData, $rowNumber, $currentDate, $params) {
    // 데이터 추출 및 기본값 설정
    $num = $rowData["num"] ?? '';
    $orderdate = $rowData["orderdate"] ?? '';
    $indate = $rowData["indate"] ?? '';
    $company = $rowData["company"] ?? '';
    $text = $rowData["text"] ?? '';
    
    // 텍스트 정리
    $text = str_replace([",", "|"], " ", $text);
    
    // 현재 날짜 강조
    $date_font = ($currentDate == $orderdate) ? "color-red" : "color-black";
    
    // 요일 추가
    if (!empty($orderdate)) {
        $week = ["(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)"];
        $orderdate = $orderdate . $week[date('w', strtotime($orderdate))];
    }
    
    // JavaScript 함수 파라미터 생성 (JSON 방식으로 안전하게 처리)
    $jsParams = [
        'num' => $num,
        'page' => $params['page'],
        'find' => $params['find'],
        'search' => $params['search'],
        'process' => $params['process'],
        'asprocess' => $params['asprocess'],
        'yearcheckbox' => $params['yearcheckbox'],
        'year' => $params['year'],
        'fromdate' => $params['fromdate'],
        'todate' => $params['todate'],
        'separate_date' => $params['separate_date'],
        'scale' => $params['scale']
    ];
    
    // data-* 속성으로 안전하게 파라미터 전달
    $dataAttrs = [];
    foreach ($jsParams as $key => $value) {
        $dataAttrs[] = sprintf('data-%s="%s"', 
            htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
        );
    }
    ?>
    <tr style="cursor:pointer;" class="table-row-clickable" <?=implode(' ', $dataAttrs)?>>
        <td class="text-center"><?=htmlspecialchars($rowNumber, ENT_QUOTES, 'UTF-8')?></td>
        <td class="<?=$date_font?> text-center"><?=htmlspecialchars($orderdate, ENT_QUOTES, 'UTF-8')?></td>
        <td class="<?=$date_font?> text-center"><?=htmlspecialchars($indate, ENT_QUOTES, 'UTF-8')?></td>
        <td class="text-center"><?=htmlspecialchars($company, ENT_QUOTES, 'UTF-8')?></td>
        <td class="color-gray"><?=htmlspecialchars($text, ENT_QUOTES, 'UTF-8')?></td>
    </tr>
<?php
}

// 데이터가 있는 경우 테이블 렌더링
if (!empty($dataList)) {
    // 파라미터 배열 준비
    $renderParams = [
        'page' => $page,
        'find' => $find,
        'search' => $search,
        'process' => $process,
        'asprocess' => $asprocess,
        'yearcheckbox' => $yearcheckbox,
        'year' => $year,
        'fromdate' => $fromdate,
        'todate' => $todate,
        'separate_date' => $separate_date,
        'scale' => $scale
    ];
    
    // 각 행 렌더링 (1부터 시작)
    $rowNumber = 1;
    foreach ($dataList as $rowData) {
        renderTableRow($rowData, $rowNumber, $nowday, $renderParams);
        $rowNumber++;
    }
} else {
    // 데이터가 없는 경우
    echo '<tr><td colspan="5" class="text-center">조회된 데이터가 없습니다.</td></tr>';
}
?>
                    </tbody>
                </table>
            </div>
        </div> <!--card-body-->
    </div> <!--card-->
</div> <!--container-->

</form>

<div class="container-fluid">
    <?php include '../footer_sub.php'; ?>
</div>

<script>
'use strict';    
var dataTable; // DataTables 인스턴스 전역 변수
var paintpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
        "order": [[1, 'desc']],
        "drawCallback": function(settings) {
            // 모바일에서 카드 형식으로 표시하기 위해 라벨과 값 분리
            var isMobile = window.innerWidth <= 768;
            var api = this.api();
            var columns = ['번호', '접수', '발주', '발주처', '(현장명 등) 발주내용'];
            
            api.rows().every(function() {
                var row = this.node();
                var cells = $(row).find('td');
                cells.each(function(index) {
                    var $cell = $(this);
                    
                    if (isMobile) {
                        // 모바일: 라벨과 값으로 분리
                        if (columns[index]) {
                            var cellText = $cell.text().trim();
                            
                            // 이미 변환된 경우 스킵 (라벨이 있는지 확인)
                            if ($cell.find('.mobile-label').length === 0) {
                                // 원본 텍스트 저장
                                var originalText = cellText;
                                
                                // 라벨과 값으로 분리
                                $cell.html(
                                    '<span class="mobile-label">' + columns[index] + '</span>' +
                                    '<span class="mobile-value">' + originalText + '</span>'
                                );
                            }
                        }
                    } else {
                        // PC: 원래 형식 유지 (라벨 제거)
                        if ($cell.find('.mobile-label').length > 0) {
                            var originalText = $cell.find('.mobile-value').text();
                            $cell.html(originalText);
                        }
                    }
                });
            });
        }
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('paintpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var paintpageNumber = dataTable.page.info().page + 1;
        setCookie('paintpageNumber', paintpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('paintpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });

    // 초기 로드 시 모바일 카드 형식 적용
    if (window.innerWidth <= 768) {
        dataTable.draw(false);
    }

    // 윈도우 리사이즈 이벤트 처리 (모바일/PC 전환 시)
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (dataTable) {
                dataTable.draw(false);
            }
        }, 250);
    });
	
	$("#writeBtn").click(function(){ 
		var page = paintpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)			
		var url = "write_form.php"; 
		customPopup(url, '신규 등록', 1400, 800); 	
	});
	
	// 테이블 행 클릭 이벤트 (이벤트 위임 방식)
	$('#myTable').on('click', '.table-row-clickable', function() {
		var $row = $(this);
		
		// data-* 속성에서 파라미터 추출
		var params = {
			menu: 'no',
			num: $row.data('num'),
			page: paintpageNumber || $row.data('page') || '',
			find: $row.data('find') || '',
			search: $row.data('search') || '',
			process: $row.data('process') || '',
			asprocess: $row.data('asprocess') || '',
			yearcheckbox: $row.data('yearcheckbox') || '',
			year: $row.data('year') || '',
			fromdate: $row.data('fromdate') || '',
			todate: $row.data('todate') || '',
			separate_date: $row.data('separate_date') || '',
			scale: $row.data('scale') || ''
		};
		
		// 쿼리스트링 생성
		var queryString = Object.keys(params)
			.filter(function(key) { return params[key] !== ''; })
			.map(function(key) { return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]); })
			.join('&');
		
		var url = 'view.php?' + queryString;
		
		// 팝업 열기
		if (typeof customPopup === 'function') {
			customPopup(url, '도장 발주', 1400, 800);
		} else {
			window.open(url, '도장 발주', 'width=1400,height=800');
		}
	});			
});

function restorePageNumber() {
    var savedPageNumber = getCookie('paintpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

// 상세 페이지로 이동 (레거시 함수 - data-* 속성 방식으로 대체됨)
// 하위 호환성을 위해 유지하지만 더 이상 사용되지 않음
function redirectToView(num, page, find, search, process, asprocess, yearcheckbox, year, fromdate, todate, separate_date, scale) {
    console.warn('redirectToView는 더 이상 사용되지 않습니다. 이벤트 위임 방식을 사용하세요.');
    
    var params = {
        menu: 'no',
        num: num,
        page: paintpageNumber || page || '',
        find: find || '',
        search: search || '',
        process: process || '',
        asprocess: asprocess || '',
        yearcheckbox: yearcheckbox || '',
        year: year || '',
        fromdate: fromdate || '',
        todate: todate || '',
        separate_date: separate_date || '',
        scale: scale || ''
    };
    
    var queryString = Object.keys(params)
        .filter(function(key) { return params[key] !== ''; })
        .map(function(key) { return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]); })
        .join('&');
    
    var url = 'view.php?' + queryString;
    
    if (typeof customPopup === 'function') {
        customPopup(url, '도장 발주', 1400, 800);
    } else {
        window.open(url, '도장 발주', 'width=1400,height=800');
    }
}
</script>

<script>
$(document).ready(function() {
    if (typeof saveLogData === 'function') {
        saveLogData('도장 발주');
    }
});
</script>

</body>
</html>