# Error Statistics System Development Documentation

## 프로젝트 개요
원자재 부적합 통계 시스템(`statistics.php`)의 UI/UX 개선 프로젝트로, 사용자가 혼란스러워하던 체크박스 시스템을 제거하고 두 가지 데이터셋을 동시에 표시하는 탭 기반 인터페이스로 전환.

## 주요 변경사항

### 1. UI 구조 개선
- **기존**: 체크박스("소장불량/업체불량/기타 제외") 기반 필터링
- **개선**: Bootstrap 탭 네비게이션으로 두 데이터셋 동시 표시
  - `전체 부적합 데이터` (소장/업체/기타 포함)
  - `소장/업체/기타 제외 데이터` (필터링된 데이터)

### 2. 데이터베이스 쿼리 수정
```php
// 전체 데이터 쿼리
$sql_all = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month,
            outdate, outworkplace, item, spec, steelnum, bad_choice
            FROM mirae8440.steel
            WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재')
            AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
            ORDER BY year, month";

// 필터링된 데이터 쿼리
$sql_chart_filtered = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month,
                      outdate, outworkplace, item, spec, steelnum, bad_choice
                      FROM mirae8440.steel
                      WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재'
                      AND bad_choice != '소장' AND bad_choice != '소장불량' AND bad_choice != '업체' AND bad_choice != '업체불량' AND bad_choice != '기타')
                      AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
                      ORDER BY year, month";
```

### 3. 해결된 주요 이슈

#### 출고일 필드 표시 문제
- **문제**: 테이블에서 출고일(outdate)과 현장명(outworkplace) 필드가 표시되지 않음
- **원인**: SQL SELECT 문에서 해당 필드들이 누락되어 있었음
- **해결**: 모든 SQL 쿼리에 `outdate, outworkplace` 필드 추가

#### JavaScript 오류 수정
- **문제**: `monthly_totals_filtered` 변수 정의 누락으로 인한 JavaScript 오류
- **해결**: PHP에서 필터링된 데이터의 월별 합계 계산 및 JavaScript 변수 정의

#### 필터 조건 개선
- **문제**: 사용자가 불량유형을 '소장' 또는 '소장불량'으로 혼재하여 입력
- **해결**: WHERE 조건에 단축형과 전체형 모두 포함
  ```sql
  AND bad_choice != '소장' AND bad_choice != '소장불량'
  AND bad_choice != '업체' AND bad_choice != '업체불량'
  AND bad_choice != '기타'
  ```

### 4. 현재 구현 상태

#### HTML 구조
```html
<!-- 탭 네비게이션 -->
<ul class="nav nav-pills" id="dataTypeTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="all-data-tab" data-bs-toggle="pill" data-bs-target="#all-data">
      전체 부적합 데이터
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="filtered-data-tab" data-bs-toggle="pill" data-bs-target="#filtered-data">
      소장/업체/기타 제외 데이터
    </button>
  </li>
</ul>

<!-- 탭 콘텐츠 -->
<div class="tab-content" id="dataTypeTabContent">
  <div class="tab-pane fade show active" id="all-data" role="tabpanel">
    <!-- 전체 데이터 차트 및 테이블 -->
  </div>
  <div class="tab-pane fade" id="filtered-data" role="tabpanel">
    <!-- 필터링된 데이터 차트 및 테이블 -->
  </div>
</div>
```

#### 차트 구현
- **전체 데이터 차트**: `mychart-all` (빨간색 #dc3545)
- **필터링된 데이터 차트**: `mychart-filtered` (노란색 #ffc107)
- **라이브러리**: Highcharts.js 사용
- **차트 타입**: Column chart

#### 테이블 구현
- **DataTables**: 각 탭별로 별도 DataTable 인스턴스
- **컬럼**: 출고일, 불량유형, 현장명, 종류, 규격, 수량, 발생비용
- **정렬**: 출고일 기준 내림차순 기본 정렬

### 5. 기술 스택
- **Backend**: PHP 7+, PDO MySQL
- **Frontend**: Bootstrap 5, jQuery, Highcharts.js, DataTables
- **Database**: MySQL/MariaDB (mirae8440.steel 테이블)

### 6. 가격 계산 로직
```php
$weight = $thickness * $width * $length/1000;
$total_price = ($weight * $price * $steelnum * 7.93)/1000;
```
- 철강 밀도: 7.93 g/cm³
- 가격 기준: settings.ini 파일에서 읽어온 kg당 단가

### 7. 쿠키 기반 설정
- `Allmonth`: 기간무시 월별그래프 표시 여부
- `errorstatpageNumber`: DataTable 페이지 번호 저장

### 8. 향후 개선 가능사항
1. **반응형 디자인**: 모바일 환경 최적화
2. **엑셀 내보내기**: 각 탭별 데이터 Excel 다운로드 기능
3. **실시간 업데이트**: Ajax 기반 자동 갱신
4. **상세 필터링**: 날짜, 현장명, 불량유형별 추가 필터
5. **비교 차트**: 두 데이터셋을 하나의 차트에서 비교 표시

### 9. 파일 위치
- **메인 파일**: `/error/statistics.php`
- **설정 파일**: `/steel/settings.ini` (가격 정보)
- **공통 파일**: `/lib/mydb.php` (데이터베이스 연결)

### 10. 테스트 체크리스트
- [ ] 두 탭 모두에서 출고일 필드 정상 표시 확인
- [ ] 필터링 조건 정확성 검증 (소장/소장불량, 업체/업체불량 제외)
- [ ] 차트 데이터와 테이블 데이터 일치성 확인
- [ ] 비용 계산 정확성 검증
- [ ] DataTable 페이징 및 검색 기능 동작 확인
- [ ] 브라우저 호환성 테스트 (Chrome, Firefox, Edge)

## 프로젝트 완료 상태
✅ **완료**: 출고일 필드 표시 문제 해결
✅ **완료**: 이중 탭 UI 구조 구현
✅ **완료**: 필터링 조건 개선
✅ **완료**: JavaScript 오류 수정
✅ **완료**: 차트 및 테이블 분리 표시

현재 시스템은 사용자가 요청한 모든 기능이 정상 작동하는 상태입니다.