<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';

// POST 요청만 허용
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? '';
if ($requestMethod !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method not allowed'), JSON_UNESCAPED_UNICODE);
    exit;
}

require_once(includePath('lib/mydb.php'));

try {
    $pdo = db_connect();

    // 거래처 번호 확인
    $num = isset($_POST['num']) ? intval($_POST['num']) : 0;

    if (!$num) {
        throw new Exception('잘못된 거래처 번호입니다.');
    }

    // 거래처 존재 여부 확인
    $checkSQL = "SELECT company_name FROM {$DB}.customer WHERE num = ? AND is_deleted = 'N'";
    $checkStmt = $pdo->prepare($checkSQL);
    $checkStmt->execute(array($num));
    $customer = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        throw new Exception('삭제할 거래처를 찾을 수 없습니다.');
    }

    // 논리적 삭제 (is_deleted = 'Y'로 변경)
    $deleteSQL = "UPDATE {$DB}.customer SET is_deleted = 'Y', last_modified_date = NOW() WHERE num = ?";
    $stmt = $pdo->prepare($deleteSQL);
    $result = $stmt->execute(array($num));

    if ($result) {
        // 로그 기록
        error_log("거래처 삭제: ID=" . $num . ", 거래처명=" . $customer['company_name']);

        echo json_encode(array(
            'success' => true,
            'message' => '거래처가 성공적으로 삭제되었습니다.'
        ), JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('데이터베이스 삭제에 실패했습니다.');
    }
} catch (PDOException $ex) {
    error_log("거래처 삭제 DB 오류: " . $ex->getMessage());
    echo json_encode(array(
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다: ' . $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
} catch (Exception $ex) {
    error_log("거래처 삭제 오류: " . $ex->getMessage());
    echo json_encode(array(
        'success' => false,
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}
?>
