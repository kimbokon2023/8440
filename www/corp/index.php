<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$WebSite = $_SESSION["WebSite"] ?? '';

// 첫 화면 표시 문구
$title_message = '거래처 조회';

// // 권한 체크
// if (!isset($_SESSION["level"]) || $level > 5) {
//     sleep(1);
    
//     // 로컬/서버 환경에 따른 동적 리다이렉션
//     $host = $_SERVER['HTTP_HOST'] ?? '';
//     if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
//         header("Location: http://" . $host . "/login/login_form.php");
//     } else {
//         header("Location: http://" . $host . "/login/login_form.php");
//     }
//     exit;
// }

include getDocumentRoot() . '/load_header.php';
?>
<title> <?=$title_message?> </title>
<!-- Tabulator CSS and JS -->
<link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<body>
    <?php require_once(includePath('myheader.php')); ?>   

<style>
/* Light mode styles */
body {
  background-color: #ffffff;
  color: #000000;
  overflow-x: hidden; /* 가로 스크롤 제거 */
}

/* 모바일 전용 스타일 */
@media (max-width: 768px) {
  body {
    font-size: 16px; /* iOS 줌 방지 */
    overflow-x: hidden; /* 모바일에서만 가로 스크롤 방지 */
  }

  /* 컨테이너 패딩 조정 */
  .container-fluid {
    padding-left: 5px !important;
    padding-right: 5px !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  /* 카드 마진/패딩 줄이기 */
  .card {
    margin-bottom: 0.25rem !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    border-radius: 8px;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .card-body {
    padding: 0.4rem 0.3rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .card-header {
    padding: 0.4rem 0.3rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  /* 페이지 헤더 최적화 */
  .page-header {
    padding: 0.75rem 0.5rem !important;
    margin-bottom: 0.5rem !important;
  }

  .page-header h1 {
    font-size: 1.2rem !important;
    margin: 0 !important;
  }

  .page-header p {
    font-size: 0.8rem !important;
    margin: 0.125rem 0 0 !important;
  }

  /* 액션 바 최적화 */
  .action-bar {
    margin-bottom: 0.5rem !important;
    padding: 0.25rem 0 !important;
    flex-wrap: wrap !important;
    gap: 0.25rem !important;
  }

  .action-bar .btn-group {
    gap: 0.25rem !important;
    flex-wrap: wrap !important;
  }

  .action-bar .btn {
    padding: 0.4rem 0.6rem !important;
    font-size: 0.8rem !important;
    min-height: 36px !important;
    max-width: 100% !important;
    flex-shrink: 0 !important;
    box-sizing: border-box !important;
  }

  /* 버튼 그룹 스택 방식 */
  .d-flex.align-items-center {
    flex-wrap: wrap;
    gap: 0.25rem;
  }

  /* 버튼 크기 조정 */
  .btn-sm {
    padding: 0.3rem 0.5rem !important;
    font-size: 0.75rem !important;
    min-height: 36px !important;
    min-width: 36px !important;
    margin: 0.125rem !important;
    max-width: 100% !important;
    flex-shrink: 0 !important;
    box-sizing: border-box !important;
  }

  /* 대형 버튼들 */
  .btn:not(.btn-sm) {
    min-height: 40px !important;
    padding: 0.5rem 0.75rem !important;
    font-weight: 500;
    font-size: 0.85rem !important;
    max-width: 100% !important;
    flex-shrink: 0 !important;
    box-sizing: border-box !important;
  }

  /* 필터 섹션 최적화 */
  .filter-section {
    padding: 0.5rem 0.4rem !important;
    margin-bottom: 0.5rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .filter-form {
    flex-direction: column !important;
    gap: 0.5rem !important;
    align-items: stretch !important;
  }

  .filter-group {
    min-width: 100% !important;
    max-width: 100% !important;
    margin-bottom: 0.25rem !important;
  }

  .filter-group label {
    font-size: 0.75rem !important;
    margin-bottom: 0.25rem !important;
  }

  /* 검색 입력 필드 최적화 */
  .search-input-wrapper {
    max-width: 100% !important;
    width: 100% !important;
  }

  .search-input-wrapper input {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0.4rem 2.5rem 0.4rem 2.4rem !important;
    font-size: 0.9rem !important;
    min-height: 40px !important;
    box-sizing: border-box !important;
  }

  /* 필터 버튼 최적화 */
  .filter-buttons {
    flex-wrap: wrap !important;
    gap: 0.25rem !important;
  }

  .filter-buttons .btn {
    padding: 0.3rem 0.6rem !important;
    font-size: 0.75rem !important;
    min-height: 36px !important;
    flex: 1 1 auto !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  /* 검색 영역 수직 스택 */
  .d-flex.justify-content-center.align-items-center {
    flex-direction: column;
    gap: 0.5rem;
  }

  .d-flex.justify-content-center.align-items-center > * {
    margin: 0.125rem;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  /* 검색 드롭다운과 입력필드 */
  #find, #search {
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  /* 검색 영역 모바일 최적화 */
  .form-control, .form-select {
    font-size: 16px; /* iOS 줌 방지 */
    min-height: 40px !important;
    padding: 0.4rem 0.5rem !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  /* 아이콘 크기 조정 */
  .bi {
    font-size: 1em !important;
  }

  /* 타이틀 항용 */
  h5 {
    font-size: 1.2rem !important;
    font-weight: 600;
    margin: 0.25rem 0 !important;
  }

  /* 페이지네이션 최적화 */
  .pagination-info {
    padding: 0.5rem 0.25rem !important;
    margin: 0.25rem 0 !important;
    flex-wrap: wrap !important;
    gap: 0.5rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .pagination-controls {
    flex-wrap: wrap !important;
    gap: 0.25rem !important;
    max-width: 100% !important;
  }

  .pagination-controls label {
    font-size: 0.75rem !important;
    margin: 0 !important;
  }

  .pagination-controls select {
    padding: 0.3rem 0.5rem !important;
    font-size: 0.75rem !important;
    min-height: 36px !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  .pagination-controls span {
    font-size: 0.75rem !important;
    padding: 0.25rem 0.5rem !important;
  }

  /* 텍스트/버튼이 카드 영역을 벗어나지 않도록 */
  * {
    max-width: 100% !important;
    box-sizing: border-box !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
  }

  /* Tabulator 모바일 카드 형식 */
  #tabulator-table {
    display: none !important;
  }

  #mobile-tabulator-cards {
    display: block !important;
    padding: 0.5rem 0.4rem !important;
  }

  .mobile-tabulator-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.5rem 0.4rem !important;
    margin-bottom: 0.5rem !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .mobile-tabulator-card-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 0.4rem !important;
    padding-bottom: 0.4rem !important;
    border-bottom: 1px solid #f1f5f9;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .mobile-tabulator-card-item:last-child {
    border-bottom: none;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
  }

  .mobile-tabulator-card-label {
    font-size: 0.7rem !important;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 0.25rem !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .mobile-tabulator-card-value {
    font-size: 0.85rem !important;
    color: #1f2937;
    word-wrap: break-word;
    overflow-wrap: break-word;
    max-width: 100% !important;
  }

  .mobile-tabulator-card-value .badge {
    font-size: 0.7rem !important;
    padding: 0.2rem 0.4rem !important;
  }

  /* 모달 모바일 최적화 */
  .modal-dialog {
    margin: 0.25rem !important;
    max-width: calc(100% - 0.5rem) !important;
  }

  .modal-dialog.modal-xl {
    max-width: calc(100% - 0.5rem) !important;
    margin: 0.25rem !important;
  }

  .modal-dialog.modal-fullscreen {
    margin: 0 !important;
    max-width: 100% !important;
    height: 100vh !important;
  }

  .modal-content {
    border-radius: 8px !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .modal-header {
    padding: 0.5rem 0.4rem !important;
    border-bottom: 1px solid rgba(255,255,255,0.2) !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
    flex-wrap: wrap !important;
  }

  .modal-header .modal-title {
    font-size: 1rem !important;
    margin: 0 !important;
    padding: 0 !important;
    flex: 1 1 auto !important;
    min-width: 0 !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
  }

  .modal-header .btn-close {
    padding: 0.25rem !important;
    margin: 0 !important;
    font-size: 0.8rem !important;
    flex-shrink: 0 !important;
  }

  .modal-header .btn {
    padding: 0.3rem 0.5rem !important;
    font-size: 0.75rem !important;
    min-height: 36px !important;
    margin: 0.125rem !important;
    flex-shrink: 0 !important;
  }

  .modal-header > div {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0.25rem !important;
    align-items: center !important;
  }

  .modal-body {
    padding: 0.5rem 0.4rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
    font-size: 0.9rem !important;
  }

  .modal-footer {
    padding: 0.5rem 0.4rem !important;
    border-top: 1px solid #dee2e6 !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
    flex-wrap: wrap !important;
    gap: 0.25rem !important;
  }

  .modal-footer .btn {
    padding: 0.4rem 0.6rem !important;
    font-size: 0.8rem !important;
    min-height: 36px !important;
    flex: 1 1 auto !important;
    min-width: 0 !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  /* 상세 정보 섹션 모바일 최적화 */
  .detail-section {
    margin-bottom: 0.75rem !important;
    padding: 0.5rem 0.25rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .detail-section-title {
    font-size: 0.85rem !important;
    margin-bottom: 0.5rem !important;
    padding: 0.25rem 0 !important;
  }

  .detail-grid {
    grid-template-columns: 1fr !important;
    gap: 0.5rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .detail-item {
    padding: 0.5rem 0.4rem !important;
    margin-bottom: 0.25rem !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
  }

  .detail-label {
    font-size: 0.7rem !important;
    margin-bottom: 0.25rem !important;
  }

  .detail-value {
    font-size: 0.85rem !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    max-width: 100% !important;
  }

  .detail-table {
    font-size: 0.8rem !important;
    max-width: 100% !important;
    overflow-x: auto !important;
    display: block !important;
  }

  .detail-table th,
  .detail-table td {
    padding: 0.4rem 0.5rem !important;
    font-size: 0.75rem !important;
  }

  /* 첨부 파일 목록 모바일 최적화 */
  .customer-files-list {
    gap: 0.5rem !important;
    margin-top: 0.5rem !important;
  }

  .customer-file-item {
    padding: 0.5rem 0.4rem !important;
    gap: 0.5rem !important;
    font-size: 0.8rem !important;
  }

  .customer-file-item .file-icon {
    font-size: 1rem !important;
  }

  .customer-file-item .file-name {
    font-size: 0.8rem !important;
  }

  /* 컬럼 설정 모달 최적화 */
  #columnCheckboxes {
    margin: 0 !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
  }

  #columnCheckboxes .row {
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  #columnCheckboxes [class*="col-"] {
    padding: 0.25rem 0.4rem !important;
    margin-bottom: 0.25rem !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  #columnCheckboxes .form-check {
    padding: 0.25rem 0 !important;
    margin: 0 !important;
  }

  #columnCheckboxes .form-check-label {
    font-size: 0.8rem !important;
    padding-left: 0.5rem !important;
  }

  #columnCheckboxes .form-check-input {
    width: 1rem !important;
    height: 1rem !important;
    margin-top: 0.125rem !important;
  }

  /* 모달 내부 스피너 최적화 */
  .modal-body .spinner-border {
    width: 2rem !important;
    height: 2rem !important;
  }

  .modal-body .text-muted {
    font-size: 0.85rem !important;
    margin-top: 0.5rem !important;
  }

  /* 거래처 수정 모달 iframe 최적화 */
  #customerEditModal .modal-body {
    height: calc(100vh - 80px) !important;
    padding: 0 !important;
  }

  #customerEditModal .modal-header {
    min-height: 50px !important;
  }

  #customerEditModal iframe {
    width: 100% !important;
    height: 100% !important;
    border: none !important;
  }
}

/* Tabulator 통합 폰트 크기 설정 */
.tabulator {
    font-size: 1.03em;
    max-width: 100%;
    overflow-x: hidden;
}

/* Tabulator 테이블 컨테이너 가로 스크롤 제거 */
#tabulator-table {
    max-width: 100%;
    overflow-x: hidden !important;
}

#tabulator-table .tabulator-tableHolder {
    overflow-x: hidden !important;
}

/* 모바일 카드 컨테이너 - PC에서는 숨김 */
@media (min-width: 769px) {
    #mobile-tabulator-cards {
        display: none !important;
    }
}

/* 테이블 뷰 가로 스크롤 설정 */
.table-view {
    width: 100%;
    overflow-x: hidden;
}

/* 데스크톱에서만 가로 스크롤 활성화 */
@media (min-width: 769px) {
    .table-view, #tabulator-table {
        overflow-x: hidden !important;
    }
}

/* 모달 z-index 설정 (검색창 위에 표시되도록) */
.modal {
    z-index: 1050 !important;
}

.modal-backdrop {
    z-index: 1040 !important;
}

.modal.show {
    z-index: 1050 !important;
}

#customerDetailModal,
#customerEditModal,
#columnSettingsModal {
    z-index: 1050 !important;
}

/* 거래처 수정 모달 body 스크롤 제거 (iframe 내부에서만 스크롤) */
#customerEditModal .modal-body {
    overflow: hidden !important;
    padding: 0 !important;
}

/* 거래처 조회 전용 스타일 */
.page-header {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.page-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}

.page-header p {
    margin: 0.25rem 0 0;
    opacity: 0.85;
    font-size: 0.95rem;
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 10px;
}

.action-bar .btn-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-bar .btn {
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.action-bar .btn-primary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
}

.action-bar .btn-outline-secondary {
    border: 1px solid #d1d5db;
    color: #374151;
}

.filter-section {
    background: #f8f9fa;
    padding: 1.25rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    position: relative;
    z-index: 1;
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
    justify-content: center;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 220px;
}

.filter-group label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #6b7280;
}

.search-input-wrapper {
    position: relative;
    z-index: 1;
}

.search-input-wrapper input {
    padding-left: 2.4rem;
    padding-right: 2.5rem;
    border-radius: 999px;
    border: 2px solid #e5e7eb;
    width: 300px;
    max-width: 100%;
    position: relative;
    z-index: 1;
}

.search-input-wrapper > i.bi-search {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1rem;
    pointer-events: none;
    z-index: 2;
}

.search-input-wrapper .clear-search {
    position: absolute;
    right: 10px !important;
    left: auto !important;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
    cursor: pointer;
    display: none !important;
    padding: 4px 6px;
    border-radius: 50%;
    transition: all 0.2s;
    z-index: 3;
    line-height: 1;
    pointer-events: auto !important;
}

.search-input-wrapper .clear-search:hover {
    background: #e5e7eb;
    color: #475569;
}

.search-input-wrapper .clear-search.show {
    display: block !important;
}

.filter-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-buttons .btn {
    border-radius: 999px;
    padding: 0.4rem 0.9rem;
    font-size: 0.85rem;
    border: 1px solid #e5e7eb;
    background: white;
    color: #475569;
    transition: all 0.2s;
}

.filter-buttons .btn:hover {
    background: #e0f2fe;
    color: #0f172a;
    border-color: #93c5fd;
    transform: translateY(-1px);
}

.filter-buttons .btn.active {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
}

.detail-section {
    margin-bottom: 24px;
}

.detail-section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
}

.detail-item {
    padding: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-size: 0.78rem;
    color: #6b7280;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.detail-value {
    font-size: 1rem;
    color: #0f172a;
    font-weight: 600;
}

.detail-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.detail-table th,
.detail-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.9rem;
}

.detail-table th {
    background: #f1f5f9;
    font-weight: 600;
    color: #475569;
}

.detail-empty {
    padding: 1rem;
    text-align: center;
    border: 1px dashed #cbd5f5;
    border-radius: 10px;
    color: #94a3b8;
    background: #f8fafc;
}

/* 첨부 파일 목록 스타일 */
.customer-files-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 10px;
}

.customer-file-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.customer-file-item:hover {
    background: #e9ecef;
    border-color: #6c757d;
    transform: translateX(4px);
    box-shadow: 0 2px 4px rgba(108, 117, 125, 0.1);
}

.customer-file-item .file-icon {
    font-size: 1.2rem;
    flex-shrink: 0;
}

.customer-file-item .file-name {
    flex: 1;
    color: #1e293b;
    font-size: 0.9rem;
    word-break: break-all;
}

.customer-file-item:hover .file-name {
    color: #495057;
    font-weight: 500;
}

/* 테이블 스타일 개선 */
.tabulator .tabulator-header {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    font-weight: 600;
}

.tabulator .tabulator-header .tabulator-col {
    background: transparent;
    border-right: 1px solid rgba(255,255,255,0.2);
}

.tabulator .tabulator-header .tabulator-col:hover {
    background: rgba(255,255,255,0.1);
}

.tabulator .tabulator-row {
    transition: all 0.2s ease;
    cursor: pointer;
    pointer-events: auto !important;
    user-select: none;
}

.tabulator .tabulator-row:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.tabulator .tabulator-row:active {
    background-color: #e9ecef !important;
    transform: translateY(0);
}

.tabulator .tabulator-row.tabulator-selectable:hover {
    background: #e9ecef;
}

.tabulator .tabulator-row-even {
    background-color: #fafafa;
}

.tabulator .tabulator-row-even:hover {
    background-color: #f5f9ff !important;
}

/* 상태 배지 스타일 */
.status-new {
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}

/* 페이지네이션 스타일 */
.pagination-info {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-controls select {
    border-radius: 5px;
    border: 1px solid #ced4da;
    padding: 0.25rem 0.5rem;
}

.pagination-controls .btn {
    border-radius: 5px;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* 다크 모드 지원 */
[data-theme="dark"] body {
    background-color: #1a1a1a;
    color: #ffffff;
}

[data-theme="dark"] .search-section {
    background: #2d3748;
    color: white;
}

[data-theme="dark"] .filter-buttons .btn {
    background: #4a5568;
    color: #e2e8f0;
    border-color: #4a5568;
}

[data-theme="dark"] .filter-buttons .btn:hover {
    background: #6c757d;
    color: white;
}

[data-theme="dark"] .filter-buttons .btn.active {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

[data-theme="dark"] .tabulator .tabulator-header {
    background: linear-gradient(135deg, #495057 0%, #343a40 100%);
}

[data-theme="dark"] .tabulator .tabulator-row:hover {
    background: #4a5568;
}

[data-theme="dark"] .pagination-info {
    background: #2d3748;
    color: white;
}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div>
            <h1><i class="bi bi-building"></i> 거래처 관리</h1>
            <p>거래처 정보를 조회하고 관리할 수 있습니다.</p>
        </div>
    </div>

    <div class="action-bar">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary" onclick="openColumnSettings()">
                <i class="bi bi-gear"></i> 컬럼 설정
                    </button>
        </div>
        <div class="btn-group">
            <!-- <button type="button" class="btn btn-success" onclick="importExcel()">
                <i class="bi bi-file-earmark-excel"></i> Excel 임포트
            </button> -->
            <button type="button" class="btn btn-primary" onclick="addCustomer()">
                <i class="bi bi-plus-circle"></i> 거래처 등록
                    </button>
        </div>
                </div>
                
    <div class="filter-section">
        <div class="filter-form">
            <div class="filter-group">
                <label>검색어</label>
                <div class="search-input-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" id="searchInput" placeholder="거래처명, 대표자, 연락처 등을 입력하세요">
                    <i class="bi bi-x-circle clear-search" id="clearSearchBtn"></i>
                </div>
            </div>
            <div class="filter-group">
                <label>빠른 필터</label>
                <div class="filter-buttons">
                    <button type="button" class="btn active" data-filter="all">
                        <i class="bi bi-people"></i> 전체 그룹
                </button>
                    <button type="button" class="btn" data-filter="sales">
                        <i class="bi bi-currency-dollar"></i> 매출
                    </button>
                    <button type="button" class="btn" data-filter="purchase">
                        <i class="bi bi-cart"></i> 매입
                </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 테이블 섹션 -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-view">
                <div id="tabulator-table"></div>
            </div>
            <!-- 모바일 카드 형식 -->
            <div id="mobile-tabulator-cards" style="display: none;"></div>
        </div>
    </div>

    <!-- 페이지네이션 정보 -->
    <div class="pagination-info">
        <div class="pagination-controls">
            <label for="pageSize">페이지당 항목:</label>
            <select id="pageSize" onchange="changePageSize()">
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <div class="pagination-controls">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToFirstPage()">
                <i class="bi bi-chevron-double-left"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToPrevPage()">
                <i class="bi bi-chevron-left"></i>
            </button>
            <span id="pageInfo" class="mx-2">1 / 1</span>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToNextPage()">
                <i class="bi bi-chevron-right"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToLastPage()">
                <i class="bi bi-chevron-double-right"></i>
            </button>
        </div>

        <div class="pagination-controls">
            <span id="totalCount">총 0건</span>
        </div>
    </div>

    <!-- 거래처 상세 모달 -->
    <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white;">
                    <h5 class="modal-title" id="customerDetailModalLabel"><i class="bi bi-card-list me-2"></i>거래처 상세 정보</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="customerDetailContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-secondary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">거래처 정보를 불러오는 중입니다...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="customerDeleteBtn">
                        <i class="bi bi-trash"></i> 삭제
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> 닫기
                    </button>
                    <button type="button" class="btn btn-primary" id="customerEditBtn">
                        <i class="bi bi-pencil-square"></i> 수정하기
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 거래처 수정 모달 -->
    <div class="modal fade" id="customerEditModal" tabindex="-1" aria-labelledby="customerEditModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
                    <h5 class="modal-title" id="customerEditModalLabel"><i class="bi bi-pencil-square me-2"></i><span id="customerEditModalTitle">거래처 수정</span></h5>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <button type="button" class="btn btn-sm btn-success" onclick="iframeSaveCustomer()" id="iframeSaveBtn" style="display: none;">
                            <i class="bi bi-check-lg"></i> 저장
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="iframeDeleteCustomer()" id="iframeDeleteBtn" style="display: none;">
                            <i class="bi bi-trash"></i> 삭제
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> 취소
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-0" style="height: calc(100vh - 56px); overflow: hidden;">
                    <iframe id="customerEditIframe" src="" style="width: 100%; height: 100%; border: none; display: block;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- 컬럼 설정 모달 -->
    <div class="modal fade" id="columnSettingsModal" tabindex="-1" aria-labelledby="columnSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white;">
                    <h5 class="modal-title" id="columnSettingsModalLabel">
                        <i class="bi bi-gear"></i> 컬럼 표시 설정
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">표시할 컬럼을 선택하세요. 설정은 자동으로 저장됩니다.</p>
                    <div id="columnCheckboxes" class="row">
                        <!-- JavaScript로 동적 생성 -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="resetColumnSettings()">
                        <i class="bi bi-arrow-clockwise"></i> 초기화
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check-lg"></i> 확인
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php
    // 데이터베이스 연결
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 거래처 테이블이 없으면 생성
    $createTableSQL = "
CREATE TABLE IF NOT EXISTS mirae8440.customer (
    num INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    classification ENUM('사업자', '개인') DEFAULT '사업자' COMMENT '구분',
    trade_name VARCHAR(100) DEFAULT NULL COMMENT '상호(법인명)',
    company_name VARCHAR(100) NOT NULL COMMENT '거래처명',
    registration_number VARCHAR(20) DEFAULT NULL COMMENT '등록번호',
    representative_name VARCHAR(50) DEFAULT NULL COMMENT '대표자명',
    phone_number VARCHAR(20) DEFAULT NULL COMMENT '전화번호',
    mobile_number VARCHAR(20) DEFAULT NULL COMMENT '휴대폰번호',
    fax_number VARCHAR(20) DEFAULT NULL COMMENT 'FAX번호',
    business_type VARCHAR(50) DEFAULT NULL COMMENT '업태',
    business_category VARCHAR(50) DEFAULT NULL COMMENT '종목',
    remarks TEXT DEFAULT NULL COMMENT '적요',
    address TEXT DEFAULT NULL COMMENT '주소',
    business_registration_number VARCHAR(20) DEFAULT NULL COMMENT '사업자번호',
    registration_date DATE DEFAULT NULL COMMENT '거래처등록일',
    last_modified_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '최종수정일',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성일',
    is_deleted CHAR(1) DEFAULT 'N' COMMENT '삭제여부',
    -- 그룹 정보
    is_sales_customer CHAR(1) DEFAULT 'N' COMMENT '매출거래처',
    is_purchase_customer CHAR(1) DEFAULT 'N' COMMENT '매입거래처',
    is_other_customer CHAR(1) DEFAULT 'N' COMMENT '기타거래처',
    -- 계좌 정보
    bank_name VARCHAR(50) DEFAULT NULL COMMENT '은행명',
    account_number VARCHAR(50) DEFAULT NULL COMMENT '계좌번호',
    account_holder VARCHAR(50) DEFAULT NULL COMMENT '예금주',
    -- 내 계좌 정보
    my_account_id INT DEFAULT NULL COMMENT '내 계좌 ID',
    -- 첨부파일
    attached_files TEXT DEFAULT NULL COMMENT '첨부파일 정보 (JSON)',
    INDEX idx_company_name (company_name),
    INDEX idx_registration_number (registration_number),
    INDEX idx_representative_name (representative_name),
    INDEX idx_classification (classification)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='거래처 정보 테이블'
";

// 담당자 정보 테이블 생성
$createContactTableSQL = "
CREATE TABLE IF NOT EXISTS mirae8440.customer_contact (
    num INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL COMMENT '거래처 ID',
    contact_name VARCHAR(50) NOT NULL COMMENT '담당자명',
    contact_phone VARCHAR(20) DEFAULT NULL COMMENT '연락처',
    contact_email VARCHAR(100) DEFAULT NULL COMMENT '이메일',
    contact_remarks TEXT DEFAULT NULL COMMENT '비고',
    is_invoice_contact CHAR(1) DEFAULT 'N' COMMENT '계산서 담당자',
    position_department VARCHAR(100) DEFAULT NULL COMMENT '직급/부서',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성일',
    is_deleted CHAR(1) DEFAULT 'N' COMMENT '삭제여부',
    INDEX idx_customer_id (customer_id),
    FOREIGN KEY (customer_id) REFERENCES mirae8440.customer(num) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='거래처 담당자 정보 테이블'
";

try {
    $pdo->exec($createTableSQL);
    $pdo->exec($createContactTableSQL);
    
    // 기존 테이블에 새로운 컬럼들 추가 (테이블이 이미 존재하는 경우)
    $alterColumns = [
        "ALTER TABLE mirae8440.customer ADD COLUMN classification ENUM('사업자', '개인') DEFAULT '사업자' COMMENT '구분'",
        "ALTER TABLE mirae8440.customer ADD COLUMN trade_name VARCHAR(100) DEFAULT NULL COMMENT '상호(법인명)'",
        "ALTER TABLE mirae8440.customer ADD COLUMN is_sales_customer CHAR(1) DEFAULT 'N' COMMENT '매출거래처'",
        "ALTER TABLE mirae8440.customer ADD COLUMN is_purchase_customer CHAR(1) DEFAULT 'N' COMMENT '매입거래처'",
        "ALTER TABLE mirae8440.customer ADD COLUMN is_other_customer CHAR(1) DEFAULT 'N' COMMENT '기타거래처'",
        "ALTER TABLE mirae8440.customer ADD COLUMN bank_name VARCHAR(50) DEFAULT NULL COMMENT '은행명'",
        "ALTER TABLE mirae8440.customer ADD COLUMN account_number VARCHAR(50) DEFAULT NULL COMMENT '계좌번호'",
        "ALTER TABLE mirae8440.customer ADD COLUMN account_holder VARCHAR(50) DEFAULT NULL COMMENT '예금주'",
        "ALTER TABLE mirae8440.customer ADD COLUMN my_account_id INT DEFAULT NULL COMMENT '내 계좌 ID'",
        "ALTER TABLE mirae8440.customer ADD COLUMN attached_files TEXT DEFAULT NULL COMMENT '첨부파일 정보 (JSON)'"
    ];
    
    for ($i = 0; $i < count($alterColumns); $i++) {
        $sql = $alterColumns[$i];
        try {
            $pdo->exec($sql);
        } catch (PDOException $ex) {
            // 컬럼이 이미 존재하는 경우 무시
            if (strpos($ex->getMessage(), 'Duplicate column name') === false &&
                strpos($ex->getMessage(), 'already exists') === false) {
                error_log("테이블 구조 업데이트 오류: " . $ex->getMessage());
            }
        }
    }

    // 인덱스 추가
    try {
        $pdo->exec("ALTER TABLE mirae8440.customer ADD INDEX idx_classification (classification)");
    } catch (PDOException $ex) {
        // 인덱스가 이미 존재하는 경우 무시
        if (strpos($ex->getMessage(), 'Duplicate key name') === false) {
            error_log("인덱스 추가 오류: " . $ex->getMessage());
        }
    }
    
    // 샘플 데이터 삽입 (테이블이 비어있을 때만)
    $countSQL = "SELECT COUNT(*) as count FROM mirae8440.customer";
    $countResult = $pdo->query($countSQL);
    $countRow = $countResult->fetch(PDO::FETCH_ASSOC);
    $count = $countRow ? $countRow['count'] : 0;

    if ($count == 0) {
        $sampleData = array(
            array('사업자', '(주)한산엘테크', '(주)한산엘테크', '136-81-19428', '이세원', '', '031-981-6108', '', '제조', '금속표면처리', '엘리베이터', '', '경기도 김포시 하성면 원산리 603-3', '', '2018-05-03', 'Y', 'Y', 'N', '기업은행', '123-456789-01-012', '이세원'),
            array('사업자', '(주)일해이엔지', '(주)일해이엔지', '121-81-40915', '권영창', '031-3667-5058', '', '031-366-7509', '제조', '부동산', '엘리베이터', '', '경기도 화성시 마도면 마도로574-116', '', '2018-05-03', 'Y', 'N', 'Y', '신한은행', '110-456-789012', '권영창'),
            array('사업자', '태광기전', '태광기전', '113-81-66495', '최승범', '02-2101-3060', '', '02-2101-3063', '도소매', '전기용품', '전기부품', '', '서울시 구로구 구로동 중앙유통단지', '', '2019-12-01', 'N', 'Y', 'N', '국민은행', '123456-78-901234', '최승범'),
            array('사업자', '대한전기', '대한전기', '123-45-67890', '김대한', '02-1234-5678', '010-1234-5678', '02-1234-5679', '제조', '전기기기', '전기부품', '우수거래처', '서울시 강남구 테헤란로 123', '123-45-67890', '2020-01-15', 'Y', 'Y', 'N', '우리은행', '1002-123-456789', '김대한'),
            array('사업자', '미래건설', '미래건설', '234-56-78901', '박미래', '031-234-5678', '010-2345-6789', '031-234-5679', '건설', '건축', '건설자재', '장기거래', '경기도 성남시 분당구 판교로 456', '234-56-78901', '2020-03-20', 'N', 'N', 'Y', '하나은행', '123-456789-12345', '박미래')
        );

        $insertSQL = "INSERT INTO mirae8440.customer (classification, trade_name, company_name, registration_number, representative_name, phone_number, mobile_number, fax_number, business_type, business_category, remarks, address, business_registration_number, registration_date, is_sales_customer, is_purchase_customer, is_other_customer, bank_name, account_number, account_holder) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertSQL);

        for ($j = 0; $j < count($sampleData); $j++) {
            $data = $sampleData[$j];
            $stmt->execute($data);
        }
    }
} catch (PDOException $ex) {
    error_log("거래처 테이블 생성 오류: " . $ex->getMessage());
}

// 거래처 데이터 조회 (담당자 이메일 포함)
$sql = "SELECT c.*, 
        GROUP_CONCAT(DISTINCT cc.contact_email SEPARATOR ', ') as contact_emails
        FROM mirae8440.customer c
        LEFT JOIN mirae8440.customer_contact cc ON c.num = cc.customer_id AND (cc.is_deleted = 'N' OR cc.is_deleted IS NULL)
        WHERE (c.is_deleted = 'N' OR c.is_deleted IS NULL) 
        GROUP BY c.num
        ORDER BY c.num DESC";
$table_data = array();

try {
    $stmh = $pdo->query($sql);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $js_data = array(
            'num' => $row['num'],
            'classification' => $row['classification'],
            'trade_name' => $row['trade_name'],
            'company_name' => $row['company_name'],
            'registration_number' => $row['registration_number'],
            'representative_name' => $row['representative_name'],
            'phone_number' => $row['phone_number'],
            'mobile_number' => $row['mobile_number'],
            'fax_number' => $row['fax_number'],
            'business_type' => $row['business_type'],
            'business_category' => $row['business_category'],
            'remarks' => $row['remarks'],
            'address' => $row['address'],
            'business_registration_number' => $row['business_registration_number'],
            'registration_date' => $row['registration_date'],
            'last_modified_date' => $row['last_modified_date'],
            'is_sales_customer' => $row['is_sales_customer'],
            'is_purchase_customer' => $row['is_purchase_customer'],
            'is_other_customer' => $row['is_other_customer'],
            'bank_name' => $row['bank_name'],
            'account_number' => $row['account_number'],
            'account_holder' => $row['account_holder'],
            'contact_emails' => $row['contact_emails'] ? $row['contact_emails'] : ''
        );
        $table_data[] = $js_data;
    }
} catch (PDOException $ex) {
    error_log("거래처 데이터 조회 오류: " . $ex->getMessage());
}
?>

    <script>
        // PHP에서 전달된 테이블 데이터
        var phpTableData = <?php echo json_encode($table_data); ?>;

        var table; // Tabulator 인스턴스 전역 변수
        var currentCustomerId = null;
        var customerDetailModal = null;
        var customerEditModal = null;
        var rowClickLock = false; // 중복 클릭 방지
        console.log('[DEBUG] customer/corp script initialized');

        // 거래처 상세 모달 열기 함수 (전역 함수로 정의)
        window.openCustomerDetail = function(customerNum) {
            console.log('[DEBUG] openCustomerDetail invoked', customerNum);
            if (!customerNum) {
                alert('거래처 정보를 찾을 수 없습니다.');
                return;
            }

            currentCustomerId = customerNum;

            var modalElement = document.getElementById('customerDetailModal');
            var contentElement = document.getElementById('customerDetailContent');
            if (!modalElement || !contentElement) {
                console.error('모달 요소를 찾을 수 없습니다.');
                return;
            }

            // 부트스트랩 모달 인스턴스 생성 또는 가져오기
            if (!customerDetailModal) {
                if (typeof bootstrap === 'undefined') {
                    console.error('Bootstrap이 로드되지 않았습니다.');
                    alert('페이지를 새로고침해주세요.');
                    return;
                }
                customerDetailModal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: true
                });
            }

            // 로딩 표시
            contentElement.innerHTML = '<div class="text-center py-5">' +
                '<div class="spinner-border text-secondary" role="status" style="width: 3rem; height: 3rem;">' +
                '<span class="visually-hidden">Loading...</span>' +
                '</div>' +
                '<p class="mt-3 text-muted">거래처 정보를 불러오는 중입니다...</p>' +
                '</div>';

            // 모달 열기
            try {
                customerDetailModal.show();
            } catch (e) {
                console.error('모달 열기 오류:', e);
                // Bootstrap 모달이 없으면 직접 표시
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                document.body.classList.add('modal-open');
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modalBackdrop';
                document.body.appendChild(backdrop);
            }

            // 거래처 데이터 로드
            fetch('get_customer_detail.php?num=' + encodeURIComponent(customerNum))
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        if (typeof renderCustomerDetail === 'function') {
                            contentElement.innerHTML = renderCustomerDetail(data.customer, data.contacts || []);
                            // 첨부 파일 목록 로드
                            loadCustomerFiles(customerNum);
                        } else {
                            contentElement.innerHTML = '<div class="detail-empty">거래처 정보를 렌더링할 수 없습니다.</div>';
                        }
                    } else {
                        contentElement.innerHTML = '<div class="detail-empty">' + (data.message || '거래처 정보를 불러올 수 없습니다.') + '</div>';
                    }
                })
                .catch(function(error) {
                    console.error('거래처 정보 로드 오류:', error);
                    contentElement.innerHTML = '<div class="detail-empty">거래처 정보를 불러오는 중 오류가 발생했습니다.</div>';
                });
        };

        // 거래처 등록/수정 모달 열기 함수 (통합 함수)
        // customerNum이 null이거나 없으면 등록 모드, 있으면 수정 모드
        window.openCustomerEditModal = function(customerNum) {
            console.log('[DEBUG] openCustomerEditModal invoked', customerNum, 'currentCustomerId:', currentCustomerId);
            
            // 등록 모드인지 수정 모드인지 확인
            var isEditMode = customerNum && customerNum > 0;
            
            if (isEditMode) {
                currentCustomerId = customerNum;
            } else {
                // 등록 모드
                currentCustomerId = null;
            }

            // 상세 모달 닫기
            if (customerDetailModal) {
                try {
                    customerDetailModal.hide();
                } catch (e) {}
            }

            var modalElement = document.getElementById('customerEditModal');
            var iframe = document.getElementById('customerEditIframe');
            var modalTitle = document.getElementById('customerEditModalTitle');
            var saveBtn = document.getElementById('iframeSaveBtn');
            var deleteBtn = document.getElementById('iframeDeleteBtn');

            if (!modalElement || !iframe) {
                console.error('거래처 편집 모달 요소를 찾을 수 없습니다.');
                return;
            }

            // 모달 제목 변경
            if (modalTitle) {
                modalTitle.textContent = isEditMode ? '거래처 수정' : '거래처 등록';
            }

            // 버튼 표시/숨김 처리
            if (deleteBtn) {
                // 삭제 버튼: 수정 모드일 때만 표시
                deleteBtn.style.display = isEditMode ? 'inline-flex' : 'none';
            }
            if (saveBtn) {
                // 저장 버튼: 항상 표시
                saveBtn.style.display = 'inline-flex';
            }

            // 부트스트랩 모달 인스턴스 생성 또는 가져오기
            if (!customerEditModal) {
                if (typeof bootstrap === 'undefined') {
                    console.error('Bootstrap이 로드되지 않았습니다.');
                    alert('페이지를 새로고침해주세요.');
                    return;
                }
                customerEditModal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: true
                });
            }

            // iframe에 해당 페이지 로드
            if (isEditMode) {
                // 수정 모드: edit.php 로드
                iframe.src = 'edit.php?num=' + encodeURIComponent(currentCustomerId) + '&iframe=1';
            } else {
                // 등록 모드: add.php 로드
                iframe.src = 'add.php?iframe=1';
            }

            // 모달 열기
            try {
                customerEditModal.show();
            } catch (e) {
                console.error('모달 열기 오류:', e);
                // Bootstrap 모달이 없으면 직접 표시
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                document.body.classList.add('modal-open');
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modalBackdrop';
                document.body.appendChild(backdrop);
            }
        };

        $(document).ready(function() {
            console.log('[DEBUG] Document ready fired');
            // PHP에서 전달받은 데이터 사용
            var tableData = phpTableData || [];
    
    // Tabulator 컬럼 정의
    var columns = [
        {
            title: "번호",
            field: "num",
            width: 60,
            hozAlign: "center",
            visible: false
        },
        {
            title: "선택",
            field: "select",
            minWidth: 60,
            width: 60,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                return '<input type="checkbox" class="form-check-input">';
            },
            cellClick: function(e, cell) {
                // 체크박스 셀 클릭 시 이벤트 전파 중단 (모달 방지)
                // 체크박스 기본 동작은 유지하기 위해 return false 제거
                e.stopPropagation();
            }
        },
        {
            title: "구분",
            field: "classification",
            minWidth: 70,
            width: 80,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                var badgeClass = value === '사업자' ? 'bg-secondary' : 'bg-light text-dark border';
                return '<span class="badge ' + badgeClass + '">' + value + '</span>';
            }
        },
        {
            title: "거래처명",
            field: "company_name",
            minWidth: 150,
            widthGrow: 2,
            hozAlign: "left",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                return '<i class="bi bi-building me-2"></i>' + value;
            }
        },
        {
            title: "상호(법인명)",
            field: "trade_name",
            minWidth: 120,
            widthGrow: 1.5,
            hozAlign: "left",
            visible: false
        },
        {
            title: "등록번호",
            field: "registration_number",
            minWidth: 100,
            width: 120,
            hozAlign: "center"
        },
        {
            title: "대표자명",
            field: "representative_name",
            minWidth: 80,
            width: 100,
            hozAlign: "center"
        },
        {
            title: "전화번호",
            field: "phone_number",
            minWidth: 100,
            width: 120,
            hozAlign: "center"
        },
        {
            title: "휴대폰번호",
            field: "mobile_number",
            minWidth: 100,
            width: 120,
            hozAlign: "center"
        },
        {
            title: "FAX번호",
            field: "fax_number",
            minWidth: 100,
            width: 120,
            hozAlign: "center"
        },
        {
            title: "거래처등록일",
            field: "registration_date",
            minWidth: 100,
            width: 120,
            hozAlign: "center",
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 날짜 문자열을 비교 가능한 형식으로 변환
                var dateA = a ? (a.length >= 10 ? a.substring(0, 10) : a) : '';
                var dateB = b ? (b.length >= 10 ? b.substring(0, 10) : b) : '';
                
                if (!dateA && !dateB) return 0;
                if (!dateA) return 1;
                if (!dateB) return -1;
                
                // YYYY-MM-DD 형식이므로 문자열 비교로 정렬 가능
                return dateA.localeCompare(dateB);
            },
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value) {
                    // YYYY-MM-DD 형식으로 표시
                    if (value.length >= 10) {
                        return value.substring(0, 10); // YYYY-MM-DD
                    }
                    return value;
                }
                return '';
            }
        },
        {
            title: "최종수정일",
            field: "last_modified_date",
            minWidth: 100,
            width: 120,
            hozAlign: "center",
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 날짜 문자열을 비교 가능한 형식으로 변환
                var dateA = a ? (a.length >= 10 ? a.substring(0, 10) : a) : '';
                var dateB = b ? (b.length >= 10 ? b.substring(0, 10) : b) : '';
                
                if (!dateA && !dateB) return 0;
                if (!dateA) return 1;
                if (!dateB) return -1;
                
                // YYYY-MM-DD 형식이므로 문자열 비교로 정렬 가능
                return dateA.localeCompare(dateB);
            },
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value) {
                    // YYYY-MM-DD 형식으로 표시
                    if (value.length >= 10) {
                        return value.substring(0, 10); // YYYY-MM-DD
                    }
                    // Date 객체인 경우
                    if (value instanceof Date) {
                        var year = value.getFullYear();
                        var month = String(value.getMonth() + 1).padStart(2, '0');
                        var day = String(value.getDate()).padStart(2, '0');
                        return year + '-' + month + '-' + day;
                    }
                    return value;
                }
                return '';
            }
        },
        {
            title: "업태",
            field: "business_type",
            minWidth: 70,
            width: 80,
            hozAlign: "center"
        },
        {
            title: "종목",
            field: "business_category",
            minWidth: 80,
            width: 100,
            hozAlign: "center"
        },
        {
            title: "적요",
            field: "remarks",
            minWidth: 100,
            widthGrow: 1,
            hozAlign: "left"
        },
        {
            title: "주소",
            field: "address",
            minWidth: 150,
            widthGrow: 2.5,
            hozAlign: "left",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value && value.length > 30) {
                    return value.substring(0, 30) + '...';
                }
                return value || '';
            }
        },
        {
            title: "사업자번호",
            field: "business_registration_number",
            minWidth: 100,
            width: 120,
            hozAlign: "center"
        },
        {
            title: "그룹",
            field: "groups",
            minWidth: 100,
            width: 120,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var rowData = cell.getRow().getData();
                var groups = [];
                if (rowData.is_sales_customer === 'Y') groups.push('<span class="badge bg-secondary">매출</span>');
                if (rowData.is_purchase_customer === 'Y') groups.push('<span class="badge bg-dark">매입</span>');
                if (rowData.is_other_customer === 'Y') groups.push('<span class="badge bg-light text-dark border">기타</span>');
                return groups.join(' ');
            }
        },
        {
            title: "계좌정보",
            field: "account_info",
            minWidth: 120,
            width: 150,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var rowData = cell.getRow().getData();
                if (rowData.bank_name && rowData.account_number) {
                    return rowData.bank_name + '<br>' + rowData.account_number;
                }
                return '';
            }
        },
        {
            title: "담당자 이메일",
            field: "contact_emails",
            minWidth: 150,
            widthGrow: 1.5,
            hozAlign: "left",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value) {
                    // 여러 이메일이 콤마로 구분되어 있을 경우 처리
                    var emails = value.split(', ');
                    if (emails.length > 1) {
                        return '<i class="bi bi-envelope me-2"></i>' + emails[0] + ' <span class="text-muted">(+' + (emails.length - 1) + ')</span>';
                    }
                    return '<i class="bi bi-envelope me-2"></i>' + value;
                }
                return '<span class="text-muted">-</span>';
            }
        }
    ];
    
    // Tabulator 초기화
    table = new Tabulator("#tabulator-table", {
        data: tableData,
        columns: columns,
        layout: "fitDataFill",
        responsiveLayout: "hide",
        tooltips: true,
        addRowPos: "top",
        history: true,
        pagination: "local",
        paginationSize: 20,
        paginationSizeSelector: [20, 50, 100, 200],
        movableColumns: true,
        resizableColumns: true,
        autoResize: true,
        selectable: false, // 행 선택 기능 비활성화 (클릭 이벤트와 충돌 방지)
        initialSort: [
            {column: "num", dir: "desc"}
        ],
        locale: "ko-kr",
        langs: {
            "ko-kr": {
                "pagination": {
                    "page_size": "페이지당 항목",
                    "page_title": "페이지 표시",
                    "first": "첫 페이지",
                    "first_title": "첫 페이지",
                    "last": "마지막 페이지",
                    "last_title": "마지막 페이지",
                    "prev": "이전 페이지",
                    "prev_title": "이전 페이지",
                    "next": "다음 페이지",
                    "next_title": "다음 페이지",
                    "all": "전체",
                    "counter": {
                        "showing": "표시 중",
                        "of": "/",
                        "rows": "행",
                        "pages": "페이지"
                    }
                }
            }
        }
        });
        console.log('[DEBUG] Tabulator created. Data count:', typeof table.getDataCount === 'function' ? table.getDataCount() : 'unknown');

            // 테이블 생성 후 rowClick 이벤트 수동 바인딩 (더 안정적)
            console.log('[DEBUG] Binding rowClick event with .on() method');
            table.on("rowClick", function(e, row) {
                console.log('[DEBUG] .on() rowClick fired', e, row);
                
                // 체크박스 클릭인지 확인 (체크박스 영역 클릭 시 모달 열지 않음)
                if (e.target && (e.target.classList.contains('form-check-input') || 
                    e.target.type === 'checkbox' || 
                    e.target.tagName === 'INPUT')) {
                    console.log('[DEBUG] 체크박스 클릭 감지 - 모달 열지 않음 (.on)');
                    return;
                }
                
                // 중복 클릭 방지
                if (rowClickLock) {
                    console.log('[DEBUG] rowClick locked, ignoring');
                    return;
                }
                rowClickLock = true;
                setTimeout(function() {
                    rowClickLock = false;
                }, 100);
                
                var rowData = row.getData();
                console.log('[DEBUG] .on() rowClick data', rowData);
                
                if (rowData && rowData.num) {
                    console.log('[DEBUG] Calling openCustomerDetail with num:', rowData.num);
                    if (typeof window.openCustomerDetail === 'function') {
                        window.openCustomerDetail(rowData.num);
                    } else {
                        console.error('[DEBUG] openCustomerDetail 함수를 찾을 수 없습니다.');
                    }
                } else {
                    console.warn('[DEBUG] .on() rowClick missing num value', rowData);
                }
            });

            // 테이블 생성 후 rowDblClick 이벤트 수동 바인딩
            table.on("rowDblClick", function(e, row) {
                console.log('[DEBUG] .on() rowDblClick fired');
                
                // 체크박스 클릭인지 확인
                if (e.target && (e.target.classList.contains('form-check-input') || 
                    e.target.type === 'checkbox' || 
                    e.target.tagName === 'INPUT')) {
                    return;
                }
                
                var rowData = row.getData();
                console.log('[DEBUG] .on() rowDblClick data', rowData);
                
                if (rowData && rowData.num) {
                    if (typeof window.openCustomerEditModal === 'function') {
                        window.openCustomerEditModal(rowData.num);
                    } else {
                        console.error('[DEBUG] openCustomerEditModal 함수를 찾을 수 없습니다.');
            }
        }
        });

            // 테이블 초기화 완료 후 페이지네이션 정보 업데이트 및 컬럼 설정 로드
            setTimeout(function() {
                if (table && typeof table.getDataCount === 'function') {
                    updatePaginationInfo();
                }

                // 저장된 컬럼 설정 불러오기
                loadColumnSettings();

                // 행 클릭 가능함을 사용자에게 알리는 툴팁 추가
                $('#tabulator-table .tabulator-row').attr('title', '클릭하여 거래처 상세 정보를 확인합니다');
                
                // 직접 DOM 이벤트 리스너도 추가 (추가 보험)
                console.log('[DEBUG] Adding direct DOM event listeners');
                var rows = document.querySelectorAll('#tabulator-table .tabulator-row');
                console.log('[DEBUG] Found', rows.length, 'rows');
                rows.forEach(function(rowElement, index) {
                    rowElement.addEventListener('click', function(e) {
                        console.log('[DEBUG] Direct DOM click on row', index, e);
                        // Tabulator 이벤트가 작동하지 않을 때를 대비한 백업
                        if (e.target && (e.target.classList.contains('form-check-input') || e.target.type === 'checkbox')) {
                            return;
                        }
                        // Tabulator의 rowClick이 작동하면 중복 실행을 방지하기 위해 주석 처리
                        // var row = table.getRowFromPosition(index);
                        // if (row) {
                        //     var rowData = row.getData();
                        //     if (rowData && rowData.num && typeof window.openCustomerDetail === 'function') {
                        //         window.openCustomerDetail(rowData.num);
                        //     }
                        // }
                    }, true); // capture phase에서 실행
                });
            }, 500);

            // 테이블 데이터 변경 시 페이지네이션 정보 업데이트
            table.on("dataLoaded", function(data) {
                setTimeout(function() {
                    if (table && typeof table.getDataCount === 'function') {
                        updatePaginationInfo();
                    }
                    $('#tabulator-table .tabulator-row').attr('title', '클릭하여 거래처 상세 정보를 확인합니다');
                    // 모바일 카드 업데이트
                    renderMobileTabulatorCards();
                }, 100);
            });

            table.on("pageLoaded", function(pageno) {
                setTimeout(function() {
                    if (table && typeof table.getDataCount === 'function') {
                        updatePaginationInfo();
                    }
                    // 모바일 카드 업데이트
                    renderMobileTabulatorCards();
                }, 100);
            });

            var editBtn = document.getElementById('customerEditBtn');
            if (editBtn) {
                editBtn.addEventListener('click', function() {
                    if (typeof window.openCustomerEditModal === 'function') {
                        window.openCustomerEditModal(currentCustomerId);
                    } else {
                        console.error('openCustomerEditModal 함수를 찾을 수 없습니다.');
                    }
                });
            }

            // 삭제 버튼 클릭 이벤트
            var deleteBtn = document.getElementById('customerDeleteBtn');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    if (typeof window.deleteCustomerFromDetail === 'function') {
                        window.deleteCustomerFromDetail(currentCustomerId);
            } else {
                        console.error('deleteCustomerFromDetail 함수를 찾을 수 없습니다.');
                    }
                });
            }

            var editModalElement = document.getElementById('customerEditModal');
            if (editModalElement) {
                editModalElement.addEventListener('hidden.bs.modal', function() {
                    var iframe = document.getElementById('customerEditIframe');
                    if (iframe) {
                        iframe.src = '';
                    }
                    // 모달 제목과 버튼 초기화
                    var modalTitle = document.getElementById('customerEditModalTitle');
                    if (modalTitle) {
                        modalTitle.textContent = '거래처 수정';
                    }
                    var saveBtn = document.getElementById('iframeSaveBtn');
                    var deleteBtn = document.getElementById('iframeDeleteBtn');
                    if (saveBtn) {
                        saveBtn.style.display = 'none';
                    }
                    if (deleteBtn) {
                        deleteBtn.style.display = 'none';
                    }
                });
            }

            // 검색어 필터링 함수
            var applySearchFilter = function() {
                if (!table) {
                    console.error('[DEBUG] 테이블이 초기화되지 않았습니다.');
                    return;
                }
                
                var searchValue = $('#searchInput').val().trim();
                var currentFilter = $('.filter-buttons .btn.active').data('filter');
                
                // 검색어와 그룹 필터를 모두 적용하는 커스텀 필터
                table.setFilter(function(data) {
                    // 그룹 필터 검사
                    var groupMatch = true;
                    if (currentFilter === 'sales') {
                        groupMatch = data.is_sales_customer === 'Y';
                    } else if (currentFilter === 'purchase') {
                        groupMatch = data.is_purchase_customer === 'Y';
                    }
                    // 'all'인 경우는 모든 그룹 통과
                    
                    if (!groupMatch) {
                        return false;
                    }
                    
                    // 검색어 필터 검사
                    if (!searchValue || searchValue === '') {
                        return true; // 검색어가 없으면 그룹 필터만 적용
                    }
                    
                    var searchLower = searchValue.toLowerCase();
                    
                    // 각 필드에서 검색 (대소문자 구분 없음)
                    var companyName = (data.company_name || '').toString().toLowerCase();
                    var tradeName = (data.trade_name || '').toString().toLowerCase();
                    var representativeName = (data.representative_name || '').toString().toLowerCase();
                    var registrationNumber = (data.registration_number || '').toString().toLowerCase();
                    var phoneNumber = (data.phone_number || '').toString().toLowerCase();
                    var mobileNumber = (data.mobile_number || '').toString().toLowerCase();
                    var faxNumber = (data.fax_number || '').toString().toLowerCase();
                    var businessType = (data.business_type || '').toString().toLowerCase();
                    var businessCategory = (data.business_category || '').toString().toLowerCase();
                    var bankName = (data.bank_name || '').toString().toLowerCase();
                    var accountNumber = (data.account_number || '').toString().toLowerCase();
                    var address = (data.address || '').toString().toLowerCase();
                    var remarks = (data.remarks || '').toString().toLowerCase();
                    
                    // OR 조건: 하나라도 매칭되면 true
                    var searchMatch = companyName.indexOf(searchLower) !== -1 ||
                                     tradeName.indexOf(searchLower) !== -1 ||
                                     representativeName.indexOf(searchLower) !== -1 ||
                                     registrationNumber.indexOf(searchLower) !== -1 ||
                                     phoneNumber.indexOf(searchLower) !== -1 ||
                                     mobileNumber.indexOf(searchLower) !== -1 ||
                                     faxNumber.indexOf(searchLower) !== -1 ||
                                     businessType.indexOf(searchLower) !== -1 ||
                                     businessCategory.indexOf(searchLower) !== -1 ||
                                     bankName.indexOf(searchLower) !== -1 ||
                                     accountNumber.indexOf(searchLower) !== -1 ||
                                     address.indexOf(searchLower) !== -1 ||
                                     remarks.indexOf(searchLower) !== -1;
                    
                    return searchMatch;
                });
            };

            // X 버튼 표시/숨김 제어 함수
            var updateClearButton = function() {
                var searchValue = $('#searchInput').val().trim();
                if (searchValue) {
                    $('#clearSearchBtn').addClass('show').show();
                } else {
                    $('#clearSearchBtn').removeClass('show').hide();
                }
            };

            // 검색어 입력 이벤트
        $('#searchInput').on('input', function() {
                var searchValue = $(this).val().trim();
                console.log('[DEBUG] 검색어 입력:', searchValue);
                
                // X 버튼 표시/숨김 제어
                updateClearButton();
                
                applySearchFilter();
            });

            // 검색어 입력란 focus 이벤트 (값이 있을 때 X 버튼 표시)
            $('#searchInput').on('focus', function() {
                updateClearButton();
            });

            // 검색어 지우기 버튼 클릭 이벤트
            $('#clearSearchBtn').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $('#searchInput').val('').focus();
                updateClearButton();
                applySearchFilter();
            });

            // 초기 상태 확인 (페이지 로드 시)
            updateClearButton();

        // 필터 버튼 클릭 이벤트
        $('.filter-buttons .btn').on('click', function() {
            $('.filter-buttons .btn').removeClass('active');
            $(this).addClass('active');

            var filter = $(this).data('filter');
                console.log('[DEBUG] 그룹 필터 변경:', filter);
                
                applySearchFilter();
            });
        });

        // 페이지네이션 정보 업데이트 함수
        // 모바일 카드 렌더링 함수
        function renderMobileTabulatorCards() {
            if (!table) return;
            
            var isMobile = window.innerWidth <= 768;
            var cardsContainer = document.getElementById('mobile-tabulator-cards');
            var tabulatorContainer = document.getElementById('tabulator-table');
            
            if (!isMobile) {
                // PC 화면: Tabulator 표시, 카드 숨김
                if (tabulatorContainer) {
                    tabulatorContainer.style.display = '';
                }
                if (cardsContainer) {
                    cardsContainer.style.display = 'none';
                }
                return;
            }
            
            // 모바일 화면: Tabulator 숨김, 카드 표시
            if (tabulatorContainer) {
                tabulatorContainer.style.display = 'none';
            }
            if (!cardsContainer) return;
            
            cardsContainer.style.display = 'block';
            cardsContainer.innerHTML = '';
            
            try {
                var data = table.getData();
                var columns = table.getColumns();
                
                if (!data || data.length === 0) {
                    cardsContainer.innerHTML = '<div class="text-center py-4 text-muted">데이터가 없습니다.</div>';
                    return;
                }
                
                // 표시할 컬럼 필드 목록 (중요한 필드만)
                var displayFields = [
                    { field: 'classification', label: '구분' },
                    { field: 'company_name', label: '거래처명' },
                    { field: 'trade_name', label: '상호(법인명)' },
                    { field: 'representative_name', label: '대표자명' },
                    { field: 'phone_number', label: '전화번호' },
                    { field: 'mobile_number', label: '휴대폰번호' },
                    { field: 'business_type', label: '업태' },
                    { field: 'business_category', label: '종목' }
                ];
                
                data.forEach(function(rowData, index) {
                    var card = document.createElement('div');
                    card.className = 'mobile-tabulator-card';
                    card.style.cursor = 'pointer';
                    card.setAttribute('data-row-index', index);
                    
                    var cardHtml = '';
                    
                    displayFields.forEach(function(fieldInfo) {
                        var value = rowData[fieldInfo.field];
                        if (value === null || value === undefined || value === '') {
                            return; // 빈 값은 표시하지 않음
                        }
                        
                        var displayValue = value;
                        
                        // 특수 포맷팅
                        if (fieldInfo.field === 'classification') {
                            var badgeClass = value === '사업자' ? 'bg-secondary' : 'bg-light text-dark border';
                            displayValue = '<span class="badge ' + badgeClass + '">' + value + '</span>';
                        }
                        
                        cardHtml += '<div class="mobile-tabulator-card-item">';
                        cardHtml += '<div class="mobile-tabulator-card-label">' + fieldInfo.label + '</div>';
                        cardHtml += '<div class="mobile-tabulator-card-value">' + displayValue + '</div>';
                        cardHtml += '</div>';
                    });
                    
                    if (cardHtml === '') {
                        cardHtml = '<div class="text-muted">데이터 없음</div>';
                    }
                    
                    card.innerHTML = cardHtml;
                    
                    // 클릭 이벤트: 상세 정보 모달 열기
                    card.addEventListener('click', function(e) {
                        if (e.target.tagName === 'INPUT' || e.target.type === 'checkbox') {
                            return;
                        }
                        if (rowData && rowData.num && typeof window.openCustomerDetail === 'function') {
                            window.openCustomerDetail(rowData.num);
                        }
                    });
                    
                    cardsContainer.appendChild(card);
                });
            } catch (error) {
                console.error('모바일 카드 렌더링 오류:', error);
                cardsContainer.innerHTML = '<div class="text-center py-4 text-danger">데이터를 불러오는 중 오류가 발생했습니다.</div>';
            }
        }
        
        // 화면 크기 변경 시 카드/테이블 전환
        function updateTabulatorDisplay() {
            renderMobileTabulatorCards();
        }
        
        // 초기 로드 및 리사이즈 이벤트
        $(document).ready(function() {
            updateTabulatorDisplay();
            $(window).on('resize', function() {
                updateTabulatorDisplay();
            });
        });

        function updatePaginationInfo() {
            if (table) {
                try {
                    // Tabulator 버전에 따라 다른 함수명 사용
                    var pageInfo = null;
                    var totalRows = 0;

                    // 다양한 방법으로 페이지 정보 가져오기 시도
                    if (typeof table.getPageInfo === 'function') {
                        pageInfo = table.getPageInfo();
                    } else if (typeof table.getPage === 'function') {
                        var currentPage = table.getPage();
                        var pageSize = table.getPageSize();
                        var totalData = table.getDataCount();
                        var totalPages = Math.ceil(totalData / pageSize);
                        pageInfo = {
                            page: currentPage,
                            pages: totalPages
                        };
                    }

                    // 총 행 수 가져오기
                    if (typeof table.getDataCount === 'function') {
                        totalRows = table.getDataCount();
                    } else if (typeof table.getData === 'function') {
                        totalRows = table.getData().length;
                    }

                    // 페이지 정보 표시
                    if (pageInfo && pageInfo.page && pageInfo.pages) {
                        $('#pageInfo').text(pageInfo.page + ' / ' + pageInfo.pages);
                    }

                    // 총 개수 표시
                    if (totalRows !== undefined && totalRows >= 0) {
                        $('#totalCount').text('총 ' + totalRows + '건');
                    }
                } catch (error) {
                    // 기본값 설정
                    $('#pageInfo').text('1 / 1');
                    $('#totalCount').text('총 0건');
                }
            }
        }

        // 페이지 크기 변경
        function changePageSize() {
            var pageSize = $('#pageSize').val();
            table.setPageSize(parseInt(pageSize));
        }

        // 페이지네이션 컨트롤 함수들
        function goToFirstPage() {
            table.setPage(1);
        }

        function goToPrevPage() {
            var currentPage = table.getPage();
            if (currentPage > 1) {
                table.setPage(currentPage - 1);
            }
        }

        function goToNextPage() {
            if (table) {
                try {
                    var currentPage = table.getPage();
                    var pageSize = table.getPageSize();
                    var totalData = table.getDataCount();
                    var totalPages = Math.ceil(totalData / pageSize);

                    if (currentPage < totalPages) {
                        table.setPage(currentPage + 1);
                    }
                } catch (error) {
                    // 오류 무시
                }
            }
        }

        function goToLastPage() {
            if (table) {
                try {
                    var pageSize = table.getPageSize();
                    var totalData = table.getDataCount();
                    var totalPages = Math.ceil(totalData / pageSize);

                    if (totalPages > 0) {
                        table.setPage(totalPages);
                    }
                } catch (error) {
                    // 오류 무시
                }
            }
        }

        // Excel 임포트 함수
        function importExcel() {
            if (confirm('Excel 파일을 임포트하시겠습니까?\n\n주의: 기존 데이터를 삭제하고 새로 임포트하려면 "확인"을, 기존 데이터를 유지하면서 중복만 업데이트하려면 "취소"를 누르신 후 다음 단계에서 선택하세요.')) {
                // 기존 데이터 삭제 후 임포트
                if (confirm('⚠️ 경고: 모든 기존 거래처 데이터가 삭제됩니다!\n\n정말로 기존 데이터를 모두 삭제하고 새로 임포트하시겠습니까?')) {
                    window.location.href = 'import_excel.php?clear=1';
                } else {
                    // 기존 데이터 유지하면서 임포트 (중복은 업데이트)
                    window.location.href = 'import_excel.php';
                }
            } else {
                // 기존 데이터 유지하면서 임포트 (중복은 업데이트)
                if (confirm('거래처리스트.xls 파일의 내용을 데이터베이스에 임포트하시겠습니까?\n\n기존 거래처는 업데이트되고, 새로운 거래처는 추가됩니다.')) {
                    window.location.href = 'import_excel.php';
                }
            }
        }

        // 거래처 등록 함수 (통합 함수 재사용)
        function addCustomer() {
            console.log('[DEBUG] addCustomer called');
            // customerNum을 null로 전달하여 등록 모드로 열기
            openCustomerEditModal(null);
        }

        // postMessage 리스너 (전역으로 정의)
        window.addEventListener('message', function(event) {
            var data = event.data || {};
            if (data.scope !== 'customerModule') {
                return;
            }

            if (data.type === 'customerUpdated' || data.type === 'customerCreated') {
                if (customerEditModal) {
                    customerEditModal.hide();
                }
                var iframe = document.getElementById('customerEditIframe');
                if (iframe) {
                    iframe.src = '';
                }
                location.reload();
            } else if (data.type === 'editClosed' || data.type === 'customerEditCanceled') {
                if (customerEditModal) {
                    customerEditModal.hide();
                }
                var cancelIframe = document.getElementById('customerEditIframe');
                if (cancelIframe) {
                    cancelIframe.src = '';
                }
            }
        });

        // iframe 내부 함수 호출 (모달 헤더 버튼용)
        window.iframeSaveCustomer = function() {
            var iframe = document.getElementById('customerEditIframe');
            if (iframe && iframe.contentWindow) {
                // add.php나 edit.php의 폼 제출
                try {
                    var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    var form = iframeDoc.getElementById('customerForm');
                    if (form) {
                        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    } else {
                        // jQuery 이벤트로 제출
                        if (iframe.contentWindow.$) {
                            iframe.contentWindow.$('#customerForm').submit();
                        }
                    }
                } catch (e) {
                    console.error('iframe 내부 폼 제출 오류:', e);
                }
            }
        };

        window.iframeDeleteCustomer = function() {
            if (!currentCustomerId) {
                alert('삭제할 거래처가 선택되지 않았습니다.');
                return;
            }
            
            if (!confirm('정말로 이 거래처를 삭제하시겠습니까?')) {
                return;
            }
            
            var iframe = document.getElementById('customerEditIframe');
            if (iframe && iframe.contentWindow && typeof iframe.contentWindow.deleteCustomer === 'function') {
                iframe.contentWindow.deleteCustomer();
            } else {
                alert('삭제 기능을 사용할 수 없습니다.');
            }
        };

        // 거래처 상세 모달에서 삭제 함수
        window.deleteCustomerFromDetail = function(customerNum) {
            if (!customerNum) {
                alert('삭제할 거래처가 선택되지 않았습니다.');
                return;
            }

            if (!confirm('정말로 이 거래처를 삭제하시겠습니까?\n\n삭제된 데이터는 복구할 수 없습니다.')) {
                return;
            }

            console.log('[DEBUG] 거래처 삭제 시작:', customerNum);

            // AJAX로 삭제 요청
            var formData = new FormData();
            formData.append('num', customerNum);

            fetch('delete.php', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    alert(data.message || '거래처가 성공적으로 삭제되었습니다.');
                    
                    // 모달 닫기
                    if (customerDetailModal) {
                        customerDetailModal.hide();
                    }
                    
                    // 목록 새로고침
                    location.reload();
                } else {
                    alert('삭제 실패: ' + (data.message || '알 수 없는 오류가 발생했습니다.'));
                }
            })
            .catch(function(error) {
                console.error('거래처 삭제 오류:', error);
                alert('삭제 중 오류가 발생했습니다: ' + error.message);
            });
        };

        function renderCustomerDetail(customer, contacts) {
            if (!customer) {
                return '<div class="detail-empty">거래처 정보를 찾을 수 없습니다.</div>';
            }

            var groups = [];
            if (customer.is_sales_customer === 'Y') groups.push('<span class="badge bg-secondary me-1">매출</span>');
            if (customer.is_purchase_customer === 'Y') groups.push('<span class="badge bg-dark me-1">매입</span>');
            if (customer.is_other_customer === 'Y') groups.push('<span class="badge bg-light text-dark border me-1">기타</span>');
            if (!groups.length) {
                groups.push('<span class="text-muted">-</span>');
            }

            var html = '';
            html += '<div class="detail-section">';
            html += '<div class="detail-section-title"><i class="bi bi-info-circle"></i> 기본 정보</div>';
            html += '<div class="detail-grid">';
            html += detailItem('거래처명', formatValue(customer.company_name));
            html += detailItem('상호(법인명)', formatValue(customer.trade_name));
            html += detailItem('구분', formatValue(customer.classification));
            html += detailItem('등록번호', formatValue(customer.registration_number));
            html += detailItem('대표자명', formatValue(customer.representative_name));
            html += detailItem('사업자번호', formatValue(customer.business_registration_number));
            html += '</div></div>';

            html += '<div class="detail-section">';
            html += '<div class="detail-section-title"><i class="bi bi-telephone"></i> 연락처 / 주소</div>';
            html += '<div class="detail-grid">';
            html += detailItem('전화번호', formatValue(customer.phone_number));
            html += detailItem('휴대폰번호', formatValue(customer.mobile_number));
            html += detailItem('FAX번호', formatValue(customer.fax_number));
            html += detailItem('주소', formatValue(customer.address), true);
            html += '</div></div>';

            html += '<div class="detail-section">';
            html += '<div class="detail-section-title"><i class="bi bi-briefcase"></i> 사업 정보</div>';
            html += '<div class="detail-grid">';
            html += detailItem('업태', formatValue(customer.business_type));
            html += detailItem('종목', formatValue(customer.business_category));
            html += detailItem('등록일', formatDate(customer.registration_date));
            html += detailItem('최종수정일', formatDate(customer.last_modified_date));
            html += detailItem('계좌정보', formatValue(customer.bank_name && customer.account_number ? customer.bank_name + ' ' + customer.account_number : ''));
            html += detailItem('예금주', formatValue(customer.account_holder));
            html += detailItem('그룹', groups.join(' '));
            html += detailItem('비고', formatValue(customer.remarks));
            html += '</div></div>';

            html += '<div class="detail-section">';
            html += '<div class="detail-section-title"><i class="bi bi-person-lines-fill"></i> 담당자 정보</div>';
            if (contacts && contacts.length) {
                html += '<div class="table-responsive"><table class="detail-table">';
                html += '<thead><tr><th>#</th><th>이름</th><th>연락처</th><th>이메일</th><th>직급/부서</th><th>계산서</th><th>비고</th></tr></thead><tbody>';
                contacts.forEach(function(contact, index) {
                    html += '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + formatValue(contact.contact_name) + '</td>' +
                        '<td>' + formatValue(contact.contact_phone) + '</td>' +
                        '<td>' + formatValue(contact.contact_email) + '</td>' +
                        '<td>' + formatValue(contact.position_department) + '</td>' +
                        '<td>' + (contact.is_invoice_contact === 'Y' ? '예' : '아니오') + '</td>' +
                        '<td>' + formatValue(contact.contact_remarks) + '</td>' +
                        '</tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<div class="detail-empty">등록된 담당자 정보가 없습니다.</div>';
            }
            html += '</div>';

            // 첨부 파일 섹션 추가
            html += '<div class="detail-section">';
            html += '<div class="detail-section-title"><i class="bi bi-paperclip"></i> 첨부 파일</div>';
            html += '<div id="customerFilesList" class="customer-files-list">';
            html += '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            html += '</div>';
            html += '</div>';

            return html;
        }

        function detailItem(label, value, fullWidth) {
            var classes = 'detail-item' + (fullWidth ? ' full-width' : '');
            return '<div class="' + classes + '">' +
                '<div class="detail-label">' + label + '</div>' +
                '<div class="detail-value">' + (value || '-') + '</div>' +
                '</div>';
        }

        // 거래처 첨부 파일 목록 로드
        function loadCustomerFiles(customerNum) {
            var filesListElement = document.getElementById('customerFilesList');
            if (!filesListElement) {
                console.warn('customerFilesList 요소를 찾을 수 없습니다.');
                return;
            }

            $.ajax({
                url: '/filedrive/fileprocess.php',
                type: 'GET',
                data: {
                    num: customerNum,
                    tablename: 'customer',
                    item: 'attached',
                    folderPath: '미래기업/uploads/customer'
                },
                dataType: 'json'
            }).done(function(data) {
                console.log('첨부 파일 목록 조회 결과:', data);
                
                var html = '';
                
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(file, index) {
                        // 파일 정보 정규화
                        var fileId = file.fileId || file.picname || '';
                        var realname = file.realname || 'Unknown';
                        var link = file.link || file.webViewLink || '';
                        
                        // link가 없으면 Google Drive 링크 생성
                        if (!link && fileId) {
                            link = 'https://drive.google.com/file/d/' + fileId + '/view';
                        }
                        
                        // 파일 확장자로 아이콘 결정
                        var fileIcon = '📄';
                        var fileExt = '';
                        if (realname) {
                            var extMatch = realname.match(/\.([^.]+)$/i);
                            if (extMatch) {
                                fileExt = extMatch[1].toLowerCase();
                            }
                        }
                        
                        // 확장자별 아이콘
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) {
                            fileIcon = '🖼️';
                        } else if (fileExt === 'pdf') {
                            fileIcon = '📕';
                        } else if (['doc', 'docx'].includes(fileExt)) {
                            fileIcon = '📘';
                        } else if (['xls', 'xlsx'].includes(fileExt)) {
                            fileIcon = '📊';
                        }
                        
                        // HTML 이스케이프 처리 (data 속성용 - 작은따옴표도 이스케이프)
                        var escapedRealname = escapeHtml(realname).replace(/'/g, '&#39;');
                        var escapedFileId = escapeHtml(String(fileId)).replace(/'/g, '&#39;');
                        var escapedLink = escapeHtml(link || '').replace(/'/g, '&#39;');
                        
                        // data 속성에 안전하게 저장
                        html += '<div class="customer-file-item" data-file-id="' + escapedFileId + '" data-link="' + escapedLink + '" data-realname="' + escapedRealname + '" data-index="' + index + '">';
                        html += '<span class="file-icon">' + fileIcon + '</span>';
                        html += '<span class="file-name">' + escapeHtml(realname) + '</span>';
                        html += '</div>';
                    });
                } else {
                    html = '<div class="detail-empty">첨부된 파일이 없습니다.</div>';
                }
                
                filesListElement.innerHTML = html;
                
                // 파일 아이템 클릭 이벤트 바인딩
                $(filesListElement).find('.customer-file-item').on('click', function() {
                    var fileId = $(this).data('file-id');
                    var link = $(this).data('link');
                    var realname = $(this).data('realname');
                    var index = $(this).data('index');
                    viewCustomerFile(fileId, link, realname, index);
                });
            }).fail(function(jqxhr, status, error) {
                console.error('첨부 파일 목록 조회 오류:', jqxhr, status, error);
                filesListElement.innerHTML = '<div class="detail-empty text-muted">파일 목록을 불러올 수 없습니다.</div>';
            });
        }

        // 거래처 파일 보기/다운로드 함수 (전역 함수)
        window.viewCustomerFile = function(fileId, link, realname, index) {
            console.log('파일 클릭:', { fileId: fileId, link: link, realname: realname, index: index });
            
            // link가 없으면 Google Drive 링크 생성
            if (!link && fileId) {
                link = 'https://drive.google.com/file/d/' + fileId + '/view';
            }
            
            if (!link) {
                alert('파일 링크를 가져올 수 없습니다.');
                return;
            }
            
            // 파일 확장자 확인
            var isImage = false;
            var isPdf = false;
            var isViewable = false;
            
            if (realname) {
                var extMatch = realname.match(/\.([^.]+)$/i);
                if (extMatch) {
                    var ext = extMatch[1].toLowerCase();
                    isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
                    isPdf = ext === 'pdf';
                    isViewable = isImage || isPdf;
                }
            }
            
            if (isImage) {
                // 이미지인 경우: 팝업으로 확대 보기
                if (typeof popupCenter === 'function') {
                    popupCenter(link, 'imageViewer', 1000, 700);
                } else {
                    var width = 1000;
                    var height = 700;
                    var left = (window.innerWidth / 2) - (width / 2) + window.screenX;
                    var top = (window.innerHeight / 2) - (height / 2) + window.screenY;
                    window.open(link, 'imageViewer_' + Date.now(), 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top + ', scrollbars=yes, resizable=yes');
                }
            } else if (isViewable) {
                // PDF나 이미지는 새 창에서 열기
                if (typeof popupCenter === 'function') {
                    popupCenter(link, 'fileViewer', 900, 700);
                } else {
                    var width = 900;
                    var height = 700;
                    var left = (window.innerWidth / 2) - (width / 2) + window.screenX;
                    var top = (window.innerHeight / 2) - (height / 2) + window.screenY;
                    window.open(link, 'fileViewer_' + Date.now(), 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top + ', scrollbars=yes, resizable=yes');
                }
            } else {
                // 다운로드 가능한 파일은 다운로드 링크 생성
                var a = document.createElement('a');
                a.href = link;
                a.download = realname || 'download';
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
        }

        function formatValue(value, fallback) {
            if (value === null || value === undefined || value === '') {
                return fallback || '-';
            }
            return escapeHtml(String(value));
        }

        function formatDate(value) {
            if (!value) return '-';
            var date = new Date(value);
            if (isNaN(date.getTime())) {
                return escapeHtml(value);
            }
            return date.toISOString().split('T')[0];
        }

        function escapeHtml(value) {
            return value
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        // 컬럼 설정 관련 함수들

        // 쿠키 저장 함수
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        // 쿠키 읽기 함수
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // 컬럼 설정 모달 열기
        function openColumnSettings() {
            var columns = table.getColumns();
            var checkboxContainer = $('#columnCheckboxes');
            checkboxContainer.empty();

            // 각 컬럼에 대한 체크박스 생성
            columns.forEach(function(column) {
                var definition = column.getDefinition();
                var field = definition.field;
                var title = definition.title;

                // 선택 컬럼과 번호 컬럼은 제외
                if (field === 'select' || field === 'num') {
                    return;
                }

                var isVisible = column.isVisible();
                var checkboxHtml =
                    '<div class="col-md-6 mb-2">' +
                    '  <div class="form-check">' +
                    '    <input class="form-check-input" type="checkbox" value="' + field + '" ' +
                    '           id="col_' + field + '" ' + (isVisible ? 'checked' : '') +
                    '           onchange="toggleColumn(\'' + field + '\')">' +
                    '    <label class="form-check-label" for="col_' + field + '">' +
                    '      ' + title +
                    '    </label>' +
                    '  </div>' +
                    '</div>';

                checkboxContainer.append(checkboxHtml);
            });

            // 모달 표시
            var modal = new bootstrap.Modal(document.getElementById('columnSettingsModal'));
            modal.show();
        }

        // 컬럼 표시/숨김 토글
        function toggleColumn(field) {
            var checkbox = $('#col_' + field);
            var isChecked = checkbox.is(':checked');

            if (isChecked) {
                table.showColumn(field);
            } else {
                table.hideColumn(field);
            }

            // 설정 저장
            saveColumnSettings();
        }

        // 컬럼 설정 저장
        function saveColumnSettings() {
            var columns = table.getColumns();
            var settings = {};

            columns.forEach(function(column) {
                var definition = column.getDefinition();
                var field = definition.field;

                // 선택 컬럼과 번호 컬럼은 제외
                if (field === 'select' || field === 'num') {
                    return;
                }

                settings[field] = column.isVisible();
            });

            // JSON 문자열로 변환하여 쿠키에 저장 (365일 유지)
            setCookie('corp_table_columns', JSON.stringify(settings), 365);
        }

        // 컬럼 설정 불러오기
        function loadColumnSettings() {
            var settingsStr = getCookie('corp_table_columns');

            if (!settingsStr) {
                return; // 저장된 설정이 없으면 기본값 사용
            }

            try {
                var settings = JSON.parse(settingsStr);

                // 각 컬럼의 표시 여부 적용
                Object.keys(settings).forEach(function(field) {
                    var isVisible = settings[field];

                    if (isVisible) {
                        table.showColumn(field);
                    } else {
                        table.hideColumn(field);
                    }
                });
            } catch (e) {
                console.error('컬럼 설정 로드 실패:', e);
            }
        }

        // 컬럼 설정 초기화
        function resetColumnSettings() {
            // 쿠키 삭제
            setCookie('corp_table_columns', '', -1);

            // 모든 컬럼 표시 (선택, 번호 제외)
            var columns = table.getColumns();
            columns.forEach(function(column) {
                var definition = column.getDefinition();
                var field = definition.field;

                if (field !== 'select' && field !== 'num') {
                    table.showColumn(field);
                }
            });

            // 모달 닫기
            var modal = bootstrap.Modal.getInstance(document.getElementById('columnSettingsModal'));
            if (modal) {
                modal.hide();
            }

            // 모달 재오픈 (체크박스 상태 업데이트 위해)
            setTimeout(function() {
                openColumnSettings();
            }, 300);
        }
    </script>

    <div class="container-fluid mt-3 mb-3">
        <?php include '../footer_sub.php'; ?>
    </div>
</body>

</html>
