<?php
// 생산완료확인 업데이트 API - 로컬/서버 환경 호환
header("Content-Type: application/json; charset=UTF-8");

// 입력값 검증 및 초기화
$num = $_REQUEST["num"] ?? '';
$madeconfirm = '1';

// 입력값 유효성 검사
if (empty($num) || !is_numeric($num)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => '유효하지 않은 번호입니다.',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Bootstrap을 통한 환경별 초기화
require_once __DIR__ . '/../bootstrap.php';

// Database connection is already available from bootstrap.php
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => '데이터베이스 연결에 실패했습니다.',
            'error' => isLocal() ? $e->getMessage() : '시스템 오류'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE mirae8440.work SET madeconfirm = ? WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $madeconfirm, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_INT);
    
    $result = $stmh->execute();
    $affectedRows = $stmh->rowCount();
    
    if ($affectedRows > 0) {
        $pdo->commit();
        
        // 성공 응답
        $data = [
            'success' => true,
            'message' => '확인처리가 완료되었습니다.',
            'num' => $num,
            'madeconfirm' => $madeconfirm,
            'affected_rows' => $affectedRows
        ];
    } else {
        $pdo->rollBack();
        
        // 업데이트된 행이 없는 경우
        $data = [
            'success' => false,
            'message' => '해당 번호의 데이터를 찾을 수 없습니다.',
            'num' => $num
        ];
    }
     
} catch (PDOException $Exception) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // 에러 로깅
    error_log("Database error in update_madeconfirm.php: " . $Exception->getMessage());
    
    // 에러 응답
    http_response_code(500);
    $data = [
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다.',
        'error' => isLocal() ? $Exception->getMessage() : '시스템 오류',
        'num' => $num
    ];
}
// JSON 응답 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);