<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php"); 
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

$item = $_POST['item'] ?? '';

$searchspec = '';
$searchsupplier = '';
$default_condition = false;

if ($item == '304 HL') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '현진스텐';
    $default_condition = true;
} elseif ($item == '201 2B MR') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '윤스틸';
    $default_condition = true;
} elseif ($item == '201 HL' || $item == '201 VB') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '우성스틸';
    $default_condition = true;
} elseif ($item == '201 MR BEAD' || $item == '2B VB' || $item == 'VB') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '한산엘테크';
    $default_condition = true;
} elseif ($item == 'CR') {
    $searchspec = '1.2*1219*1950';
    $searchsupplier = '용민철강';
    $default_condition = true;
} elseif ($item == 'PO') {
    $searchspec = '1.2*1219*1950';
    $searchsupplier = '용민철강';
    $default_condition = true;
} elseif ($item == 'EGI') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '용민철강';
    $default_condition = true;
}

$unitprice = 0;
$data = [];

try {
    for ($i = 23; $i >= 0; $i--) {
        $target_month = date('Y-m-01', strtotime("-$i months"));
        $next_month = date('Y-m-01', strtotime("$target_month +1 month"));

        $sql = "SELECT steel_item, spec, supplier, suppliercost, steelnum
                FROM {$DB}.eworks
                WHERE outdate >= :target_month
                AND outdate < :next_month
                AND eworks_item = '원자재구매'
                AND (is_deleted IS NULL OR is_deleted = '') ";

        if ($default_condition) {
            $sql .= " AND spec = :spec AND supplier LIKE :supplier";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':target_month', $target_month);
        $stmt->bindParam(':next_month', $next_month);
        if ($default_condition) {
            $stmt->bindValue(':spec', $searchspec);
            $stmt->bindValue(':supplier', "%$searchsupplier%");
        }

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $suppliercost = (int)str_replace(',', '', $row['suppliercost']);
            $spec_parts = explode('*', $row['spec']);

            $val1 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[0] ?? 0);
            $val2 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[1] ?? 0);
            $val3 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[2] ?? 0);
            $weight = ($val1 * $val2 * $val3 * 7.93 * (int)$row['steelnum']) / 1000000;
            $weight = $weight > 0 ? $weight : 1;
            $unit_weight = floor($suppliercost / $weight);
            $month = substr($target_month, 0, 7);
            $data[$month] = $unit_weight;
        }
    }

    // 최근 값 추출
    if (!empty($data)) {
        krsort($data);
        foreach ($data as $price) {
            if ($price > 0) {
                $unitprice = $price;
                break;
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode(["unitprice" => $unitprice]);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
?>
