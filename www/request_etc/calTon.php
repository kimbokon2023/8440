<?php session_start();
isset($_REQUEST["item"]) ? $item=$_REQUEST["item"] : $item="";   
isset($_REQUEST["spec"]) ? $spec=$_REQUEST["spec"] : $spec="";   

 ?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<body>
<title> 톤으로 수량산출 계산기 </title>
<style>
   @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");
</style>

	<section class ="d-flex fex-column align-items-left flex-md-row p-1">
	 <div class="p-2 pt-md-3 pb-md-3 text-left" style="width:100%;">	  
		 <form id="mainFrm" method="post" enctype="multipart/form-data" >		
            <input type="hidden" id="SelectWork" name="SelectWork" > 
            <input type="hidden" id="vacancy" name="vacancy" > 
            <input type="hidden" id="num" name="num" value=<?=$num?> > 
            <input type="hidden" id="page" name="page" value=<?=$page?> > 
            <input type="hidden" id="calculate" name="calculate" value=<?=$calculate?> > 
		    <div class="card-header"> 			                        	                         		
						 <div class="input-group p-2 mb-1">							 
						 
						  종 류 : &nbsp; <input name="item" id="item" value='<?=$item?>' >&nbsp; &nbsp; 
						  규 격 : &nbsp; <input name="spec" id="spec" value='<?=$spec?>' >    	
						 </div>			                        	                         	
						 
						 <div class="input-group p-2 mb-1">							 						 
						  주문톤수(ton) : &nbsp; <input name="expected" id="expected" value='<?=$expected?>' >&nbsp; &nbsp; 						  
						 </div>
						 <div class="input-group p-2 mb-1">							 						 
						  계산된 매수 : &nbsp; <input name="steelnum" id="steelnum" value='<?=$steelnum?>' >&nbsp; &nbsp; 						  
						 </div>
						 <div class="input-group p-2 mb-1">							 
						<button  type="button" id="calBtn"  class="btn btn-secondary" > 계산하기 </button> &nbsp;
						<button  type="button" id="returnBtn" class="btn btn-outline-danger"> 적용하기 </button> &nbsp;						

					    
						 <div class="input-group  justify-content-center p-5 mb-5" id="loading" style="display:none;" >							 
						   <img id="loading-image" src="/img/loading.gif" alt="Loading..." />						   
                         </div>
						
		   
				</div>
						
				<div id="grid">  </div>		
					
	            <div id="tui-pagination-container" class="tui-pagination"></div>
			 <!-- 배열을 전달하기 위한 Grid 값
				<input id="steelcompany" name="steelcompany[]" type=hidden > -->			  	
			  			     
			<div id="tmpdiv"> </div>
	     	
			</form>		
			
  <form id=Form1 name="Form1">
    <input type=hidden id="steelcompany" name="steelcompany[]" >
  </form>  			
              
		  </div>
		  
 <script> 

$(document).ready(function(){
					
	$("#calBtn").click(function(){    // 계산하기 수량
     var expected = $('#expected').val();
     var spec = $('#spec').val();
	 
	 var arr = spec.split('*');
	 var unit;
	 
	 console.log(arr);
	 
	 unit = (Number(arr[0])*Number(arr[1])*Number(arr[2]) * 7.93) /1000000;
	 
	 sheet = parseInt(Number(expected) * 1000 / unit);
	 
	 $("#steelnum").val(sheet);
	 
	 console.log(unit);
	 
		// 부모창에 적용해 주기 opener 활용기법
		$("input[name=steelnum]", opener.document).val($('#steelnum').val());		
		$("#comment", opener.document).val($('#expected').val() + '톤 주문해 주세요');
		window.close();	 


	 });	
	
	$("#returnBtn").click(function(){    // 부모창에 적용하기

		// 부모창에 적용해 주기 opener 활용기법
		$("input[name=steelnum]", opener.document).val($('#steelnum').val());		
		window.close();
	 });		
								
}); // end of ready document


  </script>
    </div>
  </div>	 
</section>
</body>
</html>

