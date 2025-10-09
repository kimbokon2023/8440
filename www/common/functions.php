<?php
/**
 * 공통 함수 파일
 * 환경에 관계없이 일관된 인터페이스를 제공하는 함수들
 */

require_once __DIR__ . '/../config/environment.php';

/**
 * 환경별 URL 생성
 * @param string $path 경로
 * @return string 완전한 URL
 */
function url($path = '') {
    return getPath($path);
}

/**
 * 환경별 자산(assets) URL 생성
 * @param string $path 자산 경로 (css/style.css, js/script.js 등)
 * @return string 완전한 자산 URL
 */
function asset($path) {
    if (isLocal()) {
        // 로컬 환경에서는 상대 경로 사용 (도메인 제외)
        $baseUrl = getBaseUrl();
        $relativePath = str_replace($baseUrl, '', getPath($path));
        return $relativePath ?: '/' . ltrim($path, '/');
    } else {
        // 서버 환경에서는 절대 URL 사용
        return getPath($path);
    }
}

/**
 * 현재 환경이 로컬인지 확인
 * @return bool
 */
function isLocal() {
    return Environment::isLocal();
}

/**
 * 현재 환경이 서버인지 확인
 * @return bool
 */
function isServer() {
    return Environment::isServer();
}

/**
 * 환경별 리다이렉트
 * @param string $path 리다이렉트할 경로
 */
function redirect($path) {
    header("Location: " . getPath($path));
    exit;
}

/**
 * 환경별 JavaScript 파일 로드
 * @param string $path JS 파일 경로
 * @return string script 태그
 */
function js($path) {
    $url = getPath($path);
    return '<script src="' . $url . '"></script>';
}

/**
 * 환경별 CSS 파일 로드
 * @param string $path CSS 파일 경로
 * @return string link 태그
 */
function css($path) {
    $url = getPath($path);
    return '<link rel="stylesheet" href="' . $url . '">';
}

/**
 * 현재 페이지 URL 가져오기
 * @return string 현재 페이지의 전체 URL
 */
function currentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    return $protocol . '://' . $host . $uri;
}

/**
 * 환경별 에러 표시 설정
 */
function setupErrorReporting() {
    if (isLocal()) {
        // 로컬 환경: 모든 에러 표시
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
    } else {
        // 서버 환경: 에러 숨김, 로그만 기록
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        ini_set('log_errors', 1);
    }
}

/**
 * 디버그 정보 출력 (로컬 환경에서만)
 * @param mixed $data 출력할 데이터
 * @param string $label 레이블
 */
function debug($data, $label = 'DEBUG') {
    if (isLocal()) {
        echo '<pre style="background: #f5f5f5; border: 1px solid #ddd; padding: 10px; margin: 10px 0;">';
        echo '<strong>' . htmlspecialchars($label) . ':</strong>' . "\n";
        print_r($data);
        echo '</pre>';
    }
}

/**
 * 자산 파일 존재 여부 확인
 * @param string $path 자산 경로
 * @return bool 파일 존재 여부
 */
function assetExists($path) {
    $absolutePath = absolutePath($path);
    return file_exists($absolutePath);
}

/**
 * 자산 URL 생성 (파일 존재 여부 확인 포함)
 * @param string $path 자산 경로
 * @param string $fallback 대체 URL (파일이 없을 경우)
 * @return string 자산 URL
 */
function assetWithFallback($path, $fallback = '') {
    if (assetExists($path)) {
        return asset($path);
    } else {
        if (isLocal()) {
            debug("Asset file not found: " . $path, 'ASSET WARNING');
        }
        return $fallback ?: asset($path);
    }
}

/**
 * 환경 정보 가져오기
 * @return array 환경 정보 배열
 */
function getEnvironmentInfo() {
    return [
        'environment' => Environment::getCurrent(),
        'is_local' => Environment::isLocal(),
        'is_server' => Environment::isServer(),
        'base_url' => getBaseUrl(),
        'debug_mode' => isDebugMode()
    ];
}

/**
 * 환경별 Document Root 경로 반환
 * @return string Document Root 경로
 */
function getDocumentRoot() {
    // 물리 경로는 실행 파일 기준으로 계산하면 서버 구조(/www/<폴더명>)에도 안전하다
    $root = realpath(__DIR__ . '/..');
    return $root !== false ? $root : (__DIR__ . '/..');
}

/**
 * 환경별 절대 경로 생성
 * @param string $path 상대 경로
 * @return string 절대 경로
 */
function absolutePath($path = '') {
    $root = getDocumentRoot();
    $path = ltrim($path, '/');
    return $path ? $root . '/' . $path : $root;
}

/**
 * 환경별 require/include 경로 생성
 * @param string $path 상대 경로
 * @return string include용 절대 경로
 */
function includePath($path) {
    return absolutePath($path);
}

// 날짜 공백이나 null 등 돌려주는 함수 
function NullCheckDate($requestdate) {
  if ($requestdate != "0000-00-00") {
    $request_year = date("Y", strtotime($requestdate));
    if ($request_year < 2010) {
      $requestdate = null;
    } else {
      $requestdate = date("Y-m-d", strtotime($requestdate));
    }
  } else {
    $requestdate = "";
  }
  return $requestdate;
}
// 날짜 공백이나 null 등 돌려주는 함수 
function isNotNull($datestr) {
  if ($datestr != "0000-00-00" && $datestr != "" && $datestr != null ) {
    $request_year = date("Y", strtotime($datestr));
    if ($request_year < 2010) {
      $datestr = null;
    } else {
      $datestr = date("Y-m-d", strtotime($datestr));
    }
  } else {
    $datestr = "";
  }
  return $datestr;
}

function is_string_valid($str) {
    if (is_null($str) || !isset($str) || trim($str) === '') {
        return false;
    } else {
        return true;
    }
}

 function echo_null($str) {	
	$strval = ($str == "") ? "&nbsp;&nbsp;&nbsp;" : $str ;
	return $strval;		
}

function trans_date($tdate) {
  if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
		else $tdate="";							
	return $tdate;	
}


function conv_num($num) {
$number = (float)str_replace(',', '', $num);
return $number;
}

