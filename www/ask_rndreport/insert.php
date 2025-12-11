<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';
header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["user_id"] ?? '';

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? 'insert';
$num = $_REQUEST["num"] ?? '';
$indate = $_REQUEST["indate"] ?? '';
$mytitle = $_REQUEST["mytitle"] ?? '';
$content = $_REQUEST["content"] ?? '';
$author = $_REQUEST["author"] ?? '';
$first_writer = $_REQUEST["first_writer"] ?? '';
$update_log = $_REQUEST["update_log"] ?? '';
$timekey = $_REQUEST["timekey"] ?? '';
$store = $_REQUEST["store"] ?? '';

// 데이터 매핑
$outworkplace = $mytitle;
$al_content = $content;
$e_title = '연구개발보고서';
$eworks_item = '연구개발보고서';

// JSON 데이터 생성
$data = [
    "e_title" => $e_title,
    "indate" => $indate,
    "author" => $author,
    "outworkplace" => $outworkplace,
    "al_content" => $al_content
];

$contents = json_encode($data, JSON_UNESCAPED_UNICODE);

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode === "modify") {
    try {
        $pdo->beginTransaction();
        
        $data = date("Y-m-d H:i:s") . " - " . $user_name . "  ";
        $update_log = $data . $update_log . "&#10";

        $sql = "UPDATE {$DB}.eworks SET 
                    indate = ?, 
                    outworkplace = ?, 
                    author = ?, 
                    update_log = ?, 
                    contents = ?, 
                    e_title = ?, 
                    eworks_item = ?, 
                    al_content = ?,
                    store = ?
                WHERE num = ? LIMIT 1";

        $stmh = $pdo->prepare($sql);
        $stmh->execute([
            $indate, 
            $outworkplace, 
            $author, 
            $update_log, 
            $contents,
            $e_title, 
            $eworks_item, 
            $al_content, 
            $store, 
            $num
        ]);
        
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("연구개발보고서 수정 오류: " . $ex->getMessage());
        echo json_encode(["error" => $ex->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    // 신규 등록 모드 (copy, insert)
    $registdate = date("Y-m-d H:i:s");
    $first_writer = $user_name . " _" . $registdate;
    $author_id = $user_id;
    $status = 'send';

    // JSON에서 결재라인 정보 가져오기
    $e_line_id = '';
    $e_line = '';
    $al_part = "지원파트";

    $jsonFilePath = getDocumentRoot() . '/member/Company_approvalLine_.json';
    if (file_exists($jsonFilePath)) {
        $jsonString = file_get_contents($jsonFilePath);
        $approvalLines = json_decode($jsonString, true);

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
    }

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO {$DB}.eworks (
                    indate, outworkplace, first_writer, author,
                    update_log, contents, e_title, eworks_item,
                    registdate, author_id, status, al_content, e_line_id, e_line, store 
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->execute([
            $indate, 
            $outworkplace, 
            $first_writer, 
            $author,
            $update_log, 
            $contents, 
            $e_title, 
            $eworks_item,
            $registdate, 
            $author_id, 
            $status, 
            $al_content, 
            rtrim($e_line_id, '!'), 
            rtrim($e_line, '!'), 
            $store
        ]);
        
        $pdo->commit();

        // 생성된 레코드의 num 가져오기
        $stmh = $pdo->prepare("SELECT num FROM {$DB}.eworks ORDER BY num DESC LIMIT 1");
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"] ?? '';

        // 첨부파일의 parentnum 업데이트
        if ($timekey) {
            $pdo->beginTransaction();
            $sql = "UPDATE {$DB}.picuploads SET parentnum = ? WHERE parentnum = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->execute([$num, $timekey]);
            $pdo->commit();
        }
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("연구개발보고서 등록 오류: " . $ex->getMessage());
        echo json_encode(["error" => $ex->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 결과 반환
echo json_encode(["num" => $num, "mode" => $mode], JSON_UNESCAPED_UNICODE);
