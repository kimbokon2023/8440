<?php
/**
 * 발주서 삭제 처리 API
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    return $default;
}

// 변수 초기화
$mode = getRequestValue("mode", '');
$num = getRequestValue("num", '');
$page = getRequestValue("page", '');

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 응답 데이터 초기화
$data = array(
    'success' => false,
    'num' => $num,
    'message' => ''
);

// 삭제 처리
try {
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM mirae8440.make WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
    // 성공 응답
    $data['success'] = true;
    $data['message'] = '발주서가 성공적으로 삭제되었습니다.';
    
} catch (Exception $ex) {
    // 롤백
    $pdo->rollBack();
    
    // 에러 로깅
    error_log("발주서 삭제 오류 (num: {$num}): " . $ex->getMessage());
    
    // 실패 응답
    $data['success'] = false;
    $data['message'] = '삭제 중 오류가 발생했습니다: ' . $ex->getMessage();
}

// JSON 응답 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>