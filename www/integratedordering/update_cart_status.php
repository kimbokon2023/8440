<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 및 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 8) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

header('Content-Type: application/json; charset=utf-8');

// 요청 데이터 수신
$nums = $_POST['nums'] ?? []; // 배열 형태의 num
$cart = $_POST['cart'] ?? 0;  // 1: 담기, 0: 빼기 (필요시)

if (empty($nums)) {
    echo json_encode(['success' => false, 'message' => '선택된 항목이 없습니다.']);
    exit;
}

try {
    // nums 배열을 정수형으로 변환 및 유효성 검사
    $nums = array_map('intval', $nums);
    $nums = array_filter($nums); // 0 또는 false 제거

    if (empty($nums)) {
        echo json_encode(['success' => false, 'message' => '유효한 항목이 없습니다.']);
        exit;
    }

    // IN 절을 위한 플레이스홀더 생성 (?, ?, ?)
    $placeholders = implode(',', array_fill(0, count($nums), '?'));
    
    $sql = "UPDATE mirae8440.eworks SET cart = ? WHERE num IN ($placeholders)";
    
    $stmt = $pdo->prepare($sql);
    
    // 파라미터 바인딩: 첫 번째는 cart 값, 나머지는 nums 값들
    $params = array_merge([$cart], $nums);
    $stmt->execute($params);
    
    echo json_encode(['success' => true, 'message' => count($nums) . '건이 처리되었습니다.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB 오류: ' . $e->getMessage()]);
}
?>
