<?php
/**
 * 도장 발주서 상세보기 페이지
 * 로컬 및 서버 환경 모두 지원
 */

// 공통 함수 및 세션 로드
require_once __DIR__ . '/../common/functions.php';
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
$title_message = '도장 발주서 보기';

// _request.php에서 로드될 변수들
require_once '_request.php';

// 추가 변수 초기화
$voc_alert = getRequestValue("voc_alert", '');
$ma_alert = getRequestValue("ma_alert", '');
$order_alert = getRequestValue("order_alert", '');
$yearcheckbox = getRequestValue("yearcheckbox", '');
$year = getRequestValue("year", '');
$check = getRequestValue("check", '');
$output_check = getRequestValue("output_check", '');
$plan_output_check = getRequestValue("plan_output_check", '');
$team_check = getRequestValue("team_check", '');
$measure_check = getRequestValue("measure_check", '');
$cursort = getRequestValue("cursort", '');
$sortof = getRequestValue("sortof", '');
$stable = getRequestValue("stable", '');
$sqltext = getRequestValue("sqltext", '');
$process = getRequestValue("process", '');
$find = getRequestValue("find", '');
$upnum = getRequestValue("upnum", '');
$sort = getRequestValue("sort", '');
$m2 = getRequestValue("m2", '');

// 데이터 관련 변수 초기화
$orderdate = '';
$indate = '';
$company = '';
$text = '';
$arr = array();
$user_name = $_SESSION['nick'] ?? '';
$counter = 0;

// 데이터베이스에서 발주서 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.make WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $num = $row["num"] ?? '';
        $orderdate = $row["orderdate"] ?? '';
        $indate = $row["indate"] ?? '';
        $company = $row["company"] ?? '';
        $text = $row["text"] ?? '';
    } else {
        echo "검색결과가 없습니다.<br>";
    }
    
} catch (PDOException $ex) {
    error_log("발주서 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "오류: 발주서를 불러오는 중 문제가 발생했습니다.";
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

	.d-flex.justify-content-center span {
		font-size: 0.8rem !important;
		margin-right: 0.3rem !important;
		flex-shrink: 0 !important;
		white-space: nowrap !important;
	}

	.d-flex.justify-content-center input[type="date"],
	.d-flex.justify-content-center input[type="text"] {
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
		display: block !important;
		text-align: center !important;
		margin: 0.25rem 0 !important;
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

	/* TUI Grid 컨테이너 모바일 최적화 */
	.d-flex.justify-content-center {
		width: 100% !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		padding: 0.3rem !important;
		box-sizing: border-box !important;
		flex-direction: column !important;
	}

	/* 제목 영역 모바일 최적화 */
	.d-flex.justify-content-center.mt-2.mb-3 {
		margin-top: 0.5rem !important;
		margin-bottom: 0.5rem !important;
		padding: 0.3rem !important;
	}

	.d-flex.justify-content-center.mt-2 {
		margin-top: 0.5rem !important;
		padding: 0.3rem !important;
	}

	.d-flex.justify-content-center.mt-3.mb-2 {
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


<script>
'use strict';

// 화면을 시간 지연 후 나타내기
function callit() {
    setTimeout(function() {
        if (typeof save_check === 'function') {
            save_check();
        }
    }, 500);
}
</script>

<form id="board_form" name="board_form" method="post">
    <div class="container-fluid">
        <div class="card mt-2 mb-4">
            <div class="card-body">
                <div class="card mt-2 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mt-2 mb-3">
                            <h5><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?> &nbsp; 발주번호: <?=htmlspecialchars($num, ENT_QUOTES, 'UTF-8')?></h5>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-2">
                            <span class="text-dark fs-6">발주일</span>&nbsp;
                            <input disabled type="date" id="orderdate" name="orderdate" value="<?=htmlspecialchars($orderdate, ENT_QUOTES, 'UTF-8')?>">&nbsp;&nbsp;
                            
                            <span class="text-dark fs-6">접수일</span>&nbsp;
                            <input disabled type="date" id="indate" name="indate" value="<?=htmlspecialchars($indate, ENT_QUOTES, 'UTF-8')?>">&nbsp;&nbsp;
                            
                            <span class="text-dark fs-6">발주처</span>&nbsp;
                            <?php
                            if (empty($company)) {
                                $company = "유일기업";
                            }
                            ?>
                            <input disabled type="text" id="company" name="company" value="<?=htmlspecialchars($company, ENT_QUOTES, 'UTF-8')?>" size="20" placeholder="발주처">
                        </div>
                        
                        <div class="d-flex justify-content-center mt-3 mb-2">
                            <span class="badge bg-danger fs-6">콤마(,)를 사용하면 자료가 정확히 나오지 않습니다. 콤마(,)는 절대 사용하지 마세요!</span>
                        </div>
                        
                        <?php
                        // 버튼 URL 파라미터 생성
                        $modify_params = http_build_query([
                            'mode' => 'modify',
                            'num' => $num,
                            'page' => $page,
                            'search' => $search,
                            'find' => $find,
                            'process' => $process,
                            'yearcheckbox' => $yearcheckbox,
                            'year' => $year,
                            'check' => $check,
                            'output_check' => $output_check,
                            'team_check' => $team_check,
                            'plan_output_check' => $plan_output_check,
                            'cursort' => $cursort,
                            'sortof' => $sortof,
                            'stable' => 1,
                            'scale' => $scale
                        ], '', '&', PHP_QUERY_RFC3986);
                        
                        $copy_params = http_build_query([
                            'mode' => 'copy',
                            'num' => $num,
                            'search' => $search
                        ], '', '&', PHP_QUERY_RFC3986);
                        
                        $print_params = http_build_query([
                            'num' => $num,
                            'upnum' => $upnum,
                            'page' => $page,
                            'search' => $search,
                            'find' => $find,
                            'list' => 1,
                            'process' => $process,
                            'sort' => $sort,
                            'm2' => $m2
                        ], '', '&', PHP_QUERY_RFC3986);
                        
                        $delete_params = http_build_query([
                            'num' => $num,
                            'page' => $page,
                            'check' => $check,
                            'scale' => $scale
                        ], '', '&', PHP_QUERY_RFC3986);
                        ?>
                        
                        <div class="d-flex justify-content-start mt-3">
                            <button type="button" class="btn btn-dark btn-sm me-1" onclick="window.close();">&times; 닫기</button>
                            <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?<?=$modify_params?>';"><i class="bi bi-pencil-square"></i> 수정</button>
                            <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php';"><i class="bi bi-pencil"></i> 신규</button>
                            <button type="button" class="btn btn-primary btn-sm me-1" onclick="location.href='write_form.php?<?=$copy_params?>';"><ion-icon name="copy-outline"></ion-icon> 데이터 복사</button>
                            <button type="button" class="btn btn-success btn-sm me-1" onclick="window.open('savefile.php?<?=$print_params?>', '인쇄하기', 'left=100,top=100,scrollbars=yes,toolbars=no,width=1500,height=700');"><i class="bi bi-printer"></i> 발주서 인쇄</button>
                            <button type="button" class="btn btn-danger btn-sm me-1" onclick="del('delete.php?<?=$delete_params?>')"><i class="bi bi-trash"></i> 삭제</button>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center">
                    <div id="grid" style="width:1300px;"></div>
                    <div id="mobile-grid-cards"></div>
                </div>
                
                <input type="hidden" id="num" name="num" value="<?=htmlspecialchars($num, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="text" name="text" value="<?=htmlspecialchars($text, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="voc_alert" name="voc_alert" value="<?=htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="ma_alert" name="ma_alert" value="<?=htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="order_alert" name="order_alert" value="<?=htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="year" name="year" value="<?=htmlspecialchars($year, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="check" name="check" value="<?=htmlspecialchars($check, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="stable" name="stable" value="<?=htmlspecialchars($stable, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="sqltext" name="sqltext" value="<?=htmlspecialchars($sqltext, ENT_QUOTES, 'UTF-8')?>">
            </div>
        </div>
    </div>
</form>       	      

<script>
$(document).ready(function() {
    // PHP 변수를 JavaScript로 전달
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
    var COL_COUNT = 6;
    
    var data = [];
    var columns = [];
    
    // 텍스트 데이터 파싱
    var text = <?php echo json_encode($text); ?>;
    arr = text.split('|');
    
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
        
        data.push(row);
    }


    // 모바일 감지
    var isMobile = window.innerWidth <= 768;
    
    // 모바일에서 컬럼 너비 조정
    var columns = [
        {
            header: '구분',
            name: 'col1',
            sortingType: 'desc',
            sortable: true,
            width: isMobile ? 40 : 50,
            align: 'center'
        },
        {
            header: '현장명',
            name: 'col2',
            width: isMobile ? 200 : 400,
            align: 'center'
        },
        {
            header: '품목',
            name: 'col3',
            width: isMobile ? 120 : 200,
            align: 'center'
        },
        {
            header: '수량',
            name: 'col4',
            width: isMobile ? 60 : 70,
            align: 'center'
        },
        {
            header: '단위',
            name: 'col5',
            width: isMobile ? 60 : 70,
            align: 'center'
        },
        {
            header: '단가',
            name: 'col6',
            width: isMobile ? 80 : 100,
            align: 'center'
        },
        {
            header: '색상',
            name: 'col7',
            width: isMobile ? 150 : 300,
            align: 'center'
        }
    ];
    
    // 모바일에서 카드 형식으로 표시하는 함수
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
                
                // 컬럼 헤더 정의 (col1 제외)
                var columnLabels = {
                    'col2': '현장명',
                    'col3': '품목',
                    'col4': '수량',
                    'col5': '단위',
                    'col6': '단가',
                    'col7': '색상'
                };
                
                // 각 행을 카드로 변환
                data.forEach(function(row, index) {
                    var card = document.createElement('div');
                    card.className = 'mobile-grid-card';
                    
                    var cardContent = '';
                    
                    // 각 컬럼을 카드 아이템으로 추가 (col1 제외)
                    Object.keys(columnLabels).forEach(function(colName) {
                        var value = row[colName] || '';
                        if (value !== '') {
                            cardContent += '<div class="mobile-grid-card-item">';
                            cardContent += '<span class="mobile-grid-card-label">' + columnLabels[colName] + '</span>';
                            cardContent += '<span class="mobile-grid-card-value">' + value + '</span>';
                            cardContent += '</div>';
                        }
                    });
                    
                    card.innerHTML = cardContent;
                    cardsContainer.appendChild(card);
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
    const grid = new tui.Grid({
        el: document.getElementById('grid'),
        data: data,
        bodyHeight: isMobile ? 400 : 620,
        columns: columns,
        columnOptions: {
            resizable: true
        },
        rowHeaders: ['rowNum', 'checkbox']
    });
    
    // 그리드 테마 적용
    var Grid = tui.Grid;
    Grid.applyTheme('striped', {
        selection: {
            background: '#4555f9',
            border: '#004082'
        },
        scrollbar: {
            background: '#f5f5f5',
            thumb: '#d9d9d9',
            active: '#c1c1c1'
        },
        row: {
            even: {
                background: '#CFE2FF'
            }
        },
        cell: {
            normal: {
                background: '#fbfbfb',
                border: '#e0e0e0',
                showVerticalBorder: true
            },
            header: {
                background: '#eee',
                border: '#ccc',
                showVerticalBorder: true
            },
            rowHeader: {
                border: '#ccc',
                showVerticalBorder: true
            },
            editable: {
                background: '#fbfbfb'
            },
            selectedHeader: {
                background: '#d8d8d8'
            },
            focused: {
                border: '#418ed4'
            },
            disabled: {
                text: '#b0b0b0'
            }
        }
    });
    
    // col1 컬럼 숨기기
    grid.hideColumn('col1');
    
    // 초기 로드 시 모바일 카드 형식 적용
    renderMobileCards();
    
    // 윈도우 리사이즈 이벤트 처리
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            isMobile = window.innerWidth <= 768;
            renderMobileCards();
        }, 250);
    });
});



// 숫자 포맷팅 함수
function inputNumberFormat(obj) {
    obj.value = comma(uncomma(obj.value));
}

function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

// 날짜 입력 마스크
function date_mask(formd, textid) {
    var form = document[formd];
    if (!form) return;
    
    var text = form[textid];
    if (!text) return;
    
    var textlength = text.value.length;
    
    if (textlength == 4) {
        text.value = text.value + "-";
    } else if (textlength == 7) {
        text.value = text.value + "-";
    } else if (textlength > 9) {
        checkdate(text);
    }
}

// 날짜 유효성 검사
function checkdate(input) {
    var validformat = /^\d{4}-\d{2}-\d{2}$/;
    var returnval = false;
    
    if (!validformat.test(input.value)) {
        alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
        input.select();
        return false;
    }
    
    var parts = input.value.split("-");
    var yearfield = parts[0];
    var monthfield = parts[1];
    var dayfield = parts[2];
    var dayobj = new Date(yearfield, monthfield - 1, dayfield);
    
    if ((dayobj.getMonth() + 1 != monthfield) ||
        (dayobj.getDate() != dayfield) ||
        (dayobj.getFullYear() != yearfield)) {
        alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
        input.select();
        return false;
    }
    
    return true;
}

// 텍스트 입력 처리 (10% 증가 및 클립보드 복사)
function input_Text() {
    var testElem = document.getElementById("test");
    if (testElem) {
        testElem.value = comma(Math.floor(uncomma(testElem.value) * 1.1));
        testElem.select();
        
        try {
            document.execCommand("Copy");
        } catch (err) {
            console.warn('클립보드 복사 실패:', err);
        }
    }
}  

// 엔터키 캡처
function captureReturnKey(e) {
    if (e.keyCode == 13 && e.srcElement.type != 'textarea') {
        return false;
    }
}

function recaptureReturnKey(e) {
    if (e.keyCode == 13) {
        exe_search();
    }
}

function Enter_Check() {
    if (event.keyCode == 13) {
        exe_search();
    }
}

function Enter_CheckTel() {
    if (event.keyCode == 13) {
        exe_searchTel();
    }
}

// 검색 실행
function exe_search() {
    var outworkplaceElem = document.getElementById("outworkplace");
    if (!outworkplaceElem) return;
    
    var postData = (typeof changeUri === 'function') ? 
        changeUri(outworkplaceElem.value) : 
        outworkplaceElem.value;
    
    var sendData = $(":input:radio[name=root]:checked").val();
    
    if (typeof $ !== 'undefined') {
        $("#displaysearch").show();
        $("#displaysearch").load("./searchkd.php?mode=search&search=" + encodeURIComponent(postData));
    }
}

// 전화번호 검색 실행
function exe_searchTel() {
    var receiverElem = document.getElementById("receiver");
    if (!receiverElem) return;
    
    var postData = (typeof changeUri === 'function') ? 
        changeUri(receiverElem.value) : 
        receiverElem.value;
    
    if (typeof $ !== 'undefined') {
        $("#displaysearchworker").show();
        $("#displaysearchworker").load("./workerlist.php?mode=search&search=" + encodeURIComponent(postData));
    }
}

// 삭제 처리
function del(href) {
    if (typeof Swal === 'undefined') {
        if (confirm('정말 삭제하시겠습니까?')) {
            deleteRecord();
        }
        return;
    }
    
    Swal.fire({
        title: '자료 삭제',
        text: "삭제는 신중! 정말 삭제하시겠습니까?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '삭제',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteRecord();
        }
    });
}

// 실제 삭제 처리
function deleteRecord() {
    $.ajax({
        url: 'delete.php',
        type: 'post',
        data: $("#board_form").serialize(),
        dataType: 'json',
    }).done(function(data) {
        // 삭제 성공 시 알림
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: "파일 삭제완료",
                duration: 2000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                },
            }).showToast();
        } else {
            alert('삭제가 완료되었습니다.');
        }
        
        setTimeout(function() {
            if (window.opener && !window.opener.closed) {
                if (typeof window.opener.restorePageNumber === 'function') {
                    window.opener.restorePageNumber();
                }
                window.opener.location.reload();
            }
            window.close();
        }, 1000);
    }).fail(function() {
        alert('삭제 중 오류가 발생했습니다.');
    });
}

// 정렬 처리
function sortall() {
    if (typeof save_check === 'function') {
        save_check();
    }
    
    var sortElem = document.getElementById("sort");
    if (sortElem) {
        var sort = sortElem.value;
        sortElem.value = (sort == '1') ? '2' : '1';
    }
    
    var modifyElem = document.getElementById("modify");
    if (modifyElem) {
        modifyElem.value = "1";
    }
    
    document.getElementById('board_form').submit();
}

// 저장 처리
function saveit() {
    if (typeof save_check === 'function') {
        save_check();
    }
    document.getElementById('board_form').submit();
}

// 텍스트 저장 (테이블 데이터)
function savetext() {
    if (typeof $ === 'undefined' || typeof table1 === 'undefined') {
        console.warn('필수 라이브러리가 로드되지 않았습니다.');
        return;
    }
    
    var trlength = $('#spreadsheet tbody tr').length;
    var tmp = "";
    
    for (var i = 0; i < trlength; i++) {
        tmp += table1.getRowData(i) + '|';
    }
    
    console.log('행 수:', trlength);
    console.log('저장 데이터:', tmp);
    
    $("#text").val(tmp);
}

// 페이지 이동
function load(href) {
    if (typeof save_check === 'function') {
        save_check();
    }
    document.location.href = href;
}

function movetowin(href) {
    if (typeof save_check === 'function') {
        save_check();
    }
    document.location.href = href;
}

// 체크 저장 (플레이스홀더)
function save_check() {
    // 체크된 것 저장하기
    // 필요시 구현
}

// 발주서 전송 알림
function send_alert() {
    var tmp = "./save_alert.php";
    
    if (typeof $ !== 'undefined') {
        $("#vacancy").load(tmp);
    }
    
    if (typeof alertify !== 'undefined') {
        alertify.alert('발주서 전송 알림창', '<h1>발주서가 전송되었습니다.</h1>');
    } else {
        alert('발주서가 전송되었습니다.');
    }
}

// 알림 데이터 로드
function click_it() {
    var name = <?php echo json_encode($user_name); ?>;
    var tmp = "./load_alert.php";
    
    if (typeof $ !== 'undefined') {
        $("#vacancy").load(tmp);
        var alerts = $("#alerts").val();
    }
}

// 2초마다 알림 체크
var timer = setInterval(function() {
    click_it();
}, 2000);

// 슬립 함수 (사용 지양 권장)
function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}

</script>

</body>
</html>
