<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함  
 ?>
 
<?php include getDocumentRoot() . '/load_header.php' ?>
  
<?php 
  if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header ("Location:https://8440.co.kr/login/logout.php");
         exit;
   }  
										  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

$writtenarr = array();
$contentsarr = array();

// 같은 날짜 중복입력을 방지하기 위해서 입력날을 체크한다.

 try{
	  $sql = "select * from ".$DB.".daylaborer ";
	  $stmh = $pdo->prepare($sql); 
      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();  
	  
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			 
	  
	  array_push($writtenarr ,$row["askdatefrom"]);
	  array_push($contentsarr ,$row["content"]);
	  
	  }
	  
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }// 같은 날짜 중복입력을 방지하기 위해서 입력날을 체크한다.


// var_dump($writtenarr);

 try{
	  $sql = "select * from ".$DB.".daylaborer where num = ? ";
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

// 배열로 기본정보 불러옴
 include "load_DB.php";
	
if($num=='')
{
	$registdate=date("Y-m-d");	
	$askdatefrom=date("Y-m-d");		
	$askdateto=date("Y-m-d");	
// 신규데이터인 경우			
$usedday = abs(strtotime($askdateto) - strtotime($askdatefrom)) + 1;  // 날짜 빼기 계산	
$item='';
$state='결재상신';
$name= '';	

// DB에서 part 찾아서 넣어주기

	// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기 
	for($i=0;$i<count($basic_name_arr);$i++)  
	{	
	  if(trim($basic_name_arr[$i]) == trim($name))   
	  {
				$part = $basic_part_arr[$i];
				break;
	  }
			
	   
	}
}

// 잔여일수 개인별 산출 루틴   
try{  
	       
		// 연차 잔여일수 산출
		$totalusedday = 0;
		$totalremainday = 0;		
		 for($i=0;$i<count($totalname_arr);$i++)	 
			 if($name== $totalname_arr[$i])
			 {
				$availableday  = $availableday_arr[$i];
			 }	

        // 연차 사용일수 계산		
		 for($i=0;$i<count($totalname_arr);$i++)	 
			 if($name== $totalname_arr[$i])
			 {
				$totalusedday = $totalused_arr[$i];
				$totalremainday = $availableday - $totalusedday;	
				
			 }			   					
			
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
 
 $name_arr = array_unique($totalname_arr); 
?>  
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
  
<form id="board_form" name="board_form" class="form-signin" method="post"  >
	<input type="hidden" id="mode" name="mode">
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  				
	<input type="hidden" id="registdate" name="registdate" value="<?=$today?>" >			  						  
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" > 					  
	<input type="hidden" id="item" name="item" value="1">

<div class="container " style="width:420px;">    		
			<div class="card">
				  <div class="card-header">
					<h5 class="card-title text-center" style="color:#113366;"> 일용직 근태 </h5>
				  </div>
				<div class="card-body text-center">			  
			  
				<h6 class="form-signin-heading mt-1 mb-1"> 근무형태 </h6>	
					<div class="d-flex justify-content-center mt-1 mb-3">                
					<select name="content" id="content" class="form-select form-select-sm w-auto text-center mx-1">
						   <?php		 
						   $content_arr= array();
						   array_push($content_arr,"일당");
						   for($i=0;$i<count($content_arr);$i++) {
								 if($content==$content_arr[$i])
											print "<option selected value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
									 else   
							   print "<option value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
						   } 		   
								?>	  
						</select> 								
					</div>

				<h6 class="form-signin-heading mt-3 mb-1"> 근무일자 </h6>	  
				<div class="d-flex justify-content-center">
				    <input type="date"   class="form-control text-center w-auto" id="askdatefrom"  name="askdatefrom"  required  autofocus  value="<?=$askdatefrom?>"  >	 
				</div>
				
				<h6 class="form-signin-heading mt-3 mb-2"> 성함 </h6>	  
				<div class="d-flex justify-content-center">				
					<input type="text"   class="form-control text-center me-1" style="width:30%;" id="labor_name"  name="labor_name"  required  autofocus  value="<?=$labor_name?>" >	
					 <button id="load_name" class="btn btn-dark btn-sm" type="button"> <i class="bi bi-search"></i>  </button>
				</div>	

				<h6 class="form-signin-heading mt-3 mb-2"> 요청/요청확인/지급완료 </h6>	  
				<div class="d-flex justify-content-center">				
				  <select name="state" id="state"  class="form-select form-select-sm w-auto text-center mx-1">
					<?php		
					// 연차종류 4종류로 만듬
					$state_arr = array('요청','요청확인','지급완료');  
					if($state=='') $state="요청";
				   for($i=0;$i<count($state_arr);$i++) {
						 if($state === $state_arr[$i])
									print "<option selected value='" . $state_arr[$i] . "'> " . $state_arr[$i] .   "</option>";
							 else   
					   print "<option value='" . $state_arr[$i] . "'> " . $state_arr[$i] .   "</option>";
				   }  		   
					  ?>	  
					</select> 	
				</div>	
				<h6 class="form-signin-heading mt-3 mb-2"> 비고(전번/추가근무) </h6>	  
				<div class="d-flex justify-content-center">								  
					<input type="text"   class="form-control text-center" style="width:50%;" id="part"  name="part"  autocomplete="off" autofocus  value="<?=$part?>" >												
				</div>	
				<div class="d-flex justify-content-center mt-4 mb-2">				
				<? if( $user_name==='이경묵' || $user_name==='김보곤' || $user_name==='조경임' || $user_name==='소민지' ) 				
					print '<button id="saveBtn" class="btn btn-dark btn-sm me-2" type="button"> <i class="bi bi-floppy-fill"></i> 저장 </button>';
				 ?>
				 
				<? if((int)$num>0 && ($user_name==='이경묵' || $user_name==='김보곤'|| $user_name==='조경임'|| $user_name==='소민지') ) {  ?>				
				<button id="delBtn" class="btn btn-danger btn-sm" type="button"> <i class="bi bi-trash"></i>  삭제  </button>
				<? } ?>
				<button id="closeBtn" type="button" onclick="self.close();" class="btn btn-dark  btn-sm ms-5"  > &times; 닫기  </button> 	   
                 </div>			  
				</div>
       	   	</div>
	</div>		
		
</body>
</html>
	
		  
<script> 

$(document).ready(function(){
	
var state =  $('#state').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
	

// select 특근으로 수정시 시간 8시간으로 자동변경
$("#content").change(function(){
   var val = $('select[name="content"]').val();	
   
   console.log(val);
		 
});	


$("#closeBtn").click(function(){    // 저장하고 창닫기	

	 });	
	 
$("#load_name").click(function(){  
	
		href = './load_name.php?tablename=daylaborer' ;				
		popupCenter(href, '일용직 이름 검색', 550, 500);

	 });	
				
$("#saveBtn").click(function(){      // DATA 저장버튼 누름

// 일자정보를 배열에 넣고 첫번째줄의 요소를 바꿔준다.

writtenarr = <?php echo json_encode($writtenarr); ?> ;		
contentsarr = <?php echo json_encode($contentsarr); ?> ;		

	var num = $("#num").val();  
	var part = $("#part").val();  
    var state = $("#state").val();  
    var user_name = $("#user_name").val(); 
    var askdatefrom = $("#askdatefrom").val(); 
    var content = $("#content").val(); 
	


if( user_name=='김보곤'  || user_name=='이경묵'  || user_name=='조경임' || user_name=='소민지') {  
   if(Number(num)>0) 
       $("#mode").val('modify');     
      else
          $("#mode").val('insert');     
	  
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
		else
		$('#myModal').modal('show');  
		
 }); 
		 
$("#delBtn").click(function(){      // del
var num = $("#num").val();    
var state = $("#state").val();  
var user_name = $("#user_name").val();     
// 결재상신이 아닌경우 수정안됨
if(state=='결재상신' || user_name=='김보곤' || user_name=='이경묵' || user_name=='조경임' || user_name=='소민지') {   
   $("#mode").val('delete');     
   

	// DATA 삭제버튼 클릭시
		Swal.fire({ 
			   title: '해당 DATA 삭제', 
			   text: " DATA 삭제는 신중하셔야 합니다. '\n 정말 삭제 하시겠습니까?", 
			   icon: 'warning', 
			   showCancelButton: true, 
			   confirmButtonColor: '#3085d6', 
			   cancelButtonColor: '#d33', 
			   confirmButtonText: '삭제', 
			   cancelButtonText: '취소' })
			   .then((result) => { if (result.isConfirmed) { 
												
					$.ajax({
						url: "insert_ask.php",
						type: "post",		
						data: $("#board_form").serialize(),
						dataType:"json",
						success : function( data ){
							console.log( data);
							
																		
									 Toastify({
											text: "파일 삭제 완료!",
											duration: 3000,
											close:true,
											gravity:"top",
											position: "center",
											backgroundColor: "#4fbe87",
										}).showToast();									
								  setTimeout(function() {
											opener.location.reload();
											window.close();									
									   }, 1000);															
														
																						 
												
								},
								error : function( jqxhr , status , error ){
									console.log( jqxhr , status , error );
							} 			      		
						   });												
			   } });     
			   
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
