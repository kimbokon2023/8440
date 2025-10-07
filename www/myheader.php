<?php require_once(includePath('session.php')); ?>

<form id="eworks_board_form" name="eworks_board_form" method="post" enctype="multipart/form-data" >	    
	<input type="hidden" id="eworksPage" name="eworksPage" value="<?= isset($eworksPage) ? $eworksPage : '' ?>" > 
	<input type="hidden" id="e_viewexcept_id" name="e_viewexcept_id" value="<?= isset($e_viewexcept_id) ? $e_viewexcept_id : '' ?>" >   <!-- 전자결재 보기 제한 -->    
	<input type="hidden" id="e_num" name="e_num" value="<?= isset($e_num) ? $e_num : '' ?>" > 
	<input type="hidden" id="ripple_num" name="ripple_num" value="<?= isset($ripple_num) ? $ripple_num : '' ?>" > 
	<input type="hidden" id="SelectWork" name="SelectWork" value="<?= isset($SelectWork) ? $SelectWork : '' ?>" > 
	<input type="hidden" id="eworksel" name="eworksel" value="<?= isset($eworksel) ? $eworksel : '' ?>" >    <!-- 전자결재 진행상태  draft send -->    
	<input type="hidden" id="choice" name="choice" value="<?= isset($choice) ? $choice : '' ?>" >    <!-- 전자결재 진행상태  draft send -->        
	<input type="hidden" id="approval_right" name="approval_right" value="<?= isset($approval_right) ? $approval_right : '' ?>" >   
	<input type="hidden" id="status" name="status" value="<?= isset($status) ? $status : '' ?>" >   
	<input type="hidden" id="done" name="done" value="<?= isset($done) ? $done : '' ?>" >    <!-- 전자결재 진행상태  done -->        
	<input type="hidden" id="author_id" name="author_id" value="<?= isset($author_id) ? $author_id : '' ?>" > 
	<input type="hidden" id="ework_approval" name="ework_approval" value="<?= isset($ework_approval) ? $ework_approval : 0 ?>" > 

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
	<input type="hidden" id="e_confirm" name="e_confirm">
	<input type="hidden" id="e_confirm_arr" name="e_confirm_arr[]">
	<input type="hidden" id="e_confirm_id" name="e_confirm_id">
	<input type="hidden" id="e_confirm_id_arr" name="e_confirm_id_arr[]">	  
	
 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-xxl">     
	<?php } ?>	
  
<div class="row d-flex">        
    <div class="col-sm-2 justify-content-center">        	
		<a href="<?$root_dir?>/index.php">
			<?php //<img class="img-fluid" src="<?$root_dir /img/companylogo.jpg"> ?>            
			<img src="<?$root_dir?>/img/mirae_logo.png" style="width:70%;"> &nbsp;	
		</a>		
	</div>
<div class="col-sm-10 justify-content-center">     
	<nav class="navbar navbar-expand navbar-custom "> 	 
	<div class="navbar-nav ">   
            <div class="nav-item" id="home-menu">
			<!-- 드롭다운 메뉴-->
			<a class="nav-link" href="<?$root_dir?>/index.php?home=1" title="mirae Homepage"  ><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-house-check-fill" viewBox="0 0 16 16">
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
				   <a class="dropdown-item " href="../request/list.php">원자재 구매/입출고</a>				   
				   <a class="dropdown-item " href="../steel/list.php">원자재 출고</a>
				   <a class="dropdown-item " href="../steel/rawmaterial.php">원자재 재고현황</a>
				   <a class="dropdown-item " href="../request_etc/list.php">부자재 구매/입출고</a>
				   <a class="dropdown-item " href="../ceiling/list_part_table.php">부자재 재고현황</a>                    
				   <a class="dropdown-item " href="../delivery/list.php"> <i class="bi bi-truck"></i> 화물/택배 배송 </a>
				</div>
				<div class="col">
				   <b> <a class="text-primary" href="../iso/list.php">품질ISO,EQ</a> </b>				   
				   <a class="dropdown-item " href="../QC/goal.php">품질방침/품질목표</a>
				   <a class="dropdown-item " href="../iso/list.php">ISO 9001/14001</a>
				   <a class="dropdown-item " href="../error/qc_method.php">품질불량 관리기법/교육 </a>
				   <a class="dropdown-item " href="../idea/index.php">직원 제안제도 운영</a>
				   <a class="dropdown-item " href="../error/index.php">부적합 보고</a>
				   <a class="dropdown-item " href="../error/statistics.php">부적합(품질)통계</a>
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
				   <a class="dropdown-item " href="https://8440.co.kr/school" target="_blank">  코딩강의 </a>	
				   <a class="dropdown-item " href="https://8440.co.kr/quiz" target="_blank">  코딩퀴즈 </a>	
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
                    JAMB(쟘)
                </a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/work/list.php">
						<i class="bi bi-card-checklist"></i> 수주 현황
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/month_schedule.php">
						<i class="bi bi-calendar-week"></i> 월간 생산일정
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/work_statistics.php">
						<i class="bi bi-bar-chart-line-fill"></i> 제조 통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work_voc/list.php">
						<i class="bi bi-ear"></i> 시공소장 VOC
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/picgal.php">
						<i class="bi bi-images"></i> 시공 사진
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/list_hpi.php">
						<i class="bi bi-megaphone"></i> 업체별 HPI정보
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/statistics.php">
						<i class="bi bi-person-workspace"></i> 시공비 통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/workfee.php">
						<i class="bi bi-calculator"></i> 시공비 결산
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/graph/monthly_jamb.php?header=header">
						<i class="bi bi-bar-chart-line-fill"></i> 수주통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/work/output_statis.php">
						<i class="bi bi-graph-up-arrow"></i> 매출통계
					</a>
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
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/work_statistics.php">
						<i class="bi bi-bar-chart-line-fill"></i> 제조 통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/sillcover/list.php">
						<i class="bi bi-bookshelf"></i> [재료분리대 출고]
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/graph/monthly_ceiling.php?header=header">
						<i class="bi bi-bar-chart-line-fill"></i> 수주통계
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/output_statis.php">
						<i class="bi bi-graph-up-arrow"></i> 매출통계
					</a>
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
					<a class="dropdown-item" href="<?=$root_dir?>/order/index.php">
						<i class="bi bi-clipboard-check"></i> 발주 리스트
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/corp/index.php">
						<i class="bi bi-building"></i> 거래처 관리
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/askitem/list.php">
						<i class="bi bi-basket3"></i> 품의서
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/request/list.php">
						<i class="bi bi-cart-check"></i> 원자재 구매/입출고
					</a>    										
					<a class="dropdown-item" href="<?=$root_dir?>/steel/list.php">
						<i class="bi bi-box-arrow-up-right"></i> 원자재 출고
					</a>                    					
					<a class="dropdown-item" href="<?=$root_dir?>/steel/rawmaterial.php">
						<i class="bi bi-box-seam"></i> 원자재 재고현황
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/request_etc/list.php">
						<i class="bi bi-cart-check"></i> 부자재 구매/입출고
					</a>                                                
					<a class="dropdown-item" href="<?=$root_dir?>/ceiling/list_part_table.php">
						<i class="bi bi-archive"></i> 부자재 재고현황
					</a>	
					<a class="dropdown-item" href="<?=$root_dir?>/outorder/list.php">
						<i class="bi bi-diagram-3"></i> 외주(덴크리,서한,다온텍)
					</a>									
					<a class="dropdown-item" href="<?=$root_dir?>/make/list.php">
						<i class="bi bi-bag-check"></i> 도장발주
					</a>									
					<a class="dropdown-item" href="<?=$root_dir?>/delivery/list.php">
						<i class="bi bi-truck"></i> 화물/택배 배송
					</a>	
					<a class="dropdown-item" href="<?=$root_dir?>/afterorder/index.php">
						<i class="bi bi-cup-straw"></i> 중식석식 주문
					</a>									
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
					<a class="dropdown-item" href="<?=$root_dir?>/error/qc_method.php">
						<i class="bi bi-sliders2-vertical"></i> 품질불량 관리기법/교육
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/idea/index.php">
						<i class="bi bi-person-plus-fill"></i> 직원 제안제도 운영
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/error/index.php">
						<i class="bi bi-file-earmark-text-fill"></i> 부적합 보고
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/error/statistics.php">
						<i class="bi bi-bar-chart-line-fill"></i> 부적합(품질)통계
					</a>					
					<a class="dropdown-item" href="<?=$root_dir?>/errormeeting/index.php">
						<i class="bi bi-person-gear"></i> 부적합개선(품질분임조)
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
					<a class="dropdown-item" href="<?=$root_dir?>/qc/menu.php">
						<i class="bi bi-clipboard2-check-fill"></i> 장비 점검
					</a>                    
					<a class="dropdown-item" href="<?=$root_dir?>/qcoffice/menu.php">
						<i class="bi bi-building-gear"></i> 사무실 정비
					</a>                    
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
                    연구소
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
					<a class="dropdown-item" href="http://workpt.co.kr" target="_blank">
						<i class="bi bi-laptop"></i> Work Portal
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/RnD/list.php">
						<i class="bi bi-easel"></i> 연구소 게시판
					</a>
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
					<hr style="margin:7px!important;">
					<a class="dropdown-item" href="<?=$root_dir?>/ad/index.php">
						<i class="bi bi-robot"></i> 미래기업 IT 사업 메뉴얼
					</a>
				</div>


            </div>					
            <div class="nav-item dropdown flex-fill">			 
                <!-- 드롭다운 메뉴-->
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    게시&코딩
                </a>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="<?=$root_dir?>/notice/list.php">
					<i class="bi bi-megaphone"></i> 공지사항
				</a>                    
				<a class="dropdown-item" href="<?=$root_dir?>/qna/list.php">
					<i class="bi bi-folder-symlink"></i> 자료실
				</a>                    			
				<a class="dropdown-item" href="<?=$root_dir?>/popupwindow/list.php">
					<i class="bi bi-window-stack"></i> 팝업창
				</a>                    			
				<a class="dropdown-item" href="<?=$root_dir?>/HRboard/list.php">
					<i class="bi bi-people-fill"></i> 인사/교육/총무
				</a>                    			
				<a class="dropdown-item" href="<?=$root_dir?>/vote/list.php">
					<i class="bi bi-check2-square"></i> 투표
				</a>     
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
                    공유
                </a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=$root_dir?>/holiday/list.php?header=header">
						<i class="bi bi-calendar-event-fill"></i> 일정표 휴일설정
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/youtube.php">
						<i class="bi bi-youtube"></i> 미래기업
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/annualleave/index.php">
						<i class="bi bi-person-bounding-box"></i> 연차 휴가
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/absent_office/index.php">
						<i class="bi bi-people-fill"></i> 사무실 근태관리
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/absent/index.php">
						<i class="bi bi-building-gear"></i> 공장 근태관리
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/daylaborer/index.php">
						<i class="bi bi-person-workspace"></i> 일용직 근태관리
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/fund/list.php">
						<i class="bi bi-piggy-bank-fill"></i> 공동자금
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/roadview.php">
						<i class="bi bi-geo-alt-fill"></i> 직원연락처
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/shop/index.php">
						<i class="bi bi-puzzle-fill"></i> 금속 작품쇼핑몰
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/jamb/jamb.php">
						<i class="bi bi-boxes"></i> 잠설계모델링
					</a>
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
					<!-- <a class="dropdown-item" href="<?=$root_dir?>/phomi/intro_code.php">
						<i class="bi bi-table"></i> 코드별 질감/제품명/사이즈
					</a>					 -->
					<a class="dropdown-item" href="https://phomi.co.kr/default/index.php" target="_blank">
						<i class="bi bi-globe"></i> 포미스톤 웹사이트
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
						<?php if ($_SESSION["name"] == '김보곤' || $_SESSION["name"] == '소현철') { ?>
							<a class="dropdown-item" href="<?=$root_dir?>/member/list.php">
								<i class="bi bi-person-lines-fill"></i> 회원관리
							</a> 
						<?php } ?>   			
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
							echo '<a class="dropdown-item" href="' . $WebSite . 'logdata_menu.php">
									<i class="bi bi-menu-button-wide-fill"></i> 메뉴접속기록
								  </a>';
						}
						if ($_SESSION["name"] == '김보곤' || $_SESSION["name"] == '소현철') {
							?>
							<a class="dropdown-item" href="<?=$WebSite?>/automan/list.php">
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
					<a class="dropdown-item " href="<?=$root_dir?>/askitem/list.php"> 
						<i class="bi bi-basket3"></i> 품의서
					</a>         
					<a class="dropdown-item" href="<?=$root_dir?>/annualleave/index.php">
						<i class="bi bi-person-bounding-box"></i> 연차 휴가
					</a>
					<a class="dropdown-item" href="<?=$root_dir?>/absent_office/index.php"> 
						<i class="bi bi-people-fill"></i> 사무실 근태관리
					</a>
					<?php if($user_name  == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지') : ?>
					<a class="dropdown-item" href="<?=$root_dir?>/absent/index.php">
						<i class="bi bi-building-gear"></i> 공장 근태관리
					</a>
					<?php endif; ?>
					<a class="dropdown-item" href="<?=$root_dir?>/idea/index.php">
						<i class="bi bi-person-plus-fill"></i> 직원 제안제도 운영
					</a>		
					<a class="dropdown-item" href="<?=$root_dir?>/error/index.php">
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
				<img src="<?=$WebSite?>img/eworks_reach.png" > 
			</span>     
		</div>	  
	</div>
</div>
</form>