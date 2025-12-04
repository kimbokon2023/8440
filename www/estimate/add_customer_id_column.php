<?php
/**
 * orders 테이블에 customer_id 컬럼 추가 스크립트
 */

require_once __DIR__ . '/../bootstrap.php';

// 권한 확인 (관리자만 실행 가능하도록 설정)
$level = $_SESSION["level"] ?? 999;
if ($level > 5) {
    die("권한이 없습니다.");
}

try {
    $pdo = db_connect();
    $DB = $_SESSION["DB"] ?? 'mirae8440';

    echo "<h3>orders 테이블 스키마 업데이트</h3>";

    // 컬럼 존재 여부 확인
    $checkSql = "SELECT count(*) FROM information_schema.COLUMNS 
                 WHERE TABLE_SCHEMA = :db 
                 AND TABLE_NAME = 'orders' 
                 AND COLUMN_NAME = 'customer_id'";
    
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([':db' => $DB]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        echo "<p style='color: blue;'>[INFO] customer_id 컬럼이 이미 존재합니다.</p>";
    } else {
        // 컬럼 추가
        $alterSql = "ALTER TABLE `orders` ADD COLUMN `customer_id` INT(11) NULL COMMENT '거래처 고유번호' AFTER `issue_date`";
        $pdo->exec($alterSql);
        echo "<p style='color: green;'>[SUCCESS] customer_id 컬럼이 성공적으로 추가되었습니다.</p>";
        
        // 인덱스 추가
        $indexSql = "ALTER TABLE `orders` ADD INDEX `idx_customer_id` (`customer_id`)";
        $pdo->exec($indexSql);
        echo "<p style='color: green;'>[SUCCESS] customer_id 인덱스가 추가되었습니다.</p>";
    }

    echo "<p>업데이트가 완료되었습니다. <a href='index.php'>목록으로 돌아가기</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>[ERROR] 데이터베이스 오류: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>[ERROR] 오류: " . $e->getMessage() . "</p>";
}
?>
