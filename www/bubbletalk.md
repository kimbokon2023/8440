# 말풍선(Bubble Talk) 개발 가이드

## 개요
웹 페이지에 사용자 알림을 위한 말풍선 형태의 UI 컴포넌트를 구현하는 방법을 정리한 문서입니다.

## 구현된 기능
1. **택배 알림 말풍선**: 화물/택배 출고 알림
2. **식사주문 알림 말풍선**: 오전 10시 이후 중식 주문 알림

## 1. CSS 스타일링

### 기본 말풍선 스타일
```css
/* 기본 말풍선 */
.bubble-talk {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    z-index: 1000;
    animation: float 3s ease-in-out infinite;
    cursor: pointer;
    max-width: 250px;
    text-align: center;
}
```

### 말풍선 꼬리 (화살표)
```css
.bubble-talk::before {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 20px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid #764ba2;
}
```

### 닫기 버튼 스타일
```css
.bubble-talk .close-btn {
    position: absolute;
    top: 5px;
    right: 8px;
    font-size: 18px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.bubble-talk .close-btn:hover {
    opacity: 1;
    transform: scale(1.1);
}
```

### 아이콘 애니메이션
```css
.bubble-talk .icon {
    font-size: 16px;
    margin-right: 6px;
    animation: bounce 2s infinite;
}
```

### 애니메이션 키프레임
```css
@keyframes float {
    0%, 100% { transform: translateY(0px); } 
    50% { transform: translateY(-10px); }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-3px); }
    60% { transform: translateY(-2px); }
}
```

## 2. HTML 구조

### 기본 HTML 템플릿
```html
<!-- 말풍선 기본 구조 -->
<div class="bubble-talk" id="bubbleTalkId">
    <span class="icon">📦</span>
    메시지 내용
    <span class="close-btn" onclick="closeBubbleTalk()">×</span>
</div>
```

### 실제 구현 예시
```html
<!-- 택배 알림 말풍선 -->
<div class="delivery-reminder" id="deliveryReminder">
    <span class="icon">📦</span>
    금일 화물/택배가 있어요!
    <span class="close-btn" onclick="closeDeliveryReminder()">×</span>
</div>

<!-- 식사주문 알림 말풍선 -->
<div class="lunch-reminder" id="lunchReminder">
    <span class="icon">🍽️</span>
    식사주문해 주세요!
    <span class="close-btn" onclick="closeLunchReminder()">×</span>
</div>
```

## 3. JavaScript 기능

### 기본 닫기 함수
```javascript
function closeBubbleTalk() {
    $('#bubbleTalkId').fadeOut(300);
}
```

### 시간 기반 조건부 표시
```javascript
function checkCondition() {
    // 한국시간 현재 시간 가져오기
    const now = new Date();
    const koreaTime = new Date(now.getTime() + (9 * 60 * 60 * 1000)); // UTC+9
    const currentHour = koreaTime.getHours();
    
    // 조건 확인
    if (currentHour >= 10) {
        // 조건에 맞으면 말풍선 표시
        $('#bubbleTalkId').fadeIn(300);
    } else {
        // 조건에 맞지 않으면 말풍선 숨김
        $('#bubbleTalkId').fadeOut(300);
    }
}

// 주기적 확인 (10분마다)
setInterval(checkCondition, 10 * 60 * 1000);

// 페이지 로드 시 즉시 확인
$(document).ready(function() {
    checkCondition();
});
```

### 클릭 이벤트 처리
```javascript
$(document).ready(function() {
    // 말풍선 클릭 시 특정 동작
    $('#bubbleTalkId').click(function(e) {
        // X 버튼 클릭이 아닌 경우에만 동작
        if (!$(e.target).hasClass('close-btn')) {
            // 원하는 동작 수행
            performAction();
        }
    });
});
```

## 4. 색상 테마

### 다양한 색상 조합
```css
/* 보라색 계열 (기본) */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* 빨간색 계열 (경고) */
background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);

/* 청록색 계열 (정보) */
background: linear-gradient(135deg, #20b2aa 0%, #17a2b8 100%);

/* 초록색 계열 (성공) */
background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);

/* 주황색 계열 (주의) */
background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
```

## 5. 위치 조정

### 여러 말풍선 배치
```css
/* 첫 번째 말풍선 */
.bubble-talk-1 {
    top: 20px;
    right: 20px;
}

/* 두 번째 말풍선 */
.bubble-talk-2 {
    top: 80px;
    right: 20px;
}

/* 세 번째 말풍선 */
.bubble-talk-3 {
    top: 140px;
    right: 20px;
}
```

## 6. 실제 구현 예시

### 택배 알림 말풍선
- **조건**: 항상 표시
- **색상**: 보라색 그라데이션
- **아이콘**: 📦
- **기능**: 클릭 시 테이블 하이라이트

### 식사주문 알림 말풍선
- **조건**: 오전 10시 이후 + 중식 주문 없음
- **색상**: 청록색 그라데이션
- **아이콘**: 🍽️
- **기능**: 10분마다 자동 확인

## 7. 개발 팁

### 1. 접근성 고려
- `z-index`를 충분히 높게 설정 (1000 이상)
- 키보드 접근성 고려
- 스크린 리더 호환성

### 2. 반응형 디자인
- 모바일에서도 적절한 크기 유지
- `max-width` 설정으로 긴 텍스트 처리

### 3. 성능 최적화
- `setInterval` 대신 필요한 시점에만 확인
- 불필요한 DOM 조작 최소화

### 4. 사용자 경험
- 적절한 애니메이션 시간 (300ms)
- 명확한 닫기 버튼 제공
- 중복 알림 방지

## 8. 확장 가능성

### 추가 기능 아이디어
1. **자동 사라짐**: 일정 시간 후 자동 숨김
2. **사운드 알림**: 말풍선 표시 시 소리 재생
3. **진동 알림**: 모바일에서 진동 피드백
4. **다크모드**: 다크 테마 지원
5. **다국어**: 여러 언어 지원

### 커스터마이징 옵션
- 아이콘 변경
- 메시지 내용 동적 변경
- 색상 테마 선택
- 위치 조정
- 애니메이션 효과 변경

## 9. 브라우저 호환성

### 지원 브라우저
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

### 폴백 처리
```css
/* CSS Grid 미지원 브라우저용 */
.bubble-talk {
    display: block; /* 기본값 */
    display: flex; /* 지원 브라우저 */
}
```

## 10. 디버깅 가이드

### 일반적인 문제
1. **말풍선이 보이지 않음**: `z-index` 확인
2. **위치가 이상함**: `position: fixed` 확인
3. **애니메이션이 작동하지 않음**: CSS 키프레임 확인
4. **JavaScript 오류**: 콘솔에서 오류 메시지 확인

### 개발 도구 활용
- 브라우저 개발자 도구로 CSS 디버깅
- `console.log()`로 JavaScript 디버깅
- 네트워크 탭으로 AJAX 요청 확인

---

이 문서를 참고하여 다양한 말풍선 알림 기능을 구현할 수 있습니다. 필요에 따라 스타일과 기능을 커스터마이징하여 사용하세요.
