<?php
/**
 * RnDfund 글쓰기/수정 폼 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
// 첫 화면 표시 문구
$title_message = '연구전담부서 운영비';

?>

<?php
include getDocumentRoot() . '/load_header.php';

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 변수 초기화
$callback = $_REQUEST["callback"] ?? '';
$which = $_REQUEST["which"] ?? '2';
$num = $_REQUEST["num"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$regist_state = $_REQUEST["regist_state"] ?? '1';

// 변수 초기화
$first_writer = '';
$update_log = '';
$page = $_REQUEST["page"] ?? '';
$item = '';
$comment = '';    
  
if ($mode == "modify") {
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        if ($count < 1) {
            echo "검색결과가 없습니다.<br>";
        } else {
            include '_row.php';
        }
    } catch (PDOException $Exception) {
        error_log("게시글 조회 오류: " . $Exception->getMessage());
        echo "오류: " . $Exception->getMessage();
    }
}
  
if ($mode != "modify") {
    // 수정모드가 아닐때 신규 자료일때는 변수 초기화
    $proDate = date("Y-m-d");
    $writer = $_SESSION["name"] ?? '';
    $amount = '';
    $memo = '';
    $which = '2';
} 

?>

<title>  <?=$title_message ?>  </title> 
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
	   <input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >

<div class="container">    
<div class="card">    
<div class="card-header text-center">    
	  <span class="fs-5" >  <?=$title_message ?>  </span >
</div>
<div class="card-body">   
<div class="d-flex mb-1 mt-3 justify-content-start">    		
	<button class="btn btn-dark btn-sm me-1" onclick="self.close();" > &times; 닫기 </button>
	<button type="button" id="saveBtn"  class="btn btn-dark btn-sm"> <i class="bi bi-floppy-fill"></i> 저장 </button>	&nbsp;       
	  
</div>
  	
<div class="d-flex mb-1 mt-3 justify-content-center">   
   
<table class="table table-bordered" >
<tbody>
  <tr>  
<?php
$aryreg = array('', '');  // 배열 초기화
$aryitem = array();
if (empty($which)) $which = '2';
switch ($which) {
    case "1":
        $aryreg[0] = "checked";
        break;
    case "2":
        $aryreg[1] = "checked";
        break;
    default:
        break;
}
?>
	<td colspan="4" class="text-center mt-3">	
		<h6>	
		   구분 :      
		   <span class="text-primary"> 수입   </span>    	   
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
	품 목  	 
	 </td>
	 <td colspan="3">		  
	 <input type="text"  id="item" name="item" value="<?=$item?>" class="form-control" placeholder="품목"> 	 
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
  <tr>
   <td class="text-center">
	비 고
	 </td>
	 <td colspan="3">		  
		<input type="text" name="comment" id="comment" value="<?=$comment?>"  class="form-control"  placeholder="비고" />	 	 </div>
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
							}, 800);						
							
							
							var num = data["num"];
							
							setTimeout(function(){
								location.href='view.php?tablename=' + $("#tablename").val() + '&num=' + num ;					
							}, 800);													
								
						}, 800);

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
