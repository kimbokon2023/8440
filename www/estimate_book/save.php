<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json; charset=utf-8');

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if ($level > 5) {
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $pdo = db_connect();

    $mode = $_POST['mode'] ?? 'insert';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    // 데이터 정리
    $display_name = trim($_POST['display_name'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile_phone = trim($_POST['mobile_phone'] ?? '');
    $work_phone = trim($_POST['work_phone'] ?? '');
    $home_phone = trim($_POST['home_phone'] ?? '');
    $memo = trim($_POST['memo'] ?? '');

    // 필수값 체크 (최소한 이름이나 회사는 있어야 함)
    if (empty($display_name) && empty($company_name) && empty($email)) {
        throw new Exception('표시 이름, 회사, 이메일 중 하나는 입력해야 합니다.');
    }

    if ($mode === 'update' && $id > 0) {
        // 수정
        $sql = "UPDATE estimate_customer SET 
                display_name = ?, 
                company_name = ?, 
                department = ?, 
                email = ?, 
                mobile_phone = ?, 
                work_phone = ?, 
                home_phone = ?, 
                memo = ?,
                last_modified_date = NOW()
                WHERE num = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $display_name, $company_name, $department, $email, 
            $mobile_phone, $work_phone, $home_phone, $memo, 
            $id
        ]);
    } else {
        // 등록
        $sql = "INSERT INTO estimate_customer (
                display_name, company_name, department, email, 
                mobile_phone, work_phone, home_phone, memo,
                classification, created_at, is_deleted
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, '주소록', NOW(), 'N')";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $display_name, $company_name, $department, $email, 
            $mobile_phone, $work_phone, $home_phone, $memo
        ]);
        $id = $pdo->lastInsertId();
    }

    if ($result) {
        echo json_encode(['success' => true, 'message' => '저장되었습니다.', 'id' => $id]);
    } else {
        throw new Exception('저장에 실패했습니다.');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>