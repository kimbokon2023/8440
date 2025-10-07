<?php

header("Content-Type: application/json"); // JSON 응답 설정

isset($_REQUEST["picname"]) ? $picname = $_REQUEST["picname"] : $picname = '';

require_once("../lib/mydb.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

// Google Drive 서비스 계정 설정
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json');
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes([Google_Service_Drive::DRIVE]);

$service = new Google_Service_Drive($client);

// Google Drive에서 파일 삭제
function deleteFileFromGoogleDrive($service, $fileId) {
    try {
        $service->files->delete($fileId);
        return true;
    } catch (Exception $e) {
        error_log("Google Drive 파일 삭제 실패: " . $e->getMessage());
        return false;
    }
}

$pdo = db_connect();

try {
    $pdo->beginTransaction();

    // DB에서 파일 정보 조회
    $sql = "SELECT * FROM mirae8440.picuploads WHERE picname = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $picname, PDO::PARAM_STR);
    $stmh->execute();

    $fileData = $stmh->fetch(PDO::FETCH_ASSOC);

    if ($fileData) {
        $fileId = $fileData["picname"];

        // Google Drive 파일 삭제
        if (deleteFileFromGoogleDrive($service, $fileId)) {
            // DB에서 파일 정보 삭제
            $sql = "DELETE FROM mirae8440.picuploads WHERE picname = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $picname, PDO::PARAM_STR);
            $stmh->execute();

            $pdo->commit();

            echo json_encode([
                "status" => "success",
                "message" => "파일 삭제 성공",
                "picname" => $picname
            ]);
        } else {
            $pdo->rollBack();
            echo json_encode([
                "status" => "error",
                "message" => "Google Drive에서 파일 삭제 실패",
                "picname" => $picname
            ]);
        }
    } else {
        $pdo->rollBack();
        echo json_encode([
            "status" => "error",
            "message" => "파일 정보를 찾을 수 없습니다.",
            "picname" => $picname
        ]);
    }
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        "status" => "error",
        "message" => "DB 처리 중 오류 발생: " . $e->getMessage()
    ]);
}
?>
