<?php
/**
 * 장비 점검 페이지 리다이렉트
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 요청 변수 초기화
$mcname = isset($_REQUEST["mcname"]) ? $_REQUEST["mcname"] : '';
$selnum = isset($_REQUEST["selnum"]) ? $_REQUEST["selnum"] : 1;

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_path = '/mymachine/laser.php';

// 안전한 URL 파라미터 생성
$params = http_build_query([
    'mcname' => $mcname,
    'selnum' => $selnum
], '', '&', PHP_QUERY_RFC3986);

// 리다이렉트 URL 구성
$redirect_url = "{$protocol}://{$host}{$base_path}?{$params}";

// 리다이렉트
header("Location: {$redirect_url}");
exit;
?>
