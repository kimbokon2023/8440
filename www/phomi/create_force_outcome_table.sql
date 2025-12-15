-- phomi_deposit 테이블에 force_outcome 컬럼 추가 SQL
-- 기존 테이블에 강제 지출 금액 컬럼을 추가합니다.

ALTER TABLE `phomi_deposit` 
ADD COLUMN `force_outcome` decimal(15,2) DEFAULT NULL COMMENT '강제 지출 금액 (VAT 포함)' 
AFTER `deposit_amount`;

-- 인덱스 추가 (선택사항 - 날짜별 조회 성능 향상)
ALTER TABLE `phomi_deposit` 
ADD INDEX `idx_deposit_date_force_outcome` (`deposit_date`, `force_outcome`);

