<?php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== member 테이블 구조 ===\n\n";
$sql = "DESCRIBE member";
$result = $pdo->query($sql);
foreach ($result as $row) {
    echo $row['Field'] . " | " . $row['Type'] . " | Key: " . $row['Key'] . "\n";
}

echo "\n=== member 테이블 샘플 데이터 (5개) ===\n";
$sql2 = "SELECT * FROM member LIMIT 5";
$result2 = $pdo->query($sql2);
foreach ($result2 as $row) {
    print_r($row);
    echo "\n";
}
?>
