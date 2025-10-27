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
                o.quantity,
                o.unit,
                o.total_price,
                c.company_name as customer_name,
                o.product_name
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
        $calendar[$date][] = [
            'id' => $order['id'],
            'order_number' => $order['order_number'],
            'customer_name' => $order['customer_name'],
            'product_name' => $order['product_name'],
            'quantity' => $order['quantity'],
            'unit' => $order['unit'],
            'total_price' => $order['total_price'],
            'status' => $order['status']
        ];
    }

    echo json_encode($calendar, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '데이터 조회 실패: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>
