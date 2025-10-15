<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

$tablename = "eworks";

header("Content-Type: application/json");

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$author = $_REQUEST["author"] ?? '';
$al_part = $_REQUEST["al_part"] ?? '';
$author_id = $_REQUEST["author_id"] ?? '';
$registdate = $_REQUEST["registdate"] ?? '';
$al_item = $_REQUEST["al_item"] ?? '';
$al_askdatefrom = $_REQUEST["al_askdatefrom"] ?? '';
$al_askdateto = $_REQUEST["al_askdateto"] ?? '';
$al_usedday = $_REQUEST["al_usedday"] ?? '';
$al_content = $_REQUEST["al_content"] ?? '';
$status = $_REQUEST["status"] ?? '';
$htmltext = $_REQUEST["htmltext"] ?? '';

$data = array(
    "author" => $author,
    "al_item" => $al_item,
    "al_askdatefrom" => $al_askdatefrom,
    "al_askdateto" => $al_askdateto,
    "al_usedday" => $al_usedday,
    "al_content" => $al_content
);

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        error_log("eworks 연차 조회 오류: " . $ex->getMessage());
    }

    // 전자 결재에 보여질 내용 data 수정 update
    $contents = json_encode($data, JSON_UNESCAPED_UNICODE);

    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . "." . $tablename . " set author_id=?, author=?, registdate=?, al_item=?, al_askdatefrom=?, al_askdateto=?, al_usedday=?, al_content=?, status=?, al_part=?, contents=?";
        $sql .= " where num=? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $author_id, PDO::PARAM_STR);
        $stmh->bindValue(2, $author, PDO::PARAM_STR);
        $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
        $stmh->bindValue(4, $al_item, PDO::PARAM_STR);
        $stmh->bindValue(5, $al_askdatefrom, PDO::PARAM_STR);
        $stmh->bindValue(6, $al_askdateto, PDO::PARAM_STR);
        $stmh->bindValue(7, $al_usedday, PDO::PARAM_STR);
        $stmh->bindValue(8, $al_content, PDO::PARAM_STR);
        $stmh->bindValue(9, $status, PDO::PARAM_STR);
        $stmh->bindValue(10, $al_part, PDO::PARAM_STR);
        $stmh->bindValue(11, $contents, PDO::PARAM_STR);
        $stmh->bindValue(12, $num, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("eworks 연차 수정 오류: " . $ex->getMessage());
    }
}

// 삽입 모드
if ($mode == "insert") {
    try {
        $pdo->beginTransaction();

        // Read and decode the JSON file
        $jsonString = file_get_contents(getDocumentRoot() . '/member/Company_approvalLine_.json');
        $approvalLines = json_decode($jsonString, true);

        // Default values for e_line_id and e_line
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

        // Set status based on the part
        $status = ($al_part == '제조파트') ? 'send' : 'send';
        $e_title = '연차신청';

        // 전자 결재에 보여질 내용 data 수정 update
        $contents = json_encode($data, JSON_UNESCAPED_UNICODE);
        $eworks_item = '연차';

        // SQL statement with additional fields for e_line_id, e_line, and status
        $sql = "INSERT INTO " . $DB . "." . $tablename . " (author_id, author, registdate, al_item, al_askdatefrom, al_askdateto, al_usedday, al_content, status, al_part, e_line_id, e_line, e_title, contents, eworks_item) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $author_id, PDO::PARAM_STR);
        $stmh->bindValue(2, $author, PDO::PARAM_STR);
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
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("eworks 연차 삽입 오류: " . $ex->getMessage());
    }
}

// 삭제 모드
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . "." . $tablename . " set is_deleted=?";
        $sql .= " where num=? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, true, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("eworks 연차 삭제 오류: " . $ex->getMessage());
    }
}

// JSON 응답 데이터 생성
$data = array(
    "registdate" => $registdate,
    "status" => $status
);

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>

