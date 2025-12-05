<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");


// 요청 파라미터 초기화
$savedName = $_POST['savedName'] ?? '';

// 참조라인 파일 경로
$refLineDir = './RefLine';
$filePath = $refLineDir . '/RefLine_' . $userid . '.json';

try {
    // 디렉토리 존재 확인
    if (!file_exists($refLineDir)) {
        @mkdir($refLineDir, 0755, true);
    }
    
    // 파일 존재 확인
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo json_encode(array(
            "status" => "error",
            "message" => "참조라인 파일을 찾을 수 없습니다."
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
    
    // 해당 참조라인 찾기
    foreach ($data as $refLine) {
        if (isset($refLine['savedName']) && $refLine['savedName'] === $savedName) {
            echo json_encode($refLine, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    // 참조라인을 찾지 못한 경우
    http_response_code(404);
    echo json_encode(array(
        "status" => "error",
        "message" => "해당 참조라인을 찾을 수 없습니다."
    ), JSON_UNESCAPED_UNICODE);
    
} catch (Exception $ex) {
    error_log("참조라인 조회 오류: " . $ex->getMessage());
    
    http_response_code(500);
    echo json_encode(array(
        "status" => "error",
        "message" => "참조라인 조회 중 오류가 발생했습니다.",
        "error" => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
