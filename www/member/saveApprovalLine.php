<?php
/**
 * 결재라인 저장 처리 (AJAX)
 * 로컬 및 서버 환경 모두 지원
 */

if (!isset($_SESSION)) {
    session_start();
}

header("Content-Type: application/json; charset=utf-8");

// 응답 데이터 초기화
$response = [
    'status' => 'error',
    'message' => ''
];

// JSON 데이터 수신
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// 입력값 검증
if (json_last_error() !== JSON_ERROR_NONE) {
    $response['message'] = 'JSON 데이터 파싱 오류: ' . json_last_error_msg();
    error_log("JSON 파싱 오류: " . json_last_error_msg());
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($data['userId']) || !isset($data['savedName']) || !isset($data['approvalOrder'])) {
    $response['message'] = '필수 데이터가 누락되었습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 변수 초기화
$userId = $data['userId'];
$savedName = $data['savedName'];
$approvalOrder = $data['approvalOrder'];

// 파일 경로 설정
$filePath = './Company_approvalLine_.json';

// 기존 데이터 로드
$existingData = [];

if (file_exists($filePath)) {
    $fileContent = file_get_contents($filePath);
    $existingData = json_decode($fileContent, true);
    
    if (!is_array($existingData)) {
        $existingData = [];
        error_log("기존 JSON 데이터가 배열이 아닙니다. 새 배열로 초기화합니다.");
    }
}

// 새로운 결재라인 정보 추가
$newApprovalLine = [
    'userId' => $userId,
    'savedName' => $savedName,
    'approvalOrder' => $approvalOrder
];

$existingData[] = $newApprovalLine;

// 파일 쓰기 권한 확인
$directory = dirname($filePath);
if (!is_writable($directory)) {
    $response['message'] = '파일 쓰기 권한이 없습니다.';
    error_log("파일 쓰기 권한 오류: {$directory}");
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 파일에 데이터 저장
$jsonData = json_encode($existingData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

if ($jsonData === false) {
    $response['message'] = 'JSON 인코딩 오류: ' . json_last_error_msg();
    error_log("JSON 인코딩 오류: " . json_last_error_msg());
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

if (file_put_contents($filePath, $jsonData) === false) {
    $response['message'] = '파일 저장에 실패했습니다.';
    error_log("파일 저장 실패: {$filePath}");
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 성공 응답
$response['status'] = 'success';
$response['message'] = '결재라인이 성공적으로 저장되었습니다.';
$response['data'] = $newApprovalLine;

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
