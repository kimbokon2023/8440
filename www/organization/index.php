<?php
/**
 * 조직도 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";
$WebSite = $base_url . '/';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location: {$base_url}/login/login_form.php");
    exit;
}

// 캐시 방지 헤더
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Note: common/functions.php already loaded at line 7
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>조직도</title>
    <link rel="stylesheet" href="styles.css">
    
    <script type="text/javascript">
    (function() {
        'use strict';
        
        // 세션 변수를 JavaScript에 안전하게 전달
        window.userLevel = <?php echo json_encode($level, JSON_UNESCAPED_UNICODE); ?>;
        window.userName = <?php echo json_encode($user_name, JSON_UNESCAPED_UNICODE); ?>;
        
    })();
    </script>
</head>
<body>
    <div id="organizationChart"></div>
    <script src="scripts.js"></script>
</body>
</html>
