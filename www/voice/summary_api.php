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

if (!isset($data['text']) || empty(trim($data['text']))) {
    echo json_encode(['ok' => false, 'error' => '요약할 텍스트가 없습니다.']);
    exit;
}

$text = $data['text'];

// Claude API 키 읽기
$apiKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/apikey/claude_api.txt';
if (!file_exists($apiKeyFile)) {
    echo json_encode(['ok' => false, 'error' => 'Claude API 키 파일이 존재하지 않습니다.']);
    exit;
}

$apiKey = trim(file_get_contents($apiKeyFile));

// Claude API 요청 프롬프트
$promptText = <<<EOT
다음 음성 녹음을 텍스트로 변환한 내용을 회사 업무 기록용으로 요약해주세요.

**원본 텍스트:**
{$text}

**요약 지침:**
1. 회사 업무 기록에 적합한 형식으로 작성하세요
2. 중요한 내용, 결정사항, 액션 아이템을 명확히 정리하세요
3. 간결하고 명확하게 핵심만 요약하세요
4. 날짜, 시간, 담당자, 금액 등 중요한 정보는 빠뜨리지 마세요
5. 불필요한 대화나 중복 내용은 제거하세요

**응답 형식:**
제목과 본문을 포함한 정리된 요약문만 반환하세요. 추가 설명은 하지 마세요.
EOT;

// Claude API 호출
$apiUrl = 'https://api.anthropic.com/v1/messages';

$requestBody = [
    'model' => 'claude-3-5-haiku-20241022',
    'max_tokens' => 2048,
    'messages' => [
        [
            'role' => 'user',
            'content' => $promptText
        ]
    ]
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . $apiKey,
    'anthropic-version: 2023-06-01'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
$curlInfo = curl_getinfo($ch);
curl_close($ch);

// 디버깅 로그
error_log("Summary API Request - HTTP Code: {$httpCode}");
error_log("Summary API Response: " . substr($response, 0, 500));

if ($httpCode !== 200) {
    error_log("Claude API Error: HTTP {$httpCode} - {$response}");

    // 에러 상세 정보 파싱
    $errorDetails = 'API 호출 실패';
    if ($response) {
        $errorJson = json_decode($response, true);
        if (isset($errorJson['error']['message'])) {
            $errorDetails = $errorJson['error']['message'];
        } elseif (isset($errorJson['error']['type'])) {
            $errorDetails = $errorJson['error']['type'];
        }
    }

    echo json_encode([
        'ok' => false,
        'error' => "Claude API 호출 실패 (HTTP {$httpCode})",
        'details' => $errorDetails,
        'curl_error' => $curlError,
        'response' => $response,
        'url' => $curlInfo['url']
    ]);
    exit;
}

$apiResponse = json_decode($response, true);

if (!isset($apiResponse['content'][0]['text'])) {
    error_log("Claude API Response Format Error: " . $response);
    echo json_encode([
        'ok' => false,
        'error' => 'Claude API 응답 형식 오류',
        'raw_response' => $response
    ]);
    exit;
}

// Claude가 반환한 요약문 추출
$summary = $apiResponse['content'][0]['text'];

// 텍스트 정리 (앞뒤 공백 제거)
$summary = trim($summary);

// 성공 응답
echo json_encode([
    'ok' => true,
    'summary' => $summary
]);
