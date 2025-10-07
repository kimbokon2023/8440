 <!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.css" integrity="sha512-VSD3lcSci0foeRFRHWdYX4FaLvec89irh5+QAGc00j5AOdow2r5MFPhoPEYBUQdyarXwbzyJEO7Iko7+PnPuBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="./style.css" rel="stylesheet">

<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.js" integrity="sha512-MnKz2SbnWiXJ/e0lSfSzjaz9JjJXQNb2iykcZkEY2WOzgJIWVqJBFIIPidlCjak0iTH2bt2u1fHQ4pvKvBYy6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="http://8440.co.kr/order/order.js"></script>
<script src="http://8440.co.kr/common.js"></script>	

</head>


<?php session_start(); 
							                	
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	
// 초기 서버를 이동중에 저정해야할 변수들을 저장하면서 작업한다. 자료를 추가 불러올때 카운터 숫자등..
$init_read = array();   // 환경파일 불러오기
$init_read = parse_ini_file("./settings.ini",false);	

if(isset($_REQUEST["SelectWork"]))  // 어떤 작업을 지시했는지 파악해서 돌려줌.
	$SelectWork=$_REQUEST["SelectWork"];
		else 
			$SelectWork="";   // 초기화		

 if(file_exists('uploadfilearr.txt'))
    $myfiles = file("uploadfilearr.txt");
	   else
		   $myfiles = array();
	   
// DB에서 자재정보를 읽어온다.	   
require_once("../lib/mydb.php");
$pdo = db_connect();	
$sql="select * from mirae8440.steelsource"; 
    try{  

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $count=0;
   $source_num=array();
   $sortorder=array();
   $source_item=array();
   $source_spec=array();
   $source_take=array();   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
 			  $source_num[$count]=$row["num"];			  
 			  $sortorder[$count]=$row["sortorder"];
 			  $source_item[$count]=$row["item"];
 			  $source_spec[$count]=$row["spec"];
		      $source_take[$count]=$row["take"]; 
	        $count++;	   			  
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
	   
?>
<body>


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">원자재 현황 부족(마이너스) 상태 알림</h4>
        </div>
        <div class="modal-body">		
           <div class="row gx-4 gx-lg-4 align-items-center">		  
				   <br>
				   <div id="alertmsg" class="fs-3" > </div> <br>
				  <br>		  									
				</div>
			</div>		  
        <div class="modal-footer">
          <button id="closeModalBtn" type="button" class="btn btn-default btn-sm " data-dismiss="modal">닫기</button>
        </div>
		</div>
		</div>
      </div>      
   
<? include '../myheader.php'; ?>   

<style>
  .text-center {
    display: flex;
    justify-content: center;
  }
</style>

<div class="container text-center">
<div class="row d-flex mt-3 p-1 mb-1 justify-content-center" >	
    <h3>미래기업 원자재 환경설정</h3>
  </div>
</div>
<div class="container text-center">
  <div class="row d-flex mt-1 p-1 mb-1 justify-content-center" >	
  <form id="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">
    <input type="hidden" id="vacancy" name="vacancy">
    <div class="card-header">
      <div class="input-group p-1 mb-1 text-center">
        <button type="button" class="btn btn-success" id="SavesettingsBtn">환경설정 적용&저장</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="input-group-text">원자재 자료 수정/저장 화면</span>
      </div>
      <div class="input-group p-1 mb-1">
        <span style="color:red;">&nbsp;&nbsp;원자재(철판) 단가(KG당)&nbsp;&nbsp;</span>
      </div>
      <div class="input-group p-1 mb-1">
        <span class="input-group-text bg-secondary"><i class="bi bi-layout-text-window"></i></span>
        <span class="input-group-text text-white bg-secondary">PO</span>
        <input type="text" id="PO" style="color:grey;text-align:right;" name="PO" size="6" value="<?=$readIni['PO']?>" onkeyup="inputNumberFormat(this)">
        <span class="input-group-text text-white bg-secondary">CR</span>
        <input type="text" id="CR" style="color:black;text-align:right;" name="CR" size="6" value="<?=$readIni['CR']?>" onkeyup="inputNumberFormat(this)">
        <span class="input-group-text text-white bg-secondary">EGI</span>
        <input type="text" id="EGI" style="color:brown;text-align:right;" name="EGI" size="6" value="<?=$readIni['EGI']?>" onkeyup="inputNumberFormat(this)">
      </div>
      <div class="input-group p-1 mb-1">
        <span class="input-group-text text-white bg-secondary">201 HL</span>
        <input type="text" id="HL201" style="color:purple;text-align:right;" name="HL201" size="6" value="<?=$readIni['HL201']?>" onkeyup="inputNumberFormat(this)">
        <span class="input-group-text text-white bg-secondary">201 MR</span>
        <input type="text" id="MR201" style="color:blue;text-align:right;" name="MR201" size="6" value="<?=$readIni['MR201']?>" onkeyup="inputNumberFormat(this)">
      </div>
      <div class="input-group p-1 mb-1">
        <span class="input-group-text text-white bg-secondary">304 HL</span>
        <input type="text" id="HL304" style="color:purple;text-align:right;" name="HL304" size="6" value="<?=$readIni['HL304']?>" onkeyup="inputNumberFormat(this)">
        <span class="input-group-text text-white bg-secondary">304 MR</span>
        <input type="text" id="MR304" style="color:blue;text-align:right;" name="MR304" size="6" value="<?=$readIni['MR304']?>" onkeyup="inputNumberFormat(this)">
      </div>
      <div class="input-group p-1 mb-1">
        <span class="input-group-text text-white bg-secondary">특수소재평균값</span>
        <input type="text" id="etcsteel" style="color:red;text-align:right;" name="etcsteel" size="6" value="<?=$readIni['etcsteel']?>" onkeyup="inputNumberFormat(this)">
      </div>
      <input id="Call_Ecount" type="hidden" value="0">
      <input id="source_num" name="source_num[]" type="hidden" value="<?=$source_num?>">
      <input id="sortorder" name="sortorder[]" type="hidden" value="<?=$sortorder?>">
      <input id="source_item" name="source_item[]" type="hidden" value="<?=$source_item?>">
      <input id="source_spec" name="source_spec[]" type="hidden" value="<?=$source_spec?>">
      <input id="source_take" name="source_take[]" type="hidden" value="<?=$source_take?>">
    </div>
  </form>
  </div>

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
    str = str.replace(/\./g, ''); 
    return Number(str.replace(/[^\d]+/g, '')); 
}

$(document).ready(function(){
	
	let timer2 = setTimeout(function(){  // 시작과 동시에 계산이 이뤄진다.
		// calculateit();
		$("#TIME_DATE").val(getToday());		
		console.log(getToday());	
	}, 1000)
	
	
					 $("#PreviousBtn").click(function(){       // 이전화면 돌아가기		    							 
						    self.close();
					 });		
				 					  					 
					 
					 $("#SavesettingsBtn").click(function(){   						
											
						let msg = '저장완료';
														
							$.ajax({
								url: "process.php",
								type: "post",		
								data: $("#board_form").serialize(),								
								success : function( data ){		

								   console.log(data);			
																			
										 Toastify({
												text: msg ,
												duration: 3000,
												close:true,
												gravity:"top",
												position: "center",
												backgroundColor: "#4fbe87",
											}).showToast();			
									
								 // // 부모창 실행
									if (window.opener) {
										window.opener.location.reload();
									}
									  self.close();					
	  
																		
														},
														error : function( jqxhr , status , error ){
															console.log( jqxhr , status , error );
															   } 			      		
												   });												
								
								   
					 });		// 환경설정 저장클릭									 
					 			 
	 $("#deldataBtn").click(function(){    deldataDo(); });	  
	 $("#SelInsertDataBtn").click(function(){    SelInsertData(); });	  							 

	 $("#Ecount_estimate").click(function(){Ecount_login_click('estimate');});	
					 
					 
		
	 
				 
		
});	 // end of readydocument


 
function 	alert_msg(titlemsg,contextmsg) {
// 화면에 메시지창
	Swal.fire({ 
		   title: titlemsg, 
		   text: contextmsg , 
		   icon: 'success',                  // success, error, warning, info, question  5가지 가능함.
		   showCancelButton: true, 
		   confirmButtonColor: '#3085d6', 
		   cancelButtonColor: '#d33', 
		   confirmButtonText: '저장', 
		   cancelButtonText: '취소' })
		   .then((result) => { if (result.isConfirmed) { 
			$("#SelectWork").val('saveini'); 						 
			$("#mainFrm").submit();  
		   
		   Swal.fire( '수고하세요.', '알림완료!', 'success' ) } })		
}
	
					 
function getToday(){   // 2021-01-28 형태리턴
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;    //1월이 0으로 되기때문에 +1을 함.
    var date = now.getDate();

    month = month >=10 ? month : "0" + month;
    date  = date  >= 10 ? date : "0" + date;
     // ""을 빼면 year + month (숫자+숫자) 됨.. ex) 2018 + 12 = 2030이 리턴됨.

    //console.log(""+year + month + date);
    return today = ""+year + "-" + month + "-" + date; 
}

// 형식 : sleep(초)    //1/1000초 단위 ex) 5초 = sleep(5000)

function sleep(num){	//[1/1000초]

			 var now = new Date();

			   var stop = now.getTime() + num;

			   while(true){

				 now = new Date();

				 if(now.getTime() > stop)return;

			   }

}
</script> 	 


</body>

</html>