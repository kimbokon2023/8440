<?php 
require_once(includePath('session.php'));   

header("Content-Type: application/json");

$mode = $_POST["mode"] ?? '';
$num = $_POST["num"] ?? '';
$cabledone = $_POST["cabledone"] ?? '';

include getDocumentRoot() . '/ceiling/_requestDB.php';
require_once("../lib/mydb.php");
$pdo = db_connect();

if ($mode == "modify" && !empty($num)) {
    $data = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . "  ";    
    $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea

    try {         
        $pdo->beginTransaction();   
        $sql = "UPDATE mirae8440.ceiling SET cabledone = ? WHERE num = ? LIMIT 1";        
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $cabledone, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit(); 

        echo json_encode(["num" => $num, "status" => "success"], JSON_UNESCAPED_UNICODE);
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(["status" => "error", "message" => "잘못된 요청입니다."], JSON_UNESCAPED_UNICODE);
}
?>
