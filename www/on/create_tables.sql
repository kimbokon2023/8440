-- 다온텍 발주 시스템 데이터베이스 테이블 생성
-- 기존 mirae8440 DB에 daon_ 접두사로 테이블 생성
-- 아크릴, LED바 등의 제품 발주 관리 시스템

-- 1. 거래처 정보 테이블
CREATE TABLE IF NOT EXISTS `daon_customers` (
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
  `created_by` int(11) NOT NULL COMMENT '등록자ID',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
  PRIMARY KEY (`id`),
  KEY `idx_company_name` (`company_name`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='거래처 정보';

-- 2. 제품 정보 테이블
CREATE TABLE IF NOT EXISTS `daon_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(50) DEFAULT NULL COMMENT '제품코드',
  `product_name` varchar(255) NOT NULL COMMENT '제품명',
  `product_type` varchar(100) NOT NULL COMMENT '제품구분(아크릴/LED바/기타)',
  `spec` varchar(500) DEFAULT NULL COMMENT '규격',
  `unit` varchar(20) DEFAULT 'EA' COMMENT '단위',
  `standard_price` decimal(15,2) DEFAULT NULL COMMENT '표준단가',
  `description` text COMMENT '제품설명',
  `image_path` varchar(500) DEFAULT NULL COMMENT '제품이미지경로',
  `status` enum('active','inactive') DEFAULT 'active' COMMENT '제품상태',
  `created_by` int(11) NOT NULL COMMENT '등록자ID',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_product_code` (`product_code`),
  KEY `idx_product_type` (`product_type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='제품 정보';

-- 3. 발주 정보 테이블
CREATE TABLE IF NOT EXISTS `daon_orders` (
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
  `created_by` int(11) NOT NULL COMMENT '등록자ID',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_order_number` (`order_number`),
  KEY `idx_order_date` (`order_date`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `daon_customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='발주 정보';

-- 4. 발주 상세 항목 테이블 (여러 제품을 한번에 발주하는 경우)
CREATE TABLE IF NOT EXISTS `daon_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '발주ID',
  `product_id` int(11) DEFAULT NULL COMMENT '제품ID',
  `product_name` varchar(255) NOT NULL COMMENT '제품명',
  `spec` varchar(500) DEFAULT NULL COMMENT '규격',
  `quantity` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '수량',
  `unit` varchar(20) DEFAULT 'EA' COMMENT '단위',
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '단가',
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '총액',
  `note` text COMMENT '비고',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_product_id` (`product_id`),
  CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `daon_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='발주 상세 항목';

-- 5. 발주 첨부파일 테이블
CREATE TABLE IF NOT EXISTS `daon_order_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '발주ID',
  `file_name` varchar(255) NOT NULL COMMENT '파일명',
  `file_path` varchar(500) NOT NULL COMMENT '파일경로',
  `file_size` int(11) DEFAULT NULL COMMENT '파일크기(bytes)',
  `file_type` varchar(100) DEFAULT NULL COMMENT '파일타입',
  `uploaded_by` int(11) NOT NULL COMMENT '업로드자ID',
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '업로드일시',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  CONSTRAINT `fk_file_order` FOREIGN KEY (`order_id`) REFERENCES `daon_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='발주 첨부파일';

-- 6. 발주 이력 테이블 (상태 변경 이력 추적)
CREATE TABLE IF NOT EXISTS `daon_order_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '발주ID',
  `previous_status` varchar(50) DEFAULT NULL COMMENT '이전상태',
  `new_status` varchar(50) NOT NULL COMMENT '변경후상태',
  `comment` text COMMENT '변경사유',
  `changed_by` int(11) NOT NULL COMMENT '변경자ID',
  `changed_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '변경일시',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_changed_at` (`changed_at`),
  CONSTRAINT `fk_history_order` FOREIGN KEY (`order_id`) REFERENCES `daon_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='발주 이력';

-- 7. 납품 정보 테이블
CREATE TABLE IF NOT EXISTS `daon_deliveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '발주ID',
  `delivery_date` date NOT NULL COMMENT '납품일자',
  `delivery_quantity` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '납품수량',
  `delivery_address` varchar(500) DEFAULT NULL COMMENT '납품장소',
  `recipient_name` varchar(100) DEFAULT NULL COMMENT '수령인',
  `recipient_tel` varchar(20) DEFAULT NULL COMMENT '수령인연락처',
  `delivery_note` text COMMENT '납품비고',
  `status` enum('scheduled','completed','failed') DEFAULT 'scheduled' COMMENT '납품상태',
  `created_by` int(11) NOT NULL COMMENT '등록자ID',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_delivery_date` (`delivery_date`),
  CONSTRAINT `fk_delivery_order` FOREIGN KEY (`order_id`) REFERENCES `daon_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='납품 정보';

-- 샘플 데이터 삽입 (선택사항)

-- 샘플 거래처
INSERT INTO daon_customers (company_name, business_number, ceo_name, tel, manager_name, manager_tel, created_by)
VALUES
('(주)샘플광고', '123-45-67890', '홍길동', '02-1234-5678', '김담당', '010-1234-5678', 1),
('다온기업', '987-65-43210', '이사장', '031-987-6543', '박매니저', '010-9876-5432', 1);

-- 샘플 제품
INSERT INTO daon_products (product_code, product_name, product_type, spec, unit, standard_price, created_by)
VALUES
('ACR-001', '투명 아크릴판', '아크릴', '1200x600x3T', 'EA', 25000.00, 1),
('LED-001', 'LED 바 (백색)', 'LED바', '1M', 'EA', 15000.00, 1),
('LED-002', 'LED 바 (RGB)', 'LED바', '1M', 'EA', 20000.00, 1),
('ACR-002', '컬러 아크릴판', '아크릴', '1200x600x5T', 'EA', 35000.00, 1);

-- 샘플 발주
INSERT INTO daon_orders (order_number, order_date, delivery_date, customer_id, product_name, product_type, quantity, unit, unit_price, total_price, created_by)
VALUES
('ON-20250101-001', '2025-01-15', '2025-01-20', 1, '투명 아크릴판', '아크릴', 10, 'EA', 25000, 250000, 1),
('ON-20250101-002', '2025-01-16', '2025-01-22', 2, 'LED 바 (백색)', 'LED바', 20, 'EA', 15000, 300000, 1);
