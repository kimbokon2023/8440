<?php
/**
 * 청구일 일괄 업데이트 처리
 * 로컬 및 서버 환경 모두 지원
 */

header("Content-Type: application/json; charset=utf-8");

// 변수 초기화 (?? '' 형태)
$num_arr = $_REQUEST["num_arr"] ?? '';
$recordDate_arr = $_REQUEST["recordDate_arr"] ?? '';

// 입력 유효성 검사
if (empty($num_arr) || empty($recordDate_arr)) {
    error_log("saveDemand 필수 입력값 누락");
    echo json_encode([
        'success' => false,
        'message' => '필수 입력값이 누락되었습니다.',
        'num_arr' => [],
        'recordDate_arr' => []
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 배열 파싱
$num_tmp = explode(",", $num_arr[0]);
$date_tmp = explode(",", $recordDate_arr[0]);

// 배열 크기 검증
if (count($num_tmp) !== count($date_tmp)) {
    error_log("saveDemand 배열 크기 불일치: num=" . count($num_tmp) . ", date=" . count($date_tmp));
    echo json_encode([
        'success' => false,
        'message' => '데이터 배열 크기가 일치하지 않습니다.',
        'num_arr' => $num_tmp,
        'recordDate_arr' => $date_tmp
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once("../lib/mydb.php");
$pdo = db_connect();

$success_count = 0;
$error_count = 0;

for ($i = 0; $i < count($num_tmp); $i++) {
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE mirae8440.oem SET demand=? WHERE num=? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $date_tmp[$i], PDO::PARAM_STR);
        $stmh->bindValue(2, $num_tmp[$i], PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
        
        $success_count++;
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("청구일 업데이트 오류 (num: {$num_tmp[$i]}, demand: {$date_tmp[$i]}): " . $ex->getMessage());
        $error_count++;
    }
}

// JSON 응답
$data = [
    'success' => ($error_count === 0),
    'message' => "{$success_count}건 업데이트 완료" . ($error_count > 0 ? ", {$error_count}건 실패" : ""),
    'success_count' => $success_count,
    'error_count' => $error_count,
    'num_arr' => $num_tmp,
    'recordDate_arr' => $date_tmp
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
