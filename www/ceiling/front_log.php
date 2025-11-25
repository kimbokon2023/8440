<?php
require_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;

// 권한 체크
if (!isset($_SESSION["level"]) || $level >= 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}
?>
<?php include getDocumentRoot() . '/load_header.php'; ?>
<title> 본천장/LC Front Log</title>
<!-- Dashboard CSS -->
<link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />
<!-- Tabulator CSS -->
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
<!-- Tabulator JS -->
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<style>
/* Light & Subtle Theme - Ceiling Front Log */
/* ========================================= */

body {
    background: var(--gradient-primary);
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    overflow-x: hidden;
}

.container-fluid {
    max-width: 100%;
    overflow-x: hidden;
    padding: 0.9rem;
}

/* Output Chart Container 스타일 */
.output-chart-container {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(51, 65, 85, 0.05);
  margin-bottom: 1rem;
  overflow: hidden;
  transition: all 0.2s ease;
  max-width: 100%;
  /* 화면 가운데 정렬 추가 */
  margin-left: auto;
  margin-right: auto;
  display: block;
}

.output-chart-container:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

/* 섹션 제목 스타일 개선 */
.output-chart-container h5.output-section-title,
.output-section-title {
  color: #334155;
  font-weight: 600;
  font-size: 1.1rem;
  margin: 0;
}

/* 통합 카드 내부 소제목 스타일 */
.output-chart-container h6.text-muted {
  color: #64748b !important;
  font-weight: 500;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 0.5rem;
  margin-bottom: 1rem !important;
}

/* 통합 카드 내부 열 간격 조정 */
.output-chart-container .row > [class*="col-"] {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}

/* Output Table 스타일 */
.output-table {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: none;
  width: 100%;
  border-collapse: collapse;
}

.output-table th {
  background: #f8fafc !important;
  color: var(--dashboard-text) !important;
  padding: 0.75rem !important;
  font-size: 0.85rem !important;
  font-weight: 500 !important;
  border: 1px solid #e2e8f0 !important;
  text-align: center !important;
  vertical-align: middle !important;
  position: relative;
}

.output-table td {
  padding: 0.75rem !important;
  font-size: 0.85rem !important;
  color: var(--dashboard-text) !important;
  border: 1px solid #e2e8f0 !important;
  vertical-align: middle !important;
  background: white;
}

.output-table tbody tr:hover td {
  background-color: #f1f5f9 !important;
  transition: background-color 0.2s ease;
}

/* 컴팩트 테이블 스타일 */
.output-table.table-sm th {
  padding: 0.5rem !important;
  font-size: 0.8rem !important;
}

.output-table.table-sm td {
  padding: 0.5rem !important;
  font-size: 0.8rem !important;
}

/* PC에서 테이블이 카드 body 폭을 꽉 채우도록 설정 */
@media (min-width: 992px) {
  .table-responsive {
    width: 100%;
  }

  .table-responsive .table {
    width: 100% !important;
    margin-bottom: 0;
  }
}

/* 합계 행 강조 스타일 */
.output-table .table-info td {
  background-color: #f0f9ff !important;
  font-weight: 500 !important;
}

.output-table .table-info:hover td {
  background-color: #e0f2fe !important;
}

.modern-management-card,
.glass-container {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.modern-management-card:hover,
.glass-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

.compact-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--dashboard-text);
}

.table th {
    background: #f0fbff !important;
    color: var(--dashboard-text) !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    border: 1px solid var(--dashboard-border) !important;
    padding: 0.75rem !important;
    text-align: center;
    vertical-align: middle;
}

.table td {
    font-size: 0.9rem !important;
    padding: 0.75rem !important;
    border: 1px solid var(--dashboard-border) !important;
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--dashboard-text) !important;
    vertical-align: middle;
}

.card {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.btn-primary {
    background: var(--dashboard-accent) !important;
    color: white !important;
    border: none !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2) !important;
}

.btn-primary:hover {
    background: var(--dashboard-accent-light) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(100, 116, 139, 0.3) !important;
}

.btn-dark {
    background: var(--dashboard-accent) !important;
    color: white !important;
    border: none !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2) !important;
}

.btn-dark:hover {
    background: var(--dashboard-accent-light) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(100, 116, 139, 0.3) !important;
}

.btn-secondary {
    background: var(--dashboard-secondary) !important;
    color: var(--dashboard-text) !important;
    border: 1px solid var(--dashboard-border) !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.btn-secondary:hover {
    background: #c7f0ff !important;
    color: var(--dashboard-text) !important;
    transform: translateY(-1px) !important;
}

.text-primary {
    color: var(--dashboard-text) !important;
}

.form-control {
    border: 1px solid var(--dashboard-border);
    background: white;
    color: var(--dashboard-text);
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
    outline: none;
}

.form-select {
    border: 1px solid var(--dashboard-border);
    background: white;
    color: var(--dashboard-text);
    border-radius: 6px;
}

.table-primary > th {
    background: #f0fbff !important;
    color: var(--dashboard-text) !important;
    border: 1px solid var(--dashboard-border) !important;
}

.table-hover tbody tr:hover {
    background-color: var(--dashboard-hover) !important;
    transition: background-color 0.2s ease;
}

.table tbody tr:hover td {
    background-color: var(--dashboard-hover) !important;
}

/* Tabulator 스타일 - Light & Subtle Theme */
.tabulator {
    background: white;
    border: 1px solid var(--dashboard-border);
    border-radius: 8px;
    box-shadow: var(--dashboard-shadow);
}

.tabulator .tabulator-header {
    background: #f0fbff !important;
    color: var(--dashboard-text);
    border: none;
}

.tabulator .tabulator-header .tabulator-col {
    background: transparent;
    border-right: 1px solid var(--dashboard-border);
    color: var(--dashboard-text);
    font-size: 0.9rem;
    font-weight: 600;
}

.tabulator .tabulator-header .tabulator-col .tabulator-col-content {
    padding: 0.75rem;
}

.tabulator .tabulator-row {
    background: rgba(255, 255, 255, 0.95);
    border-bottom: 1px solid var(--dashboard-border);
}

.tabulator .tabulator-row:hover {
    background: var(--dashboard-hover) !important;
    transition: background-color 0.2s ease;
}

.tabulator .tabulator-row .tabulator-cell {
    border-right: 1px solid var(--dashboard-border);
    font-size: 0.9rem;
    padding: 0.75rem;
    color: var(--dashboard-text);
}

.tabulator .tabulator-row.tabulator-selected {
    background: rgba(100, 116, 139, 0.1) !important;
}

.tabulator .tabulator-footer {
    background: var(--dashboard-secondary);
    border-top: 1px solid var(--dashboard-border);
    color: var(--dashboard-text);
}

/* PC에서 기존 버튼 숨기기 */
.pc-only-btn {
	display: none !important;
}

/* PC/모바일 공통 - 검색 아이콘 버튼 표시 */
.btn-search-icon,
#searchBtnMobile {
	display: flex !important;
}

/* PC/모바일 공통 검색창 스타일 */
.search-container {
	display: flex !important;
	flex-direction: row !important;
	flex-wrap: nowrap !important;
	align-items: center !important;
	justify-content: center !important;
	gap: 0.5rem !important;
	padding: 0.5rem !important;
	width: 100% !important;
}

.inputWrap {
	flex: 0 1 auto !important;
	min-width: 200px !important;
	max-width: 400px !important;
	position: relative !important;
	display: flex !important;
	align-items: center !important;
}

.inputWrap input {
	width: 100% !important;
	max-width: 100% !important;
	padding: 0.5rem 80px 0.5rem 0.75rem !important;
	font-size: 0.9rem !important;
	border: 2px solid #28a745 !important;
	border-radius: 0.5rem !important;
	background-color: #fff !important;
	margin: 0 !important;
	box-sizing: border-box !important;
}

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
	opacity: 0.6 !important;
	transition: opacity 0.2s ease !important;
}

.btnClear:hover {
	opacity: 1 !important;
}

.btn-search-icon {
	position: absolute !important;
	right: 8px !important;
	top: 50% !important;
	transform: translateY(-50%) !important;
	width: 40px !important;
	height: 40px !important;
	min-width: 40px !important;
	padding: 0 !important;
	border: none !important;
	background: transparent !important;
	display: flex !important;
	align-items: center !important;
	justify-content: center !important;
	z-index: 11 !important;
	cursor: pointer !important;
	border-radius: 0.25rem !important;
	transition: background-color 0.2s ease !important;
}

.btn-search-icon i {
	font-size: 1.2rem !important;
	color: #28a745 !important;
}

.btn-search-icon:hover {
	background-color: rgba(40, 167, 69, 0.1) !important;
}

#searchInfo {
	font-size: 0.85rem !important;
	margin-left: 0.5rem !important;
	flex-shrink: 0 !important;
	white-space: nowrap !important;
	color: #6c757d !important;
}

/* PC 화면 - 조명천장&본천장 제품별 단가 테이블 최적화 */
@media (min-width: 769px) {
	/* 단가 테이블 컨테이너 */
	.col-sm-4 .table-responsive {
		overflow-x: visible !important;
	}
	
	/* 단가 테이블 스타일 */
	.col-sm-4 .table {
		min-width: auto !important;
		width: 100% !important;
		font-size: 0.8rem !important;
	}
	
	/* 단가 테이블 헤더 */
	.col-sm-4 .table thead th {
		font-size: 0.75rem !important;
		padding: 0.4rem 0.3rem !important;
		white-space: nowrap !important;
	}
	
	/* 단가 테이블 셀 */
	.col-sm-4 .table tbody td {
		font-size: 0.75rem !important;
		padding: 0.4rem 0.3rem !important;
		white-space: nowrap !important;
	}
	
	/* 단가 테이블 내부 span */
	.col-sm-4 .table tbody td span {
		font-size: 0.75rem !important;
	}
	
	/* 단가 테이블 제목 */
	.col-sm-4 .card-title {
		font-size: 0.85rem !important;
	}
	
	/* 발주접수 후 미청구 제품 결재 예측 테이블 (#table2) 동일 스타일 적용 */
	#table2 {
		min-width: auto !important;
		width: 100% !important;
		font-size: 0.8rem !important;
	}
	
	/* #table2 헤더 */
	#table2 thead th {
		font-size: 0.75rem !important;
		padding: 0.4rem 0.3rem !important;
		white-space: nowrap !important;
	}
	
	/* #table2 셀 */
	#table2 tbody td {
		font-size: 0.75rem !important;
		padding: 0.4rem 0.3rem !important;
		white-space: nowrap !important;
	}
	
	/* #table2 제목 */
	.col-sm-8 .fs-6 {
		font-size: 0.85rem !important;
	}
}

@media (max-width: 768px) {
	/* body와 html의 width 제한 */
	html, body {
		max-width: 100vw !important;
		overflow-x: hidden !important;
		font-size: 16px !important;
	}

	/* 컨테이너 모바일 최적화 */
	.container,
	.container-fluid {
		max-width: 100vw !important;
		padding: 10px !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
	}
	
	/* PC 전용 버튼 숨기기 */
	.pc-only-btn {
		display: none !important;
	}
	
	/* 모바일 전용 검색 버튼 표시 */
	.btn-search-icon,
	#searchBtnMobile {
		display: flex !important;
	}

	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin: 0 !important;
		padding: 0 !important;
	}

	.row.justify-content-center.align-items-center.mt-1 {
		margin: 0.5rem 0 !important;
		padding: 0 !important;
	}

	/* 컬럼 모바일 최적화 */
	.col-sm-4,
	.col-sm-5,
	.col-sm-7,
	.col-sm-8 {
		flex: 0 0 100% !important;
		max-width: 100% !important;
		padding: 0.5rem !important;
		margin-bottom: 1rem !important;
	}

	/* 카드 모바일 최적화 */
	.card,
	.glass-container,
	.modern-management-card {
		margin: 0.5rem 0 !important;
		width: 100% !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
		border-radius: 12px !important;
	}

	.card-body {
		padding: 0.75rem 0.5rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.card-title {
		font-size: 0.9rem !important;
		margin-bottom: 0.75rem !important;
		padding: 0 0.5rem !important;
	}

	.card-title.fs-6 {
		font-size: 0.85rem !important;
	}

	/* 검색 영역 모바일 최적화 - 통합 레이아웃 */
	.search-container {
		justify-content: flex-start !important;
		padding: 0.25rem 0.5rem !important;
		margin-top: 0.25rem !important;
	}
	
	.inputWrap {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		max-width: none !important;
	}
	
	.inputWrap input {
		font-size: 1rem !important;
	}
	
	#searchInfo {
		font-size: 0.7rem !important;
		margin-left: 0.3rem !important;
	}

	/* 테이블 모바일 최적화 - 가로 스크롤 */
	.table-responsive {
		overflow-x: auto !important;
		overflow-y: visible !important;
		-webkit-overflow-scrolling: touch !important;
		width: 100% !important;
		max-width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
		display: block !important;
		position: relative !important;
	}

	.table-responsive .table {
		width: auto !important;
		min-width: 100% !important;
		margin-bottom: 0 !important;
		display: table !important;
		table-layout: auto !important;
	}

	.table {
		font-size: 0.8rem !important;
		width: auto !important;
		min-width: 800px !important;
		margin-bottom: 0 !important;
		display: table !important;
		table-layout: auto !important;
	}

	.table th {
		font-size: 0.75rem !important;
		padding: 0.5rem 0.4rem !important;
		white-space: nowrap !important;
		text-align: center !important;
		min-width: 60px !important;
	}

	.table td {
		font-size: 0.75rem !important;
		padding: 0.5rem 0.4rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		text-align: center !important;
		min-width: 60px !important;
	}
	
	/* 테이블2 (발주접수 후 미청구 제품 결재 예측) 최소 너비 설정 */
	#table2 {
		min-width: 1000px !important;
		width: auto !important;
	}
	
	#table2 th {
		min-width: 80px !important;
		white-space: normal !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		padding: 0.5rem 0.4rem !important;
	}
	
	#table2 td {
		min-width: 80px !important;
		white-space: normal !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		padding: 0.5rem 0.4rem !important;
	}
	
	/* 인승 열 최소 너비 */
	#table2 th:first-child,
	#table2 td:first-child {
		min-width: 100px !important;
	}
	
	/* 수량합 열 최소 너비 */
	#table2 th:nth-child(2),
	#table2 td:nth-child(2) {
		min-width: 70px !important;
	}
	
	/* 조명천장/본천장 수량 열 */
	#table2 th:nth-child(3),
	#table2 th:nth-child(5),
	#table2 td:nth-child(3),
	#table2 td:nth-child(5) {
		min-width: 60px !important;
	}
	
	/* 조명천장/본천장 금액 열 */
	#table2 th:nth-child(4),
	#table2 th:nth-child(6),
	#table2 td:nth-child(4),
	#table2 td:nth-child(6) {
		min-width: 90px !important;
	}
	
	/* 기타 인테리어 열 (rowspan이 있는 셀) */
	#table2 th[rowspan="2"]:nth-child(7),
	#table2 td[rowspan="4"]:nth-child(7) {
		min-width: 100px !important;
	}
	
	/* 합계 열 */
	#table2 th:last-child,
	#table2 td:last-child {
		min-width: 100px !important;
	}
	
	/* 합계 행의 colspan 셀 */
	#table2 td[colspan="4"],
	#table2 td[colspan="2"] {
		min-width: 200px !important;
	}
	
	/* output-table 모바일 최적화 */
	.output-table {
		width: auto !important;
		min-width: 100% !important;
		display: table !important;
		table-layout: auto !important;
	}
	
	.output-chart-container .table-responsive {
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
		width: 100% !important;
		max-width: 100% !important;
	}

	/* Tabulator 모바일 최적화 - 숨기기 */
	#myTable {
		display: none !important;
	}
	
	/* Tabulator UI 요소 숨기기 */
	.tabulator .tabulator-header,
	.tabulator .tabulator-footer,
	.tabulator .tabulator-paginator {
		display: none !important;
	}
	
	/* 모바일 카드 컨테이너 */
	.mobile-cards-container {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem !important;
		box-sizing: border-box !important;
	}
	
	.mobile-card {
		background: white !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 8px !important;
		padding: 0.75rem !important;
		margin-bottom: 0.75rem !important;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}
	
	.mobile-card strong {
		color: #0dcaf0 !important;
		margin-right: 0.5rem !important;
		font-size: 0.9rem !important;
		flex-shrink: 0 !important;
	}
	
	.mobile-card span {
		flex: 1 !important;
		min-width: 0 !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		font-size: 0.8em !important;
	}
	
	.mobile-card .d-flex {
		display: flex !important;
		align-items: center !important;
		margin-bottom: 0.5rem !important;
		flex-wrap: wrap !important;
	}

	/* 입력 필드 모바일 최적화 */
	.form-control {
		font-size: 0.75rem !important;
		padding: 0.35rem 0.4rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.form-control.w-75 {
		width: 90% !important;
		max-width: 150px !important;
	}

	/* 버튼 모바일 최적화 */
	.btn {
		font-size: 0.75rem !important;
		padding: 0.35rem 0.5rem !important;
		white-space: nowrap !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.btn-sm {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.45rem !important;
	}

	/* 모든 버튼과 텍스트가 카드 내부에 머물도록 */
	.card *,
	.glass-container *,
	.modern-management-card *,
	.container *,
	.container-fluid *,
	.mobile-card * {
		box-sizing: border-box !important;
	}

	.card button,
	.card .btn,
	.card span,
	.card input,
	.card table,
	.glass-container button,
	.glass-container .btn,
	.glass-container span,
	.glass-container input,
	.glass-container table,
	.container button,
	.container .btn,
	.container span,
	.container input,
	.mobile-card button,
	.mobile-card .btn,
	.mobile-card span,
	.mobile-card input {
		max-width: 100% !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
	
	/* 텍스트 줄바꿈 */
	p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
	
	/* 모달 최적화 */
	.modal-dialog {
		max-width: 100% !important;
		margin: 0 !important;
		width: 100% !important;
		height: 100% !important;
	}
	
	.modal-content {
		width: 100% !important;
		height: 100% !important;
		border-radius: 0 !important;
	}
	
	.modal-body {
		overflow-y: auto !important;
		max-height: calc(100vh - 120px) !important;
	}

	/* 출력 섹션 제목 모바일 최적화 */
	.output-section-title,
	.output-chart-container h5.output-section-title {
		font-size: 0.9rem !important;
		margin-bottom: 0.75rem !important;
	}

	.output-chart-container {
		padding: 1rem 0.75rem !important;
		margin-bottom: 1rem !important;
	}

	.output-chart-container h6.text-muted {
		font-size: 0.8rem !important;
		padding-bottom: 0.4rem !important;
		margin-bottom: 0.75rem !important;
	}

	/* 출력 테이블 모바일 최적화 */
	.output-table {
		font-size: 0.75rem !important;
	}

	.output-table th {
		font-size: 0.7rem !important;
		padding: 0.5rem 0.4rem !important;
	}

	.output-table td {
		font-size: 0.7rem !important;
		padding: 0.5rem 0.4rem !important;
	}

	.output-table.table-sm th {
		font-size: 0.65rem !important;
		padding: 0.4rem 0.3rem !important;
	}

	.output-table.table-sm td {
		font-size: 0.65rem !important;
		padding: 0.4rem 0.3rem !important;
	}
}

/* Unified Button Styles */
.btn-primary,
.btn-dark {
    background: var(--dashboard-accent) !important;
    color: var(--btn-text-on-accent) !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 0.4rem 0.8rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1) !important;
}

.btn-primary:hover,
.btn-dark:hover {
    background: var(--dashboard-accent-light) !important;
    color: var(--btn-hover-text) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.15) !important;
    opacity: 0.9 !important;
}

.btn-secondary {
    background: var(--dashboard-secondary) !important;
    color: var(--btn-text-on-light) !important;
    border: 1px solid var(--dashboard-border) !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.btn-secondary:hover {
    background: #c7f0ff !important;
    color: var(--btn-text-on-light) !important;
    transform: translateY(-1px) !important;
}

/* Unified Form Elements */
.form-control,
.form-select {
    border-color: var(--dashboard-border);
    background: white;
    color: var(--dashboard-text);
    border-radius: 4px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
}
</style>
</head>
<?php require_once(includePath('myheader.php')); ?>
<?php
// 환경파일 불러오기
$readIni = array();
$readIni = parse_ini_file("./estimate.ini", false);

// 초기 서버를 이동중에 저장해야할 변수들을 저장하면서 작업한다. 자료를 추가 불러올때 카운터 숫자등..
$init_read = array();
$init_read = parse_ini_file("./estimate.ini", false);

// _request.php에서 정의되는 변수들을 include
include "_request.php";

// 추가 변수 초기화 (hidden input에서 사용)
$voc_alert = isset($_REQUEST["voc_alert"]) ? $_REQUEST["voc_alert"] : '';
$ma_alert = isset($_REQUEST["ma_alert"]) ? $_REQUEST["ma_alert"] : '';
$order_alert = isset($_REQUEST["order_alert"]) ? $_REQUEST["order_alert"] : '';
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$scale = isset($_REQUEST["scale"]) ? $_REQUEST["scale"] : '';
$yearcheckbox = isset($_REQUEST["yearcheckbox"]) ? $_REQUEST["yearcheckbox"] : '';
$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : '';
$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : 0;
$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : 0;
$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : 0;
$sqltext = isset($_REQUEST["sqltext"]) ? $_REQUEST["sqltext"] : '';
$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : '';
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$user_name = isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : '';

// 날짜 범위 체크 함수
function check_in_range($start_date, $end_date, $user_date)
{
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($user_date);
    
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 첨부파일 관련 배열 초기화
$savefilename_arr = array();
$realname_arr = array();
$attach_arr = array();
$tablename = 'ceiling';
$item = 'ceiling';

// 합계 배열 초기화
$sum = array();

// 모드 설정
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";

// 검색어 설정
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

// 현재 날짜 (출고예정 구분용)
$now = date("Y-m-d");

// 미출고 리스트 조건
$attached = " and ((workday='') or (workday='0000-00-00')) ";
$whereattached = " where workday='' ";

// SQL 쿼리 (서버사이드 검색 제거 - Tabulator 클라이언트 사이드 검색 사용)
$sql = "select * from mirae8440.ceiling " . $whereattached;
$sqlcon = "select * from mirae8440.ceiling " . $whereattached; // 전체 레코드수 파악용


// 인승별 합계 변수 초기화
$lc_total_12 = 0;
$bon_total_12 = 0;
$lc_total_13to17 = 0;
$bon_total_13to17 = 0;
$lc_total_18 = 0;
$bon_total_18 = 0;
$etcsum = 0;

// 데이터 조회 및 합계 계산
try {
    $allstmh = $pdo->query($sqlcon);
    
    while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {
        $inseung = $row["inseung"];
        $bon_su = $row["bon_su"];
        $lc_su = $row["lc_su"];
        $price = $row["price"];
        
        // 콤마 제거 및 숫자 변환
        $numericPrice = (int)str_replace(',', '', $price);
        $etcsum += $numericPrice;
        
        $inseung_num = intval($inseung);
        $lc_su_num = intval($lc_su);
        $bon_su_num = intval($bon_su);
        
        // 12인승 이하 LC
        if ($inseung_num <= 12 && $lc_su_num >= 1) {
            $lc_total_12 += $lc_su_num;
        }
        
        // 13~17인승 LC
        if ($inseung_num >= 13 && $inseung_num <= 17 && $lc_su_num >= 1) {
            $lc_total_13to17 += $lc_su_num;
        }
        
        // 18인승 이상 LC
        if ($inseung_num >= 18 && $lc_su_num >= 1) {
            $lc_total_18 += $lc_su_num;
        }
        
        // 12인승 이하 본천장
        if ($inseung_num <= 12 && $bon_su_num >= 1) {
            $bon_total_12 += $bon_su_num;
        }
        
        // 13~17인승 본천장
        if ($inseung_num >= 13 && $inseung_num <= 17 && $bon_su_num >= 1) {
            $bon_total_13to17 += $bon_su_num;
        }
        
        // 18인승 이상 본천장
        if ($inseung_num >= 18 && $bon_su_num >= 1) {
            $bon_total_18 += $bon_su_num;
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}


// 인승별 합계
$sum_12 = $lc_total_12 + $bon_total_12;
$sum_13to17 = $lc_total_13to17 + $bon_total_13to17;
$sum_18 = $lc_total_18 + $bon_total_18;

// 합계표 계산 (천원 단위)

// 12인승 이하
$bon_total_12_amount = str_replace(',', '', $bon_total_12) * intval(str_replace(',', '', $readIni["bon_unit_12"])) / 1000;
$lc_total_12_amount = str_replace(',', '', $lc_total_12) * intval(str_replace(',', '', $readIni["lc_unit_12"])) / 1000;
$bon_total_12_total = $bon_total_12_amount + $lc_total_12_amount;
$bon_total_12_num = $bon_total_12 + $lc_total_12;

// 13~17인승
$bon_total_13to17_amount = str_replace(',', '', $bon_total_13to17) * intval(str_replace(',', '', $readIni["bon_unit_13to17"])) / 1000;
$lc_total_13to17_amount = str_replace(',', '', $lc_total_13to17) * intval(str_replace(',', '', $readIni["lc_unit_13to17"])) / 1000;
$bon_total_13to17_total = $bon_total_13to17_amount + $lc_total_13to17_amount;
$bon_total_13to17_num = $bon_total_13to17 + $lc_total_13to17;

// 18인승 이상
$bon_total_18_amount = str_replace(',', '', $bon_total_18) * intval(str_replace(',', '', $readIni["bon_unit_18"])) / 1000;
$lc_total_18_amount = str_replace(',', '', $lc_total_18) * intval(str_replace(',', '', $readIni["lc_unit_18"])) / 1000;
$bon_total_18_total = $bon_total_18_amount + $lc_total_18_amount;
$bon_total_18_num = $bon_total_18 + $lc_total_18;

// 전체 합계 (특정품목 가격 포함, 천원 단위)
$total = $bon_total_12_total + $bon_total_13to17_total + $bon_total_18_total + ($etcsum / 1000);

// 전체 레코드수 파악
try {
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();    		
			 
 ?>
 
<div class="container">  

<style>
.fixed-table {
		position: sticky;
		top: 0;
		background-color: #fff;
		z-index: 1;
		margin-bottom: 10px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}
	
    .form-group {
        display: flex;
        justify-content: center;
    }

    .form-group input {
        margin: auto;
		text-align : center;
    }	

/* 우측배너 제작 */
.sideBanner {
  position: absolute;
  width: calc(100vw - 90vw);
  height:calc(100vh - 70vh);
  top: calc(100vh - 70vh);
  left: calc(100vw - 15vw);  
}

input{	
	border: none!important;
}
</style>	

<form id="board_form" name="board_form" class="form-signin" method="post" action="front_log.php">		
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >
	<input type="hidden" id="num" name="num" value="<?=$num?>" >                        
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" >

<div class="row justify-content-center align-items-center mt-1">
	<div class="col-sm-4 text-center">
	  <div class="card align-middle justify-content-center " style="border-radius: 15px;">
		<div class="card-body mt-2 mb-2 justify-content-center">	
	<span class="card-title fs-6" style="color: #113366; ">조명천장&본천장 제품별 단가 (단위:천원)</span> 
						
	<div class="table-responsive">
		<table class="table table-bordered justify-content-center" style="min-width: 400px;">
			<thead class="table-primary">
				<tr>
					<th >인승</th>
					<th >조명천장</th>
					<th >본천장</th>
				</tr>        
			</thead>
			<tbody>
				<tr>
					<td>12인승 이하</td>
					<td>
					   <div class="d-flex justify-content-center">
							<span class="w-75 text-end"><?= number_format((float)str_replace(',', '', $readIni['lc_unit_12'] ?? 0)) ?></span>
						</div>
					</td>
					<td>
					   <div class="d-flex justify-content-center">
							<span class="w-75 text-end"><?= number_format((float)str_replace(',', '', $readIni['bon_unit_12'] ?? 0)) ?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>13인승 이상 17인승 이하</td>
					<td>
					   <div class="d-flex justify-content-center">
							<span class="w-75 text-end"><?= number_format((float)str_replace(',', '', $readIni['lc_unit_13to17'] ?? 0)) ?></span>
						</div>
					</td>
					<td>
					   <div class="d-flex justify-content-center">
							<span class="w-75 text-end"><?= number_format((float)str_replace(',', '', $readIni['bon_unit_13to17'] ?? 0)) ?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>18인승 이상</td>
					<td>
					   <div class="d-flex justify-content-center">
							<span class="w-75 text-end"><?= number_format((float)str_replace(',', '', $readIni['lc_unit_18'] ?? 0)) ?></span>
						</div>
					</td>
					<td>
					   <div class="d-flex justify-content-center">
							<span class="w-75 text-end"><?= number_format((float)str_replace(',', '', $readIni['bon_unit_18'] ?? 0)) ?></span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>	
	</div>
	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 
		<button id="writeBtn" type="button" class="btn btn-primary btn-sm">  <i class="bi bi-pencil-square"></i> 단가 자료수정  </button> &nbsp;&nbsp;&nbsp;&nbsp;
	</div>
</div>
</div>
</div>


<div class="col-sm-8 text-center">	
<div class="card align-middle justify-content-center " style="border-radius: 20px;">
<div class="card-body mt-2 mb-2 justify-content-center">	
<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 	
  <span class="fs-6 mt-2">
     발주접수 후 미청구 제품 결재 예측 (단위 : 천원)
   </span>
   <button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	
</div>


<div class="table-responsive">
<table id="table2" class="table table-bordered justify-content-center" >
    <thead class="table-primary">
        <tr>
            <th rowspan = "2" > 인승</th>
            <th rowspan = "2" > 수량합</th>
            <th colspan = "2" > 조명천장</th>
            <th colspan = "2" > 본천장</th>			        
			<th rowspan = "2" > 기타<br> 인테리어
			                       
			</th>     
            <th rowspan = "2" > 합계</th>
        </tr>        
        <tr>            
            <th >수량</th>            
            <th >금액</th>
			<th >수량</th>            
            <th >금액</th>
        </tr>        
    </thead>	
 <tbody>
    <tr>
		<td >  12인승 이하</td>
        <td>
			<div class="d-flex justify-content-center">			
				<input type="text"  class="form-control w-75 text-end"  value="<?= number_format($sum_12) ?>"  />
			</div>	
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input type="text" class="form-control w-75 text-end"   value="<?= number_format($lc_total_12) ?>"   />
			</div>
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input type="text"  class="form-control text-end"  value="<?= number_format($lc_total_12_amount) ?>"   />
			</div>
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input type="text"  class="form-control w-75 text-end"   value="<?= number_format($bon_total_12) ?>" />
			</div>
        </td>
        <td>
			<div class="d-flex justify-content-center">			
				<input type="text"  class="form-control  text-end"  value="<?= number_format($bon_total_12_amount) ?>" />
			</div>
        </td>
        <td rowspan="4" class="vertical-middle">
		    <div class="d-flex justify-content-center">			
				<input type="text"  class="form-control text-end fw-bold text-primary"   value="<?= number_format($etcsum/1000) ?>"  />
			</div>
        </td>	
        <td>
		    <div class="d-flex justify-content-center">			
				<input type="text"  class="form-control text-end fw-bold"   value="<?= number_format($bon_total_12_total) ?>"  />
			</div>
        </td>			
    </tr>
    <tr>
            <td>13인승 이상 17인승 이하</td>
        <td>
		    <div class="d-flex justify-content-center">			
				<input type="text"   class="form-control w-75 text-end"   value="<?= number_format($sum_13to17) ?>"  />
			</div>
        </td>	
        <td>
		    <div class="d-flex justify-content-center">			
				<input type="text"  class="form-control w-75 text-end"    value="<?= number_format($lc_total_13to17) ?>"   />
			</div>
        </td>		
        <td>
			<div class="d-flex justify-content-center">	
				<input type="text"   class="form-control  text-end"   value="<?= number_format($lc_total_13to17_amount) ?>"   />
			</div>
        </td>
        <td>
		    <div class="d-flex justify-content-center">			
				<input type="text"  class="form-control w-75 text-end"    value="<?= number_format($bon_total_13to17) ?>"   />
			</div>
        </td>		
        <td>
			<div class="d-flex justify-content-center">	
				<input type="text"   class="form-control  text-end"  value="<?= number_format($bon_total_13to17_amount) ?>" />
			</div>
        </td>
        <td>
		    <div class="d-flex justify-content-center">			
				<input type="text"   class="form-control  text-end fw-bold"  value="<?= number_format($bon_total_13to17_total) ?>"  />
			</div>
        </td>			
    </tr>
	
    <tr>	
	
	  <td>18인승 이상</td>
	  
        <td>
		    <div class="d-flex justify-content-center">					
				<input type="text"   class="form-control w-75 text-end"  value="<?= number_format($sum_18) ?>"  />
			</div>
        </td>			
        <td>
		    <div class="d-flex justify-content-center">					
				<input type="text"  class="form-control w-75 text-end"  value="<?= number_format($lc_total_18) ?>"   />
			</div>
        </td>		
        <td>
		    <div class="d-flex justify-content-center">					
				<input type="text"   class="form-control text-end"  value="<?= number_format($lc_total_18_amount) ?>"   />
			</div>
        </td>
        <td>
		    <div class="d-flex justify-content-center">					
				<input type="text"  class="form-control w-75 text-end"  value="<?= number_format($bon_total_18) ?>"   />
			</div>
        </td>		
        <td>
		    <div class="d-flex justify-content-center">					
				<input type="text"    class="form-control text-end"  value="<?= number_format($bon_total_18_amount) ?>" />
			</div>
        </td>		
        <td>
		    <div class="d-flex justify-content-center">					
				<input type="text"   class="form-control  text-end"   name="bon_total_18_total" style=" font-weight: bold;" value="<?= number_format($bon_total_18_total) ?>"  />
			</div>
        </td>			
    </tr>
	<?php
   	   // 천장모델 합계
	  $ceiling_total = $bon_total_12_total + $bon_total_13to17_total + $bon_total_18_total;
	  $ceiling_total_string = '(본천장+LC) : ' . number_format($ceiling_total);
	?>
    <tr>
        <td colspan="4" >합계</td>            
        <td colspan="2" >
		    <div class="d-flex justify-content-center">					
				<input type="text" class="form-control  text-end text-primary"  name="ceiling_total"  value="<?= $ceiling_total_string ?>"  />
			</div>
        </td>         
        <td>
		    <div class="d-flex justify-content-center">					
				<span class="text-end text-danger" id="total" name="total" style="display: inline-block; width: 100%; background: none; border: none; box-shadow: none; padding: 0;"><?= number_format($total) ?></span>
			</div>
        </td>
    </tr>		
</tbody>
</table>
</div>                  
</div>
</div>
</div>
</div>
</div>
</div>
  </form>					
<div id="vacancy" style="display:none">  </div>	
  
<div class="container">   
  		
				<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
				<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
				<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
				<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
				<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
				<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
				<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
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

<div class="card glass-container mt-1 mb-1">
	<div class="card-body">  	
		<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 	    	
</div>	  
<div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center search-container">  
	<div class="inputWrap">
		<input type="text" id="search" name="search" class="form-control p-1 me-1" style="width:300px!important;" placeholder="현장명, 발주처, 타입.."  value="<?=$search?>" >
		<button class="btnClear" type="button"></button>
		<button type="button" id="searchBtnMobile" class="btn-search-icon">
			<i class="bi bi-search"></i>
		</button>
	</div>
	<button id="searchBtn" type="button" class="btn btn-dark btn-sm pc-only-btn"> <i class="bi bi-search"></i> 검색 </button>
	<button id="clearBtn" type="button" class="btn btn-secondary btn-sm pc-only-btn"> <i class="bi bi-x-circle"></i> 초기화 </button>
	<span id="searchInfo" class="text-muted small"></span>
</div> <!-- end of 2단계 list_search1  -->     
<div class="table-responsive">
  <div id="myTable"></div>
  <div style="display:none;">
    <table id="dataSource">
      <tbody>		
		     
    <?php
    $start_num = $total_row;    // 페이지당 표시되는 첫번째 글순번
    $dataArray = [];  // Tabulator용 데이터 배열 초기화
    
    // 합계 배열 초기화
    if (!isset($sum[0])) $sum[0] = 0;
    if (!isset($sum[1])) $sum[1] = 0;
    if (!isset($sum[2])) $sum[2] = 0;
    if (!isset($sum[3])) $sum[3] = 0;
    if (!isset($sum[4])) $sum[4] = 0;
    if (!isset($sum[5])) $sum[5] = 0;
    
    $dis_text = "";
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';
        
        // 첨부파일이 있는 경우 '(비규격)' 문구 추가
        for ($i = 0; $i < count($attach_arr); $i++) {
            if ($attach_arr[$i] == $num) {
                $workplacename = '(비규격)' . $workplacename;
            }
        }
        
        // 합계 계산
        $sum[0] = $sum[0] + (int)$su;
        $sum[1] += (int)$bon_su;
        $sum[2] += (int)$lc_su;
        $sum[3] += (int)$etc_su;
        $sum[4] += (int)$air_su;
        $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
        
        $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),  본천장 : " . $sum[1] . " (EA),  L/C : " . $sum[2] . " (EA), 기타 : " . $sum[3] . " (EA), 공기청정기 : " . $sum[4] . " (EA) "; 			   			  

        // 날짜 변환
        $workday = trans_date($workday);
        $demand = trans_date($demand);
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        $testday = trans_date($testday);
        $lc_draw = trans_date($lc_draw);
        $lclaser_date = trans_date($lclaser_date);
        $lcbending_date = trans_date($lcbending_date);
        $lclwelding_date = trans_date($lclwelding_date);
        $lcwelding_date = trans_date($lcwelding_date);
        $lcassembly_date = trans_date($lcassembly_date);
        $main_draw = trans_date($main_draw);
        $eunsung_make_date = trans_date($eunsung_make_date);
        $eunsung_laser_date = trans_date($eunsung_laser_date);
        $mainbending_date = trans_date($mainbending_date);
        $mainwelding_date = trans_date($mainwelding_date);
        $mainpainting_date = trans_date($mainpainting_date);
        $mainassembly_date = trans_date($mainassembly_date);
        
        $order_date1 = trans_date($order_date1);
        $order_date2 = trans_date($order_date2);
        $order_date3 = trans_date($order_date3);
        $order_date4 = trans_date($order_date4);
        $order_input_date1 = trans_date($order_input_date1);
        $order_input_date2 = trans_date($order_input_date2);
        $order_input_date3 = trans_date($order_input_date3);
        $order_input_date4 = trans_date($order_input_date4);				  
        
        // 작업 상태 설정
        $state_work = 0;
        if ($row["checkbox"] == 0) $state_work = 1;
        if (substr($row["workday"], 0, 2) == "20") $state_work = 2;
        if (substr($row["endworkday"], 0, 2) == "20") $state_work = 3;
        
        // 본천장 도면 표시
        $main_draw_arr = "";
        if (substr($main_draw, 0, 2) == "20") {
            $main_draw_arr = iconv_substr($main_draw, 5, 5, "utf-8");
        } elseif ($bon_su < 1) {
            $main_draw_arr = "X";
        }
        
        // LC 도면 표시
        $lc_draw_arr = "";
        if (substr($lc_draw, 0, 2) == "20") {
            $lc_draw_arr = iconv_substr($lc_draw, 5, 5, "utf-8");
        } elseif ($lc_su < 1) {
            $lc_draw_arr = "X";
        }
        if ($type == '011' || $type == '012' || $type == '013D' || $type == '025' || $type == '017' || $type == '014' || $type == '037') {
            $lc_draw_arr = "X";
        }
        
        // 본천장 조립 표시
        $mainassembly_arr = "";
        if (substr($mainassembly_date, 0, 2) == "20") {
            $mainassembly_arr = iconv_substr($mainassembly_date, 5, 5, "utf-8");
        } elseif ($bon_su < 1) {
            $mainassembly_arr = "X";
        }
        
        // LC 조립 표시
        $lcassembly_arr = "";
        if (substr($lcassembly_date, 0, 2) == "20") {
            $lcassembly_arr = iconv_substr($lcassembly_date, 5, 5, "utf-8");
        } elseif ($lc_su < 1 || $type == '011' || $type == '012' || $type == '013D' || $type == '025' || $type == '017' || $type == '014') {
            $lcassembly_arr = "X";
        }
        
        // 작업 항목 문자열 생성
        $workitem = "";
        if ($su != "") {
            $workitem = $su . " , ";
        }
        if ($bon_su != "") {
            $workitem .= "본 " . $bon_su . ", ";
        }
        if ($lc_su != "") {
            $workitem .= "L/C " . $lc_su . ", ";
        }
        if ($etc_su != "") {
            $workitem .= "기타 " . $etc_su . ", ";
        }
        if ($air_su != "") {
            $workitem .= "공기청정기 " . $air_su . " ";
        }
        
        // 파트 문자열 생성
        $part = "";
        if ($order_com1 != "") {
            $part = $order_com1 . ",";
        }
        if ($order_com2 != "") {
            $part .= $order_com2 . ", ";
        }
        if ($order_com3 != "") {
            $part .= $order_com3 . ", ";
        }
        if ($order_com4 != "") {
            $part .= $order_com4 . ", ";
        }
        
        // 배송 정보 문자열 생성
        $deli_text = "";
        if ($delivery != "" || $delipay != 0) {
            $deli_text = $delivery . " " . $delipay;
        }  
        
        // Tabulator용 데이터 배열에 추가
        $dataArray[] = [
            'num' => $num,
            'start_num' => $start_num,
            'orderday' => $orderday ? $orderday : '',
            'workplacename' => $workplacename ? htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') : '',
            'secondord' => $secondord ? htmlspecialchars(substr($secondord, 0, 10), ENT_QUOTES, 'UTF-8') : '',
            'type' => $type ? htmlspecialchars(substr($type, 0, 5), ENT_QUOTES, 'UTF-8') : '',
            'inseung' => $inseung ? htmlspecialchars(substr($inseung, 0, 2), ENT_QUOTES, 'UTF-8') : '',
            'car_insize' => $car_insize ? htmlspecialchars(substr($car_insize, 0, 9), ENT_QUOTES, 'UTF-8') : '',
            'bon_su' => $bon_su ? $bon_su : '',
            'lc_su' => $lc_su ? $lc_su : '',
            'etc_su' => $etc_su ? $etc_su : '',
            'price' => $price !== '' && $price !== null ? $price : 0,
            'memo' => $memo ? htmlspecialchars(substr($memo, 0, 8), ENT_QUOTES, 'UTF-8') : ''
        ];
        
        $start_num--;
    }
    
    // Tabulator용 JavaScript 데이터 생성
    echo "<script>var tableData = " . json_encode($dataArray, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS) . ";</script>";
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
    echo "<script>var tableData = [];</script>";
}
?>
      </tbody>
    </table>
  </div>
</div>
</div>
</div> <!--card-body-->
</div> <!--card -->

 <div class="d-flex mb-1 mt-1 justify-content-center  align-items-center " >  
<? include '../footer_sub.php'; ?>
</div>
</div>
</body>
<script>
// 전역 변수 선언
var table; // Tabulator 인스턴스
var workfrontlogpageNumber; // 현재 페이지 번호 저장
var isRenderingCards = false;
var processedTables = new Set();

// 모바일 환경 체크
function isMobile() {
	return window.innerWidth <= 768;
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

// Tabulator 초기화 함수
function initializeTabulator() {
    // tableData가 정의되지 않았거나 비어있는 경우 빈 배열로 초기화
    if (typeof tableData === 'undefined' || !tableData) {
        console.error('tableData is not defined or empty');
        tableData = [];
    }
    
    // 데이터 확인 (디버깅용)
    console.log('tableData length:', tableData.length);
    console.log('tableData sample:', tableData.slice(0, 3));

    // Tabulator 초기 설정
    table = new Tabulator("#myTable", {
        data: tableData,
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 50,
        paginationSizeSelector: [25, 50, 100, 200, 500, 1000],
        paginationCounter: "rows",
        movableColumns: true,
        resizableRows: false,
        initialSort: [
            {column: "orderday", dir: "desc"}
        ],
        columns: [
            {title: "번호", field: "start_num", width: 80, hozAlign: "center", headerSort: true},
            {title: "접수", field: "orderday", width: 100, hozAlign: "center", headerSort: true},
            {title: "현장명", field: "workplacename", minWidth: 200, headerSort: true},
            {title: "발주처", field: "secondord", width: 120, hozAlign: "center", headerSort: true},
            {title: "타입", field: "type", width: 100, hozAlign: "center", headerSort: true},
            {title: "인승", field: "inseung", width: 80, hozAlign: "center", headerSort: true},
            {title: "Car inside", field: "car_insize", width: 120, hozAlign: "center", headerSort: true},
            {title: "본", field: "bon_su", width: 80, hozAlign: "center", headerSort: true},
            {title: "L/C", field: "lc_su", width: 80, hozAlign: "center", headerSort: true},
            {title: "기타", field: "etc_su", width: 80, hozAlign: "center", headerSort: true},
            {title: "제품가격", field: "price", width: 120, hozAlign: "right", headerSort: true,
                formatter: function(cell, formatterParams) {
                    var value = cell.getValue() || 0;
                    return typeof value === 'number' ? value.toLocaleString() : value;
                }
            },
            {title: "비고", field: "memo", width: 200, hozAlign: "left", headerSort: true}
        ],
        rowClick: function(e, row) {
            var data = row.getData();
            var num = data.num;
            if(num) {
                redirectToView(num);
            }
        }
    });

    // 검색 함수
    function performSearch() {
        var searchValue = document.getElementById('search').value.trim();
        var searchInfo = document.getElementById('searchInfo');
        
        console.log('검색어:', searchValue);
        
        if (searchValue === '') {
            // 검색어가 없으면 모든 필터 제거
            table.clearFilter();
            searchInfo.textContent = '';
            
            // 모바일 카드 재렌더링
            if (isMobile()) {
                setTimeout(function() {
                    renderMobileCards();
                }, 200);
            }
        } else {
            // 공백 제거한 검색어 생성
            var cleanSearchValue = searchValue.replace(/\s+/g, '');

            // 여러 컬럼에서 검색 (현장명, 발주처, 타입, 비고)
            table.setFilter(function(data) {
                // 현장명에서 공백 제거 후 검색
                var workplacename = (data.workplacename || '').replace(/\s+/g, '');
                if (workplacename.toLowerCase().includes(cleanSearchValue.toLowerCase())) {
                    return true;
                }

                // 발주처 검색
                var secondord = (data.secondord || '');
                if (secondord.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                // 타입 검색
                var type = (data.type || '');
                if (type.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                // 비고 검색
                var memo = (data.memo || '');
                if (memo.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                // 인승 검색
                var inseung = (data.inseung || '');
                if (inseung.includes(searchValue)) {
                    return true;
                }

                // Car inside 검색
                var car_insize = (data.car_insize || '');
                if (car_insize.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                return false;
            });

            // 검색 결과 카운트 표시
            setTimeout(function() {
                var filteredCount = table.getDataCount("active");
                var totalCount = table.getDataCount();
                searchInfo.textContent = `검색결과: ${filteredCount}건 / 전체: ${totalCount}건`;
                
                // 모바일 카드 재렌더링
                if (isMobile()) {
                    setTimeout(function() {
                        renderMobileCards();
                    }, 200);
                }
            }, 100);
        }
    }

    // 검색 버튼 클릭 이벤트 (PC/모바일 통합)
    var searchBtn = document.getElementById('searchBtn');
    var searchBtnMobile = document.getElementById('searchBtnMobile');
    var clearBtn = document.getElementById('clearBtn');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', performSearch);
    }
    if (searchBtnMobile) {
        searchBtnMobile.addEventListener('click', performSearch);
    }

    // 초기화 버튼 클릭 이벤트
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            document.getElementById('search').value = '';
            table.clearFilter();
            document.getElementById('searchInfo').textContent = '';
        });
    }
    
    // Clear 버튼 (X 아이콘) 클릭 이벤트
    var btnClear = document.querySelector('.btnClear');
    if (btnClear) {
        btnClear.addEventListener('click', function() {
            document.getElementById('search').value = '';
            table.clearFilter();
            document.getElementById('searchInfo').textContent = '';
        });
    }

    // 엔터키로 검색
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // 실시간 검색 (타이핑할 때마다)
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300); // 300ms 대기 후 검색
    });

    // 페이지 번호 복원
    var savedPageNumber = getCookie('workfrontlogpageNumber');
    if (savedPageNumber) {
        table.setPage(parseInt(savedPageNumber));
    }

    // 페이지 변경 이벤트 리스너
    table.on("pageLoaded", function(pageno) {
        workfrontlogpageNumber = pageno;
        setCookie('workfrontlogpageNumber', pageno, 10);
    });
    
    // Tabulator 데이터 로드 후 모바일 카드 렌더링
    if (isMobile()) {
        setTimeout(function() {
            renderMobileCards();
        }, 300);
    }
    
    // Tabulator 데이터 변경 감지
    table.on("dataLoaded", function() {
        if (isMobile()) {
            setTimeout(function() {
                renderMobileCards();
            }, 300);
        }
    });
    
    // 필터 변경 감지
    table.on("dataFiltered", function() {
        if (isMobile()) {
            setTimeout(function() {
                renderMobileCards();
            }, 200);
        }
    });
}

// 모바일 카드 렌더링 함수
function renderMobileCards() {
	if (isRenderingCards) {
		return;
	}
	
	if (window.innerWidth > 768) {
		var containers = document.querySelectorAll('.mobile-cards-container');
		containers.forEach(function(container) {
			container.remove();
		});
		return;
	}
	
	isRenderingCards = true;
	
	// 기존 카드 컨테이너 제거
	var existingContainer = document.querySelector('.mobile-cards-container');
	if (existingContainer) {
		existingContainer.remove();
	}
	
	// Tabulator 데이터 가져오기
	if (!table) {
		isRenderingCards = false;
		return;
	}
	
	var tableData = table.getData();
	if (!tableData || tableData.length === 0) {
		isRenderingCards = false;
		return;
	}
	
	// 카드 컨테이너 생성
	var cardContainer = document.createElement('div');
	cardContainer.className = 'mobile-cards-container';
	cardContainer.style.cssText = 'width: 100%; max-width: 100%; padding: 0.5rem; box-sizing: border-box;';
	
	// Tabulator 컬럼 정보 가져오기
	var columns = table.getColumns();
	var columnTitles = [];
	columns.forEach(function(column) {
		var field = column.getField();
		var title = column.getDefinition().title || field;
		columnTitles.push({field: field, title: title});
	});
	
	// 각 행을 카드로 변환
	tableData.forEach(function(rowData, index) {
		var card = document.createElement('div');
		card.className = 'mobile-card';
		card.style.cssText = 'background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem; margin-bottom: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;';
		
		// 각 컬럼을 카드 항목으로 변환
		columnTitles.forEach(function(col) {
			var field = col.field;
			var title = col.title;
			var value = rowData[field];
			
			if (value !== undefined && value !== null && value !== '') {
				var cardItem = document.createElement('div');
				cardItem.className = 'd-flex align-items-center mb-2';
				cardItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap;';
				
				var label = document.createElement('strong');
				label.textContent = title + ': ';
				label.style.cssText = 'color: #0dcaf0; margin-right: 0.5rem; font-size: 0.9rem; flex-shrink: 0;';
				
				var valueSpan = document.createElement('span');
				if (field === 'price' && typeof value === 'number') {
					valueSpan.textContent = value.toLocaleString();
				} else {
					valueSpan.textContent = value;
				}
				valueSpan.style.cssText = 'flex: 1; min-width: 0; word-wrap: break-word; overflow-wrap: break-word; font-size: 0.8em;';
				
				cardItem.appendChild(label);
				cardItem.appendChild(valueSpan);
				card.appendChild(cardItem);
			}
		});
		
		// 카드 클릭 이벤트 (상세보기)
		card.style.cursor = 'pointer';
		card.addEventListener('click', function() {
			var num = rowData.num;
			if (num) {
				redirectToView(num);
			}
		});
		
		cardContainer.appendChild(card);
	});
	
	// Tabulator 컨테이너 다음에 카드 컨테이너 삽입
	var tableContainer = document.getElementById('myTable');
	if (tableContainer && tableContainer.parentNode) {
		tableContainer.parentNode.insertBefore(cardContainer, tableContainer.nextSibling);
	}
	
	isRenderingCards = false;
}

// 디바운스된 렌더링 함수
var debouncedRenderMobileCards = debounce(renderMobileCards, 300);

// 문서 준비 이벤트
$(document).ready(function() {
    // Tabulator 초기화 (PHP 데이터 로드 대기)
    setTimeout(function() {
        initializeTabulator();
    }, 100);
    
    // 창 크기 변경 시 모바일 카드 재렌더링
    $(window).on('resize', debouncedRenderMobileCards);
    
    // Tabulator 데이터 변경 감지
    if (table) {
        table.on("dataLoaded", function() {
            if (isMobile()) {
                setTimeout(function() {
                    renderMobileCards();
                }, 300);
            }
        });
    }
});

// 페이지 번호 복원 함수
function restorePageNumber() {
    var savedPageNumber = getCookie('workfrontlogpageNumber');
    if (savedPageNumber && table) {
        table.setPage(parseInt(savedPageNumber));
    }
}

// 단가입력 버튼 이벤트
$(document).ready(function() {
    $("#writeBtn").click(function() {
        popupCenter('./estimate.php', '쟘 제품 단가입력', 1050, 600);
    });
});

// 종류별 합계 표시 함수
function dis_text() {
    var dis_text = '<?php echo $dis_text; ?>';
    $("#dis_text").val(dis_text);
}

// 검색 조건 설정 함수
function search_condition(con) {
    $("#check").val(con);
    $("#page").val('1');
    $("#search").val('');
    $("#stable").val('0');
    $('#board_form').submit();
}

// 버튼 조건 설정 함수
function button_condition(con) {
    $("#sortof").val(con);
    $("#page").val('1');
    $("#stable").val('0');
    $('#board_form').submit();
}

// 상세보기 페이지로 이동
function redirectToView(num) {
    var page = workfrontlogpageNumber || 1;
    var url = "view.php?menu=no&num=" + num;
    customPopup(url, '천장 수주내역', 1800, 850);
}
</script>

<script>
// 로그 데이터 저장
$(document).ready(function() {
    saveLogData('Ceiling FrontLog');
});
</script>   
  </html>  