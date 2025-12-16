-- phomi_deposit 테이블에 force_outcome 컬럼 추가 SQL
-- 기존 테이블에 강제 지출 금액 컬럼을 추가합니다.
-- varchar 타입으로 설정하여 숫자와 텍스트('매장' 등) 모두 저장 가능

ALTER TABLE `phomi_deposit` 
ADD COLUMN `force_outcome` varchar(50) DEFAULT NULL COMMENT '강제 지출 금액 (VAT 포함, 숫자 또는 텍스트)' 
AFTER `deposit_amount`;

-- 기존에 decimal 타입으로 생성된 경우 변경하는 SQL
-- ALTER TABLE `phomi_deposit` 
-- MODIFY COLUMN `force_outcome` varchar(50) DEFAULT NULL COMMENT '강제 지출 금액 (VAT 포함, 숫자 또는 텍스트)';

-- 인덱스 추가 (선택사항 - 날짜별 조회 성능 향상)
ALTER TABLE `phomi_deposit` 
ADD INDEX `idx_deposit_date_force_outcome` (`deposit_date`, `force_outcome`);

