<?php
/**
 * Fund 레코드 삭제 처리 스크립트
 * fund 테이블의 레코드를 삭제합니다.
 */

 require_once __DIR__ . '/../bootstrap.php';

// JSON 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';

// 변수 초기화
$success = false;
$message = '';

// 입력 검증
if (empty($num)) {
    error_log("fund/delete.php error: No num specified");
    echo json_encode(array(
        'success' => false,
        'message' => '삭제할 레코드 번호가 지정되지 않았습니다.',
        'num' => ''
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM {$DB}.fund WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    // 삭제된 행 수 확인
    $deletedRows = $stmh->rowCount();
    
    $pdo->commit();
    
    $success = true;
    $message = '레코드가 성공적으로 삭제되었습니다.';
    
    if ($deletedRows === 0) {
        $message = '삭제할 레코드를 찾을 수 없습니다.';
    }
    
} catch (Exception $ex) {
    // 트랜잭션 롤백
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    $success = false;
    $message = '데이터 삭제 오류';
    error_log("DB delete error in fund/delete.php: " . $ex->getMessage());
}

// JSON 응답 생성
$data = array(
    'success' => $success,
    'message' => $message,
    'num' => $num,
    'page' => $page
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>