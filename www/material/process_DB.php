<?php
/**
 * 실측일 업데이트 처리
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
$num = getRequestValue("num", '');
$measureday = date("Y-m-d");

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 성공 여부 플래그
$success = false;

// 실측일 업데이트
try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE mirae8440.work 
            SET measureday = ? 
            WHERE num = ? 
            LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $measureday, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
    
    $success = true;
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("실측일 업데이트 오류 (num: {$num}): " . $ex->getMessage());
    die("오류: 실측일 업데이트 중 문제가 발생했습니다.");
}

// 리다이렉트 (로컬/서버 환경 모두 지원)
if ($success) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // 안전한 URL 파라미터 생성
    $params = http_build_query([
        'num' => $num
    ], '', '&', PHP_QUERY_RFC3986);
    
    $redirect_url = "{$protocol}://{$host}/p/view.php?{$params}";
    
    header("Location: {$redirect_url}");
    exit;
}
?>