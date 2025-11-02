<?php
// 출력 버퍼링 시작 (모든 출력 제어)
ob_start();

// 에러 리포팅 설정 (Notice 포함 모든 에러 출력 비활성화)
error_reporting(0);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

try {
    require_once __DIR__ . '/../bootstrap.php';
    require_once __DIR__ . '/login/check_login.php';
    
    // include 파일이 설정을 덮어쓸 수 있으므로 다시 설정
    error_reporting(0);
    ini_set('display_errors', '0');
    
    // 이전 출력 버퍼 비우기
    ob_clean();
    
    // JSON 응답을 위한 헤더 설정
    header('Content-Type: application/json; charset=utf-8');
    
    $isEdit = isset($_POST['customer_id']) && !empty($_POST['customer_id']);
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    // 세션 사용자 ID 확인
    if (!isset($_SESSION['daon_userid']) || empty($_SESSION['daon_userid'])) {
        throw new Exception('로그인이 필요합니다.');
    }
    
    // 필수 필드 확인
    if (empty($_POST['company_name'])) {
        throw new Exception('회사명은 필수 입력 항목입니다.');
    }
    
    if ($isEdit) {
        // 수정
        $sql = "UPDATE daon_customers SET
                company_name = ?,
                business_number = ?,
                ceo_name = ?,
                address = ?,
                tel = ?,
                fax = ?,
                email = ?,
                manager_name = ?,
                manager_tel = ?,
                note = ?,
                status = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['company_name'] ?? '',
            $_POST['business_number'] ?? null,
            $_POST['ceo_name'] ?? null,
            $_POST['address'] ?? null,
            $_POST['tel'] ?? null,
            $_POST['fax'] ?? null,
            $_POST['email'] ?? null,
            $_POST['manager_name'] ?? null,
            $_POST['manager_tel'] ?? null,
            $_POST['note'] ?? null,
            $_POST['status'] ?? 'active',
            $_POST['customer_id'] ?? null
        ]);

        $customerId = $_POST['customer_id'];

    } else {
        // 신규 등록
        $sql = "INSERT INTO daon_customers (
                company_name, business_number, ceo_name, address, tel, fax,
                email, manager_name, manager_tel, note, status, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['company_name'] ?? '',
            $_POST['business_number'] ?? null,
            $_POST['ceo_name'] ?? null,
            $_POST['address'] ?? null,
            $_POST['tel'] ?? null,
            $_POST['fax'] ?? null,
            $_POST['email'] ?? null,
            $_POST['manager_name'] ?? null,
            $_POST['manager_tel'] ?? null,
            $_POST['note'] ?? null,
            $_POST['status'] ?? 'active',
            $_SESSION['daon_userid'] ?? null
        ]);

        $customerId = $pdo->lastInsertId();
    }

    // AJAX 요청인 경우 JSON 응답
    if ($isAjax) {
        echo json_encode([
            'success' => true,
            'customer_id' => $customerId,
            'message' => $isEdit ? '거래처가 수정되었습니다.' : '거래처가 등록되었습니다.'
        ], JSON_UNESCAPED_UNICODE);
        ob_end_flush();
        exit;
    }

    // 일반 폼 제출인 경우 리다이렉트
    ob_end_clean();
    header('Location: customer_list.php');
    exit;

} catch (PDOException $e) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    echo json_encode([
        'success' => false,
        'message' => 'DB 오류: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit;
    
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit;
}
?>
