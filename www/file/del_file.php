<?php
/**
 * 파일 삭제 처리 스크립트
 * 업로드된 파일을 물리적으로 삭제하고 DB 레코드도 제거합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// JSON 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 변수 초기화
$savename = $_REQUEST["savename"] ?? '';
$DB = $_SESSION['DB'] ?? 'mirae8440';
$success = false;
$message = '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 입력 검증
    if (empty($savename)) {
        throw new Exception('삭제할 파일명이 지정되지 않았습니다.');
    }
    
    // 파일명 보안 검증 (경로 탐색 공격 방지)
    if (strpos($savename, '..') !== false || strpos($savename, '/') !== false || strpos($savename, '\\') !== false) {
        throw new Exception('잘못된 파일명입니다.');
    }
    
    // 물리적 파일 경로 설정
    $upload_dir = __DIR__ . '/../uploads/';
    $made_name = $upload_dir . $savename;
    
    // 파일 존재 여부 확인 및 삭제
    if (file_exists($made_name)) {
        if (!unlink($made_name)) {
            throw new Exception('파일 삭제에 실패했습니다.');
        }
    } else {
        error_log("File not found for deletion: {$made_name}");
        // 파일이 없어도 DB 레코드는 삭제 시도
    }
    
    // 데이터베이스 트랜잭션 시작
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM {$DB}.fileuploads WHERE savename = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $savename, PDO::PARAM_STR);
    $stmh->execute();
    
    // 삭제된 행 수 확인
    $deletedRows = $stmh->rowCount();
    
    $pdo->commit();
    
    $success = true;
    $message = '파일이 성공적으로 삭제되었습니다.';
    
    if ($deletedRows === 0) {
        $message .= ' (DB 레코드 없음)';
    }
    
} catch (Exception $ex) {
    // 트랜잭션 롤백
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    $success = false;
    $message = $ex->getMessage();
    error_log("File deletion error in del_file.php: " . $ex->getMessage());
}

// JSON 응답 생성
$data = array(
    'success' => $success,
    'message' => $message,
    'savename' => $savename
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>