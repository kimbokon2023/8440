<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

$tablename = 'work';

if (isset($_POST['img'])) {
    $img = $_POST['img'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $file = '../' . $tablename . '/signatures/' . uniqid() . '.png';

    if (!file_exists('../' . $tablename . '/signatures/')) {
        mkdir('../' . $tablename . '/signatures/', 0777, true);
    }

    file_put_contents($file, $data);
    $fileUrl = '/signatures/' . basename($file);

    // 서버에 파일명 저장 부분 구현
    $num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

    require_once("../lib/mydb.php");
    $pdo = db_connect();

    try {
        $sql = "SELECT customer FROM $DB.$tablename WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        $customerData = json_decode($row["customer"], true);
        $customerData['image_url'] = $fileUrl;
        $jsonDataString = json_encode($customerData);

        $sql = "UPDATE $DB.$tablename SET customer = ? WHERE num = ? LIMIT 1";
        $pdo->beginTransaction();
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $jsonDataString, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Database error: " . $Exception->getMessage()));
        exit();
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "No image data received"));
}
?>
