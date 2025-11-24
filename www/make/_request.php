<?php
/**
 * 요청 파라미터 처리 및 초기화
 * 로컬 및 서버 환경 모두 지원
 */

// 공통 함수 로드
require_once __DIR__ . '/../common/functions.php';

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    return $default;
}

// 기본 변수 초기화
$num = getRequestValue("num", '');           // 번호
$search = getRequestValue("search", '');     // 검색어 (목록표에 제목, 이름 등 나오는 부분)
$find = getRequestValue("find", '');         // 검색 필드
$mode = getRequestValue("mode", 'not');      // 모드 (기본값: not)
$fromdate = getRequestValue("fromdate", ''); // 시작일
$todate = getRequestValue("todate", '');     // 종료일
$year = getRequestValue("year", '');         // 연도
$process = getRequestValue("process", '');   // 프로세스
$asprocess = getRequestValue("asprocess", ''); // AS 프로세스
$separate_date = getRequestValue("separate_date", ''); // 구분 날짜
$scale = getRequestValue("scale", '');       // 스케일
$page = getRequestValue("page", '1');        // 페이지 번호 (기본값: 1)
$text = getRequestValue("text", '');         // 텍스트 데이터

// 데이터베이스 연결
if (function_exists('includePath')) {
    require_once(includePath('lib/mydb.php'));
} else {
    require_once(__DIR__ . '/../lib/mydb.php');
}
$pdo = db_connect();

// 집계 데이터 배열
$sum = array();
?>