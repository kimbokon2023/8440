<?php require_once __DIR__ . '/bootstrap.php'; ?>

<?php
// 모바일 감지 함수 (쿠키 확인 포함)
if (!function_exists('isMobile')) {
    function isMobile() {
        // force_pc_view 쿠키가 설정되어 있으면 PC 버전으로 강제 표시
        if (isset($_COOKIE['force_pc_view']) && $_COOKIE['force_pc_view'] == '1') {
            return false;
        }
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        // 모바일 디바이스 감지
        $mobilePattern = '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i';
        $isMobileDevice = preg_match($mobilePattern, $userAgent);
        
        // 화면 너비도 확인 (JavaScript로 설정된 경우)
        if (isset($_COOKIE['screen_width'])) {
            $screenWidth = (int)$_COOKIE['screen_width'];
            return $isMobileDevice || $screenWidth < 768;
        }
        
        return $isMobileDevice;
    }
}

// chkMobile 변수 설정 (이미 설정되어 있지 않은 경우만)
if (!isset($chkMobile)) {
    $chkMobile = function_exists('isMobile') ? isMobile() : false;
}
?>

<form id="eworks_board_form" name="eworks_board_form" method="post" enctype="multipart/form-data" >	    
	<input type="hidden" id="eworksPage" name="eworksPage" value="<?= isset($eworksPage) ? $eworksPage : '' ?>" > 
	<input type="hidden" id="e_viewexcept_id" name="e_viewexcept_id" value="<?= isset($e_viewexcept_id) ? $e_viewexcept_id : '' ?>" >   <!-- 전자결재 보기 제한 -->    
	<input type="hidden" id="e_num" name="e_num" value="<?= isset($e_num) ? $e_num : '' ?>" > 
	<input type="hidden" id="num" name="num" value="<?= isset($num) ? $num : '' ?>" > 
	<input type="hidden" id="ripple_num" name="ripple_num" value="<?= isset($ripple_num) ? $ripple_num : '' ?>" > 
	<input type="hidden" id="SelectWork" name="SelectWork" value="<?= isset($SelectWork) ? $SelectWork : '' ?>" > 
	<input type="hidden" id="eworksel" name="eworksel" value="<?= isset($eworksel) ? $eworksel : '' ?>" >    <!-- 전자결재 진행상태  draft send -->    
	<input type="hidden" id="choice" name="choice" value="<?= isset($choice) ? $choice : '' ?>" >    <!-- 전자결재 진행상태  draft send -->        
	<input type="hidden" id="approval_right" name="approval_right" value="<?= isset($approval_right) ? $approval_right : '' ?>" >   
	<input type="hidden" id="status" name="status" value="<?= isset($status) ? $status : '' ?>" >   
	<input type="hidden" id="done" name="done" value="<?= isset($done) ? $done : '' ?>" >    <!-- 전자결재 진행상태  done -->        
	<input type="hidden" id="author_id" name="author_id" value="<?= isset($author_id) ? $author_id : '' ?>" > 
	<input type="hidden" id="author" name="author" value="<?= isset($author) ? $author : '' ?>" > 
	<input type="hidden" id="ework_approval" name="ework_approval" value="<?= isset($ework_approval) ? $ework_approval : 0 ?>" > 
	
	<!-- 전자결재 단일 필드 -->
	<input type="hidden" id="eworks_item" name="eworks_item" value="<?= isset($eworks_item) ? $eworks_item : '' ?>">
	<input type="hidden" id="e_title" name="e_title" value="<?= isset($e_title) ? $e_title : '' ?>">
	<input type="hidden" id="e_line" name="e_line" value="<?= isset($e_line) ? $e_line : '' ?>">
	<input type="hidden" id="e_line_id" name="e_line_id" value="<?= isset($e_line_id) ? $e_line_id : '' ?>">
	<input type="hidden" id="e_confirm" name="e_confirm" value="<?= isset($e_confirm) ? $e_confirm : '' ?>">
	<input type="hidden" id="e_confirm_id" name="e_confirm_id" value="<?= isset($e_confirm_id) ? $e_confirm_id : '' ?>">
	<input type="hidden" id="r_line" name="r_line" value="<?= isset($r_line) ? $r_line : '' ?>">
	<input type="hidden" id="r_line_id" name="r_line_id" value="<?= isset($r_line_id) ? $r_line_id : '' ?>">
	<input type="hidden" id="contents" name="contents" value="<?= isset($contents) ? htmlspecialchars($contents, ENT_QUOTES, 'UTF-8') : '' ?>">
	<input type="hidden" id="registdate" name="registdate" value="<?= isset($registdate) ? $registdate : '' ?>">
	<input type="hidden" id="recordtime" name="recordtime" value="<?= isset($recordtime) ? $recordtime : '' ?>">

	<!-- 전자결재 관련 배열 -->	
	<input type="hidden" id="numid_arr" name="numid_arr[]">
	<input type="hidden" id="registdate_arr" name="registdate_arr[]">
	<input type="hidden" id="eworks_item_arr" name="eworks_item_arr[]">
	<input type="hidden" id="author_arr" name="author_arr[]">
	<input type="hidden" id="author_id_arr" name="author_id_arr[]">
	<input type="hidden" id="e_title_arr" name="e_title_arr[]">
	<input type="hidden" id="e_line_id_arr" name="e_line_id_arr[]">
	<input type="hidden" id="e_line_arr" name="e_line_arr[]">
	<input type="hidden" id="r_line_arr" name="r_line_arr[]">
	<input type="hidden" id="r_line_id_arr" name="r_line_id_arr[]">
	<input type="hidden" id="e_confirm_arr" name="e_confirm_arr[]">
	<input type="hidden" id="e_confirm_id_arr" name="e_confirm_id_arr[]">	  
	
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
	
 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-xxl">     
	<?php } ?>	
  
<div class="row d-flex align-items-center header-row" style="margin: 0; padding: 8px 0;">
<div class="col-4 col-sm-2 d-flex align-items-center justify-content-start header-logo-col" style="padding-left: 25px; padding-right: 8px;">
		<a href="<?= $root_dir ?>/index.php" class="d-flex align-items-center header-logo-link" style="height: 100%;">
			<?php //<img class="img-fluid" src="<?$root_dir /img/companylogo.jpg"> ?>
			<img src="<?= $root_dir ?>/img/mirae_logo.png" class="header-logo-img" style="width:100%; max-width: 120px; height: auto; object-fit: contain;">
		</a>
		<!-- 모바일 기계 접속 시에만 표시되는 PC/모바일 화면 버전 토글 버튼 (로고 밑에 작은 버튼) -->
		<?php 
		// 실제 모바일 디바이스인지 확인 (쿠키 무시, User-Agent 기반)
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
		$mobilePattern = '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i';
		$isActualMobileDevice = preg_match($mobilePattern, $userAgent);
		?>
		<?php if($isActualMobileDevice) : ?>
		<div class="btn-group btn-group-sm mt-1" role="group" aria-label="화면 버전 전환" style="font-size: 0.65rem; width: 100%; max-width: 120px;">
			<button type="button" class="btn btn-sm <?php echo (isset($_COOKIE['force_pc_view']) && $_COOKIE['force_pc_view'] == '1') ? 'btn-primary' : 'btn-outline-primary'; ?>" id="pcVersionToggle" onclick="togglePCVersion(true);" style="padding: 0.2rem 0.4rem; font-size: 0.65rem; flex: 1;">
				<i class="bi bi-display"></i> PC
			</button>
			<button type="button" class="btn btn-sm <?php echo (!isset($_COOKIE['force_pc_view']) || $_COOKIE['force_pc_view'] != '1') ? 'btn-primary' : 'btn-outline-primary'; ?>" id="mobileVersionToggle" onclick="togglePCVersion(false);" style="padding: 0.2rem 0.4rem; font-size: 0.65rem; flex: 1;">
				<i class="bi bi-phone"></i> 모바일
			</button>
		</div>
		<?php endif; ?>
	</div>
<div class="col-8 col-sm-10 d-flex justify-content-end align-items-center header-menu-col" style="padding: 0 8px;">
	<!-- 모바일 햄버거 메뉴 버튼 -->
	<button class="btn btn-primary d-md-none mobile-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavMenu" aria-controls="mobileNavMenu" style="display: flex; align-items: center; justify-content: center;">
		<i class="bi bi-list" style="font-size: 1.5rem;"></i>
	</button>

	<!-- PC 네비게이션 -->
	<nav class="navbar navbar-expand navbar-custom d-none d-md-flex">
	<div class="navbar-nav ">
            <div class="nav-item" id="home-menu">
			<!-- 드롭다운 메뉴-->
			<a class="nav-link" href="<?= $root_dir ?>/index.php?home=1" title="mirae Homepage"  ><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-house-check-fill" viewBox="0 0 16 16">
			  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
			  <path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
			  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.707l.547.547 1.17-1.951a.5.5 0 1 1 .858.514Z"/>
			</svg> </a>
						
			<!-- 큰 메뉴 -->	
			<?php if($_SESSION["level"] !==20 && $_SESSION["level"] < 6) : ?>
			<!-- <div class="sitemap-dropdown">      
				<div class="row p-2 m-2" >       
				<div class="col" >	      
					<b> <a class="text-primary" href="../work/list.php">JAMB(쟘)</a>  </b>
					<a class="dropdown-item  " href="../work/list.php">수주 현황</a>
					<a class="dropdown-item  " href="../work/month_schedule.php"> 월간 생산일정</a>						
					<a class="dropdown-item  " href="../work/work_statistics.php"> 제조 통계  </a>
					<a class="dropdown-item  " href="../work_voc/list.php">시공소장 VOC</a>
					<a class="dropdown-item  " href="../work/picgal.php"> 시공 사진</a>
					<a class="dropdown-item  " href="../work/list_hpi.php"> 업체별 HPI정보</a>					
					<a class="dropdown-item  " href="../work/statistics.php"> 시공비 통계</a>
					<a class="dropdown-item  " href="../work/workfee.php"> 시공비 결산</a>
					<a class="dropdown-item  " href="../graph/monthly_jamb.php?header=header"> 수주통계 </a>
					<a class="dropdown-item  " href="../work/output_statis.php"> 매출통계 </a>
					<a class="dropdown-item  " href="../work/front_log.php"> Front Log</a>
				</div>
				<div class="col">
				   <b> <a class="text-primary" href="../ceiling/list.php">수주 리스트</a> </b>
				   <a class="dropdown-item " href="../ceiling/list.php">수주현황</a>
				   <a class="dropdown-item " href="../ceiling/month_schedule.php">월간 생산일정</a>				   
				   <a class="dropdown-item " href="../ceiling/work_statistics.php">제조 통계</a>
				   <a class="dropdown-item " href="../outorder/list.php">외주(덴크리,서한,다온텍)</a>
				   <a class="dropdown-item " href="../oem/list.php">서한(NP)-이전사용</a>
				   <a class="dropdown-item " href="../sillcover/list.php">[재료분리대 출고]</a>
				   <a class="dropdown-item " href="../make/list.php">도장발주</a>
				   <a class="dropdown-item " href="../graph/monthly_ceiling.php?header=header"> 수주통계 </a>
				   <a class="dropdown-item " href="../ceiling/output_statis.php"> 매출통계 </a>
				   <a class="dropdown-item " href="../ceiling/front_log.php">Front Log</a>
				</div>
				<div class="col">
				   <b> <span class="text-primary"> 구매&자재 </span> </b>
				   <a class="dropdown-item " href="../askitem/list.php">품의서</a>                    
				   <a class="dropdown-item " href="../request/list.php">원자재 구매신청</a>				   
				   <a class="dropdown-item " href="../steel/list.php">원자재 출고</a>
				   <a class="dropdown-item " href="../steel/rawmaterial.php">원자재 재고현황</a>
				   <a class="dropdown-item " href="../request_etc/list.php">부자재 구매신청</a>
				   <a class="dropdown-item " href="../ceiling/list_part_table.php">부자재 재고현황</a>                    
				   <a class="dropdown-item " href="../delivery/list.php"> <i class="bi bi-truck"></i> 화물/택배 배송 </a>
				</div>
				<div class="col">
				   <b> <a class="text-primary" href="../iso/list.php">품질ISO,EQ</a> </b>				   
				   <a class="dropdown-item " href="../QC/goal.php">품질방침/품질목표</a>
				   <a class="dropdown-item " href="../iso/list.php">ISO 9001/14001</a>
				   <a class="dropdown-item " href="../errors/qc_method.php">품질불량 관리기법/교육 </a>
				   <a class="dropdown-item " href="../idea/index.php">직원 제안제도 운영</a>
				   <a class="dropdown-item " href="../errors/index.php">부적합 보고</a>
				   <a class="dropdown-item " href="../errors/statistics.php">부적합(품질)통계</a>
				   <a class="dropdown-item " href="../errormeeting/index.php">부적합개선(분임조)</a>
				   <a class="dropdown-item " href="../p_workstandard/list.php">작업표준서</a>
				   <a class="dropdown-item " href="../p_qccontrol/list.php">QC 공정표</a>
				   <a class="dropdown-item " href="../p_inspection/list.php">출하 검사서</a>
				   <a class="dropdown-item " href="../qc/menu.php">장비 점검</a>
				   <a class="dropdown-item " href="../qcoffice/menu.php">사무실 정비</a>
				   <a class="dropdown-item " href="../p_evaluation/list.php">협력업체 평가표</a>
				</div>
				<div class="col">
				  <b> <a class="text-primary" href="../s_board/list.php">안전보건</a> </b>
				   <a class="dropdown-item " href="../s_board/list.php">안전보건 통합관리</a>
				   <a class="dropdown-item " href="../safety/index.php">위험성 평가</a>
				   <a class="dropdown-item " href="../RiskAssessment/list.php">위험성 평가 자료실</a>
				   <a class="dropdown-item " href="../safetycard/menu.php">안전 카드뉴스</a>
				   <a class="dropdown-item " href="../safety/law.php">고용노동부고시(지침)</a>
				   <a class="dropdown-item " href="../safety/sif/list.php">핵심위험요인SIF 평가표</a>
				</div>
				<div class="col">
				  <b> <a class="text-primary" href="../RnD/list.php">연구소</a> </b>	  
					<a class="dropdown-item" href="../ask_rndplan/list.php">연구개발계획서</a>
					<a class="dropdown-item" href="../ask_rndnote/list.php">연구노트</a>
					<a class="dropdown-item" href="../ask_rndreport/list.php">연구개발보고서</a>				  
				   <a class="dropdown-item " href="../RnDfund/list.php">연구전담부서 운영비</a>				  
				   <a class="dropdown-item " href="https://www.rnd.or.kr/user/main.do" target="_blank">연구개발전담부서 </a>
				   <a class="dropdown-item " href="https://cloud.koita.or.kr/#/login" target="_blank"> 산기협 연구노트 로그인 </a>				   
				   <a class="dropdown-item " href="https://research.rnd.or.kr/krc/" target="_blank">  연구개발활동 입력확인 </a>
				   <a class="dropdown-item " href="https://www.koita.or.kr/" target="_blank"> 한국산업기술진흥협회 </a>	
				   <a class="dropdown-item " href="http://workpt.co.kr" target="_blank">  Work Portal</a>	
				   <a class="dropdown-item " href="../RnD/list.php">연구소 게시판</a>				   				   
				   <a class="dropdown-item " href="../RnDnotice/list.php">개발 공지&자료</a>
				   <a class="dropdown-item " href="../RnDlist/list.php">개발 진행현황</a>
				   <a class="dropdown-item " href="../AIprompt/list.php">AI prompt</a>
				</div>
				<div class="col">
				  <b> <a class="text-primary" href="../notice/list.php">게시&코딩</a> </b>
				   <a class="dropdown-item " href="../notice/list.php">공지사항</a>
				   <a class="dropdown-item " href="../qna/list.php">자료실</a>
				   <a class="dropdown-item " href="../HRboard/list.php">인사/교육/총무</a>
				   <a class="dropdown-item " href="../vote/list.php">투표</a>
                    <a class="dropdown-item " href="<?= getBaseUrl() ?>/school" target="_blank">  코딩강의 </a>	
                    <a class="dropdown-item " href="<?= getBaseUrl() ?>/quiz" target="_blank">  코딩퀴즈 </a>	
				   <a class="dropdown-item " href="https://www.youtube.com/watch?v=GjawjeSDRM0&list=PLS4D8xUyesvcgvy6d9vFjJpRiUFZblaai" target="_blank">  코딩팟 캐스트 </a>	
				</div>				
				<div class="col">
				  <b> <a class="text-primary" href="../youtube.php">공유</a> </b>				  
				   <a class="dropdown-item " href="../youtube.php">미래기업</a>
				   <a class="dropdown-item " href="../annualleave/index.php">연차 휴가</a>
				   <a class="dropdown-item " href="../absent_office/index.php">사무실 근태관리</a>
				   <a class="dropdown-item " href="../absent/index.php">공장 근태관리</a>
				   <a class="dropdown-item " href="../daylaborer/index.php">일용직 근태관리</a>
				   <a class="dropdown-item " href="../afterorder/index.php">중식석식 주문</a>	   
				   <a class="dropdown-item " href="../fund/list.php">공동자금</a>
				   <a class="dropdown-item " href="../roadview.php">직원연락처</a>
				   <a class="dropdown-item " href="../shop/index.php">금속 작품쇼핑몰</a>
				   <a class="dropdown-item " href="../jamb/jamb.php">잠설계모델링</a>
				   <a class="dropdown-item " href="../roulette/index.php">룰렛(roulette)</a>
				   <a class="dropdown-item " href="../roulette/index_dart.php">다트 선정 룰렛</a>
				   <a class="dropdown-item " href="../tetris/index.php">테트리스 게임</a>
				</div>
				<div class="col">
				  <b> <a class="text-primary" href="../youtube.php">전자결재</a> </b>				  
				  <a class="dropdown-item " href="../askitem/list.php">품의서</a>         
				  <a class="dropdown-item " href="../annualleave/index.php">연차 휴가</a>          

				</div>
				</div>
			</div>    -->
			<?php endif; ?>
		</div>		
		<?php if($_SESSION["level"] !==20 && $_SESSION["level"] < 6) : ?>
            <div class="nav-item dropdown ">			 			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    JAMB
                </a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/work/list.php">
						<i class="bi bi-card-checklist"></i> 수주 현황
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/month_schedule.php">
						<i class="bi bi-calendar-week"></i> 월간 생산일정
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/work_voc/list.php">
						<i class="bi bi-ear"></i> 시공소장 VOC
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/picgal.php">
						<i class="bi bi-images"></i> 시공 사진
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/list_hpi.php">
						<i class="bi bi-megaphone"></i> 업체별 HPI정보
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/workfee.php">
						<i class="bi bi-calculator"></i> 시공비 결산
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">					
					<a class="dropdown-item" href="<?=$root_dir?>/work/statistics.php">
						<i class="bi bi-person-workspace"></i> 시공비 통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/work_statistics.php">
						<i class="bi bi-bar-chart-line-fill"></i> 제조 통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/graph/monthly_jamb.php?header=header">
						<i class="bi bi-bar-chart-line-fill"></i> 수주통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/output_statis.php">
						<i class="bi bi-graph-up-arrow"></i> 매출통계
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/work/front_log.php">
						<i class="bi bi-infinity"></i> Front Log
					</a>
				</div>

            </div>
            <div class="nav-item dropdown">			 			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle"  href="#" data-toggle="dropdown">
                   수주
                </a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/list.php">
						<i class="bi bi-card-checklist"></i> 수주현황
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/month_schedule.php">
						<i class="bi bi-calendar-week"></i> 월간 생산일정
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">					
					<a class="dropdown-item" href="<?=$root_dir?>/sillcover/list.php">
						<i class="bi bi-bookshelf"></i> [재료분리대 출고]
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/work_statistics.php">
						<i class="bi bi-bar-chart-line-fill"></i> 제조 통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/graph/monthly_ceiling.php?header=header">
						<i class="bi bi-bar-chart-line-fill"></i> 수주통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/output_statis.php">
						<i class="bi bi-graph-up-arrow"></i> 매출통계
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/front_log.php">
						<i class="bi bi-infinity"></i> Front Log
					</a>
				</div>
            </div> 
			<?php endif; ?>
			<?php if($_SESSION["level"] !==20 && $_SESSION["level"] < 6) : ?>
			<div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->				
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    구매/발주/자재
                </a>								
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/integratedordering/list.php">
						<i class="bi bi-clipboard-check"></i> 통합 발주 현황
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/orders/index.php">
						<i class="bi bi-clipboard-check"></i> 구매발주서 관리
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/send_email_list/index.php">
						<i class="bi bi-clipboard-check"></i> 발주 이메일전송 리스트 
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/corp/index.php">
						<i class="bi bi-building"></i> 발주 거래처 관리
					</a>    			
					<hr style="margin:7px!important; border-color:#007bff;">							
					<a class="dropdown-item" href="<?=$root_dir?>/askitem/list.php">
						<i class="bi bi-basket3"></i> 품의서
					</a>    		
					<hr style="margin:7px!important; border-color:#007bff;">								
					<a class="dropdown-item" href="<?=$root_dir?>/request/list.php">
						<i class="bi bi-cart-check"></i> 원자재 구매신청
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/steel/list.php">
						<i class="bi bi-box-arrow-up-right"></i> 원자재 출고
					</a>                    					
					<a class="dropdown-item" href="<?=$root_dir?>/steel/rawmaterial.php">
						<i class="bi bi-box-seam"></i> 원자재 재고현황
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/request_etc/list.php">
						<i class="bi bi-cart-check"></i> 부자재 구매신청
					</a>                                                
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/list_part_table.php">
						<i class="bi bi-archive"></i> 부자재 재고현황
					</a>	
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/outorder/list.php">
						<i class="bi bi-diagram-3"></i> 외주(덴크리,서한,다온텍)
					</a>									
					<a class="dropdown-item" href="<?=$root_dir?>/make/list.php">
						<i class="bi bi-bag-check"></i> 도장발주
					</a>									
					<a class="dropdown-item" href="<?=$root_dir?>/delivery/list.php">
						<i class="bi bi-truck"></i> 화물/택배 배송
					</a>	
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/afterorder/index.php">
						<i class="bi bi-cup-straw"></i> 중식석식 주문
					</a>			
					<hr style="margin:7px!important; border-color:#007bff;">						
					<a class="dropdown-item" href="<?=$root_dir?>/oem/list.php">
						<i class="bi bi-exclamation-triangle-fill"></i> 구 서한(NP)-이전메뉴
					</a>						
				</div>
            </div>							
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    품질(EQ/ISO)
                </a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/QC/goal.php">
						<i class="bi bi-1-circle-fill"></i> 품질방침/품질목표
					</a>				
					<a class="dropdown-item" href="<?=$root_dir?>/iso/list.php">
						<i class="bi bi-globe2"></i> ISO 9001/14001 인증
					</a>		
					<a class="dropdown-item" href="<?=$root_dir?>/errors/qc_method.php">
						<i class="bi bi-sliders2-vertical"></i> 품질불량 관리기법/교육
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/idea/index.php">
						<i class="bi bi-person-plus-fill"></i> 직원 제안제도 운영
					</a>				
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/errors/index.php">
						<i class="bi bi-file-earmark-text-fill"></i> 부적합 보고
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/errors/statistics.php">
						<i class="bi bi-bar-chart-line-fill"></i> 부적합(품질)통계
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/errormeeting/index.php">
						<i class="bi bi-person-gear"></i> 부적합개선(품질분임조)
					</a>				
					<hr style="margin:15px!important; border-color:#007bff;">						
					<a class="dropdown-item" href="<?=$root_dir?>/process_guide/index.php">
						<i class="bi bi-gear-wide-connected"></i> 작업 공정
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/p_workstandard/list.php">
						<i class="bi bi-diagram-3-fill"></i> 작업표준서
					</a>                    
					<a class="dropdown-item" href="<?=$root_dir?>/p_qccontrol/list.php">
						<i class="bi bi-kanban-fill"></i> QC 공정표
					</a>   
					<a class="dropdown-item" href="<?=$root_dir?>/p_inspection/list.php">
						<i class="bi bi-upc-scan"></i> 출하 검사서
					</a>   
					<hr style="margin:15px!important; border-color:#007bff;">						
					<a class="dropdown-item" href="<?=$root_dir?>/qc/menu.php">
						<i class="bi bi-clipboard2-check-fill"></i> 장비 점검
					</a>                    
					<a class="dropdown-item" href="<?=$root_dir?>/qcoffice/menu.php">
						<i class="bi bi-building-gear"></i> 사무실 정비
					</a>       
					<hr style="margin:15px!important; border-color:#007bff;">						             
					<a class="dropdown-item" href="<?=$root_dir?>/p_evaluation/list.php">
						<i class="bi bi-people-fill"></i> 협력업체 평가표
					</a> 
				</div>
            </div>			
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    안전보건/위험성
                </a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?=$root_dir?>/s_board/list.php">
					<i class="bi bi-calendar-plus"></i> 안전보건 통합관리
				</a>										
				<a class="dropdown-item" href="<?=$root_dir?>/safety/index.php">
					<i class="bi bi-award-fill"></i> 위험성 평가
				</a>					
				<a class="dropdown-item" href="<?=$root_dir?>/RiskAssessment/list.php">
					<i class="bi bi-list-task"></i> 위험성 평가 자료실
				</a>		
				<hr style="margin:7px!important; border-color:#007bff;">
				<a class="dropdown-item" href="<?=$root_dir?>/safetycard/menu.php">
					<i class="bi bi-exclamation-triangle-fill"></i> 안전 카드뉴스
				</a>					
				<a class="dropdown-item" href="<?=$root_dir?>/safety/law.php">
					<i class="bi bi-journal-bookmark-fill"></i> 고용노동부고시(지침)
				</a>										
				<a class="dropdown-item" href="<?=$root_dir?>/safety/sif/list.php">
					<i class="bi bi-qr-code-scan"></i> 핵심위험요인SIF 평가표
				</a>		
			</div>

            </div>					
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    연구소/개발
                </a> 
				<div class="dropdown-menu">				
					<a class="dropdown-item" href="<?=$root_dir?>/ask_rndplan/list.php">
						<i class="bi bi-journal-medical"></i> 연구개발계획서
					</a>			
					<a class="dropdown-item" href="<?=$root_dir?>/ask_rndnote/list.php">
						<i class="bi bi-journal-medical"></i> 연구노트
					</a>			
					<a class="dropdown-item" href="<?=$root_dir?>/ask_rndreport/list.php">
						<i class="bi bi-journal-medical"></i> 연구개발보고서
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/RnDfund/list.php">
						<i class="bi bi-credit-card"></i> 연구전담부서 운영비
					</a>
					<a class="dropdown-item" href="https://www.rnd.or.kr/user/main.do" target="_blank">
						<i class="bi bi-building"></i> 연구개발전담부서
					</a>
					<a class="dropdown-item" href="https://cloud.koita.or.kr/#/login" target="_blank">
						<i class="bi bi-journal-text"></i> 산기협 연구노트 로그인
					</a>
					<a class="dropdown-item" href="https://research.rnd.or.kr/krc/" target="_blank">
						<i class="bi bi-pencil-square"></i> 연구개발활동 입력확인
					</a>
					<a class="dropdown-item" href="https://www.koita.or.kr/" target="_blank">
						<i class="bi bi-globe"></i> 한국산업기술진흥협회
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/RnD/list.php">
						<i class="bi bi-easel"></i> 연구소 게시판
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">										
					<a class="dropdown-item" href="<?=$root_dir?>/it/index.php">
						<i class="bi bi-megaphone-fill"></i> IT 개발 프로세트 소개
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">		
					<a class="dropdown-item" href="<?=$root_dir?>/RnDnotice/list.php">
						<i class="bi bi-megaphone-fill"></i> 개발 공지&자료
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/RnDlist/list.php">
						<i class="bi bi-kanban"></i> 개발 진행현황
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/AIprompt/list.php">
						<i class="bi bi-robot"></i> AI prompt
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/channel/list.php">
						<i class="bi bi-robot"></i> 연구 유튜브 채널분석
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/ad/index.php">
						<i class="bi bi-robot"></i> 미래기업 IT 사업 메뉴얼
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
						<a class="dropdown-item" href="https://8440.co.kr/school" target="_blank">
					<i class="bi bi-laptop"></i> 코딩강의
					</a>
					<a class="dropdown-item" href="https://8440.co.kr/quiz" target="_blank">
					<i class="bi bi-question-circle"></i> 코딩퀴즈
					</a>
					<a class="dropdown-item" href="https://www.youtube.com/watch?v=GjawjeSDRM0&list=PLS4D8xUyesvcgvy6d9vFjJpRiUFZblaai" target="_blank">
					<i class="bi bi-youtube"></i> 코딩팟캐스트
					</a>					
				</div>


            </div>					
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    근태관리
                </a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?=$root_dir?>/annualleave/index.php">
					<i class="bi bi-person-bounding-box"></i> 연차
				</a>
				<hr style="margin:7px!important; border-color:#007bff;">
				<a class="dropdown-item" href="<?=$root_dir?>/request_overtime/index.php">
					<i class="bi bi-clock-history"></i> 연장근무(잔업/특근) 사전승인 신청
				</a>
				<hr style="margin:7px!important; border-color:#007bff;">
				<a class="dropdown-item" href="<?=$root_dir?>/absent_office/index.php">
					<i class="bi bi-people-fill"></i> 사무실
				</a>
				<a class="dropdown-item" href="<?=$root_dir?>/absent/index.php">
					<i class="bi bi-building-gear"></i> 공장
				</a>
				<a class="dropdown-item" href="<?=$root_dir?>/daylaborer/index.php">
					<i class="bi bi-person-workspace"></i> 일용직
				</a>    				
				<hr style="margin:7px!important; border-color:#007bff;">
				<a class="dropdown-item" href="<?=$root_dir?>/holiday/list.php?header=header">
					<i class="bi bi-calendar-event-fill"></i> 달력 휴일설정
				</a>
			</div>
			</div>
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    게시
                </a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?=$root_dir?>/notice/list.php">
					<i class="bi bi-megaphone"></i> 공지사항
				</a>                    
				<a class="dropdown-item" href="<?=$root_dir?>/qna/list.php">
					<i class="bi bi-folder-symlink"></i> 자료실
				</a>                    			
				<hr style="margin:7px!important; border-color:#007bff;">
				<a class="dropdown-item" href="<?=$root_dir?>/popupwindow/list.php">
					<i class="bi bi-window-stack"></i> 팝업창
				</a>                    			
				<a class="dropdown-item" href="<?=$root_dir?>/HRboard/list.php">
					<i class="bi bi-people-fill"></i> 인사/교육/총무
				</a>                    			
				<a class="dropdown-item" href="<?=$root_dir?>/vote/list.php">
					<i class="bi bi-check2-square"></i> 투표
				</a>     				
			</div>

            </div>                      
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    공유
                </a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/youtube.php">
						<i class="bi bi-youtube"></i> 미래기업
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/fund/list.php">
						<i class="bi bi-piggy-bank-fill"></i> 공동자금
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/roadview.php">
						<i class="bi bi-geo-alt-fill"></i> 직원연락처
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/shop/index.php">
						<i class="bi bi-puzzle-fill"></i> 금속 작품쇼핑몰
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/jamb/jamb.php">
						<i class="bi bi-boxes"></i> 잠설계모델링
					</a>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/roulette/index.php">
						<i class="bi bi-record-circle"></i> 룰렛(roulette)
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/roulette/index_dart.php">
						<i class="bi bi-bullseye"></i> 다트 선정 룰렛
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/tetris/index.php">
						<i class="bi bi-controller"></i> 테트리스 게임
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/race/index.php">
						<i class="bi bi-controller"></i> 동물 경주 게임
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/ranking/index.php">
						<i class="bi bi-gift"></i> 선물 순위 선정
					</a>
				</div>
            </div>    	
			<?php if($_SESSION["level"] ==20 || $_SESSION["level"] < 6) : ?>
            <div class="nav-item dropdown flex-fill">			 			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle text-warning"  href="#" data-toggle="dropdown">
                   <i class="bi bi-gem"></i> 포미스톤
                </a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/phomi/list_estimate.php">
						<i class="bi bi-receipt"></i> 견적서
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/phomi/list.php">
						<i class="bi bi-card-checklist"></i> 수주현황
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/phomi/list_outorder.php">
						<i class="bi bi-box-arrow-right"></i> 출고요청서
					</a>	
					<?php if($_SESSION["level"] !== 20) : // 대리점코드가 아닐때 ?>				
					<a class="dropdown-item" href="<?=$root_dir?>/phomi/list_deposit.php">
						<i class="bi bi-cash-coin"></i> 본사입금(예치금)
					</a>					
					<?php endif; ?>
					<a class="dropdown-item" href="<?=$root_dir?>/phomi/unit_price.php">
						<i class="bi bi-currency-dollar"></i> 단가표
					</a>									
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="https://phomistonekorea.co.kr/index.php" target="_blank">
						<i class="bi bi-globe"></i> 미래기업 포미스톤 웹
					</a>					
					<a class="dropdown-item" href="https://phomi.co.kr/default/index.php" target="_blank">
						<i class="bi bi-globe"></i> 본사 포미스톤 웹사이트
					</a>					
				</div>
            </div>
			<?php endif; ?>								
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                 <a class="nav-link  dropdown-toggle" href="#"  data-toggle="dropdown" >  <?=$_SESSION["name"]?>님  </a>                
					<div class="dropdown-menu">                   
						<a class="dropdown-item" href="<?=$root_dir?>/login/logout.php">
							<i class="bi bi-box-arrow-right"></i> 로그아웃
						</a>                    
						<a class="dropdown-item" href="<?=$root_dir?>/member/updateForm.php?id=<?=$_SESSION["userid"]?>">
							<i class="bi bi-person-gear"></i> 정보수정
						</a>           
						<hr style="margin:7px!important; border-color:#007bff;">         
						<?php if ($_SESSION["name"] == '김보곤' || $_SESSION["name"] == '소현철') { ?>
							<a class="dropdown-item" href="<?=$root_dir?>/member/list.php">
								<i class="bi bi-person-lines-fill"></i> 회원관리
							</a> 
						<?php } ?>   		
						<hr style="margin:7px!important; border-color:#007bff;">	
						<?php if ($_SESSION["name"] == '김보곤' || $_SESSION["name"] == '소현철') { ?>													
						<a class="dropdown-item" href="<?=$root_dir?>/logdata_python.php">
							<i class="bi bi-terminal-fill"></i> 파이썬 자동설계 기록
						</a> 
						<?php } ?>
						<?php if ($_SESSION["name"] == '김보곤' || $_SESSION["name"] == '소현철') { ?>								
							<a class="dropdown-item" href="<?=$root_dir?>/logdata.php">
								<i class="bi bi-clock-history"></i> 로그인기록
							</a> 
						<?php } ?>     
						<?php
						if ($_SESSION["name"] == '김보곤') {
							echo '<a class="dropdown-item" href="' . getBaseUrl() . '/logdata_menu.php">
									<i class="bi bi-menu-button-wide-fill"></i> 메뉴접속기록
								  </a>';
						}
						if ($_SESSION["name"] == '김보곤' || $_SESSION["name"] == '소현철') {
							?>
							<a class="dropdown-item" href="<?=getBaseUrl()?>/automan/list.php">
								<i class="bi bi-calculator-fill"></i> 전산실장 정산
							</a> 
						<?php } ?>                    
					</div>
            </div>				
			<div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
				<!-- 전자결재 관련 알람 -->
				<a class="nav-link dropdown-toggle" href="#" onclick="seltab(3);"> 				 
					<span id="alert_eworks_bell" style="display:none; font-size:15px;">🔔결재</span> <!-- 종 아이콘 -->
					<i class="bi bi-file-earmark-text"></i>  
					<span id="alert_eworks"></span> <!-- 알림 버튼 -->
					전자결재 
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item " href="<?=$root_dir?>/ask_rndnote_mirae/list.php"> 
						<i class="bi bi-journal-medical"></i> 프로젝트 개발진행 노트
					</a>         
					<a class="dropdown-item" href="<?=$root_dir?>/ask_rndnote/list.php">
						<i class="bi bi-journal-medical"></i> 연구노트
					</a>				
					<hr style="margin:7px!important; border-color:#007bff;">		
					<a class="dropdown-item " href="<?=$root_dir?>/askitem/list.php"> 
						<i class="bi bi-basket3"></i> 품의서
					</a>         
					<a class="dropdown-item" href="<?=$root_dir?>/annualleave/index.php">
						<i class="bi bi-person-bounding-box"></i> 연차
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/absent_office/index.php"> 
						<i class="bi bi-people-fill"></i> 사무실 근태
					</a>
					<?php if($user_name  == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지') : ?>
					<a class="dropdown-item" href="<?=$root_dir?>/absent/index.php">
						<i class="bi bi-building-gear"></i> 공장 근태
					</a>
					<?php endif; ?>
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/idea/index.php">
						<i class="bi bi-person-plus-fill"></i> 직원 제안제도 운영
					</a>		
					<hr style="margin:7px!important; border-color:#007bff;">
					<a class="dropdown-item" href="<?=$root_dir?>/errors/index.php">
						<i class="bi bi-file-earmark-text-fill"></i> 부적합 보고
					</a>								

				</div>
            </div>   
			<?php endif; ?>
			<?php if($_SESSION["level"] ==20) : ?>
				<div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                 <a class="nav-link  dropdown-toggle" href="#"  data-toggle="dropdown" >  <?=$_SESSION["name"]?>님  </a>                
					<div class="dropdown-menu">                   
						<a class="dropdown-item" href="<?=$root_dir?>/login/logout.php">
							<i class="bi bi-box-arrow-right"></i> 로그아웃
						</a>                    
						<a class="dropdown-item" href="<?=$root_dir?>/member/updateForm.php?id=<?=$_SESSION["userid"]?>">
							<i class="bi bi-person-gear"></i> 정보수정
						</a>        
					</div> 
            </div>				
			<?php endif; ?>			
		</nav>      
  </div>  			
</div>  	  
<?php 
	// 전자결재 관련 모달
	include getDocumentRoot() . "/eworks/list_form.php" ;   
	include getDocumentRoot() .  "/eworks/write_form.php" ;   
?>
	<div class="d-flex" >
		<div class="sideEworksBanner" style="display:none;">
			<span class="text-center text-dark">
				<img src="<?=getBaseUrl()?>/img/eworks_reach.png" >
			</span>
		</div>
	</div>
</div>

<!-- 모바일 오프캔버스 메뉴 -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileNavMenu" aria-labelledby="mobileNavMenuLabel" style="width: 85%; max-width: 360px;">
	<div class="offcanvas-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
		<h5 class="offcanvas-title" id="mobileNavMenuLabel">
			<i class="bi bi-menu-button-wide"></i> 메뉴
		</h5>
		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body p-0">
		<div class="accordion accordion-flush" id="mobileMenuAccordion">
			<?php if($_SESSION["level"] !==20 && $_SESSION["level"] < 6) : ?>

			<!-- 홈 -->
			<div class="mobile-menu-item">
				<a href="<?= $root_dir ?>/index.php?home=1" class="mobile-menu-link">
					<i class="bi bi-house-check-fill"></i>
					<span>홈</span>
				</a>
			</div>

			<!-- JAMB -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJamb">
						<i class="bi bi-wrench-adjustable-circle"></i> JAMB
					</button>
				</h2>
				<div id="collapseJamb" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/work/list.php" class="mobile-sub-link"><i class="bi bi-card-checklist"></i> 수주 현황</a>
						<a href="<?=$root_dir?>/work/month_schedule.php" class="mobile-sub-link"><i class="bi bi-calendar-week"></i> 월간 생산일정</a>
						<a href="<?=$root_dir?>/work_voc/list.php" class="mobile-sub-link"><i class="bi bi-ear"></i> 시공소장 VOC</a>
						<a href="<?=$root_dir?>/work/picgal.php" class="mobile-sub-link"><i class="bi bi-images"></i> 시공 사진</a>
						<a href="<?=$root_dir?>/work/list_hpi.php" class="mobile-sub-link"><i class="bi bi-megaphone"></i> 업체별 HPI정보</a>
						<a href="<?=$root_dir?>/work/workfee.php" class="mobile-sub-link"><i class="bi bi-calculator"></i> 시공비 결산</a>
						<a href="<?=$root_dir?>/work/statistics.php" class="mobile-sub-link"><i class="bi bi-person-workspace"></i> 시공비 통계</a>
						<a href="<?=$root_dir?>/work/work_statistics.php" class="mobile-sub-link"><i class="bi bi-bar-chart-line-fill"></i> 제조 통계</a>
						<a href="<?=$root_dir?>/graph/monthly_jamb.php?header=header" class="mobile-sub-link"><i class="bi bi-bar-chart-line-fill"></i> 수주통계</a>
						<a href="<?=$root_dir?>/work/output_statis.php" class="mobile-sub-link"><i class="bi bi-graph-up-arrow"></i> 매출통계</a>
						<a href="<?=$root_dir?>/work/front_log.php" class="mobile-sub-link"><i class="bi bi-infinity"></i> Front Log</a>
					</div>
				</div>
			</div>

			<!-- 수주 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuju">
						<i class="bi bi-clipboard-check"></i> 수주
					</button>
				</h2>
				<div id="collapseSuju" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/ceiling/list.php" class="mobile-sub-link"><i class="bi bi-card-checklist"></i> 수주현황</a>
						<a href="<?=$root_dir?>/ceiling/month_schedule.php" class="mobile-sub-link"><i class="bi bi-calendar-week"></i> 월간 생산일정</a>
						<a href="<?=$root_dir?>/sillcover/list.php" class="mobile-sub-link"><i class="bi bi-bookshelf"></i> [재료분리대 출고]</a>
						<a href="<?=$root_dir?>/ceiling/work_statistics.php" class="mobile-sub-link"><i class="bi bi-bar-chart-line-fill"></i> 제조 통계</a>
						<a href="<?=$root_dir?>/graph/monthly_ceiling.php?header=header" class="mobile-sub-link"><i class="bi bi-bar-chart-line-fill"></i> 수주통계</a>
						<a href="<?=$root_dir?>/ceiling/output_statis.php" class="mobile-sub-link"><i class="bi bi-graph-up-arrow"></i> 매출통계</a>
						<a href="<?=$root_dir?>/ceiling/front_log.php" class="mobile-sub-link"><i class="bi bi-infinity"></i> Front Log</a>
					</div>
				</div>
			</div>

			<!-- 구매/발주/자재 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePurchase">
						<i class="bi bi-cart-check"></i> 구매/발주/자재
					</button>
				</h2>
				<div id="collapsePurchase" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/integratedordering/list.php" class="mobile-sub-link"><i class="bi bi-clipboard-check"></i> 통합 발주 관리</a>
						<a href="<?=$root_dir?>/orders/index.php" class="mobile-sub-link"><i class="bi bi-clipboard-check"></i> 발주 리스트</a>
						<a href="<?=$root_dir?>/corp/index.php" class="mobile-sub-link"><i class="bi bi-building"></i> 거래처 관리</a>
						<a href="<?=$root_dir?>/send_email_list/index.php" class="mobile-sub-link"><i class="bi bi-clipboard-check"></i> 이메일전송 리스트 </a>						
						<a href="<?=$root_dir?>/askitem/list.php" class="mobile-sub-link"><i class="bi bi-basket3"></i> 품의서</a>
						<a href="<?=$root_dir?>/request/list.php" class="mobile-sub-link"><i class="bi bi-cart-check"></i> 원자재 구매신청</a>
						<a href="<?=$root_dir?>/steel/list.php" class="mobile-sub-link"><i class="bi bi-box-arrow-up-right"></i> 원자재 출고</a>
						<a href="<?=$root_dir?>/steel/rawmaterial.php" class="mobile-sub-link"><i class="bi bi-box-seam"></i> 원자재 재고현황</a>
						<a href="<?=$root_dir?>/request_etc/list.php" class="mobile-sub-link"><i class="bi bi-cart-check"></i> 부자재 구매신청</a>
						<a href="<?=$root_dir?>/ceiling/list_part_table.php" class="mobile-sub-link"><i class="bi bi-archive"></i> 부자재 재고현황</a>
						<a href="<?=$root_dir?>/outorder/list.php" class="mobile-sub-link"><i class="bi bi-diagram-3"></i> 외주(덴크리,서한,다온텍)</a>
						<a href="<?=$root_dir?>/make/list.php" class="mobile-sub-link"><i class="bi bi-bag-check"></i> 도장발주</a>
						<a href="<?=$root_dir?>/delivery/list.php" class="mobile-sub-link"><i class="bi bi-truck"></i> 화물/택배 배송</a>
						<a href="<?=$root_dir?>/afterorder/index.php" class="mobile-sub-link"><i class="bi bi-cup-straw"></i> 중식석식 주문</a>
						<a href="<?=$root_dir?>/oem/list.php" class="mobile-sub-link"><i class="bi bi-exclamation-triangle-fill"></i> 구 서한(NP)-이전메뉴</a>
					</div>
				</div>
			</div>

			<!-- 품질(EQ/ISO) -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQuality">
						<i class="bi bi-award"></i> 품질(EQ/ISO)
					</button>
				</h2>
				<div id="collapseQuality" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/QC/goal.php" class="mobile-sub-link"><i class="bi bi-1-circle-fill"></i> 품질방침/품질목표</a>
						<a href="<?=$root_dir?>/iso/list.php" class="mobile-sub-link"><i class="bi bi-globe2"></i> ISO 9001/14001 인증</a>
						<a href="<?=$root_dir?>/errors/qc_method.php" class="mobile-sub-link"><i class="bi bi-sliders2-vertical"></i> 품질불량 관리기법/교육</a>
						<a href="<?=$root_dir?>/idea/index.php" class="mobile-sub-link"><i class="bi bi-person-plus-fill"></i> 직원 제안제도 운영</a>
						<a href="<?=$root_dir?>/errors/index.php" class="mobile-sub-link"><i class="bi bi-file-earmark-text-fill"></i> 부적합 보고</a>
						<a href="<?=$root_dir?>/errors/statistics.php" class="mobile-sub-link"><i class="bi bi-bar-chart-line-fill"></i> 부적합(품질)통계</a>
						<a href="<?=$root_dir?>/errormeeting/index.php" class="mobile-sub-link"><i class="bi bi-person-gear"></i> 부적합개선(품질분임조)</a>
						<a href="<?=$root_dir?>/p_workstandard/list.php" class="mobile-sub-link"><i class="bi bi-diagram-3-fill"></i> 작업표준서</a>
						<a href="<?=$root_dir?>/p_qccontrol/list.php" class="mobile-sub-link"><i class="bi bi-kanban-fill"></i> QC 공정표</a>
						<a href="<?=$root_dir?>/p_inspection/list.php" class="mobile-sub-link"><i class="bi bi-upc-scan"></i> 출하 검사서</a>
						<a href="<?=$root_dir?>/qc/menu.php" class="mobile-sub-link"><i class="bi bi-clipboard2-check-fill"></i> 장비 점검</a>
						<a href="<?=$root_dir?>/qcoffice/menu.php" class="mobile-sub-link"><i class="bi bi-building-gear"></i> 사무실 정비</a>
						<a href="<?=$root_dir?>/p_evaluation/list.php" class="mobile-sub-link"><i class="bi bi-people-fill"></i> 협력업체 평가표</a>
					</div>
				</div>
			</div>

			<!-- 안전보건/위험성 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSafety">
						<i class="bi bi-shield-check"></i> 안전보건/위험성
					</button>
				</h2>
				<div id="collapseSafety" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/s_board/list.php" class="mobile-sub-link"><i class="bi bi-calendar-plus"></i> 안전보건 통합관리</a>
						<a href="<?=$root_dir?>/safety/index.php" class="mobile-sub-link"><i class="bi bi-award-fill"></i> 위험성 평가</a>
						<a href="<?=$root_dir?>/RiskAssessment/list.php" class="mobile-sub-link"><i class="bi bi-list-task"></i> 위험성 평가 자료실</a>
						<a href="<?=$root_dir?>/safetycard/menu.php" class="mobile-sub-link"><i class="bi bi-exclamation-triangle-fill"></i> 안전 카드뉴스</a>
						<a href="<?=$root_dir?>/safety/law.php" class="mobile-sub-link"><i class="bi bi-journal-bookmark-fill"></i> 고용노동부고시(지침)</a>
						<a href="<?=$root_dir?>/safety/sif/list.php" class="mobile-sub-link"><i class="bi bi-qr-code-scan"></i> 핵심위험요인SIF 평가표</a>
					</div>
				</div>
			</div> 

			<!-- 연구소/개발 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRnD">
						<i class="bi bi-lightbulb"></i> 연구소/개발
					</button>
				</h2>
				<div id="collapseRnD" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/ask_rndplan/list.php" class="mobile-sub-link"><i class="bi bi-journal-medical"></i> 연구개발계획서</a>
						<a href="<?=$root_dir?>/ask_rndnote/list.php" class="mobile-sub-link"><i class="bi bi-journal-medical"></i> 연구노트</a>
						<a href="<?=$root_dir?>/ask_rndreport/list.php" class="mobile-sub-link"><i class="bi bi-journal-medical"></i> 연구개발보고서</a>
						<a href="<?=$root_dir?>/RnDfund/list.php" class="mobile-sub-link"><i class="bi bi-credit-card"></i> 연구전담부서 운영비</a>
						<a href="<?=$root_dir?>/RnD/list.php" class="mobile-sub-link"><i class="bi bi-easel"></i> 연구소 게시판</a>
						<a href="<?=$root_dir?>/it/index.php" class="mobile-sub-link"><i class="bi bi-megaphone-fill"></i> IT 개발 프로세트 소개</a>
						<a href="<?=$root_dir?>/RnDnotice/list.php" class="mobile-sub-link"><i class="bi bi-megaphone-fill"></i> 개발 공지&자료</a>
						<a href="<?=$root_dir?>/RnDlist/list.php" class="mobile-sub-link"><i class="bi bi-kanban"></i> 개발 진행현황</a>
						<a href="<?=$root_dir?>/AIprompt/list.php" class="mobile-sub-link"><i class="bi bi-robot"></i> AI prompt</a>
						<a href="<?=$root_dir?>/channel/list.php" class="mobile-sub-link"><i class="bi bi-robot"></i> 연구 유튜브 채널분석</a>
						<a href="<?=$root_dir?>/ad/index.php" class="mobile-sub-link"><i class="bi bi-robot"></i> 미래기업 IT 사업 메뉴얼</a>
						<a href="https://8440.co.kr/school" target="_blank" class="mobile-sub-link"><i class="bi bi-laptop"></i> 코딩강의</a>
						<a href="https://8440.co.kr/quiz" target="_blank" class="mobile-sub-link"><i class="bi bi-question-circle"></i> 코딩퀴즈</a>
					</div>
				</div>
			</div>

			<!-- 근태관리 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAttendance">
						<i class="bi bi-clock-history"></i> 근태관리
					</button>
				</h2>
				<div id="collapseAttendance" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/annualleave/index.php" class="mobile-sub-link"><i class="bi bi-person-bounding-box"></i> 연차</a>
						<a href="<?=$root_dir?>/request_overtime/index.php" class="mobile-sub-link"><i class="bi bi-clock-history"></i> 연장근무(잔업/특근) 사전승인 신청</a>
						<a href="<?=$root_dir?>/absent_office/index.php" class="mobile-sub-link"><i class="bi bi-people-fill"></i> 사무실</a>
						<a href="<?=$root_dir?>/absent/index.php" class="mobile-sub-link"><i class="bi bi-building-gear"></i> 공장</a>
						<a href="<?=$root_dir?>/daylaborer/index.php" class="mobile-sub-link"><i class="bi bi-person-workspace"></i> 일용직</a>
						<a href="<?=$root_dir?>/holiday/list.php?header=header" class="mobile-sub-link"><i class="bi bi-calendar-event-fill"></i> 달력 휴일설정</a>
					</div>
				</div>
			</div>

			<!-- 게시 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBoard">
						<i class="bi bi-megaphone"></i> 게시
					</button>
				</h2>
				<div id="collapseBoard" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/notice/list.php" class="mobile-sub-link"><i class="bi bi-megaphone"></i> 공지사항</a>
						<a href="<?=$root_dir?>/qna/list.php" class="mobile-sub-link"><i class="bi bi-folder-symlink"></i> 자료실</a>
						<a href="<?=$root_dir?>/popupwindow/list.php" class="mobile-sub-link"><i class="bi bi-window-stack"></i> 팝업창</a>
						<a href="<?=$root_dir?>/HRboard/list.php" class="mobile-sub-link"><i class="bi bi-people-fill"></i> 인사/교육/총무</a>
						<a href="<?=$root_dir?>/vote/list.php" class="mobile-sub-link"><i class="bi bi-check2-square"></i> 투표</a>
					</div>
				</div>
			</div>

			<!-- 공유 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShare">
						<i class="bi bi-share"></i> 공유
					</button>
				</h2>
				<div id="collapseShare" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/youtube.php" class="mobile-sub-link"><i class="bi bi-youtube"></i> 미래기업</a>
						<a href="<?=$root_dir?>/fund/list.php" class="mobile-sub-link"><i class="bi bi-piggy-bank-fill"></i> 공동자금</a>
						<a href="<?=$root_dir?>/roadview.php" class="mobile-sub-link"><i class="bi bi-geo-alt-fill"></i> 직원연락처</a>
						<a href="<?=$root_dir?>/shop/index.php" class="mobile-sub-link"><i class="bi bi-puzzle-fill"></i> 금속 작품쇼핑몰</a>
						<a href="<?=$root_dir?>/jamb/jamb.php" class="mobile-sub-link"><i class="bi bi-boxes"></i> 잠설계모델링</a>
						<a href="<?=$root_dir?>/roulette/index.php" class="mobile-sub-link"><i class="bi bi-record-circle"></i> 룰렛(roulette)</a>
						<a href="<?=$root_dir?>/roulette/index_dart.php" class="mobile-sub-link"><i class="bi bi-bullseye"></i> 다트 선정 룰렛</a>
						<a href="<?=$root_dir?>/tetris/index.php" class="mobile-sub-link"><i class="bi bi-controller"></i> 테트리스 게임</a>
						<a href="<?=$root_dir?>/race/index.php" class="mobile-sub-link"><i class="bi bi-controller"></i> 동물 경주 게임</a>
						<a href="<?=$root_dir?>/ranking/index.php" class="mobile-sub-link"><i class="bi bi-gift"></i> 선물 순위 선정</a>
					</div>
				</div>
			</div>

			<?php if($_SESSION["level"] ==20 || $_SESSION["level"] < 6) : ?>
			<!-- 포미스톤 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePhomi">
						<i class="bi bi-gem"></i> 포미스톤
					</button>
				</h2>
				<div id="collapsePhomi" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/phomi/list_estimate.php" class="mobile-sub-link"><i class="bi bi-receipt"></i> 견적서</a>
						<a href="<?=$root_dir?>/phomi/list.php" class="mobile-sub-link"><i class="bi bi-card-checklist"></i> 수주현황</a>
						<a href="<?=$root_dir?>/phomi/list_outorder.php" class="mobile-sub-link"><i class="bi bi-box-arrow-right"></i> 출고요청서</a>
						<?php if($_SESSION["level"] !== 20) : ?>
						<a href="<?=$root_dir?>/phomi/list_deposit.php" class="mobile-sub-link"><i class="bi bi-cash-coin"></i> 본사입금(예치금)</a>
						<?php endif; ?>
						<a href="<?=$root_dir?>/phomi/unit_price.php" class="mobile-sub-link"><i class="bi bi-currency-dollar"></i> 단가표</a>
						<a href="https://phomistonekorea.co.kr/index.php" target="_blank" class="mobile-sub-link"><i class="bi bi-globe"></i> 미래기업 포미스톤 웹</a>
						<a href="https://phomi.co.kr/default/index.php" target="_blank" class="mobile-sub-link"><i class="bi bi-globe"></i> 본사 포미스톤 웹사이트</a>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<!-- 전자결재 -->
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEworks">
						<i class="bi bi-file-earmark-text"></i> 전자결재
					</button>
				</h2>
				<div id="collapseEworks" class="accordion-collapse collapse" data-bs-parent="#mobileMenuAccordion">
					<div class="accordion-body p-0">
						<a href="<?=$root_dir?>/ask_rndnote_mirae/list.php" class="mobile-sub-link"><i class="bi bi-journal-medical"></i> 프로젝트 개발진행 노트</a>
						<a href="<?=$root_dir?>/ask_rndnote/list.php" class="mobile-sub-link"><i class="bi bi-journal-medical"></i> 연구노트</a>
						<a href="<?=$root_dir?>/askitem/list.php" class="mobile-sub-link"><i class="bi bi-basket3"></i> 품의서</a>
						<a href="<?=$root_dir?>/annualleave/index.php" class="mobile-sub-link"><i class="bi bi-person-bounding-box"></i> 연차</a>
						<a href="<?=$root_dir?>/absent_office/index.php" class="mobile-sub-link"><i class="bi bi-people-fill"></i> 사무실 근태</a>
						<?php if($user_name  == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지') : ?>
						<a href="<?=$root_dir?>/absent/index.php" class="mobile-sub-link"><i class="bi bi-building-gear"></i> 공장 근태</a>
						<?php endif; ?>
						<a href="<?=$root_dir?>/idea/index.php" class="mobile-sub-link"><i class="bi bi-person-plus-fill"></i> 직원 제안제도 운영</a>
						<a href="<?=$root_dir?>/errors/index.php" class="mobile-sub-link"><i class="bi bi-file-earmark-text-fill"></i> 부적합 보고</a>
					</div>
				</div>
			</div>

			<?php endif; ?>

			<!-- 사용자 메뉴 -->
			<div class="mobile-menu-item" style="margin-top: 10px; border-top: 2px solid #e9ecef; padding-top: 10px;">
				<div style="padding: 15px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600;">
					<i class="bi bi-person-circle"></i> <?=$_SESSION["name"]?>님
				</div>
			</div>

			<div class="mobile-menu-item">
				<a href="<?=$root_dir?>/login/logout.php" class="mobile-menu-link">
					<i class="bi bi-box-arrow-right"></i>
					<span>로그아웃</span>
				</a>
			</div>

			<div class="mobile-menu-item">
				<a href="<?=$root_dir?>/member/updateForm.php?id=<?=$_SESSION["userid"]?>" class="mobile-menu-link">
					<i class="bi bi-person-gear"></i>
					<span>정보수정</span>
				</a>
			</div>

			<?php if ($_SESSION["name"] == '김보곤' || $_SESSION["name"] == '소현철') { ?>
			<div class="mobile-menu-item">
				<a href="<?=$root_dir?>/member/list.php" class="mobile-menu-link">
					<i class="bi bi-person-lines-fill"></i>
					<span>회원관리</span>
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<!-- 모바일 메뉴 스타일 -->
<style>
.mobile-menu-toggle {
	border-radius: 8px;
	padding: 8px 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* 헤더 행 기본 스타일 - 모든 환경에서 강제 적용 */
.header-row {
	display: flex !important;
	align-items: center !important;
	flex-wrap: nowrap !important;
	min-height: 60px;
	width: 100% !important;
	box-sizing: border-box !important;
}

.header-logo-col,
.header-menu-col {
	display: flex !important;
	align-items: center !important;
	flex-shrink: 0;
	box-sizing: border-box !important;
}

.header-logo-link {
	display: flex !important;
	align-items: center !important;
	height: 100% !important;
	width: 100% !important;
	box-sizing: border-box !important;
}

.header-logo-img {
	object-fit: contain !important;
	max-height: 50px;
	width: auto !important;
	display: block !important;
}

/* 모바일 환경 최적화 - 강제 정렬 */
@media (max-width: 767.98px) {
	/* 헤더 행 강제 설정 */
	.header-row,
	.row.d-flex.align-items-center.header-row,
	div.row.d-flex.align-items-center.header-row {
		display: flex !important;
		align-items: center !important;
		flex-wrap: nowrap !important;
		min-height: 60px !important;
		height: auto !important;
		width: 100% !important;
		margin: 0 !important;
		padding: 8px 0 !important;
		box-sizing: border-box !important;
	}
	
	/* 로고 컬럼 강제 설정 */
	.header-logo-col,
	.col-4.col-sm-2.d-flex.align-items-center.header-logo-col,
	div.col-4.col-sm-2.d-flex.align-items-center.header-logo-col {
		display: flex !important;
		align-items: center !important;
		height: 100% !important;
		flex: 0 0 auto !important;
		max-width: 40% !important;
		width: auto !important;
		padding: 0 8px !important;
		box-sizing: border-box !important;
	}
	
	/* 메뉴 컬럼 강제 설정 */
	.header-menu-col,
	.col-8.col-sm-10.d-flex.justify-content-end.align-items-center.header-menu-col,
	div.col-8.col-sm-10.d-flex.justify-content-end.align-items-center.header-menu-col {
		display: flex !important;
		align-items: center !important;
		justify-content: flex-end !important;
		height: 100% !important;
		flex: 1 1 auto !important;
		padding: 0 8px !important;
		box-sizing: border-box !important;
	}
	
	/* 로고 링크 강제 설정 */
	.header-logo-link,
	.header-logo-col a,
	.header-logo-col a.d-flex.align-items-center {
		display: flex !important;
		align-items: center !important;
		height: 100% !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}
	
	/* 로고 이미지 강제 설정 */
	.header-logo-img,
	.header-logo-col img,
	.header-logo-link img {
		object-fit: contain !important;
		max-height: 50px !important;
		width: auto !important;
		max-width: 100% !important;
		height: auto !important;
		display: block !important;
		margin: 0 !important;
	}
	
	/* 햄버거 버튼 강제 설정 */
	.mobile-menu-toggle,
	button.btn.btn-primary.d-md-none.mobile-menu-toggle {
		flex-shrink: 0 !important;
		min-width: 44px !important;
		width: 44px !important;
		height: 44px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		padding: 0 !important;
		margin: 0 !important;
		box-sizing: border-box !important;
	}
}

/* PC 환경 */
@media (min-width: 768px) {
	.header-row {
		display: flex !important;
		align-items: center !important;
		flex-wrap: nowrap !important;
	}
	
	.header-logo-col,
	.header-menu-col {
		display: flex !important;
		align-items: center !important;
		height: 100% !important;
	}
	
	.header-logo-img {
		max-height: 60px;
	}
}

.offcanvas {
	box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
}

.accordion-button {
	padding: 15px 20px;
	font-weight: 600;
	color: #2d3748;
	background-color: #f8f9fa;
}

.accordion-button:not(.collapsed) {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	box-shadow: none;
}

.accordion-button:focus {
	box-shadow: none;
	border-color: rgba(102, 126, 234, 0.5);
}

.accordion-button i {
	margin-right: 10px;
	font-size: 1.1rem;
}

.mobile-sub-link {
	display: block;
	padding: 12px 20px 12px 50px;
	color: #4a5568;
	text-decoration: none;
	transition: all 0.2s ease;
	border-left: 3px solid transparent;
}

.mobile-sub-link:hover {
	background-color: #f0f4f8;
	color: #667eea;
	border-left-color: #667eea;
	padding-left: 55px;
}

.mobile-sub-link i {
	margin-right: 10px;
	color: #667eea;
	width: 20px;
	text-align: center;
}

.mobile-menu-item {
	border-bottom: 1px solid #e9ecef;
}

.mobile-menu-link {
	display: flex;
	align-items: center;
	padding: 15px 20px;
	color: #2d3748;
	text-decoration: none;
	transition: all 0.2s ease;
	font-weight: 500;
}

.mobile-menu-link:hover {
	background-color: #f0f4f8;
	color: #667eea;
}

.mobile-menu-link i {
	margin-right: 12px;
	font-size: 1.2rem;
	width: 25px;
	text-align: center;
	color: #667eea;
}

.accordion-body {
	background-color: #ffffff;
}

.accordion-item {
	border: none;
	border-bottom: 1px solid #e9ecef;
}

.accordion-item:last-child {
	border-bottom: none;
}

/* 스크롤바 스타일링 */
.offcanvas-body::-webkit-scrollbar {
	width: 6px;
}

.offcanvas-body::-webkit-scrollbar-track {
	background: #f1f1f1;
}

.offcanvas-body::-webkit-scrollbar-thumb {
	background: #667eea;
	border-radius: 3px;
}

.offcanvas-body::-webkit-scrollbar-thumb:hover {
	background: #764ba2;
}
</style>

<script>
// PC/모바일 버전 토글 함수 (공통 함수)
function togglePCVersion(isPC) {
    var path = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/')) + '/';
    if (path === '/') path = '/';
    
    if (isPC) {
        // PC 버전으로 전환 - 쿠키 설정 (1년 유지)
        var expiryDate = new Date();
        expiryDate.setFullYear(expiryDate.getFullYear() + 1);
        document.cookie = 'force_pc_view=1; expires=' + expiryDate.toUTCString() + '; path=' + path;
    } else {
        // 모바일 버전으로 전환 - 쿠키 삭제
        document.cookie = 'force_pc_view=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=' + path;
        // 혹시 루트에 설정된 쿠키가 있을 수 있으므로 루트 경로로도 삭제 시도
        document.cookie = 'force_pc_view=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    }
    
    // 페이지 리로드
    window.location.reload();
}
</script>

</form>
</form>