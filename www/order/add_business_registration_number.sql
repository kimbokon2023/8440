-- order 테이블에 사업자등록번호 컬럼 추가
-- 거래처의 사업자등록번호를 저장하기 위한 컬럼

ALTER TABLE `order` 
ADD COLUMN `business_registration_number` VARCHAR(20) DEFAULT NULL COMMENT '사업자등록번호' 
AFTER `contact_name`;

-- 인덱스 추가 (검색 성능 향상)
ALTER TABLE `order` 
ADD INDEX `idx_business_registration_number` (`business_registration_number`);

