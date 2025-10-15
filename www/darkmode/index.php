<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>다크모드 예제</title>
    <style>
        :root {
            --background-color: #ffffff;
            --text-color: #000000;
            --toggle-bg: #ccc;
            --toggle-slider: #ffffff;
        }
        
        [data-theme="dark"] {
            --background-color: #1a1a1a;
            --text-color: #ffffff;
            --toggle-bg: #4a4a4a;
            --toggle-slider: #f1f1f1;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        h1 {
            margin-bottom: 30px;
            font-size: 2.5rem;
            text-align: center;
        }
        
        .container {
            text-align: center;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: var(--background-color);
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            cursor: pointer;
        }
        
        .toggle-switch input[type="checkbox"] {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--toggle-bg);
            transition: 0.4s;
            border-radius: 34px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: var(--toggle-slider);
            transition: 0.4s;
            border-radius: 50%;
        }
        
        .toggle-switch input:checked + .toggle-slider {
            background-color: #2196F3;
        }
        
        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }
        
        .toggle-label {
            display: block;
            margin-top: 15px;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .info {
            margin-top: 30px;
            padding: 20px;
            border-radius: 5px;
            background-color: rgba(128, 128, 128, 0.1);
        }
        
        .info p {
            margin: 10px 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>다크모드 예제</h1>
        
        <label class="toggle-switch">
            <input type="checkbox" id="theme-toggle" aria-label="다크모드 전환">
            <span class="toggle-slider"></span>
        </label>
        <span class="toggle-label" id="theme-label">라이트 모드</span>
        
        <div class="info">
            <p>토글 스위치를 클릭하여 다크모드를 활성화/비활성화할 수 있습니다.</p>
            <p>선택한 테마는 localStorage에 저장되어 다음 방문 시에도 유지됩니다.</p>
        </div>
    </div>
    
    <script>
        // ES5 호환 JavaScript (IE11+ 지원)
        (function() {
            // DOM 요소 선택
            var toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
            var themeLabel = document.getElementById('theme-label');
            var currentTheme = localStorage.getItem('theme');
            
            // 초기 테마 설정
            if (currentTheme) {
                document.documentElement.setAttribute('data-theme', currentTheme);
                
                if (currentTheme === 'dark') {
                    toggleSwitch.checked = true;
                    updateLabel(true);
                }
            } else {
                // 기본값: 시스템 설정 확인 (ES5 호환)
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    toggleSwitch.checked = true;
                    updateLabel(true);
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    updateLabel(false);
                }
            }
            
            // 테마 전환 함수
            function switchTheme(e) {
                if (e.target.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    updateLabel(true);
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    updateLabel(false);
                }
            }
            
            // 라벨 업데이트 함수
            function updateLabel(isDark) {
                if (themeLabel) {
                    themeLabel.textContent = isDark ? '다크 모드' : '라이트 모드';
                }
            }
            
            // 이벤트 리스너 등록
            toggleSwitch.addEventListener('change', switchTheme, false);
            
            // 시스템 테마 변경 감지 (선택사항)
            if (window.matchMedia) {
                var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                // IE11은 addEventListener를 지원하지 않으므로 addListener 사용
                if (mediaQuery.addEventListener) {
                    mediaQuery.addEventListener('change', function(e) {
                        var newTheme = e.matches ? 'dark' : 'light';
                        document.documentElement.setAttribute('data-theme', newTheme);
                        localStorage.setItem('theme', newTheme);
                        toggleSwitch.checked = e.matches;
                        updateLabel(e.matches);
                    });
                } else if (mediaQuery.addListener) {
                    // IE11 호환
                    mediaQuery.addListener(function(e) {
                        var newTheme = e.matches ? 'dark' : 'light';
                        document.documentElement.setAttribute('data-theme', newTheme);
                        localStorage.setItem('theme', newTheme);
                        toggleSwitch.checked = e.matches;
                        updateLabel(e.matches);
                    });
                }
            }
        })();
    </script>
</body>
</html>