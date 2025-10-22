<?php
/**
 * work_voc 모듈 - 댓글 삽입 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 확인
session_start();

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';
$ripple_content = $_REQUEST["ripple_content"] ?? '';

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

try {
    $pdo->beginTransaction();
    $sql = "INSERT INTO mirae8440.voc_ripple(parent, id, name, nick, content, regist_day)
            VALUES(?, ?, ?, ?, ?, now())";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->bindValue(2, $_SESSION["userid"] ?? '', PDO::PARAM_STR);
    $stmh->bindValue(3, $_SESSION["name"] ?? '', PDO::PARAM_STR);
    $stmh->bindValue(4, $_SESSION["nick"] ?? '', PDO::PARAM_STR);
    $stmh->bindValue(5, $ripple_content, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();

    header("Location:" . getBaseUrl() . "/work_voc/view.php?num=$num&page=$page");

} catch (PDOException $Exception) {
    $pdo->rollBack();
    error_log("insert ripple error: " . $Exception->getMessage());
    echo "댓글 등록 중 오류: " . $Exception->getMessage();
    exit;
}
?>