<?php
// check_sa_key_id.php
header('Content-Type: text/plain; charset=utf-8');

$path = $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json';
$cfg  = json_decode(file_get_contents($path), true);

$kid  = $cfg['private_key_id'] ?? null;
$url  = $cfg['client_x509_cert_url'] ?? null; // 서비스계정 공개키 목록 JSON

echo "private_key_id: $kid\n";
echo "certs_url     : $url\n";

if (!$kid || !$url) {
  exit("config 부족(type/service_account JSON 맞는지 확인)\n");
}

$resp = @file_get_contents($url);
if ($resp === false) {
  exit("공개 인증서 목록을 가져오지 못했습니다(네트워크/방화벽 확인)\n");
}
$data = json_decode($resp, true);

if (isset($data[$kid])) {
  echo "[OK] 구글 공개키 목록에 해당 key_id가 존재합니다.\n";
} else {
  echo "[FAIL] 공개키 목록에 현재 key_id가 없습니다. (키 삭제/불일치 의심)\n";
  echo " - GCP 콘솔에서 Keys 목록과 비교해 보세요.\n";
}
