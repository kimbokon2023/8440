<?php
/**
 * HRboard 삭제 처리 페이지
 * HRboard 게시글과 첨부파일을 삭제합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
require_once getDocumentRoot() . '/session.php';

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

// 변수 초기화
$success = false;
$message = '';
$deletedPictures = 0;
$deletedRecord = 0;

// 입력 검증
if (empty($num)) {
    echo json_encode([
        'success' => false,
        'message' => '잘못된 접근입니다. (num 누락)',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($tablename)) {
    echo json_encode([
        'success' => false,
        'message' => '잘못된 접근입니다. (tablename 누락)',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    // 1단계: 첨부파일 삭제
    try {
        $sql1 = "DELETE FROM {$DB}.picuploads WHERE parentnum = ? AND tablename = ?";
        $stmh1 = $pdo->prepare($sql1);
        $stmh1->bindValue(1, $num, PDO::PARAM_STR);
        $stmh1->bindValue(2, $tablename, PDO::PARAM_STR);
        $stmh1->execute();
        
        $deletedPictures = $stmh1->rowCount();
        
    } catch (Exception $ex) {
        error_log("Picture delete error in HRboard/delete.php: " . $ex->getMessage());
        // 첨부파일 삭제 실패는 계속 진행 (본문 삭제 우선)
    }
    
    // 2단계: 본문 레코드 삭제
    $sql = "DELETE FROM {$DB}.{$tablename} WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $deletedRecord = $stmh->rowCount();
    
    // 커밋
    $pdo->commit();
    
    // 결과 확인
    if ($deletedRecord > 0) {
        $success = true;
        if ($deletedPictures > 0) {
            $message = "삭제되었습니다. (첨부파일 {$deletedPictures}개 포함)";
        } else {
            $message = '삭제되었습니다.';
        }
    } else {
        $success = false;
        $message = '삭제할 레코드를 찾을 수 없습니다.';
    }
    
} catch (Exception $ex) {
    // 롤백
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Delete error in HRboard/delete.php: " . $ex->getMessage());
    
    $success = false;
    $message = '삭제 중 오류가 발생했습니다.';
}

// JSON 응답 생성
$response = [
    'success' => $success,
    'message' => $message,
    'num' => $num,
    'deletedPictures' => $deletedPictures,
    'deletedRecord' => $deletedRecord
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
