<?php include $_SERVER['DOCUMENT_ROOT'] . '/session.php';   

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

$tablename = "eworks";
isset($_REQUEST["author_list"]) ? $author_list = json_decode($_REQUEST["author_list"], true) : $author_list = [];
$registdate = $_REQUEST["registdate"] ?? date("Y-m-d H:i:s");
$al_item = $_REQUEST["al_item"] ?? "연차";
$al_askdatefrom = $_REQUEST["al_askdatefrom"] ?? "";
$al_askdateto = $_REQUEST["al_askdateto"] ?? "";
$al_usedday = $_REQUEST["al_usedday"] ?? 0;
$al_content = $_REQUEST["al_content"] ?? "";

require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    $pdo->beginTransaction();

    foreach ($author_list as $author) {
        $author_name = $author['name'];
        $al_part = $author['al_part'];
        $author_id = $author['author_id'];

        // Read and decode the JSON file
        $jsonString = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/member/Company_approvalLine_.json');
        $approvalLines = json_decode($jsonString, true);

        $e_line_id = '';
        $e_line = '';

        // Check if decoded JSON is an array and process it
        if (is_array($approvalLines)) {
            foreach ($approvalLines as $line) {
                if ($al_part == $line['savedName']) {
                    foreach ($line['approvalOrder'] as $order) {
                        $e_line_id .= $order['user-id'] . '!';
                        $e_line .= $order['name'] . '!';
                    }
                    break;
                }
            }
        }

        $status = 'send';
        $e_title = '연차신청';
        $eworks_item = '연차';

        $data = array(
            "author" => $author_name,
            "al_item" => $al_item,
            "al_askdatefrom" => $al_askdatefrom,
            "al_askdateto" => $al_askdateto,
            "al_usedday" => $al_usedday,
            "al_content" => $al_content
        );

        $contents = json_encode($data, JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO " . $DB . "." . $tablename . " (author_id, author, registdate, al_item, al_askdatefrom, al_askdateto, al_usedday, al_content, status, al_part, e_line_id, e_line, e_title, contents, eworks_item) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $author_id, PDO::PARAM_STR);
        $stmh->bindValue(2, $author_name, PDO::PARAM_STR);
        $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
        $stmh->bindValue(4, $al_item, PDO::PARAM_STR);
        $stmh->bindValue(5, $al_askdatefrom, PDO::PARAM_STR);
        $stmh->bindValue(6, $al_askdateto, PDO::PARAM_STR);
        $stmh->bindValue(7, $al_usedday, PDO::PARAM_STR);
        $stmh->bindValue(8, $al_content, PDO::PARAM_STR);
        $stmh->bindValue(9, $status, PDO::PARAM_STR);
        $stmh->bindValue(10, $al_part, PDO::PARAM_STR);
        $stmh->bindValue(11, rtrim($e_line_id, '!'), PDO::PARAM_STR);
        $stmh->bindValue(12, rtrim($e_line, '!'), PDO::PARAM_STR);
        $stmh->bindValue(13, $e_title, PDO::PARAM_STR);
        $stmh->bindValue(14, $contents, PDO::PARAM_STR);
        $stmh->bindValue(15, $eworks_item, PDO::PARAM_STR);

        $stmh->execute();
    }

    $pdo->commit();
    echo json_encode(["status" => "success", "message" => "대량 등록이 완료되었습니다."]);
} catch (PDOException $Exception) {
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => $Exception->getMessage()]);
}

?>
