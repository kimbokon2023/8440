<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../common/functions.php';
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
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
 
$title_message = '포미스톤 견적서';      
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
		max-width: 100% !important;
		width: 100% !important;
		box-sizing: border-box !important;
		margin: 0 !important;
		padding: 0 !important;
	}
	
	* {
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
	
	/* 컨테이너 최적화 */
	.container,
	.container-fluid {
		padding: 0.5rem !important;
		max-width: 100% !important;
		width: 100% !important;
		box-sizing: border-box !important;
		margin: 0 auto !important;
		overflow-x: hidden !important;
	}
	
	/* 카드 영역 최적화 */
	.card {
		margin: 0.5rem auto !important;
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}
	
	.card-body {
		padding: 0.75rem !important;
		overflow-x: hidden !important;
		overflow-y: visible !important;
		max-height: none !important;
		height: auto !important;
	}
	
	/* 제목 영역 최적화 */
	.d-flex.mb-3.mt-2.justify-content-center.align-items-center {
		flex-direction: row !important;
		flex-wrap: wrap !important;
		align-items: center !important;
		justify-content: center !important;
		gap: 0.25rem !important;
		padding: 0.5rem !important;
		margin-bottom: 0.5rem !important; /* mb-3 (1rem)을 절반으로 줄임 */
	}
	
	.d-flex.mb-3.mt-2.justify-content-center.align-items-center h4 {
		width: 100% !important;
		text-align: center !important;
		font-size: 1.5rem !important;
		margin-bottom: 0.5rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		flex-basis: 100% !important;
		flex-shrink: 0 !important;
	}
	
	.d-flex.mb-3.mt-2.justify-content-center.align-items-center button {
		width: auto !important;
		flex: 0 0 auto !important;
		min-width: auto !important;
		max-width: none !important;
		margin: 0.125rem !important;
		padding: 0.375rem 0.75rem !important;
		font-size: 0.9rem !important;
		white-space: nowrap !important;
	}
	
	.d-flex.mb-3.mt-2.justify-content-center.align-items-center button i {
		margin-right: 0.25rem !important;
	}
	
	/* 알림 영역 최적화 - 모바일에서 숨김 */
	.alert-container {
		display: none !important;
	}
	
	.alert {
		display: none !important;
	}
	
	/* 검색 영역 최적화 */
	.d-flex.justify-content-center.align-items-center {
		flex-direction: column !important;
		align-items: stretch !important;
		gap: 0.5rem !important;
		padding: 0.5rem !important;
		overflow-x: hidden !important;
		overflow-y: hidden !important;
		max-height: none !important;
		height: auto !important;
	}
	
	/* 날짜 입력 영역 - 한 행에 표시 */
	.d-flex.mb-1.mt-1.justify-content-center.align-items-center {
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		align-items: center !important;
		justify-content: flex-start !important;
		gap: 0.25rem !important;
		padding: 0.25rem 0.5rem !important; /* 상하 패딩을 절반으로 줄임 */
		white-space: nowrap !important;
		margin-top: 0.25rem !important; /* mt-1 (0.25rem)을 절반으로 줄임 */
		margin-bottom: 0.25rem !important; /* mb-1 (0.25rem)을 절반으로 줄임 */
	}
	
	/* 기간 설정 영역 최적화 */
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
	
	/* 날짜 입력 필드 최적화 - 한 행에 표시 */
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
	
	/* 일반 날짜 입력 (다른 곳에 있는 경우) */
	input[type="date"]:not(.d-flex.mb-1.mt-1.justify-content-center.align-items-center input[type="date"]) {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem !important;
		font-size: 1rem !important;
		margin: 0.25rem 0 !important;
	}
	
	/* 검색 영역 최적화 - 한 행에 검색 입력과 버튼 통합 */
	.search-container {
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		align-items: center !important;
		justify-content: flex-start !important;
		gap: 0.5rem !important;
		padding: 0.25rem 0.5rem !important; /* 상하 패딩을 절반으로 줄임 */
		width: 100% !important;
		overflow-x: hidden !important;
		overflow-y: hidden !important;
		max-height: none !important;
		height: auto !important;
		margin-top: 0.25rem !important; /* 상단 마진 추가하여 간격 조정 */
	}
	
	.inputWrap {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		position: relative !important;
		display: flex !important;
		align-items: center !important;
		overflow: hidden !important;
		max-height: none !important;
		height: auto !important;
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
		overflow: hidden !important;
		overflow-x: hidden !important;
		overflow-y: hidden !important;
		height: auto !important;
		max-height: none !important;
		line-height: 1.5 !important;
	}
	
	.inputWrap input::placeholder {
		color: #999 !important;
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
	
	.btn-search-icon:hover {
		background-color: rgba(40, 167, 69, 0.1) !important;
	}
	
	/* 모바일에서는 PC 검색 버튼 숨김 */
	.search-container #searchBtn {
		display: none !important;
	}
	
	/* 버튼 최적화 */
	button {
		width: 100% !important;
		max-width: 100% !important;
		margin: 0.25rem 0 !important;
		padding: 0.5rem !important;
		font-size: 1rem !important;
	}
	
	/* DataTables 숨기기 */
	.dataTables_length,
	.dataTables_filter,
	.dataTables_info,
	.dataTables_paginate {
		display: none !important;
	}
	
	/* 테이블을 카드 형식으로 변환 */
	.table-responsive {
		overflow-x: visible !important;
	}
	
	.table {
		display: none !important;
	}
	
	.table thead {
		display: none !important;
	}
	
	.table tbody {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.table tbody tr {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		margin-bottom: 0.75rem !important;
		border: 1px solid #ddd !important;
		border-radius: 0.5rem !important;
		padding: 0.75rem !important;
		background: #f8f9fa !important;
		box-sizing: border-box !important;
		cursor: pointer !important;
	}
	
	.table tbody td {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 0 !important;
		text-align: left !important;
		border: none !important;
		font-size: 0.9rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		box-sizing: border-box !important;
	}
	
	.table tbody td:before {
		content: attr(data-label) ": ";
		font-weight: bold !important;
		color: #007bff !important;
		margin-right: 0.5rem !important;
		display: inline-block !important;
	}
	
	.table tbody td:first-child:before {
		content: "번호: " !important;
	}
	
	.table tbody td:nth-child(2):before {
		content: "견적일자: " !important;
	}
	
	.table tbody td:nth-child(3):before {
		content: "수신: " !important;
	}
	
	.table tbody td:nth-child(4):before {
		content: "구분: " !important;
	}
	
	.table tbody td:nth-child(5):before {
		content: "현장명: " !important;
	}
	
	.table tbody td:nth-child(6):before {
		content: "작성자: " !important;
	}
	
	.table tbody td:nth-child(7):before {
		content: "합계(VAT별도): " !important;
	}
	
	.table tbody td:nth-child(8):before {
		content: "합계(VAT포함): " !important;
	}
	
	/* tfoot 최적화 */
	.table tfoot {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		margin-top: 1rem !important;
	}
	
	.table tfoot tr {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		border: 2px solid #0dcaf0 !important;
		border-radius: 0.5rem !important;
		padding: 0.75rem !important;
		background: #d1ecf1 !important;
		box-sizing: border-box !important;
	}
	
	.table tfoot td {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 0 !important;
		text-align: left !important;
		border: none !important;
		font-size: 0.9rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		box-sizing: border-box !important;
	}
	
	.table tfoot td:before {
		content: attr(data-label) ": ";
		font-weight: bold !important;
		color: #0dcaf0 !important;
		margin-right: 0.5rem !important;
		display: inline-block !important;
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
		max-width: 100% !important;
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
	
	/* 자동완성 리스트 최적화 */
	#autocomplete-list {
		width: 100% !important;
		max-width: 100% !important;
		left: 0 !important;
		right: 0 !important;
		top: 100% !important;
		position: absolute !important;
		z-index: 99 !important;
		overflow-x: hidden !important;
		overflow-y: auto !important;
		max-height: 200px !important;
	}
	
	/* 검색 영역 전체에 스크롤 제거 */
	.search-container,
	.search-container * {
		overflow-y: hidden !important;
		max-height: none !important;
	}
	
	.search-container .inputWrap,
	.search-container .inputWrap * {
		overflow-y: hidden !important;
		max-height: none !important;
	}
	
	/* 모바일 카드 컨테이너 */
	.mobile-cards-container {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 0 !important;
		box-sizing: border-box !important;
	}
	
	.mobile-card {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}
	
	.mobile-card .d-flex {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
	
	.mobile-card strong {
		flex-shrink: 0 !important;
		min-width: fit-content !important;
	}
	
	.mobile-card span {
		flex: 1 !important;
		min-width: 0 !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		font-size: 0.8em !important; /* 현재 폰트 크기의 80%로 줄임 (20% 감소) */
	}
	
	.mobile-card-summary {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}
	
	.mobile-card-summary .d-flex {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
	
	.mobile-card-summary strong {
		flex-shrink: 0 !important;
		min-width: fit-content !important;
	}
	
	.mobile-card-summary span {
		flex: 1 !important;
		min-width: 0 !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
}

/* PC 환경에서 모바일 전용 요소 숨김 */
@media (min-width: 769px) {
	.btn-search-icon,
	#searchBtnMobile {
		display: none !important;
	}
}
</style>   
</head>		 
<body>

<?php
$tablename = 'phomi_estimate';

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
    $fromdate = '2025-01-01'; // 최초 견적일자
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
$SettingDate = "quote_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

// level이 20(데리점)인 경우 author_id 제한 추가
$author_limit = "";
if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
    $author_id = addslashes($_SESSION['userid']);
    $author_limit = " AND author_id = '{$author_id}' ";
    $Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') AND author_id = '{$author_id}' ";
}

// 기본 쿼리 조건
$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . $author_limit . " ORDER BY ";

$a = $common . " quote_date DESC, num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_estimate " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    

if($mode=="search"){
    if($search==""){			  
        $sql="select * from {$DB}.phomi_estimate " . $a; 										
    }
    elseif($search!="") { 
        // level 20이면 author_id 제한 추가
        if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
            $author_id = addslashes($_SESSION['userid']);
            $sql ="select * from {$DB}.phomi_estimate where ((quote_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signer like '%$search%') )  " . $Andis_deleted . " AND author_id = '{$author_id}' order by num desc  ";	
        } else {
            $sql ="select * from {$DB}.phomi_estimate where ((quote_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signer like '%$search%') )  " . $Andis_deleted . " order by num desc  ";	
        }
    }
}
if($mode=="") {
   $sql="select * from {$DB}.phomi_estimate " . $a; 						                         
}
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;
   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
?>

<form name="board_form" id="board_form"  method="post" action="list_estimate.php?mode=search">  
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >							
<div class="container-fluid">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
					<h4> <?=$title_message?> </h4>  
					<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 			
					<button type="button" class="btn btn-danger btn-sm mx-1" onclick="location.href='list.php';" title="수주 관리">
						<i class="bi bi-file-earmark-text"></i> 수주서로 이동
					</button>
					<button type="button" class="btn btn-success btn-sm mx-1" onclick="location.href='list_outorder.php';" title="출고요청서">
						<i class="bi bi-box-seam"></i> 출고요청서
					</button>
					<button type="button" class="btn btn-secondary btn-sm mx-1" onclick="location.href='unit_price.php';" title="단가표">
						<i class="bi bi-currency-dollar"></i> 단가표
					</button>
					<button type="button" class="btn btn-dark btn-sm mx-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button>					
				</div>	
				<div class="d-flex justify-content-center align-items-center alert-container"> 
					<div class="alert alert-primary p-2" role="alert">
						포미스톤 견적서 관리 시스템입니다. 견적 -> 수주 -> 출고요청서 순으로 이동합니다.
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
					<div class="d-flex justify-content-center align-items-center search-container">
				<?php } ?>	&nbsp;				
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control w-auto mx-1" placeholder="검색어를 입력해 주세요."> &nbsp;			
				<button class="btnClear"></button>
				<button type="button" id="searchBtnMobile" class="btn-search-icon"> <i class="bi bi-search"></i>  </button>
			</div>				
			<div id="autocomplete-list">
			</div>
			 &nbsp;												   			   
				<?php if(!$chkMobile) { ?>
					<button type="button" id="searchBtn" class="btn btn-dark  btn-sm"> <i class="bi bi-search"></i>  </button>	&nbsp;&nbsp;
				<?php } ?>
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
        <th class="text-start"  style="width:100px;">견적일자</th>		
        <th class="text-center text-primary" scope="col" style="width:100px;">수신</th>		
        <th class="text-center text-success" scope="col" style="width:100px;">구분</th>		
        <th class="text-center w300px" > 현장명 </th>
        <th class="text-center w100px" > 작성자</th>
        <th class="text-center w120px" > 합계(VAT별도)</th>    
		<th class="text-center w120px" >합계(VAT포함)</th>    
      </tr>
    </thead>
    <tfoot>
      <tr class="table-info fw-bold">	
        <td colspan="6" class="text-end fw-medium" data-label="소계">소계</td>
        <td class="text-end fw-medium" id="total-ex-vat" data-label="합계(VAT별도)">0</td>
        <td class="text-end fw-medium" id="total-inc-vat" data-label="합계(VAT포함)">0</td>
      </tr>
    </tfoot>
    <tbody>
      <?php      
			$start_num = $total_row; // 페이지당 표시되는 첫번째 글순번
			$total_sum_ex_vat = 0; // 전체 합계(부가세별도)
			$total_sum_inc_vat = 0; // 전체 합계(부가세포함)
			
			while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				$num = $row['num'];
				$quote_date = $row['quote_date'];
				$recipient = $row['recipient'];
				$division = $row['division'];
				$site_name = $row['site_name'];
				$signer = $row['signer'];
				$author = $row['author'];
				$author_id = $row['author_id'];
				$createdAt = $row['createdAt'];
				$updatedAt = $row['updatedAt'];
				
				// JSON 데이터 파싱 및 합계 계산
				$items = [];
				$other_costs = [];
				$total_supply = 0;
				$total_tax = 0;
				$other_costs_supply = 0;
				$other_costs_tax = 0;
				
							
				// 최종 합계 계산
				$total_ex_vat = $row['total_ex_vat'];
				$total_inc_vat = $row['total_inc_vat'];
				
				// 전체 합계에 추가
				$total_sum_ex_vat += $total_ex_vat;
				$total_sum_inc_vat += $total_inc_vat;
				
				$total_ex_vat = number_format($total_ex_vat);
				$total_inc_vat = number_format($total_inc_vat);
					
				echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
				?>
					<td class="text-center" data-label="번호"><?= $start_num ?></td>
					
					<td class="text-start" data-order="<?= $quote_date ?>" data-label="견적일자"> <?=$quote_date?> </td>	  
					<td class="text-start"
						data-order="<?= $recipient ?>"
						data-label="수신">
						<?= $recipient ?>
					</td>  <!-- 수신 -->
					<td class="text-center"
						data-order="<?= $division ?>"
						data-label="구분">
						<?= $division ?>
					</td>  <!-- 구분 -->
					<td class="text-start" data-label="현장명"> <?= $site_name ?> </td>          
					<td class="text-center text-primary" data-label="작성자"><?= $author ?></td>
					<td class="text-end" data-label="합계(VAT별도)"><?= $total_ex_vat ?></td>
					<td class="text-end" data-label="합계(VAT포함)"><?= $total_inc_vat ?></td>
					</tr>
		<?php
			$start_num--;  
			 } 
			 
			 // 전체 합계 포맷팅
			 $total_sum_ex_vat_formatted = number_format($total_sum_ex_vat);
			 $total_sum_inc_vat_formatted = number_format($total_sum_inc_vat);
			 
		  } catch (PDOException $Exception) {
		  print "오류: ".$Exception->getMessage();
		  }   
		?>
		
		<script>
		// PHP에서 계산된 합계를 JavaScript로 전달
		var totalSumExVat = <?= $total_sum_ex_vat ?? 0 ?>;
		var totalSumIncVat = <?= $total_sum_inc_vat ?? 0 ?>;
		
		// 페이지 로드 시 소계 업데이트
		$(document).ready(function() {
			$('#total-ex-vat').text(totalSumExVat.toLocaleString());
			$('#total-inc-vat').text(totalSumIncVat.toLocaleString());
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

// ============================================
// 모바일 최적화 관련 함수들 (mobile_dev.md 패턴 적용)
// ============================================

/**
 * 현재 화면이 모바일인지 확인
 * @param {number} breakpoint - 모바일/PC 구분 기준 (기본값: 768)
 * @returns {boolean}
 */
function isMobile(breakpoint) {
	breakpoint = breakpoint || 768;
	return window.innerWidth <= breakpoint;
}

/**
 * 디바운스 함수 - 불필요한 함수 호출 방지
 * @param {Function} func - 실행할 함수
 * @param {number} wait - 대기 시간 (ms)
 * @returns {Function}
 */
function debounce(func, wait) {
	var timeout;
	return function executedFunction(...args) {
		var later = function() {
			clearTimeout(timeout);
			func(...args);
		};
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
	};
}

// 렌더링 플래그 (중복 렌더링 방지)
var isRenderingCards = false;
var processedTables = new Set(); // 처리된 테이블 추적

/**
 * 모바일에서 테이블을 카드 형식으로 변환하는 함수
 * mobile_dev.md 패턴 적용
 */
function renderMobileCards() {
	// 이미 렌더링 중이면 무시
	if (isRenderingCards) {
		return;
	}
	
	// 데스크톱에서는 모든 카드 컨테이너 제거
	if (!isMobile()) {
		var containers = document.querySelectorAll('.mobile-cards-container');
		containers.forEach(function(container) {
			container.remove();
		});
		processedTables.clear();
		return;
	}
	
	// 렌더링 시작 플래그 설정
	isRenderingCards = true;
	
	try {
		var table = document.getElementById('myTable');
		if (!table) {
			isRenderingCards = false;
			return;
		}
		
		// 테이블 ID 또는 고유 식별자 생성
		var tableId = table.id || 'myTable';
		
		// 이미 처리된 테이블인지 확인
		if (processedTables.has(tableId)) {
			// 기존 카드 컨테이너 업데이트
			var existingContainer = document.getElementById('mobileCardsContainer');
			if (existingContainer) {
				existingContainer.innerHTML = '';
			} else {
				isRenderingCards = false;
				return;
			}
		} else {
			processedTables.add(tableId);
		}
		
		var tbody = table.querySelector('tbody');
		if (!tbody) {
			isRenderingCards = false;
			return;
		}
		
		// DataTables의 현재 표시된 행만 가져오기
		var rows = [];
		if (typeof dataTable !== 'undefined' && dataTable) {
			// DataTables가 활성화된 경우 현재 페이지의 행만 가져오기
			var visibleRows = dataTable.rows({ page: 'current' }).nodes();
			rows = Array.from(visibleRows);
		} else {
			// DataTables가 없는 경우 모든 행 가져오기
			rows = Array.from(tbody.querySelectorAll('tr'));
		}
		
		var cardsContainer = document.getElementById('mobileCardsContainer');
		
		// 카드 컨테이너가 없으면 생성
		if (!cardsContainer) {
			cardsContainer = document.createElement('div');
			cardsContainer.id = 'mobileCardsContainer';
			cardsContainer.className = 'mobile-cards-container';
			table.parentElement.appendChild(cardsContainer);
		}
		
		cardsContainer.innerHTML = '';
		
		// 테이블 헤더 읽기 (라벨용)
		var headers = table.querySelectorAll('thead th');
		var labels = [];
		headers.forEach(function(header) {
			labels.push(header.textContent.trim());
		});
		
		// 기본 라벨 (헤더가 없는 경우 대비)
		if (labels.length === 0) {
			labels = ['번호', '견적일자', '수신', '구분', '현장명', '작성자', '합계(VAT별도)', '합계(VAT포함)'];
		}
		
		rows.forEach(function(row) {
			var cells = row.querySelectorAll('td');
			if (cells.length === 0) return;
			
			var card = document.createElement('div');
			card.className = 'card mobile-card mb-3';
			card.style.cssText = 'border: 1px solid #ddd; border-radius: 0.5rem; padding: 0.75rem; background: #f8f9fa; cursor: pointer; box-sizing: border-box; width: 100%; max-width: 100%;';
			
			// 클릭 이벤트 설정
			var dataId = row.getAttribute('data-id');
			if (dataId) {
				card.onclick = function() {
					redirectToView(dataId);
				};
			} else {
				// onclick 속성에서 함수 추출
				var onclickAttr = row.getAttribute('onclick');
				if (onclickAttr) {
					card.setAttribute('onclick', onclickAttr);
				}
			}
			
			cells.forEach(function(cell, index) {
				if (index < labels.length) {
					var cardItem = document.createElement('div');
					cardItem.className = 'd-flex align-items-center mb-2';
					cardItem.style.cssText = 'padding: 0.5rem 0; border-bottom: 1px solid #eee; word-wrap: break-word; overflow-wrap: break-word;';
					if (index === cells.length - 1) {
						cardItem.style.borderBottom = 'none';
					}
					
					var label = document.createElement('strong');
					var labelText = cell.getAttribute('data-label') || labels[index] || '';
					label.textContent = labelText + ': ';
					label.style.cssText = 'color: #007bff; margin-right: 0.5rem; flex-shrink: 0;';
					
					var value = document.createElement('span');
					value.textContent = cell.textContent.trim();
					value.style.cssText = 'word-wrap: break-word; overflow-wrap: break-word; flex: 1; min-width: 0;';
					
					cardItem.appendChild(label);
					cardItem.appendChild(value);
					card.appendChild(cardItem);
				}
			});
			
			cardsContainer.appendChild(card);
		});
		
		// tfoot 처리
		var tfoot = table.querySelector('tfoot');
		if (tfoot) {
			var tfootRow = tfoot.querySelector('tr');
			if (tfootRow) {
				var tfootCells = tfootRow.querySelectorAll('td');
				if (tfootCells.length > 0) {
					var summaryCard = document.createElement('div');
					summaryCard.className = 'card mobile-card-summary mt-3';
					summaryCard.style.cssText = 'border: 2px solid #0dcaf0; border-radius: 0.5rem; padding: 0.75rem; background: #d1ecf1; font-weight: bold; box-sizing: border-box; width: 100%; max-width: 100%;';
					
					tfootCells.forEach(function(cell, index) {
						var summaryItem = document.createElement('div');
						summaryItem.className = 'd-flex align-items-center mb-2';
						summaryItem.style.cssText = 'padding: 0.5rem 0; word-wrap: break-word; overflow-wrap: break-word;';
						
						var label = document.createElement('strong');
						label.style.cssText = 'color: #0dcaf0; margin-right: 0.5rem; flex-shrink: 0;';
						
						var labelText = cell.getAttribute('data-label') || '';
						if (!labelText) {
							if (index === 0) {
								labelText = '소계';
							} else if (index === 1) {
								labelText = '합계(VAT별도)';
							} else if (index === 2) {
								labelText = '합계(VAT포함)';
							}
						}
						
						label.textContent = labelText + ': ';
						
						var value = document.createElement('span');
						value.textContent = cell.textContent.trim();
						value.style.cssText = 'word-wrap: break-word; overflow-wrap: break-word; flex: 1; min-width: 0;';
						
						summaryItem.appendChild(label);
						summaryItem.appendChild(value);
						summaryCard.appendChild(summaryItem);
					});
					
					cardsContainer.appendChild(summaryCard);
				}
			}
		}
	} catch (error) {
		console.error('모바일 카드 렌더링 오류:', error);
	} finally {
		// 렌더링 완료 플래그 해제
		isRenderingCards = false;
	}
}

// 디바운스된 렌더링 함수
var debouncedRenderMobileCards = debounce(renderMobileCards, 300);

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
        "initComplete": function() {
			// 모바일에서 카드 형식으로 변환
			if (isMobile()) {
				setTimeout(function() {
					processedTables.clear(); // 테이블 처리 상태 초기화
					debouncedRenderMobileCards();
				}, 200);
			}
		},
		"drawCallback": function() {
			// 페이지 변경 시 모바일 카드 다시 렌더링
			if (isMobile()) {
				setTimeout(function() {
					debouncedRenderMobileCards();
				}, 200);
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
	
	// 창 크기 변경 시 모바일 카드 다시 렌더링 (디바운스 적용)
	$(window).on('resize', debounce(function() {
		if (isMobile()) {
			processedTables.clear(); // 테이블 처리 상태 초기화
			debouncedRenderMobileCards();
		} else {
			// 데스크톱으로 돌아가면 카드 컨테이너 제거
			var containers = document.querySelectorAll('.mobile-cards-container');
			containers.forEach(function(container) {
				container.remove();
			});
			processedTables.clear();
		}
	}, 300));
	
	// 초기 로드 시 모바일 카드 렌더링
	if (isMobile()) {
		setTimeout(function() {
			debouncedRenderMobileCards();
		}, 500);
	}
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
            // Trigger click event on search button
            if (isMobile()) {
                $("#searchBtnMobile").click();
            } else {
                $("#searchBtn").click();
            }
        }
    });	
});

$(document).ready(function() { 
	$("#writeBtn").click(function(){ 		
		var tablename = $("#tablename").val();			
		var url = "ET_write_form.php?tablename=" + tablename ; 
		customPopup(url, '등록', 1200, 950); 		
	 });	 
	// 검색 버튼 클릭 이벤트 (PC용과 모바일용 모두)
	function handleSearch() {
		// 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장

		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();
		document.getElementById('board_form').submit();
		
		// 모바일에서 카드 다시 렌더링
		if (isMobile()) {
			setTimeout(function() {
				processedTables.clear(); // 테이블 처리 상태 초기화
				debouncedRenderMobileCards();
			}, 500);
		}
	}
	
	$("#searchBtn, #searchBtnMobile").click(function() {
		handleSearch();
	});
}); 


function redirectToView(num) {    
    var tablename = $("#tablename").val();    	
	
    var url = "ET_write_form.php?mode=view&num=" + num         
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
