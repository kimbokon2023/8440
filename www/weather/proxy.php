<?php
// weather/proxy.php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

$baseUrl = 'https://apis.data.go.kr/1360000/AsosHourlyInfoService/getWthrDataList';
parse_str($_SERVER['QUERY_STRING'], $qs);
$qs['dataType'] = 'JSON';
$url = $baseUrl . '?' . http_build_query($qs);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error'=>'프록시 호출 실패','status'=>$httpCode]);
    exit;
}

// 여기서 HTML이 아니라 순수 JSON만 echo 되어야 합니다
echo $response;
