<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';
require_once(includePath('lib/mydb.php'));

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $pdo = db_connect();
    
    // 거래처 번호 확인
    $num = isset($_POST['num']) ? intval($_POST['num']) : 0;
    
    if (!$num) {
        throw new Exception('잘못된 거래처 번호입니다.');
    }
    
    // 거래처 존재 여부 확인
    $checkSQL = "SELECT company_name FROM mirae8440.customer WHERE num = ? AND is_deleted = 'N'";
    $checkStmt = $pdo->prepare($checkSQL);
    $checkStmt->execute([$num]);
    $customer = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        throw new Exception('삭제할 거래처를 찾을 수 없습니다.');
    }
    
    // 논리적 삭제 (is_deleted = 'Y'로 변경)
    $deleteSQL = "UPDATE mirae8440.customer SET is_deleted = 'Y', last_modified_date = NOW() WHERE num = ?";
    $stmt = $pdo->prepare($deleteSQL);
    $result = $stmt->execute([$num]);
    
    if ($result) {
        // 로그 기록
        error_log("거래처 삭제: ID={$num}, 거래처명={$customer['company_name']}");
        
        echo json_encode([
            'success' => true, 
            'message' => '거래처가 성공적으로 삭제되었습니다.'
        ]);
    } else {
        throw new Exception('데이터베이스 삭제에 실패했습니다.');
    }
    
} catch (PDOException $e) {
    error_log("거래처 삭제 DB 오류: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => '데이터베이스 오류가 발생했습니다: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
