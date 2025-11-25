# 모바일 최적화 개발 가이드

이 문서는 `ET_write_form.php`와 `list_estimate.php`에서 구현한 모바일 최적화 처리 과정을 정리한 가이드입니다. 특히 Select2를 사용하는 경우 모바일 환경에서의 처리 방법, 검색창 및 버튼 최적화를 중점적으로 다룹니다.

## 목차

1. [개요](#개요)
2. [Select2 모바일 비활성화 패턴](#select2-모바일-비활성화-패턴)
3. [모바일 카드 렌더링](#모바일-카드-렌더링)
4. [Select 요소 처리](#select-요소-처리)
5. [CSS 미디어 쿼리](#css-미디어-쿼리)
6. [이벤트 동기화](#이벤트-동기화)
7. [재사용 가능한 코드 패턴](#재사용-가능한-코드-패턴)
8. [검색창 및 버튼 최적화](#검색창-및-버튼-최적화-list_estimatephp)

---

## 개요

### 모바일 최적화의 목적

- **Select2 검색 기능 제거**: 모바일 환경에서는 Select2의 검색 기능이 불필요하고 사용성이 떨어지므로 일반 `<select>` 요소로 대체
- **카드 기반 레이아웃**: 테이블을 모바일 친화적인 카드 형태로 변환
- **반응형 디자인**: 화면 크기에 따라 자동으로 레이아웃 변경

### 기본 원칙

- **화면 크기 기준**: `window.innerWidth <= 768` (또는 CSS `@media (max-width: 768px)`)
- **PC와 모바일 분리**: PC에서는 Select2 사용, 모바일에서는 일반 select 사용
- **데이터 동기화**: 원본 요소와 모바일 카드 내부 요소 간 양방향 동기화

---

## Select2 모바일 비활성화 패턴

### 1. Select2 초기화 함수 수정

Select2를 초기화하는 함수에서 모바일 환경 체크를 추가합니다.

```javascript
// Select2 초기화
function initializeSelect2() {
    // 모바일 환경에서는 Select2를 초기화하지 않음
    if (window.innerWidth <= 768) {
        return;
    }
    
    $('.product-select').select2({
        placeholder: '상품을 선택하세요',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "검색 결과가 없습니다.";
            },
            searching: function() {
                return "검색 중...";
            }
        }
    });
}

// 초기 Select2 설정
initializeSelect2();
```

### 2. 옵션 로딩 함수에서 Select2 조건부 초기화

옵션을 동적으로 로드하는 함수에서도 모바일 체크를 추가합니다.

```javascript
function populateProductOptions(selectElement, callback) {
    const initialValue = selectElement.data('initial-value');
    
    $.ajax({
        url: 'get_products.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // 옵션 추가 로직...
            
            // 모바일 환경에서는 Select2를 초기화하지 않음
            if (window.innerWidth > 768) {
                // Select2 업데이트
                if (selectElement.hasClass('select2-hidden-accessible')) {
                    selectElement.select2('destroy');
                }
                
                // Select2 재초기화
                selectElement.select2({
                    placeholder: '상품을 선택하세요',
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "검색 결과가 없습니다.";
                        },
                        searching: function() {
                            return "검색 중...";
                        }
                    }
                });
            }
            
            // 초기값 설정 및 콜백 실행...
        },
        error: function(xhr, status, error) {
            console.error('상품 데이터 로드 오류:', error);
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
}
```

### 핵심 포인트

- **조건부 초기화**: `if (window.innerWidth > 768)` 조건으로 PC에서만 Select2 초기화
- **기존 Select2 제거**: 재초기화 전에 `select2('destroy')` 호출
- **모바일에서는 일반 select 유지**: Select2 초기화를 건너뛰면 자동으로 일반 select로 동작

---

## 모바일 카드 렌더링

### 1. 기본 구조

테이블을 모바일 카드로 변환하는 함수입니다.

```javascript
function renderMobileCards() {
    // 이미 렌더링 중이면 무시
    if (isRenderingCards) {
        return;
    }
    
    // 데스크톱에서는 모든 카드 컨테이너 제거
    if (window.innerWidth > 768) {
        var containers = document.querySelectorAll('.mobile-cards-container');
        containers.forEach(function(container) {
            container.remove();
        });
        return;
    }
    
    // 렌더링 시작 플래그 설정
    isRenderingCards = true;
    
    // 테이블 처리 로직...
    
    // 렌더링 완료 플래그 해제
    isRenderingCards = false;
}
```

### 2. 중복 렌더링 방지

디바운스 함수와 플래그를 사용하여 중복 렌더링을 방지합니다.

```javascript
// 디바운스 함수
function debounce(func, wait) {
    var timeout;
    return function executedFunction(...args) {
        var later = function() {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// 렌더링 플래그
var isRenderingCards = false;

// 디바운스된 렌더링 함수
var debouncedRenderMobileCards = debounce(renderMobileCards, 300);
```

### 3. 카드 생성 로직

테이블의 각 행을 카드로 변환합니다.

```javascript
// 테이블의 각 행 처리
var rows = table.querySelectorAll('tbody tr');
rows.forEach(function(row, rowIndex) {
    // 카드 생성
    var card = document.createElement('div');
    card.className = 'card mobile-card mb-3';
    
    // 테이블 헤더 읽기
    var headers = table.querySelectorAll('thead th');
    
    // 각 셀을 카드 항목으로 변환
    var cells = row.querySelectorAll('td');
    cells.forEach(function(cell, cellIndex) {
        var label = headers[cellIndex] ? headers[cellIndex].textContent.trim() : '';
        
        // 카드 항목 생성
        var cardItem = document.createElement('div');
        cardItem.className = 'd-flex align-items-center mb-2';
        
        var labelSpan = document.createElement('strong');
        labelSpan.textContent = label + ':';
        labelSpan.className = 'me-2';
        
        var valueSpan = document.createElement('span');
        // valueSpan에 셀 내용 추가...
        
        cardItem.appendChild(labelSpan);
        cardItem.appendChild(valueSpan);
        card.appendChild(cardItem);
    });
    
    // 카드 컨테이너에 추가
    cardContainer.appendChild(card);
});
```

---

## Select 요소 처리

### 1. Select 요소 복제 및 스타일 적용

모바일 카드에서 select 요소를 복제하고 스타일을 적용합니다.

```javascript
// select 요소가 있는 경우 특별 처리
var selectElement = cell.querySelector('select');
if (selectElement) {
    // 원본 select의 현재 값 저장
    var originalValue = $(selectElement).val() || '';
    var originalRowIndex = selectElement.getAttribute('data-row') || '';
    
    // 원본 select 요소를 복제
    var clonedSelect = selectElement.cloneNode(true);
    clonedSelect.style.cssText = 'width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; display: block !important; visibility: visible !important; opacity: 1 !important;';
    clonedSelect.className = selectElement.className;
    
    // 고유 ID 생성 (중복 방지)
    clonedSelect.id = (selectElement.id || '') + '-mobile-' + originalRowIndex;
    clonedSelect.name = selectElement.name;
    clonedSelect.setAttribute('data-row', originalRowIndex);
    clonedSelect.setAttribute('data-original-select-id', selectElement.id || '');
    
    // valueSpan에 select 추가
    valueSpan.appendChild(clonedSelect);
    valueSpan.style.cssText = 'width: 100% !important; display: block !important;';
    
    // select 요소가 즉시 보이도록 처리
    clonedSelect.style.display = 'block';
    clonedSelect.style.visibility = 'visible';
    clonedSelect.style.opacity = '1';
    
    // 모바일에서는 Select2 없이 일반 select로 처리
    setTimeout(function() {
        if (typeof $ !== 'undefined') {
            var $clonedSelect = $(clonedSelect);
            var $originalSelect = $(selectElement);
            
            // select 요소가 보이도록 보장
            $clonedSelect.css({
                'display': 'block',
                'visibility': 'visible',
                'opacity': '1',
                'width': '100%',
                'max-width': '100%',
                'box-sizing': 'border-box',
                'padding': '0.375rem 0.75rem',
                'font-size': '1rem',
                'line-height': '1.5',
                'border': '1px solid #ced4da',
                'border-radius': '0.25rem',
                'background-color': '#fff'
            });
            
            // 옵션 로드 및 이벤트 동기화...
        }
    }, 200);
}
```

### 2. 옵션 로드

복제된 select에 옵션을 로드합니다.

```javascript
// populateProductOptions를 호출하여 옵션 로드
if (typeof populateProductOptions === 'function') {
    populateProductOptions($clonedSelect, function() {
        // 옵션 로드 완료 후 원본 select의 값 복사
        if (originalValue) {
            $clonedSelect.val(originalValue);
        }
        
        // 이벤트 동기화...
    });
} else {
    // populateProductOptions가 없는 경우 원본 select의 옵션 복사
    var originalOptions = $originalSelect.find('option');
    $clonedSelect.empty();
    originalOptions.each(function() {
        var optionValue = $(this).val();
        var optionText = $(this).text();
        var isSelected = $(this).prop('selected');
        var newOption = $('<option></option>').attr('value', optionValue).text(optionText);
        if (isSelected) {
            newOption.prop('selected', true);
        }
        $clonedSelect.append(newOption);
    });
    
    if (originalValue) {
        $clonedSelect.val(originalValue);
    }
}
```

---

## 이벤트 동기화

### 1. 양방향 동기화 패턴

원본 select와 복제된 select 간의 값을 동기화합니다.

```javascript
// 복제된 select의 change 이벤트 → 원본 select 업데이트
$clonedSelect.off('change.mobile-sync');
$clonedSelect.on('change.mobile-sync', function(e) {
    var selectedValue = $clonedSelect.val();
    
    // 원본 select에 값 설정
    $originalSelect.val(selectedValue);
    
    // 원본 select의 change 이벤트 트리거
    $originalSelect.trigger('change');
    
    // 원본 테이블의 계산 함수 호출
    if (typeof updateTotals === 'function') {
        updateTotals();
    }
    
    // 카드 다시 렌더링하여 선택된 값이 표시되도록
    if (window.innerWidth <= 768) {
        setTimeout(function() {
            renderMobileCards();
        }, 300);
    }
});

// 원본 select의 change 이벤트 → 복제된 select 업데이트
$originalSelect.off('change.mobile-sync');
$originalSelect.on('change.mobile-sync', function() {
    var currentValue = $originalSelect.val();
    if ($clonedSelect.val() !== currentValue) {
        $clonedSelect.val(currentValue);
    }
});
```

### 2. 이벤트 네임스페이스 사용

`.mobile-sync` 네임스페이스를 사용하여 이벤트를 관리합니다.

- **장점**: 다른 이벤트와 충돌 방지, 쉽게 제거 가능 (`off('change.mobile-sync')`)
- **사용법**: `on('change.mobile-sync', ...)`, `off('change.mobile-sync')`

---

## CSS 미디어 쿼리

### 1. 기본 미디어 쿼리

```css
@media (max-width: 768px) {
    /* 공급자 정보 숨기기 */
    .supplier-info {
        display: none !important;
    }
    
    /* body와 html 오버플로우 방지 */
    html, body {
        overflow-x: hidden !important;
        max-width: 100% !important;
        width: 100% !important;
        box-sizing: border-box !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    * {
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 컨테이너 최적화 */
    .container,
    .container-fluid {
        padding: 0.5rem !important;
        max-width: 100% !important;
        width: 100% !important;
        box-sizing: border-box !important;
        margin: 0 auto !important;
        overflow-x: hidden !important;
    }
    
    /* 카드 영역 최적화 */
    .card {
        margin: 0.5rem auto !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
    }
    
    /* 입력 필드 최적화 */
    .form-control,
    .form-select,
    input[type="text"],
    input[type="date"],
    input[type="number"],
    textarea,
    select {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 내부 select 요소 최적화 */
    .mobile-card select,
    .mobile-card .form-select,
    .mobile-card .product-select {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .mobile-card .select2-container {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .mobile-card .select2-selection__rendered {
        text-overflow: ellipsis !important;
        overflow: hidden !important;
        white-space: nowrap !important;
    }
}
```

### 2. PC 전용 스타일

PC 화면에서는 모바일 스타일이 적용되지 않도록 명시적으로 설정합니다.

```css
/* PC 화면 전용 스타일 (미디어 쿼리 밖에 위치) */
.title-author-wrapper {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    /* 모바일에서는 세로 배치 */
    .title-author-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
```

---

## 재사용 가능한 코드 패턴

### 1. Select2 초기화 헬퍼 함수

다른 파일에서도 사용할 수 있는 범용 함수입니다.

```javascript
/**
 * Select2를 조건부로 초기화하는 헬퍼 함수
 * @param {jQuery} $selectElement - Select2를 적용할 select 요소
 * @param {Object} options - Select2 옵션
 * @param {number} breakpoint - 모바일/PC 구분 기준 (기본값: 768)
 */
function conditionalSelect2Init($selectElement, options, breakpoint) {
    breakpoint = breakpoint || 768;
    
    // 모바일 환경에서는 Select2를 초기화하지 않음
    if (window.innerWidth <= breakpoint) {
        return;
    }
    
    // 기존 Select2가 있으면 제거
    if ($selectElement.hasClass('select2-hidden-accessible')) {
        $selectElement.select2('destroy');
    }
    
    // Select2 초기화
    $selectElement.select2(options);
}

// 사용 예시
conditionalSelect2Init($('.product-select'), {
    placeholder: '상품을 선택하세요',
    allowClear: true,
    width: '100%'
});
```

### 2. 모바일 체크 헬퍼 함수

```javascript
/**
 * 현재 화면이 모바일인지 확인
 * @param {number} breakpoint - 모바일/PC 구분 기준 (기본값: 768)
 * @returns {boolean}
 */
function isMobile(breakpoint) {
    breakpoint = breakpoint || 768;
    return window.innerWidth <= breakpoint;
}

// 사용 예시
if (isMobile()) {
    // 모바일 전용 로직
} else {
    // PC 전용 로직
}
```

### 3. Select 동기화 헬퍼 함수

```javascript
/**
 * 원본 select와 복제된 select 간의 동기화 설정
 * @param {jQuery} $originalSelect - 원본 select 요소
 * @param {jQuery} $clonedSelect - 복제된 select 요소
 * @param {Function} onSync - 동기화 시 실행할 콜백 함수
 */
function syncSelectElements($originalSelect, $clonedSelect, onSync) {
    // 복제된 select → 원본 select
    $clonedSelect.off('change.mobile-sync');
    $clonedSelect.on('change.mobile-sync', function() {
        var selectedValue = $clonedSelect.val();
        $originalSelect.val(selectedValue).trigger('change');
        
        if (onSync && typeof onSync === 'function') {
            onSync(selectedValue);
        }
    });
    
    // 원본 select → 복제된 select
    $originalSelect.off('change.mobile-sync');
    $originalSelect.on('change.mobile-sync', function() {
        var currentValue = $originalSelect.val();
        if ($clonedSelect.val() !== currentValue) {
            $clonedSelect.val(currentValue);
        }
    });
}

// 사용 예시
syncSelectElements($originalSelect, $clonedSelect, function(value) {
    if (typeof updateTotals === 'function') {
        updateTotals();
    }
    if (isMobile()) {
        setTimeout(function() {
            renderMobileCards();
        }, 300);
    }
});
```

### 4. 창 크기 변경 감지

```javascript
// 창 크기 변경 시 Select2 재초기화
$(window).on('resize', debounce(function() {
    if (isMobile()) {
        // 모바일로 전환: Select2 제거
        $('.product-select').each(function() {
            var $select = $(this);
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
        });
    } else {
        // PC로 전환: Select2 초기화
        initializeSelect2();
    }
}, 300));
```

---

## 구현 체크리스트

다른 파일에 모바일 최적화를 적용할 때 다음 사항을 확인하세요:

### Select2 관련

- [ ] `initializeSelect2()` 함수에 모바일 체크 추가
- [ ] 옵션 로드 함수에 모바일 체크 추가
- [ ] 창 크기 변경 시 Select2 재초기화 로직 추가

### 모바일 카드 렌더링

- [ ] `renderMobileCards()` 함수 구현
- [ ] 중복 렌더링 방지 (디바운스, 플래그)
- [ ] Select 요소 복제 및 스타일 적용
- [ ] 이벤트 동기화 설정

### CSS

- [ ] `@media (max-width: 768px)` 미디어 쿼리 추가
- [ ] 모바일 카드 내부 select 요소 스타일 추가
- [ ] PC 전용 스타일 명시 (필요한 경우)

### 테스트

- [ ] 모바일 화면에서 Select2 검색 기능이 비활성화되는지 확인
- [ ] 일반 select로 옵션 선택이 정상 작동하는지 확인
- [ ] 원본 select와 복제된 select 간 동기화 확인
- [ ] 창 크기 변경 시 자동 전환 확인

---

## 주의사항

1. **타이밍 이슈**: Select2 초기화와 옵션 로드는 `setTimeout`을 사용하여 DOM 준비 후 실행
2. **이벤트 중복 방지**: `.mobile-sync` 네임스페이스를 사용하여 이벤트 관리
3. **메모리 누수 방지**: 이벤트 리스너 제거 (`off()`) 후 재등록 (`on()`)
4. **성능 최적화**: 디바운스 함수 사용으로 불필요한 렌더링 방지
5. **접근성**: 모바일에서도 키보드 네비게이션 가능하도록 일반 select 유지

---

## 참고 파일

### ET_write_form.php
- 전체 구현 예시
- 주요 함수 위치:
  - `initializeSelect2()`: 라인 2279
  - `populateProductOptions()`: 라인 4119
  - `renderMobileCards()`: 라인 1673
  - `fixRowNumberLayout()`: 라인 2115
  - CSS 미디어 쿼리: 라인 74

### list_estimate.php
- 검색창 및 버튼 최적화 구현 예시
- 주요 함수 위치:
  - `renderMobileCards()`: 모바일 카드 렌더링
  - `isMobile()`: 모바일 환경 체크
  - `debounce()`: 디바운스 함수
  - 검색 이벤트 처리: 라인 1311-1350
  - CSS 미디어 쿼리: 라인 97

---

## 검색창 및 버튼 최적화 (list_estimate.php)

### 1. 검색창 통합 레이아웃

모바일 환경에서 검색 입력 필드와 검색 버튼을 한 행에 통합하여 표시합니다.

#### HTML 구조

```html
<div class="d-flex justify-content-center align-items-center search-container">
    <div class="inputWrap">
        <input type="text" id="search" name="search" class="form-control" placeholder="검색어를 입력해 주세요.">
        <button class="btnClear"></button>
        <button type="button" id="searchBtnMobile" class="btn-search-icon">
            <i class="bi bi-search"></i>
        </button>
    </div>
</div>
```

#### CSS 스타일

```css
@media (max-width: 768px) {
    /* 검색 컨테이너 - 한 행에 표시 */
    .search-container {
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 0.5rem !important;
        padding: 0.25rem 0.5rem !important;
        width: 100% !important;
        overflow-x: hidden !important;
        overflow-y: hidden !important;
        margin-top: 0.25rem !important;
    }
    
    /* 입력 필드 래퍼 */
    .inputWrap {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        overflow: hidden !important;
    }
    
    /* 검색 입력 필드 */
    .inputWrap input {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 80px 0.5rem 0.75rem !important; /* 오른쪽 아이콘 공간 확보 */
        font-size: 1rem !important;
        border: 2px solid #28a745 !important; /* 녹색 테두리 */
        border-radius: 0.5rem !important;
        background-color: #fff !important;
        overflow: hidden !important;
    }
    
    /* Clear 버튼 (X 아이콘) */
    .btnClear {
        position: absolute !important;
        right: 50px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 24px !important;
        height: 24px !important;
        z-index: 10 !important;
        cursor: pointer !important;
    }
    
    /* 검색 아이콘 버튼 (입력 필드 내부) */
    .btn-search-icon {
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 11 !important;
        cursor: pointer !important;
    }
    
    .btn-search-icon i {
        font-size: 1.2rem !important;
        color: #28a745 !important;
    }
    
    .btn-search-icon:hover {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    /* 모바일에서는 PC 검색 버튼 숨김 */
    .search-container #searchBtn {
        display: none !important;
    }
}
```

### 2. 버튼 최적화

#### 액션 버튼 (원래 크기 유지)

모바일에서도 버튼이 가로를 꽉 채우지 않고 원래 크기만큼만 차지하도록 설정합니다.

```css
@media (max-width: 768px) {
    /* 제목 영역 버튼 그룹 */
    .d-flex.mb-3.mt-2.justify-content-center.align-items-center {
        flex-direction: row !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.25rem !important;
        padding: 0.5rem !important;
        margin-bottom: 0.5rem !important; /* 행간격 절반으로 줄임 */
    }
    
    /* 버튼 원래 크기 유지 */
    .d-flex.mb-3.mt-2.justify-content-center.align-items-center button {
        width: auto !important;
        flex: 0 0 auto !important;
        min-width: auto !important;
        max-width: none !important;
        margin: 0.125rem !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 0.9rem !important;
        white-space: nowrap !important;
    }
}
```

#### 행간격 조정

버튼과 검색창 사이의 행간격을 절반으로 줄입니다.

```css
@media (max-width: 768px) {
    /* 제목 영역 하단 마진 감소 */
    .d-flex.mb-3.mt-2.justify-content-center.align-items-center {
        margin-bottom: 0.5rem !important; /* 기존 1rem의 절반 */
    }
    
    /* 검색 컨테이너 상단 마진 */
    .search-container {
        margin-top: 0.25rem !important;
        padding: 0.25rem 0.5rem !important; /* 상하 패딩 절반 */
    }
    
    /* 날짜 입력 영역 마진 조정 */
    .d-flex.mb-1.mt-1.justify-content-center.align-items-center {
        margin-top: 0.25rem !important;
        margin-bottom: 0.25rem !important;
        padding: 0.25rem 0.5rem !important;
    }
}
```

### 3. 날짜 입력 필드 최적화

날짜 범위 입력 필드와 구분자(`~`)를 한 행에 표시합니다.

```css
@media (max-width: 768px) {
    /* 날짜 입력 영역 - 한 행에 표시 */
    .d-flex.mb-1.mt-1.justify-content-center.align-items-center {
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 0.25rem !important;
    }
    
    /* 날짜 입력 필드 */
    .d-flex.mb-1.mt-1.justify-content-center.align-items-center input[type="date"] {
        width: auto !important;
        flex: 1 1 0 !important;
        min-width: 0 !important;
        max-width: calc(50% - 0.75rem) !important; /* ~ 기호 공간 고려 */
        padding: 0.375rem 0.5rem !important;
        font-size: 0.85rem !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }
    
    /* 날짜 구분자 */
    .date-separator {
        flex-shrink: 0 !important;
        margin: 0 0.125rem !important;
        font-size: 0.85rem !important;
        white-space: nowrap !important;
    }
}
```

### 4. 페이지네이션 숨김

모바일 환경에서 DataTables의 페이지네이션 정보와 컨트롤을 숨깁니다.

```css
@media (max-width: 768px) {
    /* DataTables UI 요소 숨기기 */
    .dataTables_length,
    .dataTables_filter,
    .dataTables_info,
    .dataTables_paginate {
        display: none !important;
    }
}
```

### 5. 모바일 전용 요소 PC에서 숨김

PC 환경에서 모바일 전용 검색 버튼을 숨깁니다.

```css
/* PC 환경에서 모바일 전용 요소 숨김 */
@media (min-width: 769px) {
    .btn-search-icon,
    #searchBtnMobile {
        display: none !important;
    }
}
```

### 6. 모바일 카드 폰트 크기 조정

모바일 카드의 검정색 텍스트(값 부분) 폰트 크기를 20% 줄입니다.

```css
@media (max-width: 768px) {
    .mobile-card span {
        flex: 1 !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        font-size: 0.8em !important; /* 현재 폰트 크기의 80% (20% 감소) */
    }
}
```

### 7. JavaScript 이벤트 처리

#### 검색 입력 필드 Enter 키 처리

```javascript
$(document).ready(function() {
    // 검색 입력 필드에서 Enter 키 처리
    $("#search").keydown(function(event) {
        if (event.key === "Enter" || event.keyCode === 13) {
            event.preventDefault();
            // 모바일/PC에 따라 적절한 검색 버튼 클릭
            if (isMobile()) {
                $("#searchBtnMobile").click();
            } else {
                $("#searchBtn").click();
            }
        }
    });
});
```

#### 검색 버튼 클릭 이벤트 통합

```javascript
$(document).ready(function() {
    // PC용과 모바일용 검색 버튼 모두에 동일한 핸들러 적용
    $("#searchBtn, #searchBtnMobile").click(function() {
        // 페이지 번호를 1로 설정
        currentpageNumber = 1;
        setCookie('currentpageNumber', currentpageNumber, 10);
        
        // 날짜 범위를 '전체'로 설정
        $('#dateRange').val('전체').change();
        
        // 폼 제출
        document.getElementById('board_form').submit();
        
        // 모바일에서 카드 다시 렌더링
        if (isMobile()) {
            setTimeout(function() {
                processedTables.clear();
                debouncedRenderMobileCards();
            }, 500);
        }
    });
});
```

### 8. 알림 메시지 숨김

모바일 환경에서 불필요한 알림 메시지를 숨깁니다.

```css
@media (max-width: 768px) {
    /* 알림 영역 숨김 */
    .alert-container {
        display: none !important;
    }
    
    .alert {
        display: none !important;
    }
}
```

### 핵심 포인트

1. **검색창 통합**: 검색 입력 필드와 버튼을 한 행에 배치하여 공간 효율성 향상
2. **버튼 크기**: 모바일에서도 버튼이 원래 크기만큼만 차지하도록 설정
3. **행간격 최적화**: 요소 간 간격을 절반으로 줄여 화면 공간 활용
4. **페이지네이션 숨김**: 모바일에서 불필요한 페이지네이션 정보 제거
5. **PC/모바일 분리**: 각 환경에 맞는 UI 요소만 표시
6. **폰트 크기 조정**: 가독성을 유지하면서 공간 절약

---

## 업데이트 이력

- 2025-01-XX: 초기 문서 작성 (ET_write_form.php 기반)
- 2025-01-XX: 검색창 및 버튼 최적화 섹션 추가 (list_estimate.php 기반)

