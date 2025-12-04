<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/lib/mydb.php';

echo "Checking 'estimates' table schema using db_connect()...\n";

try {
    $pdo = db_connect();
    
    $current_db = $pdo->query('SELECT DATABASE()')->fetchColumn();
    echo "Connected DB: $current_db\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM estimates");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Columns in 'estimates':\n";
    foreach ($columns as $col) {
        echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
    
    // Check if email exists
    $email_exists = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'email') {
            $email_exists = true;
            break;
        }
    }
    
    if ($email_exists) {
        echo "\nSUCCESS: 'email' column found.\n";
    } else {
        echo "\nFAILURE: 'email' column NOT found.\n";
        
        // Try to add it
        echo "Attempting to add 'email' column...\n";
        $pdo->exec("ALTER TABLE `estimates` ADD COLUMN `email` VARCHAR(100) DEFAULT NULL COMMENT '이메일'");
        echo "Column 'email' added.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
