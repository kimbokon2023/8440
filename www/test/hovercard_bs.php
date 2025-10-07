<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hover 카드 UI</title>
    <!-- Bootstrap 5.3 CSS (이미 포함된 상태) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hover-card {
            position: absolute;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out, visibility 0.3s;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 16px;
            width: 260px;
            z-index: 100;
        }

        .hover-trigger:hover .hover-card {
            visibility: visible;
            opacity: 1;
        }

        /* 방향 설정 */
        .hover-card[data-position="up"] {
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
        }

        .hover-card[data-position="down"] {
            top: 110%;
            left: 50%;
            transform: translateX(-50%);
        }

        .hover-card[data-position="right"] {
            left: 110%;
            top: 50%;
            transform: translateY(-50%);
        }

        .hover-card[data-position="left"] {
            right: 110%;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <!-- Hover 카드 트리거 -->
    <div class="position-relative hover-trigger p-3 bg-white shadow-sm rounded cursor-pointer">
        <img src="https://via.placeholder.com/150" alt="이미지" class="rounded">
        <p class="text-center mt-2 fw-semibold">블랙커피 Vanilla JS Lv1</p>
        
        <!-- Hover 시 나타날 카드 -->		
        <div class="hover-card" data-position="up"> 
            <h3 class="fw-bold fs-6">블랙커피 Vanilla JS Lv1 위쪽</h3>
            <p class="text-muted small mt-2">Vanilla Javascript로 만들어보는 상태관리 카페 앱</p>
            <ul class="mt-2 small text-secondary">
                <li>✔️ 자바스크립트 상태관리</li>
                <li>✔️ 자바스크립트 이벤트 처리</li>
                <li>✔️ 웹서버와의 비동기 통신</li>
            </ul>
            <button class="mt-3 w-100 btn btn-primary btn-sm">장바구니에 추가</button>
        </div>
        <div class="hover-card" data-position="right">
            <h3 class="fw-bold fs-6">블랙커피 Vanilla JS Lv1 오른쪽</h3>
            <p class="text-muted small mt-2">Vanilla Javascript로 만들어보는 상태관리 카페 앱</p>
            <ul class="mt-2 small text-secondary">
                <li>✔️ 자바스크립트 상태관리</li>
                <li>✔️ 자바스크립트 이벤트 처리</li>
                <li>✔️ 웹서버와의 비동기 통신</li>
            </ul>
            <button class="mt-3 w-100 btn btn-primary btn-sm">장바구니에 추가</button>
        </div>
        <div class="hover-card" data-position="down">
            <h3 class="fw-bold fs-6">블랙커피 Vanilla JS Lv1 아래</h3>
            <p class="text-muted small mt-2">Vanilla Javascript로 만들어보는 상태관리 카페 앱</p>
            <ul class="mt-2 small text-secondary">
                <li>✔️ 자바스크립트 상태관리</li>
                <li>✔️ 자바스크립트 이벤트 처리</li>
                <li>✔️ 웹서버와의 비동기 통신</li>
            </ul>
            <button class="mt-3 w-100 btn btn-primary btn-sm">장바구니에 추가</button>
        </div>
        <div class="hover-card" data-position="left">
            <h3 class="fw-bold fs-6">블랙커피 Vanilla JS Lv1 왼쪽</h3>
            <p class="text-muted small mt-2">Vanilla Javascript로 만들어보는 상태관리 카페 앱</p>
            <ul class="mt-2 small text-secondary">
                <li>✔️ 자바스크립트 상태관리</li>
                <li>✔️ 자바스크립트 이벤트 처리</li>
                <li>✔️ 웹서버와의 비동기 통신</li>
            </ul>
            <button class="mt-3 w-100 btn btn-primary btn-sm">장바구니에 추가</button>
        </div>
    </div>

</body>
</html>
