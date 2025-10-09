<?php
// 서명 이미지 저장 - 로컬/서버 환경 호환
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../bootstrap.php';

$tablename = 'work';

// 입력값 검증
$img = $_POST['img'] ?? '';
$num = $_REQUEST["num"] ?? '';

if (empty($img)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "이미지 데이터가 없습니다."]);
    exit();
}

if (empty($num) || !is_numeric($num)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "유효하지 않은 번호입니다."]);
    exit();
}

try {
    // Base64 이미지 데이터 처리
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    
    if ($data === false) {
        throw new Exception("이미지 디코딩에 실패했습니다.");
    }

    // 저장 디렉토리 설정
    $signatureDir = getDocumentRoot() . '/' . $tablename . '/signatures/';
    
    if (!file_exists($signatureDir)) {
        if (!mkdir($signatureDir, 0755, true)) {
            throw new Exception("디렉토리 생성에 실패했습니다.");
        }
    }

    // 파일 저장
    $filename = uniqid() . '.png';
    $filePath = $signatureDir . $filename;
    
    if (file_put_contents($filePath, $data) === false) {
        throw new Exception("파일 저장에 실패했습니다.");
    }
    
    // 환경별 파일 URL 생성
    $fileUrl = 'signatures/' . $filename;

    // 데이터베이스 연결
    if (!isset($pdo) || !$pdo) {
        $pdo = db_connect();
    }

    // 트랜잭션 시작
    $pdo->beginTransaction();

    try {
        // 기존 customer 데이터 조회
        $sql = "SELECT customer FROM $DB.$tablename WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("해당 번호의 데이터를 찾을 수 없습니다.");
        }

        // customer 데이터 업데이트
        $customerData = json_decode($row["customer"] ?? '{}', true);
        if ($customerData === null) {
            $customerData = [];
        }
        
        $customerData['image_url'] = $fileUrl;
        $jsonDataString = json_encode($customerData, JSON_UNESCAPED_UNICODE);

        // 데이터베이스 업데이트
        $sql = "UPDATE $DB.$tablename SET customer = ? WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $jsonDataString, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_INT);
        $stmh->execute();

        // 트랜잭션 커밋
        $pdo->commit();

        // 성공 응답
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "서명이 성공적으로 저장되었습니다.",
            "image_url" => $fileUrl,
            "filename" => $filename
        ]);

    } catch (PDOException $Exception) {
        // 트랜잭션 롤백
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        // 저장된 파일 삭제
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        throw new Exception("데이터베이스 오류: " . $Exception->getMessage());
    }

} catch (Exception $e) {
    http_response_code(500);
    if (isLocal()) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    } else {
        error_log("Error in save_signature.php: " . $e->getMessage());
        echo json_encode([
            "success" => false,
            "message" => "서명 저장 중 오류가 발생했습니다."
        ]);
    }
}
?>
 