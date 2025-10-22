<?php
/**
 * RnDfund 요청 변수 초기화
 * 로컬 및 서버 환경 모두 지원
 */

// 요청 변수 초기화 (?? '' 패턴 사용)
$num = $_REQUEST["num"] ?? '';
$proDate = $_REQUEST["proDate"] ?? '';
$writer = $_REQUEST["writer"] ?? '';
$amount = $_REQUEST["amount"] ?? '';
$memo = $_REQUEST["memo"] ?? '';
$which = $_REQUEST["which"] ?? '';
$item = $_REQUEST["item"] ?? '';
$comment = $_REQUEST["comment"] ?? '';

?>	
