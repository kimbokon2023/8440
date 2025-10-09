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
$num = $_REQUEST["num"] ?? '';
$deadline = date("Y-m-d");

// 로그 변수 (필요시 사용)
// $log = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . " ";
// $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE mirae8440.work SET deadline = ? WHERE num = ? LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $deadline, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);
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

// 각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
    "success" => true,
    "num" => $num,
    "deadline" => $deadline
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>