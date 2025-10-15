<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

header("Content-Type: application/json");

// 세션 변수 초기화
$userid = $_SESSION["userid"] ?? '';
$name = $_SESSION["name"] ?? '';
$nick = $_SESSION["nick"] ?? '';
$DB = $_SESSION["DB"] ?? '';

// 요청 파라미터 초기화
$timekey = $_REQUEST["timekey"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$is_html = $_REQUEST["is_html"] ?? '';
$noticecheck = $_REQUEST["noticecheck"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';
$division = $_REQUEST["division"] ?? '';
$searchtext = $_REQUEST["searchtext"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        $sql = "select * from mirae8440." . $tablename . " where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        error_log($tablename . " 조회 오류: " . $ex->getMessage());
    }

    try {
        $pdo->beginTransaction();
        $sql = "update mirae8440." . $tablename . " set subject=?, content=?, is_html=?, division=?, searchtext=? where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $division, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchtext, PDO::PARAM_STR);
        $stmh->bindValue(6, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log($tablename . " 수정 오류: " . $ex->getMessage());
    }
} else {
    // 삽입 모드
    if ($is_html == "y") {
        $content = htmlspecialchars($content);
    }

    try {
        $pdo->beginTransaction();
        $sql = "insert into mirae8440." . $tablename . " (id, name, nick, subject, content, regist_day, hit, is_html, division, searchtext)";
        $sql .= " values(?, ?, ?, ?, ?, now(), 0, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $userid, PDO::PARAM_STR);
        $stmh->bindValue(2, $name, PDO::PARAM_STR);
        $stmh->bindValue(3, $nick, PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $division, PDO::PARAM_STR);
        $stmh->bindValue(8, $searchtext, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log($tablename . " 삽입 오류: " . $ex->getMessage());
    }
}

// 신규데이터인 경우 num을 추출한 후 view로 보여주기
if ($mode !== "modify") {
    $sql = "select * from mirae8440." . $tablename . " order by num asc";

    try {
        $stmh = $pdo->query($sql);
        $rowNum = $stmh->rowCount();
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $num = $row["num"];
        }
    } catch (PDOException $ex) {
        error_log($tablename . " num 조회 오류: " . $ex->getMessage());
    }

    // 신규데이터인 경우 첨부파일/첨부이미지 추가한 것이 있으면 parentid 변경해줌
    $id = $num;

    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . ".picuploads set parentnum=? where parentnum=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("picuploads 업데이트 오류: " . $ex->getMessage());
    }
}

// JSON 응답 데이터 생성
$data = [
    'num' => $num,
    'tablename' => $tablename
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>

