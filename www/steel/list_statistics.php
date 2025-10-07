<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

 if(!isset($_SESSION["level"]) || $level>=5) {
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");
         exit;
   }  
$title_message = "원자재 입출고 차트";
?>

<?php include getDocumentRoot() . '/load_header.php' ?>   

<title> <?=$title_message?> </title> 
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
</head>

<body>

<?php

// 파라미터
$search         = $_REQUEST['search']         ?? '';
$Bigsearch      = $_REQUEST['Bigsearch']      ?? '';
$separate_date  = $_REQUEST['separate_date']  ?? '2'; 
$display_sel    = $_REQUEST['display_sel']    ?? 'doughnut';
$find           = $_REQUEST['find']           ?? '전체';
$mode           = $_REQUEST['mode']           ?? '';
$fromdate       = $_REQUEST['fromdate']       ?? '';
$todate         = $_REQUEST['todate']         ?? '';

// 위 $separate_date 라디오 버튼(입고일/출고일)로 설정하되,
// find와는 별개로 쓰거나, 필요 없으면 제거하셔도 됩니다.

// 기간 기본값 설정
if ($fromdate === '') {
    $currentYear = date('Y');
    $fromdate    = $currentYear . '-01-01';
}
if ($todate === '') {
    $currentYear   = date('Y');
    $todate        = $currentYear . '-12-31';
}
$Transtodate   = date('Y-m-d', strtotime($todate . '+1 days'));

// DB 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
// steelsource 테이블 조회
$sql = "SELECT * FROM mirae8440.steelsource";
try {
    $stmh     = $pdo->query($sql);
    $rowNum   = $stmh->rowCount();
    $counter  = 0;
    
    $steelsource_num  = [];
    $steelsource_item = [];
    $steelsource_spec = [];
    $steelsource_take = [];
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $counter++;
        $steelsource_num[$counter]  = $row['num'];
        $steelsource_item[$counter] = $row['item'];
        $steelsource_spec[$counter] = $row['spec'];
        $steelsource_take[$counter] = $row['take'];
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// separate_date에 따라 날짜 컬럼 설정
if ($separate_date === '1') {
    $SettingDate = "outdate";
} else {
    $SettingDate = "indate";
}

// 조회 조건(where) 구문
$common = " WHERE (outdate BETWEEN DATE('$fromdate') AND DATE('$Transtodate')) 
            AND (which = '$separate_date')";

// 전체 합계(입고 부분) 배열 초기화
$sum_title = [];
$sum       = [];

// steel 테이블 조회
$sql = "SELECT * FROM mirae8440.steel " . $common;
try {
    $stmh = $pdo->query($sql);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num      = $row['num'];
        $item     = $row['item'];
        $spec     = $row['spec'];
        $steelnum = $row['steelnum'];
        $company  = $row['company'];
        $comment  = $row['comment'];
        $which    = $row['which'];

        // 비교를 위한 임시 문자열
        $tmp = $item . $spec;

        // steelsource 항목들과 비교
        for ($i = 1; $i <= $rowNum; $i++) {
            // steelsource_item + steelsource_spec
            $sum_title[$i] = $steelsource_item[$i] . $steelsource_spec[$i];

            // 입고(1) 이면서 동일 품목/규격일 경우 합산
            if ($which === '1' && $tmp === $sum_title[$i]) {
                // null(정의 전)인 경우 0으로 초기화
                if (!isset($sum[$i])) {
                    $sum[$i] = 0;
                }
                $sum[$i] += (int)$steelnum;
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}


// ------------------------------
// 검색 조건 만들기
// ------------------------------

// 1) 기본 기간 조건
//    outdate를 기준으로 검색하든, 별도로 $SettingDate 값을 써서 검색하든
//    프로젝트 상황에 맞춰 조정하세요.
$commonWhere = " (outdate BETWEEN DATE(:fromdate) AND DATE(:todate)) ";

// 2) find(전체/입고/출고)에 따른 조건 분기
if ($find === '전체') {
    // 입고 + 출고 모두
    $commonWhere .= " AND (which = '1' OR which = '2') ";
} elseif ($find === '입고') {
    // which=1인 데이터만
    $commonWhere .= " AND which = '1' ";
} elseif ($find === '출고') {
    // which=2인 데이터만
    $commonWhere .= " AND which = '2' ";
}

// 3) 검색어($search)가 있으면 item, spec, company, model, comment에서 LIKE 검색
//    필요하면 outdate, outworkplace 등 다른 컬럼도 추가하세요.
if (!empty($search)) {
    $commonWhere .= " AND (item =:Bigsearch 
                       OR spec =:search ) ";
}

// ------------------------------
// 최종 SQL 만들기
// ------------------------------
$sql = "SELECT * FROM mirae8440.steel WHERE " . $commonWhere . " ORDER BY num DESC";

// 파라미터 바인딩
$params = [
    ':fromdate' => $fromdate,
    ':todate'   => $Transtodate,
];

if (!empty($search)) {
    $params[':Bigsearch'] = $Bigsearch ;
    $params[':search'] = $search ;
}
// print_r($sql);
// print_r('param : ' . $params);
		            
$nowday=date("Y-m-d");   // 현재일자 변수지정   
	
$output_item_arr = array();	
$output_weight_arr = array();	
$input_arr = array();	
$temp_arr = array();	
$count=0;


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

// 검색조건의 옵션넣기
$findarr=array('전체','입고','출고');  

try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute($params);
	
?>
		 
<form name="board_form" id="board_form"  method="post" action="list_statistics.php?mode=search&search=<?=$search?>">        

<div class="container-fluid">
<div class="card">
<div class="card-body">  
 	<div class="d-flex mb-3 mt-4 justify-content-center align-items-center"> 		 
		<H5>
			 <?=$titlemessage?>
		</H5>		 
	</div>		
	<div class="d-flex mt-3 mb-2 justify-content-center align-items-center">           
		  <h3 class="justify-content-center" > 원자재 입출고 차트보기 </h3> 		  
	</div>	
		  
	<div class="d-flex mt-2 mb-2 justify-content-center align-items-center">   
    ▷ <?= $total_row ?> &nbsp;&nbsp;
	
	 <?php
	    if($separate_date=="1") {
			 ?>
			&nbsp; 입고일 <input type="radio" checked name="separate_date" value="1">
			&nbsp; 출고일 <input type="radio" name="separate_date" value="2">
			<?php
             		}    ?>	 
			 <?php
	    if($separate_date=="2") {
			 ?>
			&nbsp; 입고일 <input type="radio"  name="separate_date" value="1">
			&nbsp; 출고일 <input type="radio" checked name="separate_date" value="2">
			<?php
             		}    ?>	 	
	</div>
	<div class="d-flex mt-1 mb-1 justify-content-center align-items-center">   
		<!-- 기간부터 검색까지 연결 묶음 start -->								
			<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 	
			<div id="showframe" class="card">
				<div class="card-header " style="padding:2px;">
					<div class="d-flex justify-content-center align-items-center">  
						기간 설정
					</div>
				</div>
				<div class="card-body">
					<div class="d-flex justify-content-center align-items-center">  	
						<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
						<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange"   onclick='pre_year()' > 전년도 </button>  
						<button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='three_month_ago()' > M-3월 </button>
						<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='prepre_month()' > 전전월 </button>	
						<button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='pre_month()' > 전월 </button> 						
						<button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange "  onclick='this_today()' > 오늘 </button>
						<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_month()' > 당월 </button>
						<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_year()' > 당해년도 </button> 
					</div>
				</div>
			</div>		
			   <input type="date" id="fromdate" name="fromdate" size="12" class="form-control"   style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate" size="12" class="form-control"   style="width:100px;" value="<?=$todate?>" placeholder="기간 끝">  &nbsp;     </span> 
			   &nbsp;&nbsp;			 
				<select name="find" id="find" class="form-select form-select-sm w-auto">
				   <?php			   
					   for($i=0;$i<count($findarr);$i++) {
							 if($find==$findarr[$i]) 
										print "<option selected value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
								 else   
						   print "<option value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
					   } 		   
					?>	  
				</select>
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
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control mx-1" style="width:150px;" > 
				<button class="btnClear"></button>
			</div>	     
		   <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-4"  > <i class="bi bi-search"></i>  </button>					
				&nbsp;				
			<span id="showextract" class="btn btn-primary btn-sm " > <i class="bi bi-tools"></i> </span>	&nbsp; 
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
    </div>		
	
  <div class="card mt-2 mb-2">
<div class="card-body">  
	 <?php
		$tableData = array();  // 최종 테이블로 보여줄 데이터			
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			   
			   // echo '<pre>';
			   // print_r($row);
			   // echo '</pre>';			   

              $num=$row["num"];
 			  $outdate=$row["outdate"];			  
			  
			  $indate=$row["indate"];
			  $outworkplace=$row["outworkplace"];
			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  $model=$row["model"];	 	
			  $temp_arr = explode("*", $spec);
			  
			//  print $temp_arr[0];			  
			  $output_weight_arr[$count]=floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum)/1000000) ;
			  
				switch ($item) {
					case   "304 HL"     :   $output_item_arr[0] += $output_weight_arr[$count]; break;
					case   "304 MR"     :   $output_item_arr[1] += $output_weight_arr[$count]; break;	

					case   "PO"     :   $output_item_arr[3] += $output_weight_arr[$count]; break;	
					case   "EGI"     :   $output_item_arr[4] += $output_weight_arr[$count]; break;
					case   "CR"     :   $output_item_arr[5] += $output_weight_arr[$count]; break;									
					default:  $output_item_arr[2] += $output_weight_arr[$count];break;	
				}
				// $calculatedWeight 는 KG 단위
				// 필요하면 $calculatedWeight / 1000 해서 톤(ton) 단위로 바로 누적해도 됨
				$calculatedWeight = floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum) / 1000000);

				// "item|spec" 같은 고유 키를 만든다.
				$key = $item . '|' . $spec;
				
				// 아직 $tableData에 키가 없으면 새로 만든다.
				if (!isset($tableData[$key])) {
					$tableData[$key] = array(
						'item'   => $item,
						'spec'   => $spec,
						'weight' => 0,  // 누적할 것이므로 0으로 초기화
					);
				}
				
				// 해당 항목에 무게를 누적
				$tableData[$key]['weight'] += $calculatedWeight;
				$count++;
				$start_num--;  
		 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
  print " <div class='d-flex mt-2 mb-3 justify-content-center align-items-center mt-1 mb-1'> ";
  print " <h6> <span style='color:red' > 304 HL </span>&nbsp; " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
  print " <span style='color:blue' > 304 MR </span>&nbsp; " . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
  print " <span style='color:orange' > 기타SUS </span>&nbsp; " . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
  print " <span style='color:green' > PO </span>&nbsp; " . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;   " ; 
  print " <span style='color:purple' > EGI </span>&nbsp; " . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
  print " <span style='color:brown' > CR </span> &nbsp;" . number_format($output_item_arr[5]) . "KG </h6>  </div>  " ; 

		switch ($display_sel) {
			case   "doughnut"     :   $chartchoice[0]='checked'; break;
			case   "pie"     :   $chartchoice[1]='checked'; break;
			case   "bar"     :   $chartchoice[2]='checked'; break;
			case   "line"     :   $chartchoice[3]='checked'; break;
			case   "radar"     :   $chartchoice[4]='checked'; break;
			case   "polarArea"     :   $chartchoice[5]='checked'; break;
		}
 ?>
  <div class="d-flex justify-content-center align-items-center mt-2 mb-2">	   
	   <input id="display_sel" name="display_sel" type='hidden' value='<?=$display_sel?>' >
				&nbsp; 도넛 <input type="radio" <?=$chartchoice[0]?> name="chart_sel" value="doughnut">
				&nbsp; 파이 <input type="radio" <?=$chartchoice[1]?> name="chart_sel" value="pie">
				&nbsp; 바 <input type="radio" <?=$chartchoice[2]?> name="chart_sel" value="bar">
				&nbsp; 라인 <input type="radio" <?=$chartchoice[3]?> name="chart_sel" value="line">
				&nbsp; 레이더 <input type="radio" <?=$chartchoice[4]?> name="chart_sel" value="radar">
				&nbsp; Polar Area <input type="radio" <?=$chartchoice[5]?> name="chart_sel" value="polarArea"> 
	</div>
	<div class="d-flex justify-content-center align-items-center">		
		<canvas id="myChart" width="800" height="500"></canvas>
	</div>   

<?php
// 테이블 출력 (while 루프 이후)
?>
<div class="d-flex justify-content-center align-items-center mt-2 mb-2">	   
<table class="table table-bordered table-hover table-sm mt-3 w-50">
    <thead class="table-secondary">
        <tr>
            <th class="text-center" >철판구분</th>
            <th class="text-center" >사이즈</th>
            <th class="text-center" >톤수</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // $tableData를 순회하며 tr 생성
    foreach ($tableData as $rowData) {
        // $rowData['weight']는 KG 단위이므로, 톤(ton)으로 보이려면 /1000
        $tonValue = $rowData['weight']/1000 ;
        // 소수점 2자리까지만 표시 (필요에 따라 다르게 가능)
        $tonValueFormat = number_format($tonValue, 2);

        echo "<tr>";
        echo "<td class='text-center'>" . htmlspecialchars($rowData['item']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($rowData['spec']) . "</td>";
        echo "<td class='text-end'>" . $tonValueFormat . "톤</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>	
	 </div>       
	 </div>       
   </div>
   </div>
   </div>
   </div> <!-- end of content -->      
</form>
   
<script>
$(document).ready(function() { 
	$("input:radio[name=separate_date]").click(function() { 
		document.getElementById('board_form').submit(); 	
	}) 
});

$(document).ready(function() {

    // 쿠키에서 dateRange 값을 읽어와 셀렉트 박스에 반영
    var savedDateRange = getCookie('dateRange');
    if (savedDateRange) {
        $('#dateRange').val(savedDateRange);
    }

    // dateRange 셀렉트 박스 변경 이벤트 처리
    $('#dateRange').on('change', function() {
        var selectedRange = $(this).val();
        var currentDate = new Date(); // 현재 날짜
        var fromDate, toDate;

        switch(selectedRange) {
            case '최근3개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 3));
                break;
            case '최근6개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 6));
                break;
            case '최근1년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;
            case '최근2년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 2));
                break;
            case '직접설정':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;   
            case '전체':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 20));
                break;            
            default:
                // 기본 값 또는 예외 처리
                break;
        }

        // 날짜 형식을 YYYY-MM-DD로 변환
        toDate = formatDate(new Date()); // 오늘 날짜
        fromDate = formatDate(fromDate); // 계산된 시작 날짜

        // input 필드 값 설정
        $('#fromdate').val(fromDate);
        $('#todate').val(toDate);
		
		var selectedDateRange = $(this).val();
       // 쿠키에 저장된 값과 현재 선택된 값이 다른 경우에만 페이지 새로고침
        if (savedDateRange !== selectedDateRange) {
            setCookie('dateRange', selectedDateRange, 30); // 쿠키에 dateRange 저장
			document.getElementById('board_form').submit();      
        }		
		
		
    });
});

/* Checkbox change event */
$('input[name="chart_sel"]').change(function() {
    // 모든 radio를 순회한다.
    $('input[name="chart_sel"]').each(function() {
        var value = $(this).val();              // value
        var checked = $(this).prop('checked');  // jQuery 1.6 이상 (jQuery 1.6 미만에는 prop()가 없음, checked, selected, disabled는 꼭 prop()를 써야함)
        // var checked = $(this).attr('checked');   // jQuery 1.6 미만 (jQuery 1.6 이상에서는 checked, undefined로 return됨)
        // var checked = $(this).is('checked');
        var $label = $(this).next();
 
        if(checked)  {
           $("#display_sel").val(value);
	       document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
		}

    });
});

    var arr1 = <?php echo json_encode($output_item_arr);?> ;
	var ctx = document.getElementById('myChart');
    var chart_type = document.getElementById('display_sel').value;

	var myChart = new Chart(ctx, {
		type: chart_type,
		data: {
			labels: ['304 HL', '304 MR', '기타SUS', 'PO','EGI','CR'],
			datasets: [{
				label: '#주요 원자재 입/출고현황 단위(KG) ',
				data: [arr1[0], arr1[1], arr1[2],arr1[3], arr1[4], arr1[5] ],
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
				],
				borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1
			}]
		},
		options: {
			responsive: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			},
		}
	});

function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

  </script>

  </body>

  </html>