<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 파라미터 초기화
$mcno = $_REQUEST["mcname"] ?? '';
 
  // 첫 화면 표시 문구
 $title_message = '미래기업 고객만족 품질경영';
 
 // 베이스 URL 설정 (로컬/서버 환경 자동 감지)
 $base_url = getBaseUrl();
 // HTTPS 페이지에서는 HTTP를 HTTPS로 강제 변환
 if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
     $base_url = str_replace('http://', 'https://', $base_url);
 }
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
        
        /* 메인 카드 최적화 */
        .container > .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 메인 카드 body 최적화 */
        .container > .card > .card-body {
            padding: 0.75rem 0.5rem !important;
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        
        /* 일반 카드 최적화 */
        .card {
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            max-width: 100vw !important;
            width: 100% !important;
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
        
        /* 제목 이미지 최적화 */
        img[src*="qc-bg.jpg"] {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
        }
        
        /* 제목 최적화 */
        h5 {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* d-flex 요소 최적화 */
        .d-flex {
            flex-wrap: wrap !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center {
            margin: 0.5rem 0 !important;
            padding: 0 !important;
        }
        
        /* 장비 카드 그리드 최적화 */
        .row.gx-4.gx-lg-5 {
            margin: 0 auto !important;
            padding: 0 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .row.gx-4.gx-lg-5 .col {
            padding: 0.5rem 0.25rem !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .row.gx-4.gx-lg-5 .col .card {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .row.gx-4.gx-lg-5 .col .card.h-100 {
            height: auto !important;
            min-height: auto !important;
        }
        
        /* 장비 카드 내부 최적화 */
        .card-body.p-2 {
            padding: 0.75rem 0.5rem !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        .card-body.p-2 .text-center {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body.p-2 h5 {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            margin-bottom: 0.5rem !important;
            padding: 0 0.25rem !important;
            box-sizing: border-box !important;
        }
        
        .card-body.p-2 span {
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            display: block !important;
            margin-bottom: 0.25rem !important;
            padding: 0 0.25rem !important;
            box-sizing: border-box !important;
            max-width: 100% !important;
        }
        
        .card-body.p-2 span.fw-bolder {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* QR 코드 이미지 최적화 */
        .card-body.p-2 img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
            box-sizing: border-box !important;
        }
        
        /* QR 코드 없음 메시지 최적화 */
        .card-body.p-2 .text-muted {
            font-size: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            padding: 0.5rem !important;
            box-sizing: border-box !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
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
        
        /* 카드 클릭 영역 최적화 */
        .card.h-100[onclick] {
            cursor: pointer !important;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1) !important;
        }
        
        /* jQuery DataTables 숨기기 (만약 있다면) */
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
        }
        
        /* TUI Grid 숨기기 (만약 있다면) */
        .tui-grid-container {
            display: none !important;
        }
        
        /* Tabulator 숨기기 (만약 있다면) */
        .tabulator {
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
        
        .modal-header .btn-close {
            padding: 0.5rem !important;
            margin: 0 !important;
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
        
        .modal-body img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
        }
        
        .modal-body table {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: fixed !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body table td,
        .modal-body table th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
        }
        
        .modal-footer .btn {
            width: 100% !important;
            margin: 0.25rem 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
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
    }
    
    /* PC 환경 최적화 */
    @media (min-width: 769px) {
        .d-flex.justify-content-center .btn,
        .d-flex.justify-content-start .btn {
            margin-left: 0.25rem !important;
            margin-right: 0.25rem !important;
        }
    }
</style>

</head>

<body>

<?php include includePath("common/modal.php"); ?>
   
<?php require_once(includePath('myheader.php')); ?>   

<?php

 if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 $_SESSION["url"]='https://8440.co.kr/qc/laser.php?mcno=' . $mcno ; 		  		 
		 sleep(1);
	     header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
}  
							                	
require_once(includePath('lib/mydb.php'));
$pdo = db_connect(); 

// 배열로 장비점검리스트 불러옴
include includePath("qc/load_DB.php");

// // 배열로 미점검 장비점검리스트 불러옴
// include "load_nocheck.php";

 ?>
 
<div class="container">  
	<div class="card mt-2 mb-2">  
	<div class="card-body">   
 <div class="d-flex mt-3 mb-1 justify-content-center">  			
				<?php 
				// HTTPS 페이지에서는 HTTP를 HTTPS로 변환
				$img_url = $base_url . '/img/qc-bg.jpg';
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
				    $img_url = str_replace('http://', 'https://', $img_url);
				}
				?>
				<img src="<?= htmlspecialchars($img_url) ?>" style="width:100%;" alt="qc Background">
 </div>
	<h5 class="fw-bolder mb-4"> 점검 장비 </h5>
	<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
				
<?php

$todate=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.mymc order by num";

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$counter=0;
$num_arr=array();
$mcno_arr=array();
$mcname_arr=array();
$mcspec_arr=array();
$mcmaker_arr=array();
$mcmain_arr=array();
$mcsub_arr=array();
$qrcode_arr=array();

 try{  
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
	  $num = $row["num"] ?? '';
	  $mcno = $row["mcno"] ?? '';
	  $mcname = $row["mcname"] ?? '';
	  $mcspec = $row["mcspec"] ?? '';
	  $mcmaker = $row["mcmaker"] ?? '';
	  $mcmain = $row["mcmain"] ?? '';
	  $mcsub = $row["mcsub"] ?? '';
	  $qrcode = $row["qrcode"] ?? '';
		
	  $num_arr[$counter] = $row["num"] ?? '';
	  $mcno_arr[$counter] = $row["mcno"] ?? '';
	  $mcname_arr[$counter] = $row["mcname"] ?? '';
	  $mcspec_arr[$counter] = $row["mcspec"] ?? '';
	  $mcmaker_arr[$counter] = $row["mcmaker"] ?? '';
	  $mcmain_arr[$counter] = $row["mcmain"] ?? '';
	  $mcsub_arr[$counter] = $row["mcsub"] ?? '';
	  $qrcode_tmp = $row["qrcode"] ?? '';
	  
	  // QR 코드 경로를 환경에 맞게 동적 생성
	  if (!empty($qrcode_tmp)) {
	      // 이미 전체 URL인 경우
	      if (strpos($qrcode_tmp, 'http://') === 0 || strpos($qrcode_tmp, 'https://') === 0) {
	          // HTTPS 페이지에서는 HTTP를 HTTPS로 변환
	          if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
	              $qrcode = str_replace('http://', 'https://', $qrcode_tmp);
	          } else {
	              $qrcode = $qrcode_tmp;
	          }
	      } else {
	          // 상대 경로인 경우 base URL 추가
	          // DB 값이 'laser01' 같은 형태라면 '/img/laser01.png'로 변환
	          $img_path = $qrcode_tmp;
	          
	          // 경로가 /로 시작하지 않으면 /img/ 추가하고 .png 확장자 추가
	          if (strpos($img_path, '/') !== 0) {
	              // 확장자가 없으면 .png 추가
	              if (strpos($img_path, '.') === false) {
	                  $img_path = '/img/' . $img_path . '.png';
	              } else {
	                  $img_path = '/img/' . $img_path;
	              }
	          }
	          
	          $qrcode = $base_url . $img_path;
	      }
	  } else {
	      $qrcode = '';
	  }
	  
	  $qrcode_arr[$counter] = $qrcode;
	  // 디버깅: QR 코드 경로 확인
	  // error_log("QR Code for {$mcname}: Original={$qrcode_tmp}, Final={$qrcode}");
   
      $counter++;	
	 		
	?>			
	<div class="col mb-2">			     
		<div class="card h-100" onclick="choiceMC(<?=$num?>,'<?=$mcmain?>','<?=$mcsub?>','<?=$mcno?>');" >  
			
			<!-- Product details-->
			<div class="card-body p-2">
				<div class="text-center ">                                    
					<h5 class="fw-bolder"> <?=$row["mcname"]?> </h5>
				</div>
				<div class="text-center ">                                    
					<span class="fw-bolder"> <?=$row["mcspec"]?> </span>
				</div> 
				<div class="text-center ">                                    
					<span class="fw-bolder"> 점검(정) <?=$row["mcmain"]?> </span>
				</div>
				<div class="text-center ">                                    
					<span class="fw-bolder"> 점검(부) <?=$row["mcsub"]?> </span>
				</div>
				<div class="text-center ">                                    
				   <span class="fw-bolder">
				   <?php if (!empty($qrcode)): ?>
				       <img src="<?= htmlspecialchars($qrcode) ?>" 
				            style="width:100%;height:100%;" 
				            alt="QR Code" 
				            onerror="this.style.display='none'; console.log('Image load failed: <?= htmlspecialchars($qrcode) ?>');">
				   <?php else: ?>
				       <div class="text-muted" style="padding: 20px;">QR 코드 없음</div>
				   <?php endif; ?>
				   </span>
				</div>
			</div>
		</div>					
	</div> 
<?php
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }	
?> 					
					
	</div>
</div>
</div>
</div>
	
<!-- ajax 전송으로 DB 수정 -->
<?php include includePath('formload.php'); ?>	
	
<!-- Footer-->
<?php include includePath('shop/footer.php'); ?>  					

<script>

function choiceMC(num, mcmain, mcsub, mcno) {
    var mcMap = {
        1: 'laser01',
        2: 'vcut01',
        3: 'bending01',
        4: 'shearing01',
        5: 'welder01',
        6: 'welder02',
        7: 'welder03',
        8: 'welder04',
        9: 'motor01',
        10: 'motor02',
        11: 'tapdrill01',
        12: 'comp01',
        13: 'comp02'
    };

    // 동작 환경에 따라 base URL 결정 (현재 페이지 프로토콜 사용)
    var baseUrl;
    var protocol = window.location.protocol; // 'http:' or 'https:'
    if (location.hostname === 'localhost' || location.hostname === '127.0.0.1') {
        baseUrl = protocol + '//8440.local/qc/laser.php';
    } else {
        baseUrl = protocol + '//8440.co.kr/qc/laser.php';
    }

    var link = '';
    if (mcMap[num]) {
        link = baseUrl + '?mcno=' + mcMap[num] + '&mcname=' + mcMap[num];
    }

    console.log('Link:', link);
    console.log('Num:', num);
    console.log('MC Name:', mcMap[num]);
    
    if (num > 0 && link) {
        popupCenter(link, '장비 정검', 1200, 900);
    }
}

// 서버에 작업 기록
$(document).ready(function(){
    saveLogData('장비점검 화면'); // 다른 페이지에 맞는 menuName을 전달
    
    // HTTPS 페이지에서 모든 HTTP URL을 HTTPS로 변환
    if (window.location.protocol === 'https:') {
        // 모든 img 태그의 src 속성 변환
        $('img[src^="http://"]').each(function() {
            var src = $(this).attr('src');
            if (src && src.indexOf('http://') === 0) {
                $(this).attr('src', src.replace('http://', 'https://'));
            }
        });
        
        // 모든 link 태그의 href 속성 변환
        $('link[href^="http://"]').each(function() {
            var href = $(this).attr('href');
            if (href && href.indexOf('http://') === 0) {
                $(this).attr('href', href.replace('http://', 'https://'));
            }
        });
        
        // 모든 script 태그의 src 속성 변환
        $('script[src^="http://"]').each(function() {
            var src = $(this).attr('src');
            if (src && src.indexOf('http://') === 0) {
                $(this).attr('src', src.replace('http://', 'https://'));
            }
        });
        
        // 동적으로 추가되는 요소들도 변환
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        // img 태그
                        $(node).find('img[src^="http://"]').each(function() {
                            var src = $(this).attr('src');
                            if (src && src.indexOf('http://') === 0) {
                                $(this).attr('src', src.replace('http://', 'https://'));
                            }
                        });
                        // link 태그
                        $(node).find('link[href^="http://"]').each(function() {
                            var href = $(this).attr('href');
                            if (href && href.indexOf('http://') === 0) {
                                $(this).attr('href', href.replace('http://', 'https://'));
                            }
                        });
                        // script 태그
                        $(node).find('script[src^="http://"]').each(function() {
                            var src = $(this).attr('src');
                            if (src && src.indexOf('http://') === 0) {
                                $(this).attr('src', src.replace('http://', 'https://'));
                            }
                        });
                        // 자기 자신이 img, link, script인 경우
                        if ($(node).is('img[src^="http://"]')) {
                            var src = $(node).attr('src');
                            if (src && src.indexOf('http://') === 0) {
                                $(node).attr('src', src.replace('http://', 'https://'));
                            }
                        }
                        if ($(node).is('link[href^="http://"]')) {
                            var href = $(node).attr('href');
                            if (href && href.indexOf('http://') === 0) {
                                $(node).attr('href', href.replace('http://', 'https://'));
                            }
                        }
                        if ($(node).is('script[src^="http://"]')) {
                            var src = $(node).attr('src');
                            if (src && src.indexOf('http://') === 0) {
                                $(node).attr('src', src.replace('http://', 'https://'));
                            }
                        }
                    }
                });
            });
        });
        
        // DOM 변경 감지 시작
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    // 모바일 환경에서 모달 열릴 때 body 스크롤 잠금
    if (window.innerWidth <= 768) {
        $(document).on('show.bs.modal', '.modal', function() {
            $('body').css({
                'overflow': 'hidden',
                'position': 'fixed',
                'width': '100%',
                'height': '100%'
            });
        });
        
        $(document).on('hidden.bs.modal', '.modal', function() {
            $('body').css({
                'overflow': '',
                'position': '',
                'width': '',
                'height': ''
            });
        });
    }
    
    // jQuery DataTables 숨기기 (만약 있다면)
    function hideDataTablesControls() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    }
    
    // 초기 실행 및 리사이즈 이벤트
    hideDataTablesControls();
    $(window).on('resize', function() {
        hideDataTablesControls();
    });
    
    // DataTables draw 이벤트에서도 실행
    if ($.fn.DataTable) {
        $(document).on('draw.dt', 'table', function() {
            hideDataTablesControls();
        });
    }
});

</script> 
</body>
</html>