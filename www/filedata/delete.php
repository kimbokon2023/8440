<?php
/**
 * Concert 레코드 삭제 처리 스크립트
 * 물리적 파일과 DB 레코드를 함께 삭제합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'phptest1';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

// 변수 초기화
$copied_name = array();
$count = 0;

// 입력 검증
if (empty($num)) {
    error_log("delete.php error: No num specified");
    die("삭제할 레코드 번호가 지정되지 않았습니다.");
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 업로드 디렉토리 설정 (환경에 따라 동적 설정)
$upload_dir = __DIR__ . '/../data/';
if (!is_dir($upload_dir)) {
    $upload_dir = 'C:/xampp/htdocs/data/';
}

try {
    // 파일 정보 조회
    $sql = "SELECT * FROM {$DB}.concert WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count > 0) {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $copied_name[0] = $row['file_copied_0'] ?? '';
        $copied_name[1] = $row['file_copied_1'] ?? '';
        $copied_name[2] = $row['file_copied_2'] ?? '';
        
        // 물리적 파일 삭제
        for ($i = 0; $i < 3; $i++) {
            if (!empty($copied_name[$i])) {
                $image_name = $upload_dir . $copied_name[$i];
                
                if (file_exists($image_name)) {
                    if (!unlink($image_name)) {
                        error_log("Failed to delete file: {$image_name}");
                    }
                } else {
                    error_log("File not found for deletion: {$image_name}");
                }
            }
        }
    } else {
        error_log("Record not found for deletion: num={$num}");
    }
    
} catch (PDOException $ex) {
    error_log("DB select error in delete.php: " . $ex->getMessage());
    die("데이터 조회 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
}

try {
    // DB 레코드 삭제
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM {$DB}.concert WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
    // 로컬/서버 환경에 따른 동적 리다이렉션
    $baseUrl = getBaseUrl();
    $redirectUrl = $baseUrl . "/concert/list.php";
    
    header("Location: " . $redirectUrl);
    exit;
    
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("DB delete error in delete.php: " . $ex->getMessage());
    die("데이터 삭제 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
}

?>