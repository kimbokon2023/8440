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

session_start();

$level= $_SESSION["level"];
$user_name= $_SESSION["name"];
										  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=$_REQUEST["num"]; 
require_once("../lib/mydb.php");
$pdo = db_connect();	

 try{
	  $sql = "select * from mirae8440.almember where num = ? ";
	  $stmh = $pdo->prepare($sql); 
      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.		
		 
	  include 'rowDB.php';
	  
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }
 // end of if	

// 배열로 기본정보 불러옴
 include "load_DB.php";		
  
 $currentYear = date("Y"); // 현재 년도 가져오기  
 
 // echo '<pre>';
// print_r($dateofentry_arr);
// echo '</pre>';
 
?>  
<form id="board_form"  name="board_form" class="form-signin" method="post"  >  
<div class="container-fluid" style="width:380px;">   
    <div class="row d-flex justify-al_content-center align-items-center h-50">	
        <div class="col-12 text-center">
			<div class="card align-middle" style="border-radius:20px;">
				<div class="card" style="padding:10px;margin:10px;">
					<h3 class="card-title text-center" style="color:#113366;"> 년초 (연차) 대량등록 </h3>
				</div>					
			<div class="card-body text-center">			  
				<input type="hidden" id="mode" name="mode">
				<input type="hidden" id="num" name="num" value="<?=$num?>" >			  				
				<input type="hidden" id="registdate" name="registdate" value="<?=$registdate?>"  >			  						  				
				<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" >
				<input type="hidden" id="author_id" name="author_id" value="<?=$author_id?>" > 					
				<input type="hidden" id="htmltext" name="htmltext" >						
				<div id="savetext">
				<div class="d-flex justify-content-center align-items-center mb-3">
					<!-- 해당년도 및 현재년도 input 추가 -->
					<div class="mt-3">
						<label for="current-year" class="form-label">해당년도</label>
						<input type="text" id="current-year" name="current_year" class="form-control fs-6 text-center" value="<?= $currentYear ?>" readonly>
					</div>
				</div>
				<div class="form-control">
					<label>
						<input type="checkbox" id="select-all" class="me-2">
						전체 선택
					</label>
					<div id="author-list" class="d-flex flex-wrap mt-2">
						<?php
						for ($i = 0; $i < count($employee_name_arr); $i++) {
							$checked = ($author == $employee_name_arr[$i]) ? "checked" : "";
							$dateofentry = isset($dateofentry_arr[$employee_name_arr[$i]]) ? $dateofentry_arr[$employee_name_arr[$i]] : "";

							echo "<div class='d-flex align-items-start text-start' style='width: 33.33%;'>";
							echo "<label class='w-100'>";
							echo "<input type='checkbox' class='author-checkbox ms-3' name='author[]' value='" . $employee_name_arr[$i] . "' data-author-id='" . $employee_id_arr[$i] . "' data-dateofentry='" . $dateofentry . "' $checked>";
							echo "<span class='ms-2'>" . $employee_name_arr[$i] . "</span>";
							echo "</label>";
							echo "</div>";
						}
						?>
					</div>

				</div>
				</div>
				</div>
				
					<script>
						// "전체 선택" 체크박스 동작
						$('#select-all').on('change', function () {
							var isChecked = $(this).is(':checked');
							$('.author-checkbox').prop('checked', isChecked);
						});

						// 개별 체크박스 동작 시 "전체 선택" 체크박스 상태 업데이트
						$('.author-checkbox').on('change', function () {
							var allChecked = $('.author-checkbox:checked').length === $('.author-checkbox').length;
							$('#select-all').prop('checked', allChecked);
						});
					</script>
				<div class="d-flex justify-content-center align-items-center mb-3">							
					<button id="saveBtn" class="btn btn-sm btn-dark me-1 " type="button"> (주의) 대량등록 실행 </button>					
					<button class="btn btn-dark btn-sm me-1" id="closeBtn" > &times; 닫기 </button>
				</div>
				
				</div>
       	   	</div>
			</div>		
				
	  </div>

	</div>		 
	
</form>	
		
<script> 
var ajaxRequest = null;

$(document).ready(function(){				
	
var status =  $('#status').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
		
$("#closeBtn").click(function(){    // 저장하고 창닫기	
	window.close(); // 현재 창 닫기
	setTimeout(function(){									
		if (window.opener && !window.opener.closed) {					
			window.opener.location.reload(); // 부모 창 새로고침
		}	
	}, 1000);
 });	  

$("#saveBtn").click(function () {
    var authorList = [];
    var basicNameArr = <?= json_encode($employee_name_arr); ?>;
    var basicAlPartArr = <?= json_encode($employee_part_arr); ?>;	

    // 선택된 직원 정보를 배열에 추가
    $(".author-checkbox:checked").each(function () {		
        var authorName = $(this).val();
        var authorPart = "";

        // PHP 배열에서 해당 직원의 부서 찾기
        for (var i = 0; i < basicNameArr.length; i++) {
            if (basicNameArr[i].trim() === authorName.trim()) {
                authorPart = basicAlPartArr[i];
                break;
            }
        }

        authorList.push({
            name: authorName,
            part: authorPart,
            author_id: $(this).data("author-id"),
            availableday: 0 ,
            dateofentry: $(this).data("dateofentry")
        });
    });

    if (authorList.length === 0) {
        alert("직원을 선택하세요.");
        return;
    }

    // 추가 데이터를 hidden 필드로 가져오기
    var registdate = $("#registdate").val();
    var currentYear = $("#current-year").val();
    var alUsedDay = '';

    // 요청 데이터 생성
    var requestData = {
        author_list: JSON.stringify(authorList),        
        current_year: currentYear,
        availableday: alUsedDay
    };

    console.log("Request Data:", requestData);

    $.ajax({
        url: "insert_init.php",
        type: "POST",
        data: requestData,
        dataType: "json",
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "등록 완료",
                    text: "대량 등록이 성공적으로 완료되었습니다.",
                    confirmButtonText: "확인"
                }).then(() => {
                    window.opener.location.reload(); // 부모 창 새로고침
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "오류 발생",
                    text: response.message,
                    confirmButtonText: "확인"
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX 에러:", textStatus, errorThrown);
            Swal.fire({
                icon: "error",
                title: "저장 실패",
                text: "저장 중 오류가 발생했습니다. 다시 시도해주세요.",
                confirmButtonText: "확인"
            });
        }
    });
});


}); // end of ready document

</script>
</body>
</html>