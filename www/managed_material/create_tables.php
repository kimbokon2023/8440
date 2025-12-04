<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

$pdo = db_connect();

$sql_master = "CREATE TABLE IF NOT EXISTS `material_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registdate` date DEFAULT NULL COMMENT '등록일자',
  `item_name` varchar(255) DEFAULT NULL COMMENT '품명',
  `spec` varchar(255) DEFAULT NULL COMMENT '규격',
  `price` decimal(15,2) DEFAULT 0.00 COMMENT '가격',
  `unit` varchar(50) DEFAULT NULL COMMENT '단위',
  `remarks` text DEFAULT NULL COMMENT '비고',
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT '삭제여부',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='관리대상 자재 마스터';";

$sql_transaction = "CREATE TABLE IF NOT EXISTS `material_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL COMMENT '자재 ID',
  `transaction_type` enum('in','out') NOT NULL COMMENT '입고/출고',
  `transaction_date` date DEFAULT NULL COMMENT '기준일',
  `quantity` int(11) DEFAULT 0 COMMENT '수량',
  `remarks` text DEFAULT NULL COMMENT '비고',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_material_id` (`material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='관리대상 자재 입출고 내역';";

try {
    $pdo->exec($sql_master);
    echo "Table 'material_master' created or already exists.<br>";
    
    $pdo->exec($sql_transaction);
    echo "Table 'material_transactions' created or already exists.<br>";
    
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>
