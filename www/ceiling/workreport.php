<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session_header.php')); 
$title_message = '조명/천장 (모델/파트별) 작업시간';

?>

<?php include getDocumentRoot() . '/load_header.php' ?>
 
<title> <?=$title_message?> </title> 
</head>
 
 <?php 
 
 
$fileName = "gridData.json";

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
  
  if(isset($_REQUEST["display_sel"]))   //목록표에 제목,이름 등 나오는 부분
	 $display_sel=$_REQUEST["display_sel"];	 
	 else
		 	 $display_sel='bar';	

  if(isset($_REQUEST["item_sel"]))   //목록표에 제목,이름 등 나오는 부분
			$item_sel=$_REQUEST["item_sel"];	 
	 else
		 	 $item_sel="가공파트(레/V/절)";	

 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용 
 	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";    
 
  if(isset($_REQUEST["displaytext"]))
     $displaytext=$_REQUEST["displaytext"];
  else 
     $displaytext="이번주";        
 
// 기간을 정하는 구간
 
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

 if($fromdate=="")
	{
		$fromdate=date("Y-m-d",time());		
	}
if($todate=="")
	{		
		$Transtodate=strtotime($todate . '+6 days');
		$Transtodate=date("Y-m-d",$Transtodate);
		$todate = $Transtodate;
	}
    else
	{
		$Transtodate=strtotime($todate);
		$Transtodate=date("Y-m-d",$Transtodate);
	}
 
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

?>
		 
<body>

<form name="board_form" id="board_form"  method="post" action="workreport.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
<div class="container-fluid mt-2">  
	<div class="card mt-1 mb-4">  
	<div class="card-body">  
	<div class="card-header mt-2  justify-content-center text-center "> 		
		<span class="fs-4" > <?=$title_message?> 
		     &nbsp;&nbsp;&nbsp;
		    <button type="button" id="inputTableBtn" class="btn btn-primary btn-sm"> 모델/파트별 작업시간 정리표</button>
		 </span>	
	</div>		
	
	
	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 					
	 
    <div class="input-group p-2 mb-2  justify-content-center ">
	<button type="button" id="thisweek" class="btn btn-dark btn-sm"  onclick='javascript:this_week();return false;' > 이번주 </button>	&nbsp;  	   
	<button type="button" id="nextweek" class="btn btn-dark btn-sm"  onclick='next_week()' > 2주간 </button>	&nbsp;  	   
	<button type="button" class="btn btn-dark btn-sm"  onclick='nextnext_week()' > 3주간 </button>	&nbsp;  	   	
    <span class='input-group-text align-items-center' style='width:400 px;'>  
    <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp; 부터 &nbsp;  
       <input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">  &nbsp;  까지    </span>  &nbsp;
		&nbsp;        
       </div>	
</div>
	
  <?php
// Define your SQL query based on search and date conditions

$now = date("Y-m-d");

$sql = "SELECT * FROM mirae8440.ceiling WHERE deadline BETWEEN DATE(:fromdate) AND DATE(:Transtodate) " . $sqltag;

require_once("../lib/mydb.php");
$pdo = db_connect();

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
$outModel = ["011","012","013D","025","017","017S","017M","014","037","038"];

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

             			
			
			// print 'aa ' . $eunsung_laser_date;
			// print 'bb ' . $lclaser_date;
			
			// 작업 시간을 해당 파트에 더합니다.
			// laser 본천장 일자가 있나 확인해서 처리함
			if( $bon_su> 0 and ($eunsung_laser_date === '0000-00-00' || $eunsung_laser_date === '') )
 				$datePartData[$deadline]['laser'] += (float)$laserTime * $bon_su * 0.8 ;
			if( $lc_su> 0 and ($lclaser_date === '0000-00-00' || $lclaser_date === '' ) )
 				$datePartData[$deadline]['laser'] += (float)$laserTime * $lc_su * 0.2  ;
			
			if( ( $bon_su> 0 and ($eunsung_laser_date === '0000-00-00' || $eunsung_laser_date === '') ) or ( $lc_su> 0 and ($lclaser_date === '0000-00-00' || $lclaser_date === '' ) and !in_array($type, $outModel)  ) )
			{
				if((int)$bon_su > 0)
				  $text = '본 ' . $bon_su ;
			    if((int)$lc_su > 0)
					if($text==='')
				         $text = 'LC ' . $lc_su ;
					   else
				         $text .= ', LC ' . $lc_su ;
				if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $deadline, $matches)) {
					$year = $matches[1]; // 연도 부분 추출
					$date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
				}
			  
				$dateLaser[$deadline][] =  [
					'date' => $date ,
					'place' => $workplacename, 
					'content' => $text,
					'type' => $type,
					'num' => $num,
				];	
			}				
						
			
			// 제관 본천장/라이트케이스 일자가 있나 확인해서 처리함
			if( $bon_su> 0 and ($mainwelding_date === '0000-00-00' || $mainwelding_date === '') )
 				$datePartData[$deadline]['welding'] += (float)$weldingTime * $bon_su  ;
			if( $lc_su> 0 and ($lcwelding_date === '0000-00-00' || $lcwelding_date === '') )
 				$datePartData[$deadline]['welding'] += (float)$lightCaseTime * $lc_su   ;
			
			if( ( $bon_su> 0 and ($mainwelding_date === '0000-00-00' || $mainwelding_date === '') ) or ( $lc_su> 0 and ($lcwelding_date === '0000-00-00' || $lcwelding_date === '' )  and !in_array($type, $outModel)  ) )						
			{
				if((int)$bon_su > 0)
				  $text1 = '본 ' . $bon_su ;
			    if((int)$lc_su > 0)
					if($text1==='')
				         $text1 = 'LC ' . $lc_su ;
					   else
				         $text1 .= ', LC ' . $lc_su ;
					 
				if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $deadline, $matches)) {
					$year = $matches[1]; // 연도 부분 추출
					$date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
				}
			  
				$dateWelding[$deadline][] = [
					'date' => $date ,
					'place' => $workplacename, 
					'content' => $text1,
					'type' => $type,
					'num' => $num,
				];				
				
				
				
			}
			
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

// $dateLaser 배열을 date 키에 따라 오름차순으로 정렬합니다
uasort($dateLaser, 'sortByDate');
uasort($dateWelding, 'sortByDate');
uasort($dateAssembly, 'sortByDate');


// var_dump($dateLaser);

// $datePartData 배열을 날짜 기준으로 오름차순 정렬
ksort($datePartData);

// // 결과 출력
// foreach ($datePartData as $date => $partData) {
    // echo "날짜: " . $date . "<br>";
    // echo "레이져 작업 시간: " . $partData['laser'] . "<br>";
    // echo "본천장 용접 시간: " . $partData['welding'] . "<br>";
    // echo "조명천장 용접 시간: " . $partData['lightCase'] . "<br>";
    // echo "조립 작업 시간: " . $partData['assembly'] . "<br>";
// }

$laserData = array();
foreach ($datePartData as $date => $partData) {
	if (preg_match('/(\d{4})-(\d{2}-\d{2})/', $date, $matches)) {
		$year = $matches[1]; // 연도 부분 추출
		$date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
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
		$year = $matches[1]; // 연도 부분 추출
		$date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
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
		$year = $matches[1]; // 연도 부분 추출
		$date = $matches[2]; // 'yyyy-'를 제외한 날짜 부분 추출
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
    <div class="d-flex mt-2 mb-3 justify-content-center align-items-center "> 
        <span class="fs-6 badge bg-dark"> 조회기간 : </span> &nbsp; 
        <input type="text" id="displaytext" name="displaytext" value="<?=$displaytext?>" size="4" readonly> 
    </div>
</div>

<div class="row"> 
    <?php
    function createSection($title, $subtitle, $canvasId, $data, $label) {
        ?>
        <div class="col-sm-4 mt-2 mb-5 justify-content-center align-items-center"> 
            <div class="d-flex mt-2 mb-5 justify-content-center align-items-center"> 
                <span class="fs-5 badge bg-secondary"><?= $title ?></span> &nbsp; <span class="fs-5"><?= $subtitle ?></span>
            </div>
            <div class="d-flex mt-2 mb-5 justify-content-center align-items-center"> 
                <canvas class="mychart" id="<?= $canvasId ?>"></canvas>
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

const laserData = <?php echo json_encode($laserData); ?>;
const weldingData = <?php echo json_encode($weldingData); ?>;
const assemblyData = <?php echo json_encode($assemblyData); ?>;

// console.log('Laser Data:', laserData);
// console.log('Welding Data:', weldingData);
// console.log('Assembly Data:', assemblyData);

createChart(document.getElementById("myChart_laser").getContext("2d"), "가공파트 작업시간", laserData, 'laser');
createChart(document.getElementById("myChart_welding").getContext("2d"), "제관파트 작업시간", weldingData, 'welding');
createChart(document.getElementById("myChart_assembly").getContext("2d"), "조립파트 작업시간", assemblyData, 'assembly');

$(function() {
    $("#inputTableBtn").click(function() {
        popupCenter('workreport_table.php','테이블',1100,850);
    });
});
</script>
