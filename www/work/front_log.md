# Front Log 시스템 개발문서

미래기업 ERP 시스템의 Front Log 모듈 개발 가이드입니다. (Tabulator 기반)

## 시스템 개요

Front Log는 발주접수 후 미실측, 미청구 제품의 결재 예측을 위한 시스템입니다.
- **기술 스택**: PHP 7+, MySQL, Tabulator 5.5.0, Bootstrap 5
- **주요 기능**: 쟘 제품별 단가 관리, 현장 현황 추적, 실시간 합계 계산
- **UI 개선**: 통합 카드 디자인, output-chart-container 스타일 적용

## 디자인 시스템

### 통합 카드 레이아웃 (2024 업데이트)
```css
/* Output Chart Container 스타일 */
.output-chart-container {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(51, 65, 85, 0.05);
  margin-bottom: 1rem;
  overflow: hidden;
  transition: all 0.2s ease;
  max-width: 100%;
  /* 화면 가운데 정렬 추가 */
  margin-left: auto;
  margin-right: auto;
  display: block;
}

.output-chart-container:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

/* PC에서 테이블이 카드 body 폭을 꽉 채우도록 설정 */
@media (min-width: 992px) {
  .table-responsive {
    width: 100%;
  }

  .table-responsive .table {
    width: 100% !important;
    margin-bottom: 0;
  }
}
```

### 섹션 제목 및 구조
```css
.output-section-title {
  color: #334155;
  font-weight: 600;
  font-size: 1.1rem;
  margin: 0;
}

.output-chart-container h6.text-muted {
  color: #64748b !important;
  font-weight: 500;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 0.5rem;
  margin-bottom: 1rem !important;
}
```

### Output Table 스타일
```css
.output-table {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: none;
  width: 100%;
  border-collapse: collapse;
}

.output-table th {
  background: #f8fafc !important;
  color: var(--dashboard-text) !important;
  padding: 0.75rem !important;
  font-size: 0.85rem !important;
  font-weight: 500 !important;
  border: 1px solid #e2e8f0 !important;
  text-align: center !important;
}

.output-table tbody tr:hover td {
  background-color: #f1f5f9 !important;
  transition: background-color 0.2s ease;
}
```

## Tabulator 테이블 구성

### 라이브러리 설정
```html
<!-- Tabulator CDN -->
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
```

### 테이블 초기화
```javascript
table = new Tabulator("#myTable", {
    data: tableData,
    layout: "fitColumns",
    pagination: "local",
    paginationSize: 50,
    paginationSizeSelector: [50, 100, 200, 500, 1000],
    paginationCounter: "rows",
    movableColumns: true,
    resizableRows: false,
    initialSort: [
        {column: "orderday", dir: "desc"}
    ],
    columns: [
        // 컬럼 정의...
    ],
    rowClick: function(e, row) {
        var data = row.getData();
        var num = data.num;
        if(num) {
            redirectToView(num);
        }
    }
});
```

### 컬럼 구성
| 필드명 | 제목 | 너비 | 정렬 | 특별기능 |
|--------|------|------|------|----------|
| checkstep | 구분 | 80px | ✓ | 신규 태그 표시 |
| orderday | 접수 | 100px | ✓ | 날짜 형식 |
| workplacename | 현장명 | 200px+ | ✓ | 신규 접두사 |
| materials | 재질(소재) | 120px | ✓ | - |
| firstord | 원청 | 100px | ✓ | - |
| secondord | 발주처 | 100px | ✓ | - |
| worker | 시공팀 | 100px | ✓ | - |
| workitem | 설치수량 | 120px | ✓ | 막/멍/쪽 표시 |
| hpi | HPI | 100px | ✓ | 10자 제한 |
| memo | 비고 | 100px | ✓ | 10자 제한 |

## 데이터 처리

### PHP → JavaScript 데이터 변환
```php
// 안전한 데이터 변환
$dataArray[] = [
    'num' => $num,
    'checkstep' => $checkstep ? $checkstep : '',
    'orderday' => $orderday ? $orderday : '',
    'workplacename' => $workplacename ? htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') : '',
    'materials' => $materials ? htmlspecialchars($materials, ENT_QUOTES, 'UTF-8') : '',
    // ... 기타 필드
];

// JSON 생성 (안전한 이스케이프)
echo "tableData = " . json_encode($dataArray, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS) . ";";
```

### 천원 단위 계산
```php
// 금액 표시 (천원 단위)
value="<?= number_format($WJ_HL_amount/1000) ?> 천원"
```

## 쟘 제품별 단가 시스템 (통합 카드 구조)

### 통합 카드 레이아웃
```html
<!-- 제품별 단가 및 Front Log 통합 카드 (75% 너비, 가운데 정렬) -->
<div class="output-chart-container mt-3 w-75">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h5 class="output-section-title mb-0">제품별 단가 및 Front Log 매출 예측시스템</h5>
		<button id="writeBtn" type="button" class="btn btn-primary btn-sm">
			<i class="bi bi-pencil-square"></i> 단가 수정
		</button>
	</div>

	<div class="row">
		<!-- 제품별 단가 테이블 (좌측) -->
		<div class="col-md-5">
			<h6 class="text-muted mb-3">제품별 단가</h6>
			<!-- 단가 테이블 -->
		</div>

		<!-- Front Log 매출 예측 테이블 (우측) -->
		<div class="col-md-7">
			<h6 class="text-muted mb-3">Front Log (발주접수 후 미실측, 미청구 제품 결재 예측시스템)</h6>
			<!-- 매출 예측 테이블 -->
		</div>
	</div>
</div>

<!-- 현장 미실측, 미청구현황 카드 (75% 너비, 가운데 정렬) -->
<div class="card output-chart-container mt-1 mb-1 w-75">
	<div class="card-body">
		<!-- 검색 및 필터링 UI -->
	</div>
</div>
```

### 단가 테이블 구조
| 구분 | H/L 재질 | 기타 재질 |
|------|----------|-----------|
| 막판유 | WJ_HL | WJ |
| 막판무 | NJ_HL | NJ |
| 쪽쟘 | SJ_HL | SJ |

### 매출 예측 테이블 구조
| 구분 | 수량 | H/L(천원) | 기타(천원) | 합계(천원) |
|------|------|-----------|------------|------------|
| 막판유 | WJ_num | WJ_HL_amount/1000 | WJ_amount/1000 | WJ_total/1000 |
| 막판무 | NJ_num | NJ_HL_amount/1000 | NJ_amount/1000 | NJ_total/1000 |
| 쪽쟘 | SJ_num | SJ_HL_amount/1000 | SJ_amount/1000 | SJ_total/1000 |
| **합계** | - | - | - | **total/1000** |

### 합계 계산 로직
```php
// 재질별 분류 및 합계 계산
$combinedMaterials = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;

if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
    $WJ_HL += $widejamb;  // H/L 재질
} else {
    $WJ += $widejamb;     // 기타 재질
}

// 최종 합계 (천원 단위 표시)
$WJ_total = $WJ_amount + $WJ_HL_amount;
$total = $WJ_total + $NJ_total + $SJ_total;
echo number_format($total/1000); // 천원 단위로 표시
```

## 검색 및 필터링

### 검색 조건
- **공사구분**: 전체, 덧방, 신규
- **원청**: OTIS, TKEK
- **발주처**: 한산, 우성, 제일특수강
- **검색 필드**: 전체, 현장명, 원청, 발주처, 미래시공팀, 설계자

### 동적 SQL 생성
```php
// 조건별 SQL 생성
if($findstr !== "전체") {
    $searchstr = ($findstr == '덧방') ? '' : '신규';
    $sql .= " and ( checkstep='$searchstr' )";
}

// 미출고 리스트 필터
if ($check == '1') {
    $attached = " and ( workday='0000-00-00' or workday='' or workday IS NULL ) ";
}
```

## Tabulator 스타일링 (업데이트된 Light & Subtle Theme)

### 테이블 스타일
```css
.tabulator {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.tabulator:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

.tabulator .tabulator-header {
    background: #f0fbff;  /* Very light cyan */
    color: var(--dashboard-text);
    border: none;
    border-radius: 12px 12px 0 0;
}

.tabulator .tabulator-header .tabulator-col {
    background: transparent;
    border-right: 1px solid var(--dashboard-border);
    color: var(--dashboard-text);
    font-size: 0.8rem;
    font-weight: 500;
}

.tabulator .tabulator-row {
    background: rgba(255, 255, 255, 0.9);
    border-bottom: 1px solid var(--dashboard-border);
}

.tabulator .tabulator-row:hover {
    background-color: var(--dashboard-hover);
    color: var(--dashboard-text);
    transition: background-color 0.2s ease;
}
```

### 반응형 디자인
```css
@media (max-width: 768px) {
    .col-sm-5, .col-sm-7 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }
}
```

## 주요 기능

### 1. 실시간 합계 표시
- JavaScript로 동적 계산
- "총 X (SET), (막판 : X, 막판(無) : X, 쪽쟘 : X)" 형식

### 2. 페이지 상태 관리
```javascript
// 페이지 번호 쿠키 저장/복원
table.on("pageLoaded", function(pageno) {
    workfrontlogpageNumber = pageno;
    setCookie('workfrontlogpageNumber', pageno, 10);
});
```

### 3. 상세보기 팝업
```javascript
function redirectToView(num) {
    var page = workfrontlogpageNumber || 1;
    var url = "view.php?menu=no&num=" + num;
    customPopup(url, 'jamb 수주내역', 1800, 850);
}
```

## 성능 최적화

### 1. 로컬 페이지네이션
- 서버 부하 감소
- 빠른 페이지 전환
- 클라이언트 측 정렬/필터링

### 2. 데이터 안전성
- `htmlspecialchars()` 적용
- JSON 이스케이프 처리
- null 값 체크

### 3. 메모리 효율성
- 필요한 데이터만 로드
- 문자열 길이 제한 (HPI, memo: 10자)

## 개발 가이드라인

### 1. 새 컬럼 추가
1. PHP 데이터 배열에 필드 추가
2. Tabulator columns 설정에 컬럼 정의 추가
3. 필요시 formatter 함수 구현

### 2. 스타일 수정
- CSS 변수 활용으로 일관성 유지
- Material Design Light Blue 테마 준수
- 글래스모피즘 효과 적용

### 3. 성능 고려사항
- 대용량 데이터 시 서버 측 페이지네이션 고려
- 검색 조건 최적화
- 인덱스 활용

## 트러블슈팅

### 자주 발생하는 문제
1. **JavaScript 문법 오류**: JSON 특수문자 이스케이프 확인
2. **페이지 번호 오류**: 쿠키 저장/복원 로직 점검
3. **스타일 깨짐**: CSS 변수 정의 확인

### 디버깅 도구
```javascript
// 데이터 확인
console.log('tableData length:', tableData.length);
console.log('tableData sample:', tableData.slice(0, 3));
```

## 최신 UI 개선사항 (2024년 12월)

### 1. 통합 카드 디자인
- **단일 카드 구조**: 제품별 단가와 Front Log 매출 예측을 하나의 `output-chart-container`로 통합
- **2컬럼 레이아웃**: 좌측 단가 테이블(col-md-5), 우측 매출 예측(col-md-7)
- **중복 버튼 제거**: 단가 수정 버튼을 카드 상단 우측에 하나만 배치
- **카드 너비 조정**: `w-75` 클래스로 화면 너비의 75%로 설정
- **가운데 정렬**: `margin-left: auto`, `margin-right: auto`로 카드 중앙 배치

### 2. 테이블 스타일 개선
- **output-table 클래스**: 일관된 테이블 디자인
- **읽기 전용 데이터**: 단가 테이블을 입력 필드에서 표시 전용으로 변경
- **천원 단위 표시**: 모든 금액을 `number_format(amount/1000)` 형식으로 표시
- **합계 행 강조**: `table-info` 클래스로 최종 합계 행 시각적 강조

### 3. 색상 및 스타일 업데이트
- **Light & Subtle Theme**: 부드러운 색상과 미묘한 그림자 효과
- **호버 효과**: 카드와 테이블 행에 부드러운 transform 애니메이션
- **반응형 디자인**: 모바일 환경에서 자동으로 1컬럼 레이아웃으로 변경
- **PC 최적화**: 992px 이상에서 테이블이 카드 body 폭을 꽉 채우도록 설정

## 실시간 검색 기능

### Tabulator 검색 구현
```javascript
function performSearch() {
    var searchValue = document.getElementById('search').value.trim();
    var cleanSearchValue = searchValue.replace(/\s+/g, '');

    table.setFilter(function(data) {
        // 현장명 검색 (공백 제거)
        var workplacename = (data.workplacename || '').replace(/\s+/g, '');
        if (workplacename.toLowerCase().includes(cleanSearchValue.toLowerCase())) {
            return true;
        }

        // 기타 필드 검색 (재질, 원청, 발주처, 시공팀, HPI, 비고)
        var fields = ['materials', 'firstord', 'secondord', 'worker', 'hpi', 'memo', 'workitem'];
        for (let field of fields) {
            var fieldValue = (data[field] || '');
            if (fieldValue.toLowerCase().includes(searchValue.toLowerCase())) {
                return true;
            }
        }
        return false;
    });
}
```

### 검색 결과 표시
- **실시간 카운터**: "검색결과: X건 / 전체: Y건" 형식으로 표시
- **입력 지연**: 300ms 타이밍으로 성능 최적화
- **엔터키 지원**: 즉시 검색 실행

## 파일 구조

```
work/
├── front_log.php          # 메인 파일 (통합 카드 디자인 적용)
├── front_log_fixed.php    # 완전 개선된 버전
├── front_log.md          # 이 문서 (업데이트됨)
├── _row.php              # 행 데이터 처리
├── estimate.php          # 단가 입력 팝업
├── estimate.ini          # 단가 설정 파일
└── view.php              # 상세보기 팝업
```

## 버전 정보

- **Tabulator**: 5.5.0
- **Bootstrap**: 5.x
- **PHP**: 7+
- **MySQL**: 5.7+
- **UI Framework**: Light & Subtle Theme (2024)

## 개발 히스토리

### 2024년 12월 업데이트
- ✅ 통합 카드 레이아웃 적용
- ✅ output-chart-container 스타일 시스템 도입
- ✅ 중복 버튼 제거 및 UI 정리
- ✅ 읽기 전용 단가 테이블로 변경
- ✅ 천원 단위 금액 표시 표준화
- ✅ Light & Subtle Theme 적용
- ✅ 카드 너비 최적화 (75% 너비, 가운데 정렬)
- ✅ PC 환경 테이블 폭 최적화 (992px 이상)
- ✅ 현장 현황 카드도 동일한 스타일 적용

---

*이 문서는 미래기업 ERP 시스템의 Front Log 모듈 유지보수를 위해 작성되었습니다.*
*수정 시 기존 기능과의 호환성을 유지해주세요.*