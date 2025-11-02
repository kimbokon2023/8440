-- 발주사항(멀티행) 컬럼 추가
-- daon_orders 테이블에 발주사항을 JSON TEXT 형태로 저장하는 컬럼을 추가합니다.

ALTER TABLE daon_orders 
ADD COLUMN order_items TEXT NULL COMMENT '발주사항(JSON 형태)' AFTER note;

-- 실행 확인
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE, 
    COLUMN_COMMENT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'daon_orders' 
AND COLUMN_NAME = 'order_items';

