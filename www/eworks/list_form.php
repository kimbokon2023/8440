<!--전자결재 리스트창 -->
<!--Extra Full Modal -->
<div class="modal fade" id="eworks_form" tabindex="-90">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <i class="bi bi-briefcase"></i> 전자결재 </h5>
                <button type="button" class="btn btn-light-secondary" id="closeModalxBtn">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block"><i class="bi bi-x"></i></span>
                </button>
            </div>
            <div class="modal-body ">
                <div class="card">                    
                        <div class="card-body">
							<div id="eworksNavContainer">
							<!-- nav 리스트 가져오기 -->
							<?php   include $_SERVER['DOCUMENT_ROOT'] . "/eworks_nav.php"; ?>
							</div>
							<div class="d-flex mt-2 mb-1 justify-content-center" >								
								<button class="btn btn-dark btn-sm me-2" type="button" id="E_searchAllBtn" > 전체 </button>

								<input type="text" id="EworksSearch" name="EworksSearch" class="form-control me-2" style="width:150px;" value="<?=$EworksSearch?>" onkeydown="if (event.keyCode === 13) enterkey()" >
								<button class="btn btn-dark btn-sm  me-2" type="button" onclick="enterkey(); " > <i class="bi bi-search"></i>   </button> </span> 

								<button class="btn btn-dark btn-sm  me-2" type="button" onclick="viewEworks_detail('',1);" > <i class="bi bi-pencil-square"></i> 작성 </button>
							</div>	
							<div class="row" >							
								 <?php include $_SERVER['DOCUMENT_ROOT'] . "/eworks/list.php"; ?>
							</div>
                        </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end mt-3">			 
                <button type="button" id="closeEworksBtn" class="btn btn-outline-dark btn-sm">
                   &times; 닫기
                </button>
            </div>
        </div>
    </div>
</div>
