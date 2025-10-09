<?php
require_once __DIR__ . '/bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 요청 변수 안전하게 초기화
$search = $_REQUEST["search"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '';

// 기간을 정하는 구간
$todate = date("Y-m-d");   // 현재일자 변수지정
$nowday = date("Y-m-d");   // 현재일자 변수지정
$counter = 0;

// SQL 쿼리 조건
$common = " ORDER BY num DESC";

// 검색 조건에 따른 SQL 쿼리
if ($search == "") {
    $sql = "SELECT * FROM mirae8440.autopanel " . $common;
} else {
    $sql = "SELECT * FROM mirae8440.autopanel WHERE (log LIKE '%$search%') " . $common;
}

// 배열 초기화
$num_arr = array();
$data_arr = array();

try {
    $stmh = $pdo->query($sql);  // 검색조건에 맞는글 stmh
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num_arr[$counter] = $row["num"];
        $data_arr[$counter] = $row["log"];
        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

include includePath('load_header.php');
?>
<title>자동작도 실행기록</title>

<!-- TUI Grid CSS -->
<link rel="stylesheet" href="https://uicdn.toast.com/grid/latest/tui-grid.css" />

<!-- TUI Grid JS -->
<script src="https://uicdn.toast.com/grid/latest/tui-grid.js"></script>

</head>

<body>
<?php include includePath('myheader.php'); ?>
		
<form id="board_form" name="board_form" method="post">
    <div class="container">
        <input type="hidden" id="voc_alert" name="voc_alert" value="<?php echo htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8'); ?>" size="5">
        
        <div class="card mt-1 mb-1">
            <div class="card-body">
                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <div class="input-group p-2 mb-2 justify-content-center">
                        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" size="30" onkeydown="JavaScript:SearchEnter();" placeholder="검색어">
                        <button type="button" id="searchBtn" class="btn btn-dark"><i class="bi bi-search"></i> 검색</button>
                    </div>
                </div>
            </div> <!--card-body-->
        </div> <!--card -->
        
        <div class="card mt-1 mb-1">
            <div class="card-header justify-content-center text-center">
                <h5>다양한 자동작도 프로그램 실행기록</h5>
            </div>
            
            <div class="card-body">
                <div id="grid"></div>
            </div>
        </div>
    </div>
</form>

</body>
</html>	 
  
<script>
// JavaScript 코드로 크롬 주소 표시줄 커스터마이징
document.addEventListener('DOMContentLoaded', function() {
    // 주소 표시줄 요소를 선택합니다.
    var addressBar = document.querySelector('.omnibox');
    if (addressBar) {
        // 배경색을 빨간색으로 변경합니다.
        addressBar.style.backgroundColor = 'red';
    }
});

$(document).ready(function() {
    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });
    
    var arr1 = <?php echo json_encode($num_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    var arr2 = <?php echo json_encode($data_arr ?: array(), JSON_UNESCAPED_UNICODE); ?> || [];
    
    var rowNum = <?php echo $counter; ?>;
    let row_count = 200;
    const COL_COUNT = 2;
    
    const data = [];
    const columns = [];
    
    for (let i = 0; i < row_count; i += 1) {
        const row = { name: i };
        for (let j = 0; j < COL_COUNT; j += 1) {
            row['num'] = arr1[i];
            row['details'] = arr2[i];
        }
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
    
    const grid = new tui.Grid({
        el: document.getElementById('grid'),
        data: data,
        bodyHeight: 700,
        columns: [
            {
                header: '번호',
                name: 'num',
                sortingType: 'desc',
                sortable: true,
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
                header: '실행시간 및 내역',
                name: 'details',
                width: 1000,
                editor: 'text',
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
        },
    });	

    grid.on('dblclick', (e) => {
        // 더블클릭 이벤트 (필요시 구현)
    });
    
    // 키처리
    grid.on('keydown', (e) => {
        const keyCode = e.keyboardEvent.code;
        console.log(e);
        console.log(keyCode);
        
        // const cell = grid.getActiveCell();
        // const editorType = grid.getEditorType(cell);
        
        // if (editorType !== 'text') {
        //     return;
        // }
        
        // if ((keyCode >= 48 && keyCode <= 90) || // 숫자 및 알파벳
        //     (keyCode >= 96 && keyCode <= 105) || // 숫자 패드
        //     (keyCode === 229)) { // 한글 입력
        //     cell.startEditing();
        // }
    });
    
    // grid.on('focusChange', function(ev) {
    //     const { rowKey, columnName } = ev;
    //     const cell = grid.getValue(rowKey, columnName);
    //     console.log(cell);
    //     console.log(rowKey);
    //     console.log(columnName);
    //     grid.startEditing(rowKey, columnName, false);
    // });
    
    // grid.on('beforeKeyDown', function(ev) {
    //     const { keyCode } = ev;
    //     const cell = grid.getActiveCell();
    //     const editorType = grid.getEditorType(cell);
    //     
    //     if (editorType !== 'text') {
    //         return;
    //     }
    //     
    //     if ((keyCode >= 48 && keyCode <= 90) || // 숫자 및 알파벳
    //         (keyCode >= 96 && keyCode <= 105) || // 숫자 패드
    //         (keyCode === 229)) { // 한글 입력
    //         cell.startEditing();
    //     }
    // });
});
</script>

</body>
</html>
