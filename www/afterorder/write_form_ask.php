<?php include getDocumentRoot() . "/session.php";  

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    
include getDocumentRoot() . '/load_header.php';							  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
 
 if($user_name=='소현철' || $user_name=='김보곤' || $user_name=='최장중' || $user_name=='이경묵'   || $user_name=='소민지'  )
	  $admin = 1;  

$writtenarr = array();
$contentsarr = array();

// 같은 날짜 중복입력을 방지하기 위해서 입력날을 체크한다.

 try{
	  $sql = "select * from ".$DB.".afterorder ";
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
	  $sql = "select * from ".$DB.".afterorder where num = ? ";
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
<form id="board_form" name="board_form" class="form-signin" method="post"  >
	<input type="hidden" id="mode" name="mode">
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  				
	<input type="hidden" id="registdate" name="registdate" value="<?=$today?>" >			  						  
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" size="4" > 					  

<div class="container " style="width:380px;">    

<?php include getDocumentRoot() . '/common/modal.php'; ?>

		
			<div class="card">
				  <div class="card-header">
					<h5 class="card-title text-center" style="color:#113366;"> 식사주문 </h5>
				  </div>
				<div class="card-body text-center">
			  
			  
				<h6 class="form-signin-heading mt-1 mb-1"> 식사 유형 </h6>	
					<div class="d-flex justify-content-center mt-1 mb-4">                
					<select name="content" id="content"  class="form-select form-select-sm w-auto text-center mx-1">
						   <?php		 
						   $content_arr= array();
						   array_push($content_arr,"중식","석식");
						   for($i=0;$i<count($content_arr);$i++) {
								 if($content==$content_arr[$i])
											print "<option selected value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
									 else   
							   print "<option value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
						   } 		   
								?>	  
						</select> 								
					</div>

				<h6 class="form-signin-heading mt-2 mb-1"> 주문일자 </h6>	  
				<div class="d-flex justify-content-center">
				    <input type="date"   class="form-control text-center w-auto" id="askdatefrom"  name="askdatefrom"  required  autofocus  value="<?=$askdatefrom?>" >
				</div>
				<h6 class="form-signin-heading mt-2 mb-1"> 식사인원 </h6>	  
				<div class="d-flex justify-content-center">				
				  <select name="item" id="item"   class="form-select form-select-sm w-auto text-center mx-1">
					<?php							
					$item_arr = array('1','2','3','4','5','6','7','8','9','10', '11','12','13','14','15','16','17','18','19','20');  
					if($item=='') $item="4";
				   for($i=0;$i<count($item_arr);$i++) {
						 if($item === $item_arr[$i])
									print "<option selected value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   "</option>";
							 else   
					   print "<option value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   "</option>";
				   }  		   
					  ?>	  
					</select> 	
				</div>		
				
				<h6 class="form-signin-heading mt-2 mb-1"> 종류 </h6>	  
				<div class="d-flex justify-content-center">				
	          <select name="memo" id="memo"   class="form-select form-select-sm w-auto text-center mx-1">
			    <?php		
				// 연차종류 4종류로 만듬
				$memo_arr = array('지정식당','(외식)중화요리','(외식)한식','(외식)양식','(외식)기타 식사주문','자체(라면외)');  
                if($memo=='') $memo="지정식당";
			   for($i=0;$i<count($memo_arr);$i++) {
					 if($memo === $memo_arr[$i])
								print "<option selected value='" . $memo_arr[$i] . "'> " . $memo_arr[$i] .   "</option>";
						 else   
				   print "<option value='" . $memo_arr[$i] . "'> " . $memo_arr[$i] .   "</option>";
			   }  		   
				  ?>	  
				</select> 	
				</div>	

				<h6 class="form-signin-heading mt-2 mb-1"> 요청/확인 </h6>	  
				<div class="d-flex justify-content-center">				
				  <select name="state" id="state"   class="form-select form-select-sm w-auto text-center mx-1">
					<?php		
					// 연차종류 4종류로 만듬
					$state_arr = array('요청','완료');  
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
				
				<div class="d-flex justify-content-center mt-5 mb-2">				
				<?php if($admin) 				
					print '<button id="saveBtn" class="btn btn-dark btn-sm me-2" type="button">  <i class="bi bi-floppy-fill"></i> 저장	</button>';
				    if((int)$num>0 && $admin ) {  ?>				
				<button id="delBtn" class="btn btn-lg btn-danger btn-sm" type="button"> <i class="bi bi-trash"></i>  삭제  </button>
				<? } ?>
				<button class="btn btn-dark btn-sm ms-4" onclick="self.close();" >  &times; 닫기 </button>
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
	
	var pass = 0;

	// 같은 날짜가 있으면 오류를 내보냄		
	for(i=0;i<	writtenarr.length ; i++)
	{
		if(askdatefrom == writtenarr[i] && content == contentsarr[i])
			pass = 1;		
	}

  // 같은 날짜가 있다면 화면에 모달창 띄움
 if(pass & num==='' )
 {
	 
	 		$('#alertmsg').text('해당 날짜는 이미 등록되어 있습니다. 확인해주세요.');  
	 		$('#myModal').modal('show');  
			return false;
 }

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
			console.log(data);
		    opener.location.reload();
		    window.close();			
		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
		} 			      		
	   });				
		
 }); 
		 
$("#delBtn").click(function(){      // del
var num = $("#num").val();    
var state = $("#state").val();  
var user_name = $("#user_name").val();     
// 결재상신이 아닌경우 수정안됨
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
