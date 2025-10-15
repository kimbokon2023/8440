<?php
/**
 * 외주 데이터 삭제
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';

// 정렬 관련 변수 초기화
$cursort = $_REQUEST["cursort"] ?? $_POST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? $_POST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? $_POST["stable"] ?? '0';

// 필수 파라미터 검증
if (empty($num)) {
    error_log("외주 삭제 실패: num이 비어있음");
    die("오류: 삭제할 번호가 지정되지 않았습니다.");
}

require_once("../lib/mydb.php");
$pdo = db_connect();

$upload_dir = '../uploads/';

// 배열 초기화
$copied_name = ['', '', ''];

try {
    $sql = "SELECT * FROM mirae8440.oem WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count > 0) {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $copied_name[0] = $row["file_copied_0"] ?? '';
        $copied_name[1] = $row["file_copied_1"] ?? '';
        $copied_name[2] = $row["file_copied_2"] ?? '';
        
        // 첨부파일 삭제
        for ($i = 0; $i < 3; $i++) {
            if (!empty($copied_name[$i])) {
                $image_name = $upload_dir . $copied_name[$i];
                if (file_exists($image_name)) {
                    if (!@unlink($image_name)) {
                        error_log("파일 삭제 실패: {$image_name}");
                    }
                }
            }
        }
    }
} catch (PDOException $ex) {
    error_log("외주 첨부파일 조회 오류 (num: {$num}): " . $ex->getMessage());
    // 파일 삭제 실패해도 데이터 삭제는 진행
}

try {
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM mirae8440.oem WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
    // 리다이렉트
    header("Location: {$base_url}/oem/list.php");
    exit;
    
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("외주 데이터 삭제 오류 (num: {$num}): " . $ex->getMessage());
    die("오류: 데이터 삭제 중 문제가 발생했습니다.");
}
?>
