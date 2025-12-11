-- estimates 테이블에 author 컬럼 추가
-- 작성자 ID를 저장하는 컬럼

ALTER TABLE `estimates` 
ADD COLUMN `author` VARCHAR(50) DEFAULT NULL COMMENT '작성자 이름' 
AFTER `created_at`;

-- 인덱스 추가 (선택사항)
ALTER TABLE `estimates` 
ADD INDEX `idx_author` (`author`);

