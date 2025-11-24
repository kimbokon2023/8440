<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = isset($_SESSION["DB"]) ? $_SESSION["DB"] : "";
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";
$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:http://8440.co.kr/login/login_form.php");
    exit;
}

// 에러 표시 설정
ini_set('display_errors', '1');

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title> 주요부품 입출고 이력 </title>

<style>
/* 모바일 최적화 스타일 */
@media (max-width: 768px) {
    /* 컨테이너 및 카드 최적화 */
    .container-fluid {
        padding: 0.75rem 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 제목 최적화 */
    .fs-3, .fs-5, h4, h5, h6 {
        font-size: 1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        text-align: center !important;
        margin-bottom: 0.75rem !important;
        padding: 0 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: nowrap !important;
        min-height: 40px !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    /* d-flex 컨테이너 안의 버튼은 자동 크기 */
    .d-flex .btn,
    .d-flex.justify-content-center .btn,
    .d-flex.align-items-center .btn {
        width: auto !important;
        max-width: none !important;
        margin: 0.25rem !important;
        flex-shrink: 0 !important;
    }
    
    .btn-sm {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.6rem !important;
        min-height: 36px !important;
    }
    
    /* d-flex justify-content-center 최적화 */
    .d-flex.justify-content-center {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
        justify-content: center !important;
        align-items: center !important;
    }
    
    /* Grid 숨기기 및 카드 컨테이너 표시 */
    #grid {
        display: none !important;
    }
    
    #mobile-grid-cards {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 0.25rem !important;
    }
    
    .mobile-grid-card {
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        margin: 0.5rem auto 0.75rem auto !important;
        padding: 0.75rem !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item {
        display: flex !important;
        flex-direction: column !important;
        margin-bottom: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        border-bottom: 1px solid #f0f0f0 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .mobile-grid-card-label {
        font-weight: bold !important;
        font-size: 0.75rem !important;
        color: #666 !important;
        margin-bottom: 0.25rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .mobile-grid-card-value {
        font-size: 0.9rem !important;
        color: #333 !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 0 !important;
        overflow: visible !important;
    }
    
    /* 텍스트 오버플로우 방지 */
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
}

/* PC 화면 */
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

// REQUEST 변수 초기화
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

// _request.php에서 정의되는 변수들 (초기화)
include "_request.php";

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 부품 제목 배열 생성
$title_arr = array(
    "",
    "일반휀",
    "휠터휀 (LH용)",
    "판넬고정구 (금성아크릴)",
    "비상구 스위치 (건흥KH-9015)",
    "비상등",
    "할로겐(7W -6500K)",
    "T5 일반 5W(300)",
    "T5 일반 11W(600)",
    "T5 일반 15W(900)",
    "T5 일반 20W(1200)",
    "T5 KS 6W(300)",
    "T5 KS 10W(600)",
    "T5 KS 15W(900)",
    "T5 KS 20W(1200)",
    "직관등 600mm",
    "직관등 800mm",
    "직관등 1000mm",
    "직관등 1200mm",
    "할로겐(7W -6500K KS)"
);
// part20 추가: 2020년 7월 20일 김부장님 요청

$itemCount = count($title_arr);
$title = isset($title_arr[$num - 1]) ? $title_arr[$num - 1] : "";

// 배열 초기화
$num_arr = array();
$data_arr = array();

// 입고 데이터 수집
$sql = "select * from mirae8440.part ";
$tmpsum = 0;

try {
    $stmh = $pdo->query($sql);
    $rowCount = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        for ($i = 0; $i < $itemCount; $i++) {
            $tmp = 'part' . (string)($num); // $num으로 넘어온 배열 순서에 해당되는 것 찾기
            
            if ((int)$row[$tmp] > 0) { // 0이 아니고 part번호가 같을때
                // 입고 누적 루틴
                array_push($data_arr, $row['inputdate'] . "!input! 입고 ! " . $row[$tmp]);
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 출고 데이터 수집
$sql = "select * from mirae8440.ceiling ";

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // 2010년 이후 날짜인지 확인
        $mainAssemblyDate = $row["mainassembly_date"];
        $lcAssemblyDate = $row["lcassembly_date"];
        $basicdate = '';
        
        if ($mainAssemblyDate >= "2010-01-01" || $lcAssemblyDate >= "2010-01-01") {
            // 기준 날짜 설정
            if ($mainAssemblyDate >= "2010-01-01") {
                $basicdate = $mainAssemblyDate;
            }
            if ($lcAssemblyDate >= "2010-01-01") {
                $basicdate = $lcAssemblyDate;
            }
            
            // 해당 부품 출고 데이터 수집
            for ($i = 0; $i < $itemCount; $i++) {
                $tmp = 'part' . (string)($i + 1); // part1~20 생성
                
                if (($i + 1) == (int)$num && (int)$row[$tmp] > 0) {
                    // 조립 또는 LC 조립완료 기준으로 정리
                    array_push($data_arr, $basicdate . "!output!" . $row["workplacename"] . "!" . $row[$tmp]);
                }
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  

// 입출고 데이터 처리
$arr = array_unique($data_arr);
rsort($arr); // 내림차순 정렬
sort($arr); // 오름차순 정렬

// 재고 계산 변수 초기화
$inputNum = 0;
$outputNum = 0;

$inoutdate_arr = array();
$partin_arr = array();
$partout_arr = array();
$partremain_arr = array();
$placename = array();

// 입출고 데이터 분석 및 재고 계산
for ($i = 0; $i < count($arr); $i++) {
    $exarr = explode("!", $arr[$i]);
    
    $inoutdate_arr[$i] = $exarr[0]; // 날짜
    
    if ($exarr[1] == 'input') { // 입고 계산
        $inputNum += (int)$exarr[3];
        $partin_arr[$i] = (int)$exarr[3];
        $placename[$i] = $exarr[2];
    }
    
    if ($exarr[1] == 'output') { // 출고 계산
        $outputNum += (int)$exarr[3];
        $partout_arr[$i] = (int)$exarr[3];
        $placename[$i] = $exarr[2];
    }
    
    $partremain_arr[$i] = $inputNum - $outputNum; // 재고량 계산
} 

?>

<div class="container-fluid">
    <form name="board_form" id="board_form" method="post" action="part_table.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">
        
        <div class="d-flex mb-3 mt-2 justify-content-center align-items-center">
            <div id="display_board" class="text-primary fs-3 text-center" style="display:none"></div>
        </div>
        
        <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
            <span class="fs-5">일자별 주요 부품 입출고</span>
            &nbsp;&nbsp;
            <span class="fs-5 text-primary"><?=$title?></span>
            &nbsp; &nbsp; &nbsp;
            <button id="closeBtn" type="button" class="btn btn-dark btn-sm" onclick="self.close();">창닫기</button>
        </div>
        
        <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
            <div id="grid" class="board"></div>
            <div id="mobile-grid-cards" style="display: none; width: 100%;"></div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // 링크 밑줄 제거
    $('a').children().css('textDecoration', 'none');
    $('a').parent().css('textDecoration', 'none');
    
    // PHP 배열 데이터를 JavaScript로 가져오기
    var numcopy = new Array();
    var arr = <?php echo json_encode($inoutdate_arr); ?>;
    var arr1 = <?php echo json_encode($placename); ?>;
    var arr2 = <?php echo json_encode($partin_arr); ?>;
    var arr3 = <?php echo json_encode($partout_arr); ?>;
    var arr4 = <?php echo json_encode($partremain_arr); ?>;
    
    var rowNum = arr.length;
    var count = 0;
    
    const COL_COUNT = 5;
    const data = [];
    const columns = [];
    
    // 데이터 행 생성 (역순 출력)
    for (var i = rowNum - 1; i >= 0; i--) {
        const row = { name: count };
        
        for (let j = 0; j < COL_COUNT; j++) {
            row['col1'] = arr[i];
            row['col2'] = arr1[i];
            row['col3'] = arr2[i];
            row['col4'] = arr3[i];
            row['col5'] = arr4[i];
        }
        
        numcopy[i] = i + 1;
        data.push(row);
        count++;
    }

    
    // 커스텀 텍스트 에디터 클래스
    class CustomTextEditor {
        constructor(props) {
            const el = document.createElement('input');
            const { maxLength } = props.columnInfo.editor.options;
            
            el.type = 'text';
            el.maxLength = maxLength;
            el.value = String(props.value);
            
            this.el = el;
        }
        
        getElement() {
            return this.el;
        }
        
        getValue() {
            return this.el.value;
        }
        
        mounted() {
            this.el.select();
        }
    }
    
    // 모바일 여부 확인
    var isMobile = window.innerWidth <= 768;
    var bodyHeight = isMobile ? 400 : 700;
    
    // grid를 전역 변수로 선언
    window.grid = new tui.Grid({
        el: document.getElementById('grid'),
        data: data,
        bodyHeight: bodyHeight,
        columns: [
            {
                header: '입고 조립완료일',
                name: 'col1',
                sortingType: 'desc',
                sortable: true,
                width: 150,
                align: 'center'
            },
            {
                header: '현장명',
                name: 'col2',
                width: 200,
                align: 'left'
            },
            {
                header: '입고',
                name: 'col3',
                width: 100,
                align: 'center'
            },
            {
                header: '출고',
                name: 'col4',
                width: 100,
                align: 'center'
            },
            {
                header: '재고',
                name: 'col5',
                width: 100,
                align: 'center'
            }
        ],
        columnOptions: {
            resizable: true
        },
        rowHeaders: ['rowNum']
    });	
    
    // TUI Grid 테마 적용
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
                background: '#0000'
            },
            hover: {
                background: '#cfe2ff'
            }
        },
        cell: {
            normal: {
                background: '#fbfbfb',
                border: '#e0e0e0',
                showVerticalBorder: true
            },
            header: {
                background: '#cfe2ff',
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
            }
        }
    });
    
    // 모바일 카드 렌더링 함수
    function renderMobileGridCards() {
        if (!window.grid) return;
        
        var isMobile = window.innerWidth <= 768;
        var cardsContainer = document.getElementById('mobile-grid-cards');
        var gridContainer = document.getElementById('grid');
        
        if (!isMobile) {
            // PC 화면: Grid 표시, 카드 숨김
            if (gridContainer) {
                gridContainer.style.display = '';
            }
            if (cardsContainer) {
                cardsContainer.style.display = 'none';
            }
            return;
        }
        
        // 모바일 화면: Grid 숨김, 카드 표시
        if (gridContainer) {
            gridContainer.style.display = 'none';
        }
        if (!cardsContainer) return;
        
        cardsContainer.style.display = 'block';
        cardsContainer.innerHTML = '';
        
        try {
            var gridData = window.grid.getData();
            
            if (!gridData || gridData.length === 0) {
                cardsContainer.innerHTML = '<div class="text-center py-4 text-muted">데이터가 없습니다.</div>';
                return;
            }
            
            // 컬럼 매핑
            var columnMap = [
                { name: 'col1', label: '입고 조립완료일' },
                { name: 'col2', label: '현장명' },
                { name: 'col3', label: '입고' },
                { name: 'col4', label: '출고' },
                { name: 'col5', label: '재고' }
            ];
            
            gridData.forEach(function(rowData, index) {
                var card = document.createElement('div');
                card.className = 'mobile-grid-card';
                
                var cardHtml = '';
                
                columnMap.forEach(function(colInfo) {
                    var value = rowData[colInfo.name];
                    if (value === null || value === undefined || value === '') {
                        value = '0';
                    }
                    
                    var displayValue = value;
                    
                    cardHtml += '<div class="mobile-grid-card-item">';
                    cardHtml += '<div class="mobile-grid-card-label">' + colInfo.label + '</div>';
                    cardHtml += '<div class="mobile-grid-card-value">' + displayValue + '</div>';
                    cardHtml += '</div>';
                });
                
                if (cardHtml === '') {
                    cardHtml = '<div class="text-muted">데이터 없음</div>';
                }
                
                card.innerHTML = cardHtml;
                cardsContainer.appendChild(card);
            });
        } catch (error) {
            console.error('모바일 카드 렌더링 오류:', error);
            cardsContainer.innerHTML = '<div class="text-center py-4 text-danger">데이터를 불러오는 중 오류가 발생했습니다.</div>';
        }
    }
    
    // 화면 크기 변경 시 카드/그리드 전환
    function updateGridDisplay() {
        renderMobileGridCards();
    }
    
    // Grid 렌더링 완료 후 모바일 카드 렌더링
    setTimeout(function() {
        updateGridDisplay();
    }, 300);
    
    // 리사이즈 이벤트
    window.addEventListener('resize', function() {
        updateGridDisplay();
    });
    
    // 페이지 로드 완료 후에도 한 번 더 확인
    window.addEventListener('load', function() {
        setTimeout(function() {
            updateGridDisplay();
        }, 500);
    });
});

// 검색 엔터 이벤트
function SearchEnter() {
    if (event.keyCode == 13) {
        document.getElementById('board_form').submit();
    }
}

// 페이지 이동 함수
function movetoPage(page) {
    $("#page").val(page);
    $("#list").val('1');
    $("#board_form").submit();
}
</script>

</body>
</html>