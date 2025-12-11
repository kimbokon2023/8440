<?php
/**
 * 케이블 작업 완료 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

header("Content-Type: application/json; charset=utf-8");

// 요청 변수 초기화
$mode = isset($_POST["mode"]) ? $_POST["mode"] : '';
$num = isset($_POST["num"]) ? $_POST["num"] : '';
$cabledone = isset($_POST["cabledone"]) ? $_POST["cabledone"] : '';

// 응답 데이터 초기화
$response = [
    'status' => 'error',
    'message' => '',
    'num' => $num
];

// 요청 변수 파일 포함 (update_log 등의 변수를 가져오기 위해)
$update_log = '';
if (file_exists(getDocumentRoot() . '/ceiling/_requestDB.php')) {
    include getDocumentRoot() . '/ceiling/_requestDB.php';
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 입력값 검증
if ($mode == "modify" && !empty($num)) {
    try {
        // 업데이트 로그 생성
        $data = date("Y-m-d H:i:s") . " - " . ($_SESSION["name"] ?? 'unknown') . "  ";
        $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea
        
        // 트랜잭션 시작
        $pdo->beginTransaction();
        
        // SQL 실행
        $sql = "UPDATE mirae8440.ceiling SET cabledone = ? WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $cabledone, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_INT);
        $stmh->execute();
        
        // 커밋
        $pdo->commit();
        
        // 성공 응답
        $response['status'] = 'success';
        $response['message'] = '케이블 작업 완료 상태가 업데이트되었습니다.';
        
    } catch (PDOException $ex) {
        // 롤백
        $pdo->rollBack();
        
        // 에러 로깅
        error_log("케이블 작업 완료 처리 오류 (num: {$num}): " . $ex->getMessage());
        
        // 에러 응답
        $response['message'] = '데이터베이스 오류가 발생했습니다.';
        $response['error'] = $ex->getMessage();
    }
} else {
    $response['message'] = '잘못된 요청입니다. 모드와 번호를 확인해주세요.';
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
