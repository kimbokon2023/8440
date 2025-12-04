<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json');

$pdo = db_connect();
$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'save_master') {
        $mode = $_POST['mode'];
        $id = $_POST['id'];
        $registdate = $_POST['registdate'];
        $item_name = $_POST['item_name'];
        $spec = $_POST['spec'];
        $price = $_POST['price'] ?: 0;
        $unit = $_POST['unit'];
        $remarks = $_POST['remarks'];

        if ($mode === 'insert') {
            $sql = "INSERT INTO material_master (registdate, item_name, spec, price, unit, remarks) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$registdate, $item_name, $spec, $price, $unit, $remarks]);
        } else {
            $sql = "UPDATE material_master SET registdate=?, item_name=?, spec=?, price=?, unit=?, remarks=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$registdate, $item_name, $spec, $price, $unit, $remarks, $id]);
        }
        echo json_encode(['success' => true]);

    } elseif ($action === 'save_transaction') {
        $material_id = $_POST['material_id'];
        $transaction_type = $_POST['transaction_type'];
        $transaction_date = $_POST['transaction_date'];
        $quantity = $_POST['quantity'];
        $remarks = $_POST['remarks'];

        $sql = "INSERT INTO material_transactions (material_id, transaction_type, transaction_date, quantity, remarks) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$material_id, $transaction_type, $transaction_date, $quantity, $remarks]);
        
        echo json_encode(['success' => true]);

    } elseif ($action === 'update_transaction') {
        $id = $_POST['id'];
        $material_id = $_POST['material_id'];
        $transaction_type = $_POST['transaction_type'];
        $transaction_date = $_POST['transaction_date'];
        $quantity = $_POST['quantity'];
        $remarks = $_POST['remarks'];

        $sql = "UPDATE material_transactions SET material_id=?, transaction_type=?, transaction_date=?, quantity=?, remarks=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$material_id, $transaction_type, $transaction_date, $quantity, $remarks, $id]);
        
        echo json_encode(['success' => true]);

    } elseif ($action === 'delete_transaction') {
        $id = $_POST['id'];
        $sql = "DELETE FROM material_transactions WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        
    } elseif ($action === 'delete_master') {
        $id = $_POST['id'];
        // 논리적 삭제 (is_deleted = 1)
        $sql = "UPDATE material_master SET is_deleted = 1 WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
