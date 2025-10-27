<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== member 테이블 확인 ===\n\n";

// 1. member 테이블 구조 확인
echo "1. member 테이블 구조:\n";
$sql1 = "DESCRIBE member";
$result1 = $pdo->query($sql1)->fetchAll();
foreach ($result1 as $row) {
    echo "   " . $row['Field'] . " | " . $row['Type'] . " | " . $row['Key'] . "\n";
}

// 2. member 테이블 데이터 (id=0 확인)
echo "\n2. id=0인 member 데이터:\n";
$sql2 = "SELECT * FROM member WHERE id = 0 OR id = '0'";
$result2 = $pdo->query($sql2)->fetchAll();
echo "   결과 개수: " . count($result2) . "건\n";
foreach ($result2 as $row) {
    echo "   ID=" . $row['id'] . " | userid=" . $row['userid'] . " | name=" . $row['name'] . "\n";
}

// 3. 모든 member 데이터
echo "\n3. 모든 member 데이터:\n";
$sql3 = "SELECT id, userid, name FROM member ORDER BY id";
$result3 = $pdo->query($sql3)->fetchAll();
echo "   전체 개수: " . count($result3) . "건\n";
foreach ($result3 as $row) {
    echo "   ID=" . $row['id'] . " | userid=" . $row['userid'] . " | name=" . $row['name'] . "\n";
}

// 4. daon_orders의 created_by 값 확인
echo "\n4. daon_orders의 created_by 값:\n";
$sql4 = "SELECT DISTINCT created_by FROM daon_orders ORDER BY created_by";
$result4 = $pdo->query($sql4)->fetchAll();
foreach ($result4 as $row) {
    echo "   created_by=" . $row['created_by'] . "\n";
}

// 5. 문제의 JOIN 쿼리 실행
echo "\n5. 문제의 JOIN 쿼리:\n";
$sql5 = "SELECT o.id, o.order_number, o.created_by, u.id as user_id, u.userid, u.name
         FROM daon_orders o
         LEFT JOIN member u ON o.created_by = u.id
         WHERE o.id = 4";
$result5 = $pdo->query($sql5)->fetchAll();
echo "   결과 개수: " . count($result5) . "건\n";
foreach ($result5 as $row) {
    echo "   order_id=" . $row['id'] .
         " | created_by=" . $row['created_by'] .
         " | user_id=" . $row['user_id'] .
         " | userid=" . $row['userid'] .
         " | name=" . $row['name'] . "\n";
}

echo "\n=== 분석 완료 ===\n";
?>
