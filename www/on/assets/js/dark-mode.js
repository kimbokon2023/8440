/**
 * 다크모드 토글 및 쿠키 저장 시스템
 */

// 다크모드 토글 함수
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    html.setAttribute('data-theme', newTheme);

    // 쿠키에 테마 저장 (30일 유효)
    const expires = new Date();
    expires.setDate(expires.getDate() + 30);
    document.cookie = `daon_theme=${newTheme}; expires=${expires.toUTCString()}; path=/`;

    // 아이콘 변경
    updateThemeIcon(newTheme);
}

// 테마 아이콘 업데이트
function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    if (icon) {
        if (theme === 'dark') {
            icon.className = 'fas fa-sun';
        } else {
            icon.className = 'fas fa-moon';
        }
    }
}

// 쿠키에서 테마 로드
function loadThemeFromCookie() {
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        const [name, value] = cookie.trim().split('=');
        if (name === 'daon_theme') {
            return value;
        }
    }
    return 'light'; // 기본값
}

// 페이지 로드시 저장된 테마 적용
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = loadThemeFromCookie();
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
});
