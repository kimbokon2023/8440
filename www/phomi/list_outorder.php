<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../common/functions.php';
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
 
$title_message = '포미스톤 출고 요청서';      
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
</style>   
</head>		 
<body>

<?php
$tablename = 'phomi_outorder';
 
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
    $fromdate = '2025-01-01';
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
$SettingDate = "out_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . " ORDER BY ";

$a = $common . " num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_outorder " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
if($mode=="search"){
	  if($search==""){
				$sql="select * from {$DB}.phomi_outorder " . $a; 										
			   }
		 elseif($search!="") { 			    
			  $sql ="select * from {$DB}.phomi_outorder where ((out_date like '%$search%')  or (customer like '%$search%' ) ";
			  $sql .="or (manager like '%$search%') or (address like '%$search%') or (contact like '%$search%') )  " . $Andis_deleted . " order by num desc  ";										 								
			}
	   }
if($mode=="") {
   $sql="select * from {$DB}.phomi_outorder " . $a; 						                         
}						            
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
?>

<form name="board_form" id="board_form"  method="post" action="list_outorder.php?mode=search">  
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
					<button type="button" class="btn btn-primary btn-sm mx-1" onclick="location.href='list_estimate.php';" title="견적 관리">
						<i class="bi bi-file-earmark-text"></i> 견적서로 이동
					</button>
					<!-- <button type="button" class="btn btn-success btn-sm mx-1" onclick="location.href='list_outorder.php';" title="출고요청서">
						<i class="bi bi-box-seam"></i> 출고요청서
					</button> -->
					<button type="button" class="btn btn-secondary btn-sm mx-1" onclick="location.href='unit_price.php';" title="단가표">
						<i class="bi bi-currency-dollar"></i> 단가표
					</button>						
				</div>	
				<div class="d-flex justify-content-center align-items-center"> 
					<div class="mx-2 pc-only-card" style="width: 100%; max-width: 650px;">
						<div class="card shadow border-0" style="background: linear-gradient(90deg, #ffdde1 0%, #ee9ca7 100%);">
							<div class="card-body p-3">
								<div class="d-flex align-items-center mb-2">
									<i class="bi bi-exclamation-triangle-fill text-danger fs-3 me-2"></i>
									<h5 class="mb-0 fw-bold text-danger">출고요청 안내</h5>
								</div>
								<ul class="mb-2 ps-4" style="font-size:1.05em;">
									<li>출고요청서는 <span class="fw-bold text-primary">수주리스트</span>에서 생성합니다.</li>
									<li>출고일 <span class="fw-bold text-danger">1~2일 전</span>에는 요청서 전달을 꼭 부탁드립니다.</li>
									<li>출고일자를 지정하고 저장하면, <span class="fw-bold text-success">수주리스트에 자동 적용</span>됩니다.</li>
									<li>요청서 전달 시 <span class="fw-bold text-primary">제품명, 규격</span>을 반드시 확인해 주세요.</li>
									<li>
										<span class="fw-bold text-dark">진행 순서:</span>
										<span class="badge bg-secondary mx-1">견적</span>
										<i class="bi bi-arrow-right"></i>
										<span class="badge bg-primary mx-1">수주</span>
										<i class="bi bi-arrow-right"></i>
										<span class="badge bg-success mx-1">출고요청서</span>
									</li>
								</ul>
								<div class="mt-2 p-2 rounded" style="background:rgba(255,255,255,0.7);">
									<i class="bi bi-info-circle text-info"></i>
									<span class="fw-bold">Code 안내:</span>
									<span class="badge bg-dark mx-1">A-</span>1200×2400
									<span class="badge bg-dark mx-1">B-</span>1200×2700(2800)
									<span class="badge bg-dark mx-1">C-</span>1200×3000
									<span class="badge bg-dark mx-1">Z-</span>1200×600
								</div>
							</div>
						</div>
					</div>
					<div class="card shadow-sm border-success mb-2" style="max-width: 600px;">
						<div class="card-header bg-success text-white d-flex align-items-center">
							<i class="bi bi-geo-alt-fill me-2"></i>
							<strong>창고 정보</strong>
						</div>
						<div class="card-body bg-light">
							<p class="mb-1">
								<i class="bi bi-geo-alt text-success"></i>
								<strong>주소:</strong> 경기도 군포시 번영로 82-27 N동(동관) 7층
							</p>
							<p class="mb-1">
								<i class="bi bi-person-badge text-primary"></i>
								<strong>출고담당자:</strong> 권대홍 대리
							</p>
							<p class="mb-0">
								<i class="bi bi-telephone text-info"></i>
								<strong>연락처:</strong> 010-4277-0858
							</p>
						</div>
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
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control w-auto mx-1" placeholder="출고일자, 발주처, 담당자, 주소, 연락처 검색..." > &nbsp;			
				<button class="btnClear" type="button"></button>
				<button type="button" id="searchBtnMobile" class="btn-search-icon">
					<i class="bi bi-search"></i>
				</button>
			</div>				
			<div id="autocomplete-list">
			</div>
			 &nbsp;												   			   
				<button type="button" id="searchBtn" class="btn btn-dark btn-sm pc-only-btn"> <i class="bi bi-search"></i>  </button>	&nbsp;&nbsp;
				<!-- <button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	    			  -->
		</div>
	</div>
  </div>	
<style>
th {
    white-space: nowrap;
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

	/* 검색 영역 - Grid 레이아웃 */
	.card-body .d-flex:has(#search) {
		display: grid !important;
		grid-template-columns: 1fr auto !important;
		gap: 8px !important;
		align-items: center !important;
	}

	/* 버튼 영역 줄바꿈 허용 */
	.d-flex.justify-content-center {
		flex-wrap: wrap !important;
		overflow-x: visible !important;
		gap: 0.4rem !important;
		justify-content: flex-start !important;
	}

	.d-flex.justify-content-center.align-items-center {
		justify-content: center !important;
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
		font-size: 0.85rem !important;
	}

	/* 라벨과 값 사이 콜론 */
	#myTable td:after {
		content: ':' !important;
		position: absolute !important;
		left: 32% !important;
		font-weight: bold !important;
		color: #9ca3af !important;
	}

	/* 첫 번째 셀 (번호) 숨김 */
	#myTable td:first-child {
		display: none !important;
	}

	#myTable td:first-child:after,
	#myTable td:first-child:before {
		display: none !important;
	}

	/* 출고일자 강조 */
	#myTable td:nth-child(2) {
		font-weight: 600 !important;
		color: #495057 !important;
		border-bottom: 1px solid #e9ecef !important;
		padding-bottom: 12px !important;
		margin-bottom: 8px !important;
	}

	/* 발주처 강조 - 가장 중요 */
	#myTable td:nth-child(3) {
		background: #e7f3ff !important;
		font-weight: 700 !important;
		font-size: 1.05rem !important;
		color: #0056b3 !important;
		padding: 14px 12px !important;
		padding-left: 12px !important;
		margin: 8px 0 !important;
		border-radius: 4px !important;
		border-left: 4px solid #0056b3 !important;
		display: block !important;
	}

	#myTable td:nth-child(3):before {
		position: static !important;
		display: block !important;
		width: 100% !important;
		margin-bottom: 6px !important;
		font-size: 0.85rem !important;
		color: #6b7280 !important;
		font-weight: 600 !important;
	}

	#myTable td:nth-child(3):after {
		display: none !important;
	}

	/* 담당자 강조 */
	#myTable td:nth-child(4) {
		font-weight: 600 !important;
		color: #059669 !important;
	}

	/* 모든 텍스트와 버튼이 카드 내부에 머물도록 */
	.card *,
	.container *,
	.container-fluid * {
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
	.card li {
		max-width: 100% !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		white-space: normal !important;
	}

	/* 안내 카드 최적화 */
	.card.shadow-sm {
		max-width: 100% !important;
		overflow-x: hidden !important;
	}

	.card.shadow-sm .card-body {
		padding: 0.75rem !important;
	}

	.card.shadow-sm ul {
		padding-left: 1.5rem !important;
		font-size: 0.9rem !important;
	}

	.card.shadow-sm li {
		margin-bottom: 0.5rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
	
	.card.shadow-sm .badge {
		font-size: 0.8rem !important;
		padding: 0.25rem 0.5rem !important;
		white-space: nowrap !important;
	}
}
</style>		  
<div class="card mb-2">
<div class="card-body">	  	  
   <div class="table-responsive"> 	
   <table class="table table-hover " id="myTable">
    <thead class="table-primary">
      <tr>
        <th class="text-start"  style="width:5%;">번호</th>
        <th class="text-start"  style="width:100px;">출고일자</th>		
        <th class="text-center text-primary" scope="col" style="width:100px;">발주처</th>		
        <th class="text-center text-success" scope="col" style="width:100px;">담당자</th>		
        <th class="text-center w300px" > 주소 </th>
        <th class="text-center w100px" > 받는 분 Tel</th>
        <th class="text-center w120px" > 배차</th>    
		<th class="text-center w120px" > 시공면적(㎡)</th>    
		<th class="text-center w120px" > 시공</th>    
		<th class="text-center w300px" > 비고</th>    
      </tr>
    </thead>	
    <tbody>
      <?php      
			$start_num = $total_row; // 페이지당 표시되는 첫번째 글순번      
			while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				$num = $row['num'];
				$out_date = $row['out_date'];
				$customer = $row['customer'];
				$manager = $row['manager'];
				$address = $row['address'];
				$contact = $row['recipient_phone']; // 받는 분 Tel
				$dispatch_type = $row['dispatch_type'];
				$area_sqm = $row['area_sqm'];
				$construction_done = $row['construction_done'];
				$createdAt = $row['createdAt'] ?? $row['creatAt'] ?? '';
				$updatedAt = $row['updatedAt'] ?? $row['updateAt'] ?? '';
				$note = $row['note'];

				
				// JSON 데이터 파싱
				$items = [];
				$total_quantity = 0;
				
				// 상품 데이터 파싱
				if(!empty($row['items'])) {
					$items = json_decode($row['items'], true) ?? [];
					foreach($items as $item) {
						$total_quantity += $item['quantity'] ?? 0;
					}
				}
				
				// 주소 길이 제한 (모바일 대응)
				$display_address = $address;
				// if (strlen($display_address) > 30) {
				// 	$display_address = substr($display_address, 0, 30) . '...';
				// }
					
				echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
				?>
					<td class="text-center"><?= $start_num ?></td>
					
					<td class="text-start" data-order="<?= $out_date ?>" data-label="출고일자"> <?=$out_date?> </td>	  
					<td class="text-start"
						data-order="<?= $customer ?>"
						data-label="발주처">
						<?= $customer ?>
					</td>  <!-- 발주처 -->
					<td class="text-center"
						data-order="<?= $manager ?>"
						data-label="담당자">
						<?= $manager ?>
					</td>  <!-- 담당자 -->
					<td class="text-start" title="<?= $address ?>" data-label="주소"> <?= $display_address ?> </td>          
					<td class="text-start text-primary" data-label="받는 분 Tel"><?= $contact ?></td>
					<td class="text-center" data-label="배차"><?= $dispatch_type ?></td>
					<td class="text-center" data-label="시공면적(㎡)"><?= $area_sqm ?></td>
					<td class="text-center" data-label="시공"><?= $construction_done ?></td>
					<td class="text-start" data-label="비고"><?= $note ?></td>
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
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
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

$(document).ready(function() { 
	$("#writeBtn").click(function(){ 		
		var tablename = $("#tablename").val();			
		var url = "OR_write_form.php?tablename=" + tablename ; 
		customPopup(url, '등록', 1200, 950); 		
	 });	 
	 
	// 검색 버튼 클릭 이벤트 (PC/모바일 공통)
	function performSearch() {
		// 페이지 번호를 1로 설정
		requestetcpageNumber = 1;
		setCookie('requestetcpageNumber', requestetcpageNumber, 10); // 쿠키에 페이지 번호 저장

		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();
		document.getElementById('board_form').submit();
	}
	
	$("#searchBtn").click(function() { 
		performSearch();
	});
	
	// 모바일 검색 버튼 클릭 이벤트
	$("#searchBtnMobile").click(function() {
		performSearch();
	});
	
	// Clear 버튼 클릭 이벤트
	$(".btnClear").click(function() {
		$("#search").val('');
		$("#search").focus();
	});
}); 


function redirectToView(num) {    
    var tablename = $("#tablename").val();    	
	
    var url = "OR_write_form.php?mode=view&num=" + num         
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
