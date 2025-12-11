<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// json을 사용하기 위해 필요한 구문
header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';

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

// 데이터 변환
$outworkplace = $mytitle;
$al_content = $content;
$e_title = $mytitle;
$eworks_item = '개발프로젝트 연구노트';

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
        
        // 업데이트 로그 추가
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
                    al_content = ?
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
            $num
        ]);
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("개발프로젝트 연구노트 수정 오류: " . $ex->getMessage());
        echo json_encode(["error" => $ex->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
// 신규 등록 모드
else {
    $registdate = date("Y-m-d H:i:s");
    $first_writer = $user_name . " _" . $registdate;
    $author_id = $user_id;
    $status = 'send';

    // JSON에서 결재라인 정보 가져오기
    $jsonFilePath = getDocumentRoot() . '/member/Company_approvalLine_.json';
    $e_line_id = '';
    $e_line = '';
    $al_part = "지원파트";

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
                    registdate, author_id, status, al_content, e_line_id, e_line 
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
            rtrim($e_line, '!')
        ]);
        $pdo->commit();

        // 방금 삽입된 레코드의 num 가져오기
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
        error_log("개발프로젝트 연구노트 등록 오류: " . $ex->getMessage());
        echo json_encode(["error" => $ex->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 결과 반환
echo json_encode(["num" => $num, "mode" => $mode], JSON_UNESCAPED_UNICODE);
?>