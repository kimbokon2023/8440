<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
$level= $_SESSION["level"];
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];	
$WebSite = "http://8440.co.kr/";	
 
$menu=$_REQUEST["menu"]; 
   
$title_message = '사급업체';   
   
?>

<?php 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    
   
	include getDocumentRoot() . '/load_header.php';
   
 ?>

<?php include getDocumentRoot() . '/common/modal.php'; ?>

<title> <?=$title_message?>  </title>

</head>

<body>

<?php
  

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];

  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
?>	
    
<form id="board_form" name="board_form" method="post" enctype="multipart/form-data"   >		

	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">             
	<input type="hidden" id="num" name="num" value=<?=$num?> > 
	<input type="hidden" id="page" name="page" value=<?=$page?> > 
	<input type="hidden" id="mode" name="mode" value=<?=$mode?> > 		
	<input type="hidden" id="text1" name="text1" value=<?=$text1?> > 			
	<input type="hidden" id="text2" name="text2" value=<?=$text2?> > 			
	<input type="hidden" id="text3" name="text3" value=<?=$text3?> > 	

<title><?=$title_message?>   </title>

<div class="container">					
<div class="card" >
<div class="card-body" >	
	<h4 class="text-center mt-2 mb-4" >  <?=$title_message?>   </h4>			
			<div class="d-flex mt-1 mb-3">
					<button id="saveBtn" type="button" class="btn btn-dark btn-sm me-4">
						<i class="bi bi-floppy"></i> 저장   
					</button>			 
					<button class="btn btn-secondary btn-sm me-1" onclick="self.close();"> <i class="bi bi-x-lg"></i> 창닫기 </button>	
			</div>
						<div class="card">			
							<div class="card-body">
								<div class="input-group mb-1">										
									<span class="input-group-text" class="text-center" style="width:130px;" > <?=$title_message?>명 </span>
									<input type="text" class="form-control" id="company"  name="company" value="<?=$company?>" >                                                    
								</div>                                            										
						</div>  
				</div>
				</div>
			</div>
		</div>
</form>
</body>
</html>

	 
<script>
/* ESC 키 누를시 팝업 닫기 */
$(document).keydown(function(e){
		//keyCode 구 브라우저, which 현재 브라우저
		var code = e.keyCode || e.which;
		
		if (code == 27) { // 27은 ESC 키번호
			self.close();
		}
});

$(document).ready(function(){	  
	 
// 저장 버튼 서버에 저장하고 Ecount 전송함
$("#saveBtn").on("click", function() {
		
	$("#SelectWork").val("insert");		
	
	let msg = '저장완료';
									
		$.ajax({
			url: "process.php",
			type: "post",		
			data: $("#board_form").serialize(),								
			success : function( data ){		

               console.log(data);			
														
					 Toastify({
							text: msg ,
							duration: 3000,
							close:true,
							gravity:"top",
							position: "center",
							backgroundColor: "#4fbe87",
						}).showToast();			
						
                 // 부모창 실행
				 $("#search", opener.document).val(  $("#company").val()  ); 
				$(opener.location).attr("href", "javascript:reloadlist();");	
					
				  setTimeout(function() {
							  // 창 닫기
							   self.close();	
						   }, 1500);				
				
                 							  
													
									},
									error : function( jqxhr , status , error ){
										console.log( jqxhr , status , error );
								           } 			      		
							   });												
				   
   });			
	
});	
	
</script>