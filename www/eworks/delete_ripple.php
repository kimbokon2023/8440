<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$page = $_REQUEST["page"] ?? '';
$ripple_num = $_REQUEST["ripple_num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

// 데이터베이스 연결
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM {$DB}.eworks_ripple WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $ripple_num, PDO::PARAM_INT);
    $stmh->execute();
    
    $pdo->commit();
    
    // 성공 응답
    echo json_encode(array(
        'success' => true,
        'message' => '댓글이 삭제되었습니다.',
        'ripple_num' => $ripple_num
    ), JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    
    error_log("댓글 삭제 오류: " . $ex->getMessage());
    
    http_response_code(500);
    echo json_encode(array(
        'success' => false,
        'error' => '댓글 삭제 중 오류가 발생했습니다.',
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
