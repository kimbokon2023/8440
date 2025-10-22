<?php
require_once __DIR__ . '/bootstrap.php';

// 데이터베이스 초기화 (bootstrap.php에서 이미 처리되지만 명시적으로 확인)
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 조회할 알림 번호
$num = 1;

// 응답 배열 초기화
$response = array();

try {
    // 알림 데이터 조회
    $sql = "select * from " . $DB . ".alert where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();

    // 결과 가져오기
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    // 응답 배열 구성 (null safe)
    if ($row) {
        $response = array(
            'voc_alert' => $row["voc_alert"] ?? '',
            'ma_alert' => $row["ma_alert"] ?? '',
            'order_alert' => $row["order_alert"] ?? ''
        );
    } else {
        $response = array(
            'voc_alert' => '',
            'ma_alert' => '',
            'order_alert' => ''
        );
    }
} catch (PDOException $Exception) {
    // 에러 응답
    $response = array(
        'error' => $Exception->getMessage(),
        'voc_alert' => '',
        'ma_alert' => '',
        'order_alert' => ''
    );
}

// JSON 응답 반환
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
