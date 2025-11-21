-- orders 테이블에 프로젝트/현장명 컬럼 추가
-- 프로젝트/현장명을 저장하기 위한 컬럼

ALTER TABLE `orders` 
ADD COLUMN `project_site` VARCHAR(255) DEFAULT NULL COMMENT '프로젝트/현장명' 
AFTER `fax`;

-- 인덱스 추가 (검색 성능 향상)
ALTER TABLE `orders` 
ADD INDEX `idx_project_site` (`project_site`);

