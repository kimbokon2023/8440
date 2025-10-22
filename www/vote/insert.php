<?php
/**
 * Vote 모듈 - 투표 데이터 삽입/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? '';

// 권한 체크
if ($level > 8) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// JSON 응답 헤더 설정
header("Content-Type: application/json");

// 요청 변수 초기화
$timekey = $_REQUEST["timekey"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$num = $_REQUEST["num"] ?? '';
$is_html = $_REQUEST["is_html"] ?? '';
$noticecheck = $_REQUEST["noticecheck"] ?? '';
$status = $_REQUEST["status"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';
$searchtext = $_REQUEST["searchtext"] ?? '';
$deadline = $_REQUEST["deadline"] ?? '';

// 데이터베이스 연결
$pdo = db_connect();
$id = '';

try {
    if ($mode == "modify") {
        // 수정 모드
        $sql = "SELECT * FROM mirae8440.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        $pdo->beginTransaction();
        $sql = "UPDATE mirae8440.{$tablename} SET
                subject = ?, content = ?, is_html = ?,
                noticecheck = ?, searchtext = ?, status = ?, deadline = ?
                WHERE num = ?";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $noticecheck, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchtext, PDO::PARAM_STR);
        $stmh->bindValue(6, $status, PDO::PARAM_STR);
        $stmh->bindValue(7, $deadline, PDO::PARAM_STR);
        $stmh->bindValue(8, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();

        $id = $num;

    } else {
        // 신규 등록 모드
        if ($is_html == "y") {
            $content = htmlspecialchars($content);
        }

        $pdo->beginTransaction();
        $sql = "INSERT INTO mirae8440.{$tablename}
                (id, name, nick, subject, content, is_html,
                 noticecheck, searchtext, status, regist_day, deadline)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmh->bindValue(2, $user_name, PDO::PARAM_STR);
        $stmh->bindValue(3, $_SESSION["nick"] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $noticecheck, PDO::PARAM_STR);
        $stmh->bindValue(8, $searchtext, PDO::PARAM_STR);
        $stmh->bindValue(9, $status, PDO::PARAM_STR);
        $stmh->bindValue(10, $deadline, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();

        // 새로 생성된 레코드의 num 추출
        $sql = "SELECT * FROM mirae8440.{$tablename} ORDER BY num DESC LIMIT 1";
        $stmh = $pdo->query($sql);
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"] ?? '';
        $id = $num;

        // 첨부파일 parentid 업데이트
        if (!empty($timekey)) {
            $pdo->beginTransaction();
            $sql = "UPDATE mirae8440.fileuploads SET parentid = ? WHERE parentid = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $id, PDO::PARAM_STR);
            $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
        }
    }

} catch (PDOException $Exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $error_message = "데이터베이스 오류: " . $Exception->getMessage();
    error_log($error_message);

    echo json_encode([
        'status' => 'error',
        'message' => $error_message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 성공 응답
$data = [
    'status' => 'success',
    'num' => $num,
    'id' => $id,
    'tablename' => $tablename
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>