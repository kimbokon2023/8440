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
    
    $isEdit = isset($_POST['product_id']) && !empty($_POST['product_id']);
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    // 세션 사용자 ID 확인
    if (!isset($_SESSION['daon_userid']) || empty($_SESSION['daon_userid'])) {
        throw new Exception('로그인이 필요합니다.');
    }
    
    // 필수 필드 확인
    if (empty($_POST['product_name'])) {
        throw new Exception('제품명은 필수 입력 항목입니다.');
    }
    
    if ($isEdit) {
        // 수정
        $sql = "UPDATE daon_products SET
                product_code = ?,
                product_name = ?,
                product_type = ?,
                spec = ?,
                unit = ?,
                standard_price = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['product_code'] ?? null,
            $_POST['product_name'] ?? '',
            $_POST['product_type'] ?? null,
            $_POST['spec'] ?? null,
            $_POST['unit'] ?? 'EA',
            $_POST['standard_price'] ? str_replace(',', '', $_POST['standard_price']) : null,
            $_POST['product_id'] ?? null
        ]);

        $productId = $_POST['product_id'];

    } else {
        // 신규 등록
        // 제품코드 자동 생성 (입력되지 않은 경우)
        $product_code = $_POST['product_code'] ?? '';
        if (empty($product_code)) {
            // 가장 최근 제품코드 조회
            $sql_last = "SELECT product_code FROM daon_products ORDER BY id DESC LIMIT 1";
            $stmt_last = $pdo->query($sql_last);
            $last_product = $stmt_last->fetch();
            
            if ($last_product && preg_match('/^P(\d+)$/', $last_product['product_code'], $matches)) {
                $next_num = intval($matches[1]) + 1;
                $product_code = 'P' . str_pad($next_num, 4, '0', STR_PAD_LEFT);
            } else {
                $product_code = 'P0001';
            }
        }
        
        $sql = "INSERT INTO daon_products (
                product_code, product_name, product_type, spec, unit,
                standard_price, status, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $product_code,
            $_POST['product_name'] ?? '',
            $_POST['product_type'] ?? null,
            $_POST['spec'] ?? null,
            $_POST['unit'] ?? 'EA',
            $_POST['standard_price'] ? str_replace(',', '', $_POST['standard_price']) : null,
            'active',
            $_SESSION['daon_userid'] ?? null
        ]);

        $productId = $pdo->lastInsertId();
    }

    // AJAX 요청인 경우 JSON 응답
    if ($isAjax) {
        echo json_encode([
            'success' => true,
            'product_id' => $productId,
            'message' => $isEdit ? '제품이 수정되었습니다.' : '제품이 등록되었습니다.'
        ], JSON_UNESCAPED_UNICODE);
        ob_end_flush();
        exit;
    }

    // 일반 폼 제출인 경우 리다이렉트
    ob_end_clean();
    header('Location: product_list.php');
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
