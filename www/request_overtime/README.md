# 연장근무(잔업/특근) 사전승인 신청 시스템

## 개요
직원들의 연장근무(잔업/특근)에 대한 사전승인을 전자결재 시스템과 연동하여 관리하는 시스템입니다.

## 주요 기능
- **연장근무 신청**: 작업일자, 근무유형, 시간, 사유 입력
- **CRUD 작업**: 신청, 수정, 삭제, 조회
- **전자결재 연동**: eworks 테이블과 연동하여 결재 프로세스 진행
- **모달 기반 UI**: 팝업 대신 모달창을 사용한 사용자 친화적 인터페이스

## 파일 구조
```
request_overtime/
├── index.php              # 메인 페이지 (리스트 + 모달)
├── process.php            # CRUD 백엔드 처리
├── check_columns.sql      # DB 컬럼 확인 및 추가 스크립트
└── README.md              # 이 파일
```

## 데이터베이스 구조

### eworks 테이블 활용
기존 eworks 테이블의 컬럼을 재사용하여 별도 테이블 생성 없이 구현:

#### 신규 컬럼 (추가 필요)
- `ot_type` VARCHAR(50) - 연장근무 유형 (잔업/특근)
- `ot_start_time` TIME - 연장근무 시작시간
- `ot_end_time` TIME - 연장근무 종료시간

#### 기존 컬럼 활용
- `al_askdatefrom` - 작업일자
- `al_usedday` - 연장근무 시간 (시간 단위)
- `al_content` - 신청사유
- `author` - 신청자명
- `author_id` - 신청자 ID
- `al_part` - 부서
- `status` - 결재 상태 (send/ing/end)
- `registdate` - 신청일
- `is_deleted` - 삭제 플래그 (소프트 삭제)

## 설치 방법

### 1. 데이터베이스 컬럼 추가
```sql
-- check_columns.sql 실행
mysql -u [사용자명] -p [데이터베이스명] < check_columns.sql
```

또는 MySQL 클라이언트에서 직접 실행:
```sql
ALTER TABLE eworks ADD COLUMN IF NOT EXISTS ot_type VARCHAR(50) NULL;
ALTER TABLE eworks ADD COLUMN IF NOT EXISTS ot_start_time TIME NULL;
ALTER TABLE eworks ADD COLUMN IF NOT EXISTS ot_end_time TIME NULL;
```

### 2. 파일 업로드
`request_overtime` 폴더를 `/www/` 디렉토리에 업로드

### 3. 권한 설정
- 관리자 권한: 소현철, 김보곤, 최장중, 이경묵
- 일반 사용자: 본인 신청 내역만 조회/수정/삭제 가능

### 4. 메뉴 추가 (이미 완료됨)
`myheader.php`의 근태관리 메뉴에 이미 추가되어 있음:
```php
<a class="dropdown-item" href="<?=$root_dir?>/request_overtime/index.php">
    <i class="bi bi-clock-history"></i> 연장근무(잔업/특근) 사전승인 신청
</a>
```

## 사용 방법

### 연장근무 신청
1. 메인 화면에서 "신청" 버튼 클릭
2. 모달창에서 정보 입력:
   - 근무유형: 잔업 또는 특근 선택
   - 작업일자: 연장근무 날짜
   - 시작시간/종료시간: 연장근무 시간대
   - 연장시간: 자동 계산
   - 신청사유: 사유 선택
3. "저장" 버튼 클릭

### 연장근무 수정
1. 리스트에서 수정할 항목 클릭
2. 모달창에서 정보 수정
3. "수정" 버튼 클릭
- **주의**: 결재 진행중(status가 있는 경우)에는 수정 불가

### 연장근무 삭제
1. 리스트에서 삭제할 항목 클릭
2. 모달창에서 "삭제" 버튼 클릭
3. 확인 메시지에서 "확인" 클릭
- **주의**: 결재 진행중에는 삭제 불가

## 결재 프로세스
1. **결재상신 (send)**: 신청 시 자동으로 결재상신 상태로 설정
2. **결재중 (ing)**: 1차 결재 진행 중
3. **결재완료 (end)**: 최종 결재 완료

## 필터링 방식
`ot_type` 컬럼을 이용해 연장근무 데이터만 필터링:
```sql
WHERE ot_type IS NOT NULL
```

## API 엔드포인트 (process.php)

### POST /request_overtime/process.php
- **mode=insert**: 신청 등록
- **mode=update**: 신청 수정
- **mode=delete**: 신청 삭제 (소프트 삭제)
- **mode=load**: 신청 조회

## 주요 특징
1. **기존 테이블 활용**: 새로운 테이블 생성 없이 eworks 테이블 활용
2. **전자결재 연동**: 기존 전자결재 시스템과 완전 통합
3. **모달 기반 UI**: 사용자 경험 향상
4. **자동 계산**: 시간 입력 시 연장시간 자동 계산
5. **권한 관리**: 본인 작성 데이터만 수정/삭제 가능
6. **결재 상태 관리**: 결재 진행중 수정/삭제 방지

## 참고 시스템
- 연차 시스템 (`/annualleave/`)을 기반으로 설계
- 동일한 eworks 테이블 활용
- 유사한 UI/UX 패턴

## 개발 정보
- **개발일**: 2025-01-31
- **기반 시스템**: 연차 관리 시스템
- **데이터베이스**: MySQL (eworks 테이블)
- **프레임워크**: Bootstrap 5, jQuery
- **언어**: PHP 8.x, JavaScript

## 라이선스
내부 시스템 전용
