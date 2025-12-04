<?php
/**
 * 세션 관리 및 환경별 설정
 */

// 환경별 설정 로드
require_once __DIR__ . '/config/environment.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    // 세션 설정 (24시간 = 86400초)
    ini_set('session.gc_maxlifetime', 86400);
    ini_set('session.cookie_lifetime', 86400);
    
    // 내부 GC 활성화 (시스템 cron이 커스텀 경로를 청소하지 않을 수 있음)
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    
    // 커스텀 세션 저장 경로 설정 (시스템 GC 간섭 방지)
    $session_save_path = __DIR__ . '/data/sessions';
    if (!file_exists($session_save_path)) {
        mkdir($session_save_path, 0777, true);
    }
    ini_set('session.save_path', $session_save_path);
    
    // 세션 쿠키 파라미터 설정 (session_start 전에 호출해야 함)
    session_set_cookie_params([
        'lifetime' => 86400,  // 24시간
        'path' => '/',
        'secure' => false,    // HTTPS 사용 시 true로 변경
        'httponly' => true,   // XSS 공격 방지
        'samesite' => 'Lax'   // CSRF 공격 방지
    ]);
    
    session_start();
    
    // 세션 타임아웃 체크
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        $elapsed = time() - $_SESSION['LAST_ACTIVITY'];
        // 24시간(86400초) 이상 경과 시 세션 파기
        if ($elapsed > 86400) {
            session_unset();
            session_destroy();
            session_start();
        }
    }
    
    // 마지막 활동 시간 업데이트
    $_SESSION['LAST_ACTIVITY'] = time();
}

$DB        = $_SESSION['DB']      ?? 'mirae8440';
$level     = $_SESSION['level']   ?? null;
$user_name = $_SESSION['name']    ?? null;
$user_id   = $_SESSION['userid']  ?? null;
$hp        = $_SESSION['hp']      ?? null;

// 환경별 URL 설정 (로컬/서버 자동 구분)
$WebSite  = getBaseUrl() . '/';
$root_dir = getBaseUrl();
$version  = '21';

$expiryTime    = (int)ini_get('session.gc_maxlifetime');
$remainingTime = 0;

if (isset($_SESSION['LAST_ACTIVITY'])) {
    $elapsedTime   = time() - (int)$_SESSION['LAST_ACTIVITY'];
    if ($elapsedTime < $expiryTime) {
        $remainingTime = $expiryTime - $elapsedTime;
    }
}

$today = date('Y-m-d');

// 모바일 탐지 (0 위치에서도 true 되도록 !== false 사용)
$mAgent    = ["iPhone","iPod","Android","Blackberry","Opera Mini","Windows ce","Nokia","sony"];
$chkMobile = false;
foreach ($mAgent as $agent) {
    if (isset($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], $agent) !== false) {
        $chkMobile = true;
        if ($user_name === '권영철') {
            $submenu = 1;
        }
        break;
    }
}