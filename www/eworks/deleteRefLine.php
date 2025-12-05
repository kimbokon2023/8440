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
    
    // 참조라인 제거
    $found = false;
    foreach ($data as $key => $value) {
        if (isset($value['savedName']) && $value['savedName'] === $savedName) {
            unset($data[$key]);
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        http_response_code(404);
        echo json_encode(array(
            "status" => "error",
            "message" => "삭제할 참조라인을 찾을 수 없습니다."
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 파일 업데이트
    $result = file_put_contents($filePath, json_encode(array_values($data), JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        throw new Exception("파일 저장 실패");
    }
    
    // 성공 응답
    echo json_encode(array(
        "status" => "success",
        "message" => "참조라인이 삭제되었습니다.",
        "savedName" => $savedName
    ), JSON_UNESCAPED_UNICODE);
    
} catch (Exception $ex) {
    error_log("참조라인 삭제 오류: " . $ex->getMessage());
    
    http_response_code(500);
    echo json_encode(array(
        "status" => "error",
        "message" => "참조라인 삭제 중 오류가 발생했습니다.",
        "error" => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
