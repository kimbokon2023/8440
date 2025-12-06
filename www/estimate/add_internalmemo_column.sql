-- estimates 테이블에 internalmemo 컬럼 추가
ALTER TABLE `estimates` ADD COLUMN `internalmemo` TEXT DEFAULT NULL COMMENT '회사 내부 메모';
