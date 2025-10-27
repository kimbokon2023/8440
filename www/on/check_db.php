<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== 데이터베이스 직접 확인 ===\n\n";

// 1. 전체 발주 개수
$sql1 = "SELECT COUNT(*) as cnt FROM daon_orders";
$result1 = $pdo->query($sql1)->fetch();
echo "1. 전체 발주 개수: " . $result1['cnt'] . "건\n\n";

// 2. 발주번호별 개수
$sql2 = "SELECT order_number, COUNT(*) as cnt FROM daon_orders GROUP BY order_number ORDER BY cnt DESC";
$result2 = $pdo->query($sql2)->fetchAll();
echo "2. 발주번호별 개수:\n";
foreach ($result2 as $row) {
    echo "   " . $row['order_number'] . " : " . $row['cnt'] . "건\n";
}

// 3. 모든 발주 ID와 번호
echo "\n3. 모든 발주 상세:\n";
$sql3 = "SELECT id, order_number, order_date, product_name, created_at FROM daon_orders ORDER BY id";
$result3 = $pdo->query($sql3)->fetchAll();
foreach ($result3 as $row) {
    echo "   ID=" . $row['id'] .
         " | 번호=" . $row['order_number'] .
         " | 날짜=" . $row['order_date'] .
         " | 제품=" . $row['product_name'] .
         " | 등록=" . $row['created_at'] . "\n";
}

// 4. index.php와 동일한 쿼리 실행
echo "\n4. index.php와 동일한 쿼리 실행:\n";
$sql4 = "SELECT
            o.id,
            o.order_number,
            o.order_date,
            o.delivery_date,
            o.customer_id,
            c.company_name as customer_name,
            o.product_name,
            o.product_type,
            o.quantity,
            o.unit,
            o.unit_price,
            o.total_price,
            o.status,
            o.priority,
            o.created_by,
            o.created_at,
            u.name as creator_name
        FROM daon_orders o
        LEFT JOIN daon_customers c ON o.customer_id = c.id
        LEFT JOIN member u ON o.created_by = u.id
        WHERE 1=1
        ORDER BY o.order_date DESC, o.created_at DESC
        LIMIT 100";

$result4 = $pdo->query($sql4)->fetchAll();
echo "   결과 개수: " . count($result4) . "건\n\n";
foreach ($result4 as $row) {
    echo "   ID=" . $row['id'] .
         " | 번호=" . $row['order_number'] .
         " | 거래처=" . $row['customer_name'] .
         " | 제품=" . $row['product_name'] . "\n";
}

echo "\n=== 분석 완료 ===\n";
?>
