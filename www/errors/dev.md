# 부적합 매칭 확인 (match_check.php) 개발 문서

## 개요
원자재 출고 불량 건(`steel`)과 부적합 보고서(`error`)를 연결(매칭)하여 관리하는 페이지입니다.

## 주요 로직

### 1. 데이터 조회 및 필터링

#### 매칭 대기 중인 원자재 (좌측 패널)
- **대상**: `steel` 테이블에서 `which='2'`(출고)이고 `bad_choice`가 존재하는 항목.
- **제외 조건**: `bad_choice`가 ['소재', '기타', '해당없음', '소장', '개발품', '업체', '운반중']에 포함되거나, 이미 `error_match` 테이블에 `steel_num`이 존재하는 경우 제외.
- **정렬**: 출고일(`outdate`) 내림차순.

#### 부적합 보고서 선택 (우측 패널)
- **대상**: `error` 테이블의 데이터.
- **제외 조건**: 이미 `error_match` 테이블에 `error_num`이 존재하는 보고서는 **자동으로 리스트에서 제외**됩니다.
  ```sql
  AND NOT EXISTS (SELECT 1 FROM mirae8440.error_match m WHERE m.error_num = mirae8440.error.num)
  ```
- **기능**:
  - **자세히 보기**: `viewReport(num)` 함수를 통해 `write_form.php` 팝업을 띄움.
  - **제외**: `excludeReport(num)` 함수를 통해 보고서를 매칭 대상에서 수동으로 제외.

### 2. 매칭 및 제외 처리 (Backend)

모든 요청은 `POST` 메서드로 처리되며 `mirae8440.error_match` 테이블을 사용합니다.

| 모드 (`mode`) | 설명 | 로직 |
| :--- | :--- | :--- |
| `link` | 자재-보고서 연결 | `steel_nums` 배열의 각 항목에 대해 `INSERT INTO error_match (steel_num, error_num) ...` 실행. |
| `exclude` | 자재 제외 | 선택한 자재들의 `error_num`을 `0`으로 설정하여 저장. (`INSERT ... VALUES (?, 0)`) |
| `exclude_report` | 보고서 제외 | 선택한 보고서의 `steel_num`을 `0`으로 설정하여 저장. (`INSERT ... VALUES (0, ?)`) |
| `unlink` | 해제 | `error_match` 테이블에서 해당 `id` 레코드를 삭제 (`DELETE`). |

### 3. 최근 매칭 현황 (하단 테이블)

- **조회**: `error_match` 테이블을 기준으로 `steel`과 `error` 테이블을 조인합니다.
- **조인 방식**: 보고서 제외(`steel_num=0`)와 자재 제외(`error_num=0`) 케이스를 모두 포함하기 위해 `LEFT JOIN`을 사용합니다.
  ```sql
  SELECT ... FROM error_match m
  LEFT JOIN steel s ON m.steel_num = s.num
  LEFT JOIN error e ON m.error_num = e.num
  ORDER BY m.created_at DESC
  ```
- **표시**:
  - 자재 제외 시: `error_num`은 `-`, 비고에 '자재제외' 표시.
  - 보고서 제외 시: 자재 정보는 `-`, 비고에 '보고서제외' 표시.

## 주요 스크립트 함수

- `viewReport(num, event)`: 부적합 보고서 상세 팝업 오픈.
- `excludeReport(num, event)`: 부적합 보고서 단독 제외 처리 (AJAX).
- `linkItems()`: 선택한 자재와 보고서를 매칭.
- `excludeItems()`: 선택한 자재를 매칭 제외.
- `showLinkedItems(errorNum)`: 보고서에 연결된 자재 목록을 모달로 표시.
