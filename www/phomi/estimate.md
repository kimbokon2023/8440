# 모바일 화면 최적화 가이드 (견적서)

## 개요
견적서(`ET_write_form.php`)에서 구현한 모바일 화면 최적화 패턴을 정리한 문서입니다. 이 패턴은 수주리스트 및 다른 테이블 기반 화면에서도 재사용할 수 있습니다.

## 핵심 원리

### 1. 테이블 → 카드 변환
- 모바일 화면(768px 이하)에서 테이블을 카드 형식으로 자동 변환
- 원본 테이블은 숨기고, 카드 컨테이너를 동적으로 생성
- 각 테이블 행(`tr`)을 개별 카드(`.mobile-card`)로 변환

### 2. 이벤트 위임 (Event Delegation)
- `$(document).on('input', '.quantity-input, .unit-price-input', ...)` 형태로 이벤트 위임 사용
- 새로 추가/복사된 카드의 입력 필드에도 자동으로 이벤트 연결
- 클래스명을 일관되게 유지하여 이벤트 위임이 작동하도록 보장

### 3. 원본 테이블과 모바일 카드 동기화
- 모바일 카드의 입력 필드 변경 시 원본 테이블의 해당 행을 찾아 동기화
- 원본 테이블에서 계산 수행 후 결과를 모바일 카드에 반영
- 단방향 동기화: 모바일 카드 → 원본 테이블 → 계산 → 모바일 카드 업데이트

## 주요 함수 및 패턴

### 1. 모바일 카드 렌더링 함수

```javascript
function renderMobileCards() {
    // 1. 모바일 환경 확인
    if (window.innerWidth > 768) {
        // 데스크톱에서는 카드 컨테이너 제거
        return;
    }
    
    // 2. 모든 테이블에 대해 카드 변환
    var tables = document.querySelectorAll('table:not(.mobile-cards-container table)');
    
    tables.forEach(function(table) {
        // 3. 카드 컨테이너 생성 또는 찾기
        var cardsContainer = document.querySelector('#mobileCardsContainer-' + tableId);
        if (!cardsContainer) {
            // 카드 컨테이너 생성
        }
        
        // 4. 각 행을 카드로 변환
        var rows = tbody.querySelectorAll('tr');
        rows.forEach(function(row) {
            // 카드 생성 및 데이터 복사
        });
    });
}
```

**핵심 포인트:**
- `processedTables` Set을 사용하여 중복 처리 방지
- 행 추가/삭제/복사 후 `processedTables.clear()` 호출하여 재렌더링 보장
- 입력 필드 생성 시 원본 테이블의 클래스와 속성 유지

### 2. 입력 필드 클래스 유지

```javascript
// select가 아닌 경우 입력 필드 처리
var inputElement = cell.querySelector('input');
if (inputElement) {
    // 입력 필드를 복제하여 클래스와 속성 유지
    var clonedInput = inputElement.cloneNode(true);
    clonedInput.className = inputElement.className; // 클래스 유지
    clonedInput.name = inputElement.name; // name 속성 유지
    clonedInput.id = (inputElement.id || '') + '-mobile-' + rowIndex;
    clonedInput.value = inputElement.value;
    // ... 기타 속성 복사
}
```

**핵심 포인트:**
- 원본 테이블의 입력 필드 클래스(`quantity-input`, `unit-price-input` 등)를 그대로 유지
- 이벤트 위임이 작동하려면 클래스명이 일치해야 함

### 3. 상품 선택 시 동적 업데이트

```javascript
// 상품 선택 이벤트 (이벤트 위임)
$(document).on('change', '.product-select', function(e) {
    handleProductSelectChange($(this));
});

function handleProductSelectChange(selectElement) {
    // 1. 선택된 상품의 데이터 추출 (규격, 단가 등)
    // 2. 원본 테이블의 입력 필드 업데이트
    // 3. 금액 계산 (공급가액, 세액)
    // 4. 모바일 카드의 입력 필드도 업데이트
    if (rowIndexForMobile !== undefined && rowIndexForMobile !== '') {
        const $mobileCard = $('.mobile-card').filter(function() {
            const $cardSelect = $(this).find('select[data-row="' + rowIndexForMobile + '"]');
            return $cardSelect.length > 0;
        });
        
        if ($mobileCard.length > 0) {
            // 모바일 카드의 입력 필드 업데이트
            $mobileCard.find('input.specification-input').val(size);
            $mobileCard.find('input.size-input').val(spec);
            $mobileCard.find('input.unit-price-input').val(unitPriceVal.toLocaleString());
            // ... 기타 필드 업데이트
        }
    }
}
```

**핵심 포인트:**
- 모바일 카드의 select 변경 시 원본 테이블의 select로 이벤트 전달
- 원본 테이블에서 계산 후 결과를 모바일 카드에 반영
- `data-row` 속성을 사용하여 원본 테이블 행과 모바일 카드 매칭

### 4. 수량/단가 변경 시 자동 계산

```javascript
// 수량/단가 입력 이벤트 (이벤트 위임)
$(document).on('input', '.quantity-input, .unit-price-input', function() {
    const $input = $(this);
    let row = $input.closest('tr.item-row');
    
    // 모바일 카드의 입력 필드인 경우 원본 테이블의 행 찾기
    if (row.length === 0 || !row.hasClass('item-row')) {
        const $mobileCard = $input.closest('.mobile-card');
        if ($mobileCard.length > 0) {
            // 모바일 카드 내의 select 요소에서 data-row 찾기
            const rowIndex = $mobileCard.find('select[data-row]').first().attr('data-row');
            if (rowIndex !== undefined && rowIndex !== '') {
                // 원본 테이블의 행 찾기
                row = $('.item-row[data-row="' + rowIndex + '"]');
                
                // 모바일 카드의 값을 원본 테이블로 동기화
                if ($input.hasClass('quantity-input')) {
                    row.find('.quantity-input').val($input.val());
                } else if ($input.hasClass('unit-price-input')) {
                    row.find('.unit-price-input').val($input.val());
                }
            }
        }
    }
    
    // 원본 테이블에서 계산 수행
    // ... 계산 로직
    
    // 모바일 카드의 값도 업데이트
    if (rowIndex !== undefined && rowIndex !== '') {
        const $mobileCard = $('.mobile-card').filter(function() {
            const $cardSelect = $(this).find('select[data-row="' + rowIndex + '"]');
            return $cardSelect.length > 0;
        });
        
        if ($mobileCard.length > 0) {
            // 모바일 카드의 면적, 공급가액, 세액 업데이트
            $mobileCard.find('input.area-input').val(totalArea.toFixed(2));
            // ... 기타 필드 업데이트
        }
    }
});
```

**핵심 포인트:**
- 모바일 카드의 입력 필드 변경 → 원본 테이블 동기화 → 계산 → 모바일 카드 업데이트
- `data-row` 속성을 사용하여 원본 테이블 행과 모바일 카드 매칭

### 5. 기타 비용(부자재) 카드 자동 계산

```javascript
// 기타 비용 입력 이벤트 (이벤트 위임)
$(document).on('input', '.cost-quantity-input, .cost-unit-price-input', function(e) {
    const $input = $(this);
    let row = $input.closest('.cost-row');
    
    // 모바일 카드의 입력 필드인 경우 원본 테이블의 행 찾기
    if (row.length === 0 || !row.hasClass('cost-row')) {
        const $mobileCard = $input.closest('.mobile-card');
        if ($mobileCard.length > 0) {
            // 모바일 카드 내의 입력 필드에서 구분, 항목, 단위 값을 가져와서 원본 테이블 행 찾기
            const $cardCategoryInput = $mobileCard.find('input[name*="[category]"]');
            const $cardItemInput = $mobileCard.find('input[name*="[item]"]');
            const $cardUnitInput = $mobileCard.find('input[name*="[unit]"]');
            
            // 원본 테이블에서 일치하는 행 찾기
            $('.cost-row').each(function() {
                const $costRow = $(this);
                if (rowCategory === cardCategoryVal && rowItem === cardItemVal && rowUnit === cardUnitVal) {
                    row = $costRow;
                    return false;
                }
            });
            
            // 모바일 카드의 값을 원본 테이블로 동기화
            if (row.length > 0) {
                if ($input.hasClass('cost-quantity-input')) {
                    row.find('.cost-quantity-input').val($input.val());
                } else if ($input.hasClass('cost-unit-price-input')) {
                    row.find('.cost-unit-price-input').val($input.val());
                }
            }
        }
    }
    
    // 원본 테이블에서 계산 수행
    calculateCostRow(row);
    
    // 모바일 카드의 값도 업데이트
    const $mobileCard = $input.closest('.mobile-card');
    if ($mobileCard.length > 0 && row.length > 0) {
        const supplyAmount = row.find('.cost-supply-amount').val();
        const taxAmount = row.find('.cost-tax-amount').val();
        
        $mobileCard.find('input.cost-supply-amount').val(supplyAmount);
        $mobileCard.find('input.cost-tax-amount').val(taxAmount);
    }
    
    // 소계 및 합계 업데이트
    updateOtherCostsSubtotal();
    updateTotals();
});
```

**핵심 포인트:**
- 기타 비용 테이블은 `data-row` 속성이 없으므로 구분, 항목, 단위 값을 비교하여 원본 테이블 행 찾기
- 시공비 특별 계산 로직도 정상 동작 (28헤베 이하는 70만원 고정, 초과시 헤베당 25,000원)

### 6. 행 추가/복사/삭제 시 카드 재렌더링

```javascript
function addRowAfter(rowIndex) {
    // ... 행 추가 로직
    
    // 모바일 카드 다시 렌더링
    if (window.innerWidth <= 768) {
        // processedTables 초기화하여 모든 테이블을 다시 처리
        processedTables.clear();
        setTimeout(function() {
            renderMobileCards();
            setTimeout(function() {
                fixRowNumberLayout();
            }, 300);
        }, 200);
    }
}

function copyRow(rowIndex) {
    // ... 행 복사 로직
    
    // 모바일 카드 다시 렌더링
    if (window.innerWidth <= 768) {
        processedTables.clear();
        setTimeout(function() {
            renderMobileCards();
            setTimeout(function() {
                fixRowNumberLayout();
            }, 300);
        }, 200);
    }
}

function deleteRow(rowIndex) {
    // ... 행 삭제 로직
    
    // 모바일 카드 다시 렌더링
    if (window.innerWidth <= 768) {
        processedTables.clear();
        setTimeout(function() {
            renderMobileCards();
            setTimeout(function() {
                fixRowNumberLayout();
            }, 300);
        }, 200);
    }
}
```

**핵심 포인트:**
- 행 추가/복사/삭제 후 `processedTables.clear()` 호출하여 모든 테이블을 다시 처리
- `renderMobileCards()` 호출 후 `fixRowNumberLayout()` 호출하여 레이아웃 수정

## CSS 패턴

### 모바일 카드 스타일

```css
@media (max-width: 768px) {
    /* 테이블 숨기기 */
    table:not(.mobile-cards-container table) {
        display: none !important;
    }
    
    /* 모바일 카드 컨테이너 */
    .mobile-cards-container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 0 !important;
        box-sizing: border-box !important;
    }
    
    /* 모바일 카드 */
    .mobile-card {
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 내부 텍스트 줄바꿈 */
    .mobile-card span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 입력 필드 */
    .mobile-card input,
    .mobile-card select {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
}
```

## 구현 체크리스트

다른 화면에 모바일 최적화를 적용할 때 다음 사항을 확인하세요:

### 1. 테이블 구조 확인
- [ ] 테이블에 `thead`, `tbody` 구조가 있는지 확인
- [ ] 각 `td`에 `data-label` 속성이 있는지 확인 (카드의 라벨로 사용됨)
- [ ] 행에 고유 식별자(`data-row` 등)가 있는지 확인

### 2. 입력 필드 클래스 확인
- [ ] 입력 필드에 일관된 클래스명이 있는지 확인 (예: `quantity-input`, `unit-price-input`)
- [ ] 이벤트 위임을 사용하는 경우 클래스명이 일치해야 함

### 3. 이벤트 핸들러 확인
- [ ] 이벤트 위임(`$(document).on(...)`)을 사용하는지 확인
- [ ] 모바일 카드의 입력 필드 변경 시 원본 테이블과 동기화하는 로직이 있는지 확인
- [ ] 계산 후 모바일 카드의 값을 업데이트하는 로직이 있는지 확인

### 4. 행 추가/삭제/복사 처리
- [ ] 행 추가/삭제/복사 후 `processedTables.clear()` 호출하는지 확인
- [ ] `renderMobileCards()` 호출하여 카드 재렌더링하는지 확인
- [ ] `fixRowNumberLayout()` 호출하여 레이아웃 수정하는지 확인

### 5. Select 요소 처리
- [ ] Select2를 사용하는 경우 모바일 카드에서는 Select2를 초기화하지 않음
- [ ] 모바일 카드의 select 변경 시 원본 테이블의 select로 이벤트 전달
- [ ] 무한 루프 방지를 위한 `isSyncing` 플래그 사용

## 주의사항

1. **무한 루프 방지**
   - 모바일 카드와 원본 테이블 간 양방향 동기화 시 무한 루프 발생 가능
   - 단방향 동기화 사용: 모바일 카드 → 원본 테이블 → 계산 → 모바일 카드 업데이트
   - `isSyncing` 플래그를 사용하여 동기화 중 이벤트 재발생 방지

2. **이벤트 위임 사용**
   - 동적으로 추가되는 요소에도 이벤트가 연결되도록 이벤트 위임 사용
   - `$(document).on('input', '.quantity-input', ...)` 형태로 사용

3. **클래스명 일관성**
   - 원본 테이블과 모바일 카드의 입력 필드 클래스명이 일치해야 함
   - 이벤트 위임이 작동하려면 클래스명이 필수

4. **타이밍 이슈**
   - 카드 렌더링 후 이벤트 핸들러 연결을 위해 `setTimeout` 사용
   - DOM 업데이트 완료를 기다린 후 이벤트 핸들러 연결

5. **성능 최적화**
   - `processedTables` Set을 사용하여 중복 처리 방지
   - `debounce` 함수를 사용하여 과도한 렌더링 방지

## 참고 파일

- `www/phomi/ET_write_form.php`: 견적서 모바일 최적화 구현 예제
- `www/phomi/mobile_dev.md`: 모바일 개발 가이드 (검색창, 버튼 등 UI 패턴)

## 다음 단계

수주리스트에 적용할 때:
1. 테이블 구조 분석
2. 입력 필드 클래스명 확인
3. 이벤트 핸들러 확인 및 수정
4. `renderMobileCards()` 함수 적용
5. 모바일 카드와 원본 테이블 동기화 로직 추가
6. CSS 미디어 쿼리 추가

