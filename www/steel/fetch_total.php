<?php
header("Content-Type: application/json; charset=utf-8");

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$item = trim($_POST['item'] ?? '');
$spec = trim($_POST['spec'] ?? '');
$company = trim($_POST['company'] ?? '');

$sum_title = [];
$sum = [];
$company_arr = [];

// 기준목록
try {
    $sql = "SELECT * FROM mirae8440.steelsource ORDER BY sortorder ASC, item ASC, spec ASC";
    $stmh = $pdo->query($sql);
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $i = trim($row["item"]);
        $s = trim($row["spec"]);
        $c = trim($row["take"]);

        // 입력값이 있을 경우만 필터
        if (
            ($item && $item !== $i) ||
            ($spec && $spec !== $s) ||
            ($company && $company !== $c)
        ) continue;

        $sum_title[] = $i . $s . $c;
        $company_arr[] = $c;
    }
    $sum_title = array_unique($sum_title);
    sort($sum_title);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// 입출고 집계
try {
    $sql = "SELECT * FROM mirae8440.steel ORDER BY outdate";
    $stmh = $pdo->query($sql);
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $i = trim($row["item"]);
        $s = trim($row["spec"]);
        $c = trim($row["company"]);
        $which = $row["which"];
        $num = (int)$row["steelnum"];

        $key = $i . $s . $c;
        foreach ($sum_title as $idx => $title) {
            if ($key === $title) {
                $sum[$idx] = ($sum[$idx] ?? 0) + ($which == '1' ? $num : -$num);
            }
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

echo json_encode([
    'sum_title' => array_values($sum_title),
    'sum' => array_values($sum),
    'company_arr' => array_values($company_arr),
    'sumcount' => count($sum_title)
]);
exit;
