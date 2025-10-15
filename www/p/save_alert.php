<?php
/**
 * 알람 설정 업데이트 처리
 * 로컬 및 서버 환경 모두 지원
 */

// 요청 변수 초기화 (?? '' 형태)
$choice = $_REQUEST["choice"] ?? '';
$num = 1;

require_once "../lib/mydb.php";
$pdo = db_connect();

// 알람 설정/해제 처리
if ($choice == '2') {
    $alerts = 0; // 알람해제
} else {
    $alerts = 1; // 알람설정
}

try {
    $pdo->beginTransaction();
    $sql = "update mirae8440.alert set alert=? where num=? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $alerts, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);

    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    print "오류: " . $Exception->getMessage();
}
?>