<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../bootstrap.php';
// =================================================================================
// 1. unit_price.php - 데이터 목록 조회 페이지
// =================================================================================
?>
<?php require_once(includePath('session.php'));

// --- 페이지 설정 ---
$title_message = '포미스톤 단가표';
$tablename = "phomi_unitprice";
?>
<?php include getDocumentRoot() . '/load_header.php' ?>

<title> <?= $title_message ?> </title>
<style>
  /* 테이블 행에 마우스를 올렸을 때 커서를 포인터로 변경 */
  .table-hover tbody tr:hover {
    cursor: pointer;
  }
  /* DataTables 검색창 오른쪽 정렬 */
  .dataTables_filter {
    float: right;
  }
  
  /* 토글 컬럼 스타일 */
  .toggle-column {
    transition: all 0.3s ease;
    width: 80px !important;
    max-width: 80px !important;
    min-width: 80px !important;
  }
  
  /* 토글 컬럼이 숨겨진 상태 */
  .toggle-column.hidden {
    display: none !important;
  }
  
  /* 테이블 컬럼 너비 조정 */
  #unitPriceTable th.toggle-column,
  #unitPriceTable td.toggle-column {
    width: 80px !important;
    max-width: 80px !important;
    min-width: 80px !important;
  }
  
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
    .container,
    .container-fluid {
      padding: 0.5rem !important;
      max-width: 100% !important;
      width: 100% !important;
      box-sizing: border-box !important;
      margin: 0 auto !important;
      overflow-x: hidden !important;
    }
    
    /* 카드 영역 최적화 */
    .card {
      margin: 0.5rem auto !important;
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      overflow-x: hidden !important;
    }
    
    .card-body {
      padding: 0.75rem !important;
      overflow-x: hidden !important;
      overflow-y: visible !important;
    }
    
    /* 제목 영역 최적화 */
    .d-flex.mt-3.mb-2.justify-content-center {
      flex-direction: row !important;
      flex-wrap: wrap !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 0.25rem !important;
      padding: 0.5rem !important;
      margin-bottom: 0.5rem !important;
      margin-top: 0.5rem !important;
    }
    
    .d-flex.mt-3.mb-2.justify-content-center h5 {
      width: 100% !important;
      text-align: center !important;
      font-size: 1.5rem !important;
      margin-bottom: 0.5rem !important;
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
      flex-basis: 100% !important;
      flex-shrink: 0 !important;
    }
    
    .d-flex.mt-3.mb-2.justify-content-center button {
      width: auto !important;
      flex: 0 0 auto !important;
      min-width: auto !important;
      max-width: none !important;
      margin: 0.125rem !important;
      padding: 0.375rem 0.75rem !important;
      font-size: 0.9rem !important;
      white-space: nowrap !important;
    }
    
    /* 모바일에서 신규 버튼 텍스트 변경 */
    .mobile-write-btn .btn-text-pc {
      display: none !important;
    }
    
    .mobile-write-btn .btn-text-mobile {
      display: inline !important;
    }
    
    /* 모바일에서 검색 영역의 신규 등록 버튼 숨김 */
    .pc-write-btn {
      display: none !important;
    }
    
    /* 검색 영역 최적화 */
    .d-flex.mt-4.mb-1.justify-content-center.align-items-center {
      flex-direction: row !important;
      flex-wrap: wrap !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 0.5rem !important;
      padding: 0.5rem !important;
      margin-top: 0.5rem !important;
      margin-bottom: 0.5rem !important;
    }
    
    /* 검색 컨테이너 - 한 행에 표시 */
    .search-container {
      flex-direction: row !important;
      flex-wrap: nowrap !important;
      align-items: center !important;
      justify-content: flex-start !important;
      gap: 0.5rem !important;
      padding: 0.25rem 0.5rem !important;
      width: 100% !important;
      overflow-x: hidden !important;
      overflow-y: hidden !important;
      margin-top: 0.25rem !important;
    }
    
    /* 입력 필드 래퍼 */
    .inputWrap {
      flex: 1 1 auto !important;
      min-width: 0 !important;
      position: relative !important;
      display: flex !important;
      align-items: center !important;
      overflow: hidden !important;
    }
    
    /* 검색 입력 필드 */
    .inputWrap input {
      width: 100% !important;
      max-width: 100% !important;
      padding: 0.5rem 80px 0.5rem 0.75rem !important;
      font-size: 1rem !important;
      margin: 0 !important;
      box-sizing: border-box !important;
      border: 2px solid #28a745 !important;
      border-radius: 0.5rem !important;
      background-color: #fff !important;
      overflow: hidden !important;
    }
    
    .inputWrap input::placeholder {
      color: #999 !important;
    }
    
    /* Clear 버튼 (X 아이콘) */
    .btnClear {
      position: absolute !important;
      right: 50px !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      width: 24px !important;
      height: 24px !important;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg>') no-repeat center !important;
      background-size: 16px 16px !important;
      border: none !important;
      cursor: pointer !important;
      z-index: 10 !important;
    }
    
    /* 검색 아이콘 버튼 (입력 필드 내부) */
    .btn-search-icon {
      position: absolute !important;
      right: 8px !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      width: 40px !important;
      height: 40px !important;
      min-width: 40px !important;
      padding: 0 !important;
      margin: 0 !important;
      border: none !important;
      background: transparent !important;
      border-radius: 0.25rem !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      z-index: 11 !important;
      cursor: pointer !important;
    }
    
    .btn-search-icon i {
      font-size: 1.2rem !important;
      color: #28a745 !important;
    }
    
    .btn-search-icon:hover {
      background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    /* 모바일에서는 PC 검색 버튼 숨김 */
    .search-container #searchBtn,
    .input-group #searchBtn {
      display: none !important;
    }
    
    /* 입력 그룹 최적화 */
    .input-group {
      width: 100% !important;
      max-width: 100% !important;
      flex-direction: row !important;
    }
    
    /* DataTables 숨기기 */
    .dataTables_length,
    .dataTables_filter,
    .dataTables_info,
    .dataTables_paginate {
      display: none !important;
    }
    
    /* 테이블 숨기기 (모바일에서는 카드로 표시) */
    #unitPriceTable {
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
    }
    
    .mobile-card span {
      flex: 1 !important;
      min-width: 0 !important;
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
      font-size: 0.8em !important;
    }
    
    /* 버튼 최적화 */
    button {
      white-space: nowrap !important;
    }
    
    .d-flex.mt-4.mb-1.justify-content-center.align-items-center button {
      width: auto !important;
      flex: 0 0 auto !important;
      min-width: auto !important;
      padding: 0.375rem 0.75rem !important;
      font-size: 0.9rem !important;
    }
  }
  
  /* PC 환경에서 모바일 전용 요소 숨김 */
  @media (min-width: 769px) {
    .btn-search-icon,
    #searchBtnMobile {
      display: none !important;
    }
    
    /* PC에서 모바일 신규 버튼 숨김 */
    .mobile-write-btn {
      display: none !important;
    }
    
    /* PC에서 신규 버튼 텍스트 표시 */
    .mobile-write-btn .btn-text-pc {
      display: inline !important;
    }
    
    .mobile-write-btn .btn-text-mobile {
      display: none !important;
    }
    
    /* PC에서 검색 영역의 신규 등록 버튼 표시 */
    .pc-write-btn {
      display: inline-block !important;
    }
  }
</style>
</head>

<body>
  <?php require_once(includePath('myheader.php')); ?>

  <?php
  // // --- 권한 확인 ---
  // if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
  //   echo "<script>alert('접근 권한이 없습니다.'); location.href='" . $_SESSION["WebSite"] . "login/login_form.php';</script>";
  //   exit;
  // }

  require_once(includePath('lib/mydb.php'));
  $pdo = db_connect();

  // --- 검색 처리 ---
  $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

      try {
      // 검색어가 있을 경우와 없을 경우의 SQL문 분기
      $sql = "SELECT * FROM mirae8440." . $tablename;
      if (!empty($search)) {
        $sql .= " WHERE prodcode LIKE ? OR texture_eng LIKE ? OR texture_kor LIKE ? OR design_eng LIKE ? OR design_kor LIKE ? OR type LIKE ? OR size LIKE ? OR thickness LIKE ? OR area LIKE ? OR dist_price_per_m2 LIKE ? OR dist_price_total LIKE ? OR retail_price_per_m2 LIKE ? OR retail_price_total LIKE ?";
        $search_param = "%" . $search . "%";
      }
      $sql .= " ORDER BY CAST(SUBSTRING(prodcode, 1, 1) AS CHAR) ASC, CAST(SUBSTRING(prodcode, 2) AS UNSIGNED) ASC";
      
      $stmh = $pdo->prepare($sql);
      
      // 검색어가 있을 경우에만 파라미터 바인딩
      if (!empty($search)) {
        $stmh->bindValue(1, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(2, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(3, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(4, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(5, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(6, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(7, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(8, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(9, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(10, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(11, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(12, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(13, $search_param, PDO::PARAM_STR);
      }

    $stmh->execute();
    $total_row = $stmh->rowCount();
  } catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
    exit;
  }
  ?>

  <form name="board_form" id="board_form" method="post" action="unit_price.php">
    <div class="container-fluid justify-content-center">
      <div class="card mt-2 mb-4">
        <div class="card-body">
          <div class="d-flex mt-3 mb-2 justify-content-center">
            <h5> <?= $title_message ?> </h5>
            <button type="button" class="btn btn-dark btn-sm mx-3" onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>
            <button type="button" class="btn btn-danger btn-sm mx-1" onclick="location.href='list.php';" title="수주 관리">
						<i class="bi bi-file-earmark-text"></i> 수주
					</button>
					<button type="button" class="btn btn-primary btn-sm mx-1" onclick="location.href='list_estimate.php';" title="견적 관리">
						<i class="bi bi-file-earmark-text"></i> 견적
					</button>
					<button type="button" class="btn btn-success btn-sm mx-1" onclick="location.href='list_outorder.php';" title="출고요청서">
						<i class="bi bi-box-seam"></i> 출고요청서
					</button>
					<button type="button" class="btn btn-warning btn-sm mx-1" onclick="window.open('intro_goods.php', 'introGoodsPopup', 'width=1200,height=800,scrollbars=yes,resizable=yes');" title="색상 정보 테이블">
						<i class="bi bi-palette-fill"></i> 색상정보
					</button>
					<button type="button" class="btn btn-primary btn-sm mx-1 mobile-write-btn" id="writeBtn"> <i class="bi bi-pencil"></i> <span class="btn-text-pc">신규 등록</span><span class="btn-text-mobile">신규</span> </button>
					<!-- <button type="button" class="btn btn-secondary btn-sm mx-1" onclick="location.href='unit_price.php';" title="단가표">
						<i class="bi bi-currency-dollar"></i> 단가표
					</button>	             -->
          </div>

          <div class="d-flex mt-4 mb-1 justify-content-center align-items-center">
            <?php if (isset($_SESSION['level']) && $_SESSION['level'] == '1'): ?>
              <div class="form-check form-switch me-3">
                <input class="form-check-input fs-6" type="checkbox" id="showHeadOfficePrice" name="showHeadOfficePrice">
                <label class="form-check-label fs-6" for="showHeadOfficePrice">공급가 보기</label>
              </div>
            <?php endif; ?>
            <div class="d-flex justify-content-center align-items-center search-container">
              <div class="inputWrap">
                <input type="text" id="search" class="form-control" name="search" autocomplete="off" value="<?= $search ?>" placeholder="품목코드, 질감, 디자인 등">
                <button class="btnClear" type="button"></button>
                <button type="button" id="searchBtnMobile" class="btn-search-icon">
                  <i class="bi bi-search"></i>
                </button>
              </div>
              <?php 
              // PC에서만 표시되는 검색 버튼
              if (!isset($chkMobile) || !$chkMobile): 
              ?>
                <button id="searchBtn" type="submit" class="btn btn-dark btn-sm ms-2"><i class="bi bi-search"></i></button>
              <?php endif; ?>
            </div>
            <!-- PC에서만 표시되는 신규 등록 버튼 -->
            <?php if (!isset($chkMobile) || !$chkMobile): ?>
              <button type="button" class="btn btn-primary btn-sm mx-2 pc-write-btn" id="writeBtnPc"> <i class="bi bi-pencil"></i> 신규 등록 </button>
            <?php endif; ?>
          </div>

          <div class="row d-flex mt-3">
            <div class="col">
              <!-- 모바일 카드 컨테이너 -->
              <div id="mobileCardsContainer" class="mobile-cards-container"></div>
              <table class="table table-hover table-sm table-bordered" id="unitPriceTable">
                <thead class="table-primary text-center">
                  <tr>
                    <th>품목코드</th>
                    <th>질감(영문)</th>
                    <th>질감(한글)</th>
                    <th>디자인(영문)</th>
                    <th>디자인(한글)</th>
                    <th>이미지</th>
                    <th>분류</th>
                    <th>사이즈</th>
                    <th>두께(mm)</th>
                    <th>헤베(㎡)</th>
                    <?php if (isset($_SESSION['level']) && $_SESSION['level'] == '1'): ?>
                      <th class="toggle-column hidden">공급가(㎡)</th>
                    <?php endif; ?>
                    <th>대리점가(㎡)</th>
                    <th>유통가(㎡)</th>
                    <th>유통가(원장)</th>
                    <th>소비자가(㎡)</th>
                    <th>소비자가(원장)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                    <tr onclick="redirectToEdit('<?= $row['num'] ?>', '<?= $tablename ?>')">
                      <td class="text-center"><?= $row['prodcode'] ?></td>
                      <td><?= $row['texture_eng'] ?></td>
                      <td><?= $row['texture_kor'] ?></td>
                      <td><?= $row['design_eng'] ?></td>
                      <td><?= $row['design_kor'] ?></td>
                      <td class="text-center">
                        <?php if (!empty($row['image_url'])): ?>
                          <a href="javascript:void(0);" onclick="openPagePopup('<?= $row['image_url'] ?>'); event.stopPropagation();" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-image"></i>
                          </a>
                        <?php else: ?>
                          <span class="text-muted">-</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center"><?= $row['type'] ?></td>
                      <td class="text-center"><?= $row['size'] ?></td>
                      <td class="text-center"><?= $row['thickness'] ?></td>
                      <td class="text-center"><?= $row['area'] ?></td>
                      <?php if (isset($_SESSION['level']) && $_SESSION['level'] == '1'): ?>
                        <td class="text-end toggle-column hidden"><?= number_format($row['price_per_m2']) ?></td>
                      <?php endif; ?>
                      <td class="text-end"><?= number_format($row['price_agent']) ?></td>
                      <td class="text-end"><?= number_format($row['dist_price_per_m2']) ?></td>
                      <td class="text-end"><?= number_format($row['dist_price_total']) ?></td>
                      <td class="text-end"><?= number_format($row['retail_price_per_m2']) ?></td>
                      <td class="text-end"><?= number_format($row['retail_price_total']) ?></td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div><!--card-body-->
      </div><!--card -->
    </div><!--container-->
  </form>

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
    var processedTables = new Set();
    
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
        processedTables.clear();
        return;
      }
      
      isRenderingCards = true;
      
      var table = document.getElementById('unitPriceTable');
      if (!table || processedTables.has(table)) {
        isRenderingCards = false;
        return;
      }
      
      processedTables.add(table);
      
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
        card.style.cssText = 'border: 1px solid #ddd; border-radius: 0.5rem; padding: 0.75rem; background: #f8f9fa; cursor: pointer; box-sizing: border-box; width: 100%; max-width: 100%;';
        
        var onclickAttr = row.getAttribute('onclick');
        if (onclickAttr) {
          card.onclick = function() {
            eval(onclickAttr);
          };
        }
        
        var cells = row.querySelectorAll('td');
        cells.forEach(function(cell, index) {
          if (index >= headers.length) return;
          
          var label = headers[index] ? headers[index].textContent.trim() : '';
          var cellText = cell.textContent.trim();
          
          // 숨겨진 컬럼은 건너뛰기
          if (cell.classList.contains('hidden') || cell.closest('tr').querySelector('td.toggle-column.hidden') === cell) {
            return;
          }
          
          var cardItem = document.createElement('div');
          cardItem.className = 'd-flex align-items-center mb-2';
          
          var labelSpan = document.createElement('strong');
          labelSpan.textContent = label + ':';
          labelSpan.className = 'me-2';
          
          var valueSpan = document.createElement('span');
          
          // 이미지 버튼이 있는 경우
          var imageBtn = cell.querySelector('a.btn');
          if (imageBtn) {
            var clonedBtn = imageBtn.cloneNode(true);
            clonedBtn.onclick = function(e) {
              e.stopPropagation();
              var originalOnclick = imageBtn.getAttribute('onclick');
              if (originalOnclick) {
                eval(originalOnclick);
              }
            };
            valueSpan.appendChild(clonedBtn);
          } else {
            valueSpan.textContent = cellText;
          }
          
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
    
    // Clear 버튼 클릭 이벤트
    $(document).on('click', '.btnClear', function() {
      $('#search').val('').focus();
    });
    
    $(document).ready(function() {
      // DataTables 초기화
      var table = $('#unitPriceTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": false, // 상단의 커스텀 검색 사용
        "pageLength": 120,
        "lengthMenu": [25, 50, 100, 120, 200, 500, 1000],
        "language": {
          "lengthMenu": "_MENU_ 개씩 보기",
          "zeroRecords": "표시할 데이터가 없습니다.",
          "info": "페이지 _PAGE_ / _PAGES_ (총 <?= $total_row ?> 개)",
          "infoEmpty": "",
          "infoFiltered": "",
          "paginate": {
            "first": "처음",
            "last": "마지막",
            "next": "다음",
            "previous": "이전"
          }
        },
        "order": [
          [0, 'asc']
        ], // 품목코드 기준 오름차순 정렬
        "initComplete": function() {
          // 모바일에서 카드 형식으로 변환
          if (isMobile()) {
            setTimeout(function() {
              processedTables.clear();
              debouncedRenderMobileCards();
            }, 200);
          }
        },
        "drawCallback": function() {
          // 페이지 변경 시 모바일 카드 다시 렌더링
          if (isMobile()) {
            setTimeout(function() {
              debouncedRenderMobileCards();
            }, 200);
          }
        },
        "columnDefs": [
          {
            "targets": 0, // 품목코드 열
            "type": "string",
            "render": function(data, type, row) {
              if (type === 'sort') {
                // 정렬용 데이터: 첫 글자 + 숫자 부분을 0으로 패딩
                const match = data.match(/^([A-Z])(\d+)$/);
                if (match) {
                  const letter = match[1];
                  const number = match[2].padStart(3, '0');
                  return letter + number;
                }
                return data;
              }
              return data; // 표시용 데이터는 그대로
            }
          }
        ]
      });

      // 페이지 로드 시 저장된 체크박스 상태 복원
      var savedState = localStorage.getItem('showHeadOfficePrice');
      console.log('저장된 상태:', savedState); // 디버깅용
      
      if (savedState === 'true' || savedState === true) {
        $('#showHeadOfficePrice').prop('checked', true);
        // 약간의 지연 후 공급가 열 표시 (DOM이 완전히 로드된 후)
        setTimeout(function() {
          showSupplyPriceColumn();
        }, 100);
      } else {
        // 체크되지 않은 상태로 초기화
        $('#showHeadOfficePrice').prop('checked', false);
        localStorage.setItem('showHeadOfficePrice', 'false');
        // 초기 상태에서는 공급가 열이 이미 숨겨져 있으므로 별도 처리 불필요
      }
      
      // 초기 로드 시 모바일 카드 렌더링
      if (isMobile()) {
        setTimeout(function() {
          processedTables.clear();
          debouncedRenderMobileCards();
        }, 500);
      }

      // 공급가 보기 체크박스 이벤트 처리
      $('#showHeadOfficePrice').change(function() {
        var isChecked = $(this).is(':checked');
        console.log('체크박스 상태 변경:', isChecked); // 디버깅용
        
        // 상태를 localStorage에 저장
        localStorage.setItem('showHeadOfficePrice', isChecked.toString());
        
        if (isChecked) {
          // 공급가 열 표시
          showSupplyPriceColumn();
        } else {
          // 공급가 열 숨김
          hideSupplyPriceColumn();
        }
      });

      // 공급가 열 표시 함수는 위에서 정의됨
      function hideSupplyPriceColumn() {
        console.log('공급가 열 숨김 함수 실행');
        
        // CSS 클래스 추가로 공급가 열을 숨김
        $('.toggle-column').addClass('hidden');
        
        // 모바일 카드 다시 렌더링
        if (isMobile()) {
          setTimeout(function() {
            processedTables.clear();
            debouncedRenderMobileCards();
          }, 200);
        }
      }
      
      // 공급가 열 표시 함수 수정
      function showSupplyPriceColumn() {
        console.log('공급가 열 표시 함수 실행');
        
        // CSS 클래스 제거로 공급가 열을 표시
        $('.toggle-column').removeClass('hidden');
        
        // 모바일 카드 다시 렌더링
        if (isMobile()) {
          setTimeout(function() {
            processedTables.clear();
            debouncedRenderMobileCards();
          }, 200);
        }
      }
      
      // 창 크기 변경 시 카드 렌더링
      $(window).on('resize', debounce(function() {
        if (isMobile()) {
          setTimeout(function() {
            processedTables.clear();
            debouncedRenderMobileCards();
          }, 200);
        } else {
          var containers = document.querySelectorAll('.mobile-cards-container');
          containers.forEach(function(container) {
            container.innerHTML = '';
          });
          processedTables.clear();
        }
      }, 300));

      // 숫자 포맷팅 함수
      function number_format(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }
    });

    // 페이지 팝업 열기
    function openPagePopup(pageUrl) {
      window.open(pageUrl, 'pagePopup', 'width=1000,height=700,scrollbars=yes,resizable=yes');
    }

    // 수정 폼으로 이동 (팝업)
    function redirectToEdit(num, tablename) {
      var url = "unit_price_form.php?mode=edit&num=" + num + "&tablename=" + tablename;
      // window.open(url, '단가 정보 수정', 'width=1000,height=800,scrollbars=yes');
      customPopup(url, '단가 정보 수정', 1000, 900);
    }

    // 신규 등록 폼으로 이동 (팝업) - 모바일과 PC 모두 처리
    $("#writeBtn, #writeBtnPc").click(function() {
      var tablename = '<?= $tablename ?>';
      var url = "unit_price_form.php?tablename=" + tablename;
      // window.open(url, '단가 정보 신규 등록', 'width=1000,height=800,scrollbars=yes');
      customPopup(url, '단가 정보 신규 등록', 1000, 900);
    });

    // 검색 입력 필드 Enter 키 처리
    $(document).ready(function() {
      $("#search").keydown(function(event) {
        if (event.key === "Enter" || event.keyCode === 13) {
          event.preventDefault();
          if (isMobile()) {
            $("#searchBtnMobile").click();
          } else {
            $("#searchBtn").click();
          }
        }
      });
    });
    
    // 검색 버튼 클릭 이벤트 (PC용과 모바일용 모두)
    $(document).ready(function() {
      $("#searchBtn, #searchBtnMobile").click(function(e) {
        e.preventDefault();
        document.getElementById('board_form').submit();
        
        // 모바일에서 카드 다시 렌더링
        if (isMobile()) {
          setTimeout(function() {
            processedTables.clear();
            debouncedRenderMobileCards();
          }, 500);
        }
      });
    });
    
    // 서버에 작업 기록
    $(document).ready(function() {
      saveLogData('포미스톤 단가표 조회');
    });
  </script>
</body>
</html>