<!-- 기간부터 검색까지 연결 묶음 start -->
<style>
/* setdate.php 모바일 최적화 스타일 */
@media (max-width: 768px) {
    /* 카드 최적화 */
    .card {
        margin: 0.5rem auto !important;
        border-radius: 0.5rem !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .card-header {
        padding: 0.5rem 0.4rem !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 메인 컨테이너 최적화 */
    .d-flex.justify-content-center.align-items-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.75rem !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* 기간 버튼 최적화 - 모바일에서 숨기기 */
    #showdate {
        display: none !important;
    }
    
    /* 기간 설정 카드 최적화 */
    #showframe {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.5rem 0 !important;
    }
    
    #showframe .card-body {
        padding: 0.5rem 0.4rem !important;
    }
    
    /* 기간 설정 버튼 그룹 최적화 */
    #showframe .d-flex.justify-content-center.align-items-center {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
        justify-content: flex-start !important;
    }
    
    #showframe .btn {
        flex: 1 1 auto !important;
        min-width: calc(50% - 0.25rem) !important;
        max-width: calc(50% - 0.25rem) !important;
        margin: 0.125rem !important;
        font-size: 0.8rem !important;
        padding: 0.4rem 0.5rem !important;
    }
    
    /* 날짜 입력 필드 최적화 */
    input[type="date"] {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        box-sizing: border-box !important;
    }
    
    /* 날짜 입력 그룹 최적화 - 모바일 */
    .date-input-group {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* 날짜 구분자 숨기기 - 모바일 */
    .date-separator {
        display: none !important;
    }
    
    /* 날짜 입력 필드 모바일 최적화 */
    .date-input-group input[type="date"] {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* 검색 입력 필드 최적화 */
    .inputWrap {
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 0.5rem !important;
        position: relative !important;
    }
    
    #search {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
        padding-right: 2.5rem !important;
        box-sizing: border-box !important;
        margin: 0 !important;
    }
    
    .btnClear {
        position: absolute !important;
        right: 0.5rem !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 1.5rem !important;
        height: 1.5rem !important;
        background: transparent !important;
        border: none !important;
        cursor: pointer !important;
    }
    
    /* 검색 버튼 최적화 */
    #searchBtn {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        margin-top: 0.5rem !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: nowrap !important;
        min-height: 40px !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    .btn-sm {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.6rem !important;
        min-height: 36px !important;
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
        display: inline !important;
        overflow: visible !important;
    }
}

/* PC 환경 최적화 */
@media (min-width: 769px) {
    /* 버튼 간격 최적화 */
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-start .btn {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }
    
    /* 날짜 입력 그룹 - PC에서는 한 줄로 배치 */
    .date-input-group {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 0.5rem !important;
        width: auto !important;
        max-width: none !important;
        margin-bottom: 0 !important;
    }
    
    /* 날짜 구분자 표시 - PC */
    .date-separator {
        display: inline !important;
        margin: 0 0.25rem !important;
    }
    
    /* 날짜 입력 필드 PC 최적화 */
    .date-input-group input[type="date"] {
        width: 100px !important;
        max-width: 100px !important;
    }
    
    /* 메인 컨테이너 PC 레이아웃 - 한 줄로 배치 */
    .setdate-container {
        flex-direction: row !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
        gap: 0.5rem !important;
    }
    
    /* 기간 버튼 PC 레이아웃 */
    #showdate {
        width: auto !important;
        max-width: none !important;
        margin-bottom: 0 !important;
        flex-shrink: 0 !important;
    }
    
    /* 기간 설정 카드 PC 레이아웃 */
    #showframe {
        width: auto !important;
        max-width: none !important;
        margin: 0 !important;
        flex-shrink: 0 !important;
    }
    
    /* 검색 입력 필드 PC 레이아웃 */
    .inputWrap {
        width: 200px !important;
        max-width: 200px !important;
        margin-bottom: 0 !important;
        flex-shrink: 0 !important;
    }
    
    .inputWrap #search {
        width: 200px !important;
        max-width: 200px !important;
    }
    
    /* 검색 버튼 PC 레이아웃 */
    #searchBtn {
        width: auto !important;
        max-width: none !important;
        margin-top: 0 !important;
        flex-shrink: 0 !important;
    }
    
    /* 기간 설정 버튼 그룹 PC 레이아웃 */
    .date-range-buttons {
        flex-wrap: wrap !important;
        justify-content: center !important;
    }
    
    .date-range-buttons .btn {
        flex: 0 0 auto !important;
        min-width: auto !important;
        max-width: none !important;
    }
}
</style>

<div class="card setdate-card">
<div class="card-body">
<div class="d-flex justify-content-center align-items-center setdate-container">
<span id="showdate" class="btn btn-dark btn-sm" > 기간 </span>
<div id="showframe" class="card">
	<div class="card-header ">
		<div class="d-flex justify-content-center align-items-center">  
			기간 설정
		</div>
	</div>
	<div class="card-body">
		<div class="d-flex justify-content-center align-items-center date-range-buttons">  	
			<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
			<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange"   onclick='pre_year()' > 전년도 </button>  
			<button type="button" id="three_month" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='three_month_ago()' > M-3월 </button>
			<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='prepre_month()' > 전전월 </button>	
			<button type="button" id="premonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='pre_month()' > 전월 </button> 						
			<button type="button" class="btn btn-outline-danger btn-sm me-1  change_dateRange"  onclick='this_today()' > 오늘 </button>
			<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_month()' > 당월 </button>
			<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_year()' > 당해년도 </button> 
		</div>
	</div>
</div>			

<div class="date-input-group">
   <input type="date" id="fromdate" name="fromdate"  class="form-control" style="width:100px;"  value="<?=$fromdate?>">
   <span class="date-separator"> ~ </span>
   <input type="date" id="todate" name="todate"  class="form-control me-1"  style="width:100px;"    value="<?=$todate?>" >
</div>

<div class="inputWrap">
<input type="text" id="search" name="search" style="width:150px;"   value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control me-1" style="width:200px;" >
	<button class="btnClear"></button>	
</div>	
	<button type="button" id="searchBtn" class="btn btn-dark btn-sm"  >  <i class="bi bi-search"></i> 검색 </button>						
</div>		
</div>		
</div>		
<!-- 기간부터 검색까지 연결 묶음 end -->