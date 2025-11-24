<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/load_GoogleDrive.php';

/**
 * 문지방덮개 (재료분리대) 작성/수정 폼
 * 
 * 출고/입고 데이터 작성 및 수정, 파일 첨부 기능 제공
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$admin = $_SESSION["level"] === 1 ? '1' : '0';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';
$chkMobile = $_SESSION["chkMobile"] ?? false;

// 권한 체크
if ($level > 8) {
    $_SESSION["url"] = getBaseUrl() . '/sillcover/write_form.php';
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 첫 화면 표시 문구
$titlemsg = '재료분리대 출고사진';

include getDocumentRoot() . '/load_header.php';
?>

<title><?= htmlspecialchars($titlemsg, ENT_QUOTES, 'UTF-8') ?></title>
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
  
  input[readonly], textarea[readonly] {
    background-color: #f0f0f0; /* Light gray background */
    color: #6c757d; /* Optional: Slightly darker text color */
    border-color: #ced4da; /* Optional: Border color adjustment */
    cursor: not-allowed; /* Change cursor to indicate non-editable */
}

/* 모바일 반응형 스타일 */
@media (max-width: 768px) {
	/* body와 html의 width 제한 */
	html, body {
		max-width: 100vw !important;
		overflow-x: hidden !important;
		font-size: 16px !important;
	}

	/* 컨테이너 패딩 조정 */
	.container-fluid, .container {
		max-width: 100vw !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		overflow-x: hidden !important;
	}

	/* 카드 패딩 조정 */
	.card {
		margin-bottom: 10px !important;
	}

	.card-body {
		padding: 10px !important;
	}

	/* 제목 영역 모바일 최적화 */
	.d-flex h4 {
		font-size: 1.2rem !important;
		white-space: nowrap !important;
		margin-bottom: 15px !important;
		margin-top: 10px !important;
	}

	/* 버튼 그룹 모바일 최적화 */
	.btn-sm {
		font-size: 0.9rem !important;
		padding: 0.5rem 0.8rem !important;
		white-space: nowrap !important;
		margin-bottom: 5px !important;
	}

	/* 버튼 영역 줄바꿈 허용 */
	.d-flex.justify-content-start,
	.d-flex.justify-content-end {
		flex-wrap: wrap !important;
		gap: 5px !important;
	}

	/* 테이블 모바일 최적화 */
	.table {
		font-size: 0.95rem !important;
	}

	.table td, .table th {
		padding: 8px 4px !important;
		font-size: 0.9rem !important;
		vertical-align: middle !important;
	}

	/* 테이블 첫 번째 열 (라벨) 너비 조정 */
	.table td:first-child {
		width: 25% !important;
		min-width: 80px !important;
		font-weight: 600 !important;
		background-color: #f8f9fa !important;
	}

	/* 입력 필드 모바일 최적화 */
	.form-control {
		font-size: 0.9rem !important;
		padding: 0.5rem 0.75rem !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}

	/* 날짜 입력 필드 크기 조정 */
	input[type="date"].form-control {
		font-size: 0.85rem !important;
		padding: 0.4rem 0.6rem !important;
		min-width: 140px !important;
	}

	/* 날짜 입력 필드 내부 텍스트 크기 조정 */
	input[type="date"].form-control::-webkit-datetime-edit {
		font-size: 0.85rem !important;
		padding: 0 !important;
	}

	/* 날짜 입력 필드 달력 아이콘 크기 조정 */
	input[type="date"].form-control::-webkit-calendar-picker-indicator {
		width: 18px !important;
		height: 18px !important;
		padding: 0 !important;
	}

	/* 텍스트 영역 모바일 최적화 */
	textarea.form-control {
		font-size: 0.9rem !important;
		padding: 0.5rem 0.75rem !important;
		min-height: 80px !important;
		resize: vertical !important;
	}

	/* 사진 첨부 버튼 모바일 최적화 */
	label[for="upfileimage"] {
		font-size: 0.95rem !important;
		padding: 0.6rem 1rem !important;
		margin: 10px 0 !important;
		width: auto !important;
		display: inline-block !important;
	}

	/* 이미지 표시 영역 모바일 최적화 */
	#displayImage {
		width: 100% !important;
		padding: 10px !important;
	}

	#displayImage img {
		max-width: 100% !important;
		height: auto !important;
		width: auto !important;
		margin: 5px !important;
	}

	#displayImage .row {
		margin: 0 !important;
	}

	#displayImage .col {
		padding: 5px !important;
		display: flex !important;
		flex-direction: column !important;
		align-items: center !important;
		justify-content: center !important;
	}

	/* 이미지 삭제 버튼 모바일 최적화 */
	#displayImage .btn-danger {
		margin-top: 5px !important;
		font-size: 0.85rem !important;
		padding: 0.4rem 0.6rem !important;
	}

	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin-left: -5px !important;
		margin-right: -5px !important;
	}

	.row > [class*="col-"] {
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* col-sm-9, col-sm-3 모바일에서 전체 너비 */
	.col-sm-9, .col-sm-3 {
		width: 100% !important;
		flex: 0 0 100% !important;
		max-width: 100% !important;
		margin-bottom: 10px !important;
	}

	/* 버튼 영역 중앙 정렬 */
	.col-sm-9 .d-flex,
	.col-sm-3 .d-flex {
		justify-content: center !important;
	}

	/* 테이블 반응형 처리 */
	.table-responsive {
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
	}

	/* readonly 입력 필드 모바일 스타일 */
	input[readonly], textarea[readonly] {
		font-size: 0.9rem !important;
		padding: 0.5rem 0.75rem !important;
	}

	/* 카드 내부 여백 조정 */
	.card-body > .row {
		margin-left: 0 !important;
		margin-right: 0 !important;
	}

	/* 제목과 버튼 영역 간격 조정 */
	.d-flex.mb-5.mt-5 {
		margin-bottom: 15px !important;
		margin-top: 10px !important;
	}

	/* 테이블 border 모바일 최적화 */
	.table-bordered {
		border: 1px solid #dee2e6 !important;
	}

	.table-bordered td,
	.table-bordered th {
		border: 1px solid #dee2e6 !important;
	}
}

</style>	
 

<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php
// 모바일 CSS는 위의 <style> 태그 내 @media 쿼리로 처리

// 요청 변수 초기화
$id = $_REQUEST["id"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? 'sillcover';
$item = $_REQUEST["item"] ?? '';
$timekey = $_REQUEST["timekey"] ?? '';
$searchtext = $_REQUEST["searchtext"] ?? '';

// 데이터 변수 초기화
$outdate = '';
$indate = '';
$workplace = '';
$comment = '';
$first_writer = '';
$update_log = '';
$is_deleted = '';
$request_comment = ''; 

// 수정 모드 또는 보기 모드
if ($mode == "modify" || $mode == "view") {
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
        
        if ($count < 1) {
            echo "<script>alert('결과가 없습니다.'); window.close();</script>";
            exit;
        } else {
            include '_row.php';
            
            // 날짜 포맷팅
            if ($indate != "0000-00-00" && !empty($indate)) {
                $indate = date("Y-m-d", strtotime($indate));
            } else {
                $indate = "";
            }
            
            if ($outdate != "0000-00-00" && !empty($outdate)) {
                $outdate = date("Y-m-d", strtotime($outdate));
            } else {
                $outdate = "";
            }
        }
    } catch (PDOException $Exception) {
        error_log("데이터 조회 오류: " . $Exception->getMessage());
        echo "<script>alert('데이터 조회 중 오류가 발생했습니다.'); window.close();</script>";
        exit;
    }
}

// 신규 작성 모드
if (empty($mode)) {
    $outdate = date("Y-m-d");
    $indate = date("Y-m-d");
    $workplace = '';
    $comment = '';
    $request_comment = null;
    $titlemsg = '재료분리대 출고사진';
}

// 복사 모드
if ($mode == "copy") {
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
        
        if ($count < 1) {
            echo "<script>alert('결과가 없습니다.'); window.close();</script>";
            exit;
        } else {
            include '_row.php';
            $outdate = date("Y-m-d");
            $indate = date("Y-m-d");
        }
    } catch (PDOException $Exception) {
        error_log("데이터 조회 오류: " . $Exception->getMessage());
        echo "<script>alert('데이터 조회 중 오류가 발생했습니다.'); window.close();</script>";
        exit;
    }
    $titlemsg = '재표분리대(복사) 출고사진';
    $num = '';
}

// 초기 프로그램은 $num 사용 이후 $id로 수정중임
$id = $num;
require_once getDocumentRoot() . '/load_GoogleDriveSecond.php'; // attached, image에 대한 정보 불러오기
?>


<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <!-- 전달함수 설정 input hidden -->
    <input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="timekey" name="timekey" value="<?= htmlspecialchars($timekey ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="searchtext" name="searchtext" value="<?= htmlspecialchars($searchtext, ENT_QUOTES, 'UTF-8') ?>">

<?php if ($chkMobile) { ?>
<div class="container-fluid">
<?php } else { ?>
<div class="container">
<?php } ?>
    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-5 mt-5 justify-content-center align-items-center">
                <h4><?= htmlspecialchars($titlemsg, ENT_QUOTES, 'UTF-8') ?></h4>
            </div>	

            
            <div class="row">
                <div class="col-sm-9">
                    <div class="d-flex mb-1 justify-content-start align-items-center">
                        <button id="saveBtn" type="button" class="btn btn-dark btn-sm me-2"><i class="bi bi-floppy"></i> 저장</button>
                        <?php if (($user_id === $first_writer || $admin == '1') && $mode == 'view') { ?>
                            <button type="button" class="btn btn-dark btn-sm me-2" onclick="location.href='write_form.php?mode=modify&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>';"><i class="bi bi-pencil-square"></i> 수정</button>
                            <button type="button" class="btn btn-danger btn-sm me-2 deleteBtn"><i class='bi bi-trash'></i> 삭제</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="d-flex mb-1 justify-content-end">
                        <button class="btn btn-secondary btn-sm" onclick="self.close();"><i class="bi bi-x-lg"></i> 닫기</button>&nbsp;
                    </div>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="text-center w-25">출고일</td>
                                <td class="text-center">
                                    <input type="date" id="outdate" name="outdate" class="form-control w-25" autocomplete="off" value="<?= htmlspecialchars($outdate, ENT_QUOTES, 'UTF-8') ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width:10%;">현장명</td>
                                <td>
                                    <input type="text" id="workplace" name="workplace" class="form-control" autocomplete="off" value="<?= htmlspecialchars($workplace, ENT_QUOTES, 'UTF-8') ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width:10%;">메모</td>
                                <td colspan="3">
                                    <textarea id="comment" name="comment" class="form-control" rows="2"><?= htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') ?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="d-flex mt-3 mb-1 justify-content-center">
            <label for="upfileimage" class="btn btn-outline-dark btn-sm">사진 첨부</label>
            <input id="upfileimage" name="upfileimage[]" type="file" onchange="this.value" multiple accept="image/*" capture="camera" style="display:none">
        </div>
        <div class="d-flex mb-1 justify-content-center">
            <div class="card justify-content-center">
                <div class="card-body justify-content-center">
                    <div class="d-flex mb-1 justify-content-center fs-3">
                        <div id="displayImage" class="row d-flex mt-3 mb-1 justify-content-center" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>


<script>
var ajaxRequest = null;
var ajaxRequest_write = null;

$(document).ready(function() {
    $("#saveBtn").click(function() {
        // 조건 확인
        if ($("#workplace").val() === '') {
            showWarningModal();
        } else {
            showMsgModal(2); // 파일저장중
            Toastify({
                text: "변경사항 저장중...",
                duration: 2000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                }
            }).showToast();
            setTimeout(function() {
                saveData();
            }, 1000);
        }
    });
    
    function showWarningModal() {
        Swal.fire({
            title: '등록 오류 알림',
            text: '현장명은 필수입력 요소입니다.',
            icon: 'warning',
            confirmButtonText: '확인'
        }).then(result => {
            if (result.isConfirmed) {
                return; // 사용자가 확인 버튼을 누르면 아무것도 하지 않고 종료
            }
        });
    }
    
    function saveData() {
        var num = $("#num").val();
        
        // 결재상신이 아닌경우 수정안됨
        if (Number(num) < 1) {
            $("#mode").val('insert');
        }
        
        // 폼데이터 전송시 사용함 Get form
        var form = $('#board_form')[0];
        var datasource = new FormData(form);
        
        if (ajaxRequest !== null) {
            ajaxRequest.abort();
        }
        
        showMsgModal(2); // 파일저장중
        ajaxRequest = $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "insert.php",
            type: "post",
            data: datasource,
            dataType: "json",
            success: function(data) {
                setTimeout(function() {
                    if (window.opener && !window.opener.closed) {
                        // 부모 창에 restorePageNumber 함수가 있는지 확인
                        if (typeof window.opener.restorePageNumber === 'function') {
                            window.opener.restorePageNumber(); // 함수가 있으면 실행
                        }
                    }
                }, 1000);
                setTimeout(function() {
                    hideMsgModal();
                    location.href = "write_form.php?mode=view&num=" + data["num"];
                }, 1000);
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });
    }
});


function captureReturnKey(e) {
    if (e.keyCode == 13 && e.srcElement.type != 'textarea')
        return false;
}

function deleteData() {
    Swal.fire({
        title: '자료 삭제',
        text: "삭제하시겠습니까?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '삭제',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {
            $("#mode").val('delete');
            const inputs = document.querySelectorAll('input[required]');
            
            const formData = new FormData(document.getElementById('board_form'));
            
            if (ajaxRequest_write !== null) {
                ajaxRequest_write.abort();
            }
            
            ajaxRequest_write = $.ajax({
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: "insert.php",
                type: "post",
                data: formData,
                dataType: "json",
                success: function(data) {
                    Toastify({
                        text: "저장 완료",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    setTimeout(function() {
                        if (window.opener && !window.opener.closed) {
                            // 부모 창에 restorePageNumber 함수가 있는지 확인
                            if (typeof window.opener.restorePageNumber === 'function') {
                                window.opener.restorePageNumber(); // 함수가 있으면 실행
                            }
                        }
                        setTimeout(function() {
                            self.close();
                        }, 1000);
                    }, 1000);
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        }
    });
}

$(document).ready(function() {
    $(".deleteBtn").click(function() {
        deleteData();
    });
    
    if ($("#mode").val() === 'view') {
        disableInputsForViewMode();
    }
});

function disableInputsForViewMode() {
    $('input, textarea').prop('readonly', true);
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
    var data = <?php echo json_encode($savefilename_arr ?? []); ?>;
    
    $("#displayFile").html(''); // 기존 내용 초기화
    
    if (Array.isArray(data) && data.length > 0) {
        data.forEach(function(fileData, i) {
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
    var data = <?php echo json_encode($saveimagename_arr ?? []); ?>;
    
    $("#displayImage").html(''); // 기존 내용 초기화
    
    if (Array.isArray(data) && data.length > 0) {
        data.forEach(function(fileData, i) {
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




