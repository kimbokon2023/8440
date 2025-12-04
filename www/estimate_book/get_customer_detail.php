<?php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 5) {
    echo json_encode([
        'success' => false,
        'message' => '권한이 없습니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$num = isset($_GET['num']) ? (int) $_GET['num'] : 0;
if ($num <= 0) {
    echo json_encode([
        'success' => false,
        'message' => '거래처 번호가 필요합니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = db_connect();
    $dbName = $_SESSION['DB'] ?? 'mirae8440';

    $customerSql = "SELECT * FROM `estimate_customer` WHERE num = :num AND is_deleted = 'N'";
    $stmt = $pdo->prepare($customerSql);
    $stmt->bindValue(':num', $num, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo json_encode([
            'success' => false,
            'message' => '거래처 정보를 찾을 수 없습니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $contactSql = "SELECT * FROM `estimate_customer_contact` WHERE customer_id = :num AND is_deleted = 'N' ORDER BY num ASC";
    $contactStmt = $pdo->prepare($contactSql);
    $contactStmt->bindValue(':num', $num, PDO::PARAM_INT);
    $contactStmt->execute();
    $contacts = $contactStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'customer' => $customer,
        'contacts' => $contacts
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    error_log('거래처 상세 조회 오류: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => '데이터 조회 중 오류가 발생했습니다.'
    ], JSON_UNESCAPED_UNICODE);
}

