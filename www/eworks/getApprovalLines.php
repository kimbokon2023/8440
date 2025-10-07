<?php
error_reporting(0); // 오류 리포팅 비활성화

if (!isset($_SESSION)) {
    session_start();
}

header("Content-Type: application/json");

$filePath = './approvalLine/approvalLine_' . $_SESSION['userid'] . '.json';

if(file_exists($filePath)) {
    $data = json_decode(file_get_contents($filePath), true);
    $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);

    if (json_last_error() === JSON_ERROR_NONE) {
        echo $jsonData;
    } else {
        echo json_encode(["error" => "JSON encoding error"]);
    }
} else {
    echo json_encode([]);
}

?>
