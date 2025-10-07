
<!DOCTYPE html>
<html lang="ko">
  <head>  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
	    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>	    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script> 
	
	<script src="http://8440.co.kr/common.js"></script>
	<title> 현장 검색창 </title>
    
  </head>
 <style>  
		  html,body {
		  height: 100%;
		}
</style>
<body>

    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
		     결재가 진행중입니다. <br> 
		   <br> 
		  수정사항이 있으면 결재권자에게 말씀해 주세요.
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>

<div class="container h-30">
    <div class="row d-flex justify-content-center align-items-center h-30">	        
			<div class="card align-middle" style="width:38rem; border-radius:20px;">
				<div class="card" style="padding:6px;margin:7px;">
					<h5 class="card-title text-center" style="color:#113366;"> 
					  검색선택 : &nbsp;  쟘(jamb)  <input  type="radio" checked name=search_opt value="1"> 
					  &nbsp; 천장   <input  type="radio"  name=search_opt value="2"> 
					  &nbsp; 		
		<br>					  
		<br>					  
	</h5>

		<input type="text" id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$outworkplace?>" placeholder="현장명"> 	
		<button  type="button" class="btn btn-outline-dark btn-sm" onclick="Choice_search();" > 검색 </button> &nbsp;		
									
					
				</div>	
				<div class="card-body text-center">
		  <form id="board_form" name="board_form" method="post"  enctype="multipart/form-data">
				<input type="hidden" id="mode" name="mode" >
				<input type="hidden" id="num" name="num" value="<?=$num?>" >			  								
				<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" size="5" > 
				<input type="hidden" id=filedelete name=filedelete >
				<input type="hidden" id=filename name=filename value="<?=$filename?>"  >
				<input type="hidden" id=serverfilename name=serverfilename value="<?=$serverfilename?>"  >
			  
				<span class="form-control">
				   <div id=displaysearch>
				   </div>
				</span>
				
				
		
		
				
		   
				
<br>								
				
 
				


				
			  </form>			  
				</div>
       	   	</div>
			</div>		
				
	  </div>

	</div>		 

				
	</body>
 </html>


<script>

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
function Enter_Check(){
var tmp = $('input[name=search_opt]:checked').val();	
	
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13 && tmp== 1 )
      search_jamb();  // 잠 현장검색
	  
    if(event.keyCode == 13 && tmp== 2 )
      search_ceiling();  // 천장 현장 검색	      
}

function search_jamb()
{
	  var ua = window.navigator.userAgent;
      var postData; 	 
	  var text1= document.getElementById("outworkplace").value;
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(text1);
            } else {
                postData = text1;
            }

      $("#displaysearch").show();
      $("#displaysearch").load("./search_jamb.php?mode=search&search=" + postData);
} 

function search_ceiling()
{
	  var ua = window.navigator.userAgent;
      var postData; 	 
	  var text1= document.getElementById("outworkplace").value;
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(text1);
            } else {
                postData = text1;
            }

      $("#displaysearch").show();
      $("#displaysearch").load("./search_ceiling.php?mode=search&search=" + postData);
} 

function Choice_search() {
	var tmp = $('input[name=search_opt]:checked').val();	
		if(tmp =='1' )
		  search_jamb();  // 잠 현장검색	  
		if(tmp == '2' )
		  search_ceiling();  // 천장 현장 검색	        
  }
  
</script>