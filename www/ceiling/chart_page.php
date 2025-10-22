<?php
require_once __DIR__ . '/../bootstrap.php';

// 변수 초기화
$fileName = getDocumentRoot() . "/ceiling/gridData.json";
$data = '';
$arrayData = [];

// 파일의 내용을 불러오기
if (file_exists($fileName)) {
    $data = file_get_contents($fileName);
}

// JSON 데이터 파싱
if ($data) {
    $arrayData = json_decode($data, true);
    if (!is_array($arrayData)) {
        $arrayData = [];
    }
}

// 각 컬럼의 데이터를 저장할 배열 초기화
$col1 = [];
$col2 = [];
$col3 = [];
$col4 = [];
$col5 = [];

// JSON 데이터를 순회하며 각 컬럼의 데이터 저장
foreach ($arrayData as $row) {
    $col1[] = isset($row['col1']) ? $row['col1'] : null;
    $col2[] = isset($row['col2']) ? $row['col2'] : null;
    $col3[] = isset($row['col3']) ? $row['col3'] : null;
    $col4[] = isset($row['col4']) ? $row['col4'] : null;
    $col5[] = isset($row['col5']) ? $row['col5'] : null;
}

// 디스플레이 설정
$display_sel = 'bar';
$item_sel = "가공파트(레/V/절)";

// 날짜 설정
$fromdatechart = date("Y-m-d", time());
$todatechart = strtotime($fromdatechart . '+20 days');

?>

<div class="col-sm-4">
    <div class="card mt-1 mb-3">
        <div class="card-body">
<?php
// SQL 쿼리 및 데이터베이스 연결

$now = date("Y-m-d");

$sql = "SELECT * FROM mirae8440.ceiling WHERE deadline BETWEEN DATE(:fromdatechart) AND DATE(:todatechart)";

require_once("../lib/mydb.php");
$pdo = db_connect();

// SQL 쿼리 준비
$stmt = $pdo->prepare($sql);

// 바인딩할 변수 정의
$fromdatechart = isset($_REQUEST['fromdatechart']) ? $_REQUEST['fromdatechart'] : $now;
$todatechart = isset($_REQUEST['todatechart']) ? $_REQUEST['todatechart'] : date("Y-m-d", strtotime("+20 days"));

// 파라미터 바인딩
$stmt->bindParam(':fromdatechart', $fromdatechart, PDO::PARAM_STR);
$stmt->bindParam(':todatechart', $todatechart, PDO::PARAM_STR);

// 쿼리 실행
$stmt->execute();

// 데이터 가져오기
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statement 닫기
$stmt->closeCursor();

// 날짜별로 연관 배열에 누적시간을 기록할 배열 초기화
$datePartData = [];
$dateLaser = [];
$dateWelding = [];
$dateAssembly = [];
// 데이터베이스에서 가져온 모든 날짜를 저장하는 배열
$allDates = array();

// 라이트케이스 외주모델
$outModel = ["011","012","013D","025","017","017S","017M","014","037","038"];

// 데이터베이스에서 가져온 데이터를 순회하며 날짜를 추출하여 $allDates에 추가
foreach ($data as $row) {
    $deadline = trim($row["deadline"]);
    if ($deadline !== "") {
        $allDates[] = $deadline;
    }
}

// fromdate와 todate 사이의 모든 날짜를 생성
$currentDate = strtotime($fromdatechart);
$endDate = strtotime($todatechart);
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

// 데이터 처리 메인 루프
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
    $boxwrap = $row["boxwrap"]; // 박스포장 단어
    
    $text = '';
    $text1 = '';
    $text2 = '';
    $year = '';
    $date = '';

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
            
            // 레이저 작업 시간 계산
            if ($bon_su > 0 && ($eunsung_laser_date === '0000-00-00' || $eunsung_laser_date === '')) {
                $datePartData[$deadline]['laser'] += (float)$laserTime * $bon_su * 0.8;
            }
            if ($lc_su > 0 && ($lclaser_date === '0000-00-00' || $lclaser_date === '')) {
                $datePartData[$deadline]['laser'] += (float)$laserTime * $lc_su * 0.2;
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
                    $year = $matches[1]; // 연도 부분 추출
                    $date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
                }
                
                $dateAssembly[$deadline][] = [
                    'date' => $date,
                    'place' => $workplacename,
                    'content' => $text2,
                    'type' => $type,
                    'num' => $num,
                ];
                
                // 박스포장 추가 시간
                if ($boxwrap === '박스포장') {
                    $datePartData[$deadline]['assembly'] += $lc_su * 0.5; // 1개당 포장시간 30분 추가
                }
            }
        }
    }
}

// 사용자 정의 비교 함수
function sortByDate($a, $b) {
    // 첫 번째 항목의 date를 가져옵니다
    $dateA = $a[0]['date'];
    
    // 두 번째 항목의 date를 가져옵니다
    $dateB = $b[0]['date'];
    
    // 두 날짜를 비교합니다
    return strcmp($dateA, $dateB);
}

// 날짜별 정렬
uasort($dateAssembly, 'sortByDate');

// $datePartData 배열을 날짜 기준으로 오름차순 정렬
ksort($datePartData);

// assemblyData 배열 생성
$assemblyData = array();
foreach ($datePartData as $date => $partData) {
    if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $date, $matches)) {
        $year = $matches[1]; // 연도 부분 추출
        $date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
    }
    $assemblyData[] = array(
        'date' => $date,
        'assembly' => $partData['assembly']
    );
}

?>
<?php
function createSection($title, $subtitle, $canvasId, $data, $label) {
    ?>
    <div class="row mt-2 mb-2 justify-content-center align-items-center">
        <div class="d-flex mt-2 mb-5 justify-content-center align-items-center">
            <span class="fs-5 badge bg-secondary"><?= $title ?></span>
        </div>
        <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
            <canvas class="mychart" id="<?= $canvasId ?>" style="height: 150px !important;"></canvas>
        </div>
    </div>
    <?php
}

createSection('조립파트 작업시간', '', 'myChart_assembly', $dateAssembly, '');
?>

        </div> <!-- card-body -->
    </div> <!-- card -->
</div> <!-- col-sm-4 -->
 

<script>
// 차트 색상 정의
const colors = [
    "rgba(75, 192, 192, 0.2)",
    "rgba(255, 99, 132, 0.2)",
    "rgba(54, 162, 235, 0.2)",
    "rgba(153, 102, 255, 0.2)",
    "rgba(205, 100, 25, 0.2)",
    "rgba(25, 66, 200, 0.2)",
    "rgba(95, 452, 60, 0.2)",
    "rgba(113, 62, 55, 0.2)",
    "rgba(255, 99, 132, 0.2)",
    "rgba(54, 162, 235, 0.2)"
];

// 8시간 기준선 설정
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

// 차트 생성 함수
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
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 10,
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 3
                }
            },
            plugins: {  // 8시간 적색라인 표현
                annotation: {
                    annotations: [annotation]
                }
            }
        }
    });
}

// 조립 데이터
const assemblyData = <?php echo json_encode($assemblyData); ?>;

// 차트 생성
createChart(document.getElementById("myChart_assembly").getContext("2d"), "", assemblyData, 'assembly');

</script>
