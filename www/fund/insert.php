<?php
/**
 * Fund 레코드 등록/수정 처리 스크립트
 * fund 테이블의 레코드를 등록하거나 수정합니다.
 */

 require_once __DIR__ . '/../bootstrap.php';
// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// JSON 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 파라미터 초기화
$which = $_REQUEST["which"] ?? '';
$search_opt = $_REQUEST["search_opt"] ?? '';
$displaySelect = $_REQUEST["displaySelect"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$proDate = $_REQUEST["proDate"] ?? '';
$writer = $_REQUEST["writer"] ?? '';
$amount = $_REQUEST["amount"] ?? '';
$memo = $_REQUEST["memo"] ?? '';

// 변수 초기화
$success = false;
$message = '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        // 기존 레코드 조회
        $sql = "SELECT * FROM {$DB}.fund WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            throw new Exception("해당 레코드를 찾을 수 없습니다.");
        }
        
    } catch (PDOException $ex) {
        error_log("DB select error in fund/insert.php: " . $ex->getMessage());
        echo json_encode(array(
            'success' => false,
            'message' => '데이터 조회 오류',
            'num' => $num
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        // 레코드 업데이트
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.fund SET proDate = ?, writer = ?, amount = ?, memo = ?, which = ? WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $proDate, PDO::PARAM_STR);
        $stmh->bindValue(2, $writer, PDO::PARAM_STR);
        $stmh->bindValue(3, $amount, PDO::PARAM_STR);
        $stmh->bindValue(4, $memo, PDO::PARAM_STR);
        $stmh->bindValue(5, $which, PDO::PARAM_STR);
        $stmh->bindValue(6, $num, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
        $success = true;
        $message = '레코드가 성공적으로 수정되었습니다.';
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("DB update error in fund/insert.php: " . $ex->getMessage());
        $success = false;
        $message = '데이터 수정 오류';
    }
    
} else {
    // 신규 등록 모드
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.fund (proDate, writer, amount, memo, which) VALUES (?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $proDate, PDO::PARAM_STR);
        $stmh->bindValue(2, $writer, PDO::PARAM_STR);
        $stmh->bindValue(3, $amount, PDO::PARAM_STR);
        $stmh->bindValue(4, $memo, PDO::PARAM_STR);
        $stmh->bindValue(5, $which, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
        // 신규 데이터인 경우 num 추출
        $sql = "SELECT * FROM {$DB}.fund ORDER BY num DESC LIMIT 1";
        
        try {
            $stmh = $pdo->query($sql);
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $num = $row["num"];
            }
            
        } catch (PDOException $ex) {
            error_log("DB select error in fund/insert.php: " . $ex->getMessage());
        }
        
        $success = true;
        $message = '레코드가 성공적으로 등록되었습니다.';
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("DB insert error in fund/insert.php: " . $ex->getMessage());
        $success = false;
        $message = '데이터 등록 오류';
    }
}

// JSON 응답 생성
$data = array(
    'success' => $success,
    'message' => $message,
    'num' => $num
);

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>
