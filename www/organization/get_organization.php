<?php
/**
 * 조직도 데이터 API
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "권한이 없습니다."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// CORS 헤더 (개발 중에만 사용, 배포 시 특정 도메인 지정)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

// JSON 파일 경로
$filename = __DIR__ . "/organization.json";

try {
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        if ($content === false) {
            throw new Exception("파일을 읽을 수 없습니다.");
        }
        
        // JSON 유효성 검사
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON 형식이 올바르지 않습니다: " . json_last_error_msg());
        }
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "조직도 파일을 찾을 수 없습니다."
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    error_log("Organization API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "데이터 처리 중 오류가 발생했습니다."
    ], JSON_UNESCAPED_UNICODE);
}
?>
