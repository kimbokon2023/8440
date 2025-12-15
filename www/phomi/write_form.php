<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));  

$title_message = '포미스톤 수주'; 
$title_message_sub = '수 주 서 (포미스톤)' ; 
$tablename = 'phomi_order'; 
$item ='포미스톤 수주';   
$emailTitle ='수주서';   
$subTitle = '포미스톤 제품';
$payment_account = '중소기업은행 339-084210-01-012 ㈜ 미래기업';

?>
<?php include getDocumentRoot() . '/load_header.php'; ?> 

<title> <?=$title_message?>  </title>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
<link rel="stylesheet" href="css/style.css">

<style>
/* PC 화면 기본 스타일 - 작성자 영역 한 줄 표시 */
.title-author-wrapper {
	display: flex !important;
	flex-direction: row !important;
	align-items: center !important;
	flex-wrap: nowrap !important;
	gap: 0.5rem !important;
}

.title-author-wrapper .title-text {
	display: inline !important;
	width: auto !important;
	font-size: 1.5rem !important;
	font-weight: bold !important;
	margin: 0 !important;
}

.title-author-wrapper .author-info {
	display: flex !important;
	flex-direction: row !important;
	align-items: center !important;
	flex-wrap: nowrap !important;
	gap: 0.25rem !important;
	margin-left: 1rem !important;
}

/* PC에서는 모바일 전용 버튼 숨기기 */
.mobile-only-btn {
	display: none !important;
}

	/* 모바일 환경 최적화 */
@media (max-width: 768px) {
	/* 공급자 정보 숨기기 */
	.supplier-info {
		display: none !important;
	}
	
	/* 모바일에서 계산하기 버튼 표시 */
	.mobile-only-btn {
		display: inline-block !important;
	}
	
	/* 모바일 전용 계산하기 버튼 표시 */
	.mobile-only-btn {
		display: inline-block !important;
	}
	
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
	}
	
	/* 제목 영역 최적화 */
	.d-flex.justify-content-between.align-items-center {
		flex-direction: row !important;
		flex-wrap: wrap !important;
		align-items: center !important;
		justify-content: space-between !important;
		gap: 0.5rem !important;
		padding: 0.5rem !important;
	}
	
	.d-flex.justify-content-between.align-items-center > div {
		width: auto !important;
		max-width: none !important;
		flex: 0 0 auto !important;
	}
	
	.d-flex.justify-content-between.align-items-center h4 {
		width: auto !important;
		text-align: left !important;
		font-size: 1.25rem !important;
		margin-bottom: 0 !important;
		margin-right: auto !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		flex: 0 0 auto !important;
	}
	
	.d-flex.justify-content-between.align-items-center button {
		width: auto !important;
		min-width: fit-content !important;
		max-width: none !important;
		margin: 0.25rem !important;
		padding: 0.5rem 0.75rem !important;
		font-size: 0.85rem !important;
		flex: 0 0 auto !important;
	}
	
	.d-flex.justify-content-between.align-items-center > div:last-child {
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: wrap !important;
		justify-content: flex-start !important;
		align-items: center !important;
		gap: 0.5rem !important;
		width: 100% !important;
	}
	
	/* 작성자/작성자ID 영역 최적화 */
	.d-flex.justify-content-between.align-items-center.mb-3 {
		flex-direction: column !important;
		align-items: stretch !important;
		gap: 0.5rem !important;
	}
	
	/* 작성자 영역 (제목 + 작성자 정보) - 모바일에서 제목 아래로 작성자 정보 이동 */
	.title-author-wrapper {
		width: 100% !important;
		flex-wrap: wrap !important;
		flex-direction: column !important;
		align-items: flex-start !important;
		gap: 0.5rem !important;
		margin-bottom: 0.5rem !important;
	}
	
	/* 제목 텍스트는 전체 너비로 표시 (첫 번째 줄) - 모바일에서만 */
	.title-author-wrapper .title-text {
		width: 100% !important;
		font-size: 1.25rem !important;
		font-weight: bold !important;
		display: block !important;
		line-height: 1.5 !important;
		margin-bottom: 0 !important;
	}
	
	/* 작성자 정보 영역 (제목 아래 새 줄) */
	.author-info {
		width: 100% !important;
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: wrap !important;
		align-items: flex-start !important;
		gap: 0.25rem !important;
	}
	
	/* 작성자 라벨과 입력 필드 - 한 행에 표시 */
	.author-info > span:not(.estimate-num-label) {
		white-space: nowrap !important;
		font-size: 0.9rem !important;
		margin: 0 !important;
		flex-shrink: 0 !important;
		overflow: visible !important;
	}
	
	/* 작성자 입력 필드 최적화 */
	.author-info > #author {
		flex: 0 1 auto !important;
		min-width: 80px !important;
		max-width: 120px !important;
		margin: 0 !important;
		padding: 0.375rem 0.5rem !important;
		font-size: 0.9rem !important;
		height: auto !important;
		overflow: visible !important;
		text-overflow: clip !important;
	}
	
	.author-info > #author_id {
		flex: 0 1 auto !important;
		min-width: 80px !important;
		max-width: 120px !important;
		margin: 0 !important;
		padding: 0.375rem 0.5rem !important;
		font-size: 0.9rem !important;
		height: auto !important;
		overflow: visible !important;
		text-overflow: clip !important;
	}
	
	/* 견적번호를 다음 행으로 이동 - 줄바꿈용 가상 요소 */
	.author-info > span.estimate-num-break {
		flex-basis: 100% !important;
		width: 100% !important;		
		order: 5 !important;
		display: block !important;
		margin: 0 !important;
		padding: 0 !important;
		visibility: hidden !important;
		opacity: 0 !important;
	}
	
	/* 견적번호 라벨과 입력 필드를 다음 행으로 강제 이동 */
	.author-info > span.estimate-num-label {
		flex-basis: 100% !important;
		width: 100% !important;
		margin-top: 0.5rem !important;
		margin-left: 0 !important;
		margin-bottom: 0.25rem !important;
		display: block !important;
		order: 6 !important;
		visibility: visible !important;
		opacity: 1 !important;
		position: relative !important;
		z-index: 1 !important;
	}
	
	
	/* view 모드 - h4 내부의 작성자 정보는 제목 아래로 이동 - 모바일에서만 */
	.title-author-wrapper h4 {
		width: 100% !important;
		margin-bottom: 0.5rem !important;
		font-size: 1.25rem !important;
		line-height: 1.5 !important;
		display: flex !important;
		flex-direction: column !important;
		gap: 0.5rem !important;
	}
	
	.title-author-wrapper h4 .title-text {
		font-size: 1.25rem !important;
		font-weight: bold !important;
		display: block !important;
	}
	
	h4 .author-info {
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: wrap !important;
		align-items: center !important;
		gap: 0.25rem !important;
		margin-left: 0 !important;
	}
	
	h4 .author-info > span {
		white-space: nowrap !important;
		font-size: 0.9rem !important;
		margin: 0 0.25rem !important;
	}
	
	/* 입력 필드 최적화 */
	.form-control,
	.form-select,
	input[type="text"],
	input[type="date"],
	input[type="number"],
	textarea,
	select {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
	
	/* 카드 내부 select 요소 최적화 */
	.mobile-card select,
	.mobile-card .form-select,
	.mobile-card .product-select {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
		white-space: nowrap !important;
	}
	
	/* Select2 컨테이너 최적화 */
	.mobile-card .select2-container {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
	
	.mobile-card .select2-selection {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow: hidden !important;
	}
	
	.mobile-card .select2-selection__rendered {
		width: 100% !important;
		max-width: 100% !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
		white-space: nowrap !important;
	}
	
	.mobile-card .select2-dropdown {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		padding: 0.5rem !important;
		font-size: 1rem !important;
		margin: 0.25rem 0 !important;
		box-sizing: border-box !important;
	}
	
	/* 버튼 그룹 최적화 */
	.btn-group {
		display: flex !important;
		flex-direction: column !important;
		width: 100% !important;
	}
	
	.btn-group button {
		width: 100% !important;
		margin: 0.25rem 0 !important;
	}
	
	/* 테이블을 카드 형식으로 변환 */
	.table-responsive {
		overflow-x: visible !important;
	}
	
	.table {
		display: none !important;
	}
	
	.table thead {
		display: none !important;
	}
	
	.table tbody {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.table tbody tr {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		margin-bottom: 0.75rem !important;
		border: 1px solid #ddd !important;
		border-radius: 0.5rem !important;
		padding: 0.75rem !important;
		background: #f8f9fa !important;
		box-sizing: border-box !important;
	}
	
	.table tbody td {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 0 !important;
		text-align: left !important;
		border: none !important;
		font-size: 0.9rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		box-sizing: border-box !important;
	}
	
	.table tbody td:before {
		content: attr(data-label) ": ";
		font-weight: bold !important;
		color: #007bff !important;
		margin-right: 0.5rem !important;
		display: inline-block !important;
	}
	
	/* tfoot 최적화 */
	.table tfoot {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		margin-top: 1rem !important;
	}
	
	.table tfoot tr {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		border: 2px solid #0dcaf0 !important;
		border-radius: 0.5rem !important;
		padding: 0.75rem !important;
		background: #d1ecf1 !important;
		box-sizing: border-box !important;
	}
	
	.table tfoot td {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 0 !important;
		text-align: left !important;
		border: none !important;
		font-size: 0.9rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		box-sizing: border-box !important;
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
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
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
	
	/* Select2 최적화 */
	.select2-container {
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.select2-selection {
		width: 100% !important;
		max-width: 100% !important;
	}
	
	/* d-flex 최적화 */
	.d-flex {
		flex-wrap: wrap !important;
	}
	
	.d-flex.align-items-center {
		align-items: flex-start !important;
	}
	
	/* 작은 화면에서 세로 배치 (카드 내부 제외) */
	.d-flex.justify-content-center:not(.item-row .d-flex):not(.cost-row .d-flex):not(.row-number-wrapper),
	.d-flex.justify-content-between:not(.item-row .d-flex):not(.cost-row .d-flex):not(.row-number-wrapper),
	.d-flex.justify-content-start:not(.item-row .d-flex):not(.cost-row .d-flex):not(.row-number-wrapper),
	.d-flex.justify-content-end:not(.item-row .d-flex):not(.cost-row .d-flex):not(.row-number-wrapper) {
		flex-direction: column !important;
		align-items: stretch !important;
		gap: 0.5rem !important;
	}
	
	/* 카드 내부의 번호 영역은 무조건 가로 배치 */
	.item-row .d-flex.justify-content-center,
	.cost-row .d-flex.justify-content-center,
	.item-row .row-number-wrapper,
	.cost-row .row-number-wrapper {
		flex-direction: row !important;
		flex-wrap: nowrap !important;
	}
	
	/* 테이블 헤더 숨기기 */
	#mainTable thead,
	#otherCostsTable thead {
		display: none !important;
	}
	
	/* 테이블 행을 카드 형식으로 표시 */
	.item-row,
	.cost-row {
		display: block !important;
		width: 100% !important;
		max-width: 100% !important;
		margin-bottom: 1rem !important;
		padding: 1rem !important;
		border: 1px solid #ddd !important;
		border-radius: 0.5rem !important;
		background: #f8f9fa !important;
		box-sizing: border-box !important;
	}
	
	.item-row td,
	.cost-row td {
		display: grid !important;
		grid-template-columns: 35% 65% !important;
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.75rem 0 !important;
		text-align: left !important;
		border: none !important;
		border-bottom: 1px solid #e0e0e0 !important;
		box-sizing: border-box !important;
		gap: 0.5rem !important;
		align-items: center !important;
	}
	
	.item-row td:last-child,
	.cost-row td:last-child {
		border-bottom: none !important;
	}
	
	.item-row td:before,
	.cost-row td:before {
		content: attr(data-label) ": ";
		font-weight: bold !important;
		color: #007bff !important;
		display: block !important;
		grid-column: 1 !important;
		padding-right: 0.5rem !important;
	}
	
	/* 카드 내부 값 영역 */
	.item-row td > *:not(:before),
	.cost-row td > *:not(:before) {
		grid-column: 2 !important;
		width: 100% !important;
	}
	
	/* 입력 필드와 select 최적화 */
	.item-row td input,
	.item-row td select,
	.cost-row td input,
	.cost-row td select {
		width: 100% !important;
		max-width: 100% !important;
	}
	
	/* 입력 필드 최적화 */
	.item-row input,
	.item-row select,
	.cost-row input,
	.cost-row select {
		width: 100% !important;
		max-width: 100% !important;
		min-height: 44px !important;
		font-size: 1rem !important;
		padding: 0.5rem !important;
		box-sizing: border-box !important;
	}
	
	/* Select2 최적화 (상품 선택) */
	.select2-container {
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.select2-selection {
		width: 100% !important;
		max-width: 100% !important;
		min-height: 44px !important;
		padding: 0.5rem !important;
		font-size: 1rem !important;
	}
	
	.select2-selection__rendered {
		width: 100% !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
	
	.select2-dropdown {
		width: 100% !important;
		max-width: 100vw !important;
	}
	
	/* 행 최적화 */
	.row {
		margin: 0 !important;
		flex-direction: column !important;
	}
	
	.row > [class*="col-"] {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem !important;
		margin-bottom: 0.5rem !important;
	}
	
	/* 합계 테이블 모바일 최적화 - 하나의 카드로 합치기 */
	.total-summary-table {
		display: block !important;
		width: 100% !important;
		border: 2px solid #007bff !important;
		border-radius: 0.5rem !important;
		background: #f0f8ff !important;
		padding: 1rem !important;
		margin-bottom: 1rem !important;
		box-sizing: border-box !important;
	}
	
	.total-summary-table tbody,
	.total-summary-table tr {
		display: flex !important;
		flex-direction: column !important;
		width: 100% !important;
		gap: 0.75rem !important;
	}
	
	/* 모든 td를 하나의 카드 안에 배치 */
	.total-summary-table td {
		display: flex !important;
		justify-content: space-between !important;
		align-items: center !important;
		width: 100% !important;
		padding: 0.75rem !important;
		border: none !important;
		border-bottom: 1px solid #cce5ff !important;
		text-align: left !important;
		box-sizing: border-box !important;
		background: white !important;
		border-radius: 0.25rem !important;
		margin: 0 !important;
	}
	
	.total-summary-table td:last-child {
		border-bottom: none !important;
	}
	
	/* 합계 테이블 라벨 제거 및 간단한 표시 */
	.total-summary-table td:before {
		content: "" !important;
		display: none !important;
	}
	
	/* 부가세 별도 - 첫 번째와 두 번째 td를 하나로 합치기 */
	.total-summary-table td:nth-child(1) {
		display: none !important;
	}
	
	.total-summary-table td:nth-child(2) {
		display: flex !important;
		order: 1 !important;
		justify-content: space-between !important;
		align-items: center !important;
	}
	
	.total-summary-table td:nth-child(2) .fw-semibold {
		display: none !important;
	}
	
	.total-summary-table td:nth-child(2) .d-flex {
		display: flex !important;
		justify-content: flex-end !important;
		align-items: center !important;
		margin: 0 !important;
		padding: 0 !important;
		flex: 0 0 40% !important;
		min-width: 0 !important;
	}
	
	.total-summary-table td:nth-child(2):before {
		content: "부가세 별도" !important;
		display: inline-block !important;
		font-size: 0.9rem !important;
		font-weight: 600 !important;
		color: #333 !important;
		flex: 0 0 60% !important;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
	}
	
	.total-summary-table td:nth-child(2) .total-ex-vat {
		display: inline-block !important;
		font-size: 1.1rem !important;
		font-weight: bold !important;
		color: #000 !important;
		margin-left: 0.5rem !important;
	}
	
	/* 부가세 포함 - 세 번째와 네 번째 td를 하나로 합치기 */
	.total-summary-table td:nth-child(3) {
		display: none !important;
	}
	
	.total-summary-table td:nth-child(4) {
		display: flex !important;
		order: 2 !important;
		border-bottom: none !important;
		justify-content: space-between !important;
		align-items: center !important;
	}
	
	.total-summary-table td:nth-child(4) .fw-semibold {
		display: none !important;
	}
	
	.total-summary-table td:nth-child(4) .d-flex {
		display: flex !important;
		justify-content: flex-end !important;
		align-items: center !important;
		margin: 0 !important;
		padding: 0 !important;
		flex: 0 0 40% !important;
		min-width: 0 !important;
	}
	
	.total-summary-table td:nth-child(4):before {
		content: "부가세 포함" !important;
		display: inline-block !important;
		font-size: 0.9rem !important;
		font-weight: 600 !important;
		color: #007bff !important;
		flex: 0 0 60% !important;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
	}
	
	.total-summary-table td:nth-child(4) .total-inc-vat {
		display: inline-block !important;
		font-size: 1.1rem !important;
		font-weight: bold !important;
		color: #007bff !important;
		margin-left: 0.5rem !important;
	}
	
	/* 모바일 카드 스타일 */
	.mobile-cards-container {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0.5rem 0 !important;
	}
	
	.mobile-card {
		border: 1px solid #ddd !important;
		border-radius: 0.5rem !important;
		padding: 0.75rem !important;
		margin-bottom: 0.75rem !important;
		background: #f8f9fa !important;
		box-sizing: border-box !important;
	}
	
	.mobile-card strong {
		color: #007bff !important;
		margin-right: 0.5rem !important;
		font-size: 0.9rem !important;
	}
	
	.mobile-card span {
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		font-size: 0.8em !important;
	}
	
	/* 버튼 최적화 - 기본적으로는 최소 너비로 설정 */
	button,
	.btn {
		width: auto !important;
		min-width: fit-content !important;
		max-width: none !important;
		margin: 0.25rem !important;
		padding: 0.5rem 0.75rem !important;
		font-size: 0.85rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		min-height: 44px !important;
		flex: 0 0 auto !important;
	}
	
	/* 버튼이 있는 컨테이너는 가로 배치 */
	.d-flex.justify-content-between.align-items-center,
	.d-flex.justify-content-end.align-items-center,
	.d-flex.justify-content-start.align-items-center {
		flex-direction: row !important;
		flex-wrap: wrap !important;
		justify-content: flex-start !important;
		align-items: center !important;
		gap: 0.5rem !important;
	}
	
	/* 제목과 버튼이 같은 줄에 있을 때 */
	.d-flex.justify-content-between.align-items-center h4 {
		margin-bottom: 0 !important;
		margin-right: auto !important;
	}
	
	/* 행 번호와 버튼 그룹 영역 최적화 - 한 행에 표시 */
	.item-row td.row-number-cell,
	.cost-row td.row-number-cell,
	.cost-row td.row-function-cell {
		display: block !important;
	}
	
	.item-row td.row-number-cell .row-number-wrapper,
	.cost-row td.row-number-cell .row-number-wrapper,
	.item-row td[data-label="No."] .d-flex,
	.cost-row td[data-label="No."] .d-flex {
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: nowrap !important;
		align-items: center !important;
		gap: 0.5rem !important;
		padding: 0.5rem !important;
		width: 100% !important;
		justify-content: flex-start !important;
	}
	
	.item-row td.row-number-cell .row-number-wrapper > span,
	.cost-row td.row-number-cell .row-number-wrapper > span,
	.item-row td[data-label="No."] .d-flex > span,
	.cost-row td[data-label="No."] .d-flex > span {
		width: auto !important;
		text-align: left !important;
		font-size: 0.9rem !important;
		font-weight: bold !important;
		padding: 0.375rem 0.75rem !important;
		background: #e3f2fd !important;
		border-radius: 0.25rem !important;
		flex: 0 0 auto !important;
		white-space: nowrap !important;
		margin: 0 !important;
	}
	
	.item-row td.row-number-cell .row-number-wrapper > .btn-group,
	.cost-row td.row-number-cell .row-number-wrapper > .btn-group,
	.item-row td[data-label="No."] .d-flex > .btn-group,
	.cost-row td[data-label="No."] .d-flex > .btn-group {
		width: auto !important;
		flex: 0 0 auto !important;
		margin-left: auto !important;
		display: flex !important;
		flex-direction: row !important;
		flex-wrap: nowrap !important;
	}
	
	.item-row .btn-group button,
	.cost-row .btn-group button {
		width: 36px !important;
		min-width: 36px !important;
		max-width: 36px !important;
		height: 36px !important;
		min-height: 36px !important;
		padding: 0.375rem !important;
		font-size: 0.875rem !important;
		margin: 0 !important;
		border-radius: 0.25rem !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		flex: 0 0 36px !important;
		flex-shrink: 0 !important;
		flex-grow: 0 !important;
		box-sizing: border-box !important;
		overflow: visible !important;
	}
}
</style>
</head>		 
<body>

<?php
$pdo = db_connect();

// GET 파라미터 처리
$mode = $_REQUEST["mode"] ?? 'insert';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? 'phomi_order';

// JSON 데이터 파싱
$items = [];
$other_costs = [];
$discount_items = [];
$discount_other_costs = [];

// 복사 모드일 때는 num을 초기화 (새로운 수주서로 저장하기 위해)
if($mode == 'copy') {
    $original_num = $num; // 원본 num 보관
    $num = ''; // 새로운 수주서를 위해 num 초기화
}

// 견적서에서 전달된 estimate_data 처리
$estimate_data = null;
$estimate_data_error = null;

// POST와 GET 모두에서 estimate_data 확인
if (isset($_POST['estimate_data']) && !empty($_POST['estimate_data'])) {
    $estimate_data = json_decode($_POST['estimate_data'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $estimate_data_error = '견적서 데이터 파싱 오류: ' . json_last_error_msg();
        $estimate_data = null;
    }
} elseif (isset($_GET['estimate_data']) && !empty($_GET['estimate_data'])) {
    $estimate_data = json_decode($_GET['estimate_data'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $estimate_data_error = '견적서 데이터 파싱 오류: ' . json_last_error_msg();
        $estimate_data = null;
    }
}

// 견적서 데이터 유효성 검증
if ($estimate_data && !is_array($estimate_data)) {
    $estimate_data_error = '견적서 데이터 형식이 올바르지 않습니다.';
    $estimate_data = null;
}

$order_date = date('Y-m-d');

// 데이터 조회
$order_data = null;
if(($mode == 'view' || $mode == 'modify' || $mode == 'copy')) {
    $query_num = ($mode == 'copy') ? $original_num : $num;
    if(!empty($query_num)) {
        try {
            $sql = "SELECT * FROM {$DB}.phomi_order WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $query_num, PDO::PARAM_INT);
            $stmt->execute();
            $order_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // 기본값 설정
            $order_date = $order_data['order_date'] ?? date('Y-m-d');
            $recipient = $order_data['recipient'] ?? '';
            $division = $order_data['division'] ?? '';
            $site_name = $order_data['site_name'] ?? '';
            $signed_by = $order_data['signed_by'] ?? '소현철';
            $payment_account = $order_data['payment_account'] ?? '중소기업은행 339-084210-01-012 ㈜ 미래기업';

            // 작성자 정보 설정
            if(empty($order_data['author_id']) && $_SESSION["level"] < 6) {
                $author_id = $order_data['author_id'] ?? 'mirae';
                $author = $order_data['author'] ?? '소현철';  // 미래기업 직원일 경우는 사장님 이름이 표시되도록 함.
            }
            else
            {
                if(empty($order_data['author_id'])) {
                    $author_id = $_SESSION["userid"]; // 작성자 아이디
                    $author = $_SESSION["name"]; // 작성자 이름
                }
                else {
                    $author_id = $order_data['author_id']; // 작성자 아이디
                    $author = $order_data['author']; // 작성자 이름
                }
            }

            // 견적서 번호
            $estimate_num = $order_data['estimate_num'] ?? '';

            // 합계금액
            $total_supply = $order_data['total_supply'] ?? 0;
            $total_tax = $order_data['total_tax'] ?? 0;
            $total_ex_vat = $order_data['total_ex_vat'] ?? 0;
            $total_inc_vat = $order_data['total_inc_vat'] ?? 0;

            // 수주 관련 추가 필드들
            $order_confirm_date = $order_data['order_confirm_date'] ?? '';
            $delivery_due_date = $order_data['delivery_due_date'] ?? '';
            $delivery_date = $order_data['delivery_date'] ?? '';
            $order_close_date = $order_data['order_close_date'] ?? '';

            // 회계 처리 날짜
            $payment_date_head = $order_data['payment_date_head'] ?? '';
            $payment_date_dealer = $order_data['payment_date_dealer'] ?? '';
            $tax_invoice_date = $order_data['tax_invoice_date'] ?? '';
            $deposit_date = $order_data['deposit_date'] ?? '';

            // 회계 금액 정보
            $purchase_unit_price = $order_data['purchase_unit_price'] ?? 0;
            $purchase_total = $order_data['purchase_total'] ?? 0;
            $head_balance = $order_data['head_balance'] ?? 0;
            $dealer_unit_price = $order_data['dealer_unit_price'] ?? 0;
            $dealer_amount = $order_data['dealer_amount'] ?? 0;
            $dealer_total = $order_data['dealer_total'] ?? 0;
            $dealer_fee = $order_data['dealer_fee'] ?? 0;
            $company_unit_price = $order_data['company_unit_price'] ?? 0;
            $company_amount = $order_data['company_amount'] ?? 0;
            $tax_invoice_amount = $order_data['tax_invoice_amount'] ?? 0;
            $tax_diff = $order_data['tax_diff'] ?? 0;
            $is_paid = $order_data['is_paid'] ?? '';
            $note = $order_data['note'] ?? '';
            $recipient_name = $order_data['recipient_name'] ?? '';
            $recipient_phone = $order_data['recipient_phone'] ?? '';

            //할인 상품 내역
            $discount_items = $order_data['discount_items'] ?? [];
            $discount_other_costs = $order_data['discount_other_costs'] ?? [];

            // 체크박스 상태 변수
            $exclude_construction_cost = $order_data['exclude_construction_cost'] ?? '0';
            $exclude_molding = $order_data['exclude_molding'] ?? '0';
            $etc_autocheck = $order_data['etc_autocheck'] ?? '0';

            if($order_data) {
                if(!empty($order_data['items'])) {
                    $items = json_decode($order_data['items'], true) ?? [];
                }
                if(!empty($order_data['other_costs'])) {
                    $other_costs = json_decode($order_data['other_costs'], true) ?? [];
                }
                if(!empty($order_data['discount_items'])) {
                    $discount_items = json_decode($order_data['discount_items'], true) ?? [];
                }
                if(!empty($order_data['discount_other_costs'])) {
                    $discount_other_costs = json_decode($order_data['discount_other_costs'], true) ?? [];
                }
                // 시공비 제외 체크 및 몰딩 제외체크 가져오기
                $exclude_construction_cost = $order_data['exclude_construction_cost'] ?? '0';
                $exclude_molding = $order_data['exclude_molding'] ?? '0';
                $etc_autocheck = $order_data['etc_autocheck'] ?? '1';            
            }            

        } catch (PDOException $e) {
            echo "오류: " . $e->getMessage();
        }
    }
}
else {
    // insert 모드일 때 기본값 설정
    $order_date = date('Y-m-d');
    $recipient = '';
    $division = '';
    $site_name = '';
    $signed_by = $_SESSION["name"] ?? '소현철';
    $payment_account = '중소기업은행 339-084210-01-012 ㈜ 미래기업';
    

    if(empty(!$_SESSION["userid"]) && $_SESSION["level"] < 6) {
        $author_id = 'mirae';
        $author = '소현철';  // 미래기업 직원일 경우는 사장님 이름이 표시되도록 함.
        $hp = '010-3784-5438';
        $signer = '소현철'; // 공급자에 표시되는 이름
    }
    else
    {
        $author_id = $_SESSION["userid"]; // 작성자 아이디
        $author = $_SESSION["name"]; // 작성자 이름
        $hp = $_SESSION["hp"] ?? '010-3784-5438';
        $signer = $_SESSION["name"] ?? '소현철'; // 공급자에 표시되는 이름
    }    
    
    // 견적서에서 전달된 데이터가 있으면 기본값으로 설정
    if ($estimate_data && is_array($estimate_data)) {
        // 견적서 데이터를 수주서 데이터로 변환
        // $converted_data = EstimateDataProcessor::convertEstimateToOrder($estimate_data);        
        
        $recipient = $estimate_data['recipient'];
        $site_name = $estimate_data['site_name'];
        $signed_by = $estimate_data['signed_by'];
        $division = $estimate_data['division'];
        $note = $estimate_data['note'];
        $order_date = $estimate_data['order_date'];
        $payment_account = $estimate_data['payment_account'] ?? '중소기업은행 339-084210-01-012 ㈜ 미래기업';
        $etc_autocheck = $estimate_data['etc_autocheck'] ?? '1';  // 기타비용 자동산출 체크박스 기본은 체크
        // 작성자 정보
        $author_id = $estimate_data['author_id'] ?? 'mirae';
        $author = $estimate_data['author'] ?? '소현철';
        $hp = $estimate_data['hp'] ?? '010-3784-5438';
        $signer = $estimate_data['signer'] ?? '소현철'; // 공급자에 표시되는 이름

        // 견적서 번호
        $estimate_num = $estimate_data['estimate_num'] ?? '';        

    }

    // 수주 관련 추가 필드들
    $order_confirm_date = '';
    $delivery_due_date = '';
    $delivery_date = '';
    $order_close_date = '';

    // 회계 처리 날짜
    $payment_date_head = '';
    $payment_date_dealer = '';
    $tax_invoice_date = '';
    $deposit_date = '';

    // 회계 금액 정보
    $purchase_unit_price = 0;
    $purchase_total = 0;
    $head_balance = 0;
    $dealer_unit_price = 0;
    $dealer_amount = 0;
    $dealer_total = 0;
    $dealer_fee = 0;
    $company_unit_price = 0;
    $company_amount = 0;
    $tax_invoice_amount = 0;
    $tax_diff = 0;
    $is_paid = '';
    $note = '';

    // 체크박스 상태 변수
    $exclude_construction_cost = '0';
    $exclude_molding = '0';
    $etc_autocheck = '1'; // 기타비용 자동계산 체크

}

$isFromQuote = false; // 견적서에서 가져온 경우 1, 아닌 경우 0

if ($estimate_data) {
    // 견적서에서 전달된 데이터가 있으면 items와 other_costs 설정
    if(!empty($estimate_data['items'])) {
        $items = $estimate_data['items'];
    }
    if(!empty($estimate_data['other_costs'])) {
        $other_costs = $estimate_data['other_costs'];
        
        // 견적서에서 전달된 본드 가격 확인 및 저장
        foreach($other_costs as $cost) {
            if(isset($cost['item']) && strpos($cost['item'], '본드') !== false) {
                $estimate_bond_price = $cost['unit_price'] ?? 5000; // 견적서의 본드 가격 저장
                $estimate_bond_quantity = $cost['quantity'] ?? 1; // 견적서의 본드 수량 저장
                echo "<!-- 견적서에서 전달된 본드 가격: " . $estimate_bond_price . ", 수량: " . $estimate_bond_quantity . " -->";
                break;
            }
        }
    }
    if(!empty($estimate_data['discount_items'])) {
        $discount_items = json_decode($estimate_data['discount_items'], true) ?? [];
    }
    if(!empty($estimate_data['discount_other_costs'])) {
        $discount_other_costs = json_decode($estimate_data['discount_other_costs'], true) ?? [];
    }    
    // 시공비 제외 체크 및 몰딩 제외체크 가져오기
    $exclude_construction_cost = $estimate_data['exclude_construction_cost'] ?? '0';
    $exclude_molding = $estimate_data['exclude_molding'] ?? '0';
    $etc_autocheck = $estimate_data['etc_autocheck'] ?? '1';
    
    // 현장명, 수신처, 구분 수주일자 가져오기
    $site_name = $estimate_data['site_name'] ?? '';
    $recipient = $estimate_data['recipient'] ?? '';
    $division = $estimate_data['division'] ?? '';    
    $order_date = date('Y-m-d');

    // 견적서 번호
    $estimate_num = $estimate_data['estimate_num'] ?? '';
    // 견적서에서 가져온 경우 1, 아닌 경우 0
    $isFromQuote = true;    

    // 받는 분 연락처
    $recipient_phone = $estimate_data['recipient_phone'] ?? '';    

    // echo '$estimate_data recipient_phone: ' . $recipient_phone;
}

// 견적서에서 전달된 본드 가격을 JavaScript에서 사용할 수 있도록 설정
$estimate_bond_price = $estimate_bond_price ?? 5000; // 기본값 5000원
$estimate_bond_quantity = $estimate_bond_quantity ?? 1; // 기본값 1개

// echo '<pre>';
// print_r($order_data);
// echo '</pre>';

// echo '<pre>';
// print_r($estimate_data);
// echo '</pre>';

// echo '<pre>';
// print_r($exclude_construction_cost);
// print_r($exclude_molding);
// echo '</pre>';

// echo '<pre>';
// print_r($items);
// echo '</pre>';

// echo '<pre>';
// print_r($other_costs);
// echo '</pre>';

// 보기 모드에서 합계 미리 계산

// echo 'author: ' . $author . ' hp: ' . $hp;

?>

<form method="post" id="orderForm">
    <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">    
    <input type="hidden" id="num" name="num" value="<?= $num ?>">    
    <input type="hidden" id="total_supply" name="total_supply" value="<?= $total_supply ?>">
    <input type="hidden" id="total_tax" name="total_tax" value="<?= $total_tax ?>">
    <input type="hidden" id="total_ex_vat" name="total_ex_vat" value="<?= $total_ex_vat ?>">
    <input type="hidden" id="total_inc_vat" name="total_inc_vat" value="<?= $total_inc_vat ?>">    
    <input type="hidden" id="isFromQuote" name="isFromQuote" value="<?= $isFromQuote ?>"> <!-- 견적서에서 가져온 경우 1, 아닌 경우 0 -->

<div class="container-fluid my-2">
    <div class="card shadow-sm ">
        <div class="card-body p-4">
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3 fs-4">
                <div class="d-flex align-items-center title-author-wrapper">
                    <span class="title-text">
                        <?php 
                        if($mode == 'insert') echo '포미스톤 수주서 작성';
                        elseif($mode == 'modify') echo '포미스톤 수주서 수정';
                        elseif($mode == 'copy') echo '포미스톤 수주서 복사';
                        ?>
                    </span>
                    <div class="author-info">
                        <span class="ms-5 fs-6">작성자 : </span>
                        <input class="form-control form-control-sm ms-2 me-2 w100px fs-6 fw-bold" id="author" name="author" type="text" value="<?= htmlspecialchars($author) ?>" >                    
                        <span class="ms-1 fs-6">작성자ID :</span>
                        <input class="form-control form-control-sm ms-2 me-2 w150px fs-6 fw-bold" id="author_id" name="author_id" type="text" value="<?= htmlspecialchars($author_id) ?>" >
                        <span class="estimate-num-break"></span>
                        <span class="ms-1 fs-6 estimate-num-label">견적번호 :</span>
                        <input class="form-control form-control-sm ms-2 me-2 w50px fs-6" id="estimate_num" name="estimate_num" type="text" value="<?= htmlspecialchars($estimate_num) ?>" >                    
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <!-- 모바일 전용 계산하기 버튼 -->
                    <button type="button" id="mobileCalculateBtn" class="btn btn-success btn-sm me-2 mobile-only-btn" onclick="recalculateAllMobile()">계산하기</button>
                    <button type="button" id="saveBtn" class="btn btn-primary btn-sm me-2">저장</button>
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="generatePDF()">PDF 저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($mode == 'view'): ?>
                <input type="hidden" id="author_id" name="author_id" value="<?= $author_id ?>">
                <input type="hidden" id="author" name="author" value="<?= $author ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center title-author-wrapper">
                <h4>
                    <span class="title-text">포미스톤 수주서 보기</span>
                    <div class="author-info">
                        <span class="ms-5 fs-6">작성자 : <?= htmlspecialchars($author) ?></span> 
                        <span class="ms-1 fs-6">작성자ID : <?= htmlspecialchars($author_id) ?></span>
                        <span class="estimate-num-break"></span>
                        <span class="ms-1 fs-6 estimate-num-label">견적번호 : <?= htmlspecialchars($estimate_num) ?></span>
                        <input type="hidden" id="estimate_num" name="estimate_num" value="<?= $estimate_num ?>">
                    </div>
                </h4>                
                </div>
                <div>
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="editOrder()">수정</button>
                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="copyOrder()">복사</button>
                    <button type="button" class="btn btn-danger btn-sm me-2" onclick="deleteBtn()">삭제</button>      
                    <button type="button" class="btn btn-warning btn-sm me-2" onclick="openEstimatePopup()">견적서 보기</button>
                    <button type="button" class="btn btn-info btn-sm me-2" onclick="convertToOutorder()">출고증 변환</button>                    
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="generatePDF()">PDF 저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($estimate_data_error): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>견적서 데이터 처리 오류:</strong> <?= htmlspecialchars($estimate_data_error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if($estimate_data && !$estimate_data_error): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>견적서 데이터가 성공적으로 로드되었습니다.</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="content-to-print">

<div class="container-fluid my-3">
<div class="card shadow-sm mb-4 ">
<div class="card-body p-4">    
    <!-- 헤더 -->
    <div class="text-center mb-4">
        <h1 class="h2 fw-bold mb-2">수주서</h1>                
    </div>

    <!-- 수신 & 공급자 정보 -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="border rounded p-1 h-100">                        
                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <p class="mb-1">
                        <label for="recipient">수신 : </label>
                        <input type="text" id="recipient" name="recipient" value="<?= htmlspecialchars($recipient) ?>" class="form-control form-control-sm" placeholder="수신처명">
                    </p>
                    <p class="mb-1">
                        <label for="division">구분 : </label>
                        <select id="division" name="division" class="form-select form-select-sm w-auto">
                            <option value="유통" <?= ($division == '유통' || empty($division)) ? 'selected' : '' ?>>유통</option>
                            <option value="소비자" <?= ($division == '소비자') ? 'selected' : '' ?>>소비자</option>
                        </select>
                    </p>
                    <p class="mb-1">현장명 : <input type="text" name="site_name" value="<?= htmlspecialchars($site_name) ?>" class="form-control form-control-sm" placeholder="현장명"></p>
                    <p class="mb-1">전화번호 : 010-3784-5438</p>
                    <p class="mb-1"><label for="order_date">수주일자 : </label>
                        <input type="date" id="order_date" name="order_date" value="<?= $order_date ?>" class="form-control form-control-sm w-auto">
                    </p>
                    <p class="mb-1">
                        <label for="recipient_name">물건 받는분 : </label>
                        <input type="text" id="recipient_name" name="recipient_name" value="<?= htmlspecialchars($recipient_name) ?>" class="form-control form-control-sm w-auto" placeholder="받는 분 성함">
                    </p>
                    <p class="mb-1">
                        <label for="recipient_phone">물건 받는분 전화번호 : </label>
                        <input type="text" id="recipient_phone" name="recipient_phone" value="<?= htmlspecialchars($recipient_phone) ?>" class="form-control form-control-sm w-auto" placeholder="받는 분 연락처">
                    </p>
                    
                    <p class="mb-0 mt-3 text-center"> <strong> 아래와 같이 수주합니다.</strong></p>                           

                <?php else: ?>
                    <input type="hidden" id="order_date" name="order_date" value="<?= $order_date ?>">
                    <!-- 수주서 보기 모드 -->
                    <p class="mb-1" style="font-size: 1.2em">수신 : <u id="recipient-text"><?= htmlspecialchars($recipient) ?></u> 귀하</p>
                    <p class="mb-1">구분 : <u id="division-text"><?= htmlspecialchars($division) ?></u></p>
                    <p class="mb-1">현장명 : <u id="site-name-text"><?= htmlspecialchars($site_name) ?></u></p>
                    <p class="mb-1">전화번호 : <u id="contact-text"></u>010-3784-5438</u></p>
                    <p class="mb-1">수주일자 : <u id="order-date-text"><?= date('Y년 m월 d일', strtotime($order_date)) ?></u></p>
                    <p class="mb-1">물건 받는분 : <u id="recipient-name-text"><?= htmlspecialchars($recipient_name) ?></u></p>
                    <p class="mb-1">물건 받는분 전화번호 : <u id="recipient-phone"><?= htmlspecialchars($recipient_phone) ?></u></p>

                    <p class="mb-0 mt-3 text-center"> <strong> 아래와 같이 수주합니다.</strong></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-8 mb-3 supplier-info">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size: 12px; border-collapse: collapse;">
                    <tr>
                        <td class="text-center align-middle bg-light" style="width: 30px; vertical-align: middle; border: 1px solid #000;" rowspan="5">
                            <strong>공급자</strong>
                        </td>
                        <td class="bg-light text-center" style="width: 70px; border: 1px solid #000;">상 호</td>
                        <td colspan="3" class="text-center" style="border: 1px solid #000;"> <strong>주식회사 미래기업</strong></td>                                
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">사업자번호</td>
                        <td class="text-center" style="border: 1px solid #000;">722-88-00035</td>
                        <td class="bg-light text-center" style="width: 80px; border: 1px solid #000;">대 표</td>
                        <td class="text-center" style="border: 1px solid #000;">소현철</td>
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">주 소</td>
                        <td colspan="3" class="text-start" style="border: 1px solid #000;">
                            본사 : 경기도 김포시 양촌읍 흥신로 220-27<br>
                            전시장 : 인천광역시 서구 중봉대로 393번길 16 홈씨씨2층 포미스톤
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">업 태</td>
                        <td class="text-center" style="border: 1px solid #000;">제조업</td>
                        <td class="bg-light text-center" style="border: 1px solid #000;">종 목</td>
                        <td class="text-center" style="border: 1px solid #000;">엘리베이터의장품</td>
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">담당자</td>
                        <td class="text-center" style="border: 1px solid #000;">
                            <span class="fw-bold signer-text"><?= htmlspecialchars($author) ?></span>
                        </td>
                        <td class="bg-light text-center" style="border: 1px solid #000;">연락처</td>
                        <td class="text-center" style="border: 1px solid #000;">
                            <span class="fw-bold hp-text"><?= htmlspecialchars($hp) ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- 합계 테이블 -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered mb-0 align-middle total-summary-table" style="font-size: 12px; border-collapse: collapse;">
            <tr>
                <td class="text-center bg-light" style="width: 50%; border: 1px solid #000;" data-label="합계금액(부가세별도)">
                    <div class="fw-semibold">합계금액(부가세별도)</div>
                </td>
                <td class="text-center bg-light" style="border: 1px solid #000;" data-label="금액">                            
                    <div class="d-flex justify-content-center align-items-center mt-1 fw-semibold">
                        <span class="total-ex-vat">(<?= number_format($total_ex_vat ?? 0) ?>)</span>
                    </div>
                </td>
                <td class="text-center bg-light" style="width: 50%; border: 1px solid #000;" data-label="합계금액(부가세포함)">
                    <div class="fw-semibold text-primary">합계금액(부가세포함)</div>
                </td>
                <td class="text-center bg-light" style="border: 1px solid #000;" data-label="금액">                            
                    <div class="d-flex justify-content-center align-items-center mt-1 fw-semibold">
                        <span class="total-inc-vat text-primary">(<?= number_format($total_inc_vat ?? 0) ?>)</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>  

    <!-- 상품 내역 테이블 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold mb-0">상품 내역</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">No.</th>
                        <th scope="col" style="width: 28%;">상품명</th>
                        <th scope="col" style="width: 8%;">규격</th>
                        <th scope="col" style="width: 12%;">분류</th>
                        <th scope="col" style="width: 7%;">수량EA</th>
                        <th scope="col" style="width: 6%;">m²</th>
                        <th scope="col" style="width: 8%;">단가</th>
                        <th scope="col" style="width: 6%;">공급가액</th>
                        <th scope="col" style="width: 6%;">세액</th>
                        <th scope="col" style="width: 10%;">비고</th>
                        <th scope="col" style="width: 5%;">할인</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php 
                        $item_count = max(1, count($items));
                        for($i = 0; $i < $item_count; $i++): ?>
                        <tr class="item-row" data-row="<?= $i ?>">
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-2"><?= $i + 1 ?></span>
                                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addItemRowAfter(<?= $i ?>)" title="아래에 행 추가">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyItemRow(<?= $i ?>)" title="행 복사">
                                            <i class="bi bi-files"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteItemRow(<?= $i ?>)" title="행 삭제">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: left;">
                                <select name="items[<?= $i ?>][product_code]" class="form-select form-select-sm product-select" data-row="<?= $i ?>" style="text-align: left;">
                                    <option value="">상품을 선택하세요</option>
                                    <?php
                                    // 단가표에서 상품 목록 가져오기
                                    try {
                                        $product_sql = "SELECT prodcode, texture_eng, texture_kor, design_eng, design_kor, type, size, thickness, area, dist_price_per_m2, retail_price_per_m2 FROM mirae8440.phomi_unitprice ORDER BY prodcode ASC";
                                        $product_stmt = $pdo->prepare($product_sql);
                                        $product_stmt->execute();
                                        
                                        while($product = $product_stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ($items[$i]['product_code'] ?? '') == $product['prodcode'] ? 'selected' : '';
                                            $display_name = $product['prodcode'] . ' - ' . $product['texture_kor'] . ' ' . $product['design_kor'] . ' (' . $product['texture_eng'] . ' ' . $product['design_eng'] . ')';
                                            echo '<option value="' . $product['prodcode'] . '" data-spec="' . htmlspecialchars($product['type']) . '" data-size="' . htmlspecialchars($product['size']) . '" data-thickness="' . htmlspecialchars($product['thickness']) . '" data-area="' . $product['area'] . '" data-unit-price="' . $product['dist_price_per_m2'] . '" ' . $selected . '>' . htmlspecialchars($display_name) . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo "상품 목록 조회 오류: " . $e->getMessage();
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="text" name="items[<?= $i ?>][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="<?= htmlspecialchars($items[$i]['specification'] ?? '') ?>" readonly></td>
                            <td><input type="text" name="items[<?= $i ?>][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="<?= htmlspecialchars($items[$i]['size'] ?? '') ?>" readonly></td>
                            <td><input type="number" name="items[<?= $i ?>][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="<?= $items[$i]['quantity'] ?? '1' ?>"></td>
                            <td><input type="text" name="items[<?= $i ?>][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="<?= $items[$i]['area'] ?? '' ?>" readonly></td>
                            <td><input type="text" name="items[<?= $i ?>][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="<?= number_format($items[$i]['unit_price'] ?? 0) ?>" oninput="inputNumber(this)"></td>
                            <td class="text-end supply-amount">
                                <?php 
                                $supply_amount = 0;
                                if(isset($items[$i]['quantity']) && isset($items[$i]['unit_price'])) {
                                    $supply_amount = floatval(str_replace(',', '', $items[$i]['area'])) * floatval(str_replace(',', '', $items[$i]['unit_price']));
                                }
                                echo '' . number_format($supply_amount);
                                ?>
                            </td>
                            <td class="text-end tax-amount">
                                <?php 
                                $tax_amount = $supply_amount * 0.1;
                                echo '' . number_format($tax_amount);
                                ?>
                            </td>
                            <td><input type="text" name="items[<?= $i ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($items[$i]['remarks'] ?? '') ?>"></td>
                            <td>
                                <!-- 할인버튼 추가 -->
                                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountItemRowAfter(<?= $i ?>)" title="할인 행 추가">
                                    할인
                                </button>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    <?php else: ?>
                    <?php
                    // view 모드에서 상품 목록 표시
                    $item_counter = 1;
                    $total_supply = 0;
                    $total_tax = 0;
                    foreach($items as $item): 
                        $supply_amount = floatval(str_replace(',', '', $item['area'])) * floatval(str_replace(',', '', $item['unit_price']));
                        $tax_amount = $supply_amount * 0.1;
                        
                        // 합계 계산
                        $total_supply += $supply_amount;
                        $total_tax += $tax_amount;
                        
                        // 상품명 표시
                        $display_product_name = '';
                        if(!empty($item['product_code'])) {
                            try {
                                $product_name_sql = "SELECT prodcode, texture_kor, design_kor FROM mirae8440.phomi_unitprice WHERE prodcode = :prodcode";
                                $product_name_stmt = $pdo->prepare($product_name_sql);
                                $product_name_stmt->bindParam(':prodcode', $item['product_code'], PDO::PARAM_STR);
                                $product_name_stmt->execute();
                                $product_info = $product_name_stmt->fetch(PDO::FETCH_ASSOC);
                                if($product_info) {
                                    $display_product_name = $product_info['texture_kor'] . ' ' . $product_info['design_kor'];
                                } else {
                                    $display_product_name = $item['product_name'] ?? '';
                                }
                            } catch (PDOException $e) {
                                $display_product_name = $item['product_name'] ?? '';
                            }
                        } else {
                            $display_product_name = $item['product_name'] ?? '';
                        }
                    ?>
                    <tr class="item-row-view">
                        <td><?= $item_counter ?></td>
                        <td class='product-code' style='display:none;'><?= $item['product_code'] ?></td>                        
                        <td class="text-start"><?= htmlspecialchars($display_product_name) ?></td>
                        <td><?= htmlspecialchars($item['specification'] ?? '') ?></td>
                        <td class="text-center"><?= htmlspecialchars($item['size'] ?? '') ?></td>
                        <td class="text-end quantity-input"><?= number_format($item['quantity'] ?? 0) ?></td>
                        <td class="text-end area-input"><?= number_format($item['area'] ?? 0, 2) ?></td>
                        <td class="text-end"><?= number_format(floatval(str_replace(',', '', $item['unit_price'] ?? 0))) ?></td>
                        <td class="text-end"><?= number_format($supply_amount) ?></td>
                        <td class="text-end"><?= number_format($tax_amount) ?></td>
                        <td><?= htmlspecialchars($item['remarks'] ?? '') ?></td>
                    </tr>
                    <?php 
                    $item_counter++;
                    endforeach; 
                    endif; ?>
                </tbody>
                <tfoot>     
                    <!-- 소계 행 -->                        
                    <tr class="table-secondary">
                        <td colspan="7" class="text-end fw-medium">소계</td>
                        <td class="text-end fw-bold" id="totalSupply"><?= number_format($total_supply) ?></td>
                        <td class="text-end fw-bold" id="totalTax"><?= number_format($total_tax) ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

    <!-- 기타 비용 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold mb-0">기타 비용 (부자재 및 인건비 등)</h6>
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <div class="form-check ms-3 d-flex align-items-center">
                <input class="form-check-input ms-5" type="checkbox" id="etc_autocheck" name="etc_autocheck" <?= $etc_autocheck == '1' ? 'checked' : '' ?> style="transform: scale(1.5);">
                <label class="form-check-label fs-6 ms-3 text-primary" for="etc_autocheck">
                    자동계산
                </label>
            </div>
            <div class="form-check ms-3 d-flex align-items-center">
                <input class="form-check-input ms-5" type="checkbox" id="exclude_construction_cost" name="exclude_construction_cost" <?= $exclude_construction_cost == '1' ? 'checked' : '' ?> style="transform: scale(1.5);">
                <label class="form-check-label fs-6 ms-3 text-primary" for="exclude_construction_cost">
                    시공비 제외
                </label>
            </div>
            <div class="form-check ms-3 d-flex align-items-center">
                <input class="form-check-input ms-5" type="checkbox" id="exclude_molding" name="exclude_molding" <?= $exclude_molding == '1' ? 'checked' : '' ?> style="transform: scale(1.5);">
                <label class="form-check-label fs-6 ms-3 text-primary" for="exclude_molding">
                    몰딩 제외
                </label>
            </div>
            <!-- <button type="button" class="btn btn-outline-warning btn-sm ms-3" id="recalculateOtherCostsBtn" title="기타비용 재계산">
                    <i class="bi bi-arrow-clockwise"></i> 재계산
            </button>                     -->
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="otherCostsTable">
                <thead class="table-light">
                    <tr>
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <th scope="col" style="width: 10%;">기능</th>
                        <?php endif; ?>
                            <th scope="col" style="width: 10%;">구분</th>
                            <th scope="col" style="width: 10%;">항목</th>
                            <th scope="col" style="width: 10%;">단위</th>
                            <th scope="col" style="width: 10%;">수량</th>
                            <th scope="col" style="width: 10%;">단가</th>
                            <th scope="col" style="width: 10%;">공급가액</th>
                            <th scope="col" style="width: 10%;">세액</th>
                            <th scope="col" style="width: 10%;">비고</th>
                            <th scope="col" style="width: 10%;">할인</th>
                    </tr>
                </thead>
                <tbody id="otherCostsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php                        
                        // 기존 데이터가 있으면 사용, 없으면 기본값 사용
                        $cost_count = max(1, count($other_costs));
                        for($c = 0; $c < $cost_count; $c++): 
                            $cost_data = $other_costs[$c] ?? $default_costs[$c] ?? $default_costs[0];
                        ?>
                        <tr class="cost-row" data-row="<?= $c ?>">
                            <td>
                                <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                                    <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(<?= $c ?>)" title="아래에 행 추가">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(<?= $c ?>)" title="행 복사">
                                        <i class="bi bi-files"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(<?= $c ?>)" title="행 삭제">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="<?= htmlspecialchars($cost_data['category'] ?? '') ?>">                                        
                            </td>
                            <td><input type="text" name="other_costs[<?= $c ?>][item]" class="form-control form-control-sm text-start" placeholder="항목" value="<?= htmlspecialchars($cost_data['item'] ?? '') ?>"></td>
                            <td><input type="text" name="other_costs[<?= $c ?>][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="<?= htmlspecialchars($cost_data['unit'] ?? '') ?>"></td>
                            <td><input type="number" name="other_costs[<?= $c ?>][quantity]" class="form-control form-control-sm text-end cost-quantity-input" placeholder="수량" step="1" value="<?= $cost_data['quantity'] ?? '' ?>"></td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][unit_price]" class="form-control form-control-sm text-end cost-unit-price-input" placeholder="단가" value="<?php
                                    $unit_price = $other_costs[$c]['unit_price'] ?? 0;
                                    echo is_numeric($unit_price) ? number_format($unit_price) : $unit_price;
                                ?>">
                            </td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][supply_amount]" class="form-control form-control-sm text-end cost-supply-amount" value="<?php
                                    $quantity = $other_costs[$c]['quantity'] ?? 0;
                                    $unit_price = $other_costs[$c]['unit_price'] ?? 0;
                                    $supply_amount = (is_numeric($quantity) && is_numeric($unit_price)) ? $quantity * $unit_price : 0;
                                    echo is_numeric($supply_amount) ? number_format($supply_amount) : $supply_amount;
                                ?>" readonly>
                            </td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][tax_amount]" class="form-control form-control-sm text-end cost-tax-amount" value="<?php
                                    $quantity = $other_costs[$c]['quantity'] ?? 0;
                                    $unit_price = $other_costs[$c]['unit_price'] ?? 0;
                                    $tax_amount = (is_numeric($quantity) && is_numeric($unit_price)) ? $quantity * $unit_price * 0.1 : 0;
                                    echo is_numeric($tax_amount) ? number_format($tax_amount) : $tax_amount;
                                ?>" readonly>
                            </td>
                            <td><input type="text" name="other_costs[<?= $c ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($cost_data['remarks'] ?? '') ?>"></td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px; font-size: 10px;" onclick="addDiscountCostRow(<?= $c ?>)" title="할인 행 추가">
                                    할인
                                </button>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    <?php else: // $mode == 'view' 
                        ?>                            
                    <!-- 체크박스 숨김형태 -->                    
                    <input type="hidden" id="etc_autocheck" name="etc_autocheck" value="<?= $etc_autocheck ?>">
                    <input type="hidden" id="exclude_construction_cost" name="exclude_construction_cost" value="<?= $exclude_construction_cost ?>">
                    <input type="hidden" id="exclude_molding" name="exclude_molding" value="<?= $exclude_molding ?>">
                    <?php
                    // view 모드에서 기타비용 목록 표시 (최대 4개만)
                    if(!empty($other_costs)):
                    $view_cost_count = 0;
                    $total_supply_amount = 0;
                    $total_tax_amount = 0;
                    foreach($other_costs as $cost):
                        if($view_cost_count >= 4) break; // 최대 4개만 표시
                        $view_cost_count++;
                        $cost_supply_amount = 0;
                        $cost_tax_amount = 0;
                        
                        if(isset($cost['quantity']) && isset($cost['unit_price']) && 
                            is_numeric($cost['quantity']) && is_numeric($cost['unit_price'])) {
                            
                            if(isset($cost['unit']) && ($cost['unit'] === '㎡' || $cost['unit'] === 'm²')) {
                                if($cost['quantity'] > 28) {
                                    $cost_supply_amount = $cost['quantity'] * $cost['unit_price'];
                                } else {
                                    $cost_supply_amount = $cost['unit_price'];
                                }
                            } else {
                                $cost_supply_amount = $cost['quantity'] * $cost['unit_price'];
                            }
                            
                            $cost_tax_amount = $cost_supply_amount * 0.1;
                            $total_supply_amount += $cost_supply_amount;
                            $total_tax_amount += $cost_tax_amount;
                        }
                    ?>
                    <tr class="other-cost-row-view">
                        <td class="cost-category-input"><?= htmlspecialchars($cost['category'] ?? '') ?></td>
                        <td class="text-start cost-item-input"><?= htmlspecialchars($cost['item'] ?? '') ?></td>
                        <td class="text-center cost-unit-input"><?= htmlspecialchars($cost['unit'] ?? '') ?></td>                                
                        <td class="text-end cost-quantity-input"><?= ($cost['quantity'] ?? 0) > 0 ? $cost['quantity'] : '' ?></td>
                        <td class="text-end cost-unit-price-input"><?= ($cost['quantity'] ?? 0) > 0 ? '' . number_format($cost['unit_price'] ?? 0) : '' ?></td>
                        <td class="text-end "><?= ($cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_supply_amount) : '' ?></td>
                        <td class="text-end "><?= ($cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_tax_amount) : '' ?></td>
                        <td class="text-start cost-remarks-input"><?= htmlspecialchars($cost['remarks'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; 
                        endif; 
                        endif; 
                    ?>
                    
                </tbody>
                <tfoot>
                    <!-- 기타비용 소계 행 -->                            
                    <tr class="table-secondary">
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <td colspan="6" class="text-end fw-medium">소계</td>
                        <?php else: // $mode == 'view' ?>
                            <td colspan="5" class="text-end fw-medium">소계</td>
                        <?php endif; ?>
                        <td class="text-end fw-bold" id="totalOtherCostsSupply">
                            <?php if($mode == 'view'): ?>
                                <?= number_format($total_supply_amount) ?>
                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold" id="totalOtherCostsTax">
                            <?php if($mode == 'view'): ?>
                                <?= number_format($total_tax_amount) ?>
                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>            
   
    <!-- 할인 상품 내역 테이블 -처리 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold text-danger mb-0">할인 상품 내역(본사금액 차감 - 구매자 할인 적용)</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="discountItemsTable">
                <thead class="table-light table-danger">
                    <tr>
                        <th scope="col" style="width: 8%;">No.</th>
                        <th scope="col" style="width: 25%;">상품명</th>
                        <th scope="col" style="width: 8%;">규격(Size)</th>
                        <th scope="col" style="width: 12%;">분류</th>
                        <th scope="col" style="width: 6%;">수량(EA)</th>
                        <th scope="col" style="width: 6%;">m²</th>
                        <th scope="col" style="width: 7%;">단가</th>
                        <th scope="col" style="width: 7%;">공급가액</th>
                        <th scope="col" style="width: 7%;">세액</th>
                        <th scope="col" style="width: 10%;">비고</th>
                    </tr>
                </thead>
                <tbody id="discountItemsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php
                        $discount_item_count =  count($discount_items);
                        for($i = 0; $i < $discount_item_count; $i++): ?>
                        <tr class="discount-item-row" data-row="<?= $i ?>">
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-2"><?= $i + 1 ?></span>
                                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountItemRow(<?= $i ?>)" title="행 삭제">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: left;">
                                <input type="hidden" name="discount_items[<?= $i ?>][product_code]" class="form-control form-control-sm" data-row="<?= $i ?>" style="text-align: left;" value="<?= htmlspecialchars($discount_items[$i]['product_code'] ?? '') ?>" readonly>                                
                                <!-- 상품명 표시 -->
                                <input type="text" name="discount_items[<?= $i ?>][code_string]" class="form-control form-control-sm" data-row="<?= $i ?>" style="text-align: left;" value="<?= htmlspecialchars($discount_items[$i]['code_string'] ?? '') ?>" readonly>                                
                            </td>
                            <td><input type="text" name="discount_items[<?= $i ?>][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="<?= htmlspecialchars($discount_items[$i]['specification'] ?? '') ?>" readonly></td>
                            <td><input type="text" name="discount_items[<?= $i ?>][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="<?= htmlspecialchars($discount_items[$i]['size'] ?? '') ?>" readonly></td>
                            <td><input type="number" name="discount_items[<?= $i ?>][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="<?= $discount_items[$i]['quantity'] ?? '1' ?>" readonly></td>
                            <td><input type="text" name="discount_items[<?= $i ?>][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="<?= $discount_items[$i]['area'] ?? '' ?>" readonly></td>
                            <td><input type="text" name="discount_items[<?= $i ?>][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="<?= number_format($discount_items[$i]['unit_price'] ?? 0) ?>" readonly></td>
                            <td class="text-end">
                                <input type="text" name="discount_items[<?= $i ?>][supply_amount]" class="form-control form-control-sm text-end supply-amount-input discount-item-supply-amount" placeholder="공급가액" value="<?= number_format($discount_items[$i]['supply_amount'] ?? 0) ?>" readonly>                                                                
                            </td>
                            <td class="text-end ">
                                <input type="text" name="discount_items[<?= $i ?>][tax_amount]" class="form-control form-control-sm text-end tax-amount-input discount-item-tax-amount" placeholder="세액" value="<?= number_format($discount_items[$i]['tax_amount'] ?? 0) ?>" readonly>                                                                
                            </td>
                            <td> <input type="text" name="discount_items[<?= $i ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($discount_items[$i]['remarks'] ?? '') ?>" ></td>
                        </tr>
                    <?php endfor; ?>
                    <?php else: // $mode == 'view' ?>
                    <?php
                    // view 모드에서 상품 목록 표시
                    $item_counter = 1;
                    $total_supply = 0;
                    $total_tax = 0;
                    foreach($discount_items as $discount_item): 
                        $supply_amount = floatval(str_replace(',', '', $discount_item['area'])) * floatval(str_replace(',', '', $discount_item['unit_price']));
                        $tax_amount = $supply_amount * 0.1;
                        
                        // 소계 누적
                        $total_supply -= $supply_amount;
                        $total_tax -= $tax_amount;
                        
                        // 상품명 표시
                        $display_product_name = '';
                        if(!empty($discount_item['product_code'])) {
                            try {
                                $product_name_sql = "SELECT prodcode, texture_kor, design_kor FROM mirae8440.phomi_unitprice WHERE prodcode = :prodcode";
                                $product_name_stmt = $pdo->prepare($product_name_sql);
                                $product_name_stmt->bindParam(':prodcode', $discount_item['product_code'], PDO::PARAM_STR);
                                $product_name_stmt->execute();
                                $product_info = $product_name_stmt->fetch(PDO::FETCH_ASSOC);
                                if($product_info) {
                                    $display_product_name = $product_info['texture_kor'] . ' ' . $product_info['design_kor'];
                                } else {
                                    $display_product_name = $discount_item['product_name'] ?? '';
                                }
                            } catch (PDOException $e) {
                                $display_product_name = $discount_item['product_name'] ?? '';
                            }
                        } else {
                            $display_product_name = $discount_item['product_name'] ?? '';
                        }
                    ?>
                    <tr class="discount-item-row-view">
                        <td><?= $item_counter ?></td>
                        <td class="text-start"><?= htmlspecialchars($display_product_name) ?></td>
                        <td class="text-start"><?= htmlspecialchars($discount_item['specification'] ?? '') ?></td>
                        <td class="text-center"><?= htmlspecialchars($discount_item['size'] ?? '') ?></td>
                        <td class="text-end"><?= number_format($discount_item['quantity'] ?? 0) ?></td>
                        <td class="text-end area-input"><?= number_format($discount_item['area'] ?? 0, 2) ?></td>
                        <td class="text-end"><?= number_format(floatval(str_replace(',', '', $discount_item['unit_price'] ?? 0))) ?></td>
                        <td class="text-end"><?= number_format($supply_amount) ?></td>
                        <td class="text-end"><?= number_format($tax_amount) ?></td>
                        <td><?= htmlspecialchars($discount_item['remarks'] ?? '') ?></td>
                    </tr>
                    <?php 
                    $item_counter++;                            
                    endforeach; 
                    endif; 
                    ?>
                </tbody>                    
                    <!-- 할인상품 소계 행 -->    
                    <tfoot>
                    <tr class="table-secondary">
                        <td colspan="7" class="text-end text-danger fw-medium">할인 소계</td>
                        <td class="text-end fw-bold text-danger" id="discountTotalSupply"><?= number_format($total_supply) ?></td>
                        <td class="text-end fw-bold text-danger" id="discountTotalTax"><?= number_format($total_tax) ?></td>
                        <td></td>
                    </tr>
                    </tfoot>                    
            </table>
        </div>
    </div>

    <!-- 할인기타 비용 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold text-danger mb-0">할인 기타 비용 (본사금액 차감 - 구매자 할인 적용)
                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <button type="button" class="btn btn-outline-danger btn-sm ms-2" id="addDiscountOtherCostRow">
                        할인추가
                    </button>
                <?php endif; ?>
            </h6>
            <script>
            // 할인 기타비용 행 추가 함수
            function addDiscountOtherCostRow() {
                // 현재 행 개수
                var rowCount = $('#discountOtherCostsTableBody tr.discount-cost-row').length;
                var newRowIdx = rowCount;

                // 새 행 HTML 생성
                var newRow = `
                <tr class="discount-cost-row" data-row="${newRowIdx}">
                    <td>
                        <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                            <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountCostRow(${newRowIdx})" title="행 삭제">
                                <i class="bi bi-dash"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="할인적용">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][item]" class="form-control form-control-sm ms-1" placeholder="항목" value="할인">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][unit]" class="form-control form-control-sm ms-1" placeholder="단위" value="">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][quantity]" class="form-control form-control-sm ms-1 discount-input text-end discount-cost-quantity-input" placeholder="수량" value="1">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][unit_price]" class="form-control form-control-sm ms-1 discount-input text-end discount-cost-unit-price-input" placeholder="단가" value="" oninput="inputNumber(this)" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][supply_amount]" class="form-control form-control-sm ms-1 text-end discount-cost-supply-amount" placeholder="공급가액" value="" readonly autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][tax_amount]" class="form-control form-control-sm ms-1 text-end discount-cost-tax-amount" placeholder="세액" value="" readonly autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][remarks]" class="form-control form-control-sm ms-1" placeholder="비고" value="">
                    </td>
                </tr>
                `;
                $('#discountOtherCostsTableBody').append(newRow);
            }

           // 버튼 클릭 이벤트 바인딩
            $(document).on('click', '#addDiscountOtherCostRow', function() {
                addDiscountOtherCostRow();
            });

            // discount-input 입력시 콤마 제거, 공급가액/세액 자동계산, updateTotal 호출
            // 할인 기타비용의 경우 별도 이벤트 핸들러에서 처리하므로 여기서는 제외
            function executeDiscountInputCalculation($input) {
                var $row = $input.closest('tr.discount-cost-row');
                if ($row.length === 0) return;
                
                // 수량, 단가 값 가져오기 (콤마 제거)
                var quantity = ($row.find('input[name*="[quantity]"]').val() || '').replace(/,/g, '');
                var unitPrice = ($row.find('input[name*="[unit_price]"]').val() || '').replace(/,/g, '');

                // 숫자 변환
                var qty = parseFloat(quantity) || 0;
                var price = parseFloat(unitPrice) || 0;

                // 입력값에 콤마 제거 후 다시 입력 (숫자만)
                var inputName = $input.attr('name') || '';
                if (inputName.includes('[quantity]')) {
                    $input.val(quantity.replace(/[^0-9.]/g, ''));
                }
                if (inputName.includes('[unit_price]')) {
                    $input.val(unitPrice.replace(/[^0-9.]/g, ''));
                }

                // 공급가액, 세액 계산
                var supply = Math.round(qty * price);
                var tax = Math.round(supply * 0.1);

                // 표시
                $row.find('input[name*="[supply_amount]"]').val(supply ? supply.toLocaleString() : '');
                $row.find('input[name*="[tax_amount]"]').val(tax ? tax.toLocaleString() : '');

                // 합계 업데이트 함수 호출
                if (typeof updateTotals === 'function') {
                    updateTotals();
                }
            }
            
            $(document).on('input', '.discount-input:not(.discount-cost-quantity-input):not(.discount-cost-unit-price-input)', function() {
                var $input = $(this);
                var inputId = $input.attr('id') || $input.attr('name') || 'discount-input-' + Math.random().toString(36).substr(2, 9);
                
                // 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms)
                if (isMobileDevice()) {
                    debounceMobileCalculation(inputId, function() {
                        executeDiscountInputCalculation($input);
                    }, 800);
                } else {
                    // PC 환경에서는 즉시 계산
                    executeDiscountInputCalculation($input);
                }
            });
            
            // 모바일에서 blur 이벤트 시 즉시 계산 실행
            $(document).on('blur', '.discount-input:not(.discount-cost-quantity-input):not(.discount-cost-unit-price-input)', function() {
                if (isMobileDevice()) {
                    var $input = $(this);
                    var inputId = $input.attr('id') || $input.attr('name') || 'discount-input-' + Math.random().toString(36).substr(2, 9);
                    
                    // 입력 종료 플래그 설정 (약간의 지연 후)
                    setTimeout(function() {
                        // 포커스가 다른 입력 필드로 이동하지 않았으면 입력 종료
                        var currentActive = document.activeElement;
                        if (!currentActive || (currentActive.tagName !== 'INPUT' && currentActive.tagName !== 'TEXTAREA' && currentActive.tagName !== 'SELECT')) {
                            isMobileInputActive = false;
                            activeMobileInputElement = null;
                        }
                    }, 200);
                    
                    // 해당 input의 대기 중인 계산 즉시 실행
                    if (mobileInputCalculationTimeouts[inputId]) {
                        clearTimeout(mobileInputCalculationTimeouts[inputId]);
                        delete mobileInputCalculationTimeouts[inputId];
                    }
                    
                    // blur 시에는 입력이 끝났으므로 즉시 계산 실행
                    executeDiscountInputCalculation($input);
                }
            });
            </script>            
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="discountOtherCostsTable">
                <thead class="table-light table-danger">
                    <tr>
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <th scope="col" style="width: 10%;">기능</th>
                        <?php endif; ?>
                            <th scope="col" style="width: 10%;">구분</th>
                            <th scope="col" style="width: 10%;">항목</th>
                            <th scope="col" style="width: 10%;">단위</th>
                            <th scope="col" style="width: 10%;">수량</th>
                            <th scope="col" style="width: 10%;">단가</th>
                            <th scope="col" style="width: 10%;">공급가액</th>
                            <th scope="col" style="width: 10%;">세액</th>
                            <th scope="col" style="width: 10%;">비고</th>
                    </tr>
                </thead>
                <tbody id="discountOtherCostsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php
                        // 기타비용 5개 행 자동 생성 (부자재, 시공비 등)
                        $default_costs = [
                            ['category' => '부자재', 'item' => '본드', 'unit' => 'EA', 'quantity' => '', 'unit_price' => '', 'remarks' => ''],
                            ['category' => '부자재', 'item' => '몰딩', 'unit' => 'EA', 'quantity' => '', 'unit_price' => '', 'remarks' => ''],
                            ['category' => '', 'item' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '', 'remarks' => ''],
                            ['category' => '시공비', 'item' => '㎡당 시공비', 'unit' => '㎡', 'quantity' => '', 'unit_price' => '', 'remarks' => '최소 시공비 70만원 (28㎡)'],
                            ['category' => '운송비', 'item' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '', 'remarks' => '착불']
                        ];
                        
                        // 기존 데이터가 있으면 사용, 없으면 기본값 사용
                        $discount_cost_count = count($discount_other_costs);
                        for($c = 0; $c < $discount_cost_count; $c++): 
                            $discount_cost_data = $discount_other_costs[$c] ?? $default_costs[$c] ?? $default_costs[0];
                        ?>
                        <tr class="discount-cost-row" data-row="<?= $c ?>">
                            <td>
                                <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountCostRow(<?= $c ?>)" title="행 삭제">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="discount_other_costs[<?= $c ?>][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="<?= htmlspecialchars($discount_cost_data['category'] ?? '') ?>">                                        
                            </td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][item]" class="form-control form-control-sm text-start" placeholder="항목" value="<?= htmlspecialchars($discount_cost_data['item'] ?? '') ?>"></td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="<?= htmlspecialchars($discount_cost_data['unit'] ?? '') ?>"></td>
                            <td><input type="number" name="discount_other_costs[<?= $c ?>][quantity]" class="form-control form-control-sm text-end discount-input discount-cost-quantity-input" placeholder="수량" step="1" value="<?= $discount_cost_data['quantity'] ?? '' ?>"></td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][unit_price]" class="form-control form-control-sm text-end discount-input discount-cost-unit-price-input" placeholder="단가" value="<?= is_numeric($discount_cost_data['unit_price'] ?? '') ? number_format($discount_cost_data['unit_price']) : '' ?>" oninput="inputNumber(this)"></td>
                            <td class="text-end ">
                                <input type="text" name="discount_other_costs[<?= $c ?>][supply_amount]" class="form-control form-control-sm text-end discount-cost-supply-amount" placeholder="공급가액" value="<?= number_format($discount_cost_data['supply_amount'] ?? 0) ?>" readonly>
                            </td>
                            <td class="text-end ">
                                <input type="text" name="discount_other_costs[<?= $c ?>][tax_amount]" class="form-control form-control-sm text-end discount-cost-tax-amount" placeholder="세액" value="<?= number_format($discount_cost_data['tax_amount'] ?? 0) ?>" readonly>
                            </td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($cost_data['remarks'] ?? '') ?>"></td>
                        </tr>
                        <?php endfor; ?>
                    <?php else: // $mode == 'view' ?>
                    <?php                            
                    // view 모드에서 기타비용 목록 표시 (최대 4개만)
                    if(!empty($discount_other_costs))
                      {
                        $view_cost_count = 0;
                        $total_supply = 0;
                        $total_tax = 0;
                        foreach($discount_other_costs as $discount_cost):
                            if($view_cost_count >= 4) break; // 최대 4개만 표시
                            $view_cost_count++;
                            $cost_supply_amount = 0;
                            $cost_tax_amount = 0;
                            
                            if(isset($discount_cost['quantity']) && isset($discount_cost['unit_price']) && 
                            is_numeric($discount_cost['quantity']) && is_numeric($discount_cost['unit_price'])) {
                                
                                if(isset($discount_cost['unit']) && ($discount_cost['unit'] === '㎡' || $discount_cost['unit'] === 'm²')) {
                                    if($discount_cost['quantity'] > 28) {
                                        $cost_supply_amount = $discount_cost['quantity'] * $discount_cost['unit_price'];
                                    } else {
                                        $cost_supply_amount = $discount_cost['unit_price'];
                                    }
                                } else {
                                    $cost_supply_amount = $discount_cost['quantity'] * $discount_cost['unit_price'];
                                }
                                
                                $cost_tax_amount = $cost_supply_amount * 0.1;
                                $total_supply -= $cost_supply_amount;
                                $total_tax -= $cost_tax_amount;
                            }
                        ?>
                        <tr class="discount-cost-row-view">
                            <td><?= htmlspecialchars($discount_cost['category'] ?? '') ?></td>
                            <td class="text-start cost-item-input"><?= htmlspecialchars($discount_cost['item'] ?? '') ?></td>
                            <td class="text-center cost-unit-input"><?= htmlspecialchars($discount_cost['unit'] ?? '') ?></td>                                
                            <td class="text-end discount-cost-quantity-input"><?= ($discount_cost['quantity'] ?? 0) > 0 ? $discount_cost['quantity'] : '' ?></td>
                            <td class="text-end discount-cost-unit-price-input"><?= ($discount_cost['quantity'] ?? 0) > 0 ? '' . number_format($discount_cost['unit_price'] ?? 0) : '' ?></td>
                            <td class="text-end discount-cost-supply-amount"><?= ($discount_cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_supply_amount) : '' ?></td>
                            <td class="text-end discount-cost-tax-amount"><?= ($discount_cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_tax_amount) : '' ?></td>
                            <td class="discount-cost-remarks-input"><?= htmlspecialchars($discount_cost['remarks'] ?? '') ?></td>
                        </tr>
                    <?php 
                        endforeach; 
                         }
                        endif; 
                    ?>
                </tbody>
                <tfoot>
                    <!-- 할인 기타비용 소계 행 -->                            
                    <tr class="table-secondary">
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <td colspan="6" class="text-end fw-medium text-danger"> 할인 소계</td>
                        <?php else: // $mode == 'view' ?>
                            <td colspan="5" class="text-end fw-medium text-danger">할인 소계</td>
                        <?php endif; ?>
                        <td class="text-end fw-bold text-danger" id="discountOtherCostsTotalSupply"><?= '' . number_format($total_supply) ?></td>
                        <td class="text-end fw-bold text-danger" id="discountOtherCostsTotalTax"><?= '' . number_format($total_tax) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 입금계좌정보 -->
    <div class="mb-4">                                             
        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <p class="mb-0"><h6><p class="text-center badge bg-primary">입금계좌정보 : <input type="text" name="payment_account" value="<?= htmlspecialchars($payment_account) ?>" class="form-control form-control-sm d-inline-block" style="width: 300px;" placeholder="입금계좌정보"></p></h6></p>
        <?php else: ?>
            <p class="mb-0"><h6><p class="text-center badge bg-primary">입금계좌정보 : <?= htmlspecialchars($payment_account) ?></p></h6></p>
        <?php endif; ?>
    </div>

    <!-- 비고 -->
    <div class="mb-4">
        <label class="form-label">비고</label>
        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <textarea name="note" class="form-control" rows="3" placeholder="비고사항을 입력하세요"><?= htmlspecialchars($note) ?></textarea>
        <?php else: ?>
            <div class="form-control-plaintext"><?= nl2br(htmlspecialchars($note)) ?: '-' ?></div>
        <?php endif; ?>
    </div>

    <!-- 수주 일정 및 회계 정보 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">수주 일정</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">수주 확정일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="order_confirm_date" value="<?= $order_confirm_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($order_confirm_date && $order_confirm_date != '0000-00-00') ? $order_confirm_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">출고 예정일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="delivery_due_date" value="<?= $delivery_due_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($delivery_due_date && $delivery_due_date != '0000-00-00') ? $delivery_due_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">실제 출고일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="delivery_date" value="<?= $delivery_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($delivery_date && $delivery_date != '0000-00-00') ? $delivery_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">수주 마감일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="order_close_date" value="<?= $order_close_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($order_close_date && $order_close_date != '0000-00-00') ? $order_close_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">회계 처리 날짜</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">본사 대금 지급일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="payment_date_head" value="<?= $payment_date_head ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($payment_date_head && $payment_date_head != '0000-00-00') ? $payment_date_head : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">대리점 수령일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="payment_date_dealer" value="<?= $payment_date_dealer ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($payment_date_dealer && $payment_date_dealer != '0000-00-00') ? $payment_date_dealer : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">세금계산서 발행일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="tax_invoice_date" value="<?= $tax_invoice_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($tax_invoice_date && $tax_invoice_date != '0000-00-00') ? $tax_invoice_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">업체 입금일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="deposit_date" value="<?= $deposit_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($deposit_date && $deposit_date != '0000-00-00') ? $deposit_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- 회계 금액 정보 -->
            <?php if(isset($_SESSION["level"]) && $_SESSION["level"] < 5): ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">회계 금액 정보</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">본사 매입 단가</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="purchase_unit_price" value="<?= $purchase_unit_price ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $purchase_unit_price ? number_format($purchase_unit_price) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">본사 총 매입금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="purchase_total" value="<?= $purchase_total ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $purchase_total ? number_format($purchase_total) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">본사 잔액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="head_balance" value="<?= $head_balance ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $head_balance ? number_format($head_balance) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 단가</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_unit_price" value="<?= $dealer_unit_price ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_unit_price ? number_format($dealer_unit_price) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_amount" value="<?= $dealer_amount ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_amount ? number_format($dealer_amount) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 총매출금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_total" value="<?= $dealer_total ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_total ? number_format($dealer_total) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 수수료</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_fee" value="<?= $dealer_fee ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_fee ? number_format($dealer_fee) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">업체 판매 단가</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="company_unit_price" value="<?= $company_unit_price ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $company_unit_price ? number_format($company_unit_price) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">업체 매출금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="company_amount" value="<?= $company_amount ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $company_amount ? number_format($company_amount) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">세금계산서 금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="tax_invoice_amount" value="<?= $tax_invoice_amount ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $tax_invoice_amount ? number_format($tax_invoice_amount) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">계산서 차액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="tax_diff" value="<?= $tax_diff ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $tax_diff ? number_format($tax_diff) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">입금 여부</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <select name="is_paid" class="form-select form-select-sm">
                                            <option value="">선택</option>
                                            <option value="Y" <?= ($is_paid == 'Y') ? 'selected' : '' ?>>입금완료</option>
                                            <option value="N" <?= ($is_paid == 'N') ? 'selected' : '' ?>>미입금</option>
                                        </select>
                                    <?php else: ?>
                                        <div class="form-control-plaintext"><?= ($is_paid == 'Y') ? '입금완료' : (($is_paid == 'N') ? '미입금' : '-') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
    </div>
    

    </div>
    </div>
</div>
</form>

<script>
    // 전역 함수들 (스코프 문제 해결)
let itemRowCount = <?= max(1, count($items)) ?>;
let costRowCount = <?= max(1, count($other_costs)) ?>;

// 견적서에서 전달된 본드 가격과 수량 (자동산출과 상관없이 사용)
let estimateBondPrice = <?= $estimate_bond_price ?? 5000 ?>;
let estimateBondQuantity = <?= $estimate_bond_quantity ?? 1 ?>;

// Select2 초기화 함수
function initializeSelect2(selector = '.product-select') {
    $(selector).select2({
        placeholder: '상품을 선택하세요',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "검색 결과가 없습니다.";
            },
            searching: function() {
                return "검색 중...";
            }
        }
    });
}

// 알파벳과 숫자가 혼합된 문자열을 자연스럽게 정렬하는 함수
function naturalSort(a, b) {
    // 문자열을 알파벳과 숫자 부분으로 분리
    function splitString(str) {
        return str.match(/[a-zA-Z]+|\d+/g) || [];
    }
    
    var aParts = splitString(a);
    var bParts = splitString(b);
    
    var maxLength = Math.max(aParts.length, bParts.length);
    
    for (var i = 0; i < maxLength; i++) {
        var aPart = aParts[i] || '';
        var bPart = bParts[i] || '';
        
        // 둘 다 숫자인 경우 숫자로 비교
        if (!isNaN(aPart) && !isNaN(bPart)) {
            var aNum = parseInt(aPart);
            var bNum = parseInt(bPart);
            if (aNum !== bNum) {
                return aNum - bNum;
            }
        } else {
            // 문자열 비교 (대소문자 구분 없이)
            var comparison = aPart.toLowerCase().localeCompare(bPart.toLowerCase());
            if (comparison !== 0) {
                return comparison;
            }
        }
    }    
    return 0;
}

// 상품 옵션을 동적으로 가져와서 select 요소에 채우는 함수
function populateProductOptions(selectElement, callback) {
    // 현재 선택된 값 저장
    const initialValue = selectElement.data('initial-value') || selectElement.val();
    
    $.ajax({
        url: 'get_products.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // 기존 옵션 제거 (첫 번째 옵션 제외)
            selectElement.find('option:not(:first)').remove();
            
            // 상품 데이터를 prodcode 기준으로 자연스럽게 정렬
            data.sort(function(a, b) {
                return naturalSort(a.prodcode, b.prodcode);
            });
            
            data.forEach(function(product) {
                var option = $('<option>')
                    .val(product.prodcode)
                    .attr('data-spec', product.type)
                    .attr('data-size', product.size)
                    .attr('data-thickness', product.thickness)
                    .attr('data-area', product.area)
                    .attr('data-unit-price', product.dist_price_per_m2)
                    .text(product.prodcode + ' - ' + product.texture_kor + ' ' + product.design_kor + ' (' + product.texture_eng + ' ' + product.design_eng + ')');
                selectElement.append(option);
            });
            
            // Select2 업데이트
            if (selectElement.hasClass('select2-hidden-accessible')) {
                selectElement.select2('destroy');
            }
            
            // Select2 재초기화
            selectElement.select2({
                placeholder: '상품을 선택하세요',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "검색 결과가 없습니다.";
                    },
                    searching: function() {
                        return "검색 중...";
                    }
                }
            });
            
            // Select2 초기화 완료 후 이전에 선택된 값이 있으면 다시 설정
            if (initialValue) {
                // 약간의 지연을 두어 Select2가 완전히 초기화되도록 함
                setTimeout(function() {
                    selectElement.val(initialValue).trigger('change');
                    // 콜백 함수가 있으면 실행 (Select2 값 설정 후)
                    if (callback && typeof callback === 'function') {
                        callback();
                    }
                }, 50);
            }
            
            // 콜백 함수가 있으면 실행
            if (callback && typeof callback === 'function') {
                // If no initialValue, execute callback immediately
                if (!initialValue) { // Only call if initialValue was not handled by setTimeout
                    callback();
                }
            }
        },
        error: function() {
            // 에러 발생 시에도 콜백 실행
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
}

// 상품 행 추가 (특정 행 뒤에)
function addItemRowAfter(rowIndex) {
    // 현재 행들의 개수를 다시 계산
    const currentRowCount = $('.item-row').length;
    const newRowIndex = currentRowCount;
    
    const newRowHtml = `
        <tr class="item-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addItemRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyItemRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteItemRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td style="text-align: left;">
                <select name="items[${newRowIndex}][product_code]" class="form-select form-select-sm product-select" data-row="${newRowIndex}" style="text-align: left;">
                    <option value="">상품을 선택하세요</option>
                </select>
            </td>
            <td><input type="text" name="items[${newRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" readonly></td>
            <td><input type="number" name="items[${newRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="1"></td>
            <td><input type="text" name="items[${newRowIndex}][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" oninput="inputNumber(this)"></td>
            <td class="text-end supply-amount">0</td>
            <td class="text-end tax-amount">0</td>
            <td><input type="text" name="items[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountItemRowAfter(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>                        
        </tr>
    `;
    
    // 지정된 행 뒤에 새 행 삽입
    const targetRow = $(`.item-row[data-row="${rowIndex}"]`);
    targetRow.after(newRowHtml);
    
    // 새로 추가된 행의 상품 옵션 채우기 (Select2 초기화 포함)
    const newSelectElement = targetRow.next().find('.product-select');
    const newRowElement = targetRow.next();
    
    // 새 행에 초기 로딩 플래그 설정 (새로 추가된 행이므로 상품 선택 시 단가표 기본값 사용)
    newRowElement.data('initial-load', false);
    
    populateProductOptions(newSelectElement);
    
    updateItemRowNumbers();
    autoResizeTableColumns();
    alertToast('상품 행 추가');
}

// 상품 행 복사
function copyItemRow(row) {
    var originalRow = $('.item-row[data-row="' + row + '"]');
    // 현재 행들의 개수를 다시 계산
    const currentRowCount = $('.item-row').length;
    var newRowIndex = currentRowCount;
    
    // 소스 행의 데이터 복사
    const productCode = originalRow.find('select[name*="[product_code]"]').val();
    const specification = originalRow.find('input[name*="[specification]"]').val();
    const size = originalRow.find('input[name*="[size]"]').val();
    const quantity = originalRow.find('input[name*="[quantity]"]').val();
    const area = originalRow.find('input[name*="[area]"]').val();
    const unitPrice = originalRow.find('input[name*="[unit_price]"]').val().replace(/,/g, '');
    const remarks = originalRow.find('input[name*="[remarks]"]').val();
    
    var newRowHtml = `
        <tr class="item-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addItemRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyItemRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteItemRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td style="text-align: left;">
                <select name="items[${newRowIndex}][product_code]" class="form-select form-select-sm product-select" data-row="${newRowIndex}" style="text-align: left;">
                    <option value="">상품을 선택하세요</option>
                </select>
            </td>
            <td><input type="text" name="items[${newRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="${specification}" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="${size}" readonly></td>
            <td><input type="number" name="items[${newRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="${quantity}"></td>
            <td><input type="text" name="items[${newRowIndex}][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="${area}" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="${unitPrice && unitPrice !== '' ? Number(unitPrice).toLocaleString() : ''}" oninput="inputNumber(this)"></td>
            <td class="text-end supply-amount">0</td>
            <td class="text-end tax-amount">0</td>
            <td><input type="text" name="items[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${remarks}"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountItemRowAfter(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>                        
        </tr>
    `;
    
    originalRow.after(newRowHtml);
    
    // 새로 추가된 행의 상품 옵션 채우기 (Select2 초기화 포함)
    const newRowElement = originalRow.next();
    const newSelectElement = newRowElement.find('.product-select');
    
    // 복사된 행에 초기 로딩 플래그 설정 (복사된 행이므로 기존 단가 유지)
    newRowElement.data('initial-load', true);
    
    populateProductOptions(newSelectElement, function() {
        // 복사된 데이터 설정 (Select2 초기화 완료 후)
        if (productCode) {
            // 복사된 단가를 임시로 저장
            const copiedUnitPrice = unitPrice;
            
            newRowElement.find('.product-select').val(productCode).trigger('change');
            
            // 상품 선택 후 복사된 단가로 복원
            setTimeout(function() {
                if (copiedUnitPrice && copiedUnitPrice !== '') {
                    const cleanUnitPrice = copiedUnitPrice.toString().replace(/,/g, '');
                    const unitPriceVal = parseFloat(cleanUnitPrice) || 0;
                    newRowElement.find('.unit-price-input').val(unitPriceVal.toLocaleString());
                    
                    // 금액 재계산
                    const quantityVal = parseFloat(quantity) || 0;
                    const size = newRowElement.find('.size-input').val();
                    
                    // 실제 면적 계산
                    let actualArea = 0;
                    if (size && size.trim() !== '') {
                        if (size.includes('*')) {
                            const sizeParts = size.split('*');
                            if (sizeParts.length >= 2) {
                                const width = parseFloat(sizeParts[0]) || 0;
                                const height = parseFloat(sizeParts[1]) || 0;
                                actualArea = (width * height) / 1000000;
                            }
                        } else if (size.includes('×')) {
                            const sizeParts = size.split('×');
                            if (sizeParts.length >= 2) {
                                const width = parseFloat(sizeParts[0]) || 0;
                                const height = parseFloat(sizeParts[1]) || 0;
                                actualArea = (width * height) / 1000000;
                            }
                        } else {
                            const singleSize = parseFloat(size) || 0;
                            actualArea = (singleSize * singleSize) / 1000000;
                        }
                    }
                    
                    const totalArea = quantityVal * actualArea;
                    newRowElement.find('.area-input').val(totalArea.toFixed(2));
                    
                    const supplyAmount = totalArea * unitPriceVal;
                    const taxAmount = supplyAmount * 0.1;
                    
                    newRowElement.find('.supply-amount').text(supplyAmount.toLocaleString());
                    newRowElement.find('.tax-amount').text(taxAmount.toLocaleString());
                }
            }, 100);
        }
    });
    
    // 금액 계산 - NaN 방지
    const quantityVal = parseFloat(quantity) || 0;
    let unitPriceVal = 0;
    
    // unitPrice가 숫자 형식인지 확인하고 안전하게 변환
    if (unitPrice && unitPrice !== '') {
        // 쉼표 제거 후 숫자로 변환
        const cleanUnitPrice = unitPrice.toString().replace(/,/g, '');
        unitPriceVal = parseFloat(cleanUnitPrice) || 0;
    }
    
                    const supplyAmount = actualArea * unitPriceVal;
    const taxAmount = supplyAmount * 0.1;
    
    newRowElement.find('.supply-amount').text('' + supplyAmount.toLocaleString());
    newRowElement.find('.tax-amount').text('' + taxAmount.toLocaleString());
    
    updateItemRowNumbers();
    autoResizeTableColumns();
    alertToast('상품 행 복사');
}

// 상품 행 삭제
function deleteItemRow(row) {
    if($('.item-row').length > 1) {
        $('.item-row[data-row="' + row + '"]').remove();
        updateItemRowNumbers();
        autoResizeTableColumns();
    }
    alertToast('상품 행 삭제');
}

// 상품 행 번호 업데이트
function updateItemRowNumbers() {
    $('.item-row').each(function(index) {
        $(this).attr('data-row', index);
        $(this).find('td:first span').text(index + 1);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="addItemRowAfter"]').attr('onclick', `addItemRowAfter(${index})`);
        $(this).find('button[onclick*="copyItemRow"]').attr('onclick', `copyItemRow(${index})`);
        $(this).find('button[onclick*="deleteItemRow"]').attr('onclick', `deleteItemRow(${index})`);
        
        $(this).find('input, select').each(function() {
            var name = $(this).attr('name');
            if(name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
            }
        });
    });
}

// 메인 상품 금액 계산
function calculateItemAmount(row) {
    if (row === undefined || row === null) {
        return;
    }
    
    var quantity = parseFloat($('input[name="items[' + row + '][quantity]"]').val()) || 0;
    var unitPrice = parseFloat($('input[name="items[' + row + '][unit_price]"]').val().replace(/,/g, '')) || 0;
    
    // 실제 면적 계산 (규격/사이즈 문자열에서 면적 추출)
    // NOTE: 이 화면에서 `items[][size]`는 "분류(M급/L급)"인 경우가 있어,
    // 단가 입력 시 m²가 0으로 바뀌는 오류가 발생했음. 면적 계산은 규격(`specification`) 우선.
    function parseActualAreaFromText(text) {
        if (!text || typeof text !== 'string') return 0;
        const clean = text.replace(/\s/g, '');
        let w = 0, h = 0;
        if (clean.includes('*')) {
            const parts = clean.split('*');
            w = parseFloat(parts[0]) || 0;
            h = parseFloat(parts[1]) || 0;
        } else if (clean.includes('×')) {
            const parts = clean.split('×');
            w = parseFloat(parts[0]) || 0;
            h = parseFloat(parts[1]) || 0;
        } else {
            // 단일 숫자인 경우 (가정: 정사각형)
            const single = parseFloat(clean) || 0;
            w = single;
            h = single;
        }
        if (w <= 0 || h <= 0) return 0;
        return (w * h) / 1000000; // mm² -> m²
    }
    
    const specText = $('input[name="items[' + row + '][specification]"]').val() || '';
    const sizeText = $('input[name="items[' + row + '][size]"]').val() || '';
    
    let actualArea = parseActualAreaFromText(specText);
    if (actualArea <= 0) {
        actualArea = parseActualAreaFromText(sizeText);
    }
    
    // 그래도 면적을 못 구하면 단가표의 data-area(1장 면적 m²) 사용
    if (actualArea <= 0) {
        const $rowEl = $('.item-row[data-row="' + row + '"]');
        const $opt = $rowEl.find('.product-select option:selected');
        const optArea = parseFloat(($opt.data('area') || '').toString().replace(/,/g, '')) || 0;
        if (optArea > 0) {
            actualArea = optArea;
        }
    }
    
    const $areaInput = $('input[name="items[' + row + '][area]"]');
    const currentArea = parseFloat(($areaInput.val() || '').toString().replace(/,/g, '')) || 0;
    
    // m² 열 업데이트 (수량 × 실제면적)
    // 면적을 추출할 수 없을 때는 기존 m²를 0으로 덮어쓰지 않음(사용자 버그 리포트 대응).
    let totalArea = quantity * actualArea;
    if (actualArea > 0) {
        totalArea = parseFloat(totalArea.toFixed(2));
        $areaInput.val(totalArea.toFixed(2));
    } else {
        totalArea = currentArea; // 유지
    }
    
    var supplyAmount = totalArea * unitPrice;
    var taxAmount = supplyAmount * 0.1;

    $('.item-row[data-row="' + row + '"] .supply-amount').text('' + supplyAmount.toLocaleString());
    $('.item-row[data-row="' + row + '"] .tax-amount').text('' + taxAmount.toLocaleString());

    const etcAutoChecked = $('#etc_autocheck').is(':checked') || $('#etc_autocheck').val() === '1';
    if (etcAutoChecked) {
        calculateOtherCostsFromProducts(true);
    }

}

// 합계 업데이트 함수 updateTotals함수
function updateTotals() {
    let totalSupply = 0;
    let totalTax = 0;
    let otherCostsSupply = 0;
    let otherCostsTax = 0;
    let discountTotalSupply = 0;
    let discountTotalTax = 0;
    let discountOtherCostsTotalSupply = 0;
    let discountOtherCostsTotalTax = 0;
    
    // 상품 합계 계산
    $('.item-row').each(function() {
        const supplyText = $(this).find('.supply-amount').text();
        const taxText = $(this).find('.tax-amount').text();
        totalSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        totalTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    
    // 상품 소계 업데이트
    $('#totalSupply').text('' + totalSupply.toLocaleString());
    $('#totalTax').text('' + totalTax.toLocaleString());

    // 기타비용 합계 계산
    $('.cost-row').each(function() {
        const supplyText = $(this).find('.cost-supply-amount').val();
        const taxText = $(this).find('.cost-tax-amount').val();
        otherCostsSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        otherCostsTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    
    // 기타비용 소계 업데이트    
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
    
    // 할인 상품 차감    
    $('.discount-item-row').each(function() {
        // input 값에서 콤마(,)를 제거하고 숫자로 변환
        const supplyText = ($(this).find('.discount-item-supply-amount').val() || '').replace(/,/g, '');
        const taxText = ($(this).find('.discount-item-tax-amount').val() || '').replace(/,/g, '');
        // NaN 방지: parseFloat 결과가 NaN이면 0 처리
        const supplyValue = parseFloat(supplyText);
        const taxValue = parseFloat(taxText);
        discountTotalSupply -= isNaN(supplyValue) ? 0 : supplyValue;
        discountTotalTax -= isNaN(taxValue) ? 0 : taxValue;
    });

    // 할인 기타비용 차감
    $('.discount-cost-row').each(function() {
        const supplyText = $(this).find('.discount-cost-supply-amount').val();
        const taxText = $(this).find('.discount-cost-tax-amount').val();
        discountOtherCostsTotalSupply -= parseFloat((supplyText || '').replace(/,/g, '')) || 0;
        discountOtherCostsTotalTax -= parseFloat((taxText || '').replace(/,/g, '')) || 0;
    });

    
    // 상품 소계 업데이트
    $('#totalSupply').text('' + totalSupply.toLocaleString());
    $('#totalTax').text('' + totalTax.toLocaleString());
    
    // 기타비용 소계 업데이트    
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
    
    // 할인 차감 소계 업데이트
    $('#discountTotalSupply').text('' + discountTotalSupply.toLocaleString());
    $('#discountTotalTax').text('' + discountTotalTax.toLocaleString());
    
    // 할인 기타비용 차감 소계 업데이트
    $('#discountOtherCostsTotalSupply').text('' + discountOtherCostsTotalSupply.toLocaleString());
    $('#discountOtherCostsTotalTax').text('' + discountOtherCostsTotalTax.toLocaleString());
    
    // 전체 합계 (상품 + 기타비용 - 할인 상품 - 할인 기타비용)
    const grandTotalSupply = totalSupply + otherCostsSupply + discountTotalSupply + discountOtherCostsTotalSupply;
    const grandTotalTax = totalTax + otherCostsTax + discountTotalTax + discountOtherCostsTotalTax;
    
    // hidden input 업데이트
    $('input[name="total_ex_vat"]').val(grandTotalSupply);
    $('input[name="total_inc_vat"]').val(grandTotalSupply + grandTotalTax);
    
    // 합계 테이블 업데이트
    // 모바일에서는 괄호 제거, PC에서는 괄호 포함
    if (window.innerWidth <= 768) {
        $('.total-ex-vat').text(grandTotalSupply.toLocaleString());
    } else {
        $('.total-ex-vat').text('(' + grandTotalSupply.toLocaleString() + ')');
    }
    $('.total-inc-vat').text('(' + (grandTotalSupply + grandTotalTax).toLocaleString() + ')');
    
}

// 모바일에서 부가세 별도 금액의 괄호 제거
function removeVATParentheses() {
	if (window.innerWidth <= 768) {
		$('.total-ex-vat').each(function() {
			var text = $(this).text();
			// 괄호 제거
			text = text.replace(/[()]/g, '');
			$(this).text(text);
		});
	} else {
		// PC에서는 원래대로 복원 (괄호 포함)
		$('.total-ex-vat').each(function() {
			var text = $(this).text();
			// 괄호가 없으면 추가
			if (!text.match(/^\(/)) {
				text = '(' + text + ')';
				$(this).text(text);
			}
		});
	}
}


// 행 번호 업데이트 함수
function updateRowNumbers() {
        $('.item-row').each(function(index) {
            $(this).find('td:first span').text(index + 1);
            $(this).attr('data-row', index);
            
            // 버튼의 onclick 속성 업데이트
            const buttons = $(this).find('.btn-group button');
            buttons.eq(0).attr('onclick', `addRowAfter(${index})`);
            buttons.eq(1).attr('onclick', `copyRow(${index})`);
            buttons.eq(2).attr('onclick', `deleteRow(${index})`);
        });
    }
    
// 기타비용 행 추가 함수
function addCostRowAfter(rowIndex) {
    const newRowIndex = costRowCount;
    const newRow = `
        <tr class="cost-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">                   
                    <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>           
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>                                             
                    </div>
                </div>
            </td>
            <td><input type="text" name="other_costs[${newRowIndex}][category]" class="form-control form-control-sm" placeholder="구분"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][item]" class="form-control form-control-sm text-start" placeholder="항목"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit]" class="form-control form-control-sm text-center" placeholder="단위"></td>
            <td><input type="number" name="other_costs[${newRowIndex}][quantity]" class="form-control form-control-sm text-end cost-quantity-input" placeholder="수량" step="1"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end cost-unit-price-input" placeholder="단가" ></td>
            <td><input type="text" name="other_costs[${newRowIndex}][supply_amount]" class="form-control form-control-sm text-end cost-supply-amount" value="0" readonly></td>
            <td><input type="text" name="other_costs[${newRowIndex}][tax_amount]" class="form-control form-control-sm text-end cost-tax-amount" value="0" readonly></td>
            <td><input type="text" name="other_costs[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountCostRow(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>            
        </tr>
    `;
    
    // 지정된 행 뒤에 새 행 삽입
    const targetRow = $(`.cost-row[data-row="${rowIndex}"]`);
    targetRow.after(newRow);
    
    costRowCount++;
    updateCostRowNumbers();
    updateOtherCostsSubtotal(); // 기타비용 소계 업데이트
    updateTotals();
    
    // 모바일 카드 다시 렌더링
    if (window.innerWidth <= 768) {
        processedTables.clear();
        setTimeout(function() {
            renderMobileCards();
        }, 200);
    }
}

// 기타비용 행 복사 함수
function copyCostRow(rowIndex) {
    const sourceRow = $(`.cost-row[data-row="${rowIndex}"]`);
    const newRowIndex = costRowCount;
    
    // 소스 행의 데이터 복사
    const category = sourceRow.find('input[name*="[category]"]').val();
    const item = sourceRow.find('input[name*="[item]"]').val();
    const unit = sourceRow.find('input[name*="[unit]"]').val();
    const quantity = sourceRow.find('.cost-quantity-input').val();
    const unitPrice = sourceRow.find('.cost-unit-price-input').val();
    const supplyAmount = sourceRow.find('.cost-supply-amount').val();
    const taxAmount = sourceRow.find('.cost-tax-amount').val();
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    const newRow = `
        <tr class="cost-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">                    
                    <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td><input type="text" name="other_costs[${newRowIndex}][category]" class="form-control form-control-sm" placeholder="구분" value="${category}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][item]" class="form-control form-control-sm" placeholder="항목" value="${item}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="${unit}"></td>
            <td><input type="number" name="other_costs[${newRowIndex}][quantity]" class="form-control form-control-sm cost-quantity-input text-end" placeholder="수량" step="1" value="${quantity}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit_price]" class="form-control form-control-sm cost-unit-price-input text-end" placeholder="단가" value="${unitPrice}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][supply_amount]" class="form-control form-control-sm cost-supply-amount text-end" readonly value="${supplyAmount}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][tax_amount]" class="form-control form-control-sm cost-tax-amount text-end" readonly value="${taxAmount}"></td> 
            <td><input type="text" name="other_costs[${newRowIndex}][remarks]" class="form-control form-control-sm text-start" placeholder="비고" value="${remarks}"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountCostRow(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>              
        </tr>
    `;        
    // 소스 행 뒤에 새 행 삽입
    sourceRow.after(newRow);
    
    // 새로 추가된 행의 금액 계산
    const newRowElement = sourceRow.next();
    const quantityVal = parseFloat(quantity) || 0;
    const unitPriceVal = parseFloat(unitPrice.toString().replace(/,/g, '')) || 0;
    const supplyAmountVal = quantityVal * unitPriceVal;
    const taxAmountVal = supplyAmountVal * 0.1;
    
    newRowElement.find('.discount-cost-supply-amount').val('' + supplyAmountVal.toLocaleString());
    newRowElement.find('.discount-cost-tax-amount').val('' + taxAmountVal.toLocaleString());
    
    costRowCount++;
    updateCostRowNumbers();
    updateOtherCostsSubtotal(); // 기타비용 소계 업데이트
    updateTotals();
    
    // 모바일 카드 다시 렌더링
    if (window.innerWidth <= 768) {
        processedTables.clear();
        setTimeout(function() {
            renderMobileCards();
        }, 200);
    }
}

// 기타비용 소계 즉시 업데이트 함수
function updateOtherCostsSubtotal() {
    let otherCostsSupply = 0;
    let otherCostsTax = 0;
    
    // 기타비용 합계 계산
    $('.cost-row').each(function() {
        // 공급가액 input에서 값 가져오기
        const supplyText = ($(this).find('.cost-supply-amount').val() || '0').replace(/,/g, '');
        const taxText = ($(this).find('.cost-tax-amount').val() || '0').replace(/,/g, '');
        
        // 쉼표 제거 후 숫자로 변환하여 합계에 더하기
        otherCostsSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        otherCostsTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    
    // 기타비용 소계 업데이트
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
}

// 기타비용 행 삭제 함수
function deleteCostRow(rowIndex) {
    const row = $(`.cost-row[data-row="${rowIndex}"]`);
    if ($('.cost-row').length > 1) {
        row.remove();
        updateCostRowNumbers();
        updateOtherCostsSubtotal(); // 기타비용 소계 업데이트
        updateTotals();
        
        // 모바일 카드 다시 렌더링
        if (window.innerWidth <= 768) {
            processedTables.clear();
            setTimeout(function() {
                renderMobileCards();
            }, 200);
        }
        
        // mainTable 변화 감지하여 기타비용 테이블 연동
        // setTimeout(function() {
        //     initializeOtherCostsTable();
        // }, 100);
    } else {
        alert('최소 1개의 행은 유지해야 합니다.');
    }
    alertToast('기타비용 행 삭제');
}

// 할인 상품 행 추가 (기존 상품에서 복사)
function addDiscountItemRowAfter(sourceRowIndex) {
    // view 모드에서는 실행하지 않음
    let mode = $('#mode').val();
    if(mode === 'view') return;
    
    // 소스 상품 행에서 데이터 가져오기
    const sourceRow = $('.item-row[data-row="' + sourceRowIndex + '"]');
    if (sourceRow.length === 0) {
        alertToast('원본 상품 행을 찾을 수 없습니다.');
        return;
    }
    
    // 소스 행의 데이터 복사
    const productCode = sourceRow.find('select[name*="[product_code]"]').val();
    const specification = sourceRow.find('input[name*="[specification]"]').val();
    const size = sourceRow.find('input[name*="[size]"]').val();
    const quantity = sourceRow.find('input[name*="[quantity]"]').val();
    const area = sourceRow.find('input[name*="[area]"]').val();
    const unitPrice = sourceRow.find('input[name*="[unit_price]"]').val().replace(/,/g, '');
    const supplyAmount = sourceRow.find('.supply-amount').text().replace(/,/g, '');
    const taxAmount = sourceRow.find('.tax-amount').text().replace(/,/g, '');
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    // 상품명 생성 (상품 코드 기반)
    let codeString = productCode;
    if (productCode) {
        // 상품 코드에서 상품명 추출 시도
        const selectedOption = sourceRow.find('select[name*="[product_code]"] option:selected');
        if (selectedOption.length > 0) {
            const optionText = selectedOption.text();
            codeString = productCode + ' - ' + optionText.split(' - ')[1].split(' (')[0];
        }
    }
    
    // 할인 상품 테이블에 새 행 추가
    const currentDiscountRowCount = $('.discount-item-row').length;
    const newDiscountRowIndex = currentDiscountRowCount;
    
    const newDiscountRowHtml = `
        <tr class="discount-item-row" data-row="${newDiscountRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newDiscountRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountItemRow(${newDiscountRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td style="text-align: left;">
                <input type="hidden" name="discount_items[${newDiscountRowIndex}][product_code]" class="form-control form-control-sm" data-row="${newDiscountRowIndex}" style="text-align: left;" value="${productCode}" readonly>                                
                <input type="text" name="discount_items[${newDiscountRowIndex}][code_string]" class="form-control form-control-sm" data-row="${newDiscountRowIndex}" style="text-align: left;" value="${codeString}" readonly>                                
            </td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="${specification}" readonly></td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="${size}" readonly></td>
            <td><input type="number" name="discount_items[${newDiscountRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="${quantity}" readonly></td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="${area}" readonly></td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="${unitPrice}" readonly></td>
            <td class="text-end supply-amount">
                <input type="text" name="discount_items[${newDiscountRowIndex}][supply_amount]" class="form-control form-control-sm text-end discount-item-supply-amount" placeholder="공급가액" value="${parseFloat(supplyAmount).toLocaleString()}" readonly>                                                                
            </td>
            <td class="text-end tax-amount">
                <input type="text" name="discount_items[${newDiscountRowIndex}][tax_amount]" class="form-control form-control-sm text-end discount-item-tax-amount" placeholder="세액" value="${parseFloat(taxAmount).toLocaleString()}" readonly>                                                                
            </td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${remarks}"></td>
        </tr>
    `;
    
    // 할인 상품 테이블의 tbody에 새 행 추가
    $('#discountItemsTableBody').append(newDiscountRowHtml);
    
    // 할인 상품 행 번호 업데이트
    updateDiscountItemRowNumbers();
    
    // 할인 상품 합계 계산
    calculateDiscountTotals();

    updateTotals();
    
    alertToast('할인 상품이 추가되었습니다.');
}

// 할인 상품 행 삭제
function deleteDiscountItemRow(row) {
    // view 모드에서는 실행하지 않음
    if('<?= $mode ?>' === 'view') return;
    
    $('.discount-item-row[data-row="' + row + '"]').remove();
    updateDiscountItemRowNumbers();
    calculateDiscountTotals();
    alertToast('할인 상품 행 삭제');
}

// 할인 기타비용 행 삭제
function deleteDiscountCostRow(row) {
    // view 모드에서는 실행하지 않음
    if('<?= $mode ?>' === 'view') return;
    
    $('.discount-cost-row[data-row="' + row + '"]').remove();
    updateDiscountCostRowNumbers();
    calculateDiscountCostTotals();
    alertToast('할인 기타비용 행 삭제');
}

// 할인 상품 행 번호 업데이트
function updateDiscountItemRowNumbers() {
    // view 모드에서는 버튼 업데이트를 하지 않음
    if($("#mode").val() === 'view') return;
    
    $('.discount-item-row').each(function(index) {
        $(this).attr('data-row', index);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="deleteDiscountItemRow"]').attr('onclick', `deleteDiscountItemRow(${index})`);
        
        // input 필드의 name 속성 업데이트
        $(this).find('input[name*="[product_code]"]').attr('name', `discount_items[${index}][product_code]`);
        $(this).find('input[name*="[code_string]"]').attr('name', `discount_items[${index}][code_string]`);
        $(this).find('input[name*="[specification]"]').attr('name', `discount_items[${index}][specification]`);
        $(this).find('input[name*="[size]"]').attr('name', `discount_items[${index}][size]`);
        $(this).find('input[name*="[quantity]"]').attr('name', `discount_items[${index}][quantity]`);
        $(this).find('input[name*="[area]"]').attr('name', `discount_items[${index}][area]`);
        $(this).find('input[name*="[unit_price]"]').attr('name', `discount_items[${index}][unit_price]`);
        $(this).find('input[name*="[supply_amount]"]').attr('name', `discount_items[${index}][supply_amount]`);
        $(this).find('input[name*="[tax_amount]"]').attr('name', `discount_items[${index}][tax_amount]`);
        $(this).find('input[name*="[remarks]"]').attr('name', `discount_items[${index}][remarks]`);
        
        // 행 번호 표시 업데이트
        $(this).find('td:first-child span').text(index + 1);
    });
}

// 할인 상품 합계 계산
function calculateDiscountTotals() {
    let totalSupply = 0;
    let totalTax = 0;
    
    $('.discount-item-row input.supply-amount-input').each(function() {
        const supplyAmount = parseFloat($(this).val().replace(/[,]/g, '')) || 0;
        totalSupply -= supplyAmount;
    });
    
    $('.discount-item-row input.tax-amount-input').each(function() {
        const taxAmount = parseFloat($(this).val().replace(/[,]/g, '')) || 0;
        totalTax -= taxAmount;
    });
    
    // 할인 상품 테이블의 소계 업데이트
    $('#discountTotalSupply').text('' + totalSupply.toLocaleString());
    $('#discountTotalTax').text('' + totalTax.toLocaleString());

    updateTotals();
}

// 할인 기타 비용 행자동계산 함수
function calculateDiscountCostAmount(row) {
    var quantity = parseFloat($('input[name="discount_other_costs[' + row + '][quantity]"]').val()) || 0;
    var unitPrice = parseFloat($('input[name="discount_other_costs[' + row + '][unit_price]"]').val().replace(/,/g, '')) || 0;
    
    var supplyAmount = quantity * unitPrice;
    var taxAmount = supplyAmount * 0.1;
    $('input[name="discount_other_costs[' + row + '][supply_amount]"]').val(supplyAmount.toLocaleString());
    $('input[name="discount_other_costs[' + row + '][tax_amount]"]').val(taxAmount.toLocaleString());
    
    // 합계 업데이트
    updateTotals();
}
// 할인 기타 비용 행 추가 (기존 기타 비용에서 복사)
function addDiscountCostRow(sourceRowIndex) {
    // view 모드에서는 실행하지 않음
    const mode = $('#mode').val();
    if(mode === 'view') return;

    
    // 소스 기타 비용 행에서 데이터 가져오기
    const sourceRow = $('.cost-row[data-row="' + sourceRowIndex + '"]');
    if (sourceRow.length === 0) {
        alertToast('원본 기타 비용 행을 찾을 수 없습니다.');
        return;
    }
    
    // 소스 행의 데이터 복사
    const category = sourceRow.find('input[name*="[category]"]').val();
    const item = sourceRow.find('input[name*="[item]"]').val();
    const unit = sourceRow.find('input[name*="[unit]"]').val();
    const quantity = sourceRow.find('input[name*="[quantity]"]').val();
    const unitPrice = sourceRow.find('input[name*="[unit_price]"]').val();
    const supplyAmount = sourceRow.find('input[name*="[supply_amount]"]').val();
    const taxAmount = sourceRow.find('input[name*="[tax_amount]"]').val();
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    // 할인 기타 비용 테이블에 새 행 추가
    const currentDiscountCostRowCount = $('.discount-cost-row').length;
    const newDiscountCostRowIndex = currentDiscountCostRowCount;
    
    const newDiscountCostRowHtml = `
        <tr class="discount-cost-row" data-row="${newDiscountCostRowIndex}">
            <td>
                <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountCostRow(${newDiscountCostRowIndex})" title="행 삭제">
                        <i class="bi bi-dash"></i>
                    </button>
                </div>
            </td>
            <td>
                <input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="${category}" readonly>
            </td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][item]" class="form-control form-control-sm text-start" placeholder="항목" value="${item}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="${unit}" readonly></td>
            <td><input type="number" name="discount_other_costs[${newDiscountCostRowIndex}][quantity]" class="form-control form-control-sm text-end discount-cost-quantity-input" placeholder="수량" step="1" value="${quantity}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][unit_price]" class="form-control form-control-sm text-end discount-cost-unit-price-input" placeholder="단가" value="${unitPrice}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][supply_amount]" class="form-control form-control-sm text-end discount-cost-supply-amount" value="${supplyAmount}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][tax_amount]" class="form-control form-control-sm text-end discount-cost-tax-amount" value="${taxAmount}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${remarks}"></td>
        </tr>
    `;
    
    // 할인 기타 비용 테이블의 tbody에 새 행 추가
    $('#discountOtherCostsTableBody').append(newDiscountCostRowHtml);
    
    // 할인 기타 비용 행 번호 업데이트
    updateDiscountCostRowNumbers();
    
    // 행에 대한 합계 계산
    calculateDiscountCostAmount(newDiscountCostRowIndex);
    // 할인 기타 비용 합계 계산
    calculateDiscountCostTotals();
    
    alertToast('할인 기타 비용이 추가되었습니다.');
}

// 할인 기타 비용 행 삭제
function deleteDiscountCostRow(row) {
    // view 모드에서는 실행하지 않음
    if('<?= $mode ?>' === 'view') return;
    
    $('.discount-cost-row[data-row="' + row + '"]').remove();
    updateDiscountCostRowNumbers();
    calculateDiscountCostTotals();
    updateTotals();
    alertToast('할인 기타 비용 행 삭제');
}

// 할인 기타 비용 행 번호 업데이트
function updateDiscountCostRowNumbers() {
    // view 모드에서는 버튼 업데이트를 하지 않음
    if($("#mode").val() === 'view') return;
    
    $('.discount-cost-row').each(function(index) {
        $(this).attr('data-row', index);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="deleteDiscountCostRow"]').attr('onclick', `deleteDiscountCostRow(${index})`);
        
        // input 필드의 name 속성 업데이트
        $(this).find('input[name*="[category]"]').attr('name', `discount_other_costs[${index}][category]`);
        $(this).find('input[name*="[item]"]').attr('name', `discount_other_costs[${index}][item]`);
        $(this).find('input[name*="[unit]"]').attr('name', `discount_other_costs[${index}][unit]`);
        $(this).find('input[name*="[quantity]"]').attr('name', `discount_other_costs[${index}][quantity]`);
        $(this).find('input[name*="[unit_price]"]').attr('name', `discount_other_costs[${index}][unit_price]`);
        $(this).find('input[name*="[remarks]"]').attr('name', `discount_other_costs[${index}][remarks]`);
    });
}

// 할인 기타 비용 합계 계산
function calculateDiscountCostTotals() {
    let totalSupply = 0;
    let totalTax = 0;
    
    $('.discount-cost-row').each(function() {
        const supplyAmountInput = $(this).find('.discount-cost-supply-amount');
        const taxAmountInput = $(this).find('.discount-cost-tax-amount');
        
        // input 요소가 존재하는지 확인
        if (supplyAmountInput.length && taxAmountInput.length) {
            const supplyAmountText = supplyAmountInput.val() || '0';
            const taxAmountText = taxAmountInput.val() || '0';
            
            const supplyAmount = parseFloat(supplyAmountText.replace(/[,]/g, '')) || 0;
            const taxAmount = parseFloat(taxAmountText.replace(/[,]/g, '')) || 0;
            
            totalSupply -= supplyAmount;
            totalTax -= taxAmount;
        }
    });
    
    // 할인 기타 비용 테이블의 소계 업데이트
    $('#discountOtherCostsTotalSupply').text('' + totalSupply.toLocaleString());
    $('#discountOtherCostsTotalTax').text('' + totalTax.toLocaleString());

    updateTotals();
}

// 할인 기타비용 소계 즉시 업데이트 함수
function updateDiscountOtherCostsSubtotal() {
    let discountOtherCostsSupply = 0;
    let discountOtherCostsTax = 0;
    
    // 할인 기타비용 합계 계산
    $('.discount-cost-row').each(function() {
        // 공급가액 input에서 값 가져오기
        const supplyText = $(this).find('.discount-cost-supply-amount').val() || '0';
        const taxText = $(this).find('.discount-cost-tax-amount').val() || '0';
        
        // 쉼표 제거 후 숫자로 변환하여 합계에 더하기
        discountOtherCostsSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        discountOtherCostsTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    
    // 할인 기타비용 소계 업데이트
    $('#discountOtherCostsTotalSupply').text('' + discountOtherCostsSupply.toLocaleString());
    $('#discountOtherCostsTotalTax').text('' + discountOtherCostsTax.toLocaleString());
}

// 기타비용 행 번호 업데이트
function updateCostRowNumbers() {
    // view 모드에서는 버튼 업데이트를 하지 않음
    if($("#mode").val() === 'view') return;

    $('.cost-row').each(function(index) {
        $(this).attr('data-row', index);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="addCostRowAfter"]').attr('onclick', `addCostRowAfter(${index})`);
        $(this).find('button[onclick*="copyCostRow"]').attr('onclick', `copyCostRow(${index})`);
        $(this).find('button[onclick*="deleteCostRow"]').attr('onclick', `deleteCostRow(${index})`);
        
        $(this).find('input').each(function() {
            var name = $(this).attr('name');
            if(name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
            }
        });
    });
}

// 숫자 입력 시 3자리마다 콤마로 자동 포맷팅 (정확한 숫자형 처리)
function inputNumber(input) {
    // 입력값에서 숫자와 소수점만 남김 (음수는 필요시 '-' 추가)
    let value = input.value.replace(/[^0-9.]/g, '');

    // 소수점이 여러 개 들어가는 경우 첫 번째만 남기고 제거
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }

    // 값이 없거나 숫자가 아니면 빈 문자열로 처리
    if (value === '' || isNaN(value)) {
        input.value = '';
        return;
    }

    // 정수와 소수점 분리
    let [intPart, decPart] = value.split('.');
    // 0으로 시작하는 경우 0 유지
    intPart = intPart.replace(/^0+(?=\d)/, '');

    // 3자리마다 콤마 추가
    intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    if (typeof decPart !== 'undefined') {
        input.value = intPart + '.' + decPart;
    } else {
        input.value = intPart;
    }
}

// 기타비용 금액 계산
function calculateCostAmount(row) {
    
    var $quantityInput = $('input[name="other_costs[' + row + '][quantity]"]');
    var $unitPriceInput = $('input[name="other_costs[' + row + '][unit_price]"]');
    var $supplyAmountCell = $('.cost-row[data-row="' + row + '"] .cost-supply-amount');
    var $taxAmountCell = $('.cost-row[data-row="' + row + '"] .cost-tax-amount');

    // 해당 요소가 모두 존재할 때만 계산
    if ($quantityInput.length && $unitPriceInput.length && $supplyAmountCell.length && $taxAmountCell.length) {
        var quantity = parseFloat($quantityInput.val()) || 0;
        var unitPrice = parseFloat($unitPriceInput.val().replace(/,/g, '')) || 0;

        var supplyAmount = quantity * unitPrice;
        var taxAmount = supplyAmount * 0.1;

        $supplyAmountCell.text('' + supplyAmount.toLocaleString());
        $taxAmountCell.text('' + taxAmount.toLocaleString());

        // 합계 업데이트
        updateTotals();
    }
}

// 할인 비용 금액 계산
function calculateDiscountItemAmount(row) {
    var $quantityInput = $('input[name="discount_other_costs[' + row + '][quantity]"]');
    var $unitPriceInput = $('input[name="discount_other_costs[' + row + '][unit_price]"]');
    var $supplyAmountCell = $('.discount-cost-row[data-row="' + row + '"] .discount-cost-supply-amount');
    var $taxAmountCell = $('.discount-cost-row[data-row="' + row + '"] .discount-cost-tax-amount');

    // 해당 요소가 모두 존재할 때만 계산
    if ($quantityInput.length && $unitPriceInput.length && $supplyAmountCell.length && $taxAmountCell.length) {
        var quantity = parseFloat($quantityInput.val()) || 0;
        var unitPrice = parseFloat($unitPriceInput.val().replace(/,/g, '')) || 0;

        var supplyAmount = quantity * unitPrice;
        var taxAmount = supplyAmount * 0.1;

        $supplyAmountCell.text('' + supplyAmount.toLocaleString());
        $taxAmountCell.text('' + taxAmount.toLocaleString());

        // 합계 업데이트
        updateTotals();
    }
}


// 상품 데이터 기반으로 기타비용 자동 계산
function calculateOtherCostsFromProducts(forceRecalculate = false) {
    
    let totalArea = 0;
    let totalQuantity = 0;
    let bondQuantity = 0;
    
        // 상품 데이터 분석
    $('.item-row').each(function() {
        const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
        const area = parseFloat($(this).find('.area-input').val()) || 0;
        const size = $(this).find('.size-input').val() || '';
        const specification = $(this).find('.specification-input').val() || '';
        
        totalArea += area;
        totalQuantity += quantity;
        
        // 본드 수량 계산 (원래 로직 유지)
        let sizeMultiplier = 2;
        if (specification.includes('2400')) {
            sizeMultiplier = 2;
        } else if (specification.includes('2700') || specification.includes('3000')) {
            sizeMultiplier = 3;
        }
        
        if (size.replace(/\s/g,'') === 'M급' || size.replace(/\s/g,'') === 'L급') {
            sizeMultiplier = 3;
        }
        // console.log('size', sizeMultiplier);
        // console.log('sizeMultiplier', sizeMultiplier);
        bondQuantity += quantity * sizeMultiplier;
    });
    

    // 시공비 제외 체크박스 상태 확인
    const exclude_construction_cost = $('input[name="exclude_construction_cost"]').is(':checked');
    // 몰딩 제외 체크박스 상태 확인
    const exclude_molding = $('input[name="exclude_molding"]').is(':checked');
    // 기존 기타비용 행들을 모두 제거하고 새로 생성
    const costTableBody = $('#otherCostsTableBody');
    
    // 수동 수정 여부 확인 (강제 재계산이 아닌 경우에만)
    let hasManualModifications = false;
    const etc_autocheck = $('input[name="etc_autocheck"]').is(':checked');
    if (etc_autocheck) {
        hasManualModifications = false;
    }
    
    // 수정 모드에서 기존 데이터가 있는지 확인
    let hasExistingData = false;
    $('.cost-row').each(function() {
        const category = $(this).find('input[name*="[category]"]').val();
        const item = $(this).find('input[name*="[item]"]').val();
        const quantity = $(this).find('.cost-quantity-input').val();
        const unitPrice = $(this).find('.cost-unit-price-input').val();
        
        if (category || item || quantity || unitPrice) {
            hasExistingData = true;
            return false; // break
        }
    });        
       
        // 기존 데이터가 없는 경우에만 수동 수정 플래그 확인
        $('.cost-row').each(function() {            
            // 또는 기존 데이터가 있는지 확인 (카테고리와 품목이 모두 채워져 있고 수량이나 단가가 있는 경우)
            const category = $(this).find('input[name*="[category]"]').val();
            const item = $(this).find('input[name*="[item]"]').val();
            const quantity = $(this).find('.cost-quantity-input').val();
            const unitPrice = $(this).find('.cost-unit-price-input').val();
            
            // 기존 데이터가 있고, 빈 값이 아닌 경우 수동 수정으로 간주
            if (category && item && (quantity || unitPrice)) {
                // 자동 계산된 기본값 패턴 확인
                const isAutoCalculated = 
                    // 본드: 부자재, 본드, 수량 있음, 단가 5,000
                    (category === '부자재' && item === '본드' && quantity && unitPrice === '5,000') ||
                    // 시공비: 시공비, ㎡당 시공비, 단가 25,000 또는 700,000 (시공비 제외가 아닐 때만)
                    (category === '시공비' && item === '㎡당 시공비' && (unitPrice === '25,000' || unitPrice === '700,000') && !exclude_construction_cost) ||
                    // 몰딩: 부자재, 몰딩, 빈 값들 (몰딩 제외가 아닐 때만)
                    (category === '부자재' && item === '몰딩' && !quantity && !unitPrice && !exclude_molding) ||
                    // 운송비: 운송비, 빈 항목, 비고에 '착불'
                    (category === '운송비' && !item && !quantity && !unitPrice);
                
                if (!isAutoCalculated) {
                    hasManualModifications = true;
                    return false; // break
                }
            }
        });


    
        // 수동 수정이 있고 강제 재계산이 아닌 경우 기존 데이터 보존
        if (hasManualModifications && !forceRecalculate) {
            window.isCalculatingOtherCosts = false;
            return;
        }
    
    // 기존 데이터 백업
    const existingData = [];
    $('.cost-row').each(function() {
        const rowData = {
            category: $(this).find('input[name*="[category]"]').val(),
            item: $(this).find('input[name*="[item]"]').val(),
            unit: $(this).find('input[name*="[unit]"]').val(),
            quantity: $(this).find('.cost-quantity-input').val(),
            unit_price: $(this).find('.cost-unit-price-input').val(),
            amount: $(this).find('.cost-supply-amount').val(),
            tax: $(this).find('.cost-tax-amount').val(),
            remarks: $(this).find('input[name*="[remarks]"]').val()
        };
        existingData.push(rowData);
    });
    
    // 시공비/몰딩 제외에 따라 기존 데이터 필터링
    const filteredExistingData = [];
    existingData.forEach((data, index) => {
        // 시공비 제외시 시공비 행 제외
        if (exclude_construction_cost && data.category === '시공비') {
            return;
        }
        // 몰딩 제외시 몰딩 행 제외
        if (exclude_molding && data.category === '부자재' && data.item === '몰딩') {
            return;
        }
        filteredExistingData.push(data);
    });
    
    // 시공비/몰딩 제외에 따라 행 수 조정 (자동계산 체크와 상관없이 항상 실행)
    let rowCount = 5; // 기본 5행 (본드, 몰딩, 빈행, 시공비, 운송비)
    
    if (exclude_construction_cost) {
        rowCount = 4; // 시공비 제외시 4행 (본드, 몰딩, 빈행, 운송비)
    }
    if (exclude_molding) {
        rowCount = Math.max(1, rowCount - 1); // 몰딩 제외시 1행 감소
    }
    
    // 기존 행 모두 제거
    $('#otherCostsTableBody .cost-row').remove();
    
    // 필요한 행 수만큼 생성
    for (let i = 0; i < rowCount; i++) {
        const newRow = `
            <tr class="cost-row" data-row="${i}">
                <td> 
                    <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(${i})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(${i})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>                                                
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(${i})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>                                                
                    </div>      
                </td>
                <td> <input type="text" name="other_costs[${i}][category]" class="form-control">
                <td><input type="text" name="other_costs[${i}][item]" class="form-control"></td>
                <td><input type="text" name="other_costs[${i}][unit]" class="form-control"></td>
                <td><input type="number" name="other_costs[${i}][quantity]" class="form-control cost-quantity-input text-end" step="1"></td>
                <td><input type="text" name="other_costs[${i}][unit_price]" class="form-control cost-unit-price-input text-end" ></td>
                <td><input type="text" name="other_costs[${i}][amount]" class="form-control cost-supply-amount text-end" readonly></td>
                <td><input type="text" name="other_costs[${i}][tax]" class="form-control cost-tax-amount text-end" readonly></td>
                <td><input type="text" name="other_costs[${i}][remarks]" class="form-control"></td>       
                <td> 
                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountCostRow(${i})" title="할인 행 추가">
                        할인
                    </button>
                </td>
            </tr>
        `;
        costTableBody.append(newRow);
    }
    
    // costRowCount 업데이트
    costRowCount = rowCount;

        
    // 기존 데이터 복원 (자동산출이 체크되지 않은 경우에만)
    if (!etc_autocheck && filteredExistingData.length > 0) {
        $('.cost-row').each(function(index) {
            if (filteredExistingData[index]) {
                const data = filteredExistingData[index];
                $(this).find('input[name*="[category]"]').val(data.category);
                $(this).find('input[name*="[item]"]').val(data.item);
                $(this).find('input[name*="[unit]"]').val(data.unit);
                $(this).find('.cost-quantity-input').val(data.quantity);
                $(this).find('.cost-unit-price-input').val(data.unit_price);
                $(this).find('.cost-supply-amount').val(data.amount);
                $(this).find('.cost-tax-amount').val(data.tax);
                $(this).find('input[name*="[remarks]"]').val(data.remarks);
                
                // 금액 계산
                calculateCostRow($(this));
            }
        });
    }
    
    // 새로 생성된 행들의 수동 수정 플래그 초기화
    $('.cost-row').data('manually-modified', false);

    // 기타비용 행 업데이트 - 기본 텍스트 라벨은 항상 설정
    const updatedCostRows = $('.cost-row');
    
    let rowIndex = 0;
    updatedCostRows.each(function(index) {
        // console.log(`행 ${index + 1} 업데이트 중...`);
        
        // 몰딩 제외시 2번째 행(몰딩)을 건너뛰기 위한 로직
        let actualRowIndex = rowIndex;
        if (exclude_molding && rowIndex === 1) {
            // 몰딩 제외시 2번째 행을 건너뛰고 다음 행으로
            rowIndex++;
            actualRowIndex = rowIndex;
        }
        
        if (actualRowIndex === 0) {
            // 1행: 부자재, 본드
            $(this).find('input[name*="[category]"]').val('부자재');
            $(this).find('input[name*="[item]"]').val('본드');
            $(this).find('input[name*="[unit]"]').val('EA');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                // 견적서에서 전달된 본드 가격 사용, 수량은 계산된 값 사용
                const bondPrice = estimateBondPrice || 5000; // 견적서 가격 우선, 없으면 기본값
                const bondQty = Math.round(bondQuantity); // 계산된 수량 사용 (자동계산 시)
                
                $(this).find('.cost-quantity-input').val(bondQty);
                $(this).find('.cost-unit-price-input').val(bondPrice.toLocaleString());
                $(this).find('.cost-supply-amount').val((bondPrice * bondQty).toLocaleString());
                $(this).find('.cost-tax-amount').val((bondPrice * bondQty * 0.1).toLocaleString());
                
            }
            $(this).find('input[name*="[remarks]"]').val('');
        } else if (actualRowIndex === 1 && !exclude_molding) {
            // 2행: 부자재, 몰딩 (몰딩 제외가 아닐 때만)
            $(this).find('input[name*="[category]"]').val('부자재');
            $(this).find('input[name*="[item]"]').val('몰딩');
            $(this).find('input[name*="[unit]"]').val('EA');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                $(this).find('.cost-quantity-input').val('');
                $(this).find('.cost-unit-price-input').val('');
                $(this).find('.cost-supply-amount').val('');
                $(this).find('.cost-tax-amount').val('');
            }
            $(this).find('input[name*="[remarks]"]').val('');
        } else if (actualRowIndex === 2 || (actualRowIndex === 1 && exclude_molding)) {
            // 3행: 빈 행 (몰딩 제외시 2행이 빈 행이 됨)
            $(this).find('input[name*="[category]"]').val('');
            $(this).find('input[name*="[item]"]').val('');
            $(this).find('input[name*="[unit]"]').val('');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                $(this).find('.cost-quantity-input').val('');
                $(this).find('.cost-unit-price-input').val('');
                $(this).find('.cost-supply-amount').val('');
                $(this).find('.cost-tax-amount').val('');
            }
            $(this).find('input[name*="[remarks]"]').val('');
        } else if (actualRowIndex === 3 || (actualRowIndex === 2 && exclude_molding)) {
            if (exclude_construction_cost) {
                // 시공비 제외시: 4행은 운송비
                $(this).find('input[name*="[category]"]').val('운송비');
                $(this).find('input[name*="[item]"]').val('');
                $(this).find('input[name*="[unit]"]').val('');
                
                // 자동계산이 체크된 경우에만 수치 값 설정
                if (etc_autocheck) {
                    $(this).find('.cost-quantity-input').val('');
                    $(this).find('.cost-unit-price-input').val('');
                    $(this).find('.cost-supply-amount').val('');
                    $(this).find('.cost-tax-amount').val('');
                }
                $(this).find('input[name*="[remarks]"]').val('착불');
            } else {
                // 시공비 포함시: 4행은 시공비
                $(this).find('input[name*="[category]"]').val('시공비');
                $(this).find('input[name*="[item]"]').val('㎡당 시공비');
                $(this).find('input[name*="[unit]"]').val('㎡');
                
                // 자동계산이 체크된 경우에만 수치 값 설정
                if (etc_autocheck) {
                    $(this).find('.cost-quantity-input').val(totalArea.toFixed(2));
                    // 시공비는 헤베당 25000원 추가 기본 70만원 28헤베까지는 70만원 이상시 헤베당 25000원 추가
                    if (totalArea <= 28) {
                        $(this).find('.cost-unit-price-input').val('700,000');
                        $(this).find('.cost-supply-amount').val((700000).toLocaleString());
                        $(this).find('.cost-tax-amount').val((700000 * 0.1).toLocaleString());
                    } else {
                        $(this).find('.cost-unit-price-input').val('25,000');
                        $(this).find('.cost-supply-amount').val((25000 * totalArea).toLocaleString());
                        $(this).find('.cost-tax-amount').val((25000 * totalArea * 0.1).toLocaleString());
                    }
                    $(this).find('input[name*="[remarks]"]').val('최소 시공비 70만원 (28㎡)');
                }
            }
        } else if (actualRowIndex === 4 || (actualRowIndex === 3 && exclude_molding && !exclude_construction_cost)) {
            // 5행: 운송비 (시공비 포함시에만)
            $(this).find('input[name*="[category]"]').val('운송비');
            $(this).find('input[name*="[item]"]').val('');
            $(this).find('input[name*="[unit]"]').val('');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                $(this).find('.cost-quantity-input').val('');
                $(this).find('.cost-unit-price-input').val('');
                $(this).find('.cost-supply-amount').val('');
                $(this).find('.cost-tax-amount').val('');
            }
            $(this).find('input[name*="[remarks]"]').val('착불');
        }
        
        // 각 행의 입력값 확인
        const category = $(this).find('input[name*="[category]"]').val();
        const item = $(this).find('input[name*="[item]"]').val();
        rowIndex++;
    });
    // 기타비용 소계 업데이트
    updateOtherCostsSubtotal();
    // 총합계 업데이트
    updateTotals();
}

// 기타비용 행 계산 함수
function calculateCostRow(row) {
    
    // 기본 input 값 가져오기
    const quantity = parseFloat(row.find('.cost-quantity-input').val().replace(/,/g, '')) || 0;
    const unitPrice = row.find('.cost-unit-price-input').val().replace(/,/g, '') || '0';    
    const category = row.find('input[name*="[category]"]').val() || '';
    const item = row.find('input[name*="[item]"]').val() || '';

    let supplyAmount = 0;
    
    // 기본 계산 로직 - 수량 * 단가
    supplyAmount = quantity * unitPrice;

    
    // 시공비 특별 계산 로직 - 기본 input 값 무시하고 규칙에 따라 계산
    if (category === '시공비' && item === '㎡당 시공비') {
        if (quantity <= 28) {
            supplyAmount = 700000; // 28헤베 이하는 70만원 고정
        } else {
            supplyAmount = 25000 * quantity; // 28헤베 초과시 헤베당 25,000원
        }
    }
    
    // 세액은 공급가액의 10%
    const taxAmount = supplyAmount * 0.1;
    
    // 계산 결과를 input에 설정 (천단위 구분기호 포함)
    row.find('.cost-supply-amount').val(supplyAmount.toLocaleString());
    row.find('.cost-tax-amount').val(taxAmount.toLocaleString());
}

// 할인 기타비용 행 계산 함수
function calculateDiscountCostRow(row) {
    
    // 기본 input 값 가져오기
    const quantityText = row.find('.discount-cost-quantity-input').val();
    const quantity = parseFloat((quantityText || '').toString().replace(/,/g, '')) || 0;
    const unitPriceText = row.find('.discount-cost-unit-price-input').val();
    const unitPrice = parseFloat((unitPriceText || '').toString().replace(/,/g, '')) || 0;
    
    // 기본 계산 로직 - 수량 * 단가
    const supplyAmount = quantity * unitPrice;
    
    // 세액은 공급가액의 10%
    const taxAmount = supplyAmount * 0.1;
    
    
    // 계산 결과를 input에 설정 (천단위 구분기호 포함)
    row.find('.discount-cost-supply-amount').val(supplyAmount.toLocaleString());
    row.find('.discount-cost-tax-amount').val(taxAmount.toLocaleString());
    
    // 모바일 카드의 값도 업데이트
    const rowIndex = row.attr('data-row');
    if (rowIndex !== undefined && rowIndex !== '') {
        const $mobileCard = $('.mobile-card').filter(function() {
            // 모바일 카드 내의 할인 기타비용 행 찾기
            const $cardRow = $(this);
            const $cardCategoryInput = $cardRow.find('input[name*="discount_other_costs"][name*="[category]"]');
            const $cardItemInput = $cardRow.find('input[name*="discount_other_costs"][name*="[item]"]');
            
            if ($cardCategoryInput.length > 0 && $cardItemInput.length > 0) {
                const cardCategoryVal = $cardCategoryInput.val();
                const cardItemVal = $cardItemInput.val();
                const rowCategory = row.find('input[name*="discount_other_costs"][name*="[category]"]').val();
                const rowItem = row.find('input[name*="discount_other_costs"][name*="[item]"]').val();
                
                return rowCategory === cardCategoryVal && rowItem === cardItemVal;
            }
            return false;
        });
        
        if ($mobileCard.length > 0) {
            // 모바일 카드의 공급가액과 세액 업데이트
            $mobileCard.find('input.discount-cost-supply-amount, input[name*="discount_other_costs"][name*="[supply_amount]"]').val(supplyAmount.toLocaleString());
            $mobileCard.find('input.discount-cost-tax-amount, input[name*="discount_other_costs"][name*="[tax_amount]"]').val(taxAmount.toLocaleString());
            
            // 공급가액과 세액을 span으로 표시하는 경우도 업데이트
            $mobileCard.find('strong').each(function() {
                const labelText = $(this).text().trim();
                if (labelText.includes('공급가액') || labelText.includes('Supply')) {
                    const $nextSpan = $(this).next('span');
                    if ($nextSpan.length > 0) {
                        $nextSpan.text(supplyAmount.toLocaleString());
                    }
                }
                if (labelText.includes('세액') || labelText.includes('Tax')) {
                    const $nextSpan = $(this).next('span');
                    if ($nextSpan.length > 0) {
                        $nextSpan.text(taxAmount.toLocaleString());
                    }
                }
            });
        }
    }
}

$(document).ready(function() {
    var mode = $('#mode').val();
    
    // 초기 Select2 및 계산
    initializeSelect2();
    
    // 기존 상품 행들의 옵션 채우기
    $('.product-select').each(function() {
        // 기존 값이 있으면 data 속성에 저장
        const currentValue = $(this).val();
        if (currentValue) {
            $(this).data('initial-value', currentValue);
        }
        
        // 초기 로딩 플래그 설정 (이후 상품 변경 시 구분용)
        const itemRow = $(this).closest('.item-row');
        itemRow.data('initial-load', true);
        
        populateProductOptions($(this));
    });
    
    // 상품 선택 이벤트 (기존 로직은 handleProductSelectChange로 통합됨)
    // NOTE: 과거 로직은 규격/분류 매핑이 뒤섞여 m²가 0으로 덮이는 원인이 되었으므로 비활성화.
    $(document).on('change', '.product-select', function() {
        return;
    });

    const etcAutoChecked = $('#etc_autocheck').is(':checked') || $('#etc_autocheck').val() === '1';
      
    // 조회모드가 아닐 때만 기타비용 자동 계산 초기화 insert일때 기타비용 초기형태 만들어줌
    if (mode !== 'view' && $('.item-row').length > 0) {
        // 수정 모드에서 기존 데이터가 있으면 자동 계산 방지
        let hasExistingData = false;
        $('.cost-row').each(function() {
            const category = $(this).find('input[name*="[category]"]').val();
            const item = $(this).find('input[name*="[item]"]').val();
            const quantity = $(this).find('.cost-quantity-input').val();
            const unitPrice = $(this).find('.cost-unit-price-input').val();
            
            if (category || item || quantity || unitPrice) {
                hasExistingData = true;
                return false; // break
            }
        });
        
        if (hasExistingData) {
        } else {
            // 기존 데이터가 없을 때만 자동 계산 실행
            calculateOtherCostsFromProducts(false);
        }
    }  

    // 수량/단가 변경 이벤트 
    // PC용 즉시 계산 함수
    function executeCalculationPC($input, row, cost_row) {
        // cost-quantity-input 또는 cost-unit-price-input에서 입력이 일어나면 새로운 calculateCostRow 함수 호출
        if($input.hasClass('cost-quantity-input') || $input.hasClass('cost-unit-price-input')) {
            var $costRow = $input.closest('.cost-row');
            calculateCostRow($costRow);
            updateTotals();
            // 기타비용 입력 시에는 calculateOtherCostsFromProducts 호출하지 않음 (포커스 유지)
            return;
        }

        if($input.hasClass('quantity-input') || $input.hasClass('unit-price-input')) {
            calculateItemAmount(row);
            // 소계 업데이트 (updateTotals 내부에서 처리되지만 명시적으로 호출)
            if (typeof updateItemSubtotals === 'function') {
                updateItemSubtotals();
            }
            if (etcAutoChecked) {  
                calculateOtherCostsFromProducts(true);
            }
            // 합계 업데이트
            updateTotals();
        } else if($input.hasClass('discount-cost-quantity-input') || $input.hasClass('discount-cost-unit-price-input')) {
            // 할인 기타비용 입력 - calculateOtherCostsFromProducts 호출하지 않음 (포커스 유지)
            var $discountCostRow = $input.closest('.discount-cost-row');
            if ($discountCostRow.length > 0) {
                calculateDiscountCostRow($discountCostRow);
                // 할인 기타비용 소계 업데이트
                updateDiscountOtherCostsSubtotal();
            }
            updateTotals();
        } else if($input.hasClass('discount-item-quantity-input') || $input.hasClass('discount-item-unit-price-input')) {
            calculateDiscountItemAmount(row);
            updateTotals();
        }
    }
    
    // 모바일용 지연 계산 함수 (입력이 완전히 끝난 후 실행)
    function executeCalculationMobile($input, row, cost_row) {
        // 입력 중 플래그 확인 - 입력 중이면 계산하지 않음
        if (isMobileInputActive && activeMobileInputElement && activeMobileInputElement !== $input[0]) {
            return;
        }
        
        // 입력이 완전히 끝난 후에만 계산 실행
        setTimeout(function() {
            // 다시 한번 입력 중인지 확인
            if (isMobileInputActive && activeMobileInputElement && activeMobileInputElement !== $input[0]) {
                return;
            }
            
            executeCalculationPC($input, row, cost_row);
        }, 100);
    }
    
    // 공통 계산 함수 (PC/모바일 분기)
    function executeCalculation($input, row, cost_row) {
        if (isMobileDevice()) {
            executeCalculationMobile($input, row, cost_row);
        } else {
            executeCalculationPC($input, row, cost_row);
        }
    }
    
    $(document).on('input', '.quantity-input, .unit-price-input, .cost-quantity-input, .cost-unit-price-input, .discount-item-quantity-input, .discount-item-unit-price-input, .discount-cost-quantity-input, .discount-cost-unit-price-input', function() {
        var $input = $(this);
        var inputId = $input.attr('id') || $input.attr('name') || Math.random().toString(36);
        var row = $input.closest('.item-row, .cost-row, .discount-item-row').data('row');
        var cost_row = $input.closest('.cost-row').data('row');
        
        // 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms로 증가)
        if (isMobileDevice()) {
            // 입력 중이면 계산하지 않음
            if (isMobileInputActive && activeMobileInputElement === this) {
                debounceMobileCalculation(inputId, function() {
                    // 입력이 완전히 끝난 후에만 계산
                    if (!isMobileInputActive || activeMobileInputElement !== $input[0]) {
                        executeCalculationPC($input, row, cost_row);
                    }
                }, 800);
            } else {
                debounceMobileCalculation(inputId, function() {
                    executeCalculationPC($input, row, cost_row);
                }, 800);
            }
        } else {
            // PC 환경에서는 즉시 계산
            executeCalculationPC($input, row, cost_row);
        }
    });
    
    // 모바일에서 blur 이벤트 시 즉시 계산 실행
    $(document).on('blur', '.quantity-input, .cost-quantity-input, .cost-unit-price-input, .discount-item-quantity-input, .discount-item-unit-price-input, .discount-cost-quantity-input, .discount-cost-unit-price-input', function() {
        if (isMobileDevice()) {
            var $input = $(this);
            var inputId = $input.attr('id') || $input.attr('name') || Math.random().toString(36);
            
            // 입력 종료 플래그 설정 (약간의 지연 후)
            setTimeout(function() {
                // 포커스가 다른 입력 필드로 이동하지 않았으면 입력 종료
                var currentActive = document.activeElement;
                if (!currentActive || (currentActive.tagName !== 'INPUT' && currentActive.tagName !== 'TEXTAREA' && currentActive.tagName !== 'SELECT')) {
                    isMobileInputActive = false;
                    activeMobileInputElement = null;
                }
            }, 200);
            
            // 해당 input의 대기 중인 계산 즉시 실행
            if (mobileInputCalculationTimeouts[inputId]) {
                clearTimeout(mobileInputCalculationTimeouts[inputId]);
                delete mobileInputCalculationTimeouts[inputId];
            }
            
            // 모바일 카드에서 원본 테이블의 행 찾기
            var row = null;
            var cost_row = null;
            
            // 모바일 카드 내부인지 확인
            var $mobileCard = $input.closest('.mobile-card');
            if ($mobileCard.length > 0) {
                // 모바일 카드에서 data-row 속성 찾기
                var $rowElement = $mobileCard.find('select[data-row], input[data-row]').first();
                if ($rowElement.length > 0) {
                    var rowIndex = $rowElement.attr('data-row');
                    if (rowIndex !== undefined && rowIndex !== '') {
                        row = parseInt(rowIndex);
                    }
                }
                
                // cost-row인지 확인
                if ($mobileCard.find('.cost-quantity-input, .cost-unit-price-input').length > 0) {
                    cost_row = row;
                }
                
                // row를 찾지 못한 경우, input의 name 속성에서 추출 시도
                if (row === null || row === undefined) {
                    var inputName = $input.attr('name') || '';
                    var match = inputName.match(/\[(\d+)\]/);
                    if (match && match[1]) {
                        row = parseInt(match[1]);
                    }
                }
                
                // 모바일 카드의 값을 원본 테이블에 동기화
                var inputValue = $input.val();
                var inputName = $input.attr('name');
                
                if (inputName) {
                    var $originalInput = $('input[name="' + inputName + '"], select[name="' + inputName + '"]').not('.mobile-card input, .mobile-card select');
                    if ($originalInput.length > 0) {
                        $originalInput.val(inputValue);
                        $originalInput.trigger('change');
                    }
                }
            } else {
                // 원본 테이블에서 찾기
                row = $input.closest('.item-row, .cost-row, .discount-item-row').data('row');
                cost_row = $input.closest('.cost-row').data('row');
            }
            
            // blur 시에는 입력이 끝났으므로 PC 함수 직접 호출
            if (row !== null && row !== undefined) {
                setTimeout(function() {
                    // executeCalculationPC 내부에서 이미 updateTotals를 호출하지만, 
                    // 모바일에서는 명시적으로 다시 호출하여 확실하게 업데이트
                    executeCalculationPC($input, row, cost_row);
                    
                    // 소계 및 합계 업데이트 (모바일에서 명시적으로 호출 - executeCalculationPC 후)
                    setTimeout(function() {
                        if (typeof updateItemSubtotals === 'function') {
                            updateItemSubtotals();
                        }
                        if (typeof updateOtherCostsSubtotal === 'function') {
                            updateOtherCostsSubtotal();
                        }
                        if (typeof updateDiscountItemSubtotals === 'function') {
                            updateDiscountItemSubtotals();
                        }
                        if (typeof updateDiscountOtherCostsSubtotal === 'function') {
                            updateDiscountOtherCostsSubtotal();
                        }
                        if (typeof updateTotals === 'function') {
                            updateTotals();
                        }
                    }, 100);
                }, 50);
            }
        }
    });
    
    // 시공비/몰딩 제외 체크박스 이벤트 리스너
    $('#exclude_construction_cost, #exclude_molding').change(function() {
        // 기타비용 테이블 재계산 (강제 재계산으로 설정하여 자동계산 체크와 상관없이 동작)
        calculateOtherCostsFromProducts(true);
        alertToast('시공비 등 재계산 ');
    });
       
    // 기타비용 행 입력 이벤트
    function executeCostCalculation($input) {
        const row = $input.closest('.cost-row');
        
        // 수동 수정 플래그 설정
        row.data('manually-modified', true);
        
        // 새로운 calculateCostRow 함수 호출 (jQuery 객체 전달)
        calculateCostRow(row);
        
        // 기타비용 소계 즉시 업데이트
        updateOtherCostsSubtotal();
    }
    
    $(document).on('input', '.cost-quantity-input, .cost-unit-price-input', function() {
        const $input = $(this);
        var inputId = $input.attr('id') || $input.attr('name') || 'cost-input-' + Math.random().toString(36).substr(2, 9);
        
        // 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms)
        if (isMobileDevice()) {
            debounceMobileCalculation(inputId, function() {
                executeCostCalculation($input);
            }, 800);
        } else {
            // PC 환경에서는 즉시 계산
            executeCostCalculation($input);
        }
    });
    
    // 모바일에서 blur 이벤트 시 즉시 계산 실행
    $(document).on('blur', '.cost-quantity-input, .cost-unit-price-input', function() {
        if (isMobileDevice()) {
            var $input = $(this);
            var inputId = $input.attr('id') || $input.attr('name') || 'cost-input-' + Math.random().toString(36).substr(2, 9);
            
            // 입력 종료 플래그 설정 (약간의 지연 후)
            setTimeout(function() {
                // 포커스가 다른 입력 필드로 이동하지 않았으면 입력 종료
                var currentActive = document.activeElement;
                if (!currentActive || (currentActive.tagName !== 'INPUT' && currentActive.tagName !== 'TEXTAREA' && currentActive.tagName !== 'SELECT')) {
                    isMobileInputActive = false;
                    activeMobileInputElement = null;
                }
            }, 200);
            
            // 해당 input의 대기 중인 계산 즉시 실행
            if (mobileInputCalculationTimeouts[inputId]) {
                clearTimeout(mobileInputCalculationTimeouts[inputId]);
                delete mobileInputCalculationTimeouts[inputId];
            }
            
            // blur 시에는 입력이 끝났으므로 즉시 계산 실행
            executeCostCalculation($input);
        }
    });
    
    // 할인 기타비용 행 입력 이벤트
    function executeDiscountCostCalculation($input) {
        let row = $input.closest('.discount-cost-row');
        
        // 모바일 카드의 입력 필드인 경우 원본 테이블의 행 찾기
        if (row.length === 0 || !row.hasClass('discount-cost-row')) {
            // 모바일 카드 내부인지 확인
            const $mobileCard = $input.closest('.mobile-card');
            if ($mobileCard.length > 0) {
                // 모바일 카드 내의 입력 필드에서 구분, 항목, 단위 값을 가져와서 원본 테이블 행 찾기
                const $cardCategoryInput = $mobileCard.find('input[name*="discount_other_costs"][name*="[category]"]');
                const $cardItemInput = $mobileCard.find('input[name*="discount_other_costs"][name*="[item]"]');
                const $cardUnitInput = $mobileCard.find('input[name*="discount_other_costs"][name*="[unit]"]');
                
                if ($cardCategoryInput.length > 0 && $cardItemInput.length > 0) {
                    const cardCategoryVal = ($cardCategoryInput.val() || '').trim();
                    const cardItemVal = ($cardItemInput.val() || '').trim();
                    const cardUnitVal = ($cardUnitInput.val() || '').trim();
                    
                    // 원본 테이블에서 일치하는 행 찾기
                    $('.discount-cost-row').each(function() {
                        const $discountCostRow = $(this);
                        const rowCategory = ($discountCostRow.find('input[name*="discount_other_costs"][name*="[category]"]').val() || '').trim();
                        const rowItem = ($discountCostRow.find('input[name*="discount_other_costs"][name*="[item]"]').val() || '').trim();
                        const rowUnit = ($discountCostRow.find('input[name*="discount_other_costs"][name*="[unit]"]').val() || '').trim();
                        
                        if (rowCategory === cardCategoryVal && rowItem === cardItemVal && rowUnit === cardUnitVal) {
                            row = $discountCostRow;
                            return false; // break
                        }
                    });
                    
                    // 원본 테이블 행을 찾았으면 모바일 카드의 값을 원본 테이블로 동기화
                    if (row.length > 0 && row.hasClass('discount-cost-row')) {
                        if ($input.hasClass('discount-cost-quantity-input')) {
                            const mobileQuantity = $input.val();
                            row.find('.discount-cost-quantity-input').val(mobileQuantity);
                        } else if ($input.hasClass('discount-cost-unit-price-input')) {
                            const mobileUnitPrice = $input.val();
                            row.find('.discount-cost-unit-price-input').val(mobileUnitPrice);
                        }
                    }
                }
            }
        }
        
        if (row.length === 0 || !row.hasClass('discount-cost-row')) {
            return; // 원본 테이블의 행을 찾을 수 없으면 종료
        }
        
        // 할인 기타비용 계산 함수 호출
        calculateDiscountCostRow(row);
        
        // 모바일 카드의 값도 업데이트
        const $mobileCard = $input.closest('.mobile-card');
        if ($mobileCard.length > 0 && row.length > 0) {
            // 계산된 공급가액과 세액을 모바일 카드에도 반영
            const supplyAmount = row.find('.discount-cost-supply-amount').val();
            const taxAmount = row.find('.discount-cost-tax-amount').val();
            
            $mobileCard.find('input.discount-cost-supply-amount, input[name*="discount_other_costs"][name*="[supply_amount]"]').val(supplyAmount);
            $mobileCard.find('input.discount-cost-tax-amount, input[name*="discount_other_costs"][name*="[tax_amount]"]').val(taxAmount);
            
            // 공급가액과 세액을 span으로 표시하는 경우도 업데이트
            $mobileCard.find('strong').each(function() {
                const labelText = $(this).text().trim();
                if (labelText.includes('공급가액') || labelText.includes('Supply')) {
                    const $nextSpan = $(this).next('span');
                    if ($nextSpan.length > 0) {
                        $nextSpan.text(supplyAmount);
                    }
                }
                if (labelText.includes('세액') || labelText.includes('Tax')) {
                    const $nextSpan = $(this).next('span');
                    if ($nextSpan.length > 0) {
                        $nextSpan.text(taxAmount);
                    }
                }
            });
        }
        
        // 할인 기타비용 소계 즉시 업데이트
        updateDiscountOtherCostsSubtotal();
        
        // 전체 합계 업데이트
        updateTotals();
    }
    
    $(document).on('input', '.discount-cost-quantity-input, .discount-cost-unit-price-input', function() {
        const $input = $(this);
        var inputId = $input.attr('id') || $input.attr('name') || 'discount-cost-input-' + Math.random().toString(36).substr(2, 9);
        
        // 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms)
        if (isMobileDevice()) {
            debounceMobileCalculation(inputId, function() {
                executeDiscountCostCalculation($input);
            }, 800);
        } else {
            // PC 환경에서는 즉시 계산
            executeDiscountCostCalculation($input);
        }
    });
    
    // 모바일에서 blur 이벤트 시 즉시 계산 실행
    $(document).on('blur', '.discount-cost-quantity-input, .discount-cost-unit-price-input', function() {
        if (isMobileDevice()) {
            var $input = $(this);
            var inputId = $input.attr('id') || $input.attr('name') || 'discount-cost-input-' + Math.random().toString(36).substr(2, 9);
            
            // 입력 종료 플래그 설정 (약간의 지연 후)
            setTimeout(function() {
                // 포커스가 다른 입력 필드로 이동하지 않았으면 입력 종료
                var currentActive = document.activeElement;
                if (!currentActive || (currentActive.tagName !== 'INPUT' && currentActive.tagName !== 'TEXTAREA' && currentActive.tagName !== 'SELECT')) {
                    isMobileInputActive = false;
                    activeMobileInputElement = null;
                }
            }, 200);
            
            // 해당 input의 대기 중인 계산 즉시 실행
            if (mobileInputCalculationTimeouts[inputId]) {
                clearTimeout(mobileInputCalculationTimeouts[inputId]);
                delete mobileInputCalculationTimeouts[inputId];
            }
            
            // blur 시에는 입력이 끝났으므로 즉시 계산 실행
            executeDiscountCostCalculation($input);
        }
    });
    
    // 기타비용 카테고리/품목 수동 수정 추적 및 재계산
    function executeCostCategoryItemCalculation($input) {
        const row = $input.closest('.cost-row');
        row.data('manually-modified', true);
        
        // 카테고리나 품목이 변경되면 계산 함수 호출 (시공비 특별 계산 로직 적용을 위해)
        calculateCostRow(row);
        
        // 기타비용 소계 업데이트
        updateOtherCostsSubtotal();
        updateTotals();
    }
    
    $(document).on('input', '.cost-row input[name*="[category]"], .cost-row input[name*="[item]"]', function() {
        const $input = $(this);
        var inputId = $input.attr('id') || $input.attr('name') || 'cost-category-item-input-' + Math.random().toString(36).substr(2, 9);
        
        // 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms)
        if (isMobileDevice()) {
            debounceMobileCalculation(inputId, function() {
                executeCostCategoryItemCalculation($input);
            }, 800);
        } else {
            // PC 환경에서는 즉시 계산
            executeCostCategoryItemCalculation($input);
        }
    });
    
    // 모바일에서 blur 이벤트 시 즉시 계산 실행
    $(document).on('blur', '.cost-row input[name*="[category]"], .cost-row input[name*="[item]"]', function() {
        if (isMobileDevice()) {
            var $input = $(this);
            var inputId = $input.attr('id') || $input.attr('name') || 'cost-category-item-input-' + Math.random().toString(36).substr(2, 9);
            
            // 해당 input의 대기 중인 계산 즉시 실행
            if (mobileInputCalculationTimeouts[inputId]) {
                clearTimeout(mobileInputCalculationTimeouts[inputId]);
                delete mobileInputCalculationTimeouts[inputId];
            }
            
            executeCostCategoryItemCalculation($input);
        }
    });
    
    // mainTable 변화 감지하여 기타비용 테이블 연동
    function executeProductSelectChange($select) {
        setTimeout(function() {
            calculateOtherCostsFromProducts(false);
        }, 150);
    }
    
    $(document).on('change', '.product-select', function() {
        var $select = $(this);
        var selectId = $select.attr('id') || $select.attr('name') || 'product-select-' + Math.random().toString(36).substr(2, 9);
        
        // 모바일 환경인 경우 입력이 끝날 때까지 대기 (300ms)
        if (isMobileDevice()) {
            debounceMobileCalculation(selectId, function() {
                executeProductSelectChange($select);
            }, 300);
        } else {
            // PC 환경에서는 즉시 실행
            executeProductSelectChange($select);
        }
    });
        
    // 단가 입력 필드에 대한 별도 이벤트 핸들러 추가
    function executeUnitPriceCalculation($input) {
        const row = $input.closest('.item-row').data('row');
        if (row !== undefined) {
            calculateItemAmount(row);
            updateTotals();
        }
    }
    
    $(document).on('input', '.unit-price-input', function() {
        var $input = $(this);
        var inputId = $input.attr('id') || $input.attr('name') || 'unit-price-input-' + Math.random().toString(36).substr(2, 9);
        
        // 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms)
        if (isMobileDevice()) {
            debounceMobileCalculation(inputId, function() {
                executeUnitPriceCalculation($input);
            }, 800);
        } else {
            // PC 환경에서는 즉시 계산
            executeUnitPriceCalculation($input);
        }
    });

    // 단가 입력 필드에 대한 keyup 이벤트 핸들러 추가 (백업용) - 모바일에서는 사용하지 않음
    $(document).on('keyup', '.unit-price-input', function() {
        if (!isMobileDevice()) {
            var $input = $(this);
            executeUnitPriceCalculation($input);
        }
    });
    
    // 모바일에서 blur 이벤트 시 즉시 계산 실행
    $(document).on('blur', '.unit-price-input', function() {
        if (isMobileDevice()) {
            var $input = $(this);
            var inputId = $input.attr('id') || $input.attr('name') || 'unit-price-input-' + Math.random().toString(36).substr(2, 9);
            
            // 해당 input의 대기 중인 계산 즉시 실행
            if (mobileInputCalculationTimeouts[inputId]) {
                clearTimeout(mobileInputCalculationTimeouts[inputId]);
                delete mobileInputCalculationTimeouts[inputId];
            }
            
            executeUnitPriceCalculation($input);
        }
    });

    // 테스트용 버튼 추가 (디버깅용)
    $(document).on('click', '.test-calc-btn', function() {
        const row = $(this).closest('.item-row').data('row');
        if (row !== undefined) {
            calculateItemAmount(row);
            updateTotals();
        }
    });

    // 기존 기타비용 데이터 분석하여 수동 수정 여부 판단
    function analyzeExistingOtherCosts() {
        $('.cost-row').each(function() {
            const category = $(this).find('input[name*="[category]"]').val();
            const item = $(this).find('input[name*="[item]"]').val();
            const quantity = $(this).find('.cost-quantity-input').val();
            const unitPrice = $(this).find('.cost-unit-price-input').val();
            
            // 기존 데이터가 있고, 자동 계산된 기본값과 다른 경우 수동 수정으로 간주
            if (category && item && (quantity || unitPrice)) {
                // 자동 계산된 기본값 패턴 확인
                const isAutoCalculated = 
                    // 본드: 부자재, 본드, 수량 있음, 단가 5,000
                    (category === '부자재' && item === '본드' && quantity && unitPrice === '5,000') ||
                    // 시공비: 시공비, ㎡당 시공비, 단가 25,000 또는 700,000
                    (category === '시공비' && item === '㎡당 시공비' && (unitPrice === '25,000' || unitPrice === '700,000')) ||
                    // 몰딩: 부자재, 몰딩, 빈 값들
                    (category === '부자재' && item === '몰딩' && !quantity && !unitPrice) ||
                    // 운송비: 운송비, 빈 항목, 비고에 '착불'
                    (category === '운송비' && !item && !quantity && !unitPrice);
                
                if (!isAutoCalculated) {
                    $(this).data('manually-modified', true);
                }
            }
        });
    }
    
    // 기존 데이터 분석 실행
    analyzeExistingOtherCosts();
    
    // 조회모드가 아닐 때만 기존 기타비용 데이터의 금액 계산
    if ($("#mode").val() !== 'view' && etcAutoChecked) {
        // 기존 기타비용 데이터의 금액 계산
        $('.cost-row').each(function() {
            const $row = $(this);
            const $quantityInput = $row.find('.cost-quantity-input');
            const $unitPriceInput = $row.find('.cost-unit-price-input');
            const $categoryInput = $row.find('input[name*="[category]"]');
            const $itemInput = $row.find('input[name*="[item]"]');
            
            const quantity = parseFloat($quantityInput.val()) || 0;
            const unitPriceText = $unitPriceInput.val();
            const category = $categoryInput.val();
            const item = $itemInput.val();
            
            if (quantity > 0 && unitPriceText) {
                let unitPrice = 0;
                let supplyAmount = 0;
                
                // 시공비 특별 계산 로직
                if (category === '시공비' && item === '㎡당 시공비') {
                    // 시공비는 헤베당 25000원 추가 기본 70만원 28헤베까지는 70만원 이상시 헤베당 25000원 추가
                    if (quantity <= 28) {
                        supplyAmount = 700000; // 70만원 고정
                    } else {
                        supplyAmount = 25000 * quantity; // ㎡당 25,000원
                    }
                } else {
                    // 일반 계산 로직
                    // 단가에서 쉼표 제거 후 숫자로 변환
                    if (unitPriceText && unitPriceText !== '') {
                        const cleanUnitPrice = unitPriceText.replace(/,/g, '');
                        unitPrice = parseFloat(cleanUnitPrice) || 0;
                    }
                    supplyAmount = quantity * unitPrice;
                }
                
                const taxAmount = supplyAmount * 0.1;
                
                $row.find('.cost-supply-amount').text('' + supplyAmount.toLocaleString());
                $row.find('.cost-tax-amount').text('' + taxAmount.toLocaleString());
                
            }
        });
    }
    
    // 초기 합계 계산 view가 아닐때 
    if($("#mode").val() !== 'view') {   
        updateTotals();
    }
    
});

// 테이블 열 너비 자동 조절 함수 (전역 스코프)
function autoResizeTableColumns() {
    const table = $('#itemsTable');
    const headers = table.find('thead th');
    
    // 임시로 사용할 span 요소를 body에 추가 (너비 계산용)
    // 한 번만 생성하고 재사용합니다.
    if ($('#tempSpan').length === 0) {
        $('<span id="tempSpan" style="position:absolute; top:-9999px; left:-9999px; white-space:nowrap; padding: 0 8px;"></span>').appendTo('body');
    }
    const tempSpan = $('#tempSpan');

    headers.each(function(index) {
        // 상품명 열(두 번째 열, index: 1)은 고정 너비로 처리
        if (index === 1) {
            $(this).css('width', '200px'); // Select2 UI를 고려한 고정 너비
            return; // 다음 열로 넘어감
        }

        // --- 나머지 열은 동적으로 너비 조절 ---
        let maxWidth = 0;
        
        // 1. 헤더 자체의 너비 계산
        tempSpan.css('font', $(this).css('font'));
        tempSpan.text($(this).text());
        maxWidth = tempSpan.prop('scrollWidth');
        
        // 2. 해당 열의 모든 셀(td) 내용 너비 계산
        table.find(`tbody tr`).each(function() {
            const cell = $(this).find('td').eq(index);
            const input = cell.find('input, select');
            let contentWidth = 0;

            if (input.length > 0) {
                // Select2의 경우, 선택된 텍스트를 가져와야 합니다.
                if (input.is('select') && input.hasClass('select2-hidden-accessible')) {
                    const selectedText = input.next('.select2-container').find('.select2-selection__rendered').text();
                    tempSpan.text(selectedText);
                } else {
                     tempSpan.text(input.val());
                }
                
                tempSpan.css('font', input.css('font'));
                contentWidth = tempSpan.prop('scrollWidth');
            } else {
                // 일반 텍스트 셀의 경우
                tempSpan.css('font', cell.css('font'));
                tempSpan.text(cell.text());
                contentWidth = tempSpan.prop('scrollWidth');
            }

            if (contentWidth > maxWidth) {
                maxWidth = contentWidth;
            }
        });
        
        // 3. 계산된 최대 너비에 여유 공간을 더해 th에 적용
        // input 테두리, select 화살표 등을 고려하여 30px 정도 여유를 줍니다.
        $(this).css('width', maxWidth + 30 + 'px');
    });
}

// 기타비용 테이블 열 너비 자동 조절 함수 (전역 스코프)
function autoResizeOtherCostsTableColumns() {
    const table = $('#otherCostsTable');
    const headers = table.find('thead th');
    
    // 임시로 사용할 span 요소를 body에 추가 (너비 계산용)
    if ($('#tempSpanOtherCosts').length === 0) {
        $('<span id="tempSpanOtherCosts" style="position:absolute; top:-9999px; left:-9999px; white-space:nowrap; padding: 0 8px;"></span>').appendTo('body');
    }
    const tempSpan = $('#tempSpanOtherCosts');

    headers.each(function(index) {
        // --- 모든 열을 동적으로 너비 조절 ---
        let maxWidth = 0;
        
        // 1. 헤더 자체의 너비 계산
        tempSpan.css('font', $(this).css('font'));
        tempSpan.text($(this).text());
        maxWidth = tempSpan.prop('scrollWidth');
        
        // 2. 해당 열의 모든 셀(td) 내용 너비 계산
        table.find(`tbody tr`).each(function() {
            const cell = $(this).find('td').eq(index);
            const input = cell.find('input, select');
            let contentWidth = 0;

            if (input.length > 0) {
                tempSpan.text(input.val());
                tempSpan.css('font', input.css('font'));
                contentWidth = tempSpan.prop('scrollWidth');
            } else {
                // 일반 텍스트 셀의 경우
                tempSpan.css('font', cell.css('font'));
                tempSpan.text(cell.text());
                contentWidth = tempSpan.prop('scrollWidth');
            }

            if (contentWidth > maxWidth) {
                maxWidth = contentWidth;
            }
        });
        
        // 3. 계산된 최대 너비에 여유 공간을 더해 th에 적용
        $(this).css('width', maxWidth + 30 + 'px');
    });
}

$(document).ready(function() {
    
    // 페이지 로드 시 테이블 너비 자동 조절
    autoResizeTableColumns();
    autoResizeOtherCostsTableColumns();
    
    // 입력 필드에 입력이 발생할 때마다 너비 조절 (실시간)
    // 테이블 컬럼 너비 조절 함수들
    function executeAutoResizeTableColumns() {
        if (typeof autoResizeTableColumns === 'function') {
            autoResizeTableColumns();
        }
    }
    
    function executeAutoResizeOtherCostsTableColumns() {
        if (typeof autoResizeOtherCostsTableColumns === 'function') {
            autoResizeOtherCostsTableColumns();
        }
    }
    
    $(document).on('input', '.item-row input', function() {
        // 모바일 환경인 경우 입력이 끝날 때까지 대기
        if (isMobileDevice()) {
            var $input = $(this);
            var inputId = $input.attr('id') || $input.attr('name') || 'item-row-input-' + Math.random().toString(36).substr(2, 9);
            debounceMobileCalculation(inputId, executeAutoResizeTableColumns, 800);
        } else {
            executeAutoResizeTableColumns();
        }
    });

    // Select2 드롭다운 값이 변경될 때마다 너비 조절
    $(document).on('change', '.product-select', function() {
        // 모바일 환경인 경우 입력이 끝날 때까지 대기
        if (isMobileDevice()) {
            var $select = $(this);
            var selectId = $select.attr('id') || $select.attr('name') || 'product-select-resize-' + Math.random().toString(36).substr(2, 9);
            debounceMobileCalculation(selectId, executeAutoResizeTableColumns, 300);
        } else {
            executeAutoResizeTableColumns();
        }
    });
    
    // 기타비용 입력 필드에 입력이 발생할 때마다 너비 조절 (실시간)
    $(document).on('input', '.cost-row input', function() {
        // 모바일 환경인 경우 입력이 끝날 때까지 대기
        if (isMobileDevice()) {
            var $input = $(this);
            var inputId = $input.attr('id') || $input.attr('name') || 'cost-row-input-' + Math.random().toString(36).substr(2, 9);
            debounceMobileCalculation(inputId, executeAutoResizeOtherCostsTableColumns, 800);
        } else {
            executeAutoResizeOtherCostsTableColumns();
        }
    });
    
    // 저장 버튼 클릭 이벤트
    $("#saveBtn").off('click').on('click', function() {
        
        try {
            // JSON 데이터 생성
            const items = [];
            $('.item-row').each(function() {
                const productCode = $(this).find('select[name*="[product_code]"]').val();
                const productName = $(this).find('select[name*="[product_code]"]').find('option:selected').text();
                const specification = $(this).find('.specification-input').val();
                const size = $(this).find('.size-input').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
                const area = parseFloat($(this).find('input[name*="[area]"]').val()) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;            
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                items.push({
                    product_code: productCode,
                    product_name: productName,
                    specification: specification,
                    size: size,
                    quantity: quantity,
                    area: area,
                    unit_price: unitPrice,
                    remarks: remarks
                });
            });
            
            const otherCosts = [];
            $('.cost-row').each(function() {
                const category = $(this).find('input[name*="[category]"]').val();
                const item = $(this).find('input[name*="[item]"]').val();
                const unit = $(this).find('input[name*="[unit]"]').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val().replace(/,/g, '')) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                otherCosts.push({
                    category: category,
                    item: item,
                    unit: unit,
                    quantity: quantity,
                    unit_price: unitPrice,
                    remarks: remarks
                });
            });
        
            // 할인 상품 데이터 생성
            const discountItems = [];
            $('.discount-item-row').each(function() {
                const productCode = $(this).find('input[name*="[product_code]"]').val();
                const codeString = $(this).find('input[name*="[code_string]"]').val();
                const specification = $(this).find('input[name*="[specification]"]').val();
                const size = $(this).find('input[name*="[size]"]').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
                const area = parseFloat($(this).find('input[name*="[area]"]').val()) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;
                const supplyAmount = parseFloat($(this).find('input[name*="[supply_amount]"]').val().replace(/,/g, '')) || 0;
                const taxAmount = parseFloat($(this).find('input[name*="[tax_amount]"]').val().replace(/,/g, '')) || 0;
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                discountItems.push({
                    product_code: productCode,
                    code_string: codeString,
                    specification: specification,
                    size: size,
                    quantity: quantity,
                    area: area,
                    unit_price: unitPrice,
                    supply_amount: supplyAmount,
                    tax_amount: taxAmount,
                    remarks: remarks
                });
            });

            // 할인 기타 비용 데이터 생성
            const discountOtherCosts = [];
            $('.discount-cost-row').each(function() {
                const category = $(this).find('input[name*="[category]"]').val();
                const item = $(this).find('input[name*="[item]"]').val();
                const unit = $(this).find('input[name*="[unit]"]').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val().replace(/,/g, '')) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;
                const supplyAmount = parseFloat($(this).find('input[name*="[supply_amount]"]').val().replace(/,/g, '')) || 0;
                const taxAmount = parseFloat($(this).find('input[name*="[tax_amount]"]').val().replace(/,/g, '')) || 0;
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                discountOtherCosts.push({
                    category: category,
                    item: item,
                    unit: unit,
                    quantity: quantity,
                    unit_price: unitPrice,  
                    supply_amount: supplyAmount,
                    tax_amount: taxAmount,
                    remarks: remarks
                });
            });

            const notices = [];
            $('input[name="notices[]"]').each(function() {
                if ($(this).val().trim()) {
                    notices.push($(this).val());
                }
            });
            
            // 시공비 제외 체크박스 상태 추가
            const exclude_construction_cost = $('#exclude_construction_cost').is(':checked');
            // 몰딩 제외 체크박스 상태 추가
            const exclude_molding = $('#exclude_molding').is(':checked');
            
            // 폼 데이터 수집   
            const formData = new FormData($('#orderForm')[0]);
            // total_ex_vat input요소에 span class total_ex_vat 요소에서 원화표시 제거하고 넣기
            const total_ex_vat = $('span.total-ex-vat').text().replace(/[^\d]/g, '');
            const total_inc_vat = $('span.total-inc-vat').text().replace(/[^\d]/g, '');

            formData.append('total_ex_vat', total_ex_vat);
            formData.append('total_inc_vat', total_inc_vat);
            formData.append('items_json', JSON.stringify(items));
            formData.append('other_costs_json', JSON.stringify(otherCosts));
            formData.append('discount_items_json', JSON.stringify(discountItems));
            formData.append('discount_other_costs_json', JSON.stringify(discountOtherCosts));
            formData.append('notices_json', JSON.stringify(notices));
            formData.append('exclude_construction_cost', exclude_construction_cost ? '1' : '0');
            formData.append('exclude_molding', exclude_molding ? '1' : '0');
            formData.append('estimate_num', $('#estimate_num').val());
            
            // 디버그: 전송할 데이터 확인
            // console.log('items:', items);
            // console.log('otherCosts:', otherCosts);
            // console.log('discountItems:', discountItems);
            // console.log('discountOtherCosts:', discountOtherCosts);
            
            // AJAX 호출
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                timeout: 60000, // 30초 타임아웃
                beforeSend: function() {
                },
                success: function(response) {
                    
                    if (response.result === 'success') {
                        
                        Swal.fire({
                            icon: 'success',
                            title: '성공', 
                            text: response.message,
                            confirmButtonColor: '#3085d6',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    
                        setTimeout(function() {                    
                            // 성공 시 view 모드로 이동
                            const mode = '<?= $mode ?>';
                            const num = response.num;
                            // 복사인 경우 view 모드로 이동 (새로 생성된 num 사용)
                            // 부모창 새로고침
                            if(window.opener) {
                                window.opener.location.reload();
                            }
                            window.location.href = 'write_form.php?mode=view&num=' + num + '&tablename=phomi_order';                        
                        }, 1500);
                    } else {
                        alert('저장 중 오류가 발생했습니다: ' + response.message);
                        saveBtn.prop('disabled', false).text(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    
                    let errorMessage = '저장 중 오류가 발생했습니다.';
                    if (status === 'timeout') {
                        errorMessage = '요청 시간이 초과되었습니다.';
                    } else if (xhr.status === 0) {
                        errorMessage = '네트워크 연결을 확인해주세요.';
                    } else if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMessage = errorResponse.message || errorMessage;
                        } catch (e) {
                            errorMessage = error || errorMessage;
                        }
                    }
                    
                    alert(errorMessage);
                    saveBtn.prop('disabled', false).text(originalText);
                }
            });
        } catch (error) {
            alert('JavaScript 오류가 발생했습니다: ' + error.message);
            saveBtn.prop('disabled', false).text(originalText);
        }    
    });
}); // end of $(document).ready(function() 

function editOrder() {
    var num = $('#num').val();
    window.location.href = 'write_form.php?mode=modify&num=' + num + '&tablename=phomi_order';
}

function copyOrder() {
    var num = $('#num').val();
    window.location.href = 'write_form.php?mode=copy&num=' + num + '&tablename=phomi_order';
}

// PDF 수주서 생성 함수
function generatePDF() {
        var recipient = '<?= htmlspecialchars($recipient) ?>';
        var site_name = '<?= htmlspecialchars($site_name) ?>';
        var quote_date = '<?= $quote_date ?>';
        
        // 파일명 생성
        var today = new Date();
        var formattedDate = "(" + String(today.getFullYear()).slice(-2) + "." + ("0" + (today.getMonth() + 1)).slice(-2) + "." + ("0" + today.getDate()).slice(-2) + ")";
        // 파일명에서 특수문자 제거
        var sanitizedRecipient = recipient.replace(/[\\/:*?"<>|]/g, '');
        var sanitizedSiteName = site_name.replace(/[\\/:*?"<>|]/g, '');
        var result = '포미스톤수주서_' + sanitizedRecipient + '_' + sanitizedSiteName + formattedDate + '.pdf';     
        
        var element = document.getElementById('content-to-print');
        
        // PDF 생성 전에 작은 글씨 크기 적용
        element.style.fontSize = '10px';
        element.querySelectorAll('.table th, .table td').forEach(function(el) {
            el.style.fontSize = '10px';
            el.style.padding = '2px 4px';
            el.style.border = '0.1px solid #000';
            el.style.borderCollapse = 'collapse';
            el.style.borderWidth = '0.1px';
        });
        element.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(function(el) {
            el.style.fontSize = '14px';
        });
        element.querySelectorAll('.small').forEach(function(el) {
            el.style.fontSize = '8px';
        });
        
        // 테이블 테두리 강제 적용
        element.querySelectorAll('.table').forEach(function(table) {
            table.style.borderCollapse = 'collapse';
            table.style.border = '0.1px solid #000';
            table.style.borderSpacing = '0';
            table.style.width = '100%';
            table.style.borderWidth = '0.1px';
        });
        
        // 모든 테이블 셀에 매우 얇은 테두리 적용
        element.querySelectorAll('.table th, .table td').forEach(function(el) {
            el.style.border = '0.1px solid #000';
            el.style.borderCollapse = 'collapse';
            el.style.borderSpacing = '0';
            el.style.borderWidth = '0.1px';
        });
        
        // rowspan 셀 테두리 강제 적용
        element.querySelectorAll('td[rowspan]').forEach(function(cell) {
            cell.style.border = '0.1px solid #000';
            cell.style.borderCollapse = 'collapse';
            cell.style.borderRight = '0.1px solid #000';
            cell.style.borderLeft = '0.1px solid #000';
            cell.style.borderTop = '0.1px solid #000';
            cell.style.borderBottom = '0.1px solid #000';
            cell.style.borderWidth = '0.1px';
            cell.style.verticalAlign = 'middle';
        });
        
        // rowspan이 있는 행의 모든 셀에 테두리 강제 적용
        element.querySelectorAll('tr').forEach(function(row) {
            if (row.querySelector('td[rowspan]')) {
                row.querySelectorAll('td').forEach(function(cell) {
                    cell.style.border = '0.1px solid #000';
                    cell.style.borderCollapse = 'collapse';
                    cell.style.borderWidth = '0.1px';
                });
            }
        });
        
        var opt = {
            margin: [8, 2, 10, 2], // 더 작은 여백
            filename: result,
            image: { type: 'jpeg', quality: 1 },
            html2canvas: {
                scale: 4,   // 해상도를 2로 낮춤 (더 작은 글씨)
                useCORS: true,
                scrollY: 0,
                scrollX: 0,
                windowWidth: document.body.scrollWidth,
                windowHeight: document.body.scrollHeight        
            }, 
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: {
                mode: ['css', 'legacy'],
                avoid: ['tr', '.avoid-break'] // 페이지 나누기 방지
            }
        };
        
        html2pdf().from(element).set(opt).save().then(function() {
            // PDF 생성 후 원래 스타일로 복원
            element.style.fontSize = '';
            element.querySelectorAll('.table th, .table td').forEach(function(el) {
                el.style.fontSize = '';
                el.style.padding = '';
                el.style.border = '';
                el.style.borderCollapse = '';
            });
            element.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(function(el) {
                el.style.fontSize = '';
            });
            element.querySelectorAll('.small').forEach(function(el) {
                el.style.fontSize = '';
            });
            
            // 테이블 테두리 스타일 복원
            element.querySelectorAll('.table').forEach(function(table) {
                table.style.borderCollapse = '';
                table.style.border = '';
                table.style.borderSpacing = '';
                table.style.width = '';
            });
            
            // 모든 테이블 셀 스타일 복원
            element.querySelectorAll('.table th, .table td').forEach(function(el) {
                el.style.border = '';
                el.style.borderCollapse = '';
                el.style.borderSpacing = '';
            });
            
            // rowspan 셀 테두리 스타일 복원
            element.querySelectorAll('td[rowspan]').forEach(function(cell) {
                cell.style.border = '';
                cell.style.borderCollapse = '';
                cell.style.borderRight = '';
                cell.style.borderLeft = '';
                cell.style.borderTop = '';
                cell.style.borderBottom = '';
                cell.style.verticalAlign = '';
            });
            
            // rowspan이 있는 행의 모든 셀 스타일 복원
            element.querySelectorAll('tr').forEach(function(row) {
                if (row.querySelector('td[rowspan]')) {
                    row.querySelectorAll('td').forEach(function(cell) {
                        cell.style.border = '';
                        cell.style.borderCollapse = '';
                    });
                }
            });
        });
}
 
function openEstimatePopup() {
    var estimateNum = "<?= htmlspecialchars($estimate_num) ?>";
    if (!estimateNum) {
        alert("견적번호가 없습니다.");
        return;
    }
    var url = "/phomi/ET_write_form.php?mode=view&num=" + encodeURIComponent(estimateNum);
    window.open(url, "estimatePopup", "width=1200,height=900,scrollbars=yes,resizable=yes");
} 

function alertToast(message) {
    // 기본 배경 색상 (초록)
    let backgroundColor = "linear-gradient(to right, #00b09b, #96c93d)";

    // 조건에 따라 색상 변경
    if (message.includes("추가")) {
        backgroundColor = "linear-gradient(to right, #2196F3, #21CBF3)"; // 파란 계열
    } else if (message.includes("삭제")) {
        backgroundColor = "linear-gradient(to right, #f44336, #e57373)"; // 빨간 계열
    } else if (message.includes("복사")) {
        backgroundColor = "linear-gradient(to right, #4CAF50, #81C784)"; // 녹색 계열
    }

    Toastify({
        text: message,
        duration: 2000,
        close: true,
        gravity: "top",
        position: "center",
        style: {
            background: backgroundColor
        },
    }).showToast();	
}

// 삭제 함수
function deleteBtn() {
    var num = $('#num').val();
    $('#mode').val('delete');

    if (!num) {
        Swal.fire({
            icon: 'error',
            title: '오류',
            text: '삭제할 수주서를 찾을 수 없습니다.'
        });
        return;
    }
    
    // 삭제 확인
    Swal.fire({
        title: '삭제 확인',
        text: '정말로 수주서를 삭제하시겠습니까?\n삭제된 수주서는 복구할 수 없습니다.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '삭제',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX로 삭제 요청
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: {
                    num: num,
                    mode: $('#mode').val(),
                    tablename: $('#tablename').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '삭제 완료',
                            text: '수주서가 성공적으로 삭제되었습니다.'
                        }).then(() => {
                            // 부모창 새로고침 후 현재창 닫기
                            if (window.opener) {
                                window.opener.location.reload();
                            }
                            window.close();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '삭제 실패',
                            text: '삭제 중 오류가 발생했습니다: ' + response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: '오류',
                        text: '삭제 중 오류가 발생했습니다.'
                    });
                }
            });
        }
    });
}   

  
// 수주서에서 출고증으로 변환하는 함수
function convertToOutorder() {
    // 현재 수주서 데이터 수집
    var orderData = {
        quote_date: $('#order_date').val() || '',
        recipient: $('#recipient-text').text() || '미래기업',
        site_name: $('#site-name-text').text() || '',
        signer: $('.signer-text').text() || '소현철',
        hp: $('.hp-text').text() || '010-3784-5438',
        order_date: $('#order_date').val() || '',
        order_num: $('#num').val() || '',
        items: [],
        other_costs: [],
        //받는분, 받는분 전화번호 추가
        recipient_name: $('#recipient-name-text').text() || '',
        recipient_phone: $('#recipient-phone').text() || '',
    };
    
    // 아이템 데이터 수집
    $('.item-row-view').each(function() {
        var row = $(this);
        var item = {
            prodcode: row.find('.product-code').text(),
            quantity: parseFloat(row.find('.quantity-input').text()) || 0,                
            note: row.find('.remarks-input').text() || ''
        };
        orderData.items.push(item);
    });
    
    // 출고증 페이지로 데이터 전송
    var outorderUrl = 'OR_write_form.php?mode=insert&tablename=phomi_outorder';
    var form = $('<form>', {
        'method': 'POST',
        'action': outorderUrl
    });
    
    // 기타 비용 데이터 수집
    
    $('.other-cost-row-view').each(function(index) {
        var row = $(this);
        var category = row.find('.cost-category-input').text();
        var item = row.find('.cost-item-input').text();
        var quantityText = row.find('.cost-quantity-input').text();
        var unitPriceText = row.find('.cost-unit-price-input').text();
        var prodcode = '';
        
        
        // 본드와 몰딩에 대한 prodcode 설정
        if (item.indexOf('본드') !== -1) {
            prodcode = 'BOND';
        }
        if (item.indexOf('몰딩') !== -1) {
            prodcode = 'MOLDING';
        }
        
        var costItem = {
            prodcode: prodcode,
            category: category,
            item: item,
            unit: row.find('.cost-unit-input').text(),
            quantity: parseFloat(quantityText) || 0,                
            unit_price: parseFloat(unitPriceText.replace(/,/g, '')) || 0,
            remarks: row.find('.cost-remarks-input').text() || ''
        };
        
        // 빈 데이터가 아닌 경우에만 추가
        if (category || item || costItem.quantity > 0 || costItem.unit_price > 0) {
            orderData.other_costs.push(costItem);
        }
    });
    
    // 본드가 없으면 강제로 추가 (기존 로직)
    var hasBond = false;
    var bondQuantity = 1; // 기본 수량
    var bondUnitPrice = 5000; // 기본 단가
    
    orderData.other_costs.forEach(function(cost) {
        if (cost.item && cost.item.indexOf('본드') !== -1) {
            hasBond = true;
            // 기존 본드의 수량과 단가를 사용
            bondQuantity = cost.quantity || 1;
            bondUnitPrice = cost.unit_price || 5000;
        }
    });
    
    if (!hasBond) {
        orderData.other_costs.push({
            prodcode: 'BOND',
            category: '부자재',
            item: '본드',
            unit: 'EA',
            quantity: bondQuantity,
            unit_price: bondUnitPrice,
            remarks: '자동 추가'
        });
    } else {
    }
    
    // 데이터를 hidden input으로 추가
    form.append($('<input>', {
        'type': 'hidden',
        'name': 'order_data',
        'value': JSON.stringify(orderData)
    }));
                    
    // 폼을 body에 추가하고 제출 
    $('body').append(form);      

    form.submit();
}

// 중복 호출 방지를 위한 플래그
var isRenderingCards = false;
var renderCardsTimeout = null;
var processedTables = new Set(); // 처리된 테이블 추적 (전역 변수)

// 모바일에서 테이블을 카드 형식으로 변환하는 함수
function renderMobileCards() {
	// 이미 렌더링 중이면 무시
	if (isRenderingCards) {
		return;
	}
	
	// 데스크톱에서는 모든 카드 컨테이너 제거
	if (window.innerWidth > 768) {
		var containers = document.querySelectorAll('.mobile-cards-container');
		containers.forEach(function(container) {
			container.remove();
		});
		return;
	}
	
	// 입력 중이면 렌더링하지 않음
	if (isMobileInputActive && activeMobileInputElement) {
		return;
	}
	
	// 현재 포커스된 요소 저장 (input 필드인 경우)
	var activeElement = document.activeElement;
	var savedFocus = null;
	var savedValue = null;
	var savedSelectionStart = null;
	var savedSelectionEnd = null;
	
	if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA')) {
		// 입력 중이면 렌더링하지 않음
		if (isMobileInputActive && activeMobileInputElement === activeElement) {
			return;
		}
		
		savedFocus = {
			id: activeElement.id,
			name: activeElement.name,
			className: activeElement.className,
			value: activeElement.value,
			selectionStart: activeElement.selectionStart,
			selectionEnd: activeElement.selectionEnd
		};
		savedValue = activeElement.value;
		savedSelectionStart = activeElement.selectionStart;
		savedSelectionEnd = activeElement.selectionEnd;
	}
	
	// 렌더링 시작 플래그 설정
	isRenderingCards = true;
	
	// 모든 테이블에 대해 카드 변환
	var tables = document.querySelectorAll('table:not(.mobile-cards-container table)');
	
	tables.forEach(function(table) {
		// 테이블이 이미 숨겨져 있거나 카드 컨테이너 내부에 있는 경우 건너뛰기
		if (table.style.display === 'none' || table.closest('.mobile-cards-container')) {
			return;
		}
		
		// 합계 테이블은 카드로 변환하지 않음 (CSS로 직접 표시)
		if (table.classList.contains('total-summary-table')) {
			return;
		}
		
		// 테이블 ID 또는 고유 식별자 생성
		var tableId = table.id;
		if (!tableId) {
			var parent = table.parentElement;
			var tableIndex = Array.from(parent.querySelectorAll('table:not(.mobile-cards-container table)')).indexOf(table);
			var parentId = parent.id || parent.className || 'container';
			tableId = 'table-' + parentId.replace(/\s+/g, '-') + '-' + tableIndex;
		}
		
		// 이미 해당 테이블에 대한 카드 컨테이너가 있는지 확인
		var cardsContainer = document.querySelector('#mobileCardsContainer-' + tableId);
		if (!cardsContainer) {
			cardsContainer = document.createElement('div');
			cardsContainer.id = 'mobileCardsContainer-' + tableId;
			cardsContainer.className = 'mobile-cards-container';
			cardsContainer.setAttribute('data-table-id', tableId);
			cardsContainer.style.cssText = 'width: 100%; max-width: 100%; padding: 0.5rem 0;';
			
			if (table.nextSibling) {
				table.parentElement.insertBefore(cardsContainer, table.nextSibling);
			} else {
				table.parentElement.appendChild(cardsContainer);
			}
		}
		
		// 기존 내용 제거 (항상 새로 렌더링)
		cardsContainer.innerHTML = '';
		
		// 처리된 테이블로 표시 (중복 방지)
		processedTables.add(tableId);
		
		// tbody 처리
		var tbody = table.querySelector('tbody');
		if (tbody) {
			var rows = tbody.querySelectorAll('tr');
			rows.forEach(function(row) {
				var cells = row.querySelectorAll('td');
				if (cells.length === 0) return;
				
				var card = document.createElement('div');
				card.className = 'mobile-card';
				card.style.cssText = 'border: 1px solid #ddd; border-radius: 0.5rem; padding: 0.75rem; margin-bottom: 0.75rem; background: #f8f9fa;';
				
				// 테이블 헤더에서 라벨 가져오기
				var thead = table.querySelector('thead');
				var headers = [];
				if (thead) {
					var headerRow = thead.querySelector('tr');
					if (headerRow) {
						var headerCells = headerRow.querySelectorAll('th');
						headerCells.forEach(function(headerCell) {
							headers.push(headerCell.textContent.trim());
						});
					}
				}
				
				cells.forEach(function(cell, index) {
					var label = cell.getAttribute('data-label') || headers[index] || '항목 ' + (index + 1);
					
					var cardItem = document.createElement('div');
					cardItem.style.cssText = 'padding: 0.5rem 0; border-bottom: 1px solid #eee;';
					if (index === cells.length - 1) {
						cardItem.style.borderBottom = 'none';
					}
					
					var labelSpan = document.createElement('strong');
					labelSpan.textContent = label + ': ';
					labelSpan.style.cssText = 'color: #007bff; margin-right: 0.5rem;';
					
					var valueSpan = document.createElement('span');
					
					// select 요소가 있는 경우 특별 처리
					var selectElement = cell.querySelector('select');
					if (selectElement) {
						var originalValue = $(selectElement).val() || '';
						var originalRowIndex = selectElement.getAttribute('data-row') || '';
						
						var clonedSelect = selectElement.cloneNode(true);
						clonedSelect.style.cssText = 'width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; display: block !important; visibility: visible !important; opacity: 1 !important;';
						var baseClasses = selectElement.className.split(' ').filter(function(cls) {
							return cls !== 'select2-hidden-accessible';
						}).join(' ');
						clonedSelect.className = baseClasses;
						clonedSelect.removeAttribute('data-select2-id');
						clonedSelect.removeAttribute('tabindex');
						clonedSelect.removeAttribute('aria-hidden');
						clonedSelect.id = (selectElement.id || '') + '-mobile-' + originalRowIndex;
						clonedSelect.name = selectElement.name;
						clonedSelect.setAttribute('data-row', originalRowIndex);
						clonedSelect.setAttribute('data-original-select-id', selectElement.id || '');
						
						valueSpan.appendChild(clonedSelect);
						valueSpan.style.cssText = 'width: 100% !important; display: block !important;';
						
						clonedSelect.style.display = 'block';
						clonedSelect.style.visibility = 'visible';
						clonedSelect.style.opacity = '1';
						
						// 모바일 터치 이벤트 처리 - 이벤트 전파 방지
						clonedSelect.addEventListener('touchstart', function(e) {
							e.stopPropagation();
							this.focus();
						}, { passive: true });
						
						clonedSelect.addEventListener('touchend', function(e) {
							e.stopPropagation();
						}, { passive: true });
						
						clonedSelect.addEventListener('click', function(e) {
							e.stopPropagation();
							this.focus();
						});
						
						clonedSelect.addEventListener('focus', function(e) {
							e.stopPropagation();
						});
						
						clonedSelect.addEventListener('change', function(e) {
							e.stopPropagation();
						});
						
						setTimeout(function() {
							if (typeof $ !== 'undefined') {
								var $clonedSelect = $(clonedSelect);
								var $originalSelect = $(selectElement);
								
								$clonedSelect.css({
									'display': 'block',
									'visibility': 'visible',
									'opacity': '1',
									'width': '100%',
									'max-width': '100%',
									'box-sizing': 'border-box',
									'padding': '0.375rem 0.75rem',
									'font-size': '1rem',
									'line-height': '1.5',
									'border': '1px solid #ced4da',
									'border-radius': '0.25rem',
									'background-color': '#fff'
								});
								
								// 원본 select의 옵션을 복사
								var originalOptions = $originalSelect.find('option');
								$clonedSelect.empty();
								originalOptions.each(function() {
									var optionValue = $(this).val();
									var optionText = $(this).text();
									var isSelected = $(this).prop('selected');
									var optionData = {
										'data-spec': $(this).data('spec'),
										'data-size': $(this).data('size'),
										'data-thickness': $(this).data('thickness'),
										'data-area': $(this).data('area'),
										'data-unit-price': $(this).data('unit-price')
									};
									var newOption = $('<option>', {
										value: optionValue,
										text: optionText
									}).data(optionData);
									if (isSelected) {
										newOption.prop('selected', true);
									}
									$clonedSelect.append(newOption);
								});
								
								if (originalValue) {
									$clonedSelect.val(originalValue);
								}
								
								// change 이벤트 동기화
								var isSyncing = false;
								
								$clonedSelect.off('change.mobile-sync');
								$clonedSelect.on('change.mobile-sync', function(e) {
									if (isSyncing) {
										return;
									}
									
									if (e.isTrigger) {
										return;
									}
									
									isSyncing = true;
									var selectedValue = $clonedSelect.val();
									
									$originalSelect.off('change.mobile-sync');
									$originalSelect.val(selectedValue);
									
									if ($originalSelect.hasClass('select2-hidden-accessible')) {
										$originalSelect.trigger('change.select2');
										$originalSelect.trigger('select2:select');
									} else {
										$originalSelect.trigger('change');
									}
									
									// 모바일에서는 입력이 완전히 끝난 후에만 실행
									if (isMobileDevice()) {
										// 입력 중이면 지연
										var delayTime = isMobileInputActive ? 500 : 300;
										setTimeout(function() {
											if (typeof handleProductSelectChange === 'function') {
												handleProductSelectChange($originalSelect);
											}
										}, delayTime);
									} else {
										// PC에서는 즉시 실행
										setTimeout(function() {
											if (typeof handleProductSelectChange === 'function') {
												handleProductSelectChange($originalSelect);
											}
										}, 150);
									}
									
									setTimeout(function() {
										isSyncing = false;
									}, 300);
								});
								
								$originalSelect.off('change.mobile-sync');
							}
						}, 200);
					} else {
						// 입력 필드인 경우 클래스와 속성을 유지
						var inputElement = cell.querySelector('input');
						if (inputElement) {
							var clonedInput = inputElement.cloneNode(true);
							clonedInput.className = inputElement.className;
							clonedInput.name = inputElement.name;
							clonedInput.id = (inputElement.id || '') + '-mobile-' + (row.getAttribute('data-row') || '');
							clonedInput.value = inputElement.value;
							clonedInput.type = inputElement.type;
							clonedInput.placeholder = inputElement.placeholder;
							clonedInput.readOnly = inputElement.readOnly;
							clonedInput.step = inputElement.step;
							clonedInput.style.cssText = 'width: 100% !important; max-width: 100% !important; box-sizing: border-box !important;';
							
							// 모바일 터치 이벤트 처리 - 이벤트 전파 방지 및 포커스 유지
							clonedInput.addEventListener('touchstart', function(e) {
								e.stopPropagation();
								e.stopImmediatePropagation();
								e.preventDefault();
								var self = this;
								setTimeout(function() {
									self.focus();
								}, 10);
								return false;
							}, { passive: false, capture: true });
							
							clonedInput.addEventListener('touchend', function(e) {
								e.stopPropagation();
								e.stopImmediatePropagation();
								e.preventDefault();
								return false;
							}, { passive: false, capture: true });
							
							clonedInput.addEventListener('click', function(e) {
								e.stopPropagation();
								e.stopImmediatePropagation();
								e.preventDefault();
								var self = this;
								setTimeout(function() {
									self.focus();
								}, 10);
								return false;
							}, true);
							
							clonedInput.addEventListener('focus', function(e) {
								e.stopPropagation();
								e.stopImmediatePropagation();
							}, true);
							
							clonedInput.addEventListener('blur', function(e) {
								// 다른 input 필드로 포커스가 이동하는 경우에만 blur 허용
								var relatedTarget = e.relatedTarget;
								if (!relatedTarget || !relatedTarget.closest || !relatedTarget.closest('.mobile-card')) {
									// 포커스를 유지
									var self = this;
									setTimeout(function() {
										if (document.activeElement !== self) {
											self.focus();
										}
									}, 10);
								}
							}, true);
							
							clonedInput.addEventListener('input', function(e) {
								e.stopPropagation();
							}, true);
							
							valueSpan.appendChild(clonedInput);
						} else {
							valueSpan.innerHTML = cell.innerHTML;
						}
						valueSpan.style.cssText = 'word-wrap: break-word; overflow-wrap: break-word; width: 100% !important; max-width: 100% !important; box-sizing: border-box !important;';
					}
					
					// 버튼 클릭 이벤트 재바인딩
					if (cell.querySelector('.btn-group')) {
						var btnGroup = valueSpan.querySelector('.btn-group');
						if (btnGroup) {
							var currentStyle = btnGroup.getAttribute('style') || '';
							btnGroup.setAttribute('style', currentStyle + '; display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; width: auto !important; gap: 0.25rem !important; margin: 0 !important;');
						}
						
						var buttons = valueSpan.querySelectorAll('button');
						buttons.forEach(function(button) {
							var btnStyle = button.getAttribute('style') || '';
							button.setAttribute('style', btnStyle + '; width: 36px !important; min-width: 36px !important; max-width: 36px !important; height: 36px !important; flex: 0 0 36px !important; flex-shrink: 0 !important;');
							
							var originalOnclick = button.getAttribute('onclick');
							if (originalOnclick) {
								var rowIndex = row.getAttribute('data-row') || '';
								var rowClass = row.className;
								button.setAttribute('data-original-row', rowIndex);
								button.setAttribute('data-row-class', rowClass);
								
								button.removeAttribute('onclick');
								
								button.addEventListener('click', function(e) {
									e.stopPropagation();
									e.preventDefault();
									
									if (originalOnclick) {
										try {
											var match = originalOnclick.match(/(\w+)\(([^)]*)\)/);
											if (match) {
												var funcName = match[1];
												var args = match[2];
												if (window[funcName]) {
													if (args) {
														window[funcName](parseInt(args));
													} else {
														window[funcName]();
													}
												}
											}
										} catch (err) {
										}
									}
									
									setTimeout(function() {
										if (window.innerWidth <= 768) {
											processedTables.clear();
											renderMobileCards();
										}
									}, 300);
								});
							}
						});
					}
					
					cardItem.appendChild(labelSpan);
					cardItem.appendChild(valueSpan);
					card.appendChild(cardItem);
				});
				
				cardsContainer.appendChild(card);
			});
		}
		
		// 모든 모바일 카드의 input, select, textarea 필드에 터치 이벤트 리스너 추가 (동적 생성 요소 포함)
		setTimeout(function() {
			var allInputs = document.querySelectorAll('.mobile-card input, .mobile-card select, .mobile-card textarea');
			allInputs.forEach(function(input) {
				// 이미 이벤트 리스너가 있는지 확인하는 플래그
				if (input.hasAttribute('data-touch-events-bound')) {
					return; // 이미 바인딩됨
				}
				
				// 터치 이벤트 리스너 추가
				input.addEventListener('touchstart', function(e) {
					e.stopPropagation();
					e.stopImmediatePropagation();
					if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA') {
						this.focus();
					}
				}, { passive: true, capture: true });
				
				input.addEventListener('touchend', function(e) {
					e.stopPropagation();
					e.stopImmediatePropagation();
				}, { passive: true, capture: true });
				
				input.addEventListener('touchmove', function(e) {
					e.stopPropagation();
				}, { passive: true, capture: true });
				
				input.addEventListener('click', function(e) {
					e.stopPropagation();
					e.stopImmediatePropagation();
					if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA') {
						this.focus();
					}
				}, true);
				
				input.addEventListener('focus', function(e) {
					e.stopPropagation();
					e.stopImmediatePropagation();
				}, true);
				
				input.addEventListener('input', function(e) {
					e.stopPropagation();
				}, true);
				
				input.addEventListener('change', function(e) {
					e.stopPropagation();
				}, true);
				
				// 바인딩 완료 플래그 설정
				input.setAttribute('data-touch-events-bound', 'true');
			});
		}, 150);
		
		// tfoot 처리
		var tfoot = table.querySelector('tfoot');
		if (tfoot) {
			var tfootRow = tfoot.querySelector('tr');
			if (tfootRow) {
				var tfootCells = tfootRow.querySelectorAll('td');
				if (tfootCells.length > 0) {
					var summaryCard = document.createElement('div');
					summaryCard.className = 'mobile-card-summary';
					summaryCard.style.cssText = 'border: 2px solid #0dcaf0; border-radius: 0.5rem; padding: 0.75rem; margin-top: 1rem; background: #d1ecf1; font-weight: bold;';
					
					tfootCells.forEach(function(cell, index) {
						var summaryItem = document.createElement('div');
						summaryItem.style.cssText = 'padding: 0.5rem 0;';
						
						var label = document.createElement('strong');
						label.style.cssText = 'color: #0dcaf0; margin-right: 0.5rem;';
						label.textContent = (index === 0 ? '소계' : '합계') + ': ';
						
						var value = document.createElement('span');
						value.innerHTML = cell.innerHTML;
						
						summaryItem.appendChild(label);
						summaryItem.appendChild(value);
						summaryCard.appendChild(summaryItem);
					});
					
					cardsContainer.appendChild(summaryCard);
				}
			}
		}
	});
	
	// 렌더링 완료 플래그 해제
	setTimeout(function() {
		isRenderingCards = false;
	}, 100);
}

// debounce 함수
function debounce(func, wait) {
	return function() {
		var context = this;
		var args = arguments;
		clearTimeout(renderCardsTimeout);
		renderCardsTimeout = setTimeout(function() {
			func.apply(context, args);
		}, wait);
	};
}

// 모바일 입력 계산용 debounce 함수 (입력이 끝날 때까지 대기)
var mobileInputCalculationTimeouts = {};

function debounceMobileCalculation(inputId, calculationFunc, wait) {
	// 기존 타이머 취소
	if (mobileInputCalculationTimeouts[inputId]) {
		clearTimeout(mobileInputCalculationTimeouts[inputId]);
	}
	
	// 새 타이머 설정
	mobileInputCalculationTimeouts[inputId] = setTimeout(function() {
		calculationFunc();
		delete mobileInputCalculationTimeouts[inputId];
	}, wait);
}

// 모바일 환경 감지
function isMobileDevice() {
	return window.innerWidth <= 768 || 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}

// debounce된 카드 렌더링 함수
var debouncedRenderMobileCards = debounce(renderMobileCards, 300);

// 창 크기 변경 시 모바일 카드 다시 렌더링 및 괄호 처리
$(window).on('resize', function() {
	debouncedRenderMobileCards();
	removeVATParentheses();
});

// 모바일 입력 중 플래그 (전역 변수)
var isMobileInputActive = false;
var activeMobileInputElement = null;

// 모바일 환경에서 동적으로 생성된 모든 input, select, textarea 필드에 터치 이벤트 위임
$(document).on('touchstart', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
	e.stopPropagation();
	e.stopImmediatePropagation();
	
	if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
		// 입력 시작 플래그 설정
		isMobileInputActive = true;
		activeMobileInputElement = this;
		
		// 포커스 설정
		var self = this;
		setTimeout(function() {
			if (self && document.body.contains(self)) {
				self.focus();
				// 포커스가 실제로 설정되었는지 확인
				if (document.activeElement !== self) {
					self.focus();
				}
			}
		}, 50);
	}
	
	// preventDefault는 선택적으로만 사용 (키보드가 나오도록)
	return true;
});

$(document).on('touchend', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
	e.stopPropagation();
	e.stopImmediatePropagation();
	
	if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
		// 포커스 유지
		var self = this;
		setTimeout(function() {
			if (self && document.body.contains(self)) {
				self.focus();
			}
		}, 10);
	}
	
	return true;
});

$(document).on('touchmove', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
	e.stopPropagation();
	// 스크롤을 허용하기 위해 preventDefault는 사용하지 않음
});

$(document).on('click', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
	e.stopPropagation();
	e.stopImmediatePropagation();
	
	if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
		isMobileInputActive = true;
		activeMobileInputElement = this;
		
		var self = this;
		setTimeout(function() {
			if (self && document.body.contains(self)) {
				self.focus();
			}
		}, 10);
	}
	
	return false;
});

$(document).on('focus', '.mobile-card input, .mobile-card select, .mobile-card textarea', function(e) {
	e.stopPropagation();
	e.stopImmediatePropagation();
	
	if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA' || this.tagName === 'SELECT') {
		isMobileInputActive = true;
		activeMobileInputElement = this;
	}
});

// blur 이벤트 처리 - 입력 중에는 키보드가 사라지지 않도록 함
$(document).on('blur', '.mobile-card input, .mobile-card textarea', function(e) {
	if (!isMobileDevice()) {
		return; // 모바일이 아니면 일반 처리
	}
	
	var $input = $(this);
	var inputId = $input.attr('id') || $input.attr('name') || Math.random().toString(36);
	
	// 포커스가 다른 입력 필드로 이동하는 경우는 허용
	var relatedTarget = e.relatedTarget;
	if (relatedTarget && (relatedTarget.tagName === 'INPUT' || relatedTarget.tagName === 'TEXTAREA' || relatedTarget.tagName === 'SELECT')) {
		isMobileInputActive = true;
		activeMobileInputElement = relatedTarget;
		return;
	}
	
	// 계산이 필요한 입력 필드인지 확인
	var hasCalculationClass = $input.hasClass('quantity-input') || 
	                          $input.hasClass('unit-price-input') ||
	                          $input.hasClass('cost-quantity-input') ||
	                          $input.hasClass('cost-unit-price-input') ||
	                          $input.hasClass('discount-item-quantity-input') ||
	                          $input.hasClass('discount-item-unit-price-input') ||
	                          $input.hasClass('discount-cost-quantity-input') ||
	                          $input.hasClass('discount-cost-unit-price-input');
	
	// 계산이 필요한 필드인 경우 계산 먼저 실행
	if (hasCalculationClass) {
		// 입력 종료 플래그 설정 (약간의 지연 후)
		setTimeout(function() {
			// 포커스가 다른 입력 필드로 이동하지 않았으면 입력 종료
			var currentActive = document.activeElement;
			if (!currentActive || (currentActive.tagName !== 'INPUT' && currentActive.tagName !== 'TEXTAREA' && currentActive.tagName !== 'SELECT')) {
				isMobileInputActive = false;
				activeMobileInputElement = null;
			}
		}, 200);
		
		// 해당 input의 대기 중인 계산 즉시 실행
		if (mobileInputCalculationTimeouts[inputId]) {
			clearTimeout(mobileInputCalculationTimeouts[inputId]);
			delete mobileInputCalculationTimeouts[inputId];
		}
		
		// 모바일 카드에서 원본 테이블의 행 찾기
		var row = null;
		var cost_row = null;
		
		// 모바일 카드 내부인지 확인
		var $mobileCard = $input.closest('.mobile-card');
		if ($mobileCard.length > 0) {
			// 모바일 카드에서 data-row 속성 찾기 (select 또는 input에서)
			var $rowElement = $mobileCard.find('select[data-row], input[data-row]').first();
			if ($rowElement.length > 0) {
				var rowIndex = $rowElement.attr('data-row');
				if (rowIndex !== undefined && rowIndex !== '') {
					row = parseInt(rowIndex);
				}
			}
			
			// cost-row인지 확인
			if ($mobileCard.find('.cost-quantity-input, .cost-unit-price-input').length > 0) {
				cost_row = row;
			}
			
			// row를 찾지 못한 경우, input의 name 속성에서 추출 시도
			if (row === null || row === undefined) {
				var inputName = $input.attr('name') || '';
				var match = inputName.match(/\[(\d+)\]/);
				if (match && match[1]) {
					row = parseInt(match[1]);
				}
			}
			
			// 모바일 카드의 값을 원본 테이블에 동기화
			var inputValue = $input.val();
			var inputName = $input.attr('name');
			
			// 원본 테이블의 해당 입력 필드 찾기 및 값 동기화
			if (inputName) {
				var $originalInput = $('input[name="' + inputName + '"], select[name="' + inputName + '"]').not('.mobile-card input, .mobile-card select');
				if ($originalInput.length > 0) {
					$originalInput.val(inputValue);
					// change 이벤트 트리거 (Select2 등이 있는 경우)
					$originalInput.trigger('change');
				}
			}
		} else {
			// 원본 테이블에서 찾기
			row = $input.closest('.item-row, .cost-row, .discount-item-row').data('row');
			cost_row = $input.closest('.cost-row').data('row');
		}
		
		// 계산 실행 (즉시 실행)
		if (typeof executeCalculationPC === 'function' && row !== null && row !== undefined) {
			// 약간의 지연 후 계산 실행 (값 동기화 후)
			setTimeout(function() {
				executeCalculationPC($input, row, cost_row);
				
				// 소계 및 합계 업데이트 (모바일에서 명시적으로 호출)
				if (typeof updateItemSubtotals === 'function') {
					updateItemSubtotals();
				}
				if (typeof updateOtherCostsSubtotal === 'function') {
					updateOtherCostsSubtotal();
				}
				if (typeof updateDiscountItemSubtotals === 'function') {
					updateDiscountItemSubtotals();
				}
				if (typeof updateDiscountOtherCostsSubtotal === 'function') {
					updateDiscountOtherCostsSubtotal();
				}
				if (typeof updateTotals === 'function') {
					updateTotals();
				}
			}, 50);
		}
		
		// 계산이 필요한 필드는 포커스 복원하지 않음 (blur 허용)
		// 이벤트 전파는 막지 않아 다른 blur 이벤트 핸들러도 실행되도록 함
		// 하지만 이벤트가 중복 실행되지 않도록 stopPropagation은 하지 않음
	}
	
	// 계산이 필요 없는 필드만 포커스 복원 시도
	// 입력 중이면 포커스 복원 시도
	if (isMobileInputActive && activeMobileInputElement === this) {
		var self = this;
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		
		setTimeout(function() {
			if (self && document.body.contains(self)) {
				// 포커스 복원 시도
				self.focus();
				// 여전히 포커스가 없으면 다시 시도
				if (document.activeElement !== self) {
					setTimeout(function() {
						if (self && document.body.contains(self)) {
							self.focus();
						}
					}, 50);
				}
			}
		}, 10);
		
		return false;
	}
	// 다른 input 필드로 포커스가 이동하는 경우에만 blur 허용
	if (!relatedTarget || !$(relatedTarget).closest('.mobile-card').length) {
		// 계산이 필요 없는 필드만 포커스를 유지
		if (!hasCalculationClass) {
			// 포커스를 유지
			var self = this;
			setTimeout(function() {
				if (document.activeElement !== self) {
					self.focus();
				}
			}, 10);
		}
	}
});

// 모바일 카드에서 상품 선택 시 동적 업데이트 함수
function handleProductSelectChange(selectElement) {
	const selectedProductCode = selectElement.val();
	const selectedOption = selectElement.find('option:selected');
	
	const selectId = selectElement.attr('id') || '';
	const isMobileCardSelect = selectId.includes('-mobile-');
	
	let itemRow;
	if (isMobileCardSelect) {
		const originalSelectIdFromAttr = selectElement.attr('data-original-select-id');
		if (originalSelectIdFromAttr && originalSelectIdFromAttr !== '') {
			const $originalSelect = $('#' + originalSelectIdFromAttr);
			if ($originalSelect.length > 0) {
				return handleProductSelectChange($originalSelect);
			}
		}
		
		const rowIndex = selectElement.attr('data-row');
		if (rowIndex !== undefined && rowIndex !== '') {
			const $originalSelectByRow = $('.product-select[data-row="' + rowIndex + '"]').not('[id*="-mobile-"]');
			if ($originalSelectByRow.length > 0) {
				return handleProductSelectChange($originalSelectByRow.first());
			}
		}
		
		const mobileMatch = selectId.match(/^(.+)-mobile-\d+$/);
		if (mobileMatch && mobileMatch[1] && mobileMatch[1] !== '') {
			const originalSelectId = mobileMatch[1];
			const $originalSelect = $('#' + originalSelectId);
			if ($originalSelect.length > 0) {
				return handleProductSelectChange($originalSelect);
			}
		}
		return;
	} else {
		itemRow = selectElement.closest('.item-row');
	}

	if (!itemRow || itemRow.length === 0) {
		return;
	}
	
	if (selectedProductCode) {
		const spec = selectedOption.data('spec') || '';
		const size = selectedOption.data('size') || '';
		const area = selectedOption.data('area') || '';
		const unitPrice = selectedOption.data('unit-price') || '';
		
		const specInput = itemRow.find('.specification-input');
		const sizeInput = itemRow.find('.size-input');
		
		if (specInput.length === 0 || sizeInput.length === 0) {
			return;
		}
		
		specInput.val(size || '');
		sizeInput.val(spec || '');
		
		// 모바일 카드의 입력 필드도 업데이트
		const rowIndexForMobile = itemRow.attr('data-row');
		if (rowIndexForMobile !== undefined && rowIndexForMobile !== '') {
			const $mobileCard = $('.mobile-card').filter(function() {
				const $cardSelect = $(this).find('select[data-row="' + rowIndexForMobile + '"]');
				return $cardSelect.length > 0;
			});
			
			if ($mobileCard.length > 0) {
				const $mobileSpecInput = $mobileCard.find('.specification-input, input[name*="[specification]"]');
				const $mobileSizeInput = $mobileCard.find('.size-input, input[name*="[size]"]');
				
				if ($mobileSpecInput.length > 0) {
					$mobileSpecInput.val(size || '');
				}
				if ($mobileSizeInput.length > 0) {
					$mobileSizeInput.val(spec || '');
				}
			}
		}
		
		// 실제 면적 계산
		let actualArea = 0;
		if (size && typeof size === 'string') {
			if (size.includes('*')) {
				const sizeParts = size.split('*');
				if (sizeParts.length >= 2) {
					const width = parseFloat(sizeParts[0]) || 0;
					const height = parseFloat(sizeParts[1]) || 0;
					actualArea = (width * height) / 1000000;
				}
			} else if (size.includes('×')) {
				const sizeParts = size.split('×');
				if (sizeParts.length >= 2) {
					const width = parseFloat(sizeParts[0]) || 0;
					const height = parseFloat(sizeParts[1]) || 0;
					actualArea = (width * height) / 1000000;
				}
			}
		}
		
		let unitPriceVal = 0;
		if (unitPrice && unitPrice !== '' && !isNaN(unitPrice)) {
			unitPriceVal = parseFloat(unitPrice) || 0;
			itemRow.find('.unit-price-input').val(unitPriceVal.toLocaleString());
		}
		
		const existingQuantity = parseFloat(itemRow.find('.quantity-input').val()) || 0;
		const quantity = existingQuantity > 0 ? existingQuantity : 1;
		itemRow.find('.quantity-input').val(quantity);
		
		const totalArea = quantity * actualArea;
		itemRow.find('.area-input').val(totalArea.toFixed(2));
		
		// 금액 계산
		const supplyAmount = totalArea * unitPriceVal;
		const taxAmount = supplyAmount * 0.1;

		const $supplyAmountEl = itemRow.find('.supply-amount');
		const $taxAmountEl = itemRow.find('.tax-amount');
		
		if ($supplyAmountEl.length > 0 && $taxAmountEl.length > 0) {
			$supplyAmountEl.text('' + supplyAmount.toLocaleString());
			$taxAmountEl.text('' + taxAmount.toLocaleString());
		}
		
		// 모바일 카드의 단가, 수량, 면적, 금액 필드도 업데이트
		if (rowIndexForMobile !== undefined && rowIndexForMobile !== '') {
			const $mobileCard = $('.mobile-card').filter(function() {
				const $cardSelect = $(this).find('select[data-row="' + rowIndexForMobile + '"]');
				return $cardSelect.length > 0;
			});
			
			if ($mobileCard.length > 0) {
				const $mobileUnitPriceInput = $mobileCard.find('input.unit-price-input, input[name*="[unit_price]"]');
				const $mobileQuantityInput = $mobileCard.find('input.quantity-input, input[name*="[quantity]"]');
				const $mobileAreaInput = $mobileCard.find('input.area-input, input[name*="[area]"]');
				
				if ($mobileUnitPriceInput.length > 0) {
					$mobileUnitPriceInput.val(unitPriceVal > 0 ? unitPriceVal.toLocaleString() : '');
				}
				if ($mobileQuantityInput.length > 0) {
					$mobileQuantityInput.val(quantity);
				}
				if ($mobileAreaInput.length > 0) {
					$mobileAreaInput.val(totalArea.toFixed(2));
				}
				
				$mobileCard.find('strong').each(function() {
					const labelText = $(this).text().trim();
					if (labelText.includes('공급가액') || labelText.includes('Supply')) {
						const $nextSpan = $(this).next('span');
						if ($nextSpan.length > 0) {
							$nextSpan.text('' + supplyAmount.toLocaleString());
						}
					}
					if (labelText.includes('세액') || labelText.includes('Tax')) {
						const $nextSpan = $(this).next('span');
						if ($nextSpan.length > 0) {
							$nextSpan.text('' + taxAmount.toLocaleString());
						}
					}
				});
			}
		}

		if (typeof updateItemSubtotals === 'function') {
			updateItemSubtotals();
		}
		if (typeof updateTotals === 'function') {
			updateTotals();
		}
	}
}

// 수량을 변경시 금액 계산 (모바일 카드 지원)
function executeQuantityUnitPriceCalculation($input) {
	let row = $input.closest('tr.item-row');
	
	// 모바일 카드의 입력 필드인 경우 원본 테이블의 행 찾기
	if (row.length === 0 || !row.hasClass('item-row')) {
		const $mobileCard = $input.closest('.mobile-card');
		if ($mobileCard.length > 0) {
			const rowIndex = $mobileCard.find('select[data-row]').first().attr('data-row');
			if (rowIndex !== undefined && rowIndex !== '') {
				row = $('.item-row[data-row="' + rowIndex + '"]');
				
				if ($input.hasClass('quantity-input')) {
					const mobileQuantity = $input.val();
					row.find('.quantity-input').val(mobileQuantity);
				} else if ($input.hasClass('unit-price-input')) {
					const mobileUnitPrice = $input.val();
					row.find('.unit-price-input').val(mobileUnitPrice);
				}
			}
		}
	}
	
	if (row.length === 0 || !row.hasClass('item-row')) {
		return;
	}
	// 공통 계산 함수로 위임: m²/공급가액/세액까지 함께 갱신
	const rowIndex = row.data('row');
	if (rowIndex !== undefined && rowIndex !== null) {
		calculateItemAmount(rowIndex);
		if (typeof updateItemSubtotals === 'function') {
			updateItemSubtotals();
		}
		if (typeof updateTotals === 'function') {
			updateTotals();
		}
	}
}

$(document).on('input', '.quantity-input, .unit-price-input', function() {
	const $input = $(this);
	var inputId = $input.attr('id') || $input.attr('name') || 'quantity-unit-price-input-' + Math.random().toString(36).substr(2, 9);
	
	// PC/원본 테이블 입력은 상단 공통 계산 핸들러에서 처리하므로(중복 방지) 모바일 카드만 처리
	if ($input.closest('tr.item-row').length > 0) {
		return;
	}
	
	// 모바일 환경인 경우 입력이 끝날 때까지 대기 (800ms)
	if (isMobileDevice()) {
		debounceMobileCalculation(inputId, function() {
			executeQuantityUnitPriceCalculation($input);
		}, 800);
	} else {
		// PC 환경에서는 즉시 계산
		executeQuantityUnitPriceCalculation($input);
	}
});

// 모바일에서 blur 이벤트 시 즉시 계산 실행
$(document).on('blur', '.quantity-input, .unit-price-input', function() {
	if (isMobileDevice()) {
		const $input = $(this);
		var inputId = $input.attr('id') || $input.attr('name') || 'quantity-unit-price-input-' + Math.random().toString(36).substr(2, 9);
		
		// PC/원본 테이블 입력은 상단 공통 계산 핸들러에서 처리하므로(중복 방지) 모바일 카드만 처리
		if ($input.closest('tr.item-row').length > 0) {
			return;
		}
		
		// 해당 input의 대기 중인 계산 즉시 실행
		if (mobileInputCalculationTimeouts[inputId]) {
			clearTimeout(mobileInputCalculationTimeouts[inputId]);
			delete mobileInputCalculationTimeouts[inputId];
		}
		
		executeQuantityUnitPriceCalculation($input);
	}
});

// Select2 이벤트 (PC용)
$(document).on('select2:select select2:unselect', '.product-select', function() {
	handleProductSelectChange($(this));
});

// 일반 change 이벤트 (모바일용 및 PC 백업용)
function executeProductSelectChangeHandler($select) {
	if ($select.hasClass('select2-hidden-accessible')) {
		return;
	}
	if (typeof handleProductSelectChange === 'function') {
		handleProductSelectChange($select);
	}
}

$(document).on('change', '.product-select', function(e) {
	var $select = $(this);
	var selectId = $select.attr('id') || $select.attr('name') || 'product-select-change-' + Math.random().toString(36).substr(2, 9);
	
	// 모바일 환경인 경우 입력이 끝날 때까지 대기 (300ms)
	if (isMobileDevice()) {
		debounceMobileCalculation(selectId, function() {
			executeProductSelectChangeHandler($select);
		}, 300);
	} else {
		// PC 환경에서는 즉시 실행
		executeProductSelectChangeHandler($select);
	}
});

// 모바일 전용 전체 재계산 함수
function recalculateAllMobile() {
	if (!isMobileDevice()) {
		return; // 모바일이 아니면 실행하지 않음
	}
	
	// 모바일 카드의 모든 값을 원본 테이블에 동기화
	$('.mobile-card').each(function() {
		var $mobileCard = $(this);
		
		// 모바일 카드에서 data-row 찾기
		var rowIndex = $mobileCard.find('select[data-row], input[data-row]').first().attr('data-row');
		if (rowIndex === undefined || rowIndex === '') {
			// name 속성에서 추출 시도
			var $firstInput = $mobileCard.find('input[name*="["]').first();
			if ($firstInput.length > 0) {
				var inputName = $firstInput.attr('name') || '';
				var match = inputName.match(/\[(\d+)\]/);
				if (match && match[1]) {
					rowIndex = match[1];
				}
			}
		}
		
		if (rowIndex !== undefined && rowIndex !== '') {
			// 모바일 카드의 모든 입력 필드 값을 원본 테이블에 동기화
			$mobileCard.find('input, select, textarea').each(function() {
				var $mobileInput = $(this);
				var inputName = $mobileInput.attr('name');
				var inputValue = $mobileInput.val();
				
				if (inputName) {
					var $originalInput = $('input[name="' + inputName + '"], select[name="' + inputName + '"], textarea[name="' + inputName + '"]').not('.mobile-card input, .mobile-card select, .mobile-card textarea');
					if ($originalInput.length > 0) {
						$originalInput.val(inputValue);
						$originalInput.trigger('change');
					}
				}
			});
		}
	});
	
	// 모든 상품 행 계산
	$('.item-row').each(function() {
		var row = $(this).data('row');
		if (row !== undefined && row !== null) {
			calculateItemAmount(row);
			
			// 계산된 공급가액과 세액을 모바일 카드에 반영
			var $itemRow = $(this);
			var supplyAmountText = $itemRow.find('.supply-amount').text();
			var taxAmountText = $itemRow.find('.tax-amount').text();
			
			var $mobileCard = $('.mobile-card').filter(function() {
				var $cardSelect = $(this).find('select[data-row="' + row + '"]');
				return $cardSelect.length > 0;
			});
			
			if ($mobileCard.length > 0) {
				// 모바일 카드의 공급가액과 세액 업데이트
				$mobileCard.find('strong').each(function() {
					var labelText = $(this).text().trim();
					if (labelText.includes('공급가액') || labelText.includes('Supply')) {
						var $nextSpan = $(this).next('span');
						if ($nextSpan.length > 0) {
							$nextSpan.text(supplyAmountText);
						}
					}
					if (labelText.includes('세액') || labelText.includes('Tax')) {
						var $nextSpan = $(this).next('span');
						if ($nextSpan.length > 0) {
							$nextSpan.text(taxAmountText);
						}
					}
				});
			}
		}
	});
	
	// 모든 기타비용 행 계산
	$('.cost-row').each(function() {
		var $costRow = $(this);
		var costRowIndex = $costRow.data('row');
		calculateCostRow($costRow);
		
		// 계산된 공급가액과 세액을 모바일 카드에 반영
		var supplyAmount = $costRow.find('.cost-supply-amount').val();
		var taxAmount = $costRow.find('.cost-tax-amount').val();
		
		var $mobileCard = $('.mobile-card').filter(function() {
			var $cardInput = $(this).find('input[data-row="' + costRowIndex + '"]');
			return $cardInput.length > 0;
		});
		
		if ($mobileCard.length > 0) {
			// 모바일 카드의 공급가액과 세액 업데이트
			$mobileCard.find('strong').each(function() {
				var labelText = $(this).text().trim();
				if (labelText.includes('공급가액') || labelText.includes('Supply')) {
					var $nextSpan = $(this).next('span');
					if ($nextSpan.length > 0) {
						$nextSpan.text(supplyAmount || '0');
					}
				}
				if (labelText.includes('세액') || labelText.includes('Tax')) {
					var $nextSpan = $(this).next('span');
					if ($nextSpan.length > 0) {
						$nextSpan.text(taxAmount || '0');
					}
				}
			});
		}
	});
	
	// 모든 할인 상품 행 계산
	$('.discount-item-row').each(function() {
		var row = $(this).data('row');
		if (row !== undefined && row !== null) {
			calculateDiscountItemAmount(row);
			
			// 계산된 공급가액과 세액을 모바일 카드에 반영
			var $discountItemRow = $(this);
			var supplyAmountText = $discountItemRow.find('.discount-item-supply-amount').val() || '0';
			var taxAmountText = $discountItemRow.find('.discount-item-tax-amount').val() || '0';
			
			var $mobileCard = $('.mobile-card').filter(function() {
				var $cardInput = $(this).find('input[name*="discount_items[' + row + ']"]');
				return $cardInput.length > 0;
			});
			
			if ($mobileCard.length > 0) {
				$mobileCard.find('strong').each(function() {
					var labelText = $(this).text().trim();
					if (labelText.includes('공급가액') || labelText.includes('Supply')) {
						var $nextSpan = $(this).next('span');
						if ($nextSpan.length > 0) {
							$nextSpan.text(supplyAmountText.replace(/,/g, ''));
						}
					}
					if (labelText.includes('세액') || labelText.includes('Tax')) {
						var $nextSpan = $(this).next('span');
						if ($nextSpan.length > 0) {
							$nextSpan.text(taxAmountText.replace(/,/g, ''));
						}
					}
				});
			}
		}
	});
	
	// 모든 할인 기타비용 행 계산
	$('.discount-cost-row').each(function() {
		var $discountCostRow = $(this);
		var discountCostRowIndex = $discountCostRow.data('row');
		calculateDiscountCostRow($discountCostRow);
		
		// 계산된 공급가액과 세액을 모바일 카드에 반영
		var supplyAmount = $discountCostRow.find('.discount-cost-supply-amount').val();
		var taxAmount = $discountCostRow.find('.discount-cost-tax-amount').val();
		
		var $mobileCard = $('.mobile-card').filter(function() {
			var $cardInput = $(this).find('input[name*="discount_other_costs[' + discountCostRowIndex + ']"]');
			return $cardInput.length > 0;
		});
		
		if ($mobileCard.length > 0) {
			$mobileCard.find('strong').each(function() {
				var labelText = $(this).text().trim();
				if (labelText.includes('공급가액') || labelText.includes('Supply')) {
					var $nextSpan = $(this).next('span');
					if ($nextSpan.length > 0) {
						$nextSpan.text(supplyAmount || '0');
					}
				}
				if (labelText.includes('세액') || labelText.includes('Tax')) {
					var $nextSpan = $(this).next('span');
					if ($nextSpan.length > 0) {
						$nextSpan.text(taxAmount || '0');
					}
				}
			});
		}
	});
	
	// 기타비용 자동 계산 (체크박스가 체크되어 있는 경우)
	const etcAutoChecked = $('#etc_autocheck').is(':checked') || $('#etc_autocheck').val() === '1';
	if (etcAutoChecked) {
		calculateOtherCostsFromProducts(true);
	}
	
	// 소계 업데이트 (개별 행 계산 후)
	if (typeof updateItemSubtotals === 'function') {
		updateItemSubtotals();
	}
	if (typeof updateOtherCostsSubtotal === 'function') {
		updateOtherCostsSubtotal();
	}
	if (typeof updateDiscountItemSubtotals === 'function') {
		updateDiscountItemSubtotals();
	}
	if (typeof updateDiscountOtherCostsSubtotal === 'function') {
		updateDiscountOtherCostsSubtotal();
	}
	
	// 합계 업데이트 (소계 업데이트 후)
	if (typeof updateTotals === 'function') {
		updateTotals();
	}
	
	// 모바일 카드 다시 렌더링 (계산된 값 반영)
	setTimeout(function() {
		processedTables.clear();
		renderMobileCards();
		removeVATParentheses();
	}, 200);
	
	// 완료 메시지
	if (typeof alertToast === 'function') {
		alertToast('계산 완료');
	} else {
		alert('계산이 완료되었습니다.');
	}
}

// 초기 로드 시 모바일 카드 렌더링 및 괄호 처리
$(document).ready(function() {
	if (window.innerWidth <= 768) {
		setTimeout(function() {
			processedTables.clear();
			renderMobileCards();
			removeVATParentheses();
		}, 500);
	} else {
		removeVATParentheses();
	}
	
	// MutationObserver로 테이블 변경 감지
	if (window.innerWidth <= 768) {
		var observer = new MutationObserver(function(mutations) {
			// 입력 중이면 renderMobileCards 호출하지 않음
			if (isMobileInputActive && activeMobileInputElement) {
				return; // 입력 중에는 렌더링하지 않음
			}
			
			// input 필드에 포커스가 있으면 renderMobileCards 호출하지 않음
			var activeElement = document.activeElement;
			if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA' || activeElement.tagName === 'SELECT')) {
				return; // 포커스가 있는 동안은 렌더링하지 않음
			}
			
			var hasRowChange = false;
			mutations.forEach(function(mutation) {
				if (mutation.type === 'childList') {
					mutation.addedNodes.forEach(function(node) {
						if (node.nodeType === 1) {
							if (node.tagName === 'TR' || node.tagName === 'TBODY' || node.tagName === 'TABLE') {
								hasRowChange = true;
							}
							if (node.querySelector && node.querySelector('table')) {
								hasRowChange = true;
							}
						}
					});
					mutation.removedNodes.forEach(function(node) {
						if (node.nodeType === 1) {
							if (node.tagName === 'TR' || node.tagName === 'TBODY' || node.tagName === 'TABLE') {
								hasRowChange = true;
							}
						}
					});
				}
			});
			
			if (hasRowChange) {
				processedTables.clear();
				debouncedRenderMobileCards();
			}
		});
		
		var containersToObserve = document.querySelectorAll('.table-responsive, .card-body, tbody');
		containersToObserve.forEach(function(container) {
			observer.observe(container, {
				childList: true,
				subtree: false
			});
		});
	}
});


</script>
</body>
</html>