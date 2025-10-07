<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 IT 사업 메뉴얼</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Malgun Gothic', 'Apple SD Gothic Neo', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* 배경 애니메이션 */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 50px,
                rgba(255,255,255,0.03) 50px,
                rgba(255,255,255,0.03) 100px
            );
            animation: backgroundMove 30s linear infinite;
            z-index: 0;
        }

        @keyframes backgroundMove {
            0% { transform: translateX(-100px) translateY(-100px); }
            100% { transform: translateX(100px) translateY(100px); }
        }

        /* 부유하는 도형들 */
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape1 {
            top: 10%;
            left: 10%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #ff6b6b, #ffa726);
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape2 {
            top: 60%;
            right: 15%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #4ecdc4, #45b7cd);
            transform: rotate(45deg);
            animation-delay: 2s;
        }

        .shape3 {
            bottom: 20%;
            left: 20%;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #96ceb4, #ffeaa7);
            border-radius: 30%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .container {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
            max-width: 1200px;
            width: 100%;
        }

        .header {
            margin-bottom: 80px;
            opacity: 0;
            animation: fadeInUp 1s ease-out 0.5s forwards;
        }

        .company-logo {
            font-size: 3.5em;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
            font-weight: bold;
        }

        .main-title {
            font-size: 2.5em;
            font-weight: bold;
            color: white;
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
            margin-bottom: 15px;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 1.3em;
            color: rgba(255,255,255,0.9);
            font-weight: 300;
            letter-spacing: 1px;
        }

        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
        }

        .menu-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 50px 30px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(50px);
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .menu-item:nth-child(1) { animation: slideInUp 0.8s ease-out 1s forwards; }
        .menu-item:nth-child(2) { animation: slideInUp 0.8s ease-out 1.3s forwards; }
        .menu-item:nth-child(3) { animation: slideInUp 0.8s ease-out 1.6s forwards; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .menu-item:hover::before {
            opacity: 1;
        }

        .menu-item:hover {
            transform: translateY(-10px) scale(1.05);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.25);
        }

        .menu-icon {
            font-size: 4em;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        .menu-item:hover .menu-icon {
            transform: scale(1.2) rotate(5deg);
        }

        .menu-title {
            font-size: 1.6em;
            font-weight: bold;
            color: white;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            line-height: 1.3;
        }

        .menu-description {
            font-size: 1em;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.5;
            text-align: center;
        }

        /* 개별 메뉴 아이템 색상 테마 */
        .menu-item.web-dev:hover {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.3), rgba(155, 89, 182, 0.3));
        }

        .menu-item.elevator-new:hover {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.3), rgba(26, 188, 156, 0.3));
        }

        .menu-item.elevator-panel:hover {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.3), rgba(192, 57, 43, 0.3));
        }

        .pulse-effect {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: pulse 2s infinite;
            pointer-events: none;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.1);
                opacity: 0;
            }
        }

        /* 반응형 디자인 */
        @media (max-width: 768px) {
            .company-logo {
                font-size: 2.5em;
            }
            
            .main-title {
                font-size: 2em;
            }
            
            .subtitle {
                font-size: 1.1em;
            }
            
            .menu-container {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 0 20px;
            }
            
            .menu-item {
                padding: 40px 25px;
                min-height: 180px;
            }
            
            .menu-icon {
                font-size: 3em;
            }
            
            .menu-title {
                font-size: 1.4em;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            .header {
                margin-bottom: 50px;
            }
            
            .company-logo {
                font-size: 2em;
            }
            
            .main-title {
                font-size: 1.6em;
            }
            
            .menu-item {
                padding: 35px 20px;
                min-height: 160px;
            }
        }

        /* 클릭 효과 */
        .menu-item:active {
            transform: translateY(-5px) scale(1.02);
        }

        /* 로딩 스피너 */
        .loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255,255,255,0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
    </div>

    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <div class="container">
        <div class="header">            
            <h1 class="main-title">미래기업 IT 사업 메뉴얼</h1>
            <p class="subtitle">혁신적인 기술로 미래를 설계합니다</p>
        </div>

        <div class="menu-container">
            <div class="menu-item web-dev" onclick="navigateTo('website.html')">
                <div class="pulse-effect"></div>
                <div class="menu-icon">💻</div>
                <h2 class="menu-title">업무용 웹프로그램<br>개발</h2>
                <p class="menu-description">
                    맞춤형 웹 솔루션으로<br>
                    업무 효율성을 극대화합니다
                </p>
            </div>

            <div class="menu-item elevator-new" onclick="navigateTo('elevator_jamb.html')">
                <div class="pulse-effect"></div>
                <div class="menu-icon">🏗️</div>
                <h2 class="menu-title">엘리베이터 신규쟘<br>자동작도</h2>
                <p class="menu-description">
                    신규 엘리베이터 설계를<br>
                    자동화하여 정확성을 보장합니다
                </p>
            </div>

            <div class="menu-item elevator-panel" onclick="navigateTo('elevator_panel.html')">
                <div class="pulse-effect"></div>
                <div class="menu-icon">⚙️</div>
                <h2 class="menu-title">엘리베이터 판넬<br>자동작도</h2>
                <p class="menu-description">
                    판넬 설계 자동화로<br>
                    설계 오류를 획기적으로 감소시킵니다
                </p>
            </div>
        </div>
    </div>

    <script>
        // 페이지 로드 시 애니메이션 효과
        document.addEventListener('DOMContentLoaded', function() {
            // 메뉴 아이템에 호버 효과 추가
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.05)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });

        // 네비게이션 함수
        function navigateTo(url) {
            if (url === '#') {
                // 준비 중인 기능에 대한 알림
                showNotification('해당 기능은 준비 중입니다.', 'info');
                return;
            }
            
            // 로딩 스피너 표시
            const loading = document.getElementById('loading');
            loading.style.display = 'block';
            
            // 클릭 효과 추가
            event.currentTarget.style.transform = 'translateY(-5px) scale(1.02)';
            
            // 약간의 지연 후 페이지 이동 (UX 향상)
            setTimeout(() => {
                window.location.href = url;
            }, 500);
        }

        // 알림 함수
        function showNotification(message, type = 'info') {
            // 기존 알림 제거
            const existingNotification = document.querySelector('.notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            // 새 알림 생성
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                color: #333;
                padding: 20px 30px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 10000;
                font-weight: 500;
                border-left: 4px solid ${type === 'info' ? '#3498db' : '#e74c3c'};
                transform: translateX(400px);
                transition: all 0.3s ease;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // 애니메이션으로 표시
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // 3초 후 제거
            setTimeout(() => {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 3000);
        }

        // 키보드 네비게이션 (접근성 향상)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.classList.contains('menu-item')) {
                e.target.click();
            }
        });

        // 터치 디바이스 지원
        if ('ontouchstart' in window) {
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('touchstart', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                item.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.style.transform = 'translateY(0) scale(1)';
                    }, 150);
                });
            });
        }
    </script>
</body>
</html>