<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

header('Content-Type: application/json');

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// 월의 시작일과 종료일 계산
$startDate = sprintf('%04d-%02d-01', $year, $month);
$endDate = date('Y-m-t', strtotime($startDate));

try {
    $sql = "SELECT
                o.id,
                o.order_number,
                o.delivery_date,
                o.status,
                o.order_items,
                c.company_name as customer_name
            FROM daon_orders o
            LEFT JOIN daon_customers c ON o.customer_id = c.id
            WHERE o.delivery_date BETWEEN ? AND ?
            ORDER BY o.delivery_date, o.order_number";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$startDate, $endDate]);
    $orders = $stmt->fetchAll();

    // 날짜별로 그룹화
    $calendar = [];
    foreach ($orders as $order) {
        $date = $order['delivery_date'];
        if (!isset($calendar[$date])) {
            $calendar[$date] = [];
        }
        
        // order_items JSON 파싱
        $first_product_name = '-';
        $item_count = 0;
        $total_amount = 0;
        $items_summary = [];
        
        if (!empty($order['order_items'])) {
            try {
                $items = json_decode($order['order_items'], true);
                if (is_array($items) && count($items) > 0) {
                    $first_product_name = $items[0]['product_name'] ?? '-';
                    $item_count = count($items);
                    
                    foreach ($items as $item) {
                        $amount = intval($item['amount'] ?? 0);
                        $total_amount += $amount;
                        
                        $items_summary[] = [
                            'product_name' => $item['product_name'] ?? '',
                            'spec' => $item['spec'] ?? '',
                            'quantity' => $item['quantity'] ?? '',
                            'unit' => $item['unit'] ?? 'EA',
                            'unit_price' => intval($item['unit_price'] ?? 0),
                            'amount' => $amount
                        ];
                    }
                }
            } catch (Exception $e) {
                $first_product_name = '(오류)';
            }
        }
        
        $calendar[$date][] = [
            'id' => $order['id'],
            'order_number' => $order['order_number'],
            'customer_name' => $order['customer_name'],
            'first_product_name' => $first_product_name,
            'item_count' => $item_count,
            'total_amount' => $total_amount,
            'items' => $items_summary,
            'status' => $order['status']
        ];
    }

    echo json_encode($calendar, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '데이터 조회 실패: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>
