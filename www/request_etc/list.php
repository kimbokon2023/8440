<?php
/**
 * request_etc 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/vendor/autoload.php';

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';	

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
$client->addScope(Google_Service_Drive::DRIVE);

// Google Drive 서비스 초기화
$service = new Google_Service_Drive($client);

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

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if ($level > 8) {
    $_SESSION["url"] = getBaseUrl() . '/request_etc/list.php';
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

$title_message = '부자재 구매';     
?> 
<?php include getDocumentRoot() . '/load_header.php'; ?> 
<title> <?=$title_message?>  </title>  
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
	#myTable td:nth-child(1),  /* 번호 */
	#myTable td:nth-child(3),  /* 납기 */
	#myTable td:nth-child(4),  /* 완료 */
	#myTable td:nth-child(5),  /* 진행상태 */
	#myTable td:nth-child(6),  /* 요청인 */
	#myTable td:nth-child(8),  /* 규격 */
	#myTable td:nth-child(10), /* 공급처 */
	#myTable td:nth-child(11)  /* 구매방법 */
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

	/* 접수일 (2번째) */
	#myTable td:nth-child(2) {
		font-weight: 600;
		color: #495057;
		border-bottom: 1px solid #e9ecef;
		padding-bottom: 4px !important;
		margin-bottom: 3px;
	}

	/* 구매 물품명 (7번째) - 현장명 역할, 가장 중요 */
	#myTable td:nth-child(7),
	#myTable td[data-label="구매 물품명"] {
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

	/* 구매 물품명에 일반 td 스타일이 적용되지 않도록 */
	#myTable td:nth-child(7),
	#myTable td[data-label="구매 물품명"] {
		padding-left: 4px !important;
	}

	/* 구매 물품명 라벨 스타일 */
	#myTable td:nth-child(7):before,
	#myTable td[data-label="구매 물품명"]:before {
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

	/* 수량 (9번째) */
	#myTable td:nth-child(9) {
		font-weight: 600;
		color: #dc2626;
		font-size: 1rem !important;
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
	.checktask,             /* 입고완료 제외/포함 버튼 */
	.date-row #dateRange,   /* 날짜 범위 드롭다운 */
	#rawmaterialBtn         /* 재고 버튼 */
	{
		display: none !important;
	}

	/* 모바일에서 신규 버튼은 표시 (직접 등록 가능) */
	#writeBtn {
		display: inline-block !important;
		visibility: visible !important;
		opacity: 1 !important;
	}

	/* 검색 버튼 표시 */
	#searchBtn {
		display: inline-block !important;
	}
}
</style>   
</head>		 
<body>
<?php
$tablename = 'request_etc';
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
    .blink {
        animation: blink 1s linear infinite;
    }

    #floatingCartBtn {
        position: absolute;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    </style>';
}
 
include "_request.php"; 
$find = $_REQUEST["find"] ?? '';  // 목록표에 제목,이름 등 나오는 부분
	  
$pdo = db_connect();


// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 6개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
 
$SettingDate="registdate";

$Andis_deleted =  " AND (is_deleted IS NULL or is_deleted='0')  AND eworks_item='부자재구매' ";
$Whereis_deleted =  " Where  (is_deleted IS NULL or is_deleted='0')  AND eworks_item='부자재구매' ";	

$common="   where " . $SettingDate . " between date_format('$fromdate', '%Y-%m-%d 00:00:00') and date_format('$Transtodate', '%Y-%m-%d 23:59:59') " . $Andis_deleted . " order by " ;
$a= $common . " num desc ";    //내림차순 전체
  
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum=array();
$num_arr = array();
$item_arr = array();
$supplier_arr = array();
$company_arr = array();

$sql = "select * from {$DB}.eworks " . $a; 	
 
 $recount = 0 ;
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

		$num=$row["num"];			  
		include '_row.php';		
		 
		$tmp=$steel_item . $spec;			  
		$num_arr[$recount] = $num;
		$item_arr[$recount] = $steel_item;
		$supplier_arr[$recount] = $supplier;
		$company_arr[$recount] = $company;
		$recount++;	
		
		for($i=1;$i<=$rowNum;$i++) {			
			  $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='1' and $tmp==$sum_title[$i])
					$sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
				// $sum[$i]=(float)-1;				
				   }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

$sql="select * from mirae8440.eworks " . $a; 	 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			   include '_row.php';
			  
			  $tmp=$steel_item . $spec;
	
        for($i=1;$i<=$rowNum;$i++) {
			 			  
 			  
	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='2' and $tmp==$sum_title[$i])
				    $sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  



// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
  if($mode=="search"){
		  if($search==""){			  
			    if($done_check_val=='1')  // 입고완료제외인 경우
				     {
								$common="   where ( " . $SettingDate . " between date('$fromdate') and date('$Transtodate') ) and which<>'3' " . $Andis_deleted . "  order by " ;
								$a = $common . " num desc ";    //내림차순 전체							 
							 $sql="select * from mirae8440.eworks " . $a; 	
					 }
					else
							{
									 $sql="select * from mirae8440.eworks " . $a; 					
							}						
					
			       }
             elseif($search!="") { 
			    if($done_check_val=='1')  // 입고완료제외인 경우			 
			          	{			 
							  $sql ="select * from mirae8440.eworks where ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
							  $sql .="or (steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (first_writer like '%$search%') or (supplier like '%$search%')  or (payment like '%$search%')  or (request_comment like '%$search%')) and (which<>'3')  " . $Andis_deleted . "  order by num desc ";
						}
								else
								{
										  $sql ="select * from mirae8440.eworks where ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
										  $sql .="or (steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%')  or (first_writer like '%$search%') or (payment like '%$search%')   or (supplier like '%$search%') or (request_comment like '%$search%') )  " . $Andis_deleted . " order by num desc  ";										 
								}				
						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.eworks " . $a; 						                         
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   

$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;


 // print $sql;	
 
 // // search 모드가 아닐때는 100개로 데이터 제한
// if($mode!=='search')  
	// $sql .= ' limit 0, 50';

 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
 			  $outdate=$row["outdate"];			  
			  
			  include '_row.php';

			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
   
	 try{
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}

?>

<form name="board_form" id="board_form"  method="post" action="list.php?mode=search">    
			<input type="hidden" id="done_check_val" name="done_check_val" value="<?=$done_check_val?>" >
			<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
			<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
			<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 				
			<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
			<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
			<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
			<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
			<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
			<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
			<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
			<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
			<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
			<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
			<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
			<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 
			<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >	
						
<div class="container-fluid">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
					<h4> <?=$title_message?> </h4>  
				</div>	
			<!-- 날짜 행 -->
			<div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center date-row">
				<?php
					if($done_check_val==='0')   
						print '<button class="btn btn-dark  btn-sm  checktask " type="button"   > 입고완료 제외 </button>  &nbsp;&nbsp;';
					else
						print '<button class="btn btn-outline-dark  btn-sm  checktask " type="button"   > 입고완료 포함 </button>  &nbsp;&nbsp;';	
					print '<span > ▷ ' .  $total_row . '&nbsp;&nbsp; </span> ' ;
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
							<button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='three_month_ago()' > M-3월 </button>
							<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='prepre_month()' > 전전월 </button>	
							<button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='pre_month()' > 전월 </button> 						
							<button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange "  onclick='this_today()' > 오늘 </button>
							<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_month()' > 당월 </button>
							<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_year()' > 당해년도 </button> 
						</div>
					</div>
				</div>		
				<input type="date" id="fromdate" name="fromdate" size="12"  class="form-control"   style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일">
				<span class="date-separator">~</span>
				<input type="date" id="todate" name="todate" size="12"   class="form-control"   style="width:100px;" value="<?=$todate?>" placeholder="기간 끝">
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
				<div class="inputWrap">
					<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control" style="width:150px;" >
					<button class="btnClear"></button>
				</div>				
				<div id="autocomplete-list">
				</div>
				<button type="button" id="searchBtn" class="btn btn-dark  btn-sm mx-1 "  > <i class="bi bi-search"></i>  </button>
				<button type="button" class="btn btn-dark  btn-sm mx-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	     
				<button  type="button" id="rawmaterialBtn"  class="btn btn-dark btn-sm mx-1" > <i class="bi bi-list"></i> 재고 </button>
				<button type="button" class="btn btn-primary btn-sm mx-1" onclick="addToCart()">
					<i class="bi bi-cart-plus"></i> 구매카트 담기
				</button>
			</div>
		</div>
      </div>	
	<style>
	th {
		white-space: nowrap;
	}
	/* 체크박스 스타일 */
	.row-checkbox {
		cursor: pointer;
	}
	</style>		  
<!-- 발주서 작성 모달 -->
<div class="modal fade" id="orderWriteModal" tabindex="-1" aria-labelledby="orderWriteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 95%; height: 95%;">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="orderWriteModalLabel">
                    <i class="bi bi-cart-check"></i> 구매카트에서 발주서 작성
                </h5>
                <div>
                    <button type="button" class="btn btn-success btn-sm me-1" onclick="iframeSaveOrderFromCart()">저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="iframeCancelOrderFromCart()">취소</button>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="height: calc(100% - 50px); overflow: hidden;">
                <iframe id="orderWriteIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>		  
<div class="card mb-2">
<div class="card-body">	  	  
   <div class="table-responsive"> 	
   <table class="table table-hover " id="myTable">
    <thead class="table-primary">
      <tr>
        <th class="text-center" scope="col" style="width:40px;">
            <input type="checkbox" id="checkAll" onclick="toggleAll(this)">
        </th>
        <th class="text-center" scope="col" style="width:4%;">번호</th>
        <th class="text-center" scope="col" style="width:80px;">접수 </th>
		<th class=" text-center" style="width:4%;">  납기</th>   
		<th class=" text-center" style="width:4%;">  완료 </th>     <!-- 완료일 -->		
        <th class="text-center" scope="col"> 진행상태 </th>
        <th class="text-center" scope="col"> 구매카트 </th>
        <th class="text-center" scope="col"> 요청인</th>
        <th class="text-center" scope="col"> 구매 물품명</th>
        <th class="text-center" scope="col"> 규격</th>        
        <th class="text-center" scope="col"> 수량</th>
        <th class="text-center" scope="col"> 공급처</th>
        <th class="text-center" scope="col"> 구매방법</th>
        <th class="text-center" scope="col"> 비고</th>
      </tr>
    </thead>
	
    <tbody>
      <?php
      if ($page <= 1)
        $start_num = $total_row; // 페이지당 표시되는 첫번째 글순번
      else
        $start_num = $total_row - ($page - 1) * $scale;
	//  print $sql;
      try {
      while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        include '_row.php';
		
        // if ($registdate != "") {
          // $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
          // $registdate = $registdate . $week[date('w', strtotime($registdate))];
        // }
		
		echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
      ?>
          <td class="text-center" onclick="event.stopPropagation()">
              <input type="checkbox" class="row-checkbox" value="<?= $num ?>">
          </td>
		  <td class="text-center" data-label="번호"><?= $start_num ?></td>
            <?php				
				// DateTime 객체 생성
				$dateTime1 = new DateTime($registdate);
				// 날짜 형식을 YYYY-MM-DD로 변환
				$formattedDate = $dateTime1->format('Y-m-d');
				
				$formattedDate_outdate ='';
				if(isNotNull($outdate))
				{
					$dateTime2 = new DateTime($outdate);
					// 날짜 형식을 YYYY-MM-DD로 변환
					$formattedDate_outdate = $dateTime2->format('m-d');
				}
				$formattedDate_indate ='';
				if(isNotNull($indate))
				{
					$dateTime3 = new DateTime($indate);
					// 날짜 형식을 YYYY-MM-DD로 변환
					$formattedDate_indate = $dateTime3->format('m-d');
				}
		  ?>

		<td class="text-center" data-order="<?= $registdate ?>" data-label="접수">
			<?=$formattedDate?>
		</td>

		<td class="text-center" data-order="<?= $outdate ?>" data-label="납기">
		<?= $formattedDate_outdate ?>
		</td>          

		<td class="text-center" data-order="<?= $indate ?>" data-label="완료">
		<?= $formattedDate_indate ?>
		</td>
			<?php
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
				break;
			}			
			?>
			
		  <td class="text-center <?= $font_state ?>" data-label="진행상태"><?= $tmp_word ?></td>
		  
          <td class="text-center" data-label="구매카트">
              <?php if (isset($cart) && $cart == 1): ?>
                  <span class="badge bg-primary"><i class="bi bi-cart-check"></i> 담김</span>
              <?php endif; ?>
          </td>

          <td class="text-center" data-label="요청인">
		   <?php
				$pattern = "/^[가-힣]+/";
				
				preg_match($pattern, $first_writer, $matches);
				$tmpStr = $matches[0]; // '이미래' 출력
		    ?>			
          <?= $tmpStr ?> </td>
          <td class="text-center color-brown" data-label="구매 물품명"><?= $outworkplace ?></td>
          <td class="text-center color-brown" data-label="규격"><?= $spec ?></td>
		  <!-- <td class="text-center thumbnail-cell"></td> <!--  이미지 썸네일 -->
          <td class="text-center color-red" data-label="수량"><?= $steelnum ?></td>
          <td class="text-center" data-label="공급처"><?= $supplier ?></td>
		<td class="text-center" data-label="구매방법">
		<?php
			if ($payment == '법인카드') {
			echo '<span class="text-primary">법인카드</span>';
			} elseif ($payment == '세금계산서') {
			echo '<span class="text-danger">세금계산서</span>';
			}
			?>
		</td>
          <td data-label="비고"><?= $request_comment ?></td>
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
   

<!-- ajax 전송으로 DB 수정 -->
<? include "../formload.php"; ?>	
   
<div class="container-fluid">
<? include '../footer_sub.php'; ?>
</div>

<!-- Floating Cart Button -->
<button id="floatingCartBtn" class="btn btn-primary btn-sm" onclick="triggerAddToCart()">
    <i class="fas fa-cart-plus"></i> 담기
</button>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var requestetcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "order": [], // 초기 정렬 비활성화 (서버 정렬 사용)
        "columnDefs": [
            { "orderable": false, "targets": 0 } // 체크박스 컬럼 정렬 비활성화
        ],
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
	
	// 자재현황 클릭시
	$("#rawmaterialBtn").click(function(){ 			
		 popupCenter('/ceiling/list_part_table.php?menu=no'  , '부자재현황보기', 1050, 950);	
	});	

});


$(document).ready(function() { 

// 입고완료 처리부분 버튼설계
	$(".checktask").click(function() {	  
      // 체크박스가 선택되어 있으면 페이지 리로드	  
      $("#sortof").val('');
      $("#cursort").val('');
      
	  var check = $("#done_check_val").val();		 
	  
	  if(Number(check) === 1)
		$("#done_check_val").val('0');		 
	    else
			$("#done_check_val").val('1');		 
		
	  $("#board_form").submit();
  });

	$("#writeBtn").click(function(){ 		
		var tablename = $("#tablename").val();			
		var url = "write_form.php?tablename=" + tablename ; 

		customPopup(url, '부자재 구매 등록', 1400, 800); 		
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

function redirectToView(num) {    
    var tablename = $("#tablename").val();
    	
    var url = "view.php?mode=view&num=" + num         
        + "&tablename=" + tablename;   
		
	customPopup(url, '원자재 구매', 1200, 800); 	    
}

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('부자재 구매'); // 다른 페이지에 맞는 menuName을 전달
});

// 전체 선택/해제
function toggleAll(source) {
    var checkboxes = document.querySelectorAll('.row-checkbox');
    for(var i=0; i<checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}

// Floating Cart Button Logic
$(document).on('change', '.row-checkbox', function(e) {
    var floatingBtn = $('#floatingCartBtn');
    
    if ($(this).is(':checked')) {
        // Show button near the checkbox
        var rect = this.getBoundingClientRect();
        var scrollTop = $(window).scrollTop();
        var scrollLeft = $(window).scrollLeft();

        // Ensure button is visible and positioned correctly
        floatingBtn.css({
            'top': (rect.top + scrollTop - 10) + 'px',
            'left': (rect.left + scrollLeft + 30) + 'px',
            'display': 'block',
            'position': 'absolute',
            'z-index': 9999
        });
        
        // Append to body to avoid overflow issues if table container has overflow:hidden
        $('body').append(floatingBtn);
        
    } else {
        // Check if any other checkboxes are checked
        if ($('.row-checkbox:checked').length === 0) {
            floatingBtn.hide();
        }
    }
});

// Hide button when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('.row-checkbox').length && !$(e.target).closest('#floatingCartBtn').length) {
        // $('#floatingCartBtn').hide(); // Optional
    }
});

// Trigger Add to Cart from Floating Button
window.triggerAddToCart = function() {
    addToCart();
};

// 구매카트 담기 (모달 열기)
function addToCart() {
    var checkboxes = document.querySelectorAll('.row-checkbox:checked');
    var nums = [];
    
    if(checkboxes.length === 0) {
        Swal.fire('알림', '선택된 항목이 없습니다.', 'warning');
        return;
    }

    checkboxes.forEach(function(checkbox) {
        nums.push(checkbox.value);
    });

    // 모달 열기 (발주서 작성 화면)
    openOrderWriteModal(nums);
}

// 발주서 작성 모달 열기
function openOrderWriteModal(itemNums) {
    var modalElement = document.getElementById('orderWriteModal');
    if (!modalElement) {
        console.error('orderWriteModal 요소를 찾을 수 없습니다.');
        return;
    }

    var orderWriteModal = new bootstrap.Modal(modalElement, {
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
                Swal.fire('성공', message.payload.message || '발주서가 저장되었습니다.', 'success').then(() => {
                    var modalElement = document.getElementById('orderWriteModal');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                    location.reload();
                });
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
</script> 
</body>
</html>