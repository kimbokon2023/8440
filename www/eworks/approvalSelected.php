<?php
require_once __DIR__ . '/../bootstrap.php';

header("Content-Type: application/json");

function getPosition($userId, $pdo) {
    $query = "SELECT position FROM mirae8440.member WHERE id = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $userId, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['position'] : '';
    } catch (PDOException $e) {
        return '';
    }
}

function getEConfirmValues($e_num, $pdo) {
    $query = "SELECT e_confirm, e_confirm_id, e_line_id FROM mirae8440.eworks WHERE num = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
        $stmh->execute();
        return $stmh->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

$date = date('Y-m-d H:i:s');
$e_nums = $_REQUEST["selectedIds"] ?? [];
$last_e_num = null;

try {
    foreach ($e_nums as $e_num) {
        $last_e_num = $e_num;
        $confirmValues = getEConfirmValues($e_num, $pdo);
        $e_confirm = $confirmValues['e_confirm'] ?? '';
        $e_confirm_id = $confirmValues['e_confirm_id'] ?? '';
        $e_line_id = $confirmValues['e_line_id'] ?? '';    

        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo) . " ". $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;
        
        $e_line_id_count = count(explode("!", $e_line_id));
        $e_confirm_count = count(explode("!", $e_confirm_id_value));	
        $status = 'ing';	
        $done = null;
        
        if ($e_line_id_count == $e_confirm_count) {
            $status = 'end';
            $done = 'done';
        }

        $sql = "UPDATE mirae8440.eworks SET e_confirm=?, e_confirm_id=?, done=? , status=?  WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$e_confirm_value, $e_confirm_id_value, $done, $status, $e_num]);
    }

    $data = array(
        "num" => $last_e_num,
    );

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database processing error', 'message' => $e->getMessage()]);
}

?>

