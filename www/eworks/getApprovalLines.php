<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 결재라인 파일 경로
$approvalLineDir = './approvalLine';
$filePath = $approvalLineDir . '/approvalLine_' . $userid . '.json';

try {
    // 디렉토리 존재 확인
    if (!file_exists($approvalLineDir)) {
        @mkdir($approvalLineDir, 0755, true);
    }
    
    // 파일이 없는 경우 빈 배열 반환
    if (!file_exists($filePath)) {
        echo json_encode(array(), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // JSON 파일 읽기
    $jsonContent = file_get_contents($filePath);
    $data = json_decode($jsonContent, true);
    
    // JSON 디코딩 오류 확인
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array(
            "status" => "error",
            "message" => "JSON 디코딩 오류",
            "error" => json_last_error_msg()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 데이터 검증
    if (!is_array($data)) {
        $data = array();
    }
    
    // 성공 응답
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
    // JSON 인코딩 오류 확인
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON 인코딩 오류: " . json_last_error_msg());
        
        http_response_code(500);
        echo json_encode(array(
            "status" => "error",
            "message" => "JSON 인코딩 오류",
            "error" => json_last_error_msg()
        ), JSON_UNESCAPED_UNICODE);
    }
    
} catch (Exception $ex) {
    error_log("결재라인 목록 조회 오류: " . $ex->getMessage());
    
    http_response_code(500);
    echo json_encode(array(
        "status" => "error",
        "message" => "결재라인 목록 조회 중 오류가 발생했습니다.",
        "error" => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
