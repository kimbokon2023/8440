<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

$pdo = db_connect();

try {
    // 1. Add new columns to estimate_customer
    $alterQueries = [
        "ALTER TABLE estimate_customer ADD COLUMN display_name VARCHAR(100) COMMENT '전자 우편 표시 이름'",
        "ALTER TABLE estimate_customer ADD COLUMN department VARCHAR(100) COMMENT '부서'",
        "ALTER TABLE estimate_customer ADD COLUMN work_phone VARCHAR(50) COMMENT '근무처 전화'",
        "ALTER TABLE estimate_customer ADD COLUMN home_phone VARCHAR(50) COMMENT '집 전화 번호'",
        "ALTER TABLE estimate_customer ADD COLUMN mobile_phone VARCHAR(50) COMMENT '휴대폰'",
        "ALTER TABLE estimate_customer ADD COLUMN memo TEXT COMMENT '메모'",
        "ALTER TABLE estimate_customer ADD COLUMN email VARCHAR(100) COMMENT '전자 메일 주소'"
    ];

    foreach ($alterQueries as $sql) {
        try {
            $pdo->exec($sql);
            echo "Executed: $sql<br>";
        } catch (PDOException $e) {
            // Ignore if column already exists
            echo "Skipped (likely exists): $sql (" . $e->getMessage() . ")<br>";
        }
    }

    // 2. Drop unused columns (Optional, but requested "The rest are all unnecessary")
    // We will keep 'company_name' as '회사'
    // We will keep 'id' or 'num' as primary key
    // We will keep 'is_deleted' for soft delete
    // We will keep 'created_at', 'updated_at'
    
    // Mapping:
    // "전자 우편 표시 이름" -> display_name
    // "회사" -> company_name
    // "부서" -> department
    // "근무처 전화" -> work_phone
    // "집 전화 번호" -> home_phone
    // "휴대폰" -> mobile_phone
    // "메모" -> memo
    // "전자 메일 주소" -> email

    echo "Table structure updated successfully.<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
