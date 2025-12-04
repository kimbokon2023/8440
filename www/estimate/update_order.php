<?php
/**
 * 발주서 수정 API
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

// POST 데이터 받기
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// 필수 파라미터 확인
$id = $data['id'] ?? '';
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

    // 업데이트할 필드 구성
    $updateFields = [];
    $params = [':id' => $id];

    // 공급업체명
    if (isset($data['supplier_name'])) {
        $updateFields[] = "supplier_name = :supplier_name";
        $params[':supplier_name'] = $data['supplier_name'];
    }

    // 발행일
    if (isset($data['issue_date'])) {
        $updateFields[] = "issue_date = :issue_date";
        $params[':issue_date'] = $data['issue_date'];
    }

    // 납기일자
    if (isset($data['delivery_date'])) {
        $updateFields[] = "delivery_date = :delivery_date";
        $params[':delivery_date'] = $data['delivery_date'];
    }

    // 비고
    if (isset($data['note'])) {
        $updateFields[] = "note = :note";
        $params[':note'] = $data['note'];
    }

    // 상태
    if (isset($data['status'])) {
        $updateFields[] = "status = :status";
        $params[':status'] = $data['status'];
    }

    // 공급가액
    if (isset($data['subtotal'])) {
        $updateFields[] = "subtotal = :subtotal";
        $params[':subtotal'] = $data['subtotal'];
    }

    // 품목 JSON
    if (isset($data['order_items'])) {
        $updateFields[] = "order_items = :order_items";
        $params[':order_items'] = is_string($data['order_items']) ? $data['order_items'] : json_encode($data['order_items'], JSON_UNESCAPED_UNICODE);
    }

    // 업데이트 시간
    $updateFields[] = "updated_at = NOW()";

    if (empty($updateFields)) {
        echo json_encode([
            'success' => false,
            'message' => '업데이트할 데이터가 없습니다.'
        ]);
        exit;
    }

    // SQL 실행
    $sql = "UPDATE `orders` SET " . implode(', ', $updateFields) . " WHERE id = :id AND is_deleted = 0";
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => '발주서가 성공적으로 수정되었습니다.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '수정할 발주서를 찾을 수 없거나 변경사항이 없습니다.'
        ]);
    }

} catch (Exception $e) {
    error_log("발주서 수정 오류: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => '데이터 수정 중 오류가 발생했습니다: ' . $e->getMessage()
    ]);
}
