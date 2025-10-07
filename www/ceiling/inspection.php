<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
$title_message = '조명천장 검사서';

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
 
  if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }  
   
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";
 
$inspectiondate = date("Y-m-d", time());
      
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  try{
      $sql = "select * from mirae8440.ceiling where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
      $item_file_0 = $row["file_name_0"];
      $item_file_1 = $row["file_name_1"];

      $copied_file_0 = "../uploads/". $row["file_copied_0"];
      $copied_file_1 = "../uploads/". $row["file_copied_1"];
	 }

              $num=$row["num"];

			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $secondord=$row["secondord"];
			  $address=$row["address"];
			  $worker=$row["worker"];
			  $chargedman=$row["chargedman"];
			  $chargedmantel=$row["chargedmantel"];			  
			  
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=(int)$row["su"];			  
			  $bon_su=(int)$row["bon_su"];			  
			  $lc_su=(int)$row["lc_su"];			  
			  $etc_su=(int)$row["etc_su"];			  
			  $air_su=(int)$row["air_su"];			  
		
	$text='조명천장';			

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  

?>
   
   <title>  <?=$title_message?> </title>
  
   </head>
 <body>   
   
<form  name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?=$num?>"enctype="multipart/form-data"> 
 
 <input type="hidden" id="num" name="num" value="<?=$num?>"  >     
 
<div class="container">

	<div class="card">
			<div class="card-header">
				<div class="d-flex justify-content-center mb-1 mt-1">  
					<h4 class="card-title"> <?=$title_message?>  </h4> 
				</div>
			</div> 			
				<div class="card-body">
					<div class="d-flex justify-content-start mb-2 ">  
						<button onclick="window.close();" type="button" class="btn btn-dark btn-sm me-1">    <ion-icon name="close-outline"></ion-icon> 창닫기  </button>  </button>
						<button type="button"  id="printBtn"   class="btn btn-success btn-sm"> <ion-icon name="print-outline"></ion-icon> 인쇄  </button>        
						
					</div>				
					<div class="row">
						<div class="col-lg-4 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  >검사일시 :</span>
								<input type="date" class="form-control" id="inspectiondate" name="inspectiondate" value="<?=$inspectiondate?>"  >                                                    
							</div>
						</div>
						<div class="col-lg-7 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text"  style="width:100px;" >현장명 : </span>
								<input type="text" class="form-control" type="text"  name="workplacename" value="<?=$workplacename?>"  >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  >현장주소 : </span>
								<input type="text" class="form-control" type="text" name="address" value="<?=$address?>" >                                                    
							</div>
						</div>
						    <div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  > 발주처 :</span>
								<input type="text" class="form-control" type="text" name="secondord" value="<?=$secondord?>"   >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  > 품목 :</span>
								<input type="text" class="form-control" type="text" name="text" value="<?=$text?>"   >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
						</div>						
					</div>		
					</div>
				</div>				
			</div>
	</form>	
</div>																
			

<script>


function inputNumberFormat(obj) { 
    obj.value = comma(uncomma(obj.value)); 
} 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}

function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트 담당자 연락처 찾기
    }
}
function Enter_firstCheck(){
    if(event.keyCode == 13){
      exe_firstordman();  // 원청 담당자 전번 가져오기
    }
}

function Enter_chargedman_Check(){
    if(event.keyCode == 13){
      exe_chargedman();  // 현장소장 전번 가져오기
    }
}

function exe_search()
{

     var tmp=$('#secondordman').val();
	 switch (tmp) {
		 case '김관' :
         $("#secondordmantel").val("010-2648-0225");		 
         $("#secondordman").val("김관부장");		 
         $("#secondord").val("한산");		 
		 break;		
	 }
}
function exe_firstordman()
{
     var tmp=$('#firstordman').val();
	 switch (tmp) {
		 case '고범섭' :
         $("#firstordman").val("고범섭소장");		 		 
         $("#firstordmantel").val("010-6774-6211");		 
         $("#firstord").val("오티스");			 		 		 
         $("#secondord").val("우성");			 
		 break;		 
	 }
}

function exe_chargedman()
{
}

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}


$(document).ready(function(){	


$("#printBtn").click(function(){ 	
	  // page 1로 초기화 해야함
     const inspectiondate = $("#inspectiondate").val();
     const num = $("#num").val();
	 
	 popupCenter('inspection_print.php?num=' + num + '&inspectiondate=' + inspectiondate ,'검사서',1600,950); 
	 
 
 });	

});

</script>
</body>
</html>
