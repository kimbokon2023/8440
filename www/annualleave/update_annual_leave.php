<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

require_once(includePath('annualleave/load_DB.php'));

header("Content-Type: application/json");

// 요청 파라미터 초기화
$year = isset($_POST['year']) ? intval($_POST['year']) : date("Y");
$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';

// load_DB.php에서 정의될 변수들 초기화 (정의되지 않은 경우 대비)
$availableday_arr = $availableday_arr ?? array();
$basic_name_arr = $basic_name_arr ?? array();
$referencedate_arr = $referencedate_arr ?? array();
$al_usedday_arr = $al_usedday_arr ?? array();
$author_arr = $author_arr ?? array();
$al_askdatefrom_arr = $al_askdatefrom_arr ?? array();
$status_arr = $status_arr ?? array();

$total = 0;
$thisyeartotalusedday = 0;
$thisyeartotalremainday = 0;

// 발생일수 계산
for ($i = 0; $i < count($availableday_arr); $i++) {
    if (trim($user_name) == trim($basic_name_arr[$i]) && (trim($referencedate_arr[$i]) == $year)) {
        $total = $availableday_arr[$i];
    }
}

// 사용일 계산
for ($i = 0; $i < count($al_usedday_arr); $i++) {
    if (trim($user_name) == trim($author_arr[$i]) && substr(trim($al_askdatefrom_arr[$i]), 0, 4) == $year && trim($status_arr[$i]) == 'end') {
        $thisyeartotalusedday += $al_usedday_arr[$i];
    }
}

// 잔여일수 계산
$thisyeartotalremainday = $total - $thisyeartotalusedday;

// 결과 반환
echo json_encode([
    'success' => true,
    'total' => $total,
    'usedDays' => $thisyeartotalusedday,
    'remainingDays' => $thisyeartotalremainday
]);
?>

