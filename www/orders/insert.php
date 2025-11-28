<?php
/**
 * 구매발주서 등록/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";
$WebSite = $base_url . '/';

// AJAX 요청 여부 확인
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// 디버그 로깅 함수
function debug_log($message, $data = null) {
    error_log("[ORDER_DEBUG] " . $message . ($data ? " - Data: " . print_r($data, true) : ""));
}

debug_log("=== INSERT.PHP 시작 ===");
debug_log("요청 메소드: " . $_SERVER['REQUEST_METHOD']);
debug_log("AJAX 요청: " . ($is_ajax ? "YES" : "NO"));
debug_log("POST 데이터", $_POST);

// 권한 확인
if (!isset($_SESSION["level"]) || $level > 5) {
    debug_log("권한 확인 실패 - level: " . $level);
    
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => '로그인이 필요합니다.',
            'redirect_url' => $WebSite . "login/login_form.php"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    sleep(1);
    header("Location: {$WebSite}login/login_form.php");
    exit;
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    debug_log("잘못된 요청 메소드: " . $_SERVER['REQUEST_METHOD']);
    
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'POST 요청만 허용됩니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    header('HTTP/1.0 405 Method Not Allowed');
    exit;
}

// 데이터베이스 연결
try {
    $pdo = db_connect();
    debug_log("데이터베이스 연결 성공");
} catch (Exception $e) {
    debug_log("데이터베이스 연결 실패: " . $e->getMessage());
    
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => '데이터베이스 연결 실패: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo "<script>alert('데이터베이스 연결 실패: " . addslashes($e->getMessage()) . "'); history.back();</script>";
    exit;
}

// 폼 데이터 받기
$action = $_POST['action'] ?? '';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$cart_items_param = $_POST['cart_items'] ?? ''; // 구매카트 항목 (저장 시 cart 컬럼 업데이트용)
debug_log("구매카트 항목 파라미터: " . var_export($cart_items_param, true));

// 새로운 발주서 번호 생성
$order_no = $_POST['order_no'] ?? '';
if (empty($order_no) && $action !== 'update') {
    $order_no = date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

// 필수 필드 검증
$required_fields = ['supplier_name', 'issue_date'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        debug_log("필수 필드 누락: " . $field);
        
        if ($is_ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => "필수 항목이 누락되었습니다: " . $field
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        echo "<script>alert('필수 항목이 누락되었습니다: " . $field . "'); history.back();</script>";
        exit;
    }
}

// 데이터 정제
$issue_date = $_POST['issue_date'];
$supplier_code = trim($_POST['supplier_code'] ?? '');
$supplier_name = trim($_POST['supplier_name']);
$supplier_address = trim($_POST['supplier_address'] ?? '');
$business_type = trim($_POST['business_type'] ?? '');
$business_item = trim($_POST['business_item'] ?? '');
$supplier_phone = trim($_POST['supplier_phone'] ?? '');
$supplier_fax = trim($_POST['supplier_fax'] ?? '');
$contact_name = trim($_POST['contact_name'] ?? '');
$business_registration_number = trim($_POST['business_registration_number'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$fax = trim($_POST['fax'] ?? '');
$project_site = trim($_POST['project_site'] ?? '');
$delivery_date = $_POST['delivery_date'] ?? null;
$delivery_location = trim($_POST['delivery_location'] ?? '');
$payment_terms = trim($_POST['payment_terms'] ?? '');
$note = trim($_POST['note'] ?? '');
$status = $_POST['status'] ?? 'draft';

// JSON 데이터 처리
$order_items_json = $_POST['order_items'] ?? '[]';
debug_log("받은 JSON 데이터: " . $order_items_json);

$order_items = json_decode($order_items_json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    debug_log("JSON 파싱 오류: " . json_last_error_msg());
    
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => '품목 데이터 형식이 올바르지 않습니다: ' . json_last_error_msg()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo "<script>alert('품목 데이터 형식이 올바르지 않습니다.'); history.back();</script>";
    exit;
}

debug_log("파싱된 품목 데이터", $order_items);

// 합계 계산
$subtotal = 0;
if (is_array($order_items)) {
    foreach ($order_items as $item) {
        if (isset($item['공급가액']) && is_numeric($item['공급가액'])) {
            $subtotal += floatval($item['공급가액']);
        }
    }
}

// 납기일 처리
if (!empty($delivery_date) && !DateTime::createFromFormat('Y-m-d', $delivery_date)) {
    $delivery_date = null;
}

// 발행일 유효성 검사
if (!DateTime::createFromFormat('Y-m-d', $issue_date)) {
    debug_log("발행일 형식 오류: " . $issue_date);
    
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => '발행일 형식이 올바르지 않습니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo "<script>alert('발행일 형식이 올바르지 않습니다.'); history.back();</script>";
    exit;
}

// 상태 값 검증
$valid_statuses = ['draft', 'sent', 'completed'];
if (!in_array($status, $valid_statuses)) {
    $status = 'draft';
}

try {
    if ($action === 'update' && $id > 0) {
        // 수정 작업
        // 기존 데이터 존재 확인
        $check_stmt = $pdo->prepare("SELECT id FROM `orders` WHERE id = :id AND is_deleted = 0");
        $check_stmt->execute([':id' => $id]);

        if (!$check_stmt->fetch()) {
            echo "<script>alert('존재하지 않는 발주서입니다.'); location.href='index.php';</script>";
            exit;
        }

        $sql = "UPDATE `orders` SET
                order_no = :order_no,
                issue_date = :issue_date,
                customer_id = :customer_id,
                supplier_code = :supplier_code,
                supplier_name = :supplier_name,
                supplier_address = :supplier_address,
                business_type = :business_type,
                business_item = :business_item,
                supplier_phone = :supplier_phone,
                supplier_fax = :supplier_fax,
                contact_name = :contact_name,
                business_registration_number = :business_registration_number,
                phone = :phone,
                fax = :fax,
                project_site = :project_site,
                order_items = :order_items,
                subtotal = :subtotal,
                delivery_date = :delivery_date,
                delivery_location = :delivery_location,
                payment_terms = :payment_terms,
                note = :note,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':order_no' => $order_no ?: null,
            ':issue_date' => $issue_date,
            ':customer_id' => !empty($_POST['customer_id']) ? $_POST['customer_id'] : null,
            ':supplier_code' => $supplier_code ?: null,
            ':supplier_name' => $supplier_name,
            ':supplier_address' => $supplier_address ?: null,
            ':business_type' => $business_type ?: null,
            ':business_item' => $business_item ?: null,
            ':supplier_phone' => $supplier_phone ?: null,
            ':supplier_fax' => $supplier_fax ?: null,
            ':contact_name' => $contact_name ?: null,
            ':business_registration_number' => $business_registration_number ?: null,
            ':phone' => $phone ?: null,
            ':fax' => $fax ?: null,
            ':project_site' => $project_site ?: null,
            ':order_items' => $order_items_json,
            ':subtotal' => $subtotal,
            ':delivery_date' => $delivery_date,
            ':delivery_location' => $delivery_location ?: null,
            ':payment_terms' => $payment_terms ?: null,
            ':note' => $note ?: null,
            ':status' => $status,
            ':id' => $id
        ];

        if ($stmt->execute($params)) {
            debug_log("수정 완료 - ID: " . $id);
            
            if ($is_ajax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => true,
                    'message' => '발주서가 성공적으로 수정되었습니다.',
                    'id' => $id,
                    'redirect_url' => 'write_form.php?id=' . $id
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            echo "<script>
                alert('발주서가 성공적으로 수정되었습니다.');
                location.href='write_form.php?id=" . $id . "';
            </script>";
        } else {
            throw new Exception('발주서 수정 중 오류가 발생했습니다.');
        }

    } else {
        // 새로 추가 작업
        $sql = "INSERT INTO `orders` (
                order_no, issue_date, customer_id, supplier_code, supplier_name, supplier_address,
                business_type, business_item, supplier_phone, supplier_fax, contact_name,
                business_registration_number, phone, fax, project_site, order_items, subtotal, delivery_date, delivery_location,
                payment_terms, note, status, created_at, updated_at, is_deleted
                ) VALUES (
                :order_no, :issue_date, :customer_id, :supplier_code, :supplier_name, :supplier_address,
                :business_type, :business_item, :supplier_phone, :supplier_fax, :contact_name,
                :business_registration_number, :phone, :fax, :project_site, :order_items, :subtotal, :delivery_date, :delivery_location,
                :payment_terms, :note, :status, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0
                )";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':order_no' => $order_no ?: null,
            ':issue_date' => $issue_date,
            ':customer_id' => !empty($_POST['customer_id']) ? $_POST['customer_id'] : null,
            ':supplier_code' => $supplier_code ?: null,
            ':supplier_name' => $supplier_name,
            ':supplier_address' => $supplier_address ?: null,
            ':business_type' => $business_type ?: null,
            ':business_item' => $business_item ?: null,
            ':supplier_phone' => $supplier_phone ?: null,
            ':supplier_fax' => $supplier_fax ?: null,
            ':contact_name' => $contact_name ?: null,
            ':business_registration_number' => $business_registration_number ?: null,
            ':phone' => $phone ?: null,
            ':fax' => $fax ?: null,
            ':project_site' => $project_site ?: null,
            ':order_items' => $order_items_json,
            ':subtotal' => $subtotal,
            ':delivery_date' => $delivery_date,
            ':delivery_location' => $delivery_location ?: null,
            ':payment_terms' => $payment_terms ?: null,
            ':note' => $note ?: null,
            ':status' => $status
        ];

        if ($stmt->execute($params)) {
            $new_id = $pdo->lastInsertId();
            debug_log("등록 완료 - 새 ID: " . $new_id);
            
            // 구매카트 항목이 있으면 eworks 테이블의 cart 컬럼을 1로 업데이트
            debug_log("구매카트 업데이트 체크 - cart_items_param: " . var_export($cart_items_param, true));
            if (!empty($cart_items_param)) {
                try {
                    $item_nums = explode(',', $cart_items_param);
                    $item_nums = array_filter(array_map('intval', $item_nums));
                    debug_log("구매카트 항목 번호 배열: " . var_export($item_nums, true));
                    
                    if (!empty($item_nums)) {
                        $placeholders = implode(',', array_fill(0, count($item_nums), '?'));
                        $update_cart_sql = "UPDATE {$DB}.eworks 
                                            SET cart = 1 
                                            WHERE num IN ({$placeholders}) 
                                            AND is_deleted IS NULL 
                                            AND eworks_item = '원자재구매'";
                        
                        debug_log("구매카트 업데이트 SQL: " . $update_cart_sql);
                        $update_cart_stmt = $pdo->prepare($update_cart_sql);
                        $update_cart_stmt->execute($item_nums);
                        $updated_rows = $update_cart_stmt->rowCount();
                        debug_log("구매카트 업데이트 완료 - 업데이트된 행 수: " . $updated_rows);
                    } else {
                        debug_log("구매카트 항목 번호 배열이 비어있음");
                    }
                } catch (PDOException $e) {
                    debug_log("구매카트 업데이트 오류: " . $e->getMessage());
                    error_log("구매카트 업데이트 오류: " . $e->getMessage());
                    // 구매카트 업데이트 실패해도 발주서 저장은 성공으로 처리
                }
            } else {
                debug_log("구매카트 항목 파라미터가 비어있음");
            }
            
            if ($is_ajax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => true,
                    'message' => '발주서가 성공적으로 등록되었습니다.',
                    'id' => $new_id,
                    'redirect_url' => 'write_form.php?id=' . $new_id
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            echo "<script>
                alert('발주서가 성공적으로 등록되었습니다.');
                location.href='write_form.php?id=" . $new_id . "';
            </script>";
        } else {
            throw new Exception('발주서 등록 중 오류가 발생했습니다.');
        }
    }

} catch (PDOException $e) {
    // 데이터베이스 오류
    debug_log("PDO 오류: " . $e->getMessage());
    error_log("Order Insert/Update Error: " . $e->getMessage());
    
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        
        // 중복 제약 조건 등 특정 오류 처리
        if ($e->getCode() == 23000) {
            echo json_encode([
                'success' => false,
                'message' => '중복된 데이터가 존재합니다.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false,
                'message' => '데이터베이스 오류가 발생했습니다: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    // 중복 제약 조건 등 특정 오류 처리
    if ($e->getCode() == 23000) {
        echo "<script>alert('중복된 데이터가 존재합니다.'); history.back();</script>";
    } else {
        echo "<script>alert('데이터베이스 오류가 발생했습니다.'); history.back();</script>";
    }
    exit;
    
} catch (Exception $e) {
    // 기타 오류
    debug_log("일반 오류: " . $e->getMessage());
    error_log("Order Process Error: " . $e->getMessage());
    
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo "<script>alert('" . addslashes($e->getMessage()) . "'); history.back();</script>";
    exit;
}

debug_log("=== INSERT.PHP 완료 ===");
?>