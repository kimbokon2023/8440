<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 최신 연차 신청 데이터 조회 후 상세 페이지로 리다이렉트
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 최신 데이터 번호 초기화
$num = '';

try {
    // 최신 연차 신청 데이터 조회
    $sql = "select * from " . $DB . ".eworks order by num desc limit 1";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    // 데이터가 있으면 번호 추출
    if ($row) {
        $num = $row["num"] ?? '';
    }
} catch (PDOException $Exception) {
    // 에러 로그 기록
    error_log("데이터 조회 오류: " . $Exception->getMessage());
    
    // 에러 발생 시 목록 페이지로 이동
    header("Location:" . getBaseUrl() . "/request/");
    exit;
}

// 데이터가 있으면 상세 페이지로, 없으면 목록 페이지로 리다이렉트
if ($num) {
    header("Location:" . getBaseUrl() . "/request/view.php?num=" . $num);
} else {
    header("Location:" . getBaseUrl() . "/request/");
}
exit;
?>
