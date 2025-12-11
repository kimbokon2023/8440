<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 개별로 신청하는 것에 대한 DB처리 구간

// JSON 헤더 설정
header("Content-Type: application/json");

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$name = $_REQUEST["name"] ?? '';
$part = $_REQUEST["part"] ?? '';
$state = $_REQUEST["state"] ?? '';
$registdate = $_REQUEST["registdate"] ?? '';
$item = $_REQUEST["item"] ?? '';
$askdatefrom = $_REQUEST["askdatefrom"] ?? '';
$askdateto = $_REQUEST["askdateto"] ?? '';
$usedday = $_REQUEST["usedday"] ?? '';
$content = $_REQUEST["content"] ?? '';
$labor_name = $_REQUEST["labor_name"] ?? '';

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'registdate' => $registdate,
    'state' => $state
);

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        // 기존 데이터 조회
        $sql = "SELECT * FROM {$DB}.daylaborer WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            $response['message'] = '수정할 데이터를 찾을 수 없습니다.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
    } catch (PDOException $ex) {
        error_log("일용직 데이터 조회 오류: " . $ex->getMessage());
        $response['message'] = '데이터 조회 중 오류가 발생했습니다.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.daylaborer 
                SET id = ?, 
                    name = ?, 
                    registdate = ?, 
                    item = ?, 
                    askdatefrom = ?, 
                    askdateto = ?, 
                    usedday = ?, 
                    content = ?, 
                    state = ?, 
                    part = ?, 
                    labor_name = ? 
                WHERE num = ? 
                LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->bindValue(2, $name, PDO::PARAM_STR);
        $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
        $stmh->bindValue(4, $item, PDO::PARAM_STR);
        $stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);
        $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);
        $stmh->bindValue(7, $usedday, PDO::PARAM_STR);
        $stmh->bindValue(8, $content, PDO::PARAM_STR);
        $stmh->bindValue(9, $state, PDO::PARAM_STR);
        $stmh->bindValue(10, $part, PDO::PARAM_STR);
        $stmh->bindValue(11, $labor_name, PDO::PARAM_STR);
        $stmh->bindValue(12, $num, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
        
        $response['success'] = true;
        $response['message'] = '수정되었습니다.';
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("일용직 데이터 수정 오류: " . $ex->getMessage());
        $response['message'] = '수정 중 오류가 발생했습니다.';
    }
}

// 신규 등록 모드
if ($mode == "insert") {
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.daylaborer 
                (id, name, registdate, item, askdatefrom, askdateto, usedday, content, state, part, labor_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->bindValue(2, $name, PDO::PARAM_STR);
        $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
        $stmh->bindValue(4, $item, PDO::PARAM_STR);
        $stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);
        $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);
        $stmh->bindValue(7, $usedday, PDO::PARAM_STR);
        $stmh->bindValue(8, $content, PDO::PARAM_STR);
        $stmh->bindValue(9, $state, PDO::PARAM_STR);
        $stmh->bindValue(10, $part, PDO::PARAM_STR);
        $stmh->bindValue(11, $labor_name, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
        
        $response['success'] = true;
        $response['message'] = '등록되었습니다.';
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("일용직 데이터 등록 오류: " . $ex->getMessage());
        $response['message'] = '등록 중 오류가 발생했습니다.';
    }
}

// 삭제 모드
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();
        
        $sql = "DELETE FROM {$DB}.daylaborer WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
        
        $response['success'] = true;
        $response['message'] = '삭제되었습니다.';
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("일용직 데이터 삭제 오류: " . $ex->getMessage());
        $response['message'] = '삭제 중 오류가 발생했습니다.';
    }
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>