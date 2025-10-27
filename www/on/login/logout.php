<?php
session_start();

// 다온텍 관련 세션 모두 제거
unset($_SESSION['daon_userid']);
unset($_SESSION['daon_name']);
unset($_SESSION['daon_nick']);
unset($_SESSION['daon_level']);
unset($_SESSION['daon_part']);
unset($_SESSION['daon_position']);
unset($_SESSION['daon_hp']);
unset($_SESSION['daon_email']);
unset($_SESSION['daon_company']);

// 쿠키 삭제
setcookie("daon_user", "", time() - 3600, "/");

// 세션 완전 파괴 (선택사항 - 다른 시스템 세션도 유지하려면 주석 처리)
// session_destroy();

// 로그인 페이지로 리다이렉트
header('Location: login_form.php');
exit;
?>
