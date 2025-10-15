<?php
// 공통 요청 파라미터 초기화
// PHP 7.3 호환성을 위한 NULL 병합 연산자 사용

// $firstItem이 정의되지 않은 경우를 대비한 초기화
$firstItem = $firstItem ?? '';

// 검색 관련 변수
$Bigsearch = $_REQUEST["Bigsearch"] ?? $firstItem;   // 목록표에 제목,이름 등 나오는 부분
$search = $_REQUEST["search"] ?? "";
$find = $_REQUEST["find"] ?? "";
$separate_date = $_REQUEST["separate_date"] ?? "";

// 목록 및 페이징 관련 변수
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;
$scale = $_REQUEST["scale"] ?? 50;

// 페이지 설정
$page_scale = 20;   // 한 페이지당 표시될 페이지 수  10페이지
$first_num = intval(($page - 1) * $scale);   // 리스트에 표시되는 게시글의 첫 순번.

// 모드 및 작성자 관련 변수
$mywrite = $_REQUEST["mywrite"] ?? "";
$mode = $_REQUEST["mode"] ?? "";

// 날짜 관련 변수
if ($separate_date == "") {
    $separate_date = "1";
}
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";

// 기타 변수
$num = $_REQUEST["num"] ?? "";
$process = $_REQUEST["process"] ?? "전체";

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

?>