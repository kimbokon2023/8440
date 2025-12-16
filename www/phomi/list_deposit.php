<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
require_once getDocumentRoot() . '/vendor/autoload.php';
require_once(includePath('lib/mydb.php'));

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';	

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
$client->addScope(\Google\Service\Drive::DRIVE);

// Google Drive 서비스 초기화
$service = new \Google\Service\Drive($client);

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    return count($response->files) > 0 ? $response->files[0]->id : null;
}

// Google Drive에서 파일 썸네일 검사 및 반환
function getThumbnail($fileId, $service) {
    try {
        $file = $service->files->get($fileId, ['fields' => 'thumbnailLink']);
        return $file->thumbnailLink ?? null; // 썸네일 URL이 있으면 반환, 없으면 null
    } catch (Exception $e) {
        error_log("썸네일 가져오기 실패: " . $e->getMessage());
        return null; // 실패 시 null 반환
    }
}
 
$title_message = '포미스톤 본사 예치금 및 지출 관리';      
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? ''; 
?> 
 
<?php include getDocumentRoot() . '/load_header.php'; ?>  
<title> <?=$title_message?>  </title>  
 
<style>
#showextract {
	display: inline-block;
	position: relative;
}		
.showextractframe {
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
/* 금액 관련 스타일 */
.income-amount {
    color: #007bff !important;
    font-weight: bold !important;
}
.expense-amount {
    color: #dc3545 !important;
    font-weight: bold !important;
}
.balance-amount {
    color: #6c757d !important;
    font-weight: bold !important;
}
.negative-balance {
    color: #dc3545 !important;
}
/* 테이블 내부 요소에 대한 추가 스타일 */
#myTable .income-amount,
#myTable td.income-amount {
    color: #007bff !important;
}

#myTable .expense-amount,
#myTable td.expense-amount {
    color: #dc3545 !important;
}

#myTable .balance-amount,
#myTable td.balance-amount {
    color: #6c757d !important;
}

#myTable .negative-balance,
#myTable td.negative-balance {
    color: #dc3545 !important;
}

/* PC/모바일 공통 검색창 스타일 */
.inputWrap {
	flex: 0 1 auto !important;
	min-width: 200px !important;
	max-width: 400px !important;
	position: relative !important;
	display: flex !important;
	align-items: center !important;
}

.inputWrap input {
	width: 100% !important;
	max-width: 100% !important;
	padding: 0.5rem 80px 0.5rem 0.75rem !important;
	font-size: 0.9rem !important;
	border: 2px solid #28a745 !important;
	border-radius: 0.5rem !important;
	background-color: #fff !important;
	margin: 0 !important;
	box-sizing: border-box !important;
}

.btnClear {
	position: absolute !important;
	right: 12px !important;
	top: 50% !important;
	transform: translateY(-50%) !important;
	width: 24px !important;
	height: 24px !important;
	background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg>') no-repeat center !important;
	background-size: 16px 16px !important;
	border: none !important;
	cursor: pointer !important;
	z-index: 10 !important;
	opacity: 0.6 !important;
	transition: opacity 0.2s ease !important;
}

.btnClear:hover {
	opacity: 1 !important;
}

.btn-search-icon {
	position: absolute !important;
	right: 8px !important;
	top: 50% !important;
	transform: translateY(-50%) !important;
	width: 40px !important;
	height: 40px !important;
	min-width: 40px !important;
	padding: 0 !important;
	border: none !important;
	background: transparent !important;
	display: none !important;
	align-items: center !important;
	justify-content: center !important;
	z-index: 11 !important;
	cursor: pointer !important;
	border-radius: 0.25rem !important;
	transition: background-color 0.2s ease !important;
}

.btn-search-icon i {
	font-size: 1.2rem !important;
	color: #28a745 !important;
}

.btn-search-icon:hover {
	background-color: rgba(40, 167, 69, 0.1) !important;
}

/* PC 화면 - 검색창 최대 너비 제한 */
@media (min-width: 769px) {
	.inputWrap {
		max-width: 200px !important;
	}
	
	.pc-only-btn {
		display: inline-block !important;
	}
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
	
	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin: 0 !important;
		padding: 0 !important;
		max-width: 100vw !important;
		overflow-x: hidden !important;
	}

	/* 카드 모바일 최적화 */
	.card {
		margin: 0.5rem auto !important;
		width: 100% !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
		border-radius: 12px !important;
	}
	
	/* 카드 컨테이너 가운데 정렬 */
	.d-flex.justify-content-center.align-items-center {
		justify-content: center !important;
		flex-wrap: wrap !important;
	}
	
	/* 상단 카드 영역 가운데 정렬 */
	.card-body > .d-flex.justify-content-center.align-items-center {
		justify-content: center !important;
		align-items: center !important;
	}

	.card-body {
		padding: 0.75rem 0.5rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.card-title {
		font-size: 0.9rem !important;
		margin-bottom: 0.75rem !important;
		padding: 0 0.5rem !important;
	}

	/* 제목 영역 */
	h4 {
		font-size: 1.1rem !important;
		white-space: nowrap !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	/* 버튼 모바일 최적화 */
	.btn-sm {
		font-size: 0.85rem !important;
		padding: 0.4rem 0.6rem !important;
		white-space: nowrap !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	/* PC 전용 버튼 숨기기 */
	.pc-only-btn {
		display: none !important;
	}

	/* PC 전용 카드 숨기기 */
	.pc-only-card {
		display: none !important;
	}

	/* 기간 버튼 숨기기 */
	#showdate {
		display: none !important;
	}

	/* 기간 설정 팝업 모바일 최적화 */
	.showextractframe,
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

	/* 날짜 입력 필드 */
	#fromdate, #todate {
		width: auto !important;
		min-width: 120px !important;
		max-width: 150px !important;
		font-size: 0.9rem !important;
		padding: 0.4rem !important;
		box-sizing: border-box !important;
		flex-shrink: 0 !important;
	}

	/* 검색 영역 모바일 최적화 */
	.inputWrap {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		max-width: none !important;
	}
	
	.inputWrap input {
		font-size: 1rem !important;
	}

	.btn-search-icon {
		display: flex !important;
	}

	/* 검색 버튼 */
	#searchBtn {
		white-space: nowrap !important;
	}

	/* 상단 제어 영역 세로 배치 */
	.card-body .d-flex {
		flex-direction: column !important;
		align-items: stretch !important;
		gap: 10px !important;
	}

	.card-body > .d-flex:first-of-type {
		flex-direction: row !important;
		justify-content: space-between !important;
		align-items: center !important;
		margin-bottom: 15px !important;
		padding-bottom: 12px !important;
		border-bottom: 2px solid #e9ecef !important;
		flex-wrap: wrap !important;
	}

	/* 날짜 입력 영역 - 한 줄에 표시 */
	.card-body .d-flex:has(#fromdate),
	.d-flex:has(#fromdate) {
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		align-items: center !important;
		gap: 0.5rem !important;
		justify-content: flex-start !important;
	}
	
	/* 날짜 입력 영역의 ~ 기호 */
	.d-flex:has(#fromdate) span,
	.card-body .d-flex:has(#fromdate) span {
		white-space: nowrap !important;
		flex-shrink: 0 !important;
	}

	/* DataTables 숨기기 */
	.dataTables_wrapper .dataTables_length,
	.dataTables_wrapper .dataTables_filter {
		display: none !important;
	}

	.dataTables_wrapper .dataTables_paginate {
		font-size: 0.9rem !important;
		margin-top: 15px !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button {
		padding: 0.5rem 0.7rem !important;
		margin: 0 2px !important;
	}

	.dataTables_wrapper .dataTables_info {
		font-size: 0.9rem !important;
		text-align: center !important;
		margin-top: 10px !important;
		margin-bottom: 10px !important;
	}

	/* 테이블 모바일 최적화 - 카드 레이아웃 */
	.table-responsive {
		overflow-x: visible !important;
	}

	#myTable thead {
		display: none !important;
	}

	#myTable,
	#myTable tbody,
	#myTable tr,
	#myTable td {
		display: block !important;
		width: 100% !important;
	}

	#myTable tr {
		margin: 0 auto 15px auto !important;
		border: 1px solid #dee2e6 !important;
		border-radius: 10px !important;
		background: white !important;
		box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
		padding: 14px !important;
		overflow: hidden !important;
		box-sizing: border-box !important;
		width: 100% !important;
		max-width: 100% !important;
	}

	/* 카드 내 필드 스타일 */
	#myTable td {
		text-align: left !important;
		padding: 12px !important;
		border: none !important;
		position: relative !important;
		padding-left: 35% !important;
		white-space: normal !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		min-height: 40px !important;
		font-size: 1rem !important;
		line-height: 1.6 !important;
		box-sizing: border-box !important;
	}

	/* 라벨 표시 */
	#myTable td:before {
		content: attr(data-label);
		position: absolute !important;
		left: 12px !important;
		width: 30% !important;
		padding-right: 8px !important;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
		font-weight: 600 !important;
		color: #6b7280 !important;
		font-size: 0.9rem !important;
	}

	#myTable td:after {
		content: ':' !important;
		position: absolute !important;
		left: 32% !important;
		font-weight: bold !important;
		color: #9ca3af !important;
	}

	/* 모든 텍스트와 버튼이 카드 내부에 머물도록 */
	.card *,
	.container *,
	.container-fluid *,
	.row *,
	.col-md-* {
		box-sizing: border-box !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	.card button,
	.card .btn,
	.card span,
	.card input,
	.card table,
	.card p,
	.card ul,
	.card li,
	.card strong,
	.card label,
	.card select {
		max-width: 100% !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		white-space: normal !important;
	}
}
</style>   
</head>		 
<body>

<?php
$tablename = 'phomi_deposit';
 require_once(includePath('myheader.php')); 
 // 모바일이면 특정 CSS 적용
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
 
$pdo = db_connect();

// 현재 날짜
$currentDate = date("Y-m-d");

$fromdate = $_REQUEST['fromdate'] ?? '';
$todate = $_REQUEST['todate'] ?? '';

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-12 months", strtotime($currentDate))); // 12개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
$SettingDate = "deposit_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . " ORDER BY ";

$a = $common . " num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_deposit " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
if($mode=="search"){
	  if($search==""){
				$sql="select * from {$DB}.phomi_deposit " . $a; 										
			   }
		 elseif($search!="") { 			    
			  $sql ="select * from {$DB}.phomi_deposit where ((deposit_date like '%$search%')  or (note like '%$search%' ) ";
			  $sql .="or (deposit_amount like '%$search%') )  " . $Andis_deleted . " order by num desc  ";										 								
			}
	   }
if($mode=="") {
   $sql="select * from {$DB}.phomi_deposit " . $a; 						                         
}						            
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;   
   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
	
	// 지출 데이터 조회 (phomi_order 테이블에서 출고일이 있는 것들)
	// phomi_unitprice 테이블과 조인하여 공급가 단가를 가져옴
	// 같은 날짜를 그룹핑하지 않고 수주 데이터 각각 보관
	$expense_sql = "SELECT o.delivery_date, o.items, o.other_costs, 
	                       o.site_name, o.recipient, o.num as order_num
	                FROM {$DB}.phomi_order o
	                WHERE o.delivery_date IS NOT NULL 
	                AND o.delivery_date != '0000-00-00' 
	                AND o.delivery_date BETWEEN '$fromdate' AND '$Transtodate'
	                AND (o.is_deleted IS NULL OR o.is_deleted = 'N')
	                ORDER BY o.delivery_date DESC, o.num DESC";
	
	$expense_stmh = $pdo->query($expense_sql);
	$daily_orders = []; // 날짜별 수주 목록 [date] => [order1, order2...]

	while ($expense_row = $expense_stmh->fetch(PDO::FETCH_ASSOC)) {
	    $date = $expense_row['delivery_date'];
	    if (!isset($daily_orders[$date])) {
	        $daily_orders[$date] = [];
	    }
	    
	    $order_expense = 0;
	    
	    // items에서 공급가액과 세액 계산 (phomi_unitprice 테이블의 공급가 단가 사용)
	    if (!empty($expense_row['items'])) {
	        $items = json_decode($expense_row['items'], true);
	        if (is_array($items)) {
	            foreach ($items as $item) {
	                if (isset($item['area']) && isset($item['product_code'])) {
	                    $area = floatval(str_replace(',', '', $item['area']));
	                    $product_code = $item['product_code'];
	                    
	                    // phomi_unitprice 테이블에서 공급가 단가 조회
	                    $unit_price_sql = "SELECT price_per_m2 FROM {$DB}.phomi_unitprice WHERE prodcode = ?";
	                    $unit_price_stmh = $pdo->prepare($unit_price_sql);
	                    $unit_price_stmh->execute([$product_code]);
	                    $unit_price_row = $unit_price_stmh->fetch(PDO::FETCH_ASSOC);
	                    
						// 총판 공급가 price_per_m2 컬럼
	                    if ($unit_price_row && floatval(str_replace(',', '', $unit_price_row['price_per_m2'])) > 0) {
	                        // 공급가 단가로 계산
	                        $supply_amount = $area * $unit_price_row['price_per_m2'];
	                        $tax_amount = $supply_amount * 0.1; // 10% 부가세
	                        $order_expense += $supply_amount + $tax_amount;
	                    } else {
	                        // 공급가 단가가 없으면 기존 단가 사용 (fallback)
	                        if (isset($item['unit_price'])) {
	                            $unit_price = floatval(str_replace(',', '', $item['unit_price']));
	                            $supply_amount = $area * $unit_price;
	                            $tax_amount = $supply_amount * 0.1; // 10% 부가세
	                            $order_expense += $supply_amount + $tax_amount;								
	                        }
	                    }
	                }
	            }
	        }
	    }
		    
	    // other_costs는 기존대로 계산 (공급가 단가가 별도로 저장되어 있지 않음)
	    if (!empty($expense_row['other_costs'])) {
	        $other_costs = json_decode($expense_row['other_costs'], true);
	        if (is_array($other_costs)) {
	            foreach ($other_costs as $cost) {
	                if (isset($cost['quantity']) && isset($cost['unit_price'])) {
	                    $quantity = floatval($cost['quantity']);
	                    $unit_price = floatval(str_replace(',', '', $cost['unit_price']));
	                    
	                    // 본드 항목에 대해 본사 협약가격 5000원 적용
	                    if (isset($cost['item']) && strpos($cost['item'], '본드') !== false) {
	                        $unit_price = 5000; // 본사 협약가격
	                    }
	                    
	                    $supply_amount = $quantity * $unit_price;
	                    $tax_amount = $supply_amount * 0.1; // 10% 부가세
	                    $order_expense += $supply_amount + $tax_amount;
	                }
	            }
	        }
	    }
	    
	    // 개별 수주 정보 저장
		$daily_orders[$date][] = [
			'expense' => $order_expense,
			'site_name' => $expense_row['site_name'],
			'recipient' => $expense_row['recipient'],
			'order_num' => $expense_row['order_num']
		];
	}
	
	// 입금 데이터 조회
	$daily_deposits = [];
	$first_deposit_nums = []; // 날짜별 첫 번째 입금 번호 (강제 지출 저장용)
	
	while ($deposit_row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	    $date = $deposit_row['deposit_date'];
	    if (!isset($daily_deposits[$date])) {
	        $daily_deposits[$date] = [];
	        $first_deposit_nums[$date] = $deposit_row['num']; // 첫 번째 발견된 번호 저장 (쿼리가 num desc이므로 가장 큰 번호일 수 있음. 필요시 정렬 고려, 원본은 순서대로 였음)
	    }
	    // 원본 쿼리가 order by num desc이므로, 가장 최신 번호가 먼저 옴.
	    // 저장용 num은 아무거나 상관없을 수 있으나 기존 로직 유지.
	    
	    $daily_deposits[$date][] = [
	        'amount' => $deposit_row['deposit_amount'],
	        'num' => $deposit_row['num'],
	        'note' => $deposit_row['note'] ?? '' // note가 없을 수도 있으니 체크
	    ];
	}
	
	// 강제 지출 금액 조회 (force_outcome) - phomi_deposit 테이블에서 날짜별로 조회
	$force_outcomes = []; // 계산용 (숫자로 변환된 값)
	$force_outcome_raw = []; // 원본 텍스트 저장용 (모달 표시용)
	$force_outcome_nums = []; // 날짜별 첫 번째 입금 레코드의 num 저장 (DB에서 조회한 것)
	$force_outcome_sql = "SELECT deposit_date, force_outcome, MIN(num) as first_num 
	                      FROM {$DB}.phomi_deposit 
	                      WHERE deposit_date BETWEEN '$fromdate' AND '$Transtodate'
	                      AND (is_deleted IS NULL OR is_deleted = 'N')
	                      AND force_outcome IS NOT NULL 
	                      AND force_outcome != ''
	                      GROUP BY deposit_date, force_outcome";
	$force_outcome_stmh = $pdo->query($force_outcome_sql);
	while ($force_row = $force_outcome_stmh->fetch(PDO::FETCH_ASSOC)) {
	    $force_value = trim($force_row['force_outcome']);
	    $date = $force_row['deposit_date'];
	    
	    // 원본 텍스트 저장 (모달 표시용)
	    $force_outcome_raw[$date] = $force_value;
	    
	    // '매장' 텍스트는 0으로 처리, 숫자는 그대로 사용 (계산용)
	    if ($force_value === '매장') {
	        $force_outcomes[$date] = 0;
	    } else {
	        $force_outcomes[$date] = floatval($force_value);
	    }
	    $force_outcome_nums[$date] = $force_row['first_num'];
	}
	
	// 모든 날짜를 합쳐서 정렬
	$all_dates = array_unique(array_merge(array_keys($daily_deposits), array_keys($daily_orders)));
	rsort($all_dates); // 최신 날짜부터 정렬
	
	// 누적 잔액 계산 - 시작 잔액을 0으로 설정
	$running_balance = 0;
	$balance_data = [];
	
	// 날짜순으로 정렬 (과거부터 현재까지) - 잔액 계산을 위해
	sort($all_dates);
	
	foreach ($all_dates as $date) {
		// 해당 날짜의 입금 리스트
		$deposits_for_day = $daily_deposits[$date] ?? [];
		
		// 해당 날짜의 첫 번째 입금 레코드의 num 찾기 (force_outcome 저장용)
	    $first_deposit_num = $first_deposit_nums[$date] ?? null;
		
		// force_outcome 정보
		$force_outcome_val = $force_outcomes[$date] ?? null;
		$force_outcome_raw_value = $force_outcome_raw[$date] ?? null;
		
		// DB에서 조회한 first_num이 있으면 그것을 우선 사용 (기존 로직 유지)
		if (isset($force_outcome_nums[$date])) {
	        $first_deposit_num = $force_outcome_nums[$date];
	    }

		// 1. 입금 처리 - 개별 입금 내역 표시
		foreach ($deposits_for_day as $deposit) {
			$income = $deposit['amount'];
			if ($income > 0) { // 금액이 0인 입금 내역도 표시해야 하나? 보통 0원은 없을 것.
				$running_balance += $income;
				
				$balance_data[] = [
					'date' => $date,
					'income' => $income,
					'expense' => 0,
					'balance' => $running_balance,
					'site_names' => '',
					'recipients' => '',
					'order_nums' => '',
					'deposit_nums' => [$deposit['num']], // 개별 번호 배열로 전달
					'force_outcome' => $force_outcome_val,
					'force_outcome_raw' => $force_outcome_raw_value,
					'first_deposit_num' => $first_deposit_num,
					'type' => 'income',
					'note' => $deposit['note'] // 입금 비고 표시 가능성 대비 -> 나중에 view에서 활용 가능
				];
			}
		}

		// 2. 지출 처리
		// 2. 지출 처리
		// 규칙:
		// 1) 강제 지출 금액이 숫자(금액)로 설정된 경우 -> 해당 금액으로 1개 행 통합 표시 (사용자가 금액을 지정했으므로 그 금액이 정확히 표시되어야 함)
		// 2) '매장'으로 설정된 경우 -> 개별 수주 내역 표시하되 금액은 0원 (내역은 보고 싶으나 비용처리 안 함)
		// 3) 강제 지출이 없는 경우 -> 개별 수주 내역 표시
		
		// 강제 지출이 있고, 그 값이 '매장'이 아닌 경우 (즉, 금액 수정)
		if ($force_outcome_val !== null && $force_outcome_raw_value !== '매장') {
			// 강제 지출 금액으로 1개 행 생성 (통합)
			$expense = $force_outcome_val;
			$running_balance -= $expense;
			
			$site_names_str = '';
			$recipients_str = '';
			$order_nums_str = '';
			if (isset($daily_orders[$date])) {
				foreach ($daily_orders[$date] as $order) {
					$site_names_str .= $order['site_name'] . '; ';
					$recipients_str .= $order['recipient'] . '; ';
					$order_nums_str .= $order['order_num'] . ',';
				}
			}
			$site_names_str = trim($site_names_str, '; ');
			$recipients_str = trim($recipients_str, '; ');
			$order_nums_str = trim($order_nums_str, ',');

			$balance_data[] = [
				'date' => $date,
				'income' => 0,
				'expense' => $expense,
				'balance' => $running_balance,
				'site_names' => $site_names_str,
				'recipients' => $recipients_str,
				'order_nums' => $order_nums_str,
				'deposit_nums' => [], 
				'force_outcome' => $force_outcome_val,
				'force_outcome_raw' => $force_outcome_raw_value,
				'first_deposit_num' => $first_deposit_num,
				'type' => 'expense_forced'
			];
		}
		// 그 외 (강제지출 없음 OR '매장') -> 수주 데이터가 있으면 개별 표시
		elseif (isset($daily_orders[$date]) && count($daily_orders[$date]) > 0) {
			// 수주 내역 loop
			foreach ($daily_orders[$date] as $order) {
				$expense = $order['expense'];
				
				// '매장'으로 설정된 경우 지출액을 0으로 처리
				if ($force_outcome_raw_value === '매장') {
					$expense = 0;
				}
				
				$running_balance -= $expense;

				$balance_data[] = [
					'date' => $date,
					'income' => 0,
					'expense' => $expense,
					'balance' => $running_balance,
					'site_names' => $order['site_name'],
					'recipients' => $order['recipient'],
					'order_nums' => $order['order_num'],
					'deposit_nums' => [],
					'force_outcome' => null, // 개별 수주 표시 시 강제 지출 값은 연결하지 않음
					'force_outcome_raw' => $force_outcome_raw_value,
					'first_deposit_num' => $first_deposit_num,
					'type' => 'expense'
				];
			}
		} 
		// 수주 데이터는 없는데 강제 지출 설정이 있는 경우 ('매장' 포함)
		elseif ($force_outcome_val !== null) {
			// 강제 지출 금액으로 1개 행 생성
			$expense = $force_outcome_val;
			$running_balance -= $expense;

			$balance_data[] = [
				'date' => $date,
				'income' => 0,
				'expense' => $expense,
				'balance' => $running_balance,
				'site_names' => '',
				'recipients' => '',
				'order_nums' => '',
				'deposit_nums' => [], 
				'force_outcome' => $force_outcome_val,
				'force_outcome_raw' => $force_outcome_raw_value,
				'first_deposit_num' => $first_deposit_num,
				'type' => 'expense_forced'
			];
		}
	}
	
	// 최종 결과를 최신 날짜부터 표시하기 위해 역순으로 정렬
	$balance_data = array_reverse($balance_data);
	
	// 입금 데이터를 다시 조회 (테이블 표시용)
	$stmh = $pdo->query($sql);
?>

<form name="board_form" id="board_form"  method="post" action="list_deposit.php?mode=search">  
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >							
<div class="container">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
					<h4> <?=$title_message?> </h4>  
					<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 			
				</div>	
				<div class="d-flex justify-content-center align-items-center"> 
					<div class="alert alert-primary p-2 pc-only-card" role="alert">
						포미스톤 본사 예치금 및 지출 관리 시스템입니다. 총판 공급가 기준으로 예치금이 차감됩니다. 지출액은 VAT포함 금액입니다.
					</div>		 
				</div>					
			<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  													   
			<!-- 기간부터 검색까지 연결 묶음 start -->
			<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 		
			<div id="showframe" class="card showextractframe" style="width:500px;">
				<div class="card-header " style="padding:2px;">
					<div class="d-flex justify-content-center align-items-center">  
						기간 설정
					</div>
				</div>
				<div class="card-body ">
					<div class="d-flex justify-content-center align-items-center">  	
						<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
						<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange" onclick='pre_year()'> 전년도 </button>  
						<button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='three_month_ago()'> M-3월 </button>
						<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='prepre_month()'> 전전월 </button>	
						<button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='pre_month()'> 전월 </button> 						
						<button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange "  onclick='this_today()'> 오늘 </button>
						<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_month()'> 당월 </button>
						<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_year()'> 당해년도 </button> 
					</div>
				</div>
			</div>		
			   <input type="date" id="fromdate" name="fromdate" size="12"  class="form-control"   style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate" size="12"   class="form-control"   style="width:100px;" value="<?=$todate?>" placeholder="기간 끝">  &nbsp;     </span> 
			   &nbsp;&nbsp;
				   
				<?php if($chkMobile) { ?>
						</div>
					<div class="d-flex justify-content-center align-items-center">  	
				<?php } ?>&nbsp;				
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control w-auto mx-1" placeholder="날짜, 비고, 입금액 검색..." > &nbsp;			
				<button class="btnClear" type="button"></button>
				<button type="button" id="searchBtnMobile" class="btn-search-icon">
					<i class="bi bi-search"></i>
				</button>
			</div>				
			<div id="autocomplete-list">
			</div>
			 &nbsp;												   			   
				<button type="button" id="searchBtn" class="btn btn-dark btn-sm pc-only-btn"> <i class="bi bi-search"></i>  </button>	&nbsp;&nbsp;
				<button type="button" class="btn btn-outline-secondary btn-sm me-1" onclick="openHelpModal()"><i class="bi bi-info-circle"></i></button>
				<button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	    			 
		</div>
	</div>
  </div>	
<style>
th {
    white-space: nowrap;
}
</style>		  
<div class="card mb-2">
<div class="card-body">	  	  
   <div class="table-responsive"> 	
   <table class="table table-hover " id="myTable">
         <thead class="table-primary">
       <tr>
         <th class="text-start"  style="width:5%;">번호</th>
         <th class="text-start"  style="width:100px;">날짜</th>		
         <th class="text-end income-amount" scope="col" style="width:150px;">입금액</th>		
         <th class="text-end expense-amount" scope="col" style="width:150px;">지출액(VAT포함)</th>		
         <th class="text-end balance-amount" scope="col" style="width:150px;">잔액</th>		
         <th class="text-start" style="width:300px;">현장명</th>
         <th class="text-start" style="width:300px;">수신처</th>
         <th class="text-start w300px" > 입금/지출 구분 </th>
         <th class="text-center" style="width:100px;">지출 수정</th>
       </tr>
     </thead>	
    <tbody>
      <?php      
			$start_num = count($balance_data); // 페이지당 표시되는 첫번째 글순번      
			foreach ($balance_data as $index => $data) {
				$date = $data['date'];
				$income = $data['income'];
				$expense = $data['expense'];
				$balance = $data['balance'];
				$site_names = $data['site_names'];
				$recipients = $data['recipients'];
				$order_nums = $data['order_nums'];
				$deposit_nums = $data['deposit_nums'];
				
				// 금액 포맷팅
				$income_formatted = $income > 0 ? number_format($income) : '-';
				$expense_formatted = $expense > 0 ? number_format($expense) : '-';
				$balance_formatted = number_format($balance);
				
				// 비고 생성
				$note = '';
				if ($income > 0 && $expense > 0) {
					$note = '입금 및 지출';
				} elseif ($income > 0) {
					$note = '입금';
				} elseif ($expense > 0) {
					$note = '지출';
				}
				
				// 잔액이 음수인 경우 스타일 적용
				$balance_class = $balance < 0 ? 'negative-balance' : 'balance-amount';
				
				// 입금/지출 여부에 따라 다른 클릭 이벤트 설정
				$click_data = '';
				if ($income > 0 && $expense > 0) {
					// 입금 및 지출이 모두 있는 경우 - 입금 처리 우선
					$click_data = 'data-type="income" data-date="'.$date.'" data-deposit-nums="'.implode(',', $deposit_nums).'"';
				} elseif ($income > 0) {
					// 입금만 있는 경우
					$click_data = 'data-type="income" data-date="'.$date.'" data-deposit-nums="'.implode(',', $deposit_nums).'"';
				} elseif ($expense > 0) {
					// 지출만 있는 경우 - 수주 번호 조회 필요
					$click_data = 'data-type="expense" data-date="'.$date.'" data-order-nums="'.$order_nums.'"';
				}
				
				echo '<tr class="deposit-row" '.$click_data.'>';
				?>
					<td class="text-center" data-label="번호"><?= $start_num ?></td>
					<td class="text-start" data-label="날짜" data-order="<?= $date ?>"> <?=$date?> </td>	  
					<td class="text-end income-amount" data-label="입금액" data-order="<?= $income ?>">
						<?= $income_formatted ?>
					</td>  <!-- 입금액 -->
					<td class="text-end expense-amount" data-label="지출액(VAT포함)" data-order="<?= $expense ?>">
						<?= $expense_formatted ?>
					</td>  <!-- 지출액 -->
					<td class="text-end <?= $balance_class ?>" data-label="잔액" data-order="<?= $balance ?>">
						<?= $balance_formatted ?>
					</td>  <!-- 잔액 -->
					<td class="text-start" data-label="현장명"> <?= $site_names ? $site_names : '-' ?> </td>  <!-- 현장명 -->
					<td class="text-start" data-label="수신처"> <?= $recipients ? $recipients : '-' ?> </td>  <!-- 수신처 -->
					<td class="text-start" data-label="입금/지출 구분"> <?= $note ?> </td>
					<td class="text-center edit-expense-cell" data-label="지출 수정">
						<button type="button" class="btn btn-sm btn-warning"
						        onclick="return openExpenseModal('<?= $date ?>','<?= $expense ?>','<?= htmlspecialchars($data['force_outcome_raw'] ?? ($data['force_outcome'] ?? ''), ENT_QUOTES) ?>','<?= $data['first_deposit_num'] ?? '' ?>');">
							<i class="bi bi-pencil"></i> 수정
						</button>
					</td>          
					</tr>
		<?php
			$start_num--;  
			 } 
		  } catch (PDOException $Exception) {
		  print "오류: ".$Exception->getMessage();
		  }   
		?>
    </tbody>
  </table>
</div>

</div>   
</div>   
</div>  

</form>

<!-- 지출 수정 모달 -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="font-size: 1.3rem;">
            <div class="modal-header">
                <h5 class="modal-title" id="editExpenseModalLabel" style="font-size: 1.625rem; font-weight: 600;">지출 금액 수정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="transform: scale(1.3);"></button>
            </div>
            <div class="modal-body" style="font-size: 1.3rem;">
                <form id="editExpenseForm">
                    <input type="hidden" id="expense_date" name="expense_date">
                    <input type="hidden" id="expense_num" name="expense_num">
                    <div class="mb-4">
                        <label for="force_outcome" class="form-label" style="font-size: 1.3rem; font-weight: 500; margin-bottom: 0.75rem;">강제 지출 금액 (VAT 포함)</label>
                        <input type="text" class="form-control" id="force_outcome" name="force_outcome" 
                               placeholder="지출 금액을 입력하세요 (예: 1000000 또는 '매장')" 
                               style="font-size: 1.3rem; padding: 0.65rem 0.9rem; height: auto;" required>
                        <small class="form-text text-muted" style="font-size: 1.1rem; display: block; margin-top: 0.5rem;">
                            이 금액이 해당 날짜의 지출액으로 사용됩니다.<br>
                            <span class="text-info"><strong>※ '매장'을 입력하면 0으로 처리되어 원래 계산된 지출액이 표시됩니다.</strong></span>
                        </small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" style="font-size: 1.3rem; font-weight: 500; margin-bottom: 0.75rem;">현재 계산된 지출액</label>
                        <input type="text" class="form-control" id="current_expense" readonly 
                               style="font-size: 1.3rem; padding: 0.65rem 0.9rem; height: auto;">
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="font-size: 1.3rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 1.3rem; padding: 0.65rem 1.3rem;">취소</button>
                <button type="button" class="btn btn-primary" id="saveExpenseBtn" style="font-size: 1.3rem; padding: 0.65rem 1.3rem;">저장</button>
            </div>
        </div>
    </div>
</div>
      
<!-- 도움말 모달 -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white py-3">
                <h5 class="modal-title fs-5" id="helpModalLabel">
                    <i class="bi bi-question-circle"></i> 지출 수정(강제 지출 금액) 사용법
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto; font-size: 0.95rem;">
                <div class="p-2">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-cash-coin"></i> 강제 지출 금액 입력</h6>
                    <p class="text-muted mb-3">
                        - ‘지출 수정’ 버튼을 눌러 금액을 입력하면, 해당 날짜의 지출액을 강제로 덮어씁니다.<br>
                        - 입력한 금액이 화면의 지출액보다 우선하여 사용됩니다.
                    </p>
                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-arrow-counterclockwise"></i> 복원(0 또는 '매장' 입력)</h6>
                    <p class="text-muted mb-3">
                        - 강제 지출 금액에 <strong>0</strong> 또는 <strong>'매장'</strong>을 입력 후 저장하면 강제값이 제거되고, 원래 계산된 지출액으로 복원됩니다.<br>
                        - <span class="text-info"><strong>'매장'을 입력하면 자동으로 0으로 처리됩니다.</strong></span>
                    </p>
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-lightning-charge"></i> 저장 동작</h6>
                    <p class="text-muted mb-3">
                        - 금액 &gt; 0: 해당 날짜의 첫 입금 레코드에 강제 지출 금액을 저장합니다.<br>
                        - 금액 = 0 또는 '매장': 강제 지출 금액 컬럼을 NULL로 설정하여 강제값을 제거합니다.
                    </p>
                    <h6 class="fw-bold text-secondary mb-2"><i class="bi bi-shield-check"></i> 주의사항</h6>
                    <p class="text-muted mb-1">
                        - 숫자 또는 '매장'을 입력할 수 있습니다. (음수 불가)<br>
                        - 저장 후 페이지가 새로고침되며 적용 결과가 반영됩니다.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var requestetcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 페이지 로딩
$(document).ready(function(){	
    var loader = document.getElementById('loadingOverlay');
	if(loader)
		loader.style.display = 'none';
});

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 200,
        "lengthMenu": [200, 500, 1000, 2000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('requestetcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var requestetcpageNumber = dataTable.page.info().page + 1;
        setCookie('requestetcpageNumber', requestetcpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('requestetcpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('requestetcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

$(document).ready(function() {
    // Event listener for keydown on #search
    $("#search").keydown(function(event) {
        // Check if the pressed key is 'Enter'
        if (event.key === "Enter" || event.keyCode === 13) {
            // Prevent the default action to stop form submission
            event.preventDefault();
            // Trigger click event on #searchBtn
            $("#searchBtn").click();
        }
    });	
});

// 모바일 환경 감지
function isMobile() {
	return window.innerWidth <= 768;
}

// 검색 기능
function performSearch() {
	var searchValue = $('#search').val();
	if (dataTable) {
		dataTable.search(searchValue).draw();
	}
}

// 검색창 클리어
$(document).on('click', '.btnClear', function() {
	$('#search').val('');
	if (dataTable) {
		dataTable.search('').draw();
	}
});

$(document).ready(function() { 
	$("#writeBtn").click(function(){ 		
		var tablename = $("#tablename").val();			
		var url = "write_form_deposit.php?tablename=" + tablename ; 
		customPopup(url, '등록', 800, 600); 		
	 });	 
	$("#searchBtn, #searchBtnMobile").click(function() { 
		// 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장

		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();
		document.getElementById('board_form').submit();
	});
	
	// 모바일에서 DataTables 초기화 시 길이 및 필터 숨기기
	if (isMobile()) {
		$('.dataTables_length, .dataTables_filter').hide();
	}
}); 


function redirectToView(num) {    
    var tablename = $("#tablename").val();    	
	
    var url = "write_form_deposit.php?mode=view&num=" + num         
        + "&tablename=" + tablename;   
	customPopup(url, '', 800, 600); 			
}

function restorePageNumber() {    
    location.reload();
}

// 테이블 행 클릭 이벤트 처리
$(document).ready(function(){
	$('#myTable tbody').on('click', 'tr', function(e) {
		// 행 내부 지출 수정 영역 클릭 시 행 클릭 처리 스킵 (이중 팝업 방지)
		if ($(e.target).closest('.edit-expense-cell').length) {
			return;
		}
		var type = $(this).data('type');
		var date = $(this).data('date');
		var orderNums = $(this).data('order-nums');
		var depositNums = $(this).data('deposit-nums');
		
		console.log('Click event - type:', type, 'date:', date, 'orderNums:', orderNums, 'depositNums:', depositNums);
		
		if (type === 'income') {
			// 입금인 경우 - 입금 번호를 사용하여 상세보기 또는 수정 팝업
			if (depositNums && depositNums !== '') {
				// 첫 번째 입금 번호 사용 (여러 개가 있을 경우)
				var firstDepositNum = depositNums.toString().split(',')[0];
				if (firstDepositNum && firstDepositNum !== '') {
					console.log('Opening deposit with num:', firstDepositNum);
					var tablename = $("#tablename").val();
					var url = "write_form_deposit.php?mode=view&num=" + firstDepositNum + "&tablename=" + tablename;
					customPopup(url, '입금 상세보기', 800, 600);
				} else {
					console.log('Invalid deposit number:', firstDepositNum);
				}
			} else {
				console.log('No deposit numbers found for date:', date);
			}
		} else if (type === 'expense') {
			// 지출인 경우 - 수주 상세보기 팝업 (num 고유키 사용)
			if (orderNums && orderNums !== '') {
				// 첫 번째 수주 번호 사용 (여러 개가 있을 경우)
				var firstOrderNum = orderNums.toString().split(',')[0];
				if (firstOrderNum && firstOrderNum !== '') {
					console.log('Opening order with num:', firstOrderNum);
					var url = "write_form.php?mode=view&num=" + firstOrderNum;
					customPopup(url, '수주 상세보기', 1200, 800);
				} else {
					console.log('Invalid order number:', firstOrderNum);
				}
			} else {
				console.log('No order numbers found for date:', date);
			}
		}
	});
});

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('<?=$title_message?>'); // 다른 페이지에 맞는 menuName을 전달
});

// 지출 수정 모달 관련
$(document).ready(function(){
	// Bootstrap 5 모달 핸들러
	const modalEl = document.getElementById('editExpenseModal');
	let editExpenseModal = null;
	if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
		editExpenseModal = new bootstrap.Modal(modalEl);
	}
	// 도움말 모달 핸들러
	const helpModalEl = document.getElementById('helpModal');
	let helpModal = null;
	if (helpModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
		helpModal = new bootstrap.Modal(helpModalEl);
	}
	// Bootstrap이 없을 때를 위한 수동 show/hide 함수
	const showFallbackModal = () => {
		if (!modalEl) return;
		modalEl.style.display = 'block';
		modalEl.classList.add('show');
		modalEl.removeAttribute('aria-hidden');
		modalEl.setAttribute('aria-modal', 'true');
		document.body.classList.add('modal-open');
	};
	const hideFallbackModal = () => {
		if (!modalEl) return;
		modalEl.style.display = 'none';
		modalEl.classList.remove('show');
		modalEl.setAttribute('aria-hidden', 'true');
		modalEl.removeAttribute('aria-modal');
		document.body.classList.remove('modal-open');
	};

	// 도움말 모달 열기 함수 (estimate/index.php 스타일)
	window.openHelpModal = function() {
		if (helpModal) {
			helpModal.show();
		} else if (helpModalEl) {
			helpModalEl.style.display = 'block';
			helpModalEl.classList.add('show');
			helpModalEl.removeAttribute('aria-hidden');
			helpModalEl.setAttribute('aria-modal', 'true');
			document.body.classList.add('modal-open');
		}
		return false;
	};

	// 전역 함수로 모달 열기 (행 클릭과 충돌 방지, inline onclick 사용)
	window.openExpenseModal = function(date, expense, forceOutcome, num) {
		const expVal = parseFloat(expense) || 0;
		$('#expense_date').val(date);
		$('#expense_num').val(num || '');
		$('#current_expense').val(expVal > 0 ? number_format(expVal) + '원' : '-');
		$('#force_outcome').val(forceOutcome || '');
		
		if (editExpenseModal) {
			editExpenseModal.show();
		} else {
			showFallbackModal(); // fallback
		}
		return false; // 클릭 이벤트 전파/기본동작 방지
	};
	
	// 저장 버튼 클릭 이벤트
	$('#saveExpenseBtn').click(function(){
		const date = $('#expense_date').val();
		let num = $('#expense_num').val();
		let forceOutcome = $('#force_outcome').val().trim();
		
		if (!date) {
			alert('날짜 정보가 없습니다.');
			return;
		}
		
		// 빈 문자열이나 0은 허용 (기존 로직 유지 - 복원 기능)
		// '매장' 텍스트도 허용 (서버에서 처리)
		// 숫자가 아닌 경우 체크 (단, 빈 문자열, 0, '매장'은 제외)
		if (forceOutcome !== '' && forceOutcome !== '0' && forceOutcome !== '매장' && isNaN(parseFloat(forceOutcome))) {
			alert('올바른 지출 금액을 입력하세요.\n(숫자, 0, 빈 값, 또는 "매장"을 입력할 수 있습니다.)');
			return;
		}
		
		// 음수는 허용하지 않음 (단, '매장'은 제외)
		if (forceOutcome !== '' && forceOutcome !== '0' && forceOutcome !== '매장' && parseFloat(forceOutcome) < 0) {
			alert('음수는 입력할 수 없습니다.');
			return;
		}
		
		// AJAX로 저장
		$.ajax({
			url: 'process_force_outcome.php',
			type: 'POST',
			data: {
				mode: 'save',
				expense_date: date,
				num: num,
				force_outcome: forceOutcome
			},
			dataType: 'json',
			success: function(response){
				if (response.success) {
					alert('지출 금액이 저장되었습니다.');
					if (editExpenseModal) {
						editExpenseModal.hide();
					} else {
						hideFallbackModal();
					}
					location.reload();
				} else {
					alert('저장 실패: ' + (response.message || '알 수 없는 오류'));
				}
			},
			error: function(xhr, status, error){
				console.error('Error:', error);
				alert('저장 중 오류가 발생했습니다.');
			}
		});
	});
	
	// 숫자 포맷팅 함수
	function number_format(num) {
		return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
});
</script> 

</body>
</html>
