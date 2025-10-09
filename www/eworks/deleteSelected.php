<?php
require_once __DIR__ . '/../bootstrap.php';

header("Content-Type: application/json");

// 여러 e_num 값을 배열로 받아옵니다.
$e_nums = $_REQUEST["selectedIds"] ?? [];
$last_e_num = null; 

try {
    foreach ($e_nums as $e_num) {
        $last_e_num = $e_num;

        // Get current e_viewexcept_id
        $sql_select = "SELECT e_viewexcept_id FROM mirae8440.eworks WHERE num = ?";
        $stmh_select = $pdo->prepare($sql_select);
        $stmh_select->bindValue(1, $e_num, PDO::PARAM_INT);
        $stmh_select->execute();
        $row = $stmh_select->fetch(PDO::FETCH_ASSOC);
        $e_viewexcept_id = $row ? $row['e_viewexcept_id'] : '';

        $e_viewexcept_id_new = ($e_viewexcept_id === '' || $e_viewexcept_id === null) ? $user_id : $e_viewexcept_id . '!' . $user_id;

        $sql_update = "UPDATE mirae8440.eworks SET e_viewexcept_id=? WHERE num=?";
        $stmh_update = $pdo->prepare($sql_update);
        $stmh_update->execute([$e_viewexcept_id_new, $e_num]);
    }

    $data = array(
        "num" =>  $last_e_num,
        "selectedIds" => $e_nums,
    );

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database processing error', 'message' => $e->getMessage()]);
}

?>

