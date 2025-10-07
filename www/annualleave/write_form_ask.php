<?php include $_SERVER['DOCUMENT_ROOT'] . '/session.php';  ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?> 
 <style>
.table-bordered,
.table-bordered td,
.table-bordered th {
    border-color: #000000 !important; /* 더 진한 테두리 색상 */
}
</style>
 
<body>
    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal al_content-->
      <div class="modal-al_content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-1 mb-5 justify-al_content-center" >
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
  <?php

$tablename = "eworks";
										  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
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

// 배열로 기본정보 불러옴
 include "load_DB.php"; 
	
if(empty($num))
{
	$registdate = date("Y-m-d H:i:s");
	$al_askdatefrom=date("Y-m-d");		
	$al_askdateto=date("Y-m-d");	
	// 신규데이터인 경우			
	$al_usedday = abs(strtotime($al_askdateto) - strtotime($al_askdatefrom)) + 1;  // 날짜 빼기 계산	
	$al_item='연차';
	$status='send';
	$statusstr='결재상신';
	$author= $_SESSION["name"];	
	$author_id= $user_id;

// DB에서 al_part 찾아서 넣어주기

	// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기 
	for($i=0;$i<count($basic_name_arr);$i++)  
	{	
	  if(trim($basic_name_arr[$i]) == trim($author))   
	  {
				$al_part = $basic_al_part_arr[$i];
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
 
<form id="board_form"  name="board_form" class="form-signin" method="post"  >  

<div class="container-fluid" style="width:380px;">   
    <div class="row d-flex justify-al_content-center align-items-center h-50">	
        <div class="col-12 text-center">
			<div class="card align-middle" style="border-radius:20px;">
				<div class="card" style="padding:10px;margin:10px;">
					<h3 class="card-title text-center" style="color:#113366;"> (연차)신청 </h3>
				</div>					
				<div class="card-body text-center">
			  
				<input type="hidden" id="mode" name="mode">
				<input type="hidden" id="num" name="num" value="<?=$num?>" >			  				
				<input type="hidden" id="registdate" name="registdate" value="<?=$registdate?>"  >			  						  				
				<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" >
				<input type="hidden" id="author_id" name="author_id" value="<?=$author_id?>" > 					
				<input type="hidden" id="htmltext" name="htmltext" > 					
				
				<?php
				//var_dump($al_part);			

               if($e_confirm ==='') 
			   {
					$formattedDate = date("m/d", strtotime($registdate)); // 월/일 형식으로 변환
					// echo $formattedDate; // 출력
					
					if($al_part=='제조파트')
					{
						$approvals = array(
							array("name" => "공장장 이경묵", "date" =>  $formattedDate),
							array("name" => "대표 소현철", "date" =>  $formattedDate),
							// 더 많은 결재권자가 있을 수 있음...
						);	
					}
					if($al_part=='지원파트')
					{
						$approvals = array(
							array("name" => "이사 최장중", "date" =>  $formattedDate),
							array("name" => "대표 소현철", "date" =>  $formattedDate),
							// 더 많은 결재권자가 있을 수 있음...
						);	
					}
			   }
			   else
			   {			
					$approver_ids = explode('!', $e_confirm_id);
					$approver_details = explode('!', $e_confirm);

					$approvals = array();

					foreach($approver_ids as $index => $id) {
						if (isset($approver_details[$index])) {
							// Use regex to match the pattern (name title date time)
							// The pattern looks for any character until it hits a series of digits that resemble a date followed by a time
							preg_match("/^(.+ \d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})$/", $approver_details[$index], $matches);

							// Ensure that the full pattern and the two capturing groups are present
							if (count($matches) === 3) {
								$nameWithTitle = $matches[1]; // This is the name and title
								$time = $matches[2]; // This is the time
								$date = substr($nameWithTitle, -10); // Extract date from the end of the 'nameWithTitle' string
								$nameWithTitle = trim(str_replace($date, '', $nameWithTitle)); // Remove the date from the 'nameWithTitle' to get just the name and title
								$formattedDate = date("m/d H:i:s", strtotime("$date $time")); // Combining date and time

								$approvals[] = array("name" => $nameWithTitle, "date" => $formattedDate);
							}
						}
					}
			   }					
				
				if($status === 'end')
				  {
				?>
					<div class="container mb-2" style="width:300px;">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th colspan="<?php echo count($approvals); ?>" class="text-center fs-6">결재</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($approvals as $approval) { ?>
										<td class="text-center fs-6" style="height: 50px;"><?php echo $approval["name"]; ?></td>
									<?php } ?>
								</tr>
								<tr>
									<?php foreach ($approvals as $approval) { ?>
										<td class="text-center"><?php echo $approval["date"]; ?></td>
									<?php } ?>
								</tr>
							</tbody>
						</table>
					</div>			  				  
			  <? } ?>
				<div id="savetext">
				<div class="d-flex justify-content-center align-items-center">
					성명		
				<select name="author" id="author" class="form-select d-block w-auto mx-2" style="font-size: 0.8rem; height: 32px;">
				   <?php		                  
				   for($i=0;$i<count($employee_name_arr);$i++) {
						 if($author==$employee_name_arr[$i])
									print "<option selected value='" . $employee_name_arr[$i] . "'> " . $employee_name_arr[$i] .   "</option>";
							 else   
					   print "<option value='" . $employee_name_arr[$i] . "'> " . $employee_name_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select> 
				<!-- 이전 코드 생략 -->
				&nbsp; &nbsp; 부서
				
				<input type="text" id="al_part" name="al_part" value="<?=$al_part ?>"  class="form-control text-center w100px ms-2 me-1"  readonly>
				<!-- 나머지 코드 생략 -->
				</div>								
				<div class="d-flex justify-content-center align-items-center mt-4 mb-1">								
			    <?php		
				// 연차종류 6종류로 만듬
				$item_arr = array('연차','오전반차','오전반반차','오후반차','오후반반차','경조사');  				
				   for($i=0;$i<count($item_arr);$i++) {
						 if($al_item==$item_arr[$i])
								print "<input type='radio' name='al_item'  checked='checked' value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   " &nbsp ";
							 else   
								print "<input type='radio' name='al_item'  value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   " &nbsp ";
								
						if($i%2 == 0)		 
								print '</div> <div class="d-flex justify-content-center align-items-center mt-1 mb-2">	';
				   } 		   
				  ?>	  
				</div>				
				<div class="d-flex justify-content-center align-items-center mt-5 mb-2">								
					신청시작일 
					<input type="date" id="al_askdatefrom"  name="al_askdatefrom"  class="form-control text-center w100px ms-2 me-1"  required  autofocus  value="<?=$al_askdatefrom?>" >
				</div>
				<div class="d-flex justify-content-center align-items-center mb-2">								
					신청종료일
					<input type="date" id="al_askdateto"   name="al_askdateto"    class="form-control text-center w100px ms-2 me-1"  required value="<?=$al_askdateto?>" >
				</div>
				<div class="d-flex justify-content-center align-items-center">												
					<span class="text-danger fw-bold" >신청 기간 산출</span>				
				<input type="text"   id="al_usedday" name="al_usedday"   class="form-control text-center w50px ms-2 me-1"  readonly class="text-center" value="<?=$al_usedday?>" >
				</div>
				<div class="d-flex justify-content-center align-items-center">								
					<span class="text-primary fw-bold"  >연차 잔여일수</span>					
				<input type="text"   id="totalremainday"   name="totalremainday"   class="form-control text-center w50px ms-2 me-1"   class="text-center" readonly value="<?=$totalremainday?>" >
				</div>
				<br>
				<h6 class="form-signin-heading mt-2 mb-2">신청 사유</h6>		
				<div class="d-flex justify-content-center align-items-center">								
					<select name="al_content" id="al_content"  class="form-select d-block w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
					   <?php		 
							   $al_content_arr= array();
							   array_push($al_content_arr,"개인사정","휴가","여행", "병원진료등", "전직원연차", "경조사", "기타");
							   for($i=0;$i<count($al_content_arr);$i++) {
									 if($al_content==$al_content_arr[$i])
												print "<option selected value='" . $al_content_arr[$i] . "'> " . $al_content_arr[$i] .   "</option>";
										 else   
								   print "<option value='" . $al_content_arr[$i] . "'> " . $al_content_arr[$i] .   "</option>";
							   } 		   
						?>	  
						</select> 				
					</div>
				</div>  <!-- end of savetext -->					
				
				
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
									
				<h6 class="form-signin-heading mt-2 mb-2">결재 상태</h6>	
				<div class="d-flex justify-content-center align-items-center">												
					<input type="hidden" id="status" name="status"  value="<?=$status?>" >						
					<input type="text"   id="statusstr" name="statusstr"   class="form-control text-center w120px ms-2 me-1"  readonly value="<?=$statusstr?>" >						
				</div>
					<br> 	  
									
				<button id="saveBtn" class="btn btn-sm btn-dark me-1 " type="button">
				<? if((int)$num>0) print '결재상신(수정)';  else print '결재상신(등록)'; ?></button>
				<? if((int)$num>0) {  ?>				
					<button id="delBtn" class="btn btn-sm btn-danger ms-1 me-4" type="button">삭제</button>
				<? } ?>						  
				<button class="btn btn-dark btn-sm me-1" id="closeBtn" > &times; 닫기 </button>
				</div>
       	   	</div>
			</div>		
				
	  </div>

	</div>		 
		
</form>	
		
<script> 
ajaxRequest = null;

$(document).ready(function(){
				
	// Array of employee names, parts, and corresponding IDs
	var employeeNameArray = <?= json_encode($employee_name_arr); ?>;
	var employeePartArray = <?= json_encode($employee_part_arr); ?>; // Array of corresponding employee parts
	var employeeIdArray = <?= json_encode($employee_id_arr); ?>; // Array of corresponding employee IDs

	// Elements from the DOM
	var nameSelect = document.getElementById("author");
	var partInput = document.getElementById("al_part");
	var authorIdInput = document.getElementById("author_id");

	// Function to update part and author ID based on the selected name
	function updatePartAndId() {
		var selectedIndex = nameSelect.selectedIndex;
		var selectedPart = selectedIndex >= 0 ? employeePartArray[selectedIndex] : "";
		var selectedAuthorId = selectedIndex >= 0 ? employeeIdArray[selectedIndex] : "";

		partInput.value = selectedPart;
		authorIdInput.value = selectedAuthorId;
	}

	// Event listener for when the name selection changes
	nameSelect.addEventListener("change", updatePartAndId);

	// Initialize part and author ID on page load
	updatePartAndId();

	
var status =  $('#status').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
	
	
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
	  case '경조사' :	 	   
		 $('#al_usedday').val(0);
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
	  case '경조사' :	 	   
		 $('#al_usedday').val(0);
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
	  case '경조사' :	 	   
		 $('#al_usedday').val(0);
		 break;		 
   }
});	

$("#closeBtn").click(function(){    // 저장하고 창닫기	
				window.close(); // 현재 창 닫기
	setTimeout(function(){									
				if (window.opener && !window.opener.closed) {					
					window.opener.location.reload(); // 부모 창 새로고침
				}					

				
	}, 1000);
 });	 

// 휴가 등 대량으로 데이터를 생성할때 활용하는 루틴
$("#massBtn").click(function(){   

  $("#mode").val('insert');     
	  
	$.ajax({
		url: "mass_insert.php",
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
 }); 				
					
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
	var num = $("#num").val();  
	var part = $("#part").val();  
    var status = $("#status").val();  
    var user_name = $("#user_name").val(); 	
    var admin = '<?php echo $admin ; ?>';

if(status=='send' || admin === '1') {  
   if(Number(num)>0) 
       $("#mode").val('modify');     
      else
          $("#mode").val('insert');   

    // savetext div의 HTML 내용을 가져옴
    var htmlContent = document.getElementById('savetext').innerHTML;

   $("#htmltext").val(encodeURIComponent(htmlContent));	  
	  
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
		{
			
		Toastify({
			text: "본인과 관리자만 수정 가능",
			duration: 2000,
			close:true,
			gravity:"top",
			position: "center",
			style: {
				background: "linear-gradient(to right, #00b09b, #96c93d)"
			},
		}).showToast();	
		}
		
 }); 
		 
$("#delBtn").click(function(){      // del
var num = $("#num").val();    
var status = $("#status").val();  
var user_name = $("#user_name").val();  
   
// 결재상신이 아닌경우 수정안됨
if(status=='send' || user_name=='김보곤') {   
   
	// DATA 삭제버튼 클릭시
	Swal.fire({ 
	   title: '삭제', 
	   text: " 삭제! '\n 정말 삭제 하시겠습니까?", 
	   icon: 'warning', 
	   showCancelButton: true, 
	   confirmButtonColor: '#3085d6', 
	   cancelButtonColor: '#d33', 
	   confirmButtonText: '삭제', 
	   cancelButtonText: '취소' })
	   .then((result) => { if (result.isConfirmed) {
 
	   
		$("#mode").val('delete');  
			
		if (ajaxRequest !== null) {
				ajaxRequest.abort();
			}

			 // ajax 요청 생성
		 ajaxRequest = $.ajax({	
					url: "insert_ask.php",
					type: "post",		
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){														
								console.log('저장된 Num ' + $("#num").val()) ;													
								 Toastify({
										text: "삭제 완료!",
										duration: 3000,
										close:true,
										gravity:"top",
										position: "center",
										style: {
											background: "linear-gradient(to right, #00b09b, #96c93d)"
										},
									}).showToast();									
																			
							console.log( data);
							opener.location.reload();
							window.close();	
											
							},
							error : function( jqxhr , status , error ){
								console.log( jqxhr , status , error );

								$('#myModal').modal('show');  								
						} 			      		
					   });												
			} 	})

		}
   });	
 

}); // end of ready document

// 두날짜 사이 일자 구하기 
const getDateDiff = (d1, d2) => {
  const date1 = new Date(d1);
  const date2 = new Date(d2);
  
  let count = 0;
  const oneDay = 24 * 60 * 60 * 1000; // 하루의 밀리세컨드 수

  while (date1 < date2) {
    const dayOfWeek = date1.getDay(); // 요일 (0:일, 1:월, ..., 6:토)

    // 토요일(6)이나 일요일(0)이 아닌 경우에만 count 증가
    if (dayOfWeek !== 0 && dayOfWeek !== 6) {
      count++;
    }

    date1.setTime(date1.getTime() + oneDay); // 다음 날짜로 이동
  }

  return count;
}

</script>
</body>
</html>

