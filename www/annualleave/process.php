<?php

if(!isset($_SESSION))      
    session_start(); 
if(isset($_SESSION["DB"]))
    $DB = $_SESSION["DB"];  
$level = $_SESSION["level"];
$user_name = $_SESSION["name"];
$user_id = $_SESSION["userid"]; 

$tablename = "eworks";
										  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
require_once("../lib/mydb.php");
$pdo = db_connect();	

 try{
	  $sql = "select * from " . $DB . "." . $tablename . "  where num = ? ";
	  $stmh = $pdo->prepare($sql); 
      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.		
		 
	  include 'rowDBask.php';
	  
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }
 // end of if	
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	

// 배열로 기본정보 불러옴
 include "load_DB.php";
 
// print $totalremainday;

// 잔여일수 개인별 산출 루틴   
try{  
	// 연차 잔여일수 산출
		$totalusedday = 0;
		$totalremainday = 0;		
		 for($i=0;$i<count($totalname_arr);$i++)	 
			 if($author== $totalname_arr[$i])
			 {
				$availableday  = $availableday_arr[$i];
			 }	

        // 연차 사용일수 계산		
		 for($i=0;$i<count($totalname_arr);$i++)	 
			 if($author== $totalname_arr[$i])
			 {
				$totalusedday = $totalused_arr[$i];
				$totalremainday = $availableday - $totalusedday;	
				
			 }			   					
			
	  } catch (PDOException $Exception) {
	  print "오류: ".$Exception->getMessage();
  }  
  
?>  

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
		    결재가 완료되었습니다. <br> 
		   <br> 
		  수고하셨습니다.
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
</div>

<div class="container h-60">
    <div class="row d-flex justify-content-center align-items-center h-50">	
        <div class="col-12 text-center">
			<div class="card align-middle" style="width:24rem; border-radius:20px;">
				<div class="card" style="padding:10px;margin:10px;">
					<h3 class="card-title text-center" style="color:#113366;"> (연차) 승인</h3>
				</div>	
				<div class="card-body text-center">
			  <form id="board_form" class="form-signin" method="post"  >
				<input type="hidden" id="mode" name="mode">
				<input type="hidden" id="num" name="num" value="<?=$num?>" >			  				
				<input type="hidden" id="registdate" name="registdate" value="<?=$today?>" >			  				
				<input type="hidden" id="al_part" name="al_part" value="<?=$al_part?>" >			  
				<input type="hidden" id="author_id" name="author_id" value="<?=$author_id?>" >			  
				<input type="hidden" id="admin_name" name="admin_name" value="<?=$admin_name?>" >			  
			  
				<span class="form-control">
				성 명		<input type="text" id="author" name="author" size=8  class="text-center" readonly  value="<?=$author?>" >
				</span>
				<span class="form-control">		
				선택		&nbsp;&nbsp;					
			    <?php		
				// 연차종류 4종류로 만듬
				$item_arr = array('연차','오전반차','오전반반차','오후반차','오후반반차');  				
				   for($i=0;$i<count($item_arr);$i++) {
						 if($al_item==$item_arr[$i])
									print "<input type='radio' name='al_item'  checked='checked' value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   " &nbsp ";
							 else   
									print "<input type='radio' name='al_item'  value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   " &nbsp ";
				   } 		   
				  ?>	  
				
				</span>
				<span class="form-control">
				신청시작일 <input type="date"   id="al_askdatefrom"  name="al_askdatefrom"  readonly required  autofocus  value="<?=$al_askdatefrom?>" >
				</span>
				<span class="form-control">
				신청종료일
				<input type="date" id="askdateto"   name="askdateto"  readonly required value="<?=$al_askdateto?>" >
				</span>
				<span class="form-control">
				<span style="color:blue">신청 기간 산출</span>				
				<input type="text"   id="al_usedday"  size=2  name="al_usedday" readonly  class="text-center" value="<?=$al_usedday?>" >
				</span>
				<span class="form-control">
				<span style="color:red">연차 잔여일수</span>					
				<input type="text"   id="totalremainday"   name="totalremainday" size=2  class="text-center" readonly value="<?=$totalremainday?>" >
				</span>	
			<br>
				<h6 class="form-signin-heading">신청 사유</h6>				
					<select name="al_content" id="al_content" readonly class="form-control text-center" >
						   <?php		 
							   $content_arr= array();
							   array_push($content_arr,"개인사정","휴가","여행", "병원진료등", "모두 함께 쉬는 연차휴가", "기타");
							   for($i=0;$i<count($content_arr);$i++) {
									 if($al_content==$content_arr[$i])
												print "<option selected readonly value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
										 else   
								   print "<option readonly value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
							   } 		   
							?>	  
					</select> 		
				<?php
				   switch($status) {					   
					   case 'send':
						  $statusstr = '결재상신';
						  break;
					   case 'ing':
						  $statusstr = '결재중';
						  break;
					   case 'end':
						  $statusstr = '결재완료';
						  break;
					   default:
						  $statusstr = '';
						  break;
				   }	
				?>								
				<h6 class="form-signin-heading">결재 상태</h6>				
					<input type="hidden"   id="status" name="status"  value="<?=$status?>" >						
						<input type="text"   id="statusstr" name="statusstr"  class="form-control text-center" readonly value="<?=$statusstr?>" >											
									<br> 	  
				<button id="saveBtn" class="btn btn-lg btn-secondary btn-block" type="button">
				<? if((int)$num>0) print '승인';  else print '결재상신(등록)'; ?></button>
				<? if((int)$num>0) {  ?>				
				<button id="delBtn" class="btn btn-lg btn-danger btn-block" type="button">삭제</button>
				<? } ?>
			  </form>			  
				</div>
       	   	</div>
			</div>		
				
	  </div>

	</div>		 
		  
<script> 

$(document).ready(function(){
	// 파트 가져옴 (제조파트, 지원파트)
	var al_part = '<?php echo $al_part; ?>';

// 신청일 변경시 종료일도 변경함
$("#al_askdatefrom").change(function(){
   var radioVal = $('input[name="al_item"]:checked').val();	
   console.log(radioVal);
   $('#al_askdateto').val($("#al_askdatefrom").val());      
   
   const result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
   
   switch(radioVal)
   {
      case '연차' :
	     $('#al_usedday').val(result);
		 break;
	  case '오전반차' :	 
	  case '오후반차' :	 	   
		 $('#al_usedday').val(result/2);
		 break;
	  case '오전반반차' :	 
	  case '오후반반차' :	 	   
		 $('#al_usedday').val(result/4);
		 break;
   }
});	
	
$('input[name="al_item"]').change(function(){
   var radioVal = $('input[name="al_item"]:checked').val();	
   console.log(radioVal);
   
   // $('#al_askdateto').val($("#al_askdatefrom").val());      
   
   const result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
   
   switch(radioVal)
   {
      case '연차' :
	     $('#al_usedday').val(result);
		 break;
	  case '오전반차' :	 
	  case '오후반차' :	 	   
		 $('#al_usedday').val(result/2);
		 break;
	  case '오전반반차' :	 
	  case '오후반반차' :	 	   
		 $('#al_usedday').val(result/4);
		 break;
   }
});	

// 종료일을 변경해도 자동계산해 주기	
$("#al_askdateto").change(function(){
   var radioVal = $('input[name="al_item"]:checked').val();	
   console.log(radioVal);
   // $('#al_askdateto').val($("#al_askdatefrom").val());      
   
   const result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
   
   switch(radioVal)
   {
      case '연차' :
	     $('#al_usedday').val(result);
		 break;
	  case '오전반차' :	 
	  case '오후반차' :	 	   
		 $('#al_usedday').val(result/2);
		 break;
	  case '오전반반차' :	 
	  case '오후반반차' :	 	   
		 $('#al_usedday').val(result/4);
		 break;
   }
});	

$("#closeBtn").click(function(){    // 저장하고 창닫기	

	 });	

$("#copyBtn").click(function(){    // 데이터복사버튼

	var num = $("#num").val();     
	location.href='copy_data.php?num=' + num;
	 });					
					
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
	var num = $("#num").val();  
    var status = $("#status").val();   
	$("#mode").val('modify');     
    var admin_name = $("#admin_name").val();
	
	var resultOK = 0;
	
	console.log(status);
	console.log(admin_name);
		
	if((admin_name == '소현철' || admin_name =='김보곤') && status =='ing') {     
		$("#status").val('end');	
		resultOK = 1;
		} // end of if
		
	// 지원파트 1차 결재	
	if((admin_name=='최장중' || admin_name=='김보곤') && status=='send' && al_part=='지원파트') {     
		$("#status").val('ing');	
		resultOK = 1;
		} // end of if	
	// 제조파트 1차 결재	
	if((admin_name=='이경묵' || admin_name=='김보곤') && status=='send' && al_part=='제조파트') {     
		$("#status").val('ing');	
		resultOK = 1;
		} // end of if	
		
    console.log('변경후 status ' + status);		
  if(resultOK == 1)	   {  // 결과가 성공하면 
	$.ajax({
		url: "insert_ask.php",
		type: "post",		
		data: $("#board_form").serialize(),
		dataType:"json",
		success : function( data ){
			console.log( data);
			$('#myModal').modal('show');
			  //1초 지연후 실행
				setTimeout(function() {
			    opener.location.reload();
		        window.close();							 
				}, 2000);		    
		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
		} 			      		
	   });	
					}	   
	else   // 오류가 있으면 오류창 띄움
		{
		  tmp='결재권자가 틀립니다. 확인바랍니다.';		
		  $('#alertmsg').html(tmp); 
			$('#myModal').modal('show');
			  //1초 지연후 실행
				setTimeout(function() {
			    opener.location.reload();
		        window.close();							 
				}, 2000);	
					}
	
		
 }); 
		 
$("#delBtn").click(function(){      // del
	   var num = $("#num").val();    
	   var status = $("#status").val();   
	   
		// 결재상신이 아닌경우 수정안됨
		if(confirm("데이터 삭제.\n\n정말 지우시겠습니까?")) {	
		   $("#mode").val('delete');     	  
			$.ajax({
				url: "insert_ask.php",
				type: "post",		
				data: $("#board_form").serialize(),
				dataType:"json",
				success : function( data ){
					console.log( data);
					opener.location.reload();
					window.close();			
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
				} 			      		
			   });		
			} // end of if
		
 }); 
		 

}); // end of ready document

// 두날짜 사이 일자 구하기 
const getDateDiff = (d1, d2) => {
  const date1 = new Date(d1);
  const date2 = new Date(d2);
  
  const diffDate = date1.getTime() - date2.getTime();
  
  return Math.abs(diffDate / (1000 * 60 * 60 * 24)); // 밀리세컨 * 초 * 분 * 시 = 일
}

</script>
</body>
</html>

