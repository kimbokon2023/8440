<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

$isEdit = isset($_POST['order_id']) && !empty($_POST['order_id']);

try {
    // 신규 등록 시 발주번호 자동 생성
    if (!$isEdit) {
        $orderNumber = $_POST['order_number'];

        // 발주번호가 완성되지 않았으면 (끝에 -로 끝나면) 자동으로 일련번호 추가
        if (substr($orderNumber, -1) === '-') {
            $prefix = $orderNumber; // ON-20251022-

            // 같은 날짜의 마지막 발주번호 찾기
            $sql = "SELECT order_number FROM daon_orders
                    WHERE order_number LIKE ?
                    ORDER BY order_number DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$prefix . '%']);
            $lastOrder = $stmt->fetch();

            if ($lastOrder) {
                // 마지막 번호에서 일련번호 추출
                $lastNumber = intval(substr($lastOrder['order_number'], -3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // 새 발주번호 생성 (3자리 패딩)
            $orderNumber = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $_POST['order_number'] = $orderNumber;
        }
    }

    if ($isEdit) {
        // 수정
        $sql = "UPDATE daon_orders SET
                order_date = ?,
                delivery_date = ?,
                customer_id = ?,
                product_name = ?,
                product_type = ?,
                spec = ?,
                quantity = ?,
                unit = ?,
                unit_price = ?,
                total_price = ?,
                vat_included = ?,
                status = ?,
                priority = ?,
                delivery_address = ?,
                note = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['order_date'],
            $_POST['delivery_date'] ?: null,
            $_POST['customer_id'],
            $_POST['product_name'],
            $_POST['product_type'] ?: null,
            $_POST['spec'] ?: null,
            $_POST['quantity'],
            $_POST['unit'] ?: 'EA',
            $_POST['unit_price'],
            $_POST['total_price'],
            isset($_POST['vat_included']) ? 1 : 0,
            $_POST['status'],
            $_POST['priority'],
            $_POST['delivery_address'] ?: null,
            $_POST['note'] ?: null,
            $_POST['order_id']
        ]);

        $_SESSION['message'] = '발주가 수정되었습니다.';
        $_SESSION['message_type'] = 'success';

    } else {
        // 신규 등록
        $sql = "INSERT INTO daon_orders (
                order_number, order_date, delivery_date, customer_id,
                product_name, product_type, spec, quantity, unit,
                unit_price, total_price, vat_included, status, priority,
                delivery_address, note, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['order_number'],
            $_POST['order_date'],
            $_POST['delivery_date'] ?: null,
            $_POST['customer_id'],
            $_POST['product_name'],
            $_POST['product_type'] ?: null,
            $_POST['spec'] ?: null,
            $_POST['quantity'],
            $_POST['unit'] ?: 'EA',
            $_POST['unit_price'],
            $_POST['total_price'],
            isset($_POST['vat_included']) ? 1 : 0,
            $_POST['status'] ?? 'pending',
            $_POST['priority'] ?? 'normal',
            $_POST['delivery_address'] ?: null,
            $_POST['note'] ?: null,
            $_SESSION['daon_userid']
        ]);

        $_SESSION['message'] = '발주가 등록되었습니다.';
        $_SESSION['message_type'] = 'success';
    }

    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['message'] = '오류가 발생했습니다: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    header('Location: ' . ($isEdit ? 'order_form.php?id=' . $_POST['order_id'] : 'order_form.php'));
    exit;
}
?>
