<?php
/**
 * work_voc 모듈 - 확인완료 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 확인
session_start();

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';
$option = $_REQUEST["option"] ?? '';

// 권한 체크
if (!isset($_SESSION["userid"])) {
    echo '<script>
        alert("로그인 후 이용해 주세요.");
        history.back();
    </script>';
    exit;
}

// 데이터베이스 연결
$pdo = db_connect();

$is_html = "2"; // 확인완료 상태

try {
    $pdo->beginTransaction();
    $sql = "UPDATE mirae8440.voc SET is_html = ? WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $is_html, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    error_log("check_done error: " . $Exception->getMessage());
    echo "오류: " . $Exception->getMessage();
    exit;
}

if ($option == 1) {
    header("Location:" . getBaseUrl() . "/work_voc/view_temp.php?num=$num&page=$page");
} else {
    header("Location:" . getBaseUrl() . "/work_voc/view.php?num=$num&page=$page");
}
?>