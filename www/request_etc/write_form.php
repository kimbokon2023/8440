<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/load_GoogleDrive.php'; // 세션 등 여러가지 포함됨 파일 포함
	
if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 $_SESSION["url"]='https://8440.co.kr/request_etc/write_form.php' ; 		  		 
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }	 
	
$titlemsg	= '부자재 구매';	
 
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
   
 // 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        body, table th, table td, h4, .form-control {
            font-size: 30px;
        }
         h4 {
            font-size: 40px; 
        }
		.btn-sm {
        font-size: 30px;
		}
		
    </style>';
}

$tablename = 'request_etc';  
  
$callback=$_REQUEST["callback"];  // 출고현황에서 체크번호
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";

  if(isset($_REQUEST["which"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $which=$_REQUEST["which"];
  else
   $which="2";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";
  
 
  if ($mode=="modify" or $mode=="view"){
    try{
      $sql = "select * from mirae8440.eworks where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "결과가 없습니다.<br>";
     }else{
		 
 			include '_row.php';		  
	  
			 if($indate!="0000-00-00") $indate = date("Y-m-d", strtotime( $indate) );
					else $indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";	 					
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     } 
  }
  
      
  if ($mode!="modify" and $mode!="view" and $mode!="copy"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
			  $outdate=date("Y-m-d");
			  $indate=null;
			  $outworkplace=$row["outworkplace"];
			  
			  $payment=null;			  
			  $steel_item=null;			  
			  $spec=null;
			  $steelnum=null;			  
			  $company="";
			  $supplier="";
			  $request_comment=null;
			  $which="1";	 			   // 요청 
			  $titlemsg	= '부자재 구매';
  } 
  
  if ($mode=="copy"){
    try{
      $sql = "select * from mirae8440.eworks where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "결과가 없습니다.<br>";
     }else{
		 			  
			  $indate='';
			  $outworkplace=$row["outworkplace"];
			  
			  $steel_item=$row["steel_item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $supplier=$row["supplier"];
			  $request_comment=$row["request_comment"];
			  $which=$row["which"];	 	  			  
	  					
			 $outdate = date("Y-m-d"); // 요청일은 현재일로 세팅함
			
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 
     $titlemsg	= '(데이터 복사) 부자재 구매';	
	 $num='';	 
	 $id = $num;  
	 $parentid = $num;    
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
	<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
	<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >	
	<input type="hidden" id="steelitem" name="steelitem" >
	<input type="hidden" id="steelspec" name="steelspec" >
	<input type="hidden" id="steeltake" name="steeltake" >	
	
<?php if($chkMobile) { ?>	
<div class="container-fluid" >
<?php } if(!$chkMobile) { ?>	
<div class="container" >
<?php  } ?>	
	<div class="card">
	<div class="card-body">			   
	
	<div class="d-flex mb-5 mt-5 justify-content-center align-items-center"> 	
		<h4> <?=$titlemsg?> </h4> 
	</div>	
	
	<div class="row">
		<div class="col-sm-9">		   
			<div class="d-flex  mb-1 justify-content-start  align-items-center"> 		   
				<button id="saveBtn" type="button" class="btn btn-dark  btn-sm me-2"  > <i class="bi bi-floppy"></i> 저장  </button> 
			</div> 			
		</div> 	
		<div class="col-sm-3">	
				<div class="d-flex  mb-1 justify-content-end"> 	
				   <button class="btn btn-secondary btn-sm" onclick="self.close();"  > <i class="bi bi-x-lg"></i> 창닫기 </button>&nbsp;					
				</div> 			
		</div> 			
	</div> 
		  
	<?php	 		  
	 	 $aryreg=array();
		 if($which=='') $which='2';
		 
	     switch ($which) {
			case   "1"             : $aryreg[0] = "checked" ; break;
			case   "2"             :$aryreg[1] =  "checked" ; break;
			case   "3"             :$aryreg[2] =  "checked" ; break;
			default: break;
		}		 
			
 
	   ?>		  	   
	   	
  <div class="row mt-2">
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-5" >';
	  else
		  echo '<div class="col-sm-12" >';
	  ?>
	  
      <table class="table table-bordered">
        <tr >
        <td colspan="2" class="text-center " >
            <h4> (집행인 입력부분) </h4>
	   </td>
        </tr>
        <tr>
          <td>
            <label>진행상태</label>
          </td>
          <td>
            <span class="text-primary">요청</span> &nbsp;
            <input class="radioprogress" type="radio" <?=$aryreg[0]?> name="which" value="1"> &nbsp;&nbsp;
            <span class="text-danger">발주보냄</span> &nbsp;
            <input class="radioprogress" type="radio" <?=$aryreg[1]?> name="which" value="2"> &nbsp;&nbsp;
            <span class="text-dark">입고완료</span> &nbsp;
            <input class="radioprogress" type="radio" <?=$aryreg[2]?> name="which" value="3"> &nbsp;&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <label>구매방식</label>
          </td>
          <td>
		  	<?php	 		  
	 	 $aryreg=array();
		 if($payment=='') $which='법인카드';
	     switch ($payment) {
			case   "법인카드"             : $aryreg[0] = "checked" ; break;
			case   "세금계산서"             :$aryreg[1] =  "checked" ; break;			
			default: break;
		}		 		
 
	   ?>			  
		  
		<span class="text-primary">법인카드</span> &nbsp;
            <input type="radio" <?=$aryreg[0]?>  name="payment" value="법인카드">&nbsp;&nbsp;
            <span class="text-danger">세금계산서</span>&nbsp;
            <input type="radio" <?=$aryreg[1]?>  name="payment" value="세금계산서">&nbsp;&nbsp;
          </td>
        </tr>
	<tr>
		  <td>
			<label for="outdate">접수</label>
		  </td>          
			<?php
				// 예시: $registdate = '2024-01-25 16:28:23';

				// DateTime 객체 생성
				$dateTime = new DateTime($registdate);

				// 날짜 형식을 YYYY-MM-DD로 변환
				$formattedDate = $dateTime->format('Y-m-d');
				?>

				<td>
				<?php if($chkMobile) { ?>	
					<input type="date" class="form-control" id="registdate" name="registdate" value="<?=$formattedDate?>" style="width:200px;" >
				<?php } if(!$chkMobile) { ?>	
					<input type="date" class="form-control" id="registdate" name="registdate" value="<?=$formattedDate?>" style="width:100px;" >
				<?php  } ?>	
			
				</td>
					 
		</tr>
		<tr>
		  <td>
			<label for="outdate">납기(필요)</label>
		  </td>
		  <td>
				<?php if($chkMobile) { ?>	
					<input type="date" class="form-control" id="outdate" name="outdate" value="<?=$outdate?>" style="width:200px;" >
				<?php } if(!$chkMobile) { ?>	
					<input type="date" class="form-control" id="outdate" name="outdate" value="<?=$outdate?>" style="width:100px;" >
				<?php  } ?>			  
		  
			
		  </td>
		</tr>
		<tr>
		  <td>
			<label for="indate">입고완료</label>
          </td>
          <td>
				<?php if($chkMobile) { ?>	
					<input type="date" class="form-control" id="indate" name="indate" value="<?=$indate?>" style="width:200px;" >
				<?php } if(!$chkMobile) { ?>	
					<input type="date" class="form-control" id="indate" name="indate" value="<?=$indate?>" style="width:100px;" >
				<?php  } ?>	
          </td>
        </tr>
      </table>
    </div>
  
  <?php if($chkMobile===false)  
			echo '<div class="col-sm-7" >';
	  else
		  echo '<div class="col-sm-12" >';
	  ?>
      <table class="table table-bordered">
        <tr >
        <td colspan="2" class="text-center " >
            <h4> (요청인 입력부분) </h4>
		   </td>
        </tr>
        <tr>
           <td style="width:20%;">
            <label for="outworkplace">물품명</label>
          </td>
          <td>
            <input type="text" class="form-control" id="outworkplace" name="outworkplace" value="<?=$outworkplace?>" size="30" placeholder="구입물품명" autocomplete="off" required >
          </td>
        </tr>
        <tr>
          <td>
            <label for="spec">규격</label>
          </td>
          <td>
            <input type="text" class="form-control" id="spec" name="spec" value="<?=$spec?>" placeholder="규격" autocomplete="off">
          </td>
        </tr>
        <tr>
          <td>
            <label for="steelnum">수량</label>
          </td>
          <td>
            <input type="text" class="form-control" id="steelnum" name="steelnum" value="<?=$steelnum?>" style="width:20%;" placeholder="수량" autocomplete="off"  required >
          </td>
        </tr>
        <tr>
          <td>
            <label for="supplier">공급사</label>
          </td>
          <td>
            <input type="text" class="form-control" id="supplier" name="supplier" value="<?=$supplier?>" style="width:50%;" placeholder="공급사" autocomplete="off">
          </td>
        </tr>
        <tr>
          <td>
            <label for="request_comment">비고</label>
          </td>
          <td>
            <textarea class="form-control" rows="2" id="request_comment" name="request_comment" ><?=$request_comment?></textarea>
          </td>
        </tr>
      </table>
	  	  
    </div>
  </div>
  
   
	  <div class="d-flex mt-3 mb-1 justify-content-center">  	 			
			  <label  for="upfileimage" class="btn btn-outline-dark btn-sm ">  제품 사진 첨부 </label>	
					 <input id="upfileimage"  name="upfileimage[]" type="file" onchange="this.value" multiple accept=".gif, .jpg, .png" style="display:none">		
       </div>
		<div class="d-flex  mb-1 justify-content-center"> 
		   <div class="card justify-content-center ">	
				   <div class="card-body justify-content-center ">	
					   <div class="d-flex  mb-1 justify-content-center fs-3"> 
							<div id ="displayImage" class="row d-flex mt-3 mb-1 justify-content-center" style="display:none;">  	 		 					 
							</div>		
						  </div>		
					</div>   
			</div>   
		</div>   
	
	 </div>	  
		</div>	  
		</div>	  
		</div>	  
 </div>	  
		
</form>
	
<script>

$(document).ready(function(){
    // 모든 .radioprogress 요소를 가져옵니다
    let radios = document.querySelectorAll('.radioprogress');

    // 현재 날짜를 yyyy-mm-dd 형식으로 가져옵니다
    let currentDate = new Date().toISOString().substr(0, 10); 

    radios.forEach(function(radio) {
        radio.addEventListener('click', function() {
            let indateElement = document.getElementById('indate');

            if (this.value === '3') {  // 입고완료가 선택되었을 때
                indateElement.value = currentDate;
            } else {
                indateElement.value = '';  // 날짜를 비웁니다
            }
        });
    });
});

$(document).ready(function(){
		
	 $("#saveBtn").click(function(){ 
		// 필요한 값 설정
		$('#steelitem').val($('#steel_item').val());
		$('#steelspec').val($('#spec').val());
		$('#steeltake').val($('#company').val());
		
		// 조건 확인
		if($("#outworkplace").val() === '' || $("#steelnum").val()  === '' ) {
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

	function showWarningModal() {
		Swal.fire({                                    
			title: '등록 오류 알림',
			text: '현장명, 수량은 필수입력 요소입니다.',
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
				  // console.log('data :' , data);
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
					location.href = "view.php?num=" + data["num"];
				}, 1000);	
							
				}, 1000);						
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });		
			
	}	

});

		 
function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
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
								"<img id='image" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
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
							"<img id='image" + i + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
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




