CREATE TABLE `phomi_account` (
  `num` INT(11) NOT NULL AUTO_INCREMENT COMMENT '고유 ID',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성시간',
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정시간',
  `is_deleted` VARCHAR(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT '삭제여부 (Y/N)',

  -- 기본 정보
  `record_date` DATE NOT NULL COMMENT '날짜',
  `location` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '지점 (본사/무상지원 등)',
  `product` VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '품목',
  `code` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '코드',
  `width` INT DEFAULT NULL COMMENT '가로',
  `depth` INT DEFAULT NULL COMMENT '세로',
  `thickness` DECIMAL(5,2) DEFAULT NULL COMMENT '헤베',
  `quantity` INT DEFAULT NULL COMMENT '수량',
  `total_area` DECIMAL(10,2) DEFAULT NULL COMMENT '총헤베',

  -- 미래매입
  `purchase_unit_price` INT DEFAULT NULL COMMENT '매입 단가',
  `purchase_amount` INT DEFAULT NULL COMMENT '매입 금액',
  `purchase_total` INT DEFAULT NULL COMMENT '총 매입금액',

  -- 본사잔액
  `head_balance` INT DEFAULT NULL COMMENT '본사잔액',

  -- 대리점매출
  `dealer_unit_price` INT DEFAULT NULL COMMENT '대리점 단가',
  `dealer_amount` INT DEFAULT NULL COMMENT '대리점 금액',
  `dealer_total` INT DEFAULT NULL COMMENT '대리점 총매출금액',

  -- 업체매출
  `company_unit_price` INT DEFAULT NULL COMMENT '업체 단가',
  `company_amount` INT DEFAULT NULL COMMENT '업체 매출금액',
  `tax_invoice_amount` INT DEFAULT NULL COMMENT '세금계산서 금액',

  -- 기타
  `tax_diff` INT DEFAULT NULL COMMENT '계산서 차액',
  `dealer_fee` INT DEFAULT NULL COMMENT '대리점 수수료',
  `is_paid` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '입금 여부',
  `note` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '비고',

  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='매입/매출 관리 테이블';
