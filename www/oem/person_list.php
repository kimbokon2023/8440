<?php
/**
 * 미실측리스트 추출
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 세션 변수 초기화 (?? '' 형태)
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
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미실측리스트 추출</title>
    
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

<?php
// 요청 변수 초기화 (?? '' 형태)
$search = $_REQUEST["search"] ?? '';
$list = $_REQUEST["list"] ?? 0;

require_once("../lib/mydb.php");
$pdo = db_connect();

$attached = " AND (measureday='') ";
$orderby = "ORDER BY orderday DESC";
$a = " " . $orderby;

// SQL 쿼리 (Prepared Statement 사용 - SQL Injection 방지)
$sql = "SELECT * FROM mirae8440.work WHERE (worker LIKE ?)" . $attached . $a;

// 배열 변수 초기화
$counter = 0;
$secondord_arr = [];
$workplacename_arr = [];
$address_arr = [];
$sum_arr = [];
$material_arr = [];
$hpi_arr = [];
$firstordman_arr = [];
$firstordmantel_arr = [];
$chargedman_arr = [];
$chargedmantel_arr = [];
$startday_arr = [];
$testday_arr = [];

try {
    $stmh = $pdo->prepare($sql);
    $searchTerm = "%{$search}%";
    $stmh->bindValue(1, $searchTerm, PDO::PARAM_STR);
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
        
        $secondord_arr[$counter] = $secondord;
        $workplacename_arr[$counter] = $workplacename;
        $address_arr[$counter] = $address;
        $material_arr[$counter] = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;
        $chargedman_arr[$counter] = $chargedman;
        $chargedmantel_arr[$counter] = $chargedmantel;
        $firstordman_arr[$counter] = $firstordman;
        $firstordmantel_arr[$counter] = $firstordmantel;
        $hpi_arr[$counter] = $hpi;
        $startday_arr[$counter] = $startday;
        $testday_arr[$counter] = $testday;
        
        $workitem = "";
        if ($widejamb != "") {
            $workitem = "막판" . $widejamb . " ";
        }
        if ($normaljamb != "") {
            $workitem .= "막(無)" . $normaljamb . " ";
        }
        if ($smalljamb != "") {
            $workitem .= "쪽쟘" . $smalljamb . " ";
        }
        
        $sum_arr[$counter] = $workitem;
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("미실측리스트 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}
?>

<div id="wrap">
    <div id="content">
        <div id="spreadsheet"></div>
        <div class="clear"></div>
        <div class="clear"></div>
        
        <div id="order2"></div>
        <div class="clear"></div>
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
    
    var sort = function(instance, cellNum, orderParam) {
        var orderType = (orderParam) ? 'desc' : 'asc';
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
        [''], [''], [''], [''], [''], [''], [''], [''], [''], [''],
        [''], [''], [''], [''], [''], [''], [''], [''], [''], [''],
        [''], [''], [''], [''], [''], [''], [''], [''], [''], [''],
        [''], [''], [''], [''], [''], [''], [''], [''], [''], [''],
        [''], [''], [''], [''], [''], [''], [''], [''], [''], [''],
        [''], [''], [''], [''], [''], [''], [''], [''], [''], [''],
        [''], [''], [''], [''], [''], [''], [''], [''], [''], [''],
        [''], [''], [''], ['']
    ];
    
    var table1 = jexcel(document.getElementById('spreadsheet'), {
        data: data,
        tableOverflow: true,
        rowResize: true,
        columnDrag: true,
        tableHeight: '600px',
        tableWidth: '1450px',
        columns: [
            { title: '발주처', type: 'text', width: '60' },
            { title: '현장명', type: 'text', width: '150' },
            { title: '현장주소', type: 'text', width: '180' },
            { title: '수량', type: 'text', width: '100' },
            { title: '재질', type: 'text', width: '200' },
            { title: 'HPI', type: 'text', width: '100' },
            { title: '담당자PM', type: 'text', width: '80' },
            { title: '전화번호', type: 'text', width: '100' },
            { title: '현장소장', type: 'text', width: '80' },
            { title: '전화번호', type: 'text', width: '100' },
            { title: '착공일', type: 'text', width: '90' },
            { title: '검사일', type: 'text', width: '90' }
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
    
    /**
     * 데이터 로드 함수
     */
    window.load_data = function() {
        var arr1 = <?php echo json_encode($secondord_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr2 = <?php echo json_encode($workplacename_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr3 = <?php echo json_encode($address_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr4 = <?php echo json_encode($sum_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr5 = <?php echo json_encode($material_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr6 = <?php echo json_encode($hpi_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr7 = <?php echo json_encode($firstordman_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr8 = <?php echo json_encode($firstordmantel_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr9 = <?php echo json_encode($chargedman_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr10 = <?php echo json_encode($chargedmantel_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr11 = <?php echo json_encode($startday_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr12 = <?php echo json_encode($testday_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        var rowNum = <?php echo json_encode($counter, JSON_UNESCAPED_UNICODE); ?>;
        
        table1.setRowData(0, ["발주처", "현장명", "현장주소", "설치수량", "재질", "HPI형태", "담당자PM", "담당전번", "현장소장", "소장전번", "착공일", "검사일"]);
        
        for (var i = 0; i < rowNum; i++) {
            table1.setRowData(i + 1, [arr1[i], arr2[i], arr3[i], arr4[i], arr5[i], arr6[i], arr7[i], arr8[i], arr9[i], arr10[i], arr11[i], arr12[i]]);
        }
    };
    
    // 페이지 로드 후 데이터 로드
    setTimeout(function() {
        load_data();
    }, 500);
    
})();
</script>

</body>
</html>
