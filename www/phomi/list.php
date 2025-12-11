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
 
$title_message = '포미스톤 수주';      
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

/* 모바일 환경 최적화 */
@media (max-width: 768px) {
	/* body와 html 오버플로우 방지 */
	html, body {
		overflow-x: hidden !important;
		max-width: 100vw !important;
	}
	
	/* 컨테이너 최적화 */
	.container-fluid {
		padding: 0.5rem !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
	}
	
	/* 카드 최적화 */
	.card {
		width: 100% !important;
		max-width: 100% !important;
		margin: 0.5rem 0 !important;
		padding: 0.5rem !important;
		box-sizing: border-box !important;
	}
	
	.card-body {
		padding: 0.75rem !important;
	}
	
	/* 테이블 숨기기 (모바일에서는 카드로 표시) */
	table:not(.mobile-cards-container table) {
		display: none !important;
	}
	
	/* DataTables UI 요소 숨기기 */
	.dataTables_length,
	.dataTables_filter,
	.dataTables_info,
	.dataTables_paginate {
		display: none !important;
	}
	
	/* 모바일 카드 컨테이너 */
	.mobile-cards-container {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 0 !important;
		box-sizing: border-box !important;
	}
	
	.mobile-card {
		border: 1px solid #ddd;
		border-radius: 0.5rem;
		padding: 0.75rem;
		margin-bottom: 0.75rem;
		background: #f8f9fa;
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}
	
	.mobile-card strong {
		flex-shrink: 0 !important;
		min-width: fit-content !important;
		color: #0d6efd !important;
		margin-right: 0.5rem !important;
	}
	
	.mobile-card span {
		flex: 1 !important;
		min-width: 0 !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		font-size: 0.9em !important;
	}
	
	/* 카드 내부 텍스트 줄바꿈 */
	.mobile-card div {
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
	
	/* 기간 버튼 숨기기 */
	#showdate {
		display: none !important;
	}
	
	/* 기간 설정 프레임 최적화 */
	#showframe {
		width: 100% !important;
		max-width: 100% !important;
		left: 0 !important;
		transform: none !important;
		position: fixed !important;
		top: 50% !important;
		z-index: 9999 !important;
	}
	
	#showframe .card-body {
		padding: 0.75rem !important;
	}
	
	#showframe .d-flex.justify-content-center.align-items-center {
		flex-wrap: wrap !important;
		gap: 0.5rem !important;
	}
	
	#showframe button {
		width: calc(50% - 0.25rem) !important;
		margin: 0.25rem 0 !important;
		padding: 0.5rem !important;
		font-size: 0.9rem !important;
	}
	
	/* 날짜 입력 필드 최적화 */
	.d-flex.mb-1.mt-1.justify-content-center.align-items-center {
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		align-items: center !important;
		justify-content: flex-start !important;
		gap: 0.25rem !important;
		padding: 0.25rem 0.5rem !important;
		white-space: nowrap !important;
		margin-top: 0.25rem !important;
		margin-bottom: 0.25rem !important;
	}
	
	.d-flex.mb-1.mt-1.justify-content-center.align-items-center input[type="date"] {
		width: auto !important;
		flex: 1 1 0 !important;
		min-width: 0 !important;
		max-width: calc(50% - 0.75rem) !important;
		padding: 0.375rem 0.5rem !important;
		font-size: 0.85rem !important;
		margin: 0 !important;
		box-sizing: border-box !important;
	}
	
	/* 검색 영역 최적화 */
	.search-container {
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		align-items: center !important;
		justify-content: flex-start !important;
		gap: 0.5rem !important;
		padding: 0.25rem 0.5rem !important;
		width: 100% !important;
		overflow-x: hidden !important;
		overflow-y: hidden !important;
		margin-top: 0.25rem !important;
	}
	
	.inputWrap {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		position: relative !important;
		display: flex !important;
		align-items: center !important;
		overflow: hidden !important;
	}
	
	.inputWrap input {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 80px 0.5rem 0.75rem !important;
		font-size: 1rem !important;
		margin: 0 !important;
		box-sizing: border-box !important;
		border: 2px solid #28a745 !important;
		border-radius: 0.5rem !important;
		background-color: #fff !important;
	}
	
	.btnClear {
		position: absolute !important;
		right: 50px !important;
		top: 50% !important;
		transform: translateY(-50%) !important;
		width: 24px !important;
		height: 24px !important;
		background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg>') no-repeat center !important;
		background-size: 16px 16px !important;
		border: none !important;
		cursor: pointer !important;
		z-index: 10 !important;
	}
	
	/* 검색 버튼을 입력 필드 내부로 통합 */
	.btn-search-icon {
		position: absolute !important;
		right: 8px !important;
		top: 50% !important;
		transform: translateY(-50%) !important;
		width: 40px !important;
		height: 40px !important;
		min-width: 40px !important;
		padding: 0 !important;
		margin: 0 !important;
		border: none !important;
		background: transparent !important;
		border-radius: 0.25rem !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		z-index: 11 !important;
		cursor: pointer !important;
	}
	
	.btn-search-icon i {
		font-size: 1.2rem !important;
		color: #28a745 !important;
	}
	
	/* 버튼 최적화 */
	.btn-sm {
		font-size: 0.85rem !important;
		padding: 0.375rem 0.5rem !important;
		white-space: nowrap !important;
	}
	
	/* 제목과 버튼 행 분리 */
	.title-row {
		flex-direction: column !important;
		align-items: center !important;
		margin-bottom: 0.5rem !important;
	}
	
	.button-row {
		flex-direction: row !important;
		flex-wrap: wrap !important;
		justify-content: center !important;
		align-items: center !important;
		gap: 0.5rem !important;
		margin-top: 0.5rem !important;
		margin-bottom: 0.5rem !important;
	}
	
	.button-row .btn {
		flex: 0 0 auto !important;
		min-width: fit-content !important;
		margin: 0.25rem !important;
	}
	
	/* 모달창 최적화 */
	.modal-dialog {
		max-width: 100% !important;
		margin: 0 !important;
		height: 100% !important;
	}
	
	.modal-content {
		height: 100% !important;
		border-radius: 0 !important;
	}
	
	.modal-body {
		overflow-y: auto !important;
		max-height: calc(100vh - 120px) !important;
	}
	
	/* 팝업 창 최적화 (customPopup 사용 시) */
	iframe {
		width: 100% !important;
		max-width: 100% !important;
	}
	
	/* 알림 메시지 숨기기 (모바일에서 깜빡임 방지) */
	.alert {
		font-size: 0.85rem !important;
		padding: 0.5rem !important;
	}
	
	/* 알림 메시지 컨테이너 숨기기 */
	.alert-container {
		display: none !important;
	}
}
</style>   
</head>		 
<body>

<?php
$tablename = 'phomi_order';
 if(!$chkMobile) 
{ 	
	require_once(includePath('myheader.php')); 
}

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
    $fromdate = "2025-01-01";
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
$SettingDate = "order_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

// level이 20(데리점)인 경우 author_id 제한 추가
$author_limit = "";
if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
    $author_id = addslashes($_SESSION['userid']);
    $author_limit = " AND author_id = '{$author_id}' ";
    $Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') AND author_id = '{$author_id}' ";
}

$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . $author_limit . " ORDER BY ";

$a = $common . " num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_order " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
if($mode=="search"){
    if($search==""){
        $sql="select * from {$DB}.phomi_order " . $a; 										
    }
    elseif($search!="") { 
        // level 20이면 author_id 제한 추가
        if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
            $author_id = addslashes($_SESSION['userid']);
            $sql ="select * from {$DB}.phomi_order where ((order_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signed_by like '%$search%') or (author like '%$search%') or (author_id like '%$search%') )  " . $Andis_deleted . " AND author_id = '{$author_id}' order by num desc  ";	
        } else {
            $sql ="select * from {$DB}.phomi_order where ((order_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signed_by like '%$search%') or (author like '%$search%') or (author_id like '%$search%') )  " . $Andis_deleted . " order by num desc  ";	
        }
    }
}
if($mode=="") {
   $sql="select * from {$DB}.phomi_order " . $a; 						                         
}						            
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;   
   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
?>

<form name="board_form" id="board_form"  method="post" action="list.php?mode=search">  
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >							
<div class="container-fluid">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center title-row">  
					<h4> <?=$title_message?> </h4>  
				</div>
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center button-row">  
					<button type="button" class="btn btn-dark btn-sm mx-1"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 			
					<button type="button" class="btn btn-primary btn-sm mx-1" onclick="location.href='list_estimate.php';" title="견적 관리">
						<i class="bi bi-file-earmark-text"></i> 견적서로 이동
					</button>
					<button type="button" class="btn btn-success btn-sm mx-1" onclick="location.href='list_outorder.php';" title="출고요청서">
						<i class="bi bi-box-seam"></i> 출고요청서
					</button>
					<button type="button" class="btn btn-secondary btn-sm mx-1" onclick="location.href='unit_price.php';" title="단가표">
						<i class="bi bi-currency-dollar"></i> 단가표
					</button>
				</div>	
				<div class="d-flex justify-content-center align-items-center alert-container"> 
					<div class="alert alert-primary p-2" role="alert">
						포미스톤 수주 관리 시스템입니다. 견적 -> 수주 -> 출고요청서 순으로 이동합니다.
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
					<div class="d-flex justify-content-center align-items-center search-container">  	
				<?php } else { ?>
					<div class="d-flex justify-content-center align-items-center">
				<?php } ?>&nbsp;				
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control w-auto mx-1" placeholder="검색어를 입력해 주세요."> &nbsp;			
				<button class="btnClear"></button>
				<?php if($chkMobile) { ?>
					<button type="button" id="searchBtnMobile" class="btn-search-icon">
						<i class="bi bi-search"></i>
					</button>
				<?php } ?>
			</div>				
			<div id="autocomplete-list">
			</div>
			 &nbsp;												   			   
				<button type="button" id="searchBtn" class="btn btn-dark  btn-sm"> <i class="bi bi-search"></i>  </button>	&nbsp;&nbsp;
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
        <th class="text-start"  style="width:100px;">수주일자</th>		
        <th class="text-start"  style="width:50px;">견적No</th>		
        <th class="text-center text-primary" scope="col" style="width:150px;">수신</th>		
        <th class="text-center text-success" scope="col" style="width:50px;">구분</th>		
        <th class="text-center w200px" > 현장명 </th>
        <th class="text-center w100px" > 작성자</th>
        <th class="text-center w120px" > 출고예정일</th>    
		<th class="text-center w120px" > 실제출고일</th>    
		<th class="text-end w120px" > 업체매출금액</th>    
        <th class="text-end w120px" > 합계(VAT별도)</th>    
		<th class="text-end w120px" >합계(VAT포함)</th>   		
		<th class="text-end w120px" >세금계산서 금액</th>   		
		<th class="text-center w120px" >입금 여부</th>   		
      </tr>
    </thead>	
    <tfoot>
      <tr class="table-info fw-bold">	
        <td colspan="9" class="text-end fw-medium">소계</td>
        <td class="text-end fw-bold" id="total-company-amount">0</td>
        <td class="text-end fw-bold" id="total-ex-vat">0</td>
        <td class="text-end fw-bold" id="total-inc-vat">0</td>
        <td class="text-end fw-bold" id="total-tax-invoice-amount">0</td>
        <td></td>
      </tr>
    </tfoot>
    <tbody>
      <?php      
			$start_num = $total_row; // 페이지당 표시되는 첫번째 글순번
			$total_company_amount = 0; // 전체 업체매출금액 합계
			$total_sum_ex_vat = 0; // 전체 합계(부가세별도)
			$total_sum_inc_vat = 0; // 전체 합계(부가세포함)
			$total_tax_invoice_amount = 0; // 전체 세금계산서 금액 합계
			
			while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				$num = $row['num'];
				$order_date = $row['order_date'];
				$recipient = $row['recipient'];
				$division = $row['division'];
				$site_name = $row['site_name'];
				$signed_by = $row['signed_by'];
				$delivery_due_date = $row['delivery_due_date'];
				$delivery_date = $row['delivery_date'];
				$company_amount = $row['company_amount'] ?? 0;
				$company_amount_ex_vat = $row['company_amount_ex_vat'] ?? 0;
				$company_amount_inc_vat = $row['company_amount_inc_vat'] ?? 0;
				$createdAt = $row['createdAt'] ?? '';
				$updatedAt = $row['updatedAt'] ?? '';
				$total_inc_vat = $row['total_inc_vat'] ?? 0;
				$total_ex_vat = $row['total_ex_vat'] ?? 0;
				$tax_invoice_amount = $row['tax_invoice_amount'] ?? 0;
				$deposit_status = $row['deposit_status'] ?? '';
				$author = $row['author'];
				$author_id = $row['author_id'];
				$estimate_num = $row['estimate_num'];

				// 전체 합계에 추가
				$total_company_amount += $company_amount;
				$total_sum_ex_vat += $total_ex_vat;
				$total_sum_inc_vat += $total_inc_vat;
				$total_tax_invoice_amount += $tax_invoice_amount;
				
				// 금액 포맷팅
				$company_amount_formatted = $company_amount ? number_format($company_amount) : '-';
				$company_amount_ex_vat_formatted = $company_amount_ex_vat ? number_format($company_amount_ex_vat) : '-';
				$company_amount_inc_vat_formatted = $company_amount_inc_vat ? number_format($company_amount_inc_vat) : '-';
				$total_inc_vat_formatted = $total_inc_vat ? number_format($total_inc_vat) : '-';
				$total_ex_vat_formatted = $total_ex_vat ? number_format($total_ex_vat) : '-';
				$tax_invoice_amount_formatted = $tax_invoice_amount ? number_format($tax_invoice_amount) : '-';
				
				// 날짜 포맷팅
				$delivery_due_date_formatted = $delivery_due_date ? $delivery_due_date : '-';
				$delivery_date_formatted = $delivery_date ? $delivery_date : '-';
					
				echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
				?>
					<td class="text-center"><?= $start_num ?></td>
					
					<td class="text-start" data-order="<?= $order_date ?>"> <?=$order_date?> </td>	  
					<td class="text-end" data-order="<?= $estimate_num ?>"> <?=$estimate_num?> </td>	  
					<td class="text-start"
						data-order="<?= $recipient ?>">
						<?= $recipient ?>
					</td>  <!-- 수신 -->
					<td class="text-center"
						data-order="<?= $division ?>">
						<?= $division ?>
					</td>  <!-- 구분 -->
					<td class="text-start"> <?= $site_name ?> </td>          
					<td class="text-center text-primary"><?= $author ?></td>
					<td class="text-center"><?= $delivery_due_date_formatted == '0000-00-00' ? '' : $delivery_due_date_formatted ?></td>
					<td class="text-center"><?= $delivery_date_formatted == '0000-00-00' ? '' : $delivery_date_formatted ?></td>
					<td class="text-end"><?= $company_amount_formatted ?></td>
					<td class="text-end"><?= $total_ex_vat_formatted ?></td>
					<td class="text-end"><?= $total_inc_vat_formatted ?></td>
					<td class="text-end"><?= $tax_invoice_amount_formatted ?></td>
					<td class="text-center"><?= $deposit_status ?></td>
					</tr>
		<?php
			$start_num--;  
			 } 
			 
			 // 전체 합계 포맷팅
			 $total_company_amount_formatted = number_format($total_company_amount);
			 $total_sum_ex_vat_formatted = number_format($total_sum_ex_vat);
			 $total_sum_inc_vat_formatted = number_format($total_sum_inc_vat);
			 $total_tax_invoice_amount_formatted = number_format($total_tax_invoice_amount);
			 
		  } catch (PDOException $Exception) {
		  print "오류: ".$Exception->getMessage();
		  }   
		?>
		
		<script>
		// PHP에서 계산된 합계를 JavaScript로 전달
		var totalCompanyAmount = <?= $total_company_amount ?? 0 ?>;
		var totalSumExVat = <?= $total_sum_ex_vat ?? 0 ?>;
		var totalSumIncVat = <?= $total_sum_inc_vat ?? 0 ?>;
		var totalTaxInvoiceAmount = <?= $total_tax_invoice_amount ?? 0 ?>;
		
		// 페이지 로드 시 소계 업데이트
		$(document).ready(function() {
			$('#total-company-amount').text(totalCompanyAmount.toLocaleString());
			$('#total-ex-vat').text(totalSumExVat.toLocaleString());
			$('#total-inc-vat').text(totalSumIncVat.toLocaleString());
			$('#total-tax-invoice-amount').text(totalTaxInvoiceAmount.toLocaleString());
		});
		</script>
    </tbody>
  </table>
</div>

</div>   
</div>   
</div>  

</form>	 
      
<script>
var dataTable; // DataTables 인스턴스 전역 변수
var requestetcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 페이지 로딩
$(document).ready(function(){	
    var loader = document.getElementById('loadingOverlay');
	if(loader)
		loader.style.display = 'none';
});

// 모바일 카드 렌더링 관련 전역 변수
var isRenderingCards = false;
var renderCardsTimeout = null;
var processedTables = new Set(); // 처리된 테이블 추적 (전역 변수)

// 모바일에서 테이블을 카드 형식으로 변환하는 함수
function renderMobileCards() {
	// 이미 렌더링 중이면 무시
	if (isRenderingCards) {
		return;
	}
	
	// 데스크톱에서는 모든 카드 컨테이너 제거
	if (window.innerWidth > 768) {
		var containers = document.querySelectorAll('.mobile-cards-container');
		containers.forEach(function(container) {
			container.remove();
		});
		return;
	}
	
	// 렌더링 시작 플래그 설정
	isRenderingCards = true;
	
	// 모든 테이블에 대해 카드 변환
	var tables = document.querySelectorAll('table:not(.mobile-cards-container table)');
	
	tables.forEach(function(table) {
		// 테이블이 이미 숨겨져 있거나 카드 컨테이너 내부에 있는 경우 건너뛰기
		if (table.style.display === 'none' || table.closest('.mobile-cards-container')) {
			return;
		}
		
		// 합계 테이블은 카드로 변환하지 않음
		if (table.classList.contains('total-summary-table')) {
			return;
		}
		
		// 테이블 ID 또는 고유 식별자 생성
		var tableId = table.id;
		if (!tableId) {
			var parent = table.parentElement;
			var tableIndex = Array.from(parent.querySelectorAll('table:not(.mobile-cards-container table)')).indexOf(table);
			var parentId = parent.id || parent.className || 'container';
			tableId = 'table-' + parentId.replace(/\s+/g, '-') + '-' + tableIndex;
		}
		
		// 이미 해당 테이블에 대한 카드 컨테이너가 있는지 확인
		var cardsContainer = document.querySelector('#mobileCardsContainer-' + tableId);
		if (!cardsContainer) {
			// 카드 컨테이너 생성
			cardsContainer = document.createElement('div');
			cardsContainer.id = 'mobileCardsContainer-' + tableId;
			cardsContainer.className = 'mobile-cards-container';
			cardsContainer.setAttribute('data-table-id', tableId);
			cardsContainer.style.cssText = 'width: 100%; max-width: 100%; padding: 0.5rem 0;';
			
			// 테이블 다음에 카드 컨테이너 삽입
			if (table.nextSibling) {
				table.parentElement.insertBefore(cardsContainer, table.nextSibling);
			} else {
				table.parentElement.appendChild(cardsContainer);
			}
		}
		
		// 기존 내용 제거 (항상 새로 렌더링)
		cardsContainer.innerHTML = '';
		
		// 처리된 테이블로 표시 (중복 방지)
		processedTables.add(tableId);
		
		// tbody 처리
		var tbody = table.querySelector('tbody');
		if (tbody) {
			var rows = tbody.querySelectorAll('tr');
			
			// 테이블 헤더에서 라벨 가져오기
			var thead = table.querySelector('thead');
			var headers = [];
			if (thead) {
				var headerCells = thead.querySelectorAll('th');
				headerCells.forEach(function(headerCell) {
					headers.push(headerCell.textContent.trim());
				});
			}
			
			rows.forEach(function(row) {
				var cells = row.querySelectorAll('td');
				if (cells.length === 0) return;
				
				var card = document.createElement('div');
				card.className = 'mobile-card';
				card.style.cssText = 'border: 1px solid #ddd; border-radius: 0.5rem; padding: 0.75rem; margin-bottom: 0.75rem; background: #f8f9fa;';
				
				// 클릭 이벤트 복사
				if (row.onclick) {
					card.onclick = row.onclick;
				} else if (row.getAttribute('onclick')) {
					card.setAttribute('onclick', row.getAttribute('onclick'));
				} else if (row.getAttribute('data-id')) {
					var dataId = row.getAttribute('data-id');
					card.setAttribute('data-id', dataId);
					card.style.cursor = 'pointer';
					card.addEventListener('click', function() {
						if (typeof redirectToView === 'function') {
							redirectToView(dataId);
						}
					});
				}
				
				cells.forEach(function(cell, index) {
					var label = cell.getAttribute('data-label') || headers[index] || '항목 ' + (index + 1);
					
					var cardItem = document.createElement('div');
					cardItem.style.cssText = 'padding: 0.5rem 0; border-bottom: 1px solid #eee; display: flex; flex-wrap: wrap; align-items: center;';
					if (index === cells.length - 1) {
						cardItem.style.borderBottom = 'none';
					}
					
					var labelSpan = document.createElement('strong');
					labelSpan.textContent = label + ': ';
					labelSpan.style.cssText = 'color: #007bff; margin-right: 0.5rem; flex-shrink: 0;';
					
					var valueSpan = document.createElement('span');
					valueSpan.innerHTML = cell.innerHTML;
					valueSpan.style.cssText = 'word-wrap: break-word; overflow-wrap: break-word; flex: 1; min-width: 0;';
					
					cardItem.appendChild(labelSpan);
					cardItem.appendChild(valueSpan);
					card.appendChild(cardItem);
				});
				
				cardsContainer.appendChild(card);
			});
		}
		
		// tfoot 처리
		var tfoot = table.querySelector('tfoot');
		if (tfoot) {
			var tfootRow = tfoot.querySelector('tr');
			if (tfootRow) {
				var tfootCells = tfootRow.querySelectorAll('td');
				if (tfootCells.length > 0) {
					var summaryCard = document.createElement('div');
					summaryCard.className = 'mobile-card-summary';
					summaryCard.style.cssText = 'border: 2px solid #0dcaf0; border-radius: 0.5rem; padding: 0.75rem; margin-top: 1rem; background: #d1ecf1; font-weight: bold;';
					
					tfootCells.forEach(function(cell, index) {
						var summaryItem = document.createElement('div');
						summaryItem.style.cssText = 'padding: 0.5rem 0;';
						
						var label = document.createElement('strong');
						label.style.cssText = 'color: #0dcaf0; margin-right: 0.5rem;';
						label.textContent = '소계: ';
						
						var value = document.createElement('span');
						value.innerHTML = cell.innerHTML;
						
						summaryItem.appendChild(label);
						summaryItem.appendChild(value);
						summaryCard.appendChild(summaryItem);
					});
					
					cardsContainer.appendChild(summaryCard);
				}
			}
		}
	});
	
	// 렌더링 완료 플래그 해제
	setTimeout(function() {
		isRenderingCards = false;
	}, 100);
}

// debounce 함수
function debounce(func, wait) {
	var timeout;
	return function executedFunction() {
		var context = this;
		var args = arguments;
		var later = function() {
			timeout = null;
			func.apply(context, args);
		};
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
	};
}

// 모바일 카드 렌더링 debounce
var debouncedRenderMobileCards = debounce(renderMobileCards, 300);

// 모바일 환경 확인 함수
function isMobile() {
	return window.innerWidth <= 768;
}

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
        "order": [[0, 'desc']],
        "drawCallback": function(settings) {
            // DataTables 그리기 완료 후 모바일 카드 렌더링
            if (isMobile()) {
                setTimeout(function() {
                    processedTables.clear();
                    renderMobileCards();
                }, 100);
            }
        }
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
    
    // 초기 로드 시 모바일 카드 렌더링
    if (isMobile()) {
        setTimeout(function() {
            renderMobileCards();
        }, 500);
    }
    
    // 창 크기 변경 시 모바일 카드 렌더링
    $(window).on('resize', function() {
        debouncedRenderMobileCards();
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
    
    // 모바일 검색 버튼 이벤트
    $(document).on('click', '#searchBtnMobile', function() {
        $("#searchBtn").click();
    });
    
    // 검색창 클리어 버튼 이벤트
    $(document).on('click', '.btnClear', function() {
        $('#search').val('').focus();
    });
});

$(document).ready(function() { 
	$("#writeBtn").click(function(){ 		
		var tablename = $("#tablename").val();			
		var url = "write_form.php?tablename=" + tablename ; 
		customPopup(url, '등록', 1200, 950); 		
	 });	 
	$("#searchBtn").click(function() { 
		// 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장

		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();
		document.getElementById('board_form').submit();
	});
}); 


function redirectToView(num) {    
    var tablename = $("#tablename").val();    	
	
    var url = "write_form.php?mode=view&num=" + num         
        + "&tablename=" + tablename;   
	customPopup(url, '', 1200, 950); 			
}

function restorePageNumber() {    
    location.reload();
}

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('<?=$title_message?>'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 

</body>
</html>
