# 구매발주서 관리 시스템

미래기업 ERP 시스템의 구매발주서 관리 모듈입니다.

## 📁 파일 구조

```
order/
├── create_table.sql        # 데이터베이스 테이블 생성 SQL
├── index.php              # 발주서 목록 페이지
├── write_form.php         # 발주서 작성/수정 폼
├── insert.php             # 발주서 저장 처리
├── view.php               # 발주서 상세보기
├── delete.php             # 발주서 삭제 처리 (Soft Delete)
├── 경리나라 발주서 양식.pdf
├── 경리나라 업체발송 양식.pdf
└── 경리나라발주화면 참고용 리스트.png
```

## 🗄️ 데이터베이스 구조

### `order` 테이블

| 필드명 | 타입 | 설명 |
|--------|------|------|
| id | INT(11) AUTO_INCREMENT | 기본키 |
| issue_date | DATE | 발행일 |
| supplier_name | VARCHAR(255) | 공급업체명 |
| item_specification | TEXT | 품목/규격 |
| supply_price | DECIMAL(15,2) | 공급가액 |
| tax_amount | DECIMAL(15,2) | 세액 |
| total_amount | DECIMAL(15,2) | 합계금액 |
| status | VARCHAR(50) | 상태 (draft/sent/completed) |
| responsible_company | VARCHAR(255) | 담당업체 |
| final_send_date | DATETIME | 최종 마감 발송일 |
| final_pass_send_date | DATETIME | 최종 파스 발송일 |
| photo_work_code | VARCHAR(255) | 포토앤드/작업코드 |
| created_at | DATETIME | 최초저장시간 |
| updated_at | DATETIME | 업데이트시간 |
| is_deleted | TINYINT(1) | 삭제여부 (0:정상, 1:삭제) |

## 🚀 설치 방법

1. 데이터베이스 테이블 생성:
   ```sql
   -- create_table.sql 파일의 내용을 실행
   ```

2. 필요한 권한 확인:
   - 세션 레벨 5 이하의 사용자만 접근 가능

## 💡 기능

### 1. 목록 페이지 (index.php)
- ✅ 발주서 목록 표시 (페이지네이션)
- ✅ 검색 기능 (공급업체명, 발행일 기간)
- ✅ 정렬 기능 (발행일, 생성일 기준)
- ✅ 상태별 필터링
- ✅ 체크박스 전체 선택/해제
- ✅ 삭제된 항목 제외 (is_deleted = 0)

### 2. 작성/수정 폼 (write_form.php)
- ✅ 발주서 신규 작성
- ✅ 기존 발주서 수정
- ✅ 데이터 복사 기능 (mode=copy)
- ✅ 부가세 자동 계산 (공급가액의 10%)
- ✅ 실시간 합계 계산
- ✅ 필수 필드 유효성 검사

### 3. 상세보기 (view.php)
- ✅ 발주서 상세 정보 표시
- ✅ 수정/복사/삭제 버튼
- ✅ 키보드 단축키 지원
- ✅ 메타데이터 표시 (등록일, 수정일)

### 4. 데이터 처리
- ✅ **insert.php**: 신규 등록 및 수정 처리
- ✅ **delete.php**: Soft Delete (is_deleted = 1)
- ✅ PDO 사용한 안전한 SQL 처리
- ✅ 트랜잭션 및 오류 처리

## 🎨 UI/UX 특징

- **반응형 디자인**: Bootstrap 5 기반
- **직관적 인터페이스**: 색상으로 구분된 상태 표시
- **키보드 단축키**: Ctrl+E(수정), Ctrl+Shift+C(복사), ESC(목록)
- **실시간 계산**: 자동 부가세 및 합계 계산
- **검색 최적화**: 엔터키 검색, 초기화 버튼

## 🔒 보안 기능

- **권한 체크**: 세션 레벨 검증
- **SQL 인젝션 방지**: PDO prepared statements
- **XSS 방지**: htmlspecialchars() 사용
- **CSRF 보호**: 폼 기반 토큰 (필요시 추가 가능)
- **Soft Delete**: 실제 데이터 삭제 대신 플래그 설정

## 📊 상태 관리

| 상태 | 값 | 설명 |
|------|-----|------|
| 임시저장 | draft | 작성 중인 발주서 |
| 발송완료 | sent | 공급업체에 발송 완료 |
| 완료 | completed | 처리 완료된 발주서 |

## 🔧 커스터마이징

### 추가 가능한 기능
- PDF 생성/출력
- 이메일 발송
- 파일 첨부
- 승인 워크플로우
- 댓글/히스토리 기능

### 설정 변경
- `$per_page`: 페이지당 표시 항목 수 (기본값: 20)
- 부가세율 변경: JavaScript의 0.1 값 수정
- 상태 옵션 추가: `$valid_statuses` 배열 수정

## 🚨 주의사항

1. **데이터베이스 백업**: 삭제는 Soft Delete이지만 정기 백업 권장
2. **권한 관리**: 세션 레벨 5 이하만 접근 가능
3. **파일 업로드**: 현재 파일 첨부 기능 없음 (필요시 추가 개발)
4. **브라우저 호환성**: 모던 브라우저 (IE 11+ 권장)

## 📝 개발 로그

- **2025-09-24**: 초기 시스템 구축 완료
  - 기본 CRUD 기능 구현
  - 검색/페이지네이션 추가
  - Soft Delete 적용
  - 반응형 UI 구현