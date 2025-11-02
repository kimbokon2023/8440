<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';
require_once __DIR__ . '/../api/file_api.php';

$isEdit = isset($_POST['order_id']) && !empty($_POST['order_id']);

try {
    // 신규 등록 시 발주번호 자동 생성
    if (!$isEdit) {
        $orderNumber = $_POST['order_number'];

        // 발주번호가 완성되지 않았으면 (끝에 -로 끝나면) 자동으로 일련번호 추가
        if (substr($orderNumber, -1) === '-') {
            $prefix = $orderNumber; // ON-20251022-

            // 같은 날짜의 마지막 발주번호 찾기
            $sql = "SELECT order_number FROM daon_orders
                    WHERE order_number LIKE ?
                    ORDER BY order_number DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$prefix . '%']);
            $lastOrder = $stmt->fetch();

            if ($lastOrder) {
                // 마지막 번호에서 일련번호 추출
                $lastNumber = intval(substr($lastOrder['order_number'], -3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // 새 발주번호 생성 (3자리 패딩)
            $orderNumber = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $_POST['order_number'] = $orderNumber;
        }
    }

    // 발주사항 JSON 처리
    $order_items = isset($_POST['order_items']) ? $_POST['order_items'] : null;
    
    // JSON 유효성 검사
    if ($order_items) {
        $decoded = json_decode($order_items, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('발주사항 데이터 형식 오류');
        }
    }
    
    if ($isEdit) {
        // 수정
        $sql = "UPDATE daon_orders SET
                order_date = ?,
                delivery_date = ?,
                billing_date = ?,
                payment_date = ?,
                customer_id = ?,
                status = ?,
                priority = ?,
                delivery_address = ?,
                note = ?,
                order_items = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['order_date'],
            $_POST['delivery_date'] ?: null,
            $_POST['billing_date'] ?: null,
            $_POST['payment_date'] ?: null,
            $_POST['customer_id'],
            $_POST['status'],
            $_POST['priority'],
            $_POST['delivery_address'] ?: null,
            $_POST['note'] ?: null,
            $order_items,
            $_POST['order_id']
        ]);

        $_SESSION['message'] = '발주가 수정되었습니다.';
        $_SESSION['message_type'] = 'success';

    } else {
        // 신규 등록
        $sql = "INSERT INTO daon_orders (
                order_number, order_date, delivery_date, billing_date, payment_date, customer_id,
                status, priority, delivery_address, note, order_items, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['order_number'],
            $_POST['order_date'],
            $_POST['delivery_date'] ?: null,
            $_POST['billing_date'] ?: null,
            $_POST['payment_date'] ?: null,
            $_POST['customer_id'],
            $_POST['status'] ?? 'pending',
            $_POST['priority'] ?? 'normal',
            $_POST['delivery_address'] ?: null,
            $_POST['note'] ?: null,
            $order_items,
            $_SESSION['daon_userid']
        ]);

        $newOrderId = $pdo->lastInsertId();
        
        error_log("신규 발주 등록 완료: order_id = {$newOrderId}");
        
        // 임시 파일번호를 실제 order_id로 업데이트
        if (isset($_POST['temp_parentnum']) && !empty($_POST['temp_parentnum'])) {
            $tempParentNum = $_POST['temp_parentnum'];
            
            error_log("temp_parentnum 확인: {$tempParentNum}");
            
            try {
                $fileOptions = [
                    'tablename' => 'daon_orders',
                    'item' => 'attached',
                    'old_parentnum' => $tempParentNum,
                    'new_parentnum' => $newOrderId,
                    'DBtable' => 'picuploads'
                ];
                
                error_log("파일 업데이트 옵션: " . json_encode($fileOptions));
                
                $result = updateFileIdsInGoogleDrive($fileOptions);
                
                error_log("파일 번호 업데이트 결과: " . json_encode($result));
            } catch (Exception $e) {
                error_log("파일 번호 업데이트 오류: " . $e->getMessage());
            }
        } else {
            error_log("temp_parentnum이 없습니다. POST 데이터: " . json_encode($_POST));
        }

        $_SESSION['message'] = '발주가 등록되었습니다.';
        $_SESSION['message_type'] = 'success';
    }

    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['message'] = '오류가 발생했습니다: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    header('Location: ' . ($isEdit ? 'order_form.php?id=' . $_POST['order_id'] : 'order_form.php'));
    exit;
}
?>
