<?php
/**
 * 기타 요청 공통 변수 초기화
 * 
 * 원자재 구매 요청(기타) 관련 페이지에서 공통으로 사용하는 변수 초기화
 */

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
$first_num = ($page - 1) * $scale;   // 리스트에 표시되는 게시글의 첫 순번

// 모드
$mode = $_REQUEST["mode"] ?? '';

// 정렬 모드
$cursort = isset($_REQUEST["cursort"]) ? intval($_REQUEST["cursort"]) : 0;

// 입고완료 제외 체크
$done_check_val = $_REQUEST["done_check_val"] ?? '0';
$done_check = $_REQUEST["done_check"] ?? '';

// 날짜 범위
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

// insert.php에서 사용하는 추가 변수들
$which = $_REQUEST["which"] ?? '';
$outdate = $_REQUEST["outdate"] ?? '';
$requestdate = $_REQUEST["requestdate"] ?? '';
$indate = $_REQUEST["indate"] ?? '';
$outworkplace = $_REQUEST["outworkplace"] ?? '';
$steel_item = $_REQUEST["steel_item"] ?? '';
$spec = $_REQUEST["spec"] ?? '';
$steelnum = $_REQUEST["steelnum"] ?? '';
$company = $_REQUEST["company"] ?? '';
$request_comment = $_REQUEST["request_comment"] ?? '';
$payment = $_REQUEST["payment"] ?? '';
$supplier = $_REQUEST["supplier"] ?? '';
$first_writer = $_REQUEST["first_writer"] ?? '';
$update_log = $_REQUEST["update_log"] ?? '';

// 원자재 목록 관련 변수
$rowNum = $_REQUEST["rowNum"] ?? 0;
$steelsource_item = $_REQUEST["steelsource_item"] ?? array();
$steelsource_spec = $_REQUEST["steelsource_spec"] ?? array();
?>
