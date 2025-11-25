<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 기간을 정하는 구간	 
$todate = date("Y-m-d"); // 현재일자 변수지정   	
$common = " order by num desc ";  // 출고예정일이 현재일보다 클때 조건	
$sql = "select * from mirae8440.logdata " . $common; 			
$nowday = date("Y-m-d");   // 현재일자 변수지정   
$counter = 0;	
?>

<?php include getDocumentRoot() . '/load_header.php' ?>
<title>포미스톤 색상 정보 테이블</title>
<style>
  /* 모바일 환경 최적화 */
  @media (max-width: 768px) {
    /* body와 html 오버플로우 방지 */
    html, body {
      overflow-x: hidden !important;
      max-width: 100% !important;
      width: 100% !important;
      box-sizing: border-box !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    
    * {
      max-width: 100% !important;
      box-sizing: border-box !important;
    }
    
    /* 컨테이너 최적화 */
    .container {
      padding: 0.5rem !important;
      max-width: 100% !important;
      width: 100% !important;
      margin: 0 auto !important;
      overflow-x: hidden !important;
    }
    
    /* 제목 및 닫기 버튼 영역 최적화 */
    .d-flex.justify-content-between.align-items-center {
      position: relative !important;
      width: 100% !important;
      margin-bottom: 1rem !important;
      padding: 0 0.5rem !important;
    }
    
    h5 {
      font-size: 1.25rem !important;
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
      margin-bottom: 0 !important;
      padding: 0 !important;
      flex-grow: 1 !important;
      text-align: center !important;
    }
    
    /* 닫기 버튼 최적화 */
    #closeBtn {
      position: absolute !important;
      right: 0.5rem !important;
      top: 0 !important;
      flex-shrink: 0 !important;
      padding: 0.375rem 0.75rem !important;
      font-size: 0.85rem !important;
      white-space: nowrap !important;
      z-index: 10 !important;
    }
    
    #closeBtn i {
      margin-right: 0.25rem !important;
    }
    
    /* 테이블 숨기기 (모바일에서는 카드로 표시) */
    .table-responsive,
    .table {
      display: none !important;
    }
    
    /* 모바일 카드 컨테이너 */
    .mobile-cards-container {
      width: 100% !important;
      max-width: 100% !important;
      padding: 0.5rem 0 !important;
      box-sizing: border-box !important;
    }
    
    .mobile-card {
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      overflow-x: hidden !important;
      margin-bottom: 0.75rem !important;
      border: 1px solid #ddd !important;
      border-radius: 0.5rem !important;
      padding: 0.75rem !important;
      background: #f8f9fa !important;
    }
    
    .mobile-card .d-flex {
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
    }
    
    .mobile-card strong {
      flex-shrink: 0 !important;
      min-width: fit-content !important;
      color: #0d6efd !important;
      margin-right: 0.5rem !important;
    }
    
    .mobile-card span {
      flex: 1 !important;
      min-width: 0 !important;
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
      font-size: 0.9rem !important;
    }
    
    /* 텍스트 줄바꿈 */
    span, text, label, p, div, td {
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
    }
  }
  
  /* PC 화면에서 닫기 버튼 스타일 */
  .d-flex.justify-content-between.align-items-center {
    position: relative;
    width: 100%;
  }
  
  #closeBtn {
    position: absolute;
    right: 0;
    top: 0;
    flex-shrink: 0;
  }
  
  #closeBtn i {
    margin-right: 0.25rem;
  }
</style>
</head> 

<body class="p-10">
<div class="container mt-4 d-flex justify-content-center">
  <div style="position: relative; width: 100%;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="mb-0 fw-bold text-center flex-grow-1">포미스톤 색상 정보 테이블</h5>
      <button type="button" class="btn btn-secondary btn-sm" id="closeBtn" onclick="window.close()" style="position: absolute; right: 0; top: 0;">
        <i class="bi bi-x-lg"></i> 닫기
      </button>
    </div>
    <!-- 모바일 카드 컨테이너 -->
    <div id="mobileCardsContainer" class="mobile-cards-container"></div>
    <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm align-middle" id="colorTable">
        <thead class="table-dark">
          <tr>
            <th class="text-center">영문 색상명</th>
            <th class="text-center">한글어 발음</th>
            <th class="text-center">뜻/느낌 설명</th>
          </tr>
        </thead>
        <tbody>
          <tr><td class="text-start">CASTOL WHITE</td><td class="text-start">캐스톨 화이트</td><td class="text-start">고급스러운 흰색, 약간 미색 느낌</td></tr>
          <tr><td class="text-start">VEIL GRAY</td><td class="text-start">베일 그레이</td><td class="text-start">얇은 베일처럼 흐릿한 회색</td></tr>
          <tr><td class="text-start">VEIL DARK GREY</td><td class="text-start">베일 다크 그레이</td><td class="text-start">짙은 회색, 안개 낀 느낌</td></tr>
          <tr><td class="text-start">SHAHARA LIGHT GRAY</td><td class="text-start">사하라 라이트 그레이</td><td class="text-start">사막의 밝은 회색, 모래빛 회색</td></tr>
          <tr><td class="text-start">CLOUD YELLOW</td><td class="text-start">클라우드 옐로우</td><td class="text-start">연한 노란색, 구름 틈 햇살 느낌</td></tr>
          <tr><td class="text-start">ANDES WHITE</td><td class="text-start">안데스 화이트</td><td class="text-start">산맥처럼 청량하고 밝은 흰색</td></tr>
          <tr><td class="text-start">NILE DARK GREY</td><td class="text-start">나일 다크 그레이</td><td class="text-start">나일강의 깊은 물빛 회색</td></tr>
          <tr><td class="text-start">ANDES GRAY</td><td class="text-start">안데스 그레이</td><td class="text-start">차분한 회색, 자연 느낌</td></tr>
          <tr><td class="text-start">SUNIS WHITE</td><td class="text-start">수니스 화이트</td><td class="text-start">밝고 따뜻한 흰색 (브랜드성 이름일 수 있음)</td></tr>
          <tr><td class="text-start">KAMU RED</td><td class="text-start">카무 레드</td><td class="text-start">강렬한 빨강색 (Kamu는 브랜드/지명일 수 있음)</td></tr>
          <tr><td class="text-start">CLOUD WHITE</td><td class="text-start">클라우드 화이트</td><td class="text-start">순백색, 구름처럼 뽀얀 느낌</td></tr>
          <tr><td class="text-start">PLAIN WHITE</td><td class="text-start">플레인 화이트</td><td class="text-start">순수하고 단순한 흰색</td></tr>
          <tr><td class="text-start">AUSTRALIA YELLOW</td><td class="text-start">오스트레일리아 옐로우</td><td class="text-start">따뜻한 호주 햇살 같은 노란색</td></tr>
          <tr><td class="text-start">GREYISH DESERT</td><td class="text-start">그레이쉬 데저트</td><td class="text-start">회갈색에 가까운 색, 건조한 느낌</td></tr>
          <tr><td class="text-start">DARK GRAY</td><td class="text-start">다크 그레이</td><td class="text-start">짙은 회색, 중후한 느낌</td></tr>
          <tr><td class="text-start">PORTORO</td><td class="text-start">포르토로</td><td class="text-start">고급 검정 대리석 색상 (금줄이 섞인 경우 많음)</td></tr>
          <tr><td class="text-start">NILE LIGHT YELLOW</td><td class="text-start">나일 라이트 옐로우</td><td class="text-start">은은한 노란색, 부드러운 느낌</td></tr>
          <tr><td class="text-start">CASTOL BLUE</td><td class="text-start">캐스톨 블루</td><td class="text-start">깊고 중후한 파란색 (브랜드 느낌)</td></tr>
          <tr><td class="text-start">ANDES YELLOW</td><td class="text-start">안데스 옐로우</td><td class="text-start">산맥의 빛나는 노란빛</td></tr>
          <tr><td class="text-start">BLUE GREY</td><td class="text-start">블루 그레이</td><td class="text-start">푸른빛이 도는 회색</td></tr>
          <tr><td class="text-start">ANDES GOLD</td><td class="text-start">안데스 골드</td><td class="text-start">금빛이 감도는 고급스러운 색상</td></tr>
          <tr><td class="text-start">MULTI-COLOR RED</td><td class="text-start">멀티컬러 레드</td><td class="text-start">여러 색이 섞인 붉은 계열</td></tr>
          <tr><td class="text-start">H02</td><td class="text-start">H02 (코드명)</td><td class="text-start">색상 코드, 별도 지정된 의미 없음</td></tr>
          <tr><td class="text-start">H04</td><td class="text-start">H04 (코드명)</td><td class="text-start">색상 코드, 별도 지정된 의미 없음</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  // 모바일 환경 체크 함수
  function isMobile(breakpoint) {
    breakpoint = breakpoint || 768;
    return window.innerWidth <= breakpoint;
  }
  
  // 디바운스 함수
  function debounce(func, wait) {
    var timeout;
    return function executedFunction(...args) {
      var later = function() {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
  
  // 모바일 카드 렌더링 플래그
  var isRenderingCards = false;
  
  // 모바일 카드 렌더링 함수
  function renderMobileCards() {
    if (isRenderingCards) {
      return;
    }
    
    if (window.innerWidth > 768) {
      var containers = document.querySelectorAll('.mobile-cards-container');
      containers.forEach(function(container) {
        container.innerHTML = '';
      });
      return;
    }
    
    isRenderingCards = true;
    
    var table = document.getElementById('colorTable');
    if (!table) {
      isRenderingCards = false;
      return;
    }
    
    var cardsContainer = document.getElementById('mobileCardsContainer');
    if (!cardsContainer) {
      isRenderingCards = false;
      return;
    }
    
    cardsContainer.innerHTML = '';
    
    var headers = table.querySelectorAll('thead th');
    var rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(function(row) {
      var card = document.createElement('div');
      card.className = 'card mobile-card';
      card.style.cssText = 'border: 1px solid #ddd; border-radius: 0.5rem; padding: 0.75rem; background: #f8f9fa; box-sizing: border-box; width: 100%; max-width: 100%;';
      
      var cells = row.querySelectorAll('td');
      cells.forEach(function(cell, index) {
        if (index >= headers.length) return;
        
        var label = headers[index] ? headers[index].textContent.trim() : '';
        var cellText = cell.textContent.trim();
        
        var cardItem = document.createElement('div');
        cardItem.className = 'd-flex align-items-center mb-2';
        
        var labelSpan = document.createElement('strong');
        labelSpan.textContent = label + ':';
        labelSpan.className = 'me-2';
        
        var valueSpan = document.createElement('span');
        valueSpan.textContent = cellText;
        
        cardItem.appendChild(labelSpan);
        cardItem.appendChild(valueSpan);
        card.appendChild(cardItem);
      });
      
      cardsContainer.appendChild(card);
    });
    
    isRenderingCards = false;
  }
  
  // 디바운스된 렌더링 함수
  var debouncedRenderMobileCards = debounce(renderMobileCards, 300);
  
  // 페이지 로드 시 카드 렌더링
  $(document).ready(function() {
    if (isMobile()) {
      setTimeout(function() {
        debouncedRenderMobileCards();
      }, 200);
    }
  });
  
  // 창 크기 변경 시 카드 렌더링
  $(window).on('resize', debounce(function() {
    if (isMobile()) {
      setTimeout(function() {
        debouncedRenderMobileCards();
      }, 200);
    } else {
      var containers = document.querySelectorAll('.mobile-cards-container');
      containers.forEach(function(container) {
        container.innerHTML = '';
      });
    }
  }, 300));
</script>
</body>
</html>
