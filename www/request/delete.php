<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 원자재 구매 요청 삭제 (Soft Delete)
 * 
 * 실제 삭제가 아닌 is_deleted 플래그를 설정하여 논리적 삭제 처리
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 테이블명
$tablename = "eworks";

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;

// 필수 데이터 체크
if (empty($num)) {
    echo json_encode(array(
        "success" => false,
        "message" => "삭제할 데이터 번호가 없습니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}


// Soft Delete 처리
try {
    $pdo->beginTransaction();

    $sql = "update " . $DB . "." . $tablename . " set is_deleted = ? ";
    $sql .= "where num = ? LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, 1, PDO::PARAM_INT);  // is_deleted = 1 (삭제됨)
    $stmh->bindValue(2, $num, PDO::PARAM_INT);

    $stmh->execute();
    $pdo->commit();

    // 성공 응답
    echo json_encode(array(
        "success" => true,
        "num" => $num,
        "page" => $page,
        "message" => "데이터가 성공적으로 삭제되었습니다."
    ), JSON_UNESCAPED_UNICODE);
} catch (PDOException $Exception) {
    $pdo->rollBack();
    
    // 에러 로그 기록
    error_log("데이터 삭제 오류: " . $Exception->getMessage());
    
    // 에러 응답
    echo json_encode(array(
        "success" => false,
        "message" => "데이터 삭제 중 오류가 발생했습니다.",
        "error" => $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}
?>
