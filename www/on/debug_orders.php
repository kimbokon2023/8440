<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>발주 데이터 디버그</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        table { background: white; border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background: #667eea; color: white; }
        .duplicate { background: #ffebee; }
        h2 { color: #333; }
        .info { background: #e3f2fd; padding: 15px; margin: 20px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 발주 데이터 디버그</h1>

    <?php
    try {
        // 전체 발주 개수
        $countSql = "SELECT COUNT(*) as total FROM daon_orders";
        $countStmt = $pdo->query($countSql);
        $count = $countStmt->fetch();

        echo "<div class='info'>";
        echo "<strong>전체 발주 건수:</strong> " . $count['total'] . "건<br>";

        // 중복 발주번호 확인
        $dupSql = "SELECT order_number, COUNT(*) as cnt
                   FROM daon_orders
                   GROUP BY order_number
                   HAVING COUNT(*) > 1";
        $dupStmt = $pdo->query($dupSql);
        $duplicates = $dupStmt->fetchAll();

        if (count($duplicates) > 0) {
            echo "<strong style='color:red;'>⚠️ 중복 발주번호 발견:</strong> " . count($duplicates) . "개<br>";
            foreach ($duplicates as $dup) {
                echo "- " . htmlspecialchars($dup['order_number']) . " : " . $dup['cnt'] . "건<br>";
            }
        } else {
            echo "<strong style='color:green;'>✅ 중복 발주번호 없음</strong>";
        }
        echo "</div>";

        // 전체 발주 목록 (최근 20건)
        $sql = "SELECT
                    id,
                    order_number,
                    order_date,
                    delivery_date,
                    customer_id,
                    product_name,
                    quantity,
                    unit,
                    total_price,
                    status,
                    created_at,
                    created_by
                FROM daon_orders
                ORDER BY id DESC
                LIMIT 50";

        $stmt = $pdo->query($sql);
        $orders = $stmt->fetchAll();

        echo "<h2>📋 최근 발주 목록 (최대 50건)</h2>";
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>발주번호</th>";
        echo "<th>발주일</th>";
        echo "<th>납품일</th>";
        echo "<th>거래처ID</th>";
        echo "<th>제품명</th>";
        echo "<th>수량</th>";
        echo "<th>금액</th>";
        echo "<th>상태</th>";
        echo "<th>등록일시</th>";
        echo "<th>등록자</th>";
        echo "</tr>";

        $prevOrderNumber = '';
        foreach ($orders as $order) {
            $isDuplicate = ($order['order_number'] === $prevOrderNumber);
            $rowClass = $isDuplicate ? 'duplicate' : '';

            echo "<tr class='$rowClass'>";
            echo "<td>" . $order['id'] . "</td>";
            echo "<td><strong>" . htmlspecialchars($order['order_number']) . "</strong></td>";
            echo "<td>" . $order['order_date'] . "</td>";
            echo "<td>" . ($order['delivery_date'] ?: '-') . "</td>";
            echo "<td>" . $order['customer_id'] . "</td>";
            echo "<td>" . htmlspecialchars($order['product_name']) . "</td>";
            echo "<td>" . number_format($order['quantity']) . " " . htmlspecialchars($order['unit']) . "</td>";
            echo "<td>" . number_format($order['total_price']) . "원</td>";
            echo "<td>" . $order['status'] . "</td>";
            echo "<td>" . $order['created_at'] . "</td>";
            echo "<td>" . htmlspecialchars($order['created_by']) . "</td>";
            echo "</tr>";

            $prevOrderNumber = $order['order_number'];
        }

        echo "</table>";

        echo "<div class='info' style='margin-top:20px;'>";
        echo "<strong>중복 삭제 방법:</strong><br>";
        echo "중복된 데이터를 삭제하려면, 각 발주번호별로 가장 최근 것만 남기고 나머지를 삭제해야 합니다.<br>";
        echo "삭제하려는 ID를 확인한 후, 아래 페이지로 이동하여 삭제하세요:<br>";
        echo "<a href='order_view.php?id=[ID번호]'>발주 상세보기에서 삭제</a>";
        echo "</div>";

    } catch (PDOException $e) {
        echo "<div style='color:red;'>오류: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    ?>

    <p><a href="index.php">← 메인으로 돌아가기</a></p>
</body>
</html>
