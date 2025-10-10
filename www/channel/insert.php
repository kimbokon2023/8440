<?php
require_once __DIR__ . '/../bootstrap.php';
include includePath('session.php');

header("Content-Type: application/json"); // JSON을 사용하기 위해 필요한 구문

// Initialize request variables
$timekey = $_REQUEST["timekey"] ?? ''; // 신규데이터에 생성할때 임시저장키
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$is_html = $_REQUEST["is_html"] ?? ''; // checkbox는 체크해야 변수명 전달됨
$noticecheck = $_REQUEST["noticecheck"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';
$division = $_REQUEST["division"] ?? '';
$searchtext = $_REQUEST["searchtext"] ?? '';

// Initialize session variables
$DB = $_SESSION["DB"] ?? 'mirae8440';
$userid = $_SESSION["userid"] ?? '';
$name = $_SESSION["name"] ?? '';
$nick = $_SESSION["nick"] ?? '';
   
        
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

if ($mode == "modify") {
    // 기존 레코드 조회
    try {
        $sql = "SELECT * FROM mirae8440." . $tablename . " WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }

    // 레코드 업데이트
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE mirae8440." . $tablename . " SET subject=?, content=?, is_html=?, division=?, searchtext=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $division, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchtext, PDO::PARAM_STR);
        $stmh->bindValue(6, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
} else {
    // HTML이 활성화된 경우
    if ($is_html == "y") {
        $content = htmlspecialchars($content);
    }

    // 신규 레코드 삽입
    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO mirae8440." . $tablename . " (id, name, nick, subject, content, regist_day, hit, is_html, division, searchtext) ";
        $sql .= "VALUES(?, ?, ?, ?, ?, now(), 0, ?, ?, ?)";
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
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}   
   
if ($mode !== "modify") {
    // 신규데이터인경우 num을 추출한 후 view로 보여주기
    $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num ASC";

    try {
        $stmh = $pdo->query($sql);
        $rowNum = $stmh->rowCount();
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $num = $row["num"] ?? '';
        }
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }

    // 신규데이터인 경우 첨부파일/첨부이미지 추가한 것이 있으면 parentid 변경해줌
    $id = $num;

    try {
        $pdo->beginTransaction();
        $sql = "UPDATE " . $DB . ".picuploads SET parentnum=? WHERE parentnum=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

// JSON 응답 반환
$data = [
    'num' => $num,
    'tablename' => $tablename
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>

