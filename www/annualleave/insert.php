<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$name = $_REQUEST["name"] ?? '';
$part = $_REQUEST["part"] ?? '';
$dateofentry = $_REQUEST["dateofentry"] ?? '';
$referencedate = $_REQUEST["referencedate"] ?? '';
$availableday = $_REQUEST["availableday"] ?? '';
$comment = $_REQUEST["comment"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        $sql = "select * from mirae8440.almember where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        error_log("almember 조회 오류: " . $ex->getMessage());
    }

    try {
        $pdo->beginTransaction();
        $sql = "update mirae8440.almember set name=?, part=?, dateofentry=?, referencedate=?, availableday=?, comment=?";
        $sql .= " where num=? LIMIT 1";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $name, PDO::PARAM_STR);
        $stmh->bindValue(2, $part, PDO::PARAM_STR);
        $stmh->bindValue(3, $dateofentry, PDO::PARAM_STR);
        $stmh->bindValue(4, $referencedate, PDO::PARAM_STR);
        $stmh->bindValue(5, $availableday, PDO::PARAM_STR);
        $stmh->bindValue(6, $comment, PDO::PARAM_STR);
        $stmh->bindValue(7, $num, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("almember 수정 오류: " . $ex->getMessage());
    }
}

// 삽입 모드
if ($mode == "insert") {
    try {
        $pdo->beginTransaction();

        $sql = "insert into mirae8440.almember(name, part, dateofentry, referencedate, availableday, comment)";
        $sql .= " values(?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $name, PDO::PARAM_STR);
        $stmh->bindValue(2, $part, PDO::PARAM_STR);
        $stmh->bindValue(3, $dateofentry, PDO::PARAM_STR);
        $stmh->bindValue(4, $referencedate, PDO::PARAM_STR);
        $stmh->bindValue(5, $availableday, PDO::PARAM_STR);
        $stmh->bindValue(6, $comment, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("almember 삽입 오류: " . $ex->getMessage());
    }
}

// 삭제 모드
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();

        $sql = "delete from mirae8440.almember where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("almember 삭제 오류: " . $ex->getMessage());
    }
}

// JSON 응답 데이터 생성
$data = array(
    "dateofentry" => $dateofentry,
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
