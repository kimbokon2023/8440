<?php
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
    
    // 입력 데이터 검증 및 정리
    $classification = trim($_POST['classification'] ?? '사업자');
    $trade_name = trim($_POST['trade_name'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    $registration_number = trim($_POST['registration_number'] ?? '');
    $representative_name = trim($_POST['representative_name'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $mobile_number = trim($_POST['mobile_number'] ?? '');
    $fax_number = trim($_POST['fax_number'] ?? '');
    $business_type = trim($_POST['business_type'] ?? '');
    $business_category = trim($_POST['business_category'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $business_registration_number = trim($_POST['business_registration_number'] ?? '');
    $registration_date = $_POST['registration_date'] ?? date('Y-m-d');
    
    // 그룹 정보
    $is_sales_customer = isset($_POST['is_sales_customer']) ? 'Y' : 'N';
    $is_purchase_customer = isset($_POST['is_purchase_customer']) ? 'Y' : 'N';
    $is_other_customer = isset($_POST['is_other_customer']) ? 'Y' : 'N';
    
    // 계좌 정보
    $bank_name = trim($_POST['bank_name'] ?? '');
    $account_number = trim($_POST['account_number'] ?? '');
    $account_holder = trim($_POST['account_holder'] ?? '');
    $my_account_id = intval($_POST['my_account_id'] ?? 0);
    
    // 필수 필드 검증
    if (empty($company_name)) {
        throw new Exception('거래처명은 필수 입력 항목입니다.');
    }
    
    // 거래처명 중복 검사
    $checkSQL = "SELECT COUNT(*) as count FROM mirae8440.customer WHERE company_name = ? AND is_deleted = 'N'";
    $checkStmt = $pdo->prepare($checkSQL);
    $checkStmt->execute([$company_name]);
    $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count > 0) {
        throw new Exception('이미 등록된 거래처명입니다.');
    }
    
    // 사업자번호 중복 검사 (사업자번호가 입력된 경우)
    if (!empty($business_registration_number)) {
        $checkSQL = "SELECT COUNT(*) as count FROM mirae8440.customer WHERE business_registration_number = ? AND is_deleted = 'N'";
        $checkStmt = $pdo->prepare($checkSQL);
        $checkStmt->execute([$business_registration_number]);
        $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($count > 0) {
            throw new Exception('이미 등록된 사업자번호입니다.');
        }
    }
    
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    // 거래처 정보 삽입
    $insertSQL = "INSERT INTO mirae8440.customer (
        classification,
        trade_name,
        company_name, 
        registration_number, 
        representative_name, 
        phone_number, 
        mobile_number, 
        fax_number, 
        business_type, 
        business_category, 
        remarks, 
        address, 
        business_registration_number, 
        registration_date,
        is_sales_customer,
        is_purchase_customer,
        is_other_customer,
        bank_name,
        account_number,
        account_holder,
        my_account_id,
        created_at,
        is_deleted
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'N')";
    
    $stmt = $pdo->prepare($insertSQL);
    $result = $stmt->execute([
        $classification,
        $trade_name,
        $company_name,
        $registration_number,
        $representative_name,
        $phone_number,
        $mobile_number,
        $fax_number,
        $business_type,
        $business_category,
        $remarks,
        $address,
        $business_registration_number,
        $registration_date,
        $is_sales_customer,
        $is_purchase_customer,
        $is_other_customer,
        $bank_name,
        $account_number,
        $account_holder,
        $my_account_id
    ]);
    
    if (!$result) {
        throw new Exception('거래처 정보 저장에 실패했습니다.');
    }
    
    $customerId = $pdo->lastInsertId();
    
    // 담당자 정보 처리
    if (isset($_POST['contact_name']) && is_array($_POST['contact_name'])) {
        $contactNames = $_POST['contact_name'];
        $contactPhones = $_POST['contact_phone'] ?? [];
        $contactEmails = $_POST['contact_email'] ?? [];
        $contactRemarks = $_POST['contact_remarks'] ?? [];
        $isInvoiceContacts = $_POST['is_invoice_contact'] ?? [];
        $positionDepartments = $_POST['position_department'] ?? [];
        
        $contactInsertSQL = "INSERT INTO mirae8440.customer_contact (
            customer_id,
            contact_name,
            contact_phone,
            contact_email,
            contact_remarks,
            is_invoice_contact,
            position_department,
            created_at,
            is_deleted
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'N')";
        
        $contactStmt = $pdo->prepare($contactInsertSQL);
        
        for ($i = 0; $i < count($contactNames); $i++) {
            $contactName = trim($contactNames[$i]);
            if (!empty($contactName)) {
                $contactPhone = isset($contactPhones[$i]) ? trim($contactPhones[$i]) : '';
                $contactEmail = isset($contactEmails[$i]) ? trim($contactEmails[$i]) : '';
                $contactRemark = isset($contactRemarks[$i]) ? trim($contactRemarks[$i]) : '';
                $isInvoiceContact = isset($isInvoiceContacts[$i]) ? 'Y' : 'N';
                $positionDepartment = isset($positionDepartments[$i]) ? trim($positionDepartments[$i]) : '';
                
                $contactStmt->execute([
                    $customerId,
                    $contactName,
                    $contactPhone,
                    $contactEmail,
                    $contactRemark,
                    $isInvoiceContact,
                    $positionDepartment
                ]);
            }
        }
    }
    
    // 첨부파일 처리 (향후 구현)
    // TODO: 파일 업로드 처리
    
    // 트랜잭션 커밋
    $pdo->commit();
    
    // 로그 기록
    error_log("새 거래처 등록: ID={$customerId}, 거래처명={$company_name}");
    
    echo json_encode([
        'success' => true, 
        'message' => '거래처가 성공적으로 등록되었습니다.',
        'id' => $customerId
    ]);
    
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("거래처 등록 DB 오류: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => '데이터베이스 오류가 발생했습니다: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>