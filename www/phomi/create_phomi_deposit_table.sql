-- phomi_deposit 테이블 생성
-- 본사 예치금 관리용 테이블

CREATE TABLE IF NOT EXISTS `phomi_deposit` (
  `num` int(11) NOT NULL AUTO_INCREMENT COMMENT '고유번호',
  `deposit_date` date NOT NULL COMMENT '입금일',
  `deposit_amount` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT '입금액',
  `note` text COMMENT '비고',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
  `is_deleted` char(1) DEFAULT 'N' COMMENT '삭제여부 (Y/N)',
  PRIMARY KEY (`num`),
  KEY `idx_deposit_date` (`deposit_date`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='포미스톤 예치금 관리 테이블';

-- 샘플 데이터 삽입 (선택사항)
-- INSERT INTO `phomi_deposit` (`deposit_date`, `deposit_amount`, `note`) VALUES
-- ('2024-01-15', 1000000.00, '1월 예치금'),
-- ('2024-02-15', 1500000.00, '2월 예치금'),
-- ('2024-03-15', 2000000.00, '3월 예치금'); 