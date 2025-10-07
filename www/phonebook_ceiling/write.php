 <?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
 ?>
 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
 <?php
 
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];

$tablename = 'phonebook';
  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
    
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : 0;

// 수정일 경우
if($num>0)
{	
 $SelectWork = 'update';
 // 재질 배열 추출 
  require_once("../lib/mydb.php");
  $pdo = db_connect();
   
// 소장 VOC 가져오기
	try{
     $sql = "select * from ". $DB . "." . $tablename . " where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
		include '_row.php';

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }      	
}

else
{
	$SelectWork = 'insert';
	$phone_name = $search;
	$phonenumber = '010-';
}
 
  $scale = 10;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
	?>	


<body>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">			 

	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">             
	<input type="hidden" id="num" name="num" value=<?=$num?> > 
	<input type="hidden" id="page" name="page" value=<?=$page?> > 
	<input type="hidden" id="mode" name="mode" value=<?=$mode?> > 
	<input type="hidden" id="tablename" name="tablename" value=<?=$tablename?> > 	

<title> 연락처 등록/수정 </title>

<div class="container-fluid">					
<div class="card justify-content-center text-center mt-4 mb-3" >
	<div class="card-header">
		<span class="text-center fs-5" > 연락처  </span>								
	</div>
	<div class="card-body" >								
	    <div class="row  justify-content-center text-center" >	
		
						<div class="d-flex row">    
								<div class="input-group mb-1">										
									<span class="input-group-text" style="width:130px;" > 성명 </span>
									<input type="text" class="form-control" id="phone_name"  name="phone_name" value="<?=$phone_name?>" >                                                    
								</div>                                            										
						</div>   
						<div class="d-flex row">    
								<div class="input-group mb-1">										
									<span class="input-group-text" style="width:130px;" > 전화번호 </span>
									<input type="text" class="form-control" id="phonenumber"  name="phonenumber" value="<?=$phonenumber?>" >                                                    
								</div>                                            										
						</div>                                            										
						   
						<div class="d-flex row">    
								<div class="input-group mb-1">										
									<span class="input-group-text" style="width:130px;" > 소속 </span>
									<input type="text" class="form-control" id="belongstr"  name="belongstr" value="<?=$belongstr?>" >                                                    
								</div>                                            										
						</div>                                            										
						   
						
					</div>

				
								
								
							</div>
							<div class="card-footer justify-content-start">
								<button type="button"  id="closeBtn" class="btn btn-outline-dark btn-sm me-2">
									<ion-icon name="close-circle-outline"></ion-icon> Close
								</button>
								<button type="button" id="saveBtn"  class="btn btn-dark btn-sm">
									<ion-icon name="save-outline"></ion-icon> 저장 
								</button>
								</div>
							</div>
						</div>


</form>
</body>
</html>

	 
<script>
/* ESC 키 누를시 팝업 닫기 */
// $(document).keydown(function(e){
		// //keyCode 구 브라우저, which 현재 브라우저
		// var code = e.keyCode || e.which;
		
		// if (code == 27) { // 27은 ESC 키번호
			// self.close();
		// }
// });

$(document).ready(function(){	  
	 
	// 창닫기 버튼
	$("#closeBtn").on("click", function() {
		self.close();
	});	
	
// 저장 버튼 서버에 저장하고 Ecount 전송함
$("#saveBtn").on("click", function() {
		
	$("#SelectWork").val("<?php echo $SelectWork; ?>");		
	
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
				 $("#search", opener.document).val(  $("#phone_name").val()  ); 
				$(opener.location).attr("href", "javascript:reloadlist();");	

		setTimeout(function() {
			  // 창 닫기
			   self.close();								   
		   }, 500);				

			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
				   } 			      		
		});												

	});			
	
});	  // end of ready
	
</script>