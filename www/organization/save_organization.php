<?php
/**
 * 조직도 데이터 저장 API
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';

// CORS 헤더 (개발 중에만 사용, 배포 시 특정 도메인 지정)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// JSON 파일 경로
$filename = __DIR__ . "/organization.json";

try {
    // 사용자 권한 확인 (레벨 1만 저장 가능)
    if (!isset($_SESSION["level"]) || $level != 1) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "message" => "저장 권한이 없습니다. (레벨 1 필요)"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // POST 메소드 확인
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            "status" => "error",
            "message" => "POST 요청만 허용됩니다."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 입력 데이터 받기
    $data = file_get_contents("php://input");
    
    if ($data === false || empty($data)) {
        throw new Exception("입력 데이터가 없습니다.");
    }
    
    // JSON 유효성 검사
    $jsonData = json_decode($data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON 형식이 올바르지 않습니다: " . json_last_error_msg());
    }
    
    // 파일 저장
    $result = file_put_contents($filename, $data);
    
    if ($result === false) {
        throw new Exception("파일 저장에 실패했습니다.");
    }
    
    // 성공 로그
    error_log("Organization data saved by {$user_name} (level {$level})");
    
    echo json_encode([
        "status" => "success",
        "message" => "조직도 데이터가 성공적으로 저장되었습니다.",
        "saved_by" => $user_name,
        "saved_at" => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("Organization save error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "저장 중 오류가 발생했습니다: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
