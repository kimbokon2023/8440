<?php
/**
 * 시공완료일 업데이트 처리
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';
$doneday = $_REQUEST["doneday"] ?? '';
$workday = $_REQUEST["workday"] ?? '';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';

// workday 값 검증 및 기본값 설정 (빈 문자열, "0000-00-00", "1970-01-01" 중 하나라도 해당하면 doneday로 대체)
if (empty($workday) || $workday === "0000-00-00" || $workday === "1970-01-01") {
    $workday = $doneday;
}

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
    $sql = "update mirae8440.work set doneday=?, workday=?, update_log=? where num=? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $doneday, PDO::PARAM_STR);
    $stmh->bindValue(2, $workday, PDO::PARAM_STR);
    $stmh->bindValue(3, $update_log, PDO::PARAM_STR);
    $stmh->bindValue(4, $num, PDO::PARAM_STR);

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
        alert('시공완료 처리되었습니다.');
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
?>