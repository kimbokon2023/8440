<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';

// 권한 체크
if($level > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php"); 
    exit;
}

$title_message = "투표";

include includePath('load_header.php');
?> 
  
<title>  <?=$title_message?>  </title> 
    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
        
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
            
            /* 제목 영역 최적화 */
            .d-flex.mt-3.mb-1.justify-content-center.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .d-flex.mt-3.mb-1.justify-content-center.align-items-center h5 {
                font-size: 1.25rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                text-align: center !important;
                margin: 0.5rem 0 !important;
            }
            
            /* 작성자/체크박스 영역 최적화 */
            .d-flex.mt-2.mb-1.justify-content-center.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 0.5rem !important;
            }
            
            .d-flex.mt-2.mb-1.justify-content-center.align-items-center .card {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .card-body .d-flex.mt-2.mb-1.justify-content-center.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .card-body .d-flex.justify-content-center.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .card-body .d-flex.mt-2.justify-content-center.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .card-body .d-flex.mt-2.justify-content-center.align-items-center span,
            .card-body .d-flex.mt-2.justify-content-center.align-items-center select,
            .card-body .d-flex.mt-2.justify-content-center.align-items-center input {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0.25rem 0 !important;
            }
            
            .card-body .d-flex.justify-content-center.align-items-center input[type="checkbox"] {
                width: auto !important;
                max-width: auto !important;
            }
            
            /* 버튼 그룹 최적화 */
            .d-flex.mt-1.mb-1.justify-content-start.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .d-flex.mt-1.mb-1.justify-content-start.align-items-center button {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0.25rem 0 !important;
                padding: 0.5rem !important;
                font-size: 1rem !important;
            }
            
            /* Summernote 에디터 최적화 */
            .note-editor {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            .note-editor.note-frame {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .note-editor .note-editing-area {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .note-editor .note-editable {
                width: 100% !important;
                max-width: 100% !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            .note-toolbar {
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: auto !important;
            }
            
            .note-toolbar .note-btn-group {
                flex-wrap: wrap !important;
            }
            
            /* Summernote 초기화 시 모바일 크기 조정 */
            #summernote {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            /* 투표 목록 영역 최적화 */
            .d-flex.mt-3.mb-1.justify-content-center {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 0.5rem !important;
            }
            
            .d-flex.mt-3.mb-1.justify-content-center .card {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .d-flex.mt-2.mb-2.justify-content-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .d-flex.mt-2.mb-2.justify-content-center span,
            .d-flex.mt-2.mb-2.justify-content-center button {
                width: 100% !important;
                max-width: 100% !important;
                text-align: center !important;
                margin: 0.25rem 0 !important;
            }
            
            /* 투표 테이블 최적화 */
            #dynamicTable {
                font-size: 0.875rem !important;
            }
            
            #dynamicTable th,
            #dynamicTable td {
                padding: 0.5rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            #dynamicTable input {
                width: 100% !important;
                max-width: 100% !important;
                font-size: 0.875rem !important;
            }
            
            #dynamicTable button {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0.25rem 0 !important;
                font-size: 0.875rem !important;
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
<?php include includePath('common/modal.php'); ?>
      
<?php   

// 요청 변수 초기화
$id = $_REQUEST["id"] ?? '';
$fileorimage = $_REQUEST["fileorimage"] ?? '';
$item = $_REQUEST["item"] ?? '';
$upfilename = $_REQUEST["upfilename"] ?? '';
$tablename = $_REQUEST["tablename"] ?? 'vote';
$savetitle = $_REQUEST["savetitle"] ?? '';
$mode = $_REQUEST["mode"] ?? 'insert';
$num = $_REQUEST["num"] ?? '';
$parentid = $_REQUEST["parentid"] ?? '';
$pInput = $_REQUEST["pInput"] ?? '';
$searchtext = $_REQUEST["searchtext"] ?? '';

// 변수 초기화 (신규 생성 모드 대응)
$item_subject = '';
$is_html = '';
$content = '';
$noticecheck = '';
$status = '';
$deadline = '';
$votelist = '{}';

$num_Array = array();     
$piclist_Array = array();  	  
		  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

if($mode == "modify" && !empty($num)) {
    try {
        $sql = "SELECT * FROM " . $DB . "." . $tablename . " WHERE num = ?";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $num, PDO::PARAM_INT); 
        $stmh->execute();
        
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $item_subject = $row["subject"] ?? '';
            $is_html = $row["is_html"] ?? '';
            $content = $row["content"] ?? '';
            $noticecheck = $row["noticecheck"] ?? '';
            $status = $row["status"] ?? '';
            $deadline = $row["deadline"] ?? '';
            $votelist = $row["votelist"] ?? '{}';
        } else {
            echo "<script>alert('게시글을 찾을 수 없습니다.'); window.close();</script>";
            exit;
        }
    } catch (PDOException $Exception) {
        error_log("데이터 조회 오류: " . $Exception->getMessage());
        echo "<script>alert('데이터 조회 중 오류가 발생했습니다.'); window.close();</script>";
        exit;
    }
}

// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id = $num;  
// 신규데이터 작성시 키값지정 parentid값이 없으면 데이터 저장안됨
$timekey = date("Y_m_d_H_i_s");
?>
 
<form  id="board_form" name="board_form" method="post" enctype="multipart/form-data"> 
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>" >			  									
	<input type="hidden" id="parentid" name="parentid" value="<?= htmlspecialchars($parentid, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?= htmlspecialchars($fileorimage, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?= htmlspecialchars($upfilename, ENT_QUOTES, 'UTF-8') ?>" >			  									
	<input type="hidden" id="savetitle" name="savetitle" value="<?= htmlspecialchars($savetitle, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?= htmlspecialchars($pInput, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?= htmlspecialchars($timekey, ENT_QUOTES, 'UTF-8') ?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->		
	<input type="hidden" id="searchtext" name="searchtext" value="<?= htmlspecialchars($searchtext, ENT_QUOTES, 'UTF-8') ?>" >  <!-- summernote text저장 -->				

<div class="container-fluid">  
	<div class="d-flex mt-3 mb-1 justify-content-center align-items-center"> 
		<h5> 투표 </h5>  
	</div>	      
	<div class="d-flex mt-2 mb-1 justify-content-center align-items-center"> 		
		<div class="card mt-2" style="width:80%;">  
			<div class="card-body">  	
				<div class="d-flex mt-2 mb-1 justify-content-center align-items-center"> 		
				 <div class="row"> 
					 <div class="col-3-sm"> 
						<div class="d-flex justify-content-center align-items-center"> 							 
							작성자  : &nbsp;    <?= htmlspecialchars($_SESSION["nick"] ?? '', ENT_QUOTES, 'UTF-8') ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="checkbox" name="is_html" value="y" <?php if($is_html == 'y') echo 'checked'; ?> >&nbsp; HTML 쓰기  &nbsp;
							<input type="checkbox" name="noticecheck" value="y" <?php if($noticecheck == 'y') echo 'checked'; ?> > &nbsp; 전체공지 										
						</div>
					</div>
					 <div class="col-5-sm"> 													
						<div class="d-flex  mt-2 justify-content-center align-items-center"> 	
							 진행상태  &nbsp;     
						   <select name="status" id="status" class="form-control me-1" style="width:70px;" >
						   <?php			   
							   
							   if($status === '') {
									$status = '진행중';
							   }
								
							   $arrStr = array('진행중', '마감');
							   
							   foreach($arrStr as $statusOption) {
									$selected = ($status === $statusOption) ? 'selected' : '';
									echo "<option " . $selected . " value='" . htmlspecialchars($statusOption, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($statusOption, ENT_QUOTES, 'UTF-8') . "</option>";
								} 	
										?>	  
								</select> 
								&nbsp;&nbsp;  마감일  &nbsp;&nbsp;     
								<input id="deadline" name="deadline" type="date"  value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>" required  class="form-control me-1" style="width:100px;" >					   
									&nbsp;&nbsp;   
									제목 &nbsp; 					
							<input id="subject" name="subject" type="text" required class="form-control" style="width:400px;" value="<?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?>">&nbsp;														
						</div>	
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>
	<div class="card mt-1 mb-1" >  
		<div class="card-body">  
			<div class="d-flex mt-1 mb-1 justify-content-start align-items-center"> 
				<span class="me-3"> </span>			
				<button class="btn btn-dark btn-sm me-1" onclick="self.close();" > &times; 닫기 </button>
				<button type="button"   class="btn btn-dark btn-sm" id="saveBtn"  >  <i class="bi bi-floppy-fill"></i> 저장  </button>			
			</div>
 
 <div class="d-flex mt-2 mb-1 justify-content-center">  	
	<textarea id="summernote" name="content" rows="15" required><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
 </div>
 <div class="d-flex mt-3 mb-1 justify-content-center">  	
		<div class="card p-2"> 
		<div class="card-body">  
			<div class="d-flex mt-2 mb-2 justify-content-center">    
				 <span class="text-primary fs-5 text-center me-3"> 투표 목록 만들기 </span>
				 <button  type="button" class="btn btn-dark btn-sm" id="savelistBtn"> 저장 </button>	 
			</div>	 
			<div class="table-responsive">
			
				<table class="table table-bordered" id="dynamicTable">
					<thead>
						<tr>
							<th class="text-center" style="width:15%;">번호</th>
							<th class="text-center" style="width:50%;">투표항목</th>
							<th class="text-center" style="width:20%;">투표참여자</th>
							<th class="text-center" style="width:20%;">작업</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

		</div>
	</div>
	</div>
 </div>
</div>  
</div>  
</div>  
</div>  
</form>
 </body>
 </html>

<script>

$(document).ready(function() {
    var i = 1;

    // '+' 버튼 이벤트 핸들러: 현재 행 바로 아래에 새 행 추가
    $(document).on('click', '.add', function() {
        var currentRow = $(this).closest('tr');
        var rowIndex = currentRow.index(); // 현재 행의 인덱스를 구합니다.
        var newRow = '<tr>' +
            '<td class="text-center"><input type="text" name="col1[]" class="form-control text-center" value="' + (rowIndex + 2) + '" /></td>' + // 인덱스 + 2를 고유키로 설정
            '<td><input type="text" name="col2[]" class="form-control" /></td>' +
            '<td><input type="text" name="col3[]" class="form-control" /></td>' +
            '<td class="text-center" ><button type="button" class="btn btn-success btn-sm add">+</button> ' +
            '<button type="button" class="btn btn-danger  btn-sm remove">-</button></td>' +
        '</tr>';
        $(newRow).insertAfter(currentRow);
    });

    // '-' 버튼 이벤트 핸들러: 현재 행 삭제
    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });
	
    var piclistObj = {}; 
    try {
        piclistObj = JSON.parse('<?php echo addslashes($votelist); ?>');
    } catch (e) {
        console.error("JSON 파싱 오류: ", e);
    }

    // Row_COUNT를 piclistObj의 col2 배열 길이에 따라 동적으로 설정
    const Row_COUNT = piclistObj.col2 ? piclistObj.col2.length : 0;
    const COL_NAMES = 3;
    const column = Array.from({ length: COL_NAMES }, function(_, i) { return 'col' + (i+1); });

    // 데이터가 없는 경우에만 초기 행 추가
    if (!piclistObj.col2 || piclistObj.col2.length === 0) {
        $('#dynamicTable tbody').append('<tr id="row1">' +
            '<td class="text-center"><input type="text" name="col1[]" class="form-control text-center"  value="1" /></td>' +
            '<td><input type="text" name="col2[]" class="form-control" /></td>' +
            '<td><input type="text" name="col3[]" class="form-control" /></td>' +
            '<td class="text-center"><button type="button" class="btn btn-success  btn-sm add">+</button></td>' +
        '</tr>');
    }	
		
    const data = Array.from({ length: Row_COUNT }, function(_, i) {
        var row = {};
        column.forEach(function(col, index) {
            row[col] = (piclistObj[col] && piclistObj[col][i] ? piclistObj[col][i] : '');
        });
        return row;
    });

    data.forEach(function(row, index) {
        // col2에 값이 있는 경우에만 행을 추가합니다.
        if(row.col2) {
            $('#dynamicTable tbody').append('<tr>' +
                '<td class="text-center"><input type="text" name="col1[]" class="form-control text-center" value="' + (index + 1) + '" /></td>' +
                '<td><input type="text" name="col2[]" class="form-control" value="' + row.col2 + '" /></td>' +
                '<td><input type="text" name="col3[]" class="form-control" value="' + row.col3 + '" /></td>' +
                '<td class="text-center"><button type="button" class="btn btn-success  btn-sm add">+</button> ' +
                '<button type="button" class="btn btn-danger  btn-sm remove">-</button></td>' +
            '</tr>');
        }
    });

function savegrid() {
    let columns = {
        col1: [],
        col2: [],
        col3: []
    };

    // 각 행에 대해 반복하여 데이터 수집
    $('#dynamicTable tbody tr').each(function() {
        var col1 = $(this).find('input[name="col1[]"]').val();
        var col2 = $(this).find('input[name="col2[]"]').val();
        var col3 = $(this).find('input[name="col3[]"]').val();
 
        // 투표항목이 있는지 검사
        if (col2) {
            columns.col1.push(col1);
            columns.col2.push(col2);
            columns.col3.push(col3);
        }
    });
	
	    
	const datanum = '<?php echo $num; ?>';
	
	console.log('datanum', datanum);
    const dataToSend = {
        num: datanum,
        data: columns
    };
	
	console.log(JSON.stringify(dataToSend));
    
	
	
    $.ajax({
        url: "makejsonlist.php",
        type: "post",
        data: JSON.stringify(dataToSend),
        dataType: "json",
        success: function(data) {
            console.log(data);
            Swal.fire(
                '등록완료',
                '데이터가 성공적으로 등록되었습니다.',
                'success'
            );
        },
        error: function(jqxhr, status, error) {
            console.log(jqxhr, status, error);
            Swal.fire(
                '오류 발생',
                '데이터 등록 중 오류가 발생했습니다. 다시 시도해주세요.',
                'error'
            );
        }
    });
 
}


$("#savelistBtn").click(function(){  
    savegrid();
  });	  

});	  

var ajaxRequest = null;

$(document).ready(function(){	
      // 모바일 환경 감지
      var isMobile = window.innerWidth <= 768;
      
      // Summernote 초기화
      var summernoteConfig = {
          placeholder: '내용 작성',
          maximumImageFileSize: 1920*5000, 		
          tabsize: 2,
          height: isMobile ? 300 : 350,
          width: isMobile ? '100%' : 1200,
          toolbar: isMobile ? [
              ['font', ['bold', 'underline', 'clear']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['insert', ['link', 'picture']]
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
      };
      
      $('#summernote').summernote(summernoteConfig);
      
      // 창 크기 변경 시 Summernote 크기 조정
      $(window).on('resize', function() {
          var isMobileNow = window.innerWidth <= 768;
          if (isMobileNow !== isMobile) {
              isMobile = isMobileNow;
              $('#summernote').summernote('destroy');
              summernoteConfig.height = isMobile ? 300 : 350;
              summernoteConfig.width = isMobile ? '100%' : 1200;
              summernoteConfig.toolbar = isMobile ? [
                  ['font', ['bold', 'underline', 'clear']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['insert', ['link', 'picture']]
              ] : [
                  ['style', ['style']],
                  ['font', ['bold', 'underline', 'clear']],
                  ['color', ['color']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['table', ['table']],
                  ['insert', ['link', 'picture', 'video']],
                  ['view', ['fullscreen', 'codeview', 'help']]
              ];
              $('#summernote').summernote(summernoteConfig);
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


$("#saveBtn").click(function(){ 
    Fninsert();
}); 	 
 	 	 
$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
}); 	 

// 하단복사 버튼
$("#closeBtn1").click(function(){ 
   $("#closeBtn").click();
})
	
$("#closeBtn").click(function(){    // 저장하고 창닫기	
    opener.location.reload();	
	self.close();
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
						var tablename = data["tablename"];
						
						setTimeout(function(){
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

