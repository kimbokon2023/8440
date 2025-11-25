# 모바일 터치와 데스크톱 마우스 입력의 공통 처리 전략

모바일 환경에서 터치 시 불필요하게 팝업 또는 UI가 닫히거나 click 이벤트가 중복 실행되는 문제는 아래와 같은 원인으로 발생한다.

## 주요 문제 원인

### 1. 터치 시 `touchstart → touchend → click` 이벤트가 연속 발생

모바일 브라우저는 터치 입력 후 약 300ms 뒤 자동으로 `click` 이벤트를 생성한다.  
그 결과, 터치 1회가 이벤트 두 번으로 처리되어 UI가 닫히는 버그가 발생한다.

### 2. input, textarea, select 같은 동적으로 높이가 변하는 요소에서 blur나 포커스 전환 문제가 발생

모바일 키보드가 올라오면서 강제로 blur 이벤트나 레이아웃 재계산이 일어나  
팝업 닫기 조건이 발동되는 경우가 특히 자주 발생한다.

### 3. 자동 계산 이벤트가 입력 제어를 빼앗는 문제

모바일에서 input 이벤트가 발생하는 순간 즉시 실행되는 자동 계산 함수들이 DOM을 조작하면서 포커스를 잃게 만든다.

---

## 문제 해결 전략

### 1. Pointer 이벤트로 통합 처리 (가장 권장)

마우스, 터치, 펜 입력을 모두 `pointerdown` 하나로 통합해 이벤트 중복 문제를 근본적으로 제거한다.

```js
element.addEventListener('pointerdown', handleInput);
```

**장점:**
- 마우스와 터치를 하나의 이벤트로 처리
- 이벤트 중복 문제 자동 해결
- 코드 단순화

**단점:**
- 구형 브라우저 지원 필요 시 polyfill 필요

---

### 2. 터치 이벤트 전파 차단 및 포커스 관리

모바일에서 터치 이벤트의 전파를 차단하고 포커스를 명시적으로 관리한다.

#### 2.1 터치 이벤트 전파 차단

```js
// touchstart 이벤트 처리
$(document).on('touchstart', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    
    if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
        // 입력 시작 플래그 설정
        isMobileInputActive = true;
        activeMobileInputElement = this;
        
        // 포커스 설정 (지연 후 재확인)
        var self = this;
        setTimeout(function() {
            if (self && document.body.contains(self)) {
                self.focus();
                // 포커스가 실제로 설정되었는지 확인
                if (document.activeElement !== self) {
                    self.focus();
                }
            }
        }, 50);
    }
    
    // preventDefault는 선택적으로만 사용 (키보드가 나오도록)
    return true;
}, { passive: false, capture: true });
```

#### 2.2 touchend 이벤트 처리

```js
$(document).on('touchend', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    
    if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
        // 포커스 유지
        var self = this;
        setTimeout(function() {
            if (self && document.body.contains(self)) {
                self.focus();
            }
        }, 10);
    }
    
    return true;
}, { passive: false, capture: true });
```

#### 2.3 touchmove 이벤트 처리

```js
$(document).on('touchmove', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
    e.stopPropagation();
    // 스크롤을 허용하기 위해 preventDefault는 사용하지 않음
}, { passive: true, capture: true });
```

#### 2.4 click 이벤트 처리

```js
$(document).on('click', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    
    if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
        isMobileInputActive = true;
        activeMobileInputElement = this;
        
        var self = this;
        setTimeout(function() {
            if (self && document.body.contains(self)) {
                self.focus();
            }
        }, 10);
    }
    
    return false;
}, { capture: true });
```

#### 2.5 focus 이벤트 처리

```js
$(document).on('focus', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    
    if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
        isMobileInputActive = true;
        activeMobileInputElement = this;
    }
}, { capture: true });
```

#### 2.6 blur 이벤트 처리 (키보드가 사라지지 않도록)

```js
$(document).on('blur', '.mobile-card input, .mobile-card textarea', function(e) {
    if (!isMobileDevice()) {
        return; // 모바일이 아니면 일반 처리
    }
    
    // 포커스가 다른 입력 필드로 이동하는 경우는 허용
    var relatedTarget = e.relatedTarget;
    if (relatedTarget && (relatedTarget.tagName === 'INPUT' || relatedTarget.tagName === 'TEXTAREA' || relatedTarget.tagName === 'SELECT')) {
        isMobileInputActive = true;
        activeMobileInputElement = relatedTarget;
        return;
    }
    
    // 입력 중이면 포커스 복원 시도
    if (isMobileInputActive && activeMobileInputElement === this) {
        var self = this;
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        setTimeout(function() {
            if (self && document.body.contains(self)) {
                // 포커스 복원 시도
                self.focus();
                // 여전히 포커스가 없으면 다시 시도
                if (document.activeElement !== self) {
                    setTimeout(function() {
                        if (self && document.body.contains(self)) {
                            self.focus();
                        }
                    }, 50);
                }
            }
        }, 10);
        
        return false;
    }
}, { capture: true, passive: false });
```

---

### 3. 모바일 입력 상태 추적 플래그

입력 중인지 추적하는 전역 플래그를 사용하여 입력 중에는 DOM 조작을 차단한다.

```js
// 모바일 입력 중 플래그 (전역 변수)
var isMobileInputActive = false;
var activeMobileInputElement = null;
```

**사용 예시:**
- `renderMobileCards()` 함수에서 입력 중이면 렌더링하지 않음
- `MutationObserver`에서 입력 중이면 DOM 변경 감지하지 않음
- 계산 함수에서 입력 중이면 실행 지연

---

### 4. 모바일 전용 계산 지연 처리

모바일에서는 입력이 완전히 끝날 때까지 계산을 지연시킨다.

#### 4.1 Debounce 함수

```js
// 모바일 입력 계산용 debounce 함수 (입력이 끝날 때까지 대기)
var mobileInputCalculationTimeouts = {};

function debounceMobileCalculation(inputId, calculationFunc, wait) {
    // 기존 타이머 취소
    if (mobileInputCalculationTimeouts[inputId]) {
        clearTimeout(mobileInputCalculationTimeouts[inputId]);
    }
    
    // 새 타이머 설정
    mobileInputCalculationTimeouts[inputId] = setTimeout(function() {
        calculationFunc();
        delete mobileInputCalculationTimeouts[inputId];
    }, wait);
}
```

#### 4.2 모바일 환경 감지

```js
// 모바일 환경 감지
function isMobileDevice() {
    return window.innerWidth <= 768 || 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}
```

#### 4.3 PC/모바일 분기 처리

```js
// PC용 즉시 계산 함수
function executeCalculationPC($input, row, cost_row) {
    // 즉시 계산 실행
    calculateItemAmount(row);
    updateTotals();
}

// 모바일용 지연 계산 함수
function executeCalculationMobile($input, row, cost_row) {
    // 입력 중 플래그 확인 - 입력 중이면 계산하지 않음
    if (isMobileInputActive && activeMobileInputElement && activeMobileInputElement !== $input[0]) {
        return;
    }
    
    // 입력이 완전히 끝난 후에만 계산 실행
    setTimeout(function() {
        // 다시 한번 입력 중인지 확인
        if (isMobileInputActive && activeMobileInputElement && activeMobileInputElement !== $input[0]) {
            return;
        }
        
        executeCalculationPC($input, row, cost_row);
    }, 100);
}

// 공통 계산 함수 (PC/모바일 분기)
function executeCalculation($input, row, cost_row) {
    if (isMobileDevice()) {
        executeCalculationMobile($input, row, cost_row);
    } else {
        executeCalculationPC($input, row, cost_row);
    }
}
```

#### 4.4 Input 이벤트 처리

```js
$(document).on('input', '.quantity-input, .unit-price-input', function() {
    var $input = $(this);
    var inputId = $input.attr('id') || $input.attr('name') || 'input-' + Math.random().toString(36).substr(2, 9);
    
    // 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms)
    if (isMobileDevice()) {
        // 입력 중이면 계산하지 않음
        if (isMobileInputActive && activeMobileInputElement === this) {
            debounceMobileCalculation(inputId, function() {
                // 입력이 완전히 끝난 후에만 계산
                if (!isMobileInputActive || activeMobileInputElement !== $input[0]) {
                    executeCalculationPC($input, row, cost_row);
                }
            }, 800);
        } else {
            debounceMobileCalculation(inputId, function() {
                executeCalculationPC($input, row, cost_row);
            }, 800);
        }
    } else {
        // PC 환경에서는 즉시 계산
        executeCalculationPC($input, row, cost_row);
    }
});
```

#### 4.5 Blur 이벤트에서 즉시 계산

```js
$(document).on('blur', '.quantity-input, .unit-price-input', function() {
    if (isMobileDevice()) {
        var $input = $(this);
        var inputId = $input.attr('id') || $input.attr('name') || 'input-' + Math.random().toString(36).substr(2, 9);
        
        // 입력 종료 플래그 설정 (약간의 지연 후)
        setTimeout(function() {
            // 포커스가 다른 입력 필드로 이동하지 않았으면 입력 종료
            var currentActive = document.activeElement;
            if (!currentActive || (currentActive.tagName !== 'INPUT' && currentActive.tagName !== 'TEXTAREA' && currentActive.tagName !== 'SELECT')) {
                isMobileInputActive = false;
                activeMobileInputElement = null;
            }
        }, 200);
        
        // 해당 input의 대기 중인 계산 즉시 실행
        if (mobileInputCalculationTimeouts[inputId]) {
            clearTimeout(mobileInputCalculationTimeouts[inputId]);
            delete mobileInputCalculationTimeouts[inputId];
        }
        
        var row = $input.closest('.item-row').data('row');
        // blur 시에는 입력이 끝났으므로 PC 함수 직접 호출
        executeCalculationPC($input, row, cost_row);
    }
});
```

---

### 5. MutationObserver에서 입력 중 차단

입력 중에는 DOM 변경 감지를 차단하여 불필요한 재렌더링을 방지한다.

```js
// MutationObserver로 테이블 변경 감지
if (window.innerWidth <= 768) {
    var observer = new MutationObserver(function(mutations) {
        // 입력 중이면 renderMobileCards 호출하지 않음
        if (isMobileInputActive && activeMobileInputElement) {
            return; // 입력 중에는 렌더링하지 않음
        }
        
        // input 필드에 포커스가 있으면 renderMobileCards 호출하지 않음
        var activeElement = document.activeElement;
        if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA' || activeElement.tagName === 'SELECT')) {
            return; // 포커스가 있는 동안은 렌더링하지 않음
        }
        
        // ... 나머지 로직
    });
}
```

---

### 6. renderMobileCards 함수에서 입력 중 차단

```js
function renderMobileCards() {
    // 이미 렌더링 중이면 무시
    if (isRenderingCards) {
        return;
    }
    
    // 입력 중이면 렌더링하지 않음
    if (isMobileInputActive && activeMobileInputElement) {
        return;
    }
    
    // 현재 포커스된 요소 저장 (input 필드인 경우)
    var activeElement = document.activeElement;
    
    if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA')) {
        // 입력 중이면 렌더링하지 않음
        if (isMobileInputActive && activeMobileInputElement === activeElement) {
            return;
        }
        
        // ... 포커스 저장 로직
    }
    
    // ... 나머지 렌더링 로직
}
```

---

## 구현 체크리스트

### 필수 구현 항목

- [ ] 모바일 입력 상태 추적 플래그 (`isMobileInputActive`, `activeMobileInputElement`)
- [ ] 터치 이벤트 전파 차단 (`touchstart`, `touchend`, `touchmove`)
- [ ] Click 이벤트 전파 차단 및 포커스 설정
- [ ] Focus 이벤트에서 입력 시작 플래그 설정
- [ ] Blur 이벤트에서 포커스 복원 및 입력 종료 플래그 설정
- [ ] 모바일 환경 감지 함수 (`isMobileDevice()`)
- [ ] 모바일 입력 계산용 debounce 함수 (`debounceMobileCalculation()`)
- [ ] PC/모바일 분기 처리 함수 (`executeCalculationPC()`, `executeCalculationMobile()`)
- [ ] Input 이벤트에서 모바일 debounce 적용
- [ ] Blur 이벤트에서 즉시 계산 실행
- [ ] MutationObserver에서 입력 중 차단
- [ ] renderMobileCards 함수에서 입력 중 차단

### 권장 구현 항목

- [ ] Pointer 이벤트로 통합 처리 (최신 브라우저 지원 시)
- [ ] 입력 중 플래그 자동 해제 타이머
- [ ] 포커스 복원 재시도 로직 강화

---

## 주의사항

1. **passive 옵션 사용 시 주의**
   - `touchstart`, `touchend`에서 `preventDefault()`가 필요하면 `passive: false` 사용
   - `touchmove`에서 스크롤을 허용하려면 `passive: true` 사용

2. **이벤트 전파 차단 순서**
   - `stopImmediatePropagation()` → `stopPropagation()` → `preventDefault()` 순서로 적용

3. **포커스 복원 타이밍**
   - `setTimeout`을 사용하여 DOM 조작 후 포커스 복원
   - 여러 번 재시도하여 확실히 포커스 복원

4. **입력 종료 플래그 해제**
   - Blur 이벤트에서 약간의 지연 후 플래그 해제 (다른 입력 필드로 이동하는 경우 대비)

5. **Debounce 시간 조정**
   - 일반 입력: 800ms
   - Select 변경: 300-500ms
   - 테이블 컬럼 너비 조절: 800ms

---

## 참고 사례

### 실제 적용 예시 (write_form.php)

```js
// 모바일 입력 중 플래그
var isMobileInputActive = false;
var activeMobileInputElement = null;

// 모바일 환경 감지
function isMobileDevice() {
    return window.innerWidth <= 768 || 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}

// 모바일 입력 계산용 debounce
var mobileInputCalculationTimeouts = {};

function debounceMobileCalculation(inputId, calculationFunc, wait) {
    if (mobileInputCalculationTimeouts[inputId]) {
        clearTimeout(mobileInputCalculationTimeouts[inputId]);
    }
    
    mobileInputCalculationTimeouts[inputId] = setTimeout(function() {
        calculationFunc();
        delete mobileInputCalculationTimeouts[inputId];
    }, wait);
}

// 터치 이벤트 처리
$(document).on('touchstart', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    
    if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
        isMobileInputActive = true;
        activeMobileInputElement = this;
        
        var self = this;
        setTimeout(function() {
            if (self && document.body.contains(self)) {
                self.focus();
                if (document.activeElement !== self) {
                    self.focus();
                }
            }
        }, 50);
    }
    
    return true;
}, { passive: false, capture: true });

// Input 이벤트 처리
$(document).on('input', '.quantity-input, .unit-price-input', function() {
    var $input = $(this);
    var inputId = $input.attr('id') || $input.attr('name') || 'input-' + Math.random().toString(36).substr(2, 9);
    
    if (isMobileDevice()) {
        if (isMobileInputActive && activeMobileInputElement === this) {
            debounceMobileCalculation(inputId, function() {
                if (!isMobileInputActive || activeMobileInputElement !== $input[0]) {
                    executeCalculationPC($input, row, cost_row);
                }
            }, 800);
        } else {
            debounceMobileCalculation(inputId, function() {
                executeCalculationPC($input, row, cost_row);
            }, 800);
        }
    } else {
        executeCalculationPC($input, row, cost_row);
    }
});
```

---

## 결론

모바일에서 터치 입력 시 키보드가 바로 닫히는 문제는 다음과 같은 전략으로 해결할 수 있다:

1. **터치 이벤트 전파 차단**: `stopPropagation()`, `stopImmediatePropagation()` 사용
2. **포커스 명시적 관리**: `touchstart`, `touchend`, `click`, `focus` 이벤트에서 포커스 설정
3. **Blur 이벤트 차단**: 입력 중에는 blur를 차단하고 포커스 복원
4. **입력 상태 추적**: 전역 플래그로 입력 중인지 추적
5. **계산 지연 처리**: 모바일에서는 입력이 끝날 때까지 계산을 지연
6. **DOM 조작 차단**: 입력 중에는 MutationObserver와 renderMobileCards 차단

이러한 전략을 통해 모바일과 데스크톱에서 동일한 사용자 경험을 제공할 수 있다.

