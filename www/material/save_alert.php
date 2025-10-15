<?php
/**
 * 알림 설정/해제 처리
 * 로컬 및 서버 환경 모두 지원
 */

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    return $default;
}

// 변수 초기화
$choice = getRequestValue("choice", '');
$num = 1;  // 알림 번호 (고정)

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 알림 상태 설정
$alerts = ($choice == '2') ? 0 : 1;  // 2: 해제, 기타: 설정

// 성공 여부 플래그
$success = false;

// 알림 상태 업데이트
try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE mirae8440.alert 
            SET alert = ? 
            WHERE num = ? 
            LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $alerts, PDO::PARAM_INT);
    $stmh->bindValue(2, $num, PDO::PARAM_INT);
    
    $stmh->execute();
    $pdo->commit();
    
    $success = true;
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("알림 설정 업데이트 오류 (num: {$num}): " . $ex->getMessage());
    
    // JSON 응답 (AJAX 호출용)
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'message' => '알림 설정 중 오류가 발생했습니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 성공 응답
if ($success) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'message' => ($alerts == 1) ? '알림이 설정되었습니다.' : '알림이 해제되었습니다.',
        'alert_status' => $alerts
    ], JSON_UNESCAPED_UNICODE);
}
?>