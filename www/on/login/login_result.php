<?php
require_once __DIR__ . '/../../bootstrap.php';

$id = $_POST["uid"] ?? '';
$pw = $_POST["upw"] ?? '';

if (empty($id) || empty($pw)) {
    header('Location: login_form.php?error=empty');
    exit;
}

try {
    // daon_member 테이블에서 사용자 조회
    $sql = "SELECT * FROM daon_member WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();

    if ($count < 1) {
        // 아이디가 없는 경우
        header('Location: login_form.php?error=invalid');
        exit;
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // 비밀번호 확인 (평문 비교 - 추후 해시로 변경 권장)
    if ($pw != $row["pass"]) {
        header('Location: login_form.php?error=invalid');
        exit;
    }

    // 계정 상태 확인
    if ($row["status"] != 'active') {
        header('Location: login_form.php?error=inactive');
        exit;
    }

    // 로그인 성공 - 세션 설정
    $_SESSION["daon_userid"] = $row["id"];
    $_SESSION["daon_name"] = $row["name"];
    $_SESSION["daon_nick"] = $row["nick"];
    $_SESSION["daon_level"] = $row["level"];
    $_SESSION["daon_part"] = $row["part"];
    $_SESSION["daon_position"] = $row["position"];
    $_SESSION["daon_hp"] = $row["hp"];
    $_SESSION["daon_email"] = $row["email"];
    $_SESSION["daon_company"] = $row["company"];

    // 마지막 로그인 시간 업데이트
    $updateSql = "UPDATE daon_member SET last_login = NOW() WHERE id = ?";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindValue(1, $id, PDO::PARAM_STR);
    $updateStmt->execute();

    // 로그인 로그 기록
    $logSql = "INSERT INTO daon_login_logs (user_id, ip_address, user_agent) VALUES (?, ?, ?)";
    $logStmt = $pdo->prepare($logSql);
    $logStmt->bindValue(1, $id, PDO::PARAM_STR);
    $logStmt->bindValue(2, $_SERVER['REMOTE_ADDR'] ?? 'unknown', PDO::PARAM_STR);
    $logStmt->bindValue(3, $_SERVER['HTTP_USER_AGENT'] ?? 'unknown', PDO::PARAM_STR);
    $logStmt->execute();

    // 로그인 성공 시 쿠키 설정
    setcookie("daon_user", $id, time() + 86400, "/"); // 1일 동안 유효

    // 저장된 돌아갈 URL이 있으면 해당 페이지로, 없으면 메인 페이지로
    $redirectUrl = $_SESSION['daon_return_url'] ?? '../index.php';
    unset($_SESSION['daon_return_url']); // 사용 후 제거

    header('Location: ' . $redirectUrl);
    exit;

} catch (PDOException $e) {
    error_log('Login Error: ' . $e->getMessage());
    header('Location: login_form.php?error=system');
    exit;
}
?>
