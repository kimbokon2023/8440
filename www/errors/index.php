<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 관리자 권한 설정
$admin = 0;
$admin_names = array('소현철', '김보곤', '최장중', '이경묵', '조경임');
if (in_array($user_name, $admin_names)) {
    $admin = 1;
}

$tablename = 'error';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 접속 IP 기록
$ip_address = $_SERVER["REMOTE_ADDR"] ?? '';
$ip_address = 'ip_(불량보고) : ' . $ip_address;

$data = date("Y-m-d H:i:s") . " - " . $user_id . " - " . $user_name . '  ' . $ip_address;

try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO {$DB}.logdata (data) VALUES (?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $data, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("접속 로그 기록 오류: " . $ex->getMessage());
}

// _row.php에서 사용되는 변수 초기화
$num = '';
$occur = '';
$occurconfirm = '';
$approve = '';
$errortype = '';
$place = '';
$reporter = '';
$content = '';
$method = '';
$involved = '';
$materialFee = 0;
$deliveryFee = 0;
$workFee = 0;
$etcFee = 0;
$totalFee = 0;

// 결재권자 결재정보 보기
$approvalwait = 0;

if ($admin == 1) {
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE approve <> '처리완료'";
    
    try {
        $stmh = $pdo->query($sql);
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include "_row.php";
            $approvalwait += 1;
        }
    } catch (PDOException $ex) {
        error_log("결재 대기 건 조회 오류: " . $ex->getMessage());
    }
}

// 서버의 정보를 읽어와 메인화면 꾸미기
$sql = "SELECT * FROM {$DB}.error ORDER BY num DESC";

$numarr = array();

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include "_row.php";
    }
} catch (PDOException $ex) {
    error_log("부적합 리스트 조회 오류: " . $ex->getMessage());
}

// 랜덤하게 유튜브 주소 추출
$youtube_arr = array();
array_push($youtube_arr, "https://www.youtube.com/embed/VPwhUEc84pg");
array_push($youtube_arr, "https://www.youtube.com/embed/NcFf9JhcHDQ");
array_push($youtube_arr, "https://www.youtube.com/embed/aXB5XNmG-TE");
array_push($youtube_arr, "https://www.youtube.com/embed/5ulG8-brBng");

$youtubeURL = $youtube_arr[rand(0, count($youtube_arr) - 1)];

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';

include getDocumentRoot() . '/load_header.php';

?>

<title>부적합 품질경영</title> 

<style>
/* 모바일 반응형 스타일 */
@media (max-width: 768px) {
	/* body와 html의 width 제한 */
	html, body {
		max-width: 100vw !important;
		overflow-x: hidden !important;
		font-size: 16px !important;
	}

	/* 컨테이너 패딩 조정 */
	.container-fluid {
		max-width: 100vw !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		overflow-x: hidden !important;
	}

	/* 카드 패딩 조정 */
	.card {
		margin-bottom: 10px !important;
	}

	.card-body {
		padding: 10px !important;
	}

	/* 제목 영역 모바일 최적화 */
	.d-flex h4, h4, h5 {
		font-size: 1.1rem !important;
		white-space: normal !important;
		margin-bottom: 10px !important;
	}

	/* 버튼 그룹 모바일 최적화 */
	.btn-sm {
		font-size: 0.85rem !important;
		padding: 0.4rem 0.6rem !important;
		white-space: nowrap !important;
		margin-bottom: 5px !important;
		margin-right: 3px !important;
	}

	/* 버튼 영역 줄바꿈 허용 */
	.d-flex.justify-content-center {
		flex-wrap: wrap !important;
		gap: 5px !important;
	}

	/* 검색 영역 모바일 최적화 */
	.d-flex.mb-2.px-5 {
		flex-wrap: nowrap !important;
		gap: 4px !important;
		padding: 0.5rem 0.25rem !important;
		margin: 0.5rem 0 !important;
		justify-content: center !important;
		align-items: center !important;
	}

	.d-flex.mb-2.px-5 > * {
		margin: 0 !important;
		flex-shrink: 0 !important;
	}

	.d-flex.mb-2.px-5 > span:first-child {
		font-size: 0.85rem !important;
		font-weight: 600 !important;
		white-space: nowrap !important;
	}

	.d-flex.mb-2.px-5 #search {
		width: auto !important;
		min-width: 120px !important;
		max-width: 200px !important;
		font-size: 0.85rem !important;
		padding: 0.35rem 0.5rem !important;
		height: 32px !important;
		flex: 1 1 auto !important;
	}

	.d-flex.mb-2.px-5 #searchBtn,
	.d-flex.mb-2.px-5 #writeBtn {
		font-size: 0.85rem !important;
		padding: 0.35rem 0.6rem !important;
		height: 32px !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
	}

	/* 테이블 모바일에서 카드 형태로 변환 */
	#myTable thead {
		display: none !important;
	}

	#myTable tbody tr {
		display: block !important;
		width: 100% !important;
		margin-bottom: 10px !important;
		border: 1px solid #dee2e6 !important;
		border-radius: 8px !important;
		background: white !important;
		box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
		padding: 8px !important;
		overflow: hidden !important;
		cursor: pointer !important;
	}

	#myTable tbody tr td {
		display: block !important;
		width: 100% !important;
		text-align: left !important;
		padding: 6px 4px !important;
		border: none !important;
		position: relative !important;
		padding-left: 35% !important;
		white-space: normal !important;
		word-wrap: break-word !important;
		min-height: 30px !important;
		font-size: 0.9rem !important;
		line-height: 1.4 !important;
	}

	#myTable tbody tr td:before {
		content: attr(data-label);
		position: absolute !important;
		left: 4px !important;
		width: 30% !important;
		padding-right: 3px !important;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
		font-weight: 600 !important;
		color: #6b7280 !important;
		font-size: 0.8rem !important;
	}

	#myTable tbody tr td:after {
		display: none !important;
	}

	/* 첫 번째 셀 (번호) 스타일 */
	#myTable tbody tr td:first-child {
		font-weight: 600 !important;
		color: #495057 !important;
		border-bottom: 1px solid #e9ecef !important;
		padding-bottom: 6px !important;
		margin-bottom: 4px !important;
	}

	/* 확인일 스타일 */
	#myTable tbody tr td:nth-child(2) {
		font-weight: 600 !important;
		color: #495057 !important;
	}

	/* 승인상태 강조 */
	#myTable tbody tr td:nth-child(3) {
		font-weight: 700 !important;
		color: #dc2626 !important;
	}

	/* 현장명 강조 */
	#myTable tbody tr td:nth-child(5) {
		background: #e7f3ff !important;
		font-weight: 700 !important;
		font-size: 1rem !important;
		color: #0056b3 !important;
		padding: 6px 4px !important;
		padding-left: 4px !important;
		margin: 4px 0 !important;
		border-radius: 4px !important;
		border-left: 4px solid #0056b3 !important;
		display: block !important;
	}

	#myTable tbody tr td:nth-child(5):before {
		position: static !important;
		display: block !important;
		width: 100% !important;
		margin-bottom: 2px !important;
		font-size: 0.8rem !important;
		color: #6b7280 !important;
		font-weight: 600 !important;
		overflow: visible !important;
		text-overflow: clip !important;
	}

	/* 비용 관련 셀 강조 */
	#myTable tbody tr td.text-end {
		font-weight: 600 !important;
		color: #dc2626 !important;
	}

	/* 테이블 반응형 컨테이너 */
	.table-responsive {
		overflow-x: visible !important;
		-webkit-overflow-scrolling: touch !important;
		width: 100% !important;
		margin-bottom: 15px !important;
	}

	/* 이미지 모바일 최적화 */
	img {
		max-width: 100% !important;
		height: auto !important;
	}

	/* 텍스트 영역 모바일 최적화 */
	.typing-txt {
		font-size: 0.85rem !important;
		white-space: normal !important;
	}

	/* col-sm-12 모바일에서 전체 너비 */
	.col-sm-12 {
		width: 100% !important;
		flex: 0 0 100% !important;
		max-width: 100% !important;
		margin-bottom: 10px !important;
	}
	
	/* 카드 최적화 */
	.card {
		width: calc(100% - 1rem) !important;
		max-width: calc(100% - 1rem) !important;
		margin: 0.5rem auto !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
	
	/* jQuery DataTable 컨트롤 숨기기 */
	.dataTables_length,
	.dataTables_filter {
		display: none !important;
	}
	
	/* 검색 UI 최적화 */
	.d-flex.mb-2.px-5 {
		flex-direction: column !important;
		align-items: stretch !important;
		gap: 0.5rem !important;
		padding: 0.5rem 0.25rem !important;
	}
	
	.d-flex.mb-2.px-5 > * {
		width: 100% !important;
		max-width: 100% !important;
		margin: 0.25rem 0 !important;
	}
	
	.d-flex.mb-2.px-5 #search {
		width: 100% !important;
		max-width: 100% !important;
		min-width: auto !important;
		flex: 1 1 auto !important;
	}
	
	.d-flex.mb-2.px-5 #searchBtn,
	.d-flex.mb-2.px-5 #writeBtn {
		width: 100% !important;
		max-width: 100% !important;
		flex: 1 1 auto !important;
	}
	
	/* 텍스트 오버플로우 방지 강화 */
	* {
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		box-sizing: border-box !important;
	}
	
	/* 모든 텍스트 요소 강제 줄바꿈 */
	p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		word-break: break-word !important;
		white-space: normal !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
	
	/* span 요소 줄바꿈 처리 */
	span {
		display: inline !important;
		overflow: visible !important;
	}
	
	/* 모달 최적화 */
	.modal {
		padding: 0 !important;
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
	}
	
	.modal-header {
		padding: 0.75rem 0.5rem !important;
		flex-shrink: 0 !important;
	}
	
	.modal-body {
		padding: 0.75rem 0.5rem !important;
		overflow-y: auto !important;
		flex: 1 1 auto !important;
		-webkit-overflow-scrolling: touch !important;
	}
	
	.modal-footer {
		padding: 0.75rem 0.5rem !important;
		flex-shrink: 0 !important;
	}
	
	/* SweetAlert2 모달 최적화 */
	.swal2-popup {
		width: 90% !important;
		max-width: 90% !important;
		padding: 1rem !important;
		font-size: 0.875rem !important;
	}
	
	.swal2-title {
		font-size: 1.125rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
	
	.swal2-content {
		font-size: 0.875rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
	
	.swal2-actions {
		flex-direction: column !important;
		gap: 0.5rem !important;
	}
	
	.swal2-confirm,
	.swal2-cancel {
		width: 100% !important;
		margin: 0 !important;
	}
	
	/* '기간' 버튼 숨기기 */
	#showdate {
		display: none !important;
	}
	
	/* 테이블 셀 텍스트 줄바꿈 강화 */
	#myTable tbody tr td {
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		word-break: break-word !important;
		white-space: normal !important;
		overflow: visible !important;
	}
	
	/* 이미지 최적화 */
	img {
		width: 100% !important;
		max-width: 100% !important;
		height: auto !important;
		object-fit: contain !important;
	}
}

/* PC 환경 버튼 간격 최적화 */
@media (min-width: 769px) {
	.d-flex.justify-content-center .btn,
	.d-flex.justify-content-start .btn,
	.d-flex.mb-2.px-5 .btn {
		margin-left: 0.25rem !important;
		margin-right: 0.25rem !important;
	}
}
</style>
</head>

<body>

<?php include includePath('myheader.php') ?>

<form name="board_form" id="board_form" method="post">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center mt-2 mb-2">
                                    <!-- 품질불량 관리기법 -->
                                    <div id="Materialshow">
                                        <h4 class="text-center">
                                            품질불량 관리기법
                                            <img src="<?= asset('img/click.gif') ?>" width="5%" height="5%" alt="클릭">
                                        </h4>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-center mt-2 mb-2">
                                    <div id="Material" style="display:none;">
                                        <section class="page-section">                                            
                                                <div class="row text-center">
                                                    <?php include '8d.php'; ?>
                                                </div>
                                                <div class="row text-left">
                                                    <?php include 'fmea.php'; ?>
                                                    <img src="<?= asset('img/qm1.jpg') ?>" alt="품질경영 1">
                                                    <img src="<?= asset('img/qm2.jpg') ?>" alt="품질경영 2">
                                                    <img src="<?= asset('img/qm3.jpg') ?>" alt="품질경영 3">
                                                    <img src="<?= asset('img/qm4.jpg') ?>" alt="품질경영 4">
                                                    <img src="<?= asset('img/qm5.jpg') ?>" alt="품질경영 5">
                                                    <img src="<?= asset('img/qm6.jpg') ?>" alt="품질경영 6">
                                                </div>
                                        </section>
                                    </div>
                                </div>
                                
                                <div class="d-flex px-1 px-lg-1 mt-1 justify-content-center">
                                    <h5 class="mb-1 text-secondary">지속적 관심/분석/개선이 불량감소에 큰 도움이 됩니다.</h5>
                                </div>
                                
                                <div class="d-flex mt-3 justify-content-center">
                                    <div class="typing-txt">
                                        승인상태 : 결재상신 -> 1차결재 -> 처리완료
                                    </div>
                                    <p class="typing"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
        <?php
        // 기간을 정하는 구간
        if ($fromdate == "") {
            $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
        }
        
        if ($todate == "") {
            $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
            $Transtodate = strtotime($todate . '+1 days');
            $Transtodate = date("Y-m-d", $Transtodate);
        } else {
            $Transtodate = strtotime($todate);
            $Transtodate = date("Y-m-d", $Transtodate);
        }
        
        // SQL 쿼리 생성
        if ($mode == "search" || $mode == "") {
            if ($search == "") {
                $sql = "SELECT * FROM {$DB}.error ORDER BY num DESC";
            } elseif ($search == "결재상신 1차결재") {
                $sql = "SELECT * FROM {$DB}.error WHERE approve = '결재상신' OR approve = '1차결재' ORDER BY num DESC";
                $search = null;
            } else {
                // 기본 SQL Injection 방어
                $search_safe = str_replace("'", "''", $search);
                $sql = "SELECT * FROM {$DB}.error WHERE " .
                       "(reporter LIKE '%{$search_safe}%') OR " .
                       "(place LIKE '%{$search_safe}%') OR " .
                       "(content LIKE '%{$search_safe}%') OR " .
                       "(method LIKE '%{$search_safe}%') OR " .
                       "(involved LIKE '%{$search_safe}%') OR " .
                       "(approve LIKE '%{$search_safe}%') " .
                       "ORDER BY occur DESC";
            }
        }
        
        // 레코드 조회
        try {
            $stmh = $pdo->query($sql);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                include "_row.php";
            }
        } catch (PDOException $ex) {
            error_log("부적합 검색 오류: " . $ex->getMessage());
        }
        
        // 전체 레코드 수 파악
        $total_row = 0;
        
        try {
            $stmh = $pdo->query($sql);
            $total_row = $stmh->rowCount();
        } catch (PDOException $ex) {
            error_log("레코드 수 조회 오류: " . $ex->getMessage());
        }
        ?>
        
        <input id="view_table" name="view_table" type="hidden" value="<?= htmlspecialchars($view_table) ?>">
        <input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert) ?>" size="5">
        <input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert) ?>" size="5">
        <input type="hidden" id="order_alert" name="order_alert" value="<?= htmlspecialchars($order_alert) ?>" size="5">
        
        <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
            ▷ <?= $total_row ?> 건 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" class="form-control me-2" style="width:150px;height:32px;" 
                   name="search" id="search" value="<?= htmlspecialchars($search) ?>" 
                   onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
            <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2">
                <i class="bi bi-search"></i> 검색
            </button>
            <button type="button" class="btn btn-dark btn-sm" id="writeBtn">
                <i class="bi bi-pencil"></i> 신규
            </button>
            &nbsp;&nbsp;&nbsp;
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover w-100" id="myTable">
                    <thead class="table-primary">
                    <tr class="middle-align">
                        <th class="text-center align-middle w30px">번호</th>
                        <th class="text-center align-middle w80px">확인일</th>
                        <th class="text-center align-middle w80px">승인상태</th>
                        <th class="text-center align-middle w80px">불량유형</th>
                        <th class="text-center align-middle w200px">현장명(품명)</th>
                        <th class="text-center align-middle w50px">보고자</th>
                        <th class="text-center align-middle w300px">발생원인(분석)</th>
                        <th class="text-center align-middle w300px">처리방안(개선사항)</th>
                        <th class="text-center align-middle">관련 직원</th>
                        <th class="text-center align-middle">자재비</th>
                        <th class="text-center align-middle">운송비</th>
                        <th class="text-center align-middle">시공비</th>
                        <th class="text-center align-middle">기타비용</th>
                        <th class="text-center align-middle">비용합계</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $start_num = $total_row;
                    
                    try {
                        $stmh = $pdo->query($sql);
                        
                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            include "_row.php";
                    ?>
                            <tr style="cursor: pointer;" onclick="popupCenter('write_form.php?num=<?= $num ?>', '부적합 보고서', 1000, 920); return false;">
                                <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                <td class="text-center" data-label="확인일"><?= htmlspecialchars($occurconfirm) ?></td>
                                <td class="text-center" data-label="승인상태"><?= htmlspecialchars($approve) ?></td>
                                <td class="text-center" data-label="불량유형"><?= htmlspecialchars($errortype) ?></td>
                                <td data-label="현장명(품명)"><?= htmlspecialchars($place) ?></td>
                                <td class="text-center" data-label="보고자"><?= htmlspecialchars($reporter) ?></td>
                                <td data-label="발생원인(분석)"><?= htmlspecialchars($content) ?></td>
                                <td data-label="처리방안(개선사항)"><?= htmlspecialchars($method) ?></td>
                                <td class="text-center" data-label="관련 직원"><?= htmlspecialchars($involved) ?></td>
                                <td class="text-end" data-label="자재비"><?= is_numeric($materialFee) ? number_format($materialFee) : '' ?></td>
                                <td class="text-end" data-label="운송비"><?= is_numeric($deliveryFee) ? number_format($deliveryFee) : '' ?></td>
                                <td class="text-end" data-label="시공비"><?= is_numeric($workFee) ? number_format($workFee) : '' ?></td>
                                <td class="text-end" data-label="기타비용"><?= is_numeric($etcFee) ? number_format($etcFee) : '' ?></td>
                                <td class="text-end" data-label="비용합계"><?= is_numeric($totalFee) ? number_format($totalFee) : '' ?></td>
                            </tr>
                    <?php
                            $start_num--;
                        }
                    } catch (PDOException $ex) {
                        error_log("테이블 출력 오류: " . $ex->getMessage());
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        </div>
    
    <!-- Footer -->
    <?php include "footer.php"; ?>
</form>

<form name="settingsFrm" id="settingsFrm" method="post" action="settings.php">
</form>

<!-- Core theme JS -->
<script src="<?= asset('error/js/scripts.js') ?>"></script>

<script>
// ES5 호환 JavaScript
var dataTable; // DataTables 인스턴스 전역 변수
var errorpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {
    // 모바일 환경에서 '기간' 버튼 숨기기
    if (window.innerWidth <= 768) {
        $('#showdate').hide();
    }
    
    // 창 크기 변경 시 '기간' 버튼 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('#showdate').hide();
        } else {
            $('#showdate').show();
        }
    });
    
    // 모바일 환경에서 jQuery DataTable 컨트롤 숨기기
    if (window.innerWidth <= 768) {
        $('.dataTables_length, .dataTables_filter').hide();
    }
    
    // 창 크기 변경 시 DataTable 컨트롤 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    });
    
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('errorpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        errorpageNumber = dataTable.page.info().page + 1;
        setCookie('errorpageNumber', errorpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('errorpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('errorpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = errorpageNumber;
    var url = "view.php?num=" + num + "&tablename=" + tablename;
    customPopup(url, '', 1200, 900);
}

$(document).ready(function() {
    $("#writeBtn").click(function() {
        var page = errorpageNumber;
        var tablename = '<?php echo $tablename; ?>';
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '', 1300, 850);
    });

    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });

    $("#adminprocess").click(function() {
        $('#search').val('결재상신 1차결재');
        document.getElementById('board_form').submit();
    });

    $("#searchNoinputBtn").click(function() {
        $('#search').val('');
        document.getElementById('board_form').submit();
    });

    // 서버에 작업 기록
    saveLogData('부적합 보고');
});
</script>

</body>
</html>


