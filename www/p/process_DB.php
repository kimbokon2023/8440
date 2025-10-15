<?php
/**
 * 실측일 업데이트 처리
 * 로컬 및 서버 환경 모두 지원
 */
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';
$measureday = $_REQUEST["measureday"] ?? '';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';

require_once "../lib/mydb.php";
$pdo = db_connect();

try {
    $sql = "select * from mirae8440.work where num=?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();

    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $update_log = $row["update_log"] ?? '';
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$session_name = $_SESSION["name"] ?? '';
$data = date("Y-m-d H:i:s") . " - " . $session_name . " ";
$update_log = $data . $update_log . "&#10"; // 개행문자 Textarea

try {
    $pdo->beginTransaction();
    $sql = "update mirae8440.work set measureday=?, update_log=? where num=? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $measureday, PDO::PARAM_STR);
    $stmh->bindValue(2, $update_log, PDO::PARAM_STR);
    $stmh->bindValue(3, $num, PDO::PARAM_STR);

    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    print "오류: " . $Exception->getMessage();
}

// 업데이트 완료 후 리다이렉트 처리
if (isset($_REQUEST['from_view']) && $_REQUEST['from_view'] == '1') {
    // view.php에서 온 경우: 부모 창 갱신 후 현재 페이지로 돌아가기
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
</head>
<body>
<script>
    // 부모 창이 있으면 갱신 (팝업인 경우)
    if (window.opener && !window.opener.closed) {
        window.opener.location.reload();
        window.location.href = 'view.php?num=$num&check=$check';
    } else {
        // 일반 페이지에서 온 경우: 메시지 후 index.php로 이동
        alert('실측완료 처리되었습니다.');
        window.location.href = 'index.php?check=$check';
    }
</script>
</body>
</html>";
} else {
    // 다른 곳에서 온 경우: 기존 로직 유지
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
</head>
<body>
<script>
    if (window.opener && !window.opener.closed) {
        window.opener.location.reload();
    }
    window.location.href = 'view.php?num=$num&check=$check';
</script>
</body>
</html>";
}
exit;
