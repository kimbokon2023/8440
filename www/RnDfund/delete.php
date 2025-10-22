<?php
/**
 * RnDfund 삭제 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

// 필수 변수 검증
if (empty($num) || empty($tablename)) {
    echo json_encode(array(
        "success" => false,
        "message" => "필수 파라미터가 누락되었습니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}   
   
try {
    $pdo->beginTransaction();
    $sql = "delete from " . $DB . "." . $tablename . " where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    error_log("삭제 오류: " . $Exception->getMessage());

    echo json_encode(array(
        "success" => false,
        "message" => "삭제 중 오류가 발생했습니다.",
        "error" => $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 성공 응답
$data = array(
    'success' => true,
    'num' => $num,
    'message' => '성공적으로 삭제되었습니다.'
);

echo json_encode($data, JSON_UNESCAPED_UNICODE);     
?>