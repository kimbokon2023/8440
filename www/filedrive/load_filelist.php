<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php';
$pdo = db_connect();

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json');
$client->addScope(Google_Service_Drive::DRIVE);
$service = new Google_Service_Drive($client);

header("Content-Type: application/json");

$tablename = $_GET["tablename"] ?? '';
$num = $_GET["num"] ?? '';
$item = 'attached';  // 기본은 파일, 필요 시 image 처리 가능

$savefilename_arr = [];

$SearchKey = $num ?: (date("Y_m_d_H_i_s") . '_' . rand(100, 999));

$sql = "SELECT * FROM mirae8440.picuploads WHERE tablename=? AND item = ? AND parentnum = ?";
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$tablename, $item, $SearchKey]);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"];
        $realname = $row["realname"];

        if (preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            $fileId = $picname;
            try {
                $file = $service->files->get($fileId, ['fields' => 'webViewLink, thumbnailLink']);
                $savefilename_arr[] = [
                    'thumbnail' => $file->thumbnailLink,
                    'link' => $file->webViewLink,
                    'fileId' => $fileId,
                    'realname' => $realname
                ];
            } catch (Exception $e) {
                $savefilename_arr[] = [
                    'thumbnail' => null,
                    'link' => null,
                    'fileId' => $fileId,
                    'realname' => $realname
                ];
            }
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

echo json_encode($savefilename_arr, JSON_UNESCAPED_UNICODE);
?>
