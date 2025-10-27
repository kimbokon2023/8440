<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID가 필요합니다']);
    exit;
}

try {
    $sql = "DELETE FROM daon_products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => '삭제되었습니다']);
    } else {
        echo json_encode(['success' => false, 'message' => '삭제할 항목을 찾을 수 없습니다']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '오류: ' . $e->getMessage()]);
}
?>
