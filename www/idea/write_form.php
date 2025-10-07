<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  
	
$menu=$_REQUEST["menu"];
$title_message = '개선을 위한 제안하기 ';   

print $level;

?>
  
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
	 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
			 sleep(1);
			 header("Location:https://8440.co.kr/login/login_form.php"); 
			 exit;
	   } 
 ?>
    
<title> <?=$title_message?>  </title> 

  <style>
    .table td,
    .table th {
      vertical-align: middle !important;
    }
  </style>
  
</head> 
<body>   
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'); ?>   
<?php	   									  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

 try{
	  $sql = "select * from {$DB}.idea where num = ? ";
	  $stmh = $pdo->prepare($sql); 
      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.		
		 
	  include 'rowDB.php';
	  
	  if($count < 1)
	  {
		  
		  $occur =  date("Y-m-d");
	  }
	  
	  $imgurl = './img/' . $serverfilename ;
	  
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
 }
 // end of if	
	
 
?>  
<form id="board_form" name="board_form" method="post"  enctype="multipart/form-data">
<input type="hidden" id="mode" name="mode" >
<input type="hidden" id="num" name="num" value="<?=$num?>" >			  								
<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" size="5" > 
<input type="hidden" id="filedelete" name="filedelete" >
<input type="hidden" id="filename" name="filename" value="<?=$filename?>"  >
<input type="hidden" id="serverfilename" name="serverfilename" value="<?=$serverfilename?>"  >

<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center">	
        <div class="col-12 text-center">
		<div class="card align-middle" style="border-radius:20px;">
			<div class="card">
				<h4 class="card-title text-center mt-2 mb-2" style="color:#113366;"> 제안 하기 </h4>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td class="text-center"> 제안 일시 </td>
							<td class="text-center w120px">
								<input type="date" id="occur" name="occur" value="<?=$occur?>" class="form-control w-auto">								
							</td>
							<td class="text-center text-primary fw-bold w100px"> 제안 유형 </td>
								<td class="text-center align-middle" colspan="5">
									<?php 
									$idea_type_arr = ['비용절감','작업시간 단축','안전활동','업무효율화','수익증대'];

									// 저장된 값이 문자열("!" 구분)인 경우 배열로 변환합니다.
									if (!empty($errortype)) {
										if (!is_array($errortype)) {
											$errortype = explode('!', $errortype); // 구분자를 "!"로 변경
										}
									} else {
										$errortype = [];
									}
									
									foreach ($idea_type_arr as $type): 
									?>
										<label class="checkbox-inline" style="margin-right: 10px;">
											<input type="checkbox" name="errortype[]" value="<?= $type ?>" <?= in_array($type, $errortype) ? 'checked' : '' ?>>
											<?= $type ?>
										</label>
									<?php endforeach; ?>
								</td>


						</tr>
						<tr>
							<td class="text-center fw-bold">제안명</td>
							<td class="text-start w500px" colspan="3" >
								<input type="text" name="place" id="place" class="form-control" value="<?=$place?>" autofocus>								
							</td>						
							<td class="text-center">최초 제안자</td>
							<td class="text-center w80px">
								<input type="text" id="firstone" name="firstone" class="form-control" value="<?=$firstone?>" placeholder="최초 제안자">
							</td>						
							<td class="text-center">참여자</td>
							<td>
								<input type="text" id="emember" name="emember" class="form-control" value="<?=$emember?>" placeholder="제안 참여자">
							</td>
						</tr>
						<tr>
							<td class="text-center text-primary fw-bold align-middle">제안 내용 <br> (기존 상태) </td>
							<td colspan="8">
								<textarea id="content" name="content" class="form-control" rows="3"><?=$content?></textarea>
							</td>
						</tr>
						<tr>
							<td class="text-center align-middle  text-danger fw-bold ">개선 효과 <br> (비용절감, <br> 수익증대, <br> 작업시간 단축 등) </td>
							<td colspan="8">
								<textarea id="method" name="method" class="form-control" rows="7"><?=$method?></textarea>
							</td>
						</tr>
						<tr>
							<td class="text-center align-middle">첨부 이미지</td>
							<td colspan="8">
								<div class="d-flex align-items-center">
									<span class="text-success w300px" ><?= $filename ?: '이미지 없음' ?></span>
									<input id="mainbefore" name="mainBefore" type="file" class="form-control ms-3">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		
	  <?php 
			if($filename!=null) {	
			  print " <span class='form-control'> ";
			  print '<br>';
			  print "<div class='imagediv' > ";
			  echo "<img class='before_work' src='". $imgurl  . "'  >";			  			  
			  print "</div> </span> <br> ";
			  }
		?>		   	
			<br>								
			<h5 class="form-signin-heading mt-1 mb-2">제안등급 부여</h5>	
			<div class="d-flex justify-content-center">				
				<select id="approve" name="approve" class="form-control text-center" 
					style="width:150px; <?= (intval($level) != 1 && intval($level) != 2) ? 'pointer-events: none; background-color: #e9ecef;' : '' ?>">
					<option value="" <?= empty($approve) ? 'selected' : '' ?>>(선택)</option>
					<option value="A등급" <?= $approve == 'A등급' ? 'selected' : '' ?>>A등급</option>
					<option value="B등급" <?= $approve == 'B등급' ? 'selected' : '' ?>>B등급</option>
					<option value="C등급" <?= $approve == 'C등급' ? 'selected' : '' ?>>C등급</option>
					<option value="등급보류" <?= $approve == '등급보류' ? 'selected' : '' ?>>등급보류</option>
				</select>
			</div>						

			<h5 class="form-signin-heading mt-1 mb-2">포상지급 여부</h5>	
			<div class="d-flex justify-content-center">				
				<select id="payment" name="payment" class="form-control text-center" 
					style="width:150px; <?= (intval($level) != 1 && intval($level) != 2) ? 'pointer-events: none; background-color: #e9ecef;' : '' ?>">
					<option value="" <?= empty($payment) ? 'selected' : '' ?>>(선택)</option>
					<option value="지급완료" <?= $payment == '지급완료' ? 'selected' : '' ?>>지급완료</option>
				</select>
			</div>


				 <div class="d-flex mt-4 mb-5 justify-content-center">				
					<button type="button" id="closeBtn"  class="btn btn-dark btn-sm me-1" > <ion-icon name="close-outline"></ion-icon> 창닫기 </button>				 

					<?php
					// 권한 체크: 작성자($firstone)나 참여자($emember)에 현재 사용자($user_name)가 포함되어 있거나, $level이 1이면 권한 있음
					$isAuthorized = (intval($level) == 1 || intval($level) == 2) || (strpos($firstone, $user_name) !== false) || (strpos($emember, $user_name) !== false);
					?>

					<button id="saveBtn" class="btn btn-dark btn-sm me-1" type="button" >
						<?php 
						if ((int)$num > 0) { 
							echo '<ion-icon name="color-wand-outline"></ion-icon> 결재상신(수정)'; 
						} else { 
							echo '<ion-icon name="color-wand-outline"></ion-icon> 결재상신(등록)'; 
						}
						?>
					</button>

					<?php if ((int)$num > 0) { ?>
						<button id="delBtn" class="btn btn-danger btn-sm" type="button" 
							<?= !$isAuthorized ? 'disabled style="pointer-events: none; opacity: 0.6;"' : '' ?>>
							<ion-icon name="trash-outline"></ion-icon> 삭제
						</button>
					<?php } ?>


 				 </div>
				</div>
				</div>
				</div>						
			</div>
</form>
</body>
</html>

<script>
	
function delPic(delChoice)
{
if(delChoice=='before')
    $("#filedelete").val('before');
if(delChoice=='after')
    $("#filedelete").val('after');
   
document.getElementById('board_form').submit();	

}
	
$(document).ready(function(){	
		
// 신청일 변경시 종료일도 변경함
$("#occur").change(function(){   
   $('#opendate').val($("#occur").val());            
});	

$("#regpicBtn").click(function(){    // 사진등록
  const num = $("#num").val();
  window.open('reg_pic.php?num=' + num ,"사진등록","width=1200, height=700, top=0,left=0,scrollbars=no");	
});

$("#mainbefore").change(function(e) {
    //do whatever you want here
  	isfile = $("#filename").val();
	changefile = $("#mainbefore").val();
	
	if(changefile!='')
		$("#filename").val('');
	
  // $('#displaypic').show(); 	
  // $('#displaypic').load('pic_insert.php?file=' + fileData );   
  
});
 
	 	  
delPicFn = function(divID, delChoice) {
	console.log(divID, delChoice);

	$.ajax({
		url:'delpic.php?picname=' + delChoice ,
		type:'post',
		data: $("mainFrm").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const picname = data["picname"];		   
		   console.log(data);
			$("#pic" + divID).remove();  // 그림요소 삭제
			$("#delPic" + divID).remove();  // 그림요소 삭제

		   $("#pInput").val('');			
        });		

	};
			
var approve =  $('#approve').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
	

$("#closeBtn").click(function(){    // 저장하고 창닫기	
  window.close();
	 });	
			
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
	var num = $("#num").val();  
	var part = $("#part").val();  
    var approve = $("#approve").val();  
    var user_name = $("#user_name").val(); 
	var reporter = $("#firstone").val();   
	   	   
	if(Number(num)>0) 
	{
		   $("#mode").val('modify');     
	}
		  else
		  {
			  $("#mode").val('insert');     
			if (user_name=='최장중') {
				$("#approve").val('1차결재'); 
			}				
			  
		  }
		  	// 폼데이터 전송시 사용함 Get form         
			var form = $('#board_form')[0];  	    
			// Create an FormData object          
			var data = new FormData(form); 
	
		
		$.ajax({
			enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 600000, 			
			url: "insert.php",
			type: "post",		
			data: data,
			// dataType:"text",  // text형태로 보냄
			success : function( data){
						console.log(data);				
						Toastify({
							text: "파일 저장완료 ",
							duration: 2000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
						
						setTimeout(function(){
							if (window.opener && !window.opener.closed) {
								opener.location.reload();
							}		
						}, 1000);			
				
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });		
		// } // end of if		
			// else
			  // {
			  // tmp='보고자만 결재상신 상태가 아닌 경우 수정이 가능합니다.';		
			  // $('#alertmsg').html(tmp); 			  
				// $('#myModal').modal('show');  
			  // }		
 }); 
	 
$("#delBtn").click(function(){      // del
	var num = $("#num").val();    
	var reporter = $("#firstone").val();    
	var admin = '<?php echo $admin; ?>';
	var user_name = $("#user_name").val();  
	
	if( (reporter==user_name && approve=='결재상신') || (admin=='1') || user_name=='김보곤' || user_name=='최장중') 
	 {  
			
		 Swal.fire({
					title: '자료 삭제',
					text: "삭제는 신중! 정말 삭제하시겠습니까?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: '삭제',
					cancelButtonText: '취소'
				}).then((result) => {
					if (result.isConfirmed) {
						$("#mode").val('delete');  
						$.ajax({
							url: "insert.php",
							type: "post",		
							data: $("#board_form").serialize(),
							dataType:"text",  // text형태로 보냄
							success : function( data ){
								console.log(data);
								Toastify({
									text: "파일 삭제완료 ",
									duration: 2000,
									close:true,
									gravity:"top",
									position: "center",
									style: {
										background: "linear-gradient(to right, #00b09b, #96c93d)"
									},
								}).showToast();	
								setTimeout(function(){
									if (window.opener && !window.opener.closed) {
										window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
										window.opener.location.reload(); // 부모 창 새로고침
										window.close();
									}							
									
								}, 1000);	
							},
							error : function( jqxhr , status , error ){
								console.log( jqxhr , status , error );
							} 			      		
						   });	
							

					}
				});		

		} // end of if
		else
		  {
			Swal.fire({
				title: '삭제불가',
				text: "작성자와 관리자만 삭제가능합니다.",
				icon: 'error',
				confirmButtonText: '확인'
			});
		  }
			
	 }); // end of function

}); // end of ready document

// 자동으로 행이 늘어나는 마법의 자바스크립트 코드
document.addEventListener("DOMContentLoaded", function() {
  const content = document.getElementById("content");
  const textarea = document.getElementById("method");

  // textarea 높이를 조절하는 함수
  function adjustHeight(el) {
    el.style.height = "auto";             // 먼저 높이를 초기화
    el.style.height = el.scrollHeight + "px"; // 내용에 맞춰 높이 재설정
  }

  // 입력 이벤트 발생 시 높이 조절
  content.addEventListener("input", function() {
    adjustHeight(this);
  });
  
  // 입력 이벤트 발생 시 높이 조절
  textarea.addEventListener("input", function() {
    adjustHeight(this);
  });

  // 페이지 로드시, 이미 입력된 내용이 있다면 높이 조절
  adjustHeight(content);
  adjustHeight(textarea);
});
</script>