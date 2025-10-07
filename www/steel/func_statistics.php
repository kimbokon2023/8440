<?php
require_once(includePath('session.php'));

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
	
	$tableData = array();  // 최종 테이블로 보여줄 데이터			
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {		   	   

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

			// $calculatedWeight 는 KG 단위
			// 필요하면 $calculatedWeight / 1000 해서 톤(ton) 단위로 바로 누적해도 됨
			$calculatedWeight = floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum) / 1000000);

			// "item|spec" 같은 고유 키를 만든다.
			$key = $item . '|' . $spec;
			
			// "item" 기준으로 그룹화하여 누적
			if (!isset($tableData[$item])) {
				$tableData[$item] = array(
					'item'   => $item,
					'weight' => 0,  // 초기 무게 0
				);
			}

			// 해당 철판구분의 무게를 누적
			$tableData[$item]['weight'] += $calculatedWeight;
			$count++;
			$start_num--;  
	 } 
	} catch (PDOException $Exception) {
	print "오류: ".$Exception->getMessage();
	}  
	
// 무게(weight)를 기준으로 내림차순 정렬
usort($tableData, function ($a, $b) {
    return $b['weight'] <=> $a['weight'];  // 내림차순 정렬
});	
  
?>

<div class="d-flex justify-content-center align-items-center ">	   
<table class="table table-bordered table-hover table-sm mt-3">
    <thead class="table-secondary">
        <tr>
            <th class="text-center">철판구분</th>
            <th class="text-center">톤수</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // 정렬된 데이터 출력 (0.1톤 이하 제외)
    foreach ($tableData as $rowData) {
        $tonValue = $rowData['weight'] / 1000;  // KG -> 톤 변환

        // 0.1톤(100kg) 이하인 경우 출력하지 않음
        if ($tonValue < 0.1) {
            continue;
        }

        $tonValueFormat = number_format($tonValue, 2); // 소수점 2자리

        echo "<tr>";
        echo "<td class='text-center'>" . htmlspecialchars($rowData['item']) . "</td>";
        echo "<td class='text-end'>" . $tonValueFormat . "톤</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>	
</div>  