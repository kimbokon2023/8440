<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hover 카드 UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hover-card {
            position: absolute;
            visibility: hidden;
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 16px;
            width: 260px;
        }

        .hover-trigger:hover .hover-card {
            visibility: visible;
            opacity: 1;
            transform: scale(1);
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
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <!-- Hover 카드 트리거 -->
    <div class="relative hover-trigger p-4 bg-white shadow-lg rounded-lg cursor-pointer">
        <img src="https://via.placeholder.com/150" alt="이미지" class="rounded-lg">
        <p class="text-center mt-2 text-sm font-semibold">블랙커피 Vanilla JS Lv1</p>
        
        <!-- Hover 시 나타날 카드 -->
        <div class="hover-card" data-position="right">
            <h3 class="font-bold text-lg">블랙커피 Vanilla JS Lv1</h3>
            <p class="text-gray-600 text-sm mt-2">Vanilla Javascript로 만들어보는 상태관리 카페 앱</p>
            <ul class="mt-2 text-sm text-gray-700 space-y-1">
                <li>✔️ 자바스크립트 상태관리</li>
                <li>✔️ 자바스크립트 이벤트 처리</li>
                <li>✔️ 웹서버와의 비동기 통신</li>
            </ul>
            <button class="mt-3 w-full bg-purple-600 text-white py-2 rounded-lg text-sm hover:bg-purple-700">장바구니에 추가</button>
        </div>
    </div>

</body>
</html>
