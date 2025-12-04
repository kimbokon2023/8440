<?php
require_once __DIR__ . '/../bootstrap.php';

try {
    $pdo = db_connect();
    
    // Check if column exists
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `estimates` LIKE 'business_registration_number'");
    $stmt->execute();
    $exists = $stmt->fetch();
    
    if (!$exists) {
        $sql = "ALTER TABLE `estimates` ADD COLUMN `business_registration_number` VARCHAR(50) DEFAULT NULL COMMENT '사업자등록번호' AFTER `supplier_fax`";
        $pdo->exec($sql);
        echo "Column 'business_registration_number' added successfully.\n";
    } else {
        echo "Column 'business_registration_number' already exists.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
