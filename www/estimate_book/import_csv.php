<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

// Increase execution time for large imports
set_time_limit(300);

$pdo = db_connect();

echo "<h1>Address Book Import & Setup</h1>";

// 1. Setup Database Structure
try {
    echo "<h2>1. Setting up Database Structure...</h2>";
    
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
            echo "Skipped (likely exists): $sql<br>";
        }
    }
    echo "Table structure check complete.<br>";

} catch (Exception $e) {
    echo "Error setting up DB: " . $e->getMessage() . "<br>";
}

// 2. Import CSV
try {
    echo "<h2>2. Importing CSV Data...</h2>";
    
    $csvFile = __DIR__ . '/data.csv';
    if (!file_exists($csvFile)) {
        throw new Exception("CSV file not found at: $csvFile");
    }

    $handle = fopen($csvFile, "r");
    if ($handle === FALSE) {
        throw new Exception("Could not open CSV file.");
    }

    // Skip header row
    fgetcsv($handle);

    $successCount = 0;
    $failCount = 0;

    $stmt = $pdo->prepare("INSERT INTO estimate_customer (
        display_name, company_name, department, work_phone, home_phone, mobile_phone, memo, email,
        classification, created_at, is_deleted
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?,
        '주소록', NOW(), 'N'
    )");

    while (($data = fgetcsv($handle)) !== FALSE) {
        // CSV Structure:
        // "전자 우편 표시 이름","회사","부서","근무처 전화","집 전화 번호","휴대폰","메모","전자 메일 주소"
        // 0: display_name
        // 1: company_name
        // 2: department
        // 3: work_phone
        // 4: home_phone
        // 5: mobile_phone
        // 6: memo
        // 7: email

        $display_name = trim($data[0]);
        $company_name = trim($data[1]);
        $department = trim($data[2]);
        $work_phone = trim($data[3]);
        $home_phone = trim($data[4]);
        $mobile_phone = trim($data[5]);
        $memo = trim($data[6]);
        $email = trim($data[7]);

        // Validation: At least one contact info or name should exist
        if (empty($company_name) && empty($display_name) && empty($email)) {
            $failCount++;
            continue;
        }

        try {
            $stmt->execute([
                $display_name,
                $company_name,
                $department,
                $work_phone,
                $home_phone,
                $mobile_phone,
                $memo,
                $email
            ]);
            $successCount++;
        } catch (Exception $e) {
            echo "Failed to insert row: " . implode(',', $data) . " - " . $e->getMessage() . "<br>";
            $failCount++;
        }
    }

    fclose($handle);

    echo "<h3>Import Complete</h3>";
    echo "Success: $successCount<br>";
    echo "Failed: $failCount<br>";

} catch (Exception $e) {
    echo "Error importing CSV: " . $e->getMessage() . "<br>";
}
?>
