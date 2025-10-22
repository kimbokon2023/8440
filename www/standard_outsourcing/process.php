<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 사급업체 데이터 처리
 * 
 * 업체 데이터 삽입/수정/삭제 처리
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';

// 테이블명
$tablename = "steelcompany";

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$SelectWork = $_REQUEST["SelectWork"] ?? '';
$company = $_REQUEST["company"] ?? '';

// num이 0이면 insert로 처리
if ((int)$num == 0) {
    $SelectWork = "insert";
}

include "_request.php";

// 수정 모드
if ($SelectWork == "update") {
    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . "." . $tablename . " set company = ? where num = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $company, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_INT);
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("데이터 수정 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "데이터 수정 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
}


// 신규 작성 모드
if ($SelectWork == "insert") {
    // 데이터 신규 등록하는 구간
    try {
        $pdo->beginTransaction();
        
        $sql = "insert into " . $DB . "." . $tablename . " (company) values(?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $company, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("데이터 삽입 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "데이터 삽입 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 새로 삽입된 레코드의 num 추출
    try {
        $sql = "select * from " . $DB . "." . $tablename . " order by num desc limit 1";
        $stmh = $pdo->prepare($sql);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $num = $row["num"];
        }
    } catch (PDOException $Exception) {
        error_log("num 추출 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "데이터 번호 추출 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 삭제 모드
if ($SelectWork == "delete") {
    try {
        $pdo->beginTransaction();
        $sql = "delete from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit();
    } catch (Exception $ex) {
        $pdo->rollBack();
        error_log("데이터 삭제 오류: " . $ex->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "데이터 삭제 중 오류가 발생했습니다.",
            "error" => $ex->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 성공 응답
$data = array(
    "success" => true,
    "num" => $num,
    "message" => $SelectWork == "update" ? "데이터가 성공적으로 수정되었습니다." : 
                ($SelectWork == "delete" ? "데이터가 성공적으로 삭제되었습니다." : "데이터가 성공적으로 추가되었습니다.")
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
