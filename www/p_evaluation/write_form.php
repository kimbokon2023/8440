<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함

  // 첫 화면 표시 문구
 $title_message = '협력업체 평가표';     
   
?>
   
<?php include getDocumentRoot() . '/load_header.php' ?>
<link rel="stylesheet" type="text/css" href="./background.css">   
 
<title> <?=$title_message?> </title>  
 
 </head> 

<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php


 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   } 
   

   if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

$mode=$_REQUEST["mode"] ?? '';

if((int)$num > 0 )
	$SelectWork = 'update';
  else
	  $SelectWork = 'insert';
  
isset($_REQUEST["tablename"]) ? $tablename=$_REQUEST["tablename"] :  $tablename=''; 
 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

if($mode!=='copy')
{
     try{
      $sql = "select * from mirae8440.p_evaluation where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
		  print "신규작성.<br>";

		for($i=1;$i<24;$i++)
		{
		   $txtname = 'txt' . $i ;   
		   $$txtname = null ;

		}	
	  
     }else{

			include '_row.php';
					
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
}
else
{
	 try{
      $sql = "select * from mirae8440.p_evaluation where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.    
	   include '_row.php';
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 // 복사시 공백 설정
	 $num = '';
}
 

?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">	
  
	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">             
	<input type="hidden" id="num" name="num" value=<?=$num?> > 
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >	
	<input type="hidden" id="page" name="page" value=<?=$page?> > 
	<input type="hidden" id="mode" name="mode" value=<?=$mode?> > 
	<input type="hidden" id="create" name="create" value=<?=$create?> >   
 
<div class="container">  
	<div class="card mt-2 mb-4">  
	<div class="card-body">   
 <div class="d-flex align-items-center justify-content-end">
  <button type="button" id="saveBtn" class="btn btn-dark  btn-sm mx-1"> <i class="bi bi-floppy-fill"></i> 저장 </button>  
  <button type="button" id="copyBtn" class="btn btn-primary  btn-sm mx-1"> <i class="bi bi-copy"></i> 복사 </button>  
  <?php if(intval($num)>0) { ?>
	  <button type="button" id="delBtn" class="btn btn-danger btn-sm mx-1" > <i class="bi bi-trash"></i>  삭제   </button>	 &nbsp;
  <?php } ?>
  <button type="button" id="closeBtn" class="btn btn-dark btn-sm mx-1"> &times; 닫기 </button>		
</div> 
 <div class="d-flex align-items-center justify-content-center">
  <h4 class="mb-0">평가점수 합 : <span id="total"></span></h4>
</div> 

 <div class="d-flex align-items-center justify-content-center">
<div id="print">  
<div id="outlineprint">  

	
    <div class="img">      
	<div class="clear"> </div>
    <div id="row1">  
	<input id="txt1"  name="txt1" value="<?=$txt1?>" type="text" size="10" style="border-color: blue;width:130px;height:30px;" > 
	<input id="txt2"  name="txt2" value="<?=$txt2?>"  type="text" size="10" style="margin-left:165px;border-color: blue;width:100px;height:30px;" > 	
	</div>    
	<div class="clear"> </div>
    <div id="row2">  
	<input id="txt3"  name="txt3" value="<?=$txt3?>" type="text" size="10" style="border-color: blue;width:130px;height:30px;" > 
	<input id="txt4"  name="txt4" value="<?=$txt4?>"  type="text" size="10" style="margin-left:165px;border-color: blue;width:100px;height:30px;" > 	
	</div>        
    <div class="clear"> </div>	
    <div id="row3">  
	<input id="txt5"  name="txt5" value="<?=$txt5?>" type="text" size="10" style="border-color: blue;width:130px;height:30px;" > 
	<input id="txt6"  name="txt6" value="<?=$txt6?>"  type="date" size="10" style="font-size:12px;margin-left:165px;border-color: blue;width:100px;height:30px;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row4">  
	<input id="txt7"  name="txt7" value="<?=$txt7?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center; " > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row5">  
	<input id="txt8"  name="txt8" value="<?=$txt8?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row6">  
	<input id="txt9"  name="txt9" value="<?=$txt9?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row7">  
	<input id="txt10"  name="txt10" value="<?=$txt10?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row8">  
	<input id="txt11"  name="txt11" value="<?=$txt11?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         	
    <div id="row9">  
	<input id="txt12"  name="txt12" value="<?=$txt12?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>	
    <div id="row10">  
	<input id="txt13"  name="txt13" value="<?=$txt13?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row11">  
	<input id="txt14"  name="txt14" value="<?=$txt14?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row12">  
	<input id="txt15"  name="txt15" value="<?=$txt15?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row13">  
	<input id="txt16"  name="txt16" value="<?=$txt16?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row14">  
	<input id="txt17"  name="txt17" value="<?=$txt17?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         	
    <div id="row15">  
	<input id="txt18"  name="txt18" value="<?=$txt18?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         
    <div id="row16">  
	<input id="txt19"  name="txt19" value="<?=$txt19?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
	
    <div id="row17">  
	<input id="txt20"  name="txt20" value="<?=$txt20?>" type="text" size="5" style="border-color: blue;width:80px;height:30px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         
    <div id="row18">  
	<input id="txt21"  name="txt21" value="<?=$txt21?>" type="date" size="5" style="border-color: blue;width:80px;height:20px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row19">  
	<input id="txt22"  name="txt22" value="<?=$txt22?>" type="date" size="5" style="border-color: blue;width:80px;height:20px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
    <div id="row20">  
	<input id="txt23"  name="txt23" value="<?=$txt23?>" type="date" size="5" style="border-color: blue;width:80px;height:20px; text-align:center;" > 	
	</div>        
    <div class="clear"> </div>		
         

    </div>    <!-- end of outline --> 
    
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

ajaxRequestSub = null ;

$(document).ready(function(){	

	 $("#total").text( $("#txt17").val() +'점');	 
	 
    function calculate() {
		
      // txt7부터 txt15까지 입력된 값들을 더합니다.
      let sum = 0;
      for (let i = 7; i <= 15; i++) {
        let value = parseFloat($('#txt' + i).val());
        if (!isNaN(value)) {
          sum += value;
        }
      }

      // txt16의 값을 뺍니다.
      let subtractValue = parseFloat($('#txt16').val());
      if (!isNaN(subtractValue)) {
        sum -= subtractValue;
      }
	  
	  $("#txt17").val(sum);
	  $("#total").text(sum +'점');
	  
	}

    // txt7부터 txt16까지 input의 입력이 변경될 때마다 calculate() 함수를 호출합니다.
    for (let i = 7; i <= 16; i++) {
      $("#txt" + i).on("input", function() {
        calculate();
      });
     }
	
		
	     // $("#board_form").submit();	   
		// popupCenter('pdf1.php?imageURL=' + $('#imageURL').val(), 'PDF파일보기/저장', 1000,800) ;
		

	$("#copyBtn").on("click", function() {	
	
	    $("#mode").val('copy');
	    $("#board_form").submit();	  
	});
	// 저장 버튼 서버에 저장하고 Ecount 전송함
	$("#saveBtn").on("click", function() {	

      const user_name = '<?php echo $user_name; ?>';
	  
	  if(user_name!=='김보곤') 
	  {
		  myalert('저장 권한이 없습니다.');
	  }
	  else
	  {
		    
         if(Number($("#num").val()) > 0 )
			   $('#SelectWork').val('update');
		   else
			   $('#SelectWork').val('insert');
			   
			console.log( $("#board_form").serialize() );	
			
				  if (ajaxRequest !== null) {
						ajaxRequest.abort();
					}

						 // ajax 요청 생성
						 ajaxRequest = $.ajax({
								url: "process.php",
								type: "post",		
								data: $("#board_form").serialize(),		
								datatype : 'json',
								success : function( data ){	

                                    console.log(data);
                                    $("#num").val(data["num"]);									
							
								Swal.fire({
									
								   title: '자료등록/수정',
								   text: '처리되었습니다.',
								   icon: 'success',
								   
								   showCancelButton: false, // cancel버튼 보이기. 기본은 원래 없음
								   confirmButtonColor: '#3085d6', // confrim 버튼 색깔 지정
								   cancelButtonColor: '#d33', // cancel 버튼 색깔 지정
								   confirmButtonText: '확인', // confirm 버튼 텍스트 지정
								   // cancelButtonText: '취소', // cancel 버튼 텍스트 지정
								   
								   reverseButtons: true, // 버튼 순서 거꾸로
								   
								}).then(result => {
								   // 만약 Promise리턴을 받으면,
								   if (result.isConfirmed) { // 만약 모달창에서 confirm 버튼을 눌렀다면
			  					  opener.location.reload();
								 // $(opener.location).attr("href", "javascript:reloadlist();");	
								  window.close();
												}				
												});				  // end of swal
																	
										},
												error : function( jqxhr , status , error ){
													console.log( jqxhr , status , error );
													   } 			      		
							});	 // end of ajax
							   
					   
	  } // end of else if		
				   										   
   });	 // end of function			
   
				
	$("#closeBtn").click(function(){  	 
        // $("#board_form").submit();	   
		self.close();
		
	});	
						
	$("#delBtn").click(function(){  	 

		var level = Number($('#session_level').val());
		if (level > 2) {
			Swal.fire({
				title: '관리자 권한 필요',
				text: "삭제하려면 관리자에게 문의해 주세요.",
				icon: 'error',
				confirmButtonText: '확인'
			});
		} else {
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
				if (ajaxRequestSub !== null) {
						ajaxRequestSub.abort();
					}							
					ajaxRequestSub = $.ajax({
						url: 'delete.php',
						type: "post",
						data: $("#board_form").serialize(),
						dataType: "json", 
						success: function(data) {	
								setTimeout(function() {
									window.opener.location.reload(); // 0.5초 후 부모 창 새로고침
									setTimeout(function() {
										window.close(); // 추가적으로 0.5초 후 현재 창 닫기
									}, 500);
								}, 500);
							},
							error : function( jqxhr , status , error ){
								console.log( jqxhr , status , error );
							} 			      		
						   });		
				
					}
			});
		}
		
	});	
		
});

function myalert(str) {
 Toastify({
		text: str,
		duration: 3000,
		close:true,
		gravity:"top",
		position: "center",
		backgroundColor: "#4fbe87",
	}).showToast();	
	
	setTimeout(function() {
		// 시간지연
		}, 1000);	
}	
	

</script>	

