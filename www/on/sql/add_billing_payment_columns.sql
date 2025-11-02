-- 청구일, 입금일 컬럼 추가
-- daon_orders 테이블에 청구일과 입금일 컬럼을 추가합니다.

ALTER TABLE daon_orders 
ADD COLUMN billing_date DATE NULL COMMENT '청구일' AFTER delivery_date,
ADD COLUMN payment_date DATE NULL COMMENT '입금일' AFTER billing_date;

-- 인덱스 추가 (검색 성능 향상)
CREATE INDEX idx_billing_date ON daon_orders(billing_date);
CREATE INDEX idx_payment_date ON daon_orders(payment_date);

-- 실행 확인
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE, 
    COLUMN_COMMENT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'daon_orders' 
AND COLUMN_NAME IN ('billing_date', 'payment_date');

