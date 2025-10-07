<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
require_once getDocumentRoot() . '/vendor/autoload.php';
require_once(includePath('lib/mydb.php'));

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';

// Google Drive 클라이언트 설정  
// require_once getDocumentRoot() . '/vendor/autoload.php';에서 가져옴
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

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }  

include getDocumentRoot() . '/load_header.php';

  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
	$mode=$_REQUEST["mode"];
  else
	$mode="";

if($mode === 'copy')
	$title_message = "(데이터복사) 본천장&조명천장 수주내역" ;
else if($mode === 'split')  
	$title_message = "(분할 & 복사) 본천장&조명천장 수주내역" ;
else
	$title_message = "본천장&조명천장 수주내역" ;
 ?> 
<script src="../js/calinseung.js"></script>    
<title> <?=$title_message?> </title>
</head>

<body>

<style>
.table, td, input {
  font-size: 13px !important;
  vertical-align: middle;
	padding: 2px; /* 셀 내부 여백을 5px로 설정 */
	border-spacing: 2px; /* 셀 간 간격을 5px로 설정 */
	text-align:center;
}
	
  .table td input.form-control, textarea.form-control  {
    height: 25px;
    border: 1px solid #392f31; /* 테두리 스타일 추가 */
    border-radius: 4px; /* 테두리 라운드 처리 */
  }
  
  /* textarea 자동 높이 조절을 위한 스타일 */
  textarea.form-control {
    min-height: 40px;
    max-height: 300px;
    overflow-y: auto;
    resize: vertical;
    transition: height 0.2s ease;
  }	
  
    input[type="checkbox"] {
    transform: scale(1.6); /* 크기를 1.5배로 확대 */
    margin-right: 10px;   /* 확대 후의 여백 조정 */
}

.tooltip-inner {
    background-color: black !important; /* 배경색 */
    color: white !important; /* 글자색 */
}
.tooltip-arrow {
    color: black !important; /* 화살표 색상 */
}

/* 외주가공 메모 말풍선 스타일 */ 
.outsourcing-memo-bubble {
    position: absolute;
    top: 50%;
    left: 100%;
    transform: translateY(-50%);
    margin-left: 15px;
    z-index: 1000;
    animation: slideInRight 0.3s ease-out;
}

/* 반응형 디자인 */
@media (max-width: 768px) {
    .outsourcing-memo-bubble {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin-left: 0;
        width: 90%;
        max-width: 350px;
    }
    
    .bubble-content::before {
        display: none;
    }
}

.bubble-content {
    background: linear-gradient(135deg,rgb(86, 193, 219) 0%,rgb(35, 173, 197) 100%);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    min-width: 300px;
    max-width: 400px;
    position: relative;
}

.bubble-content::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-right: 8px solid rgb(70, 188, 235);
}

.bubble-header {
    background: rgba(255, 255, 255, 0.1);
    padding: 12px 15px;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.bubble-title {
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.bubble-close {
    background: none;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.bubble-close:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.bubble-body {
    padding: 15px;
}

.bubble-textarea {
    border: none;
    border-radius: 8px;
    resize: vertical;
    min-height: 80px;
    font-size: 13px;
    background: rgba(255, 255, 255, 0.95);
    transition: all 0.2s;
}

.bubble-textarea:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3);
    background: white;
}

.bubble-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.bubble-footer small {
    color: rgba(255, 255, 255, 0.8);
    font-size: 11px;
}

.bubble-footer .btn {
    padding: 4px 12px;
    font-size: 12px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: all 0.2s;
}

.bubble-footer .btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateY(-50%) translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }
    to {
        opacity: 0;
        transform: translateY(-50%) translateX(-20px);
    }
}

.bubble-hide {
    animation: slideOutRight 0.2s ease-in forwards;
}
	  
  </style> 
  
<?   
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";  

  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  

$pdo = db_connect();	
 
$URLsave = "https://8440.co.kr/ceilingloadpic.php?num=" . $num;  

	// 첨부 파일(엑셀파일) 에 대한 읽어오는 부분
	require_once(includePath('lib/mydb.php'));
	$pdo = db_connect();
	 
	// 첨부파일 있는 것 불러오기 
	$savefilename_arr=array(); 
	$realname_arr=array(); 
	$attach_arr=array(); 
	$tablename='ceiling';
	$item = 'ceiling';

  
  if ($mode=="modify"){
    try{
		  $sql = "select * from mirae8440.ceiling where num = ? ";
		  $stmh = $pdo->prepare($sql); 

		$stmh->bindValue(1,$num,PDO::PARAM_STR); 
		$stmh->execute();
		$count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
	  
	  include "_rowDB.php";
	  
      $item_file_0 = $row["file_name_0"];
      $item_file_1 = $row["file_name_1"];

      $copied_file_0 = "../uploads/". $row["file_copied_0"];
      $copied_file_1 = "../uploads/". $row["file_copied_1"];
	 }
				   

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }
  
if ($mode !== "modify" and $mode !== "copy" and $mode !== "split"  ) {
    // 수정모드가 아닐 때, 즉 신규 자료일 때 변수 초기화
    $initialValues = array(
        "orderday" => date("Y-m-d"),
        "checkstep" => "",
        "workplacename" => "",
        "address" => "",
        "firstord" => "",
        "firstordman" => "",
        "firstordmantel" => "",
        "secondord" => "",
        "secondordman" => "",
        "secondordmantel" => "",
        "chargedman" => "",
        "measureday" => "",
        "drawday" => "",
        "deadline" => "",
        "startday" => "",
        "testday" => "",
        "hpi" => "",
        "workday" => "",
        "worker" => "",
        "endworkday" => "",
        "material1" => "",
        "material2" => "",
        "material3" => "",
        "material4" => "",
        "material5" => "",
        "material6" => "",
        "widejamb" => "",
        "normaljamb" => "",
        "smalljamb" => "",
        "memo" => "",
        "update_day" => "",
        "regist_day" => "",
        "delivery" => "",
        "delicar" => "",
        "delicompany" => "",
        "delipay" => "",
        "delimethod" => "",
        "demand" => "",
        "update_log" => "",
        "type" => "",
        "inseung" => "",
        "su" => "",
        "bon_su" => "",
        "lc_su" => "",
        "etc_su" => "",
        "air_su" => "",
        "car_insize" => "",
        "order_com1" => "",
        "order_text1" => "",
        "order_com2" => "",
        "order_text2" => "",
        "order_com3" => "",
        "order_text3" => "",
        "order_com4" => "",
        "order_text4" => "",
        "lc_draw" => "",
        "etc_draw" => "",
        "lclaser_com" => "",
        "lclaser_date" => "",
        "lcbending_date" => "",
        "lcwelding_date" => "",
        "lcpainting_date" => "",
        "lcassembly_date" => "",
        "main_draw" => "",
        "eunsung_make_date" => "",
        "eunsung_laser_date" => "",
        "mainbending_date" => "",
        "mainwelding_date" => "",
        "mainpainting_date" => "",
        "mainassembly_date" => "",
        "etclaser_date" => "",
        "etcbending_date" => "",
        "etcwelding_date" => "",
        "etcpainting_date" => "",
        "etcassembly_date" => "",
        "memo2" => "",
        "order_date1" => "",
        "order_date2" => "",
        "order_date3" => "",
        "order_date4" => "",
        "order_input_date1" => "",
        "order_input_date2" => "",
        "order_input_date3" => "",
        "order_input_date4" => "",
        "outsourcing" => "",        
        "cabledone" => "",
		"designer" => "",
		"outsourcing_memo" => ""
    );

    foreach ($initialValues as $key => $value) {
        $$key = $value;
    }
}

  if ($mode=="copy" or $mode=='split'){
    try{
      $sql = "select * from mirae8440.ceiling where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
      $item_file_0 = $row["file_name_0"];
      $item_file_1 = $row["file_name_1"];

      $copied_file_0 = "../uploads/". $row["file_copied_0"];
      $copied_file_1 = "../uploads/". $row["file_copied_1"];
	 }
    
	include '_rowDB.php';
            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];	
  				
		      $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lclaser_date=trans_date($lclaser_date);
		      $lcbending_date=trans_date($lcbending_date);
		      $lcwelding_date=trans_date($lcwelding_date);
		      $lcpainting_date=trans_date($lcpainting_date);
		      $lcassembly_date=trans_date($lcassembly_date);		      
		      $eunsung_make_date=trans_date($eunsung_make_date);			
		      $eunsung_laser_date=trans_date($eunsung_laser_date);			
		      $mainbending_date=trans_date($mainbending_date);			
		      $mainwelding_date=trans_date($mainwelding_date);			
		      $mainpainting_date=trans_date($mainpainting_date);			
		      $mainassembly_date=trans_date($mainassembly_date);	
		      $etclaser_date=trans_date($etclaser_date);			
		      $etcbending_date=trans_date($etcbending_date);			
		      $etcwelding_date=trans_date($etcwelding_date);			
		      $etcpainting_date=trans_date($etcpainting_date);			
		      $etcassembly_date=trans_date($etcassembly_date);		
			  
		      $order_date1=trans_date($order_date1);					   
		      $order_date2=trans_date($order_date2);					   
		      $order_date3=trans_date($order_date3);					   
		      $order_date4=trans_date($order_date4);					   
		      $order_input_date1=trans_date($order_input_date1);					   
		      $order_input_date2=trans_date($order_input_date2);					   
		      $order_input_date3=trans_date($order_input_date3);					   
		      $order_input_date4=trans_date($order_input_date4);					  
			  
            $designer ="";
			$deadline = null;
			$lc_draw = null;
			$etc_draw = null;
			$main_draw = null;
			
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	// 자료번호 초기화 
	$num = 0;		 
	 
  }
    
   $material_arr = array('','304 Hair Line 1.2T','304 HL 1.2T','304 Mirror 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)',  '기타' );
			 
?>


<form id="board_form" name="board_form" method="post"  onkeydown="return captureReturnKey(event)"  >	
		      
<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
<input type="hidden" id="num" name="num" value="<?=$num?>"  >
<input type="hidden" id="mode" name="mode" value="<?=$mode?>"  >


<div class="container-fluid">	  
<div class="card">	  
<div class="card-body">	  

   
<div class='bigPictureWrapper'>
	<div class='bigPicture'>
	</div>	   
</div>   
   	
<div class="row mt-1 align-items-center"> 		
<div class="col-sm-1"> 		
</div> 		
<div class="col-sm-10"> 		  
<div class="d-flex justify-content-start align-items-center mt-3 mb-2 ">		
	<span class="fs-5 me-5">  <?=$title_message?> </span>           
    <!-- 이 부분 조회 화면과 다름 -->   	
  	   <button id="saveBtn" type="button" class="btn btn-dark  btn-sm me-2"  > <i class="bi bi-floppy-fill"></i> 저장 </button> 	   	
	</div>
	</div>
	<div class="col-sm-1 text-end"> 	
		<button class="btn btn-secondary btn-sm" onclick="self.close();" > <i class="bi bi-x-lg"></i> 닫기 </button>&nbsp;	
	</div> 			 
</div> 
<div class="d-flex justify-content-center mb-1 mt-1"> 	
	<div class="col-sm-5 p-1 rounded" style=" border: 1px solid #392f31; " > 	
		<table class="table table-reponsive">
		 
		  <tbody>
			<tr>
			  <td colspan="1">현장명</td>
			  <td colspan="4"><input type="text" id="workplacename" name="workplacename" value="<?=$workplacename?>" class="form-control"  style="text-align: left;"  required></td>
			  <td colspan="1" >
				<h5> <span class="badge bg-success ">외주가공</span>  </h5>
			  </td>						  
			</tr>
			<tr>
			  <td>주소</td>
			  <td colspan="4"><input type="text" id="address" name="address" value="<?=$address?>" class="form-control"  style="text-align: left;"  onkeydown="if(event.keyCode == 13) { addressBtn('address'); }" ></td>
			  <td colspan="1">
				<div class="d-flex align-items-center position-relative ms-2">
					<input type="checkbox" 
						   id="outsourcing" 
						   name="outsourcing" 
						   value="외주"											      
						   <?php echo ($outsourcing === '외주') ? 'checked' : ''; ?> >
					<label for="outsourcing" class="ms-2 mb-0">외주 메모</label>
					
					<!-- 말풍선 UI -->
					<div id="outsourcing-memo-bubble" class="outsourcing-memo-bubble" style="display: none;">
						<div class="bubble-content">
							<div class="bubble-header">
								<span class="bubble-title">외주가공 메모</span>
								<button type="button" class="bubble-close" onclick="hideOutsourcingMemo()">
									<i class="bi bi-x"></i>
								</button>
							</div>
							<div class="bubble-body">
								<textarea id="outsourcing_memo" 
										  name="outsourcing_memo" 
										  class="form-control bubble-textarea" 
										  placeholder="외주가공 관련 메모를 입력하세요..."
										  maxlength="200"><?=htmlspecialchars($outsourcing_memo ?? '')?></textarea>
								<div class="bubble-footer">
									<small class="text-muted">
										<span id="memo-char-count">0</span>/200
									</small>
									<button type="button" class="btn btn-primary btn-sm" onclick="saveOutsourcingMemo()">
										<i class="bi bi-check"></i> 저장
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			  </td>					  
			</tr>
			<tr>
			 <td class="col" style="width:10%;" > 원청</td>
				  <td class="col" >   
					<input type="text" id="firstord" name="firstord" value="<?=$firstord?>" class="form-control"></td>
				  <td class="col" style="width:10%;" > 담당</td>
				  <td class="col" style="width:20%;" >   
					  <input type="text" id="firstordman" name="firstordman" size="11" value="<?=$firstordman?>" onkeydown="if(event.keyCode == 13) { phonebookBtn('firstord','firstordman', 'firstordmantel','true'); }">
					  <button type="button" class="btn btn-dark-outline btn-sm" onclick="phonebookBtn('firstord', 'firstordman', 'firstordmantel', 'false');">
						<i class="bi bi-gear-fill"></i>
					  </button>
				  </td>
				  <td class="col" style="width:10%;" > Tel</td>
				  <td class="col"  > 
				  <input type="text" id="firstordmantel" name="firstordmantel" value="<?=$firstordmantel?>" class="form-control"></td>
				  <td> </td>
			</tr>
			<tr>
			  <td class="col">발주처</td>
			  <td class="col"><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" class="form-control"></td>
			  <td class="col">담당</td>
			  <td class="col" style="width:20%;" >    
				  <input type="text" id="secondordman" name="secondordman" size="11" value="<?=$secondordman?>" onkeydown="if(event.keyCode == 13) { phonebookBtn('secondord', 'secondordman', 'secondordmantel','true'); }">
				  <button type="button" class="btn btn-dark-outline btn-sm" onclick="phonebookBtn('secondord', 'secondordman','secondordmantel','false');"> 
					
				  <i class="bi bi-gear-fill"></i></button>
			  </td>
			  <td class="col">Tel</td>
			  <td class="col"><input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" class="form-control"></td>
			  <td> </td>
			</tr>
			<tr>			  
			  <td>현장담당</td>
			  <td><input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control" onkeydown="JavaScript:Enter_chargedman_Check();"></td>
			  <td class="col">Tel</td>
			  <td class="col"><input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control"></td>			  
			  <td>설계자</td>
			  <td class="text-center">
			  <select name="designer" id="designer" class="form-select d-block w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
			   <?php		 
			   $designer_arr = [];
					   array_push($designer_arr,' ','안현섭','이미래','이소정','소민지','조경임','김선영');
					   $mat_count = sizeof($designer_arr);
					   
				   for($i=0;$i<$mat_count;$i++) {
							 if($designer==$designer_arr[$i])
										print "<option selected value='" . $designer_arr[$i] . "'> " . $designer_arr[$i] .   "</option>";
								 else   
						   print "<option value='" . $designer_arr[$i] . "'> " . $designer_arr[$i] .   "</option>";
					   } 	
					   ?>
			</tr>
		<!-- New Table Rows -->
		<tr>
		  <td>접수일</td>
		  <!-- 수정불가하게 -->
		  <td><input type="date" name="orderday" id="orderday" value="<?=$orderday?>"  class="form-control" readonly></td>

		  <td class="text-success fw-bold"> 본천장 설계</td>
		  <td><input type="date" name="main_draw" id="main_draw" value="<?=$main_draw?>" class="form-control"></td>

		  <td class="text-info fw-bold">LC 설계</td>
		  <td><input type="date" name="lc_draw" id="lc_draw" value="<?=$lc_draw?>" class="form-control"></td>
		</tr>
		<tr>
		  <td colspan="4"></td>
		  <td class="text-primary fw-bold">기타 설계</td>
		  <td><input type="date" name="etc_draw" id="etc_draw" value="<?=$etc_draw?>" class="form-control"></td>
		</tr>
		<tr>
		  <td style="color:red;">납기일</td>
		  <td><input type="date" name="deadline" id="deadline" value="<?=$deadline?>" class="form-control"></td>    
		  <td style="color:blue;">출고일</td>
		  <td><input type="date" name="workday" id="workday" value="<?=$workday?>" class="form-control"></td>    
		  
		  <td class="text-danger"> 청구일</td>		   
		  <td>
				<input type="date" name="demand" id="demand" value="<?=$demand?>"  class="form-control" > 
		   </td>		  
		</tr>
		<tr>
		<td>미래 시공팀</td>
		  <td><input type="text" name="worker" value="<?=$worker?>" class="form-control"></td>
		  <td>서버도면폴더</td>
		  <td colspan="3" >  <input type="text" id="dwglocation" name="dwglocation" value="<?=$dwglocation?>" class="form-control" placeholder="Nas2dual 저장위치 폴더명 \천장완료\ 다음위치" ></td>
		</tr>      
				
		<tr>
		  <td>운송방식 </td>
		  <td>
			<input type="text" name="delivery" value="<?=$delivery?>" class="form-control">
		  </td>	
		  <td>
			<span style="color:red;">운임비 </span>
		  </td>	
		  <td>
			<input type="text" name="delipay" value="<?=$delipay?>" class="form-control" onkeyup="inputNumberFormat(this)">
		  </td>
			<td colspan="2" class="text-success fs-6"> <b>박스포장 &nbsp;
				<input type="checkbox" id="boxwrap"  name="boxwrap"  value="박스포장" <?php echo ($boxwrap === '박스포장' ? 'checked' : ''); ?>>
			</td>
		</tr>	
 <tr>
    <td>타입(Type)</td>
    <td><input type="text" id="type" name="type" value="<?=$type?>" class="form-control"></td>

    <td style="color:red;">Car insize</td>
    <td><input type="text" id="car_insize" name="car_insize" value="<?=$car_insize?>" class="form-control"></td>

    <td style="color:blue;">인승</td>
    <td><input type="text" id="inseung" name="inseung" value="<?=$inseung?>" class="form-control"></td>	
  </tr>
  
  <tr>  
    <td data-bs-toggle="tooltip" data-bs-placement="top" title="자동계산됨" >결합단위(SET)
      <input type="text" id="su"  name="su" value="<?=$su?>"  class="form-control calculate-su"  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')" >
    </td>

    <td>본천장 수량
       <input type="text" id="bon_su" name="bon_su" value="<?=$bon_su?>"  class="form-control calculate-su"  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
     </td>

    <td>L/C 수량
       <input type="text" id="lc_su"  name="lc_su" value="<?=$lc_su?>"  class="form-control calculate-su" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
    </td>
  
    <td>기타 수량
      <input type="text" id="etc_su" name="etc_su" value="<?=$etc_su?>" class="form-control calculate-su"   oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
    </td>

    <td>공기청정기
       <input type="text" id="air_su"  name="air_su" value="<?=$air_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
     </td>
    <td class="text-primary" >제품가격
       <input type="text" id="price" name="price" value="<?=$price?>" class="form-control" oninput="this.value = this.value.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',')">
   </td>
  </tr>	 
  
  <tr>
    <td> 레이져가공 순서 </td>
    <td>	
      <input type="text" id="work_order"  name="work_order" value="<?=$work_order?>" class="form-control" >
    </td> 
	<td> 레이져가공 예정 </td>
    <td>	
      <input type="date" id="laserdueday"  name="laserdueday" value="<?=$laserdueday?>" class="form-control" >
    </td>
  </tr>	
	
<tr>
	<td colspan="6">  
	  <div class="d-flex justify-content-start align-items-center">
		<h6> <span class="badge bg-success" > 하우징 소재 </span> </h6>
      <select name="material2" id="material2" class="form-select d-block w-auto mx-2 " style="font-size: 0.7rem; height: 32px;">
        <?php		 
        $mat_count = sizeof($material_arr);
        for($i=0; $i<$mat_count; $i++) {
          if($material2==$material_arr[$i])
            print "<option selected value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
          else   
            print "<option value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
        } 		   
        ?>	  
      </select>     
      <input type="text" name="material1" id="material1" value="<?=$material1?>" class="form-control w150px mx-2">      
	  </div>
  </td>
  </tr>
  <tr>
  <td colspan="6">  
	  <div class="d-flex justify-content-start align-items-center">
	  <h6> <span class="badge bg-primary" > 중판 소재 </span> </h6>     
      <select name="material4" id="material4" class="form-select w-auto mx-2" style="font-size: 0.7rem; height: 32px;">
        <?php		 
        $mat_count = sizeof($material_arr);
        for($i=0; $i<$mat_count; $i++) {
          if($material4==$material_arr[$i])
            print "<option selected value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
          else   
            print "<option value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
        } 		   
        ?>	  
      </select>     
      <input type="text" name="material3" id="material3" value="<?=$material3?>" class="form-control w150px mx-2">    
	  </div>
  </td>
</tr>
<tr>  
    <td>비고1</td>
		<td colspan="6">
            <textarea  id="memo"  name="memo" class="form-control"><?=$memo?></textarea>
          </td>
	</tr>
	<tr>
	<td>비고2 </td>		  
        <td colspan="6">
            <textarea  id="memo2"  name="memo2" class="form-control"><?=$memo2?></textarea>
          </td>          
	</tr>
		  </tbody>
		</table>

	<span class="text-center text-danger ">			
	   <h5>  <협력사 부품발주> </h5>
	</span>

<style>
  .table td {
    padding: 2px;
  }
  
   .table td input.form-control {
    height: 25px;
  }
</style>

<div class="table-responsive">
  <table class="table">
    <thead class="text-primary">
      <tr class="text-center">
        <th>구분</th>
        <th>업체</th>
        <th>내역</th>
        <th>발주일</th>
        <th>입고일</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td  id='type1'  title="클릭시 트윈테크 자동계산" >발주1</td>
        <td>
          <input type="text" name="order_com1" id="order_com1" value="<?=$order_com1?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text1" id="order_text1" value="<?=$order_text1?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date1" id="order_date1" value="<?=$order_date1?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date1" id="order_input_date1" value="<?=$order_input_date1?>" size="10" class="form-control">
        </td>
      </tr>
      <tr>
        <td  id='type2'  title="클릭시 트윈테크 자동계산">발주2</td>
        <td>
          <input type="text" name="order_com2" id="order_com2" value="<?=$order_com2?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text2" id="order_text2" value="<?=$order_text2?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date2" id="order_date2" value="<?=$order_date2?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date2" id="order_input_date2" value="<?=$order_input_date2?>" size="10" class="form-control">
        </td>
      </tr>
      <tr>
        <td  id='type3'  title="클릭시 트윈테크 자동계산">발주3</td>
        <td>
          <input type="text" name="order_com3" id="order_com3" value="<?=$order_com3?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text3" id="order_text3" value="<?=$order_text3?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date3" id="order_date3" value="<?=$order_date3?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date3" id="order_input_date3" value="<?=$order_input_date3?>" size="10" class="form-control">
        </td>
      </tr>
      <tr>
        <td id='type4'  title="클릭시 트윈테크 자동계산">발주4</td>
        <td>
          <input type="text" name="order_com4" id="order_com4" value="<?=$order_com4?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text4" id="order_text4" value="<?=$order_text4?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date4" id="order_date4" value="<?=$order_date4?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date4" id="order_input_date4" value="<?=$order_input_date4?>" size="10" class="form-control">
        </td>
      </tr>
    </tbody>
  </table>
</div>
		
</div>

<div class="col-sm-5 p-1 rounded"  style=" border: 1px solid #392f31; " > 	

	<span class="text-center text-primary ">			
	   <h4>  천장용 자재 사용량 입력 </h4>
	</span>		   
			
  <table id="partlist" class="table table-bordered table-striped">
    <thead>
      <tr class="text-center" style="vertical-align:center;">        
        <th style="width:5%;">일반휀</th>
        <th style="width:5%;">휠터휀(LH용)</th>
        <th style="width:5%;">판넬고정구(금성아크릴)</th>
        <th style="width:5%;">비상구 스위치(건흥KH-9015)</th>
        <th style="width:5%;">비상등</th>
        <th style="width:5%;">할로겐(7W -6500K)</th>
        <th style="width:5%;">할로겐(7W -6500K KS)</th>
      </tr>
	</thead>
    <tbody>	  
		 <tr>        
			<td>
				<input id="part1" type="hidden" name="part1" size="2" style="text-align: center;" value="<?=$part1?>" >
				<input id="part2" name="part2" size="2" style="text-align: center;" value="<?=$part2?>">
			</td>
			<td>
				<input id="part3" name="part3" size="2" style="text-align: center;" value="<?=$part3?>" >
			</td>
			<td>
				<input id="part4" name="part4" size="2" style="text-align: center;" value="<?=$part4?>" >
			</td>
			<td>
				<input id="part5" name="part5" size="2" style="text-align: center;" value="<?=$part5?>" >
			</td>
			<td>
				<input id="part6" name="part6" size="2" style="text-align: center;" value="<?=$part6?>" >
			</td>      
			<td>
				<input id="part7" name="part7" size="2" style="text-align: center;" value="<?=$part7?>" >
			</td>      
			<td>
				<input id="part20" name="part20" size="2" style="text-align: center;" value="<?=$part20?>" >
			</td>
		</tr>		
    </tbody>
  </table>
			   
<style>
  .table td {
    padding: 2px;
    width: 16.66%; /* 6개의 요소를 가로로 배치하기 위해 1/6(16.66%)의 너비 설정 */
  }

  .table td input.form-control {
    height: 25px;
  }
</style>	
			
	<div class="table-responsive">
	  <table class="table">		
		<tbody>
		  <tr>
			<td style="color: blue;">T5 (일반)</td>
			<td class="text-center" style="color: blue;">
			  5W(300) 
			  <input id="part8" name="part8" size="2" style="text-align: center; color: blue;" value="<?=$part8?>" class="form-control">
			</td>		  			
			<td  class="text-center" style="color: blue;">
			  11W(600) <input id="part9" name="part9" size="2" style="text-align: center; color: blue;" value="<?=$part9?>" class="form-control">
			</td>
		    	<td  class="text-center" style="color: blue;">
			  15W(900) <input id="part10" name="part10" size="2" style="text-align: center; color: blue;" value="<?=$part10?>" class="form-control">
			</td>		  
			<td  class="text-center" style="color: blue;">
			  20W(1200) <input id="part11" name="part11" size="2" style="text-align: center; color: blue;" value="<?=$part11?>" class="form-control">
			</td>
		  </tr>
		  <tr>
			<td style="color: red;">T5 (KS)</td>		
			<td  class="text-center" style="color: red;"> 
			  6W(300) <input id="part12" name="part12" size="2" style="text-align: center; color: red;" value="<?=$part12?>" class="form-control">
			</td>			
			<td  class="text-center" style="color: red;">
			  10W(600) <input id="part13" name="part13" size="2" style="text-align: center; color: red;" value="<?=$part13?>" class="form-control">
			</td>		  			
			<td  class="text-center" style="color: red;">
			  15W(900) <input id="part14" name="part14" size="2" style="text-align: center; color: red;" value="<?=$part14?>" class="form-control">
			</td>			
			<td  class="text-center" style="color: red;">
			  20W(1200) <input id="part15" name="part15" size="2" style="text-align: center; color: red;" value="<?=$part15?>" class="form-control">
			</td>
		  </tr>
		  <tr>
			<td style="color: brown;">직관등</td>
			<td  class="text-center" style="color: brown;">
			  600mm <input id="part16" name="part16" size="2" style="text-align: center; color: brown;" value="<?=$part16?>" class="form-control">
			</td>
			<td  class="text-center" style="color: brown;">
			  800mm <input id="part17" name="part17" size="2" style="text-align: center; color: brown;" value="<?=$part17?>" class="form-control">
			</td>
			<td  class="text-center" style="color: brown;">
			  1000mm <input id="part18" name="part18" size="2" style="text-align: center; color: brown;" value="<?=$part18?>" class="form-control">
			</td>		  			
			<td  class="text-center" style="color: brown;">
			  1200mm <input id="part19" name="part19" size="2" style="text-align: center; color: brown;" value="<?=$part19?>" class="form-control">
			</td>
		  </tr>
		</tbody>
	  </table>
	</div>  
			
<style>
/* 기본 td 스타일 */
.custom-table td {
  padding: 3px;
  border: 1px solid #ddd;
  /* 필요에 따라 기본 폭을 지정할 수 있음 */
}

/* input 요소가 없는 td의 폭을 좁게 지정 */
.custom-table td:not(:has(input)) {
  width: 50px; /* 원하는 폭으로 수정 */
}

</style>			
<table class="table custom-table">
  <thead>
    <tr>
      <th colspan="8">
        <h4 class="text-info"> L/C 제조 완료 현황</h4>
      </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>업체</td>
      <td>
        <input type="text" name="lclaser_com" id="lclaser_com" value="<?=$lclaser_com?>" class="form-control">
      </td>
      <td>레이져</td>
      <td>
        <input type="date" name="lclaser_date" id="lclaser_date" value="<?=$lclaser_date?>" class="form-control">
      </td>
	  <td colspan="1"> </td>
      <td>절곡</td>
      <td colspan="1">
        <input type="date" name="lcbending_date" id="lcbending_date" value="<?=$lcbending_date?>" class="form-control">
      </td>      
    </tr>
    <tr>
      <td>제관</td>
      <td>
        <input type="date" name="lcwelding_date" id="lcwelding_date" value="<?=$lcwelding_date?>" class="form-control">
      </td>
      <td>도장</td>
      <td>
        <input type="date" name="lcpainting_date" id="lcpainting_date" value="<?=$lcpainting_date?>" class="form-control">
      </td>
      <td class="text-danger"> 
		<input class="ms-3" type="checkbox" id="cabledone" name="cabledone" value="결선완료" <?php echo ($cabledone === '결선완료' ? 'checked' : ''); ?>>
          <label for="cabledone" class="fw-bold fs-6" > 결선완료 </label>
	  </td>
      <td>조립</td>
      <td>
          <input type="date" name="lcassembly_date" id="lcassembly_date" value="<?=$lcassembly_date?>" class="form-control">
      </td>	        
    </tr>
  </tbody>
</table>
	
	
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th colspan="6"> <h4 class="text-success" > 본천장 제조 완료 현황</h4> </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td >은성레이져 외주</td>
        <td>
          <input type="date" name="eunsung_make_date" id="eunsung_make_date" value="<?=$eunsung_make_date?>" class="form-control">
        </td>
        <td>  레이져 </td>
        <td>
          <input type="date" name="eunsung_laser_date" id="eunsung_laser_date" value="<?=$eunsung_laser_date?>" class="form-control">
        </td>

        <td>  절곡 </td>
        <td>
          <input type="date" name="mainbending_date" id="mainbending_date" value="<?=$mainbending_date?>" class="form-control">
        </td>
      </tr>
      <tr>		
        <td>  제관 </td>
        <td>
          <input type="date" name="mainwelding_date" id="mainwelding_date" value="<?=$mainwelding_date?>" class="form-control">
        </td>
        <td>  도장 </td>
        <td>
          <input type="date" name="mainpainting_date" id="mainpainting_date" value="<?=$mainpainting_date?>" class="form-control">
        </td>
        <td>  조립 </td>
        <td>
          <input type="date" name="mainassembly_date" id="mainassembly_date" value="<?=$mainassembly_date?>" class="form-control">
        </td>
      </tr>
    </tbody>
  </table>
</div>


<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th colspan="6" > <h4 class="text-primary" > 기타 (중판, 인테리어판넬, 발보호판 등) 제조 완료 현황 </h4> </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>  레이져 </td>
        <td>
          <input type="date" name="etclaser_date" id="etclaser_date" value="<?=$etclaser_date?>" class="form-control">
        </td>
        <td>  절곡 </td>
        <td>
          <input type="date" name="etcbending_date" id="etcbending_date" value="<?=$etcbending_date?>" class="form-control">
        </td>
        <td>  제관 </td>
        <td>
          <input type="date" name="etcwelding_date" id="etcwelding_date" value="<?=$etcwelding_date?>" class="form-control">
        </td>
      </tr>
      <tr>		
        <td>  도장 </td>
        <td>
          <input type="date" name="etcpainting_date" id="etcpainting_date" value="<?=$etcpainting_date?>" class="form-control">
        </td>
        <td>  조립 </td>
        <td>
          <input type="date" name="etcassembly_date" id="etcassembly_date" value="<?=$etcassembly_date?>" class="form-control">
        </td>
      </tr>
    </tbody>
  </table>
</div>
</div>
 	
<div class="col-sm-2 p-1  rounded"   style=" border: 1px solid #392f31; " > 	
</div> 	
	   
</div>
</div>			
</div>			
	<div class="card mt-1 mb-5" style=" border: 1px solid #392f31; ">			
	<div class="card-header">			
		<h4 class="text-center">포장 완료사진</h4>
	</div>
	<div id="displayPicture" style="display: none;" class="row">
	  </div> 		
	</div>
			 
</div> 
</form>
</body>
</html>    

<script>
document.getElementById('main_draw').addEventListener('change', function() {
	user_name = '<?php echo $user_name;?>';
    document.getElementById('designer').value = user_name;
});

document.getElementById('lc_draw').addEventListener('change', function() {
	user_name = '<?php echo $user_name;?>';
    document.getElementById('designer').value = user_name;
});


$(document).ready(function(){
	
	// 외주가공 체크박스 이벤트
	$('#outsourcing').change(function() {
		if ($(this).is(':checked')) {
			showOutsourcingMemo();
		} else {
			hideOutsourcingMemo();
		}
	});
	
	// 외주가공 메모 말풍선 표시
	function showOutsourcingMemo() {
		$('#outsourcing-memo-bubble').show();
		$('#outsourcing_memo').focus();
		updateCharCount();
	}
	
	// 외주가공 메모 말풍선 숨기기
	function hideOutsourcingMemo() {
		$('#outsourcing-memo-bubble').addClass('bubble-hide');
		setTimeout(function() {
			$('#outsourcing-memo-bubble').hide().removeClass('bubble-hide');
		}, 200);
	}
	
	// 외주가공 메모 저장
	function saveOutsourcingMemo() {
		const memo = $('#outsourcing_memo').val();
		// 여기서는 폼 전송 시 함께 저장되므로 별도 AJAX 호출은 하지 않음
		// 성공 메시지 표시
		Swal.fire({
			title: '저장 완료',
			text: '외주가공 메모가 저장되었습니다.',
			icon: 'success',
			timer: 1500,
			showConfirmButton: false
		});
	}
	
	// 글자 수 카운트 업데이트
	function updateCharCount() {
		const count = $('#outsourcing_memo').val().length;
		$('#memo-char-count').text(count);
	}
	
	// 외주가공 메모 입력 시 글자 수 카운트
	$(document).on('input', '#outsourcing_memo', updateCharCount);
	
	// 외주가공 메모 저장 버튼 클릭
	window.saveOutsourcingMemo = saveOutsourcingMemo;
	window.hideOutsourcingMemo = hideOutsourcingMemo;
	
	// 외부 클릭 시 말풍선 닫기
	$(document).on('click', function(e) {
		if (!$(e.target).closest('#outsourcing-memo-bubble, #outsourcing').length) {
			hideOutsourcingMemo();
		}
	});
	
	// 페이지 로드 시 기존 데이터가 있으면 말풍선 표시
	if ($('#outsourcing').is(':checked') && $('#outsourcing_memo').val().trim() !== '') {
		showOutsourcingMemo();
	}
	
	// textarea 자동 높이 조절 함수
	function autoResizeTextarea() {
		$('textarea').each(function() {
			// 스크롤 높이를 0으로 초기화
			this.style.height = 'auto';
			// 내용에 맞게 높이 조절 (최소 높이 40px)
			this.style.height = Math.max(40, this.scrollHeight) + 'px';
		});
	}
	
	// 페이지 로드 시 실행
	autoResizeTextarea();
	
	// textarea 내용이 변경될 때마다 실행
	$(document).on('input', 'textarea', autoResizeTextarea);
	
// 전체화면에 꽉찬 이미지 보여주는 루틴
		$(document).on("click","img",function(){
			var path = $(this).attr('src')
			showImage(path);
		});//end click event
		
		function showImage(fileCallPath){
		    
		    $(".bigPictureWrapper").css("display","flex").show();
		    
		    $(".bigPicture")
		    .html("<img src='"+fileCallPath+"' >")
		    .animate({width:'100%', height: '100%'}, 1000);
		    
		  }//end fileCallPath
		  
		$(".bigPictureWrapper").on("click", function(e){
		    $(".bigPicture").animate({width:'0%', height: '0%'}, 1000);
		    setTimeout(function(){
		      $('.bigPictureWrapper').hide();
		    }, 1000);
		  });//end bigWrapperClick event	
		  

	// 도면폴더 클릭시 실행
	$("#dwgclick").click(function() {
		// Link = "file://nas2dual/%EC%9E%A0%EC%99%84%EB%A3%8C/"; 
		
		var Urls = '<?php echo $dwglocation; ?>';	
					
		Urls = "\\\\nas2dual\\천장완료\\" + Urls;	
		
	   $('#insertword').val(Urls)
	   
	 // var obj = document.getElementById('insertword'); 
	 // obj.select();  //인풋 컨트롤의 내용 전체 선택  
	 // document.execCommand("copy");  //복사    
	 
		tmp = "<br> 도면저장 폴더위치가 복사되었습니다. <br> 탐색기에 'Ctrl+v'로 붙여넣기 하세요! <br>";				
		$('#alertmsg').html(tmp); 
	   $('#myModal').modal('show');		   
		  
	   clipboardCopy('insertword');		
		   
		setTimeout(function() { 
	  var obj = document.getElementById('insertword'); 
	  obj.select();  //인풋 컨트롤의 내용 전체 선택  
	  document.execCommand("copy");  //복사        
		}, 500);	   	   
		
		setTimeout(function() { 
			 $('#myModal').modal('hide');	      
		}, 1500);	   
	   
	   // clipboardCopy('insertword');		
	
	});
	

	// rendering 사진 멀티업로드	
	$("#upfile").change(function(e) {	    
			// 실측서 이미지 선택
			$("#item").val('ceilingrendering');
			var item = $("#item").val();
			FileProcess(item);	
			
			
	});	 	
		
	function FileProcess(item) {
	//do whatever you want here
	num = $("#num").val();

	  // 사진 서버에 저장하는 구간	
	  // 사진 서버에 저장하는 구간	
			//tablename 설정
		   $("#tablename").val('ceiling');  
			// 폼데이터 전송시 사용함 Get form         
			var form = $('#board_form')[0];  	    
			// Create an FormData object          
			var data = new FormData(form); 
			
			console.log(form);
			console.log(data);

			tmp='사진을 저장중입니다. 잠시만 기다려주세요.';					
			$('#alertmsg').html(tmp); 			  
			$('#myModal').modal('show'); 	

			$.ajax({
				enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
				processData: false,    
				contentType: false,      
				cache: false,           
				timeout: 600000, 			
				url: "../p/mspic_insert.php?num=" + num ,
				type: "post",		
				data: data,						
				success : function(data){
					console.log(data);
					// opener.location.reload();
					// window.close();	
					setTimeout(function() {
						$('#myModal').modal('hide');  
						}, 1000);	
					// 사진이 등록되었으면 100 입력됨
					 $("#pInput").val('100');							

				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
							} 			      		
			   });	

	}		   
 
  $("#car_insize").focusout(function(){
//    $("#inseung").css("background-color", "silver");
		const insize = $("input[name=car_insize]" ).val();
		const wide_insize = insize.split('*');
		const wide = Number(wide_insize[0]);
        const depth = Number(wide_insize[1]);	
  
   $("#inseung").val(calinseung( wide, depth ) );

  });	
	
//타입을 입력하면 회사가 바뀐다.
$("#type").keydown(function(e) {
	  changeType();
});

  $("#type").focusout(function(){
     changeType();
  });	
	
function changeType() {
		const type = $("input[name=type]" ).val();
		   $("input[name=order_com1]").val('');
		   $("input[name=order_com2]").val('');		
		   $("input[name=order_com3]").val('');		
		   $("input[name=order_com4]").val('');		
		   $("input[name=order_com5]").val('');		

		if(type=='바리솔' )
		  {
		   $("input[name=order_com1]").val('투엘비');
		   $("input[name=order_com2]").val('다온텍');		   
		  }	
		if(type=='011' || type=='012' || type=='017' || type=='013D' )
		  {
		   $("input[name=order_com1]").val('덴크리');
		   $("input[name=order_com2]").val('트윈테크');		   
		  }	
		if(type=='N20')
		  {
		   $("input[name=order_com1]").val('다온텍');
		   $("input[name=order_com2]").val('알루스');
		   $("input[name=order_com3]").val('트윈테크');
		  }	
		if(type=='N21')
		  {
		   $("input[name=order_com1]").val('다온텍');
		   $("input[name=order_com2]").val('다온텍');
		  }		
		if(type=='N23')
		  {
		   $("input[name=order_com1]").val('트윈테크');
		   $("input[name=order_com2]").val('다온텍');
		  }	
		if(type=='신N20')
		  {
		   $("input[name=order_com1]").val('다온텍');
		   $("input[name=order_com2]").val('알루스');
		   $("input[name=order_com3]").val('트윈테크');
		  }	
		if(type=='N20변형')
		  {
		   $("input[name=order_com1]").val('다온텍');
		   $("input[name=order_com2]").val('트윈테크');
		  }	
		if(type=='027' || type=='015' || type=='013' || type=='033' || type=='033변형' || type=='035' || type=='036')
		  {
		   $("input[name=order_com1]").val('다온텍');
		  }	

		if(type=='032' || type=='034' )
		  {
		   $("input[name=order_com1]").val('알루스');
		   $("input[name=order_com2]").val('다온텍');
		  }	
		if(type=='037' ||  type=='038')
		  {
		   $("input[name=order_com1]").val('청디자인');
		  }	
		if(type=='026')
		  {
		   $("input[name=order_com1]").val('덴크리');		   
		   $("input[name=order_com2]").val('트윈테크');
		  }	
		if(type=='026변형')
		  {
		   $("input[name=order_com1]").val('다온텍');		   
		   $("input[name=order_com2]").val('트윈테크');
		  }		
		if(type=='027')
		  {
		   $("input[name=order_com1]").val('다온텍');
		  }		
		if(type=='031')
		  {
		   $("input[name=order_com1]").val('다온텍');
		   $("input[name=order_com2]").val('트윈테크');
		   $("input[name=order_com3]").val('알루스');
		  }			
		if(type=='N038' || type=='038' || type=='051' || type=='052')
		  {
		   $("input[name=order_com1]").val('다온텍');		   
		   $("input[name=order_com2]").val('알루스');
		  }			
		if(type=='053')
		  {
		   $("input[name=order_com1]").val('다온텍');		   
		  }			
}		  
	
	$("#type1").click(function(){
		$("input[name=order_com1]").val('트윈테크');
        calculateBoth('1');
	});		
	$("#type2").click(function(){
		$("input[name=order_com2]").val('트윈테크');
        calculateBoth('2');
	});		
	$("#type3").click(function(){
		$("input[name=order_com3]").val('트윈테크');
        calculateBoth('3');
	});		
	$("#type4").click(function(){
		$("input[name=order_com4]").val('트윈테크');
        calculateBoth('4');
	});		
	
	calculateBoth = function(NUM) {
		const type = $("input[name=type]" ).val();
		const insize = $("input[name=car_insize]" ).val();
		const lc_su = $("input[name=lc_su]" ).val();
	    const first_name = "order_text" + NUM; 
		
		if(Number(lc_su)<1) 
		   {
			 alert('L/C 수량을 입력해 주세요');			 
		   }
		
		let result;
		let jungSu;
		let divider;

		const wide_insize = insize.split('*');
		const wide = Number(wide_insize[0]);
        const depth=Number(wide_insize[1]);
		
		let result_wide=0;

		switch(type) {
			case '011' :
			   result_wide=wide-730;
			   break;
			case '012' :
			   result_wide=wide-750;
			   break;
			case '013D' :
			   result_wide=wide-705;
			   break;
			case '014' :
			   result_wide=wide/2-143;
			   break;
			case '017' :
			   result_wide=wide-810;
			   break;
			case '017S' :
			case '017s' :
			   result_wide=wide-410;
			   break;
			case '017m' :
			case '017M' :
			   result_wide=wide-610;
			   break;
			case 'N20' :
			   result_wide=wide-705;
			   break;
			case '026' :
			   result_wide=wide-670;
			   break;			   
			default:
				 break;
		}

		if(depth<1000)
	     	{
			   jungSu=1;
			   divider = 1;
	     		}
			else if(depth>=1800)
			   {
				 jungSu = 3;
				 divider = 3;
			   }
			   else
			      {
					jungSu = 2;
					divider = 2;
				  }
				
		let result_depth=0;

		switch(type) {
			case '011' :
			   result_depth= (depth-54)/divider-11  ;
			   break;
			case '012' :
			   result_depth=(depth-54)/divider -11 ;
			   break;
			case '013D' :
			   result_depth=(depth-20)/divider-11 ;
			   break;
			case '014' :
			   result_depth=(depth-54) -11 ;
			   break;
			case '017' :
			   if(depth>=1800)
					  result_depth=(depth-60)/3 -11;
				  else
					  result_depth=(depth-60)/2 -11;
			   break;
			case '017S' :
			case '017s' :
			   result_depth=(depth-60)/divider -10;
			   break;
			case '017m' :
			case '017M' :
			   result_depth=(depth-60)/divider -11;
			   break;
			case 'N20' :
			   result_depth=(depth-56)/divider -10;
			   break;
			case '026' :
			   result_depth=(depth-58)/divider -11;
			   break;			   
			default:
				 break;
		}					
		let tmp="";
		tmp = '중판:' + result_wide + "*" + Math.floor(result_depth) + "*" + jungSu*lc_su + "EA" ;
		$("input[name=" + first_name + "]").val(tmp);
}
	
	
	
});

function inputNumberFormat(obj) { 
    obj.value = comma(uncomma(obj.value)); 
} 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트 담당자 연락처 찾기
    }
}
function Enter_firstCheck(){
    if(event.keyCode == 13){
	const data1 = "ceiling";
	const data2 = "firstordman";
	const data3 = "firstordmantel";	
	const search = $("#" + data2).val();
    if(event.keyCode == 13){     
     window.open('load_tel.php?search=' + search +'&data1=' + data1 + '&data2=' + data2 + '&data3=' + data3,'전번 조회','top=0, left=0, width=1500px, height=600px, scrollbars=yes');	  
    }
    }
}

function Enter_chargedman_Check(){
	const data1 = "ceiling";
	const data2 = "chargedman";
	const data3 = "chargedmantel";	
	const search = $("#" + data2).val();
    if(event.keyCode == 13){     
     window.open('load_tel.php?search=' + search +'&data1=' + data1 + '&data2=' + data2 + '&data3=' + data3,'전번 조회','top=0, left=0, width=1500px, height=600px, scrollbars=yes');	  
    }
}

function exe_search()
{

	
}

function exe_firstordman()
{
     var tmp=$('#firstordman').val();
	 switch (tmp) {
		 case '손시락' :
         $("#firstordman").val("손시락소장");		 		 
         $("#firstordmantel").val("010-6774-9253");		 
         $("#firstord").val("오티스");			 		 		 
         $("#secondord").val("우성");			 
		 break;		 		 
				 
	 }
}


function exe_chargedman()
{
     var tmp=$('#chargedman').val();
	 switch (tmp) {
		 case '서재길' :
         $("#chargedmantel").val("010-3797-2665");		 		 
         $("#chargedman").val("서재길소장");
		 break;					 
		 
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

function phonebookBtn(belong, firstitem, seconditem, enterpress)
{	
    // 소속 추가 belong
    var search = $("#" + firstitem).val();		
	var belongstr = $("#" + belong).val();
	
    href = '../phonebook/list.php?search=' + search + '&firstitem=' + firstitem  + '&seconditem=' + seconditem  + '&enterpress=' + enterpress  + '&belong=' + belong  + '&belongstr=' + belong ;				
	popupCenter(href, '전화번호 검색', 600, 770);
}

function addressBtn(address)
{	
    var search = $("#" + address).val();
	
    href = '../phonebook/list_address.php?search=' + search  ;				
	popupCenter(href, '주소검색', 800, 700);
}



$(document).ready(function(){
		
	 $("#saveBtn").click(function(){ 
		
		// 조건 확인
		if($("#workplacename").val() === '' || $("#su").val()  === '' ) {
			showWarningModal();
		} else {
			
		   showMsgModal(2); // 파일저장중
			
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
             else			
				$("#mode").val('modify'); 
			
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
				setTimeout(function(){									
					if (window.opener && !window.opener.closed) {
						// 부모 창에 restorePageNumber 함수가 있는지 확인
						if (typeof window.opener.restorePageNumber === 'function') {
							window.opener.restorePageNumber(); // 함수가 있으면 실행
						}								
					}		
				}, 1000);		
			setTimeout(function(){			
					 hideMsgModal();							
					 location.href = "view.php?num=" + data["num"];
			}, 1000);				
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });		
			
	}	
});


$(document).ready(function(){
	
var mode = '<?php echo $mode; ?>';

	if(mode==='copy')
	{
	// data 초기화
		Swal.fire({
		  title: '데이터 복사',
		  text: "기본사항을 제외하고 초기화 하실래요?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: '네 그렇게 합시다!'
		}).then((result) => {
		  if (result.isConfirmed) {
			  // 실제 코드입력
				$('#board_form').find('input').each(function(){ $(this).val(''); });
				$('#board_form').find('textarea').each(function(){ $(this).val(''); });

				$('#workplacename').val("<? echo $workplacename; ?>");
				$('#address').val("<? echo $address; ?>");
				$('#firstord').val("<? echo $firstord; ?>");
				$('#firstordman').val("<? echo $firstordman; ?>");
				$('#firstordmantel').val("<? echo $firstordmantel; ?>");
				$('#secondord').val("<? echo $secondord; ?>");
				$('#secondordman').val("<? echo $secondordman; ?>");
				$('#secondordmantel').val("<? echo $secondordmantel; ?>");
				$('#chargedman').val("<? echo $chargedman; ?>");
				$('#chargedmantel').val("<? echo $chargedmantel; ?>");
				$('#orderday').val(getToday());

			Swal.fire(
			  '처리되었습니다.',
			  '데이터가 성공적으로 복사되었습니다.',
			  'success'
			)
		  }
		  else
		  {  
				$('#board_form').find('input').each(function(){ $(this).val(''); });
				$('#board_form').find('textarea').each(function(){ $(this).val(''); });

				$('#workplacename').val("<? echo $workplacename; ?>");
				$('#address').val("<? echo $address; ?>");
				$('#firstord').val("<? echo $firstord; ?>");
				$('#firstordman').val("<? echo $firstordman; ?>");
				$('#firstordmantel').val("<? echo $firstordmantel; ?>");
				$('#secondord').val("<? echo $secondord; ?>");
				$('#secondordman').val("<? echo $secondordman; ?>");
				$('#secondordmantel').val("<? echo $secondordmantel; ?>");
				$('#chargedman').val("<? echo $chargedman; ?>");
				$('#chargedmantel').val("<? echo $chargedmantel; ?>");
				$('#orderday').val(getToday());
				
				$('#type').val("<? echo $type; ?>");
				$('#inseung').val("<? echo $inseung; ?>");
				$('#car_insize').val("<? echo $car_insize; ?>");
				
				$('#order_com1').val("<? echo $order_com1; ?>");
				$('#order_com2').val("<? echo $order_com2; ?>");
				$('#order_com3').val("<? echo $order_com3; ?>");
				$('#order_com4').val("<? echo $order_com4; ?>");
		  }	  
		});		
	  }

	if(mode==='split') // 분할
	{
			// data 초기화
		Swal.fire({
		  title: '데이터 분할&복사',
		  text: "기존 데이터를 여러개로 나눌때 사용합니다. \n\r 기존의 모든 데이터를 그대로 복사하는 개념입니다. ",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: '네 합시다!'
		}).then((result) => {
		  if (result.isConfirmed) {
			  // 실제 코드입력

			Swal.fire(
			  '처리되었습니다.',
			  '데이터가 성공적으로 복사되었습니다.',
			  'success'
			)
		  }
		  
		});	
    }
});
</script>

<!-- su 자동계산 -->
<script>
	function calculateSu() {
		const bonSu = parseInt(document.getElementById('bon_su').value) || 0;
		const lcSu = parseInt(document.getElementById('lc_su').value) || 0;
		const etcSu = parseInt(document.getElementById('etc_su').value) || 0;
		
		const su = Math.max(bonSu, lcSu, etcSu);
		document.getElementById('su').value = su;
	}

	document.addEventListener('DOMContentLoaded', (event) => {
		const elements = document.querySelectorAll('.calculate-su');

		elements.forEach((element) => {
			element.addEventListener('input', calculateSu);
			element.addEventListener('change', calculateSu);
		});
	});
</script>


<!-- 부트스트랩 툴팁 -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
  
  	// $("#order_form_write").modal("show");	
	
	// 모든 input의 autocomplete off 시키기
	document.querySelectorAll('input').forEach(function(input) {
    input.setAttribute('autocomplete', 'off');
	});

  
});

function updateOptions(selector, value) {
    const element = document.querySelector(selector);
    if (element) {
        element.value = value; // input이나 select 요소에 값 설정
    }
}


</script>

	</body>
 </html>

