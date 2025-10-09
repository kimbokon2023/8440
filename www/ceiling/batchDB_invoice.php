<?php
require_once __DIR__ . '/../bootstrap.php';

// Session-based access control (level 5 or higher required)
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// Initialize session variables
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["user_name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$level = $_SESSION["level"] ?? '';

// Initialize base URL
$base_url = getBaseUrl();

$title_message = '출고증 일괄';

include includePath('load_header.php');
?>

<title><?php echo htmlspecialchars($title_message); ?></title>
   
<style>
#showframe {
	width:200px !important;
}
</style>   

</head>

<body>

<?php

// Initialize request variables
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$search = $_REQUEST['search'] ?? '';
$num = $_REQUEST["num"] ?? '';
$recordDate = $_REQUEST["recordDate"] ?? date("Y-m-d");

// Initialize date variables
if ($fromdate == "") {
    $fromdate = date("Y-m-d");
}
if ($todate == "") {
    $todate = date("Y-m-d");
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

require_once includePath('lib/mydb.php');
$pdo = db_connect();

$orderby = " ORDER BY deadline DESC ";
	
$now = date("Y-m-d");  // 현재 날짜와 크거나 같으면 출고예정으로 구분

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);

// Build SQL query
if ($search == "") {
    $sql = "SELECT * FROM {$DB}.ceiling WHERE deadline BETWEEN DATE('$fromdate') AND DATE('$Transtodate')" . $orderby;
} else {
    $sql = "SELECT * FROM {$DB}.ceiling WHERE deadline BETWEEN DATE('$fromdate') AND DATE('$Transtodate') ";
    $sql .= " AND ((REPLACE(workplacename,' ','') LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (order_com1 LIKE '%$search%') OR (order_com2 LIKE '%$search%') OR (order_com3 LIKE '%$search%') OR (order_com4 LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
    $sql .= " OR (delicompany LIKE '%$search%') OR (type LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (car_insize LIKE '%$search%') OR (memo LIKE '%$search%') OR (memo2 LIKE '%$search%') OR (material1 LIKE '%$search%') OR (material2 LIKE '%$search%') OR (material3 LIKE '%$search%') OR (material4 LIKE '%$search%') OR (material5 LIKE '%$search%') OR (air_su LIKE '%$search%') OR (searchtag LIKE '%$search%') OR (boxwrap LIKE '%$search%')) " . $orderby;
}

// Initialize counters and arrays
$counter = 0;
$num_arr = array();
$deadline_arr = array();
$workday_arr = array();
$workplacename_arr = array();
$address_arr = array();
$secondord_arr = array();
$sum_arr = array();
$delivery_arr = array();
$content_arr = array();
$demand_arr = array();
$memo_arr = array();
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$jamb_total = 0;

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_rowDB.php';

        $workitem = "";

        if ($workday == '0000-00-00')
            $workday = '';

        if ($su != "")
            $workitem = $su . " , ";
        if ($bon_su != "")
            $workitem .= "본 " . $bon_su . ", ";
        if ($lc_su != "")
            $workitem .= "L/C " . $lc_su . ", ";
        if ($etc_su != "")
            $workitem .= "기타 " . $etc_su . ", ";
        if ($air_su != "")
            $workitem .= "공기청정기 " . $air_su . " ";

        $part = "";
        if ($order_com1 != "")
            $part = $order_com1 . ",";
        if ($order_com2 != "")
            $part .= $order_com2 . ", ";
        if ($order_com3 != "")
            $part .= $order_com3 . ", ";
        if ($order_com4 != "")
            $part .= $order_com4 . ", ";

        $deli_text = "";
        if ($delivery != "" || $delipay != 0)
            $deli_text = $delivery . " " . $delipay;

        $num_arr[$counter] = $num;
        array_push($deadline_arr, $deadline);
        $workday_arr[$counter] = $workday;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $delivery_arr[$counter] = $delivery;
        $secondord_arr[$counter] = $secondord;
        $demand_arr[$counter] = $demand;
        $content_arr[$counter] = $type . " " . $inseung . " 인승 " . $car_insize;
        $memo_arr[$counter] = $memo;
        $sum_arr[$counter] = $workitem;

        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
$all_sum = $sum1 + $sum2 + $sum3;
		 
?>

<form name="board_form" id="board_form" method="post" action="batchDB_invoice.php?mode=search">

    <input type="hidden" id="num_arr" name="num_arr[]">
    <input type="hidden" id="recordDate_arr" name="recordDate_arr[]">

    <div class="container-fluid">
        <div class="card mt-2 mb-4">
            <div class="card-body">
                <div class="card mt-2 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="fs-5 text-primary me-3">(천정/LC)</span>
                            <h5><?php echo htmlspecialchars($title_message); ?></h5> &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-3" id="refresh"><i class="bi bi-arrow-clockwise"></i> 새로고침</button>
                            <button type="button" class="btn btn-dark btn-sm mx-3" onclick="window.close();"><i class="bi bi-x-lg"></i> 닫기</button>
                        </div>

                        <div class="row">
                            <div class="d-flex mt-1 justify-content-center align-items-center">
                                <!-- 기간부터 검색까지 연결 묶음 start -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <span id="showdate" class="btn btn-dark btn-sm"> 납기일 기간 </span>&nbsp;
                                            <div id="showframe" class="card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        기간 설정
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <button type="button" id="premonth" class="btn btn-dark btn-sm me-1" onclick="yesterday()"> 전일 </button>
                                                        <button type="button" class="btn btn-outline-dark btn-sm me-1" onclick="this_today()"> 금일 </button>
                                                        <button type="button" class="btn btn-dark btn-sm me-1" onclick="this_tomorrow()"> 익일 </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="date" id="fromdate" name="fromdate" class="form-control" style="width:100px;" value="<?php echo htmlspecialchars($fromdate); ?>"> &nbsp; ~ &nbsp;
                                            <input type="date" id="todate" name="todate" class="form-control me-1" style="width:100px;" value="<?php echo htmlspecialchars($todate); ?>">
                                            <div class="inputWrap">
                                                <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" class="form-control me-1" style="width:200px;">
                                                <button class="btnClear"></button>
                                            </div>
                                            <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"><i class="bi bi-search"></i> 검색</button>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <input type="date" id="recordDate" name="recordDate" class="form-control me-1" style="width:120px;" value="<?php echo htmlspecialchars($recordDate); ?>">
                                            <button type="button" id="saveDeadlineBtn" class="btn btn-outline-danger btn-sm me-5">선택항목 납기일 변경</button>&nbsp;&nbsp;
                                            <button type="button" id="saveBtn" class="btn btn-outline-success btn-sm"> 선택 출고일 입력 </button> &nbsp;&nbsp;
                                            <button type="button" id="cancelBtn" class="btn btn-danger btn-sm"> 선택 출고일 취소 </button> &nbsp;&nbsp;
                                            <button type="button" id="invoiceBtn" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> 선택 출고증</button>
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div id="grid"></div>
            </div>
        </div> <!--card-body-->
    </div> <!--card -->
    </div> <!--container-->

</form>

</body>
</html>

<script>

$(document).ready(function() {

    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });

    var num = <?php echo json_encode($num_arr ?: array()); ?> || [];
    var numcopy = new Array();
    var arr1 = <?php echo json_encode($workday_arr ?: array()); ?> || [];
    var arr2 = <?php echo json_encode($workplacename_arr ?: array()); ?> || [];
    var arr3 = <?php echo json_encode($address_arr ?: array()); ?> || [];
    var arr4 = <?php echo json_encode($sum_arr ?: array()); ?> || [];
    var arr5 = <?php echo json_encode($delivery_arr ?: array()); ?> || [];
    var arr6 = <?php echo json_encode($content_arr ?: array()); ?> || [];
    var arr7 = <?php echo json_encode($secondord_arr ?: array()); ?> || [];
    var arr8 = <?php echo json_encode($num_arr ?: array()); ?> || [];
    var arr9 = <?php echo json_encode($deadline_arr ?: array()); ?> || [];
    var arr10 = <?php echo json_encode($memo_arr ?: array()); ?> || [];
    var total_sum = 0;

    var rowNum = <?php echo json_encode($counter); ?>;
    var jamb_total = <?php echo json_encode($jamb_total); ?>;

    const data = [];
    const columns = [];
    var count = 0;  // 전체줄수 카운트

    for (var i = 0; i < rowNum; i++) {
        total_sum = total_sum + Number(uncomma(arr6[i] || ''));
        var row = { name: i };
        row[`col1`] = arr1[i] || '';
        row[`col2`] = arr2[i] || '';
        row[`col3`] = arr7[i] || '';
        row[`col4`] = arr3[i] || '';
        row[`col5`] = arr4[i] || '';
        row[`col6`] = arr5[i] || '';
        row[`col7`] = arr6[i] || '';
        row[`col8`] = arr8[i] || '';
        row[`col9`] = arr9[i] || '';
        row[`col10`] = arr10[i] || '';
        data.push(row);
        numcopy[count] = num[i];
        count++;
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
        bodyHeight: 500,
        columns: [
            {
                header: '납기일',
                name: 'col9',
                color: 'red',
                sortingType: 'desc',
                sortable: true,
                width: 80,
                align: 'center'
            },
            {
                header: '출고일',
                name: 'col1',
                sortingType: 'desc',
                sortable: true,
                width: 80,
                align: 'center'
            },
            {
                header: '현장명',
                name: 'col2',
                width: 250,
                align: 'center'
            },
            {
                header: '발주처',
                name: 'col3',
                sortingType: 'desc',
                sortable: true,
                width: 150,
                align: 'center'
            },
            {
                header: '상세내역(모델, 인승, 카사이즈)',
                name: 'col7',
                width: 280,
                align: 'center'
            },
            {
                header: '비 고',
                name: 'col10',
                width: 250,
                align: 'center'
            },
            {
                header: '수량',
                name: 'col5',
                width: 150,
                align: 'center'
            },
            {
                header: '운송내역',
                name: 'col6',
                width: 120,
                align: 'center'
            },
            {
                header: '현장주소',
                name: 'col4',
                width: 200,
                align: 'center'
            },
            {
                header: 'rec No.',
                name: 'col8',
                width: 50,
                align: 'center'
            }
        ],
        columnOptions: {
            resizable: true
        },
        rowHeaders: ['rowNum', 'checkbox']
    });

    var Grid = tui.Grid;
    Grid.applyTheme('default', {
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

    // 더블클릭 이벤트
    grid.on('dblclick', (e) => {
        var link = <?php echo json_encode($base_url); ?> + '/ceiling/view.php?menu=no&num=' + numcopy[e.rowKey];
        if (numcopy[e.rowKey] > 0)
            popupCenter(link, "천정/LC 수주내역", 1850, 920);
        console.log(e.rowKey);
    });

    // 출고증 출력하기 묶음출력
    $("#invoiceBtn").click(function() {
        var tmp = grid.getCheckedRowKeys();
        var col8Array = tmp.map(function(e) {
            return grid.getValue(e, 'col8');
        });
        var col8String = col8Array.join(',');
        console.log(col8String);

        // 출고일 입력하기
        $("#saveBtn").click();

        popupCenter('transform_group.php?array=' + encodeURIComponent(col8String), '출고증 인쇄', 1500, 900);
    });

    $("#refresh").click(function() {
        location.reload();
    });

    // 납기일 일괄적용
    $("#saveDeadlineBtn").click(function() {
        var tmp = grid.getCheckedRowKeys();
        tmp.forEach(function(e) {
            grid.setValue(e, 'col9', $("#recordDate").val());
        });
        savegrid("deadline");
    });

    $("#saveBtn").click(function() {
        var tmp = grid.getCheckedRowKeys();
        tmp.forEach(function(e) {
            grid.setValue(e, 'col1', $("#recordDate").val());
        });
        savegrid();
    });

    $("#cancelBtn").click(function() {
        var tmp = grid.getCheckedRowKeys();
        tmp.forEach(function(e) {
            grid.setValue(e, 'col1', '');
        });
        savegrid();
    });

    $("#clearBtn").click(function() {
        var tmp = grid.getCheckedRowKeys();
        tmp.forEach(function(e) {
            grid.setValue(e, 'col9', '');
        });
        savegrid();
    });

    // grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
    function savegrid(choice = null) {
        let num_arr = new Array();
        let recordDate_arr = new Array();

        const MAXcount = grid.getRowCount();
        let pushcount = 0;

        for (var i = 0; i < MAXcount; i++) {  // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/추가된 것을 반영못함.
            num_arr.push(grid.getValue(i, 'col8'));
            if (choice == 'deadline')
                recordDate_arr.push(grid.getValue(i, 'col9'));  // 납기일 col9
            else
                recordDate_arr.push(grid.getValue(i, 'col1'));  // 출고일은 col1
        }
        $('#num_arr').val(num_arr);
        $('#recordDate_arr').val(recordDate_arr);

        console.log(choice);
        console.log(recordDate_arr);

        // 출고일 저장하기
        $.ajax({
            url: "save_workday.php?choice=" + choice,
            type: "post",
            data: $("#board_form").serialize(),
            dataType: "json",
            success: function(data) {
                console.log(data);
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });
    }
});

function SearchEnter() {
    if (event.keyCode == 13) {
        document.getElementById('board_form').submit();
    }
}

</script>