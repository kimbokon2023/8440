<?php
require_once __DIR__ . '/../bootstrap.php';

$DB = $_SESSION["DB"] ?? 'mirae8440';

echo "<h1>Debug Info</h1>";
echo "DB: $DB<br>";

try {
    // 1. Check total count
    $sql = "SELECT count(*) as cnt FROM {$DB}.eworks";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total rows in eworks: " . $row['cnt'] . "<br><br>";

    // 2. Check distinct eworks_item
    $sql = "SELECT eworks_item, count(*) as cnt FROM {$DB}.eworks GROUP BY eworks_item";
    $stmt = $pdo->query($sql);
    echo "<b>eworks_item distribution:</b><br>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "[" . $row['eworks_item'] . "]: " . $row['cnt'] . "<br>";
    }
    echo "<br>";

    // 3. Dump last 50 rows to see raw data
    $sql = "SELECT num, eworks_item, outdate, registdate, is_deleted, which FROM {$DB}.eworks ORDER BY num DESC LIMIT 50";
    $stmt = $pdo->query($sql);
    echo "<b>Last 50 rows:</b><br>";
    echo "<table border='1'><tr><th>Num</th><th>Item</th><th>Outdate</th><th>Registdate</th><th>Is Deleted</th><th>Which</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['num'] . "</td>";
        echo "<td>" . $row['eworks_item'] . "</td>";
        echo "<td>" . $row['outdate'] . "</td>";
        echo "<td>" . $row['registdate'] . "</td>";
        echo "<td>" . var_export($row['is_deleted'], true) . "</td>";
        echo "<td>" . $row['which'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
