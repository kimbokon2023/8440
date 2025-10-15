<?php
/**
 * 간단한 Google Drive API 테스트 페이지
 * 서버에서 직접 테스트할 수 있는 페이지
 */

// common/functions.php 로드 (getDocumentRoot 함수 사용을 위해)
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
    $docRoot = getDocumentRoot();
} else {
    // functions.php가 없으면 현재 디렉토리의 상위 디렉토리를 사용
    $docRoot = dirname(__DIR__);
}

// file_api.php가 있는지 확인
if (!file_exists(__DIR__ . '/file_api.php')) {
    die('file_api.php 파일을 찾을 수 없습니다. 경로: ' . __DIR__ . '/file_api.php');
}

require_once __DIR__ . '/file_api.php';

header('Content-Type: application/json; charset=utf-8');

// 요청 메서드 초기화
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// GET 또는 POST 요청 허용
if (!in_array($requestMethod, ['GET', 'POST'])) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// 요청 데이터 가져오기
$requestData = $requestMethod === 'GET' ? $_GET : $_POST;
$action = $requestData['action'] ?? '';

try {
    // Google Drive 연결 테스트
    if ($action === 'testConnection' || $action === '') {
        $fileManager = new GoogleDriveFileManager();

        if ($fileManager->service) {
            // 간단한 API 호출로 연결 테스트
            $response = $fileManager->service->files->listFiles([
                'pageSize' => 1,
                'fields' => 'files(id, name)'
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'Google Drive 연결 성공',
                'fileCount' => count($response->files),
                'serviceInitialized' => true
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Google Drive 서비스가 초기화되지 않았습니다.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    // 파일 목록 조회 테스트
    elseif ($action === 'getFiles') {
        $fileManager = new GoogleDriveFileManager();

        $options = [
            'tablename' => $requestData['tablename'] ?? 'test_table',
            'item' => $requestData['item'] ?? 'attached',
            'parentnum' => $requestData['parentnum'] ?? date('YmdHis'),
            'DBtable' => $requestData['DBtable'] ?? 'picuploads'
        ];

        $result = $fileManager->getFiles($options);
        echo json_encode([
            'status' => 'success',
            'result' => $result
        ], JSON_UNESCAPED_UNICODE);
    }
    // 도움말
    elseif ($action === 'help') {
        echo json_encode([
            'status' => 'info',
            'message' => 'Google Drive 파일 관리 API 테스트',
            'available_actions' => [
                'testConnection' => 'Google Drive 연결 테스트 (기본)',
                'getFiles' => '파일 목록 조회',
                'help' => '도움말 표시'
            ],
            'usage_examples' => [
                'testConnection' => '?action=testConnection',
                'getFiles' => '?action=getFiles&tablename=my_table&parentnum=123'
            ],
            'server_info' => [
                'document_root' => $docRoot,
                'file_api_exists' => file_exists(__DIR__ . '/file_api.php'),
                'tokens_exists' => file_exists($docRoot . '/tokens/mytoken.json'),
                'session_exists' => file_exists($docRoot . '/session.php'),
                'vendor_exists' => file_exists($docRoot . '/vendor/autoload.php'),
                'mydb_exists' => file_exists($docRoot . '/lib/mydb.php')
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '지원하지 않는 액션입니다: ' . $action,
            'available_actions' => ['testConnection', 'getFiles', 'help']
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], JSON_UNESCAPED_UNICODE);
}
?>