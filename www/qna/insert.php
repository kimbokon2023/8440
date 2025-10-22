<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * QNA 게시글 등록/수정 처리
 * 
 * 게시글 신규 등록 또는 수정 처리
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_nick = $_SESSION["nick"] ?? '';

// 요청 변수 초기화
$timekey = $_REQUEST["timekey"] ?? '';   // 신규데이터 생성시 임시저장키
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$tablename = $_REQUEST["tablename"] ?? 'qna';
$id = $_REQUEST["id"] ?? '';
$num = $_REQUEST["num"] ?? '';
$is_html = $_REQUEST["is_html"] ?? '';
$noticecheck = $_REQUEST["noticecheck"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';
$searchtext = $_REQUEST["searchtext"] ?? '';

// 필수 데이터 체크
if (empty($subject) || empty($content)) {
    echo json_encode(array(
        "success" => false,
        "message" => "제목과 내용은 필수 입력 항목입니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}


// 수정 모드
if ($mode == "modify") {
    // 기존 데이터 조회
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            echo json_encode(array(
                "success" => false,
                "message" => "수정할 게시글을 찾을 수 없습니다."
            ), JSON_UNESCAPED_UNICODE);
            exit;
        }
    } catch (PDOException $Exception) {
        error_log("게시글 조회 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "게시글 조회 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 게시글 수정
    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . "." . $tablename . " set subject = ?, content = ?, is_html = ?, searchtext = ? where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $searchtext, PDO::PARAM_STR);
        $stmh->bindValue(5, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("게시글 수정 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "게시글 수정 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    // 신규 등록 모드
    
    // HTML 컨텐츠 이스케이프 처리
    if ($is_html == "y") {
        $content = htmlspecialchars($content);
    }
    
    try {
        $pdo->beginTransaction();
        $sql = "insert into " . $DB . "." . $tablename . " (id, name, nick, subject, content, regist_day, hit, is_html, searchtext) ";
        $sql .= "values(?, ?, ?, ?, ?, now(), 0, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmh->bindValue(2, $user_name, PDO::PARAM_STR);
        $stmh->bindValue(3, $user_nick, PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $searchtext, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("게시글 등록 오류: " . $Exception->getMessage());
        echo json_encode(array(
            "success" => false,
            "message" => "게시글 등록 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 신규 등록인 경우 생성된 num 추출
if ($mode !== "modify") {
    // 최신 등록된 게시글 번호 추출
    $sql = "select * from " . $DB . "." . $tablename . " order by num desc limit 1";
    
    try {
        $stmh = $pdo->query($sql);
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $num = $row["num"] ?? '';
        }
    } catch (PDOException $Exception) {
        error_log("최신 게시글 조회 오류: " . $Exception->getMessage());
    }
    
    // 신규 데이터인 경우 첨부파일/첨부이미지의 parentnum을 실제 num으로 변경
    if (!empty($timekey)) {
        $id = $num;
        
        try {
            $pdo->beginTransaction();
            $sql = "update " . $DB . ".picuploads set parentnum = ? where parentnum = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $id, PDO::PARAM_INT);
            $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            error_log("첨부파일 parentnum 업데이트 오류: " . $Exception->getMessage());
        }
    }
}

// 성공 응답
$data = array(
    'success' => true,
    'num' => $num,
    'tablename' => $tablename,
    'message' => $mode == "modify" ? "게시글이 수정되었습니다." : "게시글이 등록되었습니다."
);

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
