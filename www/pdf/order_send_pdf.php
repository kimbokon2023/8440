<?php
/**
 * 발주서 업체 발송용 PDF 생성 (다운로드)
 */

require_once __DIR__ . '/../lib/OrderPdfGenerator.php';

// ------------------------- 입력값 -------------------------
$id = $_GET['id'] ?? 0;
$id = (int)$id;
$id = (int)$id;
$download = $_GET['download'] ?? 1;
$preview = $_GET['preview'] ?? 0;

if ($preview) {
    $download = 0;
}
$download = (int)$download;

if ($id <= 0) {
    http_response_code(400);
    exit('잘못된 id 파라미터입니다.');
}

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 5) {
    http_response_code(403);
    exit('권한이 없습니다.');
}

try {
    $generator = new OrderPdfGenerator();
    $generator->stream($id, (bool)$download);
} catch (Exception $e) {
    error_log("PDF 생성 오류: " . $e->getMessage());
    http_response_code(500);
    echo 'PDF 생성 중 오류가 발생했습니다: ' . htmlspecialchars($e->getMessage());
}
