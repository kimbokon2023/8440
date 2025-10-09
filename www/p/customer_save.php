<?php
// 공사완료 확인서 데이터 저장 - 로컬/서버 환경 호환
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../bootstrap.php';

$tablename = 'work';

// 입력값 검증 및 초기화
$num = $_REQUEST["num"] ?? '';
$jsonData = $_REQUEST['customerData'] ?? '';

// 입력값 유효성 검사
if (empty($num) || !is_numeric($num)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "유효하지 않은 번호입니다."]);
    exit();
}

if (empty($jsonData)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "고객 데이터가 없습니다."]);
    exit();
}

// JSON 데이터 파싱
$data = json_decode($jsonData, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "잘못된 JSON 데이터입니다."]);
    exit();
}

// 데이터베이스 연결
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        http_response_code(500);
        if (isLocal()) {
            echo json_encode(["success" => false, "message" => "데이터베이스 연결 실패: " . $e->getMessage()]);
        } else {
            error_log("Database connection failed in customer_save.php: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "데이터베이스 연결에 실패했습니다."]);
        }
        exit();
    }
}

$field = 'customer';

try {
    // 트랜잭션 시작
    $pdo->beginTransaction();

    // 업데이트 쿼리 생성 및 실행
    $sql = "UPDATE $DB.$tablename SET $field = ? WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $jsonData, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_INT);
    $stmh->execute();

    // 영향받은 행 수 확인
    $rowCount = $stmh->rowCount();

    // 트랜잭션 커밋
    $pdo->commit();

    // 성공 응답
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => "데이터가 성공적으로 저장되었습니다.",
        "data" => $data,
        "rowCount" => $rowCount
    ]);

} catch (PDOException $Exception) {
    // 트랜잭션 롤백
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    http_response_code(500);
    if (isLocal()) {
        echo json_encode([
            "success" => false,
            "message" => "데이터베이스 오류: " . $Exception->getMessage()
        ]);
    } else {
        error_log("Database error in customer_save.php: " . $Exception->getMessage());
        echo json_encode([
            "success" => false,
            "message" => "데이터 저장 중 오류가 발생했습니다."
        ]);
    }
    exit();
}
?>
