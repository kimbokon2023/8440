-- estimates 테이블의 phone 컬럼을 reference로 변경
-- 기존 데이터는 그대로 유지됩니다.

-- 1단계: phone 컬럼을 reference로 이름 변경
ALTER TABLE `estimates` 
CHANGE COLUMN `phone` `reference` VARCHAR(50) DEFAULT NULL COMMENT '참조';

-- 변경 확인
-- SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
-- AND TABLE_NAME = 'estimates' 
-- AND COLUMN_NAME IN ('reference', 'project_site');
