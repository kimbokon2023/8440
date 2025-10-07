<?php
error_reporting(0); // 오류 리포팅 비활성화
header("Content-Type: application/json");

if (!isset($_SESSION)) {
    session_start();
}

$savedName = $_POST['savedName'] ?? '';
$filePath = './RefLine/RefLine_' . $_SESSION['userid'] . '.json';

if(file_exists($filePath)) {
    $data = json_decode(file_get_contents($filePath), true);
    if (is_array($data)) {
        // 결재라인 제거
        foreach ($data as $key => $value) {
            if ($value['savedName'] === $savedName) {
                unset($data[$key]);
                break;
            }
        }

        // 파일 업데이트
        file_put_contents($filePath, json_encode(array_values($data), JSON_UNESCAPED_UNICODE));
        echo json_encode(["status" => "success", "message" => "Ref line deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid data format"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "File not found"]);
}
?>
