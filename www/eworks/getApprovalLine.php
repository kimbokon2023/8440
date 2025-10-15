<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$userid = $_SESSION['userid'] ?? '';

// 요청 파라미터 초기화
$savedName = $_POST['savedName'] ?? '';

// 결재라인 파일 경로
$approvalLineDir = './approvalLine';
$filePath = $approvalLineDir . '/approvalLine_' . $userid . '.json';

try {
    // 디렉토리 존재 확인
    if (!file_exists($approvalLineDir)) {
        @mkdir($approvalLineDir, 0755, true);
    }
    
    // 파일 존재 확인
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo json_encode(array(
            "status" => "error",
            "message" => "결재라인 파일을 찾을 수 없습니다."
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // JSON 파일 읽기
    $jsonContent = file_get_contents($filePath);
    $data = json_decode($jsonContent, true);
    
    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(array(
            "status" => "error",
            "message" => "잘못된 데이터 형식입니다."
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 해당 결재라인 찾기
    foreach ($data as $approvalLine) {
        if (isset($approvalLine['savedName']) && $approvalLine['savedName'] === $savedName) {
            echo json_encode($approvalLine, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    // 결재라인을 찾지 못한 경우
    http_response_code(404);
    echo json_encode(array(
        "status" => "error",
        "message" => "해당 결재라인을 찾을 수 없습니다."
    ), JSON_UNESCAPED_UNICODE);
    
} catch (Exception $ex) {
    error_log("결재라인 조회 오류: " . $ex->getMessage());
    
    http_response_code(500);
    echo json_encode(array(
        "status" => "error",
        "message" => "결재라인 조회 중 오류가 발생했습니다.",
        "error" => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
