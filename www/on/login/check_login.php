<?php
/**
 * 로그인 체크 공통 파일
 * 각 페이지 상단에 include하여 사용
 */

// 세션이 시작되지 않았으면 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 로그인 여부 확인
if (!isset($_SESSION['daon_userid']) || empty($_SESSION['daon_userid'])) {
    // 현재 URL을 세션에 저장 (로그인 후 돌아오기 위함)
    $_SESSION['daon_return_url'] = $_SERVER['REQUEST_URI'];

    // 로그인 페이지로 리다이렉트 (절대 경로 사용)
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $loginUrl = "{$protocol}://{$host}/on/login/login_form.php";

    header("Location: {$loginUrl}");
    exit;
}

// 권한 레벨 확인 함수
function checkDaonLevel($required_level = 10) {
    if (!isset($_SESSION['daon_level'])) {
        return false;
    }

    // 레벨이 낮을수록 높은 권한 (1: 최고관리자, 5: 관리자, 10: 일반)
    return $_SESSION['daon_level'] <= $required_level;
}

// 관리자 권한 확인 함수
function isDaonAdmin() {
    return isset($_SESSION['daon_level']) && $_SESSION['daon_level'] <= 5;
}

// 최고 관리자 권한 확인 함수
function isDaonSuperAdmin() {
    return isset($_SESSION['daon_level']) && $_SESSION['daon_level'] == 1;
}
?>
