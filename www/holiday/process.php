<?php
require_once(includePath('session.php'));

$tablename = 'holiday';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

header("Content-Type: application/json");  // Use JSON content type

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

include "_request.php";

// 검색 태그 생성 (등록일자와 코멘트를 조합)
$searchtag = $registedate . ' ' . $comment;

if ($mode == "update") {
    $update_log = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . " " . $update_log . "&#10";
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE " . $DB . "." . $tablename . " SET ";
        $sql .= "registedate = ?, startdate = ?, enddate = ?, periodcheck = ?, comment = ?, searchtag = ?, update_log = ? ";
        $sql .= "WHERE num = ? LIMIT 1"; // Update only one record matching the 'num'

        $stmh = $pdo->prepare($sql);

        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $startdate, PDO::PARAM_STR);
        $stmh->bindValue(3, $enddate, PDO::PARAM_STR);
        $stmh->bindValue(4, $periodcheck, PDO::PARAM_INT);
        $stmh->bindValue(5, $comment, PDO::PARAM_STR);
        $stmh->bindValue(6, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(7, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(8, $num, PDO::PARAM_INT);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

if ($mode == "insert" || $mode == '' || $mode == null) {
    $update_log = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . " " . $update_log . "&#10";
    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO " . $DB . "." . $tablename . " (";
        $sql .= "registedate, startdate, enddate, periodcheck, comment, searchtag, update_log ";
        $sql .= ") VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);

        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $startdate, PDO::PARAM_STR);
        $stmh->bindValue(3, $enddate, PDO::PARAM_STR);
        $stmh->bindValue(4, $periodcheck, PDO::PARAM_INT);
        $stmh->bindValue(5, $comment, PDO::PARAM_STR);
        $stmh->bindValue(6, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(7, $update_log, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

if ($mode == "delete") { // Data deletion
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE " .  $DB . "." . $tablename . " SET is_deleted=1 WHERE num = ?";  
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);      
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        print "오류: ".$ex->getMessage();
    }
}

$data = [   
 'num' => $num,
 'mode' => $mode
]; 

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>
