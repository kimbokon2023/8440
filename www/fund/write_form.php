<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
$level= $_SESSION["level"];
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];	

include getDocumentRoot() . '/load_header.php';

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}    
   
$callback=$_REQUEST["callback"];  // 출고현황에서 체크번호
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";

  if(isset($_REQUEST["which"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $which=$_REQUEST["which"];
  else
   $which="2";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

   if(isset($_REQUEST["page"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $page=$_REQUEST["page"];
  else
   $page=1;   

  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  
  if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $find=$_REQUEST["find"];
  else
   $find="";

  if(isset($_REQUEST["process"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $process=$_REQUEST["process"];
  else
   $process="전체";

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];


  if(isset($_REQUEST["regist_state"]))  // 등록하면 1로 설정 접수상태
   $regist_state=$_REQUEST["regist_state"];
  else
   $regist_state="1";

 $year=$_REQUEST["year"];   // 년도 체크박스
      
  require_once("../lib/mydb.php");
  $pdo = db_connect();    
  
  if ($mode=="modify"){
    try{
      $sql = "select * from mirae8440.fund where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{


              $num=$row["num"];
 			  $proDate=$row["proDate"];			  			  
 			  $writer=$row["writer"];			  			  
 			  $amount=$row["amount"];			  			  
 			  $memo=$row["memo"];
			  $which=$row["which"];							
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }
  
  
  if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.          
			  $proDate=date("Y-m-d");
 			  $writer=$_SESSION["name"];			  			  
 			  $amount="";			  			  	  			  
 			  $memo="";			  			  
			  $which="2";
  } 

?>

<title> 공동자금 </title> 
</head>
  
<body>

<form  id="board_form" name="board_form" method="post" enctype="multipart/form-data"> 

<div class="container">    
<div class="d-flex mb-1 mt-5 justify-content-center">   
   

			      
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
       <input type="hidden" id="page" name="page" value="<?=$page?>"  >
       <input type="hidden" id="mode" name="mode" value="<?=$mode?>"  >
       <input type="hidden" id="num" name="num" value="<?=$num?>"  >
	

<div class="container">    
<div class="card">    
<div class="card-header text-center">    
<div class="d-flex justify-content-end">    		
	  <span class="fs-5 me-5" > 공동자금 </span>
	  <span class="fs-5 me-5" > &nbsp;</span>
	  <button class="btn btn-dark btn-sm ms-5" onclick="self.close();" > &times; 닫기 </button>
</div>
</div>
<div class="card-body">   
<div class="d-flex mb-1 mt-3 justify-content-start">    		
	<button type="button" id="saveBtn"  class="btn btn-dark btn-sm me-5"> <i class="bi bi-floppy-fill"></i> 저장  </button>	&nbsp;       
</div>
  	
<div class="d-flex mb-1 mt-3 justify-content-center">   
   
<table class="table table-bordered" >
<tbody>
  <tr>
  
 <?php	 		  
	 	 $aryreg=array();
	 	 $aryitem=array();
		 if($which=='') $which='2';
	     switch ($which) {
					case   "1"             : $aryreg[0] = "checked" ; break;
					case   "2"             :$aryreg[1] =  "checked" ; break;
					default: break;
				}		
	   ?>
	<td colspan="4" class="text-center mt-3">	
		<h6>	
		   구분 :       <span class="text-primary"> 수입   </span>    	   
		   <input  type="radio" <?=$aryreg[0]?> name=which value="1">     
			   <span class="text-danger"> 지출   </span>     
			<input  type="radio" <?=$aryreg[1]?>  name=which value="2">  	 
		</h6>
	</td>
  </tr>

  <tr>
   <td class="text-center">
	 기록일   
	 </td>
	 <td>
	 <input type="date" id="proDate" name="proDate" class="form-control text-end" style="width:100px;"  value="<?=$proDate?>" size="14" >  
	 </td>
	 <td class="text-center">	 
	작성자  
	 </td>
	 <td>	
	 <input type="text" id="writer" name="writer" value="<?=$writer?>" class="form-control text-center" style="width:100px;"  >  
    	 </td>
	 </tr>	 
  <tr>
   <td class="text-center">
	내 역  	 
	 </td>
	 <td colspan="3">		  
	 <input type="text"  id="memo" name="memo" value="<?=$memo?>" class="form-control" placeholder="내역"> 	 
    	 </td>
	 </tr>	 
  <tr>
   <td class="text-center">
	금 액
	 </td>
	 <td colspan="3">		  
     <input type="text" name="amount" id="amount" value="<?=$amount?>"  onkeyup="inputNumberFormat(this)" class="form-control text-end" style="width:100px;" placeholder="금액" />	 	 </div>
	    	 </td>
	    	 
		</tr>
	</tbody>
</table>

	 
	  </div> 
	  </div> 
	  </div> 
	  </div> 
	  </div> 
</form>	  
	  
<script>

$(document).ready(function(){
 	 
$("#saveBtn").click(function(){ 
    Fninsert();
}); 	 
			
// 자료의 삽입/수정하는 모듈 
function Fninsert() {	 
		   
    var form = $('#board_form')[0];
    var data = new FormData(form);

    // 폼 데이터를 콘솔에 출력하여 확인합니다.
    // for (var pair of data.entries()) {
        // console.log(pair[0] + ', ' + pair[1]);
    // }	
   // console.log(data);   
   
	ajaxRequest = $.ajax({
		enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
		processData: false,    
		contentType: false,      
		cache: false,           
		timeout: 600000, 			
		url: "insert.php",
		type: "post",		
		data: data,			
		dataType:"json",  
		success : function(data){
			
				console.log(data);
				setTimeout(function() {						
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
								opener.location.reload();
							}		
						}, 1000);						
						
						
						var num = data["num"];
						
						setTimeout(function(){
							location.href='view.php?num=' + num ;					
						}, 1000);													
							
					}, 1000);

		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
					} 			      		
	   });		

		
 }
 
	
});

function inputNumberFormat(obj) { 
    obj.value = comma(uncomma(obj.value)); 
} 

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}


</script> 
	</body>
 </html>
