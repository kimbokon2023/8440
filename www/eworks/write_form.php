<!-- eworks에서 모달 위에 띄우는 모달 창 결재 상세내역 -->
<div class="modal fade" id="eworks_viewmodal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullsm" role="document" >
    <div class="modal-content">
      <div class="modal-header" title="e결재 창">
        <h6 class="modal-title"> <i class="bi bi-credit-card-2-front-fill"></i> 결재 </h6>
			<button type="button" class="btn btn-light-secondary"  id="closeModaldetailBtn" >
					<i class="bx bx-x d-block d-sm-none"></i>
					<span class="d-none d-sm-block"><i class="bi bi-x"></i></span>
			</button>          
        </button>
      </div>
      <div class="modal-body">
       <!-- 결재정보 -->
         <div class="row">		
		   <div class="col-sm-8">		
			</div>	
		   <div id="approvalTable" class="col-sm-4">								
			</div>
         </div>
		<div class="card">	   				
				<div class="card-body" id="eworks_viewcontents">						
				<div class="table-reponsive mb-2 ">
				<table class="table table-bordered">
				   <tbody>
				   
					 <tr class="align-items-center">
					   <td class="text-center" > 구분 </td> 
							<td>
								<select class="form-control" id="eworks_item" name="eworks_item" onchange="eworksItemChanged(this)">
									<option value="일반">일반</option>
									<option value="연차">연차</option>
									<option value="원자재구매">원자재구매</option>
									<option value="부자재구매">부자재구매</option>
									<option value="품의서">품의서</option>
									<option value="연구개발계획서">연구개발계획서</option>
									<option value="연구노트">연구노트</option>
									<option value="연구개발보고서">연구개발보고서</option>
									<option value="개발프로젝트 연구노트">개발프로젝트 연구노트</option>
								</select>
							</td>
					  <td class="text-center" > 결제라인 </td> 
					  <td>								
							<div class="d-flex">			
								<input type="text" class="form-control" id="e_line" style="width:90%;" name="e_line"  >                                                        		
								<input type="hidden"  id="e_line_id" name="e_line_id">
								<button type="button" class="form-control"  id="setLineBtn" onclick = "setLine();"  style="width:8%;" ><i class="bi bi-search"></i></button>
							</div>							  
					  </td>
					  <td class="text-center" > 작성일시 </td>
					   <td >
						  <input type="text" class="form-control" id="registdate"  name="registdate"  >                                                    
						  </td>
						<td class="text-center" > 작성자 </td>
						<td> 	
							<input type="text" class="form-control" id="author"  name="author"  >                                                    
						</td>
					  </tr>
					  <tr class="align-items-center">
					  <td class="text-center" > 결제진행 </td>
					   <td colspan="3">
							 <input type="text" class="form-control" readonly id="e_prograss"  name="e_prograss"  >   
						</td>							  
						<td class="text-center" >참조 </td> 
						<td colspan="3">
							<div class="d-flex">			
								<input type="text" class="form-control" id="r_line" style="width:90%;" name="r_line"  >                                                        		
								<input type="hidden"  id="r_line_id" name="r_line_id">
								<button type="button" class="form-control" onclick="setRef();" style="width:8%;" ><i class="bi bi-search" ></i></button>	                                                  
							</div>							  
						</td>			
					  </tr>		
                      <tr class="align-items-center">				  
					  <td class="text-center" > 내용 </td>
					   <td  colspan="7">
							<div class="row d-flex justify-content-center " >
								<div id="htmlContainer">							  
									<textarea id="contents"  class="form-control" name="contents" rows="10"  ><?=$contents?></textarea>	
								</div>
							</div>
						</td>		
					  </tr>
						
				</tbody>
				</table>
				</div>					
					<div id="eworksBtn">																															 
						<?php include 'eworksBtn.php'; ?>																
					</div>	
				</div>
			</div>
		</div>			
      <div class="modal-footer">
	    <span id="numdisplay"> </span>
        <button type="button"  class="btn btn-outline-dark btn-sm" id="closesecondModalBtn" > &times; 닫기 </button>
      </div>
      </div>
    </div>
</div>

