<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/session.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/load_header.php";

// 권한 체크 (레벨 5 이하만 접근)
if ($level > 5) {
    echo "<script>alert('접근 권한이 없습니다.'); history.back();</script>";
    exit;
}
?>