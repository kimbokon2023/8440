<?php
/**
 * Google Drive 파일 관리 API 테스트 파일
 * 
 * 이 파일은 file_api.php의 기능을 테스트하기 위한 엔드포인트입니다.
 */

require_once getDocumentRoot() . '/file_api.php';

header('Content-Type: application/json; charset=utf-8');

// GET 또는 POST 요청 허용
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// 요청 데이터 가져오기 (GET 또는 POST)
$requestData = $_SERVER['REQUEST_METHOD'] === 'GET' ? $_GET : $_POST;
$action = $requestData['action'] ?? '';

try {
    $fileManager = new GoogleDriveFileManager();
    
    switch ($action) {
        case 'upload':
            // 파일 업로드 테스트
            if (!isset($_FILES['files'])) {
                throw new Exception('파일이 업로드되지 않았습니다.');
            }
            
            $options = [
                'tablename' => $requestData['tablename'] ?? 'test_table',
                'item' => $requestData['item'] ?? 'attached',
                'parentnum' => $requestData['parentnum'] ?? date('YmdHis'),
                'folderPath' => $requestData['folderPath'] ?? '미래기업/test',
                'compress' => isset($requestData['compress']) ? (bool)$requestData['compress'] : true,
                'quality' => (int)($requestData['quality'] ?? 70)
            ];
            
            $result = $fileManager->uploadFiles($_FILES['files'], $options);
            echo json_encode(['status' => 'success', 'result' => $result]);
            break;
            
        case 'getFiles':
            // 파일 목록 조회 테스트
            $options = [
                'tablename' => $requestData['tablename'] ?? 'test_table',
                'item' => $requestData['item'] ?? 'attached',
                'parentnum' => $requestData['parentnum'] ?? date('YmdHis'),
                'DBtable' => $requestData['DBtable'] ?? 'picuploads'
            ];
            
            $result = $fileManager->getFiles($options);
            echo json_encode(['status' => 'success', 'result' => $result]);
            break;
            
        case 'deleteFile':
            // 파일 삭제 테스트
            $fileId = $requestData['fileId'] ?? '';
            if (empty($fileId)) {
                throw new Exception('파일 ID가 필요합니다.');
            }
            
            $options = [
                'tablename' => $requestData['tablename'] ?? 'test_table',
                'item' => $requestData['item'] ?? 'attached',
                'DBtable' => $requestData['DBtable'] ?? 'picuploads'
            ];
            
            $result = $fileManager->deleteFile($fileId, $options);
            echo json_encode(['status' => 'success', 'result' => $result]);
            break;
            
        case 'updateIds':
            // 파일 ID 업데이트 테스트
            $options = [
                'tablename' => $requestData['tablename'] ?? 'test_table',
                'item' => $requestData['item'] ?? 'attached',
                'parentnum' => $requestData['parentnum'] ?? date('YmdHis'),
                'DBtable' => $requestData['DBtable'] ?? 'picuploads'
            ];
            
            $result = $fileManager->updateFileIds($options);
            echo json_encode(['status' => 'success', 'result' => $result]);
            break;
            
        case 'testConnection':
            // Google Drive 연결 테스트
            try {
                $service = $fileManager->service ?? null;
                if ($service) {
                    // 간단한 API 호출로 연결 테스트
                    $response = $service->files->listFiles([
                        'pageSize' => 1,
                        'fields' => 'files(id, name)'
                    ]);
                    echo json_encode([
                        'status' => 'success', 
                        'message' => 'Google Drive 연결 성공',
                        'fileCount' => count($response->files)
                    ]);
                } else {
                    throw new Exception('Google Drive 서비스가 초기화되지 않았습니다.');
                }
            } catch (Exception $e) {
                throw new Exception('Google Drive 연결 실패: ' . $e->getMessage());
            }
            break;
            
        case '':
        case 'help':
            // 도움말 표시
            echo json_encode([
                'status' => 'info',
                'message' => 'Google Drive 파일 관리 API 테스트',
                'available_actions' => [
                    'testConnection' => 'Google Drive 연결 테스트',
                    'getFiles' => '파일 목록 조회',
                    'updateIds' => '파일 ID 업데이트',
                    'upload' => '파일 업로드 (POST만)',
                    'deleteFile' => '파일 삭제 (POST만)'
                ],
                'usage' => [
                    'GET' => '?action=testConnection&tablename=my_table&parentnum=123',
                    'POST' => 'action=upload&files=...&tablename=my_table'
                ]
            ]);
            break;
            
        default:
            throw new Exception('지원하지 않는 액션입니다: ' . $action);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
