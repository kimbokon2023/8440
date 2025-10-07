<?php
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db_connect();
    
    // POST 데이터 받기
    $mode = $_POST['mode'] ?? '';
    $num = $_POST['num'] ?? '';
    $tablename = $_POST['tablename'] ?? 'phomi_outorder';
    
    // 기본 필드들
    $out_date = $_POST['out_date'] ?? '';
    $customer = $_POST['customer'] ?? '';
    $manager = $_POST['manager'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $dispatch_type = $_POST['dispatch_type'] ?? '';
    $area_sqm = $_POST['area_sqm'] ?? '';
    $construction_done = $_POST['construction_done'] ?? '';
    $note = $_POST['note'] ?? '';
    $order_date = $_POST['order_date'] ?? '';
    $order_num = $_POST['order_num'] ?? ''; 
    $recipient_name = $_POST['recipient_name'] ?? '';
    $recipient_phone = $_POST['recipient_phone'] ?? '';
    // JSON 데이터 파싱
    $items_json = $_POST['items_json'] ?? '[]';
    $items = json_decode($items_json, true) ?? [];
    
    // 현재 시간
    $current_time = date('Y-m-d H:i:s');
    
    // 사용자명 가져오기
    $user_name = $_SESSION['user_name'] ?? 'Unknown';
    
    // items를 JSON 문자열로 변환
    $items_json_string = json_encode($items);
    
    // update_log 처리 함수
    function updateLog($pdo, $num, $user_name, $current_time, $mode) {
        global $DB;
        
        $new_log_entry = $user_name . " - " . $current_time . " (" . $mode . ")";
        
        if ($mode == 'insert') {
            return $new_log_entry;
        } else {
            // 기존 로그 가져오기
            $sql = "SELECT update_log FROM {$DB}.phomi_outorder WHERE num = :num";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $num, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $existing_log = $row['update_log'] ?? '';
            if (!empty($existing_log)) {
                return $existing_log . "\n" . $new_log_entry;
            } else {
                return $new_log_entry;
            }
        }
    }
    
    if ($mode == 'insert' || $mode == 'copy') {
        // 새 출고증 등록 (copy 모드도 동일한 로직)
        
        // update_log 생성
        $update_log = updateLog($pdo, null, $user_name, $current_time, 'insert');
        
        $sql = "INSERT INTO {$DB}.phomi_outorder (
            out_date, customer, manager, address, contact, 
            dispatch_type, area_sqm, construction_done, note, items,
            update_log, order_date, order_num, recipient_name, recipient_phone, createdAt, updatedAt
        ) VALUES (
            :out_date, :customer, :manager, :address, :contact,
            :dispatch_type, :area_sqm, :construction_done, :note, :items,
            :update_log, :order_date, :order_num, :recipient_name, :recipient_phone, :createdAt, :updatedAt
        )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':out_date', $out_date);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':manager', $manager);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':dispatch_type', $dispatch_type);
        $stmt->bindParam(':area_sqm', $area_sqm);
        $stmt->bindParam(':construction_done', $construction_done);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':items', $items_json_string);
        $stmt->bindParam(':update_log', $update_log);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->bindParam(':order_num', $order_num);
        $stmt->bindParam(':recipient_name', $recipient_name);
        $stmt->bindParam(':recipient_phone', $recipient_phone);
        $stmt->bindParam(':createdAt', $current_time);
        $stmt->bindParam(':updatedAt', $current_time);
        
        $stmt->execute();
        $new_num = $pdo->lastInsertId();
        
        echo json_encode([
            'result' => 'success',
            'message' => '출고증이 성공적으로 등록되었습니다.',
            'num' => $new_num
        ]);
        
    } elseif ($mode == 'modify') {
        // 출고증 수정
        if (empty($num)) {
            throw new Exception('수정할 출고증 번호가 없습니다.');
        }
        
        // update_log 업데이트
        $update_log = updateLog($pdo, $num, $user_name, $current_time, 'modify');
        
        $sql = "UPDATE {$DB}.phomi_outorder SET 
            out_date = :out_date,
            customer = :customer,
            manager = :manager,
            address = :address,
            contact = :contact,
            dispatch_type = :dispatch_type,
            area_sqm = :area_sqm,
            construction_done = :construction_done,
            note = :note,
            items = :items,
            update_log = :update_log,
            order_date = :order_date,
            order_num = :order_num,
            recipient_name = :recipient_name,
            recipient_phone = :recipient_phone,
            updatedAt = :updatedAt
            WHERE num = :num";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':out_date', $out_date);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':manager', $manager);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':dispatch_type', $dispatch_type);
        $stmt->bindParam(':area_sqm', $area_sqm);
        $stmt->bindParam(':construction_done', $construction_done);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':items', $items_json_string);
        $stmt->bindParam(':update_log', $update_log);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->bindParam(':order_num', $order_num);
        $stmt->bindParam(':recipient_name', $recipient_name);
        $stmt->bindParam(':recipient_phone', $recipient_phone);
        $stmt->bindParam(':updatedAt', $current_time);
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        
        $stmt->execute();
        
        echo json_encode([
            'result' => 'success',
            'message' => '출고증이 성공적으로 수정되었습니다.',
            'num' => $num
        ]);
        
    } else {
        throw new Exception('잘못된 모드입니다.');
    }

    // mode가 delete인 경우는 수주서에 출고일을 제거하고, 다른 mode일때는 출고일을 기록한다.
    // order 테이블의 	delivery_date 컬럼 수정
    if($mode == 'delete') {
        $sql = "UPDATE {$DB}.phomi_order SET delivery_date = NULL WHERE num = :num";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':num', $order_num, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $sql = "UPDATE {$DB}.phomi_order SET delivery_date = :delivery_date WHERE num = :num";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':delivery_date', $out_date);
        $stmt->bindParam(':num', $order_num, PDO::PARAM_INT);
        $stmt->execute();
    }   
    
} catch (Exception $e) {
    echo json_encode([
        'result' => 'error',
        'message' => '오류가 발생했습니다: ' . $e->getMessage()
    ]);
}
?>
