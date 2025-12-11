<?php
/**
 * Google Drive 파일 관리 API 모듈
 * 
 * 사용법:
 * 1. require_once getDocumentRoot() . '/api/file_api.php';
 * 2. $fileManager = new GoogleDriveFileManager();
 * 3. $fileManager->uploadFiles($files, $options);
 * 4. $fileManager->getFiles($options);
 * 5. $fileManager->deleteFile($fileId, $options);
 */

// common/functions.php 로드
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
    $docRoot = getDocumentRoot();
} else {
    // functions.php가 없으면 현재 디렉토리의 상위 디렉토리를 사용
    $docRoot = dirname(__DIR__);
}

require_once $docRoot . '/session.php';
require_once $docRoot . '/vendor/autoload.php';
require_once $docRoot . '/lib/mydb.php';

class GoogleDriveFileManager {
    public $service;  // 테스트를 위해 public으로 변경
    private $pdo;
    private $DB;
    private $docRoot;
    
    public function __construct() {
        global $docRoot;
        $this->docRoot = $docRoot;
        $this->initializeGoogleDrive();
        $this->pdo = db_connect();
        $this->DB = $_SESSION['DB'] ?? 'chandj';
    }
    
    /**
     * Google Drive 서비스 초기화
     */
    private function initializeGoogleDrive() {
        global $docRoot;
        $tokenPath = $docRoot . '/tokens/mytoken.json';
        
        if (!file_exists($tokenPath)) {
            error_log("Google Drive 토큰 파일을 찾을 수 없습니다: " . $tokenPath);
            throw new Exception("Google Drive 토큰 파일을 찾을 수 없습니다.");
        }
        
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $tokenPath);
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->setScopes([Google_Service_Drive::DRIVE]);
        $this->service = new Google_Service_Drive($client);
    }
    
    /**
     * 폴더 경로로 폴더 ID 가져오기 또는 생성
     * 
     * @param string $path 폴더 경로 (예: "미래기업/uploads")
     * @return string|null 폴더 ID
     */
    public function getOrCreateFolderByPath($path) {
        $pathParts = explode('/', $path);
        $parentId = null;
        
        foreach ($pathParts as $part) {
            if (empty($part)) continue;
            
            // SQL 인젝션 방지를 위한 이스케이프 처리
            $escapedPart = addslashes($part);
            $query = "name='$escapedPart' and mimeType='application/vnd.google-apps.folder' and trashed=false";
            
            if ($parentId) {
                $query .= " and '$parentId' in parents";
            }
            
            try {
                $response = $this->service->files->listFiles([
                    'q' => $query,
                    'spaces' => 'drive',
                    'fields' => 'files(id, name)'
                ]);
                
                if (count($response->files) > 0) {
                    $parentId = $response->files[0]->id;
                } else {
                    // 폴더 생성
                    $fileMetadata = new Google_Service_Drive_DriveFile([
                        'name' => $part,
                        'mimeType' => 'application/vnd.google-apps.folder',
                        'parents' => $parentId ? [$parentId] : []
                    ]);
                    $folder = $this->service->files->create($fileMetadata, ['fields' => 'id']);
                    $parentId = $folder->id;
                }
            } catch (Exception $e) {
                error_log("폴더 생성/조회 오류 ($part): " . $e->getMessage());
                throw $e;
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
     * 
     * @param string $source 원본 파일 경로
     * @param string $destination 대상 파일 경로
     * @param int $quality 압축 품질 (1-100)
     * @return bool 성공 여부
     */
    private function compressImage($source, $destination, $quality = 70) {
        if (!file_exists($source)) {
            error_log("이미지 압축 실패: 원본 파일이 존재하지 않습니다 - " . $source);
            return false;
        }
        
        $info = getimagesize($source);
        
        if ($info === false) {
            error_log("이미지 압축 실패: 이미지 정보를 가져올 수 없습니다 - " . $source);
            return false;
        }
        
        $image = false;
        
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        }
        
        if ($image === false) {
            error_log("이미지 압축 실패: 지원하지 않는 이미지 형식 - " . $info['mime']);
            return false;
        }
        
        $result = imagejpeg($image, $destination, $quality);
        imagedestroy($image);
        
        return $result;
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
        
        // parentnum이 비어있으면 타임스탬프로 자동 생성
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
            
            // 파일이 없거나 빈 파일인 경우 건너뛰기
            if (empty($filename) || empty($tmpName)) {
                continue;
            }
            
            $tmpNm = explode('.', $filename);
            $ext = strtolower(end($tmpNm));
            
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
            $microtime = microtime(true);
            $milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);
            $newFileName = date("Y_m_d_H_i_s", $microtime) . "_" . $milliseconds . "_" . $i . "." . $ext;
            
            $filePath = $tmpName;
            $compressedFilePath = null;
            
            // 이미지 압축 처리
            if ($isImage && $options['compress']) {
                $tempDir = $this->docRoot . '/temp/';
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }
                $compressedFilePath = $tempDir . $newFileName;
                $compressResult = $this->compressImage($filePath, $compressedFilePath, $options['quality']);
                
                if (!$compressResult) {
                    $response[] = [
                        'file' => $filename,
                        'status' => 'error',
                        'message' => '이미지 압축 실패'
                    ];
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
                $mimeType = mime_content_type($filePath);
                
                $uploadedFile = $this->service->files->create($fileMetadata, [
                    'data' => $content,
                    'mimeType' => $mimeType,
                    'uploadType' => 'multipart',
                    'fields' => 'id, webViewLink'
                ]);
                
                $fileId = $uploadedFile->id;
                $this->setFilePublic($fileId);
                
                // 데이터베이스에 저장
                $this->pdo->beginTransaction();
                try {
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
                } catch (Exception $dbEx) {
                    $this->pdo->rollBack();
                    throw $dbEx;
                }
                
                $response[] = [
                    'file' => $filename,
                    'status' => 'success',
                    'new_name' => $newFileName,
                    'fileId' => $fileId,
                    'realname' => $filename
                ];
                
            } catch (Exception $e) {
                error_log("Google Drive 업로드 실패 (" . $filename . "): " . $e->getMessage());
                $response[] = [
                    'file' => $filename,
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
            
            // 임시 파일 삭제
            if ($compressedFilePath && file_exists($compressedFilePath)) {
                unlink($compressedFilePath);
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
        $response = [];
        
        try {
            $sql = "SELECT * FROM {$this->DB}.{$options['DBtable']} WHERE tablename = ? AND item = ? AND parentnum = ?";
            $stmh = $this->pdo->prepare($sql);
            $stmh->execute([$options['tablename'], $options['item'], $options['parentnum']]);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $fileId = $row['picname'] ?? '';
                $realname = $row['realname'] ?? '';
                
                if (empty($fileId)) {
                    continue;
                }
                
                try {
                    $file = $this->service->files->get($fileId, ['fields' => 'thumbnailLink, webViewLink']);
                    $response[] = [
                        'fileId' => $fileId,
                        'thumbnail' => $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId",
                        'link' => $file->webViewLink ?? null,
                        'realname' => $realname
                    ];
                } catch (Exception $e) {
                    error_log("Google Drive 파일 정보 가져오기 실패 (ID: $fileId): " . $e->getMessage());
                    $response[] = [
                        'fileId' => $fileId,
                        'thumbnail' => "https://drive.google.com/uc?id=$fileId",
                        'link' => null,
                        'realname' => $realname,
                        'error' => '파일 정보를 가져올 수 없습니다.'
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("파일 목록 조회 실패: " . $e->getMessage());
            return ['error' => $e->getMessage()];
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
        
        if (empty($fileId)) {
            return ['status' => 'error', 'message' => '파일 ID가 필요합니다.'];
        }
        
        try {
            // Google Drive에서 파일 삭제
            $this->service->files->delete($fileId);
            
            // 데이터베이스에서 파일 정보 삭제
            $sql = "DELETE FROM {$this->DB}.{$options['DBtable']} WHERE tablename = ? AND item = ? AND picname = ?";
            $stmh = $this->pdo->prepare($sql);
            $stmh->execute([$options['tablename'], $options['item'], $fileId]);
            
            return [
                'status' => 'success',
                'message' => '파일 삭제 완료',
                'deleted_rows' => $stmh->rowCount()
            ];
        } catch (Exception $e) {
            error_log("파일 삭제 실패 (ID: $fileId): " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
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
        $updated = 0;
        
        try {
            $sql = "SELECT * FROM {$this->DB}.{$options['DBtable']} WHERE tablename = ? AND item = ? AND parentnum = ?";
            $stmh = $this->pdo->prepare($sql);
            $stmh->execute([$options['tablename'], $options['item'], $options['parentnum']]);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $picname = $row["picname"] ?? '';
                
                if (empty($picname)) {
                    continue;
                }
                
                // 파일 ID가 아닌 경우 (파일명인 경우만 처리)
                if (!preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
                    try {
                        $escapedName = addslashes($picname);
                        $query = "name='$escapedName' and trashed=false";
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
                        error_log("파일 ID 업데이트 실패 ($picname): " . $e->getMessage());
                    }
                }
            }
            
            return [
                'status' => 'success',
                'updated' => $updated,
                'message' => "$updated 개의 파일 ID가 업데이트되었습니다."
            ];
        } catch (Exception $e) {
            error_log("파일 ID 업데이트 실패: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}

/**
 * 간편 사용을 위한 헬퍼 함수들
 */

/**
 * 파일 업로드 헬퍼 함수
 * 
 * @param array $files $_FILES 배열
 * @param array $options 설정 옵션
 * @return array 결과 배열
 */
function uploadFilesToGoogleDrive($files, $options = []) {
    try {
        $fileManager = new GoogleDriveFileManager();
        return $fileManager->uploadFiles($files, $options);
    } catch (Exception $e) {
        error_log("uploadFilesToGoogleDrive 오류: " . $e->getMessage());
        return [['status' => 'error', 'message' => $e->getMessage()]];
    }
}

/**
 * 파일 목록 조회 헬퍼 함수
 * 
 * @param array $options 설정 옵션
 * @return array 파일 목록
 */
function getFilesFromGoogleDrive($options = []) {
    try {
        $fileManager = new GoogleDriveFileManager();
        return $fileManager->getFiles($options);
    } catch (Exception $e) {
        error_log("getFilesFromGoogleDrive 오류: " . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

/**
 * 파일 삭제 헬퍼 함수
 * 
 * @param string $fileId Google Drive 파일 ID
 * @param array $options 설정 옵션
 * @return array 결과
 */
function deleteFileFromGoogleDrive($fileId, $options = []) {
    try {
        $fileManager = new GoogleDriveFileManager();
        return $fileManager->deleteFile($fileId, $options);
    } catch (Exception $e) {
        error_log("deleteFileFromGoogleDrive 오류: " . $e->getMessage());
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

/**
 * parentnum 업데이트 (임시번호 → 실제 ID)
 * 
 * @param array $options 설정 옵션
 * @return array 결과
 */
function updateParentNumInDB($options = []) {
    $defaultOptions = [
        'tablename' => '',
        'item' => 'attached',
        'old_parentnum' => '',
        'new_parentnum' => '',
        'DBtable' => 'picuploads'
    ];
    
    $options = array_merge($defaultOptions, $options);
    
    try {
        $pdo = db_connect();
        $DB = $_SESSION['DB'] ?? 'mirae8440';
        
        // parentnum 업데이트
        $sql = "UPDATE {$DB}.{$options['DBtable']} 
                SET parentnum = ? 
                WHERE tablename = ? 
                AND item = ? 
                AND parentnum = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $options['new_parentnum'],
            $options['tablename'],
            $options['item'],
            $options['old_parentnum']
        ]);
        
        $affected = $stmt->rowCount();
        
        error_log("parentnum 업데이트 완료: {$options['old_parentnum']} → {$options['new_parentnum']}, 영향받은 행: {$affected}");
        
        return [
            'status' => 'success',
            'message' => "{$affected}개 파일의 parentnum이 업데이트되었습니다.",
            'updated_count' => $affected
        ];
    } catch (Exception $e) {
        error_log("updateParentNumInDB 오류: " . $e->getMessage());
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

/**
 * 파일 ID 업데이트 헬퍼 함수
 * 
 * @param array $options 설정 옵션
 * @return array 결과
 */
function updateFileIdsInGoogleDrive($options = []) {
    // old_parentnum이 있으면 parentnum 업데이트
    if (isset($options['old_parentnum']) && isset($options['new_parentnum'])) {
        return updateParentNumInDB($options);
    }
    
    // 그 외에는 기존 로직 (파일명 → 파일ID 업데이트)
    try {
        $fileManager = new GoogleDriveFileManager();
        return $fileManager->updateFileIds($options);
    } catch (Exception $e) {
        error_log("updateFileIdsInGoogleDrive 오류: " . $e->getMessage());
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}
?>