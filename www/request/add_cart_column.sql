-- eworks 테이블에 cart 컬럼 추가
-- 구매카트로 이동한 항목을 기록하기 위한 컬럼

ALTER TABLE eworks 
ADD COLUMN IF NOT EXISTS `cart` TINYINT(1) DEFAULT 0 COMMENT '구매카트 여부 (0: 미담기, 1: 담김)' 
AFTER `is_deleted`;

-- 인덱스 추가 (성능 향상)
CREATE INDEX IF NOT EXISTS `idx_cart` ON eworks(`cart`);

