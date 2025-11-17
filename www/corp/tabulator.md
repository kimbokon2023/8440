# Tabulator 행 클릭 이벤트 정리

이 문서는 `list.php` 파일에서 사용되는 Tabulator 테이블의 행 클릭 이벤트 처리 방식을 정리한 것입니다.

## 목차
1. [구매 테이블 (purchaseTable)](#구매-테이블-purchasetable)
2. [재고 테이블 (stockTable)](#재고-테이블-stocktable)
3. [출고 테이블 (outgoingTable)](#출고-테이블-outgoingtable)

---

## 구매 테이블 (purchaseTable)

### 1. 행 더블클릭 이벤트 (rowDblClick)

```4166:4176:purchase/list.php
window.purchaseTable.on("rowDblClick", function(e, row){
	const data = row.getData();
	// PurchaseManager가 로드되어 있으면 사용, 아니면 직접 호출
	if (typeof PurchaseManager !== 'undefined' && PurchaseManager.viewPurchase) {
		PurchaseManager.viewPurchase(data.num);
	} else if (typeof viewpurchase === 'function') {
		viewpurchase(data.num);
	} else {
		console.error('구매 보기 함수를 찾을 수 없습니다.');
	}
});
```

**동작 방식:**
- 행을 더블클릭하면 해당 구매 내역의 상세 정보를 보여줍니다.
- `PurchaseManager.viewPurchase()` 함수가 있으면 우선 사용하고, 없으면 `viewpurchase()` 함수를 호출합니다.
- `data.num` 값을 전달하여 특정 구매 내역을 식별합니다.

### 2. 행 클릭 이벤트 (rowClick)

```4312:4325:purchase/list.php
let rowClickLock = false;
window.purchaseTable.on('rowClick', function(e, row){
	if (rowClickLock) return; // 두번 클릭 방지
	rowClickLock = true;
	setTimeout(function(){ rowClickLock = false; }, 100); // 0.1초 후 해제

	if (typeof console !== 'undefined') {
// console.log('rowClick fired:', row && row.getData && row.getData());
	}
	const d = row.getData();
	if (typeof openPurchaseEditor === 'function') {
		openPurchaseEditor(d && d.num);
	}
});
```

**동작 방식:**
- 행을 클릭하면 구매 편집기를 엽니다.
- 중복 클릭 방지 로직이 포함되어 있습니다 (`rowClickLock`).
- 클릭 후 0.1초 동안 추가 클릭을 무시합니다.
- `openPurchaseEditor()` 함수에 `data.num` 값을 전달합니다.

---

## 재고 테이블 (stockTable)

### 1. 행 클릭 이벤트 (rowClick) - 테이블 설정 내부

```4810:4831:purchase/list.php
rowClick: function(e, row) {
	// 체크박스 클릭인지 확인 (체크박스 영역 클릭 시 팝업 열지 않음)
	if (e.target && (e.target.classList.contains('cart-checkbox') || e.target.type === 'checkbox' || 
		e.target.classList.contains('outbound-cart-checkbox') || e.target.classList.contains('form-check-input'))) {
// console.log('🔍 체크박스 클릭 감지 - 팝업 열지 않음');
		// 체크박스 클릭 시 change 이벤트는 자연스럽게 발생하도록 함
		return;
	}
	
	// 재고 추적 팝업 열기
// console.log('🔍 STEP 7: 재고 테이블 행 클릭됨 (모바일:', isMobile, ')');
	const data = row.getData();
// console.log('🔍 행 데이터:', data);
	
	if (typeof window.openStockTracker === 'function') {
// console.log('✅ STEP 8: openStockTracker 함수 존재, 호출 시작');
		window.openStockTracker(data);
	} else {
		console.error('❌ STEP 8: openStockTracker 함수가 존재하지 않음');
// console.log('🔍 window 객체 확인:', typeof window.openStockTracker);
	}
},
```

**동작 방식:**
- 행을 클릭하면 재고 추적 팝업을 엽니다.
- 체크박스 영역을 클릭한 경우 팝업을 열지 않고 체크박스 동작만 수행합니다.
- `openStockTracker()` 함수에 행 데이터 전체를 전달합니다.

### 2. 행 터치 이벤트 (rowTap) - 모바일 지원

```4833:4851:purchase/list.php
// 모바일 터치 지원을 위한 rowTap 이벤트도 추가
rowTap: function(e, row) {
	// 모바일에서 터치 시에도 동일한 로직 적용
	if (e.target && (e.target.classList.contains('cart-checkbox') || e.target.type === 'checkbox' || 
		e.target.classList.contains('outbound-cart-checkbox') || e.target.classList.contains('form-check-input'))) {
// console.log('🔍 모바일 체크박스 터치 감지 - 팝업 열지 않음');
		return;
	}
	
// console.log('🔍 모바일 재고 테이블 터치됨');
	const data = row.getData();
// console.log('🔍 터치 데이터:', data);
	
	if (typeof window.openStockTracker === 'function') {
// console.log('✅ 모바일 openStockTracker 함수 호출');
		window.openStockTracker(data);
	} else {
		console.error('❌ 모바일 openStockTracker 함수 없음');
	}
},
```

**동작 방식:**
- 모바일 기기에서 터치 이벤트를 처리합니다.
- `rowClick`과 동일한 로직을 적용합니다.

### 3. 행 클릭 이벤트 (rowClick) - 수동 바인딩

```5163:5182:purchase/list.php
// 행 클릭 이벤트 수동 바인딩 (Tabulator 객체 생성 후)
window.stockTable.on("rowClick", function(e, row) {
	// 체크박스 클릭인지 확인 (체크박스 영역 클릭 시 팝업 열지 않음)
	if (e.target && (e.target.classList.contains('cart-checkbox') || e.target.type === 'checkbox')) {
// console.log('🔍 체크박스 클릭 감지 - 팝업 열지 않음 (수동 바인딩)');
		// 체크박스 클릭 시 change 이벤트는 자연스럽게 발생하도록 함
		return;
	}
	
// console.log('🔍 STEP 7: 재고 테이블 행 클릭됨 (수동 바인딩)');
	const data = row.getData();
// console.log('🔍 행 데이터:', data);
	
	if (typeof window.openStockTracker === 'function') {
// console.log('✅ STEP 8: openStockTracker 함수 존재, 호출 시작');
		window.openStockTracker(data);
	} else {
		console.error('❌ STEP 8: openStockTracker 함수가 존재하지 않음');
// console.log('🔍 window 객체 확인:', typeof window.openStockTracker);
	}
});
```

**동작 방식:**
- 테이블 생성 후 수동으로 이벤트를 바인딩합니다.
- 테이블 설정 내부의 `rowClick`과 동일한 동작을 수행합니다.

### 4. 셀 클릭 이벤트 (cellClick) - 체크박스 셀

```4902:4907:purchase/list.php
cellClick: function(e, cell) {
	// 체크박스 셀 클릭 시 이벤트 전파 중단 (팝업 방지)
	// 체크박스 기본 동작은 유지하기 위해 return false 제거
	e.stopPropagation();
	// return false; 제거 - 체크박스 동작 유지
}
```

**동작 방식:**
- 체크박스가 있는 셀을 클릭하면 이벤트 전파를 중단합니다.
- 이로 인해 행 클릭 이벤트가 발생하지 않아 팝업이 열리지 않습니다.
- 체크박스의 기본 동작(체크/언체크)은 정상적으로 작동합니다.

---

## 출고 테이블 (outgoingTable)

### 1. 행 클릭 이벤트 (rowClick)

```5568:5579:purchase/list.php
let rowClickLock = false;
window.outgoingTable.on('rowClick', function(e, row){
	if (rowClickLock) return; // 두번 클릭 방지
	rowClickLock = true;
	setTimeout(function(){ rowClickLock = false; }, 100); // 0.1초 후 해제

// console.log('출고 테이블 rowClick fired:', row && row.getData && row.getData());
	const data = row.getData();
	if (typeof openOutboundEditor === 'function') {
		openOutboundEditor(data && data.num);
	}
});
```

**동작 방식:**
- 행을 클릭하면 출고 편집기를 엽니다.
- 구매 테이블과 동일하게 중복 클릭 방지 로직이 포함되어 있습니다.
- `openOutboundEditor()` 함수에 `data.num` 값을 전달합니다.

### 2. 행 더블클릭 이벤트 (rowDblClick)

```5582:5587:purchase/list.php
// 더블클릭 이벤트도 추가 (구매 테이블과 동일)
window.outgoingTable.on("rowDblClick", function(e, row){
	const data = row.getData();
	if (typeof openOutboundEditor === 'function') {
		openOutboundEditor(data.num);
	}
});
```

**동작 방식:**
- 행을 더블클릭해도 출고 편집기를 엽니다.
- 구매 테이블과 동일한 패턴을 따릅니다.

---

## 공통 패턴

### 중복 클릭 방지
구매 테이블과 출고 테이블에서는 `rowClickLock` 변수를 사용하여 중복 클릭을 방지합니다:
- 클릭 시 `rowClickLock`을 `true`로 설정
- 0.1초 후 `false`로 재설정
- 클릭 중에는 추가 클릭 무시

### 체크박스 클릭 처리
재고 테이블에서는 체크박스 클릭 시 팝업이 열리지 않도록 처리합니다:
- 클릭된 요소가 체크박스인지 확인
- 체크박스인 경우 행 클릭 이벤트를 중단 (`return`)
- 셀 클릭 이벤트에서 `e.stopPropagation()`으로 이벤트 전파 중단

### 함수 존재 여부 확인
모든 이벤트 핸들러에서는 호출할 함수가 존재하는지 확인합니다:
```javascript
if (typeof functionName === 'function') {
	functionName(parameters);
} else {
	console.error('함수를 찾을 수 없습니다.');
}
```

---

## 참고사항

- 모든 테이블은 `window` 객체에 전역 변수로 저장됩니다 (`window.purchaseTable`, `window.stockTable`, `window.outgoingTable`)
- 모바일 환경에서는 일부 테이블이 Tabulator 대신 카드 리스트로 렌더링될 수 있습니다
- 이벤트 핸들러는 테이블 생성 후에 바인딩됩니다

