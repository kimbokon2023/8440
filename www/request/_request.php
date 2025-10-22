<?php
// 요청 변수 초기화 (Null Coalescing Operator 사용)

// 검색 관련 변수
$search = $_REQUEST["search"] ?? '';

// 날짜 구분 (출고일/완료일)
$separate_date = $_REQUEST["separate_date"] ?? '1';

// 목록 표시 여부
$list = $_REQUEST["list"] ?? 0;

// 페이지네이션 설정
$page = isset($page) && is_numeric($page) ? $page : 1;
$scale = isset($scale) && is_numeric($scale) ? $scale : 50;
$page_scale = 20;   // 한 페이지당 표시될 페이지 수
$first_num = intval(($page - 1) * $scale);   // 리스트에 표시되는 게시글의 첫 순번

// 내 글만 보기
$mywrite = $_REQUEST["mywrite"] ?? '';

// 정렬 모드
$cursort = isset($_REQUEST["cursort"]) ? intval($_REQUEST["cursort"]) : 0;

// 입고완료 제외 체크
$done_check_val = $_REQUEST["done_check_val"] ?? '0';
$done_check = $_REQUEST["done_check"] ?? '';

// 날짜 범위
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

// 상세 조회용 번호
$num = $_REQUEST["num"] ?? '';

// 검색어
$find = $_REQUEST["find"] ?? '';

// 처리 상태
$process = $_REQUEST["process"] ?? '전체';
?>
