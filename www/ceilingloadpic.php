<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
require_once getDocumentRoot() . '/vendor/autoload.php';
require_once(includePath('lib/mydb.php'));

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';

include getDocumentRoot() . '/load_header.php';
  
$num=$_REQUEST["num"];

$pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.ceiling where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
    $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
  
	$workplacename=$row["workplacename"];
	$secondord=$row["secondord"];
	$deadline=$row["deadline"];

	$type=$row["type"];			  
	$inseung=$row["inseung"];			  	
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
   

$picNum=0; 
$picData=array(); 

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';

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

    
 ?>
 
 
 <title> 미래기업 천장&LC 출하관련 사진 </title>
 </head>
  <body>
 <style>
    .rotated {
  transform: rotate(90deg);
  -ms-transform: rotate(90deg); /* IE 9 */
  -moz-transform: rotate(90deg); /* Firefox */
  -webkit-transform: rotate(90deg); /* Safari and Chrome */
  -o-transform: rotate(90deg); /* Opera */
}
</style> 

 <div  class="container">
    <div class="card">
     <div class="card-title justify-content-center ">
	 	<div class="d-flex  mb-1 justify-content-center fs-3"> 
			현장명 :       <?=$workplacename?>	
		</div>
	 </div>
       <div class="card-body ">	
	   
				<div class="table-reponsive mb-2  fs-4">
				<table class="table table-bordered">
				   <tbody>				   
					 <tr>
					   <td class="text-center fw-bold" > 발주처 </td>
							<td class="text-center" >	
								  <?=$secondord?>  
							</td>
					  <td class="text-center fw-bold" > 인승 </td>
					  <td class="text-center" >								
							<?=$inseung?> 					  
					  </td>
					  <td class="text-center fw-bold" > 타입 </td>
					     <td class="text-center" >	
							<?=$type?>                                                
						  </td>
						<td class="text-center fw-bold" > 납품일자 </td>
						<td class="text-center" >	
							  <?=$deadline?>                                               
						</td>
					  </tr>
					  
						
				</tbody>
				</table>
				</div>		   
	   
	   	   		   
		     <div class="d-flex  mt-2 mb-1 justify-content-center fs-3"> 
					(주)미래기업  출하관련 사진 
			  </div>
	    <div class="d-flex  mt-2 mb-1 justify-content-center fs-3"> 	      
		<div class="row">	 
					<div id = "displayPicture" style="display:none;" > </div> 
		</div>		   
	   </div>
	   </div> 
 </div>
 </div>
 
 
<script>

$(document).ready(function () {
    // 모달 닫기 버튼
    $("#closeModalBtn").click(function () {
        $('#myModal').modal('hide');
    });

		displayPictureLoad(); // 기존 사진 로드
});

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
                "<img id='pic" + index + "' src='" + thumbnail + "' style='width:400px; height:auto;'>" +
            "</div>"
        );
    });
}


</script>
 
</body>
</html>    
 
 