<?php
header("Access-Control-Allow-Origin: *"); // 이 헤더는 개발 중에만 사용하시고, 실제 배포시에는 특정 도메인을 지정해주세요.
header("Content-Type: application/json");

$filename = "organization.json";

if (file_exists($filename)) {
    echo file_get_contents($filename);
} else {
    echo json_encode(["status" => "error", "message" => "File not found."]);
}
?>
