<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
require_once getDocumentRoot() . '/vendor/autoload.php';
require_once(includePath('lib/mydb.php'));

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';	

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
if (class_exists('Google_Service_Drive')) {
    $client->addScope(\Google_Service_Drive::DRIVE);

    // Google Drive 서비스 초기화
    $service = new \Google_Service_Drive($client);
} else {
    // Google_Service_Drive 클래스가 없을 때 경고 없이 처리
    $service = null;
}

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    return count($response->files) > 0 ? $response->files[0]->id : null;
}
// 첫 화면 표시 문구
$title_message = 'EL Ceiling 수주'; 
 ?>

<?php 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
		  header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }

include getDocumentRoot() . '/load_header.php';   
 ?>
<title> <?=$title_message?> </title>
<!-- Tabulator CSS and JS -->
<link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>
<?php
// 모바일 접속일 때만 viewport meta 태그 출력
function isMobile() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    return preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent);
}
// if (isMobile()) {
//     echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
// }
?>
<body>		 
<!-- 로딩 오버레이 -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="loading-container">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="loading-message">
            <h5 class="text-primary mb-2">미설계 리스트를 추출중입니다</h5>
            <p class="text-muted">잠시만 기다려주세요...</p>
        </div>
    </div>
</div>

<!-- 정렬 완료 모달 -->
<div id="sortCompleteModal" class="sort-complete-modal" style="display: none;">
    <div class="sort-complete-container">
        <div class="sort-complete-icon">
            <i class="bi bi-check-circle-fill text-success"></i>
        </div>
        <div class="sort-complete-message">
            <h5 class="text-success mb-2">정렬이 완료되었습니다</h5>
            <p class="text-muted mb-0">납기일 순으로 정렬되었습니다.</p>
        </div>
        <button type="button" class="btn btn-success btn-sm" onclick="closeSortCompleteModal()">
            확인
        </button>
    </div>
</div>

<?php require_once(includePath('myheader.php')); ?>   
<style>
/* 로딩 오버레이 스타일 */
 .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(3px);
    animation: fadeIn 0.3s ease-in-out;
}

.loading-container {
    background: white;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%;
    animation: slideUp 0.4s ease-out;
}

.loading-spinner {
    margin-bottom: 20px;
}

.loading-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}

.loading-message h5 {
    font-weight: 600;
    margin-bottom: 10px;
}

.loading-message p {
    font-size: 0.95rem;
    margin: 0;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

/* 다크 모드 지원 */
[data-theme="dark"] .loading-container {
    background: #2d3748;
    color: white;
}

[data-theme="dark"] .loading-message h5 {
    color: #63b3ed !important;
}

[data-theme="dark"] .loading-message p {
    color: #a0aec0 !important;
}

/* 정렬 완료 모달 스타일 */
.sort-complete-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.3s ease-in-out;
}

.sort-complete-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%;
    animation: slideUp 0.4s ease-out;
    border: 2px solid #28a745;
}

.sort-complete-icon {
    margin-bottom: 20px;
}

.sort-complete-icon i {
    font-size: 4rem;
    animation: bounceIn 0.6s ease-out;
}

.sort-complete-message h5 {
    font-weight: 600;
    margin-bottom: 10px;
}

.sort-complete-message p {
    font-size: 1rem;
    margin: 0;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* 다크 모드 지원 - 정렬 완료 모달 */
[data-theme="dark"] .sort-complete-container {
    background: #2d3748;
    color: white;
    border-color: #28a745;
}

[data-theme="dark"] .sort-complete-message h5 {
    color: #28a745 !important;
}

[data-theme="dark"] .sort-complete-message p {
    color: #a0aec0 !important;
}

/* Light mode styles */
body {
  background-color: #ffffff;
  color: #000000;
  overflow-x: auto; /* 데스크톱에서 가로 스크롤 허용 */
}

/* 모바일 전용 스타일 */
@media (max-width: 768px) {
  body {
    font-size: 16px; /* iOS 줌 방지 */
    overflow-x: hidden; /* 모바일에서만 가로 스크롤 방지 */
  }

  /* 컨테이너 패딩 조정 */
  .container-fluid {
    padding-left: 8px;
    padding-right: 8px;
  }

  /* 카드 마진 줄이기 */
  .card {
    margin-bottom: 0.5rem;
    border-radius: 8px;
  }

  .card-body {
    padding: 0.75rem;
  }

  /* 버튼 그룹 스택 방식 */
  .d-flex.align-items-center {
    flex-wrap: wrap;
    gap: 0.25rem;
  }

  /* 버튼 크기 조정 */
  .btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    min-height: 44px; /* 터치 친화적 크기 */
    min-width: 44px;
    margin: 0.125rem; /* 버튼 간 공간 */
  }

  /* 대형 버튼들 */
  .btn:not(.btn-sm) {
    min-height: 48px;
    padding: 0.75rem 1rem;
    font-weight: 500;
  }

  /* 옵션 버튼들 모바일 최적화 */
  #showalign, #showextract {
    min-width: 80px;
    white-space: nowrap;
  }

  /* 하드웨어 버튼 스타일 */
  .btn:active {
    transform: scale(0.98);
    transition: transform 0.1s ease;
  }

  /* 드롭다운 컨테이너 모바일 최적화 */
  #showalignframe, #showextractframe {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    max-width: 90vw;
    max-height: 80vh;
    overflow-y: auto;
  }

  /* 검색 영역 수직 스택 */
  .d-flex.justify-content-center.align-items-center {
    flex-direction: column;
    gap: 0.5rem;
  }

  .d-flex.justify-content-center.align-items-center > * {
    margin: 0.125rem;
  }

  /* 검색 드롭다운과 입력필드 */
  #find, #search {
    width: 100% !important;
    max-width: 200px;
  }

  /* 날짜 입력필드 */
  #fromdate, #todate {
    width: 140px !important;
  }

  /* 체크박스 라벨 */
  label[for="receptionDateFilter"] {
    font-size: 0.875rem;
    margin: 0.25rem;
  }

  /* D-day 선택 */
  #dDaySelect {
    width: 80px !important;
    min-width: 80px;
  }

  /* 검색 영역 모바일 최적화 */
  .form-control, .form-select {
    font-size: 16px; /* iOS 줌 방지 */
    min-height: 44px;
  }

  /* 공지사항 배너 모바일 최적화 */
  .shadow-lg.rounded-4 {
    margin: 0.5rem;
    padding: 1rem;
    min-height: auto;
    text-align: center;
  }

  .shadow-lg.rounded-4 .badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    display: inline-block;
    margin: 0.25rem;
  }

  /* 아이콘 크기 조정 */
  .bi {
    font-size: 1.1em;
  }

  /* 타이틀 항용 */
  h5 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0.5rem 0;
  }

  /* 배지 색상 */
  .badge.bg-dark {
    background-color: #495057 !important;
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
  }

  /* 커스텀 입력 래퍼 */
  .inputWrap {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    max-width: 200px;
  }

  .inputWrap input {
    width: 100% !important;
    padding-right: 2.5rem;
  }

  .btnClear {
    position: absolute;
    right: 0.5rem;
    width: 20px;
    height: 20px;
    background: #6c757d;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btnClear:before {
    content: '×';
    color: white;
    font-size: 14px;
    line-height: 1;
  }
}

/* Dark mode styles */
[data-theme="dark"] {
  background-color: #000000;
  color: #ffffff;
}

/* Toggle switch styles */
.toggle-switch {
  display: inline-block;
  position: relative;
  width: 60px;
  height: 34px;
}

.toggle-switch input[type="checkbox"] {
  display: none;
}

.toggle-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 34px;
}

.toggle-slider:before {
	  position: absolute;
	  content: "";
	  height: 26px;
	  width: 26px;
	  left: 4px;
	  bottom: 4px;
	  background-color: white;
	  transition: .4s;
	  border-radius: 50%;
}

input[type="checkbox"]:checked + .toggle-slider {
  background-color: #2196F3;
}

input[type="checkbox"]:checked + .toggle-slider:before {
  transform: translateX(26px);
}

  .hidden-field,
  .part-field {
	display: none; /* 체크박스 체크 시 숨겨진 필드를 숨김 */
  }
  
/* 기본적으로 숨김 처리 */

#deadline_laserContainer {
	cursor: pointer;
}

#autocomplete-list {
	border: 1px solid #d4d4d4;
	border-bottom: none;
	border-top: none;
	position: absolute;
	top: 93%;
	left: 50%;
	right: 30%;
	width : 10%;
	z-index: 999;
}
.autocomplete-item {
	padding: 10px;
	cursor: pointer;
	background-color: #fff;
	border-bottom: 1px solid #d4d4d4;
}
.autocomplete-item:hover {
	background-color: #e9e9e9;
}

.custom-tooltip {
    display: none;
    position: absolute;
    border: 1px solid #ddd;
    background-color: blue;
	color:white;
	font-size:25px;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    top: 100px;
}

/* 툴팁 위치 조정 */
#denkriModel:hover + .custom-tooltip {
    display: block;
    left: 70%; /* 화면 가로축의 중앙에 위치 */
    top: 90px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}
	
#showalign {
	display: inline-block;
	position: relative;
}
		
#showalignframe {
	display: none;
	position: absolute;
	width: 1000px;
	z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */    
    transform: translateX(-40%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */		
}
	
#showextract {
	display: inline-block;
	position: relative;
}

#showextractframe {
	display: none;
	position: absolute;
	width: 50%;
	z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */    
    transform: translateX(-40%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */		
}	

th, td {
    vertical-align: middle !important;
}

/* Tabulator 스타일 개선 */
#tabulator-table {
    width: 100%;
    overflow-x: auto; /* 가로 스크롤 활성화 */
}

.tabulator {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow-x: auto; /* 가로 스크롤 활성화 */
    min-width: 100%; /* 최소 너비 설정 */
}

.tabulator .tabulator-header,
.tabulator .tabulator-header .tabulator-col {
    background-color: #b6d7ff;
}

.tabulator .tabulator-header {
    border-bottom: 1px solid #dee2e6;
    height: 52px; /* 기본 40px의 1.3배 */
}

.tabulator .tabulator-header .tabulator-col {
    border-right: 1px solid #dee2e6;
}

.tabulator .tabulator-row {
    border-bottom: 1px solid #dee2e6;
    height: 42px; /* 기본 32px의 1.3배 */
}

.tabulator .tabulator-row:hover {
    background-color: #e3f2fd;
    transition: background-color 0.2s ease;
}

.tabulator .tabulator-row.tabulator-row-even {
    background-color: #ffffff;
}

.tabulator .tabulator-row.tabulator-row-odd {
    background-color: #f8f9fa;
}

.tabulator .tabulator-cell {
    border-right: 1px solid #dee2e6;
    padding: 5px 10px; /* 1.3배 증가 (4px->5px, 8px->10px) */
    line-height: 32px; /* 행 높이에 맞춘 line-height */
}

.tabulator .tabulator-cell:hover {
    background-color: #e3f2fd;
}

/* 테이블 행 전체가 클릭 가능하도록 강조 및 통합된 스타일 */
.tabulator .tabulator-row,
.tabulator .tabulator-cell {
    cursor: pointer;
}

.tabulator .tabulator-row:hover,
.tabulator .tabulator-cell:hover {
    transition: background-color 0.2s ease;
}

/* 클릭 시 시각적 피드백 */
.tabulator .tabulator-row:active {
    background-color: #bbdefb;
}

.tabulator .tabulator-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

/* 모바일 테이블 스타일 */
@media (max-width: 768px) {
  .tabulator {
    font-size: 14px;
    border-radius: 8px;
  }

  .tabulator .tabulator-header {
    height: 48px;
  }

  .tabulator .tabulator-row {
    height: 56px; /* 모바일에서 더 높게 */
  }

  .tabulator .tabulator-cell {
    padding: 8px 6px;
    line-height: 1.4;
    word-break: break-word;
  }

  /* 모바일에서 특정 컬럼 숨기기 */
  .tabulator .tabulator-col[data-field="reception_date"],
  .tabulator .tabulator-col[data-field="delivery_date"],
  .tabulator .tabulator-col[data-field="detailed_spec"] {
    display: none;
  }

  .tabulator .tabulator-cell[tabulator-field="reception_date"],
  .tabulator .tabulator-cell[tabulator-field="delivery_date"],
  .tabulator .tabulator-cell[tabulator-field="detailed_spec"] {
    display: none;
  }

  /* 모바일 카드형 레이아웃 대안 */
  .mobile-card-view {
    display: none;
  }

  .mobile-card-view.active {
    display: block;
  }

  .mobile-card-view .tabulator {
    display: none;
  }

  .data-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .data-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transform: translateY(-1px);
  }

  .data-card:active {
    transform: translateY(0);
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
  }

  .data-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
  }

  .data-card-title {
    font-weight: 600;
    font-size: 1rem;
    color: #212529;
    margin: 0;
  }

  .data-card-number {
    background: #007bff;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
  }

  .data-card-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    font-size: 0.875rem;
  }

  .data-card-item {
    display: flex;
    flex-direction: column;
  }

  .data-card-item.full-width {
    grid-column: span 2;
  }

  .data-card-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.125rem;
  }

  .data-card-value {
    color: #212529;
    font-weight: 500;
  }

  .status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
  }

  .status-complete {
    background-color: #d4edda;
    color: #155724;
  }

  .status-pending {
    background-color: #fff3cd;
    color: #856404;
  }

  .status-progress {
    background-color: #cce5ff;
    color: #004085;
  }

  /* 추가 모바일 최적화 */

  /* 로딩 스피너 및 모달 */
  .loading-overlay .loading-container {
    max-width: 90vw;
    text-align: center;
  }

  .sort-complete-modal .sort-complete-container {
    max-width: 90vw;
    text-align: center;
  }

  /* 테이블 오버플로우 처리 */
  .table-responsive {
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }

  /* Todo 리스트 영역 */
  .card .table {
    font-size: 0.875rem;
    margin-bottom: 0;
  }

  #todolist, #todolist_draw {
    font-size: 0.8rem;
    padding: 0.5rem;
    min-height: 100px;
    resize: none;
  }

  /* 컬럼 설정 카드 */
  .column-settings-card {
    margin-bottom: 0.5rem;
  }

  .column-settings-card .card-body {
    max-height: 40vh;
    overflow-y: auto;
  }

  .form-check {
    padding: 0.25rem 0;
  }

  .form-check-label {
    font-size: 0.875rem;
    cursor: pointer;
  }

  /* 터치 영역 예약 */
  .data-card, .btn, .form-control, .form-select, .form-check-input {
    -webkit-tap-highlight-color: rgba(0,0,0,0.1);
  }

  /* iOS 사파리 보정 */
  input, select, textarea, button {
    -webkit-appearance: none;
    border-radius: 0.375rem;
  }

  /* 스크롤 바 스타일링 */
  ::-webkit-scrollbar {
    width: 6px;
  }

  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  ::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* 에니메이션 효과 */
  .data-card, .btn, .form-control {
    transition: all 0.2s ease;
  }

  /* 접근성 개선 */
  .visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
  }

  /* 포커스 지시자 */
  button:focus, .btn:focus,
  input:focus, select:focus, textarea:focus,
  .form-control:focus, .form-select:focus {
    outline: 2px solid #007bff;
    outline-offset: 2px;
  }

  /* 고대비 모드 지원 */
  @media (prefers-contrast: high) {
    .data-card {
      border: 2px solid #000;
    }
    .status-badge {
      border: 1px solid currentColor;
    }
  }

  /* 다크 모드 상태에서의 모바일 스타일 */
  [data-theme="dark"] .data-card {
    background: #2d3748;
    border-color: #4a5568;
    color: #e2e8f0;
  }

  [data-theme="dark"] .data-card-label {
    color: #a0aec0;
  }

  [data-theme="dark"] .btn-outline-primary {
    color: #63b3ed;
    border-color: #63b3ed;
  }

  [data-theme="dark"] .btn-outline-primary:hover {
    background-color: #63b3ed;
    color: #1a202c;
  }
}

/* 컬럼 설정 카드 스타일 */
.column-settings-card {
    margin-bottom: 1rem;
}

.column-settings-card .form-check {
    margin-bottom: 0.25rem;
}

.column-settings-card .form-check-label {
    font-size: 0.9rem;
}

.column-settings-card .card-header:hover {
    background-color: #f8f9fa;
}

.column-settings-card .collapse.show {
    display: block;
}

.column-settings-card .collapse:not(.show) {
    display: none;
}

#column-settings-icon {
    transition: transform 0.3s ease;
}

#column-settings-icon.rotated {
    transform: rotate(180deg);
}

/* 헤더 필터 스타일 */
.tabulator .tabulator-header .tabulator-col .tabulator-header-filter {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px 10px; /* 1.3배 증가 (4px->5px, 8px->10px) */
    font-size: 12px;
    margin: 2px;
    transition: all 0.2s ease;
}

.tabulator .tabulator-header .tabulator-col .tabulator-header-filter:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    background-color: #ffffff;
}

.tabulator .tabulator-header .tabulator-col .tabulator-header-filter input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 11px;
    padding: 2px;
}

.tabulator .tabulator-header .tabulator-col .tabulator-header-filter select {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 11px;
    padding: 2px;
    cursor: pointer;
}

/* 헤더 필터가 있는 컬럼의 높이 조정 */
.tabulator .tabulator-header .tabulator-col.tabulator-col-title {
    min-height: 78px; /* 필터를 위한 추가 공간 (60px * 1.3) */
}

/* 활성화된 필터 스타일 */
.tabulator .tabulator-header .tabulator-col .tabulator-header-filter.tabulator-header-filter-active {
    background-color: #e7f3ff;
    border-color: #007bff;
    font-weight: bold;
}

/* 필터 플레이스홀더 스타일 */
.tabulator .tabulator-header .tabulator-col .tabulator-header-filter input::placeholder,
.tabulator .tabulator-header .tabulator-col .tabulator-header-filter select option:first-child {
    color: #6c757d;
    font-style: italic;
}

/* 검색 결과 하이라이트 효과 */
.tabulator-row.tabulator-filtered {
    background-color: #fff3cd !important;
    border-left: 3px solid #ffc107;
}

/* Tabulator 통합 폰트 크기 설정 */
.tabulator {
    font-size: 1.03em;
}

/* 테이블 뷰 가로 스크롤 설정 */
.table-view {
    width: 100%;
    overflow-x: auto;
}

/* 데스크톱에서만 가로 스크롤 활성화 */
@media (min-width: 769px) {
    .table-view, #tabulator-table {
        overflow-x: auto !important;
    }
}
</style>

</head>
<?php
$tablename = 'ceiling'; 
include "_request.php";
// print 'search : ' . var_dump($search);
function check_in_range($start_date, $end_date, $user_date)
{  
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = strtotime($user_date);
  
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}	  

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// /////////////////////////첨부파일 있는 것 불러오기 
$savefilename_arr=array(); 
$realname_arr=array(); 
$attach_arr=array(); 
$tablename='ceiling';
$item = 'ceiling';

$sql = "SELECT * FROM {$DB}.picuploads WHERE tablename=? AND item = ? AND parentnum = ?";
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$tablename, $item, $num]);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"];
        $realname = $row["realname"];
        $realname_arr[] = $realname; // realname 배열에 추가

        if (preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            // Google Drive 파일 ID로 처리
            $fileId = $picname;

            try {
                // Google Drive 파일 정보 가져오기
                $file = $service->files->get($fileId, ['fields' => 'webViewLink, thumbnailLink']);
                $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                $webViewLink = $file->webViewLink;
                $savefilename_arr[] = [
                    'thumbnail' => $thumbnailUrl,
                    'link' => $webViewLink,
                    'fileId' => $fileId,
                    'realname' => $realname // realname 포함
                ];
            } catch (Exception $e) {
                error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                $savefilename_arr[] = [
                    'thumbnail' => "https://drive.google.com/uc?id=$fileId",
                    'link' => null,
                    'fileId' => $fileId,
                    'realname' => $realname // realname 포함
                ];
            }
        } else {
            // Google Drive에서 파일 이름으로 검색
            try {
                $query = sprintf("name='%s' and trashed=false", addslashes($picname)); // 파일 이름으로 검색
                $response = $service->files->listFiles([
                    'q' => $query,
                    'fields' => 'files(id, webViewLink, thumbnailLink)',
                    'pageSize' => 1
                ]);

                if (count($response->files) > 0) {
                    $file = $response->files[0];
                    $fileId = $file->id; // 검색된 파일의 ID
                    $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                    $webViewLink = $file->webViewLink;
                    $savefilename_arr[] = [
                        'thumbnail' => $thumbnailUrl,
                        'link' => $webViewLink,
                        'fileId' => $fileId,
                        'realname' => $realname // realname 포함
                    ];

                    // 데이터베이스 업데이트: 검색된 파일 ID 저장
                    $updateSql = "UPDATE {$DB}.picuploads SET picname = ? WHERE item = ? AND parentnum = ? AND picname = ?";
                    $updateStmh = $pdo->prepare($updateSql);
                    $updateStmh->execute([$fileId, $item, $num, $picname]);
                } else {
                    error_log("Google Drive에서 파일을 찾을 수 없습니다: " . $picname);
                    $savefilename_arr[] = [
                        'thumbnail' => null,
                        'link' => null,
                        'fileId' => null,
                        'realname' => $realname // realname 포함
                    ];
                }
            } catch (Exception $e) {
                error_log("Google Drive 파일 검색 실패: " . $e->getMessage());
                $savefilename_arr[] = [
                    'thumbnail' => null,
                    'link' => null,
                    'fileId' => null,
                    'realname' => $realname // realname 포함
                ];
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// var_dump($attach_arr);

$today = date('Y-m-d');

// 기본 D-15
$dDay = isset($_GET['dDay']) ? intval($_GET['dDay']) : 15;
$afterday = date("Y-m-d", strtotime("+$dDay day", strtotime($today)));
$end_date = $afterday;

$start_date = $today ;
//print check_in_range($start_date, $end_date, $user_date);
//print '오늘 날짜 ' . $today;
//print '오늘기준 6일전 날짜 ' . $beforefiveday;

// laser todolist 배열
$todolist=array();
$todolistlink=array();

// 설계 todolist 배열
$todolist_draw=array();
$todolistlink_draw=array();

if(isset($_REQUEST["fromdate"]))  //수정 버튼을 클릭해서 호출했는지 체크
$fromdate=$_REQUEST["fromdate"];

if(isset($_REQUEST["todate"]))  //수정 버튼을 클릭해서 호출했는지 체크
$todate=$_REQUEST["todate"];

// 접수일 기준 필터 처리
$reception_filter = '0';
if (isset($_REQUEST["reception_filter"]) && $_REQUEST["reception_filter"] == '1') {
    $reception_filter = '1';
} elseif (isset($_POST["reception_filter"]) && $_POST["reception_filter"] == '1') {
    $reception_filter = '1';
}  

// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 1개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
		
$sql="select * from mirae8440.ceiling " ;
try{  
		$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh    
		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			
			  include '_rowDB.php';						  	
			  			  
			  $main_draw_arr="";			  
			  if(substr($main_draw,0,2)=="20")  $main_draw_arr= iconv_substr($main_draw,5,5,"utf-8");		    
			     elseif($bon_su<1) $main_draw_arr= "X";		    
   
   		        $lc_draw_arr="";			  
			  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr= iconv_substr($lc_draw,5,5,"utf-8") ;
			     elseif($lc_su<1) $lc_draw_arr = "X";	
			  if($type=='011' || $type=='012' || $type=='013D' || $type=='025' || $type=='017' || $type=='014' || $type=='037' || $type=='038')
				 $lc_draw_arr = "X";		
			
			   // 기타에 대한 처리
			   $etc_draw_arr="";
				if(substr($etc_draw,0,2)=="20")  
					$etc_draw_arr= iconv_substr($etc_draw,5,5,"utf-8");		    
				elseif($etc_su<1) $etc_draw_arr= "X";		    				 
				
			  
			  $maincondition = 1; // 조건충족 확인 본천장 제작과정 확인
			  $lccondition = 1; // 조건충족 확인 LC 설계가 있다면 제작과정 확인
			  $etccondition = 1; // 조건충족 확인 제작과정 확인
			  $maincondition_draw = 1; // 조건충족 확인 본천장 설계
			  $lccondition_draw = 1; // 조건충족 확인 LC 설계가 있다면 본천장 레이져 가공 조건충족여부
			  $etccondition_draw = 1; // 조건충족 확인 기타 설계
			  // laser 일정
			  // 은성본천장 외주인경우는 제외시킴
			  if ( (($main_draw!='' and $main_draw!='0000-00-00') and ( ($eunsung_laser_date!='' and $eunsung_laser_date!='0000-00-00') or ($eunsung_make_date!='' and $eunsung_make_date!='0000-00-00')  ) )  or  ($main_draw_arr== "X") )
				    $maincondition = 0; 
				
			  // LC 도면설계와 LC레이져가공의 조건이 충족되면
			  if( (($lc_draw!='' and $lc_draw!='0000-00-00') and ($lclaser_date!='' and $lclaser_date!='0000-00-00')) or  ($lc_draw_arr== "X")  )
				    $lccondition = 0; 		

			  // etc 도면설계와 etc레이져가공의 조건이 충족되면
			  if( (($etc_draw!='' and $etc_draw!='0000-00-00') and ($etclaser_date!='' and $etclaser_date!='0000-00-00')) or  ($etc_draw_arr== "X")  )
				    $etccondition = 0; 		
				
			  // 설계일정	
			  // 은성본천장 외주인경우는 제외시킴
			  if ( ($main_draw!='' and $main_draw!='0000-00-00') or  ($main_draw_arr== "X") )
				    $maincondition_draw = 0; 
				
			  // LC 도면설계와 LC레이져가공의 조건이 충족되면
			  if( ($lc_draw!='' and $lc_draw!='0000-00-00') or  ($lc_draw_arr== "X")  )
				    $lccondition_draw = 0; 		
				
			  // etc 도면설계와 etc레이져가공의 조건이 충족되면
			  if( ($etc_draw!='' and $etc_draw!='0000-00-00') or  ($etc_draw_arr== "X")  )
				    $etccondition_draw = 0; 		
					
			  
			  $user_date = $deadline;			   
			  // laser 가공일정 체크
			  if(check_in_range($start_date, $end_date, $user_date) && ($maincondition || $lccondition || $etccondition) )
			    {
				  array_push($todolist,$workplacename . '(' . $secondord .')' );			  
				  array_push($todolistlink,$num);			  
				}		   
			  // 설계 일정 체크	
			  if(check_in_range($start_date, $end_date, $user_date) && ($maincondition_draw || $lccondition_draw || $etccondition_draw) )
			    {
				  array_push($todolist_draw,$workplacename . '(' . $secondord .')' );			  
				  array_push($todolistlink_draw,$num);			  
				}
		
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

 $todolistCount = count($todolist);
 $todolistCount_draw = count($todolist_draw);
	   
  $sum=array();
  	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"] ?? "";
  else 
     $mode="";     
   
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
   $find=$_REQUEST["find"] ?? "";
  	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분

// 정렬 파라미터 처리
$sortof = $_REQUEST["sortof"] ?? "num";
$cursort = $_REQUEST["cursort"] ?? "desc";

// 기본 정렬 설정
$orderby = "order by " . $sortof . " " . $cursort . " ";

if ($check=='1')  // 미출고 리스트 클릭
		{
				$attached=" and ((workday='') or (workday='0000-00-00')) ";
				$whereattached=" where ( workday='' or (workday='0000-00-00') ) ";
		}
if ($check=='2')  // 제작완료 출고대기 클릭
		{				
				$whereattached=" where ((workday='') or (workday='0000-00-00')) and ( ((bon_su!='') and (mainassembly_date!='0000-00-00')) or  ((lc_su!='') and (lcassembly_date!='0000-00-00')) or ((etc_su!='') and (etcassembly_date!='0000-00-00')) )";
				$attached=" and  ( (workday='') or (workday='0000-00-00')) and ( ((bon_su!='') and (mainassembly_date!='0000-00-00')) or  ((lc_su!='') and (lcassembly_date!='0000-00-00')) or ((etc_su!='') and (etcassembly_date!='0000-00-00')) ) ";
                // 정렬이 명시적으로 설정되지 않은 경우에만 기본 정렬 적용
                if ($sortof == "num" && $cursort == "desc") {
                    $orderby="order by deadline asc, orderday asc ";
                }
		}		
if ($check=='3')  // 출고완료 미청구 클릭
		{
				$attached=" and ((workday!='') and (workday!='0000-00-00')) ";
				$whereattached=" where workday!='' ";
		}
if ($check=='4')  // 미설계List
		{
				$attached=" and ( ((main_draw='') or (main_draw='0000-00-00')) and (bon_su>'0')) or  ((etc_draw='') or (etc_draw='0000-00-00')) and (etc_su>'0') or (((lc_draw='') or (lc_draw='0000-00-00')) and (lc_su>'0') and type NOT IN ('011','012','013D','025','017','014','037','038') ) ";
				// $attached=" and (((main_draw='') or (main_draw='0000-00-00')) or (((lc_draw='')  or (lc_draw='0000-00-00')) and type NOT IN ('011','012','013D','025','017','014')))  ";
				$whereattached=" where (((main_draw='') or (main_draw='0000-00-00')) and (bon_su>'0')) or ((etc_draw='') or (etc_draw='0000-00-00')) and (etc_su>'0') or (((lc_draw='') or (lc_draw='0000-00-00')) and (lc_su>'0') and type NOT IN ('011','012','013D','025','017','014','037','038') ) ";				
		}		
if ($check=='6')  // 외주가공
		{
	        $attached = " and (outsourcing!='') ";
			$whereattached = " where outsourcing!='' ";
		}		
		
if ($check=='5')  // 설계완료 미발주 부품
		{
	$attached="  and NOT (
    ((main_draw = '' OR main_draw = '0000-00-00') AND bon_su > '0')
    OR
    ((lc_draw = '' OR lc_draw = '0000-00-00') AND lc_su > '0' AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))
    OR
    ((etc_draw = '' OR etc_draw = '0000-00-00') AND etc_su > '0')
	) 
   And 
	((date(workday)>=date(now())) or date(workday)='0000-00-00' ) and (((order_com1<>'') and (order_date1='0000-00-00')) or  ((order_com2<>'') and (order_date2='0000-00-00')) or ((order_com3<>'') and (order_date3='0000-00-00'))) ";
								
				$whereattached=" where  NOT (
				((main_draw = '' OR main_draw = '0000-00-00') AND bon_su > '0')
				OR
				((lc_draw = '' OR lc_draw = '0000-00-00') AND lc_su > '0' AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))
				OR
				((etc_draw = '' OR etc_draw = '0000-00-00') AND etc_su > '0')
				) and ((date(workday)>=date(now())) or date(workday)='0000-00-00' ) and (((order_com1<>'') and (order_date1='0000-00-00')) or  ((order_com2<>'') and (order_date2='0000-00-00')) or ((order_com3<>'') and (order_date3='0000-00-00')))";				
		// print $attached;
		}			
		
if ($check=='12' )  // 출고완료 체크 그리고 미청구된 것만 보기
		{
				$attached=" and (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00')))    ";
				$whereattached=" where workday!='' and demand='' ";
				
                // 정렬이 명시적으로 설정되지 않은 경우에만 기본 정렬 적용
                if ($sortof == "num" && $cursort == "desc") {
                    $orderby="order by workday desc ";
                }
				
		}		
if ($check=='0' || $check==0)
	$whereattached = '';
		
// 완료일 기준 또는 접수일 기준
if ($reception_filter == '1') {
    $SettingDate = " orderday "; // 접수일 기준
} else {
    $SettingDate = " orderday "; // 완료일 기준 (기본값)
}

$Andis_deleted =  " AND is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;
$Whereis_deleted =  " Where is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;	 
	 
$common= $SettingDate . " between date('$fromdate') and date('$Transtodate') ";
		
$andPhrase= " and " . $common  . $orderby ;
$wherePhrase= " where " . $common  . $orderby ;

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);  

if($search==""){
	               if($whereattached!=='')
						$sql="select * from mirae8440.ceiling " . $whereattached . $andPhrase; 					                 
					else
						$sql="select * from mirae8440.ceiling " . $wherePhrase ;					                 
			       }
			 elseif($search!="" && $find!="all")
			    {
         			$sql="select * from mirae8440.ceiling where ($find like '%$search%') " . $attached . $andPhrase;         			
                 }     				 
             elseif($search!="" && $find=="all") { // 필드별 
	              if($check!='1' && $check!='2') {		 
					  $sql ="select * from mirae8440.ceiling where ((replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )   or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (type like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize like '%$search%' ) or (memo like '%$search%' ) or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  or (searchtag like '%$search%' )   or (boxwrap like '%$search%' )  or (designer like '%$search%' )  )  " . $attached . $andPhrase; 					                       
				  }				  
				 if($check=='1') {			  
						  $sql ="select * from mirae8440.ceiling where ( (replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )  or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (type like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize like '%$search%' ) or (memo like '%$search%' )   or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  or (searchtag like '%$search%' )  or (boxwrap like '%$search%' )  or (designer like '%$search%' )  ) "  . $attached . $andPhrase;						  
					  }		
				 // 제작 완료인데 검색하는 조건
				 if($check=='2') {			  
						  $sql ="select * from mirae8440.ceiling where ( (replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )  or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (type like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize like '%$search%' ) or (memo like '%$search%' )   or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  or (searchtag like '%$search%' )  or (boxwrap like '%$search%' )  or (designer like '%$search%' )  ) "  . $attached . $andPhrase;
					  }				  
		   
			        }    
// print $sql;  
$current_condition = $check;
// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    		
			 
 ?>

<form id="board_form" name="board_form" method="post" action="list.php?mode=search">  
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 		
	<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
	<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
	<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
	<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
	<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
	<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
	<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
	<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 		
	<input type="hidden" id="list" name="list" value="<?=$list?>" >
	<input type="hidden" id="reception_filter" name="reception_filter" value="<?=$reception_filter?>" > 		

<div class="container-fluid">  
	<div class="card mb-2">  
	<div class="card-body">  	 
	<div class="d-flex justify-content-center align-items-center my-1">
		<div class="w-100" style="max-width: 1000px;">
			<div class="shadow-lg rounded-4 d-flex align-items-center px-2 py-1" style="background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%); min-height:70px; border: 2px solid #0369a1;">
				<i class="bi bi-megaphone-fill text-white me-3" style="font-size:2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
				<div class="flex-grow-1">
					<div class="text-white fw-bold mb-1" style="font-size:1.2rem; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
						2025년 8월부터 
						<span class="badge bg-white text-cyan px-3 py-2 mx-2" style="font-size:1.1rem; font-weight:700; box-shadow: 0 2px 4px rgba(0,0,0,0.2); color: #0369a1 !important;">037</span>
						<span class="badge bg-white text-cyan px-3 py-2 mx-2" style="font-size:1.1rem; font-weight:700; box-shadow: 0 2px 4px rgba(0,0,0,0.2); color: #0369a1 !important;">038</span>
						자체 생산!
					</div>
					<div class="text-white-50" style="font-size:1rem;">
						코드는 <span class="fw-bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5); color: #e0f2fe !important;">N037, N038</span>로 입력해 주세요.
						<small class="text-white-75 ms-2" style="font-size:0.9em;">※ 기존 청디자인 모델과 구분하기 위함입니다.</small>
					</div>
				</div>
				<img src="../img/notice-ceiling.svg" alt="Notice" class="ms-3 d-none d-md-block" style="height:40px; filter: brightness(0) invert(1);">
			</div>
		</div>
	</div>
	<div class="d-flex  p-1 m-1 mt-1 justify-content-center align-items-center "> 		
		  <a href="list.php">   <h5>  <?=$title_message?> </h5> </a>	 &nbsp;&nbsp;&nbsp;	&nbsp;	  	
		<!-- 화면 리로드 -->
		<button type="button" class="btn btn-dark btn-sm me-2" onclick="window.location.reload();">
			<i class="bi bi-arrow-clockwise"></i> 
		</button>
	   <span id="showalign" class="btn btn-dark btn-sm me-2" > <i class="bi bi-grid-3x3"></i> 정렬 </span>	
		<div id="showalignframe" class="card">
			<div class="card-header text-center " style="padding:2px;">
				화면정렬
			</div>					
				<div class="card-body">				
					<?php
					function printCheckbox($id, $value, $label, $checkedValue) {
						$isChecked = ($value == $checkedValue) ? "checked" : "";                                    
						echo "<input type='checkbox' class='search-condition' $isChecked id=$id value='$value'>&nbsp; <span class='badge bg-dark' style='font-size:13px;'> $label </span>  &nbsp;&nbsp;";
					}                                                        

					printCheckbox('all', '0', '전체', $current_condition);                                
					printCheckbox('without', '1', '미출고', $current_condition);
					printCheckbox('notdesigned', '4', '미설계', $current_condition);
					printCheckbox('notordered', '5', '(설계완료) 미발주 부품', $current_condition);
					printCheckbox('outsourcingcheck', '6', ' 외주가공', $current_condition);
					printCheckbox('output_complete', '3', '출고완료', $current_condition);
					printCheckbox('ready_to_ship', '2', '제작완료 출고대기', $current_condition);
					printCheckbox('not_claimed', '12', '출고완료 미청구', $current_condition);
					?>
				   
				</div>
			</div>				
	   <span id="showextract" class="btn btn-dark btn-sm me-2" > <i class="bi bi-building-add"></i> 부가기능 </span>
		<div id="showextractframe" class="card">
			<div class="card-header text-center " style="padding:2px;">
				부가기능
			</div>					
				<div class="card-body">
					<button type="button" id="reportBtn" class="btn btn-dark btn-sm"> <i class="bi bi-bar-chart"></i> 공정 작업시간 </button>
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('../mceiling/list.php','모바일현장용',1900,900);"> 모바일(현장) </button>
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('call_csv.php','CSV 파일추출',1600,500);"> 엑셀CSV</button>  
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('calcarsize.php','인승 산출 계산기',1860,800);"> 인승 산출 </button>					
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('batch.php','납기 일괄',1800,900);">  납기일괄 </button>  
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('delivery_fee.php','배송비추출',1500,800);" > 배송비 </button>
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('batchDB.php','청구 일괄',1800,900);"> 청구일괄 </button>
					<span id="denkriModel" class="btn btn-info btn-sm ">덴크리모델</span>
					<div id="customTooltip" class="custom-tooltip">
							(덴크리 외주 모델 : 011,012,013D,025,017,014)
					</div> 	
					<button type="button" id="showHolepunching" class="btn btn-primary btn-sm " > 홀타공도 </button> 
					<button type="button" id="catalog" class="btn btn-primary btn-sm " > 천장 카다로그 </button> 
						
				</div>				
			</div>					
													
			<button type="button" class="btn btn-primary btn-sm me-2" id="plan_cutandbending">
				<i class="bi bi-calendar3"></i> 생산공정표
			</button>
			<!-- <button type="button" class="btn btn-dark btn-sm me-2" onclick="window.open('plan_making.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','납품일정 List DB','left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900');">  <ion-icon name="calendar-outline"></ion-icon> 납품예정 </button>    	 -->
			<button type="button" class="btn btn-secondary btn-sm me-2" onclick="showNotDesignedList();">
				<i class="bi bi-list-task"></i> 전체 미설계 리스트
			</button>
		
			<!-- D- 날짜 선택 15일부터 25일까지 -->
			<select id="dDaySelect" class="form-select w-auto mx-1" style="font-size:1em; height:30px;" >
				<option value="15">D-15</option>
				<option value="16">D-16</option>
				<option value="17">D-17</option>
				<option value="18">D-18</option>
				<option value="19">D-19</option>
				<option value="20">D-20</option>
				<option value="21">D-21</option>
				<option value="22">D-22</option>
				<option value="23">D-23</option>
				<option value="24">D-24</option>
				<option value="25">D-25</option>
			</select>			
			<button type="button" class="btn btn-danger btn-sm me-2">   <span id="deadline_laser" > </span>  	</button>  			  
			<button type="button" class="btn btn-warning btn-sm me-2">  <span id="notdrawing" > </span>  	</button>  			  
				
		</div>	
	 <div class="row " >  				
		<div class="col-sm-8">
		  <div id="display_list" class="card justify-content-center align-items-center">										
			  <table class="table table-hover">
				<tbody>
				  <tr>
					<td >				 
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="laserBadge" class="text-danger fs-6"> laser 미가공 List</span>                                                          
					  </div>			 
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="todolist" class="form-control">Todo List</span>                                                          
					  </div>
					</td>	
				  </tr>
				</tbody>
			  </table>
		  </div>
		</div>
		<div class="col-sm-4">
		  <div id="display_list_draw" class="card justify-content-center align-items-center">										
			  <table class="table table-hover">
				<tbody>
				  <tr>
					<td >	
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="drawBadge" class="text-primary fs-6"> 미설계 List</span>                                                          
					  </div>	
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="todolist_draw" class="form-control"></span>                                                          
					  </div>
					</td>	
				  </tr>
				</tbody>
			  </table>
		  </div>
		</div>

	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center"> 	  
			총 <?= $total_row ?> 건 &nbsp;		&nbsp;
			<!-- 기간부터 검색까지 연결 묶음 start -->
				<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp;                    
                <input type="checkbox" id="receptionDateFilter" class="form-check-input me-1" <?= (isset($_GET['reception_filter']) && $_GET['reception_filter'] == '1') || (isset($_POST['reception_filter']) && $_POST['reception_filter'] == '1') ? 'checked' : '' ?>>
                <label for="receptionDateFilter" class="mx-2 text-muted" style="cursor: pointer;"> 접수일 기준 </label>
			   <input type="date" id="fromdate" name="fromdate"   class="form-control p-1"   style="width:100px;" value="<?=$fromdate?>" >  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate"  class="form-control p-1"   style="width:100px;" value="<?=$todate?>" >  &nbsp;     </span> 
			   &nbsp;&nbsp;		 
				
		<select id="find" name="find" class="form-select w-auto mx-1" style="font-size:1em; height:30px;" >
			<?php
				$options = array(
					'all' => '전체',
					'workplacename' => '현장명',
					'firstord' => '원청',
					'secondord' => '발주처',
					'type' => '타입'
				);
				foreach ($options as $value => $label) {
					$selected = ($find == $value) ? 'selected' : '';
					echo "<option value='$value' $selected>$label</option>";
				}
			?>
		</select>	
		<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control" style="width:150px;" > &nbsp;			
				<button class="btnClear"></button>
		</div>				
		<div id="autocomplete-list">				
		</div>	
		  &nbsp;
		  <button id="searchBtn" type="button" class="btn btn-dark  btn-sm" > <i class="bi bi-search"></i> 검색 </button> 		  
		  &nbsp;&nbsp;&nbsp;		    

				 <button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	     
				 <button  type="button" id="rawmaterialBtn"  class="btn btn-dark btn-sm" > <i class="bi bi-list"></i> 재고 </button> &nbsp;	
         </div> 	 
   </div> <!--card-body-->
   </div> <!--card -->   
</div> <!--card -->   
<div class="container-fluid">   
<!-- Column Visibility Controls -->
<div class="row">
    <!-- 컬럼 표시 설정 카드 (전체 너비) --> 
    <div class="col-12"> 
        <div class="row">
            <div class="col-md-4">
                <div class="card column-settings-card">
                    <div class="card-header" style="cursor: pointer;" id="column-settings-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">🔧 컬럼 표시 설정</h6>
                            <i class="bi bi-chevron-down" id="column-settings-icon"></i>
                        </div>
                    </div>
                    <div class="card-body collapse" id="column-settings-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="num" id="col-num" checked>
                                    <label class="form-check-label" for="col-num">번호</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="outsourcing" id="col-outsourcing" checked>
                                    <label class="form-check-label" for="col-outsourcing">외주</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="orderday" id="col-orderday" checked>
                                    <label class="form-check-label" for="col-orderday">접수</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="main_draw" id="col-main_draw" checked>
                                    <label class="form-check-label" for="col-main_draw">본설계</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="lc_draw" id="col-lc_draw" checked>
                                    <label class="form-check-label" for="col-lc_draw">L/C설계</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="etc_draw" id="col-etc_draw" checked>
                                    <label class="form-check-label" for="col-etc_draw">기타설계</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="mainassembly" id="col-mainassembly" checked>
                                    <label class="form-check-label" for="col-mainassembly">본제작</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="lcassembly" id="col-lcassembly" checked>
                                    <label class="form-check-label" for="col-lcassembly">L/C제작</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="etcassembly" id="col-etcassembly" checked>
                                    <label class="form-check-label" for="col-etcassembly">기타제작</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="deadline" id="col-deadline" checked>
                                    <label class="form-check-label" for="col-deadline">납기</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="workday" id="col-workday" checked>
                                    <label class="form-check-label" for="col-workday">출고</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="photo" id="col-photo" checked>
                                    <label class="form-check-label" for="col-photo">사진</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="demand" id="col-demand" checked>
                                    <label class="form-check-label" for="col-demand">청구</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="workplacename" id="col-workplacename" checked>
                                    <label class="form-check-label" for="col-workplacename">현장명</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="secondord" id="col-secondord" checked>
                                    <label class="form-check-label" for="col-secondord">발주처</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="type" id="col-type" checked>
                                    <label class="form-check-label" for="col-type">타입</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="inseung" id="col-inseung" checked>
                                    <label class="form-check-label" for="col-inseung">인승</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="car_insize" id="col-car_insize" checked>
                                    <label class="form-check-label" for="col-car_insize">inside</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="bon_su" id="col-bon_su" checked>
                                    <label class="form-check-label" for="col-bon_su">본</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="lc_su" id="col-lc_su" checked>
                                    <label class="form-check-label" for="col-lc_su">L/C</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="etc_su" id="col-etc_su" checked>
                                    <label class="form-check-label" for="col-etc_su">기타</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="air_su" id="col-air_su" checked>
                                    <label class="form-check-label" for="col-air_su">공청</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="order_com1" id="col-order_com1" checked>
                                    <label class="form-check-label" for="col-order_com1">납품1</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="order_date1" id="col-order_date1" checked>
                                    <label class="form-check-label" for="col-order_date1">주문1</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="order_com2" id="col-order_com2" checked>
                                    <label class="form-check-label" for="col-order_com2">납품2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="order_date2" id="col-order_date2" checked>
                                    <label class="form-check-label" for="col-order_date2">주문2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="order_com3" id="col-order_com3" checked>
                                    <label class="form-check-label" for="col-order_com3">납품3</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="order_date3" id="col-order_date3" checked>
                                    <label class="form-check-label" for="col-order_date3">주문3</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" value="memo" id="col-memo" checked>
                                    <label class="form-check-label" for="col-memo">비고</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-primary btn-sm" id="select-all-columns">전체 선택</button>
                            <button type="button" class="btn btn-secondary btn-sm" id="deselect-all-columns">전체 해제</button>
                            <button type="button" class="btn btn-success btn-sm" id="save-column-settings">수동 저장</button>
                            <small class="text-muted ms-2">* 체크박스 변경 시 자동 저장됩니다</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 모바일/데스크톱 뷰 전환 버튼 -->
<div class="d-flex justify-content-center mb-3 d-md-none">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary btn-sm" id="tableViewBtn">
            <i class="bi bi-table"></i> 테이블
        </button>
        <button type="button" class="btn btn-primary btn-sm" id="cardViewBtn">
            <i class="bi bi-card-list"></i> 카드
        </button>
    </div>
</div>

<!-- 기존 테이블 뷰 -->
<div id="table-view" class="table-view">
    <?php if(!$chkMobile) { ?>
    <div class="d-flex justify-content-center align-items-center mb-2">
        <div id="tabulator-table"></div>
    </div>
    <?php } else { ?>
    <div class="table-responsive">
        <div id="tabulator-table"></div>
    </div>
    <?php } ?>
</div>

<!-- 모바일 카드 뷰 -->
<div id="card-view" class="mobile-card-view d-md-none" style="display: none;">
    <div id="mobile-cards-container">
        <!-- 동적으로 생성될 카드들 -->
    </div>

    <!-- 모바일 페이지네이션 -->
    <div class="d-flex justify-content-center mt-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="prevPageBtn">
                <i class="bi bi-chevron-left"></i> 이전
            </button>
            <span class="btn btn-outline-secondary btn-sm" id="pageInfo">1 / 1</span>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="nextPageBtn">
                다음 <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<div style="display: none;">
    <table id="data-source">
        <tbody>
		<?php  		  
			$start_num=$total_row;   
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			  
			 // var_dump($row);			  
			  include '_rowDB.php';
			  
			// 첨부파일이 있는경우 '(비규격)' 앞에 문구 넣어주는 루틴임
			for($i=0;$i<count($attach_arr);$i++)
	            if($attach_arr[$i] == $num)
					  $workplacename = '(비규격)' .  $workplacename;		  
			  
			  $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

		      $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lc_draw=trans_date($lc_draw);
		      $lclaser_date=trans_date($lclaser_date);
		      $lcbending_date=trans_date($lcbending_date);
		      $lclwelding_date=trans_date($lclwelding_date);
		      $lcwelding_date=trans_date($lcwelding_date);
		      $lcassembly_date=trans_date($lcassembly_date);
		      $main_draw=trans_date($main_draw);			
		      $eunsung_make_date=trans_date($eunsung_make_date);			
		      $eunsung_laser_date=trans_date($eunsung_laser_date);			
		      $mainbending_date=trans_date($mainbending_date);			
		      $mainwelding_date=trans_date($mainwelding_date);			
		      $mainpainting_date=trans_date($mainpainting_date);			
		      $mainassembly_date=trans_date($mainassembly_date);										

		      $order_date1=trans_date($order_date1);					   
		      $order_date2=trans_date($order_date2);					   
		      $order_date3=trans_date($order_date3);					   
		      $order_date4=trans_date($order_date4);					   
		      $order_input_date1=trans_date($order_input_date1);					   
		      $order_input_date2=trans_date($order_input_date2);					   
		      $order_input_date3=trans_date($order_input_date3);					   
		      $order_input_date4=trans_date($order_input_date4);				  
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
			  
			$main_draw_arr="";			  
			  if(substr($main_draw,0,2)=="20")  $main_draw_arr= iconv_substr($main_draw,5,5,"utf-8");		    
			     elseif($bon_su<1) $main_draw_arr= "X";		    
   
			$lc_draw_arr="";			  
			  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr= iconv_substr($lc_draw,5,5,"utf-8") ;
			     elseif($lc_su<1) $lc_draw_arr = "X";	
			  if($type=='011' || $type=='012' || $type=='013D'|| $type=='025'|| $type=='017'|| $type=='014' || $type=='037'  || $type=='038' )
			                         $lc_draw_arr = "X";	
			$etc_draw_arr="";			  
			  if(substr($etc_draw,0,2)=="20")  $etc_draw_arr= iconv_substr($etc_draw,5,5,"utf-8") ;
			     elseif($etc_su<1) $etc_draw_arr = "X";				  	 

			  $mainassembly_arr="";			  
			  if(substr($mainassembly_date,0,2)=="20")  
				      $mainassembly_arr= iconv_substr($mainassembly_date,5,5,"utf-8");		    
			     elseif($bon_su<1) 
				     $mainassembly_arr= "X";		    
   
			  $lcassembly_arr="";			  
			  if(substr($lcassembly_date,0,2)=="20")  
				  $lcassembly_arr= iconv_substr($lcassembly_date,5,5,"utf-8") ;
			     elseif($lc_su<1  || $type=='011' || $type=='012' ||  $type=='013D' || $type=='025' || $type=='017' || $type=='014'  || $type=='037'  || $type=='038' )
    				 $lcassembly_arr = "X";	
			  $etcassembly_arr="";			  
			  if(substr($etcassembly_date,0,2)=="20")  
				  $etcassembly_arr= iconv_substr($etcassembly_date,5,5,"utf-8") ;
			     elseif($etc_su<1) $etcassembly_arr = "X";				  	 

		  $sqltmp=" select * from ".$DB.".picuploads where parentnum ='$num' and item='ceilingwrap' ";	
		  $tmpmsg = "";
			 try{  
			// 레코드 전체 sql 설정
			   $stmhtmp = $pdo->query($sqltmp);    
			   
			   while($rowtmp = $stmhtmp->fetch(PDO::FETCH_ASSOC)) {
					$tmpmsg = "등록" ;
					}		 
			   } catch (PDOException $Exception) {
				print "오류: ".$Exception->getMessage();
			}  		
				 
				 
			 $workitem="";
				 
				 if($su!="")
					    $workitem= $su . " , "; 
				 if($bon_su!="")
					    $workitem .="본 " . $bon_su . ", "; 					
				 if($lc_su!="")
					    $workitem .="L/C " . $lc_su . ", "; 											
				 if($etc_su!="")
					    $workitem .="기타 "  . $etc_su . ", "; 																	
				 if($air_su!="")
					    $workitem .="공기청정기 "  . $air_su . " "; 																							
						
				 $part="";
				 if($order_com1!="")
					    $part= $order_com1 . "," ; 
				 if($order_com2!="")
					    $part .= $order_com2 . ", " ; 						
				 if($order_com3!="")
					    $part .= $order_com3 . ", " ; 												
				 if($order_com4!="")
					    $part .= $order_com4 . ", " ; 
						
                 $deli_text="";
				 if($delivery!="" || $delipay!=0)
				 		  $deli_text = $delivery . " " . $delipay ;  
           
						   
				// 초기화
				$title = '';

				// 조건에 따라 $title에 문자열 추가
				if (!empty($part1)) {
					$title .= '중국산휀 ' . $part1 . ', ';
				}
				if (!empty($part2)) {
					$title .= '일반휀 ' . $part2 . ', ';
				}
				if (!empty($part3)) {
					$title .= '휠터펜(LH용) ' . $part3 . ', ';
				}
				if (!empty($part4)) {
					$title .= '판넬고정구(금성아크릴) ' . $part4 . ', ';
				}
				if (!empty($part5)) {
					$title .= '비상구 스위치(건흥KH-9015) ' . $part5 . ', ';
				}
				if (!empty($part6)) {
					$title .= '비상등 ' . $part6 . ', ';
				}
				if (!empty($part7)) {
					$title .= '할로겐(7W-6500K) ' . $part7 . ', ';
				}
				if (!empty($part8)) {
					$title .= 'T5(일반) 5W(300) ' . $part8 . ', ';
				}
				if (!empty($part9)) {
					$title .= 'T5(일반) 11W(600) ' . $part9 . ', ';
				}
				if (!empty($part10)) {
					$title .= 'T5(일반) 15W(900) ' . $part10 . ', ';
				}
				if (!empty($part11)) {
					$title .= 'T5(일반) 20W(1200) ' . $part11 . ', ';
				}
				if (!empty($part12)) {
					$title .= 'T5(KS) 6W(300) ' . $part12 . ', ';
				}
				if (!empty($part13)) {
					$title .= 'T5(KS) 10W(600) ' . $part13 . ', ';
				}
				if (!empty($part14)) {
					$title .= 'T5(KS) 15W(900) ' . $part14 . ', ';
				}
				if (!empty($part15)) {
					$title .= 'T5(KS) 20W(1200) ' . $part15 . ', ';
				}
				if (!empty($part16)) {
					$title .= '직관등 600mm ' . $part16 . ', ';
				}
				if (!empty($part17)) {
					$title .= '직관등 800mm ' . $part17 . ', ';
				}
				if (!empty($part18)) {
					$title .= '직관등 1000mm ' . $part18 . ', ';
				}
				if (!empty($part19)) {
					$title .= '직관등 1200mm ' . $part19 . ', ';
				}
				
	// 마지막 쉼표와 공백 제거
	$title = rtrim($title, ', ');	
	//  var_dump($part7);
	
	// JavaScript 배열에 데이터 추가
	$js_data = array(
		'tablenum' => $num,
		'tablename' => $tablename,
		'num' => $start_num,
		'outsourcing' => $outsourcing,
		'orderday' => $orderday,
		'main_draw' => $main_draw_arr,
		'lc_draw' => $lc_draw_arr,
		'etc_draw' => $etc_draw_arr,
		'mainassembly' => $mainassembly_arr,
		'lcassembly' => $lcassembly_arr,
		'etcassembly' => $etcassembly_arr,
		'deadline' => $deadline, // 전체 날짜를 저장하여 정렬에 사용
		'workday' => $workday, // 전체 날짜를 저장하여 정렬에 사용
		'photo' => $tmpmsg,
		'demand' => $demand, // 전체 날짜를 저장하여 정렬에 사용
		'workplacename' => $workplacename,
		'secondord' => $secondord,
		'type' => ($type && strlen($type) > 0) ? iconv_substr($type, 0, 5, "utf-8") : '',
		'inseung' => ($inseung && strlen($inseung) > 0) ? iconv_substr($inseung, 0, 2, "utf-8") : '',
		'car_insize' => ($car_insize && strlen($car_insize) > 0) ? iconv_substr($car_insize, 0, 9, "utf-8") : '',
		'bon_su' => $bon_su,
		'lc_su' => $lc_su,
		'etc_su' => $etc_su,
		'air_su' => $air_su,
		'order_com1' => ($order_com1 && strlen($order_com1) > 0) ? iconv_substr($order_com1, 0, 4, "utf-8") : '',
		'order_date1' => $order_date1, // 전체 날짜를 저장하여 정렬에 사용
		'order_com2' => ($order_com2 && strlen($order_com2) > 0) ? iconv_substr($order_com2, 0, 4, "utf-8") : '',
		'order_date2' => $order_date2, // 전체 날짜를 저장하여 정렬에 사용
		'order_com3' => ($order_com3 && strlen($order_com3) > 0) ? iconv_substr($order_com3, 0, 4, "utf-8") : '',
		'order_date3' => $order_date3, // 전체 날짜를 저장하여 정렬에 사용
		'memo' => ($memo && strlen($memo) > 0) ? iconv_substr($memo, 0, 8, "utf-8") : ''
	);
	
	if (!isset($table_data)) {
		$table_data = array();
	}
	$table_data[] = $js_data;						   
 ?>	 		
					
<tr data-num="<?=$num?>" data-tablename="<?=$tablename?>" onclick="clickTableRow(<?=$num?>, '<?=$tablename?>')" style="cursor: pointer;">       
    <td class="text-center" > <?php echo echo_null($start_num) ?></td>
	<td class="text-center"><span class="badge bg-success"><?=$outsourcing?></span></td>
    <td class="text-center" > <?php echo echo_null($orderday) ?></td>
    <td class="text-center"  data-order="<?= $main_draw_arr ?>" ><?php echo echo_null($main_draw_arr) ?></td>
    <td class="text-center"  data-order="<?= $lc_draw_arr ?>" ><?php echo echo_null($lc_draw_arr) ?></td>
    <td class="text-center"  data-order="<?= $etc_draw_arr ?>" ><?php echo echo_null($etc_draw_arr) ?></td>
    <td class="text-center"  data-order="<?= $mainassembly_arr ?>" ><?php echo echo_null($mainassembly_arr) ?></td>
    <td class="text-center"  data-order="<?= $lcassembly_arr ?>" ><?php echo echo_null($lcassembly_arr) ?></td>
    <td class="text-center"  data-order="<?= $etcassembly_arr ?>" ><?php echo echo_null($etcassembly_arr) ?></td>
    <td class="text-center"  data-order="<?= $deadline ?>" ><?php echo echo_null(iconv_substr($deadline, 5, 5, "utf-8")) ?></td>
    <td class="text-center"  data-order="<?= $workday ?>" ><?php echo echo_null(iconv_substr($workday, 5, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null($tmpmsg) ?></td>
    <td class="text-center"  data-order="<?= $demand ?>" ><?php echo echo_null(iconv_substr($demand, 5, 5, "utf-8")) ?></td>
    <td class="text-start "><?php echo echo_null($workplacename) ?></td>
    <td class="text-center"><?php echo echo_null($secondord) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($type, 0, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($inseung, 0, 2, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($car_insize, 0, 9, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null($bon_su) ?></td>
    <td class="text-center"><?php echo echo_null($lc_su) ?></td>
    <td class="text-center"><?php echo echo_null($etc_su) ?></td>
    <td class="text-center"><?php echo echo_null($air_su) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($order_com1, 0, 4, "utf-8")) ?></td>
    <td class="text-center" data-order="<?= $order_date1 ?>" ><?php echo echo_null(iconv_substr($order_date1, 5, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($order_com2, 0, 4, "utf-8")) ?></td>
    <td class="text-center" data-order="<?= $order_date2 ?>" ><?php echo echo_null(iconv_substr($order_date2, 5, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($order_com3, 0, 4, "utf-8")) ?></td>
    <td class="text-center" data-order="<?= $order_date3 ?>" ><?php echo echo_null(iconv_substr($order_date3, 5, 5, "utf-8")) ?></td>
	<td class="text-start "><?php echo echo_null(iconv_substr($memo, 0, 8, "utf-8")) ?></td>        
  </tr>
	<?php	
	  $start_num--;
		 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }    
 ?>
     <!-- Table body 부분은 아래에 추가 -->
    </tbody>  
    </table>  
</div>
</div>

<script>
// PHP에서 전달된 테이블 데이터
var phpTableData = <?php echo json_encode(isset($table_data) ? $table_data : array()); ?>;
console.log('PHP Table Data:', phpTableData);
</script>
      
   </div> <!--container-->
</form>	
	<div class="container-fluid mt-3 mb-3">
		<? include '../footer_sub.php'; ?>
	</div>
</body>
</html>
<script> 

var table; // Tabulator 인스턴스 전역 변수
var ceilingpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 직접 TR 클릭 함수
function clickTableRow(num, tablename) {
    console.log('TR clicked directly - num:', num, 'tablename:', tablename);
    if (num) {
        redirectToView(num, tablename);
    } else {
        console.error('TR 클릭에서 num 값이 없습니다');
        alert('데이터 오류: 레코드 번호를 찾을 수 없습니다.');
    }
}

// Tabulator 행에 클릭 이벤트 연결 (백업 방법 - 현재는 rowClick 이벤트 사용 중)
function attachRowClickEvents() {
    console.log('🔗 attachRowClickEvents called - using built-in rowClick event instead');
    
    // 현재는 Tabulator의 rowClick 이벤트를 직접 사용하므로 
    // 이 함수는 백업/대체 용도로만 유지
    var rowCount = $('#tabulator-table .tabulator-row').length;
    console.log('📊 Available rows for backup event binding:', rowCount);
}

$(document).ready(function() {			
    // PHP에서 전달받은 데이터 사용
    var tableData = phpTableData || [];
    console.log('🚀 Initializing Tabulator with data count:', tableData.length);
    console.log('📋 Sample data structure:', tableData[0] || 'No data available');
    
    // 데이터 검증
    if (tableData.length === 0) {
        console.warn('⚠️ No table data found - check PHP data generation');
    } else {
        var firstItem = tableData[0];
        if (!firstItem.tablenum) {
            console.error('❌ tablenum field missing in data structure:', firstItem);
        }
    }
    
    // 🔥💪 강력한 Tabulator 컬럼 정의 (검색 필터 포함) 💪🔥
    var columns = [
        {
            title: "번호", 
            field: "num", 
            width: 80, 
            hozAlign: "center", 
            sorter: "number"
        },
        {
            title: "외주", 
            field: "outsourcing", 
            width: 80, 
            hozAlign: "center", 
            formatter: function(cell) {
                var value = cell.getValue();
                return value ? '<span class="badge bg-success">' + value + '</span>' : '';
            },
        },
        {
            title: "접수", 
            field: "orderday", 
            width: 120, 
            hozAlign: "center",
        },
        {
            title: "본설계", 
            field: "main_draw", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "L/C설계", 
            field: "lc_draw", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "기타설계", 
            field: "etc_draw", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "미설계 상태", 
            field: "not_designed_status", 
            width: 150, 
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var rowData = cell.getRow().getData();
                return generateNotDesignedStatus(rowData);
            },
        },
        {
            title: "본제작", 
            field: "mainassembly", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "L/C제작", 
            field: "lcassembly", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "기타제작", 
            field: "etcassembly", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "납기", 
            field: "deadline", 
            width: 80, 
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value && value.length > 5 && value.indexOf('0000-00-00') === -1) {
                    return value.substring(5, 10); // YYYY-MM-DD에서 MM-DD만 표시
                }
                return value || '';
            },
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 전체 날짜로 정렬 (YYYY-MM-DD 형식)
                if (!a || a === '0000-00-00') a = '';
                if (!b || b === '0000-00-00') b = '';
                if (a === b) return 0;
                if (a === '') return 1;
                if (b === '') return -1;
                return a.localeCompare(b);
            }
        },
        {
            title: "출고", 
            field: "workday", 
            width: 80, 
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value && value.length > 5 && value.indexOf('0000-00-00') === -1) {
                    return value.substring(5, 10); // YYYY-MM-DD에서 MM-DD만 표시
                }
                return value || '';
            },
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 전체 날짜로 정렬 (YYYY-MM-DD 형식)
                if (!a || a === '0000-00-00') a = '';
                if (!b || b === '0000-00-00') b = '';
                if (a === b) return 0;
                if (a === '') return 1;
                if (b === '') return -1;
                return a.localeCompare(b);
            }
        },
        {
            title: "사진", 
            field: "photo", 
            width: 60, 
            hozAlign: "center",
        },
        {
            title: "청구", 
            field: "demand", 
            width: 80, 
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value && value.length > 5 && value.indexOf('0000-00-00') === -1) {
                    return value.substring(5, 10); // YYYY-MM-DD에서 MM-DD만 표시
                }
                return value || '';
            },
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 전체 날짜로 정렬 (YYYY-MM-DD 형식)
                if (!a || a === '0000-00-00') a = '';
                if (!b || b === '0000-00-00') b = '';
                if (a === b) return 0;
                if (a === '') return 1;
                if (b === '') return -1;
                return a.localeCompare(b);
            }
        },
        {
            title: "현장명", 
            field: "workplacename", 
            width: 200, 
            hozAlign: "left",
        },
        {
            title: "발주처", 
            field: "secondord", 
            width: 120, 
            hozAlign: "center",
        },
        {
            title: "타입", 
            field: "type", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "인승", 
            field: "inseung", 
            width: 60, 
            hozAlign: "center",
        },
        {
            title: "inside", 
            field: "car_insize", 
            width: 100, 
            hozAlign: "center",
        },
        {
            title: "본", 
            field: "bon_su", 
            width: 60, 
            hozAlign: "center",
        },
        {
            title: "L/C", 
            field: "lc_su", 
            width: 60, 
            hozAlign: "center",
        },
        {
            title: "기타", 
            field: "etc_su", 
            width: 60, 
            hozAlign: "center",
        },
        {
            title: "공청", 
            field: "air_su", 
            width: 60, 
            hozAlign: "center",
        },
        {
            title: "납품1", 
            field: "order_com1", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "주문1", 
            field: "order_date1", 
            width: 80, 
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value && value.length > 5 && value.indexOf('0000-00-00') === -1) {
                    return value.substring(5, 10); // YYYY-MM-DD에서 MM-DD만 표시
                }
                return value || '';
            },
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 전체 날짜로 정렬 (YYYY-MM-DD 형식)
                if (!a || a === '0000-00-00') a = '';
                if (!b || b === '0000-00-00') b = '';
                if (a === b) return 0;
                if (a === '') return 1;
                if (b === '') return -1;
                return a.localeCompare(b);
            }
        },
        {
            title: "납품2", 
            field: "order_com2", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "주문2", 
            field: "order_date2", 
            width: 80, 
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value && value.length > 5 && value.indexOf('0000-00-00') === -1) {
                    return value.substring(5, 10); // YYYY-MM-DD에서 MM-DD만 표시
                }
                return value || '';
            },
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 전체 날짜로 정렬 (YYYY-MM-DD 형식)
                if (!a || a === '0000-00-00') a = '';
                if (!b || b === '0000-00-00') b = '';
                if (a === b) return 0;
                if (a === '') return 1;
                if (b === '') return -1;
                return a.localeCompare(b);
            }
        },
        {
            title: "납품3", 
            field: "order_com3", 
            width: 80, 
            hozAlign: "center",
        },
        {
            title: "주문3", 
            field: "order_date3", 
            width: 80, 
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value && value.length > 5 && value.indexOf('0000-00-00') === -1) {
                    return value.substring(5, 10); // YYYY-MM-DD에서 MM-DD만 표시
                }
                return value || '';
            },
            sorter: function(a, b, aRow, bRow, column, dir, sorterParams) {
                // 전체 날짜로 정렬 (YYYY-MM-DD 형식)
                if (!a || a === '0000-00-00') a = '';
                if (!b || b === '0000-00-00') b = '';
                if (a === b) return 0;
                if (a === '') return 1;
                if (b === '') return -1;
                return a.localeCompare(b);
            }
        },
        {
            title: "비고", 
            field: "memo", 
            width: 150, 
            hozAlign: "left",
        }
    ];
    
    // Tabulator 초기화
    table = new Tabulator("#tabulator-table", {
        data: tableData,
        columns: columns,
        layout: "fitColumns",
        responsiveLayout: false, // 반응형 레이아웃 비활성화하여 모든 컬럼 표시
        // height 제거 - 페이지네이션으로 행 수 제한, 세로 스크롤 불필요
        tooltips: true,
        addRowPos: "top",
        history: true,
        pagination: "local",
        paginationSize: 50,
        paginationSizeSelector: [50, 100, 200, 500, 1000],
        movableColumns: true,
        resizableRows: true,
        selectable: false, // 행 선택 비활성화 (클릭 이벤트와 충돌 방지)
        initialSort: [
            {column: "num", dir: "desc"}
        ],
        // 직접적인 행 클릭 이벤트 처리
        rowClick: function(e, row) {
            // alert('행 클릭됨!'); // 즉시 확인용
            console.log('🎯 === TABULATOR ROW CLICK EVENT FIRED ===');
            
            var rowData = row.getData();
            console.log('📋 Full row data:', rowData);
            
            var num = rowData.tablenum;
            var tablename = rowData.tablename || 'ceiling';
            
            console.log('🔢 Extracted num:', num);
            console.log('🏷️ Extracted tablename:', tablename);
            
            if (num) {
                console.log('✅ Calling redirectToView...');
                // 직접 window.open으로 테스트
                var url = "view.php?num=" + num + "&tablename=" + tablename;
                window.open(url, '_blank', 'width=1850,height=900,scrollbars=yes,resizable=yes');
            } else {
                console.error('❌ tablenum 값이 없습니다');
                alert('데이터 오류: 레코드 번호를 찾을 수 없습니다.');
            }
        },
        
        // 셀 클릭 이벤트도 추가 (백업)
        cellClick: function(e, cell) {
            console.log('🔥 CELL CLICK EVENT FIRED!');
            // alert('셀 클릭됨!');
            
            var row = cell.getRow();
            var rowData = row.getData();
            var num = rowData.tablenum;
            var tablename = rowData.tablename || 'ceiling';
            
            if (num) {
                var url = "view.php?num=" + num + "&tablename=" + tablename;
                window.open(url, '_blank', 'width=1850,height=900,scrollbars=yes,resizable=yes');
            }
        },
        locale: "ko-kr",
        langs: {
            "ko-kr": {
                "pagination": {
                    "page_size": "페이지 크기",
                    "first": "처음",
                    "first_title": "첫 페이지",
                    "last": "마지막",
                    "last_title": "마지막 페이지",
                    "prev": "이전",
                    "prev_title": "이전 페이지",
                    "next": "다음",
                    "next_title": "다음 페이지"
                }
            }
        }
    });

    // 테이블 초기화 완료 후 설정 적용
    table.on("tableBuilt", function() {
        console.log('🔧 Tabulator table built successfully');
        console.log('📊 Row count:', table.getDataCount());

        // 컬럼 가시성 설정 로드 (테이블 빌드 후 즉시 적용)
        loadColumnSettings();

        // 미설계 상태 열의 초기 표시/숨김 설정
        var isNotDesignedMode = $('#check').val() === '4';
        if (table.getColumn('not_designed_status')) {
            table.getColumn('not_designed_status').show(isNotDesignedMode);
        }
        
        var firstRow = table.getRows()[0];
        if (firstRow) {
            var firstRowData = firstRow.getData();
            console.log('🎯 First row data:', firstRowData);
            console.log('🔍 First row tablenum:', firstRowData.tablenum);
            console.log('🔍 First row tablename:', firstRowData.tablename);
        } else {
            console.log('❌ No rows found in table');
        }
        
        // 강력한 이벤트 바인딩 테스트
        console.log('🔗 Setting up STRONG event binding...');
        setTimeout(function() {
            // 전체 테이블 영역에 이벤트 위임
            $('#tabulator-table').off('click.strong').on('click.strong', '.tabulator-row', function(e) {
                // alert('강력한 클릭 이벤트 발생!');
                console.log('💪 STRONG click event fired!');
                
                var $row = $(this);
                console.log('클릭된 행:', $row);
                
                // Tabulator row 객체 찾기
                var tabulatorRow = table.getRows().find(row => row.getElement() === this);
                if (tabulatorRow) {
                    var rowData = tabulatorRow.getData();
                    console.log('💪 Strong event - Row data:', rowData);
                    
                    var num = rowData.tablenum;
                    var tablename = rowData.tablename || 'ceiling';
                    
                    console.log('💪 Strong event - num:', num, 'tablename:', tablename);
                    
                    if (num) {
                        var url = "view.php?num=" + num + "&tablename=" + tablename;
                        console.log('💪 Opening URL:', url);
                        window.open(url, '_blank', 'width=1850,height=900,scrollbars=yes,resizable=yes');
                    }
                } else {
                    console.error('💪 Tabulator row 객체를 찾을 수 없습니다');
                }
            });
            
            // 직접 행 선택해서 이벤트 바인딩도 시도
            var $rows = $('#tabulator-table .tabulator-row');
            console.log('🔢 Found', $rows.length, 'tabulator rows for direct binding');
            
            $rows.each(function(index) {
                $(this).off('click.direct').on('click.direct', function(e) {
                    // alert('직접 바인딩 클릭 #' + index);
                    console.log('🎯 Direct binding click on row', index);
                });
            });
            
            console.log('✅ Strong event delegation attached to #tabulator-table');
        }, 1000);
        
        // Tabulator 행에 직접 클릭 이벤트 연결 (기존 함수)
        attachRowClickEvents();
        
        // 컬럼 설정 패널 상태 로드
        loadPanelState();

        // 컬럼 가시성 설정은 tableBuilt 이벤트에서 로드됩니다
        
        // 필터는 각 컬럼 헤더에서 직접 사용
        
        // 페이지 번호 복원 (초기 로드 시)
        var savedPageNumber = getCookie('ceilingpageNumber');
        if (savedPageNumber) {
            table.setPage(parseInt(savedPageNumber));
        }
    });
    
    // 페이지 변경 시에도 클릭 이벤트 재연결
    table.on("pageLoaded", function() {
        attachRowClickEvents();
    });

    // 페이지 변경 이벤트 리스너
    table.on("pageLoaded", function(pageno) {
        setCookie('ceilingpageNumber', pageno, 10);
    });
    
    // 컬럼 설정 패널 토글
    $('#column-settings-header').on('click', function() {
        var body = $('#column-settings-body');
        var icon = $('#column-settings-icon');
        
        if (body.hasClass('show')) {
            body.removeClass('show');
            icon.removeClass('rotated');
            setCookie('columnPanelExpanded', 'false', 365);
        } else {
            body.addClass('show');
            icon.addClass('rotated');
            setCookie('columnPanelExpanded', 'true', 365);
        }
    });
    
    // 컬럼 토글 이벤트 (실시간 적용)
    $('.column-toggle').on('change', function() {
        var field = $(this).val();
        var isChecked = $(this).is(':checked');
        
        if (isChecked) {
            table.showColumn(field);
        } else {
            table.hideColumn(field);
        }
        
        // 실시간으로 설정 저장
        saveColumnSettings();
    });
    
    // 전체 선택 버튼
    $('#select-all-columns').on('click', function() {
        $('.column-toggle').prop('checked', true);
        $('.column-toggle').each(function() {
            var field = $(this).val();
            table.showColumn(field);
        });
        saveColumnSettings();
    });
    
    // 전체 해제 버튼
    $('#deselect-all-columns').on('click', function() {
        $('.column-toggle').prop('checked', false);
        $('.column-toggle').each(function() {
            var field = $(this).val();
            table.hideColumn(field);
        });
        saveColumnSettings();
    });
    
    // 설정 저장 버튼 (수동 저장)
    $('#save-column-settings').on('click', function() {
        saveColumnSettings();
        alert('컬럼 설정이 저장되었습니다.');
    });
});

// 컬럼 설정 저장 함수
function saveColumnSettings() {
    var settings = {};
    $('.column-toggle').each(function() {
        settings[$(this).val()] = $(this).is(':checked');
    });
    setCookie('columnSettings', JSON.stringify(settings), 365);
}

// 패널 상태 로드 함수
function loadPanelState() {
    var isExpanded = getCookie('columnPanelExpanded');
    var body = $('#column-settings-body');
    var icon = $('#column-settings-icon');
    
    if (isExpanded === 'true') {
        body.addClass('show');
        icon.addClass('rotated');
    } else if (isExpanded === 'false') {
        body.removeClass('show');
        icon.removeClass('rotated');
    } else {
        // 기본값: 접힌 상태
        body.removeClass('show');
        icon.removeClass('rotated');
        setCookie('columnPanelExpanded', 'false', 365);
    }
}

// 컬럼 설정 로드 함수
function loadColumnSettings() {
    var settings = getCookie('columnSettings');
    if (settings) {
        try {
            settings = JSON.parse(settings);
            $('.column-toggle').each(function() {
                var $checkbox = $(this);
                var field = $checkbox.val();

                if (settings.hasOwnProperty(field)) {
                    $checkbox.prop('checked', settings[field]);

                    // Tabulator 테이블에 실제 적용
                    if (table && table.getColumn) {
                        try {
                            if (settings[field]) {
                                table.showColumn(field);
                            } else {
                                table.hideColumn(field);
                            }
                        } catch (columnError) {
                            console.warn('컬럼 ' + field + ' 처리 중 오류:', columnError);
                        }
                    }
                }
            });
        } catch (e) {
            console.error('컬럼 설정 로드 실패:', e);
        }
    } else {
        // 설정이 없을 때 기본값으로 모든 컬럼 표시
        console.log('🔧 컬럼 설정 없음 - 모든 컬럼을 기본 표시');
        $('.column-toggle').each(function() {
            var $checkbox = $(this);
            var field = $checkbox.val();

            // 체크박스는 이미 HTML에서 checked 상태
            // Tabulator 테이블에서 모든 컬럼 표시 확인
            if (table && table.getColumn) {
                try {
                    table.showColumn(field);
                } catch (columnError) {
                    console.warn('기본 컬럼 표시 중 오류:', field, columnError);
                }
            }
        });

        // 기본 설정 저장 (모든 컬럼 표시)
        saveColumnSettings();
    }
}

function redirectToView(num, tablename) {
    console.log('🚀 redirectToView called with num:', num, 'tablename:', tablename);
    
    if (!num) {
        console.error('❌ num 값이 없습니다');
        return;
    }
    
    var url = "view.php?num=" + num + "&tablename=" + tablename;
    console.log('🔗 Opening popup with URL:', url);
    
    // customPopup 함수가 없는 경우 대체 방법 사용
    if (typeof customPopup === 'function') {
        console.log('📋 Using customPopup function');
        customPopup(url, '조명천장 수주내역', 1850, 900);
    } else {
        console.log('📋 Using window.open fallback');
        // 대체 팝업 방법
        window.open(url, '조명천장 수주내역', 'width=1850,height=900,scrollbars=yes,resizable=yes');
    }
}

// 테스트 함수 - 브라우저 콘솔에서 호출 가능
function testTableClick() {
    console.log('🧪 === TABLE CLICK TEST ===');
    
    if (!table) {
        console.error('❌ Table object not found');
        return;
    }
    
    var rows = table.getRows();
    console.log('📊 Total rows:', rows.length);
    
    if (rows.length > 0) {
        var firstRow = rows[0];
        var rowData = firstRow.getData();
        console.log('🎯 First row data:', rowData);
        console.log('🔢 tablenum:', rowData.tablenum);
        console.log('🏷️ tablename:', rowData.tablename);
        
        // 수동으로 첫 번째 행 클릭 시뮬레이션
        console.log('🎭 Simulating click on first row...');
        if (rowData.tablenum) {
            redirectToView(rowData.tablenum, rowData.tablename || 'ceiling');
        }
    } else {
        console.error('❌ No rows available for testing');
    }
}

// 필터 관련 함수들 제거 - 각 컬럼 헤더에서 직접 검색 사용

$(document).ready(function(){	
	$("#writeBtn").click(function(){ 		
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '조명천장 수주내역', 1850, 950); 	
	 });			 
	 
	// 자재현황 클릭시
	$("#rawmaterialBtn").click(function(){ 			
		 popupCenter('/ceiling/list_part_table.php?menu=no'  , '부자재현황보기', 1050, 950);	
	});		 
			
	// 생산공정표 클릭시
	$("#plan_cutandbending").click(function(){ 
		//  popupCenter('/ceiling/plan_cutandbending.php?menu=no'  , '가공 스케줄', 1900, 950);	
		 popupCenter('/mceiling/list.php?menu=no'  , '', 1900, 950);	
	});		 
		
});	



function SearchEnter(){
    if(event.keyCode == 13){		
		saveSearch();
    }
}

function saveSearch() {
    let searchInput = document.getElementById('search');
    let searchValue = searchInput.value;

    console.log('searchValue ' + searchValue);

    // 접수일 기준 필터 값을 폼에 설정
    let receptionFilter = document.getElementById('receptionDateFilter');
    let receptionFilterValue = receptionFilter && receptionFilter.checked ? '1' : '0';
    document.getElementById('reception_filter').value = receptionFilterValue;

    // 접수일 기준이 체크되어 있지 않을 때는 전체 기간으로 설정
    if (receptionFilterValue === '0') {
        // 전체 기간으로 설정 (매우 넓은 범위)
        let fromDate = new Date('2020-01-01');
        let toDate = new Date();

        // 날짜를 YYYY-MM-DD 형식으로 포맷
        let formatDate = function(date) {
            let year = date.getFullYear();
            let month = ('0' + (date.getMonth() + 1)).slice(-2);
            let day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        };

        $('#fromdate').val(formatDate(fromDate));
        $('#todate').val(formatDate(toDate));
    }

    if (searchValue !== "") {
        let now = new Date();
        let timestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

        let searches = getSearches();
        // 기존에 동일한 검색어가 있는 경우 제거
        searches = searches.filter(search => search.keyword !== searchValue);
        // 새로운 검색 정보 추가
        searches.unshift({ keyword: searchValue, time: timestamp });
        searches = searches.slice(0, 50);

        document.cookie = "searches=" + JSON.stringify(searches) + "; max-age=31536000";

		var ceilingpageNumber = 1;
		setCookie('ceilingpageNumber', ceilingpageNumber, 10); // 쿠키에 페이지 번호 저장
    }

    document.getElementById('board_form').submit();
}

// 검색창에 쿠키를 이용해서 저장하고 화면에 보여주는 코드 묶음
	document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const autocompleteList = document.getElementById('autocomplete-list');  

    searchInput.addEventListener('input', function() {
	const val = this.value;
	let searches = getSearches();
	let matches = searches.filter(s => {
		if (typeof s.keyword === 'string') {
			return s.keyword.toLowerCase().includes(val.toLowerCase());
		}
		return false;
	});			
	   renderAutocomplete(matches);               
    });
	 
    
    searchInput.addEventListener('focus', function() {
        let searches = getSearches();
        renderAutocomplete(searches);   

        console.log(searches);				
    });
			
});

    var isMouseOverSearch = false;
    var isMouseOverAutocomplete = false;

    document.getElementById('search').addEventListener('focus', function() {
        isMouseOverSearch = true;
        showAutocomplete();
    });

	document.getElementById('search').addEventListener('blur', function() {        
		setTimeout(function() {
			if (!isMouseOverAutocomplete) {
				hideAutocomplete();
			}
		}, 100); // Delay of 100 milliseconds
	});


    function hideAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'none';
    }

    function showAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'block';
    }


function renderAutocomplete(matches) {
    const autocompleteList = document.getElementById('autocomplete-list');    

    // Remove all .autocomplete-item elements
    const items = autocompleteList.getElementsByClassName('autocomplete-item');
    while(items.length > 0){
        items[0].parentNode.removeChild(items[0]);
    }

    matches.forEach(function(match) {
        let div = document.createElement('div');
        div.className = 'autocomplete-item';
        div.innerHTML =  '<span class="text-primary">' + match.keyword + ' </span>';
        div.addEventListener('click', function() {
            document.getElementById('search').value = match.keyword;
            autocompleteList.innerHTML = '';            
			console.log(match.keyword);
            document.getElementById('board_form').submit();    
        });
        autocompleteList.appendChild(div);
    });
}


function getSearches() {
    let cookies = document.cookie.split('; ');
    for (let cookie of cookies) {
        if (cookie.startsWith('searches=')) {
            try {
                let searches = JSON.parse(cookie.substring(9));
                // 배열이 50개 이상의 요소를 포함하는 경우 처음 50개만 반환
                if (searches.length > 50) {
                    return searches.slice(0, 50);
                }
                return searches;
            } catch (e) {
                console.error('Error parsing JSON from cookies', e);
                return []; // 오류가 발생하면 빈 배열 반환
            }
        }
    }
    return []; // 'searches' 쿠키가 없는 경우 빈 배열 반환
}

   
$(document).ready(function(){	
	$("#denkriModel").hover(function(){
		$("#customTooltip").show();
	}, function(){
		$("#customTooltip").hide();
	});

	$("#searchBtn").click(function(){ 	
			saveSearch(); 
		});		

	$("#reportBtn").click(function(){ 	
		// 작업예측
		popupCenter('workreport.php','작업예측',1600,950); 
	 
	 });		
		
	$("#showHolepunching").click(function(){ 	
		// 팝업창으로 홀타공도 띄움     
		popupCenter('showhole.php','홀타공도',1400,900); 
	 
	 });	
	 
	$("#catalog").click(function(){ 	
		// 팝업창으로 카다로그 띄움     
		popupCenter('showcatalog.php','카다로그',1400,900); 
	 
	 });		
		
	var todolist = <?php echo json_encode($todolist); ?>;
	var link = <?php echo json_encode($todolistlink); ?>;

	var htmlstr = '';

	for(i=0;i<todolist.length;i++)
	{
		if(i%2==0)
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >   <span style="background-color:#46D2D2"> ' + todolist[i] + '</span> </a>  &nbsp; ';
		else
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >    ' + todolist[i] + ' </a> &nbsp;&nbsp;';
	}

	if(todolist.length === 0) {
		// $('#deadline_laser').html('<img src="../img/medal.jpg" style="width:7%;" alt="Medal" />  [레이져가공] 없음');
		$('#todolist').hide();
		$('#deadline_laser').text(' 레이져가공 리스트 없음 ');
		// $('#deadline_laser').addClass("marquee");
	} else {
		$('#todolist').html(htmlstr);    
		$('#todolist').show();
		$('#deadline_laser').text('  Laser 미가공(' + todolist.length + '건)  ');
		 // $('#deadline_laser').removeClass("marquee"); 
	}
				
	var todolist_draw = <?php echo json_encode($todolist_draw); ?>;
	var link_darw = <?php echo json_encode($todolistlink_draw); ?>;

	var htmlstr = '';

	for(i=0;i<todolist_draw.length;i++)
	{
		if(i%2==0)
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link_darw[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >   <span style="background-color:#46D2D2"> ' + todolist_draw[i] + '</span> </a>  &nbsp; ';
		else
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link_darw[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >    ' + todolist_draw[i] + ' </a> &nbsp;&nbsp;';
	}

	if(todolist_draw.length === 0) {
		// $('#notdrawing').html('<img src="../img/medal.jpg" style="width:7%;" alt="Medal" />  [레이져가공] 없음');
		$('#todolist_draw').hide();
		$('#notdrawing').text(' 레이져가공 리스트 없음 ');
		// $('#notdrawing').addClass("marquee");
	} else {
		$('#todolist_draw').html(htmlstr);    
		$('#todolist_draw').show();
		$('#notdrawing').text('  미설계(' + todolist_draw.length + '건)  ');
		// $('#notdrawing').removeClass("marquee"); 
	}
});

$(document).ready(function() {
    $('.search-condition').change(function() {
        // 모든 체크박스의 선택을 해제합니다.
        $('.search-condition').not(this).prop('checked', false);

        // 선택된 체크박스의 값으로 `check` 필드를 업데이트합니다.
        var condition = $(this).is(":checked") ? $(this).val() : '';
        $("#check").val(condition);

        // 접수일 기준 필터 값을 폼에 설정
        var receptionFilterValue = $('#receptionDateFilter').is(':checked') ? '1' : '0';
        $('#reception_filter').val(receptionFilterValue);

        // 미설계 상태 열의 표시/숨김 업데이트
        if (typeof table !== 'undefined') {
            var isNotDesignedMode = condition === '4';
            if (table.getColumn('not_designed_status')) {
                table.getColumn('not_designed_status').show(isNotDesignedMode);
            }
            // 테이블 다시 그리기
            table.redraw(true);
        }

        // 검색 입력란을 비우고 폼을 제출합니다.
        // $("#search").val('');
        $('#board_form').submit();
    });

});

// 미설계 상태 생성 함수
function generateNotDesignedStatus(rowData) {
    // 미설계 체크가 되어있지 않으면 빈 문자열 반환
    if ($('#check').val() !== '4') {
        return '';
    }
    
    var notDesignedItems = [];
    
    // 본설계 미설계 체크
    if (!rowData.main_draw || rowData.main_draw === '' || rowData.main_draw === '0000-00-00') {
        if (rowData.bon_su && parseInt(rowData.bon_su) > 0) {
            notDesignedItems.push('본천장');
        }
    }
    
    // L/C설계 미설계 체크 (특정 타입 제외)
    if (!rowData.lc_draw || rowData.lc_draw === '' || rowData.lc_draw === '0000-00-00') {
        if (rowData.lc_su && parseInt(rowData.lc_su) > 0) {
            var type = rowData.type || '';
            // 특정 타입들은 L/C 설계가 필요하지 않음
            if (!['011', '012', '013D', '025', '017', '014', '037', '038'].includes(type)) {
                notDesignedItems.push('L/C');
            }
        }
    }
    
    // 기타설계 미설계 체크
    if (!rowData.etc_draw || rowData.etc_draw === '' || rowData.etc_draw === '0000-00-00') {
        if (rowData.etc_su && parseInt(rowData.etc_su) > 0) {
            notDesignedItems.push('기타');
        }
    }
    
    // 미설계 항목이 없으면 빈 문자열 반환
    if (notDesignedItems.length === 0) {
        return '';
    }
    
    // 미설계 항목들을 하나의 문구로 조합
    var statusText = notDesignedItems.join(' ') + ' 미설계';
    
    // 적색 글씨로 스타일링된 HTML 반환
    return '<span style="color: #dc3545; font-weight: bold; font-size: 0.9em;">' + statusText + '</span>';
}

// 정렬 완료 모달 닫기 함수
function closeSortCompleteModal() {
    $('#sortCompleteModal').fadeOut(300);
}

// 정렬 완료 모달 메시지 업데이트 함수
function updateSortCompleteModal(title, message) {
    $('#sortCompleteModal .sort-complete-message h5').text(title);
    $('#sortCompleteModal .sort-complete-message p').text(message);
}

// 미설계 리스트 보기 함수
function showNotDesignedList() {
    // 로딩 오버레이 표시
    $('#loadingOverlay').fadeIn(300);
    
    // 모든 체크박스의 선택을 해제합니다.
    $('.search-condition').prop('checked', false);
    
    // 미설계 체크박스를 체크합니다.
    $('#notdesigned').prop('checked', true);
    
    // check 필드를 미설계 값(4)으로 설정합니다.
    $("#check").val('4');
    
    // 접수일 기준 필터 값을 폼에 설정
    var receptionFilterValue = $('#receptionDateFilter').is(':checked') ? '1' : '0';
    $('#reception_filter').val(receptionFilterValue);
    
    // 납기일 오름차순 정렬을 위한 hidden 필드 추가
    if (!$('#sortof').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'sortof',
            name: 'sortof',
            value: 'deadline'
        }).appendTo('#board_form');
    } else {
        $('#sortof').val('deadline');
    }
    
    if (!$('#cursort').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'cursort',
            name: 'cursort',
            value: 'asc'
        }).appendTo('#board_form');
    } else {
        $('#cursort').val('asc');
    }
    
    // 정렬 완료 상태를 표시하기 위한 파라미터 추가
    if (!$('#sortComplete').length) {
        $('<input>').attr({
            type: 'hidden',
            id: 'sortComplete',
            name: 'sortComplete',
            value: '1'
        }).appendTo('#board_form');
    } else {
        $('#sortComplete').val('1');
    }
    
    // 약간의 지연 후 폼을 제출합니다 (로딩 UI가 보이도록)
    setTimeout(function() {
        // 정렬 완료 모달 메시지 업데이트
        updateSortCompleteModal('정렬이 완료되었습니다', '납기일 순으로 정렬되었습니다.');
        $('#board_form').submit();
    }, 500);
}
</script> 

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 페이지 로드 완료 시 로딩 오버레이 숨기기
    $('#loadingOverlay').fadeOut(300);
    
    // 정렬 완료 모달 표시 확인
    var urlParams = new URLSearchParams(window.location.search);
    var sortComplete = urlParams.get('sortComplete');
    var checkValue = $('#check').val();
    
    // 미설계 체크가 되어있거나 sortComplete 파라미터가 있으면 모달 표시
    if (sortComplete === '1' || checkValue === '4') {
      // 약간의 지연 후 정렬 완료 모달 표시
      setTimeout(function() {
        // 미설계 체크 상태에 따라 메시지 업데이트
        if (checkValue === '4') {
          updateSortCompleteModal('미설계 리스트가 표시됩니다', '납기일 순으로 정렬되어 긴급한 설계 작업을 우선적으로 확인할 수 있습니다.');
        }
        $('#sortCompleteModal').fadeIn(300);
      }, 1000);
    }
    
    // D- 날짜 선택 시 페이지 업데이트
    document.getElementById('dDaySelect').addEventListener('change', function() {
      var selectedDday = this.value;
      window.location.href = '?dDay=' + selectedDday;
    });

    // URL에서 dDay 값을 가져와서 선택 상태로 설정
    var urlParams = new URLSearchParams(window.location.search);
    var dDayParam = urlParams.get('dDay');
    if (dDayParam) {
      document.getElementById('dDaySelect').value = dDayParam;
      document.getElementById('laserBadge').innerText = 'D-' + dDayParam + ' laser 미가공 List';
      document.getElementById('drawBadge').innerText = 'D-' + dDayParam + ' 미설계 List';
    }

    // deadline_laser 클릭 이벤트 리스너 추가
    document.getElementById('deadline_laser').addEventListener('click', function() {
      var displayListElement = document.getElementById('display_list');
      if (displayListElement.style.display === 'none') {
        displayListElement.style.display = 'table-cell';
      } else {
        displayListElement.style.display = 'none';
      }
    });	       
		
    // notdrawing 클릭 이벤트 리스너 추가
    document.getElementById('notdrawing').addEventListener('click', function() {
      var displayListElement = document.getElementById('display_list_draw');
      if (displayListElement.style.display === 'none') {
        displayListElement.style.display = 'table-cell';
      } else {
        displayListElement.style.display = 'none';
      }
    });
  });
  
$(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';
   // title = '품질방침/품질목표';
   // title = '절곡 ' + title ;
   saveMenuLog(title);
});	

// 페이지 완전 로드 시 로딩 오버레이 숨기기
window.addEventListener('load', function() {
    $('#loadingOverlay').fadeOut(300);
});

// 모바일 카드 레이아웃 관련 함수들
let currentPage = 1;
let itemsPerPage = 10;
let filteredData = [];

// 뷰 전환 함수
function switchView(viewType) {
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');
    const tableViewBtn = document.getElementById('tableViewBtn');
    const cardViewBtn = document.getElementById('cardViewBtn');

    if (viewType === 'card') {
        tableView.style.display = 'none';
        cardView.style.display = 'block';
        tableViewBtn.classList.remove('btn-primary');
        tableViewBtn.classList.add('btn-outline-primary');
        cardViewBtn.classList.remove('btn-outline-primary');
        cardViewBtn.classList.add('btn-primary');
        generateMobileCards();
    } else {
        tableView.style.display = 'block';
        cardView.style.display = 'none';
        tableViewBtn.classList.remove('btn-outline-primary');
        tableViewBtn.classList.add('btn-primary');
        cardViewBtn.classList.remove('btn-primary');
        cardViewBtn.classList.add('btn-outline-primary');
    }
}

// 모바일 카드 생성 함수
function generateMobileCards() {
    if (!table || !table.getData) return;

    const data = table.getData();
    filteredData = data; // 필터링 로직 추가 가능

    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredData.slice(startIndex, endIndex);

    const container = document.getElementById('mobile-cards-container');
    container.innerHTML = '';

    pageData.forEach(item => {
        const card = createDataCard(item);
        container.appendChild(card);
    });

    updatePaginationInfo(currentPage, totalPages);
}

// 개별 카드 생성 함수
function createDataCard(data) {
    const card = document.createElement('div');
    card.className = 'data-card';
    card.onclick = () => {
        const num = data.tablenum;
        const tablename = data.tablename || 'ceiling';
        if (num) {
            const url = "view.php?num=" + num + "&tablename=" + tablename;
            window.open(url, '_blank', 'width=1850,height=900,scrollbars=yes,resizable=yes');
        }
    };

    // 상태 결정
    let statusClass = 'status-pending';
    let statusText = '진행중';
    if (data.output_yn === 'Y') {
        statusClass = 'status-complete';
        statusText = '출고완료';
    } else if (data.design_yn === 'Y') {
        statusClass = 'status-progress';
        statusText = '설계완료';
    }

    card.innerHTML = `
        <div class="data-card-header">
            <h6 class="data-card-title">${data.workplacename || '현장명 없음'}</h6>
            <span class="data-card-number">#${data.num || data.tablenum}</span>
        </div>
        <div class="data-card-body">
            <div class="data-card-item">
                <div class="data-card-label">원청</div>
                <div class="data-card-value">${data.firstord || '-'}</div>
            </div>
            <div class="data-card-item">
                <div class="data-card-label">발주처</div>
                <div class="data-card-value">${data.secondord || '-'}</div>
            </div>
            <div class="data-card-item">
                <div class="data-card-label">타입</div>
                <div class="data-card-value">${data.type || '-'}</div>
            </div>
            <div class="data-card-item">
                <div class="data-card-label">상태</div>
                <div class="data-card-value">
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </div>
            </div>
            <div class="data-card-item full-width">
                <div class="data-card-label">규격</div>
                <div class="data-card-value">${data.spec || '-'}</div>
            </div>
            <div class="data-card-item">
                <div class="data-card-label">수량</div>
                <div class="data-card-value">${data.su || '0'}</div>
            </div>
            <div class="data-card-item">
                <div class="data-card-label">납기일</div>
                <div class="data-card-value">${data.delivery_date || '-'}</div>
            </div>
        </div>
    `;

    return card;
}

// 페이지네이션 업데이트 함수
function updatePaginationInfo(current, total) {
    const pageInfo = document.getElementById('pageInfo');
    const prevBtn = document.getElementById('prevPageBtn');
    const nextBtn = document.getElementById('nextPageBtn');

    if (pageInfo) pageInfo.textContent = `${current} / ${total}`;
    if (prevBtn) prevBtn.disabled = current <= 1;
    if (nextBtn) nextBtn.disabled = current >= total;
}

// 페이지 이동 함수
function goToPage(direction) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);

    if (direction === 'prev' && currentPage > 1) {
        currentPage--;
    } else if (direction === 'next' && currentPage < totalPages) {
        currentPage++;
    }

    generateMobileCards();
}

// 이벤트 리스너 설정
$(document).ready(function() {
    // 뷰 전환 버튼 이벤트
    $('#tableViewBtn').on('click', () => switchView('table'));
    $('#cardViewBtn').on('click', () => switchView('card'));

    // 페이지네이션 버튼 이벤트
    $('#prevPageBtn').on('click', () => goToPage('prev'));
    $('#nextPageBtn').on('click', () => goToPage('next'));

    // 초기 뷰 설정 (모바일에서는 카드 뷰가 기본)
    if (window.innerWidth <= 768) {
        setTimeout(() => switchView('card'), 500);
    }

    // 화면 크기 변경 감지
    $(window).on('resize', function() {
        if (window.innerWidth > 768) {
            switchView('table');
        }
    });
});

</script> 