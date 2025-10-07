<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php';
header("Content-Type: application/json");

$mode = $_REQUEST["mode"] ?? 'insert';
$num = $_REQUEST["num"] ?? '';
$indate = $_REQUEST["indate"] ?? '';
$mytitle = $_REQUEST["mytitle"] ?? '';
$content = $_REQUEST["content"] ?? '';
$author = $_REQUEST["author"] ?? '';
$first_writer = $_REQUEST["first_writer"] ?? '';
$update_log = $_REQUEST["update_log"] ?? '';
$timekey = $_REQUEST["timekey"] ?? '';

$outworkplace = $mytitle;
$al_content = $content;
$e_title = '연구개발계획서';
$eworks_item = '연구개발계획서';

$data = [
    "e_title" => $e_title,
    "indate" => $indate,
    "author" => $author,
    "outworkplace" => $outworkplace,
    "al_content" => $al_content
];

$contents = json_encode($data, JSON_UNESCAPED_UNICODE);

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

if ($mode === "modify") {
    try {
        $pdo->beginTransaction();
        $data = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . "  ";
        $update_log = $data . $update_log . "&#10";

        $sql = "UPDATE {$DB}.eworks SET 
                    indate = ?, 
                    outworkplace = ?, 
                    author = ?, 
                    update_log = ?, 
                    contents = ?, 
                    e_title = ?, 
                    eworks_item = ?, 
                    al_content = ?
                WHERE num = ? LIMIT 1";

        $stmh = $pdo->prepare($sql);
        $stmh->execute([
            $indate, $outworkplace, $author, $update_log, $contents,
            $e_title, $eworks_item, $al_content, $num
        ]);
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
} else {
    $registdate = date("Y-m-d H:i:s");
    $first_writer = $_SESSION["name"] . " _" . $registdate;
    $author_id = $user_id;
    $status = 'send';
	
    // JSON에서 결재라인 정보 가져오기
    $jsonString = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/member/Company_approvalLine_.json');
    $approvalLines = json_decode($jsonString, true);

    // 결재라인 기본값 설정
    $e_line_id = '';
    $e_line = '';
    $al_part = "지원파트";

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
	

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO {$DB}.eworks (
                    indate, outworkplace, first_writer, author,
                    update_log, contents, e_title, eworks_item,
                    registdate, author_id, status, al_content, e_line_id, e_line 
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->execute([
            $indate, $outworkplace, $first_writer, $author,
            $update_log, $contents, $e_title, $eworks_item,
            $registdate, $author_id, $status, $al_content, rtrim($e_line_id, '!'), rtrim($e_line, '!')
        ]);
        $pdo->commit();

        $stmh = $pdo->prepare("SELECT num FROM {$DB}.eworks ORDER BY num DESC LIMIT 1");
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"] ?? '';

        // 첨부파일의 parentnum 업데이트
        $pdo->beginTransaction();
        $sql = "UPDATE {$DB}.picuploads SET parentnum = ? WHERE parentnum = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$num, $timekey]);
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}

echo json_encode(["num" => $num, "mode" => $mode], JSON_UNESCAPED_UNICODE);
