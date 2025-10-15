<?php
/**
 * 결재라인 저장 처리 파일
 * 사용자의 결재라인 정보를 JSON 파일로 저장합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// JSON 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 변수 초기화
$data = null;
$existingData = array();
$userId = '';
$savedName = '';
$approvalOrder = '';
$filePath = '';
$dirPath = __DIR__ . '/approvalLine';

try {
    // JSON 데이터 수신 및 검증
    $rawData = file_get_contents('php://input');
    if ($rawData === false || empty($rawData)) {
        throw new Exception('No data received');
    }
    
    $data = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }
    
    // 필수 필드 검증
    if (!isset($data['userId']) || empty($data['userId'])) {
        throw new Exception('userId is required');
    }
    if (!isset($data['savedName']) || empty($data['savedName'])) {
        throw new Exception('savedName is required');
    }
    if (!isset($data['approvalOrder'])) {
        throw new Exception('approvalOrder is required');
    }
    
    // 변수 할당
    $userId = $data['userId'];
    $savedName = $data['savedName'];
    $approvalOrder = $data['approvalOrder'];
    
    // 디렉토리 존재 확인 및 생성
    if (!is_dir($dirPath)) {
        if (!mkdir($dirPath, 0755, true)) {
            throw new Exception('Failed to create directory: ' . $dirPath);
        }
    }
    
    // 파일명 보안 처리 (파일명에 사용할 수 없는 문자 제거)
    $safeUserId = preg_replace('/[^a-zA-Z0-9_-]/', '_', $userId);
    $filePath = $dirPath . '/approvalLine_' . $safeUserId . '.json';
    
    // 파일이 이미 존재하면 기존 데이터를 로드하고, 존재하지 않으면 새 배열을 생성
    if (file_exists($filePath)) {
        $fileContent = file_get_contents($filePath);
        if ($fileContent !== false) {
            $existingData = json_decode($fileContent, true);
            if (!is_array($existingData)) {
                // 기존 데이터가 배열이 아니면 새 배열 생성
                $existingData = array();
                error_log("Warning: Existing data in {$filePath} is not an array. Resetting to empty array.");
            }
        } else {
            throw new Exception('Failed to read existing file: ' . $filePath);
        }
    } else {
        $existingData = array();
    }
    
    // 새로운 결재라인 정보를 기존 데이터에 추가
    $existingData[] = array(
        'userId' => $userId,
        'savedName' => $savedName,
        'approvalOrder' => $approvalOrder
    );
    
    // 파일에 수정된 데이터 저장
    $jsonData = json_encode($existingData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($jsonData === false) {
        throw new Exception('Failed to encode data to JSON: ' . json_last_error_msg());
    }
    
    if (file_put_contents($filePath, $jsonData) === false) {
        throw new Exception('Failed to write file: ' . $filePath);
    }
    
    // 성공 응답
    echo json_encode(array(
        'status' => 'success', 
        'message' => 'Approval line saved successfully.',
        'savedName' => $savedName,
        'count' => count($existingData)
    ), JSON_UNESCAPED_UNICODE);
    
} catch (Exception $ex) {
    // 오류 로깅 및 응답
    error_log("Error in saveApprovalLine.php: " . $ex->getMessage());
    http_response_code(400);
    echo json_encode(array(
        'status' => 'error', 
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
