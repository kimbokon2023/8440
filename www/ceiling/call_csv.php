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

include includePath('load_header.php');
?>
 
<title>CSV 저장</title>
<?php

// Initialize request variables
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$recordDate = $_REQUEST["recordDate"] ?? date("Y-m-d");
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;

// Initialize sorting variables
$cursort = $_REQUEST["cursort"] ?? 0;
$sortof = $_REQUEST["sortof"] ?? 0;
$stable = $_REQUEST["stable"] ?? 0;

// Initialize other variables
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
 
// Sorting logic
if (isset($_REQUEST["sortof"])) {
    if ($sortof == 1 && $stable == 0) {  // 접수일 클릭되었을때
        if ($cursort != 1)
            $cursort = 1;
        else
            $cursort = 2;
    }
    if ($sortof == 2 && $stable == 0) {  // 납기일 클릭되었을때
        if ($cursort != 3)
            $cursort = 3;
        else
            $cursort = 4;
    }
    if ($sortof == 3 && $stable == 0) {  // 실측일 클릭되었을때
        if ($cursort != 5)
            $cursort = 5;
        else
            $cursort = 6;
    }
    if ($sortof == 4 && $stable == 0) {  // 도면작성일 클릭되었을때
        if ($cursort != 7)
            $cursort = 7;
        else
            $cursort = 8;
    }
    if ($sortof == 5 && $stable == 0) {  // 출고일 클릭되었을때
        if ($cursort != 9)
            $cursort = 9;
        else
            $cursort = 10;
    }
    if ($sortof == 6 && $stable == 0) {  // 청구 클릭되었을때
        if ($cursort != 11)
            $cursort = 11;
        else
            $cursort = 12;
    }
} else {
    $sortof = 0;
    $cursort = 0;
}

// Initialize sum array
$sum = array(0, 0, 0, 0, 0, 0);

// Initialize date variables
if ($fromdate == "") {
    $fromdate = date("Y-m-d", time());
}
if ($todate === "") {
    $todate = date("Y-m-d");
    $Transtodate = strtotime($todate . '+31 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$orderby = " ORDER BY workday DESC ";
	
$now = date("Y-m-d");  // 현재 날짜와 크거나 같으면 출고예정으로 구분

// Build SQL query
if ($mode == "search") {
    if ($search == "") {
        $sql = "SELECT * FROM {$DB}.ceiling WHERE workday BETWEEN DATE('$fromdate') AND DATE('$Transtodate')" . $orderby;
    } elseif ($search != "") {
        $sql = "SELECT * FROM {$DB}.ceiling WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
        $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN DATE('$fromdate') AND DATE('$Transtodate'))" . $orderby;
    }
}

require_once includePath('lib/mydb.php');
$pdo = db_connect();

// Initialize counters and arrays
$counter = 0;
$csv_dump = array();
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
   
try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $firstord = $row["firstord"] ?? '';
        $secondord = $row["secondord"] ?? '';
        $orderday = $row["orderday"] ?? '';
        $deadline = $row["deadline"] ?? '';
        $demand = $row["demand"] ?? '';
        $type = $row["type"] ?? '';
        $inseung = $row["inseung"] ?? '';
        $su = $row["su"] ?? '';
        $bon_su = $row["bon_su"] ?? '';
        $lc_su = $row["lc_su"] ?? '';
        $etc_su = $row["etc_su"] ?? '';
        $air_su = $row["air_su"] ?? '';
        $car_insize = $row["car_insize"] ?? '';

        $sum[0] = $sum[0] + (int)$su;
        $sum[1] += (int)$bon_su;
        $sum[2] += (int)$lc_su;
        $sum[3] += (int)$etc_su;
        $sum[4] += (int)$air_su;
        $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

        $orderday = trans_date($orderday);
        $deadline = trans_date($deadline);

        $csv_dump[$counter] = '';
        $csv_dump[$counter] .= $orderday . ",";
        $csv_dump[$counter] .= $firstord . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $secondord) . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $workplacename) . ",";
        $csv_dump[$counter] .= $deadline . ",";
        $csv_dump[$counter] .= str_replace(",", "; ", $type) . ",";
        $csv_dump[$counter] .= $inseung . ",";
        $csv_dump[$counter] .= $su . ",";
        $csv_dump[$counter] .= $bon_su . ",";
        $csv_dump[$counter] .= $lc_su . ",";
        $csv_dump[$counter] .= $etc_su . ",";
        $csv_dump[$counter] .= $air_su . ",";
        $csv_dump[$counter] .= $car_insize . ",";

        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
$all_sum = $sum1 + $sum2 + $sum3;
?>

<body>

<form name="board_form" id="board_form" method="post" action="call_csv.php?mode=search&year=<?php echo urlencode($year); ?>&search=<?php echo urlencode($search); ?>&process=<?php echo urlencode($process); ?>&asprocess=<?php echo urlencode($asprocess); ?>&fromdate=<?php echo urlencode($fromdate); ?>&todate=<?php echo urlencode($todate); ?>&up_fromdate=<?php echo urlencode($up_fromdate); ?>&up_todate=<?php echo urlencode($up_todate); ?>&separate_date=<?php echo urlencode($separate_date); ?>&view_table=<?php echo urlencode($view_table); ?>">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">

                <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
                    <span class="badge bg-success fs-6">CSV 엑셀 다운로드</span> &nbsp;&nbsp;
                </div>

                <div class="row">
                    <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                        <!-- 기간설정 칸 -->
                        <?php include includePath('setdate.php'); ?>
                    </div>
                </div>

            </div> <!-- end of card-header -->
            <h3> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 검색버튼을 클릭 후 '엑셀CSV저장'을 클릭해 주세요.
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                <button type="button" class="btn btn-secondary" id="downloadcsvBtn"> CSV 엑셀 다운로드 </button>&nbsp;&nbsp;&nbsp;
            </h3>
            <br>

        </div> <!-- end of list_search -->
    </div> <!-- end of container-fluid -->
</form>

<script> 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


function prepre_month() {  // 전전월
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
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
} 


function pre_month() {  // 전월
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
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


function this_month() {  // 당해월
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
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


function this_year() {  // 당해년도
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
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


$(document).ready(function() {

    var arr = <?php echo json_encode($csv_dump ?: array()); ?> || [];
    var counter = <?php echo json_encode($counter); ?>;
    var total_sum = 0;

    $("#downloadcsvBtn").click(function() {
        Do_gridexport();
    });

    // CSV 파일 export
    function Do_gridexport() {
        let csvContent = "data:text/csv;charset=utf-8,\uFEFF";  // 한글파일은 뒤에 \uFEFF 추가

        // header 넣기
        let row = "";
        row += "번호,날짜,원청,발주처,현장명,납기일,타입,인승,수량,본천장,L/C,기타,공기청정기,Car Insize ";
        csvContent += row + "\r\n";

        const COLNUM = 13;
        for (let i = 0; i < counter; i++) {
            let row = "";
            row += (i + 1) + ',';
            let tmp = String(arr[i] || '');
            tmp = tmp.replace(/undefined/gi, "");
            row += tmp.replace(/#/gi, " ");
            csvContent += row + "\r\n";
        }

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "miraeCSV_CeilingData.csv");
        document.body.appendChild(link);
        link.click();
    }

});

</script>

</body>
</html>