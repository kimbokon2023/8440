<?php
require_once __DIR__ . '/../common/functions.php';
?>
﻿<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함

  // 첫 화면 표시 문구
 $title_message = '협력업체 평가표';     
   
?>
   
<?php include getDocumentRoot() . '/load_header.php' ?>
<link rel="stylesheet" type="text/css" href="./background.css">   
 
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
        h4 {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* d-flex 요소 최적화 */
        .d-flex {
            flex-wrap: wrap !important;
        }
        
        .d-flex.justify-content-end,
        .d-flex.justify-content-center,
        .d-flex.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
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
        
        .btn-sm {
            font-size: 0.875rem !important;
            padding: 0.5rem !important;
        }
        
        /* 입력 필드 최적화 */
        input[type="text"],
        input[type="date"] {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            margin-left: 0 !important;
            font-size: 1rem !important;
            box-sizing: border-box !important;
            padding: 0.5rem !important;
            position: relative !important;
            left: auto !important;
            right: auto !important;
        }
        
        /* 특정 입력 필드 ID별 최적화 */
        #txt1, #txt2, #txt3, #txt4, #txt5, #txt6,
        #txt7, #txt8, #txt9, #txt10, #txt11, #txt12,
        #txt13, #txt14, #txt15, #txt16, #txt17, #txt18,
        #txt19, #txt20, #txt21, #txt22, #txt23 {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            margin-left: 0 !important;
            font-size: 1rem !important;
            box-sizing: border-box !important;
            padding: 0.5rem !important;
            position: relative !important;
            left: auto !important;
            right: auto !important;
        }
        
        /* 입력 필드 컨테이너 최적화 */
        #row1, #row2, #row3, #row4, #row5, #row6, #row7, #row8, #row9, #row10,
        #row11, #row12, #row13, #row14, #row15, #row16, #row17, #row18, #row19, #row20 {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.5rem 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }
        
        .clear {
            display: none !important;
        }
        
        /* 이미지 영역 숨기기 및 카드 형태로 변환 */
        .img {
            display: none !important;
        }
        
        #print,
        #outlineprint {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            background: none !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-form-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .mobile-form-card {
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
        
        .mobile-form-card-title {
            font-weight: bold;
            font-size: 1rem;
            color: #495057;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #dee2e6;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-form-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 0.25rem;
            box-sizing: border-box !important;
        }
        
        .mobile-form-label {
            font-weight: bold;
            font-size: 0.875rem;
            color: #495057;
            margin-bottom: 0.5rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-form-input {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem !important;
            box-sizing: border-box !important;
        }
        
        .mobile-form-input:focus {
            border-color: #80bdff !important;
            outline: 0 !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        }
        
        .mobile-form-section {
            margin-bottom: 1rem;
        }
        
        .mobile-form-section-title {
            font-weight: bold;
            font-size: 1.125rem;
            color: #212529;
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            background: #e9ecef;
            border-radius: 0.25rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
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
            display: inline-block !important;
            overflow: visible !important;
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
        
        /* jQuery DataTables 컨트롤 숨기기 */
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
        }
        
        /* PC용 이미지 기반 폼 숨기기 */
        .img,
        #row1, #row2, #row3, #row4, #row5, #row6, #row7, #row8, #row9, #row10,
        #row11, #row12, #row13, #row14, #row15, #row16, #row17, #row18, #row19, #row20 {
            display: none !important;
        }
        
        /* PC용 입력 필드 비활성화 (모바일에서는 모바일 입력 필드만 사용) */
        .pc-input {
            display: none !important;
        }
        
        /* 모바일 입력 필드만 표시 */
        input[data-original-id] {
            display: block !important;
        }
    }
    
    /* PC 환경 최적화 */
    @media (min-width: 769px) {
        /* 모바일 폼 숨기기 */
        #mobile-form-container {
            display: none !important;
        }
        
        /* 모바일 입력 필드 숨기기 */
        input[data-original-id] {
            display: none !important;
        }
        
        /* PC용 이미지 기반 폼 표시 */
        .img {
            display: block !important;
        }
        
        #row1, #row2, #row3, #row4, #row5, #row6, #row7, #row8, #row9, #row10,
        #row11, #row12, #row13, #row14, #row15, #row16, #row17, #row18, #row19, #row20 {
            display: block !important;
        }
        
        /* PC용 입력 필드 표시 */
        .pc-input {
            display: inline-block !important;
        }
        
        /* 모바일 입력 필드 숨기기 */
        input[data-original-id] {
            display: none !important;
        }
    }
    
    /* PC 환경 최적화 */
    @media (min-width: 769px) {
        .d-flex.justify-content-end .btn,
        .d-flex.justify-content-center .btn,
        .d-flex.align-items-center .btn {
            margin-left: 0.25rem !important;
            margin-right: 0.25rem !important;
        }
    }
</style>
 
 </head> 

<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php


// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . ($_SESSION["WebSite"] ?? '') . "login/login_form.php");
    exit;
}

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$page = $_REQUEST["page"] ?? '';
$create = $_REQUEST["create"] ?? '';

if ((int)$num > 0) {
    $SelectWork = 'update';
} else {
    $SelectWork = 'insert';
}

require_once includePath('lib/mydb.php');
$pdo = db_connect();

if ($mode !== 'copy') {
    try {
        $sql = "select * from mirae8440.p_evaluation where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        if ($count < 1) {
            print "신규작성.<br>";

            // txt1부터 txt23까지 null로 초기화
            for ($i = 1; $i < 24; $i++) {
                $txtname = 'txt' . $i;
                $$txtname = null;
            }
        } else {
            include '_row.php';
        }
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
} else {
    try {
        $sql = "select * from mirae8440.p_evaluation where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        include '_row.php';
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
    // 복사 시 num 공백 설정
    $num = '';
}
 

?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">	
  
	<input type="hidden" id="SelectWork" name="SelectWork" value="<?= $SelectWork ?>">
	<input type="hidden" id="num" name="num" value="<?= $num ?>">
	<input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
	<input type="hidden" id="page" name="page" value="<?= $page ?>">
	<input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
	<input type="hidden" id="create" name="create" value="<?= $create ?>">   
 
<div class="container">  
	<div class="card mt-2 mb-4">  
	<div class="card-body">   
 <div class="d-flex align-items-center justify-content-end">
  <button type="button" id="saveBtn" class="btn btn-dark  btn-sm mx-1"> <i class="bi bi-floppy-fill"></i> 저장 </button>  
  <button type="button" id="copyBtn" class="btn btn-primary  btn-sm mx-1"> <i class="bi bi-copy"></i> 복사 </button>  
  <?php if(intval($num)>0) { ?>
	  <button type="button" id="delBtn" class="btn btn-danger btn-sm mx-1" > <i class="bi bi-trash"></i>  삭제   </button>	 &nbsp;
  <?php } ?>
  <button type="button" id="closeBtn" class="btn btn-dark btn-sm mx-1"> &times; 닫기 </button>		
</div> 
 <div class="d-flex align-items-center justify-content-center">
  <h4 class="mb-0">평가점수 합 : <span id="total"></span></h4>
</div> 

 <div class="d-flex align-items-center justify-content-center">
<div id="print">  
<div id="outlineprint">  

	<!-- PC용 이미지 기반 폼 -->
    <div class="img">      
	<div class="clear"> </div>
    <div id="row1">
	<input id="txt1" class="pc-input" name="txt1" value="<?= htmlspecialchars($txt1 ?? '') ?>" type="text" size="10" style="border-color: blue;width:130px;height:30px;">
	<input id="txt2" class="pc-input" name="txt2" value="<?= htmlspecialchars($txt2 ?? '') ?>" type="text" size="10" style="margin-left:165px;border-color: blue;width:100px;height:30px;">
	</div>
	<div class="clear"> </div>
    <div id="row2">
	<input id="txt3" class="pc-input" name="txt3" value="<?= htmlspecialchars($txt3 ?? '') ?>" type="text" size="10" style="border-color: blue;width:130px;height:30px;">
	<input id="txt4" class="pc-input" name="txt4" value="<?= htmlspecialchars($txt4 ?? '') ?>" type="text" size="10" style="margin-left:165px;border-color: blue;width:100px;height:30px;">
	</div>
    <div class="clear"> </div>
    <div id="row3">
	<input id="txt5" class="pc-input" name="txt5" value="<?= htmlspecialchars($txt5 ?? '') ?>" type="text" size="10" style="border-color: blue;width:130px;height:30px;">
	<input id="txt6" class="pc-input" name="txt6" value="<?= htmlspecialchars($txt6 ?? '') ?>" type="date" size="10" style="font-size:12px;margin-left:165px;border-color: blue;width:100px;height:30px;"> 	
	</div>        
    <div class="clear"> </div>		
    <div id="row4">  
	<input id="txt7" class="pc-input" name="txt7" value="<?= htmlspecialchars($txt7 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center; " > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row5">  
	<input id="txt8" class="pc-input" name="txt8" value="<?= htmlspecialchars($txt8 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row6">  
	<input id="txt9" class="pc-input" name="txt9" value="<?= htmlspecialchars($txt9 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row7">  
	<input id="txt10" class="pc-input" name="txt10" value="<?= htmlspecialchars($txt10 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row8">  
	<input id="txt11" class="pc-input" name="txt11" value="<?= htmlspecialchars($txt11 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         	
    <div id="row9">  
	<input id="txt12" class="pc-input" name="txt12" value="<?= htmlspecialchars($txt12 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>	
    <div id="row10">  
	<input id="txt13" class="pc-input" name="txt13" value="<?= htmlspecialchars($txt13 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row11">  
	<input id="txt14" class="pc-input" name="txt14" value="<?= htmlspecialchars($txt14 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row12">  
	<input id="txt15" class="pc-input" name="txt15" value="<?= htmlspecialchars($txt15 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row13">  
	<input id="txt16" class="pc-input" name="txt16" value="<?= htmlspecialchars($txt16 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row14">  
	<input id="txt17" class="pc-input" name="txt17" value="<?= htmlspecialchars($txt17 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         	
    <div id="row15">  
	<input id="txt18" class="pc-input" name="txt18" value="<?= htmlspecialchars($txt18 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         
    <div id="row16">  
	<input id="txt19" class="pc-input" name="txt19" value="<?= htmlspecialchars($txt19 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
	
    <div id="row17">  
	<input id="txt20" class="pc-input" name="txt20" value="<?= htmlspecialchars($txt20 ?? '') ?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         
    <div id="row18">  
	<input id="txt21" class="pc-input" name="txt21" value="<?= htmlspecialchars($txt21 ?? '') ?>" type="date" size="5" style="border-color: blue;width:80px;height:20px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row19">  
	<input id="txt22" class="pc-input" name="txt22" value="<?= htmlspecialchars($txt22 ?? '') ?>" type="date" size="5" style="border-color: blue;width:80px;height:20px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row20">  
	<input id="txt23" class="pc-input" name="txt23" value="<?= htmlspecialchars($txt23 ?? '') ?>" type="date" size="5" style="border-color: blue;width:80px;height:20px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    </div>    <!-- end of outline --> 
    
    <!-- 모바일용 텍스트 기반 카드 폼 -->
    <div id="mobile-form-container">
        <!-- 기본 정보 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">기본 정보</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">업체명</label>
                <input id="txt1_mobile" value="<?= htmlspecialchars($txt1 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt1">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">제조구분</label>
                <input id="txt2_mobile" value="<?= htmlspecialchars($txt2 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt2">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">대표자</label>
                <input id="txt3_mobile" value="<?= htmlspecialchars($txt3 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt3">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">전년구입실적</label>
                <input id="txt4_mobile" value="<?= htmlspecialchars($txt4 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt4">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">품명</label>
                <input id="txt5_mobile" value="<?= htmlspecialchars($txt5 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt5">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">작성일</label>
                <input id="txt6_mobile" value="<?= htmlspecialchars($txt6 ?? '') ?>" type="date" class="mobile-form-input" data-original-id="txt6">
            </div>
        </div>
        
        <!-- 신용도 평가 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">신용도 평가</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">부환경 (경영능력, 내부환경의 타사 대비) - 최대 8점</label>
                <input id="txt7_mobile" value="<?= htmlspecialchars($txt7 ?? '') ?>" type="number" min="0" max="8" step="1" class="mobile-form-input" placeholder="점수 입력 (우수:8, 보통:5, 미흡:3)" data-original-id="txt7">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">경영실적 (최근 2년간 매출액, 시장 점유율) - 최대 7점</label>
                <input id="txt8_mobile" value="<?= htmlspecialchars($txt8 ?? '') ?>" type="number" min="0" max="7" step="1" class="mobile-form-input" placeholder="점수 입력 (상승:7, 정체:5, 하락:3)" data-original-id="txt8">
            </div>
        </div>
        
        <!-- 가격조건 평가 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">가격조건 평가</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">납품단가의 적정성 (납품단가의 시세 및 동종타사 대비) - 최대 15점</label>
                <input id="txt9_mobile" value="<?= htmlspecialchars($txt9 ?? '') ?>" type="number" min="0" max="15" step="1" class="mobile-form-input" placeholder="점수 입력 (낮다:15, 동등:12, 높다:8)" data-original-id="txt9">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">단가조정의 협조성 (시황변동 및 현장여건에 따른 구입단가 조정) - 최대 15점</label>
                <input id="txt10_mobile" value="<?= htmlspecialchars($txt10 ?? '') ?>" type="number" min="0" max="15" step="1" class="mobile-form-input" placeholder="점수 입력 (협조:15, 참여:12, 부정적:7)" data-original-id="txt10">
            </div>
        </div>
        
        <!-- 품질관리 평가 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">품질관리 평가</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">품질관리 (동종타업체에 대비한 품질) - 최대 15점</label>
                <input id="txt11_mobile" value="<?= htmlspecialchars($txt11 ?? '') ?>" type="number" min="0" max="15" step="1" class="mobile-form-input" placeholder="점수 입력 (우수:15, 유사:12, 나쁨:7)" data-original-id="txt11">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">하자발생 및 사후관리 (납품후 하자발생 및 처리) - 최대 10점</label>
                <input id="txt12_mobile" value="<?= htmlspecialchars($txt12 ?? '') ?>" type="number" min="0" max="10" step="1" class="mobile-form-input" placeholder="점수 입력 (신속/정확:10, 보통:8, 미흡:5)" data-original-id="txt12">
            </div>
        </div>
        
        <!-- 영업 평가 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">영업 평가</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">업무협조의 성실신속성 (본사/현장업무 수행 협조) - 최대 20점</label>
                <input id="txt13_mobile" value="<?= htmlspecialchars($txt13 ?? '') ?>" type="number" min="0" max="20" step="1" class="mobile-form-input" placeholder="점수 입력 (적극적:20, 보통:15, 불성실:10)" data-original-id="txt13">
            </div>
        </div>
        
        <!-- 납기 평가 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">납기 평가</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">납기준수 (납기 준수 여부) - 최대 10점</label>
                <input id="txt14_mobile" value="<?= htmlspecialchars($txt14 ?? '') ?>" type="number" min="0" max="10" step="1" class="mobile-form-input" placeholder="점수 입력 (납기내:10, 1회위반:8, 2회위반:5, 3회위반:3)" data-original-id="txt14">
            </div>
        </div>
        
        <!-- 기타 평가 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">기타 평가</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">특별가점 1 (자재 품귀시 당사 자재수급에 적극협조한 업체) - 최대 5점</label>
                <input id="txt15_mobile" value="<?= htmlspecialchars($txt15 ?? '') ?>" type="number" min="0" max="5" step="1" class="mobile-form-input" placeholder="점수 입력 (0~5)" data-original-id="txt15">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">특별감점 (자재 품귀시 비협조, 납기지연 및 하자 발생이 잦은 업체) - 최대 10점</label>
                <input id="txt16_mobile" value="<?= htmlspecialchars($txt16 ?? '') ?>" type="number" min="0" max="10" step="1" class="mobile-form-input" placeholder="감점 입력 (0~10)" data-original-id="txt16">
            </div>
        </div>
        
        <!-- 합계 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">평가점수 합계</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">총점 (자동 계산)</label>
                <input id="txt17_mobile" value="<?= htmlspecialchars($txt17 ?? '') ?>" type="text" class="mobile-form-input" readonly style="background-color: #e9ecef; font-weight: bold; font-size: 1.25rem;" data-original-id="txt17">
            </div>
        </div>
        
        <!-- 결재 -->
        <div class="mobile-form-card">
            <div class="mobile-form-card-title">결재</div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">작성자</label>
                <input id="txt18_mobile" value="<?= htmlspecialchars($txt18 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt18">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">검토자</label>
                <input id="txt19_mobile" value="<?= htmlspecialchars($txt19 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt19">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">승인자</label>
                <input id="txt20_mobile" value="<?= htmlspecialchars($txt20 ?? '') ?>" type="text" class="mobile-form-input" data-original-id="txt20">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">작성일</label>
                <input id="txt21_mobile" value="<?= htmlspecialchars($txt21 ?? '') ?>" type="date" class="mobile-form-input" data-original-id="txt21">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">검토일</label>
                <input id="txt22_mobile" value="<?= htmlspecialchars($txt22 ?? '') ?>" type="date" class="mobile-form-input" data-original-id="txt22">
            </div>
            <div class="mobile-form-item">
                <label class="mobile-form-label">승인일</label>
                <input id="txt23_mobile" value="<?= htmlspecialchars($txt23 ?? '') ?>" type="date" class="mobile-form-input" data-original-id="txt23">
            </div>
        </div>
    </div>
    
					</div>   
				</div>   
			</div>   
		</div>   
	</div>   
</div>   
  
  </form>  
</body>
</html>


<script>

ajaxRequestSub = null ;

// 입력 필드 동기화 함수 (모바일 <-> PC)
function syncInputFields(sourceId, targetId) {
    var sourceValue = $('#' + sourceId).val();
    if (sourceValue !== undefined && sourceValue !== null) {
        $('#' + targetId).val(sourceValue);
    }
}

// 점수 계산 함수
function calculate() {
    // txt7부터 txt15까지 입력된 값들을 더합니다.
    let sum = 0;
    for (let i = 7; i <= 15; i++) {
        // PC와 모바일 모두 확인
        let pcValue = parseFloat($('#txt' + i).val());
        let mobileValue = parseFloat($('#txt' + i + '_mobile').val());
        let value = !isNaN(pcValue) && pcValue !== 0 ? pcValue : (!isNaN(mobileValue) && mobileValue !== 0 ? mobileValue : 0);
        if (!isNaN(value) && value > 0) {
            sum += value;
        }
    }

    // txt16의 값을 뺍니다.
    let pcSubtractValue = parseFloat($('#txt16').val());
    let mobileSubtractValue = parseFloat($('#txt16_mobile').val());
    let subtractValue = !isNaN(pcSubtractValue) && pcSubtractValue !== 0 ? pcSubtractValue : (!isNaN(mobileSubtractValue) && mobileSubtractValue !== 0 ? mobileSubtractValue : 0);
    if (!isNaN(subtractValue) && subtractValue > 0) {
        sum -= subtractValue;
    }
    
    // 합계 업데이트 (PC와 모바일 모두)
    $("#txt17").val(sum);
    $("#txt17_mobile").val(sum);
    $("#total").text(sum + '점');
}

// 폼 제출 전 동기화 함수
function syncBeforeSubmit() {
    if (window.innerWidth <= 768) {
        // 모바일: 모바일 입력 필드 값을 PC 입력 필드에 복사
        for (let i = 1; i <= 23; i++) {
            var mobileValue = $('#txt' + i + '_mobile').val();
            if (mobileValue !== undefined && mobileValue !== null && mobileValue !== '') {
                $('#txt' + i).val(mobileValue);
            }
        }
    } else {
        // PC: PC 입력 필드 값을 모바일 입력 필드에 복사
        for (let i = 1; i <= 23; i++) {
            var pcValue = $('#txt' + i).val();
            if (pcValue !== undefined && pcValue !== null && pcValue !== '') {
                $('#txt' + i + '_mobile').val(pcValue);
            }
        }
    }
}

$(document).ready(function(){	
    // 초기 합계 표시
    var initialTotal = $("#txt17").val() || $("#txt17_mobile").val() || '0';
    $("#total").text(initialTotal + '점');
    
    // PC와 모바일 입력 필드 동기화
    for (let i = 1; i <= 23; i++) {
        // PC -> 모바일 동기화
        $("#txt" + i).on("input change", function() {
            syncInputFields('txt' + i, 'txt' + i + '_mobile');
            if (i >= 7 && i <= 16) {
                calculate();
            }
        });
        
        // 모바일 -> PC 동기화
        $("#txt" + i + "_mobile").on("input change", function() {
            syncInputFields('txt' + i + '_mobile', 'txt' + i);
            if (i >= 7 && i <= 16) {
                calculate();
            }
        });
    }

    // txt7부터 txt16까지 input의 입력이 변경될 때마다 calculate() 함수를 호출합니다.
    for (let i = 7; i <= 16; i++) {
        $("#txt" + i).on("input", function() {
            calculate();
        });
        $("#txt" + i + "_mobile").on("input", function() {
            calculate();
        });
    }
    
    // 초기 계산
    calculate();
    
    // 폼 제출 전 동기화
    $("#board_form").on("submit", function(e) {
        syncBeforeSubmit();
    });
	
		
	     // $("#board_form").submit();	   
		// popupCenter('pdf1.php?imageURL=' + $('#imageURL').val(), 'PDF파일보기/저장', 1000,800) ;
		

	$("#copyBtn").on("click", function() {	
	    syncBeforeSubmit(); // 동기화
	    $("#mode").val('copy');
	    $("#board_form").submit();	  
	});
	// 저장 버튼 서버에 저장하고 Ecount 전송함
	$("#saveBtn").on("click", function() {	

      const user_name = '<?php echo $user_name; ?>';
	  
	  if(user_name!=='김보곤') 
	  {
		  myalert('저장 권한이 없습니다.');
	  }
	  else
	  {
		    syncBeforeSubmit(); // 동기화
		    
         if(Number($("#num").val()) > 0 )
			   $('#SelectWork').val('update');
		   else
			   $('#SelectWork').val('insert');
			   
			console.log( $("#board_form").serialize() );	
			
				  if (ajaxRequest !== null) {
						ajaxRequest.abort();
					}

						 // ajax 요청 생성
						 ajaxRequest = $.ajax({
								url: "process.php",
								type: "post",		
								data: $("#board_form").serialize(),		
								datatype : 'json',
								success : function( data ){	

                                    console.log(data);
                                    $("#num").val(data["num"]);									
							
								Swal.fire({
									
								   title: '자료등록/수정',
								   text: '처리되었습니다.',
								   icon: 'success',
								   
								   showCancelButton: false, // cancel버튼 보이기. 기본은 원래 없음
								   confirmButtonColor: '#3085d6', // confrim 버튼 색깔 지정
								   cancelButtonColor: '#d33', // cancel 버튼 색깔 지정
								   confirmButtonText: '확인', // confirm 버튼 텍스트 지정
								   // cancelButtonText: '취소', // cancel 버튼 텍스트 지정
								   
								   reverseButtons: true, // 버튼 순서 거꾸로
								   
								}).then(result => {
								   // 만약 Promise리턴을 받으면,
								   if (result.isConfirmed) { // 만약 모달창에서 confirm 버튼을 눌렀다면
			  					  opener.location.reload();
								 // $(opener.location).attr("href", "javascript:reloadlist();");	
								  window.close();
												}				
												});				  // end of swal
																	
										},
												error : function( jqxhr , status , error ){
													console.log( jqxhr , status , error );
													   } 			      		
							});	 // end of ajax
							   
					   
	  } // end of else if		
				   										   
   });	 // end of function			
   
				
	$("#closeBtn").click(function(){  	 
        // $("#board_form").submit();	   
		self.close();
		
	});	
						
	$("#delBtn").click(function(){  	 

		var level = Number($('#session_level').val());
		if (level > 2) {
			Swal.fire({
				title: '관리자 권한 필요',
				text: "삭제하려면 관리자에게 문의해 주세요.",
				icon: 'error',
				confirmButtonText: '확인'
			});
		} else {
			Swal.fire({
				title: '자료 삭제',
				text: "삭제는 신중! 정말 삭제하시겠습니까?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '삭제',
				cancelButtonText: '취소'
			}).then((result) => {
				
			if (result.isConfirmed) {
				syncBeforeSubmit(); // 동기화
				if (ajaxRequestSub !== null) {
						ajaxRequestSub.abort();
					}							
					ajaxRequestSub = $.ajax({
						url: 'delete.php',
						type: "post",
						data: $("#board_form").serialize(),
						dataType: "json", 
						success: function(data) {	
								setTimeout(function() {
									window.opener.location.reload(); // 0.5초 후 부모 창 새로고침
									setTimeout(function() {
										window.close(); // 추가적으로 0.5초 후 현재 창 닫기
									}, 500);
								}, 500);
							},
							error : function( jqxhr , status , error ){
								console.log( jqxhr , status , error );
							} 			      		
						   });		
				
					}
			});
		}
		
	});	
		
});

function myalert(str) {
 Toastify({
		text: str,
		duration: 3000,
		close:true,
		gravity:"top",
		position: "center",
		backgroundColor: "#4fbe87",
	}).showToast();	
	
	setTimeout(function() {
		// 시간지연
		}, 1000);	
}	
	

</script>	

