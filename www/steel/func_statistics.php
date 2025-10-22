<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 철강 통계 함수
 * 
 * 철판 입출고 통계 데이터 조회 및 집계
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$search = $_REQUEST['search'] ?? '';
$Bigsearch = $_REQUEST['Bigsearch'] ?? '';
$separate_date = $_REQUEST['separate_date'] ?? '2';
$display_sel = $_REQUEST['display_sel'] ?? 'doughnut';
$find = $_REQUEST['find'] ?? '전체';
$mode = $_REQUEST['mode'] ?? '';
$fromdate = $_REQUEST['fromdate'] ?? '';
$todate = $_REQUEST['todate'] ?? '';

// 기간 기본값 설정
if ($fromdate === '') {
    $currentYear = date('Y');
    $fromdate = $currentYear . '-01-01';
}
if ($todate === '') {
    $currentYear = date('Y');
    $todate = $currentYear . '-12-31';
}
$Transtodate = date('Y-m-d', strtotime($todate . '+1 days'));
// steelsource 테이블 조회
$sql = "SELECT * FROM " . $DB . ".steelsource";
try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    $counter = 0;
    
    $steelsource_num = [];
    $steelsource_item = [];
    $steelsource_spec = [];
    $steelsource_take = [];
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $counter++;
        $steelsource_num[$counter] = $row['num'] ?? '';
        $steelsource_item[$counter] = $row['item'] ?? '';
        $steelsource_spec[$counter] = $row['spec'] ?? '';
        $steelsource_take[$counter] = $row['take'] ?? '';
    }
} catch (PDOException $Exception) {
    error_log("steelsource 조회 오류: " . $Exception->getMessage());
}

// separate_date에 따라 날짜 컬럼 설정
if ($separate_date === '1') {
    $SettingDate = "outdate";
} else {
    $SettingDate = "indate";
}

// 전체 합계(입고 부분) 배열 초기화
$sum_title = [];
$sum = [];

// steel 테이블 조회 (입고 데이터 집계)
try {
    // 🔒 SQL 인젝션 방지: Prepared Statement 사용
    $sql = "SELECT * FROM " . $DB . ".steel 
            WHERE (outdate BETWEEN DATE(?) AND DATE(?)) 
            AND (which = ?)";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
    $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
    $stmh->bindValue(3, $separate_date, PDO::PARAM_STR);
    $stmh->execute();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row['num'] ?? '';
        $item = $row['item'] ?? '';
        $spec = $row['spec'] ?? '';
        $steelnum = $row['steelnum'] ?? 0;
        $company = $row['company'] ?? '';
        $comment = $row['comment'] ?? '';
        $which = $row['which'] ?? '';
        
        // 비교를 위한 임시 문자열
        $tmp = $item . $spec;
        
        // steelsource 항목들과 비교
        for ($i = 1; $i <= $rowNum; $i++) {
            // steelsource_item + steelsource_spec
            $sum_title[$i] = ($steelsource_item[$i] ?? '') . ($steelsource_spec[$i] ?? '');
            
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
    error_log("steel 테이블 조회 오류: " . $Exception->getMessage());
}

// ------------------------------
// 검색 조건 만들기
// ------------------------------

// 1) 기본 기간 조건
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

// 3) 검색어($search)가 있으면 item, spec에서 검색
if (!empty($search)) {
    $commonWhere .= " AND (item = :Bigsearch OR spec = :search) ";
}

// 최종 SQL 만들기
$sql = "SELECT * FROM " . $DB . ".steel WHERE " . $commonWhere . " ORDER BY num DESC";

// 파라미터 바인딩
$params = [
    ':fromdate' => $fromdate,
    ':todate' => $Transtodate,
];

if (!empty($search)) {
    $params[':Bigsearch'] = $Bigsearch;
    $params[':search'] = $search;
}

// 변수 초기화
$nowday = date("Y-m-d");   // 현재일자 변수지정
$output_item_arr = array();
$output_weight_arr = array();
$input_arr = array();
$temp_arr = array();
$count = 0;
$start_num = 0;  // 초기화

// 철판 종류 또는 규격 불러오기 함수
function loadSteelData($pdo, $DB, $tableName, $columnName) {
    $sql = "SELECT * FROM " . $DB . "." . $tableName;
    try {
        $stmh = $pdo->query($sql);
        $dataArr = array();
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($dataArr, $row[$columnName] ?? '');
        }
        
        sort($dataArr);  // 오름차순으로 배열 정렬
        return $dataArr;
    } catch (PDOException $Exception) {
        error_log("loadSteelData 오류 (" . $tableName . "): " . $Exception->getMessage());
        return array();
    }
}

// 철판 종류 불러오기
$steelitem_arr = loadSteelData($pdo, $DB, "steelitem", "item");
// 철판 규격 불러오기
$spec_arr = loadSteelData($pdo, $DB, "steelspec", "spec");
$item_counter = count($steelitem_arr);
$spec_counter = count($spec_arr);

// 검색조건의 옵션넣기
$findarr = array('전체', '입고', '출고');  


// 테이블 데이터 집계
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute($params);
    
    $tableData = array();  // 최종 테이블로 보여줄 데이터
		  
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $outdate = $row["outdate"] ?? '';
        $indate = $row["indate"] ?? '';
        $outworkplace = $row["outworkplace"] ?? '';
        $item = $row["item"] ?? '';
        $spec = $row["spec"] ?? '';
        $steelnum = $row["steelnum"] ?? 0;
        $company = $row["company"] ?? '';
        $comment = $row["comment"] ?? '';
        $which = $row["which"] ?? '';
        $model = $row["model"] ?? '';
        
        $temp_arr = explode("*", $spec);
        
        // 규격 배열 검증 (3개 요소 필요)
        if (count($temp_arr) >= 3) {
            $output_weight_arr[$count] = floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum) / 1000000);
            
            // $calculatedWeight 는 KG 단위
            $calculatedWeight = floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum) / 1000000);
            
            // "item|spec" 같은 고유 키를 만든다.
            $key = $item . '|' . $spec;
            
            // "item" 기준으로 그룹화하여 누적
            if (!isset($tableData[$item])) {
                $tableData[$item] = array(
                    'item' => $item,
                    'weight' => 0,  // 초기 무게 0
                );
            }
            
            // 해당 철판구분의 무게를 누적
            $tableData[$item]['weight'] += $calculatedWeight;
        }
        
        $count++;
        $start_num--;
    }
} catch (PDOException $Exception) {
    error_log("steel 테이블 집계 오류: " . $Exception->getMessage());
}  


// 무게(weight)를 기준으로 내림차순 정렬
usort($tableData, function ($a, $b) {
    return $b['weight'] <=> $a['weight'];  // 내림차순 정렬
});
?>

<div class="d-flex justify-content-center align-items-center">
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
            echo "<td class='text-center'>" . htmlspecialchars($rowData['item'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td class='text-end'>" . htmlspecialchars($tonValueFormat, ENT_QUOTES, 'UTF-8') . "톤</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
  