<?php
require_once __DIR__ . '/../bootstrap.php';

// 응답 헤더 설정
header('Content-Type: text/html; charset=utf-8');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 변수 초기화
$name = $_REQUEST["name"] ?? '';
$score = $_REQUEST["score"] ?? 0;

// 데이터 유효성 검사
if (empty($name) || empty($score)) {
    header("Location:" . getBaseUrl() . "/tetris/index.php?error=invalid_data");
    exit;
}

// 데이터 신규 등록
$rec_date = date("Y-m-d H:i:s");

try {
    $pdo->beginTransaction();

    // SQL 준비
    $sql = "insert into " . $DB . ".tetris(";
    $sql .= "name, rec_date, score";
    $sql .= ") ";
    $sql .= "values(?, ?, ?)";

    // SQL 실행
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $name, PDO::PARAM_STR);
    $stmh->bindValue(2, $rec_date, PDO::PARAM_STR);
    $stmh->bindValue(3, $score, PDO::PARAM_INT);

    $stmh->execute();
    $pdo->commit();

    // 성공 시 리다이렉트
    header("Location:" . getBaseUrl() . "/tetris/index.php?new=yes");
    exit;
} catch (PDOException $Exception) {
    $pdo->rollBack();
    
    // 에러 로그 기록
    error_log("테트리스 점수 등록 오류: " . $Exception->getMessage());
    
    // 에러 메시지 출력
    echo "<!DOCTYPE html>";
    echo "<html lang='ko'>";
    echo "<head>";
    echo "<meta charset='utf-8'>";
    echo "<title>오류 발생</title>";
    echo "</head>";
    echo "<body>";
    echo "<h3>점수 등록 중 오류가 발생했습니다.</h3>";
    echo "<p>오류 내용: " . htmlspecialchars($Exception->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><a href='" . getBaseUrl() . "/tetris/index.php'>돌아가기</a></p>";
    echo "</body>";
    echo "</html>";
}
?>
