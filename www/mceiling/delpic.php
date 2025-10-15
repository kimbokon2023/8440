<?php
/**
 * 천장 포장 사진 삭제 처리 (AJAX)
 * 로컬 및 서버 환경 모두 지원
 */

header("Content-Type: application/json; charset=utf-8");

// 요청 변수 초기화
$picname = isset($_REQUEST["picname"]) ? $_REQUEST["picname"] : '';

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'picname' => $picname
);

// 파일명 검증
if (empty($picname)) {
    $response['message'] = '파일명이 지정되지 않았습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 물리적 파일 삭제
$upload_dir = '../imgceiling/';
$file_path = $upload_dir . $picname;

// 파일 존재 여부 확인 후 삭제
if (file_exists($file_path)) {
    if (@unlink($file_path)) {
        $response['message'] = '파일이 삭제되었습니다.';
    } else {
        $response['message'] = '파일 삭제 실패';
        error_log("파일 삭제 실패: {$file_path}");
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    // 파일이 없어도 DB에서는 삭제 시도
    $response['message'] = '파일이 존재하지 않습니다 (DB에서는 삭제 시도).';
    error_log("파일 없음: {$file_path}");
}

// 데이터베이스에서 삭제
try {
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM mirae8440.ceilpicfile WHERE picname = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $picname, PDO::PARAM_STR);
    $stmh->execute();
    
    // 삭제된 행 수 확인
    $rowCount = $stmh->rowCount();
    
    $pdo->commit();
    
    if ($rowCount > 0) {
        $response['success'] = true;
        $response['message'] .= ' DB에서 삭제되었습니다.';
    } else {
        $response['message'] .= ' DB에 해당 레코드가 없습니다.';
    }
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("DB 삭제 오류 (picname: {$picname}): " . $ex->getMessage());
    $response['message'] = '데이터베이스 삭제 중 오류가 발생했습니다.';
    $response['error'] = $ex->getMessage();
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
