<?php
/**
 * 작업표준서 작성/수정 폼 페이지
 * 작업표준서 게시글을 작성하거나 수정하는 폼을 표시
 */
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/load_GoogleDrive.php'; // 세션 등 여러가지 포함됨 파일 포함

$title_message = '작업표준서';
?>

<?php include getDocumentRoot() . '/load_header.php' ?>


<title> <?=$title_message?> </title>

<style>
/* 모바일 환경 최적화 */
@media (max-width: 768px) {
    /* 컨테이너 최적화 */
    .container,
    .container-fluid {
        padding: 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 최적화 */
    .card {
        margin: 0.5rem auto !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 제목 최적화 */
    .fs-5 {
        font-size: 1.125rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        text-align: center !important;
    }
    
    /* 입력 필드 최적화 */
    input[type="text"],
    textarea,
    select.form-control {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        font-size: 1rem !important;
        padding: 0.5rem !important;
        margin: 0.25rem 0 !important;
    }
    
    /* d-flex 요소 최적화 */
    .d-flex {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
        flex-wrap: wrap !important;
    }
    
    .d-flex.justify-content-center,
    .d-flex.justify-content-start,
    .d-flex.justify-content-left {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모든 텍스트 요소 강제 줄바꿈 */
    p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        display: inline !important;
        overflow: visible !important;
    }
    
    /* label 최적화 */
    label {
        display: block !important;
        margin-bottom: 0.25rem !important;
        width: 100% !important;
    }
    
    /* Summernote 에디터 최적화 */
    .note-editor {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .note-editable {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    /* 이미지 최적화 */
    img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        object-fit: contain !important;
    }
    
    #displayImage img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
    }
    
    /* 파일 링크 최적화 */
    #displayFile a {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        display: block !important;
        padding: 0.5rem !important;
    }
    
    /* 파일/이미지 표시 영역 최적화 */
    #displayFile,
    #displayImage {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    #displayFile .row,
    #displayImage .row {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.5rem 0 !important;
    }
    
    #displayFile .d-flex,
    #displayImage .d-flex {
        flex-direction: column !important;
        align-items: center !important;
        gap: 0.5rem !important;
    }
    
    /* 파일 입력 최적화 */
    input[type="file"] {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
    }
    
    /* 모달 최적화 */
    .modal {
        padding: 0 !important;
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
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        overflow-y: auto !important;
        flex: 1 1 auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    
    .modal-body img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    /* SweetAlert2 모달 최적화 */
    .swal2-popup {
        width: 90% !important;
        max-width: 90% !important;
        padding: 1rem !important;
        font-size: 0.875rem !important;
    }
    
    .swal2-title {
        font-size: 1.125rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-content {
        font-size: 0.875rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-actions {
        flex-direction: column !important;
        gap: 0.5rem !important;
    }
    
    .swal2-confirm,
    .swal2-cancel {
        width: 100% !important;
        margin: 0 !important;
    }
    
    /* '기간' 버튼 숨기기 */
    #showdate {
        display: none !important;
    }
    
    /* row 최적화 */
    .row {
        margin: 0 !important;
    }
    
    .col {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
}

/* PC 환경 버튼 간격 최적화 */
@media (min-width: 769px) {
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-start .btn,
    .d-flex.justify-content-left .btn {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }
}
</style>

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

// 요청 변수 안전하게 초기화
$id = $_REQUEST["id"] ?? '';
$num = $_REQUEST["num"] ?? '';
$fileorimage = $_REQUEST["fileorimage"] ?? '';  // file or image
$item = $_REQUEST["item"] ?? '';
$upfilename = $_REQUEST["upfilename"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$savetitle = $_REQUEST["savetitle"] ?? '';   // log기록 저장 타이틀
$mode = $_REQUEST["mode"] ?? '';   //수정 버튼을 클릭해서 호출했는지 체크

// 초기화
$item_subject = '';
$is_html = '';
$item_content = '';
$qnacheck = '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

if ($mode=="modify"){
	try{
		$sql = "select * from mirae8440." . $tablename . " where num = ? ";
		$stmh = $pdo->prepare($sql);

		$stmh->bindValue(1, $num, PDO::PARAM_STR);
		$stmh->execute();
		$count = $stmh->rowCount();
		if($count<1){
			print "검색결과가 없습니다.<br>";
		}else{
			$row = $stmh->fetch(PDO::FETCH_ASSOC);
			$item_subject = $row["subject"] ?? '';
			$is_html = $row["is_html"] ?? '';
			$item_content = $row["content"] ?? '';
			$qnacheck = $row["qnacheck"] ?? '';
		}
	}catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}
}


// 초기 프로그램은 $num사용 이후 $id로 수정중임
$id=$num;
require_once getDocumentRoot() . '/load_GoogleDriveSecond.php'; // attached, image에 대한 정보 불러오기
?>

<form  id="board_form" name="board_form" method="post" enctype="multipart/form-data">
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >
	<input type="hidden" id="id" name="id" value="<?=$id?>" >
	<input type="hidden" id="num" name="num" value="<?=$num?>" >
	<input type="hidden" id="item" name="item" value="<?=$item?>" >
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey ?? ''?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->
	<input type="hidden" id="searchtext" name="searchtext" value="<?=$searchtext ?? ''?>" >  <!-- summernote text저장 -->


<div class="container">
	<div class="d-flex mt-3 mb-1 justify-content-center align-items-center">
			<span class="fs-5" > &nbsp;&nbsp;  <?=$title_message?> &nbsp;&nbsp;</span>
	</div>
	<div class="d-flex mt-2 mb-1 justify-content-center align-items-center">
		<div class="card mt-2">
			<div class="card-body">
				 <div class="row">
						<div class="d-flex justify-content-center align-items-center">
							작성자  : &nbsp;    <?=$_SESSION["nick"] ?? ''?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						<div class="d-flex  mt-2 justify-content-center align-items-center">
							<span class="form-control me-2" style="width: 50px;border:0px;" > 제목 </span>
							<input id="subject" name="subject" type="text" required class="form-control" style="width:500px;"  autocomplete="off"  <?php if($mode=="modify"){ ?> value="<?=$item_subject?>" <?php }?>>&nbsp;
						</div>
				</div>
				</div>
			</div>
		</div>
	<div class="d-flex mt-1 mb-1 justify-content-start align-items-center">
		<button class="btn btn-dark btn-sm me-1" onclick="self.close();" > &times; 닫기</button>
		<button type="button"   class="btn btn-dark btn-sm" id="saveBtn"  >   <i class="bi bi-floppy-fill"></i> 저장  </button>

	</div>
	 <div class="d-flex mt-3 mb-1 justify-content-center">
	 <textarea id="summernote" name="content" rows="20" ><?=$item_content?></textarea>
	</div>

	<div class="d-flex mt-3 mb-1 justify-content-center">
			 <label for="upfile" class="input-group-text btn btn-outline-primary btn-sm"> 파일(10M 이하) pdf파일 첨부 </label>
			 <input id="upfile"  name="upfile[]" type="file" onchange="this.value" multiple  style="display:none" >
	</div>

	<div id ="displayFile" class="d-flex mt-1 mb-1 justify-content-center" style="display:none;">

	</div>
	<div id ="displayImage" class="row d-flex mt-1 mb-1 justify-content-center" style="display:none;">

	</div>

	<div class="d-flex mt-1 mb-1 justify-content-center">
			<label  for="upfileimage" class="input-group-text btn btn-outline-dark btn-sm ">  사진 첨부 </label>
			 <input id="upfileimage"  name="upfileimage[]" type="file" onchange="this.value" multiple accept=".gif, .jpg, .png" style="display:none">
	</div>

	</div>
</form>

<script>
$(document).ready(function(){
    // 모바일 환경에서 '기간' 버튼 숨기기
    if (window.innerWidth <= 768) {
        $('#showdate').hide();
    }
    
    // 창 크기 변경 시 '기간' 버튼 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('#showdate').hide();
        } else {
            $('#showdate').show();
        }
    });
    

      // 모바일 환경 체크
      var isMobile = window.innerWidth <= 768;
      var editorHeight = isMobile ? 300 : 500;
      var editorWidth = isMobile ? '100%' : 1200;
      
      $('#summernote').summernote({
        placeholder: '내용 작성',
		// maximumImageFileSize: 500*1024, // 500 KB
		maximumImageFileSize: 1920*5000,
        tabsize: 2,
        height: editorHeight,
        width: editorWidth,
        toolbar: isMobile ? [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'picture']],
          ['view', ['codeview', 'help']]
        ] : [
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
                    // resizedImage는 처리된 이미지의 데이터 URL입니다.
                    $('#summernote').summernote('insertImage', resizedImage);
                        });
                    }
                }
            }
      });
      
      // 창 크기 변경 시 Summernote 크기 조정
      $(window).on('resize', function() {
          var isMobile = window.innerWidth <= 768;
          var editorHeight = isMobile ? 300 : 500;
          var editorWidth = isMobile ? '100%' : 1200;
          
          $('#summernote').summernote('destroy');
          $('#summernote').summernote({
              placeholder: '내용 작성',
              maximumImageFileSize: 1920*5000,
              tabsize: 2,
              height: editorHeight,
              width: editorWidth,
              toolbar: isMobile ? [
                  ['style', ['style']],
                  ['font', ['bold', 'underline', 'clear']],
                  ['color', ['color']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['insert', ['link', 'picture']],
                  ['view', ['codeview', 'help']]
              ] : [
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
      });


$("#closeModalBtn").click(function(){
    $('#myModal').modal('hide');
});

// 하단복사 버튼
$("#closeBtn1").click(function(){
   $("#closeBtn").click();
})

$("#closeBtn").click(function(){    // 저장하고 창닫기
	self.close();
});


$("#saveBtn").click(function(){
    Fninsert();
});

// 자료의 삽입/수정하는 모듈
function Fninsert() {

	console.log($("#mode").val());

	// Summernote 초기화 후
	let content = $('#summernote').summernote('code'); // 에디터의 내용을 HTML 형태로 가져옵니다.

	// HTML 문자열을 DOM 요소로 변환
	let tempDiv = document.createElement('div');
	tempDiv.innerHTML = content;

	// 이제 tempDiv 내부에서 원하는 태그를 선택할 수 있습니다.
	let elements = tempDiv.querySelectorAll('p, b');

	let extractedTexts = [];
	elements.forEach(element => {
		extractedTexts.push(element.textContent);
	});

	console.log(extractedTexts.join(','));

    var extractedText = extractedTexts.join(',');

	console.log('extractedTexts');
	console.log(extractedTexts);
	$("#searchtext").val(extractedText);

    var form = $('#board_form')[0];
    var data = new FormData(form);


    showMsgModal(2); // 파일저장중
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
				// console.log(data);
				setTimeout(function() {
						Toastify({
							text: "파일 저장완료 ",
							duration: 1500,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();

						setTimeout(function(){
							if (window.opener && !window.opener.closed) {
								// 작업 완료 후 모달 닫기
								opener.location.reload();
							}
						}, 1000);

						var num = data["num"];
						var tablename = data["tablename"];

						setTimeout(function(){
							hideMsgModal();
							location.href='view.php?page=1&num=' + num + "&tablename=" + tablename ;
						}, 1000);

					}, 1000);

		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
					}
	   });
	}
}); // end of ready document



function deleteLastchar(str)
// 마지막 문자 제거하는 함수
{
  return str = str.substr(0, str.length - 1);
}

</script>

<script>
$(document).ready(function () {
	displayFileLoad();	// 기존파일 업로드 보이기
	displayImageLoad();	// 기존이미지 업로드 보이기

    // 첨부파일 업로드 처리
    $("#upfile").change(function (e) {
		if (this.files.length === 0) {
			// 파일이 선택되지 않았을 때
			console.warn("파일이 선택되지 않았습니다.");
			return;
		}

        const form = $('#board_form')[0];
        const data = new FormData(form);

        // 추가 데이터 설정
        data.append("tablename", $("#tablename").val() );
        data.append("item", "attached");
        data.append("upfilename", "upfile"); // upfile 파일 name
        data.append("folderPath", "미래기업/uploads");
		data.append("DBtable", "picuploads");

		showMsgModal(2); // 파일저장중

        // AJAX 요청 (Google Drive API)
        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "/filedrive/fileprocess.php",
            type: "POST",
            data: data,
            success: function (response) {
                 console.log("응답 데이터:", response);

                let successCount = 0;
                let errorCount = 0;
                let errorMessages = [];

                response.forEach((item) => {
                    if (item.status === "success") {
                        successCount++;
                    } else if (item.status === "error") {
                        errorCount++;
                        errorMessages.push(`파일: ${item.file}, 메시지: ${item.message}`);
                    }
                });

                if (successCount > 0) {
                    Toastify({
                        text: `${successCount}개의 파일이 성공적으로 업로드되었습니다.`,
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                }

                if (errorCount > 0) {
                    Toastify({
                        text: `오류 발생: ${errorCount}개의 파일 업로드 실패\n상세 오류: ${errorMessages.join("\n")}`,
                        duration: 5000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#f44336",
                    }).showToast();
                }

                setTimeout(function () {
					displayFile();
					hideMsgModal();
                }, 1000);

            },
            error: function (jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
            },
        });
    });

    // 첨부 이미지 업로드 처리
    $("#upfileimage").change(function (e) {
		if (this.files.length === 0) {
			// 파일이 선택되지 않았을 때
			console.warn("파일이 선택되지 않았습니다.");
			return;
		}

        const form = $('#board_form')[0];
        const data = new FormData(form);

        // 추가 데이터 설정
        data.append("tablename", $("#tablename").val() );
        data.append("item", "image");
        data.append("upfilename", "upfileimage"); // upfile 파일 name
        data.append("folderPath", "미래기업/uploads");
        data.append("DBtable", "picuploads");

		showMsgModal(1); // 이미지저장중

        // AJAX 요청 (Google Drive API)
        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "/filedrive/fileprocess.php",
            type: "POST",
            data: data,
            success: function (response) {
                console.log("응답 데이터:", response);

                let successCount = 0;
                let errorCount = 0;
                let errorMessages = [];

                response.forEach((item) => {
                    if (item.status === "success") {
                        successCount++;
                    } else if (item.status === "error") {
                        errorCount++;
                        errorMessages.push(`파일: ${item.file}, 메시지: ${item.message}`);
                    }
                });

                if (successCount > 0) {
                    Toastify({
                        text: `${successCount}개의 파일이 성공적으로 업로드되었습니다.`,
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                }

                if (errorCount > 0) {
                    Toastify({
                        text: `오류 발생: ${errorCount}개의 파일 업로드 실패\n상세 오류: ${errorMessages.join("\n")}`,
                        duration: 5000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#f44336",
                    }).showToast();
                }

                setTimeout(function () {
					displayImage();
					hideMsgModal();
                }, 1000);

            },
            error: function (jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
            },
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
								"<ion-icon name='trash-outline'></ion-icon>" +
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
							"<ion-icon name='trash-outline'></ion-icon>" +
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

// 첨부된 이미지 불러오기
function displayImage() {
    $('#displayImage').show();
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
            item: 'image',
            folderPath: '미래기업/uploads',
        },
        dataType: 'json',
    }).done(function (data) {
        console.log("파일 데이터:", data);

        $("#displayImage").html(''); // 기존 내용 초기화

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function (fileData, index) {
                const realName = fileData.realname || '다운로드 파일';
                const thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
                const link = fileData.link || '#';
                const fileId = fileData.fileId || null;

                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + index, fileData);
                    $("#displayImage").append(
                        "<div class='text-danger'>파일 ID가 누락되었습니다.</div>"
                    );
                    return;
                }

				$("#displayImage").append(
					"<div class='row mb-3'>" +
						"<div class='col d-flex align-items-center justify-content-center'>" +
							"<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
								"<img id='image" + index + "' src='" + thumbnail + "' style='width:100%; max-width:100%; height:auto;'>" +
							"</a> &nbsp;&nbsp;" +
							"<button type='button' class='btn btn-danger btn-sm' id='delImage" + index + "' onclick=\"delImageFn('" + index + "', '" + fileId + "')\">" +
								"<ion-icon name='trash-outline'></ion-icon>" +
							"</button>" +
						"</div>" +
					"</div>"
				);

            });
        } else {
            $("#displayImage").append(
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

// 기존 이미지 불러오기 (Google Drive에서 가져오기)
function displayImageLoad() {
    $('#displayImage').show();
    var data = <?php echo json_encode($saveimagename_arr); ?>;

    $("#displayImage").html(''); // 기존 내용 초기화

    if (Array.isArray(data) && data.length > 0) {
        data.forEach(function (fileData, i) {
            const realName = fileData.realname || '다운로드 파일';
            const thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
            const link = fileData.link || '#';
            const fileId = fileData.fileId || null;

            if (!fileId) {
                console.error("fileId가 누락되었습니다. index: " + i, fileData);
                return;
            }

			$("#displayImage").append(
				"<div class='row mb-3'>" +
					"<div class='col d-flex align-items-center justify-content-center'>" +
						"<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
							"<img id='image" + i + "' src='" + thumbnail + "' style='width:100%; max-width:100%; height:auto;'>" +
						"</a> &nbsp;&nbsp;" +
						"<button type='button' class='btn btn-danger btn-sm' id='delImage" + i + "' onclick=\"delImageFn('" + i + "', '" + fileId + "')\">" +
							"<ion-icon name='trash-outline'></ion-icon>" +
						"</button>" +
					"</div>" +
				"</div>"
			);

        });
    } else {
        $("#displayImage").append(
            "<div class='text-center text-muted'>No files</div>"
        );
    }
}

// 이미지 삭제 처리 함수
function delImageFn(divID, fileId) {
    Swal.fire({
        title: "이미지 삭제 확인",
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
                    item: "image",
                    folderPath: "미래기업/uploads",
                    DBtable: "picuploads",
                }),
                contentType: "application/json",
                dataType: 'json',
            }).done(function (response) {
                if (response.status === 'success') {
                    console.log("삭제 완료:", response);
                    $("#image" + divID).remove();
                    $("#delImage" + divID).remove();

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

</body>
</html>
