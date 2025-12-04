-- estimates 테이블에 email 컬럼 추가
ALTER TABLE `estimates` ADD COLUMN `email` VARCHAR(100) DEFAULT NULL COMMENT '이메일';
