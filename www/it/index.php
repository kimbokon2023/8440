<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevProcess Pro - IT 프로젝트 개발 프로세스</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(to bottom right, #0f172a, #581c87, #0f172a);
            color: white;
            line-height: 1.6;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Navigation */
        nav {
            position: fixed;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s;
        }

        nav.scrolled {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: bold;
            background: linear-gradient(to right, #22d3ee, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #22d3ee;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        .mobile-menu {
            display: none;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem;
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
        }

        .mobile-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Hero Section */
        .hero {
            padding-top: 8rem;
            padding-bottom: 5rem;
            text-align: center;
        }

        .badge {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(34, 211, 238, 0.2);
            border: 1px solid rgba(34, 211, 238, 0.3);
            border-radius: 9999px;
            color: #22d3ee;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .gradient-text {
            background: linear-gradient(to right, #22d3ee, #3b82f6, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.25rem;
            color: #cbd5e1;
            margin-bottom: 3rem;
            max-width: 48rem;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 1rem 2rem;
            background: linear-gradient(to right, #22d3ee, #3b82f6);
            border: none;
            border-radius: 0.5rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            box-shadow: 0 20px 25px -5px rgba(34, 211, 238, 0.5);
            transform: translateY(-2px);
        }

        .btn-secondary {
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .stat-label {
            color: #94a3b8;
            margin-top: 0.5rem;
        }

        /* Section Styles */
        section {
            padding: 5rem 1rem;
        }

        .section-dark {
            background: rgba(15, 23, 42, 0.5);
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .section-header p {
            color: #94a3b8;
            font-size: 1.125rem;
        }

        /* Grid Layouts */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .grid-4 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        /* Card Styles */
        .card {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(34, 211, 238, 0.5);
            transform: scale(1.05);
        }

        .card-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(to bottom right, #22d3ee, #3b82f6);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .card-desc {
            color: #94a3b8;
        }

        /* Problem Cards */
        .problem-card {
            padding: 1.5rem;
            background: linear-gradient(to bottom right, rgba(239, 68, 68, 0.1), rgba(249, 115, 22, 0.1));
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 1rem;
            transition: all 0.3s;
        }

        .problem-card:hover {
            border-color: rgba(239, 68, 68, 0.4);
        }

        .problem-emoji {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Process Cards */
        .process-card {
            cursor: pointer;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            transition: all 0.3s;
        }

        .process-card:hover {
            border-color: rgba(34, 211, 238, 0.5);
            transform: scale(1.05);
        }

        .process-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .process-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .process-emoji {
            font-size: 2rem;
        }

        .process-step {
            font-size: 0.875rem;
            color: #22d3ee;
            font-weight: 600;
        }

        .process-title {
            font-size: 1.125rem;
            font-weight: bold;
        }

        .process-duration {
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .process-details {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            font-size: 0.875rem;
            animation: fadeIn 0.3s;
        }

        .process-details.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Project Cards */
        .project-card {
            position: relative;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            transition: all 0.3s;
        }

        .project-card:hover {
            transform: scale(1.05);
        }

        .project-card.featured {
            border-color: #a855f7;
            box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.5);
        }

        .featured-badge {
            position: absolute;
            top: -0.75rem;
            left: 50%;
            transform: translateX(-50%);
            padding: 0.25rem 1rem;
            background: linear-gradient(to right, #a855f7, #ec4899);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .project-tag {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .project-duration {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .project-example {
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .feature-list {
            list-style: none;
            margin-bottom: 1.5rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #cbd5e1;
        }

        /* Contact Form */
        .contact-section {
            background: linear-gradient(to right, rgba(34, 211, 238, 0.2), rgba(59, 130, 246, 0.2));
        }

        .form-container {
            max-width: 56rem;
            margin: 0 auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        input, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
            color: white;
            transition: all 0.3s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #22d3ee;
        }

        input::placeholder, textarea::placeholder {
            color: #94a3b8;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(to right, #22d3ee, #3b82f6);
            border: none;
            border-radius: 0.5rem;
            color: white;
            font-weight: bold;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            box-shadow: 0 20px 25px -5px rgba(34, 211, 238, 0.5);
        }

        .contact-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .contact-feature {
            display: flex;
            gap: 0.75rem;
        }

        .contact-feature-icon {
            color: #22d3ee;
            flex-shrink: 0;
        }

        /* Footer */
        footer {
            padding: 3rem 1rem;
            background: rgba(15, 23, 42, 0.8);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            font-weight: bold;
            margin-bottom: 0.75rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section li {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .footer-section a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #22d3ee;
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            font-size: 0.875rem;
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-header h2 {
                font-size: 1.875rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <div class="logo">
                <i data-lucide="code" style="width: 32px; height: 32px; color: #22d3ee;"></i>
                <span>DevProcess Pro</span>
            </div>
            
            <ul class="nav-links">
                <li><a href="#process">프로세스</a></li>
                <li><a href="#benefits">장점</a></li>
                <li><a href="#projects">프로젝트</a></li>
                <li><a href="#contact">문의</a></li>
            </ul>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i data-lucide="menu" style="width: 24px; height: 24px;"></i>
            </button>
        </div>

        <div class="mobile-menu" id="mobileMenu">
            <a href="#process">프로세스</a>
            <a href="#benefits">장점</a>
            <a href="#projects">프로젝트</a>
            <a href="#contact">문의</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="badge">🚀 체계적인 IT 프로젝트 개발</div>
            
            <h1>
                <span class="gradient-text">성공적인 프로젝트의</span><br>
                <span>시작은 체계입니다</span>
            </h1>
            
            <p>
                기획부터 운영까지, 11단계의 검증된 프로세스로<br>
                당신의 비즈니스를 성공으로 이끕니다
            </p>

            <div class="cta-buttons">
                <button class="btn-primary">
                    무료 상담 받기
                    <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
                </button>
                <button class="btn-secondary">
                    <i data-lucide="download" style="width: 20px; height: 20px;"></i>
                    가이드 다운로드
                </button>
            </div>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-value" style="color: #22d3ee;">30-50%</div>
                    <div class="stat-label">시간 절감</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #3b82f6;">100+</div>
                    <div class="stat-label">성공 프로젝트</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #a855f7;">98%</div>
                    <div class="stat-label">고객 만족도</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #ec4899;">24/7</div>
                    <div class="stat-label">지원 체계</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="section-dark">
        <div class="container">
            <div class="section-header">
                <h2>이런 경험 있으신가요?</h2>
                <p>많은 IT 프로젝트가 실패하는 이유</p>
            </div>

            <div class="grid grid-3">
                <div class="problem-card">
                    <div class="problem-emoji">😤</div>
                    <h3 class="card-title">요구사항 변경</h3>
                    <p class="card-desc">프로젝트 중간에 계속 바뀌는 요구사항으로 일정 지연</p>
                </div>
                <div class="problem-card">
                    <div class="problem-emoji">😞</div>
                    <h3 class="card-title">기대 불일치</h3>
                    <p class="card-desc">완성 후 '이게 아닌데...' 하는 반응</p>
                </div>
                <div class="problem-card">
                    <div class="problem-emoji">😰</div>
                    <h3 class="card-title">운영 문제</h3>
                    <p class="card-desc">출시 후 계속되는 오류와 높은 유지보수 비용</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits">
        <div class="container">
            <div class="section-header">
                <h2>체계적인 프로세스의 힘</h2>
                <p>검증된 방법론으로 리스크를 최소화합니다</p>
            </div>

            <div class="grid grid-4">
                <div class="card">
                    <div class="card-icon">
                        <i data-lucide="clock" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="card-title">30-50% 시간 절감</h3>
                    <p class="card-desc">체계적인 프로세스로 불필요한 작업 제거</p>
                </div>
                <div class="card">
                    <div class="card-icon">
                        <i data-lucide="trending-up" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="card-title">고객 만족도 향상</h3>
                    <p class="card-desc">명확한 소통과 단계적 검증</p>
                </div>
                <div class="card">
                    <div class="card-icon">
                        <i data-lucide="shield" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="card-title">출시 후 안정성</h3>
                    <p class="card-desc">철저한 테스트로 오류 최소화</p>
                </div>
                <div class="card">
                    <div class="card-icon">
                        <i data-lucide="zap" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="card-title">확장 용이</h3>
                    <p class="card-desc">미래를 고려한 설계</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="section-dark">
        <div class="container">
            <div class="section-header">
                <h2>11단계 개발 프로세스</h2>
                <p>각 단계를 클릭하여 자세한 내용을 확인하세요</p>
            </div>

            <div class="grid grid-3" id="processGrid"></div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects">
        <div class="container">
            <div class="section-header">
                <h2>프로젝트 규모별 안내</h2>
                <p>귀하의 비즈니스에 맞는 최적의 솔루션을 제안합니다</p>
            </div>

            <div class="grid grid-3">
                <div class="project-card">
                    <div class="project-tag" style="background: linear-gradient(to right, #3b82f6, #22d3ee);">소규모 프로젝트</div>
                    <div class="project-duration">6-8주</div>
                    <div class="project-example">예: 간단한 쇼핑몰</div>
                    <ul class="feature-list">
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            상품 관리
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            주문/결제
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            회원 관리
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            기본 통계
                        </li>
                    </ul>
                    <button class="btn-secondary" style="width: 100%;">견적 문의</button>
                </div>

                <div class="project-card featured">
                    <div class="featured-badge">인기</div>
                    <div class="project-tag" style="background: linear-gradient(to right, #a855f7, #ec4899);">중규모 프로젝트</div>
                    <div class="project-duration">10-14주</div>
                    <div class="project-example">예: 업무 관리 시스템</div>
                    <ul class="feature-list">
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            복잡한 프로세스
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            권한 관리
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            알림 시스템
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            상세 리포트
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            외부 연동
                        </li>
                    </ul>
                    <button class="btn-secondary" style="width: 100%;">견적 문의</button>
                </div>

                <div class="project-card">
                    <div class="project-tag" style="background: linear-gradient(to right, #f97316, #ef4444);">대규모 프로젝트</div>
                    <div class="project-duration">16주+</div>
                    <div class="project-example">예: 통합 플랫폼</div>
                    <ul class="feature-list">
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            다중 모듈
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            고급 보안
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            빅데이터 처리
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            AI 통합
                        </li>
                        <li class="feature-item">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #22c55e;"></i>
                            실시간 처리
                        </li>
                    </ul>
                    <button class="btn-secondary" style="width: 100%;">견적 문의</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2>지금 바로 시작하세요</h2>
                <p>무료 상담으로 귀하의 프로젝트에 대한 구체적인 로드맵을 받아보세요</p>
            </div>

            <div class="form-container">
                <form>
                    <div class="form-grid">
                        <input type="text" placeholder="이름" required>
                        <input type="email" placeholder="이메일" required>
                    </div>
                    <input type="text" placeholder="회사명 (선택)" style="margin-bottom: 1.5rem;">
                    <textarea placeholder="프로젝트에 대해 간단히 설명해주세요" rows="4" style="margin-bottom: 1.5rem;"></textarea>
                    <button type="submit" class="submit-btn">무료 상담 신청하기</button>
                </form>
            </div>

            <div class="contact-features">
                <div class="contact-feature">
                    <i data-lucide="users" class="contact-feature-icon" style="width: 24px; height: 24px;"></i>
                    <div>
                        <h3 style="font-weight: bold; margin-bottom: 0.25rem;">전문 컨설팅</h3>
                        <p style="font-size: 0.875rem; color: #94a3b8;">프로젝트 시작 전 기술적 가능성 검토</p>
                    </div>
                </div>
                <div class="contact-feature">
                    <i data-lucide="clock" class="contact-feature-icon" style="width: 24px; height: 24px; color: #3b82f6;"></i>
                    <div>
                        <h3 style="font-weight: bold; margin-bottom: 0.25rem;">맞춤형 일정</h3>
                        <p style="font-size: 0.875rem; color: #94a3b8;">귀사의 상황에 맞는 개발 일정 제안</p>
                    </div>
                </div>
                <div class="contact-feature">
                    <i data-lucide="shield" class="contact-feature-icon" style="width: 24px; height: 24px; color: #a855f7;"></i>
                    <div>
                        <h3 style="font-weight: bold; margin-bottom: 0.25rem;">투명한 견적</h3>
                        <p style="font-size: 0.875rem; color: #94a3b8;">상세한 비용 산출 및 설명</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <div class="logo" style="margin-bottom: 1rem;">
                        <i data-lucide="code" style="width: 24px; height: 24px; color: #22d3ee;"></i>
                        <span>DevProcess Pro</span>
                    </div>
                    <p style="font-size: 0.875rem; color: #94a3b8;">체계적인 IT 프로젝트 개발 솔루션</p>
                </div>
                
                <div class="footer-section">
                    <h4>서비스</h4>
                    <ul>
                        <li><a href="#">웹 개발</a></li>
                        <li><a href="#">앱 개발</a></li>
                        <li><a href="#">시스템 개발</a></li>
                        <li><a href="#">컨설팅</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>자료</h4>
                    <ul>
                        <li><a href="#">가이드북</a></li>
                        <li><a href="#">포트폴리오</a></li>
                        <li><a href="#">블로그</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>연락처</h4>
                    <ul>
                        <li>이메일: contact@devprocess.com</li>
                        <li>전화: 02-1234-5678</li>
                        <li>카카오톡: @devprocess</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 DevProcess Pro. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
        }

        // Process data
        const processes = [
            { id: 1, title: "목표 설정", duration: "1-2일", icon: "🎯", desc: "프로젝트의 명확한 목표와 성공 기준을 정의합니다. 해결하려는 문제와 기대 효과를 문서화합니다." },
            { id: 2, title: "요구사항 수집", duration: "3-7일", icon: "📋", desc: "실제 사용자와 업무 담당자를 인터뷰하여 필요한 기능을 구체적으로 파악합니다." },
            { id: 3, title: "우선순위 결정", duration: "1-2일", icon: "⚡", desc: "모든 기능을 한 번에 개발하지 않고, 핵심 기능부터 단계적으로 개발 계획을 수립합니다." },
            { id: 4, title: "상세 기획", duration: "2-5일", icon: "📝", desc: "각 화면과 기능을 구체적으로 문서화하고 예외 상황까지 정의합니다." },
            { id: 5, title: "기술 설계", duration: "2-7일", icon: "🏗️", desc: "시스템 구조, 데이터베이스, 보안 등 기술적인 설계를 완성합니다." },
            { id: 6, title: "일정 수립", duration: "1일", icon: "📅", desc: "구체적인 개발 일정과 중간 점검 일정을 확정합니다." },
            { id: 7, title: "개발", duration: "N주", icon: "💻", desc: "설계된 기능을 실제로 코드로 구현하며 주간 단위로 진행상황을 공유합니다." },
            { id: 8, title: "테스트", duration: "병행", icon: "🧪", desc: "개발과 병행하여 기능, 호환성, 성능, 보안을 철저히 테스트합니다." },
            { id: 9, title: "배포 준비", duration: "1-2일", icon: "📦", desc: "실제 서비스 환경에 안전하게 배포하기 위한 모든 준비를 완료합니다." },
            { id: 10, title: "출시", duration: "1일", icon: "🚀", desc: "실제 사용자가 접근 가능하도록 서비스를 오픈하고 집중 모니터링합니다." },
            { id: 11, title: "모니터링", duration: "상시", icon: "📊", desc: "시스템 안정성과 사용자 만족도를 지속적으로 관리하고 개선합니다." }
        ];

        // Render process cards
        const processGrid = document.getElementById('processGrid');
        processes.forEach(process => {
            const card = document.createElement('div');
            card.className = 'process-card';
            card.innerHTML = `
                <div class="process-header">
                    <div class="process-info">
                        <span class="process-emoji">${process.icon}</span>
                        <div>
                            <div class="process-step">STEP ${process.id}</div>
                            <h3 class="process-title">${process.title}</h3>
                        </div>
                    </div>
                    <i data-lucide="chevron-down" style="width: 20px; height: 20px; transition: transform 0.3s;"></i>
                </div>
                <div class="process-duration">소요 시간: ${process.duration}</div>
                <div class="process-details">${process.desc}</div>
            `;
            
            card.addEventListener('click', function() {
                const details = this.querySelector('.process-details');
                const icon = this.querySelector('[data-lucide="chevron-down"]');
                details.classList.toggle('active');
                if (details.classList.contains('active')) {
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
            
            processGrid.appendChild(card);
        });

        // Re-initialize Lucide icons after dynamic content
        lucide.createIcons();

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobileMenu');
                    mobileMenu.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>