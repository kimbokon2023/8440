<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php';

// Google Drive 서비스 계정 인증 설정
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json');
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes([Google_Service_Drive::DRIVE]);
$service = new Google_Service_Drive($client);

header("Content-Type: application/json"); // JSON 응답 설정

// 특정 경로의 폴더 확인 또는 생성 (재귀적 처리)
function getOrCreateFolderByPath($service, $path) {
    $pathParts = explode('/', $path);
    $parentId = null; // 최상위 루트

    foreach ($pathParts as $part) {
        if (empty($part)) continue;

        $query = "name='$part' and mimeType='application/vnd.google-apps.folder' and trashed=false";
        if ($parentId) {
            $query .= " and '$parentId' in parents";
        }

        $response = $service->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)'
        ]);

        if (count($response->files) > 0) {
            $parentId = $response->files[0]->id;
        } else {
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $part,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => $parentId ? [$parentId] : []
            ]);
            $folder = $service->files->create($fileMetadata, ['fields' => 'id']);
            $parentId = $folder->id;
        }
    }

    return $parentId;
}

// 파일 공개 설정 함수
function setFilePublic($service, $fileId) {
    $permission = new Google_Service_Drive_Permission();
    $permission->setRole('reader');
    $permission->setType('anyone');

    try {
        $service->permissions->create($fileId, $permission);
        return true;
    } catch (Exception $e) {
        error_log("권한 설정 실패: " . $e->getMessage());
        return false;
    }
}

// HTTP 메서드에 따른 처리
$method = $_SERVER['REQUEST_METHOD'];
$folderPath = $_REQUEST['folderPath'] ?? '미래기업/uploads'; // 기본 폴더 경로
$DBtable = $_REQUEST['DBtable'] ?? 'picuploads' ;
$uploadsFolderId = getOrCreateFolderByPath($service, $folderPath);
$upfilename = $_REQUEST['upfilename'] ?? 'upfile';
// num이 없으면 timekey를 받아온다.
$parentnum = !empty($_REQUEST["num"]) ? $_REQUEST["num"] : $_REQUEST["timekey"];

// 세션에서 DB 이름 가져오기
$DB = $_SESSION['DB'] ?? 'chandj';

if ($method === 'POST') {
    $tablename = $_REQUEST['tablename'] ?? '';
    $item = $_REQUEST['item'] ?? '';    
	
    // 파일 업로드 데이터 검증
    if (!isset($_FILES[$upfilename]) || !isset($_FILES[$upfilename]['name']) || !is_array($_FILES[$upfilename]['name'])) {
        echo json_encode(['error' => '파일 데이터가 유효하지 않습니다.'], JSON_UNESCAPED_UNICODE);
        exit;
    }	

    $countfiles = count($_FILES[$upfilename]['name']);
    $response = [];

    for ($i = 0; $i < $countfiles; $i++) {
        $filename = $_FILES[$upfilename]['name'][$i];
        if ($filename) {
            $tmpNm = explode('.', $filename);
            $ext = strtolower(end($tmpNm));

            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
			$microtime = microtime(true);
			$milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);
			$new_file_name = date("Y_m_d_H_i_s", $microtime) . "_" . $milliseconds . "_" . $i . "." . $ext;

            $filePath = $_FILES[$upfilename]['tmp_name'][$i]; // 원본 파일 경로

            if ($isImage) {
                // 이미지인 경우 압축 처리
                $tempDir = $_SERVER['DOCUMENT_ROOT'] . '/temp/';
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }
                $compressedFilePath = $tempDir . $new_file_name;
                $filePath = compress_image($filePath, $compressedFilePath, 70);				
				
                if (!$filePath) {
                    $response[] = ['file' => $filename, 'status' => 'error', 'message' => '이미지 압축 실패.'];
                    continue;
                }
            }

            try {
                // Google Drive에 파일 업로드
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $new_file_name,
                    'parents' => [$uploadsFolderId]
                ]);
                $content = file_get_contents($filePath);
                $uploadedFile = $service->files->create($fileMetadata, [
                    'data' => $content,
                    'mimeType' => mime_content_type($filePath),
                    'uploadType' => 'multipart',
                    'fields' => 'id, webViewLink'
                ]);

                $fileId = $uploadedFile->id;
                setFilePublic($service, $fileId);

                // 데이터베이스에 저장
                $pdo = db_connect();
                $pdo->beginTransaction();
                $sql = "INSERT INTO {$DB}.{$DBtable} (tablename, item, parentnum, picname, realname) VALUES (?, ?, ?, ?, ?)";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(1, $tablename, PDO::PARAM_STR);
                $stmh->bindValue(2, $item, PDO::PARAM_STR);
                $stmh->bindValue(3, $parentnum, PDO::PARAM_STR);
                $stmh->bindValue(4, $fileId, PDO::PARAM_STR);
                $stmh->bindValue(5, $filename, PDO::PARAM_STR);
                $stmh->execute();
                $pdo->commit();

                $response[] = ['file' => $filename, 'status' => 'success', 'new_name' => $new_file_name, 'fileId' => $fileId , 'realname' => $filename ] ;
            } catch (Exception $e) {
                error_log("Google Drive 업로드 실패: " . $e->getMessage());
                $response[] = ['file' => $filename, 'status' => 'error', 'message' => $e->getMessage()];
            }

            // 임시 파일 삭제 (이미지일 경우만 삭제)
            if ($isImage && isset($compressedFilePath) && file_exists($compressedFilePath)) {
                unlink($compressedFilePath);
            }
        }
    }
	
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} elseif ($method === 'GET') {
    // 파일 조회 처리
    $tablename = $_REQUEST['tablename'] ?? '';
    $item = $_REQUEST['item'] ?? '';    

    $pdo = db_connect();
    $sql = "SELECT * FROM {$DB}.{$DBtable} WHERE tablename = ? AND item = ? AND parentnum = ?";
    $response = [];

    try {
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$tablename, $item, $parentnum]);
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $fileId = $row['picname'];
            $realname = $row['realname'];
            $file = $service->files->get($fileId, ['fields' => 'thumbnailLink, webViewLink']);
            $response[] = [
                'fileId' => $fileId,
                'thumbnail' => $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId",
                'link' => $file->webViewLink,
				'realname' => $realname
            ];
        }
    } catch (Exception $e) {
        error_log("파일 조회 실패: " . $e->getMessage());
        $response = ['error' => $e->getMessage()];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} elseif ($method === 'DELETE') {
    // JSON 데이터 파싱
    $input = json_decode(file_get_contents('php://input'), true);

    $fileId = $input['fileId'] ?? '';
    $tablename = $input['tablename'] ?? '';
    $item = $input['item'] ?? '';
    $folderPath = $input['folderPath'] ?? '';
    $DBtable = $input['DBtable'] ?? 'picuploads';

    $response = [];

    try {
        // Google Drive 파일 삭제
        $service->files->delete($fileId);

        // 데이터베이스에서 파일 정보 삭제
        $pdo = db_connect();
        $sql = "DELETE FROM {$DB}.{$DBtable} WHERE tablename = ? AND item = ? AND picname = ? ";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$tablename, $item, $fileId]);

        $response = ['status' => 'success', 'message' => '파일 삭제 완료'];
    } catch (Exception $e) {
        error_log("파일 삭제 실패: " . $e->getMessage());
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['error' => '지원하지 않는 요청 방식입니다.'], JSON_UNESCAPED_UNICODE);
}
