<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  

$tablename = isset($_REQUEST['tablename']) ? $_REQUEST['tablename'] : 'todos';  
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';  
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : '';  

header("Content-Type: application/json");  // Use JSON content type

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

include getDocumentRoot() . "/todo/_request.php"; // Ensure this file properly sets all needed variables

if ($mode == "update") {
    $update_log = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . " " . $update_log . "&#10";
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE " . $DB . "." . $tablename . " SET 
                orderdate = ?, towhom = ?, reply = ?, deadline = ?, work_status = ?, title = ?, 
                first_writer = ?, update_log = ?, searchtag = ?
                WHERE num = ? LIMIT 1";

        $stmh = $pdo->prepare($sql);

        $stmh->bindValue(1, $orderdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $towhom, PDO::PARAM_STR);
        $stmh->bindValue(3, $reply, PDO::PARAM_STR);
        $stmh->bindValue(4, $deadline, PDO::PARAM_STR);
        $stmh->bindValue(5, $work_status, PDO::PARAM_STR);
        $stmh->bindValue(6, $title, PDO::PARAM_STR);
        $stmh->bindValue(7, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(8, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(9, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(10, $num, PDO::PARAM_INT);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        echo json_encode(["error" => $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if ($mode == "insert" || $mode == '' || $mode == null) {
    $update_log = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . " " . $update_log . "&#10";
    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO " . $DB . "." . $tablename . " (
                orderdate, towhom, reply, deadline, work_status, title, 
                first_writer, update_log, searchtag
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);

        $stmh->bindValue(1, $orderdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $towhom, PDO::PARAM_STR);
        $stmh->bindValue(3, $reply, PDO::PARAM_STR);
        $stmh->bindValue(4, $deadline, PDO::PARAM_STR);
        $stmh->bindValue(5, $work_status, PDO::PARAM_STR);
        $stmh->bindValue(6, $title, PDO::PARAM_STR);
        $stmh->bindValue(7, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(8, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(9, $searchtag, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        echo json_encode(["error" => $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if ($mode == "delete") {
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE " .  $DB . "." . $tablename . " SET is_deleted=1 WHERE num = ?";  
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);      
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        echo json_encode(["error" => $ex->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$data = [   
    'num' => $num,
    'title' => $title,
    'orderdate' => $orderdate,
    'mode' => $mode
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
