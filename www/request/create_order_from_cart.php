<?php
/**
 * 구매카트에서 발주서 생성
 * eworks 테이블에서 cart = 1인 항목들을 order 테이블에 추가
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

try {
    $pdo->beginTransaction();

    // 구매카트에 담긴 항목들 조회 (cart = 1)
    $sql = "SELECT * FROM {$DB}.eworks 
            WHERE cart = 1 
            AND is_deleted IS NULL 
            AND eworks_item = '원자재구매'
            ORDER BY num ASC";

    $stmt = $pdo->query($sql);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_items)) {
        echo json_encode([
            'success' => false,
            'message' => '구매카트에 담긴 항목이 없습니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 공급처별로 그룹화
    $supplier_groups = [];
    foreach ($cart_items as $item) {
        $supplier = $item['supplier'] ?? '';
        if (empty($supplier)) {
            $supplier = '미지정';
        }
        
        if (!isset($supplier_groups[$supplier])) {
            $supplier_groups[$supplier] = [];
        }
        $supplier_groups[$supplier][] = $item;
    }

    $created_orders = [];
    $total_items = 0;

    // 공급처별로 발주서 생성
    foreach ($supplier_groups as $supplier => $items) {
        // 발주서 번호 생성
        $order_no = date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // 발주일자 (오늘 날짜)
        $issue_date = date('Y-m-d');
        
        // 발주 품목 배열 생성
        $order_items = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $quantity = floatval($item['steelnum'] ?? 0);
            $unit_price = floatval(str_replace(',', '', $item['suppliercost'] ?? 0));
            $supply_amount = $quantity * $unit_price;
            $tax = round($supply_amount * 0.1);
            $total_amount = $supply_amount + $tax;

            $order_items[] = [
                'item_name' => $item['steel_item'] ?? '',
                'spec' => $item['spec'] ?? '',
                'quantity' => $quantity,
                'unit' => 'EA',
                'unit_price' => $unit_price,
                'supply_amount' => $supply_amount,
                'tax' => $tax,
                'total_amount' => $total_amount,
                'note' => $item['request_comment'] ?? ''
            ];

            $subtotal += $supply_amount;
        }

        $order_items_json = json_encode($order_items, JSON_UNESCAPED_UNICODE);

        // order 테이블에 INSERT
        $insert_sql = "INSERT INTO `orders` (
            order_no, issue_date, supplier_name, contact_name,
            order_items, subtotal, delivery_date, delivery_location,
            payment_terms, note, status, created_at, updated_at, is_deleted
        ) VALUES (
            :order_no, :issue_date, :supplier_name, :contact_name,
            :order_items, :subtotal, :delivery_date, :delivery_location,
            :payment_terms, :note, :status, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0
        )";

        $insert_stmt = $pdo->prepare($insert_sql);
        
        // 첫 번째 항목의 정보를 기본값으로 사용
        $first_item = $items[0];
        $delivery_date = !empty($first_item['requestdate']) ? $first_item['requestdate'] : null;
        $delivery_location = $first_item['outworkplace'] ?? null;
        
        $insert_params = [
            ':order_no' => $order_no,
            ':issue_date' => $issue_date,
            ':supplier_name' => $supplier,
            ':contact_name' => $supplier, // 공급처명을 거래처명으로도 사용
            ':order_items' => $order_items_json,
            ':subtotal' => $subtotal,
            ':delivery_date' => $delivery_date,
            ':delivery_location' => $delivery_location,
            ':payment_terms' => null,
            ':note' => '구매카트에서 자동 생성',
            ':status' => 'draft'
        ];

        if ($insert_stmt->execute($insert_params)) {
            $order_id = $pdo->lastInsertId();
            $created_orders[] = [
                'id' => $order_id,
                'order_no' => $order_no,
                'supplier' => $supplier,
                'item_count' => count($items)
            ];
            $total_items += count($items);
        }
    }

    // 구매카트 비우기 (cart = 0으로 업데이트)
    $update_cart_sql = "UPDATE {$DB}.eworks 
                        SET cart = 0 
                        WHERE cart = 1 
                        AND is_deleted IS NULL 
                        AND eworks_item = '원자재구매'";
    $pdo->exec($update_cart_sql);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => count($created_orders) . '개의 발주서가 생성되었습니다. (총 ' . $total_items . '개 항목)',
        'orders' => $created_orders,
        'order_count' => count($created_orders),
        'item_count' => $total_items
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("발주서 생성 오류: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => '발주서 생성 중 오류가 발생했습니다: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

