<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db_connect();
    
    $mode = $_POST['mode'] ?? '';
    $expense_date = $_POST['expense_date'] ?? '';
    $num = $_POST['num'] ?? '';
    $force_outcome = trim($_POST['force_outcome'] ?? '');
    
    if (empty($expense_date)) {
        throw new Exception('날짜가 필요합니다.');
    }
    
    // 날짜 형식 검증
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expense_date)) {
        throw new Exception('올바른 날짜 형식이 아닙니다.');
    }
    
    // 빈 문자열이나 0은 NULL로 처리 (복원 기능)
    if ($force_outcome === '' || $force_outcome === '0') {
        $force_outcome = null;
    }
    // '매장'은 그대로 저장 (텍스트로 저장)
    // 숫자는 그대로 저장
    
    switch($mode) {
        case 'save':
            // num이 있으면 해당 레코드 업데이트, 없으면 해당 날짜의 첫 번째 레코드 찾기
            if (empty($num)) {
                // 해당 날짜의 첫 번째 입금 레코드 찾기
                $find_sql = "SELECT num FROM {$DB}.phomi_deposit 
                             WHERE deposit_date = :expense_date 
                             AND (is_deleted IS NULL OR is_deleted = 'N')
                             ORDER BY num ASC 
                             LIMIT 1";
                $find_stmt = $pdo->prepare($find_sql);
                $find_stmt->bindParam(':expense_date', $expense_date);
                $find_stmt->execute();
                $found = $find_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($found) {
                    $num = $found['num'];
                } else {
                    // 해당 날짜에 입금 레코드가 없으면 새로 생성
                    $insert_sql = "INSERT INTO {$DB}.phomi_deposit 
                                    (deposit_date, deposit_amount, force_outcome, note, createdAt, updatedAt, is_deleted) 
                                    VALUES (:expense_date, 0, :force_outcome, '강제 지출 금액', NOW(), NOW(), 'N')";
                    $insert_stmt = $pdo->prepare($insert_sql);
                    $insert_stmt->bindParam(':expense_date', $expense_date);
                    $insert_stmt->bindParam(':force_outcome', $force_outcome);
                    $insert_stmt->execute();
                    $num = $pdo->lastInsertId();
                }
            }
            
            // force_outcome이 NULL이면 삭제(복원)로 처리
            if ($force_outcome === null) {
                $sql = "UPDATE {$DB}.phomi_deposit 
                        SET force_outcome = NULL, updatedAt = NOW() 
                        WHERE num = :num 
                        AND (is_deleted IS NULL OR is_deleted = 'N')";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':num', $num, PDO::PARAM_INT);
                $stmt->execute();
                
                echo json_encode([
                    'result' => 'success',
                    'success' => true,
                    'message' => '강제 지출 금액이 제거되었습니다.',
                    'num' => $num
                ]);
            } else {
                // 해당 레코드의 force_outcome 업데이트 (숫자 또는 텍스트 모두 저장)
                $sql = "UPDATE {$DB}.phomi_deposit 
                        SET force_outcome = :force_outcome, updatedAt = NOW() 
                        WHERE num = :num 
                        AND (is_deleted IS NULL OR is_deleted = 'N')";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':force_outcome', $force_outcome);
                $stmt->bindParam(':num', $num, PDO::PARAM_INT);
                $stmt->execute();
                
                // rowCount가 0이어도 값이 동일한 경우일 수 있으므로 성공으로 처리
                echo json_encode([
                    'result' => 'success',
                    'success' => true,
                    'message' => '지출 금액이 저장되었습니다.',
                    'num' => $num
                ]);
            }
            break;
            
        case 'delete':
            // 강제 지출 금액 삭제 (force_outcome을 NULL로 설정)
            if (empty($num)) {
                // 해당 날짜의 첫 번째 레코드 찾기
                $find_sql = "SELECT num FROM {$DB}.phomi_deposit 
                             WHERE deposit_date = :expense_date 
                             AND (is_deleted IS NULL OR is_deleted = 'N')
                             ORDER BY num ASC 
                             LIMIT 1";
                $find_stmt = $pdo->prepare($find_sql);
                $find_stmt->bindParam(':expense_date', $expense_date);
                $find_stmt->execute();
                $found = $find_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($found) {
                    $num = $found['num'];
                } else {
                    throw new Exception('삭제할 레코드를 찾을 수 없습니다.');
                }
            }
            
            $sql = "UPDATE {$DB}.phomi_deposit 
                    SET force_outcome = NULL, updatedAt = NOW() 
                    WHERE num = :num 
                    AND (is_deleted IS NULL OR is_deleted = 'N')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $num, PDO::PARAM_INT);
            $stmt->execute();
            
            echo json_encode([
                'result' => 'success',
                'success' => true,
                'message' => '강제 지출 금액이 삭제되었습니다.'
            ]);
            break;
            
        case 'get':
            // 특정 날짜의 강제 지출 금액 조회
            $sql = "SELECT num, deposit_date, force_outcome FROM {$DB}.phomi_deposit 
                    WHERE deposit_date = :expense_date 
                    AND force_outcome IS NOT NULL 
                    AND force_outcome > 0
                    AND (is_deleted IS NULL OR is_deleted = 'N')
                    ORDER BY num ASC 
                    LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':expense_date', $expense_date);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'result' => 'success',
                'success' => true,
                'data' => $result
            ]);
            break;
            
        default:
            throw new Exception('잘못된 모드입니다.');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'result' => 'error',
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

