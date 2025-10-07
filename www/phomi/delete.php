<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

header('Content-Type: application/json');

try {
    // POST 데이터 확인
    $num = $_POST['num'] ?? '';
    $tablename = $_POST['tablename'] ?? '';
    
    if (empty($num) || empty($tablename)) {
        echo json_encode([
            'result' => 'error',
            'message' => '필수 파라미터가 누락되었습니다.'
        ]);
        exit;
    }
    
    $pdo = db_connect();
    
    // 견적서 존재 여부 확인
    $check_sql = "SELECT num FROM {$DB}.{$tablename} WHERE num = ? AND (is_deleted IS NULL OR is_deleted = 'N')";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$num]);
    
    if ($check_stmt->rowCount() == 0) {
        echo json_encode([
            'result' => 'error',
            'message' => '삭제할 파일을 찾을 수 없습니다.'
        ]);
        exit;
    }
    
    // 실제 삭제 대신 is_deleted 플래그를 'Y'로 설정 (소프트 삭제)
    $delete_sql = "UPDATE {$DB}.{$tablename} SET is_deleted = 'Y', updatedAt = NOW() WHERE num = ?";
    $delete_stmt = $pdo->prepare($delete_sql);
    $result = $delete_stmt->execute([$num]);
    
    if ($result) {
        echo json_encode([
            'result' => 'success',
            'message' => '성공적으로 삭제되었습니다.'
        ]);
    } else {
        echo json_encode([
            'result' => 'error',
            'message' => '삭제 처리 중 오류가 발생했습니다.'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'result' => 'error',
        'message' => '데이터베이스 오류: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'result' => 'error',
        'message' => '오류가 발생했습니다: ' . $e->getMessage()
    ]);
}
?> 