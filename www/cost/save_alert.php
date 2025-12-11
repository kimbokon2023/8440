<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// JSON 헤더 설정
header('Content-Type: application/json');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$choice = $_REQUEST["choice"] ?? '';
$num = $_REQUEST["num"] ?? '';

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'num' => $num,
    'alert' => 0
);

// 알람 설정/해제 처리
if ($choice == '2') {
    $alerts = 0;  // 알람해제
} else {
    $alerts = 1;  // 알람설정
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE {$DB}.alert SET alert = ? WHERE num = ? LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $alerts, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
    
    // 성공 응답
    $response['success'] = true;
    $response['message'] = $alerts == 1 ? '알람이 설정되었습니다.' : '알람이 해제되었습니다.';
    $response['alert'] = $alerts;
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("알람 설정 오류: " . $ex->getMessage());
    
    $response['success'] = false;
    $response['message'] = '알람 설정 중 오류가 발생했습니다.';
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>