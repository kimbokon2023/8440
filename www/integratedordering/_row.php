<?php
// 통합 발주 시스템용 _row.php
// 원자재(request)와 부자재(request_etc)의 필드를 모두 포함

$num = $row["num"] ?? '';
$outdate = $row["outdate"] ?? '';
$requestdate = $row["requestdate"] ?? '';
$indate = $row["indate"] ?? '';

// 원자재: 현장명, 부자재: 물품명
$outworkplace = $row["outworkplace"] ?? '';

$steel_item = $row["steel_item"] ?? '';
$spec = $row["spec"] ?? '';
$steelnum = $row["steelnum"] ?? '';
$company = $row["company"] ?? '';
$supplier = $row["supplier"] ?? '';

$request_comment = $row["request_comment"] ?? '';
$which = $row["which"] ?? '';
$model = $row["model"] ?? '';
$first_writer = $row["first_writer"] ?? '';
$update_log = $row["update_log"] ?? '';
$suppliercost = $row["suppliercost"] ?? '';
$inventory = $row["inventory"] ?? '';
$status = $row["status"] ?? '';

// 부자재 전용 필드
$payment = $row["payment"] ?? '';

// 공통 필드
$author_id = $row["author_id"] ?? '';
$author = $row["author"] ?? '';
$registdate = $row["registdate"] ?? '';
$eworks_item = $row["eworks_item"] ?? '';

// 추가 필드 (필요시)
$stock = $row["stock"] ?? 0;
$cart = $row["cart"] ?? 0;
?>
