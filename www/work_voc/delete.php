<?php
/**
 * work_voc 모듈 - 데이터 삭제 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 확인
session_start();

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';

// 권한 체크
if (!isset($_SESSION["userid"])) {
    echo '<script>
        alert("로그인 후 이용해 주세요.");
        history.back();
    </script>';
    exit;
}

// 데이터베이스 연결
$pdo = db_connect();

$upload_dir = 'uploads';   // 물리적 저장위치

try {
    $sql = "SELECT * FROM mirae8440.voc WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();

    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $copied_name = [
        $row['file_copied_0'] ?? '',
        $row['file_copied_1'] ?? '',
        $row['file_copied_2'] ?? ''
    ];

    for ($i = 0; $i < 3; $i++) {
        if ($copied_name[$i]) {
            $image_name = $upload_dir . $copied_name[$i];
            if (file_exists($image_name)) {
                unlink($image_name);
            }
        }
    }
} catch (PDOException $Exception) {
    error_log("file deletion error: " . $Exception->getMessage());
    echo "파일 삭제 중 오류: " . $Exception->getMessage();
    exit;
}

try {
    $pdo->beginTransaction();
    $sql = "DELETE FROM mirae8440.voc WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();

    header("Location:" . getBaseUrl() . "/work_voc/list.php");

} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("delete error: " . $ex->getMessage());
    echo "삭제 중 오류: " . $ex->getMessage();
    exit;
}
?>