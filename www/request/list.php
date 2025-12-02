<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 원자재 구매 & 입출고 목록
 * 
 * 원자재 구매 요청 및 입출고 현황을 관리하는 메인 페이지
 */

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    $_SESSION["url"] = getBaseUrl() . '/request/list.php';
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

// 페이지네이션 변수 초기화
$page = $_REQUEST["page"] ?? '';
$scale = $_REQUEST["scale"] ?? '';
?>

<?php include getDocumentRoot() . '/load_header.php'; ?>  

<title> 원자재 구매&입출고 </title> 

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
	width : 10%;
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

th {
    white-space: nowrap;
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
	.d-flex h5 {
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
	
	/* 기간 버튼 모바일에서 숨기기 */
	#showdate {
		display: none !important;
	}
	
	/* dateRange 드롭다운 모바일에서 숨기기 */
	.date-row #dateRange {
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

	/* 검색 행: find, search, searchBtn을 한 행에 표시 */
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

	/* Bigsearch 드롭다운 모바일에서 숨기기 */
	.search-row #Bigsearch {
		display: none !important;
	}

	/* 드롭다운 필드 */
	.search-row .form-select,
	.search-row #find {
		width: auto !important;
		min-width: 80px !important;
		max-width: 100px !important;
		font-size: 0.75rem !important;
		height: 30px !important;
		padding: 0.2rem 0.4rem !important;
		flex: 0 0 auto !important;
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

	/* 모바일에서 불필요한 필드 숨기기 */
	#myTable td:nth-child(1),  /* 체크박스 */
	#myTable td:nth-child(2),  /* 번호 */
	#myTable td:nth-child(4),  /* 구매카트 */
	#myTable td:nth-child(5),  /* 납기 */
	#myTable td:nth-child(7),  /* 완료일 */
	#myTable td:nth-child(9),  /* 진행상태 */
	#myTable td:nth-child(10), /* 이관 */
	#myTable td:nth-child(12), /* 모델명 */
	#myTable td:nth-child(14), /* 규격 */
	#myTable td:nth-child(16), /* 사급여부 */
	#myTable td:nth-child(18)  /* 공급가액 */
	{
		display: none !important;
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

	/* 접수일 (3번째) */
	#myTable td:nth-child(3) {
		font-weight: 600;
		color: #495057;
		border-bottom: 1px solid #e9ecef;
		padding-bottom: 4px !important;
		margin-bottom: 3px;
	}

	/* 현장명 (11번째) - 가장 중요, 반드시 표시 */
	#myTable td:nth-child(11),
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
	#myTable td:nth-child(11),
	#myTable td[data-label="현장명"] {
		padding-left: 4px !important;
	}

	/* 현장명 라벨 스타일 */
	#myTable td:nth-child(11):before,
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

	/* 철판종류 (13번째) */
	#myTable td:nth-child(13) {
		font-weight: 600;
		color: #764ba2;
	}

	/* 수량 (15번째) */
	#myTable td:nth-child(15) {
		font-weight: 600;
		color: #dc2626;
		font-size: 1rem !important;
	}

	/* 공급처 (17번째) */
	#myTable td:nth-child(17) {
		font-weight: 600;
		color: #059669;
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

	/* 모바일에서 불필요한 버튼 숨기기 */
	#showextract,           /* 부가기능 버튼 */
	#showdate,              /* 기간 버튼 */
	#mywriteBtn,            /* 내글 버튼 */
	#writeBtn,              /* 신규 버튼 */
	#showCost,              /* 단가 추적 버튼 */
	#addToCartBtn,          /* 구매카트 담기 버튼 */
	.checktask,             /* 입고 제외/포함 버튼 */
	.date-row #dateRange,   /* 날짜 범위 드롭다운 */
	.search-row #Bigsearch  /* 철판종류 드롭다운 */
	{
		display: none !important;
	}

	/* 검색 버튼만 표시 */
	#searchBtn {
		display: inline-block !important;
	}
}
</style> 
</head>
<body>		 
<?php include getDocumentRoot() . '/myheader.php'; ?>    
<?php
include "_request.php";

// 추가 요청 변수 초기화
$Bigsearch = $_REQUEST["Bigsearch"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 철판종류 데이터 추출
$sql = "select * from " . $DB . ".steelsource order by sortorder asc, item desc";

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    $counter = 0;
    $item_counter = 0;
    
    // 배열 초기화
    $steelsource_num = array();
    $steelsource_item = array();
    $steelsource_spec = array();
    $steelsource_take = array();
    $steelsource_item_yes = array();
    $steelsource_spec_yes = array();
    $spec_arr = array();
    $last_item = "";
    $last_spec = "";
    $pass = '0';

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $steelsource_num[$counter] = $row["num"] ?? '';
        $steelsource_item[$counter] = trim($row["item"] ?? '');
        $steelsource_spec[$counter] = trim($row["spec"] ?? '');
        $company = trim($row["take"] ?? '');

        // 일반매입 처리
        if ($row["take"] == '미래기업') $company = '';
        if ($row["take"] == '윤스틸') $company = '';
        if ($row["take"] == '현진스텐') $company = '';
        
        $steelsource_take[$counter] = $company;

        // 고유한 아이템 추출
        if ($steelsource_item[$counter] != $last_item) {
            $last_item = $steelsource_item[$counter];
            $steelsource_item_yes[$item_counter] = $last_item;
            $item_counter++;
        }
        
        $counter++;
    }
} catch (PDOException $Exception) {
    error_log("철판종류 조회 오류: " . $Exception->getMessage());
    echo "<p>데이터를 불러오는 중 오류가 발생했습니다.</p>";
}

// 고유한 아이템 정리
array_push($steelsource_item_yes, " ");
$steelsource_item_yes = array_unique($steelsource_item_yes);
sort($steelsource_item_yes);

// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 3개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
    $Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}

$find = $_REQUEST["find"] ?? ''; 
// 철판 데이터 재조회 (인덱스 카운터 포함)
$sql = "select * from " . $DB . ".steelsource";

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    $counter = 0;
    
    $steelsource_num = array();
    $steelsource_item = array();
    $steelsource_spec = array();
    $steelsource_take = array();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $counter++;
        $steelsource_num[$counter] = $row["num"] ?? '';
        $steelsource_item[$counter] = $row["item"] ?? '';
        $steelsource_spec[$counter] = $row["spec"] ?? '';
        $steelsource_take[$counter] = $row["take"] ?? '';
    }
} catch (PDOException $Exception) {
    error_log("철판 데이터 조회 오류: " . $Exception->getMessage());
}  

// 날짜 기준 설정
if ($separate_date == "1") {
    $SettingDate = "registdate ";
} else {
    $SettingDate = "indate ";
}

// 완료일 기준으로 강제 설정
$SettingDate = "outdate ";

// 내 글만 보기 조건
$Andmywrite = "";
if ($mywrite === '1') {
    $Andmywrite = " And first_writer like '%" . $user_name . "%' ";
}

// 삭제되지 않은 데이터 조건
$Andis_deleted = " AND is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;
$Whereis_deleted = " Where is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;	 

// SQL 조건 생성
if ($done_check_val === '1') {
    $common = " where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') and (which = '1' or which = '2') " . $Andis_deleted . " order by ";
} else {
    $common = " where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') " . $Andis_deleted . " order by ";
}

$a = $common . " num desc ";  // 내림차순

// 전체합계(입고부분)를 산출하는 부분
$sum_title = array();
$sum = array();
$num_arr = array();
$item_arr = array();
$supplier_arr = array();
$company_arr = array();

$sql = "select * from " . $DB . ".eworks " . $a;
$recount = 0;

try {
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';

        $tmp = $steel_item . $spec;

        $num_arr[$recount] = $num;
        $item_arr[$recount] = $steel_item;
        $supplier_arr[$recount] = $supplier;
        $company_arr[$recount] = $company;

        $recount++;

        // 합계 계산
        for ($i = 1; $i <= $rowNum; $i++) {
            if (!isset($sum_title[$i])) $sum_title[$i] = '';
            if (!isset($sum[$i])) $sum[$i] = 0;
            
            $sum_title[$i] = $steelsource_item[$i] . $steelsource_spec[$i];
            
            if ($which == '1' && $tmp == $sum_title[$i]) {
                $sum[$i] = $sum[$i] + (int)$steelnum;  // 입고숫자 더해주기 합계표
            }
        }
    }
} catch (PDOException $Exception) {
    error_log("데이터 조회 오류: " . $Exception->getMessage());
}


 // 전체합계(출고부분)를 처리하는 부분 

// print $sql;

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
                  
if(trim($Bigsearch)==='' && $find==="전체")
		{		
				 $sql="select * from ".$DB.".eworks " . $a ; 														 
		}
		else {	 
		
				 $sql="select * from ".$DB.".eworks " . $a ; 														 
		
			  if($find=='전체') {
				  $sql ="select * from ".$DB.".eworks where (steel_item like '%$Bigsearch%') " . $Andis_deleted . "  order by  num desc ";									  
							}
		
			  if($find=='입고') {
				  $sql ="select * from ".$DB.".eworks where (steel_item like '%$Bigsearch%')  and (which = '3' ) " . $Andis_deleted . " order by num desc ";
						}
			  if($find=='출고') {
				  $sql ="select * from ".$DB.".eworks where (steel_item like '%$Bigsearch%')  and ( which = '2' ) " . $Andis_deleted . " order by num desc ";
						}										
						
						
				}
							
if($mode==="search" && $search!=="" && $find!=="전체") { // 각 필드별로 검색어가 있는지 쿼리주는 부분	
	 if(trim($Bigsearch)==='')   // Bigsearch가 없는 경우
		  {
			 if($find==='입고') {
				 
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and (which = '3' ) and  ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%'))  ";
				  $sql .=" " . $Andis_deleted . " order by num desc ";
				  
					}
				 if($find==='출고') { // 출고인 경우								  								
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and (which = '2' ) and  ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) ";
				  $sql .=" " . $Andis_deleted . " order by  num desc ";

					}
				 if($find==='공급처') { // 공급처 경우								  								
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate'))  and (supplier like '%$search%') " ;   
				  $sql .=" " . $Andis_deleted . " order by num desc ";
					}
		   }
			  else {   // bigsearch 있는 경우
						 if($find==='입고') {
								// 철판종류도 지정하고 검색어도 있는 경우
							  $sql ="select * from ".$DB.".eworks where  (steel_item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
							  $sql .="or (  steel_item like '%$search%')  or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) and (which = '3' ) " . $Andis_deleted . "  order by num desc ";
								}
							else { // 출고인 경우								  
								// 철판종류도 지정하고 검색어도 있는 경우
								  $sql ="select * from ".$DB.".eworks where  (steel_item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
								  $sql .="or (  steel_item like '%$search%') or  (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) and (which = '2' ) " . $Andis_deleted . "  order by num desc ";												  
								}
					   }			
		}						   
 if($search!=="" && $find==="전체") { // 각 필드별로 검색어가 있는지 쿼리주는 부분	
		// 필드 선택없고 눌렀을때 Bigsearch -> steel_item값이 있을 경우 검색
		if(trim($Bigsearch)!=='')						
		{ 
			  // 철판종류도 지정하고 검색어도 있는 경우
			  $sql ="select * from ".$DB.".eworks where  (steel_item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' )  or (replace(supplier,' ','') like '%$search%' ) ";
			  $sql .="or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) " . $Andis_deleted . "  order by num desc ";							  
		  }  
		  else
			  {							  						
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate') ) and ( (outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' )  or (replace(supplier,' ','') like '%$search%' ) " ;
				  $sql .="or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%') ) " . $Andis_deleted . "  order by num desc ";
			  }
		}
               

// 현재일자
$nowday = date("Y-m-d");
$dateCon = " AND between date('$fromdate') and date('$Transtodate') ";

// 데이터 조회
try {
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';
    }
} catch (PDOException $Exception) {
    error_log("데이터 조회 오류: " . $Exception->getMessage());
}

// 전체 행 개수 조회
try {
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();
} catch (PDOException $Exception) {
    error_log("전체 행 개수 조회 오류: " . $Exception->getMessage());
    $total_row = 0;
}

// 등록 상태 초기화
$regist_state = $regist_state ?? "1";

// 등록 상태 문자열
switch ($regist_state) {
    case "1":
        $regist_word = "등록";
        break;
    case "2":
        $regist_word = "접수";
        break;
    case "3":
        $regist_word = "완료";
        break;
    default:
        $regist_word = "등록";
        break;
}
?>


<form name="board_form" id="board_form" method="post" action="list.php?mode=search">

<?php
// Hidden 필드용 변수 초기화 (_request.php에 없는 변수들)
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';
$check = $_REQUEST["check"] ?? '';
$output_check = $_REQUEST["output_check"] ?? '';
$plan_output_check = $_REQUEST["plan_output_check"] ?? '';
$team_check = $_REQUEST["team_check"] ?? '';
$measure_check = $_REQUEST["measure_check"] ?? '';
$sortof = $_REQUEST["sortof"] ?? '';
$stable = $_REQUEST["stable"] ?? '';
$sqltext = $_REQUEST["sqltext"] ?? '';
$BigsearchTag = $_REQUEST["BigsearchTag"] ?? '';
?>

    <input type="hidden" id="done_check_val" name="done_check_val" value="<?= $done_check_val ?>">
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?= $voc_alert ?>">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?= $ma_alert ?>">
    <input type="hidden" id="order_alert" name="order_alert" value="<?= $order_alert ?>">
    <input type="hidden" id="scale" name="scale" value="<?= $scale ?>">
    <input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?= $yearcheckbox ?>">
    <input type="hidden" id="year" name="year" value="<?= $year ?>">
    <input type="hidden" id="check" name="check" value="<?= $check ?>">
    <input type="hidden" id="output_check" name="output_check" value="<?= $output_check ?>">
    <input type="hidden" id="plan_output_check" name="plan_output_check" value="<?= $plan_output_check ?>">
    <input type="hidden" id="team_check" name="team_check" value="<?= $team_check ?>">
    <input type="hidden" id="measure_check" name="measure_check" value="<?= $measure_check ?>">
    <input type="hidden" id="cursort" name="cursort" value="<?= $cursort ?>">
    <input type="hidden" id="sortof" name="sortof" value="<?= $sortof ?>">
    <input type="hidden" id="stable" name="stable" value="<?= $stable ?>">
    <input type="hidden" id="sqltext" name="sqltext" value="<?= $sqltext ?>">
    <input type="hidden" id="mywrite" name="mywrite" value="<?= $mywrite ?>">
    <input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?= $BigsearchTag ?>"> 				

<div class="container-fluid justify-content-center align-items-center">
<div class="card mt-2 "> 
<div class="card-body">
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
		<h5> 원자재 구매 & 입출고</h5>  
		<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>
		<button type="button" class="btn btn-primary btn-sm mx-2" id="addToCartBtn" title="구매카트에 담기"> <i class="bi bi-cart-plus"></i> 구매카트 담기 </button>
	</div>
	<!-- 날짜 행 -->
	<div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center date-row">
		<?php
			print '▷ ' .  $total_row . '&nbsp;&nbsp; ' ;					
			if($done_check_val==='0')   
				print '<button class="btn btn-dark  btn-sm  checktask " type="button"> <i class="bi bi-search"></i> 입고 제외 </button>  &nbsp;&nbsp;';
			else
				print '<button class="btn btn-outline-dark  btn-sm  checktask " type="button"   >  <i class="bi bi-search"></i> 입고 포함 </button>  &nbsp;&nbsp;';								  
		?>
		<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 
		<select name="dateRange" id="dateRange" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
			<?php
			$dateRangeArray = array('최근3개월','최근6개월', '최근1년', '최근2년','직접설정','전체');
			$savedDateRange = $_COOKIE['dateRange'] ?? ''; // 쿠키에서 dateRange 값 읽기

			foreach ($dateRangeArray as $range) {
				$selected = ($savedDateRange == $range) ? 'selected' : '';
				echo "<option $selected value='$range'>$range</option>";
			}
			?>
		</select>			
		
		<div id="showframe" class="card">
			<div class="card-header " style="padding:2px;">
				<div class="d-flex justify-content-center align-items-center">  
					기간 설정
				</div>
			</div>
			<div class="card-body">
				<div class="d-flex justify-content-center align-items-center">  	
					<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
					<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange"   onclick='pre_year()' > 전년도 </button>  
					<button type="button" id="three_month" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='three_month_ago()' > M-3월 </button>
					<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='prepre_month()' > 전전월 </button>	
					<button type="button" id="premonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='pre_month()' > 전월 </button> 						
					<button type="button" class="btn btn-outline-danger btn-sm me-1  change_dateRange"  onclick='this_today()' > 오늘 </button>
					<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_month()' > 당월 </button>
					<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_year()' > 당해년도 </button> 
				</div>
			</div>
		</div>		

		<input type="date" id="fromdate" name="fromdate" class="form-control" style="width:100px;" value="<?=$fromdate?>">
		<span class="date-separator">~</span>
		<input type="date" id="todate" name="todate" class="form-control" style="width:100px;" value="<?=$todate?>">
		<button type="button" id="mywriteBtn" class="btn btn-dark  btn-sm" > 내글 </button>	
	</div>

	<!-- 검색 행 -->
	<div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center search-row">
		<select id="find" name="find" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
			<?php
			$findarr=array('전체','입고','출고','공급처');
			for($i=0;$i<count($findarr);$i++) {
				if($find==$findarr[$i]) 
					print "<option selected value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
				else   
					print "<option value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
			} 		   
			?>				   
		</select>
		<select name="Bigsearch" id="Bigsearch" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
			<?php
			for($i=0;$i<count($steelsource_item_yes);$i++) {
				if($Bigsearch==$steelsource_item_yes[$i])
					print "<option selected value='" . $steelsource_item_yes[$i] . "'> " . $steelsource_item_yes[$i] .   "</option>";
				else
					print "<option value='" . $steelsource_item_yes[$i] . "'> " . $steelsource_item_yes[$i] .   "</option>";
			}
			?>
		</select>		   			
		<div class="inputWrap">
			<input type="text" id="search" name="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" class="form-control" style="width:150px;">
			<button class="btnClear"></button>
		</div>				
		<button type="button" id="searchBtn" class="btn btn-dark  btn-sm mx-2 "> <i class="bi bi-search"></i> 검색  </button>
		<div id="autocomplete-list">
		</div>
		<span id="showextract" class="btn btn-primary btn-sm" > <i class="bi bi-tools"></i>  </span>	
		<div id="showextractframe" class="card">
			<div class="card-header text-center " style="padding:2px;">
				자주사용하는 사이즈
			</div>					
			<div class="card-body">
				<div class="p-1 m-1" >
					<button type="button" class="btn btn-primary btn-sm" onclick="HL304_list_click();" > 304 HL </button>	&nbsp;   
					<button type="button" class="btn btn-success btn-sm" onclick="MR304_list_click();" > 304 MR </button>	&nbsp;    			 
					<button type="button" class="btn btn-secondary btn-sm" onclick="VB_list_click();" > VB </button>	&nbsp;    
					<button type="button" class="btn btn-warning btn-sm" onclick="EGI_list_click();" > EGI </button>	&nbsp;    
					<button type="button" class="btn btn-danger btn-sm" onclick="PO_list_click();" > PO </button>	&nbsp;    
					<button type="button" class="btn btn-dark btn-sm" onclick="CR_list_click();" > CR </button>	&nbsp;  
					<button type="button" class="btn btn-success btn-sm" onclick="MR201_list_click();" > 201 2B MR </button>	&nbsp;  
				</div>	
				<div class="p-1 m-1" >
					<span class="text-success "> <strong> 쟘 1.2T &nbsp; </strong> </span>	
					<button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_1950_list_click();"> 1000x1950  </button> &nbsp;
					<button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_2150_list_click();"> 1000x2150  </button> &nbsp;				   
					<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size42150_list_click();">  4'X2150 </button> &nbsp;
					<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size1000_8_list_click();"> 1000x8' </button> &nbsp; 
				</div>	
				<div class="p-1 m-1" >
					&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button"   class="btn btn-outline-success btn-sm"  onclick="size4_8_list_click();"> 4'x8' </button> &nbsp;
					<button type="button"  class="btn btn-outline-success btn-sm"  onclick="size1000_2700_list_click();"> 1000x2700 </button> &nbsp;
					<button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_2700_list_click();"> 4'x2700 </button> &nbsp;
					<button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_3200_list_click();"> 4'x3200  </button> &nbsp;
					<button type="button" class="btn btn-outline-success btn-sm"   onclick="size4_4000_list_click();"> 4'x4000 </button> &nbsp;	   			  
				</div>			  
				<div class="p-1 m-1" >
					<span class="text-success "> <strong> 신규쟘 1.5T(HL) &nbsp; </strong> </span>	
					<button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_2150_list_click();"> 4'x2150 </button> &nbsp;				
					<button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_8_list_click();"> 4'x8' </button> &nbsp;								  
					<span class="text-success "> <strong> 신규쟘 2.0T(EGI) &nbsp; </strong> </span>	
					<button type="button" class="btn btn-outline-success btn-sm" onclick="size20_4_8_list_click();"> 4'x8'  </button> &nbsp;
				</div>			
				<div class=" p-1 m-1" >	   
					천장 1.2T(CR)  </button> &nbsp; 
					<button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1680_list_click();"> 4'x1680 </button> &nbsp;
					<button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1950_list_click();"> 4'x1950 </button> &nbsp;
					<button type="button"  class="btn btn-outline-danger btn-sm"  onclick="size12_4_8_list_click();"> 4'x8' </button> &nbsp;
				</div>			  
				<div class=" p-1 m-1" >			  				   
					천장 1.6T(CR)   &nbsp; 	  
					<button type="button"  class="btn btn-outline-primary btn-sm" onclick="size16_4_1680_list_click();"> 4'x1680 </button> &nbsp;
					<button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_1950_list_click();"> 4'x1950 </button> &nbsp;
					<button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_8_list_click();"> 4'x8' </button> &nbsp;		   		   
				</div>
				<div class=" p-1 m-1" >	
					천장 2.3T(PO)  &nbsp; 	  
					<button type="button" class="btn btn-outline-secondary btn-sm" onclick="size23_4_1680_list_click();"> 4'x1680 </button> &nbsp;
					<button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_1950_list_click();"> 4'x1950 </button> &nbsp;
					<button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_8_list_click();"> 4'x8'  </button> &nbsp;					  
					천장 3.2T(PO)  &nbsp; 	  
					<button type="button" class="btn btn-outline-secondary btn-sm" onclick="size32_4_1680_list_click();"> 4'x1680 </button> &nbsp;									   
				</div>
			</div>					
		</div>			
		<?php
		if(isset($_SESSION["userid"]))
		{
		?>			
		<button type="button" class="btn btn-dark  btn-sm mx-2" id="writeBtn"> <i class="bi bi-pencil"></i>  신규  </button> 			     
		<button type="button" class="btn btn-dark  btn-sm" id="showCost" >  단가 추적 </button>
		<?php
		}
		?>
	</div>
      </div>
      </div>
      </div>
	  
   <div class="row d-flex justify-content-center align-items-center"> 			  
		 <table class="table table-hover table-border w-100" id="myTable">
		   <thead class="table-primary">
		   <tr>
            <th class=" text-center" style="width:3%;" >
				<input type="checkbox" id="selectAll" title="전체 선택/해제">
			</th>
            <th class=" text-center" style="width:4%;" >번호</th>
            <th class=" text-center" style="width:6%;" > 접수 </th>    
            <th class=" text-center" style="width:6%;"> 구매카트 </th>     
            <th class=" text-center" style="width:5%;"> 납기</th>   
            <th class=" text-center" style="width:5%;"> 결재 </th>     
            <th class=" text-center" style="width:5%;"> 완료일 </th>     <!-- 완료일 -->
            <th class=" text-center" style="width:5%;"> 요청인 </th>     
            <th class=" text-center" style="width:5%;"> 진행상태 </th>     
            <th class=" text-center" style="width:5%;"> 이관 </th>     
            <th class=" text-center" style="width:10%;" > 현 장 명 </th>     
            <th class=" text-center" style="width:4%;" >  모 델 명 </th>     
            <th class=" text-primary text-center" style="width:9%;"> 철판종류 </th>    
            <th class=" text-center"> 규격    </th>   
            <th class=" text-danger text-center" >  수량 </th>   
            <th class=" text-center"> 사급여부 </th> 
            <th class=" text-center"  style="width:6%;"> 공급처 </th>  
            <th class=" text-center"  > 공급가액 </th>  
            <th class=" text-center" style="width:15%;"> 비고 </th>  
		  </tr>
		</thead>
	  <tbody>    

<?php
try {
    // 시작 번호 계산
    if ($page <= 1) {
        $start_num = $total_row;
    } else {
        $start_num = $total_row - ($page - 1) * $scale;
    }

    // 데이터 출력
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';

        // 날짜 포맷 변환
        $requestdate = NullCheckDate($requestdate);
        $indate = NullCheckDate($indate);
        $outdate = NullCheckDate($outdate);

        // 날짜 폰트 색상 설정
        $date_font = "text-dark";
        if ($nowday == $outdate) {
            $date_font = "text-success";
        }

        // 출고일에 요일 추가
        if ($outdate != "") {
            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
            $outdate = $outdate . $week[date('w', strtotime($outdate))];
        }

        // 진행상태 설정
        if ($which == '') $which = '1';
        
        switch ($which) {
            case "1":
                $tmp_word = "요청";
                $font_state = "text-primary";
                break;
            case "2":
                $tmp_word = "발주보냄";
                $font_state = "text-danger";
                break;
            case "3":
                $tmp_word = "입고완료";
                $font_state = "text-secondary";
                break;
            default:
                $tmp_word = "";
                $font_state = "";
                break;
        }

        // 요청인 이름 추출 (한글만)
        $pattern = "/^[가-힣]+/";
        preg_match($pattern, $first_writer, $matches);
        $tmpStr = $matches[0] ?? '';

        // 결재 상태 설정
        switch ($status) {
            case 'send':
                $statusstr = '상신';
                break;
            case 'ing':
                $statusstr = '진행';
                break;
            case 'end':
                $statusstr = '완료';
                break;
            default:
                $statusstr = '';
                break;
        }
?>

<tr onclick="redirectToView('<?= $num ?>', '<?= $find ?>', '<?= $search ?>', '<?= $Bigsearch ?>', '<?= $yearcheckbox ?>', '<?= $year ?>', '<?= $fromdate ?>', '<?= $todate ?>', '<?= $separate_date ?>', '<?= $scale ?>','<?= $mywrite ?>')">
    <td class="text-center" onclick="event.stopPropagation();" data-label="">
		<input type="checkbox" class="row-checkbox" name="selected_items[]" value="<?= $num ?>" data-num="<?= $num ?>">
	</td>
    <td class="text-center" data-label="번호"><?= $start_num ?></td>
    <td class="<?= $date_font ?> text-center" data-label="접수" data-order="<?= $outdate ?>"><?= iconv_substr($outdate, 0, 15, "utf-8") ?></td>
    <td class="text-center" data-label="구매카트">
        <?php
        $cart_value = $cart ?? 0;
        if ($cart_value == 1) {
            echo '<span class="badge bg-primary"><i class="bi bi-cart-check"></i> 담김</span>';
        } else {
            echo '&nbsp;';
        }
        ?>
    </td>
    <td class="<?= $date_font ?> text-center" data-label="납기" data-order="<?= $requestdate ?>"><?= iconv_substr($requestdate, 5, 9, "utf-8") ?></td>
    <td class="text-center<?php if ($status === 'ing') echo ' text-primary blink'; ?>" data-label="결재"><?= $statusstr ?> &nbsp;</td>
    <style>
    @keyframes blink {
        0%   { opacity: 1; }
        50%  { opacity: 0.35; }
        100% { opacity: 1; }
    }
    .blink {
        animation: blink 1s linear infinite;
    }
    </style>
    <td class="text-center" data-label="완료일" data-order="<?= $indate ?>"><?= iconv_substr($indate, 5, 9, "utf-8") ?></td>
    <td class="text-center" data-label="요청인"><?= $tmpStr ?></td>
    <td class="<?= $font_state ?> text-center" data-label="진행상태"><?= $tmp_word ?></td>
    <td class="text-center" data-label="이관"><?= $inventory ?> &nbsp;</td>
    <td class="" data-label="현장명"><?= $outworkplace ?></td>
    <td class="text-center" data-label="모델명"><?= iconv_substr($model, 0, 8, "utf-8") ?></td>
    <td class="color-blue text-center" data-label="철판종류"><?= $steel_item ?></td>
    <td class="color-brown text-center" data-label="규격"><?= $spec ?></td>
    <td class="color-red text-center" data-label="수량"><?= $steelnum ?></td>
    <td class="color-green text-center" data-label="사급여부"><?= $company ?></td>
    <td class="text-center" data-label="공급처"><?= $supplier ?></td>
    <td class="text-center" data-label="공급가액">
        <?php
        $suppliercost = $suppliercost ?? '';
        $number = (int)str_replace(',', '', $suppliercost);
        
        if ($number > 0) {
            echo '<span class="badge bg-success">' . htmlspecialchars($suppliercost, ENT_QUOTES, 'UTF-8') . '</span>';
        } else {
            echo htmlspecialchars($suppliercost, ENT_QUOTES, 'UTF-8');
        }
        ?>
    </td>
    <td class="" data-label="비고"><?= htmlspecialchars($request_comment, ENT_QUOTES, 'UTF-8') ?></td>
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

</form>

<!-- 발주서 작성 모달 -->
<div class="modal fade" id="orderWriteModal" tabindex="-1" aria-labelledby="orderWriteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="modal-title" id="orderWriteModalLabel">🛒 구매카트에서 발주서 작성</h5>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button type="button" class="btn btn-sm btn-success" onclick="iframeSaveOrderFromCart()">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="iframeCancelOrderFromCart()">
                        <i class="fas fa-times"></i> 취소
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="height: calc(100vh - 60px); overflow: auto;">
                <iframe id="orderWriteIframe" src="" style="width: 100%; min-height: 100%; border: none; display: block;"></iframe>
            </div>
        </div>
    </div>
</div>	

<div class="container-fluid">
<?php include '../footer_sub.php'; ?>
</div>
  
     </body>
  </html>
  
<script>

var dataTable; // DataTables 인스턴스 전역 변수
var requestpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('requestpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var requestpageNumber = dataTable.page.info().page + 1;
        setCookie('requestpageNumber', requestpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('requestpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('requestpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function saveSearch() {
    let searchInput = document.getElementById('search');
    let searchValue = searchInput.value;

    // console.log('searchValue ' + searchValue);

    if (searchValue === "") {              
		// 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장

		// 폼 제출
		document.getElementById('board_form').submit();
    } else {
        let now = new Date();
        let timestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

        let searches = getSearches();
        // 기존에 동일한 검색어가 있는 경우 제거
        searches = searches.filter(search => search.keyword !== searchValue);
        // 새로운 검색 정보 추가
        searches.unshift({ keyword: searchValue, time: timestamp });
        searches = searches.slice(0, 50);

        document.cookie = "searches=" + JSON.stringify(searches) + "; max-age=31536000";
        // 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장
		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();		
        document.getElementById('board_form').submit();
    }
}

// 검색창에 쿠키를 이용해서 저장하고 화면에 보여주는 코드 묶음
$(document).ready(function() {
    const searchInput = document.getElementById('search');
    const autocompleteList = document.getElementById('autocomplete-list');  

    searchInput.addEventListener('input', function() {
	const val = this.value;
	let searches = getSearches();
	let matches = searches.filter(s => {
		if (typeof s.keyword === 'string') {
			return s.keyword.toLowerCase().includes(val.toLowerCase());
		}
		return false;
	});				
		renderAutocomplete(matches);               
    });
	     
    searchInput.addEventListener('focus', function() {
        let searches = getSearches();
        renderAutocomplete(searches);   

       // console.log(searches);				
    });
			
});

    var isMouseOverSearch = false;
    var isMouseOverAutocomplete = false;

    document.getElementById('search').addEventListener('focus', function() {
        isMouseOverSearch = true;
        showAutocomplete();
    });

	document.getElementById('search').addEventListener('blur', function() {        
		setTimeout(function() {
			if (!isMouseOverAutocomplete) {
				hideAutocomplete();
			}
		}, 100); // Delay of 100 milliseconds
	});

    function hideAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'none';
    }

    function showAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'block';
    }

function renderAutocomplete(matches) {
    const autocompleteList = document.getElementById('autocomplete-list');    

    // Remove all .autocomplete-item elements
    const items = autocompleteList.getElementsByClassName('autocomplete-item');
    while(items.length > 0){
        items[0].parentNode.removeChild(items[0]);
    }

    matches.forEach(function(match) {
			let div = document.createElement('div') ;
			div.className = 'autocomplete-item' ;
			div.innerHTML =  '<span class="text-primary">' + match.keyword + ' </span>';
			div.addEventListener('click', function() {
			document.getElementById('search').value = match.keyword;
			autocompleteList.innerHTML = '';
						
			// console.log(match.keyword);
			document.getElementById('board_form').submit();    
        });
        autocompleteList.appendChild(div);
    });
	
}

function getSearches() {
	let cookies = document.cookie.split('; ');
	// console.log('cookies ' + cookies);	
	for(let cookie of cookies) {
		if(cookie.startsWith('searches=')) {
			return JSON.parse(cookie.substring(9));
		}
	}
	return [];
}


function redirectToView(num, find, search, Bigsearch, yearcheckbox, year, fromdate, todate, separate_date, scale, mywrite) {
    var page = requestpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "view.php?menu=no&num=" + num         
        + "&find=" + find 
        + "&search=" + search 
        + "&Bigsearch=" + Bigsearch 
        + "&yearcheckbox=" + yearcheckbox 
        + "&year=" + year 
        + "&fromdate=" + fromdate 
        + "&todate=" + todate 
        + "&separate_date=" + separate_date 
        + "&scale=" + scale
        + "&mywrite=" + mywrite;       

	customPopup(url, '원자재 구매', 1400, 800); 		    
}


function SearchEnter()
{

    if(event.keyCode === 13){
	  saveSearch(); 
    }
}


$(document).ready(function() { 

		$(".checktask").click(function() {	  
		  // 체크박스가 선택되어 있으면 페이지 리로드
		  $("#page").val('1');	  
		  $("#search").val('');
		  
		  var check = $("#done_check_val").val();		 
		  
		  if(check === '1')
			$("#done_check_val").val('0');		 
			else
				$("#done_check_val").val('1');		 
			
		  $("#board_form").submit();
	  });
	  

	$("#writeBtn").click(function(){ 
		var page = requestpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
			
		var url = "write_form.php"; 

		customPopup(url, '원자재 구매 등록', 1400, 800); 	
	 });		
		 		 
	$("#showCost").click(function(){ 
		var url = "../cost/list.php?menu=no" ; 

		customPopup(url, '단가추이',1800,800);
	 });		
		 

	$("#searchBtn").click(function(){ 		 
		 saveSearch(); 
	 });		
		 
	$("#mywriteBtn").click(function(){ 			  
		 $("#mywrite").val('1');  // 내글
		 document.getElementById('board_form').submit();    
	 
	 });	 


});


$(document).ready(function() {

    // 쿠키에서 dateRange 값을 읽어와 셀렉트 박스에 반영
    var savedDateRange = getCookie('dateRange');
    if (savedDateRange) {
        $('#dateRange').val(savedDateRange);
    }

    // dateRange 셀렉트 박스 변경 이벤트 처리
    $('#dateRange').on('change', function() {
        var selectedRange = $(this).val();
        var currentDate = new Date(); // 현재 날짜
        var fromDate, toDate;

        switch(selectedRange) {
            case '최근3개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 3));
                break;
            case '최근6개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 6));
                break;
            case '최근1년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;
            case '최근2년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 2));
                break;
            case '직접설정':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;   
            case '전체':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 20));
                break;            
            default:
                // 기본 값 또는 예외 처리
                break;
        }

        // 날짜 형식을 YYYY-MM-DD로 변환
        toDate = formatDate(new Date()); // 오늘 날짜
        fromDate = formatDate(fromDate); // 계산된 시작 날짜

        // input 필드 값 설정
        $('#fromdate').val(fromDate);
        $('#todate').val(toDate);
		
		var selectedDateRange = $(this).val();
       // 쿠키에 저장된 값과 현재 선택된 값이 다른 경우에만 페이지 새로고침
        if (savedDateRange !== selectedDateRange) {
            setCookie('dateRange', selectedDateRange, 30); // 쿠키에 dateRange 저장
			document.getElementById('board_form').submit();      
        }		
		
		
    });
});

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

// 전체 선택/해제 기능
$(document).ready(function() {
    $('#selectAll').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
    });

    // 개별 체크박스 변경 시 전체 선택 체크박스 상태 업데이트
    $(document).on('change', '.row-checkbox', function() {
        var totalCheckboxes = $('.row-checkbox').length;
        var checkedCheckboxes = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // 구매카트 담기 버튼 클릭 이벤트
    $('#addToCartBtn').on('click', function() {
        var selectedItems = [];
        $('.row-checkbox:checked').each(function() {
            selectedItems.push($(this).val());
        });

        if (selectedItems.length === 0) {
            alert('구매카트에 담을 항목을 선택해주세요.');
            return;
        }

        // 모달 열기 (발주서 작성 화면)
        openOrderWriteModal(selectedItems);
    });

    // 발주서 작성 모달 열기
    function openOrderWriteModal(itemNums) {
        var modalElement = document.getElementById('orderWriteModal');
        if (!modalElement) {
            console.error('orderWriteModal 요소를 찾을 수 없습니다.');
            return;
        }

        var orderWriteModal = bootstrap.Modal.getOrCreateInstance(modalElement, {
            backdrop: 'static',
            keyboard: true
        });

        var iframe = document.getElementById('orderWriteIframe');
        if (!iframe) {
            console.error('orderWriteIframe 요소를 찾을 수 없습니다.');
            return;
        }

        // 체크된 항목의 num을 콤마로 구분하여 전달
        var cartItems = itemNums.join(',');
        var iframeUrl = '../orders/write_form.php?iframe=1&cart_items=' + encodeURIComponent(cartItems);

        iframe.src = iframeUrl;
        
        // 모달 메시지 리스너 설정
        ensureIframeMessageListenerForCart();
        
        orderWriteModal.show();
    }

    // iframe에서 메시지를 받는 리스너 (구매카트 모달용)
    function ensureIframeMessageListenerForCart() {
        if (window.iframeMessageListenerForCartRegistered) {
            return;
        }
        
        window.addEventListener('message', function(event) {
            // 보안: 같은 출처에서 온 메시지만 처리
            if (event.origin !== window.location.origin) {
                return;
            }
            
            var message = event.data;
            if (message && message.scope === 'orderModule') {
                if (message.type === 'orderSaved') {
                    // 발주서 저장 완료
                    alert(message.payload.message || '발주서가 저장되었습니다.');
                    var modalElement = document.getElementById('orderWriteModal');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                    // 페이지 새로고침 또는 필요한 작업
                    location.reload();
                } else if (message.type === 'orderCanceled') {
                    // 발주서 작성 취소
                    var modalElement = document.getElementById('orderWriteModal');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                }
            }
        });
        
        window.iframeMessageListenerForCartRegistered = true;
    }

    // iframe 내부의 저장 함수 호출
    window.iframeSaveOrderFromCart = function() {
        var iframe = document.getElementById('orderWriteIframe');
        if (iframe && iframe.contentWindow) {
            try {
                // iframe 내부의 saveOrder 함수 호출
                if (typeof iframe.contentWindow.saveOrder === 'function') {
                    iframe.contentWindow.saveOrder();
                } else {
                    alert('저장 기능을 사용할 수 없습니다.');
                }
            } catch (e) {
                console.error('저장 함수 호출 오류:', e);
                alert('저장 중 오류가 발생했습니다.');
            }
        }
    };

    // iframe 내부의 취소 함수 호출
    window.iframeCancelOrderFromCart = function() {
        var iframe = document.getElementById('orderWriteIframe');
        if (iframe && iframe.contentWindow) {
            try {
                // iframe 내부의 cancelOrder 함수 호출
                if (typeof iframe.contentWindow.cancelOrder === 'function') {
                    iframe.contentWindow.cancelOrder();
                } else {
                    // 함수가 없으면 모달만 닫기
                    var modalElement = document.getElementById('orderWriteModal');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                }
            } catch (e) {
                console.error('취소 함수 호출 오류:', e);
                // 오류 발생 시에도 모달 닫기
                var modalElement = document.getElementById('orderWriteModal');
                if (modalElement) {
                    var modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            }
        }
    };
});

</script>

<script>
	$(document).ready(function(){
		saveLogData('원자재 구매'); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 