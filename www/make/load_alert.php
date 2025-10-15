<?php
/**
 * 알림 데이터 로드
 * 로컬 및 서버 환경 모두 지원
 */

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 변수 초기화
$num = 1;  // 알림 번호 (고정)
$alerts = '';  // 알림 내용

// 알림 데이터 조회
try {
    $sql = "SELECT * FROM mirae8440.alert WHERE num = ?";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $alerts = $row["alert"] ?? '';
    }
    
} catch (PDOException $ex) {
    error_log("알림 로드 오류: " . $ex->getMessage());
    $alerts = '';  // 에러 발생 시 빈 값
}
?>

<script>
'use strict';

// PHP에서 전달받은 알림 데이터
var alerts = <?php echo json_encode($alerts, JSON_UNESCAPED_UNICODE); ?>;

// jQuery 안전성 체크 후 값 설정
if (typeof $ !== 'undefined') {
    $("#alerts").val(alerts);
    // console.log('알림 데이터:', alerts);
} else {
    console.warn('jQuery가 로드되지 않았습니다.');
}
</script>