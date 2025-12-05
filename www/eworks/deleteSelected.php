<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$selectedIds = $_REQUEST["selectedIds"] ?? array();

// 데이터베이스 연결
$pdo = db_connect();

$last_e_num = null;

try {
    // 선택된 항목들에 대해 숨김 처리
    foreach ($selectedIds as $e_num) {
        $last_e_num = $e_num;
        
        // 현재 숨김 대상 ID 조회
        $sql_select = "SELECT e_viewexcept_id FROM {$DB}.eworks WHERE num = ?";
        $stmh_select = $pdo->prepare($sql_select);
        $stmh_select->bindValue(1, $e_num, PDO::PARAM_INT);
        $stmh_select->execute();
        $row = $stmh_select->fetch(PDO::FETCH_ASSOC);
        
        $e_viewexcept_id = $row ? $row['e_viewexcept_id'] : '';
        
        // 숨김 대상 ID 추가
        if ($e_viewexcept_id === '' || $e_viewexcept_id === null) {
            $e_viewexcept_id_new = $user_id;
        } else {
            $e_viewexcept_id_new = $e_viewexcept_id . '!' . $user_id;
        }
        
        // 데이터베이스 업데이트
        $sql_update = "UPDATE {$DB}.eworks SET e_viewexcept_id = ? WHERE num = ?";
        $stmh_update = $pdo->prepare($sql_update);
        $stmh_update->execute(array($e_viewexcept_id_new, $e_num));
    }
    
    // 성공 응답
    $data = array(
        "success" => true,
        "num" => $last_e_num,
        "selectedIds" => $selectedIds,
        "message" => "선택된 항목이 숨김 처리되었습니다."
    );
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $ex) {
    error_log("숨김 처리 오류: " . $ex->getMessage());
    
    http_response_code(500);
    echo json_encode(array(
        'success' => false,
        'error' => 'Database processing error',
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>
