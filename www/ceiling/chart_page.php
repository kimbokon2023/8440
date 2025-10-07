<?php

$fileName = getDocumentRoot() . "/ceiling/gridData.json";

// 파일의 내용을 불러오기
if (file_exists($fileName)) 
    $data = file_get_contents($fileName);

// 기존의 JSON 데이터 파싱 코드
$arrayData = json_decode($data, true);

// 각 컬럼의 데이터를 저장할 배열 초기화
$col1 = [];
$col2 = [];
$col3 = [];
$col4 = [];
$col5 = [];

// JSON 데이터를 순회하며 각 컬럼의 데이터 저장
foreach($arrayData as $row) {
    $col1[] = isset($row['col1']) ? $row['col1'] : null;
    $col2[] = isset($row['col2']) ? $row['col2'] : null;
    $col3[] = isset($row['col3']) ? $row['col3'] : null;
    $col4[] = isset($row['col4']) ? $row['col4'] : null;
    $col5[] = isset($row['col5']) ? $row['col5'] : null;
}

// var_dump($arrayData);
  
$display_sel='bar';	
$item_sel="가공파트(레/V/절)";	
 
$fromdatechart=date("Y-m-d",time());		
$todatechart=strtotime($fromdatechart . '+20 days');
   		  

?>
		
<div class="col-sm-4">  
	<div class="card mt-1 mb-3">  
	<div class="card-body">  		
<?php
// Define your SQL query based on search and date conditions

$now = date("Y-m-d");

$sql = "SELECT * FROM mirae8440.ceiling WHERE deadline BETWEEN DATE(:fromdatechart) AND DATE(:todatechart)";

require_once("../lib/mydb.php");
$pdo = db_connect();

// Prepare the SQL query
$stmt = $pdo->prepare($sql);

// Define the variables to bind
$fromdatechart = $_REQUEST['fromdatechart'] ?? $now; // 예를 들어 현재 날짜로 설정하거나 요청에서 가져옵니다
$todatechart = $_REQUEST['todatechart'] ?? date("Y-m-d", strtotime("+20 days")); 

// Bind parameters
$stmt->bindParam(':fromdatechart', $fromdatechart, PDO::PARAM_STR);
$stmt->bindParam(':todatechart', $todatechart, PDO::PARAM_STR);

// Execute the query
$stmt->execute();

// Fetch data into an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debugging: print the SQL query and the bound parameters
// var_dump($sql);
// var_dump($fromdatechart, $todatechart);

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

foreach ($data as $row) {	
	$num=intval($row["num"]);			  
	$su=intval($row["su"]);			  
	$bon_su=intval($row["bon_su"]);			  
	$lc_su=intval($row["lc_su"]);	
	
    $type = trim($row["type"]);
    $deadline = $row["deadline"];
	  
	$workplacename=$row["workplacename"];			  		
	$lclaser_date=$row["lclaser_date"];			  
	$lcbending_date=$row["lcbending_date"];			  
	$lcwelding_date=$row["lcwelding_date"];			  	
	$lcassembly_date=$row["lcassembly_date"];			  		  
	$eunsung_make_date=$row["eunsung_make_date"];			  
	$eunsung_laser_date=$row["eunsung_laser_date"];			  
	$mainbending_date=$row["mainbending_date"];			  
	$mainwelding_date=$row["mainwelding_date"];			  	
	$mainassembly_date=$row["mainassembly_date"];			
	$boxwrap=$row["boxwrap"];		 // 박스포장 단어	
	
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
		
		// print $Jsontype;

		if ( $deadline !== null && $Jsontype === $type ) {
			// 해당 날짜와 파트에 대한 누적 시간
			if (!isset($datePartData[$deadline])) {
				$datePartData[$deadline] = [
					'laser' => 0,
					'welding' => 0,
					'lightCase' => 0,
					'assembly' => 0,
				];
			}	
             			
			if( $bon_su> 0 and ($eunsung_laser_date === '0000-00-00' || $eunsung_laser_date === '') )
 				$datePartData[$deadline]['laser'] += (float)$laserTime * $bon_su * 0.8 ;
			if( $lc_su> 0 and ($lclaser_date === '0000-00-00' || $lclaser_date === '' ) )
 				$datePartData[$deadline]['laser'] += (float)$laserTime * $lc_su * 0.2  ;
			
			
			
			// 조립 본천장/라이트케이스 일자가 있나 확인해서 처리함			
			if( $lc_su> 0 and ($lcassembly_date === '0000-00-00' || $lcassembly_date === '')  and !in_array($type, $outModel)  )
			{
 				$datePartData[$deadline]['assembly'] += (float)$assemblyTime * $lc_su   ;	
				if((int)$bon_su > 0)
				  $text2 = '본 ' . $bon_su ;
			    if((int)$lc_su > 0)
					if($text2 ==='')
				         $text2 = 'LC ' . $lc_su ;
					   else
				         $text2 .= ', LC ' . $lc_su ;
				if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $deadline, $matches)) {
					$year = $matches[1]; // 연도 부분 추출
					$date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
				}
			  
				$dateAssembly[$deadline][] = [
					'date' => $date ,
					'place' => $workplacename, 
					'content' => $text2,
					'type' => $type,
					'num' => $num,
				];					
				
			if($boxwrap==='박스포장') 			
				$datePartData[$deadline]['assembly'] += $lc_su  * 0.5  ;	 // 1개당 포장시간 30분 추가하는 로직				
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

uasort($dateAssembly, 'sortByDate');


// var_dump($dateAssembly);

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
            <div class="d-flex mt-1 mb-2 justify-content-center align-items-center" > 
                <canvas class="mychart" id="<?= $canvasId ?>" style="height: 150px !important;"></canvas>
            </div>      
        </div>
        <?php
    }    
    createSection('조립파트 작업시간', '', 'myChart_assembly', $dateAssembly, '');
    ?>
</div> <!--card-body-->
</div> <!--card -->
</div> <!--col-sm-4 -->
 
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

const assemblyData = <?php echo json_encode($assemblyData); ?>;

createChart(document.getElementById("myChart_assembly").getContext("2d"), "", assemblyData, 'assembly');

</script>
