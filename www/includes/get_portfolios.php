<?php
/**
 * 시공사례 데이터 가져오기
 * index.php에서 include하여 사용
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

$portfolios = [];

try {
    $pdo = db_connect();
    $DB = $_SESSION['DB'] ?? 'mirae8440';
    
    // 공개된 시공사례만 가져오기 (최신순 12개)
    $sql = "SELECT * FROM {$DB}.portfolio WHERE is_published = 1 ORDER BY display_order ASC, created_at DESC LIMIT 12";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $portfolios = $stmh->fetchAll(PDO::FETCH_ASSOC);
    
    // images 필드 JSON 디코딩 처리
    foreach ($portfolios as &$portfolio) {
        if (isset($portfolio['images']) && !empty($portfolio['images'])) {
            $decoded = json_decode($portfolio['images'], true);
            $portfolio['images'] = $decoded !== null ? $decoded : [];
        } else {
            $portfolio['images'] = [];
        }
    }
    unset($portfolio); // 참조 해제
    
} catch (PDOException $e) {
    // 오류 발생 시 빈 배열 유지
    error_log("Portfolio fetch error: " . $e->getMessage());
}

