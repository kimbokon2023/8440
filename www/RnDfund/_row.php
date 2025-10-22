<?php
/**
 * RnDfund 행 데이터 변수 초기화
 * 로컬 및 서버 환경 모두 지원
 */

// 행 데이터 변수 초기화 (?? '' 패턴 사용)
$num = $row["num"] ?? '';
$proDate = $row["proDate"] ?? '';
$writer = $row["writer"] ?? '';
$amount = $row["amount"] ?? '';
$memo = $row["memo"] ?? '';
$which = $row["which"] ?? '';
$item = $row["item"] ?? '';
$comment = $row["comment"] ?? '';

?>