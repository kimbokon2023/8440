<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// JSON 응답 헤더 설정
header("Content-Type: application/json");

// 세션 변수 초기화
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$id = $_SESSION["userid"] ?? '';

// 요청 파라미터 수신
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$name = $_REQUEST["name"] ?? '';
$part = $_REQUEST["part"] ?? '';
$registdate = $_REQUEST["registdate"] ?? '';
$item = $_REQUEST["item"] ?? '';
$askdatefrom = $_REQUEST["askdatefrom"] ?? '';
$askdateto = $_REQUEST["askdateto"] ?? '';
$usedday = $_REQUEST["usedday"] ?? '';
$content = $_REQUEST["content"] ?? '';
$state = $_REQUEST["state"] ?? '';

// DB 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        $sql = "select * from mirae8440.absent_office where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        error_log("absent_office 조회 오류: " . $ex->getMessage());
    }

    try {
        $pdo->beginTransaction();
        $sql = "update mirae8440.absent_office set id=?, name=?, registdate=?, item=?, askdatefrom=?, askdateto=?, usedday=?, content=?, state=?, part=?";
        $sql .= " where num=? LIMIT 1";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->bindValue(2, $name, PDO::PARAM_STR);
        $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
        $stmh->bindValue(4, $item, PDO::PARAM_STR);
        $stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);
        $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);
        $stmh->bindValue(7, $usedday, PDO::PARAM_STR);
        $stmh->bindValue(8, $content, PDO::PARAM_STR);
        $stmh->bindValue(9, $state, PDO::PARAM_STR);
        $stmh->bindValue(10, $part, PDO::PARAM_STR);
        $stmh->bindValue(11, $num, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("absent_office 수정 오류: " . $ex->getMessage());
    }
}

// 삽입 모드
if ($mode == "insert") {
    try {
        $pdo->beginTransaction();

        $sql = "insert into mirae8440.absent_office(id, name, registdate, item, askdatefrom, askdateto, usedday, content, state, part)";
        $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($name == '조경임' || $name == '김선영') {
            $state = '1차결재';
        } else {
            $state = '결재상신';
        }

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->bindValue(2, $name, PDO::PARAM_STR);
        $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
        $stmh->bindValue(4, $item, PDO::PARAM_STR);
        $stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);
        $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);
        $stmh->bindValue(7, $usedday, PDO::PARAM_STR);
        $stmh->bindValue(8, $content, PDO::PARAM_STR);
        $stmh->bindValue(9, $state, PDO::PARAM_STR);
        $stmh->bindValue(10, $part, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("absent_office 삽입 오류: " . $ex->getMessage());
    }
}

// 삭제 모드
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();

        $sql = "delete from mirae8440.absent_office where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("absent_office 삭제 오류: " . $ex->getMessage());
    }
}

// JSON 응답 데이터 생성
$data = array(
    "registdate" => $registdate,
    "state" => $state,
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>