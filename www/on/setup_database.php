<?php
/**
 * 다온텍 데이터베이스 초기 설정 스크립트
 * 브라우저에서 한번만 실행하세요
 */

require_once __DIR__ . '/../bootstrap.php';

// 보안을 위해 특정 IP나 조건에서만 실행되도록 제한 가능
// if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
//     die('Access denied');
// }

echo "<!DOCTYPE html>";
echo "<html lang='ko'>";
echo "<head><meta charset='utf-8'><title>다온텍 DB 설정</title>";
echo "<style>body{font-family:sans-serif;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";
echo "</head><body>";
echo "<h1>다온텍 데이터베이스 초기 설정</h1>";

try {
    // 1. daon_member 테이블 생성
    echo "<h2>1. daon_member 테이블 생성</h2>";
    $sql = "CREATE TABLE IF NOT EXISTS `daon_member` (
      `id` varchar(50) NOT NULL COMMENT '아이디',
      `pass` varchar(255) NOT NULL COMMENT '비밀번호',
      `name` varchar(100) NOT NULL COMMENT '이름',
      `nick` varchar(100) DEFAULT NULL COMMENT '닉네임',
      `level` int(11) DEFAULT '10' COMMENT '권한레벨(1:최고관리자, 5:관리자, 10:일반)',
      `part` varchar(50) DEFAULT NULL COMMENT '부서',
      `position` varchar(50) DEFAULT NULL COMMENT '직급',
      `hp` varchar(20) DEFAULT NULL COMMENT '핸드폰번호',
      `email` varchar(100) DEFAULT NULL COMMENT '이메일',
      `company` varchar(100) DEFAULT 'daon' COMMENT '회사코드',
      `status` enum('active','inactive','pending') DEFAULT 'active' COMMENT '상태',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
      `last_login` datetime DEFAULT NULL COMMENT '마지막로그인',
      PRIMARY KEY (`id`),
      KEY `idx_level` (`level`),
      KEY `idx_status` (`status`),
      KEY `idx_company` (`company`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='다온텍 회원'";
    $pdo->exec($sql);
    echo "<p class='success'>✓ daon_member 테이블 생성 완료</p>";

    // 2. 기본 계정 추가
    echo "<h2>2. 기본 관리자 계정 추가</h2>";
    $checkSql = "SELECT COUNT(*) FROM daon_member WHERE id = 'admin'";
    $count = $pdo->query($checkSql)->fetchColumn();

    if ($count == 0) {
        $insertSql = "INSERT INTO daon_member (id, pass, name, nick, level, part, position, company, status) VALUES
        ('admin', 'admin1234', '관리자', '관리자', 1, '관리부', '관리자', 'daon', 'active'),
        ('daon', 'daon1234', '다온텍', '다온', 5, '영업부', '담당자', 'daon', 'active')";
        $pdo->exec($insertSql);
        echo "<p class='success'>✓ 기본 계정 추가 완료 (admin/admin1234, daon/daon1234)</p>";
    } else {
        echo "<p class='info'>→ 기본 계정이 이미 존재합니다</p>";
    }

    // 3. 로그인 로그 테이블
    echo "<h2>3. daon_login_logs 테이블 생성</h2>";
    $sql = "CREATE TABLE IF NOT EXISTS `daon_login_logs` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` varchar(50) NOT NULL COMMENT '사용자ID',
      `login_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '로그인시간',
      `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP주소',
      `user_agent` varchar(500) DEFAULT NULL COMMENT '브라우저정보',
      PRIMARY KEY (`id`),
      KEY `idx_user_id` (`user_id`),
      KEY `idx_login_time` (`login_time`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='다온텍 로그인 로그'";
    $pdo->exec($sql);
    echo "<p class='success'>✓ daon_login_logs 테이블 생성 완료</p>";

    // 4. 거래처 테이블
    echo "<h2>4. daon_customers 테이블 생성</h2>";
    $sql = "CREATE TABLE IF NOT EXISTS `daon_customers` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `company_name` varchar(255) NOT NULL COMMENT '회사명',
      `business_number` varchar(20) DEFAULT NULL COMMENT '사업자번호',
      `ceo_name` varchar(100) DEFAULT NULL COMMENT '대표자명',
      `address` varchar(500) DEFAULT NULL COMMENT '주소',
      `tel` varchar(20) DEFAULT NULL COMMENT '전화번호',
      `fax` varchar(20) DEFAULT NULL COMMENT '팩스번호',
      `email` varchar(100) DEFAULT NULL COMMENT '이메일',
      `manager_name` varchar(100) DEFAULT NULL COMMENT '담당자명',
      `manager_tel` varchar(20) DEFAULT NULL COMMENT '담당자연락처',
      `note` text COMMENT '비고',
      `status` enum('active','inactive') DEFAULT 'active' COMMENT '거래처상태',
      `created_by` varchar(50) NOT NULL COMMENT '등록자ID',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
      PRIMARY KEY (`id`),
      KEY `idx_company_name` (`company_name`),
      KEY `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='거래처 정보'";
    $pdo->exec($sql);
    echo "<p class='success'>✓ daon_customers 테이블 생성 완료</p>";

    // 5. 제품 테이블
    echo "<h2>5. daon_products 테이블 생성</h2>";
    $sql = "CREATE TABLE IF NOT EXISTS `daon_products` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `product_code` varchar(50) DEFAULT NULL COMMENT '제품코드',
      `product_name` varchar(255) NOT NULL COMMENT '제품명',
      `product_type` varchar(100) NOT NULL COMMENT '제품구분',
      `spec` varchar(500) DEFAULT NULL COMMENT '규격',
      `unit` varchar(20) DEFAULT 'EA' COMMENT '단위',
      `standard_price` decimal(15,2) DEFAULT NULL COMMENT '표준단가',
      `description` text COMMENT '제품설명',
      `image_path` varchar(500) DEFAULT NULL COMMENT '제품이미지경로',
      `status` enum('active','inactive') DEFAULT 'active' COMMENT '제품상태',
      `created_by` varchar(50) NOT NULL COMMENT '등록자ID',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
      PRIMARY KEY (`id`),
      UNIQUE KEY `unique_product_code` (`product_code`),
      KEY `idx_product_type` (`product_type`),
      KEY `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='제품 정보'";
    $pdo->exec($sql);
    echo "<p class='success'>✓ daon_products 테이블 생성 완료</p>";

    // 6. 발주 테이블
    echo "<h2>6. daon_orders 테이블 생성</h2>";
    $sql = "CREATE TABLE IF NOT EXISTS `daon_orders` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `order_number` varchar(50) NOT NULL COMMENT '발주번호',
      `order_date` date NOT NULL COMMENT '발주일자',
      `delivery_date` date DEFAULT NULL COMMENT '납품요청일',
      `customer_id` int(11) NOT NULL COMMENT '거래처ID',
      `product_id` int(11) DEFAULT NULL COMMENT '제품ID',
      `product_name` varchar(255) NOT NULL COMMENT '제품명',
      `product_type` varchar(100) DEFAULT NULL COMMENT '제품구분',
      `spec` varchar(500) DEFAULT NULL COMMENT '규격',
      `quantity` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '수량',
      `unit` varchar(20) DEFAULT 'EA' COMMENT '단위',
      `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '단가',
      `total_price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '총액',
      `vat_included` tinyint(1) DEFAULT '1' COMMENT '부가세포함여부',
      `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending' COMMENT '발주상태',
      `priority` enum('normal','high','urgent') DEFAULT 'normal' COMMENT '우선순위',
      `delivery_address` varchar(500) DEFAULT NULL COMMENT '납품주소',
      `note` text COMMENT '비고',
      `created_by` varchar(50) NOT NULL COMMENT '등록자ID',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
      PRIMARY KEY (`id`),
      UNIQUE KEY `unique_order_number` (`order_number`),
      KEY `idx_order_date` (`order_date`),
      KEY `idx_customer_id` (`customer_id`),
      KEY `idx_status` (`status`),
      KEY `idx_priority` (`priority`),
      CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `daon_customers` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='발주 정보'";
    $pdo->exec($sql);
    echo "<p class='success'>✓ daon_orders 테이블 생성 완료</p>";

    // 7. 샘플 데이터 추가
    echo "<h2>7. 샘플 데이터 추가</h2>";
    $checkCustomer = $pdo->query("SELECT COUNT(*) FROM daon_customers")->fetchColumn();
    if ($checkCustomer == 0) {
        $sql = "INSERT INTO daon_customers (company_name, business_number, ceo_name, tel, manager_name, manager_tel, created_by) VALUES
        ('(주)샘플광고', '123-45-67890', '홍길동', '02-1234-5678', '김담당', '010-1234-5678', 'admin'),
        ('다온기업', '987-65-43210', '이사장', '031-987-6543', '박매니저', '010-9876-5432', 'admin')";
        $pdo->exec($sql);
        echo "<p class='success'>✓ 샘플 거래처 추가 완료</p>";
    }

    $checkProduct = $pdo->query("SELECT COUNT(*) FROM daon_products")->fetchColumn();
    if ($checkProduct == 0) {
        $sql = "INSERT INTO daon_products (product_code, product_name, product_type, spec, unit, standard_price, created_by) VALUES
        ('ACR-001', '투명 아크릴판', '아크릴', '1200x600x3T', 'EA', 25000.00, 'admin'),
        ('LED-001', 'LED 바 (백색)', 'LED바', '1M', 'EA', 15000.00, 'admin'),
        ('LED-002', 'LED 바 (RGB)', 'LED바', '1M', 'EA', 20000.00, 'admin'),
        ('ACR-002', '컬러 아크릴판', '아크릴', '1200x600x5T', 'EA', 35000.00, 'admin')";
        $pdo->exec($sql);
        echo "<p class='success'>✓ 샘플 제품 추가 완료</p>";
    }

    echo "<hr>";
    echo "<h2 style='color:green;'>🎉 데이터베이스 설정 완료!</h2>";
    echo "<p>이제 <a href='login/login_form.php'>로그인 페이지</a>로 이동하여 로그인하세요.</p>";
    echo "<p><strong>기본 계정:</strong> admin / admin1234</p>";
    echo "<p style='color:red;'><strong>보안:</strong> 설정 완료 후 이 파일(setup_database.php)을 삭제하세요!</p>";

} catch (PDOException $e) {
    echo "<p class='error'>❌ 오류 발생: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>
