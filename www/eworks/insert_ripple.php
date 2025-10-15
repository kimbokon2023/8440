<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$userid = $_SESSION["userid"] ?? '';
$name = $_SESSION["name"] ?? '';
$nick = $_SESSION["nick"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$e_num = $_REQUEST["e_num"] ?? '';
$page = $_REQUEST["page"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$ripple_content = $_REQUEST["ripple_content"] ?? '';

// 데이터베이스 연결
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO {$DB}.eworks_ripple (parent, id, name, nick, content, regist_day) " .
           "VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
    $stmh->bindValue(2, $userid, PDO::PARAM_STR);
    $stmh->bindValue(3, $name, PDO::PARAM_STR);
    $stmh->bindValue(4, $nick, PDO::PARAM_STR);
    $stmh->bindValue(5, $ripple_content, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
    // 성공 응답
    echo json_encode(array(
        'success' => true,
        'message' => '댓글이 등록되었습니다.',
        'ripple_id' => $pdo->lastInsertId()
    ), JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    
    error_log("댓글 등록 오류: " . $ex->getMessage());
    
    http_response_code(500);
    echo json_encode(array(
        'success' => false,
        'error' => '댓글 등록 중 오류가 발생했습니다.',
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
