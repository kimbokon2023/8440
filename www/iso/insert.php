<?php
/**
 * ISO 입력/수정 처리 페이지
 * ISO 게시글을 등록하거나 수정합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
require_once getDocumentRoot() . '/session.php';

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_nick = $_SESSION["nick"] ?? '';

// 로그인 확인
if (empty($user_id)) {
    echo json_encode(array(
        'success' => false,
        'message' => '로그인이 필요합니다.',
        'num' => ''
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 요청 파라미터 초기화
$timekey = $_REQUEST["timekey"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';
$is_html = $_REQUEST["is_html"] ?? '';

// 변수 초기화
$success = false;
$message = '';
$id = '';

// 입력 검증
if (empty($tablename)) {
    echo json_encode(array(
        'success' => false,
        'message' => '잘못된 접근입니다. (tablename 누락)',
        'num' => $num
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($subject)) {
    echo json_encode(array(
        'success' => false,
        'message' => '제목을 입력해주세요.',
        'num' => $num
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($content)) {
    echo json_encode(array(
        'success' => false,
        'message' => '내용을 입력해주세요.',
        'num' => $num
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode === "modify") {
    if (empty($num)) {
        echo json_encode(array(
            'success' => false,
            'message' => '잘못된 접근입니다. (num 누락)',
            'num' => $num
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        // 기존 레코드 확인
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            echo json_encode(array(
                'success' => false,
                'message' => '수정할 레코드를 찾을 수 없습니다.',
                'num' => $num
            ), JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 수정 처리
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} SET subject = ?, content = ?, is_html = ? WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $num, PDO::PARAM_STR);
        
        $stmh->execute();
        $rowCount = $stmh->rowCount();
        
        $pdo->commit();
        
        if ($rowCount > 0) {
            $success = true;
            $message = '수정되었습니다.';
        } else {
            $success = false;
            $message = '수정할 내용이 없습니다.';
        }
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Update error in iso/insert.php: " . $ex->getMessage());
        
        echo json_encode(array(
            'success' => false,
            'message' => '수정 중 오류가 발생했습니다.',
            'num' => $num
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
} else {
    // 신규 등록 모드
    
    // HTML이 아닌 경우 XSS 방지
    if ($is_html !== "y") {
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    }
    
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.{$tablename} (id, name, nick, subject, content, regist_day, hit, is_html) ";
        $sql .= "VALUES (?, ?, ?, ?, ?, NOW(), 0, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmh->bindValue(2, $user_name, PDO::PARAM_STR);
        $stmh->bindValue(3, $user_nick, PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        
        $stmh->execute();
        $num = $pdo->lastInsertId();
        
        $pdo->commit();
        
        $success = true;
        $message = '등록되었습니다.';
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Insert error in iso/insert.php: " . $ex->getMessage());
        
        echo json_encode(array(
            'success' => false,
            'message' => '등록 중 오류가 발생했습니다.',
            'num' => ''
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 신규 등록 시 첨부파일 parentnum 업데이트
    if (!empty($timekey) && !empty($num)) {
        try {
            $pdo->beginTransaction();
            
            $sql = "UPDATE {$DB}.picuploads SET parentnum = ? WHERE parentnum = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $num, PDO::PARAM_STR);
            $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
            $stmh->execute();
            
            $updatedPictures = $stmh->rowCount();
            
            $pdo->commit();
            
            if ($updatedPictures > 0) {
                $message .= " (첨부파일 {$updatedPictures}개 연결됨)";
            }
            
        } catch (PDOException $ex) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Picture update error in iso/insert.php: " . $ex->getMessage());
            // 첨부파일 업데이트 실패는 경고만 (본문 등록은 성공)
        }
    }
}

// JSON 응답 생성
$response = array(
    'success' => $success,
    'message' => $message,
    'num' => $num,
    'tablename' => $tablename
);

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
