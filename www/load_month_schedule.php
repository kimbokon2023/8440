<?php
// 월간상세일정 카드 내용 (달력 형태)
?>
<div class="row d-flex" style="padding:0; margin-top: 10px;">
	<!-- 월간상세일정 -->
	<div class="col-12 schedule_card" style="padding:7;">
		<div class="modern-management-card">
			<div class="modern-dashboard-header d-flex justify-content-between align-items-center">
				<span>📅 월간상세일정</span>
				<button type="button" id="schedule_toggle" class="btn btn-sm mx-1 fw-bold" style="background: #0288d1; color: white; border: none; border-radius: 6px; padding: 0.25rem 0.5rem;">
					<i class="bi bi-chevron-up"></i>
				</button>
			</div>
			<div class="modern-dashboard-body mt-1">
				<!-- 접고 펼치기 가능한 컨테이너 -->
				<div id="scheduleContentWrapper">
				<div class="row">
					<!-- Calendar Controls -->
					<div class="col-sm-4">
					  <div class="d-flex justify-content-start align-items-center">
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
				</div><!-- #scheduleContentWrapper -->
			</div>
		</div>
	</div>
</div>

<style>
/* 모바일에서 월간상세일정 가로 스크롤 */
@media (max-width: 768px) {
	/* body와 html의 width 제한 */
	html, body {
		max-width: 100vw !important;
		overflow-x: hidden !important;
	}

	/* 월간상세일정 카드 */
	.schedule_card {
		padding: 5px !important;
		overflow-x: hidden !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.modern-management-card {
		margin: 0.5rem 0 !important;
		width: 100% !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
	}

	.modern-dashboard-header {
		padding: 0.75rem 0.5rem !important;
		font-size: 0.9rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.modern-dashboard-header span {
		font-size: 0.85rem !important;
		flex-shrink: 1 !important;
		min-width: 0 !important;
	}

	.modern-dashboard-header #schedule_toggle {
		font-size: 0.75rem !important;
		padding: 0.3rem 0.5rem !important;
		flex-shrink: 0 !important;
		margin-left: 0.5rem !important;
	}

	.modern-dashboard-body {
		padding: 0.5rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	/* 컬럼 모바일 최적화 */
	.col-sm-4,
	.col-sm-5,
	.col-sm-7,
	.col-sm-12 {
		flex: 0 0 100% !important;
		max-width: 100% !important;
		padding-left: 5px !important;
		padding-right: 5px !important;
		margin-bottom: 0.75rem !important;
	}

	/* 일정 관리 섹션 모바일 최적화 */
	.col-sm-4:first-of-type .d-flex {
		flex-wrap: wrap !important;
		gap: 0.3rem !important;
		justify-content: center !important;
		align-items: center !important;
		padding: 0.5rem 0 !important;
	}

	.col-sm-4:first-of-type h6 {
		font-size: 0.8rem !important;
		margin: 0 !important;
		width: 100% !important;
		text-align: center !important;
		margin-bottom: 0.3rem !important;
	}

	.col-sm-4:first-of-type .modern-data-value {
		font-size: 0.7rem !important;
		padding: 0.2rem 0.4rem !important;
	}

	/* 기간 설정 영역 모바일 최적화 - 한 줄로 표시 */
	.col-sm-4:nth-of-type(2) {
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.col-sm-4:nth-of-type(2) .d-flex {
		flex-wrap: nowrap !important;
		gap: 0.2rem !important;
		justify-content: center !important;
		align-items: center !important;
		padding: 0.5rem 0.25rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.col-sm-4:nth-of-type(2) #todo-prev-month,
	.col-sm-4:nth-of-type(2) #todo-next-month {
		font-size: 0.75rem !important;
		padding: 0.3rem 0.4rem !important;
		flex-shrink: 0 !important;
		min-width: 32px !important;
		height: 30px !important;
	}

	.col-sm-4:nth-of-type(2) #todo-current-period {
		font-size: 0.8rem !important;
		font-weight: 600 !important;
		white-space: nowrap !important;
		margin: 0 0.3rem !important;
		flex-shrink: 0 !important;
	}

	.col-sm-4:nth-of-type(2) #todo-current-month {
		font-size: 0.75rem !important;
		padding: 0.3rem 0.5rem !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
		margin-left: 0.3rem !important;
	}

	/* 검색 영역 모바일 최적화 - 한 줄로 표시 */
	.col-sm-4:last-of-type {
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.col-sm-4:last-of-type .d-flex {
		flex-wrap: nowrap !important;
		gap: 0.2rem !important;
		justify-content: flex-start !important;
		align-items: center !important;
		padding: 0.5rem 0.25rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.col-sm-4:last-of-type .inputWrap {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		max-width: calc(100% - 50px) !important;
		margin-right: 0.3rem !important;
		box-sizing: border-box !important;
	}

	.col-sm-4:last-of-type #searchTodo {
		width: 100% !important;
		min-width: 0 !important;
		max-width: 100% !important;
		font-size: 0.75rem !important;
		height: 30px !important;
		padding: 0.3rem 0.4rem !important;
		box-sizing: border-box !important;
	}

	.col-sm-4:last-of-type .btnClear {
		width: 20px !important;
		height: 20px !important;
		flex-shrink: 0 !important;
	}

	.col-sm-4:last-of-type #searchTodoBtn {
		font-size: 0.75rem !important;
		padding: 0.3rem 0.5rem !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
		min-width: 36px !important;
		height: 30px !important;
	}

	/* 필터 옵션 모바일 최적화 */
	#todo-board {
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	#todo-board .d-flex {
		flex-wrap: wrap !important;
		gap: 0.4rem !important;
		justify-content: center !important;
		align-items: center !important;
		padding: 0.5rem 0.25rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	#todo-board .radio-label {
		flex-shrink: 0 !important;
		margin: 0.1rem !important;
	}

	#todo-board .radio-label .modern-data-value {
		font-size: 0.7rem !important;
		padding: 0.2rem 0.5rem !important;
		white-space: nowrap !important;
	}

	#todo-board .radio-label input[type="radio"] {
		width: 14px !important;
		height: 14px !important;
		margin-right: 0.3rem !important;
	}

	/* 달력 컨테이너 */
	#todo-calendar-container {
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
		min-width: 100% !important;
		padding: 0.5rem 0.25rem !important;
	}

	/* 달력 테이블 */
	#todo-calendar-container table {
		min-width: 600px !important;
		width: auto !important;
		font-size: 0.75rem !important;
	}

	#todo-calendar-container table th,
	#todo-calendar-container table td {
		padding: 0.4rem 0.3rem !important;
		font-size: 0.7rem !important;
	}

	/* todosMain-list도 스크롤 가능하게 */
	#todosMain-list {
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
		margin-top: 0.75rem !important;
		padding: 0.5rem 0.25rem !important;
	}

	#todosMain-list table {
		min-width: 600px !important;
		width: auto !important;
		font-size: 0.75rem !important;
	}

	#todosMain-list table th,
	#todosMain-list table td {
		padding: 0.4rem 0.3rem !important;
		font-size: 0.7rem !important;
	}

	/* scheduleContentWrapper 전체 컨테이너 */
	#scheduleContentWrapper {
		overflow-x: hidden !important;
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	/* 모든 버튼이 카드 내부에 머물도록 */
	.modern-management-card * {
		box-sizing: border-box !important;
	}

	.modern-management-card button {
		max-width: 100% !important;
		word-wrap: break-word !important;
	}

	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin-left: -5px !important;
		margin-right: -5px !important;
	}

	.row > [class*="col-"] {
		padding-left: 5px !important;
		padding-right: 5px !important;
	}
}
</style>
