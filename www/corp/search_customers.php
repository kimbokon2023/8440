<?php
/**
 * 거래처 검색 API
 * orders/write_form.php에서 사용
 */

require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 검색어 가져오기
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($searchTerm)) {
    echo json_encode(array(
        'success' => false,
        'message' => '검색어를 입력해주세요.'
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

require_once(includePath('lib/mydb.php'));

try {
    $pdo = db_connect();
    
    // 거래처 검색 (거래처명, 사업자번호로 검색)
    $sql = "SELECT 
                num,
                company_name,
                business_registration_number,
                representative_name,
                phone_number,
                fax_number
            FROM {$DB}.customer 
            WHERE is_deleted = 'N' 
            AND (
                company_name LIKE :search1 
                OR business_registration_number LIKE :search2
                OR trade_name LIKE :search3
            )
            ORDER BY company_name ASC
            LIMIT 50";
    
    $stmt = $pdo->prepare($sql);
    $searchPattern = '%' . $searchTerm . '%';
    $stmt->execute(array(
        ':search1' => $searchPattern,
        ':search2' => $searchPattern,
        ':search3' => $searchPattern
    ));
    
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(array(
        'success' => true,
        'customers' => $customers,
        'count' => count($customers)
    ), JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $ex) {
    error_log("거래처 검색 오류: " . $ex->getMessage());
    echo json_encode(array(
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다.'
    ), JSON_UNESCAPED_UNICODE);
} catch (Exception $ex) {
    error_log("거래처 검색 오류: " . $ex->getMessage());
    echo json_encode(array(
        'success' => false,
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}
?>

