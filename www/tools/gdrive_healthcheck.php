<?php
/**
 * Google Drive Service Account Health Check
 * - 점검 항목: 경로/권한/JSON 파싱/type/개행/토큰 발급/간단 API 호출
 * - 저장 위치 예: /mirae8440/www/tools/gdrive_healthcheck.php
 */

header('Content-Type: text/plain; charset=utf-8');

$start = microtime(true);

// ==== 1) 환경 설정 ====
$DOCROOT = isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] : __DIR__;
$CREDENTIAL_PATH = $DOCROOT . '/tokens/mytoken.json';   // ← 사용 중인 서비스계정 JSON 경로
$SCOPES = ['https://www.googleapis.com/auth/drive.file']; // 필요 시 조정: drive, drive.readonly, drive.file 등
$SUBJECT = null; // 도메인 위임 사용 시 'user@yourdomain.com' 지정, 아니면 null

// ==== 2) 공통 유틸 ====
function ok($msg){ echo "[ OK ] $msg\n"; }
function ng($msg){ echo "[FAIL] $msg\n"; }
function info($msg){ echo "[INFO] $msg\n"; }

// ==== 3) PHP/확장 체크 ====
info("PHP version: ".PHP_VERSION);
if (version_compare(PHP_VERSION, '7.2', '<')) { ng('PHP 7.2 이상 권장'); }
if (!extension_loaded('json')) { ng('json 확장 미로딩'); exit; }
if (!extension_loaded('openssl')) { ng('openssl 확장 미로딩'); exit; }
ok('필수 확장(json/openssl) 확인');

// ==== 4) Composer Autoload 로딩 ====
$autoloadCandidates = [
  $DOCROOT.'/vendor/autoload.php',
  __DIR__.'/vendor/autoload.php',
];
$autoloadLoaded = false;
foreach ($autoloadCandidates as $cand) {
  if (is_file($cand)) { require_once $cand; $autoloadLoaded = true; ok("autoload 로딩: $cand"); break; }
}
if (!$autoloadLoaded) { ng('vendor/autoload.php 를 찾지 못했습니다. 프로젝트 루트 경로를 확인하세요.'); exit; }

// 네임스페이스 클래스 사용
use Google\Client;
use Google\Service\Drive;

// ==== 5) 자격증명 파일 점검 ====
info("DOCROOT: $DOCROOT");
info("CREDENTIAL_PATH: $CREDENTIAL_PATH");

if (!file_exists($CREDENTIAL_PATH)) { ng('자격증명 파일이 존재하지 않습니다.'); exit; }
ok('자격증명 파일 존재');

if (!is_readable($CREDENTIAL_PATH)) { ng('자격증명 파일을 읽을 수 없습니다(권한 확인).'); exit; }
ok('자격증명 파일 읽기 권한 확인');

$raw = file_get_contents($CREDENTIAL_PATH);
$sha = hash('sha256', $raw);
info("credential sha256: $sha");

$cfg = json_decode($raw, true);
if (!$cfg) { ng('JSON 파싱 실패: 파일 내용이 손상되었을 수 있습니다.'); exit; }
ok('JSON 파싱 성공');

// type 확인
if (!isset($cfg['type']) || $cfg['type'] !== 'service_account') {
  ng('자격증명 type이 service_account 가 아닙니다. GCP에서 서비스계정 키(JSON)인지 확인하세요.');
  info('현재 type: '.($cfg['type'] ?? 'N/A'));
  exit;
}
ok('type: service_account 확인');

// private_key 개행 보정
if (isset($cfg['private_key'])) {
  $before = $cfg['private_key'];
  $cfg['private_key'] = str_replace(["\\n", "\r\n"], "\n", $cfg['private_key']);
  if ($before !== $cfg['private_key']) {
    info('private_key 내 개행(\\n) → 실제 줄바꿈으로 보정 적용');
  }
} else {
  ng('private_key 필드가 없습니다. 키 파일이 손상되었을 수 있습니다.');
  exit;
}
ok('private_key 확인');

// ==== 6) 토큰 발급 시도 ====
try {
  $client = new Client();
  $client->setAuthConfig($cfg);
  $client->setScopes($SCOPES);
  if ($SUBJECT) { $client->setSubject($SUBJECT); } // 도메인 위임 필요시에만

  $token = $client->fetchAccessTokenWithAssertion(); // JWT 서명 + 토큰 발급
  if (isset($token['access_token'])) {
    ok('토큰 발급 성공 (JWT 서명 정상)');
    info('expires_in: '.($token['expires_in'] ?? 'N/A'));
  } else {
    ng('토큰 발급 실패 (fetchAccessTokenWithAssertion 반환값 확인 필요)');
    print_r($token);
    exit;
  }
} catch (Throwable $e) {
  ng('토큰 발급 예외: '.$e->getMessage());
  // 스택이 필요하면 아래 주석 해제
  // echo $e;
  exit;
}

// ==== 7) Drive API 간단 호출 ====
try {
  $drive = new Drive($client);
  // 사용자/스토리지 정보만 요청(권한 적음)
  $about = $drive->about->get(['fields' => 'user(displayName,emailAddress),storageQuota(usage,limit)']);
  ok('Drive API 호출 성공');
  info('user.displayName: '.($about->getUser()['displayName'] ?? 'N/A'));
  info('user.emailAddress: '.($about->getUser()['emailAddress'] ?? 'N/A'));
  $q = $about->getStorageQuota();
  info('storage usage/limit: '.(($q['usage'] ?? 'N/A').'/'.($q['limit'] ?? 'N/A')));
} catch (Throwable $e) {
  ng('Drive API 호출 실패: '.$e->getMessage());
  info('스코프 또는 드라이브 접근 권한(공유드라이브/폴더 공유) 문제일 수 있습니다.');
}

// ==== 8) 시간 오차 힌트 ====
$serverTime = time();
info('server time (epoch): '.$serverTime.' ('.date('c',$serverTime).')');
info('※ 서버 시간이 NTP와 5분 이상 차이나면 invalid_grant 류 오류가 발생할 수 있습니다.');

// ==== 9) 완료 ====
$elapsed = round((microtime(true)-$start)*1000);
info("Done in {$elapsed} ms");
