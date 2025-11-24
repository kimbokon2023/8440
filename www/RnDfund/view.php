<?php
/**
 * RnDfund 상세보기 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php'; 

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}   

include includePath('load_header.php'); 
// 첫 화면 표시 문구
$title_message = '연구전담부서 운영비';   
 
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$page = $_REQUEST["page"] ?? '';
$search = $_REQUEST["search"] ?? '';
$Bigsearch = $_REQUEST["Bigsearch"] ?? '';
$find = $_REQUEST["find"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';

// 기타 변수 초기화
$first_writer = '';
$update_log = '';
$which = '';
$proDate = '';
$writer = '';
$item = '';
$memo = '';
$amount = '';
$comment = '';
$admin = $_SESSION["admin"] ?? '';
      
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

    try{
      $sql = "select * from  ".$DB."." . $tablename . "  where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
		 
			include '_row.php';			
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 
?>
 
 <title> <?=$title_message ?>  </title> 
 <style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        .card-header {
            padding: 0.75rem !important;
        }
        
        .card-header span {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* 버튼 그룹 최적화 */
        .d-flex.mb-1.mt-3.justify-content-start {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-1.mt-3.justify-content-start button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 테이블 최적화 - 카드 형식으로 변환 */
        .table {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .table thead {
            display: none !important;
        }
        
        .table tbody {
            display: block !important;
            width: 100% !important;
        }
        
        .table tr {
            display: block !important;
            width: 100% !important;
            margin-bottom: 0.5rem !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            padding: 0.75rem !important;
            background: #fff !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            box-sizing: border-box !important;
        }
        
        .table td {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            padding: 0.5rem !important;
            border: none !important;
            text-align: left !important;
            box-sizing: border-box !important;
        }
        
        .table td::before {
            content: attr(data-label) !important;
            font-weight: bold !important;
            font-size: 0.875rem !important;
            color: #495057 !important;
            margin-bottom: 0.25rem !important;
        }
        
        .table td:first-child::before {
            content: attr(data-label) !important;
        }
        
        /* 입력 필드 최적화 */
        .form-control {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            box-sizing: border-box !important;
        }
        
        input[type="date"],
        input[type="text"],
        input[type="radio"] {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 라디오 버튼 그룹 최적화 */
        h6 {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        h6 span {
            display: inline-block !important;
            margin-right: 0.5rem !important;
        }
        
        h6 input[type="radio"] {
            width: auto !important;
            margin-right: 0.5rem !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
</style>
 </head>
   
<body>

<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" > 

<div class="container">    
			      
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
       <input type="hidden" id="page" name="page" value="<?=$page?>"  >
       <input type="hidden" id="num" name="num" value="<?=$num?>"  >
	   <input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >	   


<div class="card">    
<div class="card-header text-center">    
	  <span class="fs-4" > <?=$title_message ?>  </span >
</div>
<div class="card-body">   
<div class="d-flex mb-1 mt-3 justify-content-start">    		
	<button class="btn btn-dark btn-sm me-1" onclick="self.close();" > &times; 닫기 </button>
<?php
   if(isset($_SESSION["userid"]) &&  ( $user_name==='소현철' ||  $user_name==='소민지' ||  $user_name==='김보곤')   )
   {
  ?>	
	<button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>&mode=modify&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page ?? '', ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>&Bigsearch=<?= htmlspecialchars($Bigsearch ?? '', ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find ?? '', ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year ?? '', ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process ?? '', ENT_QUOTES, 'UTF-8') ?>&asprocess=<?= htmlspecialchars($asprocess ?? '', ENT_QUOTES, 'UTF-8') ?>&fromdate=<?= htmlspecialchars($fromdate ?? '', ENT_QUOTES, 'UTF-8') ?>&todate=<?= htmlspecialchars($todate ?? '', ENT_QUOTES, 'UTF-8') ?>&separate_date=<?= htmlspecialchars($separate_date ?? '', ENT_QUOTES, 'UTF-8') ?>'" >  <i class="bi bi-pencil-square"></i>  수정  </button>			
	<button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'" >  <i class="bi bi-pencil"></i>  신규 </button>			
	<button type="button" class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page ?? '', ENT_QUOTES, 'UTF-8') ?>')" > <i class="bi bi-trash"></i>  삭제   </button>								
  <?php
   }
  ?> 	
	
</div>
  	
<div class="d-flex mb-1 mt-3 justify-content-center">   
   
<table class="table table-bordered" >
<tbody>
  <tr>
  
 <?php
	 	 $aryreg = array('', '');  // 배열 초기화
	 	 $aryitem = array();
		 if($which=='') $which='2';
	     switch ($which) {
					case   "1"             : $aryreg[0] = "checked" ; break;
					case   "2"             : $aryreg[1] = "checked" ; break;
					default: break;
				}
	   ?>
	<td colspan="4" class="text-center mt-3" data-label="구분">	
    <h6>	
	   구분 :       <span class="text-primary"> 수입   </span>    	   
	   <input  type="radio" <?= htmlspecialchars($aryreg[0], ENT_QUOTES, 'UTF-8') ?> name="which" value="1">     
		   <span class="text-danger"> 지출   </span>     
		<input  type="radio" <?= htmlspecialchars($aryreg[1], ENT_QUOTES, 'UTF-8') ?>  name="which" value="2">  	 
		</h6>
	</td>
  </tr>

  <tr>
   <td class="text-center" data-label="기록일">
	 기록일   
	 </td>
	 <td data-label="기록일">
	 <input type="date" id="proDate" name="proDate" class="form-control text-end" style="width:100px;" value="<?= htmlspecialchars($proDate, ENT_QUOTES, 'UTF-8') ?>" size="14" >  
	 </td>
	 <td class="text-center" data-label="작성자">	 
	작성자  
	 </td>
	 <td data-label="작성자">	
	 <input type="text" id="writer" name="writer" value="<?= htmlspecialchars($writer, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center" style="width:100px;"  >  
    	 </td>
	 </tr>	 
  <tr>
   <td class="text-center" data-label="품목">
	품 목  	 
	 </td>
	 <td colspan="3" data-label="품목">		  
	 <input type="text" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" class="form-control" placeholder="품목"> 	 
    	 </td>
 </tr> 
   <tr>
   <td class="text-center" data-label="내역">
	내 역  	 
	 </td>
	 <td colspan="3" data-label="내역">		  
	 <input type="text" id="memo" name="memo" value="<?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?>" class="form-control" placeholder="내역"> 	 
    	 </td>
 </tr>	 
  <tr>
   <td class="text-center" data-label="금액">
	금 액
	 </td>
	 <td colspan="3" data-label="금액">		  
		<input type="text" name="amount" id="amount" value="<?= htmlspecialchars($amount, ENT_QUOTES, 'UTF-8') ?>" onkeyup="inputNumberFormat(this)" class="form-control text-end" style="width:100px;" placeholder="금액" />
	 </td>
	    	 
   </tr>	 
  <tr>
   <td class="text-center" data-label="비고">
	비 고
	 </td>
	 <td colspan="3" data-label="비고">		  
		<input type="text" name="comment" id="comment" value="<?= htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') ?>" class="form-control" placeholder="비고" />
	 </td>
	    	 
   </tr>
	</tbody>
</table>
 
</div> 
</div> 
</div> 
</form>	  

<script>

$(document).ready(function(){
		
   $("div *").find("input,textarea").prop("disabled",true);	
 });		 
	

function del(href) {    
    var user_name  = '<?php echo  $user_name ; ?>' ;
    var writer  = '<?php echo  $writer ; ?>' ;
    var admin  = '<?php echo  $admin ; ?>' ;
	if( user_name !== writer && admin !== '1' )
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
				$.ajax({
					url:'delete.php',
					type:'post',
					data: $("#board_form").serialize(),
					dataType: 'json',
					}).done(function(data){		
						Toastify({
							text: "파일 삭제완료 ",
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
								// window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
								window.opener.location.reload(); // 부모 창 새로고침
								window.close();
							}							
							
						}, 1000);
			
					  
					});
            }
        });
    }
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
