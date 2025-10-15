<?php
/**
 * 서한컴퍼니 외주 청구일 일괄처리
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location: {$base_url}/login/login_form.php");
    exit;
}

// 날짜 변환 함수
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}

// 요청 변수 초기화
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : '';
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : '';
$recordDate = isset($_REQUEST["recordDate"]) ? $_REQUEST["recordDate"] : date("Y-m-d");

// 체크박스 변수 초기화
$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : (isset($_POST["check"]) ? $_POST["check"] : '0');
$plan_output_check = isset($_REQUEST["plan_output_check"]) ? $_REQUEST["plan_output_check"] : (isset($_POST["plan_output_check"]) ? $_POST["plan_output_check"] : '0');
$output_check = isset($_REQUEST["output_check"]) ? $_REQUEST["output_check"] : (isset($_POST["output_check"]) ? $_POST["output_check"] : '0');
$team_check = isset($_REQUEST["team_check"]) ? $_REQUEST["team_check"] : (isset($_POST["team_check"]) ? $_POST["team_check"] : '0');
$measure_check = isset($_REQUEST["measure_check"]) ? $_REQUEST["measure_check"] : (isset($_POST["measure_check"]) ? $_POST["measure_check"] : '0');

// 페이지 변수
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

// 정렬 관련 변수
$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : 0;
$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : 0;
$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : 0;

// 정렬 로직
if (isset($_REQUEST["sortof"])) {
    if ($sortof == 1 && $stable == 0) {
        $cursort = ($cursort != 1) ? 1 : 2;
    }
    if ($sortof == 2 && $stable == 0) {
        $cursort = ($cursort != 3) ? 3 : 4;
    }
    if ($sortof == 3 && $stable == 0) {
        $cursort = ($cursort != 5) ? 5 : 6;
    }
    if ($sortof == 4 && $stable == 0) {
        $cursort = ($cursort != 7) ? 7 : 8;
    }
    if ($sortof == 5 && $stable == 0) {
        $cursort = ($cursort != 9) ? 9 : 10;
    }
    if ($sortof == 6 && $stable == 0) {
        $cursort = ($cursort != 11) ? 11 : 12;
    }
} else {
    $sortof = 0;
    $cursort = 0;
}

// 기타 변수 초기화
$sum = [];
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : '';
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : '';
$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : '';
$process = isset($_REQUEST["process"]) ? $_REQUEST["process"] : '';
$asprocess = isset($_REQUEST["asprocess"]) ? $_REQUEST["asprocess"] : '';
$up_fromdate = isset($_REQUEST["up_fromdate"]) ? $_REQUEST["up_fromdate"] : '';
$up_todate = isset($_REQUEST["up_todate"]) ? $_REQUEST["up_todate"] : '';
$separate_date = isset($_REQUEST["separate_date"]) ? $_REQUEST["separate_date"] : '';
$view_table = isset($_REQUEST["view_table"]) ? $_REQUEST["view_table"] : '';

// 날짜 설정
if (empty($fromdate)) {
    $fromdate = substr(date("Y-m-d", time()), 0, 7) . "-01";
}

if (empty($todate)) {
    $todate = date("Y-m-d");
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$orderby = " ORDER BY deadline DESC";
$now = date("Y-m-d");

// SQL 쿼리 생성 (Prepared Statement 사용)
$sql = '';
$params = [];

if ($mode == "search") {
    if (empty($search)) {
        $sql = "SELECT * FROM mirae8440.oem WHERE deadline BETWEEN DATE(?) AND DATE(?)" . $orderby;
        $params = [$fromdate, $Transtodate];
    } else {
        $sql = "SELECT * FROM mirae8440.oem WHERE ((workplacename LIKE ? ) OR (firstordman LIKE ? ) OR (secondordman LIKE ? ) OR (chargedman LIKE ? ) ";
        $sql .= "OR (delivery LIKE ? ) OR (firstord LIKE ? ) OR (secondord LIKE ? ) OR (memo LIKE ? )) AND (deadline BETWEEN DATE(?) AND DATE(?))" . $orderby;
        $searchTerm = "%{$search}%";
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $fromdate, $Transtodate];
    }
} else {
    $sql = "SELECT * FROM mirae8440.oem WHERE deadline BETWEEN DATE(?) AND DATE(?)" . $orderby;
    $params = [$fromdate, $Transtodate];
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열 변수 초기화
$counter = 0;
$workday_arr = [];
$workplacename_arr = [];
$address_arr = [];
$secondord_arr = [];
$sum_arr = [];
$delivery_arr = [];
$content_arr = [];
$num_arr = [];
$demand_arr = [];

$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$sum = [0, 0, 0, 0, 0, 0];

$dis_text = '';
$jamb_total = 0;

try {
    $stmh = $pdo->prepare($sql);
    
    // 파라미터 바인딩
    foreach ($params as $index => $param) {
        $stmh->bindValue($index + 1, $param, PDO::PARAM_STR);
    }
    
    $stmh->execute();
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
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
        $orderday = $row["orderday"];
        $measureday = $row["measureday"];
        $drawday = $row["drawday"];
        $deadline = $row["deadline"];
        $delicompany = $row["delicompany"];
        $delivery = $row["delivery"];
        $delipay = $row["delipay"];
        $workday = $row["workday"];
        $startday = $row["startday"];
        $testday = $row["testday"];
        $worker = $row["worker"];
        $endworkday = $row["endworkday"];
        $material1 = $row["material1"];
        $material2 = $row["material2"];
        $material3 = $row["material3"];
        $material4 = $row["material4"];
        $material5 = $row["material5"];
        $material6 = $row["material6"];
        $memo = $row["memo"];
        $regist_day = $row["regist_day"];
        $update_day = $row["update_day"];
        $demand = $row["demand"];
        
        $type1 = $row["type1"];
        $inseung1 = $row["inseung1"];
        $car_insize1 = $row["car_insize1"];
        $su = $row["su"];
        $bon_su = $row["bon_su"];
        $lc_su = $row["lc_su"];
        $etc_su = $row["etc_su"];
        $air_su = $row["air_su"];
        
        $type2 = $row["type2"];
        $type3 = $row["type3"];
        $type4 = $row["type4"];
        $type5 = $row["type5"];
        $type6 = $row["type6"];
        $type7 = $row["type7"];
        $type8 = $row["type8"];
        $type9 = $row["type9"];
        $type10 = $row["type10"];
        $inseung2 = $row["inseung2"];
        $inseung3 = $row["inseung3"];
        $inseung4 = $row["inseung4"];
        $inseung5 = $row["inseung5"];
        $inseung6 = $row["inseung6"];
        $inseung7 = $row["inseung7"];
        $inseung8 = $row["inseung8"];
        $inseung9 = $row["inseung9"];
        $inseung10 = $row["inseung10"];
        $car_insize2 = $row["car_insize2"];
        $car_insize3 = $row["car_insize3"];
        $car_insize4 = $row["car_insize4"];
        $car_insize5 = $row["car_insize5"];
        $car_insize6 = $row["car_insize6"];
        $car_insize7 = $row["car_insize7"];
        $car_insize8 = $row["car_insize8"];
        $car_insize9 = $row["car_insize9"];
        $car_insize10 = $row["car_insize10"];
        
        $comment1 = $row["comment1"];
        $comment2 = $row["comment2"];
        $comment3 = $row["comment3"];
        $comment4 = $row["comment4"];
        $comment5 = $row["comment5"];
        $comment6 = $row["comment6"];
        $comment7 = $row["comment7"];
        $comment8 = $row["comment8"];
        $comment9 = $row["comment9"];
        $comment10 = $row["comment10"];
        
        $order_date1 = $row["order_date1"];
        $order_date2 = $row["order_date2"];
        $order_date3 = $row["order_date3"];
        $order_date4 = $row["order_date4"];
        $order_input_date1 = $row["order_input_date1"];
        $order_input_date2 = $row["order_input_date2"];
        $order_input_date3 = $row["order_input_date3"];
        $order_input_date4 = $row["order_input_date4"];
        
        // 미선언 변수 초기화
        $first_item1 = '';
        $first_item2 = '';
        $first_item3 = '';
        $first_item4 = '';
        $second_item1 = '';
        $second_item2 = '';
        $second_item3 = '';
        $second_item4 = '';
        $third_item1 = '';
        $third_item2 = '';
        $third_item3 = '';
        $third_item4 = '';
        $order_com1 = '';
        $order_com2 = '';
        $order_com3 = '';
        $order_com4 = '';
        
        $demand = trans_date($demand);
        
        $sum[0] = $sum[0] + (int)$su;
        $sum[1] += (int)$bon_su;
        $sum[2] += (int)$lc_su;
        $sum[3] += (int)$etc_su;
        $sum[4] += (int)$air_su;
        $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
        
        $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),   L/C : " . $sum[2] . "  (EA), 기타 : " . $sum[3] . "  (EA)";
        
        $workitem = "";
        
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
        
        $workday_arr[$counter] = $workday;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $delivery_arr[$counter] = $delivery;
        $secondord_arr[$counter] = $secondord;
        $num_arr[$counter] = $num;
        $demand_arr[$counter] = $demand;
        
        $content_arr[$counter] = $type1 . " " . $inseung1 . " " . $car_insize1 . " " . $first_item1 . " " . $first_item2 . " " . $first_item3 . " " . $first_item4 . " " . $comment1;
        $content_arr[$counter] .= $type2 . " " . $inseung2 . " " . $car_insize2 . " " . $second_item1 . " " . $second_item2 . " " . $second_item3 . " " . $second_item4 . " " . $comment2;
        $content_arr[$counter] .= $type3 . " " . $inseung3 . " " . $car_insize3 . " " . $third_item1 . " " . $third_item2 . " " . $third_item3 . " " . $third_item4 . " " . $comment3;
        
        $sum_arr[$counter] = $workitem;
        
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("외주 데이터 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}

$all_sum = $sum1 + $sum2 + $sum3;
?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>서한컴퍼니 외주 청구일 일괄처리</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
    <script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
</head>
<body>

<div id="wrap">
    <div id="content" style="width:1450px;">
        <form name="board_form" id="board_form" method="post" action="batchDB.php?mode=search&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&asprocess=<?= htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8') ?>&fromdate=<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>&todate=<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>&up_fromdate=<?= htmlspecialchars($up_fromdate, ENT_QUOTES, 'UTF-8') ?>&up_todate=<?= htmlspecialchars($up_todate, ENT_QUOTES, 'UTF-8') ?>&separate_date=<?= htmlspecialchars($separate_date, ENT_QUOTES, 'UTF-8') ?>&view_table=<?= htmlspecialchars($view_table, ENT_QUOTES, 'UTF-8') ?>">
            
            <div id="list_search" style="width:1200px;">
                <div id="list_search111">
                    <?php if ($separate_date == "1") { ?>
                        &nbsp; 입출고일 <input type="radio" checked name="separate_date" value="1">
                        &nbsp; 접수일 <input type="radio" name="separate_date" value="2">
                    <?php } ?>
                    
                    <?php if ($separate_date == "2") { ?>
                        &nbsp; 입출고일 <input type="radio" name="separate_date" value="1">
                        &nbsp; 접수일 <input type="radio" checked name="separate_date" value="2">
                    <?php } ?>
                    
                    <input id="prepremonth" type="button" onclick="prepre_month()" value="전전월">
                    <input id="premonth" type="button" onclick="pre_month()" value="전월">
                    <input type="date" id="fromdate" name="fromdate" size="12" value="<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 시작일">부터
                    <input type="date" id="todate" name="todate" size="12" value="<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 끝">까지
                    <input id="thismonth" type="button" onclick="this_month()" value="당월">
                    <input id="thisyear" type="button" onclick="this_year()" value="당해년도">
                </div>
                
                <div id="list_search2"><img src="../img/select_search.gif" alt="Search"></div>
                <div id="list_search4"><input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"></div>
                <div id="list_search5"><input type="image" src="../img/list_search_button.gif" alt="Search Button"></div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div id="list_search6">합계 : <input type="text" id="dis_text" size="100" style="font-size:12px;"></div>
                <br>
            </div>
            
            <div class="clear"></div>
            
            <h3 class="input-text">
                출고일 기준 자료 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="date" id="recordDate" name="recordDate" size="12" value="<?= htmlspecialchars($recordDate, ENT_QUOTES, 'UTF-8') ?>" placeholder=""> 선택체크
                <button type="button" id="saveBtn" class="btn btn-secondary">적용&저장</button>&nbsp;
                <button type="button" id="clearBtn" class="btn btn-outline-danger">선택 Clear</button>&nbsp;
            </h3>
            
            <div class="clear"></div>
            <div id="grid" style="width:1620px;"></div>
            <div class="clear"></div>
        </form>
    </div>
    <div class="clear"></div>
</div>

<form id="Form1" name="Form1">
    <input type="hidden" id="num_arr" name="num_arr[]">
    <input type="hidden" id="recordDate_arr" name="recordDate_arr[]">
</form>

<script type="text/javascript">
(function() {
    'use strict';
    
    var arr1 = <?php echo json_encode($workday_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr2 = <?php echo json_encode($workplacename_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr3 = <?php echo json_encode($address_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr4 = <?php echo json_encode($sum_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr5 = <?php echo json_encode($delivery_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr6 = <?php echo json_encode($content_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr7 = <?php echo json_encode($secondord_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr8 = <?php echo json_encode($num_arr, JSON_UNESCAPED_UNICODE); ?>;
    var arr9 = <?php echo json_encode($demand_arr, JSON_UNESCAPED_UNICODE); ?>;
    var rowNum = <?php echo json_encode($counter, JSON_UNESCAPED_UNICODE); ?>;
    var jambTotal = <?php echo json_encode($jamb_total, JSON_UNESCAPED_UNICODE); ?>;
    var disText = <?php echo json_encode($dis_text, JSON_UNESCAPED_UNICODE); ?>;
    
    var totalSum = 0;
    var data = [];
    var columns = [];
    
    // 데이터 생성
    for (var i = 0; i < rowNum; i++) {
        totalSum = totalSum + Number(uncomma(arr6[i]));
        var row = { name: i };
        row['col1'] = arr1[i];
        row['col2'] = arr2[i];
        row['col3'] = arr7[i];
        row['col4'] = arr3[i];
        row['col5'] = arr4[i];
        row['col6'] = arr5[i];
        row['col7'] = arr6[i];
        row['col8'] = arr8[i];
        row['col9'] = arr9[i];
        data.push(row);
    }
    
    // Custom Text Editor
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
    
    $(document).ready(function() {
        // TUI Grid 초기화
        var grid = new tui.Grid({
            el: document.getElementById('grid'),
            data: data,
            bodyHeight: 700,
            columns: [
                {
                    header: '출고일',
                    name: 'col1',
                    sortingType: 'desc',
                    sortable: true,
                    width: 80,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: '청구일',
                    name: 'col9',
                    color: 'red',
                    sortingType: 'desc',
                    sortable: true,
                    width: 80,
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
                    width: 280,
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
                    name: 'col3',
                    width: 150,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: '현장주소',
                    name: 'col4',
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
                    header: '수량',
                    name: 'col5',
                    width: 150,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
                    align: 'center'
                },
                {
                    header: '운송내역',
                    name: 'col6',
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
                    header: '상세내역',
                    name: 'col7',
                    width: 250,
                    editor: {
                        type: CustomTextEditor,
                        options: {
                            maxLength: 50
                        }
                    },
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
            rowHeaders: ['rowNum', 'checkbox'],
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
        
        // 저장 버튼
        $("#saveBtn").click(function() {
            var tmp = grid.getCheckedRowKeys();
            tmp.forEach(function(e) {
                grid.setValue(e, 'col9', $("#recordDate").val());
            });
            savegrid();
        });
        
        // Clear 버튼
        $("#clearBtn").click(function() {
            var tmp = grid.getCheckedRowKeys();
            tmp.forEach(function(e) {
                grid.setValue(e, 'col9', '');
            });
            savegrid();
        });
        
        /**
         * Grid 변경된 내용을 PHP 넘기기 위해 input hidden에 넣는다
         */
        function savegrid() {
            var numArr = [];
            var recordDateArr = [];
            
            var MAXcount = grid.getRowCount();
            
            for (var i = 0; i < MAXcount; i++) {
                numArr.push(grid.getValue(i, 'col8'));
                recordDateArr.push(grid.getValue(i, 'col9'));
            }
            
            $('#num_arr').val(numArr);
            $('#recordDate_arr').val(recordDateArr);
            
            $.ajax({
                url: "saveDemand.php",
                type: "post",
                data: $("#Form1").serialize(),
                dataType: "json",
                success: function(data) {
                    console.log(data);
                },
                error: function(jqxhr, status, error) {
                    console.error(status, error);
                }
            });
        }
        
        dis_text();
    });
    
    /**
     * 숫자 포맷팅 함수
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
     * 전전월
     */
    function prepre_month() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        mm = mm - 2;
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
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
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
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
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
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
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
        
        document.getElementById("fromdate").value = frompreyear;
        document.getElementById("todate").value = topreyear;
        document.getElementById('board_form').submit();
    }
    
    /**
     * 합계 텍스트 표시
     */
    function dis_text() {
        $("#dis_text").val(disText);
    }
    
})();
</script>

</body>
</html>
