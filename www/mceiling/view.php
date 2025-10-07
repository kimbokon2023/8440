<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once("../lib/mydb.php");
$pdo = db_connect();

// Google API 클라이언트 로드 확인
if (!class_exists('Google_Client') && !class_exists('\Google\Client')) {
    die('Google API client library not found. Please run: composer install');
}


// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json';

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
<style> 
#panel, #flip {
  padding: 3px;
  text-align: left;
  color:brown;
  border: solid 1px #c3c3c3;
}

#panel {
  padding: 40px;
  display: none;
}

#addpanel, #addflip {
  padding: 3px;
  text-align: center;
  color:white;
  background-color:grey;
  border: solid 1px #c3c3c3;
}

#addpanel {
  padding: 30px;
  display: none;
}

.table th {
    vertical-align: middle;
	font-size : 16px;
}

.table td {
    vertical-align: middle;
	font-size : 16px;	
}

@media (min-width: 800px) {
	.table th {    
		font-size : 22px;
	}

	.table td {    
		font-size : 22px;	
	}
}

/* 체크박스 크게 보이게 하기 */
input[type="checkbox"] {
    transform: scale(1.6); /* 크기를 1.5배로 확대 */
    margin-right: 10px;   /* 확대 후의 여백 조정 */
}
</style>

<title> 천장/LC </title>
</head>
<body> 
 <?php 
  // 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        body, table th, table td, h3, .form-control {
            font-size: 30px;
        }
         h4 {
            font-size: 35px; 
        }
		.btn-sm {
			font-size: 26px;
		}		
    </style>';
}
     
$num=$_REQUEST["num"] ?? '';
  
$picNum=0; 
$picData=array(); 

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
$picData = [];
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

 
 // print_r($picData);
 
 try{
     $sql = "select * from mirae8440.ceiling where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
 
    include '../ceiling/_row.php';
		
  $workday=trans_date($workday);
  $demand=trans_date($demand);
  $orderday=trans_date($orderday);
  $deadline=trans_date($deadline);
  $testday=trans_date($testday);
  $lc_draw=trans_date($lc_draw);
  $lclaser_date=trans_date($lclaser_date);
  $lcbending_date=trans_date($lcbending_date);
  $lcwelding_date=trans_date($lcwelding_date);
  $lcpainting_date=trans_date($lcpainting_date);
  $lcassembly_date=trans_date($lcassembly_date);
  $main_draw=trans_date($main_draw);			
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
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  
	   $main_draw_arr="";			  
	  if(substr($main_draw,0,2)=="20")  $main_draw_arr= iconv_substr($main_draw,0,10,"utf-8");		    
		 elseif($bon_su<1) $main_draw_arr= "X";		    

		$lc_draw_arr="";			  
	  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr= iconv_substr($lc_draw,0,10,"utf-8") ;
		 elseif($lc_su<1) $lc_draw_arr = "X";	
	  if($type=='011'||$type=='012'|| $type=='013D'||$type=='025'||$type=='017'||$type=='014')
							 $lc_draw_arr = "X";	
  
     if((int)$bon_su>0) {
	
				}
	  else
	  {
	    $eunsung_laser_date ="X";
        $mainbending_date = "X";
        $mainwelding_date = "X";
        $mainpainting_date ="X";
        $mainassembly_date ="X";
	  }



   if((int)$lc_su>0 && $type!='011' && $type!='012' && $type!='013D' && $type!='025' && $type!='017' && $type!='014'  && $type!='037'  && $type!='038'  ) {
		
				}
	  else
	  {
	    $lclaser_date ="X";
        $lcbending_date = "X";
        $lcwelding_date = "X";
        $lcpainting_date ="X";
        $lcassembly_date ="X";
	  }

    if((int)$etc_su < 1 ) {
      $etclaser_date ="X";
      $etcwelding_date = "X";
      $etcpainting_date = "X";
      $etcassembly_date ="X";
      $etcbending_date ="X";
      }

			  $workplacename = "[".$secondord ."]". $workplacename;			
   
 ?>

		 
<body>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">  
		      
  <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
  <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
  <input type="hidden" id="check_draw" name="check_draw" value="<?=$check_draw?>" size="1" > 	
  <input type="hidden" id="scale" name="scale" value="<?=$scale?>" > 	
  <input type="hidden" id="num" name="num" value="<?=$num?>" > 	
  <input type="hidden" id="id" name="id" value="<?=$num?>" >	   
  <input type="hidden" id="workplacename" name="workplacename" value="<?=$workplacename?>" >	   
  <input id="pInput" name="pInput" type="hidden" value="0" >	
  <input id="vacancy" name="vacancy" type="hidden" >	

<div  class="container-fluid">  
<div  class="card">  
<div  class="card-body">   
	<div class="d-flex justify-content-start mb-2">	    
		 <button class="btn btn-sm" onclick="window.close();" 
			 style="background: linear-gradient(90deg, #e0e4ea 0%, #cfd8dc 100%); color: #495057; font-weight:600; border:1px solid #b0bec5; box-shadow: 0 2px 8px rgba(180,180,180,0.08);"
		 > &times; 닫기 </button>&nbsp; 
	</div>
	<div class="d-flex mt-2 mb-2 justify-content-center">	     	        
		  
			 <h2>
				<span class="badge" style="background: linear-gradient(90deg, #e0e4ea 0%, #cfd8dc 100%); color: #495057; font-weight:600; border:1px solid #b0bec5; box-shadow: 0 2px 8px rgba(180,180,180,0.08);">
					천장/LC 조회 수정
				</span>
			 </h2>
       <button 
           class="btn btn-sm mx-2" 
           id="viewOrder" 
           onclick="navigateToLink(event, '../ceiling/view.php?num=<?=$num?>'); return false;" 
           style="background: linear-gradient(90deg, #ececec 0%, #d3d3d3 100%); color: #333; border: 1px solid #bdbdbd; box-shadow: 0 2px 6px rgba(180,180,180,0.08); font-weight: 500;"
       >
           <i class="bi bi-arrow-right-circle" style="color:#888;"></i> 수주서 이동
       </button>
	</div>
	<div class="d-flex mt-3 mb-2 justify-content-center">	     	
			
		  <label for="upfile" class="form-control text-center fs-4 mx-3 " style="width:20%;">  포장사진등록  </label>	  
		  <input id="upfile" name="upfile[]" type="file"  class="form-control fs-4" style="width:30%;" onchange="this.value" placeholder="포장사진등록"  multiple accept=".gif, .jpg, .png" >	  

	</div>	   
			
		<div class="table-responsive">
			<table class="table table-bordered table-sm">
				<thead class="table-primary">
					<tr>
						<th class="text-center"> 현장명</th>
						<th class="text-center"> 발주접수일</th>
						<th class="text-center"> 납기일</th>
						<th class="text-center"> 본천장설계</th>
						<th class="text-center"> LC설계</th>
						<th class="text-center"> 기타설계</th>
						<th class="text-center"> 결합(SET)</th>
						<th class="text-center"> 본천장</th>
						<th class="text-center"> L/C</th>
						<th class="text-center"> 기타 </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td  class="text-start"><?=$workplacename?></td>
						<td  class="text-center"><?=$orderday?></td>
						<td  class="text-center"><?=$deadline?></td>
						<td  class="text-center"><?=$main_draw_arr?></td>
						<td  class="text-center"><?=$lc_draw_arr?></td>
						<td  class="text-center"><?=$etc_draw_arr?></td>
						<td  class="text-center"><?=$su?></td>
						<td  class="text-center"><?= (int)$bon_su > 0 ? $bon_su : '' ?></td>
						<td  class="text-center"><?= (int)$lc_su > 0 ? $lc_su : '' ?></td>
						<td  class="text-center"><?= (int)$etc_su > 0 ? $etc_su : '' ?></td>
					</tr>
					<tr>
						<td  colspan="10" class="text-start text-primary fw-bold"> <span class="text-dark"> 비고 : </span> <?=$memo?></td>
					</tr>
				</tbody>
			</table>
		</div>

 <!-- 외주가공 있을시  -->
<?php if (!empty($outsourcing)): ?>
  <div class="row justify-content-center">
      <div class="badge bg-success text-white text-center my-2" style="width:35%;">
          <h3 class="mb-0">외주가공 있음</h3> 
      </div>
  </div>
<?php endif; ?>	    
<?php if (!empty($outsourcing)): ?>
  <div class="row justify-content-center">
      <div class="text-danger text-center my-2" >          
          <h5 class="mb-0"> <?=$outsourcing_memo?>  </h5>
      </div>
  </div>
<?php endif; ?>	    

<div class="row" >
<?php if ($chkMobile): ?>
	<div class="col-sm-12 rounded"   style=" border: 2px solid #392f31; " > 
<?php else: ?>     
	<div class="col-sm-4 rounded"   style=" border: 2px solid #392f31; " > 
<?php endif; ?>	
	 <table class="table table-bordered table-sm" >
	  <thead> 
		 <tr>
		   <th colspan="2" class="text-primary text-center " >  <h4> 본천장 제조현황  </h4> </th>
	   <tr>
	   </thead>
	  <tbody>
		<tr>
		  <td class="text-center"> 본 laser완료 </td>
		  <td class="text-center">  
		  <div class="d-flex justify-content-center align-items-center">
			<input type="text" name="eunsung_laser_date"  id="eunsung_laser_date" class="form-control w120px fs-6 "   style="text-align:center;" value="<?=$eunsung_laser_date?>" > &nbsp; &nbsp;
				 <?php  if($eunsung_laser_date!='X') 
						 {
							print   ' <button type="button" class="btn btn-primary  me-4  " onclick="saveData(' . "'eunsung_laser_date'" . ');"> 완료  </button>   ';
							print   ' <button type="button" class="btn btn-danger   " onclick="dodatadel(' . "'eunsung_laser_date'" . ');"> 삭제  </button> ';
						 }
							?>
			  </div>
		  </td>
		</tr>      
		<tr>
	  <td class="text-center"> 본 절곡 </td>
	  <td class="text-center">
		<div class="d-flex justify-content-center align-items-center">
		  <input type="text" name="mainbending_date" id="mainbending_date" class="form-control w120px fs-6" style="text-align:center;" value="<?=$mainbending_date?>"> &nbsp; &nbsp;
		  <?php if ($mainbending_date != 'X') {
			print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'mainbending_date'" . ');"> 완료 </button> ';
			print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'mainbending_date'" . ');"> 삭제 </button> ';
		  } ?>
		</div>
	  </td>
	</tr>

	<tr>
	  <td class="text-center"> 본 제관 </td>
	  <td class="text-center">
		<div class="d-flex justify-content-center align-items-center">
		  <input type="text" name="mainwelding_date" id="mainwelding_date" class="form-control w120px fs-6" style="text-align:center;" value="<?=$mainwelding_date?>"> &nbsp; &nbsp;
		  <?php if ($mainwelding_date != 'X') {
			print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'mainwelding_date'" . ');"> 완료 </button> ';
			print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'mainwelding_date'" . ');"> 삭제 </button> ';
		  } ?>
		</div>
	  </td>
	</tr>

	<tr>
	  <td class="text-center"> 본 도장 </td>
	  <td class="text-center">
		<div class="d-flex justify-content-center align-items-center">
		  <input type="text" name="mainpainting_date" id="mainpainting_date" class="form-control w120px fs-6" style="text-align:center;" value="<?=$mainpainting_date?>"> &nbsp; &nbsp;
		  <?php if ($mainpainting_date != 'X') {
			print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'mainpainting_date'" . ');"> 완료 </button> ';
			print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'mainpainting_date'" . ');"> 삭제 </button> ';
		  } ?>
		</div>
	  </td>
	</tr>

	<tr>
	  <td class="text-center"> 본 조립 </td>
	  <td class="text-center">
		<div class="d-flex justify-content-center align-items-center">
		  <input type="text" name="mainassembly_date" id="mainassembly_date" class="form-control w120px fs-6" style="text-align:center;" value="<?=$mainassembly_date?>"> &nbsp; &nbsp;
		  <?php if ($mainassembly_date != 'X') {
			print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'mainassembly_date'" . ');"> 완료 </button> ';
			print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'mainassembly_date'" . ');"> 삭제 </button> ';
		  } ?>
		</div>
	  </td>
	</tr>

	   </tbody>
	</table>
  </div>

  
<?php if ($chkMobile): ?>
	<div class="col-sm-12 rounded"   style=" border: 2px solid #392f31; " > 
<?php else: ?>     
	<div class="col-sm-4 rounded"   style=" border: 2px solid #392f31; " > 
<?php endif; ?>	
	 <table class="table table-bordered table-sm" >
      <thead> 
      <tr>
        <th colspan="2"  class=" text-center  text-danger"> <h5> L/C (Type : <?=$type?>)  </h5>  </th>
        <tr>
      </thead>
      <tbody>
      <tr>
        <td class="text-center"> L/C laser </td>
        <td class="text-center"> 
        <div class="d-flex justify-content-center align-items-center">
        <input type="text" name="lclaser_date"  id="lclaser_date" class="form-control text-center w120px fs-6 "  value="<?=$lclaser_date?>" > &nbsp; &nbsp;
          <?php  if($lclaser_date!='X') 
              {
                print   ' <button type="button" class="btn btn-primary   me-4   " onclick="saveData(' . "'lclaser_date'" . ');"> 완료  </button>   ';
                print   ' <button type="button" class="btn btn-danger   " onclick="dodatadel(' . "'lclaser_date'" . ');"> 삭제  </button> ';
              }
                ?>
          </div>
        </td>
      </tr>      
  <tr>
    <td class="text-center"> L/C 절곡 </td>
    <td class="text-center">
      <div class="d-flex justify-content-center align-items-center">
        <input type="text" name="lcbending_date" id="lcbending_date" class="form-control text-center w120px fs-6" value="<?=$lcbending_date?>"> &nbsp; &nbsp;
        <?php if ($lcbending_date != 'X') {
          print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'lcbending_date'" . ');"> 완료 </button> ';
          print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'lcbending_date'" . ');"> 삭제 </button> ';
        } ?>
      </div>
    </td>
  </tr>

  <tr>
    <td class="text-center"> L/C 제관 </td>
    <td class="text-center">
      <div class="d-flex justify-content-center align-items-center">
        <input type="text" name="lcwelding_date" id="lcwelding_date" class="form-control text-center w120px fs-6" value="<?=$lcwelding_date?>"> &nbsp; &nbsp;
        <?php if ($lcwelding_date != 'X') {
          print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'lcwelding_date'" . ');"> 완료 </button> ';
          print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'lcwelding_date'" . ');"> 삭제 </button> ';
        } ?>
      </div>
    </td>
  </tr>
  <tr>
    <td class="text-center"> L/C 도장 </td>
    <td class="text-center">
      <div class="d-flex justify-content-center align-items-center">
        <input type="text" name="lcpainting_date" id="lcpainting_date" class="form-control text-center w120px fs-6" value="<?=$lcpainting_date?>"> &nbsp; &nbsp;
        <?php if ($lcpainting_date != 'X') {
          print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'lcpainting_date'" . ');"> 완료 </button> ';
          print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'lcpainting_date'" . ');"> 삭제 </button> ';
        } ?>
      </div>
    </td>
  </tr>
  <tr>
      <td colspan="2" class="text-danger text-center" >
      <input class="ms-3 fs-5" type="checkbox" id="cabledone" name="cabledone" value="결선완료" <?php echo ($cabledone === '결선완료' ? 'checked' : ''); ?>>
            <label for="cabledone" class="fw-bold fs-5" > 결선완료 </label>
      </td>
  </tr>
  <tr>
    <td class="text-center"> L/C 포장 </td>
    <td class="text-center">
      <div class="d-flex justify-content-center align-items-center">
        <input type="text" name="lcassembly_date" id="lcassembly_date" class="form-control text-center w120px fs-6" value="<?=$lcassembly_date?>"> &nbsp; &nbsp;
        <?php if ($lcassembly_date != 'X') {
          print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'lcassembly_date'" . ');"> 완료 </button> ';
          print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'lcassembly_date'" . ');"> 삭제 </button> ';
        } ?>
      </div>
    </td>
  </tr>		
	   </tbody>
	</table>
  </div>

<?php if ($chkMobile): ?>
	<div class="col-sm-12 rounded"   style=" border: 2px solid #392f31; " > 
<?php else: ?>     
	<div class="col-sm-4  rounded"   style=" border: 2px solid #392f31; " > 
<?php endif; ?>	
	 <table class="table table-bordered table-sm" >
	  <thead> 
		 <tr>
		   <th colspan="2"  class=" text-center  text-secondary"> <h4> 기타  </h4>  </th>
		   <tr>
	   </thead>
	  <tbody>
		<tr>
		  <td class="text-center"> 기타 레이저 </td>
		  <td class="text-center"> 
		  <div class="d-flex justify-content-center align-items-center">
			<input type="text" name="etclaser_date"  id="etclaser_date" class="form-control text-center w120px fs-6 "  value="<?=$etclaser_date?>" > &nbsp; &nbsp;
				 <?php  if($etclaser_date!='X') 
						 {
						print   ' <button type="button" class="btn btn-primary   me-4   " onclick="saveData(' . "'etclaser_date'" . ');"> 완료  </button>   ';
						print   ' <button type="button" class="btn btn-danger   " onclick="dodatadel(' . "'etclaser_date'" . ');"> 삭제  </button> ';
						 }
							?>
				</div>
		  </td>
		</tr>      
<tr>
  <td class="text-center"> 기타 절곡 </td>
  <td class="text-center">
    <div class="d-flex justify-content-center align-items-center">
      <input type="text" name="etcbending_date" id="etcbending_date" class="form-control text-center w120px fs-6" value="<?=$etcbending_date?>"> &nbsp; &nbsp;
      <?php if ($etcbending_date != 'X') {
		print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'etcbending_date'" . ');"> 완료 </button> ';
		print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'etcbending_date'" . ');"> 삭제 </button> ';
      } ?>
    </div>
  </td>
</tr>

<tr>
  <td class="text-center"> 기타 제관 </td>
  <td class="text-center">
    <div class="d-flex justify-content-center align-items-center">
      <input type="text" name="etcwelding_date" id="etcwelding_date" class="form-control text-center w120px fs-6" value="<?=$etcwelding_date?>"> &nbsp; &nbsp;
      <?php if ($etcwelding_date != 'X') {
		print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'etcwelding_date'" . ');"> 완료 </button> ';
		print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'etcwelding_date'" . ');"> 삭제 </button> ';
      } ?>
    </div>
  </td>
</tr>

<tr>
  <td class="text-center"> 기타 도장 </td>
  <td class="text-center">
    <div class="d-flex justify-content-center align-items-center">
      <input type="text" name="etcpainting_date" id="etcpainting_date" class="form-control text-center w120px fs-6" value="<?=$etcpainting_date?>"> &nbsp; &nbsp;
      <?php if ($etcpainting_date != 'X') {
		print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'etcpainting_date'" . ');"> 완료 </button> ';
		print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'etcpainting_date'" . ');"> 삭제 </button> ';
      } ?>
    </div>
  </td>
</tr>
<tr>
  <td class="text-center"> 기타 조립 </td>
  <td class="text-center">
    <div class="d-flex justify-content-center align-items-center">
      <input type="text" name="etcassembly_date" id="etcassembly_date" class="form-control text-center w120px fs-6" value="<?=$etcassembly_date?>"> &nbsp; &nbsp;
      <?php if ($etcassembly_date != 'X') {
		print ' <button type="button" class="btn btn-primary  me-4" onclick="saveData(' . "'etcassembly_date'" . ');"> 완료 </button> ';
		print ' <button type="button" class="btn btn-danger " onclick="dodatadel(' . "'etcassembly_date'" . ');"> 삭제 </button> ';
      } ?>
    </div>
  </td>
</tr> 
	
	   </tbody>
	</table>
  </div>
  </div>

	<div class="row mt-2 mb-2">  &nbsp;&nbsp;&nbsp; </div>	
		   
	<div class="row mt-2 mb-2">
		<h2 class="fs-2 font-center text-center">
			<div id="addflip">추가정보 보기</div>
		</h2>
	</div>
	<div id="addpanel">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td class="  font-center text-center" colspan="3" >현장주소</td>
					<td class="  font-center text-center"><?=$address?></td>
				</tr>
				<tr>
					<td class="  font-center text-center"  style="width:15%;" >제품출고일</td>
					<td class="  font-center text-center"><?=$workday?></td>				
					<td class="  font-center text-center">원 청</td>
					<td class="  font-center text-center"><?=$firstord?></td>
				</tr>
				<tr>
					<td class="  font-center text-center">담당</td>
					<td class="  font-center text-center"><?=$firstordman?></td>
					<td class="  font-center text-center">연락처</td>
					<td class="  font-center text-center"><?=$firstordmantel?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">발주처</td>
					<td class="  font-center text-center"><?=$secondord?></td>
					<td class="  font-center text-center">담당</td>
					<td class="  font-center text-center"><?=$secondordman?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">연락처</td>
					<td class="  font-center text-center"><?=$secondordmantel?></td>
					<td class="  font-center text-center">운반비내역</td>
					<td class="  font-center text-center"><?=$delivery?> <?=$delipay?></td>
				</tr>
				<tr>
					
					<td class="  font-center text-center">담당</td>
					<td class="  font-center text-center"><?=$chargedman?></td>
					<td class="  font-center text-center">연락처</td>
					<td class="  font-center text-center"><?=$chargedmantel?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">타입</td>
					<td class="  font-center text-center"><?=$type?> &nbsp;&nbsp;&nbsp; 인승: <?=$inseung?></td>
					<td class="  font-center text-center">car insize</td>
					<td class="  font-center text-center"><?=$car_insize?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">발주1</td>
					<td class="  font-center text-center"><?=$order_com1?> &nbsp;&nbsp;&nbsp; <?=$order_text1?> &nbsp;&nbsp;&nbsp; <?=$order_date1?> &nbsp;&nbsp;&nbsp; <?=$order_input_date1?></td>					
					<td class="  font-center text-center">발주2</td>
					<td class="  font-center text-center"><?=$order_com2?> &nbsp;&nbsp;&nbsp; <?=$order_text2?> &nbsp;&nbsp;&nbsp; <?=$order_date2?> &nbsp;&nbsp;&nbsp; <?=$order_input_date2?></td>					
				</tr>
				<tr>					
					<td class="  font-center text-center">발주3</td>
					<td class="  font-center text-center"><?=$order_com3?> &nbsp;&nbsp;&nbsp; <?=$order_text3?> &nbsp;&nbsp;&nbsp; <?=$order_date3?> &nbsp;&nbsp;&nbsp; <?=$order_input_date3?></td>
					<td class="  font-center text-center">발주4</td>
					<td class="  font-center text-center"><?=$order_com4?> &nbsp;&nbsp;&nbsp; <?=$order_text4?> &nbsp;&nbsp;&nbsp; <?=$order_date4?> &nbsp;&nbsp;&nbsp; <?=$order_input_date4?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">재질1</td>
					<td class="  font-center text-center"><?=$material2?> &nbsp;&nbsp;&nbsp; <?=$material1?></td>
					<td class="  font-center text-center">재질2</td>
					<td class="  font-center text-center"><?=$material4?> &nbsp;&nbsp;&nbsp; <?=$material3?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">재질3</td>
					<td class="  font-center text-center"><?=$material6?> &nbsp;&nbsp;&nbsp; <?=$material5?></td>
					<td class="  font-center text-center">비고1</td>
					<td class="  font-center text-center"><?=$memo?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">비고2</td>
					<td class="  font-center text-center"><?=$memo2?></td>
					<td class="  font-center text-center">청구일자</td>
					<td class="  font-center text-center"><?=$demand?></td>
				</tr>
				<tr>					
					<td class="  font-center text-center">최초등록</td>
					<td class="  font-center text-center" colspan="3"><?=$first_writer?></td>
				</tr>
				<tr>
					<td class="  font-center text-center">log</td>
					<td class="font-center text-start" colspan="3" ><?=$update_log?></td>
					
				</tr>
			</tbody>
		</table>
	</div>

	   
	   
	   
	<div class="row">	 
				<div id = "displayPicture" style="display:none;" > </div> 
	</div>
			</div>
  </div>
			</div>
  </form>

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
        FileProcess();
    });

    // 파일 업로드 처리 함수
    function FileProcess() {
        const num = $("#num").val();
        const form = $('#board_form')[0];
        const data = new FormData(form);

        // 추가 데이터 설정
        data.append("tablename", "ceilingwrap");
        data.append("item", "ceilingwrap");
        data.append("folderPath", "imgwork");
        data.append("DBtable", "picuploads");

        console.log("업로드 파일:", $('#upfile').val());

		showMsgModal(1); // 파일저장중

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
                // console.log("응답 데이터:", response);

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
					    hideMsgModal();
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
            tablename: 'ceilingwrap',
            item: 'ceilingwrap',
            folderPath: 'imgwork',
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
                            "<img id='pic" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +                        
                    "</div>" +
                    "<div class='d-flex justify-content-center'>" +
                        "<button type='button' class='mb-3 text-center btn btn-danger ' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                            "<i class='bi bi-trash'></i>" +
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
                    "<img id='pic" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +                
            "</div>" +
            "<div class='d-flex justify-content-center'>" +
                "<button type='button' class='mb-3 text-center btn btn-danger ' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                    "<i class='bi bi-trash'></i> " +
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
                tablename: "ceilingwrap",
                item: "ceilingwrap",
                folderPath: "imgwork",
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

	 
 function displayoutputlist(){
		alert("dkdkdkd");
		$("#displayoutput").show(); 
		$("#displayoutput").load("./outputlist.php");	 	 		 
 }

	
  $(document).ready(function(){
  
	$("#addflip").click(function(){
    $("#addpanel").slideToggle();
  });  
  
  $("#addpanel").click(function(){
    $("#addpanel").slideUp("slow");
  });  
});


function saveData(anyone) {	
    var id="#" + anyone ;	
    var num = <?php echo $num; ?>; 
	var tmp="./insert.php?num="+ num +"&data=" + anyone ;	
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

     // console.log(anyone  );
		if(dd<10) {
			dd='0'+dd;
		} 

		if(mm<10) {
			mm='0'+mm;
		} 
		today = yyyy+'-'+mm+'-'+dd;
		$("#vacancy").load(tmp);     
		$(id).val(today);
		if (window.opener && !window.opener.closed) {
			// 부모 창에 restorePageNumber 함수가 있는지 확인
			if (typeof window.opener.restorePageNumber === 'function') {
				window.opener.restorePageNumber(); // 함수가 있으면 실행
			}
			window.opener.location.reload(); // 부모 창 새로고침
		}			
		
}


function dodatadel(anyone) {	
	var anyone;
    var id="#" + anyone ;	
    var num = <?php echo $num; ?>; 
	var tmp="./insert.php?num="+ num +"&deldata=" + anyone ;	
	
			$("#vacancy").load(tmp);     
		    $(id).val('');
		if (window.opener && !window.opener.closed) {
			// 부모 창에 restorePageNumber 함수가 있는지 확인
			if (typeof window.opener.restorePageNumber === 'function') {
				window.opener.restorePageNumber(); // 함수가 있으면 실행
			}
			window.opener.location.reload(); // 부모 창 새로고침
		}				
			
}

function dodata_all(anyone) {	
	var anyone;
    var id;	
    var num = <?php echo $num; ?>; 
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
    var tmp;	
	var yyyy = today.getFullYear();
	var arr=[];
	
		if(dd<10) {
			dd='0'+dd;
		} 

		if(mm<10) {
			mm='0'+mm;
		}        
	 today = yyyy+'-'+mm+'-'+dd;
	 
		arr[0]='eunsung_laser_date';
		arr[1]='lclaser_date';
		arr[2]='mainbending_date';
		arr[3]='lcbending_date';
		arr[4]='mainwelding_date';
		arr[5]='lcwelding_date';
		arr[6]='mainpainting_date';
		arr[7]='lcpainting_date';
		arr[8]='mainassembly_date';
		arr[9]='lcassembly_date';
		arr[10]='etclaser_date';
		arr[11]='etcbending_date';
		arr[12]='etcwelding_date';
		arr[13]='etcpainting_date';
		arr[14]='etcassembly_date';

 for(i=0;i<15;i++) {
	        tmp="./insert.php?num="+ num +"&data=" + arr[i] ;	
			$("#vacancy").load(tmp);     
			id="#" + arr[i];	
		    $(id).val(today);
	   }
}


function dodatadel_all(anyone) {
	
	var anyone;
    var id="#" + anyone ;	
    var num = <?php echo $num; ?>; 
	var tmp="./insert.php?num="+ num +"&deldata=" + anyone ;		
			$("#vacancy").load(tmp);     
		    $(id).val('');
}
 	
	
$(document).ready(function(){
    $("#cabledone").change(function(){  
        var checked = $(this).is(":checked") ? "결선완료" : ""; // 체크 여부 확인
        var num = $("#num").val();  // 현재 작업의 num 값

        $.ajax({
            url: "insert_cabledone.php",
            type: "POST",
            data: { mode: "modify", num: num, cabledone: checked },
            dataType: "json",
            success: function(response) {
                if(response.num) {
                    Swal.fire({
                        title: '저장 완료',
                        text: '결선 상태가 저장되었습니다.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: ", status, error);
                Swal.fire({
                    title: '저장 실패',
                    text: '서버에 데이터를 저장하는 데 실패했습니다.',
                    icon: 'error'
                });
            }
        });
    });
});

function navigateToLink(event, url) {
    // 이벤트의 대상이 <a> 태그가 아닐 경우에만 링크를 따라갑니다.
    if (event.target.tagName !== 'A') {
        Swal.fire({
            title: '수주서로 이동하시겠어요?',
            text: '세부 내역 페이지로 이동합니다.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '이동',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
                customPopup(url, '세부 내역', 1900, 980);
            }
        });
    }
}

</script>
</body>
</html>