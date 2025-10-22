<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 원자재 구매 요청 → 원자재(철판) 재고 이관 처리
 * 
 * 1. 요청 데이터의 상태를 '입고완료'로 변경
 * 2. 원자재 재고 테이블에 새 레코드 추가
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$update_log = $_REQUEST["update_log"] ?? '';

// 필수 데이터 체크
if (empty($num)) {
    echo json_encode(array(
        "success" => false,
        "message" => "처리할 데이터 번호가 없습니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 입고 완료 상태 설정
$which = '3';
$inventory = '이관';
$indate = date("Y-m-d");   // 현재일자
$today = date("Y-m-d H:i:s") . " - " . $user_name . "  ";
$update_log = $today . $update_log . "&#10";  // 개행문자 Textarea     		


// 1단계: 요청 데이터 입고 완료 처리
try {
    $pdo->beginTransaction();

    $sql = "update " . $DB . ".eworks set which = ?, indate = ?, inventory = ?, update_log = ? where num = ? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $which, PDO::PARAM_STR);
    $stmh->bindValue(2, $indate, PDO::PARAM_STR);
    $stmh->bindValue(3, $inventory, PDO::PARAM_STR);
    $stmh->bindValue(4, $update_log, PDO::PARAM_STR);
    $stmh->bindValue(5, $num, PDO::PARAM_INT);

    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    
    error_log("입고 완료 처리 오류: " . $Exception->getMessage());
    
    echo json_encode(array(
        "success" => false,
        "message" => "입고 완료 처리 중 오류가 발생했습니다.",
        "error" => $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 2단계: 요청 자료를 읽어서 원자재 재고에 이관
try {
    $sql = "select * from " . $DB . ".eworks where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    $count = $stmh->rowCount();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);

    // 데이터가 없으면 종료
    if (!$row) {
        echo json_encode(array(
            "success" => false,
            "message" => "요청 데이터를 찾을 수 없습니다."
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 현재일자
    $nowday = date("Y-m-d");

    // 이관할 데이터 추출
    $num = $row["num"] ?? '';
    $outdate = $nowday;
    $indate = $nowday;
    $outworkplace = $row["outworkplace"] ?? '';
    $steel_item = $row["steel_item"] ?? '';
    $spec = $row["spec"] ?? '';
    $steelnum = $row["steelnum"] ?? '';
    $company = $row["company"] ?? '';
    $request_comment = $row["request_comment"] ?? '';
    $which = '1';  // 원자재 재고 상태
    $model = $row["model"] ?? '';
    $supplier = $row["supplier"] ?? '';
} catch (PDOException $Exception) {
    error_log("요청 데이터 조회 오류: " . $Exception->getMessage());
    
    echo json_encode(array(
        "success" => false,
        "message" => "요청 데이터 조회 중 오류가 발생했습니다.",
        "error" => $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}


// 3단계: 원자재 재고 테이블에 데이터 신규 등록
try {
    $pdo->beginTransaction();

    // 최초 등록자 기록
    $first_writer = $user_name . " _" . date("Y-m-d H:i:s");

    $sql = "insert into " . $DB . ".steel(";
    $sql .= "which, outdate, indate, outworkplace, item, spec, steelnum, company, comment, model, first_writer, supplier";
    $sql .= ") ";
    $sql .= "values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $which, PDO::PARAM_STR);
    $stmh->bindValue(2, $outdate, PDO::PARAM_STR);
    $stmh->bindValue(3, $indate, PDO::PARAM_STR);
    $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);
    $stmh->bindValue(5, $steel_item, PDO::PARAM_STR);
    $stmh->bindValue(6, $spec, PDO::PARAM_STR);
    $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);
    $stmh->bindValue(8, $company, PDO::PARAM_STR);
    $stmh->bindValue(9, $request_comment, PDO::PARAM_STR);
    $stmh->bindValue(10, $model, PDO::PARAM_STR);
    $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);
    $stmh->bindValue(12, $supplier, PDO::PARAM_STR);

    $stmh->execute();
    $pdo->commit();

    // 성공 응답
    echo json_encode(array(
        "success" => true,
        "num" => $num,
        "message" => "원자재 재고로 이관이 완료되었습니다."
    ), JSON_UNESCAPED_UNICODE);
} catch (PDOException $Exception) {
    $pdo->rollBack();
    
    // 에러 로그 기록
    error_log("원자재 재고 이관 오류: " . $Exception->getMessage());
    
    // 에러 응답
    echo json_encode(array(
        "success" => false,
        "message" => "원자재 재고 이관 중 오류가 발생했습니다.",
        "error" => $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}
?>
