	
  <!-- Modal --> 
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">
		<div class="d-flex justify-content-center large text-warning mb-2"> 
			<!-- <img id=popupwindow src="./img/popupmall.jpg"  style="width:60%; height:60%;"> 	-->
			<img id=popupwindow src="./img/steelname2.jpg"  style="width:100%; height:100%;"> 		                              				
		</div>
		</div>
			
        <div class="modal-footer">		
          <button type="button" class="btn btn-default" id="closemodalBtn" data-dismiss="modal">닫기</button>
        </div>
		</div>
      </div>
	</div>
	

 <!-- Modal HTML -->
    <div id="timeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">서버 이관작업 안내</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2> 금일 작업한 도면을 Nas2dual 회사 서버에 올려주세요. </h2>
					<br>
					<br>
					<h2> 오늘도 수고 많으셨습니다.</h2>
                </div>
                <div class="modal-footer">
                    <button id="timeModalcloseBtn" type="button" class="btn btn-secondary fs-3"  onclick="stopInterval()" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>
	
<!-- Modal --> 
<!-- Vertically centered modal -->    
<div class="modal fade" id="Approval Modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered">

		<!-- Modal content-->
		<div class="modal-content modal-lg">
			<div class="modal-header">          
			<h4 class="modal-title">결재 알림</h4>
			</div>
				<div class="modal-body">
				<div class="d-flex justify-content-center mb-2 fs-5"> 
				결재 내용이 있습니다. 확인바랍니다.
				</div>
				</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="closemodalApprovalBtn" data-dismiss="modal">닫기</button>
			</div>
		</div>
    </div>
</div>

<!-- todo모달 컨테이너 -->
<div class="container-fluid">
	<!-- Modal -->
	<div id="todoModal" class="modal">
		<div class="modal-content"  style="width:800px;">
			<div class="modal-header">
				<span class="modal-title">할일</span>
				<span class="todo-close">&times;</span>
			</div>
			<div class="modal-body">
				<div class="custom-card"></div>
			</div>
		</div>
	</div>
</div>
