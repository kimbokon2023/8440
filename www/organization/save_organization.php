<?php
session_start();  // 세션 시작

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$filename = "organization.json";

// 사용자 권한 확인
if (isset($_SESSION["level"]) && $_SESSION["level"] == 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = file_get_contents("php://input");
        if (file_put_contents($filename, $data)) {
            echo json_encode(["status" => "success", "message" => "Data successfully saved."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Unable to save data."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Permission denied."]);
}
?>
