<?php
/**
 * 이미지 파일 삭제 처리 (구버전)
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// JSON 응답 헤더 설정
header("Content-Type: application/json");

// 요청 변수 초기화
$picname = $_REQUEST["picname"] ?? '';

if (empty($picname)) {
    echo json_encode([
        'status' => 'error',
        'message' => '파일명이 제공되지 않았습니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
$pdo = db_connect();

try {
    // 물리적 파일 삭제
    $upload_dir = '../uploads/';
    $made_name = $upload_dir . $picname;

    if (file_exists($made_name)) {
        unlink($made_name);
    }

    // 데이터베이스에서 삭제
    $pdo->beginTransaction();
    $sql = "DELETE FROM mirae8440.picuploads WHERE picname = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $picname, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();

    // 성공 응답
    $data = [
        'status' => 'success',
        'picname' => $picname,
        'message' => '파일이 성공적으로 삭제되었습니다.'
    ];

} catch (Exception $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $error_message = "파일 삭제 오류: " . $ex->getMessage();
    error_log($error_message);

    $data = [
        'status' => 'error',
        'message' => $error_message,
        'picname' => $picname
    ];
}

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>