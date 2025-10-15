<?php
/**
 * 서한컴퍼니 출고 리스트 (납품 예정)
 * 로컬 및 서버 환경 모두 지원
 */

require_once("../lib/mydb.php");
$pdo = db_connect();

/**
 * 날짜 변환 함수
 */
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}

// 기간 설정
$todate = date("Y-m-d");
$nowday = date("Y-m-d");
$counter = 1;

// SQL 쿼리 (사용자 입력이 없으므로 직접 쿼리 사용)
$common = " WHERE DATE(deadline) >= DATE(NOW()) ORDER BY deadline";
$sql = "SELECT * FROM mirae8440.oem" . $common;

// 세션 변수 초기화 (?? '' 형태)
$DB = $_SESSION["DB"] ?? 'mirae8440';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>서한컴퍼니 출고 리스트</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
    <script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
</head>
<body>

<?php
// 요청 변수 초기화 (?? '' 형태)
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;
$cursort = $_REQUEST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? '0';
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

// 날짜 설정
if (empty($fromdate)) {
    $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

// 배열 변수 초기화
$counter = 0;
$workday_arr = [];
$testday_arr = [];
$workplacename_arr = [];
$worker_arr = [];
$secondord_arr = [];  // CRITICAL FIX: $$secondord_arr → $secondord_arr
$material_arr = [];
$sum_arr = [];
$main_draw_arr = [];
$lc_draw_arr = [];
$type_arr = [];
$car_insize_arr = [];
$detail_arr = [];
$sum1 = [];
$sum2 = [];
$sum3 = [];
$sum4 = [];
$sum5 = [];
$sum = [];

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $checkstep = $row["checkstep"];
        $workplacename = $row["workplacename"];
        $address = $row["address"];
        $firstord = $row["firstord"];
        $firstordman = $row["firstordman"];
        $firstordmantel = $row["firstordmantel"];
        $secondord = $row["secondord"];
        $secondordman = $row["secondordman"];
        $secondordmantel = $row["secondordmantel"];
        $chargedman = $row["chargedman"];
        $chargedmantel = $row["chargedmantel"];
        $orderday = $row["orderday"];
        $measureday = $row["measureday"];
        $drawday = $row["drawday"];
        $deadline = $row["deadline"];
        $workday = $row["workday"];
        $worker = $row["worker"];
        $endworkday = $row["endworkday"];
        $material1 = $row["material1"];
        $material2 = $row["material2"];
        $material3 = $row["material3"];
        $material4 = $row["material4"];
        $material5 = $row["material5"];
        $material6 = $row["material6"];
        $widehap = $row["widehap"];
        $normalhap = $row["normalhap"];
        $smallhap = $row["smallhap"];
        $memo = $row["memo"];
        $regist_day = $row["regist_day"];
        $update_day = $row["update_day"];
        $demand = $row["demand"];
        $startday = $row["startday"];
        $testday = $row["testday"];
        $hpi = $row["hpi"];
        $delicompany = $row["delicompany"];
        $delipay = $row["delipay"];
        $type1 = $row["type1"];
        $inseung1 = $row["inseung1"];
        $su = $row["su"];
        $bon_su = $row["bon_su"];
        $lc_su = $row["lc_su"];
        $etc_su = $row["etc_su"];
        $air_su = $row["air_su"];
        $car_insize1 = $row["car_insize1"];
        $order_com1 = $row["order_com1"];
        $order_text1 = $row["order_text1"];
        $order_com2 = $row["order_com2"];
        $order_text2 = $row["order_text2"];
        $order_com3 = $row["order_com3"];
        $order_text3 = $row["order_text3"];
        $order_com4 = $row["order_com4"];
        $order_text4 = $row["order_text4"];
        $lc_draw = $row["lc_draw"];
        $lclaser_com = $row["lclaser_com"];
        $lclaser_date = $row["lclaser_date"];
        $lcbending_date = $row["lcbending_date"];
        $lcwelding_date = $row["lcwelding_date"];
        $lcpainting_date = $row["lcpainting_date"];
        $lcassembly_date = $row["lcassembly_date"];
        $main_draw = $row["main_draw"];
        $eunsung_make_date = $row["eunsung_make_date"];
        $eunsung_laser_date = $row["eunsung_laser_date"];
        $mainbending_date = $row["mainbending_date"];
        $mainwelding_date = $row["mainwelding_date"];
        $mainpainting_date = $row["mainpainting_date"];
        $mainassembly_date = $row["mainassembly_date"];
        $memo2 = $row["memo2"];
        
        $sum1[$counter] = isset($sum1[$counter]) ? $sum1[$counter] + (int)$su : (int)$su;
        $sum2[$counter] = isset($sum2[$counter]) ? $sum2[$counter] + (int)$bon_su : (int)$bon_su;
        $sum3[$counter] = isset($sum3[$counter]) ? $sum3[$counter] + (int)$lc_su : (int)$lc_su;
        $sum4[$counter] = isset($sum4[$counter]) ? $sum4[$counter] + (int)$etc_su : (int)$etc_su;
        $sum5[$counter] = isset($sum5[$counter]) ? $sum5[$counter] + (int)$air_su : (int)$air_su;
        
        $workday = trans_date($workday);
        $demand = trans_date($demand);
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        $testday = trans_date($testday);
        $lc_draw = trans_date($lc_draw);
        $lclaser_date = trans_date($lclaser_date);
        $lcbending_date = trans_date($lcbending_date);
        $lcwelding_date = trans_date($lcwelding_date);
        $lcpainting_date = trans_date($lcpainting_date);
        $lcassembly_date = trans_date($lcassembly_date);
        $main_draw = trans_date($main_draw);
        $eunsung_make_date = trans_date($eunsung_make_date);
        $eunsung_laser_date = trans_date($eunsung_laser_date);
        $mainbending_date = trans_date($mainbending_date);
        $mainwelding_date = trans_date($mainwelding_date);
        $mainpainting_date = trans_date($mainpainting_date);
        $mainassembly_date = trans_date($mainassembly_date);
        
        $sum_material = $material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6;
        
        $workday_arr[$counter] = $deadline;
        $testday_arr[$counter] = $testday;
        $workplacename_arr[$counter] = $workplacename;
        $material_arr[$counter] = $sum_material;
        $worker_arr[$counter] = $worker;
        $secondord_arr[$counter] = $secondord;
        $type_arr[$counter] = $type1;
        $car_insize_arr[$counter] = $car_insize1;
        
        $workitem = "";
        if ($lc_su != "") {
            $workitem .= "L/C " . $lc_su . ", ";
        }
        if ($etc_su != "") {
            $workitem .= "기타 " . $etc_su . ", ";
        }
        if ($air_su != "") {
            $workitem .= "공기청정기 " . $air_su . " ";
        }
        
        $detail_arr[$counter] = $workitem;
        
        $main_draw_arr[$counter] = "";
        if (substr($main_draw, 0, 2) == "20") {
            $main_draw_arr[$counter] = "OK";
        } elseif ($bon_su < 1) {
            $main_draw_arr[$counter] = "X";
        }
        
        $lc_draw_arr[$counter] = "";
        if (substr($lc_draw, 0, 2) == "20") {
            $lc_draw_arr[$counter] = "OK";
        } elseif ($lc_su < 1) {
            $lc_draw_arr[$counter] = "X";
        }
        
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("출고 리스트 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}
?>

<div id="wrap">
    <h1>&nbsp; 서한컴퍼니 출고 리스트</h1>
    <br>
    
    <div id="grid"></div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    $(document).ready(function() {
        var arr1 = <?php echo json_encode($workday_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr2 = <?php echo json_encode($workplacename_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr3 = <?php echo json_encode($worker_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr4 = <?php echo json_encode($material_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr5 = <?php echo json_encode($sum_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr7 = <?php echo json_encode($main_draw_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr8 = <?php echo json_encode($lc_draw_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr9 = <?php echo json_encode($secondord_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr10 = <?php echo json_encode($type_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr11 = <?php echo json_encode($car_insize_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr12 = <?php echo json_encode($detail_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        var hap1 = <?php echo json_encode($sum1, JSON_UNESCAPED_UNICODE); ?>;
        var hap2 = <?php echo json_encode($sum2, JSON_UNESCAPED_UNICODE); ?>;
        var hap3 = <?php echo json_encode($sum3, JSON_UNESCAPED_UNICODE); ?>;
        var hap4 = <?php echo json_encode($sum4, JSON_UNESCAPED_UNICODE); ?>;
        var hap5 = <?php echo json_encode($sum5, JSON_UNESCAPED_UNICODE); ?>;
        
        var total_sum = 0;
        var hap1_sum = 0;
        var hap2_sum = 0;
        var hap3_sum = 0;
        var hap4_sum = 0;
        var hap5_sum = 0;
        var sum_tmp = 0;
        var tmp = "";
        
        var rowNum = <?php echo json_encode($counter, JSON_UNESCAPED_UNICODE); ?>;
        
        var j = 0;
        var past;
        past = arr1[0];
        
        var COL_COUNT = 6;
        var data = [];
        
        // IE11 호환: for loop으로 변경
        for (var i = 0; i < rowNum; i++) {
            var row = { name: j };
            
            if (arr1[i] != past) {
                if (hap1_sum > 0) tmp = tmp + hap1_sum + "(set), ";
                if (hap2_sum > 0) tmp = tmp + " 본청장 " + hap2_sum + ",";
                if (hap3_sum > 0) tmp = tmp + " L/C  " + hap3_sum + ",";
                if (hap4_sum > 0) tmp = tmp + " 기타  " + hap4_sum + ",";
                if (hap5_sum > 0) tmp = tmp + " Air " + hap5_sum;
                
                for (var k = 0; k < COL_COUNT; k++) {
                    row['col1'] = '';
                    row['col2'] = '';
                    row['col3'] = '';
                    row['col4'] = '';
                    row['col5'] = '';
                    row['col6'] = '';
                    row['col7'] = '';
                    row['col8'] = tmp;
                }
                data.push(row);
                j++;
                
                row = { name: j };
                for (var k = 0; k < COL_COUNT; k++) {
                    row['col1'] = '';
                    row['col2'] = '';
                    row['col3'] = '';
                    row['col4'] = '';
                    row['col5'] = '';
                    row['col6'] = '';
                    row['col7'] = '';
                    row['col8'] = '';
                }
                data.push(row);
                j++;
                
                hap1_sum = 0;
                hap2_sum = 0;
                hap3_sum = 0;
                hap4_sum = 0;
                hap5_sum = 0;
                tmp = "";
            }
            
            row = { name: j };
            for (var k = 0; k < COL_COUNT; k++) {
                row['col1'] = arr1[i];
                row['col2'] = arr7[i];
                row['col3'] = arr8[i];
                row['col4'] = arr2[i];
                row['col5'] = arr9[i];
                row['col6'] = arr10[i];
                row['col7'] = arr11[i];
                row['col8'] = arr12[i];
            }
            data.push(row);
            
            hap1_sum = hap1_sum + (hap1[i] || 0);
            hap2_sum = hap2_sum + (hap2[i] || 0);
            hap3_sum = hap3_sum + (hap3[i] || 0);
            hap4_sum = hap4_sum + (hap4[i] || 0);
            hap5_sum = hap5_sum + (hap5[i] || 0);
            
            past = arr1[i];
            j++;
        }
        
        // 마지막 소계
        tmp = " L/C  " + hap3_sum + " 기타  " + hap4_sum;
        row = { name: j };
        for (var k = 0; k < COL_COUNT; k++) {
            row['col1'] = '';
            row['col2'] = '';
            row['col3'] = '';
            row['col4'] = '';
            row['col5'] = '';
            row['col6'] = '';
            row['col7'] = '';
            row['col8'] = tmp;
        }
        data.push(row);
        
        // IE11 호환: class를 function constructor로 변환
        function CustomTextEditor(props) {
            var el = document.createElement('input');
            var maxLength = props.columnInfo.editor.options.maxLength;
            
            el.type = 'text';
            el.maxLength = maxLength;
            el.value = String(props.value);
            
            this.el = el;
        }
        
        CustomTextEditor.prototype.getElement = function() {
            return this.el;
        };
        
        CustomTextEditor.prototype.getValue = function() {
            return this.el.value;
        };
        
        CustomTextEditor.prototype.mounted = function() {
            this.el.select();
        };
        
        var grid = new tui.Grid({
            el: document.getElementById('grid'),
            data: data,
            bodyHeight: 800,
            columns: [
                {
                    header: '납품예정일',
                    name: 'col1',
                    sortingType: 'desc',
                    sortable: true,
                    width: 120,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: '본천장도면',
                    name: 'col2',
                    width: 100,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: 'L/C도면',
                    name: 'col3',
                    width: 100,
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
                    name: 'col4',
                    width: 350,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: '발주처',
                    name: 'col5',
                    width: 120,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: '타입',
                    name: 'col6',
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
                    header: 'Car Insize',
                    name: 'col7',
                    width: 100,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: '납품내역',
                    name: 'col8',
                    width: 300,
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
    });
    
    /**
     * 숫자 포맷팅
     */
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
    
    /**
     * 전월
     */
    function pre_month() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        mm = mm - 1;
        if (mm < 1) {
            mm = '12';
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        if (mm >= 12) {
            yyyy = yyyy - 1;
        }
        
        var frompreyear = yyyy + '-' + mm + '-01';
        var tmp = 0;
        
        switch (Number(mm)) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                tmp = 30;
                break;
        }
        
        var topreyear = yyyy + '-' + mm + '-' + tmp;
        
        var fromdateElement = document.getElementById("fromdate");
        var todateElement = document.getElementById("todate");
        var boardForm = document.getElementById('board_form');
        
        if (fromdateElement) fromdateElement.value = frompreyear;
        if (todateElement) todateElement.value = topreyear;
        if (boardForm) boardForm.submit();
    }
    
    /**
     * 당해월
     */
    function this_month() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        if (mm < 10) {
            mm = '0' + mm;
        }
        
        var frompreyear = yyyy + '-' + mm + '-01';
        var tmp = 0;
        
        switch (Number(mm)) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                tmp = 30;
                break;
        }
        
        var topreyear = yyyy + '-' + mm + '-' + tmp;
        
        var fromdateElement = document.getElementById("fromdate");
        var todateElement = document.getElementById("todate");
        var boardForm = document.getElementById('board_form');
        
        if (fromdateElement) fromdateElement.value = frompreyear;
        if (todateElement) todateElement.value = topreyear;
        if (boardForm) boardForm.submit();
    }
    
    /**
     * 당해년도
     */
    function this_year() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        if (mm < 10) {
            mm = '0' + mm;
        }
        
        var frompreyear = yyyy + '-01' + '-01';
        var tmp = 0;
        
        switch (Number(mm)) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                tmp = 31;
                break;
            case 2:
                tmp = 28;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                tmp = 30;
                break;
        }
        
        var topreyear = yyyy + '-' + mm + '-' + dd;
        
        var fromdateElement = document.getElementById("fromdate");
        var todateElement = document.getElementById("todate");
        var boardForm = document.getElementById('board_form');
        
        if (fromdateElement) fromdateElement.value = frompreyear;
        if (todateElement) todateElement.value = topreyear;
        if (boardForm) boardForm.submit();
    }
    
})();
</script>

<div class="clear"></div>

</body>
</html>
