<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// JSON 헤더 설정
header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';

// 요청 파라미터 초기화
$tablename = $_REQUEST['tablename'] ?? 'delivery';
$mode = $_REQUEST['mode'] ?? '';
$num = $_REQUEST['num'] ?? '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

include "_request.php";

// 검색 태그 생성
$searchtag = $registedate . ' ' .
             $receiver . ' ' .
             $receiver_tel . ' ' .
             $address . ' ' .
             $sender . ' ' .
             $item_name . ' ' .
             $unit . ' ' .
             $surang . ' ' .
             $fee . ' ' .
             $fee_type . ' ' .
             $goods_price;

// 업데이트 로그 생성
$update_log = date("Y-m-d H:i:s") . " - " . $user_name . " " . ($update_log ?? '') . "&#10";

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'num' => $num,
    'mode' => $mode
);

// 수정 모드
if ($mode == "update") {
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} 
                SET registedate = ?, 
                    receiver = ?, 
                    receiver_tel = ?, 
                    address = ?, 
                    sender = ?, 
                    item_name = ?, 
                    unit = ?, 
                    surang = ?, 
                    fee = ?, 
                    fee_type = ?, 
                    goods_price = ?, 
                    update_log = ?, 
                    searchtag = ?
                WHERE num = ? 
                LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        
        // 바인딩
        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $receiver, PDO::PARAM_STR);
        $stmh->bindValue(3, $receiver_tel, PDO::PARAM_STR);
        $stmh->bindValue(4, $address, PDO::PARAM_STR);
        $stmh->bindValue(5, $sender, PDO::PARAM_STR);
        $stmh->bindValue(6, $item_name, PDO::PARAM_STR);
        $stmh->bindValue(7, $unit, PDO::PARAM_STR);
        $stmh->bindValue(8, str_replace(',', '', $surang), PDO::PARAM_STR);
        $stmh->bindValue(9, str_replace(',', '', $fee), PDO::PARAM_STR);
        $stmh->bindValue(10, $fee_type, PDO::PARAM_STR);
        $stmh->bindValue(11, str_replace(',', '', $goods_price), PDO::PARAM_STR);
        $stmh->bindValue(12, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(13, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(14, $num, PDO::PARAM_INT);
        
        $stmh->execute();
        $pdo->commit();
        
        $response['success'] = true;
        $response['message'] = '수정되었습니다.';
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("배송 정보 수정 오류: " . $ex->getMessage());
        $response['message'] = '수정 중 오류가 발생했습니다.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 신규 등록 모드 (insert, copy)
if ($mode == "insert" || $mode == "copy" || $mode == '' || $mode == null) {
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.{$tablename} 
                (registedate, receiver, receiver_tel, address, sender, 
                 item_name, unit, surang, fee, fee_type, goods_price, 
                 update_log, searchtag) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        
        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $receiver, PDO::PARAM_STR);
        $stmh->bindValue(3, $receiver_tel, PDO::PARAM_STR);
        $stmh->bindValue(4, $address, PDO::PARAM_STR);
        $stmh->bindValue(5, $sender, PDO::PARAM_STR);
        $stmh->bindValue(6, $item_name, PDO::PARAM_STR);
        $stmh->bindValue(7, $unit, PDO::PARAM_STR);
        $stmh->bindValue(8, str_replace(',', '', $surang), PDO::PARAM_STR);
        $stmh->bindValue(9, str_replace(',', '', $fee), PDO::PARAM_STR);
        $stmh->bindValue(10, $fee_type, PDO::PARAM_STR);
        $stmh->bindValue(11, str_replace(',', '', $goods_price), PDO::PARAM_STR);
        $stmh->bindValue(12, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(13, $searchtag, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
        
        $response['success'] = true;
        $response['message'] = '등록되었습니다.';
        $response['num'] = $pdo->lastInsertId();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("배송 정보 등록 오류: " . $ex->getMessage());
        $response['message'] = '등록 중 오류가 발생했습니다.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 삭제 모드 (Soft Delete)
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} 
                SET is_deleted = 1 
                WHERE num = ?";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit();
        
        $response['success'] = true;
        $response['message'] = '삭제되었습니다.';
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("배송 정보 삭제 오류: " . $ex->getMessage());
        $response['message'] = '삭제 중 오류가 발생했습니다.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 성공 응답에 추가 데이터 포함
if ($response['success']) {
    $response['data'] = array(
        'num' => $num,
        'mode' => $mode,
        'receiver' => $receiver,
        'receiver_tel' => $receiver_tel,
        'address' => $address,
        'sender' => $sender,
        'item_name' => $item_name,
        'unit' => $unit,
        'surang' => $surang,
        'fee' => $fee,
        'fee_type' => $fee_type,
        'goods_price' => $goods_price
    );
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>
