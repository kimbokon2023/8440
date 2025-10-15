<?php
/**
 * 실측일 업데이트 처리 페이지
 * work 테이블의 measureday 필드를 현재 날짜로 업데이트합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

// 입력 검증
if (empty($num)) {
    error_log("process_DB.php: num parameter is missing");
    die("잘못된 접근입니다. (num 누락)");
}

// 세션 변수 초기화 (로그 기록용)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$DB = $_SESSION["DB"] ?? 'mirae8440';

// 현재 날짜 설정
$measureday = date("Y-m-d");

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE {$DB}.work SET measureday = ? WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $measureday, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
} catch (PDOException $ex) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("DB update error in m/process_DB.php: " . $ex->getMessage());
    die("데이터베이스 업데이트 중 오류가 발생했습니다.");
}

// 동적 리다이렉션
$baseUrl = getBaseUrl();
header("Location: " . $baseUrl . "/p/view.php?num=" . urlencode($num));
exit;
?>
