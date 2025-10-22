<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 안전보건 카드뉴스 상세보기
 * 
 * 선택한 카드뉴스 이미지를 크게 표시
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 변수 초기화
$index = $_REQUEST["index"] ?? '';
$img_filename = $_REQUEST["img"] ?? '';
$name = $_REQUEST["name"] ?? '';

// 데이터 검증
if (empty($img_filename)) {
    echo "<script>alert('이미지 정보가 없습니다.'); window.close();</script>";
    exit;
}

// 권한 체크
if ($level > 8) {
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정
$base_url = getBaseUrl();
$img_url = $base_url . '/img/' . $img_filename;

include includePath('load_header.php');
?>

<title><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> - 안전보건 카드뉴스</title>

<style>
    body {
        margin: 0;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .cardnews-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .cardnews-title {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #dee2e6;
    }
    
    .cardnews-image {
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        border-radius: 5px;
    }
    
    .btn-container {
        text-align: center;
        margin-top: 30px;
    }
    
    .btn-close-window {
        padding: 10px 30px;
        font-size: 16px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .btn-close-window:hover {
        background-color: #5a6268;
    }
    
    @media print {
        .btn-container {
            display: none;
        }
    }
</style>

</head>

<body>

<div class="cardnews-container">
    <div class="cardnews-title">
        <h2><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h2>
    </div>
    
    <div class="cardnews-content">
        <img src="<?= htmlspecialchars($img_url, ENT_QUOTES, 'UTF-8') ?>" 
             alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" 
             class="cardnews-image"
             onerror="this.src='<?= $base_url ?>/img/noimage.jpg'">
    </div>
    
    <div class="btn-container">
        <button type="button" class="btn-close-window" onclick="window.print();">
            <i class="bi bi-printer"></i> 인쇄
        </button>
        <button type="button" class="btn-close-window" onclick="window.close();">
            <i class="bi bi-x-circle"></i> 닫기
        </button>
    </div>
</div>

<script>
// 이미지 로드 확인
document.addEventListener('DOMContentLoaded', function() {
    var img = document.querySelector('.cardnews-image');
    if (img) {
        img.addEventListener('error', function() {
            console.error('이미지 로드 실패:', '<?= htmlspecialchars($img_url, ENT_QUOTES, 'UTF-8') ?>');
        });
        
        img.addEventListener('load', function() {
            console.log('이미지 로드 성공:', '<?= htmlspecialchars($img_url, ENT_QUOTES, 'UTF-8') ?>');
        });
    }
});

// 서버에 작업 기록
$(document).ready(function() {
    saveLogData('안전보건 카드뉴스 상세보기: <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>');
});
</script>

</body>
</html>

