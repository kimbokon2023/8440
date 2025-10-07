<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session.php'); 

$title_message= "투표" ;

?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   } 

 ?> 
  
<title>  <?=$title_message?>  </title> 
    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
    </style>  
 </head>  
<body>
<?php include "../common/modal.php"; ?>      
<?php   

$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
$fileorimage = isset($_REQUEST["fileorimage"]) ? $_REQUEST["fileorimage"] : "";
$item = isset($_REQUEST["item"]) ? $_REQUEST["item"] : "";
$upfilename = isset($_REQUEST["upfilename"]) ? $_REQUEST["upfilename"] : "";
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : "";
$savetitle = isset($_REQUEST["savetitle"]) ? $_REQUEST["savetitle"] : "";
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

$num_Array=array();     
$piclist_Array=array();  	  
		  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

  if ($mode=="modify"){
    try{
      $sql = "select * from ".$DB.".". $tablename . " where num = ? ";
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
      $noticecheck = $row["noticecheck"];
      $status = $row["status"];
      $deadline = $row["deadline"];
      $votelist = $row["votelist"] ?? '{}'; // votelist 값이 없을 경우의 기본값
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }  
    else
  {
	  $num='';
	  $id='';	  
      $votelist = '{}';
      $mode='insert';
  }

// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num;  
 // 신규데이터 작성시 키값지정 parentid값이 없으면 데이터 저장안됨
$timekey = date("Y_m_d_H_i_s");
?>
 
<form  id="board_form" name="board_form" method="post" enctype="multipart/form-data"> 
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  									
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?=$upfilename?>" >			  									
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->		
	<input type="hidden" id="searchtext" name="searchtext" value="<?=$searchtext?>" >  <!-- summernote text저장 -->				

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
							작성자  : &nbsp;    <?=$_SESSION["nick"]?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="checkbox" name="is_html" value="y" <?php if($is_html=='y') print 'checked'; ?> >&nbsp; HTML 쓰기  &nbsp;
							<input type="checkbox" name="noticecheck" value="y" <?php if($noticecheck=='y') print 'checked'; ?> > &nbsp; 전체공지 										
						</div>
					</div>
					 <div class="col-5-sm"> 													
						<div class="d-flex  mt-2 justify-content-center align-items-center"> 	
							 진행상태  &nbsp;     
						   <select name="status" id="status" class="form-control me-1" style="width:70px;" >
						   <?php			   
							   
							   if($status === '')
									$status = '진행중';
								
							   $arrStr = array();
							   array_push($arrStr,'진행중','마감');
							   
							   for($i=0; $i<count($arrStr); $i++) {
										 if($status===$arrStr[$i])
													print "<option selected value='" . $arrStr[$i] . "'> " . $arrStr[$i] .   "</option>";
											 else   
									   print "<option value='" . $arrStr[$i] . "'> " . $arrStr[$i] .   "</option>";
								   } 	
										?>	  
								</select> 
								&nbsp;&nbsp;  마감일  &nbsp;&nbsp;     
								<input id="deadline" name="deadline" type="date"  value="<?=$deadline?>" required  class="form-control me-1" style="width:100px;" >					   
									&nbsp;&nbsp;   
									제목 &nbsp; 					
							<input id="subject" name="subject" type="text" required class="form-control" style="width:400px;" <?php if($mode=="modify"){ ?> value="<?=$item_subject?>" <?php }?>>&nbsp;														
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
	<textarea id="summernote" name="content" rows="15" required><?=$content?></textarea>
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

      $('#summernote').summernote({
				placeholder: '내용 작성',
				// maximumImageFileSize: 500*1024, // 500 KB
				maximumImageFileSize: 1920*5000, 		
				tabsize: 2,
				height: 350,
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

