<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;

// 권한 체크 (필요 시 조정)
if (!isset($_SESSION["level"])) {
    $_SESSION["url"] = getBaseUrl() . '/process_guide/index.php';
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

include getDocumentRoot() . '/load_header.php';
?>
<title>작업 공정 안내 - 미래정공</title>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    :root {
        /* 그레이 톤 색상 변경 */
        --bg-primary: #ffffff;
        --bg-secondary: #ffffff;
        --bg-card: #f8f9fa;
        --bg-gradient-start: #6c757d; /* Gray */
        --bg-gradient-end: #495057;   /* Darker Gray */
        --text-primary: #333333;
        --text-secondary: #666666;
        --text-white: #ffffff;
        --border-color: #e0e0e0;
        --border-light: #f0f0f0;
        --shadow: rgba(0,0,0,0.08);
        --shadow-hover: rgba(108, 117, 125, 0.2); /* Gray shadow */
        --hover-bg: #f8f9fa; /* Light gray hover */
    }

    [data-theme="dark"] {
        /* 다크 모드 색상 */
        --bg-primary: #1a1a2e;
        --bg-secondary: #16213e;
        --bg-card: #1e2a3a;
        --bg-gradient-start: #495057;
        --bg-gradient-end: #343a40;
        --text-primary: #e2e8f0;
        --text-secondary: #cbd5e0;
        --text-white: #ffffff;
        --border-color: #4a5568;
        --border-light: #2d3748;
        --shadow: rgba(0,0,0,0.3);
        --shadow-hover: rgba(108, 117, 125, 0.5);
        --hover-bg: #2d3748;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .order-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 15px 20px 25px 20px;
    }

    .page-header {
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        color: white;
        padding: 20px 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(108, 117, 125, 0.15);
    }

    .page-header h1 {
        margin: 0 0 8px 0;
        font-size: 22px;
        font-weight: 600;
        color: white;
    }

    .page-header p {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 13px;
    }

    /* Process Specific Styles */
    .process-section {
        background: var(--bg-card);
        border-radius: 9px;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px var(--shadow);
        padding: 40px;
        margin-bottom: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .process-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px var(--shadow-hover);
    }

    .process-content {
        display: flex;
        align-items: center;
        gap: 40px;
    }

    .process-text {
        flex: 1;
    }

    .process-image {
        flex: 1;
        position: relative;
        overflow: hidden;
        border-radius: 10px;
    }

    .process-image img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }

    .process-section:hover .process-image img {
        transform: scale(1.05);
    }

    .process-number {
        display: inline-block;
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        color: white;
        width: 36px;
        height: 36px;
        line-height: 36px;
        text-align: center;
        border-radius: 50%;
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .process-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--text-primary);
    }

    .process-desc {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .feature-list li {
        margin-bottom: 8px;
        padding-left: 20px;
        position: relative;
        font-size: 0.95rem;
        color: var(--text-primary);
    }

    .feature-list li:before {
        content: '\f00c'; /* FontAwesome check */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        color: var(--bg-gradient-start);
        font-size: 0.8rem;
        top: 3px;
    }

    @media (max-width: 992px) {
        .process-content {
            flex-direction: column;
            text-align: center;
            gap: 25px;
        }

        .process-content.reverse {
            flex-direction: column;
        }

        .feature-list li {
            text-align: left;
            display: inline-block;
            margin-right: 15px;
        }
    }

    /* Animation classes */
    .fade-in-up {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="order-container">
    
    <div class="page-header">
        <h1><i class="fas fa-cogs"></i> 미래기업 작업 공정 안내</h1>
        <p>최고의 품질을 위한 미래기업만의 체계적인 제조 공정을 소개합니다.</p>
    </div>

    <!-- Section 1: Ceiling -->
    <div class="process-section fade-in-up">
        <div class="process-content">
            <div class="process-image">
                <img src="<?= $root_dir ?>/img/process/ceiling.png" alt="조명천장/본천장 제조 공정">
            </div>
            <div class="process-text">
                <span class="process-number">01</span>
                <h2 class="process-title">조명천장 / 본천장 제조</h2>
                <p class="process-desc">
                    현대적인 디자인과 정밀한 기술력이 결합된 천장 시스템을 제조합니다. 
                    고객의 요구사항에 맞춘 맞춤형 설계부터 정밀 가공까지, 
                    공간의 가치를 높이는 최상의 천장 솔루션을 제공합니다.
                </p>
                <ul class="feature-list">
                    <li>정밀 레이저 커팅 및 절곡</li>
                    <li>고품질 분체 도장 마감</li>
                    <li>내진 설계 및 안전성 확보</li>
                    <li>다양한 디자인 패턴 구현 가능</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Section 2: Jamb -->
    <div class="process-section fade-in-up">
        <div class="process-content reverse">
            <div class="process-text">
                <span class="process-number">02</span>
                <h2 class="process-title">쟘(Jamb) 제조</h2>
                <p class="process-desc">
                    건축물의 디테일을 완성하는 고정밀 쟘(Jamb)을 생산합니다. 
                    견고한 내구성과 완벽한 마감 처리를 통해 
                    도어와 창호의 품격을 한 단계 높여드립니다.
                </p>
                <ul class="feature-list">
                    <li>자동화 라인을 통한 균일한 품질</li>
                    <li>다양한 규격 및 형상 대응</li>
                    <li>신속한 납기 준수</li>
                    <li>현장 맞춤형 솔루션 제공</li>
                </ul>
            </div>
            <div class="process-image">
                <img src="<?= $root_dir ?>/img/process/jamb.png" alt="쟘 제조 공정">
            </div>
        </div>
    </div>

    <!-- Section 3: Separator -->
    <div class="process-section fade-in-up">
        <div class="process-content">
            <div class="process-image">
                <img src="<?= $root_dir ?>/img/process/separator.png" alt="재료분리대 제조 공정">
            </div>
            <div class="process-text">
                <span class="process-number">03</span>
                <h2 class="process-title">재료분리대 제조</h2>
                <p class="process-desc">
                    서로 다른 마감재의 경계를 깔끔하게 정리하는 재료분리대를 제작합니다. 
                    기능성과 심미성을 동시에 고려한 디자인으로 
                    공간의 완성도를 높이는 필수 건축 자재입니다.
                </p>
                <ul class="feature-list">
                    <li>스테인리스, 알루미늄 등 다양한 소재</li>
                    <li>미려한 표면 처리 (헤어라인, 미러 등)</li>
                    <li>시공 편의성을 고려한 설계</li>
                    <li>특수 규격 주문 제작 가능</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
    // Scroll Animation Observer
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Animate only once
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up').forEach(element => {
            observer.observe(element);
        });
    });
</script>
