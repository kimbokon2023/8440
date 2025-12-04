<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db_connect();

    $q = $_GET['q'] ?? '';
    
    if (empty($q)) {
        echo json_encode(['success' => true, 'customers' => []]);
        exit;
    }

    $sql = "SELECT num, display_name, company_name, department, work_phone, home_phone, mobile_phone, email, memo 
            FROM estimate_customer 
            WHERE is_deleted = 'N' 
            AND (display_name LIKE ? OR company_name LIKE ? OR email LIKE ? OR mobile_phone LIKE ?)
            ORDER BY company_name ASC, display_name ASC
            LIMIT 20";

    $stmt = $pdo->prepare($sql);
    $term = "%$q%";
    $stmt->execute([$term, $term, $term, $term]);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'customers' => $customers
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
