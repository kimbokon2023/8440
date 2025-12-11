<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// JSON 헤더 설정
header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'num' => $num
);

// 변수 초기화
$which = '3';
$indate = date("Y-m-d");   // 현재일자 변수지정
$nowday = date("Y-m-d");

// 변수 초기화 (request 테이블에서 가져올 데이터)
$outdate = '';
$outworkplace = '';
$item = '';
$spec = '';
$steelnum = '';
$company = '';
$comment = '';
$model = '';
$supplier = '';
$first_writer = '';

// Step 1: 당일 입고완료처리
try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE {$DB}.request SET which = ?, indate = ? WHERE num = ? LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $which, PDO::PARAM_STR);
    $stmh->bindValue(2, $indate, PDO::PARAM_STR);
    $stmh->bindValue(3, $num, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("입고완료 처리 오류: " . $ex->getMessage());
    
    $response['message'] = '입고완료 처리 중 오류가 발생했습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// Step 2: 요청자료를 읽어서 원자재에 이관함
try {
    $sql = "SELECT * FROM {$DB}.request WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($count > 0) {
        $num = $row["num"];
        $outdate = $nowday;
        $indate = $nowday;
        $outworkplace = $row["outworkplace"];
        $item = $row["item"];
        $spec = $row["spec"];
        $steelnum = $row["steelnum"];
        $company = $row["company"];
        $comment = $row["comment"];
        $which = '1';
        $model = $row["model"];
        $supplier = $row["supplier"];
    } else {
        throw new Exception("요청 데이터를 찾을 수 없습니다. num: " . $num);
    }
} catch (PDOException $ex) {
    error_log("요청 데이터 조회 오류: " . $ex->getMessage());
    
    $response['message'] = '요청 데이터 조회 중 오류가 발생했습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
} catch (Exception $ex) {
    error_log($ex->getMessage());
    
    $response['message'] = $ex->getMessage();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// Step 3: 원자재 입고처리 데이터 신규 등록하는 구간
try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO {$DB}.steel 
            (which, outdate, indate, outworkplace, item, spec, steelnum, company, comment, 
             model, first_writer, supplier) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // first writer 기록
    $first_writer = $user_name . " _" . date("Y-m-d H:i:s");
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $which, PDO::PARAM_STR);
    $stmh->bindValue(2, $outdate, PDO::PARAM_STR);
    $stmh->bindValue(3, $indate, PDO::PARAM_STR);
    $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);
    $stmh->bindValue(5, $item, PDO::PARAM_STR);
    $stmh->bindValue(6, $spec, PDO::PARAM_STR);
    $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);
    $stmh->bindValue(8, $company, PDO::PARAM_STR);
    $stmh->bindValue(9, $comment, PDO::PARAM_STR);
    $stmh->bindValue(10, $model, PDO::PARAM_STR);
    $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);
    $stmh->bindValue(12, $supplier, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
    
    // 성공 응답
    $response['success'] = true;
    $response['message'] = '원자재 입고가 완료되었습니다.';
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("원자재 입고 등록 오류: " . $ex->getMessage());
    
    $response['message'] = '원자재 입고 등록 중 오류가 발생했습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>