<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

// 헤더 설정 - JSON 응답
header('Content-Type: application/json');

// 권한 확인
if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POST 요청만 허용됩니다.']);
    exit;
}

// JSON 데이터 받기
$input = json_decode(file_get_contents('php://input'), true);
$bulk = isset($input['bulk']) && $input['bulk'] === true;

if ($bulk) {
    // 일괄 삭제 처리
    $ids = isset($input['ids']) ? $input['ids'] : [];

    if (empty($ids) || !is_array($ids)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => '삭제할 ID 목록이 필요합니다.']);
        exit;
    }

    // ID 유효성 검사
    $ids = array_map('intval', $ids);
    $ids = array_filter($ids, function($id) { return $id > 0; });

    if (empty($ids)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => '올바른 ID가 없습니다.']);
        exit;
    }
} else {
    // 단일 삭제 처리
    $id = isset($input['id']) ? (int)$input['id'] : 0;

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => '올바르지 않은 ID입니다.']);
        exit;
    }

    $ids = [$id];
}

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '데이터베이스 연결 실패']);
    exit;
}

try {
    // 발주서 존재 확인
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $check_sql = "SELECT id, supplier_name FROM `order` WHERE id IN ($placeholders) AND is_deleted = 0";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute($ids);
    $existing_orders = $check_stmt->fetchAll();

    if (empty($existing_orders)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => '존재하지 않는 발주서입니다.']);
        exit;
    }

    $existing_ids = array_column($existing_orders, 'id');
    $existing_count = count($existing_ids);

    // Soft Delete 실행 (is_deleted = 1로 설정)
    $delete_placeholders = str_repeat('?,', count($existing_ids) - 1) . '?';
    $delete_sql = "UPDATE `order` SET is_deleted = 1, updated_at = CURRENT_TIMESTAMP WHERE id IN ($delete_placeholders)";
    $delete_stmt = $pdo->prepare($delete_sql);

    if ($delete_stmt->execute($existing_ids)) {
        // 삭제 성공
        $deleted_count = $delete_stmt->rowCount();

        if ($bulk) {
            echo json_encode([
                'success' => true,
                'message' => "{$deleted_count}개의 발주서가 삭제되었습니다.",
                'deleted_count' => $deleted_count,
                'requested_count' => count($ids),
                'deleted_ids' => $existing_ids
            ]);

            // 로그 기록
            error_log("Bulk Order Delete: {$deleted_count} orders deleted by {$_SESSION['name']}, IDs: " . implode(',', $existing_ids));
        } else {
            // 단일 삭제의 경우 기존 응답 유지
            $order = $existing_orders[0];
            echo json_encode([
                'success' => true,
                'message' => '발주서가 삭제되었습니다.',
                'data' => [
                    'id' => $order['id'],
                    'supplier_name' => $order['supplier_name']
                ]
            ]);

            // 로그 기록
            error_log("Order deleted: ID={$order['id']}, Supplier={$order['supplier_name']}, User={$_SESSION['name']}");
        }

    } else {
        throw new Exception('삭제 처리 중 오류가 발생했습니다.');
    }

} catch (PDOException $e) {
    // 데이터베이스 오류
    error_log("Order Delete Database Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);

} catch (Exception $e) {
    // 기타 오류
    error_log("Order Delete Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>