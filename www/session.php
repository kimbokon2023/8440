<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$DB        = $_SESSION['DB']      ?? 'mirae8440';
$level     = $_SESSION['level']   ?? null;
$user_name = $_SESSION['name']    ?? null;
$user_id   = $_SESSION['userid']  ?? null;
$hp        = $_SESSION['hp']      ?? null;

$WebSite  = 'https://8440.co.kr/';
$root_dir = 'https://8440.co.kr';
$version  = '13';

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