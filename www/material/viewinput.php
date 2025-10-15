<?php
/**
 * 원자재 입고 이력 리스트 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    } elseif (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return $default;
}

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$WebSite = $protocol . "://" . $host;

// 세션 변수 안전하게 가져오기
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 999;
$id_name = isset($_SESSION["name"]) ? $_SESSION["name"] : '';

// 권한 확인 (레벨 7 이하만 접근 가능)
if (!isset($_SESSION["level"]) || $level > 7) {
    sleep(2);
    header("Location: {$WebSite}/login/logout.php");
    exit;
}

// 요청 변수 초기화
$check = getRequestValue("check", '1');
$etc = getRequestValue("etc", '');

// XSS 방지를 위해 먼저 이스케이프한 후 '/' 문자를 줄바꿈으로 변환
$etc = htmlspecialchars($etc, ENT_QUOTES, 'UTF-8');
$etc = str_replace('/', '<br>', $etc);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>원자재 철판 입고리스트</title>
    
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- AlertifyJS -->
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/partner.css" type="text/css" />
    
    <style>
        #panel, #flip {
            padding: 5px;
            text-align: center;
            background-color: #e5eecc;
            border: solid 1px #c3c3c3;
        }
        
        #panel {
            padding: 50px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <br>
        <h2 class="display-5 font-center text-left text-primary">원자재 입고 이력 리스트</h2>
        <br>
        <h2 class="display-5 font-center text-left"><?= $etc ?></h2>
        <br>
    </div>
</body>
</html>
