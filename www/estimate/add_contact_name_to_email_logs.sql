-- estimate_email_logs 테이블에 거래처명 컬럼 추가
-- 거래처명을 직접 저장하여 조회 성능 향상

-- 1. 컬럼 추가 (이미 존재하면 오류 발생하므로 주의)
ALTER TABLE `estimate_email_logs` 
ADD COLUMN `contact_name` VARCHAR(255) DEFAULT NULL COMMENT '거래처명' 
AFTER `estimate_id`;

-- 2. 기존 데이터 업데이트 (estimates 테이블에서 거래처명 가져오기)
UPDATE `estimate_email_logs` l
INNER JOIN `estimates` e ON l.estimate_id = e.id
SET l.contact_name = COALESCE(e.contact_name, '')
WHERE l.contact_name IS NULL;

-- 3. 인덱스 추가 (선택사항, 검색 성능 향상)
ALTER TABLE `estimate_email_logs` 
ADD INDEX `idx_contact_name` (`contact_name`);

