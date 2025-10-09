<?php
// 검색어 초기화
$search = $_REQUEST["search"] ?? '';

// 버튼 클릭시 읽는 값
$check = $_REQUEST["check"] ?? 0;

// 도면 미설계List
$check_draw = $_REQUEST["check_draw"] ?? ($_POST["check_draw"] ?? '0');

// 미출고 리스트
$output_check = $_REQUEST["output_check"] ?? ($_POST["output_check"] ?? '0');

// 시공팀미지정
$team_check = $_REQUEST["team_check"] ?? ($_POST["team_check"] ?? '0');

// 출고예정
$plan_output_check = $_REQUEST["plan_output_check"] ?? ($_POST["plan_output_check"] ?? '0');

// 측정
$measure_check = $_REQUEST["measure_check"] ?? ($_POST["measure_check"] ?? '0');
?>
