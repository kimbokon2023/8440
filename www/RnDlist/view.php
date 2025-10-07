<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/load_GoogleDrive.php'; // 세션 등 여러가지 포함됨 파일 포함

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}   
include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';     
// 첫 화면 표시 문구
$title_message = '개발진행';    
?>
<title> <?=$title_message?> </title>  
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php'; ?> 

</head> 

<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/modal.php"; ?>
    
<?php 
 $num=$_REQUEST["num"];
 $page=$_REQUEST["page"];   //페이지번호
 $tablename=$_REQUEST["tablename"];   //페이지번호
  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	
  
 
 try{
     $sql = "select * from  ".$DB."." . $tablename . " where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
     $item_num     = $row["num"];
     $item_id      = $row["id"];
     $item_name    = $row["name"];
     $item_nick    = $row["nick"];   
     $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
     $item_content = $row["content"];
     $item_date    = $row["regist_day"];
     $item_date    = substr($item_date, 0, 10);   
     $item_hit     = $row["hit"];     
     $is_html      = $row["is_html"];
     $division      = $row["division"];
     $searchtext      = $row["searchtext"];	 
        } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
  } 	 
      
     // if ($is_html!="y"){
	// $item_content = str_replace(" ", "&nbsp;", $item_content);
	// $item_content = str_replace("\n", "<br>", $item_content);
     // }	
     $new_hit = $item_hit + 1;
     try{
       $pdo->beginTransaction(); 
       $sql = "update ".$DB."." . $tablename . " set hit=? where num=?";   // 글 조회수 증가
       $stmh = $pdo->prepare($sql);  
       $stmh->bindValue(1, $new_hit, PDO::PARAM_STR);      
       $stmh->bindValue(2, $num, PDO::PARAM_STR);           
       $stmh->execute();
       $pdo->commit(); 
       } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
  }



// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num;  
$author_id = $item_id  ;
  
require_once $_SERVER['DOCUMENT_ROOT'] . '/load_GoogleDriveSecond.php'; // attached, image에 대한 정보 불러오기  
?> 


<form  id="board_form" name="board_form" method="post" enctype="multipart/form-data"> 
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  									
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  										
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->				
	<input type="hidden" id="searchtext" name="searchtext" value="<?=$searchtext?>" >  <!-- summernote text저장 -->				    
</form>  
 
<div class="container">  
	<div class="card mt-2 mb-4">  
	<div class="card-body">  
		<div class="d-flex mt-3 mb-4 justify-content-center">  
			<h5>  <?=$title_message?> </h5> 
		</div>	
	 <div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-left  align-items-center">  				
		
		<button type="button" id="closeBtn"  class="btn btn-dark btn-sm me-1" > &times; 닫기 </button>
		<?php
		// 삭제 수정은 관리자와 글쓴이만 가능토록 함
		
		if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" ||
			   $_SESSION["level"]===1 )
			{
		?>			
			<button type="button"   class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?=$tablename?>&mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>'" >  <i class="bi bi-pencil-square"></i>  수정  </button>			
			<button type="button"   class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?=$tablename?>'" >  <i class="bi bi-pencil"></i>  신규 </button>			
			<button type="button"   class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?tablename=<?=$tablename?>&num=<?=$num?>&page=<?=$page?>')" > <i class="bi bi-trash"></i>  삭제   </button>								
		<?php  }  ?>				
		
	</div>  
	  
		<div class="card">  
			<div class="card-body">  	 
				<div class="row d-flex  p-2 m-2 mt-1 mb-1 justify-content-center bg-secondary text-white align-items-center"> 		   
				  <div class="col-7 text-start fw-bold fs-6" > 구분 : <?= $division ?> | <?= $item_subject ?> </div>
				  <div class="col-5 text-end" > <?= $noticecheck_memo ?> |<?= $item_nick ?> | 조회 : <?= $item_hit ?> | <?= $item_date ?>   </div>   
				</div>
	  
				<div class="row d-flex  p-2 m-2 mt-1 mb-1 justify-content-left"> 	  
					<?=$item_content ?>
				</div>
			</div>
		</div>
	   <div class="row d-flex  p-2 m-2 mt-1 mb-1 justify-content-left "> 	
			<div id ="displayImage" class="row d-flex mt-1 mb-1 justify-content-center" style="display:none;">  	 		 					 
		</div>		
		
		<div id ="displayFile" class="d-flex mt-1 mb-1 justify-content-center" style="display:none;">
		
		</div>			
		</div>			
			
 </div> 
 </div> 
 </div> 

  
<script> 

$(document).ready(function(){	
 	 
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


}); // end of ready document
 	

function del(href) {    
    var user_id  = '<?php echo  $user_id ; ?>' ;
    var author_id  = '<?php echo  $author_id ; ?>' ;
    var admin  = '<?php echo  $admin ; ?>' ;
	if( user_id !== author_id && admin !== '1' )
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
					url: "delete.php",
					type: "post",		
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){
						console.log(data);
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
								window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
								window.opener.location.reload(); // 부모 창 새로고침
								window.close();
							}							
							
						}, 1000);	
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
					} 			      		
				   });	
                    

            }
        });
    }
}
 
</script>

<script>
$(document).ready(function(){

	 displayFileLoad();				 
	 displayImageLoad();	

}); // end of ready document
 
// 기존 있는 이미지 화면에 보여주기
function displayImageLoad() { 
	$('#displayImage').show();	
	var saveimagename_arr = <?php echo json_encode($saveimagename_arr);?> ;	

    $("#displayImage").html('');
    saveimagename_arr.forEach(function(pic, index) {
        var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
		const realName = pic.realname || '다운로드 파일';
        var link = pic.link || '#';
        var fileId = pic.fileId || null;

        if (!fileId) {
            console.error("fileId가 누락되었습니다. index: " + index, pic);
            return; // fileId가 없으면 해당 항목 건너뛰기
        }

		$("#displayImage").append(
			"<div class='row mt-2 mb-1'>" +
				"<div class='d-flex justify-content-center mt-1 mb-1'>" +
					"<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
						"<img id='pic" + index + "' src='" + thumbnail + "' style='width:300px; height:auto;'>" +
					"</a>" +
				"</div>" +
			"</div>"
		);

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

			// 파일 정보 행 추가
			$("#displayFile").append(
				"<div class='row mb-3'>" +
					"<div id='file" + i + "' class='col d-flex align-items-center justify-content-center'>" +
						"<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" +
							realName +
						"</a> &nbsp; &nbsp; " +
					"</div>" +
				"</div>"
			);

        });
    } else {
        $("#displayFile").append(
            "<div class='text-center text-muted'>No attached files</div>"
        );
    }
}

</script>

</body>
</html>    