<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';

$title_message = '본천장&조명천장 출고증 일괄';

// 권한 체크 (level 5 이하만 접근 가능)
if (!isset($_SESSION["level"]) || $level > 8) {
    // 세션에 원래 URL 저장
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION["url"] = $current_url;
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}
  
  // 첫 화면 표시 문구
 $title_message = '장비 (주간,정기) 점검표';     
   
 ?>
   
<?php include includePath('load_header.php') ?>
 
<title> <?=$title_message?> </title>

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
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
            max-width: 100% !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 제목 최적화 */
        h3.spantitle {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            width: 100% !important;
        }
        
        /* d-flex 요소 최적화 */
        .d-flex {
            flex-wrap: wrap !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        .d-flex.justify-content-start,
        .d-flex.justify-content-end,
        .d-flex.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        /* 선택 박스 및 버튼 최적화 */
        select.form-control,
        .form-control {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            font-size: 1rem !important;
            box-sizing: border-box !important;
        }
        
        .badge {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 버튼 최적화 */
        .btn {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 디버그 버튼은 너비 자동 */
        #toggleDebugBtn {
            width: auto !important;
            max-width: fit-content !important;
            flex-shrink: 0 !important;
        }
        
        /* 제목과 디버그 버튼을 포함한 컨테이너는 가로 방향 유지 */
        .d-flex.justify-content-between.align-items-center {
            flex-direction: row !important;
            align-items: center !important;
        }
        
        .spantitle {
            flex: 1 !important;
            margin: 0 !important;
        }
        
        .btn-sm {
            font-size: 0.875rem !important;
            padding: 0.5rem !important;
        }
        
        /* 테이블을 카드 형식으로 변환 */
        #myTable_wrapper {
            display: none !important;
        }
        
        #myTable {
            display: none !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-cards-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin: 0.5rem 0;
            padding: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: calc(100% - 1rem) !important;
            max-width: calc(100% - 1rem) !important;
            margin-left: auto !important;
            margin-right: auto !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .mobile-card-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .mobile-card-item:last-child {
            border-bottom: none;
        }
        
        .mobile-card-label {
            font-weight: bold;
            font-size: 0.875rem;
            color: #495057;
            margin-bottom: 0.25rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-value {
            font-size: 0.875rem;
            color: #212529;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
        }
        
        /* jQuery DataTables 컨트롤 숨기기 */
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
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
            display: block !important;
            overflow: visible !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
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
        
        .modal-body {
            padding: 0.75rem 0.5rem !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            flex: 1 1 auto !important;
            -webkit-overflow-scrolling: touch !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
        }
        
        .modal-footer .btn {
            width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        /* SweetAlert2 모달 최적화 */
        .swal2-popup {
            width: 90% !important;
            max-width: 90% !important;
            padding: 1rem !important;
            font-size: 0.875rem !important;
        }
        
        .swal2-title {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-content {
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-actions {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .swal2-confirm,
        .swal2-cancel {
            width: 100% !important;
            margin: 0 !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 정보 텍스트 최적화 */
        .d-flex.align-items-center.mt-3.mb-1 {
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
    }
    
    /* PC 환경 최적화 */
    @media (min-width: 769px) {
        .d-flex.justify-content-start .btn,
        .d-flex.justify-content-end .btn,
        .d-flex.align-items-center .btn {
            margin-left: 0.25rem !important;
            margin-right: 0.25rem !important;
        }
        
        #mobile-cards-container {
            display: none !important;
        }
    }
</style>

 </head> 
 
<body>

<?php include includePath("common/modal.php"); ?>

<?php
 // 모바일이면 특정 CSS 적용 (기존 스타일은 위의 미디어 쿼리로 대체)
// 기존 폰트 크기는 너무 커서 제거하고 미디어 쿼리에서 적절한 크기로 설정

require_once(includePath('lib/mydb.php'));
$pdo = db_connect(); 

// 배열로 장비점검리스트 불러옴
$load_db_path = includePath("qc/load_DB.php");
if (!file_exists($load_db_path)) {
    die("ERROR: load_DB.php 파일을 찾을 수 없습니다: " . $load_db_path);
}
include $load_db_path;

// 디버깅 모드 체크 (URL 파라미터 debug=1로 활성화)
$debug_mode = isset($_GET['debug']) && $_GET['debug'] == '1';

// 디버깅: 배열이 제대로 로드되었는지 화면에 표시
$debug_output = '';
if ($debug_mode) {
    $debug_output .= '<div id="debug-info" style="background: #f0f0f0; padding: 10px; margin: 10px 0; border: 2px solid #333;">';
    $debug_output .= '<h4>🔍 load_DB.php 디버깅 정보</h4>';
    $debug_output .= '<p><strong>load_DB.php 경로:</strong> ' . htmlspecialchars($load_db_path) . '</p>';
    $debug_output .= '<p><strong>파일 존재:</strong> ' . (file_exists($load_db_path) ? '✅ Yes' : '❌ No') . '</p>';

    if (isset($mcno_arr)) {
        $debug_output .= '<p><strong>mcno_arr 개수:</strong> ' . count($mcno_arr) . '</p>';
        $debug_output .= '<p><strong>mcno_arr 내용:</strong> ' . htmlspecialchars(implode(', ', $mcno_arr)) . '</p>';
    } else {
        $debug_output .= '<p><strong>mcno_arr:</strong> ❌ 정의되지 않음</p>';
    }

    if (isset($mcname_arr)) {
        $debug_output .= '<p><strong>mcname_arr 개수:</strong> ' . count($mcname_arr) . '</p>';
        $debug_output .= '<p><strong>mcname_arr 내용:</strong> ' . htmlspecialchars(implode(', ', $mcname_arr)) . '</p>';
    } else {
        $debug_output .= '<p><strong>mcname_arr:</strong> ❌ 정의되지 않음</p>';
    }

    if (isset($mcmain_arr)) {
        $debug_output .= '<p><strong>mcmain_arr 개수:</strong> ' . count($mcmain_arr) . '</p>';
    } else {
        $debug_output .= '<p><strong>mcmain_arr:</strong> ❌ 정의되지 않음</p>';
    }

    if (isset($mcsub_arr)) {
        $debug_output .= '<p><strong>mcsub_arr 개수:</strong> ' . count($mcsub_arr) . '</p>';
    } else {
        $debug_output .= '<p><strong>mcsub_arr:</strong> ❌ 정의되지 않음</p>';
    }

    $debug_output .= '<p><strong>현재 mcno 파라미터:</strong> ' . htmlspecialchars($mcno ?? 'null') . '</p>';
    $debug_output .= '<p><strong>현재 selnum 파라미터:</strong> ' . htmlspecialchars($selnum ?? 'null') . '</p>';
    $debug_output .= '</div>';
}

// 디버깅: 배열이 제대로 로드되지 않았을 경우 초기화
if (!isset($mcno_arr) || empty($mcno_arr)) {
    if ($debug_mode) {
        error_log("laser.php: mcno_arr is empty or not set after load_DB.php");
    }
    // 임시로 배열 초기화
    $mcno_arr = array();
    $mcname_arr = array();
    $mcmain_arr = array();
    $mcsub_arr = array();
}   

 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	  
  }
 
if(isset($_REQUEST["scale"])) // $_REQUEST["scale"]값이 없을 때에는 20로 지정 
 {
    $scale=$_REQUEST["scale"];  // 페이지 번호
 }
  else
  {
    $scale=10;	   // 한 페이지에 보여질 게시글 수
  }   

  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // List에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"] ?? '';
  else 
     $mode="";     
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"] ?? '';
$todate=$_REQUEST["todate"] ?? '';

if($fromdate=="")	$fromdate="2010-01-01";

if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}
		  
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];

$a= " order by checkdate desc ";    //내림차순
$b= " order by checkdate desc ";    //내림차순 전체

 isset($_REQUEST["mcno"])  ? $mcno=$_REQUEST["mcno"] :   $mcno=$_REQUEST["mcname"]; 
 isset($_REQUEST["selnum"])  ? $selnum=$_REQUEST["selnum"] :   $selnum=1; 

// var_dump($mcno);
// var_dump($selnum);
 
if(intval($selnum)==1)
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' " . $a; 						
}  
 
if(intval($selnum)==2)
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='주간' " . $a; 						
}  
if(intval($selnum)==3) 
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='1개월' " . $a; 						
}  
if(intval($selnum)==4) 
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='2개월' " . $a; 						
}  
if(intval($selnum)==5) 
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='6개월' " . $a; 						
}  
// 미점검 리스트
if(intval($selnum)==6) 
{	     
	 // var_dump($mcno_arr[1]);
	 // var_dump($questionstep_arr);
	// check1~check10까지 해당 질의 수를 추출해서 sql문장을 만들어 보자
	for($j=0;$j<count($mcno_arr);$j++)
	 {
	  if($mcno==$mcno_arr[$j])
	  {  
		  $arrtmp = explode(",", $questionstep_arr[$j]);  // 이 함수 조심해야 함. 배열은 인식못함		  
	  }
	 }
		  
  $period = array();
  array_push($period,"주간","1개월","2개월","6개월");
  
  // var_dump($period);
  
   $sqladd = "";
  
  for($k=0; $k<count($period); $k++)   // 주간/1개월/2개월/6개월  4개항목 반복문
	{
	  $sqladd .=" (item='" . $mcno . "' and term = '" . $period[$k] . "' ) and ( ";  // 공통적으로 주간/1개월/2개월/6개월 주기
	  for($j=1; $j<=(int)$arrtmp[$k]; $j++)
	  {
		  if($j!=1)
			  $sqladd .= " or " ;    
		  $sqladd .= "   check" . $j . " is null  ";  // 공통적으로 주간/1개월/2개월/6개월 주기 
	  }
	   if($k==count($period)-1)
	        $sqladd .= "  ) " ; 
		else
			$sqladd .= "  ) or " ; 
	}	  
		$sql="select * from mirae8440.mymclist where " . $sqladd . $a; 							
		
}  
		 
// 점검 리스트
if(intval($selnum)==7) 
{	     
	 // var_dump($mcno_arr[1]);
	 // var_dump($questionstep_arr);
	// check1~check10까지 해당 질의 수를 추출해서 sql문장을 만들어 보자
	for($j=0;$j<count($mcno_arr);$j++)
	 {
	  if($mcno==$mcno_arr[$j])
	  {  
		  $arrtmp = explode(",", $questionstep_arr[$j]);  // 이 함수 조심해야 함. 배열은 인식못함		  
	  }
	 }
	 
	 // var_dump($arrtmp);	  
	 // var_dump('배열점검');
	 
	  $sqladd=" and term = '주간' ) and ( ";  // 공통적으로 주간/1개월/2개월/6개월 주기
	  
	  for($j=1; $j<=(int)$arrtmp[0]; $j++)
	  {
		  if($j!=1)
			  $sqladd .= " and " ;    // 점검완료는 and 조건
		  $sqladd .= "   check" . $j . " is not null  ";  // 공통적으로 주간/1개월/2개월/6개월 주기  not null로 변경
	  }
	    $sqladd .= "  ) " ; 
	  
		$sql="select * from mirae8440.mymclist where (item='" . $mcno . "'" . $sqladd . $a; 					
		
  $period = array();
  array_push($period,"주간","1개월","2개월","6개월");
  
  // var_dump($period);
  
   $sqladd = "";
  
  for($k=0; $k<count($period); $k++)   // 주간/1개월/2개월/6개월  4개항목 반복문
	{
	  $sqladd .=" (item='" . $mcno . "' and term = '" . $period[$k] . "' ) and ( ";  // 공통적으로 주간/1개월/2개월/6개월 주기
	  for($j=1; $j<=(int)$arrtmp[$k]; $j++)
	  {
		  if($j!=1)
			   $sqladd .= " and " ;    // 점검완료는 and 조건
		  $sqladd .= "   check" . $j . " is not null  ";  // 공통적으로 주간/1개월/2개월/6개월 주기  not null로 변경 두가지 검색조건 6,7번 다른점
	  }
	   if($k==count($period)-1)
	        $sqladd .= "  ) " ; 
		else
			$sqladd .= "  ) or " ; 
	}
	  
		$sql="select * from mirae8440.mymclist where " . $sqladd . $a; 							
		
	// print $sql;  

}  
			
 // print $sql;  			
 
$nowday=date("Y-m-d");   // 현재일자 변수지정   
   
// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    		
			 
 ?>
		 
<form name="board_form" id="board_form"  method="post" action="laser.php" >    
		
	<input type="hidden" id="alerts" name="alerts" value="<?=$alerts?>" size="3" > 	
	<input type="hidden" id="selnum" name="selnum" value="<?=$selnum?>"  > 	
	<input type="hidden" id="mcmain" name="mcmain" value="<?=$mcmain?>"  > 	
	<input type="hidden" id="mcsub" name="mcsub" value="<?=$mcsub?>"  > 					
  
<?php if($chkMobile) { ?>	
<div class="container-fluid mt-2 mb-2"  >
<?php } if(!$chkMobile) { ?>	
<div class="container mt-2 mb-2"  >   
<?php  } ?>		

<div class="card mt-2 mb-4">  
<div class="card-body">
 <?php echo $debug_output; ?>
 <div class="d-flex mt-3 mb-1 justify-content-between align-items-center">  
   <h3 class="spantitle mb-0">
		장비 점검
	</h3>
	<?php if (isset($_SESSION["level"]) && $_SESSION["level"] <= 3) : ?>
	<button type="button" class="btn btn-sm <?php echo $debug_mode ? 'btn-warning' : 'btn-outline-secondary'; ?>" id="toggleDebugBtn" onclick="toggleDebug()" style="flex-shrink: 0;">
		<i class="fas fa-bug"></i> <?php echo $debug_mode ? '디버그 ON' : '디버그'; ?>
	</button>
	<?php endif; ?>
 </div>
 <div class="d-flex mt-3 mb-1 justify-content-end">  
<?php
    // 모바일 환경에서는 한 줄로, PC에서는 기존 방식 유지
    if(!isset($_SESSION["userid"])) {
        if ($chkMobile) {
            // 모바일: 세로 한 줄 배치
            ?>
            <div class="d-flex flex-column align-items-start">
                <a href="../login/login_form.php">로그인</a>
                <a href="../member/insertForm.php">회원가입</a>
            </div>
            <?php
        } else {
            // PC: 기존 가로 배치
            ?>
            <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
            <?php
        }
    } else {
        if ($chkMobile) {
            // 모바일: 세로 한 줄 배치
            ?>
            <div class="d-flex flex-column align-items-start">
                <span><?=$_SESSION["name"]?></span>
                <a href="../login/logout.php">로그아웃</a>
                <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
            </div>
            <?php
        } else {
            // PC: 기존 가로 배치
            ?>
            <?=$_SESSION["name"]?> | 
            <a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
            <?php
        }
    }
?>

</div>   
	  
	<div class="d-flex align-items-center mt-4 mb-3 justify-content-start">  
   				<?php if($chkMobile) { ?>
						<select class="form-control me-2" name="mcno" id="mcno" style="width:25%;" >		
				<?php }  else { ?>		
						<select class="form-control me-2" name="mcno" id="mcno" style="width:12%;" >		  
				<?php } ?>		
		   <?php
		   $arr_count = count($mcno_arr);
		   
		   if ($arr_count == 0) {
		       // 배열이 비어있으면 기본 옵션 표시
		       echo '<option value="">장비를 선택하세요</option>';
		   } else {
		       for($i = 0; $i < $arr_count; $i++) {
		           $mcno_value = htmlspecialchars($mcno_arr[$i], ENT_QUOTES, 'UTF-8');
		           if($mcno == $mcno_arr[$i])
		               echo "<option selected value='" . $mcno_value . "'>" . $mcno_value . "</option>";
		           else   
		               echo "<option value='" . $mcno_value . "'>" . $mcno_value . "</option>";
		       }
		   }
		   ?>      
		</select>						 
   				<?php if($chkMobile) { ?>
						<span class="badge bg-secondary form-control me-2 fs-5" style="width:35%;border:0px;"  readonly>		
				<?php }  else { ?>		
						<span class="badge bg-secondary form-control me-2 fs-5" style="width:20%;border:0px;" readonly>		  
				<?php } ?>						
				 (정) <?=$mcmain?>, (부) <?=$mcsub?> 
			</span>			
			<button type="button" class="btn btn-danger btn-sm me-2" onclick="show_list(6);"> 미점검 </button>
			<button type="button" class="btn btn-success btn-sm me-2" onclick="show_list(7);"> 점검완료 </button>
	</div> 

	<div class="d-flex align-items-center mt-3 mb-1 justify-content-start">    
				<button type="button" id="closeBtn" class="btn btn-outline-dark  btn-sm "  >    창닫기 </button>	
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-outline-dark btn-sm " onclick="show_list(1);"> 전체  </button>&nbsp;
				<button type="button" class="btn btn-outline-success  btn-sm  "  onclick="show_list(2);"> 주간 </button>&nbsp;
				<button type="button" class="btn btn-outline-danger  btn-sm  "  onclick="show_list(3);"> 1개월 </button> &nbsp;				  
				<button type="button" class="btn btn-outline-secondary  btn-sm  "  onclick="show_list(4);"> 2개월  </button>&nbsp;
				<button type="button" class="btn btn-outline-dark  btn-sm  "       onclick="show_list(5);"> 6개월 </button>
	</div>	 
	<div class="d-flex align-items-center mt-3 mb-1 justify-content-start">  	   
	     (주간 점검) 매주 금요일 작업종료 30분전, <br> (월간 점검) 매월 넷째주 금요일 작업종료 30분전,  <br>
		 (2개월 점검) 짝수달 넷째주 금요일 작업종료 30분전 , <br> (6개월 점검) 6월,12월 넷째주 금요일 작업종료 30분전  <br>       
      </div> 
	<div class="d-flex align-items-center mt-3 mb-1 justify-content-start"> 
             ▷  <?= $total_row ?> 개 &nbsp;&nbsp;	&nbsp;&nbsp;    	
    </div> 

	<div class="row d-flex"  >
		<!-- 모바일 카드 컨테이너 -->
		<div id="mobile-cards-container"></div>
		
		<table class="table table-hover" id="myTable">
			<thead class="table-primary" >
				<tr>
					 <th class="text-center" data-label="점검일"> 점검일   </th>
					 <th class="text-center" data-label="주간점검"> 주간점검  </th>
					 <th class="text-center" data-label="1개월점검"> 1개월점검 </th>
					 <th class="text-center" data-label="2개월점검"> 2개월점검 </th>   
					 <th class="text-center" data-label="6개월점검"> 6개월점검 </th>   
				</tr>
			</thead>
			<tbody> 
	<?php  
	  $start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
         include "rowDB.php";
			 
// 점검을 했는지 여부는 check1의 체크여부로 판단함
// check1~check10까지 해당 질의 수를 추출
for($j=0;$j<count($mcno_arr);$j++)
	 {
	  if($mcno==$mcno_arr[$j])
	  {  
		  $arrtmp = explode(",", $questionstep_arr[$j]);  
	  }
	 }	
// 주간점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[0]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$weektermstr = '미점검';
				break;
			}
		    else
				$weektermstr = '완료';		
	  }
// 1개월 점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[1]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$monthtermstr = '미점검';
				break;
			}
		    else
				$monthtermstr = '완료';		
	  }
// 2개월 점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[2]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$twomonthtermstr = '미점검';
				break;
			}
		    else
				$twomonthtermstr = '완료';		
	  }
// 6개월 점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[3]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$sixmonthtermstr = '미점검';
				break;
			}
		    else
				$sixmonthtermstr = '완료';		
	  }
			 
			 ?>
			 
	<tr onclick="redirectToView('<?=$num?>')" data-num="<?= htmlspecialchars($num) ?>">   			 	
    <td class="text-center" data-label="점검일"> 
        <span class="  text-center" >            <?= htmlspecialchars($checkdate) ?>  </span> 
    </td>

    <td class="text-center" data-label="주간점검"> 
        <span class="  text-center" >            
                <?php if($term=='주간') echo htmlspecialchars($weektermstr); ?>            
        </span>
    </td>
    
    <td class="text-center" data-label="1개월점검"> 
        <span class="  text-center" >            
                <?php if($term=='1개월') echo htmlspecialchars($monthtermstr); ?>              
        </span>
    </td>

    <td class="text-center" data-label="2개월점검"> 
        <span class="  text-center" >            
                <?php if($term=='2개월') echo htmlspecialchars($twomonthtermstr); ?>              
        </span>
    </td>

    <td class="text-center" data-label="6개월점검"> 
        <span class="  text-center" >             
                <?php if($term=='6개월') echo htmlspecialchars($sixmonthtermstr); ?>              
        </span>
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
 </div>
     
</form>
	
	

<script>

var dataTable; // DataTables 인스턴스 전역 변수
var mcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 디버그 모드 토글 함수
function toggleDebug() {
    var currentUrl = new URL(window.location.href);
    var debugParam = currentUrl.searchParams.get('debug');
    
    if (debugParam === '1') {
        // 디버그 모드 끄기
        currentUrl.searchParams.delete('debug');
    } else {
        // 디버그 모드 켜기
        currentUrl.searchParams.set('debug', '1');
    }
    
    window.location.href = currentUrl.toString();
}

// 모바일 카드 렌더링 함수
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-cards-container').empty();
        return;
    }
    
    var container = $('#mobile-cards-container');
    container.empty();
    
    if (typeof dataTable !== 'undefined' && dataTable) {
        // DataTables에서 현재 페이지의 데이터 가져오기
        var rows = dataTable.rows({page: 'current'}).nodes();
        
        $(rows).each(function() {
            var $row = $(this);
            var num = $row.data('num') || $row.find('td:first').text().trim();
            var card = $('<div class="mobile-card"></div>');
            
            // 각 셀을 카드 아이템으로 변환
            $row.find('td').each(function() {
                var $cell = $(this);
                var label = $cell.data('label') || $cell.closest('table').find('th').eq($cell.index()).data('label') || '';
                var value = $cell.text().trim();
                
                if (value) {
                    var item = $('<div class="mobile-card-item"></div>');
                    item.append($('<div class="mobile-card-label">' + htmlspecialchars(label) + '</div>'));
                    item.append($('<div class="mobile-card-value">' + htmlspecialchars(value) + '</div>'));
                    card.append(item);
                }
            });
            
            // 클릭 이벤트 추가
            card.css('cursor', 'pointer');
            card.on('click', function() {
                redirectToView(num);
            });
            
            container.append(card);
        });
    } else {
        // DataTables가 없을 경우 일반 테이블에서 데이터 추출
        $('#myTable tbody tr').each(function() {
            var $row = $(this);
            var num = $row.data('num') || '';
            var card = $('<div class="mobile-card"></div>');
            
            $row.find('td').each(function() {
                var $cell = $(this);
                var label = $cell.data('label') || $('#myTable thead th').eq($cell.index()).data('label') || '';
                var value = $cell.text().trim();
                
                if (value) {
                    var item = $('<div class="mobile-card-item"></div>');
                    item.append($('<div class="mobile-card-label">' + htmlspecialchars(label) + '</div>'));
                    item.append($('<div class="mobile-card-value">' + htmlspecialchars(value) + '</div>'));
                    card.append(item);
                }
            });
            
            card.css('cursor', 'pointer');
            card.on('click', function() {
                redirectToView(num);
            });
            
            container.append(card);
        });
    }
}

// HTML 특수문자 이스케이프 함수
function htmlspecialchars(str) {
    if (typeof str !== 'string') return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return str.replace(/[&<>"']/g, function(m) { return map[m]; });
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
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('mcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var mcpageNumber = dataTable.page.info().page + 1;
        setCookie('mcpageNumber', mcpageNumber, 10); // 쿠키에 페이지 번호 저장
        renderMobileCards(); // 모바일 카드 다시 렌더링
    });
    
    // 검색 이벤트 리스너
    dataTable.on('search.dt', function() {
        renderMobileCards(); // 모바일 카드 다시 렌더링
    });
    
    // 그리기 이벤트 리스너
    dataTable.on('draw.dt', function() {
        renderMobileCards(); // 모바일 카드 다시 렌더링
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('mcpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    // 초기 모바일 카드 렌더링
    renderMobileCards();
    
    // 리사이즈 이벤트
    $(window).on('resize', function() {
        renderMobileCards();
    });
    
    // jQuery DataTables 컨트롤 숨기기/보이기
    function toggleDataTablesControls() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    }
    
    toggleDataTablesControls();
    $(window).on('resize', function() {
        toggleDataTablesControls();
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('mcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num) {       	
    var url = "view.php?num=" + num ;
	customPopup(url, '장비 점검', 1300, 800); 		    
}

function show_list(insu){      
	$("#selnum").val(insu); 
	$("#page").val('1'); 
	// alert($("#selnum").val());
	$("#board_form").submit(); 
}  

$(document).ready(function(){	

 $("#mcno").bind( "change", function() {		
	  $("#board_form").submit(); 
	 });	

	$("#closeBtn").click(function(){ 	   
	   window.close();		
	});	
	 
		
});	
</script>		

</body>
</html>