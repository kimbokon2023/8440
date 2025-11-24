<?php require_once __DIR__ . '/bootstrap.php';

 if(!isset($_SESSION["level"]) && $_SESSION["level"]==20) {
	// 포미스톤 레벨 20부여
	header ("Location:../phomi/list.php");
	exit;  
 }
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>6) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
	     header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }  

require_once(includePath('load_header.php'));
// 택배화물 수는 기본 0
$delivery_count_today = 0;
?>
 
<title> 미래기업 업무포탈</title> 
  
<!--head 태그 내 추가-->
<!-- Favicon-->	
<link rel="icon" type="image/x-icon" href="favicon.ico">   <!-- 33 x 33 -->
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">    <!-- 144 x 144 -->
<link rel="apple-touch-icon" type="image/x-icon" href="favicon.ico">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Consolidated Dashboard Styles -->
<link rel="stylesheet" href="css/dashboard-style.css"> 

<style>
.blink-toggle {
    animation: blink 2s infinite;
}

@keyframes blink {
    0%, 50% { 
        opacity: 1; 
    }
    51%, 100% { 
        opacity: 0.3; 
    }
}

.blink-badge {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8em;
    font-weight: bold;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* 택배 알림 말풍선 */
.delivery-reminder {
    position: fixed;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    z-index: 1000;
    cursor: pointer;
    max-width: 250px;
    text-align: center;
    /* PC 기본 위치 */
    top: 20px;
    right: 20px;
    animation: float 3s ease-in-out infinite;
}

/* 모바일 대응 */
@media (max-width: 768px) {
    .delivery-reminder {
        /* 모바일에서는 하단 중앙에 고정 (전자결재 버튼 위) */
        top: auto;
        bottom: 90px;
        left: 50%;
        right: auto;
        transform: translateX(-50%);
        max-width: calc(100% - 40px);
        width: auto;
        animation: float-mobile 3s ease-in-out infinite;
    }

    @keyframes float-mobile {
        0%, 100% {
            transform: translateX(-50%) translateY(0px);
        }
        50% {
            transform: translateX(-50%) translateY(-8px);
        }
    }
}

.delivery-reminder::before {
    content: '';
    position: absolute;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    /* PC: 하단 꼬리 */
    bottom: -8px;
    right: 20px;
    border-top: 8px solid #764ba2;
    border-bottom: none;
}

@media (max-width: 768px) {
    .delivery-reminder::before {
        /* 모바일: 상단 꼬리 (위쪽 방향) */
        bottom: auto;
        top: -8px;
        left: 50%;
        right: auto;
        transform: translateX(-50%);
        border-top: none;
        border-bottom: 8px solid #667eea;
    }
}

/* 테이블 헤더 하이라이트 */
.delivery-table-highlight {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%) !important;
    color: white !important;
    animation: pulse-highlight 1.5s infinite;
    transform: scale(1.02);
    transition: all 0.3s ease;
}

@keyframes pulse-highlight {
    0%, 100% { 
        box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7);
    }
    50% { 
        box-shadow: 0 0 0 10px rgba(255, 107, 107, 0);
    }
}

.delivery-reminder:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

@media (max-width: 768px) {
    .delivery-reminder:hover {
        transform: translateX(-50%) scale(1.05);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.delivery-reminder .icon {
    font-size: 16px;
    margin-right: 6px;
    animation: bounce 2s infinite;
}

.delivery-reminder .close-btn {
    position: absolute;
    top: 5px;
    right: 8px;
    font-size: 18px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.delivery-reminder .close-btn:hover {
    opacity: 1;
    transform: scale(1.1);
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-3px); }
    60% { transform: translateY(-2px); }
}

/* 식사주문 알림 말풍선 */
.lunch-reminder {
    position: fixed;
    background: linear-gradient(135deg, #20b2aa 0%, #17a2b8 100%);
    color: white;
    padding: 12px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(32, 178, 170, 0.3);
    z-index: 1000;
    cursor: pointer;
    max-width: 250px;
    text-align: center;
    display: none;
    /* PC 기본 위치 */
    top: 80px;
    right: 20px;
    animation: float 3s ease-in-out infinite;
}

/* 모바일 대응 */
@media (max-width: 768px) {
    .lunch-reminder {
        /* 모바일에서는 하단 중앙에 고정 (택배 알림보다 위) */
        top: auto;
        bottom: 165px;
        left: 50%;
        right: auto;
        transform: translateX(-50%);
        max-width: calc(100% - 40px);
        width: auto;
        animation: float-mobile 3s ease-in-out infinite;
    }
}

.lunch-reminder::before {
    content: '';
    position: absolute;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    /* PC: 하단 꼬리 */
    bottom: -8px;
    right: 20px;
    border-top: 8px solid #17a2b8;
    border-bottom: none;
}

@media (max-width: 768px) {
    .lunch-reminder::before {
        /* 모바일: 상단 꼬리 (위쪽 방향) */
        bottom: auto;
        top: -8px;
        left: 50%;
        right: auto;
        transform: translateX(-50%);
        border-top: none;
        border-bottom: 8px solid #20b2aa;
    }
}

.lunch-reminder:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

@media (max-width: 768px) {
    .lunch-reminder:hover {
        transform: translateX(-50%) scale(1.05);
    }
}

.lunch-reminder .icon {
    font-size: 16px;
    margin-right: 6px;
    animation: bounce 2s infinite;
}

.lunch-reminder .close-btn {
    position: absolute;
    top: 5px;
    right: 8px;
    font-size: 18px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.lunch-reminder .close-btn:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* 모바일 가로 스크롤 방지 */
@media (max-width: 768px) {
    body {
        overflow-x: hidden;
    }

    /* 컨테이너 최대 너비 제한 */
    .container, .container-xxl {
        max-width: 100%;
        overflow-x: hidden;
    }
}
</style>
</head> 

<?php require_once(includePath('myheader.php')); ?>
	
<?php
 	
// 덴크리 분기
if($_SESSION["userid"]=='3675')  // 김재현님 전번뒷자리
{
	header ("Location:../outorder/list.php");
	exit;  
}  
	
$readIni = array();   // steel 환경파일 불러오기 정면에 철판사용에 대한 그래프 띄우기
$readIni = parse_ini_file("./steel/steelinfo.ini",false);	

$yesterdayTotal = $readIni['yesterdaytotal'] === "NaN" ? 0 : floatval($readIni['yesterdaytotal']);
$yesterdayUsed = floatval($readIni['yesterdayused']);
$total = $yesterdayTotal + $yesterdayUsed;


$used = $readIni['yesterdayused'];
$saved = $readIni['yesterdaysaved'];
$used_rate = round($used / 10000000 *1000) / 10;
$saved_rate = round($saved / 500000 *1000) / 10; 

// 결재권자를 배열에 담아서 검색 후 있으면 알람창 띄워주는 로직 계발

$approvalarr = array();

// echo("<meta http-equiv='refresh' content='20'>");  // 1초후 새로고침

// 장비 점검일마다 자료 생성하기 (주간,월간,2개월,6개월 점검)
include "./mymachine/createDB.php";

// // 사무실 청소 장비점검일마다 자료 생성하기 (주간,월간,2개월,6개월 점검)
include "./qcoffice/createDB.php";

// 불량DB 결재루틴 제작
include "./steel/checkerrorDB.php";

// jamb 전일 매출 가져오기 
include "./work/load_jamb_output.php";
// jamb 접수 현황 가져오기
include "./load_jamb_pre.php";

// LC 전일 매출 가져오기
include "./ceiling/load_ceiling_output.php";
// 천장 접수 현황 가져오기
include "./load_ceiling_pre.php";

// var_dump($bad_choice_arr);
// $bad_number 불량수

// print  $_SERVER['HTTP_USER_AGENT'];
	
// popupwindow 테이블의 내용을 적용하기
$popupDisplay = false;  // 팝업 표시 여부 플래그
$tablename = 'popupwindow';
 try{
      $sql = "select * from {$DB}.{$tablename} where division='표시' order by num "; // 표시인것 띄우기
      $stmh = $pdo->query($sql); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count>1){        
      $row = $stmh->fetch(PDO::FETCH_ASSOC);      
      $division = $row["division"];
      $popupContents = $row["searchtext"];
	 if($division === '표시'){
		$popupDisplay = true;
		}
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }

?> 

<?if($chkMobile) { ?>
   <!-- 모바일 일때
<div class="container-xxl">    
	<div class="d-flex mb-1 mt-2 justify-content-center">    
	   <img src="./img/intrologo.png" style="width:100%;" ></a>	
	</div>
</div> -->
<?}?>

<!-- 택배 알림 말풍선 -->
<div class="delivery-reminder" id="deliveryReminder" style="display: none;">
    <span class="icon">📦</span>
    금일 화물/택배가 있어요!
    <span class="close-btn" onclick="closeDeliveryReminder()">×</span>
</div>

<!-- 식사주문 알림 말풍선 -->
<div class="lunch-reminder" id="lunchReminder" style="display: none;">
    <span class="icon">🍽️</span>
    식사주문해 주세요!
    <span class="close-btn" onclick="closeLunchReminder()">×</span>
</div>

 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-xxl">     
	<?php } ?>	 

<?php
    $tabs = array(
		"알림" => 0,
		"작성" => 1,
		"상신" => 2,
		"미결" => 3,
		"진행" => 4,
		"결재" => 5
    );
?>
 
<!-- 모바일 전자결재 토글 버튼 -->
<div class="eworks-mobile-toggle d-md-none" id="eworksMobileToggle">
    <button type="button" class="eworks-toggle-btn" onclick="toggleEworksSidebar()">
        <i class="bi bi-file-earmark-text"></i>
        <span class="eworks-badge-container" id="eworksTotalBadge"></span>
    </button>
</div>

<!-- 전자결재 사이드바 -->
<div class="sideBanner" id="eworksSidebar">
    <div class="eworks-sidebar-header">
        <span class="text-center text-dark fw-bold">전자결재</span>
        <button type="button" class="btn-close" onclick="toggleEworksSidebar()" aria-label="Close"></button>
    </div>

    <div class="eworks-sidebar-content">
	<?php
		// print $eworks_level  ;
		foreach ($tabs as $label => $tabId) {
			$badgeId = "badge" . $tabId;
    ?>

	<div class="mb-2">
		 <?php if ($label !== "알림")
			{
					if($eworks_level && ($tabId>=3) )
					{
					  print '<button type="button" class="btn btn-dark rounded-pill eworks-mobile-btn" onclick="seltab(' . $tabId . '); closeEworksSidebarOnClick();"> ';
					  echo $label;
					  print '<span class="badge badge-pill badge-dark" id="' . $badgeId . '"></span>';
					}
					else if (!$eworks_level)  // 일반결재 상신하는 그룹
					{
					  print '<button type="button" class="btn btn-dark rounded-pill eworks-mobile-btn" onclick="seltab(' . $tabId . '); closeEworksSidebarOnClick();"> ';
					  echo $label;
					  print '<span class="badge badge-pill badge-dark" id="' . $badgeId . '"></span>';
					}

				}
				else
				{
					   print '<div id="bellIcon"> 🔔결재 </div>';
				}
			?>
		</button>
	</div>
    <?php
    }
    ?>
    </div>
</div>
</div>

<style>
/* 모바일 전자결재 토글 버튼 */
.eworks-mobile-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 999;
}

.eworks-toggle-btn {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.eworks-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.eworks-toggle-btn:active {
    transform: scale(0.95);
}

.eworks-badge-container {
    position: absolute;
    top: -5px;
    right: -5px;
    min-width: 24px;
    height: 24px;
    background: #ff4757;
    border-radius: 12px;
    color: white;
    font-size: 12px;
    font-weight: bold;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.eworks-badge-container.active {
    display: flex;
    animation: badge-pop 0.3s ease;
}

@keyframes badge-pop {
    0% { transform: scale(0); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

/* PC에서는 기존 사이드바 표시 */
@media (min-width: 769px) {
    .eworks-mobile-toggle {
        display: none !important;
    }

    .sideBanner {
        display: block !important;
    }
}

/* 모바일에서 사이드바 완전히 숨김 */
@media (max-width: 768px) {
    .sideBanner,
    .sideEworksBanner {
        display: none !important; /* 기본적으로 완전히 숨김 */
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8); /* 중앙 위치, 초기 크기 작게 */
        width: calc(100% - 24px); /* 좌우 여백 12px씩 */
        max-width: 360px;
        height: auto;
        max-height: calc(100vh - 24px); /* 상하 여백 12px씩 */
        background: #ffffff !important;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        z-index: 1001;
        overflow: hidden; /* 외부 스크롤 방지 */
        padding: 0; /* 내부 패딩 제거 (헤더/내용에서 개별 설정) */
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        /* 화면 경계를 벗어나지 않도록 */
        box-sizing: border-box;
        /* 텍스트 기본 색상 설정 */
        color: #212529;
    }

    .sideBanner.active,
    .sideEworksBanner.active {
        display: flex !important; /* flex로 변경하여 레이아웃 개선 */
        flex-direction: column;
        transform: translate(-50%, -50%) scale(1); /* 중앙 위치, 정상 크기 */
        opacity: 1; /* 완전히 보이기 */
        /* 화면 높이의 85%까지 사용 (여백 7.5%씩) - 더 넓게 */
        max-height: 85vh;
        height: auto;
        min-height: 300px; /* 최소 높이 보장 */
    }
    
    /* 모달 헤더 고정 */
    .sideBanner.active .eworks-sidebar-header,
    .sideEworksBanner.active .eworks-sidebar-header {
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 18px 16px 18px;
        background: #ffffff;
        border-bottom: 2px solid #e9ecef;
    }
    
    .sideBanner.active .eworks-sidebar-header > span,
    .sideEworksBanner.active .eworks-sidebar-header > span {
        flex: 1;
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        color: #212529 !important;
    }
    
    /* 모달 내용 스크롤 영역 - 모든 버튼이 보이도록 */
    .sideBanner.active .eworks-sidebar-content,
    .sideEworksBanner.active .eworks-sidebar-content {
        flex: 1 1 auto;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding: 16px 18px 24px 18px;
        min-height: 0; /* flex 아이템이 축소될 수 있도록 */
        /* 스크롤바 스타일링 */
        -webkit-overflow-scrolling: touch;
        /* 스크롤바가 잘 보이도록 */
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.4) rgba(0, 0, 0, 0.1);
        max-height: calc(85vh - 100px); /* 헤더 높이를 제외한 나머지 공간 */
    }
    
    /* 웹킷 브라우저용 스크롤바 스타일 */
    .sideBanner.active .eworks-sidebar-content::-webkit-scrollbar,
    .sideEworksBanner.active .eworks-sidebar-content::-webkit-scrollbar {
        width: 8px;
    }
    
    .sideBanner.active .eworks-sidebar-content::-webkit-scrollbar-track,
    .sideEworksBanner.active .eworks-sidebar-content::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }
    
    .sideBanner.active .eworks-sidebar-content::-webkit-scrollbar-thumb,
    .sideEworksBanner.active .eworks-sidebar-content::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 4px;
    }
    
    .sideBanner.active .eworks-sidebar-content::-webkit-scrollbar-thumb:hover,
    .sideEworksBanner.active .eworks-sidebar-content::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.5);
    }
    
    /* 사이드바 내부 버튼 스타일 개선 */
    .sideBanner .btn,
    .sideEworksBanner .btn {
        width: 100%;
        margin: 8px 0;
        padding: 16px 20px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: visible; /* 텍스트가 잘리지 않도록 */
        text-overflow: ellipsis;
        /* 명시적으로 색상 지정 */
        background-color: #212529 !important;
        border-color: #212529 !important;
        color: #ffffff !important;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 52px; /* 최소 높이 보장 */
        box-sizing: border-box;
    }
    
    .sideBanner .btn:hover,
    .sideEworksBanner .btn:hover,
    .sideBanner .btn:active,
    .sideEworksBanner .btn:active {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* 버튼 내부 텍스트 및 배지 스타일 */
    .sideBanner .btn span,
    .sideEworksBanner .btn span {
        color: #ffffff !important;
        font-weight: 600;
        margin-left: 8px;
    }
    
    /* 배지 스타일 강화 */
    .sideBanner .badge,
    .sideEworksBanner .badge {
        background-color: rgba(255, 255, 255, 0.3) !important;
        color: #ffffff !important;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 10px;
    }
    
    /* 모바일에서 버튼 간격 조정 */
    .sideBanner .mb-2,
    .sideEworksBanner .mb-2 {
        margin-bottom: 4px !important;
    }
    
    /* 버튼 컨테이너 여백 최소화 */
    .sideBanner .mb-2:last-child,
    .sideEworksBanner .mb-2:last-child {
        margin-bottom: 0 !important;
    }
    
    /* 사이드바 닫기 버튼 스타일 */
    .sideBanner .btn-close,
    .sideEworksBanner .btn-close {
        background: transparent !important;
        border: none !important;
        font-size: 1.5rem;
        opacity: 0.8;
        cursor: pointer;
        padding: 4px 8px;
        transition: all 0.2s ease;
        color: #333 !important;
        filter: brightness(0.5);
    }
    
    .sideBanner .btn-close:hover,
    .sideEworksBanner .btn-close:hover {
        opacity: 1;
        transform: scale(1.1);
        filter: brightness(0);
    }
    
    /* 제목 텍스트 스타일 강화 */
    .sideBanner > div:first-child span,
    .sideEworksBanner > div:first-child span {
        color: #212529 !important;
        font-weight: 700 !important;
        font-size: 20px !important;
    }
    
    /* 모바일 버튼 클릭 효과 */
    .eworks-mobile-btn:active {
        transform: scale(0.95);
    }

    /* 모바일에서 카드 너비 100%로 조정 */
    .board_list {
        width: 100% !important;
        padding: 7px !important;
    }

    .modern-management-card,
    .card {
        width: 100% !important;
        margin: 0 0 10px 0 !important;
    }

    /* 컨테이너 최대 너비 */
    .container-xxl {
        padding-left: 15px !important;
        padding-right: 15px !important;
        max-width: 100% !important;
    }
}

/* 부트스트랩 모달 모바일 fullscreen 스타일 */
@media (max-width: 768px) {
    /* 전자결재 부트스트랩 모달 fullscreen */
    #eworks_form.modal {
        padding: 0 !important;
        margin: 0 !important;
        z-index: 1055 !important;
    }
    
    #eworks_form.modal.show {
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        z-index: 1055 !important;
    }
    
    #eworks_form .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7) !important;
        backdrop-filter: blur(8px);
        z-index: 1050 !important;
    }
    
    #eworks_form .modal-backdrop.show {
        z-index: 1050 !important;
    }
    
    #eworks_form .eworks-modal-dialog {
        margin: 0 !important;
        max-width: 100vw !important;
        width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        min-height: 100vh !important;
        padding: 0 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        transform: none !important;
        z-index: 1055 !important;
    }
    
    /* 전자결재 상세 모달 (eworks_viewmodal) - 모바일에서 리스트 모달 위에 표시 */
    #eworks_viewmodal.modal {
        z-index: 9999 !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    #eworks_viewmodal.modal.show {
        z-index: 9999 !important;
        display: block !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    #eworks_viewmodal .modal-dialog {
        z-index: 9999 !important;
        margin: 0 !important;
        max-width: 100vw !important;
        width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        min-height: 100vh !important;
        padding: 0 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        transform: none !important;
    }
    
    #eworks_viewmodal .modal-content {
        z-index: 9999 !important;
        width: 100vw !important;
        max-width: 100vw !important;
        min-width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        min-height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        border-radius: 0 !important;
        border: none !important;
        display: flex !important;
        flex-direction: column !important;
        box-shadow: none !important;
        position: relative !important;
    }
    
    #eworks_viewmodal .modal-header {
        flex-shrink: 0;
        padding: 16px 20px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        position: sticky;
        top: 0;
        z-index: 10;
        border-radius: 0 !important;
    }
    
    #eworks_viewmodal .modal-title {
        color: white !important;
        font-weight: 700;
        font-size: 20px;
        margin: 0;
    }
    
    #eworks_viewmodal .modal-header .btn,
    #eworks_viewmodal .modal-header button {
        color: white !important;
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }
    
    #eworks_viewmodal .modal-header .btn:hover,
    #eworks_viewmodal .modal-header button:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    #eworks_viewmodal .modal-body {
        flex: 1 1 auto !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding: 12px !important;
        margin: 0 !important;
        -webkit-overflow-scrolling: touch;
        background: #f8f9fa !important;
        min-height: calc(100vh - 120px) !important;
        max-height: calc(100vh - 120px) !important;
    }
    
    #eworks_viewmodal .modal-footer {
        flex-shrink: 0;
        padding: 12px 20px;
        border-top: 1px solid #e9ecef;
        background: #ffffff;
        justify-content: center;
        position: relative !important;
        z-index: 100 !important;
    }
    
    /* 버튼 영역 z-index 설정 */
    #eworks_viewmodal #eworksBtn {
        position: relative !important;
        z-index: 100 !important;
    }
    
    #eworks_viewmodal #eworksBtn .d-flex {
        position: relative !important;
        z-index: 100 !important;
    }
    
    /* 승인, 반려, 보류 버튼 z-index 설정 */
    #eworks_viewmodal #eworks_approvalBtn,
    #eworks_viewmodal #eworks_rejectBtn,
    #eworks_viewmodal #eworks_waitBtn,
    #eworks_viewmodal #eworks_saveBtn,
    #eworks_viewmodal #eworks_delBtn,
    #eworks_viewmodal #eworks_sendBtn,
    #eworks_viewmodal #eworks_recallBtn {
        position: relative !important;
        z-index: 101 !important;
    }
    
    /* card-body 내부 버튼 영역 */
    #eworks_viewmodal .card-body #eworksBtn {
        position: relative !important;
        z-index: 100 !important;
        margin-top: 16px !important;
    }
    
    /* 버튼들이 다른 요소 위에 표시되도록 */
    #eworks_viewmodal .card-body {
        position: relative !important;
        z-index: 10 !important;
    }
    
    #eworks_viewmodal .card {
        position: relative !important;
        z-index: 10 !important;
    }
    
    /* SweetAlert 모달이 전자결재 상세 모달 위에 표시되도록 */
    .swal2-container {
        z-index: 10000 !important;
    }
    
    .swal2-popup {
        z-index: 10001 !important;
    }
    
    .swal2-backdrop-show {
        z-index: 9999 !important;
    }
    
    /* SweetAlert가 열려있을 때 전자결재 상세 모달보다 위에 표시 */
    body.swal2-shown .swal2-container {
        z-index: 10000 !important;
    }
    
    body.swal2-shown #eworks_viewmodal.modal {
        z-index: 9998 !important;
    }
    
    body.swal2-shown #eworks_viewmodal .modal-dialog {
        z-index: 9998 !important;
    }
    
    body.swal2-shown #eworks_viewmodal .modal-content {
        z-index: 9998 !important;
    }
    
    /* 전자결재 상세 모달 내부 테이블 모바일 최적화 */
    #eworks_viewmodal .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 12px !important;
    }
    
    #eworks_viewmodal .table {
        font-size: 13px !important;
        width: 100% !important;
        table-layout: auto !important;
        margin-bottom: 0 !important;
    }
    
    #eworks_viewmodal .table td,
    #eworks_viewmodal .table th {
        padding: 10px 8px !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        font-size: 13px !important;
        line-height: 1.5 !important;
        vertical-align: middle !important;
    }
    
    #eworks_viewmodal .table td.text-center {
        text-align: center !important;
        font-weight: 600;
        background-color: #f8f9fa;
        min-width: 80px;
        width: auto;
    }
    
    #eworks_viewmodal .table td:not(.text-center) {
        text-align: left !important;
    }
    
    /* 모바일에서 테이블 행을 세로로 배치 */
    #eworks_viewmodal .table tbody tr {
        display: flex !important;
        flex-direction: column !important;
        border: 1px solid #dee2e6 !important;
        margin-bottom: 12px !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        background: white !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
    }
    
    #eworks_viewmodal .table tbody tr td {
        display: flex !important;
        flex-direction: row !important;
        padding: 12px !important;
        border: none !important;
        border-bottom: 1px solid #e9ecef !important;
    }
    
    #eworks_viewmodal .table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    #eworks_viewmodal .table tbody tr td.text-center {
        background-color: #667eea !important;
        color: white !important;
        font-weight: 700 !important;
        padding: 10px 12px !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2) !important;
        min-width: 0 !important;
        width: 100% !important;
    }
    
    #eworks_viewmodal .table tbody tr td:not(.text-center) {
        flex: 1 !important;
        padding-left: 12px !important;
    }
    
    /* 입력 필드 모바일 최적화 */
    #eworks_viewmodal .form-control {
        font-size: 14px !important;
        padding: 8px 12px !important;
        border-radius: 6px !important;
        border: 1px solid #ced4da !important;
        width: 100% !important;
    }
    
    #eworks_viewmodal select.form-control {
        min-height: 40px !important;
    }
    
    #eworks_viewmodal textarea.form-control {
        min-height: 120px !important;
        resize: vertical !important;
    }
    
    /* 카드 스타일 모바일 최적화 */
    #eworks_viewmodal .card {
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    
    #eworks_viewmodal .card-body {
        padding: 12px !important;
    }
    
    /* approvalTable 모바일 최적화 */
    #eworks_viewmodal #approvalTable {
        width: 100% !important;
        margin-bottom: 12px !important;
    }
    
    #eworks_viewmodal #approvalTable table {
        width: 100% !important;
        font-size: 12px !important;
    }
    
    #eworks_viewmodal #approvalTable td,
    #eworks_viewmodal #approvalTable th {
        padding: 8px 6px !important;
        font-size: 12px !important;
        word-break: break-word !important;
    }
    
    /* 버튼 모바일 최적화 */
    #eworks_viewmodal .btn {
        min-height: 44px !important;
        font-size: 14px !important;
        padding: 8px 16px !important;
        border-radius: 6px !important;
    }
    
    #eworks_viewmodal .btn-sm {
        min-height: 36px !important;
        font-size: 13px !important;
        padding: 6px 12px !important;
    }
    
    /* 모바일에서 d-flex 요소 최적화 */
    #eworks_viewmodal .d-flex {
        flex-wrap: wrap !important;
    }
    
    #eworks_viewmodal .d-flex input {
        flex: 1 1 auto !important;
        min-width: 0 !important;
    }
    
    #eworks_viewmodal .d-flex button {
        flex: 0 0 auto !important;
        min-width: 44px !important;
    }
    
    /* 모바일에서 테이블을 카드 형태로 변환 */
    #eworks_viewmodal .table tbody {
        display: block !important;
    }
    
    #eworks_viewmodal .table tbody tr {
        display: block !important;
        width: 100% !important;
        margin-bottom: 16px !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        background: white !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
    }
    
    #eworks_viewmodal .table tbody tr td {
        display: block !important;
        width: 100% !important;
        padding: 12px !important;
        border: none !important;
        border-bottom: 1px solid #e9ecef !important;
        text-align: left !important;
    }
    
    #eworks_viewmodal .table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    #eworks_viewmodal .table tbody tr td.text-center {
        background-color: #667eea !important;
        color: white !important;
        font-weight: 700 !important;
        padding: 12px !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2) !important;
        text-align: center !important;
    }
    
    /* 테이블 내부의 입력 필드와 버튼 최적화 */
    #eworks_viewmodal .table tbody tr td .form-control {
        width: 100% !important;
        margin-top: 4px !important;
    }
    
    #eworks_viewmodal .table tbody tr td .d-flex {
        display: flex !important;
        gap: 8px !important;
        margin-top: 4px !important;
    }
    
    #eworks_viewmodal .table tbody tr td .d-flex input {
        flex: 1 !important;
    }
    
    #eworks_viewmodal .table tbody tr td .d-flex button {
        flex: 0 0 auto !important;
        min-width: 44px !important;
    }
    
    /* colspan 처리 */
    #eworks_viewmodal .table tbody tr td[colspan] {
        width: 100% !important;
    }
    
    /* approvalTable 모바일 최적화 추가 */
    #eworks_viewmodal #approvalTable {
        width: 100% !important;
        margin-bottom: 12px !important;
    }
    
    #eworks_viewmodal #approvalTable .table {
        width: 100% !important;
        font-size: 12px !important;
    }
    
    #eworks_viewmodal #approvalTable .table td,
    #eworks_viewmodal #approvalTable .table th {
        padding: 8px 6px !important;
        font-size: 12px !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    /* htmlContainer 모바일 최적화 */
    #eworks_viewmodal #htmlContainer {
        width: 100% !important;
    }
    
    #eworks_viewmodal #htmlContainer table {
        width: 100% !important;
        font-size: 13px !important;
    }
    
    #eworks_viewmodal #htmlContainer table td {
        padding: 8px !important;
        word-break: break-word !important;
    }
    
    /* 전자결재 상세 모달 backdrop - 리스트 모달 위에 표시 */
    #eworks_viewmodal + .modal-backdrop,
    .modal-backdrop.show:last-of-type {
        z-index: 9998 !important;
        background-color: rgba(0, 0, 0, 0.8) !important;
    }
    
    /* 전자결재 상세 모달이 열릴 때 리스트 모달 backdrop 숨기기 */
    body.modal-open #eworks_viewmodal.modal ~ .modal-backdrop,
    body.modal-open .modal-backdrop:not(:last-of-type) {
        display: none !important;
        visibility: hidden !important;
        pointer-events: none !important;
        z-index: 1040 !important;
        opacity: 0 !important;
    }
    
    /* 상세 모달이 열려있을 때 리스트 모달 backdrop 완전히 숨기기 */
    #eworks_viewmodal.modal.show ~ .modal-backdrop:not(:last-of-type),
    #eworks_viewmodal.modal.show + .modal-backdrop:not(:last-of-type) {
        display: none !important;
        visibility: hidden !important;
        pointer-events: none !important;
        opacity: 0 !important;
    }
    
    /* 전자결재 상세 모달이 열릴 때 리스트 모달 backdrop 위에 표시 */
    body.modal-open #eworks_viewmodal.modal {
        z-index: 9999 !important;
    }
    
    body.modal-open #eworks_viewmodal .modal-dialog {
        z-index: 9999 !important;
    }
    
    body.modal-open #eworks_viewmodal .modal-content {
        z-index: 9999 !important;
    }
    
    /* 리스트 모달의 backdrop이 상세 모달을 가리지 않도록 */
    #eworks_form.modal.show ~ .modal-backdrop:not(:last-of-type),
    .modal-backdrop.eworks-backdrop-hidden {
        display: none !important;
        visibility: hidden !important;
        pointer-events: none !important;
        opacity: 0 !important;
        z-index: 1040 !important;
    }
    
    #eworks_form .eworks-modal-content-custom {
        width: 100vw !important;
        max-width: 100vw !important;
        min-width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        min-height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        border-radius: 0 !important;
        border: none !important;
        display: flex !important;
        flex-direction: column !important;
        box-shadow: none !important;
        position: relative !important;
    }
    
    #eworks_form .modal-header {
        flex-shrink: 0;
        padding: 16px 20px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        position: sticky;
        top: 0;
        z-index: 10;
        border-radius: 0 !important;
    }
    
    #eworks_form .modal-title {
        color: white !important;
        font-weight: 700;
        font-size: 20px;
        margin: 0;
    }
    
    #eworks_form .modal-header .btn,
    #eworks_form .modal-header button {
        color: white !important;
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }
    
    #eworks_form .modal-header .btn:hover,
    #eworks_form .modal-header button:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    #eworks_form .modal-body {
        flex: 1 1 auto !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding: 12px !important;
        margin: 0 !important;
        -webkit-overflow-scrolling: touch;
        background: #f8f9fa !important;
        min-height: calc(100vh - 120px) !important; /* 헤더와 푸터 높이 제외 */
        max-height: calc(100vh - 120px) !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 1 !important;
    }
    
    #eworks_form .modal-body .card {
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    #eworks_form .modal-body .card-body {
        padding: 12px !important;
    }
    
    #eworks_form #eworks_list {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        min-height: 400px !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    #eworks_form #eworks_list table {
        display: table !important;
        visibility: visible !important;
        width: 100% !important;
    }
    
    #eworks_form #eworks_list .table-responsive {
        display: block !important;
        visibility: visible !important;
        overflow-x: auto !important;
    }
    
    #eworks_form .modal-footer {
        flex-shrink: 0;
        padding: 12px 20px;
        border-top: 1px solid #e9ecef;
        background: #ffffff;
        justify-content: center;
    }
    
    #eworks_form .modal-footer .btn {
        min-width: 100px;
        min-height: 44px;
        font-size: 15px;
    }
    
    /* 전자결재 화면 내부 요소 모바일 최적화 */
    #eworks_form .card,
    #eworks_form .card-body {
        padding: 12px !important;
        margin-bottom: 12px !important;
    }
    
    #eworks_form .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }
    
    #eworks_form .table {
        font-size: 13px !important;
        min-width: 100% !important;
        width: 100% !important;
        table-layout: fixed !important;
    }
    
    /* 모바일에서 테이블 헤더 두 줄 스타일 */
    #eworks_form .table thead .d-md-none {
        display: table-row !important;
    }
    
    #eworks_form .table thead .d-md-none th {
        padding: 10px 6px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        line-height: 1.4 !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        word-break: keep-all !important;
        vertical-align: middle !important;
        text-align: center !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    
    /* 모바일 헤더 첫 번째 행 */
    #eworks_form .table thead .d-md-none:first-of-type th {
        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    
    /* 모바일 헤더 두 번째 행 */
    #eworks_form .table thead .d-md-none:last-of-type th {
        border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    
    /* 모바일에서 체크박스 셀 (rowspan) */
    #eworks_form .table thead .d-md-none th[rowspan] {
        vertical-align: middle !important;
        padding: 15px 6px !important;
    }
    
    /* 모바일 버튼 스타일 */
    #eworks_form .table thead .d-md-none .btn-sm-mobile {
        padding: 4px 8px !important;
        font-size: 11px !important;
        min-height: 32px !important;
    }
    
    #eworks_form .table th,
    #eworks_form .table td {
        padding: 10px 6px !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        font-size: 13px !important;
        line-height: 1.5 !important;
    }
    
    /* 모바일에서 테이블 데이터 행 스타일 */
    /* 모바일에서 데이터 행 스타일 */
    #eworks_form .table tbody .d-md-none {
        display: table-row !important;
    }
    
    #eworks_form .table tbody .d-md-none td {
        padding: 10px 6px !important;
        font-size: 12px !important;
        vertical-align: middle !important;
        border: 1px solid #dee2e6 !important;
        word-wrap: break-word !important;
        word-break: keep-all !important;
        line-height: 1.5 !important;
        cursor: pointer !important;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1) !important;
        touch-action: manipulation !important;
        user-select: none !important;
        -webkit-user-select: none !important;
    }
    
    /* 클릭 가능한 셀 스타일 (체크박스와 버튼 제외) */
    #eworks_form .table tbody .d-md-none td[onclick] {
        cursor: pointer !important;
        position: relative !important;
    }
    
    /* 클릭 가능한 셀에 호버 효과 (모바일에서도 터치 피드백) */
    #eworks_form .table tbody .d-md-none td[onclick]:active {
        background-color: #f0f0f0 !important;
    }
    
    /* 모바일에서 첫 번째 컬럼 (체크박스, rowspan) */
    #eworks_form .table tbody .d-md-none td[rowspan] {
        width: 5% !important;
        vertical-align: middle !important;
        padding: 15px 6px !important;
    }
    
    /* 모바일 첫 번째 행의 컬럼들 */
    #eworks_form .table tbody .d-md-none:first-of-type td:nth-child(2) {
        width: 18% !important;
    }
    
    #eworks_form .table tbody .d-md-none:first-of-type td:nth-child(3) {
        width: 22% !important;
    }
    
    #eworks_form .table tbody .d-md-none:first-of-type td:nth-child(4) {
        width: 18% !important;
    }
    
    #eworks_form .table tbody .d-md-none:first-of-type td:nth-child(5) {
        width: 18% !important;
    }
    
    #eworks_form .table tbody .d-md-none:first-of-type td:nth-child(6) {
        width: 19% !important;
    }
    
    /* 모바일 두 번째 행의 컬럼들 */
    #eworks_form .table tbody .d-md-none:last-of-type td:first-child {
        width: 22% !important;
    }
    
    #eworks_form .table tbody .d-md-none:last-of-type td[colspan] {
        width: 45% !important;
    }
    
    #eworks_form .table tbody .d-md-none:last-of-type td:last-child {
        width: 33% !important;
    }
    
    /* PC용 데이터 행 스타일 */
    #eworks_form .table tbody .d-none.d-md-table-row td {
        padding: 10px 6px !important;
        font-size: 13px !important;
        vertical-align: middle !important;
    }
    
    #eworks_form .btn-sm {
        padding: 6px 12px !important;
        font-size: 13px !important;
        min-height: 36px;
    }
    
    #eworks_form .form-control,
    #eworks_form .form-select {
        font-size: 16px !important; /* iOS 줌 방지 */
        min-height: 44px !important;
    }
    
    #eworks_form .d-flex {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    /* 검색 영역 모바일 최적화 */
    #eworks_form .eworks-search-bar {
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        gap: 6px !important;
        align-items: center !important;
        justify-content: center !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        padding: 4px 0 !important;
        width: 100% !important;
    }
    
    #eworks_form .eworks-search-bar > * {
        flex: 0 0 auto !important;
        margin: 0 !important;
        white-space: nowrap !important;
    }
    
    /* 전체 버튼 */
    #eworks_form #E_searchAllBtn {
        min-width: 50px !important;
        max-width: 50px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        flex-shrink: 0 !important;
    }
    
    /* 검색 입력 필드 */
    #eworks_form #EworksSearch {
        flex: 1 1 auto !important;
        min-width: 100px !important;
        max-width: none !important;
        width: auto !important;
        padding: 8px 12px !important;
        font-size: 14px !important;
    }
    
    /* 검색 버튼 */
    #eworks_form .eworks-search-btn {
        min-width: 44px !important;
        max-width: 44px !important;
        padding: 8px 10px !important;
        flex-shrink: 0 !important;
    }
    
    /* 작성 버튼 */
    #eworks_form .eworks-write-btn {
        min-width: 60px !important;
        max-width: 70px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        flex-shrink: 0 !important;
    }
    
    /* 모든 버튼 공통 스타일 */
    #eworks_form .eworks-search-bar .btn {
        min-height: 40px !important;
        height: 40px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    /* 탭 네비게이션 모바일 최적화 */
    #eworks_form #eworksNavContainer {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 12px;
    }
    
    /* 모바일 데이터 행 그룹 스타일 */
    #eworks_form .table tbody tr.d-md-none {
        border-bottom: 2px solid #e9ecef !important;
    }
    
    #eworks_form .table tbody tr.d-md-none:last-of-type {
        border-bottom: 2px solid #e9ecef !important;
    }
    
    /* 모바일에서 데이터 행 첫 번째 행 */
    #eworks_form .table tbody .d-md-none:first-of-type td {
        border-bottom: 1px solid #e9ecef !important;
    }
    
    /* 모바일에서 데이터 행 두 번째 행 */
    #eworks_form .table tbody .d-md-none:last-of-type td {
        border-top: 1px solid #e9ecef !important;
    }
    
    /* 모바일 체크박스 동기화를 위한 스타일 */
    #eworks_form .table tbody .d-md-none td[rowspan] .checkItem {
        width: auto !important;
    }
}

/* 모바일 전자결재 화면 모달 스타일 (커스텀 모달용) */
.eworks-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    max-width: 100vw;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    z-index: 1050;
    opacity: 0;
    transition: opacity 0.3s ease;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    padding: 0;
    margin: 0;
}

.eworks-modal-overlay.active {
    display: flex;
    flex-direction: column;
    opacity: 1;
}

.eworks-modal-container {
    position: relative;
    width: 100vw !important;
    max-width: 100vw !important;
    min-width: 100vw !important;
    min-height: 100vh;
    margin: 0 !important;
    padding: 0 !important;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    animation: slideUp 0.3s ease;
    box-sizing: border-box;
    left: 0 !important;
    right: 0 !important;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

.eworks-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.eworks-modal-title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: white;
}

.eworks-modal-close {
    background: transparent;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.eworks-modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.eworks-modal-close:active {
    transform: scale(0.95);
}

.eworks-modal-nav {
    background: #ffffff;
    border-bottom: 1px solid #e9ecef;
    padding: 12px 8px;
    position: sticky;
    top: 64px;
    z-index: 9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.eworks-modal-nav .nav {
    flex-wrap: wrap;
    justify-content: center;
    margin: 0;
}

.eworks-modal-nav .nav-item {
    flex: 0 0 calc(50% - 4px);
    max-width: calc(50% - 4px);
    margin: 2px;
}

.eworks-modal-nav .nav-link {
    font-size: 13px;
    padding: 8px 6px;
    margin: 0;
    cursor: pointer;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.eworks-modal-nav .nav-link i {
    font-size: 14px;
    flex-shrink: 0;
}

.eworks-modal-nav .nav-link.active {
    background-color: #667eea;
    color: white !important;
    border-radius: 6px;
}

.eworks-modal-body {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    padding: 0;
    margin: 0;
    background: #f8f9fa;
    width: 100%;
    max-width: 100vw;
    box-sizing: border-box;
}

.eworks-list-container {
    width: 100%;
    max-width: 100vw;
    height: auto;
    min-height: calc(100vh - 64px);
    padding: 16px;
    box-sizing: border-box;
    margin: 0;
}

/* 모바일에서 전자결재 테이블 및 컨텐츠 반응형 */
@media (max-width: 768px) {
    .eworks-list-container .table-responsive,
    .eworks-list-container table {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 14px;
    }
    
    .eworks-list-container .table th,
    .eworks-list-container .table td {
        padding: 8px 4px;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .eworks-list-container .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    /* 전자결재 화면 내부 요소들 모바일 최적화 */
    .eworks-list-container .card,
    .eworks-list-container .card-body {
        padding: 12px;
        margin-bottom: 12px;
    }
    
    .eworks-list-container .form-control,
    .eworks-list-container .form-select {
        font-size: 16px;
        min-height: 44px;
    }
    
    .eworks-list-container .d-flex {
        flex-wrap: wrap;
        gap: 8px;
    }
}

/* PC에서는 모달 숨김 */
@media (min-width: 769px) {
    .eworks-modal-overlay {
        display: none !important;
    }
    
    /* PC에서는 원래 위치에 표시 */
    .eworks-list-container {
        width: 100%;
        max-width: none;
        padding: 20px;
    }
    
    /* PC에서는 모달 컨테이너 초기화 */
    .eworks-modal-container {
        width: auto !important;
        max-width: none !important;
        min-width: auto !important;
    }
}

 /* 모바일 전용 스타일 강제 적용 */
@media (max-width: 768px) {
    body.modal-open {
        overflow: hidden !important;
        position: fixed !important;
        width: 100% !important;
    }
    
    .eworks-modal-overlay.active {
        position: fixed !important;
        width: 100vw !important;
        max-width: 100vw !important;
        left: 0 !important;
        right: 0 !important;
        top: 0 !important;
        bottom: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .eworks-modal-container {
        width: 100vw !important;
        max-width: 100vw !important;
        min-width: 100vw !important;
        margin: 0 !important;
        padding: 0 !important;
        left: 0 !important;
        right: 0 !important;
        border-radius: 0 !important;
    }
    
    .eworks-modal-body {
        width: 100vw !important;
        max-width: 100vw !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .eworks-list-container {
        width: 100vw !important;
        max-width: 100vw !important;
        padding: 12px !important;
        box-sizing: border-box !important;
        margin: 0 !important;
    }
    
    /* 전자결재 화면 내부 요소들 모바일 최적화 */
    .eworks-list-container * {
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .eworks-list-container .container,
    .eworks-list-container .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
}

/* 사이드바 오버레이 */
.eworks-sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px); /* 배경 블러 효과 */
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.3s ease;
    cursor: pointer;
}

.eworks-sidebar-overlay.active {
    display: block;
    opacity: 1;
}

/* 모바일에서 오버레이 애니메이션 개선 */
@media (max-width: 768px) {
    .eworks-sidebar-overlay {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
    }
}
</style>

<!-- 사이드바 오버레이 -->
<div class="eworks-sidebar-overlay" id="eworksSidebarOverlay" onclick="toggleEworksSidebar()"></div>

<!-- 달력 일자에 대한 모달 -->
<div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dayModalLabel">날짜별 상세보기</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <!-- 데이터가 동적으로 삽입됩니다 -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<!-- 전자결재용 폼 -->
<form id="eworks_board_form" name="eworks_board_form" method="post">
	<input type="hidden" id="eworks_user_id" name="user_id" value="<?=$user_id?>" >
	<input type="hidden" id="eworks_user_name" name="user_name" value="<?=$user_name?>" >
</form>

<!-- 전자결재 로딩 인디케이터 -->
<div id="loadingIndicator" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 15px; padding: 40px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; border-width: 0.3em;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="mt-3 mb-2" style="color: #113366;">자료를 조회중입니다...</h5>
        <p class="text-muted mb-0">잠시만 기다려주세요</p>
    </div>
</div>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data" >
	<input type="hidden" id="searchOpt" name="searchOpt" value="<?=$searchOpt?>" >
	<input type="hidden" id="partOpt" name="partOpt" value="<?=$partOpt?>" >
	<input type="hidden" id="page" name="page" value="<?=$page?>" >
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >
	<input type="hidden" id="partsep" name="partsep" value="<?=$partsep?>" >
	<input type="hidden" id="num" name="num" value="<?=$num?>" >

	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>" >
	<input type="hidden" id="choice" name="choice" value="<?=$choice?>" >    <!-- 전자결재 진행상태  draft send -->
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" >
	<input type="hidden" id="approval_right" name="approval_right" value="<?=$approval_right?>" >
	<input type="hidden" id="status" name="status" value="<?=$status?>" >
	<input type="hidden" id="done" name="done" value="<?=$done?>" >    <!-- 전자결재 진행상태  done -->
	<input type="hidden" id="user_id" name="user_id" value="<?=$user_id?>" >    <!-- 전자결재 진행상태  done -->
	
 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-xxl">     
	<?php } ?>
		
<!-- 하루동안 표시하지 않기 팝업창 (팝업 내용은 필요에 따라 수정) -->
<div id="dailyPopup" style="display:none; position: fixed; bottom: 20px; right: 20px; width: 400px; z-index: 1050;">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>알림</strong>
      <!-- 우측 상단 x 버튼도 필요하면 추가 -->
      <button type="button" class="btn-close" aria-label="닫기" id="closeDailyPopupX"></button>
    </div>
	<div class="card-body">
		<div class="justify-content-center fs-5" >
			<?= str_replace(',', '', $popupContents) ?>

		</div>

	</div>
    <div class="card-footer text-end">
      <!-- 왼쪽에 '닫기' 버튼 -->
      <button type="button" class="btn btn-secondary btn-sm me-2" id="closeDailyPopup">닫기</button>
      <!-- 우측 하단 '오늘 하루동안 표시하지 않기' 버튼 (x마크 포함) -->
      <button type="button" class="btn btn-danger btn-sm" id="hideToday">
        오늘 하루동안 표시하지 않기 <span>&times;</span>
      </button>
    </div>
  </div>
</div>

<!-- Modern Toolbar Section -->
<div class="modern-toolbar-container">
<div class="row d-flex mb-2">
	<div class="col-sm-4">
		<div class="d-flex justify-content-start align-items-center">
			<button type="button" id="board_view" class="modern-toolbar-btn modern-toolbar-btn-primary">
				<i class="bi bi-chevron-down"></i>
			</button>
			<?php if($_SESSION["level"] && intval($_SESSION["level"]) < 8): ?>
			<!-- 슬라이드 토글 스위치: 경영표시 -->
			<div class="form-check form-switch mx-2" style="display: flex; align-items: center;">
				<input class="form-check-input" type="checkbox" id="toggleManagementInfo" style="width: 2.5em; height: 1.3em;">
				<label class="form-check-label shop-header mx-2" for="toggleManagementInfo" style="font-size: 1rem; color:black;">					
				</label>
			</div>
			<?php endif; ?>
			<?php if($chkMobile == false): ?>
            <button type="button" class="modern-toolbar-btn modern-toolbar-btn-info"
                    onclick="popupCenter('<?= getBaseUrl() ?>/cost/calamount.php?menu=no', '', 1000, 800); return false;"
					title="원자재 가격계산기">
				<i class="bi bi-calculator-fill"></i>
			</button>
            <button type="button" class="modern-toolbar-btn modern-toolbar-btn-info"
                    onclick="popupCenter('<?= getBaseUrl() ?>/cost/list.php?menu=no&firstItem=304 HL', '', 1600, 800); return false;"
					title="원자재 가격동향">
				<i class="bi bi-bar-chart-fill"></i>
			</button>
			<button type="button" class="modern-toolbar-btn modern-toolbar-btn-info"
					onclick="popupCenter('https://finance.naver.com/marketindex/exchangeDailyQuote.nhn?marketindexCd=FX_USDKRW', '', 750, 500); return false;"
					title="원달러 환율">
				<i class="bi bi-currency-dollar"></i>
			</button>
            <button type="button" class="modern-toolbar-btn modern-toolbar-btn-info"
                    onclick="popupCenter('<?= getBaseUrl() ?>/ceiling/showcatalog.php', '', 1400, 900); return false;"
					title="천장 카다로그">
				<i class="bi bi-journal-check"></i>
			</button>
			<?php endif; ?>			
			<button type="button" class="modern-social-btn"
					onclick="popupCenter('https://www.youtube.com/@miraecorp', '', 1920, 1080); return false;"
					title="미래기업 유튜브">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 26" width="34" height="26" style="vertical-align:middle;">
					<rect width="34" height="26" rx="5" ry="5" fill="#FF0000"/>
					<polygon points="10,5 10,20 25,15" fill="#FFFFFF"/>
				</svg>
			</button>
			<button type="button" class="modern-social-btn"
					onclick="popupCenter('https://www.instagram.com/miraecompany2025/', 'Instagram', 1280, 900); return false;"
					title="미래기업 인스타그램">
				<img src="https://ko.savefrom.net/img/articles/instagram/new/instagram.webp" width="30" height="30" style="vertical-align:middle;">
			</button>
		</div>
		
	</div>		
	<div class="col-sm-4">
		<div class="d-flex justify-content-center align-items-center"> 	
			<span class="fw-bold shop-header fs-5" > 2025 을사년(푸른뱀의 해) </span> 	
		</div>
	</div>		
	<div class="col-sm-4">
		<div class="d-flex justify-content-end align-items-center">
			<span style="font-size: 0.75rem; color: var(--dashboard-text-secondary); margin-right: 0.5rem;">코딩강의</span>
            <button type="button" class="modern-toolbar-btn modern-toolbar-btn-primary"
                    onclick="popupCenter('<?= getBaseUrl() ?>/school/index.php', '', 1920, 1080); return false;"
					title="웹코딩 강좌">
				<i class="bi bi-app-indicator"></i>
			</button>
			<span style="font-size: 0.75rem; color: var(--dashboard-text-secondary); margin: 0 0.5rem 0 1rem;">코딩퀴즈</span>
            <button type="button" class="modern-toolbar-btn modern-toolbar-btn-primary"
                    onclick="popupCenter('<?= getBaseUrl() ?>/quiz/index.php', '', 1920, 1080); return false;"
					title="웹코딩 퀴즈">
				<i class="bi bi-person-raised-hand"></i>
			</button>
		</div>
	</div>
</div>

<div  id="managementInfo">
	<div class="row d-flex" style="padding:0;">		  
	<!-- 전일 경영 Report --> 
	<div class="col-sm-3 board_list" style="padding:7;">		
		<div class="modern-management-card">
			<div class="modern-dashboard-header d-flex justify-content-center align-items-center">
				<span>📊 전일 경영 현황 <span style="font-size: 0.7rem; opacity: 0.9;">(단위: 원, SET)</span></span>
			</div>		
			<!-- 매출 현황 테이블 -->
			<table class="modern-dashboard-table modern-table-spacing">
				<thead>
					<tr>
						<th style="width: 25%;">구분</th>
						<th style="width: 35%;">매출</th>
						<th style="width: 40%;">출고내역</th>
					</tr>
				</thead>
				<tbody>
					<tr class="clickable-row" onclick="location.href='./work/output_statis.php';">
						<td class="text-center">
							<span class="modern-data-value" style="color: #059669; font-weight: 600;">Jamb</span>
						</td>
						<td class="text-end">
							<span id="jambearning" class="modern-data-value"></span>
						</td>
						<td class="text-start">
							<span id="prejamblist" class="modern-data-details"></span> 
						</td>
					</tr>
					<tr class="clickable-row" onclick="location.href='./ceiling/output_statis.php';">
						<td class="text-center">
							<span class="modern-data-value" style="color: #0288d1; font-weight: 600;">천장</span>
						</td>
						<td class="text-end">
							<span id="lcearning" class="modern-data-value"></span>
						</td>
						<td class="text-start">
							<span id="preceilinglist" class="modern-data-details"></span>
						</td>
					</tr>
				</tbody>
			</table>

			<!-- 원자재 현황 테이블 -->
			<table class="modern-dashboard-table">
				<tbody>
					<tr class="clickable-row" onclick="location.href='./steel/list.php';">
						<td class="text-center" style="width: 25%;">
							<span class="modern-data-value" style="color: #64748b; font-weight: 600;">자재</span>
						</td>
						<td class="text-end" style="width: 25%;">
							<span class="modern-data-value"><?=number_format($used)?></span>
						</td>
						<td class="text-center" style="width: 25%;">
							<span class="modern-data-value" style="color: #374151; font-weight: 600;">절약</span>
						</td>
						<td class="text-end" style="width: 25%;">
							<span class="modern-data-value"><?=number_format($saved)?></span>
						</td>
					</tr>
				</tbody> 
			</table>
			</div> <!-- 경영정보 끝 -->
		
	<!-- 전일 수주내역 -->
	<div class="modern-management-card mt-1">
	<div class="modern-dashboard-header">
		📈 전일 수주내역 <span style="font-size: 0.7rem; opacity: 0.9;">(단위:원, SET)</span>
	</div>

	<!-- 수주 현황 테이블 --> 
	<table class="modern-dashboard-table">
		<thead>
		<tr>
			<th style="width: 20%;">구분</th>
			<th style="width: 25%;">수주</th>
			<th style="width: 45%;">수주내역</th>
			<th style="width: 10%;">📊</th>
		</tr>
		</thead>
		<tbody>
		<!-- Jamb 행: 클릭 시 월별 수주내역 팝업 -->
		<tr class="clickable-row"
			onclick="popupCenter('<?= getBaseUrl() ?>/graph/monthly_jamb.php','Jamb 월별 수주내역', 1500, 900); return false;">
			<td class="text-center">
			<span class="modern-data-value" style="color: #059669; font-weight: 600;">Jamb</span>
			</td>
			<td class="text-end">
			<span id="beforedayjamb" class="modern-data-value"><?= isset($beforedayjamb) ? number_format($beforedayjamb) : '' ?></span>
			</td>
			<td class="text-start">
			<span id="beforedayjamblist" class="modern-data-details"><?= $beforedayjamblist ?></span>
			</td>
			<td class="text-center">
			<span style="color: var(--dashboard-accent); font-size: 1rem;">📊</span>
			</td>
		</tr>
		<!-- 천장 행: 클릭 시 월별 수주내역 팝업 -->
		<tr class="clickable-row"
			onclick="popupCenter('<?= getBaseUrl() ?>/graph/monthly_ceiling.php','천장 월별 수주내역', 1500, 900); return false;">
			<td class="text-center">
			<span class="modern-data-value" style="color: #0288d1; font-weight: 600;">천장</span>
			</td>
			<td class="text-end">
			<span id="beforedayceiling" class="modern-data-value"><?= isset($beforedayceiling) ? number_format($beforedayceiling) : '' ?></span>
			</td>
			<td class="text-start">
			<span id="beforedayceilinglist" class="modern-data-details"><?= $beforedayceilinglist ?></span>
			</td>
			<td class="text-center">
			<span style="color: var(--dashboard-accent); font-size: 1rem;">📊</span>
			</td>
		</tr>
		</tbody>
	</table>
	</div>
		
		<!-- 식사 -->
		<div class="modern-management-card mt-1">
			<div class="modern-dashboard-header">
				🍽️ 식사 <span style="font-size: 0.7rem; opacity: 0.9;">(식사 현황)</span>
			</div>

			<!-- 식사 현황 테이블 -->
			<table class="modern-dashboard-table">
				<thead>
					<tr>
						<th style="width: 25%;">식사유형</th>
						<th style="width: 35%;">구분</th>
						<th style="width: 20%;">요청</th>
						<th style="width: 20%;">확인</th>
					</tr>
				</thead>
				<tbody>
					<tr class="clickable-row" onclick="window.location.href='./afterorder/index.php'">
						<td class="text-center">
							<span style="color: var(--dashboard-text); font-weight: 600;">
								<i class="bi bi-apple" style="color: #f59e0b; margin-right: 0.3rem;"></i>중식
							</span>
						</td>
						<td class="text-center">
							<span id="lunch_text" class="modern-data-value"></span>
						</td>
						<td class="text-center">
							<span id="text5" class="modern-data-value" style="color: #10b981; font-weight: 600;"></span>
						</td>
						<td class="text-center">
							<span id="lunch_done" class="modern-data-value" style="color: #0288d1; font-weight: 600;"></span>
						</td>
					</tr>
					<tr class="clickable-row" onclick="window.location.href='./afterorder/index.php'">
						<td class="text-center">
							<span style="color: var(--dashboard-text); font-weight: 600;">
								<i class="bi bi-apple" style="color: #ef4444; margin-right: 0.3rem;"></i>석식
							</span>
						</td>
						<td class="text-center">
							<span id="dinner_text" class="modern-data-value"></span>
						</td>
						<td class="text-center">
							<span id="text6" class="modern-data-value" style="color: #ef4444; font-weight: 600;"></span>
						</td>
						<td class="text-center">
							<span id="supper_done" class="modern-data-value" style="color: #0288d1; font-weight: 600;"></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>        
		
		
		<!-- 금일 연차/경조사 -->
		<div class="modern-management-card mt-1" style="cursor:pointer;" onclick="window.location.href='./annualleave/index.php'">
			<div class="modern-dashboard-header">
				🏖️ <a href="./annualleave/index.php" style="color: white; text-decoration: none;">금일 연차/경조사</a>
				<span style="font-size: 0.7rem; opacity: 0.9;">(휴가 현황)</span>
			</div>
			<div style="padding: 0.1rem;">
				<?php
				// 금일 연차인 사람 나타내기
				$now = date("Y-m-d",time()) ;

				$sql = "SELECT * FROM mirae8440.eworks WHERE (al_askdatefrom <= CURDATE() AND al_askdateto >= CURDATE())  AND is_deleted IS NULL ";
				$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
				$total_row = $stmh->rowCount();
				?>
				<?php if ($total_row > 0): ?>
					<div style="padding: 0.1rem;">
						<?php include "./load_aldisplay.php"; ?>
					</div>
				<?php else: ?>
					<div style="text-align: center;
								color: var(--dashboard-text-secondary);
								font-size: 0.9rem;
								padding: 1rem;">
						오늘은 연차/경조사가 없습니다.
					</div>
				<?php endif; ?>
			</div>
		</div>
		<!-- 일용직 근무 -->
		<div class="modern-management-card mt-1" style="cursor:pointer;" onclick="window.location.href='./daylaborer/index.php'">
			<div class="modern-dashboard-header">
				👷 금일 일용직 근무 <span style="font-size: 0.7rem; opacity: 0.9;">(일용직 근무 현황)</span>
			</div>
			<div style="padding: 0.1rem;">
				<?php
					$now = date("Y-m-d", time());
					$a = " WHERE askdatefrom='$now' ORDER BY num DESC";
					$sql = "SELECT * FROM mirae8440.daylaborer" . $a;

					$stmh = $pdo->query($sql);
					$total_row = $stmh->rowCount();

					if ($total_row > 0): ?>
						<table class="modern-dashboard-table">
							<tbody>
							<?php
								$currentDate = new DateTime();
								echo "<thead> <tr>";
								echo "<th style='width: 25%;'> 성명 </th>";
								echo "<th style='width: 25%;'> 진행상태 </th>";
								echo "<th style='width: 25%;'> 비고 </th>";
								echo "</tr> </thead>";
								echo "<thead> <tr>";

								while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
									echo '<tr>';
									echo '<td class="text-center" style="width: 25%;">' . htmlspecialchars($row["labor_name"]) . '</td>';

									$class = "text-center";
									$class .= ($row["state"] == '요청') ? " badge bg-success" : " badge bg-primary";

									echo '<td class="text-center" style="width: 25%;"> &nbsp; <span class="' . $class . '"> ' . htmlspecialchars($row["state"]) . ' </span> </td>';
									echo '<td class="text-center" style="width: 25%;"> &nbsp; ' . htmlspecialchars($row["part"]) . '</td>';
									echo '</tr>';
								}
							?>
							</tbody>
						</table>
					<?php else: ?>
						<div style="text-align: center;
									color: var(--dashboard-text-secondary);
									font-size: 0.9rem;
									padding: 1rem;">
							오늘은 일용직 근무가 없습니다.
						</div>
					<?php endif; ?>
			</div>
		</div> 

	<!-- 12개월 jamb 매출추이  -->	
	<div class="card justify-content-center" style="cursor:pointer;" onclick="window.location.href='./work/output_statis.php'">		
		<div class="card-body my-card-padding">	
			<?php include "./load_statistics_jamb.php"; ?>
		</div>   
	</div>   
		
	<!-- 12개월 ceiling 매출추이  -->	
	<div class="card justify-content-center" style="cursor:pointer;" onclick="window.location.href='./ceiling/output_statis.php'">		
		<div class="card-body my-card-padding">	
			<?php include "./load_statistics_ceiling.php"; ?>
		</div>   
	</div>   
		
	</div>  <!-- end of col-sm-4 -->

	<div class="col-sm-3 board_list" style="padding:7;">		
		
		<!-- 금일 접수/출고 현황 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				📋 금일 접수/출고 현황 <span style="font-size: 0.7rem; opacity: 0.9;">(오늘 작업 현황)</span>
			</div>

			<!-- 접수/출고 현황 테이블 -->
			<table class="modern-dashboard-table">
				<thead>
					<tr>
						<th style="width: 25%;">구분</th>
						<th style="width: 25%;">접수</th>
						<th style="width: 25%;">출고예정</th>
						<th style="width: 25%;">출고완료</th>
					</tr>
				</thead>
				<tbody>
					<tr class="clickable-row" onclick="window.location.href='./work/list.php'">
						<td class="text-center">
							<span style="color: var(--dashboard-text); font-weight: 600;">
								JAMB
							</span>
						</td>
						<td class="text-center">
							<span id="jamb_registedate" class="modern-data-value"></span>
						</td>
						<td class="text-center">
							<span id="jamb_duedate" class="modern-badge modern-badge-jamb" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;"></span>
						</td>
						<td class="text-center">
							<span id="jamb_outputdonedate" class="modern-badge modern-badge-material" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;"></span>
						</td>
					</tr>
					<tr class="clickable-row" onclick="window.location.href='./ceiling/list.php'">
						<td class="text-center">
							<span style="color: var(--dashboard-text); font-weight: 600;">
								천장
							</span>
						</td>
						<td class="text-center">
							<span id="ceiling_registedate" class="modern-data-value"></span>
						</td>
						<td class="text-center">
							<span id="ceiling_duedate" class="modern-badge modern-badge-ceiling" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;"></span>
						</td>
						<td class="text-center">
							<span id="ceiling_outputdonedate" class="modern-badge modern-badge-material" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;"></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>				            	
			
		<!-- 구매 및 외주 -->
		<div class="modern-management-card mt-1">
			<div class="modern-dashboard-header">
				🛒 구매 및 외주 <span style="font-size: 0.7rem; opacity: 0.9;">(발주 현황)</span>
			</div>
			<div class="modern-card-body">
				<table class="modern-dashboard-table">
					<thead>
						<tr>
						<th class="text-center"> 구분 </th>
						<th class="text-center"> 요청 </th>
						<th class="text-center"> 발주보냄 </th>
						<th class="text-center"> 입고완료 </th>
						</tr>
					</thead>
					<tbody>
						<tr onclick="window.location.href='./request/list.php'" style="cursor:pointer;">
							<td class="text-center">
							<span class="modern-category-text"><i class="bi bi-bag-fill"></i>  원자재  </span>
							</td>
							<td class="text-center">
							<span class="modern-data-value" id="text1"></span>
							</td>
							<td class="text-center">
							<span class="modern-data-value" id="text8"></span>
							</td>
							<td class="text-center">
							<span class="modern-data-value" id="steel_done"></span>
							</td>
						</tr>
						<tr onclick="window.location.href='./request/list.php'" style="cursor:pointer;">
							<td class="text-center">
							<span class="modern-category-text"><i class="bi bi-cart-dash"></i>  부자재  </span>
							</td>
							<td class="text-center">
							<span class="modern-data-value" id="text2"></span>
							</td>
							<td class="text-center">
							<span class="modern-data-value" id="text7"></span>
							</td>
							<td class="text-center">
							<span class="modern-data-value" id="etc_done"></span>
							</td>
						</tr>

						<tr onclick="window.location.href='./outorder/list.php';" style="cursor:pointer;">
							<td class="text-center">
								<span class="modern-category-text">덴크리</span>
							</td>
							<td class="text-center">
								<span class="modern-data-value" id="dancre_registedate"></span>
							</td>
							<td class="text-center">
								<span class="modern-data-value" id="dancre_duedate"></span>
							</td>
							<td class="text-center">
								<span class="modern-data-value" id="dancre_outputdonedate"></span>
							</td>
						</tr>
						<tr onclick="window.location.href='./outorder/list.php';" style="cursor:pointer;">
							<td class="text-center">
								<span class="modern-category-text">다온텍</span>
							</td>
							<td class="text-center">
								<span class="modern-data-value" id="daontech_registedate"></span>
							</td>
							<td class="text-center">
								<span class="modern-data-value" id="daontech_duedate"></span>
							</td>
							<td class="text-center">
								<span class="modern-data-value" id="daontech_outputdonedate"></span>
							</td>
						</tr>
						<!--
							<tr>
								<td class="text-center">
									<span class="text-primary" > USD/KRW 환율 </span>
								</td>
								<td  class="text-end" colspan="3">
									<span id="currencyrate"  class="text-primary" >    </span>
								</td>
							</tr>
							-->
					</tbody>
				</table>												   
		</div> 	
		</div> 	
					
		<!-- 화물/택배 금일출고 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./delivery/list.php" style="color: white; text-decoration: none;">
					🚛 화물/택배 금일출고 <span style="font-size: 0.7rem; opacity: 0.9;">(배송 현황)</span>
				</a>
			</div>
		<?php
		//택배배송 관련
		$now = date("Y-m-d", time());
		
		$a = " WHERE registedate BETWEEN '$now' AND '$now' AND is_deleted IS NULL ORDER BY num DESC limit 7";

		$sql = "SELECT * FROM mirae8440.delivery" . $a;

		$stmh = $pdo->query($sql);
		$total_row = $stmh->rowCount();
		$delivery_count_today = $total_row; // JavaScript에서 사용할 변수

		// 현재 날짜를 DateTime 객체로 가져옵니다.
		$currentDate = new DateTime();
		if($total_row > 0) {
		?>
		<table class="modern-dashboard-table" id="deliveryTable1">
			<thead>
				<tr>
					<th class='text-center'> 순번 </th>
					<th class='text-center'> 품명/현장명 </th>
					<th class='text-center'> 받을분 </th>
				</tr>
			</thead>
			<tbody>
			<?php
					// 현재 날짜를 DateTime 객체로 가져옵니다.
					$currentDate = new DateTime();
					$start_num = 0;
					while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						$start_num ++ ;
						// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
						$item_num = $row["num"];


						print '<tr onclick="viewBoard(\'delivery\', ' .  $item_num  . ');" style="cursor:pointer;">';
						print '<td class="text-center">' . $start_num . '</td>';
							$text = mb_strlen($row["item_name"], 'UTF-8') > 10 ? mb_substr($row["item_name"], 0, 10, 'UTF-8') . '..' : $row["item_name"];
							$text = str_replace(',', '', $text);
						print '<td class="text-start blink-toggle" data-original-text="' . htmlspecialchars($text) . '"> &nbsp; ' . $text . '</td>';

						$text = mb_strlen($row["receiver"], 'UTF-8') > 20 ? mb_substr($row["receiver"], 0, 20, 'UTF-8') . '..' : $row["receiver"];
						$text = str_replace(',', '', $text);
						print '<td class="text-start"> &nbsp; ' . $text . '</td>';
						print '</tr>';
						}
				?>
				</tbody>
			</table>							 
			<?php 
				} 
					else {
						?>
						<div style="text-align: center;
									color: var(--dashboard-text-secondary);
									font-size: 0.9rem;
									padding: 1rem;">
							오늘은 화물/택배 금일출고가 없습니다.
						</div>						
						</tbody>
						</table>					
				<?php }	?>		
		</div>   	
				
		<!-- 도장 발주 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./make/list.php" style="color: white; text-decoration: none;">
					🎨 도장 발주 <span style="font-size: 0.7rem; opacity: 0.9;">(최근7건)</span>
				</a>
			</div>
		<?php
		//도장관련 글이 일주일에 해당되면
		$now = date("Y-m-d", time());
		
		// $oneWeekAgo = date("Y-m-d", strtotime("-1 week", strtotime($now)));			// 1주전 정보		
		$twentyDaysAgo = date("Y-m-d", strtotime("-20 days", strtotime($now)));  // 20일 전 정보	
		$fiveDaysAgo = date("Y-m-d", strtotime("-5 days", strtotime($now)));  // 5일 전 정보	
		$endOfDay = date("Y-m-d 23:59:59", time());
		$a = " WHERE indate BETWEEN '$twentyDaysAgo' AND '$endOfDay' ORDER BY num DESC limit 7";

		$sql = "SELECT * FROM mirae8440.make" . $a;

		$stmh = $pdo->query($sql);
		$total_row = $stmh->rowCount();	

		// 현재 날짜를 DateTime 객체로 가져옵니다.
		$currentDate = new DateTime();					
		if($total_row > 0) {
		?>			
		<table class="modern-dashboard-table">
			<tbody>
			<?php
					// 현재 날짜를 DateTime 객체로 가져옵니다.
					$currentDate = new DateTime();
					if($total_row > 0) {
						print "<thead> <tr>";
						print "<th class='text-center' style='width: 25%;'> 일자 </th>";
						print "<th class='text-center' style='width: 75%;'> 작업 내용 </th>";
						print "</tr> </thead>";

					while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
						$item_num = $row["num"];
						$printDate = date('m-d', strtotime($row["indate"]));
						$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';

						print '<tr onclick="viewBoard(\'paint\', ' .  $item_num  . ');" style="cursor:pointer;">';

						print '<td class="text-center">';
						print $printDate;
						print '</td>';

						// 최근 5일 이내인지 확인
						if($row["indate"] >= $fiveDaysAgo) {
							$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
						} else {
							$newImage = ''; // 이미지 표시 안 함
						}

						$text = mb_strlen($row["text"], 'UTF-8') > 20 ? mb_substr($row["text"], 0, 20, 'UTF-8') . '..' : $row["text"];
						$text = str_replace(',', '', $text);
						print '<td class="text-start"> &nbsp; ' . $newImage . $text . '</td>';

						print '</tr>';
						}
					}
				?> 
				</tbody>
			</table>							
			<?php } ?>
		</div>   	
				
			
	<!-- 직원 제안제도 현황 -->
	<div class="modern-management-card">
		<div class="modern-dashboard-header">
			<a href='./idea/index.php' style="color: white; text-decoration: none;">
				💡 직원 제안제도 현황 <span style="font-size: 0.7rem; opacity: 0.9;">(아이디어 현황)</span>
			</a>
		</div>
		<?php		
		$now = date("Y-m-d", time());		
		// 한 달 전 날짜를 계산
		$oneMonthAgo = date("Y-m-d", strtotime("-1 month", strtotime($now)));  
		$endOfDay = date("Y-m-d 23:59:59", time());
		
		// 최신 5개의 자료을 가져오기 위해 ORDER BY와 LIMIT 사용
		$a = " ORDER BY occur DESC LIMIT 10";

		$sql = "SELECT * FROM mirae8440.idea " . $a;

		try {
			$stmh = $pdo->query($sql);
			$total_row = $stmh->rowCount();	

			if($total_row > 0) {
		?>			
		<table class="modern-dashboard-table">
		<thead>
			<tr>
				<th class="text-center">일자</th>
				<th class="text-center">제안명</th>
				<th class="text-center">성명</th>
			</tr>
		</thead>
			<tbody>
			<?php
				while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
					$item_num = $row["num"];
					$occur = $row["occur"];
					$firstone = $row["firstone"];
					$occur_short = substr($occur, 5); // 'YYYY-MM-DD'에서 'MM-DD' 부분만 가져옴

					// 'occur' 날짜를 DateTime 객체로 변환
					$occurDate = new DateTime($row["occur"]);
					// 2주일 전
					$oneWeekAgo = date("Y-m-d", strtotime("-2 weeks", strtotime($now)));
					// 한 달 전 날짜를 DateTime 객체로 변환
					$oneMonthAgoDate = new DateTime($oneMonthAgo);
					$oneWeekAgoDate = new DateTime($oneWeekAgo);

					// 최근 한 달 이내인지 확인
					if($occurDate >= $oneWeekAgoDate) {
						$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
					} else {
						$newImage = ''; // 이미지 표시 안 함
					}

					echo '<tr onclick="viewBoard(\'idea\', ' .  htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8')  . ');" style="cursor:pointer;">';
					$text = mb_strlen($row["place"], 'UTF-8') > 10 ? mb_substr($row["place"], 0, 10, 'UTF-8') . '..' : $row["place"];
					$text = str_replace(',', '', $text);
					echo '<td class="text-center"> ' . $occur_short  . '</td>';
					echo '<td class="text-start"> &nbsp; ' . $newImage . '&nbsp;' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</td>';
					echo '<td class="text-center"> ' . $firstone  . '</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>							
		<?php 
			} else {
				// 자료가 없을 경우 메시지 표시
				echo '<div class="text-center">표시할 자료가 없습니다.</div>';
			}
		} catch (PDOException $e) {
			// 쿼리 실행 에러 처리
			echo '<div class="text-center text-danger">데이터를 불러오는 중 오류가 발생했습니다.</div>';
			// 실제 운영 환경에서는 에러 메시지를 노출하지 않도록 주의하세요.
			// 개발 시에는 아래 주석을 해제하여 디버깅할 수 있습니다.
			// echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
		}
		?>
	</div>
	
	<!-- 품질분임조활동 자료 -->
	<div class="modern-management-card">
		<div class="modern-dashboard-header">
			<a href='./errormeeting/index.php' style="color: white; text-decoration: none;">
				📋 품질분임조 개선활동 <span style="font-size: 0.7rem; opacity: 0.9;">(개선 활동)</span>
			</a>
		</div> 
		<?php		
		$now = date("Y-m-d", time());		
		// 한 달 전 날짜를 계산
		$oneMonthAgo = date("Y-m-d", strtotime("-1 month", strtotime($now)));  
		$endOfDay = date("Y-m-d 23:59:59", time());
		
		// 최신 5개의 자료을 가져오기 위해 ORDER BY와 LIMIT 사용
		$a = " ORDER BY occur DESC LIMIT 5";

		$sql = "SELECT * FROM mirae8440.emeeting " . $a;

		try {
			$stmh = $pdo->query($sql);
			$total_row = $stmh->rowCount();	

			if($total_row > 0) {
		?>			
		<table class="modern-dashboard-table">
			<tbody>
			<?php
				while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
					$item_num = $row["num"];

					// 'occur' 날짜를 DateTime 객체로 변환
					$occurDate = new DateTime($row["occur"]);
					// 한 달 전 날짜를 DateTime 객체로 변환
					$oneMonthAgoDate = new DateTime($oneMonthAgo);

					// 최근 한 달 이내인지 확인
					if($occurDate >= $oneMonthAgoDate) {
						$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
					} else {
						$newImage = ''; // 이미지 표시 안 함
					}

					echo '<tr onclick="viewBoard(\'errormeeting\', ' .  htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8')  . ');" style="cursor:pointer;">';
					$text = mb_strlen($row["method"], 'UTF-8') > 20 ? mb_substr($row["method"], 0, 20, 'UTF-8') . '...' : $row["method"];
					$text = str_replace(',', '', $text);
					echo '<td class="text-start"> &nbsp; ' . $newImage . '&nbsp;' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>							
		<?php 
			} else {
				// 자료가 없을 경우 메시지 표시
				echo '<div class="text-center">표시할 자료가 없습니다.</div>';
			}
		} catch (PDOException $e) {
			// 쿼리 실행 에러 처리
			echo '<div class="text-center text-danger">데이터를 불러오는 중 오류가 발생했습니다.</div>';
			// 실제 운영 환경에서는 에러 메시지를 노출하지 않도록 주의하세요.
			// 개발 시에는 아래 주석을 해제하여 디버깅할 수 있습니다.
			// echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
		}
		?>
	</div>
	</div>

	<!-- 품질/위험성평가 -->	
	<div class="col-sm-3 board_list" style="padding:7;"> 
			<!-- 미래기업 품질목표 -->

			<div class="d-flex justify-content-center align-items-center mb-1">
				<button type="button"
						class="modern-quality-goal-btn"
						onclick="popupCenter('./QC/goal.php?header=header', '미래기업 품질방침/품질목표', 1400, 900); return false;"
						title="미래기업 품질방침/품질목표">
					<i class="bi bi-gear-fill"></i>
					미래기업 품질방침/품질목표
				</button>
			</div>

		<!-- 당해년도 품질불량  -->	
		<div class="card justify-content-center" style="cursor:pointer;" onclick="location.href='./errors/statistics.php';">		
			<div class="card-body my-card-padding">	
				<?php include "./load_errorstatistics.php"; ?>		
			</div>   
		</div>   
		<!--  품질불량 세부내역(전체불량) -->
		<div class="modern-management-card" id="nonConformanceCost">
			<div class="modern-dashboard-header">
				<a href="./errors/statistics.php" style="color: white; text-decoration: none;">
					📊 전체불량율/세부내역 <span style="font-size: 0.7rem; opacity: 0.9;">(품질 분석)</span>
				</a>
			</div>
		<div class="modern-card-body">		   
				<?php $option = "option";
					include getDocumentRoot() . '/QC/rate_badAll.php';?>   
				<?php include getDocumentRoot() . '/QC/rate_badDetail.php'; ?>   			        
		</div>   
		</div>               
		
	</div>	<!-- end of col-sm-4 -->

	<!-- 공지 및 자료실 -->		
	<div class="col-sm-3 board_list" style="padding:7;">  			
		<!-- 전체 공지 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./notice/list.php" style="color: white; text-decoration: none;">
					📢 전체 공지 <span style="font-size: 0.7rem; opacity: 0.9;">(공지사항)</span>
				</a>
			</div>
				<div class="modern-card-body">					
				<?php   
				//전체 공지사항
				$now = date("Y-m-d",time()) ;				  
				$a="   where noticecheck='y' order by num desc ";  				  
				$sql="select * from mirae8440.notice " . $a; 		
				$stmh = $pdo->query($sql);
				$total_row = $stmh->rowCount();
				
				// 현재 날짜를 DateTime 객체로 가져옵니다.
				$currentDate = new DateTime();
				
				if($total_row > 0) {
					echo '<table class="modern-dashboard-table">';
					
					while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
						$dataDate = new DateTime($row["regist_day"]);
						
						// 날짜 차이를 계산합니다.
						$interval = $currentDate->diff($dataDate)->days;

						// 이미지 태그 초기화
						$newImage = '';

						// 7일 이내면 이미지를 추가합니다.
						if($interval < 7) {
							$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
						}
						
						$item_num = $row["num"]; 
						$sqlsub="select * from mirae8440.notice_ripple where parent=$item_num";
						$stmh1 = $pdo->query($sqlsub); 
						$num_ripple=$stmh1->rowCount(); 
						
						$echoPrint = mb_substr($row["subject"], 0, 25, "UTF-8");  

						// 데이터-속성 추가하여 공지의 ID 또는 필요한 정보를 저장
						print '<tr onclick="viewBoard(\'notice\', ' .  $item_num  . ');" style="cursor:pointer;"><td class="text-start"> &nbsp;  ' . $newImage . $echoPrint;

						if($num_ripple>0)
								echo ' &nbsp; <span class="modern-data-value"> ' . $num_ripple . ' </span> </td> ';
							else
								echo  '</td> ';

							echo '</tr>'; // 테이블 행 종료
						}
						echo '</table>';
					} else {
						echo '<span> &nbsp; </span>';
					}
					?>  
		</div>   
	</div> 

		<!-- 새소식 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./notice/list.php" style="color: white; text-decoration: none;">
					📰 새소식 <span style="font-size: 0.7rem; opacity: 0.9;">(뉴스)</span>
				</a>
			</div>
		<div class="modern-card-body">	
		<table class="modern-dashboard-table">
			<tbody>				     
			<?php   
			//공지사항
			$now = date("Y-m-d", time());

			// 1주일 전 날짜 계산
			$oneWeekAgo = date("Y-m-d", strtotime("-5 week", strtotime($now)));					
			$endOfDay = date("Y-m-d 23:59:59", time());
			// 전체 공지된 내용은 제외한다.
			$a = " WHERE regist_day BETWEEN '$oneWeekAgo' AND '$endOfDay' AND noticecheck<>'y' ORDER BY num DESC";
			$sql = "SELECT * FROM mirae8440.notice" . $a;

			$stmh = $pdo->query($sql);
			$total_row = $stmh->rowCount();


			// 현재 날짜를 DateTime 객체로 가져옵니다.
			$currentDate = new DateTime();					
			if($total_row > 0) {						
			print '<tr>';				
			print '<td class="align-middle text-center" rowspan="' . ($total_row) . '" style="width:20%;" onmouseover="this.style.backgroundColor=\'initial\';" onmouseout="this.style.backgroundColor=\'initial\';"> 공지 </td> ';

			while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
				$dataDate = new DateTime($row["regist_day"]);
				
				// 날짜 차이를 계산합니다.
				$interval = $currentDate->diff($dataDate)->days;

				// 이미지 태그 초기화
				$newImage = '';

				// 15일 이내면 이미지를 추가합니다.
				if($interval < 7) {
					$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
				}
				// 데이터-속성 추가하여 공지의 ID 또는 필요한 정보를 저장
				print '<td class="text-start" ';
				
				$item_num = $row["num"]; 
				$sqlsub="select * from mirae8440.notice_ripple where parent=$item_num";
				$stmh1 = $pdo->query($sqlsub); 
				$num_ripple=$stmh1->rowCount(); 
				$echoPrint = mb_substr($row["subject"], 0, 18, "UTF-8");  
				// 데이터-속성 추가하여 공지의 ID 또는 필요한 정보를 저장
				print '<span onclick="viewBoard(\'notice\', ' .  $item_num  . ');"> &nbsp;  ' . $newImage . $echoPrint . '</span> ';
				if($num_ripple>0)
						echo '<span class="modern-data-value"> '.$num_ripple.' </span> ';
				print '</span> </td> </tr>';
			}
			} 

			//자료실
			$now = date("Y-m-d", time());

			// // 1주일 전 날짜 계산
			$oneWeekAgo = date("Y-m-d", strtotime("-3 week", strtotime($now)));			// 1주전 정보		
			$endOfDay = date("Y-m-d 23:59:59", time());
			$a = " WHERE regist_day BETWEEN '$oneWeekAgo' AND '$endOfDay' ORDER BY num DESC";

			$sql = "SELECT * FROM mirae8440.qna" . $a;

			$stmh = $pdo->query($sql);
			$total_row = $stmh->rowCount();


			// 현재 날짜를 DateTime 객체로 가져옵니다.
			$currentDate = new DateTime();					
			if($total_row > 0) {						
			print '<tr>';				
			print '<td class="align-middle " rowspan="' . ($total_row) . '" style="width:15%;"  onmouseover="this.style.backgroundColor=\'initial\';" onmouseout="this.style.backgroundColor=\'initial\';"> <a href="../qna/list.php"> 자료실 </a> </td> ';					
			while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
				$dataDate = new DateTime($row["regist_day"]);
				
				// 날짜 차이를 계산합니다.
				$interval = $currentDate->diff($dataDate)->days;

				// 이미지 태그 초기화
				$newImage = '';

				// 7 이내면 이미지를 추가합니다.
				if($interval < 7) {
					$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
				}
				// 데이터-속성 추가하여 공지의 ID 또는 필요한 정보를 저장
				print '<td class="text-start" ';
				print ' onclick="viewBoard(\'qna\', ' . $row["num"] . ');">' . $newImage . $row["subject"] . '</td>';
				print '</tr>';
			}
			} 
			?>  
				</tbody>
				</table>
			</div>   
		</div>        
			
		<!-- 함께하는 의사결정(투표) --> 
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./vote/list.php" style="color: white; text-decoration: none;">
					🗳️ 함께하는 의사결정 <span style="font-size: 0.7rem; opacity: 0.9;">(직원투표)</span>
				</a>
			</div>
		<?php
		//투표관련 글이 일주일에 해당되면
		$now = date("Y-m-d", time());

		$oneWeekAgo = date("Y-m-d", strtotime("-120 week", strtotime($now)));			
		$endOfDay = date("Y-m-d 23:59:59", time());
		$a = " WHERE regist_day BETWEEN '$oneWeekAgo' AND '$endOfDay' ORDER BY num DESC";

		$sql = "SELECT * FROM mirae8440.vote" . $a;

		$stmh = $pdo->query($sql);
		$total_row = $stmh->rowCount();

		// 현재 날짜를 DateTime 객체로 가져옵니다.
		$currentDate = new DateTime();					
		if($total_row > 0) {
		?>
		
		<div class="modern-card-body">
		<?php   
		// 투표
		$now = date("Y-m-d",time());				  
		$a="   where noticecheck='y' order by num desc ";  				  
		$sql="select * from mirae8440.vote " . $a; 		
		$stmh = $pdo->query($sql);
		$total_row = $stmh->rowCount();

		// 현재 날짜를 DateTime 객체로 가져옵니다.
		$currentDate = new DateTime();

		if($total_row > 0) {
			echo '<table class="modern-dashboard-table">';

			while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				// 데이터 처리
				$dataDate = new DateTime($row["regist_day"]);
				$deadlineStr = new DateTime($row["deadline"]);
				$formattedDeadline = $deadlineStr->format('m월 d일');
				$interval = $currentDate->diff($deadlineStr)->days;

				// 이미지 태그 초기화
				$newImage = ''; 
				if($interval < 7) {
					$newImage = '<img src="./img/vote.gif" style="width:10%;" alt="Vote" /> &nbsp;';
				
				}
				// 테이블 행 시작
				echo '<tr onclick="viewBoard(\'vote\', ' . $row["num"] . ');" style="cursor:pointer;">';
				
				// 투표 제목
				$text = mb_strlen($row["subject"], 'UTF-8') > 12 ? mb_substr($row["subject"], 0, 12, 'UTF-8') . '..' : $row["subject"];

				echo '<td class="text-start fw-bold text-dark " style="width:120px;" >' . $newImage . $text . '</td>';

				// 마감일
				if($row["status"] !== '마감') {
					$deadlineImage = '<img src="./img/deadline.gif" style="width:50%;" alt="deadline" /> &nbsp;';
					echo '<td class="text-primary">' . $deadlineImage . $formattedDeadline . '</td>';
				}

				echo '</tr>'; // 테이블 행 종료
			}

			echo '</table>';
		} else {
			echo '<span> &nbsp; </span>';
		}
		
		?>  
		</div>
			<?php } ?>
			
			</div>               

		<!-- 추억 사진&영상 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./youtube.php" style="color: white; text-decoration: none;">
					📸 추억의 사진&영상 <span style="font-size: 0.7rem; opacity: 0.9;">(미디어)</span>
				</a>
			</div>

		<div class="modern-card-body">
			<div class="text-center mb-2">
				<span class="fw-bold shop-header fs-6">2025년 카페 자투라</span>
			</div>
				<div class="d-flex justify-content-center align-items-center mb-1">		
				<iframe width="240" height="135" src="https://www.youtube.com/embed/KEXDABu2_9Q" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> &nbsp;&nbsp;&nbsp;	
				</div>
				<div class="d-flex justify-content-center align-items-center">		
					<div class="photo-frame justify-content-center text-center">
						<?php
						for ($i = 1; $i <= 6; $i++) {
							// 사진 파일 경로 생성
							$photoPath = "img/trip/20251113_trip" . sprintf("%02d", $i) . ".jpg";
							// 사진 태그 출력
							echo '<img src="' . $photoPath . '" class="framed-photo">';
						}
						?>
					</div>
				</div>
			</div>
		</div>

		</div>	<!-- end of col-sm-4 -->
	<!-- Jamb 금일출고와 본천장/조명천장 출고를 나란히 표시 -->
	<div class="row d-flex" style="padding:0; margin-top: 10px;">
		<!-- Jamb 금일출고 -->
		<div class="col-sm-6 board_list" style="padding:7;">
			<div class="modern-management-card">
				<div class="modern-dashboard-header d-flex justify-content-center align-items-center">
					<span>🏭 Jamb 금일출고</span>
				</div>
				<div class="modern-card-body">
					<?php 
					$a="   where endworkday='$now' order by num desc ";  
					$sql="select * from mirae8440.work " . $a; 					
					$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
					$total_row=$stmh->rowCount();   
					include "./load_jamb.php";
					?>
				</div>
			</div>
		</div>

		<!-- 본천장/조명천장 출고 -->
		<div class="col-sm-6 board_list" style="padding:7;">
			<div class="modern-management-card">
				<div class="modern-dashboard-header d-flex justify-content-center align-items-center">
					<span>🏢 본천장/조명천장 출고</span>
				</div>
				<div class="modern-card-body">
					<?php 
					$a = " where deadline='$now' order by num desc ";    
					$sql="select * from mirae8440.ceiling " . $a; 					
					$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
					$total_row=$stmh->rowCount();
					include "./load_ceiling.php";
					?>
				</div>
			</div>
		</div>
	</div>
	</div>            	    
</div>    <!-- id="managementInfo" -->        	    

<div  id="NoneManagementInfo">
	<div class="row d-flex" style="padding:0;">		  

	<!-- 원자재(철판) 미입고 -->		
	<div class="col-sm-3 board_list" style="padding:7;">  			
		<!-- 원자재(철판) 미입고 간소화된 리스트 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./request/list.php" style="color: white; text-decoration: none;">
					🏭 원자재(철판) 미입고 <span style="font-size: 0.7rem; opacity: 0.9;"></span>
				</a>
			</div>
			<style>
				/* 원자재 카드용 테이블 셀 텍스트 줄바꿈 방지 및 말줄임표 처리 */
				.modern-dashboard-table td {
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
					max-width: 0;
					font-size: 0.8rem;
				}
				/* 진행 컬럼 - 배지가 잘리지 않도록 */
				.modern-dashboard-table td:nth-child(1) {
					max-width: 100px;
					min-width: 80px;
				}
				/* 현장명 컬럼 */
				.modern-dashboard-table td:nth-child(2) {
					max-width: 150px;
				}
				/* 종류/규격 컬럼 */
				.modern-dashboard-table td:nth-child(3) {
					max-width: 150px;
				}
			</style>
			<div class="modern-card-body">
				<?php
				// 원자재 미입고 간소화된 리스트
				$sql = "SELECT num, outworkplace, steel_item, spec, which, outdate, requestdate
						FROM mirae8440.eworks 
						WHERE eworks_item='원자재구매' AND (which != '3') AND is_deleted IS NULL 
						ORDER BY outdate DESC  ";
				$stmh = $pdo->query($sql);
				$total_row = $stmh->rowCount();
				?>
				
				<?php if ($total_row > 0): ?>
				<table class="modern-dashboard-table">
					<thead>
						<tr>
							<th style="width: 20%;">진행</th>
							<th style="width: 40%;">현장명</th>
							<th style="width: 40%;">종류/규격</th>
						</tr>
					</thead>
					<tbody>
						<?php while($row = $stmh->fetch(PDO::FETCH_ASSOC)): 
							$num = $row["num"];
							$outworkplace = $row["outworkplace"];
							$steel_item = $row["steel_item"];
							$spec = $row["spec"];
							$which = $row["which"];
							
							// 진행상태 설정
							switch ($which) {
								case "1":
									$status_badge = '<span class="badge bg-primary blink" style="font-size: 0.65rem; padding: 0.25em 0.5em;">요청</span>';
									break;
								case "2":
									$status_badge = '<span class="badge bg-secondary" style="font-size: 0.65rem; padding: 0.25em 0.5em;">보냄</span>';
									break;
								default:
									$status_badge = '<span class="badge bg-light text-dark" style="font-size: 0.65rem; padding: 0.25em 0.5em;">기타</span>';
									break;
							}
							
						?>
						<tr onclick="popupCenter('./request/view.php?menu=no&num=<?= $num ?>', '원자재 주문', 1700, 900);" style="cursor: pointer;">
							<td class="text-center"><?= $status_badge ?></td>
							<?php
								$display_outworkplace = htmlspecialchars($outworkplace);
								if(mb_strlen($outworkplace, 'UTF-8') > 8) {
									$display_outworkplace = mb_substr($outworkplace, 0, 8, 'UTF-8') . '..';
								}
							?>
							<td class="text-start" title="<?= htmlspecialchars($outworkplace) ?>"><?= $display_outworkplace ?></td>
							<td class="text-start" title="<?= htmlspecialchars($steel_item . ' ' . $spec) ?>"><?= htmlspecialchars($steel_item . ' ' . $spec) ?></td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				<?php else: ?>
				<div style="text-align: center; color: var(--dashboard-text-secondary); font-size: 0.9rem; padding: 1rem;">
					원자재(철판) 미입고 현황이 없습니다.
				</div>
				<?php endif; ?>
			</div>
		</div>

	</div>	<!-- end of col-sm-4 -->			
	
	
	<!-- 부자재 & 주자재 -->		
	<div class="col-sm-3 board_list" style="padding:7;">  			
		<!-- 부자재 미입고 간소화된 리스트 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./request_etc/list.php" style="color: white; text-decoration: none;">
					🛒 부자재 미입고 현황 <span style="font-size: 0.7rem; opacity: 0.9;"></span>
				</a>
			</div>
			<style>
				.blink {
					animation: blink 1s linear infinite;
				}
				@keyframes blink {
					0%, 50% { opacity: 1; }
					51%, 100% { opacity: 0.3; }
				}
				/* 테이블 셀 텍스트 줄바꿈 방지 및 말줄임표 처리 */
				.modern-dashboard-table td {
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
					max-width: 0;
					font-size: 0.8rem;
				}
				/* 진행 컬럼 - 배지가 잘리지 않도록 */
				.modern-dashboard-table td:nth-child(1) {
					max-width: 80px;
					min-width: 60px;
				}
				/* 현장명 컬럼 */
				.modern-dashboard-table td:nth-child(2) {
					max-width: 200px;
				}
				/* 종류/규격 컬럼 */
				.modern-dashboard-table td:nth-child(3) {
					max-width: 200px;
				}
			</style>
			<div class="modern-card-body">
				<?php
				// 부자재 미입고 간소화된 리스트
				$sql = "SELECT num, outworkplace, spec, which, outdate
						FROM mirae8440.eworks 
						WHERE eworks_item='부자재구매' AND (which != '3') AND is_deleted IS NULL 
						ORDER BY outdate DESC ";
				$stmh = $pdo->query($sql);
				$total_row = $stmh->rowCount();
				?>
				
				<?php if ($total_row > 0): ?>
				<table class="modern-dashboard-table">
					<thead>
						<tr>
							<th style="width: 15%;">진행</th>
							<th style="width: 45%;">현장명</th>
							<th style="width: 40%;">종류/규격</th>
						</tr>
					</thead>
					<tbody>
						<?php while($row = $stmh->fetch(PDO::FETCH_ASSOC)): 
							$num = $row["num"];
							$outworkplace = $row["outworkplace"];
							$spec = $row["spec"];
							$which = $row["which"];
							
							// 진행상태 설정
							switch ($which) {
								case "1":
									$status_badge = '<span class="badge bg-primary blink" style="font-size: 0.65rem; padding: 0.25em 0.5em;">요청</span>';
									break;
								case "2":
									$status_badge = '<span class="badge bg-secondary" style="font-size: 0.65rem; padding: 0.25em 0.5em;">보냄</span>';
									break;
								default:
									$status_badge = '<span class="badge bg-light text-dark" style="font-size: 0.65rem; padding: 0.25em 0.5em;">기타</span>';
									break;
							}
							
						?>
						<tr onclick="popupCenter('./request_etc/view.php?menu=no&num=<?= $num ?>', '부자재 미입고 상세', 1200, 800);" style="cursor: pointer;">
							<td class="text-center"><?= $status_badge ?></td>
							<td class="text-start" title="<?= htmlspecialchars($outworkplace) ?>">
								<?php
									$text = mb_strlen($outworkplace, 'UTF-8') > 8 ? mb_substr($outworkplace, 0, 8, 'UTF-8') . '..' : $outworkplace;
									echo htmlspecialchars($text);
								?>
							</td>
							<td class="text-start" title="<?= htmlspecialchars($spec) ?>"><?= htmlspecialchars($spec) ?></td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				<?php else: ?>
				<div style="text-align: center; color: var(--dashboard-text-secondary); font-size: 0.9rem; padding: 1rem;">
					부자재 미입고 현황이 없습니다.
				</div>
				<?php endif; ?>
			</div>
		</div>		
	</div>	<!-- end of col-sm-4 -->

	<div class="col-sm-3 board_list" style="padding:7;">								
		<!-- 화물/택배 금일출고 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./delivery/list.php" style="color: white; text-decoration: none;">
					🚛 화물/택배 금일출고 <span style="font-size: 0.7rem; opacity: 0.9;">(배송 현황)</span>
				</a>
			</div>
		<?php
		//도장관련 글이 일주일에 해당되면
		$now = date("Y-m-d", time());
		
		$a = " WHERE registedate BETWEEN '$now' AND '$now' AND is_deleted IS NULL ORDER BY num DESC limit 3";

		$sql = "SELECT * FROM mirae8440.delivery" . $a;

		$stmh = $pdo->query($sql);
		$total_row = $stmh->rowCount();	

		// 현재 날짜를 DateTime 객체로 가져옵니다.
		$currentDate = new DateTime();					
		if($total_row > 0) {
		?>			
		<table class="modern-dashboard-table" id="deliveryTable2">
			<thead>
				<tr>
					<th class='text-center'> 품명/현장명 </th>
					<th class='text-center'> 받을분 </th>
				</tr>
			</thead>
			<tbody>
			<?php
					// 현재 날짜를 DateTime 객체로 가져옵니다.
					$currentDate = new DateTime();
					$start_num = 0;
					while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						$start_num ++ ;
						// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
						$item_num = $row["num"];


						print '<tr onclick="viewBoard(\'delivery\', ' .  $item_num  . ');" style="cursor:pointer;">';						
							$text = mb_strlen($row["item_name"], 'UTF-8') > 10 ? mb_substr($row["item_name"], 0, 10, 'UTF-8') . '..' : $row["item_name"];
							$text = str_replace(',', '', $text);
						print '<td class="text-start blink-toggle" data-original-text="' . htmlspecialchars($text) . '"> &nbsp; ' . $text . '</td>';

						$text = mb_strlen($row["receiver"], 'UTF-8') > 20 ? mb_substr($row["receiver"], 0, 20, 'UTF-8') . '..' : $row["receiver"];
						$text = str_replace(',', '', $text);
						print '<td class="text-start"> &nbsp; ' . $text . '</td>';
						print '</tr>';
						}
				?>
				</tbody>
			</table>							 
			<?php 
				} 
					else {
						?>
						<div style="text-align: center;
									color: var(--dashboard-text-secondary);
									font-size: 0.9rem;
									padding: 1rem;">
							오늘은 화물/택배 금일출고가 없습니다.
						</div>						
						</tbody>
						</table>					
				<?php }	?>		
		</div>   	
				
		<!-- 도장 발주 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./make/list.php" style="color: white; text-decoration: none;">
					🎨 도장 발주 <span style="font-size: 0.7rem; opacity: 0.9;">(최근3건)</span>
				</a>
			</div>
		<?php
		//도장관련 글이 일주일에 해당되면
		$now = date("Y-m-d", time());
		
		// $oneWeekAgo = date("Y-m-d", strtotime("-1 week", strtotime($now)));			// 1주전 정보		
		$twentyDaysAgo = date("Y-m-d", strtotime("-20 days", strtotime($now)));  // 20일 전 정보	
		$fiveDaysAgo = date("Y-m-d", strtotime("-5 days", strtotime($now)));  // 5일 전 정보	
		$endOfDay = date("Y-m-d 23:59:59", time());
		$a = " WHERE indate BETWEEN '$twentyDaysAgo' AND '$endOfDay' ORDER BY num DESC limit 3";

		$sql = "SELECT * FROM mirae8440.make" . $a;

		$stmh = $pdo->query($sql);
		$total_row = $stmh->rowCount();	

		// 현재 날짜를 DateTime 객체로 가져옵니다.
		$currentDate = new DateTime();					
		if($total_row > 0) {
		?>			
		<table class="modern-dashboard-table">
			<tbody>
			<?php
					// 현재 날짜를 DateTime 객체로 가져옵니다.
					$currentDate = new DateTime();
					if($total_row > 0) {
						print "<thead> <tr>";
						print "<th class='text-center' style='width: 25%;'> 일자 </th>";
						print "<th class='text-center' style='width: 75%;'> 작업 내용 </th>";
						print "</tr> </thead>";

					while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
						$item_num = $row["num"];
						$printDate = date('m-d', strtotime($row["indate"]));
						$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';

						print '<tr onclick="viewBoard(\'paint\', ' .  $item_num  . ');" style="cursor:pointer;">';

						print '<td class="text-center">';
						print $printDate;
						print '</td>';

						// 최근 5일 이내인지 확인
						if($row["indate"] >= $fiveDaysAgo) {
							$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
						} else {
							$newImage = ''; // 이미지 표시 안 함
						}

						$text = mb_strlen($row["text"], 'UTF-8') > 15 ? mb_substr($row["text"], 0, 15, 'UTF-8') . '..' : $row["text"];
						$text = str_replace(',', '', $text);
						print '<td class="text-start"> &nbsp; ' . $newImage . $text . '</td>';

						print '</tr>';
						}
					}
				?> 
				</tbody>
			</table>							
			<?php } ?>
		</div>   			
	</div>

	<!-- 전체공지 -->	
	<div class="col-sm-3 board_list" style="padding:7;"> 
		<!-- 전체 공지 -->
		<div class="modern-management-card">
			<div class="modern-dashboard-header">
				<a href="./notice/list.php" style="color: white; text-decoration: none;">
					📢 전체 공지 <span style="font-size: 0.7rem; opacity: 0.9;">(공지사항)</span>
				</a>
			</div>
				<div class="modern-card-body">					
					<?php   
				//전체 공지사항
				$now = date("Y-m-d",time()) ;				  
				$a="   where noticecheck='y' order by num desc limit 3 ";  				  
				$sql="select * from mirae8440.notice " . $a; 		
				$stmh = $pdo->query($sql);
				$total_row = $stmh->rowCount();
				
				// 현재 날짜를 DateTime 객체로 가져옵니다.
				$currentDate = new DateTime();
				
				if($total_row > 0) {
					echo '<table class="modern-dashboard-table">';
					
					while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						// 데이터의 등록 날짜를 DateTime 객체로 가져옵니다.
						$dataDate = new DateTime($row["regist_day"]);
						
						// 날짜 차이를 계산합니다.
						$interval = $currentDate->diff($dataDate)->days;

						// 이미지 태그 초기화
						$newImage = '';

						// 7일 이내면 이미지를 추가합니다.
						if($interval < 7) {
							$newImage = '<img src="./img/new-gif.gif" style="width:10%;" alt="New" /> &nbsp;';
						}
						
						$item_num = $row["num"]; 
						$sqlsub="select * from mirae8440.notice_ripple where parent=$item_num";
						$stmh1 = $pdo->query($sqlsub); 
						$num_ripple=$stmh1->rowCount(); 
						
						$echoPrint = mb_substr($row["subject"], 0, 25, "UTF-8");  
						
						// 데이터-속성 추가하여 공지의 ID 또는 필요한 정보를 저장
						print '<tr onclick="viewBoard(\'notice\', ' .  $item_num  . ');" style="cursor:pointer;"><td class="text-start"> &nbsp;  ' . $newImage . $echoPrint;

						if($num_ripple>0)
						echo ' &nbsp; <span class="modern-data-value"> ' . $num_ripple . ' </span> </td> ';
					else
					echo  '</td> ';

				echo '</tr>'; // 테이블 행 종료
			}
			echo '</table>'; 
					} else {
						echo '<span> &nbsp; </span>';
					}
					?>  
		</div>   
	</div> 		        
	<!-- 당해년도 품질불량  -->	
	<div class="card justify-content-center" style="cursor:pointer;" onclick="location.href='./errors/statistics.php';">		
		<div class="card-body my-card-padding">	
			<?php include "./load_errorstatistics.php"; ?>		
		</div>   
	</div> 		

	<!-- 추억 사진&영상 -->
	<div class="modern-management-card">
		<div class="modern-dashboard-header">
			<a href="./youtube.php" style="color: white; text-decoration: none;">
				📸 추억의 사진&영상 <span style="font-size: 0.7rem; opacity: 0.9;">(미디어)</span>
			</a>
		</div>
		<div class="modern-card-body">
			<div class="text-center mb-2">
				<span class="fw-bold shop-header fs-6">2025년 카페 자투라</span>
			</div>
				<div class="d-flex justify-content-center align-items-center mb-1">		
				<iframe width="240" height="135" src="https://www.youtube.com/embed/KEXDABu2_9Q" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> &nbsp;&nbsp;&nbsp;	
				</div>
				<!-- <div class="d-flex justify-content-center align-items-center">		
					<div class="photo-frame justify-content-center text-center">
						<?php
						// for ($i = 1; $i <= 4; $i++) {
						// 	// 사진 파일 경로 생성
						// 	$photoPath = "img/trip/20251113_trip" . sprintf("%02d", $i) . ".jpg";
						// 	// 사진 태그 출력
						// 	echo '<img src="' . $photoPath . '" class="framed-photo">';
						// }
						?>
					</div>
				</div> -->
		</div>
	</div>
	
</div>	<!-- end of col-sm-4 -->

	<!-- Jamb 금일출고와 본천장/조명천장 출고를 나란히 표시 -->
	<div class="row d-flex" style="padding:0; margin-top: 10px;">
		<!-- Jamb 금일출고 -->
		<div class="col-sm-6 board_list" style="padding:7;">
			<div class="modern-management-card">
				<div class="modern-dashboard-header d-flex justify-content-center align-items-center">
					<span>🏭 Jamb 금일출고</span>
				</div>
				<div class="modern-card-body">
					<?php include "./load_jamb.php"; ?>
				</div>
			</div>
		</div>

		<!-- 본천장/조명천장 출고 -->
		<div class="col-sm-6 board_list" style="padding:7;">
			<div class="modern-management-card">
				<div class="modern-dashboard-header d-flex justify-content-center align-items-center">
					<span>🏢 본천장/조명천장 출고</span>
				</div>
				<div class="modern-card-body">
					<?php include "./load_ceiling.php"; ?>
				</div>
			</div>
		</div>
	</div>
	</div>            	    
</div>    <!-- id="NoneManagementInfo" -->        	    
 
<!-- 조직도 -->		
<div class="row">					
<div class="col-sm-12">					
<div class="d-flex justify-content-center">					
<div id="org_chart_div"></div>

    <script type="text/javascript">
        // PHP 변수를 JavaScript로 전달
        var deliveryCountToday = <?php echo isset($delivery_count_today) ? $delivery_count_today : 0; ?>;
        // console.log('deliveryCountToday: ' + deliveryCountToday);
        // google.charts.load('current', {packages:["orgchart"]});
        // google.charts.setOnLoadCallback(drawChart);

        // function drawChart() {
            // const data = new google.visualization.DataTable();
            // data.addColumn('string', 'Name');
            // data.addColumn('string', 'Manager');
            // data.addColumn('string', 'ToolTip');

            // // 조직도 데이터
            // data.addRows([
                // [{v: '소현철 대표', f: '<div class="custom-node"><div class="title">소현철 대표</div><div class="subtitle">CEO</div></div>'}, '', 'CEO/President'],
                
                // // 지원파트
                // [{v: '관리/영업지원', f: '<div class="custom-node"><div class="title">관리<br>영업지원</div><div class="subtitle">지원 파트</div></div>'}, '소현철 대표', ''],
                // [{v: '설계', f: '<div class="custom-node"><div class="title">설계</div><div class="subtitle">지원파트</div></div>'}, '소현철 대표', 'CEO 소현철'],
                // [{v: '기업전담부서', f: '<div class="custom-node"><div class="title">기업<br>전담부서</div></div>'}, '소현철 대표', ''],

                // // 제조/생산
                // [{v: '이경묵 공장장', f: '<div class="custom-node"><div class="title">이경묵 <br> 공장장</div><div class="subtitle">제조/생산</div></div>'}, '소현철 대표', ''],

                // // 관리/영업지원 세부
                // [{v: '총괄', f: '<div class="custom-node"><div class="title">총괄 최장중 이사</div></div>'}, '관리/영업지원', ''],
                // [{v: '영업관리 조경임 부장', f: '<div class="custom-node"><div class="title">영업관리<br> 조경임 부장</div></div>'}, '총괄', ''],
                // [{v: '총무/경리 소민지 사원', f: '<div class="custom-node"><div class="title">총무/경리 <br>소민지 사원</div></div>'}, '영업관리 조경임 부장', ''],

                // // 설계 세부
                // [{v: '설계 이미래 과장', f: '<div class="custom-node"><div class="title">설계 이미래 과장</div></div>'}, '설계', ''],
                // [{v: '설계 이소정 사원', f: '<div class="custom-node"><div class="title">설계 이소정 사원</div></div>'}, '설계', ''],

                // // 기업전담부서 세부
                // [{v: '연구 김보곤 실장', f: '<div class="custom-node"><div class="title">연구 김보곤 실장</div></div>'}, '기업전담부서', ''],
                // [{v: '연구 안현섭 차장', f: '<div class="custom-node"><div class="title">연구 안현섭 차장</div></div>'}, '기업전담부서', ''],

                // // 제조/생산 세부
                // [{v: '절곡 조성원 부장', f: '<div class="custom-node"><div class="title">절곡 조성원 부장</div></div>'}, '이경묵 공장장', ''],                
				
                // // 제조/생산 세부                
                // [{v: '절곡 김영무 과장', f: '<div class="custom-node"><div class="title">절곡 <br> 김영무 과장</div></div>'}, '절곡 조성원 부장', ''],
                // [{v: '가공 까심 사원', f: '<div class="custom-node"><div class="title">가공 <br> 까심 사원</div></div>'}, '절곡 김영무 과장', ''],
                // [{v: '가공 샤집 사원', f: '<div class="custom-node"><div class="title">가공 <br> 샤집 사원</div></div>'}, '절곡 김영무 과장', ''],
                // [{v: '가공 딥 사원', f: '<div class="custom-node"><div class="title">가공 <br> 딥 사원</div></div>'}, '절곡 김영무 과장', ''],
                
                // [{v: '용접 라나 과장', f: '<div class="custom-node"><div class="title">용접 <br> 라나 과장</div></div>'}, '이경묵 공장장', ''],
                // [{v: '용접 불한 사원', f: '<div class="custom-node"><div class="title">용접 <br> 불한 사원</div></div>'}, '용접 라나 과장', ''],
				
                // [{v: '조립 권영철 부장', f: '<div class="custom-node"><div class="title">조립 권영철 부장</div></div>'}, '이경묵 공장장', ''],                
                // [{v: '조립 안병길 실장', f: '<div class="custom-node"><div class="title">조립 안병길 실장</div></div>'}, '조립 권영철 부장', ''],
                // [{v: '조립 김수로 대리', f: '<div class="custom-node"><div class="title">조립 김수로 대리</div></div>'}, '조립 권영철 부장', ''],
                // [{v: '조립 이도훈 사원', f: '<div class="custom-node"><div class="title">조립 이도훈 사원</div></div>'}, '조립 권영철 부장', ''],
				
            // ]);

            // const chart = new google.visualization.OrgChart(document.getElementById('org_chart_div'));
            // chart.draw(data, {allowHtml: true});
        // }
    </script>

</div>      <!-- end of row d-flex board_list -->            	    
</div>               
</div>
</div>
</div>

 <!-- 모바일 전자결재 화면 모달 -->
<div class="eworks-modal-overlay" id="eworksModalOverlay">
    <div class="eworks-modal-container" id="eworksModalContainer">
        <div class="eworks-modal-header">
            <h5 class="eworks-modal-title" id="eworksModalTitle">전자결재</h5>
            <button type="button" class="eworks-modal-close" onclick="closeEworksModal(); return false;" aria-label="닫기">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <!-- 전자결재 nav 영역 -->
        <div class="eworks-modal-nav" id="eworksModalNav">
            <!-- eworks_nav.php에서 동적으로 로드됨 -->
        </div>
        <div class="eworks-modal-body" id="eworksModalBody">
            <div id="eworks_list" class="eworks-list-container"></div>
        </div>
    </div>
</div>

 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-xxl">     
	<?php } ?>
 
<!-- 권영철님 화면일때 표시함 빠른메뉴 일때 -->
<?if($submenu==1) { ?>
   <!--모바일 일때 -->
<div class="d-flex mb-5 mt-5 justify-content-center d-md-none">    
   <button  type="button" class="btn btn-success btn-lg fs-1" onclick="location.href='../mceiling/list.php';"> 모바일 천장/LC 사진등록 조립기록 화면 바로가기  </button>&nbsp;&nbsp;&nbsp;
</div>  
<?}?>

<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 					

<?php include 'mymodal.php'; ?>

<?php   
// 저녁식사요청
   $now = date("Y-m-d",time()) ;  
   
   $lunch_done = '';  
   $supper_done = '';
   $eat_count = '';
   $aftereat_count = '';
   $lunch_text = '';
   $dinner_text = '';
   
   $sql="select * from mirae8440.afterorder where askdatefrom='$today' " ;
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		  
		  if( $row["content"] == '중식' )
		  {
		       $eat_count =  $row["item"];    
			   $lunch_text = "(" . $row["memo"] . ")";    
			   $lunch_done = $row["state"];    
		  }
		  
		  if( $row["content"] == '석식' )
		  {
		       $aftereat_count =  $row["item"];    
			   $dinner_text = "(" . $row["memo"] . ")";   
			   $supper_done = $row["state"];    			   
		  }		   
	  }
?>  		

<?php   
// 연차 결재요청 리스트 불러오기
   // require_once("./lib/mydb.php");
   // $pdo = db_connect();   
   // $now = date("Y-m-d",time()) ;  
   
   // $sql="select * from mirae8440.eworks where status<>'end' AND is_deleted IS NULL order by num desc" ;
   // $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   // $total_row=$stmh->rowCount();
   // if($total_row>0 && $admin==1 ) 
      // include "./load_al.php";     
?>  		
	  		
<?php   
// 품질불량 보고서 리스트 불러오기
   $now = date("Y-m-d",time()) ;  
   
   $sql="select * from mirae8440.error where approve <> '처리완료' order by num desc" ;
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0  ) 
   include "./load_error.php";     

?>  	
	           
<?php   
// 장비 미점검 리스트 불러오기
   $now = date("Y-m-d",time()) ;  
   
   $sql="select * from mirae8440.mymclist where done is null order by num desc" ;
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0  ) 
      include "./load_mc.php";     
?>  
				        
<?php   
// 사무실 미점검 리스트 불러오기
   $now = date("Y-m-d",time()) ;  
   
   $sql="select * from mirae8440.myarealist where done is null order by num desc" ;
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0  ) 
      include "./qcoffice/load_area.php";     

   // 덴크리 서한 정보 가져오기	  
   $a="   where deadline='$now' order by num desc ";  
   $sql="select * from mirae8440.outorder " . $a; 		
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0) 
      include "./load_outorder.php";  
  
?>  		
		    
<?php     
// 부자재 미입고 카드
$sql="select * from mirae8440.eworks where eworks_item='부자재구매' and (which != '3') and is_deleted is NULL order by outdate desc" ; 		// 미입고 소모품
$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
$total_row=$stmh->rowCount();
if($total_row>0) 
   include "./load_request_etc.php";

// 원자재 미입고 카드
$sql="select * from mirae8440.eworks where eworks_item='원자재구매' and (which != '3') and is_deleted is NULL  order by outdate desc" ; 		// 미입고 원자재
$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
$total_row=$stmh->rowCount();
if($total_row>0) 
   include "./load_request.php";		

// 월간상세일정 카드 (항상 표시)
include "./load_month_schedule.php";		
 
 ?>
    
<!-- <div class="card">		
<div class="card-header">       	      
	<div class="d-flex mb-2 mt-2 justify-content-center">    
		<H4> <span id="advice"> </span> </H4>
	</div>  
</div>   -->

<span id="displaytmp" > </span>
	<!-- 아래 dialog 태그 영역이 메시지 창 -->
	<?php if($chkMobile==false) { ?>
	<dialog id="myMsgDialog"  >
    <?php } else  { ?>
	<dialog id="myMsgDialog"  >
	<?php }  ?>
		<!-- 문의사항 등록 section-->
		<section class="py-0">   	
      <div class="container px-4 px-lg-5 mt-2">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <H5 class="text-center"> <?php echo $bad_number; ?> 건의 부적합이 있습니다.</H5>
         <div class="row">
          <div class="col-sm-3 text-center">
            <button class="btn btn-outline-dark" type="button" onclick="closeDialog()">
                <i class="bi-cart-fill me-1"></i>
                  닫기
            </button>
          </div>
          <div class="col-sm-9 text-center">
             <a class="btn btn-outline-dark mt-auto" href="./errors/list.php?search=Y&state=처리전&item=all"> 부적합 내역 확인하러 가기 </a>
          </div>
         </div>
         <div class="row">
         </div>
        </div>
      </div>
		</section>
	</dialog>

<?php include 'footer.php'; ?>

</div> <!-- container-fulid end -->
</form> 


  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/counter.js"></script>
  <script src="assets/js/custom.js"></script>

<script>  

// 장비점검 관련 토글버튼
$(document).ready(function(){
    var toggleMCBtn = document.getElementById("toggleMCBtn");

    if (toggleMCBtn) {
        toggleMCBtn.addEventListener("click", function() {
            var mcTable = document.getElementById("MCtable");
            var isShown = mcTable.style.display === "block";
            if(mcTable)
			{
				mcTable.style.display = isShown ? "none" : "block"; // 표시 상태 토글
				setCookie("showMCBtn", isShown ? "hide" : "show", 1); // 쿠키 상태 업데이트 (1일간 유효)
			}
        });
    }

    var toggleOfficeBtn = document.getElementById("toggleOfficeBtn");

    if (toggleOfficeBtn) {
        toggleOfficeBtn.addEventListener("click", function() {
            var OfficeTable = document.getElementById("Officetable");
            var isShown = OfficeTable.style.display === "block";
            if(OfficeTable)
			{
				OfficeTable.style.display = isShown ? "none" : "block"; // 표시 상태 토글
				setCookie("showOfficeBtn", isShown ? "hide" : "show", 1); // 쿠키 상태 업데이트 (1일간 유효)
			}

        });
    }

    // 먼저 쿠키 상태를 복원
    checkCookieAndToggleDisplay();
    
    // 경영정보 토글 상태를 별도로 복원 (DOM 로드 지연 대응)
    setTimeout(function() {
        restoreManagementToggleState();
    }, 100);

    // 경영정보 토글 기능
    var toggleManagementBtn = document.getElementById("toggleManagementInfo");

    if (toggleManagementBtn) {
        // change 이벤트로 변경하여 Bootstrap 체크박스와 호환성 향상
        toggleManagementBtn.addEventListener("change", function() {
            var managementInfo = document.getElementById("managementInfo");
            var NoneManagementInfo = document.getElementById("NoneManagementInfo");

            // 체크박스의 현재 상태를 확인
            var isChecked = this.checked;
            // console.log("Toggle Management Info - isChecked:", isChecked); // 디버깅용

            if (isChecked) {
                // 경영정보 보이기
                if (managementInfo) managementInfo.style.display = "block";
                if (NoneManagementInfo) NoneManagementInfo.style.display = "none";
                // 부자재 미입고 카드 보이기
                var requestEtcCard = document.getElementById("requestEtcCard");
                if (requestEtcCard) requestEtcCard.style.display = "block";
                // 원자재 미입고 카드 보이기
                var requestCard = document.getElementById("requestCard");
                if (requestCard) requestCard.style.display = "block";
                setCookie("showManagementInfo", "show", 30); // 30일간 유효
                setCookie("toggleManagementInfo", "checked", 30); // 체크박스 상태 저장
            } else {
                // 경영정보 숨기기 (간소화된 화면만 표시)
                if (managementInfo) managementInfo.style.display = "none";
                if (NoneManagementInfo) NoneManagementInfo.style.display = "block";
                // 부자재 미입고 카드 숨기기
                var requestEtcCard = document.getElementById("requestEtcCard");
                if (requestEtcCard) requestEtcCard.style.display = "none";
                // 원자재 미입고 카드 숨기기
                var requestCard = document.getElementById("requestCard");
                if (requestCard) requestCard.style.display = "none";
                setCookie("showManagementInfo", "hide", 30); // 30일간 유효
                setCookie("toggleManagementInfo", "unchecked", 30); // 체크박스 상태 저장
            }
        });
    }

    // 화물/택배 풍선 표시 조건 확인
    if (Number(deliveryCountToday) > 0) {
		// console.log('실행전 조건 deliveryCountToday: ' + deliveryCountToday);
        setTimeout(function() {
            $('#deliveryReminder').fadeIn(300);
        }, 1000); // 페이지 로드 1초 후 표시
    }
});


function viewBoard(sel, num) {
	if(sel==='notice')
		popupCenter("./notice/view.php?num=" + num + "&menu=no&page=1&tablename=notice" , '공지사항', 1300, 850);	  
	if(sel==='qna')
		popupCenter("./qna/view.php?num=" + num + "&menu=no&page=1&tablename=qna" , '자료실', 1500, 900);	  
	if(sel==='s_board')
		popupCenter("./s_board/view.php?num=" + num + "&menu=no&page=1&tablename=s_board" , '안전보건', 1500, 900);	 
 	if(sel==='vote')
		popupCenter("./vote/view.php?num=" + num + "&menu=no&page=1&tablename=vote" , '투표', 1500, 900);	  
 	if(sel==='daylaborer')
		popupCenter("./daylaborer/write_form_ask.php?num=" + num + "&menu=no&page=1&tablename=daylaborer" , '일용직관리', 500, 550);	 
 	if(sel==='paint')
		popupCenter("./make/view.php?num=" + num + "&menu=no&page=1&tablename=make" , '도장', 1400, 800);	 
 	if(sel==='RiskAssessment')
		popupCenter("./RiskAssessment/view.php?num=" + num + "&menu=no&tablename=RiskAssessment" , '위험성평가', 1400, 800);	 
 	if(sel==='errormeeting')
		popupCenter("./errormeeting/write_form.php?num=" + num + "&menu=no&tablename=emeeting" , '', 1100, 850);
 	if(sel==='idea')
		popupCenter("./idea/write_form.php?num=" + num + "&menu=no&tablename=emeeting" , '', 1100, 850);
}

alreadyShown = getCookie("notificationShown");   

///////////////////// input 필드 값 옆에 X 마크 띄우기 
///////////////////// input 필드 값 옆에 X 마크 띄우기 

var btnClear = document.querySelectorAll('.btnClear');
btnClear.forEach(function(btn){
	btn.addEventListener('click', function(e){
		btn.parentNode.querySelector('input').value = "";
		e.preventDefault(); // 기본 이벤트 동작 막기
	  // 포커스 얻기
	  btn.parentNode.querySelector('input').focus();				
	})
})	
 
var intervalId; // 인터벌 식별자를 저장할 변수

// 월요일(0)부터 금요일(4)까지 특정 시간(오후 16시 50분)에 모달 창을 띄웁니다.
function showNotification() {
            var now = new Date();
            var day = now.getDay(); // 0부터 일요일, 1부터 월요일, ..., 6부터 토요일

            if (day >= 1 && day <= 5) { // 월요일부터 금요일인 경우
                var targetTime = new Date();
                targetTime.setHours(17); 
                targetTime.setMinutes(15); 
                targetTime.setSeconds(0); // 0초
				
				// console.log(now);
				// console.log(targetTime);
				

                if (now >= targetTime) { // 현재 시간이 목표 시간 이후인 경우
                    var alreadyShown = getCookie("notificationShown"); // 쿠키에서 확인한 결과를 가져옵니다.
					// console.log('cookie');
					// console.log(alreadyShown);
                    // if (alreadyShown==='N') { // 쿠키가 없는 경우
                        // $('#timeModal').modal('show'); // 모달 창을 띄웁니다.
                        // setCookie("notificationShown", "true", 1); // 쿠키를 설정하여 확인한 것을 표시합니다.
                        // clearInterval(intervalId); // 인터벌을 멈춥니다.
                    // }
                }
            }
        } 
	
// 인터벌 멈추는 함수
function stopInterval() {
	clearInterval(intervalId);
}	
	
$(document).ready(function(){	

// 환율 가져오기
/*
fetch('https://quotation-api-cdn.dunamu.com/v1/forex/recent?codes=FRX.KRWUSD')
// 도메인 만료
  .then(response => response.json())
  .then(data => {
    const usdKrw = data[0];
    const usdKrwPrice = usdKrw.basePrice;
	const formattedPrice = usdKrwPrice.toLocaleString();
    const usdDate = usdKrw.date;
    const usdTime = usdKrw.time;
    // console.log(`USD/KRW 환율: ${usdKrwPrice}`);
	$("#currencyrate").text(usdDate + " " + usdTime + " 기준 : " + formattedPrice + " (원)");
  });
  */

intervalId = setInterval(showNotification, 10000); // 10초마다 showNotification 함수를 실행하고 식별자를 저장합니다.

// DataTable 라이브러리 로드 확인
if (typeof $.fn.DataTable === 'undefined') {
  // console.warn('DataTable 라이브러리가 로드되지 않았습니다.');
}

// 인생의 조언 60가지 가져와서 보여주기
fetch('advice.json')
  .then(response => response.json())
  .then(data => {
    let randomIndex = Math.floor(Math.random() * data.length);
    let advice = data[randomIndex].advice;
    const adviceElement = document.getElementById('advice');
    if (adviceElement) {
      adviceElement.innerHTML = "오늘의 격언 : " + "'" + advice + "'";
    }
  })
  .catch(error => {
    // console.warn('advice.json 파일을 로드할 수 없습니다:', error);
  });

// 안전한 요소 업데이트
function safeUpdateElement(selector, value) {
  const element = $(selector);
  if (element.length > 0) {
    element.text(value);
  }
}

safeUpdateElement('#text1', <?php echo json_encode($request_asked_count ?? ''); ?>); // 화면상단에 건수 표시  원자재 요청건
safeUpdateElement('#text8', <?php echo json_encode($request_send_count ?? ''); ?>); // 화면상단에 건수 표시  원자재 발주보냄
safeUpdateElement('#text2', <?php echo json_encode($request_etc_asked_count ?? ''); ?>); // 화면상단에 건수 표시 기타물품 요청건
safeUpdateElement('#text7', <?php echo json_encode($request_etc_send_count ?? ''); ?>); // 화면상단에 건수 표시 기타물품 발주보냄
safeUpdateElement('#text5', <?php echo json_encode($eat_count ?? ''); ?>); // 화면상단에 건수 표시 중식
safeUpdateElement('#text6', <?php echo json_encode($aftereat_count ?? ''); ?>); // 화면상단에 건수 표시 석식
safeUpdateElement('#lunch_text', <?php echo json_encode($lunch_text ?? ''); ?>); // 화면상단에 중식 종류
safeUpdateElement('#dinner_text', <?php echo json_encode($dinner_text ?? ''); ?>); // 화면상단에 석식 종류
safeUpdateElement('#lunch_done', <?php echo json_encode($lunch_done ?? ''); ?>); 
safeUpdateElement('#supper_done', <?php echo json_encode($supper_done ?? ''); ?>); 
// 쟘 금일 정보 가져오기
safeUpdateElement('#jamb_registedate', <?php echo json_encode($jamb_registedate ?? ''); ?>); 
safeUpdateElement('#jamb_duedate', <?php echo json_encode($jamb_duedate ?? ''); ?>); 
safeUpdateElement('#jamb_outputdonedate', <?php echo json_encode($jamb_outputdonedate ?? ''); ?>); 
// 천장 금일 정보 가져오기
safeUpdateElement('#ceiling_registedate', <?php echo json_encode($ceiling_registedate ?? ''); ?>); 
safeUpdateElement('#ceiling_duedate', <?php echo json_encode($ceiling_duedate ?? ''); ?>); 
safeUpdateElement('#ceiling_outputdonedate', <?php echo json_encode($ceiling_outputdonedate ?? ''); ?>); 
// 덴크리 금일 정보 가져오기
safeUpdateElement('#dancre_registedate', <?php echo json_encode($dancre_registedate ?? ''); ?>); 
safeUpdateElement('#dancre_duedate', <?php echo json_encode($dancre_duedate ?? ''); ?>); 
safeUpdateElement('#dancre_outputdonedate', <?php echo json_encode($dancre_outputdonedate ?? ''); ?>); 
// 다온텍 금일 정보 가져오기
$('#daontech_registedate').text(<?php echo json_encode($daontech_registedate ?? ''); ?>); 
$('#daontech_duedate').text(<?php echo json_encode($daontech_duedate ?? ''); ?>); 
$('#daontech_outputdonedate').text(<?php echo json_encode($daontech_outputdonedate ?? ''); ?>); 

const Jamb_OutputPrice = <?php echo $jambearning; ?> ;  // 싱글 퀘테이션마크를 삭제하면 숫자로 표현됨 주의!!!
const Jamb_formattedPrice = Jamb_OutputPrice.toLocaleString() ;
$("#jambearning").text(Jamb_formattedPrice); // 잠 매출

const Lc_OutputPrice = <?php echo $lcearning; ?>; 		      // 싱글 퀘테이션마크를 삭제하면 숫자로 표현됨 주의!!!
const Lc_formattedPrice = Lc_OutputPrice.toLocaleString() ;
$('#lcearning').text(Lc_formattedPrice  ) ;            // 조명천장 매출

const prejamblist = <?php echo json_encode($prejamblist ?? ''); ?>; 		      
const preceilinglist = <?php echo json_encode($preceilinglist ?? ''); ?>; 		  

$('#prejamblist').text(prejamblist) ;            // 전일 쟘 출고내역
$('#preceilinglist').text(preceilinglist) ;            // 전일 천장 출고내역


$('#steel_done').text(<?php echo json_encode($steel_done ?? ''); ?>); 
$('#etc_done').text(<?php echo json_encode($etc_done ?? ''); ?>); 
	
$('#eworksel').val('draft');  // 최초 전자결재 작성으로 정함

// console.log("get cookie : " + getCookie("popupYN"));				 

let admin = <?php echo json_encode($admin ?? '0'); ?>;			

if(admin=='1') 
	   openPopup();	   	
	
var user_id = $('#user_id').val();
var user_name = $('#user_name').val();
var approvalarr = <?php echo json_encode($approvalarr);?> ;

// console.log('결재권자');
// console.log(approvalarr);

// 결재권자의 배열에 들어있으면 모달창 띄우기
if(approvalarr.includes(user_id))
     $('#Approval Modal').modal('show');						 

		$("#closemodalApprovalBtn").click(function(){   	
		  	$('#Approval Modal').modal('hide');						 
		 });  
		$("#closemodalBtn").click(function(){   	
		  	$('#myModal').modal('hide');						 
		 });  
   
   $("#popupwindow").click(function(){
                location.href='./shop/index.php';
            });   
	
	// 금일도 수고하셨습니다. 도면작도 올려주세요. 모달창
	$("#timeModalcloseBtn").click(function(){ 
		$('#timeModal').modal('hide');
	});
	
	$("#closeModalBtn").click(function(){ 
		$('#my80sizeCenterModal').modal('hide');
	});
});

	
function check_alert()
{	
	// load 알림설정
	var tmp; 				
	var user_name = $('#user_name').val();
	
	var NoCheck = <?php echo json_encode($NocheckDeviceNum ?? ''); ?>;
	var NoCheckArea = <?php echo json_encode($NocheckAreaNum ?? ''); ?>;
	var NocheckOfficePerson = <?php echo json_encode($NocheckOfficePerson ?? []); ?>;
	
	// console.log('NoCheck', NoCheck);
	// console.log('NoCheckArea', NoCheckArea);
	// console.log('NocheckOfficePerson', NocheckOfficePerson);
	
	 // ajax 요청 생성
	if (ajaxRequest !== null) {
		ajaxRequest.abort();
	} 
		 
		ajaxRequest = $.ajax({
			url: "load_alert.php",
			type: "post",
			data: $("#board_form").serialize(),
			dataType: "json", 
			success: function(data) {
				
				var voc_alert = data.voc_alert;
				var ma_alert = data.ma_alert;
				var order_alert = data.order_alert;

				// Input fields update
				$("#voc_alert").val(voc_alert);
				$("#ma_alert").val(ma_alert);
				$("#order_alert").val(order_alert);														
	
													
				if( (user_name=='이경묵' || user_name=='최장중' )&& Number(NoCheck) !== 0 ) {			 // 장비점검이 안끝났을때...
										
						Swal.fire({
						  icon: 'success',
						  title: '장비점검 확인요청',
						  html: ' 매주 금요일 미점검 장비리스트 <span class="badge bg-danger fs-5" > ' + NoCheck + ' </span> 존재함. <br> 장비점검을 마무리 해 주세요. ',
						});
						
						clearInterval(timer);								
					}		
									
													
				if(NocheckOfficePerson && NocheckOfficePerson.includes(user_name) && Number(NoCheckArea) !== 0) {		 // 사무실점검이 안끝났을때...										
						Swal.fire({
							icon: 'success',
							title: '사무실점검 확인요청',
							html: ' 매주 금요일 사무실 점검을 실시해 주세요! ',
						});
								
						clearInterval(timer);								
					}		
	
					},
					error : function( jqxhr , status , error ){
						// console.log( jqxhr , status , error );
				} 			      		
			   });		
	   									
}

var timer = setInterval(function() {
    checkAndRunFunction(); // 하루에 한 번만 실행되는 함수 호출
}, 5000); // 5초 간격

function checkAndRunFunction() {
    var functionName = 'check_McArea';
    var executed = getCookie(functionName);

    // console.log('check_alert() running 5sec interval');
    // console.log('executed', executed);
	
    if (!executed) {
        // 함수 실행
        check_alert();
        showNotification();

        // 하루 동안 유지되는 쿠키 설정 
        setCookie(functionName, 'true', 1 * 60); // 1시간 후 만료
    }
}
	
setTimeout(function() {
	// 하루한번만 창을 띄우기 위한 로직
	var cookieCheck = getCookie("popupYN");
	if (cookieCheck != "N")	
	  {
		// 하루 한번이 아니면 띄워주고 쿠키저장한다.
		setCookie("popupYN", "N", 1);   // 하루한번을 위한 쿠키 저장
		// 로그인시 팝업창 띄우기 부분	
			// $('#myModal').modal('show');	
		  }
		//	$('#myModal').modal('hide');	
		// $('#myModal').modal('show');
   }, 5000);	

// 페이지 로드 시 쿠키 상태에 따라 MCtable의 표시 상태를 결정하는 함수
function checkCookieAndToggleDisplay() {
    var showMC = getCookie("showMCBtn");
    var mcTable = document.getElementById("MCtable");
	
	if(!mcTable)
		return
    
    if (showMC === "show") {
        mcTable.style.display = "block"; // 쿠키가 "show"이면 표시
    } else {
        mcTable.style.display = "none"; // 그렇지 않으면 숨김
    }
    var showOffice = getCookie("showOfficeBtn");
    var OfficeTable = document.getElementById("Officetable");
		
	if(!OfficeTable)
		return
		if (showOffice === "show") {
			OfficeTable.style.display = "block"; // 쿠키가 "show"이면 표시
		} else {
			OfficeTable.style.display = "none"; // 그렇지 않으면 숨김
		}

    // 경영정보 토글 상태 복원
    var showManagementInfo = getCookie("showManagementInfo");
    var toggleManagementInfo = getCookie("toggleManagementInfo");
    var managementInfo = document.getElementById("managementInfo");
    var nonConformanceCost = document.getElementById("nonConformanceCost");
    var toggleManagementBtn = document.getElementById("toggleManagementInfo");
    
    if (managementInfo && toggleManagementBtn) {
        // toggleManagementInfo 쿠키가 있으면 그것을 우선적으로 사용
        if (toggleManagementInfo === "checked") {
            // 체크된 상태로 복원
            managementInfo.style.display = "block";
            if (nonConformanceCost) nonConformanceCost.style.display = "block";
            toggleManagementBtn.checked = true;
        } else if (toggleManagementInfo === "unchecked") {
            // 체크 해제된 상태로 복원
            managementInfo.style.display = "none";
            if (nonConformanceCost) nonConformanceCost.style.display = "block";
            toggleManagementBtn.checked = false;
        } else if (showManagementInfo === "hide") {
            // 기존 showManagementInfo 쿠키 사용 (하위 호환성)
            managementInfo.style.display = "none";
            if (nonConformanceCost) nonConformanceCost.style.display = "block";
            toggleManagementBtn.checked = false;
        } else {
            // 기본값: 보임 상태
            managementInfo.style.display = "block";
            if (nonConformanceCost) nonConformanceCost.style.display = "block";
            toggleManagementBtn.checked = true;
        }
    }
}

// 경영정보 토글 상태만 복원하는 함수
function restoreManagementToggleState() {
    var showManagementInfo = getCookie("showManagementInfo");
    var toggleManagementInfo = getCookie("toggleManagementInfo");
    var managementInfo = document.getElementById("managementInfo");
    var NoneManagementInfo = document.getElementById("NoneManagementInfo");
    var toggleManagementBtn = document.getElementById("toggleManagementInfo");
    
    if (managementInfo && toggleManagementBtn) {
        // toggleManagementInfo 쿠키가 있으면 그것을 우선적으로 사용
        if (toggleManagementInfo === "checked") {
            // 체크된 상태로 복원
            managementInfo.style.display = "block";
            if (NoneManagementInfo) NoneManagementInfo.style.display = "none";
            // 부자재 미입고 카드 보이기
            var requestEtcCard = document.getElementById("requestEtcCard");
            if (requestEtcCard) requestEtcCard.style.display = "block";
            // 원자재 미입고 카드 보이기
            var requestCard = document.getElementById("requestCard");
            if (requestCard) requestCard.style.display = "block";
            toggleManagementBtn.checked = true;
        } else if (toggleManagementInfo === "unchecked") {
            // 체크 해제된 상태로 복원
            managementInfo.style.display = "none";
            if (NoneManagementInfo) NoneManagementInfo.style.display = "block";
            // 부자재 미입고 카드 숨기기
            var requestEtcCard = document.getElementById("requestEtcCard");
            if (requestEtcCard) requestEtcCard.style.display = "none";
            // 원자재 미입고 카드 숨기기
            var requestCard = document.getElementById("requestCard");
            if (requestCard) requestCard.style.display = "none";
            toggleManagementBtn.checked = false;
        } else if (showManagementInfo === "hide") {
            // 기존 showManagementInfo 쿠키 사용 (하위 호환성)
            managementInfo.style.display = "none";
            if (NoneManagementInfo) NoneManagementInfo.style.display = "block";
            // 부자재 미입고 카드 숨기기
            var requestEtcCard = document.getElementById("requestEtcCard");
            if (requestEtcCard) requestEtcCard.style.display = "none";
            // 원자재 미입고 카드 숨기기
            var requestCard = document.getElementById("requestCard");
            if (requestCard) requestCard.style.display = "none";
            toggleManagementBtn.checked = false;
        } else {
            // 기본값: 보임 상태
            managementInfo.style.display = "block";
            if (NoneManagementInfo) NoneManagementInfo.style.display = "none";
            // 부자재 미입고 카드 보이기
            var requestEtcCard = document.getElementById("requestEtcCard");
            if (requestEtcCard) requestEtcCard.style.display = "block";
            // 원자재 미입고 카드 보이기
            var requestCard = document.getElementById("requestCard");
            if (requestCard) requestCard.style.display = "block";
            toggleManagementBtn.checked = true;
        }
    } 
}

function openPopup() { 
	var cookieCheck = getCookie("popupYN");
	if (cookieCheck != "N")
		    showMsg();
}	
	
function closePopup() {        
		setCookie("popupYN", "N", 1);
		closeMsg();
}  

function showMsg(){
	var dialog = document.getElementById("myMsgDialog");
	var bad_number = <?php echo json_encode($bad_number ?? 0); ?>;
	if(bad_number>0)
		dialog.showModal();
}

function closeMsg(){
	var dialog = document.getElementById("myMsgDialog");
	dialog.close();
}
function closeDialog(){
	var dialog = document.getElementById("closeDialog");
	dialog.close();
}
		
function sendMsg(){
	var dialog = document.getElementById("myMsgDialog");
	dialog.close();
}
  	
function restorePageNumber(){
    window.location.reload();
}

$(document).ready(function() {

	function inputEnter(inputID, buttonID) {
		document.getElementById(inputID).addEventListener('keydown', function(event) {
			if (event.key === 'Enter') {
				document.getElementById(buttonID).click();
				event.preventDefault(); // 기본 동작 차단
			}
		});
	}
				
    // searchTodo 입력 필드에서 Enter 키를 누르면 searchTodoBtn 버튼 클릭
    inputEnter('searchTodo', 'searchTodoBtn');    
	
    // todo_view 버튼 클릭 이벤트
    $("#todo_view").on("click", function() { 
		var showTodoView = getCookie("showTodoView");
		var todoCalendarContainer = $("#todo-calendar-container");
		var todoMainList = $("#todosMain-list");
		
		if (showTodoView === "show") {
			// 현재 보이는 상태 → 숨김
			todoCalendarContainer.css("display", "none");
			todoMainList.css("display", "none");
			setCookie("showTodoView", "hide", 1440); // 1440분 = 1일
			$(this).find("i").removeClass("bi-chevron-up").addClass("bi-chevron-down");
		} else {
			// 현재 숨겨진 상태 → 보임
			todoCalendarContainer.css("display", "inline-block");
			todoMainList.css("display", "block");
			setCookie("showTodoView", "show", 1440); // 1440분 = 1일
			$(this).find("i").removeClass("bi-chevron-down").addClass("bi-chevron-up");
		}
    });
	
	// 페이지 로드 시 todo_view 쿠키 값에 따라 초기 상태 설정
	var showTodoView = getCookie("showTodoView");
	var todoCalendarContainer = $("#todo-calendar-container");
	var todoMainList = $("#todosMain-list");
	
	if (showTodoView === "hide") {
		// 쿠키에 숨김으로 저장되어 있으면 숨김 상태로 시작
		todoCalendarContainer.css("display", "none");
		todoMainList.css("display", "none");
		$("#todo_view").find("i").removeClass("bi-chevron-up").addClass("bi-chevron-down");
	} else {
		// 기본값 또는 "show"이면 보임 상태로 시작
		todoCalendarContainer.css("display", "inline-block");
		todoMainList.css("display", "block");
		$("#todo_view").find("i").removeClass("bi-chevron-down").addClass("bi-chevron-up");
		// 쿠키가 없으면 기본값으로 "show" 설정
		if (!showTodoView) {
			setCookie("showTodoView", "show", 1440);
		}
	}
	
	// schedule_toggle 버튼 클릭 이벤트 (월간상세일정 접고 펼치기)
    $("#schedule_toggle").on("click", function() { 
		var showScheduleView = getCookie("showScheduleView");
		var scheduleContentWrapper = $("#scheduleContentWrapper");
		
		if (showScheduleView === "show") {
			// 현재 보이는 상태 → 숨김
			scheduleContentWrapper.slideUp(300);
			setCookie("showScheduleView", "hide", 1440); // 1440분 = 1일
			$(this).find("i").removeClass("bi-chevron-up").addClass("bi-chevron-down");
		} else {
			// 현재 숨겨진 상태 → 보임
			scheduleContentWrapper.slideDown(300);
			setCookie("showScheduleView", "show", 1440); // 1440분 = 1일
			$(this).find("i").removeClass("bi-chevron-down").addClass("bi-chevron-up");
		}
    });
	
	// 페이지 로드 시 schedule_toggle 쿠키 값에 따라 초기 상태 설정
	var showScheduleView = getCookie("showScheduleView");
	var scheduleContentWrapper = $("#scheduleContentWrapper");
	
	if (showScheduleView === "hide") {
		// 쿠키에 숨김으로 저장되어 있으면 숨김 상태로 시작
		scheduleContentWrapper.css("display", "none");
		$("#schedule_toggle").find("i").removeClass("bi-chevron-up").addClass("bi-chevron-down");
	} else {
		// 기본값 또는 "show"이면 보임 상태로 시작
		scheduleContentWrapper.css("display", "block");
		$("#schedule_toggle").find("i").removeClass("bi-chevron-down").addClass("bi-chevron-up");
		// 쿠키가 없으면 기본값으로 "show" 설정
		if (!showScheduleView) {
			setCookie("showScheduleView", "show", 1440);
		}
	}
	
    // board_view 버튼 클릭 이벤트
    $("#board_view").on("click", function() {
		var showBoardView = getCookie("showBoardView");		
		var board_list = $(".board_list");
		
		if (showBoardView === "show") {
			// 현재 보이는 상태 → 숨김
			board_list.css("display", "none");
			$("#org_chart_div").hide();
			setCookie("showBoardView", "hide", 1440); // 1440분 = 1일
			$(this).find("i").removeClass("bi-chevron-up").addClass("bi-chevron-down");
		} else {
			// 현재 숨겨진 상태 → 보임
			board_list.css("display", "inline-block");
			$("#org_chart_div").show();
			setCookie("showBoardView", "show", 1440); // 1440분 = 1일
			$(this).find("i").removeClass("bi-chevron-down").addClass("bi-chevron-up");
		}		
    });	

	// 페이지 로드 시 board_view 쿠키 값에 따라 초기 상태 설정	
	var showBoardView = getCookie("showBoardView");		
	var board_list = $(".board_list");
	
	if (showBoardView === "hide") {
		// 쿠키에 숨김으로 저장되어 있으면 숨김 상태로 시작
		board_list.css("display", "none");	
		$("#org_chart_div").hide();
		$("#board_view").find("i").removeClass("bi-chevron-up").addClass("bi-chevron-down");
	} else {
		// 기본값 또는 "show"이면 보임 상태로 시작
		board_list.css("display", "inline-block");		
		$("#org_chart_div").show();
		$("#board_view").find("i").removeClass("bi-chevron-down").addClass("bi-chevron-up");
		// 쿠키가 없으면 기본값으로 "show" 설정
		if (!showBoardView) {
			setCookie("showBoardView", "show", 1440);
		}
	}	

// 모바일 전자결재 모달 열기/닫기 함수 (전역 함수로 등록)
window.openEworksModal = function() {
    if (isMobileDevice()) {
        const overlay = document.getElementById('eworksModalOverlay');
        const body = document.getElementById('eworksModalBody');
        if (overlay && body) {
            overlay.style.display = 'flex';
            overlay.style.position = 'fixed';
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.left = '0';
            overlay.style.right = '0';
            overlay.style.top = '0';
            overlay.style.bottom = '0';
            
            const container = document.getElementById('eworksModalContainer');
            if (container) {
                container.style.width = '100vw';
                container.style.maxWidth = '100vw';
                container.style.margin = '0';
                container.style.padding = '0';
            }
            
            setTimeout(() => {
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
            }, 10);
        }
    }
};

// 전자결재 모달 닫기 함수 (부트스트랩 모달용)
function closeEworksModal() {
    // 부트스트랩 모달 닫기
    $('#eworks_form').modal('hide');
    
    // 커스텀 모달도 닫기 (혹시 열려있다면)
    if (isMobileDevice()) {
        const overlay = document.getElementById('eworksModalOverlay');
        if (overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
            setTimeout(() => {
                overlay.style.display = 'none';
                // 모달이 닫히면 index2.php로 돌아가기 (현재 페이지 유지)
                // 필요시 window.location.href = '/index2.php'; 사용 가능
            }, 300);
        }
    }
}

// 전역 함수로 등록 (index.js에서도 사용 가능하도록)
window.closeEworksModal = closeEworksModal;

// 모바일에서 전자결재 버튼 클릭 시 모달 열기
// 주석: refresheworks() 함수 내에서 모달을 열도록 변경하여 중복 호출 방지
// $(document).ready(function() {
//     // seltab 함수 래핑하여 모바일에서 모달 열기
//     if (typeof window.seltab !== 'undefined') {
//         const originalSeltab = window.seltab;
//         window.seltab = function(e_num) {
//             const result = originalSeltab.apply(this, arguments);
//             // 모바일에서는 모달 열기 (약간의 지연을 두어 데이터 로드 후 열기)
//             if (isMobileDevice()) {
//                 setTimeout(function() {
//                     if (typeof openEworksModal === 'function') {
//                         openEworksModal();
//                     }
//                 }, 100);
//             }
//             return result;
//         };
//     }
// });

});


// 하루동안 띄워주는 팝업창 만들기 코드
// 쿠키 함수는 common.js에서 로드됨 (setCookie, getCookie, deleteCookie)
// common.js의 setCookie는 분 단위로 작동: setCookie(name, value, minutes)
// 팝업용 일 단위 쿠키 함수
function setCookieForDays(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

$(document).ready(function(){
    // division 컬럼 값이 "표시"인 경우에만 팝업창을 보여줌(쿠키가 설정되어 있지 않은 경우)
    <?php if($popupDisplay){ ?>
      if(getCookie('dailyPopupShown') !== 'true'){
          $('#dailyPopup').show();
      }
    <?php } ?>

    // [닫기] 버튼 클릭 시 팝업 숨기기
    $('#closeDailyPopup, #closeDailyPopupX').click(function(){
        $('#dailyPopup').hide();
    });
    
    // [오늘 하루동안 표시하지 않기] 버튼 클릭 시 쿠키 설정 후 팝업 숨기기  
    $('#hideToday').click(function(){
        // 하루(1일) 동안 팝업을 보이지 않도록 쿠키 설정
        setCookieForDays('dailyPopupShown', 'true', 1);
        $('#dailyPopup').hide();
    });
});

// 화물출고 토글 blink 기능
$(document).ready(function() {
    $('.blink-toggle').each(function() {
        const $element = $(this);
        const originalText = $element.data('original-text');
        let isShowingBadge = true;
        
        // 2초마다 토글
        setInterval(function() {
            if (isShowingBadge) {
                $element.html('&nbsp; <span class="blink-badge">요청</span>');
            } else {
                $element.html('&nbsp; ' + originalText);
            }
            isShowingBadge = !isShowingBadge;
        }, 2000);
    });
    
    // 택배 알림 말풍선 기능 (클릭 시 테이블 하이라이트)
    $('#deliveryReminder').click(function(e) {
        // X 버튼 클릭이 아닌 경우에만 하이라이트 토글
        if (!$(e.target).hasClass('close-btn')) {
            if ($('.delivery-table-highlight').length > 0) {
                // 이미 하이라이트되어 있으면 제거
                $('.delivery-table-highlight').removeClass('delivery-table-highlight');
            } 
        }
    });
    
});

// X 마크 클릭 시 말풍선 닫기 함수
function closeDeliveryReminder() {
    $('#deliveryReminder').fadeOut(300);
    // 하이라이트도 제거
    $('.delivery-table-highlight').removeClass('delivery-table-highlight');
}

// 식사주문 말풍선 닫기 함수
function closeLunchReminder() {
    $('#lunchReminder').fadeOut(300);
}

// 모바일 체크 함수
function isMobileDevice() {
    return window.innerWidth <= 768;
}

// 전자결재 사이드바 닫기 함수
function closeEworksSidebar() {
    const sidebar = document.getElementById('eworksSidebar');
    const overlay = document.getElementById('eworksSidebarOverlay');

    if (sidebar && overlay) {
        // 닫기 애니메이션
        sidebar.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
        sidebar.classList.remove('active');
        
        // 오버레이 페이드 아웃
        overlay.style.transition = 'opacity 0.3s ease';
        overlay.classList.remove('active');
        
        // 배경 스크롤 복원
        document.body.style.overflow = '';
        
        // 애니메이션 완료 후 오버레이 완전히 숨기기
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

// 버튼 클릭 시 사이드바 닫기 함수
function closeEworksSidebarOnClick() {
    // 모바일에서만 작동
    if (isMobileDevice()) {
        // 약간의 지연을 두어 버튼 클릭 이벤트가 먼저 실행되도록
        setTimeout(closeEworksSidebar, 100);
    }
}

// 전자결재 사이드바 토글 함수
function toggleEworksSidebar() {
    // 모바일에서만 작동
    if (!isMobileDevice()) return;

    const sidebar = document.getElementById('eworksSidebar');
    const overlay = document.getElementById('eworksSidebarOverlay');

    if (sidebar && overlay) {
        const isActive = sidebar.classList.contains('active');

        if (isActive) {
            // 닫기
            closeEworksSidebar();
        } else {
            // 열기 애니메이션
            overlay.style.display = 'block';
            
            // 스크롤 위치 초기화
            if (sidebar) {
                sidebar.scrollTop = 0;
            }
            
            // 강제 리플로우
            void overlay.offsetWidth;
            
            setTimeout(() => {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
                
                // 모달이 화면 밖으로 나가지 않도록 보정
                requestAnimationFrame(() => {
                    const rect = sidebar.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    const viewportWidth = window.innerWidth;
                    const margin = 16; // 여백
                    
                    // 상단이 잘리는 경우
                    if (rect.top < margin) {
                        sidebar.style.top = margin + 'px';
                        sidebar.style.transform = 'translate(-50%, 0) scale(1)';
                        sidebar.style.maxHeight = (viewportHeight - (margin * 2)) + 'px';
                    }
                    // 하단이 잘리는 경우
                    else if (rect.bottom > viewportHeight - margin) {
                        sidebar.style.top = 'auto';
                        sidebar.style.bottom = margin + 'px';
                        sidebar.style.transform = 'translate(-50%, 0) scale(1)';
                        sidebar.style.maxHeight = (viewportHeight - (margin * 2)) + 'px';
                    }
                    // 양쪽 모두 잘리는 경우 (모달이 화면보다 큰 경우)
                    else if (rect.height > viewportHeight - (margin * 2)) {
                        sidebar.style.top = margin + 'px';
                        sidebar.style.bottom = 'auto';
                        sidebar.style.transform = 'translate(-50%, 0) scale(1)';
                        sidebar.style.maxHeight = (viewportHeight - (margin * 2)) + 'px';
                    }
                    // 좌우 중앙 정렬 확인
                    if (rect.left < margin) {
                        sidebar.style.left = '50%';
                    }
                    if (rect.right > viewportWidth - margin) {
                        sidebar.style.left = '50%';
                    }
                });
            }, 10);
        }
    }
}

// 전자결재 배지 총합 업데이트 함수
function updateEworksTotalBadge() {
    let total = 0;

    // 모든 개별 배지의 숫자를 합산
    for (let i = 0; i <= 5; i++) {
        const badge = document.getElementById('badge' + i);
        if (badge && badge.textContent) {
            const count = parseInt(badge.textContent) || 0;
            total += count;
        }
    }

    // 총합 배지 업데이트
    const totalBadge = document.getElementById('eworksTotalBadge');
    if (totalBadge) {
        if (total > 0) {
            totalBadge.textContent = total;
            totalBadge.classList.add('active');
        } else {
            totalBadge.classList.remove('active');
        }
    }
}

// 페이지 로드 시 초기화
$(document).ready(function() {
    // 초기 배지 업데이트
    updateEworksTotalBadge();

    // 주기적으로 배지 업데이트 (10초마다)
    setInterval(updateEworksTotalBadge, 10000);

    // 모바일 전자결재 사이드바 초기화
    if (isMobileDevice()) {
        const sidebar = document.getElementById('eworksSidebar');
        const overlay = document.getElementById('eworksSidebarOverlay');

        if (sidebar && overlay) {
            // 모바일에서는 기본적으로 숨김 상태 확보
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';

            // 리사이즈 시 열린 사이드바 닫기
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
    }
});

// 한국시간 오전 10시 이후 중식 주문 확인 함수
function checkLunchOrder() {
    // 한국시간 현재 시간 가져오기
    const now = new Date();
    const koreaTime = new Date(now.getTime() + (9 * 60 * 60 * 1000)); // UTC+9
    const currentHour = koreaTime.getHours();
    
    // 오전 10시 이후인지 확인
    if (currentHour >= 10) {
        // 중식 주문 데이터 확인 (PHP에서 전달된 변수 사용)
        const lunchDone = <?php echo json_encode($lunch_done ?? ''); ?>;
        const eatCount = <?php echo json_encode($eat_count ?? ''); ?>;
        
        // 중식 주문이 없거나 완료되지 않은 경우
        if (!lunchDone || lunchDone === '' || eatCount === '' || eatCount === '0') {
            $('#lunchReminder').fadeIn(300);
        } else {
            $('#lunchReminder').fadeOut(300);
        }
    } else {
        // 오전 10시 이전이면 말풍선 숨기기
        $('#lunchReminder').fadeOut(300);
    }
}

// 10분마다 중식 주문 확인
setInterval(checkLunchOrder, 10 * 60 * 1000); // 10분 = 10 * 60 * 1000ms

// 페이지 로드 시 즉시 확인
$(document).ready(function() {
    checkLunchOrder();
});

</script>
</body>
</html>