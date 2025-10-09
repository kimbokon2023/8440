<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// JSON 응답 헤더 설정
header("Content-Type: application/json");

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

include 'request.php';

// 디렉토리 설정
$upload_dir = '../uploads/';    // 원본 파일 저장 위치
$trash_dir = './trash/';        // 휴지통 폴더 위치

// 휴지통 폴더가 없다면 생성
if (!file_exists($trash_dir)) {
    mkdir($trash_dir, 0777, true);
}

// 변수 초기화
$copied_name = array();

// 1단계: 레코드를 JSON으로 백업
try {
    // 레코드 검색 및 추출
    $stmh = $pdo->prepare("SELECT * FROM mirae8440.work WHERE num = ?");
    $stmh->execute([$num]);
    $row = $stmh->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // JSON 형식으로 변환
        $jsonData = json_encode($row, JSON_UNESCAPED_UNICODE);

        // 파일로 저장 (파일 이름은 레코드 ID와 현재 날짜/시간을 사용)
        $backupFileName = $trash_dir . "trash_" . $num . "_" . date("Y-m-d_H-i-s") . ".json";
        file_put_contents($backupFileName, $jsonData);
    }
} catch (PDOException $Exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '백업 오류: ' . $Exception->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2단계: 첨부파일을 휴지통으로 이동
try {
    $sql = "SELECT * FROM mirae8440.work WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();

    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $copied_name[0] = $row['file_copied_0'] ?? '';
        $copied_name[1] = $row['file_copied_1'] ?? '';
        $copied_name[2] = $row['file_copied_2'] ?? '';

        for ($i = 0; $i < 3; $i++) {
            if ($copied_name[$i] && file_exists($upload_dir . $copied_name[$i])) {
                $image_name = $upload_dir . $copied_name[$i];
                $new_location = $trash_dir . $copied_name[$i];
                rename($image_name, $new_location);  // 파일을 휴지통으로 이동
            }
        }
    }
} catch (PDOException $Exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '파일 이동 오류: ' . $Exception->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3단계: 데이터베이스에서 레코드 삭제
try {
    $pdo->beginTransaction();
    
    $sql = "DELETE FROM mirae8440.work WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
} catch (Exception $ex) {
    if ($pdo) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '삭제 오류: ' . $ex->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 성공 응답
$data = array(
    "success" => true,
    "num" => $num
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>