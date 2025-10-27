<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

$isEdit = isset($_POST['product_id']) && !empty($_POST['product_id']);

try {
    if ($isEdit) {
        // 수정
        $sql = "UPDATE daon_products SET
                product_code = ?,
                product_name = ?,
                product_type = ?,
                spec = ?,
                unit = ?,
                standard_price = ?,
                description = ?,
                status = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['product_code'] ?: null,
            $_POST['product_name'],
            $_POST['product_type'],
            $_POST['spec'] ?: null,
            $_POST['unit'],
            $_POST['standard_price'] ?: null,
            $_POST['description'] ?: null,
            $_POST['status'] ?? 'active',
            $_POST['product_id']
        ]);

    } else {
        // 신규 등록
        $sql = "INSERT INTO daon_products (
                product_code, product_name, product_type, spec, unit,
                standard_price, description, status, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['product_code'] ?: null,
            $_POST['product_name'],
            $_POST['product_type'],
            $_POST['spec'] ?: null,
            $_POST['unit'],
            $_POST['standard_price'] ?: null,
            $_POST['description'] ?: null,
            $_POST['status'] ?? 'active',
            $_SESSION['daon_userid']
        ]);
    }

    header('Location: product_list.php');
    exit;

} catch (PDOException $e) {
    die('오류: ' . $e->getMessage());
}
?>
