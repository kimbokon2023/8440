-- orders 테이블에 author 컬럼 추가
-- 작성자 이름 저장용

-- 1. 컬럼 추가
ALTER TABLE `orders` 
ADD COLUMN `author` VARCHAR(50) DEFAULT NULL COMMENT '작성자 이름' 
AFTER `created_at`;

-- 2. 인덱스 추가 (선택사항, 검색 성능 향상)
CREATE INDEX `idx_author` ON `orders` (`author`);

