<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/load_GoogleDrive.php'; // 세션 등 여러가지 포함됨 파일 포함	
$title_message = '개발프로젝트 연구노트';   
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/common.php' ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php'; ?>  
<title> <?=$titlemsg?> </title>
</head>
<style>
.show {display:block} /*보여주기*/
.hide {display:none} /*숨기기*/
  input[type="text"] {
    text-align: left !important ;
  }  
  input[type="number"] {
    text-align: left !important ;
  }
 td, th, tr, span, input {
    vertical-align: middle;
  }
</style>	

<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/modal.php"; ?>
   
<?php   

$tablename = 'eworks';  
  
$mode=  $_REQUEST["mode"] ?? '' ;
$num=  $_REQUEST["num"] ?? '' ;
$author=  $user_name ?? '' ;
$indate=date("Y-m-d") ?? '' ;
 
  if ($mode=="modify" or $mode=="view"){
    try{
      $sql = "select * from {$DB}.eworks where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "결과가 없습니다.<br>";
     }else{		 
 		include  $_SERVER['DOCUMENT_ROOT'] . '/eworks/_row.php';	
		// 전자결재의 정보를 다시 변환해 준다.		
		$mytitle = $outworkplace ?? '';
		$content = $al_content ?? '';
		$content_reason = $request_comment ?? '';	

		$titlemsg = $mode === 'modify' ? '프로젝트 개발진행 노트(수정)' : '프로젝트 개발진행 노트(조회)'; 
		
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     } 
  }
  
      
  if ($mode!="modify" and $mode!="view" and $mode!="copy"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
	$indate=date("Y-m-d");
	$author = $user_name;
	$titlemsg = '개발프로젝트 연구노트 작성';
  } 
  
  if ($mode=="copy"){
    try{
      $sql = "select * from {$DB}.eworks where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "결과가 없습니다.<br>";
     }else{
		 include $_SERVER['DOCUMENT_ROOT'] .'/eworks/_row.php';		
		// 전자결재의 정보를 다시 변환해 준다.		
		$mytitle = $outworkplace ?? '';
		$content = $al_content ?? '';
		$content_reason = $request_comment ?? '';	
		$indate=date("Y-m-d");
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 
     $titlemsg	= '(데이터 복사) 개발프로젝트 연구노트';	
	 $num='';	 
	 $id = $num;  
	 $parentid = $num;    
	 $author = $user_name;
	 $update_log='';
  }  
  
// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num;    
require_once $_SERVER['DOCUMENT_ROOT'] . '/load_GoogleDriveSecond.php'; // attached, image에 대한 정보 불러오기  
?>

<form id="board_form" name="board_form" method="post"  onkeydown="return captureReturnKey(event)"  >	
    
	<!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  								
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  									
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  									
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->		
	<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >		
	<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >		
	
<div class="container-fluid" >
<div class="card">
<div class="card-body">			   

<div class="row">
	<div class="col-sm-7">
		<div class="d-flex mb-5 mt-5 justify-content-center align-items-center ">
			<h4> <?=$titlemsg?> 
			</h4> 
		</div>
	</div>
    <div class="col-sm-5">		
	<?php
		//var_dump($al_part);			

		$al_part=='지원파트';
	   if($e_confirm ==='' || $e_confirm === null) 
	   {
			$formattedDate = date("Y-m-d", strtotime($registdate)); // 월/일 형식으로 변환			
			
			if($al_part=='지원파트')
			{
				$approvals = array(
					array("name" => "이사 최장중", "date" =>  $formattedDate),
					array("name" => "대표 소현철", "date" =>  $formattedDate),
					// 더 많은 결재권자가 있을 수 있음...
				);	
			}
	   }	   
	else
		{
			$approver_ids = explode('!', $e_confirm_id);
			$approver_details = explode('!', $e_confirm);

			$approvals = array();
			$exam = array();

			if (empty($store)) {
				$exam['name'] = '이미래 과장';
				$exam['date'] = '';
			} else {
				// 공백을 기준으로 마지막 값을 날짜로 분리
				$parts = explode(' ', $store);
				$exam['date'] = array_pop($parts); // 마지막 요소: '10:04:48'
				$exam['date'] = array_pop($parts) . ' ' . $exam['date']; // 하나 더 꺼내서 날짜와 합치기
				$exam['name'] = implode(' ', $parts); // 나머지는 이름
			}
			
			foreach($approver_ids as $index => $id) {
				if (isset($approver_details[$index])) {

					// 날짜 기준 (예: "2025-02-01")
					$baseDate = $indate;

					// 대상자별 시간대 설정
					if ($index === 0) { // 첫 번째는 연구원
						$hour = 11;					
					} else { // 나머지는 최종결재권자 (14시~17시)
						$hour = rand(14, 17);
					}

					$minute = rand(0, 59);
					$second = rand(0, 59);
					$randomTime = sprintf('%02d:%02d:%02d', $hour, $minute, $second);
					$timestamp = strtotime("$baseDate $randomTime");
					$formattedDate = date("Y-m-d H:i:s", $timestamp);

					// 첫 번째는 연구원 정보 저장
					if (empty($exam['date'])) {						
						 // 첫 번째는 연구원
						$hour = 10;					
						$minute = rand(0, 59);
						$second = rand(0, 59);
						$randomTime = sprintf('%02d:%02d:%02d', $hour, $minute, $second);
						$timestamp = strtotime("$baseDate $randomTime");
						$FirstformattedDate = date("Y-m-d H:i:s", $timestamp);	
						$exam['date'] = $FirstformattedDate;
					}					
					// 첫 번째는 연구원
					// 연구원시간 넣으려면 아래 주석 해제
					
						// $hour = 10;					
						// $minute = rand(0, 59);
						// $second = rand(0, 59);
						// $randomTime = sprintf('%02d:%02d:%02d', $hour, $minute, $second);
						// $timestamp = strtotime("$baseDate $randomTime");
						// $FirstformattedDate = date("Y-m-d H:i:s", $timestamp);	
						// $exam['date'] = $FirstformattedDate;	
						
					// 연구원시간 넣으려면 아래 주석 해제끝.....						

					// 원래 문자열에서 이름 + 직함 추출
					preg_match("/^(.+ \d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})$/", $approver_details[$index], $matches);
					if (count($matches) === 3) {
						$nameWithTitle = $matches[1];
						$date = substr($nameWithTitle, -10); // '2024-02-28'
						$nameWithTitle = trim(str_replace($date, '', $nameWithTitle));
						if (preg_match('/\d{4}-\d{2}-\d{2}/',$nameWithTitle , $secondmatches)) {
								$date = $secondmatches[0]; // 결과: 2025-02-28
							}
						
						// 날짜가 다르면만 처리
						if ($date !== $indate) 
							$approvals[] = array("name" => $nameWithTitle, "date" => $formattedDate);
						else
							$approvals[] = array("name" => $nameWithTitle, "date" => ($date . ' '. $matches[2]));
					}

				}
			}
		}	

    // 검토자 결재정보 생성
	$store =  $exam["name"] . ' ' .  $exam['date'] ;
		
	if($status === 'end' and ($e_confirm !=='' && $e_confirm !== null) )
		{
	?>				
		
	<input type="hidden" id="store" name="store" value="<?=$store?>"  >		  <!--store는 검토자와 날짜시간 기록 -->			
	<input type="hidden" id="firstTime" name="firstTime" value="<?=$approvals[0]["date"]?>" >		
	<input type="hidden" id="secondTime" name="secondTime" value="<?=$approvals[1]["date"]?>" >
	
			<div class="container mb-2">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th colspan="1" class="text-center fs-6">검토</th>
							<th id="setApprovalTime" colspan="<?php echo count($approvals); ?>" class="text-center fs-6">결재 <i class="bi bi-gear"></i></th>
						</tr>
					</thead>
					<tbody>
						<tr>
						    <td class="text-center fs-6" style="height: 60px;"><?php echo $exam["name"]; ?></td>
							<?php foreach ($approvals as $approval) { ?>
								<td class="text-center fs-6" style="height: 60px;"><?php echo $approval["name"]; ?></td>
							<?php } ?>
						</tr>
						<tr>
							<td class="text-center"><?php echo $exam["date"]; ?></td>
							<?php foreach ($approvals as $approval) { ?>
								<td class="text-center"><?php echo $approval["date"]; ?></td>
							<?php } ?>
						</tr>
					</tbody>
				</table>
			</div>			  
		  
		  <?  } 
		 else 
			 {
		   ?>
	<div class="container mb-2">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="<?php echo count($approvals); ?>" class="text-center fs-6">결재 진행 전</th>
				</tr>
			</thead>
			<tbody>
				<tr>								
				</tr>
			</tbody>
		</table>
	</div>	
  <?  }   ?>
	  
 </div> 			
</div> 
 	
	
<?php if($mode!='view') { ?>		
	
	<div class="row">
		<div class="col-sm-9">		   
			<div class="d-flex  mb-1 justify-content-start  align-items-center"> 		   
				<button id="saveBtn" type="button" class="btn btn-dark  btn-sm me-2"  > <i class="bi bi-floppy"></i> 저장(결재상신)  </button> 
			</div> 			
		</div> 	
		<div class="col-sm-3">	
				<div class="d-flex  mb-1 justify-content-end"> 	
				   <button class="btn btn-secondary btn-sm" onclick="self.close();"  > <i class="bi bi-x-lg"></i> 창닫기 </button>&nbsp;					
				</div> 			
		</div> 			
	</div> 
<?php } else {  ?>		
       <div class="row">
		<?php if($chkMobile) { ?>	
		  <div class="col-sm-12">
		<?php } if(!$chkMobile) { ?>	
		  <div class="col-sm-7">
		<?php  } ?>			 		   
			<div class="d-flex  justify-content-start"> 							
				<?php if($chkMobile==true)	{ ?>
					<button class="btn btn-dark btn-sm" onclick="location.href='list.php'" > <i class="bi bi-card-list"></i> 목록 </button>&nbsp;	
				<?php } ?>						
					<button type="button"   class="btn btn-dark btn-sm" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>'" > <i class="bi bi-pencil-square"></i>  수정 </button> &nbsp;
				<?php if($user_id === $author_id || $admin)	{ ?>
						<button type="button"  class="btn btn-danger btn-sm" onclick="javascript:deleteFn('delete.php?num=<?=$num?>&page=<?=$page?>')" ><i class="bi bi-trash"></i>  삭제 </button>	 &nbsp;
				<?php } ?>									
					<button type="button"   class="btn btn-dark btn-sm" onclick="location.href='write_form.php'" > <i class="bi bi-pencil"></i>  신규 </button>		&nbsp;										
					<button type="button"   class="btn btn-primary btn-sm" onclick="location.href='write_form.php?mode=copy&num=<?=$num?>'" > <i class="bi bi-copy"></i> 복사 </button>	&nbsp;							
			 </div> 			
		 </div> 			
		<?php if($chkMobile) { ?>	
		  <div class="col-sm-12">
		<?php } if(!$chkMobile) { ?>	
		  <div class="col-sm-5 text-end">
		<?php  } ?>	
				<div class="d-flex  mb-1 justify-content-end"> 	
					<button class="btn btn-secondary btn-sm" type="button" onclick="self.close();" >  &times; 창닫기 </button>&nbsp;									
				</div> 					 
			</div> 
	 </div> <!-- end of row -->	
<?php } // end of elseif  ?>	

<div class="row mt-2">  
  <table class="table table-bordered">
    <tr>
      <td class="text-center w-10 fw-bold"><label for="indate">작성일</label></td>          			
      <td class="w-10">
        <input type="date" class="form-control w-100 viewNoBtn" id="indate" name="indate" value="<?=$indate?>" >				
      </td>	
      <td class="text-center w-10 fw-bold"><label for="author">작성자</label></td>          			
      <td class="w-10">
        <input type="text" class="form-control text-center w-100 viewNoBtn" id="author" name="author" value="<?=$author?>" >				
      </td>
      <td class="text-center w-10 fw-bold"><label for="mytitle">제목</label></td>
      <td class="w-30">
        <input type="text" class="form-control viewNoBtn" id="mytitle" name="mytitle" value="<?=$mytitle?>" placeholder="제목" >
      </td>
    </tr>
  </table>
</div>

<div class="row d-flex justify-content-center mt-2">    
	 <textarea id="summernote" name="content" rows="18" required><?=$content?></textarea>      
</div>
 
   <div class="d-flex mb-3 justify-content-center  align-items-center"> 		   
		<label for="upfile" class="btn btn-outline-primary btn-sm mx-2 viewNoSpan">  파일 첨부  </label>
		<input id="upfile" name="upfile[]" type="file" multiple style="display:none">
		<div id="displayFile" class="mt-4"></div>
	</div>
	
	 </div>	  
		</div>	  
		</div>	  
		</div>	  
 </div>	  
</form>	
<script>
$(document).ready(function() {
  // Summernote 초기화
  $('#summernote').summernote({
    placeholder: '내용',
    maximumImageFileSize: 1920 * 5000,
    tabsize: 2,
    height: 450,
    width: 1300,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ],
    callbacks: {
      onImageUpload: function(files) {
        if (files.length > 0) {
          var file = files[0];
          resizeImage(file, function(resizedImage) {
            $('#summernote').summernote('insertImage', resizedImage);
          });
        }
      }
    }
  });

  // 뷰 모드일 경우 비활성화
  var mode = '<?php echo $mode; ?>';
  if (mode === 'view') {
    $('#summernote').summernote('disable');
  }

  // 이미지 리사이징 함수
  function resizeImage(file, callback) {
    var reader = new FileReader();
    reader.onloadend = function(e) {
      var tempImg = new Image();
      tempImg.src = reader.result;
      tempImg.onload = function() {
        var MAX_WIDTH = 800;
        var MAX_HEIGHT = 500;
        var tempW = tempImg.width;
        var tempH = tempImg.height;

        if (tempW > tempH) {
          if (tempW > MAX_WIDTH) {
            tempH *= MAX_WIDTH / tempW;
            tempW = MAX_WIDTH;
          }
        } else {
          if (tempH > MAX_HEIGHT) {
            tempW *= MAX_HEIGHT / tempH;
            tempH = MAX_HEIGHT;
          }
        }

        var canvas = document.createElement('canvas');
        canvas.width = tempW;
        canvas.height = tempH;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(tempImg, 0, 0, tempW, tempH);
        var dataURL = canvas.toDataURL("image/jpeg");

        callback(dataURL);
      };
    };
    reader.readAsDataURL(file);
  }
  
  // Summernote의 내용에서 (2024.03.30 ~ 2024.04.05)와 같은 형식을 찾아서, 그 중 종료일인 2024.04.05를 <input type="date" ... id="indate"> 요소의 value로 자동 입력하는 것입니다. 이를 구현하려면 .summernote('change') 이벤트를 활용할 수 있습니다.
  // Summernote의 .summernote('code')나 summernote.change 이벤트에서 contents로 가져온 HTML에는 <span>, <p>, <li>, <ul> 등 많은 HTML 태그가 포함됩니다. 따라서 원하는 텍스트만 추출하려면 HTML 태그 제거가 필요합니다. 이를 위해 jQuery 또는 DOM API를 이용해 innerText로 변환하거나 text()를 추출하는 방법을 쓰면 됩니다.
	$('#summernote').on('summernote.change', function(we, contents, $editable) {
	  // HTML을 제거하고 텍스트만 추출
	  var tempDiv = document.createElement("div");
	  tempDiv.innerHTML = contents;
	  var plainText = tempDiv.innerText || tempDiv.textContent || "";

	  // 1. 날짜 범위 추출 후 indate 설정
	  var dateMatch = plainText.match(/\(\d{4}\.\d{2}\.\d{2} ~ (\d{4}\.\d{2}\.\d{2})\)/);
	  if (dateMatch && dateMatch[1]) {
		var formattedDate = dateMatch[1].replace(/\./g, '-');
		$('#indate').val(formattedDate);
	  }

	  // 2. 주요 연구개발 내용 추출 후 mytitle 설정
	  var titleMatch = plainText.match(/주요 연구개발 내용:\s*([^:\n]+)/);
	  if (titleMatch && titleMatch[1]) {
		var title = titleMatch[1].trim();
		$('#mytitle').val('[본천장 자동작도] ' + title);
	  }
	});


  
});
</script>


<script>
$(document).ready(function(){		
	 $("#saveBtn").click(function(){ 
		// 조건 확인
		if($("#mytitle").val() === '' || $("#content").val() === ''  ) {
			showWarningModal();
		} else {
		   showMsgModal(2); // 파일저장중
			Toastify({
				text: "변경사항 저장중...",
				duration: 2000,
				close:true,
				gravity:"top",
				position: "center",
				style: {
					background: "linear-gradient(to right, #00b09b, #96c93d)"
				},
			}).showToast();	
			setTimeout(function(){
					 saveData();
			}, 1000);
		  
		}
	});
	
	
	// 강제 결재시간 세팅 동작	
	$("#setApprovalTime").click(function() {
	    showMsgModal(2); // 파일저장중
			Toastify({
				text: "결재시간 조정중 ..",
				duration: 2000,
				close:true,
				gravity:"top",
				position: "center",
				style: {
					background: "linear-gradient(to right, #00b09b, #96c93d)"
				},
			}).showToast();	
			setTimeout(function(){
					 updateData();
			}, 1000);
	});		

	function showWarningModal() {
		Swal.fire({                                    
			title: '등록 오류 알림',
			text: '제목, 내용, 사유는 필수입력 요소입니다.',
			icon: 'warning',
			// ... 기타 설정 ...
		}).then(result => {
			if (result.isConfirmed) { 
				return; // 사용자가 확인 버튼을 누르면 아무것도 하지 않고 종료
			}         
		});
	}

	function saveData() {		
		var num = $("#num").val();  		
		// 결재상신이 아닌경우 수정안됨     
		if(Number(num) < 1) 				
				$("#mode").val('insert');     			  						
		//  console.log($("#mode").val());    
		// 폼데이터 전송시 사용함 Get form         
		var form = $('#board_form')[0];  	    	
		var datasource = new FormData(form); 

		// console.log(data);
		if (ajaxRequest !== null) {
			ajaxRequest.abort();
		}		 
		ajaxRequest = $.ajax({
			enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 600000, 			
			url: "insert.php",
			type: "post",		 
			data: datasource,			
			dataType: "json", 
			success : function(data){
				  console.log('data :' , data);
				  Swal.fire(
					  '자료등록 완료',
					  '데이터가 성공적으로 등록되었습니다.',
					  'success'
					);
				setTimeout(function(){									
					if (window.opener && !window.opener.closed) {
						// 부모 창에 restorePageNumber 함수가 있는지 확인
						if (typeof window.opener.restorePageNumber === 'function') {
							window.opener.restorePageNumber(); // 함수가 있으면 실행
						}								
					}
				setTimeout(function(){		
					hideMsgModal();	
					// location.href = "view.php?num=" + data["num"];
					self.close();
				}, 1000);	
							
				}, 1000);						
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });					
	}	

	function updateData() {		
		var num = $("#num").val();  				
		var form = $('#board_form')[0];  	    	
		var datasource = new FormData(form); 

		// console.log(data);
		if (ajaxRequest !== null) {
			ajaxRequest.abort();
		}		 
		ajaxRequest = $.ajax({
			enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 600000, 			
			url: "/ask_rndreport/approval_update.php",
			type: "post",		 
			data: datasource,			
			dataType: "json", 
			success : function(data){
				
				hideMsgModal();
				window.close();										
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });					
	}		
	
});

// 파일삭제
function deleteFn(href) {    
	// 삭제 확인
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
				url: 'delete.php',
				type: 'post',
				data: $("#board_form").serialize(),
				dataType: 'json',
			}).done(function(data) {
				// 삭제 후 처리
				Toastify({
					text: "파일 삭제완료 ",
					duration: 2000,
					close: true,
					gravity: "top",
					position: "center",
					style: {
						background: "linear-gradient(to right, #00b09b, #96c93d)"
					},
				}).showToast();
				setTimeout(function() {
					if (window.opener && !window.opener.closed) {
						window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
						window.opener.location.reload(); // 부모 창 새로고침
					}
					setTimeout(function() {  window.close(); }, 500);
					
				}, 1000);
			});
		}
	});
}
	 
function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}
</script> 

<script>
$(document).ready(function() {
    displayFileLoad();

    $('#upfile').change(function () {
        const form = $('#board_form')[0];
        const data = new FormData(form);
        data.append("tablename", $('#tablename').val());
        data.append("item", "attached");
        data.append("upfilename", "upfile");
        data.append("folderPath", "미래기업/uploads");
        data.append("DBtable", "picuploads");

        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "/filedrive/fileprocess.php",
            type: "POST",
            data: data,
            success: function () {
                displayFile();
            }
        });
    });
});

// 화면에서 저장한 첨부된 파일 불러오기
function displayFile() {
    $('#displayFile').show();
    const params = $("#timekey").val() ? $("#timekey").val() : $("#num").val();

    if (!params) {
        console.error("ID 값이 없습니다. 파일을 불러올 수 없습니다.");
        alert("ID 값이 유효하지 않습니다. 다시 시도해주세요.");
        return;
    }

    console.log("요청 ID:", params); // 요청 전 ID 확인

    $.ajax({
        url: '/filedrive/fileprocess.php',
        type: 'GET',
        data: {
            num: params,
			tablename: $("#tablename").val(),
            item: 'attached',
            folderPath: '미래기업/uploads',
        },
        dataType: 'json',
    }).done(function (data) {
        console.log("파일 데이터:", data);

        $("#displayFile").html(''); // 기존 내용 초기화

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function (fileData, index) {
                const realName = fileData.realname || '다운로드 파일';
                const link = fileData.link || '#';
                const fileId = fileData.fileId || null;

                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + index, fileData);
                    $("#displayFile").append(
                        "<div class='text-danger'>파일 ID가 누락되었습니다.</div>"
                    );
                    return;
                }

				$("#displayFile").append(
					"<div class='row mt-1 mb-2'>" +
						"<div class='d-flex align-items-center justify-content-center'>" +
							"<span id='file" + index + "'>" +
								"<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" + realName + "</a>" +
							"</span> &nbsp;&nbsp;" +
							"<button type='button' class='btn btn-danger btn-sm' id='delFile" + index + "' onclick=\"delFileFn('" + index + "', '" + fileId + "')\">" +
								"<i class='bi bi-trash'></i>" +
							"</button>" +
						"</div>" +
					"</div>"
				);


            });
        } else {
            $("#displayFile").append(
                "<div class='text-center text-muted'>No files</div>"
            );
        }
    }).fail(function (error) {
        console.error("파일 불러오기 오류:", error);
        Swal.fire({
            title: "파일 불러오기 실패",
            text: "파일을 불러오는 중 문제가 발생했습니다.",
            icon: "error",
            confirmButtonText: "확인",
        });
    });
}

// 기존 파일 불러오기 (Google Drive에서 가져오기)
function displayFileLoad() {
    $('#displayFile').show();
    var data = <?php echo json_encode($savefilename_arr); ?>;

    $("#displayFile").html(''); // 기존 내용 초기화

    if (Array.isArray(data) && data.length > 0) {
        data.forEach(function (fileData, i) {
            const realName = fileData.realname || '다운로드 파일';
            const link = fileData.link || '#';
            const fileId = fileData.fileId || null;

            if (!fileId) {
                console.error("fileId가 누락되었습니다. index: " + i, fileData);
                return;
            }

			$("#displayFile").append(
				"<div class='row mb-3'>" +
					"<div class='d-flex mb-3 align-items-center justify-content-center'>" +
						"<span id='file" + i + "'>" +
							"<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" + realName + "</a>" +
						"</span> &nbsp;&nbsp;" +
						"<button type='button' class='btn btn-danger btn-sm' id='delFile" + i + "' onclick=\"delFileFn('" + i + "', '" + fileId + "')\">" +
							"<i class='bi bi-trash'></i>" +
						"</button>" +
					"</div>" +
				"</div>"
			);

        });
    } else {
        $("#displayFile").append(
            "<div class='text-center text-muted'>No files</div>"
        );
    }
}

// 파일 삭제 처리 함수
function delFileFn(divID, fileId) {
    Swal.fire({
        title: "파일 삭제 확인",
        text: "정말 삭제하시겠습니까?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "삭제",
        cancelButtonText: "취소",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/filedrive/fileprocess.php',
                type: 'DELETE',
                data: JSON.stringify({
                    fileId: fileId,
                    tablename: $("#tablename").val(),
                    item: "attached",
                    folderPath: "미래기업/uploads",
                    DBtable: "picuploads",
                }),
                contentType: "application/json",
                dataType: 'json',
            }).done(function (response) {
                if (response.status === 'success') {
                    console.log("삭제 완료:", response);
                    $("#file" + divID).remove();
                    $("#delFile" + divID).remove();

                    Swal.fire({
                        title: "삭제 완료",
                        text: "파일이 성공적으로 삭제되었습니다.",
                        icon: "success",
                        confirmButtonText: "확인",
                    });
                } else {
                    console.log(response.message);
                }
            }).fail(function (error) {
                console.error("삭제 중 오류:", error);
                Swal.fire({
                    title: "삭제 실패",
                    text: "파일 삭제 중 문제가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인",
                });
            });
        }
    });
}

</script>

<script>
$(document).ready(function () {
	// 모드가 'view'인 경우 disable 처리 (기존 코드 유지)
	var mode = '<?php echo $mode; ?>';
	if (mode === 'view') {
		disableView(); 
	}

	function disableView() {
			$('input, textarea ').prop('readonly', true); // Disable all input, textarea, and select elements
			$('input[type=hidden]').prop('readonly', false); 

			// checkbox와 radio는 클릭 불가능하게 하고 시각적 강조
			$('input[type="checkbox"], input[type="radio"]').each(function() {
				$(this).addClass('readonly-checkbox readonly-radio');
			});

			// 파일 입력 비활성화 
			$('input[type=file]').prop('disabled', true); 
			$('.viewNoBtn').prop('disabled', true);  //버튼 비활성화
			$('.searchplace').prop('disabled', true);  // 수신자 버튼 비활성화
			$('.searchsecondord').prop('disabled', true);  // 수신자 버튼 비활성화
			
			// 레이블 텍스트 크게 설정
			$('label').css('font-size', '1em');
			$('.viewNoSpan').css('display', 'none');
			
			// select 속성 readonly 효과 내기
			$('select[data-readonly="true"]').on('mousedown', function(event) {
				event.preventDefault();
			});

			// checkbox 속성 readonly 효과 내기
			$('input[type="checkbox"][data-readonly="true"]').on('click', function(event) {
				event.preventDefault();
			});

	}
});

function formatInput(input) {
    let value = input.value;
    value = value.replace(/,/g, ""); // Remove all existing commas
    value = value.replace(/[^\d]/g, ""); // Remove all non-digit characters
    input.value = numberWithCommas(value); // Add commas and update the value
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
</script>

</body>
</html>