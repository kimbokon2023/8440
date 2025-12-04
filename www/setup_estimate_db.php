<?php
require_once __DIR__ . '/bootstrap.php';

// Force local environment config for CLI
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'mirae8440';

$dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    $DB = $db_name;

    // 1. estimates table
    $sql = "CREATE TABLE IF NOT EXISTS `estimates` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `estimate_no` VARCHAR(50) DEFAULT NULL COMMENT '견적서 번호',
        `issue_date` DATE NOT NULL COMMENT '견적일자',
        `supplier_code` VARCHAR(50) DEFAULT NULL COMMENT '등록번호',
        `supplier_name` VARCHAR(255) NOT NULL COMMENT '상호',
        `supplier_address` TEXT DEFAULT NULL COMMENT '주소',
        `business_type` VARCHAR(100) DEFAULT NULL COMMENT '업태',
        `business_item` VARCHAR(100) DEFAULT NULL COMMENT '종목',
        `supplier_phone` VARCHAR(50) DEFAULT NULL COMMENT '전화번호',
        `supplier_fax` VARCHAR(50) DEFAULT NULL COMMENT '팩스번호',
        `contact_name` VARCHAR(100) DEFAULT NULL COMMENT '거래처명',
        `phone` VARCHAR(50) DEFAULT NULL COMMENT '전화번호',
        `fax` VARCHAR(50) DEFAULT NULL COMMENT '팩스번호',
        `estimate_items` TEXT DEFAULT NULL COMMENT '견적 품목 (JSON 형태)',
        `subtotal` DECIMAL(15,2) DEFAULT 0.00 COMMENT '합계',
        `delivery_date` DATE DEFAULT NULL COMMENT '납기일자',
        `delivery_location` VARCHAR(255) DEFAULT NULL COMMENT '납품장소',
        `payment_terms` VARCHAR(255) DEFAULT NULL COMMENT '결제조건',
        `note` TEXT DEFAULT NULL COMMENT '비고',
        `status` VARCHAR(50) DEFAULT 'draft' COMMENT '상태 (draft, sent, completed)',
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '최초저장시간',
        `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '업데이트시간',
        `is_deleted` TINYINT(1) DEFAULT 0 COMMENT '삭제여부 (0:정상, 1:삭제)',
        `customer_id` INT(11) DEFAULT NULL COMMENT '거래처 ID',
        `project_site` VARCHAR(255) DEFAULT NULL COMMENT '프로젝트/현장',
        `valid_date` DATE DEFAULT NULL COMMENT '유효일자',
        PRIMARY KEY (`id`),
        KEY `idx_estimate_no` (`estimate_no`),
        KEY `idx_issue_date` (`issue_date`),
        KEY `idx_supplier_name` (`supplier_name`),
        KEY `idx_status` (`status`),
        KEY `idx_is_deleted` (`is_deleted`),
        KEY `idx_created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='견적서 테이블';";
    $pdo->exec($sql);
    echo "Table 'estimates' created successfully.\n";

    // 2. estimate_customer table
    $sql = "CREATE TABLE IF NOT EXISTS `estimate_customer` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `classification` VARCHAR(50) DEFAULT '사업자' COMMENT '구분',
        `trade_name` VARCHAR(100) DEFAULT NULL COMMENT '상호',
        `company_name` VARCHAR(100) NOT NULL COMMENT '거래처명',
        `registration_number` VARCHAR(50) DEFAULT NULL COMMENT '등록번호',
        `representative_name` VARCHAR(50) DEFAULT NULL COMMENT '대표자명',
        `phone_number` VARCHAR(50) DEFAULT NULL COMMENT '전화번호',
        `mobile_number` VARCHAR(50) DEFAULT NULL COMMENT '휴대전화',
        `fax_number` VARCHAR(50) DEFAULT NULL COMMENT '팩스번호',
        `business_type` VARCHAR(100) DEFAULT NULL COMMENT '업태',
        `business_category` VARCHAR(100) DEFAULT NULL COMMENT '종목',
        `remarks` TEXT DEFAULT NULL COMMENT '비고',
        `address` VARCHAR(255) DEFAULT NULL COMMENT '주소',
        `business_registration_number` VARCHAR(50) DEFAULT NULL COMMENT '사업자등록번호',
        `registration_date` DATE DEFAULT NULL COMMENT '등록일',
        `last_modified_date` DATETIME DEFAULT NULL COMMENT '최종수정일',
        `is_sales_customer` CHAR(1) DEFAULT 'N' COMMENT '매출거래처여부',
        `is_purchase_customer` CHAR(1) DEFAULT 'N' COMMENT '매입거래처여부',
        `is_other_customer` CHAR(1) DEFAULT 'N' COMMENT '기타거래처여부',
        `bank_name` VARCHAR(50) DEFAULT NULL COMMENT '은행명',
        `account_number` VARCHAR(50) DEFAULT NULL COMMENT '계좌번호',
        `account_holder` VARCHAR(50) DEFAULT NULL COMMENT '예금주',
        `my_account_id` INT(11) DEFAULT 0 COMMENT '자사계좌ID',
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
        `is_deleted` CHAR(1) DEFAULT 'N' COMMENT '삭제여부',
        PRIMARY KEY (`id`),
        KEY `idx_company_name` (`company_name`),
        KEY `idx_is_deleted` (`is_deleted`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='견적 거래처 테이블';";
    $pdo->exec($sql);
    echo "Table 'estimate_customer' created successfully.\n";

    // 3. estimate_customer_contact table
    $sql = "CREATE TABLE IF NOT EXISTS `estimate_customer_contact` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `customer_id` INT(11) NOT NULL COMMENT '거래처 ID',
        `contact_name` VARCHAR(50) NOT NULL COMMENT '담당자명',
        `contact_phone` VARCHAR(50) DEFAULT NULL COMMENT '연락처',
        `contact_email` VARCHAR(100) DEFAULT NULL COMMENT '이메일',
        `contact_remarks` TEXT DEFAULT NULL COMMENT '비고',
        `is_invoice_contact` CHAR(1) DEFAULT 'N' COMMENT '계산서담당자여부',
        `position_department` VARCHAR(100) DEFAULT NULL COMMENT '직위/부서',
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
        `is_deleted` CHAR(1) DEFAULT 'N' COMMENT '삭제여부',
        PRIMARY KEY (`id`),
        KEY `idx_customer_id` (`customer_id`),
        KEY `idx_is_deleted` (`is_deleted`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='견적 거래처 담당자 테이블';";
    $pdo->exec($sql);
    echo "Table 'estimate_customer_contact' created successfully.\n";

    // 4. estimate_email_logs table
    $sql = "CREATE TABLE IF NOT EXISTS `estimate_email_logs` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `estimate_id` INT(11) DEFAULT NULL COMMENT '견적서 ID',
        `sender_email` VARCHAR(255) NOT NULL COMMENT '발신자 이메일',
        `recipient_email` VARCHAR(255) NOT NULL COMMENT '수신자 이메일',
        `subject` VARCHAR(255) NOT NULL COMMENT '제목',
        `content` TEXT NOT NULL COMMENT '내용',
        `status` VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT '전송 상태 (pending, success, fail)',
        `error_message` TEXT DEFAULT NULL COMMENT '에러 메시지',
        `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '발송 일시',
        PRIMARY KEY (`id`),
        KEY `idx_estimate_id` (`estimate_id`),
        KEY `idx_sent_at` (`sent_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='견적서 이메일 발송 로그';";
    $pdo->exec($sql);
    echo "Table 'estimate_email_logs' created successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
