<?php
/**
 * 로그아웃 처리 페이지
 * 사용자 세션을 종료하고 로그인 페이지로 리다이렉션합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 모든 세션 변수 제거
unset($_SESSION["userid"]);
unset($_SESSION["name"]);
unset($_SESSION["nick"]);
unset($_SESSION["level"]);
unset($_SESSION["weather"]);

// 추가 세션 변수 제거 (있을 경우)
if (isset($_SESSION["DB"])) {
    unset($_SESSION["DB"]);
}
if (isset($_SESSION["WebSite"])) {
    unset($_SESSION["WebSite"]);
}
if (isset($_SESSION["admin"])) {
    unset($_SESSION["admin"]);
}

// 세션 완전히 파괴 (선택사항)
// session_destroy();

// 동적 리다이렉션
$baseUrl = getBaseUrl();
header("Location: " . $baseUrl . "/login/login_form.php");
exit;
?>
