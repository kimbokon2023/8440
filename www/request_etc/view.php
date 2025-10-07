<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json';	

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json';

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
$client->addScope(Google_Service_Drive::DRIVE);

// Google Drive 서비스 초기화
$service = new Google_Service_Drive($client);

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    return count($response->files) > 0 ? $response->files[0]->id : null;
}

  
 if(!isset($_SESSION["level"]) || $level>=8) {         
		 sleep(1);
		 header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }   
      
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
  
<title> 부자재 구매 </title>

</head>

<style>

.show {display:block} /*보여주기*/

.hide {display:none} /*숨기기*/

</style>


<style>

 td, th, tr, span, input {
    vertical-align: middle;
  }

</style>	
 
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'; ?>
   
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
   
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

   if(isset($_REQUEST["page"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $page=$_REQUEST["page"];
  else
   $page=1;   

  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];

  if(isset($_REQUEST["separate_date"]))   //출고일 완료일
	 $separate_date=$_REQUEST["separate_date"];	 
      
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();
    
$id = $num;  
$parentid = $num;  

// 첨부 이미지 있는 것 불러오기 
$picData=array(); 
$tablename='goods';
$item = 'image';

// 파일 확인 및 Google Drive 정보 가져오기
$sql = "SELECT * FROM {$DB}.picuploads WHERE item = ? AND parentnum = ?";
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$item, $num]);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"];

        if (preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            // Google Drive 파일 ID로 처리
            $fileId = $picname;

            try {
                // Google Drive 파일 정보 가져오기
                $file = $service->files->get($fileId, ['fields' => 'webViewLink, thumbnailLink']);
                $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                $webViewLink = $file->webViewLink;
                $picData[] = ['thumbnail' => $thumbnailUrl, 'link' => $webViewLink, 'fileId' => $fileId];
            } catch (Exception $e) {
                error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                $picData[] = ['thumbnail' => "https://drive.google.com/uc?id=$fileId", 'link' => null, 'fileId' => $fileId];
            }
        } else {
            // Google Drive에서 파일 이름으로 검색
            try {
                $query = sprintf("name='%s' and trashed=false", addslashes($picname)); // 파일 이름으로 검색
                $response = $service->files->listFiles([
                    'q' => $query,
                    'fields' => 'files(id, webViewLink, thumbnailLink)',
                    'pageSize' => 1
                ]);

                if (count($response->files) > 0) {
                    $file = $response->files[0];
                    $fileId = $file->id; // 검색된 파일의 ID
                    $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";					
                    $webViewLink = $file->webViewLink;
                    $picData[] = ['thumbnail' => $thumbnailUrl, 'link' => $webViewLink, 'fileId' => $fileId];

                    // 데이터베이스 업데이트: 검색된 파일 ID 저장
                    $updateSql = "UPDATE {$DB}.picuploads SET picname = ? WHERE item = ? AND parentnum = ? AND picname = ?";
                    $updateStmh = $pdo->prepare($updateSql);
                    $updateStmh->execute([$fileId, $item, $num, $picname]);
                } else {
                    error_log("Google Drive에서 파일을 찾을 수 없습니다: " . $picname);
                    $picData[] = ['thumbnail' => null, 'link' => null, 'fileId' => null];
                }
            } catch (Exception $e) {
                error_log("Google Drive 파일 검색 실패: " . $e->getMessage());
                $picData[] = ['thumbnail' => null, 'link' => null, 'fileId' => null];
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$picNum = count($picData);

// PHP 배열을 JSON으로 인코딩 - 추후 삭제를 위한 코드
$picData_json = json_encode($picData);   

     try{
      $sql = "select * from mirae8440.eworks where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
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

if($steelnum=='' or $steelnum==null )	
   $steelnum = 1 ; 
?>
 
<form  id="board_form" name="board_form" method="post"  onkeydown="return captureReturnKey(event)" > 
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?=$upfilename?>" >			  								
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		

<?php if($chkMobile) { ?>	
<div class="container-fluid mt-2 mb-2"  >
<?php } if(!$chkMobile) { ?>	
<div class="container mt-2 mb-2"  >   
<?php  } ?>		    
    
    <div class="card">
       <div class="card-body ">
       <div class="row">
			<div class="col-sm-8">
				<div class="d-flex mb-5 mt-5 justify-content-center align-items-center ">
					<h4> 부자재 구매 </h4>
				</div>
			</div>
			
	   <div class="col-sm-4">		
	<?php
				//var_dump($al_part);			

				$al_part=='지원파트';
               if($e_confirm ==='' || $e_confirm === null) 
			   {
					$formattedDate = date("m/d", strtotime($registdate)); // 월/일 형식으로 변환
					// echo $formattedDate; // 출력
					
					if($al_part=='제조파트')
					{
						$approvals = array(
							array("name" => "공장장 이경묵", "date" =>  $formattedDate),
							array("name" => "대표 소현철", "date" =>  $formattedDate),
							// 더 많은 결재권자가 있을 수 있음...
						);	
					}
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

					foreach($approver_ids as $index => $id) {
						if (isset($approver_details[$index])) {
							// Use regex to match the pattern (name title date time)
							// The pattern looks for any character until it hits a series of digits that resemble a date followed by a time
							preg_match("/^(.+ \d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})$/", $approver_details[$index], $matches);

							// Ensure that the full pattern and the two capturing groups are present
							if (count($matches) === 3) {
								$nameWithTitle = $matches[1]; // This is the name and title
								$time = $matches[2]; // This is the time
								$date = substr($nameWithTitle, -10); // Extract date from the end of the 'nameWithTitle' string
								$nameWithTitle = trim(str_replace($date, '', $nameWithTitle)); // Remove the date from the 'nameWithTitle' to get just the name and title
								$formattedDate = date("m/d H:i:s", strtotime("$date $time")); // Combining date and time

								$approvals[] = array("name" => $nameWithTitle, "date" => $formattedDate);
							}
						}
					}


					// // Now $approvals contains the necessary details
					// foreach ($approvals as $approval) {
						// echo "Approver: " . $approval['name'] . ", Date: " . $approval['date'] . "<br>";
					// }
			   }					
				
				if($status === 'end' and ($e_confirm !=='' && $e_confirm !== null) )
				  {
				?>				
				
					<div class="container mb-2">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th colspan="<?php echo count($approvals); ?>" class="text-center fs-5">결재</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($approvals as $approval) { ?>
										<td class="text-center fs-5" style="height: 60px;"><?php echo $approval["name"]; ?></td>
									<?php } ?>
								</tr>
								<tr>
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
									<th colspan="<?php echo count($approvals); ?>" class="text-center fs-5">결재 진행 전</th>
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
 

       <div class="row">
		<?php if($chkMobile) { ?>	
		  <div class="col-sm-12">
		<?php } if(!$chkMobile) { ?>	
		  <div class="col-sm-7">
		<?php  } ?>			 		   
				<div class="d-flex  justify-content-start"> 							
					<?php if($chkMobile==true)	{ ?>
						<button class="btn btn-dark btn-sm" onclick="location.href='list.php'" > <i class="bi bi-card-list"></i> 목록  </button>&nbsp;	
					<?php } ?>						
						<button type="button"   class="btn btn-dark btn-sm" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>'" >  <i class="bi bi-pencil-square"></i>  수정 </button> &nbsp;
					<?php if($user_id === $author_id || $admin)	{ ?>
							<button type="button"   class="btn btn-danger btn-sm" onclick="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>')" > <i class="bi bi-trash"></i>  삭제  </button>	 &nbsp;
					<?php } ?>									
						<button type="button"   class="btn btn-dark btn-sm" onclick="location.href='write_form.php'" > <i class="bi bi-pencil"></i>  신규 </button>		&nbsp;										
						<button type="button"   class="btn btn-primary btn-sm" onclick="location.href='write_form.php?mode=copy&num=<?=$num?>'" > <i class="bi bi-copy"></i> 복사 </button>	&nbsp;
					<?php
					  if($which !=='3')
					  {				  
					  ?>			
						<button type="button"   id="inputdoneAndMove"  class="btn btn-success  btn-sm "> <i class="bi bi-kanban"></i> 당일 입고완료 </button>	&nbsp;
					  <?php } ?>				
				 </div> 			
			 </div> 			
		<?php if($chkMobile) { ?>	
		  <div class="col-sm-12">
		<?php } if(!$chkMobile) { ?>	
		  <div class="col-sm-5 text-end">
		<?php  } ?>	
			<div class="d-flex  mb-1 justify-content-end"> 	
				<button class="btn btn-secondary btn-sm" id="closeBtn" >  <i class="bi bi-x-lg"></i> 닫기 </button>&nbsp;									
			</div> 					 
	 </div> 
	 </div> 
 
	   
	   	
<div class="row mt-2">
	<?php if($chkMobile===false)  
	echo '<div class="col-sm-5" >';
	else
	  echo '<div class="col-sm-12" >';
	?>
	  
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
            <span class="text-primary">요청</span>&nbsp;
            <input type="radio" <?=$aryreg[0]?> name="which" value="1">&nbsp;&nbsp;
            <span class="text-danger">발주보냄</span>&nbsp;
            <input type="radio" <?=$aryreg[1]?> name="which" value="2">&nbsp;&nbsp;
            <span class="text-dark">입고완료</span>&nbsp;
            <input type="radio" <?=$aryreg[2]?> name="which" value="3">&nbsp;&nbsp;
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
            <input type="radio" <?=$aryreg[0]?> name="payment" value="법인카드">&nbsp;&nbsp;
            <span class="text-danger">세금계산서</span>&nbsp;
            <input type="radio" <?=$aryreg[1]?> name="payment" value="세금계산서">&nbsp;&nbsp;
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
            <input type="text" class="form-control" id="outworkplace" name="outworkplace" value="<?=$outworkplace?>" autocomplete="off">
          </td>
        </tr>
        <tr>
          <td>
            <label for="spec">규격</label>
          </td>
          <td>
            <input type="text" class="form-control" id="spec" name="spec" value="<?=$spec?>" >
          </td>
        </tr>
        <tr>
          <td>
            <label for="steelnum">수량</label>
          </td>
          <td>
            <input type="text" class="form-control" id="steelnum" name="steelnum" value="<?=$steelnum?>" style="width:20%;" autocomplete="off">
          </td>
        </tr>
        <tr>
          <td>
            <label for="supplier">공급사</label>
          </td>
          <td>
            <input type="text" class="form-control" id="supplier" name="supplier" value="<?=$supplier?>" style="width:50%;" autocomplete="off">
          </td>
        </tr>
        <tr>
          <td>
            <label for="request_comment">비고</label>
          </td>
          <td>
            <textarea class="form-control" rows="2" id="request_comment" name="request_comment" readonly ><?=$request_comment?></textarea>
          </td>
        </tr>
        <tr>
			<?php
			$update_log_extract = substr($update_log, 0, 31);
			$update_log_extract_br = str_replace("&#10;", "<br>", $update_log_extract);
			?>
			<td colspan="2">
				등록 : <?=$first_writer?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<br> 최종수정 : <?=$update_log_extract_br?> &nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-outline-dark btn-sm" id="showlogBtn">
					Log 기록
				</button>
			</td>

        </tr>		
		
      </table>
    </div>
  </div>
</div>
  <div class="row">	 
  <div class="d-flex justify-content-center  mt-3">	 
    <div class="card justify-content-center " style="width:60%;">
     <div class="card-title justify-content-center ">
	 	<div class="d-flex  justify-content-center fs-3"> 
		  <label for="upfile" class="form-label"> <h4 class="text-center"> 제품 사진 </h4> </label>
		</div>
	 </div>
       <div class="card-body justify-content-center ">	
	   <div class="d-flex  mb-1 justify-content-center fs-3"> 
			<div id ="displayPicture" class="row d-flex mt-3 mb-1 justify-content-center" style="display:none;">  	 		 					 
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
	</div> 
	</div> 
	</div> 
</form>

  <form id="Form1" name="Form1">
		<input type="hidden" id="num" name="num" value="<?=$num?>" >
		<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>" >
  </form>  	
		
 </div>

  

<script>
$(document).ready(function(){
		
	$('#closeBtn').click(function() {
		window.close(); // 현재 창 닫기
	});
	
    // Select input, textarea, and radio elements
    $("input, textarea").each(function() {

        $(this).prop('readonly', true);

        $(this).css('background-color', '#f5f5f5');
    });	

	// Log 파일보기
	$("#showlogBtn").click( function() {     	
		var num = '<?php echo $num; ?>' 
		// table 이름을 넣어야 함
		var workitem =  'eworks' ;
		// 버튼 비활성화
		var btn = $(this);						
			popupCenter("../Showlog.php?num=" + num + "&workitem=" + workitem , '로그기록 보기', 500, 400);									 
		btn.prop('disabled', false);					 					 
	});			


			
	   // 당일입고처리 및 DB이관
		$("#inputdoneAndMove").click(function(){   	
		var num = <?php echo $num; ?> ; 
		  
		  $('#num').val(num);
		   
		   console.log($("#Form1").serialize());
		   
			$.ajax({
				url: "movetosteel.php",
				type: "post",		
				data: $("#Form1").serialize(),
				dataType:"json",
				success : function( data ){
					setTimeout(function(){									
						if (window.opener && !window.opener.closed) {
								// 부모 창에 restorePageNumber 함수가 있는지 확인
								if (typeof window.opener.restorePageNumber === 'function') {
									window.opener.restorePageNumber(); // 함수가 있으면 실행
								}
									window.opener.location.reload(); // 부모 창 새로고침
								}							
							 location.href = "view.php?num=" + data["num"];
					}, 1000);		
					
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
				} 			      		
			   });	// end of ajax	
			   
          // 입고완료 라디오버튼 체크
		  $("input:radio[name='which']:radio[value='3']").prop('checked', true); 	   
		  // 입고일 화면 변경
		   $('#indate').val(getCurrentDate()); 

		  tmp='당일입고 처리 완료';
		  
		  $('#alertmsg').html(tmp); 
		  
		  $('#myModal').modal('show'); 

			   
		 });	// end of button click
	
});

// 현재날짜 구하기
function getCurrentDate()
{
	var date = new Date();
	var year = date.getFullYear().toString();

	var month = date.getMonth() + 1;
	month = month < 10 ? '0' + month.toString() : month.toString();

	var day = date.getDate();
	day = day < 10 ? '0' + day.toString() : day.toString();

   // return year + month + day ;
   return year + '-'+ month + '-'+ day ;
}


function del(href) {
    var level = Number($('#session_level').val());
    if (level > 2) {
        // 관리자 권한이 필요한 경우
        Swal.fire({
            title: '관리자 권한 필요',
            text: "삭제하려면 관리자에게 문의해 주세요.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
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
                // 이미지 파일 삭제 처리
                var saveimagename_arr = <?php echo $picData_json; ?>;
                saveimagename_arr.forEach(function(savename) {
                    $.ajax({
                        url: '../file/del_file.php',
                        type: 'post',
                        data: { savename: savename },
                        dataType: 'json'
                    }).done(function(response) {
                        console.log("Deleted image: " + response.savename);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.log("Error deleting image: " + textStatus);
                    });
                });

                // 데이터베이스에서의 기록 삭제
                $.ajax({
                    url: 'delete.php',
                    type: 'post',
                    data: $("#Form1").serialize(),
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
                        $('#closeBtn').click();
                    }, 1000);
                });
            }
        });
    }
}

  
function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트
    }
}
function Enter_CheckTel(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_searchTel();  // 실행할 이벤트
    }
}


function exe_search()
{
      var postData = changeUri(document.getElementById("outworkplace").value);
      var sendData = $(":input:radio[name=root]:checked").val();

      $("#displaysearch").show();
	 if(sendData=='주일')
         $("#displaysearch").load("./search.php?mode=search&search=" + postData);
	 if(sendData=='경동') 
         $("#displaysearch").load("./searchkd.php?mode=search&search=" + postData);	  
}

function send_alert() {   // 알림을 서버에 저장
 
var tmp; 				
		
	tmp="../save_alert.php?ma_alert=1";	
		
    $("#vacancy").load(tmp);      
	
    alertify.alert('발주서 전송 알림창', '<h1> 발주서가 전송되었습니다. </h1>'); 	

 }      


function exe_searchTel()
{
	  var postData =  changeUri(document.getElementById("receiver").value);
      $("#displaysearchworker").show();
      $("#displaysearchworker").load("./workerlist.php?mode=search&search=" + postData);
}

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}
 	 
</script> 
		
<script>

$(document).ready(function () {
    // 모달 닫기 버튼
    $("#closeModalBtn").click(function () {
        $('#myModal').modal('hide');
    });

    // 초기값 설정
    $("#pInput").val('50'); // 최초 화면 사진 파일 보여주기

    // 2초 간격으로 사진 업데이트 체크
    let timer3 = setInterval(() => {
        if ($("#pInput").val() == '100') {
            displayPicture(); // 새로운 사진 로드
        }
        if ($("#pInput").val() == '50') {
            displayPictureLoad(); // 기존 사진 로드
        }
    }, 2000);

    // 파일 선택 시 업로드 처리
    $("#upfile").change(function (e) {
		if (this.files.length === 0) {
			// 파일이 선택되지 않았을 때
			console.warn("파일이 선택되지 않았습니다.");
			return;
		}	
        FileProcess();
    });

    // 파일 업로드 처리 함수
    function FileProcess() {
        const num = $("#num").val();
        const form = $('#board_form')[0];
        const data = new FormData(form);

        // 추가 데이터 설정
        data.append("tablename", "goods");
        data.append("item", "image");
        data.append("folderPath", "uploads");
        data.append("DBtable", "picuploads");

        console.log("업로드 파일:", $('#upfile').val());

        // 모달 표시
        const tmp = '사진을 저장 중입니다. 잠시만 기다려주세요.';
        $('#alertmsg').html(tmp);
        $('#myModal').modal('show');

        // AJAX 요청
        $.ajax({
            enctype: "multipart/form-data",
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

                // 업로드 결과 알림
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

                // 모달 닫기
                setTimeout(function () {
                    $('#myModal').modal('hide');
                }, 1000);

                $("#pInput").val('100'); // 업데이트 표시
            },
            error: function (jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
            },
        });
    }
});

// 새로운 사진 불러오기
function displayPicture() {
    $('#displayPicture').show();
    var params = $("#num").val();

    $.ajax({
        url: '/filedrive/fileprocess.php',
        type: 'GET',
        data: {
            num: params,
            tablename: 'goods',
            item: 'image',
            folderPath: 'uploads',
        },
        dataType: 'json',
    }).done(function (data) {
        console.log("사진 데이터:", data);

        $("#displayPicture").html(''); // 기존 내용 초기화
        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function (picData, index) {
                var thumbnail = picData.thumbnail || '/assets/default-thumbnail.png';
                var link = picData.link || '#';
                var fileId = picData.fileId || null;

                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + index, picData);
                    return; // fileId가 없으면 해당 항목 건너뛰기
                }

                // 결과를 화면에 추가
                $("#displayPicture").append(
                    "<div class='d-flex justify-content-center mt-2 mb-1'>" +
                        "<a href='" + link + "' target='_blank'>" +
                            "<img id='pic" + index + "' src='" + thumbnail + "' style='width:550px; height:auto;'>" +
                        "</a>" +
                    "</div>" +
                    "<div class='d-flex justify-content-center'>" +
                        "<button type='button' class='mb-3 text-center btn btn-danger btn-lg' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                            "<ion-icon name='trash-outline'></ion-icon>" +
                        "</button>" +
                    "</div>"
                );
            });
        } else {
            $("#displayPicture").append(
                "<div class='text-center text-muted'>등록된 사진이 없습니다.</div>"
            );
        }

        $("#pInput").val(''); // 입력 초기화
    }).fail(function (error) {
        console.error("사진 불러오기 오류:", error);
        alert("사진을 불러오는 중 문제가 발생했습니다.");
    });
}

// 기존 사진 로드
function displayPictureLoad() {
    $('#displayPicture').show();
    var picNum = "<?php echo $picNum; ?>";
    var picData = <?php echo json_encode($picData); ?>;

    $("#displayPicture").html('');
    picData.forEach(function(pic, index) {
        var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
        var link = pic.link || '#';
        var fileId = pic.fileId || null;

        if (!fileId) {
            console.error("fileId가 누락되었습니다. index: " + index, pic);
            return; // fileId가 없으면 해당 항목 건너뛰기
        }

        $("#displayPicture").append(
            "<div class='d-flex justify-content-center mt-2 mb-1'>" +
                "<a href='" + link + "' target='_blank'>" +
                    "<img id='pic" + index + "' src='" + thumbnail + "' style='width:550px; height:auto;'>" +
                "</a>" +
            "</div>" +
            "<div class='d-flex justify-content-center'>" +
                "<button type='button' class='mb-3 text-center btn btn-danger btn-lg' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                    "<ion-icon name='trash-outline'></ion-icon>" +
                "</button>" +
            "</div>"
        );
    });
    $("#pInput").val('');
}


// 파일 삭제 처리 함수
function delPicFn(divID, fileId) {
    if (confirm("삭제한 사진은 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'DELETE',
            data: JSON.stringify({
                fileId: fileId,
                tablename: "goods",
                item: "image",
                folderPath: "uploads",
                DBtable: "picuploads"
            }),
            contentType: "application/json",
            dataType: 'json',
        }).done(function (response) {
            if (response.status === 'success') {
                console.log("삭제 완료:", response);
                $("#pic" + divID).remove(); // 그림 삭제
                $("#delPic" + divID).remove(); // 버튼 삭제

                Toastify({
                    text: "사진이 성공적으로 삭제되었습니다.",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#f44336",
                }).showToast();
            } else {                
                console.log(response.message);
            }
        }).fail(function (error) {
            console.error("삭제 중 오류:", error);
            alert("파일 삭제 중 문제가 발생했습니다.");
        });
    }
}

</script>

</body>
 </html>
