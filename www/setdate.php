<!-- 기간부터 검색까지 연결 묶음 start -->
<div class="card">
<div class="card-body">
<div class="d-flex justify-content-center align-items-center">
<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 
<div id="showframe" class="card">
	<div class="card-header ">
		<div class="d-flex justify-content-center align-items-center">  
			기간 설정
		</div>
	</div>
	<div class="card-body">
		<div class="d-flex justify-content-center align-items-center">  	
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

	   <input type="date" id="fromdate" name="fromdate"  class="form-control " style="width:100px;"  value="<?=$fromdate?>">  &nbsp; ~ &nbsp;  
	   <input type="date" id="todate" name="todate"  class="form-control me-1"  style="width:100px;"    value="<?=$todate?>" >    </span>  
	<div class="inputWrap">
	<input type="text" id="search" name="search" style="width:150px;"   value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control me-1" style="width:200px;" >
		<button class="btnClear"></button>	
	</div>	
		<button type="button" id="searchBtn" class="btn btn-dark btn-sm"  >  <i class="bi bi-search"></i> 검색 </button>						
</div>		
</div>		
</div>		
<!-- 기간부터 검색까지 연결 묶음 end -->