<?php
require_once(includePath('session.php'));  

$tablename = $_REQUEST['tablename'] ?? 'delivery';  
$mode = $_REQUEST['mode'] ?? '';  

header("Content-Type: application/json");  // JSON 응답 설정

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
 
include "_request.php";
 
$searchtag = $registedate . ' ' .
              $receiver . ' ' . 
              $receiver_tel . ' ' .
              $address . ' ' .
              $sender . ' ' .
              $item_name . ' ' .
              $unit . ' ' .
              $surang . ' ' .
              $fee . ' ' .
              $fee_type . ' ' .
              $goods_price;

$update_log = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . " " . ($update_log ?? '') . "&#10";

if ($mode == "update") {
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE " . $DB . "." . $tablename . " SET 
                registedate = ?, receiver = ?, receiver_tel = ?, address = ?, sender = ?, 
                item_name = ?, unit = ?, surang = ?, fee = ?, fee_type = ?, goods_price = ?, 
                update_log = ?, searchtag = ?
                WHERE num = ? LIMIT 1"; 

        $stmh = $pdo->prepare($sql);

        // 바인딩
        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $receiver, PDO::PARAM_STR);
        $stmh->bindValue(3, $receiver_tel, PDO::PARAM_STR);
        $stmh->bindValue(4, $address, PDO::PARAM_STR);
        $stmh->bindValue(5, $sender, PDO::PARAM_STR);
        $stmh->bindValue(6, $item_name, PDO::PARAM_STR);
        $stmh->bindValue(7, $unit, PDO::PARAM_STR);
        $stmh->bindValue(8, str_replace(',', '', $surang), PDO::PARAM_STR);
        $stmh->bindValue(9, str_replace(',', '', $fee), PDO::PARAM_STR);
        $stmh->bindValue(10, $fee_type, PDO::PARAM_STR);
        $stmh->bindValue(11, str_replace(',', '', $goods_price), PDO::PARAM_STR);
        $stmh->bindValue(12, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(13, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(14, $num, PDO::PARAM_INT);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        echo json_encode(["error" => $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if ($mode == "insert" || $mode == "copy" || $mode == '' || $mode == null) {
    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO " . $DB . "." . $tablename . " (
                registedate, receiver, receiver_tel, address, sender, 
                item_name, unit, surang, fee, fee_type, goods_price, 
                update_log, searchtag) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);

        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $receiver, PDO::PARAM_STR);
        $stmh->bindValue(3, $receiver_tel, PDO::PARAM_STR);
        $stmh->bindValue(4, $address, PDO::PARAM_STR);
        $stmh->bindValue(5, $sender, PDO::PARAM_STR);
        $stmh->bindValue(6, $item_name, PDO::PARAM_STR);
        $stmh->bindValue(7, $unit, PDO::PARAM_STR);
        $stmh->bindValue(8, str_replace(',', '', $surang), PDO::PARAM_STR);
        $stmh->bindValue(9, str_replace(',', '', $fee), PDO::PARAM_STR);
        $stmh->bindValue(10, $fee_type, PDO::PARAM_STR);
        $stmh->bindValue(11, str_replace(',', '', $goods_price), PDO::PARAM_STR);
        $stmh->bindValue(12, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(13, $searchtag, PDO::PARAM_STR);

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
        $sql = "UPDATE " .  $DB . "." . $tablename . " SET is_deleted = 1 WHERE num = ?";  
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

// JSON 응답
$data = [   
 'num' => $num,
 'mode' => $mode,
 'receiver' => $receiver,
 'receiver_tel' => $receiver_tel,
 'address' => $address,
 'sender' => $sender,
 'item_name' => $item_name,
 'unit' => $unit,
 'surang' => $surang,
 'fee' => $fee,
 'fee_type' => $fee_type,
 'goods_price' => $goods_price
]; 

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
