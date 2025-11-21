<?php
/**
 * 발주서 상세 정보 조회 API
 */

require_once __DIR__ . '/../bootstrap.php';

// JSON 응답 헤더
header('Content-Type: application/json; charset=utf-8');

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 5) {
    echo json_encode([
        'success' => false,
        'message' => '권한이 없습니다.'
    ]);
    exit;
}

// ID 파라미터 확인
$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode([
        'success' => false,
        'message' => '발주서 ID가 필요합니다.'
    ]);
    exit;
}

try {
    // 데이터베이스 연결
    $pdo = db_connect();
    
    // 발주서 정보 조회
    $sql = "SELECT * FROM `orders` WHERE id = :id AND is_deleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo json_encode([
            'success' => false,
            'message' => '발주서를 찾을 수 없습니다.'
        ]);
        exit;
    }
    
    // 날짜 포맷 변환
    if ($order['issue_date']) {
        $order['issue_date'] = date('Y-m-d', strtotime($order['issue_date']));
    }
    if ($order['delivery_date']) {
        $order['delivery_date'] = date('Y-m-d', strtotime($order['delivery_date']));
    }
    
    // 성공 응답
    echo json_encode([
        'success' => true,
        'order' => $order
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("발주서 조회 오류: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => '데이터 조회 중 오류가 발생했습니다.'
    ]);
}

