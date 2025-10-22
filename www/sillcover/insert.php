<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 문지방덮개 데이터 삽입/수정/삭제
 * 
 * 출고/입고 데이터 처리 및 첨부파일 관리
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';

// 테이블명
$tablename = 'sillcover';

// 요청 변수 초기화
$timekey = $_REQUEST["timekey"] ?? '';  // 신규데이터 생성 시 임시저장 키
$mode = $_REQUEST["mode"] ?? '';  // modify, insert, delete
$num = $_REQUEST["num"] ?? '';

// _request.php에서 변수 로드
include '_request.php';

// 검색 태그 생성
$searchtag = $outdate . ' ' . $indate . ' ' . $workplace . ' ' . $comment . ' ' . $first_writer . ' ' . $update_log;

// 수정 모드
if ($mode == "modify") {
    // 업데이트 로그 추가
    $data = date("Y-m-d H:i:s") . " - " . $user_name . "  ";
    $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea
    
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            echo json_encode(array(
                "success" => false,
                "message" => "수정할 데이터를 찾을 수 없습니다."
            ), JSON_UNESCAPED_UNICODE);
            exit;
        }
    } catch (PDOException $Exception) {
        error_log("데이터 조회 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "데이터 조회 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE " . $DB . "." . $tablename . " 
                SET outdate = ?, indate = ?, workplace = ?, comment = ?, 
                    first_writer = ?, update_log = ?, is_deleted = ?, searchtag = ?
                WHERE num = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $outdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $indate, PDO::PARAM_STR);
        $stmh->bindValue(3, $workplace, PDO::PARAM_STR);
        $stmh->bindValue(4, $comment, PDO::PARAM_STR);
        $stmh->bindValue(5, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(6, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(7, $is_deleted, PDO::PARAM_INT);
        $stmh->bindValue(8, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(9, $num, PDO::PARAM_INT);
        
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
if ($mode == "insert") {
    // 작성자 설정
    $first_writer = $user_id;
    
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO " . $DB . "." . $tablename . " (outdate, indate, workplace, comment, 
                                       first_writer, update_log, searchtag)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $outdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $indate, PDO::PARAM_STR);
        $stmh->bindValue(3, $workplace, PDO::PARAM_STR);
        $stmh->bindValue(4, $comment, PDO::PARAM_STR);
        $stmh->bindValue(5, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(6, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(7, $searchtag, PDO::PARAM_STR);
        
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
        $sql = "SELECT * FROM " . $DB . "." . $tablename . " ORDER BY num DESC LIMIT 1";
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
    
    // 신규 데이터인 경우 첨부파일/이미지의 parentnum 업데이트
    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . ".picuploads set parentnum = ? where parentnum = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("첨부파일 parentnum 업데이트 오류: " . $Exception->getMessage());
        // 첨부파일 업데이트 실패는 치명적이지 않으므로 계속 진행
    }
}

// 삭제 모드
if ($mode == "delete") {
    // 1단계: Soft Delete (is_deleted = 1 설정)
    try {
        $pdo->beginTransaction();
        $query = "UPDATE " . $DB . "." . $tablename . " SET is_deleted = 1 WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Soft Delete 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "데이터 삭제 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 2단계: 첨부파일 삭제
    try {
        $pdo->beginTransaction();
        $sql1 = "delete from " . $DB . ".picuploads where parentnum = ? and tablename = ?";
        $stmh1 = $pdo->prepare($sql1);
        $stmh1->bindValue(1, $num, PDO::PARAM_INT);
        $stmh1->bindValue(2, $tablename, PDO::PARAM_STR);
        $stmh1->execute();
        $pdo->commit();
    } catch (Exception $ex) {
        $pdo->rollBack();
        error_log("첨부파일 삭제 오류: " . $ex->getMessage());
        // 첨부파일 삭제 실패는 치명적이지 않으므로 계속 진행
    }
}

// 성공 응답
$data = array(
    "success" => true,
    "num" => $num,
    "message" => $mode == "modify" ? "데이터가 성공적으로 수정되었습니다." : 
                ($mode == "delete" ? "데이터가 성공적으로 삭제되었습니다." : "데이터가 성공적으로 추가되었습니다.")
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>

