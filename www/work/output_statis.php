<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

$title_message = "JAMB 매출";

// 환경파일 불러오기
$readIni = array();
$readIni = parse_ini_file("./estimate.ini", false);

include includePath('load_header.php');
?>

<!-- Highcharts 라이브러리 -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<title><?= $title_message ?></title>

<!-- Light & Subtle Theme CSS -->
<link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />

<style>
/* ========================================= */
/* Light & Subtle Theme - Output Statistics Specific */
/* ========================================= */
 
body {
  background: var(--gradient-primary);
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

/* Form Controls - 1.3배 크기 조정 */
.output-form-control {
  border: 1px solid var(--dashboard-border);
  border-radius: 4px;
  font-size: 1.0rem;  /* 1.3배 크기 */
  padding: 0.65rem;    /* 패딩도 1.3배 */
  background: white;
  color: var(--dashboard-text);
}

/* Tables - Light & Subtle Theme */
.output-table {
  background: white;
  border: 1px solid var(--dashboard-border);
  border-radius: 8px;
  overflow: hidden;
  box-shadow: none;
  width: 100%;
  border-collapse: collapse;
}

.output-table th {
  background: #f8fafc !important;  /* Very light gray */
  color: var(--dashboard-text) !important;
  padding: 0.75rem !important;
  font-size: 0.85rem !important;
  font-weight: 500 !important;
  border: 1px solid #e2e8f0 !important;
  text-align: center !important;
  vertical-align: middle !important;
  position: relative;
}

.output-table td {
  padding: 0.75rem !important;
  font-size: 0.85rem !important;
  color: var(--dashboard-text) !important;
  border: 1px solid #e2e8f0 !important;
  vertical-align: middle !important;
  background: white;
}

/* 컴팩트 테이블 스타일 */
.output-table.table-sm th {
  padding: 0.5rem !important;
  font-size: 0.8rem !important;
}

.output-table.table-sm td {
  padding: 0.5rem !important;
  font-size: 0.8rem !important;
}

.output-table tbody tr:hover td {
  background-color: #f1f5f9 !important;
  transition: background-color 0.2s ease;
}

/* 합계 행 강조 스타일 */
.output-table .table-info td {
  background-color: #f0f9ff !important;
  font-weight: 500 !important;
}

.output-table .table-info:hover td {
  background-color: #e0f2fe !important;
}

/* 매출 세부 현황 제목 스타일 */
.output-section-title {
  color: var(--dashboard-text);
  font-weight: 600;
  font-size: 1.17rem;  /* 1.3배 크기 */
  margin: 0;
}

/* 버튼 스타일 - Light & Subtle Theme */
.btn-primary {
  background: var(--dashboard-accent) !important;
  color: var(--btn-text-on-accent) !important;
  border: none !important;
  border-radius: 6px !important;
  padding: 0.4rem 0.8rem !important;
  font-weight: 500 !important;
  font-size: 0.875rem !important;
  transition: all 0.2s ease !important;
  box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1) !important;
}

.btn-primary:hover {
  background: var(--dashboard-accent-light) !important;
  color: var(--btn-hover-text) !important;
  transform: translateY(-1px) !important;
  box-shadow: 0 2px 8px rgba(51, 65, 85, 0.15) !important;
  opacity: 0.9 !important;
}

.btn-sm {
  padding: 0.3rem 0.6rem !important;
  font-size: 0.8rem !important;
}

/* Output Statistics Chart Container */
.output-chart-container {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(51, 65, 85, 0.05);
  margin-bottom: 1rem;
  overflow: hidden;
  transition: all 0.2s ease;
  max-width: 100%;
}

.output-chart-container:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

/* 섹션 제목 스타일 개선 */
.output-chart-container h5.output-section-title {
  color: #334155;
  font-weight: 600;
  font-size: 1.1rem;
  margin: 0;
}

/* 테이블 반응형 컨테이너 */
.output-chart-container .table-responsive {
  border-radius: 8px;
  overflow: hidden;
}

/* 통합 카드 내부 소제목 스타일 */
.output-chart-container h6.text-muted {
  color: #64748b !important;
  font-weight: 500;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 0.5rem;
  margin-bottom: 1rem !important;
}

/* 통합 카드 내부 열 간격 조정 */
.output-chart-container .row > [class*="col-"] {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}

/* 모바일에서 테이블 간격 */
@media (max-width: 768px) {
  .output-chart-container .row > [class*="col-"] {
    margin-bottom: 1.5rem;
  }

  .output-chart-container .row > [class*="col-"]:last-child {
    margin-bottom: 0;
  }
}

/* Modern Management Card */
.modern-management-card {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    overflow: hidden;
    transition: all 0.2s ease;
    margin-bottom: 1rem;
}

.modern-management-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

.modern-management-card .card-body {
  padding: 1.5rem;
}

.output-form-control:focus {
  border-color: var(--dashboard-accent);
  box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
}

.output-form-label {
  color: var(--dashboard-text);
  font-weight: 500;
  font-size: 1.0rem;  /* 라벨도 1.3배 크기 */
}

/* Radio Button Styling */
.custom-radio {
  display: inline-flex;
  align-items: center;
  margin-right: 1.5rem;
  margin-bottom: 0.5rem;
}

.custom-radio .radio-input {
  opacity: 0;
  position: absolute;
  width: 0;
}

.custom-radio .radio-label {
  display: inline-flex;
  align-items: center;
  background: var(--dashboard-secondary);
  color: var(--dashboard-text);
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 500;
  transition: all 0.2s ease;
  cursor: pointer;
  border: 1px solid var(--dashboard-border);
}

.custom-radio .radio-input:checked + .radio-label {
  background: var(--gradient-accent);
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2);
}

.custom-radio .radio-label:hover {
  background: var(--dashboard-hover);
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(100, 116, 139, 0.1);
}

/* 버튼 스타일 개선 */
.btn-primary {
  background: #0369a1 !important;
  color: white !important;
  border: none !important;
  border-radius: 6px !important;
  padding: 0.5rem 0.75rem !important;
  font-weight: 500 !important;
  font-size: 0.85rem !important;
  transition: all 0.2s ease !important;
  box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1) !important;
}

.btn-primary:hover {
  background: #0284c7 !important;
  color: white !important;
  transform: translateY(-1px) !important;
  box-shadow: 0 2px 6px rgba(51, 65, 85, 0.15) !important;
}

.chart-canvas {
  height: 280px !important;
  border-radius: 8px;
}

.chart-container {
  width: 100%;
  height: 280px;
  border-radius: 8px;
}

/* Page Title */
.output-page-title {
  color: var(--dashboard-text);
  font-weight: 600;
  margin-bottom: 1.5rem;
  font-size: 1.0rem;
  text-align: center;
}

/* Filter Container */
.output-filter-container {
  background: var(--gradient-primary);
  border: 1px solid var(--dashboard-border);
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--dashboard-shadow);
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .container {
    margin: 0.5rem;
    padding: 1rem;
  }

  .output-output-form-control {
    font-size: 1rem;
    padding: 0.5rem;
  }

  .output-form-label {
    font-size: 1rem;
  }

  .custom-radio {
    margin-right: 0.8rem;
  }

  .custom-radio .radio-label {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
  }
}
</style> 
</head>		 
<body>
<?php require_once(includePath('myheader.php')); ?>

<?php
// 요청 변수 안전하게 초기화
$load_confirm = $_REQUEST["load_confirm"] ?? '';
$display_sel = $_REQUEST["display_sel"] ?? 'bar';
$item_sel = $_REQUEST["item_sel"] ?? '년도비교';
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$search = $_REQUEST["search"] ?? '';

$sum = array();

// 기간 초기화 (올해를 날짜 기간으로 설정)
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

$orderby = " ORDER BY workday DESC";

$now = date("Y-m-d");   // 현재 날짜와 크거나 같으면 출고예정으로 구분

// SQL 쿼리 생성
if ($search == "") {
    $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$fromdate') AND date('$Transtodate')" . $orderby;
} else {
    $sql = "SELECT * FROM mirae8440.work WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
    $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN date('$fromdate') AND date('$Transtodate'))" . $orderby;
}

// 변수 초기화
$WJ_HL = 0;
$WJ = 0;
$NJ_HL = 0;
$NJ = 0;
$SJ_HL = 0;
$SJ = 0;

try {
    $allstmh = $pdo->query($sql);

    while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {
        $material1 = $row["material1"];
        $material2 = $row["material2"];
        $material3 = $row["material3"];
        $material4 = $row["material4"];
        $material5 = $row["material5"];
        $material6 = $row["material6"];
        $widejamb = intval($row["widejamb"]);
        $normaljamb = intval($row["normaljamb"]);
        $smalljamb = intval($row["smalljamb"]);
        $workplacename = $row["workplacename"];

        $combinedMaterials = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;

        // 불량이란 단어가 들어가 있는 수량은 제외한다.
        $findstr = '불량';
        $pos = stripos($workplacename, $findstr);

        if ($pos == 0) {
            // $WJ_HL 계산
            if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
                $WJ_HL += $widejamb;
            }

            // $WJ 계산
            if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
                $WJ += $widejamb;
            }

            // $NJ_HL 계산
            if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
                $NJ_HL += $normaljamb;
            }

            // $NJ 계산
            if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
                $NJ += $normaljamb;
            }

            // $SJ_HL 계산
            if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
                $SJ_HL += $smalljamb;
            }

            // $SJ 계산
            if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
                $SJ += $smalljamb;
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 합계표 만들기
// 막판 산출
$WJ_amount = str_replace(',', '', $WJ) * intval(str_replace(',', '', $readIni["WJ"]));
$WJ_HL_amount = str_replace(',', '', $WJ_HL) * intval(str_replace(',', '', $readIni["WJ_HL"]));
$WJ_total = $WJ_amount + $WJ_HL_amount;
$WJ_num = $WJ + $WJ_HL;

// 막판무 산출
$NJ_amount = str_replace(',', '', $NJ) * intval(str_replace(',', '', $readIni["NJ"]));
$NJ_HL_amount = str_replace(',', '', $NJ_HL) * intval(str_replace(',', '', $readIni["NJ_HL"]));
$NJ_total = $NJ_amount + $NJ_HL_amount;
$NJ_num = $NJ + $NJ_HL;

// 쪽쟘 산출
$SJ_amount = str_replace(',', '', $SJ) * intval(str_replace(',', '', $readIni["SJ"]));
$SJ_HL_amount = str_replace(',', '', $SJ_HL) * intval(str_replace(',', '', $readIni["SJ_HL"]));
$SJ_total = $SJ_amount + $SJ_HL_amount;
$SJ_num = $SJ + $SJ_HL;

$total = $WJ_total + $NJ_total + $SJ_total;

// 배열 초기화
$counter = 0;
$workday_arr = array();
$workplacename_arr = array();
$address_arr = array();
$sum_arr = array();
$delicompany_arr = array();
$delipay_arr = array();
$secondord_arr = array();
$worker_arr = array();

$item_arr = array();
$work_sum = array();
$month_sum = array();

$item_arr[0] = '막판';
$item_arr[1] = '막(無)';
$item_arr[2] = '쪽쟘';

$work_sum[0] = $WJ_total;
$work_sum[1] = $NJ_total;
$work_sum[2] = $SJ_total;

$all_sum = $work_sum[0] + $work_sum[1] + $work_sum[2];

$year = substr($fromdate, 0, 4);

// 년도 비교
if ($item_sel === '년도비교') 
 {
    $year_count=0;
    $date_count=0;
    $year_sum = array();	
    $year=substr($fromdate,0,4) -1;				

    while($year_count<2) {
		
        $month_count=0;		
		
        while($month_count<12) {	
		
			$month=$month_count + 1;
			switch ($month_count) {
				case   0   :   $day=31; break;
				case   1   :   $day=28; break;
				case   2   :   $day=31; break;
				case   3   :   $day=30; break;
				case   4   :   $day=31; break;
				case   5   :   $day=30; break;
				case   6   :   $day=31; break;
				case   7   :   $day=31; break;
				case   8   :   $day=30; break;
				case   9   :   $day=31; break;
				case   10  :   $day=30; break;
				case   11  :   $day=31; break;
			}			
			
            $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);
            $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);

            $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$month_fromdate') AND date('$month_todate')"; 			
		
		$sum1 = 0; $sum2 = 0; $sum3 = 0;		
		$WJ_HL = 0;
		$WJ = 0;
		$NJ_HL = 0;
		$NJ = 0;
		$SJ_HL = 0;
		$SJ = 0;
		$WJ_total = 0;
		$NJ_total = 0;
		$SJ_total = 0;
		
        try {
			$stmh = $pdo->query($sql);
			$rowNum = $stmh->rowCount();
			
			while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				
			include '_row.php';

			$widejamb = intval($row["widejamb"]);
			$normaljamb = intval($row["normaljamb"]);
			$smalljamb = intval($row["smalljamb"]);					

			$combinedMaterials = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;
				
			$findstr = '불량';
			$pos = stripos($workplacename, $findstr);							   
			   
			if($pos==0)  {
				// $WJ_HL 계산
				if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
				  $WJ_HL += $widejamb;
				}
				// $WJ 계산
				if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
				  $WJ += $widejamb;
				}
				// $NJ_HL 계산
				if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
				  $NJ_HL += $normaljamb;
				}
				// $NJ 계산
				if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
				  $NJ += $normaljamb;
				}
				// $SJ_HL 계산
				if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
				  $SJ_HL += $smalljamb;
				}
				// $SJ 계산
				if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
				  $SJ += $smalljamb;
				}
			}
					// 합계표 만들기
					// 막판 산출
					$WJ_amount = str_replace(',', '', $WJ) * intval(str_replace(',', '', $readIni["WJ"]));
					$WJ_HL_amount = str_replace(',', '', $WJ_HL) * intval(str_replace(',', '', $readIni["WJ_HL"]));
					$WJ_total = $WJ_amount + $WJ_HL_amount ;
					$WJ_num = $WJ + $WJ_HL;
					// 막판무 산출
					$NJ_amount = str_replace(',', '', $NJ) * intval(str_replace(',', '', $readIni["NJ"]));
					$NJ_HL_amount = str_replace(',', '', $NJ_HL) * intval(str_replace(',', '', $readIni["NJ_HL"]));
					$NJ_total = $NJ_amount + $NJ_HL_amount ;
					$NJ_num = $NJ + $NJ_HL;
					// 쪽쟘 산출
					$SJ_amount = str_replace(',', '', $SJ) * intval(str_replace(',', '', $readIni["SJ"]));
					$SJ_HL_amount = str_replace(',', '', $SJ_HL) * intval(str_replace(',', '', $readIni["SJ_HL"]));
					$SJ_total = $SJ_amount + $SJ_HL_amount ;
					$SJ_num = $SJ + $SJ_HL;

				 } 	 
			   } catch (PDOException $Exception) {
				print "오류: ".$Exception->getMessage();
			} 				
				
            $year_sum[$date_count] = $WJ_total + $NJ_total + $SJ_total ;
            $month_count++;
            $date_count++;
        }
        $year_count++;
        $year++;
    }
}

if($item_sel==='월별비교') 
	{
	$month_count=0;      // 월별 차트 통계 내는 부분
	while($month_count<12)		 
	{	

	$year=substr($fromdate,0,4) ;
	
	$month=$month_count + 1;
		switch ($month_count) {
			case   0   :   $day=31; break;
			case   1   :   $day=28; break;
			case   2   :   $day=31; break;
			case   3   :   $day=30; break;
			case   4   :   $day=31; break;
			case   5   :   $day=30; break;
			case   6   :   $day=31; break;
			case   7   :   $day=31; break;
			case   8   :   $day=30; break;
			case   9   :   $day=31; break;
			case   10  :   $day=30; break;
			case   11  :   $day=31; break;

	}
					  
        $month_fromdate = sprintf("%04d-%02d-%02d", $year, $month, 1);  // 날짜형식으로 바꾸기
        $month_todate = sprintf("%04d-%02d-%02d", $year, $month, $day);  // 날짜형식으로 바꾸기

        if ($search === "") {
            $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$month_fromdate') AND date('$month_todate')";
        }

        if ($search !== "") {
            $sql = "SELECT * FROM mirae8440.work WHERE ((workplacename LIKE '%$search%') OR (firstordman LIKE '%$search%') OR (secondordman LIKE '%$search%') OR (chargedman LIKE '%$search%') ";
            $sql .= "OR (delicompany LIKE '%$search%') OR (hpi LIKE '%$search%') OR (firstord LIKE '%$search%') OR (secondord LIKE '%$search%') OR (worker LIKE '%$search%') OR (memo LIKE '%$search%')) AND (workday BETWEEN date('$month_fromdate') AND date('$month_todate'))";
        }					
		$counter=0;
		$sum1=0;
		$sum2=0;
		$sum3=0;

		$sum1 = 0; $sum2 = 0; $sum3 = 0;
		
		$WJ_HL = 0;
		$WJ = 0;
		$NJ_HL = 0;
		$NJ = 0;
		$SJ_HL = 0;
		$SJ = 0;
		$WJ_total = 0;
		$NJ_total = 0;
		$SJ_total = 0;		
		
        try {
			$stmh = $pdo->query($sql);
			$rowNum = $stmh->rowCount();
			
			while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    include '_row.php';
					
					$widejamb = intval($row["widejamb"]);
					$normaljamb = intval($row["normaljamb"]);
					$smalljamb = intval($row["smalljamb"]);					
					
					$combinedMaterials = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;
					
				   $findstr = '불량';
				   $pos = stripos($workplacename, $findstr);							   
				   
				   if($pos==0)  {
						// $WJ_HL 계산
						if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
						  $WJ_HL += $widejamb;
						}
						// $WJ 계산
						if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
						  $WJ += $widejamb;
						}
						// $NJ_HL 계산
						if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
						  $NJ_HL += $normaljamb;
						}
						// $NJ 계산
						if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
						  $NJ += $normaljamb;
						}
						// $SJ_HL 계산
						if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
						  $SJ_HL += $smalljamb;
						}
						// $SJ 계산
						if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
						  $SJ += $smalljamb;
						}
				   }

					// 합계표 만들기
					// 막판 산출
					$WJ_amount = str_replace(',', '', $WJ) * intval(str_replace(',', '', $readIni["WJ"]));
					$WJ_HL_amount = str_replace(',', '', $WJ_HL) * intval(str_replace(',', '', $readIni["WJ_HL"]));
					$WJ_total = $WJ_amount + $WJ_HL_amount ;
					$WJ_num = $WJ + $WJ_HL;
					// 막판무 산출
					$NJ_amount = str_replace(',', '', $NJ) * intval(str_replace(',', '', $readIni["NJ"]));
					$NJ_HL_amount = str_replace(',', '', $NJ_HL) * intval(str_replace(',', '', $readIni["NJ_HL"]));
					$NJ_total = $NJ_amount + $NJ_HL_amount ;
					$NJ_num = $NJ + $NJ_HL;
					// 쪽쟘 산출
					$SJ_amount = str_replace(',', '', $SJ) * intval(str_replace(',', '', $readIni["SJ"]));
					$SJ_HL_amount = str_replace(',', '', $SJ_HL) * intval(str_replace(',', '', $readIni["SJ_HL"]));
					$SJ_total = $SJ_amount + $SJ_HL_amount ;
					$SJ_num = $SJ + $SJ_HL;
					
					$counter++;	   
					$total_row++;

				 } 	 
			   } catch (PDOException $Exception) {
				print "오류: ".$Exception->getMessage();
			} 				
				
            $month_sum[$month_count] = $WJ_total + $NJ_total + $SJ_total ;
            $month_count++;       
        }
       
}


// 차트는 바차트로 고정한다.
$chartchoice[1] = 'checked';  // 바차트 선택
$chart_sel = 'bar';  // 바차트 선택

?>


<form id="board_form" name="board_form" method="post" action="output_statis.php?mode=search">
    <input type="hidden" id="chart_sel" name="chart_sel" value="bar">
    <input type="hidden" id="display_sel" name="display_sel" value="bar">
    <input id="item_sel" name="item_sel" type="hidden" value="<?= $item_sel ?>">     

<div class="container">

    <div class="output-filter-container mt-1 mb-2">
        <h4 class="output-page-title"><?= $title_message ?></h4>
    </div>

    <div class="row">
        <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
            <!-- 기간설정 칸 -->
            <?php include getDocumentRoot() . '/setdate.php' ?>
        </div>
    </div>

    <div class="row">
        <div class="d-flex justify-content-center align-items-center mt-2 mb-2">
            <?php
            switch ($item_sel) {
                case "년도비교": $item_sel_choice[0] = 'checked'; break;
                case "월별비교": $item_sel_choice[1] = 'checked'; break;
                case "종류별비교": $item_sel_choice[2] = 'checked'; break;
            }
            ?>

            <label class="custom-radio me-1">
                <input type="radio" class="radio-input" <?= $item_sel_choice[0] ?> name="item_sel" value="년도비교">
                <span class="radio-label">년도비교</span>
            </label>
            <label class="custom-radio me-1">
                <input type="radio" class="radio-input" <?= $item_sel_choice[1] ?> name="item_sel" value="월별비교">
                <span class="radio-label">월별비교</span>
            </label>
            <label class="custom-radio">
                <input type="radio" class="radio-input" <?= $item_sel_choice[2] ?> name="item_sel" value="종류별비교">
                <span class="radio-label">종류별비교</span>
            </label>
        </div>
    </div>
	<!-- JAMB 매출 통합 카드 -->
	<div class="modern-management-card mt-3">
		<div class="modern-dashboard-header">
			<h3 style="color: #000; margin: 0; font-size: 0.9rem; font-weight: 500;">JAMB 매출</h3>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-8">
					<div class="output-chart-container" style="margin-bottom: 0;">
						<div id="myChart" class="chart-container"></div>
					</div>
				</div>
				<div class="col-sm-4">
    <?php
    if ($item_sel == "종류별비교") {		

		echo "<div class='d-flex justify-content-end mb-1'> ";
		echo "<span class='output-section-title text-end'> (단위:천원) </span> </div>";
		echo "<table class='output-table'>";
		echo ' <thead>';
		echo "<tr>";
		echo "<th class='text-center'>해당월</th>";
		echo "<th class='text-center'>매출</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$total_production = 0;

		for ($i = 0; $i < 3; $i++) {
				$total_production += $work_sum[$i];
			echo "<tr>";
			echo "<td class='text-center'>" . $item_arr[$i] . "</td>";
			echo "<td class='text-end'>" .number_format($work_sum[$i]/1000) . " (천원)</td>";
			echo "</tr>";
		}

		// 합계 행 추가
		echo "<tr>";
		echo "<td class='text-center'><strong>합계</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_production/1000) . " (천원)</strong></td>";
		echo "</tr>";

		echo "</tbody>";
		echo "</table>";
		
    }

	if ($item_sel == "월별비교") {		

		echo "<div class='d-flex justify-content-end mb-1'> ";
		echo "<span class='output-section-title text-end'> (단위:천원) </span> </div>";
		echo "<table class='output-table'>";
		echo '<thead>';
		echo "<tr>";
		echo "<th class='text-center'>해당월</th>";
		echo "<th class='text-center'>매출합</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$months = ['red', 'blue', 'orange', 'green', 'purple', 'blue', 'orange', 'green', 'purple', 'red', 'blue', 'brown'];

		$total_monthly_production = 0;

		foreach ($months as $key => $color) {
			$total_monthly_production += $month_sum[$key];

			echo "<tr>";
			echo "<td class='text-center'>" . ($key + 1) . "월</td>";
			echo "<td class='text-end'>" . number_format($month_sum[$key]/1000) . " (천원)</td>";
			echo "</tr>";
		}

		// 합계 행 추가
		echo "<tr>";
		echo "<td class='text-center'><strong>합계</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_monthly_production/1000) . " (천원)</strong></td>";
		echo "</tr>";

		echo "</tbody>";
		echo "</table>";
	}

	if ($item_sel == "년도비교") {		
		$month_fromdate = sprintf("%04d", $month_todate);
		$month_fromdate = intval($month_fromdate) -1 ;
		$month_todate = sprintf("%04d", $month_todate);	

		echo "<div class='d-flex justify-content-end mb-1'> ";
		echo "<span class='output-section-title text-end'> (단위:천원) </span> </div>";
		echo "<table class='output-table'>";
		echo '<thead>';
		echo "<tr>";
		echo "<th class='text-center'>해당월</th>";
		echo "<th class='text-center'>" . $month_fromdate . "년 매출</th>";
		echo "<th class='text-center'>" . $month_todate . "년 매출</th>";

		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";	

		$total_last_year = 0;
		$total_this_year = 0;

		for ($i = 0; $i < 12; $i++) {
			$total_last_year += $year_sum[$i];
			$total_this_year += $year_sum[($i + 12)];

			echo "<tr>";
			echo "<td  class='text-center'>" . ($i + 1) . "월 </td>";
			echo "<td  class='text-end'>" . number_format($year_sum[$i]/1000) . " (천원)</td>";
			echo "<td  class='text-end'>" . number_format($year_sum[($i + 12)]/1000) . " (천원)</td>";
			echo "</tr>";
		}

		// 합계 행 추가
		echo "<tr>";
		echo "<td class='text-center'><strong>합계</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_last_year/1000) . " (천원)</strong></td>";
		echo "<td class='text-end'><strong>" . number_format($total_this_year/1000) . " (천원)</strong></td>";
		echo "</tr>";

		echo "</tbody>";
		echo "</table>";
	}

    ?>
				</div>
			</div>
		</div>

	<!-- 제품별 단가 및 매출 세부 현황 섹션 -->
	</div>

   <!-- 제품별 단가 및 매출 세부 현황 카드 (차트와 동일한 크기) -->
   <div class="output-chart-container mt-3 ">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h5 class="output-section-title mb-0">제품별 단가 및 매출 현황</h5>
			<button id="writeBtn" type="button" class="btn btn-primary btn-sm">
				<ion-icon name="pencil-outline"></ion-icon> 수정
			</button>
		</div>

		<div class="row">
			<!-- 제품별 단가 테이블 -->
			<div class="col-md-5">
				<h6 class="text-muted mb-3" style="font-size: 0.9rem; font-weight: 500;">제품별 단가</h6>
				<div class="table-responsive">
					<table class="output-table table-sm">
						<thead>
							<tr>
								<th rowspan="2" class="align-middle text-center" style="width:30%;">구분</th>
								<th colspan="2" class="align-middle text-center">재질(천원)</th>
							</tr>
							<tr>
								<th class="text-center">H/L</th>
								<th class="text-center">기타</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center">막판유</td>
								<td class="text-end"><?= number_format($readIni['WJ_HL']) ?></td>
								<td class="text-end"><?= number_format($readIni['WJ']) ?></td>
							</tr>
							<tr>
								<td class="text-center">막판무</td>
								<td class="text-end"><?= number_format($readIni['NJ_HL']) ?></td>
								<td class="text-end"><?= number_format($readIni['NJ']) ?></td>
							</tr>
							<tr>
								<td class="text-center">쪽쟘</td>
								<td class="text-end"><?= number_format($readIni['SJ_HL']) ?></td>
								<td class="text-end"><?= number_format($readIni['SJ']) ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- 매출 세부 현황 테이블 -->
			<div class="col-md-7">
				<h6 class="text-muted mb-3" style="font-size: 0.9rem; font-weight: 500;">매출 세부 현황</h6>
				<div class="table-responsive">
					<table class="output-table table-sm">
						<thead>
							<tr>
								<th rowspan="2" class="align-middle text-center" style="width:18%;">구분</th>
								<th rowspan="2" class="align-middle text-center" style="width:15%;">수량</th>
								<th colspan="2" class="align-middle text-center">재질</th>
								<th rowspan="2" class="align-middle text-center">합계(천원)</th>
							</tr>
							<tr>
								<th class="text-center">H/L</th>
								<th class="text-center">기타</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center">막판유</td>
								<td class="text-end"><?= number_format($WJ_num) ?></td>
								<td class="text-end"><?= number_format($WJ_HL_amount) ?></td>
								<td class="text-end"><?= number_format($WJ_amount) ?></td>
								<td class="text-end"><strong><?= number_format($WJ_total/1000) ?> 천원</strong></td>
							</tr>
							<tr>
								<td class="text-center">막판무</td>
								<td class="text-end"><?= number_format($NJ_num) ?></td>
								<td class="text-end"><?= number_format($NJ_HL_amount) ?></td>
								<td class="text-end"><?= number_format($NJ_amount) ?></td>
								<td class="text-end"><strong><?= number_format($NJ_total/1000) ?> 천원</strong></td>
							</tr>
							<tr>
								<td class="text-center">쪽쟘</td>
								<td class="text-end"><?= number_format($SJ_num) ?></td>
								<td class="text-end"><?= number_format($SJ_HL_amount) ?></td>
								<td class="text-end"><?= number_format($SJ_amount) ?></td>
								<td class="text-end"><strong><?= number_format($SJ_total/1000) ?> 천원</strong></td>
							</tr>
							<tr class="table-info">
								<td colspan="4" class="text-center"><strong>합계</strong></td>
								<td class="text-end"><strong style="color: #0369a1;"><?= number_format($total/1000) ?> 천원</strong></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
   </div>
		</div> <!--output-filter-container-->
	   </div>
	</div>
</div> <!--container-->     
	 
 </form> 


<script>
$(document).ready(function() {
    $("#writeBtn").click(function() {
        popupCenter('./estimate.php', '쟘 제품 단가입력', 1050, 600);
    });

    saveLogData('Jamb 매출통계');
});

/* Checkbox change event */
$('input[name="chart_sel"]').each(function() {
    var value = $(this).val();
    var checked = $(this).prop('checked');
    var $label = $(this).next();
    if (checked) {
        $("#display_sel").val(value);
        document.getElementById('board_form').submit();
    }
});

$('input[name="item_sel"]').change(function() {
    // 모든 radio를 순회한다.
    $('input[name="item_sel"]').each(function() {
        var value = $(this).val();
        var checked = $(this).prop('checked');
        var $label = $(this).next();
        if (checked) {
            $("#item_sel").val(value);
            document.getElementById('board_form').submit();
        }
    });
});

// 데이터 준비 (천원 단위로 변환)
var item_arr = <?php echo json_encode(isset($item_arr) ? $item_arr : []); ?>;
var work_sum = <?php echo json_encode(isset($work_sum) && is_array($work_sum) ? array_map(function($x) { return is_numeric($x) ? $x / 1000 : 0; }, $work_sum) : []); ?>;
var month_sum = <?php echo json_encode(isset($month_sum) && is_array($month_sum) ? array_map(function($x) { return is_numeric($x) ? $x / 1000 : 0; }, $month_sum) : []); ?>;
var year_sum = <?php echo json_encode(isset($year_sum) && is_array($year_sum) ? array_map(function($x) { return is_numeric($x) ? $x / 1000 : 0; }, $year_sum) : []); ?>;
var item_type = document.getElementById('item_sel').value;

// 데이터 유효성 검사
if (!item_arr) item_arr = [];
if (!work_sum) work_sum = [];
if (!month_sum) month_sum = [];
if (!year_sum) year_sum = [];

// Highcharts 기본 설정
var chartOptions = {
    chart: {
        type: 'column',
        backgroundColor: 'rgba(255, 255, 255, 0.9)',
        height: 280
    },
    title: {
        text: 'JAMB 매출 현황 (천원)',
        style: { fontSize: '14px', fontWeight: '600', color: '#01579b' }
    },
    xAxis: {
        labels: { style: { fontSize: '10px', color: '#01579b' } }
    },
    yAxis: {
        title: { text: '천원', style: { fontSize: '10px', color: '#01579b' } },
        labels: { style: { fontSize: '10px', color: '#01579b' } }
    },
    tooltip: {
        formatter: function() {
            return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' 천원</b>';
        }
    },
    legend: { enabled: false },
    credits: { enabled: false },
    plotOptions: {
        column: {
            colorByPoint: true
        }
    },
    colors: ['#0288d1', '#0277bd', '#01579b', '#4fc3f7', '#29b6f6', '#03a9f4', '#039be5', '#0288d1']
};

if (item_type == '종류별비교') {
    var categories = [
        item_arr[0] || '항목1',
        item_arr[1] || '항목2',
        item_arr[2] || '항목3'
    ];
    var data = [
        work_sum[0] || 0,
        work_sum[1] || 0,
        work_sum[2] || 0
    ];

    Highcharts.chart('myChart', Object.assign({}, chartOptions, {
        title: { text: 'JAMB 종류별 매출 비교 (천원)', style: { fontSize: '14px', fontWeight: '600', color: '#01579b' } },
        xAxis: {
            categories: categories,
            labels: { style: { fontSize: '10px', color: '#01579b' } }
        },
        series: [{
            name: 'JAMB 매출',
            data: data
        }]
    }));
}

if (item_type == '월별비교') {
    var monthLabels = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];

    // 12개월 데이터 안전하게 처리
    var monthData = [];
    for (var i = 0; i < 12; i++) {
        monthData.push(month_sum[i] || 0);
    }

    Highcharts.chart('myChart', Object.assign({}, chartOptions, {
        title: { text: '월별 매출 현황 (천원)', style: { fontSize: '14px', fontWeight: '600', color: '#01579b' } },
        xAxis: {
            categories: monthLabels,
            labels: { style: { fontSize: '10px', color: '#01579b' } }
        },
        series: [{
            name: 'JAMB 매출',
            data: monthData
        }]
    }));
}

if (item_type == '년도비교') {
    var yearLabels = ['전년1월', '1월', '전년2월', '2월', '전년3월', '3월', '전년4월', '4월', '전년5월', '5월', '전년6월', '6월', '전년7월', '7월', '전년8월', '8월', '전년9월', '9월', '전년10월', '10월', '전년11월', '11월', '전년12월', '12월'];
    var yearData = [];

    // 데이터 유효성 검사 - year_sum이 배열이고 최소 24개 요소가 있는지 확인
    if (!Array.isArray(year_sum)) {
        year_sum = [];
    }

    // 배열 길이를 24로 확장 (12개월 × 2년)
    while (year_sum.length < 24) {
        year_sum.push(0);
    }

    // 년도비교 데이터 재구성 - 안전한 인덱스 접근
    for (var i = 0; i < 12; i++) {
        var prevYearValue = year_sum[i];
        var currentYearValue = year_sum[i + 12];

        // 숫자가 아닌 값이나 null/undefined 처리
        prevYearValue = (typeof prevYearValue === 'number' && !isNaN(prevYearValue)) ? prevYearValue : 0;
        currentYearValue = (typeof currentYearValue === 'number' && !isNaN(currentYearValue)) ? currentYearValue : 0;

        yearData.push(prevYearValue);      // 전년도 데이터
        yearData.push(currentYearValue);   // 올해 데이터
    }

    // 최종 데이터 유효성 검사
    if (yearData.length === 0 || yearData.every(val => val === 0)) {
        console.warn('년도비교 차트: 유효한 데이터가 없습니다.');
        yearData = new Array(24).fill(0); // 빈 데이터로 초기화
    }

    Highcharts.chart('myChart', Object.assign({}, chartOptions, {
        title: { text: '전년 대비 월별 매출 현황 (천원)', style: { fontSize: '14px', fontWeight: '600', color: '#01579b' } },
        xAxis: {
            categories: yearLabels,
            labels: { style: { fontSize: '10px', color: '#01579b' } }
        },
        plotOptions: {
            column: {
                colorByPoint: false
            }
        },
        colors: ['#64748b', '#0288d1'], // 전년도는 회색, 올해는 파란색
        series: [{
            name: '전년 대비 JAMB 매출',
            data: yearData,
            type: 'column'
        }]
    }));
}
</script>

</body>
</html>