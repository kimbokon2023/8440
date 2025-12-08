-- estimates 테이블에 PDF 안내 문구 컬럼 추가
-- PDF 생성 시 표시되는 안내 문구를 사용자가 직접 수정할 수 있도록 함

ALTER TABLE `estimates` 
ADD COLUMN `disclaimer_text` TEXT DEFAULT NULL COMMENT 'PDF 안내 문구 (면책 조항)';

-- 기존 데이터에 기본 안내 문구 설정 (선택사항)
-- UPDATE `estimates` 
-- SET `disclaimer_text` = '1. 상기 견적의 금액은 이후 확정 시 금액이 변동될 수 있습니다.
-- 2. 제품 현장 도착 후 즉시 현장 검수를 원칙으로 하며, 반품·교환 시 추가 운송비가 발생할 수 있습니다.
-- 3. 견적서 내역 검토는 구매자의 의무이며, 미검토로 인한 배송 오류에 대한 책임은 구매자에게 있습니다.
-- 4. 본 견적서로 계약서를 갈음하며, 납기 확정 시 견적 내용에 동의하는 것으로 간주합니다.'
-- WHERE `disclaimer_text` IS NULL;

