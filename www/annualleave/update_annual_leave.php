<?php
require_once(includePath('session.php'));  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

require_once(includePath('annualleave/load_DB.php'));

header("Content-Type: application/json");

$year = isset($_POST['year']) ? intval($_POST['year']) : date("Y");
$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';

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
