<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정
$base_url = getBaseUrl();

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["user_name"] ?? '';

$title_message = '조명/천장 (모델/파트별) 작업시간';

include includePath('load_header.php');
?>

<title><?php echo $title_message; ?></title>
</head>

<?php
// JSON 파일 읽기
$fileName = "gridData.json";
$data = '';
$arrayData = array();

// 파일의 내용을 불러오기
if (file_exists($fileName)) {
    $data = file_get_contents($fileName);
    // 기존의 JSON 데이터 파싱 코드
    $arrayData = json_decode($data, true);
}

// 각 컬럼의 데이터를 저장할 배열 초기화
$col1 = [];
$col2 = [];
$col3 = [];
$col4 = [];
$col5 = [];

// JSON 데이터를 순회하며 각 컬럼의 데이터 저장
if (is_array($arrayData)) {
    foreach ($arrayData as $row) {
        $col1[] = isset($row['col1']) ? $row['col1'] : null;
        $col2[] = isset($row['col2']) ? $row['col2'] : null;
        $col3[] = isset($row['col3']) ? $row['col3'] : null;
        $col4[] = isset($row['col4']) ? $row['col4'] : null;
        $col5[] = isset($row['col5']) ? $row['col5'] : null;
    }
}

// 요청 변수 안전하게 초기화
$display_sel = $_REQUEST["display_sel"] ?? 'bar';
$item_sel = $_REQUEST["item_sel"] ?? "가공파트(레/V/절)";
$check = $_REQUEST["check"] ?? ($_POST["check"] ?? '');
$mode = $_REQUEST["mode"] ?? '';
$displaytext = $_REQUEST["displaytext"] ?? "이번주";
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$year = $_REQUEST["year"] ?? '';
$search = $_REQUEST["search"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

// 기간을 정하는 구간
if ($fromdate == "") {
    $fromdate = date("Y-m-d", time());
}

if ($todate == "") {
    $Transtodate = strtotime($fromdate . '+6 days');
    $Transtodate = date("Y-m-d", $Transtodate);
    $todate = $Transtodate;
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

?>

<body>

<form name="board_form" id="board_form" method="post" action="workreport.php?mode=search&year=<?php echo urlencode($year); ?>&search=<?php echo urlencode($search); ?>&process=<?php echo urlencode($process); ?>&asprocess=<?php echo urlencode($asprocess); ?>&fromdate=<?php echo urlencode($fromdate); ?>&todate=<?php echo urlencode($todate); ?>&up_fromdate=<?php echo urlencode($up_fromdate); ?>&up_todate=<?php echo urlencode($up_todate); ?>&separate_date=<?php echo urlencode($separate_date); ?>&view_table=<?php echo urlencode($view_table); ?>">
    <div class="container-fluid mt-2">
        <div class="card mt-1 mb-4">
            <div class="card-body">
                <div class="card-header mt-2 justify-content-center text-center">
                    <span class="fs-4"><?php echo $title_message; ?>
                        &nbsp;&nbsp;&nbsp;
                        <button type="button" id="inputTableBtn" class="btn btn-primary btn-sm">모델/파트별 작업시간 정리표</button>
                    </span>
                </div>

                <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center">
                    <div class="input-group p-2 mb-2 justify-content-center">
                        <button type="button" id="thisweek" class="btn btn-dark btn-sm" onclick='javascript:this_week();return false;'>이번주</button>&nbsp;
                        <button type="button" id="nextweek" class="btn btn-dark btn-sm" onclick='next_week()'>2주간</button>&nbsp;
                        <button type="button" class="btn btn-dark btn-sm" onclick='nextnext_week()'>3주간</button>&nbsp;
                        <span class='input-group-text align-items-center' style='width:400px;'>
                            <input type="date" id="fromdate" name="fromdate" size="12" value="<?php echo htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8'); ?>" placeholder="기간 시작일">&nbsp;부터&nbsp;
                            <input type="date" id="todate" name="todate" size="12" value="<?php echo htmlspecialchars($todate, ENT_QUOTES, 'UTF-8'); ?>" placeholder="기간 끝">&nbsp;까지
                        </span>&nbsp;
                        &nbsp;
                    </div>
                </div>
	
    <?php
    // SQL 쿼리 정의
    $now = date("Y-m-d");
    $sqltag = '';

    $sql = "SELECT * FROM {$DB}.ceiling WHERE deadline BETWEEN DATE(:fromdate) AND DATE(:Transtodate) " . $sqltag;

    // Prepare the SQL query
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $stmt->bindParam(':Transtodate', $Transtodate, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch data into an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the statement
    $stmt->closeCursor();

    // 날짜별로 연관 배열에 누적시간을 기록할 배열 초기화
    $datePartData = [];
    $dateLaser = [];
    $dateWelding = [];
    $dateAssembly = [];
    
    // 데이터베이스에서 가져온 모든 날짜를 저장하는 배열
    $allDates = array();

    // 라이트케이스 외주모델
    $outModel = ["011", "012", "013D", "025", "017", "017S", "017M", "014", "037", "038"];

    // 데이터베이스에서 가져온 데이터를 순회하며 날짜를 추출하여 $allDates에 추가
    foreach ($data as $row) {
        $deadline = trim($row["deadline"]);
        if ($deadline !== "") {
            $allDates[] = $deadline;
        }
    }

    // fromdate와 todate 사이의 모든 날짜를 생성
    $currentDate = strtotime($fromdate);
    $endDate = strtotime($todate);
    while ($currentDate <= $endDate) {
        $allDates[] = date("Y-m-d", $currentDate);
        $currentDate = strtotime("+1 day", $currentDate);
    }

    // 중복 제거 및 정렬
    $allDates = array_unique($allDates);
    sort($allDates);

    // 모든 날짜에 대한 초기 작업 시간 설정
    foreach ($allDates as $date) {
        if (!isset($datePartData[$date])) {
            $datePartData[$date] = [
                'laser' => 0,
                'welding' => 0,
                'lightCase' => 0,
                'assembly' => 0,
            ];
        }
    }

    foreach ($data as $row) {
        $num = intval($row["num"]);
        $su = intval($row["su"]);
        $bon_su = intval($row["bon_su"]);
        $lc_su = intval($row["lc_su"]);

        $type = trim($row["type"]);
        $deadline = $row["deadline"];

        $workplacename = $row["workplacename"];
        $lclaser_date = $row["lclaser_date"];
        $lcbending_date = $row["lcbending_date"];
        $lcwelding_date = $row["lcwelding_date"];
        $lcassembly_date = $row["lcassembly_date"];
        $eunsung_make_date = $row["eunsung_make_date"];
        $eunsung_laser_date = $row["eunsung_laser_date"];
        $mainbending_date = $row["mainbending_date"];
        $mainwelding_date = $row["mainwelding_date"];
        $mainassembly_date = $row["mainassembly_date"];
        $boxwrap = $row["boxwrap"];

        $text = '';
        $text1 = '';
        $text2 = '';

        // JSON 데이터를 순회하며 날짜별 작업 시간 누적
        foreach ($arrayData as $rowjson) {
            $Jsontype = isset($rowjson['col1']) ? trim($rowjson['col1']) : null;
            $laserTime = isset($rowjson['col2']) ? $rowjson['col2'] : 0;
            $weldingTime = isset($rowjson['col3']) ? $rowjson['col3'] : 0;
            $lightCaseTime = isset($rowjson['col4']) ? $rowjson['col4'] : 0;
            $assemblyTime = isset($rowjson['col5']) ? $rowjson['col5'] : 0;

            if ($deadline !== null && $Jsontype === $type) {
                // 해당 날짜와 파트에 대한 누적 시간
                if (!isset($datePartData[$deadline])) {
                    $datePartData[$deadline] = [
                        'laser' => 0,
                        'welding' => 0,
                        'lightCase' => 0,
                        'assembly' => 0,
                    ];
                }

                // 작업 시간을 해당 파트에 더합니다.
                // laser 본천장 일자가 있나 확인해서 처리함
                if ($bon_su > 0 && ($eunsung_laser_date === '0000-00-00' || $eunsung_laser_date === '')) {
                    $datePartData[$deadline]['laser'] += (float)$laserTime * $bon_su * 0.8;
                }
                if ($lc_su > 0 && ($lclaser_date === '0000-00-00' || $lclaser_date === '')) {
                    $datePartData[$deadline]['laser'] += (float)$laserTime * $lc_su * 0.2;
                }

                if (($bon_su > 0 && ($eunsung_laser_date === '0000-00-00' || $eunsung_laser_date === '')) || ($lc_su > 0 && ($lclaser_date === '0000-00-00' || $lclaser_date === '') && !in_array($type, $outModel))) {
                    if ((int)$bon_su > 0) {
                        $text = '본 ' . $bon_su;
                    }
                    if ((int)$lc_su > 0) {
                        if ($text === '') {
                            $text = 'LC ' . $lc_su;
                        } else {
                            $text .= ', LC ' . $lc_su;
                        }
                    }
                    if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $deadline, $matches)) {
                        $year = $matches[1];
                        $date = $matches[2];
                    }

                    $dateLaser[$deadline][] = [
                        'date' => $date,
                        'place' => $workplacename,
                        'content' => $text,
                        'type' => $type,
                        'num' => $num,
                    ];
                }

                // 제관 본천장/라이트케이스 일자가 있나 확인해서 처리함
                if ($bon_su > 0 && ($mainwelding_date === '0000-00-00' || $mainwelding_date === '')) {
                    $datePartData[$deadline]['welding'] += (float)$weldingTime * $bon_su;
                }
                if ($lc_su > 0 && ($lcwelding_date === '0000-00-00' || $lcwelding_date === '')) {
                    $datePartData[$deadline]['welding'] += (float)$lightCaseTime * $lc_su;
                }

                if (($bon_su > 0 && ($mainwelding_date === '0000-00-00' || $mainwelding_date === '')) || ($lc_su > 0 && ($lcwelding_date === '0000-00-00' || $lcwelding_date === '') && !in_array($type, $outModel))) {
                    if ((int)$bon_su > 0) {
                        $text1 = '본 ' . $bon_su;
                    }
                    if ((int)$lc_su > 0) {
                        if ($text1 === '') {
                            $text1 = 'LC ' . $lc_su;
                        } else {
                            $text1 .= ', LC ' . $lc_su;
                        }
                    }

                    if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $deadline, $matches)) {
                        $year = $matches[1];
                        $date = $matches[2];
                    }

                    $dateWelding[$deadline][] = [
                        'date' => $date,
                        'place' => $workplacename,
                        'content' => $text1,
                        'type' => $type,
                        'num' => $num,
                    ];
                }

                // 조립 본천장/라이트케이스 일자가 있나 확인해서 처리함
                if ($lc_su > 0 && ($lcassembly_date === '0000-00-00' || $lcassembly_date === '') && !in_array($type, $outModel)) {
                    $datePartData[$deadline]['assembly'] += (float)$assemblyTime * $lc_su;
                    
                    if ((int)$bon_su > 0) {
                        $text2 = '본 ' . $bon_su;
                    }
                    if ((int)$lc_su > 0) {
                        if ($text2 === '') {
                            $text2 = 'LC ' . $lc_su;
                        } else {
                            $text2 .= ', LC ' . $lc_su;
                        }
                    }
                    if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $deadline, $matches)) {
                        $year = $matches[1];
                        $date = $matches[2];
                    }

                    $dateAssembly[$deadline][] = [
                        'date' => $date,
                        'place' => $workplacename,
                        'content' => $text2,
                        'type' => $type,
                        'num' => $num,
                    ];

                    if ($boxwrap === '박스포장') {
                        $datePartData[$deadline]['assembly'] += $lc_su * 0.5;  // 1개당 포장시간 30분 추가
                    }
                }
            }
        }
    }

    // 사용자 정의 비교 함수
    function sortByDate($a, $b) {
        $dateA = $a[0]['date'];
        $dateB = $b[0]['date'];
        return strcmp($dateA, $dateB);
    }

    // $dateLaser 배열을 date 키에 따라 오름차순으로 정렬
    uasort($dateLaser, 'sortByDate');
    uasort($dateWelding, 'sortByDate');
    uasort($dateAssembly, 'sortByDate');

    // $datePartData 배열을 날짜 기준으로 오름차순 정렬
    ksort($datePartData);

    // laserData 배열 생성
    $laserData = array();
    foreach ($datePartData as $date => $partData) {
        if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $date, $matches)) {
            $year = $matches[1];
            $date = $matches[2];
        }
        $laserData[] = array(
            'date' => $date,
            'laser' => $partData['laser']
        );
    }

    // weldingData 배열 생성
    $weldingData = array();
    foreach ($datePartData as $date => $partData) {
        if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $date, $matches)) {
            $year = $matches[1];
            $date = $matches[2];
        }
        $weldingData[] = array(
            'date' => $date,
            'welding' => $partData['welding']
        );
    }

    // assemblyData 배열 생성
    $assemblyData = array();
    foreach ($datePartData as $date => $partData) {
        if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $date, $matches)) {
            $year = $matches[1];
            $date = $matches[2];
        }
        $assemblyData[] = array(
            'date' => $date,
            'assembly' => $partData['assembly']
        );
    }
    ?>

    <style>
        table th, table td {
            font-size: 13px;
        }
    </style>

    <div class="row mt-3 mb-1">
        <div class="d-flex mt-2 mb-3 justify-content-center align-items-center">
            <span class="fs-6 badge bg-dark">조회기간 :</span>&nbsp;
            <input type="text" id="displaytext" name="displaytext" value="<?php echo htmlspecialchars($displaytext, ENT_QUOTES, 'UTF-8'); ?>" size="4" readonly>
        </div>
    </div>

    <div class="row">
        <?php
        function createSection($title, $subtitle, $canvasId, $data, $label) {
            ?>
            <div class="col-sm-4 mt-2 mb-5 justify-content-center align-items-center">
                <div class="d-flex mt-2 mb-5 justify-content-center align-items-center">
                    <span class="fs-5 badge bg-secondary"><?php echo $title; ?></span>&nbsp;<span class="fs-5"><?php echo $subtitle; ?></span>
                </div>
                <div class="d-flex mt-2 mb-5 justify-content-center align-items-center">
                    <canvas class="mychart" id="<?php echo $canvasId; ?>"></canvas>
                </div>
                <div class="d-flex mt-5 mb-5 justify-content-center align-items-center">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">일자</th>
                                <th class="text-center">현장명</th>
                                <th class="text-center">모델</th>
                                <th class="text-center">제작내역</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $dates): ?>
                                <?php foreach ($dates as $detail): ?>
                                    <tr>
                                        <td><?php echo $detail['date']; ?></td>
                                        <td><?php echo $detail['place']; ?></td>
                                        <td><?php echo $detail['type']; ?></td>
                                        <td><?php echo $detail['content']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }

        createSection('가공파트', '(Laser/V컷/절곡)', 'myChart_laser', $dateLaser, '가공파트 작업시간');
        createSection('제관파트', '(용접/도장)', 'myChart_welding', $dateWelding, '제관파트 작업시간');
        createSection('조립파트', '', 'myChart_assembly', $dateAssembly, '조립파트 작업시간');
        ?>
    </div>

            </div> <!--card-body-->
        </div> <!--card -->
    </div> <!--container-->
</form>
</body>

<script>
    const colors = [
        "rgba(75, 192, 192, 0.2)",
        "rgba(255, 99, 132, 0.2)",
        "rgba(54, 162, 235, 0.2)",
        'rgba(153, 102, 255, 0.2)',
        'rgba(205, 100, 25, 0.2)',
        'rgba(25, 66, 200, 0.2)',
        'rgba(95, 452, 60, 0.2)',
        'rgba(113, 62, 55, 0.2)',
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)'
    ];

    const annotation = {
        type: 'line',
        mode: 'horizontal',
        scaleID: 'y',
        value: 8,
        borderColor: 'rgba(255, 0, 0, 1)',
        borderWidth: 3,
        borderDash: [5, 5],
        label: {
            content: '8 hours',
            enabled: true,
            position: 'right'
        }
    };

    function createChart(ctx, label, data, dataKey) {
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: data.map(entry => entry.date),
                datasets: [{
                    label: label,
                    data: data.map(entry => entry[dataKey]),
                    backgroundColor: colors.slice(0, data.length),
                    borderColor: colors.slice(0, data.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 10,
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 3
                    }
                },
                plugins: {
                    annotation: {
                        annotations: [annotation]
                    }
                }
            }
        });
    }

    const laserData = <?php echo json_encode($laserData ?: array(), JSON_UNESCAPED_UNICODE); ?>;
    const weldingData = <?php echo json_encode($weldingData ?: array(), JSON_UNESCAPED_UNICODE); ?>;
    const assemblyData = <?php echo json_encode($assemblyData ?: array(), JSON_UNESCAPED_UNICODE); ?>;

    createChart(document.getElementById("myChart_laser").getContext("2d"), "가공파트 작업시간", laserData, 'laser');
    createChart(document.getElementById("myChart_welding").getContext("2d"), "제관파트 작업시간", weldingData, 'welding');
    createChart(document.getElementById("myChart_assembly").getContext("2d"), "조립파트 작업시간", assemblyData, 'assembly');

    $(function() {
        $("#inputTableBtn").click(function() {
            popupCenter('workreport_table.php', '테이블', 1100, 850);
        });
    });
</script>
</html>
