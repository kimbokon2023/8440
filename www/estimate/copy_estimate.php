<?php
/**
 * 견적서 복제 API
 * 기존 견적서를 복제하여 새로운 견적서를 생성합니다.
 * 첨부파일은 복제하지 않습니다.
 */

require_once __DIR__ . '/../bootstrap.php';

// JSON 응답 헤더
header('Content-Type: application/json; charset=utf-8');

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 5) {
    echo json_encode([
        'success' => false,
        'message' => '권한이 없습니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ID 파라미터 확인
$id = $_POST['id'] ?? $_GET['id'] ?? '';
$id = (int)$id;

if (empty($id) || $id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => '견적서 ID가 필요합니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 데이터베이스 연결
    $pdo = db_connect();
    
    // 원본 견적서 정보 조회
    $sql = "SELECT * FROM `estimates` WHERE id = :id AND is_deleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $originalEstimate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$originalEstimate) {
        echo json_encode([
            'success' => false,
            'message' => '견적서를 찾을 수 없습니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 복제할 필드 목록 (ID, estimate_no, 첨부파일 관련 필드는 제외)
    $copyFields = [
        'issue_date',
        'customer_id',
        'supplier_code',
        'supplier_name',
        'supplier_address',
        'business_type',
        'business_item',
        'supplier_phone',
        'supplier_fax',
        'contact_name',
        'business_registration_number',
        'reference',
        'fax',
        'project_site',
        'estimate_items',
        'subtotal',
        'delivery_date',
        'delivery_location',
        'payment_terms',
        'note',
        'internalmemo',
        'disclaimer_text',
        'status',
        'valid_date',
        'email'
    ];
    
    // INSERT 쿼리 구성
    $fields = [];
    $placeholders = [];
    $values = [];
    
    foreach ($copyFields as $field) {
        $fields[] = $field;
        $placeholders[] = ':' . $field;
        $values[':' . $field] = $originalEstimate[$field] ?? null;
    }
    
    // estimate_no는 새로 생성하지 않음 (자동 생성되도록 NULL)
    // 상태는 항상 'draft'로 설정 (복제본은 임시저장 상태)
    $values[':status'] = 'draft';
    
    // 발행일은 오늘 날짜로 변경
    $values[':issue_date'] = date('Y-m-d');
    
    // is_deleted는 0으로 설정
    $fields[] = 'is_deleted';
    $placeholders[] = ':is_deleted';
    $values[':is_deleted'] = 0;
    
    $sql = "INSERT INTO `estimates` (" . implode(', ', $fields) . ", created_at, updated_at) 
            VALUES (" . implode(', ', $placeholders) . ", CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    
    $insertStmt = $pdo->prepare($sql);
    $insertStmt->execute($values);
    
    // 새로 생성된 견적서 ID
    $newEstimateId = $pdo->lastInsertId();
    
    // 성공 응답
    echo json_encode([
        'success' => true,
        'message' => '견적서가 성공적으로 복제되었습니다.',
        'id' => $newEstimateId
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("견적서 복제 오류: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => '견적서 복제 중 오류가 발생했습니다: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

