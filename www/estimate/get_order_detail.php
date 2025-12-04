<?php
/**
 * 견적서 상세 정보 조회 API
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
    ]);
    exit;
}

// ID 파라미터 확인
$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode([
        'success' => false,
        'message' => '견적서 ID가 필요합니다.'
    ]);
    exit;
}

try {
    // 데이터베이스 연결
    $pdo = db_connect();
    
    // 견적서 정보 조회
    $sql = "SELECT * FROM `estimates` WHERE id = :id AND is_deleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo json_encode([
            'success' => false,
            'message' => '견적서를 찾을 수 없습니다.'
        ]);
        exit;
    }
    
    // 날짜 포맷 변환
    if ($order['issue_date']) {
        $order['issue_date'] = date('Y-m-d', strtotime($order['issue_date']));
    }
    if ($order['delivery_date']) {
        $order['delivery_date'] = date('Y-m-d', strtotime($order['delivery_date']));
    }

    // 거래처 이메일 조회
    // 이미 저장된 이메일이 있으면 그것을 사용하고, 없으면 조회
    if (empty($order['email'])) {
        $order['email'] = '';
    
    // 1. customer_id가 있으면 우선 사용
    if (!empty($order['customer_id'])) {
        try {
            // 거래처 담당자 이메일 조회 (계산서 담당자 우선, 없으면 첫 번째 담당자)
            // estimate_customer_contact 테이블 사용
            $contactSql = "SELECT contact_email FROM estimate_customer_contact 
                           WHERE customer_id = :cid AND is_deleted = 'N' 
                           ORDER BY is_invoice_contact DESC, id ASC LIMIT 1";
            $contactStmt = $pdo->prepare($contactSql);
            $contactStmt->bindValue(':cid', $order['customer_id']);
            $contactStmt->execute();
            $contact = $contactStmt->fetch(PDO::FETCH_ASSOC);

            if ($contact && !empty($contact['contact_email'])) {
                $order['email'] = $contact['contact_email'];
            }
        } catch (Exception $e) {
            error_log("거래처 이메일 조회(ID) 오류: " . $e->getMessage());
        }
    }
    
    // 2. 이메일을 못 찾았고 사업자번호가 있으면 사업자번호로 조회 (하위 호환성)
    if (empty($order['email']) && !empty($order['business_registration_number'])) {
        try {
            // 사업자번호로 거래처 ID 조회
            $custSql = "SELECT id FROM estimate_customer WHERE business_registration_number = :brn AND is_deleted = 'N' LIMIT 1";
            $custStmt = $pdo->prepare($custSql);
            $custStmt->bindValue(':brn', $order['business_registration_number']);
            $custStmt->execute();
            $customer = $custStmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                // 거래처 담당자 이메일 조회
                $contactSql = "SELECT contact_email FROM estimate_customer_contact 
                               WHERE customer_id = :cid AND is_deleted = 'N' 
                               ORDER BY is_invoice_contact DESC, id ASC LIMIT 1";
                $contactStmt = $pdo->prepare($contactSql);
                $contactStmt->bindValue(':cid', $customer['id']);
                $contactStmt->execute();
                $contact = $contactStmt->fetch(PDO::FETCH_ASSOC);

                if ($contact && !empty($contact['contact_email'])) {
                    $order['email'] = $contact['contact_email'];
                }
            }
        } catch (Exception $e) {
            error_log("거래처 이메일 조회(사업자번호) 오류: " . $e->getMessage());
        }
    }

    // 3. 이메일을 못 찾았고 거래처명(상호)이 있으면 상호로 조회
    if (empty($order['email']) && !empty($order['contact_name'])) {
        try {
            // estimate_customer 테이블에서 company_name 또는 display_name으로 조회
            // estimate_book/setup_db.php에 따르면 email 컬럼이 존재함
            $custSql = "SELECT email FROM estimate_customer 
                        WHERE (company_name = :name OR display_name = :name) 
                        AND is_deleted = 'N' 
                        LIMIT 1";
            $custStmt = $pdo->prepare($custSql);
            $custStmt->bindValue(':name', $order['contact_name']);
            $custStmt->execute();
            $customer = $custStmt->fetch(PDO::FETCH_ASSOC);

            if ($customer && !empty($customer['email'])) {
                $order['email'] = $customer['email'];
            }
        } catch (Exception $e) {
            error_log("거래처 이메일 조회(상호) 오류: " . $e->getMessage());
        }
    }
    } // End of if (empty($order['email']))
    
    // 성공 응답
    echo json_encode([
        'success' => true,
        'order' => $order
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("견적서 조회 오류: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => '데이터 조회 중 오류가 발생했습니다.'
    ]);
}

