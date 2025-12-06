<?php
// CLI 환경에서 localhost로 인식되도록 설정
$_SERVER['HTTP_HOST'] = 'localhost';

require_once __DIR__ . '/../bootstrap.php';

$pdo = db_connect();

try {
    $sql = "CREATE TABLE IF NOT EXISTS `admin_phomi` (
        `num` int(11) NOT NULL AUTO_INCREMENT,
        `member_id` varchar(50) NOT NULL,
        `member_name` varchar(50) NOT NULL,
        `rank_order` int(11) NOT NULL DEFAULT 0,
        PRIMARY KEY (`num`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    $pdo->exec($sql);
    echo "Table 'admin_phomi' created successfully.\n";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
?>
