<?php
require_once __DIR__ . '/bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

$title_message = '미래기업 추억 사진 영상';

// 요청 변수 안전하게 초기화
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';

include includePath('load_header.php');
?>

<meta property="og:type" content="미래기업 유튜브">
<meta property="og:title" content="미래기업 추억">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="미래기업 영상 모음!">
<meta property="og:image" content="<?php echo $base_url; ?>/img/miraethumbnail.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

<title><?php echo $title_message; ?></title>
</head>

<body>

<?php include includePath('myheader.php'); ?>   

<style>
    .progress-bar {
        background: -webkit-linear-gradient(left, #dcdcdc 0%, #3c3c3c 100%); /* Chrome10-25,Safari5.1-6 */
    }
    
    .progress-bar2 {
        background: -webkit-linear-gradient(left, #CCCCFF 0%, #aaaaaa 100%); /* Chrome10-25,Safari5.1-6 */
    }
    
    .typing-txt {
        display: none;
    }
    
    .typeing-txt ul {
        list-style: none;
    }
    
    .typing {
        display: inline-block;
        animation-name: cursor;
        animation-duration: 0.3s;
        animation-iteration-count: infinite;
    }
    
    @keyframes cursor {
        0% {
            border-right: 1px solid #fff;
        }
        50% {
            border-right: 1px solid #000;
        }
        100% {
            border-right: 1px solid #fff;
        }
    }
    
    .photo-frame {
        margin: 15px;
        padding: 10px;
        background-color: white;
        border: 5px solid #ccc;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: inline-block;
    }
    
    .framed-photo {
        width: 45%;
        height: auto;
        border: 1px solid #ddd;
    }
    
    .framed-photofull {
        width: 90%;
        height: auto;
        border: 1px solid #ddd;
    }
    
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body,
        .card-header {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 헤더 최적화 */
        .card-header {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        
        .card-header button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .card-header span {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 이미지 최적화 */
        .photo-frame {
            margin: 0.5rem !important;
            padding: 0.5rem !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .framed-photo {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            margin: 0.25rem 0 !important;
            display: block !important;
        }
        
        .framed-photofull {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* YouTube iframe 최적화 */
        iframe {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            aspect-ratio: 16 / 9 !important;
            margin: 0.5rem 0 !important;
        }
        
        /* iframe 컨테이너 최적화 */
        .d-flex.p-2.mb-2.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 0.5rem !important;
            gap: 0.5rem !important;
        }
        
        /* 제목 최적화 */
        .d-flex.p-2.mb-2.mt-5.justify-content-center,
        .d-flex.p-2.mb-2.mt-3.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.p-2.mb-2.mt-5.justify-content-center h4,
        .d-flex.p-2.mb-2.mt-3.justify-content-center h4 {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            font-size: 1.1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            margin: 0.5rem 0 !important;
        }
        
        /* row 최적화 */
        .row.d-flex {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .row.d-flex .col-sm-12 {
            padding: 0.5rem !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
</style>

<div class="container">
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?php echo htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8'); ?>" size="5">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?php echo htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8'); ?>" size="5">
    <input type="hidden" id="order_alert" name="order_alert" value="<?php echo htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8'); ?>" size="5">

    <!-- 여직원 단합식사 -->
    <div class="row d-flex board_list" style="padding:0;">
        <div class="col-sm-12 board_list" style="padding:7;">
            <div class="card justify-content-center my-card-padding">
                <div class="card-header text-center my-card-padding mt-5 mb-2">
                    <button type="button" id="albumBtn4" class="btn btn-dark btn-sm me-2 fw-bold"><i class="bi bi-chevron-down"></i></button>
                    <span class="fw-bold shop-header fs-5">여직원 단합식사</span>
                </div>
                <div id="album4">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="photo-frame justify-content-center text-center">
                            <?php
                            $photoPath = "img/trip/2024trip001.jpg";
                            echo '<img src="' . $base_url . '/' . $photoPath . '" class="framed-photofull">';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- 2025 카페 자투라 -->
    <div class="row d-flex board_list" style="padding:0;">
        <div class="col-sm-12 board_list" style="padding:7;">
            <div class="card justify-content-center my-card-padding">
                <div class="card-header text-center my-card-padding mt-2 mb-2">
                    <button type="button" id="albumBtn6" class="btn btn-dark btn-sm me-2 fw-bold"><i class="bi bi-chevron-down"></i></button>
                    <span class="fw-bold shop-header fs-5">2025 카페 자투라</span>
                </div>
                <div id="album6">
                    <div class="d-flex p-2 mb-2 justify-content-center">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/KEXDABu2_9Q" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>                        
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="photo-frame justify-content-center text-center">
                            <?php
                            for ($i = 1; $i <= 14; $i++) {
                                // 사진 파일 경로 생성, $i를 2자리로 포맷 (예: 01, 02, ...)
                                $photoPath = "img/trip/20251113_trip" . sprintf("%02d", $i) . ".jpg";
                                // 사진 태그 출력
                                echo '<img src="' . $base_url . '/' . $photoPath . '" class="framed-photo">';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- 2025 홍천 스키 -->
    <div class="row d-flex board_list" style="padding:0;">
        <div class="col-sm-12 board_list" style="padding:7;">
            <div class="card justify-content-center my-card-padding">
                <div class="card-header text-center my-card-padding mt-2 mb-2">
                    <button type="button" id="albumBtn1" class="btn btn-dark btn-sm me-2 fw-bold"><i class="bi bi-chevron-down"></i></button>
                    <span class="fw-bold shop-header fs-5">2025 홍천 스키</span>
                </div>
                <div id="album1">
                    <div class="d-flex p-2 mb-2 justify-content-center">
                        <iframe width="315" height="560" src="https://www.youtube.com/embed/CpgEZMwbamU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        &nbsp;&nbsp;&nbsp;
                        <iframe width="315" height="560" src="https://www.youtube.com/embed/GWBmJ-EQz8c" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="photo-frame justify-content-center text-center">
                            <?php
                            for ($i = 1; $i <= 36; $i++) {
                                // 사진 파일 경로 생성
                                $photoPath = "img/trip/202501trip" . $i . ".jpg";
                                // 사진 태그 출력
                                echo '<img src="' . $base_url . '/' . $photoPath . '" class="framed-photo">';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <!-- 2024 한탄강 -->
    <div class="row d-flex board_list" style="padding:0;">
        <div class="col-sm-12" style="padding:7;">
            <div class="card justify-content-center my-card-padding">
                <div class="card-header text-center my-card-padding">
                    <button type="button" id="albumBtn2" class="btn btn-dark btn-sm me-2 fw-bold"><i class="bi bi-chevron-down"></i></button>
                    <span class="fw-bold shop-header fs-5">2024 한탄강</span>
                </div>
                <div id="album2">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="photo-frame justify-content-center text-center">
                            <img src="<?php echo $base_url; ?>/img/trip/20241213_trip1.jpg" class="framed-photo">
                            <img src="<?php echo $base_url; ?>/img/trip/20241213_trip2.jpg" class="framed-photo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <!-- 2023 군산 선유도 -->
    <div class="row d-flex" style="padding:0;">
        <div class="col-sm-12" style="padding:7;">
            <div class="card justify-content-center my-card-padding">
                <div class="card-header text-center my-card-padding mt-2 mb-2">
                    <button type="button" id="albumBtn3" class="btn btn-dark btn-sm me-2 fw-bold"><i class="bi bi-chevron-down"></i></button>
                    <span class="fw-bold shop-header fs-5">2023 군산 선유도</span>
                </div>
                <div id="album3">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="photo-frame justify-content-center text-center">
                            <?php
                            for ($i = 1; $i <= 56; $i++) {
                                // 숫자를 두 자리로 포맷
                                $formattedNumber = sprintf("%02d", $i);
                                // 사진 파일 경로 생성
                                $photoPath = "img/trip/2023trip" . $formattedNumber . ".jpg";
                                // 사진 태그 출력
                                echo '<img src="' . $base_url . '/' . $photoPath . '" class="framed-photo">';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <!-- 2022 동해 속초 -->
    <div class="row d-flex" style="padding:0;">
        <div class="col-sm-12" style="padding:7;">
            <div class="card justify-content-center my-card-padding">
                <div class="card-header text-center my-card-padding mt-2 mb-2">
                    <button type="button" id="albumBtn5" class="btn btn-dark btn-sm me-2 fw-bold"><i class="bi bi-chevron-down"></i></button>
                    <span class="fw-bold shop-header fs-5">2022 동해 속초</span>
                </div>
                <div id="album5">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="photo-frame justify-content-center text-center">
                            <?php
                            for ($i = 1; $i <= 28; $i++) {
                                // 숫자를 두 자리로 포맷
                                $formattedNumber = sprintf("%02d", $i);
                                // 사진 파일 경로 생성
                                $photoPath = "img/trip/2022trip" . $formattedNumber . ".jpg";
                                // 사진 태그 출력
                                echo '<img src="' . $base_url . '/' . $photoPath . '" class="framed-photo">';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 


    <!-- 유튜브 영상 섹션 -->
    <div class="d-flex p-2 mb-2 mt-5 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 공장문턱 단차 제거작업</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/lthPaJyxLUo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 공장바닥 라인 도색</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/e34jGHQXEy0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 창립10주년 행사 new</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/YKcek2of6S8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">new 미래기업 드론촬영분</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/PzX3742gYjM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">2022 미래기업 강원도 여행</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/cRBlI-x3GSc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">김이사님 5만원 아저씨 빙의</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/QZpHQVvLOxA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 무사고 안전기원</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/VC454gmrU6E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 전직원 드론촬영</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/wssvBQ5vS1Y" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">제1회 미래기업 골프 미니퍼팅대회결과</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/laoox8c0bA8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 사내 미니퍼팅대회</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/lW5hrw_vWsU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 직원콜라보 Lee Kyoungmook Enamul Rana</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/eV2oLtAzzoQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 사무실 어느 퍼팅매트 입고날 feat 제공 안차장님</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/ZJP5uRV7JU0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 직원소개 Rana 용접마스터</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/aU1spL0v2gI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 이경묵 공장장 영상</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/B3OPdFKm7JY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 직원 성실한 Enamul 영상</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/wNRrbx4WXW8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 직원 패러디 넌 나에게 모욕감을 줬어</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/PnrQpLDXkfI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">[드론촬영] 미래기업 공장소개</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/XFFPwYE9nKg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 찾아오는 방법 (김포시 양촌읍 흥신로)</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/zPeiLZm8peQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <div class="d-flex p-2 mb-2 mt-3 justify-content-center">
        <h4 class="text-secondary text-center">미래기업 공장이전 프롤로그</h4>
    </div>
    <div class="d-flex p-2 mb-2 justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/QzHtt-meogo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>    <br><br>

    <?php include includePath('footer.php'); ?>

</div> <!-- container end -->

<script src="<?php echo $base_url; ?>/assets/js/isotope.min.js"></script>
<script src="<?php echo $base_url; ?>/assets/js/owl-carousel.js"></script>
<script src="<?php echo $base_url; ?>/assets/js/counter.js"></script>
<script src="<?php echo $base_url; ?>/assets/js/custom.js"></script>

<script>
    $(document).ready(function() {
        // 모바일에서 iframe 높이 조정
        function adjustIframeHeight() {
            if (window.innerWidth <= 768) {
                $('iframe').each(function() {
                    var $iframe = $(this);
                    var width = $iframe.width();
                    if (width > 0) {
                        // 16:9 비율로 높이 계산
                        var height = (width * 9) / 16;
                        $iframe.css('height', height + 'px');
                    }
                });
            }
        }
        
        // 초기 조정
        adjustIframeHeight();
        
        // 창 크기 변경 시 조정
        $(window).on('resize', function() {
            adjustIframeHeight();
        });
        
        // 쿠키 저장 함수
        function setCookie(name, value, days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "expires=" + date.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }

        // 쿠키 읽기 함수
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i].trim();
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        }

        // 공통 클릭 이벤트 처리 함수
        function toggleAlbum(buttonId, albumId, cookieName) {
            var albumContainer = $(albumId);

            // 페이지 로딩 시 쿠키 값에 따라 초기 상태 설정
            var showAlbum = getCookie(cookieName);
            if (showAlbum === "hide") {
                albumContainer.css("display", "none");
            } else {
                albumContainer.css("display", "inline-block");
            }

            // 버튼 클릭 이벤트
            $(buttonId).on("click", function() {
                if (albumContainer.css("display") === "none") {
                    albumContainer.css("display", "inline-block");
                    setCookie(cookieName, "show", 10);
                    // 앨범이 열릴 때 iframe 높이 재조정
                    setTimeout(adjustIframeHeight, 100);
                } else {
                    albumContainer.css("display", "none");
                    setCookie(cookieName, "hide", 10);
                }
            });
        }

        // 앨범 버튼과 컨테이너 매핑
        toggleAlbum("#albumBtn1", "#album1", "showAlbum1");
        toggleAlbum("#albumBtn2", "#album2", "showAlbum2");
        toggleAlbum("#albumBtn3", "#album3", "showAlbum3");
        toggleAlbum("#albumBtn4", "#album4", "showAlbum4");
        toggleAlbum("#albumBtn5", "#album5", "showAlbum5");
        toggleAlbum("#albumBtn6", "#album6", "showAlbum6"); // 카페 자투라

        // 방문기록 남김
        var title = '<?php echo $title_message; ?>';
        saveMenuLog(title);
    });
</script>

</body>
</html>
