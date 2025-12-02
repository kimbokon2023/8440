<?php
/**
 * 이메일 발송 로그 삭제 처리
 */
require_once __DIR__ . '/../bootstrap.php';

// JSON 응답 헤더
header('Content-Type: application/json; charset=utf-8');

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 5) {
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

// 요청 메서드 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;
}

// 데이터 받기
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? 0;

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => '삭제할 ID가 없습니다.']);
    exit;
}

try {
    $pdo = db_connect();
    
    // 삭제 쿼리 실행
    $stmt = $pdo->prepare("DELETE FROM sent_email_logs WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => '삭제되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '삭제할 데이터가 없거나 이미 삭제되었습니다.']);
    }

} catch (Exception $e) {
    error_log("Email Log Delete Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '삭제 중 오류가 발생했습니다.']);
}
?>
