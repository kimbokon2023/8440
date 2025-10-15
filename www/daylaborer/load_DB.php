<?php
// 세션 변수 초기화 (부모 파일에서 설정되어 있어야 함)
$user_name = $user_name ?? '';
$DB = $DB ?? 'mirae8440';

// 기본 정보 배열 초기화
$basic_num_arr = array();
$basic_name_arr = array();
$basic_part_arr = array();
$referencedate_arr = array();
$availableday_arr = array();

// 제조 파트 직원 정보 조회
$sql = "SELECT * FROM {$DB}.member WHERE part = '제조' ORDER BY numorder ASC";

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($basic_num_arr, $row["num"]);
        array_push($basic_name_arr, $row["name"]);
        array_push($basic_part_arr, $row["part"]);
        array_push($referencedate_arr, $row["referencedate"]);
        array_push($availableday_arr, $row["availableday"]);
    }
} catch (PDOException $ex) {
    error_log("제조 파트 직원 정보 조회 오류: " . $ex->getMessage());
}

// 현재 일자
$today = date("Y-m-d");

// 연차 신청 데이터 조회
$sql = "SELECT * FROM {$DB}.absent";

$num_arr = array();
$id_arr = array();
$name_arr = array();
$part_arr = array();
$registdate_arr = array();
$item_arr = array();
$askdatefrom_arr = array();
$askdateto_arr = array();
$usedday_arr = array();
$content_arr = array();
$state_arr = array();

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($num_arr, $row["num"]);
        array_push($id_arr, $row["id"]);
        array_push($name_arr, $row["name"]);
        array_push($part_arr, $row["part"]);
        array_push($registdate_arr, $row["registdate"]);
        array_push($item_arr, $row["item"]);
        array_push($askdatefrom_arr, $row["askdatefrom"]);
        array_push($askdateto_arr, $row["askdateto"]);
        array_push($usedday_arr, $row["usedday"]);
        array_push($content_arr, $row["content"]);
        array_push($state_arr, $row["state"]);
    }
} catch (PDOException $ex) {
    error_log("연차 신청 데이터 조회 오류: " . $ex->getMessage());
}

// 직원별 연차 사용 현황 배열
$totalname_arr = array();
$totalused_arr = array();
$totalusedYear_arr = array();

// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기
for ($j = 0; $j < count($basic_name_arr); $j++) {
    array_push($totalname_arr, $basic_name_arr[$j]);
    
    // 사용일 계산 (처리완료일때 가산됨)
    $totalused_arr[$j] = 0;
    
    for ($i = 0; $i < count($num_arr); $i++) {
        if (trim($basic_name_arr[$j]) == trim($name_arr[$i]) && 
            (substr(trim($askdatefrom_arr[$i]), 0, 4) == trim($referencedate_arr[$j])) && 
            trim($state_arr[$i]) == '처리완료') {
            
            $totalused_arr[$j] += (float)$usedday_arr[$i];
            $totalusedYear_arr[$j] = $referencedate_arr[$j];
        }
    }
}

// 금년도 개별 일수 산출
$total = 0;

for ($i = 0; $i < count($availableday_arr); $i++) {
    if (trim($user_name) == trim($basic_name_arr[$i]) && 
        (trim($referencedate_arr[$i]) == date("Y"))) {
        $total = $availableday_arr[$i];
    }
}

// 사용일 계산 (처리완료일때 가산됨)
// 금년도 년차 수량 계산
$thisyeartotalusedday = 0;
$totalusedday = 0;

for ($i = 0; $i < count($usedday_arr); $i++) {
    if (trim($user_name) == trim($name_arr[$i]) && 
        substr(trim($askdatefrom_arr[$i]), 0, 4) == trim(date("Y")) && 
        trim($state_arr[$i]) == '처리완료') {
        
        $thisyeartotalusedday += (float)$usedday_arr[$i];
    }
}

// 전체 사용일 계산 (모든 년도)
for ($i = 0; $i < count($usedday_arr); $i++) {
    if (trim($user_name) == trim($name_arr[$i]) && 
        trim($state_arr[$i]) == '처리완료') {
        
        $totalusedday += (float)$usedday_arr[$i];
    }
}

// 잔여일 산출
$totalremainday = $total - $totalusedday;
$thisyeartotalremainday = $total - $thisyeartotalusedday;

?>