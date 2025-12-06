<?php
/**
 * 로그아웃 처리 페이지
 * 사용자 세션을 종료하고 로그인 페이지로 리다이렉션합니다.
 */

// 로컬과 서버 호환성을 위한 설정
require_once __DIR__ . '/../bootstrap.php';

// 세션 시작 (아직 시작되지 않았다면)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 모든 세션 변수 제거
unset($_SESSION["userid"]);
unset($_SESSION["name"]);
unset($_SESSION["nick"]);
unset($_SESSION["level"]);
unset($_SESSION["ecountID"]);
unset($_SESSION["part"]);
unset($_SESSION["eworks_level"]);
unset($_SESSION["position"]);
unset($_SESSION["hp"]);
unset($_SESSION["DB"]);
unset($_SESSION["LAST_ACTIVITY"]);
unset($_SESSION["weather"]);
unset($_SESSION["url"]);

// 추가 세션 변수 제거 (있을 경우)
if (isset($_SESSION["WebSite"])) {
    unset($_SESSION["WebSite"]);
}
if (isset($_SESSION["admin"])) {
    unset($_SESSION["admin"]);
}

// 모든 세션 변수 제거 (안전장치)
$_SESSION = array();

// 세션 쿠키 삭제
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 세션 완전히 파괴
session_destroy();

// 동적 리다이렉션 - index.php로 이동 (홈페이지에 머물기)
$baseUrl = getBaseUrl();
header("Location: " . $baseUrl . "/index.php");
exit;
?>
