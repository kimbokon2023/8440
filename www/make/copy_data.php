<?php
/**
 * 도장 발주서 등록/수정 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    return $default;
}

// 페이지 설정
$title_message = '도장 발주';

// 기본 변수 초기화
$sort = getRequestValue("sort", "1");
$num = getRequestValue("num", '');
$mode = getRequestValue("mode", "not");

// 검색 관련 변수
$search = getRequestValue("search", '');
$find = getRequestValue("find", '');
$year = getRequestValue("year", '');
$process = getRequestValue("process", '');
$asprocess = getRequestValue("asprocess", '');
$fromdate = getRequestValue("fromdate", '');
$todate = getRequestValue("todate", '');
$separate_date = getRequestValue("separate_date", '');
$page = getRequestValue("page", '');
$scale = getRequestValue("scale", '');

// 데이터 관련 변수 초기화
$orderdate = date("Y-m-d");
$indate = date("Y-m-d");
$company = '';
$text = '';
$arr = array();
$user_name = $_SESSION['nick'] ?? '';
$counter = 0;

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 데이터베이스에서 기존 발주 정보 조회
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
        // 검색 결과가 없을 경우 기본값 유지
        echo "검색결과가 없습니다.<br>";
    }
} catch (PDOException $Exception) {
    error_log("데이터베이스 오류: " . $Exception->getMessage());
    echo "오류: 데이터를 불러오는 중 문제가 발생했습니다.";
}

// 발주처 배열 설정
$company_arr = array(
    "",
    "진성산업",
    "유일기업",
    "은성산업",
    "한산엘테크"
);
$company_count = count($company_arr);

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?></title>

<body>

<?php include '../myheader.php'; ?>

<div class="container justify-content-center">
    <div class="card mt-2 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-center mt-2 mb-2">
                <h4>발주서 등록/수정 (발주처 반드시 지정하세요!) &nbsp; 발주번호: <?=htmlspecialchars($num, ENT_QUOTES, 'UTF-8')?></h4>
                
                <?php
                // 안전한 URL 파라미터 생성
                $list_params = http_build_query([
                    'mode' => 'search',
                    'search' => $search,
                    'find' => $find,
                    'year' => $year,
                    'process' => $process,
                    'asprocess' => $asprocess,
                    'fromdate' => $fromdate,
                    'todate' => $todate,
                    'separate_date' => $separate_date
                ], '', '&', PHP_QUERY_RFC3986);
                
                $save_params = http_build_query([
                    'num' => $num,
                    'page' => $page,
                    'text' => $text
                ], '', '&', PHP_QUERY_RFC3986);
                ?>
                
                <button type="button" class="btn btn-secondary btn-sm" onclick="load('list.php?<?=$list_params?>')">목록</button>&nbsp;
                <button type="button" class="btn btn-dark btn-sm" onclick="savetext('insert.php?<?=$save_params?>')">완료(저장)</button>&nbsp;
            </div>

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
            ?>
            
            <form id="board_form" name="board_form" method="post" action="insert.php?<?=$form_params?>">
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
                
                <div class="d-flex justify-content-center mt-2">
                    <span class="badge bg-danger fs-5">콤마(,)를 사용하면 자료가 정확히 나오지 않습니다. 콤마(,)는 절대 사용하지 마세요!</span>
                </div>

                <div class="d-flex justify-content-center mt-2 mb-1">
                    <div id="grid"></div>
                </div>

                <input type="hidden" id="textsave" name="textsave" value="<?=htmlspecialchars($text, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="mode" name="mode" value="<?=htmlspecialchars($mode, ENT_QUOTES, 'UTF-8')?>">
                <input type="hidden" id="page" name="page" value="<?=htmlspecialchars($page, ENT_QUOTES, 'UTF-8')?>">
            </form>
        </div> <!--card-body-->
    </div> <!--card -->
</div> <!--container-->

<script>
'use strict';

$(document).ready(function() {
    // PHP 변수를 JavaScript로 전달
    var mode = '<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>';
    var arr = <?php echo json_encode($arr); ?>;
    var name = '<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>';
    var counter = '<?php echo $counter; ?>';
    
    // 배열 초기화
    var left_check = [];
    var mid_check = [];
    var right_check = [];
    var done_check = [];
    var remain_check = [];
    var tmp;
    
    // 그리드 설정
    var rowNum = "<?php echo $counter; ?>";
    var row_count = 50;
    var COL_COUNT = 6;
    
    var data = [];
    var columns = [];
    
    var text = '<?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?>';
    
    console.log('텍스트 데이터:', text);
    
    // 텍스트 데이터 파싱
    arr = text.split('|');
    
    if (mode === 'copy') {
        // copy 모드: 도장 복사 시
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
        // 신규 생성: 빈 행 15개 생성
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
    
    // TUI Grid 초기화
    const grid = new tui.Grid({
        el: document.getElementById('grid'),
        data: data,
        bodyHeight: 620,
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
        rowHeaders: ['rowNum'],
        pageOptions: {
            useClient: false,
            perPage: 20
        }
    });
			
    // 저장 함수
    window.savetext = function(href) {
        var tmp = "";
        
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
        
        $("#textsave").val(tmp);  // 테이블의 텍스트를 히든 폼에 기록
        $("form:first").submit(); // 첫번째 폼 제출
        console.log('저장 데이터:', tmp);
    };
    
    // col1 컬럼 숨기기
    grid.hideColumn('col1');
    
    // Cell 변경 이벤트 처리
    grid.on('editingFinish', function(ev) {
        var i = ev.rowKey;
        var set_num = Number(grid.getValue(i, 'col4'));
        var EAstring = grid.getValue(i, 'col5');
        var set_color = grid.getValue(i, 'col8');
        var set_item = grid.getValue(i, 'col9');
        var set_place = grid.getValue(i, 'col2');
        
        console.log('행 변경:', ev.rowKey);
        
        // 수량 입력 시 단위 자동 설정 (수정 모드가 아닐 때)
        if (set_num !== '' && mode !== "modify" && EAstring === '') {
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
});


// 저장 처리
function saveit() {
    // $("#modify").val("1"); // 이전화면 유지
    if (typeof save_check === 'function') {
        save_check();
    }
    document.getElementById('board_form').submit();
}

// 페이지 이동
function load(href) {
    document.location.href = href;
}

// 윈도우 이동 (저장 확인 후)
function movetowin(href) {
    if (typeof save_check === 'function') {
        save_check();
    }
    document.location.href = href;
}

// 발주서 전송 알림
function send_alert() {
    var tmp = "./save_alert.php";
    
    $("#vacancy").load(tmp);
    
    if (typeof alertify !== 'undefined') {
        alertify.alert('발주서 전송 알림창', '<h1>발주서가 전송되었습니다.</h1>');
    } else {
        alert('발주서가 전송되었습니다.');
    }
}

</script>

</body>
</html>
