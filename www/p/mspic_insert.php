<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
include "../php/common.php";

// Google Drive 서비스 계정 인증 설정
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json');
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes([Google_Service_Drive::DRIVE]);

header("Content-Type: application/json"); // JSON 응답 설정

$service = new Google_Service_Drive($client);

// 로그 기록을 위한 함수
function logMessage($message) {
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/logs/upload_debug.log';
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// 특정 폴더 확인 또는 생성
function getOrCreateFolder($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    if (count($response->files) > 0) {
        $folderId = $response->files[0]->id;
        logMessage("Folder exists: $folderName (ID: $folderId)");
        return $folderId;
    }

    // 폴더가 없으면 생성
    $fileMetadata = new Google_Service_Drive_DriveFile([
        'name' => $folderName,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => $parentFolderId ? [$parentFolderId] : []
    ]);

    $folder = $service->files->create($fileMetadata, [
        'fields' => 'id'
    ]);

    $folderId = $folder->id;
    logMessage("Folder created: $folderName (ID: $folderId)");
    return $folderId;
}

// 파일에 공개 읽기 권한 추가
function setFilePublic($service, $fileId) {
    $permission = new Google_Service_Drive_Permission();
    $permission->setType('anyone');
    $permission->setRole('reader');

    try {
        $service->permissions->create($fileId, $permission, [
            'fields' => 'id'
        ]);
        logMessage("File made public: $fileId");
    } catch (Exception $e) {
        logMessage("Failed to set public permission for file $fileId: " . $e->getMessage());
    }
}

// '미래기업/uploads' 폴더의 ID 가져오기
$miraeFolderId = getOrCreateFolder($service, '미래기업');
$uploadsFolderId = getOrCreateFolder($service, 'uploads', $miraeFolderId);

$num = $_REQUEST["num"] ?? "";
$check = $_REQUEST["check"] ?? $_POST["check"];
$workplacename = $_REQUEST["workplacename"] ?? "";
$tablename = $_REQUEST["tablename"] ?? "";
$item = $_REQUEST["item"] ?? "";

$filechoice = 'upfile';
$countfiles = count($_FILES[$filechoice]['name']);
$response = []; // JSON 응답 데이터 초기화

// /temp/ 디렉토리 생성 확인 및 설정
$tempDir = $_SERVER['DOCUMENT_ROOT'] . '/temp/';
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0755, true); // 디렉토리 생성
}

for ($i = 0; $i < $countfiles; $i++) {
    $filename = $_FILES[$filechoice]['name'][$i];

    if ($filename != '') {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $tmpNm = explode('.', $filename);
        $ext = strtolower(end($tmpNm));

        if (!in_array($ext, $allowed_ext)) {
            logMessage("Invalid file extension: $filename");
            $response[] = ['file' => $filename, 'status' => 'error', 'message' => '허용되지 않는 확장자입니다.'];
            continue;
        }

        // 이미지 압축 및 저장
        $new_file_name = date("Y_m_d_H_i_s") . "_" . $i . "." . $ext;
        $compressedFilePath = $tempDir . $new_file_name;
        $result = compress_image($_FILES[$filechoice]["tmp_name"][$i], $compressedFilePath, 70);

        if (!$result || !file_exists($compressedFilePath) || filesize($compressedFilePath) == 0) {
            logMessage("Image compression failed for: $filename");
            $response[] = ['file' => $filename, 'status' => 'error', 'message' => '이미지 압축 실패 또는 파일 크기가 0입니다.'];
            continue;
        }

        // Google Drive에 파일 업로드
        try {
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $new_file_name,
                'parents' => [$uploadsFolderId]
            ]);

            $content = file_get_contents($compressedFilePath);
            $uploadedFile = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => mime_content_type($compressedFilePath),
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink'
            ]);

            $fileId = $uploadedFile->id;
            $webViewLink = $uploadedFile->webViewLink;

            logMessage("File uploaded successfully: $filename (ID: $fileId, Link: $webViewLink)");

            // 파일에 공개 권한 설정
            setFilePublic($service, $fileId);

            // 데이터베이스에 저장
            require_once("../lib/mydb.php");
            $pdo = db_connect();

            $pdo->beginTransaction();
            $sql = "INSERT INTO mirae8440.picuploads (tablename, item, parentnum, picname) VALUES (?, ?, ?, ?)";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $tablename, PDO::PARAM_STR);
            $stmh->bindValue(2, $item, PDO::PARAM_STR);
            $stmh->bindValue(3, $num, PDO::PARAM_STR);
            $stmh->bindValue(4, $fileId, PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();

            $response[] = ['file' => $filename, 'status' => 'success', 'new_name' => $new_file_name, 'fileId' => $fileId, 'webViewLink' => $webViewLink];
        } catch (Exception $e) {
            logMessage("Google Drive upload error for file $filename: " . $e->getMessage());
            $response[] = ['file' => $filename, 'status' => 'error', 'message' => 'Google Drive 업로드 중 오류 발생: ' . $e->getMessage()];
        }

        // 임시 파일 삭제
        unlink($compressedFilePath);
    }
}

// 로그 기록 남기기
$data = date("Y-m-d H:i:s") . " - " . $_SESSION["userid"] . " - " . $_SESSION["name"] . " - " . $workplacename . " - 실측서 사진기록";
$pdo = db_connect();
$pdo->beginTransaction();
$sql = "INSERT INTO mirae8440.logdata(data) VALUES(?)";
$stmh = $pdo->prepare($sql);
$stmh->bindValue(1, $data, PDO::PARAM_STR);
$stmh->execute();
$pdo->commit();

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
