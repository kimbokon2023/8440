<?php
/**
 * 실측일(measureday) 업데이트 처리 스크립트
 * work 테이블의 실측일을 업데이트하고 로그를 기록합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$user_name = $_SESSION['name'] ?? '';
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$measureday = $_REQUEST["measureday"] ?? '';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';

// 변수 초기화
$update_log = '';

// 입력 검증
if (empty($num)) {
    error_log("file_process.php error: No num specified");
    die("레코드 번호가 지정되지 않았습니다.");
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 기존 update_log 조회
    $sql = "SELECT * FROM {$DB}.work WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $update_log = $row["update_log"] ?? '';
    } else {
        throw new Exception("해당 레코드를 찾을 수 없습니다.");
    }
    
} catch (PDOException $ex) {
    error_log("DB select error in file_process.php: " . $ex->getMessage());
    die("데이터 조회 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
}

// 로그 데이터 생성
$data = date("Y-m-d H:i:s") . " - " . $user_name . " ";
$update_log = $data . $update_log . "&#10"; // 개행문자 Textarea

try {
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    $sql = "UPDATE {$DB}.work SET measureday = ?, update_log = ? WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $measureday, PDO::PARAM_STR);
    $stmh->bindValue(2, $update_log, PDO::PARAM_STR);
    $stmh->bindValue(3, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("DB update error in file_process.php: " . $ex->getMessage());
    die("데이터 업데이트 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
}

// 로컬/서버 환경에 따른 동적 리다이렉션
$baseUrl = getBaseUrl();
$redirectUrl = $baseUrl . "/p/view.php?" . http_build_query(array(
    'num' => $num,
    'check' => $check
));

header("Location: " . $redirectUrl);
exit;

?>