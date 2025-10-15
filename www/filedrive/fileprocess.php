<?php
/**
 * Google Drive 파일 업로드/조회/삭제 API
 * Google Drive를 이용한 파일 관리 기능을 제공합니다.
 */

// 에러 표시 활성화 (디버깅용)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 에러 핸들러 등록
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [{$errno}]: {$errstr} in {$errfile} on line {$errline}");
    
    // JSON 응답으로 오류 전송
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode(array(
            'error' => 'PHP Error',
            'message' => $errstr,
            'file' => basename($errfile),
            'line' => $errline
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
});

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 필수 파일 포함 - 직접 경로 사용
try {
    require_once __DIR__ . '/../session.php';
} catch (Exception $e) {
    error_log("session.php 로드 실패: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(array('error' => 'Session load failed', 'message' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    require_once __DIR__ . '/../php/common.php';
} catch (Exception $e) {
    error_log("common.php 로드 실패: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(array('error' => 'Common functions load failed', 'message' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    require_once __DIR__ . '/../lib/mydb.php';
} catch (Exception $e) {
    error_log("mydb.php 로드 실패: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(array('error' => 'Database library load failed', 'message' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
    exit;
}

// Composer autoload 확인
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    error_log("Composer autoload not found: {$autoloadPath}");
    http_response_code(500);
    echo json_encode(array(
        'error' => 'Google API library not installed',
        'message' => 'Please run: composer install'
    ), JSON_UNESCAPED_UNICODE);
    exit;
}
require_once $autoloadPath;

// JSON 응답 설정
header('Content-Type: application/json; charset=utf-8');

// PHP 업로드 제한 설정 (20MB)
ini_set('upload_max_filesize', '20M');
ini_set('post_max_size', '20M');
ini_set('max_execution_time', 300); // 5분
ini_set('memory_limit', '256M');

// Google Drive 서비스 계정 인증 설정
$tokenPath = __DIR__ . '/../tokens/mytoken.json';
if (!file_exists($tokenPath)) {
    error_log("Google service account token not found: {$tokenPath}");
    http_response_code(500);
    echo json_encode(array(
        'error' => 'Google service account token not found',
        'message' => 'Please configure service account credentials'
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $tokenPath);

$service = null;
$googleDriveEnabled = false;

try {
    // Google API 클래스 존재 확인
    if (!class_exists('Google_Client')) {
        error_log("Google_Client 클래스를 찾을 수 없습니다.");
        throw new Exception("Google API 클라이언트를 찾을 수 없습니다.");
    }
    
    if (!class_exists('Google_Service_Drive')) {
        error_log("Google_Service_Drive 클래스를 찾을 수 없습니다.");
        throw new Exception("Google Drive 서비스 클래스를 찾을 수 없습니다.");
    }
    
    /** @var Google_Client $client */
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->setScopes(array(Google_Service_Drive::DRIVE));
    
    /** @var Google_Service_Drive $service */
    $service = new Google_Service_Drive($client);
    $googleDriveEnabled = true;
    
    error_log("Google Drive 클라이언트 초기화 성공");
} catch (Exception $ex) {
    error_log("Google Client initialization error: " . $ex->getMessage());
    $service = null;
    $googleDriveEnabled = false;
    // Google Drive 실패해도 계속 진행 (로컬 저장으로 대체)
}

/**
 * 특정 경로의 폴더를 확인하거나 생성 (재귀적 처리)
 * @param Google_Service_Drive $service Google Drive 서비스 객체
 * @param string $path 폴더 경로 (예: '미래기업/uploads')
 * @return string|null 폴더 ID
 */
function getOrCreateFolderByPath($service, $path) {
    $pathParts = explode('/', $path);
    $parentId = null; // 최상위 루트

    foreach ($pathParts as $part) {
        if (empty($part)) {
            continue;
        }

        // SQL Injection과 유사한 공격 방지를 위해 이스케이프 처리
        $partEscaped = str_replace("'", "\\'", $part);
        
        $query = "name='{$partEscaped}' and mimeType='application/vnd.google-apps.folder' and trashed=false";
        if ($parentId) {
            $query .= " and '{$parentId}' in parents";
        }

        $response = $service->files->listFiles(array(
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)'
        ));

        if (count($response->files) > 0) {
            $parentId = $response->files[0]->id;
        } else {
            /** @var Google_Service_Drive_DriveFile $fileMetadata */
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => $part,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => $parentId ? array($parentId) : array()
            ));
            $folder = $service->files->create($fileMetadata, array('fields' => 'id'));
            $parentId = $folder->id;
        }
    }

    return $parentId;
}

/**
 * 파일을 공개 설정하는 함수
 * @param Google_Service_Drive $service Google Drive 서비스 객체
 * @param string $fileId 파일 ID
 * @return bool 성공 여부
 */
function setFilePublic($service, $fileId) {
    /** @var Google_Service_Drive_Permission $permission */
    $permission = new Google_Service_Drive_Permission();
    $permission->setRole('reader');
    $permission->setType('anyone');

    try {
        $service->permissions->create($fileId, $permission);
        return true;
    } catch (Exception $ex) {
        error_log("권한 설정 실패: " . $ex->getMessage());
        return false;
    }
}

// HTTP 메서드 확인
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 파라미터 초기화
$folderPath = $_REQUEST['folderPath'] ?? '미래기업/uploads';
$DBtable = $_REQUEST['DBtable'] ?? 'picuploads';
$upfilename = $_REQUEST['upfilename'] ?? 'upfile';
$num = $_REQUEST["num"] ?? '';
$timekey = $_REQUEST["timekey"] ?? '';

// parentnum 설정: num이 있으면 num, 없으면 timekey
$parentnum = !empty($num) ? $num : $timekey;

// 변수 초기화
$uploadsFolderId = null;
$tablename = '';
$item = '';
$response = array();

// Google Drive 폴더 ID 가져오기 (Google Drive가 활성화된 경우만)
$uploadsFolderId = null;
if ($googleDriveEnabled && $service) {
    try {
        $uploadsFolderId = getOrCreateFolderByPath($service, $folderPath);
        error_log("Google Drive 폴더 ID 획득 성공: " . $uploadsFolderId);
    } catch (Exception $ex) {
        error_log("Failed to get or create folder: " . $ex->getMessage());
        $googleDriveEnabled = false; // Google Drive 비활성화하고 계속 진행
    }
} else {
    error_log("Google Drive 비활성화 상태 - 로컬 저장 모드로 진행");
}

if ($method === 'POST') {
    $tablename = $_REQUEST['tablename'] ?? '';
    $item = $_REQUEST['item'] ?? '';
    $isMobile = $_REQUEST['isMobile'] ?? '0';
    
    error_log("업로드 요청 - 모바일: {$isMobile}, 테이블: {$tablename}, 아이템: {$item}");
    
    // 파일 업로드 데이터 검증
    if (!isset($_FILES[$upfilename]) || !isset($_FILES[$upfilename]['name']) || !is_array($_FILES[$upfilename]['name'])) {
        echo json_encode(array('error' => '파일 데이터가 유효하지 않습니다.'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // POST 크기 검증 (20MB 제한)
    $postSize = $_SERVER['CONTENT_LENGTH'] ?? 0;
    $maxPostSize = 20 * 1024 * 1024; // 20MB
    
    if ($postSize > $maxPostSize) {
        echo json_encode(array('error' => '업로드 파일 크기가 너무 큽니다. (최대 20MB)'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $countfiles = count($_FILES[$upfilename]['name']);
    $response = array();
    
    for ($i = 0; $i < $countfiles; $i++) {
        $filename = $_FILES[$upfilename]['name'][$i] ?? '';
        
        // 모바일 카메라 촬영 파일의 경우 파일명이 이상할 수 있으므로 처리
        if ($isMobile === '1' && (empty($filename) || $filename === 'image.jpg' || $filename === 'image.jpeg')) {
            $filename = 'camera_' . date('YmdHis') . '_' . uniqid() . '.jpg';
            error_log("모바일 카메라 파일명 정규화: " . $filename);
        }
        
        if (empty($filename)) {
            continue;
        }
        
        // 파일 업로드 에러 확인
        $fileError = $_FILES[$upfilename]['error'][$i] ?? UPLOAD_ERR_NO_FILE;
        if ($fileError !== UPLOAD_ERR_OK) {
            $errorMessages = array(
                UPLOAD_ERR_INI_SIZE => '파일이 php.ini의 upload_max_filesize 설정을 초과합니다.',
                UPLOAD_ERR_FORM_SIZE => '파일이 HTML 폼의 MAX_FILE_SIZE 설정을 초과합니다.',
                UPLOAD_ERR_PARTIAL => '파일이 부분적으로만 업로드되었습니다.',
                UPLOAD_ERR_NO_FILE => '파일이 업로드되지 않았습니다.',
                UPLOAD_ERR_NO_TMP_DIR => '임시 폴더가 없습니다.',
                UPLOAD_ERR_CANT_WRITE => '파일 쓰기에 실패했습니다.',
                UPLOAD_ERR_EXTENSION => 'PHP 확장에 의해 업로드가 중단되었습니다.'
            );
            
            $errorMessage = $errorMessages[$fileError] ?? '알 수 없는 업로드 오류';
            error_log("File upload error code: {$fileError} for file: {$filename} - {$errorMessage}");
            $response[] = array('file' => $filename, 'status' => 'error', 'message' => $errorMessage);
            continue;
        }
        
        // 모바일 카메라 촬영 파일 특별 처리
        if ($isMobile === '1') {
            error_log("모바일 카메라 촬영 파일 처리: " . $filename);
        }
        
        // 개별 파일 크기 검증 (20MB 제한)
        $fileSize = $_FILES[$upfilename]['size'][$i] ?? 0;
        $maxFileSize = 20 * 1024 * 1024; // 20MB
        
        if ($fileSize > $maxFileSize) {
            error_log("File too large: {$filename} ({$fileSize} bytes)");
            $response[] = array('file' => $filename, 'status' => 'error', 'message' => '파일 크기가 너무 큽니다. (최대 20MB)');
            continue;
        }
        
        $tmpNm = explode('.', $filename);
        $ext = strtolower(end($tmpNm));
        
        // 확장자가 없는 경우 기본값 설정 (모바일 카메라 촬영 시 발생할 수 있음)
        if (empty($ext)) {
            $ext = 'jpg'; // 기본적으로 jpg로 설정
            $filename = $filename . '.' . $ext;
            error_log("확장자 없는 파일명에 jpg 추가: " . $filename);
        }
        
        $isImage = in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'));
        $microtime = microtime(true);
        $milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);
        $new_file_name = date("Y_m_d_H_i_s", $microtime) . "_" . $milliseconds . "_" . $i . "." . $ext;
        
        $filePath = $_FILES[$upfilename]['tmp_name'][$i]; // 원본 파일 경로
        $compressedFilePath = '';
        
        if ($isImage) {
            // 이미지인 경우 압축 처리
            $tempDir = __DIR__ . '/../temp/';
            if (!is_dir($tempDir)) {
                if (!mkdir($tempDir, 0755, true)) {
                    error_log("Failed to create temp directory: {$tempDir}");
                    $response[] = array('file' => $filename, 'status' => 'error', 'message' => '임시 디렉토리 생성 실패');
                    continue;
                }
            }
            
            $compressedFilePath = $tempDir . $new_file_name;
            
            try {
                // 모바일 카메라 촬영 파일 처리 개선
                error_log("이미지 처리 시작: " . $filename . " (크기: " . filesize($filePath) . " bytes)");
                
                // 파일이 실제로 이미지인지 확인
                $imageInfo = getimagesize($filePath);
                if ($imageInfo === false) {
                    error_log("유효하지 않은 이미지 파일: " . $filename);
                    throw new Exception("유효하지 않은 이미지 파일입니다.");
                }
                
                error_log("이미지 정보: " . json_encode($imageInfo));
                
                // compress_image 함수 존재 확인
                if (!function_exists('compress_image')) {
                    error_log("compress_image 함수가 정의되지 않았습니다.");
                    throw new Exception("이미지 압축 함수를 찾을 수 없습니다.");
                }
                
                // 압축 시도
                $originalSize = filesize($filePath);
                $filePath = compress_image($filePath, $compressedFilePath, 70);
                
                if (!$filePath || !file_exists($filePath)) {
                    throw new Exception("이미지 압축 실패 또는 파일 생성 실패");
                }
                
                $compressedSize = filesize($filePath);
                error_log("이미지 압축 성공: " . $filePath . " (원본: {$originalSize} bytes, 압축: {$compressedSize} bytes)");
                
            } catch (Exception $ex) {
                error_log("이미지 압축 오류: " . $ex->getMessage());
                
                // 압축 실패 시 원본 파일 사용
                error_log("압축 실패, 원본 파일 사용: " . $filePath);
                
                // 원본 파일이 유효한지 확인
                if (file_exists($filePath) && filesize($filePath) > 0) {
                    error_log("원본 파일 사용 가능: " . $filePath);
                } else {
                    $response[] = array('file' => $filename, 'status' => 'error', 'message' => '이미지 압축 실패 및 원본 파일 손상: ' . $ex->getMessage());
                    continue;
                }
            }
        }

        try {
            $fileId = null;
            
            if ($googleDriveEnabled && $service && $uploadsFolderId) {
                // Google Drive에 파일 업로드
                error_log("Google Drive 업로드 시작: " . $filename);
                
                /** @var Google_Service_Drive_DriveFile $fileMetadata */
                $fileMetadata = new Google_Service_Drive_DriveFile(array(
                    'name' => $new_file_name,
                    'parents' => array($uploadsFolderId)
                ));
                
                $content = file_get_contents($filePath);
                if ($content === false) {
                    throw new Exception("파일 읽기 실패: {$filename}");
                }
                
                $uploadedFile = $service->files->create($fileMetadata, array(
                    'data' => $content,
                    'mimeType' => mime_content_type($filePath),
                    'uploadType' => 'multipart',
                    'fields' => 'id, webViewLink'
                ));
                
                $fileId = $uploadedFile->id;
                setFilePublic($service, $fileId);
                
                error_log("Google Drive 업로드 성공: " . $fileId);
            } else {
                // 로컬 저장 모드 (Google Drive 비활성화된 경우)
                error_log("로컬 저장 모드: " . $filename);
                
                // 로컬 저장 경로 설정
                $localUploadDir = __DIR__ . '/../uploads/';
                if (!is_dir($localUploadDir)) {
                    if (!mkdir($localUploadDir, 0755, true)) {
                        throw new Exception("로컬 업로드 디렉토리 생성 실패");
                    }
                }
                
                $localFilePath = $localUploadDir . $new_file_name;
                if (!copy($filePath, $localFilePath)) {
                    throw new Exception("로컬 파일 복사 실패");
                }
                
                $fileId = $new_file_name; // 로컬 파일명을 ID로 사용
                error_log("로컬 저장 성공: " . $localFilePath);
            }
            
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
            
            $response[] = array(
                'file' => $filename,
                'status' => 'success',
                'new_name' => $new_file_name,
                'fileId' => $fileId,
                'realname' => $filename,
                'storage_type' => $googleDriveEnabled ? 'google_drive' : 'local'
            );
            
        } catch (Exception $ex) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("파일 업로드 실패: " . $ex->getMessage());
            $response[] = array('file' => $filename, 'status' => 'error', 'message' => $ex->getMessage());
        }
        
        // 임시 파일 삭제 (이미지일 경우)
        if ($isImage && !empty($compressedFilePath) && file_exists($compressedFilePath)) {
            unlink($compressedFilePath);
        }
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} elseif ($method === 'GET') {
    // 파일 조회 처리
    $tablename = $_REQUEST['tablename'] ?? '';
    $item = $_REQUEST['item'] ?? '';
    
    $pdo = db_connect();
    $sql = "SELECT * FROM {$DB}.{$DBtable} WHERE tablename = ? AND item = ? AND parentnum = ?";
    $response = array();
    
    try {
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($tablename, $item, $parentnum));
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $fileId = $row['picname'];
            $realname = $row['realname'] ?? '';
            
            try {
                $file = $service->files->get($fileId, array('fields' => 'thumbnailLink, webViewLink'));
                $response[] = array(
                    'fileId' => $fileId,
                    'thumbnail' => $file->thumbnailLink ?? "https://drive.google.com/uc?id={$fileId}",
                    'link' => $file->webViewLink ?? "https://drive.google.com/file/d/{$fileId}/view",
                    'realname' => $realname
                );
            } catch (Exception $ex) {
                error_log("Failed to get file info for fileId {$fileId}: " . $ex->getMessage());
                $response[] = array(
                    'fileId' => $fileId,
                    'thumbnail' => "https://drive.google.com/uc?id={$fileId}",
                    'link' => "https://drive.google.com/file/d/{$fileId}/view",
                    'realname' => $realname,
                    'error' => 'File info retrieval failed'
                );
            }
        }
    } catch (Exception $ex) {
        error_log("파일 조회 실패: " . $ex->getMessage());
        $response = array('error' => $ex->getMessage());
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} elseif ($method === 'DELETE') {
    // JSON 데이터 파싱
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        http_response_code(400);
        echo json_encode(array('status' => 'error', 'message' => 'Invalid JSON data'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $fileId = $input['fileId'] ?? '';
    $tablename = $input['tablename'] ?? '';
    $item = $input['item'] ?? '';
    $folderPath = $input['folderPath'] ?? '';
    $DBtable = $input['DBtable'] ?? 'picuploads';
    
    $response = array();
    
    // 입력 검증
    if (empty($fileId)) {
        http_response_code(400);
        echo json_encode(array('status' => 'error', 'message' => 'fileId is required'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        // Google Drive 파일 삭제
        $service->files->delete($fileId);
        
        // 데이터베이스에서 파일 정보 삭제
        $pdo = db_connect();
        $pdo->beginTransaction();
        
        $sql = "DELETE FROM {$DB}.{$DBtable} WHERE tablename = ? AND item = ? AND picname = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($tablename, $item, $fileId));
        
        $pdo->commit();
        
        $response = array('status' => 'success', 'message' => '파일 삭제 완료');
    } catch (Exception $ex) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("파일 삭제 실패: " . $ex->getMessage());
        http_response_code(500);
        $response = array('status' => 'error', 'message' => $ex->getMessage());
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} else {
    http_response_code(405);
    echo json_encode(array('error' => '지원하지 않는 요청 방식입니다.'), JSON_UNESCAPED_UNICODE);
}

?>
