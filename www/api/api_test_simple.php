<?php
/**
 * 간단한 Google Drive API 테스트 페이지
 * 서버에서 직접 테스트할 수 있는 페이지
 */

// DOCUMENT_ROOT가 설정되지 않은 경우 현재 디렉토리 사용
$docRoot = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__FILE__);

// file_api.php가 있는지 확인
if (!file_exists(__DIR__ . '/file_api.php')) {
    die('file_api.php 파일을 찾을 수 없습니다. 경로: ' . __DIR__ . '/file_api.php');
}

require_once __DIR__ . '/file_api.php';

header('Content-Type: application/json; charset=utf-8');

// GET 또는 POST 요청 허용
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// 요청 데이터 가져오기
$requestData = $_SERVER['REQUEST_METHOD'] === 'GET' ? $_GET : $_POST;
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
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Google Drive 서비스가 초기화되지 않았습니다.'
            ]);
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
        echo json_encode(['status' => 'success', 'result' => $result]);
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
            'usage' => [
                'GET' => '?action=testConnection',
                'GET' => '?action=getFiles&tablename=my_table&parentnum=123'
            ],
            'server_info' => [
                'document_root' => $docRoot,
                'file_api_exists' => file_exists($docRoot . '/file_api.php'),
                'tokens_exists' => file_exists($docRoot . '/tokens/mytoken.json'),
                'session_exists' => file_exists($docRoot . '/session.php'),
                'vendor_exists' => file_exists($docRoot . '/vendor/autoload.php'),
                'mydb_exists' => file_exists($docRoot . '/lib/mydb.php')
            ]
        ]);
    }
    
    else {
        echo json_encode([
            'status' => 'error',
            'message' => '지원하지 않는 액션입니다: ' . $action,
            'available_actions' => ['testConnection', 'getFiles', 'help']
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
