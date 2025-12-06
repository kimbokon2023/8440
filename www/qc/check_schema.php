<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$sql = "DESCRIBE mirae8440.mymc";
$stmt = $pdo->query($sql);
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Table: mymc\n";
foreach ($columns as $col) {
    echo $col['Field'] . " | " . $col['Type'] . " | " . $col['Null'] . " | " . $col['Key'] . "\n";
}
?>
