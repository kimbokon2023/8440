<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// JSON 응답 헤더 설정
header("Content-Type: application/json");

// 요청 변수 안전하게 초기화
$num_arr = $_REQUEST["num_arr"] ?? '';
$recordDate_arr = $_REQUEST["recordDate_arr"] ?? '';

// 배열 변수 초기화
$num_tmp = array();
$date_tmp = array();

// 배열 데이터 파싱
if (is_array($num_arr) && isset($num_arr[0]) && is_string($num_arr[0])) {
    $num_tmp = explode(",", $num_arr[0]);
}

if (is_array($recordDate_arr) && isset($recordDate_arr[0]) && is_string($recordDate_arr[0])) {
    $date_tmp = explode(",", $recordDate_arr[0]);
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 청구일 일괄 업데이트
for ($i = 0; $i < count($num_tmp); $i++) {
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE mirae8440.work SET workfeedate = ? WHERE num = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $date_tmp[$i], PDO::PARAM_STR);  // 청구일 기록
        $stmh->bindValue(2, $num_tmp[$i], PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
    } catch (PDOException $Exception) {
        if ($pdo) {
            $pdo->rollBack();
        }
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => '오류: ' . $Exception->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
// 성공 응답 데이터
$data = array(
    "success" => true,
    "num_arr" => $num_tmp,
    "recordDate_arr" => $date_tmp,
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>