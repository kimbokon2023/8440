<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  

$tablename = isset($_REQUEST['tablename']) ? $_REQUEST['tablename'] : '';  
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';  
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : ''; // update_log 추가
$update_log = isset($_REQUEST['update_log']) ? $_REQUEST['update_log'] : ''; // update_log 추가

header("Content-Type: application/json");  

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

if (isset($_REQUEST["checkarr"])) {
    $check_Arr = $_REQUEST["checkarr"];
} else {
    $check_Arr = [];
}

$now = date("Y-m-d");
$data = date("Y-m-d H:i:s") . " - " . $_SESSION["name"];
$update_log = $data . "&#10" . $update_log ;  // 개행문자 Textarea

// 로그 업데이트
try {
    $pdo->beginTransaction();
    $sql = "UPDATE mirae8440.ceiling SET update_log=? WHERE num=? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $update_log, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    print "오류: " . $Exception->getMessage();
}

// 체크박스 값에 따른 컬럼 업데이트
foreach ($check_Arr as $check_value) {
    try {
        $pdo->beginTransaction();
        switch ($check_value) {
            case '1': // 본천장 완료
                $sql = "UPDATE mirae8440.ceiling SET eunsung_laser_date=? WHERE num=? LIMIT 1";
                break;
            case '2': // LC 완료
                $sql = "UPDATE mirae8440.ceiling SET lclaser_date=? WHERE num=? LIMIT 1";
                break;
            case '3': // 인테리어 완료
                $sql = "UPDATE mirae8440.ceiling SET etclaser_date=? WHERE num=? LIMIT 1";
                break;
            default:
                continue 2; // 무효한 값은 건너뜀
        }
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $now, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

// 최종 결과 데이터 반환
$data = [   
 'num' => $num, 
 'check_Arr' => $check_Arr
]; 
 
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
