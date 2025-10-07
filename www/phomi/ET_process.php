<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

// 디버그 로그 시작
error_log("=== ET_process.php 시작 ===");
error_log("REQUEST 데이터: " . print_r($_REQUEST, true));

// GET 파라미터 처리
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? 'phomi_estimate';

error_log("Mode: $mode, Num: $num, Tablename: $tablename");

// POST 데이터 처리
$quote_date = $_REQUEST["quote_date"] ?? date('Y-m-d');
$recipient = $_REQUEST["recipient"] ?? '';
$division = $_REQUEST["division"] ?? '';
$site_name = $_REQUEST["site_name"] ?? '';
$head_office_address = $_REQUEST["head_office_address"] ?? '경기도 김포시 양촌읍 흥신로 220-27';
$showroom_address = $_REQUEST["showroom_address"] ?? '인천광역시 서구 중봉대로 393번길 16 홈씨씨2층 포미스톤';
$signer = $_REQUEST["signer"] ?? '소현철';
$total_ex_vat = $_REQUEST["total_ex_vat"] ?? 0;
$total_inc_vat = $_REQUEST["total_inc_vat"] ?? 0;
$payment_account = $_REQUEST["payment_account"] ?? '중소기업은행 339-084210-01-012 ㈜ 미래기업';
$author_id = $_REQUEST["author_id"] ?? $_SESSION["userid"];
$author = $_REQUEST["author"] ?? $_SESSION["name"];
$recipient_phone = $_REQUEST["recipient_phone"] ?? '';

// JSON 데이터 처리
$items_json = $_REQUEST["items_json"] ?? '[]';
$other_costs_json = $_REQUEST["other_costs_json"] ?? '[]';
$notices_json = $_REQUEST["notices_json"] ?? '[]';

// 시공비 제외 체크박스 처리
$exclude_construction_cost = $_REQUEST["exclude_construction_cost"] ?? '0';
// 몰딩 제외 체크박스 처리
$exclude_molding = $_REQUEST["exclude_molding"] ?? '0';
// 기타비용 자동산출 체크박스 처리
$etc_autocheck = $_REQUEST["etc_autocheck"] ?? '0';

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

// 현재 시간
$current_time = date('Y-m-d H:i:s');

// 사용자명 가져오기
$user_name = $_SESSION['user_name'] ?? 'Unknown';

// update_log 처리 함수
function updateLog($pdo, $num, $user_name, $current_time, $mode) {
    global $DB;
    
    $new_log_entry = $user_name . " - " . $current_time . " (" . $mode . ")";
    
    if ($mode == 'insert') {
        return $new_log_entry;
    } else {
        // 기존 로그 가져오기
        $sql = "SELECT update_log FROM {$DB}.phomi_estimate WHERE num = :num";
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

error_log("데이터베이스 연결 완료");

if ($mode == "modify") {
    // 수정 모드
    error_log("=== 수정 모드 시작 ===");
    try {
        $pdo->beginTransaction();
        
        // update_log 업데이트
        $update_log = updateLog($pdo, $num, $user_name, $current_time, 'modify');
        
        $sql = "UPDATE {$DB}.phomi_estimate SET 
                quote_date = ?, recipient = ?, division = ?, site_name = ?, 
                head_office_address = ?, showroom_address = ?, signer = ?, 
                total_ex_vat = ?, total_inc_vat = ?, payment_account = ?,
                items = ?, other_costs = ?, notices = ?, 
                exclude_construction_cost = ?, exclude_molding = ?,
                etc_autocheck = ?, author_id = ?, author = ?, recipient_phone = ?,
                update_log = ?, updatedAt = NOW() 
                WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $quote_date, PDO::PARAM_STR);
        $stmh->bindValue(2, $recipient, PDO::PARAM_STR);
        $stmh->bindValue(3, $division, PDO::PARAM_STR);
        $stmh->bindValue(4, $site_name, PDO::PARAM_STR);
        $stmh->bindValue(5, $head_office_address, PDO::PARAM_STR);
        $stmh->bindValue(6, $showroom_address, PDO::PARAM_STR);
        $stmh->bindValue(7, $signer, PDO::PARAM_STR);
        $stmh->bindValue(8, $total_ex_vat, PDO::PARAM_INT);
        $stmh->bindValue(9, $total_inc_vat, PDO::PARAM_INT);
        $stmh->bindValue(10, $payment_account, PDO::PARAM_STR);
        $stmh->bindValue(11, $items_json, PDO::PARAM_STR);
        $stmh->bindValue(12, $other_costs_json, PDO::PARAM_STR);
        $stmh->bindValue(13, $notices_json, PDO::PARAM_STR);
        $stmh->bindValue(14, $exclude_construction_cost, PDO::PARAM_INT);
        $stmh->bindValue(15, $exclude_molding, PDO::PARAM_INT);
        $stmh->bindValue(16, $etc_autocheck, PDO::PARAM_INT);
        $stmh->bindValue(17, $author_id, PDO::PARAM_STR);
        $stmh->bindValue(18, $author, PDO::PARAM_STR);
        $stmh->bindValue(19, $recipient_phone, PDO::PARAM_STR);
        $stmh->bindValue(20, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(21, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit();
        
        error_log("수정 성공 - Num: $num");
        $result = "success";
        $message = "견적서가 성공적으로 수정되었습니다.";
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("수정 실패 - 오류: " . $Exception->getMessage());
        $result = "error";
        $message = "오류: " . $Exception->getMessage();
    }

} 
elseif ($mode == "delete") {
    // 삭제 모드 (실제 삭제하지 않고 is_deleted 플래그만 설정)
    error_log("=== 삭제 모드 시작 ===");
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE {$DB}.phomi_estimate SET is_deleted = 'Y', updatedAt = NOW() WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit();
        
        error_log("삭제 성공 - Num: $num");
        $result = "success";
        $message = "견적서가 성공적으로 삭제되었습니다.";
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("삭제 실패 - 오류: " . $Exception->getMessage());
        $result = "error";
        $message = "오류: " . $Exception->getMessage();
    }

} else {
    // 신규 등록 모드
    error_log("=== 신규 등록 모드 시작 ===");
    try {
        $pdo->beginTransaction();
        
        // update_log 생성
        $update_log = updateLog($pdo, null, $user_name, $current_time, 'insert');
        
        $sql = "INSERT INTO {$DB}.phomi_estimate 
                (quote_date, recipient, division, site_name, head_office_address, 
                showroom_address, signer, total_ex_vat, total_inc_vat, payment_account,
                items, other_costs, notices, exclude_construction_cost, exclude_molding, 
                etc_autocheck, author_id, author, recipient_phone, update_log, createdAt, updatedAt) 
                VALUES (?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?,
                        NOW(), NOW())";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $quote_date, PDO::PARAM_STR);
        $stmh->bindValue(2, $recipient, PDO::PARAM_STR);
        $stmh->bindValue(3, $division, PDO::PARAM_STR);
        $stmh->bindValue(4, $site_name, PDO::PARAM_STR);
        $stmh->bindValue(5, $head_office_address, PDO::PARAM_STR);
        $stmh->bindValue(6, $showroom_address, PDO::PARAM_STR);
        $stmh->bindValue(7, $signer, PDO::PARAM_STR);
        $stmh->bindValue(8, $total_ex_vat, PDO::PARAM_INT);
        $stmh->bindValue(9, $total_inc_vat, PDO::PARAM_INT);
        $stmh->bindValue(10, $payment_account, PDO::PARAM_STR);
        $stmh->bindValue(11, $items_json, PDO::PARAM_STR);
        $stmh->bindValue(12, $other_costs_json, PDO::PARAM_STR);
        $stmh->bindValue(13, $notices_json, PDO::PARAM_STR);
        $stmh->bindValue(14, $exclude_construction_cost, PDO::PARAM_INT);
        $stmh->bindValue(15, $exclude_molding, PDO::PARAM_INT);
        $stmh->bindValue(16, $etc_autocheck, PDO::PARAM_INT);
        $stmh->bindValue(17, $author_id, PDO::PARAM_STR);
        $stmh->bindValue(18, $author, PDO::PARAM_STR);
        $stmh->bindValue(19, $recipient_phone, PDO::PARAM_STR);
        $stmh->bindValue(20, $update_log, PDO::PARAM_STR);
        $stmh->execute();
        
        // 새로 생성된 num 가져오기
        $num = $pdo->lastInsertId();
        $pdo->commit();
        
        error_log("신규 등록 성공 - Num: $num");
        $result = "success";
        $message = "견적서가 성공적으로 저장되었습니다.";
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("신규 등록 실패 - 오류: " . $Exception->getMessage());
        $result = "error";
        $message = "오류: " . $Exception->getMessage();
    }
}

$data = [
    'result' => $result,
    'message' => $message,
    'num' => $num,
    'tablename' => $tablename
];

error_log("응답 데이터: " . json_encode($data, JSON_UNESCAPED_UNICODE));
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?> 