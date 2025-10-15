<?php
/**
 * 배송비 산출 페이지
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

// 체크박스 변수 초기화 (?? '' 형태)
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';

// 페이지 변수
$page = $_REQUEST["page"] ?? 1;

// 정렬 관련 변수
$cursort = $_REQUEST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? '0';

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
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

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

$orderby = " ORDER BY workday DESC";
$now = date("Y-m-d");

// SQL 쿼리 생성 (Prepared Statement 사용)
$sql = '';
$params = [];

if ($mode == "search") {
    if (empty($search)) {
        $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN DATE(?) AND DATE(?)" . $orderby;
        $params = [$fromdate, $Transtodate];
    } else {
        $sql = "SELECT * FROM mirae8440.work WHERE ((workplacename LIKE ?) OR (firstordman LIKE ?) OR (secondordman LIKE ?) OR (chargedman LIKE ?) ";
        $sql .= "OR (delicompany LIKE ?) OR (hpi LIKE ?) OR (firstord LIKE ?) OR (secondord LIKE ?) OR (worker LIKE ?) OR (memo LIKE ?)) AND (workday BETWEEN DATE(?) AND DATE(?))" . $orderby;
        $searchTerm = "%{$search}%";
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $fromdate, $Transtodate];
    }
} else {
    // 기본값
    $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN DATE(?) AND DATE(?)" . $orderby;
    $params = [$fromdate, $Transtodate];
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열 변수 초기화
$counter = 0;
$workday_arr = [];
$workplacename_arr = [];
$address_arr = [];
$sum_arr = [];
$delicompany_arr = [];
$delipay_arr = [];
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$all_sum = 0;
$jamb_total = '';

try {
    $stmh = $pdo->prepare($sql);
    
    // 파라미터 바인딩
    foreach ($params as $index => $param) {
        $stmh->bindValue($index + 1, $param, PDO::PARAM_STR);
    }
    
    $stmh->execute();
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
        $widejamb = $row["widejamb"];
        $normaljamb = $row["normaljamb"];
        $smalljamb = $row["smalljamb"];
        $memo = $row["memo"];
        $regist_day = $row["regist_day"];
        $update_day = $row["update_day"];
        $demand = $row["demand"];
        $startday = $row["startday"];
        $testday = $row["testday"];
        $hpi = $row["hpi"];
        $delicompany = $row["delicompany"];
        $delipay = $row["delipay"];
        
        // 날짜 변환
        if ($orderday != "0000-00-00" && $orderday != "1970-01-01" && $orderday != "") {
            $orderday = date("Y-m-d", strtotime($orderday));
        } else {
            $orderday = "";
        }
        
        if ($measureday != "0000-00-00" && $measureday != "1970-01-01" && $measureday != "") {
            $measureday = date("Y-m-d", strtotime($measureday));
        } else {
            $measureday = "";
        }
        
        if ($drawday != "0000-00-00" && $drawday != "1970-01-01" && $drawday != "") {
            $drawday = date("Y-m-d", strtotime($drawday));
        } else {
            $drawday = "";
        }
        
        if ($deadline != "0000-00-00" && $deadline != "1970-01-01" && $deadline != "") {
            $deadline = date("Y-m-d", strtotime($deadline));
        } else {
            $deadline = "";
        }
        
        if ($workday != "0000-00-00" && $workday != "1970-01-01" && $workday != "") {
            $workday = date("Y-m-d", strtotime($workday));
        } else {
            $workday = "";
        }
        
        if ($endworkday != "0000-00-00" && $endworkday != "1970-01-01" && $endworkday != "") {
            $endworkday = date("Y-m-d", strtotime($endworkday));
        } else {
            $endworkday = "";
        }
        
        if ($demand != "0000-00-00" && $demand != "1970-01-01" && $demand != "") {
            $demand = date("Y-m-d", strtotime($demand));
        } else {
            $demand = "";
        }
        
        if ($startday != "0000-00-00" && $startday != "1970-01-01" && $startday != "") {
            $startday = date("Y-m-d", strtotime($startday));
        } else {
            $startday = "";
        }
        
        if ($testday != "0000-00-00" && $testday != "1970-01-01" && $testday != "") {
            $testday = date("Y-m-d", strtotime($testday));
        } else {
            $testday = "";
        }
        
        $workday_arr[$counter] = $workday;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $delicompany_arr[$counter] = $delicompany;
        $delipay_arr[$counter] = $delipay;
        
        $workitem = "";
        if ($widejamb != "") {
            $workitem = "막판" . $widejamb . " ";
            $sum1 += (int)$widejamb;
        }
        if ($normaljamb != "") {
            $workitem .= "막(無)" . $normaljamb . " ";
            $sum2 += (int)$normaljamb;
        }
        if ($smalljamb != "") {
            $workitem .= "쪽쟘" . $smalljamb . " ";
            $sum3 += (int)$smalljamb;
        }
        
        $sum_arr[$counter] = $workitem;
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("배송비 데이터 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}

$all_sum = $sum1 + $sum2 + $sum3;
$jamb_total = "막판:" . $sum1 . ", " . "막판(無):" . $sum2 . ", " . "쪽쟘:" . $sum3 . "  합계:" . $all_sum;
?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>배송비 산출</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" type="text/css" href="../css/jexcel.css">
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>

<div id="wrap">
    <div id="content" style="width:1450px;">
        <form name="board_form" id="board_form" method="post" action="delivery_fee.php?mode=search&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&asprocess=<?= htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8') ?>&fromdate=<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>&todate=<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>&up_fromdate=<?= htmlspecialchars($up_fromdate, ENT_QUOTES, 'UTF-8') ?>&up_todate=<?= htmlspecialchars($up_todate, ENT_QUOTES, 'UTF-8') ?>&separate_date=<?= htmlspecialchars($separate_date, ENT_QUOTES, 'UTF-8') ?>&view_table=<?= htmlspecialchars($view_table, ENT_QUOTES, 'UTF-8') ?>">
            
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
                <div id="list_search6">합계 : <input type="text" id="dis_text" size="40" style="font-size:12px;"></div>
            </div>
            
            <div id="spreadsheet"></div>
            <div class="clear"></div>
        </form>
    </div>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var changed = function(instance, cell, x, y, value) {
        var cellName = jexcel.getColumnNameFromId([x, y]);
    };
    
    var beforeChange = function(instance, cell, x, y, value) {
        var cellName = jexcel.getColumnNameFromId([x, y]);
    };
    
    var insertedRow = function(instance) {};
    var insertedColumn = function(instance) {};
    var deletedRow = function(instance) {};
    var deletedColumn = function(instance) {};
    
    var sort = function(instance, cellNum, order) {
        var orderType = (order) ? 'desc' : 'asc';
    };
    
    var resizeColumn = function(instance, cell, width) {};
    var resizeRow = function(instance, cell, height) {};
    
    var selectionActive = function(instance, x1, y1, x2, y2, origin) {
        var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
        var cellName2 = jexcel.getColumnNameFromId([x2, y2]);
    };
    
    var loaded = function(instance) {};
    var moveRow = function(instance, from, to) {};
    var moveColumn = function(instance, from, to) {};
    var blur = function(instance) {};
    var focus = function(instance) {};
    
    var data = [
        [''],
        [''],
        ['']
    ];
    
    var table1 = jexcel(document.getElementById('spreadsheet'), {
        data: data,
        tableOverflow: true,
        rowResize: true,
        columnDrag: true,
        tableHeight: '700px',
        tableWidth: '1250px',
        columns: [
            { title: '출고일', type: 'text', width: '100' },
            { title: '현장명', type: 'text', width: '300' },
            { title: '현장주소', type: 'text', width: '350' },
            { title: '수량', type: 'text', width: '200' },
            { title: '운송자', type: 'text', width: '150' },
            { title: '비용', type: 'text', width: '120' }
        ],
        onchange: changed,
        onbeforechange: beforeChange,
        oninsertrow: insertedRow,
        oninsertcolumn: insertedColumn,
        ondeleterow: deletedRow,
        ondeletecolumn: deletedColumn,
        onselection: selectionActive,
        onsort: sort,
        onresizerow: resizeRow,
        onresizecolumn: resizeColumn,
        onmoverow: moveRow,
        onmovecolumn: moveColumn,
        onload: loaded,
        onblur: blur,
        onfocus: focus
    });
    
    $(function() {
        // Datepicker 설정 (필요시 활성화)
    });
    
    /**
     * 데이터 로드 함수
     */
    window.load_data = function() {
        var arr1 = <?php echo json_encode($workday_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr2 = <?php echo json_encode($workplacename_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr3 = <?php echo json_encode($address_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr4 = <?php echo json_encode($sum_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr5 = <?php echo json_encode($delicompany_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr6 = <?php echo json_encode($delipay_arr, JSON_UNESCAPED_UNICODE); ?>;
        var rowNum = <?php echo json_encode($counter, JSON_UNESCAPED_UNICODE); ?>;
        var jambTotal = <?php echo json_encode($jamb_total, JSON_UNESCAPED_UNICODE); ?>;
        var totalSum = 0;
        
        for (var i = 0; i < rowNum; i++) {
            table1.setRowData(i, [arr1[i], arr2[i], arr3[i], arr4[i], arr5[i], arr6[i]]);
            totalSum = totalSum + Number(uncomma(arr6[i]));
            table1.insertRow();
        }
        
        table1.setRowData(rowNum, ['', '', jambTotal, '', '배송비 합계', comma(totalSum)]);
    };
    
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
        var disText = <?php echo json_encode($jamb_total, JSON_UNESCAPED_UNICODE); ?>;
        $("#dis_text").val(disText);
    }
    
    // 페이지 로드 후 실행
    setTimeout(function() {
        load_data();
        dis_text();
    }, 500);
    
})();
</script>

</body>
</html>
