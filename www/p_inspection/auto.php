<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 설정 변수
$tablename = 'p_inspection';
$inspector = '조성원';
$cutoff_date = '2023-03-01';

try {
    // 2023-03-01 이후 작업일자를 가진 레코드 조회
    $sql = "SELECT * FROM mirae8440.work WHERE workday > ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $cutoff_date, PDO::PARAM_STR);
    $stmh->execute();

    $pdo->beginTransaction();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $parentID = $row['num'];
        $workday = $row['workday'];
        $workplacename = $row['workplacename'];

        // p_inspection 테이블에 데이터 삽입
        $sql_insert = "INSERT INTO mirae8440." . $tablename . " (parentID, writer, check0, check1, check2, check3, check4, check5, check6, check7, check8, check9, regist_day, subject) ";
        $sql_insert .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh_insert = $pdo->prepare($sql_insert);

        $stmh_insert->bindValue(1, $parentID, PDO::PARAM_STR);
        $stmh_insert->bindValue(2, $inspector, PDO::PARAM_STR);
        for ($i = 3; $i <= 12; $i++) {
            $stmh_insert->bindValue($i, $workday, PDO::PARAM_STR);
        }
        $stmh_insert->bindValue(13, $workday, PDO::PARAM_STR);
        $stmh_insert->bindValue(14, $workplacename, PDO::PARAM_STR);

        $stmh_insert->execute();
    }

    $pdo->commit();

    echo "데이터가 성공적으로 삽입되었습니다.";
} catch (PDOException $Exception) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    print "오류: " . $Exception->getMessage();
}