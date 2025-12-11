<?php
/**
 * Holiday 휴무일 처리 페이지
 * 휴무일 등록/수정/삭제를 처리합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
require_once(includePath('session.php'));

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 파라미터 초기화
$mode = $_REQUEST['mode'] ?? '';
$tablename = 'holiday';

// 변수 초기화
$success = false;
$message = '';
$num = '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// _request.php에서 사용될 변수들 사전 초기화
$registedate = '';
$comment = '';
$is_deleted = '';
$searchtag = '';
$update_log = '';
$startdate = '';
$enddate = '';
$periodcheck = '0';

// 요청 파라미터 로드
include "_request.php";

// 입력 검증
if (empty($startdate) && ($mode === 'update' || $mode === 'insert' || empty($mode))) {
    echo json_encode([
        'success' => false,
        'message' => '시작일을 입력해주세요.',
        'num' => $num,
        'mode' => $mode
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 검색 태그 생성 (등록일자와 코멘트를 조합)
$searchtag = trim($startdate . ' ' . $enddate . ' ' . $comment);

// 업데이트 로그 생성
$current_log = date("Y-m-d H:i:s") . " - " . $user_name;
$update_log = $current_log . " " . $update_log . "&#10";

// 수정 모드
if ($mode === "update") {
    if (empty($num)) {
        echo json_encode([
            'success' => false,
            'message' => '잘못된 접근입니다.',
            'num' => $num,
            'mode' => $mode
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} SET ";
        $sql .= "registedate = ?, startdate = ?, enddate = ?, periodcheck = ?, ";
        $sql .= "comment = ?, searchtag = ?, update_log = ? ";
        $sql .= "WHERE num = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $startdate, PDO::PARAM_STR);
        $stmh->bindValue(3, $enddate, PDO::PARAM_STR);
        $stmh->bindValue(4, $periodcheck, PDO::PARAM_INT);
        $stmh->bindValue(5, $comment, PDO::PARAM_STR);
        $stmh->bindValue(6, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(7, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(8, $num, PDO::PARAM_INT);
        
        $stmh->execute();
        $rowCount = $stmh->rowCount();
        
        $pdo->commit();
        
        if ($rowCount > 0) {
            $success = true;
            $message = '수정되었습니다.';
        } else {
            $success = false;
            $message = '수정할 내용이 없거나 레코드를 찾을 수 없습니다.';
        }
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Update error in holiday/process.php: " . $ex->getMessage());
        
        $success = false;
        $message = '수정 중 오류가 발생했습니다.';
    }
}

// 신규 등록 모드
if ($mode === "insert" || empty($mode)) {
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.{$tablename} (";
        $sql .= "registedate, startdate, enddate, periodcheck, comment, searchtag, update_log";
        $sql .= ") VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $registedate, PDO::PARAM_STR);
        $stmh->bindValue(2, $startdate, PDO::PARAM_STR);
        $stmh->bindValue(3, $enddate, PDO::PARAM_STR);
        $stmh->bindValue(4, $periodcheck, PDO::PARAM_INT);
        $stmh->bindValue(5, $comment, PDO::PARAM_STR);
        $stmh->bindValue(6, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(7, $update_log, PDO::PARAM_STR);
        
        $stmh->execute();
        $num = $pdo->lastInsertId();
        
        $pdo->commit();
        
        $success = true;
        $message = '등록되었습니다.';
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Insert error in holiday/process.php: " . $ex->getMessage());
        
        $success = false;
        $message = '등록 중 오류가 발생했습니다.';
    }
}

// 삭제 모드 (Soft Delete)
if ($mode === "delete") {
    if (empty($num)) {
        echo json_encode([
            'success' => false,
            'message' => '잘못된 접근입니다.',
            'num' => $num,
            'mode' => $mode
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} SET is_deleted = 1 WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        
        $rowCount = $stmh->rowCount();
        
        $pdo->commit();
        
        if ($rowCount > 0) {
            $success = true;
            $message = '삭제되었습니다.';
        } else {
            $success = false;
            $message = '삭제할 레코드를 찾을 수 없습니다.';
        }
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Delete error in holiday/process.php: " . $ex->getMessage());
        
        $success = false;
        $message = '삭제 중 오류가 발생했습니다.';
    }
}

// JSON 응답 생성
$response = [
    'success' => $success,
    'message' => $message,
    'num' => $num,
    'mode' => $mode
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
