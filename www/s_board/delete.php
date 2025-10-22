<?php
/**
 * s_board 게시글 삭제 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

// 필수 데이터 체크
if (empty($num) || empty($tablename)) {
    echo json_encode(array(
        "success" => false,
        "message" => "필수 정보가 누락되었습니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}   
   

// 1단계: 첨부파일 삭제
try {
    $pdo->beginTransaction();
    $sql1 = "delete from " . $DB . ".picuploads where parentnum = ? and tablename = ?";
    $stmh1 = $pdo->prepare($sql1);
    $stmh1->bindValue(1, $num, PDO::PARAM_INT);
    $stmh1->bindValue(2, $tablename, PDO::PARAM_STR);
    $stmh1->execute();
    $pdo->commit();
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("첨부파일 삭제 오류: " . $ex->getMessage());
}

// 2단계: 게시글 삭제
try {
    $pdo->beginTransaction();
    $sql = "delete from " . $DB . "." . $tablename . " where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    $pdo->commit();

    // 성공 응답
    echo json_encode(array(
        "success" => true,
        "num" => $num,
        "message" => "게시글이 성공적으로 삭제되었습니다."
    ), JSON_UNESCAPED_UNICODE);
} catch (Exception $ex) {
    $pdo->rollBack();
    
    error_log("게시글 삭제 오류: " . $ex->getMessage());
    
    // 에러 응답
    echo json_encode(array(
        "success" => false,
        "message" => "게시글 삭제 중 오류가 발생했습니다.",
        "error" => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}        
?>