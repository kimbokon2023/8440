<?php
if (!isset($_SESSION)) {
    session_start();
}

// 요청 변수 초기화
$mcname = $_REQUEST["mcname"] ?? '';
$selnum = $_REQUEST["selnum"] ?? 1;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$target = $protocol . $host . $basePath . "/laser.php?mcname=" . urlencode($mcname) . "&selnum=" . urlencode($selnum);
header("Location:$target");
?>