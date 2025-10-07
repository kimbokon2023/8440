<?php
if (!isset($_SESSION)) {
    session_start();
}

// JSON 데이터 수신
$data = json_decode(file_get_contents('php://input'), true);

// 파일 경로 설정
$filePath = './approvalLine/approvalLine_' . $data['userId'] . '.json';

// 파일이 이미 존재하면 기존 데이터를 로드하고, 존재하지 않으면 새 배열을 생성
if(file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
    if (!is_array($existingData)) { // 기존 데이터가 배열이 아니면 새 배열 생성
        $existingData = array();
    }
} else {
    $existingData = array();
}

// 새로운 결재라인 정보를 기존 데이터에 추가
$existingData[] = array(
    'userId' => $data['userId'],
    'savedName' => $data['savedName'],
    'approvalOrder' => $data['approvalOrder']
);

// 파일에 수정된 데이터 저장
file_put_contents($filePath, json_encode($existingData));

echo json_encode(array('status' => 'success', 'message' => 'Approval line saved successfully.'));
?>
