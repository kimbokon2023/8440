<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/session.php';

 if(!isset($_SESSION["level"]) || $level>=5) {
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");
         exit;
   }  
							                	
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	

$menu=$_REQUEST["menu"]; 
//  print_r ($menu);  

 ?>
  
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?> 

<title> 원자재 현황 </title>

</head>
 
<body >

<?php include "../common/modal.php"; ?>

 
<?  if($navibar!='1' && $menu !== "no") include $_SERVER['DOCUMENT_ROOT'] . '/myheader.php'; ?>       
   
<style>
    	
#showextract {
	display: inline-block;
	position: relative;
}
		
#showextractframe {
    display: none;
    position: absolute;
    width: 800px;
    z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */
    top: 110px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}

</style>

 <?php
 
 
function calculate_saved_weight($row, $thickness)
{
    $saved_weight = 0.0;

    for ($i = 1; $i <= 5; $i++) {
        $saved_weight += ($thickness * $row["used_width_$i"] * $row["used_length_$i"] * 7.93 * (int)$row["used_num_$i"]) / 1000000;
    }

    return sprintf('%0.1f', $saved_weight);
}

function calculate_saved_fee($item, $saved_weight, $readIni)
{
    switch ($item) {
        case 'CR':
            $saved_fee = $saved_weight * conv_num($readIni['CR']);
            break;
        case 'PO':
            $saved_fee = $saved_weight * conv_num($readIni['PO']);
            break;
        case 'EGI':
            $saved_fee = $saved_weight * conv_num($readIni['EGI']);
            break;
        case 'GI':
            $saved_fee = $saved_weight * conv_num($readIni['EGI']);
            break;
        case '304 HL':
            $saved_fee = $saved_weight * conv_num($readIni['HL304']);
            break;
        case '201 HL':
            $saved_fee = $saved_weight * conv_num($readIni['HL201']);
            break;
        case '201 2B MR':
        case '201 MR':
            $saved_fee = $saved_weight * conv_num($readIni['MR201']);
            break;
        case '304 MR':
            $saved_fee = $saved_weight * conv_num($readIni['MR304']);
            break;
        case 'VB':
        case 'V/B':
		case '304 VB':
		case 'VB NSP':
		case '304 V/B':		
		case 'BEAD':
		case '304 BEAD':
		case '304 BA BEAD':
		case '201 MR BEAD':
		case 'NFS-BZ':
		case 'HL Ecolor Brown':
		case 'Bead Ecolor Brown':
		case 'Bead NSP':
		case 'STS 304 HL HTM':
            $saved_fee = $saved_weight * conv_num($readIni['VB304']);
            break;
        case 'i3 304 HL':
            $saved_fee = $saved_weight * conv_num($readIni['I3HL304']);
            break;
        case 'i3 304 MR':
            $saved_fee = $saved_weight * conv_num($readIni['I3MR304']);
            break;
        default:
            $saved_fee = $saved_weight * conv_num($readIni['etcsteel']);
            break;
    }

    return $saved_fee;
}
 
include "_request.php";

$Bigsearch=$_REQUEST["Bigsearch"] ?? '';	 	
       
 // 철판종류에 대한 추출부분  
$sql="select * from mirae8440.steelsource order by sortorder asc, item desc "; 					

$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
$rowNum = $stmh->rowCount();  
$counter=0;
$item_counter=0;
$steelsource_item=array();
$steelsource_spec=array();
$steelsource_take=array();
$spec_arr=array();
$last_item="";
$last_spec="";
$steelsource = array();

 // 철판종류에 대한 추출부분
 $sql = "select * from mirae8440.steelsource order by sortorder asc, item desc, spec asc ";

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $company = trim($row["take"]);
        array_push($steelsource, trim($row["item"]) . '|' . trim($row["spec"]) . '|' . trim($company));
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$steelsource_item = $steelsource;

// var_dump($steelsource_item);

$sum_title = array();
$sum = array();
$yday_sum = array();
$ydaysaved_sum = array();

$title_arr = array();
$titleurl_arr = array();
$num_arr = array();
$full_arr = array();

$yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 86400);
$week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");

if ($week[date('w', strtotime($yesterday))] == '(일)') {
    $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 172800);
}

$sql = "select * from mirae8440.steel where outdate between date('$yesterday') and date('$yesterday') order by outdate";
$tmpsum = 0;

try {
    $stmh = $pdo->query($sql);

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $outdate = $row["outdate"];
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $comment = $row["comment"];
        $which = $row["which"];

        $company = trim($row["company"]);

        $tmp = $item . $spec . $company;
        $tmpsum += (int)$steelnum;
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

if ($tmpsum < 1) {
    $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 259200);
}

// 검색 조건 정리 - 빈 값 체크를 더 엄격하게
$Bigsearch = trim($Bigsearch);
$search = trim($search);

if(!empty($Bigsearch) && !empty($search))
	$sql = "select * from mirae8440.steel where (item = '$Bigsearch') and  (spec = '$search')  ";
else if(!empty($Bigsearch))
	$sql = "select * from mirae8440.steel where  (item = '$Bigsearch') ";
else if(!empty($search))
	$sql = "select * from mirae8440.steel where (spec = '$search') ";
else
	$sql = "SELECT * FROM mirae8440.steel";

// 디버깅용 - SQL 쿼리 확인
echo "<!-- SQL Query: " . $sql . " -->";
echo "<!-- Bigsearch: '" . $Bigsearch . "', search: '" . $search . "' -->";

for ($i = 0; $i < count($steelsource_item); $i++) {
    $sum[$i] = 0;
    $num_arr[$i] = 0;
    $full_arr[$i] = "";
}

try {
    $stmh = $pdo->query($sql);

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $outdate = $row["outdate"];
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = intval($row["steelnum"]);
        $comment = $row["comment"];
        $which = $row["which"];		
        $company = trim($row["company"]);
   
        $temp_arr = explode("*", $spec);
        $saved_weight = calculate_saved_weight($row, $temp_arr[0]);
        $saved_fee = calculate_saved_fee($item, $saved_weight, $readIni);

        $tmp = trim($item) . '|' . trim($spec) . '|' . trim($company);

        for ($i = 0; $i < count($steelsource_item); $i++) {
            if ($which == '1' && $tmp == $steelsource_item[$i]) {
                $sum_title[$i] = $steelsource_item[$i];
                $titleurl_arr[$i] = rawurlencode($steelsource_item[$i]);
                $sum[$i] = intval($sum[$i]) + $steelnum;
                $num_arr[$i]++;
                // $full_arr[$i] = $full_arr[$i] . ',' . $outdate . ' ' . $num . ' ' . $which . ' ' . $item . ' ' . $spec . ' ' . $steelnum;
				// print '입고 : ' . intval($steelnum);
				
            }
            if ($which == '2' && $tmp == $steelsource_item[$i]) {
                $sum_title[$i] = $steelsource_item[$i];
                $titleurl_arr[$i] = rawurlencode($steelsource_item[$i]);
                 $sum[$i] = intval($sum[$i]) - $steelnum;
                $num_arr[$i]++;
				// print '출고 : ' . intval($steelnum);
                // $full_arr[$i] = $full_arr[$i] . ',' . $outdate . ' ' . $num . ' ' . $which . ' ' . $item . ' ' . $spec . ' ' . $steelnum;
            }
            if ($which == '2' && $tmp == $steelsource_item[$i] && $outdate == $yesterday) {
                $yday_sum[$i] += $steelnum;
                $ydaysaved_sum[$i] += $saved_fee;
            }
        }
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// echo '<pre>';
// var_dump($sum_title);
// echo '</pre>';



// 철판 종류 또는 규격 불러오기 함수
function loadSteelData($pdo, $tableName, $columnName) {
    $sql = "SELECT * FROM mirae8440." . $tableName;
    try {
        $stmh = $pdo->query($sql);
        $dataArr = array();
		
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($dataArr, $row[$columnName]);
        }
		
        sort($dataArr);  // 오름차순으로 배열 정렬
        return $dataArr;

    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
        return array();
    }
}

// 철판 종류 불러오기
$steelitem_arr = loadSteelData($pdo, "steelitem", "item");
// 철판 규격 불러오기
$spec_arr = loadSteelData($pdo, "steelspec", "spec");
$item_counter = count($steelitem_arr);
$spec_counter = count($spec_arr);

// print 'sum count : ' . count($sum);
// var_dump($sum);

// print $Bigsearch;
// print $sql;
?>   
     
<!-- menu -->
<form name="board_form" id="board_form"  method="post" action="rawmaterial.php?mode=search&search=<?=$search?>">  
	<input type="hidden" id="username" name="username" value="<?=$user_name?>"   > 					
	<input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?=$BigsearchTag?>"   > 					

	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>"   > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>"   > 	
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>"   > 	
	<input type="hidden" id="page" name="page" value="<?=$page?>"   > 	
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>"   > 	
	<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>"   > 	
	<input type="hidden" id="year" name="year" value="<?=$year?>"   > 	
	<input type="hidden" id="check" name="check" value="<?=$check?>"   > 	
	<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>"   > 	
	<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>"   > 	
	<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>"   > 	
	<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>"   > 	
	<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>"   > 	
	<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>"   > 	
	<input type="hidden" id="stable" name="stable" value="<?=$stable?>"   > 	
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
	<input type="hidden" id="list" name="list" value="<?=$list?>" > 				
	<input type="hidden" id="menu" name="menu" value="<?=$menu?>" > 	


 <div class="container" >
 
  <div class="card">			
  <div class="card-body">    
		<div id="vacancy" style="display:none">  </div> 
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-5 text-center" style="display:none; white-space: normal;  height: 100px; "> 
		</div>     
	</div>		
	<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 
		 <span class="fs-6"> 원자재(철판) 재고 </span>
		 <button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 
	 </div> 
	 <div class="d-flex mb-2 mt-3 justify-content-center align-items-center"> 
		 <span class="badge bg-primary fs-6" id="up_display2">   </span> 
	 </div>	

	<!-- 원자재 가격 정보 카드 -->
	<div class="row mt-3 mb-3">
		<div class="col-md-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header"  onclick="togglePriceCard()">
					<div class="d-flex justify-content-between align-items-center">
						<h6 class="card-title mb-0">
							<i class="bi bi-currency-dollar me-2"></i>원자재 가격 정보
						</h6>
						<button type="button" class="btn btn-dark btn-sm" id="priceCardToggle">
							<i class="bi bi-chevron-down"></i>
						</button>
					</div>
				</div>
				<div class="card-body p-3" id="priceCardBody">
					<?php 
					$labels = [
					  'PO' => 'PO',
					  'CR' => 'CR',
					  'EGI' => 'EGI',
					  '201 HL' => 'HL201',
					  '201 MR' => 'MR201',
					  '304 HL' => 'HL304',
					  '304 MR' => 'MR304',
					  'i3 304 HL' => 'I3HL304',
					  'i3 304 MR' => 'I3MR304',
					  '304 VB' => 'VB304',
					  '304 BEAD' => 'BEAD304',
					  'VB' => 'VB304',
					  'V/B' => 'VB304',
					  'VB NSP' => 'VB304',
					  '304 V/B' => 'VB304',
					  'BEAD' => 'VB304',
					  '304 BA BEAD' => 'VB304',
					  '201 MR BEAD' => 'VB304',
					  'NFS-BZ' => 'VB304',
					  'HL Ecolor Brown' => 'VB304',
					  'Bead Ecolor Brown' => 'VB304',
					  'Bead NSP' => 'VB304',
					  'STS 304 HL HTM' => 'VB304',
					  '304 2B BA' => 'VB304',
					  'MR VB' => 'VB304',					  
					  '2B VB' => 'VB304',
					  '201 2B MR' => 'MR201',
					  'AL' => 'VB304',
					  '특수소재평균' => 'etcsteel'
					];

					// 자재를 그룹별로 분류
					$basic_materials = ['PO', 'CR', 'EGI'];
					$hl_materials = ['201 HL', '304 HL', 'i3 304 HL'];
					$mr_materials = ['201 MR', '304 MR', 'i3 304 MR', '201 2B MR'];
					$vb_bead_materials = ['304 VB', '304 BEAD'];
					$special_materials = ['특수소재평균'];
					?>
					
					<!-- 기본 자재 -->
					<div class="row mb-3">
						<div class="col-12">
							<h6 class="text-muted mb-2">
								<i class="bi bi-circle-fill text-warning me-1"></i>기본 자재
							</h6>
							<div class="row g-2">
								<?php foreach($basic_materials as $material): ?>
								<div class="col-md-4 col-lg-3">
									<div class="card border-0 bg-light h-100">
										<div class="card-body text-center p-2">
											<div class="fw-bold text-primary small"><?= $material ?></div>
											<div class="text-success fw-bold"><?= number_format((float)$readIni[$labels[$material]]) ?>원</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?> 
							</div>
						</div>
					</div>

					<!-- HL 계열 -->
					<div class="row mb-3">
						<div class="col-12">
							<h6 class="text-muted mb-2">
								<i class="bi bi-circle-fill text-info me-1"></i>HL 계열
							</h6>
							<div class="row g-2">
								<?php foreach($hl_materials as $material): ?>
								<div class="col-md-4 col-lg-3">
									<div class="card border-0 bg-light h-100">
										<div class="card-body text-center p-2">
											<div class="fw-bold text-primary small"><?= $material ?></div>
											<div class="text-success fw-bold"><?= number_format((float)$readIni[$labels[$material]]) ?>원</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- MR 계열 -->
					<div class="row mb-3">
						<div class="col-12">
							<h6 class="text-muted mb-2">
								<i class="bi bi-circle-fill text-success me-1"></i>MR 계열
							</h6>
							<div class="row g-2">
								<?php foreach($mr_materials as $material): ?>
								<div class="col-md-4 col-lg-3">
									<div class="card border-0 bg-light h-100">
										<div class="card-body text-center p-2">
											<div class="fw-bold text-primary small"><?= $material ?></div>
											<div class="text-success fw-bold"><?= number_format((float)$readIni[$labels[$material]]) ?>원</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- VB/BEAD 계열 -->
					<div class="row mb-3">
						<div class="col-12">
							<h6 class="text-muted mb-2">
								<i class="bi bi-circle-fill text-danger me-1"></i>VB/BEAD 계열
							</h6>
							<div class="row g-2">
								<?php foreach($vb_bead_materials as $material): ?>
								<div class="col-md-4 col-lg-3">
									<div class="card border-0 bg-light h-100">
										<div class="card-body text-center p-2">
											<div class="fw-bold text-primary small"><?= $material ?></div>
											<div class="text-success fw-bold"><?= number_format((float)$readIni[$labels[$material]]) ?>원</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- 특수 소재 -->
					<div class="row">
						<div class="col-12">
							<h6 class="text-muted mb-2">
								<i class="bi bi-circle-fill text-secondary me-1"></i>특수 소재
							</h6>
							<div class="row g-2">
								<?php foreach($special_materials as $material): ?>
								<div class="col-md-4 col-lg-3">
									<div class="card border-0 bg-light h-100">
										<div class="card-body text-center p-2">
											<div class="fw-bold text-primary small"><?= $material ?></div>
											<div class="text-success fw-bold"><?= number_format((float)$readIni[$labels[$material]]) ?>원</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	    
	<div class="d-flex mb-1 mt-1 justify-content-center align-items-center"> 
  
	
<select name="Bigsearch" id="Bigsearch" class="form-select form-select-sm w-auto">
    <?php
	array_unshift($steelitem_arr, " ");
    for($i=0;$i<count($steelitem_arr);$i++) {
        if($Bigsearch==$steelitem_arr[$i])
            print "<option selected value='" . $steelitem_arr[$i] . "'> " . $steelitem_arr[$i] .   "</option>";
        else
            print "<option value='" . $steelitem_arr[$i] . "'> " . $steelitem_arr[$i] .   "</option>";
    }
    ?>
</select>		   				   
   &nbsp;
<style>
    #search {
        width: 160px;
    }
</style>

<input type="text" name="search" class="form-control w150px mx-1" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="ex)1.2*1219*2438" autocomplete="off" >  
		<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"  > <i class="bi bi-search"></i>  </button>			
				<span id="showextract" class="btn btn-primary btn-sm mx-2" ><i class="bi bi-tools"></i>  </span>	
				<div id="showextractframe" class="card">
					<div class="card-header text-center " style="padding:2px;">
						자주사용하는 사이즈
					</div>					
						<div class="card-body">
							 <div class="p-1 m-1" >
								 <button type="button" class="btn btn-primary btn-sm" onclick="HL304_list_click();" > 304 HL </button>	&nbsp;   
								 <button type="button" class="btn btn-success btn-sm" onclick="MR304_list_click();" > 304 MR </button>	&nbsp;    			 
								 <button type="button" class="btn btn-secondary btn-sm" onclick="VB_list_click();" > VB </button>	&nbsp;    
								 <button type="button" class="btn btn-warning btn-sm" onclick="EGI_list_click();" > EGI </button>	&nbsp;    
								 <button type="button" class="btn btn-danger btn-sm" onclick="PO_list_click();" > PO </button>	&nbsp;    
								 <button type="button" class="btn btn-dark btn-sm" onclick="CR_list_click();" > CR </button>	&nbsp;  
								 <button type="button" class="btn btn-success btn-sm" onclick="MR201_list_click();" > 201 2B MR </button>	&nbsp;  
								   </div>	
								  <div class="p-1 m-1" >
								  <span class="text-success "> <strong> 쟘 1.2T &nbsp; </strong> </span>	
									<button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_1950_list_click();"> 1000x1950  </button> &nbsp;
									<button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_2150_list_click();"> 1000x2150  </button> &nbsp;				   
									<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size42150_list_click();">  4'X2150 </button> &nbsp;
									<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size1000_8_list_click();"> 1000x8' </button> &nbsp; 
								  </div>	
								  <div class="p-1 m-1" >
								 &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								  <button type="button"   class="btn btn-outline-success btn-sm"  onclick="size4_8_list_click();"> 4'x8' </button> &nbsp;
								  <button type="button"  class="btn btn-outline-success btn-sm"  onclick="size1000_2700_list_click();"> 1000x2700 </button> &nbsp;
								   <button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_2700_list_click();"> 4'x2700 </button> &nbsp;
								   <button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_3200_list_click();"> 4'x3200  </button> &nbsp;
								   <button type="button" class="btn btn-outline-success btn-sm"   onclick="size4_4000_list_click();"> 4'x4000 </button> &nbsp;	   			  
								  </div>			  
								  <div class="p-1 m-1" >
								  <span class="text-success "> <strong> 신규쟘 1.5T(HL) &nbsp; </strong> </span>	
								   <button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_2150_list_click();"> 4'x2150 </button> &nbsp;				
								   <button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_8_list_click();"> 4'x8' </button> &nbsp;								  
								  <span class="text-success "> <strong> 신규쟘 2.0T(EGI) &nbsp; </strong> </span>	
								   <button type="button" class="btn btn-outline-success btn-sm" onclick="size20_4_8_list_click();"> 4'x8'  </button> &nbsp;
									
								  </div>			  

								<div class=" p-1 m-1" >	   
								   천장 1.2T(CR)  </button> &nbsp; 
								  <button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1680_list_click();"> 4'x1680 </button> &nbsp;
								  <button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1950_list_click();"> 4'x1950 </button> &nbsp;
								  <button type="button"  class="btn btn-outline-danger btn-sm"  onclick="size12_4_8_list_click();"> 4'x8' </button> &nbsp;
								  </div>			  
								  <div class=" p-1 m-1" >			  				   
								  천장 1.6T(CR)   &nbsp; 	  
								  <button type="button"  class="btn btn-outline-primary btn-sm" onclick="size16_4_1680_list_click();"> 4'x1680 </button> &nbsp;
								  <button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_1950_list_click();"> 4'x1950 </button> &nbsp;
								  <button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_8_list_click();"> 4'x8' </button> &nbsp;		   		   
								  </div>
								  <div class=" p-1 m-1" >	
								   천장 2.3T(PO)  &nbsp; 	  
								   <button type="button" class="btn btn-outline-secondary btn-sm" onclick="size23_4_1680_list_click();"> 4'x1680 </button> &nbsp;
								   <button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_1950_list_click();"> 4'x1950 </button> &nbsp;
								   <button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_8_list_click();"> 4'x8'  </button> &nbsp;					  
								   천장 3.2T(PO)  &nbsp; 	  
								   <button type="button" class="btn btn-outline-secondary btn-sm" onclick="size32_4_1680_list_click();"> 4'x1680 </button> &nbsp;
								   
								  </div>
							   
						</div>
					</div>			   
	     
		
	 <button  type="button" id="showmaterialfeeBtn" class="btn btn-dark btn-sm me-1" > <i class="bi bi-gear"></i>  가격 </button>
		 <button  type="button" id="displayinput" class="btn btn-dark btn-sm me-1"  > <i class="bi bi-list-check"></i> 수불 </button> 
		 <button  type="button" id="displaygraph" class="btn btn-dark btn-sm me-1"  > <i class="bi bi-bar-chart-line"></i> 출고 </button> 
		 <button type="button" class="btn btn-dark btn-sm mx-1" id="csvDownload" >  <ion-icon name="save-outline"></ion-icon>  엑셀CSV  </button> 
    </div>		
	
	<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">   
		 <div id="grid" style="width:1050px;">		  		  
		  </div>
	</div>	
</div>
</div>
</div>
</form>

<script>
function SearchEnter(){
    if(event.keyCode == 13){	    
		document.getElementById('board_form').submit(); 
    }
}

// 콤마 제거 함수
function uncomma(str) { 
    str = String(str); 
    str = str.replace(/,/g, ''); 
    return Number(str.replace(/[^\d]+/g, '')); 
}

// 콤마 추가 함수
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
}

$(document).ready(function(){	

	$("#searchBtn").click(function(){ 			 
		 document.getElementById('board_form').submit();     
	 });

	// 전체조회 클릭시
	
	$("#AllBtn").click(function(){ 	
		  // page 1로 초기화 해야함
		 $("#Bigsearch").val('');
		 $("#search").val('');
		 document.getElementById('board_form').submit();   	 
	 });	

	// 원자재 가격테이블 클릭시
	$("#showmaterialfeeBtn").click(function(){ 			
		 popupCenter('/steel/settings.php'  , '원자재현황보기', 800, 700);	
	});

	// 기간변수불현황
	$("#displayinput").click(function(){         
		 popupCenter('/steel/list_materialinout.php'  , '기간별수불현황 보기', 1100, 900);	
	});

	// 기간변수불현황
	$("#displaygraph").click(function(){         
		 popupCenter('/steel/list_statistics.php'  , '출고통계 보기', 1100, 900);	
	});

	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});

	var numcopy = new Array(); 	 
	var titleurl_arr =  <?php echo json_encode($titleurl_arr); ?> ;	 
	var jsonData  = <?php echo json_encode($steelsource_item);?> ;

	var arr4 = <?php echo json_encode($sum_title);?> ; 
	var arr5 = <?php echo json_encode($sum);?> ; // 누적수량
	var arr7 = <?php echo json_encode($num_arr);?> ; // 기록된 고유번호
	var yday_sum = <?php echo json_encode($yday_sum);?> ;   // 전날 사용자재
	var ydaysaved_sum = <?php echo json_encode($ydaysaved_sum);?> ;   // 전날 절약자재

	// PHP에서 정의된 labels 배열을 JavaScript로 가져옵니다.
	var readIni = <?php echo json_encode($readIni); ?>;
	var labels = <?php echo json_encode($labels); ?>;
	 
	 var tmp;
	 var weight;
	 var price;
	 var total_sum = 0;
	 var yesterday = 0;
	 var yesterdaysaved = 0;
	 var check_minus= 0;
	 var check_str=""; 
	 
	 console.log("jsonData " , jsonData);
	 
	 var splitData = [];

		// 각 문자열을 '|'로 분할하여 새로운 배열을 만듭니다.
		let str ;					
				
		// 2차원 배열을 1차원 배열로 변환합니다.
		let arr1 = [];
		let arr2 = [];
		let arr3 = [];
								
			var keys = Object.keys(jsonData);
			for (var i = 0; i < keys.length; i++) {
			  var key = keys[i];
			  var value = jsonData[key];
			  var arrvalue = value.split('|');		

			 // console.log(keys);
			 // console.log(keys.length);
			 // console.log(arrvalue[0]);
			 // console.log(arrvalue[1]);
			 // console.log(arrvalue[2]);
			  
			  arr1.push(arrvalue[0]);
			  arr2.push(arrvalue[1]);
			  arr3.push(arrvalue[2]);						  
			}					
		
	 var count=0;
	 
	 let counter = 0;	 // numcopy 변수적용

	const COL_COUNT = 10;

	const data = [];
	const columns = [];
	
	let savedDataArray = []; // 이전의 모든 데이터를 저장할 배열을 선언합니다.
	 
for (var i = 0; i < arr1.length; i++) {
    var strArr = arr2[i].split('*');
    var materialType = arr1[i];
    var weight, price;
	
		 if(materialType=='CR'|| materialType=='PO' || materialType=='EGI' || materialType=='HTM')
				weight=Math.floor(7.85 * Number(strArr[0]) * Number(strArr[1]) * Number(strArr[2]) / 1000000);
		 if(materialType=='304 HL'|| materialType=='304 MR' || materialType=='VB'  || materialType=='MR VB TI-BRONZE' || materialType=='BEAD BRONZE'  || materialType=='3S BLACK V/B'  || materialType=='BEAD GOLD'  || materialType=='BEAD BLACK'  || materialType=='V/B'  || materialType=='201 2B MR'  || materialType=='2B VB'  || materialType=='MR BRONZE'  || materialType=='MR VB' || materialType=='i3 304 HL' || materialType=='i3 304 MR')     
				weight=Math.floor(7.93 * Number(strArr[0]) * Number(strArr[1]) * Number(strArr[2])  / 1000000) ;		
		 if(materialType=='AL')
				weight=Math.floor(2.56 * Number(strArr[0]) * Number(strArr[1]) * Number(strArr[2]) / 1000000) ;		
	  price=0;
	  
	 if(arr3[i]==='' || arr3[i]===null) { 
		  // labels 배열에서 직접 가격 키를 가져와서 사용
		  var priceInputId = labels[materialType] || 'etcsteel';
		  price = uncomma(readIni[priceInputId]);
		  
		  // 특별한 경우들 처리 (두께별 추가 가격)
		  if(materialType === '304 HL' && strArr[0]=='0.8') {
			  price = uncomma(readIni['HL304']) + 500;  // 500원 평균 더 비쌈 
		  }
		  if(materialType === '304 MR' && strArr[0]=='0.8') {
			  price = uncomma(readIni['MR304']) + 600;  // 600원 평균 더 비쌈 
		  }
	 }


		// 304HL 1.2*1219*2438은 수량이 0이어도 표시하는 예외 처리
		var isSpecialCase = (materialType === '304 HL' && arr2[i] === '1.2*1219*2438');
		
		if((Number(arr5[i]) != 0 && !isNaN(arr5[i])) || isSpecialCase) { // arr5[i]가 숫자이고 0이 아닌 경우 또는 특별한 경우
			let currentData = materialType + arr2[i] + arr3[i]; // 현재 데이터를 문자열로 만듭니다.

			if(!savedDataArray.includes(currentData)) { // savedDataArray 배열에 currentData가 없는 경우
				// 데이터를 처리합니다.
				const row = { name: count }; 
				tmp = Number(arr5[i]);

				row['col1'] = materialType;
				row['col2'] = arr2[i];
				row['col3'] = arr3[i];
				row['col4'] = tmp;
				row['col5'] = comma(tmp * weight);
				row['col6'] = comma(price);
				row['col7'] = comma(tmp * weight * price);
				row['col8'] = arr7[i];
				
				data.push(row);
				
				numcopy[counter] = titleurl_arr[i];   
				counter++;                
				total_sum += (tmp * weight * price);
				count++;
				
				if(tmp < 0) {             
					check_minus++;
					check_str += materialType + " " + arr2[i] + " 업체명: " + arr3[i] + " " + tmp + " ";
				}
				
				savedDataArray.push(currentData); // 현재 데이터를 savedDataArray 배열에 추가합니다.
			}
		}
		else 
		{
			// table1.setRowData(i-1,[materialType,arr2[i],arr3[i]]);	 
		}		
		
		if(Number(yday_sum[i])!=0 && !isNaN(yday_sum[i])) 	 // 어제 출고된 철판 금액 정리	   
				{
						yesterday += (Number(yday_sum[i])*weight*price);
						// console.log(yday_sum[i]);
				}			
		if(Number(ydaysaved_sum[i])!=0 && !isNaN(ydaysaved_sum[i])) 	 // 어제 잔재로 사용한 철판 금액
				{
						yesterdaysaved += Number(ydaysaved_sum[i]);
					// console.log(ydaysaved_sum[i]);
				}		
		}  // end of for

		// alert(total_sum);
		var strdata ="";
		strdata = total_sum + '\n' + yesterday;
		$("#up_display2").text(comma(total_sum) + '원');
		
		// console.log(total_sum);
		// console.log(yesterday);
		// console.log(yesterdaysaved);
		
		tmp="./saveinfo.php?yesterdaytotal=" + total_sum + "&yesterdayused=" + yesterday + "&yesterdaysaved=" + yesterdaysaved;			
		$("#vacancy").load(tmp);     
		
		if(check_minus>0)  {
					$("#display_board").show();
					$("#display_board").text("재고 마이너스 상태 확인 : " + check_str);
					if($("#username").val()=='김영무') {
						$("#alertmsg").text("재고 마이너스 상태 확인 : " + check_str);
						$('#myModal').modal('show');
						}
					}  

			const grid = new tui.Grid({
				  el: document.getElementById('grid'),
				  data: data,
				  bodyHeight: 600,					  					
				  columns: [ 				   
					{
					  header: '철판종류 구분',
					  name: 'col1',
					  sortingType: 'desc',
					  sortable: true,
					  width:200,		
					  align: 'center'
					},			
					{
					  header: '규격 (두께 x W x H)',
					  name: 'col2',
					  width:140,	
					  align: 'center'
					},
					{
					  header: '사급자재 여부',
					  name: 'col3',
					  sortingType: 'desc',
					  sortable: true,					  
					  width:180,	
					  align: 'center'
					},
					{
					  header: '수량',
					  name: 'col4',
					  width:70,
					  align: 'center'
					},
					{
					  header: '중량(kg)',
					  name: 'col5',
					  width:70,	
					  align: 'center'
					},
					{
					  header: 'kg당 단가',
					  name: 'col6',
					  width:100,		
					  align: 'center'
					},
					{
					  header: '금액',
					  name: 'col7',
					  width:150,
					  align: 'center'
					},
					{
					  header: '기록건수',
					  name: 'col8',
					  width:80,
					  align: 'center'
					}				
				  ],
				columnOptions: {
						resizable: true
					  },
				  rowHeaders: ['rowNum'],
				});	

			// grid 색상등 꾸미기
			var Grid = tui.Grid; // or require('tui-grid')
			Grid.applyTheme('default', {
				selection: {
					background: '#ccc',
					border: '#fdfcfc'
				  },
				  scrollbar: {
					background: '#e6eef5',
					thumb: '#d9d9d9',
					active: '#c1c1c1'
				  },
				  row: {
					hover: {
					  background: '#e6eef5'
					}
				  },
				  cell: {
					normal: {
					  background: '#FFFF',
					  border: '#e6eef5',
					  showVerticalBorder: true
					},
					header: {
					  background: '#e6eef5',
					  border: '#fdfcfc',
					  showVerticalBorder: true
					},
					rowHeader: {
					  border: '#e6eef5',
					  showVerticalBorder: true
					},
					editable: {
					  background: '#fbfbfb'
					},
					selectedHeader: {
					  background: '#e6eef5'
					},
					focused: {
					  border: '#e6eef5'
					},
					disabled: {
					  text: '#e6eef5'
					}
				  }	
			});	
				

	// 더블클릭 이벤트	
	grid.on('dblclick', (e) => {	
		let col1 = grid.getValue(e.rowKey, 'col1');
		let col2 = grid.getValue(e.rowKey, 'col2');
		let col3 = grid.getValue(e.rowKey, 'col3');
		
		// + 기호를 @로 치환
		col1 = (col1 + '').replace(/\+/g, '@');
		col2 = (col2 + '').replace(/\+/g, '@');
		col3 = (col3 + '').replace(/\+/g, '@');
		
		const sendvar = col1 + "|" + col2 + "|" + col3;
		
		var link = 'https://8440.co.kr/steel/part_view.php?arr=' + encodeURIComponent(sendvar);
		popupCenter(link, '원자재 입출고 상세내역', 1000, 900);				   
	});		
	
	
document.getElementById("csvDownload").addEventListener("click", function () {
  // tui.Grid 인스턴스 가져오기
  const gridData = grid.getData(); // grid는 tui.Grid 인스턴스

  // 헤더 생성
  const columns = grid.getColumns();
  const csvRows = [];
  const headerData = columns.map(col => col.header || col.name); // 컬럼 헤더 가져오기
  csvRows.push(headerData.join(","));

  // 데이터 행 추가
  gridData.forEach(row => {
    const rowData = columns.map(col => row[col.name] || ""); // 컬럼 데이터를 순서대로 추가
    csvRows.push(rowData.join(","));
  });

  // CSV 파일 생성
  const csvContent = csvRows.join("\n");
  const blob = new Blob(["\ufeff" + csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.setAttribute("download", "미래기업원자재정보.csv");
  document.body.appendChild(link);
  link.click();
});
	
});

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('원자재 현황조회'); // 다른 페이지에 맞는 menuName을 전달
	
	// 가격 카드 상태 복원
	loadPriceCardState();
});

// 가격 카드 토글 함수
function togglePriceCard() {
	const cardBody = document.getElementById('priceCardBody');
	const toggleButton = document.getElementById('priceCardToggle');
	const toggleIcon = toggleButton.querySelector('i');
	
	if (cardBody.style.display === 'none') {
		cardBody.style.display = 'block';
		toggleIcon.className = 'bi bi-chevron-down';
		savePriceCardState(true);
	} else {
		cardBody.style.display = 'none';
		toggleIcon.className = 'bi bi-chevron-up';
		savePriceCardState(false);
	}
}

// 가격 카드 상태 저장 (쿠키)
function savePriceCardState(isOpen) {
	const expires = new Date();
	expires.setTime(expires.getTime() + (365 * 24 * 60 * 60 * 1000)); // 1년
	document.cookie = `priceCardOpen=${isOpen}; expires=${expires.toUTCString()}; path=/`;
}

// 가격 카드 상태 로드 (쿠키)
function loadPriceCardState() {
	const cookies = document.cookie.split(';');
	let isOpen = true; // 기본값은 열림
	
	for (let cookie of cookies) {
		const [name, value] = cookie.trim().split('=');
		if (name === 'priceCardOpen') {
			isOpen = value === 'true';
			break;
		}
	}
	
	if (!isOpen) {
		document.getElementById('priceCardBody').style.display = 'none';
		const toggleButton = document.getElementById('priceCardToggle');
		const toggleIcon = toggleButton.querySelector('i');
		toggleIcon.className = 'bi bi-chevron-up';
	}
}
</script> 
</body>
</html>