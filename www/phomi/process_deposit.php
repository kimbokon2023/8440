<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db_connect();
    
    $mode = $_POST['mode'] ?? '';
    $tablename = $_POST['tablename'] ?? 'phomi_deposit';
    $num = $_POST['num'] ?? '';
    
    // 기본 데이터 수집
    $deposit_date = $_POST['deposit_date'] ?? date('Y-m-d');
    $deposit_amount = $_POST['deposit_amount'] ?? 0;
    $note = $_POST['note'] ?? '';
    
   
    switch($mode) {
        case 'insert':
            // 새 본사 예치금 등록
            $sql = "INSERT INTO {$DB}.phomi_deposit (
                deposit_date, deposit_amount, note, createdAt, updatedAt, is_deleted
            ) VALUES (
                :deposit_date, :deposit_amount, :note, NOW(), NOW(), 'N'
            )";                    
           
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':deposit_date', $deposit_date);
            $stmt->bindParam(':deposit_amount', $deposit_amount);
            $stmt->bindParam(':note', $note);
            
            $stmt->execute();
            $new_num = $pdo->lastInsertId();
            
            echo json_encode([
                'result' => 'success',
                'success' => true,
                'message' => '본사 예치금이 성공적으로 등록되었습니다.',
                'num' => $new_num
            ]);
            break;
            
        case 'modify':
            // 본사 예치금 수정
            if(empty($num)) {
                throw new Exception('수정할 본사 예치금 번호가 없습니다.');
            }
            
            $sql = "UPDATE {$DB}.phomi_deposit SET 
                deposit_date = :deposit_date,
                deposit_amount = :deposit_amount,
                note = :note,
                updatedAt = NOW()
                WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $num, PDO::PARAM_INT);
            $stmt->bindParam(':deposit_date', $deposit_date);
            $stmt->bindParam(':deposit_amount', $deposit_amount);
            $stmt->bindParam(':note', $note);
            
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'result' => 'success',
                    'success' => true,
                    'message' => '본사 예치금이 성공적으로 수정되었습니다.',
                    'num' => $num
                ]);
            } else {
                echo json_encode([
                    'result' => 'error',
                    'success' => false,
                    'message' => '수정할 본사 예치금을 찾을 수 없습니다.'
                ]);
            }
            break;
            
        case 'delete':
            // 본사 예치금 삭제 (논리 삭제)
            if(empty($num)) {
                throw new Exception('삭제할 본사 예치금 번호가 없습니다.');
            }
            
            $sql = "UPDATE {$DB}.phomi_deposit SET is_deleted = 'Y', updatedAt = NOW() WHERE num = :num";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $num, PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'result' => 'success',
                    'success' => true,
                    'message' => '본사 예치금이 성공적으로 삭제되었습니다.'
                ]);
            } else {
                echo json_encode([
                    'result' => 'error',
                    'success' => false,
                    'message' => '삭제할 본사 예치금을 찾을 수 없습니다.'
                ]);
            }
            break;
            
        case 'get':
            // 본사 예치금 조회
            if(empty($num)) {
                throw new Exception('조회할 본사 예치금 번호가 없습니다.');
            }
            
            $sql = "SELECT * FROM {$DB}.phomi_deposit WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $num, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result) {
                echo json_encode([
                    'result' => 'success',
                    'success' => true,
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'result' => 'error',
                    'success' => false,
                    'message' => '조회할 본사 예치금을 찾을 수 없습니다.'
                ]);
            }
            break;
            
        case 'list':
            // 본사 예치금 목록 조회
            $fromdate = $_POST['fromdate'] ?? date('Y-m-d', strtotime('-3 months'));
            $todate = $_POST['todate'] ?? date('Y-m-d');
            
            $sql = "SELECT * FROM {$DB}.phomi_deposit 
                    WHERE deposit_date BETWEEN :fromdate AND :todate 
                    AND (is_deleted IS NULL OR is_deleted = 'N') 
                    ORDER BY deposit_date DESC, num DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':fromdate', $fromdate);
            $stmt->bindParam(':todate', $todate);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'result' => 'success',
                'success' => true,
                'data' => $results
            ]);
            break;
            
        default:
            throw new Exception('잘못된 모드입니다.');
    }
    
} catch (Exception $e) {
    error_log("본사 예치금 처리 오류: " . $e->getMessage());
    echo json_encode([
        'result' => 'error',
        'success' => false,
        'message' => '오류가 발생했습니다: ' . $e->getMessage()
    ]);
} catch (PDOException $e) {
    error_log("데이터베이스 오류: " . $e->getMessage());
    echo json_encode([
        'result' => 'error',
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다.'
    ]);
} catch (Error $e) {
    error_log("시스템 오류: " . $e->getMessage());
    echo json_encode([
        'result' => 'error',
        'success' => false,
        'message' => '시스템 오류가 발생했습니다.'
    ]);
}
?>
