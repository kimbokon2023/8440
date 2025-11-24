<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// Environment detection for URL
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$base_url = $is_local ? 'http://localhost' : 'http://8440.co.kr';
$WebSite = $base_url . '/';

// Initialize request variables with null safety
$menu = $_REQUEST["menu"] ?? '';

// Initialize session variables with null safety
$level = $_SESSION["level"] ?? null;

$title_message = '원자재 가격 설정';

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}    

include getDocumentRoot() . '/load_header.php';   
   
 ?>

<?php include getDocumentRoot() . '/common/modal.php'; ?>

<title> <?=$title_message?>  </title>

<style>
/* 모바일 최적화 스타일 */
@media (max-width: 768px) {
    /* 컨테이너 및 카드 최적화 */
    .container-fluid {
        padding: 0.75rem 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .card {
        margin: 0.5rem auto !important;
        border-radius: 0.5rem !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
    }
    
    .card-header {
        padding: 0.75rem 0.5rem !important;
    }
    
    .card-header h5 {
        font-size: 1.5rem !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
    }
    
    /* 내부 카드 최적화 */
    .card.border-0.bg-light {
        margin: 0.5rem auto !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        box-sizing: border-box !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 1.3125rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: nowrap !important;
        min-height: 40px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.5rem !important;
    }
    
    .btn-sm {
        font-size: 1.2rem !important;
        padding: 0.4rem 0.6rem !important;
    }
    
    .row.mb-3:first-of-type {
        flex-direction: column !important;
    }
    
    .row.mb-3:first-of-type .col-md-6 {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.25rem !important;
    }
    
    .row.mb-3:first-of-type .col-md-6.text-end {
        text-align: left !important;
    }
    
    /* 입력 필드 최적화 */
    .form-label {
        font-size: 1.3125rem !important;
        margin-bottom: 0.25rem !important;
    }
    
    .form-control,
    .form-control-lg {
        font-size: 1.3125rem !important;
        padding: 0.5rem 0.5rem !important;
        height: auto !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 입력 그룹 최적화 */
    .input-group {
        width: 100% !important;
        max-width: 100% !important;
        flex-wrap: nowrap !important;
        box-sizing: border-box !important;
    }
    
    .input-group-text {
        font-size: 1.125rem !important;
        padding: 0.5rem 0.4rem !important;
        min-width: 40px !important;
        flex-shrink: 0 !important;
        box-sizing: border-box !important;
    }
    
    .input-group .form-control {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    /* 행 레이아웃 최적화 */
    .row {
        margin-left: -0.25rem !important;
        margin-right: -0.25rem !important;
    }
    
    .row > [class*="col-"] {
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
        margin-bottom: 0.75rem !important;
    }
    
    /* 첫 번째 행 (PO, CR, EGI) */
    .row.mb-3:nth-of-type(2) .col-md-4 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
    
    /* 두 번째 행 (201 HL, 201 MR, 304 HL, 304 MR) */
    .row.mb-3:nth-of-type(3) .col-md-3 {
        width: 50% !important;
        max-width: 50% !important;
        flex: 0 0 50% !important;
    }
    
    /* 세 번째 행 (i3 304 HL, i3 304 MR, 304 VB, 304 BEAD, 특수소재평균) */
    .row.mb-3:nth-of-type(4) .col-md-3 {
        width: 50% !important;
        max-width: 50% !important;
        flex: 0 0 50% !important;
    }
    
    /* 카드 내부 최적화 */
    .card.border-0.bg-light {
        margin: 0 !important;
        border-radius: 0.5rem !important;
    }
    
    .card.border-0.bg-light .card-body {
        padding: 0.75rem 0.5rem !important;
    }
    
    .card-title {
        font-size: 1.35rem !important;
        margin-bottom: 0.75rem !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 폼 그룹 최적화 */
    .form-group {
        margin-bottom: 0.75rem !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* 숫자 입력 필드 텍스트 정렬 유지 */
    .text-end {
        text-align: right !important;
    }
    
    /* 아이콘 크기 조정 */
    .bi {
        font-size: 1.3125rem !important;
    }
}

/* 모달 최적화 */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem !important;
        max-width: calc(100% - 1rem) !important;
    }
    
    .modal-dialog.modal-xl,
    .modal-dialog.modal-fullscreen {
        margin: 0 !important;
        max-width: 100% !important;
        height: 100% !important;
    }
    
    .modal-content {
        height: 100% !important;
        border-radius: 0.5rem !important;
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        min-height: 50px !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-title {
        font-size: 1.5rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
    }
    
    .modal-header .btn-close {
        margin: 0 !important;
        padding: 0.5rem !important;
    }
    
    .modal-header .btn-sm {
        padding: 0.3rem 0.5rem !important;
        font-size: 1.125rem !important;
        min-height: 36px !important;
        min-width: 36px !important;
        margin: 0.125rem !important;
        flex: 1 1 auto !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        font-size: 1.35rem !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-footer .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 1.3125rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.25rem !important;
    }
    
    /* SweetAlert2 모달 최적화 */
    .swal2-popup {
        width: 90% !important;
        max-width: 90% !important;
        padding: 1rem !important;
        font-size: 1.35rem !important;
    }
    
    .swal2-title {
        font-size: 1.65rem !important;
        word-wrap: break-word !important;
    }
    
    .swal2-content {
        font-size: 1.3125rem !important;
        word-wrap: break-word !important;
    }
    
    .swal2-actions {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    .swal2-confirm,
    .swal2-cancel {
        font-size: 1.3125rem !important;
        padding: 0.5rem 1rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
    }
}

/* PC 화면 최적화 */
@media (min-width: 769px) {
    .btn {
        white-space: nowrap !important;
    }
    
    .input-group-text {
        min-width: 45px !important;
    }
}
</style>

</head>

<body>

<?php
							                	
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	
// 초기 서버를 이동중에 저정해야할 변수들을 저장하면서 작업한다. 자료를 추가 불러올때 카운터 숫자등..
$init_read = array();   // 환경파일 불러오기
$init_read = parse_ini_file("./settings.ini",false);	

// Initialize more request variables with null safety
$SelectWork = $_REQUEST["SelectWork"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';		

 if(file_exists('uploadfilearr.txt'))
    $myfiles = file("uploadfilearr.txt");
	   else
		   $myfiles = array();
	   
// DB에서 자재정보를 읽어온다.	   
require_once("../lib/mydb.php");
$pdo = db_connect();	
$sql="select * from ".$DB.".steelsource"; 
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

  <?php include getDocumentRoot() . '/load_header.php' ?>

<form id="board_form" method="post" enctype="multipart/form-data"  >		
	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>"> 
	<input type="hidden" id="vacancy" name="vacancy" > 																			
									
	<input id="Call_Ecount" type=hidden value="0" >	
	<input id="source_num" name="source_num[]" type=hidden value="<?=$source_num?>" >	
	<input id="sortorder" name="sortorder[]" type=hidden value="<?=$sortorder?>" >	
	<input id="source_item" name="source_item[]" type=hidden value="<?=$source_item?>" >	
	<input id="source_spec" name="source_spec[]" type=hidden value="<?=$source_spec?>" >	
	<input id="source_take" name="source_take[]" type=hidden value="<?=$source_take?>" >		

	<div class="container-fluid py-4">	
		<div class="card shadow-sm border-0">	
			<div class="card-header">	
				<h5 class="text-center mb-0">
					<i class="bi bi-gear-fill me-2"></i><?=$title_message?>
				</h5>
			</div>
			<div class="card-body">						 
				<div class="row mb-3">
					<div class="col-md-6 col-12 mb-2 mb-md-0">
						<button id="SavesettingsBtn" type="button" class="btn btn-success btn-sm w-100">
							<i class="bi bi-floppy-fill me-2"></i>설정 저장
						</button>		
					</div>
					<div class="col-md-6 col-12 text-md-end">
						<button class="btn btn-secondary btn-sm w-100 w-md-auto" onclick="self.close();">
							<i class="bi bi-x-lg me-2"></i>창 닫기
						</button>	
					</div>
				</div>			
				
				<div class="card border-0 bg-light">	
					<div class="card-body">	
						<h6 class="card-title text-muted mb-3">
							<i class="bi bi-currency-dollar me-2"></i>원자재 가격 설정
						</h6>
						
						<!-- 첫 번째 행: PO, CR, EGI -->
						<div class="row mb-3">
							<div class="col-md-4">
								<div class="form-group">
									<label for="PO" class="form-label fw-bold text-secondary">PO</label>
									<div class="input-group">
										<span class="input-group-text bg-warning text-dark">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="PO" class="form-control form-control-lg text-end fw-bold" 
											   name="PO" value="<?=$readIni['PO']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="CR" class="form-label fw-bold text-secondary">CR</label>
									<div class="input-group">
										<span class="input-group-text bg-dark text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="CR" class="form-control form-control-lg text-end fw-bold" 
											   name="CR" value="<?=$readIni['CR']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="EGI" class="form-label fw-bold text-secondary">EGI</label>
									<div class="input-group">
										<span class="input-group-text bg-danger text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="EGI" class="form-control form-control-lg text-end fw-bold" 
											   name="EGI" value="<?=$readIni['EGI']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
						</div>
						
						<!-- 두 번째 행: 201 HL, 201 MR, 304 HL, 304 MR -->
						<div class="row mb-3">
							<div class="col-md-3">
								<div class="form-group">
									<label for="HL201" class="form-label fw-bold text-secondary">201 HL</label>
									<div class="input-group">
										<span class="input-group-text bg-purple text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="HL201" class="form-control form-control-lg text-end fw-bold" 
											   name="HL201" value="<?=$readIni['HL201']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="MR201" class="form-label fw-bold text-secondary">201 MR</label>
									<div class="input-group">
										<span class="input-group-text bg-info text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="MR201" class="form-control form-control-lg text-end fw-bold" 
											   name="MR201" value="<?=$readIni['MR201']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="HL304" class="form-label fw-bold text-secondary">304 HL</label>
									<div class="input-group">
										<span class="input-group-text bg-secondary text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="HL304" class="form-control form-control-lg text-end fw-bold" 
											   name="HL304" value="<?=$readIni['HL304']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="MR304" class="form-label fw-bold text-secondary">304 MR</label>
									<div class="input-group">
										<span class="input-group-text bg-info text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="MR304" class="form-control form-control-lg text-end fw-bold" 
											   name="MR304" value="<?=$readIni['MR304']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
						</div>
						
						<!-- 세 번째 행: i3 304 HL, i3 304 MR, 특수소재평균 -->
						<div class="row mb-3">
							<div class="col-md-3">
								<div class="form-group">
									<label for="i3HL304" class="form-label fw-bold text-secondary">i3 304 HL</label>
									<div class="input-group">
										<span class="input-group-text bg-success text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="I3HL304" class="form-control form-control-lg text-end fw-bold" 
											   name="I3HL304" value="<?=$readIni['I3HL304'] ?? ''?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="i3MR304" class="form-label fw-bold text-secondary">i3 304 MR</label>
									<div class="input-group">
										<span class="input-group-text bg-warning text-dark">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="I3MR304" class="form-control form-control-lg text-end fw-bold" 
											   name="I3MR304" value="<?=$readIni['I3MR304'] ?? ''?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="VB304" class="form-label fw-bold text-secondary"> 304 VB</label>
									<div class="input-group">
										<span class="input-group-text bg-secondary text-dark">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="VB304" class="form-control form-control-lg text-end fw-bold" 
											   name="VB304" value="<?=$readIni['VB304'] ?? ''?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="BEAD304" class="form-label fw-bold text-secondary">304 BEAD</label>
									<div class="input-group">
										<span class="input-group-text bg-secondary text-dark">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="BEAD304" class="form-control form-control-lg text-end fw-bold" 
											   name="BEAD304" value="<?=$readIni['BEAD304'] ?? ''?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="etcsteel" class="form-label fw-bold text-secondary">특수소재평균</label>
									<div class="input-group">
										<span class="input-group-text bg-danger text-white">
											<i class="bi bi-tag-fill"></i>
										</span>
										<input type="text" id="etcsteel" class="form-control form-control-lg text-end fw-bold" 
											   name="etcsteel" value="<?=$readIni['etcsteel']?>" 
											   onkeyup="inputNumberFormat(this)" placeholder="0">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<!-- 빈 공간 -->
							</div>
						</div>
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
</section>
</body>
</html>