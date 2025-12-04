<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if ($level > 5) {
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once(includePath('lib/mydb.php'));

try {
    $pdo = db_connect();

    // JSON 입력 처리
    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? (int)$input['id'] : 0;

    // Fallback for form data
    if ($id === 0 && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
    }

    if ($id <= 0) {
        throw new Exception('잘못된 ID입니다.');
    }

    // 거래처 존재 여부 확인
    $checkSQL = "SELECT company_name, display_name FROM estimate_customer WHERE num = ? AND is_deleted = 'N'";
    $checkStmt = $pdo->prepare($checkSQL);
    $checkStmt->execute([$id]);
    $customer = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        throw new Exception('삭제할 항목을 찾을 수 없습니다.');
    }

    // 논리적 삭제 (is_deleted = 'Y'로 변경)
    // Note: The column is 'is_deleted', value 'Y' or 'N' based on previous code. 
    // Wait, save.php used 'N'. Let's check import_csv.php used 'N'.
    // So 'Y' is correct for deleted.
    // Also updating updated_at if exists.
    
    $deleteSQL = "UPDATE estimate_customer SET is_deleted = 'Y', last_modified_date = NOW() WHERE num = ?";
    $stmt = $pdo->prepare($deleteSQL);
    $result = $stmt->execute([$id]);

    if ($result) {
        $name = $customer['display_name'] ?: $customer['company_name'];
        echo json_encode(['success' => true, 'message' => '삭제되었습니다.']);
    } else {
        throw new Exception('데이터베이스 삭제에 실패했습니다.');
    }
} catch (Exception $ex) {
    echo json_encode(['success' => false, 'message' => $ex->getMessage()]);
}
?>
