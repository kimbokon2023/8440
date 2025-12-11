<?php
/**
 * 게시글 삭제 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 변수 초기화 및 검증
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : '';

// 필수 파라미터 검증
if (empty($num)) {
    error_log("게시글 삭제 실패: num이 비어있음");
    echo json_encode([
        'success' => false,
        'message' => '삭제할 게시글 번호가 지정되지 않았습니다.',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($tablename)) {
    error_log("게시글 삭제 실패: tablename이 비어있음");
    echo json_encode([
        'success' => false,
        'message' => '테이블명이 지정되지 않았습니다.',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    // 1단계: 첨부파일 삭제
    try {
        $sql1 = "DELETE FROM {$DB}.picuploads WHERE parentnum = ? AND tablename = ?";
        $stmh1 = $pdo->prepare($sql1);
        $stmh1->bindValue(1, $num, PDO::PARAM_STR);
        $stmh1->bindValue(2, $tablename, PDO::PARAM_STR);
        $stmh1->execute();
    } catch (Exception $ex) {
        error_log("첨부파일 삭제 오류 (num: {$num}, tablename: {$tablename}): " . $ex->getMessage());
        // 첨부파일 삭제 실패해도 계속 진행 (게시글 삭제는 시도)
    }
    
    // 2단계: 게시글 삭제
    $sql = "DELETE FROM {$DB}.{$tablename} WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
    // 성공 응답
    echo json_encode([
        'success' => true,
        'message' => '게시글이 성공적으로 삭제되었습니다.',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
    
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("게시글 삭제 오류 (num: {$num}, tablename: {$tablename}): " . $ex->getMessage());
    
    // 실패 응답
    echo json_encode([
        'success' => false,
        'message' => '게시글 삭제 중 문제가 발생했습니다.',
        'error' => $ex->getMessage(),
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
