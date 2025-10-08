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
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    z-index: 1000;
    animation: float 3s ease-in-out infinite;
    cursor: pointer;
    max-width: 250px;
    text-align: center;
}

.delivery-reminder::before {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 20px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid #764ba2;
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
    top: 80px;
    right: 20px;
    background: linear-gradient(135deg, #20b2aa 0%, #17a2b8 100%);
    color: white;
    padding: 12px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(32, 178, 170, 0.3);
    z-index: 1000;
    animation: float 3s ease-in-out infinite;
    cursor: pointer;
    max-width: 250px;
    text-align: center;
    display: none;
}

.lunch-reminder::before {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 20px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid #17a2b8;
}

.lunch-reminder:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
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
 
<div class="sideBanner">
    <span class="text-center text-dark">&nbsp; 전자결재 </span>
     
	<?php	
		// print $eworks_level  ;		
		foreach ($tabs as $label => $tabId) {
			$badgeId = "badge" . $tabId;
    ?>
	
	<div class="mb-1 mt-1">
		 <?php if ($label !== "알림") 
			{					
					if($eworks_level && ($tabId>=3) )
					{
					  print '<button type="button" class="btn btn-dark rounded-pill" onclick="seltab(' . $tabId . '); "> ';
					  echo $label; 
					  print '<span class="badge badge-pill badge-dark" id="' . $badgeId . '"></span>';				  
					} 
					else if (!$eworks_level)  // 일반결재 상신하는 그룹
					{				
					  print '<button type="button" class="btn btn-dark rounded-pill" onclick="seltab(' . $tabId . '); "> ';
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
					UI
				</label>
			</div>
			<?php endif; ?>
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
			<button type="button" class="modern-social-btn"
					onclick="popupCenter('https://blog.naver.com/mirae8440', '', 1800, 900); return false;"
					title="미래기업 네이버 블로그">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="27" height="27" style="vertical-align:middle;">
					<rect width="100" height="100" rx="15" ry="15" fill="#00c73c"/>
					<path d="M20 20 h60 a5 5 0 0 1 5 5 v30 a5 5 0 0 1 -5 5 h-20 l-10 10 -10-10 h-20 a5 5 0 0 1 -5 -5 v-30 a5 5 0 0 1 5-5z" fill="#ffffff"/>
					<text x="50%" y="48%" text-anchor="middle" dy=".35em" font-family="Arial, sans-serif" font-weight="bold" font-size="20" fill="#f47920">N blog</text>
				</svg>
			</button>
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
			onclick="popupCenter('/graph/monthly_jamb.php','Jamb 월별 수주내역', 1500, 900); return false;">
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
			onclick="popupCenter('/graph/monthly_ceiling.php','천장 월별 수주내역', 1500, 900); return false;">
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
		<div class="card justify-content-center" style="cursor:pointer;" onclick="location.href='./error/statistics.php';">		
			<div class="card-body my-card-padding">	
				<?php include "./load_errorstatistics.php"; ?>		
			</div>   
		</div>   
		<!--  품질불량 세부내역(전체불량) -->
		<div class="modern-management-card" id="nonConformanceCost">
			<div class="modern-dashboard-header">
				<a href="./error/statistics.php" style="color: white; text-decoration: none;">
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
				<span class="fw-bold shop-header fs-6">2025년 홍천 스키여행!</span>
			</div>
				<div class="d-flex justify-content-center align-items-center mb-1">		
				<iframe width="135" height="230" src="https://www.youtube.com/embed/CpgEZMwbamU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> &nbsp;&nbsp;&nbsp;
				<iframe width="135" height="230" src="https://www.youtube.com/embed/GWBmJ-EQz8c" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>		    			
				</div>
				<div class="d-flex justify-content-center align-items-center">		
					<div class="photo-frame justify-content-center text-center">
						<?php
						for ($i = 1; $i <= 4; $i++) {
							// 사진 파일 경로 생성
							$photoPath = "img/trip/202501trip" . $i . ".jpg";
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
	<div class="card justify-content-center" style="cursor:pointer;" onclick="location.href='./error/statistics.php';">		
		<div class="card-body my-card-padding">	
			<?php include "./load_errorstatistics.php"; ?>		
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
        console.log('deliveryCountToday: ' + deliveryCountToday);
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

 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-xxl">     
	<?php } ?>
 
<!-- 권영철님 화면일때 표시함 빠른메뉴 일때 -->
<?if($submenu==1) { ?>
   <!--모바일 일때 -->
<div class="d-flex mb-5 mt-5 justify-content-center">    
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
 
 ?>
    
<!-- <div class="card">		
<div class="card-header">       	      
	<div class="d-flex mb-2 mt-2 justify-content-center">    
		<H4> <span id="advice"> </span> </H4>
	</div>  
</div>   -->
<!-- <div class="card-body">      -->
<?
// // 난수를 발생해서 이미지 불러오기 (명언 관련 이미지)

//     $rndimg = rand(1,36);
// 	$maxwidth = 400;
// 	$maxheight = 400;
	
// 	print '<br> <div class="d-flex justify-content-center"> 		 ';
// 	$imgpath = './img/goodwordgif/' . $rndimg . '.gif' ;
// 	$imgsize = getimagesize($imgpath);

// 	print '<img	src="' . $imgpath . '">  </div>';
?>  
<!-- </div>
</div> -->

<span id="displaytmp" > </span>
	<!-- 아래 dialog 태그 영역이 메시지 창 -->
	<?php if($chkMobile==false) { ?>
	<dialog id="myMsgDialog"  >
    <?php } else  { ?>
	<dialog id="myMsgDialog"  >
	<?php }  ?>
		<!-- 문의사항 등록 section-->
		<section class="py-0">   				 
		<div class="container">
			<div class="input-form-backgroud row">
			  <div class="input-form col-sm-12 mx-auto">
				<h3 class="mb-3 text-center">불량접수 리스트 알림</h3>				

				  <div class="row ">
					<div class="col-sm-2 mb-3 text-center">
					  출고일
					</div>
					<div class="col-sm-7 mb-3 text-center">
					  현장명
					</div>
					  <div class="col-sm-3 mb-3 text-center">
					  불량내용 
					</div>
				  </div>		
				  <div class="row" style="overflow-y: scroll; height:300px;	overflow-x: hidden;" >
				  <?php 
				     for($i=0;$i<count($bad_choice_arr);$i++)
					 {
						print '  <div class="row"> ';
                             print ' <div class="col-sm-2 mb-3 text-center"> ';
					           print $outdate_arr[$i];
					         print '</div>';			
                             print ' <div class="col-sm-7 mb-3"> ';
					           print $workplace_arr[$i];
					         print '</div>';			
                             print ' <div class="col-sm-3 mb-3 text-center"> ';
					           print $bad_choice_arr[$i];
					         print '</div>';						
						print '  </div>		';
					 }
				  ?>                  
				  </div>	
				   <div class="row" > </div>
				   <div class="row" > </div>
				   <div class="row" > </div>
				   <div class="row justify-content-center fs-4 mb-3  text-primary mt-3" > 불량접수 건 부적합리스트 등록 요청해 주세요.</div>
				  <div class="row justify-content-center text-center">							
						<input type="button" id="mButton" onclick="closeMsg();" value=" 창닫기 " >
						<input type="button" id="cButton" onclick="closePopup();" value="하루에 한번만 보기 " >
						</div> 		
			  </div>
			</div>
		</div>
		</section>					
	</dialog> 
</div> 
</div> 

<!-- todo Calendar -->
<?php if($chkMobile==false) { ?> 
    <div class="container">     
<?php } else { ?>
    <div class="container-xxl">     
<?php } ?>     
<div class="modern-management-card">
    <div class="modern-dashboard-header">
        📅 월간상세일정 
    </div>
    <div class="modern-dashboard-body mt-1">
        <div class="row">
            <!-- Calendar Controls -->
            <div class="col-sm-4">
              <div class="d-flex justify-content-start align-items-center">
                <button type="button" id="todo_view" class="btn btn-sm mx-1 fw-bold" style="background: #0288d1; color: white; border: none; border-radius: 6px; padding: 0.25rem 0.5rem;">
                    <i class="bi bi-chevron-down"></i>
                </button>
                <h6 class="mb-0 mx-2" style="color: #1e293b; font-weight: 600;">일정 관리</h6>
                <span class="modern-data-value" style="color: #64748b; font-size: 0.75rem;">
                    <i class="bi bi-tree-fill"></i> 연차
                </span>
                <span class="modern-data-value ms-2" style="color: #0288d1; font-size: 0.75rem;">
                    <i class="bi bi-yin-yang"></i> 설계완료
                </span>
              </div>
            </div>
            <div class="col-sm-4">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <button type="button" id="todo-prev-month" class="btn btn-sm me-2" style="background: #0288d1; color: white; border: none; border-radius: 6px; padding: 0.25rem 0.5rem;">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                    <span id="todo-current-period" class="modern-data-value me-2" style="color: #1e293b; font-weight: 600;"></span>
                    <button type="button" id="todo-next-month" class="btn btn-sm me-2" style="background: #0288d1; color: white; border: none; border-radius: 6px; padding: 0.25rem 0.5rem;">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    <button type="button" id="todo-current-month" class="btn btn-sm me-5" style="background: rgba(2, 136, 209, 0.1); color: #0288d1; border: 1px solid #0288d1; border-radius: 6px; padding: 0.25rem 0.5rem; font-weight: 600;">
                        <?php echo date("m",time()); ?> 월
                    </button>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="d-flex justify-content-end align-items-center mb-1">
                    <div class="inputWrap me-1 d-flex align-items-center">
                        <input type="text" name="searchTodo" id="searchTodo" class="form-control me-1" autocomplete="off" style="width:200px; font-size:12px; height:30px; border: 1px solid #e2e8f0; border-radius: 6px;" />
                        <button type="button" class="btnClear d-flex align-items-center justify-content-center"></button>
                    </div>
                    <button type="button" id="searchTodoBtn" class="btn btn-sm me-2 d-flex align-items-center justify-content-center" style="background: #475569; color: white; border: none; border-radius: 6px; padding: 0.25rem 0.5rem;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="todo-board">
            <div class="row d-flex">
                <div class="col-sm-5">
                </div>
                <div class="col-sm-7">
                    <!-- 필터 옵션 -->
                    <div class="d-flex justify-content-end align-items-center mb-2" style="gap: 0.75rem;">
                        <label class="radio-label d-flex align-items-center" style="cursor: pointer;">
                            <input type="radio" name="filter" id="filter_all" class="filter-radio me-2" checked>
                            <span class="modern-data-value" style="color: #475569; font-weight: 600; background: rgba(71, 85, 105, 0.1); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">전체</span>
                        </label>
                        <label class="radio-label d-flex align-items-center" style="cursor: pointer;">
                            <input type="radio" name="filter" id="filter_al" class="filter-radio me-2">
                            <span class="modern-data-value" style="color: #64748b; font-weight: 600; font-size: 0.75rem;">연차</span>
                        </label>
                        <label class="radio-label d-flex align-items-center" style="cursor: pointer;">
                            <input type="radio" name="filter" id="filter_jamb" class="filter-radio me-2">
                            <span class="modern-data-value" style="color: #059669; font-weight: 600; background: rgba(5, 150, 105, 0.1); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">쟘(jamb)</span>
                        </label>
                        <label class="radio-label d-flex align-items-center" style="cursor: pointer;">
                            <input type="radio" name="filter" id="filter_CL" class="filter-radio me-2">
                            <span class="modern-data-value" style="color: #0288d1; font-weight: 600; background: rgba(2, 136, 209, 0.1); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">천장(ceiling)</span>
                        </label>
                        <label class="radio-label d-flex align-items-center" style="cursor: pointer;">
                            <input type="radio" name="filter" id="filter_jambCL" class="filter-radio me-2">
                            <span class="modern-data-value" style="color: #059669; font-weight: 600; background: rgba(5, 150, 105, 0.1); padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">+쟘</span>
                            <span class="modern-data-value ms-1" style="color: #0288d1; font-weight: 600; background: rgba(2, 136, 209, 0.1); padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">+천장</span>
                        </label>
                        <label class="radio-label d-flex align-items-center" style="cursor: pointer;">
                            <input type="radio" name="filter" id="filter_OEM" class="filter-radio me-2">
                            <span class="modern-data-value" style="color: #0ea5e9; font-weight: 600; background: rgba(14, 165, 233, 0.1); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">외주</span>
                        </label>
                        <label class="radio-label d-flex align-items-center" style="cursor: pointer;">
                            <input type="radio" name="filter" id="filter_etc" class="filter-radio me-2">
                            <span class="modern-data-value me-5" style="color: #64748b; font-weight: 600; font-size: 0.75rem;">기타</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        <div id="todosMain-list" style="margin-top: 1rem;">
        </div>
 
        <div class="row">
            <div id="todo-calendar-container"></div>
        </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="todo-calendar-container" class="p-1"></div>
            </div>
        </div>
    </div>
</div>
</div>

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
        toggleManagementBtn.addEventListener("click", function() {
            var managementInfo = document.getElementById("managementInfo");
            var NoneManagementInfo = document.getElementById("NoneManagementInfo");

            if (managementInfo) {
                // 체크박스의 현재 상태를 확인
                var isChecked = this.checked;

                if (isChecked) {
                    // 경영정보 보이기
                    managementInfo.style.display = "block";
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
                    managementInfo.style.display = "none";
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
            }
        });
    }

    // 화물/택배 풍선 표시 조건 확인
    if (Number(deliveryCountToday) > 0) {
		console.log('실행전 조건 deliveryCountToday: ' + deliveryCountToday);
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
  console.warn('DataTable 라이브러리가 로드되지 않았습니다.');
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
    console.warn('advice.json 파일을 로드할 수 없습니다:', error);
  });

// 안전한 요소 업데이트
function safeUpdateElement(selector, value) {
  const element = $(selector);
  if (element.length > 0) {
    element.text(value);
  }
}

safeUpdateElement('#text1', '<?php echo $request_asked_count; ?>'); // 화면상단에 건수 표시  원자재 요청건
safeUpdateElement('#text8', '<?php echo $request_send_count; ?>'); // 화면상단에 건수 표시  원자재 발주보냄
safeUpdateElement('#text2', '<?php echo $request_etc_asked_count; ?>'); // 화면상단에 건수 표시 기타물품 요청건
safeUpdateElement('#text7', '<?php echo $request_etc_send_count; ?>'); // 화면상단에 건수 표시 기타물품 발주보냄
safeUpdateElement('#text5', '<?php echo $eat_count; ?>'); // 화면상단에 건수 표시 중식
safeUpdateElement('#text6', '<?php echo $aftereat_count; ?>'); // 화면상단에 건수 표시 석식
safeUpdateElement('#lunch_text', '<?php echo $lunch_text; ?>'); // 화면상단에 중식 종류
safeUpdateElement('#dinner_text', '<?php echo $dinner_text; ?>'); // 화면상단에 석식 종류
safeUpdateElement('#lunch_done', '<?php echo $lunch_done; ?>'); 
safeUpdateElement('#supper_done', '<?php echo $supper_done; ?>'); 
// 쟘 금일 정보 가져오기
safeUpdateElement('#jamb_registedate', '<?php echo $jamb_registedate; ?>'); 
safeUpdateElement('#jamb_duedate', '<?php echo $jamb_duedate; ?>'); 
safeUpdateElement('#jamb_outputdonedate', '<?php echo $jamb_outputdonedate; ?>'); 
// 천장 금일 정보 가져오기
safeUpdateElement('#ceiling_registedate', '<?php echo $ceiling_registedate; ?>'); 
safeUpdateElement('#ceiling_duedate', '<?php echo $ceiling_duedate; ?>'); 
safeUpdateElement('#ceiling_outputdonedate', '<?php echo $ceiling_outputdonedate; ?>'); 
// 덴크리 금일 정보 가져오기
safeUpdateElement('#dancre_registedate', '<?php echo $dancre_registedate; ?>'); 
safeUpdateElement('#dancre_duedate', '<?php echo $dancre_duedate; ?>'); 
safeUpdateElement('#dancre_outputdonedate', '<?php echo $dancre_outputdonedate; ?>'); 
// 다온텍 금일 정보 가져오기
$('#daontech_registedate').text('<?php echo $daontech_registedate; ?>'); 
$('#daontech_duedate').text('<?php echo $daontech_duedate; ?>'); 
$('#daontech_outputdonedate').text('<?php echo $daontech_outputdonedate; ?>'); 

const Jamb_OutputPrice = <?php echo $jambearning; ?> ;  // 싱글 퀘테이션마크를 삭제하면 숫자로 표현됨 주의!!!
const Jamb_formattedPrice = Jamb_OutputPrice.toLocaleString() ;
$("#jambearning").text(Jamb_formattedPrice); // 잠 매출

const Lc_OutputPrice = <?php echo $lcearning; ?>; 		      // 싱글 퀘테이션마크를 삭제하면 숫자로 표현됨 주의!!!
const Lc_formattedPrice = Lc_OutputPrice.toLocaleString() ;
$('#lcearning').text(Lc_formattedPrice  ) ;            // 조명천장 매출

const prejamblist = '<?php echo $prejamblist; ?>'; 		      
const preceilinglist = '<?php echo $preceilinglist; ?>'; 		  

$('#prejamblist').text(prejamblist) ;            // 전일 쟘 출고내역
$('#preceilinglist').text(preceilinglist) ;            // 전일 천장 출고내역


$('#steel_done').text('<?php echo $steel_done; ?>'); 
$('#etc_done').text('<?php echo $etc_done; ?>'); 
	
$('#eworksel').val('draft');  // 최초 전자결재 작성으로 정함

// console.log("get cookie : " + getCookie("popupYN"));				 

let admin = '<?php echo $admin; ?>';			

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
	
	var NoCheck = '<?php echo $NocheckDeviceNum ; ?>';
	var NoCheckArea = '<?php echo $NocheckAreaNum ; ?>';
	var NocheckOfficePerson = <?php echo json_encode($NocheckOfficePerson); ?>;
	
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
						console.log( jqxhr , status , error );
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
	var bad_number ='<?php echo $bad_number; ?>';
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
	
    // todo_view
    $("#todo_view").on("click", function() { 
		var showTodoView = getCookie("showTodoView");
		var todoCalendarContainer = $("#todo-list");
		if (showTodoView === "show") {
			todoCalendarContainer.css("display", "none");
			setCookie("showTodoView",  "hide"  , 10);
		} else {
			todoCalendarContainer.css("display", "inline-block");
			setCookie("showTodoView",  "show"  , 10);
		}
    });	
	
    // board_view
    $("#board_view").on("click", function() {
		var showBoardView = getCookie("showBoardView");		
		var board_list = $(".board_list");
		if (showBoardView === "show") {
			board_list.css("display", "none");
			$("#org_chart_div").hide();
			
			setCookie("showBoardView",  "hide"  , 10);
		} else {
			board_list.css("display", "inline-block");
			$("#org_chart_div").show();
			setCookie("showBoardView",  "show"  , 10);
		}		
    });	

	// 최초 실행될때 쿠키값을 기억하고 행하는 구문임.		
	var showBoardView = getCookie("showBoardView");		
	var board_list = $(".board_list");
	if (showBoardView === "show") {		
		board_list.css("display", "inline-block");		
		$("#org_chart_div").show();
	} else {
		board_list.css("display", "none");	
		$("#org_chart_div").hide();
	}	

});


// 하루동안 띄워주는 팝업창 만들기 코드
// (예시) 쿠키 저장 함수
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// (예시) 쿠키 불러오기 함수
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i].trim();
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
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
        setCookie('dailyPopupShown', 'true', 1);
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

// 한국시간 오전 10시 이후 중식 주문 확인 함수
function checkLunchOrder() {
    // 한국시간 현재 시간 가져오기
    const now = new Date();
    const koreaTime = new Date(now.getTime() + (9 * 60 * 60 * 1000)); // UTC+9
    const currentHour = koreaTime.getHours();
    
    // 오전 10시 이후인지 확인
    if (currentHour >= 10) {
        // 중식 주문 데이터 확인 (PHP에서 전달된 변수 사용)
        const lunchDone = '<?php echo $lunch_done; ?>';
        const eatCount = '<?php echo $eat_count; ?>';
        
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