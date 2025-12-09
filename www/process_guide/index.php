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

    /* Process Flow Visualization - Enhanced UI */
    .process-flow-container {
        margin-top: 30px;
        position: relative;
        padding: 40px 0;
    }
    
    .process-steps-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        max-width: 900px;
        margin: 0 auto;
    }

    /* Connecting Line */
    .process-steps-wrapper::before {
        content: '';
        position: absolute;
        top: 40px;
        left: 50px;
        right: 50px;
        height: 4px;
        background: #e9ecef;
        z-index: 0;
        border-radius: 4px;
    }

    .process-step-item {
        position: relative;
        z-index: 1;
        text-align: center;
        width: 30%;
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .process-step-item:hover {
        transform: translateY(-5px);
    }

    .step-icon-circle {
        width: 80px;
        height: 80px;
        background: white;
        border: 4px solid var(--border-light);
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 1.8rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden; /* Ensure image stays within circle */
        position: relative;
    }

    .step-icon-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .process-step-item:hover .step-icon-circle {
        border-color: #3b82f6; /* Blue border on hover */
        color: #3b82f6;
        box-shadow: 0 8px 15px rgba(59, 130, 246, 0.2);
    }
    
    .process-step-item.active .step-icon-circle {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-color: transparent;
        color: white;
    }

    .step-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .step-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .btn-view-qc {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        font-size: 0.85rem;
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-view-qc:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* Modal Styles */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-backdrop-custom.show {
        display: block;
        opacity: 1;
    }

    .qc-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        width: 90%;
        max-width: 800px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        z-index: 1001;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }

    .qc-modal.show {
        display: block;
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

    .qc-modal-header {
        background: linear-gradient(135deg, var(--bg-card) 0%, #ffffff 100%);
        padding: 20px 30px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .qc-modal-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qc-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 5px;
        transition: color 0.2s;
    }

    .qc-modal-close:hover {
        color: #ef4444;
    }

    .qc-modal-body {
        padding: 0;
        max-height: 70vh;
        overflow-y: auto;
    }

    /* QC Data Table Style */
    .qc-table-wrapper {
        width: 100%;
        border-collapse: collapse;
    }

    .qc-table-wrapper th {
        background: #f8fafc;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-light);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .qc-table-wrapper td {
        padding: 16px 15px;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .qc-table-wrapper tr:last-child td {
        border-bottom: none;
    }

    .qc-table-wrapper tr:hover td {
        background: #fafafa;
    }

    .check-point-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .badge-primary { background: #eff6ff; color: #3b82f6; }
    .badge-success { background: #f0fdf4; color: #22c55e; }
    .badge-purple { background: #f5f3ff; color: #8b5cf6; }

    @media (max-width: 768px) {
        .process-steps-wrapper {
            flex-direction: column;
            gap: 40px;
            align-items: center;
        }
        .process-steps-wrapper::before {
            width: 4px;
            height: auto;
            top: 20px;
            bottom: 20px;
            left: 50%;
            right: auto;
            transform: translateX(-50%);
        }
        .process-step-item {
            width: 100%;
        }
    }
    
    /* Process Card Grid Styles */
    .process-card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 20px;
        max-width: 1200px; /* Constrain width */
        margin-left: auto;
        margin-right: auto;
    }

    .process-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid var(--border-light);
        display: flex;
        flex-direction: column;
    }

    .process-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .process-card-img {
        width: 100%;
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .process-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .process-card:hover .process-card-img img {
        transform: scale(1.1);
    }

    .process-card-icon {
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        z-index: 2;
        color: #3b82f6;
        font-size: 1.5rem;
    }

    .process-card-body {
        padding: 40px 20px 25px; /* Top padding clears the icon */
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .process-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--text-primary);
    }

    .process-card-desc {
        font-size: 0.95rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }
    
    @media (max-width: 992px) {
        .process-card-grid {
            /* Keep 3 columns on tablet landscape if possible, or maybe 2 is better? 
               User asks for 3, but if it's too small it breaks. 
               Let's set min-width for cards? 
               actually, let's keep it responsive but maybe user's screen is large enough.
               I will comment out the 2-column step for 992px to favor 3 columns if user insists,
               BUT 3 columns on 768-992px might be tight. 
               Let's Try to keep 3 columns until 768px.
            */
             grid-template-columns: repeat(3, 1fr); 
        }
    }

    @media (max-width: 768px) {
        .process-card-grid {
            grid-template-columns: 1fr;
        }
    }</style>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="order-container">
    
    <div class="page-header">
        <h1><i class="fas fa-cogs"></i> 미래기업 작업 공정 안내</h1>
        <p>최고의 품질을 위한 미래기업만의 체계적인 제조 공정을 소개합니다.</p>
    </div>

    <!-- Section 1: Ceiling -->
    <div class="process-section fade-in-up">
        <div class="process-content">
            <div class="process-text" style="text-align:center;">
                <span class="process-number">01</span>
                <h2 class="process-title">조명천장 / 본천장 제조</h2>
                <p class="process-desc">
                    현대적인 디자인과 정밀한 기술력이 결합된 천장 시스템을 제조합니다. 
                    고객의 요구사항에 맞춘 맞춤형 설계부터 정밀 가공까지, 
                    공간의 가치를 높이는 최상의 천장 솔루션을 제공합니다.
                </p>
                <div style="display:inline-block; text-align:left;">
                    <ul class="feature-list">
                        <li>정밀 레이저 커팅 및 절곡</li>
                        <li>고품질 분체 도장 마감</li>
                        <li>내진 설계 및 안전성 확보</li>
                        <li>다양한 디자인 패턴 구현 가능</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Added Process Flow Visualization (6 Steps) -->
        <div class="process-flow-container">
            <h4 class="text-center mb-5" style="color:var(--text-secondary); font-weight:600; letter-spacing:1px;">TOTAL MANUFACTURING PROCESS</h4>
            
            <div class="process-card-grid">
                <!-- Step 1: Laser -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/laser_process.png" alt="Laser Process">
                        <div class="process-card-icon">
                            <i class="fas fa-crosshairs"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">1. 레이저 (Laser)</h3>
                        <p class="process-card-desc">
                            최첨단 CNC 레이저 설비를 이용한 정밀 절단 가공으로<br>
                            오차 없는 완벽한 품질을 구현합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('laser')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 2: V-Cut -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/vcut_process.png" alt="V-Cut Process">
                        <div class="process-card-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">2. V-커팅 (V-Cut)</h3>
                        <p class="process-card-desc">
                            날카롭고 정교한 모서리 마감을 위해<br>
                            정밀 V-홈 가공 공정을 거칩니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('vcut')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 3: Bending -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/bending_process.png" alt="Bending Process">
                        <div class="process-card-icon">
                            <i class="fas fa-bezier-curve"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">3. 절곡 (Bending)</h3>
                        <p class="process-card-desc">
                            설계 도면에 맞춘 정확한 각도와 치수로<br>
                            빈틈없는 절곡 성형을 수행합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('bending')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 4: Painting -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/painting_process.png" alt="Painting Process">
                        <div class="process-card-icon">
                            <i class="fas fa-fill-drip"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">4. 분체도장 (Painting)</h3>
                        <p class="process-card-desc">
                            내구성과 심미성을 모두 잡은 고품질 분체 도장으로<br>
                            최상의 표면 마감을 제공합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('painting')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 5: Assembly -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/assembly_process.png" alt="Assembly Process">
                        <div class="process-card-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">5. 조립 (Assembly)</h3>
                        <p class="process-card-desc">
                            숙련된 전문가의 꼼꼼한 조립 과정을 통해<br>
                            결점 없는 완벽한 제품을 완성합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('assembly')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 6: Shipment -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/shipment_process.png" alt="Shipment Process">
                        <div class="process-card-icon">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">6. 출하 (Shipment)</h3>
                        <p class="process-card-desc">
                            철저한 최종 검수와 안전한 패킹 시스템으로<br>
                            현장까지 완벽하게 배송합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('shipment')">QC 공정확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Jamb -->
    <div class="process-section fade-in-up">
        <div class="process-content reverse">
            <div class="process-text" style="text-align:center;">
                <span class="process-number">02</span>
                <h2 class="process-title">쟘(Jamb) 제조</h2>
                <p class="process-desc">
                    건축물의 디테일을 완성하는 고정밀 쟘(Jamb)을 생산합니다. 
                    견고한 내구성과 완벽한 마감 처리를 통해 
                    도어와 창호의 품격을 한 단계 높여드립니다.
                </p>
                <div style="display:inline-block; text-align:left;">
                    <ul class="feature-list">
                        <li>자동화 라인을 통한 균일한 품질</li>
                        <li>다양한 규격 및 형상 대응</li>
                        <li>신속한 납기 준수</li>
                        <li>현장 맞춤형 솔루션 제공</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Added Process Flow Visualization -->
        <div class="process-flow-container">
            <h4 class="text-center mb-4" style="color:var(--text-secondary); font-weight:600;">Process Detail & Quality Control</h4>
            
            <div class="process-card-grid">
                <!-- Step 1: Laser -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/jamb_laser_process.png" alt="Jamb Laser Process">
                        <div class="process-card-icon">
                            <i class="fas fa-crosshairs"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">1. 레이저 커팅 (Laser)</h3>
                        <p class="process-card-desc">
                            최첨단 레이저 설비를 이용한 정밀 절단 가공으로<br>
                            복잡한 형상도 오차 없이 완벽하게 구현합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('laser')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 2: V-Cut -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/jamb_vcut_process.png" alt="Jamb V-Cut Process">
                        <div class="process-card-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">2. V-커팅 (V-Cut)</h3>
                        <p class="process-card-desc">
                            날카롭고 정교한 모서리 마감을 위해<br>
                            전용 V-홈 가공 장비로 정밀 가공합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('vcut')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 3: Bending -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/jamb_bending_process.png" alt="Jamb Bending Process">
                        <div class="process-card-icon">
                            <i class="fas fa-bezier-curve"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">3. 절곡 (Bending)</h3>
                        <p class="process-card-desc">
                            설계 도면에 맞춘 정확한 각도와 치수로<br>
                            고정밀 절곡 성형을 수행하여 내구성을 높입니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('bending')">QC 공정확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Separator (Elevator Sill) -->
    <div class="process-section fade-in-up">
        <div class="process-content">
            <div class="process-text" style="text-align:center;">
                <span class="process-number">03</span>
                <h2 class="process-title">재료분리대 (Elevator Sill) 제조</h2>
                <p class="process-desc">
                    서로 다른 바닥 마감재를 깔끔하게 분리해주는 엘리베이터 SILL을 제작합니다.<br>
                    정밀한 조립과 견고한 체결 방식을 통해 최상의 내구성을 보장합니다.
                </p>
                <div style="display:inline-block; text-align:left;">
                    <ul class="feature-list">
                        <li>스테인리스/알루미늄 하이브리드 조립</li>
                        <li>전동 공구를 이용한 정밀 체결</li>
                        <li>카운터 가공을 통한 완벽한 마감</li>
                        <li>안전한 포장 및 출고</li>
                    </ul>
                </div>
            </div>
        </div>
        </div>

        <div class="process-flow-container">
            <h4 class="text-center mb-4" style="color:var(--text-secondary); font-weight:600;">Process Detail & Quality Control</h4>
            
            <div class="process-card-grid">
                <!-- Step 1: Material Prep -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/sill_material_prep.png" alt="Material Preparation">
                        <div class="process-card-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">1. 자재 준비 (Preparation)</h3>
                        <p class="process-card-desc">
                            스텐레스와 알루미늄 바디 등<br>
                            조립에 필요한 자재를 정렬하고 검수합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('sill_prep')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 2: Assembly -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/sill_assembly_process.png" alt="Sill Assembly">
                        <div class="process-card-icon">
                            <i class="fas fa-screwdriver"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">2. 조립 및 체결 (Assembly)</h3>
                        <p class="process-card-desc">
                            전동공구와 카운터 가공을 통해<br>
                            스텐레스와 알루미늄 바디를 견고하게 결합합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('sill_assembly')">QC 공정확인</button>
                    </div>
                </div>

                <!-- Step 3: Packaging -->
                <div class="process-card">
                    <div class="process-card-img">
                        <img src="img/sill_packaging_process.png" alt="Packaging">
                        <div class="process-card-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                    <div class="process-card-body">
                        <h3 class="process-card-title">3. 포장 및 출고 (Packaging)</h3>
                        <p class="process-card-desc">
                            완성된 SILL 제품을 안전하게 보호하기 위해<br>
                            꼼꼼하게 포장하여 출고합니다.
                        </p>
                        <button class="btn-view-qc" onclick="openQcModal('sill_packing')">QC 공정확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QC Modal Structure -->
    <div class="modal-backdrop-custom" id="modalBackdrop"></div>
    <div class="qc-modal" id="qcModal">
        <div class="qc-modal-header">
            <div class="qc-modal-title" id="qcModalTitle">
                <i class="fas fa-check-circle text-primary"></i> <span>QC Detail</span>
            </div>
            <button class="qc-modal-close" onclick="closeQcModal()">&times;</button>
        </div>
        <div class="qc-modal-body">
            <table class="qc-table-wrapper">
                <thead>
                    <tr>
                        <th style="width: 25%">검사 항목 (Check Point)</th>
                        <th style="width: 30%">판정 기준 (Criteria)</th>
                        <th style="width: 25%">검사 방법 (Method)</th>
                        <th style="width: 20%">검사 빈도 (Freq)</th>
                    </tr>
                </thead>
                <tbody id="qcModalContent">
                    <!-- Dynamic Content -->
                </tbody>
            </table>
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

    // QC Data Structure
    const qcData = {
        'laser': {
            title: "레이저 커팅 품질관리 기준 (Laser QC)",
            items: [
                { point: "치수 정밀도", criteria: "도면 치수 ±0.1mm 이내", method: "버니어 캘리퍼스 / 줄자", freq: "초물, 중물, 종물", badge: "badge-primary" },
                { point: "절단면 상태", criteria: "Burr 발생 없을 것 (< 0.2mm)", method: "육안 검사 / 촉수", freq: "전수 검사", badge: "badge-success" },
                { point: "스크래치", criteria: "표면 보호 필름 손상 없을 것", method: "육안 검사", freq: "전수 검사", badge: "badge-purple" },
                { point: "피어싱 상태", criteria: "관통 상태 양호, 똥 튐 없음", method: "육안 검사", freq: "샘플링 (10%)", badge: "badge-primary" },
                { point: "수량 확인", criteria: "지시 수량과 일치", method: "카운팅", freq: "작업 종료 시", badge: "badge-success" }
            ]
        },
        'vcut': {
            title: "V-커팅 품질관리 기준 (V-Cut QC)",
            items: [
                { point: "잔여 두께", criteria: "재질별 표준 잔여량 ±0.05mm", method: "깊이 게이지", freq: "초물, 중물, 종물", badge: "badge-primary" },
                { point: "가공 깊이", criteria: "편차 없이 균일할 것", method: "육안 / 게이지", freq: "전수 검사", badge: "badge-success" },
                { point: "휨/변형", criteria: "가공 후 휨 발생 최소화", method: "정반 측정", freq: "샘플링 (5%)", badge: "badge-purple" },
                { point: "표면 상태", criteria: "가공면 거칠기 양호", method: "육안 검사", freq: "전수 검사", badge: "badge-success" }
            ]
        },
        'bending': {
            title: "절곡 품질관리 기준 (Bending QC)",
            items: [
                { point: "절곡 각도", criteria: "도면 각도 ±1° 이내", method: "각도기 / 프로트랙터", freq: "초물, 중물, 종물", badge: "badge-primary" },
                { point: "절곡 치수", criteria: "도면 치수 ±0.5mm 이내", method: "버니어 캘리퍼스 / 줄자", freq: "전수 검사", badge: "badge-success" },
                { point: "대각 상태", criteria: "대각 치수 차이 ±1mm 이내", method: "줄자", freq: "샘플링 (10%)", badge: "badge-purple" },
                { point: "외관 상태", criteria: "찍힘, 긁힘, 터짐 없을 것", method: "육안 검사", freq: "전수 검사", badge: "badge-success" }
            ]
        },
        'painting': {
            title: "도장 및 마감 품질관리 (Painting QC)",
            items: [
                { point: "도막 두께", criteria: "지정 사양 준수 (보통 40~60μm)", method: "도막 측정기", freq: "로트별 샘플링", badge: "badge-primary" },
                { point: "부착성 테스트", criteria: "박리 없을 것 (Cross-cut)", method: "테이프 테스트", freq: "로트별 1회", badge: "badge-purple" },
                { point: "색상/광택", criteria: "표준 시편과 일치", method: "육안 / 광택계", freq: "전수 검사", badge: "badge-success" },
                { point: "표면 상태", criteria: "오염, 핀홀, 흐름 없을 것", method: "육안 검사", freq: "전수 검사", badge: "badge-success" }
            ]
        },
        'assembly': {
            title: "조립 공정 품질관리 (Assembly QC)",
            items: [
                { point: "조립 단차", criteria: "±0.5mm 이내 (유격 없을 것)", method: "틈새 게이지", freq: "전수 검사", badge: "badge-primary" },
                { point: "체결 상태", criteria: "볼트/피스 누락 및 풀림 없을 것", method: "육안 / 토크렌치", freq: "전수 검사", badge: "badge-success" },
                { point: "작동 확인", criteria: "간섭 없이 부드럽게 작동", method: "작동 테스트", freq: "전수 검사", badge: "badge-success" },
            ]
        },
        'shipment': {
            title: "포장 및 출하 검사 (Shipment QC)",
            items: [
                { point: "제품 보호", criteria: "보호 테이프/간지 부착 상태 양호", method: "육안 검사", freq: "전수 검사", badge: "badge-success" },
                { point: "구성품 수량", criteria: "출하 명세서와 일치", method: "카운팅", freq: "전수 검사", badge: "badge-primary" },
                { point: "라벨 부착", criteria: "식별표 부착 및 내용 일치", method: "육안 대조", freq: "전수 검사", badge: "badge-success" },
                { point: "파레트 적재", criteria: "견고하게 밴딩 처리됨", method: "육안 검사", freq: "전수 검사", badge: "badge-purple" }
            ]
        },
        'sill_prep': {
            title: "자재 준비 및 검수 (Material QC)",
            items: [
                { point: "원자재 규격", criteria: "STS/AL 재질 및 두께 확인", method: "버니어 캘리퍼스", freq: "입고 시", badge: "badge-primary" },
                { point: "자재 외관", criteria: "찍힘, 휨, 부식 등 결함 없을 것", method: "육안 검사", freq: "전수 검사", badge: "badge-success" },
                { point: "부속품 확인", criteria: "스크류 및 체결 부품 수량/규격", method: "육안 / 카운팅", freq: "작업 전", badge: "badge-purple" }
            ]
        },
        'sill_assembly': {
             title: "조립 및 체결 품질관리 (Assembly QC)",
             items: [
                 { point: "체결 상태", criteria: "스크류 체결 견고성, 유격 없음", method: "드라이버 / 육안", freq: "전수 검사", badge: "badge-primary" },
                 { point: "카운터 가공", criteria: "나사 머리가 돌출되지 않을 것", method: "육안 / 촉수", freq: "전수 검사", badge: "badge-success" },
                 { point: "조립 단차", criteria: "이종 소재 간 단차 최소화", method: "틈새 게이지", freq: "샘플링", badge: "badge-purple" }
             ]
        },
        'sill_packing': {
            title: "포장 및 출고 검사 (Packing QC)",
            items: [
                { point: "포장 상태", criteria: "보호재 밀착 및 외관 손상 방지", method: "육안 검사", freq: "전수 검사", badge: "badge-success" },
                { point: "제품 표시", criteria: "규격 및 수량 표기 정확성", method: "육안 검사", freq: "전수 검사", badge: "badge-primary" }
            ]
        }
    };

    function openQcModal(type) {
        const data = qcData[type];
        if (!data) return;

        // Set Title
        document.getElementById('qcModalTitle').innerHTML = `<i class="fas fa-check-circle text-primary"></i> <span>${data.title}</span>`;

        // Build Table Rows
        const tbody = document.getElementById('qcModalContent');
        tbody.innerHTML = data.items.map(item => `
            <tr>
                <td><span class="check-point-badge ${item.badge}">${item.point}</span></td>
                <td style="font-weight:600; color:#374151;">${item.criteria}</td>
                <td>${item.method}</td>
                <td><span style="color:#6b7280; font-size:0.9em;">${item.freq}</span></td>
            </tr>
        `).join('');

        // Show Modal
        const backdrop = document.getElementById('modalBackdrop');
        const modal = document.getElementById('qcModal');
        
        backdrop.classList.add('show');
        setTimeout(() => modal.classList.add('show'), 10); // Trigger transition
    }

    function closeQcModal() {
        const backdrop = document.getElementById('modalBackdrop');
        const modal = document.getElementById('qcModal');
        
        modal.classList.remove('show');
        setTimeout(() => backdrop.classList.remove('show'), 300); // Wait for transition
    }

    // Close on backdrop click
    document.getElementById('modalBackdrop').addEventListener('click', closeQcModal);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeQcModal();
    });
</script>
