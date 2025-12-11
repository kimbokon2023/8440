<?php
require_once __DIR__ . '/../bootstrap.php';
?>

<!--전자결재 리스트창 -->
<!--Extra Full Modal -->
<div class="modal fade" id="request_form" tabindex="-99">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> 전자결재 </h4>
                <button type="button" class="btn btn-light-secondary" id="closeModalxBtn">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block"><i class="bi bi-x"></i></span>
                </button>
            </div>
            <div class="modal-body mb-1">
                <div class="card mb-2">
                    <div class="card-content">
                        <div class="card-body">
						<div id="eworksNavContainer">
						
						
						</div>
						<div class="d-flex mt-3 mb-3 justify-content-center" >								
							<button class="btn btn-dark btn-sm me-2" type="button" id="E_searchAllBtn" > 전체 </button>

							<input type="text" id="search" name="search" class=" me-2" value="<?=$search?>" onkeydown="if (event.keyCode === 13) enterkey()" >
							<button class="btn btn-dark btn-sm  me-2" type="button" onclick="enterkey(); " > <ion-icon name="search-outline"></ion-icon> </button> </span> 

							<button class="btn btn-dark btn-sm  me-2" type="button" onclick="viewEworks_detail('',1);" > <i class="bi bi-pencil-square"></i> 작성 </button>
						</div>						
                             

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end mt-3">			 
                <button type="button" id="closeEworksBtn" class="btn btn-outline-dark btn-sm">
                    <ion-icon name="close-outline"></ion-icon> 닫기
                </button>
            </div>
        </div>
    </div>
</div>
