<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 알림 설정/해제 처리
 * 
 * choice: 1 = 알림 설정, 2 = 알림 해제
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 변수 초기화
$choice = $_REQUEST["choice"] ?? '';
$num = $_REQUEST["num"] ?? '';

// 데이터 유효성 검사
if (empty($num)) {
    echo json_encode(array(
        "success" => false,
        "message" => "필수 정보가 누락되었습니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 알림 상태 설정
if ($choice == '2') {
    $alerts = 0;  // 알람 해제
} else {
    $alerts = 1;  // 알람 설정
}

try {
    $pdo->beginTransaction();

    $sql = "update " . $DB . ".alert set alert = ? ";
    $sql .= "where num = ? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $alerts, PDO::PARAM_INT);
    $stmh->bindValue(2, $num, PDO::PARAM_INT);

    $stmh->execute();
    $pdo->commit();

    // 성공 응답
    echo json_encode(array(
        "success" => true,
        "message" => $alerts ? "알림이 설정되었습니다." : "알림이 해제되었습니다.",
        "alert_status" => $alerts
    ), JSON_UNESCAPED_UNICODE);
} catch (PDOException $Exception) {
    $pdo->rollBack();

    // 에러 로그 기록
    error_log("알림 설정 오류: " . $Exception->getMessage());

    // 에러 응답
    echo json_encode(array(
        "success" => false,
        "message" => "알림 설정 중 오류가 발생했습니다.",
        "error" => $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}
?>
