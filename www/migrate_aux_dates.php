<?php
require_once 'bootstrap.php';

$DB = $_SESSION['DB'] ?? 'mirae8440';
$pdo = db_connect();

echo "Starting migration for Auxiliary Materials (부자재구매)...\n";

try {
    $pdo->beginTransaction();

    // 1. Move Delivery Date: outdate -> requestdate
    // 현재 'outdate'에 저장된 납기일을 'requestdate'로 복사
    $sql1 = "UPDATE {$DB}.eworks SET requestdate = outdate WHERE eworks_item = '부자재구매'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $count1 = $stmt1->rowCount();
    echo "Step 1: Copied outdate to requestdate for $count1 rows.\n";

    // 2. Move Receipt Date: registdate -> outdate
    // 현재 'registdate'에 저장된 접수일을 'outdate'로 복사
    // registdate는 datetime일 수 있으므로 그대로 복사하거나 date() 변환 필요할 수 있음.
    // DB 컬럼 타입에 따라 자동 변환되기를 기대하지만, 명시적으로 처리하는 것이 안전할 수 있음.
    // 하지만 같은 datetime 타입이라면 그대로 복사.
    $sql2 = "UPDATE {$DB}.eworks SET outdate = registdate WHERE eworks_item = '부자재구매'";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute();
    $count2 = $stmt2->rowCount();
    echo "Step 2: Copied registdate to outdate for $count2 rows.\n";

    $pdo->commit();
    echo "Migration completed successfully.\n";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>
