<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
$title_message = '자체시험 성적서';

include getDocumentRoot() . '/load_header.php';
 
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

 
			  $inspectiondate = $row["mainassembly_date"];			  
			  
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=(int)$row["su"];			  
			  $bon_su=(int)$row["bon_su"];			  
			  $lc_su=(int)$row["lc_su"];			  
			  $etc_su=(int)$row["etc_su"];			  
			  $air_su=(int)$row["air_su"];		
			  
			  $inseung= $row["inseung"];			  
			  $car_insize= $row["car_insize"];			  			  
		
	$text='자체시험성적서';			

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  

?>
   
   <title>  <?=$title_message?> </title>
  
   </head>
 <body>   
   
<form  id="board_form"  name="board_form" enctype="multipart/form-data"> 
 
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
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text"  style="width:100px;" >현장명  </span>
								<input type="text" class="form-control" type="text"  name="workplacename" value="<?=$workplacename?>"  >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  >현장주소  </span>
								<input type="text" class="form-control" type="text" name="address" value="<?=$address?>" >                                                    
							</div>
						</div>
						    <div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  > 발주처 </span>
								<input type="text" class="form-control" type="text" name="secondord" value="<?=$secondord?>"   >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  > 항목 </span>
								<input type="text" class="form-control" type="text" name="text" value="<?=$text?>"   >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  > 인승 </span>
								<input type="text" class="form-control" type="text" name="inseung" value="<?=$inseung?>"   >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-8 mb-1">
							<div class="input-group mb-1" >
								<span class="input-group-text" style="width:100px;"  > Car Inside </span>
								<input type="text" class="form-control" type="text" name="car_insize" value="<?=$car_insize?>"   >                                                    
							</div>
						</div>
						<div class="col-lg-4 mb-1">                                                
												</div>						
					</div>		
					</div>
				</div>				
			</div>
</form>										
			

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

function input_Text(){
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고
  var copyText = document.getElementById("test");   // 클립보드 복사 
  copyText.select();
  document.execCommand("Copy");
}  

function copy_below(){	

var park = document.getElementsByName("asfee");

document.getElementById("ashistory").value  = document.getElementById("ashistory").value + document.getElementById("asday").value + " " + document.getElementById("aswriter").value+ " " + document.getElementById("asorderman").value + " ";
document.getElementById("ashistory").value  = document.getElementById("ashistory").value  + document.getElementById("asordermantel").value + " " ;
     if(park[1].checked) {
        document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 유상 " + document.getElementById("asfee").value + " ";		
	 }		 
	   else
	   {
	    document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 무상 "+ document.getElementById("asfee").value + " ";				   
	   }
	   
document.getElementById("ashistory").value  += document.getElementById("asfee_estimate").value + " " + document.getElementById("aslist").value+ " " + document.getElementById("as_refer").value + " ";	
document.getElementById("ashistory").value  += document.getElementById("asproday").value + " " + document.getElementById("setdate").value+ " " + document.getElementById("asman").value + " ";	
document.getElementById("ashistory").value  += document.getElementById("asendday").value + " " + document.getElementById("asresult").value+ "        ";
   
}  

function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;
	
    }
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


	$("#printBtn").click(function() {
		// Gather form field values
		const inspectiondate = $("#inspectiondate").val();
		const num = $("#num").val();
		const workplacename = $("input[name='workplacename']").val();
		const address = $("input[name='address']").val();
		const secondord = $("input[name='secondord']").val();
		const text = $("input[name='text']").val();
		const inseung = $("input[name='inseung']").val();
		const car_insize = $("input[name='car_insize']").val();

		// Construct the URL with the form field values
		const url = 'millsheet_print.php' +
			'?num=' + num +
			'&inspectiondate=' + inspectiondate +
			'&workplacename=' + encodeURIComponent(workplacename) +
			'&address=' + encodeURIComponent(address) +
			'&secondord=' + encodeURIComponent(secondord) +
			'&text=' + encodeURIComponent(text) +
			'&inseung=' + encodeURIComponent(inseung) +
			'&car_insize=' + encodeURIComponent(car_insize);

		popupCenter(url, '자체시험성적서', 1600, 950);
	});


});
</script>
</body>
</html>
