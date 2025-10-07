<?php
require_once getDocumentRoot() . '/load_GoogleDrive.php'; // 세션 등 여러가지 포함됨 파일 포함

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}   
include getDocumentRoot() . '/load_header.php';    
// 첫 화면 표시 문구
 $title_message = '개발진행현황'; 
 ?>
<title> <?=$title_message?> </title>  
</head>
<body>
<?php include getDocumentRoot() . '/common/modal.php'; ?>

<?php

isset($_REQUEST["id"])  ? $id=$_REQUEST["id"] :   $id=''; 
isset($_REQUEST["fileorimage"])  ? $fileorimage=$_REQUEST["fileorimage"] :   $fileorimage=''; // file or image
isset($_REQUEST["item"])  ? $item=$_REQUEST["item"] :   $item=''; 
isset($_REQUEST["upfilename"])  ? $upfilename=$_REQUEST["upfilename"] :   $upfilename=''; 
isset($_REQUEST["tablename"])  ? $tablename=$_REQUEST["tablename"] :  $tablename=''; 
isset($_REQUEST["savetitle"])  ? $savetitle=$_REQUEST["savetitle"] :  $savetitle='';   // log기록 저장 타이틀

    
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";
          
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  if ($mode=="modify"){
    try{
      $sql = "select * from ".$DB."." . $tablename . " where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
      $item_subject = $row["subject"];
      $is_html = $row["is_html"];
      $content = $row["content"];
      $qnacheck = $row["qnacheck"];
      $division = $row["division"];
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
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->				
	<input type="hidden" id="searchtext" name="searchtext" value="<?=$searchtext?>" >  <!-- summernote text저장 -->		
	
<div class="container">  
	<div class="d-flex mt-3 mb-1 justify-content-center align-items-center"> 
			<span class="fs-5" > &nbsp;&nbsp;  <?=$title_message?> &nbsp;&nbsp;</span>	
	</div>	      
	<div class="d-flex mt-2 mb-1 justify-content-center align-items-center"> 		
		<div class="card mt-2 mb-2" style="width:80%;">  
			<div class="card-body">  					
				 <div class="row"> 					 
						<div class="d-flex justify-content-center align-items-center"> 							 
							작성자  : &nbsp;    <?=$_SESSION["nick"]?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
						</div>					
						<div class="d-flex  mt-2 justify-content-center align-items-center"> 							 
							<span class="form-control me-2" style="width: 50px;border:0px;" > 구분 </span>
							<input id="division" name="division" type="text" class="form-control me-2" style="width:300px;"  autocomplete="off" <?php if($mode=="modify"){ ?> value="<?=$division?>" <?php }?>>&nbsp;														
							<span class="form-control me-2" style="width: 50px;border:0px;" > 제목 </span>
							<input id="subject" name="subject" type="text" required class="form-control" style="width:600px;"  autocomplete="off" <?php if($mode=="modify"){ ?> value="<?=$item_subject?>" <?php }?>>&nbsp;														
						</div>						
				</div>
				</div>			
			</div>
		</div>
	<div class="d-flex mt-1 mb-1 justify-content-start align-items-center"> 					 
		<button class="btn btn-dark btn-sm me-1" onclick="self.close();" > &times; 닫기 </button>
		<button type="button"   class="btn btn-dark btn-sm" id="saveBtn"  >   <i class="bi bi-floppy-fill"></i> 저장  </button>			
	</div>
	 <div class="d-flex mt-3 mb-1 justify-content-center">  
	 <textarea id="summernote" name="content" rows="20" ><?=$content?></textarea>
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

      $('#summernote').summernote({
        placeholder: '내용 작성',
		// maximumImageFileSize: 500*1024, // 500 KB
		maximumImageFileSize: 1920*5000, 		
        tabsize: 2,
        height: 500,
        width: 1200,
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
                    // resizedImage는 처리된 이미지의 데이터 URL입니다.
                    $('#summernote').summernote('insertImage', resizedImage);
                        });
                    }
                }
            }
      });
	  
function resizeImage(file, callback) {
    var reader = new FileReader();
    reader.onloadend = function(e) {
        var tempImg = new Image();
        tempImg.src = reader.result;
        tempImg.onload = function() {
            // 여기서 원하는 이미지 크기로 설정
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
			 
	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	}); 	 

	// 하단복사 버튼
	$("#closeBtn1").click(function(){ 
	   $("#closeBtn").click();
	});
		
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
								"<img id='image" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
							"</a> &nbsp;&nbsp;" +
							"<button type='button' class='btn btn-danger btn-sm' id='delImage" + index + "' onclick=\"delImageFn('" + index + "', '" + fileId + "')\">" +
								"<i class='bi bi-trash'></i>" +
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
							"<img id='image" + i + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
						"</a> &nbsp;&nbsp;" +
						"<button type='button' class='btn btn-danger btn-sm' id='delImage" + i + "' onclick=\"delImageFn('" + i + "', '" + fileId + "')\">" +
							"<i class='bi bi-trash'></i>" +
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




