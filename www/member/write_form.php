<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  

$check = isset($_COOKIE['check']) ? $_COOKIE['check'] : 'false';
$lastdate = isset($_COOKIE['lastdate']) ? $_COOKIE['lastdate'] : 'false';

?>
  
 <?php include getDocumentRoot() . '/load_header.php' ?>
 
 <?php

										  
isset($_REQUEST["id"])  ? $id=$_REQUEST["id"] :   $id=''; 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

	
if($id!=='null')
{
 try{
	  $sql = "select * from mirae8440.member where id = ? ";
	  $stmh = $pdo->prepare($sql); 
      $stmh->bindValue(1,$id,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.		
		   include '_row.php';
		 
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }
 // end of if	
 
 	$mode = 'modify';         
}

else
{
	$id='';
	$level= '8';	
	$mode = 'insert';	
}
 
?>  
  
<title> 회원관리(등록) </title> 

    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
    </style> 
 
 </head>
  
 
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
		   <div id="alertmsg" class="fs-1 mb-5 justify-content-center" >
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

<div class="container-fluid">    
    <div class="card align-middle">
        <div class="card" style="padding:10px;margin:10px;">
            <h4 class="card-title text-center" style="color:#113366;"> 회원등록/수정 </h4>
        </div>
        <div class="card-body text-center">
            <form id="board_form" name="board_form" class="form-signin" method="post">
                <input type="hidden" id="mode" name="mode" value="<?=$mode?>">

                <table class="table table-bordered">
                    <tr>
                        <td colspan="3">* 구분(미래기업,협력사,작업소장,포미스톤)</td>
                        <td colspan="1">
                            <select id="division" name="division" class="form-select w-auto" style="font-size: 0.7rem; height:30px;">
                                <option value="">-- 선택 --</option>
                                <option value="미래기업" <?=($division == "미래기업") ? "selected" : ""?>>미래기업</option>
                                <option value="협력사" <?=($division == "협력사") ? "selected" : ""?>>협력사</option>
                                <option value="작업소장" <?=($division == "작업소장") ? "selected" : ""?>>작업소장</option>
                                <option value="포미스톤" <?=($division == "포미스톤") ? "selected" : ""?>>포미스톤</option>
                            </select>
                        </td>                        
                    </tr>
                    <tr>
                        <td>* 성명</td>
                        <td>
                            <input type="text" id="name" name="name" value="<?=$name?>" class="form-control text-center">
                        </td>
                        <td>* ID</td>
                        <td>
                            <input type="text" id="id" name="id" value="<?=$id?>" class="form-control text-center">
                        </td>
                    </tr>
                    <tr>
                        <td>* Password</td>
                        <td>
                            <input type="text" id="pass" name="pass" value="<?=$pass?>" class="form-control text-center">
                        </td>
                        <td>연락처 (HP)</td>
                        <td>
                            <input type="text" id="hp" name="hp" value="<?=$hp?>" class="form-control text-center">
                        </td>
                    </tr>
                    <tr>
                        <td>* 레벨
                        <br>
                        <span class="text-muted"> - 포미스톤은 20으로 설정</span>
                        </td>
                        <td class="align-middle">
                            <input type="text" id="level" name="level" value="<?=$level?>" class="form-control text-center">
                        </td>
                        <td class="align-middle">파트</td>
                        <td class="align-middle">
                            <input type="text" id="part" name="part" value="<?=$part?>" class="form-control text-center">
                        </td>
                    </tr>
                    <tr>
                        <td>번호순서 (Numorder)</td>
                        <td>
                            <input type="text" id="numorder" name="numorder" value="<?=$numorder?>" class="form-control text-center">
                        </td>
                        <td>직위 (Position)</td>
                        <td>
                            <input type="text" id="position" name="position" value="<?=$position?>" class="form-control text-center">
                        </td>
                    </tr>
                    <tr>
                        <td>전자결재 레벨 (eworks_level)</td>
                        <td colspan="1">
                            <input type="text" id="eworks_level" name="eworks_level" value="<?=$eworks_level?>" class="form-control text-center">
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </table>

                <div class="d-flex justify-content-center mt-4 mb-2">
                    <?php if($user_name === '김보곤'): ?>
                        <button id="saveBtn" class="btn btn-dark btn-sm me-2" type="button">
                            <i class="bi bi-floppy-fill"></i> 저장
                        </button>
                        <button id="delBtn" class="btn btn-danger btn-sm" type="button">
                            <i class="bi bi-trash"></i> 삭제
                        </button>
                        <button class="btn btn-outline-secondary btn-sm me-2 ms-5" type="button" onclick="self.close();">
                            <i class="bi bi-x-lg"></i> 닫기
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>		  
<script> 
ajaxRequest = null;

$(document).ready(function(){
var state =  $('#state').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});

$("#closeBtn").click(function(){    // 저장하고 창닫기	

	 });	
				
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
	var id = $("#id").val();  
	var part = $("#part").val();  

	var form = $('#board_form')[0];  	    	
	var datasource = new FormData(form); 
	
		// console.log(data);
		if (ajaxRequest !== null) {
			ajaxRequest.abort();
		}		 
		ajaxRequest = $.ajax({
			enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 600000, 
			url: "insert.php",
			type: "post",		
			data: datasource,
			dataType:"json",
			success : function(data){
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
							window.opener.location.reload(); // 부모 창 새로고침
							window.close();	
						}
					}, 1000);							
			
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });		
	
		
 }); 
			 
	$("#delBtn").click(function () {
		Swal.fire({
			title: '삭제하시겠습니까?',
			text: "삭제하면 되돌릴 수 없습니다.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '삭제',
			cancelButtonText: '취소'
		}).then((result) => {
			if (result.isConfirmed) {
				$("#mode").val('delete');

				$.ajax({
					url: "insert.php",
					type: "post",
					data: $("#board_form").serialize(),
					dataType: "json",
					success: function (data) {
						console.log(data);
						Swal.fire({
							title: '삭제 완료',
							text: '데이터가 성공적으로 삭제되었습니다.',
							icon: 'success',
							confirmButtonText: '확인'
						}).then(() => {
							opener.location.reload();
							window.close();
						});
					},
					error: function (jqxhr, status, error) {
						console.log(jqxhr, status, error);
						Swal.fire({
							title: '오류 발생',
							text: '삭제 중 오류가 발생했습니다.',
							icon: 'error'
						});
					}
				});
			}
		});
	});
	

}); // end of ready document

// 두날짜 사이 일자 구하기 
const getDateDiff = (d1, d2) => {
  const date1 = new Date(d1);
  const date2 = new Date(d2);
  
  const diffDate = date1.getTime() - date2.getTime();
  
  return Math.abs(diffDate / (1000 * 60 * 60 * 24)); // 밀리세컨 * 초 * 분 * 시 = 일
}


function updateCheck() {
    let isChecked = document.getElementById('check').checked;
    document.cookie = "check=" + isChecked + ";path=/";    	
    document.cookie = "lastdate=" + $("#askdatefrom").val();
}

</script>
</body>
</html>