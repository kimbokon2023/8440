-- eworks 테이블에 연장근무 관련 컬럼 추가 (존재하지 않는 경우에만)

-- ot_type: 연장근무 유형 (잔업/특근)
ALTER TABLE eworks ADD COLUMN IF NOT EXISTS ot_type VARCHAR(50) NULL COMMENT '연장근무 유형 (잔업/특근)';

-- ot_start_time: 시작시간
ALTER TABLE eworks ADD COLUMN IF NOT EXISTS ot_start_time TIME NULL COMMENT '연장근무 시작시간';

-- ot_end_time: 종료시간
ALTER TABLE eworks ADD COLUMN IF NOT EXISTS ot_end_time TIME NULL COMMENT '연장근무 종료시간';

-- 기존 컬럼들 활용 (ALTER 불필요):
-- al_askdatefrom: 작업일자
-- al_usedday: 연장근무 시간
-- al_content: 사유
-- author: 신청자
-- author_id: 신청자 ID
-- al_part: 부서
-- status: 결재 상태
-- registdate: 등록일
-- is_deleted: 삭제 플래그

-- 인덱스 추가 (성능 향상)
CREATE INDEX IF NOT EXISTS idx_ot_type ON eworks(ot_type);
CREATE INDEX IF NOT EXISTS idx_al_askdatefrom ON eworks(al_askdatefrom);
