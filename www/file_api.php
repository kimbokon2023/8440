<?php
/**
 * Google Drive 파일 관리 API 모듈
 * 
 * 사용법:
 * 1. require_once $_SERVER['DOCUMENT_ROOT'] . '/file_api.php';
 * 2. $fileManager = new GoogleDriveFileManager();
 * 3. $fileManager->uploadFiles($files, $options);
 * 4. $fileManager->getFiles($options);
 * 5. $fileManager->deleteFile($fileId, $options);
 */

// DOCUMENT_ROOT가 설정되지 않은 경우 현재 디렉토리 사용
$docRoot = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__FILE__);
require_once $docRoot . '/session.php';
require_once $docRoot . '/vendor/autoload.php';
require_once $docRoot . '/lib/mydb.php';

class GoogleDriveFileManager {
    public $service;  // 테스트를 위해 public으로 변경
    private $pdo;
    private $DB;
    
    public function __construct() {
        $this->initializeGoogleDrive();
        $this->pdo = db_connect();
        $this->DB = $_SESSION['DB'] ?? 'chandj';
    }
    
    /**
     * Google Drive 서비스 초기화
     */
    private function initializeGoogleDrive() {
        global $docRoot;
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $docRoot . '/tokens/mytoken.json');
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->setScopes([Google_Service_Drive::DRIVE]);
        $this->service = new Google_Service_Drive($client);
    }
    
    /**
     * 폴더 경로로 폴더 ID 가져오기 또는 생성
     */
    public function getOrCreateFolderByPath($path) {
        $pathParts = explode('/', $path);
        $parentId = null;
        
        foreach ($pathParts as $part) {
            if (empty($part)) continue;
            
            $query = "name='$part' and mimeType='application/vnd.google-apps.folder' and trashed=false";
            if ($parentId) {
                $query .= " and '$parentId' in parents";
            }
            
            $response = $this->service->files->listFiles([
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
                $folder = $this->service->files->create($fileMetadata, ['fields' => 'id']);
                $parentId = $folder->id;
            }
        }
        
        return $parentId;
    }
    
    /**
     * 파일 공개 설정
     */
    private function setFilePublic($fileId) {
        $permission = new Google_Service_Drive_Permission();
        $permission->setRole('reader');
        $permission->setType('anyone');
        
        try {
            $this->service->permissions->create($fileId, $permission);
            return true;
        } catch (Exception $e) {
            error_log("권한 설정 실패: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 이미지 압축 함수
     */
    private function compressImage($source, $destination, $quality = 70) {
        $info = getimagesize($source);
        
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        } else {
            return false;
        }
        
        return imagejpeg($image, $destination, $quality);
    }
    
    /**
     * 파일 업로드
     * 
     * @param array $files $_FILES 배열
     * @param array $options 설정 옵션
     * @return array 결과 배열
     */
    public function uploadFiles($files, $options = []) {
        $defaultOptions = [
            'folderPath' => '미래기업/uploads',
            'tablename' => '',
            'item' => 'attached',
            'parentnum' => '',
            'DBtable' => 'picuploads',
            'compress' => true,
            'quality' => 70
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        if (empty($options['parentnum'])) {
            $microtime = microtime(true);
            $milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);
            $options['parentnum'] = date("Y_m_d_H_i_s", $microtime) . '_' . $milliseconds;
        }
        
        $uploadsFolderId = $this->getOrCreateFolderByPath($options['folderPath']);
        $response = [];
        
        $fileCount = is_array($files['name']) ? count($files['name']) : 1;
        
        for ($i = 0; $i < $fileCount; $i++) {
            $filename = is_array($files['name']) ? $files['name'][$i] : $files['name'];
            $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            
            if ($filename) {
                $tmpNm = explode('.', $filename);
                $ext = strtolower(end($tmpNm));
                
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                $microtime = microtime(true);
                $milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);
                $newFileName = date("Y_m_d_H_i_s", $microtime) . "_" . $milliseconds . "_" . $i . "." . $ext;
                
                $filePath = $tmpName;
                
                if ($isImage && $options['compress']) {
                    global $docRoot;
                    $tempDir = $docRoot . '/temp/';
                    if (!file_exists($tempDir)) {
                        mkdir($tempDir, 0755, true);
                    }
                    $compressedFilePath = $tempDir . $newFileName;
                    $filePath = $this->compressImage($filePath, $compressedFilePath, $options['quality']);
                    
                    if (!$filePath) {
                        $response[] = ['file' => $filename, 'status' => 'error', 'message' => '이미지 압축 실패'];
                        continue;
                    }
                    $filePath = $compressedFilePath;
                }
                
                try {
                    $fileMetadata = new Google_Service_Drive_DriveFile([
                        'name' => $newFileName,
                        'parents' => [$uploadsFolderId]
                    ]);
                    
                    $content = file_get_contents($filePath);
                    $uploadedFile = $this->service->files->create($fileMetadata, [
                        'data' => $content,
                        'mimeType' => mime_content_type($filePath),
                        'uploadType' => 'multipart',
                        'fields' => 'id, webViewLink'
                    ]);
                    
                    $fileId = $uploadedFile->id;
                    $this->setFilePublic($fileId);
                    
                    // 데이터베이스에 저장
                    $this->pdo->beginTransaction();
                    $sql = "INSERT INTO {$this->DB}.{$options['DBtable']} (tablename, item, parentnum, picname, realname) VALUES (?, ?, ?, ?, ?)";
                    $stmh = $this->pdo->prepare($sql);
                    $stmh->execute([
                        $options['tablename'],
                        $options['item'],
                        $options['parentnum'],
                        $fileId,
                        $filename
                    ]);
                    $this->pdo->commit();
                    
                    $response[] = [
                        'file' => $filename,
                        'status' => 'success',
                        'new_name' => $newFileName,
                        'fileId' => $fileId,
                        'realname' => $filename
                    ];
                    
                } catch (Exception $e) {
                    error_log("Google Drive 업로드 실패: " . $e->getMessage());
                    $response[] = ['file' => $filename, 'status' => 'error', 'message' => $e->getMessage()];
                }
                
                // 임시 파일 삭제
                if ($isImage && $options['compress'] && isset($compressedFilePath) && file_exists($compressedFilePath)) {
                    unlink($compressedFilePath);
                }
            }
        }
        
        return $response;
    }
    
    /**
     * 파일 목록 조회
     * 
     * @param array $options 설정 옵션
     * @return array 파일 목록
     */
    public function getFiles($options = []) {
        $defaultOptions = [
            'tablename' => '',
            'item' => 'attached',
            'parentnum' => '',
            'DBtable' => 'picuploads'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        $sql = "SELECT * FROM {$this->DB}.{$options['DBtable']} WHERE tablename = ? AND item = ? AND parentnum = ?";
        $response = [];
        
        try {
            $stmh = $this->pdo->prepare($sql);
            $stmh->execute([$options['tablename'], $options['item'], $options['parentnum']]);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $fileId = $row['picname'];
                $realname = $row['realname'];
                
                try {
                    $file = $this->service->files->get($fileId, ['fields' => 'thumbnailLink, webViewLink']);
                    $response[] = [
                        'fileId' => $fileId,
                        'thumbnail' => $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId",
                        'link' => $file->webViewLink,
                        'realname' => $realname
                    ];
                } catch (Exception $e) {
                    error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                    $response[] = [
                        'fileId' => $fileId,
                        'thumbnail' => "https://drive.google.com/uc?id=$fileId",
                        'link' => null,
                        'realname' => $realname
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("파일 조회 실패: " . $e->getMessage());
            $response = ['error' => $e->getMessage()];
        }
        
        return $response;
    }
    
    /**
     * 파일 삭제
     * 
     * @param string $fileId Google Drive 파일 ID
     * @param array $options 설정 옵션
     * @return array 결과
     */
    public function deleteFile($fileId, $options = []) {
        $defaultOptions = [
            'tablename' => '',
            'item' => 'attached',
            'DBtable' => 'picuploads'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        try {
            // Google Drive에서 파일 삭제
            $this->service->files->delete($fileId);
            
            // 데이터베이스에서 파일 정보 삭제
            $sql = "DELETE FROM {$this->DB}.{$options['DBtable']} WHERE tablename = ? AND item = ? AND picname = ?";
            $stmh = $this->pdo->prepare($sql);
            $stmh->execute([$options['tablename'], $options['item'], $fileId]);
            
            return ['status' => 'success', 'message' => '파일 삭제 완료'];
        } catch (Exception $e) {
            error_log("파일 삭제 실패: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * 파일 정보 업데이트 (실제 파일명으로 검색하여 ID 업데이트)
     * 
     * @param array $options 설정 옵션
     * @return array 결과
     */
    public function updateFileIds($options = []) {
        $defaultOptions = [
            'tablename' => '',
            'item' => 'attached',
            'parentnum' => '',
            'DBtable' => 'picuploads'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        $sql = "SELECT * FROM {$this->DB}.{$options['DBtable']} WHERE tablename = ? AND item = ? AND parentnum = ?";
        $updated = 0;
        
        try {
            $stmh = $this->pdo->prepare($sql);
            $stmh->execute([$options['tablename'], $options['item'], $options['parentnum']]);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $picname = $row["picname"];
                
                // 파일 ID가 아닌 경우 (파일명인 경우)
                if (!preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
                    try {
                        $query = sprintf("name='%s' and trashed=false", addslashes($picname));
                        $response = $this->service->files->listFiles([
                            'q' => $query,
                            'fields' => 'files(id)',
                            'pageSize' => 1
                        ]);
                        
                        if (count($response->files) > 0) {
                            $fileId = $response->files[0]->id;
                            
                            // 데이터베이스 업데이트
                            $updateSql = "UPDATE {$this->DB}.{$options['DBtable']} SET picname = ? WHERE item = ? AND parentnum = ? AND picname = ?";
                            $updateStmh = $this->pdo->prepare($updateSql);
                            $updateStmh->execute([$fileId, $options['item'], $options['parentnum'], $picname]);
                            $updated++;
                        }
                    } catch (Exception $e) {
                        error_log("파일 ID 업데이트 실패: " . $e->getMessage());
                    }
                }
            }
            
            return ['status' => 'success', 'updated' => $updated];
        } catch (Exception $e) {
            error_log("파일 ID 업데이트 실패: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}

/**
 * 간편 사용을 위한 헬퍼 함수들
 */

/**
 * 파일 업로드 헬퍼 함수
 */
function uploadFilesToGoogleDrive($files, $options = []) {
    $fileManager = new GoogleDriveFileManager();
    return $fileManager->uploadFiles($files, $options);
}

/**
 * 파일 목록 조회 헬퍼 함수
 */
function getFilesFromGoogleDrive($options = []) {
    $fileManager = new GoogleDriveFileManager();
    return $fileManager->getFiles($options);
}

/**
 * 파일 삭제 헬퍼 함수
 */
function deleteFileFromGoogleDrive($fileId, $options = []) {
    $fileManager = new GoogleDriveFileManager();
    return $fileManager->deleteFile($fileId, $options);
}

/**
 * 파일 ID 업데이트 헬퍼 함수
 */
function updateFileIdsInGoogleDrive($options = []) {
    $fileManager = new GoogleDriveFileManager();
    return $fileManager->updateFileIds($options);
}
?>
