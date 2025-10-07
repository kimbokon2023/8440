<?php
error_reporting(0); // 오류 리포팅 비활성화

if (!isset($_SESSION)) {
    session_start();
}

header("Content-Type: application/json");

// POST 데이터 수신
$savedName = $_POST['savedName'] ?? '';
$filePath = './RefLine/RefLine_' . $_SESSION['userid'] . '.json';

if(file_exists($filePath)) {
    $data = json_decode(file_get_contents($filePath), true);
    
    foreach ($data as $RefLine) {
        if ($RefLine['savedName'] === $savedName) {
			echo json_encode($RefLine, JSON_UNESCAPED_UNICODE);            
			// echo json_encode(["status" => "success", "num" => "num"], JSON_UNESCAPED_UNICODE);          
			// echo json_encode(array('status' => 'success', 'message' => 'Ref line saved successfully.'));
            exit;
        }
    }	
	
}


?>
