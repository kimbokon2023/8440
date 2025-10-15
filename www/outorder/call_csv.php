<?php
/**
 * 외주 주문 CSV 다운로드
 * 로컬 및 서버 환경 모두 지원
 */

// 세션 시작
session_start();

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    header("Location: {$protocol}://{$host}/login/login_form.php");
    exit;
}

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
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <title>자료 CSV 저장</title>
</head>

<?php
// 요청 변수 초기화 (?? '' 형태)
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;

// 정렬 관련 변수 초기화
$cursort = $_REQUEST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? '0';

// 정렬 로직 처리
if ($sortof != '0') {
    
    if ($sortof == 1 && $stable == 0) {
        // 접수일 클릭되었을 때
        if ($cursort != 1) {
            $cursort = 1;
        } else {
            $cursort = 2;
        }
    }
    
    if ($sortof == 2 && $stable == 0) {
        // 납기일 클릭되었을 때
        if ($cursort != 3) {
            $cursort = 3;
        } else {
            $cursort = 4;
        }
    }
    
    if ($sortof == 3 && $stable == 0) {
        // 실측일 클릭되었을 때
        if ($cursort != 5) {
            $cursort = 5;
        } else {
            $cursort = 6;
        }
    }
    
    if ($sortof == 4 && $stable == 0) {
        // 도면작성일 클릭되었을 때
        if ($cursort != 7) {
            $cursort = 7;
        } else {
            $cursort = 8;
        }
    }
    
    if ($sortof == 5 && $stable == 0) {
        // 출고일 클릭되었을 때
        if ($cursort != 9) {
            $cursort = 9;
        } else {
            $cursort = 10;
        }
    }
    
    if ($sortof == 6 && $stable == 0) {
        // 청구 클릭되었을 때
        if ($cursort != 11) {
            $cursort = 11;
        } else {
            $cursort = 12;
        }
    }
} else {
    $sortof = '0';
    $cursort = '0';
}

// 기타 요청 변수 초기화
$sum = array();
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

// 기간을 정하는 구간
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 4);
    $fromdate = $fromdate . "-01-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

// SQL 정렬 및 현재 날짜
$orderby = " ORDER BY orderday DESC ";
$now = date("Y-m-d");  // 현재 날짜와 크거나 같으면 출고예정으로 구분

// SQL 쿼리 생성
if ($mode == "search") {
    if ($search == "") {
        $sql = "SELECT * FROM {$DB}.outorder WHERE orderday BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;
    } elseif ($search != "") {
        $sql = "SELECT * FROM {$DB}.outorder WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
        $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN date('$fromdate') AND date('$Transtodate'))" . $orderby;
    }
} else {
    $sql = "SELECT * FROM {$DB}.outorder WHERE orderday BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");

try {
    $pdo = db_connect();
} catch (Exception $ex) {
    error_log("DB 연결 실패: " . $ex->getMessage());
    die("데이터베이스 연결에 실패했습니다.");
}

// 배열 및 카운터 초기화
$counter = 0;
$csv_dump = array();
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
   
// 데이터 조회 및 CSV 데이터 생성
try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // 필요한 필드만 추출 (?? '' 형태)
        $num = $row["num"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $firstord = $row["firstord"] ?? '';
        $secondord = $row["secondord"] ?? '';
        $orderday = $row["orderday"] ?? '';
        $deadline = $row["deadline"] ?? '';
        $demand = $row["demand"] ?? '';
        $type1 = $row["type1"] ?? '';
        $inseung1 = $row["inseung1"] ?? '';
        $su = $row["su"] ?? '';
        $bon_su = $row["bon_su"] ?? '';
        $lc_su = $row["lc_su"] ?? '';
        $etc_su = $row["etc_su"] ?? '';
        $car_insize1 = $row["car_insize1"] ?? '';
        $memo = $row["memo"] ?? '';
        
        // 날짜 변환
        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);
        
        // CSV 데이터 생성 (배열 초기화 포함)
        if (!isset($csv_dump[$counter])) {
            $csv_dump[$counter] = '';
        }
        
        $csv_dump[$counter] .= $orderday . ",";
        $csv_dump[$counter] .= $firstord . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $secondord) . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $workplacename) . ",";
        $csv_dump[$counter] .= $deadline . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $type1) . ",";
        $csv_dump[$counter] .= $inseung1 . ",";
        $csv_dump[$counter] .= $su . ",";
        $csv_dump[$counter] .= $bon_su . ",";
        $csv_dump[$counter] .= $lc_su . ",";
        $csv_dump[$counter] .= $etc_su . ",";
        $csv_dump[$counter] .= $car_insize1 . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $memo) . ",";
        
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("데이터 조회 오류: " . $ex->getMessage());
    print "오류: " . htmlspecialchars($ex->getMessage());
}

$all_sum = $sum1 + $sum2 + $sum3;		 
		 
?>

<body>
    <?php
    // 동적 URL 생성
    $formAction = htmlspecialchars("call_csv.php?mode=search&year={$year}&search={$search}&process={$process}&asprocess={$asprocess}&fromdate={$fromdate}&todate={$todate}&up_fromdate={$up_fromdate}&up_todate={$up_todate}&separate_date={$separate_date}&view_table={$view_table}", ENT_QUOTES, 'UTF-8');
    ?>
    
    <form name="board_form" id="board_form" method="post" action="<?= $formAction ?>">
        <h2>&nbsp;&nbsp;&nbsp;&nbsp; CSV파일로 저장하기</h2>
   
   <div id="list_search">
		
        <div id="list_search111">
            <?php if ($separate_date == "1"): ?>
                &nbsp; 입출고일 <input type="radio" checked name="separate_date" value="1">
                &nbsp; 접수일 <input type="radio" name="separate_date" value="2">
            <?php endif; ?>
            
            <?php if ($separate_date == "2"): ?>
                &nbsp; 입출고일 <input type="radio" name="separate_date" value="1">
                &nbsp; 접수일 <input type="radio" checked name="separate_date" value="2">
            <?php endif; ?>
            
            <input id="prepremonth" type="button" onclick="prepre_month()" value="전전월">
            <input id="premonth" type="button" onclick="pre_month()" value="전월">
            <input type="date" id="fromdate" name="fromdate" size="12" value="<?= htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 시작일">부터
            <input type="date" id="todate" name="todate" size="12" value="<?= htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') ?>" placeholder="기간 끝">까지
            <input id="thismonth" type="button" onclick="this_month()" value="당월">
            <input id="thisyear" type="button" onclick="this_year()" value="당해년도">
        </div>
        
        <div id="list_search2">
            <img src="../img/select_search.gif">
        </div>
        
        <div id="list_search4">
            <input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        
        <div id="list_search5">
            <input type="image" src="../img/list_search_button.gif">
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        <br>
        <div class="clear"></div>
        <br>
        
        <h3>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 검색버튼을 클릭 후 'CSV 엑셀 다운로드'를 클릭해 주세요.
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
            <button type="button" class="btn btn-secondary" id="downloadcsvBtn">CSV 엑셀 다운로드</button>&nbsp;&nbsp;&nbsp;
        </h3>
        <br>
        
        </div> <!-- end of list_search -->
    </form> 

 


    <div class="clear"></div>
</body>

<script>
(function() {
    'use strict';
    
    // 유틸리티 함수들
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }


    // 전전월 조회 (전역 함수로 노출)
    window.prepre_month = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; // January is 0!
        var yyyy = today.getFullYear();
        
        if (dd < 10) {
            dd = '0' + dd;
        }
        
        mm = mm - 2;  // 전전월
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
    }; 


    // 전월 조회 (전역 함수로 노출)
    window.pre_month = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; // January is 0!
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
    };
    
    // 당해월 조회 (전역 함수로 노출)
    window.this_month = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; // January is 0!
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
    };
    
    // 당해년도 조회 (전역 함수로 노출)
    window.this_year = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; // January is 0!
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
    };


    $(document).ready(function() {
        
        var arr = <?php echo json_encode($csv_dump, JSON_UNESCAPED_UNICODE); ?>;
        var counter = <?php echo json_encode($counter, JSON_UNESCAPED_UNICODE); ?>;
        var total_sum = 0;
        
        // CSV 다운로드 버튼 클릭
        $("#downloadcsvBtn").click(function() {
            Do_gridexport();
        });
        
        // CSV 파일 export 함수
        function Do_gridexport() {
            // 한글파일은 뒤에 \uFEFF 추가해서 해결함.
            var csvContent = "data:text/csv;charset=utf-8,\uFEFF";
            
            // header 넣기
            var row = "";
            row += "번호,날짜,원청,발주처,현장명,납기일,타입,인승,수량,본천장,L/C,기타, Car Inside, 메모 ";
            
            csvContent += row + "\r\n";
            
            var COLNUM = 14;
            for (var i = 0; i < counter; i++) {
                var row = "";
                row += (i + 1) + ',';
                var tmp = String(arr[i]);
                tmp = tmp.replace(/undefined/gi, "");
                row += tmp.replace(/#/gi, " ");
                csvContent += row + "\r\n";
            }
            
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "miraeCSV_OutorderData.csv");
            document.body.appendChild(link);
            link.click();
        }
        
    });  // end document.ready
    
})();  // end IIFE
</script>

</html>
