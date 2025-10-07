<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
include getDocumentRoot() . '/load_header.php';
?>
<title>Jamb Front Log</title>
<!-- Tabulator CSS -->
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
<!-- Tabulator JS -->
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<!-- Dashboard CSS -->
<link rel="stylesheet" href="/css/dashboard-style.css" type="text/css" />

<style>
/* Light & Subtle Theme - Front Log Specific */
/* ========================================= */

body {
    background: var(--gradient-primary);
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    overflow-x: hidden;
}

.container-fluid {
    max-width: 100%;
    overflow-x: hidden;
    padding: 0.9rem;
}

/* Output Chart Container 스타일 */
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
  /* 화면 가운데 정렬 추가 */
  margin-left: auto;
  margin-right: auto;
  display: block;
}

.output-chart-container:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

/* 섹션 제목 스타일 개선 */
.output-chart-container h5.output-section-title,
.output-section-title {
  color: #334155;
  font-weight: 600;
  font-size: 1.1rem;
  margin: 0;
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

/* Output Table 스타일 */
.output-table {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: none;
  width: 100%;
  border-collapse: collapse;
}

.output-table th {
  background: #f8fafc !important;
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

.output-table tbody tr:hover td {
  background-color: #f1f5f9 !important;
  transition: background-color 0.2s ease;
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

/* PC에서 테이블이 카드 body 폭을 꽉 채우도록 설정 */
@media (min-width: 992px) {
  .table-responsive {
    width: 100%;
  }
  
  .table-responsive .table {
    width: 100% !important;
    margin-bottom: 0;
  }
}
 
/* 합계 행 강조 스타일 */
.output-table .table-info td {
  background-color: #f0f9ff !important;
  font-weight: 500 !important;
}

.output-table .table-info:hover td {
  background-color: #e0f2fe !important;
}

.card {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    box-shadow: var(--dashboard-shadow);
    border-radius: 12px;
}

/* Unified Button Styles */
.btn-primary,
.btn-dark {
    background: var(--dashboard-accent) !important;
    color: var(--btn-text-on-accent) !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 0.4rem 0.8rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1) !important;
}

.btn-primary:hover,
.btn-dark:hover {
    background: var(--dashboard-accent-light) !important;
    color: var(--btn-hover-text) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.15) !important;
    opacity: 0.9 !important;
}

.btn-secondary {
    background: var(--dashboard-secondary) !important;
    color: var(--btn-text-on-light) !important;
    border: 1px solid var(--dashboard-border) !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.btn-secondary:hover {
    background: #c7f0ff !important;
    color: var(--btn-text-on-light) !important;
    transform: translateY(-1px) !important;
}

/* Unified Form Elements */
.form-control,
.form-select {
    border-color: var(--dashboard-border);
    background: white;
    color: var(--dashboard-text);
    border-radius: 4px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
}

/* Tabulator 스타일 - Light & Subtle Theme */
.tabulator {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.tabulator:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

.tabulator .tabulator-header {
    background: #f0fbff;
    color: var(--dashboard-text);
    border: none;
    border-radius: 12px 12px 0 0;
}

.tabulator .tabulator-header .tabulator-col {
    background: transparent;
    border-right: 1px solid var(--dashboard-border);
    color: var(--dashboard-text);
    font-size: 0.8rem;
    font-weight: 500;
}

.tabulator .tabulator-row {
    background: rgba(255, 255, 255, 0.9);
    border-bottom: 1px solid var(--dashboard-border);
}

.tabulator .tabulator-row:hover {
    background-color: var(--dashboard-hover);
    color: var(--dashboard-text);
    transition: background-color 0.2s ease;
}

@media (max-width: 768px) {
    .col-sm-5, .col-sm-7 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }
}
</style>
</head>

<body>
<?php require_once(includePath('myheader.php')); ?>
<?php
ini_set('display_errors','1');
$readIni = array();
$readIni = parse_ini_file("./estimate.ini",false);
include "request.php";
$Twosearchword = array();
if(strpos($search,',') !== false){
  $Twosearchword = explode(',',$search);
}
$sum=array();
$scale = 1000;

if(isset($_REQUEST["findstr"]))
$findstr=$_REQUEST["findstr"];
  else
	  $findstr="전체";

if(isset($_REQUEST["company1"]))
$company1=$_REQUEST["company1"];

if(isset($_REQUEST["company2"]))
$company2=$_REQUEST["company2"];

if(isset($_REQUEST["workersel"]))
$workersel=$_REQUEST["workersel"];

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

switch($cursort)
{
   case 1 :
   $orderby="order by orderday desc, num desc  ";
   break;
   case 2 :
   $orderby="order by orderday asc, num desc  ";
   break;
   case 3 :
   $orderby="order by doneday desc, num desc  ";
   break;
   case 4 :
   $orderby="order by doneday asc, num desc  ";
   break;
   case 5 :
   $orderby="order by measureday desc, num desc  ";
   break;
   case 6 :
   $orderby="order by measureday asc, num desc  ";
   break;
   case 7:
   $orderby="order by endworkday desc, num desc  ";
   break;
   case 8 :
   $orderby="order by endworkday asc, num desc  ";
   break;
   case 9 :
   $orderby="order by workday desc, num desc  ";
   break;
   case 10:
   $orderby="order by workday asc, num desc  ";
   break;
   case 11 :
   $orderby="order by demand desc, num desc";
   break;
   case 12:
   $orderby="order by demand asc, num desc";
   break;
   case 13 :
   $orderby="order by deadline asc, num desc  ";
   break;
   case 14:
   $orderby="order by deadline desc, num desc  ";
   break;
default:
   $orderby="order by orderday desc, num desc ";
break;
}

$measure_check = 1;
$check = 1;

if ($check=='1')
{
	$attached=" and ( workday='0000-00-00' or workday='' or workday IS NULL  ) ";
	$whereattached=" where (workday='0000-00-00' or workday='' or workday IS NULL ) ";
}

$now = date("Y-m-d");

$a= " " . $orderby . " ";
$b=  " " . $orderby;

$search = str_replace(' ', '', $search);

	  if($search=="" && $findstr==="전체"){
				 $sql="select * from mirae8440.work " . $whereattached . $a;
				 $sqlcon = "select * from mirae8440.work "  . $whereattached .  $b;
			   }
		 elseif($search!="" && $find!="all" && $findstr==="전체")
			{
				$sql="select * from mirae8440.work where ($find like '%$search%') " . $attached . $a;
				$sqlcon="select * from mirae8440.work where ($find like '%$search%')  "  . $attached . $b;
			 }
		 elseif($findstr!=="전체") {
		   if($findstr=='덧방')
				   $searchstr = '';
			   else
				   $searchstr = '신규';

			if(count($Twosearchword)>1)
			   {
					  if($check!='1')
						  $attached = '';

						  $search1 = $Twosearchword[0];
						  $sql ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )  or (secondordman like '%$search1%' )  or (chargedman like '%$search1%' ) ";
						  $sql .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))  and ( checkstep='$searchstr' )  " ;
						  $search2 = $Twosearchword[1];
						  $sql .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' )  or (secondordman like '%$search2%' )  or (chargedman like '%$search2%' ) ";
						  $sql .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))   and ( checkstep='$searchstr' )  " . $attached . $a;

						  $sqlcon ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )  or (secondordman like '%$search1%' )  or (chargedman like '%$search1%' ) ";
						  $sqlcon .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))   and ( checkstep='$searchstr' )  " ;
						  $sqlcon .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' )  or (secondordman like '%$search2%' )  or (chargedman like '%$search2%' ) ";
						  $sqlcon .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))   and ( checkstep='$searchstr' )  " . $attached . $b;

				}
			  else {
					  if($check!='1') {
						  $sql ="select * from mirae8440.work where ((replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' ))   and ( checkstep='$searchstr' )   " . $a;

						  $sqlcon ="select * from mirae8440.work where ((replace(workplacename,' ','') like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sqlcon .="or (delicompany like '%$search%' )  or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' ))   and ( checkstep='$searchstr' )   " . $b;
					  }

				 if($check=='1' || $output_check=='1' || $measure_check=='1' || $plan_output_check=='1' || $team_check=='1') {
						  $sql ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  )   and ( checkstep='$searchstr' ) "  . $attached . $a;

						  $sqlcon ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  )   and ( checkstep='$searchstr' ) "  . $attached . $b;
					  }
				}
			  }

		 elseif($search!="" && $find=="all") {
			if(count($Twosearchword)>1)
			   {
					  if($check!='1')
						  $attached = '';

						  $search1 = $Twosearchword[0];
						  $sql ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )  or (secondordman like '%$search1%' )  or (chargedman like '%$search1%' ) ";
						  $sql .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))  " ;
						  $search2 = $Twosearchword[1];
						  $sql .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' )  or (secondordman like '%$search2%' )  or (chargedman like '%$search2%' ) ";
						  $sql .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))  " . $attached . $a;

						  $sqlcon ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )  or (secondordman like '%$search1%' )  or (chargedman like '%$search1%' ) ";
						  $sqlcon .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))  " ;
						  $sqlcon .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' )  or (secondordman like '%$search2%' )  or (chargedman like '%$search2%' ) ";
						  $sqlcon .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))  " . $attached . $b;
				}
			  else {
					  if($check!='1') {
						  $sql ="select * from mirae8440.work where (replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  " . $a;

						  $sqlcon ="select * from mirae8440.work where (replace(workplacename,' ','') like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sqlcon .="or (delicompany like '%$search%' )  or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  " . $b;
					  }

				 if($check=='1' || $output_check=='1' || $measure_check=='1' || $plan_output_check=='1' || $team_check=='1') {
						  $sql ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  ) "  . $attached . $a;

						  $sqlcon ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  ) "  . $attached . $b;
					  }
				}
			  }

$WJ_HL = 0;
$WJ = 0;
$NJ_HL = 0;
$NJ = 0;
$SJ_HL = 0;
$SJ = 0;

try {
  $allstmh = $pdo->query($sqlcon);

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

    $combinedMaterials = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;

    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $WJ_HL += $widejamb;
    }

    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $WJ += $widejamb;
    }

    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $NJ_HL += $normaljamb;
    }

    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $NJ += $normaljamb;
    }

    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $SJ_HL += $smalljamb;
    }

    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $SJ += $smalljamb;
    }
  }
} catch (PDOException $Exception) {
  print "오류: " . $Exception->getMessage();
}

$WJ_amount = str_replace(',', '', $WJ) * intval(str_replace(',', '', $readIni["WJ"]));
$WJ_HL_amount = str_replace(',', '', $WJ_HL) * intval(str_replace(',', '', $readIni["WJ_HL"]));
$WJ_total = $WJ_amount + $WJ_HL_amount ;
$WJ_num = $WJ + $WJ_HL;

$NJ_amount = str_replace(',', '', $NJ) * intval(str_replace(',', '', $readIni["NJ"]));
$NJ_HL_amount = str_replace(',', '', $NJ_HL) * intval(str_replace(',', '', $readIni["NJ_HL"]));
$NJ_total = $NJ_amount + $NJ_HL_amount ;
$NJ_num = $NJ + $NJ_HL;

$SJ_amount = str_replace(',', '', $SJ) * intval(str_replace(',', '', $readIni["SJ"]));
$SJ_HL_amount = str_replace(',', '', $SJ_HL) * intval(str_replace(',', '', $readIni["SJ_HL"]));
$SJ_total = $SJ_amount + $SJ_HL_amount ;
$SJ_num = $SJ + $SJ_HL;

$total = $WJ_total + $NJ_total + $SJ_total ;

try{
	$allstmh = $pdo->query($sqlcon);
	$temp2=$allstmh->rowCount();
	$stmh = $pdo->query($sql);
	$temp1=$stmh->rowCount();

	$total_row = $temp2;
	$total_page = ceil($total_row / $scale);
	$current_page = ceil($page/$page_scale);
} catch (PDOException $Exception) {
	print "오류: ".$Exception->getMessage();
}
?>

<form id="board_form" name="board_form" method="post" action="front_log.php?mode=search">
<div class="container-fluid">
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" >
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" >
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" >
	<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" >
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" >
	<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" >
	<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" >
	<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" >
	<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" >
	<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" >
	<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" >
	<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" >
	<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" >
	<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" >
	<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" >
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" >
	<input type="hidden" id="list" name="list" value="<?=$list?>" >
	<input type="hidden" id="buttonval" name="buttonval" value="<?=$buttonval?>" >
	<input type="hidden" id="findstr" name="findstr" value="<?=$findstr?>" >

	<div id="vacancy" style="display:none">  </div>

<!-- 제품별 단가 및 Front Log 통합 카드 -->
<div class="output-chart-container mt-3 w-75">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h5 class="output-section-title mb-0">제품별 단가 및 Front Log 매출 예측시스템 (단위:천원)</h5>
		<button id="writeBtn" type="button" class="btn btn-primary btn-sm">
			<i class="bi bi-pencil-square"></i> 단가 수정
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

		<!-- Front Log 매출 예측 테이블 -->
		<div class="col-md-7">
			<h6 class="text-muted mb-3" style="font-size: 0.9rem; font-weight: 500;">발주접수 후 미실측, 미청구 </h6>

			<div class="table-responsive">
				<table class="output-table table-sm">
					<thead>
						<tr>
							<th rowspan="2" class="align-middle text-center" style="width:15%;">구분</th>
							<th rowspan="2" class="align-middle text-center">수량</th>
							<th colspan="2" class="align-middle text-center">재질(천원)</th>
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
							<td class="text-center"><?= number_format($WJ_num) ?></td>
							<td class="text-end"><?= number_format($WJ_HL_amount/1000) ?></td>
							<td class="text-end"><?= number_format($WJ_amount/1000) ?></td>
							<td class="text-end fw-bold"><?= number_format($WJ_total/1000) ?></td>
						</tr>
						<tr>
							<td class="text-center">막판무</td>
							<td class="text-center"><?= number_format($NJ_num) ?></td>
							<td class="text-end"><?= number_format($NJ_HL_amount/1000) ?></td>
							<td class="text-end"><?= number_format($NJ_amount/1000) ?></td>
							<td class="text-end fw-bold"><?= number_format($NJ_total/1000) ?></td>
						</tr>
						<tr>
							<td class="text-center">쪽쟘</td>
							<td class="text-center"><?= number_format($SJ_num) ?></td>
							<td class="text-end"><?= number_format($SJ_HL_amount/1000) ?></td>
							<td class="text-end"><?= number_format($SJ_amount/1000) ?></td>
							<td class="text-end fw-bold"><?= number_format($SJ_total/1000) ?></td>
						</tr>
						<tr class="table-info">
							<td colspan="4" class="text-center fw-bold">합계</td>
							<td class="text-end fw-bold text-danger"><?= number_format($total/1000) ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="card output-chart-container mt-1 mb-1 w-75">
	<div class="card-body">
		<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center ">
	<span class="text-decoration-none">   <h5 class="compact-title"> 현장 미실측, 미청구현황 </h5> </span>	 &nbsp;&nbsp;&nbsp;
       <div id="dis_text" class="input-group-text text-primary"> </div>&nbsp; &nbsp;
		  &nbsp;&nbsp;&nbsp;
	<span style="color:grey;"> 공사구분 :  </span> &nbsp;
	   <select name="checkwork" id="checkwork"  class="form-select w-auto mx-1"  style="font-size:1em; height:30px;" >
       <?php
		   $worktype_arr = array();
           array_push($worktype_arr,'전체','덧방','신규');

			for($i=0;$i<count($worktype_arr);$i++) {
			     if($findstr==$worktype_arr[$i])
							print "<option selected value='" . $worktype_arr[$i] . "'> " . $worktype_arr[$i] .   "</option>";
					 else
			   print "<option value='" . $worktype_arr[$i] . "'> " . $worktype_arr[$i] .   "</option>";
			}
	   ?>
	    </select>
		 &nbsp;&nbsp;
	<span style="color:grey;"> 원청 :  </span> &nbsp;
	   <select name="company1" id="company1"  class="form-select w-auto mx-1" style="font-size:1em; height:30px;" >
           <?php
		   $com1_arr = array();
           array_push($com1_arr,' ','OTIS','TKEK');

			for($i=0;$i<count($com1_arr);$i++) {
			     if($company1==$com1_arr[$i])
							print "<option selected value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
					 else
			   print "<option value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
			}
	    ?>
	</select>

  &nbsp;&nbsp;
	 	<span style="color:grey;"> 발주처 :  </span> &nbsp;
		<select name="company2" id="company2" class="form-select w-auto mx-1"  style="font-size:1em; height:30px;" >
           <?php

		   $com1_arr = array();
           array_push($com1_arr,' ','한산','우성','제일특수강');

			for($i=0;$i<count($com1_arr);$i++) {
			     if($company2==$com1_arr[$i])
							print "<option selected value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
					 else
			   print "<option value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
			}
	      	?>
	    </select>
	  &nbsp;&nbsp;
</div>
<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center">
<select name="find" id="find" class="form-select form-select-sm w-auto mx-1" >
    <?php
    $options = [
        'all' => '전체',
        'workplacename' => '현장명',
        'firstord' => '원청',
        'secondord' => '발주처',
        'worker' => '미래시공팀',
        'designer' => '설계자'
    ];

    foreach ($options as $value => $label) {
        $selected = ($find == $value) ? 'selected' : '';
        echo "<option value='$value' $selected>$label</option>";
    }
?>
</select>
&nbsp;

<input type="text" id="search" name="search" class="form-control p-1 me-1" style="width:300px;" placeholder="현장명, 원청, 발주처, 시공팀, HPI, 비고 검색..."  value="<?=$search?>" > &nbsp;

<button id="searchBtn" type="button" class="btn btn-dark  btn-sm"  > <i class="bi bi-search"></i> 검색 </button> &nbsp;
<button id="clearBtn" type="button" class="btn btn-secondary btn-sm"> <i class="bi bi-x-circle"></i> 초기화 </button> &nbsp;
<span id="searchInfo" class="text-muted small"></span> &nbsp;&nbsp;&nbsp;&nbsp;

</div>

<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center">

<div class="table-responsive">
  <div id="myTable"></div>
  <div style="display:none;">
    <table id="dataSource">
      <tbody>

<?php
try {
	$stmh = $pdo->query($sql);
	while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		include '_row.php';

		$widejamb = (int)$widejamb;
		$normaljamb = (int)$normaljamb;
		$smalljamb = (int)$smalljamb;

		$sum[0] += $widejamb;
		$sum[1] += $normaljamb;
		$sum[2] += $smalljamb;
		$sum[3] += $widejamb + $normaljamb + $smalljamb;
	}
	$dis_text = "총 {$sum[3]} (SET),  (막판 : {$sum[0]} ,  막판(無) : {$sum[1]} ,  쪽쟘 : {$sum[2]} )";
} catch (PDOException $Exception) {
	print "오류: ".$Exception->getMessage();
}
?>
      </tbody>
    </table>
  </div>
</div>
</div>

</div>
</div>

<br>
<br>
	<div class="container-fluid">
	<? include '../footer_sub.php'; ?>
	</div>
</div>
</form>
</body>
</html>

<script>
var table;
var workfrontlogpageNumber;

var tableData = [];
<?php
try {
    $stmh = $pdo->query($sql);
    $dataArray = [];
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';

        $dates = ['orderday', 'measureday', 'drawday', 'deadline', 'workday', 'endworkday', 'demand', 'startday', 'testday', 'doneday'];
        foreach ($dates as $date) {
            if (${$date} != "0000-00-00" && ${$date} != "1970-01-01" && ${$date} != "") {
                ${$date} = date("Y-m-d", strtotime(${$date}));
            } else {
                ${$date} = "";
            }
        }

        $materials = trim($material2 . " " . $material1 . " " . $material3 . " " . $material4 . " " . $material5 . " " . $material6);

        $workitem = "";
        if($widejamb != "") $workitem .= "막" . $widejamb . " ";
        if($normaljamb != "") $workitem .= "멍" . $normaljamb . " ";
        if($smalljamb != "") $workitem .= "쪽" . $smalljamb . " ";

        $dataArray[] = [
            'num' => $num,
            'checkstep' => $checkstep ? $checkstep : '',
            'orderday' => $orderday ? $orderday : '',
            'workplacename' => $workplacename ? htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') : '',
            'materials' => $materials ? htmlspecialchars($materials, ENT_QUOTES, 'UTF-8') : '',
            'firstord' => $firstord ? htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') : '',
            'secondord' => $secondord ? htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') : '',
            'worker' => $worker ? htmlspecialchars($worker, ENT_QUOTES, 'UTF-8') : '',
            'workitem' => trim($workitem) ? htmlspecialchars(trim($workitem), ENT_QUOTES, 'UTF-8') : '',
            'hpi' => $hpi ? htmlspecialchars(substr($hpi, 0, 10), ENT_QUOTES, 'UTF-8') : '',
            'memo' => $memo ? htmlspecialchars(substr($memo, 0, 10), ENT_QUOTES, 'UTF-8') : ''
        ];
    }
    echo "tableData = " . json_encode($dataArray, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS) . ";";
} catch (PDOException $Exception) {
    echo "tableData = [];";
}
?>

$(document).ready(function() {
    console.log('tableData length:', tableData.length);
    console.log('tableData sample:', tableData.slice(0, 3));

    table = new Tabulator("#myTable", {
        data: tableData,
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 50,
        paginationSizeSelector: [50, 100, 200, 500, 1000],
        paginationCounter: "rows",
        movableColumns: true,
        resizableRows: false,
        initialSort: [
            {column: "orderday", dir: "desc"}
        ],
        columns: [
            {title: "구분", field: "checkstep", width: 80, hozAlign: "center", headerSort: true,
                formatter: function(cell, formatterParams) {
                    var value = cell.getValue() || "";
                    if(value === '신규') {
                        return '<span class="btn btn-danger btn-sm">' + value + '</span>';
                    }
                    return value;
                }
            },
            {title: "접수", field: "orderday", width: 100, hozAlign: "center", headerSort: true},
            {title: "현장명", field: "workplacename", minWidth: 200, headerSort: true,
                formatter: function(cell, formatterParams) {
                    var value = cell.getValue() || "";
                    var checkstep = cell.getRow().getData().checkstep || "";
                    if(checkstep === '신규') {
                        return "[신규] " + value;
                    }
                    return value;
                }
            },
            {title: "재질(소재)", field: "materials", width: 120, hozAlign: "center", headerSort: true},
            {title: "원청", field: "firstord", width: 100, hozAlign: "center", headerSort: true},
            {title: "발주처", field: "secondord", width: 100, hozAlign: "center", headerSort: true},
            {title: "시공팀", field: "worker", width: 100, hozAlign: "center", headerSort: true},
            {title: "설치수량", field: "workitem", width: 120, hozAlign: "center", headerSort: true},
            {title: "HPI", field: "hpi", width: 100, hozAlign: "center", headerSort: true},
            {title: "비고", field: "memo", width: 100, hozAlign: "left", headerSort: true}
        ],
        rowClick: function(e, row) {
            var data = row.getData();
            var num = data.num;
            if(num) {
                redirectToView(num);
            }
        }
    });

    function performSearch() {
        var searchValue = document.getElementById('search').value.trim();
        var searchInfo = document.getElementById('searchInfo');

        console.log('검색어:', searchValue);

        if (searchValue === '') {
            table.clearFilter();
            searchInfo.textContent = '';
        } else {
            var cleanSearchValue = searchValue.replace(/\s+/g, '');

            table.setFilter(function(data) {
                var workplacename = (data.workplacename || '').replace(/\s+/g, '');
                if (workplacename.toLowerCase().includes(cleanSearchValue.toLowerCase())) {
                    return true;
                }

                var materials = (data.materials || '');
                if (materials.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                var firstord = (data.firstord || '');
                if (firstord.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                var secondord = (data.secondord || '');
                if (secondord.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                var worker = (data.worker || '');
                if (worker.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                var hpi = (data.hpi || '');
                if (hpi.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                var memo = (data.memo || '');
                if (memo.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                var workitem = (data.workitem || '');
                if (workitem.toLowerCase().includes(searchValue.toLowerCase())) {
                    return true;
                }

                return false;
            });

            setTimeout(function() {
                var filteredCount = table.getDataCount("active");
                var totalCount = table.getDataCount();
                searchInfo.textContent = `검색결과: ${filteredCount}건 / 전체: ${totalCount}건`;
            }, 100);
        }
    }

    document.getElementById('searchBtn').addEventListener('click', performSearch);

    document.getElementById('clearBtn').addEventListener('click', function() {
        document.getElementById('search').value = '';
        table.clearFilter();
        document.getElementById('searchInfo').textContent = '';
    });

    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });

    var savedPageNumber = getCookie('workfrontlogpageNumber');
    if (savedPageNumber) {
        table.setPage(parseInt(savedPageNumber));
    }

    table.on("pageLoaded", function(pageno) {
        workfrontlogpageNumber = pageno;
        setCookie('workfrontlogpageNumber', pageno, 10);
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('workfrontlogpageNumber');
    if (savedPageNumber && table) {
        table.setPage(parseInt(savedPageNumber));
    }
}

function SearchEnter(){
    if(event.keyCode == 13){
		$("#page").val('1');
		$("#stable").val('1');
		document.getElementById('board_form').submit();
    }
}

function button_condition(con)
{
	$("#buttonval").val(con);
	$("#sortof").val(con);
	$("#page").val('1');
	$("#stable").val('0');
	$('#board_form').submit();
}

$(document).ready(function(){
$("#writeBtn").click(function(){
    popupCenter('./estimate.php', '쟘 제품 단가입력', 800, 500);
 });

$("#checkwork").change(function(){
	$("#findstr").val($(this).val());
	$('#board_form').submit();
});

  $("#company1").change(function(){
	$('#sortof').val('0');
		 $("#company2").val('');
		 $("#workersel").val('');
         List_name($(this).val())
});

  $("#company2").change(function(){
	$('#sortof').val('0');
		$("#company1").val('');
		 $("#workersel").val('');
         List_name($(this).val())
});

  $("#workersel").change(function(){
		$("#company1").val('');
		$("#company2").val('');
         List_name($(this).val())
});

    $("#without").change(function(){
        if($("#without").is(":checked")){
            $('#check').val('1');
            $('#board_form').submit();
        }else{
            $('#check').val('');
            $('#board_form').submit();
        }
    });
});

function dis_text()
{
	var dis_text = '<?php echo $dis_text; ?>';
	$("#dis_text").text(dis_text);
}
function List_name(worker)
{
	var worker;
	var name='<?php echo $user_name; ?>' ;

	$("#search").val(worker);
	$('#board_form').submit();
}

setTimeout(function() {
 dis_text();
}, 500);

function redirectToView(num) {
	var page = workfrontlogpageNumber || 1;
	var url = "view.php?menu=no&num=" + num;
	customPopup(url, 'jamb 수주내역', 1800, 850);
}
$(document).ready(function(){
	saveLogData('Jamb FrontLog');
});
</script>