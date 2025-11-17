<?php
/**
 * 구매카트에 항목 추가 처리
 * eworks 테이블의 cart 컬럼을 업데이트하여 구매카트에 담긴 항목을 기록
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// AJAX 요청 여부 확인
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 권한 확인
if (!isset($_SESSION["level"]) || $level > 5) {
    echo json_encode([
        'success' => false,
        'message' => '권한이 없습니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'POST 요청만 허용됩니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => '데이터베이스 연결 실패: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 선택된 항목들 받기
$items = $_POST['items'] ?? [];

if (empty($items) || !is_array($items)) {
    echo json_encode([
        'success' => false,
        'message' => '선택된 항목이 없습니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 숫자 배열로 변환 및 검증
$item_nums = [];
foreach ($items as $item) {
    $num = (int)$item;
    if ($num > 0) {
        $item_nums[] = $num;
    }
}

if (empty($item_nums)) {
    echo json_encode([
        'success' => false,
        'message' => '유효한 항목이 없습니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo->beginTransaction();

    // 선택된 항목들의 cart 컬럼을 1로 업데이트
    $placeholders = implode(',', array_fill(0, count($item_nums), '?'));
    $sql = "UPDATE {$DB}.eworks 
            SET cart = 1 
            WHERE num IN ({$placeholders}) 
            AND is_deleted IS NULL 
            AND eworks_item = '원자재구매'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($item_nums);

    $affected_rows = $stmt->rowCount();

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => $affected_rows . '개의 항목이 구매카트에 담겼습니다.',
        'count' => $affected_rows
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("구매카트 추가 오류: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => '구매카트 추가 중 오류가 발생했습니다: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

