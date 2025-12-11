<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../bootstrap.php';
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
require_once(includePath('session.php'));

try {
    // 견적서 목록을 가져오는 쿼리
    $sql = "SELECT num, quote_date, recipient, site_name 
            FROM {$DB}.phomi_estimate 
            WHERE (is_deleted IS NULL OR is_deleted = 'N') 
            ORDER BY quote_date DESC, num DESC 
            LIMIT 50";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $estimates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'estimates' => $estimates
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '견적서 목록을 가져오는 중 오류가 발생했습니다: ' . $e->getMessage()
    ]);
}
?> 