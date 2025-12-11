<?php
/**
 * 도장 발주서 작성/수정 폼
 * 로컬 및 서버 환경 모두 지원
 */

// 공통 함수 및 세션 로드
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level >= 5) {
    sleep(1);
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $WebSite = "{$protocol}://{$host}/";
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}

// 페이지 설정
$title_message = '도장 발주';

// _request.php에서 로드될 변수들
require_once '_request.php';

// 데이터 관련 변수 초기화
$orderdate = date("Y-m-d");
$indate = date("Y-m-d");
$company = '';
$text = '';
$arr = array();
$user_name = $_SESSION['nick'] ?? '';
$counter = 0;

// 수정 또는 복사 모드일 때 데이터 조회
if ($mode == "modify" || $mode == "copy") {
    try {
        $sql = "SELECT * FROM mirae8440.make WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $num = $row["num"] ?? '';
            $orderdate = $row["orderdate"] ?? date("Y-m-d");
            $indate = $row["indate"] ?? date("Y-m-d");
            $company = $row["company"] ?? '';
            $text = $row["text"] ?? '';
        } else {
            echo "검색결과가 없습니다.<br>";
        }
        
    } catch (PDOException $ex) {
        error_log("발주서 조회 오류 (num: {$num}): " . $ex->getMessage());
        echo "오류: 발주서를 불러오는 중 문제가 발생했습니다.";
    }
}

// 발주처 배열 설정
$company_arr = array(
    "",
    "진성케미칼",
    "유일기업",
    "은성산업",
    "한산엘테크"
);
$company_count = count($company_arr);

// 발주처 기본값 설정
if (empty($company)) {
    $company = "유일기업";
}
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?></title>
<style>
/* 모바일 최적화 */
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
		padding: 5px !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
	}

	/* 카드 모바일 최적화 */
	.card {
		margin: 0.25rem 0 !important;
		width: 100% !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
	}

	.card-body {
		padding: 0.4rem 0.3rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.card-header {
		padding: 0.4rem 0.3rem !important;
		font-size: 0.9rem !important;
	}

	.card-header h4,
	.card-header h5 {
		font-size: 0.9rem !important;
		margin: 0 !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	/* 날짜/입력 필드 영역 모바일 최적화 */
	.d-flex.justify-content-center {
		flex-wrap: wrap !important;
		gap: 0.25rem !important;
		justify-content: flex-start !important;
		align-items: center !important;
		padding: 0.3rem 0.25rem !important;
		margin: 0.25rem 0 !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.d-flex.justify-content-center span,
	.d-flex.justify-content-center .badge {
		font-size: 0.75rem !important;
		margin-right: 0.3rem !important;
		flex-shrink: 0 !important;
		white-space: nowrap !important;
	}

	.d-flex.justify-content-center input[type="date"],
	.d-flex.justify-content-center input[type="text"],
	.d-flex.justify-content-center select {
		width: auto !important;
		min-width: 120px !important;
		max-width: calc(50% - 0.25rem) !important;
		font-size: 0.8rem !important;
		padding: 0.3rem 0.4rem !important;
		flex: 0 0 auto !important;
		margin: 0 0.15rem !important;
		box-sizing: border-box !important;
	}

	/* 버튼 영역 모바일 최적화 */
	.d-flex.justify-content-start {
		flex-wrap: wrap !important;
		gap: 0.2rem !important;
		justify-content: flex-start !important;
		align-items: center !important;
		padding: 0.3rem 0.25rem !important;
		margin: 0.25rem 0 !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	.btn {
		font-size: 0.75rem !important;
		padding: 0.25rem 0.4rem !important;
		white-space: nowrap !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		flex: 0 0 auto !important;
		margin: 0.1rem !important;
	}

	.btn-sm {
		font-size: 0.7rem !important;
		padding: 0.25rem 0.35rem !important;
	}

	/* 배지 모바일 최적화 */
	.badge {
		font-size: 0.7rem !important;
		padding: 0.3rem 0.5rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		display: inline-block !important;
		text-align: center !important;
		margin: 0.1rem !important;
	}

	/* TUI Grid 모바일 최적화 - 카드 형식 */
	#grid {
		display: none !important;
	}

	/* 모바일 카드 형식 컨테이너 */
	#mobile-grid-cards {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		padding: 0.3rem !important;
		display: block !important;
	}

	/* 모바일 카드 형식 개별 카드 */
	.mobile-grid-card {
		background: #fff !important;
		border: 1px solid #dee2e6 !important;
		border-radius: 0.25rem !important;
		box-shadow: 0 0.1rem 0.2rem rgba(0, 0, 0, 0.075) !important;
		padding: 0.4rem !important;
		margin-bottom: 0.4rem !important;
		box-sizing: border-box !important;
		width: 100% !important;
		max-width: 100% !important;
	}

	.mobile-grid-card:hover {
		background: #f8f9fa !important;
		box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.1) !important;
	}

	.mobile-grid-card-item {
		display: flex !important;
		width: 100% !important;
		padding: 0.3rem 0.4rem !important;
		border-bottom: 1px solid #f0f0f0 !important;
		box-sizing: border-box !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		flex-wrap: wrap !important;
	}

	.mobile-grid-card-item:last-child {
		border-bottom: none !important;
	}

	.mobile-grid-card-label {
		font-weight: bold !important;
		display: inline-block !important;
		min-width: 30% !important;
		margin-right: 0.5rem !important;
		color: #495057 !important;
		font-size: 0.75rem !important;
		flex-shrink: 0 !important;
	}

	.mobile-grid-card-value {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		font-size: 0.75rem !important;
		color: #212529 !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	/* 모바일 카드 입력 필드 스타일 */
	.mobile-grid-card input[type="text"],
	.mobile-grid-card input[type="number"],
	.mobile-grid-card select {
		width: 100% !important;
		max-width: 100% !important;
		font-size: 0.75rem !important;
		padding: 0.3rem 0.4rem !important;
		border: 1px solid #ced4da !important;
		border-radius: 0.25rem !important;
		box-sizing: border-box !important;
		background: #fff !important;
	}

	.mobile-grid-card input[type="text"]:focus,
	.mobile-grid-card input[type="number"]:focus,
	.mobile-grid-card select:focus {
		border-color: #80bdff !important;
		outline: 0 !important;
		box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
	}

	/* TUI Grid 컨테이너 모바일 최적화 */
	.d-flex.justify-content-center.mt-2.mb-1 {
		width: 100% !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		padding: 0.3rem !important;
		box-sizing: border-box !important;
		flex-direction: column !important;
		margin-top: 0.5rem !important;
		margin-bottom: 0.5rem !important;
	}

	/* 제목 영역 모바일 최적화 */
	.d-flex.justify-content-center.mt-2.mb-3 {
		margin-top: 0.5rem !important;
		margin-bottom: 0.5rem !important;
		padding: 0.3rem !important;
	}

	.d-flex.justify-content-center.mt-1.mb-1 {
		margin-top: 0.3rem !important;
		margin-bottom: 0.3rem !important;
		padding: 0.3rem !important;
	}

	.d-flex.justify-content-center.mt-3.mb-2 {
		margin-top: 0.5rem !important;
		margin-bottom: 0.5rem !important;
		padding: 0.3rem !important;
	}

	.d-flex.justify-content-center.mt-2.mb-2 {
		margin-top: 0.5rem !important;
		margin-bottom: 0.5rem !important;
		padding: 0.3rem !important;
	}

	.d-flex.justify-content-start.mt-3 {
		margin-top: 0.5rem !important;
	}

	/* 모든 요소가 카드 내부에 머물도록 */
	.card *,
	.container *,
	.container-fluid * {
		box-sizing: border-box !important;
		max-width: 100% !important;
	}

	.card button,
	.card .btn,
	.card span,
	.card input,
	.container button,
	.container .btn,
	.container span,
	.container input,
	.card-body *,
	.card-header * {
		max-width: 100% !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		box-sizing: border-box !important;
	}

	/* 카드 내부 모든 요소가 넘치지 않도록 */
	.card {
		overflow-x: hidden !important;
		overflow-y: visible !important;
	}

	.card-body {
		overflow-x: hidden !important;
		overflow-y: visible !important;
	}

	/* 폼 요소 모바일 최적화 */
	form {
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
	}

	form * {
		max-width: 100% !important;
		box-sizing: border-box !important;
	}
}

/* PC에서 모바일 카드 컨테이너 숨기기 */
@media (min-width: 769px) {
	#mobile-grid-cards {
		display: none !important;
	}
	
	#grid {
		display: block !important;
	}
}
</style>

</head>
<body>
     

<?php
// 폼 액션 URL 생성
$form_params = http_build_query([
    'mode' => $mode == 'modify' ? 'modify' : 'not',
    'num' => $num,
    'search' => $search,
    'find' => $find,
    'year' => $year,
    'process' => $process,
    'asprocess' => $asprocess,
    'fromdate' => $fromdate,
    'todate' => $todate,
    'separate_date' => $separate_date,
    'scale' => $scale
], '', '&', PHP_QUERY_RFC3986);

// 저장 버튼 URL 파라미터
$save_params = http_build_query([
    'num' => $num,
    'page' => $page,
    'text' => $text,
    'scale' => $scale
], '', '&', PHP_QUERY_RFC3986);
?>

<form id="board_form" name="board_form" method="post" action="insert.php?<?=$form_params?>">
    <div class="container-fluid">
        <div class="card mt-2 mb-4">
            <div class="card-body">
                <div class="card mt-2 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mt-2 mb-3">
                            <h5><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?> &nbsp; 발주번호: <?=htmlspecialchars($num, ENT_QUOTES, 'UTF-8')?></h5>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-1 mb-1">
                            <span class="badge bg-secondary fs-6">발주일</span>&nbsp;
                            <input type="date" id="orderdate" name="orderdate" value="<?=htmlspecialchars($orderdate, ENT_QUOTES, 'UTF-8')?>">&nbsp;&nbsp;
                            
                            <span class="badge bg-secondary fs-6">접수일</span>&nbsp;
                            <input type="date" id="indate" name="indate" value="<?=htmlspecialchars($indate, ENT_QUOTES, 'UTF-8')?>">&nbsp;&nbsp;
                            
                            <span class="badge bg-secondary fs-6">발주처</span>&nbsp;
                            <select name="company" id="company">
                                <?php
                                for ($i = 0; $i < $company_count; $i++) {
                                    $selected = ($company == $company_arr[$i]) ? ' selected' : '';
                                    echo '<option' . $selected . ' value="' . htmlspecialchars($company_arr[$i], ENT_QUOTES, 'UTF-8') . '">' 
                                         . htmlspecialchars($company_arr[$i], ENT_QUOTES, 'UTF-8') . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-3 mb-2">
                            <span class="badge bg-danger fs-6">콤마(,)를 사용하면 자료가 정확히 나오지 않습니다. 콤마(,)는 절대 사용하지 마세요!</span>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-2 mb-2">
                            <span class="fs-6" id="addressDisplay"></span>
                        </div>
                        
                        <div class="d-flex justify-content-start mt-3">
                            <button type="button" class="btn btn-dark btn-sm me-1" onclick="window.close();">&times; 닫기</button>
                            <button type="button" class="btn btn-dark btn-sm" onclick="savetext('insert.php?<?=$save_params?>')"><i class="bi bi-floppy-fill"></i> 저장</button>
                            
                            <!-- 행 추가/삭제 버튼 -->
                            <button type="button" class="btn btn-outline-primary btn-sm ms-5" id="insertRow">
                                <ion-icon name="add-outline"></ion-icon> 선택 밑 행 삽입
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm mx-4" id="deleteRow">
                                <ion-icon name="trash-outline"></ion-icon> 선택 행 삭제
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center mt-2 mb-1">
                    <div id="grid"></div>
                    <div id="mobile-grid-cards"></div>
                </div>
                
                <input type="hidden" id="textsave" name="textsave" value="<?=htmlspecialchars($text, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="mode" name="mode" value="<?=htmlspecialchars($mode, ENT_QUOTES, 'UTF-8')?>">
                
                <br><br>
            </div> <!--card-body-->
        </div> <!--card-->
    </div> <!--container-->
</form>
    
<script>
'use strict';

$(document).ready(function() {
    // PHP 변수를 JavaScript로 안전하게 전달
    var mode = <?php echo json_encode($mode); ?>;
    var arr = <?php echo json_encode($arr); ?>;
    var name = <?php echo json_encode($user_name); ?>;
    var counter = <?php echo json_encode($counter); ?>;
    
    // 배열 초기화
    var left_check = [];
    var mid_check = [];
    var right_check = [];
    var done_check = [];
    var remain_check = [];
    var tmp;
    
    // 그리드 설정
    var rowNum = <?php echo json_encode($counter); ?>;
    var row_count = 50;
    var COL_COUNT = 9;
    var isComposing = false;
    
    var data = [];
    var columns = [];
    
    // 텍스트 데이터 파싱
    var text = <?php echo json_encode($text); ?>;
    arr = text.split('|');
    
    if (mode !== 'not') {
        // 수정/복사 모드: 기존 데이터 로드
        for (var i = 0; i < arr.length - 1; i++) {
            var row = { name: i };
            tmp = arr[i].split(',');
            
            row['col1'] = tmp[0] || '';
            row['col2'] = tmp[1] || '';
            row['col3'] = tmp[2] || '';
            row['col4'] = tmp[3] || '';
            row['col5'] = tmp[4] || '';
            row['col6'] = tmp[5] || '';
            row['col7'] = tmp[6] || '';
            row['col8'] = '';
            row['col9'] = '';
            
            data.push(row);
        }
    } else {
        // 신규 모드: 빈 행 15개 생성
        for (var i = 0; i < 15; i++) {
            var row = { name: i };
            row['col1'] = '';
            row['col2'] = '';
            row['col3'] = '';
            row['col4'] = '';
            row['col5'] = '';
            row['col6'] = '';
            row['col7'] = '';
            row['col8'] = '';
            row['col9'] = '';
            data.push(row);
        }
    }

    // 모바일 감지 및 grid 변수 선언 (먼저 선언)
    var isMobile = window.innerWidth <= 768;
    var grid; // grid 변수 선언 (초기화 전에 선언)
    
    // 커스텀 텍스트 에디터 클래스
    class CustomTextEditor {
        constructor(props) {
            const el = document.createElement('input');
            const { maxLength } = props.columnInfo.editor.options;
            
            el.type = 'text';
            el.maxLength = maxLength;
            el.value = String(props.value);
            
            this.el = el;
            this.props = props;
            this.el.addEventListener('keydown', this.onKeyDown.bind(this));
        }
        
        onKeyDown(event) {
            var { key } = event;
            var { rowKey, columnName } = this.props;
            
            if (key === 'ArrowUp' || key === 'ArrowDown' || key === 'ArrowLeft' || key === 'ArrowRight') {
                event.preventDefault();
                event.stopPropagation();
                
                this.props.grid.finishEditing();
                this.navigateNextCell(key, rowKey, columnName);
            }
        }
        
        navigateNextCell(key, rowKey, columnName) {
            var grid = this.props.grid;
            var { rowKey: newRowKey, columnName: newColumnName } = grid.getFocusedCell();
            
            switch (key) {
                case 'ArrowUp':
                    newRowKey = Math.max(newRowKey - 1, 0);
                    break;
                case 'ArrowDown':
                    newRowKey = Math.min(newRowKey + 1, grid.getRowCount() - 1);
                    break;
                case 'ArrowLeft':
                    newColumnName = grid.prevColumnName(columnName);
                    break;
                case 'ArrowRight':
                    newColumnName = grid.nextColumnName(columnName);
                    break;
            }
            
            if (newRowKey !== rowKey || newColumnName !== columnName) {
                grid.focus(newRowKey, newColumnName);
                grid.startEditing(newRowKey, newColumnName);
            }
        }
        
        getElement() {
            return this.el;
        }
        
        getValue() {
            return this.el.value;
        }
        
        mounted() {
            this.el.focus();
            this.el.setSelectionRange(this.el.value.length, this.el.value.length);
        }
    }
    
    // 한글 입력 처리
    var keySequence = '';
    
    document.addEventListener('keydown', function(ev) {
        var key = ev.key;
        var target = ev.target;
        
        if (target.tagName === 'INPUT') {
            return;
        }
        
        // grid가 정의되지 않았거나 모바일 환경이면 리턴
        if (typeof grid === 'undefined' || !grid || isMobile) {
            return;
        }
        
        var focusedCell = grid.getFocusedCell();
        if (!focusedCell) return;
        
        var rowKey = focusedCell.rowKey;
        var columnName = focusedCell.columnName;
        
        // 한글 추적
        keySequence += key;
        
        // 한글 완성형 정규식
        var hangulRegex = /[\uAC00-\uD7AF]/;
        var hasHangul = hangulRegex.test(keySequence);
        
        if (hasHangul) {
            grid.setValue(rowKey, columnName, keySequence);
            grid.startEditing(rowKey, columnName, { editor: CustomTextEditor });
        } else if (!isComposing) {
            // 영문/숫자 키 체크
            var isAlphaNumericKey = (key.length === 1) && (
                (key.charCodeAt(0) >= 48 && key.charCodeAt(0) <= 57) ||  // 숫자 0-9
                (key.charCodeAt(0) >= 65 && key.charCodeAt(0) <= 90) ||  // 대문자 A-Z
                (key.charCodeAt(0) >= 97 && key.charCodeAt(0) <= 122)    // 소문자 a-z
            );
            
            if (isAlphaNumericKey) {
                grid.setValue(rowKey, columnName, key);
                grid.startEditing(rowKey, columnName, { editor: CustomTextEditor });
            }
        }
        
        // 키 시퀀스 리셋
        setTimeout(function() {
            keySequence = '';
        }, 200);
    });
    
    // 모바일 감지
    var isMobile = window.innerWidth <= 768;
    var grid; // grid 변수 선언 (초기화 전에 선언)
    
    // 모바일에서 카드 형식으로 표시하는 함수 (편집 가능)
    function renderMobileCards() {
        var cardsContainer = document.getElementById('mobile-grid-cards');
        var gridElement = document.getElementById('grid');
        
        if (isMobile) {
            // 모바일: 그리드 숨기고 카드 표시
            if (gridElement) {
                gridElement.style.display = 'none';
            }
            
            if (cardsContainer) {
                cardsContainer.innerHTML = '';
                
                // 컬럼 헤더 정의 및 입력 타입 (col1 제외)
                var columnConfig = {
                    'col2': { label: '현장명', type: 'text' },
                    'col3': { label: '품목', type: 'select', options: [
                        { text: '천장', value: '천장' },
                        { text: 'L/C', value: 'L/C' },
                        { text: '중판', value: '중판' },
                        { text: '커버', value: '커버' },
                        { text: '쪽쟘상판', value: '쪽쟘상판' },
                        { text: '쪽쟘기둥', value: '쪽쟘기둥' },
                        { text: '쟘상판', value: '쟘상판' },
                        { text: '쟘기둥', value: '쟘기둥' },
                        { text: '휀커버', value: '휀커버' },
                        { text: '보강', value: '보강' },
                        { text: '비상구', value: '비상구' },
                        { text: '', value: '' }
                    ]},
                    'col9': { label: '특이품목 기재시 적용', type: 'text' },
                    'col4': { label: '수량', type: 'text' },
                    'col5': { label: '단위', type: 'text' },
                    'col6': { label: '단가', type: 'text' },
                    'col7': { label: '기본색상', type: 'select', options: [
                        { text: '백색무광', value: '백색무광' },
                        { text: '백색유광', value: '백색유광' },
                        { text: '흑색무광', value: '흑색무광' },
                        { text: '흑색유광', value: '흑색유광' },
                        { text: '펄골드', value: '펄골드' },
                        { text: '진고동', value: '진고동' },
                        { text: '샴페인골드', value: '샴페인골드' },
                        { text: 'DK쿠퍼', value: 'DK쿠퍼' },
                        { text: '', value: '' }
                    ]},
                    'col8': { label: '별도색상인 경우(기재)', type: 'text' }
                };
                
                // 각 행을 카드로 변환
                data.forEach(function(row, index) {
                    var card = document.createElement('div');
                    card.className = 'mobile-grid-card';
                    card.setAttribute('data-row-index', index);
                    
                    var cardContent = '';
                    
                    // 각 컬럼을 카드 아이템으로 추가 (col1 제외)
                    Object.keys(columnConfig).forEach(function(colName) {
                        var config = columnConfig[colName];
                        var value = row[colName] || '';
                        var inputId = 'mobile-' + colName + '-' + index;
                        
                        cardContent += '<div class="mobile-grid-card-item">';
                        cardContent += '<span class="mobile-grid-card-label">' + config.label + '</span>';
                        cardContent += '<span class="mobile-grid-card-value">';
                        
                        if (config.type === 'select' && config.options) {
                            // Select 박스
                            cardContent += '<select id="' + inputId + '" class="mobile-input" data-col="' + colName + '" data-row="' + index + '">';
                            config.options.forEach(function(option) {
                                var selected = (value === option.value) ? ' selected' : '';
                                cardContent += '<option value="' + option.value + '"' + selected + '>' + option.text + '</option>';
                            });
                            cardContent += '</select>';
                        } else {
                            // Text 입력
                            cardContent += '<input type="text" id="' + inputId + '" class="mobile-input" data-col="' + colName + '" data-row="' + index + '" value="' + (value || '') + '" placeholder="입력하세요">';
                        }
                        
                        cardContent += '</span>';
                        cardContent += '</div>';
                    });
                    
                    card.innerHTML = cardContent;
                    cardsContainer.appendChild(card);
                });
                
                // 입력 필드 변경 이벤트 리스너 추가
                cardsContainer.querySelectorAll('.mobile-input').forEach(function(input) {
                    input.addEventListener('change', function() {
                        var rowIndex = parseInt(this.getAttribute('data-row'));
                        var colName = this.getAttribute('data-col');
                        var newValue = this.value;
                        
                        // 그리드 데이터 업데이트 (grid가 초기화된 경우에만)
                        if (grid && typeof grid.getRowCount === 'function' && typeof grid.setValue === 'function' && rowIndex < grid.getRowCount()) {
                            grid.setValue(rowIndex, colName, newValue);
                        }
                        
                        // 로컬 데이터도 업데이트
                        if (data[rowIndex]) {
                            data[rowIndex][colName] = newValue;
                        }
                        
                        // 자동 처리 로직 (그리드와 동일)
                        if (colName === 'col4' && newValue !== '') {
                            // 수량 입력 시 단위 자동 설정
                            var unitValue = data[rowIndex] ? data[rowIndex]['col5'] : '';
                            if (!unitValue || unitValue === '') {
                                if (grid && typeof grid.setValue === 'function') {
                                    grid.setValue(rowIndex, 'col5', 'EA');
                                }
                                if (data[rowIndex]) data[rowIndex]['col5'] = 'EA';
                                var unitInput = document.getElementById('mobile-col5-' + rowIndex);
                                if (unitInput) unitInput.value = 'EA';
                            }
                        }
                        
                        if (colName === 'col8' && newValue !== '') {
                            // 별도 색상이 입력되면 기본 색상에 복사
                            if (grid && typeof grid.setValue === 'function') {
                                grid.setValue(rowIndex, 'col7', newValue);
                            }
                            if (data[rowIndex]) data[rowIndex]['col7'] = newValue;
                            var colorInput = document.getElementById('mobile-col7-' + rowIndex);
                            if (colorInput && colorInput.tagName === 'SELECT') {
                                colorInput.value = newValue;
                            } else if (colorInput) {
                                colorInput.value = newValue;
                            }
                        }
                        
                        if (colName === 'col9' && newValue !== '') {
                            // 특이 품목이 입력되면 품목에 복사
                            if (grid && typeof grid.setValue === 'function') {
                                grid.setValue(rowIndex, 'col3', newValue);
                            }
                            if (data[rowIndex]) data[rowIndex]['col3'] = newValue;
                            var itemInput = document.getElementById('mobile-col3-' + rowIndex);
                            if (itemInput && itemInput.tagName === 'SELECT') {
                                itemInput.value = newValue;
                            } else if (itemInput) {
                                itemInput.value = newValue;
                            }
                        }
                    });
                });
                
                cardsContainer.style.display = 'block';
            }
        } else {
            // PC: 카드 숨기고 그리드 표시
            if (cardsContainer) {
                cardsContainer.style.display = 'none';
            }
            if (gridElement) {
                gridElement.style.display = 'block';
            }
        }
    }
    
    // TUI Grid 초기화
    grid = new tui.Grid({
        el: document.getElementById('grid'),
        data: data,
        bodyHeight: isMobile ? 400 : 620,
        columns: [
            {
                header: '구분',
                name: 'col1',
                sortingType: 'desc',
                sortable: true,
                width: 50,
                editor: {
                    type: CustomTextEditor,
                    options: {
                        maxLength: 50
                    }
                },
                align: 'center'
            },
            {
                header: '현장명',
                name: 'col2',
                width: 300,
                editor: {
                    type: CustomTextEditor,
                    options: {
                        maxLength: 50
                    }
                },
                align: 'center'
            },
            {
                header: '품목',
                name: 'col3',
                width: 200,
                editor: {
                    type: 'select',
                    options: {
                        maxLength: 20,
                        useViewMode: true,
                        listItems: [
                            { text: '천장', value: '천장' },
                            { text: 'L/C', value: 'L/C' },
                            { text: '중판', value: '중판' },
                            { text: '커버', value: '커버' },
                            { text: '쪽쟘상판', value: '쪽쟘상판' },
                            { text: '쪽쟘기둥', value: '쪽쟘기둥' },
                            { text: '쟘상판', value: '쟘상판' },
                            { text: '쟘기둥', value: '쟘기둥' },
                            { text: '휀커버', value: '휀커버' },
                            { text: '보강', value: '보강' },
                            { text: '비상구', value: '비상구' },
                            { text: '', value: '' }
                        ]
                    }
                },
                align: 'center'
            },
            {
                header: '특이품목 기재시 적용',
                name: 'col9',
                width: 150,
                editor: {
                    type: CustomTextEditor,
                    options: {
                        maxLength: 20
                    }
                },
                align: 'center'
            },
            {
                header: '수량',
                name: 'col4',
                width: 70,
                editor: {
                    type: CustomTextEditor,
                    options: {
                        maxLength: 50
                    }
                },
                align: 'center'
            },
            {
                header: '단위',
                name: 'col5',
                width: 70,
                editor: {
                    type: CustomTextEditor,
                    options: {
                        maxLength: 50
                    }
                },
                align: 'center'
            },
            {
                header: '단가',
                name: 'col6',
                width: 60,
                editor: {
                    type: CustomTextEditor,
                    options: {
                        maxLength: 50
                    }
                },
                align: 'center'
            },
            {
                header: '기본색상',
                name: 'col7',
                width: 160,
                editor: {
                    type: 'select',
                    options: {
                        maxLength: 20,
                        useViewMode: true,
                        listItems: [
                            { text: '백색무광', value: '백색무광' },
                            { text: '백색유광', value: '백색유광' },
                            { text: '흑색무광', value: '흑색무광' },
                            { text: '흑색유광', value: '흑색유광' },
                            { text: '펄골드', value: '펄골드' },
                            { text: '진고동', value: '진고동' },
                            { text: '샴페인골드', value: '샴페인골드' },
                            { text: 'DK쿠퍼', value: 'DK쿠퍼' },
                            { text: '', value: '' }
                        ]
                    }
                },
                align: 'center'
            },
            {
                header: '별도색상인 경우(기재)',
                name: 'col8',
                width: 200,
                editor: {
                    type: CustomTextEditor,
                    options: {
                        maxLength: 50
                    }
                },
                align: 'center'
            }
        ],
        columnOptions: {
            resizable: true
        },
        rowHeaders: ['rowNum', 'checkbox'],
        pageOptions: {
            useClient: false,
            perPage: 20
        }
    });
			
    // 저장 함수 (모바일/PC 모두 지원)
    window.savetext = function(href) {
        var tmp = "";
        
        if (isMobile) {
            // 모바일: 카드에서 데이터 가져오기
            var cardsContainer = document.getElementById('mobile-grid-cards');
            if (cardsContainer) {
                var cards = cardsContainer.querySelectorAll('.mobile-grid-card');
                cards.forEach(function(card, index) {
                    var rowIndex = parseInt(card.getAttribute('data-row-index'));
                    var rowData = data[rowIndex] || {};
                    
                    tmp += (rowData['col1'] || '');
                    tmp += ',' + (rowData['col2'] || '');
                    tmp += ',' + (rowData['col3'] || '');
                    tmp += ',' + (rowData['col4'] || '');
                    tmp += ',' + (rowData['col5'] || '');
                    tmp += ',' + (rowData['col6'] || '');
                    tmp += ',' + (rowData['col7'] || '');
                    tmp += '|';
                });
            }
        } else {
            // PC: 그리드에서 데이터 가져오기 (grid가 초기화된 경우에만)
            if (grid && typeof grid.getRowCount === 'function' && typeof grid.getValue === 'function') {
                for (var i = 0; i < grid.getRowCount(); i++) {
                    tmp += grid.getValue(i, 'col1');
                    tmp += ',' + grid.getValue(i, 'col2');
                    tmp += ',' + grid.getValue(i, 'col3');
                    tmp += ',' + grid.getValue(i, 'col4');
                    tmp += ',' + grid.getValue(i, 'col5');
                    tmp += ',' + grid.getValue(i, 'col6');
                    tmp += ',' + grid.getValue(i, 'col7');
                    tmp += '|';
                }
            } else {
                // grid가 없으면 data 배열에서 가져오기
                data.forEach(function(row) {
                    tmp += (row['col1'] || '');
                    tmp += ',' + (row['col2'] || '');
                    tmp += ',' + (row['col3'] || '');
                    tmp += ',' + (row['col4'] || '');
                    tmp += ',' + (row['col5'] || '');
                    tmp += ',' + (row['col6'] || '');
                    tmp += ',' + (row['col7'] || '');
                    tmp += '|';
                });
            }
        }
        
        $("#textsave").val(tmp);
        console.log('저장 데이터:', tmp);
        $("#board_form").submit();
    };
    
    // col1 컬럼 숨기기 (grid가 존재할 때만)
    if (grid && typeof grid.hideColumn === 'function') {
        grid.hideColumn('col1');
    }
    
    // 초기 로드 시 모바일 카드 형식 적용
    renderMobileCards();
    
    // 그리드 데이터 변경 시 카드 업데이트 (PC에서만)
    if (grid && typeof grid.on === 'function' && typeof grid.getRowCount === 'function') {
        grid.on('afterChange', function() {
            if (!isMobile) {
                // PC에서만 그리드 데이터를 로컬 데이터에 동기화
                for (var i = 0; i < grid.getRowCount(); i++) {
                    if (data[i]) {
                        data[i]['col1'] = grid.getValue(i, 'col1') || '';
                        data[i]['col2'] = grid.getValue(i, 'col2') || '';
                        data[i]['col3'] = grid.getValue(i, 'col3') || '';
                        data[i]['col4'] = grid.getValue(i, 'col4') || '';
                        data[i]['col5'] = grid.getValue(i, 'col5') || '';
                        data[i]['col6'] = grid.getValue(i, 'col6') || '';
                        data[i]['col7'] = grid.getValue(i, 'col7') || '';
                        data[i]['col8'] = grid.getValue(i, 'col8') || '';
                        data[i]['col9'] = grid.getValue(i, 'col9') || '';
                    }
                }
            }
        });
    }
    
    // 윈도우 리사이즈 이벤트 처리
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            isMobile = window.innerWidth <= 768;
            renderMobileCards();
        }, 250);
    });
    
    // Cell 편집 완료 이벤트 (grid가 존재할 때만)
    if (grid && typeof grid.on === 'function') {
        grid.on('editingFinish', function(ev) {
        var i = ev.rowKey;
        var set_num = Number(grid.getValue(i, 'col4'));
        var EAstring = grid.getValue(i, 'col5');
        var set_color = grid.getValue(i, 'col8');
        var set_item = grid.getValue(i, 'col9');
        var set_place = grid.getValue(i, 'col2');
        
        console.log('행 변경:', ev.rowKey);
        
        // 수량 입력 시 단위 자동 설정
        if (set_num !== '' && EAstring === '') {
            grid.setValue(i, 'col5', 'EA');
        }
        
        // 별도 색상이 입력되면 기본 색상에 복사
        if (set_color !== '') {
            grid.setValue(i, 'col7', set_color);
        }
        
        // 특이 품목이 입력되면 품목에 복사
        if (set_item !== '') {
            grid.setValue(i, 'col3', set_item);
        }
        });
    }
				
    // 행 추가 버튼 이벤트
    $("#insertRow").click(function() {
        if (!grid || typeof grid.getCheckedRows !== 'function') {
            alert('그리드가 초기화되지 않았습니다.');
            return;
        }
        var checkedRows = grid.getCheckedRows();
        
        if (checkedRows.length === 0) {
            alert("먼저 삽입할 위치의 행을 선택하세요.");
            return;
        }
        
        var firstCheckedRowKey = checkedRows[0].rowKey;
        var rowIndex = firstCheckedRowKey + 1;
        
        console.log("삽입 위치:", rowIndex);
        
        // 기존 데이터를 한 칸씩 뒤로 이동
        for (var i = grid.getRowCount() - 1; i >= rowIndex; i--) {
            var rowData = grid.getRow(i);
            grid.setValue(i + 1, 'col1', rowData.col1);
            grid.setValue(i + 1, 'col2', rowData.col2);
            grid.setValue(i + 1, 'col3', rowData.col3);
            grid.setValue(i + 1, 'col4', rowData.col4);
            grid.setValue(i + 1, 'col5', rowData.col5);
            grid.setValue(i + 1, 'col6', rowData.col6);
            grid.setValue(i + 1, 'col7', rowData.col7);
            grid.setValue(i + 1, 'col8', rowData.col8);
            grid.setValue(i + 1, 'col9', rowData.col9);
        }
        
        // 새로운 빈 행 추가
        grid.setValue(rowIndex, 'col1', '');
        grid.setValue(rowIndex, 'col2', '');
        grid.setValue(rowIndex, 'col3', '');
        grid.setValue(rowIndex, 'col4', '');
        grid.setValue(rowIndex, 'col5', '');
        grid.setValue(rowIndex, 'col6', '');
        grid.setValue(rowIndex, 'col7', '');
        grid.setValue(rowIndex, 'col8', '');
        grid.setValue(rowIndex, 'col9', '');
        
        grid.focus(rowIndex, 'col2');
    });
    
    // 행 삭제 버튼 이벤트
    $("#deleteRow").click(function() {
        if (!grid || typeof grid.getCheckedRows !== 'function') {
            alert('그리드가 초기화되지 않았습니다.');
            return;
        }
        var checkedRows = grid.getCheckedRows();
        
        if (checkedRows.length === 0) {
            alert("삭제할 행을 선택하세요.");
            return;
        }
        
        var checkedRowKeys = checkedRows.map(function(row) {
            return row.rowKey;
        }).sort(function(a, b) {
            return a - b;
        });
        
        checkedRowKeys.forEach(function(rowKey) {
            var rowCount = grid.getRowCount();
            
            // 선택된 행부터 마지막 행까지 한 칸씩 앞으로 이동
            for (var i = rowKey; i < rowCount - 1; i++) {
                var nextRow = grid.getRow(i + 1);
                grid.setValue(i, 'col1', nextRow.col1);
                grid.setValue(i, 'col2', nextRow.col2);
                grid.setValue(i, 'col3', nextRow.col3);
                grid.setValue(i, 'col4', nextRow.col4);
                grid.setValue(i, 'col5', nextRow.col5);
                grid.setValue(i, 'col6', nextRow.col6);
                grid.setValue(i, 'col7', nextRow.col7);
                grid.setValue(i, 'col8', nextRow.col8);
                grid.setValue(i, 'col9', nextRow.col9);
            }
            
            // 마지막 행 초기화
            var lastIndex = grid.getRowCount() - 1;
            grid.setValue(lastIndex, 'col1', '');
            grid.setValue(lastIndex, 'col2', '');
            grid.setValue(lastIndex, 'col3', '');
            grid.setValue(lastIndex, 'col4', '');
            grid.setValue(lastIndex, 'col5', '');
            grid.setValue(lastIndex, 'col6', '');
            grid.setValue(lastIndex, 'col7', '');
            grid.setValue(lastIndex, 'col8', '');
            grid.setValue(lastIndex, 'col9', '');
        });
        
        if (grid && typeof grid.blur === 'function') {
            grid.blur();
        }
    });
    
    // 발주처 변경 이벤트
    $('#company').on('change', function() {
        var selectedCompany = $(this).val();
        var addressInfo = '';
        
        if (selectedCompany === '진성케미칼') {
            addressInfo = '(주)진성케미칼 / 주소 경기도 김포시 양촌읍 삼도공단로 66-1(가동) / 담당자 노하늘과장 010-3167-1154';
        } else {
            addressInfo = '';
        }
        
        $('#addressDisplay').text(addressInfo);
    });
    
    // 페이지 로드 시 발주처 정보 표시
    $('#company').trigger('change');
});

// 저장 처리
function saveit() {
    if (typeof save_check === 'function') {
        save_check();
    }
    document.getElementById('board_form').submit();
}

// 페이지 이동
function load(href) {
    document.location.href = href;
}

function movetowin(href) {
    if (typeof save_check === 'function') {
        save_check();
    }
    document.location.href = href;
}

</script>
</body>
</html>
