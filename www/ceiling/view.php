<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json';
 
$menu=$_REQUEST["menu"]; 
   
$title_message = '본천장&조명천장 수주내역';   
   
?>

<?php 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';   
   
 ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'; ?>

<title> <?=$title_message?>  </title>

</head>

<body>

<style>
.table, td, input {
	font-size: 14px !important;
	vertical-align: middle;
	padding: 2px; /* 셀 내부 여백을 5px로 설정 */
	border-spacing: 2px; /* 셀 간 간격을 5px로 설정 */
	text-align:center;
}

.table td input.form-control {
	height: 25px;
	border: 1px solid #ced4da; /* 테두리 스타일 추가 */
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
	transform: scale(1.6); /* 크기를 1.6배로 확대 */
	margin-right: 10px;   /* 확대 후의 여백 조정 */  
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
    pointer-events: none; /* 읽기 전용 */
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
   
<?php
   
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';

$process = $_REQUEST["process"] ?? '';
$year = $_REQUEST["year"] ?? '';

$check_draw = $_REQUEST["check_draw"] ?? $_POST["check_draw"] ?? '';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';


$cursort = $_REQUEST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? '0';

$navibar = $_REQUEST["navibar"] ?? '0';

$pdo = db_connect();	
 
$WrappicNum=0; 

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

// '폴더의 ID 가져오기
$miraeFolderId = getFolderId($service, '미래기업');
$uploadsFolderId = getFolderId($service, 'imgwork', $miraeFolderId);

$pdo = db_connect();

// Google Drive 및 데이터베이스에서 파일 정보 가져오기
$WrappicData = [];
$tablename = 'ceilingwrap';
$item = 'ceilingwrap';

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
                $WrappicData[] = ['thumbnail' => $thumbnailUrl, 'link' => $webViewLink, 'fileId' => $fileId];
            } catch (Exception $e) {
                error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                $WrappicData[] = ['thumbnail' => "https://drive.google.com/uc?id=$fileId", 'link' => null, 'fileId' => $fileId];
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
                    $WrappicData[] = ['thumbnail' => $thumbnailUrl, 'link' => $webViewLink, 'fileId' => $fileId];

                    // 데이터베이스 업데이트: 검색된 파일 ID 저장
                    $updateSql = "UPDATE {$DB}.picuploads SET picname = ? WHERE item = ? AND parentnum = ? AND picname = ?";
                    $updateStmh = $pdo->prepare($updateSql);
                    $updateStmh->execute([$fileId, $item, $num, $picname]);
                } else {
                    error_log("Google Drive에서 파일을 찾을 수 없습니다: " . $picname);
                    $WrappicData[] = ['thumbnail' => null, 'link' => null, 'fileId' => null];
                }
            } catch (Exception $e) {
                error_log("Google Drive 파일 검색 실패: " . $e->getMessage());
                $WrappicData[] = ['thumbnail' => null, 'link' => null, 'fileId' => null];
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$WrappicNum = count($WrappicData);

 
 try{
     $sql = "select * from mirae8440.ceiling where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
      
     include '_rowDB.php';	  
			
	  $workday=trans_date($workday);
	  $demand=trans_date($demand);
	  $orderday=trans_date($orderday);
	  $deadline=trans_date($deadline);
	  $testday=trans_date($testday);
	  $lc_draw=trans_date($lc_draw);
	  $etc_draw=trans_date($etc_draw);
	  $lclaser_date=trans_date($lclaser_date);
	  $lclbending_date=trans_date($lclbending_date);
	  $lclwelding_date=trans_date($lclwelding_date);
	  $lcpainting_date=trans_date($lcpainting_date);
	  $lcassembly_date=trans_date($lcassembly_date);
	  $main_draw=trans_date($main_draw);			
	  $eunsung_make_date=trans_date($eunsung_make_date);			
	  $eunsung_laser_date=trans_date($eunsung_laser_date);			
	  $mainbending_date=trans_date($mainbending_date);			
	  $mainwelding_date=trans_date($mainwelding_date);			
	  $mainpainting_date=trans_date($mainpainting_date);			
	  $mainassembly_date=trans_date($mainassembly_date);		
	  
	  $order_date1=trans_date($order_date1);					   
	  $order_date2=trans_date($order_date2);					   
	  $order_date3=trans_date($order_date3);					   
	  $order_date4=trans_date($order_date4);					   
	  $order_input_date1=trans_date($order_input_date1);					   
	  $order_input_date2=trans_date($order_input_date2);					   
	  $order_input_date3=trans_date($order_input_date3);					   
	  $order_input_date4=trans_date($order_input_date4);	
	  
	  
	  // $outsourcing = '외주';
			
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
 
 // 랜더링 이미지 이미 있는 것 불러오기 
$picData=array(); 
$tablename='ceiling';
$item = 'ceilingrendering';

// '폴더의 ID 가져오기
$miraeFolderId = getFolderId($service, '미래기업');
$uploadsFolderId = getFolderId($service, 'uploads', $miraeFolderId);

$pdo = db_connect();

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
  
$URLsave = "https://8440.co.kr/ceilingloadpic.php?num=" . $num;  
 
// // 첨부파일 있는 것 불러오기 
// $savefilename_arr=array(); 
// $realname_arr=array(); 
// $attach_arr=array(); 
// $tablename='ceiling';
// $item = 'ceiling';

// $sql=" select * from mirae8440.picuploads where tablename ='$tablename' and item ='$item' and parentid ='$num' ";	

 // try{  
   // $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   // while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			// array_push($realname_arr, $row["realname"]);			
			// array_push($savefilename_arr, $row["savename"]);			
			// array_push($attach_arr, $row["parentid"]);			
        // }		 
   // } catch (PDOException $Exception) {
    // print "오류: ".$Exception->getMessage();
  // }  
    
$savefilename_arr = [];
$realname_arr = [];
$tablename = 'ceiling';
$item = 'ceiling';

$sql = "SELECT * FROM {$DB}.picuploads WHERE tablename=? AND item = ? AND parentnum = ?";
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$tablename, $item, $num]);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"];
        $realname = $row["realname"];
        $realname_arr[] = $realname; // realname 배열에 추가

        if (preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            // Google Drive 파일 ID로 처리
            $fileId = $picname;

            try {
                // Google Drive 파일 정보 가져오기
                $file = $service->files->get($fileId, ['fields' => 'webViewLink, thumbnailLink']);
                $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                $webViewLink = $file->webViewLink;
                $savefilename_arr[] = [
                    'thumbnail' => $thumbnailUrl,
                    'link' => $webViewLink,
                    'fileId' => $fileId,
                    'realname' => $realname // realname 포함
                ];
            } catch (Exception $e) {
                error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                $savefilename_arr[] = [
                    'thumbnail' => "https://drive.google.com/uc?id=$fileId",
                    'link' => null,
                    'fileId' => $fileId,
                    'realname' => $realname // realname 포함
                ];
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
                    $savefilename_arr[] = [
                        'thumbnail' => $thumbnailUrl,
                        'link' => $webViewLink,
                        'fileId' => $fileId,
                        'realname' => $realname // realname 포함
                    ];

                    // 데이터베이스 업데이트: 검색된 파일 ID 저장
                    $updateSql = "UPDATE {$DB}.picuploads SET picname = ? WHERE item = ? AND parentnum = ? AND picname = ?";
                    $updateStmh = $pdo->prepare($updateSql);
                    $updateStmh->execute([$fileId, $item, $num, $picname]);
                } else {
                    error_log("Google Drive에서 파일을 찾을 수 없습니다: " . $picname);
                    $savefilename_arr[] = [
                        'thumbnail' => null,
                        'link' => null,
                        'fileId' => null,
                        'realname' => $realname // realname 포함
                    ];
                }
            } catch (Exception $e) {
                error_log("Google Drive 파일 검색 실패: " . $e->getMessage());
                $savefilename_arr[] = [
                    'thumbnail' => null,
                    'link' => null,
                    'fileId' => null,
                    'realname' => $realname // realname 포함
                ];
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
  
// print_r($savefilename_arr);

$material_arr = array('','304 Hair Line 1.2T','304 HL 1.2T','304 Mirror 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)',  '기타' );
  
 ?>
 
		      
<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  > 
 
<div class="container-fluid">	  
<div class="card p-2 m-2">	  
   
<div class='bigPictureWrapper'>
	<div class='bigPicture'>
	</div>	   
</div>   
   		  
<div class="row mt-1 align-items-center"> 		
<div class="col-sm-1"> 		
</div> 		
<div class="col-sm-10"> 		  
<div class="d-flex justify-content-start align-items-center mt-3 mb-2 ">		
	<span class="fs-6 me-5">  <?=$title_message?> (#<?=$num?>) </span>   
	
	<button class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1';" >  <i class="bi bi-pencil-square"></i>  수정  </button>
	<button class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>&check=<?=$check?>')">  <i class="bi bi-trash"></i>  삭제   </button>
	<button class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php';" > <ion-icon name="create-outline"></ion-icon> 신규 </button>
	<button class="btn btn-primary btn-sm me-1" onclick="location.href='write_form.php?mode=copy&num=<?=$num?>';" >  <i class="bi bi-copy"></i> 복사 </button>&nbsp;
	<button class="btn btn-outline-primary btn-sm me-1" onclick="location.href='write_form.php?mode=split&num=<?=$num?>';"> <i class="bi bi-window-split"></i>  분할&복사 </button>
	<button class="btn btn-success btn-sm me-1" onclick="popupCenter('transform.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>&m2=<?=$m2?>','출고증 인쇄',1400,900);" > <i class="bi bi-printer"></i> 출고증 </button>
	<button class="btn btn-success btn-sm me-1" onclick="popupCenter('transform_sheet.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>&m2=<?=$m2?>','출고증 인쇄',1400,900);" > <i class="bi bi-printer"></i> 거래명세서  </button>
	<button class="btn btn-success btn-sm me-1" onclick="popupCenter('inspection.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>&m2=<?=$m2?>','검사서(후지) 인쇄',1000,500);" > <i class="bi bi-printer"></i> 검사서(후지)</button> 
	<?php 
	
	if(intval($bon_su)>0)
    {  ?>	
	<button class="btn btn-success btn-sm" onclick="popupCenter('millsheet.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>&m2=<?=$m2?>','자체시험성적서 인쇄',1000,500);" > 자체시험성적서 <ion-icon name="print-outline"></ion-icon> </button>&nbsp;&nbsp;   						
	<?php }  ?>
	
	</div>
	</div>
	<div class="col-sm-1 text-end"> 	
		<button class="btn btn-secondary btn-sm" onclick="self.close();" > <i class="bi bi-x-lg"></i> 닫기 </button>&nbsp;	
	</div> 			 
</div> 
<div class="d-flex justify-content-center mb-1 mt-1"> 	
	<div class="col-sm-5 p-1  rounded"   style=" border: 1px solid #392f31; " > 
		<table class="table table-reponsive">
		 
		  <tbody>
			<tr>
			  <td colspan="1">현장명</td>
			  <td colspan="4">
				<input type="text" id="workplacename" name="workplacename" value="<?=$workplacename?>" class="form-control" style="text-align: left;" required>
			  </td>
			  <td colspan="1">
			   <?php
					  if($outsourcing === '외주')
					  {
							print '<h5> <span id="outsourcingBtn" class="badge bg-success ">외주가공</span>    &nbsp;	';							
					  }		
				?>			  
			</td>
			</tr>
			  <td>주소</td>
			  <td colspan="4"><input type="text" name="address" value="<?=$address?>" class="form-control"  style="text-align: left;" ></td>
			  <td colspan="1">
			   <?php
					  if($outsourcing === '외주')
					  {
							print '<div class="d-flex align-items-center position-relative ms-2">';
							print '<input type="checkbox" checked id="outsourcing" name="outsourcing" onclick="return false;" value="외주" >';
							print '<label for="outsourcing" class="ms-2 mb-0">외주 메모</label>';
							
							// 외주가공 메모가 있으면 말풍선 표시
							if(!empty($outsourcing_memo)) {
								print '<div id="outsourcing-memo-bubble" class="outsourcing-memo-bubble" style="display: block;">';
								print '<div class="bubble-content">';
								print '<div class="bubble-header">';
								print '<span class="bubble-title">외주가공 메모</span>';
								print '<button type="button" class="bubble-close" onclick="hideOutsourcingMemo()">';
								print '<i class="bi bi-x"></i>';
								print '</button>';
								print '</div>';
								print '<div class="bubble-body">';
								print '<textarea id="outsourcing_memo" class="form-control bubble-textarea" readonly>' . htmlspecialchars($outsourcing_memo) . '</textarea>';
								print '<div class="bubble-footer">';
								print '<small class="text-muted">읽기 전용</small>';
								print '</div>';
								print '</div>';
								print '</div>';
								print '</div>';
							}
							
							print '</div>';
					  }		
				?>			  
			</td>
			</tr>
			<tr>
			  <td>원청</td>
				  <td> <input type="text" id="firstord" name="firstord" value="<?=$firstord?>" class="form-control"></td>
               <td>담당</td>				  
				  <td> <input type="text" id="firstordman" name="firstordman" value="<?=$firstordman?>" class="form-control" onkeydown="JavaScript:Enter_firstCheck();"> </td>
				<td>Tel</td>				  				  
				  <td> <input type="text" id="firstordmantel" name="firstordmantel" value="<?=$firstordmantel?>" class="form-control" > </td>				
			  </td>
			</tr>
			<tr>
			  <td>발주처</td>
			  <td><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" class="form-control"></td>
			  <td>담당</td>
			  <td><input type="text" id="secondordman" name="secondordman" value="<?=$secondordman?>" class="form-control" onkeydown="JavaScript:Enter_Check();"></td>
			  <td>Tel</td>
			  <td><input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" class="form-control"></td>
			</tr>


			<tr>
			  <td>현장담당</td>
			  <td><input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control" onkeydown="JavaScript:Enter_chargedman_Check();"></td>
			  <td>Tel</td>
			  <td><input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control"></td>
			  <td>설계자</td>
			  <td><input type="text" name="designer" id="designer" value="<?=$designer?>" class="form-control"></td>
			</tr>
		<!-- New Table Rows -->
		<tr>
		  <td>접수일</td>
		  <td><input type="date" name="orderday" id="orderday" value="<?=$orderday?>" class="form-control"></td>

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
		  <td ><input type="text" name="worker" value="<?=$worker?>" class="form-control"></td>
		  <td  colspan="3">서버도면폴더</td>
		  <td><a id="dwgclick" href="#"><?=$dwglocation?></a></td>
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
				<input type="checkbox" id="boxwrap" onclick="return false;" name="boxwrap"  value="박스포장" <?php echo ($boxwrap === '박스포장' ? 'checked' : ''); ?>>
			</td>		  
		</tr>	
 <tr>
    <td>타입(Type)</td>
    <td><input type="text" id="type" name="type" value="<?=$type?>" class="form-control"></td>


    <td style="color:red;">Car inside</td>
    <td><input type="text" id="car_insize" name="car_insize" value="<?=$car_insize?>" class="form-control"></td>

    <td style="color:blue;">인승</td>
    <td><input type="text" id="inseung" name="inseung" value="<?=$inseung?>" class="form-control"></td>

  </tr>
  <tr>
    <td>결합단위(SET)
      <input type="text" name="su" value="<?=$su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>

    <td>본천장 수량
       <input type="text" name="bon_su" value="<?=$bon_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>

    <td>L/C 수량
       <input type="text" name="lc_su" value="<?=$lc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
  
    <td>기타 수량
      <input type="text" name="etc_su" value="<?=$etc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>

    <td>공기청정기
       <input type="text" name="air_su" value="<?=$air_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
    <td class="text-primary" >제품가격
       <input type="text" name="price" value="<?=$price?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
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
		
<div id="display_partner">		

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
        <td>발주1</td>
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
        <td>발주2</td>
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
        <td>발주3</td>
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
        <td>발주4</td>
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
		
		
</div>

<div class="col-sm-5 p-1  rounded"   style=" border: 1px solid #392f31; " > 	

<!-- 자재사용량 표시화면 -->
<div id="display_partInput" >

	<span class="text-center text-primary ">			
	   <h5>  천장용 자재 사용량 입력 </h5>
	</span>		   
			   
<style>
  .table td {
    padding: 2px;
    width: 16.66%; /* 6개의 요소를 가로로 배치하기 위해 1/6(16.66%)의 너비 설정 */
  }

  .table td input.form-control {
    height: 25px;
  }
</style>

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
</div>  

<!-- 자재사용량 표시화면 -->
<div id="display_process_LC">
	<div class="table-responsive">
  
			
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
        <h4 class="text-secondary"> L/C 제조 진행 상태</h4>
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
		<input class="ms-3" type="checkbox" id="cabledone" name="cabledone" value="결선완료" onclick="return false;" <?php echo ($cabledone === '결선완료' ? 'checked' : ''); ?>>
          <label for="cabledone" class="fw-bold fs-6" > 결선완료 </label>
	  </td>
      <td>조립</td>
      <td>
          <input type="date" name="lcassembly_date" id="lcassembly_date" value="<?=$lcassembly_date?>" class="form-control">
      </td>	        
    </tr>
  </tbody>
</table>  
  
</div>
</div>
<div id="display_process_Bon">    
	<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th colspan="6"><h4 class="text-success" > 본천장 제조 진행 상태</h4></th>
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
</div>
<div id="display_process_Etc">    
	<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th colspan="6" > <h4 class="text-primary" > 기타 (중판, 인테리어판넬, 발보호판 등) 제조 진행 상태 </h4> </th>
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

<div id="display_urlcopy"> 		
   <div class="input-group mt-3 mb-3">
   <button type="button" id="urlsave" class="btn btn-primary"  > 시공사진 URL 복사하기 </button>  &nbsp;  	
   
   <textarea  style="color:grey;font-size:14px;height:30px;" rows="1" cols="60" name="URL" id="URL" > <?=$URLsave?> </textarea>
   &nbsp; &nbsp; &nbsp; 
</div>	
</div>	


		
</div> 	

<div class="col-sm-2 p-1  rounded"   style=" border: 1px solid #392f31; " > 


<!-- 엑셀파일 첨부 부분 -->    
	<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">  		
					
		<input type="hidden" id="id" name="id" value="<?=$num?>" >
		<input type="hidden" id="num" name="num" value="<?=$num?>"  > 
		<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >	 	 
		<input type="hidden" id="item" name="item" value="<?=$item?>" >	 
		<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>"  > 
		<input id="pInput" name="pInput" type="hidden"  value="<?=$pInput?>" >	
		<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
		<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  											
		<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >	
		<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >	
		
			<div class="row ">				 
				 <H5  class=" text-success text-center" >  
				   비규격 파일
				 </H5> 
			</div>	
		 <div class="row mt-3 mb-1 justify-content-center p-2">  	 		 
					 <label for="upfileattached" class="btn btn-outline-primary btn-sm"> (10M 이하) excel파일 첨부 </label>						  							
					 <input id="upfileattached"  name="upfileattached[]" type="file" onchange="this.value" multiple  style="display:none" >
			</div>			
					
		<div class="row mt-3 mb-1 justify-content-center">  	 		 			
			<div id ="displayfile" class="d-flex mt-3 mb-1 justify-content-center" style="display:none;">  	 		 					
				 
			</div>			
		</div>			

		<div class="row mt-5 mb-5">
			 
		</div>	
			
					
		<div class="row">
			 <H5  class=" text-center" > 특수타입 렌더링</H5> 				 
		</div>	

	 
	 
		 <div class="row mt-1 mb-1 p-2 justify-content-center">  	
			
 				<span class="form-control p-2">			
                   <label for="upfile" class="input-group-text btn btn-outline-outline btn-sm p-2"> <H6  class=" text-center text-danger" > (이미지 파일 업로드) </H6>  </label>						  											
                   <input id="upfile"  name="upfile[]" class="input" type="file" onchange="this.value" multiple accept=".gif, .jpg, .png">
				
				</span>	
	     </div>    		
   
			 <div class="row mt-3 mb-1 justify-content-center">  	   
				<div class="card-body text-center">			
					<div id = "displayrender" class="mb-2" style="display:none;" >  </div>   
					<br>					
					
				</div>									
			</div>
		</form>
		
<div class="row p-2">
  <?php print "최초 : " . $first_writer; ?>
</div>
<div class="row p-2">
  <?php
  $update_log = preg_replace('/(\d{4})/', "\n$1", $update_log);
  ?>
  <style>
    textarea[name="update_log"] {
      background-color: #f2f2f2;
      border: 1px solid #ccc;
      white-space: pre-wrap;
    }
  </style>
  <textarea class="form-control p-3" readonly name="update_log"><?=$update_log?></textarea>
</div>		

</div> 	
	   
</div>
</div>
			
		<div class="card mt-1 mb-5" style=" border: 1px solid #392f31; ">			
	  <div class="card-header">			
		<h2 class="text-center">포장 완료사진</h2>
	  </div>
	  <div id="displayPicture" style="display: none;" class="row">
	  </div> 		
	</div>
			 
</div> 
</body>
</html>    


<!-- Add the following JavaScript code -->
<script>
  // Check if all PHP variables are empty
  function areAllVariablesEmpty() {
    return (
      <?php
        // Check if all the variables are empty and return true or false
        echo json_encode(
          empty($part1) && empty($part2) && empty($part3) && empty($part4) &&
          empty($part5) && empty($part6) && empty($part7) && empty($part8) &&
          empty($part9) && empty($part10) && empty($part11) && empty($part12) &&
          empty($part13) && empty($part14) && empty($part15) && empty($part16) &&
          empty($part17) && empty($part18) && empty($part19) && empty($part20)
        );
      ?>
    );
  }

  // Hide the div if all variables are empty
  if (areAllVariablesEmpty()) {
    document.getElementById("display_partInput").style.display = "none";
  }
</script>

<!-- 레이져 일자 없을 경우 -->
<script>
  // Check if all specified variables are empty
  function areAllVariablesEmpty(...variables) {
    for (const variable of variables) {
      if (variable.trim() !== "") {
        return false;
      }
    }
    return true;
  }

  // Variables to check
  var lclaser_date = "<?php echo $lclaser_date; ?>";
  var lcbending_date = "<?php echo $lcbending_date; ?>";
  var lcwelding_date = "<?php echo $lcwelding_date; ?>";
  var lcpainting_date = "<?php echo $lcpainting_date; ?>";
  var lcassembly_date = "<?php echo $lcassembly_date; ?>";

  // Hide the div if all specified variables are empty
  if (areAllVariablesEmpty(lclaser_date, lcbending_date, lcwelding_date, lcpainting_date, lcassembly_date)) {
    document.getElementById("display_process_LC").style.display = "none";
  }
</script>

<!-- 본천장  일자 없을 경우 -->
<script>
  // Check if all specified variables are empty
  function areAllVariablesEmpty(...variables) {
    for (const variable of variables) {
      if (variable.trim() !== "") {
        return false;
      }
    }
    return true;
  }

  // Variables to check
  var eunsung_laser_date = "<?php echo $eunsung_laser_date; ?>";
  var mainbending_date = "<?php echo $mainbending_date; ?>";
  var mainwelding_date = "<?php echo $mainwelding_date; ?>";
  var mainpainting_date = "<?php echo $mainpainting_date; ?>";
  var mainassembly_date = "<?php echo $mainassembly_date; ?>";

  // Hide the div if all specified variables are empty
  if (areAllVariablesEmpty(eunsung_laser_date, mainbending_date, mainwelding_date, mainpainting_date, mainassembly_date)) {
    document.getElementById("display_process_Bon").style.display = "none";
  }
</script>

<!-- 기타사항의 일자 없을 경우 -->
<script>
  // Check if all specified variables are empty
  function areAllVariablesEmpty(...variables) {
    for (const variable of variables) {
      if (variable.trim() !== "") {
        return false;
      }
    }
    return true;
  }

  // Variables to check
  var etclaser_date = "<?php echo $etclaser_date; ?>";
  var etcbending_date = "<?php echo $etcbending_date; ?>";
  var etcwelding_date = "<?php echo $etcwelding_date; ?>";
  var etcpainting_date = "<?php echo $etcpainting_date; ?>";
  var etcassembly_date = "<?php echo $etcassembly_date; ?>";

  // Hide the div if all specified variables are empty
  if (areAllVariablesEmpty(etclaser_date, etcbending_date, etcwelding_date, etcpainting_date, etcassembly_date)) {
    document.getElementById("display_process_Etc").style.display = "none";
  }
</script>

<!-- 협력사의 발주가 있는 경우 -->
<script>
  // Check if all specified variables are empty
  function areAllVariablesEmpty(...variables) {
    for (const variable of variables) {
      if (variable.trim() !== "") {
        return false;
      }
    }
    return true;
  }

  // Variables to check
  var order_com1 = "<?php echo $order_com1; ?>";
  var order_text1 = "<?php echo $order_text1; ?>";
  var order_com2 = "<?php echo $order_com2; ?>";
  var order_text2 = "<?php echo $order_text2; ?>";
  var order_com3 = "<?php echo $order_com3; ?>";
  var order_text3 = "<?php echo $order_text3; ?>";
  var order_com4 = "<?php echo $order_com4; ?>";
  var order_text4 = "<?php echo $order_text4; ?>";
  var order_date1 = "<?php echo $order_date1; ?>";
  var order_date2 = "<?php echo $order_date2; ?>";
  var order_date3 = "<?php echo $order_date3; ?>";
  var order_date4 = "<?php echo $order_date4; ?>";
  var order_input_date1 = "<?php echo $order_input_date1; ?>";
  var order_input_date2 = "<?php echo $order_input_date2; ?>";
  var order_input_date3 = "<?php echo $order_input_date3; ?>";
  var order_input_date4 = "<?php echo $order_input_date4; ?>";

  // Hide the div if all specified variables are empty
  if (areAllVariablesEmpty(order_com1, order_text1, order_com2, order_text2, order_com3, order_text3, order_com4, order_text4,
    order_date1, order_date2, order_date3, order_date4, order_input_date1, order_input_date2, order_input_date3, order_input_date4)) {
    document.getElementById("display_partner").style.display = "none";
  }
</script>

<!-- 포장이미지가 없는 경우 -->

<script>
  // Check if the specified array is empty
  function isArrayEmpty(arr) {
    return arr.length === 0;
  }

  // PHP array to JavaScript array conversion
  var WrappicData = <?php echo json_encode($WrappicData); ?>;

  // Hide the div if the PHP array is empty
  if (isArrayEmpty(WrappicData)) {
    document.getElementById("display_urlcopy").style.display = "none";
  }
</script>



 <script language="javascript">
$(document).ready(function(){
	
	// 외주가공 메모 말풍선 숨기기 함수
	function hideOutsourcingMemo() {
		$('#outsourcing-memo-bubble').addClass('bubble-hide');
		setTimeout(function() {
			$('#outsourcing-memo-bubble').hide().removeClass('bubble-hide');
		}, 200);
	}
	
	// 외부 클릭 시 말풍선 닫기
	$(document).on('click', function(e) {
		if (!$(e.target).closest('#outsourcing-memo-bubble, #outsourcing').length) {
			hideOutsourcingMemo();
		}
	});
	
	// 전역 함수로 등록
	window.hideOutsourcingMemo = hideOutsourcingMemo;
	
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
	
	// 화면 조회만 가능하도록 만듬
	// $("div *").disable();
   $("div *").find("input,textarea,select").prop("disabled",true);	  // disabled는 값 전달 안됨. readonly 사용
   $("#insertword").prop("disabled",false); // 자료위치 정보 풀어줌
   $("#upfile").prop("disabled",false); // 파일선택 열어줌
   $("#upfileattached").prop("disabled",false); // 파일선택 열어줌
   
   $("#id").prop("disabled",false); // 파일선택 열어줌
   $("#num").prop("disabled",false); // 파일선택 열어줌
   $("#mode").prop("disabled",false); // 파일선택 열어줌
   $("#item").prop("disabled",false); // 파일선택 열어줌
   $("#tablename").prop("disabled",false); // 파일선택 열어줌
   $("#pInput").prop("disabled",false); // 파일선택 열어줌
   $("#displayrender").prop("disabled",false); // 파일선택 열어줌
   $("#displayfile").prop("disabled",false); // excel 파일선택 열어줌
   $("#displayPicture").prop("disabled",false); // 파일선택 열어줌
   $("#savetitle").prop("disabled",false); // 열어줌
   $("#upfilename").prop("disabled",false); // 열어줌
   $("#parentid").prop("disabled",false); // 열어줌
   $("#fileorimage").prop("disabled",false); // 열어줌   
   $("#URL").prop("disabled",false); 
   $("#boxwrap").prop("disabled",false);  // 포장방식
   $("#cabledone").prop("disabled",false);  // 결선완료
   
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
		  

	// 매초 검사해서 이미지가 있으면 보여주기
	$("#pInput").val('50'); // 최초화면 사진파일 보여주기
		
	let timer3 = setInterval(() => {  // 2초 간격으로 사진업데이트 체크한다.
			  if($("#pInput").val()=='100')   // 사진이 등록된 경우
			  {
					 // displayFile(); 
					 displayPicture();  
					 // console.log(100);
			  }	      
			  if($("#pInput").val()=='50')   // 사진이 등록된 경우
			  {
					 displayFileLoad();				 				  
					 displayPictureLoad();				 
			  }	     
			   
		 }, 2000);	
		 
 
WrapdisplayPictureLoad();

// 기존 포장 사진 로드
function WrapdisplayPictureLoad() {
    $('#displayPicture').show();
    var picNum = "<?php echo $WrappicNum; ?>";
    var picData = <?php echo json_encode($WrappicData); ?>;

    $("#displayPicture").html('');
	
	var prefix = "<div class='d-flex justify-content-start mt-2 mb-1'>";
	$("#displayPicture").append(prefix);             
    picData.forEach(function(pic, index) {
        var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
        var link = pic.link || '#';
        var fileId = pic.fileId || null;

        if (!fileId) {
            console.error("fileId가 누락되었습니다. index: " + index, pic);
            return; // fileId가 없으면 해당 항목 건너뛰기
        }

        $("#displayPicture").append(             
              "<img id='pic" + index + "' src='" + thumbnail + "' style='width:350px; height:auto;'>"
        );
    });    
	$("#displayPicture").append('</div>');
}


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
 
			 
	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});
			
	$("#closeBtn").click(function(){    // 저장하고 창닫기	
		 });	
	 
	$("#urlsave").click(function(){  
	
		var content = document.getElementById('URL');
		
		alert('URL이 복사되었습니다. 붙여넣기 하세요' );			
		
		content.select();
		console.log(document.execCommand('copy'));	
			
	});		 
		
		 
});  // end of ready


// 랜더링 사진 불러오기
function displayPicture() {
    $('#displayrender').show();
    var params = $("#num").val();

    $.ajax({
        url: '/filedrive/fileprocess.php',
        type: 'GET',
        data: {
            num: params,
            tablename: 'ceiling',
            item: 'ceilingrendering',
            folderPath: 'uploads',
        },
        dataType: 'json',
    }).done(function (data) {
        console.log("사진 데이터:", data);

        $("#displayrender").html('');
        data.forEach(function(picData, index) {
            var thumbnail = picData.thumbnail || '/assets/default-thumbnail.png';
            var link = picData.link || '#';
            var fileId = picData.fileId || null;

            if (!fileId) {
                console.error("fileId가 누락되었습니다. index: " + index, picData);
                return; // fileId가 없으면 해당 항목 건너뛰기
            }

            $("#displayrender").append(
                "<div class='d-flex justify-content-center mt-2 mb-1'>" +                    
                        "<img id='pic" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +                    
                "</div>" +
                "<div class='d-flex justify-content-center'>" +
                    "<button type='button' class='mb-3 text-center btn btn-danger btn-sm' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                        "<i class='bi bi-trash'></i>" +
                    "</button>" +
                "</div>"
            );
        });
        $("#pInput").val('');
    }).fail(function (error) {
        console.error("사진 불러오기 오류:", error);
        alert("사진을 불러오는 중 문제가 발생했습니다.");
    });
}


// 기존 사진 로드
function displayPictureLoad() {
    $('#displayrender').show();
    var picNum = "<?php echo $picNum; ?>";
    var picData = <?php echo json_encode($picData); ?>;

    $("#displayrender").html('');
    picData.forEach(function(pic, index) {
        var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
        var link = pic.link || '#';
        var fileId = pic.fileId || null;

        if (!fileId) {
            console.error("fileId가 누락되었습니다. index: " + index, pic);
            return; // fileId가 없으면 해당 항목 건너뛰기
        }

        $("#displayrender").append(
            "<div class='d-flex justify-content-center mt-2 mb-1'>" +                
                    "<img id='pic" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +                
            "</div>" +
            "<div class='d-flex justify-content-center'>" +
                "<button type='button' class='mb-3 text-center btn btn-danger btn-sm' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                    "<i class='bi bi-trash'></i>" +
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
                tablename: "ceiling",
                item: "ceilingrendering",
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

var imgObj = new Image();
function showImgWin(imgName) {
imgObj.src = imgName;
setTimeout("createImgWin(imgObj)", 100);
}
function createImgWin(imgObj) {
if (! imgObj.complete) {
setTimeout("createImgWin(imgObj)", 100);
return;
}
imageWin = window.open("", "imageWin",
"width=" + imgObj.width + ",height=" + imgObj.height);
}

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


function copy_below(){	

var park = document.getElementsByName("asfee");

document.getElementById("ashistory").value  = document.getElementById("ashistory").value + document.getElementById("asday").value + " " + document.getElementById("aswriter").value+ " " + document.getElementById("asorderman").value + " ";
document.getElementById("ashistory").value  = document.getElementById("ashistory").value  + document.getElementById("asordermantel").value + " " ;
     if(park[1].checked) {
        document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 유상 " + document.getElementById("asfee").value + " ";		
	 }		 
	   else
	   {
	    document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 무상 "+ document.getElementById("asfee").value + " ";				   
	   }
	   
		document.getElementById("ashistory").value  += document.getElementById("asfee_estimate").value + " " + document.getElementById("aslist").value+ " " + document.getElementById("as_refer").value + " ";	
		document.getElementById("ashistory").value  += document.getElementById("asproday").value + " " + document.getElementById("setdate").value+ " " + document.getElementById("asman").value + " ";	
		document.getElementById("ashistory").value  += document.getElementById("asendday").value + " " + document.getElementById("asresult").value+ "        ";

}  

function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;

    }
}

 function displayoutputlist(){
	 alert("dkdkdkd");
   $("#displayoutput").show(); 
   $("#displayoutput").load("./outputlist.php");	 	 
		 
	 }
function check_alert()
{	
// load 알림설정
var tmp; 				
var name='<?php echo $user_name; ?>' ;
 
			tmp="../load_alert.php";			
			$("#vacancy").load(tmp);     
		    var voc_alert=$("#voc_alert").val();	 
		    var ma_alert=$("#ma_alert").val();	 
 		if(name=='김진억' && voc_alert=='1') {			
			alertify.alert('<H1> 현장VOC 도착 알림</H1>', '<h1> 김진억 이사님 <br> <br> 현장VOC가 접수되었습니다. 확인 후 조치바랍니다. </h1>'); 			
			tmp="../save_alert.php?voc_alert=0" + "&ma_alert=" + ma_alert;	
			$("#voc_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}
 		if(name=='조경임' && ma_alert=='1') {			
			alertify.alert('<h1> 발주서 접수 알림 </h1>', '<h1> 조부장님 <br> <br> 발주서가 접수되었습니다. 내역 확인 후 발주해 주세요. </h1>'); 			
			tmp="../save_alert.php?ma_alert=0" + "&voc_alert=" + voc_alert;	
			$("#ma_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}											
}

// clipboardCopy
function clipboardCopy(idName) { 
 var obj = document.getElementById(idName); 
 obj.select();  //인풋 컨트롤의 내용 전체 선택  
 document.execCommand("copy");  //복사  
 // obj.setSelectionRange(0, 0);  //커서 위치 초기화
 }


// 5초마다 알람상황을 체크합니다.
	var timer;
	timer=setInterval(function(){
		check_alert();
	},3000); 
 	 
function del(href) {    
	var user_name = '<?php echo $user_name; ?>';
	var first_writer = '<?php echo $first_writer; ?>';
	var admin = '<?php echo $admin; ?>';

if (first_writer.includes(user_name) && admin !== '1') 
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
								// 부모 창에 restorePageNumber 함수가 있는지 확인
								if (typeof window.opener.restorePageNumber === 'function') {
									window.opener.restorePageNumber(); // 함수가 있으면 실행
								}
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
$(document).ready(function () {
    // 첨부파일 업로드 처리
    $("#upfileattached").change(function (e) {
		if (this.files.length === 0) {
			// 파일이 선택되지 않았을 때
			console.warn("파일이 선택되지 않았습니다.");
			return;
		}	
        const form = $('#board_form')[0];
        const data = new FormData(form);

        // 추가 데이터 설정
        data.append("tablename", "ceiling");
        data.append("item", "ceiling");
        data.append("upfilename", "upfileattached"); // upfile 파일 name
        data.append("folderPath", "uploads");
        data.append("DBtable", "picuploads");

        console.log("업로드 파일:", $("#upfileattached").val());

        const tmp = '파일을 저장 중입니다. 잠시만 기다려주세요.';
        $('#alertmsg').html(tmp);
        $('#myModal').modal('show');

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
                    $('#myModal').modal('hide');
					location.reload(); 
                }, 1000);

                $("#pInput").val('100'); // 업데이트 표시
            },
            error: function (jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
            },
        });
    });
});

// 첨부된 파일 불러오기
function displayFile() {
    $('#displayfile').show();
    const params = $("#num").val();

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
            tablename: 'ceiling',
            item: 'ceiling',
            folderPath: 'uploads',
        },
        dataType: 'json',
    }).done(function (data) {
        console.log("파일 데이터:", data);

        $("#displayfile").html(''); // 기존 내용 초기화

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function (fileData, index) {
                const realName = fileData.realname || '다운로드 파일';
                const link = fileData.link || '#';
                const fileId = fileData.fileId || null;

                if (!fileId) {
                    console.error(`fileId가 누락되었습니다. index: ${index}`, fileData);
                    $("#displayfile").append(
                        `<div class="text-danger">파일 ID가 누락되었습니다.</div>`
                    );
                    return;
                }

                $("#displayfile").append(
                    `<div class='row mb-3' id='file${index}'>
                        <div class='col d-flex align-items-center justify-content-center'>
                            <a href='${link}' download='${realName}'>${realName}</a>
                        </div>
                        <div class='col d-flex justify-content-center'>
                            <button type='button' class='btn btn-danger btn-sm' id='delFile${index}' onclick="delFileFn('${index}', '${fileId}')">
                                <i class='bi bi-trash'></i> 삭제
                            </button>
                        </div>
                    </div>`
                );
            });
        } else {
            $("#displayfile").append(
                "<div class='text-center text-muted'>등록된 파일이 없습니다.</div>"
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

// 기존 파일 불러오기
// 기존 파일 불러오기 (Google Drive에서 가져오기)
function displayFileLoad() {
    $('#displayfile').show();
    var data = <?php echo json_encode($savefilename_arr); ?>;

    $("#displayfile").html(''); // 기존 내용 초기화

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
            $("#displayfile").append(
                "<div class='row mb-3'>" +
                    "<div id='file" + i + "'class='col d-flex align-items-center justify-content-center'>" +
                        "<a href='" + link + "' download='" + realName + "'>" +
                            realName +
                        "</a>" +
                    "</div>" +
                    "<div class='col d-flex justify-content-center'>" +
                        "<button type='button' class='btn btn-danger btn-sm' id='delFile" + i + "' onclick=\"delFileFn('" + i + "', '" + fileId + "')\">" +
                            "<i class='bi bi-trash'></i> " +
                        "</button>" +
                    "</div>" +
                "</div>"
            );
        });
    } else {
        $("#displayfile").append(
            "<div class='text-center text-muted'>등록된 파일이 없습니다.</div>"
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
					tablename: "ceiling",
					item: "ceiling",
					folderPath: "uploads",
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