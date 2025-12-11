<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json');

$pdo = db_connect();

try {
    $admins = json_decode($_POST['admins'], true);

    if (!is_array($admins)) {
        throw new Exception("Invalid data format.");
    }

    $pdo->beginTransaction();

    // 1. 기존 데이터 삭제 (Truncate 대신 Delete 사용, 안전을 위해)
    $sql_delete = "DELETE FROM admin_phomi";
    $pdo->exec($sql_delete);

    // 2. 새로운 데이터 삽입
    $sql_insert = "INSERT INTO admin_phomi (member_id, member_name, rank_order) VALUES (:member_id, :member_name, :rank_order)";
    $stmt = $pdo->prepare($sql_insert);

    foreach ($admins as $admin) {
        $stmt->bindValue(':member_id', $admin['member_id']);
        $stmt->bindValue(':member_name', $admin['member_name']);
        $stmt->bindValue(':rank_order', $admin['rank_order']);
        $stmt->execute();
    }

    $pdo->commit();

    echo json_encode(['status' => 'success', 'message' => 'Settings saved successfully.']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
