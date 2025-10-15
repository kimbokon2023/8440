<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 10;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// JSON 헤더 설정
header("Content-Type: application/json");

// 테이블명 설정
$tablename = "cost";

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'num' => $num,
    'page' => $page
);

try {
    // 소프트 삭제 처리
    $pdo->beginTransaction();
    
    $sql = "UPDATE {$DB}.{$tablename} SET is_deleted = ?, deleted_at = NOW() WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, 'Y', PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
    
    $response['success'] = true;
    $response['message'] = '삭제가 완료되었습니다.';
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("원자재 발주 삭제 오류: " . $ex->getMessage());
    $response['success'] = false;
    $response['message'] = '삭제 중 오류가 발생했습니다.';
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>