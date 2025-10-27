<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

$isEdit = isset($_POST['customer_id']) && !empty($_POST['customer_id']);

try {
    if ($isEdit) {
        // 수정
        $sql = "UPDATE daon_customers SET
                company_name = ?,
                business_number = ?,
                ceo_name = ?,
                address = ?,
                tel = ?,
                fax = ?,
                email = ?,
                manager_name = ?,
                manager_tel = ?,
                note = ?,
                status = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['company_name'],
            $_POST['business_number'] ?: null,
            $_POST['ceo_name'] ?: null,
            $_POST['address'] ?: null,
            $_POST['tel'] ?: null,
            $_POST['fax'] ?: null,
            $_POST['email'] ?: null,
            $_POST['manager_name'] ?: null,
            $_POST['manager_tel'] ?: null,
            $_POST['note'] ?: null,
            $_POST['status'] ?? 'active',
            $_POST['customer_id']
        ]);

    } else {
        // 신규 등록
        $sql = "INSERT INTO daon_customers (
                company_name, business_number, ceo_name, address, tel, fax,
                email, manager_name, manager_tel, note, status, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['company_name'],
            $_POST['business_number'] ?: null,
            $_POST['ceo_name'] ?: null,
            $_POST['address'] ?: null,
            $_POST['tel'] ?: null,
            $_POST['fax'] ?: null,
            $_POST['email'] ?: null,
            $_POST['manager_name'] ?: null,
            $_POST['manager_tel'] ?: null,
            $_POST['note'] ?: null,
            $_POST['status'] ?? 'active',
            $_SESSION['daon_userid']
        ]);
    }

    header('Location: customer_list.php');
    exit;

} catch (PDOException $e) {
    die('오류: ' . $e->getMessage());
}
?>
