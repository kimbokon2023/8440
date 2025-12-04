<?php
// Force local environment config
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'mirae8440';

$dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";

echo "Connecting to DB: $db_name as $db_user...\n";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "Connected successfully.\n";

    // List all databases
    echo "Listing databases:\n";
    $stmt = $pdo->query("SHOW DATABASES");
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo implode(", ", $dbs) . "\n";
    
    // List of databases to check
    $target_dbs = ['jtechel', 'mirae8440', 'phomistonekorea', 'somanji131'];
    
    foreach ($target_dbs as $db) {
        if (!in_array($db, $dbs)) continue;
        
        echo "\nProcessing database: $db...\n";
        
        try {
            $pdo->exec("USE `$db`");
            
            // Check if estimates table exists
            $stmt = $pdo->query("SHOW TABLES LIKE 'estimates'");
            if (!$stmt->fetch()) {
                echo "Table 'estimates' does not exist in $db.\n";
                continue;
            }
            
            // Check columns
            $stmt = $pdo->query("SHOW COLUMNS FROM estimates");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('email', $columns)) {
                echo "Column 'email' ALREADY EXISTS in $db.\n";
            } else {
                echo "Column 'email' NOT FOUND in $db. Adding it now...\n";
                $pdo->exec("ALTER TABLE `estimates` ADD COLUMN `email` VARCHAR(100) DEFAULT NULL COMMENT '이메일'");
                echo "Column 'email' added successfully to $db.\n";
            }
        } catch (Exception $e) {
            echo "Error processing $db: " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
