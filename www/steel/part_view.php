<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 변수 초기화 (null coalescing operator 사용)
$menu = $_REQUEST["menu"] ?? '';
$arr = $_REQUEST["arr"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$year = $_REQUEST["year"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$scale = $_REQUEST["scale"] ?? '';

$title_message = '원자재 내역 추적';

?>

<?php
    include includePath('load_header.php');
?>

<?php include includePath('common/modal.php'); ?>
 
<title> <?=$title_message?>  </title>

<style>
/* 모바일 최적화 */
@media (max-width: 768px) {
    .container-fluid {
        padding: 5px !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }

    .row {
        margin-left: -5px !important;
        margin-right: -5px !important;
    }

    .row > [class*="col-"] {
        padding-left: 5px !important;
        padding-right: 5px !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    /* 제목 영역 최적화 */
    .d-flex.mb-3.mt-2,
    .d-flex.mb-1.mt-2 {
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
        padding: 0.25rem 0 !important;
        margin-bottom: 0.25rem !important;
        margin-top: 0.25rem !important;
    }

    .fs-5 {
        font-size: 0.9rem !important;
    }

    .fs-3 {
        font-size: 1rem !important;
    }

    /* 버튼 최적화 */
    .btn {
        padding: 0.4rem 0.6rem !important;
        font-size: 0.8rem !important;
        min-height: 36px !important;
        margin: 0.25rem 0.125rem !important;
        max-width: 100% !important;
        flex-shrink: 0 !important;
        box-sizing: border-box !important;
    }

    .btn-sm {
        padding: 0.3rem 0.5rem !important;
        font-size: 0.75rem !important;
        min-height: 36px !important;
    }

    /* TUI Grid 모바일 카드 형식 */
    #grid {
        display: none !important;
    }

    #mobile-grid-cards {
        display: block !important;
        padding: 0.5rem 0.4rem !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .mobile-grid-card {
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

    .mobile-grid-card-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 0.4rem !important;
        padding-bottom: 0.4rem !important;
        border-bottom: 1px solid #f1f5f9;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }

    .mobile-grid-card-item:last-child {
        border-bottom: none;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .mobile-grid-card-label {
        font-size: 0.7rem !important;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.25rem !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .mobile-grid-card-value {
        font-size: 0.85rem !important;
        color: #1f2937;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100% !important;
    }

    /* 텍스트/버튼이 카드 영역을 벗어나지 않도록 */
    * {
        max-width: 100% !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
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
}

/* PC에서는 모바일 카드 숨김 */
@media (min-width: 769px) {
    #mobile-grid-cards {
        display: none !important;
    }
}
</style>
 
</head>

<body>

<?php

// arr 파라미터 처리
$reviced_arr = isset($_REQUEST["arr"]) ? str_replace("@", "+", urldecode($_REQUEST["arr"])) : "";

// 자재명에 & 사용한 것을 @로 변경했으니 다시 돌려줌
// $reviced_arr = str_replace("@", "&", $reviced_arr);

// var_dump($reviced_arr);

include "_request.php";

require_once("../lib/mydb.php");
$pdo = db_connect();

$num_arr = array();
$data_arr = array();   // 입고일자 배열

// 전체합계(입고부분)를 산출하는 부분
$sql = "SELECT * FROM mirae8440.steel ";
$tmpsum = 0;

try {
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);
    $rowCount = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $outdate = $row["outdate"] ?? '';
        $item = trim($row["item"] ?? '');
        $spec = trim($row["spec"] ?? '');
        $steelnum = $row["steelnum"] ?? '';
        $company = trim($row["company"] ?? '');
        $comment = $row["comment"] ?? '';
        $which = $row["which"] ?? '';

        $tmp = $item . '|' . $spec . '|' . $company;

        if ($which == '1' && $tmp == trim($reviced_arr)) {
            if ($row["supplier"] !== null) {
                array_push($data_arr, $row['outdate'] . "|input| (입고) 공급처: " . $row["supplier"] . " - " . $row["outworkplace"] . " | " . $steelnum);
            } else {
                array_push($data_arr, $row['outdate'] . "|input| (입고)  - " . $row["outworkplace"] . " | " . $steelnum);
            }
        }

        if ($which == '2' && $tmp == trim($reviced_arr)) {
            array_push($data_arr, $row['outdate'] . "|output| " . $row["outworkplace"] . " | " . $steelnum);
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 부품 입고/출고 계산해서 재고량 파악
sort($data_arr); // 내림차순 정렬

$inputNum = 0;
$outputNum = 0;

$inoutdate_arr = array();
$partin_arr = array();
$partout_arr = array();
$partremain_arr = array();
$placename = array();

// var_dump($arr);

for ($i = 0; $i < count($data_arr); $i++) {
    $exarr = explode("|", $data_arr[$i]);

    $inoutdate_arr[$i] = $exarr[0] ?? '';
    $partin_arr[$i] = 0;
    $partout_arr[$i] = 0;

    if (trim($exarr[1] ?? '') == 'input') { // 입고 계산
        $inputNum += (int)($exarr[3] ?? 0);
        $partin_arr[$i] = (int)($exarr[3] ?? 0);
        $placename[$i] = $exarr[2] ?? '';
    }

    if (trim($exarr[1] ?? '') == 'output') { // 출고 계산
        $outputNum += (int)($exarr[3] ?? 0);
        $partout_arr[$i] = (int)($exarr[3] ?? 0);
        $placename[$i] = $exarr[2] ?? '';
    }

    $partremain_arr[$i] = $inputNum - $outputNum;
}

?>

<form name="board_form" id="board_form" method="post" action="part_table.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">

    <div class="container-fluid">

        <div class="d-flex mb-3 mt-2 justify-content-center align-items-center">
            <div id="display_board" class="text-primary fs-3 text-center" style="display:none">
            </div>
        </div>

        <div class="row justify-content-center align-items-center">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
                <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
                    <span class="fs-5 text-primary"><?=$reviced_arr?></span>
                    <span class="fs-5">&nbsp; (입출고 상세내역) 추적  (<?=count($data_arr)?> 건 기록)</span>
                </div>
            </div>
            <div class="col-sm-2 text-end">
                <button id="closeBtn" type="button" class="btn btn-secondary btn-sm" onclick="self.close();">
                    <i class="bi bi-x-lg"></i> 창닫기
                </button>
            </div>
        </div>

        <div class="row mb-1 mt-2 justify-content-center align-items-center">
            <div id="grid"></div>
            <!-- 모바일 카드 형식 -->
            <div id="mobile-grid-cards" style="display: none; width: 100%;"></div>
        </div>

    </div>

</form>

</body>
</html>

<script>
$(document).ready(function() {

    $('a').children().css('textDecoration', 'none');  // a tag 전체 밑줄없앰.
    $('a').parent().css('textDecoration', 'none');

    var numcopy = new Array();
    var arr = <?php echo json_encode($inoutdate_arr); ?>;
    var arr1 = <?php echo json_encode($placename); ?>;
    var arr2 = <?php echo json_encode($partin_arr); ?>;
    var arr3 = <?php echo json_encode($partout_arr); ?>;
    var arr4 = <?php echo json_encode($partremain_arr); ?>;

    var rowNum = arr.length;   // sum_title의 길이
    var count = 0;

    const COL_COUNT = 5;

    const data = [];
    const columns = [];

    for (i = rowNum - 1; i >= 0; i--) { // 역순으로 출력하기 0보다 크고 데이터수보다 작은 구간

        const row = { name: count };

        for (let j = 0; j < COL_COUNT; j++) {
            row[`col1`] = arr[i];
            row[`col2`] = arr1[i];
            row[`col3`] = arr2[i];
            row[`col4`] = arr3[i];
            row[`col5`] = arr4[i];
        }
        numcopy[i] = i + 1;
        data.push(row);
    }
 
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
    var bodyHeight = isMobile ? 400 : 750;
    
    // grid를 전역 변수로 선언
    window.grid = new tui.Grid({
        el: document.getElementById('grid'),
        data: data,
        bodyHeight: bodyHeight,
        columns: [
            {
                header: '입출고일',
                name: 'col1',
                sortingType: 'desc',
                sortable: true,
                width: isMobile ? 120 : 150,
                align: 'center'
            },
            {
                header: '현장명',
                name: 'col2',
                sortingType: 'desc',
                sortable: true,
                width: isMobile ? 200 : 440,
                align: 'left'
            },
            {
                header: '입고',
                name: 'col3',
                sortingType: 'desc',
                sortable: true,
                width: isMobile ? 80 : 100,
                align: 'center'
            },
            {
                header: '출고',
                name: 'col4',
                sortingType: 'desc',
                sortable: true,
                width: isMobile ? 80 : 100,
                align: 'center'
            },
            {
                header: '재고',
                name: 'col5',
                sortingType: 'desc',
                sortable: true,
                width: isMobile ? 80 : 100,
                align: 'center'
            }
        ],
        columnOptions: {
            resizable: true
        },
        rowHeaders: ['rowNum']
        // pageOptions: {
        //     useClient: false,
        //     perPage: 20
        // }
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
            var columns = window.grid.getColumns();
            
            if (!gridData || gridData.length === 0) {
                cardsContainer.innerHTML = '<div class="text-center py-4 text-muted">데이터가 없습니다.</div>';
                return;
            }
            
            // 컬럼 매핑
            var columnMap = [
                { name: 'col1', label: '입출고일' },
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
                        value = '0'; // 빈 값은 0으로 표시
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
    
    // grid 색상등 꾸미기
    var Grid = tui.Grid; // or require('tui-grid')
    Grid.applyTheme('default', {
        selection: {
            background: '#e6eef5',
            border: '#fdfcfc'
        },
        scrollbar: {
            background: '#e6eef5',
            thumb: '#d9d9d9',
            active: '#c1c1c1'
        },
        row: {
            hover: {
                background: '#ccc'
            }
        },
        cell: {
            normal: {
                background: '#fbfbfb',
                border: '#e6eef5',
                showVerticalBorder: true
            },
            header: {
                background: '#e6eef5',
                border: '#fdfcfc',
                showVerticalBorder: true
            },
            rowHeader: {
                border: '#e6eef5',
                showVerticalBorder: true
            },
            editable: {
                background: '#fbfbfb'
            },
            selectedHeader: {
                background: '#e6eef5'
            },
            focused: {
                border: '#e6eef5'
            },
            disabled: {
                text: '#e6eef5'
            }
        }
    });
    
    // Grid 렌더링 완료 후 모바일 카드 렌더링
    // Grid가 완전히 렌더링된 후에 카드 렌더링 시도
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
</script> 