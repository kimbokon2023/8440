<?php
// 출력 버퍼링 시작
ob_start();

// 에러 리포팅 설정 (Notice 포함 모든 에러 출력 비활성화)
error_reporting(0);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

try {
    require_once __DIR__ . '/../bootstrap.php';
    require_once __DIR__ . '/login/check_login.php';
    
    // include 파일이 설정을 덮어쓸 수 있으므로 다시 설정
    error_reporting(0);
    ini_set('display_errors', '0');
    
    // 이전 출력 비우기
    ob_clean();
    
    // JSON 응답을 위한 헤더 설정
    header('Content-Type: application/json; charset=utf-8');

    $sql = "SELECT id, company_name, manager_name, tel, email
            FROM daon_customers
            WHERE status = 'active'
            ORDER BY company_name";
    
    $stmt = $pdo->query($sql);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($customers, JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit;
    
} catch (PDOException $e) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit;
    
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit;
}
