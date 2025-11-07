<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

// 권한 체크
if ($level > 5) {
    echo json_encode(['ok' => false, 'error' => '접근 권한이 없습니다.']);
    exit;
}

// POST 데이터 받기
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['audio'])) {
    echo json_encode(['ok' => false, 'error' => '오디오 데이터가 없습니다.']);
    exit;
}

$audioBase64 = $data['audio'];

// OpenAI API 키 읽기 (Whisper 사용)
$openaiKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/apikey/openai_api.txt';
if (!file_exists($openaiKeyFile)) {
    echo json_encode(['ok' => false, 'error' => 'OpenAI API 키 파일이 존재하지 않습니다. /apikey/openai_api.txt 파일을 생성해주세요.']);
    exit;
}

$openaiApiKey = trim(file_get_contents($openaiKeyFile));

// 오디오 데이터 준비 (base64에서 data:audio/... 부분 제거)
$audioData = $audioBase64;
$mediaType = 'audio/webm'; // 기본값

if (preg_match('/^data:(audio\/[\w+]+);base64,(.+)$/', $audioBase64, $matches)) {
    $mediaType = $matches[1]; // audio/webm, audio/wav 등
    $audioData = $matches[2]; // 순수 base64
}

// 오디오를 임시 파일로 저장
$tempDir = sys_get_temp_dir();
$extension = ($mediaType === 'audio/wav') ? 'wav' : 'webm';
$tempFile = $tempDir . '/voice_' . uniqid() . '.' . $extension;
file_put_contents($tempFile, base64_decode($audioData));

// OpenAI Whisper API 호출 (음성 -> 텍스트 변환)
$whisperUrl = 'https://api.openai.com/v1/audio/transcriptions';

$postFields = [
    'model' => 'whisper-1',
    'file' => new CURLFile($tempFile, $mediaType, 'audio.' . $extension),
    'language' => 'ko', // 한국어 우선 인식
    'response_format' => 'json'
];

$ch = curl_init($whisperUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $openaiApiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// 임시 파일 삭제
@unlink($tempFile);

if ($httpCode !== 200) {
    error_log("Whisper API Error: HTTP {$httpCode} - {$response}");

    // 에러 상세 정보 파싱
    $errorDetails = 'API 호출 실패';
    if ($response) {
        $errorJson = json_decode($response, true);
        if (isset($errorJson['error']['message'])) {
            $errorDetails = $errorJson['error']['message'];
        }
    }

    echo json_encode([
        'ok' => false,
        'error' => "OpenAI Whisper API 호출 실패 (HTTP {$httpCode})",
        'details' => $errorDetails,
        'curl_error' => $curlError
    ]);
    exit;
}

$apiResponse = json_decode($response, true);

if (!isset($apiResponse['text'])) {
    error_log("Whisper API Response Format Error: " . $response);
    echo json_encode([
        'ok' => false,
        'error' => 'Whisper API 응답 형식 오류',
        'raw_response' => $response
    ]);
    exit;
}

// Whisper가 반환한 텍스트 추출
$transcript = $apiResponse['text'];

// 텍스트 정리 (앞뒤 공백 제거)
$transcript = trim($transcript);

// 성공 응답
echo json_encode([
    'ok' => true,
    'transcript' => $transcript
]);
