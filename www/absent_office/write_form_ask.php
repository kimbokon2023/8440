<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함

$check = isset($_COOKIE['check']) ? $_COOKIE['check'] : 'false';
$lastdate = isset($_COOKIE['lastdate']) ? $_COOKIE['lastdate'] : 'false';
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<?php

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:https://8440.co.kr/login/logout.php"); 
         exit;
   }
										  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

 try{
	  $sql = "select * from mirae8440.absent_office where num = ? ";
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
	
if($check !== 'false')	
{
	$askdatefrom=$lastdate;	
}
else
{
	$askdatefrom=date("Y-m-d");		
}

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

<body>

<form id="board_form" class="form-signin" method="post"  >
	<input type="hidden" id="mode" name="mode">
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  				
	<input type="hidden" id="registdate" name="registdate" value="<?=$today?>" >			  						  
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" > 					

<div class="container justify-content-center align-items-center " style="width:415px;">
	
		<div class="card mt-5">
		<div class="card-body text-center">
			<div class="d-flex mt-3 mb-3 align-middle justify-content-center align-items-center ">                     
					<h4 class="text-center" style="color:#113366;"> 연장근무/특근 </h4>		
			</div>
			<div class="d-flex justify-content-center align-items-center mt-3 mb-3">					
				<span> 성명	 </span>
				<select name="name" id="name" class="form-select form-select-sm w-auto text-center mx-1">
				   <?php		            		   
				   for($i=0;$i<count($name_arr);$i++) {
						 if($name==$name_arr[$i])
									print "<option selected value='" . $name_arr[$i] . "'> " . $name_arr[$i] .   "</option>";
							 else   
					   print "<option value='" . $name_arr[$i] . "'> " . $name_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select> 		
				<span> 부서	 </span>				
				<select name="part" id="part"  class="form-select form-select-sm w-auto text-center mx-1">
				   <?php		 
				   $part_arr= array();
				   array_push($part_arr,"지원파트");
				   for($i=0;$i<count($part_arr);$i++) {
						 if($part==$part_arr[$i])
									print "<option selected value='" . $part_arr[$i] . "'> " . $part_arr[$i] .   "</option>";
							 else   
					   print "<option value='" . $part_arr[$i] . "'> " . $part_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select> 	
			</div>			
				<div class="d-flex justify-content-center align-items-center mt-3 mb-3">								
					고정 
					<input type="checkbox" id="check"  name="check"  value="<?=$check?>"  <?= $check === 'true' ? 'checked' : '' ?> onchange="updateCheck()" > &nbsp;&nbsp;
					작업일 
					<input type="date"  class="form-control w100px mx-2" id="askdatefrom"  name="askdatefrom"  required  autofocus  value="<?=$askdatefrom?>" >
				</div>				
				<div class="d-flex justify-content-center align-items-center mt-2 mb-2">
					시간선택		&nbsp;&nbsp;					
				  <select name="item" id="item"  class="form-select form-select-sm w-auto text-center">
					<?php		
					// 연차종류 4종류로 만듬
					$item_arr = array('1','1.5','2','2.5','3','3.5','4','4.5','5','5.5','6','6.5','7','7.5','8','8.5','9','9.5','10','10.5','11','11.5','12','12.5','13','13.5','14');
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

				<div class="d-flex justify-content-center align-items-center mt-3 mb-3">					
						신청구분  &nbsp;&nbsp;           
					<select name="content" id="content" class="form-select form-select-sm w-auto text-center">
					   <?php		 
							   $content_arr= array();
							   array_push($content_arr,"특근");
							   for($i=0;$i<count($content_arr);$i++) {
									 if($content==$content_arr[$i])
												print "<option selected value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
										 else   
								   print "<option value='" . $content_arr[$i] . "'> " . $content_arr[$i] .   "</option>";
							   } 		   
							?>	  
					</select> 	
				</div>							
					<div class="d-flex mt-3 mb-3 align-middle justify-content-center align-items-center ">  						
						<button id="saveBtn" class="btn btn-dark btn-sm me-1" type="button">  <i class="bi bi-floppy-fill"></i> 저장 </button>						
						<?php if((int)$num>0 && ($user_name==='김보곤') ) {  ?>				
							<button id="delBtn" class="btn btn-danger btn-sm" type="button"> <i class="bi bi-trash"></i> 삭제  </button>
						<? } ?>
						<button class="btn btn-dark btn-sm mx-4" id="closeBtn" >  &times; 닫기 </button>					
					</div>
				</div>
       	   	</div>
			</div>	
		</form>		
	</body>
</html>
		
<script> 

$(document).ready(function(){
var state =  $('#state').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
	

// 신청일 변경시 종료일도 변경함
$("#askdatefrom").change(function(){
   var radioVal = $('input[name="item"]:checked').val();	
   console.log(radioVal);
   $('#askdateto').val($("#askdatefrom").val());      
   
   const result = getDateDiff($("#askdatefrom").val(), $("#askdateto").val()) + 1;
   
   switch(radioVal)
   {
      case '연차' :
	     $('#usedday').val(result);
		 break;
	  case '오전반차' :	 
	  case '오후반차' :	 	   
		 $('#usedday').val(result/2);
		 break;
	  case '오전반반차' :	 
	  case '오후반반차' :	 	   
		 $('#usedday').val(result/4);
		 break;
   }
		
 //  고정여부 확인		
 updateCheck();

		
});	

// select 특근으로 수정시 시간 8시간으로 자동변경
$("#content").change(function(){
   var val = $('select[name="content"]').val();	
   
   console.log(val);

   switch(val)
   {
      case '특근' :
	     $("#item").val("8").prop("selected", true);
		 break;
	  case '연장근로' :	 	  
		 $("#item").val("2.5").prop("selected", true);
		 break;
   }
		 
});	


$("#closeBtn").click(function(){    // 저장하고 창닫기	
      window.close();
	 });	
				
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
	var num = $("#num").val();  
	var part = $("#part").val();  
    var state = $("#state").val();  
    var user_name = $("#user_name").val(); 
	
	
if(user_name=='김보곤' || user_name === $("#name").val() )
{
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
}
  else
	  alert('작성자만 수정이 가능합니다.');
		
 }); 
		 
$("#delBtn").click(function(){      // del
    var user_name  = '<?php echo  $user_name ; ?>' ;
    var name  = '<?php echo  $name ; ?>' ;
    var admin  = '<?php echo  $admin ; ?>' ;
	if( user_name !== name && admin !== '1' )
	{
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
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
			 $("#mode").val('delete');     
				  
				$.ajax({
					url: "insert_ask.php",
					type: "post",		
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){
						console.log( data);
						if (window.opener && !window.opener.closed) {
							opener.location.reload();
						}
						window.close();			
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
					} 			      		
				   });	

            }
        });
    }
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
