<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? 'Unknown';
$user_id = $_SESSION["userid"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

include getDocumentRoot() . '/common.php';

// 요청 변수 안전하게 초기화
$timekey = $_REQUEST["timekey"] ?? '';   // 신규데이터에 생성할때 임시저장키
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

// 기본 항목 불러옴
include '_request.php';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE mirae8440." . $tablename . " SET parentID=?, subject=?, regist_day=?, check0=?, check1=?, check2=?, check3=?, check4=?, check5=?, check6=?, check7=?, check8=?, check9=?, writer=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $parentID, PDO::PARAM_STR);
        $stmh->bindValue(2, $subject, PDO::PARAM_STR);
        $stmh->bindValue(3, $regist_day, PDO::PARAM_STR);
        $stmh->bindValue(4, $check0, PDO::PARAM_STR);
        $stmh->bindValue(5, $check1, PDO::PARAM_STR);
        $stmh->bindValue(6, $check2, PDO::PARAM_STR);
        $stmh->bindValue(7, $check3, PDO::PARAM_STR);
        $stmh->bindValue(8, $check4, PDO::PARAM_STR);
        $stmh->bindValue(9, $check5, PDO::PARAM_STR);
        $stmh->bindValue(10, $check6, PDO::PARAM_STR);
        $stmh->bindValue(11, $check7, PDO::PARAM_STR);
        $stmh->bindValue(12, $check8, PDO::PARAM_STR);
        $stmh->bindValue(13, $check9, PDO::PARAM_STR);
        $stmh->bindValue(14, $writer, PDO::PARAM_STR);
        $stmh->bindValue(15, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
} else {
    // 신규 등록 모드
    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO mirae8440." . $tablename . " (parentID, subject, regist_day, check0, check1, check2, check3, check4, check5, check6, check7, check8, check9, writer) ";
        $sql .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $parentID, PDO::PARAM_STR);
        $stmh->bindValue(2, $subject, PDO::PARAM_STR);
        $stmh->bindValue(3, $regist_day, PDO::PARAM_STR);
        $stmh->bindValue(4, $check0, PDO::PARAM_STR);
        $stmh->bindValue(5, $check1, PDO::PARAM_STR);
        $stmh->bindValue(6, $check2, PDO::PARAM_STR);
        $stmh->bindValue(7, $check3, PDO::PARAM_STR);
        $stmh->bindValue(8, $check4, PDO::PARAM_STR);
        $stmh->bindValue(9, $check5, PDO::PARAM_STR);
        $stmh->bindValue(10, $check6, PDO::PARAM_STR);
        $stmh->bindValue(11, $check7, PDO::PARAM_STR);
        $stmh->bindValue(12, $check8, PDO::PARAM_STR);
        $stmh->bindValue(13, $check9, PDO::PARAM_STR);
        $stmh->bindValue(14, $writer, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

    // 신규데이터인 경우 num을 추출한 후 view로 보여주기
    $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";

    try {
        $stmh = $pdo->query($sql);
        $rowNum = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"];
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
}

// JSON 응답 반환
$data = [
    'num' => $num,
    'row' => $row
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
