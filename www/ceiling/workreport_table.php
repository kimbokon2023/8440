<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
$level = $_SESSION["level"] ?? 10;
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정
$base_url = getBaseUrl();

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["user_name"] ?? '';

include includePath('load_header.php');
?>

<title>모델/파트별 작업시간 정리</title>
</head>

<?php
$fromdate = date("Y-m-d", time());
$Transtodate = date("Y-m-d", time());

$orderby = " ORDER BY deadline DESC ";

$now = date("Y-m-d");  // 현재 날짜

$sql = "SELECT * FROM {$DB}.ceiling WHERE deadline BETWEEN DATE('$fromdate') AND DATE('$Transtodate')" . $orderby;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 변수 초기화
$counter = 0;
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $checkstep = $row["checkstep"] ?? '';
        $orderday = $row["orderday"] ?? '';
        $measureday = $row["measureday"] ?? '';
        $drawday = $row["drawday"] ?? '';
        $deadline = $row["deadline"] ?? '';
        $delicompany = $row["delicompany"] ?? '';
        $delivery = $row["delivery"] ?? '';
        $delipay = $row["delipay"] ?? 0;
        
        $workday = $row["workday"] ?? '';
        $startday = $row["startday"] ?? '';
        $testday = $row["testday"] ?? '';
        $worker = $row["worker"] ?? '';
        $endworkday = $row["endworkday"] ?? '';
        
        $memo = $row["memo"] ?? '';
        $regist_day = $row["regist_day"] ?? '';
        $update_day = $row["update_day"] ?? '';
        $demand = $row["demand"] ?? '';
        
        $type = $row["type"] ?? '';
        $inseung = $row["inseung"] ?? '';
        $su = $row["su"] ?? '';
        $bon_su = $row["bon_su"] ?? '';
        $lc_su = $row["lc_su"] ?? '';
        $etc_su = $row["etc_su"] ?? '';
        $air_su = $row["air_su"] ?? '';
        $car_insize = $row["car_insize"] ?? '';
        
        $lc_draw = $row["lc_draw"] ?? '';
        $lclaser_com = $row["lclaser_com"] ?? '';
        $lclaser_date = $row["lclaser_date"] ?? '';
        $lcbending_date = $row["lcbending_date"] ?? '';
        $lcwelding_date = $row["lcwelding_date"] ?? '';
        $lcpainting_date = $row["lcpainting_date"] ?? '';
        $lcassembly_date = $row["lcassembly_date"] ?? '';
        $main_draw = $row["main_draw"] ?? '';
        $eunsung_make_date = $row["eunsung_make_date"] ?? '';
        $eunsung_laser_date = $row["eunsung_laser_date"] ?? '';
        $mainbending_date = $row["mainbending_date"] ?? '';
        $mainwelding_date = $row["mainwelding_date"] ?? '';
        $mainpainting_date = $row["mainpainting_date"] ?? '';
        $mainassembly_date = $row["mainassembly_date"] ?? '';
        $memo2 = $row["memo2"] ?? '';
        
        $order_date1 = $row["order_date1"] ?? '';
        $order_date2 = $row["order_date2"] ?? '';
        $order_date3 = $row["order_date3"] ?? '';
        $order_date4 = $row["order_date4"] ?? '';
        $order_input_date1 = $row["order_input_date1"] ?? '';
        $order_input_date2 = $row["order_input_date2"] ?? '';
        $order_input_date3 = $row["order_input_date3"] ?? '';
        $order_input_date4 = $row["order_input_date4"] ?? '';
        
        $order_com1 = $row["order_com1"] ?? '';
        $order_com2 = $row["order_com2"] ?? '';
        $order_com3 = $row["order_com3"] ?? '';
        $order_com4 = $row["order_com4"] ?? '';
        
        $workitem = "";
        
        if ($workday == '0000-00-00') {
            $workday = '';
        }
        
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
        
        $deli_text = "";
        if ($delivery != "" || $delipay != 0) {
            $deli_text = $delivery . " " . $delipay;
        }
        
        $counter++;
    }
} catch (PDOException $Exception) {
    echo "오류: " . $Exception->getMessage();
}

$all_sum = $sum1 + $sum2 + $sum3;

// 오늘을 담는 변수
$recordDate = date("Y-m-d", time());
?>

<body>

<form name="board_form" id="board_form" method="post">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-3 mb-1 justify-content-center">
                    <span class="fs-3 text-primary">(조명/천장)&nbsp;&nbsp;&nbsp;</span>
                    <span class="fs-3 text-dark" style="color:blue;">모델/파트별 작업시간 정리표&nbsp;&nbsp;&nbsp;</span>
                    <button type="button" class="btn btn-outline-secondary" onclick="self.close();">닫기</button>&nbsp;
                    <button type="button" id="saveBtn" class="btn btn-dark">저장</button>&nbsp;
                </div>
                
                <div class="d-flex p-3 justify-content-center">
                    <span class="fs-5 badge bg-danger">(덴크리 외주) 011,012,013D,025,017,017S,017M,014</span>&nbsp;&nbsp;
                    <span class="fs-5 badge bg-primary">(청디자인 외주) 037,038</span>&nbsp;&nbsp;
                    <span class="fs-6 badge bg-secondary">단위 : 시간 H</span>
                </div>
                
                <div class="d-flex justify-content-center">
                    <span class="fs-5 text-danger">* 조립 박스포장시 30분 가산</span>
                </div>
                
                <div class="d-flex p-2 mb-2 justify-content-center">
                    <div id="grid"></div>
                </div>
            </div>
        </div>
    </div>
</form>

</body>
</html>  


<script>
    $(document).ready(function() {
        var rowNum = 100;
        
        const data = [];
        const columns = [];
        var count = 0;  // 전체줄수 카운트
        
        for (var i = 0; i < rowNum; i++) {
            var row = { name: i };
            row['col1'] = '';
            row['col2'] = '';
            row['col3'] = '';
            row['col4'] = '';
            row['col5'] = '';
            data.push(row);
            count++;
        }

        // 페이지가 로드될 때 서버로부터 JSON 파일 불러오기
        $.ajax({
            url: "load_json.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                grid.resetData(response);
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });

        const grid = new tui.Grid({
            el: document.getElementById('grid'),
            data: data,
            bodyHeight: 550,
            columns: [
                {
                    header: '모델명',
                    name: 'col1',
                    sortingType: 'desc',
                    sortable: true,
                    editor: 'text',
                    width: 180,
                    align: 'center'
                },
                {
                    header: '가공(레/V/절)파트',
                    name: 'col2',
                    sortingType: 'desc',
                    sortable: true,
                    width: 180,
                    editor: 'text',
                    align: 'center'
                },
                {
                    header: '제관파트(본천장)',
                    name: 'col3',
                    editor: 'text',
                    sortingType: 'desc',
                    sortable: true,
                    width: 180,
                    align: 'center'
                },
                {
                    header: '제관파트(LC)',
                    name: 'col4',
                    editor: 'text',
                    sortingType: 'desc',
                    sortable: true,
                    width: 180,
                    align: 'center'
                },
                {
                    header: '조립파트',
                    name: 'col5',
                    editor: 'text',
                    sortingType: 'desc',
                    sortable: true,
                    width: 180,
                    align: 'center'
                }
            ],
            columnOptions: {
                resizable: true
            },
            rowHeaders: ['rowNum']
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

        $("#saveBtn").click(function() {
            savegrid();
        });

        function savegrid() {
            const gridData = grid.getData();
            const jsonData = JSON.stringify(gridData);

            $.ajax({
                url: "workreport_savejson.php",
                type: "POST",
                data: { 'gridData': jsonData },
                success: function(response) {
                    console.log(response);
                    // Toastify를 사용하여 토스트 메시지 표시
                    Toastify({
                        text: "자료 저장완료",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: 'center'
                    }).showToast();
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        }
    });
</script>
