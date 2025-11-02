<?php
/**
 * Google Drive 파일 목록 조회 API
 * DB에 저장된 파일 ID로 Google Drive 파일 정보를 조회합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 필수 파일 포함
require_once getDocumentRoot() . '/session.php';
require_once getDocumentRoot() . '/lib/mydb.php';

// Composer autoload 확인
$autoloadPath = getDocumentRoot() . '/vendor/autoload.php';
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

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 파라미터 초기화
$tablename = $_GET["tablename"] ?? '';
$num = $_GET["num"] ?? '';
$item = $_GET["item"] ?? 'attached'; // 기본은 파일, 필요 시 image 처리 가능

// 변수 초기화
$savefilename_arr = array();
$SearchKey = '';

// SearchKey 설정: num이 있으면 num, 없으면 임시 키 생성
if (!empty($num)) {
    $SearchKey = $num;
} else {
    $SearchKey = date("Y_m_d_H_i_s") . '_' . rand(100, 999);
}

// Google Drive 서비스 계정 인증 설정
$tokenPath = getDocumentRoot() . '/tokens/mytoken.json';
if (!file_exists($tokenPath)) {
    error_log("Google service account token not found: {$tokenPath}");
    http_response_code(500);
    echo json_encode(array(
        'error' => 'Google service account token not found',
        'message' => 'Please configure service account credentials'
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    /** @var Google_Client $client */
    $client = new Google_Client();
    $client->setAuthConfig($tokenPath);
    $client->addScope(Google_Service_Drive::DRIVE);
    
    /** @var Google_Service_Drive $service */
    $service = new Google_Service_Drive($client);
} catch (Exception $ex) {
    error_log("Google Client initialization error: " . $ex->getMessage());
    http_response_code(500);
    echo json_encode(array(
        'error' => 'Failed to initialize Google Drive client',
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
$pdo = db_connect();

$sql = "SELECT * FROM {$DB}.picuploads WHERE tablename = ? AND item = ? AND parentnum = ?";

try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute(array($tablename, $item, $SearchKey));
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"] ?? '';
        $realname = $row["realname"] ?? '';
        
        // Google Drive 파일 ID 형식 검증 (25자 이상의 영숫자, 하이픈, 언더스코어)
        if (preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            $fileId = $picname;
            
            try {
                $file = $service->files->get($fileId, array('fields' => 'webViewLink, webContentLink, thumbnailLink'));
                $savefilename_arr[] = array(
                    'thumbnail' => $file->thumbnailLink ?? null,
                    'link' => $file->webViewLink ?? "https://drive.google.com/file/d/{$fileId}/view",
                    'downloadLink' => $file->webContentLink ?? "https://drive.google.com/uc?export=download&id={$fileId}",
                    'fileId' => $fileId,
                    'realname' => $realname
                );
            } catch (Exception $ex) {
                error_log("Failed to get file info for fileId {$fileId}: " . $ex->getMessage());
                $savefilename_arr[] = array(
                    'thumbnail' => null,
                    'link' => "https://drive.google.com/file/d/{$fileId}/view",
                    'downloadLink' => "https://drive.google.com/uc?export=download&id={$fileId}",
                    'fileId' => $fileId,
                    'realname' => $realname,
                    'error' => 'File info retrieval failed'
                );
            }
        } else {
            error_log("Invalid Google Drive file ID format: {$picname}");
        }
    }
    
} catch (PDOException $ex) {
    error_log("DB query error in load_filelist.php: " . $ex->getMessage());
    http_response_code(500);
    echo json_encode(array('error' => 'Database query failed', 'message' => $ex->getMessage()), JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode($savefilename_arr, JSON_UNESCAPED_UNICODE);

?>
